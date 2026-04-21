@extends('layouts.institute')

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto pb-10">
    <!-- Toast Notifications Container -->
    <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

    <!-- Page Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Fee Management</h1>
            <p class="text-sm text-slate-400 mt-2 font-medium">Track student payments, dues, and financial records.</p>
        </div>
        <div class="flex items-center gap-4">
            <select id="student-search" onchange="loadStudentFees()" class="px-5 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold shadow-sm outline-none focus:ring-4 focus:ring-blue-500/5 transition-all min-w-[250px]">
                <option value="">Search Student...</option>
                <!-- Students loaded vs JS -->
            </select>
            <button onclick="openFeeModal()" class="px-6 py-3 bg-[#1e3a8a] text-white rounded-2xl font-bold text-[13px] shadow-lg shadow-blue-900/10 hover:scale-[1.02] transition-transform">
                + Create Fee Record
            </button>
        </div>
    </div>

    <!-- Stats Row (Dynamic) -->
    <div id="fee-stats-row" class="hidden grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center space-x-4">
            <div class="h-12 w-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Paid So Far</p>
                <h3 id="stat-paid" class="text-2xl font-extrabold text-slate-800 tracking-tight">₹0</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center space-x-4">
            <div class="h-12 w-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Outstanding Due</p>
                <h3 id="stat-due" class="text-2xl font-extrabold text-slate-800 tracking-tight text-rose-600">₹0</h3>
            </div>
        </div>
    </div>

    <!-- Fee Records Table -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/20">
            <h3 class="text-sm font-extrabold text-slate-700 uppercase tracking-widest">Fee Log</h3>
            <div id="loading-spinner" class="hidden h-5 w-5 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin"></div>
        </div>

        <div class="overflow-x-auto min-h-[200px]">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] uppercase font-extrabold text-slate-400 tracking-widest border-b border-slate-50">
                        <th class="px-8 py-5">Month / Period</th>
                        <th class="px-8 py-5">Total Amount</th>
                        <th class="px-8 py-5">Due Amount</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="fee-table-body" class="divide-y divide-slate-50">
                    <tr><td colspan="5" class="px-8 py-20 text-center text-slate-400 font-medium italic">Please search for a student to view financial records.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Fee Modal -->
<div id="fee-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <div onclick="closeFeeModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="bg-white w-full max-w-xl rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="p-10">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Create Fee Record</h2>
                    <p class="text-sm text-slate-400 mt-1">Generate a new monthly fee invoice for the student.</p>
                </div>
                <button onclick="closeFeeModal()" class="h-10 w-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="fee-form" class="space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Month</label>
                        <select name="month" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none">
                            <option value="January">January</option><option value="February">February</option>
                            <option value="March">March</option><option value="April">April</option>
                            <option value="May">May</option><option value="June">June</option>
                            <option value="July">July</option><option value="August">August</option>
                            <option value="September">September</option><option value="October">October</option>
                            <option value="November">November</option><option value="December">December</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Year</label>
                        <input type="number" name="year" value="{{ date('Y') }}" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Total Fee Amount</label>
                    <input type="number" name="total_amount" required placeholder="e.g. 5000" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none">
                </div>

                <div class="pt-6 border-t border-slate-50 flex items-center justify-end space-x-4">
                    <button type="button" onclick="closeFeeModal()" class="px-8 py-3.5 text-[13px] font-bold text-slate-400">Cancel</button>
                    <button type="submit" class="px-10 py-3.5 bg-[#1e3a8a] text-white rounded-2xl font-bold text-[13px] shadow-lg flex items-center">
                        Confirm Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Record Payment Modal -->
