@extends('layouts.institute')

@section('content')
    <div class="space-y-2 max-w-[1600px] mx-auto pb-2">
        <!-- Toast Notifications Container -->
        <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

        <!-- Premium Header & Action Row -->
        <div class="bg-white p-2 rounded-xl border border-slate-100 shadow-sm flex flex-col lg:flex-row items-center gap-3 mb-2">
            <!-- Title -->
            <div class="px-3 border-r border-slate-100 hidden lg:block">
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">Student Management</h1>
            </div>
            
            <!-- Mobile Title -->
            <div class="lg:hidden w-full px-2 mb-1">
                <h1 class="text-lg font-bold text-slate-800 tracking-tight">Student Management</h1>
            </div>

            <!-- Integrated Search Bar -->
          

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 w-full lg:w-auto lg:ml-auto pr-1">
                  <div class="flex-1 relative w-full lg:max-w-md">
                <div class="relative flex items-center bg-slate-50/50 border border-slate-100 rounded-lg focus-within:bg-white focus-within:ring-2 focus-within:ring-orange-500/10 focus-within:border-orange-500/20 transition-all p-1">
                    <div class="pl-2.5 pr-2 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="search-input" onkeypress="if(event.key === 'Enter') fetchStudents()"
                        placeholder="Search students..."
                        class="flex-1 bg-transparent border-none py-1.5 text-sm font-medium outline-none">
                    <button onclick="fetchStudents()" class="px-4 py-1.5 bg-slate-800 text-white rounded-md font-bold text-[11px] hover:bg-slate-900 transition-all shadow-sm">
                        Search
                    </button>
                </div>
            </div>
                <button onclick="openExportModal()" class="flex-1 lg:flex-none px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg font-bold text-[12px] hover:bg-slate-50 transition-all flex items-center justify-center group">
                    <svg class="w-3.5 h-3.5 mr-2 text-slate-400 group-hover:text-slate-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export
                </button>
                <a href="{{ route('institute.students.create') }}" class="flex-1 lg:flex-none px-5 py-2 bg-[#ff6600] text-white rounded-lg font-bold text-[12px] shadow-lg shadow-orange-900/10 hover:bg-[#e65c00] transition-all text-center whitespace-nowrap">
                    + New Student
                </a>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 mb-2">
            <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm flex items-center gap-3">
                <div class="h-9 w-9 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Students</p>
                    <h3 id="stat-total-students" class="text-xl font-bold text-slate-800 leading-none">...</h3>
                </div>
            </div>



            <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm flex items-center gap-3">
                <div class="h-9 w-9 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Performance</p>
                    <h3 id="stat-performance" class="text-xl font-bold text-slate-800 leading-none">...</h3>
                </div>
            </div>

            <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm flex items-center gap-3">
                <div class="h-9 w-9 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pending Fees</p>
                    <h3 id="stat-pending-fees" class="text-xl font-bold text-slate-800 leading-none">...</h3>
                </div>
            </div>
        </div>

          

            <!-- Registry Grid Container -->
            <div id="table-container" class="relative">
                <div id="loading-spinner"
                    class="absolute inset-0 z-50 bg-white/60 backdrop-blur-[2px] hidden flex items-center justify-center transition-all duration-300">
                    <div class="flex flex-col items-center">
                        <div class="h-10 w-10 border-4 border-slate-100 border-t-blue-600 rounded-full animate-spin"></div>
                    </div>
                </div>

                <!-- Grid -->
                <div id="student-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                    <!-- Data loaded via AJAX -->
                </div>

                <!-- Pagination -->
                <div id="pagination-container"
                    class="mt-2 px-5 py-3 bg-white rounded-xl border border-slate-100 flex items-center justify-between shadow-sm">
                    <!-- Pagination generated via JS -->
                </div>
            </div>

            @push('modals')
                    <!-- Export Selection Modal -->
                    <div id="export-modal" class="fixed inset-0 z-[120] flex items-center justify-center hidden">
                        <div onclick="closeExportModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
                        <div
                            class="bg-white w-full max-w-sm rounded-xl shadow-2xl relative z-10 overflow-hidden pt-6 px-8 pb-8 animate-in fade-in zoom-in duration-300">
                            <div class="text-center mb-6">
                                <div
                                    class="h-16 w-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-extrabold text-slate-800">Export Student List</h3>
                                <p class="text-sm text-slate-400 mt-1">Select your preferred format.</p>
                            </div>

                            <div class="space-y-3 mb-8">
                                <label
                                    class="relative flex items-center p-4 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-50 transition-all group has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/30">
                                    <input type="radio" name="export-format" value="pdf" checked class="hidden">
                                    <div
                                        class="h-10 w-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center mr-4 group-has-[:checked]:border-blue-200">
                                        <svg class="w-5 h-5 text-rose-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M7 2h10a2 2 0 012 2v16a2 2 0 01-2 2H7a2 2 0 01-2-2V4a2 2 0 012-2zm0 2v16h10V4H7zm2 4h6v2H9V8zm0 4h6v2H9v-2zm0 4h3v2H9v-2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-slate-700">PDF Document</p>
                                        <p class="text-[11px] font-medium text-slate-400">Best for printing & sharing</p>
                                    </div>
                                    <div
                                        class="h-5 w-5 border-2 border-slate-200 rounded-full flex items-center justify-center group-has-[:checked]:border-blue-500">
                                        <div
                                            class="h-2.5 w-2.5 bg-blue-500 rounded-full scale-0 transition-transform group-has-[:checked]:scale-100">
                                        </div>
                                    </div>
                                </label>

                                <label
                                    class="relative flex items-center p-4 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-50 transition-all group has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/30">
                                    <input type="radio" name="export-format" value="csv" class="hidden">
                                    <div
                                        class="h-10 w-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center mr-4 group-has-[:checked]:border-emerald-200">
                                        <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zM6 20V4h7v5h5v11H6z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-slate-700">Excel / CSV</p>
                                        <p class="text-[11px] font-medium text-slate-400">Best for data analysis</p>
                                    </div>
                                    <div
                                        class="h-5 w-5 border-2 border-slate-200 rounded-full flex items-center justify-center group-has-[:checked]:border-emerald-500">
                                        <div
                                            class="h-2.5 w-2.5 bg-emerald-500 rounded-full scale-0 transition-transform group-has-[:checked]:scale-100">
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div class="flex items-center space-x-3">
                                <button onclick="closeExportModal()"
                                    class="flex-1 py-3.5 text-[13px] font-bold text-slate-500 bg-slate-50 rounded-2xl">Cancel</button>
                                <button onclick="runExport()"
                                    class="flex-1 py-3.5 text-[13px] font-bold text-white bg-blue-600 rounded-2xl shadow-lg shadow-blue-200">OK,
                                    Export</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <div id="delete-modal" class="fixed inset-0 z-[110] flex items-center justify-center hidden px-4">
                    <div onclick="closeDeleteModal()" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
                    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl relative z-10 overflow-hidden animate-in fade-in zoom-in duration-300">
                        <!-- Top Accent Border -->
                        <div class="h-1 bg-rose-600 w-full"></div>

                        <div class="p-6">
                            <div class="flex items-start gap-4 mb-5">
                                <div class="h-10 w-10 bg-rose-50 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1-1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-800 mb-2">Delete Student?</h3>
                                    <p class="text-sm text-slate-500 leading-relaxed">
                                        Are you sure you want to permanently remove <span id="delete-student-name" class="font-bold text-slate-800"></span>? This action cannot be undone and will erase all academic and financial history.
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 mb-2">
                                <button onclick="closeDeleteModal()" class="flex-1 py-2.5 border-2 border-teal-600 text-teal-600 rounded-xl font-semibold text-sm hover:bg-teal-50 transition-all text-center">
                                    Cancel
                                </button>
                                <button id="confirm-delete-btn" onclick="executeDelete()" class="flex-1 py-2.5 bg-rose-600 text-white rounded-xl font-semibold text-sm shadow-lg shadow-rose-900/10 hover:bg-rose-700 transition-all">
                                    Yes, Delete Student
                                </button>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="bg-slate-50 px-6 py-3 flex items-center gap-2 border-t border-slate-100">
                            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Authenticated as Admin</span>
                        </div>
                    </div>
                </div>
            @endpush

        <script>
            const INSTITUTE_NAME = "{{ $institute->institute_name }}";
            const CSRF_TOKEN = "{{ csrf_token() }}";

            // Initial Load
            document.addEventListener('DOMContentLoaded', () => {
                fetchStudents();
                fetchDashboardStats();
            });

            // --- API Calls ---

            async function fetchDashboardStats() {
                try {
                    const response = await fetch('{{ url('/api/v1/institute/reports/dashboard') }}', {
                        headers: { 'Accept': 'application/json' }
                    });
                    const result = await response.json();
                    if (result.status === 'success') {
                        const stats = result.data;
                        document.getElementById('stat-total-students').textContent = stats.students_count.toLocaleString();
                        document.getElementById('stat-performance').textContent = stats.performance || '0%';
                        document.getElementById('stat-pending-fees').textContent = '₹' + (stats.total_due_fees || 0).toLocaleString();
                    }
                } catch (error) {
                    console.error('Error loading stats:', error);
                }
            }

            async function fetchStudents(page = 1) {
                toggleLoader(true);
                try {
                    // Build Query Parameters
                    const search = document.getElementById('search-input').value;

                    let url = `{{ url('/api/v1/institute/students') }}?page=${page}`;
                    if (search) url += `&search=${encodeURIComponent(search)}`;

                    const response = await fetch(url, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const result = await response.json();
                    if (result.status === 'success') {
                        renderStudents(result.data.items);
                        renderPagination(result.data);

                        // Update Dashboard Stats from Real Data
                        if (result.data.stats) {
                            document.getElementById('stat-performance').textContent = result.data.stats.performance;
                        }
                    }
                } catch (error) {
                    showToast('Error loading students', 'error');
                } finally {
                    toggleLoader(false);
                }
            }

            function resetFilters() {
                document.getElementById('search-input').value = '';
                fetchStudents();
            }

            let fetchTimeout = null;
            function debounceFetch() {
                if (fetchTimeout) clearTimeout(fetchTimeout);
                fetchTimeout = setTimeout(() => fetchStudents(), 500);
            }

            function openExportModal() {
                document.getElementById('export-modal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeExportModal() {
                document.getElementById('export-modal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            function runExport() {
                const format = document.querySelector('input[name="export-format"]:checked').value;
                const search = document.getElementById('search-input').value;

                let url = `/institute/students/export?format=${format}`;
                if (search) url += `&search=${encodeURIComponent(search)}`;

                closeExportModal();
                window.location.href = url;
            }

            let studentToDelete = null;

            function openDeleteModal(id, name = '') {
                studentToDelete = id;
                if (name) {
                    document.getElementById('delete-student-name').textContent = name;
                }
                document.getElementById('delete-modal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeDeleteModal() {
                studentToDelete = null;
                document.getElementById('delete-modal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            window.executeDelete = async function () {
                if (!studentToDelete) return;
                const id = studentToDelete;
                closeDeleteModal();

                try {
                    const response = await fetch(`/institute/students/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (response.ok) {
                        const result = await response.json();
                        showToast(result.message || 'Student deleted successfully', 'success');
                        fetchStudents();
                    } else {
                        const errorData = await response.json().catch(() => ({}));
                        showToast(errorData.message || 'Failed to delete student', 'error');
                    }
                } catch (error) {
                    console.error('Delete error:', error);
                    showToast('Delete request failed', 'error');
                }
            }

            // --- UI Helpers ---

            function renderStudents(students) {
                const container = document.getElementById('student-grid');

                if (students.length === 0) {
                    container.innerHTML = `<div class="col-span-full flex flex-col items-center justify-center py-12 text-slate-400 bg-white rounded-2xl border border-slate-100">
                                                <svg class="w-16 h-16 mb-4 opacity-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                                <p class="font-bold text-sm uppercase tracking-widest text-slate-300">No students found</p>
                                            </div>`;
                    return;
                }

                container.innerHTML = students.map(student => {
                    // Real performance based on average homework score (out of 10)
                    const avgScore = student.homework_submissions_avg_score ? parseFloat(student.homework_submissions_avg_score) : 0;
                    const performance = Math.round((avgScore / 10) * 100);
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
                                    <!-- Batch Badge -->
                                    <div class="absolute top-4 right-4">
                                        <span class="px-2 py-0.5 bg-slate-50 text-slate-400 text-[9px] font-black rounded-md uppercase tracking-tight">
                                            ${student.batch ? student.batch.name.substring(0, 15) : 'Unassigned'}
                                        </span>
                                    </div>

                                    <!-- Profile Section -->
                                    <div class="flex flex-col items-left mb-4">
                                        <div class="h-16 w-16 rounded-full border-2 border-slate-50 overflow-hidden mb-3 shadow-inner">
                                            <img src="${student.profile_image_url}" class="w-full h-full object-cover">
                                        </div>
                                        <h4 class="text-base font-black text-slate-800 text-left tracking-tight leading-tight">${student.name}</h4>
                                        <p class="text-[10px] font-bold text-slate-400 mt-0.5">${student.email || 'no-email@tuoora.edu'}</p>
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
                                        <button onclick="event.stopPropagation(); openDeleteModal(${student.id}, '${student.name.replace(/'/g, "\\'")}')" class="action-btn text-slate-400 hover:text-rose-500 transition-all" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                }).join('');
            }

            function renderPagination(data) {
                const container = document.getElementById('pagination-container');
                if (!data.links || data.last_page <= 1) {
                    container.innerHTML = `<span class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Showing ${data.total} students</span>`;
                    return;
                }

                let html = `<div class="flex items-center gap-2">`;

                // Prev Button
                const prevLink = data.links[0];
                html += `<button onclick="${prevLink.url ? `fetchStudents(${new URL(prevLink.url).searchParams.get('page')})` : ''}" 
                                class="h-10 w-10 flex items-center justify-center rounded-xl border border-slate-100 ${!prevLink.url ? 'opacity-30 cursor-not-allowed' : 'hover:bg-slate-50 text-slate-600'} transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            </button>`;

                // Pages
                data.links.slice(1, -1).forEach(link => {
                    if (link.active) {
                        html += `<button class="h-10 w-10 flex items-center justify-center rounded-xl bg-[#a85000] text-white text-[13px] font-black shadow-lg shadow-orange-900/10">${link.label}</button>`;
                    } else if (link.label === '...') {
                        html += `<span class="h-10 w-10 flex items-center justify-center text-slate-300 font-black text-[13px]">...</span>`;
                    } else {
                        html += `<button onclick="fetchStudents(${link.label})" class="h-10 w-10 flex items-center justify-center rounded-xl bg-white border border-slate-100 text-[13px] font-black text-slate-500 hover:bg-slate-50 transition-all shadow-sm">${link.label}</button>`;
                    }
                });

                // Next Button
                const nextLink = data.links[data.links.length - 1];
                html += `<button onclick="${nextLink.url ? `fetchStudents(${new URL(nextLink.url).searchParams.get('page')})` : ''}" 
                                class="h-10 w-10 flex items-center justify-center rounded-xl border border-slate-100 ${!nextLink.url ? 'opacity-30 cursor-not-allowed' : 'hover:bg-slate-50 text-slate-600'} transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </button>`;

                html += `</div>`;
                container.innerHTML = `
                        <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest hidden sm:block">Showing ${data.from}-${data.to} of ${data.total} entries</span>
                        ${html}
                    `;
            }

            function toggleLoader(show) {
                const loader = document.getElementById('loading-spinner');
                if (show) {
                    loader.classList.remove('hidden');
                    loader.classList.add('flex');
                } else {
                    loader.classList.add('hidden');
                    loader.classList.remove('flex');
                }
            }

            function showToast(message, type = 'success') {
                const container = document.getElementById('toast-container');
                const toast = document.createElement('div');
                const color = type === 'success' ? 'emerald' : 'rose';

                toast.className = `bg-white border border-slate-100 text-${color}-600 px-6 py-4 rounded-2xl shadow-xl flex items-center animate-in slide-in-from-right-10 duration-300 border-l-4 border-l-${color}-500`;
                toast.innerHTML = `
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-sm font-black uppercase tracking-tight">${message}</span>
                    `;

                container.appendChild(toast);
                setTimeout(() => {
                    toast.classList.replace('animate-in', 'animate-out');
                    toast.classList.add('fade-out');
                    setTimeout(() => toast.remove(), 500);
                }, 4000);
            }
        </script>

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;400;500;600;700;800;900&display=swap');

            :root {
                --font-outfit: 'Outfit', sans-serif;
            }

            body {
                font-family: var(--font-outfit);
            }

            .custom-scrollbar::-webkit-scrollbar {
                width: 4px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #e2e8f0;
                border-radius: 10px;
            }
        </style>
@endsection