@extends('layouts.institute')

@section('content')
    <div class=" max-w-[1600px] mx-auto pb-10">
        <!-- Toast Notifications Container -->
        <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

        <!-- Main Container -->
        <div class=" rounded-[2rem] overflow-hidden relative min-h-[500px]">
            <!-- Header Toolbar: Combined Batch Info & Registry Actions -->
            <div class=" py-5 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/20">
                <div class="flex items-center gap-4">
                    <a href="{{ route('institute.batches.index') }}"
                        class="h-10 w-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-blue-600 transition-all shadow-sm group">
                        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <div class="flex items-center gap-3">
                            <h1 id="batch-name-heading" class="text-xl font-extrabold text-[#111827] tracking-tight line-clamp-1">Batch Details</h1>
                            <span id="batch-id-badge"
                                class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[9px] font-black rounded-md uppercase tracking-widest border border-blue-100 shadow-sm animate-pulse">Loading...</span>
                        </div>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Student Registry</span>
                            <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                            <span id="batch-schedule-text" class="text-[11px] font-bold text-slate-500 tracking-tight">--:-- to --:--</span>
                            <div id="loading-indicator" class="hidden h-3 w-3 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin ml-1"></div>
                        </div>
                        <p id="batch-description-text" class="text-[11px] font-bold text-slate-400 mt-1 max-w-xl line-clamp-1 italic">No description available for this batch.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <div class="relative group">
                        <input type="text" id="student-search" onkeyup="filterStudents()" placeholder="Search scholars..."
                            class="px-4 py-2 bg-white border border-slate-100 rounded-xl text-xs font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all w-48 shadow-sm">
                        <svg class="w-4 h-4 text-slate-300 absolute right-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <button onclick="openEnrollModal()"
                        class="px-4 py-2 bg-[#1e3a8a] text-white rounded-xl font-bold text-[10px] shadow-lg shadow-blue-900/10 hover:scale-[1.02] transition-transform flex items-center uppercase tracking-widest">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Enroll
                    </button>
                  
                </div>
            </div>

            <!-- Batch Stats -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Stats Cards -->
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5">
                    <div class="h-12 w-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Total Students</p>
                        <h3 id="stat-students-count" class="text-xl font-black text-slate-800">--</h3>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5">
                    <div class="h-12 w-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Monthly Fee</p>
                        <h3 id="stat-monthly-fee" class="text-xl font-black text-slate-800">₹--</h3>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5">
                    <div class="h-12 w-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
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
                        <div class="h-8 w-8 border-4 border-slate-200 border-t-blue-600 rounded-full animate-spin mx-auto"></div>
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
                <div class="p-6 border-b border-slate-100 flex-shrink-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Enroll Students</h2>
                            <p class="text-sm text-slate-400 mt-1">Select scholars to assign to <span id="target-batch-name"
                                    class="text-blue-600 font-bold">this batch</span>.</p>
                        </div>
                        <button onclick="closeEnrollModal()"
                            class="h-10 w-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-6 border-b border-slate-100 flex-shrink-0">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="enroll-search" onkeyup="searchEnrollableStudents()"
                            placeholder="Search available scholars by name or phone..."
                            class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-[12px] font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto">
                    <div class="p-6">
                        <div id="enrollable-list" class="space-y-2">
                            <!-- Populated via AJAX -->
                            <div class="py-10 text-center text-slate-400 font-medium italic text-[12px]">
                                Start typing to find available scholars...
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-slate-100 flex items-center justify-between flex-shrink-0 bg-slate-50/50">
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest"><span
                            id="selected-count">0</span> Scholars Selected</p>
                    <div class="flex gap-3">
                        <button onclick="closeEnrollModal()"
                            class="px-6 py-2.5 text-[12px] font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</button>
                        <button id="confirm-enroll-btn" onclick="confirmEnrollment()" disabled
                            class="px-6 py-2.5 bg-[#1e3a8a] text-white rounded-xl font-bold text-[12px] shadow-lg shadow-blue-900/10 hover:scale-[1.02] transition-all disabled:opacity-50 disabled:grayscale disabled:scale-100">
                            Enroll Selected
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('modals')
    <div id="student-modal" class="fixed inset-0 z-[200] flex items-center justify-center hidden">
        <div onclick="closeStudentModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div
            class="bg-white w-full max-w-4xl rounded-3xl shadow-2xl relative z-10 overflow-hidden animate-in fade-in zoom-in duration-300">
            <div class="pt-6 px-8 pb-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 id="modal-title" class="text-2xl font-extrabold text-slate-800 tracking-tight">New Student Registration</h2>
                        <p id="modal-subtitle" class="text-sm text-slate-400 mt-1">Enroll a new scholar into the academic registry.</p>
                    </div>
                    <button onclick="closeStudentModal()"
                        class="h-10 w-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="student-form" onsubmit="handleSave(event)" class="space-y-6">
                    <input type="hidden" id="student-id" name="id">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div class="space-y-2">
                            <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                            <input type="text" name="name" id="field-name" required placeholder="John Doe"
                                class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                            <input type="email" name="email" id="field-email" required placeholder="john@example.com"
                                class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label id="label-password" class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Password</label>
                            <input type="password" name="password" id="field-password" placeholder="••••••••"
                                class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Phone Number</label>
                            <input type="text" name="phone" id="field-phone" placeholder="+123 456 7890"
                                class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Assigned Batch</label>
                            <select name="batch_id" id="field-batch"
                                class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none appearance-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                                <option value="">No Batch Assigned</option>
                                @foreach(\App\Models\Batch::where('institute_id', auth()->guard('institute')->id())->get() as $batch)
                                    <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Standard</label>
                            <input type="text" name="standard" id="field-standard" placeholder="e.g. 10th Grade"
                                class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Date of Birth</label>
                            <input type="date" name="dob" id="field-dob"
                                class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Guardian Name</label>
                            <input type="text" name="guardian_name" id="field-guardian_name" placeholder="Mr. Richard Roe"
                                class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Monthly Fee (₹)</label>
                            <input type="number" name="fees" id="field-fees" placeholder="0"
                                class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Account Status</label>
                            <select name="status" id="field-status"
                                class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none appearance-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-50 flex items-center justify-end space-x-4">
                        <button type="button" onclick="closeStudentModal()"
                            class="px-8 py-3.5 text-[13px] font-bold text-slate-400">Cancel</button>
                        <button type="submit" id="submit-btn"
                            class="px-10 py-3.5 bg-[#1e3a8a] text-white rounded-2xl font-bold text-[13px] shadow-lg hover:scale-[1.02] transition-transform flex items-center">
                            <span id="btn-text">Confirm Registration</span>
                            <span id="btn-loader"
                                class="hidden h-4 w-4 border-2 border-white/30 border-t-white rounded-full animate-spin ml-3"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

    <script>
        const BATCH_ID = "{{ $id }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";
        const API_BATCH_URL = `/api/v1/institute/batches/${BATCH_ID}`;
        const API_STUDENTS_URL = `/api/v1/institute/students?batch_id=${BATCH_ID}`;
        let allStudents = [];
        let selectedStudentIds = new Set();
        let enrollableStudents = [];

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
                    document.getElementById('stat-students-count').innerText = batch.students_count || '0';
                    document.getElementById('stat-monthly-fee').innerText = `₹${batch.fees || '0'}`;
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
                    renderEnrollableList(enrollableStudents);
                }
            } catch (error) {
                listContainer.innerHTML = `<p class="py-10 text-center text-rose-500 font-bold text-xs">Failed to fetch available scholars</p>`;
            }
        }

        function renderEnrollableList(students) {
            const container = document.getElementById('enrollable-list');
            const query = document.getElementById('enroll-search').value.trim();

            if (students.length === 0 && query) {
                // Show create new student option
                container.innerHTML = `
                    <div onclick="showCreateStudentForm('${query}')" class="flex items-center p-4 border border-dashed border-blue-300 rounded-xl cursor-pointer hover:bg-blue-50 transition-all group bg-blue-50/30">
                        <div class="h-10 w-10 rounded-lg bg-blue-100 border-2 border-blue-300 flex items-center justify-center text-blue-600 mr-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-blue-700">Create New Scholar</p>
                            <p class="text-[10px] font-medium text-blue-600 uppercase tracking-widest">Name: ${query}</p>
                        </div>
                        <svg class="w-5 h-5 text-blue-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </div>
                `;
                return;
            }

            if (students.length === 0) {
                container.innerHTML = `<p class="py-10 text-center text-slate-400 font-medium italic text-[12px]">No unassigned scholars found. All available students are already enrolled in batches.</p>`;
                return;
            }

            let html = students.map(student => `
                    <div onclick="toggleStudentSelection(${student.id})" class="flex items-center p-3 border border-slate-100 rounded-lg cursor-pointer hover:bg-slate-50 transition-all group ${selectedStudentIds.has(student.id) ? 'bg-blue-50/50 border-blue-200' : ''}">
                        <div class="h-9 w-9 rounded-lg bg-white border border-slate-200 flex items-center justify-center font-bold text-slate-400 mr-3 group-hover:border-blue-200 transition-all shrink-0 text-sm">
                            ${student.name.substring(0, 1).toUpperCase()}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-700 truncate">${student.name}</p>
                            <p class="text-[9px] font-medium text-slate-400 uppercase tracking-widest">Unassigned</p>
                        </div>
                        <div class="h-5 w-5 border-2 border-slate-200 rounded-full flex items-center justify-center transition-all shrink-0 ${selectedStudentIds.has(student.id) ? 'border-blue-500 bg-blue-500' : ''}">
                            <svg class="w-3 h-3 text-white ${selectedStudentIds.has(student.id) ? 'block' : 'hidden'}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                `).join('');

            // If there's a query and results exist, also show create option
            if (query) {
                html += `
                    <div class="pt-2 mt-2 border-t border-slate-100">
                        <div onclick="showCreateStudentForm('${query}')" class="flex items-center p-3 border border-dashed border-blue-300 rounded-lg cursor-pointer hover:bg-blue-50 transition-all group bg-blue-50/30">
                            <div class="h-9 w-9 rounded-lg bg-blue-100 border-2 border-blue-300 flex items-center justify-center text-blue-600 mr-3 group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-blue-700">Create New Scholar</p>
                                <p class="text-[9px] font-medium text-blue-600 uppercase tracking-widest">Name: ${query}</p>
                            </div>
                            <svg class="w-5 h-5 text-blue-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </div>
                    </div>
                `;
            }

            container.innerHTML = html;
        }

        function showCreateStudentForm(name) {
            document.getElementById('student-form').reset();
            document.getElementById('student-id').value = '';
            document.getElementById('modal-title').innerText = 'New Student Registration';
            document.getElementById('modal-subtitle').innerText = 'Enroll a new scholar into the academic registry.';
            
            document.getElementById('field-name').value = name;
            document.getElementById('field-batch').value = BATCH_ID;
            
            document.getElementById('field-password').required = true;
            document.getElementById('label-password').innerText = 'Password';
            document.getElementById('field-email').disabled = false;
            document.getElementById('btn-text').innerText = 'Confirm Registration';

            showModal();
        }

        async function handleSave(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const id = formData.get('id');
            const isEdit = id && id !== '';

            const url = isEdit ? `/api/v1/institute/students/${id}` : `/api/v1/institute/students`;
            const jsonData = Object.fromEntries(formData.entries());
            if (isEdit) jsonData['_method'] = 'PUT';

            toggleSubmitLoading(true);

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify(jsonData)
                });

                const result = await response.json();

                if (result.status === 'success') {
                    showToast(result.message, 'success');
                    closeStudentModal();
                    
                    fetchStudents(); // Refresh main list
                    if (!document.getElementById('enroll-modal').classList.contains('hidden')) {
                        searchEnrollableStudents(); // Refresh enrollment search if open
                    }
                } else {
                    if (result.errors) {
                        Object.values(result.errors).forEach(err => showToast(err[0], 'error'));
                    } else {
                        showToast(result.message || 'Error saving data', 'error');
                    }
                }
            } catch (error) {
                showToast('Network error, please try again', 'error');
            } finally {
                toggleSubmitLoading(false);
            }
        }

        function showModal() {
            document.getElementById('student-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeStudentModal() {
            document.getElementById('student-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function toggleSubmitLoading(show) {
            const btn = document.getElementById('submit-btn');
            const loader = document.getElementById('btn-loader');
            const text = document.getElementById('btn-text');
            
            if (btn) btn.disabled = show;
            if (loader) loader.classList.toggle('hidden', !show);
            if (text) text.classList.toggle('opacity-50', show);
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
        }

        async function confirmEnrollment() {
            const btn = document.getElementById('confirm-enroll-btn');
            btn.disabled = true;
            btn.innerHTML = `<span class="h-4 w-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>`;

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
                            batch_id: BATCH_ID
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
                const initials = student.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
                return `
                        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-blue-100 transition-all group animate-in zoom-in-95 duration-300 relative">
                            <div class="flex items-center mb-3">
                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center text-white font-black text-sm tracking-tighter mr-4 shadow-lg shadow-blue-500/20 group-hover:scale-105 transition-transform shrink-0">
                                    ${initials}
                                </div>
                                <div class="overflow-hidden">
                                    <h4 class="text-[14px] font-black text-[#1f2937] leading-tight break-words">${student.name}</h4>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">ID: ST-0${student.id}</p>
                                </div>
                            </div>

                            <div class="space-y-2.5 pt-2 border-t border-slate-50">
                                <div class="flex items-center justify-between">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Phone</span>
                                    <span class="text-[11px] font-bold text-slate-700">${student.phone || 'N/A'}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Standard</span>
                                    <span class="text-[11px] font-bold text-slate-700">${student.standard || 'N/A'}</span>
                                </div>
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