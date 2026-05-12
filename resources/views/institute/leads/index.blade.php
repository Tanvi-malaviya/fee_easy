@extends('layouts.institute')

@section('content')
    <div>
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <div class="flex items-center gap-4 md:gap-12">
                <h1 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight">
                    Leads Management
                </h1>
            </div>

            <div class="flex items-center gap-4">
                <button onclick="openLeadModal()"
                    class="px-4 py-2 bg-[#A8440B] text-white rounded-lg text-sm font-semibold hover:bg-[#8e3a09] transition-all flex items-center gap-2">

                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>

                    Add Lead
                </button>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 pb-20 lg:pb-0 min-h-screen">

            <!-- Sidebar -->
            <div class="col-span-1 lg:col-span-3">

                <div class="lg:sticky lg:top-4 h-fit">

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col overflow-hidden h-[calc(100vh-2rem)]">

                        <!-- Search Area -->
                        <div class="p-4 border-b border-gray-100 bg-gray-50/50 shrink-0">

                            <div class="flex gap-2">

                                <div class="relative flex-1">
                                    <input type="text" id="lead-search" placeholder="Search leads..."
                                        class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#A8440B]/20 transition-all outline-none">

                                    <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>

                                <button onclick="fetchLeads()"
                                    class="px-4 py-2 bg-[#A8440B] text-white rounded-lg hover:bg-[#8e3a09] transition-all flex items-center justify-center shadow-sm">

                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>

                                </button>
                            </div>
                        </div>

                        <!-- List Container -->
                        <div id="lead-list-container"
                            class="flex-1 overflow-y-auto divide-y divide-gray-100 custom-scrollbar">

                            <!-- Loader -->
                            <div class="p-12 text-center loader-container">

                                <div
                                    class="h-8 w-8 border-[3px] border-gray-100 border-t-[#A8440B] rounded-full animate-spin mx-auto mb-3">
                                </div>

                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                    Loading Leads...
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Area -->
            <div id="detail-panel" class="col-span-1 lg:col-span-9 rounded-xl flex flex-col relative">

                <!-- Empty State -->
                <div id="detail-empty"
                    class="flex-1 flex flex-col items-center justify-center p-8 text-center bg-gray-50/30 min-h-[500px]">

                    <div
                        class="h-20 w-20 bg-gray-100 rounded-full flex items-center justify-center mb-6 text-gray-400">

                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                        </svg>

                    </div>

                    <h3 class="text-base font-bold text-gray-800 mb-2 tracking-tight">
                        Select a Lead
                    </h3>

                    <p class="text-xs text-gray-400 max-w-[240px] leading-relaxed">
                        Choose a lead from the sidebar to view their full profile,
                        interaction history, and analytics.
                    </p>
                </div>

                <!-- Detail Loader -->
                <div id="lead-detail-loader"
                    class="hidden h-full bg-white rounded-xl border border-gray-200 shadow-sm flex flex-col items-center justify-center text-center p-12 min-h-[500px]">

                    <div
                        class="h-12 w-12 border-4 border-gray-100 border-t-[#A8440B] rounded-full animate-spin mb-4">
                    </div>

                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                        Fetching Details...
                    </p>
                </div>

                <!-- Content Area -->
                <div id="detail-content" class="hidden flex flex-col gap-2">

                    <!-- Header Card -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div id="lead-avatar"
                                    class="h-16 w-16 bg-[#e67e22] rounded-2xl flex items-center justify-center text-xl font-bold text-white shadow-lg shadow-orange-200 shrink-0">
                                    JM
                                </div>
                                <div>
                                    <h2 id="detail-name" class="text-xl md:text-2xl font-bold text-gray-800 mb-0.5">Julianne Moore</h2>
                                    <div class="flex items-center gap-2 text-gray-400 font-medium">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                        </svg>
                                        <span id="detail-course" class="text-xs">Advanced UX Research Course</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <button onclick="editLead()"
                                    class="px-4 py-1.5 bg-white border-2 border-[#007B8A] text-[#007B8A] rounded-xl text-xs font-bold hover:bg-[#007B8A]/5 transition-all flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </button>
                                <button onclick="deleteLead()"
                                    class="px-4 py-1.5 bg-white border-2 border-[#D32F2F] text-[#D32F2F] rounded-xl text-xs font-bold hover:bg-[#D32F2F]/5 transition-all flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete Lead
                                </button>
                            </div>
                        </div>
                    </div>

                   <!-- Info Cards Grid -->
                    <div class="grid grid-cols-2 gap-2">
                        <!-- Contact Info Card -->
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div class="h-8 w-8 bg-gray-50 rounded-lg flex items-center justify-center text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Contact Information
                                </h3>
                            </div>
                            <div class="space-y-6">
                                <div class="flex flex-wrap gap-x-12 gap-y-4">
                                    <div class="min-w-[180px] max-w-full">
                                        <label
                                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Email
                                            Address</label>
                                        <p id="detail-email" class="text-sm font-bold text-gray-700 break-all">
                                            julianne.m@example.com</p>
                                    </div>
                                    <div class="min-w-[140px]">
                                        <label
                                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Phone
                                            Number</label>
                                        <p id="detail-phone" class="text-sm font-bold text-gray-700">+1 (555) 098-7654</p>
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Address</label>
                                    <p id="detail-address" class="text-sm font-bold text-gray-700 leading-relaxed">1248
                                        Oakwood Ave, Suite 400, San Francisco, CA 94105</p>
                                </div>
                            </div>
                        </div>

                        <!-- Reference Card -->
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div class="h-8 w-8 bg-gray-50 rounded-lg flex items-center justify-center text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Reference</h3>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <label
                                        class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Lead
                                        Source</label>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                        </svg>
                                        <p id="detail-source" class="text-sm font-bold text-gray-700">Alumni Referral</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Referrer</label>
                                        <p id="detail-referrer" class="text-sm font-bold text-gray-700">David Smith (Cohort
                                            #12)</p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Acquisition
                                            Date</label>
                                        <p id="detail-date" class="text-sm font-bold text-gray-700">Oct 24, 2023</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Interaction Notes -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 bg-gray-50 rounded-lg flex items-center justify-center text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Interaction Notes</h3>
                            </div>
                            <button onclick="openNoteModal()"
                                class="text-[11px] font-bold text-[#A8440B] hover:underline flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Note
                            </button>
                        </div>

                        <div id="notes-timeline"
                            class="space-y-0 relative before:absolute before:left-[13px] before:top-2 before:bottom-2 before:w-[1.5px] before:bg-gray-100">
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
            let selectedLeadData = null;
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

                    if (currentLeads.length > 0 && !selectedLeadId) {
                        selectLead(currentLeads[0].id);
                    } else {
                        renderLeadList();
                    }
                } catch (error) {
                    console.error('Fetch Error:', error);
                    let errorMsg = 'Sync Error';
                    if (error.message.includes('HTTP')) errorMsg = `Error: ${error.message}`;
                    else if (error.message) errorMsg = error.message;
                    
                    container.innerHTML = `
                        <div class="p-8 text-center">
                            <div class="text-[10px] font-bold text-rose-500 mb-1">${errorMsg}</div>
                            <button onclick="fetchLeads()" class="text-[9px] font-bold text-gray-400 hover:text-gray-600 underline">Try Again</button>
                        </div>
                    `;
                }
            }

            function renderLeadList() {
                const container = document.getElementById('lead-list-container');
                if (!container) return;

                if (currentLeads.length === 0) {
                    container.innerHTML = `<div class="p-12 text-center text-xs font-medium text-gray-400">No leads found</div>`;
                    return;
                }

                container.innerHTML = currentLeads.map(lead => {
                    const isActive = String(selectedLeadId) == String(lead.id);
                    return `
                        <div onclick="selectLead('${lead.id}')" 
                            class="relative p-5 cursor-pointer hover:bg-gray-50 transition-all ${isActive ? 'bg-[#A8440B]/5' : ''}">
                            <div class="flex items-start justify-between mb-1">
                                <h4 class="text-sm font-bold ${isActive ? 'text-[#A8440B]' : 'text-gray-800'}">${lead.full_name}</h4>
                            </div>
                            <p class="text-xs ${isActive ? 'text-orange-900/60' : 'text-gray-600'} font-medium mb-3 truncate">${lead.course_selection || 'General Inquiry'}</p>
                            <div class="flex items-center gap-4 text-[10px] font-bold ${isActive ? 'text-orange-400' : 'text-gray-500'}">
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
                    `;
                }).join('');
            }

            async function selectLead(id) {
                selectedLeadId = id;
                renderLeadList();

                const emptyState = document.getElementById('detail-empty');
                const contentArea = document.getElementById('detail-content');
                const loader = document.getElementById('lead-detail-loader');

                if (emptyState) emptyState.classList.add('hidden');
                if (contentArea) contentArea.classList.add('hidden');
                if (loader) loader.classList.remove('hidden');

                try {
                    const response = await fetch(`/api/v1/institute/leads/${id}`);
                    const result = await response.json();
                    loader.classList.add('hidden');

                    if (result.data) {
                        const lead = result.data;
                        selectedLeadData = lead;
                        contentArea.classList.remove('hidden');
                        document.getElementById('detail-name').textContent = lead.full_name;
                        document.getElementById('detail-course').textContent = lead.course_selection || 'General Inquiry';
                        document.getElementById('detail-email').textContent = lead.email || 'N/A';
                        document.getElementById('detail-phone').textContent = lead.phone || 'N/A';
                        document.getElementById('detail-address').textContent = lead.address || 'N/A';
                        document.getElementById('detail-source').textContent = lead.reference || 'N/A';
                        document.getElementById('detail-referrer').textContent = lead.referer || 'N/A';
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
                    <div class="relative pl-10 pb-5">
                        <div class="absolute left-0 top-0.5 h-[26px] w-[26px] rounded-full bg-white border-2 ${index === 0 ? 'border-orange-500' : 'border-sky-200'} flex items-center justify-center z-10">
                            <div class="h-1 w-1 rounded-full ${index === 0 ? 'bg-orange-500' : 'bg-sky-200'}"></div>
                        </div>
                        <div class="flex items-center justify-between mb-1">
                            <h4 class="text-xs font-bold text-gray-800">${note.title}</h4>
                            <span class="text-[10px] font-bold text-gray-300">${new Date(note.created_at).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</span>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                            <p class="text-[11px] font-medium text-gray-500 leading-relaxed">${note.note}</p>
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
                document.getElementById('lead-error').classList.add('hidden');
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
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                        body: JSON.stringify(data)
                    });

                    if (response.ok) {
                        closeLeadModal();
                        fetchLeads();
                    } else {
                        const errorData = await response.json();
                        const errDiv = document.getElementById('lead-error');
                        errDiv.textContent = errorData.message || 'Error saving lead';
                        errDiv.classList.remove('hidden');
                    }
                } catch (error) { console.error(error); } finally {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = originalText;
                }
            }

            function editLead() {
                const lead = selectedLeadData || currentLeads.find(l => l.id == selectedLeadId);
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
                form.elements['referer'].value = lead.referer || '';
            }

            function openConfirmModal(title, message, itemName, onConfirm) {
                const modal = document.getElementById('confirm-modal');
                const content = document.getElementById('confirm-modal-content');
                document.getElementById('confirm-title').textContent = title;
                document.getElementById('confirm-item-name').textContent = itemName;
                
                const proceedBtn = document.getElementById('confirm-proceed-btn');
                const newBtn = proceedBtn.cloneNode(true);
                proceedBtn.parentNode.replaceChild(newBtn, proceedBtn);
                
                newBtn.onclick = () => {
                    onConfirm();
                    closeConfirmModal();
                };

                modal.classList.remove('hidden');
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            }

            function closeConfirmModal() {
                const modal = document.getElementById('confirm-modal');
                const content = document.getElementById('confirm-modal-content');
                content.classList.add('scale-95', 'opacity-0');
                content.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => modal.classList.add('hidden'), 300);
            }

            async function deleteLead() {
                if (!selectedLeadId) return;
                const lead = selectedLeadData || currentLeads.find(l => l.id == selectedLeadId);
                const leadName = lead ? lead.full_name : 'this lead';

                openConfirmModal(
                    'Delete Lead?',
                    '',
                    leadName,
                    async () => {
                        try {
                            const response = await fetch(`/api/v1/institute/leads/${selectedLeadId}`, {
                                method: 'DELETE',
                                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
                            });
                            if (response.ok) {
                                selectedLeadId = null;
                                selectedLeadData = null;
                                document.getElementById('detail-empty').classList.remove('hidden');
                                document.getElementById('detail-content').classList.add('hidden');
                                fetchLeads();
                            }
                        } catch (error) { console.error(error); }
                    }
                );
            }

            function openNoteModal() {
                const modal = document.getElementById('note-modal');
                const content = document.getElementById('note-modal-content');
                document.getElementById('note-form').reset();
                document.getElementById('note-error').classList.add('hidden');
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
                if (!selectedLeadId) return;
                const form = event.target;
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());

                try {
                    const response = await fetch(`/api/v1/institute/leads/${selectedLeadId}/notes`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                        body: JSON.stringify(data)
                    });
                    if (response.ok) {
                        closeNoteModal();
                        selectLead(selectedLeadId);
                    }
                } catch (error) { console.error(error); }
            }

            document.getElementById('lead-search')?.addEventListener('keyup', e => {
                if (e.key === 'Enter') fetchLeads();
            });

            fetchLeads();
        </script>
    @endpush

    <style>
        html,
        body {
            height: 100%;
        }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            overflow-x: hidden;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>
@endsection