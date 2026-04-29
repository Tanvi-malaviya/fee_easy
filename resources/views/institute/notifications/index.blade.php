@extends('layouts.institute')

@section('content')
<div class="space-y-6 max-w-[1000px] mx-auto pb-12 pt-4">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-[550] text-slate-800 tracking-tight leading-tight">Notifications</h1>
            <p class="text-xs text-slate-400 mt-1 font-medium leading-relaxed">Stay updated with your digital campus activities and milestones.</p>
        </div>

    </div>

    <!-- Notifications List -->
    <div id="notifications-list" class="space-y-4">
        <!-- Data populated via AJAX -->
        <div class="col-span-full py-20 text-center text-slate-300 italic text-xs">Loading notifications...</div>
    </div>
    

</div>

<script>
    async function fetchNotifications() {
        try {
            const response = await fetch("/api/v1/institute/notifications", { 
                headers: { 
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                } 
            });
            const result = await response.json();
            if (result.status === 'success') {
                renderNotifications(result.data);
            }
        } catch (error) { 
            console.error(error);
            showToast('Load error', 'error'); 
        }
    }

    function renderNotifications(notifs) {
        const container = document.getElementById('notifications-list');
        if (!notifs || notifs.length === 0) {
            container.innerHTML = `
                <div class="p-20 text-center text-slate-400 italic bg-white rounded-[2rem] border border-slate-100 shadow-sm">
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
                'orange': { bg: 'bg-orange-50', text: 'text-orange-500', border: 'border-orange-500' },
                'emerald': { bg: 'bg-emerald-50', text: 'text-emerald-500', border: 'border-emerald-500' },
                'sky': { bg: 'bg-sky-50', text: 'text-sky-500', border: 'border-sky-500' },
                'rose': { bg: 'bg-rose-50', text: 'text-rose-500', border: 'border-rose-500' },
                'slate': { bg: 'bg-slate-50', text: 'text-slate-500', border: 'border-slate-500' }
            }[color] || { bg: 'bg-slate-50', text: 'text-slate-500', border: 'border-slate-100' };

            const timeString = formatTime(n.created_at);

            return `
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-start gap-4 transition-all hover:shadow-md ${!n.is_read ? 'border-l-[5px] ' + colorClasses.border : ''}">
                <div class="h-10 w-10 ${colorClasses.bg} ${colorClasses.text} rounded-full flex items-center justify-center shrink-0 shadow-sm">
                    ${icon}
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-800 leading-tight">${n.title}</h3>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider bg-slate-50 px-2 py-1 rounded-md border border-slate-100">${timeString}</span>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1 leading-relaxed font-medium">${n.message}</p>
                    
                    ${titleLower.includes('expire') ? `
                        <div class="mt-4 flex items-center gap-3">
                            <button class="px-4 py-1.5 bg-[#ff6c00] hover:bg-[#e05f00] text-white rounded-xl font-bold text-[10px] shadow-md shadow-orange-500/10 hover:scale-[1.02] transition-all">
                                Renew Now
                            </button>
                            <button class="px-3 py-1.5 text-slate-400 hover:text-slate-600 font-bold text-[10px] transition-colors">
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
            const response = await fetch("/api/v1/institute/notifications/mark-all-read", { 
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
