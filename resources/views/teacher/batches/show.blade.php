@extends('layouts.teacher')

@section('title', $batch->name)

@section('content')
<div class="mb-6 flex items-center justify-between flex-wrap gap-3">
    <div>
        <a href="{{ route('teacher.dashboard') }}" class="text-xs font-bold text-slate-400 hover:text-primary">&larr; Back to My Batches</a>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight mt-1">{{ $batch->name }}</h1>
        <p class="text-sm text-slate-500">{{ $batch->subject ?: 'General' }} &middot; {{ $batch->students_count }} students</p>
    </div>
</div>

<div class="border-b border-slate-200 mb-6 overflow-x-auto">
    <div class="flex gap-1 min-w-max" id="tab-nav">
        <button data-tab="students" class="tab-btn px-4 py-2.5 text-xs font-bold rounded-t-lg border-b-2 border-primary text-primary">Students</button>
        <button data-tab="attendance" class="tab-btn px-4 py-2.5 text-xs font-bold rounded-t-lg border-b-2 border-transparent text-slate-500 hover:text-slate-800">Attendance</button>
        <button data-tab="homework" class="tab-btn px-4 py-2.5 text-xs font-bold rounded-t-lg border-b-2 border-transparent text-slate-500 hover:text-slate-800">Homework</button>
        <button data-tab="exams" class="tab-btn px-4 py-2.5 text-xs font-bold rounded-t-lg border-b-2 border-transparent text-slate-500 hover:text-slate-800">Exams</button>
        <button data-tab="timetable" class="tab-btn px-4 py-2.5 text-xs font-bold rounded-t-lg border-b-2 border-transparent text-slate-500 hover:text-slate-800">Timetable</button>
        @if($batch->teacher_can_view_fees)
            <button data-tab="fees" class="tab-btn px-4 py-2.5 text-xs font-bold rounded-t-lg border-b-2 border-transparent text-slate-500 hover:text-slate-800">Fees</button>
        @endif
    </div>
</div>

<!-- STUDENTS TAB -->
<div id="tab-students" class="tab-panel">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-black text-slate-800">Batch Students</h2>
        <button onclick="openStudentModal()" class="px-4 py-2 bg-primary text-white rounded-lg text-xs font-bold hover:bg-orange-700">+ Add Student</button>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-[10px] uppercase tracking-wide text-slate-500 font-black">
                <tr><th class="text-left p-3">Name</th><th class="text-left p-3">Enrollment ID</th><th class="text-right p-3">Actions</th></tr>
            </thead>
            <tbody id="students-tbody"></tbody>
        </table>
    </div>
</div>

<!-- ATTENDANCE TAB -->
<div id="tab-attendance" class="tab-panel hidden">
    <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
        <h2 class="font-black text-slate-800">Student Attendance</h2>
        <div class="flex items-center gap-2">
            <input type="date" id="attendance-date" value="{{ now()->toDateString() }}" class="h-10 px-3 rounded-lg border-2 border-slate-100 text-xs font-semibold">
            <button onclick="saveAttendance()" class="px-4 py-2 bg-primary text-white rounded-lg text-xs font-bold hover:bg-orange-700">Save Attendance</button>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-[10px] uppercase tracking-wide text-slate-500 font-black">
                <tr><th class="text-left p-3">Name</th><th class="text-left p-3">Status</th></tr>
            </thead>
            <tbody id="attendance-tbody"></tbody>
        </table>
    </div>
</div>

<!-- HOMEWORK TAB -->
<div id="tab-homework" class="tab-panel hidden">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-black text-slate-800">Homework</h2>
        <button onclick="openHomeworkModal()" class="px-4 py-2 bg-primary text-white rounded-lg text-xs font-bold hover:bg-orange-700">+ Assign Homework</button>
    </div>
    <div id="homework-list" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
</div>

<!-- EXAMS TAB -->
<div id="tab-exams" class="tab-panel hidden">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-black text-slate-800">Exams</h2>
        <button onclick="openExamModal()" class="px-4 py-2 bg-primary text-white rounded-lg text-xs font-bold hover:bg-orange-700">+ Create Exam</button>
    </div>
    <div id="exams-list" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
</div>

