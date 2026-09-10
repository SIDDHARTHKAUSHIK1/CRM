<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.quotes.edit.title') - #{{ $quote->id }} {{ $quote->subject }}
    </x-slot>

    {!! view_render_event('admin.contacts.quotes.edit.form_controls.before', ['quote' => $quote]) !!}

    <x-admin::form
        :action="route('admin.quotes.update', $quote->id) . '?' . http_build_query(array_merge(
            request()->route()?->parameters() ?? [],
            request()->all()
        ))"
        method="PUT"
    >
        <div class="flex flex-col gap-6">
            <!-- Top Non-Sticky Clean Action Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-col gap-1.5">
                    <x-admin::breadcrumbs
                        name="quotes.edit"
                        :entity="$quote"
                    />

                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-800 dark:text-white">
                            {{ $quote->subject }}
                        </h1>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $quote->expired_at && strtotime($quote->expired_at) < time() ? 'bg-rose-100 text-rose-800 border border-rose-300 dark:bg-rose-950 dark:text-rose-200 dark:border-rose-800' : 'bg-emerald-100 text-emerald-800 border border-emerald-300 dark:bg-emerald-950 dark:text-emerald-200 dark:border-emerald-800' }}">
                            <span class="w-2 h-2 rounded-full {{ $quote->expired_at && strtotime($quote->expired_at) < time() ? 'bg-rose-600' : 'bg-emerald-500' }}"></span>
                            {{ $quote->expired_at && strtotime($quote->expired_at) < time() ? 'Status: Expired' : 'Status: Active Proposal' }}
                        </span>
                    </div>
                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">
                        Proposal #{{ $quote->id }} · Created on {{ core()->formatDate($quote->created_at, 'd M Y') }}
                    </p>
                </div>

                <div class="flex items-center gap-3 flex-wrap">
                    @if (bouncer()->hasPermission('quotes.print'))
                        <a
                            href="{{ route('admin.quotes.print', $quote->id) }}"
                            target="_blank"
                            class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-slate-800 bg-slate-50 border border-slate-300 rounded-xl hover:bg-slate-100 shadow-2xs transition-all dark:border-gray-700 dark:bg-gray-800 dark:text-slate-100 dark:hover:bg-gray-700"
                        >
                            <svg class="w-4 h-4 text-slate-700 dark:text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Receipt</span>
                        </a>
                    @endif

                    @php
                        $buyerPhone = collect($quote->person?->contact_numbers ?? [])->pluck('value')->first();
                        $cleanPhone = $buyerPhone ? preg_replace('/[^0-9]/', '', $buyerPhone) : null;
                    @endphp

                    @if ($cleanPhone)
                        <a
                            href="https://wa.me/{{ $cleanPhone }}"
                            target="_blank"
                            class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-emerald-800 bg-emerald-50 border border-emerald-300 rounded-xl hover:bg-emerald-100 shadow-2xs transition-all dark:bg-emerald-950 dark:text-emerald-200 dark:border-emerald-700"
                        >
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                            <span>Share WhatsApp</span>
                        </a>
                    @endif

                    {!! view_render_event('admin.contacts.quotes.edit.save_button.before', ['quote' => $quote]) !!}

                    <button
                        type="submit"
                        class="primary-button !px-5 !py-2.5 !rounded-xl !text-xs font-extrabold shadow-md active:scale-95 transition-all gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>@lang('admin::app.quotes.edit.save-btn')</span>
                    </button>

                    {!! view_render_event('admin.contacts.quotes.edit.save_button.after', ['quote' => $quote]) !!}
                </div>
            </div>

            <v-quote :errors="errors">
                <x-admin::shimmer.quotes />
            </v-quote>
        </div>
    </x-admin::form>

    {!! view_render_event('admin.contacts.quotes.edit.form_controls.after', ['quote' => $quote]) !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-quote-template"
        >
            <div class="flex flex-col gap-6">
                <!-- Interactive 4-Step Process Stepper Navigation Bar -->
                <div class="bg-white dark:bg-gray-900 p-3 rounded-2xl border border-slate-200/90 dark:border-gray-800 shadow-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5">
                        <!-- Step 1 Button -->
                        <button
                            type="button"
                            @click="scrollToSection('deal-client-info')"
                            :class="[
                                'flex items-center p-3 rounded-xl border text-left transition-all w-full cursor-pointer',
                                activeSection === 'deal-client-info'
                                    ? 'border-brandColor bg-brandColor/10 dark:bg-brandColor/20 shadow-xs'
                                    : 'bg-slate-50/80 hover:bg-slate-100/90 border-slate-200/80 dark:bg-gray-800/60 dark:hover:bg-gray-800 dark:border-gray-700/60'
                            ]"
                        >
                            <span :class="[
                                'flex h-8 w-8 shrink-0 items-center justify-center rounded-xl text-xs font-bold transition-all',
                                activeSection === 'deal-client-info'
                                    ? 'bg-brandColor text-white shadow-xs'
                                    : 'bg-slate-200 text-slate-800 dark:bg-gray-700 dark:text-slate-100'
                            ]">1</span>
                            <div class="ml-3 min-w-0">
                                <p :class="[
                                    'text-xs font-bold leading-tight',
                                    activeSection === 'deal-client-info'
                                        ? 'text-brandColor dark:text-brandColor'
                                        : 'text-gray-800 dark:text-white'
                                ]">Deal & Client Info</p>
                                <p class="text-[11px] font-semibold text-slate-600 dark:text-slate-300">Core Identifiers</p>
                            </div>
                        </button>

                        <!-- Step 2 Button -->
                        <button
                            type="button"
                            @click="scrollToSection('units-pricing')"
                            :class="[
                                'flex items-center p-3 rounded-xl border text-left transition-all w-full cursor-pointer',
                                activeSection === 'units-pricing'
                                    ? 'border-brandColor bg-brandColor/10 dark:bg-brandColor/20 shadow-xs'
                                    : 'bg-slate-50/80 hover:bg-slate-100/90 border-slate-200/80 dark:bg-gray-800/60 dark:hover:bg-gray-800 dark:border-gray-700/60'
                            ]"
                        >
                            <span :class="[
                                'flex h-8 w-8 shrink-0 items-center justify-center rounded-xl text-xs font-bold transition-all',
                                activeSection === 'units-pricing'
                                    ? 'bg-brandColor text-white shadow-xs'
                                    : 'bg-slate-200 text-slate-800 dark:bg-gray-700 dark:text-slate-100'
                            ]">2</span>
                            <div class="ml-3 min-w-0">
                                <p :class="[
                                    'text-xs font-bold leading-tight',
                                    activeSection === 'units-pricing'
                                        ? 'text-brandColor dark:text-brandColor'
                                        : 'text-gray-800 dark:text-white'
                                ]">Units & Pricing</p>
                                <p class="text-[11px] font-semibold text-slate-600 dark:text-slate-300">Inventory & Tax</p>
                            </div>
                        </button>

                        <!-- Step 3 Button -->
                        <button
                            type="button"
                            @click="scrollToSection('payment-milestones')"
                            :class="[
                                'flex items-center p-3 rounded-xl border text-left transition-all w-full cursor-pointer',
                                activeSection === 'payment-milestones'
                                    ? 'border-brandColor bg-brandColor/10 dark:bg-brandColor/20 shadow-xs'
                                    : 'bg-slate-50/80 hover:bg-slate-100/90 border-slate-200/80 dark:bg-gray-800/60 dark:hover:bg-gray-800 dark:border-gray-700/60'
                            ]"
                        >
                            <span :class="[
                                'flex h-8 w-8 shrink-0 items-center justify-center rounded-xl text-xs font-bold transition-all',
                                activeSection === 'payment-milestones'
                                    ? 'bg-brandColor text-white shadow-xs'
                                    : 'bg-slate-200 text-slate-800 dark:bg-gray-700 dark:text-slate-100'
                            ]">3</span>
                            <div class="ml-3 min-w-0">
                                <p :class="[
                                    'text-xs font-bold leading-tight',
                                    activeSection === 'payment-milestones'
                                        ? 'text-brandColor dark:text-brandColor'
                                        : 'text-gray-800 dark:text-white'
                                ]">Payment Milestones</p>
                                <p class="text-[11px] font-semibold text-slate-600 dark:text-slate-300">Tranches & Escrow</p>
                            </div>
                        </button>

                        <!-- Step 4 Button -->
                        <button
                            type="button"
                            @click="scrollToSection('terms-approvals')"
                            :class="[
                                'flex items-center p-3 rounded-xl border text-left transition-all w-full cursor-pointer',
                                activeSection === 'terms-approvals'
                                    ? 'border-brandColor bg-brandColor/10 dark:bg-brandColor/20 shadow-xs'
                                    : 'bg-slate-50/80 hover:bg-slate-100/90 border-slate-200/80 dark:bg-gray-800/60 dark:hover:bg-gray-800 dark:border-gray-700/60'
                            ]"
                        >
                            <span :class="[
                                'flex h-8 w-8 shrink-0 items-center justify-center rounded-xl text-xs font-bold transition-all',
                                activeSection === 'terms-approvals'
                                    ? 'bg-brandColor text-white shadow-xs'
                                    : 'bg-slate-200 text-slate-800 dark:bg-gray-700 dark:text-slate-100'
                            ]">4</span>
                            <div class="ml-3 min-w-0">
                                <p :class="[
                                    'text-xs font-bold leading-tight',
                                    activeSection === 'terms-approvals'
                                        ? 'text-brandColor dark:text-brandColor'
                                        : 'text-gray-800 dark:text-white'
                                ]">Terms & Approvals</p>
                                <p class="text-[11px] font-semibold text-slate-600 dark:text-slate-300">Commercial Sign-off</p>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Section 1: Deal & Client Info (2-Column Cards) -->
                <div id="deal-client-info" class="scroll-mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Card A: Proposal Basics -->
                    <div class="flex flex-col justify-between rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-gray-900">
                        <div>
                            <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-gray-800">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-600 dark:bg-purple-950/80 dark:text-purple-400 flex items-center justify-center font-bold text-sm">
                                        🏢
                                    </span>
                                    <div>
                                        <h3 class="text-sm font-bold text-gray-800 dark:text-white">Proposal Basics</h3>
                                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">Core parameters & deal identifiers</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Ref: Q-{{ $quote->id }}</span>
                            </div>

                            <div class="py-4 space-y-4">
                                <!-- Proposal Subject -->
                                <x-admin::attributes
                                    :custom-attributes="app('Crm\Attribute\Repositories\AttributeRepository')->findWhere([
                                        'entity_type' => 'quotes',
                                        ['code', 'IN', ['subject']],
                                    ])"
                                    :custom-validations="[
                                        'expired_at' => [
                                            'required',
                                            'date_format:yyyy-MM-dd',
                                            'after:' .  \Carbon\Carbon::yesterday()->format('Y-m-d')
                                        ],
                                    ]"
                                    :entity="$quote"
                                />

                                <!-- Description -->
                                <x-admin::attributes
                                    :custom-attributes="app('Crm\Attribute\Repositories\AttributeRepository')->findWhere([
                                            'entity_type' => 'quotes',
                                            ['code', 'IN', ['description']],
                                        ])"
                                    :custom-validations="[
                                        'expired_at' => [
                                            'required',
                                            'date_format:yyyy-MM-dd',
                                            'after:' .  \Carbon\Carbon::yesterday()->format('Y-m-d')
                                        ],
                                    ]"
                                    :entity="$quote"
                                />

                                <!-- Sales Lead & Validity Date -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <x-admin::attributes
                                        :custom-attributes="app('Crm\Attribute\Repositories\AttributeRepository')->findWhere([
                                            'entity_type' => 'quotes',
                                            ['code', 'IN', ['user_id', 'expired_at']],
                                        ])->sortBy('sort_order')"
                                        :custom-validations="[
                                            'expired_at' => [
                                                'required',
                                                'date_format:yyyy-MM-dd',
                                                'after:' .  \Carbon\Carbon::yesterday()->format('Y-m-d')
                                            ],
                                        ]"
                                        :entity="$quote"
                                    />
                                </div>

                                <!-- Custom User Defined Attributes -->
                                <x-admin::attributes
                                    :custom-attributes="app('Crm\Attribute\Repositories\AttributeRepository')->findWhere([
                                        'entity_type' => 'quotes',
                                        'is_user_defined' => 1,
                                    ])->sortBy('sort_order')"
                                    :custom-validations="[
                                        'expired_at' => [
                                            'required',
                                            'date_format:yyyy-MM-dd',
                                            'after:' .  \Carbon\Carbon::yesterday()->format('Y-m-d')
                                        ],
                                    ]"
                                    :entity="$quote"
                                />
                            </div>
                        </div>

                        <div class="p-3 bg-slate-50/90 dark:bg-gray-800/60 rounded-xl border border-slate-200/80 dark:border-gray-800 flex items-center justify-between text-xs font-semibold text-slate-700 dark:text-slate-200">
                            <span>Approval Matrix: <strong class="text-gray-800 dark:text-white font-bold">Tier 2 Commercial</strong></span>
                            <span class="text-emerald-700 dark:text-emerald-400 font-bold flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Standard Terms Applied
                            </span>
                        </div>
                    </div>

                    <!-- Card B: Client & Buyer Profile -->
                    <div class="flex flex-col justify-between rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-gray-900">
                        <div>
                            <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-gray-800">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-950/80 dark:text-blue-400 flex items-center justify-center font-bold text-sm">
                                        👤
                                    </span>
                                    <div>
                                        <h3 class="text-sm font-bold text-gray-800 dark:text-white">Client & Buyer Profile</h3>
                                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">Corporate entity and registered billing address</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-200 border border-emerald-300 dark:border-emerald-800">
                                    KYC Verified
                                </span>
                            </div>

                            <div class="py-4 space-y-4">
                                <!-- Person & Linked Lead Lookup -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <x-admin::attributes
                                        :custom-attributes="app('Crm\Attribute\Repositories\AttributeRepository')->findWhere([
                                            'entity_type' => 'quotes',
                                            ['code', 'IN', ['person_id']],
                                        ])->sortBy('sort_order')"
                                        :custom-validations="[
                                            'expired_at' => [
                                                'required',
                                                'date_format:yyyy-MM-dd',
                                                'after:' .  \Carbon\Carbon::yesterday()->format('Y-m-d')
                                            ],
                                        ]"
                                        :entity="$quote"
                                    />

                                    <x-admin::attributes.edit.lookup />

                                    <x-admin::form.control-group class="w-full">
                                        <x-admin::form.control-group.label class="!font-bold !text-gray-600dark:!text-slate-100">
                                            @lang('admin::app.quotes.create.link-to-lead')
                                        </x-admin::form.control-group.label>

                                        <v-lookup-component
                                            :key="leadEntity.id"
                                            :attribute="{'code': 'lead_id', 'name': 'Lead', 'lookup_type': 'leads'}"
                                            :value="leadEntity"
                                            can-add-new="true"
                                            @lookup-added="setLeadEntity"
                                            @lookup-removed="setLeadEntity"
                                        ></v-lookup-component>
                                    </x-admin::form.control-group>
                                </div>

                                <!-- Billing Address -->
                                <x-admin::attributes
                                    :custom-attributes="app('Crm\Attribute\Repositories\AttributeRepository')->findWhere([
                                        'entity_type' => 'quotes',
                                        ['code', 'IN', ['billing_address']],
                                    ])"
                                    :custom-validations="[
                                        'billing_address' => [
                                            'max:100',
                                        ],
                                    ]"
                                    :entity="$quote"
                                />

                                <!-- Shipping Address Same As Billing Address Switch -->
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label class="!text-xs !font-bold !text-gray-600dark:!text-slate-100">
                                        @lang('admin::app.quotes.create.same-as-billing')
                                    </x-admin::form.control-group.label>

                                    <input
                                        type="hidden"
                                        name="shipping_address_same_as_billing"
                                        :value="0"
                                    />

                                    <x-admin::form.control-group.control
                                        type="switch"
                                        name="shipping_address_same_as_billing"
                                        value="1"
                                        :label="trans('admin::app.quotes.create.same-as-billing')"
                                        :checked="(bool) (old('shipping_address_same_as_billing') ?? (! empty($quote->shipping_address) && $quote->shipping_address == $quote->billing_address))"
                                        @change="sameAsBilling = $event.target.checked"
                                    />
                                </x-admin::form.control-group>

                                <!-- Shipping Address (if different) -->
                                <template v-if="! sameAsBilling">
                                    <x-admin::attributes
                                        :custom-attributes="app('Crm\Attribute\Repositories\AttributeRepository')->findWhere([
                                            'entity_type' => 'quotes',
                                            ['code', 'IN', ['shipping_address']],
                                        ])"
                                        :custom-validations="[
                                            'shipping_address' => [
                                                'max:100',
                                            ],
                                        ]"
                                        :entity="$quote"
                                    />
                                </template>
                            </div>
                        </div>

                        <div class="p-3 bg-slate-50/90 dark:bg-gray-800/60 rounded-xl border border-slate-200/80 dark:border-gray-800 flex items-center justify-between text-xs font-semibold text-slate-700 dark:text-slate-200">
                            <span>Buyer Entity: <strong class="text-gray-800 dark:text-white font-bold">{{ $quote->person?->organization?->name ?? 'Direct Buyer' }}</strong></span>
                            <span class="text-emerald-700 dark:text-emerald-400 font-bold">GST & RERA Compliant</span>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Property Units & Commercial Pricing Table -->
                <div id="units-pricing" class="scroll-mt-6 rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 dark:border-gray-800 gap-2">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 dark:bg-amber-950/80 dark:text-amber-400 flex items-center justify-center font-bold text-sm">
                                📑
                            </span>
                            <div>
                                <h3 class="text-sm font-bold text-gray-800 dark:text-white">Property Units & Commercial Pricing</h3>
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">Breakdown of inventory units, base rates, discounts, and applicable taxes</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-800 dark:bg-gray-800 dark:text-slate-200 border border-slate-300 dark:border-gray-700">
                            INR Currency (Base + GST)
                        </span>
                    </div>

                    <div class="pt-4">
                        <v-quote-item-list
                            :errors="errors"
                            :lead-entity="leadEntity"
                            @navigate-step="scrollToSection"
                        ></v-quote-item-list>
                    </div>
                </div>
            </div>
        </script>

        <script
            type="text/x-template"
            id="v-quote-item-list-template"
        >
            <div class="flex flex-col gap-6">
                <!-- Products Table Container -->
                <div class="overflow-x-auto rounded-xl border border-slate-200/90 dark:border-gray-800">
                    <x-admin::table>
                        <!-- Table Head -->
                        <x-admin::table.thead>
                            <x-admin::table.thead.tr class="bg-slate-100/90 dark:bg-gray-800/80 text-gray-800 dark:text-white font-extrabold text-xs uppercase tracking-wider">
                                <x-admin::table.th class="py-3.5 px-4 font-bold">
                                    @lang('admin::app.quotes.create.product-name')
                                </x-admin::table.th>

                                <x-admin::table.th class="py-3.5 px-3 text-center font-bold">
                                    @lang('admin::app.quotes.create.quantity')
                                </x-admin::table.th>

                                <x-admin::table.th class="py-3.5 px-3 text-center font-bold">
                                    @lang('admin::app.quotes.create.price')
                                </x-admin::table.th>

                                <x-admin::table.th class="py-3.5 px-3 text-center font-bold">
                                    @lang('admin::app.quotes.create.amount')
                                </x-admin::table.th>

                                <x-admin::table.th class="py-3.5 px-3 text-center font-bold">
                                    @lang('admin::app.quotes.create.discount')
                                </x-admin::table.th>

                                <x-admin::table.th class="py-3.5 px-3 text-center font-bold">
                                    @lang('admin::app.quotes.create.tax')
                                </x-admin::table.th>

                                <x-admin::table.th class="py-3.5 px-3 text-center font-bold">
                                    @lang('admin::app.quotes.create.total')
                                </x-admin::table.th>

                                <x-admin::table.th
                                    v-if="products.length > 1"
                                    class="py-3.5 px-4 text-center font-bold"
                                >
                                    @lang('admin::app.quotes.create.action')
                                </x-admin::table.th>
                            </x-admin::table.thead.tr>
                        </x-admin::table.thead>

                        <!-- Table Body -->
                        <x-admin::table.tbody>
                            <template
                                v-for='(product, index) in products'
                                :key="index"
                            >
                                <v-quote-item
                                    :product="product"
                                    :index="index"
                                    :errors="errors"
                                    @onRemoveProduct="removeProduct($event)"
                                ></v-quote-item>
                            </template>
                        </x-admin::table.tbody>
                    </x-admin::table>
                    <x-admin::form.control-group.error name="items"/>
                </div>

                <!-- Add New Quote Item Action -->
                <div class="flex items-center justify-between">
                    <button
                        type="button"
                        class="secondary-button !px-4 !py-2.5 !rounded-xl !text-xs font-bold transition active:scale-95 shadow-2xs gap-2"
                        @click="addProduct"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>@lang('admin::app.quotes.create.add-item')</span>
                    </button>

                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Total @{{ products.length }} Inventory Unit(s) configured</span>
                </div>

                <!-- Section 3 & 4 Grid: Payment Milestones & Net Financial Summary -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start pt-6 border-t border-slate-200 dark:border-gray-800">
                    <!-- Section 3: Scheduled Milestone Tranches Preview (7 Cols) -->
                    <div id="payment-milestones" class="scroll-mt-6 lg:col-span-7 bg-slate-50/90 dark:bg-gray-800/60 rounded-2xl border border-slate-200/90 dark:border-gray-800 p-5 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-200/80 dark:border-gray-700/80">
                            <div class="flex items-center gap-2.5">
                                <span class="w-7 h-7 rounded-xl bg-purple-100 text-purple-700 dark:bg-purple-950 dark:text-purple-300 flex items-center justify-center font-bold text-xs">
                                    ₹
                                </span>
                                <h4 class="text-xs font-bold text-gray-800 dark:text-white uppercase tracking-wider">Scheduled Milestone Tranches</h4>
                            </div>
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">3 Installments</span>
                        </div>

                        <!-- Milestone Cards -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3.5 rounded-xl border border-slate-200/90 bg-white dark:border-gray-700 dark:bg-gray-900 shadow-2xs">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-200 font-bold text-xs flex items-center justify-center">1</span>
                                    <div>
                                        <p class="text-xs font-bold text-gray-800 dark:text-white">Booking Token (10%)</p>
                                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">Payable immediately upon LOI acceptance</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400">Due in 7 days</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-3.5 rounded-xl border border-slate-200/90 bg-white dark:border-gray-700 dark:bg-gray-900 shadow-2xs">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-xl bg-slate-200 text-slate-800 dark:bg-gray-800 dark:text-slate-200 font-bold text-xs flex items-center justify-center">2</span>
                                    <div>
                                        <p class="text-xs font-bold text-gray-800 dark:text-white">Sale Agreement Execution (20%)</p>
                                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">Upon registered agreement & stamp duty</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Due in 30 days</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-3.5 rounded-xl border border-slate-200/90 bg-white dark:border-gray-700 dark:bg-gray-900 shadow-2xs">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-xl bg-slate-200 text-slate-800 dark:bg-gray-800 dark:text-slate-200 font-bold text-xs flex items-center justify-center">3</span>
                                    <div>
                                        <p class="text-xs font-bold text-gray-800 dark:text-white">Handover & Possession (70%)</p>
                                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">Upon occupancy certificate issuance & key handover</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Final Handover</span>
                                </div>
                            </div>
                        </div>

                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-300 text-center pt-1">All payments to be remitted to the project designated Escrow account.</p>
                    </div>

                    <!-- Section 4: Terms, Approvals & Net Financial Summary Box (5 Cols) -->
                    <div id="terms-approvals" class="scroll-mt-6 lg:col-span-5 bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/90 dark:border-gray-800 shadow-xs overflow-hidden">
                        <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-200">Net Financial Summary</span>
                            <span class="text-xs font-bold text-purple-400 bg-slate-800 px-2.5 py-1 rounded">All Taxes Included</span>
                        </div>

                        <div class="p-5 space-y-4 text-xs text-slate-700 dark:text-slate-200">
                            <!-- Base Subtotal -->
                            <div class="flex justify-between items-center font-bold">
                                <span>@lang('admin::app.quotes.create.sub-total', ['symbol' => core()->currencySymbol(config('app.currency'))])</span>
                                <input type="hidden" name="sub_total" :value="subTotal" readonly>
                                <span class="font-bold text-gray-800 dark:text-white text-sm tabular-nums">@{{ subTotal }}</span>
                            </div>

                            <!-- Discount Amount -->
                            <div class="flex justify-between items-center font-bold">
                                <span>@lang('admin::app.quotes.create.total-discount', ['symbol' => core()->currencySymbol(config('app.currency'))])</span>
                                <input type="hidden" name="discount_amount" :value="discountAmount">
                                <span class="font-bold text-emerald-600 dark:text-emerald-400 text-sm tabular-nums">-@{{ discountAmount }}</span>
                            </div>

                            <!-- Tax Amount -->
                            <div class="flex justify-between items-center font-bold">
                                <span>@lang('admin::app.quotes.create.total-tax', ['symbol' => core()->currencySymbol(config('app.currency'))])</span>
                                <input type="hidden" name="tax_amount" :value="taxAmount">
                                <span class="font-bold text-gray-800 dark:text-white text-sm tabular-nums">+@{{ taxAmount }}</span>
                            </div>

                            <!-- Adjustment Amount -->
                            <div class="flex justify-between items-center font-bold">
                                <span>@lang('admin::app.quotes.create.total-adjustment', ['symbol' => core()->currencySymbol(config('app.currency'))])</span>
                                <div class="w-32">
                                    <x-admin::form.control-group.control
                                        type="inline"
                                        ::name="`adjustment_amount`"
                                        ::value="adjustmentAmount"
                                        rules="required|decimal:4"
                                        ::errors="errors"
                                        :label="trans('admin::app.quotes.create.adjustment-amount')"
                                        :placeholder="trans('admin::app.quotes.create.adjustment-amount')"
                                        @on-change="handleAdjustmentAmountChange"
                                    />
                                </div>
                            </div>

                            <!-- Net Grand Total -->
                            <div class="border-t border-slate-200 dark:border-gray-800 pt-3.5">
                                <div class="flex justify-between items-baseline">
                                    <span class="text-sm font-bold text-gray-800 dark:text-white">Net Payable Amount</span>
                                    <input type="hidden" name="grand_total" :value="grandTotal">
                                    <span class="text-2xl sm:text-3xl font-bold text-purple-600 dark:text-purple-400 tracking-tight tabular-nums">
                                        @{{ grandTotal }}
                                    </span>
                                </div>
                            </div>

                            <!-- Commercial & Legal Note -->
                            <div class="rounded-xl bg-slate-50 dark:bg-gray-800/60 p-3.5 text-xs font-medium text-slate-700 dark:text-slate-300 leading-relaxed border border-slate-200/80 dark:border-gray-800">
                                <strong class="font-bold text-gray-800 dark:text-white">Commercial Sign-off Note:</strong> Registration charges, stamp duty, and society maintenance fees are payable as per actuals.
                            </div>

                            <!-- Action Button Inside Summary -->
                            <div class="pt-2">
                                <button
                                    type="submit"
                                    class="primary-button w-full !py-3 !px-4 !rounded-xl !text-xs font-bold tracking-wide shadow-md active:scale-98 transition-all gap-2 cursor-pointer"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Approve & Save Proposal</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </script>

        <script
            type="text/x-template"
            id="v-quote-item-template"
        >
            <x-admin::table.thead.tr class="border-b border-slate-200/80 dark:border-gray-800 hover:bg-slate-50 dark:hover:bg-gray-800/40 transition">
                <!-- Quote Product Name -->
                <x-admin::table.td class="py-3.5 px-4 font-semibold text-gray-800 dark:text-white">
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::lookup
                            ::src="src"
                            ::name="`${inputName}[product_id]`"
                            ::params="params"
                            ::value="{ id: product.product_id, name: product.name }"
                            @on-selected="(product) => addProduct(product)"
                            :placeholder="trans('admin::app.quotes.edit.search-products')"
                            rules="required"
                            :label="trans('admin::app.quotes.edit.product-name')"
                            ::class="errors[`${inputName}[product_id]`] ? 'border !border-red-600 hover:border-red-600' : ''"
                        />
                        <x-admin::form.control-group.error name="`items.${product.id}.product_id`"/>
                        <x-admin::form.control-group.error ::name="`${inputName}[product_id]`"/>
                    </x-admin::form.control-group>
                </x-admin::table.td>

                <!-- Quantity -->
                <x-admin::table.td class="!px-2 ltr:text-right rtl:text-left">
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.control
                            type="inline"
                            ::name="`${inputName}[quantity]`"
                            ::value="product.quantity"
                            rules="required|decimal:4"
                            ::errors="errors"
                            :label="trans('admin::app.quotes.create.quantity')"
                            :placeholder="trans('admin::app.quotes.create.quantity')"
                            @on-change="(event) => product.quantity = event.value"
                            position="center"
                        />
                        <x-admin::form.control-group.error ::name="`items.${product.id}.quantity`"/>
                    </x-admin::form.control-group>
                </x-admin::table.td>

                <!-- Price -->
                <x-admin::table.td class="!px-2 ltr:text-right rtl:text-left">
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.control
                            type="inline"
                            ::name="`${inputName}[price]`"
                            ::value="(product.price) ?? 0"
                            rules="required|decimal:4"
                            ::errors="errors"
                            :label="trans('admin::app.quotes.create.price')"
                            :placeholder="trans('admin::app.quotes.create.price')"
                            @on-change="(event) => product.price = event.value"
                            position="center"
                            ::value-label="$admin.formatPrice(product.price)"
                        />
                        <x-admin::form.control-group.error name="`items.${product.id}.price`"/>
                        <x-admin::form.control-group.error ::name="`${inputName}[price]`"/>
                    </x-admin::form.control-group>
                </x-admin::table.td>

                <!-- Total -->
                <x-admin::table.td class="!px-2 ltr:text-right rtl:text-left">
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.control
                            type="inline"
                            ::name="`${inputName}[total]`"
                            ::value="(product.price * product.quantity) ?? 0"
                            rules="required|decimal:4"
                            ::errors="errors"
                            :label="trans('admin::app.quotes.create.total')"
                            :placeholder="trans('admin::app.quotes.create.total')"
                            :allowEdit="false"
                            position="center"
                            ::value-label="$admin.formatPrice(product.price * product.quantity)"
                        />
                        <x-admin::form.control-group.error name="`items.${product.id}.total`"/>
                        <x-admin::form.control-group.error ::name="`${inputName}[total]`"/>
                    </x-admin::form.control-group>
                </x-admin::table.td>

                <!-- Discount Amount -->
                <x-admin::table.td class="!px-2 ltr:text-right rtl:text-left">
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.control
                            type="inline"
                            ::name="`${inputName}[discount_amount]`"
                            ::value="product.discount_amount"
                            rules="required|decimal:4"
                            ::errors="errors"
                            :label="trans('admin::app.quotes.create.discount-amount')"
                            :placeholder="trans('admin::app.quotes.create.discount-amount')"
                            @on-change="(event) => product.discount_amount = event.value"
                            position="center"
                            ::value-label="$admin.formatPrice(product.discount_amount)"
                        />
                        <x-admin::form.control-group.error name="`items.${product.id}.discount_amount`"/>
                        <x-admin::form.control-group.error ::name="`${inputName}[discount_amount]`"/>
                    </x-admin::form.control-group>
                </x-admin::table.td>

                <!-- Tax Amount -->
                <x-admin::table.td class="!px-2 ltr:text-right rtl:text-left">
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.control
                            type="inline"
                            ::name="`${inputName}[tax_amount]`"
                            ::value="product.tax_amount"
                            rules="required|decimal:4"
                            ::errors="errors"
                            :label="trans('admin::app.quotes.create.tax-amount')"
                            :placeholder="trans('admin::app.quotes.create.tax-amount')"
                            @on-change="(event) => product.tax_amount = event.value"
                            position="center"
                            ::value-label="$admin.formatPrice(product.tax_amount)"
                        />
                        <x-admin::form.control-group.error name="`items.${product.id}.tax_amount`"/>
                        <x-admin::form.control-group.error ::name="`${inputName}[tax_amount]`"/>
                    </x-admin::form.control-group>
                </x-admin::table.td>

                <!-- Total with Discount -->
                <x-admin::table.td class="!px-2 ltr:text-right rtl:text-left">
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.control
                            type="inline"
                            ::name="`${inputName}[final_total]`"
                            ::errors="errors"
                            ::value="parseFloat(product.price * product.quantity) + parseFloat(product.tax_amount) - parseFloat(product.discount_amount)"
                            :allowEdit="false"
                            position="center"
                            ::value-label="$admin.formatPrice(parseFloat(product.price * product.quantity) + parseFloat(product.tax_amount) - parseFloat(product.discount_amount))"
                        />
                        <x-admin::form.control-group.error name="`items.${product.id}.final_total`"/>
                        <x-admin::form.control-group.error ::name="`${inputName}[final_total]`"/>
                    </x-admin::form.control-group>
                </x-admin::table.td>

                <!-- Action -->
                <x-admin::table.td
                    v-if="$parent.products.length > 1"
                    class="py-3 px-3 text-center"
                >
                    <button
                        type="button"
                        @click="removeProduct"
                        class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition dark:hover:bg-rose-950/50 cursor-pointer"
                        title="Remove Unit"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </x-admin::table.td>
            </x-admin::table.thead.tr>
        </script>

        <script type="module">
            app.component('v-quote', {
                template: '#v-quote-template',

                props: ['errors'],

                data() {
                    return {
                        activeSection: 'deal-client-info',

                        leadEntity: @json($lookUpEntityData ?? []),

                        sameAsBilling: {{ (old('shipping_address_same_as_billing') ?? (! empty($quote->shipping_address) && $quote->shipping_address == $quote->billing_address)) ? 'true' : 'false' }},
                    };
                },

                methods: {
                    /**
                     * Scroll smoothly to section with offset.
                     *
                     * @param {String} sectionId
                     */
                    scrollToSection(sectionId) {
                        this.activeSection = sectionId;
                        const element = document.getElementById(sectionId);

                        if (element) {
                            const offset = 90;
                            const bodyRect = document.body.getBoundingClientRect().top;
                            const elementRect = element.getBoundingClientRect().top;
                            const elementPosition = elementRect - bodyRect;
                            const offsetPosition = elementPosition - offset;

                            window.scrollTo({
                                top: offsetPosition,
                                behavior: 'smooth'
                            });
                        }
                    },

                    setLeadEntity($event) {
                        this.leadEntity = $event ?? { id: '', name: '' };
                    },
                },
            });

            app.component('v-quote-item-list', {
                template: '#v-quote-item-list-template',

                props: ['errors', 'leadEntity'],

                emits: ['navigate-step'],

                data() {
                    return {
                        adjustmentAmount: '0.0000',

                        products: @json($leadProducts ?? $quote->items ?? []),
                    }
                },

                watch: {
                    'leadEntity.id': function(newLeadId, oldLeadId) {
                        if (newLeadId === oldLeadId) {
                            return;
                        }

                        if (! newLeadId) {
                            this.products = [];

                            return;
                        }

                        this.fetchLeadProducts(newLeadId);
                    },
                },

                computed: {
                    /**
                     * Calculate the sub total of the products.
                     *
                     * @returns {Number}
                     */
                    subTotal() {
                        const total = this.products.reduce((carry, product) => {
                            return carry + this.getProductBaseTotal(product);
                        }, 0);

                        return this.formatDecimal(total);
                    },

                    /**
                     * Calculate the total discount amount of the products.
                     *
                     * @returns {Number}
                     */
                    discountAmount() {
                        const total = this.products.reduce((carry, product) => {
                            return carry + this.parseDecimal(product.discount_amount);
                        }, 0);

                        return this.formatDecimal(total);
                    },

                    /**
                     * Calculate the total tax amount of the products.
                     *
                     * @returns {Number}
                     */
                    taxAmount() {
                        const total = this.products.reduce((carry, product) => {
                            return carry + this.parseDecimal(product.tax_amount);
                        }, 0);

                        return this.formatDecimal(total);
                    },

                    /**
                     * Calculate the grand total of the products.
                     *
                     * @returns {Number}
                     */
                    grandTotal() {
                        const itemsTotal = this.products.reduce((carry, product) => {
                            return carry
                                + this.getProductBaseTotal(product)
                                + this.parseDecimal(product.tax_amount)
                                - this.parseDecimal(product.discount_amount);
                        }, 0);

                        return this.formatDecimal(itemsTotal + this.parseDecimal(this.adjustmentAmount));
                    },
                },

                methods: {
                    /**
                     * Parse decimal-like values safely.
                     *
                     * @param {Number|String|null} value
                     *
                     * @returns {Number}
                     */
                    parseDecimal(value) {
                        const parsedValue = Number.parseFloat(value);

                        return Number.isFinite(parsedValue) ? parsedValue : 0;
                    },

                    /**
                     * Format numeric values as fixed decimals.
                     *
                     * @param {Number|String|null} value
                     *
                     * @returns {String}
                     */
                    formatDecimal(value) {
                        return this.parseDecimal(value).toFixed(4);
                    },

                    /**
                     * Calculate product line subtotal.
                     *
                     * @param {Object} product
                     *
                     * @returns {Number}
                     */
                    getProductBaseTotal(product) {
                        return this.parseDecimal(product.price) * this.parseDecimal(product.quantity);
                    },

                    /**
                     * Keep adjustment amount stored as a fixed decimal string.
                     *
                     * @param {Object} event
                     *
                     * @returns {void}
                     */
                    handleAdjustmentAmountChange(event) {
                        this.adjustmentAmount = this.formatDecimal(event.value);
                    },

                    /**
                     * Fetch and replace items with selected lead products.
                     *
                     * @param {Number|String} leadId
                     *
                     * @returns {void}
                     */
                    fetchLeadProducts(leadId) {
                        this.$axios
                            .get("{{ route('admin.quotes.lead_products', '__LEAD_ID__') }}".replace('__LEAD_ID__', leadId))
                            .then((response) => {
                                const leadProducts = response.data?.data ?? [];

                                this.products = leadProducts;

                                this.$emitter.emit('add-flash', {
                                    type: leadProducts.length ? 'success' : 'info',
                                    message: leadProducts.length
                                        ? 'Lead products assigned to quote. See items section.'
                                        : 'No products found for selected lead.',
                                });
                            })
                            .catch((error) => {
                                this.$emitter.emit('add-flash', {
                                    type: 'error',
                                    message: error?.response?.data?.message || 'Unable to fetch lead products.',
                                });
                            });
                    },

                    /**
                     * Add a new product.
                     *
                     * @returns {void}
                     */
                    addProduct() {
                        this.products.push({
                            id: null,
                            product_id: null,
                            name: '',
                            quantity: 1,
                            total: '0.0000',
                            price: '0.0000',
                            discount_amount: '0.0000',
                            tax_amount: '0.0000',
                        });
                    },

                    /**
                     * Remove the product.
                     *
                     * @param {Object} product
                     */
                    removeProduct(product) {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                if (this.products.length === 1) {
                                    this.products = [{
                                        id: null,
                                        product_id: null,
                                        name: '',
                                        quantity: null,
                                        total: 0,
                                        price: null,
                                        discount_amount: null,
                                        tax_amount: null,
                                    }];
                                } else {
                                    const index = this.products.indexOf(product);

                                    if (index !== -1) {
                                        this.products.splice(index, 1);
                                    }
                                }
                            },
                        });
                    },
                },
            });

            app.component('v-quote-item', {
                template: '#v-quote-item-template',

                props: ['index', 'product', 'errors'],

                data() {
                    return {
                        state: this.product['product_id'] ? 'old' : '',

                        products: [],
                    }
                },

                computed: {
                    /**
                     * Get the input name.
                     *
                     * @returns {String}
                     */
                    inputName() {
                        if (this.product.id) {
                            return "items[" + this.product.id + "]";
                        }

                        return "items[item_" + this.index + "]";
                    },

                    /**
                     * Get the source URL.
                     *
                     * @returns {String}
                     */
                    src() {
                        return "{{ route('admin.products.search') }}";
                    },

                    params() {
                        return {
                            params: {
                                query: this.product.name,
                            },
                        };
                    },
                },

                methods: {
                    /**
                     * Add the product.
                     *
                     * @param {Object} result
                     *
                     * @return {void}
                     */
                    addProduct(result) {
                        this.product.product_id = result.id;
                        this.product.name = result.name;
                        this.product.price = result.price ?? 0;
                        this.product.quantity = result.quantity ?? 1;
                        this.product.discount_amount = 0;
                        this.product.tax_amount = 0;
                    },

                    /**
                     * Remove the product.
                     *
                     * @return {void}
                     */
                    removeProduct() {
                        this.$emit('onRemoveProduct', this.product);
                    },
                },
            });
        </script>
    @endPushOnce

    @pushOnce('styles')
        <style>
            html {
                scroll-behavior: smooth;
            }
        </style>
    @endPushOnce
</x-admin::layouts>