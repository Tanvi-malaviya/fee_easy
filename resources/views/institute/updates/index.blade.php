@extends('layouts.institute')

@section('content')
<div class="space-y-2 max-w-[1600px] mx-auto pb-6">
    <!-- Toast Notifications Container -->
    <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

    <!-- Page Header & Info -->
    <div class="p-5 pb-0 md:p-4 md:pb-0 animate-in fade-in duration-500">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex-1">
                <span class="text-[9px] font-bold text-[#ff6c00] uppercase tracking-wider block">Communication Center</span>
                <h1 class="text-2xl font-[550] text-slate-800 tracking-tight leading-tight mt-0.5">Daily Updates Feed</h1>
                <p class="text-xs text-slate-400 mt-1 font-medium leading-relaxed max-w-xl">Post announcements, academic milestones, critical homework tasks, or general classroom notices securely.</p>
            </div>
            <div class="flex items-center gap-4">
                <button onclick="openUpdateModal()" class="px-5 py-2.5 bg-[#ff6c00] hover:bg-[#e05f00] text-white rounded-xl font-bold text-[11px] shadow-md shadow-orange-500/10 hover:scale-[1.02] active:scale-95 transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Create New Update
                </button>
            </div>
        </div>
        
        <!-- <div class="bg-slate-50/50 p-3 md:p-4 rounded-xl border border-slate-100">
            <div class="flex items-start gap-3">
                <div class="h-7 w-7 bg-white rounded-lg shadow-sm border border-slate-100 flex items-center justify-center text-[#ff6c00] shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h4 class="text-[10px] font-black text-slate-800 uppercase tracking-wider mb-0.5">Quick Guide</h4>
                    <p class="text-[10px] text-slate-500 leading-relaxed font-medium">Keep everyone informed regarding upcoming schedules, important events, and syllabus progression. Direct channels minimize delays.</p>
                </div>
            </div>
        </div> -->
    </div>

    <!-- Feed Section -->
    <div>
        
        <div id="update-feed" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Data populated via AJAX -->
            <div class="col-span-full py-10 text-center text-slate-300 italic text-xs">Loading feed...</div>
        </div>
    </div>
</div>

<!-- Add Update Modal -->
<div id="update-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden p-4">
    <div onclick="closeUpdateModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="bg-white w-full max-w-3xl rounded-[1.5rem] shadow-2xl relative z-10 flex flex-col overflow-hidden animate-in fade-in zoom-in duration-300">
        <!-- Modal Header (Fixed) -->
        <div class="p-5 border-b border-slate-50 flex items-center justify-between bg-slate-50/20">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Post New Update</h2>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Communication Hub</p>
            </div>
            <button onclick="closeUpdateModal()" class="h-8 w-8 bg-white border border-slate-100 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-500 shadow-sm transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-6">
            <form id="update-form" class="space-y-4">
                <!-- Audience & Category Section -->
                <div class="grid grid-cols-3 gap-3">
                    <div class="space-y-1">
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Recipient</label>
                        <select name="recipient" id="recipient-select" onchange="handleTargetChange()" required class="w-full px-3 py-2.5 bg-blue-50/50 border border-blue-100 rounded-xl text-xs font-bold text-blue-700 outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="students">Students</option>
                            <option value="parents">Parents</option>
                            <option value="both">Both (Students & Parents)</option>
                        </select>
                    </div>
                    
                    <div id="student-audience-container" class="space-y-1">
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Target Audience</label>
                        <select name="target_type" id="target-type-select" onchange="handleTargetChange()" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold outline-none">
                            <option value="all">All Students</option>
                            <option value="batch">Specific Batch</option>
                            <!-- <option value="standard">Specific Standard</option> -->
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Category</label>
                        <select name="category" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold outline-none">
                            <option value="Academic">Academic</option>
                            <option value="Administrative">Administrative</option>
                            <option value="Emergency">Emergency</option>
                            <option value="Event">Event</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <!-- Detail Row -->
                <div id="audience-detail-row" class="grid grid-cols-3 gap-3">
                    <div id="target-detail-container" class="col-span-1">
                        <div id="batch-selector-container" class="space-y-1 hidden animate-in slide-in-from-top-1">
                            <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Select Batch</label>
                            <select name="batch_id" id="modal-batch-select" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold outline-none">
                                <option value="">Choose Batch...</option>
                            </select>
                        </div>

                        <div id="standard-selector-container" class="space-y-1 hidden animate-in slide-in-from-top-1">
                            <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Select Standard</label>
                            <select name="standard" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold outline-none">
                                <option value="">Choose Standard...</option>
                                @for($i=1; $i<=12; $i++)
                                    <option value="{{ $i }}{{ in_array($i, [1,2,3]) ? (['st','nd','rd'][$i-1]) : 'th' }}">{{ $i }}{{ in_array($i, [1,2,3]) ? (['st','nd','rd'][$i-1]) : 'th' }} Standard</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div id="all-students-placeholder" class="space-y-1 opacity-50">
                            <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Selection Details</label>
                            <div id="placeholder-text" class="w-full px-3 py-2.5 bg-slate-100/50 border border-slate-100 rounded-xl text-[10px] font-bold text-slate-400 truncate">Everyone</div>
                        </div>
                    </div>

                    <div class="space-y-1 col-span-1">
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Subject</label>
                        <input type="text" name="topic" required placeholder="Main title" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold outline-none">
                    </div>
                    
                    <div class="space-y-1 col-span-1">
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Attachment (Image/PDF)</label>
                        <input type="file" name="attachment" accept="image/*,application/pdf" class="w-full px-3 py-2.1 bg-slate-50 border border-dashed border-slate-200 rounded-xl text-[10px] font-bold outline-none file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-[9px] file:font-black file:bg-blue-50 file:text-blue-700">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Message Content</label>
                    <textarea name="description" required rows="3" placeholder="Write details here..." class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold outline-none resize-none"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeUpdateModal()" class="px-6 py-2.5 text-xs font-bold text-slate-400 hover:text-slate-600">Cancel</button>
                    <button type="submit" id="submit-btn" class="px-8 py-2.5 bg-[#ff6c00] hover:bg-[#e05f00] text-white rounded-xl font-bold text-xs shadow-md shadow-orange-500/10 hover:scale-[1.02] active:scale-95 transition-all">
                        <span id="btn-text">Publish Update</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>

