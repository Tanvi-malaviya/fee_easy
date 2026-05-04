@extends('layouts.institute')
@section('content')
    <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

    <div class="max-w-7xl mx-auto pt-2 pb-24 px-4 sm:px-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ">
            <a href="{{ route('institute.batches.index') }}" class="hover:text-[#ff6600] transition-colors">Batches</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
            <a href="{{ route('institute.batches.show', $id) }}" class="hover:text-[#ff6600] transition-colors">Batch Details</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
            <span class="text-slate-600">Attendance</span>
        </nav>

        <!-- Header & Date Picker -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
            <div>
                <h1 id="header-batch-name" class="text-2xl font-bold text-slate-900 tracking-tight">Loading Batch...</h1>
                <p id="header-batch-subject" class="text-xs font-semibold text-slate-400 mt-1">Fetching details...</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <!-- Summary Stats -->
                <div class="bg-white px-4 py-2 rounded-xl border border-slate-100 shadow-sm flex items-center gap-4">
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Present</span>
                        <span class="text-base font-black text-[#a3360a]"><span id="summary-present">0</span></span>
                    </div>
                    <div class="h-6 w-px bg-slate-100"></div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Absent</span>
                        <span class="text-base font-black text-rose-600"><span id="summary-absent">0</span></span>
                    </div>
                </div>

                <!-- Date Picker -->
                <div class="bg-white px-4 py-2 rounded-xl border border-slate-100 shadow-sm flex flex-col items-end">
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Session Date</span>
                        <input type="date" id="session-date" onchange="fetchAttendanceData()" 
                            class="border-0 p-0 text-sm font-bold text-slate-800 outline-none focus:ring-0 cursor-pointer">
                    </div>
                    <p id="last-saved" class="text-[8px] text-slate-400 italic mt-0.5">Syncing status...</p>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="bg-white rounded-xl p-3 mb-3 border border-slate-100 shadow-sm flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-lg bg-orange-50 flex items-center justify-center text-[#ff6600]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
                <div>
                    <h3 id="stat-student-count" class="text-sm font-bold text-slate-800">0 Students Enrolled</h3>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <button onclick="markAllPresent()" class="px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl border border-slate-200 transition-all flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    Mark All Present
                </button>
                <button onclick="markAllAbsent()" class="px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl border border-slate-200 transition-all flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    Mark All Absent
                </button>
                <button onclick="submitAttendance()" id="submit-btn" class="px-5 py-2 bg-[#a3360a] hover:bg-[#852b08] text-white text-xs font-bold rounded-xl shadow-md shadow-orange-700/10 transition-all flex items-center gap-1.5">
                    Submit Attendance
                </button>
            </div>
        </div>

        <!-- Student Grid -->
        <div id="student-grid" class="flex flex-wrap gap-2.5 mb-8">
            <!-- Loading -->
            <div class="w-full py-20 text-center">
                <div class="inline-block h-10 w-10 border-4 border-slate-100 border-t-orange-600 rounded-full animate-spin"></div>
            </div>
        </div>


    </div>

    <script>
        const BATCH_ID = "{{ $id }}";
        const API_BATCH_URL = `/api/v1/institute/batches/${BATCH_ID}`;
        const API_STUDENTS_URL = `/api/v1/institute/students?batch_id=${BATCH_ID}`;
        const API_ATTENDANCE_URL = `/api/v1/institute/attendance`;
        const CSRF_TOKEN = "{{ csrf_token() }}";

        let students = [];
        let attendanceMap = {}; // student_id -> status ('present', 'absent')

        document.addEventListener('DOMContentLoaded', async () => {
            // Set date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('session-date').value = today;
            document.getElementById('session-date').setAttribute('max', today);
            
            await fetchBatchData();
            await fetchAttendanceData();
        });

        async function fetchBatchData() {
            try {
                const response = await fetch(API_BATCH_URL, { headers: { 'Accept': 'application/json' } });
                const result = await response.json();
                if (result.status === 'success') {
                    document.getElementById('header-batch-name').innerText = result.data.name;
                    document.getElementById('header-batch-subject').innerText = result.data.subject || 'No Subject';
                }
            } catch (error) {
                showToast('Failed to load batch info', 'error');
            }
        }

        async function fetchAttendanceData() {
            const date = document.getElementById('session-date').value;
            const grid = document.getElementById('student-grid');
            grid.innerHTML = `<div class="w-full py-20 text-center"><div class="inline-block h-10 w-10 border-4 border-slate-100 border-t-orange-600 rounded-full animate-spin"></div></div>`;

            try {
                // Fetch Students
                const stuRes = await fetch(API_STUDENTS_URL, { headers: { 'Accept': 'application/json' } });
                const stuResult = await stuRes.json();
                if (stuResult.status === 'success') {
                    students = stuResult.data.items || [];
                    document.getElementById('stat-student-count').innerText = `${students.length} Students Enrolled`;
                }

                // Fetch Marked Attendance
                const attRes = await fetch(`${API_ATTENDANCE_URL}?date=${date}&batch_id=${BATCH_ID}`, { headers: { 'Accept': 'application/json' } });
                const attResult = await attRes.json();
                
                attendanceMap = {};
                // Default all to present if no records exist, otherwise map existing
                if (attResult.status === 'success' && attResult.data.length > 0) {
                    attResult.data.forEach(record => {
                        attendanceMap[record.student_id] = record.status;
                    });
                    document.getElementById('last-saved').innerText = `Last saved at: ${new Date(attResult.data[0].updated_at).toLocaleTimeString()}`;
                } else {
                    students.forEach(student => {
                        attendanceMap[student.id] = 'present';
                    });
                    document.getElementById('last-saved').innerText = 'New attendance sheet generated.';
                }

                renderStudents();
                updateSummary();
            } catch (error) {
                showToast('Failed to load data', 'error');
            }
        }

        function toggleStatus(studentId, status) {
            attendanceMap[studentId] = status;
            
            // Re-render only buttons or whole grid? Let's re-render buttons for speed
            const card = document.getElementById(`card-${studentId}`);
            if (card) {
                const btnPresent = card.querySelector('.btn-present');
                const btnAbsent = card.querySelector('.btn-absent');
                
                if (status === 'present') {
                    btnPresent.className = 'btn-present flex-1 py-1.5 text-xs font-bold rounded-lg transition-all bg-[#a3360a] text-white';
                    btnAbsent.className = 'btn-absent flex-1 py-1.5 text-xs font-bold rounded-lg transition-all bg-slate-50 hover:bg-slate-100 text-slate-500';
                } else {
                    btnPresent.className = 'btn-present flex-1 py-1.5 text-xs font-bold rounded-lg transition-all bg-slate-50 hover:bg-slate-100 text-slate-500';
                    btnAbsent.className = 'btn-absent flex-1 py-1.5 text-xs font-bold rounded-lg transition-all bg-rose-700 text-white';
                }
            }

            updateSummary();
        }

        function markAllPresent() {
            students.forEach(student => {
                attendanceMap[student.id] = 'present';
            });
            renderStudents();
            updateSummary();
        }

        function markAllAbsent() {
            students.forEach(student => {
                attendanceMap[student.id] = 'absent';
            });
            renderStudents();
            updateSummary();
        }

        function updateSummary() {
            let present = 0;
            let absent = 0;
            
            students.forEach(student => {
                if (attendanceMap[student.id] === 'present') present++;
                else absent++;
            });

            document.getElementById('summary-present').innerText = present;
            document.getElementById('summary-absent').innerText = absent;
        }

        async function submitAttendance() {
            const btn = document.getElementById('submit-btn');
            const date = document.getElementById('session-date').value;
            btn.disabled = true;
            btn.innerText = 'Saving...';

            const attendanceArray = Object.keys(attendanceMap).map(id => ({
                student_id: parseInt(id),
                status: attendanceMap[id]
            }));

            try {
                const response = await fetch(API_ATTENDANCE_URL, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify({ batch_id: BATCH_ID, date: date, attendance: attendanceArray })
                });
                const result = await response.json();
                if (result.status === 'success') {
                    showToast('Attendance saved successfully!');
                    document.getElementById('last-saved').innerText = `Last saved just now.`;
                } else {
                    showToast('Failed to save attendance', 'error');
                }
            } catch (error) {
                showToast('Connection error', 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Submit Attendance';
            }
        }

        function renderStudents() {
            const container = document.getElementById('student-grid');
            if (students.length === 0) {
                container.innerHTML = `<div class="w-full py-10 text-center"><p class="text-slate-400 font-bold uppercase tracking-widest text-xs">No students in this batch.</p></div>`;
                return;
            }

            container.innerHTML = students.map(student => {
                const status = attendanceMap[student.id] || 'present';
                const isPresent = status === 'present';
                
                const btnPresentClass = isPresent ? 'bg-[#a3360a] text-white' : 'bg-slate-50 hover:bg-slate-100 text-slate-500';
                const btnAbsentClass = !isPresent ? 'bg-rose-700 text-white' : 'bg-slate-50 hover:bg-slate-100 text-slate-500';

                return `
                    <div id="card-${student.id}" class="bg-white rounded-xl p-3 border border-slate-100 shadow-sm flex flex-col justify-between relative max-w-[220px] w-full min-h-[140px]">
                        <div>
                            <div class="flex items-start justify-between mb-2">
                                <div class="h-8 w-8 rounded-full bg-slate-800 flex items-center justify-center shrink-0 overflow-hidden">
                                    <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(student.name)}&background=1e293b&color=fff&bold=true" class="w-full h-full object-cover">
                                </div>
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded-md text-[8px] font-bold uppercase tracking-wider">
                                    ID: ${student.id}
                                </span>
                            </div>

                            <h4 class="text-[13px] font-bold text-slate-900 mb-0.5 truncate">${student.name}</h4>
                            <p class="text-[10px] font-medium text-slate-400 mb-3 truncate">${student.phone || 'No contact'}</p>
                        </div>

                        <div class="flex gap-1.5 mt-auto">
                            <button onclick="toggleStatus(${student.id}, 'present')" class="btn-present flex-1 py-1.5 text-xs font-bold rounded-lg transition-all ${btnPresentClass}">
                                Present
                            </button>
                            <button onclick="toggleStatus(${student.id}, 'absent')" class="btn-absent flex-1 py-1.5 text-xs font-bold rounded-lg transition-all ${btnAbsentClass}">
                                Absent
                            </button>
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
                <div class="h-6 w-6 rounded-full flex items-center justify-center ${type === 'success' ? 'bg-[#a3360a]' : 'bg-rose-400'}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="${type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}"/></svg>
                </div>
                <p class="text-sm font-bold">${message}</p>`;
            container.appendChild(toast);
            setTimeout(() => { toast.classList.add('animate-out', 'fade-out', 'slide-out-to-right-10'); setTimeout(() => toast.remove(), 500); }, 3000);
        }
    </script>
@endsection