<!-- TIMETABLE TAB -->
<div id="tab-timetable" class="tab-panel hidden">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-black text-slate-800">Timetable</h2>
        <button onclick="openTimetableModal()" class="px-4 py-2 bg-primary text-white rounded-lg text-xs font-bold hover:bg-orange-700">+ Add Slot</button>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-[10px] uppercase tracking-wide text-slate-500 font-black">
                <tr><th class="text-left p-3">Day</th><th class="text-left p-3">Time</th><th class="text-left p-3">Room</th><th class="text-right p-3">Actions</th></tr>
            </thead>
            <tbody id="timetable-tbody"></tbody>
        </table>
    </div>
</div>

@if($batch->teacher_can_view_fees)
<!-- FEES TAB -->
<div id="tab-fees" class="tab-panel hidden">
    <h2 class="font-black text-slate-800 mb-4">Fee Records (Read-only)</h2>
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-[10px] uppercase tracking-wide text-slate-500 font-black">
                <tr><th class="text-left p-3">Student</th><th class="text-left p-3">Total</th><th class="text-left p-3">Paid</th><th class="text-left p-3">Status</th></tr>
            </thead>
            <tbody id="fees-tbody"></tbody>
        </table>
    </div>
</div>
@endif

<!-- Add Student Modal -->
<div id="student-modal" class="hidden fixed inset-0 z-[200] bg-slate-900/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full">
        <h3 class="font-black text-lg mb-4">Add Student to Batch</h3>
        <form id="student-form" class="space-y-3">
            <input required name="name" placeholder="Full Name" class="w-full h-11 px-3 rounded-lg border-2 border-slate-100 text-sm">
            <input required type="email" name="email" placeholder="Email" class="w-full h-11 px-3 rounded-lg border-2 border-slate-100 text-sm">
            <input required name="phone" placeholder="Phone (10 digits)" class="w-full h-11 px-3 rounded-lg border-2 border-slate-100 text-sm">
            <input required name="standard" placeholder="Standard / Grade" class="w-full h-11 px-3 rounded-lg border-2 border-slate-100 text-sm">
            <input required type="date" name="dob" placeholder="Date of Birth" class="w-full h-11 px-3 rounded-lg border-2 border-slate-100 text-sm">
            <input required name="guardian_name" placeholder="Guardian Name" class="w-full h-11 px-3 rounded-lg border-2 border-slate-100 text-sm">
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeModal('student-modal')" class="flex-1 h-11 rounded-lg border-2 border-slate-100 text-xs font-bold">Cancel</button>
                <button type="submit" class="flex-1 h-11 bg-primary text-white rounded-lg text-xs font-bold">Add Student</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Homework Modal -->
<div id="homework-modal" class="hidden fixed inset-0 z-[200] bg-slate-900/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full">
        <h3 class="font-black text-lg mb-4">Assign Homework</h3>
        <form id="homework-form" class="space-y-3">
            <input required name="title" placeholder="Title" class="w-full h-11 px-3 rounded-lg border-2 border-slate-100 text-sm">
            <textarea required name="description" placeholder="Description" rows="3" class="w-full px-3 py-2 rounded-lg border-2 border-slate-100 text-sm"></textarea>
            <input required type="date" name="due_date" class="w-full h-11 px-3 rounded-lg border-2 border-slate-100 text-sm">
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeModal('homework-modal')" class="flex-1 h-11 rounded-lg border-2 border-slate-100 text-xs font-bold">Cancel</button>
                <button type="submit" class="flex-1 h-11 bg-primary text-white rounded-lg text-xs font-bold">Assign</button>
            </div>
        </form>
    </div>
</div>

<!-- Create Exam Modal -->
<div id="exam-modal" class="hidden fixed inset-0 z-[200] bg-slate-900/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full">
        <h3 class="font-black text-lg mb-4">Create Exam</h3>
        <form id="exam-form" class="space-y-3">
            <input required name="title" placeholder="Exam Title" class="w-full h-11 px-3 rounded-lg border-2 border-slate-100 text-sm">
            <input name="subject" placeholder="Subject" class="w-full h-11 px-3 rounded-lg border-2 border-slate-100 text-sm">
            <input required type="date" name="exam_date" class="w-full h-11 px-3 rounded-lg border-2 border-slate-100 text-sm">
            <div class="grid grid-cols-2 gap-3">
                <input required type="number" name="total_marks" placeholder="Total Marks" class="h-11 px-3 rounded-lg border-2 border-slate-100 text-sm">
                <input required type="number" name="passing_marks" placeholder="Passing Marks" class="h-11 px-3 rounded-lg border-2 border-slate-100 text-sm">
            </div>
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeModal('exam-modal')" class="flex-1 h-11 rounded-lg border-2 border-slate-100 text-xs font-bold">Cancel</button>
                <button type="submit" class="flex-1 h-11 bg-primary text-white rounded-lg text-xs font-bold">Create</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Timetable Slot Modal -->