<div id="payment-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <div onclick="closePaymentModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="bg-white w-full max-w-xl rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="p-10">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Record Payment</h2>
                    <p class="text-sm text-slate-400 mt-1">Update student balance for <span id="payment-month-label" class="text-blue-600 font-bold">...</span></p>
                </div>
                <button onclick="closePaymentModal()" class="h-10 w-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="payment-form" class="space-y-5">
                <input type="hidden" name="fee_id" id="payment-fee-id">
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Payment Amount</label>
                    <input type="number" name="amount" id="payment-amount" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none">
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Method</label>
                    <select name="payment_method" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none">
                        <option value="Cash">Cash</option><option value="UPI">UPI</option>
                        <option value="Bank Transfer">Bank Transfer</option><option value="Cheque">Cheque</option>
                    </select>
                </div>

                <div class="pt-6 border-t border-slate-50 flex items-center justify-end space-x-4">
                    <button type="button" onclick="closePaymentModal()" class="px-8 py-3.5 text-[13px] font-bold text-slate-400">Cancel</button>
                    <button type="submit" class="px-10 py-3.5 bg-emerald-600 text-white rounded-2xl font-bold text-[13px] shadow-lg flex items-center">
                        Verify Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const CSRF_TOKEN = "{{ csrf_token() }}";
    
    document.addEventListener('DOMContentLoaded', () => fetchStudents());

    async function fetchStudents() {
        try {
            const resp = await fetch("/api/v1/institute/students", { headers: { 'Accept': 'application/json' } });
            const res = await resp.json();
            if (res.status === 'success') {
                const sel = document.getElementById('student-search');
                res.data.items.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.id;
                    opt.innerText = `${s.name} (STU-${String(s.id).padStart(4, '0')})`;
                    sel.appendChild(opt);
                });
            }
        } catch (e) { showToast('Sync error', 'error'); }
    }

    async function loadStudentFees() {
        const id = document.getElementById('student-search').value;
        if (!id) return;

        toggleLoader(true);
        try {
            const resp = await fetch(`/api/v1/institute/fees/${id}`, { headers: { 'Accept': 'application/json' } });
            const res = await resp.json();
            if (res.status === 'success') {
                renderFees(res.data);
                updateStats(res.data);
            }
        } catch (e) { showToast('Load error', 'error'); }
        finally { toggleLoader(false); }
    }

    function renderFees(fees) {
        const container = document.getElementById('fee-table-body');
        if (fees.length === 0) {
            container.innerHTML = `<tr><td colspan="5" class="px-8 py-20 text-center text-slate-400 font-medium italic">No fee records found for this student.</td></tr>`;
            return;
        }

        container.innerHTML = fees.map(fee => `
            <tr class="hover:bg-slate-50/40 transition-all">
                <td class="px-8 py-6">
                    <div class="flex flex-col">
                        <span class="text-[13px] font-extrabold text-slate-800">${fee.month} ${fee.year}</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1 italic">Monthly Cycle</span>
                    </div>
                </td>
                <td class="px-8 py-6 font-bold text-slate-600">₹${fee.total_amount}</td>
                <td class="px-8 py-6 font-bold text-rose-500">₹${fee.due_amount}</td>
                <td class="px-8 py-6">
                    <span class="px-3 py-1 rounded-full text-[9px] font-extrabold uppercase tracking-widest ${fee.status === 'Paid' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'}">${fee.status}</span>
                </td>
                <td class="px-8 py-6 text-right">
                    ${fee.status !== 'Paid' ? `<button onclick='openPaymentModal(${JSON.stringify(fee)})' class="px-5 py-2 bg-emerald-50 text-emerald-600 rounded-xl text-[11px] font-extrabold hover:bg-emerald-600 hover:text-white transition-all">Collect Fee</button>` : `<span class="text-emerald-500 font-bold text-xs flex items-center justify-end"><svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Settled</span>`}
                </td>
            </tr>
        `).join('');
    }

    function updateStats(fees) {
        let paid = 0, due = 0;
        fees.forEach(f => { paid += parseFloat(f.paid_amount); due += parseFloat(f.due_amount); });
        document.getElementById('stat-paid').innerText = `₹${paid}`;
        document.getElementById('stat-due').innerText = `₹${due}`;
        document.getElementById('fee-stats-row').classList.remove('hidden');
    }

    // Modal Handling & Submissions
    document.getElementById('fee-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const f = new FormData(e.target);
        const id = document.getElementById('student-search').value;
        f.append('student_id', id);

        const resp = await fetch("/api/v1/institute/fees", {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: JSON.stringify(Object.fromEntries(f.entries()))
        });
        const res = await resp.json();
        if (res.status === 'success') { showToast(res.message); closeFeeModal(); loadStudentFees(); }
        else showToast(res.message, 'error');
    });

    document.getElementById('payment-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const f = new FormData(e.target);
        const resp = await fetch("/api/v1/institute/payments", {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: JSON.stringify(Object.fromEntries(f.entries()))
        });
        const res = await resp.json();
        if (res.status === 'success') { showToast(res.message); closePaymentModal(); loadStudentFees(); }
        else showToast(res.message, 'error');
    });

    function openFeeModal() { 
        if(!document.getElementById('student-search').value) return showToast('Select student first', 'error');
        document.getElementById('fee-modal').classList.remove('hidden'); 
    }
    function closeFeeModal() { document.getElementById('fee-modal').classList.add('hidden'); }
    function openPaymentModal(fee) {
        document.getElementById('payment-fee-id').value = fee.id;
        document.getElementById('payment-amount').value = fee.due_amount;
        document.getElementById('payment-month-label').innerText = `${fee.month} ${fee.year}`;
        document.getElementById('payment-modal').classList.remove('hidden');
    }
    function closePaymentModal() { document.getElementById('payment-modal').classList.add('hidden'); }
    function toggleLoader(show) { document.getElementById('loading-spinner').classList.toggle('hidden', !show); }
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        const color = type === 'success' ? 'emerald' : 'rose';
        toast.className = `bg-${color}-50 border border-${color}-200 text-${color}-600 px-6 py-4 rounded-2xl shadow-xl flex items-center animate-in slide-in-from-right-10 duration-300`;
        toast.innerHTML = `<span class="text-sm font-bold">${message}</span>`;
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
</script>
@endsection
