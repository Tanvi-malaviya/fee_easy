@extends('layouts.institute')

@section('content')
<div class="max-w-7xl mx-auto mt-7">
   

    <!-- Module Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        <!-- Updates -->
        <a href="{{ route('institute.updates.index') }}" class="group bg-white rounded-2xl border border-slate-100 shadow-sm p-6 transition-all hover:shadow-md relative overflow-hidden border-t-4 border-t-teal-500">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div class="h-12 w-12 bg-teal-50 text-teal-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    </div>
                    <svg class="w-4 h-4 text-slate-200 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 tracking-tight mb-1">Updates</h3>
                <p class="text-xs text-slate-400 font-medium">News & notifications</p>
            </div>
        </a>

        <!-- Chats -->
        <a href="#" class="group bg-white rounded-2xl border border-slate-100 shadow-sm p-6 transition-all hover:shadow-md relative overflow-hidden border-t-4 border-t-orange-700">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div class="h-12 w-12 bg-orange-50 text-orange-700 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                    <svg class="w-4 h-4 text-slate-200 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 tracking-tight mb-1">Chats</h3>
                <p class="text-xs text-slate-400 font-medium">Instant messaging</p>
            </div>
        </a>

        <!-- Community -->
        <a href="#" class="group bg-white rounded-2xl border border-slate-100 shadow-sm p-6 transition-all hover:shadow-md relative overflow-hidden border-t-4 border-t-orange-500">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div class="h-12 w-12 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <svg class="w-4 h-4 text-slate-200 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 tracking-tight mb-1">Community</h3>
                <p class="text-xs text-slate-400 font-medium">Connect & share</p>
            </div>
        </a>

        <!-- Fees -->
        <a href="{{ route('institute.fees.index') }}" class="group bg-white rounded-2xl border border-slate-100 shadow-sm p-6 transition-all hover:shadow-md relative overflow-hidden border-t-4 border-t-emerald-500">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div class="h-12 w-12 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <svg class="w-4 h-4 text-slate-200 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 tracking-tight mb-1">Fees</h3>
                <p class="text-xs text-slate-400 font-medium">Payment collection</p>
            </div>
        </a>

        <!-- Notes -->
        <a href="#" class="group bg-white rounded-2xl border border-slate-100 shadow-sm p-6 transition-all hover:shadow-md relative overflow-hidden border-t-4 border-t-cyan-500">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div class="h-12 w-12 bg-cyan-50 text-cyan-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <svg class="w-4 h-4 text-slate-200 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 tracking-tight mb-1">Notes</h3>
                <p class="text-xs text-slate-400 font-medium">Study materials</p>
            </div>
        </a>

        <!-- Institute Expense -->
        <a href="#" class="group bg-white rounded-2xl border border-slate-100 shadow-sm p-6 transition-all hover:shadow-md relative overflow-hidden border-t-4 border-t-emerald-600">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div class="h-12 w-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <svg class="w-4 h-4 text-slate-200 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 tracking-tight mb-1">Institute Expense</h3>
                <p class="text-xs text-slate-400 font-medium">Budget management</p>
            </div>
        </a>

        <!-- Lead Management -->
        <a href="#" class="group bg-white rounded-2xl border border-slate-100 shadow-sm p-6 transition-all hover:shadow-md relative overflow-hidden border-t-4 border-t-orange-600">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div class="h-12 w-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </div>
                    <svg class="w-4 h-4 text-slate-200 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 tracking-tight mb-1">Lead Management</h3>
                <p class="text-xs text-slate-400 font-medium">Inquiry tracking</p>
            </div>
        </a>
    </div>

    <!-- Bottom Section
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8"> -->
        <!-- Announcement Card -->
        <!-- <div class="lg:col-span-2 bg-white rounded-2xl p-10 border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="absolute right-10 bottom-10 opacity-10 group-hover:scale-110 transition-transform duration-1000">
                <div class="flex gap-4">
                    <svg class="w-16 h-16 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    <svg class="w-24 h-24 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    <svg class="w-16 h-16 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                </div>
            </div>
            
            <div class="relative z-10">
                <div class="inline-flex items-center px-3 py-1 bg-orange-100 rounded-full text-[9px] font-bold uppercase tracking-widest mb-8 text-orange-600">
                    Announcement
                </div>
                <h2 class="text-4xl font-bold leading-tight mb-10 max-w-xl tracking-tight text-slate-800">
                    Upgrade your campus experience with Tuoora Premium
                </h2>
                <button class="px-8 py-4 bg-[#8B4513] text-white rounded-xl font-bold text-sm hover:translate-y-[-2px] transition-all shadow-lg shadow-orange-900/10">
                    Explore Features
                </button>
            </div>
        </div> -->

        <!-- Quick Stats -->
        <!-- <div class="bg-[#006666] rounded-2xl p-10 text-white flex flex-col justify-center">
            <div class="flex items-center gap-3 mb-10">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.047a1 1 0 01.897.95l1.135 11.773 2.388-2.388a1 1 0 111.414 1.414l-4.14 4.142a1 1 0 01-1.414 0l-4.142-4.142a1 1 0 111.414-1.414l2.39 2.39-1.135-11.774a1 1 0 01.897-.95z" clip-rule="evenodd"/></svg>
                <h2 class="text-[11px] font-bold uppercase tracking-[0.1em]">Quick Stats</h2>
            </div>

            <div class="space-y-12">
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-base font-medium text-white/80">Student Enrollment</span>
                        <span class="text-4xl font-bold tracking-tight">{{ number_format($stats['total_students']) }}</span>
                    </div>
                    <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full bg-white rounded-full" style="width: 70%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-base font-medium text-white/80">Monthly Revenue</span>
                        <span class="text-4xl font-bold tracking-tight">₹{{ $stats['monthly_revenue_formatted'] }}</span>
                    </div>
                    <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full bg-white rounded-full" style="width: 45%"></div>
                    </div>
                </div>
            </div>
        </div> -->
    <!-- </div> -->
</div>

<!-- <footer class="mt-20 border-t border-slate-100 py-10">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6 px-6">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            © 2026 TUOORA EDUCATION. ALL RIGHTS RESERVED.
        </p>
        <div class="flex items-center gap-8">
            <a href="#" class="text-[10px] font-bold text-slate-400 hover:text-slate-600 uppercase tracking-widest transition-colors">Support</a>
            <a href="#" class="text-[10px] font-bold text-slate-400 hover:text-slate-600 uppercase tracking-widest transition-colors">Privacy</a>
            <a href="#" class="text-[10px] font-bold text-slate-400 hover:text-slate-600 uppercase tracking-widest transition-colors">Terms</a>
            <a href="#" class="text-[10px] font-bold text-slate-400 hover:text-slate-600 uppercase tracking-widest transition-colors">System Status</a>
        </div>
    </div>
</footer> -->

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;400;500;600;700;800;900&display=swap');
    
    :root {
        --font-outfit: 'Outfit', sans-serif;
    }

    body {
        font-family: var(--font-outfit);
        background-color: #f8fafc;
    }
</style>
@endsection