<div id="timetable-modal" class="hidden fixed inset-0 z-[200] bg-slate-900/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full">
        <h3 class="font-black text-lg mb-4">Add Timetable Slot</h3>
        <form id="timetable-form" class="space-y-3">
            <input required name="subject" placeholder="Subject" class="w-full h-11 px-3 rounded-lg border-2 border-slate-100 text-sm">
            <select required name="day_of_week" class="w-full h-11 px-3 rounded-lg border-2 border-slate-100 text-sm">
                @foreach(['monday','tuesday','wednesday','thursday','friday','saturday','sunday'] as $day)
                    <option value="{{ $day }}">{{ ucfirst($day) }}</option>
                @endforeach
            </select>
            <div class="grid grid-cols-2 gap-3">
                <input required type="time" name="start_time" class="h-11 px-3 rounded-lg border-2 border-slate-100 text-sm">
                <input required type="time" name="end_time" class="h-11 px-3 rounded-lg border-2 border-slate-100 text-sm">
            </div>
            <input name="room_no" placeholder="Room No." class="w-full h-11 px-3 rounded-lg border-2 border-slate-100 text-sm">
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeModal('timetable-modal')" class="flex-1 h-11 rounded-lg border-2 border-slate-100 text-xs font-bold">Cancel</button>
                <button type="submit" class="flex-1 h-11 bg-primary text-white rounded-lg text-xs font-bold">Add Slot</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const BATCH_ID = {{ $batch->id }};
    const API = '/api/v1/teacher';

    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
    function openStudentModal() { openModal('student-modal'); }
    function openHomeworkModal() { openModal('homework-modal'); }
    function openExamModal() { openModal('exam-modal'); }
    function openTimetableModal() { openModal('timetable-modal'); }

    // ---- Tabs ----
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('border-primary', 'text-primary'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.add('border-transparent', 'text-slate-500'));
            btn.classList.add('border-primary', 'text-primary');
            btn.classList.remove('border-transparent', 'text-slate-500');

            document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
            document.getElementById('tab-' + btn.dataset.tab).classList.remove('hidden');

            loadTab(btn.dataset.tab);
        });
    });

    function loadTab(tab) {
        if (tab === 'students') loadStudents();
        if (tab === 'attendance') loadAttendance();
        if (tab === 'homework') loadHomework();
        if (tab === 'exams') loadExams();
        if (tab === 'timetable') loadTimetable();
        if (tab === 'fees') loadFees();
    }

    // ---- Students ----
    async function loadStudents() {
        const tbody = document.getElementById('students-tbody');
        tbody.innerHTML = '<tr><td class="p-3 text-slate-400 text-xs" colspan="3">Loading...</td></tr>';
        try {
            const res = await apiFetch(`${API}/batches/${BATCH_ID}/students`);
            const students = res.data || [];
            tbody.innerHTML = students.length ? students.map(s => `
                <tr class="border-t border-slate-50">
                    <td class="p-3 font-semibold">${s.name}</td>
                    <td class="p-3 text-slate-500">${s.enrollment_id ?? '-'}</td>
                    <td class="p-3 text-right">
                        <button onclick="removeStudent(${s.id})" class="text-rose-500 text-xs font-bold hover:underline">Remove</button>
                    </td>
                </tr>`).join('') : '<tr><td class="p-3 text-slate-400 text-xs" colspan="3">No students in this batch yet.</td></tr>';
        } catch (e) { showToast('Failed to load students', 'error'); }
    }

    document.getElementById('student-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const fd = new FormData(e.target);
        const body = Object.fromEntries(fd.entries());
        try {
            await apiFetch(`${API}/batches/${BATCH_ID}/students`, { method: 'POST', body: JSON.stringify(body) });
            showToast('Student added successfully');
            closeModal('student-modal');
            e.target.reset();
            loadStudents();
        } catch (err) { showToast(err.message || 'Failed to add student', 'error'); }
    });

    async function removeStudent(studentId) {
        if (!confirm('Remove this student from the batch?')) return;
        try {
            await apiFetch(`${API}/batches/${BATCH_ID}/students/${studentId}/remove`, { method: 'POST' });
            showToast('Student removed from batch');
            loadStudents();
        } catch (err) { showToast('Failed to remove student', 'error'); }
    }

    // ---- Attendance ----
    async function loadAttendance() {
        const date = document.getElementById('attendance-date').value;
        const tbody = document.getElementById('attendance-tbody');
        tbody.innerHTML = '<tr><td class="p-3 text-slate-400 text-xs" colspan="2">Loading...</td></tr>';
        try {
            const res = await apiFetch(`${API}/attendance?batch_id=${BATCH_ID}&date=${date}`);
            const rows = res.data || [];
            tbody.innerHTML = rows.length ? rows.map(r => `
                <tr class="border-t border-slate-50" data-student="${r.student_id}">
                    <td class="p-3 font-semibold">${r.student_name}</td>
                    <td class="p-3">
                        <select class="attendance-status h-9 px-2 rounded-lg border-2 border-slate-100 text-xs font-semibold">
                            <option value="present" ${r.status === 'present' ? 'selected' : ''}>Present</option>
                            <option value="absent" ${r.status === 'absent' ? 'selected' : ''}>Absent</option>
                            <option value="late" ${r.status === 'late' ? 'selected' : ''}>Late</option>
                        </select>
                    </td>
                </tr>`).join('') : '<tr><td class="p-3 text-slate-400 text-xs" colspan="2">No students in this batch.</td></tr>';
        } catch (e) { showToast('Failed to load attendance', 'error'); }
    }
    document.getElementById('attendance-date').addEventListener('change', loadAttendance);

    async function saveAttendance() {
        const date = document.getElementById('attendance-date').value;
        const rows = document.querySelectorAll('#attendance-tbody tr[data-student]');
        const attendance = Array.from(rows).map(row => ({
            student_id: row.dataset.student,
            status: row.querySelector('.attendance-status').value,
        }));
        try {
            await apiFetch(`${API}/attendance`, { method: 'POST', body: JSON.stringify({ batch_id: BATCH_ID, date, attendance }) });
            showToast('Attendance saved successfully');
        } catch (e) { showToast('Failed to save attendance', 'error'); }
    }

    // ---- Homework ----
    async function loadHomework() {
        const list = document.getElementById('homework-list');
        list.innerHTML = '<p class="text-xs text-slate-400">Loading...</p>';
        try {
            const res = await apiFetch(`${API}/homeworks?batch_id=${BATCH_ID}`);
            const items = res.data?.data || [];
            list.innerHTML = items.length ? items.map(h => `
                <div class="bg-white rounded-2xl border border-slate-100 p-4">
                    <div class="flex items-start justify-between">
                        <h4 class="font-black text-sm text-slate-800">${h.title}</h4>
                        <button onclick="deleteHomework(${h.id})" class="text-rose-500 text-[10px] font-bold hover:underline">Delete</button>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">${h.description}</p>
                    <p class="text-[10px] font-bold text-slate-400 mt-2">Due: ${h.due_date}</p>
                </div>`).join('') : '<p class="text-xs text-slate-400">No homework assigned yet.</p>';
        } catch (e) { showToast('Failed to load homework', 'error'); }
    }

    document.getElementById('homework-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const fd = new FormData(e.target);
        const body = Object.fromEntries(fd.entries());
        body.batch_id = BATCH_ID;
        try {
            await apiFetch(`${API}/homeworks`, { method: 'POST', body: JSON.stringify(body) });
            showToast('Homework assigned successfully');
            closeModal('homework-modal');
            e.target.reset();
            loadHomework();
        } catch (err) { showToast(err.message || 'Failed to assign homework', 'error'); }
    });

    async function deleteHomework(id) {
        if (!confirm('Delete this homework?')) return;
        try {
            await apiFetch(`${API}/homeworks/${id}`, { method: 'DELETE' });
            showToast('Homework deleted');
            loadHomework();
        } catch (e) { showToast('Failed to delete', 'error'); }
    }

    // ---- Exams ----
    async function loadExams() {
        const list = document.getElementById('exams-list');
        list.innerHTML = '<p class="text-xs text-slate-400">Loading...</p>';
        try {
            const res = await apiFetch(`${API}/exams?batch_id=${BATCH_ID}`);
            const items = res.data?.data || [];
            list.innerHTML = items.length ? items.map(ex => `
                <div class="bg-white rounded-2xl border border-slate-100 p-4">
                    <div class="flex items-start justify-between">
                        <h4 class="font-black text-sm text-slate-800">${ex.title}</h4>
                        <button onclick="deleteExam(${ex.id})" class="text-rose-500 text-[10px] font-bold hover:underline">Delete</button>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">${ex.subject ?? ''} &middot; ${ex.exam_date}</p>
                    <p class="text-[10px] font-bold text-slate-400 mt-2">Total: ${ex.total_marks} &middot; Passing: ${ex.passing_marks} &middot; ${ex.status}</p>
                </div>`).join('') : '<p class="text-xs text-slate-400">No exams created yet.</p>';
        } catch (e) { showToast('Failed to load exams', 'error'); }
    }

    document.getElementById('exam-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const fd = new FormData(e.target);
        const body = Object.fromEntries(fd.entries());
        body.batch_id = BATCH_ID;
        try {
            await apiFetch(`${API}/exams`, { method: 'POST', body: JSON.stringify(body) });
            showToast('Exam created successfully');
            closeModal('exam-modal');
            e.target.reset();
            loadExams();
        } catch (err) { showToast(err.message || 'Failed to create exam', 'error'); }
    });

    async function deleteExam(id) {
        if (!confirm('Delete this exam?')) return;
        try {
            await apiFetch(`${API}/exams/${id}`, { method: 'DELETE' });
            showToast('Exam deleted');
            loadExams();
        } catch (e) { showToast('Failed to delete', 'error'); }
    }

    // ---- Timetable ----
    async function loadTimetable() {
        const tbody = document.getElementById('timetable-tbody');
        tbody.innerHTML = '<tr><td class="p-3 text-slate-400 text-xs" colspan="4">Loading...</td></tr>';
        try {
            const res = await apiFetch(`${API}/timetable?batch_id=${BATCH_ID}`);
            const items = res.data || [];
            tbody.innerHTML = items.length ? items.map(t => `
                <tr class="border-t border-slate-50">
                    <td class="p-3 font-semibold capitalize">${t.day_of_week}</td>
                    <td class="p-3 text-slate-500">${t.time_slot}</td>
                    <td class="p-3 text-slate-500">${t.room_no ?? '-'}</td>
                    <td class="p-3 text-right"><button onclick="deleteTimetable(${t.id})" class="text-rose-500 text-xs font-bold hover:underline">Delete</button></td>
                </tr>`).join('') : '<tr><td class="p-3 text-slate-400 text-xs" colspan="4">No slots scheduled.</td></tr>';
        } catch (e) { showToast('Failed to load timetable', 'error'); }
    }

    document.getElementById('timetable-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const fd = new FormData(e.target);
        const body = Object.fromEntries(fd.entries());
        body.batch_id = BATCH_ID;
        try {
            await apiFetch(`${API}/timetable`, { method: 'POST', body: JSON.stringify(body) });
            showToast('Slot added successfully');
            closeModal('timetable-modal');
            e.target.reset();
            loadTimetable();
        } catch (err) { showToast(err.message || 'Failed to add slot', 'error'); }
    });

    async function deleteTimetable(id) {
        if (!confirm('Delete this slot?')) return;
        try {
            await apiFetch(`${API}/timetable/${id}`, { method: 'DELETE' });
            showToast('Slot deleted');
            loadTimetable();
        } catch (e) { showToast('Failed to delete', 'error'); }
    }

    // ---- Fees ----
    async function loadFees() {
        const tbody = document.getElementById('fees-tbody');
        if (!tbody) return;
        tbody.innerHTML = '<tr><td class="p-3 text-slate-400 text-xs" colspan="4">Loading...</td></tr>';
        try {
            const res = await apiFetch(`${API}/fees?batch_id=${BATCH_ID}`);
            const items = res.data || [];
            tbody.innerHTML = items.length ? items.map(f => `
                <tr class="border-t border-slate-50">
                    <td class="p-3 font-semibold">${f.student?.name ?? '-'}</td>
                    <td class="p-3 text-slate-500">₹${f.total_amount ?? 0}</td>
                    <td class="p-3 text-slate-500">₹${f.paid_amount ?? 0}</td>
                    <td class="p-3 text-slate-500">${f.status ?? '-'}</td>
                </tr>`).join('') : '<tr><td class="p-3 text-slate-400 text-xs" colspan="4">No fee records found.</td></tr>';
        } catch (e) { showToast('Failed to load fees', 'error'); }
    }

    // Initial load
    loadStudents();
</script>
@endpush
@endsection
