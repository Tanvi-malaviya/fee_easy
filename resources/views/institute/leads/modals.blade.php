<!-- Add/Edit Lead Modal -->
<div id="lead-modal" class="fixed inset-0 z-[120] hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div onclick="closeLeadModal()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
        
        <div id="lead-modal-content" class="relative w-full max-w-xl scale-95 opacity-0 bg-white rounded-[2rem] shadow-2xl transition-all duration-300 overflow-hidden">
            <div class="px-10 py-8 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 id="modal-title" class="text-xl font-bold text-slate-800 mb-1">Add Lead Data</h3>
                    <p class="text-xs text-slate-400 font-medium">Populate the fields below to register a new lead in the system.</p>
                </div>
                <button onclick="closeLeadModal()" class="h-10 w-10 flex items-center justify-center rounded-full hover:bg-slate-50 text-slate-400 hover:text-slate-600 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form id="lead-form" onsubmit="saveLead(event)" class="p-10 space-y-6">
                <input type="hidden" id="lead-id" name="id">
                
                <div class="grid grid-cols-2 gap-6">
                    <div class="col-span-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Full Name</label>
                        <input type="text" name="full_name" required placeholder="Johnnathan Doe"
                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-[#A8440B] focus:ring-4 focus:ring-amber-500/5 transition-all outline-none">
                    </div>
                    
                    <div class="col-span-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Phone</label>
                        <input type="text" name="phone" required placeholder="+1 (555) 000-0000"
                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-[#A8440B] focus:ring-4 focus:ring-amber-500/5 transition-all outline-none">
                    </div>
                    
                    <div class="col-span-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Email</label>
                        <input type="email" name="email" placeholder="john@example.com"
                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-[#A8440B] focus:ring-4 focus:ring-amber-500/5 transition-all outline-none">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Address</label>
                        <div class="relative">
                            <input type="text" name="address" placeholder="Street, City, State, ZIP"
                                class="w-full pl-5 pr-12 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-[#A8440B] focus:ring-4 focus:ring-amber-500/5 transition-all outline-none">
                            <svg class="w-5 h-5 absolute right-4 top-3.5 text-slate-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Course Selection</label>
                        <div class="relative">
                            <input type="text" name="course_selection" placeholder="e.g. Advanced UI Design"
                                class="w-full pl-5 pr-12 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-[#A8440B] focus:ring-4 focus:ring-amber-500/5 transition-all outline-none">
                            <svg class="w-5 h-5 absolute right-4 top-3.5 text-slate-300" fill="currentColor" viewBox="0 0 24 24"><path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Reference</label>
                        <div class="relative">
                            <input type="text" name="reference" placeholder="Social Media, Referral..."
                                class="w-full pl-5 pr-12 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-[#A8440B] focus:ring-4 focus:ring-amber-500/5 transition-all outline-none">
                            <svg class="w-5 h-5 absolute right-4 top-3.5 text-slate-300" fill="currentColor" viewBox="0 0 24 24"><path d="M14 11h-4v-2h4v2zm10-4v12c0 1.1-.9 2-2 2H2c-1.1 0-2-.9-2-2V7c0-1.1.9-2 2-2h4V2h8v3h4c1.1 0 2 .9 2 2zm-14-3h4v1H10V4z"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Info Alert -->
                <div class="bg-gradient-to-r from-amber-50 to-emerald-50 border border-amber-100 rounded-2xl p-4 flex items-center justify-center gap-3">
                    <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span class="text-xs font-bold text-amber-800 opacity-80">Information is encrypted and stored securely</span>
                </div>
                
                <div class="pt-6 flex items-center justify-end gap-10">
                    <button type="button" onclick="closeLeadModal()" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition-all">Cancel</button>
                    <button type="submit" id="save-lead-btn" class="px-8 py-3.5 bg-[#A8440B] text-white rounded-xl text-sm font-bold shadow-lg shadow-amber-900/20 hover:translate-y-[-1px] active:scale-95 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
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
        
        <div id="note-modal-content" class="relative w-full max-w-md scale-95 opacity-0 bg-white rounded-3xl shadow-2xl transition-all duration-300 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800">Add Interaction Note</h3>
                <button onclick="closeNoteModal()" class="h-9 w-9 flex items-center justify-center rounded-full hover:bg-slate-50 text-slate-400 hover:text-slate-600 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form id="note-form" onsubmit="saveNote(event)" class="p-8 space-y-5">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Interaction Type / Title</label>
                    <input type="text" name="title" required placeholder="e.g. Phone Consultation"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-[#A8440B] transition-all outline-none">
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Notes / Details</label>
                    <textarea name="note" required rows="4" placeholder="Brief details of the interaction..."
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-[#A8440B] transition-all outline-none resize-none"></textarea>
                </div>
                
                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="closeNoteModal()" class="flex-1 px-6 py-3.5 bg-slate-100 text-slate-600 rounded-2xl text-sm font-bold hover:bg-slate-200 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 px-6 py-3.5 bg-[#A8440B] text-white rounded-2xl text-sm font-bold shadow-lg shadow-amber-900/20 hover:translate-y-[-1px] active:scale-95 transition-all">Add Note</button>
                </div>
            </form>
        </div>
    </div>
</div>
