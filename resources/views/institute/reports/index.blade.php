@extends('layouts.institute')

@section('content')
<div class="space-y-8 max-w-[1600px] mx-auto pb-10">
    <!-- Page Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Analytics & Reports</h1>
            <p class="text-sm text-slate-400 mt-2 font-medium">Deep dive into your institute's financial and academic metrics.</p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="fetchReportData()" class="p-3 bg-white border border-slate-200 text-slate-400 rounded-2xl hover:text-[#1e3a8a] transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </button>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Total Students</p>
                <h3 id="stat-students" class="text-3xl font-extrabold text-slate-800">--</h3>
                <div class="mt-4 flex items-center text-[11px] font-bold text-emerald-500">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5V5a1 1 0 011 1v5h-2a1 1 0 110-2V7.414l-3.293 3.293a1 1 0 01-1.414 0L4 8.414 5.414 7 12 7z" clip-rule="evenodd"></path></svg>
                    <span>Growth Active</span>
                </div>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Academic Batches</p>
            <h3 id="stat-batches" class="text-3xl font-extrabold text-slate-800">--</h3>
            <div class="mt-4 text-[11px] font-bold text-slate-400">Total Organized Cohorts</div>
        </div>
        <div class="bg-[#1e3a8a] p-8 rounded-[2.5rem] shadow-xl shadow-blue-900/10 text-white relative group overflow-hidden">
            <div class="relative z-10">
                <p class="text-[10px] font-extrabold text-blue-200 uppercase tracking-widest mb-2">Total Billing (Fees)</p>
                <h3 id="stat-fees" class="text-3xl font-extrabold">₹0</h3>
                <div class="mt-4 text-[11px] font-bold text-blue-200">Total Academic Revenue</div>
            </div>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all"></div>
        </div>
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Outstanding Dues</p>
            <h3 id="stat-dues" class="text-3xl font-extrabold text-rose-600">₹0</h3>
            <div class="mt-4 text-[11px] font-bold text-rose-500">Unpaid Balances</div>
        </div>
    </div>

    <!-- Charts & Reports Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Fees Distribution Table -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-50 bg-slate-50/20 flex justify-between items-center">
                <h4 class="text-sm font-extrabold text-slate-700 uppercase tracking-widest">Financial Summary</h4>
                <span id="loading-spinner" class="hidden h-5 w-5 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin"></span>
            </div>
            <div class="p-8">
                <div id="financial-content" class="space-y-6">
                    <div class="flex items-center justify-between p-6 bg-slate-50 rounded-3xl">
                        <span class="text-[13px] font-bold text-slate-500">Collected Amount</span>
                        <span id="sum-paid" class="text-lg font-extrabold text-emerald-600">₹0</span>
                    </div>
                    <div class="flex items-center justify-between p-6 bg-slate-50 rounded-3xl">
                        <span class="text-[13px] font-bold text-slate-500">Total Billed</span>
                        <span id="sum-total" class="text-lg font-extrabold text-slate-800">₹0</span>
                    </div>
                    <div class="flex items-center justify-between p-6 bg-slate-50 rounded-3xl">
                        <span class="text-[13px] font-bold text-slate-500">Pending Dues</span>
                        <span id="sum-due" class="text-lg font-extrabold text-rose-600">₹0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Summary -->
        <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col justify-center">
            <div class="text-center">
                <div class="h-16 w-16 bg-blue-50 rounded-2xl flex items-center justify-center text-[#1e3a8a] mx-auto mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <h4 class="text-xl font-extrabold text-slate-800 tracking-tight mb-2">Growth & Trends</h4>
                <p class="text-sm text-slate-400 font-medium px-10">Advanced visual charts and trend analysis will appear here as more data is collected over the session.</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => fetchReportData());

    async function fetchReportData() {
        toggleLoader(true);
        try {
            // General Dashboard Stats
            const dbResp = await fetch("/api/v1/institute/reports/dashboard", { headers: { 'Accept': 'application/json' } });
            const dbData = await dbResp.json();
            
            if (dbData.status === 'success') {
                document.getElementById('stat-students').innerText = dbData.data.students_count;
                document.getElementById('stat-batches').innerText = dbData.data.batches_count;
                document.getElementById('stat-fees').innerText = '₹' + dbData.data.total_fees;
                document.getElementById('stat-dues').innerText = '₹' + dbData.data.total_due_fees;
            }

            // Fee Summary Details
            const feeResp = await fetch("/api/v1/institute/reports/fees", { headers: { 'Accept': 'application/json' } });
            const feeData = await feeResp.json();

            if (feeData.status === 'success') {
                const s = feeData.data.summary;
                document.getElementById('sum-paid').innerText = '₹' + s.paid_amount;
                document.getElementById('sum-total').innerText = '₹' + s.total_amount;
                document.getElementById('sum-due').innerText = '₹' + s.due_amount;
            }

        } catch (error) {
            console.error('Report error:', error);
        } finally {
            toggleLoader(false);
        }
    }

    function toggleLoader(show) { document.getElementById('loading-spinner').classList.toggle('hidden', !show); }
</script>
@endsection
