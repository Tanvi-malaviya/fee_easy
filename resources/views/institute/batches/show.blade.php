@extends('layouts.institute')

@section('content')
    <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>
    <div class="max-w-[1400px] mx-auto pb-10 px-4 sm:px-6">
        <!-- Breadcrumb & Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
            <div>
                <a href="{{ route('institute.batches.index') }}"
                    class="inline-flex items-center text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] hover:text-blue-600 transition-colors group mb-3">
                    <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Batches
                </a>
                <div class="flex items-center gap-4">
                    <h1 id="batch-name-heading" class="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">Loading
                        Batch...</h1>
                </div>
                <p class="text-sm font-bold text-slate-400 mt-2 flex items-center gap-2">
                    Batch ID: <span id="batch-id-text" class="text-slate-600">---</span>
                    <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                    <span id="batch-description-text">---</span>
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button onclick="window.location.href='/institute/batches/' + BATCH_ID + '/edit'"
                    class="flex items-center gap-2 px-6 py-3 bg-white border-2 border-slate-100 rounded-2xl text-sm font-bold text-slate-700 hover:border-blue-600 hover:text-blue-600 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit Batch
                </button>
                <button onclick="deleteBatch()"
                    class="flex items-center gap-2 px-6 py-3 bg-white border-2 border-rose-50 rounded-2xl text-sm font-bold text-rose-500 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete
                </button>
            </div>
        </div>

        <!-- Top Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-12">
            <!-- Instructor Card -->
            <div
                class="bg-white p-6 rounded-2xl border border-slate-50 shadow-xl shadow-slate-200/50 relative overflow-hidden group">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-6">Instructor</p>
                <div class="flex items-center gap-5">
                    <div class="relative">
                        <div
                            class="h-16 w-16 rounded-full bg-blue-50 p-0.5 border-2 border-white shadow-sm overflow-hidden">
                            <img id="instructor-avatar"
                                src="https://ui-avatars.com/api/?name=Instructor&background=EEF2FF&color=4F46E5&bold=true"
                                class="w-full h-full object-cover rounded-full">
                        </div>
                        <div class="absolute -bottom-1 -right-1 h-5 w-5 bg-emerald-500 border-2 border-white rounded-full">
                        </div>
                    </div>
                    <div>
                        <h3 id="instructor-name" class="text-xl font-bold text-slate-900 leading-tight">Not Assigned</h3>
                        <p id="instructor-role" class="text-[11px] font-bold text-slate-400 mt-1 uppercase tracking-wider">
                            Primary Tutor</p>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-slate-50 flex items-center gap-3">
                    <div
                        class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span id="instructor-email" class="text-xs font-bold text-slate-500">contact@institute.edu</span>
                </div>
            </div>

            <!-- Schedule Card -->
            <div
                class="bg-white p-6 rounded-2xl border border-slate-50 shadow-xl shadow-slate-200/50 relative overflow-hidden group">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-6">Schedule & Venue</p>
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p id="batch-days" class="text-sm font-bold text-slate-900 leading-tight">Mon, Wed, Fri</p>
                            <p id="batch-time" class="text-[11px] font-bold text-slate-400 mt-1 uppercase tracking-wider">
                                10:00 AM - 12:30 PM</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900 leading-tight">Classroom A1</p>
                            <p class="text-[11px] font-bold text-slate-400 mt-1 uppercase tracking-wider">Main Campus</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enrollment Card -->
            <div
                class="bg-white p-6 rounded-2xl border border-slate-50 shadow-xl shadow-slate-200/50 relative overflow-hidden group">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Enrollment Status</p>
                <div class="flex items-end gap-2 mb-4">
                    <h2 id="current-enrollment" class="text-5xl font-bold text-slate-900">0</h2>
                    <p class="text-xl font-bold text-slate-300 mb-1">/ <span id="batch-capacity">50</span></p>
                </div>

                <div class="w-full h-3 bg-slate-50 rounded-full overflow-hidden mb-6 border border-slate-100">
                    <div id="enrollment-progress"
                        class="h-full bg-gradient-to-r from-orange-500 to-rose-500 rounded-full transition-all duration-1000"
                        style="width: 0%"></div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.1em] mb-1">Total Fee</p>
                        <h4 id="stat-monthly-fee" class="text-lg font-bold text-slate-800">₹0</h4>
                    </div>
                    <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.1em] mb-1">Collection</p>
                        <h4 id="stat-total-paid" class="text-lg font-bold text-slate-800">₹0</h4>
                    </div>
                </div>

                <!-- Abstract background icon -->
                <svg class="absolute -bottom-4 -right-4 w-24 h-24 text-slate-50/50 pointer-events-none" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>

        <!-- Quick Access Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
            <!-- Students -->
            <a href="{{ route('institute.batches.students', $id) }}"
                class="group bg-white p-6 rounded-2xl border-t-4 border-t-orange-500 shadow-lg shadow-slate-200/40 hover:shadow-2xl hover:shadow-orange-200/40 transition-all hover:-translate-y-2 relative overflow-hidden">
                <div
                    class="h-14 w-14 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-500 mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 leading-none">Students</h3>
                <p class="text-[11px] font-bold text-slate-400 mt-3 uppercase tracking-wider">Manage enrollments</p>
                <div class="absolute top-6 right-8 text-slate-100 group-hover:text-orange-100 transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </div>
            </a>

            <!-- Homework -->
            <a href="{{ route('institute.batches.homework', $id) }}"
                class="group bg-white p-6 rounded-2xl border-t-4 border-t-teal-500 shadow-lg shadow-slate-200/40 hover:shadow-2xl hover:shadow-teal-200/40 transition-all hover:-translate-y-2 relative overflow-hidden">
                <div
                    class="h-14 w-14 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-500 mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M9 5H7a2 2 0 00-2-2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 leading-none">Homework</h3>
                <p class="text-[11px] font-bold text-slate-400 mt-3 uppercase tracking-wider">Manage assignments</p>
                <div class="absolute top-6 right-8 text-slate-100 group-hover:text-teal-100 transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </div>
            </a>

            <!-- Attendance -->
            <a href="{{ route('institute.batches.attendance', $id) }}"
                class="group bg-white p-6 rounded-2xl border-t-4 border-t-amber-700 shadow-lg shadow-slate-200/40 hover:shadow-2xl hover:shadow-amber-200/40 transition-all hover:-translate-y-2 relative overflow-hidden">
                <div
                    class="h-14 w-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-700 mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 leading-none">Attendance</h3>
                <p class="text-[11px] font-bold text-slate-400 mt-3 uppercase tracking-wider">Track student presence</p>
                <div class="absolute top-6 right-8 text-slate-100 group-hover:text-amber-100 transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </div>
            </a>

            <!-- Resources -->
            <a href="{{ route('institute.batches.resources', $id) }}"
                class="group bg-white p-6 rounded-2xl border-t-4 border-t-emerald-600 shadow-lg shadow-slate-200/40 hover:shadow-2xl hover:shadow-emerald-200/40 transition-all hover:-translate-y-2 relative overflow-hidden">
                <div
                    class="h-14 w-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 leading-none">Resources</h3>
                <p class="text-[11px] font-bold text-slate-400 mt-3 uppercase tracking-wider">Materials and documents</p>
                <div class="absolute top-6 right-8 text-slate-100 group-hover:text-emerald-100 transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </div>
            </a>
        </div>


        <!-- Enrollment Modal -->
        <div id="enroll-modal" class="fixed inset-0 z-[120] flex items-center justify-center hidden">
            <div onclick="closeEnrollModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div
                class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl relative z-10 overflow-hidden animate-in fade-in zoom-in duration-300 max-h-[90vh] flex flex-col">

                <!-- Header with Actions -->
                <div class="p-6 border-b border-slate-100 flex-shrink-0 bg-white relative z-20">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-slate-800 tracking-tight leading-none">Assign Students
                            </h2>
                            <p class="text-[11px] font-bold text-slate-400 mt-2 uppercase tracking-widest">Assigning to
                                <span id="target-batch-name" class="text-blue-600">this batch</span>
                            </p>
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
                    class="flex-1 flex flex-col items-center justify-center p-6 text-center bg-white overflow-hidden">
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
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest"><span
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
            class="bg-white w-full max-w-[320px] rounded-2xl shadow-2xl relative z-10 overflow-hidden p-6 text-center animate-in fade-in zoom-in duration-200">
            <div class="h-14 w-14 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 leading-tight">Remove Scholar?</h3>
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
                    document.getElementById('batch-id-text').innerText = `BATCH-${batch.id}`;

                    // Populate Schedule
                    if (batch.days && Array.isArray(batch.days)) {
                        document.getElementById('batch-days').innerText = batch.days.join(', ');
                    }
                    document.getElementById('batch-time').innerText = `${batch.start_time || '--:--'} - ${batch.end_time || '--:--'}`;

                    // Populate Stats
                    const count = batch.students_count || 0;
                    const capacity = batch.capacity || 50; // Fallback to 50 if not in model
                    document.getElementById('current-enrollment').innerText = count;
                    document.getElementById('batch-capacity').innerText = capacity;

                    // Update Progress Bar
                    const progress = (count / capacity) * 100;
                    document.getElementById('enrollment-progress').style.width = `${Math.min(progress, 100)}%`;

                    document.getElementById('stat-monthly-fee').innerText = `₹${batch.fees || 0}`;
                    document.getElementById('stat-total-paid').innerText = `₹${batch.total_paid || 0}`;

                    // Populate Description
                    if (batch.description) {
                        document.getElementById('batch-description-text').innerText = batch.description;
                    }

                    // Mock Instructor data if not available (following design)
                    if (batch.subject) {
                        document.getElementById('instructor-role').innerText = batch.subject;
                    }
                }
            } catch (error) {
                showToast('Failed to load batch info', 'error');
            }
        }

        async function fetchStudents() {
            const loader = document.getElementById('loading-indicator');
            if (loader) loader.classList.remove('hidden');
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
                                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                    </svg>
                                                </div>
                                                <p class="text-rose-500 font-bold uppercase tracking-[0.2em] text-[10px]">Failed to Load Student Records</p>
                                                <button onclick="fetchStudents()" class="mt-4 text-blue-600 font-bold text-xs hover:underline uppercase tracking-widest">Try Again</button>
                                            </div>`;
            } finally {
                if (loader) loader.classList.add('hidden');
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
                                                                class="w-16 px-2 py-1 ${isSelected ? 'bg-blue-700/50 text-white border-blue-400/30' : 'bg-slate-50 text-blue-600 border-blue-100'} border rounded-lg text-[11px] font-bold outline-none focus:ring-2 focus:ring-white/20 transition-all">
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
                                                        <div class="h-6 w-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mr-2.5 text-[10px] font-bold group-hover/chip:scale-110 transition-transform">
                                                            ${student.name.substring(0, 1).toUpperCase()}
                                                        </div>
                                                        <span class="max-w-[80px] truncate">${student.name}</span>
                                                        <div class="ml-2 flex items-center gap-1 bg-slate-50 px-2 py-0.5 rounded-lg border border-slate-100 group-hover/chip:border-blue-200 group-hover/chip:bg-blue-50 transition-colors mr-1">
                                                            <span class="text-[9px] text-slate-400 font-bold">₹</span>
                                                            <input type="number" 
                                                                value="${studentFees.get(id) || BATCH_FEES}" 
                                                                onchange="studentFees.set(${id}, this.value)"
                                                                class="w-12 bg-transparent text-[10px] text-blue-600 font-bold outline-none border-none p-0 text-center [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
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
                                                <div class="h-20 w-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mb-6">
                                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                                    </svg>
                                                </div>
                                                <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-[10px]">No Scholars Found</p>
                                            </div>`;
                return;
            }

            container.innerHTML = students.map(student => {
                const initial = student.name.charAt(0).toUpperCase();
                return `
                                            <div class="group relative bg-white rounded-2xl border border-slate-50 p-6 hover:shadow-2xl hover:shadow-slate-200/50 hover:border-blue-100 transition-all duration-500 cursor-pointer overflow-hidden"
                                                 onclick="window.location.href='/institute/students/${student.id}'">

                                                <!-- Top Action Area -->
                                                <div class="flex items-start justify-between mb-6">
                                                    <div class="h-16 w-16 rounded-2xl bg-slate-50 p-0.5 border border-slate-100 overflow-hidden group-hover:scale-110 transition-transform duration-500">
                                                        <img src="${student.profile_image_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(student.name) + '&background=F8FAFC&color=64748B&bold=true'}" 
                                                             class="w-full h-full object-cover rounded-2xl">
                                                    </div>
                                                    <button onclick="event.stopPropagation(); removeFromBatch(${student.id}, '${student.name.replace(/'/g, "\\'")}')" 
                                                            class="h-10 w-10 bg-white border border-slate-50 rounded-xl flex items-center justify-center text-slate-300 hover:bg-rose-50 hover:text-rose-500 hover:border-rose-100 transition-all opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 duration-300">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>

                                                <!-- Info Section -->
                                                <div class="mb-6">
                                                    <h4 class="text-lg font-bold text-slate-900 leading-tight group-hover:text-blue-600 transition-colors">${student.name}</h4>
                                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">ID: ST-${String(student.id).padStart(4, '0')}</p>
                                                </div>

                                                <!-- Stats Grid -->
                                                <div class="grid grid-cols-2 gap-3 pb-6 border-b border-slate-50">
                                                    <div class="bg-slate-50/50 p-3 rounded-xl border border-slate-100/50">
                                                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider mb-1">Standard</p>
                                                        <p class="text-xs font-bold text-slate-700">${student.standard || 'N/A'}</p>
                                                    </div>
                                                    <div class="bg-blue-50/30 p-3 rounded-xl border border-blue-50/50">
                                                        <p class="text-[8px] font-bold text-blue-400 uppercase tracking-wider mb-1">Fee Plan</p>
                                                        <p class="text-xs font-bold text-blue-600">₹${student.fees || student.monthly_fee || '0'}</p>
                                                    </div>
                                                </div>

                                                <!-- Footer -->
                                                <div class="pt-4 flex items-center justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <div class="h-2 w-2 rounded-full ${student.status == 1 ? 'bg-emerald-500' : 'bg-slate-300'}"></div>
                                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">${student.status == 1 ? 'Active' : 'Inactive'}</span>
                                                    </div>
                                                    <div class="flex items-center gap-1 text-slate-400">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                        </svg>
                                                        <span class="text-[10px] font-bold">${student.phone || 'No Phone'}</span>
                                                    </div>
                                                </div>

                                                <!-- Hover Arrow -->
                                                <div class="absolute -bottom-2 -right-2 h-12 w-12 bg-blue-600 rounded-tl-3xl flex items-center justify-center text-white translate-x-12 translate-y-12 group-hover:translate-x-0 group-hover:translate-y-0 transition-all duration-500">
                                                    <svg class="w-5 h-5 -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                    </svg>
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
        async function deleteBatch() {
            if (!confirm('Are you sure you want to delete this batch? This action cannot be undone.')) return;

            try {
                const response = await fetch(API_BATCH_URL, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                });

                const result = await response.json();
                if (result.status === 'success') {
                    window.location.href = "{{ route('institute.batches.index') }}";
                } else {
                    showToast(result.message || 'Failed to delete batch', 'error');
                }
            } catch (error) {
                showToast('An error occurred while deleting the batch', 'error');
            }
        }
    </script>
@endsection