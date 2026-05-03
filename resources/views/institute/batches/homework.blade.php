@extends('layouts.institute')

@section('content')
    <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

    <div class="max-w-7xl mx-auto pt-2 px-4 sm:px-6">
        <!-- Breadcrumb & Header -->

        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">
            <a href="{{ route('institute.batches.index') }}" class="hover:text-[#ff6600] transition-colors">Batches</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('institute.batches.show', $id) }}" class="hover:text-[#ff6600] transition-colors">Batch
                Details</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-slate-600">Homework</span>
        </nav>


        <div class="mb-5">

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-medium text-slate-900 tracking-tight mb-1">Batch Homework</h1>
                    <p class="text-sm font-semibold text-slate-400">Centralized assignment tracking and submission
                        management.</p>
                </div>
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="flex items-center gap-0 bg-white border border-slate-200 rounded-2xl p-1 shadow-sm focus-within:border-blue-600 transition-all flex-1 md:flex-none md:w-auto">
                    <div class="relative flex-1">
                        <input type="text" id="homework-search" onkeypress="if(event.key === 'Enter') filterHomeworks()"
                            placeholder="Search assignments..."
                            class="pl-10 pr-2 py-2 bg-transparent rounded-xl text-sm font-semibold outline-none w-full md:w-[260px]">
                        <svg class="w-4 h-4 text-slate-300 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <button onclick="filterHomeworks()"
                        class="px-4 py-2 bg-slate-900 text-white text-[11px] font-bold rounded-xl hover:bg-slate-800 transition-colors uppercase tracking-widest">
                        Search
                    </button>
                </div>
                    <button onclick="openAddHomeworkModal()"
                        class="px-5 py-3 bg-[#a3360a] hover:bg-[#852b08] text-white text-xs font-bold rounded-xl shadow-md shadow-orange-700/10 transition-all flex items-center gap-2 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Homework
                    </button>
                </div>
            </div>
        </div>

        <!-- Homework Grid -->
        <div id="homework-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
            <!-- Loading State -->
            <div class="col-span-full py-32 text-center">
                <div class="inline-block h-10 w-10 border-4 border-slate-100 border-t-blue-600 rounded-full animate-spin">
                </div>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs mt-6">Syncing Assignments...</p>
            </div>
        </div>

        <!-- Pagination -->
        <div id="pagination-container" class="mt-4 mb-1 flex flex-col sm:flex-row items-center justify-between gap-6 px-2">
        </div>
    </div>

    <!-- ADD HOMEWORK MODAL -->
    <div id="add-homework-modal"
        class="fixed inset-0 z-[150] bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center p-4 font-sans">
        <div
            class="bg-white w-full max-w-[900px] max-h-[95vh] rounded-3xl shadow-2xl overflow-hidden animate-in zoom-in duration-300 flex flex-col">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-slate-100 flex items-start justify-between">
                <div>
                    <h2 class="text-[22px] font-bold text-slate-900 tracking-tight">Create New Homework</h2>

                </div>
                <button type="button" onclick="closeAddHomeworkModal()"
                    class="text-slate-400 hover:text-slate-600 transition-colors p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <form id="homework-form" onsubmit="handleHomeworkSubmit(event)" class="flex flex-col flex-1 overflow-hidden">
                <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-[1.2fr_1fr] gap-x-12 gap-y-4 overflow-y-auto custom-scrollbar flex-1">

                    <!-- Left Column -->
                    <div class="space-y-4">
                        <!-- Subject Title -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Subject
                                Title</label>
                            <input type="text" name="title" required
                                placeholder="e.g. Advanced Calculus - Differential Equations"
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[14px] font-medium text-slate-800 placeholder:text-slate-300 outline-none focus:ring-2 focus:ring-[#ff6600]/20 focus:border-[#ff6600] transition-all">
                        </div>

                        <!-- Instruction Details -->
                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Instruction
                                Details</label>
                            <textarea name="description" rows="4" required
                                placeholder="Outline the learning objectives and step-by-step instructions for the students..."
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[14px] font-medium text-slate-800 placeholder:text-slate-300 outline-none focus:ring-2 focus:ring-[#ff6600]/20 focus:border-[#ff6600] transition-all resize-none"></textarea>
                        </div>

                        <!-- Resource Materials -->
                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Resource
                                Materials</label>

                            <input type="file" name="attachment" id="homework-attachment" class="hidden"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip" onchange="handleFileSelect(event)">

                            <div id="dropzone" onclick="document.getElementById('homework-attachment').click()"
                                ondrop="handleDrop(event)" ondragover="handleDragOver(event)"
                                class="border-2 border-dashed border-slate-200 rounded-xl p-4 flex flex-col items-center justify-center bg-slate-50/50 group hover:bg-slate-50 hover:border-[#ff6600]/30 transition-all cursor-pointer">
                                <div
                                    class="h-8 w-8 bg-orange-100 rounded-lg flex items-center justify-center text-[#ff6600] mb-2 group-hover:scale-110 transition-transform">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm-1 13v4h-2v-4H8l4-4 4 4h-3zm-3-6V3.5L18.5 9H10z" />
                                    </svg>
                                </div>
                                <p class="text-[12px] font-semibold text-slate-800">Drag files or <span
                                        class="text-[#ff6600] underline decoration-[#ff6600]/30 underline-offset-4">browse</span>
                                </p>
                                <p class="text-[9px] text-slate-400 mt-1 font-bold uppercase tracking-widest">Max Size: 10MB
                                </p>
                            </div>

                            <!-- File Attachment Preview (Hidden by default) -->
                            <div id="file-preview"
                                class="hidden mt-3 items-center justify-between p-3 bg-white border border-slate-200 rounded-xl">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div
                                        class="h-10 w-10 bg-rose-50 rounded-lg flex items-center justify-center text-rose-500 shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 9h1.5m1.5 0H12m-1.5 3h1.5m-1.5 3H12" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p id="file-name" class="text-[13px] font-bold text-slate-800 truncate"></p>
                                        <p id="file-size" class="text-[11px] font-medium text-slate-400"></p>
                                    </div>
                                </div>
                                <button type="button" onclick="removeAttachment()"
                                    class="text-slate-400 hover:text-rose-500 p-2 shrink-0 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <!-- Due Date -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Due
                                Date</label>
                            <input type="date" name="due_date" required
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[14px] font-medium text-slate-800 outline-none focus:ring-2 focus:ring-[#ff6600]/20 focus:border-[#ff6600] transition-all">
                        </div>

                        <!-- Priority Level -->
                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Priority
                                Level</label>
                            <div class="relative">
                                <select
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[14px] font-medium text-slate-800 outline-none focus:ring-2 focus:ring-[#ff6600]/20 focus:border-[#ff6600] transition-all appearance-none cursor-pointer">
                                    <option>Normal Priority</option>
                                    <option>High Priority</option>
                                    <option>Low Priority</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>



                        <!-- Pro Tip -->
                        <!-- <div class="bg-teal-50/50 border border-teal-100 rounded-xl p-5 mt-4">
                                    <div class="flex items-center justify-center gap-2 mb-2">
                                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                        </svg>
                                        <span class="text-[10px] font-black text-teal-600 uppercase tracking-widest">Pro Tip</span>
                                    </div>
                                    <p class="text-[13px] text-center font-medium text-teal-700/80 leading-relaxed">Students receive
                                        real-time notifications via the Tuoora app once broadcasted.</p>
                                </div> -->
                    </div>
                </div>

                <!-- Footer -->
                <div
                    class="px-8 py-5 border-t border-slate-100 bg-slate-50/50 flex items-center justify-end gap-4 mt-auto shrink-0">
                    <button type="button" onclick="closeAddHomeworkModal()"
                        class="px-6 py-2.5 text-[14px] font-bold text-slate-500 hover:text-slate-700 transition-colors">Cancel</button>
                    <button type="submit" id="submit-btn"
                        class="px-6 py-2.5 bg-[#ff6600] text-white rounded-xl text-[14px] font-bold shadow-sm hover:bg-[#e65c00] transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                        </svg>
                        Broadcast Homework
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
    </style>

    <script>
        const BATCH_ID = "{{ $id }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";
        const API_HOMEWORKS_URL = `/api/v1/institute/homeworks`;
        const API_BATCH_URL = `/api/v1/institute/batches/${BATCH_ID}`;

        let allHomeworks = [];
        let currentBatch = null;
        let currentPage = 1;
        let lastPage = 1;

        document.addEventListener('DOMContentLoaded', async () => {
            await fetchBatchData();
            await fetchHomeworks();
        });

        async function fetchBatchData() {
            try {
                const response = await fetch(API_BATCH_URL, { headers: { 'Accept': 'application/json' } });
                const result = await response.json();
                if (result.status === 'success') {
                    currentBatch = result.data;

                    const badgeElem = document.getElementById('modal-batch-badge');
                    if (badgeElem) {
                        badgeElem.innerText = currentBatch.name;
                    }
                }
            } catch (error) {
                showToast('Failed to load batch info', 'error');
            }
        }

        async function fetchHomeworks(page = 1) {
            try {
                currentPage = page;
                const searchQuery = document.getElementById('homework-search').value;
                let url = `${API_HOMEWORKS_URL}?batch_id=${BATCH_ID}&page=${page}`;
                if (searchQuery) url += `&search=${encodeURIComponent(searchQuery)}`;

                const response = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const result = await response.json();
                if (result.status === 'success') {
                    allHomeworks = result.data.data;
                    lastPage = result.data.last_page;
                    renderHomeworks(allHomeworks);
                    renderPagination(result.data.total, result.data.per_page, result.data.from, result.data.to);
                }
            } catch (error) {
                showToast('Failed to load assignments', 'error');
            }
        }

        function renderPagination(total, perPage, from, to) {
            const container = document.getElementById('pagination-container');
            if (lastPage <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = `
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                            Showing <span class="text-slate-700">${from || 0}</span> to <span class="text-slate-700">${to || 0}</span> of <span class="text-slate-700">${total || 0}</span> assignments
                        </div>
                        <div class="flex items-center gap-1">
                    `;

            // Previous Button
            html += `<button onclick="fetchHomeworks(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} 
                                class="h-8 w-8 flex items-center justify-center text-slate-400 hover:text-orange-600 transition-all disabled:opacity-20 disabled:cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                            </button>`;

            // Page Numbers
            for (let i = 1; i <= lastPage; i++) {
                if (i === 1 || i === lastPage || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    const isActive = currentPage === i;
                    html += `<button onclick="fetchHomeworks(${i})" 
                                        class="h-9 w-9 flex items-center justify-center rounded-full text-xs font-black transition-all ${isActive ? 'bg-[#a3360a] text-white shadow-lg shadow-orange-700/20' : 'text-slate-400 hover:text-slate-700'}">
                                        ${i}
                                    </button>`;
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    html += `<span class="px-1 text-slate-300">...</span>`;
                }
            }

            // Next Button
            html += `<button onclick="fetchHomeworks(${currentPage + 1})" ${currentPage === lastPage ? 'disabled' : ''} 
                                class="h-8 w-8 flex items-center justify-center text-slate-400 hover:text-orange-600 transition-all disabled:opacity-20 disabled:cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                            </button>`;

            html += `</div>`;
            container.innerHTML = html;
        }

        function filterHomeworks() {
            fetchHomeworks(1);
        }

        function renderHomeworks(homeworks) {
            const container = document.getElementById('homework-grid');
            if (homeworks.length === 0) {
                container.innerHTML = `
                                    <div class="col-span-full py-10 text-center flex flex-col items-center">
                                        <div class="h-24 w-24 bg-slate-50 rounded-[1rem] flex items-center justify-center text-slate-200 mb-6 border border-slate-100">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        </div>
                                        <h3 class="text-xl font-black text-slate-800 mb-2">No Assignments Yet</h3>
                                        <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-[10px] mb-6">Your scholars are waiting for their first task</p>
                                        <button onclick="openAddHomeworkModal()" class="text-blue-600 font-black text-xs hover:underline uppercase tracking-widest">Create One Now</button>
                                    </div>`;
                return;
            }

            container.innerHTML = homeworks.map(hw => {
                const dueDate = new Date(hw.due_date);
                const today = new Date();
                const diffTime = dueDate - today;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const isActive = diffDays >= 0;

                const submissions = hw.submissions_count || 0;
                const total = (hw.batch && hw.batch.students_count) ? hw.batch.students_count : 0;
                const progress = total > 0 ? (submissions / total) * 100 : 0;

                const icons = [
                    '<path d="M9 7h6m0 10H9m3-10v10M9 7h6" />', // Sum
                    '<path d="M4 12h16M4 12l8-8m-8 8l8 8" />', // Arrow
                    '<path d="M12 3v19M3 12h19" />', // Plus
                    '<path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />' // Briefcase
                ];
                const randomIcon = icons[hw.id % icons.length];

                return `
                                    <div class="group bg-white rounded-2xl border border-slate-100 p-4 hover:shadow-xl hover:shadow-slate-200/40 hover:border-blue-100 transition-all duration-300 relative cursor-pointer flex flex-col" onclick="window.location.href='/institute/batches/${BATCH_ID}/homework/${hw.id}'">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="h-9 w-9 rounded-xl ${isActive ? 'bg-orange-50 text-[#ff6600]' : 'bg-slate-100 text-slate-500'} flex items-center justify-center shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">${randomIcon}</svg>
                                            </div>
                                            <span class="px-2 py-0.5 ${isActive ? 'bg-orange-50 text-[#ff6600]' : 'bg-slate-100 text-slate-500'} rounded text-[8px] font-black uppercase tracking-wider">
                                                ${isActive ? 'Active' : 'Closed'}
                                            </span>
                                        </div>

                                        <h4 class="text-[13px] font-black text-slate-800 leading-tight mb-1 truncate" title="${hw.title}">${hw.title}</h4>
                                        <p class="text-[10px] font-bold text-slate-400 mb-4 truncate uppercase tracking-tight">${currentBatch.name}</p>

                                        <div class="space-y-3 mt-auto">
                                            <div class="flex items-center justify-between">
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Due</p>
                                                <p class="text-[10px] font-black text-slate-700">${dueDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}</p>
                                            </div>

                                            <div>
                                                <div class="flex items-center justify-between mb-1.5">
                                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Submissions</p>
                                                    <p class="text-[10px] font-black text-slate-800">${submissions}/${total}</p>
                                                </div>
                                                <div class="h-1.5 w-full bg-slate-50 rounded-full overflow-hidden">
                                                    <div class="h-full ${isActive ? 'bg-[#ff6600]' : 'bg-slate-400'} rounded-full transition-all duration-1000" style="width: ${progress}%"></div>
                                                </div>
                                            </div>

                                            <div class=" flex items-center justify-between">
                                                 <p class="text-[9px] font-black ${isActive ? 'text-[#ff6600]' : 'text-slate-400'} uppercase tracking-widest">${isActive ? diffDays + ' DAYS LEFT' : 'COMPLETED'}</p>
                                                 <div class="h-6 w-6 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                                                 </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
            }).join('');
        }

        function filterHomeworks() {
            const query = document.getElementById('homework-search').value.toLowerCase();
            const filtered = allHomeworks.filter(hw => hw.title.toLowerCase().includes(query) || hw.description.toLowerCase().includes(query));
            renderHomeworks(filtered);
        }

        function openAddHomeworkModal() {
            document.getElementById('add-homework-modal').classList.replace('hidden', 'flex');
            document.body.style.overflow = 'hidden';
        }

        function closeAddHomeworkModal() {
            document.getElementById('add-homework-modal').classList.replace('flex', 'hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('homework-form').reset();
            removeAttachment();
        }

        function handleFileSelect(e) {
            const file = e.target.files[0];
            updateFilePreview(file);
        }

        function handleDrop(e) {
            e.preventDefault();
            e.stopPropagation();
            const file = e.dataTransfer.files[0];
            if (file) {
                document.getElementById('homework-attachment').files = e.dataTransfer.files;
                updateFilePreview(file);
            }
            document.getElementById('dropzone').classList.remove('border-[#ff6600]', 'bg-slate-50');
        }

        function handleDragOver(e) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('dropzone').classList.add('border-[#ff6600]', 'bg-slate-50');
        }

        function updateFilePreview(file) {
            if (!file) return;
            const dropzone = document.getElementById('dropzone');
            const preview = document.getElementById('file-preview');
            const nameEl = document.getElementById('file-name');
            const sizeEl = document.getElementById('file-size');

            dropzone.classList.add('hidden');
            preview.classList.replace('hidden', 'flex');

            nameEl.innerText = file.name;
            sizeEl.innerText = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
        }

        function removeAttachment() {
            document.getElementById('homework-attachment').value = '';
            document.getElementById('dropzone').classList.remove('hidden');
            document.getElementById('file-preview').classList.replace('flex', 'hidden');
        }

        async function handleHomeworkSubmit(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            formData.append('batch_id', BATCH_ID);

            const btn = document.getElementById('submit-btn');
            btn.disabled = true;
            btn.innerText = 'Publishing...';

            try {
                const response = await fetch(API_HOMEWORKS_URL, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: formData
                });
                const result = await response.json();
                if (result.status === 'success') {
                    showToast('Assignment published successfully!');
                    closeAddHomeworkModal();
                    e.target.reset();
                    fetchHomeworks();
                } else {
                    showToast(result.message || 'Failed to publish assignment', 'error');
                }
            } catch (error) {
                showToast('Connection error', 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Publish Assignment';
            }
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `flex items-center gap-3 px-6 py-4 rounded-2xl shadow-2xl animate-in slide-in-from-right-10 duration-500 ${type === 'success' ? 'bg-slate-900 text-white' : 'bg-rose-600 text-white'}`;
            toast.innerHTML = `
                                <div class="h-6 w-6 rounded-full flex items-center justify-center ${type === 'success' ? 'bg-blue-500' : 'bg-rose-400'}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="${type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}"/></svg>
                                </div>
                                <p class="text-sm font-bold">${message}</p>`;
            container.appendChild(toast);
            setTimeout(() => { toast.classList.add('animate-out', 'fade-out', 'slide-out-to-right-10'); setTimeout(() => toast.remove(), 500); }, 3000);
        }
    </script>
@endsection