@extends('layouts.institute')

@section('content')
    <div class="space-y-2 max-w-[1600px] mx-auto pb-6 overflow-x-hidden">
        <!-- Toast Notifications Container -->
        <div id="toast-container" class="fixed top-24 right-4 md:right-8 z-[1000] space-y-4"></div>

        <!-- Page Header & Info -->
        <div class="pt-0 px-4 md:px-8 animate-in fade-in duration-500">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-3 md:mb-4">
                <div class="flex-1">
                    <h1 class="text-2xl md:text-4xl font-medium text-slate-800 tracking-tight leading-tight">Updates Hub
                    </h1>
                    <p class="text-xs md:text-sm text-slate-400 mt-1 font-medium leading-relaxed max-w-2xl">Manage and
                        broadcast critical institutional communications, academic calendars, and financial notices to your
                        campus community.</p>
                </div>
            </div>

            <div class="flex flex-col md:flex-row items-center gap-4 mb-4 md:mb-4">
                <div class="relative flex-1 w-full group">
                    <input type="text" id="feed-search" placeholder="Search updates..."
                        class="w-full pl-10 md:pl-12 pr-28 py-2.5 md:py-3 bg-white border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none shadow-sm">
                    <svg class="w-4 h-4 md:w-5 h-5 absolute left-3.5 md:left-4 top-3 md:top-3.5 text-slate-400 group-focus-within:text-primary transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <button type="button" onclick="performSearch()"
                        class="absolute right-1.5 top-1.5 bottom-1.5 px-4 bg-primary text-white rounded-lg text-[10px] font-black uppercase tracking-widest  active:scale-95 transition-all z-20 cursor-pointer">
                        Search
                    </button>
                </div>
                <button onclick="openUpdateModal()"
                    class="w-full md:w-auto px-4 md:px-6 py-2.5 md:py-3 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-orange-900/20 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 md:w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    New Update
                </button>
            </div>

            <!-- Timeline Container -->
            <div class="relative w-full">
                <!-- Vertical Line -->
                <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-slate-200 hidden sm:block"></div>

                <div id="update-feed" class="space-y-3 relative">
                    <!-- Data populated via AJAX -->
                    <div class="py-20 text-center text-slate-300 italic text-xs">Loading feed...</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Update Modal -->
    <div id="update-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden p-4 sm:p-6">
        <div onclick="closeUpdateModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div
            class="bg-white w-full max-w-4xl rounded-[1.5rem] shadow-2xl relative z-10 flex flex-col overflow-hidden animate-in fade-in zoom-in duration-300 max-h-[90vh]">
            <!-- Modal Header (Fixed) -->
            <div class="p-4 sm:p-5 sm:pb-2 border-b border-slate-50 flex items-center justify-between bg-slate-50/20 shrink-0">
                <div>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-800 tracking-tight">Post New Update</h2>
                    <p class="text-[10px] sm:text-[11px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">
                        Communication Hub</p>
                </div>
                <button onclick="closeUpdateModal()"
                    class="h-8 w-8 bg-white border border-slate-100 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-500 shadow-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-4 sm:p-6 sm:pt-2 overflow-y-auto no-scrollbar">
                <form id="update-form" class="space-y-3">
                    <!-- Audience & Category Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="space-y-1">
                            <label
                                class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Recipient</label>
                            <select name="recipient" id="recipient-select" onchange="handleTargetChange()" required
                                class="w-full px-3 py-3 sm:py-2.5 bg-orange-50/50 border border-orange-100 rounded-xl text-xs font-bold text-orange-600 outline-none focus:ring-2 focus:ring-orange-500/20">
                                <option value="students">Students</option>
                                <option value="parents">Parents</option>
                                <option value="both">Both (Students & Parents)</option>
                            </select>
                        </div>

                        <div id="student-audience-container" class="space-y-1">
                            <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Target
                                Audience</label>
                            <select name="target_type" id="target-type-select" onchange="handleTargetChange()" required
                                class="w-full px-3 py-3 sm:py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold outline-none">
                                <option value="all">All Students</option>
                                <option value="batch">Specific Batch</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label
                                class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Category</label>
                            <select name="category" required
                                class="w-full px-3 py-3 sm:py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold outline-none">
                                <option value="Academic">Academic</option>
                                <option value="Administrative">Administrative</option>
                                <option value="Emergency">Emergency</option>
                                <option value="Event">Event</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- Detail Row -->
                    <div id="audience-detail-row" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div id="target-detail-container" class="col-span-1">
                            <div id="batch-selector-container" class="space-y-1 hidden animate-in slide-in-from-top-1">
                                <label
                                    class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Select
                                    Batch</label>
                                <select name="batch_id" id="modal-batch-select"
                                    class="w-full px-3 py-3 sm:py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold outline-none">
                                    <option value="">Choose Batch...</option>
                                </select>
                            </div>

                            <div id="standard-selector-container" class="space-y-1 hidden animate-in slide-in-from-top-1">
                                <label
                                    class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Select
                                    Standard</label>
                                <select name="standard"
                                    class="w-full px-3 py-3 sm:py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold outline-none">
                                    <option value="">Choose Standard...</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option
                                            value="{{ $i }}{{ in_array($i, [1, 2, 3]) ? (['st', 'nd', 'rd'][$i - 1]) : 'th' }}">
                                            {{ $i }}{{ in_array($i, [1, 2, 3]) ? (['st', 'nd', 'rd'][$i - 1]) : 'th' }} Standard
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div id="all-students-placeholder" class="space-y-1 opacity-50">
                                <label
                                    class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Selection
                                    Details</label>
                                <div id="placeholder-text"
                                    class="w-full px-3 py-3 sm:py-2.5 bg-slate-100/50 border border-slate-100 rounded-xl text-[10px] font-bold text-slate-400 truncate">
                                    Everyone</div>
                            </div>
                        </div>

                        <div class="space-y-1 col-span-1">
                            <label
                                class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Subject</label>
                            <input type="text" name="topic" required placeholder="Main title"
                                class="w-full px-3 py-3 sm:py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold outline-none">
                        </div>

                        <div class="space-y-1 col-span-1">
                            <label
                                class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Attachment
                                (Image/PDF)</label>
                            <input type="file" name="attachment" accept="image/*,application/pdf"
                                class="w-full px-3 py-2 bg-slate-50 border border-dashed border-slate-200 rounded-xl text-[10px] font-bold outline-none file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-[9px] file:font-black file:bg-blue-50 file:text-blue-700">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Message
                            Content</label>
                        <textarea name="description" required rows="4" placeholder="Write details here..."
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold outline-none resize-none min-h-[100px]"></textarea>
                    </div>

                    <div class="pt-2 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 sm:space-x-3">
                        <button type="button" onclick="closeUpdateModal()"
                            class="w-full sm:w-auto px-6 py-3 sm:py-2.5 text-xs font-bold text-slate-400 hover:text-slate-600">Cancel</button>
                        <button type="submit" id="submit-btn"
                            class="w-full sm:w-auto px-8 py-3 sm:py-2.5 bg-[#ff6c00] hover:bg-[#e05f00] text-white rounded-xl font-bold text-xs shadow-md shadow-orange-500/10 hover:scale-[1.02] active:scale-95 transition-all">
                            <span id="btn-text">Publish Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <!-- View Update Modal -->
    <div id="view-modal" class="fixed inset-0 z-[110] flex items-center justify-center hidden p-4">
        <div onclick="closeViewModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div
            class="bg-white w-full max-w-xl rounded-2xl shadow-2xl relative z-10 overflow-hidden animate-in fade-in zoom-in duration-300">
            <div class="px-5 py-3.5 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                <div class="flex items-center gap-3">
                    <div id="view-cat-icon" class="h-9 w-9 rounded-xl flex items-center justify-center"></div>
                    <div>
                        <h2 id="view-topic" class="text-[15px] font-black text-slate-800 leading-tight"></h2>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span id="view-category"
                                class="text-[8px] font-black uppercase tracking-widest px-1.5 py-0.5 rounded"></span>
                            <span class="text-[8px] font-bold text-slate-300">•</span>
                            <span id="view-date"
                                class="text-[8px] font-bold text-slate-400 uppercase tracking-widest"></span>
                        </div>
                    </div>
                </div>
                <button onclick="closeViewModal()"
                    class="h-8 w-8 bg-white border border-slate-100 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-500 shadow-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Target:</span>
                    <span id="view-target"
                        class="text-[9px] font-black text-[#ff6c00] bg-orange-50 px-2 py-0.5 rounded-md uppercase tracking-wider"></span>
                </div>

                <div class="prose prose-slate max-w-none">
                    <p id="view-description"
                        class="text-[13px] text-slate-600 leading-relaxed font-medium whitespace-pre-wrap"></p>
                </div>

                <div id="view-attachment-container" class="mt-4 pt-3 border-t border-slate-50 hidden">
                    <a id="view-attachment-link" href="#" target="_blank"
                        class="inline-flex items-center gap-3 p-2 bg-slate-50 border border-slate-100 rounded-xl hover:bg-orange-50 hover:border-orange-100 transition-all group w-full">
                        <div
                            class="h-8 w-8 bg-white rounded-lg flex items-center justify-center text-[#ff6c00] shadow-sm group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.414a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[11px] font-black text-slate-700 block">View File Attachment</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mt-0.5">Click
                                to open</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="px-5 py-3 bg-slate-50/30 border-t border-slate-50 flex justify-end">
                <button onclick="closeViewModal()"
                    class="px-6 py-2 bg-primary2 text-white rounded-xl font-bold text-[11px] shadow-lg shadow-slate-900/10 hover:scale-[1.02] active:scale-95 transition-all">
                    Close Details
                </button>
            </div>
        </div>
    </div>

    <script>
        const CSRF_TOKEN = "{{ csrf_token() }}";

        function getTimeAgo(date) {
            const seconds = Math.floor((new Date() - date) / 1000);
            let interval = seconds / 31536000;
            if (interval > 1) return Math.floor(interval) + " years ago";
            interval = seconds / 2592000;
            if (interval > 1) return Math.floor(interval) + " months ago";
            interval = seconds / 86400;
            if (interval > 1) return Math.floor(interval) + " days ago";
            interval = seconds / 3600;
            if (interval > 1) return Math.floor(interval) + " hours ago";
            interval = seconds / 60;
            if (interval > 1) return Math.floor(interval) + " minutes ago";
            return Math.floor(seconds) + " seconds ago";
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchBatches();
            fetchUpdates();

            // Handle Enter key in search
            document.getElementById('feed-search').addEventListener('keypress', (e) => {
                if (e.key === 'Enter') performSearch();
            });
        });

        function handleTargetChange() {
            const recipient = document.getElementById('recipient-select').value;
            const type = document.getElementById('target-type-select').value;

            const audienceCont = document.getElementById('student-audience-container');
            const batchCont = document.getElementById('batch-selector-container');
            const standardCont = document.getElementById('standard-selector-container');
            const allPlaceholder = document.getElementById('all-students-placeholder');
            const placeholderText = document.getElementById('placeholder-text');

            if (recipient === 'parents') {
                audienceCont.style.opacity = '0.3';
                audienceCont.style.pointerEvents = 'none';
                batchCont.classList.add('hidden');
                standardCont.classList.add('hidden');
                allPlaceholder.classList.remove('hidden');
                placeholderText.innerText = "Broadcasting to all Parents";
            } else if (recipient === 'both') {
                audienceCont.style.opacity = '1';
                audienceCont.style.pointerEvents = 'auto';

                batchCont.classList.toggle('hidden', type !== 'batch');
                standardCont.classList.toggle('hidden', type !== 'standard');
                allPlaceholder.classList.toggle('hidden', type !== 'all');
                placeholderText.innerText = "Broadcasting to both Students & Parents";
            } else {
                audienceCont.style.opacity = '1';
                audienceCont.style.pointerEvents = 'auto';

                batchCont.classList.toggle('hidden', type !== 'batch');
                standardCont.classList.toggle('hidden', type !== 'standard');
                allPlaceholder.classList.toggle('hidden', type !== 'all');
                placeholderText.innerText = "Broadcasting to all Students";
            }

            document.getElementById('modal-batch-select').required = ((recipient === 'students' || recipient === 'both') && type === 'batch');
        }

        async function fetchBatches() {
            try {
                const response = await fetch("/api/v1/institute/batches", { headers: { 'Accept': 'application/json' } });
                const result = await response.json();
                if (result.status === 'success') {
                    const sel = document.getElementById('modal-batch-select');
                    result.data.items.forEach(batch => {
                        const opt = document.createElement('option');
                        opt.value = batch.id;
                        opt.innerText = batch.name;
                        sel.appendChild(opt);
                    });
                }
            } catch (error) { console.error('Failed to sync batches'); }
        }

        async function fetchUpdates(search = '') {
            const container = document.getElementById('update-feed');
            container.innerHTML = `<div class="py-20 text-center text-slate-300 italic text-xs">${search ? 'Searching...' : 'Loading feed...'}</div>`;

            try {
                const url = search ? `/api/v1/institute/daily-updates?search=${encodeURIComponent(search)}` : "/api/v1/institute/daily-updates";
                const response = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const result = await response.json();

                if (result.status === 'success') {
                    let updates = result.data;

                    // Client-side fallback filter if API doesn't filter
                    if (search) {
                        const s = search.toLowerCase();
                        updates = updates.filter(u =>
                            (u.topic && u.topic.toLowerCase().includes(s)) ||
                            (u.description && u.description.toLowerCase().includes(s)) ||
                            (u.category && u.category.toLowerCase().includes(s))
                        );
                    }

                    renderUpdates(updates);
                }
            } catch (error) {
                console.error(error);
                showToast('Load error', 'error');
            }
        }

        function performSearch() {
            const query = document.getElementById('feed-search').value;
            fetchUpdates(query);
        }

        function renderUpdates(updates) {
            const container = document.getElementById('update-feed');
            if (updates.length === 0) {
                container.innerHTML = `<div class="p-8 md:p-20 text-center text-slate-400 italic bg-white rounded-[1.5rem] md:rounded-[2.5rem] border border-slate-100 md:ml-16">No updates posted yet.</div>`;
                return;
            }

            container.innerHTML = updates.map(update => {
                const catConfig = {
                    'Academic': { color: 'orange', icon: '<svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>' },
                    'Administrative': { color: 'orange', icon: '<svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>' },
                    'Emergency': { color: 'rose', icon: '<svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>' },
                    'Event': { color: 'sky', icon: '<svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' },
                    'Holiday': { color: 'indigo', icon: '<svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>' },
                    'Other': { color: 'slate', icon: '<svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>' }
                };

                const cat = update.category === 'Administrative' ? 'Fee Reminder' : update.category;
                const config = catConfig[update.category] || catConfig['Other'];
                const color = config.color;
                const updateJson = JSON.stringify(update).replace(/"/g, '&quot;');
                const timeAgo = getTimeAgo(new Date(update.created_at));

                // Improved Target Display Logic
                let targetLabel = 'Target';
                let targetValue = 'Everyone';

                if (update.recipient === 'parents') {
                    targetValue = 'Parents Only';
                } else {
                    const audience = update.recipient === 'both' ? '(Std & Par)' : '';
                    if (update.target_type === 'all') {
                        targetValue = update.recipient === 'both' ? 'All (Std & Par)' : 'All Students';
                    } else if (update.target_type === 'batch') {
                        targetValue = (update.batch ? update.batch.name : 'Batch') + ' ' + audience;
                    } else if (update.target_type === 'standard') {
                        targetValue = (update.standard ? update.standard + ' Std' : 'Standard') + ' ' + audience;
                    }
                }

                return `
                    <div class="flex flex-col items-center sm:items-start sm:flex-row gap-2 sm:gap-6 group">
                        <!-- Timeline Icon -->
                        <div class="relative z-10 shrink-0">
                            <div class="h-8 w-8 sm:h-12 sm:w-12 bg-${color}-500 text-white rounded-lg sm:rounded-xl shadow-lg shadow-${color}-500/30 flex items-center justify-center transition-transform group-hover:scale-110">
                                ${config.icon}
                            </div>
                        </div>

                        <!-- Update Card -->
                        <div class="flex-1 w-full bg-white p-2.5 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-100 shadow-sm hover:border-primary/20 transition-all duration-300 min-w-0 overflow-hidden">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-1 mb-1.5 sm:mb-2">
                                <span class="text-[9px] sm:text-[10px] font-black text-${color}-600 uppercase tracking-widest">${cat.toUpperCase()}</span>
                            </div>

                            <h3 class="text-base sm:text-lg font-bold text-slate-800 mb-0.5 sm:mb-1 leading-tight group-hover:text-primary transition-colors truncate">${update.topic || update.category}</h3>

                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-medium mb-3 line-clamp-2 sm:line-clamp-3 break-words">${update.description}</p>

                            <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                <div class="flex items-center gap-1 sm:gap-2">
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    <span class="truncate max-w-[120px] sm:max-w-none">Target: <span class="text-slate-600">${targetValue}</span></span>
                                </div>
                                <div class="flex items-center gap-1 sm:gap-2">
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    ${timeAgo}
                                </div>
                            </div>

                            ${update.attachment ? `
                            <div class="mt-2.5 pt-2 border-t border-slate-50">
                                <a href="${update.attachment}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-50 border border-slate-100 rounded-lg hover:bg-orange-50 hover:border-orange-200 transition-all group">
                                    <svg class="w-3 h-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.414a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    <span class="text-[9px] font-black text-slate-700 uppercase tracking-wider">View File</span>
                                </a>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                    `;
            }).join('');
        }

        document.getElementById('update-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const f = new FormData(e.target);
            const btn = document.getElementById('submit-btn');
            const btnText = document.getElementById('btn-text');

            btn.disabled = true;
            btnText.innerText = 'Publishing...';

            try {
                const response = await fetch("/api/v1/institute/daily-updates", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: f
                });

                const textResponse = await response.text();
                let result;
                try {
                    result = JSON.parse(textResponse);
                } catch (e) {
                    console.error("Non-JSON response:", textResponse);
                    showToast('Server error or invalid response format.', 'error');
                    return;
                }

                if (response.ok && result.status === 'success') {
                    showToast(result.message || 'Update published successfully!', 'success');
                    closeUpdateModal();
                    fetchUpdates();
                    e.target.reset();
                    handleTargetChange();
                } else {
                    showToast(result.message || 'Validation failed. Check inputs.', 'error');
                }
            } catch (error) {
                console.error(error);
                showToast('Connection error. Please try again.', 'error');
            } finally {
                btn.disabled = false;
                btnText.innerText = 'Publish Update';
            }
        });


        function openUpdateModal() { document.getElementById('update-modal').classList.remove('hidden'); }
        function closeUpdateModal() {
            document.getElementById('update-modal').classList.add('hidden');
            document.getElementById('update-form').reset();
            handleTargetChange();
        }
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const color = type === 'success' ? 'emerald' : 'rose';
            toast.className = `bg-${color}-50 border border-${color}-200 text-${color}-600 px-6 py-4 rounded-2xl shadow-xl flex items-center animate-in slide-in-from-right-10 duration-300`;
            toast.innerHTML = `<span class="text-sm font-bold">${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    </script>
@endsection