@extends('layouts.institute')

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto pb-10">
    <!-- Toast Notifications Container -->
    <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

    <!-- Page Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Batch Management</h1>
            <p class="text-sm text-slate-400 mt-2 font-medium">Organize and monitor your academic cohorts.</p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="openBatchModal()" class="px-6 py-3 bg-[#1e3a8a] text-white rounded-2xl font-bold text-[13px] shadow-lg shadow-blue-900/10 hover:scale-[1.02] transition-transform">
                + Create New Batch
            </button>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center space-x-4">
            <div class="h-12 w-12 bg-blue-50 rounded-2xl flex items-center justify-center text-[#1e3a8a]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Active Batches</p>
                <h3 id="stat-total-batches" class="text-2xl font-extrabold text-slate-800 tracking-tight">--</h3>
            </div>
        </div>
        <!-- Add more stats if needed -->
    </div>

    <!-- Batches Table -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/20">
            <h3 class="text-sm font-extrabold text-slate-700 uppercase tracking-widest">Available Cohorts</h3>
            <div id="loading-spinner" class="hidden h-5 w-5 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin"></div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] uppercase font-extrabold text-slate-400 tracking-widest border-b border-slate-50">
                        <th class="px-8 py-5">Batch ID & Name</th>
                        <th class="px-8 py-5">Subject / Course</th>
                        <th class="px-8 py-5">Schedule (Timings)</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="batch-table-body" class="divide-y divide-slate-50">
                    <!-- Data populated via AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div id="pagination-container" class="px-8 py-6 bg-slate-50/20 flex items-center justify-between border-t border-slate-50">
            <!-- Pagination JS -->
        </div>
    </div>
</div>

