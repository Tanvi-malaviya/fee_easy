@extends('layouts.institute')

@section('content')
<div class="max-w-[1200px] mx-auto pb-6 pt-6">

    <!-- Premium Profile Header -->
    <div class="bg-white rounded-[1rem] shadow-xl border border-slate-100/50 overflow-hidden relative mb-4 animate-in fade-in slide-in-from-top-4 duration-500">
        <!-- Banner -->
        <div class="h-16 bg-gradient-to-r from-[#e05f00] via-[#ff6c00] to-[#ff9f43] relative"></div>
        
        <!-- Profile Info -->
        <div class="px-6 pb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 relative">
            <div class="flex flex-col md:flex-row items-start md:items-center gap-4 -mt-12 relative z-10">
                <!-- Logo Box -->
                <div class="h-28 w-28 bg-white rounded-2xl p-1.5 shadow-2xl border border-slate-100 flex items-center justify-center overflow-hidden shrink-0">
                    <img id="profile-logo-preview" 
                         src="{{ auth()->guard('institute')->user()->logo ? asset('storage/' . auth()->guard('institute')->user()->logo) : 'https://ui-avatars.com/?name=' . urlencode(auth()->guard('institute')->user()->name) . '&background=ff6c00&color=fff' }}" 
                         class="w-full h-full object-cover rounded-xl">
                </div>
                
                <div class="md:pt-12">
                    <h1 id="view-institute_name" class="text-2xl font-[550] text-slate-800 tracking-tight">{{ auth()->guard('institute')->user()->name }}</h1>
                    <div class="flex flex-wrap items-center gap-3 mt-1 text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span id="view-city" class="city-text">{{ auth()->guard('institute')->user()->city ?? 'Location' }}</span>
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span id="view-email" class="email-text">{{ auth()->guard('institute')->user()->email }}</span>
                        </span>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('institute.profile.edit') }}" class="px-5 py-2.5 bg-[#e05f00] hover:bg-[#c44f00] text-white rounded-xl font-bold text-[10px] uppercase tracking-widest shadow-lg shadow-orange-500/20 hover:scale-[1.02] active:scale-95 transition-all md:pt-3 flex items-center justify-center h-fit">
                Edit Profile
            </a>
        </div>
    </div>

    <!-- View Mode Section -->
    <div id="profile-view-section" class="grid grid-cols-1 lg:grid-cols-2 gap-6 animate-in fade-in duration-300">
        <!-- Account Management -->
        <div class="space-y-3">
            <h2 class="text-xl font-medium text-slate-800 tracking-tight">Account Management</h2>
            
            <div class="bg-white rounded-[1rem] shadow-xl border border-slate-100/50 overflow-hidden divide-y divide-slate-50">
                <!-- Password & Security -->
                <button onclick="openPasswordModal()" class="w-full py-2.5 px-5 flex items-center justify-between hover:bg-slate-50 transition-colors group text-left">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 leading-tight">Password & Security</h3>
                            <p class="text-[10px] text-slate-400 font-medium mt-0.5">Update credentials and 2FA</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>

                <!-- Subscription Plan -->
                <a href="{{ route('institute.plans.index') }}" class="py-2.5 px-5 flex items-center justify-between hover:bg-slate-50 transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 leading-tight">Subscription Plan</h3>
                            <p class="text-[10px] text-slate-400 font-medium mt-0.5">Manage your active tier and billing</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>

                <!-- WhatsApp Integration -->
                <button type="button" onclick="openWhatsAppModal()" class="w-full py-2.5 px-5 flex items-center justify-between hover:bg-slate-50 transition-colors group text-left">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 leading-tight">WhatsApp Integration</h3>
                            <p class="text-[10px] text-slate-400 font-medium mt-0.5">Automate alerts via Meta API</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>
                
                <!-- Terms & Conditions -->
                <button class="w-full py-2.5 px-5 flex items-center justify-between hover:bg-slate-50 transition-colors group text-left">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 leading-tight">Terms & Conditions</h3>
                            <p class="text-[10px] text-slate-400 font-medium mt-0.5">Read our terms of service</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>

                <!-- Privacy Policy -->
                <button class="w-full py-2.5 px-5 flex items-center justify-between hover:bg-slate-50 transition-colors group text-left">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 leading-tight">Privacy Policy</h3>
                            <p class="text-[10px] text-slate-400 font-medium mt-0.5">Learn how we protect your data</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>

                <!-- Help & Support -->
                <button class="w-full py-2.5 px-5 flex items-center justify-between hover:bg-slate-50 transition-colors group text-left">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 leading-tight">Help & Support</h3>
                            <p class="text-[10px] text-slate-400 font-medium mt-0.5">Contact us or view FAQ</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>

        <!-- Subscription Overview -->
        <div class="space-y-3">
            <h2 class="text-lg font-[550] text-slate-800 tracking-tight">Subscription Overview</h2>
            
            <div class="bg-white rounded-[1rem] shadow-xl border border-slate-100/50 p-6 relative overflow-hidden h-fit">
                <div class="flex items-start justify-between">
                    <div>
                        <span id="badge-sub-status" class="text-[8px] bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-full font-black uppercase tracking-widest border border-emerald-100">
                            Active Plan
                        </span>
                        <h3 id="plan-title" class="text-xl font-[550] text-slate-800 tracking-tight mt-2">Loading Plan...</h3>
                        <p id="plan-tier" class="text-[11px] text-slate-400 font-medium mt-0.5">Tier: Standard</p>
                    </div>
                    <div class="h-10 w-10 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </div>
                </div>

                <!-- Capacity Usage -->
                <div class="mt-6 bg-slate-50 rounded-xl p-4 border border-slate-100">
                    <div class="flex items-center justify-between text-[11px] font-bold text-slate-500">
                        <span>Student Capacity</span>
                        <span id="capacity-text" class="text-lg font-[550] text-slate-800">0 <span class="text-xs text-slate-400 font-medium">/ 1,000 enrolled</span></span>
                    </div>
                    <div class="mt-2 h-1.5 bg-slate-200 rounded-full overflow-hidden">
                        <div id="capacity-bar" class="h-full bg-gradient-to-r from-[#e05f00] to-[#ff6c00] rounded-full" style="width: 0%;"></div>
                    </div>
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-2 gap-3 mt-6">
                    <div class="flex items-center gap-2.5">
                        <div class="h-8 w-8 bg-blue-50 text-blue-500 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Next Renewal</p>
                            <p id="sub-renewal" class="text-[11px] font-bold text-slate-700 mt-0.5">N/A</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="h-8 w-8 bg-orange-50 text-orange-500 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Member Since</p>
                            <p id="sub-created" class="text-[11px] font-bold text-slate-700 mt-0.5">N/A</p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('institute.plans.index') }}" class="w-full mt-6 py-3 bg-[#e05f00] hover:bg-[#c44f00] text-white rounded-xl font-bold text-xs uppercase tracking-widest shadow-md transition-all flex items-center justify-center gap-2">
                    Upgrade Plan
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Mode Section -->
    <div id="profile-edit-section" class="hidden animate-in fade-in duration-300">
        <div class="bg-white rounded-[1.5rem] shadow-xl border border-slate-100/50 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-[550] text-slate-800">Edit Institute Profile</h3>
                <button onclick="closeEditMode()" class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-500 rounded-xl font-bold text-xs transition-colors">
                    Back to View
                </button>
            </div>
            
            <form id="profile-form" class="space-y-4" enctype="multipart/form-data">
                @csrf
                
                <div class="flex items-center gap-4 border-b pb-4 border-slate-50">
                    <div class="relative group cursor-pointer" onclick="document.getElementById('logo-input').click()">
                        <img id="modal-logo-preview" 
                             src="{{ auth()->guard('institute')->user()->logo ? asset('storage/' . auth()->guard('institute')->user()->logo) : 'https://ui-avatars.com/?name=' . urlencode(auth()->guard('institute')->user()->name) . '&background=ff6c00&color=fff' }}" 
                             class="h-16 w-16 rounded-2xl object-cover border-2 border-slate-100 shadow-sm transition-transform group-hover:scale-105">
                        <div class="absolute inset-0 bg-black/40 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-700">Institute Logo</h4>
                        <p class="text-[10px] text-slate-400 mt-0.5">Click to upload image</p>
                    </div>
                    <input type="file" id="logo-input" name="logo" class="hidden" accept="image/*" onchange="previewLogo(this)">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Institute Name</label>
                        <input type="text" name="institute_name" id="field-institute_name" placeholder="Enter Institute Name" class="input">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Owner Name</label>
                        <input type="text" name="name" id="field-name" placeholder="Enter Owner Name" class="input">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                        <input type="email" name="email" id="field-email" placeholder="email@example.com" class="input">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone Number</label>
                        <input type="text" name="phone" id="field-phone" placeholder="Phone Number" class="input">
                    </div>
                    <div class="md:col-span-2 space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Address</label>
                        <input type="text" name="address" id="field-address" placeholder="Flat, House no., Building" class="input">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">City</label>
                        <input type="text" name="city" id="field-city" placeholder="City" class="input">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">State</label>
                        <input type="text" name="state" id="field-state" placeholder="State" class="input">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeEditMode()" class="px-5 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-xl font-bold text-xs uppercase tracking-widest transition-all">
                        Cancel
                    </button>
                    <button type="submit" id="save-profile-btn" class="px-8 py-2.5 bg-[#ff6c00] text-white rounded-xl font-bold text-xs uppercase tracking-widest shadow-lg shadow-orange-500/10 hover:scale-[1.01] transition-all flex items-center justify-center gap-2">
                        <span>Save Changes</span>
                        <div id="save-loader" class="h-4 w-4 border-2 border-white/20 border-t-white rounded-full animate-spin hidden"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Password Modal (Remains as modal since it's a small action) -->
<div id="password-modal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
        <!-- Header -->
        <div class="py-2 px-4 border-b border-slate-100 flex items-start justify-between relative">
            <div>
                <h3 class="text-base font-bold text-slate-800 leading-tight">Update Password</h3>
                <p class="text-[10px] text-slate-400 mt-0.5">Ensure your account stays secure with a strong password.</p>
            </div>
            <button onclick="closePasswordModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        
        <!-- Form -->
        <form id="password-form" class="pt-1 pb-4 px-4 space-y-3">
            @csrf
            
            <!-- Current Password -->
            <div class="">
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Current Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 7a2 2 0 012 2m-2 4a5 5 0 111.707-9.707l3.707 3.707A1 1 0 0121 4v3h-2v2h-2v2h-2.293A5 5 0 0115 13zm-5-4a1 1 0 100-2 1 1 0 000 2z"/></svg>
                    </span>
                    <input type="password" name="current_password" placeholder="Enter current password" required class="input-with-icon">
                </div>
            </div>

            <!-- New Password -->
            <div class="space-y-1">
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">New Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                    <input type="password" name="password" placeholder="Enter new password" required class="input-with-icon">
                </div>
                <p class="text-[9px] text-slate-400 flex items-center gap-1 ml-1 mt-0.5">
                    <svg class="w-3 h-3 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Min 8 characters
                </p>
            </div>

            <!-- Confirm New Password -->
            <div class="space-y-1">
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Confirm New Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </span>
                    <input type="password" name="password_confirmation" placeholder="Re-enter new password" required class="input-with-icon">
                </div>
            </div>

            <!-- Password Recommendation Alert -->
            <div class="bg-orange-50/50 rounded-xl p-2.5 border border-orange-100 flex items-start gap-2 mt-1">
                <div class="h-4 w-4 bg-orange-100 text-[#ff6c00] rounded flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 5"/></svg>
                </div>
                <div>
                    <h4 class="text-[9px] font-black text-[#ff6c00] uppercase tracking-widest">Password Recommendation</h4>
                    <p class="text-[9px] text-slate-500 font-medium leading-relaxed mt-0.5">Use a combination of uppercase, lowercase, numbers, and special characters for maximum security.</p>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100 mt-2">
                <button type="button" onclick="closePasswordModal()" class="text-[11px] font-bold text-slate-400 hover:text-slate-600 transition-colors">
                    Discard Changes
                </button>
                <button type="submit" id="submit-btn" class="px-4 py-2 bg-[#ff6c00] hover:bg-[#e05f00] text-white rounded-lg font-bold text-[10px] uppercase tracking-widest shadow-md hover:scale-[1.01] transition-all flex items-center justify-center gap-1.5">
                    <span>Update Password</span>
                    <div id="pwd-loader" class="h-3 w-3 border-2 border-white/20 border-t-white rounded-full animate-spin hidden"></div>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- WhatsApp Modal -->
<div id="whatsapp-modal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
        <!-- Header -->
        <div class="py-2 px-4 border-b border-slate-100 flex items-start justify-between relative">
            <div>
                <h3 class="text-base font-bold text-slate-800 leading-tight">WhatsApp Integration</h3>
                <p class="text-[10px] text-slate-400 mt-0.5">Connect your Meta WhatsApp Cloud API credentials.</p>
            </div>
            <button onclick="closeWhatsAppModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        
        <!-- Form -->
        <form id="whatsapp-modal-form" class="pb-4 px-4 space-y-3">
            @csrf
            <div id="wa-loader" class="py-4 flex flex-col items-center justify-center">
                <div class="h-4 w-4 border-2 border-orange-500/20 border-t-[#ff6c00] rounded-full animate-spin"></div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-2">Loading credentials...</p>
            </div>

            <div id="wa-form-content" class="space-y-2 hidden">
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">WhatsApp Phone Number</label>
                    <input type="text" name="phone_number" id="wa-phone_number" required class="input-wa" placeholder="e.g. 919876543210">
                    <p class="text-[8px] text-slate-400 font-medium ml-1">Include country code without +</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone Number ID</label>
                        <input type="text" name="phone_number_id" id="wa-phone_number_id" required class="input-wa" placeholder="e.g. 1098425...">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Business ID</label>
                        <input type="text" name="business_account_id" id="wa-business_account_id" required class="input-wa" placeholder="e.g. 1530948...">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Access Token</label>
                    <textarea name="access_token" id="wa-access_token" rows="3" required class="textarea-wa" placeholder="EAAW..."></textarea>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_active" id="wa-is_active" value="1" class="rounded border-slate-200 text-[#ff6c00] focus:ring-[#ff6c00]/20 h-3.5 w-3.5">
                    <label for="wa-is_active" class="text-[10px] font-bold text-slate-600">Activate Integration</label>
                </div>

                <!-- Footer Buttons -->
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100 mt-2">
                    <button type="button" onclick="closeWhatsAppModal()" class="text-[11px] font-bold text-slate-400 hover:text-slate-600 transition-colors">
                        Discard
                    </button>
                    <button type="submit" id="wa-submit-btn" class="px-4 py-2 bg-[#ff6c00] hover:bg-[#e05f00] text-white rounded-lg font-bold text-[10px] uppercase tracking-widest shadow-md hover:scale-[1.01] transition-all flex items-center justify-center gap-1.5">
                        <span>Save Integration</span>
                        <div id="wa-submit-loader" class="h-3 w-3 border-2 border-white/20 border-t-white rounded-full animate-spin hidden"></div>
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
.input-wa:focus, .textarea-wa:focus {
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
.input-with-icon::placeholder { color: #cbd5e1; font-weight: 500; }
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
                if (data.logo_url) {
                    document.getElementById('profile-logo-preview').src = data.logo_url;
                    document.getElementById('modal-logo-preview').src = data.logo_url;
                }

                document.getElementById('view-institute_name').innerText = data.institute_name || data.name || 'Institute';
                document.getElementById('view-city').innerText = data.city || 'Location';
                document.getElementById('view-email').innerText = data.email || '';

                const sub = result.subscription;
                const badgeSub = document.getElementById('badge-sub-status');
                const planTitle = document.getElementById('plan-title');
                if (sub) {
                    badgeSub.innerText = sub.status.toUpperCase();
                    planTitle.innerText = sub.plan_name;
                    document.getElementById('plan-tier').innerText = `Tier: ${sub.plan_tier || 'Standard'}`;
                    document.getElementById('sub-renewal').innerText = sub.expires_at ? new Date(sub.expires_at).toLocaleDateString() : 'N/A';
                    document.getElementById('sub-created').innerText = sub.created_at ? new Date(sub.created_at).toLocaleDateString() : 'N/A';
                }

                // Populate form fields
                document.getElementById('field-institute_name').value = data.institute_name || '';
                document.getElementById('field-name').value = data.name || '';
                document.getElementById('field-email').value = data.email || '';
                document.getElementById('field-phone').value = data.phone || '';
                document.getElementById('field-address').value = data.address || '';
                document.getElementById('field-city').value = data.city || '';
                document.getElementById('field-state').value = data.state || '';
            }
        } catch (error) { console.error('Error fetching profile:', error); }
    }

    document.getElementById('profile-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('save-profile-btn');
        const loader = document.getElementById('save-loader');
        btn.disabled = true; loader.classList.remove('hidden');

        try {
            const formData = new FormData(e.target);
            const response = await fetch('/api/v1/institute/profile/update', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Authorization': `Bearer ${localStorage.getItem('token')}` }
            });
            if (response.ok) { 
                showToast('Profile updated successfully!'); 
                closeEditMode();
                fetchProfile(); 
            } else { 
                showToast('Error updating profile', 'error'); 
            }
        } catch (error) { showToast('Something went wrong.', 'error'); }
        finally { btn.disabled = false; loader.classList.add('hidden'); }
    });

    function previewLogo(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('profile-logo-preview').src = e.target.result;
                document.getElementById('modal-logo-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function openEditMode() { 
        document.getElementById('profile-view-section').classList.add('hidden'); 
        document.getElementById('profile-edit-section').classList.remove('hidden'); 
    }
    
    function closeEditMode() { 
        document.getElementById('profile-edit-section').classList.add('hidden'); 
        document.getElementById('profile-view-section').classList.remove('hidden'); 
    }
    
    function openPasswordModal() { document.getElementById('password-modal').classList.replace('hidden', 'flex'); document.body.style.overflow = 'hidden'; }
    function closePasswordModal() { document.getElementById('password-modal').classList.replace('flex', 'hidden'); document.body.style.overflow = 'auto'; }

    document.getElementById('password-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('submit-btn');
        const loader = document.getElementById('pwd-loader');
        btn.disabled = true;
        if (loader) loader.classList.remove('hidden');
        
        try {
            const response = await fetch('{{ route("institute.profile.password.update") }}', {
                method: 'POST',
                body: new FormData(e.target),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (response.ok) { 
                showToast('Password updated successfully!'); 
                closePasswordModal(); 
                e.target.reset();
            } else { 
                const data = await response.json();
                showToast(data.message || 'Error updating password', 'error'); 
            }
        } catch (error) { showToast('Something went wrong.', 'error'); }
        finally { 
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
                is_active: formData.get('is_active') === '1' ? 1 : 0
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