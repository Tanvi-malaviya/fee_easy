@extends('layouts.institute')

@section('content')
    @php
        $staffList = \App\Models\Staff::where('institute_id', Auth::guard('institute')->id())->orderBy('full_name')->get();
    @endphp
    <div class="max-w-[1600px] mx-auto ">

        <!-- MAIN LIST VIEW -->
        <div id="list-view" class="space-y-2 animate-in fade-in duration-500">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="pt-1">
                    <h1 class="text-xl font-semibold text-slate-800 tracking-tight leading-tight">Batch Management</h1>
                    <p class="text-xs text-slate-400 mt-0.5 font-medium">Manage academic cohorts & enrollment status.</p>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <div
                        class="bg-white px-5 py-2 rounded-[1rem] border border-slate-100 shadow-sm flex flex-col items-center min-w-[120px]">
                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total Batches</p>
                        <h3 id="stat-total-batches" class="text-lg font-bold text-slate-800">0</h3>
                    </div>
                    <div
                        class="bg-white px-5 py-2 rounded-[1rem] border border-slate-100 shadow-sm flex flex-col items-center min-w-[120px]">
                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total Students</p>
                        <h3 id="stat-total-students" class="text-lg font-bold text-slate-800">0</h3>
                    </div>
                </div>
            </div>

            <div
                class="bg-white p-1.5 rounded-[1rem] border border-slate-100 shadow-sm flex flex-col md:flex-row items-center gap-2">
                <div class="relative flex-1 group w-full md:w-auto flex items-center">
                    <input type="text" id="batch-search" placeholder="Search batches..."
                        onkeydown="if(event.key === 'Enter') executeSearch()"
                        class="w-full pl-4 pr-24 py-2.5 bg-slate-50/50 border border-slate-100 rounded-xl text-[12px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all">
                    <button onclick="executeSearch()"
                        class="absolute right-1.5 top-1.5 bottom-1.5 px-4 bg-primary text-white rounded-lg text-[10px] font-bold hover:opacity-90 transition-all">
                        Search
                    </button>
                </div>
                <div class="flex items-center gap-2 w-full md:w-auto justify-between md:justify-start">
                    <button onclick="exportBatches()"
                        class="btn-white btn-md flex-1 md:flex-none flex justify-center items-center">Export</button>
                    @if(Auth::guard('institute')->user()->hasActiveSubscription())
                        <button onclick="toggleFormView(true)"
                            class="btn-brand btn-md whitespace-nowrap bg-primary hover:bg-primary flex-1 md:flex-none flex justify-center items-center">
                            New batch
                        </button>
                    @else
                        <button onclick="handleExpiredSubscription(event)"
                            class="btn-brand btn-md whitespace-nowrap bg-primary hover:bg-primary flex-1 md:flex-none flex justify-center items-center">
                            New batch
                        </button>
                    @endif
                </div>
            </div>

            <div id="batch-grid"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-2 relative items-start">
                <div id="loading-spinner"
                    class="absolute inset-0 z-50 bg-white/60 backdrop-blur-[2px] hidden flex items-center justify-center rounded-[1rem]">
                    <div class="h-10 w-10 border-4 border-slate-100 border-t-primary rounded-full animate-spin"></div>
                </div>
            </div>

            <div id="pagination-container"
                class="bg-white p-4 rounded-[1rem] border border-slate-100 shadow-sm flex items-center justify-between">
            </div>
        </div>

        <!-- MANAGE BATCH MODAL -->
        <div id="form-modal" class="fixed inset-0 z-[100] hidden">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"
                onclick="toggleFormView(false)"></div>

            <!-- Modal Content -->
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[650px] max-h-[95vh] overflow-y-auto scrollbar-hide">
                <style>
                    .scrollbar-hide::-webkit-scrollbar {
                        display: none;
                    }

                    .scrollbar-hide {
                        -ms-overflow-style: none;
                        scrollbar-width: none;
                    }
                </style>
                <div
                    class="bg-white rounded-[1.5rem] shadow-2xl border border-slate-100 overflow-hidden animate-in zoom-in-95 fade-in duration-300">
                    <!-- Header -->
                    <div
                        class="px-6 py-3 border-b border-slate-50 flex items-center justify-between bg-white sticky top-0 z-10">
                        <div>
                            <h1 id="form-title" class="text-lg font-bold text-slate-800 tracking-tight">Manage Batch</h1>
                        </div>
                        <button type="button" onclick="toggleFormView(false)"
                            class="h-8 w-8 flex items-center justify-center rounded-full hover:bg-slate-50 text-slate-400 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form id="batch-form" class="px-6 py-2 space-y-2">
                        <input type="hidden" id="batch-id" name="id">

                        <!-- General Information -->
                        <div class="space-y-2">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ml-1">Batch
                                        Name</label>
                                    <input type="text" name="name" id="field-name" required placeholder="e.g. Maths 2024-A"
                                        class="w-full px-3 py-2 bg-slate-50/50 border border-slate-100 rounded-lg text-[11px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all">
                                </div>
                                <div class="space-y-1">
                                    <label
                                        class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ml-1">Subject</label>
                                    <input type="text" name="subject" id="field-subject" required
                                        placeholder="Select Subject"
                                        class="w-full px-3 py-2 bg-slate-50/50 border border-slate-100 rounded-lg text-[11px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ml-1">Fees
                                        (₹)</label>
                                    <input type="text" name="fees" id="field-fees" required placeholder="0"
                                        inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                        class="w-full px-3 py-2 bg-slate-50/50 border border-slate-100 rounded-lg text-[11px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all">
                                </div>
                                <div class="space-y-1">
                                    <label
                                        class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ml-1">Description</label>
                                    <input type="text" name="description" id="field-description"
                                        placeholder="Brief details..."
                                        class="w-full px-3 py-2 bg-slate-50/50 border border-slate-100 rounded-lg text-[11px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Section -->
                        <div class="space-y-2 pt-1">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ml-1">Start
                                        Time</label>
                                    <input type="time" name="start_time" id="field-start" required
                                        onclick="this.showPicker()"
                                        class="w-full px-3 py-2 bg-slate-50/50 border border-slate-100 rounded-lg text-[11px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all cursor-pointer">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ml-1">End
                                        Time</label>
                                    <input type="time" name="end_time" id="field-end" required
                                        onclick="this.showPicker()"
                                        class="w-full px-3 py-2 bg-slate-50/50 border border-slate-100 rounded-lg text-[11px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all cursor-pointer">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-3">
                                <div class="space-y-1">
                                    <label
                                        class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ml-1">Days</label>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                                            <label class="relative cursor-pointer group">
                                                <input type="checkbox" name="days[]" value="{{ $day }}"
                                                    class="peer sr-only day-checkbox">
                                                <div
                                                    class="px-3 py-1.5 bg-white border border-slate-100 rounded-lg text-[9px] font-bold text-slate-400 transition-all peer-checked:bg-secondary/10 peer-checked:text-secondary peer-checked:border-secondary group-hover:bg-slate-50">
                                                    {{ $day }}
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label
                                        class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ml-1">Classroom
                                        / Venue</label>
                                    <input type="text" name="classroom" id="field-classroom"
                                        placeholder="e.g. Room 101, Main Hall"
                                        class="w-full px-3 py-2 bg-slate-50/50 border border-slate-100 rounded-lg text-[11px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all">
                                </div>
                                <div class="space-y-1 relative">
                                    <label
                                        class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ml-1">Assigned
                                        Staff</label>
                                    <button type="button" onclick="toggleBatchModalDropdown('staff')"
                                        class="w-full px-3 py-2 bg-slate-50/50 border border-slate-100 rounded-lg text-[11px] font-bold text-left flex items-center justify-between hover:border-brand-800 transition-all">
                                        <span id="modal-staff-label" class="text-slate-400">Select Staff</span>
                                        <svg id="modal-staff-chevron"
                                            class="w-3.5 h-3.5 text-slate-400 transition-transform" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div id="modal-staff-menu"
                                        class="absolute bottom-full mb-1 z-[110] w-full bg-white border border-slate-200 rounded-lg shadow-xl overflow-hidden hidden transform origin-bottom transition-all">
                                        <div class="py-1 max-h-48 overflow-y-auto custom-scrollbar">
                                            @foreach($staffList as $staff)
                                                <button type="button"
                                                    onclick="selectBatchModalOption('staff', '{{ $staff->id }}', '{{ $staff->full_name }}')"
                                                    class="w-full text-left px-3 py-2 text-[11px] font-bold text-slate-600 hover:bg-slate-50 hover:text-brand-800 transition-colors">
                                                    {{ $staff->full_name }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                    <input type="hidden" name="staff_id" id="field-staff" value="">
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div
                            class="pt-4 border-t border-slate-50 flex items-center justify-end gap-2 bg-white sticky bottom-0 pb-1">
                            <button type="button" onclick="toggleFormView(false)" class="btn-white btn-md">Cancel</button>
                            <button type="submit" id="submit-btn" class="btn-brand btn-md bg-primary hover:bg-primary">
                                <span id="btn-text ">Save Batch</span>
                                <span id="btn-loader"
                                    class="hidden h-3 w-3 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- DELETE CONFIRMATION MODAL -->
        <div id="delete-modal" class="fixed inset-0 z-[120] hidden">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"
                onclick="toggleDeleteModal(false)"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[450px]">
                <div
                    class="bg-white rounded-[1.5rem] shadow-2xl border-t-4 border-primary overflow-hidden animate-in zoom-in-95 fade-in duration-300">
                    <div class="p-8">
                        <div class="flex gap-4">
                            <div
                                class="h-12 w-12 bg-primary-50 text-primary rounded-full flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-slate-800 mb-2">Delete Batch?</h3>
                                <p class="text-[12px] text-slate-500 font-medium leading-relaxed mb-6">Are you sure you want
                                    to permanently remove <span id="delete-batch-name"
                                        class="font-bold text-slate-800"></span>? This action cannot be undone and will
                                    erase all academic and financial history.</p>
                                <div class="flex items-center gap-3">
                                    <button type="button" onclick="toggleDeleteModal(false)"
                                        class="flex-1 px-4 py-2.5 bg-white border border-slate-200 text-slate-500 rounded-lg text-[10px] font-bold hover:bg-slate-50 transition-all">Cancel</button>
                                    <button type="button" id="confirm-delete-btn"
                                        class="flex-[1.5] py-2.5 bg-primary text-white rounded-xl font-bold text-[12px] shadow-lg shadow-rose-900/10 hover:bg-primary transition-all">Yes,
                                        Delete Batch</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Empty State Template -->
    <template id="batches-empty-state">
        <x-empty-state title="No batches found" subtitle="Try adjusting your filters or add a new batch." icon="batches" />
    </template>

    <script>
        const API_URL = "/api/v1/institute/batches";
        const CSRF_TOKEN = "{{ csrf_token() }}";
        const staffListJs = @json($staffList);

        document.addEventListener('DOMContentLoaded', () => fetchBatches());

        async function fetchBatches(page = 1) {
            toggleLoader(true);
            const searchVal = document.getElementById('batch-search').value;
            try {
                const response = await fetch(`${API_URL}?page=${page}&search=${encodeURIComponent(searchVal)}`, {
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

        function executeSearch() {
            fetchBatches(1);
        }

        function exportBatches() {
            window.location.href = `${API_URL}/export?api_token=${CSRF_TOKEN}`; // If using token in URL or just a simple GET
            // For Sanctum, a simple window.open might work if cookies are used, 
            // or we might need to handle it via fetch if custom headers are needed.
            // Since it's a GET request to a stream, window.open is easiest.
            window.open(`${API_URL}/export`, '_blank');
        }

        function formatTime12Hour(timeStr) {
            if (!timeStr) return '--:--';
            if (timeStr.includes('AM') || timeStr.includes('PM')) return timeStr;
            const parts = timeStr.split(':');
            if (parts.length < 2) return timeStr;
            let hours = parseInt(parts[0], 10);
            const minutes = parts[1];
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            return `${hours}:${minutes} ${ampm}`;
        }

        function convert12To24(time12h) {
            if (!time12h) return '';
            if (!time12h.includes('AM') && !time12h.includes('PM')) return time12h;
            const [time, modifier] = time12h.split(' ');
            let [hours, minutes] = time.split(':');
            hours = parseInt(hours, 10);
            if (modifier === 'PM' && hours < 12) hours = hours + 12;
            if (modifier === 'AM' && hours === 12) hours = 0;
            return `${String(hours).padStart(2, '0')}:${minutes}`;
        }

        function renderBatches(items) {
            const container = document.getElementById('batch-grid');
            if (items.length === 0) {
                container.innerHTML = document.getElementById('batches-empty-state').innerHTML;
                return;
            }

            const icons = ['💻', '🎨', '🧪', '📈', '🏛️', '🛡️', '📱', '🧠'];
            container.innerHTML = items.map((batch, idx) => {
                const icon = icons[idx % icons.length];
                const studentsCount = batch.students_count || 0;
                const isClosed = batch.status === 'closed';
                const statusBadge = isClosed
                    ? '<span class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded-lg text-[7px] font-bold uppercase tracking-widest">Closed</span>'
                    : '<span class="px-2 py-0.5 bg-emerald-50 text-emerald-500 rounded-lg text-[7px] font-bold uppercase tracking-widest">Active</span>';

                let staffHtml = '';
                if (batch.staff) {
                    staffHtml = `<div class="flex items-center gap-2 text-slate-500"><svg class="w-3 h-3 " fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg><span class="text-[10px] font-bold">Staff: ${batch.staff.full_name}</span></div>`;
                }

                return `
                                                <div class="bg-white rounded-xl border border-slate-100 shadow-sm hover:shadow-md  transition-all group flex flex-col cursor-pointer" onclick="window.location.href='/institute/batches/${batch.id}'">
                                                    <!-- Card Body -->
                                                    <div class="p-4 flex-1">
                                                        <div class="flex items-start justify-between mb-3">
                                                            <div class="h-10 w-10 bg-slate-50 rounded-xl flex items-center justify-center text-lg">${icon}</div>
                                                            ${statusBadge}
                                                        </div>
                                                        <div class="space-y-1 mb-1">
                                                            <h4 class="text-sm font-bold text-slate-800 leading-tight">${batch.name}</h4>
                                                            <div class="flex items-center gap-2">
                                                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">${batch.subject}</span>
                                                                <span class="px-1.5 py-0.5 bg-blue-50 text-blue-600 rounded-md text-[8px] font-bold uppercase">₹${batch.fees || '0'}</span>
                                                            </div>
                                                        </div>
                                                        <p class="text-[10px] font-bold text-slate-400 line-clamp-2 mb-4 leading-relaxed">${batch.description || 'No description provided.'}</p>
                                                        <div class="space-y-2 text-slate-500">
                                                            <div class="flex items-center gap-2"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span class="text-[10px] font-bold">${formatTime12Hour(batch.start_time)} - ${formatTime12Hour(batch.end_time)}</span></div>
                                                            <div class="flex items-center gap-2"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><div class="flex flex-wrap gap-1">${(batch.days || []).map(day => `<span class="text-[8px] font-bold text-slate-700">${day}</span>`).join('')}</div></div>
                                                            ${staffHtml}
                                                        </div>
                                                    </div>
                                                    <!-- Footer Actions -->
                                                    <div class="flex items-center justify-between p-3 bg-slate-50/80 rounded-b-xl border-t border-slate-100">
                                                        <a href="/institute/batches/${batch.id}" class="action-btn flex items-center text-[#006b74]  font-bold text-[12px]  transition-all">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                            View
                                                        </a>
                                                        <div class="flex items-center gap-3">
                                                            <button type="button" data-batch="${encodeURIComponent(JSON.stringify(batch))}" onclick="event.stopPropagation(); handleEditClick(this)" class="action-btn text-blue-500 transition-all" title="Edit">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                            </button>
                                                            <button type="button" data-id="${batch.id}" data-name="${encodeURIComponent(batch.name)}" onclick="event.stopPropagation(); handleDeleteClick(this)" class="action-btn text-rose-500 transition-all" title="Delete">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            `;
            }).join('');
        }

        function renderPagination(data) {
            const container = document.getElementById('pagination-container');
            if (!data || data.last_page <= 1) {
                container.classList.add('hidden');
                return;
            }
            container.classList.remove('hidden');

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
            const modal = document.getElementById('form-modal');
            if (show) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent scroll
                if (!isEdit) {
                    document.getElementById('batch-form').reset();
                    document.getElementById('batch-id').value = '';
                    document.getElementById('field-fees').value = '';
                    document.getElementById('field-description').value = '';
                    document.getElementById('form-title').innerText = 'Manage Batch';
                    document.querySelectorAll('.day-checkbox').forEach(cb => cb.checked = false);

                    // Reset custom staff select dropdown
                    document.getElementById('field-staff').value = '';
                    const labelEl = document.getElementById('modal-staff-label');
                    labelEl.innerText = 'Select Staff';
                    labelEl.classList.add('text-slate-400');
                    labelEl.classList.remove('text-slate-800');
                }
            } else {
                modal.classList.add('hidden');
                document.body.style.overflow = ''; // Restore scroll
                // Hide custom menu if open
                document.getElementById('modal-staff-menu').classList.add('hidden');
                document.getElementById('modal-staff-chevron').classList.remove('rotate-180');
            }
        }

        window.toggleBatchModalDropdown = (type) => {
            const menu = document.getElementById(`modal-${type}-menu`);
            const chevron = document.getElementById(`modal-${type}-chevron`);

            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            } else {
                menu.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        };

        window.selectBatchModalOption = (type, value, label) => {
            document.getElementById(`field-${type}`).value = value;
            const labelEl = document.getElementById(`modal-${type}-label`);
            labelEl.innerText = label;
            if (value === '') {
                labelEl.classList.add('text-slate-400');
                labelEl.classList.remove('text-slate-800');
            } else {
                labelEl.classList.remove('text-slate-400');
                labelEl.classList.add('text-slate-800');
            }
            document.getElementById(`modal-${type}-menu`).classList.add('hidden');
            document.getElementById(`modal-${type}-chevron`).classList.remove('rotate-180');
        };

        function openEditForm(batch) {
            toggleFormView(true, true);
            document.getElementById('form-title').innerText = 'Manage Batch';
            document.getElementById('batch-id').value = batch.id;
            document.getElementById('field-name').value = batch.name;
            document.getElementById('field-subject').value = batch.subject;
            document.getElementById('field-fees').value = batch.fees || '';
            document.getElementById('field-description').value = batch.description || '';
            document.getElementById('field-start').value = convert12To24(batch.start_time) || '';
            document.getElementById('field-end').value = convert12To24(batch.end_time) || '';

            document.getElementById('field-classroom').value = batch.classroom || '';
            const days = batch.days || [];
            document.querySelectorAll('.day-checkbox').forEach(cb => cb.checked = days.includes(cb.value));

            // Load and pre-select staff from batch object
            const batchStaffId = batch.staff_id || '';
            document.getElementById('field-staff').value = batchStaffId;
            const staffObj = staffListJs.find(s => s.id == batchStaffId);
            const labelEl = document.getElementById('modal-staff-label');
            if (staffObj) {
                labelEl.innerText = staffObj.full_name;
                labelEl.classList.remove('text-slate-400');
                labelEl.classList.add('text-slate-800');
            } else {
                labelEl.innerText = 'Select Staff';
                labelEl.classList.add('text-slate-400');
                labelEl.classList.remove('text-slate-800');
            }
        }

        document.getElementById('batch-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const id = formData.get('id');
            const days = Array.from(form.querySelectorAll('.day-checkbox:checked')).map(cb => cb.value);

            if (days.length === 0) {
                showToast('Please select at least one day.', 'error');
                return;
            }

            const staffId = document.getElementById('field-staff').value;
            if (!staffId) {
                showToast('Please select an assigned staff member.', 'error');
                return;
            }

            // Clean payload: remove days[] and ensure days is an array
            const payload = Object.fromEntries(formData.entries());
            delete payload['days[]'];
            payload.days = days;

            toggleSubmitLoading(true);
            try {
                // Use method spoofing for PUT if ID exists
                const method = id ? 'PUT' : 'POST';
                const url = id ? `${API_URL}/${id}` : API_URL;

                const resp = await fetch(url, {
                    method: 'POST', // Always use POST for spoofing
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        ...payload,
                        _method: method // Laravel method spoofing
                    })
                });

                const result = await resp.json();

                if (resp.ok && result.status === 'success') {
                    showToast(result.message || 'Batch saved successfully');
                    toggleFormView(false);
                    fetchBatches();
                } else {
                    // Handle validation errors or server errors
                    const errorMsg = result.message || (result.errors ? Object.values(result.errors).flat()[0] : 'Error saving batch');
                    showToast(errorMsg, 'error');
                }
            } catch (error) {
                console.error('Save Error:', error);
                showToast('Network error or server unavailable', 'error');
            }
            finally { toggleSubmitLoading(false); }
        });

        let batchToDelete = null;
        function toggleDeleteModal(show, id = null, name = '') {
            const modal = document.getElementById('delete-modal');
            if (show) {
                batchToDelete = id;
                document.getElementById('delete-batch-name').innerText = name;
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                batchToDelete = null;
            }
        }

        document.getElementById('confirm-delete-btn').addEventListener('click', async () => {
            if (!batchToDelete) return;
            const btn = document.getElementById('confirm-delete-btn');
            const originalText = btn.innerText;
            btn.innerText = 'Deleting...';
            btn.disabled = true;

            try {
                const resp = await fetch(`${API_URL}/${batchToDelete}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
                });
                const result = await resp.json();
                if (result.status === 'success') {
                    showToast('Batch deleted successfully');
                    toggleDeleteModal(false);
                    fetchBatches();
                }
            } catch (error) {
                showToast('Delete failed', 'error');
            } finally {
                btn.innerText = originalText;
                btn.disabled = false;
            }
        });

        function deleteBatch(id, name) {
            toggleDeleteModal(true, id, name);
        }

        function handleEditClick(btn) {
            const batchDataStr = btn.getAttribute('data-batch');
            const batch = JSON.parse(decodeURIComponent(batchDataStr));
            openEditForm(batch);
        }

        function handleDeleteClick(btn) {
            const id = btn.getAttribute('data-id');
            const name = decodeURIComponent(btn.getAttribute('data-name'));
            deleteBatch(id, name);
        }

        function toggleLoader(show) { document.getElementById('loading-spinner').classList.toggle('hidden', !show); }
        function toggleSubmitLoading(show) {
            document.getElementById('btn-loader').classList.toggle('hidden', !show);
            document.getElementById('submit-btn').disabled = show;
        }
    </script>

    @push('modals')
        <!-- MANAGE BATCH MODAL -->
        <div id="form-modal" class="fixed inset-0 z-[100] hidden">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="toggleFormView(false)">
            </div>

            <!-- Modal Content -->
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[650px] max-h-[95vh] overflow-y-auto scrollbar-hide">
                <style>
                    .scrollbar-hide::-webkit-scrollbar {
                        display: none;
                    }

                    .scrollbar-hide {
                        -ms-overflow-style: none;
                        scrollbar-width: none;
                    }
                </style>
                <div
                    class="bg-white rounded-[1.5rem] shadow-2xl border border-slate-100 overflow-hidden animate-in zoom-in-95 fade-in duration-300">
                    <!-- Header -->
                    <div
                        class="px-6 py-3 border-b border-slate-50 flex items-center justify-between bg-white sticky top-0 z-10">
                        <div>
                            <h1 id="form-title" class="text-lg font-bold text-slate-800 tracking-tight">Manage Batch</h1>
                        </div>
                        <button type="button" onclick="toggleFormView(false)"
                            class="h-8 w-8 flex items-center justify-center rounded-full hover:bg-slate-50 text-slate-400 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form id="batch-form" class="px-6 py-2 space-y-2">
                        <input type="hidden" id="batch-id" name="id">

                        <!-- General Information -->
                        <div class="space-y-2">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ml-1">Batch
                                        Name</label>
                                    <input type="text" name="name" id="field-name" required placeholder="e.g. Maths 2024-A"
                                        class="w-full px-3 py-2 bg-slate-50/50 border border-slate-100 rounded-lg text-[11px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all">
                                </div>
                                <div class="space-y-1">
                                    <label
                                        class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ml-1">Subject</label>
                                    <input type="text" name="subject" id="field-subject" required placeholder="Select Subject"
                                        class="w-full px-3 py-2 bg-slate-50/50 border border-slate-100 rounded-lg text-[11px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ml-1">Fees
                                        (₹)</label>
                                    <input type="text" name="fees" id="field-fees" required placeholder="0" inputmode="numeric"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                        class="w-full px-3 py-2 bg-slate-50/50 border border-slate-100 rounded-lg text-[11px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all">
                                </div>
                                <div class="space-y-1">
                                    <label
                                        class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ml-1">Description</label>
                                    <input type="text" name="description" id="field-description" placeholder="Brief details..."
                                        class="w-full px-3 py-2 bg-slate-50/50 border border-slate-100 rounded-lg text-[11px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Section -->
                        <div class="space-y-2 pt-1">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ml-1">Start
                                        Time</label>
                                    <input type="time" name="start_time" id="field-start" required
                                        class="w-full px-3 py-2 bg-slate-50/50 border border-slate-100 rounded-lg text-[11px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ml-1">End
                                        Time</label>
                                    <input type="time" name="end_time" id="field-end" required
                                        class="w-full px-3 py-2 bg-slate-50/50 border border-slate-100 rounded-lg text-[11px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-3">
                                <div class="space-y-1">
                                    <label
                                        class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ml-1">Days</label>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                                            <label class="relative cursor-pointer group">
                                                <input type="checkbox" name="days[]" value="{{ $day }}"
                                                    class="peer sr-only day-checkbox">
                                                <div
                                                    class="px-3 py-1.5 bg-white border border-slate-100 rounded-lg text-[9px] font-bold text-slate-400 transition-all peer-checked:bg-secondary/10 peer-checked:text-secondary peer-checked:border-secondary group-hover:bg-slate-50">
                                                    {{ $day }}
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ml-1">Classroom
                                        / Venue</label>
                                    <input type="text" name="classroom" id="field-classroom"
                                        placeholder="e.g. Room 101, Main Hall"
                                        class="w-full px-3 py-2 bg-slate-50/50 border border-slate-100 rounded-lg text-[11px] font-bold outline-none focus:ring-4 focus:ring-primary/5 transition-all">
                                </div>
                                <div class="space-y-1 relative">
                                    <label class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ml-1">Assigned
                                        Staff</label>
                                    <button type="button" onclick="toggleBatchModalDropdown('staff')"
                                        class="w-full px-3 py-2 bg-slate-50/50 border border-slate-100 rounded-lg text-[11px] font-bold text-left flex items-center justify-between hover:border-brand-800 transition-all">
                                        <span id="modal-staff-label" class="text-slate-400">Select Staff</span>
                                        <svg id="modal-staff-chevron" class="w-3.5 h-3.5 text-slate-400 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div id="modal-staff-menu"
                                        class="absolute bottom-full mb-1 z-[110] w-full bg-white border border-slate-200 rounded-lg shadow-xl overflow-hidden hidden transform origin-bottom transition-all">
                                        <div class="py-1 max-h-48 overflow-y-auto custom-scrollbar">
                                            <button type="button" onclick="selectBatchModalOption('staff', '', 'None')"
                                                class="w-full text-left px-3 py-2 text-[11px] font-bold text-slate-600 hover:bg-slate-50 hover:text-brand-800 transition-colors">
                                                None
                                            </button>
                                            @foreach($staffList as $staff)
                                                <button type="button"
                                                    onclick="selectBatchModalOption('staff', '{{ $staff->id }}', '{{ $staff->full_name }}')"
                                                    class="w-full text-left px-3 py-2 text-[11px] font-bold text-slate-600 hover:bg-slate-50 hover:text-brand-800 transition-colors">
                                                    {{ $staff->full_name }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                    <input type="hidden" name="staff_id" id="field-staff" value="">
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div
                            class="pt-4 border-t border-slate-50 flex items-center justify-end gap-2 bg-white sticky bottom-0 pb-1">
                            <button type="button" onclick="toggleFormView(false)" class="btn-white btn-md">Cancel</button>
                            <button type="submit" id="submit-btn" class="btn-brand btn-md bg-primary hover:bg-primary">
                                <span id="btn-text ">Save Batch</span>
                                <span id="btn-loader"
                                    class="hidden h-3 w-3 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- DELETE BATCH CONFIRMATION MODAL -->
        <div id="delete-modal" class="fixed inset-0 z-[120] hidden">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"
                onclick="toggleDeleteModal(false)"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[450px]">
                <div
                    class="bg-white rounded-[1.5rem] shadow-2xl border-t-4 border-primary overflow-hidden animate-in zoom-in-95 fade-in duration-300">
                    <div class="p-8">
                        <div class="flex gap-4">
                            <div
                                class="h-12 w-12 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-slate-800 mb-2">Delete Batch?</h3>
                                <p class="text-[12px] text-slate-500 font-medium leading-relaxed mb-6">Are you sure you want
                                    to delete <span id="delete-batch-name" class="font-bold text-slate-800"></span>? This will
                                    archive the batch and tag it as 'Closed'. It will not be permanently deleted.</p>
                                <div class="flex items-center gap-3">
                                    <button type="button" onclick="toggleDeleteModal(false)"
                                        class="flex-1 px-4 py-2.5 bg-white border border-slate-200 text-slate-500 rounded-lg text-[10px] font-bold hover:bg-slate-50 transition-all">Cancel</button>
                                    <button type="button" id="confirm-delete-btn"
                                        class="flex-[1.5] py-2.5 bg-primary text-white rounded-xl font-bold text-[12px] shadow-lg shadow-rose-900/10 hover:bg-primary transition-all">Yes,
                                        Delete Batch</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endpush
@endsection