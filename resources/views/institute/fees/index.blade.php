@extends('layouts.institute')

@section('content')
<style>
    /* Hide spin-button for number input */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }
</style>
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
            @if(Auth::guard('institute')->user()->hasActiveSubscription())
            <button onclick="openFeeModal()" class="px-3 py-1.5 bg-[#f97316] hover:bg-[#ea580c] text-white rounded-lg font-bold text-xs shadow-sm transition-all flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                Add Transaction
            </button>
            @else
            <button onclick="handleExpiredSubscription(event)" class="px-3 py-1.5 bg-[#f97316] hover:bg-[#ea580c] text-white rounded-lg font-bold text-xs shadow-sm transition-all flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                Add Transaction
            </button>
            @endif
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
        <!-- Header -->
        <div class="py-3.5 px-5 bg-gradient-to-r from-[#e05f00] via-[#ff6c00] to-[#ff9f43] flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-white tracking-tight">Add Transaction</h2>
                <p class="text-[10px] text-white/80 mt-0.5">Generate a new fee invoice for the student.</p>
            </div>
            <button onclick="closeFeeModal()" class="h-8 w-8 text-white/80 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-5 sm:p-6">
            <form id="fee-form" class="space-y-3">
                <!-- Searchable Student Selection -->
                <div class="space-y-1 relative">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Select Student</label>
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
                    <!-- Selected Student Pending Fee Alert -->
                    <div id="selected-student-pending-fee" class="hidden mt-2 p-2 bg-amber-50 border border-amber-100 rounded-lg flex items-center justify-between text-[11px] font-bold text-amber-700 animate-in fade-in duration-200">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>Pending Fees:</span>
                        </div>
                        <span id="pending-fee-amount" class="text-xs font-black">₹0</span>
                    </div>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Amount</label>
                    <input type="number" name="total_amount" id="fee-amount-input" required min="1" step="0.01" disabled placeholder="Select a student first" class="w-full px-3 py-2 bg-slate-50 border border-slate-100 rounded-lg text-xs font-bold outline-none focus:ring-2 focus:ring-orange-500/20 transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                    <p id="fee-amount-hint" class="hidden text-[10px] font-bold text-slate-400 ml-1 mt-1"></p>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Fee Date</label>
                    <input type="date" name="date" id="modal-fee-date" required value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-100 rounded-lg text-xs font-bold outline-none focus:ring-2 focus:ring-orange-500/20 transition-all">
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

<!-- Receipt Modal -->
<div id="receipt-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <div onclick="closeReceiptModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="bg-[#f8fafc] w-[92%] sm:w-full max-w-3xl rounded-2xl shadow-2xl relative z-10 overflow-hidden animate-in fade-in zoom-in duration-300">
        <style>
            #receipt-modal-content::-webkit-scrollbar {
                display: none;
            }
            #receipt-modal-content {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>
        <div id="receipt-modal-content" class="max-h-[90vh] overflow-y-auto">
            <!-- Populated via AJAX -->
        </div>
    </div>
</div>

<!-- Empty State Template -->
<template id="fees-empty-state">
    <x-empty-state title="No transaction records found" subtitle="Log fee payments to see records here." icon="fees" />
