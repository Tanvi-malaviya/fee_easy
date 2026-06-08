@extends('layouts.institute')

@section('content')
    <div class="max-w-[1200px] mx-auto pb-6 pt-2">

        @if (!auth()->guard('institute')->user()->isProfileComplete())
            <!-- Incomplete Profile Warning Alert Banner -->
            <div
                class="mb-4 bg-gradient-to-r from-amber-500/10 via-orange-500/10 to-rose-500/10 border-2 border-orange-500/20 rounded-2xl p-5 shadow-lg shadow-orange-500/5 flex flex-col md:flex-row items-center justify-between gap-4 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="flex items-center gap-4">
                    <div
                        class="h-12 w-12 rounded-xl bg-orange-500 text-white flex items-center justify-center shrink-0 shadow-lg shadow-orange-500/20 animate-pulse">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 tracking-tight">Complete Your Profile Setup</h4>
                        <p class="text-xs text-slate-600 font-semibold leading-relaxed mt-0.5">Please provide your <strong>Phone
                                Number, Address, City, State, and Pincode</strong> below. Completing your profile is required to
                            gain full access to your institute dashboard and core portal features.</p>
                    </div>
                </div>
                <a href="{{ route('institute.profile.edit') }}"
                    class="w-full md:w-auto px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs uppercase tracking-widest rounded-xl shadow-md transition-all shrink-0 hover:scale-[1.02] active:scale-95 text-center flex items-center justify-center">
                    Configure Profile
                </a>
            </div>
        @endif

        <!-- Premium Profile Header -->
        <div
            class="bg-white rounded-[1rem] shadow-xl border border-slate-100/50 overflow-hidden relative mb-4 animate-in fade-in slide-in-from-top-4 duration-500">
            <!-- Banner -->
            <div class="h-16 bg-gradient-to-r from-[#e05f00] via-[#ff6c00] to-[#ff9f43] relative"></div>

            <!-- Profile Info -->
            <div class="px-6 pb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 relative">
                <div class="flex flex-col md:flex-row items-start md:items-center gap-4 -mt-12 relative z-10">
                    <!-- Logo Box -->
                    <div
                        class="h-28 w-28 bg-white rounded-2xl p-1.5 shadow-2xl border border-slate-100 flex items-center justify-center overflow-hidden shrink-0">
                        <img id="profile-logo-preview"
                            src="{{ auth()->guard('institute')->user()->logo ? asset('storage/' . auth()->guard('institute')->user()->logo) : '' }}"
                            class="w-full h-full object-cover rounded-xl {{ auth()->guard('institute')->user()->logo ? '' : 'hidden' }}">

                        <div id="profile-logo-placeholder"
                            class="w-full h-full bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl flex items-center justify-center text-white text-4xl font-black shadow-inner uppercase {{ auth()->guard('institute')->user()->logo ? 'hidden' : '' }}">
                            {{ substr(auth()->guard('institute')->user()->institute_name ?? auth()->guard('institute')->user()->name ?? 'I', 0, 1) }}
                        </div>
                    </div>

                    <div class="md:pt-12">
                        <h1 id="view-institute_name" class="text-xl font-semibold text-slate-800 tracking-tight">
                            {{ auth()->guard('institute')->user()->name }}
                        </h1>
                        <div
                            class="flex flex-wrap items-center gap-3 mt-1 text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span id="view-city"
                                    class="city-text">{{ auth()->guard('institute')->user()->city ?? 'Location' }}</span>
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span id="view-email"
                                    class="email-text">{{ auth()->guard('institute')->user()->email }}</span>
                            </span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('institute.profile.edit') }}"
                    class="px-5 py-2.5 bg-[#e05f00] hover:bg-[#c44f00] text-white rounded-xl font-bold text-[10px] uppercase tracking-widest shadow-lg shadow-orange-500/20 hover:scale-[1.02] active:scale-95 transition-all md:pt-3 flex items-center justify-center h-fit">
                    {{ auth()->guard('institute')->user()->isProfileComplete() ? 'Edit Profile' : 'Complete Setup' }}
                </a>
            </div>
        </div>

        <!-- View Mode Section -->
        <div id="profile-view-section" class="grid grid-cols-1 lg:grid-cols-2 gap-6 animate-in fade-in duration-300">
            <!-- Account Management -->
            <div class="space-y-3">
                <div
                    class="bg-white rounded-[1rem] shadow-xl border border-slate-100/50 overflow-hidden divide-y divide-slate-50">
                    <!-- Password & Security -->
                    <button onclick="openPasswordModal()"
                        class="w-full py-2.5 px-5 flex items-center justify-between hover:bg-slate-50 transition-colors group text-left">
                        <div class="flex items-center gap-4">
                            <div
                                class="h-10 w-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 leading-tight">Password & Security</h3>
                                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Update credentials and 2FA</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <!-- Subscription Plan -->
                    <a href="{{ route('institute.plans.index') }}"
                        class="py-2.5 px-5 flex items-center justify-between hover:bg-slate-50 transition-colors group">
                        <div class="flex items-center gap-4">
                            <div
                                class="h-10 w-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 leading-tight">Subscription Plan</h3>
                                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Manage your active tier and billing
                                </p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <!-- WhatsApp Integration -->
                    <button type="button" onclick="openWhatsAppModal()"
                        class="w-full py-2.5 px-5 flex items-center justify-between hover:bg-slate-50 transition-colors group text-left">
                        <div class="flex items-center gap-4">
                            <div
                                class="h-10 w-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 leading-tight">WhatsApp Integration</h3>
                                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Automate alerts via Meta API</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            @php
                                $wa = auth()->guard('institute')->user()->whatsappSettings;
                                $waConfigured = $wa && $wa->access_token && $wa->phone_number_id;
                            @endphp
                            @unless($waConfigured)
                                <span class="text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-full bg-amber-50 text-amber-600 border border-amber-100 whitespace-nowrap">Not Configured</span>
                            @endunless
                            <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </button>


                    <!-- Terms & Conditions -->
                    <a href="https://tuoora.com/terms-conditions" target="_blank"
                        class="w-full py-2.5 px-5 flex items-center justify-between hover:bg-slate-50 transition-colors group text-left">
                        <div class="flex items-center gap-4">
                            <div
                                class="h-10 w-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 leading-tight">Terms & Conditions</h3>
                                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Read our terms of service</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <!-- Privacy Policy -->
                    <a href="https://tuoora.com/privacy-policy" target="_blank"
                        class="w-full py-2.5 px-5 flex items-center justify-between hover:bg-slate-50 transition-colors group text-left">
                        <div class="flex items-center gap-4">
                            <div
                                class="h-10 w-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 leading-tight">Privacy Policy</h3>
                                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Learn how we protect your data</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <!-- Help & Support -->
                    <button
                        class="w-full py-2.5 px-5 flex items-center justify-between hover:bg-slate-50 transition-colors group text-left">
                        <div class="flex items-center gap-4">
                            <div
                                class="h-10 w-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 leading-tight">Help & Support</h3>
                                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Contact us or view FAQ</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                </div>
            </div>

            <!-- Subscription -->
            <div class="space-y-3">
                <div
                    class="bg-white rounded-[1rem] shadow-xl border border-slate-100/50 p-6 relative overflow-hidden h-fit">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-800 tracking-tight">Current Active Plan</h3>
                            <div class="flex items-center gap-2 mt-2">
                                <span id="plan-title" class="text-sm font-bold text-slate-800 uppercase tracking-wider">
                                    Loading...
                                </span>
                            </div>
                        </div>

                        <div
                            class="h-10 w-10 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Remaining Days -->
                    <div id="remaining-days-row" class="mt-3 hidden">
                        <div class="flex items-center gap-2.5 bg-slate-50 rounded-xl p-3 border border-slate-100">
                            <div
                                class="h-8 w-8 bg-orange-50 text-orange-500 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Remaining</p>
                                <p id="remaining-days-text" class="text-[11px] font-bold text-slate-700 mt-0.5">—</p>
                            </div>
                        </div>
                    </div>

                    <!-- Expiry Warning — shown only when subscription expires within 7 days -->
                    <div id="expiry-warning" class="mt-2 hidden">
                        <div class="flex items-center gap-2.5 bg-amber-50 rounded-xl p-3 border border-amber-200">
                            <div
                                class="h-8 w-8 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-amber-600 uppercase tracking-widest">Expiring Soon</p>
                                <p id="expiry-date-text" class="text-[11px] font-bold text-amber-800 mt-0.5">N/A</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- UPI & Payment settings show card -->
                <div class="bg-white rounded-[1rem] shadow-xl border border-slate-100/50 p-4 relative overflow-hidden h-fit">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <div class="w-1 h-3.5 bg-[#ff6c00] rounded-full"></div>
                            <h2 class="text-sm font-[550] text-slate-800 tracking-tight">UPI Payment Details</h2>
                        </div>
                        @if(auth()->guard('institute')->user()->upi_id || auth()->guard('institute')->user()->upi_qr_code)
                            <button type="button" onclick="openPaymentModal()" class="text-[10px] font-bold text-[#ff6c00] hover:text-[#e05f00] transition-colors">
                                Edit Settings
                            </button>
                        @endif
                    </div>

                    @if(auth()->guard('institute')->user()->upi_id || auth()->guard('institute')->user()->upi_qr_code)
                        <div class="space-y-3">
                            @if(auth()->guard('institute')->user()->upi_id)
                                <div>
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-1">UPI ID (VPA)</span>
                                    <div class="flex items-center justify-between bg-slate-50 border border-slate-100 rounded-xl py-2 px-3">
                                        <span class="text-xs font-bold text-slate-700 select-all">{{ auth()->guard('institute')->user()->upi_id }}</span>
                                        <button onclick="navigator.clipboard.writeText('{{ auth()->guard('institute')->user()->upi_id }}'); showToast('UPI ID Copied!');" class="text-slate-400 hover:text-[#ff6c00] transition-colors p-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endif

                            @if(auth()->guard('institute')->user()->upi_qr_code_url)
                                <div>
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-1">QR Code</span>
                                    <div class="flex flex-col items-center justify-center bg-slate-50 border border-slate-100 rounded-xl p-3">
                                        <div class="h-28 w-28 bg-white border border-slate-200 rounded-lg p-1.5 shadow-sm flex items-center justify-center overflow-hidden">
                                            <img id="profile-upi-qr-preview" src="{{ auth()->guard('institute')->user()->upi_qr_code_url }}" alt="UPI QR Code" class="w-full h-full object-contain">
                                        </div>
                                        <p class="text-[9px] text-slate-400 font-medium text-center mt-1.5">
                                            Scan QR code to pay student fees.
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="h-12 w-12 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-3 shadow-inner">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-700">Add UPI Details</h3>
                            <p class="text-[10px] text-slate-400 font-medium mt-1 mb-4">Set up your UPI ID and QR code to enable fee payments.</p>
                            <button type="button" onclick="openPaymentModal()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-orange-50 hover:bg-orange-100 text-[#ff6c00] rounded-xl font-bold text-[10px] transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                </svg>
                                Add
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>


    </div>

    <!-- Password Modal (Remains as modal since it's a small action) -->
    <div id="password-modal"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
        <div
            class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
            <!-- Header -->
            <div class="py-3.5 px-5 bg-gradient-to-r from-[#e05f00] via-[#ff6c00] to-[#ff9f43] flex items-start justify-between relative">
                <div>
                    <h3 class="text-base font-bold text-white leading-tight">Update Password</h3>
                    <p class="text-[10px] text-white/80 mt-0.5">Ensure your account stays secure with a strong password.
                    </p>
                </div>
                <button onclick="closePasswordModal()" class="text-white/80 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form id="password-form" class="pt-4 pb-4 px-4 space-y-2">
                @csrf

                <!-- Current Password -->
                <div class="">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Current
                        Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M15 7a2 2 0 012 2m-2 4a5 5 0 111.707-9.707l3.707 3.707A1 1 0 0121 4v3h-2v2h-2v2h-2.293A5 5 0 0115 13zm-5-4a1 1 0 100-2 1 1 0 000 2z" />
                            </svg>
                        </span>
                        <input type="password" name="current_password" id="pwd-current" placeholder="Enter current password" required
                            class="input-with-icon" style="padding-right:38px">
                        <button type="button" onclick="togglePasswordVisibility(this)" tabindex="-1"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="eye-open w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="eye-closed w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                        </button>
                    </div>
                </div>

                <!-- New Password -->
                <div class="">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">New Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        <input type="password" name="password" id="pwd-new" placeholder="Enter new password" required
                            class="input-with-icon" style="padding-right:38px">
                        <button type="button" onclick="togglePasswordVisibility(this)" tabindex="-1"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="eye-open w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="eye-closed w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                        </button>
                    </div>
                    <p class="text-[9px] text-slate-400 flex items-center gap-1 ml-1 mt-0.5">
                        <svg class="w-3 h-3 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Min 8 characters
                    </p>
                </div>

                <!-- Confirm New Password -->
                <div class="">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Confirm New
                        Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </span>
                        <input type="password" name="password_confirmation" id="pwd-confirm" placeholder="Re-enter new password" required
                            class="input-with-icon" style="padding-right:38px">
                        <button type="button" onclick="togglePasswordVisibility(this)" tabindex="-1"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="eye-open w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="eye-closed w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                        </button>
                    </div>
                </div>


                <!-- Inline Error Box -->
                <div id="pwd-error" class="hidden bg-rose-50 border border-rose-100 rounded-xl p-2.5 flex items-start gap-2">
                    <svg class="w-3.5 h-3.5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p id="pwd-error-text" class="text-[10px] font-bold text-rose-600 leading-relaxed"></p>
                </div>

                <!-- Footer Buttons -->
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100 mt-2">
                    <button type="button" onclick="closePasswordModal()"
                        class="text-[11px] font-bold text-slate-400 hover:text-slate-600 transition-colors">
                        Discard Changes
                    </button>
                    <button type="submit" id="submit-btn"
                        class="px-4 py-2 bg-[#ff6c00] hover:bg-[#e05f00] text-white rounded-lg font-bold text-[10px] uppercase tracking-widest shadow-md hover:scale-[1.01] transition-all flex items-center justify-center gap-1.5">
                        <span>Update Password</span>
                        <div id="pwd-loader"
                            class="h-3 w-3 border-2 border-white/20 border-t-white rounded-full animate-spin hidden"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- UPI Payment Settings Modal -->
    <div id="payment-modal"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
        <div
            class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200 max-h-[90vh] overflow-y-auto">
            <!-- Header -->
            <div class="py-3.5 px-5 bg-gradient-to-r from-[#e05f00] via-[#ff6c00] to-[#ff9f43] flex items-start justify-between relative">
                <div>
                    <h3 class="text-base font-bold text-white leading-tight">UPI Payment Settings</h3>
                    <p class="text-[10px] text-white/80 mt-0.5">Configure UPI ID and QR code to enable direct online fee
                        payments.</p>
                </div>
                <button onclick="closePaymentModal()" class="text-white/80 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form id="payment-form" class="pt-4 pb-4 px-4 space-y-4" enctype="multipart/form-data">
                @csrf

                <!-- UPI ID Input -->
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">UPI ID (VPA)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </span>
                        <input type="text" name="upi_id" id="field-upi_id" placeholder="merchant@upi or mobile@ybl"
                            class="input-with-icon">
                    </div>
                    <p class="text-[9px] text-slate-400 ml-1">Enter a valid merchant VPA or personal UPI ID (e.g.
                        name@bank, phone@upi).</p>
                </div>

                <!-- QR Code Upload -->
                <div class="border-t border-slate-100 pt-4">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 block mb-2">UPI QR
                        Code Image</label>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                        <!-- QR Preview Box -->
                        <div class="relative group cursor-pointer shrink-0"
                            onclick="document.getElementById('qr-input').click()">
                            <div
                                class="h-32 w-32 bg-slate-50 border border-slate-200 rounded-2xl p-2 shadow-inner flex items-center justify-center overflow-hidden">
                                <img id="qr-preview-img" src="" class="w-full h-full object-contain hidden">
                                <div id="qr-placeholder" class="text-center p-2 text-slate-400">
                                    <svg class="w-8 h-8 mx-auto mb-1 text-slate-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    <span class="text-[9px] font-bold uppercase tracking-wider block">No QR Uploaded</span>
                                </div>
                            </div>
                            <div
                                class="absolute inset-0 bg-black/40 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                </svg>
                            </div>
                        </div>
                        <!-- Upload Info -->
                        <div class="flex-1">
                            <p class="text-[9px] font-black text-[#ff6c00] uppercase tracking-widest">QR Code
                                Specifications</p>
                            <p class="text-[10px] text-slate-400 font-medium mt-0.5 leading-relaxed">
                                Please upload the QR code generated from your business app (GPay, PhonePe, Paytm, BHIM,
                                etc.). Max size 2MB. Format: PNG, JPG, JPEG.
                            </p>
                            <div class="mt-3 flex items-center gap-2">
                                <button type="button" onclick="document.getElementById('qr-input').click()"
                                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-[10px] transition-all">
                                    Choose File
                                </button>
                            </div>
                        </div>
                        <input type="file" id="qr-input" name="upi_qr_code" class="hidden" accept="image/*"
                            onchange="previewQR(this)">
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closePaymentModal()"
                        class="text-[11px] font-bold text-slate-400 hover:text-slate-600 transition-colors">
                        Discard Changes
                    </button>
                    <button type="submit" id="payment-submit-btn"
                        class="px-4 py-2 bg-[#ff6c00] hover:bg-[#e05f00] text-white rounded-lg font-bold text-[10px] uppercase tracking-widest shadow-md hover:scale-[1.01] transition-all flex items-center justify-center gap-1.5">
                        <span>Save Settings</span>
                        <div id="payment-loader"
                            class="h-3 w-3 border-2 border-white/20 border-t-white rounded-full animate-spin hidden"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- WhatsApp Modal -->
    <div id="whatsapp-modal"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
        <div
            class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
            <!-- Header -->
            <div class="py-3.5 px-5 bg-gradient-to-r from-[#e05f00] via-[#ff6c00] to-[#ff9f43] flex items-start justify-between relative">
                <div>
                    <h3 class="text-base font-bold text-white leading-tight">WhatsApp Integration</h3>
                    <p class="text-[10px] text-white/80 mt-0.5">Connect your Meta WhatsApp Cloud API credentials.</p>
                </div>
                <button onclick="closeWhatsAppModal()" class="text-white/80 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form id="whatsapp-modal-form" class="pt-4 pb-4 px-4 space-y-3">
                @csrf
                <div id="wa-loader" class="py-4 flex flex-col items-center justify-center">
                    <div class="h-4 w-4 border-2 border-orange-500/20 border-t-[#ff6c00] rounded-full animate-spin"></div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-2">Loading credentials...
                    </p>
                </div>

                <div id="wa-form-content" class="space-y-2 hidden">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">WhatsApp Phone
                            Number</label>
                        <input type="text" name="phone_number" id="wa-phone_number" required class="input-wa"
                            placeholder="e.g. 919876543210">
                        <p class="text-[8px] text-slate-400 font-medium ml-1">Include country code without +</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone Number
                                ID</label>
                            <input type="text" name="phone_number_id" id="wa-phone_number_id" required class="input-wa"
                                placeholder="e.g. 1098425...">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Business
                                ID</label>
                            <input type="text" name="business_account_id" id="wa-business_account_id" required
                                class="input-wa" placeholder="e.g. 1530948...">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Access
                            Token</label>
                        <textarea name="access_token" id="wa-access_token" rows="3" required class="textarea-wa"
                            placeholder="EAAW..."></textarea>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100 mt-2">
                        <button type="button" onclick="closeWhatsAppModal()"
                            class="text-[11px] font-bold text-slate-400 hover:text-slate-600 transition-colors">
                            Discard
                        </button>
                        <button type="submit" id="wa-submit-btn"
                            class="px-4 py-2 bg-[#ff6c00] hover:bg-[#e05f00] text-white rounded-lg font-bold text-[10px] uppercase tracking-widest shadow-md hover:scale-[1.01] transition-all flex items-center justify-center gap-1.5">
                            <span>Save Integration</span>
                            <div id="wa-submit-loader"
                                class="h-3 w-3 border-2 border-white/20 border-t-white rounded-full animate-spin hidden">
                            </div>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .input-wa {
            width: 100%;
            height: 36px;
            padding: 0 10px;
            border-radius: 6px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            font-weight: 550;
            font-size: 11px;
            color: #334155;
            transition: all 0.2s;
        }

        .input-wa:focus,
        .textarea-wa:focus {
            outline: none;
            background: #fff;
            border-color: #ff6c00;
            box-shadow: 0 4px 12px rgba(255, 108, 0, 0.05);
        }

        .textarea-wa {
            width: 100%;
            padding: 8px 10px;
            border-radius: 6px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            font-weight: 550;
            font-size: 11px;
            color: #334155;
            transition: all 0.2s;
            resize: none;
        }
    </style>

    <style>
        .input-with-icon {
            width: 100%;
            height: 38px;
            padding: 0 12px 0 32px;
            border-radius: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            font-weight: 550;
            font-size: 12px;
            color: #334155;
            transition: all 0.2s;
        }

        .input-with-icon:focus {
            outline: none;
            background: #fff;
            border-color: #ff6c00;
            box-shadow: 0 4px 12px rgba(255, 108, 0, 0.05);
        }

        .input-with-icon::placeholder {
            color: #cbd5e1;
            font-weight: 500;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', fetchProfile);

        async function fetchProfile() {
            try {
                const headers = { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' };
                const token = localStorage.getItem('token');
                if (token) headers['Authorization'] = `Bearer ${token}`;

                const response = await fetch('/api/v1/institute/profile', { headers });
                const result = await response.json();
                if (result.status === 'success') {
                    const data = result.data;
                    const logoPreview = document.getElementById('profile-logo-preview');
                    const logoPlaceholder = document.getElementById('profile-logo-placeholder');

                    if (data.logo_url) {
                        if (logoPreview) {
                            logoPreview.src = data.logo_url;
                            logoPreview.classList.remove('hidden');
                        }
                        if (logoPlaceholder) {
                            logoPlaceholder.classList.add('hidden');
                        }
                    } else {
                        if (logoPreview) {
                            logoPreview.classList.add('hidden');
                        }
                        if (logoPlaceholder) {
                            logoPlaceholder.innerText = (data.institute_name || data.name || 'I').substring(0, 1).toUpperCase();
                            logoPlaceholder.classList.remove('hidden');
                        }
                    }

                    document.getElementById('view-institute_name').innerHTML = `
                                ${data.institute_name || data.name || 'Institute'}
                                <span id="view-institute_code" class="text-xs bg-orange-50 text-[#ff6c00] px-2.5 py-1 rounded-lg font-black uppercase border border-orange-100/50 ml-2">
                                    ${data.institute_code || ''}
                                </span>
                            `;
                    document.getElementById('view-city').innerText = data.city || 'Location';
                    document.getElementById('view-email').innerText = data.email || '';

                    const sub = result.subscription;
                    const badgeSub = document.getElementById('badge-sub-status');
                    const planTitle = document.getElementById('plan-title');
                    if (sub) {
                        if (badgeSub) {
                            badgeSub.innerText = sub.status.toUpperCase();
                            if (sub.status.toLowerCase() === 'expired' || sub.status.toLowerCase() === 'inactive') {
                                badgeSub.className = 'text-[8px] bg-rose-50 text-rose-600 px-2.5 py-1 rounded-full font-black uppercase tracking-widest border border-rose-100';
                            } else {
                                badgeSub.className = 'text-[8px] bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-full font-black uppercase tracking-widest border border-emerald-100';
                            }
                        }
                        planTitle.innerText = sub.plan_name;

                        // Show remaining days and expiry warning
                        const expiry = sub.expires_at || sub.end_date;
                        if (expiry) {
                            const expiryDate = new Date(expiry);
                            const now = new Date();
                            const diffMs = expiryDate - now;
                            const diffDays = Math.ceil(diffMs / (1000 * 60 * 60 * 24));
                            const formattedDate = expiryDate.toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' });

                            // Always show remaining days
                            const remainingRow = document.getElementById('remaining-days-row');
                            const remainingText = document.getElementById('remaining-days-text');
                            if (remainingRow && remainingText) {
                                if (diffDays > 0) {
                                    remainingText.innerText = `${diffDays} day${diffDays !== 1 ? 's' : ''} remaining — expires ${formattedDate}`;
                                } else if (diffDays === 0) {
                                    remainingText.innerText = `Expires today — ${formattedDate}`;
                                } else {
                                    remainingText.innerText = `Expired on ${formattedDate}`;
                                }
                                remainingRow.classList.remove('hidden');
                            }

                            // Show expiry warning only when within 7 days
                            if (diffDays >= 0 && diffDays <= 7) {
                                const expiryWarning = document.getElementById('expiry-warning');
                                const expiryText = document.getElementById('expiry-date-text');
                                if (expiryWarning && expiryText) {
                                    const daysLabel = diffDays === 0 ? 'Expires today' : (diffDays === 1 ? 'Expires tomorrow' : `Expires in ${diffDays} days`);
                                    expiryText.innerText = `${daysLabel} — Renew now to avoid interruption`;
                                    expiryWarning.classList.remove('hidden');
                                }
                            }
                        }
                    }

                }
            } catch (error) { console.error('Error fetching profile:', error); }
        }

        function previewLogo(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    document.getElementById('profile-logo-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function openPasswordModal() { hidePwdError(); document.getElementById('password-form').reset(); document.getElementById('password-modal').classList.replace('hidden', 'flex'); document.body.style.overflow = 'hidden'; }
        function closePasswordModal() { hidePwdError(); document.getElementById('password-modal').classList.replace('flex', 'hidden'); document.body.style.overflow = 'auto'; }

        // Toggle show/hide for any password input that has an eye button sibling
        function togglePasswordVisibility(btn) {
            const input = btn.parentElement.querySelector('input');
            if (!input) return;
            const showing = input.type === 'text';
            input.type = showing ? 'password' : 'text';
            btn.querySelector('.eye-open')?.classList.toggle('hidden', !showing);
            btn.querySelector('.eye-closed')?.classList.toggle('hidden', showing);
        }

        function showPwdError(msg) {
            const box = document.getElementById('pwd-error');
            document.getElementById('pwd-error-text').textContent = msg;
            box.classList.remove('hidden');
        }
        function hidePwdError() {
            document.getElementById('pwd-error')?.classList.add('hidden');
        }

        // Client-side password policy (mirrors server rules)
        function validatePasswordForm(fd) {
            const current = (fd.get('current_password') || '').trim();
            const pwd = fd.get('password') || '';
            const confirm = fd.get('password_confirmation') || '';

            if (!current) return 'Please enter your current password.';
            if (pwd.length < 8 || pwd.length > 15) return 'New password must be between 8 and 15 characters.';
            if (!/[a-z]/.test(pwd)) return 'New password must contain at least 1 lowercase letter.';
            if (!/[A-Z]/.test(pwd)) return 'New password must contain at least 1 uppercase letter.';
            if (!/\d/.test(pwd)) return 'New password must contain at least 1 number.';
            if (!/[\W_]/.test(pwd)) return 'New password must contain at least 1 special character.';
            if (current === pwd) return 'New password cannot be the same as the current password.';
            if (pwd !== confirm) return 'New password and confirmation do not match.';
            return null;
        }

        document.getElementById('password-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            hidePwdError();

            const formData = new FormData(e.target);

            // 1) Run client-side validation BEFORE any API call
            const validationError = validatePasswordForm(formData);
            if (validationError) {
                showPwdError(validationError);
                return;
            }

            const btn = document.getElementById('submit-btn');
            const loader = document.getElementById('pwd-loader');
            btn.disabled = true;
            if (loader) loader.classList.remove('hidden');

            try {
                const response = await fetch('{{ route("institute.profile.password.update") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json().catch(() => ({}));

                if (response.ok) {
                    showToast('Password updated successfully!');
                    closePasswordModal();
                    e.target.reset();
                } else {
                    // 2) Surface the specific server error inline above the buttons
                    //    (e.g. "current password is incorrect")
                    let msg = data.message || 'Error updating password.';
                    if (data.errors) {
                        const first = Object.values(data.errors)[0];
                        if (Array.isArray(first) && first.length) msg = first[0];
                    }
                    showPwdError(msg);
                }
            } catch (error) {
                showPwdError('Something went wrong. Please try again.');
            } finally {
                btn.disabled = false;
                if (loader) loader.classList.add('hidden');
            }
        });
        // ── UPI Payment Settings Modal ──────────────────────────────────
        function openPaymentModal() {
            document.getElementById('payment-modal').classList.replace('hidden', 'flex');
            document.body.style.overflow = 'hidden';
            fetchPaymentSettings();
        }

        function closePaymentModal() {
            document.getElementById('payment-modal').classList.replace('flex', 'hidden');
            document.body.style.overflow = 'auto';
        }

        async function fetchPaymentSettings() {
            try {
                const headers = { 'X-Requested-With': 'XMLHttpRequest' };
                const token = localStorage.getItem('token');
                if (token) headers['Authorization'] = `Bearer ${token}`;

                const response = await fetch('/api/v1/institute/profile', { headers });
                const result = await response.json();

                if (result.status === 'success') {
                    const data = result.data;
                    document.getElementById('field-upi_id').value = data.upi_id || '';

                    const qrImg = document.getElementById('qr-preview-img');
                    const qrPlaceholder = document.getElementById('qr-placeholder');

                    if (data.upi_qr_code_url) {
                        qrImg.src = data.upi_qr_code_url;
                        qrImg.classList.remove('hidden');
                        qrPlaceholder.classList.add('hidden');
                    } else {
                        qrImg.classList.add('hidden');
                        qrPlaceholder.classList.remove('hidden');
                    }
                }
            } catch (error) {
                console.error('Error fetching payment settings:', error);
            }
        }

        function previewQR(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const qrImg = document.getElementById('qr-preview-img');
                    const qrPlaceholder = document.getElementById('qr-placeholder');
                    qrImg.src = e.target.result;
                    qrImg.classList.remove('hidden');
                    qrPlaceholder.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('payment-form').addEventListener('submit', async (e) => {
            e.preventDefault();

            // Front-end VPA verification (standard format check)
            const upiId = document.getElementById('field-upi_id').value.trim();
            if (upiId && !/^[\w\.\-]+@[\w\-]+$/.test(upiId)) {
                showToast('Please enter a valid UPI ID (VPA format: name@bank).', 'error');
                return;
            }

            const btn = document.getElementById('payment-submit-btn');
            const loader = document.getElementById('payment-loader');
            btn.disabled = true;
            if (loader) loader.classList.remove('hidden');

            try {
                const response = await fetch('/api/v1/institute/profile/payment/update', {
                    method: 'POST',
                    body: new FormData(e.target),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    }
                });

                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    showToast('Payment settings updated successfully!');
                    setTimeout(() => window.location.reload(), 900);
                } else {
                    showToast(result.message || 'Error updating payment settings', 'error');
                }
            } catch (error) {
                console.error('Error updating settings:', error);
                showToast('Something went wrong.', 'error');
            } finally {
                btn.disabled = false;
                if (loader) loader.classList.add('hidden');
            }
        });

        function openWhatsAppModal() {
            document.getElementById('whatsapp-modal').classList.replace('hidden', 'flex');
            document.body.style.overflow = 'hidden';
            fetchWhatsAppSettings();
        }

        function closeWhatsAppModal() {
            document.getElementById('whatsapp-modal').classList.replace('flex', 'hidden');
            document.body.style.overflow = 'auto';
        }

        async function fetchWhatsAppSettings() {
            const loader = document.getElementById('wa-loader');
            const content = document.getElementById('wa-form-content');

            try {
                const headers = { 'X-Requested-With': 'XMLHttpRequest' };
                const token = localStorage.getItem('token');
                if (token) headers['Authorization'] = `Bearer ${token}`;

                const response = await fetch('/api/v1/institute/whatsapp-settings', { headers });
                const result = await response.json();

                if (result.status === 'success' && result.data) {
                    const data = result.data;
                    document.getElementById('wa-phone_number').value = data.phone_number || '';
                    document.getElementById('wa-phone_number_id').value = data.phone_number_id || '';
                    document.getElementById('wa-business_account_id').value = data.business_account_id || '';
                    document.getElementById('wa-access_token').value = data.access_token || '';
                    document.getElementById('wa-is_active').checked = data.is_active;
                }
            } catch (error) {
                console.error('Fetch WA Settings Error:', error);
            } finally {
                loader.classList.add('hidden');
                content.classList.remove('hidden');
            }
        }

        document.getElementById('whatsapp-modal-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('wa-submit-btn');
            const loader = document.getElementById('wa-submit-loader');

            btn.disabled = true;
            loader.classList.remove('hidden');

            try {
                const formData = new FormData(e.target);
                const data = {
                    phone_number: formData.get('phone_number'),
                    phone_number_id: formData.get('phone_number_id'),
                    business_account_id: formData.get('business_account_id'),
                    access_token: formData.get('access_token'),
                    is_active: 1
                };

                const headers = {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                };
                const token = localStorage.getItem('token');
                if (token) headers['Authorization'] = `Bearer ${token}`;

                const response = await fetch('/api/v1/institute/whatsapp-settings', {
                    method: 'POST',
                    headers: headers,
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (response.ok) {
                    showToast('WhatsApp integration saved successfully!');
                    closeWhatsAppModal();
                } else {
                    showToast(result.message || 'Error saving settings', 'error');
                }
            } catch (error) {
                console.error('Update Request Failed:', error);
                showToast('Something went wrong.', 'error');
            } finally {
                btn.disabled = false;
                loader.classList.add('hidden');
            }
        });
    </script>
@endsection