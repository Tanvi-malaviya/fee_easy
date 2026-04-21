@extends('layouts.institute')

@section('content')
    <div class="space-y-4 max-w-[1600px] mx-auto pb-6">
        <!-- Toast Notifications Container -->
        <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

        <!-- Page Header & Stats -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 pb-2">
            <div class="flex items-center">
                <a href="{{ route('institute.attendance.index') }}" class="h-12 w-12 bg-white border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 hover:text-blue-600 transition-all shadow-sm mr-5 group">
                    <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h1 class="text-3xl font-extrabold text-[#111827] tracking-tight">Mark Attendance</h1>
                    <!-- <p class="text-sm text-slate-500 mt-1 font-medium">Record daily scholar presence for your sessions.</p> -->
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center bg-white rounded-xl shadow-sm  p-1">
                    <input type="date" id="attendance-date" value="{{ date('Y-m-d') }}" 
                        onchange="loadAttendanceList()"
                        class="px-3 py-2 bg-transparent text-[13px] font-bold text-slate-700 outline-none">
                    <div class="h-4 w-[1px] bg-slate-100 mx-1"></div>
                    <select id="batch-selector" 
                        onchange="loadAttendanceList()"
                        class="px-3 py-2 bg-transparent text-[13px] font-bold text-slate-700 outline-none min-w-[150px] appearance-none cursor-pointer">
                        <option value="">Choose Batch...</option>
                    </select>
                </div>

                <!-- <button onclick="loadAttendanceList()" 
                    class="px-5 py-3 bg-white text-slate-500 border border-slate-100 rounded-xl font-bold text-[12px] shadow-sm hover:bg-slate-50 transition-all flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Refresh List
                </button> -->
            </div>
        </div>

        <div id="stats-container" class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                    <svg class="w-16 h-16 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                </div>
                <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Total Students</p>
                <h3 id="stat-total" class="text-3xl font-black text-slate-800 mt-2">0</h3>
                <p class="text-[11px] font-bold text-slate-400 mt-1 uppercase tracking-tight">Assigned to Batch</p>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                    <svg class="w-16 h-16 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                </div>
                <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Present</p>
                <h3 id="stat-present" class="text-3xl font-black text-emerald-600 mt-2">0</h3>
                <p id="stat-present-label" class="text-[11px] font-bold text-emerald-600/60 mt-1 uppercase tracking-tight">Scholar Presence</p>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                    <svg class="w-16 h-16 text-rose-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                </div>
                <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Absent</p>
                <h3 id="stat-absent" class="text-3xl font-black text-rose-600 mt-2">0</h3>
                <p id="stat-absent-label" class="text-[11px] font-bold text-rose-600/60 mt-1 uppercase tracking-tight">Requires Attention</p>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden relative">
            <!-- Table Toolbar -->
            <div class="p-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/20">
                <div class="flex items-center space-x-4">
                    <h3 class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Attendance Registry</h3>
                    <div id="loading-spinner" class="hidden h-4 w-4 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin"></div>
                </div>

                <div id="bulk-actions" class="hidden flex items-center space-x-2">
                    <button onclick="markAllPresent()" class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl font-extrabold text-[10px] hover:bg-emerald-100 transition-all flex items-center uppercase tracking-widest">
                        Mark All Present
                    </button>
                    <button onclick="markAllAbsent()" class="px-4 py-2 bg-rose-50 text-rose-600 rounded-xl font-extrabold text-[10px] hover:bg-rose-100 transition-all flex items-center uppercase tracking-widest">
                        Mark All Absent
                    </button>
                </div>
            </div>

            <div class="p-4 bg-slate-50/10 border-b border-slate-50 min-h-[300px]">
                <div id="attendance-cards-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3">
                    <div class="col-span-full py-16 text-center text-slate-400 font-medium italic text-[13px]">
                        Select a batch to load scholarship records.
                    </div>
                </div>
            </div>

            <!-- Action Bar -->
            <div id="action-bar" class="hidden px-8 py-6 bg-slate-50/20 flex items-center justify-end border-t border-slate-50">
                <button id="submit-btn" onclick="submitAttendance()" class="px-12 py-4 bg-[#1e3a8a] text-white rounded-2xl font-bold text-[14px] shadow-xl shadow-blue-900/10 hover:scale-[1.02] transition-transform flex items-center">
                    <span>Save Attendance Records</span>
                    <span id="btn-loader" class="hidden h-4 w-4 border-2 border-white/30 border-t-white rounded-full animate-spin ml-3"></span>
                </button>
            </div>
        </div>
    </div>

    <script>
        const CSRF_TOKEN = "{{ csrf_token() }}";

        document.addEventListener('DOMContentLoaded', () => {
            fetchBatches();
        });

        async function fetchBatches() {
            try {
                const response = await fetch("/api/v1/institute/batches", { headers: { 'Accept': 'application/json' } });
                const result = await response.json();
                if (result.status === 'success') {
                    const selector = document.getElementById('batch-selector');
                    result.data.items.forEach(batch => {
                        const opt = document.createElement('option');
                        opt.value = batch.id;
                        opt.innerText = batch.name;
                        selector.appendChild(opt);
                    });
                }
            } catch (error) { showToast('Failed to load batches', 'error'); }
        }

        async function loadAttendanceList() {
            const batchId = document.getElementById('batch-selector').value;
            const date = document.getElementById('attendance-date').value;

            if (!batchId) { showToast('Please select a batch first', 'error'); return; }

            toggleLoader(true);
            try {
                // First, get all students in the batch
                const studentResp = await fetch(`/api/v1/institute/students?batch_id=${batchId}`, { headers: { 'Accept': 'application/json' } });
                const studentResult = await studentResp.json();

                // Then, get existing attendance for this date (if any)
                const attendanceResp = await fetch(`/api/v1/institute/attendance?date=${date}&batch_id=${batchId}`, { headers: { 'Accept': 'application/json' } });
                const attendanceResult = await attendanceResp.json();

                renderAttendanceList(studentResult.data.items, attendanceResult.data);
                document.getElementById('action-bar').classList.remove('hidden');
            } catch (error) {
                showToast('Error loading data', 'error');
            } finally {
                toggleLoader(false);
            }
        }

        function renderAttendanceList(students, existingRecords) {
            const container = document.getElementById('attendance-cards-grid');

            if (students.length === 0) {
                container.innerHTML = `<div class="col-span-full py-20 text-center text-slate-400 font-medium italic">No students found assigned to this batch.</div>`;
                document.getElementById('action-bar').classList.add('hidden');
                document.getElementById('bulk-actions').classList.add('hidden');
                updateStats(0, 0, 0);
                return;
            }

            document.getElementById('bulk-actions').classList.remove('hidden');

            // Map existing status
            const statusMap = {};
            existingRecords.forEach(rec => { statusMap[rec.student_id] = rec.status; });

            container.innerHTML = students.map(student => {
                const currentStatus = statusMap[student.id] || 'present';
                // Get initials for avatar
                const initials = student.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);

                return `
                    <div class="bg-white border border-slate-100 rounded-3xl p-5 shadow-sm hover:shadow-md transition-all group animate-in zoom-in-95 duration-300 relative flex flex-col justify-between">
                        <div>
                            <div class="flex items-center mb-5">
                                <div class="h-12 w-12 rounded-full bg-[#1e3a8a] flex items-center justify-center text-white font-black text-sm tracking-tighter mr-4 shadow-lg shadow-blue-900/20 group-hover:scale-105 transition-transform shrink-0">
                                    ${initials}
                                </div>
                                <div class="overflow-hidden">
                                    <h4 class="text-[14px] font-black text-[#1f2937] leading-tight px-1 break-words">${student.name}</h4>
                                    <p class="text-[10px] font-bold text-slate-300 uppercase tracking-widest mt-1 px-1">ID: ST-0${student.id}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2 mt-auto">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="status_${student.id}" value="present" 
                                    ${currentStatus === 'present' ? 'checked' : ''} 
                                    onchange="calculateStats()" 
                                    class="hidden peer student-status-input" data-id="${student.id}">
                                <div class="py-2.5 rounded-xl text-center text-[10px] font-black border border-transparent bg-slate-50 text-slate-400 peer-checked:bg-emerald-50 peer-checked:border-emerald-200 peer-checked:text-emerald-500 transition-all hover:bg-slate-100">PRESENT</div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="status_${student.id}" value="absent" 
                                    ${currentStatus === 'absent' ? 'checked' : ''} 
                                    onchange="calculateStats()" 
                                    class="hidden peer student-status-input" data-id="${student.id}">
                                <div class="py-2.5 rounded-xl text-center text-[10px] font-black border border-transparent bg-slate-50 text-slate-400 peer-checked:bg-rose-50 peer-checked:border-rose-100 peer-checked:text-rose-400 transition-all hover:bg-slate-100">ABSENT</div>
                            </label>
                        </div>
                    </div>
                `;
            }).join('');
            calculateStats();
        }

        function calculateStats() {
            const studentsCount = document.querySelectorAll('.student-status-input[value="present"]').length;
            const present = document.querySelectorAll('.student-status-input[value="present"]:checked').length;
            const absent = document.querySelectorAll('.student-status-input[value="absent"]:checked').length;

            updateStats(studentsCount, present, absent);
        }

        function updateStats(total, present, absent) {
            document.getElementById('stat-total').innerText = total;
            document.getElementById('stat-present').innerText = present;
            document.getElementById('stat-absent').innerText = absent;

            document.getElementById('stat-present-label').innerText = total > 0 ? Math.round((present / total) * 100) + '% Attendance Rate' : 'Ready to mark';
            document.getElementById('stat-absent-label').innerText = absent > 0 ? absent + ' students missing' : 'Everyone accounted for';
        }

        function markAllPresent() {
            document.querySelectorAll('.student-status-input[value="present"]').forEach(radio => {
                radio.checked = true;
            });
            calculateStats();
            showToast('All marked as present', 'success');
        }

        function markAllAbsent() {
            document.querySelectorAll('.student-status-input[value="absent"]').forEach(radio => {
                radio.checked = true;
            });
            calculateStats();
            showToast('All marked as absent', 'success');
        }

        async function submitAttendance() {
            const batchId = document.getElementById('batch-selector').value;
            const date = document.getElementById('attendance-date').value;
            const statusInputs = document.querySelectorAll('.student-status-input:checked');

            const attendanceData = Array.from(statusInputs).map(input => ({
                student_id: input.getAttribute('data-id'),
                status: input.value
            }));

            if (attendanceData.length === 0) return;

            toggleSubmitLoading(true);
            try {
                const response = await fetch("/api/v1/institute/attendance", {
                    method: 'POST',
                    headers: { 
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN 
                    },
                    body: JSON.stringify({
                        batch_id: batchId,
                        date: date,
                        attendance: attendanceData
                    })
                });

                const result = await response.json();
                if (result.status === 'success') {
                    showToast(result.message, 'success');
                    setTimeout(() => {
                        window.location.href = "{{ route('institute.attendance.index') }}";
                    }, 1500);
                } else {
                    showToast(result.message || 'Error saving attendance', 'error');
                }
            } catch (error) {
                showToast('Network error', 'error');
            } finally {
                toggleSubmitLoading(false);
            }
        }

        function toggleLoader(show) { document.getElementById('loading-spinner').classList.toggle('hidden', !show); }
        function toggleSubmitLoading(show) {
            const btn = document.getElementById('submit-btn');
            if (!btn) return;
            document.getElementById('btn-loader').classList.toggle('hidden', !show);
            btn.disabled = show;
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
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }
    </script>
@endsection
