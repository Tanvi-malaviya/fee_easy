@extends('layouts.institute')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-12 pt-2">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Send Push Notification</h1>
            <p class="text-xs text-slate-400 mt-0.5 font-medium">Reach students, parents & staff directly on their devices via Firebase.</p>
        </div>
        <a href="{{ route('institute.notifications.index') }}"
            class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition-all">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Back
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- ── Compose Form ── --}}
        <div class="lg:col-span-3 space-y-5">

            {{-- Audience Selector --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 space-y-4">
                <h2 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                    <span class="h-6 w-6 bg-violet-100 text-violet-600 rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </span>
                    Select Audience
                </h2>

                <div class="grid grid-cols-3 gap-2" id="audience-grid">
                    {{-- All Students --}}
                    <button type="button" onclick="selectTarget('all_students')" id="btn-all_students"
                        class="target-btn group flex flex-col items-center gap-2 p-3.5 rounded-xl border-2 border-slate-100 hover:border-blue-300 hover:bg-blue-50 transition-all text-center">
                        <div class="h-9 w-9 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center group-hover:bg-blue-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-slate-700">All Students</p>
                            <p id="count-all_students" class="text-[10px] text-slate-400 font-medium mt-0.5">—</p>
                        </div>
                    </button>

                    {{-- All Parents --}}
                    <button type="button" onclick="selectTarget('all_parents')" id="btn-all_parents"
                        class="target-btn group flex flex-col items-center gap-2 p-3.5 rounded-xl border-2 border-slate-100 hover:border-emerald-300 hover:bg-emerald-50 transition-all text-center">
                        <div class="h-9 w-9 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center group-hover:bg-emerald-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-slate-700">All Parents</p>
                            <p id="count-all_parents" class="text-[10px] text-slate-400 font-medium mt-0.5">—</p>
                        </div>
                    </button>

                    {{-- All Staff --}}
                    <button type="button" onclick="selectTarget('all_staff')" id="btn-all_staff"
                        class="target-btn group flex flex-col items-center gap-2 p-3.5 rounded-xl border-2 border-slate-100 hover:border-amber-300 hover:bg-amber-50 transition-all text-center">
                        <div class="h-9 w-9 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center group-hover:bg-amber-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-slate-700">All Staff</p>
                            <p id="count-all_staff" class="text-[10px] text-slate-400 font-medium mt-0.5">—</p>
                        </div>
                    </button>
                </div>

                <input type="hidden" id="target_type" value="">
            </div>

            {{-- Notification Content --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 space-y-4">
                <h2 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                    <span class="h-6 w-6 bg-orange-100 text-primary rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </span>
                    Compose Message
                </h2>

                {{-- Title --}}
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Notification Title *</label>
                    <input type="text" id="notif-title" placeholder="e.g. Fee Reminder, Exam Schedule, Holiday Notice..."
                        maxlength="100"
                        oninput="updatePreview()"
                        class="w-full px-4 py-3 bg-slate-50 border border-transparent rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary/30 outline-none text-slate-700 font-medium transition-all placeholder:text-slate-300">
                    <div class="flex justify-end mt-1">
                        <span id="title-count" class="text-[9px] text-slate-300 font-bold">0 / 100</span>
                    </div>
                </div>

                {{-- Message --}}
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Message Body *</label>
                    <textarea id="notif-message" rows="4" placeholder="Write your message here..."
                        maxlength="500"
                        oninput="updatePreview()"
                        class="w-full px-4 py-3 bg-slate-50 border border-transparent rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary/30 outline-none text-slate-700 font-medium transition-all placeholder:text-slate-300 resize-none"></textarea>
                    <div class="flex justify-end mt-1">
                        <span id="msg-count" class="text-[9px] text-slate-300 font-bold">0 / 500</span>
                    </div>
                </div>

                {{-- Type --}}
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Notification Type</label>
                    <select id="notif-type"
                        class="w-full px-4 py-3 bg-slate-50 border border-transparent rounded-xl text-sm focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 font-medium transition-all appearance-none">
                        <option value="general">📢 General</option>
                        <option value="fee">💰 Fee Reminder</option>
                        <option value="homework">📚 Homework</option>
                        <option value="attendance">📋 Attendance</option>
                        <option value="exam">📝 Exam</option>
                        <option value="holiday">🎉 Holiday</option>
                        <option value="event">🎭 Event</option>
                        <option value="announcement">📣 Announcement</option>
                    </select>
                </div>
            </div>

            {{-- Send Button --}}
            <button type="button" onclick="sendPushNotification()" id="send-btn"
                class="w-full py-3.5 bg-primary text-white rounded-xl text-sm font-bold shadow-lg shadow-orange-500/20 hover:translate-y-[-1px] hover:shadow-xl hover:shadow-orange-500/30 active:scale-95 transition-all flex items-center justify-center gap-2.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:translate-y-0">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Send Push Notification
            </button>
        </div>

        {{-- ── Preview Panel ── --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Live Preview --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <h2 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                    <span class="h-6 w-6 bg-slate-100 text-slate-500 rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </span>
                    Live Preview
                </h2>

                {{-- Phone mockup --}}
                <div class="bg-slate-900 rounded-[1.75rem] p-3 shadow-2xl relative overflow-hidden mx-auto max-w-[220px]">
                    {{-- Status bar --}}
                    <div class="flex justify-between items-center px-2 py-1 mb-2">
                        <span class="text-white/60 text-[9px] font-bold">9:41</span>
                        <div class="flex gap-1">
                            <div class="w-3 h-1.5 bg-white/60 rounded-sm"></div>
                            <div class="w-4 h-1.5 bg-white/60 rounded-sm"></div>
                        </div>
                    </div>

                    {{-- Notification Card --}}
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-3 space-y-1.5">
                        <div class="flex items-center gap-2">
                            <div class="h-6 w-6 bg-primary rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[9px] text-white/50 font-bold uppercase tracking-wider">Fee Easy</p>
                            </div>
                            <span class="text-[8px] text-white/40 font-medium">now</span>
                        </div>
                        <p id="preview-title" class="text-white text-[12px] font-bold leading-tight">Your notification title</p>
                        <p id="preview-body" class="text-white/70 text-[10px] leading-snug">Your message will appear here...</p>
                    </div>

                    {{-- App icons --}}
                    <div class="grid grid-cols-4 gap-2 mt-4 px-1 opacity-30">
                        @foreach(['bg-blue-500','bg-green-500','bg-yellow-500','bg-red-500','bg-purple-500','bg-pink-500','bg-indigo-500','bg-teal-500'] as $color)
                            <div class="h-10 w-10 {{ $color }} rounded-xl"></div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Device Stats --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <h2 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                    <span class="h-6 w-6 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                    Registered Devices
                </h2>

                <div id="device-stats" class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-slate-50">
                        <div class="flex items-center gap-2.5">
                            <div class="h-7 w-7 bg-blue-50 text-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <span class="text-xs font-bold text-slate-600">Students</span>
                        </div>
                        <div class="text-right">
                            <p id="stat-students" class="text-sm font-bold text-slate-800">—</p>
                            <p class="text-[9px] text-slate-400">with app</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between py-2 border-b border-slate-50">
                        <div class="flex items-center gap-2.5">
                            <div class="h-7 w-7 bg-emerald-50 text-emerald-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            </div>
                            <span class="text-xs font-bold text-slate-600">Parents</span>
                        </div>
                        <div class="text-right">
                            <p id="stat-parents" class="text-sm font-bold text-slate-800">—</p>
                            <p class="text-[9px] text-slate-400">with app</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center gap-2.5">
                            <div class="h-7 w-7 bg-amber-50 text-amber-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="text-xs font-bold text-slate-600">Staff</span>
                        </div>
                        <div class="text-right">
                            <p id="stat-staff" class="text-sm font-bold text-slate-800">—</p>
                            <p class="text-[9px] text-slate-400">with app</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tips --}}
            <div class="bg-gradient-to-br from-orange-50 to-orange-100/50 rounded-2xl border border-orange-100 p-4 space-y-2">
                <p class="text-[10px] font-bold text-orange-600 uppercase tracking-wider">💡 Tips</p>
                <ul class="space-y-1.5 text-[11px] text-orange-700 font-medium leading-relaxed">
                    <li>• Keep titles short & impactful (under 50 chars)</li>
                    <li>• Only users with the mobile app installed will receive push notifications</li>
                    <li>• Failed sends = device token expired or app uninstalled</li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Result Modal --}}
<div id="result-modal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[300] hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-sm shadow-2xl p-8 text-center scale-95 opacity-0 transition-all duration-300" id="result-modal-content">
        <div id="result-icon" class="h-16 w-16 rounded-full flex items-center justify-center mx-auto mb-4"></div>
        <h3 id="result-title" class="text-lg font-bold text-slate-800 mb-2"></h3>
        <p id="result-message" class="text-sm text-slate-500 leading-relaxed mb-6"></p>
        <button onclick="closeResultModal()"
            class="w-full py-3 bg-primary text-white rounded-xl text-sm font-bold hover:translate-y-[-1px] transition-all shadow-md shadow-orange-500/10">
            Done
        </button>
    </div>
</div>

@push('scripts')
<script>
    const CSRF_TOKEN = '{{ csrf_token() }}';
    let selectedTarget = '';
    let deviceStats = null;

    // ── Target selection ────────────────────────────────────────────────────
    const colorMap = {
        all_students:  { border: 'border-blue-400',   bg: 'bg-blue-50',   icon: 'bg-blue-100 text-blue-600' },
        all_parents:   { border: 'border-emerald-400', bg: 'bg-emerald-50', icon: 'bg-emerald-100 text-emerald-600' },
        all_staff:     { border: 'border-amber-400',   bg: 'bg-amber-50',   icon: 'bg-amber-100 text-amber-600' },
    };

    function selectTarget(type) {
        selectedTarget = type;
        document.getElementById('target_type').value = type;

        document.querySelectorAll('.target-btn').forEach(btn => {
            btn.classList.remove('border-blue-400', 'border-emerald-400', 'border-amber-400',
                'bg-blue-50', 'bg-emerald-50', 'bg-amber-50', 'ring-2', 'ring-offset-1',
                'ring-blue-300', 'ring-emerald-300', 'ring-amber-300');
            btn.classList.add('border-slate-100');
        });

        const c = colorMap[type];
        const activeBtn = document.getElementById('btn-' + type);
        activeBtn.classList.remove('border-slate-100');
        activeBtn.classList.add(c.border, c.bg, 'ring-2', 'ring-offset-1',
            c.border.replace('border-', 'ring-'));
    }

    // ── Character counters ───────────────────────────────────────────────────
    document.getElementById('notif-title').addEventListener('input', function () {
        document.getElementById('title-count').textContent = this.value.length + ' / 100';
    });
    document.getElementById('notif-message').addEventListener('input', function () {
        document.getElementById('msg-count').textContent = this.value.length + ' / 500';
    });

    // ── Live preview ─────────────────────────────────────────────────────────
    function updatePreview() {
        const title = document.getElementById('notif-title').value;
        const body  = document.getElementById('notif-message').value;
        document.getElementById('preview-title').textContent = title || 'Your notification title';
        document.getElementById('preview-body').textContent  = body  || 'Your message will appear here...';
    }

    // ── Fetch device stats ───────────────────────────────────────────────────
    async function fetchDeviceStats() {
        try {
            const res    = await fetch('/api/v1/institute/notifications/recipient-stats', {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
            });
            const result = await res.json();
            if (result.status === 'success') {
                deviceStats = result.data;
                const s = result.data.students;
                const p = result.data.parents;
                const st = result.data.staff;

                document.getElementById('stat-students').textContent = `${s.with_fcm} / ${s.total}`;
                document.getElementById('stat-parents').textContent  = `${p.with_fcm} / ${p.total}`;
                document.getElementById('stat-staff').textContent    = `${st.with_fcm} / ${st.total}`;

                document.getElementById('count-all_students').textContent = `${s.with_fcm} devices`;
                document.getElementById('count-all_parents').textContent  = `${p.with_fcm} devices`;
                document.getElementById('count-all_staff').textContent    = `${st.with_fcm} devices`;
            }
        } catch (e) {
            console.error('Stats fetch failed:', e);
        }
    }

    // ── Send push notification ────────────────────────────────────────────────
    async function sendPushNotification() {
        const title   = document.getElementById('notif-title').value.trim();
        const message = document.getElementById('notif-message').value.trim();
        const type    = document.getElementById('notif-type').value;

        if (!selectedTarget) {
            showToast('Please select an audience first', 'error');
            return;
        }
        if (!title) {
            showToast('Please enter a notification title', 'error');
            document.getElementById('notif-title').focus();
            return;
        }
        if (!message) {
            showToast('Please enter a message body', 'error');
            document.getElementById('notif-message').focus();
            return;
        }

        const btn = document.getElementById('send-btn');
        btn.disabled = true;
        btn.innerHTML = `
            <div class="h-4 w-4 border-2 border-white/40 border-t-white rounded-full animate-spin"></div>
            Sending...
        `;

        try {
            const res = await fetch('/api/v1/institute/notifications/send-push', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({
                    title,
                    message,
                    target_type: selectedTarget,
                    type
                })
            });

            const result = await res.json();

            if (result.status === 'success') {
                showResultModal('success', '🎉 Sent!', result.message, result.data);
                // Reset form
                document.getElementById('notif-title').value   = '';
                document.getElementById('notif-message').value = '';
                document.getElementById('title-count').textContent = '0 / 100';
                document.getElementById('msg-count').textContent   = '0 / 500';
                updatePreview();
                selectedTarget = '';
                document.querySelectorAll('.target-btn').forEach(btn => {
                    btn.classList.remove('border-blue-400','border-emerald-400','border-amber-400',
                        'bg-blue-50','bg-emerald-50','bg-amber-50','ring-2','ring-offset-1',
                        'ring-blue-300','ring-emerald-300','ring-amber-300');
                    btn.classList.add('border-slate-100');
                });
            } else {
                showResultModal('error', '❌ Failed', result.message || 'Something went wrong.', null);
            }
        } catch (e) {
            console.error(e);
            showResultModal('error', '❌ Error', 'Network error. Please try again.', null);
        } finally {
            btn.disabled = false;
            btn.innerHTML = `
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Send Push Notification
            `;
        }
    }

    // ── Result Modal ─────────────────────────────────────────────────────────
    function showResultModal(type, title, message, data) {
        const modal   = document.getElementById('result-modal');
        const content = document.getElementById('result-modal-content');
        const icon    = document.getElementById('result-icon');

        document.getElementById('result-title').textContent = title;

        let fullMsg = message;
        if (data) {
            fullMsg += ` (${data.sent} sent, ${data.failed} failed of ${data.total} total)`;
        }
        document.getElementById('result-message').textContent = fullMsg;

        if (type === 'success') {
            icon.className = 'h-16 w-16 rounded-full bg-green-100 text-green-500 flex items-center justify-center mx-auto mb-4';
            icon.innerHTML = `<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;
        } else {
            icon.className = 'h-16 w-16 rounded-full bg-red-100 text-red-500 flex items-center justify-center mx-auto mb-4';
            icon.innerHTML = `<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeResultModal() {
        const modal   = document.getElementById('result-modal');
        const content = document.getElementById('result-modal-content');
        content.classList.add('scale-95', 'opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    // ── Init ─────────────────────────────────────────────────────────────────
    fetchDeviceStats();
</script>
@endpush
@endsection
