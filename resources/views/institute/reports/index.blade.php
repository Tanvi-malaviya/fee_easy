@extends('layouts.institute')

@section('content')
<div class="space-y-3 max-w-[1600px] mx-auto pb-5 px-4 animate-in fade-in duration-500">
    <!-- Page Header -->
    <div id="header-container" class="mb-6">
        <div id="breadcrumb" class="hidden mb-2 items-center gap-2">
            <button onclick="exitDrillDown()" class="text-[10px] font-bold text-slate-400 uppercase tracking-wider hover:text-[#ff6c00] transition-colors flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                Back to Batches
            </button>
        </div>
        <h1 id="page-title" class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Reports Hub</h1>
        <p id="page-subtitle" class="text-xs sm:text-sm text-slate-500 font-medium mt-1.5 max-w-2xl leading-relaxed">Access real-time academic analytics, financial summaries, and student progress metrics through our centralized reporting engine.</p>
    </div>

    <style>
        .batch-card { transition: all 0.3s ease; }
        .batch-card:hover { transform: translateY(-2px); border-color: #f97316; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); }
    </style>

    <!-- Main Container -->
    <div id="reports-container" class="relative">
        <div id="loader" class="absolute inset-0 flex items-center justify-center bg-white/60 backdrop-blur-sm z-50 hidden rounded-xl">
            <div class="h-10 w-10 border-4 border-slate-200 border-t-[#f97316] rounded-full animate-spin"></div>
        </div>

        <!-- Section: Hub -->
        <div id="section-hub" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Attendance Card -->
                <div class="bg-white p-6 md:p-8 rounded-[24px] border border-slate-100/50 shadow-xl shadow-slate-200/40 flex flex-col justify-between hover:shadow-2xl hover:border-slate-200/80 transition-all duration-300">
                    <div>
                        <div class="h-12 w-12 bg-orange-50 rounded-xl flex items-center justify-center text-[#ff6c00] mb-6 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight mb-2">Attendance Report</h3>
                        <p class="text-xs md:text-sm text-slate-500 mt-1 font-medium leading-relaxed mb-6">Comprehensive analysis of daily, weekly, and monthly student presence across all active batches.</p>
                    </div>
                    <button onclick="switchTab('attendance')" class="w-full py-3 bg-[#ff6c00] hover:bg-[#e05f00] text-white text-xs md:text-sm font-extrabold rounded-xl shadow-md shadow-orange-500/20 transition-all text-center tracking-wider">
                        View Report
                    </button>
                </div>

                <!-- Fees Card -->
                <div class="bg-white p-6 md:p-8 rounded-[24px] border border-slate-100/50 shadow-xl shadow-slate-200/40 flex flex-col justify-between hover:shadow-2xl hover:border-slate-200/80 transition-all duration-300">
                    <div>
                        <div class="h-12 w-12 bg-teal-50 rounded-xl flex items-center justify-center text-teal-500 mb-6 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <h3 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight mb-2">Fee Collection Report</h3>
                        <p class="text-xs md:text-sm text-slate-500 mt-1 font-medium leading-relaxed mb-6">Track incoming revenue, pending dues, and scholarship distributions with granular filtering options.</p>
                    </div>
                    <button onclick="switchTab('fees')" class="w-full py-3 bg-[#ff6c00] hover:bg-[#e05f00] text-white text-xs md:text-sm font-extrabold rounded-xl shadow-md shadow-orange-500/20 transition-all text-center tracking-wider">
                        View Report
                    </button>
                </div>

                <!-- Performance Card -->
                <div class="bg-white p-6 md:p-8 rounded-[24px] border border-slate-100/50 shadow-xl shadow-slate-200/40 flex flex-col justify-between hover:shadow-2xl hover:border-slate-200/80 transition-all duration-300">
                    <div>
                        <div class="h-12 w-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500 mb-6 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                        <h3 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight mb-2">Student Performance Report</h3>
                        <p class="text-xs md:text-sm text-slate-500 mt-1 font-medium leading-relaxed mb-6">Deep dive into examination results, assignment completion rates, and individual student growth curves.</p>
                    </div>
                    <button onclick="switchTab('performance')" class="w-full py-3 bg-[#ff6c00] hover:bg-[#e05f00] text-white text-xs md:text-sm font-extrabold rounded-xl shadow-md shadow-orange-500/20 transition-all text-center tracking-wider">
                        View Report
                    </button>
                </div>
            </div>

            <!-- Recently Generated Reports -->
            <!-- <div class="space-y-2 mt-6">
                <h4 class="text-xs font-extrabold text-slate-800">Recently Generated Reports</h4>
                <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-slate-50 text-left">
                        <thead class="bg-slate-50 text-[9px] font-bold text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-3 py-2">Report Name</th>
                                <th class="px-3 py-2">Type</th>
                                <th class="px-3 py-2">Date</th>
                                <th class="px-3 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-[10px] font-bold text-slate-600">
                            <tr>
                                <td class="px-3 py-2 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-[#f97316] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>Batch-A Monthly Attendance</span>
                                </td>
                                <td class="px-3 py-2 text-slate-400">Attendance</td>
                                <td class="px-3 py-2 text-slate-400">Oct 24, 2024</td>
                                <td class="px-3 py-2"><span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-600 rounded-md text-[8px] font-bold">Completed</span></td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-[#f97316] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>Q3 Fee Collection Summary</span>
                                </td>
                                <td class="px-3 py-2 text-slate-400">Finance</td>
                                <td class="px-3 py-2 text-slate-400">Oct 22, 2024</td>
                                <td class="px-3 py-2"><span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-600 rounded-md text-[8px] font-bold">Completed</span></td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-[#f97316] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>Annual Performance Stats</span>
                                </td>
                                <td class="px-3 py-2 text-slate-400">Performance</td>
                                <td class="px-3 py-2 text-slate-400">Oct 20, 2024</td>
                                <td class="px-3 py-2"><span class="px-1.5 py-0.5 bg-blue-50 text-blue-600 rounded-md text-[8px] font-bold">Processing</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div> -->
        </div>

        <!-- Section: Fees -->
        <div id="section-fees" class="tab-section space-y-3 hidden">
            <div class="flex items-center justify-between">
                <h4 class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Revenue Analysis</h4>
                <button onclick="exportFees()" class="px-3 py-1.5 bg-white border border-slate-200 text-slate-700 rounded-lg font-bold text-xs shadow-sm hover:bg-slate-50 transition-all flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export
                </button>
            </div>

            <!-- Revenue Status Card -->
            <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden max-w-md">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-2">Revenue Overview</p>
                <div class="flex items-end gap-1.5 mb-2">
                    <h2 id="fee-total-collected" class="text-2xl font-black text-slate-800">₹0</h2>
                    <p class="text-sm font-bold text-slate-300 mb-0.5">/ <span id="fee-total-revenue">₹0</span></p>
                </div>

                <div class="w-full h-2 bg-slate-50 rounded-full overflow-hidden mb-3 border border-slate-100">
                    <div id="revenue-progress"
                        class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full transition-all duration-1000"
                        style="width: 0%"></div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="bg-slate-50/60 p-2 rounded-lg border border-slate-100">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Collected</p>
                        <h4 id="stat-total-collected" class="text-sm font-extrabold text-emerald-600">₹0</h4>
                    </div>
                    <div class="bg-slate-50/60 p-2 rounded-lg border border-slate-100">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Pending</p>
                        <h4 id="stat-total-pending" class="text-sm font-extrabold text-rose-500">₹0</h4>
                    </div>
                </div>
            </div>

            <div id="fee-batch-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2">
                <!-- Batch cards injected here -->
            </div>
        </div>
        </div>

        <!-- Section: Attendance -->
        <div id="section-attendance" class="tab-section space-y-3 hidden">
            <div class="flex items-center justify-between">
                <h4 class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Attendance Analytics</h4>
                <button onclick="exportAttendance()" class="px-3 py-1.5 bg-white border border-slate-200 text-slate-700 rounded-lg font-bold text-xs shadow-sm hover:bg-slate-50 transition-all flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export
                </button>
            </div>

            <!-- Weekly Graph Card -->
            <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm max-w-2xl">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 tracking-tight">Weekly Attendance Trend</h4>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Daily presence percentage across all batches</p>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="h-2 w-2 rounded-full bg-[#f97316]"></div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Presence %</span>
                    </div>
                </div>
                <div class="h-[180px] w-full relative">
                    <canvas id="weeklyAttendanceChart"></canvas>
                </div>
            </div>

            <div id="attendance-batch-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2">
                <!-- Batch cards injected here -->
            </div>
        </div>

        <!-- Drill-down View: Student Data -->
        <div id="section-drilldown" class="hidden space-y-3">
            <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="h-8 w-8 rounded-lg bg-orange-50 flex items-center justify-center text-[#f97316]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    </div>
                    <div>
                        <h4 id="drilldown-batch-name" class="text-sm font-bold text-slate-800 leading-none">Batch Name</h4>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Student Analytics Breakdown</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 pr-1">
                    <div class="text-right">
                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Aggregate</p>
                        <p id="drilldown-avg" class="text-sm font-black text-[#f97316] tracking-tight">0%</p>
                    </div>
                </div>
            </div>
            <div id="drilldown-student-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2">
                <!-- Student cards injected here -->
            </div>
        </div>

        <!-- Performance Section -->
        <div id="section-performance" class="tab-section hidden">
            <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm text-center max-w-sm mx-auto">
                <div class="h-12 w-12 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <h4 class="text-sm font-bold text-slate-800">Performance Tracking</h4>
                <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Metrics are being calibrated for the evaluation cycle.</p>
            </div>
        </div>
    </div>
</div>


<script>
    const API_REPORTS_URL = "/api/v1/institute/reports";
    const API_BATCHES_URL = "/api/v1/institute/batches";
    const API_STUDENTS_URL = "/api/v1/institute/students";
    const CSRF_TOKEN = "{{ csrf_token() }}";

    let currentTab = 'fees';
    let globalBatches = [];
    let globalFeeData = null;

    document.addEventListener('DOMContentLoaded', () => fetchAllReports());

    async function fetchAllReports() {
        toggleLoader(true);
        try {
            const [batchResp, feeResp] = await Promise.all([
                fetch(API_BATCHES_URL, { headers: { 'Accept': 'application/json' } }),
                fetch(`${API_REPORTS_URL}/fee`, { headers: { 'Accept': 'application/json' } })
            ]);

            globalBatches = (await batchResp.json()).data.items;
            globalFeeData = (await feeResp.json()).data;

            renderBatchCards();
        } catch (error) {
            console.error('Reports Error:', error);
        } finally {
            toggleLoader(false);
        }
    }

    function renderBatchCards() {
        if (currentTab === 'fees') renderFeeBatchCards();
        if (currentTab === 'attendance') renderAttendanceBatchCards();
    }

    function renderFeeBatchCards() {
        const summary = globalFeeData.summary;
        const total = summary.total_amount || 0;
        const collected = summary.paid_amount || 0;
        const pending = total - collected;
        const pct = total > 0 ? Math.min(100, Math.floor((collected / total) * 100)) : 0;
        
        document.getElementById('fee-total-collected').innerText = '₹' + collected;
        document.getElementById('fee-total-revenue').innerText = '₹' + total;
        document.getElementById('stat-total-collected').innerText = '₹' + collected;
        document.getElementById('stat-total-pending').innerText = '₹' + pending;
        document.getElementById('revenue-progress').style.width = pct + '%';

        container.innerHTML = globalBatches.map(batch => {
            const billed = batch.fees * (batch.students_count || 0);
            const paid = batch.total_paid || 0;
            const due = billed - paid;
            
            return `
                <div onclick="drillDownBatch(${batch.id}, '${batch.name.replace(/'/g, "\\'")}', 'fees')" class="batch-card bg-white p-3 rounded-xl border border-slate-100 shadow-sm transition-all duration-300 cursor-pointer group hover:border-[#f97316] hover:shadow-md relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="flex items-start justify-between mb-1.5">
                            <div>
                                <h4 class="text-xs font-bold text-slate-800 leading-tight group-hover:text-[#f97316] transition-colors">${batch.name}</h4>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Fee: ₹${batch.fees || 0}</p>
                            </div>
                            <span class="px-1.5 py-0.5 bg-slate-50 text-slate-500 rounded-md text-[8px] font-bold uppercase tracking-wider flex-shrink-0">${batch.students_count || 0} Scholars</span>
                        </div>
                    </div>
                    
                    <div class="mt-2 pt-2 border-t border-slate-50 grid grid-cols-2 gap-1">
                        <div>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Collected</p>
                            <p class="text-xs font-extrabold text-emerald-600 mt-0.5">₹${paid}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Pending</p>
                            <p class="text-xs font-extrabold ${due > 0 ? 'text-rose-500' : 'text-slate-300'} mt-0.5">₹${due}</p>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function renderAttendanceBatchCards() {
        const container = document.getElementById('attendance-batch-grid');
        container.innerHTML = globalBatches.map(batch => {
            const pct = Math.floor(Math.random() * (98 - 75 + 1) + 75); // Mock
            const color = pct > 90 ? 'emerald' : (pct > 80 ? 'blue' : 'amber');

            return `
                <div onclick="drillDownBatch(${batch.id}, '${batch.name.replace(/'/g, "\\'")}', 'attendance')" class="batch-card bg-white p-3 rounded-xl border border-slate-100 shadow-sm transition-all duration-300 cursor-pointer group hover:border-[#f97316] hover:shadow-md relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="flex items-start justify-between mb-1.5">
                            <div>
                                <h4 class="text-xs font-bold text-slate-800 leading-tight group-hover:text-[#f97316] transition-colors">${batch.name}</h4>
                                <span class="mt-0.5 px-1.5 py-0.5 bg-slate-50 text-slate-400 rounded-md text-[8px] font-bold uppercase tracking-wider inline-block">${batch.students_count || 0} Scholars</span>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Presence</p>
                                <p class="text-xs font-extrabold text-slate-800 leading-none mt-0.5">${pct}%</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-2 flex items-center gap-1.5">
                        <div class="flex-1 bg-slate-50 h-1.5 rounded-full overflow-hidden border border-slate-100/50">
                            <div class="bg-${color === 'blue' ? '[#f97316]' : color + '-500'} h-full rounded-full transition-all duration-500" style="width: ${pct}%"></div>
                        </div>
                        <span class="text-[8px] font-bold text-${color === 'blue' ? '[#f97316]' : color + '-600'} uppercase tracking-wider">${pct > 90 ? 'Perfect' : 'Stable'}</span>
                    </div>
                </div>
            `;
        }).join('');
        
        initAttendanceChart();
    }

    let attendanceChart = null;
    function initAttendanceChart() {
        const ctx = document.getElementById('weeklyAttendanceChart').getContext('2d');
        if (attendanceChart) attendanceChart.destroy();

        attendanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Attendance %',
                    data: [85, 92, 78, 88, 95, 82, 0],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.05)',
                    borderWidth: 4,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#3b82f6',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
                scales: {
                    y: { beginAtZero: true, max: 100, ticks: { font: { weight: 'bold', size: 10 }, color: '#94a3b8' }, grid: { display: false } },
                    x: { ticks: { font: { weight: 'bold', size: 10 }, color: '#94a3b8' }, grid: { display: false } }
                }
            }
        });
    }

    async function drillDownBatch(batchId, batchName, type) {
        toggleLoader(true);
        try {
            const resp = await fetch(`${API_STUDENTS_URL}?batch_id=${batchId}`, { headers: { 'Accept': 'application/json' } });
            const result = await resp.json();
            const students = result.data.items;

            document.getElementById('drilldown-batch-name').innerText = batchName;
            
            const container = document.getElementById('drilldown-student-grid');
            
            if (type === 'fees') {
                const batch = globalBatches.find(b => b.id == batchId);
                const paid = batch ? (batch.total_paid || 0) : 0;
                document.getElementById('drilldown-avg').innerText = 'COLLECTED: ₹' + paid;
                document.getElementById('drilldown-avg').classList.replace('text-blue-600', 'text-emerald-600');
                
                container.innerHTML = students.map(s => {
                    const due = s.total_due || 0;
                    return `
                        <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex items-center gap-3 hover:border-blue-500 hover:shadow-md transition-all duration-300">
                            <div class="h-10 w-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600 font-bold text-sm">${s.name.charAt(0).toUpperCase()}</div>
                            <div class="flex-1">
                                <h5 class="text-sm font-bold text-slate-900 leading-none">${s.name}</h5>
                            </div>
                            <div class="text-right">
                                <span class="px-2.5 py-1 ${due <= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'} rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                    ${due <= 0 ? 'Paid' : '₹'+due}
                                </span>
                            </div>
                        </div>
                    `;
                }).join('');
            } else if (type === 'attendance') {
                const pct = Math.floor(Math.random() * (98 - 75 + 1) + 75); // Mock
                document.getElementById('drilldown-avg').innerText = 'AVG PRESENT: ' + pct + '%';
                document.getElementById('drilldown-avg').classList.replace('text-emerald-600', 'text-blue-600');
                
                container.innerHTML = students.map(s => {
                    const sPct = Math.floor(Math.random() * (100 - 60 + 1) + 60); // Mock
                    return `
                        <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex items-center gap-3 hover:border-blue-500 hover:shadow-md transition-all duration-300">
                            <div class="h-10 w-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600 font-bold text-sm">${s.name.charAt(0).toUpperCase()}</div>
                            <div class="flex-1">
                                <h5 class="text-sm font-bold text-slate-900 leading-none">${s.name}</h5>
                                <div class="mt-2.5 flex items-center gap-2">
                                    <div class="flex-1 bg-slate-50 h-1.5 rounded-full overflow-hidden border border-slate-100/50">
                                        <div class="bg-blue-500 h-full rounded-full" style="width: ${sPct}%"></div>
                                    </div>
                                    <span class="text-[9px] font-bold text-blue-600">${sPct}%</span>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            // UI Transitions
            document.querySelectorAll('.tab-section').forEach(s => s.classList.add('hidden'));
            document.getElementById('section-hub').classList.add('hidden');
            document.getElementById('breadcrumb').classList.remove('hidden');
            document.getElementById('section-drilldown').classList.remove('hidden');
            document.getElementById('page-title').innerText = 'Batch Intelligence';

        } catch (error) {
            console.error('Drill-down Error:', error);
        } finally {
            toggleLoader(false);
        }
    }

    function exitDrillDown() {
        if (!document.getElementById('section-drilldown').classList.contains('hidden')) {
            document.getElementById('section-drilldown').classList.add('hidden');
            document.getElementById(`section-${currentTab}`).classList.remove('hidden');
            const titles = { 'fees': 'Fee Collection Report', 'attendance': 'Attendance Report', 'performance': 'Performance Report' };
            document.getElementById('page-title').innerText = titles[currentTab] || 'Report';
        } else {
            document.querySelectorAll('.tab-section').forEach(sec => sec.classList.add('hidden'));
            document.getElementById('section-hub').classList.remove('hidden');
            document.getElementById('breadcrumb').classList.add('hidden');
            document.getElementById('page-title').innerText = 'Analytics & Intelligence';
        }
    }

    function switchTab(tabId) {
        currentTab = tabId;
        document.getElementById('section-hub').classList.add('hidden');
        document.querySelectorAll('.tab-section').forEach(sec => sec.classList.add('hidden'));
        document.getElementById(`section-${tabId}`).classList.remove('hidden');
        document.getElementById('breadcrumb').classList.remove('hidden');
        
        const titles = { 'fees': 'Fee Collection Report', 'attendance': 'Attendance Report', 'performance': 'Performance Report' };
        document.getElementById('page-title').innerText = titles[tabId] || 'Report';
        
        renderBatchCards();
    }

    function toggleLoader(show) { document.getElementById('loader').classList.toggle('hidden', !show); }

    function exportFees() {
        window.location.href = '/api/v1/institute/fees/export';
    }

    function exportAttendance() {
        showToast('Attendance report generation started...', 'info');
        window.print();
    }
</script>
@endsection
