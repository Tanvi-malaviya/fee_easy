@extends('layouts.institute')

@section('content')
    <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

    <div class="max-w-7xl mx-auto pt-3 sm:pt-2 px-4 sm:px-6">
        <!-- Breadcrumb -->
        <nav class="flex flex-wrap items-center gap-y-1 gap-x-2 text-[10px] sm:text-[11px] font-black text-slate-400 uppercase tracking-[0.1em] sm:tracking-[0.2em] mb-2">
            <a href="{{ route('institute.batches.index') }}" class="hover:text-primary transition-colors">Batches</a>
            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('institute.batches.show', $id) }}" class="hover:text-primary transition-colors">Batch Details</a>
            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-slate-600">Exams & Tests</span>
        </nav>

        <!-- Header and Action bar -->
        <div class="mb-5">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl font-semibold text-slate-800 tracking-tight mb-1">Batch Exams & Tests</h1>
                    <p class="text-xs text-slate-400 mt-0.5 font-medium">Create batch examinations, manage test schedules, and record student marks.</p>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
                    <!-- Search input -->
                    <div class="flex items-center gap-0 bg-white border border-slate-200 rounded-2xl p-1 shadow-sm focus-within:border-primary transition-all w-full sm:w-auto">
                        <div class="relative flex-1">
                            <input type="text" id="exam-search" onkeypress="if(event.key === 'Enter') loadExams()"
                                placeholder="Search exams or subjects..."
                                class="pl-10 pr-2 py-2 bg-transparent rounded-xl text-sm font-semibold outline-none w-full sm:w-[200px] md:w-[260px] min-w-0">
                            <svg class="w-4 h-4 text-slate-300 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <button onclick="loadExams()"
                            class="px-4 py-2 bg-primary text-white text-[11px] font-bold rounded-xl hover:opacity-90 transition-colors uppercase tracking-widest shrink-0">
                            Search
                        </button>
                    </div>

                    <!-- Add Exam Button -->
                    <button onclick="openAddExamModal()"
                        class="px-5 py-2.5 bg-primary hover:opacity-90 text-white text-xs font-bold rounded-xl shadow-md shadow-orange-700/10 transition-all flex items-center justify-center gap-2 w-full sm:w-auto shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Exam
                    </button>
                </div>
            </div>
        </div>

        <!-- Metric Summary Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3.5">
                <div class="h-11 w-11 rounded-xl bg-orange-50 text-[#FF6B00] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-tight">Total Exams</p>
                    <h3 id="stat-total-exams" class="text-xl font-bold text-slate-900 leading-tight mt-0.5">0</h3>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3.5">
                <div class="h-11 w-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-tight">Scheduled</p>
                    <h3 id="stat-scheduled-exams" class="text-xl font-bold text-slate-900 leading-tight mt-0.5">0</h3>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3.5">
                <div class="h-11 w-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-tight">Completed</p>
                    <h3 id="stat-completed-exams" class="text-xl font-bold text-slate-900 leading-tight mt-0.5">0</h3>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3.5">
                <div class="h-11 w-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-tight">Batch Scholars</p>
                    <h3 id="stat-batch-students" class="text-xl font-bold text-slate-900 leading-tight mt-0.5">{{ $batch->students()->count() }}</h3>
                </div>
            </div>
        </div>

        <!-- Filter Row -->
        <div class="flex items-center gap-2 mb-4 overflow-x-auto pb-1">
            <button onclick="setFilter('all')" id="filter-all" class="filter-btn px-4 py-1.5 rounded-xl text-xs font-bold bg-slate-900 text-white transition-all shadow-sm">All Exams</button>
            <button onclick="setFilter('scheduled')" id="filter-scheduled" class="filter-btn px-4 py-1.5 rounded-xl text-xs font-bold bg-white text-slate-600 hover:bg-slate-50 border border-slate-200 transition-all">Scheduled</button>
            <button onclick="setFilter('completed')" id="filter-completed" class="filter-btn px-4 py-1.5 rounded-xl text-xs font-bold bg-white text-slate-600 hover:bg-slate-50 border border-slate-200 transition-all">Completed</button>
        </div>

        <!-- Exam List Grid -->
        <div id="exams-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Loading Spinner -->
            <div class="col-span-full py-28 text-center">
                <div class="inline-block h-9 w-9 border-4 border-slate-100 border-t-primary rounded-full animate-spin"></div>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs mt-4">Loading exams...</p>
            </div>
        </div>

        <!-- Pagination -->
        <div id="pagination-container" class="mt-6 mb-8 flex flex-col sm:flex-row items-center justify-between gap-4 px-2"></div>
    </div>

    <!-- ADD EXAM MODAL -->
    <div id="add-exam-modal" class="fixed inset-0 z-[150] bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-[620px] rounded-3xl shadow-2xl overflow-hidden animate-in zoom-in duration-200 flex flex-col max-h-[90vh]">
            <!-- Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-[#e05f00] via-[#ff6c00] to-[#ff9f43] flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="h-8 w-8 rounded-lg bg-white/20 flex items-center justify-center text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-white tracking-tight">Create Batch Exam</h2>
                </div>
                <button type="button" onclick="closeAddExamModal()" class="h-8 w-8 flex items-center justify-center rounded-full hover:bg-white/10 text-white/80 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form id="exam-form" onsubmit="handleExamSubmit(event)" class="p-6 space-y-4 overflow-y-auto custom-scrollbar flex-1">
                <input type="hidden" name="batch_id" value="{{ $id }}">

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Exam Title <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required placeholder="e.g. Unit Test 1, Mid-term Exam, Chapter 4 Quiz"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Subject</label>
                        <input type="text" name="subject" value="{{ $batch->subject }}" placeholder="e.g. Mathematics, Science"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Exam Date <span class="text-rose-500">*</span></label>
                        <input type="date" name="exam_date" required value="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Total Marks <span class="text-rose-500">*</span></label>
                        <input type="number" name="total_marks" id="form-total-marks" required min="1" max="1000" step="any" value="50"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Passing Marks <span class="text-rose-500">*</span></label>
                        <input type="number" name="passing_marks" id="form-passing-marks" required min="0" max="1000" step="any" value="18"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Start Time (Optional)</label>
                        <input type="time" name="start_time"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">End Time (Optional)</label>
                        <input type="time" name="end_time"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Instructions / Syllabus Description</label>
                    <textarea name="description" rows="3" placeholder="Add syllabus topics, rules, or instructions for students..."
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeAddExamModal()"
                        class="px-5 py-2.5 text-xs font-bold text-slate-500 hover:bg-slate-100 rounded-xl transition-all">
                        Cancel
                    </button>
                    <button type="submit" id="submit-btn"
                        class="px-6 py-2.5 bg-primary text-white text-xs font-bold rounded-xl shadow-md hover:opacity-90 transition-all flex items-center gap-2">
                        <span>Save Exam</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT EXAM MODAL -->
    <div id="edit-exam-modal" class="fixed inset-0 z-[150] bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-[620px] rounded-3xl shadow-2xl overflow-hidden animate-in zoom-in duration-200 flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 bg-gradient-to-r from-slate-800 to-slate-900 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="h-8 w-8 rounded-lg bg-white/20 flex items-center justify-center text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-white tracking-tight">Edit Exam Details</h2>
                </div>
                <button type="button" onclick="closeEditExamModal()" class="h-8 w-8 flex items-center justify-center rounded-full hover:bg-white/10 text-white/80 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="edit-exam-form" onsubmit="handleEditExamSubmit(event)" class="p-6 space-y-4 overflow-y-auto custom-scrollbar flex-1">
                <input type="hidden" id="edit-exam-id">

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Exam Title <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit-title" name="title" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Subject</label>
                        <input type="text" id="edit-subject" name="subject"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Exam Date <span class="text-rose-500">*</span></label>
                        <input type="date" id="edit-date" name="exam_date" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Total Marks</label>
                        <input type="number" id="edit-total-marks" name="total_marks" required min="1" step="any"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Passing Marks</label>
                        <input type="number" id="edit-passing-marks" name="passing_marks" required min="0" step="any"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Status</label>
                        <select id="edit-status" name="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <option value="scheduled">Scheduled</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Description / Instructions</label>
                    <textarea id="edit-description" name="description" rows="3"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeEditExamModal()" class="px-5 py-2.5 text-xs font-bold text-slate-500 hover:bg-slate-100 rounded-xl transition-all">
                        Cancel
                    </button>
                    <button type="submit" id="edit-submit-btn" class="px-6 py-2.5 bg-slate-900 text-white text-xs font-bold rounded-xl shadow-md hover:bg-black transition-all">
                        Update Exam
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const batchId = {{ $id }};
    let currentFilter = 'all';
    let currentPage = 1;
    let allExamsData = @json($exams ?? []);

    document.addEventListener('DOMContentLoaded', () => {
        if (allExamsData && allExamsData.length > 0) {
            updateStats(allExamsData);
            renderExams(allExamsData);
        } else {
            loadExams(1);
        }
    });

    function setFilter(filter) {
        currentFilter = filter;
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.className = 'filter-btn px-4 py-1.5 rounded-xl text-xs font-bold bg-white text-slate-600 hover:bg-slate-50 border border-slate-200 transition-all';
        });

        const activeBtn = document.getElementById(`filter-${filter}`);
        if (activeBtn) {
            activeBtn.className = 'filter-btn px-4 py-1.5 rounded-xl text-xs font-bold bg-slate-900 text-white transition-all shadow-sm';
        }

        loadExams(1);
    }

    async function loadExams(page = 1) {
        currentPage = page;
        const container = document.getElementById('exams-container');
        const search = document.getElementById('exam-search').value.trim();

        let url = `/api/v1/institute/exams?batch_id=${batchId}&page=${page}`;
        if (currentFilter !== 'all') {
            url += `&status=${currentFilter}`;
        }
        if (search) {
            url += `&search=${encodeURIComponent(search)}`;
        }

        try {
            const response = await fetch(url, {
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const result = await response.json();

            if (result.status === 'success') {
                const exams = result.data.data;
                allExamsData = exams;
                updateStats(exams);
                renderExams(exams);
                renderPagination(result.data);
            } else {
                container.innerHTML = `
                    <div class="col-span-full py-16 text-center text-rose-500 font-bold text-sm">
                        ${result.message || 'Failed to load exams.'}
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error fetching exams:', error);
            // If offline or initial array exists, fallback to filtered client-side data
            let filtered = allExamsData || [];
            if (currentFilter !== 'all') {
                filtered = filtered.filter(e => e.status === currentFilter);
            }
            if (search) {
                const s = search.toLowerCase();
                filtered = filtered.filter(e => (e.title && e.title.toLowerCase().includes(s)) || (e.subject && e.subject.toLowerCase().includes(s)));
            }
            updateStats(filtered);
            renderExams(filtered);
        }
    }

    function updateStats(exams) {
        document.getElementById('stat-total-exams').textContent = exams.length;
        const scheduled = exams.filter(e => e.status === 'scheduled').length;
        const completed = exams.filter(e => e.status === 'completed').length;

        document.getElementById('stat-scheduled-exams').textContent = scheduled;
        document.getElementById('stat-completed-exams').textContent = completed;
    }

    function renderExams(exams) {
        const container = document.getElementById('exams-container');

        if (!exams || exams.length === 0) {
            container.innerHTML = `
                <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-dashed border-slate-200 p-8">
                    <div class="h-16 w-16 bg-orange-50 text-[#FF6B00] rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">No Exams Found</h3>
                    <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1 mb-4">No examination records created for this batch yet. Schedule your first exam to begin tracking marks.</p>
                    <button onclick="openAddExamModal()" class="px-5 py-2.5 bg-primary text-white text-xs font-bold rounded-xl hover:opacity-90 transition-all shadow-md shadow-orange-700/10 inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Schedule First Exam
                    </button>
                </div>
            `;
            return;
        }

        container.innerHTML = exams.map(exam => {
            const stats = exam.stats || {};
            const isCompleted = exam.status === 'completed';
            const isCancelled = exam.status === 'cancelled';

            const todayStr = new Date().toISOString().slice(0, 10);
            const examDateStr = exam.exam_date ? exam.exam_date.slice(0, 10) : '';
            const isFuture = examDateStr && examDateStr > todayStr;
            const isToday = examDateStr === todayStr;

            let statusBadge = `
                <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider bg-blue-50 text-blue-600 border border-blue-100 flex items-center gap-1">
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Scheduled
                </span>
            `;
            if (isCompleted) {
                statusBadge = `
                    <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center gap-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Completed
                    </span>
                `;
            } else if (isCancelled) {
                statusBadge = `
                    <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500 border border-slate-200">
                        Cancelled
                    </span>
                `;
            } else if (isToday) {
                statusBadge = `
                    <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Today
                    </span>
                `;
            } else if (examDateStr && examDateStr < todayStr && (stats.marks_entered_count || 0) === 0) {
                statusBadge = `
                    <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider bg-orange-50 text-orange-600 border border-orange-100">
                        Pending Marks
                    </span>
                `;
            }

            const enterMarksUrl = `/institute/batches/${batchId}/exams/${exam.id}`;
            const isLockedForEntry = isFuture && !isCompleted && (stats.marks_entered_count || 0) === 0;

            let actionButton = '';
            if (isLockedForEntry) {
                actionButton = `
                    <div class="flex-1 py-1.5 px-2 bg-slate-100 text-slate-400 text-[11px] font-bold rounded-xl flex items-center justify-center gap-1.5 cursor-not-allowed select-none opacity-80"
                        title="Marks entry unlocks on exam date (${exam.formatted_date || exam.exam_date})">
                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span>Opens on ${exam.formatted_date || exam.exam_date}</span>
                    </div>
                `;
            } else {
                actionButton = `
                    <a href="${enterMarksUrl}"
                        class="flex-1 py-1.5 px-2.5 bg-slate-900 hover:bg-black text-white text-[11px] font-bold rounded-xl transition-all shadow-xs flex items-center justify-center gap-1.5">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <span>${stats.marks_entered_count > 0 ? 'View / Edit Marks' : 'Enter Marks'}</span>
                    </a>
                `;
            }

            return `
                <div class="bg-white rounded-2xl border border-slate-200/70 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between overflow-hidden group">
                    <div class="p-3.5">
                        <!-- Top Row -->
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="flex-1 min-w-0">
                                <span class="text-[9px] font-extrabold text-[#FF6B00] uppercase tracking-wider block truncate">${exam.subject || 'General'}</span>
                                <h3 class="text-sm font-bold text-slate-800 tracking-tight leading-snug group-hover:text-primary transition-colors truncate mt-0.5" title="${exam.title}">${exam.title}</h3>
                            </div>
                            ${statusBadge}
                        </div>

                        <!-- Date & Marks Meta -->
                        <div class="grid grid-cols-2 gap-1.5 bg-slate-50/80 p-2 rounded-xl border border-slate-100 mb-2.5 text-xs">
                            <div class="flex items-center gap-1.5 min-w-0">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-[11px] font-bold text-slate-700 truncate">${exam.formatted_date || exam.exam_date}</span>
                            </div>

                            <div class="flex items-center gap-1.5 min-w-0 justify-end">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-[11px] font-bold text-slate-700">${parseFloat(exam.total_marks)} <span class="text-[10px] text-slate-400 font-normal">(Pass: ${parseFloat(exam.passing_marks)})</span></span>
                            </div>
                        </div>

                        <!-- Stats Pills -->
                        <div class="grid grid-cols-3 gap-1 text-center mb-1">
                            <div class="p-1.5 bg-slate-50 rounded-lg">
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider block leading-none">Entered</span>
                                <span class="text-[11px] font-bold text-slate-800 leading-none mt-1 inline-block">${stats.marks_entered_count || 0}/${stats.total_students || 0}</span>
                            </div>
                            <div class="p-1.5 bg-slate-50 rounded-lg">
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider block leading-none">Passed</span>
                                <span class="text-[11px] font-bold text-emerald-600 leading-none mt-1 inline-block">${stats.passed_count || 0}</span>
                            </div>
                            <div class="p-1.5 bg-slate-50 rounded-lg">
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider block leading-none">Avg</span>
                                <span class="text-[11px] font-bold text-indigo-600 leading-none mt-1 inline-block">${stats.average_marks || 0}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Actions -->
                    <div class="px-3.5 py-2.5 bg-slate-50/60 border-t border-slate-100 flex items-center justify-between gap-1.5">
                        ${actionButton}

                        <button onclick="openEditExamModal(${exam.id})" title="Edit Details"
                            class="h-7 w-7 flex items-center justify-center bg-white hover:bg-slate-100 border border-slate-200 text-slate-600 rounded-lg transition-all">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>

                        <button onclick="confirmDeleteExam(${exam.id})" title="Delete Exam"
                            class="h-7 w-7 flex items-center justify-center bg-white hover:bg-rose-50 border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 rounded-lg transition-all">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            `;
        }).join('');
    }

    function renderPagination(meta) {
        const container = document.getElementById('pagination-container');
        if (!meta || meta.total <= meta.per_page) {
            container.innerHTML = '';
            return;
        }

        container.innerHTML = `
            <p class="text-xs font-semibold text-slate-400">
                Showing <span class="text-slate-800">${meta.from || 0}</span> to <span class="text-slate-800">${meta.to || 0}</span> of <span class="text-slate-800">${meta.total}</span> exams
            </p>
            <div class="flex items-center gap-2">
                <button onclick="loadExams(${meta.current_page - 1})" ${meta.current_page <= 1 ? 'disabled class="opacity-40 cursor-not-allowed"' : 'class="hover:bg-slate-100"'} class="px-3 py-1.5 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 transition-all">
                    Previous
                </button>
                <span class="text-xs font-bold text-slate-800 px-2">${meta.current_page} / ${meta.last_page}</span>
                <button onclick="loadExams(${meta.current_page + 1})" ${meta.current_page >= meta.last_page ? 'disabled class="opacity-40 cursor-not-allowed"' : 'class="hover:bg-slate-100"'} class="px-3 py-1.5 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 transition-all">
                    Next
                </button>
            </div>
        `;
    }

    function openAddExamModal() {
        document.getElementById('exam-form').reset();
        document.getElementById('add-exam-modal').classList.remove('hidden');
        document.getElementById('add-exam-modal').classList.add('flex');
    }

    function closeAddExamModal() {
        document.getElementById('add-exam-modal').classList.add('hidden');
        document.getElementById('add-exam-modal').classList.remove('flex');
    }

    async function handleExamSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const submitBtn = document.getElementById('submit-btn');

        const totalMarks = parseFloat(document.getElementById('form-total-marks').value);
        const passingMarks = parseFloat(document.getElementById('form-passing-marks').value);

        if (passingMarks > totalMarks) {
            showToast('Passing marks cannot be greater than Total marks.', 'error');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <div class="h-4 w-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
            <span>Saving...</span>
        `;

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch('/api/v1/institute/exams', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.status === 'success') {
                closeAddExamModal();
                showToast('Exam scheduled successfully!', 'success');
                loadExams(1);
            } else {
                showToast(result.message || 'Failed to create exam.', 'error');
            }
        } catch (error) {
            console.error(error);
            showToast('Failed to save exam.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = `<span>Save Exam</span>`;
        }
    }

    function openEditExamModal(examId) {
        const exam = allExamsData.find(e => e.id === examId);
        if (!exam) return;

        document.getElementById('edit-exam-id').value = exam.id;
        document.getElementById('edit-title').value = exam.title || '';
        document.getElementById('edit-subject').value = exam.subject || '';
        document.getElementById('edit-date').value = exam.exam_date ? exam.exam_date.substring(0, 10) : '';
        document.getElementById('edit-total-marks').value = parseFloat(exam.total_marks) || 50;
        document.getElementById('edit-passing-marks').value = parseFloat(exam.passing_marks) || 18;
        document.getElementById('edit-status').value = exam.status || 'scheduled';
        document.getElementById('edit-description').value = exam.description || '';

        document.getElementById('edit-exam-modal').classList.remove('hidden');
        document.getElementById('edit-exam-modal').classList.add('flex');
    }

    function closeEditExamModal() {
        document.getElementById('edit-exam-modal').classList.add('hidden');
        document.getElementById('edit-exam-modal').classList.remove('flex');
    }

    async function handleEditExamSubmit(e) {
        e.preventDefault();
        const examId = document.getElementById('edit-exam-id').value;
        const submitBtn = document.getElementById('edit-submit-btn');

        const totalMarks = parseFloat(document.getElementById('edit-total-marks').value);
        const passingMarks = parseFloat(document.getElementById('edit-passing-marks').value);

        if (passingMarks > totalMarks) {
            showToast('Passing marks cannot be greater than Total marks.', 'error');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = `Updating...`;

        const data = {
            title: document.getElementById('edit-title').value,
            subject: document.getElementById('edit-subject').value,
            exam_date: document.getElementById('edit-date').value,
            total_marks: totalMarks,
            passing_marks: passingMarks,
            status: document.getElementById('edit-status').value,
            description: document.getElementById('edit-description').value,
        };

        try {
            const response = await fetch(`/api/v1/institute/exams/${examId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.status === 'success') {
                closeEditExamModal();
                showToast('Exam updated successfully!', 'success');
                loadExams(currentPage);
            } else {
                showToast(result.message || 'Failed to update exam.', 'error');
            }
        } catch (error) {
            console.error(error);
            showToast('Failed to update exam.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = `Update Exam`;
        }
    }

    function confirmDeleteExam(examId) {
        showConfirmModal(
            'Delete Exam',
            'Are you sure you want to delete this exam? All student marks and results associated with it will also be permanently deleted.',
            () => deleteExam(examId),
            'Delete Exam',
            'bg-rose-600 hover:bg-rose-700 shadow-rose-900/20',
            null,
            'Irreversible Action',
            'rose'
        );
    }

    async function deleteExam(examId) {
        try {
            const response = await fetch(`/api/v1/institute/exams/${examId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const result = await response.json();
            if (result.status === 'success') {
                showToast('Exam deleted successfully.', 'success');
                loadExams(currentPage);
            } else {
                showToast(result.message || 'Failed to delete exam.', 'error');
            }
        } catch (error) {
            console.error(error);
            showToast('Error deleting exam.', 'error');
        }
    }

    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        const isSuccess = type === 'success';

        toast.className = `p-4 rounded-2xl shadow-xl border text-sm font-bold flex items-center gap-3 transition-all duration-300 transform translate-y-2 opacity-0 ${
            isSuccess ? 'bg-white text-emerald-800 border-emerald-100 shadow-emerald-500/10' : 'bg-white text-rose-800 border-rose-100 shadow-rose-500/10'
        }`;

        toast.innerHTML = `
            <div class="h-8 w-8 rounded-xl flex items-center justify-center shrink-0 ${isSuccess ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="${isSuccess ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}"/></svg>
            </div>
            <span>${message}</span>
        `;

        container.appendChild(toast);

        setTimeout(() => {
            toast.classList.remove('translate-y-2', 'opacity-0');
        }, 10);

        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }
</script>
@endpush
