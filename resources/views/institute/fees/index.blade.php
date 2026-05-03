@extends('layouts.institute')

@section('content')
<div class="space-y-2 max-w-[1600px] mx-auto pb-5 px-4 animate-in fade-in duration-500">
    <!-- Toast Notifications Container -->
    <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-3"></div>

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pl-1 pr-3 rounded-xl ">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Financial Ledger</h1>
            <p class="text-xs text-slate-400 mt-0.5 font-medium">Manage fee collections and student financial accounts.</p>
        </div>
        
        <div class="flex items-center gap-2">
            <button onclick="downloadFeeHistory()" class="px-3 py-1.5 bg-white border border-slate-200 text-slate-700 rounded-lg font-bold text-xs shadow-sm hover:bg-slate-50 transition-all flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Download PDF
            </button>
            <button onclick="openFeeModal()" class="px-3 py-1.5 bg-[#f97316] hover:bg-[#ea580c] text-white rounded-lg font-bold text-xs shadow-sm transition-all flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                Add Transaction
            </button>
        </div>
    </div>

    <!-- Filters & Stats Section -->
    <div class="flex flex-col sm:flex-row items-center gap-2 bg-white p-2 rounded-xl border border-slate-100 shadow-sm">
        <!-- Compact Total Collected Stat -->
        <div class="flex items-center gap-2 px-2 border-r border-slate-100 pr-3 sm:pr-4">
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Collected</p>
                <h3 id="stat-total-collected" class="text-sm font-extrabold text-slate-800">₹0</h3>
            </div>
            <div class="h-7 w-7 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-500 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <div class="w-full sm:w-64 relative sm:ml-auto">
            <input type="text" id="search-input" placeholder="Search by student name..." class="w-full pl-8 pr-12 py-1.5 bg-slate-50 border border-slate-100 rounded-lg text-xs font-bold outline-none focus:ring-2 focus:ring-orange-500/20 transition-all">
            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <button id="search-btn" onclick="loadAllFees(1)" class="absolute right-1 top-1 bg-[#f97316] hover:bg-[#ea580c] text-white font-extrabold text-[9px] px-2 py-1 rounded-md shadow-sm transition-all">
                Search
            </button>
        </div>
        <div class="flex gap-2">
            <select id="filter-batch" class="px-2 py-1.5 bg-slate-50 border border-slate-100 rounded-lg text-xs font-bold text-slate-600 outline-none focus:ring-2 focus:ring-orange-500/20 transition-all">
                <option value="">All Batches</option>
                <!-- Populated via JS -->
            </select>
        </div>
    </div>

    <!-- Fee Cards Grid -->
    <div class="relative min-h-[200px]">
        <!-- Loading Spinner Overlay -->
        <div id="loading-spinner" class="hidden absolute inset-0 z-30 bg-white/70 backdrop-blur-[1px] flex items-center justify-center transition-all duration-300">
            <div class="h-6 w-6 border-3 border-slate-100 border-t-[#f97316] rounded-full animate-spin"></div>
        </div>

        <div id="fee-cards-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2">
            <!-- Populated via JS -->
        </div>
    </div>

    <!-- Pagination -->
    <div id="pagination-bar" class="hidden p-2 bg-white rounded-xl border border-slate-100 shadow-sm flex items-center justify-between">
        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Showing <span id="current-range" class="text-slate-700">0-0</span> of <span id="total-records" class="text-slate-700">0</span></p>
        <div class="flex items-center gap-1">
            <button id="prev-page" onclick="changePage(-1)" class="h-7 px-2 rounded-lg border border-slate-200 text-[10px] font-bold text-slate-600 hover:bg-slate-50 disabled:opacity-30 disabled:cursor-not-allowed transition-all">Prev</button>
            <div id="page-numbers" class="flex items-center gap-1"></div>
            <button id="next-page" onclick="changePage(1)" class="h-7 px-2 rounded-lg border border-slate-200 text-[10px] font-bold text-slate-600 hover:bg-slate-50 disabled:opacity-30 disabled:cursor-not-allowed transition-all">Next</button>
        </div>
    </div>
</div>

