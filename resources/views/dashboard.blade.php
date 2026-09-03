<x-admin-layout title="Dashboard Overview">

    <div class="">
        <div class="max-w-7xl mx-auto">

            <!-- Hero KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-4">

                <!-- Total Institutes -->
                <a href="{{ route('institutes.index') }}"
                    class="p-6 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all duration-300">
                    <div
                        class="absolute -right-6 -top-6 w-24 h-24 bg-orange-50 rounded-full opacity-50 group-hover:bg-orange-100 transition-colors">
                    </div>
                    <h3 class="text-gray-500 text-[10px] font-bold tracking-widest uppercase z-10">Total Institutes</h3>
                    <p class="text-4xl font-bold text-gray-900 mt-2 z-10">{{ $totalInstitutes }}</p>
                    <div class="mt-4 flex items-center text-xs font-semibold text-[#ff6c00] z-10">
                        @if($newInstitutesThisMonth > 0)
                            <span>+{{ $newInstitutesThisMonth }} this month</span>
                        @else
                            <span>View Portfolio</span>
                        @endif
                        <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </div>
                </a>

                <!-- Active Access -->
                <a href="{{ route('subscriptions.index') }}"
                    class="p-6 bg-gradient-to-br from-[#ff6c00] to-orange-600 rounded-2xl shadow-lg border border-orange-500/80 flex flex-col justify-between relative overflow-hidden group transition-all duration-300 hover:shadow-orange-500/25 active:scale-[0.98]">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                    <h3 class="text-orange-100 text-[10px] font-semibold tracking-widest uppercase z-10">Active Access</h3>
                    <p class="text-4xl font-bold text-white mt-2 z-10">{{ $activeAccessCount }}</p>
                    <div class="mt-4 flex items-center text-xs font-semibold text-orange-100 z-10">
                        <span class="bg-white/20 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm border border-white/20">Institutes with unexpired plan</span>
                    </div>
                </a>

                <!-- Revenue This Month -->
                <a href="{{ route('revenue.index') }}"
                    class="p-6 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all duration-300">
                    <div
                        class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 rounded-full opacity-50 group-hover:bg-emerald-100 transition-colors">
                    </div>
                    <h3 class="text-gray-500 text-[10px] font-bold tracking-widest uppercase z-10">Revenue This Month</h3>
                    <p class="text-4xl font-bold text-emerald-600 mt-2 z-10">
                        {{ $currency }}{{ number_format($revenueThisMonth, 0) }}</p>
                    <div class="mt-4 flex items-center text-xs font-semibold z-10">
                        @php
                            $revDelta = $revenueLastMonth > 0
                                ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100)
                                : ($revenueThisMonth > 0 ? 100 : 0);
                        @endphp
                        <svg class="w-4 h-4 mr-1 {{ $revDelta >= 0 ? 'text-emerald-600' : 'text-red-500 rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span class="{{ $revDelta >= 0 ? 'text-emerald-600' : 'text-red-500' }}">{{ $revDelta >= 0 ? '+' : '' }}{{ $revDelta }}% vs last month</span>
                    </div>
                </a>
            </div>

            <!-- Secondary Stat Strip -->
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3 mb-6">
                <a href="{{ route('subscriptions.index', ['status' => 'active']) }}" class="p-4 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
                    <p class="text-[9px] font-bold text-amber-600 uppercase tracking-wider">Expiring ≤7 Days</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $expiringSoonCount }}</p>
                </a>
                <a href="{{ route('subscriptions.index', ['status' => 'expired']) }}" class="p-4 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
                    <p class="text-[9px] font-bold text-red-600 uppercase tracking-wider">Expired / Lapsed</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $expiredCount }}</p>
                </a>
                <a href="{{ route('subscriptions.index') }}" class="p-4 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
                    <p class="text-[9px] font-bold text-indigo-600 uppercase tracking-wider">Pending Renewals</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $pendingRenewalsCount }}</p>
                </a>
                <div class="p-4 bg-white rounded-xl border border-gray-100 shadow-sm">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">New This Month</p>
                    @php
                        $instDelta = $newInstitutesLastMonth > 0
                            ? round((($newInstitutesThisMonth - $newInstitutesLastMonth) / $newInstitutesLastMonth) * 100)
                            : ($newInstitutesThisMonth > 0 ? 100 : 0);
                    @endphp
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $newInstitutesThisMonth }}
                        <span class="text-[10px] font-bold {{ $instDelta >= 0 ? 'text-emerald-600' : 'text-red-500' }}">{{ $instDelta >= 0 ? '+' : '' }}{{ $instDelta }}%</span>
                    </p>
                </div>
                <div class="p-4 bg-white rounded-xl border border-gray-100 shadow-sm">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Lifetime Revenue</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">{{ $currency }}{{ number_format($totalRevenue, 0) }}</p>
                </div>
                <div class="p-4 bg-white rounded-xl border border-gray-100 shadow-sm">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Avg. Revenue / Institute</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">{{ $currency }}{{ number_format($avgRevenuePerInstitute, 0) }}</p>
                </div>
            </div>

            <!-- Analytics Section -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-3 mb-3">
                <!-- Institute Growth Chart -->
                <div
                    class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition hover:shadow-md duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Institute Growth</h3>
                            <p class="text-[10px] text-gray-400 font-medium mt-1 uppercase">New onboarded institutes</p>
                        </div>
                        <div class="flex bg-gray-50 p-1 rounded-xl gap-1">
                            <button onclick="updateChart('institutes', 'weekly')"
                                class="chart-tab-institutes px-3 py-1.5 text-[10px] font-black uppercase rounded-lg transition-all text-gray-400 hover:text-gray-600"
                                id="btn-institutes-weekly">Weekly</button>
                            <button onclick="updateChart('institutes', 'monthly')"
                                class="chart-tab-institutes px-3 py-1.5 text-[10px] font-black uppercase rounded-lg transition-all bg-white shadow-sm text-[#ff6c00]"
                                id="btn-institutes-monthly">Monthly</button>
                            <button onclick="updateChart('institutes', 'yearly')"
                                class="chart-tab-institutes px-3 py-1.5 text-[10px] font-black uppercase rounded-lg transition-all text-gray-400 hover:text-gray-600"
                                id="btn-institutes-yearly">Yearly</button>
                        </div>
                    </div>
                    <div id="instituteGrowthChart" class="min-h-[280px]"></div>
                </div>

                <!-- Revenue Analysis Chart -->
                <div
                    class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition hover:shadow-md duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Revenue Analysis</h3>
                            <p class="text-[10px] text-gray-400 font-medium mt-1 uppercase">Plan purchase &amp; extension income</p>
                        </div>
                        <div class="flex bg-gray-50 p-1 rounded-xl gap-1">
                            <button onclick="updateChart('revenue', 'weekly')"
                                class="chart-tab-revenue px-3 py-1.5 text-[10px] font-black uppercase rounded-lg transition-all text-gray-400 hover:text-gray-600"
                                id="btn-revenue-weekly">Weekly</button>
                            <button onclick="updateChart('revenue', 'monthly')"
                                class="chart-tab-revenue px-3 py-1.5 text-[10px] font-black uppercase rounded-lg transition-all bg-white shadow-sm text-[#ff6c00]"
                                id="btn-revenue-monthly">Monthly</button>
                            <button onclick="updateChart('revenue', 'yearly')"
                                class="chart-tab-revenue px-3 py-1.5 text-[10px] font-black uppercase rounded-lg transition-all text-gray-400 hover:text-gray-600"
                                id="btn-revenue-yearly">Yearly</button>
                        </div>
                    </div>
                    <div id="revenueAnalysisChart" class="min-h-[280px]"></div>
                </div>

                <!-- Expiry Trend Chart -->
                <div
                    class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition hover:shadow-md duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Expiry Trend</h3>
                            <p class="text-[10px] text-gray-400 font-medium mt-1 uppercase">Plans lapsing per period</p>
                        </div>
                        <div class="flex bg-gray-50 p-1 rounded-xl gap-1">
                            <button onclick="updateChart('expiry', 'weekly')"
                                class="chart-tab-expiry px-3 py-1.5 text-[10px] font-black uppercase rounded-lg transition-all text-gray-400 hover:text-gray-600"
                                id="btn-expiry-weekly">Weekly</button>
                            <button onclick="updateChart('expiry', 'monthly')"
                                class="chart-tab-expiry px-3 py-1.5 text-[10px] font-black uppercase rounded-lg transition-all bg-white shadow-sm text-[#ff6c00]"
                                id="btn-expiry-monthly">Monthly</button>
                            <button onclick="updateChart('expiry', 'yearly')"
                                class="chart-tab-expiry px-3 py-1.5 text-[10px] font-black uppercase rounded-lg transition-all text-gray-400 hover:text-gray-600"
                                id="btn-expiry-yearly">Yearly</button>
                        </div>
                    </div>
                    <div id="expiryTrendChart" class="min-h-[280px]"></div>
                </div>
            </div>

            <!-- Plan Mix + Renewal Health -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-3 mb-2">
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-1">Plan Mix</h3>
                    <p class="text-[10px] text-gray-400 font-medium mb-4 uppercase">Currently active institutes, by plan</p>
                    @if($planMix->isEmpty())
                        <p class="text-xs text-gray-400 py-10 text-center">No active plans yet.</p>
                    @else
                        <div id="planMixChart" class="min-h-[240px]"></div>
                    @endif
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-1">Renewals vs New Sales</h3>
                    <p class="text-[10px] text-gray-400 font-medium mb-4 uppercase">This month's plan purchases</p>
                    <div class="flex items-center gap-8">
                        <div>
                            <p class="text-3xl font-bold text-emerald-600">{{ $renewalsThisMonth }}</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1">Renewals</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-[#ff6c00]">{{ $newSalesThisMonth }}</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1">New Institutes</p>
                        </div>
                        @php
                            $total = $renewalsThisMonth + $newSalesThisMonth;
                            $repeatRate = $total > 0 ? round(($renewalsThisMonth / $total) * 100) : null;
                        @endphp
                        @if(!is_null($repeatRate))
                            <div class="ml-auto text-right">
                                <p class="text-3xl font-bold text-gray-900">{{ $repeatRate }}%</p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1">Repeat Rate</p>
                            </div>
                        @endif
                    </div>
                    <p class="text-[10px] text-gray-400 mt-4 leading-relaxed">Counted from plan-purchase payments: a subscription with an earlier payment on record counts as a renewal; a first-ever payment counts as a new institute.</p>
                </div>
            </div>

        </div>
    </div>

    <!-- Scripts for Charts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        const analyticsData = @json($analytics);
        const planMixData = @json($planMix);
        const currencySymbol = "{{ $currency }}";

        let charts = {
            institutes: null,
            revenue: null,
            expiry: null,
            planMix: null
        };

        function areaChartOptions(name, data, color, formatter) {
            return {
                series: [{ name: name, data: data.values }],
                chart: {
                    type: 'area',
                    height: 280,
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    fontFamily: 'inherit',
                    dropShadow: { enabled: true, color: color, top: 10, left: 0, blur: 8, opacity: 0.15 }
                },
                colors: [color],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 4, lineCap: 'round' },
                xaxis: {
                    type: 'category',
                    categories: data.labels,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    tooltip: { enabled: false },
                    labels: {
                        style: { colors: '#9ca3af', fontSize: '10px', fontWeight: 600 },
                        rotate: -45,
                        rotateAlways: true,
                        hideOverlappingLabels: false
                    }
                },
                yaxis: {
                    labels: {
                        style: { colors: '#9ca3af', fontSize: '10px', fontWeight: 600 },
                        formatter: formatter
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.05, stops: [0, 90, 100] }
                },
                tooltip: {
                    theme: 'dark',
                    y: { formatter: formatter }
                },
                legend: { show: false },
                grid: { borderColor: '#f3f4f6', strokeDashArray: 4 }
            };
        }

        function initCharts() {
            const instituteOptions = areaChartOptions('New Institutes', analyticsData.institutes.monthly, '#ff6c00', (val) => val + " Institutes");
            const revenueOptions = areaChartOptions('Revenue', analyticsData.revenue.monthly, '#10b981', (val) => currencySymbol + val.toLocaleString());
            const expiryOptions = areaChartOptions('Expiries', analyticsData.expiry.monthly, '#ef4444', (val) => val + " Expiries");

            charts.institutes = new ApexCharts(document.querySelector("#instituteGrowthChart"), instituteOptions);
            charts.revenue = new ApexCharts(document.querySelector("#revenueAnalysisChart"), revenueOptions);
            charts.expiry = new ApexCharts(document.querySelector("#expiryTrendChart"), expiryOptions);

            charts.institutes.render();
            charts.revenue.render();
            charts.expiry.render();

            if (planMixData.length > 0) {
                const planMixOptions = {
                    series: planMixData.map(p => p.total),
                    labels: planMixData.map(p => p.plan_name),
                    chart: { type: 'donut', height: 240, fontFamily: 'inherit' },
                    colors: ['#ff6c00', '#10b981', '#6366f1', '#f59e0b', '#ec4899', '#0ea5e9'],
                    legend: { position: 'bottom', fontSize: '11px', fontWeight: 600 },
                    dataLabels: { enabled: false },
                    tooltip: { y: { formatter: (val) => val + " institutes" } }
                };
                charts.planMix = new ApexCharts(document.querySelector("#planMixChart"), planMixOptions);
                charts.planMix.render();
            }
        }

        function updateChart(type, range) {
            const data = analyticsData[type][range];

            // Update UI tabs
            const tabs = document.querySelectorAll(`.chart-tab-${type}`);
            tabs.forEach(tab => {
                tab.classList.remove('bg-white', 'shadow-sm', 'text-[#ff6c00]');
                tab.classList.add('text-gray-400');
            });
            const activeTab = document.getElementById(`btn-${type}-${range}`);
            activeTab.classList.remove('text-gray-400');
            activeTab.classList.add('bg-white', 'shadow-sm', 'text-[#ff6c00]');

            const seriesName = { institutes: 'New Institutes', revenue: 'Revenue', expiry: 'Expiries' }[type];

            charts[type].updateOptions({
                xaxis: {
                    type: 'category',
                    categories: data.labels,
                    labels: {
                        rotate: -45,
                        rotateAlways: true,
                        hideOverlappingLabels: false
                    }
                },
                series: [{
                    name: seriesName,
                    data: data.values
                }]
            });
        }

        document.addEventListener('DOMContentLoaded', initCharts);
    </script>
</x-admin-layout>