</template>

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
            (s.enrollment_id && s.enrollment_id.toLowerCase().includes(query.toLowerCase()))
        );

        if (filtered.length === 0) {
            list.innerHTML = `<div class="p-2 text-center text-[10px] text-slate-400 italic">No scholars found matching "${query}"</div>`;
            return;
        }

        list.innerHTML = filtered.map(s => {
            const displayId = s.enrollment_id || `STU-${String(s.id).padStart(4, '0')}`;
            const hasRealImage = s.profile_image_url && !s.profile_image_url.includes('ui-avatars.com');
            const studentAvatar = hasRealImage
                ? `<img src="${s.profile_image_url}" alt="${s.name}" class="h-6 w-6 rounded-md object-cover border border-slate-100">`
                : `<div class="h-6 w-6 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center text-[9px] font-black group-hover:bg-[#f97316] group-hover:text-white transition-all">${s.name.charAt(0).toUpperCase()}</div>`;

            return `
            <div onclick="selectStudent('${s.id}', '${s.name}', '${displayId}')" class="flex items-center justify-between p-2 hover:bg-orange-50 rounded-lg cursor-pointer transition-colors group">
                <div class="flex items-center gap-2">
                    ${studentAvatar}
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

        // Show pending fee
        const student = allStudents.find(s => s.id == id);
        const amountInput = document.getElementById('fee-amount-input');
        const amountHint = document.getElementById('fee-amount-hint');
        if (student) {
            const pendingFeeDiv = document.getElementById('selected-student-pending-fee');
            const pendingAmountSpan = document.getElementById('pending-fee-amount');
            const pending = Number(student.total_due !== undefined ? student.total_due : 0);
            pendingAmountSpan.innerText = `₹${pending.toLocaleString('en-IN')}`;
            if (pending > 0) {
                pendingFeeDiv.classList.remove('hidden');
            } else {
                pendingFeeDiv.classList.add('hidden');
            }

            // Enable the amount field and cap it at the pending amount
            if (pending > 0) {
                amountInput.disabled = false;
                amountInput.max = pending;
                amountInput.value = '';
                amountInput.placeholder = `Max ₹${pending.toLocaleString('en-IN')}`;
                amountHint.innerText = `Cannot exceed pending fees of ₹${pending.toLocaleString('en-IN')}`;
                amountHint.classList.remove('hidden');
            } else {
                // Nothing pending — keep the amount locked
                amountInput.disabled = true;
                amountInput.removeAttribute('max');
                amountInput.value = '';
                amountInput.placeholder = 'No pending fees';
                amountHint.innerText = 'This student has no pending fees.';
                amountHint.classList.remove('hidden');
            }
        }
    }

    function clearStudentSelection() {
        document.getElementById('selected-student-id').value = '';
        document.getElementById('student-search-input').value = '';
        document.getElementById('student-search-input').readOnly = false;
        document.getElementById('clear-student-btn').classList.add('hidden');
        document.getElementById('selected-student-pending-fee').classList.add('hidden');

        // Lock the amount field again until a student is chosen
        const amountInput = document.getElementById('fee-amount-input');
        amountInput.disabled = true;
        amountInput.removeAttribute('max');
        amountInput.value = '';
        amountInput.placeholder = 'Select a student first';
        document.getElementById('fee-amount-hint').classList.add('hidden');

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
        
        fetchStudents();
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
            container.innerHTML = document.getElementById('fees-empty-state').innerHTML;
            return;
        }

        container.innerHTML = fees.map(fee => {
            const student = fee.student || {};
            const name = student.name || 'Unknown';
            const initials = name.split(' ').filter(Boolean).map(n => n[0]).join('').substring(0, 2).toUpperCase();
            
            // Format payment date
            const rawDate = fee.date;
            let formattedDate = 'N/A';
            if (rawDate) {
                const dateObj = new Date(rawDate);
                formattedDate = dateObj.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
            }

            const paidAmount = '₹' + parseFloat(fee.paid_amount || fee.total_amount || 0).toLocaleString('en-IN');

            const hasRealImage = student.profile_image_url && !student.profile_image_url.includes('ui-avatars.com');
            const avatarHtml = hasRealImage
                ? `<img src="${student.profile_image_url}" alt="${name}" class="h-8 w-8 rounded-full object-cover border-2 border-white shadow-sm">`
                : `<div class="h-8 w-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-xs font-bold border-2 border-white shadow-sm">${initials}</div>`;

            return `
            <div onclick="openReceiptModal(${fee.id})" class="bg-white rounded-xl border border-slate-100 hover:border-orange-200/60 shadow-sm p-2.5 flex flex-col justify-between hover:shadow-md cursor-pointer transition-all duration-300">
                <div class="space-y-2">
                    <!-- Top Row: Avatar & Student Details Inline -->
                    <div class="flex items-center gap-2">
                        ${avatarHtml}
                        <div class="min-w-0 flex-1">
                            <h3 class="text-[11.5px] font-black text-slate-800 truncate">${name}</h3>
                            <p class="text-[9px] text-slate-400 font-bold tracking-wider truncate">${student.enrollment_id || 'N/A'}</p>
                        </div>
                    </div>

                    <div class="border-t border-slate-50"></div>

                    <!-- Compact Details Row -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-[9.5px]">
                            <span class="text-slate-400 font-medium">PAID AMOUNT</span>
                            <span class="font-black text-emerald-600 bg-emerald-50 px-1 py-0.5 rounded">${paidAmount}</span>
                        </div>
                        <div class="flex items-center justify-between text-[9.5px]">
                            <span class="text-slate-400 font-medium">PAID DATE</span>
                            <span class="font-bold text-slate-700">${formattedDate}</span>
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

        // Guard: a student must be selected before collecting fees
        const studentId = document.getElementById('selected-student-id').value;
        if (!studentId) {
            showToast('Please select a student first.', 'error');
            return;
        }

        // Guard: amount must be positive and within the pending fees
        const amountInput = document.getElementById('fee-amount-input');
        const amount = parseFloat(amountInput.value);
        const maxPending = amountInput.max ? parseFloat(amountInput.max) : null;
        if (isNaN(amount) || amount <= 0) {
            showToast('Please enter a valid amount.', 'error');
            return;
        }
        if (maxPending !== null && amount > maxPending) {
            showToast(`Amount cannot be greater than pending fees of ₹${maxPending.toLocaleString('en-IN')}.`, 'error');
            return;
        }

        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalBtnHtml = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Saving...
        `;

        try {
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
                fetchStudents(); // Refresh students data
            }
            else showToast(res.message, 'error');
        } catch (error) {
            console.error(error);
            showToast('Something went wrong', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
        }
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

    async function openReceiptModal(feeId) {
        const modal = document.getElementById('receipt-modal');
        const content = document.getElementById('receipt-modal-content');
        
        content.innerHTML = `
            <div class="flex items-center justify-center p-12">
                <div class="h-6 w-6 border-2 border-slate-200 border-t-[#f97316] rounded-full animate-spin"></div>
            </div>
        `;
        modal.classList.remove('hidden');
        
        try {
            const resp = await fetch(`/institute/fees/receipts/${feeId}`);
            const htmlText = await resp.text();
            
            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlText, 'text/html');
            const card = doc.querySelector('.receipt-card');
            const style = doc.querySelector('style');
            
            if (card) {
                const actions = card.querySelector('.actions');
                if (actions) {
                    actions.remove();
                }
                
                card.style.boxShadow = 'none';
                card.style.border = 'none';
                
                content.innerHTML = `
                    <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-[#e05f00] via-[#ff6c00] to-[#ff9f43] sticky top-0 z-20">
                        <span class="text-sm font-black text-white tracking-tight">Receipt Details</span>
                        <div class="flex items-center gap-2">
                            <button onclick="closeReceiptModal()" class="px-3.5 py-1.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded-lg font-bold text-[11px] shadow-sm transition-all">Close</button>
                            <button onclick="downloadReceiptFromModal(${feeId})" class="px-3.5 py-1.5 bg-white text-[#ff6c00] hover:bg-slate-50 rounded-lg font-bold text-[11px] shadow-sm transition-all flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                Download Receipt
                            </button>
                        </div>
                    </div>
                    <div class="p-3">
                        <div id="receipt-card-wrapper" class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
                        </div>
                    </div>
                `;
                
                const wrapper = content.querySelector('#receipt-card-wrapper');
                
                // Add Outfit Google Font dynamically
                if (!document.getElementById('outfit-font-link')) {
                    const link = document.createElement('link');
                    link.id = 'outfit-font-link';
                    link.href = 'https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap';
                    link.rel = 'stylesheet';
                    document.head.appendChild(link);
                }

                if (style) {
                    let cssText = style.innerHTML;
                    // Delete body styles to prevent flexbox alignment from cutting off top content
                    cssText = cssText.replace(/body\s*{[^}]+}/g, '');
                    // Remove asterisk resets to prevent affecting parent DOM
                    cssText = cssText.replace(/\*\s*{[^}]+}/g, '');
                    
                    const newStyle = document.createElement('style');
                    newStyle.innerHTML = cssText;
                    wrapper.appendChild(newStyle);
                }
                
                wrapper.appendChild(card);
            } else {
                content.innerHTML = `<div class="p-5 text-center text-rose-500 font-bold">Failed to load receipt details.</div>`;
            }
        } catch (e) {
            console.error(e);
            content.innerHTML = `<div class="p-5 text-center text-rose-500 font-bold">Error loading receipt.</div>`;
        }
    }

    function closeReceiptModal() {
        document.getElementById('receipt-modal').classList.add('hidden');
    }

    function downloadReceiptFromModal(feeId) {
        window.location.href = `/institute/fees/receipts/${feeId}/download`;
    }

    function viewFee(id) {
        openReceiptModal(id);
    }

    function editFee(id) {
        showToast('Editing record ' + id, 'success');
    }

    function deleteFee(id) {
        showToast('Deleting record ' + id, 'error');
    }
</script>
@endsection
