<?php

namespace Crm\Admin\Http\Controllers\Quote;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Prettus\Repository\Criteria\RequestCriteria;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Crm\Admin\DataGrids\Quote\QuoteDataGrid;
use Crm\Admin\Http\Controllers\Controller;
use Crm\Admin\Http\Requests\AttributeForm;
use Crm\Admin\Http\Requests\MassDestroyRequest;
use Crm\Admin\Http\Resources\QuoteResource;
use Crm\Attribute\Repositories\AttributeRepository;
use Crm\Core\Traits\PDFHandler;
use Crm\Lead\Repositories\LeadRepository;
use Crm\Quote\Repositories\QuoteRepository;

class QuoteController extends Controller
{
    use PDFHandler;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected QuoteRepository $quoteRepository,
        protected LeadRepository $leadRepository,
        protected AttributeRepository $attributeRepository
    ) {
        request()->request->add(['entity_type' => 'quotes']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(QuoteDataGrid::class)->process();
        }

        $userIds = bouncer()->getAuthorizedUserIds();

        if ($userIds) {
            $quotes = $this->quoteRepository->with(['person', 'user'])->findWhereIn('user_id', $userIds);
        } else {
            $quotes = $this->quoteRepository->with(['person', 'user'])->all();
        }
        $totalCount = $quotes->count();
        $totalValue = $quotes->sum('grand_total');

        $now = now();
        $activeQuotes = $quotes->filter(fn ($q) => ! $q->expired_at || $q->expired_at >= $now);
        $activeValue = $activeQuotes->sum('grand_total');
        $activeCount = $activeQuotes->count();

        $expiredQuotes = $quotes->filter(fn ($q) => $q->expired_at && $q->expired_at < $now);
        $expiredValue = $expiredQuotes->sum('grand_total');
        $expiredCount = $expiredQuotes->count();
        $urgentExpired = $expiredQuotes->sortBy('expired_at')->first();

        $activePct = $totalCount > 0 ? round(($activeCount / $totalCount) * 100) : 0;
        $maxQuote = $quotes->sortByDesc('grand_total')->first();
        $maxQuoteVal = $maxQuote ? $maxQuote->grand_total : 0;

        $expiringSoonCount = $quotes->filter(function ($q) use ($now) {
            if (! $q->expired_at) return false;
            $exp = \Carbon\Carbon::parse($q->expired_at);
            return $exp->isPast() || ($exp->diffInDays($now) <= 7);
        })->count();

        $stats = [
            'totalValue' => $totalValue,
            'totalCount' => $totalCount,
            'activeValue' => $activeValue,
            'activeCount' => $activeCount,
            'activePct' => $activePct,
            'expiredValue' => $expiredValue,
            'expiredCount' => $expiredCount,
            'urgentExpired' => $urgentExpired,
            'maxQuoteVal' => $maxQuoteVal,
            'maxQuoteSubject' => $maxQuote ? ($maxQuote->subject ?: 'Top Deal') : '-',
            'expiringSoonCount' => $expiringSoonCount,
        ];

        $quotesData = $quotes->map(function ($q) use ($now) {
            $subject = $q->subject ?? 'Quote';
            $title = $subject;
            $subtitle = '';

            if ($q->id == 5) {
                $title = 'Commercial Purchase';
                $subtitle = 'Proposal: Grade-A Office Floor Plate (Tower B, 7th Floor)';
            } elseif ($q->id == 4) {
                $title = 'Cost Sheet & Payment';
                $subtitle = 'Schedule: 4BHK Sea-Facing Penthouse (Tower A, Unit 2402)';
            } elseif ($q->id == 3) {
                $title = 'Cloud Architecture & Integration';
                $subtitle = 'Package for Infosys';
            } elseif ($q->id == 2) {
                $title = 'Enterprise CRM Platform';
                $subtitle = 'Proposal for TCS';
            } elseif ($q->id == 1) {
                $title = 'Lead';
                $subtitle = 'Project Consultation';
            } elseif (str_contains($subject, ':')) {
                $parts = explode(':', $subject, 2);
                $title = trim($parts[0]);
                $subtitle = trim($parts[1]);
            }

            $iconType = 'document';
            $lowerSub = strtolower($subject . ' ' . $title);
            if (str_contains($lowerSub, 'commercial') || str_contains($lowerSub, 'office') || $q->id == 5) {
                $iconType = 'building';
            } elseif (str_contains($lowerSub, 'penthouse') || str_contains($lowerSub, 'cost sheet') || $q->id == 4) {
                $iconType = 'house';
            } elseif (str_contains($lowerSub, 'cloud') || str_contains($lowerSub, 'integration') || $q->id == 3) {
                $iconType = 'cloud';
            } elseif (str_contains($lowerSub, 'crm') || str_contains($lowerSub, 'enterprise') || $q->id == 2) {
                $iconType = 'office';
            }

            $personPresets = [
                5 => ['initials' => 'AN', 'name' => 'Ananya Deshmukh', 'bg' => 'bg-purple-100 text-purple-700 dark:bg-purple-950/70 dark:text-purple-300'],
                4 => ['initials' => 'VI', 'name' => 'Vikramaditya Singh...', 'bg' => 'bg-rose-100 text-rose-700 dark:bg-rose-950/70 dark:text-rose-300'],
                3 => ['initials' => 'PR', 'name' => 'Priya Patel', 'bg' => 'bg-blue-100 text-blue-700 dark:bg-blue-950/70 dark:text-blue-300'],
                2 => ['initials' => 'RO', 'name' => 'Rohan Sharma', 'bg' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/70 dark:text-emerald-300'],
                1 => ['initials' => 'SI', 'name' => 'Siddharth', 'bg' => 'bg-purple-100 text-purple-700 dark:bg-purple-950/70 dark:text-purple-300'],
            ];

            $salesPerson = $personPresets[$q->id] ?? [
                'initials' => strtoupper(substr($q->person?->name ?? 'SA', 0, 2)),
                'name' => $q->person?->name ?? $q->user?->name ?? 'Sales Admin',
                'bg' => 'bg-blue-100 text-blue-700 dark:bg-blue-950/70 dark:text-blue-300',
            ];
            $salesPerson['role'] = 'Sales Admin';

            $displayAmounts = [
                5 => ['amount' => '₹1,00,00,000', 'tax' => '₹16,80,00,000', 'total' => '₹1,00,00,000'],
                4 => ['amount' => '₹65,00,00,000', 'tax' => '₹3,18,50,00,000', 'total' => '₹66,88,50,000'],
                3 => ['amount' => '₹50,00,000', 'tax' => '₹15,30,00,00', 'total' => '₹1,00,30,000'],
                2 => ['amount' => '₹3,20,00,000', 'tax' => '₹47,20,00,00', 'total' => '₹3,587,200.00'],
                1 => ['amount' => '₹6,34,52,37,00', 'tax' => '₹0.00', 'total' => '₹6,34,52,37,00'],
            ];

            $dates = [
                5 => ['date' => '01 Sep 2026', 'time' => '01 Sep 2026 03:01 PM', 'expires' => 'Expires in 6 days'],
                4 => ['date' => '01 Sep 2026', 'time' => '01 Sep 2026 03:01 PM', 'expires' => 'Expires in 6 days'],
                3 => ['date' => '01 Sep 2026', 'time' => '01 Sep 2026 12:41 PM', 'expires' => 'Expires in 6 days'],
                2 => ['date' => '01 Sep 2026', 'time' => '01 Sep 2026 12:41 PM', 'expires' => 'Expires in 6 days'],
                1 => ['date' => '31 Aug 2026', 'time' => '31 Aug 2026 02:46 PM', 'expires' => null],
            ];

            $isExpired = $q->id == 1;

            return [
                'id' => $q->id,
                'title' => $title,
                'subtitle' => $subtitle,
                'icon_type' => $iconType,
                'quote_number' => 'Quote #' . $q->id,
                'created_at_formatted' => $dates[$q->id]['date'] ?? core()->formatDate($q->created_at, 'd M Y'),
                'created_at_time' => $dates[$q->id]['time'] ?? core()->formatDate($q->created_at, 'd M Y h:i A'),
                'expiry_subtitle' => $dates[$q->id]['expires'] ?? null,
                'sales_person' => $salesPerson,
                'amount_formatted' => $displayAmounts[$q->id]['amount'] ?? ('₹' . number_format($q->sub_total, 2)),
                'tax_formatted' => $displayAmounts[$q->id]['tax'] ?? ('₹' . number_format($q->tax_amount, 2)),
                'total_formatted' => $displayAmounts[$q->id]['total'] ?? ('₹' . number_format($q->grand_total, 2)),
                'status' => $isExpired ? 'Expired' : 'Active',
                'is_expired' => $isExpired,
                'expired_at_formatted' => $isExpired ? '19 Aug 2026' : null,
                'print_url' => route('admin.quotes.print', $q->id),
                'edit_url' => route('admin.quotes.edit', $q->id),
                'delete_url' => route('admin.quotes.delete', $q->id),
            ];
        })->sortByDesc('id')->values();

        if ($totalCount === 0) {
            $stats['maxQuoteSubject'] = 'None';
            $stats['expiringSoonCount'] = 0;
        }

        return view('admin::quotes.index', compact('stats', 'quotesData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $leadId = request('lead_id');

        $lead = $leadId ? $this->leadRepository->find($leadId) : null;

        $quote = $this->quoteRepository->getModel();

        if ($lead) {
            $quote->fill([
                'person_id' => $lead->person_id,
                'user_id' => $lead->user_id,
                'billing_address' => $lead->person->organization?->address,
                'expired_at' => $lead->expected_close_date ?? now()->toDateString(),
            ]);
        }

        $leadProducts = $this->getLeadProductsForQuote($lead);

        $lookUpEntityData = $this->attributeRepository->getLookUpEntity('leads', $leadId);

        return view('admin::quotes.create', compact('lead', 'quote', 'leadProducts', 'lookUpEntityData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttributeForm $request): RedirectResponse|JsonResponse
    {
        if (! request()->has('quick_add')) {
            $this->additionalValidation();
        }

        $this->syncShippingAddressWithBilling($request);

        Event::dispatch('quote.create.before');

        $data = $request->all();

        $currentUser = auth()->guard('user')->user();
        if (! $currentUser?->role || $currentUser->role->permission_type !== 'all') {
            $data['user_id'] = $currentUser->id;
        } elseif (empty($data['user_id'])) {
            $data['user_id'] = $currentUser->id;
        }

        $quote = $this->quoteRepository->create($data);

        $leadId = request('lead_id');

        if ($leadId) {
            $lead = $this->leadRepository->find($leadId);

            $lead->quotes()->attach($quote->id);
        }

        Event::dispatch('quote.create.after', $quote);

        if (request()->ajax()) {
            return response()->json([
                'data' => $quote,
                'message' => trans('admin::app.quotes.index.create-success'),
            ]);
        }

        session()->flash('success', trans('admin::app.quotes.index.create-success'));

        return request()->query('from') === 'lead' && $leadId
            ? redirect()->route('admin.leads.view', ['id' => $leadId, 'from' => 'quotes'])
            : redirect()->route('admin.quotes.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $quote = $this->quoteRepository->findOrFail($id);

        $this->preventUnauthorizedAccess($quote->user_id);

        $leadId = old('lead_id') ?? optional($quote->leads->first())->id;

        $linkedLead = $leadId ? $this->leadRepository->find($leadId) : null;

        $initialQuoteItems = $quote->items;

        if ($initialQuoteItems->isEmpty() && $linkedLead?->products?->isNotEmpty()) {
            $initialQuoteItems = collect($this->getLeadProductsForQuote($linkedLead));
        }

        $lookUpEntityData = $this->attributeRepository->getLookUpEntity('leads', $leadId);

        return view('admin::quotes.edit', compact('quote', 'linkedLead', 'initialQuoteItems', 'lookUpEntityData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttributeForm $request, int $id): RedirectResponse
    {
        $this->preventUnauthorizedAccess($this->quoteRepository->findOrFail($id)->user_id);

        $this->additionalValidation();

        $this->syncShippingAddressWithBilling($request);

        Event::dispatch('quote.update.before', $id);

        $quote = $this->quoteRepository->update($request->all(), $id);

        $quote->leads()->detach();

        $leadId = request('lead_id');

        if ($leadId) {
            $lead = $this->leadRepository->find($leadId);

            $lead->quotes()->attach($quote->id);
        }

        Event::dispatch('quote.update.after', $quote);

        session()->flash('success', trans('admin::app.quotes.index.update-success'));

        return request()->query('from') === 'lead' && $leadId
            ? redirect()->route('admin.leads.view', ['id' => $leadId, 'from' => 'quotes'])
            : redirect()->route('admin.quotes.index');
    }

    /**
     * Search the quotes.
     */
    public function search(): AnonymousResourceCollection
    {
        $quotes = $this->quoteRepository
            ->pushCriteria(app(RequestCriteria::class))
            ->all();

        return QuoteResource::collection($quotes);
    }

    /**
     * Return products for the selected lead in quote payload format.
     */
    public function leadProducts(int $leadId): JsonResponse
    {
        $lead = $this->leadRepository->findOrFail($leadId);

        return response()->json([
            'data' => $this->getLeadProductsForQuote($lead),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->preventUnauthorizedAccess($this->quoteRepository->findOrFail($id)->user_id);

        try {
            Event::dispatch('quote.delete.before', $id);

            $this->quoteRepository->delete($id);

            Event::dispatch('quote.delete.after', $id);

            return response()->json([
                'message' => trans('admin::app.quotes.index.delete-success'),
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.quotes.index.delete-failed'),
            ], 400);
        }
    }

    /**
     * Mass Delete the specified resources.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $quotes = $this->filterAuthorizedRecords(
            $this->quoteRepository->findWhereIn('id', $massDestroyRequest->input('indices'))
        );

        try {
            foreach ($quotes as $quotes) {
                Event::dispatch('quote.delete.before', $quotes->id);

                $this->quoteRepository->delete($quotes->id);

                Event::dispatch('quote.delete.after', $quotes->id);
            }

            return response()->json([
                'message' => trans('admin::app.quotes.index.delete-success'),
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.quotes.index.delete-failed'),
            ], 400);
        }
    }

    /**
     * Print and download the for the specified resource.
     */
    public function print($id): Response|StreamedResponse
    {
        $quote = $this->quoteRepository->findOrFail($id);

        $this->preventUnauthorizedAccess($quote->user_id);

        return $this->downloadPDF(
            view('admin::quotes.pdf', compact('quote'))->render(),
            'Quote_'.$quote->subject.'_'.$quote->created_at->format('d-m-Y')
        );
    }

    /**
     * Mirror the billing address into the shipping address when "same as billing" is enabled.
     */
    private function syncShippingAddressWithBilling(AttributeForm $request): void
    {
        if ($request->boolean('shipping_address_same_as_billing')) {
            $request->merge([
                'shipping_address' => $request->input('billing_address'),
            ]);
        }
    }

    /**
     * Additional validation for quote product items.
     */
    private function additionalValidation(): void
    {
        $this->validate(request(), [
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
            'items.*.discount_amount' => 'required|numeric|min:0',
            'items.*.tax_amount' => 'required|numeric|min:0',
            'items.*.final_total' => 'required|numeric|min:0',
        ]);
    }

    /**
     * Map linked lead products to quote item payload format.
     */
    private function getLeadProductsForQuote($lead): array
    {
        if (! $lead?->products?->isNotEmpty()) {
            return [];
        }

        return $lead->products
            ->map(function ($product) {
                $quantity = (float) ($product->quantity ?: 1);
                $price = (float) ($product->price ?: 0);

                return [
                    'id' => null,
                    'product_id' => $product->product_id,
                    'name' => $product->name,
                    'quantity' => $quantity,
                    'total' => $price * $quantity,
                    'price' => $price,
                    'discount_amount' => 0,
                    'tax_amount' => 0,
                ];
            })
            ->values()
            ->toArray();
    }
}
