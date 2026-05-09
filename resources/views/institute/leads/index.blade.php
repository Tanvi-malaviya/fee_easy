@extends('layouts.institute')

@section('content')
<div class="min-h-screen bg-[#f3f4f6] -mt-6 -mx-4 px-8 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-12">
            <h1 class="text-xl font-bold text-gray-800">Leads Management</h1>
            <nav class="flex items-center gap-6">
                <a href="#" class="text-sm font-medium text-[#A8440B] border-b-2 border-[#A8440B] pb-1">Leads</a>
                <a href="#" class="text-sm font-medium text-gray-400 hover:text-gray-600 transition-colors pb-1">Analytics</a>
                <a href="#" class="text-sm font-medium text-gray-400 hover:text-gray-600 transition-colors pb-1">Settings</a>
            </nav>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="openLeadModal()" class="px-4 py-2 bg-[#A8440B] text-white rounded-lg text-sm font-semibold hover:bg-[#8e3a09] transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Add Lead
            </button>
            <div class="h-10 w-10 rounded-full overflow-hidden border-2 border-white shadow-sm">
                <img src="https://ui-avatars.com/api/?name=Admin&background=random" alt="Profile" class="w-full h-full object-cover">
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-12 gap-8 h-[calc(100vh-160px)]">
        
        <!-- Sidebar -->
        <div class="col-span-3 flex flex-col gap-4">
            <!-- Search & Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="relative mb-4">
                    <input type="text" id="lead-search" placeholder="Search leads..." oninput="fetchLeads()"
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-100 border-none rounded-lg text-sm focus:ring-2 focus:ring-[#A8440B]/20 transition-all outline-none">
                    <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button onclick="setStatusFilter('All')" data-status="All" class="status-filter px-3 py-1.5 rounded-full text-[11px] font-bold bg-[#A8440B] text-white transition-all">All Leads</button>
                    <button onclick="setStatusFilter('New')" data-status="New" class="status-filter px-3 py-1.5 rounded-full text-[11px] font-bold bg-gray-100 text-gray-500 hover:bg-gray-200 transition-all">New</button>
                    <button onclick="setStatusFilter('Contacted')" data-status="Contacted" class="status-filter px-3 py-1.5 rounded-full text-[11px] font-bold bg-gray-100 text-gray-500 hover:bg-gray-200 transition-all">Contacted</button>
                </div>
            </div>

            <!-- List Container -->
            <div id="lead-list-container" class="bg-white rounded-xl shadow-sm border border-gray-200 flex-1 overflow-y-auto divide-y divide-gray-100">
                <!-- Loader -->
                <div class="p-12 text-center loader-container">
                    <div class="h-8 w-8 border-3 border-gray-100 border-t-[#A8440B] rounded-full animate-spin mx-auto mb-3"></div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Loading Leads...</p>
                </div>
            </div>
        </div>

        <!-- Detail View -->
        <div class="col-span-9">
            <!-- Empty State -->
            <div id="lead-empty-state" class="h-full bg-white rounded-3xl border border-gray-200 shadow-sm flex flex-col items-center justify-center text-center p-12">
                <div class="h-20 w-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Select a lead to view details</h3>
                <p class="text-sm text-gray-400 max-w-sm">Choose a lead from the left sidebar to see their contact information and interaction history.</p>
            </div>

            <!-- Content Area -->
            <div id="lead-content" class="hidden h-full flex flex-col gap-6 overflow-y-auto pr-2 custom-scrollbar">
                <!-- Header Card -->
                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-6">
                            <div id="lead-avatar" class="h-20 w-20 bg-[#e67e22] rounded-2xl flex items-center justify-center text-2xl font-bold text-white shadow-lg shadow-orange-200">
                                JM
                            </div>
                            <div>
                                <h2 id="detail-name" class="text-3xl font-bold text-gray-800 mb-1">Julianne Moore</h2>
                                <div class="flex items-center gap-2 text-gray-400 font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                    <span id="detail-course">Advanced UX Research Course</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button onclick="editLead()" class="px-6 py-2.5 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </button>
                            <button class="px-6 py-2.5 bg-[#006d77] text-white rounded-xl text-sm font-bold hover:bg-[#005a63] transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Send Email
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Info Cards Grid -->
                <div class="grid grid-cols-2 gap-6">
                    <!-- Contact Info -->
                    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-8">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="h-8 w-8 bg-gray-50 rounded-lg flex items-center justify-center text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Contact Information</h3>
                        </div>
                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Email Address</label>
                                    <p id="detail-email" class="text-sm font-bold text-gray-700">julianne.m@example.com</p>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Phone Number</label>
                                    <p id="detail-phone" class="text-sm font-bold text-gray-700">+1 (555) 098-7654</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Address</label>
                                <p id="detail-address" class="text-sm font-bold text-gray-700 leading-relaxed">1248 Oakwood Ave, Suite 400, San Francisco, CA 94105</p>
                            </div>
                        </div>
                    </div>

                    <!-- Reference Info -->
                    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-8">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="h-8 w-8 bg-gray-50 rounded-lg flex items-center justify-center text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Reference</h3>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Lead Source</label>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-orange-400" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                                    <p id="detail-source" class="text-sm font-bold text-gray-700">Alumni Referral</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Referrer</label>
                                    <p class="text-sm font-bold text-gray-700">David Smith (Cohort #12)</p>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Acquisition Date</label>
                                    <p id="detail-date" class="text-sm font-bold text-gray-700">Oct 24, 2023</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interaction Notes -->
                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-8 mb-12">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 bg-gray-50 rounded-lg flex items-center justify-center text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Interaction Notes</h3>
                        </div>
                        <button onclick="openNoteModal()" class="text-[11px] font-bold text-[#A8440B] hover:underline flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Add Note
                        </button>
                    </div>
                    
                    <div id="notes-timeline" class="space-y-0 relative before:absolute before:left-[17px] before:top-2 before:bottom-2 before:w-[1.5px] before:bg-gray-100">
                        <!-- Notes injected here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@include('institute.leads.modals')

@push('scripts')
<script>
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    let currentLeads = [];
    let selectedLeadId = null;
    let currentStatusFilter = 'All';

    async function fetchLeads() {
        const searchInput = document.getElementById('lead-search');
        const search = searchInput ? searchInput.value : '';
        const container = document.getElementById('lead-list-container');
        
        try {
            const url = `/api/v1/institute/leads?status=${currentStatusFilter}&search=${encodeURIComponent(search)}&_t=${Date.now()}`;
            const response = await fetch(url, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN } });
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            const result = await response.json();
            
            let leads = [];
            if (result && Array.isArray(result.data)) leads = result.data;
            else if (result && result.data && Array.isArray(result.data.items)) leads = result.data.items;

            currentLeads = leads;
            renderLeadList();
        } catch (error) {
            console.error('Fetch Error:', error);
            container.innerHTML = `<div class="p-8 text-center text-[10px] font-bold text-rose-500">Sync Error</div>`;
        }
    }

    function renderLeadList() {
        const container = document.getElementById('lead-list-container');
        if (!container) return;

        if (currentLeads.length === 0) {
            container.innerHTML = `<div class="p-12 text-center text-xs font-medium text-gray-400">No leads found</div>`;
            return;
        }

        container.innerHTML = currentLeads.map(lead => `
            <div onclick="selectLead(${lead.id})" 
                class="relative p-6 cursor-pointer hover:bg-gray-50 transition-all ${selectedLeadId == lead.id ? 'bg-gray-50' : ''}">
                <div class="flex items-start justify-between mb-1">
                    <h4 class="text-sm font-bold text-gray-800">${lead.full_name}</h4>
                    <span class="px-2 py-0.5 rounded-[4px] text-[8px] font-black uppercase tracking-wider ${getStatusBadgeClass(lead.status)}">
                        ${lead.status}
                    </span>
                </div>
                <p class="text-xs text-gray-400 font-medium mb-3 truncate">${lead.course_selection || 'General Inquiry'}</p>
                <div class="flex items-center gap-4 text-[10px] font-bold text-gray-300">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        ${getTimeAgo(lead.created_at)}
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        ${lead.reference || 'Referral'}
                    </div>
                </div>
            </div>
        `).join('');
    }

    function getStatusBadgeClass(status) {
        switch (status) {
            case 'New': return 'bg-emerald-50 text-emerald-600';
            case 'Contacted': return 'bg-sky-50 text-sky-500';
            case 'Qualified': return 'bg-gray-100 text-gray-500';
            case 'Lost': return 'bg-rose-50 text-rose-500';
            default: return 'bg-gray-50 text-gray-400';
        }
    }

    function setStatusFilter(status) {
        currentStatusFilter = status;
        document.querySelectorAll('.status-filter').forEach(btn => {
            if (btn.dataset.status === status) {
                btn.classList.add('bg-[#A8440B]', 'text-white');
                btn.classList.remove('bg-gray-100', 'text-gray-500');
            } else {
                btn.classList.remove('bg-[#A8440B]', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-500');
            }
        });
        fetchLeads();
    }

    async function selectLead(id) {
        selectedLeadId = id;
        renderLeadList();
        document.getElementById('lead-empty-state').classList.add('hidden');
        document.getElementById('lead-content').classList.remove('hidden');

        try {
            const response = await fetch(`/api/v1/institute/leads/${id}`);
            const result = await response.json();
            if (result.data) {
                const lead = result.data;
                document.getElementById('detail-name').textContent = lead.full_name;
                document.getElementById('detail-course').textContent = lead.course_selection || 'General Inquiry';
                document.getElementById('detail-email').textContent = lead.email || 'N/A';
                document.getElementById('detail-phone').textContent = lead.phone || 'N/A';
                document.getElementById('detail-address').textContent = lead.address || 'N/A';
                document.getElementById('detail-source').textContent = lead.reference || 'Alumni Referral';
                document.getElementById('detail-date').textContent = lead.created_at ? new Date(lead.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A';
                document.getElementById('lead-avatar').textContent = lead.full_name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
                renderTimeline(lead.notes);
            }
        } catch (error) { console.error(error); }
    }

    function renderTimeline(notes) {
        const timeline = document.getElementById('notes-timeline');
        if (!notes || notes.length === 0) {
            timeline.innerHTML = '<p class="text-xs text-gray-400 font-medium italic py-4 pl-10">No records found.</p>';
            return;
        }
        timeline.innerHTML = notes.map((note, index) => `
            <div class="relative pl-12 pb-8">
                <div class="absolute left-0 top-1 h-[34px] w-[34px] rounded-full bg-white border-2 ${index === 0 ? 'border-orange-500' : 'border-sky-200'} flex items-center justify-center z-10">
                    <div class="h-1.5 w-1.5 rounded-full ${index === 0 ? 'bg-orange-500' : 'bg-sky-200'}"></div>
                </div>
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-xs font-bold text-gray-800">${note.title}</h4>
                    <span class="text-[10px] font-bold text-gray-300">${new Date(note.created_at).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</span>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <p class="text-xs font-medium text-gray-500 leading-relaxed">${note.note}</p>
                </div>
            </div>
        `).join('');
    }

    function getTimeAgo(dateString) {
        if (!dateString) return 'unknown';
        const date = new Date(dateString);
        const diff = Math.floor((new Date() - date) / 1000);
        if (diff < 60) return 'just now';
        if (diff < 3600) return `${Math.floor(diff / 60)}m ago`;
        if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`;
        return `${Math.floor(diff / 86400)}d ago`;
    }

    function openLeadModal() {
        const modal = document.getElementById('lead-modal');
        const content = document.getElementById('lead-modal-content');
        document.getElementById('modal-title').textContent = 'Add Lead Data';
        document.getElementById('lead-id').value = '';
        document.getElementById('lead-form').reset();
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeLeadModal() {
        const modal = document.getElementById('lead-modal');
        const content = document.getElementById('lead-modal-content');
        content.classList.add('scale-95', 'opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    async function saveLead(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        const leadId = data.id;
        
        const saveBtn = document.getElementById('save-lead-btn');
        const originalText = saveBtn.innerHTML;
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<div class="h-4 w-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div> Saving...';

        try {
            const url = leadId ? `/api/v1/institute/leads/${leadId}` : '/api/v1/institute/leads';
            const method = leadId ? 'PUT' : 'POST';
            
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                closeLeadModal();
                fetchLeads();
            } else {
                const errorData = await response.json();
                alert('Error: ' + (errorData.message || 'Failed to save lead'));
            }
        } catch (error) {
            console.error('Save Error:', error);
            alert('Connection error. Please try again.');
        } finally {
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
        }
    }

    function editLead() {
        const lead = currentLeads.find(l => l.id == selectedLeadId);
        if (!lead) return;

        openLeadModal();
        document.getElementById('modal-title').textContent = 'Edit Lead Data';
        document.getElementById('lead-id').value = lead.id;
        
        const form = document.getElementById('lead-form');
        form.elements['full_name'].value = lead.full_name || '';
        form.elements['phone'].value = lead.phone || '';
        form.elements['email'].value = lead.email || '';
        form.elements['address'].value = lead.address || '';
        form.elements['course_selection'].value = lead.course_selection || '';
        form.elements['reference'].value = lead.reference || '';
    }

    function openNoteModal() {
        const modal = document.getElementById('note-modal');
        const content = document.getElementById('note-modal-content');
        document.getElementById('note-form').reset();
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeNoteModal() {
        const modal = document.getElementById('note-modal');
        const content = document.getElementById('note-modal-content');
        content.classList.add('scale-95', 'opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    async function saveNote(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        data.lead_id = selectedLeadId;

        try {
            const response = await fetch('/api/v1/institute/leads/notes', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                closeNoteModal();
                selectLead(selectedLeadId);
            }
        } catch (error) {
            console.error('Save Note Error:', error);
        }
    }

    fetchLeads();
</script>
@endpush

<style>
    body { font-family: 'Inter', -apple-system, sans-serif; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
</style>
@endsection
