<x-admin-layout title="Dashboard Overview">

    <div class="">
        <div class="max-w-7xl mx-auto">



            <!-- Metrics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

                <!-- Total Institutes -->
                <a href="{{ route('institutes.index') }}"
                    class="p-6 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all duration-300">
                    <div
                        class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-50 rounded-full opacity-50 group-hover:bg-indigo-100 transition-colors">
                    </div>
                    <h3 class="text-gray-500 text-[10px] font-bold tracking-widest uppercase z-10">Total Institutes</h3>
                    <p class="text-4xl font-bold text-gray-900 mt-2 z-10">{{ $totalInstitutes }}</p>
                    <div class="mt-4 flex items-center text-xs font-semibold text-indigo-600 z-10">
                        <span>View Portfolio</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </div>
                </a>

                <!-- Active Subscriptions -->
                <a href="{{ route('subscriptions.index') }}"
                    class="p-6 bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-2xl shadow-lg border border-indigo-500 flex flex-col justify-between relative overflow-hidden group transition-all duration-300 hover:shadow-indigo-600/20 active:scale-[0.98]">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full"></div>
                    <h3 class="text-indigo-100 text-[10px] font-semibold tracking-widest uppercase z-10">Active
                        Subscriptions</h3>
                    <p class="text-4xl font-bold text-white mt-2 z-10">{{ $activeSubscriptions }}</p>
                    <div class="mt-4 flex items-center text-xs font-semibold text-indigo-200 z-10">
                        <span class="bg-indigo-500/30 px-2 py-0.5 rounded text-[10px] uppercase">Standard SaaS</span>
                    </div>
                </a>

                <!-- Total Revenue -->
                <a href="{{ route('revenue.index') }}"
                    class="p-6 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all duration-300">
                    <div
                        class="absolute -right-6 -top-6 w-24 h-24 bg-green-50 rounded-full opacity-50 group-hover:bg-green-100 transition-colors">
                    </div>
                    <h3 class="text-gray-500 text-[10px] font-bold tracking-widest uppercase z-10">Total Revenue</h3>
                    <p class="text-4xl font-bold text-emerald-600 mt-2 z-10">
                        {{ $currency }}{{ number_format($totalRevenue, 0) }}</p>
                    <div class="mt-4 flex items-center text-xs font-semibold text-emerald-600 z-10">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span>Growth Active</span>
                    </div>
                </a>
            </div>

            <!-- Analytics Section -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
                <!-- Institute Growh Chart -->
                <div
                    class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition hover:shadow-md duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Institute Growth</h3>
                            <p class="text-[10px] text-gray-400 font-medium mt-1 uppercase">Track newly onboarded
                                institutes</p>
                        </div>
                        <div class="flex bg-gray-50 p-1 rounded-xl gap-1">
                            <button onclick="updateChart('institutes', 'weekly')"
                                class="chart-tab-institutes px-3 py-1.5 text-[10px] font-black uppercase rounded-lg transition-all"
                                id="btn-institutes-weekly">Weekly</button>
                            <button onclick="updateChart('institutes', 'monthly')"
                                class="chart-tab-institutes px-3 py-1.5 text-[10px] font-black uppercase rounded-lg transition-all bg-white shadow-sm text-indigo-600"
                                id="btn-institutes-monthly">Monthly</button>
                            <button onclick="updateChart('institutes', 'yearly')"
                                class="chart-tab-institutes px-3 py-1.5 text-[10px] font-black uppercase rounded-lg transition-all"
                                id="btn-institutes-yearly">Yearly</button>
                        </div>
                    </div>
                    <div id="instituteGrowthChart" class="min-h-[300px]"></div>
                </div>

                <!-- Revenue Analysis Chart -->
                <div
                    class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition hover:shadow-md duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Revenue Analysis</h3>
                            <p class="text-[10px] text-gray-400 font-medium mt-1 uppercase">Monitor subscription income
                                trends</p>
                        </div>
                        <div class="flex bg-gray-50 p-1 rounded-xl gap-1">
                            <button onclick="updateChart('revenue', 'weekly')"
                                class="chart-tab-revenue px-3 py-1.5 text-[10px] font-black uppercase rounded-lg transition-all"
                                id="btn-revenue-weekly">Weekly</button>
                            <button onclick="updateChart('revenue', 'monthly')"
                                class="chart-tab-revenue px-3 py-1.5 text-[10px] font-black uppercase rounded-lg transition-all bg-white shadow-sm text-indigo-600"
                                id="btn-revenue-monthly">Monthly</button>
                            <button onclick="updateChart('revenue', 'yearly')"
                                class="chart-tab-revenue px-3 py-1.5 text-[10px] font-black uppercase rounded-lg transition-all"
                                id="btn-revenue-yearly">Yearly</button>
                        </div>
                    </div>
                    <div id="revenueAnalysisChart" class="min-h-[300px]"></div>
                </div>
            </div>


        </div>
    </div>

    <!-- Scripts for Charts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        const analyticsData = @json($analytics);
        const currencySymbol = "{{ $currency }}";

        let charts = {
            institutes: null,
            revenue: null
        };

        function initCharts() {
            // Institute Growth Chart - Modern Area with Glow
            const instituteOptions = {
                series: [{
                    name: 'New Institutes',
                    data: analyticsData.institutes.monthly.values
                }],
                chart: {
                    type: 'area', // Changed to area for better 30-day visualization
                    height: 300,
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    fontFamily: 'inherit',
                    dropShadow: {
                        enabled: true,
                        color: '#6366f1',
                        top: 10,
                        left: 0,
                        blur: 8,
                        opacity: 0.15
                    }
                },
                colors: ['#6366f1'],
                dataLabels: { enabled: false }, // Disabled for cleaner monthly view
                stroke: { curve: 'smooth', width: 4, lineCap: 'round' },
                xaxis: {
                    type: 'category',
                    categories: analyticsData.institutes.monthly.labels,
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
                    labels: { style: { colors: '#9ca3af', fontSize: '10px', fontWeight: 600 } }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.35,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                tooltip: {
                    theme: 'dark',
                    y: { formatter: (val) => val + " Institutes" }
                },
                legend: { show: false }, // Killed the messy legend
                grid: { borderColor: '#f3f4f6', strokeDashArray: 4 }
            };

            // Revenue Analysis Chart - Modern Glow Area
            const revenueOptions = {
                series: [{
                    name: 'Revenue',
                    data: analyticsData.revenue.monthly.values
                }],
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    fontFamily: 'inherit',
                    dropShadow: {
                        enabled: true,
                        color: '#10b981',
                        top: 10,
                        left: 0,
                        blur: 8,
                        opacity: 0.15
                    }
                },
                colors: ['#10b981'],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 4, lineCap: 'round' },
                xaxis: {
                    type: 'category',
                    categories: analyticsData.revenue.monthly.labels,
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
                        formatter: (val) => currencySymbol + val.toLocaleString()
                    }
                },
                tooltip: {
                    theme: 'dark',
                    x: { show: true },
                    y: { formatter: (val) => currencySymbol + val.toLocaleString() }
                },
                legend: { show: false },
                grid: { borderColor: '#f3f4f6', strokeDashArray: 4 },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.35,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                }
            };

            charts.institutes = new ApexCharts(document.querySelector("#instituteGrowthChart"), instituteOptions);
            charts.revenue = new ApexCharts(document.querySelector("#revenueAnalysisChart"), revenueOptions);

            charts.institutes.render();
            charts.revenue.render();
        }

        function updateChart(type, range) {
            const data = analyticsData[type][range];

            // Update UI tabs
            const tabs = document.querySelectorAll(`.chart-tab-${type}`);
            tabs.forEach(tab => {
                tab.classList.remove('bg-white', 'shadow-sm', 'text-indigo-600');
            });
            document.getElementById(`btn-${type}-${range}`).classList.add('bg-white', 'shadow-sm', 'text-indigo-600');

            // Perform a unified update of both X-axis categories and Series data
            // This prevents ApexCharts state-corruption from firing two back-to-back updates
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
                    name: type === 'institutes' ? 'New Institutes' : 'Revenue',
                    data: data.values
                }]
            });
        }

        document.addEventListener('DOMContentLoaded', initCharts);
    </script>
</x-admin-layout>