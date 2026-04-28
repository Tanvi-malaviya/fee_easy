@extends('layouts.institute')

@section('content')
    <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

    <div class="max-w-[1400px] mx-auto pb-10 px-4 sm:px-6">
        <!-- Breadcrumb & Header -->
        <div class="mb-10">
            <nav class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">
                <a href="{{ route('institute.batches.index') }}" class="hover:text-blue-600 transition-colors">Batches</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('institute.batches.show', $id) }}" id="breadcrumb-batch-name"
                    class="hover:text-blue-600 transition-colors">Loading...</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-slate-600">Homework</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-bold text-slate-900 tracking-tight mb-2">Batch Homework</h1>
                    <p class="text-sm font-semibold text-slate-400">Centralized assignment tracking and submission management.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative group">
                        <input type="text" id="homework-search" onkeyup="filterHomeworks()" placeholder="Search assignments..."
                            class="pl-12 pr-6 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm font-semibold outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/5 transition-all w-[300px]">
                        <svg class="w-5 h-5 text-slate-300 absolute left-4 top-1/2 -translate-y-1/2 group-focus-within:text-blue-600 transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <button onclick="openAddHomeworkModal()"
                        class="px-6 py-3.5 bg-orange-600 text-white rounded-2xl text-sm font-bold flex items-center gap-2 hover:bg-orange-700 hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-orange-600/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Homework
                    </button>
                </div>
            </div>
        </div>

        <!-- Category Filters -->
        <div id="batch-filters" class="flex flex-wrap gap-2 mb-10 overflow-x-auto pb-2 scrollbar-hide">
            <button onclick="selectCategory('All')" class="category-btn active px-6 py-2.5 rounded-full text-xs font-black uppercase tracking-widest transition-all">All Batches</button>
            <!-- Batches populated via JS -->
        </div>

        <!-- Homework Grid -->
        <div id="homework-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Loading State -->
            <div class="col-span-full py-32 text-center">
                <div class="inline-block h-10 w-10 border-4 border-slate-100 border-t-blue-600 rounded-full animate-spin"></div>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs mt-6">Syncing Assignments...</p>
            </div>
        </div>
    </div>

    <!-- ADD HOMEWORK MODAL -->
    <div id="add-homework-modal" class="fixed inset-0 z-[150] bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
            <div class="p-10">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h2 class="text-3xl font-black text-slate-900 tracking-tight">New Assignment</h2>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-2">Publish a task to <span id="modal-batch-name" class="text-blue-600">this batch</span></p>
                    </div>
                    <button onclick="closeAddHomeworkModal()" class="h-12 w-12 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center hover:bg-rose-50 hover:text-rose-500 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form id="homework-form" onsubmit="handleHomeworkSubmit(event)" class="space-y-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Assignment Title</label>
                        <input type="text" name="title" required placeholder="e.g. Advanced Calculus - Pset 04" 
                            class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-[15px] font-bold outline-none focus:ring-4 focus:ring-blue-600/5 focus:border-blue-600 focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Instructions & Description</label>
                        <textarea name="description" rows="4" required placeholder="Detail the submission requirements..." 
                            class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-[15px] font-bold outline-none focus:ring-4 focus:ring-blue-600/5 focus:border-blue-600 focus:bg-white transition-all resize-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Deadline Date</label>
                        <input type="date" name="due_date" required 
                            class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-[15px] font-bold outline-none focus:ring-4 focus:ring-blue-600/5 focus:border-blue-600 focus:bg-white transition-all">
                    </div>

                    <div class="pt-4">
                        <button type="submit" id="submit-btn" class="w-full py-5 bg-blue-900 text-white rounded-[1.5rem] font-black text-sm shadow-xl shadow-blue-900/20 hover:scale-[1.02] active:scale-95 transition-all">Publish Assignment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .category-btn { @apply bg-white text-slate-400 border border-slate-100 shadow-sm; }
        .category-btn.active { @apply bg-orange-100 text-orange-700 border-orange-200 shadow-orange-100; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>

    <script>
        const BATCH_ID = "{{ $id }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";
        const API_HOMEWORKS_URL = `/api/v1/institute/homeworks`;
        const API_BATCH_URL = `/api/v1/institute/batches/${BATCH_ID}`;

        let allHomeworks = [];
        let currentBatch = null;

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
                    document.getElementById('breadcrumb-batch-name').innerText = currentBatch.name;
                    document.getElementById('modal-batch-name').innerText = currentBatch.name;
                    renderBatchFilters([currentBatch]);
                }
            } catch (error) {
                showToast('Failed to load batch info', 'error');
            }
        }

        async function fetchHomeworks() {
            try {
                const response = await fetch(API_HOMEWORKS_URL, { headers: { 'Accept': 'application/json' } });
                const result = await response.json();
                if (result.status === 'success') {
                    allHomeworks = result.data.filter(h => h.batch_id == BATCH_ID);
                    renderHomeworks(allHomeworks);
                }
            } catch (error) {
                showToast('Failed to load assignments', 'error');
            }
        }

        function renderBatchFilters(batches) {
            const container = document.getElementById('batch-filters');
            // Keep "All Batches" and add current batch
            container.innerHTML = `
                <button onclick="selectCategory('All')" class="category-btn active px-6 py-2.5 rounded-full text-xs font-black uppercase tracking-widest transition-all">All Assignments</button>
                <button onclick="selectCategory('${currentBatch.name}')" class="category-btn px-6 py-2.5 rounded-full text-xs font-black uppercase tracking-widest transition-all">${currentBatch.name}</button>
            `;
        }

        function renderHomeworks(homeworks) {
            const container = document.getElementById('homework-grid');
            if (homeworks.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full py-20 text-center flex flex-col items-center">
                        <div class="h-24 w-24 bg-slate-50 rounded-[2rem] flex items-center justify-center text-slate-200 mb-6 border border-slate-100">
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
                
                // Mock submissions for UI demonstration
                const submissions = Math.floor(Math.random() * 20) + 10;
                const total = 50;
                const progress = (submissions / total) * 100;

                const icons = [
                    '<path d="M9 7h6m0 10H9m3-10v10M9 7h6" />', // Sum
                    '<path d="M4 12h16M4 12l8-8m-8 8l8 8" />', // Arrow
                    '<path d="M12 3v19M3 12h19" />', // Plus
                    '<path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />' // Briefcase
                ];
                const randomIcon = icons[hw.id % icons.length];

                return `
                    <div class="group bg-white rounded-[2.5rem] border border-slate-50 p-8 hover:shadow-2xl hover:shadow-slate-200/50 hover:border-blue-100 transition-all duration-500 relative">
                        <div class="flex items-center justify-between mb-8">
                            <div class="h-14 w-14 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-800 group-hover:scale-110 transition-transform duration-500">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">${randomIcon}</svg>
                            </div>
                            <span class="px-4 py-1.5 ${isActive ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-50 text-slate-400'} rounded-full text-[9px] font-black uppercase tracking-widest border ${isActive ? 'border-emerald-100' : 'border-slate-100'}">
                                ${isActive ? 'Active' : 'Closed'}
                            </span>
                        </div>

                        <h4 class="text-lg font-black text-slate-900 leading-tight mb-2 group-hover:text-blue-600 transition-colors">${hw.title}</h4>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">${currentBatch.name} • Section A</p>

                        <div class="mt-8 pt-8 border-t border-slate-50 space-y-6">
                            <div class="flex items-center justify-between">
                                <div class="text-left">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Due Date</p>
                                    <p class="text-[13px] font-black text-slate-800">${dueDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-black ${isActive ? 'text-orange-500' : 'text-slate-400'} uppercase tracking-tight">${isActive ? diffDays + ' Days Left' : 'Completed'}</p>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Submissions</p>
                                    <p class="text-[11px] font-black text-slate-900">${submissions}/${total}</p>
                                </div>
                                <div class="h-2 w-full bg-slate-50 rounded-full overflow-hidden">
                                    <div class="h-full ${isActive ? 'bg-orange-500' : 'bg-slate-300'} rounded-full transition-all duration-1000" style="width: ${progress}%"></div>
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

        function selectCategory(cat) {
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.classList.toggle('active', btn.innerText.includes(cat));
            });
            // Filter logic can be extended here if multiple batches were shown
        }

        function openAddHomeworkModal() {
            document.getElementById('add-homework-modal').classList.replace('hidden', 'flex');
            document.body.style.overflow = 'hidden';
        }

        function closeAddHomeworkModal() {
            document.getElementById('add-homework-modal').classList.replace('flex', 'hidden');
            document.body.style.overflow = 'auto';
        }

        async function handleHomeworkSubmit(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());
            data.batch_id = BATCH_ID;

            const btn = document.getElementById('submit-btn');
            btn.disabled = true;
            btn.innerText = 'Publishing...';

            try {
                const response = await fetch(API_HOMEWORKS_URL, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify(data)
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
