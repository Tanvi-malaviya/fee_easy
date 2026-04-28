@extends('layouts.institute')

@section('content')
    <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

    <div class="max-w-7xl mx-auto ">
        <!-- Breadcrumb & Header -->
        <div class="mb-3">
            <nav class="flex items-center pt-7 gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">
                <a href="{{ route('institute.batches.index') }}" class="hover:text-blue-600 transition-colors">Batches</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('institute.batches.show', $id) }}" id="breadcrumb-batch-name"
                    class="hover:text-blue-600 transition-colors text-slate-600">Loading...</a>
            </nav>

            <h1 id="batch-name-heading" class="text-4xl font-bold text-slate-900 tracking-tight mb-2">Loading...</h1>
            <p id="batch-sub-info" class="text-sm font-semibold text-slate-400 flex items-center gap-2">
                Academic Year 2023-2024 • Section A-12 • <span id="instructor-name">Dr. Julian Vance</span>
            </p>
        </div>

        <!-- Top Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 mb-3">
            <!-- Total Enrolled -->
            <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm flex items-center gap-3 group">
                <div
                    class="h-10 w-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-500 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total Enrolled</p>
                    <h3 id="stat-total-students" class="text-lg font-bold text-slate-900 leading-none">0</h3>
                </div>
            </div>

            <!-- Attendance Avg -->
            <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm flex items-center gap-3 group">
                <div
                    class="h-10 w-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-500 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Attendance Avg</p>
                    <h3 class="text-lg font-bold text-slate-900 leading-none">94.2%</h3>
                </div>
            </div>

            <!-- Pending Tasks -->
            <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm flex items-center gap-3 group">
                <div
                    class="h-10 w-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M9 5H7a2 2 0 00-2-2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Pending Tasks</p>
                    <h3 class="text-lg font-bold text-slate-900 leading-none">08</h3>
                </div>
            </div>

            <!-- Class Performance -->
            <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm flex items-center gap-3 group">
                <div
                    class="h-10 w-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-500 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Performance</p>
                    <h3 class="text-lg font-bold text-orange-500 leading-none">Distinction</h3>
                </div>
            </div>
        </div>

        <!-- Control Bar -->
        <div
            class="bg-white p-3 rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row items-center gap-3 mb-3">
            <div class="relative flex-1 group">
                <input type="text" id="student-search" onkeyup="filterStudents()"
                    placeholder="Search students by name, email or ID..."
                    class="w-full pl-12 pr-6 py-3.5 bg-slate-50/50 border border-slate-100 rounded-xl text-sm font-semibold outline-none focus:border-blue-600 focus:bg-white transition-all">
                <svg class="w-5 h-5 text-slate-300 absolute left-4 top-1/2 -translate-y-1/2 group-focus-within:text-blue-600 transition-colors"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <div class="flex items-center gap-2">
                <button
                    class="px-5 py-3 bg-white border border-slate-100 rounded-lg text-sm font-bold text-slate-600 flex items-center gap-2 hover:bg-slate-50 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Export
                </button>
                <button onclick="openEnrollModal()"
                    class="px-6 py-3.5 bg-orange-500 text-white rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-orange-600 hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-orange-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Assign Student
                </button>
            </div>
        </div>

        <!-- Student Grid -->
        <div id="student-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Loading State -->
            <div class="col-span-full py-32 text-center">
                <div class="inline-block h-10 w-10 border-4 border-slate-100 border-t-blue-600 rounded-full animate-spin">
                </div>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs mt-6">Fetching Student Records...</p>
            </div>
        </div>
    </div>

    <!-- ASSIGN CUSTOM FEES FULL-PAGE OVERLAY -->
    <div id="enroll-modal" class="fixed inset-0 z-[150] bg-[#f9fafb] hidden overflow-hidden flex flex-col font-sans">
        <!-- HEADER -->
        <div class="bg-[#f9fafb] pt-4 px-4 pb-6 flex items-start justify-between shrink-0 relative z-20">
            <div class="max-w-2xl">
                <h2 class="text-[32px] leading-tight font-bold text-slate-900 tracking-tight mb-1">Assign Custom Fees</h2>
                <p class="text-sm font-medium text-slate-500 leading-relaxed">Customize tuition and resource fees for
                    specific students within the <span id="target-batch-name-display"
                        class="text-slate-700 font-semibold">Advanced Mathematics Batch 2024</span>.</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="closeEnrollModal()"
                    class="px-6 py-2.5 bg-white border border-slate-200 rounded-lg text-[13px] font-bold text-slate-600 hover:bg-slate-50 transition-all shadow-sm">Discard
                    Changes</button>
                <button id="confirm-enroll-btn" onclick="confirmEnrollment()" disabled
                    class="px-6 py-2.5 bg-[#ff6600] text-white rounded-lg text-[13px] font-bold shadow-sm hover:bg-[#e65c00] transition-all disabled:opacity-50 disabled:grayscale">Apply
                    Fee Structure</button>
            </div>
        </div>

        <div class="flex-1 flex gap-4 px-4 pb-4 overflow-hidden">
            <!-- LEFT SIDEBAR: ADD STUDENTS & BATCH INFO -->
            <div class="w-[340px] flex flex-col gap-6 shrink-0 h-full">
                <!-- Add Students Card -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm flex flex-col flex-1 overflow-hidden">
                    <div class="p-5 border-b border-slate-100">
                        <div class="flex items-center gap-2.5 mb-4">
                            <svg class="w-5 h-5 text-[#ff6600]" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                            <h3 class="text-[15px] font-bold text-slate-800">Add Students</h3>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" id="enroll-search" onkeyup="searchEnrollableStudents()"
                                placeholder="Search by name or ID..."
                                class="w-full pl-9 pr-3 py-2.5 bg-slate-50/50 border border-slate-200 rounded-lg text-[13px] font-medium outline-none focus:ring-2 focus:ring-orange-500/10 focus:border-orange-500 focus:bg-white transition-all placeholder:text-slate-400">
                        </div>
                    </div>
                    <div id="enrollable-list" class="flex-1 overflow-y-auto p-3 space-y-2 custom-scrollbar">
                        <!-- Student list populated via JS -->
                        <div class="py-12 text-center text-slate-400 font-medium text-[13px]">Loading scholars...</div>
                    </div>
                </div>

                <!-- Current Batch Card -->
                <div class="bg-[#ff6600] rounded-xl shadow-sm p-6 text-white relative overflow-hidden shrink-0">
                    <svg class="absolute -bottom-8 -right-8 w-40 h-40 text-white/10" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z" />
                    </svg>
                    <p class="text-[11px] font-semibold text-white/80 uppercase tracking-wider mb-1 relative z-10">Current
                        Batch</p>
                    <h3 id="current-batch-name-display" class="text-lg font-bold mb-6 relative z-10">Advanced Mathematics
                    </h3>
                    <div class="flex items-end justify-between relative z-10">
                        <div>
                            <h2 id="current-batch-students-display" class="text-3xl font-bold leading-none mb-1">24</h2>
                            <p class="text-[10px] font-semibold text-white/80 uppercase tracking-wider">Total Students</p>
                        </div>
                        <div class="text-right">
                            <h2 class="text-[22px] font-bold leading-none mb-1 text-white">₹<span
                                    id="current-batch-revenue-display">84,000</span></h2>
                            <p class="text-[10px] font-semibold text-white/80 uppercase tracking-wider">Projected Revenue
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MAIN AREA: SELECTED STUDENTS -->
            <div
                class="flex-1 bg-white rounded-xl shadow-sm border border-slate-200 flex flex-col overflow-hidden relative">
                <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between bg-white z-10">
                    <h3 class="text-[15px] font-bold text-slate-800">
                        Selected Students (<span id="selected-count-badge">0</span>)
                    </h3>
                    <div class="flex items-center gap-1.5 text-slate-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-[12px] font-medium">All values in INR (₹)</span>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto px-8 py-6 custom-scrollbar bg-white relative">
                    <!-- Empty State -->
                    <div id="enrollment-overview-section"
                        class="h-full flex flex-col items-center justify-center text-center opacity-60">
                        <div
                            class="h-16 w-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 mb-4 border border-slate-100 border-dashed">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                        </div>
                        <p class="text-slate-400 font-medium text-[13px]">Add more students from the side panel</p>
                    </div>

                    <!-- Dynamic List -->
                    <div id="selected-students-main-list" class="space-y-4"></div>
                </div>

                <!-- BOTTOM FOOTER INSIDE MAIN CONTAINER -->
                <div class="border-t border-slate-100 bg-[#fbfcfd] px-8 py-5 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-12">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Selected
                            </p>
                            <h4 id="selected-count-footer" class="text-xl font-bold text-[#ff6600]">00</h4>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Net Amount</p>
                            <h4 class="text-xl font-bold text-[#ff6600]">₹<span id="projected-collection">0</span></h4>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <button
                            class="text-[#ff6600] font-bold text-[13px] hover:text-[#e65c00] transition-colors flex items-center gap-2 group">
                            Next Step: Payment Schedule
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </button>
                        <button
                            class="px-5 py-2.5 bg-[#ff6600] text-white rounded-lg text-[13px] font-bold flex items-center gap-2 hover:bg-[#e65c00] transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Save Draft
                        </button>
                    </div>
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

        document.addEventListener('DOMContentLoaded', async () => {
            await fetchBatchData();
            await fetchStudents();
        });

        async function fetchBatchData() {
            try {
                const response = await fetch(API_BATCH_URL, { headers: { 'Accept': 'application/json' } });
                const result = await response.json();
                if (result.status === 'success') {
                    const batch = result.data;
                    BATCH_FEES = batch.fees || 0;
                    document.getElementById('batch-name-heading').innerText = batch.name;
                    document.getElementById('breadcrumb-batch-name').innerText = batch.name;
                    document.getElementById('target-batch-name-display').innerText = batch.name;
                    document.getElementById('stat-total-students').innerText = `${batch.students_count || 0} Students`;
                    if (batch.subject) {
                        document.getElementById('instructor-name').innerText = `Instructor: ${batch.subject}`;
                    }

                    const curBatchName = document.getElementById('current-batch-name-display');
                    if (curBatchName) curBatchName.innerText = batch.name;

                    const curBatchStudents = document.getElementById('current-batch-students-display');
                    if (curBatchStudents) curBatchStudents.innerText = batch.students_count || 0;

                    const curBatchRev = document.getElementById('current-batch-revenue-display');
                    if (curBatchRev) curBatchRev.innerText = ((batch.students_count || 0) * BATCH_FEES).toLocaleString();
                }
            } catch (error) {
                showToast('Failed to load batch info', 'error');
            }
        }

        async function fetchStudents() {
            const container = document.getElementById('student-grid');
            try {
                const response = await fetch(API_STUDENTS_URL, { headers: { 'Accept': 'application/json' } });
                const result = await response.json();
                if (result.status === 'success' && result.data && Array.isArray(result.data.items)) {
                    allStudents = result.data.items;
                    renderStudents(allStudents);
                }
            } catch (error) {
                showToast('Failed to load students', 'error');
                container.innerHTML = `
                        <div class="col-span-full py-20 text-center flex flex-col items-center">
                            <div class="h-20 w-20 bg-rose-50 rounded-full flex items-center justify-center text-rose-500 mb-6">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <p class="text-rose-500 font-bold uppercase tracking-[0.2em] text-[10px]">Failed to Load Student Records</p>
                            <button onclick="fetchStudents()" class="mt-4 text-blue-600 font-bold text-xs hover:underline uppercase tracking-widest">Try Again</button>
                        </div>`;
            }
        }

        function openEnrollModal() {
            document.getElementById('enroll-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            selectedStudentIds.clear();
            studentFees.clear();
            updateSelectedCount();
            searchEnrollableStudents();
        }

        function closeEnrollModal() {
            document.getElementById('enroll-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

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
                    enrollableStudents = result.data.items.filter(student => !student.batch_id);
                    renderEnrollableList(enrollableStudents);
                }
            } catch (error) {
                listContainer.innerHTML = `<p class="py-10 text-center text-rose-500 font-bold text-xs">Failed to fetch available scholars</p>`;
            }
        }

        function renderEnrollableList(students) {
            const container = document.getElementById('enrollable-list');
            if (students.length === 0) {
                container.innerHTML = `<p class="py-10 text-center text-slate-400 font-medium italic text-[12px]">No unassigned scholars found.</p>`;
                return;
            }
            container.innerHTML = students.map(student => {
                const isSelected = selectedStudentIds.has(parseInt(student.id, 10));
                return `
                        <div onclick="toggleStudentSelection(${student.id})" 
                            class="flex items-center p-2 rounded-xl cursor-pointer transition-all duration-300 group ${isSelected ? 'bg-orange-50 border border-orange-200' : 'hover:bg-slate-50 border border-transparent'}">
                            <div class="h-9 w-9 rounded-lg ${isSelected ? 'bg-[#ff6600] text-white' : 'bg-slate-100 text-slate-500'} flex items-center justify-center font-bold text-sm mr-3 transition-colors shrink-0">
                                ${student.name.substring(0, 1).toUpperCase()}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-bold truncate ${isSelected ? 'text-orange-900' : 'text-slate-800'}">${student.name}</p>
                                <p class="text-[10px] font-medium text-slate-400 mt-0.5">ID: #TUA-${String(student.id).padStart(4, '0')}</p>
                            </div>
                            <div class="ml-3 h-5 w-5 rounded-full flex items-center justify-center transition-all shrink-0 ${isSelected ? 'bg-[#ff6600] text-white' : 'border-2 border-slate-200'}">
                                <svg class="w-3 h-3 ${isSelected ? 'block' : 'hidden'}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </div>
                    `;
            }).join('');
        }

        function toggleStudentSelection(id) {
            id = parseInt(id, 10);
            if (selectedStudentIds.has(id)) {
                selectedStudentIds.delete(id);
                studentFees.delete(id);
            } else {
                selectedStudentIds.add(id);
                studentFees.set(id, { tuition: BATCH_FEES, other: 0 });
            }
            updateSelectedCount();
            renderEnrollableList(enrollableStudents);
        }

        function updateSelectedCount() {
            const count = selectedStudentIds.size;
            document.getElementById('selected-count-badge').innerText = count;
            document.getElementById('selected-count-footer').innerText = String(count).padStart(2, '0');
            document.getElementById('confirm-enroll-btn').disabled = count === 0;

            const emptyState = document.getElementById('enrollment-overview-section');
            if (count > 0) {
                emptyState.classList.add('hidden');
            } else {
                emptyState.classList.remove('hidden');
            }
            calculateProjectedCollection();
            renderSelectedStudents();
        }

        function calculateProjectedCollection() {
            let total = 0;
            studentFees.forEach(fee => {
                total += (parseFloat(fee.tuition) || 0) + (parseFloat(fee.other) || 0);
            });
            document.getElementById('projected-collection').innerText = total.toLocaleString();
        }

        function renderSelectedStudents() {
            const container = document.getElementById('selected-students-main-list');
            if (selectedStudentIds.size === 0) {
                container.innerHTML = '';
                return;
            }
            let html = '';
            selectedStudentIds.forEach(id => {
                const student = enrollableStudents.find(s => s.id == id);
                if (student) {
                    const fees = studentFees.get(id);
                    html += `
                            <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex items-center gap-6 group border-l-4 border-l-[#ff6600] animate-in slide-in-from-right-4 duration-300">
                                <div class="flex items-center gap-4 flex-1">
                                    <div class="h-12 w-12 rounded-lg bg-slate-100 flex items-center justify-center font-bold text-slate-500 text-lg overflow-hidden shrink-0">
                                        <img src="${student.profile_image_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(student.name) + '&background=F1F5F9&color=64748B&bold=true'}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <h4 class="text-[15px] font-bold text-slate-900 leading-tight mb-0.5">${student.name}</h4>
                                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">${student.email ? student.email.split('@')[0].substring(0, 15) : 'STUDENT'}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-6">
                                    <div>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Tuition Fee</p>
                                        <div class="flex items-center bg-white border border-slate-200 rounded-lg px-3 py-2 w-32 focus-within:border-[#ff6600] focus-within:ring-2 focus-within:ring-orange-500/10 transition-all">
                                            <span class="text-[13px] font-bold text-slate-400 mr-2">$</span>
                                            <input type="number" value="${fees.tuition}" onchange="updateStudentFee(${id}, 'tuition', this.value)"
                                                class="w-full bg-transparent text-[14px] font-bold text-slate-900 outline-none p-0">
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Other Fees</p>
                                        <div class="flex items-center bg-white border border-slate-200 rounded-lg px-3 py-2 w-32 focus-within:border-[#ff6600] focus-within:ring-2 focus-within:ring-orange-500/10 transition-all">
                                            <span class="text-[13px] font-bold text-slate-400 mr-2">$</span>
                                            <input type="number" value="${fees.other}" onchange="updateStudentFee(${id}, 'other', this.value)"
                                                class="w-full bg-transparent text-[14px] font-bold text-slate-900 outline-none p-0">
                                        </div>
                                    </div>
                                    <button onclick="toggleStudentSelection(${id})" class="text-rose-500 hover:text-rose-600 transition-colors p-2 mt-4">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                    </button>
                                </div>
                            </div>`;
                }
            });
            container.innerHTML = html;
        }

        function updateStudentFee(id, type, value) {
            const fees = studentFees.get(id);
            fees[type] = parseFloat(value) || 0;
            studentFees.set(id, fees);
            calculateProjectedCollection();
        }

        async function confirmEnrollment() {
            const btn = document.getElementById('confirm-enroll-btn');
            btn.disabled = true;
            btn.innerHTML = `<span class="h-4 w-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>`;

            try {
                const promises = Array.from(selectedStudentIds).map(id => {
                    const fees = studentFees.get(id);
                    const totalFee = (parseFloat(fees.tuition) || 0) + (parseFloat(fees.other) || 0);
                    return fetch(`/api/v1/institute/students/${id}`, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                        body: JSON.stringify({ _method: 'PUT', batch_id: BATCH_ID, monthly_fee: totalFee })
                    });
                });
                await Promise.all(promises);
                showToast(`Successfully enrolled scholars`, 'success');
                closeEnrollModal();
                fetchBatchData(); fetchStudents();
            } catch (error) {
                showToast('Enrollment partially failed', 'error');
            } finally {
                btn.disabled = false; btn.innerText = 'Apply Fee Structure';
            }
        }

        function renderStudents(students) {
            const container = document.getElementById('student-grid');
            if (students.length === 0) {
                container.innerHTML = `<div class="col-span-full py-20 text-center flex flex-col items-center">
                        <div class="h-20 w-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mb-6">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-[10px]">No Scholars Found</p>
                    </div>`;
                return;
            }
            container.innerHTML = students.map(student => {
                const performance = 60 + (student.id % 40); // Deterministic performance based on ID

                // Real fee status based on backend data
                let feeStatusText = 'Pending';
                let feeStatusColor = 'rose';

                if (!student.monthly_fee || student.monthly_fee == 0) {
                    feeStatusText = 'No Fee';
                    feeStatusColor = 'slate';
                } else if (student.total_due <= 0) {
                    feeStatusText = 'Paid';
                    feeStatusColor = 'emerald';
                }

                return `
                                <div class="group bg-white rounded-xl border border-slate-100 hover:shadow-xl hover:shadow-blue-900/5 transition-all duration-300 flex flex-col cursor-pointer relative"
                                     onclick="if(!event.target.closest('.action-btn')) window.location.href='/institute/students/${student.id}'">

                                    <!-- Top Content Section with Padding -->
                                    <div class="pt-5 pl-5 pr-5 flex-1 flex flex-col">
                                        <!-- ID Badge -->
                                        <div class="absolute top-4 right-4">
                                            <span class="px-2 py-0.5 bg-slate-50 text-slate-400 text-[9px] font-black rounded-md uppercase tracking-tight">
                                                ID: #ST-${String(student.id).padStart(4, '0')}
                                            </span>
                                        </div>

                                        <!-- Profile Section -->
                                        <div class="flex flex-col items-left mb-4">
                                            <div class="h-16 w-16 rounded-full border-2 border-slate-50 overflow-hidden mb-3 shadow-inner">
                                                <img src="${student.profile_image_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(student.name) + '&background=F1F5F9&color=64748B&bold=true'}" class="w-full h-full object-cover">
                                            </div>
                                            <h4 class="text-base font-black text-slate-800 text-left tracking-tight leading-tight">${student.name}</h4>
                                            <p class="text-[10px] font-bold text-slate-400 mt-0.5">${student.email || 'no-email@academy.edu'}</p>
                                        </div>

                                        <!-- Metrics Section -->
                                        <div class="space-y-4 mb-2 flex-1">
                                            <div>
                                                <div class="flex items-left justify-between mb-1.5">
                                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Performance</span>
                                                    <span class="text-[9px] font-black text-emerald-500 uppercase tracking-wider">${performance}%</span>
                                                </div>
                                                <div class="h-1 w-full bg-slate-50 rounded-full overflow-hidden">
                                                    <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" style="width: ${performance}%"></div>
                                                </div>
                                            </div>

                                            <div class="flex items-center justify-between border-slate-50">
                                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Fee Status</span>
                                                <span class="px-2 py-0.5 bg-${feeStatusColor}-50 text-${feeStatusColor}-600 text-[8px] font-black rounded-md uppercase tracking-tight">
                                                    ${feeStatusText}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Footer Actions -->
                                    <div class="flex items-center justify-between p-3 bg-slate-50/80 rounded-b-xl border-t border-slate-100">
                                        <a href="/institute/students/${student.id}" class="action-btn flex items-center text-[#006b74] font-bold text-[12px] hover:opacity-70 transition-all">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            View
                                        </a>
                                        <div class="flex items-center gap-3">
                                            <a href="/institute/students/${student.id}/edit" class="action-btn text-slate-400 hover:text-blue-500 transition-all" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            </a>
                                            <button onclick="event.stopPropagation(); removeFromBatch(${student.id}, '${student.name.replace(/'/g, "\\'")}')" class="action-btn text-slate-400 hover:text-rose-500 transition-all" title="Remove from batch">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>`;
            }).join('');
        }

        function filterStudents() {
            const query = document.getElementById('student-search').value.toLowerCase();
            const filtered = allStudents.filter(s => s.name.toLowerCase().includes(query) || (s.email && s.email.toLowerCase().includes(query)));
            renderStudents(filtered);
        }

        let pendingStudentToRemove = null;
        function removeFromBatch(studentId, name) {
            pendingStudentToRemove = { id: studentId, name: name };
            executeUnenroll();
        }

        async function executeUnenroll() {
            if (!pendingStudentToRemove) return;
            const { id, name } = pendingStudentToRemove;
            if (!confirm(`Are you sure you want to remove ${name} from this batch?`)) return;
            try {
                const response = await fetch(`/api/v1/institute/students/${id}`, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify({ _method: 'PUT', batch_id: null })
                });
                if ((await response.json()).status === 'success') {
                    showToast(`${name} removed`, 'success'); fetchBatchData(); fetchStudents();
                }
            } catch (error) { showToast('Failed to remove student', 'error'); } finally { pendingStudentToRemove = null; }
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `flex items-center gap-3 px-6 py-4 rounded-2xl shadow-2xl animate-in slide-in-from-right-10 duration-500 ${type === 'success' ? 'bg-slate-900 text-white' : 'bg-rose-600 text-white'}`;
            toast.innerHTML = `
                    <div class="h-6 w-6 rounded-full flex items-center justify-center ${type === 'success' ? 'bg-blue-500' : 'bg-rose-400'}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="${type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}"/></svg>
                    </div>
                    <p class="text-sm font-bold">${message}</p>`;
            container.appendChild(toast);
            setTimeout(() => { toast.classList.add('animate-out', 'fade-out', 'slide-out-to-right-10'); setTimeout(() => toast.remove(), 500); }, 3000);
        }
    </script>
@endsection