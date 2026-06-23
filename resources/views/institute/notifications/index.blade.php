@extends('layouts.institute')

@section('content')
<div class="space-y-6 max-w-[1250px] mx-auto pb-12 pt-4">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold text-slate-800 tracking-tight">Notifications</h1>
            <p class="text-xs text-slate-400 mt-0.5 font-medium">Stay updated with your digital campus activities and milestones.</p>
        </div>

    </div>

    <!-- Notifications List -->
    <div id="notifications-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Data populated via AJAX -->
        <div class="col-span-full py-20 text-center text-slate-300 italic text-xs">Loading notifications...</div>
    </div>
    

</div>

<script>
    async function fetchNotifications() {
        try {
            const response = await fetch('{{ url("/api/v1/institute/notifications") }}', { 
                headers: { 
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                } 
            });
            const result = await response.json();
            if (result.status === 'success') {
                renderNotifications(result.data);
                const hasUnread = result.data.some(n => !n.is_read);
                if (hasUnread) {
                    markAllAsReadSilently();
                }
            }
        } catch (error) { 
            console.error(error);
            showToast('Load error', 'error'); 
        }
    }

    async function markAllAsReadSilently() {
        try {
            const response = await fetch('{{ url("/api/v1/institute/notifications/mark-all-read") }}', { 
                method: 'POST',
                headers: { 
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                } 
            });
            const result = await response.json();
            if (result.status === 'success') {
                const dot = document.getElementById('notif-dot');
                if (dot) {
                    dot.classList.add('hidden');
                }
            }
        } catch (error) { 
            console.error(error);
        }
    }

    function renderNotifications(notifs) {
        const container = document.getElementById('notifications-list');
        if (!notifs || notifs.length === 0) {
            container.innerHTML = `
                <div class="col-span-full p-20 text-center text-slate-400 italic bg-white rounded-[2rem] border border-slate-100 shadow-sm">
                    No notifications yet.
                </div>`;
            return;
        }

        container.innerHTML = notifs.map(n => {
            // Map types to icons and colors
            let icon = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>`;
            let color = 'slate';
            
            const titleLower = n.title.toLowerCase();
            if (titleLower.includes('subscription') || titleLower.includes('expire') || titleLower.includes('plan')) {
                color = 'orange';
                icon = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>`;
            } else if (titleLower.includes('fee') || titleLower.includes('payment') || titleLower.includes('invoice')) {
                color = 'emerald';
                icon = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;
            } else if (titleLower.includes('system') || titleLower.includes('update') || titleLower.includes('maintenance')) {
                color = 'sky';
                icon = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;
            } else if (titleLower.includes('feature') || titleLower.includes('new') || titleLower.includes('ai')) {
                color = 'rose';
                icon = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>`;
            }

            const colorClasses = {
                'orange': {
                    gradient: 'bg-gradient-to-tr from-orange-50 to-orange-100/50 border border-orange-200/40 text-orange-600',
                    borderTop: 'border-t-orange-500',
                    dot: 'bg-orange-500',
                    badge: 'bg-orange-50 text-orange-600 border-orange-100'
                },
                'emerald': {
                    gradient: 'bg-gradient-to-tr from-emerald-50 to-emerald-100/50 border border-emerald-200/40 text-emerald-600',
                    borderTop: 'border-t-emerald-500',
                    dot: 'bg-emerald-500',
                    badge: 'bg-emerald-50 text-emerald-600 border-emerald-100'
                },
                'sky': {
                    gradient: 'bg-gradient-to-tr from-sky-50 to-sky-100/50 border border-sky-200/40 text-sky-600',
                    borderTop: 'border-t-sky-500',
                    dot: 'bg-sky-500',
                    badge: 'bg-sky-50 text-sky-600 border-sky-100'
                },
                'rose': {
                    gradient: 'bg-gradient-to-tr from-rose-50 to-rose-100/50 border border-rose-200/40 text-rose-600',
                    borderTop: 'border-t-rose-500',
                    dot: 'bg-rose-500',
                    badge: 'bg-rose-50 text-rose-600 border-rose-100'
                },
                'slate': {
                    gradient: 'bg-gradient-to-tr from-slate-50 to-slate-100/50 border border-slate-200/40 text-slate-600',
                    borderTop: 'border-t-slate-400',
                    dot: 'bg-slate-400',
                    badge: 'bg-slate-50 text-slate-500 border-slate-100'
                }
            }[color] || {
                gradient: 'bg-gradient-to-tr from-slate-50 to-slate-100/50 border border-slate-200/40 text-slate-600',
                borderTop: 'border-t-slate-400',
                dot: 'bg-slate-400',
                badge: 'bg-slate-50 text-slate-500 border-slate-100'
            };

            const timeString = formatTime(n.created_at);

            return `
            <div class="group bg-gradient-to-b from-white to-slate-50/10 p-5 rounded-2xl border border-slate-100/90 shadow-sm flex flex-col justify-between h-full transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_24px_-8px_rgba(0,0,0,0.05)] relative ${!n.is_read ? 'border-t-4 ' + colorClasses.borderTop : ''}">
                <div class="flex-1">
                    <!-- Card Top Info -->
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <div class="h-9 w-9 ${colorClasses.gradient} rounded-xl flex items-center justify-center shrink-0 shadow-sm transition-transform duration-300 group-hover:scale-105">
                            ${icon}
                        </div>
                        <span class="text-[8px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md border shrink-0 ${colorClasses.badge}">${timeString}</span>
                    </div>

                    <!-- Title & Unread Indicator -->
                    <div class="flex items-center gap-1.5 min-w-0">
                        <h3 class="text-[13px] font-bold text-slate-800 leading-snug truncate" title="${n.title}">${n.title}</h3>
                        ${!n.is_read ? `<span class="h-1.5 w-1.5 rounded-full ${colorClasses.dot} animate-pulse shrink-0"></span>` : ''}
                    </div>

                    <!-- Message Body (Line clamped for grid alignment) -->
                    <p class="text-[11px] text-slate-500 mt-2 leading-relaxed font-medium line-clamp-3" title="${n.message}">${n.message}</p>
                </div>

                <!-- Footer Actions -->
                <div class="mt-4 shrink-0">
                    ${n.image ? `
                        <div class="mt-2">
                            <a href="{{ asset('storage') }}/${n.image}" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 w-full bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-800 rounded-lg text-[9px] font-bold transition-all border border-slate-200/50 shadow-xs">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View File
                            </a>
                        </div>
                    ` : ''}
                    
                    ${titleLower.includes('expire') ? `
                        <div class="mt-3 flex flex-col gap-1 w-full">
                            <button class="w-full py-1.5 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white rounded-lg font-bold text-[9.5px] tracking-wide shadow-sm hover:scale-[1.01] transition-all">
                                Renew Now
                            </button>
                            <button class="w-full py-1 text-slate-400 hover:text-slate-500 font-bold text-[9px] tracking-wide transition-colors">
                                Dismiss
                            </button>
                        </div>
                    ` : ''}
                </div>
            </div>
            `;
        }).join('');
    }

    function formatTime(dateStr) {
        const date = new Date(dateStr);
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMins / 60);
        const diffDays = Math.floor(diffHours / 24);

        if (diffMins < 60) return `${diffMins} MINS AGO`;
        if (diffHours < 24) return `${diffHours} HOURS AGO`;
        if (diffDays === 1) return 'YESTERDAY';
        return `${diffDays} DAYS AGO`;
    }

    async function markAllAsRead() {
        try {
            const response = await fetch('{{ url("/api/v1/institute/notifications/mark-all-read") }}', { 
                method: 'POST',
                headers: { 
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                } 
            });
            const result = await response.json();
            if (result.status === 'success') {
                showToast(result.message || 'All notifications marked as read', 'success');
                fetchNotifications();
            }
        } catch (error) { 
            console.error(error);
            showToast('Action failed', 'error'); 
        }
    }

    document.addEventListener('DOMContentLoaded', fetchNotifications);
</script>
@endsection
