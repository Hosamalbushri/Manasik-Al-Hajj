{!! view_render_event('admin.dashboard.index.manasik_over_all.before') !!}

<v-dashboard-manasik-over-all>
    <x-admin::shimmer.dashboard.index.over-all />
</v-dashboard-manasik-over-all>

{!! view_render_event('admin.dashboard.index.manasik_over_all.after') !!}

@pushOnce('scripts', 'admin-dashboard-manasik-over-all')
    <script
        type="text/x-template"
        id="v-dashboard-manasik-over-all-template"
    >
        <template v-if="isLoading">
            <x-admin::shimmer.dashboard.index.over-all />
        </template>

        <template v-else-if="!manasikInactive">
            <div class="flex flex-col gap-3">
                <p class="text-base font-semibold text-gray-800 dark:text-white">
                    @lang('admin::app.dashboard.index.manasik.title')
                </p>

                <div class="grid grid-cols-4 gap-4 max-2xl:grid-cols-3 max-md:grid-cols-2 max-sm:grid-cols-1">
                <div
                    class="flex flex-col gap-2 rounded-lg border border-gray-200 bg-white px-4 py-5 dark:border-gray-800 dark:bg-gray-900"
                    v-for="stat in stats"
                    :key="stat.key"
                >
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-300">
                        @{{ stat.label }}
                    </p>

                    <div class="flex items-center gap-2">
                        <p class="text-xl font-bold dark:text-gray-300">
                            @{{ stat.value }}
                        </p>

                        <div class="flex items-center gap-0.5">
                            <span
                                class="text-base !font-semibold"
                                :class="[stat.progress < 0 ? 'icon-stats-down text-red-500 dark:!text-red-500' : 'icon-stats-up text-green-500 dark:!text-green-500']"
                            ></span>

                            <p
                                class="text-xs font-semibold"
                                :class="[stat.progress < 0 ?  'text-red-500' : 'text-green-500']"
                            >
                                @{{ Math.abs(stat.progress).toFixed(2) }}%
                            </p>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-manasik-over-all', {
            template: '#v-dashboard-manasik-over-all-template',

            data() {
                return {
                    report: [],

                    isLoading: true,
                }
            },

            computed: {
                manasikInactive() {
                    return this.report.statistics?.manasik_module === 'off';
                },

                stats() {
                    const statistics = this.report.statistics || {};

                    return [
                        {
                            key: 'hajj_rites_active',
                            label: "@lang('admin::app.dashboard.index.manasik.hajj-rites-active')",
                            value: statistics.hajj_rites_active?.current ?? 0,
                            progress: statistics.hajj_rites_active?.progress ?? 0,
                        },
                        {
                            key: 'duas_total',
                            label: "@lang('admin::app.dashboard.index.manasik.duas-total')",
                            value: statistics.duas_total?.current ?? 0,
                            progress: statistics.duas_total?.progress ?? 0,
                        },
                        {
                            key: 'map_locations_total',
                            label: "@lang('admin::app.dashboard.index.manasik.map-locations-total')",
                            value: statistics.map_locations_total?.current ?? 0,
                            progress: statistics.map_locations_total?.progress ?? 0,
                        },
                        {
                            key: 'hajj_users_total',
                            label: "@lang('admin::app.dashboard.index.manasik.hajj-users-total')",
                            value: statistics.hajj_users_total?.current ?? 0,
                            progress: statistics.hajj_users_total?.progress ?? 0,
                        },
                        {
                            key: 'hajj_users_with_completion',
                            label: "@lang('admin::app.dashboard.index.manasik.hajj-users-with-completion')",
                            value: statistics.hajj_users_with_completion?.current ?? 0,
                            progress: statistics.hajj_users_with_completion?.progress ?? 0,
                        },
                        {
                            key: 'hajj_users_completions_sum',
                            label: "@lang('admin::app.dashboard.index.manasik.hajj-users-completions-sum')",
                            value: statistics.hajj_users_completions_sum?.current ?? 0,
                            progress: statistics.hajj_users_completions_sum?.progress ?? 0,
                        },
                        {
                            key: 'guide_full_completions',
                            label: "@lang('admin::app.dashboard.index.manasik.guide-full-completions')",
                            value: statistics.guide_full_completions?.current ?? 0,
                            progress: statistics.guide_full_completions?.progress ?? 0,
                        },
                    ];
                },
            },

            mounted() {
                this.getStats({});
            },

            methods: {
                getStats(filters) {
                    this.isLoading = true;

                    filters = Object.assign({}, filters);
                    filters.type = 'manasik-over-all';

                    this.$axios.get("{{ route('admin.dashboard.stats') }}", {
                            params: filters
                        })
                        .then(response => {
                            this.report = response.data;
                            this.isLoading = false;
                        })
                        .catch(() => {
                            this.isLoading = false;
                        });
                },
            }
        });
    </script>
@endPushOnce
