<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Crm\Core\TenantContext;
use Crm\Lead\Repositories\LeadRepository;
use Crm\Lead\Repositories\PipelineRepository;
use Crm\Lead\Repositories\SourceRepository;
use Crm\Lead\Repositories\StageRepository;
use Crm\Lead\Repositories\TypeRepository;

class LandingController extends Controller
{
    public function __construct(
        protected LeadRepository $leadRepository,
        protected PipelineRepository $pipelineRepository,
        protected StageRepository $stageRepository,
        protected SourceRepository $sourceRepository,
        protected TypeRepository $typeRepository
    ) {
    }

    /**
     * Render the landing page.
     */
    public function index(): Response
    {
        $indexPath = public_path('landing-dist/index.html');

        if (file_exists($indexPath)) {
            $content = file_get_contents($indexPath);
            return response($content, 200, ['Content-Type' => 'text/html; charset=UTF-8']);
        }

        // Fallback if not compiled yet
        return response(
            '<html><head><title>Loading CRM...</title><meta http-equiv="refresh" content="2"></head>' .
            '<body style="font-family:sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;background:#F8F7F4;">' .
            '<h2>Building Landing Page... please refresh in a moment.</h2></body></html>',
            200,
            ['Content-Type' => 'text/html; charset=UTF-8']
        );
    }

    /**
     * Store lead submitted from the landing page.
     */
    public function storeLead(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'nullable|string|max:50',
            'businessType' => 'nullable|string|max:255',
            'business_type'=> 'nullable|string|max:255',
            'teamSize'     => 'nullable|string|max:255',
            'team_size'    => 'nullable|string|max:255',
            'focusArea'    => 'nullable|string|max:255',
            'focus_area'   => 'nullable|string|max:255',
            'location'     => 'nullable|string|max:255',
            'message'      => 'nullable|string|max:3000',
            'form_type'    => 'nullable|string|max:255',
        ]);

        try {
            // Ensure tenant context is set for multi-tenant data scoping
            if (!TenantContext::getTenantId()) {
                TenantContext::setTenantId(1);
            }

            $businessType = $request->input('businessType', $request->input('business_type'));
            $teamSize     = $request->input('teamSize', $request->input('team_size'));
            $focusArea    = $request->input('focusArea', $request->input('focus_area'));
            $location     = $request->input('location');
            $message      = $request->input('message');
            $formType     = $request->input('form_type', 'Landing Page Form');

            // Resolve Pipeline & Stage
            $pipeline = $this->pipelineRepository->getDefaultPipeline();
            if (!$pipeline) {
                $pipeline = $this->pipelineRepository->first();
            }

            $pipelineId = $pipeline ? $pipeline->id : 1;
            $stage = $pipeline ? $pipeline->stages()->orderBy('sort_order', 'asc')->first() : null;
            $stageId = $stage ? $stage->id : 1;

            // Resolve Source (Web / Landing Page)
            $source = $this->sourceRepository->findOneWhere(['name' => 'Web'])
                ?: $this->sourceRepository->first();
            $sourceId = $source ? $source->id : null;

            // Resolve Type (New Business)
            $type = $this->typeRepository->findOneWhere(['name' => 'New Business'])
                ?: $this->typeRepository->first();
            $typeId = $type ? $type->id : null;

            // Build human-readable formatted description
            $descriptionLines = [
                "=== LANDING PAGE INQUIRY ===",
                "Full Name: " . $request->input('name'),
                "Email: " . $request->input('email'),
                "Phone: " . ($request->input('phone') ?: 'Not provided'),
            ];

            if (!empty($businessType)) {
                $descriptionLines[] = "Business Segment: " . $businessType;
            }
            if (!empty($teamSize)) {
                $descriptionLines[] = "Sales Team Scale: " . $teamSize;
            }
            if (!empty($focusArea)) {
                $descriptionLines[] = "Primary Goal: " . $focusArea;
            }
            if (!empty($location)) {
                $descriptionLines[] = "Location / Region: " . $location;
            }
            if (!empty($message)) {
                $descriptionLines[] = "\nNotes / Message:\n" . $message;
            }

            $descriptionLines[] = "\nForm: " . $formType;
            $descriptionLines[] = "Received At: " . now()->format('d M Y, h:i A');

            $description = implode("\n", $descriptionLines);

            // Lead title
            $titleSuffix = $businessType ?: ($focusArea ?: 'Landing Page Lead');
            $leadTitle = $request->input('name') . ' - ' . $titleSuffix;

            // Prepare Person Data
            $personData = [
                'name'   => $request->input('name'),
                'emails' => [
                    ['value' => $request->input('email'), 'label' => 'work'],
                ],
            ];

            if ($request->filled('phone')) {
                $personData['contact_numbers'] = [
                    ['value' => $request->input('phone'), 'label' => 'work'],
                ];
            }

            $leadData = [
                'title'                  => $leadTitle,
                'description'            => $description,
                'lead_value'             => 0,
                'lead_pipeline_id'       => $pipelineId,
                'lead_pipeline_stage_id' => $stageId,
                'lead_source_id'         => $sourceId,
                'lead_type_id'           => $typeId,
                'status'                 => 1,
                'person'                 => $personData,
            ];

            Event::dispatch('lead.create.before');

            $lead = $this->leadRepository->create($leadData);

            Event::dispatch('lead.create.after', $lead);

            return response()->json([
                'success' => true,
                'status'  => 'success',
                'message' => 'Thank you! Your request has been received. Our team will contact you shortly.',
                'data'    => [
                    'lead_id'   => $lead->id,
                    'person_id' => $lead->person_id,
                ],
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Landing page lead capture failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'Unable to save your request right now. Please try again or reach out to us directly.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
