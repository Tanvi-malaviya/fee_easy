@extends('layouts.institute')
@section('content')
    <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

    <div class="max-w-7xl mx-auto pt-6 px-4 sm:px-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">
            <a href="{{ route('institute.batches.index') }}" class="hover:text-[#ff6600] transition-colors">Batches</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('institute.batches.show', $id) }}" class="hover:text-[#ff6600] transition-colors">Batch
                Details</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-slate-600">Resources</span>
        </nav>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-700 tracking-tight">Batch Resources</h1>
                <p class="text-xs font-semibold text-slate-400 mt-1">Manage and distribute educational files, videos, and
                    visual assets.</p>
            </div>

            <button onclick="openUploadModal()"
                class="px-5 py-3 bg-[#a3360a] hover:bg-[#852b08] text-white text-xs font-bold rounded-xl shadow-md shadow-orange-700/10 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Upload Resource
            </button>
        </div>

        <!-- Resources Grid -->
        <div id="resources-grid" class="flex flex-wrap gap-3 mb-8">
            <!-- Quick Add Card -->
            <div onclick="openUploadModal()"
                class="bg-slate-50/50 border-2 border-dashed border-slate-200 rounded-xl p-4 flex flex-col items-center justify-center group hover:bg-slate-50 hover:border-[#a3360a]/30 transition-all cursor-pointer max-w-[210px] w-full min-h-[180px]">
                <div
                    class="h-10 w-10 bg-orange-100/50 rounded-full flex items-center justify-center text-[#a3360a] mb-2 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <p class="text-xs font-bold text-slate-800">Quick Add</p>
                <p class="text-[10px] text-slate-400 mt-1">Drag & drop files here</p>
            </div>

            <!-- Grid Items rendered dynamically -->
        </div>

        <!-- Pagination Controls -->
        <div id="pagination-controls" class="flex items-center justify-center gap-2 mt-2 mb-8"></div>
    </div>

    <!-- UPLOAD MODAL -->
    <div id="upload-modal"
        class="fixed inset-0 z-[150] bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center p-4">
        <div
            class="bg-white w-full max-w-[600px] rounded-2xl shadow-2xl overflow-hidden animate-in zoom-in duration-200 flex flex-col">
            <!-- Modal Header -->
            <div class="px-6 py-3.5 flex items-start justify-between border-b border-slate-50">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Upload New Content</h2>
                    <p class="text-[10px] font-semibold text-slate-400">Distribute learning materials across batch courses
                    </p>
                </div>
                <button onclick="closeUploadModal()" class="text-slate-400 hover:text-slate-600 transition-colors p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Form -->
            <form onsubmit="handleUpload(event)" class="px-6 py-4 space-y-4">
                <!-- Subject -->
                <div>
                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Subject</label>
                    <input type="text" id="res-title" required placeholder="e.g. Advanced Calculus - Week 4 Module"
                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-700 placeholder-slate-300 outline-none focus:ring-2 focus:ring-[#a3360a]/20 focus:border-[#a3360a] transition-all">
                </div>

                <!-- Description -->
                <div>
                    <label
                        class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Description</label>
                    <textarea id="res-description" rows="2" placeholder="Provide context and learning objectives..."
                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-700 placeholder-slate-300 outline-none focus:ring-2 focus:ring-[#a3360a]/20 focus:border-[#a3360a] transition-all resize-none"></textarea>
                </div>

                <!-- Attachments -->
                <div>
                    <label
                        class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Attachments</label>

                    <!-- Drag & Drop Zone -->
                    <div id="drop-zone"
                        class="border border-dashed border-slate-200 rounded-xl p-5 flex flex-col items-center justify-center bg-slate-50/30 group hover:border-[#a3360a]/30 hover:bg-slate-50/50 transition-all cursor-pointer relative">
                        <input type="file" id="res-file" class="absolute inset-0 opacity-0 cursor-pointer"
                            onchange="handleFileSelect(event)">

                        <div
                            class="h-6 w-6 bg-orange-100/50 rounded-full flex items-center justify-center text-[#a3360a] mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                        </div>

                        <p class="text-xs font-black text-slate-800">Drag & Drop files here</p>
                        <p class="text-[10px] font-bold text-slate-400 mt-1 mb-4">MP4, PDF, or JPG/PNG (Max 50MB)</p>

                        <span
                            class="px-5 py-2 border border-slate-200 text-slate-600 hover:border-[#a3360a] hover:text-[#a3360a] text-xs font-bold rounded-xl transition-all bg-white shadow-sm flex items-center gap-2">
                            Browse Files
                        </span>
                    </div>

                    <!-- Selected File Status -->
                    <p id="selected-file-name"
                        class="text-[10px] font-bold text-emerald-600 mt-2 hidden flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>No file selected</span>
                    </p>

                    <!-- File Type Indicators (Bottom left of attachments in image) -->
                    <div class="flex items-center gap-4 mt-4">
                        <div class="flex items-center gap-1.5 text-slate-400 text-[10px] font-bold">
                            <span class="w-2.5 h-2.5 rounded bg-orange-600"></span> Images
                        </div>
                        <div class="flex items-center gap-1.5 text-slate-400 text-[10px] font-bold">
                            <span class="w-2.5 h-2.5 rounded bg-blue-600"></span> Videos
                        </div>
                        <div class="flex items-center gap-1.5 text-slate-400 text-[10px] font-bold">
                            <span class="w-2.5 h-2.5 rounded bg-rose-600"></span> Documents
                        </div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="pt-4 flex items-center justify-end gap-4 border-t border-slate-100">
                    <button type="button" onclick="closeUploadModal()"
                        class="text-xs font-bold text-slate-500 hover:text-slate-700 transition-colors">Cancel</button>
                    <button type="submit" id="upload-submit-btn"
                        class="px-6 py-3 bg-[#a3360a] hover:bg-[#852b08] text-white text-xs font-black rounded-xl transition-all shadow-md shadow-orange-700/10">
                        Initialize Batch Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- DELETE CONFIRMATION MODAL -->
    <div id="delete-modal" class="fixed inset-0 z-[150] hidden">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeDeleteModal()">
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[450px]">
            <div
                class="bg-white rounded-[1.5rem] shadow-2xl border-t-4 border-rose-500 overflow-hidden animate-in zoom-in-95 fade-in duration-300">
                <div class="p-8">
                    <div class="flex gap-4">
                        <div
                            class="h-12 w-12 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-slate-600 mb-2">Delete Resource?</h3>
                            <p class="text-[12px] text-slate-500 font-medium leading-relaxed mb-6">Are you sure you want to
                                permanently remove this resource? This action cannot be undone.</p>
                            <div class="flex items-center gap-3">
                                <button type="button" onclick="closeDeleteModal()"
                                    class="flex-1 h-12 border-2 border-emerald-500 text-emerald-500 rounded-xl font-extrabold text-[12px] hover:bg-emerald-50 transition-all">Cancel</button>
                                <button type="button" id="confirm-delete-btn"
                                    class="flex-[1.5] h-12 bg-rose-500 text-white rounded-xl font-extrabold text-[12px] shadow-lg shadow-rose-500/20 hover:bg-rose-600 transition-all">Yes,
                                    Delete Resource</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-8 py-3 bg-slate-50 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                    </svg>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Authenticated as
                        Admin</span>
                </div>
            </div>
        </div>
    </div>

    <!-- VIEW MODAL -->
    <div id="view-modal"
        class="fixed inset-0 z-[150] bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center p-4">
        <div
            class="bg-white w-full max-w-[850px] rounded-2xl shadow-2xl overflow-hidden animate-in zoom-in duration-200 flex flex-col max-h-[90vh]">
            <!-- Modal Header -->
            <div class="px-6 py-4 flex items-center justify-between border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Resource Details</span>
                </div>
                <button onclick="closeViewModal()" class="text-slate-400 hover:text-slate-600 transition-colors p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content (Scrollable) -->
            <div class="flex-1 overflow-y-auto p-6 flex flex-col md:flex-row gap-6">
                <!-- Left Side: Preview & Description -->
                <div class="flex-1 space-y-4">
                    <div>
                        <h2 id="view-title" class="text-xl font-black text-slate-900 leading-tight">Resource Title</h2>
                        <div class="flex flex-wrap items-center gap-4 text-[10px] font-bold text-slate-400 mt-2">
                            <span id="view-meta-type" class="flex items-center gap-1"><span
                                    class="w-2 h-2 rounded-full bg-orange-500"></span> MP4 Video</span>
                            <span id="view-meta-size" class="flex items-center gap-1"><span
                                    class="w-2 h-2 rounded-full bg-blue-500"></span> 124MB</span>
                            <span id="view-meta-date" class="flex items-center gap-1"><span
                                    class="w-2 h-2 rounded-full bg-emerald-500"></span> Uploaded Oct 12, 2024</span>
                        </div>
                    </div>

                    <!-- Media Preview Area -->
                    <div id="view-preview-container"
                        class="rounded-xl overflow-hidden bg-slate-900 w-full aspect-video flex items-center justify-center text-slate-500 relative min-h-[250px]">
                        <!-- Preview injected via JS -->
                    </div>

                    <!-- Description -->
                    <div>
                        <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Description</h4>
                        <p id="view-description" class="text-xs font-medium text-slate-600 leading-relaxed">No description
                            provided.</p>
                    </div>
                </div>

                <!-- Right Side: Action & Info -->
                <div class="w-full md:w-[240px] space-y-4">
                    <!-- Download Button -->
                    <a id="view-download-btn" href="#" download
                        class="w-full py-3 bg-[#a3360a] hover:bg-[#852b08] text-white text-xs font-bold rounded-xl shadow-md flex items-center justify-center gap-2 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download Resource
                    </a>

                    <!-- Resource Author -->
                    <div class="bg-slate-50/50 rounded-xl p-3 border border-slate-100 flex items-center gap-3">
                        <div
                            class="h-9 w-9 bg-orange-100/50 text-[#a3360a] rounded-xl flex items-center justify-center font-bold text-xs">
                            A
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-900">Institute Admin</p>
                            <p class="text-[8px] font-semibold text-slate-400">Content Publisher</p>
                        </div>
                    </div>

                    <!-- File Information -->
                    <div class="bg-slate-50/50 rounded-xl p-3 border border-slate-100">
                        <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2.5">File Information
                        </h4>
                        <div class="space-y-2 text-[10px] font-bold text-slate-600">
                            <div class="flex justify-between">
                                <span class="text-slate-400">Status:</span>
                                <span class="text-emerald-600">Quality Checked</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Language:</span>
                                <span>English</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const BATCH_ID = "{{ $id }}";
        const API_RESOURCES_URL = `/api/v1/institute/resources`;

        let resources = [];
        let currentPage = 1;
        const itemsPerPage = 8;
        let selectedFile = null;

        document.addEventListener('DOMContentLoaded', () => {
            fetchResources();
        });

        async function fetchResources() {
            try {
                const response = await fetch(`${API_RESOURCES_URL}?batch_id=${BATCH_ID}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const result = await response.json();
                if (result.status === 'success') {
                    resources = result.data;
                    renderResources();
                } else {
                    showToast('Failed to load resources', 'error');
                }
            } catch (error) {
                console.error(error);
                showToast('Failed to load resources', 'error');
            }
        }

        function changePage(page) {
            currentPage = page;
            renderResources();
        }

        function renderResources() {
            const container = document.getElementById('resources-grid');
            const totalPages = Math.ceil(resources.length / itemsPerPage) || 1;

            if (currentPage > totalPages) {
                currentPage = totalPages;
            }

            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const paginated = resources.slice(startIndex, endIndex);

            container.innerHTML = '';

            paginated.forEach(res => {
                const typeLabel = res.file_type.toUpperCase();
                let typeColor = 'bg-slate-100 text-slate-600';
                let iconBlock = '';

                if (res.file_type === 'video') {
                    typeColor = 'bg-orange-600 text-white';
                    iconBlock = `
                            <div class="h-28 bg-slate-900 rounded-lg flex items-center justify-center text-slate-500 relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent opacity-80"></div>
                                <svg class="w-10 h-10 text-white/40 z-10" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                            </div>`;
                } else if (res.file_type === 'image') {
                    typeColor = 'bg-blue-600 text-white';
                    iconBlock = `
                            <div class="h-28 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 relative overflow-hidden">
                                <img src="${res.file_url}" class="w-full h-full object-cover">
                            </div>`;
                } else {
                    typeColor = 'bg-rose-600 text-white';
                    iconBlock = `
                            <div class="h-28 bg-slate-50 rounded-lg flex items-center justify-center text-slate-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                            </div>`;
                }

                // Format modified date nicely
                const dateStr = new Date(res.created_at).toLocaleDateString('en-IN', {
                    day: 'numeric', month: 'short'
                });

                const itemHTML = `
                        <div id="res-${res.id}" class="bg-white rounded-xl p-3 border border-slate-100 shadow-sm flex flex-col justify-between max-w-[210px] w-full min-h-[220px] relative animate-in fade-in duration-200">
                            <div>
                                <span class="absolute top-4 right-4 z-10 px-1.5 py-0.5 ${typeColor} rounded text-[8px] font-black tracking-widest">${typeLabel}</span>
                                ${iconBlock}

                                <h4 class="text-[12px] font-bold text-slate-700 mt-2 leading-tight truncate" title="${res.title}">${res.title}</h4>
                                <p class="text-[9px] font-medium text-slate-400 mt-0.5 truncate">Added ${dateStr} • ${res.file_size || 'N/A'}</p>
                            </div>

                            <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-50">
                                <button type="button" onclick="openViewModal(${res.id})" class="text-[10px] font-bold text-[#a3360a] hover:underline flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    View
                                </button>
                                <button onclick="deleteResource(${res.id})" class="text-slate-400 hover:text-rose-600 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </div>
                    `;
                container.insertAdjacentHTML('beforeend', itemHTML);
            });

            // Render Pagination Controls
            const paginationContainer = document.getElementById('pagination-controls');
            if (totalPages <= 1) {
                paginationContainer.innerHTML = '';
                return;
            }

            let paginationHTML = `
                    <button onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-slate-600 text-xs font-bold hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                `;

            for (let i = 1; i <= totalPages; i++) {
                paginationHTML += `
                        <button onclick="changePage(${i})" class="px-3 py-1.5 text-xs font-bold rounded-lg border transition-all ${currentPage === i ? 'bg-[#a3360a] border-transparent text-white shadow-sm shadow-orange-700/10' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'}">${i}</button>
                    `;
            }

            paginationHTML += `
                    <button onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''} class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-slate-600 text-xs font-bold hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </button>
                `;

            paginationContainer.innerHTML = paginationHTML;
        }

        function handleFileSelect(event) {
            const file = event.target.files[0];
            const statusLabel = document.getElementById('selected-file-name');

            if (file) {
                selectedFile = file;
                statusLabel.querySelector('span').innerText = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                statusLabel.classList.remove('hidden');
            } else {
                selectedFile = null;
                statusLabel.classList.add('hidden');
            }
        }

        function openUploadModal() {
            document.getElementById('upload-modal').classList.replace('hidden', 'flex');
        }

        function closeUploadModal() {
            document.getElementById('upload-modal').classList.replace('flex', 'hidden');
            // Reset file states
            selectedFile = null;
            document.getElementById('selected-file-name').classList.add('hidden');
        }

        async function handleUpload(e) {
            e.preventDefault();

            if (!selectedFile) {
                showToast('Please select a file to upload.', 'error');
                return;
            }

            const title = document.getElementById('res-title').value;
            const description = document.getElementById('res-description').value;
            const submitBtn = document.getElementById('upload-submit-btn');

            // Deduce file type
            let fileType = 'document';
            const mime = selectedFile.type;
            if (mime.startsWith('image/')) fileType = 'image';
            else if (mime.startsWith('video/')) fileType = 'video';

            const formData = new FormData();
            formData.append('batch_id', BATCH_ID);
            formData.append('title', title);
            formData.append('description', description);
            formData.append('file_type', fileType);
            formData.append('file', selectedFile);

            try {
                submitBtn.disabled = true;
                submitBtn.innerText = 'Uploading...';

                const response = await fetch(API_RESOURCES_URL, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    showToast('Material uploaded successfully');
                    closeUploadModal();
                    e.target.reset();
                    fetchResources();
                } else {
                    showToast(result.message || 'Upload failed', 'error');
                }
            } catch (error) {
                console.error(error);
                showToast('Upload failed', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerText = 'Initialize Batch Upload';
            }
        }

        let pendingDeleteId = null;

        function deleteResource(id) {
            pendingDeleteId = id;
            document.getElementById('delete-modal').classList.replace('hidden', 'flex');
        }

        function closeDeleteModal() {
            pendingDeleteId = null;
            document.getElementById('delete-modal').classList.replace('flex', 'hidden');
        }

        document.getElementById('confirm-delete-btn').addEventListener('click', async () => {
            if (!pendingDeleteId) return;

            const btn = document.getElementById('confirm-delete-btn');
            btn.disabled = true;
            btn.innerText = 'Deleting...';

            try {
                const response = await fetch(`${API_RESOURCES_URL}/${pendingDeleteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();
                if (result.status === 'success') {
                    showToast('Material deleted successfully');
                    closeDeleteModal();
                    fetchResources();
                } else {
                    showToast('Failed to delete resource', 'error');
                }
            } catch (error) {
                console.error(error);
                showToast('Failed to delete resource', 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Delete';
            }
        });

        function openViewModal(id) {
            const res = resources.find(r => r.id === id);
            if (!res) return;

            document.getElementById('view-title').innerText = res.title;
            document.getElementById('view-description').innerText = res.description || 'No description provided.';

            const dateStr = new Date(res.created_at).toLocaleDateString('en-IN', {
                day: 'numeric', month: 'short', year: 'numeric'
            });

            document.getElementById('view-meta-type').innerHTML = `<span class="w-2 h-2 rounded-full bg-orange-500"></span> ${res.file_type.toUpperCase()}`;
            document.getElementById('view-meta-size').innerHTML = `<span class="w-2 h-2 rounded-full bg-blue-500"></span> ${res.file_size || 'N/A'}`;
            document.getElementById('view-meta-date').innerHTML = `<span class="w-2 h-2 rounded-full bg-emerald-500"></span> Uploaded ${dateStr}`;

            // Use full URL from API
            const fileUrl = res.file_url;
            const fileExt = res.file_path.split('.').pop().toLowerCase();

            // Links
            document.getElementById('view-download-btn').href = fileUrl;
            document.getElementById('view-open-btn').href = fileUrl;

            // Preview logic
            const previewContainer = document.getElementById('view-preview-container');
            if (res.file_type === 'video') {
                previewContainer.innerHTML = `<video src="${fileUrl}" controls class="w-full max-h-[400px] bg-black rounded-lg"></video>`;
            } else if (res.file_type === 'image') {
                previewContainer.innerHTML = `<img src="${fileUrl}" class="w-full max-h-[400px] object-contain bg-slate-50 rounded-lg">`;
            } else if (fileExt === 'pdf') {
                previewContainer.innerHTML = `<iframe src="${fileUrl}" class="w-full h-[450px] rounded-lg border border-slate-100" frameborder="0"></iframe>`;
            } else {
                previewContainer.innerHTML = `
                        <div class="flex flex-col items-center justify-center p-12 text-slate-400 bg-slate-50 w-full rounded-xl">
                            <svg class="w-16 h-16 mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            <span class="text-xs font-bold text-slate-600 truncate max-w-[250px] mb-1">${res.title}</span>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">${fileExt} Document</span>
                            <p class="text-[10px] text-slate-400 mt-4 text-center">This file type cannot be previewed directly.<br>Please use the download button to view it.</p>
                        </div>`;
            }

            document.getElementById('view-modal').classList.replace('hidden', 'flex');
        }

        function closeViewModal() {
            // Stop video playing if open
            const video = document.querySelector('#view-preview-container video');
            if (video) video.pause();

            document.getElementById('view-modal').classList.replace('flex', 'hidden');
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `flex items-center gap-3 px-6 py-4 rounded-2xl shadow-2xl animate-in slide-in-from-right-10 duration-500 ${type === 'success' ? 'bg-slate-900 text-white' : 'bg-rose-600 text-white'}`;
            toast.innerHTML = `
                    <div class="h-6 w-6 rounded-full flex items-center justify-center ${type === 'success' ? 'bg-[#a3360a]' : 'bg-rose-400'}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="${type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}"/></svg>
                    </div>
                    <p class="text-sm font-bold">${message}</p>`;
            container.appendChild(toast);
            setTimeout(() => { toast.classList.add('animate-out', 'fade-out', 'slide-out-to-right-10'); setTimeout(() => toast.remove(), 500); }, 3000);
        }
    </script>
@endsection