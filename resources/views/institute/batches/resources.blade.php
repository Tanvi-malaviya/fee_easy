@extends('layouts.institute')
@section('content')
    <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

    <div class="max-w-7xl mx-auto pt-6 pb-24 px-4 sm:px-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">
            <a href="{{ route('institute.batches.index') }}" class="hover:text-[#ff6600] transition-colors">Batches</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
            <a href="{{ route('institute.batches.show', $id) }}" class="hover:text-[#ff6600] transition-colors">Batch Details</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
            <span class="text-slate-600">Resources</span>
        </nav>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Batch Resources</h1>
                <p class="text-xs font-semibold text-slate-400 mt-1">Manage and distribute educational files, videos, and visual assets.</p>
            </div>
            
            <button onclick="openUploadModal()" class="px-5 py-3 bg-[#a3360a] hover:bg-[#852b08] text-white text-xs font-bold rounded-xl shadow-md shadow-orange-700/10 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                Upload Resource
            </button>
        </div>

        <!-- Filter Tabs -->
        <div class="flex flex-wrap items-center gap-2 mb-6">
            <button onclick="filterResources('all')" class="tab-btn active px-4 py-2 bg-[#a3360a] text-white text-xs font-bold rounded-xl border border-transparent shadow-sm transition-all">All Resources</button>
            <button onclick="filterResources('document')" class="tab-btn px-4 py-2 bg-white text-slate-600 hover:bg-slate-50 text-xs font-bold rounded-xl border border-slate-100 shadow-sm transition-all">Documents</button>
            <button onclick="filterResources('video')" class="tab-btn px-4 py-2 bg-white text-slate-600 hover:bg-slate-50 text-xs font-bold rounded-xl border border-slate-100 shadow-sm transition-all">Videos</button>
            <button onclick="filterResources('image')" class="tab-btn px-4 py-2 bg-white text-slate-600 hover:bg-slate-50 text-xs font-bold rounded-xl border border-slate-100 shadow-sm transition-all">Images</button>
        </div>

        <!-- Resources Grid -->
        <div id="resources-grid" class="flex flex-wrap gap-3 mb-8">
            <!-- Quick Add Card -->
            <div onclick="openUploadModal()" class="bg-slate-50/50 border-2 border-dashed border-slate-200 rounded-xl p-4 flex flex-col items-center justify-center group hover:bg-slate-50 hover:border-[#a3360a]/30 transition-all cursor-pointer max-w-[210px] w-full min-h-[180px]">
                <div class="h-10 w-10 bg-orange-100/50 rounded-full flex items-center justify-center text-[#a3360a] mb-2 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                </div>
                <p class="text-xs font-bold text-slate-800">Quick Add</p>
                <p class="text-[10px] text-slate-400 mt-1">Drag & drop files here</p>
            </div>

            <!-- Grid Items rendered dynamically -->
        </div>
    </div>

    <!-- UPLOAD MODAL -->
    <div id="upload-modal" class="fixed inset-0 z-[150] bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-[600px] rounded-2xl shadow-2xl overflow-hidden animate-in zoom-in duration-200 flex flex-col">
            <!-- Modal Header -->
            <div class="px-6 py-3.5 flex items-start justify-between border-b border-slate-50">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Upload New Content</h2>
                    <p class="text-[10px] font-semibold text-slate-400">Distribute learning materials across batch courses</p>
                </div>
                <button onclick="closeUploadModal()" class="text-slate-400 hover:text-slate-600 transition-colors p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
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
                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Description</label>
                    <textarea id="res-description" rows="2" placeholder="Provide context and learning objectives..." 
                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-700 placeholder-slate-300 outline-none focus:ring-2 focus:ring-[#a3360a]/20 focus:border-[#a3360a] transition-all resize-none"></textarea>
                </div>

                <!-- Attachments -->
                <div>
                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Attachments</label>
                    
                    <!-- Drag & Drop Zone -->
                    <div id="drop-zone" class="border border-dashed border-slate-200 rounded-xl p-5 flex flex-col items-center justify-center bg-slate-50/30 group hover:border-[#a3360a]/30 hover:bg-slate-50/50 transition-all cursor-pointer relative">
                        <input type="file" id="res-file" class="absolute inset-0 opacity-0 cursor-pointer" onchange="handleFileSelect(event)">
                        
                        <div class="h-12 w-12 bg-orange-100/50 rounded-full flex items-center justify-center text-[#a3360a] mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                        </div>
                        
                        <p class="text-xs font-black text-slate-800">Drag & Drop files here</p>
                        <p class="text-[10px] font-bold text-slate-400 mt-1 mb-4">MP4, PDF, or JPG/PNG (Max 50MB)</p>
                        
                        <span class="px-5 py-2 border border-slate-200 text-slate-600 hover:border-[#a3360a] hover:text-[#a3360a] text-xs font-bold rounded-xl transition-all bg-white shadow-sm flex items-center gap-2">
                            Browse Files
                        </span>
                    </div>

                    <!-- Selected File Status -->
                    <p id="selected-file-name" class="text-[10px] font-bold text-emerald-600 mt-2 hidden flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
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
                    <button type="button" onclick="closeUploadModal()" class="text-xs font-bold text-slate-500 hover:text-slate-700 transition-colors">Cancel</button>
                    <button type="submit" id="upload-submit-btn" class="px-6 py-3 bg-[#a3360a] hover:bg-[#852b08] text-white text-xs font-black rounded-xl transition-all shadow-md shadow-orange-700/10">
                        Initialize Batch Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const BATCH_ID = "{{ $id }}";
        const API_RESOURCES_URL = `/api/v1/institute/resources`;
        
        let resources = [];
        let currentFilter = 'all';
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

        function filterResources(type) {
            currentFilter = type;
            
            // Toggle active tabs
            const tabs = document.querySelectorAll('.tab-btn');
            tabs.forEach(tab => {
                tab.className = 'tab-btn px-4 py-2 bg-white text-slate-600 hover:bg-slate-50 text-xs font-bold rounded-xl border border-slate-100 shadow-sm transition-all';
            });
            event.target.className = 'tab-btn active px-4 py-2 bg-[#a3360a] text-white text-xs font-bold rounded-xl border border-transparent shadow-sm transition-all';

            renderResources();
        }

        function renderResources() {
            const container = document.getElementById('resources-grid');
            const filtered = currentFilter === 'all' ? resources : resources.filter(r => r.file_type === currentFilter);

            // Retain the Quick Add card
            container.innerHTML = `
                <div onclick="openUploadModal()" class="bg-slate-50/50 border-2 border-dashed border-slate-200 rounded-xl p-4 flex flex-col items-center justify-center group hover:bg-slate-50 hover:border-[#a3360a]/30 transition-all cursor-pointer max-w-[210px] w-full min-h-[180px]">
                    <div class="h-10 w-10 bg-orange-100/50 rounded-full flex items-center justify-center text-[#a3360a] mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    </div>
                    <p class="text-xs font-bold text-slate-800">Quick Add</p>
                    <p class="text-[10px] text-slate-400 mt-1">Drag & drop files here</p>
                </div>
            `;

            filtered.forEach(res => {
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
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l3.086-3.086a2.25 2.25 0 013.182 0l2.324 2.324m-14.73 2.25H21h-2.25" /></svg>
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

                            <h4 class="text-[12px] font-bold text-slate-900 mt-2 leading-tight truncate" title="${res.title}">${res.title}</h4>
                            <p class="text-[9px] font-medium text-slate-400 mt-0.5 truncate">Added ${dateStr} • ${res.file_size || 'N/A'}</p>
                        </div>

                        <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-50">
                            <a href="/storage/${res.file_path}" target="_blank" class="text-[10px] font-bold text-[#a3360a] hover:underline flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                Open
                            </a>
                            <button onclick="deleteResource(${res.id})" class="text-slate-400 hover:text-rose-600 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', itemHTML);
            });
        }

        function handleFileSelect(event) {
            const file = event.target.files[0];
            const statusLabel = document.getElementById('selected-file-name');
            
            if (file) {
                selectedFile = file;
                statusLabel.querySelector('span').innerText = `${file.name} (${(file.size/1024/1024).toFixed(2)} MB)`;
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

        async function deleteResource(id) {
            if (!confirm('Are you sure you want to delete this resource?')) return;

            try {
                const response = await fetch(`${API_RESOURCES_URL}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();
                if (result.status === 'success') {
                    showToast('Material deleted successfully');
                    fetchResources();
                } else {
                    showToast('Failed to delete resource', 'error');
                }
            } catch (error) {
                console.error(error);
                showToast('Failed to delete resource', 'error');
            }
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
