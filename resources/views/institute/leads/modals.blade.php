<!-- Add/Edit Lead Modal -->
<div id="lead-modal" class="fixed inset-0 z-[120] hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div onclick="closeLeadModal()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

        <div id="lead-modal-content"
            class="relative w-full max-w-xl scale-95 opacity-0 bg-white rounded-xl shadow-2xl transition-all duration-300 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 id="modal-title" class="text-base font-bold text-slate-800">Add Lead Data</h3>
                </div>
                <button onclick="closeLeadModal()"
                    class="h-8 w-8 flex items-center justify-center rounded-full hover:bg-slate-50 text-slate-400 hover:text-slate-600 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="lead-form" onsubmit="saveLead(event)" class="p-6 pt-2 pb-1 space-y-4">
                <input type="hidden" id="lead-id" name="id">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="col-span-1">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">Full
                            Name</label>
                        <input type="text" name="full_name" required placeholder="Johnnathan Doe"
                            class="w-full px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-[#ff6c00] transition-all outline-none">
                    </div>

                    <div class="col-span-1">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">Phone</label>
                        <input type="text" name="phone" required placeholder="+1 (555) 000-0000"
                            class="w-full px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-[#ff6c00] transition-all outline-none">
                    </div>

                    <div class="col-span-1 sm:col-span-2">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">Email</label>
                        <input type="email" name="email" placeholder="john@example.com"
                            class="w-full px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-[#ff6c00] transition-all outline-none">
                    </div>

                    <div class="col-span-1 sm:col-span-2">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">Address</label>
                        <div class="relative">
                            <input type="text" name="address" placeholder="Street, City, State, ZIP"
                                class="w-full pl-4 pr-12 py-2 bg-slate-50 border border-slate-100 rounded-xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-[#ff6c00] transition-all outline-none">
                            <svg class="w-5 h-5 absolute right-4 top-2 text-slate-300" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                            </svg>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">Course
                            Selection</label>
                        <div class="relative">
                            <input type="text" name="course_selection" placeholder="e.g. Advanced UI Design"
                                class="w-full pl-4 pr-12 py-2 bg-slate-50 border border-slate-100 rounded-xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-[#ff6c00] transition-all outline-none">
                            <svg class="w-5 h-5 absolute right-4 top-2 text-slate-300" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z" />
                            </svg>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">Lead
                            Source</label>
                        <div class="relative">
                            <input type="text" name="reference" placeholder="Social Media, referral..."
                                class="w-full pl-4 pr-12 py-2 bg-slate-50 border border-slate-100 rounded-xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-[#ff6c00] transition-all outline-none">
                            <svg class="w-5 h-5 absolute right-4 top-2 text-slate-300" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M14 11h-4v-2h4v2zm10-4v12c0 1.1-.9 2-2 2H2c-1.1 0-2-.9-2-2V7c0-1.1.9-2 2-2h4V2h8v3h4c1.1 0 2 .9 2 2zm-14-3h4v1H10V4z" />
                            </svg>
                        </div>
                    </div>

                    <div class="col-span-1 sm:col-span-2">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">Referrer</label>
                        <div class="relative">
                            <input type="text" name="referer" placeholder="Name of Referrer"
                                class="w-full pl-4 pr-12 py-2 bg-slate-50 border border-slate-100 rounded-xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-[#ff6c00] transition-all outline-none">
                            <svg class="w-5 h-5 absolute right-4 top-2 text-slate-300" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div id="lead-error"
                    class="hidden px-4 py-2 bg-rose-50 border border-rose-100 rounded-xl text-[11px] font-bold text-rose-500 mb-2">
                </div>

                <div class="pb-2 flex items-center justify-end gap-10">
                    <button type="button" onclick="closeLeadModal()"
                        class="text-sm font-bold text-slate-400 hover:text-slate-600 transition-all">Cancel</button>
                    <button type="submit" id="save-lead-btn"
                        class="px-8 py-3 bg-primary  text-white rounded-xl text-sm font-bold shadow-lg  hover:translate-y-[-1px] active:scale-95 transition-all flex items-center justify-center gap-2 min-w-[140px]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Save Lead
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Interaction Note Modal -->
<div id="note-modal" class="fixed inset-0 z-[120] hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div onclick="closeNoteModal()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

        <div id="note-modal-content"
            class="relative w-full max-w-md scale-95 opacity-0 bg-white rounded-2xl shadow-2xl transition-all duration-300 overflow-hidden">
            <div class="px-6 pt-2 pb-1 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800">Add Interaction Note</h3>
                <button onclick="closeNoteModal()"
                    class="h-8 w-8 flex items-center justify-center rounded-full hover:bg-slate-50 text-slate-400 hover:text-slate-600 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="note-form" onsubmit="saveNote(event)" class="p-6 space-y-4">
                <div>
                    <label
                        class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">Interaction
                        Type / Title</label>
                    <input type="text" name="title" required placeholder="e.g. Phone Consultation"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-[#ff6c00] transition-all outline-none">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">Notes
                        / Details</label>
                    <textarea name="note" required rows="4" placeholder="Brief details of the interaction..."
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-[#ff6c00] transition-all outline-none resize-none"></textarea>
                </div>
                <div id="note-error"
                    class="hidden px-4 py-2 bg-rose-50 border border-rose-100 rounded-xl text-[11px] font-bold text-rose-500 mb-2">
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeNoteModal()"
                        class="flex-1 px-6 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-200 transition-all">Cancel</button>
                    <button type="submit"
                        class="flex-1 px-6 py-2.5 bg-primary text-white rounded-xl text-sm font-bold shadow-lg shadow-amber-900/20 hover:translate-y-[-1px] active:scale-95 transition-all">Add
                        Note</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Generic Confirmation Modal -->
<div id="confirm-modal" class="fixed inset-0 z-[150] hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div onclick="closeConfirmModal()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity">
        </div>

        <div id="confirm-modal-content"
            class="relative w-full max-w-md scale-95 opacity-0 bg-white rounded-2xl shadow-2xl transition-all duration-300 overflow-hidden border-t-4 border-primary">
            <div class="p-8">
                <div class="flex items-start gap-5 mb-6">
                    <div class="h-12 w-12 bg-orange-50 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 id="confirm-title" class="text-2xl font-bold text-slate-800 mb-2 leading-tight">Delete Lead?
                        </h3>
                        <p id="confirm-message" class="text-sm text-slate-500 leading-relaxed">
                            Are you sure you want to permanently remove <span class="font-bold text-slate-700"
                                id="confirm-item-name">this lead</span>? This action cannot be undone and will erase all
                            history.
                        </p>
                    </div>
                </div>

                <div class="flex gap-3 mt-8">
                    <button onclick="closeConfirmModal()"
                        class="flex-1 px-4 py-2.5 bg-white border-2 border-slate-200 text-slate-500 rounded-xl text-sm font-bold hover:bg-slate-50 transition-all">Cancel</button>
                    <button id="confirm-proceed-btn"
                        class="flex-1 px-4 py-2.5 bg-primary text-white rounded-xl text-sm font-bold shadow-lg shadow-amber-900/20 hover:opacity-90 active:scale-95 transition-all">Yes,
                        Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>