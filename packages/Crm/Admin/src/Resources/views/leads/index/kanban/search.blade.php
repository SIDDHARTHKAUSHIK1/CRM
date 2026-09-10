{!! view_render_event('admin.leads.index.kanban.search.before') !!}

<v-kanban-search
    :is-loading="isLoading"
    :available="available"
    :applied="applied"
    @search="search"
>
</v-kanban-search>

{!! view_render_event('admin.leads.index.kanban.search.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-kanban-search-template"
    >
        <div class="relative flex w-full max-w-[380px] items-center max-md:w-full max-md:max-w-full">
            <div class="icon-search absolute top-2.5 flex items-center text-lg text-slate-400 ltr:left-3.5 rtl:right-3.5 pointer-events-none"></div>

            <input
                type="text"
                name="search"
                class="block w-full rounded-xl border border-slate-200/90 bg-white py-2 text-sm text-slate-800 transition-all placeholder:text-slate-400 hover:border-purple-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:hover:border-gray-700 dark:focus:border-indigo-500 ltr:pl-10 ltr:pr-4 rtl:pl-4 rtl:pr-10 shadow-2xs"
                placeholder="Search by client, property, or lead..."
                autocomplete="off"
                :value="getSearchedValues()"
                @keyup.enter="search"
            >
        </div>
    </script>

    <script type="module">
        app.component('v-kanban-search', {
            template: '#v-kanban-search-template',

            props: ['isLoading', 'available', 'applied'],

            emits: ['search'],

            data() {
                return {
                    filters: {
                        columns: [],
                    },
                };
            },

            mounted() {
                this.filters.columns = this.applied.filters.columns.filter((column) => column.index === 'all');
            },

            methods: {
                /**
                 * Perform a search operation based on the input value.
                 *
                 * @param {Event} $event
                 * @returns {void}
                 */
                search($event) {
                    let requestedValue = $event.target.value;

                    let appliedColumn = this.filters.columns.find(column => column.index === 'all');

                    if (! requestedValue) {
                        appliedColumn.value = [];

                        this.$emit('search', this.filters);

                        return;
                    }

                    if (appliedColumn) {
                        appliedColumn.value = [requestedValue];
                    } else {
                        this.filters.columns.push({
                            index: 'all',
                            value: [requestedValue]
                        });
                    }

                    this.$emit('search', this.filters);
                },

                /**
                 * Get the searched values for a specific column.
                 *
                 * @returns {Array}
                 */
                getSearchedValues() {
                    let appliedColumn = this.filters.columns.find(column => column.index === 'all');

                    return appliedColumn?.value ?? [];
                },
            },
        });
    </script>
@endPushOnce