<!-- View Update Modal -->
<div id="view-modal" class="fixed inset-0 z-[110] flex items-center justify-center hidden p-4">
    <div onclick="closeViewModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="bg-white w-full max-w-2xl rounded-[2rem] shadow-2xl relative z-10 overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="p-5 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
            <div class="flex items-center gap-3">
                <div id="view-cat-icon" class="h-9 w-9 rounded-xl flex items-center justify-center"></div>
                <div>
                    <h2 id="view-topic" class="text-[15px] font-black text-slate-800 leading-tight"></h2>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span id="view-category" class="text-[8px] font-black uppercase tracking-widest px-1.5 py-0.5 rounded"></span>
                        <span class="text-[8px] font-bold text-slate-300">•</span>
                        <span id="view-date" class="text-[8px] font-bold text-slate-400 uppercase tracking-widest"></span>
                    </div>
                </div>
            </div>
            <button onclick="closeViewModal()" class="h-8 w-8 bg-white border border-slate-100 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-500 shadow-sm transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Target Audience:</span>
                <span id="view-target" class="text-[9px] font-black text-[#1e3a8a] bg-blue-50 px-2 py-0.5 rounded-md uppercase tracking-wider"></span>
            </div>
            
            <div class="prose prose-slate max-w-none">
                <p id="view-description" class="text-[13px] text-slate-600 leading-relaxed font-medium whitespace-pre-wrap"></p>
            </div>

            <div id="view-attachment-container" class="mt-6 pt-4 border-t border-slate-50 hidden">
                <a id="view-attachment-link" href="#" target="_blank" class="inline-flex items-center gap-3 p-2.5 bg-slate-50 border border-slate-100 rounded-xl hover:bg-blue-50 hover:border-blue-100 transition-all group w-full">
                    <div class="h-8 w-8 bg-white rounded-lg flex items-center justify-center text-blue-600 shadow-sm group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.414a6 6 0 108.486 8.486L20.5 13"/></svg>
                    </div>
                    <div>
                        <span class="text-[11px] font-black text-slate-700 block">View File Attachment</span>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mt-0.5">Click to open</span>
                    </div>
                </a>
            </div>
        </div>
        <div class="p-4 bg-slate-50/30 border-t border-slate-50 flex justify-end">
            <button onclick="closeViewModal()" class="px-6 py-2 bg-slate-800 text-white rounded-xl font-bold text-[11px] shadow-lg shadow-slate-900/10 hover:scale-[1.02] active:scale-95 transition-all">
                Close Details
            </button>
        </div>
    </div>
