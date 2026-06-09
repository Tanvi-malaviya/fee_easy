@extends('layouts.institute')

@section('content')
    <div class="bg-pattern"></div>
    <div class="min-h-[calc(100vh-6.5rem)] flex flex-col justify-between max-w-7xl mx-auto mt-2 relative z-10">
        @if($institute->status !== 'active')
            <div class="w-full flex-grow flex items-center justify-center py-12">
                <div class="w-full max-w-md p-8 bg-white rounded-3xl border border-slate-100 shadow-xl animate-scaleUp text-center mx-4">
                    <div class="h-16 w-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-red-200/50">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    
                    <h3 class="text-2xl font-medium text-slate-800 tracking-tight mb-3">Institute Account Blocked</h3>
                    <p class="text-sm font-semibold text-slate-500 leading-relaxed px-2">
                        Your institute account is currently marked as <span class="text-red-600 font-medium uppercase">{{ $institute->status }}</span>. 
                        Please contact the administrator or support to activate your account.
                    </p>
                </div>
            </div>
        @else
            <div class="w-full">
            @if(!$institute->hasActiveSubscription())
                @if($hasPendingRenewal)
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50/50 border border-amber-200/60 rounded-2xl p-4 mb-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm relative z-20">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 bg-amber-100 text-amber-700 rounded-xl flex items-center justify-center shrink-0 border border-amber-200/50">
                                <svg class="w-5 h-5 animate-spin" style="animation-duration: 3s" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 6.5M18 9h-5" /></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-amber-800 tracking-tight">Renewal Request Pending Review</h4>
                                <p class="text-xs text-amber-600 mt-0.5">We have received your payment proof and transaction reference. Our billing team will verify it shortly.</p>
                            </div>
                        </div>
                        <a href="{{ route('institute.subscription.renew.show') }}" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-amber-600/10 shrink-0">
                            View Details</a>
                    </div>
                @else
                    <div class="bg-gradient-to-r from-rose-50 to-red-50/50 border border-rose-200/60 rounded-2xl p-4 mb-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-md shadow-rose-50/30 relative z-20">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 bg-rose-100 text-rose-700 rounded-xl flex items-center justify-center shrink-0 border border-rose-200/50 animate-bounce">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-rose-800 tracking-tight">Your Subscription Has Expired!</h4>
                                <p class="text-xs text-rose-600 mt-0.5">You can edit and delete existing records, but you cannot add new data. Please renew your plan to restore full access.</p>
                            </div>
                        </div>
                        <a href="{{ route('institute.subscription.renew.show') }}" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-extrabold rounded-xl transition-all shadow-lg shadow-rose-600/20 shrink-0 hover:scale-[1.02] active:scale-95">
                            ? Renew Subscription Now</a>
                    </div>
                @endif
            @else
                @if($hasPendingRenewal)
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50/50 border border-amber-200/60 rounded-2xl p-4 mb-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm relative z-20">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 bg-amber-100 text-amber-700 rounded-xl flex items-center justify-center shrink-0 border border-amber-200/50">
                                <svg class="w-5 h-5 animate-spin" style="animation-duration: 3s" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 6.5M18 9h-5" /></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-amber-800 tracking-tight">Renewal Request Pending Review</h4>
                                <p class="text-xs text-amber-600 mt-0.5">We have received your payment proof and transaction reference. Our billing team will verify it shortly.</p>
                            </div>
                        </div>
                        <a href="{{ route('institute.subscription.renew.show') }}" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-amber-600/10 shrink-0">
                            View Details</a>
                    </div>
                @elseif($subscriptionDaysLeft !== null && $subscriptionDaysLeft >= 0 && $subscriptionDaysLeft <= 7)
                    <div class="bg-gradient-to-r from-orange-50 to-amber-50/50 border border-orange-200/60 rounded-2xl p-4 mb-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-md shadow-orange-50/30 relative z-20 animate-scaleUp">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 bg-orange-100 text-orange-700 rounded-xl flex items-center justify-center shrink-0 border border-orange-200/50 animate-pulse">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-orange-800 tracking-tight">Your Plan is Expiring Soon!</h4>
                                <p class="text-xs text-orange-600 mt-0.5">
                                    Your subscription has only <span class="font-bold underline text-orange-700">{{ $subscriptionDaysLeft }} {{ $subscriptionDaysLeft == 1 ? 'Day' : 'Days' }} Left</span>. 
                                    Renew now to prevent any service disruption.
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('institute.subscription.renew.show') }}" class="px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-xs font-extrabold rounded-xl transition-all shadow-lg shadow-orange-600/20 shrink-0 hover:scale-[1.02] active:scale-95">
                            ⚡ Renew Subscription Now</a>
                    </div>
                @endif
            @endif

        <!-- Module Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            
            <!-- Students -->
            <a href="{{ route('institute.students.index') }}"
                class="group bg-white rounded-2xl border border-slate-100 shadow-sm p-4 transition-all hover:shadow-md relative overflow-hidden border-t-4 border-t-orange-500">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-9 w-9 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <svg class="w-4 h-4 text-slate-200 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight mb-1">Students</h3>
                    <p class="text-xs text-slate-400 font-medium">Manage student profiles</p>
                </div>
            </a>

            <!-- Batches -->
            <a href="{{ route('institute.batches.index') }}"
                class="group bg-white rounded-2xl border border-slate-100 shadow-sm p-4 transition-all hover:shadow-md relative overflow-hidden border-t-4 border-t-emerald-500">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-9 w-9 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <svg class="w-4 h-4 text-slate-200 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight mb-1">Batch Management</h3>
                    <p class="text-xs text-slate-400 font-medium">Class & curriculum setup</p>
                </div>
            </a>

            <!-- Fees -->
            <a href="{{ route('institute.fees.index') }}"
                class="group bg-white rounded-2xl border border-slate-100 shadow-sm p-4 transition-all hover:shadow-md relative overflow-hidden border-t-4 border-t-rose-500">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-9 w-9 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <svg class="w-4 h-4 text-slate-200 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight mb-1">Fees</h3>
                    <p class="text-xs text-slate-400 font-medium">Payment collection</p>
                </div>
            </a>

            <!-- Staff Management -->
            <a href="{{ route('institute.staff.index') }}"
                class="group bg-white rounded-2xl border border-slate-100 shadow-sm p-4 transition-all hover:shadow-md relative overflow-hidden border-t-4 border-t-sky-500">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-9 w-9 bg-sky-50 text-sky-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <svg class="w-4 h-4 text-slate-200 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight mb-1">Staff Management</h3>
                    <p class="text-xs text-slate-400 font-medium">Manage employees</p>
                </div>
            </a>

            <!-- Chats -->
            <a href="{{ route('institute.chats.index') }}"
                class="group bg-white rounded-2xl border border-slate-100 shadow-sm p-4 transition-all hover:shadow-md relative overflow-hidden border-t-4 border-t-orange-700">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-9 w-9 bg-orange-50 text-orange-700 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <svg class="w-4 h-4 text-slate-200 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight mb-1">Chats</h3>
                    <p class="text-xs text-slate-400 font-medium">Instant messaging</p>
                </div>
            </a>

            <!-- Reports -->
            <a href="{{ route('institute.reports.index') }}"
                class="group bg-white rounded-2xl border border-slate-100 shadow-sm p-4 transition-all hover:shadow-md relative overflow-hidden border-t-4 border-t-teal-500">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-9 w-9 bg-teal-50 text-teal-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <svg class="w-4 h-4 text-slate-200 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight mb-1">Reports</h3>
                    <p class="text-xs text-slate-400 font-medium">Insights & analytics</p>
                </div>
            </a>

            <!-- Lead Management -->
            <a href="{{ route('institute.leads.index') }}"
                class="group bg-white rounded-2xl border border-slate-100 shadow-sm p-4 transition-all hover:shadow-md relative overflow-hidden border-t-4 border-t-orange-500">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-9 w-9 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <svg class="w-4 h-4 text-slate-200 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight mb-1">Lead Management</h3>
                    <p class="text-xs text-slate-400 font-medium">Inquiry tracking</p>
                </div>
            </a>

            <!-- Notes -->
            <a href="{{ route('institute.notes.index') }}"
                class="group bg-white rounded-2xl border border-slate-100 shadow-sm p-4 transition-all hover:shadow-md relative overflow-hidden border-t-4 border-t-emerald-500">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-9 w-9 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <svg class="w-4 h-4 text-slate-200 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight mb-1">Notes</h3>
                    <p class="text-xs text-slate-400 font-medium">Study materials</p>
                </div>
            </a>

            <!-- Institute Expense -->
            <a href="{{ route('institute.expenses.index') }}"
                class="group bg-white rounded-2xl border border-slate-100 shadow-sm p-4 transition-all hover:shadow-md relative overflow-hidden border-t-4 border-t-rose-500">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-9 w-9 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <svg class="w-4 h-4 text-slate-200 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight mb-1">Institute Expense</h3>
                    <p class="text-xs text-slate-400 font-medium">Budget management</p>
                </div>
            </a>

            <!-- Updates -->
            <a href="{{ route('institute.updates.index') }}"
                class="group bg-white rounded-2xl border border-slate-100 shadow-sm p-4 transition-all hover:shadow-md relative overflow-hidden border-t-4 border-t-sky-500">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-9 w-9 bg-sky-50 text-sky-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <svg class="w-4 h-4 text-slate-200 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight mb-1">Updates</h3>
                    <p class="text-xs text-slate-400 font-medium">News & notifications</p>
                </div>
            </a>
        </div>
          
       

         

         

         

           
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
        @endif
    
        <footer class="mb-0 border-t border-slate-100 ">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4 px-6">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    © 2026 TUOORA EDUCATION. ALL RIGHTS RESERVED.
                </p>
                <div class="flex items-center gap-6">
                    <span class="text-slate-300">|</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest select-none">
                        Version {{ \App\Models\SystemSetting::get('web_version', '1.0.0') }}
                    </span>
                </div>
            </div>
        </footer>

        <!-- Expiration Alert Modal -->
        <div x-data="{ showExpiryModal: !{{ $institute->hasActiveSubscription() ? 'true' : 'false' }} }" 
             x-show="showExpiryModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

            <!-- Modal Wrapper -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100"
                     @click.away="showExpiryModal = false">
                    
                    <!-- Close button in top-right -->
                    <div class="absolute top-4 right-4">
                        <button @click="showExpiryModal = false" class="text-slate-400 hover:text-slate-600 transition-colors p-1.5 hover:bg-slate-50 rounded-xl">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="p-8">
                        <!-- Icon -->
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-50 text-rose-500 border border-rose-100 mb-6">
                            <svg class="h-7 w-7 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>

                        <div class="text-center">
                            <h3 class="text-2xl font-bold text-slate-800 tracking-tight mb-3">Subscription Has Expired!</h3>
                            <p class="text-sm font-medium text-slate-500 leading-relaxed px-4">
                                You can edit and delete existing records, but you cannot add new data. Please renew your plan to restore full access.
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-slate-50/50 border-t border-slate-100 px-8 py-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <button type="button" 
                                @click="showExpiryModal = false" 
                                class="w-full sm:w-auto inline-flex justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-xs font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition-colors uppercase tracking-wider">
                            Remind Me Later
                        </button>
                        <a href="{{ route('institute.subscription.renew.show') }}" 
                           class="w-full sm:w-auto inline-flex justify-center rounded-xl bg-rose-600 px-5 py-2.5 text-xs font-extrabold text-white shadow-lg shadow-rose-600/20 hover:bg-rose-700 transition-all hover:scale-[1.02] active:scale-95 uppercase tracking-wider">
                            ⚡ Renew Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript & Styling -->
    <script>
        function copyInstituteDetails() {
            const idText = document.getElementById('ref-inst-id').innerText;
            const codeText = document.getElementById('ref-inst-code').innerText;
            const textToCopy = `Institute ID: ${idText} | Code: ${codeText}`;
            
            navigator.clipboard.writeText(textToCopy).then(() => {
                alert('Institute Details copied to clipboard!');
            }).catch(err => {
                console.error('Copy details failed: ', err);
            });
        }
    </script>

    <style>
        @keyframes scaleUp {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        .animate-scaleUp {
            animation: scaleUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;400;500;600;700;800;900&display=swap');

        :root {
            --font-outfit: 'Outfit', sans-serif;
        }

        body {
            font-family: var(--font-outfit);
            background-color: #f8fafc;
        }

        .bg-pattern {
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(148,163,184,0.12)'/%3E%3C/svg%3E");
            z-index: 1;
            pointer-events: none;
        }
    </style>
@endsection
