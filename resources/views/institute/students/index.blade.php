@extends('layouts.institute')

@section('content')
    <div class="space-y-3 max-w-[1600px] mx-auto ">
        <!-- Toast Notifications Container -->
        <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

        <!-- Page Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Student Registry</h1>
                <!-- <p class="text-sm text-slate-400 mt-2 font-medium">Cohort overview for <span
                        class="text-[#102048] font-bold">{{ $institute->institute_name }}</span>.</p> -->
            </div>
            <div class="flex items-center gap-3">
                <button onclick="openExportModal()"
                    class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold text-[13px] shadow-sm hover:bg-slate-50 transition-all flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export
                </button>
                <a href="{{ route('institute.students.create') }}"
                    class="px-6 py-3 bg-[#1e3a8a] text-white rounded-2xl font-bold text-[13px] shadow-lg shadow-blue-900/10 hover:scale-[1.02] transition-transform">
                    + Add Student
                </a>
            </div>
        </div>

    
    <!-- Registry Table -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden relative">
            <!-- Table Header with Integrated Filters -->
            <div class="p-5 border-b border-slate-50 bg-slate-50/20">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-2 relative">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" id="search-input" oninput="debounceFetch()" placeholder="Search Registry (Name, Email, Phone)..." class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-100 rounded-xl text-[13px] font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                    </div>

                    <!-- Batch Filter -->
                    <select id="filter-batch" onchange="fetchStudents()" class="w-full px-5 py-2.5 bg-white border border-slate-100 rounded-xl text-[13px] font-bold outline-none appearance-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                        <option value="">All Batches</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                        @endforeach
                    </select>

                    <!-- Status Filter -->
                    <select id="filter-status" onchange="fetchStudents()" class="w-full px-5 py-2.5 bg-white border border-slate-100 rounded-xl text-[13px] font-bold outline-none appearance-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                        <option value="">All Status</option>
                        <option value="1">Active Only</option>
                        <option value="0">Inactive Only</option>
                    </select>
                </div>
            </div>

            <!-- Table Body with Centered Loader -->
            <div id="table-container" class="relative min-h-[400px]">
                <div id="loading-spinner" class="absolute inset-0 z-50 bg-white/60 backdrop-blur-[2px] hidden flex items-center justify-center transition-all duration-300">
                    <div class="flex flex-col items-center">
                        <div class="h-12 w-12 border-4 border-slate-100 border-t-blue-600 rounded-full animate-spin"></div>
                        <span class="mt-4 text-sm font-bold text-slate-500 tracking-wide uppercase">Refining Results...</span>
                    </div>
                </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr
                            class="text-[10px] uppercase font-extrabold text-slate-400 tracking-widest border-b border-slate-50">
                            <th class="px-8 py-5">Student & ID</th>
                            <th class="px-8 py-5">Contact Details</th>
                            <th class="px-8 py-5">Academic Info</th>
                            <th class="px-8 py-5">Batch / Status</th>
                            <th class="px-8 py-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="student-table-body" class="divide-y divide-slate-50">
                        <!-- Data loaded via AJAX -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="pagination-container"
                class="px-8 py-6 bg-slate-50/20 flex items-center justify-between border-t border-slate-50">
                <!-- Pagination generated via JS -->
            </div>
        </div>
    </div>

    @push('modals')
    <!-- Export Selection Modal -->
    <div id="export-modal" class="fixed inset-0 z-[120] flex items-center justify-center hidden">
        <div onclick="closeExportModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="bg-white w-full max-w-sm rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden pt-6 px-8 pb-8 animate-in fade-in zoom-in duration-300">
            <div class="text-center mb-6">
                <div class="h-16 w-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </div>
                <h3 class="text-xl font-extrabold text-slate-800">Export Student List</h3>
                <p class="text-sm text-slate-400 mt-1">Select your preferred format.</p>
            </div>

            <div class="space-y-3 mb-8">
                <label class="relative flex items-center p-4 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-50 transition-all group has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/30">
                    <input type="radio" name="export-format" value="pdf" checked class="hidden">
                    <div class="h-10 w-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center mr-4 group-has-[:checked]:border-blue-200">
                        <svg class="w-5 h-5 text-rose-500" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2h10a2 2 0 012 2v16a2 2 0 01-2 2H7a2 2 0 01-2-2V4a2 2 0 012-2zm0 2v16h10V4H7zm2 4h6v2H9V8zm0 4h6v2H9v-2zm0 4h3v2H9v-2z"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-slate-700">PDF Document</p>
                        <p class="text-[11px] font-medium text-slate-400">Best for printing & sharing</p>
                    </div>
                    <div class="h-5 w-5 border-2 border-slate-200 rounded-full flex items-center justify-center group-has-[:checked]:border-blue-500">
                        <div class="h-2.5 w-2.5 bg-blue-500 rounded-full scale-0 transition-transform group-has-[:checked]:scale-100"></div>
                    </div>
                </label>

                <label class="relative flex items-center p-4 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-50 transition-all group has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/30">
                    <input type="radio" name="export-format" value="csv" class="hidden">
                    <div class="h-10 w-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center mr-4 group-has-[:checked]:border-emerald-200">
                        <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zM6 20V4h7v5h5v11H6z"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-slate-700">Excel / CSV</p>
                        <p class="text-[11px] font-medium text-slate-400">Best for data analysis</p>
                    </div>
                    <div class="h-5 w-5 border-2 border-slate-200 rounded-full flex items-center justify-center group-has-[:checked]:border-emerald-500">
                        <div class="h-2.5 w-2.5 bg-emerald-500 rounded-full scale-0 transition-transform group-has-[:checked]:scale-100"></div>
                    </div>
                </label>
            </div>

            <div class="flex items-center space-x-3">
                <button onclick="closeExportModal()" class="flex-1 py-3.5 text-[13px] font-bold text-slate-500 bg-slate-50 rounded-2xl">Cancel</button>
                <button onclick="runExport()" class="flex-1 py-3.5 text-[13px] font-bold text-white bg-blue-600 rounded-2xl shadow-lg shadow-blue-200">OK, Export</button>
            </div>
        </div>
    </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 z-[110] flex items-center justify-center hidden">
        <div onclick="closeDeleteModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div
            class="bg-white w-full max-w-sm rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden pt-6 px-8 pb-8 text-center animate-in fade-in zoom-in duration-300">
            <div class="h-20 w-20 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-xl font-extrabold text-slate-800 tracking-tight mb-2">Delete Student?</h3>
            <p class="text-sm text-slate-400 font-medium mb-8">This action is permanent and will remove all student data.
            </p>
            <div class="flex items-center space-x-3">
                <button onclick="closeDeleteModal()"
                    class="flex-1 py-3.5 text-[13px] font-bold text-slate-500 bg-slate-50 rounded-2xl">Cancel</button>
                <button id="confirm-delete-btn"
                    class="flex-1 py-3.5 text-[13px] font-bold text-white bg-rose-500 rounded-2xl shadow-lg shadow-rose-200 transition-transform active:scale-95">Delete</button>
            </div>
        </div>
    </div>
    @endpush

    <script>
        const INSTITUTE_NAME = "{{ $institute->institute_name }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";

        // Initial Load
        document.addEventListener('DOMContentLoaded', () => fetchStudents());

        // --- API Calls ---

        async function fetchStudents(page = 1) {
            toggleLoader(true);
            try {
                // Build Query Parameters
                const search = document.getElementById('search-input').value;
                const batchId = document.getElementById('filter-batch').value;
                const status = document.getElementById('filter-status').value;

                let url = `/api/v1/institute/students?page=${page}`;
                if (search) url += `&search=${encodeURIComponent(search)}`;
                if (batchId) url += `&batch_id=${batchId}`;
                if (status !== '') url += `&status=${status}`;

                const response = await fetch(url, {
                    headers: { 'Accept': 'application/json' }
                });
                const result = await response.json();
                if (result.status === 'success') {
                    renderStudents(result.data.items);
                    renderPagination(result.data);
                }
            } catch (error) {
                showToast('Error loading students', 'error');
            } finally {
                toggleLoader(false);
            }
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
            const batchId = document.getElementById('filter-batch').value;
            const status = document.getElementById('filter-status').value;

            let url = `/institute/students/export?format=${format}`;
            if (search) url += `&search=${encodeURIComponent(search)}`;
            if (batchId) url += `&batch_id=${batchId}`;
            if (status !== '') url += `&status=${status}`;

            closeExportModal();
            window.location.href = url;
        }



        let studentToDelete = null;

        function openDeleteModal(id) {
            studentToDelete = id;
            document.getElementById('delete-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            studentToDelete = null;
            document.getElementById('delete-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.getElementById('confirm-delete-btn').addEventListener('click', async () => {
            if (!studentToDelete) return;
            const id = studentToDelete;
            closeDeleteModal();

            try {
                const response = await fetch(`/api/v1/institute/students/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    showToast(result.message, 'success');
                    fetchStudents();
                } else {
                    showToast('Failed to delete student', 'error');
                }
            } catch (error) {
                showToast('Delete request failed', 'error');
            }
        });

        // --- UI Helpers ---

        function renderStudents(students) {
            const container = document.getElementById('student-table-body');

            if (students.length === 0) {
                container.innerHTML = `<tr><td colspan="5" class="px-8 py-20 text-center text-slate-400 font-medium">No students found in the registry.</td></tr>`;
                return;
            }

            container.innerHTML = students.map(student => `
                <tr class="hover:bg-slate-50/40 transition-all group animate-in fade-in slide-in-from-bottom-2 duration-300">
                    <td class="px-4 py-3">
                        <div class="flex items-center">
                            <div class="h-11 w-11 rounded-xl bg-slate-100 flex items-center justify-center border border-slate-200 shadow-sm mr-4 shrink-0">
                                <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(student.name)}&background=1e3a8a&color=fff" class="w-full h-full object-cover">
                            </div>
                            <div class="flex flex-col">
                                <h4 class="text-[13px] font-extrabold text-slate-800 leading-tight">${student.name}</h4>
                                <span class="text-[10px] font-bold text-blue-600 mt-1 uppercase tracking-widest">STU-${String(student.id).padStart(4, '0')}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex flex-col">
                            <span class="text-[12px] font-bold text-slate-600">${student.email}</span>
                            <span class="text-[11px] font-medium text-slate-400 mt-0.5">${student.phone || 'No Phone'}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex flex-col">
                            <span class="text-[12px] font-bold text-slate-600">${student.standard || 'N/A'}</span>
                            <span class="text-[10px] uppercase font-extrabold text-slate-400 mt-0.5 tracking-tight truncate max-w-[150px]">${INSTITUTE_NAME}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex flex-col">
                            <span class="text-[12px] font-bold text-slate-700">${student.batch ? student.batch.name : '--'}</span>
                            <div class="mt-1.5 flex items-center">
                                <span class="h-1.5 w-1.5 rounded-full ${student.status == 1 ? 'bg-emerald-500' : 'bg-rose-500'} mr-2"></span>
                                <span class="text-[9px] font-extrabold ${student.status == 1 ? 'text-emerald-600' : 'text-rose-600'} uppercase tracking-widest">${student.status == 1 ? 'Active' : 'Inactive'}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="/institute/students/${student.id}" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all" title="View Profile">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="/institute/students/${student.id}/edit" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Edit Profile">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </a>
                            <button onclick="openDeleteModal(${student.id})" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Delete Student">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function renderPagination(data) {
            const container = document.getElementById('pagination-container');
            if (!data.links || data.last_page <= 1) {
                container.innerHTML = `<span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Showing ${data.total} Students</span>`;
                return;
            }

            let html = `<span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Showing ${data.from}-${data.to} of ${data.total}</span>`;
            html += `<div class="flex items-center gap-1">`;

            data.links.forEach(link => {
                if (link.label.includes('Previous')) {
                    html += `<button onclick="${link.url ? `fetchStudents(${new URL(link.url).searchParams.get('page')})` : ''}" class="px-3 py-1.5 rounded-lg border border-slate-100 text-[10px] font-bold ${!link.url ? 'opacity-50 cursor-not-allowed' : 'hover:bg-slate-50 text-slate-600'} transition-all">Prev</button>`;
                } else if (link.label.includes('Next')) {
                    html += `<button onclick="${link.url ? `fetchStudents(${new URL(link.url).searchParams.get('page')})` : ''}" class="px-3 py-1.5 rounded-lg border border-slate-100 text-[10px] font-bold ${!link.url ? 'opacity-50 cursor-not-allowed' : 'hover:bg-slate-50 text-slate-600'} ml-1 transition-all">Next</button>`;
                } else if (link.active) {
                    html += `<button class="px-3 py-1.5 rounded-lg bg-[#1e3a8a] text-white text-[10px] font-bold shadow-md">${link.label}</button>`;
                } else if (link.label !== '...') {
                    html += `<button onclick="fetchStudents(${link.label})" class="px-3 py-1.5 rounded-lg border border-slate-100 text-[10px] font-bold text-slate-600 hover:bg-slate-50 transition-all">${link.label}</button>`;
                } else {
                    html += `<span class="px-2 text-slate-300">...</span>`;
                }
            });

            html += `</div>`;
            container.innerHTML = html;
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

        function toggleSubmitLoading(show) {
            document.getElementById('btn-loader').classList.toggle('hidden', !show);
            document.getElementById('submit-btn').disabled = show;
            document.getElementById('btn-text').classList.toggle('opacity-50', show);
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const color = type === 'success' ? 'emerald' : 'rose';

            toast.className = `bg-${color}-50 border border-${color}-200 text-${color}-600 px-6 py-4 rounded-2xl shadow-xl flex items-center animate-in slide-in-from-right-10 duration-300`;
            toast.innerHTML = `
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm font-bold">${message}</span>
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