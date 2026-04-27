@extends('layouts.institute')

@section('content')
    <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

    <div class="max-w-[1400px] mx-auto pb-10 px-4 sm:px-6">
        <!-- Breadcrumb & Header -->
        <div class="mb-10">
            <nav class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">
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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
            <!-- Total Enrolled -->
            <div class="bg-white p-6 rounded-2xl border border-slate-50 shadow-sm flex items-center gap-5 group">
                <div
                    class="h-14 w-14 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-500 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total Enrolled</p>
                    <h3 id="stat-total-students" class="text-2xl font-bold text-slate-900">0 Students</h3>
                </div>
            </div>

            <!-- Attendance Avg -->
            <div class="bg-white p-6 rounded-2xl border border-slate-50 shadow-sm flex items-center gap-5 group">
                <div
                    class="h-14 w-14 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-500 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Attendance Avg</p>
                    <h3 class="text-2xl font-bold text-slate-900">94.2%</h3>
                </div>
            </div>

            <!-- Pending Tasks -->
            <div class="bg-white p-6 rounded-2xl border border-slate-50 shadow-sm flex items-center gap-5 group">
                <div
                    class="h-14 w-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M9 5H7a2 2 0 00-2-2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Pending Tasks</p>
                    <h3 class="text-2xl font-bold text-slate-900">08 Exams</h3>
                </div>
            </div>

            <!-- Class Performance -->
            <div class="bg-white p-6 rounded-2xl border border-slate-50 shadow-sm flex items-center gap-5 group">
                <div
                    class="h-14 w-14 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-500 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Class Performance</p>
                    <h3 class="text-2xl font-bold text-orange-500">Distinction</h3>
                </div>
            </div>
        </div>

        <!-- Control Bar -->
        <div
            class="bg-white p-3 rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row items-center gap-3 mb-10">
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
                    class="px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold text-slate-600 flex items-center gap-2 hover:bg-slate-50 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                <button
                    class="px-5 py-3.5 bg-white border border-slate-100 rounded-xl text-sm font-bold text-slate-600 flex items-center gap-2 hover:bg-slate-50 transition-all shadow-sm">
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

    <!-- Enrollment Modal (Reused) -->
    <div id="enroll-modal" class="fixed inset-0 z-[120] flex items-center justify-center hidden">
        <div onclick="closeEnrollModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div
            class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl relative z-10 overflow-hidden animate-in fade-in zoom-in duration-300 max-h-[90vh] flex flex-col">
            <div class="p-6 border-b border-slate-100 flex-shrink-0 bg-white relative z-20">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-slate-800 tracking-tight leading-none">Assign Students</h2>
                        <p class="text-[11px] font-bold text-slate-400 mt-2 uppercase tracking-widest">Assigning to <span
                                id="target-batch-name" class="text-blue-600">this batch</span></p>
                    </div>
                    <button onclick="closeEnrollModal()"
                        class="h-10 w-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-rose-50 hover:text-rose-500 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="relative group">
                    <input type="text" id="enroll-search" onkeyup="searchEnrollableStudents()"
                        placeholder="Search by name or email..."
                        class="w-full pl-12 pr-6 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-semibold outline-none focus:border-blue-600 transition-all">
                    <svg class="w-5 h-5 text-slate-300 absolute left-4 top-1/2 -translate-y-1/2 group-focus-within:text-blue-600 transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <div class="flex-1 overflow-y-auto p-2 min-h-[300px]" id="enrollable-list"></div>
            <div class="p-6 border-t border-slate-100 bg-white flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-2">
                    <span id="selected-count"
                        class="h-8 w-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">0</span>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Selected</span>
                </div>
                <button id="enroll-btn" onclick="enrollStudents()"
                    class="px-8 py-3.5 bg-blue-900 text-white rounded-xl font-bold text-sm shadow-xl shadow-blue-900/20 hover:scale-[1.02] active:scale-95 transition-all">Enroll
                    Selected</button>
            </div>
        </div>
    </div>

    <script>
        const BATCH_ID = {{ $id }};
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const API_BATCH_URL = `/api/v1/institute/batches/${BATCH_ID}`;
        const API_STUDENTS_URL = `/api/v1/institute/students?batch_id=${BATCH_ID}`;

        let allStudents = [];
        let selectedStudentIds = new Set();
        let studentFees = new Map();
        let BATCH_FEES = 0;

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
                    BATCH_FEES = batch.fees || 0;
                    document.getElementById('batch-name-heading').innerText = batch.name;
                    document.getElementById('breadcrumb-batch-name').innerText = batch.name;
                    document.getElementById('target-batch-name').innerText = batch.name;
                    document.getElementById('stat-total-students').innerText = `${batch.students_count || 0} Students`;

                    if (batch.subject) {
                        document.getElementById('instructor-name').innerText = `Instructor: ${batch.subject}`;
                    }
                }
            } catch (error) {
                showToast('Failed to load batch info', 'error');
            }
        }

        async function fetchStudents() {
            try {
                const response = await fetch(API_STUDENTS_URL, { headers: { 'Accept': 'application/json' } });
                const result = await response.json();
                if (result.status === 'success') {
                    allStudents = result.data.items;
                    renderStudents(allStudents);
                }
            } catch (error) {
                showToast('Failed to load students', 'error');
                document.getElementById('student-grid').innerHTML = `
                                <div class="col-span-full py-20 text-center flex flex-col items-center">
                                    <div class="h-20 w-20 bg-rose-50 rounded-full flex items-center justify-center text-rose-500 mb-6">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    </div>
                                    <p class="text-rose-500 font-bold uppercase tracking-[0.2em] text-[10px]">Failed to Load Student Records</p>
                                    <button onclick="fetchStudents()" class="mt-4 text-blue-600 font-bold text-xs hover:underline uppercase tracking-widest">Try Again</button>
                                </div>`;
            }
        }

        function renderStudents(students) {
            const container = document.getElementById('student-grid');
            if (students.length === 0) {
                container.innerHTML = `
                                <div class="col-span-full py-20 text-center flex flex-col items-center">
                                    <div class="h-20 w-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mb-6">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    </div>
                                    <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-[10px]">No Scholars Found</p>
                                </div>`;
                return;
            }

            container.innerHTML = students.map(student => {
                const performance = Math.floor(Math.random() * 40) + 60; // Mock performance
                const feeStatus = Math.random() > 0.3 ? 'Paid' : 'Due';
                const statusColor = feeStatus === 'Paid' ? 'emerald' : 'rose';

                return `
                                <div class="group bg-white rounded-2xl border border-slate-50 p-6 hover:shadow-2xl hover:shadow-slate-200/50 hover:border-blue-100 transition-all duration-500 cursor-pointer overflow-hidden relative"
                                     onclick="window.location.href='/institute/students/${student.id}'">

                                    <!-- ID Badge -->
                                    <div class="absolute top-4 right-4 bg-slate-50 px-2 py-1 rounded-lg">
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">TP-2023-${String(student.id).padStart(4, '0')}</p>
                                    </div>

                                    <!-- Profile -->
                                    <div class="flex flex-col items-center mb-6">
                                        <div class="h-20 w-20 rounded-full bg-slate-100 p-0.5 border border-slate-200 mb-4 overflow-hidden group-hover:scale-110 transition-transform duration-500">
                                            <img src="${student.profile_image_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(student.name) + '&background=F1F5F9&color=64748B&bold=true'}" 
                                                 class="w-full h-full object-cover rounded-full">
                                        </div>
                                        <h4 class="text-lg font-bold text-slate-900 leading-tight group-hover:text-blue-600 transition-colors">${student.name}</h4>
                                        <p class="text-[11px] font-semibold text-slate-400 truncate w-full text-center mt-1">${student.email || 'no-email@academy.edu'}</p>
                                    </div>

                                    <!-- Performance -->
                                    <div class="mb-6">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Performance</span>
                                            <span class="text-[10px] font-bold text-emerald-500">${performance}%</span>
                                        </div>
                                        <div class="h-1.5 w-full bg-slate-50 rounded-full overflow-hidden">
                                            <div class="h-full bg-emerald-500 rounded-full" style="width: ${performance}%"></div>
                                        </div>
                                    </div>

                                    <!-- Fee Status -->
                                    <div class="flex items-center justify-between pb-6 border-b border-slate-50">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Fee Status</span>
                                        <span class="px-3 py-1 bg-${statusColor}-50 text-${statusColor}-600 rounded-full text-[9px] font-bold uppercase tracking-widest">${feeStatus}</span>
                                    </div>

                                    <!-- Actions -->
                                    <div class="pt-4 flex items-center justify-between">
                                        <button class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 hover:text-blue-600 transition-colors group/btn">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            View
                                        </button>
                                        <div class="flex items-center gap-2">
                                            <button onclick="event.stopPropagation(); window.location.href='/institute/students/${student.id}/edit'" class="h-8 w-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-blue-50 hover:text-blue-600 flex items-center justify-center transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            </button>
                                            <button onclick="event.stopPropagation(); removeFromBatch(${student.id}, '${student.name.replace(/'/g, "\\'")}')" class="h-8 w-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-500 flex items-center justify-center transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
            }).join('');
        }

        function filterStudents() {
            const query = document.getElementById('student-search').value.toLowerCase();
            const filtered = allStudents.filter(s => s.name.toLowerCase().includes(query) || (s.email && s.email.toLowerCase().includes(query)));
            renderStudents(filtered);
        }

        // --- Enrollment Modal Logic ---
        function openEnrollModal() {
            document.getElementById('enroll-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            selectedStudentIds.clear();
            updateSelectedCount();
            searchEnrollableStudents();
        }

        function closeEnrollModal() {
            document.getElementById('enroll-modal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        async function searchEnrollableStudents() {
            const query = document.getElementById('enroll-search').value;
            const listContainer = document.getElementById('enrollable-list');

            listContainer.innerHTML = '<div class="flex justify-center p-10"><div class="animate-spin h-8 w-8 border-4 border-blue-100 border-t-blue-600 rounded-full"></div></div>';

            try {
                const response = await fetch(`/api/v1/institute/students?search=${query}&no_batch=true`, {
                    headers: { 'Accept': 'application/json' }
                });
                const result = await response.json();

                if (result.status === 'success') {
                    const students = result.data.items;
                    if (students.length === 0) {
                        listContainer.innerHTML = '<div class="p-10 text-center text-slate-400 font-bold uppercase tracking-widest text-[10px]">No students available</div>';
                        return;
                    }

                    listContainer.innerHTML = students.map(student => {
                        const isSelected = selectedStudentIds.has(student.id);
                        return `
                                        <div onclick="toggleStudentSelection(${student.id})" 
                                            class="flex items-center justify-between p-3 rounded-xl border-2 transition-all cursor-pointer mb-2 ${isSelected ? 'border-blue-600 bg-blue-50' : 'border-slate-50 bg-white hover:border-blue-100'}">
                                            <div class="flex items-center gap-3">
                                                <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold">
                                                    ${student.name.charAt(0).toUpperCase()}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-slate-800">${student.name}</p>
                                                    <p class="text-[10px] font-bold text-slate-400 uppercase">${student.standard || 'No Grade'}</p>
                                                </div>
                                            </div>
                                            <div class="h-6 w-6 rounded-full border-2 flex items-center justify-center ${isSelected ? 'bg-blue-600 border-blue-600 text-white' : 'border-slate-200'}">
                                                ${isSelected ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>' : ''}
                                            </div>
                                        </div>
                                    `;
                    }).join('');
                }
            } catch (error) {
                listContainer.innerHTML = '<div class="p-10 text-center text-rose-500 font-bold">Failed to load students</div>';
            }
        }

        function toggleStudentSelection(id) {
            if (selectedStudentIds.has(id)) {
                selectedStudentIds.delete(id);
            } else {
                selectedStudentIds.add(id);
            }
            updateSelectedCount();
            searchEnrollableStudents();
        }

        function updateSelectedCount() {
            document.getElementById('selected-count').innerText = selectedStudentIds.size;
        }

        async function enrollStudents() {
            if (selectedStudentIds.size === 0) return;

            const btn = document.getElementById('enroll-btn');
            btn.disabled = true;
            btn.innerText = 'Enrolling...';

            try {
                const promises = Array.from(selectedStudentIds).map(studentId =>
                    fetch(`/api/v1/institute/students/${studentId}`, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                        body: JSON.stringify({ _method: 'PUT', batch_id: BATCH_ID, monthly_fee: BATCH_FEES })
                    })
                );

                await Promise.all(promises);
                showToast(`Successfully enrolled ${selectedStudentIds.size} students`, 'success');
                closeEnrollModal();
                fetchBatchData();
                fetchStudents();
            } catch (error) {
                showToast('Enrollment failed', 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Enroll Selected';
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