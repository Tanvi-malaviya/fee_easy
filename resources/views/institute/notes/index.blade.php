@extends('layouts.institute')

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-4">
            <div>
                <h1 class="text-3xl font-medium text-slate-800 tracking-tight">Your Workspace</h1>
                
            </div>
            <div class="flex items-center gap-4">
                <button onclick="openNoteModal()"
                    class="px-6 py-2.5 bg-primary text-white rounded-xl text-sm font-bold shadow-lg shadow-orange-900/20 hover:translate-y-[-1px] active:scale-95 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    New Note
                </button>
            </div>
        </div>

        <!-- Filter Tabs -->
        <!-- <div id="category-tabs"
            class="flex items-center gap-8 border-b border-slate-100 mb-8 overflow-x-auto no-scrollbar pb-1">
            <button onclick="filterNotes('All')"
                class="category-tab px-1 py-4 text-sm font-bold text-[#A8440B] border-b-2 border-[#A8440B] whitespace-nowrap transition-all">All
                Notes</button>
            <button onclick="filterNotes('Work')"
                class="category-tab px-1 py-4 text-sm font-bold text-slate-400 border-b-2 border-transparent hover:text-slate-600 whitespace-nowrap transition-all">Work</button>
            <button onclick="filterNotes('Personal')"
                class="category-tab px-1 py-4 text-sm font-bold text-slate-400 border-b-2 border-transparent hover:text-slate-600 whitespace-nowrap transition-all">Personal</button>
            <button onclick="filterNotes('Ideas')"
                class="category-tab px-1 py-4 text-sm font-bold text-slate-400 border-b-2 border-transparent hover:text-slate-600 whitespace-nowrap transition-all">Ideas</button>
            <button onclick="filterNotes('Meeting Notes')"
                class="category-tab px-1 py-4 text-sm font-bold text-slate-400 border-b-2 border-transparent hover:text-slate-600 whitespace-nowrap transition-all">Meeting
                Notes</button>
            <button onclick="filterNotes('Family')"
                class="category-tab px-1 py-4 text-sm font-bold text-slate-400 border-b-2 border-transparent hover:text-slate-600 whitespace-nowrap transition-all">Family</button>
            <button onclick="filterNotes('Important')"
                class="category-tab px-1 py-4 text-sm font-bold text-slate-400 border-b-2 border-transparent hover:text-slate-600 whitespace-nowrap transition-all">Important</button>
        </div> -->

        <!-- Notes Grid -->
        <div id="notes-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Loader -->
            <div class="col-span-full flex flex-col items-center justify-center py-20">
                <div class="h-10 w-10 border-4 border-slate-100 border-t-primary rounded-full animate-spin mb-4"></div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Loading Workspace...</p>
            </div>
        </div>
    </div>

    <!-- Note Modal -->
    <div id="note-modal" class="fixed inset-0 z-[120] hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div onclick="closeNoteModal()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

            <div id="note-modal-content"
                class="relative w-full max-w-2xl scale-95 opacity-0 bg-white rounded-2xl shadow-2xl transition-all duration-300 overflow-visible border border-slate-100">
                <div
                    class="px-4 py-2 border-b border-slate-50 flex items-center justify-between bg-slate-50/30 rounded-t-2xl">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <h3 id="modal-title" class="text-base font-bold text-slate-800">Note Details</h3>
                    </div>
                    <button onclick="closeNoteModal()"
                        class="h-8 w-8 flex items-center justify-center rounded-full hover:bg-white text-slate-400 hover:text-slate-600 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="note-form" onsubmit="saveNote(event)" class="pl-4 pr-4 pb-4 space-y-2.5">
                    <input type="hidden" id="note-id" name="id">

                    <div>
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">Title</label>
                        <input type="text" name="title" required placeholder="Enter note title..."
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-primary transition-all outline-none">
                    </div>

                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <label
                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">Category</label>
                            <div class="relative group" id="custom-category-dropdown">
                                <input type="hidden" name="category_id" id="category-id-input" required>
                                <div onclick="toggleDropdown()" id="dropdown-selected"
                                    class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold focus-within:bg-white focus-within:border-primary focus-within:ring-4 focus-within:ring-primary/5 transition-all outline-none cursor-pointer flex items-center justify-between">
                                    <span id="selected-name" class="text-slate-400">Select Category</span>
                                </div>
                                <svg class="w-4 h-4 absolute left-3.5 top-3 text-slate-400 pointer-events-none group-focus-within:text-primary transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                <svg class="w-4 h-4 absolute right-3.5 top-3 text-slate-400 pointer-events-none transition-transform"
                                    id="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 9l-7 7-7-7" />
                                </svg>

                                <!-- Custom Options List -->
                                <div id="dropdown-options"
                                    class="hidden absolute top-full left-0 right-0 mt-2 bg-white border border-slate-100 rounded-xl shadow-2xl z-[150] max-h-40 overflow-y-auto py-2 scale-95 opacity-0 transition-all duration-200 origin-top">
                                    <!-- Options will be rendered here -->
                                </div>
                            </div>
                        </div>
                        <div class="flex items-end gap-2 pt-5">
                            <input type="file" id="note-image-input" name="image" class="hidden" accept="image/*"
                                onchange="previewImage(event)">
                            <button type="button" onclick="document.getElementById('note-image-input').click()"
                                class="px-3 py-2 bg-slate-50 text-slate-500 rounded-xl text-xs font-bold hover:bg-slate-100 transition-all border border-slate-100 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Add Image
                            </button>
                        </div>
                    </div>

                    <!-- Image Name Display -->
                    <div id="image-preview-container"
                        class="hidden items-center gap-2 px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-lg w-fit">
                        <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span id="image-name" class="text-[11px] font-bold text-slate-600 truncate max-w-[200px]"></span>
                        <button type="button" onclick="removeImage()"
                            class="p-1 hover:bg-slate-200 rounded-md text-rose-500 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">Content</label>
                        <div class="relative group">
                            <input type="hidden" name="content" id="note-content-input">
                            <div id="note-editor" contenteditable="true"
                                class="w-full px-4 py-3 min-h-[120px] bg-slate-50 border border-slate-100 rounded-2xl text-sm font-medium text-slate-700 focus:bg-white focus:border-primary transition-all outline-none leading-relaxed overflow-y-auto"
                                oninput="syncContent()"></div>

                            <!-- Editor Bar -->

                        </div>
                    </div>

                    <div id="note-error"
                        class="hidden px-4 py-1.5 bg-rose-50 border border-rose-100 rounded-xl text-[11px] font-bold text-rose-500 mb-1">
                    </div>

                    <div class="pt-2 flex items-center justify-between">
                        <span id="last-edited" class="text-[9px] font-medium text-slate-300 italic uppercase"></span>
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="closeNoteModal()"
                                class="px-4 py-2 text-xs font-bold text-slate-400 hover:text-slate-600 transition-all">Cancel</button>
                            <button type="submit" id="save-note-btn"
                                class="px-6 py-2 bg-primary text-white rounded-xl text-xs font-bold shadow-lg shadow-orange-900/20 hover:translate-y-[-1px] active:scale-95 transition-all flex items-center justify-center min-w-[100px]">
                                Save Note
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const API_BASE = '/api/v1/institute';
            const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let categories = [];
            let currentNotes = [];
            let activeCategory = 'All';

            async function init() {
                await fetchNotes();
            }

            async function loadCategories() {
                try {
                    const response = await fetch(`${API_BASE}/note-categories`);
                    const result = await response.json();
                    categories = result.data || result;
                    renderCategorySelect();
                } catch (error) { console.error('Category Error:', error); }
            }

            function renderCategoryFilters() {
                // Removed dynamic filter rendering to avoid unnecessary API calls on load
            }

            function toggleDropdown() {
                const list = document.getElementById('dropdown-options');
                const arrow = document.getElementById('dropdown-arrow');
                const isHidden = list.classList.contains('hidden');

                if (isHidden) {
                    list.classList.remove('hidden');
                    setTimeout(() => {
                        list.classList.remove('scale-95', 'opacity-0');
                        list.classList.add('scale-100', 'opacity-100');
                    }, 10);
                    arrow.classList.add('rotate-180');
                } else {
                    list.classList.add('scale-95', 'opacity-0');
                    list.classList.remove('scale-100', 'opacity-100');
                    setTimeout(() => list.classList.add('hidden'), 200);
                    arrow.classList.remove('rotate-180');
                }
            }

            function selectOption(id, name) {
                document.getElementById('category-id-input').value = id;
                const selectedSpan = document.getElementById('selected-name');
                selectedSpan.textContent = name;
                selectedSpan.classList.remove('text-slate-400');
                selectedSpan.classList.add('text-slate-900');
                toggleDropdown();
            }

            function renderCategorySelect() {
                const list = document.getElementById('dropdown-options');
                const uniqueCategories = [];
                const seenNames = new Set();

                categories.forEach(cat => {
                    const name = cat.name ? cat.name.toString().trim() : '';
                    if (!name || /^\d+$/.test(name)) return;
                    if (!seenNames.has(name.toLowerCase())) {
                        uniqueCategories.push(cat);
                        seenNames.add(name.toLowerCase());
                    }
                });

                list.innerHTML = uniqueCategories.map(cat => `
                            <div onclick="selectOption('${cat.id}', '${cat.name}')" 
                                class="px-4 py-2 text-sm font-bold text-slate-800 hover:bg-slate-50 hover:text-primary cursor-pointer transition-colors">
                                ${cat.name}
                            </div>
                        `).join('');
            }

            async function fetchNotes() {
                const grid = document.getElementById('notes-grid');
                if (!grid) return;

                try {
                    const response = await fetch(`${API_BASE}/notes`);
                    const result = await response.json();
                    
                    // Normalize data to always be an array
                    currentNotes = Array.isArray(result.data) ? result.data : (Array.isArray(result) ? result : []);
                    
                    renderNotes();
                } catch (error) {
                    console.error('Fetch Error:', error);
                    grid.innerHTML = '<div class="col-span-full text-center py-20 text-rose-500 font-bold">Failed to load notes. Check console for details.</div>';
                }
            }

            function renderNotes() {
                const grid = document.getElementById('notes-grid');
                const filtered = activeCategory === 'All'
                    ? currentNotes
                    : currentNotes.filter(n => (n.category_relation?.name || n.category) === activeCategory);

                if (filtered.length === 0) {
                    grid.innerHTML = '<div class="col-span-full text-center py-20 text-slate-400 font-bold">No notes found in this workspace.</div>';
                    return;
                }

                grid.innerHTML = filtered.map(note => {
                    const date = new Date(note.updated_at || note.created_at);
                    const timeAgo = getTimeAgo(date);
                    const categoryName = note.category_relation?.name || note.category || 'Uncategorized';
                    const catColor = getCategoryColor(categoryName);
                    const cleanPreview = stripHTML(note.content || 'No content provided.');
                    const imageHtml = note.image_url ? `
                                <div class="w-full h-24 overflow-hidden border-b border-slate-50">
                                    <img src="${note.image_url}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="${note.title}">
                                </div>
                            ` : '';

                    return `
                                <div class="group bg-white rounded-xl border border-slate-100 shadow-sm hover:shadow-xl hover:translate-y-[-4px] transition-all duration-300 relative overflow-hidden flex flex-col h-full">
                                    ${imageHtml}
                                    <div class="p-3 flex flex-col flex-1">
                                        <div class="flex items-center justify-between mb-1.5">
                                            <span class="px-2 py-0.5 ${catColor} rounded-md text-[8px] font-black uppercase tracking-widest">
                                                ${categoryName}
                                            </span>
                                        </div>
                                        <h3 class="text-base font-bold text-slate-800 mb-1 line-clamp-1 hover:text-primary transition-colors">${note.title}</h3>
                                        <div class="text-sm text-slate-500 font-medium leading-relaxed mb-3 line-clamp-4">${cleanPreview}</div>

                                        <div class="flex items-center justify-between pt-2.5 border-t border-slate-50 mt-auto">
                                            <span class="text-[9px] font-bold text-slate-300 uppercase tracking-tighter">${timeAgo}</span>
                                            <div class="flex items-center gap-1 transition-opacity">
                                                <button onclick="viewNote(${note.id})" class="p-1 text-slate-400 hover:text-primary transition-colors" title="View Note">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </button>
                                                <button onclick="showDeleteModal(${note.id})" class="p-1 text-slate-400 hover:text-rose-500 transition-colors" title="Delete Note">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                }).join('');
            }

            function stripHTML(html) {
                const tmp = document.createElement("DIV");
                tmp.innerHTML = html;
                return tmp.textContent || tmp.innerText || "";
            }

            function getCategoryColor(name) {
                const colors = {
                    'Work': 'bg-rose-50 text-rose-500',
                    'Personal': 'bg-blue-50 text-blue-500',
                    'Ideas': 'bg-emerald-50 text-emerald-500',
                    'Meeting Notes': 'bg-amber-50 text-amber-500',
                    'Family': 'bg-purple-50 text-purple-500',
                    'Important': 'bg-orange-50 text-orange-500'
                };
                return colors[name] || 'bg-slate-50 text-slate-400';
            }

            function getTimeAgo(date) {
                const seconds = Math.floor((new Date() - date) / 1000);
                let interval = seconds / 31536000;
                if (interval > 1) return Math.floor(interval) + " years ago";
                interval = seconds / 2592000;
                if (interval > 1) return Math.floor(interval) + " months ago";
                interval = seconds / 86400;
                if (interval > 1) return Math.floor(interval) + " days ago";
                interval = seconds / 3600;
                if (interval > 1) return Math.floor(interval) + " hours ago";
                interval = seconds / 60;
                if (interval > 1) return Math.floor(interval) + " minutes ago";
                return "Just now";
            }

            function filterNotes(category) {
                activeCategory = category;
                const tabs = document.querySelectorAll('.category-tab');
                tabs.forEach(tab => {
                    if (tab.textContent.trim() === category || (category === 'All' && tab.textContent.trim() === 'All Notes')) {
                        tab.classList.add('text-[#A8440B]', 'border-[#A8440B]');
                        tab.classList.remove('text-slate-400', 'border-transparent');
                    } else {
                        tab.classList.remove('text-[#A8440B]', 'border-[#A8440B]');
                        tab.classList.add('text-slate-400', 'border-transparent');
                    }
                });
                renderNotes();
            }

            async function openNoteModal() {
                const modal = document.getElementById('note-modal');
                const content = document.getElementById('note-modal-content');
                document.getElementById('note-form').reset();
                document.getElementById('note-editor').innerHTML = '';
                document.getElementById('note-content-input').value = '';
                document.getElementById('note-id').value = '';
                document.getElementById('modal-title').textContent = 'Note Details';
                document.getElementById('note-error').classList.add('hidden');
                document.getElementById('last-edited').textContent = '';
                document.getElementById('image-preview-container').classList.replace('flex', 'hidden');
                document.getElementById('image-name').textContent = '';
                document.getElementById('note-image-input').value = '';

                const selectedSpan = document.getElementById('selected-name');
                selectedSpan.textContent = 'Select Category';
                selectedSpan.classList.add('text-slate-400');
                selectedSpan.classList.remove('text-slate-900');

                modal.classList.remove('hidden');
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);

                await loadCategories();
            }

            function closeNoteModal() {
                const modal = document.getElementById('note-modal');
                const content = document.getElementById('note-modal-content');
                content.classList.add('scale-95', 'opacity-0');
                content.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => modal.classList.add('hidden'), 300);
            }

            async function editNote(id) {
                const note = currentNotes.find(n => n.id == id);
                if (!note) return;

                await openNoteModal();
                document.getElementById('note-id').value = note.id;
                document.getElementById('category-id-input').value = note.category_id;

                const catName = note.category_relation?.name || note.category || 'Select Category';
                const selectedSpan = document.getElementById('selected-name');
                selectedSpan.textContent = catName;
                selectedSpan.classList.remove('text-slate-400');
                selectedSpan.classList.add('text-slate-900');

                const form = document.getElementById('note-form');
                form.elements['title'].value = note.title;

                document.getElementById('note-editor').innerHTML = note.content || '';
                document.getElementById('note-content-input').value = note.content || '';

                if (note.image_url) {
                    document.getElementById('image-name').textContent = 'Attached Image';
                    document.getElementById('image-preview-container').classList.replace('hidden', 'flex');
                }

                document.getElementById('last-edited').textContent = `Last edited ${getTimeAgo(new Date(note.updated_at))}`;
            }

            function formatDoc(cmd, value = null) {
                document.execCommand(cmd, false, value);
                document.getElementById('note-editor').focus();
                syncContent();
            }

            function syncContent() {
                document.getElementById('note-content-input').value = document.getElementById('note-editor').innerHTML;
            }

            async function saveNote(event) {
                event.preventDefault();
                const form = event.target;
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());
                const id = data.id;
                const saveBtn = document.getElementById('save-note-btn');
                const originalText = saveBtn.textContent;

                saveBtn.disabled = true;
                saveBtn.innerHTML = '<div class="h-4 w-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>';

                try {
                    const url = id ? `${API_BASE}/notes/${id}` : `${API_BASE}/notes`;
                    const method = 'POST'; // Use POST for both, with spoofing for PUT

                    if (id) {
                        formData.append('_method', 'PUT');
                    }

                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    });

                    if (response.ok) {
                        closeNoteModal();
                        fetchNotes();
                    } else {
                        const err = await response.json();
                        const errDiv = document.getElementById('note-error');
                        errDiv.textContent = err.message || 'Validation failed';
                        errDiv.classList.remove('hidden');
                    }
                } catch (error) { console.error(error); } finally {
                    saveBtn.disabled = false;
                    saveBtn.textContent = originalText;
                }
            }

            async function deleteNote(id) {
                try {
                    const response = await fetch(`${API_BASE}/notes/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }
                    });
                    if (response.ok) fetchNotes();
                } catch (error) { console.error(error); }
            }

            let noteIdToDelete = null;

            function showDeleteModal(id) {
                noteIdToDelete = id;
                const modal = document.getElementById('delete-modal');
                const content = document.getElementById('delete-modal-content');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            }

            function closeDeleteModal() {
                const modal = document.getElementById('delete-modal');
                const content = document.getElementById('delete-modal-content');
                content.classList.replace('scale-100', 'scale-95');
                content.classList.replace('opacity-100', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    noteIdToDelete = null;
                }, 300);
            }

            async function confirmDelete() {
                if (!noteIdToDelete) return;
                const btn = document.getElementById('confirm-delete-btn');
                const originalText = btn.textContent;
                btn.disabled = true;
                btn.innerHTML = '<div class="h-4 w-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>';

                await deleteNote(noteIdToDelete);
                
                btn.disabled = false;
                btn.textContent = originalText;
                closeDeleteModal();
            }



            document.addEventListener('click', (e) => {
                const dropdown = document.getElementById('custom-category-dropdown');
                const list = document.getElementById('dropdown-options');
                if (dropdown && !dropdown.contains(e.target) && !list.classList.contains('hidden')) {
                    toggleDropdown();
                }
            });

            init();

            function previewImage(event) {
                const file = event.target.files[0];
                if (file) {
                    document.getElementById('image-name').textContent = file.name;
                    document.getElementById('image-preview-container').classList.replace('hidden', 'flex');
                }
            }

            function removeImage() {
                document.getElementById('note-image-input').value = '';
                document.getElementById('image-preview-container').classList.replace('flex', 'hidden');
                document.getElementById('image-name').textContent = '';
            }

            let currentlyViewingId = null;

            function viewNote(id) {
                const note = currentNotes.find(n => n.id === id);
                if (!note) return;

                currentlyViewingId = id;
                const modal = document.getElementById('view-modal');
                const content = document.getElementById('view-modal-content');
                
                document.getElementById('view-title').textContent = note.title;
                document.getElementById('view-content').innerHTML = note.content || 'No content provided.';
                
                const categoryName = note.category_relation?.name || note.category || 'Uncategorized';
                const catSpan = document.getElementById('view-category');
                catSpan.textContent = categoryName;
                catSpan.className = `px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-widest ${getCategoryColor(categoryName)}`;
                
                document.getElementById('view-date').textContent = `Created ${getTimeAgo(new Date(note.created_at))} • Updated ${getTimeAgo(new Date(note.updated_at))}`;

                const imgContainer = document.getElementById('view-image-container');
                if (note.image_url) {
                    document.getElementById('view-image').src = note.image_url;
                    imgContainer.classList.remove('hidden');
                } else {
                    imgContainer.classList.add('hidden');
                }

                modal.classList.remove('hidden');
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            }

            function closeViewModal() {
                const modal = document.getElementById('view-modal');
                const content = document.getElementById('view-modal-content');
                content.classList.replace('scale-100', 'scale-95');
                content.classList.replace('opacity-100', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    currentlyViewingId = null;
                }, 300);
            }

            async function deleteNoteFromView() {
                if (!currentlyViewingId) return;
                showDeleteModal(currentlyViewingId);
            }
        </script>
    @endpush

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .category-tab.active {
            color: #A8440B !important;
            border-bottom-color: #A8440B !important;
        }

        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <!-- View Note Modal -->
    <div id="view-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div onclick="closeViewModal()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div id="view-modal-content" class="relative w-full max-w-2xl scale-95 opacity-0 bg-white rounded-2xl shadow-2xl transition-all duration-300 overflow-hidden border border-slate-100">
                <div class="px-5 py-2 border-b border-slate-50 flex items-center justify-between bg-slate-50/30 rounded-t-2xl">
                    <div class="flex items-center gap-2">
                        <span id="view-category" class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-widest"></span>
                    </div>
                    <button onclick="closeViewModal()" class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-full transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-4">
                    <div id="view-image-container" class="hidden mb-4 rounded-xl overflow-hidden border border-slate-100 shadow-sm mx-auto w-fit">
                        <img id="view-image" src="" class="max-w-full h-auto max-h-[250px] object-contain" alt="">
                    </div>
                    
                    <h2 id="view-title" class="text-xl font-black text-slate-800 mb-2 leading-tight"></h2>
                    <div id="view-content" class="text-slate-600 text-sm leading-relaxed space-y-3 prose prose-slate max-w-none"></div>
                    
                    <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-between text-[10px] text-slate-300 font-bold uppercase tracking-widest">
                        <span id="view-date"></span>
                        <button onclick="deleteNoteFromView()" class="flex items-center gap-2 text-rose-400 hover:text-rose-600 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 z-[200] hidden overflow-y-auto">
        <div onclick="closeDeleteModal()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div id="delete-modal-content" class="relative w-full max-w-md scale-95 opacity-0 bg-white rounded-2xl shadow-2xl transition-all duration-300 overflow-hidden border-t-4 border-primary">
                <div class="p-8">
                    <div class="flex items-start gap-5">
                        <div class="h-12 w-12 bg-orange-50 rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-slate-800 mb-2 leading-tight">Delete Note?</h3>
                            <p class="text-sm text-slate-500 leading-relaxed">
                                Are you sure you want to permanently remove <span class="font-bold text-slate-700">this note</span>? This action cannot be undone and will erase all history.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-8">
                        <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 bg-white border-2 border-slate-200 text-slate-500 rounded-xl text-sm font-bold hover:bg-slate-50 transition-all">
                            Cancel
                        </button>
                        <button id="confirm-delete-btn" onclick="confirmDelete()" class="flex-1 px-4 py-2.5 bg-primary text-white rounded-xl text-sm font-bold shadow-lg shadow-orange-900/20 hover:opacity-90 active:scale-95 transition-all flex items-center justify-center">
                            Yes, Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection