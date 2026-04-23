@extends('layouts.institute')

@section('content')
<div class="space-y-2 max-w-[1600px] mx-auto pb-5">
    <!-- Toast Notifications Container -->
    <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

    <!-- Page Header & Stats -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 bg-white p-5 rounded-[1rem] border border-slate-100 shadow-sm">
        <div class="flex-1">
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Fee Management</h1>
            <p class="text-sm text-slate-400 mt-1 font-medium">Overview of all student payments and financial records.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-6">
            <!-- Total Collected Stat (Repositioned) -->
            <div class="flex items-center gap-4 bg-emerald-50/50 px-6 py-3 rounded-2xl border border-emerald-100">
                <div class="h-10 w-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p id="stat-label" class="text-[10px] font-bold text-emerald-600/60 uppercase tracking-widest leading-none mb-1.5">Collection</p>
                    <h3 id="stat-paid" class="text-xl font-black text-slate-800">₹0</h3>
                </div>
            </div>

            <button onclick="openFeeModal()" class="px-8 py-4 bg-[#1e3a8a] text-white rounded-2xl font-bold text-[13px] shadow-xl shadow-blue-900/20 hover:scale-[1.02] active:scale-95 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                Create Fee Record
            </button>
        </div>
    </div>

    <!-- Fee Records Table -->
    <div class="bg-white rounded-[1rem] shadow-sm border border-slate-100 overflow-hidden mt-4">
        <div class="relative min-h-[300px]">
            <!-- Centered Loading Spinner Overlay -->
            <div id="loading-spinner" class="hidden absolute inset-0 z-30 bg-white/70 backdrop-blur-[2px] flex items-center justify-center transition-all duration-300">
                <div class="flex flex-col items-center gap-4">
                    <div class="relative">
                        <div class="h-12 w-12 border-4 border-slate-100 border-t-blue-600 rounded-full animate-spin"></div>
                        <div class="absolute inset-0 h-12 w-12 border-4 border-blue-600/20 rounded-full"></div>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] animate-pulse">Syncing Data</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] uppercase font-extrabold text-slate-400 tracking-widest border-b border-slate-50 bg-slate-50/30">
                            <th class="px-5 py-4">Student</th>
                            <th class="px-5 py-4">Date</th>
                            <th class="px-5 py-4">Total Amount</th>
                            <th class="px-5 py-4 text-right">Amount Paid</th>
                        </tr>
                    </thead>
                    <tbody id="fee-table-body" class="divide-y divide-slate-50">
                        <tr><td colspan="4" class="px-5 py-20 text-center text-slate-400 font-medium italic">Loading records...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="p-8 border-t border-slate-50 flex items-center justify-between bg-slate-50/10">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Showing <span id="current-range" class="text-slate-700">0-0</span> of <span id="total-records" class="text-slate-700">0</span> records</p>
            <div class="flex items-center gap-2">
                <button id="prev-page" onclick="changePage(-1)" class="h-10 px-4 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 disabled:opacity-30 disabled:cursor-not-allowed transition-all">Previous</button>
                <div id="page-numbers" class="flex items-center gap-1"></div>
                <button id="next-page" onclick="changePage(1)" class="h-10 px-4 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 disabled:opacity-30 disabled:cursor-not-allowed transition-all">Next</button>
            </div>
        </div>
    </div>
</div>

