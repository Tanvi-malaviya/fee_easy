@extends('layouts.institute')

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto pb-10">
    <!-- Toast Notifications Container -->
    <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

    <!-- Page Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Teacher Faculty</h1>
            <p class="text-sm text-slate-400 mt-2 font-medium">Manage your teaching staff and their assignments.</p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="openTeacherModal()" class="px-6 py-3 bg-[#1e3a8a] text-white rounded-2xl font-bold text-[13px] shadow-lg shadow-blue-900/10 hover:scale-[1.02] transition-transform">
                + Register Teacher
            </button>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center space-x-4">
            <div class="h-12 w-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Total Faculty</p>
                <h3 id="stat-total-teachers" class="text-2xl font-extrabold text-slate-800 tracking-tight">--</h3>
            </div>
        </div>
    </div>

    <!-- Teachers Table -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/20">
            <h3 class="text-sm font-extrabold text-slate-700 uppercase tracking-widest">Faculty Directory</h3>
            <div id="loading-spinner" class="hidden h-5 w-5 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin"></div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] uppercase font-extrabold text-slate-400 tracking-widest border-b border-slate-50">
                        <th class="px-8 py-5">Teacher Info</th>
                        <th class="px-8 py-5">Specialization</th>
                        <th class="px-8 py-5">Contact Details</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="teacher-table-body" class="divide-y divide-slate-50">
                    <!-- Data via AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div id="pagination-container" class="px-8 py-6 bg-slate-50/20 flex items-center justify-between border-t border-slate-50"></div>
    </div>
</div>

