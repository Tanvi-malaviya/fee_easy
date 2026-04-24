@extends('layouts.institute')

@section('content')
    <div class=" max-w-[1600px] mx-auto pb-10">
        <!-- Toast Notifications Container -->
        <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

        <!-- Main Container -->
        <div class=" rounded-[2rem] overflow-hidden relative min-h-[500px]">
            <!-- Header Toolbar: Combined Batch Info & Registry Actions -->
            <div
                class=" py-5 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/20">
                <div class="flex items-center gap-4">
                    <a href="{{ route('institute.batches.index') }}" onclick="if(document.referrer.indexOf(window.location.host) !== -1) { event.preventDefault(); window.history.back(); }"
                        class="h-10 w-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-blue-600 transition-all shadow-sm group">
                        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <div class="flex items-center gap-3">
                            <h1 id="batch-name-heading"
                                class="text-xl font-extrabold text-[#111827] tracking-tight line-clamp-1">Batch Details</h1>
                            <span id="batch-id-badge"
                                class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[9px] font-black rounded-md uppercase tracking-widest border border-blue-100 shadow-sm animate-pulse">Loading...</span>
                        </div>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Student
                                Registry</span>
                            <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                            <span id="batch-schedule-text" class="text-[11px] font-bold text-slate-500 tracking-tight">--:--
                                to --:--</span>
                            <div id="loading-indicator"
                                class="hidden h-3 w-3 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin ml-1">
                            </div>
                        </div>
                        <p id="batch-description-text"
                            class="text-[11px] font-bold text-slate-400 mt-1 max-w-xl line-clamp-1 italic">No description
                            available for this batch.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <div class="relative group">
                        <input type="text" id="student-search" onkeyup="filterStudents()" placeholder="Search scholars..."
                            class="px-4 py-2 bg-white border border-slate-100 rounded-xl text-xs font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all w-48 shadow-sm">
                        <svg class="w-4 h-4 text-slate-300 absolute right-3 top-2.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <!-- Mark Attendance Button -->
                    <a href="{{ route('institute.attendance.create') }}?batch_id={{ $id }}"
                        class="px-4 py-2 bg-blue-50 text-blue-600 rounded-xl font-bold text-[10px] border border-blue-100 hover:bg-blue-600 hover:text-white hover:shadow-lg hover:shadow-blue-200 transition-all flex items-center uppercase tracking-widest group">
                        <svg class="w-3.5 h-3.5 mr-1.5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2-2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Mark Attendance
                    </a>

                    <button onclick="openEnrollModal()"
                        class="px-4 py-2 bg-[#1e3a8a] text-white rounded-xl font-bold text-[10px] shadow-lg shadow-blue-900/10 hover:scale-[1.02] transition-transform flex items-center uppercase tracking-widest">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add student
                    </button>

                </div>
            </div>

            <!-- Batch Stats -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Stats Cards -->
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5">
                    <div class="h-12 w-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Total Students</p>
                        <h3 id="stat-students-count" class="text-xl font-black text-slate-800">--</h3>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5">
                    <div class="h-12 w-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest"> Fee</p>
                        <h3 id="stat-monthly-fee" class="text-xl font-black text-slate-800">₹--</h3>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5">
                    <div class="h-12 w-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Total Collection</p>
                        <h3 id="stat-total-paid" class="text-xl font-black text-slate-800">₹--</h3>
                    </div>
                </div>
            </div>

            <div class="">
                <div id="student-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <div class="col-span-full py-16 text-center">
                        <div class="h-8 w-8 border-4 border-slate-200 border-t-blue-600 rounded-full animate-spin mx-auto">
                        </div>
                        <p class="text-slate-400 font-medium text-sm mt-4">Loading scholars...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrollment Modal -->
        <div id="enroll-modal" class="fixed inset-0 z-[120] flex items-center justify-center hidden">
            <div onclick="closeEnrollModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div
                class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden animate-in fade-in zoom-in duration-300 max-h-[90vh] flex flex-col">

                <!-- Header with Actions -->
                <div class="p-6 border-b border-slate-100 flex-shrink-0 bg-white relative z-20">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight leading-none">Assign Students
                            </h2>
                            <p class="text-[11px] font-bold text-slate-400 mt-2 uppercase tracking-widest">Assigning to
                                <span id="target-batch-name" class="text-blue-600">this batch</span></p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button onclick="closeEnrollModal()"
                                class="px-4 py-2 text-[12px] font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</button>
                            <button id="confirm-enroll-btn" onclick="confirmEnrollment()" disabled
                                class="px-6 py-2.5 bg-blue-900 text-white rounded-xl font-bold text-[12px] shadow-lg shadow-blue-900/20 hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-50 disabled:grayscale">
                                Enroll Selected
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Search Section -->
                <div class="p-6 border-b border-slate-50 flex-shrink-0 bg-slate-50/30">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="enroll-search" onkeyup="searchEnrollableStudents()"
                            onfocus="document.getElementById('enrollable-dropdown').classList.remove('hidden')"
                            placeholder="Search available scholars by name or phone..."
                            class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-[13px] font-bold outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-300 transition-all shadow-sm">

                        <!-- Floating Dropdown -->
                        <div id="enrollable-dropdown"
                            class="absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl z-[50] hidden max-h-64 overflow-y-auto custom-scrollbar animate-in slide-in-from-top-2 duration-200">
                            <div id="enrollable-list" class="p-2 space-y-1">
                                <div class="py-6 text-center text-slate-400 font-medium italic text-[11px]">Start typing to
                                    find scholars...</div>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Chips -->
                    <div id="selected-chips"
                        class="mt-4 flex flex-wrap gap-2 min-h-[40px] p-2 bg-white/50 border border-dashed border-slate-200 rounded-xl">
                        <!-- Chips populated via JS -->
                    </div>
                </div>

                <!-- Preview Area -->
                <div id="enrollment-overview-section"
                    class="flex-1 flex flex-col items-center justify-center p-8 text-center bg-white overflow-hidden">
                    <div
                        class="h-16 w-16 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center mb-4 border border-blue-100/50">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-slate-800 font-bold text-sm">Enrollment Overview</h3>
                    <p class="text-slate-400 text-[11px] mt-1.5 max-w-[220px]">Pick scholars to assign to this batch. You
                        can customize fees for each student.</p>
                </div>

                <!-- Minimal Footer -->
                <div
                    class="px-6 py-4 border-t border-slate-50 flex items-center justify-center bg-slate-50/30 flex-shrink-0">
                    <div class="flex items-center gap-2">
                        <div class="h-2 w-2 rounded-full bg-blue-500 animate-pulse"></div>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest"><span
                                id="selected-count" class="text-blue-600">0</span> Scholars Ready</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Unenroll Confirmation Modal -->
    <div id="unenroll-modal" class="fixed inset-0 z-[130] flex items-center justify-center hidden">
        <div onclick="closeUnenrollModal()" class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px]"></div>
        <div
            class="bg-white w-full max-w-[320px] rounded-[2rem] shadow-2xl relative z-10 overflow-hidden p-6 text-center animate-in fade-in zoom-in duration-200">
            <div class="h-14 w-14 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-lg font-black text-slate-800 leading-tight">Remove Scholar?</h3>
            <p class="text-[11px] font-bold text-slate-400 mt-2 leading-relaxed">Are you sure you want to remove <span
                    id="unenroll-student-name" class="text-rose-500">this student</span> from the batch?</p>

            <div class="flex items-center gap-3 mt-6">
                <button onclick="closeUnenrollModal()"
                    class="flex-1 py-3 text-[12px] font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</button>
                <button id="confirm-unenroll-btn"
                    class="flex-1 py-3 bg-rose-500 text-white rounded-xl font-bold text-[12px] shadow-lg shadow-rose-200 active:scale-95 transition-all">Remove</button>
            </div>
        </div>
    </div>
    </div>



    <script>
        const BATCH_ID = "{{ $id }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";
        const API_BATCH_URL = `/api/v1/institute/batches/${BATCH_ID}`;
        const API_STUDENTS_URL = `/api/v1/institute/students?batch_id=${BATCH_ID}`;
        let allStudents = [];
        let selectedStudentIds = new Set();
        let enrollableStudents = [];
        let BATCH_FEES = 0;
        let studentFees = new Map();

        document.addEventListener('DOMContentLoaded', () => {
            fetchBatchData();
            fetchStudents();
        });

        async function fetchBatchData() {
            try {
                const response = await fetch(API_BATCH_URL, { headers: { 'Accept': 'application/json' } });
                const result = await response.json();
                if (result.status === 'success') {
                    const batch = result.data;
                    document.getElementById('batch-name-heading').innerText = batch.name;
                    document.getElementById('target-batch-name').innerText = batch.name;
                    document.getElementById('batch-id-badge').innerText = `REF: BATCH-${batch.id}`;
                    document.getElementById('batch-id-badge').classList.remove('animate-pulse');
                    document.getElementById('batch-schedule-text').innerText = `${batch.start_time || '--:--'} — ${batch.end_time || '--:--'}`;

                    // Populate Stats
                    BATCH_FEES = batch.fees || 0;
                    document.getElementById('stat-students-count').innerText = batch.students_count || '0';
                    document.getElementById('stat-monthly-fee').innerText = `₹${BATCH_FEES}`;
                    document.getElementById('stat-total-paid').innerText = `₹${batch.total_paid || '0'}`;

                    // Populate Description
                    if (batch.description) {
                        document.getElementById('batch-description-text').innerText = batch.description;
                        document.getElementById('batch-description-text').classList.remove('text-slate-400', 'italic');
                        document.getElementById('batch-description-text').classList.add('text-slate-600');
                    }
                }
            } catch (error) {
                showToast('Failed to load batch info', 'error');
            }
        }

        async function fetchStudents() {
            document.getElementById('loading-indicator').classList.remove('hidden');
            try {
                const response = await fetch(API_STUDENTS_URL, { headers: { 'Accept': 'application/json' } });
                const result = await response.json();
                if (result.status === 'success') {
                    allStudents = result.data.items;
                    renderStudents(allStudents);
                }
            } catch (error) {
                showToast('Failed to load students', 'error');
            } finally {
                document.getElementById('loading-indicator').classList.add('hidden');
            }
        }

        // --- Enrollment Logic ---

        function openEnrollModal() {
            document.getElementById('enroll-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            selectedStudentIds.clear();
            updateSelectedCount();
            searchEnrollableStudents();
        }

        function closeEnrollModal() {
            document.getElementById('enroll-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        let enrollSearchTimeout = null;
        async function searchEnrollableStudents() {
            const query = document.getElementById('enroll-search').value;
            const listContainer = document.getElementById('enrollable-list');

            listContainer.innerHTML = `<div class="py-10 text-center animate-pulse"><div class="h-6 w-6 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin mx-auto"></div></div>`;

            try {
                let url = `/api/v1/institute/students?status=1`;
                if (query) url += `&search=${encodeURIComponent(query)}`;

                const response = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const result = await response.json();

                if (result.status === 'success') {
                    // Filter out students who already have a batch assigned
                    enrollableStudents = result.data.items.filter(student => !student.batch_id);

                    // Initialize studentFees Map with default batch fee
                    enrollableStudents.forEach(s => {
                        if (!studentFees.has(s.id)) studentFees.set(s.id, BATCH_FEES);
                    });

                    renderEnrollableList(enrollableStudents);
                }
            } catch (error) {
                listContainer.innerHTML = `<p class="py-10 text-center text-rose-500 font-bold text-xs">Failed to fetch available scholars</p>`;
            }
        }

        function renderEnrollableList(students) {
            const container = document.getElementById('enrollable-list');
            const query = document.getElementById('enroll-search').value.trim();

            if (students.length === 0) {
                container.innerHTML = `<p class="py-10 text-center text-slate-400 font-medium italic text-[12px]">No unassigned scholars found.</p>`;
                return;
            }

            let html = students.map(student => {
                const isSelected = selectedStudentIds.has(student.id);
                return `
                    <div onclick="toggleStudentSelection(${student.id})" 
                        class="flex items-center p-3 rounded-xl cursor-pointer transition-all duration-200 group ${isSelected ? 'bg-blue-600 text-white shadow-lg shadow-blue-200 ring-2 ring-blue-100' : 'hover:bg-slate-50 border border-transparent'}">
                        <div class="h-9 w-9 rounded-lg ${isSelected ? 'bg-white/20 border-white/30' : 'bg-white border-slate-200'} border flex items-center justify-center font-bold ${isSelected ? 'text-white' : 'text-slate-400'} mr-4 group-hover:scale-105 transition-transform shrink-0 text-sm">
                            ${student.name.substring(0, 1).toUpperCase()}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-0.5">
                                <p class="text-[13px] font-bold truncate ${isSelected ? 'text-white' : 'text-slate-700'}">${student.name}</p>
                                <div class="flex items-center gap-1.5 ml-2">
                                    <span class="text-[10px] font-bold ${isSelected ? 'text-blue-100' : 'text-slate-400'}">₹</span>
                                    <input type="number" 
                                        value="${studentFees.get(student.id) || BATCH_FEES}" 
                                        onchange="studentFees.set(${student.id}, this.value)"
                                        onclick="event.stopPropagation()"
                                        class="w-16 px-2 py-1 ${isSelected ? 'bg-blue-700/50 text-white border-blue-400/30' : 'bg-slate-50 text-blue-600 border-blue-100'} border rounded-lg text-[11px] font-black outline-none focus:ring-2 focus:ring-white/20 transition-all">
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <p class="text-[9px] font-bold uppercase tracking-widest ${isSelected ? 'text-blue-100/80' : 'text-slate-400'}">${student.phone || 'NO PHONE'}</p>
                                <span class="h-1 w-1 rounded-full ${isSelected ? 'bg-blue-100/40' : 'bg-slate-200'}"></span>
                                <p class="text-[9px] font-bold uppercase tracking-widest ${isSelected ? 'text-blue-100/80' : 'text-slate-400'}">READY</p>
                            </div>
                        </div>
                        <div class="ml-4 h-6 w-6 rounded-full flex items-center justify-center transition-all shrink-0 ${isSelected ? 'bg-white text-blue-600 scale-110 shadow-sm' : 'border-2 border-slate-200'}">
                            <svg class="w-3.5 h-3.5 ${isSelected ? 'block' : 'hidden'}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                `;
            }).join('');
            container.innerHTML = html;
        }

        function toggleSubmitLoading(show) {
            // Unused but keeping helper if needed
        }

        function toggleStudentSelection(id) {
            if (selectedStudentIds.has(id)) {
                selectedStudentIds.delete(id);
            } else {
                selectedStudentIds.add(id);
            }
            updateSelectedCount();
            renderEnrollableList(enrollableStudents);
        }

        function updateSelectedCount() {
            const count = selectedStudentIds.size;
            document.getElementById('selected-count').innerText = count;
            document.getElementById('confirm-enroll-btn').disabled = count === 0;

            // Hide overview if students are selected
            const overview = document.getElementById('enrollment-overview-section');
            if (count > 0) {
                overview.classList.add('hidden');
            } else {
                overview.classList.remove('hidden');
            }

            renderSelectedChips();
        }

        function renderSelectedChips() {
            const container = document.getElementById('selected-chips');
            if (selectedStudentIds.size === 0) {
                container.innerHTML = '<div class="flex items-center text-slate-400 gap-2"><svg class="w-3.5 h-3.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 01-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg><span class="text-[11px] font-bold uppercase tracking-wider opacity-60">No scholars selected yet</span></div>';
                return;
            }

            let html = '';
            selectedStudentIds.forEach(id => {
                const student = enrollableStudents.find(s => s.id == id) || allStudents.find(s => s.id == id);
                if (student) {
                    html += `
                            <div class="flex items-center bg-white border border-blue-100 text-slate-700 pl-3 pr-1 py-1.5 rounded-xl text-[11px] font-bold shadow-sm hover:shadow-md hover:border-blue-200 transition-all animate-in zoom-in duration-200 group/chip">
                                <div class="h-6 w-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mr-2.5 text-[10px] font-black group-hover/chip:scale-110 transition-transform">
                                    ${student.name.substring(0, 1).toUpperCase()}
                                </div>
                                <span class="max-w-[80px] truncate">${student.name}</span>
                                <div class="ml-2 flex items-center gap-1 bg-slate-50 px-2 py-0.5 rounded-lg border border-slate-100 group-hover/chip:border-blue-200 group-hover/chip:bg-blue-50 transition-colors mr-1">
                                    <span class="text-[9px] text-slate-400 font-bold">₹</span>
                                    <input type="number" 
                                        value="${studentFees.get(id) || BATCH_FEES}" 
                                        onchange="studentFees.set(${id}, this.value)"
                                        class="w-12 bg-transparent text-[10px] text-blue-600 font-black outline-none border-none p-0 text-center [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                </div>
                                <button onclick="toggleStudentSelection(${id})" class="h-6 w-6 rounded-lg text-slate-300 hover:text-rose-500 hover:bg-rose-50 flex items-center justify-center transition-all ml-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        `;
                }
            });
            container.innerHTML = html;
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            const dropdown = document.getElementById('enrollable-dropdown');
            const searchInput = document.getElementById('enroll-search');
            if (!dropdown.contains(e.target) && e.target !== searchInput) {
                dropdown.classList.add('hidden');
            }
        });

        async function confirmEnrollment() {
            const btn = document.getElementById('confirm-enroll-btn');
            btn.disabled = true;
            btn.innerHTML = `<div class="flex items-center justify-center"><span class="h-4 w-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span></div>`;

            try {
                const promises = Array.from(selectedStudentIds).map(id =>
                    fetch(`/api/v1/institute/students/${id}`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify({
                            _method: 'PUT',
                            batch_id: BATCH_ID,
                            monthly_fee: studentFees.get(id) || BATCH_FEES
                        })
                    })
                );

                await Promise.all(promises);

                showToast(`Successfully enrolled ${selectedStudentIds.size} scholars`, 'success');
                closeEnrollModal();
                fetchBatchData(); // Update count
                fetchStudents();  // Update list
            } catch (error) {
                showToast('Enrollment partially failed', 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Enroll Selected';
            }
        }

        // --- View Logic ---

        function renderStudents(students) {
            const container = document.getElementById('student-grid');
            if (students.length === 0) {
                container.innerHTML = `
                            <div class="col-span-full py-20 text-center flex flex-col items-center">
                                <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">No scholars found in this batch</p>
                            </div>`;
                return;
            }

            container.innerHTML = students.map(student => {
                return `
                            <div class="group relative bg-white rounded-2xl border border-slate-100 p-2.5 hover:border-blue-200 transition-all duration-300 animate-in zoom-in-95 flex flex-col cursor-pointer"
                                 onclick="window.location.href='/institute/students/${student.id}'">

                                <!-- Profile Image -->
                                <div class="h-14 w-14 rounded-xl bg-slate-100 p-0.5 border border-slate-200 mb-2 overflow-hidden mx-auto group-hover:scale-105 transition-transform">
                                    <img src="${student.profile_image_url || '/assets/images/default-avatar.png'}" 
                                         class="w-full h-full object-cover rounded-lg"
                                         onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(student.name)}&background=f1f5f9&color=64748b&bold=true'">
                                </div>

                                <!-- Name & ID -->
                                <h4 class="text-[13px] font-black text-slate-800 text-center truncate px-1 leading-tight">${student.name}</h4>
                                <span class="text-[8px] font-black text-slate-400 text-center uppercase tracking-widest mt-0.5">ID: ST-${String(student.id).padStart(3, '0')}</span>

                                <!-- Remove Button -->
                                <button onclick="event.stopPropagation(); removeFromBatch(${student.id}, '${student.name.replace(/'/g, "\\'")}')" 
                                        class="absolute -top-2 -right-2 h-7 w-7 bg-white border border-rose-100 rounded-full flex items-center justify-center text-rose-400 hover:bg-rose-500 hover:text-white hover:scale-110 shadow-sm transition-all opacity-0 group-hover:opacity-100 z-10">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>

                                <!-- Details Grid -->
                                <div class="grid grid-cols-2 gap-1.5 text-center my-3">
                                    <div class="bg-slate-50/50 border border-slate-100 rounded-lg py-1.5 group-hover:bg-blue-50/50 group-hover:border-blue-100 transition-colors">
                                        <span class="text-slate-700 font-black block text-[10px]">${student.standard || 'N/A'}</span>
                                        <span class="text-slate-400 text-[7px] font-bold uppercase tracking-tighter">Grade</span>
                                    </div>
                                    <div class="bg-slate-50/50 border border-slate-100 rounded-lg py-1.5 group-hover:bg-blue-50/50 group-hover:border-blue-100 transition-colors">
                                        <span class="text-blue-600 font-black block text-[10px]">₹${student.fees || student.monthly_fee || '0'}</span>
                                        <span class="text-slate-400 text-[7px] font-bold uppercase tracking-tighter">Fee</span>
                                    </div>
                                </div>

                                <!-- Phone Info -->
                                <div class="mt-auto pt-2 border-t border-slate-50 flex items-center justify-center gap-1.5">
                                    <div class="h-4 w-4 rounded-md bg-slate-50 flex items-center justify-center text-slate-400">
                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </div>
                                    <span class="text-[9px] font-bold text-slate-500">${student.phone || 'NO PHONE'}</span>
                                </div>
                            </div>
                        `;
            }).join('');
        }

        function filterStudents() {
            const query = document.getElementById('student-search').value.toLowerCase();
            const filtered = allStudents.filter(s => s.name.toLowerCase().includes(query) || (s.phone && s.phone.includes(query)));
            renderStudents(filtered);
        }

        let pendingStudentToRemove = null;

        function removeFromBatch(studentId, name) {
            pendingStudentToRemove = { id: studentId, name: name };
            document.getElementById('unenroll-student-name').innerText = name;
            document.getElementById('unenroll-modal').classList.remove('hidden');

            // Set up the confirm button action
            document.getElementById('confirm-unenroll-btn').onclick = executeUnenroll;
        }

        function closeUnenrollModal() {
            document.getElementById('unenroll-modal').classList.add('hidden');
            pendingStudentToRemove = null;
        }

        async function executeUnenroll() {
            if (!pendingStudentToRemove) return;

            const { id, name } = pendingStudentToRemove;
            const btn = document.getElementById('confirm-unenroll-btn');
            const originalText = btn.innerText;

            btn.disabled = true;
            btn.innerHTML = `<div class="flex items-center justify-center"><span class="h-4 w-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span></div>`;

            try {
                const response = await fetch(`/api/v1/institute/students/${id}`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({
                        _method: 'PUT',
                        batch_id: null
                    })
                });

                const result = await response.json();
                if (result.status === 'success') {
                    showToast(`${name} removed from batch`, 'success');
                    closeUnenrollModal();
                    fetchBatchData(); // Update stats
                    fetchStudents();  // Update list
                }
            } catch (error) {
                showToast('Failed to remove student', 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = originalText;
            }
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const color = type === 'success' ? 'emerald' : 'rose';
            toast.className = `bg-${color}-50 border border-${color}-200 text-${color}-600 px-6 py-4 rounded-2xl shadow-xl flex items-center animate-in slide-in-from-right-10 duration-300`;
            toast.innerHTML = `<span class="text-sm font-bold">${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => { toast.remove(); }, 3000);
        }
    </script>
@endsection