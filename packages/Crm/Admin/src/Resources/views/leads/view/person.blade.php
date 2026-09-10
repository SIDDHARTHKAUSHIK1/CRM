{!! view_render_event('admin.leads.view.person.before', ['lead' => $lead]) !!}

<div class="flex w-full flex-col gap-3.5 rounded-2xl border border-slate-200/90 bg-white p-4.5 shadow-xs dark:border-gray-800 dark:bg-gray-900">
    @if ($lead?->person)
        <x-admin::accordion class="select-none !border-none">
            <x-slot:header class="!p-0">
                <div class="flex w-full items-center justify-between gap-4 font-bold text-gray-600dark:text-white">
                    <div class="flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 text-xs dark:bg-indigo-950/60 dark:text-indigo-400">
                            👤
                        </span>
                        <h4 class="text-sm font-bold">@lang('admin::app.leads.view.persons.title')</h4>
                    </div>

                    <div class="flex items-center gap-1">
                        @if (bouncer()->hasPermission('leads.edit') && bouncer()->hasPermission('contacts.persons.edit'))
                            <v-lead-attach-person
                                url="{{ route('admin.leads.attributes.update', $lead->id) }}"
                                search-url="{{ route('admin.contacts.persons.search') }}"
                                mode="change"
                                :current-person='@json(['id' => $lead->person->id, 'name' => $lead->person->name])'
                            ></v-lead-attach-person>
                        @endif
                    </div>
                </div>
            </x-slot>

            <x-slot:content class="mt-3 !px-0 !pb-0">
                <div class="flex items-start gap-3 rounded-xl border border-slate-100 bg-slate-50/60 p-3 dark:border-gray-800 dark:bg-gray-800/40">
                    {!! view_render_event('admin.leads.view.person.avatar.before', ['lead' => $lead]) !!}

                    <!-- Person Avatar -->
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 font-bold text-white text-sm shadow-xs">
                        {{ strtoupper(substr($lead->person->name, 0, 2)) }}
                    </div>

                    {!! view_render_event('admin.leads.view.person.avatar.after', ['lead' => $lead]) !!}

                    <!-- Person Details -->
                    <div class="flex flex-1 flex-col gap-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-1.5">
                            {!! view_render_event('admin.leads.view.person.name.before', ['lead' => $lead]) !!}

                            <a
                                href="{{ route('admin.contacts.persons.view', $lead->person->id) }}"
                                class="text-sm font-bold text-gray-600hover:text-purple-600 transition dark:text-white dark:hover:text-purple-400 truncate"
                                target="_blank"
                            >
                                {{ $lead->person->name }}
                            </a>

                            {!! view_render_event('admin.leads.view.person.name.after', ['lead' => $lead]) !!}

                            <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300">
                                Primary
                            </span>
                        </div>

                        {!! view_render_event('admin.leads.view.person.job_title.before', ['lead' => $lead]) !!}

                        @if ($lead->person->job_title)
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium truncate">
                                @if ($lead->person->organization)
                                    @lang('admin::app.leads.view.persons.job-title', [
                                        'job_title' => $lead->person->job_title,
                                        'organization' => $lead->person->organization->name
                                    ])
                                @else
                                    {{ $lead->person->job_title }}
                                @endif
                            </span>
                        @endif

                        {!! view_render_event('admin.leads.view.person.job_title.after', ['lead' => $lead]) !!}

                        <!-- Contact Methods -->
                        <div class="mt-2 flex flex-col gap-1.5 pt-2 border-t border-slate-200/60 dark:border-gray-700/60 text-xs">
                            {!! view_render_event('admin.leads.view.person.contact_numbers.before', ['lead' => $lead]) !!}

                            @foreach ($lead->person->contact_numbers as $contactNumber)
                                <div class="flex items-center justify-between gap-1 group">
                                    <a
                                        class="flex items-center gap-1.5 text-slate-700 hover:text-purple-600 font-medium dark:text-slate-300 dark:hover:text-purple-400"
                                        href="callto:{{ $contactNumber['value'] }}"
                                    >
                                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-purple-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <span>{{ $contactNumber['value'] }}</span>
                                    </a>

                                    <span class="text-[10px] text-slate-400 dark:text-slate-500">
                                        {{ $contactNumber['label'] }}
                                    </span>
                                </div>
                            @endforeach

                            {!! view_render_event('admin.leads.view.person.contact_numbers.after', ['lead' => $lead]) !!}

                            {!! view_render_event('admin.leads.view.person.email.before', ['lead' => $lead]) !!}

                            @foreach ($lead->person->emails as $email)
                                <div class="flex items-center justify-between gap-1 group">
                                    <a
                                        class="flex items-center gap-1.5 text-slate-700 hover:text-purple-600 font-medium dark:text-slate-300 dark:hover:text-purple-400 truncate"
                                        href="mailto:{{ $email['value'] }}"
                                    >
                                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-purple-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="truncate">{{ $email['value'] }}</span>
                                    </a>

                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 shrink-0">
                                        {{ $email['label'] }}
                                    </span>
                                </div>
                            @endforeach

                            {!! view_render_event('admin.leads.view.person.email.after', ['lead' => $lead]) !!}
                        </div>
                    </div>
                </div>
            </x-slot>
        </x-admin::accordion>
    @else
        <div class="flex w-full items-center justify-between gap-4 font-bold text-gray-600dark:text-white">
            <div class="flex items-center gap-2">
                <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 text-xs dark:bg-indigo-950/60 dark:text-indigo-400">
                    👤
                </span>
                <h4 class="text-sm font-bold">@lang('admin::app.leads.view.persons.title')</h4>
            </div>
        </div>

        @if (bouncer()->hasPermission('leads.edit') && bouncer()->hasPermission('contacts.persons.edit'))
            <v-lead-attach-person
                url="{{ route('admin.leads.attributes.update', $lead->id) }}"
                search-url="{{ route('admin.contacts.persons.search') }}"
            ></v-lead-attach-person>
        @else
            <p class="text-sm text-gray-600 dark:text-gray-300">
                @lang('admin::app.leads.view.persons.no-person')
            </p>
        @endif
    @endif
