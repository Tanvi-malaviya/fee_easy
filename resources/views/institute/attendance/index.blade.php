@extends('layouts.institute')

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto pb-10">
    <!-- Toast Notifications Container -->
    <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

    <!-- Page Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Attendance Tracking</h1>
            <p class="text-sm text-slate-400 mt-2 font-medium">Record and manage daily attendance for your students.</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="relative">
                <input type="date" id="attendance-date" value="{{ date('Y-m-d') }}" class="px-5 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold shadow-sm outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
            </div>
            <select id="batch-selector" class="px-5 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold shadow-sm outline-none focus:ring-4 focus:ring-blue-500/5 transition-all min-w-[200px]">
                <option value="">Select Batch...</option>
                <!-- Batches loaded via JS -->
            </select>
            <button onclick="loadAttendanceList()" class="px-8 py-3 bg-[#1e3a8a] text-white rounded-2xl font-bold text-[13px] shadow-lg shadow-blue-900/10 hover:scale-[1.02] transition-transform">
                Load List
            </button>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/20">
            <h3 class="text-sm font-extrabold text-slate-700 uppercase tracking-widest">Mark Attendance</h3>
            <div id="loading-spinner" class="hidden h-5 w-5 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin"></div>
        </div>

        <div class="overflow-x-auto min-h-[300px]">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] uppercase font-extrabold text-slate-400 tracking-widest border-b border-slate-50">
                        <th class="px-8 py-5">Student Information</th>
                        <th class="px-8 py-5">Status Selection</th>
                        <th class="px-8 py-5 text-right">Last Recorded</th>
                    </tr>
                </thead>
                <tbody id="attendance-table-body" class="divide-y divide-slate-50">
                    <tr><td colspan="3" class="px-8 py-20 text-center text-slate-400 font-medium italic">Please select a batch and date to load the attendance registry.</td></tr>
                </tbody>
            </table>
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
        const container = document.getElementById('attendance-table-body');
        
        if (students.length === 0) {
            container.innerHTML = `<tr><td colspan="3" class="px-8 py-20 text-center text-slate-400 font-medium">No students found assigned to this batch.</td></tr>`;
            document.getElementById('action-bar').classList.add('hidden');
            return;
        }

        // Map existing status
        const statusMap = {};
        existingRecords.forEach(rec => { statusMap[rec.student_id] = rec.status; });

        container.innerHTML = students.map(student => {
            const currentStatus = statusMap[student.id] || 'present'; // Default to present
            return `
                <tr class="hover:bg-slate-50/40 transition-all group">
                    <td class="px-8 py-6">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center border border-slate-200 mr-4">
                                <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(student.name)}&background=1e3a8a&color=fff" class="w-full h-full rounded-xl">
                            </div>
                            <div class="flex flex-col">
                                <h4 class="text-[13px] font-extrabold text-slate-800 leading-tight">${student.name}</h4>
                                <span class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest leading-none">STU-${String(student.id).padStart(4, '0')}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex items-center space-x-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="status_${student.id}" value="present" ${currentStatus === 'present' ? 'checked' : ''} class="hidden peer student-status" data-id="${student.id}">
                                <div class="px-4 py-2 rounded-xl text-[11px] font-extrabold border border-slate-100 bg-slate-50 text-slate-400 peer-checked:bg-emerald-50 peer-checked:border-emerald-200 peer-checked:text-emerald-600 transition-all">Present</div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status_${student.id}" value="absent" ${currentStatus === 'absent' ? 'checked' : ''} class="hidden peer student-status" data-id="${student.id}">
                                <div class="px-4 py-2 rounded-xl text-[11px] font-extrabold border border-slate-100 bg-slate-50 text-slate-400 peer-checked:bg-rose-50 peer-checked:border-rose-200 peer-checked:text-rose-600 transition-all">Absent</div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status_${student.id}" value="late" ${currentStatus === 'late' ? 'checked' : ''} class="hidden peer student-status" data-id="${student.id}">
                                <div class="px-4 py-2 rounded-xl text-[11px] font-extrabold border border-slate-100 bg-slate-50 text-slate-400 peer-checked:bg-amber-50 peer-checked:border-amber-200 peer-checked:text-amber-600 transition-all">Late</div>
                            </label>
                        </div>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <span class="text-[11px] font-bold text-slate-400 italic">${statusMap[student.id] ? 'Previously Marked' : 'New Entry'}</span>
                    </td>
                </tr>
            `;
        }).join('');
    }

    async function submitAttendance() {
        const batchId = document.getElementById('batch-selector').value;
        const date = document.getElementById('attendance-date').value;
        const statusInputs = document.querySelectorAll('.student-status:checked');
        
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
        document.getElementById('btn-loader').classList.toggle('hidden', !show);
        document.getElementById('submit-btn').disabled = show;
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
