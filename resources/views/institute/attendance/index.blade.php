@extends('layouts.institute')

@section('content')
    <div class="space-y-6 max-w-[1600px] mx-auto pb-10">
        <!-- Toast Notifications Container -->
        <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

        <!-- Page Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-[#111827] tracking-tight">Attendance History</h1>
                <!-- <p class="text-sm text-slate-500 mt-1 font-medium">Review and manage past attendance records.</p> -->
            </div>

            <div class="flex items-center gap-2">
                <div class="flex items-center bg-white rounded-xl shadow-sm border border-slate-100 p-1 cursor-pointer hover:border-blue-200 transition-all group"
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
        <div class="space-y-3">
            <div class="flex items-center justify-between px-2">
                <h3 class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Recent Records Log</h3>
            </div>

            <div id="history-container" class="relative min-h-[250px]">
                <!-- Common Loader -->
                <div id="loading-spinner"
                    class="absolute inset-0 z-50 bg-white/60 backdrop-blur-[2px] hidden flex items-center justify-center transition-all duration-300">
                    <div class="flex flex-col items-center">
                        <div class="h-12 w-12 border-4 border-slate-100 border-t-blue-600 rounded-full animate-spin"></div>
                        <span class="mt-4 text-sm font-bold text-slate-500 tracking-wide uppercase">Refining Results...</span>
                    </div>
                </div>

                <div id="history-cards-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    <!-- Cards will be loaded via JS -->
                </div>

                <!-- Pagination -->
                <div id="pagination-container" class="mt-8 flex items-center justify-between border-t border-slate-100 pt-6">
                    <!-- Pagination generated via JS -->
                </div>
            </div>
        </div>
    </div>

    <script>
        let allRecords = [];
        let currentPage = 1;
        const perPage = 6;

        document.addEventListener('DOMContentLoaded', () => {
            fetchHistory();
        });

        async function fetchHistory(selectedDate = null) {
            toggleLoader(true);
            try {
                const date = selectedDate || document.getElementById('history-date-picker').value;

                if (selectedDate) {
                    const options = { month: 'short', day: 'numeric', year: 'numeric' };
                    document.getElementById('display-date').innerText = new Date(selectedDate).toLocaleDateString('en-US', options).toUpperCase();
                }

                const response = await fetch(`/api/v1/institute/attendance?date=${date}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const result = await response.json();

                if (result.status === 'success') {
                    allRecords = result.data;
                    currentPage = 1;
                    renderHistory();
                    updateHistoryStats(allRecords);
                }
            } catch (error) {
                console.error('History Fetch Error:', error);
            } finally {
                toggleLoader(false);
            }
        }

        function renderHistory() {
            const container = document.getElementById('history-cards-container');
            
            // Grouping records by batch
            const batchGroups = {};
            allRecords.forEach(rec => {
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

            const batches = Object.values(batchGroups);
            
            if (batches.length === 0) {
                container.innerHTML = `<div class="col-span-full flex items-center justify-center py-20 text-center"><p class="text-slate-400 font-medium italic">No attendance records found for today.</p></div>`;
                document.getElementById('pagination-container').innerHTML = '';
                return;
            }

            // Pagination logic
            const total = batches.length;
            const lastPage = Math.ceil(total / perPage);
            const start = (currentPage - 1) * perPage;
            const end = start + perPage;
            const paginatedBatches = batches.slice(start, end);

            container.innerHTML = paginatedBatches.map(batch => {
                const percentage = Math.round((batch.present / batch.total) * 100);
                return `
                    <div class="bg-gradient-to-br from-white to-slate-50/30 rounded-2xl shadow-md border border-slate-100 overflow-hidden hover:shadow-lg hover:border-blue-200 transition-all duration-300 animate-in fade-in zoom-in-95">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50/30 p-4 border-b border-slate-100">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <div class="h-10 w-10 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-lg flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date & Session</p>
                                        <h4 class="text-[14px] font-black text-slate-900">${new Date(batch.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</h4>
                                        <span class="text-[9px] font-bold text-slate-500">Regular Session</span>
                                    </div>
                                </div>
                                <span class="inline-flex px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[9px] font-bold rounded-full uppercase tracking-widest flex-shrink-0">Completed</span>
                            </div>
                        </div>

                        <div class="p-4 space-y-3.5">
                            <div class="pb-3.5 border-b border-slate-100">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Batch Information</p>
                                <h3 class="text-[14px] font-black text-slate-800">${batch.name}</h3>
                            </div>

                            <div class="space-y-3">
                                <div class="space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-1.5">
                                            <div class="h-2 w-2 rounded-full bg-emerald-500"></div>
                                            <span class="text-xs font-semibold text-slate-700">Present</span>
                                        </div>
                                        <span class="text-sm font-black text-emerald-600">${batch.present}/${batch.total}</span>
                                    </div>

                                    <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-full transition-all duration-300" style="width: ${percentage}%"></div>
                                    </div>

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

                        <div class="px-4 py-3 bg-slate-50/50 border-t border-slate-100 flex justify-end">
                            <a href="{{ route('institute.attendance.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl text-[11px] font-bold hover:bg-blue-700 transition-all shadow-md hover:scale-105">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-7-4l7-7m0 0v5m0-5h-5"/></svg>
                                Update Records
                            </a>
                        </div>
                    </div>
                `;
            }).join('');

            renderPagination(total, lastPage);
        }

        function renderPagination(total, lastPage) {
            const container = document.getElementById('pagination-container');
            if (lastPage <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = `
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">
                    Showing <span class="text-slate-700">${Math.min(currentPage * perPage, total)}</span> of <span class="text-slate-700">${total}</span> records
                </p>
                <div class="flex items-center space-x-2">
            `;

            // Prev Button
            html += `
                <button onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} 
                    class="h-9 px-4 rounded-xl border border-slate-100 text-[11px] font-black uppercase tracking-widest transition-all ${currentPage === 1 ? 'text-slate-300 bg-slate-50 cursor-not-allowed' : 'text-slate-600 bg-white hover:bg-slate-50 hover:border-slate-200'}">
                    Prev
                </button>
            `;

            // Page Numbers
            for (let i = 1; i <= lastPage; i++) {
                if (i === 1 || i === lastPage || (i >= currentPage - 1 && i <= currentPage + 1)) {
                    html += `
                        <button onclick="changePage(${i})" 
                            class="h-9 w-9 rounded-xl text-[11px] font-black transition-all ${i === currentPage ? 'bg-[#1e3a8a] text-white shadow-lg shadow-blue-900/20' : 'text-slate-600 bg-white border border-slate-100 hover:bg-slate-50'}">
                            ${i}
                        </button>
                    `;
                } else if (i === currentPage - 2 || i === currentPage + 2) {
                    html += `<span class="text-slate-300">...</span>`;
                }
            }

            // Next Button
            html += `
                <button onclick="changePage(${currentPage + 1})" ${currentPage === lastPage ? 'disabled' : ''} 
                    class="h-9 px-4 rounded-xl border border-slate-100 text-[11px] font-black uppercase tracking-widest transition-all ${currentPage === lastPage ? 'text-slate-300 bg-slate-50 cursor-not-allowed' : 'text-slate-600 bg-white hover:bg-slate-50 hover:border-slate-200'}">
                    Next
                </button>
            </div>`;

            container.innerHTML = html;
        }

        function changePage(page) {
            currentPage = page;
            renderHistory();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function toggleLoader(show) {
            const spinner = document.getElementById('loading-spinner');
            if (show) {
                spinner.classList.remove('hidden');
                spinner.classList.add('flex');
            } else {
                spinner.classList.add('hidden');
                spinner.classList.remove('flex');
            }
        }

    </script>
@endsection