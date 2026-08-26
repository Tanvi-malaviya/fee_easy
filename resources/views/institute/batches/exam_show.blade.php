@extends('layouts.institute')

@section('content')
    <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

    <div class="max-w-7xl mx-auto pt-2  px-4 sm:px-3 pb-20">
        <!-- Breadcrumb -->
        <nav class="flex flex-wrap items-center gap-y-1 gap-x-2 text-[10px] sm:text-[11px] font-black text-slate-400 uppercase tracking-[0.1em] sm:tracking-[0.2em] mb-4">
            <a href="{{ route('institute.batches.index') }}" class="hover:text-primary transition-colors">Batches</a>
            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('institute.batches.show', $batchId) }}" class="hover:text-primary transition-colors">Batch Details</a>
            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('institute.batches.exams', $batchId) }}" class="hover:text-primary transition-colors">Exams</a>
            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-slate-600">Marks Entry</span>
        </nav>

        <!-- Exam Header & Stats Card -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm mb-5">
            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-5">
                <!-- Left Details -->
                <div class="shrink-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-widest bg-orange-50 text-[#FF6B00] border border-orange-100/60">
                            {{ $exam->subject ?: $batch->subject ?: 'General' }}
                        </span>
                        <span class="text-xs font-semibold text-slate-400">
                            Batch: <strong class="text-slate-700">{{ $batch->name }}</strong>
                        </span>
                    </div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight leading-tight">{{ $exam->title }}</h1>
                    <p class="text-xs text-slate-400 mt-1 font-medium flex flex-wrap items-center gap-2.5">
                        <span>Date: <strong class="text-slate-700">{{ $exam->formatted_date }}</strong></span>
                        <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                        <span>Total Marks: <strong class="text-slate-700">{{ (float) $exam->total_marks }}</strong></span>
                        <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                        <span>Passing Marks: <strong class="text-slate-700">{{ (float) $exam->passing_marks }}</strong></span>
                    </p>
                </div>

                <!-- Right 5 Stats Cards in place of Back button -->
                <div id="stats-banner" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2 w-full xl:w-auto xl:max-w-2xl">
                    <div class="bg-slate-50 p-2.5 sm:p-3 rounded-xl border border-slate-100/80 min-w-[95px] text-center">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-tight truncate">Total Students</span>
                        <span id="stat-total-students" class="text-base font-bold text-slate-800 mt-0.5 block">0</span>
                    </div>
                    <div class="bg-slate-50 p-2.5 sm:p-3 rounded-xl border border-slate-100/80 min-w-[95px] text-center">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-tight truncate">Present</span>
                        <span id="stat-present" class="text-base font-bold text-slate-800 mt-0.5 block">0</span>
                    </div>
                    <div class="bg-slate-50 p-2.5 sm:p-3 rounded-xl border border-slate-100/80 min-w-[95px] text-center">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-tight truncate">Absent</span>
                        <span id="stat-absent" class="text-base font-bold text-amber-600 mt-0.5 block">0</span>
                    </div>
                    <div class="bg-slate-50 p-2.5 sm:p-3 rounded-xl border border-slate-100/80 min-w-[95px] text-center">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-tight truncate">Passed</span>
                        <span id="stat-passed" class="text-base font-bold text-emerald-600 mt-0.5 block">0</span>
                    </div>
                    <div class="bg-slate-50 p-2.5 sm:p-3 rounded-xl border border-slate-100/80 min-w-[95px] text-center">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-tight truncate">Pass Rate</span>
                        <span id="stat-pass-rate" class="text-base font-bold text-indigo-600 mt-0.5 block">0%</span>
                    </div>
                </div>
            </div>
        </div>

        @php
            $isFutureExam = $exam->exam_date && $exam->exam_date->isFuture() && !$exam->exam_date->isToday() && $exam->status === 'scheduled';
        @endphp

        @if($isFutureExam)
        <div id="future-exam-banner" class="bg-amber-50/90 border border-amber-200/80 rounded-2xl p-4 mb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-amber-900 leading-tight">Upcoming Scheduled Examination</h4>
                    <p class="text-[11px] text-amber-700 font-medium mt-0.5">This exam is scheduled for <strong>{{ $exam->formatted_date }}</strong>. Marks entry will automatically unlock once the examination date arrives.</p>
                </div>
            </div>
            <button type="button" onclick="unlockFutureExamEntry()" id="unlock-entry-btn"
                class="px-3.5 py-1.5 bg-white hover:bg-amber-100/60 border border-amber-300 text-amber-800 text-[11px] font-bold rounded-xl transition-all shrink-0 flex items-center gap-1.5 shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                <span>Unlock & Enter Early</span>
            </button>
        </div>
        @endif

        <!-- Student Marks Entry Table Card -->
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden mt-2">
            <!-- Table Action Toolbar -->
            <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-slate-50/50">
                <!-- Search input (Matching exams list page design) -->
                <div class="flex items-center gap-0 bg-white border border-slate-200 rounded-2xl p-1 shadow-sm focus-within:border-primary transition-all w-full sm:w-auto">
                    <div class="relative flex-1">
                        <input type="text" id="student-search" onkeypress="if(event.key === 'Enter') filterStudents()"
                            placeholder="Search student by name or ID..."
                            class="pl-10 pr-8 py-2 bg-transparent rounded-xl text-xs sm:text-sm font-semibold outline-none w-full sm:w-[220px] md:w-[280px] min-w-0">
                        <svg class="w-4 h-4 text-slate-300 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <button type="button" onclick="resetStudentSearch()" id="btn-reset-search"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 hidden cursor-pointer" title="Clear">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <button type="button" onclick="filterStudents()"
                        class="px-4 py-2 bg-primary text-white text-[11px] font-bold rounded-xl hover:opacity-90 transition-colors uppercase tracking-widest shrink-0 cursor-pointer">
                        Search
                    </button>
                </div>

                <div class="flex items-center flex-wrap gap-2 w-full sm:w-auto justify-end">
                    <button onclick="quickFillPassMarks()" class="px-3 py-1.5 bg-white hover:bg-emerald-50 border border-slate-200 hover:border-emerald-200 rounded-xl text-[11px] font-bold text-emerald-700 transition-all">
                        Fill Passing Marks ({{ (float) $exam->passing_marks }})
                    </button>
                    <button onclick="clearAllMarks()" class="px-3 py-1.5 bg-white hover:bg-rose-50 border border-slate-200 hover:border-rose-200 text-[11px] font-bold text-slate-500 hover:text-rose-600 rounded-xl transition-all">
                        Clear All
                    </button>
                </div>
            </div>

            <!-- Student Cards Container (Compact 5 to 6 Cards per Row) -->
            <div id="students-cards-container" class="p-3 sm:p-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-5 2xl:grid-cols-6 gap-2.5 bg-slate-50/50">
                <div class="col-span-full py-16 text-center">
                    <div class="inline-block h-7 w-7 border-4 border-slate-100 border-t-primary rounded-full animate-spin"></div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-2.5">Loading batch students...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Sticky Save Bar -->
    <div class="fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-t border-slate-200/80 shadow-2xl py-3 px-6">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-xl bg-orange-50 text-[#FF6B00] flex items-center justify-center font-bold text-xs shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-800 leading-tight">Batch: {{ $batch->name }} &bull; {{ $exam->title }}</h4>
                    <p class="text-[10px] text-slate-400 font-medium">Click Save to record all student marks into the system.</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" onclick="saveAllMarks()" id="save-marks-btn"
                    class="px-6 py-2.5 bg-primary hover:opacity-90 text-white rounded-xl shadow-lg shadow-orange-700/20 font-bold uppercase tracking-widest text-xs transition-all transform active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Save All Marks</span>
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const examId = {{ $examId }};
    const totalMarks = {{ (float) $exam->total_marks }};
    const passingMarks = {{ (float) $exam->passing_marks }};
    let isFutureLocked = @json($exam->exam_date && $exam->exam_date->isFuture() && !$exam->exam_date->isToday() && $exam->status === 'scheduled');

    let studentsData = @json($marksData ?? []);

    document.addEventListener('DOMContentLoaded', () => {
        if (Array.isArray(studentsData) && studentsData.length > 0) {
            renderStudentsTable(studentsData);
            calculateLiveStats();
        } else {
            loadMarks();
        }
    });

    function unlockFutureExamEntry() {
        isFutureLocked = false;
        const banner = document.getElementById('future-exam-banner');
        if (banner) {
            banner.innerHTML = `
                <div class="flex items-center gap-2 text-emerald-800 text-xs font-bold w-full">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Marks entry unlocked. You can now enter and save marks.</span>
                </div>
            `;
            banner.className = 'bg-emerald-50 border border-emerald-200 rounded-xl p-3 mb-4 flex items-center shadow-xs';
        }
        renderStudentsTable(studentsData);
        showToast('Marks entry unlocked successfully.', 'success');
    }

    async function loadMarks() {
        try {
            const response = await fetch(`/api/v1/institute/exams/${examId}/marks`, {
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            if (!response.ok) {
                console.warn('Marks API response status:', response.status);
                return;
            }

            const result = await response.json();

            if (result.status === 'success') {
                studentsData = result.data.students || [];
                renderStudentsTable(studentsData);
                calculateLiveStats();
            }
        } catch (error) {
            console.warn('Notice loading background marks:', error);
        }
    }

    function renderStudentsTable(students) {
        const container = document.getElementById('students-cards-container');

        if (!students || students.length === 0) {
            container.innerHTML = `
                <div class="col-span-full py-16 text-center text-slate-400 font-semibold text-xs">
                    No students enrolled in this batch yet.
                </div>
            `;
            return;
        }

        container.innerHTML = students.map((student, idx) => {
            const isAbsent = student.is_absent;
            const marksVal = student.marks_obtained !== null && student.marks_obtained !== undefined ? student.marks_obtained : '';
            const remarksVal = student.remarks || '';
            const inputDisabled = isAbsent || isFutureLocked;

            return `
                <div id="student-row-${student.student_id}" class="student-row bg-white rounded-xl p-2.5 border border-slate-100 shadow-2xs hover:shadow-md hover:border-orange-200/80 transition-all flex flex-col justify-between gap-2" data-name="${(student.student_name || '').toLowerCase()}" data-enroll="${(student.enrollment_id || '').toLowerCase()}">
                    <!-- Top: Avatar, Name & Status Badge -->
                    <div class="flex items-start justify-between gap-1 border-b border-slate-50 pb-1.5">
                        <div class="flex items-center gap-1.5 min-w-0">
                            <span class="h-4.5 w-4.5 rounded bg-slate-100 text-slate-500 font-black text-[8.5px] flex items-center justify-center shrink-0 px-1">
                                ${idx + 1}
                            </span>
                          
                            <div class="min-w-0">
                                <h4 class="text-[10.5px] font-bold text-slate-900 leading-tight truncate" title="${student.student_name}">${student.student_name}</h4>
                                <p class="text-[8.5px] font-medium text-slate-400 leading-none mt-0.5 truncate">
                                    ID: <span class="text-slate-600 font-mono font-bold">${student.enrollment_id || 'N/A'}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Result Status Badge -->
                        <div class="shrink-0" id="status-cell-${student.student_id}">
                            ${getStatusBadge(marksVal, isAbsent)}
                        </div>
                    </div>

                    <!-- Middle: Marks Input & Absent Checkbox -->
                    <div class="grid grid-cols-3 gap-1.5 items-center">
                        <div class="col-span-2">
                            <label class="text-[8px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">
                                Marks <span class="text-slate-300 font-normal">(/${totalMarks})</span>
                            </label>
                            <input type="number" step="any" min="0" max="${totalMarks}"
                                id="mark-input-${student.student_id}"
                                data-student-index="${idx}"
                                value="${marksVal}"
                                ${inputDisabled ? 'disabled' : ''}
                                oninput="onMarkChange(${student.student_id})"
                                onkeydown="handleInputKeydown(event, ${idx})"
                                placeholder="0-${totalMarks}"
                                class="w-full px-1.5 py-1 text-xs font-black text-slate-800 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-1 focus:ring-primary/20 focus:border-primary transition-all mark-cell-input text-center h-7 ${inputDisabled ? 'bg-slate-100 opacity-50 cursor-not-allowed' : ''}">
                        </div>

                        <div>
                            <label class="text-[8px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5 text-center">Absent</label>
                            <label class="h-7 flex items-center justify-center bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg cursor-pointer transition-all">
                                <input type="checkbox"
                                    id="absent-check-${student.student_id}"
                                    ${isAbsent ? 'checked' : ''}
                                    ${isFutureLocked ? 'disabled' : ''}
                                    onchange="onAbsentToggle(${student.student_id})"
                                    class="h-3 w-3 rounded text-primary focus:ring-primary border-slate-300 ${isFutureLocked ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'}">
                            </label>
                        </div>
                    </div>

                    <!-- Bottom: Teacher Remarks -->
                    <div>
                        <input type="text"
                            id="remarks-input-${student.student_id}"
                            value="${remarksVal}"
                            ${isFutureLocked ? 'disabled' : ''}
                            placeholder="${isFutureLocked ? 'Locked' : 'Remarks...'}"
                            class="w-full px-1.5 py-0.5 text-[10px] font-medium text-slate-700 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-1 focus:ring-primary/20 focus:border-primary transition-all h-6 ${isFutureLocked ? 'bg-slate-100 opacity-50 cursor-not-allowed' : ''}">
                    </div>
                </div>
            `;
        }).join('');
    }

    function getStatusBadge(marks, isAbsent) {
        if (isAbsent) {
            return `<span class="inline-block px-1.5 py-0.5 rounded-md text-[8.5px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200/60">Absent</span>`;
        }

        if (marks === '' || marks === null || marks === undefined) {
            return `<span class="inline-block px-1.5 py-0.5 rounded-md text-[8.5px] font-bold uppercase tracking-wider bg-slate-100 text-slate-400 border border-slate-200">Pending</span>`;
        }

        const numMarks = parseFloat(marks);
        const pct = Math.round((numMarks / totalMarks) * 100);

        if (numMarks >= passingMarks) {
            return `
                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[8.5px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                    <span class="h-1 w-1 rounded-full bg-emerald-500"></span>
                    Pass (${pct}%)
                </span>
            `;
        } else {
            return `
                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[8.5px] font-bold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200/60">
                    <span class="h-1 w-1 rounded-full bg-rose-500"></span>
                    Fail (${pct}%)
                </span>
            `;
        }
    }

    function onMarkChange(studentId) {
        const input = document.getElementById(`mark-input-${studentId}`);
        const val = input.value.trim();

        if (val !== '') {
            const num = parseFloat(val);
            if (num > totalMarks) {
                input.value = totalMarks;
                showToast(`Maximum marks cannot exceed ${totalMarks}`, 'error');
            } else if (num < 0) {
                input.value = 0;
            }
        }

        const statusCell = document.getElementById(`status-cell-${studentId}`);
        const isAbsent = document.getElementById(`absent-check-${studentId}`).checked;

        statusCell.innerHTML = getStatusBadge(input.value.trim(), isAbsent);
        calculateLiveStats();
    }

    function onAbsentToggle(studentId) {
        const checkbox = document.getElementById(`absent-check-${studentId}`);
        const input = document.getElementById(`mark-input-${studentId}`);
        const statusCell = document.getElementById(`status-cell-${studentId}`);

        if (checkbox.checked) {
            input.value = '';
            input.disabled = true;
            input.classList.remove('bg-slate-50');
            input.classList.add('bg-slate-100', 'opacity-50', 'cursor-not-allowed');
        } else {
            input.disabled = false;
            input.classList.remove('bg-slate-100', 'opacity-40', 'opacity-50', 'cursor-not-allowed');
            input.classList.add('bg-slate-50');
            input.focus();
        }

        statusCell.innerHTML = getStatusBadge(input.value.trim(), checkbox.checked);
        calculateLiveStats();
    }

    function calculateLiveStats() {
        let total = studentsData.length;
        let present = 0;
        let absent = 0;
        let passed = 0;
        let totalScore = 0;

        studentsData.forEach(student => {
            const sid = student.student_id;
            const checkbox = document.getElementById(`absent-check-${sid}`);
            const input = document.getElementById(`mark-input-${sid}`);

            if (!checkbox || !input) return;

            if (checkbox.checked) {
                absent++;
            } else if (input.value.trim() !== '') {
                const mark = parseFloat(input.value.trim());
                present++;
                totalScore += mark;
                if (mark >= passingMarks) {
                    passed++;
                }
            }
        });

        document.getElementById('stat-total-students').textContent = total;
        document.getElementById('stat-present').textContent = `${present} / ${total}`;
        document.getElementById('stat-absent').textContent = absent;
        document.getElementById('stat-passed').textContent = passed;

        const passRate = present > 0 ? Math.round((passed / present) * 100) : 0;
        document.getElementById('stat-pass-rate').textContent = `${passRate}%`;

        const statAvg = document.getElementById('stat-avg');
        if (statAvg) {
            const avgScore = present > 0 ? (totalScore / present).toFixed(1) : '0.0';
            statAvg.textContent = `${avgScore} / ${totalMarks}`;
        }
    }

    function handleInputKeydown(e, idx) {
        if (e.key === 'Enter' || e.key === 'ArrowDown') {
            e.preventDefault();
            const allInputs = document.querySelectorAll('.mark-cell-input:not([disabled])');
            const currentIndex = Array.from(allInputs).findIndex(input => input === e.target);
            if (currentIndex !== -1 && currentIndex < allInputs.length - 1) {
                allInputs[currentIndex + 1].focus();
                allInputs[currentIndex + 1].select();
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            const allInputs = document.querySelectorAll('.mark-cell-input:not([disabled])');
            const currentIndex = Array.from(allInputs).findIndex(input => input === e.target);
            if (currentIndex > 0) {
                allInputs[currentIndex - 1].focus();
                allInputs[currentIndex - 1].select();
            }
        }
    }

    function quickFillPassMarks() {
        if (isFutureLocked) {
            unlockFutureExamEntry();
        }

        const markInputs = document.querySelectorAll('.mark-cell-input');
        let filledCount = 0;

        markInputs.forEach(input => {
            const sid = input.id.replace('mark-input-', '');
            const check = document.getElementById(`absent-check-${sid}`);
            if (check && !check.checked) {
                input.value = passingMarks;
                input.disabled = false;
                input.classList.remove('bg-slate-100', 'opacity-40', 'opacity-50', 'cursor-not-allowed');
                input.classList.add('bg-slate-50');
                const statusCell = document.getElementById(`status-cell-${sid}`);
                if (statusCell) {
                    statusCell.innerHTML = getStatusBadge(passingMarks, false);
                }
                filledCount++;
            }
        });

        calculateLiveStats();
        showToast(`Filled passing marks (${passingMarks}) for ${filledCount} student${filledCount === 1 ? '' : 's'}.`, 'success');
    }

    function clearAllMarks() {
        const markInputs = document.querySelectorAll('.mark-cell-input');
        const absentChecks = document.querySelectorAll('input[id^="absent-check-"]');
        const remarkInputs = document.querySelectorAll('input[id^="remarks-input-"]');
        const statusCells = document.querySelectorAll('[id^="status-cell-"]');

        markInputs.forEach(input => {
            input.value = '';
            input.disabled = isFutureLocked;
            if (!isFutureLocked) {
                input.classList.remove('bg-slate-100', 'opacity-40', 'opacity-50', 'cursor-not-allowed');
                input.classList.add('bg-slate-50');
            }
        });

        absentChecks.forEach(check => {
            check.checked = false;
        });

        remarkInputs.forEach(rem => {
            rem.value = '';
        });

        statusCells.forEach(cell => {
            cell.innerHTML = `<span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-400 border border-slate-200">Pending</span>`;
        });

        if (Array.isArray(studentsData)) {
            studentsData.forEach(student => {
                student.marks_obtained = null;
                student.is_absent = false;
                student.remarks = '';
            });
        }

        calculateLiveStats();
        showToast('All marks cleared on screen.', 'info');
    }

    function filterStudents() {
        const input = document.getElementById('student-search');
        const query = (input ? input.value : '').toLowerCase().trim();
        const rows = document.querySelectorAll('.student-row');
        const resetBtn = document.getElementById('btn-reset-search');
        
        if (resetBtn) {
            if (query.length > 0) {
                resetBtn.classList.remove('hidden');
            } else {
                resetBtn.classList.add('hidden');
            }
        }

        let matchCount = 0;
        rows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const enroll = row.getAttribute('data-enroll') || '';

            if (name.includes(query) || enroll.includes(query)) {
                row.style.display = '';
                matchCount++;
            } else {
                row.style.display = 'none';
            }
        });
    }

    function resetStudentSearch() {
        const input = document.getElementById('student-search');
        if (input) input.value = '';
        filterStudents();
        if (input) input.focus();
    }

    async function saveAllMarks() {
        const saveBtn = document.getElementById('save-marks-btn');
        saveBtn.disabled = true;
        saveBtn.innerHTML = `
            <div class="h-4 w-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
            <span>Saving Marks...</span>
        `;

        const marksPayload = [];

        studentsData.forEach(student => {
            const sid = student.student_id;
            const checkbox = document.getElementById(`absent-check-${sid}`);
            const input = document.getElementById(`mark-input-${sid}`);
            const remarksInput = document.getElementById(`remarks-input-${sid}`);

            if (!checkbox || !input) return;

            const isAbsent = checkbox.checked;
            const marksVal = input.value.trim();
            const remarksVal = remarksInput ? remarksInput.value.trim() : '';

            marksPayload.push({
                student_id: sid,
                is_absent: isAbsent,
                marks_obtained: isAbsent || marksVal === '' ? null : parseFloat(marksVal),
                remarks: remarksVal,
            });
        });

        try {
            const response = await fetch(`/api/v1/institute/exams/${examId}/marks`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    marks: marksPayload,
                    mark_status_as_completed: true,
                })
            });

            const result = await response.json();

            if (result.status === 'success') {
                showToast('Exam marks saved and updated successfully!', 'success');
                // Reload data to ensure synced state
                loadMarks();
            } else {
                showToast(result.message || 'Failed to save marks.', 'error');
            }
        } catch (error) {
            console.error('Error saving marks:', error);
            showToast('Network error while saving marks.', 'error');
        } finally {
            saveBtn.disabled = false;
            saveBtn.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span>Save All Marks</span>
            `;
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
