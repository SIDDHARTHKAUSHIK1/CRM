<!-- Stages Navigation -->
{!! view_render_event('admin.leads.view.stages.before', ['lead' => $lead]) !!}

<!-- Stages Vue Component -->
<v-lead-stages>
    <x-admin::shimmer.leads.view.stages :count="$lead->pipeline->stages->count() - 1" />
</v-lead-stages>

{!! view_render_event('admin.leads.view.stages.after', ['lead' => $lead]) !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-lead-stages-template">
        <!-- Stages Container -->
        <div
            class="flex flex-col gap-3 rounded-2xl border border-slate-200/90 bg-white p-4.5 shadow-xs dark:border-gray-800 dark:bg-gray-900"
            :class="{'opacity-50 pointer-events-none': isUpdating}"
        >
            <!-- Top Action Header -->
            <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-100 dark:border-gray-800">
                <div class="flex items-center gap-2.5">
                    <span
                        class="px-2.5 py-0.5 rounded-full text-[11px] font-bold tracking-wider uppercase"
                        :class="{
                            'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/70 dark:text-emerald-300': currentStage.code == 'won',
                            'bg-rose-100 text-rose-800 dark:bg-rose-950/70 dark:text-rose-300': currentStage.code == 'lost',
                            'bg-amber-100 text-amber-800 dark:bg-amber-950/70 dark:text-amber-300': !['won', 'lost'].includes(currentStage.code),
                        }"
                    >
                        @{{ currentStage.code == 'won' ? 'DEAL WON 🎉' : (currentStage.code == 'lost' ? 'DEAL LOST' : 'DEAL IN PROGRESS') }}
                    </span>
                    <span class="text-xs text-slate-400 font-medium">Pipeline: {{ $lead->pipeline->name }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <!-- Mark as Lost Button (if not lost) -->
                    <button
                        type="button"
                        v-if="currentStage.code !== 'lost'"
                        v-for="lostStage in stages.filter(s => s.code === 'lost')"
                        @click="openModal(lostStage)"
                        class="px-3.5 py-1.5 bg-white hover:bg-rose-50 border border-slate-200 text-slate-600 hover:text-rose-600 hover:border-rose-200 text-xs font-semibold rounded-xl shadow-2xs transition active:scale-95 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-rose-950/40 dark:hover:text-rose-300"
                    >
                        Mark as Lost
                    </button>

                    <!-- Mark as Won Button (if not won) -->
                    <button
                        type="button"
                        v-if="currentStage.code !== 'won'"
                        v-for="wonStage in stages.filter(s => s.code === 'won')"
                        @click="openModal(wonStage)"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-xs shadow-emerald-600/30 transition active:scale-95"
                    >
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" fill-rule="evenodd"></path>
                        </svg>
                        <span>Mark as Won</span>
                    </button>
                </div>
            </div>

            <!-- Stages Stepper Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2 pt-1">
                <template v-for="(stage, index) in stages.filter(s => !['won', 'lost'].includes(s.code))">
                    {!! view_render_event('admin.leads.view.stages.items.before', ['lead' => $lead]) !!}

                    <div
                        class="flex items-center justify-between p-2.5 rounded-xl border cursor-pointer transition-all duration-150"
                        :class="{
                            'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-950/40 dark:border-emerald-900/60 dark:text-emerald-300': currentStage.sort_order > stage.sort_order && currentStage.code !== 'lost',
                            'bg-brandColor text-white font-bold shadow-md ring-2 ring-brandColor/40 ring-offset-1 border-transparent dark:ring-offset-gray-900': currentStage.id == stage.id,
                            'bg-slate-50 border-slate-200/90 text-slate-500 hover:bg-slate-100/80 hover:border-slate-300 dark:bg-gray-800/60 dark:border-gray-700 dark:text-slate-400': currentStage.id != stage.id && (currentStage.sort_order <= stage.sort_order || currentStage.code === 'lost'),
                        }"
                        @click="update(stage)"
                    >
                        <div class="flex items-center gap-2 min-w-0">
                            <span
                                class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[11px] font-bold"
                                :class="{
                                    'bg-emerald-600 text-white': currentStage.sort_order > stage.sort_order && currentStage.code !== 'lost',
                                    'bg-white text-brandColor font-bold': currentStage.id == stage.id,
                                    'bg-slate-200 text-slate-600 dark:bg-gray-700 dark:text-slate-300': currentStage.id != stage.id && (currentStage.sort_order <= stage.sort_order || currentStage.code === 'lost'),
                                }"
                            >
                                <template v-if="currentStage.sort_order > stage.sort_order && currentStage.code !== 'lost'">✓</template>
                                <template v-else>@{{ index + 1 }}</template>
                            </span>
                            <span class="text-xs font-semibold truncate">@{{ stage.name }}</span>
                        </div>
                        <span class="text-[10px] hidden sm:inline" :class="currentStage.id == stage.id ? 'bg-white/20 px-1.5 py-0.5 rounded text-white font-semibold' : 'text-slate-400'">
                            @{{ currentStage.id == stage.id ? 'Active' : (currentStage.sort_order > stage.sort_order ? 'Done' : '') }}
                        </span>
                    </div>

                    {!! view_render_event('admin.leads.view.stages.items.after', ['lead' => $lead]) !!}
                </template>

                <!-- Won/Lost Stage Item -->
                <div class="relative">
                    {!! view_render_event('admin.leads.view.stages.items.dropdown.before', ['lead' => $lead]) !!}

                    <x-admin::dropdown position="bottom-right">
                        <x-slot:toggle>
                            {!! view_render_event('admin.leads.view.stages.items.dropdown.toggle.before', ['lead' => $lead]) !!}

                            <div
                                class="flex items-center justify-between p-2.5 rounded-xl border cursor-pointer transition-all duration-150"
                                :class="{
                                    'bg-emerald-600 text-white font-bold border-transparent shadow-md shadow-emerald-600/25': currentStage.code == 'won',
                                    'bg-rose-600 text-white font-bold border-transparent shadow-md shadow-rose-600/25': currentStage.code == 'lost',
                                    'bg-slate-50 border-slate-200/90 text-slate-500 hover:bg-slate-100/80 dark:bg-gray-800/60 dark:border-gray-700 dark:text-slate-400': !['won', 'lost'].includes(currentStage.code),
                                }"
                                @click="stageToggler = ! stageToggler"
                            >
                                <div class="flex items-center gap-2 min-w-0">
                                    <span
                                        class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[11px] font-bold"
                                        :class="['won', 'lost'].includes(currentStage.code) ? 'bg-white text-emerald-700' : 'bg-slate-200 text-slate-600 dark:bg-gray-700 dark:text-slate-300'"
                                    >
                                        🏆
                                    </span>
                                    <span class="text-xs font-semibold truncate">
                                        @{{ ['won', 'lost'].includes(currentStage.code) ? currentStage.name : 'Won / Lost' }}
                                    </span>
                                </div>
                                <span class="text-xs">▾</span>
                            </div>

                            {!! view_render_event('admin.leads.view.stages.items.dropdown.toggle.after', ['lead' => $lead]) !!}
                        </x-slot>

                        <x-slot:menu>
                            {!! view_render_event('admin.leads.view.stages.items.dropdown.menu_item.before', ['lead' => $lead]) !!}

                            <x-admin::dropdown.menu.item
                                v-for="stage in stages.filter(stage => ['won', 'lost'].includes(stage.code))"
                                @click="openModal(stage)"
                            >
                                @{{ stage.name }}
                            </x-admin::dropdown.menu.item>

                            {!! view_render_event('admin.leads.view.stages.items.dropdown.menu_item.after', ['lead' => $lead]) !!}
                        </x-slot>
                    </x-admin::dropdown>

                    {!! view_render_event('admin.leads.view.stages.items.dropdown.after', ['lead' => $lead]) !!}
                </div>
            </div>

            {!! view_render_event('admin.leads.view.stages.form_controls.before', ['lead' => $lead]) !!}

            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="stageUpdateForm"
            >
                <form @submit="handleSubmit($event, handleFormSubmit)">
                    {!! view_render_event('admin.leads.view.stages.form_controls.modal.before', ['lead' => $lead]) !!}

                    <x-admin::modal ref="stageUpdateModal">
                        <x-slot:header>
                            {!! view_render_event('admin.leads.view.stages.form_controls.modal.header.before', ['lead' => $lead]) !!}

                            <h3 class="text-base font-semibold dark:text-white">
                                @lang('admin::app.leads.view.stages.need-more-info')
                            </h3>

                            {!! view_render_event('admin.leads.view.stages.form_controls.modal.header.after', ['lead' => $lead]) !!}
                        </x-slot>

                        <x-slot:content>
                            {!! view_render_event('admin.leads.view.stages.form_controls.modal.content.before', ['lead' => $lead]) !!}

                            <template v-if="nextStage">
                                <!-- Won Value -->
                                <template v-if="nextStage.code == 'won'">
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.leads.view.stages.won-value')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="price"
                                            name="lead_value"
                                            :value="$lead->lead_value"
                                            v-model="nextStage.lead_value"
                                        />
                                    </x-admin::form.control-group>
                                </template>

                                <!-- Lost Reason -->
                                <template v-else>
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.leads.view.stages.lost-reason')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="textarea"
                                            name="lost_reason"
                                            v-model="nextStage.lost_reason"
                                        />
                                    </x-admin::form.control-group>
                                </template>

                                <!-- Closed At -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.leads.view.stages.closed-at')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="datetime"
                                        name="closed_at"
                                        v-model="nextStage.closed_at"
                                        :label="trans('admin::app.leads.view.stages.closed-at')"
                                    />

                                    <x-admin::form.control-group.error control-name="closed_at"/>
                                </x-admin::form.control-group>
                            </template>

                            {!! view_render_event('admin.leads.view.stages.form_controls.modal.content.after', ['lead' => $lead]) !!}
                        </x-slot>

                        <x-slot:footer>
                            {!! view_render_event('admin.leads.view.stages.form_controls.modal.footer.before', ['lead' => $lead]) !!}

                            <button
                                type="submit"
                                class="primary-button"
                            >
                                @lang('admin::app.leads.view.stages.save-btn')
                            </button>

                            {!! view_render_event('admin.leads.view.stages.form_controls.modal.footer.after', ['lead' => $lead]) !!}
                        </x-slot>
                    </x-admin::modal>

                    {!! view_render_event('admin.leads.view.stages.form_controls.modal.after', ['lead' => $lead]) !!}
                </form>
            </x-admin::form>

            {!! view_render_event('admin.leads.view.stages.form_controls.after', ['lead' => $lead]) !!}
        </div>
    </script>

    <script type="module">
        app.component('v-lead-stages', {
            template: '#v-lead-stages-template',

            data() {
                return {
                    isUpdating: false,

                    currentStage: @json($lead->stage),

                    nextStage: null,

                    stages: @json($lead->pipeline->stages),

                    stageToggler: '',
                }
            },

            methods: {
                openModal(stage) {
                    if (this.currentStage.code == stage.code) {
                        return;
                    }

                    this.nextStage = stage;

                    this.$refs.stageUpdateModal.open();
                },

                handleFormSubmit(event) {
                    let params = {
                        'lead_pipeline_stage_id': this.nextStage.id
                    };

                    if (this.nextStage.code == 'won') {
                        params.lead_value = this.nextStage.lead_value;

                        params.closed_at = this.nextStage.closed_at;
                    } else if (this.nextStage.code == 'lost') {
                        params.lost_reason = this.nextStage.lost_reason;

                        params.closed_at = this.nextStage.closed_at;
                    }

                    this.update(this.nextStage, params);
                },

                update(stage, params = null) {
                    if (this.currentStage.code == stage.code) {
                        return;
                    }

                    this.$refs.stageUpdateModal.close();

                    this.isUpdating = true;

                    this.$axios
                        .put("{{ route('admin.leads.stage.update', $lead->id) }}", params ?? {
                            'lead_pipeline_stage_id': stage.id
                        })
                        .then ((response) => {
                            this.isUpdating = false;

                            this.currentStage = stage;

                            this.$parent.$refs.activities.get();

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        })
                        .catch ((error) => {
                            this.isUpdating = false;

                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                        });
                },
            },
        });
    </script>
@endPushOnce