<!-- Create Fee Modal -->
<div id="fee-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <div onclick="closeFeeModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="bg-white w-[92%] sm:w-full max-w-md rounded-2xl shadow-2xl relative z-10 overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="p-5 sm:p-6">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h2 class="text-lg font-extrabold text-slate-800 tracking-tight">Add Transaction</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Generate a new fee invoice for the student.</p>
                </div>
                <button onclick="closeFeeModal()" class="h-8 w-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="fee-form" class="space-y-3">
                <!-- Searchable Student Selection -->
                <div class="space-y-1 relative">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Select Scholar</label>
                    <div class="relative group" id="student-search-container">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" id="student-search-input" placeholder="Search by name or ID..." autocomplete="off" class="w-full pl-8 pr-20 py-2 bg-slate-50 border border-slate-100 rounded-lg text-xs font-bold outline-none focus:ring-2 focus:ring-orange-500/20 transition-all">
                        
                        <button type="button" id="clear-student-btn" onclick="clearStudentSelection()" class="hidden absolute right-2 top-1/2 -translate-y-1/2 px-2 py-1 bg-white border border-slate-200 text-[9px] font-black text-slate-400 rounded-md hover:text-orange-600 hover:border-orange-200 transition-all uppercase tracking-widest">
                            Change
                        </button>

                        <input type="hidden" name="student_id" id="selected-student-id" required>
                        
                        <!-- Search Results Dropdown -->
                        <div id="student-dropdown" class="hidden absolute left-0 right-0 mt-1 bg-white border border-slate-100 rounded-lg shadow-xl z-50 max-h-[200px] overflow-y-auto">
                            <div id="student-options-list" class="p-1 space-y-0.5">
                                <!-- Populated via JS -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Fee Date</label>
                    <input type="date" name="date" id="modal-fee-date" required value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-100 rounded-lg text-xs font-bold outline-none focus:ring-2 focus:ring-orange-500/20 transition-all">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Amount</label>
                    <input type="number" name="total_amount" required placeholder="e.g. 5000" class="w-full px-3 py-2 bg-slate-50 border border-slate-100 rounded-lg text-xs font-bold outline-none focus:ring-2 focus:ring-orange-500/20 transition-all">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Payment Method</label>
                    <div class="flex items-center gap-1 p-1 bg-slate-50 border border-slate-100 rounded-lg">
                        <label class="flex-1 cursor-pointer group">
                            <input type="radio" name="payment_method" value="Cash" checked class="peer sr-only">
                            <div class="py-1.5 text-center text-[10px] font-black text-slate-400 rounded-md transition-all peer-checked:bg-white peer-checked:text-[#f97316] peer-checked:shadow-sm">CASH</div>
                        </label>
                        <label class="flex-1 cursor-pointer group">
                            <input type="radio" name="payment_method" value="Online" class="peer sr-only">
                            <div class="py-1.5 text-center text-[10px] font-black text-slate-400 rounded-md transition-all peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm">ONLINE</div>
                        </label>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-50 flex items-center justify-end space-x-2">
                    <button type="button" onclick="closeFeeModal()" class="px-4 py-1.5 text-xs font-bold text-slate-400">Cancel</button>
                    <button type="submit" class="px-5 py-1.5 bg-[#f97316] text-white rounded-lg font-bold text-xs shadow-md hover:bg-[#ea580c] transition-all flex items-center">
                        Save Record
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
    let allBatches = [];

    document.addEventListener('DOMContentLoaded', () => {
        // fetchStudents(); // Optimized: Now lazy-loaded when modal opens
        fetchBatches();
        loadAllFees(1);
        setupStudentSearch();
        setupFilters();
    });

    async function fetchBatches() {
        try {
            const resp = await fetch("/api/v1/institute/batches", { headers: { 'Accept': 'application/json' } });
            const res = await resp.json();
            if (res.status === 'success') {
                allBatches = res.data.items || res.data;
                populateBatchFilter();
            }
        } catch (e) { console.error('Failed to fetch batches', e); }
    }

    function populateBatchFilter() {
        const select = document.getElementById('filter-batch');
        let html = '<option value="">All Batches</option>';
        allBatches.forEach(batch => {
            html += `<option value="${batch.id}">${batch.name}</option>`;
        });
        select.innerHTML = html;
    }

    function setupFilters() {
        const searchInput = document.getElementById('search-input');
        const filterBatch = document.getElementById('filter-batch');

        searchInput.addEventListener('keyup', (e) => {
            if (e.key === 'Enter') {
                loadAllFees(1);
            }
        });

        filterBatch.addEventListener('change', () => loadAllFees(1));
    }


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
            hiddenInput.value = ''; 
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
            list.innerHTML = `<div class="p-2 text-center text-[10px] text-slate-400 italic">No scholars found matching "${query}"</div>`;
            return;
        }

        list.innerHTML = filtered.map(s => {
            const displayId = s.student_id || `STU-${String(s.id).padStart(4, '0')}`;
            return `
            <div onclick="selectStudent('${s.id}', '${s.name}', '${displayId}')" class="flex items-center justify-between p-2 hover:bg-orange-50 rounded-lg cursor-pointer transition-colors group">
                <div class="flex items-center gap-2">
                    <div class="h-6 w-6 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center text-[9px] font-black group-hover:bg-[#f97316] group-hover:text-white transition-all">
                        ${s.name.charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-700">${s.name}</p>
                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">${displayId}</p>
                    </div>
                </div>
                <svg class="w-3.5 h-3.5 text-[#f97316] opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
            `;
        }).join('');
    }

    function selectStudent(id, name, studentId) {
        document.getElementById('selected-student-id').value = id;
        document.getElementById('student-search-input').value = `${name} (${studentId})`;
        document.getElementById('student-search-input').readOnly = true;
        document.getElementById('student-dropdown').classList.add('hidden');
        document.getElementById('clear-student-btn').classList.remove('hidden');
    }

    function clearStudentSelection() {
        document.getElementById('selected-student-id').value = '';
        document.getElementById('student-search-input').value = '';
        document.getElementById('student-search-input').readOnly = false;
        document.getElementById('clear-student-btn').classList.add('hidden');
        document.getElementById('student-search-input').focus();
    }

    async function fetchStudents() {
        try {
            const resp = await fetch("/api/v1/institute/students", { headers: { 'Accept': 'application/json' } });
            const res = await resp.json();
            if (res.status === 'success') {
                allStudents = res.data.items || res.data;
            }
        } catch (e) { console.error(e); }
    }

    function openFeeModal() { 
        document.getElementById('fee-modal').classList.remove('hidden');
        document.getElementById('fee-form').reset();
        clearStudentSelection();
        
        // Optimized: Lazy load students only if list is empty
        if (allStudents.length === 0) {
            fetchStudents();
        }
    }

    function closeFeeModal() { document.getElementById('fee-modal').classList.add('hidden'); }

    async function loadAllFees(page = 1) {
        currentPage = page;
        toggleLoader(true);
        
        const search = document.getElementById('search-input').value;
        const batchId = document.getElementById('filter-batch').value;

        let url = `/api/v1/institute/fees?page=${page}`;
        if (search) url += `&search=${encodeURIComponent(search)}`;
        if (batchId) url += `&batch_id=${batchId}`;

        try {
            const resp = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const res = await resp.json();
            if (res.status === 'success') {
                renderFees(res.data.items);
                updatePagination(res.data);
                // Optimized: Update stats from the same fees API response
                if (res.data.total_collected !== undefined) {
                    updateStats({ total_paid_fees: res.data.total_collected });
                }
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
        const bar = document.getElementById('pagination-bar');
        if (data.last_page > 1) {
            bar.classList.remove('hidden');
            bar.classList.add('flex');
        } else {
            bar.classList.add('hidden');
            bar.classList.remove('flex');
        }

        totalPages = data.last_page;
        document.getElementById('total-records').innerText = data.total;
        
        const start = (data.current_page - 1) * data.per_page + 1;
        const end = Math.min(data.current_page * data.per_page, data.total);
        document.getElementById('current-range').innerText = data.total > 0 ? `${start}-${end}` : '0-0';

        document.getElementById('prev-page').disabled = data.current_page === 1;
        document.getElementById('next-page').disabled = data.current_page === data.last_page;

        const container = document.getElementById('page-numbers');
        let html = '';
        
        for (let i = 1; i <= totalPages; i++) {
            const isCurrent = i === data.current_page;
            html += `
                <button onclick="loadAllFees(${i})" 
                    class="h-6 w-6 flex items-center justify-center rounded-md text-[10px] font-bold transition-all ${isCurrent ? 'bg-[#f97316] text-white shadow-md' : 'text-slate-500 hover:bg-slate-100 border border-transparent'}">
                    ${i}
                </button>
            `;
        }
        container.innerHTML = html;
    }

    function renderFees(fees) {
        const container = document.getElementById('fee-cards-container');
        if (fees.length === 0) {
            container.innerHTML = `<div class="col-span-full py-10 text-center text-slate-400 font-medium italic text-xs">No records found.</div>`;
            return;
        }

        const studentLastSeenFee = {};
        
        fees.forEach(fee => {
            const studentId = fee.student_id;
            const liveDue = parseFloat(fee.student?.total_due || 0);
            
            if (!studentLastSeenFee[studentId]) {
                fee.calculated_pending = liveDue;
                studentLastSeenFee[studentId] = fee;
            } else {
                const lastFee = studentLastSeenFee[studentId];
                fee.calculated_pending = lastFee.calculated_pending + parseFloat(lastFee.paid_amount || 0);
                studentLastSeenFee[studentId] = fee;
            }
        });

        container.innerHTML = fees.map(fee => {
            const student = fee.student || {};
            const name = student.name || 'Unknown';
            const initials = name.split(' ').filter(Boolean).map(n => n[0]).join('').substring(0, 2).toUpperCase();
            const batchName = student.batch?.name || 'No Batch';
            const totalFees = '₹' + parseFloat(student.monthly_fee || 0).toLocaleString('en-IN');
            const pendingFees = '₹' + parseFloat(fee.calculated_pending || 0).toLocaleString('en-IN');
            
            const totalDue = parseFloat(fee.calculated_pending || 0);
            const totalPaid = parseFloat(student.monthly_fee || 0) - totalDue;
            
            let statusClass = 'bg-emerald-50 text-emerald-600';
            let statusText = 'PAID';
            if (totalDue > 0 && totalPaid > 0) {
                statusClass = 'bg-orange-50 text-orange-600';
                statusText = 'PARTIAL';
            } else if (totalDue > 0 && totalPaid === 0) {
                statusClass = 'bg-rose-50 text-rose-600';
                statusText = 'DUE';
            }

            return `
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-3 flex flex-col justify-between hover:shadow-md transition-all">
                <div>
                    <!-- Top Row -->
                    <div class="flex items-center justify-between mb-2">
                        <div class="h-8 w-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-xs font-bold border-2 border-white shadow-sm">
                            ${initials}
                        </div>
                        <span class="px-1.5 py-0.5 rounded-full text-[8px] font-extrabold tracking-wider ${statusClass}">
                            ${statusText}
                        </span>
                    </div>

                    <!-- Student Info -->
                    <h3 class="text-[12px] font-bold text-slate-800 truncate">${name}</h3>
                    <p class="text-[9px] text-slate-400 font-medium flex items-center gap-1 mt-0.5">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        ${batchName}
                    </p>

                    <div class="border-t border-slate-50 my-1.5"></div>

                    <!-- Fee Details -->
                    <div class="space-y-0.5">
                        <div class="flex items-center justify-between text-[10px]">
                            <span class="text-slate-400 font-medium">TOTAL FEES</span>
                            <span class="font-bold text-slate-700">${totalFees}</span>
                        </div>
                        <div class="flex items-center justify-between text-[10px]">
                            <span class="text-slate-400 font-medium">PENDING</span>
                            <span class="font-bold ${totalDue > 0 ? (totalPaid > 0 ? 'text-orange-600' : 'text-rose-600') : 'text-slate-700'}">${pendingFees}</span>
                        </div>
                    </div>
                </div>
            </div>
            `;
        }).join('');
    }

    function updateStats(data) {
        const totalCollected = '₹' + Math.round(data.total_paid_fees || 0).toLocaleString('en-IN');
        const elCollected = document.getElementById('stat-total-collected');
        if (elCollected) elCollected.innerText = totalCollected;
    }

    document.getElementById('fee-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const f = new FormData(e.target);
        const data = Object.fromEntries(f.entries());
        
        // Amount entered is both total and paid
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

    async function downloadFeeHistory() {
        try {
            window.location.href = "/api/v1/institute/fees/export";
            showToast('Preparing your download...', 'success');
        } catch (e) {
            showToast('Download failed', 'error');
        }
    }

    function toggleLoader(show) { document.getElementById('loading-spinner').classList.toggle('hidden', !show); }
    
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        const color = type === 'success' ? 'emerald' : 'rose';
        toast.className = `bg-${color}-50 border border-${color}-200 text-${color}-600 px-4 py-2 rounded-xl shadow-lg flex items-center animate-in slide-in-from-right-5 duration-300`;
        toast.innerHTML = `<span class="text-xs font-bold">${message}</span>`;
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    function viewFee(id) {
        showToast('Viewing record ' + id, 'success');
    }

    function editFee(id) {
        showToast('Editing record ' + id, 'success');
    }

    function deleteFee(id) {
        showToast('Deleting record ' + id, 'error');
    }
</script>
@endsection
