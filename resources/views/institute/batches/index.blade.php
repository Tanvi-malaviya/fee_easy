@extends('layouts.institute')

@section('content')
    <div class="max-w-[1600px] mx-auto pb-10">

        <!-- MAIN LIST VIEW -->
        <div id="list-view" class="space-y-4 animate-in fade-in duration-500">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Batch Management</h1>
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-1">Manage academic cohorts &
                        enrollment status.</p>
                </div>

                <div class="flex items-center gap-2">
                    <div
                        class="bg-white px-5 py-3 rounded-[1rem] border border-slate-100 shadow-sm flex flex-col items-center min-w-[120px]">
                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total Batches</p>
                        <h3 id="stat-total-batches" class="text-lg font-bold text-slate-800">0</h3>
                    </div>
                    <div
                        class="bg-white px-5 py-3 rounded-[1rem] border border-slate-100 shadow-sm flex flex-col items-center min-w-[120px]">
                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total Students</p>
                        <h3 id="stat-total-students" class="text-lg font-bold text-slate-800">0</h3>
                    </div>
                </div>
            </div>

            <div
                class="bg-white p-2 rounded-[1rem] border border-slate-100 shadow-sm flex flex-col md:flex-row items-center gap-2">
                <div class="relative flex-1 group w-full md:w-auto">
                    <input type="text" id="batch-search" placeholder="Search batches..."
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50/50 border border-slate-100 rounded-xl text-[12px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-primary"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <button
                        class="h-10 px-4 bg-white border border-slate-100 text-slate-500 rounded-xl font-bold text-[10px] uppercase hover:bg-slate-50 transition-all flex items-center gap-2">Filter</button>
                    <button
                        class="h-10 px-4 bg-white border border-slate-100 text-slate-500 rounded-xl font-bold text-[10px] uppercase hover:bg-slate-50 transition-all flex items-center gap-2">Export</button>
                    <button onclick="toggleFormView(true)"
                        class="h-10 px-5 bg-primary2 text-white rounded-xl font-bold text-[10px] uppercase tracking-widest hover:scale-[1.02] transition-transform flex items-center gap-2 whitespace-nowrap">
                        Intilize new batch
                    </button>
                </div>
            </div>

            <div id="batch-grid"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 relative min-h-[300px]">
                <div id="loading-spinner"
                    class="absolute inset-0 z-50 bg-white/60 backdrop-blur-[2px] hidden flex items-center justify-center rounded-[1rem]">
                    <div class="h-10 w-10 border-4 border-slate-100 border-t-primary rounded-full animate-spin"></div>
                </div>
            </div>

            <div id="pagination-container"
                class="bg-white p-4 rounded-[1rem] border border-slate-100 shadow-sm flex items-center justify-between">
            </div>
        </div>

        <!-- MANAGE BATCH VIEW -->
        <div id="form-view" class="hidden animate-in slide-in-from-bottom-4 duration-500">
            <form id="batch-form">
                <input type="hidden" id="batch-id" name="id">

                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 id="form-title" class="text-2xl font-bold text-slate-800 tracking-tight">Manage Batch</h1>
                        <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-1">Configure academic
                            batch details, schedule, and capacity.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="toggleFormView(false)"
                            class="h-11 px-6 border-2 border-primary text-primary rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-orange-50 transition-all">Cancel</button>
                        <button type="submit" id="submit-btn"
                            class="h-11 px-6 bg-primary text-white rounded-xl font-bold text-[10px] uppercase tracking-widest shadow-lg shadow-orange-500/20 hover:scale-[1.02] transition-transform flex items-center gap-3">
                            <span id="btn-text">Save Batch</span>
                            <span id="btn-loader"
                                class="hidden h-3 w-3 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Combined Info & Schedule Card -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white p-6 rounded-[1rem] border border-slate-100 shadow-sm space-y-6">
                            <!-- Info Section -->
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 pb-2 border-b border-slate-50">
                                    <div
                                        class="h-8 w-8 bg-orange-50 text-primary rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-800 tracking-tight">General Information</h3>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div class="space-y-1.5">
                                        <label
                                            class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1">Batch
                                            Name</label>
                                        <input type="text" name="name" id="field-name" required
                                            placeholder="e.g. Maths 2024"
                                            class="w-full px-4 py-3 bg-slate-50/50 border border-slate-100 rounded-xl text-[12px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label
                                            class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1">Subject</label>
                                        <input type="text" name="subject" id="field-subject" required
                                            placeholder="Select Subject"
                                            class="w-full px-4 py-3 bg-slate-50/50 border border-slate-100 rounded-xl text-[12px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label
                                            class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1">Fees
                                            (₹)</label>
                                        <input type="number" name="fees" id="field-fees" placeholder="0"
                                            class="w-full px-4 py-3 bg-slate-50/50 border border-slate-100 rounded-xl text-[12px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all">
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1">Batch
                                        Description</label>
                                    <textarea name="description" id="field-description" rows="2"
                                        placeholder="Brief details about this cohort..."
                                        class="w-full px-4 py-3 bg-slate-50/50 border border-slate-100 rounded-xl text-[12px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all resize-none"></textarea>
                                </div>
                            </div>

                            <!-- Schedule Section -->
                            <div class="space-y-4 pt-4 border-t border-slate-50">
                                <div class="flex items-center gap-2 pb-2 border-b border-slate-50">
                                    <div
                                        class="h-8 w-8 bg-orange-50 text-primary rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-800 tracking-tight">Schedule & Timing</h3>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label
                                            class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1">Start
                                            Time</label>
                                        <input type="time" name="start_time" id="field-start"
                                            class="w-full px-4 py-3 bg-slate-50/50 border border-slate-100 rounded-xl text-[12px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label
                                            class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1">End
                                            Time</label>
                                        <input type="time" name="end_time" id="field-end"
                                            class="w-full px-4 py-3 bg-slate-50/50 border border-slate-100 rounded-xl text-[12px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all">
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1">Days
                                        of the week</label>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                            <label class="relative cursor-pointer group">
                                                <input type="checkbox" name="days[]" value="{{ substr($day, 0, 3) }}"
                                                    class="peer sr-only day-checkbox">
                                                <div
                                                    class="px-4 py-2 bg-white border border-slate-100 rounded-xl text-[11px] font-bold text-slate-400 transition-all peer-checked:bg-secondary/10 peer-checked:text-secondary peer-checked:border-secondary group-hover:bg-slate-50">
                                                    {{ $day }}
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Capacity & Visual -->
                    <div class="space-y-6">
                        <div class="bg-white p-6 rounded-[1rem] border border-slate-100 shadow-sm space-y-4">
                            <div class="flex items-center gap-2 pb-2 border-b border-slate-50">
                                <div class="h-8 w-8 bg-orange-50 text-primary rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857" />
                                    </svg>
                                </div>
                                <h3 class="text-base font-bold text-slate-800 tracking-tight">Capacity</h3>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1">Maximum
                                    Students</label>
                                <div class="flex items-center gap-3">
                                    <input type="number" name="max_capacity" id="field-capacity" placeholder="30"
                                        class="flex-1 px-4 py-3 bg-slate-50/50 border border-slate-100 rounded-xl text-[12px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all">
                                    <span
                                        class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Seats</span>
                                </div>
                            </div>
                            <div class="p-4 bg-secondary/5 rounded-xl border border-secondary/10">
                                <p class="text-[10px] font-bold text-slate-500 leading-relaxed">
                                    <span class="text-secondary font-bold uppercase tracking-widest mr-1">Pro Tip:</span>
                                    25-35 capacity is recommended for high-engagement.
                                </p>
                            </div>
                        </div>

                        <div class="relative rounded-[1rem] overflow-hidden shadow-lg h-[200px]">
                            <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=2070&auto=format&fit=crop"
                                class="w-full h-full object-cover">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent flex flex-col justify-end p-5">
                                <p class="text-white text-[11px] font-bold italic leading-tight">
                                    "Education is the most powerful weapon which you can use to change the world."
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const API_URL = "/api/v1/institute/batches";
        const CSRF_TOKEN = "{{ csrf_token() }}";

        document.addEventListener('DOMContentLoaded', () => fetchBatches());

        async function fetchBatches(page = 1) {
            toggleLoader(true);
            try {
                const response = await fetch(`${API_URL}?page=${page}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const result = await response.json();
                if (result.status === 'success') {
                    renderBatches(result.data.items);
                    renderPagination(result.data);
                    document.getElementById('stat-total-batches').innerText = result.data.total;
                    const totalStudents = result.data.items.reduce((acc, b) => acc + (b.students_count || 0), 0);
                    document.getElementById('stat-total-students').innerText = totalStudents;
                }
            } catch (error) { console.error(error); }
            finally { toggleLoader(false); }
        }

        function renderBatches(items) {
            const container = document.getElementById('batch-grid');
            if (items.length === 0) {
                container.innerHTML = `<div class="col-span-full py-20 text-center"><p class="text-slate-400 font-bold uppercase tracking-widest">No batches found</p></div>`;
                return;
            }

            const icons = ['💻', '🎨', '🧪', '📈', '🏛️', '🛡️', '📱', '🧠'];
            container.innerHTML = items.map((batch, idx) => {
                const studentsCount = batch.students_count || 0;
                const maxCapacity = batch.max_capacity || 30;
                const progress = Math.min((studentsCount / maxCapacity) * 100, 100);
                const icon = icons[idx % icons.length];
                let statusBadge = progress >= 100
                    ? '<span class="px-2 py-0.5 bg-rose-50 text-rose-500 rounded-lg text-[7px] font-bold uppercase tracking-widest">Full</span>'
                    : '<span class="px-2 py-0.5 bg-emerald-50 text-emerald-500 rounded-lg text-[7px] font-bold uppercase tracking-widest">Active</span>';

                return `
                    <div class="bg-white p-5 rounded-[1rem] border border-slate-100 shadow-sm hover:shadow-md transition-all group">
                        <div class="flex items-start justify-between mb-4">
                            <div class="h-10 w-10 bg-slate-50 rounded-xl flex items-center justify-center text-lg">${icon}</div>
                            <!-- Actions Small Card -->
                            <div class="flex items-center bg-white border border-slate-100 rounded-xl p-1 shadow-sm gap-0.5">
                                <a href="/institute/batches/${batch.id}" class="h-8 w-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-emerald-50 hover:text-emerald-500 transition-all" title="View Details">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <button onclick='openEditForm(${JSON.stringify(batch).replace(/'/g, "&apos;")})' class="h-8 w-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-blue-50 hover:text-blue-500 transition-all" title="Edit Batch">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <button onclick="deleteBatch(${batch.id}, '${batch.name.replace(/'/g, "\\'")}')" class="h-8 w-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all" title="Delete Batch">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="space-y-1 mb-2">
                            <h4 class="text-sm font-bold text-slate-800 leading-tight">${batch.name}</h4>
                            <div class="flex items-center gap-2">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">${batch.subject}</span>
                                <span class="px-1.5 py-0.5 bg-blue-50 text-blue-600 rounded-md text-[8px] font-bold uppercase">₹${batch.fees || '0'}</span>
                            </div>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 line-clamp-2 mb-4 leading-relaxed">${batch.description || 'No description provided.'}</p>
                        <div class="space-y-2 mb-4 text-slate-500">
                            <div class="flex items-center gap-2"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span class="text-[10px] font-bold">${batch.start_time || '--'} - ${batch.end_time || '--'}</span></div>
                            <div class="flex items-center gap-2"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><div class="flex flex-wrap gap-1">${(batch.days || []).map(day => `<span class="text-[8px] font-bold text-slate-700">${day}</span>`).join('<span class="text-slate-300">,</span>')}</div></div>
                        </div>
                        <div class="flex items-center justify-between mb-1.5">${statusBadge}<span class="text-[9px] font-bold text-slate-800">${studentsCount}/${maxCapacity} Students</span></div>
                        <div class="h-1 w-full bg-slate-50 rounded-full overflow-hidden"><div class="h-full bg-primary transition-all duration-500" style="width: ${progress}%"></div></div>
                    </div>
                `;
            }).join('');
        }

        function renderPagination(data) {
            const container = document.getElementById('pagination-container');
            if (!data || data.total === 0) {
                container.innerHTML = `<span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">No Batches Found</span>`;
                return;
            }

            let html = `<span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Showing ${data.from || 0} to ${data.to || 0} of ${data.total} entries</span>`;
            html += `<div class="flex items-center gap-1">`;

            // Previous Button
            html += `<button onclick="fetchBatches(${data.current_page - 1})" ${data.current_page === 1 ? 'disabled' : ''} class="h-8 w-8 flex items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:bg-slate-100 disabled:opacity-30 disabled:cursor-not-allowed">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </button>`;

            // Smart Page Numbers
            const maxVisible = 5;
            let startPage = Math.max(1, data.current_page - 2);
            let endPage = Math.min(data.last_page, startPage + maxVisible - 1);

            if (endPage - startPage < maxVisible - 1) {
                startPage = Math.max(1, endPage - maxVisible + 1);
            }

            if (startPage > 1) {
                html += `<button onclick="fetchBatches(1)" class="h-8 w-8 text-[10px] font-bold rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-100 transition-all">1</button>`;
                if (startPage > 2) html += `<span class="px-1 text-slate-300 text-[10px] font-bold">...</span>`;
            }

            for (let i = startPage; i <= endPage; i++) {
                const isActive = i === data.current_page;
                const activeClass = isActive ? 'bg-primary text-white shadow-md shadow-orange-500/20' : 'bg-slate-50 text-slate-600 hover:bg-slate-100';
                html += `<button onclick="fetchBatches(${i})" class="h-8 w-8 text-[10px] font-bold rounded-lg transition-all ${activeClass}">${i}</button>`;
            }

            if (endPage < data.last_page) {
                if (endPage < data.last_page - 1) html += `<span class="px-1 text-slate-300 text-[10px] font-bold">...</span>`;
                html += `<button onclick="fetchBatches(${data.last_page})" class="h-8 w-8 text-[10px] font-bold rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-100 transition-all">${data.last_page}</button>`;
            }

            // Next Button
            html += `<button onclick="fetchBatches(${data.current_page + 1})" ${data.current_page === data.last_page ? 'disabled' : ''} class="h-8 w-8 flex items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:bg-slate-100 disabled:opacity-30 disabled:cursor-not-allowed">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </button>`;

            html += `</div>`;
            container.innerHTML = html;
        }

        function toggleFormView(show, isEdit = false) {
            const listView = document.getElementById('list-view');
            const formView = document.getElementById('form-view');
            if (show) {
                listView.classList.add('hidden');
                formView.classList.remove('hidden');
                if (!isEdit) {
                    document.getElementById('batch-form').reset();
                    document.getElementById('batch-id').value = '';
                    document.getElementById('field-fees').value = '';
                    document.getElementById('field-description').value = '';
                    document.getElementById('form-title').innerText = 'Manage Batch';
                    document.querySelectorAll('.day-checkbox').forEach(cb => cb.checked = false);
                }
            } else {
                listView.classList.remove('hidden');
                formView.classList.add('hidden');
            }
        }

        function openEditForm(batch) {
            toggleFormView(true, true);
            document.getElementById('form-title').innerText = 'Manage Batch';
            document.getElementById('batch-id').value = batch.id;
            document.getElementById('field-name').value = batch.name;
            document.getElementById('field-subject').value = batch.subject;
            document.getElementById('field-fees').value = batch.fees || '';
            document.getElementById('field-description').value = batch.description || '';
            document.getElementById('field-capacity').value = batch.max_capacity || 30;
            document.getElementById('field-start').value = batch.start_time;
            document.getElementById('field-end').value = batch.end_time;
            const days = batch.days || [];
            document.querySelectorAll('.day-checkbox').forEach(cb => cb.checked = days.includes(cb.value));
        }

        document.getElementById('batch-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const id = formData.get('id');
            const days = Array.from(form.querySelectorAll('.day-checkbox:checked')).map(cb => cb.value);
            const payload = Object.fromEntries(formData.entries());
            payload.days = days;
            toggleSubmitLoading(true);
            try {
                const url = id ? `${API_URL}/${id}` : API_URL;
                const resp = await fetch(url, {
                    method: id ? 'PUT' : 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const result = await resp.json();
                if (result.status === 'success') {
                    showToast(result.message);
                    toggleFormView(false);
                    fetchBatches();
                }
            } catch (error) { showToast('Error saving batch', 'error'); }
            finally { toggleSubmitLoading(false); }
        });

        async function deleteBatch(id, name) {
            if (!confirm(`Are you sure you want to delete ${name}?`)) return;
            try {
                const resp = await fetch(`${API_URL}/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
                });
                const result = await resp.json();
                if (result.status === 'success') { showToast(result.message); fetchBatches(); }
            } catch (error) { showToast('Delete failed', 'error'); }
        }

        function toggleLoader(show) { document.getElementById('loading-spinner').classList.toggle('hidden', !show); }
        function toggleSubmitLoading(show) {
            document.getElementById('btn-loader').classList.toggle('hidden', !show);
            document.getElementById('submit-btn').disabled = show;
        }
    </script>
@endsection