<!-- Modal -->
<div id="teacher-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <div onclick="closeTeacherModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="p-10">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 id="modal-title" class="text-2xl font-extrabold text-slate-800 tracking-tight">Register Teacher</h2>
                    <p class="text-sm text-slate-400 mt-1">Add a new faculty member to your institute.</p>
                </div>
                <button onclick="closeTeacherModal()" class="h-10 w-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="teacher-form" class="space-y-5">
                <input type="hidden" id="teacher-id" name="id">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                        <input type="text" name="name" id="field-name" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none ring-blue-500/10 focus:ring-4 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Subject</label>
                        <input type="text" name="subject" id="field-subject" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none ring-blue-500/10 focus:ring-4 transition-all" placeholder="e.g. Physics">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Contact No.</label>
                        <input type="text" name="phone" id="field-phone" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none ring-blue-500/10 focus:ring-4 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                        <input type="email" name="email" id="field-email" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none ring-blue-500/10 focus:ring-4 transition-all">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Salary (Monthly)</label>
                        <input type="number" name="salary" id="field-salary" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none ring-blue-500/10 focus:ring-4 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Status</label>
                        <select name="status" id="field-status" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none ring-blue-500/10 focus:ring-4 transition-all">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-50 flex items-center justify-end space-x-4">
                    <button type="button" onclick="closeTeacherModal()" class="px-8 py-3.5 text-[13px] font-bold text-slate-400">Cancel</button>
                    <button type="submit" id="submit-btn" class="px-10 py-3.5 bg-[#1e3a8a] text-white rounded-2xl font-bold text-[13px] shadow-lg hover:scale-[1.02] transition-transform flex items-center">
                        <span id="btn-text">Confirm Registration</span>
                        <span id="btn-loader" class="hidden h-4 w-4 border-2 border-white/30 border-t-white rounded-full animate-spin ml-3"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const API_URL = "/api/v1/institute/teachers";
    const CSRF_TOKEN = "{{ csrf_token() }}";

    document.addEventListener('DOMContentLoaded', () => fetchTeachers());

    async function fetchTeachers(page = 1) {
        toggleLoader(true);
        try {
            const response = await fetch(`${API_URL}?page=${page}`, { headers: { 'Accept': 'application/json' } });
            const result = await response.json();
            if (result.status === 'success') {
                renderTeachers(result.data.items);
                renderPagination(result.data);
                document.getElementById('stat-total-teachers').innerText = result.data.total;
            }
        } catch (error) { showToast('Sync error', 'error'); }
        finally { toggleLoader(false); }
    }

    function renderTeachers(items) {
        const container = document.getElementById('teacher-table-body');
        if (items.length === 0) {
            container.innerHTML = `<tr><td colspan="5" class="px-8 py-20 text-center text-slate-400 font-medium italic">No teachers registered yet.</td></tr>`;
            return;
        }

        container.innerHTML = items.map(t => `
            <tr class="hover:bg-slate-50/40 transition-all group animate-in fade-in slide-in-from-bottom-2 duration-300">
                <td class="px-8 py-6">
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-xl bg-indigo-50 flex items-center justify-center border border-indigo-100 mr-4">
                            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(t.name)}&background=1e3a8a&color=fff" class="w-full h-full rounded-xl">
                        </div>
                        <div class="flex flex-col">
                            <h4 class="text-[13px] font-extrabold text-slate-800 leading-tight">${t.name}</h4>
                            <span class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest leading-none">${t.designation || 'Faculty'}</span>
                        </div>
                    </div>
                </td>
                <td class="px-8 py-6">
                    <span class="px-3 py-1 bg-slate-100 rounded-lg text-[10px] font-bold text-slate-500 uppercase tracking-widest">${t.subject || 'All Subjects'}</span>
                </td>
                <td class="px-8 py-6">
                    <div class="flex flex-col">
                        <span class="text-[12px] font-bold text-slate-600">${t.phone || 'N/A'}</span>
                        <span class="text-[10px] font-medium text-slate-400">${t.email || ''}</span>
                    </div>
                </td>
                <td class="px-8 py-6">
                    <span class="px-3 py-1 rounded-full text-[9px] font-extrabold uppercase tracking-widest ${t.status === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-400'}">${t.status}</span>
                </td>
                <td class="px-8 py-6 text-right">
                    <div class="flex items-center justify-end space-x-2">
                        <button onclick='openEditModal(${JSON.stringify(t)})' class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                        <button onclick="deleteTeacher(${t.id})" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
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
            container.innerHTML = `<span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Showing ${data.total} Faculty Members</span>`;
            return;
        }
        let html = `<span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Page ${data.current_page} of ${data.last_page}</span>`;
        html += `<div class="flex items-center gap-2">`;
        if (data.current_page > 1) html += `<button onclick="fetchTeachers(${data.current_page - 1})" class="px-4 py-2 bg-slate-50 rounded-xl text-[11px] font-bold text-slate-600">Prev</button>`;
        if (data.current_page < data.last_page) html += `<button onclick="fetchTeachers(${data.current_page + 1})" class="px-4 py-2 bg-slate-50 rounded-xl text-[11px] font-bold text-slate-600">Next</button>`;
        html += `</div>`;
        container.innerHTML = html;
    }

    document.getElementById('teacher-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const f = new FormData(e.target);
        const id = f.get('id');
        const isEdit = id && id !== '';
        const url = isEdit ? `${API_URL}/${id}` : API_URL;

        toggleSubmitLoading(true);
        try {
            const response = await fetch(url, {
                method: isEdit ? 'PUT' : 'POST',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: JSON.stringify(Object.fromEntries(f.entries()))
            });
            const result = await response.json();
            if (result.status === 'success') {
                showToast(result.message);
                closeTeacherModal();
                fetchTeachers();
            } else { showToast(result.message, 'error'); }
        } catch (error) { showToast('Server error', 'error'); }
        finally { toggleSubmitLoading(false); }
    });

    async function deleteTeacher(id) {
        if(!confirm('Delete this teacher registration?')) return;
        try {
            const r = await fetch(`${API_URL}/${id}`, { method: 'DELETE', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN } });
            const res = await r.json();
            if (res.status === 'success') { showToast(res.message); fetchTeachers(); }
        } catch (e) { showToast('Delete failed', 'error'); }
    }

    function openTeacherModal() { 
        document.getElementById('teacher-form').reset(); 
        document.getElementById('teacher-id').value = '';
        document.getElementById('modal-title').innerText = 'Register Teacher';
        showModal(); 
    }
    function openEditModal(t) {
        document.getElementById('teacher-id').value = t.id;
        document.getElementById('field-name').value = t.name;
        document.getElementById('field-subject').value = t.subject;
        document.getElementById('field-phone').value = t.phone;
        document.getElementById('field-email').value = t.email;
        document.getElementById('field-salary').value = t.salary;
        document.getElementById('field-status').value = t.status;
        document.getElementById('modal-title').innerText = 'Edit Teacher';
        showModal();
    }
    function showModal() { document.getElementById('teacher-modal').classList.remove('hidden'); }
    function closeTeacherModal() { document.getElementById('teacher-modal').classList.add('hidden'); }
    function toggleLoader(s) { document.getElementById('loading-spinner').classList.toggle('hidden', !s); }
    function toggleSubmitLoading(s) { document.getElementById('btn-loader').classList.toggle('hidden', !s); document.getElementById('submit-btn').disabled = s; }
    function showToast(m, t = 'success') {
        const c = document.getElementById('toast-container');
        const d = document.createElement('div');
        const clr = t === 'success' ? 'emerald' : 'rose';
        d.className = `bg-${clr}-50 border border-${clr}-200 text-${clr}-600 px-6 py-4 rounded-2xl shadow-xl flex items-center animate-in slide-in-from-right-10 duration-300`;
        d.innerHTML = `<span class="text-sm font-bold">${m}</span>`;
        c.appendChild(d);
        setTimeout(() => d.remove(), 3000);
    }
</script>
@endsection
