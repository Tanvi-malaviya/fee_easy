@extends('layouts.institute')

@section('content')
    <div class="w-full max-w-[1600px] mx-auto overflow-hidden animate-in fade-in duration-500 px-4 md:px-0 pb-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
            <div>
                <a href="{{ route('institute.reports.index') }}"
                    class="text-[10px] font-bold text-slate-400 uppercase tracking-wider hover:text-[#ff6c00] transition-colors flex items-center gap-1 mb-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Reports
                </a>
                <h1 class="text-xl font-semibold text-slate-800 tracking-tight">Business Analytics</h1>
                <p class="text-xs text-slate-400 mt-0.5 font-medium">Revenue, fee collection, attendance & dropout
                    trends across your institute</p>
            </div>
            <div class="relative">
                <select id="months-select" onchange="loadAnalytics()"
                    class="bg-white border border-slate-100 rounded-xl px-3 py-2 text-xs font-bold text-slate-600 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="3">Last 3 months</option>
                    <option value="6" selected>Last 6 months</option>
                    <option value="12">Last 12 months</option>
                </select>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">This Month Revenue</p>
                <p id="stat-revenue" class="text-2xl font-black text-slate-800">₹0</p>
                <p id="stat-revenue-growth" class="text-[11px] font-bold mt-1 text-slate-400">—</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Fee Collection Rate</p>
                <p id="stat-collection" class="text-2xl font-black text-emerald-500">0%</p>
                <p id="stat-collection-sub" class="text-[11px] font-bold mt-1 text-slate-400">All-time</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Attendance This Month
                </p>
                <p id="stat-attendance" class="text-2xl font-black text-[#ff6c00]">0%</p>
                <p class="text-[11px] font-bold mt-1 text-slate-400">Across all batches</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Dropout Rate</p>
                <p id="stat-dropout" class="text-2xl font-black text-rose-500">0%</p>
                <p id="stat-dropout-sub" class="text-[11px] font-bold mt-1 text-slate-400">—</p>
            </div>
        </div>

        <!-- Revenue & Collection Charts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4">
                <h3 class="text-sm font-bold text-slate-800 mb-4">Revenue Trend</h3>
                <div class="h-[240px] w-full relative">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4">
                <h3 class="text-sm font-bold text-slate-800 mb-4">Fee Collection %</h3>
                <div class="h-[240px] w-full relative">
                    <canvas id="collectionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Attendance Trend -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-800">Batch-wise Attendance Trend</h3>
                <select id="attendance-batch-select" onchange="renderAttendanceChart()"
                    class="bg-slate-50 border border-slate-100 rounded-lg px-2.5 py-1.5 text-[11px] font-bold text-slate-600 focus:outline-none">
                    <option value="all">All Batches (avg)</option>
                </select>
            </div>
            <div class="h-[260px] w-full relative">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        <!-- Dropout by Batch -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4">
            <h3 class="text-sm font-bold text-slate-800 mb-4">Batch-wise Dropout</h3>
            <div class="h-[280px] w-full relative mb-4">
                <canvas id="dropoutChart"></canvas>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="bg-slate-50 text-[9px] font-bold text-slate-400 uppercase tracking-wider">
                        <tr>
                            <th class="px-3 py-2 rounded-l-lg">Batch</th>
                            <th class="px-3 py-2">Active</th>
                            <th class="px-3 py-2">Inactive</th>
                            <th class="px-3 py-2 rounded-r-lg">Dropout Rate</th>
                        </tr>
                    </thead>
                    <tbody id="dropout-table-body" class="divide-y divide-slate-50 text-[11px] font-bold text-slate-600">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ANALYTICS_URL = '/api/v1/institute/reports/analytics';
            let revenueChart = null, collectionChart = null, attendanceChart = null, dropoutChart = null;
            let latestAttendanceBatches = [];

            function formatCurrency(n) {
                return '₹' + Number(n || 0).toLocaleString('en-IN');
            }

            async function loadAnalytics() {
                const months = document.getElementById('months-select').value;
                try {
                    const res = await fetch(`${ANALYTICS_URL}?months=${months}`, { headers: { 'Accept': 'application/json' } });
                    const result = await res.json();
                    if (result.status === 'success') {
                        renderSummary(result.data);
                        renderRevenueChart(result.data.revenue.monthly_trend);
                        renderCollectionChart(result.data.fee_collection.monthly_trend);
                        latestAttendanceBatches = result.data.attendance.batches;
                        populateBatchSelect(latestAttendanceBatches);
                        renderAttendanceChart();
                        renderDropoutChart(result.data.dropout.batches);
                        renderDropoutTable(result.data.dropout.batches);
                    }
                } catch (e) {
                    console.error('Analytics load error:', e);
                }
            }

            function renderSummary(data) {
                document.getElementById('stat-revenue').textContent = formatCurrency(data.revenue.current_month_total);
                const growth = data.revenue.growth_percent;
                const growthEl = document.getElementById('stat-revenue-growth');
                growthEl.textContent = (growth >= 0 ? '▲ ' : '▼ ') + Math.abs(growth) + '% vs last month';
                growthEl.className = 'text-[11px] font-bold mt-1 ' + (growth >= 0 ? 'text-emerald-500' : 'text-rose-500');

                document.getElementById('stat-collection').textContent = data.fee_collection.overall_percent + '%';
                document.getElementById('stat-collection-sub').textContent =
                    formatCurrency(data.fee_collection.total_collected) + ' of ' + formatCurrency(data.fee_collection.total_billed);

                document.getElementById('stat-attendance').textContent = data.attendance.overall_percent_this_month + '%';

                document.getElementById('stat-dropout').textContent = data.dropout.overall_rate + '%';
                document.getElementById('stat-dropout-sub').textContent =
                    data.dropout.total_inactive + ' inactive of ' + (data.dropout.total_active + data.dropout.total_inactive) + ' students';
            }

            function renderRevenueChart(trend) {
                const ctx = document.getElementById('revenueChart').getContext('2d');
                if (revenueChart) revenueChart.destroy();
                revenueChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: trend.map(t => t.month),
                        datasets: [{
                            label: 'Total Revenue',
                            data: trend.map(t => t.total_revenue),
                            borderColor: '#ff6c00',
                            backgroundColor: 'rgba(255,108,0,0.08)',
                            fill: true,
                            tension: 0.35,
                            pointRadius: 3,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#F1F5F9' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            function renderCollectionChart(trend) {
                const ctx = document.getElementById('collectionChart').getContext('2d');
                if (collectionChart) collectionChart.destroy();
                collectionChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: trend.map(t => t.month),
                        datasets: [{
                            label: 'Collection %',
                            data: trend.map(t => t.percent),
                            backgroundColor: '#10b981',
                            borderRadius: 6,
                            maxBarThickness: 36,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, max: 100, grid: { color: '#F1F5F9' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            function populateBatchSelect(batches) {
                const select = document.getElementById('attendance-batch-select');
                const current = select.value;
                select.innerHTML = '<option value="all">All Batches (avg)</option>' +
                    batches.map(b => `<option value="${b.batch_id}">${b.batch_name}</option>`).join('');
                select.value = current || 'all';
            }

            function renderAttendanceChart() {
                const select = document.getElementById('attendance-batch-select');
                const selected = select.value;
                const ctx = document.getElementById('attendanceChart').getContext('2d');
                if (attendanceChart) attendanceChart.destroy();

                let labels = [];
                let dataPoints = [];

                if (selected === 'all') {
                    if (latestAttendanceBatches.length > 0) {
                        labels = latestAttendanceBatches[0].trend.map(t => t.month);
                        dataPoints = labels.map((_, idx) => {
                            const values = latestAttendanceBatches
                                .map(b => b.trend[idx].percent)
                                .filter(v => v !== null && v !== undefined);
                            if (values.length === 0) return null;
                            return Math.round((values.reduce((a, b) => a + b, 0) / values.length) * 10) / 10;
                        });
                    }
                } else {
                    const batch = latestAttendanceBatches.find(b => String(b.batch_id) === String(selected));
                    if (batch) {
                        labels = batch.trend.map(t => t.month);
                        dataPoints = batch.trend.map(t => t.percent);
                    }
                }

                attendanceChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Attendance %',
                            data: dataPoints,
                            borderColor: '#2563EB',
                            backgroundColor: 'rgba(37,99,235,0.08)',
                            fill: true,
                            tension: 0.35,
                            spanGaps: true,
                            pointRadius: 3,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, max: 100, grid: { color: '#F1F5F9' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            function renderDropoutChart(batches) {
                const ctx = document.getElementById('dropoutChart').getContext('2d');
                if (dropoutChart) dropoutChart.destroy();
                dropoutChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: batches.map(b => b.batch_name),
                        datasets: [
                            {
                                label: 'Active',
                                data: batches.map(b => b.active_count),
                                backgroundColor: '#10b981',
                                borderRadius: 4,
                                maxBarThickness: 28,
                            },
                            {
                                label: 'Inactive',
                                data: batches.map(b => b.inactive_count),
                                backgroundColor: '#f43f5e',
                                borderRadius: 4,
                                maxBarThickness: 28,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10, weight: 'bold' } } } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#F1F5F9' } },
                            x: { stacked: false, grid: { display: false } }
                        }
                    }
                });
            }

            function renderDropoutTable(batches) {
                const body = document.getElementById('dropout-table-body');
                if (batches.length === 0) {
                    body.innerHTML = '<tr><td colspan="4" class="px-3 py-4 text-center text-slate-400">No batches yet</td></tr>';
                    return;
                }
                body.innerHTML = batches.map(b => `
                    <tr>
                        <td class="px-3 py-2.5">${b.batch_name}</td>
                        <td class="px-3 py-2.5 text-emerald-600">${b.active_count}</td>
                        <td class="px-3 py-2.5 text-rose-500">${b.inactive_count}</td>
                        <td class="px-3 py-2.5">${b.dropout_rate}%</td>
                    </tr>
                `).join('');
            }

            document.addEventListener('DOMContentLoaded', loadAnalytics);
        </script>
    @endpush
@endsection
