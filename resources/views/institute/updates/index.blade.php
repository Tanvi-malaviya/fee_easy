@extends('layouts.institute')

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto pb-10">
    <!-- Toast Notifications Container -->
    <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

    <!-- Page Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Daily Updates</h1>
            <p class="text-sm text-slate-400 mt-2 font-medium">Post announcements, homework, or daily class logs.</p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="openUpdateModal()" class="px-6 py-3 bg-[#1e3a8a] text-white rounded-2xl font-bold text-[13px] shadow-lg shadow-blue-900/10 hover:scale-[1.02] transition-transform">
                + Create Update
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Feed Column -->
        <div class="space-y-6">
            <h3 class="text-sm font-extrabold text-slate-700 uppercase tracking-widest pl-4">Recently Posted</h3>
            <div id="update-feed" class="space-y-6">
                <!-- Data populated via AJAX -->
                <div class="p-20 text-center text-slate-400 italic">Loading feed...</div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <h4 class="text-[13px] font-extrabold text-slate-800 leading-tight mb-4">Why post updates?</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Daily updates keep parents and students informed about what was taught in class, homework assignments, and upcoming milestones. Notifications are sent automatically to all students in the selected batch.</p>
            </div>
        </div>
    </div>
</div>

<!-- Add Update Modal -->
<div id="update-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <div onclick="closeUpdateModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="bg-white w-full max-w-xl rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="p-10">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Post New Update</h2>
                    <p class="text-sm text-slate-400 mt-1">Broadcast information to a specific student cohort.</p>
                </div>
                <button onclick="closeUpdateModal()" class="h-10 w-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="update-form" class="space-y-5">
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Target Batch</label>
                    <select id="batch-selector" name="batch_id" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none">
                        <option value="">Select Batch...</option>
                        <!-- Batches loaded via JS -->
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Topic / Title</label>
                    <input type="text" name="topic" required placeholder="e.g. Chapter 5 Homework" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none">
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Date</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none">
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Description</label>
                    <textarea name="description" rows="4" required placeholder="Details about the update..." class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none"></textarea>
                </div>

                <div class="pt-6 border-t border-slate-50 flex items-center justify-end space-x-4">
                    <button type="button" onclick="closeUpdateModal()" class="px-8 py-3.5 text-[13px] font-bold text-slate-400">Cancel</button>
                    <button type="submit" id="submit-btn" class="px-10 py-3.5 bg-[#1e3a8a] text-white rounded-2xl font-bold text-[13px] shadow-lg flex items-center">
                        <span id="btn-text">Publish Update</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const CSRF_TOKEN = "{{ csrf_token() }}";
    
    document.addEventListener('DOMContentLoaded', () => {
        fetchBatches();
        fetchUpdates();
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
        } catch (error) { showToast('Sync error', 'error'); }
    }

    async function fetchUpdates() {
        try {
            const response = await fetch("/api/v1/institute/daily-updates", { headers: { 'Accept': 'application/json' } });
            const result = await response.json();
            if (result.status === 'success') {
                renderUpdates(result.data);
            }
        } catch (error) { showToast('Load error', 'error'); }
    }

    function renderUpdates(updates) {
        const container = document.getElementById('update-feed');
        if (updates.length === 0) {
            container.innerHTML = `<div class="p-20 text-center text-slate-400 italic bg-white rounded-[2.5rem] border border-slate-100">No updates posted yet.</div>`;
            return;
        }

        container.innerHTML = updates.map(update => `
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M16.5 15.5a1.875 1.875 0 100-3.75"/></svg>
                        </div>
                        <div>
                            <h4 class="text-[13px] font-extrabold text-slate-800 leading-tight">${update.topic}</h4>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">${update.batch ? update.batch.name : 'Unknown Batch'}</span>
                        </div>
                    </div>
                    <span class="px-4 py-1.5 bg-slate-50 rounded-xl text-[10px] font-bold text-slate-400 uppercase tracking-widest border border-slate-100">${update.date}</span>
                </div>
                <p class="text-[13px] text-slate-600 leading-relaxed font-medium pl-1">${update.description}</p>
            </div>
        `).join('');
    }

    document.getElementById('update-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const f = new FormData(e.target);
        
        try {
            const response = await fetch("/api/v1/institute/daily-updates", {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: JSON.stringify(Object.fromEntries(f.entries()))
            });

            const result = await response.json();
            if (result.status === 'success') {
                showToast(result.message, 'success');
                closeUpdateModal();
                fetchUpdates();
                e.target.reset();
            } else {
                showToast(result.message || 'Error publishing update', 'error');
            }
        } catch (error) { showToast('Network error', 'error'); }
    });

    function openUpdateModal() { document.getElementById('update-modal').classList.remove('hidden'); }
    function closeUpdateModal() { document.getElementById('update-modal').classList.add('hidden'); }
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
