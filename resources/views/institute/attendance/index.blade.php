@extends('layouts.institute')

@section('content')
    <!-- Full Screen Loader -->
    <div id="fullscreen-loader" class="hidden fixed inset-0 bg-black/20 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white rounded-3xl p-10 shadow-2xl flex flex-col items-center">
            <div class="w-12 h-12 border-4 border-slate-200 border-t-blue-600 rounded-full animate-spin"></div>
            <p class="mt-5 text-slate-600 font-semibold text-sm">Loading attendance records...</p>
        </div>
    </div>

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
                    class="px-5 py-2.5 bg-[#1e3a8a] text-white rounded-xl font-bold text-[12px] shadow-lg shadow-blue-900/10 hover:scale-[1.02] transition-transform flex items-center">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        <!-- History Log Cards -->
        <div class="space-y-4">
            <div class="flex items-center justify-between px-2">
                <h3 class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Recent Records Log</h3>
            </div>

            <div id="history-cards-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <!-- Cards will be loaded via JS -->
                <div class="col-span-full flex items-center justify-center py-20">
                    <p class="text-slate-400 font-medium">Loading history records...</p>
                </div>
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
            const container = document.getElementById('history-cards-container');

            if (records.length === 0) {
                container.innerHTML = `<div class="col-span-full flex items-center justify-center py-20 text-center"><p class="text-slate-400 font-medium italic">No attendance records found for today. Get started by marking a new session!</p></div>`;
                return;
            }

            // Grouping records by batch
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

            container.innerHTML = Object.values(batchGroups).map(batch => {
                const percentage = Math.round((batch.present / batch.total) * 100);
                return `
                    <div class="bg-gradient-to-br from-white to-slate-50/30 rounded-2xl shadow-md border border-slate-100 overflow-hidden hover:shadow-lg hover:border-blue-200 transition-all duration-300">
                        <!-- Card Header -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50/30 p-4 border-b border-slate-100">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <div class="h-10 w-10 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-lg flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date & Session</p>
                                        <h4 class="text-[14px] font-black text-slate-900">${new Date(batch.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</h4>
                                        <span class="text-[9px] font-bold text-slate-500">Morning Session</span>
                                    </div>
                                </div>
                                <span class="inline-flex px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[9px] font-bold rounded-full uppercase tracking-widest flex-shrink-0">Completed</span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-4 space-y-3.5">
                            <!-- Batch Info -->
                            <div class="pb-3.5 border-b border-slate-100">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Batch Information</p>
                                <h3 class="text-[14px] font-black text-slate-800">${batch.name}</h3>
                            </div>

                            <!-- Attendance Summary -->
                            <div class="space-y-3">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Attendance Summary</p>
                                <div class="space-y-2.5">
                                    <!-- Present Count -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-1.5">
                                            <div class="h-2 w-2 rounded-full bg-emerald-500"></div>
                                            <span class="text-xs font-semibold text-slate-700">Present</span>
                                        </div>
                                        <span class="text-sm font-black text-emerald-600">${batch.present}/${batch.total}</span>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden shadow-inner">
                                        <div class="h-full bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-full transition-all duration-300" style="width: ${percentage}%"></div>
                                    </div>

                                    <!-- Percentage -->
                                    <div class="text-right">
                                        <span class="text-[9px] font-bold text-slate-500">${percentage}% Attendance</span>
                                    </div>

                                    <!-- Absent Count -->
                                    <div class="flex items-center justify-between pt-2 border-t border-slate-50">
                                        <div class="flex items-center space-x-1.5">
                                            <div class="h-2 w-2 rounded-full bg-rose-500"></div>
                                            <span class="text-xs font-semibold text-slate-700">Absent</span>
                                        </div>
                                        <span class="text-sm font-black text-rose-600">${batch.absent}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-4 py-3 bg-slate-50/50 border-t border-slate-100 flex justify-end">
                            <a href="{{ route('institute.attendance.create') }}" class="inline-flex items-center px-3.5 py-1.5 bg-blue-600 text-white rounded-lg text-[11px] font-bold hover:bg-blue-700 transition-colors shadow-lg hover:shadow-xl">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-7-4l7-7m0 0v5m0-5h-5"/></svg>
                                Edit
                            </a>
                        </div>
                    </div>
                `;
            }).join('');
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

        function toggleLoader(show) { 
            document.getElementById('fullscreen-loader').classList.toggle('hidden', !show); 
        }
    </script>
@endsection