<!-- Create Fee Modal -->
<div id="fee-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <div onclick="closeFeeModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="bg-white w-full max-w-xl rounded-[1rem] shadow-2xl relative z-10 overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Create Fee Record</h2>
                    <p class="text-sm text-slate-400 mt-1">Generate a new monthly fee invoice for the student.</p>
                </div>
                <button onclick="closeFeeModal()" class="h-10 w-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="fee-form" class="space-y-3">
                <!-- Searchable Student Selection -->
                <div class="space-y-2 relative">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Select Scholar</label>
                    <div class="relative group" id="student-search-container">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" id="student-search-input" placeholder="Search by name or student ID..." autocomplete="off" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                        <input type="hidden" name="student_id" id="selected-student-id" required>
                        
                        <!-- Search Results Dropdown -->
                        <div id="student-dropdown" class="hidden absolute left-0 right-0 mt-2 bg-white border border-slate-100 rounded-2xl shadow-xl z-50 max-h-[250px] overflow-y-auto animate-in fade-in slide-in-from-top-2 duration-200">
                            <div id="student-options-list" class="p-2 space-y-1">
                                <!-- Populated via JS -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Fee Date</label>
                    <input type="date" name="fee_date" id="modal-fee-date" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Total Fee Amount</label>
                    <input type="number" name="total_amount" required placeholder="e.g. 5000" class="w-full px-3 py-2 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none">
                </div>

                <div class="pt-4 border-t border-slate-50 flex items-center justify-end space-x-4">
                    <button type="button" onclick="closeFeeModal()" class="px-8 py-2.5 text-[13px] font-bold text-slate-400">Cancel</button>
                    <button type="submit" class="px-10 py-2.5 bg-emerald-600 text-white rounded-2xl font-bold text-[13px] shadow-lg flex items-center">
                        Create & Collect Fee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    const CSRF_TOKEN = "{{ csrf_token() }}";
    
    let currentPage = 1;
    let totalPages = 1;

    let allStudents = [];
    
    document.addEventListener('DOMContentLoaded', () => {
        fetchStudents();
        loadAllFees(1);
        setupStudentSearch();
    });

    function setupStudentSearch() {
        const input = document.getElementById('student-search-input');
        const dropdown = document.getElementById('student-dropdown');
        const list = document.getElementById('student-options-list');
        const hiddenInput = document.getElementById('selected-student-id');

        input.addEventListener('focus', () => {
            if (input.value.trim() !== '' || allStudents.length > 0) {
                renderStudentOptions(input.value);
                dropdown.classList.remove('hidden');
            }
        });

        input.addEventListener('input', (e) => {
            renderStudentOptions(e.target.value);
            dropdown.classList.remove('hidden');
            hiddenInput.value = ''; // Clear selection on type
        });

        document.addEventListener('click', (e) => {
            if (!document.getElementById('student-search-container').contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    }

    function renderStudentOptions(query = '') {
        const list = document.getElementById('student-options-list');
        const filtered = allStudents.filter(s => 
            s.name.toLowerCase().includes(query.toLowerCase()) || 
            (s.student_id && s.student_id.toLowerCase().includes(query.toLowerCase()))
        );

        if (filtered.length === 0) {
            list.innerHTML = `<div class="p-4 text-center text-xs text-slate-400 italic">No scholars found matching "${query}"</div>`;
            return;
        }

        list.innerHTML = filtered.map(s => {
            const displayId = s.student_id || `STU-${String(s.id).padStart(4, '0')}`;
            return `
            <div onclick="selectStudent('${s.id}', '${s.name}', '${displayId}')" class="flex items-center justify-between p-3 hover:bg-blue-50 rounded-xl cursor-pointer transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-[10px] font-black group-hover:bg-blue-600 group-hover:text-white transition-all">
                        ${s.name.charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-slate-700">${s.name}</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">${displayId}</p>
                    </div>
                </div>
                <svg class="w-4 h-4 text-blue-600 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
            `;
        }).join('');
    }

    function selectStudent(id, name, studentId) {
        document.getElementById('selected-student-id').value = id;
        document.getElementById('student-search-input').value = `${name} (${studentId})`;
        document.getElementById('student-dropdown').classList.add('hidden');
    }

    async function fetchStudents() {
        try {
            const resp = await fetch("/api/v1/institute/students", { headers: { 'Accept': 'application/json' } });
            const res = await resp.json();
            if (res.status === 'success') {
                allStudents = res.data.items || res.data;
            }
        } catch (e) { 
            console.error(e);
            showToast('Failed to sync scholar list', 'error'); 
        }
    }

    function openFeeModal() { 
        document.getElementById('fee-modal').classList.remove('hidden');
        document.getElementById('fee-form').reset();
        document.getElementById('student-search-input').value = '';
        document.getElementById('selected-student-id').value = '';
    }

    async function loadAllFees(page = 1) {
        currentPage = page;
        toggleLoader(true);
        try {
            const resp = await fetch(`/api/v1/institute/fees?page=${page}`, { headers: { 'Accept': 'application/json' } });
            const res = await resp.json();
            if (res.status === 'success') {
                renderFees(res.data.items);
                updateStats(res.data);
                updatePagination(res.data);
            }
        } catch (e) { showToast('Load error', 'error'); }
        finally { toggleLoader(false); }
    }

    function changePage(delta) {
        const next = currentPage + delta;
        if (next >= 1 && next <= totalPages) {
            loadAllFees(next);
        }
    }

    function updatePagination(data) {
        totalPages = data.last_page;
        document.getElementById('total-records').innerText = data.total;
        
        const start = (data.current_page - 1) * data.per_page + 1;
        const end = Math.min(data.current_page * data.per_page, data.total);
        document.getElementById('current-range').innerText = data.total > 0 ? `${start}-${end}` : '0-0';

        document.getElementById('prev-page').disabled = data.current_page === 1;
        document.getElementById('next-page').disabled = data.current_page === data.last_page;

        const container = document.getElementById('page-numbers');
        let html = '';
        
        // Show all page numbers
        for (let i = 1; i <= totalPages; i++) {
            const isCurrent = i === data.current_page;
            html += `
                <button onclick="loadAllFees(${i})" 
                    class="h-10 w-10 flex items-center justify-center rounded-xl text-xs font-bold transition-all ${isCurrent ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-slate-500 hover:bg-slate-100 border border-transparent'}">
                    ${i}
                </button>
            `;
        }
        container.innerHTML = html;
    }

    function renderFees(fees) {
        const container = document.getElementById('fee-table-body');
        if (fees.length === 0) {
            container.innerHTML = `<tr><td colspan="4" class="px-8 py-20 text-center text-slate-400 font-medium italic">No fee records found.</td></tr>`;
            return;
        }

        container.innerHTML = fees.map(fee => {
            const name = fee.student?.name || 'Unknown';
            const initials = name.split(' ').filter(Boolean).map(n => n[0]).join('').substring(0, 2).toUpperCase();
            
            return `
            <tr class="hover:bg-slate-50/40 transition-all">
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-[10px] font-black">
                            ${initials || 'S'}
                        </div>
                        <span class="text-[13px] font-bold text-slate-700">${name}</span>
                    </div>
                </td>
                <td class="px-5 py-3">
                    <div class="flex flex-col">
                        <span class="text-[13px] font-bold text-slate-700">${new Date(fee.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })}</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1 italic">Payment Date</span>
                    </div>
                </td>
                <td class="px-5 py-3 font-bold text-slate-600">₹${fee.total_amount}</td>
                <td class="px-5 py-3 font-bold text-emerald-600 text-right">₹${fee.paid_amount}</td>
            </tr>
            `;
        }).join('');
    }

    function updateStats(data) {
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        const currentMonth = monthNames[new Date().getMonth()];
        
        document.getElementById('stat-label').innerText = `${currentMonth} Collection`;
        document.getElementById('stat-paid').innerText = `₹${Math.round(data.current_month_total || 0)}`;
    }

    // Modal Handling & Submissions
    document.getElementById('fee-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const f = new FormData(e.target);
        const data = Object.fromEntries(f.entries());
        
        // Smart Date Extraction for Backend Compatibility
        const dateObj = new Date(data.fee_date);
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        
        data.month = monthNames[dateObj.getMonth()];
        data.year = dateObj.getFullYear();
        data.paid_amount = data.total_amount;

        const resp = await fetch("/api/v1/institute/fees", {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: JSON.stringify(data)
        });
        const res = await resp.json();
        if (res.status === 'success') { 
            showToast(res.message); 
            closeFeeModal(); 
            loadAllFees(); 
        }
        else showToast(res.message, 'error');
    });

    function closeFeeModal() { document.getElementById('fee-modal').classList.add('hidden'); }
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
