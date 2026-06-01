@extends('layouts.institute')

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-4">
            <div>
                <h1 class="text-xl font-semibold text-slate-800 tracking-tight">Your Workspace</h1>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="toggleBookmarkFilter()" id="bookmark-filter-btn"
                    class="px-5 py-2.5 bg-white border border-slate-200 text-slate-500 rounded-xl text-sm font-bold shadow-sm hover:border-primary/30 hover:text-primary transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                    Bookmarks
                </button>
                @if(Auth::guard('institute')->user()->hasActiveSubscription())
                <button onclick="openNoteModal()"
                    class="px-6 py-2.5 bg-primary text-white rounded-xl text-sm font-bold shadow-lg shadow-orange-900/20 hover:translate-y-[-1px] active:scale-95 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    New Note
                </button>
                @else
                <button onclick="handleExpiredSubscription(event)"
                    class="px-6 py-2.5 bg-primary text-white rounded-xl text-sm font-bold shadow-lg shadow-orange-900/20 hover:translate-y-[-1px] active:scale-95 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    New Note
                </button>
                @endif
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
                class="relative w-full max-w-3xl scale-95 opacity-0 bg-white rounded-2xl shadow-2xl transition-all duration-300 overflow-visible border border-slate-100">
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
                    <input type="hidden" id="remove-image-input" name="remove_image" value="0">

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
                                <input type="hidden" name="category_id" id="category-id-input">
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
                        <div class="flex items-end gap-3 pt-5">
                            <input type="file" id="note-image-input" name="image" class="hidden" accept="image/*"
                                onchange="previewImage(event)">
                            <button type="button" onclick="document.getElementById('note-image-input').click()"
                                class="px-3 py-2 bg-slate-50 text-slate-500 rounded-xl text-xs font-bold hover:bg-slate-100 transition-all border border-slate-100 flex items-center gap-2 h-[38px] shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Add Image
                            </button>

                            <!-- Image Preview Container -->
                            <div id="image-preview-container"
                                class="hidden items-center gap-2 px-2 py-1 bg-slate-50 border border-slate-100 rounded-xl h-[38px]">
                                <div class="w-7 h-7 rounded-lg overflow-hidden border border-slate-200 shrink-0">
                                    <img id="image-preview-element" src="" class="w-full h-full object-cover">
                                </div>
                                <span id="image-name" class="text-[10px] font-bold text-slate-600 truncate max-w-[120px]"></span>
                                <button type="button" onclick="removeImage()"
                                    class="p-1 hover:bg-slate-200 rounded-md text-rose-500 transition-colors shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1 ml-1">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Content</label>
                            <div class="flex items-center gap-2">
                                <span id="autosave-indicator" class="text-[9px] font-bold text-slate-300 uppercase tracking-wider hidden">
                                    <span id="autosave-text">Saving...</span>
                                </span>
                                <span id="word-count" class="text-[9px] font-bold text-slate-300 uppercase tracking-wider">0 words</span>
                            </div>
                        </div>

                        <!-- Rich Text Toolbar -->
                        <div class="flex flex-wrap items-center gap-0.5 px-2 py-1.5 bg-slate-50 border border-slate-100 rounded-t-xl border-b-0">

                            <!-- Font Size -->
                            <select id="font-size-select" onchange="applyFontSize(this.value)"
                                class="h-7 px-1.5 text-[11px] font-bold text-slate-600 bg-white border border-slate-200 rounded-md mr-1 outline-none cursor-pointer hover:border-primary transition-colors">
                                <option value="1">Small</option>
                                <option value="3" selected>Normal</option>
                                <option value="5">Large</option>
                                <option value="7">Huge</option>
                            </select>

                            <!-- Heading -->
                            <select id="heading-select" onchange="applyHeading(this.value)"
                                class="h-7 px-1.5 text-[11px] font-bold text-slate-600 bg-white border border-slate-200 rounded-md mr-1 outline-none cursor-pointer hover:border-primary transition-colors">
                                <option value="">Paragraph</option>
                                <option value="h1">Heading 1</option>
                                <option value="h2">Heading 2</option>
                                <option value="h3">Heading 3</option>
                            </select>

                            <div class="w-px h-5 bg-slate-200 mx-1"></div>

                            <!-- Bold -->
                            <button type="button" id="btn-bold" onclick="fmt('bold')" title="Bold (Ctrl+B)"
                                class="toolbar-btn h-7 w-7 flex items-center justify-center rounded-md text-slate-500 hover:bg-white hover:text-primary hover:shadow-sm transition-all">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M15.6 10.79c.97-.67 1.65-1.77 1.65-2.79 0-2.26-1.75-4-4-4H7v14h7.04c2.09 0 3.71-1.7 3.71-3.79 0-1.52-.86-2.82-2.15-3.42zM10 6.5h3c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5h-3v-3zm3.5 9H10v-3h3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5z"/></svg>
                            </button>

                            <!-- Italic -->
                            <button type="button" id="btn-italic" onclick="fmt('italic')" title="Italic (Ctrl+I)"
                                class="toolbar-btn h-7 w-7 flex items-center justify-center rounded-md text-slate-500 hover:bg-white hover:text-primary hover:shadow-sm transition-all">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M10 4v3h2.21l-3.42 8H6v3h8v-3h-2.21l3.42-8H18V4z"/></svg>
                            </button>

                            <!-- Underline -->
                            <button type="button" id="btn-underline" onclick="fmt('underline')" title="Underline (Ctrl+U)"
                                class="toolbar-btn h-7 w-7 flex items-center justify-center rounded-md text-slate-500 hover:bg-white hover:text-primary hover:shadow-sm transition-all">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17c3.31 0 6-2.69 6-6V3h-2.5v8c0 1.93-1.57 3.5-3.5 3.5S8.5 12.93 8.5 11V3H6v8c0 3.31 2.69 6 6 6zm-7 2v2h14v-2H5z"/></svg>
                            </button>

                            <!-- Strikethrough -->
                            <button type="button" id="btn-strikethrough" onclick="fmt('strikeThrough')" title="Strikethrough"
                                class="toolbar-btn h-7 w-7 flex items-center justify-center rounded-md text-slate-500 hover:bg-white hover:text-primary hover:shadow-sm transition-all">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M10 19h4v-3h-4v3zM5 4v3h5v3h4V7h5V4H5zM3 14h18v-2H3v2z"/></svg>
                            </button>

                            <div class="w-px h-5 bg-slate-200 mx-1"></div>

                            <!-- Align Left -->
                            <button type="button" id="btn-left" onclick="fmt('justifyLeft')" title="Align Left"
                                class="toolbar-btn h-7 w-7 flex items-center justify-center rounded-md text-slate-500 hover:bg-white hover:text-primary hover:shadow-sm transition-all">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M15 15H3v2h12v-2zm0-8H3v2h12V7zM3 13h18v-2H3v2zm0 8h18v-2H3v2zM3 3v2h18V3H3z"/></svg>
                            </button>

                            <!-- Align Center -->
                            <button type="button" id="btn-center" onclick="fmt('justifyCenter')" title="Align Center"
                                class="toolbar-btn h-7 w-7 flex items-center justify-center rounded-md text-slate-500 hover:bg-white hover:text-primary hover:shadow-sm transition-all">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M7 15v2h10v-2H7zm-4 6h18v-2H3v2zm0-8h18v-2H3v2zm4-6v2h10V7H7zM3 3v2h18V3H3z"/></svg>
                            </button>

                            <!-- Align Right -->
                            <button type="button" id="btn-right" onclick="fmt('justifyRight')" title="Align Right"
                                class="toolbar-btn h-7 w-7 flex items-center justify-center rounded-md text-slate-500 hover:bg-white hover:text-primary hover:shadow-sm transition-all">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M3 21h18v-2H3v2zm6-4h12v-2H9v2zm-6-4h18v-2H3v2zm6-4h12V7H9v2zM3 3v2h18V3H3z"/></svg>
                            </button>

                            <div class="w-px h-5 bg-slate-200 mx-1"></div>

                            <!-- Bullet List -->
                            <button type="button" id="btn-ul" onclick="fmt('insertUnorderedList')" title="Bullet List"
                                class="toolbar-btn h-7 w-7 flex items-center justify-center rounded-md text-slate-500 hover:bg-white hover:text-primary hover:shadow-sm transition-all">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M4 10.5c-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5 1.5-.67 1.5-1.5-.67-1.5-1.5-1.5zm0-6c-.83 0-1.5.67-1.5 1.5S3.17 7.5 4 7.5 5.5 6.83 5.5 6 4.83 4.5 4 4.5zm0 12c-.83 0-1.5.68-1.5 1.5s.68 1.5 1.5 1.5 1.5-.68 1.5-1.5-.67-1.5-1.5-1.5zM7 19h14v-2H7v2zm0-6h14v-2H7v2zm0-8v2h14V5H7z"/></svg>
                            </button>

                            <!-- Ordered List -->
                            <button type="button" id="btn-ol" onclick="fmt('insertOrderedList')" title="Numbered List"
                                class="toolbar-btn h-7 w-7 flex items-center justify-center rounded-md text-slate-500 hover:bg-white hover:text-primary hover:shadow-sm transition-all">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M2 17h2v.5H3v1h1v.5H2v1h3v-4H2v1zm1-9h1V4H2v1h1v3zm-1 3h1.8L2 13.1v.9h3v-1H3.2L5 10.9V10H2v1zm5-6v2h14V5H7zm0 14h14v-2H7v2zm0-6h14v-2H7v2z"/></svg>
                            </button>



                            <!-- Undo -->
                            <button type="button" onclick="document.execCommand('undo')" title="Undo (Ctrl+Z)"
                                class="toolbar-btn h-7 w-7 flex items-center justify-center rounded-md text-slate-500 hover:bg-white hover:text-slate-700 hover:shadow-sm transition-all ml-auto">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.5 8c-2.65 0-5.05.99-6.9 2.6L2 7v9h9l-3.62-3.62c1.39-1.16 3.16-1.88 5.12-1.88 3.54 0 6.55 2.31 7.6 5.5l2.37-.78C21.08 11.03 17.15 8 12.5 8z"/></svg>
                            </button>

                            <!-- Redo -->
                            <button type="button" onclick="document.execCommand('redo')" title="Redo (Ctrl+Y)"
                                class="toolbar-btn h-7 w-7 flex items-center justify-center rounded-md text-slate-500 hover:bg-white hover:text-slate-700 hover:shadow-sm transition-all">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.4 10.6C16.55 8.99 14.15 8 11.5 8c-4.65 0-8.58 3.03-9.96 7.22L3.9 16c1.05-3.19 4.05-5.5 7.6-5.5 1.95 0 3.73.72 5.12 1.88L13 16h9V7l-3.6 3.6z"/></svg>
                            </button>
                        </div>

                        <!-- Editor Area -->
                        <div class="relative">
                            <input type="hidden" name="content" id="note-content-input">
                            <div id="note-editor" contenteditable="true" spellcheck="true"
                                class="w-full px-4 py-3 min-h-[200px] max-h-[320px] bg-white border border-slate-100 rounded-b-xl text-sm text-slate-700 focus:border-primary transition-all outline-none leading-relaxed overflow-y-auto"
                                style="font-family: inherit;"
                                oninput="onEditorInput()"
                                onkeydown="handleEditorKeydown(event)"
                                onmouseup="updateToolbarState()"
                                onkeyup="updateToolbarState()"></div>
                            <div id="editor-placeholder" class="absolute top-3 left-4 text-sm text-slate-400 font-medium pointer-events-none select-none">Start writing your note...</div>
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

    <!-- Empty State Templates -->
    <template id="notes-empty-state">
        <x-empty-state title="No notes found in this workspace" subtitle="Create your first note in this workspace to get started." icon="notes" />
    </template>

    <template id="bookmarks-empty-state">
        <x-empty-state title="No bookmarked notes found" subtitle="Bookmark important notes to find them quickly here." icon="notes" />
    </template>

    @push('scripts')
        <script>
            const API_BASE = '/api/v1/institute';
            const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let categories = [];
            let currentNotes = [];
            let activeCategory = 'All';
            let showBookmarkedOnly = false;

            async function init() {
                await fetchNotes();
            }

            async function loadCategories() {
                try {
                    const response = await fetch(`${API_BASE}/note-categories`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
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

                // Prepend a 'No Category' option to allow clearing selection
                const noCat = `<div onclick="selectOption('', 'No Category')" 
                    class="px-4 py-2 text-sm font-bold text-slate-400 hover:bg-slate-50 hover:text-slate-600 cursor-pointer transition-colors border-b border-slate-50">
                    No Category
                </div>`;

                list.innerHTML = noCat + uniqueCategories.map(cat => `
                            <div onclick="selectOption('${cat.id}', '${cat.name}')" 
                                class="px-4 py-2 text-sm font-bold text-slate-800 hover:bg-slate-50 hover:text-primary cursor-pointer transition-colors">
                                ${cat.name}
                            </div>
                        `).join('');

                if (uniqueCategories.length === 0) {
                    list.innerHTML += `<div class="px-4 py-3 text-xs text-slate-400 text-center">No categories found</div>`;
                }
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
                
                let filtered = activeCategory === 'All'
                    ? currentNotes
                    : currentNotes.filter(n => (n.category_relation?.name || n.category) === activeCategory);

                if (showBookmarkedOnly) {
                    filtered = filtered.filter(n => n.is_bookmarked);
                }

                if (filtered.length === 0) {
                    const templateId = showBookmarkedOnly ? 'bookmarks-empty-state' : 'notes-empty-state';
                    const template = document.getElementById(templateId);
                    if (template) {
                        grid.innerHTML = template.innerHTML;
                    } else {
                        const message = showBookmarkedOnly ? 'No bookmarked notes found.' : 'No notes found in this workspace.';
                        grid.innerHTML = `<div class="col-span-full text-center py-20 text-slate-400 font-bold">${message}</div>`;
                    }
                    return;
                }

                grid.innerHTML = filtered.map(note => {
                    const date = new Date(note.updated_at || note.created_at);
                    const timeAgo = getTimeAgo(date);
                    const categoryName = note.category_relation?.name || note.category || 'Uncategorized';
                    const catColor = getCategoryColor(categoryName);
                    const richPreview = note.content || '<span style="opacity:.4">No content...</span>';
                    const imageHtml = note.image_url ? `
                                <div class="w-full h-32 overflow-hidden relative">
                                    <img src="${note.image_url}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="${note.title}">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
                                </div>
                            ` : `
                                <div class="w-full h-32 bg-gradient-to-br from-slate-50 to-slate-100/50 flex items-center justify-center relative overflow-hidden">
                                    <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 20px 20px;"></div>
                                    <svg class="w-8 h-8 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                            `;

                    return `
                                <div class="group bg-white rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden flex flex-col h-[320px]">
                                    ${imageHtml}
                                    
                                    <!-- Bookmark Button - Absolute Top -->
                                    <button onclick="event.stopPropagation(); toggleBookmark(${note.id})" 
                                        class="absolute top-3 right-3 p-2.5 rounded-full bg-white/90 backdrop-blur-sm shadow-sm transition-all hover:scale-110 z-20 ${note.is_bookmarked ? 'text-primary' : 'text-slate-300 hover:text-primary'}">
                                        <svg class="w-4 h-4" fill="${note.is_bookmarked ? 'currentColor' : 'none'}" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                    </button>

                                    <div class="p-4 flex flex-col flex-1">
                                        <div class="mb-2">
                                            <span class="px-2 py-0.5 ${catColor} rounded-md text-[8px] font-black uppercase tracking-widest inline-block">
                                                ${categoryName}
                                            </span>
                                        </div>
                                        
                                        <h3 class="text-base font-bold text-slate-800 mb-1 break-words line-clamp-2 hover:text-primary transition-colors cursor-pointer" onclick="viewNote(${note.id})">
                                            ${note.title}
                                        </h3>
                                        
                                        <div class="note-preview leading-relaxed overflow-hidden cursor-pointer mb-3" onclick="viewNote(${note.id})">
                                            ${richPreview}
                                        </div>

                                        <div class="flex items-center justify-between pt-3 border-t border-slate-50 mt-auto">
                                            <span class="text-[9px] font-bold text-slate-300 uppercase tracking-tighter">${timeAgo}</span>
                                            <div class="flex items-center gap-1">
                                                <button onclick="viewNote(${note.id})" class="p-1.5 text-slate-400 hover:text-primary hover:bg-slate-50 rounded-lg transition-all" title="View Note">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </button>
                                                <button onclick="showDeleteModal(${note.id})" class="p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-all" title="Delete Note">
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

            function toggleBookmarkFilter() {
                showBookmarkedOnly = !showBookmarkedOnly;
                const btn = document.getElementById('bookmark-filter-btn');
                
                if (showBookmarkedOnly) {
                    btn.classList.add('bg-primary/5', 'border-primary/30', 'text-primary');
                    btn.classList.remove('bg-white', 'border-slate-200', 'text-slate-500');
                    btn.querySelector('svg').setAttribute('fill', 'currentColor');
                } else {
                    btn.classList.remove('bg-primary/5', 'border-primary/30', 'text-primary');
                    btn.classList.add('bg-white', 'border-slate-200', 'text-slate-500');
                    btn.querySelector('svg').setAttribute('fill', 'none');
                }
                
                renderNotes();
            }

            async function openNoteModal(id = null) {
                const modal = document.getElementById('note-modal');
                const content = document.getElementById('note-modal-content');
                
                // Reset Form
                document.getElementById('note-form').reset();
                document.getElementById('note-editor').innerHTML = '';
                document.getElementById('note-content-input').value = '';
                document.getElementById('note-id').value = '';
                document.getElementById('remove-image-input').value = '0';
                document.getElementById('modal-title').textContent = 'New Note';
                document.getElementById('note-error').classList.add('hidden');
                document.getElementById('last-edited').textContent = '';
                document.getElementById('image-preview-container').classList.replace('flex', 'hidden');
                document.getElementById('image-name').textContent = '';
                document.getElementById('note-image-input').value = '';
                document.getElementById('image-preview-element').src = '';

                const selectedSpan = document.getElementById('selected-name');
                selectedSpan.textContent = 'Select Category';
                selectedSpan.classList.add('text-slate-400');
                selectedSpan.classList.remove('text-slate-900');

                // If Editing, Fetch Data
                if (id) {
                    document.getElementById('modal-title').textContent = 'Edit Note';
                    try {
                        const response = await fetch(`${API_BASE}/notes/${id}`);
                        const result = await response.json();
                        const note = result.data;

                        document.getElementById('note-id').value = note.id;
                        document.querySelector('[name="title"]').value = note.title;
                        
                        // Prefill Category
                        const catName = note.category_relation?.name || note.category;
                        if (note.category_id || catName) {
                            if (note.category_id) document.getElementById('category-id-input').value = note.category_id;
                            selectedSpan.textContent = catName || 'Category';
                            selectedSpan.classList.remove('text-slate-400');
                            selectedSpan.classList.add('text-slate-900');
                        }

                        // Prefill Image if exists
                        if (note.image_url || note.cover_image) {
                            const container = document.getElementById('image-preview-container');
                            const nameSpan = document.getElementById('image-name');
                            const previewImg = document.getElementById('image-preview-element');
                            container.classList.replace('hidden', 'flex');
                            nameSpan.textContent = note.cover_image ? note.cover_image.split('/').pop() : 'Attached Image';
                            if (previewImg) previewImg.src = note.image_url || note.cover_image;
                        }

                        document.getElementById('note-editor').innerHTML = note.content || '';
                        document.getElementById('note-content-input').value = note.content || '';

                        if (note.updated_at) {
                            document.getElementById('last-edited').textContent = `Last edited ${getTimeAgo(new Date(note.updated_at))}`;
                        }
                    } catch (error) {
                        console.error('Prefill Error:', error);
                    }
                }

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
                    document.getElementById('image-preview-element').src = note.image_url;
                    document.getElementById('image-preview-container').classList.replace('hidden', 'flex');
                }

                document.getElementById('last-edited').textContent = `Last edited ${getTimeAgo(new Date(note.updated_at))}`;
            }

            // ── Rich Text Editor Functions ──────────────────────────────────

            /** Execute a document format command and keep focus in editor */
            function fmt(cmd, value = null) {
                document.getElementById('note-editor').focus();
                document.execCommand(cmd, false, value);
                syncContent();
                updateToolbarState();
            }

            /** Apply font size (1–7) */
            function applyFontSize(size) {
                document.getElementById('note-editor').focus();
                document.execCommand('fontSize', false, size);
                syncContent();
            }

            /** Apply a heading tag (h1/h2/h3) or revert to paragraph */
            function applyHeading(tag) {
                document.getElementById('note-editor').focus();
                if (tag === '') {
                    document.execCommand('formatBlock', false, 'p');
                } else {
                    document.execCommand('formatBlock', false, tag);
                }
                syncContent();
            }

            /** Apply yellow highlight to selected text */
            function applyHighlight() {
                document.getElementById('note-editor').focus();
                document.execCommand('hiliteColor', false, '#fef08a');
                syncContent();
            }

            /** Sync hidden input from editor content + update word count + placeholder */
            function syncContent() {
                const editor = document.getElementById('note-editor');
                document.getElementById('note-content-input').value = editor.innerHTML;
                updateWordCount(editor);
                togglePlaceholder(editor);
            }

            /** Called on every input event with auto-save debounce */
            let autoSaveTimer = null;
            function onEditorInput() {
                syncContent();
                // Auto-save indicator
                const indicator = document.getElementById('autosave-indicator');
                const text = document.getElementById('autosave-text');
                if (document.getElementById('note-id').value) { // only when editing existing note
                    indicator.classList.remove('hidden');
                    text.textContent = 'Unsaved changes...';
                    clearTimeout(autoSaveTimer);
                    autoSaveTimer = setTimeout(() => {
                        text.textContent = 'Draft saved';
                        setTimeout(() => indicator.classList.add('hidden'), 1500);
                    }, 2000);
                }
            }

            /** Update word + char count display */
            function updateWordCount(editor) {
                const text = editor.innerText || editor.textContent || '';
                const words = text.trim() === '' ? 0 : text.trim().split(/\s+/).length;
                document.getElementById('word-count').textContent = `${words} word${words !== 1 ? 's' : ''}`;
            }

            /** Show/hide the placeholder */
            function togglePlaceholder(editor) {
                const ph = document.getElementById('editor-placeholder');
                if (!ph) return;
                ph.style.display = (editor.innerText.trim() === '') ? 'block' : 'none';
            }

            /** Reflect active formats in toolbar buttons */
            function updateToolbarState() {
                const cmds = ['bold', 'italic', 'underline', 'strikeThrough',
                              'insertUnorderedList', 'insertOrderedList',
                              'justifyLeft', 'justifyCenter', 'justifyRight'];
                const idMap = {
                    'bold': 'btn-bold', 'italic': 'btn-italic', 'underline': 'btn-underline',
                    'strikeThrough': 'btn-strikethrough', 'insertUnorderedList': 'btn-ul',
                    'insertOrderedList': 'btn-ol', 'justifyLeft': 'btn-left',
                    'justifyCenter': 'btn-center', 'justifyRight': 'btn-right'
                };
                cmds.forEach(cmd => {
                    const btn = document.getElementById(idMap[cmd]);
                    if (!btn) return;
                    const active = document.queryCommandState(cmd);
                    btn.classList.toggle('bg-primary/10', active);
                    btn.classList.toggle('text-primary', active);
                    btn.classList.toggle('text-slate-500', !active);
                });
            }

            /** Keyboard shortcuts inside editor */
            function handleEditorKeydown(e) {
                if (e.ctrlKey || e.metaKey) {
                    switch (e.key.toLowerCase()) {
                        case 'b': e.preventDefault(); fmt('bold'); break;
                        case 'i': e.preventDefault(); fmt('italic'); break;
                        case 'u': e.preventDefault(); fmt('underline'); break;
                        case 's': e.preventDefault();
                            // Ctrl+S triggers save
                            document.getElementById('note-form').dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
                            break;
                        case 'k': e.preventDefault(); fmt('strikeThrough'); break;
                    }
                }
                // Tab inserts 4 spaces instead of losing focus
                if (e.key === 'Tab') {
                    e.preventDefault();
                    document.execCommand('insertHTML', false, '&nbsp;&nbsp;&nbsp;&nbsp;');
                }
                syncContent();
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

            async function toggleBookmark(id) {
                const note = currentNotes.find(n => n.id == id);
                if (!note) return;

                // Optimistic UI update
                const originalStatus = note.is_bookmarked;
                note.is_bookmarked = !originalStatus;
                renderNotes();

                try {
                    const response = await fetch(`${API_BASE}/notes/${id}/bookmark`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        }
                    });

                    if (!response.ok) {
                        // Revert if failed
                        note.is_bookmarked = originalStatus;
                        renderNotes();
                    }
                } catch (error) {
                    note.is_bookmarked = originalStatus;
                    renderNotes();
                }
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
                    document.getElementById('remove-image-input').value = '0';
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('image-preview-element').src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }

            function removeImage() {
                const input = document.getElementById('note-image-input');
                const container = document.getElementById('image-preview-container');
                const nameSpan = document.getElementById('image-name');
                const removeInput = document.getElementById('remove-image-input');
                const previewImg = document.getElementById('image-preview-element');
                
                if (input) input.value = '';
                if (removeInput) removeInput.value = '1';
                if (previewImg) previewImg.src = '';
                
                if (container) {
                    container.classList.add('hidden');
                    container.classList.remove('flex');
                }
                if (nameSpan) nameSpan.textContent = '';
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

        /* ── Rich Text Editor ── */
        .toolbar-btn { flex-shrink: 0; }

        #note-editor:focus { outline: none; }

        #note-editor h1 { font-size: 1.6em; font-weight: 700; margin: .3em 0; }
        #note-editor h2 { font-size: 1.3em; font-weight: 700; margin: .3em 0; }
        #note-editor h3 { font-size: 1.1em; font-weight: 600; margin: .3em 0; }
        #note-editor ul  { list-style: disc;   padding-left: 1.4em; margin: .3em 0; }
        #note-editor ol  { list-style: decimal; padding-left: 1.4em; margin: .3em 0; }
        #note-editor li  { margin: .15em 0; }
        #note-editor blockquote { border-left: 3px solid #e2e8f0; padding-left: .8em; color: #64748b; margin: .4em 0; }
        #note-editor a   { color: #f97316; text-decoration: underline; }
        #note-editor mark, #note-editor [style*="background-color: rgb(254, 240, 138)"],
        #note-editor [style*="background-color:#fef08a"] { background: #fef08a; border-radius: 2px; padding: 0 1px; }

        /* ── Card Rich Preview ── */
        .note-preview {
            font-size: 12px;
            color: #64748b;
            max-height: 82px;
            position: relative;
            -webkit-mask-image: linear-gradient(to bottom, black 60%, transparent 100%);
            mask-image: linear-gradient(to bottom, black 60%, transparent 100%);
            word-break: break-word;
            overflow: hidden;
        }
        .note-preview b, .note-preview strong { font-weight: 700; color: #334155; }
        .note-preview i, .note-preview em { font-style: italic; }
        .note-preview u { text-decoration: underline; }
        .note-preview s, .note-preview strike { text-decoration: line-through; }
        .note-preview h1 { font-size: 1.1em; font-weight: 700; color: #1e293b; }
        .note-preview h2 { font-size: 1em;   font-weight: 700; color: #1e293b; }
        .note-preview h3 { font-size: .95em; font-weight: 600; color: #1e293b; }
        .note-preview ul { list-style: disc;   padding-left: 1.2em; }
        .note-preview ol { list-style: decimal; padding-left: 1.2em; }
        .note-preview [style*="background-color"] { border-radius: 2px; padding: 0 1px; }
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
                    
                    <h2 id="view-title" class="text-xl font-black text-slate-800 mb-2 leading-tight break-words"></h2>
                    <div id="view-content" class="text-slate-600 text-sm leading-relaxed space-y-3 prose prose-slate max-w-none break-words overflow-hidden"></div>
                    
                    <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-between text-[10px] text-slate-300 font-bold uppercase tracking-widest">
                        <span id="view-date"></span>
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