</div>

<script>
    const CSRF_TOKEN = "{{ csrf_token() }}";
    
    document.addEventListener('DOMContentLoaded', () => {
        fetchBatches();
        fetchUpdates();
    });

    function handleTargetChange() {
        const recipient = document.getElementById('recipient-select').value;
        const type = document.getElementById('target-type-select').value;
        
        const audienceCont = document.getElementById('student-audience-container');
        const batchCont = document.getElementById('batch-selector-container');
        const standardCont = document.getElementById('standard-selector-container');
        const allPlaceholder = document.getElementById('all-students-placeholder');
        const placeholderText = document.getElementById('placeholder-text');
        
        // Handle Students vs Parents vs Both
        if (recipient === 'parents') {
            audienceCont.style.opacity = '0.3';
            audienceCont.style.pointerEvents = 'none';
            batchCont.classList.add('hidden');
            standardCont.classList.add('hidden');
            allPlaceholder.classList.remove('hidden');
            placeholderText.innerText = "Broadcasting to all Parents";
        } else if (recipient === 'both') {
            audienceCont.style.opacity = '1';
            audienceCont.style.pointerEvents = 'auto';
            
            batchCont.classList.toggle('hidden', type !== 'batch');
            standardCont.classList.toggle('hidden', type !== 'standard');
            allPlaceholder.classList.toggle('hidden', type !== 'all');
            placeholderText.innerText = "Broadcasting to both Students & Parents";
        } else {
            audienceCont.style.opacity = '1';
            audienceCont.style.pointerEvents = 'auto';
            
            // Handle Students internal targeting
            batchCont.classList.toggle('hidden', type !== 'batch');
            standardCont.classList.toggle('hidden', type !== 'standard');
            allPlaceholder.classList.toggle('hidden', type !== 'all');
            placeholderText.innerText = "Broadcasting to all Students";
        }
        
        document.getElementById('modal-batch-select').required = ((recipient === 'students' || recipient === 'both') && type === 'batch');
    }

    async function fetchBatches() {
        try {
            const response = await fetch("/api/v1/institute/batches", { headers: { 'Accept': 'application/json' } });
            const result = await response.json();
            if (result.status === 'success') {
                const sel = document.getElementById('modal-batch-select');
                result.data.items.forEach(batch => {
                    const opt = document.createElement('option');
                    opt.value = batch.id;
                    opt.innerText = batch.name;
                    sel.appendChild(opt);
                });
            }
        } catch (error) { console.error('Failed to sync batches'); }
    }

    async function fetchUpdates() {
        try {
            const response = await fetch("/api/v1/institute/daily-updates", { headers: { 'Accept': 'application/json' } });
            const result = await response.json();
            if (result.status === 'success') {
                renderUpdates(result.data);
            }
        } catch (error) { showToast('Load error', 'error'); }
    }

    function renderUpdates(updates) {
        const container = document.getElementById('update-feed');
        if (updates.length === 0) {
            container.innerHTML = `<div class="p-20 text-center text-slate-400 italic bg-white rounded-[2.5rem] border border-slate-100">No updates posted yet.</div>`;
            return;
        }

        container.innerHTML = updates.map(update => {
            const catColors = {
                'Academic': 'blue',
                'Administrative': 'indigo',
                'Emergency': 'rose',
                'Event': 'amber',
                'Other': 'slate'
            };
            const color = catColors[update.category] || 'slate';
            const updateJson = JSON.stringify(update).replace(/"/g, '&quot;');
            
            return `
            <div onclick="viewUpdateDetails('${updateJson}')" class="bg-white p-5 ml-4 rounded-xl border border-slate-100 shadow-sm hover:shadow-md hover:border-[#ff6c00]/30 cursor-pointer transition-all duration-300 flex flex-col h-full group">
                <div class="flex items-center justify-between mb-3">
                    <div class="h-8 w-8 bg-${color}-50 rounded-xl flex items-center justify-center text-${color}-600 shrink-0 group-hover:scale-105 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">${update.date || 'Today'}</span>
                </div>
                
                <div class="mb-3">
                    <div class="flex items-center gap-2 mb-0.5">
                        <h4 class="text-sm font-bold text-slate-800 leading-tight truncate group-hover:text-[#ff6c00] transition-colors">${update.topic || update.category || 'General Notice'}</h4>
                    </div>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">
                        Recipient: <span class="text-[#ff6c00] font-extrabold">${update.recipient === 'both' ? 'Both' : update.recipient}</span> • Target: <span class="text-slate-600 font-extrabold">${update.target_type === 'all' ? 'Everyone' : (update.batch ? update.batch.name : (update.standard ? update.standard + ' Standard' : 'Unknown'))}</span>
                    </span>
                </div>

                <p class="text-[11px] text-slate-500 leading-relaxed font-medium mb-4 flex-1 line-clamp-2">${update.description}</p>
                
                ${update.attachment ? `
                    <div class="mt-auto pt-3 border-t border-slate-50 flex items-center justify-between text-[#ff6c00]">
                        <span class="text-[9px] font-extrabold uppercase tracking-wider">Has Attachment</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.414a6 6 0 108.486 8.486L20.5 13"/></svg>
                    </div>
                ` : `
                    <div class="mt-auto pt-3 border-t border-slate-50 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-between text-slate-400">
                         <span class="text-[9px] font-bold uppercase tracking-wider">Click to expand</span>
                         <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </div>
                `}
            </div>
            `;
        }).join('');
    }

    document.getElementById('update-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const f = new FormData(e.target);
        const btn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        
        btn.disabled = true;
        btnText.innerText = 'Publishing...';
        
        try {
            const response = await fetch("/api/v1/institute/daily-updates", {
                method: 'POST',
                headers: { 
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN 
                },
                body: f
            });

            const textResponse = await response.text();
            let result;
            try {
                result = JSON.parse(textResponse);
            } catch(e) {
                console.error("Non-JSON response:", textResponse);
                showToast('Server error or invalid response format.', 'error');
                return;
            }

            if (response.ok && result.status === 'success') {
                showToast(result.message || 'Update published successfully!', 'success');
                closeUpdateModal();
                fetchUpdates();
                e.target.reset();
                handleTargetChange();
            } else {
                showToast(result.message || 'Validation failed. Check inputs.', 'error');
            }
        } catch (error) { 
            console.error(error);
            showToast('Connection error. Please try again.', 'error'); 
        } finally {
            btn.disabled = false;
            btnText.innerText = 'Publish Update';
        }
    });

    function viewUpdateDetails(updateStr) {
        const update = JSON.parse(updateStr);
        const modal = document.getElementById('view-modal');
        const catColors = { 'Academic': 'blue', 'Administrative': 'indigo', 'Emergency': 'rose', 'Event': 'amber', 'Other': 'slate' };
        const color = catColors[update.category] || 'slate';

        const iconSvg = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>`;

        document.getElementById('view-topic').innerText = update.topic || update.category || 'General Notice';
        document.getElementById('view-category').innerText = update.category || 'Update';
        document.getElementById('view-category').className = `text-[8px] font-black uppercase tracking-widest px-1.5 py-0.5 rounded bg-${color}-50 text-${color}-600`;
        document.getElementById('view-cat-icon').className = `h-9 w-9 rounded-xl flex items-center justify-center bg-${color}-50 text-${color}-600`;
        document.getElementById('view-cat-icon').innerHTML = iconSvg;
        
        document.getElementById('view-date').innerText = update.date || 'Today';
        document.getElementById('view-description').innerText = update.description;
        document.getElementById('view-target').innerText = update.target_type === 'all' ? 'Everyone' : (update.batch ? update.batch.name : (update.standard ? update.standard + ' Standard' : 'Unknown'));

        const attachCont = document.getElementById('view-attachment-container');
        if (update.attachment) {
            attachCont.classList.remove('hidden');
            document.getElementById('view-attachment-link').href = update.attachment;
        } else {
            attachCont.classList.add('hidden');
        }

        modal.classList.remove('hidden');
    }

    function closeViewModal() { document.getElementById('view-modal').classList.add('hidden'); }
    function openUpdateModal() { document.getElementById('update-modal').classList.remove('hidden'); }
    function closeUpdateModal() { 
        document.getElementById('update-modal').classList.add('hidden');
        document.getElementById('update-form').reset();
        handleTargetChange();
    }
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        const color = type === 'success' ? 'emerald' : 'rose';
        toast.className = `bg-${color}-50 border border-${color}-200 text-${color}-600 px-6 py-4 rounded-2xl shadow-xl flex items-center animate-in slide-in-from-right-10 duration-300`;
        toast.innerHTML = `<span class="text-sm font-bold">${message}</span>`;
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
</script>
@endsection
