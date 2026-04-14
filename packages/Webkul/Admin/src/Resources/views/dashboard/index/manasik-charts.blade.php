{!! view_render_event('admin.dashboard.index.manasik_charts.before') !!}

<v-dashboard-manasik-charts></v-dashboard-manasik-charts>

{!! view_render_event('admin.dashboard.index.manasik_charts.after') !!}

@pushOnce('scripts', 'admin-dashboard-manasik-charts')
    <script
        type="text/x-template"
        id="v-dashboard-manasik-charts-template"
    >
        <template v-if="isLoading">
            <div class="grid gap-4 lg:grid-cols-2">
                <div class="light-shimmer-bg dark:shimmer h-[320px] rounded-lg border border-gray-200 dark:border-gray-800"></div>
                <div class="light-shimmer-bg dark:shimmer h-[320px] rounded-lg border border-gray-200 dark:border-gray-800"></div>
            </div>
        </template>

        <template v-else-if="!manasikInactive">
            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 sm:p-6">
                <p class="text-base font-semibold text-gray-800 dark:text-white">
                    @lang('admin::app.dashboard.index.manasik.charts-section-title')
                </p>
                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                    @lang('admin::app.dashboard.index.manasik.charts-section-hint')
                </p>

                <div class="mt-6 grid gap-8 lg:grid-cols-2">
                    <div class="flex min-w-0 flex-col gap-3">
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                            @lang('admin::app.dashboard.index.manasik.charts-map-title')
                        </p>
                        <p
                            v-if="mapLocationsSum === 0"
                            class="text-sm text-gray-500 dark:text-gray-400"
                        >
                            @lang('admin::app.dashboard.index.manasik.charts-map-empty')
                        </p>
                        <div
                            v-else
                            class="relative mx-auto flex max-w-[300px] justify-center"
                        >
                            <canvas
                                ref="mapDoughnutCanvas"
                                class="max-h-[280px] w-full"
                            ></canvas>
                        </div>
                    </div>

                    <div class="flex min-w-0 flex-col gap-3">
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                            @lang('admin::app.dashboard.index.manasik.charts-overview-title')
                        </p>
                        <div class="relative min-h-[280px] w-full">
                            <canvas ref="overviewBarCanvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-manasik-charts', {
            template: '#v-dashboard-manasik-charts-template',

            data() {
                return {
                    report: [],

                    isLoading: true,

                    barChart: null,

                    doughnutChart: null,
                };
            },

            computed: {
                manasikInactive() {
                    return this.report.statistics?.manasik_module === 'off';
                },

                mapLocationsSum() {
                    const md = this.report.statistics?.map_doughnut;

                    if (! md?.values) {
                        return 0;
                    }

                    return md.values.reduce((a, b) => a + (Number(b) || 0), 0);
                },
            },

            mounted() {
                this.loadCharts();
            },

            beforeUnmount() {
                this.destroyCharts();
            },

            methods: {
                destroyCharts() {
                    if (this.barChart) {
                        this.barChart.destroy();
                        this.barChart = null;
                    }

                    if (this.doughnutChart) {
                        this.doughnutChart.destroy();
                        this.doughnutChart = null;
                    }
                },

                waitForChartCtor() {
                    return new Promise((resolve) => {
                        let attempts = 0;

                        const maxAttempts = 240;

                        const tryResolve = () => {
                            const C = typeof globalThis !== 'undefined' ? globalThis.Chart : undefined;

                            if (typeof C === 'function') {
                                resolve(C);

                                return;
                            }

                            attempts += 1;

                            if (attempts >= maxAttempts) {
                                resolve(null);

                                return;
                            }

                            requestAnimationFrame(tryResolve);
                        };

                        tryResolve();
                    });
                },

                async loadCharts() {
                    this.isLoading = true;

                    try {
                        const { data } = await this.$axios.get("{{ route('admin.dashboard.stats') }}", {
                            params: { type: 'manasik-charts' },
                        });

                        this.report = data;
                    } catch (e) {
                        this.report = { statistics: { manasik_module: 'off' } };
                    }

                    this.isLoading = false;

                    if (this.manasikInactive) {
                        return;
                    }

                    await this.$nextTick();

                    const ChartCtor = await this.waitForChartCtor();

                    if (! ChartCtor) {
                        return;
                    }

                    this.destroyCharts();

                    const stats = this.report.statistics || {};
                    const ob = stats.overview_bar;
                    const md = stats.map_doughnut;

                    const barColors = [
                        'rgba(14, 144, 217, 0.9)',
                        'rgba(34, 197, 94, 0.9)',
                        'rgba(212, 175, 55, 0.9)',
                        'rgba(139, 92, 246, 0.9)',
                        'rgba(244, 114, 182, 0.9)',
                    ];

                    if (this.$refs.overviewBarCanvas && ob?.labels && ob?.values) {
                        this.barChart = new ChartCtor(this.$refs.overviewBarCanvas, {
                            type: 'bar',

                            data: {
                                labels: ob.labels,

                                datasets: [
                                    {
                                        data: ob.values,

                                        backgroundColor: ob.values.map((_, i) => barColors[i % barColors.length]),

                                        borderRadius: 6,

                                        borderSkipped: false,
                                    },
                                ],
                            },

                            options: {
                                responsive: true,

                                maintainAspectRatio: false,

                                plugins: {
                                    legend: {
                                        display: false,
                                    },
                                },

                                scales: {
                                    x: {
                                        ticks: {
                                            maxRotation: 45,

                                            minRotation: 0,

                                            autoSkip: true,
                                        },

                                        grid: {
                                            display: false,
                                        },
                                    },

                                    y: {
                                        beginAtZero: true,

                                        ticks: {
                                            precision: 0,
                                        },
                                    },
                                },
                            },
                        });
                    }

                    if (this.$refs.mapDoughnutCanvas && md?.labels && md?.values && this.mapLocationsSum > 0) {
                        this.doughnutChart = new ChartCtor(this.$refs.mapDoughnutCanvas, {
                            type: 'doughnut',

                            data: {
                                labels: md.labels,

                                datasets: [
                                    {
                                        data: md.values,

                                        backgroundColor: [
                                            'rgba(34, 197, 94, 0.9)',
                                            'rgba(148, 163, 184, 0.85)',
                                        ],

                                        borderWidth: 0,
                                    },
                                ],
                            },

                            options: {
                                responsive: true,

                                maintainAspectRatio: true,

                                plugins: {
                                    legend: {
                                        position: 'bottom',

                                        labels: {
                                            usePointStyle: true,

                                            padding: 16,
                                        },
                                    },
                                },
                            },
                        });
                    }
                },
            },
        });
    </script>
@endPushOnce
