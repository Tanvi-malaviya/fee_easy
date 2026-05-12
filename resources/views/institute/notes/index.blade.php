@extends('layouts.institute')

@section('content')
    <div class="max-w-7xl mx-auto px-4 md:px-6 py-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Your Workspace</h1>
                <p class="text-sm text-slate-500 mt-1 font-medium italic">Capture thoughts, refine ideas, and keep
                    everything in sync.</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="relative hidden lg:block">
                    <input type="text" id="note-search" placeholder="Search notes..."
                        class="pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none w-64 shadow-sm">
                    <svg class="w-4 h-4 absolute left-3.5 top-3 text-slate-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button onclick="openNoteModal()"
                    class="px-6 py-2.5 bg-[#A8440B] text-white rounded-xl text-sm font-bold shadow-lg shadow-orange-900/20 hover:translate-y-[-1px] active:scale-95 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    New Note
                </button>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div id="category-tabs"
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
        </div>

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
                class="relative w-full max-w-2xl scale-95 opacity-0 bg-white rounded-2xl shadow-2xl transition-all duration-300 overflow-hidden border border-slate-100">
                <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
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

                <form id="note-form" onsubmit="saveNote(event)" class="p-8 space-y-6">
                    <input type="hidden" id="note-id" name="id">

                    <div>
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Title</label>
                        <input type="text" name="title" required placeholder="Weekend Trip Ideas"
                            class="w-full px-0 text-2xl font-bold text-slate-800 placeholder-slate-300 border-none focus:ring-0 bg-transparent">
                    </div>

                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <label
                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Category</label>
                            <div class="relative">
                                <select name="category" id="category-select" required
                                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-primary transition-all outline-none appearance-none">
                                    <option value="">Select Category</option>
                                    <option value="Work">Work</option>
                                    <option value="Personal">Personal</option>
                                    <option value="Ideas">Ideas</option>
                                    <option value="Meeting Notes">Meeting Notes</option>
                                    <option value="Family">Family</option>
                                    <option value="Important">Important</option>
                                </select>
                                <svg class="w-4 h-4 absolute left-3.5 top-3 text-slate-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-end gap-2 pt-6">
                            <button type="button"
                                class="px-4 py-2.5 bg-slate-50 text-slate-500 rounded-xl text-xs font-bold hover:bg-slate-100 transition-all border border-slate-100 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Add Checklist
                            </button>
                            <button type="button"
                                class="px-4 py-2.5 bg-slate-50 text-slate-500 rounded-xl text-xs font-bold hover:bg-slate-100 transition-all border border-slate-100 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Add Image
                            </button>
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Content</label>
                        <div class="relative group">
                            <input type="hidden" name="content" id="note-content-input">
                            <div id="note-editor" contenteditable="true"
                                class="w-full px-5 py-4 min-h-[250px] bg-slate-50 border border-slate-100 rounded-2xl text-sm font-medium text-slate-700 focus:bg-white focus:border-primary transition-all outline-none leading-relaxed overflow-y-auto"
                                oninput="syncContent()"></div>

                            <!-- Editor Bar -->
                            <div
                                class="absolute bottom-4 left-4 right-4 py-2 px-4 bg-white border border-slate-100 rounded-xl shadow-sm flex items-center justify-between">
                                <div class="flex items-center gap-4 text-slate-400">
                                    <button type="button" onclick="formatDoc('bold')"
                                        class="hover:text-primary font-black text-sm transition-colors">B</button>
                                    <button type="button" onclick="formatDoc('italic')"
                                        class="hover:text-primary italic text-sm font-serif transition-colors">I</button>
                                    <button type="button" onclick="formatDoc('underline')"
                                        class="hover:text-primary underline text-sm transition-colors">U</button>
                                    <div class="w-px h-4 bg-slate-100"></div>
                                    <button type="button" onclick="formatDoc('insertUnorderedList')"
                                        class="hover:text-primary transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                        </svg>
                                    </button>
                                    <button type="button" onclick="formatDoc('insertOrderedList')"
                                        class="hover:text-primary transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                    </button>
                                </div>
                                <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">Editor</span>
                            </div>
                        </div>
                    </div>

                    <div id="note-error"
                        class="hidden px-4 py-2 bg-rose-50 border border-rose-100 rounded-xl text-[11px] font-bold text-rose-500 mb-2">
                    </div>

                    <div class="pt-4 flex items-center justify-between">
                        <span id="last-edited" class="text-[10px] font-medium text-slate-300 italic uppercase"></span>
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="closeNoteModal()"
                                class="px-6 py-2.5 text-sm font-bold text-slate-400 hover:text-slate-600 transition-all">Cancel</button>
                            <button type="submit" id="save-note-btn"
                                class="px-10 py-2.5 bg-[#A8440B] text-white rounded-xl text-sm font-bold shadow-lg shadow-orange-900/20 hover:translate-y-[-1px] active:scale-95 transition-all">
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

            function renderCategorySelect() {
                const select = document.getElementById('category-select');
                select.innerHTML = '<option value="">Select Category</option>' +
                    categories.map(cat => `<option value="${cat.id}">${cat.name}</option>`).join('');
            }

            async function fetchNotes() {
                const search = document.getElementById('note-search').value;
                const grid = document.getElementById('notes-grid');

                try {
                    const response = await fetch(`${API_BASE}/notes?search=${encodeURIComponent(search)}`);
                    const result = await response.json();
                    currentNotes = result.data || result;
                    renderNotes();
                } catch (error) {
                    console.error('Fetch Error:', error);
                    grid.innerHTML = '<div class="col-span-full text-center text-rose-500 font-bold">Failed to load notes.</div>';
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

                    return `
                                            <div class="group bg-white rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-xl hover:translate-y-[-4px] transition-all duration-300 relative overflow-hidden">
                                                <div class="flex items-center justify-between mb-4">
                                                    <span class="px-3 py-1 ${catColor} rounded-md text-[9px] font-black uppercase tracking-widest">
                                                        ${categoryName}
                                                    </span>
                                                </div>
                                                <h3 onclick="editNote(${note.id})" class="text-lg font-bold text-slate-800 mb-3 cursor-pointer group-hover:text-primary transition-colors line-clamp-1">${note.title}</h3>
                                                <div class="text-xs text-slate-500 font-medium leading-relaxed mb-6 line-clamp-3">${cleanPreview}</div>

                                                <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                                                    <span class="text-[10px] font-bold text-slate-300 uppercase tracking-tighter">${timeAgo}</span>
                                                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <button onclick="editNote(${note.id})" class="p-2 text-slate-400 hover:text-primary transition-colors">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        </button>
                                                        <button onclick="deleteNote(${note.id})" class="p-2 text-slate-400 hover:text-rose-500 transition-colors">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
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

            function openNoteModal() {
                const modal = document.getElementById('note-modal');
                const content = document.getElementById('note-modal-content');
                document.getElementById('note-form').reset();
                document.getElementById('note-editor').innerHTML = '';
                document.getElementById('note-content-input').value = '';
                document.getElementById('note-id').value = '';
                document.getElementById('modal-title').textContent = 'Note Details';
                document.getElementById('note-error').classList.add('hidden');
                document.getElementById('last-edited').textContent = '';

                modal.classList.remove('hidden');
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);

                loadCategories();
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

                openNoteModal();
                document.getElementById('note-id').value = note.id;
                const form = document.getElementById('note-form');
                form.elements['title'].value = note.title;
                form.elements['category_id'].value = note.category_id;

                document.getElementById('note-editor').innerHTML = note.content || '';
                document.getElementById('note-content-input').value = note.content || '';

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
                saveBtn.innerHTML = '<div class="h-4 w-4 border-2 border-white/30 border-t-white rounded-full animate-spin mx-auto"></div>';

                try {
                    const url = id ? `${API_BASE}/notes/${id}` : `${API_BASE}/notes`;
                    const method = id ? 'PUT' : 'POST';

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
                if (!confirm('Are you sure you want to delete this note?')) return;
                try {
                    const response = await fetch(`${API_BASE}/notes/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }
                    });
                    if (response.ok) fetchNotes();
                } catch (error) { console.error(error); }
            }

            document.getElementById('note-search').addEventListener('input', () => {
                clearTimeout(window.searchTimeout);
                window.searchTimeout = setTimeout(fetchNotes, 300);
            });

            init();
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
@endsection