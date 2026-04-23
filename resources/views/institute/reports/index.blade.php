@extends('layouts.institute')

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto pb-10">
    <!-- Page Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <div id="breadcrumb" class="hidden mb-2 items-center gap-2">
                <button onclick="exitDrillDown()" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-blue-600 transition-colors flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    Back to Batches
                </button>
            </div>
            <h1 id="page-title" class="text-3xl font-black text-slate-800 tracking-tight mt-2">Analytics & Reports</h1>
        </div>
        <div class="flex items-center gap-3">
            <!-- <button onclick="fetchAllReports()" class="h-11 px-6 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold text-xs hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Refresh
            </button> -->
        </div>
    </div>

    <!-- Tab Navigation -->
    <div id="tab-nav" class="bg-white p-1 rounded-2xl border border-slate-100 shadow-sm inline-flex items-center gap-1">
        <button onclick="switchTab('fees')" id="tab-btn-fees" class="tab-btn active px-6 py-2 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all">Fees</button>
        <button onclick="switchTab('attendance')" id="tab-btn-attendance" class="tab-btn px-6 py-2 rounded-xl text-[11px] font-black uppercase tracking-widest text-slate-400 transition-all">Attendance</button>
        <button onclick="switchTab('performance')" id="tab-btn-performance" class="tab-btn px-8 py-2 rounded-xl text-[11px] font-black uppercase tracking-widest text-slate-400 transition-all">Performance</button>
    </div>

    <style>
        .tab-btn.active { background: #1e3a8a; color: white; box-shadow: 0 4px 12px -2px rgba(30, 58, 138, 0.2); }
        .batch-card:hover { transform: translateY(-2px); border-color: #3b82f6; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); }
    </style>

    <!-- Main Container -->
    <div id="reports-container" class="relative min-h-[400px]">
        <div id="loader" class="absolute inset-0 flex items-center justify-center bg-white/50 backdrop-blur-sm z-10 hidden rounded-[2.5rem]">
            <div class="h-10 w-10 border-4 border-slate-100 border-t-blue-600 rounded-full animate-spin"></div>
        </div>

        <!-- Section: Fees -->
        <div id="section-fees" class="tab-section space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Revenue</p>
                    <h3 id="fee-total-revenue" class="text-xl font-black text-slate-800 tracking-tight">₹0</h3>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                    <p class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mb-1">Collected</p>
                    <h3 id="fee-total-collected" class="text-xl font-black text-emerald-600 tracking-tight">₹0</h3>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                    <p class="text-[9px] font-black text-rose-500 uppercase tracking-widest mb-1">Outstanding</p>
                    <h3 id="fee-total-due" class="text-xl font-black text-rose-600 tracking-tight">₹0</h3>
                </div>
            </div>
            <div id="fee-batch-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <!-- Batch cards injected here -->
            </div>
        </div>

        <!-- Section: Attendance -->
        <div id="section-attendance" class="tab-section space-y-6 hidden">
            <div id="attendance-batch-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Batch cards injected here -->
            </div>
        </div>

        <!-- Drill-down View: Student Data -->
        <div id="section-drilldown" class="hidden space-y-4">
            <div class="bg-white p-3 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div>
                        <h4 id="drilldown-batch-name" class="text-lg font-black text-slate-800 leading-none">Batch Name</h4>
                    </div>
                </div>
                <div class="flex items-center gap-3 pr-1">
                    <div class="text-right">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Aggregate</p>
                        <p id="drilldown-avg" class="text-sm font-black text-blue-600 tracking-tight">0%</p>
                    </div>
                </div>
            </div>
            <div id="drilldown-student-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                <!-- Student cards injected here -->
            </div>
        </div>

        <!-- Performance Section -->
        <div id="section-performance" class="tab-section hidden">
            <div class="bg-white p-12 rounded-2xl border border-slate-100 shadow-sm text-center">
                <div class="h-16 w-16 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <h4 class="text-xl font-black text-slate-800 tracking-tight">Performance Tracking</h4>
                <p class="text-[12px] text-slate-400 font-bold mt-1 max-w-sm mx-auto leading-relaxed">Metrics are currently being calibrated for your next cycle.</p>
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
                fetch(`${API_REPORTS_URL}/fees`, { headers: { 'Accept': 'application/json' } })
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
        document.getElementById('fee-total-revenue').innerText = '₹' + summary.total_amount;
        document.getElementById('fee-total-collected').innerText = '₹' + summary.paid_amount;
        document.getElementById('fee-total-due').innerText = '₹' + summary.due_amount;

        const container = document.getElementById('fee-batch-grid');
        container.innerHTML = globalBatches.map(batch => {
            const billed = batch.fees * (batch.students_count || 0);
            const paid = batch.total_paid || 0;
            const due = billed - paid;
            
            return `
                <div onclick="drillDownBatch(${batch.id}, '${batch.name.replace(/'/g, "\\'")}', 'fees')" class="batch-card bg-white p-4 pt-5 rounded-2xl border border-slate-100 shadow-sm transition-all cursor-pointer group">
                    <div class="flex items-start justify-between mb-1">
                        <h4 class="text-[14px] font-black text-slate-800 leading-tight">${batch.name}</h4>
                        <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-lg text-[8px] font-black uppercase tracking-tight flex-shrink-0">${batch.students_count || 0} Scholars</span>
                    </div>
                    
                    <div class="mt-4 pt-3 border-t border-slate-50 grid grid-cols-2 gap-2">
                        <div>
                            <p class="text-[7px] font-black text-slate-300 uppercase tracking-widest">Collected</p>
                            <p class="text-[12px] font-black text-emerald-500">₹${paid}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[7px] font-black text-slate-300 uppercase tracking-widest">Pending</p>
                            <p class="text-[12px] font-black ${due > 0 ? 'text-rose-500' : 'text-slate-300'}">₹${due}</p>
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
                <div onclick="drillDownBatch(${batch.id}, '${batch.name.replace(/'/g, "\\'")}', 'attendance')" class="batch-card bg-white p-4 pt-5 rounded-2xl border border-slate-100 shadow-sm transition-all cursor-pointer group">
                    <div class="flex items-start justify-between mb-1">
                        <h4 class="text-[14px] font-black text-slate-800 leading-tight">${batch.name}</h4>
                        <div class="text-right flex-shrink-0">
                            <p class="text-[7px] font-black text-slate-300 uppercase tracking-widest">Avg Present</p>
                            <p class="text-[13px] font-black text-slate-800 leading-none mt-0.5">${pct}%</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex items-center gap-2">
                        <div class="flex-1 bg-slate-50 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-${color}-500 h-full" style="width: ${pct}%"></div>
                        </div>
                        <span class="text-[9px] font-black text-${color}-600 uppercase tracking-tighter">${pct > 90 ? 'Perfect' : 'Stable'}</span>
                    </div>
                </div>
            `;
        }).join('');
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
                document.getElementById('drilldown-avg').innerText = 'FEE STATUS';
                container.innerHTML = students.map(s => {
                    const due = s.total_due || 0;
                    return `
                        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3">
                            <div class="h-9 w-9 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400 font-black text-[11px]">${s.name.charAt(0)}</div>
                            <div class="flex-1">
                                <h5 class="text-[12px] font-black text-slate-800 leading-none">${s.name}</h5>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-0.5 ${due <= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'} rounded-lg text-[8px] font-black uppercase tracking-tight">
                                    ${due <= 0 ? 'Paid' : '₹'+due}
                                </span>
                            </div>
                        </div>
                    `;
                }).join('');
            } else if (type === 'attendance') {
                document.getElementById('drilldown-avg').innerText = 'ATTENDANCE %';
                container.innerHTML = students.map(s => {
                    const pct = Math.floor(Math.random() * (100 - 60 + 1) + 60); // Mock
                    return `
                        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3">
                            <div class="h-9 w-9 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400 font-black text-[11px]">${s.name.charAt(0)}</div>
                            <div class="flex-1">
                                <h5 class="text-[12px] font-black text-slate-800 leading-none">${s.name}</h5>
                                <div class="mt-2 flex items-center gap-2">
                                    <div class="flex-1 bg-slate-50 h-1 rounded-full overflow-hidden">
                                        <div class="bg-blue-500 h-full" style="width: ${pct}%"></div>
                                    </div>
                                    <span class="text-[8px] font-black text-blue-600">${pct}%</span>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            // UI Transitions
            document.querySelectorAll('.tab-section').forEach(s => s.classList.add('hidden'));
            document.getElementById('tab-nav').classList.add('hidden');
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
        document.getElementById('section-drilldown').classList.add('hidden');
        document.getElementById('breadcrumb').classList.add('hidden');
        document.getElementById('tab-nav').classList.remove('hidden');
        document.getElementById(`section-${currentTab}`).classList.remove('hidden');
        document.getElementById('page-title').innerText = 'Analytics & Reports';
    }

    function switchTab(tabId) {
        currentTab = tabId;
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active', 'text-white');
            btn.classList.add('text-slate-400');
        });
        document.getElementById(`tab-btn-${tabId}`).classList.add('active');
        document.getElementById(`tab-btn-${tabId}`).classList.remove('text-slate-400');

        document.querySelectorAll('.tab-section').forEach(sec => sec.classList.add('hidden'));
        document.getElementById(`section-${tabId}`).classList.remove('hidden');
        renderBatchCards();
    }

    function toggleLoader(show) { document.getElementById('loader').classList.toggle('hidden', !show); }
</script>
@endsection
