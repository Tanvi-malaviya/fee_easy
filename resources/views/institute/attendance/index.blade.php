@extends('layouts.institute')

@section('content')
    <div class="space-y-6 max-w-[1600px] mx-auto pb-10">
        <!-- Page Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-2">
            <div>
                <h1 class="text-3xl font-extrabold text-[#111827] tracking-tight">Attendance History</h1>
                <!-- <p class="text-sm text-slate-500 mt-1 font-medium">Review and manage past attendance records.</p> -->
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center bg-white rounded-2xl shadow-sm border border-slate-100 p-1 cursor-pointer hover:border-blue-200 transition-all group"
                    onclick="document.getElementById('history-date-picker').showPicker()">
                    <div class="px-5 py-2.5 flex items-center">
                        <svg class="w-4 h-4 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span id="display-date"
                            class="text-sm font-bold text-slate-700 uppercase tracking-tight">{{ date('M d, Y') }}</span>
                        <input type="date" id="history-date-picker" value="{{ date('Y-m-d') }}"
                            class="absolute opacity-0 pointer-events-none" onchange="fetchHistory(this.value)">
                    </div>
                </div>

                <a href="{{ route('institute.attendance.create') }}"
                    class="px-8 py-4 bg-[#1e3a8a] text-white rounded-2xl font-bold text-[14px] shadow-xl shadow-blue-900/10 hover:scale-[1.02] transition-transform flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Mark Attendance
                </a>
            </div>
        </div>

        <!-- Overview Stats -->
        <!-- <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                        <svg class="w-12 h-12 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    </div>
                    <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Marked Sessions</p>
                    <h3 id="stat-today-marked" class="text-3xl font-black text-slate-800 mt-2">--</h3>
                    <p class="text-[11px] font-bold text-slate-400 mt-1">Batches completed</p>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                        <svg class="w-12 h-12 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    </div>
                    <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Avg. Attendance</p>
                    <h3 id="stat-avg-present" class="text-3xl font-black text-emerald-600 mt-2">--%</h3>
                    <p class="text-[11px] font-bold text-emerald-600/60 mt-1">Across all sessions</p>
                </div>
            </div> -->

            <!-- History Log Table -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden relative">
                <div class="p-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/20">
                    <h3 class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Recent Records Log</h3>
                    <div id="loading-spinner"
                        class="hidden h-4 w-4 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin"></div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="text-[10px] uppercase font-extrabold text-slate-400 tracking-widest border-b border-slate-50 bg-slate-50/10">
                                <th class="px-8 py-5">Date & Session</th>
                                <th class="px-8 py-5">Batch Information</th>
                                <th class="px-8 py-5">Attendance Summary</th>
                                <th class="px-8 py-5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="history-table-body" class="divide-y divide-slate-50">
                            <!-- Loaded via JS -->
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center text-slate-400 font-medium">Loading history
                                    records...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                fetchHistory();
            });

            async function fetchHistory(selectedDate = null) {
                toggleLoader(true);
                try {
                    const date = selectedDate || document.getElementById('history-date-picker').value;

                    // Update UI display date
                    if (selectedDate) {
                        const options = { month: 'short', day: 'numeric', year: 'numeric' };
                        document.getElementById('display-date').innerText = new Date(selectedDate).toLocaleDateString('en-US', options).toUpperCase();
                    }

                    const response = await fetch(`/api/v1/institute/attendance?date=${date}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const result = await response.json();

                    if (result.status === 'success') {
                        renderHistory(result.data);
                        updateHistoryStats(result.data);
                    }
                } catch (error) {
                    console.error('History Fetch Error:', error);
                } finally {
                    toggleLoader(false);
                }
            }

            function renderHistory(records) {
                const container = document.getElementById('history-table-body');

                if (records.length === 0) {
                    container.innerHTML = `<tr><td colspan="4" class="px-8 py-20 text-center text-slate-400 font-medium italic">No attendance records found for today. Get started by marking a new session!</td></tr>`;
                    return;
                }

                // Grouping records by batch since the API returns individual student records
                const batchGroups = {};
                records.forEach(rec => {
                    if (!batchGroups[rec.batch_id]) {
                        batchGroups[rec.batch_id] = {
                            name: rec.batch?.name || 'Unknown Batch',
                            date: rec.date,
                            total: 0,
                            present: 0,
                            absent: 0,
                            late: 0
                        };
                    }
                    batchGroups[rec.batch_id].total++;
                    if (rec.status === 'present') batchGroups[rec.batch_id].present++;
                    else if (rec.status === 'absent') batchGroups[rec.batch_id].absent++;
                    else if (rec.status === 'late') batchGroups[rec.batch_id].late++;
                });

                container.innerHTML = Object.values(batchGroups).map(batch => `
                    <tr class="hover:bg-slate-50/40 transition-all group animate-in fade-in slide-in-from-bottom-2 duration-300">
                        <td class="px-8 py-6">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 mr-4">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div class="flex flex-col">
                                    <h4 class="text-[14px] font-extrabold text-slate-800 leading-tight">${new Date(batch.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</h4>
                                    <span class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest leading-none">Morning Session</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <h4 class="text-[14px] font-extrabold text-slate-800 leading-tight">${batch.name}</h4>
                                <span class="text-[10px] font-bold text-emerald-500 mt-1 uppercase tracking-widest leading-none">Status: Completed</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center space-x-3">
                                <div class="flex flex-col">
                                    <span class="text-[16px] font-black text-slate-800">${batch.present}/${batch.total}</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Scholars Present</span>
                                </div>
                                <div class="flex-1 max-w-[100px] h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: ${(batch.present / batch.total * 100).toFixed(0)}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <a href="{{ route('institute.attendance.create') }}" class="inline-flex h-9 w-9 bg-slate-50 rounded-lg items-center justify-center text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M15.172 2.172a2.828 2.828 0 114 4L11.828 15H7v-4.828l8.172-8.172z"/></svg>
                            </a>
                        </td>
                    </tr>
                `).join('');
            }

            function updateHistoryStats(records) {
                const batchIds = new Set(records.map(r => r.batch_id));
                document.getElementById('stat-today-marked').innerText = batchIds.size;

                if (records.length > 0) {
                    const present = records.filter(r => r.status === 'present').length;
                    const avg = Math.round((present / records.length) * 100);
                    document.getElementById('stat-avg-present').innerText = avg + '%';
                }
            }

            function toggleLoader(show) { document.getElementById('loading-spinner').classList.toggle('hidden', !show); }
        </script>
@endsection