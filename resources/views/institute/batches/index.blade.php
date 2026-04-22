@extends('layouts.institute')

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto pb-10">
    <!-- Toast Notifications Container -->
    <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

    <!-- Batches Table Container -->
    <div class="bg-white rounded-[1rem] shadow-sm border border-slate-100 overflow-hidden">
        <!-- Unified Header Toolbar -->
        <div class="px-8 py-6 border-b border-slate-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/20">
            <div>
                <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Batch Management</h1>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Available Cohorts</span>
                    <div id="loading-spinner" class="hidden h-3 w-3 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin"></div>
                </div>
            </div>
            
            <button onclick="openBatchModal()" class="px-6 py-2.5 bg-[#1e3a8a] text-white rounded-xl font-bold text-[11px] shadow-lg shadow-blue-900/10 hover:scale-[1.02] transition-transform flex items-center uppercase tracking-widest">
                <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create New Batch
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] uppercase font-extrabold text-slate-400 tracking-widest border-b border-slate-50">
                        <th class="px-6 py-4">Batch ID & Name</th>
                        <th class="px-6 py-4">Subject</th>
                        <!-- <th class="px-6 py-4">Description</th> -->
                        <th class="px-6 py-4">Schedule</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
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
    <div class="bg-white w-full max-w-2xl rounded-[2rem] shadow-2xl relative z-10 overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="p-5">   
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 id="modal-title" class="text-xl font-extrabold text-slate-800 tracking-tight">Create New Batch</h2>
                    <p id="modal-subtitle" class="text-xs text-slate-400 mt-0.5">Define settings for a new cohort.</p>
                </div>
                <button onclick="closeBatchModal()" class="h-9 w-9 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="batch-form" class="space-y-4">
                <input type="hidden" id="batch-id" name="id">
                
                <div class="grid grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Batch Name</label>
                        <input type="text" name="name" id="field-name" required placeholder="Class A" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-[13px] font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Subject</label>
                        <input type="text" name="subject" id="field-subject" required placeholder="Math" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-[13px] font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Monthly Fees</label>
                        <input type="number" name="fees" id="field-fees" placeholder="0" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-[13px] font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Description</label>
                    <textarea name="description" id="field-description" placeholder="Brief details about this cohort..." rows="2" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-[13px] font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all resize-none"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Start Time</label>
                        <input type="time" name="start_time" id="field-start" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-[13px] font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">End Time</label>
                        <input type="time" name="end_time" id="field-end" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-[13px] font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Active Days</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                        <label class="relative flex-1 min-w-[60px] cursor-pointer group">
                            <input type="checkbox" name="days[]" value="{{ $day }}" class="peer sr-only day-checkbox">
                            <div class="w-full py-2 bg-slate-50 border border-slate-100 rounded-xl text-[11px] font-bold text-slate-400 text-center transition-all peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 group-hover:bg-slate-100">
                                {{ $day }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-50 flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeBatchModal()" class="px-6 py-2.5 text-[12px] font-bold text-slate-400">Cancel</button>
                    <button type="submit" id="submit-btn" class="px-8 py-2.5 bg-[#1e3a8a] text-white rounded-xl font-bold text-[12px] shadow-lg hover:scale-[1.02] transition-transform flex items-center">
                        <span id="btn-text">Confirm</span>
                        <span id="btn-loader" class="hidden h-3 w-3 border-2 border-white/30 border-t-white rounded-full animate-spin ml-2"></span>
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
        
        // Collect days array
        const days = Array.from(form.querySelectorAll('.day-checkbox:checked')).map(cb => cb.value);
        
        // Prepare data for JSON body
        const payload = Object.fromEntries(formData.entries());
        delete payload['days[]']; // Remove the PHP-style array key
        payload.days = days; // Add the actual array
        
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
            container.innerHTML = `<tr><td colspan="6" class="px-8 py-20 text-center text-slate-400 font-medium">No batches found.</td></tr>`;
            return;
        }

        container.innerHTML = items.map(batch => `
            <tr class="hover:bg-slate-50/40 transition-all group animate-in fade-in slide-in-from-bottom-2 duration-300">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="h-9 w-9 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 mr-4 shrink-0">
                            <span class="text-[10px] font-bold font-mono">B-${batch.id}</span>
                        </div>
                        <div class="flex flex-col">
                            <h4 class="text-[13px] font-extrabold text-slate-800 leading-tight">${batch.name}</h4>
                            <span class="text-[9px] font-bold text-slate-400 mt-0.5 uppercase tracking-widest leading-none">Cohort Ref</span>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex flex-col">
                        <span class="text-[12px] font-bold text-slate-600">${batch.subject}</span>
                        <span class="text-[10px] font-bold text-blue-600 mt-0.5">₹${batch.fees || '0'}</span>
                    </div>
                </td>
                
                <td class="px-6 py-4">
                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-1 bg-slate-100 rounded-lg text-[10px] font-bold text-slate-500">${batch.start_time || '--:--'}</span>
                            <span class="text-slate-300 text-xs">→</span>
                            <span class="px-2 py-1 bg-slate-100 rounded-lg text-[10px] font-bold text-slate-500">${batch.end_time || '--:--'}</span>
                        </div>
                        <div class="flex flex-wrap gap-1">
                            ${(batch.days || []).map(day => `<span class="px-1.5 py-0.5 bg-blue-50 text-blue-600 rounded text-[8px] font-black uppercase tracking-tighter border border-blue-100">${day}</span>`).join('')}
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 mr-2"></span>
                        <span class="text-[9px] font-extrabold text-emerald-600 uppercase tracking-widest">Active</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end space-x-1">
                        <a href="/institute/batches/${batch.id}" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all" title="View Details">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <button onclick='openEditModal(${JSON.stringify(batch).replace(/'/g, "&apos;")})' class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Edit Batch">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                        <button onclick="deleteBatch(${batch.id})" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Delete Batch">
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
        document.getElementById('field-fees').value = '';
        document.getElementById('field-description').value = '';
        
        // Clear checkboxes
        document.querySelectorAll('.day-checkbox').forEach(cb => cb.checked = false);

        document.getElementById('modal-title').innerText = 'Create New Batch';
        document.getElementById('btn-text').innerText = 'Confirm Batch';
        showModal();
    }

    function openEditModal(batch) {
        console.log('Editing batch:', batch);
        document.getElementById('batch-id').value = batch.id || '';
        document.getElementById('field-name').value = batch.name || '';
        document.getElementById('field-subject').value = batch.subject || '';
        document.getElementById('field-fees').value = batch.fees || '';
        document.getElementById('field-description').value = batch.description || '';
        document.getElementById('field-start').value = batch.start_time || '';
        document.getElementById('field-end').value = batch.end_time || '';
        
        // Reset and set checkboxes
        const days = batch.days || [];
        document.querySelectorAll('.day-checkbox').forEach(cb => {
            cb.checked = days.includes(cb.value);
        });

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
