<?php

namespace Crm\Admin\Http\Controllers\WhatsApp;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Crm\Admin\Http\Controllers\Controller;
use Crm\WhatsApp\Jobs\SendWhatsappCampaignMessageJob;
use Crm\WhatsApp\Models\WhatsappCampaign;
use Crm\WhatsApp\Models\WhatsappCampaignRecipient;
use Crm\WhatsApp\Models\WhatsappDoNotContact;
use Crm\WhatsApp\Repositories\WhatsappCampaignRecipientRepository;
use Crm\WhatsApp\Repositories\WhatsappCampaignRepository;
use Crm\WhatsApp\Repositories\WhatsappDoNotContactRepository;
use Crm\WhatsApp\Services\PhoneParserService;
use Crm\WhatsApp\Services\WhatsAppClientService;

class CampaignController extends Controller
{
    public function __construct(
        protected WhatsappCampaignRepository $campaignRepository,
        protected WhatsappCampaignRecipientRepository $recipientRepository,
        protected WhatsappDoNotContactRepository $dncRepository,
        protected PhoneParserService $phoneParserService,
        protected WhatsAppClientService $whatsAppClientService
    ) {}

    /**
     * Display a listing of campaigns.
     */
    public function index(Request $request): View|JsonResponse
    {
        $search = trim((string) $request->input('search'));

        $campaigns = WhatsappCampaign::with('user')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('caption', 'like', "%{$search}%");
                })
                ->orderByRaw("CASE 
                    WHEN name LIKE ? THEN 1 
                    WHEN name LIKE ? THEN 2 
                    ELSE 3 
                END", ["{$search}%", "%{$search}%"]);
            })
            ->latest('id')
            ->paginate(15)
            ->appends(['search' => $search]);

        if ($request->ajax() || $request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'data'         => $campaigns->items(),
                'current_page' => $campaigns->currentPage(),
                'last_page'    => $campaigns->lastPage(),
                'total'        => $campaigns->total(),
                'per_page'     => $campaigns->perPage(),
                'has_more'     => $campaigns->hasMorePages(),
                'next_page_url'=> $campaigns->nextPageUrl(),
                'first_item'   => $campaigns->firstItem(),
                'last_item'    => $campaigns->lastItem(),
            ]);
        }

        $gatewayStatus = $this->whatsAppClientService->getStatus();

        return view('admin::whatsapp.index', compact('campaigns', 'gatewayStatus', 'search'));
    }

    /**
     * Show the form for creating a new campaign.
     */
    public function create(): View
    {
        $gatewayStatus = $this->whatsAppClientService->getStatus();

        return view('admin::whatsapp.create', compact('gatewayStatus'));
    }

    /**
     * Store a newly created campaign and parse phone numbers.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'numbers_file'     => 'nullable|required_without:manual_numbers|file|max:10240', // 10MB
            'manual_numbers'   => 'nullable|required_without:numbers_file|string|max:50000',
            'brochure_file'    => 'nullable|required_without:caption|file|max:' . (config('whatsapp.max_media_mb', 16) * 1024),
            'caption'          => 'nullable|required_without:brochure_file|string|max:2000',
            'throttle_seconds' => 'required|integer|min:5|max:300',
            'daily_limit'      => 'nullable|integer|min:1|max:5000',
        ], [
            'numbers_file.required_without'   => 'Please upload a phone numbers file, type numbers manually, or both.',
            'manual_numbers.required_without' => 'Please type phone numbers manually, upload a file, or both.',
            'caption.required_without'        => 'Please provide a message caption, a brochure file, or both.',
            'brochure_file.required_without'  => 'Please provide a brochure file, a message caption, or both.',
        ]);

        // Upload brochure if provided
        $brochurePath = null;
        $brochureName = null;

        if ($request->hasFile('brochure_file')) {
            $brochureFile = $request->file('brochure_file');
            $brochurePath = $brochureFile->store('whatsapp_brochures', 'public');
            $brochureName = $brochureFile->getClientOriginalName();
        }

        // Parse phone numbers from file and/or manual textarea
        $parseResult = $this->phoneParserService->parseCombined(
            $request->hasFile('numbers_file') ? $request->file('numbers_file') : null,
            $request->input('manual_numbers')
        );

        if ($parseResult['valid_count'] === 0) {
            // Clean up brochure if no valid numbers
            if ($brochurePath) {
                Storage::disk('public')->delete($brochurePath);
            }

            session()->flash('error', 'No valid phone numbers could be extracted. Please check the entered or uploaded numbers.');
            return redirect()->back()->withInput();
        }

        // Create campaign in draft status
        $campaign = $this->campaignRepository->create([
            'name'             => $request->input('name'),
            'brochure_path'    => $brochurePath,
            'brochure_name'    => $brochureName,
            'caption'          => $request->input('caption'),
            'status'           => 'draft',
            'throttle_seconds' => (int) $request->input('throttle_seconds', 20),
            'daily_limit'      => $request->input('daily_limit') ? (int) $request->input('daily_limit') : null,
            'total_recipients' => $parseResult['valid_count'],
            'sent_count'       => 0,
            'failed_count'     => 0,
            'created_by'       => auth()->guard('user')->id() ?: (auth()->guard('admin')->id() ?: auth()->id()),
        ]);

        // Insert valid recipients
        $recipientsData = [];
        $now = now();
        foreach ($parseResult['valid'] as $item) {
            $recipientsData[] = [
                'whatsapp_campaign_id' => $campaign->id,
                'raw_input'            => $item['raw'],
                'phone_e164'           => $item['phone_e164'],
                'status'               => 'pending',
                'created_at'           => $now,
                'updated_at'           => $now,
            ];
        }

        foreach (array_chunk($recipientsData, 500) as $chunk) {
            WhatsappCampaignRecipient::insert($chunk);
        }

        // Cache parsing metadata for the preview screen
        session()->put("whatsapp_preview_{$campaign->id}", [
            'total_rows'      => $parseResult['total_rows'],
            'valid_count'     => $parseResult['valid_count'],
            'invalid_count'   => $parseResult['invalid_count'],
            'duplicate_count' => $parseResult['duplicate_count'],
            'invalid'         => $parseResult['invalid'],
            'duplicates'      => $parseResult['duplicates'],
        ]);

        session()->flash('success', "File parsed successfully! Found {$parseResult['valid_count']} valid contacts.");

        return redirect()->route('admin.whatsapp.preview', $campaign->id);
    }

    /**
     * Preview parsed numbers and consent confirmation before dispatching.
     */
    public function preview(int $id): View|RedirectResponse
    {
        $campaign = $this->campaignRepository->findOrFail($id);

        if ($campaign->status !== 'draft') {
            return redirect()->route('admin.whatsapp.show', $campaign->id);
        }

        $previewData = session()->get("whatsapp_preview_{$campaign->id}", [
            'total_rows'      => $campaign->total_recipients,
            'valid_count'     => $campaign->total_recipients,
            'invalid_count'   => 0,
            'duplicate_count' => 0,
            'invalid'         => [],
            'duplicates'      => [],
        ]);

        $sampleRecipients = $campaign->recipients()
            ->limit(20)
            ->get();

        $gatewayStatus = $this->whatsAppClientService->getStatus();

        return view('admin::whatsapp.preview', compact('campaign', 'previewData', 'sampleRecipients', 'gatewayStatus'));
    }

    /**
     * Download rejected numbers and duplicate records as a CSV.
     */
    public function downloadRejected(int $id): StreamedResponse
    {
        $previewData = session()->get("whatsapp_preview_{$id}", []);
        $invalid = $previewData['invalid'] ?? [];
        $duplicates = $previewData['duplicates'] ?? [];

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=whatsapp_campaign_{$id}_rejected_numbers.csv",
        ];

        return response()->stream(function () use ($invalid, $duplicates) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Type', 'Raw Input', 'Normalized / Reason', 'Details']);

            foreach ($invalid as $row) {
                fputcsv($handle, ['Invalid', $row['raw'] ?? '', '', $row['reason'] ?? 'Invalid Format']);
            }

            foreach ($duplicates as $row) {
                fputcsv($handle, ['Duplicate', $row['raw'] ?? '', $row['phone_e164'] ?? '', $row['reason'] ?? 'Duplicate']);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Start the broadcast campaign (dispatches queue jobs with staggered delay).
     */
    public function startCampaign(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $request->validate([
            'confirm_consent' => 'accepted',
            'brochure_file'   => 'nullable|file|max:' . (config('whatsapp.max_media_mb', 16) * 1024),
        ], [
            'confirm_consent.accepted' => 'You must confirm that all recipients consented to receive marketing/business messages from your organization.',
        ]);

        $campaign = $this->campaignRepository->findOrFail($id);

        if ($campaign->status !== 'draft' && $campaign->status !== 'paused') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Campaign cannot be started in current status.'], 422);
            }
            session()->flash('error', 'Campaign cannot be started in current status.');
            return redirect()->route('admin.whatsapp.show', $campaign->id);
        }

        // Update brochure file if a new file was uploaded when starting
        if ($request->hasFile('brochure_file')) {
            $file = $request->file('brochure_file');
            $path = $file->store('whatsapp-brochures', 'public');

            $campaign->update([
                'brochure_path' => $path,
                'brochure_name' => $file->getClientOriginalName(),
                'media_type'    => str_starts_with($file->getMimeType(), 'image/') ? 'image' : (str_starts_with($file->getMimeType(), 'video/') ? 'video' : 'document'),
            ]);
        }

        $campaign->update([
            'status'                => 'running',
            'consecutive_failures'  => 0,
            'pause_reason'          => null,
        ]);

        // Dispatch queued jobs with staggered safety throttle
        $pendingRecipients = $campaign->recipients()
            ->where('status', 'pending')
            ->get();

        $delaySeconds = 0;
        $throttle = max(5, (int) $campaign->throttle_seconds);

        foreach ($pendingRecipients as $recipient) {
            SendWhatsappCampaignMessageJob::dispatch($campaign->id, $recipient->id)
                ->delay(now()->addSeconds($delaySeconds));

            $delaySeconds += $throttle;
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status'  => 'running',
                'message' => "WhatsApp broadcast started! {$pendingRecipients->count()} messages queued for delivery.",
            ]);
        }

        session()->flash('success', "WhatsApp broadcast started! {$pendingRecipients->count()} messages queued for delivery.");

        return redirect()->route('admin.whatsapp.show', $campaign->id);
    }

    /**
     * Update broadcast campaign details (name, caption, brochure, pacing).
     */
    public function update(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $campaign = $this->campaignRepository->findOrFail($id);

        $request->validate([
            'name'             => 'required|string|max:255',
            'caption'          => 'nullable|string|max:2000',
            'throttle_seconds' => 'required|integer|min:5|max:300',
            'brochure_file'    => 'nullable|file|max:' . (config('whatsapp.max_media_mb', 16) * 1024),
        ]);

        $data = [
            'name'             => $request->input('name'),
            'caption'          => $request->input('caption'),
            'throttle_seconds' => (int) $request->input('throttle_seconds'),
        ];

        if ($request->hasFile('brochure_file')) {
            $file = $request->file('brochure_file');
            $path = $file->store('whatsapp-brochures', 'public');

            $data['brochure_path'] = $path;
            $data['brochure_name'] = $file->getClientOriginalName();
        }

        $campaign->update($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'       => true,
                'message'       => 'Campaign details updated successfully!',
                'name'          => $campaign->name,
                'caption'       => $campaign->caption,
                'throttle'      => $campaign->throttle_seconds,
                'brochure_name' => $campaign->brochure_name,
                'brochure_url'  => $campaign->brochure_url,
                'media_type'    => $campaign->media_type,
            ]);
        }

        session()->flash('success', 'Campaign details updated successfully!');

        return redirect()->route('admin.whatsapp.show', $campaign->id);
    }

    /**
     * Display the live campaign progress dashboard.
     */
    public function show(int $id): View
    {
        $campaign = $this->campaignRepository->findOrFail($id);

        $recipients = $campaign->recipients()
            ->when(request('status'), function ($q, $status) {
                return $q->where('status', $status);
            })
            ->when(request('search'), function ($q, $search) {
                return $q->where('phone_e164', 'like', "%{$search}%")
                    ->orWhere('raw_input', 'like', "%{$search}%");
            })
            ->orderBy('id', 'asc')
            ->paginate(50)
            ->withQueryString();

        $initialRecipients = [
            'data'         => $recipients->items(),
            'current_page' => $recipients->currentPage(),
            'last_page'    => $recipients->lastPage(),
            'total'        => $recipients->total(),
        ];

        $gatewayStatus = $this->whatsAppClientService->getStatus();

        return view('admin::whatsapp.show', compact('campaign', 'recipients', 'initialRecipients', 'gatewayStatus'));
    }

    /**
     * JSON endpoint for real-time polling from the UI dashboard.
     */
    public function status(int $id): JsonResponse
    {
        $campaign = $this->campaignRepository->findOrFail($id);

        $recipientsQuery = $campaign->recipients()
            ->when(request('status'), function ($q, $status) {
                return $q->where('status', $status);
            })
            ->when(request('search'), function ($q, $search) {
                return $q->where(function ($sub) use ($search) {
                    $sub->where('phone_e164', 'like', "%{$search}%")
                        ->orWhere('raw_input', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'asc');

        $recipients = $recipientsQuery->paginate(50);

        return response()->json([
            'id'                   => $campaign->id,
            'status'               => $campaign->status,
            'pause_reason'         => $campaign->pause_reason,
            'consecutive_failures' => $campaign->consecutive_failures,
            'total'                => $campaign->total_recipients,
            'sent'                 => $campaign->sent_count,
            'failed'               => $campaign->failed_count,
            'pending'              => $campaign->pending_count,
            'progress_percent'     => $campaign->progress_percent,
            'recipients'           => [
                'data'         => $recipients->items(),
                'current_page' => $recipients->currentPage(),
                'last_page'    => $recipients->lastPage(),
                'total'        => $recipients->total(),
            ],
            'recent_recipients'    => $campaign->recipients()->latest('updated_at')->limit(15)->get(['id', 'phone_e164', 'status', 'error_message', 'sent_at', 'updated_at']),
        ]);
    }

    /**
     * Pause a running campaign.
     */
    public function pause(int $id): JsonResponse|RedirectResponse
    {
        $campaign = $this->campaignRepository->findOrFail($id);

        if ($campaign->status === 'running') {
            $campaign->update([
                'status'       => 'paused',
                'pause_reason' => 'Manually paused by user.',
            ]);
        }

        if (request()->ajax()) {
            return response()->json(['success' => true, 'status' => 'paused']);
        }

        session()->flash('success', 'Campaign broadcast paused.');
        return redirect()->route('admin.whatsapp.show', $campaign->id);
    }

    /**
     * Resume a paused campaign.
     */
    public function resume(int $id): JsonResponse|RedirectResponse
    {
        $campaign = $this->campaignRepository->findOrFail($id);

        if ($campaign->status === 'paused') {
            $campaign->update([
                'status'               => 'running',
                'pause_reason'         => null,
                'consecutive_failures' => 0,
            ]);

            $pendingRecipients = $campaign->recipients()
                ->where('status', 'pending')
                ->get();

            $delaySeconds = 0;
            $throttle = max(5, (int) $campaign->throttle_seconds);

            foreach ($pendingRecipients as $recipient) {
                SendWhatsappCampaignMessageJob::dispatch($campaign->id, $recipient->id)
                    ->delay(now()->addSeconds($delaySeconds));

                $delaySeconds += $throttle;
            }
        }

        if (request()->ajax()) {
            return response()->json(['success' => true, 'status' => 'running']);
        }

        session()->flash('success', 'Campaign broadcast resumed.');
        return redirect()->route('admin.whatsapp.show', $campaign->id);
    }

    /**
     * Cancel a campaign and skip remaining pending recipients.
     */
    public function cancel(int $id): JsonResponse|RedirectResponse
    {
        $campaign = $this->campaignRepository->findOrFail($id);

        $campaign->update([
            'status'       => 'cancelled',
            'pause_reason' => 'Broadcast cancelled by user.',
        ]);

        $campaign->recipients()
            ->whereIn('status', ['pending', 'sending'])
            ->update(['status' => 'skipped']);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'status' => 'cancelled']);
        }

        session()->flash('success', 'Campaign cancelled.');
        return redirect()->route('admin.whatsapp.show', $campaign->id);
    }

    /**
     * Retry failed recipients in a campaign.
     */
    public function retryFailed(int $id): JsonResponse|RedirectResponse
    {
        $campaign = $this->campaignRepository->findOrFail($id);

        $failedCount = $campaign->recipients()->where('status', 'failed')->count();

        if ($failedCount === 0) {
            session()->flash('warning', 'No failed recipients found to retry.');
            return redirect()->route('admin.whatsapp.show', $campaign->id);
        }

        $campaign->recipients()->where('status', 'failed')->update([
            'status'        => 'pending',
            'attempts'      => 0,
            'error_message' => null,
        ]);

        $campaign->update([
            'failed_count'         => max(0, $campaign->failed_count - $failedCount),
            'status'               => 'running',
            'consecutive_failures' => 0,
            'pause_reason'         => null,
        ]);

        $pendingToRetry = $campaign->recipients()->where('status', 'pending')->get();

        $delaySeconds = 0;
        $throttle = max(5, (int) $campaign->throttle_seconds);

        foreach ($pendingToRetry as $recipient) {
            SendWhatsappCampaignMessageJob::dispatch($campaign->id, $recipient->id)
                ->delay(now()->addSeconds($delaySeconds));

            $delaySeconds += $throttle;
        }

        session()->flash('success', "Retrying {$failedCount} failed messages.");
        return redirect()->route('admin.whatsapp.show', $campaign->id);
    }

    /**
     * Delete a campaign.
     */
    public function destroy(int $id): RedirectResponse|JsonResponse
    {
        $campaign = $this->campaignRepository->findOrFail($id);

        if (! empty($campaign->brochure_path)) {
            Storage::disk('public')->delete($campaign->brochure_path);
        }

        $this->campaignRepository->delete($id);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'WhatsApp Campaign deleted successfully.',
            ]);
        }

        session()->flash('success', 'WhatsApp Campaign deleted successfully.');

        return redirect()->route('admin.whatsapp.index');
    }

    /**
     * WhatsApp Gateway QR Code & Connection Management Page.
     */
    public function gateway(): View
    {
        $status = $this->whatsAppClientService->getStatus();
        $qrData = $this->whatsAppClientService->getQrCode();

        return view('admin::whatsapp.gateway', compact('status', 'qrData'));
    }

    /**
     * JSON polling endpoint for Gateway Status.
     */
    public function gatewayStatus(): JsonResponse
    {
        $status = $this->whatsAppClientService->getStatus();
        return response()->json($status);
    }

    /**
     * JSON polling endpoint for Gateway QR code.
     */
    public function gatewayQr(): JsonResponse
    {
        $qrData = $this->whatsAppClientService->getQrCode();
        return response()->json($qrData);
    }

    /**
     * Disconnect / Logout WhatsApp Gateway session.
     */
    public function gatewayLogout(): JsonResponse|RedirectResponse
    {
        $result = $this->whatsAppClientService->logout();

        if (request()->ajax()) {
            return response()->json($result);
        }

        session()->flash('success', 'Logged out from WhatsApp session. Scan a new QR code to reconnect.');
        return redirect()->route('admin.whatsapp.gateway');
    }

    /**
     * Do Not Contact (DNC) Registry.
     */
    public function dnc(): View
    {
        $dncList = WhatsappDoNotContact::latest('id')->paginate(20);
        return view('admin::whatsapp.dnc', compact('dncList'));
    }

    /**
     * Add number to DNC list.
     */
    public function dncStore(Request $request): RedirectResponse
    {
        $request->validate([
            'phone'  => 'required|string',
            'reason' => 'nullable|string|max:255',
        ]);

        $normalized = $this->phoneParserService->normalizeNumber($request->input('phone'));

        if (! $normalized['valid']) {
            session()->flash('error', 'Invalid phone number format: ' . $normalized['reason']);
            return redirect()->back();
        }

        WhatsappDoNotContact::firstOrCreate(
            ['phone_e164' => $normalized['phone_e164']],
            ['reason' => $request->input('reason', 'Manual opt-out')]
        );

        session()->flash('success', "Number +{$normalized['phone_e164']} added to Do Not Contact (DNC) list.");
        return redirect()->route('admin.whatsapp.dnc');
    }

    /**
     * Remove number from DNC list.
     */
    public function dncDestroy(int $id): RedirectResponse
    {
        $this->dncRepository->delete($id);

        session()->flash('success', 'Phone number removed from DNC list.');
        return redirect()->route('admin.whatsapp.dnc');
    }
}
