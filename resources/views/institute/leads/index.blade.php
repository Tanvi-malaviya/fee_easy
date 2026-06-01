@extends('layouts.institute')

@section('content')
    <div class="max-w-[1600px] mx-auto w-full">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-3">
            <div class="flex items-center gap-4 md:gap-12">
                <div>
                    <h1 class="text-xl font-semibold text-slate-800 tracking-tight text-left">Leads Management</h1>
                    <p class="text-xs text-slate-400 mt-0.5 font-medium text-left">Track, nurture, and convert your student inquiries.</p>
                </div>
            </div>

            @if(Auth::guard('institute')->user()->hasActiveSubscription())
            <div class="flex items-center gap-4">
                <button onclick="openLeadModal()"
                    class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-[#ea580c] transition-all flex items-center gap-2">

                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>

                    Add Lead    
                </button>
            </div>
            @else
            <div class="flex items-center gap-4">
                <button onclick="handleExpiredSubscription(event)"
                    class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-[#ea580c] transition-all flex items-center gap-2">

                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>

                    Add Lead    
                </button>
            </div>
            @endif
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 lg:h-[calc(100vh-13.5rem)] lg:min-h-[450px]">

            <!-- Sidebar -->
            <div id="leads-sidebar" class="col-span-1 lg:col-span-3 lg:h-full">
                <div class="h-full flex flex-col bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

                            <!-- Search Area -->
                            <div class="p-4 border-b border-slate-50 shrink-0 bg-white">
                                <div class="relative w-full">
                                    <!-- Search Icon (Left) -->
                                    <svg class="w-4 h-4 absolute left-3.5 top-[13px] text-slate-400 pointer-events-none" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    
                                    <!-- Input Field -->
                                    <input type="text" id="lead-search" placeholder="Search leads..."
                                        class="w-full pl-10 pr-12 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-semibold focus:bg-white focus:border-[#ff6c00] transition-all outline-none text-slate-600 placeholder-slate-400">

                                    <!-- Search Button (Right - Embedded) -->
                                    <button onclick="fetchLeads()"
                                        class="absolute right-1.5 top-1.5 bottom-1.5 px-3 bg-primary text-white rounded-xl hover:opacity-90 active:scale-95 transition-all flex items-center justify-center shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- List Container -->
                            <div id="lead-list-container"
                                class="flex-1 overflow-y-auto divide-y divide-slate-50 custom-scrollbar">

                                <!-- Loader -->
                                <div class="p-12 text-center loader-container">
                                    <div
                                        class="h-8 w-8 border-[3px] border-slate-100 border-t-primary rounded-full animate-spin mx-auto mb-3">
                                    </div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        Loading Leads...
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>

                <!-- Details Area (Separate floating cards design restored as requested) -->
                <div id="detail-panel" class="col-span-1 lg:col-span-9 rounded-xl flex flex-col relative lg:h-full lg:overflow-y-auto space-y-4 pb-4 custom-scrollbar">

                    <!-- Empty State (Styled as a gorgeous floating card) -->
                    <div id="detail-empty"
                        class="flex-1 flex flex-col items-center justify-center p-12 text-center bg-white rounded-2xl border border-slate-100 shadow-sm lg:min-h-0 min-h-[500px]">

                        <div
                            class="h-20 w-20 bg-slate-50 rounded-2xl flex items-center justify-center mb-6 text-slate-300 border border-slate-100">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                            </svg>
                        </div>

                        <h3 class="text-base font-bold text-slate-800 mb-2 tracking-tight">
                            Select a Lead
                        </h3>

                        <p class="text-xs text-slate-400 max-w-[240px] leading-relaxed font-semibold">
                            Choose a lead from the sidebar to view their full profile,
                            interaction history, and analytics.
                        </p>
                    </div>

                    <!-- Detail Loader (Styled as a floating card) -->
                    <div id="lead-detail-loader"
                        class="hidden h-full bg-white rounded-2xl border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center p-12 lg:min-h-0 min-h-[500px]">
                        <div
                            class="h-12 w-12 border-4 border-slate-50 border-t-primary rounded-full animate-spin mb-4">
                        </div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                            Fetching Details...
                        </p>
                    </div>

                    <!-- Content Area -->
                    <div id="detail-content" class="hidden flex flex-col gap-2">

                        <!-- Header Card (Restored as Floating Card) -->
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <button onclick="backToLeadsList()" class="lg:hidden h-8 w-8 flex items-center justify-center text-slate-500 hover:text-slate-700 bg-slate-50 hover:bg-slate-100 rounded-lg transition-colors shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                                    </button>
                                    <div id="lead-avatar"
                                        class="h-14 w-14 bg-primary rounded-2xl flex items-center justify-center text-lg font-bold text-white shadow-lg shadow-orange-100 shrink-0">
                                        JM
                                    </div>
                                    <div>
                                        <h2 id="detail-name" class="text-lg font-black text-slate-800 tracking-tight leading-none mb-1.5 text-left">Julianne Moore</h2>
                                        <div class="flex items-center gap-2 text-slate-400">
                                            <svg class="w-3.5 h-3.5 text-primary/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                            </svg>
                                            <span id="detail-course" class="text-xs font-semibold text-slate-500">Advanced UX Research Course</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
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

                       <!-- Info Cards Grid (Contact Info Card & Reference Card side-by-side) -->
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                            <!-- Contact Info Card -->
                            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sm:p-5 space-y-4">
                                <div class="flex items-center gap-2.5 mb-1 pb-2 border-b border-slate-50">
                                    <div class="h-8 w-8 bg-primary/10 text-primary rounded-xl flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Contact Information</h3>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex flex-wrap gap-x-12 gap-y-4 text-left">
                                        <div class="min-w-[180px] max-w-full">
                                            <label
                                                class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Email Address</label>
                                            <p id="detail-email" class="text-xs font-bold text-slate-700 break-all">
                                                julianne.m@example.com</p>
                                        </div>
                                        <div class="min-w-[140px]">
                                            <label
                                                class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Phone Number</label>
                                            <p id="detail-phone" class="text-xs font-bold text-slate-700">+1 (555) 098-7654</p>
                                        </div>
                                    </div>
                                    <div class="text-left">
                                        <label
                                            class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Address</label>
                                        <p id="detail-address" class="text-xs font-bold text-slate-700 leading-relaxed">1248
                                            Oakwood Ave, Suite 400, San Francisco, CA 94105</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Reference Card -->
                            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sm:p-5 space-y-4">
                                <div class="flex items-center gap-2.5 mb-1 pb-2 border-b border-slate-50">
                                    <div class="h-8 w-8 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Reference & Inquiry</h3>
                                </div>
                                <div class="space-y-4 text-left">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label
                                                class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Reference Name</label>
                                            <p id="detail-source" class="text-xs font-bold text-slate-700">Alumni Referral</p>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Inquiry Date</label>
                                            <p id="detail-date" class="text-xs font-bold text-slate-700">Oct 24, 2023</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Interaction Notes -->
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sm:p-5">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-50 mb-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="h-8 w-8 bg-primary/10 text-primary rounded-xl flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Interaction Timeline</h3>
                                </div>
                                @if(Auth::guard('institute')->user()->hasActiveSubscription())
                                <button onclick="openNoteModal()"
                                    class="text-xs font-extrabold text-primary hover:text-primary/95 transition-all flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Add Note
                                </button>
                                @else
                                <button onclick="handleExpiredSubscription(event)"
                                    class="text-xs font-extrabold text-primary hover:text-primary/95 transition-all flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Add Note
                                </button>
                                @endif
                            </div>

                            <div id="notes-timeline"
                                class="space-y-0 relative before:absolute before:left-[7px] before:top-2 before:bottom-2 before:w-[1.5px] before:bg-slate-100">
                                <!-- Notes injected here -->
                            </div>
                        </div>

                        <!-- Spacing at the bottom of detail-content to fix flexbox scroll padding bug! -->
                        <div class="h-8 shrink-0"></div>

                    </div>
                </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        @include('institute.leads.modals')

        @push('scripts')
            <script>
                if ('scrollRestoration' in history) {
                    history.scrollRestoration = 'manual';
                }
                window.scrollTo(0, 0);
                document.addEventListener('DOMContentLoaded', () => {
                    window.scrollTo(0, 0);
                });
                window.addEventListener('load', () => {
                    setTimeout(() => {
                        window.scrollTo(0, 0);
                    }, 50);
                });

                const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                let currentLeads = [];
                let selectedLeadId = null;
                let selectedLeadData = null;
                let currentStatusFilter = 'All';

                function updateMobileView() {
                    const sidebar = document.getElementById('leads-sidebar');
                    const detailPanel = document.getElementById('detail-panel');
                    if (!sidebar || !detailPanel) return;
                    if (window.innerWidth < 1024) {
                        if (selectedLeadId) {
                            sidebar.classList.add('hidden');
                            detailPanel.classList.remove('hidden');
                        } else {
                            sidebar.classList.remove('hidden');
                            detailPanel.classList.add('hidden');
                        }
                    } else {
                        sidebar.classList.remove('hidden');
                        detailPanel.classList.remove('hidden');
                    }
                }

                function backToLeadsList() {
                    selectedLeadId = null;
                    selectedLeadData = null;
                    renderLeadList();
                    updateMobileView();
                }

                async function fetchLeads(selectId = null) {
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

                        if (selectId) {
                            selectLead(selectId);
                        } else if (currentLeads.length > 0 && !selectedLeadId) {
                            if (window.innerWidth >= 1024) {
                                selectLead(currentLeads[0].id);
                            } else {
                                renderLeadList();
                                updateMobileView();
                            }
                        } else {
                            renderLeadList();
                            updateMobileView();
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
                    const searchInput = document.getElementById('lead-search');
                    const searchQuery = searchInput ? searchInput.value.toLowerCase().trim() : '';

                    if (!container) return;

                    let filteredLeads = currentLeads;
                    if (searchQuery) {
                        filteredLeads = currentLeads.filter(lead => 
                            (lead.full_name && lead.full_name.toLowerCase().includes(searchQuery)) ||
                            (lead.phone && lead.phone.includes(searchQuery)) ||
                            (lead.email && lead.email.toLowerCase().includes(searchQuery)) ||
                            (lead.course_selection && lead.course_selection.toLowerCase().includes(searchQuery)) ||
                            (lead.reference && lead.reference.toLowerCase().includes(searchQuery))
                        );
                    }

                    if (filteredLeads.length === 0) {
                        container.innerHTML = `<div class="p-12 text-center text-xs font-semibold text-slate-400">No leads found</div>`;
                        return;
                    }

                    container.innerHTML = `<div class="p-3 space-y-1">` + filteredLeads.map(lead => {
                        const isActive = String(selectedLeadId) == String(lead.id);
                        const initials = lead.full_name ? lead.full_name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2) : 'L';
                        return `
                            <div onclick="selectLead('${lead.id}')" 
                                class="flex items-center gap-3 p-3 cursor-pointer rounded-xl transition-all select-none ${isActive ? 'bg-primary/10 text-primary font-bold' : 'hover:bg-slate-50 text-slate-700'}">
                                <div class="h-9 w-9 rounded-xl flex items-center justify-center font-bold text-xs shrink-0 ${isActive ? 'bg-primary text-white shadow-md' : 'bg-slate-100 text-slate-500'}">
                                    ${initials}
                                </div>
                                <div class="flex-1 min-w-0 text-left">
                                    <div class="flex items-center justify-between gap-1 mb-0.5">
                                        <h4 class="text-xs font-extrabold truncate ${isActive ? 'text-primary' : 'text-slate-800'}">${lead.full_name}</h4>
                                        <span class="text-[9px] font-bold text-slate-400 shrink-0">${getTimeAgo(lead.created_at)}</span>
                                    </div>
                                    <p class="text-[10px] font-semibold ${isActive ? 'text-primary/75' : 'text-slate-500'} truncate">${lead.course_selection || 'General Inquiry'}</p>
                                </div>
                            </div>
                        `;
                    }).join('') + `</div>`;
                }

                async function selectLead(id) {
                    selectedLeadId = id;
                    renderLeadList();
                    updateMobileView();

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
                            document.getElementById('detail-date').textContent = lead.created_at ? new Date(lead.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A';
                            document.getElementById('lead-avatar').textContent = lead.full_name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
                            renderTimeline(lead.notes);
                        }
                    } catch (error) { console.error(error); }
                }

                function renderTimeline(notes) {
                    const timeline = document.getElementById('notes-timeline');
                    if (!notes || notes.length === 0) {
                        timeline.innerHTML = '<p class="text-xs text-slate-400 font-bold italic py-4 pl-6">No records found.</p>';
                        return;
                    }
                    timeline.innerHTML = notes.map((note, index) => `
                        <div class="relative pl-8 pb-5">
                            <div class="absolute left-0 top-0.5 h-[16px] w-[16px] rounded-full bg-white border-2 ${index === 0 ? 'border-primary' : 'border-slate-200'} flex items-center justify-center z-10 shadow-sm">
                                <div class="h-1.5 w-1.5 rounded-full ${index === 0 ? 'bg-primary' : 'bg-slate-200'}"></div>
                            </div>
                            <div class="flex items-center justify-between mb-1.5">
                                <h4 class="text-xs font-extrabold text-slate-800">${note.title}</h4>
                                <span class="text-[9px] font-black text-slate-400">${new Date(note.created_at).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</span>
                            </div>
                            <div class="bg-slate-50/50 rounded-2xl p-3 border border-slate-100 text-left">
                                <p class="text-[11px] font-semibold text-slate-500 leading-relaxed">${note.note}</p>
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
                    const originalContent = saveBtn.innerHTML;
                    const errorDiv = document.getElementById('lead-error');

                    saveBtn.disabled = true;
                    saveBtn.innerHTML = '<div class="h-4 w-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>';
                    if (errorDiv) errorDiv.classList.add('hidden');

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

                        const result = await response.json().catch(() => ({ message: 'Server error. Please try again.' }));

                        if (response.ok) {
                            closeLeadModal();
                            const newId = leadId || (result.data ? result.data.id : null);
                            await fetchLeads(newId);
                        } else {
                            if (errorDiv) {
                                errorDiv.textContent = result.message || (result.errors ? Object.values(result.errors)[0][0] : 'Error saving lead');
                                errorDiv.classList.remove('hidden');
                            }
                        }
                    } catch (error) { 
                        console.error('Save Error:', error);
                        if (errorDiv) {
                            errorDiv.textContent = 'Connection failed. Please check your internet.';
                            errorDiv.classList.remove('hidden');
                        }
                    } finally {
                        saveBtn.disabled = false;
                        saveBtn.innerHTML = originalContent;
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
                                    await fetchLeads();
                                    updateMobileView();
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

                window.addEventListener('resize', updateMobileView);
                fetchLeads();
            </script>
        @endpush

        <style>
            html,
            body {
                height: 100%;
            }

            body {
                font-family: 'Outfit', sans-serif;
                overflow-x: hidden;
            }

            @media (min-width: 1024px) {
                body {
                    overflow-y: hidden !important;
                }
                main {
                    padding-bottom: 6px !important;
                }
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