<!-- Add/Edit Batch Modal -->
<div id="batch-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <div onclick="closeBatchModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="bg-white w-full max-w-xl rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="p-10">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 id="modal-title" class="text-2xl font-extrabold text-slate-800 tracking-tight">Create New Batch</h2>
                    <p id="modal-subtitle" class="text-sm text-slate-400 mt-1">Define settings for a new student cohort.</p>
                </div>
                <button onclick="closeBatchModal()" class="h-10 w-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="batch-form" class="space-y-5">
                <input type="hidden" id="batch-id" name="id">
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Batch Name</label>
                    <input type="text" name="name" id="field-name" required placeholder="Morning Class A" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Subject / Course</label>
                    <input type="text" name="subject" id="field-subject" required placeholder="Mathematics" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Start Time</label>
                        <input type="time" name="start_time" id="field-start" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">End Time</label>
                        <input type="time" name="end_time" id="field-end" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-50 flex items-center justify-end space-x-4">
                    <button type="button" onclick="closeBatchModal()" class="px-8 py-3.5 text-[13px] font-bold text-slate-400">Cancel</button>
                    <button type="submit" id="submit-btn" class="px-10 py-3.5 bg-[#1e3a8a] text-white rounded-2xl font-bold text-[13px] shadow-lg hover:scale-[1.02] transition-transform flex items-center">
                        <span id="btn-text">Confirm Batch</span>
                        <span id="btn-loader" class="hidden h-4 w-4 border-2 border-white/30 border-t-white rounded-full animate-spin ml-3"></span>
                    </button>
                </div>
            </form>
        </div>
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
            }
        } catch (error) {
            showToast('Failed to load batches', 'error');
        } finally {
            toggleLoader(false);
        }
    }

    async function handleSave(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const id = formData.get('id');
        const isEdit = id && id !== '';
        
        const url = isEdit ? `${API_URL}/${id}` : API_URL;
        
        // Prepare data for JSON body (API V1 expects JSON/FormData)
        const payload = Object.fromEntries(formData.entries());
        
        toggleSubmitLoading(true);

        try {
            const response = await fetch(url, {
                method: isEdit ? 'PUT' : 'POST',
                headers: { 
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN 
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (response.ok && result.status === 'success') {
                showToast(result.message, 'success');
                closeBatchModal();
                fetchBatches();
            } else {
                showToast(result.message || 'Error processing request', 'error');
            }
        } catch (error) {
            showToast('Network error', 'error');
        } finally {
            toggleSubmitLoading(false);
        }
    }

    async function deleteBatch(id) {
        if (!confirm('Are you sure you want to delete this batch?')) return;

        try {
            const response = await fetch(`${API_URL}/${id}`, {
                method: 'DELETE',
                headers: { 
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN 
                }
            });
            const result = await response.json();
            if (result.status === 'success') {
                showToast(result.message, 'success');
                fetchBatches();
            }
        } catch (error) {
            showToast('Delete failed', 'error');
        }
    }

    function renderBatches(items) {
        const container = document.getElementById('batch-table-body');
        if (items.length === 0) {
            container.innerHTML = `<tr><td colspan="5" class="px-8 py-20 text-center text-slate-400 font-medium">No batches found.</td></tr>`;
            return;
        }

        container.innerHTML = items.map(batch => `
            <tr class="hover:bg-slate-50/40 transition-all group animate-in fade-in slide-in-from-bottom-2 duration-300">
                <td class="px-8 py-6">
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 mr-4 shrink-0">
                            <span class="text-xs font-bold font-mono">B-${batch.id}</span>
                        </div>
                        <div class="flex flex-col">
                            <h4 class="text-[13px] font-extrabold text-slate-800 leading-tight">${batch.name}</h4>
                            <span class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest leading-none">Cohort Ref</span>
                        </div>
                    </div>
                </td>
                <td class="px-8 py-6">
                    <span class="text-[12px] font-bold text-slate-600">${batch.subject}</span>
                </td>
                <td class="px-8 py-6">
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 bg-slate-100 rounded-lg text-[10px] font-bold text-slate-500">${batch.start_time || '--:--'}</span>
                        <span class="text-slate-300 text-xs">→</span>
                        <span class="px-2 py-1 bg-slate-100 rounded-lg text-[10px] font-bold text-slate-500">${batch.end_time || '--:--'}</span>
                    </div>
                </td>
                <td class="px-8 py-6">
                    <div class="flex items-center">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 mr-2"></span>
                        <span class="text-[9px] font-extrabold text-emerald-600 uppercase tracking-widest">Active</span>
                    </div>
                </td>
                <td class="px-8 py-6 text-right">
                    <div class="flex items-center justify-end space-x-2">
                        <button onclick='openEditModal(${JSON.stringify(batch).replace(/'/g, "&apos;")})' class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                        <button onclick="deleteBatch(${batch.id})" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    function renderPagination(data) {
        const container = document.getElementById('pagination-container');
        if (data.last_page <= 1) {
            container.innerHTML = `<span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Showing ${data.total} Batches</span>`;
            return;
        }
        
        let html = `<span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Page ${data.current_page} of ${data.last_page}</span>`;
        html += `<div class="flex items-center gap-2">`;
        if (data.current_page > 1) 
            html += `<button onclick="fetchBatches(${data.current_page - 1})" class="px-4 py-2 bg-slate-50 rounded-xl text-[11px] font-bold text-slate-600">Previous</button>`;
        if (data.current_page < data.last_page)
            html += `<button onclick="fetchBatches(${data.current_page + 1})" class="px-4 py-2 bg-slate-50 rounded-xl text-[11px] font-bold text-slate-600">Next</button>`;
        html += `</div>`;
        container.innerHTML = html;
    }

    document.getElementById('batch-form').addEventListener('submit', handleSave);

    function openBatchModal() {
        document.getElementById('batch-form').reset();
        document.getElementById('batch-id').value = '';
        document.getElementById('modal-title').innerText = 'Create New Batch';
        document.getElementById('btn-text').innerText = 'Confirm Batch';
        showModal();
    }

    function openEditModal(batch) {
        document.getElementById('batch-id').value = batch.id;
        document.getElementById('field-name').value = batch.name;
        document.getElementById('field-subject').value = batch.subject;
        document.getElementById('field-start').value = batch.start_time;
        document.getElementById('field-end').value = batch.end_time;
        document.getElementById('modal-title').innerText = 'Edit Batch';
        document.getElementById('btn-text').innerText = 'Update Batch';
        showModal();
    }

    function showModal() { document.getElementById('batch-modal').classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    function closeBatchModal() { document.getElementById('batch-modal').classList.add('hidden'); document.body.style.overflow = 'auto'; }
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