</div>

{!! view_render_event('admin.leads.view.person.after', ['lead' => $lead]) !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-lead-attach-person-template"
    >
        <div class="flex flex-col gap-2">
            <!-- Inline attach form (no person allocated) -->
            <template v-if="mode === 'attach'">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    @lang('admin::app.leads.view.persons.no-person')
                </p>

                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                    ref="attachPersonFormWrapper"
                >
                    <form
                        @submit="handleSubmit($event, savePerson)"
                        ref="attachPersonForm"
                    >
                        <x-admin::form.control-group>
                            <x-admin::lookup
                                ::src="searchUrl"
                                name="person[id]"
                                ::value="selectedPerson"
                                rules="required"
                                :label="trans('admin::app.leads.common.contact.name')"
                                :placeholder="trans('admin::app.leads.common.contact.name')"
                                @on-selected="onPersonSelected"
                            />

                            <x-admin::form.control-group.error control-name="person[id]" />
                        </x-admin::form.control-group>

                        <div class="flex justify-end">
                            <x-admin::button
                                class="primary-button"
                                :title="trans('admin::app.leads.view.persons.attach-btn')"
                                ::loading="isStoring"
                                ::disabled="isStoring || ! selectedPerson.id"
                            />
                        </div>
                    </form>
                </x-admin::form>
            </template>

            <!-- Change person inline dropdown trigger -->
            <template v-else>
                <div
                    class="relative"
                    ref="changeWrapper"
                >
                    @if (bouncer()->hasPermission('leads.edit') && bouncer()->hasPermission('contacts.persons.edit'))
                        <a
                            type="button"
                            class="icon-edit rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                            @click="toggleChangeForm"
                        ></a>
                    @endif

                    <div
                        v-if="showChangeForm"
                        class="absolute right-0 top-full z-20 mt-2 flex w-72 flex-col gap-2 rounded-lg border border-gray-300 bg-white p-3 shadow-lg dark:border-gray-800 dark:bg-gray-900"
                        @click.stop
                    >
                        <x-admin::form
                            v-slot="{ meta, errors, handleSubmit }"
                            as="div"
                            ref="changePersonFormWrapper"
                        >
                            <form
                                @submit="handleSubmit($event, savePerson)"
                                ref="changePersonForm"
                            >
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.leads.common.contact.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::lookup
                                        ::src="searchUrl"
                                        name="person[id]"
                                        ::value="selectedPerson"
                                        rules="required"
                                        :label="trans('admin::app.leads.common.contact.name')"
                                        :placeholder="trans('admin::app.leads.common.contact.name')"
                                        @on-selected="onPersonSelected"
                                    />

                                    <x-admin::form.control-group.error control-name="person[id]" />
                                </x-admin::form.control-group>

                                <div class="flex justify-end gap-2">
                                    <button
                                        type="button"
                                        class="transparent-button"
                                        @click="toggleChangeForm"
                                    >
                                        @lang('admin::app.leads.view.persons.cancel-btn')
                                    </button>

                                    <x-admin::button
                                        class="primary-button"
                                        :title="trans('admin::app.leads.view.persons.save-btn')"
                                        ::loading="isStoring"
                                        ::disabled="isStoring || ! selectedPerson.id || selectedPerson.id == currentPerson.id"
                                    />
                                </div>
                            </form>
                        </x-admin::form>
                    </div>
                </div>
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-lead-attach-person', {
            template: '#v-lead-attach-person-template',

            props: {
                url: {
                    type: String,
                    required: true,
                },

                searchUrl: {
                    type: String,
                    required: true,
                },

                mode: {
                    type: String,
                    default: 'attach',
                },

                currentPerson: {
                    type: Object,
                    default: () => ({ id: '', name: '' }),
                },
            },

            data() {
                return {
                    isStoring: false,

                    showChangeForm: false,

                    selectedPerson: {
                        id: this.currentPerson?.id ?? '',
                        name: this.currentPerson?.name ?? '',
                    },
                };
            },

            mounted() {
                window.addEventListener('click', this.handleOutsideClick);
            },

            beforeDestroy() {
                window.removeEventListener('click', this.handleOutsideClick);
            },

            methods: {
                onPersonSelected(person) {
                    this.selectedPerson = {
                        id: person?.id ?? '',
                        name: person?.name ?? '',
                    };
                },

                toggleChangeForm(event) {
                    event?.preventDefault();
                    event?.stopPropagation();

                    this.showChangeForm = ! this.showChangeForm;

                    if (this.showChangeForm) {
                        this.selectedPerson = {
                            id: this.currentPerson?.id ?? '',
                            name: this.currentPerson?.name ?? '',
                        };
                    }
                },

                handleOutsideClick(event) {
                    const wrapper = this.$refs.changeWrapper;

                    if (
                        this.showChangeForm
                        && wrapper
                        && ! wrapper.contains(event.target)
                    ) {
                        this.showChangeForm = false;
                    }
                },

                savePerson() {
                    if (! this.selectedPerson.id) {
                        return;
                    }

                    this.isStoring = true;

                    const formData = new FormData();

                    formData.append('_method', 'PUT');
                    formData.append('person[id]', this.selectedPerson.id);
                    formData.append('person[name]', this.selectedPerson.name);

                    this.$axios.post(this.url, formData)
                        .then(response => {
                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: response.data.message,
                            });

                            window.location.reload();
                        })
                        .catch(error => {
                            this.$emitter.emit('add-flash', {
                                type: 'error',
                                message: error.response?.data?.message || error.message,
                            });
                        })
                        .finally(() => {
                            this.isStoring = false;
                        });
                },
            },
        });
    </script>
@endPushOnce
