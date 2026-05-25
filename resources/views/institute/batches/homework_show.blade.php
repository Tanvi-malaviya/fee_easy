@extends('layouts.institute')

@section('content')
    <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

    <div class="max-w-7xl mx-auto pt-2">
        <!-- Header -->
        <div class="mb-4 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 text-[11px] font-bold text-[#ff6c00] uppercase tracking-[0.15em] mb-2">
                    <a href="{{ route('institute.batches.homework', $id) }}"
                        class="flex items-center gap-2  transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back
                    </a>
                    <span class="text-slate-300">|</span>
                    <svg class="w-4 h-4 text-[#ff6c00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span id="header-batch-name">Loading...</span>
                </div>
                <h1 id="header-homework-title" class="text-xl font-semibold text-slate-800 tracking-tight mb-1">Loading...</h1>
                <p class="text-xs text-slate-400 mt-0.5 font-medium">
                    Assigned: <span id="header-assigned-date">...</span> &bull; Due: <span id="header-due-date">...</span>
                </p>
            </div>

            <button id="send-reminder-btn" onclick="sendManualReminder()"
                class="hidden px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-orange-500/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2 self-start md:self-end">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.02 6.02 0 00-4.902-5.903m-2.8-.2L7 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                Send Reminder
            </button>
        </div>

        <!-- Progress Overview -->
        <div class="bg-white rounded-xl p-6 mb-4 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[15px] font-medium text-slate-400"><span id="progress-submitted-count"
                        class="text-[#ff6c00] font-bold">0</span> / <span id="progress-total-count">0</span> Assignments
                    Submitted</p>
                <p id="progress-percentage" class="text-[13px] font-bold text-[#ff6c00] tracking-wider">0% COMPLETE</p>
            </div>

            <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden flex mb-4">
                <div id="bar-submitted" class="h-full bg-[#ff6c00] transition-all duration-1000" style="width: 0%"></div>
                <div id="bar-pending" class="h-full bg-orange-300 transition-all duration-1000" style="width: 0%"></div>
            </div>

            <div class="flex flex-wrap items-center gap-2 text-[10px] font-bold uppercase tracking-widest">
                <button onclick="filterByStatus('all')" id="btn-all"
                    class="px-3 py-1.5 bg-[#ff6c00] text-white rounded-xl transition-all shadow-md shadow-orange-500/20 flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-white/60"></span> ALL (<span id="legend-all">0</span>)
                </button>
                <button onclick="filterByStatus('submitted')" id="btn-submitted"
                    class="px-3 py-1.5 bg-white border border-slate-100 text-slate-400 rounded-xl hover:bg-slate-50 hover:text-slate-600 transition-all flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#ff6c00]"></span> SUBMITTED (<span
                        id="legend-submitted">0</span>)
                </button>
                <button onclick="filterByStatus('pending')" id="btn-pending"
                    class="px-3 py-1.5 bg-white border border-slate-100 text-slate-400 rounded-xl hover:bg-slate-50 hover:text-slate-600 transition-all flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-orange-300"></span> PENDING (<span
                        id="legend-pending">0</span>)
                </button>
            </div>
        </div>

        <!-- Student Grid -->
        <div id="student-grid" class="flex flex-wrap gap-3">
            <!-- Loading -->
            <div class="w-full py-20 text-center">
                <div class="inline-block h-10 w-10 border-4 border-slate-100 border-t-[#ff6c00] rounded-full animate-spin">
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Bar -->
    <div class="fixed bottom-10 right-10 z-[100] flex items-center gap-4 bg-white p-2.5 pl-6 rounded-2xl shadow-2xl shadow-slate-200/50 border border-slate-100 opacity-0 translate-y-4 transition-all duration-300"
        id="action-bar">
        <div class="text-right">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Auto-saving...</p>
            <p class="text-[11px] font-medium text-[#ff6c00] italic">Last updated just now</p>
        </div>
        <button onclick="publishGrades()" id="publish-btn"
            class="px-6 py-3 bg-[#ff6c00] text-white rounded-xl text-sm font-bold shadow-lg shadow-orange-500/25 hover:bg-[#e66100] active:scale-[0.98] transition-all">
            Publish Grades
        </button>
    </div>

    <script>
        const BATCH_ID = "{{ $id }}";
        const HOMEWORK_ID = "{{ $homework_id }}";
        const API_URL = `/api/v1/institute/homeworks/${HOMEWORK_ID}`;
        const API_GRADES_URL = `/api/v1/institute/homeworks/${HOMEWORK_ID}/grades`;
        const CSRF_TOKEN = "{{ csrf_token() }}";

        let homeworkData = null;
        let students = [];
        let submissionsMap = {}; // student_id -> submission object
        let currentFilter = 'all';

        document.addEventListener('DOMContentLoaded', async () => {
            await fetchHomeworkData();
        });

        async function fetchHomeworkData() {
            try {
                const response = await fetch(API_URL, { headers: { 'Accept': 'application/json' } });
                const result = await response.json();
                if (result.status === 'success') {
                    homeworkData = result.data;
                    students = homeworkData.batch.students || [];

                    // Map submissions
                    homeworkData.submissions.forEach(sub => {
                        submissionsMap[sub.student_id] = sub;
                    });

                    // Set defaults for missing
                    const dueDate = new Date(homeworkData.due_date);
                    const isPastDue = new Date() > dueDate;

                    // Toggle Send Reminder button only if 3 or fewer days are left AND it's not closed
                    const today = new Date();
                    dueDate.setHours(0, 0, 0, 0);
                    today.setHours(0, 0, 0, 0);
                    const diffTime = dueDate - today;
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    const reminderBtn = document.getElementById('send-reminder-btn');
                    if (reminderBtn) {
                        if (diffDays >= 0 && diffDays <= 3 && !homeworkData.is_closed) {
                            reminderBtn.classList.remove('hidden');
                        } else {
                            reminderBtn.classList.add('hidden');
                        }
                    }

                    students.forEach(student => {
                        if (!submissionsMap[student.id]) {
                            submissionsMap[student.id] = {
                                student_id: student.id,
                                status: 'Pending',
                                score: 0
                            };
                        } else {
                            // Standardize incoming status
                            const s = submissionsMap[student.id].status.toLowerCase();
                            if (s === 'submitted') submissionsMap[student.id].status = 'Submitted';
                            else if (s === 'pending') submissionsMap[student.id].status = 'Pending';
                            else if (s === 'missing') submissionsMap[student.id].status = 'Pending';
                            else if (s === 'late') submissionsMap[student.id].status = 'Late';

                            if (submissionsMap[student.id].score === null) {
                                submissionsMap[student.id].score = 0;
                            }
                        }
                    });

                    renderUI();
                }
            } catch (error) {
                showToast('Failed to load homework details', 'error');
            }
        }

        async function sendManualReminder() {
            const btn = document.getElementById('send-reminder-btn');
            btn.disabled = true;
            const originalText = btn.innerHTML;
            btn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg> Sending...`;

            try {
                const response = await fetch(`/api/v1/institute/homeworks/${HOMEWORK_ID}/reminder`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                });
                const result = await response.json();
                if (result.status === 'success') {
                    showToast(result.message || 'Reminders sent successfully!');
                } else {
                    showToast(result.message || 'Failed to send reminders', 'error');
                }
            } catch (error) {
                showToast('Connection error', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }

        function updateScore(studentId, change) {
            const sub = submissionsMap[studentId];
            if (sub.status === 'Missing') return;
            if (homeworkData.is_closed) return;

            let score = parseInt(sub.score) || 0;
            score += change;
            if (score < 0) score = 0;
            if (score > 10) score = 10;

            sub.score = score;
            if (sub.status === 'Pending') sub.status = 'Submitted';

            document.getElementById(`score-${studentId}`).innerText = score;
            showActionBar();
        }

        function updateProgressCounts() {
            let submittedCount = 0, pendingCount = 0;
            const total = students.length;
            students.forEach(s => {
                const st = (submissionsMap[s.id].status || '').toLowerCase();
                if (st === 'submitted' || st === 'late') submittedCount++;
                else pendingCount++;
            });
            document.getElementById('progress-submitted-count').innerText = submittedCount;
            document.getElementById('legend-submitted').innerText = submittedCount;
            document.getElementById('legend-pending').innerText = pendingCount;
            const subPct = total ? (submittedCount / total) * 100 : 0;
            const penPct = total ? (pendingCount / total) * 100 : 0;
            document.getElementById('progress-percentage').innerText = subPct.toFixed(1) + '% COMPLETE';
            document.getElementById('bar-submitted').style.width = subPct + '%';
            document.getElementById('bar-pending').style.width = penPct + '%';
        }

        function showActionBar() {
            const bar = document.getElementById('action-bar');
            bar.classList.remove('opacity-0', 'translate-y-4');
        }

        async function publishGrades() {
            const btn = document.getElementById('publish-btn');
            btn.disabled = true;
            btn.innerText = 'Publishing...';

            const grades = Object.values(submissionsMap).map(sub => ({
                student_id: sub.student_id,
                score: sub.score,
                status: sub.status
            }));

            try {
                const response = await fetch(API_GRADES_URL, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify({ grades })
                });
                const result = await response.json();
                if (result.status === 'success') {
                    showToast('Grades published successfully!');
                    // Update all badges and counts AFTER successful publish
                    updateAllBadges();
                    updateProgressCounts();
                    setTimeout(() => {
                        document.getElementById('action-bar').classList.add('opacity-0', 'translate-y-4');
                    }, 2000);
                } else {
                    showToast(result.message || 'Failed to publish grades', 'error');
                }
            } catch (error) {
                showToast('Connection error', 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Publish Grades';
            }
        }

        function updateAllBadges() {
            students.forEach(student => {
                const sub = submissionsMap[student.id];
                if (!sub) return;
                const badgeEl = document.getElementById(`badge-${student.id}`);
                if (!badgeEl) return;
                const status = (sub.status || '').toLowerCase();
                if (status === 'submitted') {
                    badgeEl.className = 'px-2.5 py-1 bg-orange-50 text-[#ff6c00] border border-orange-200/60 rounded-full text-[9px] font-bold uppercase tracking-widest';
                    badgeEl.innerText = 'Submitted';
                } else if (status === 'late') {
                    badgeEl.className = 'px-2.5 py-1 bg-amber-50 text-amber-600 border border-amber-200/60 rounded-full text-[9px] font-bold uppercase tracking-widest';
                    badgeEl.innerText = 'Late';
                } else {
                    badgeEl.className = 'px-2.5 py-1 bg-slate-100 text-slate-500 rounded-full text-[9px] font-bold uppercase tracking-widest';
                    badgeEl.innerText = 'Pending';
                }
            });
        }

        function filterByStatus(status) {
            currentFilter = status;
            renderUI();

            // Update button styles
            const btns = {
                all: document.getElementById('btn-all'),
                submitted: document.getElementById('btn-submitted'),
                pending: document.getElementById('btn-pending')
            };

            Object.keys(btns).forEach(key => {
                const btn = btns[key];
                if (!btn) return;
                if (key === status) {
                    btn.className = "px-3 py-1.5 bg-[#ff6c00] text-white rounded-xl transition-all shadow-md shadow-orange-500/20 flex items-center gap-1.5";
                } else {
                    btn.className = "px-3 py-1.5 bg-white border border-slate-100 text-slate-400 rounded-xl hover:bg-slate-50 hover:text-slate-600 transition-all flex items-center gap-1.5";
                }
            });
        }

        function renderUI() {
            // Header
            document.getElementById('header-batch-name').innerText = homeworkData.batch.name;
            document.getElementById('header-homework-title').innerText = homeworkData.title;

            const assigned = new Date(homeworkData.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            const due = new Date(homeworkData.due_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            document.getElementById('header-assigned-date').innerText = assigned;
            document.getElementById('header-due-date').innerText = due;

            // Progress
            let submittedCount = 0;
            let pendingCount = 0;
            const total = students.length;

            students.forEach(student => {
                const status = (submissionsMap[student.id].status || '').toLowerCase();
                if (status === 'submitted' || status === 'late') submittedCount++;
                else pendingCount++;
            });

            document.getElementById('progress-submitted-count').innerText = submittedCount;
            document.getElementById('progress-total-count').innerText = total;
            document.getElementById('legend-all').innerText = total;
            document.getElementById('legend-submitted').innerText = submittedCount;
            document.getElementById('legend-pending').innerText = pendingCount;

            // Filter students for grid
            let filteredStudents = students;
            if (currentFilter === 'submitted') {
                filteredStudents = students.filter(s => {
                    const status = (submissionsMap[s.id].status || '').toLowerCase();
                    return status === 'submitted' || status === 'late';
                });
            } else if (currentFilter === 'pending') {
                filteredStudents = students.filter(s => (submissionsMap[s.id].status || '').toLowerCase() === 'pending');
            }

            const subPct = total ? (submittedCount / total) * 100 : 0;
            const penPct = total ? (pendingCount / total) * 100 : 0;

            document.getElementById('progress-percentage').innerText = subPct.toFixed(1) + '% COMPLETE';
            document.getElementById('bar-submitted').style.width = subPct + '%';
            document.getElementById('bar-pending').style.width = penPct + '%';

            // Grid
            const container = document.getElementById('student-grid');
            if (filteredStudents.length === 0) {
                container.innerHTML = `<div class="col-span-full py-20 text-center w-full"><p class="text-slate-400 font-bold uppercase tracking-widest text-xs">No students match this status.</p></div>`;
                return;
            }

            container.innerHTML = filteredStudents.map(student => {
                const sub = submissionsMap[student.id];
                const isClosed = homeworkData.is_closed;

                const status = (sub.status || '').toLowerCase();
                let statusBadge = '';
                if (status === 'submitted') statusBadge = `<span id="badge-${student.id}" class="px-2.5 py-1 bg-orange-50 text-[#ff6c00] border border-orange-200/60 rounded-full text-[9px] font-bold uppercase tracking-widest">Submitted</span>`;
                else if (status === 'late') statusBadge = `<span id="badge-${student.id}" class="px-2.5 py-1 bg-amber-50 text-amber-600 border border-amber-200/60 rounded-full text-[9px] font-bold uppercase tracking-widest">Late</span>`;
                else statusBadge = `<span id="badge-${student.id}" class="px-2.5 py-1 bg-slate-100 text-slate-500 rounded-full text-[9px] font-bold uppercase tracking-widest">Pending</span>`;

                const scoreControls = isClosed
                    ? `<div class="flex items-center justify-between px-1">
                                <span class="text-slate-300 font-medium text-xs">-</span>
                                <span class="text-slate-400 font-bold text-[14px] w-10 text-center">${sub.score}</span>
                                <span class="text-slate-300 font-medium text-xs">+</span>
                           </div>`
                    : `<div class="bg-slate-50 rounded-lg p-1 flex items-center justify-between border border-slate-100">
                                <button onclick="updateScore(${student.id}, -1)" class="h-6 w-6 rounded bg-white shadow-sm flex items-center justify-center text-rose-500 font-black hover:bg-slate-100 transition-colors">-</button>
                                <span id="score-${student.id}" class="text-[14px] font-bold text-slate-900 w-10 text-center">${sub.score}</span>
                                <button onclick="updateScore(${student.id}, 1)" class="h-6 w-6 rounded bg-white shadow-sm flex items-center justify-center text-[#ff6c00] font-black hover:bg-slate-100 transition-colors">+</button>
                           </div>`;

                return `
                            <div class="bg-white rounded-xl p-3 border border-slate-100 shadow-sm flex flex-col h-full relative max-w-[230px] w-full hover:shadow-md hover:border-orange-200 transition-all">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="h-8 w-8 rounded-full bg-slate-800 flex items-center justify-center shrink-0 overflow-hidden shadow-sm">
                                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(student.name)}&background=1e293b&color=fff&bold=true" class="w-full h-full object-cover">
                                    </div>
                                    ${statusBadge}
                                </div>

                                <h4 class="text-[13px] font-bold text-slate-900 mb-0.5 truncate">${student.name}</h4>
                                <p class="text-[10px] font-medium text-slate-400 mb-3 truncate">ID: #ST-${student.id.toString().padStart(4, '0')}</p>

                                <div class="mt-auto">
                                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Assignment Score</p>
                                    ${scoreControls}
                                </div>
                            </div>
                        `;
            }).join('');
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `flex items-center gap-3 px-6 py-4 rounded-2xl shadow-2xl animate-in slide-in-from-right-10 duration-500 ${type === 'success' ? 'bg-slate-900 text-white' : 'bg-rose-600 text-white'}`;
            toast.innerHTML = `
                        <div class="h-6 w-6 rounded-full flex items-center justify-center ${type === 'success' ? 'bg-[#ff6c00]' : 'bg-rose-400'}">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="${type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}"/></svg>
                        </div>
                        <p class="text-sm font-bold">${message}</p>`;
            container.appendChild(toast);
            setTimeout(() => { toast.classList.add('animate-out', 'fade-out', 'slide-out-to-right-10'); setTimeout(() => toast.remove(), 500); }, 3000);
        }
    </script>
@endsection