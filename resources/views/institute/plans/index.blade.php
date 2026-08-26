@extends('layouts.institute')

@section('title', 'Subscription Plans')

@section('content')
<div class="max-w-[1200px] mx-auto pb-2">
    <!-- Header -->
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('institute.profile.index') }}" class="h-8 w-8 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-[#ff6c00] hover:border-orange-500/30 transition-all shadow-sm group">
            <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-slate-800 tracking-tight">Subscription Plans</h1>
            <p class="text-xs text-slate-400 mt-0.5 font-medium">Choose the perfect plan for your institute's growth</p>
        </div>
    </div>



    <!-- Section Title -->
    <div id="plans-section" class="text-center mb-6 max-w-xl mx-auto">
        <h2 class="text-xl font-bold text-slate-800 tracking-tight">Choose the best plan for your campus</h2>
        <p class="text-xs text-slate-400 mt-1">Scalable solutions for individual schools to large university networks.</p>
    </div>

    <!-- Plans Grid Loader (Skeleton Cards) -->
    <div id="plans-loader" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        @for($i = 0; $i < 4; $i++)
        <div class="bg-white rounded-3xl p-6 border border-slate-100/50 shadow-lg flex flex-col items-center justify-center relative overflow-hidden animate-pulse h-48">
            <div class="h-4 bg-slate-100 rounded-md w-1/2 mb-4"></div>
            <div class="h-10 bg-slate-100 rounded-md w-3/4 mb-2"></div>
            <div class="h-3 bg-slate-100 rounded-md w-1/3"></div>
        </div>
        @endfor
    </div>

    <div id="plans-container" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8 hidden">
        <!-- Plans dynamically injected here -->
    </div>

    <!-- Add-On Feature: Mobile App White Label Section (Compact Design) -->
    <div id="addon-section" class="mb-6">
        <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-zinc-900 rounded-2xl p-4 sm:p-5 text-white shadow-xl border border-slate-700/50">
            <!-- Background Glow Effects -->
            <div class="absolute -right-20 -top-20 w-60 h-60 bg-orange-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-60 h-60 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-4 lg:gap-6 items-center">
                <!-- Left Details -->
                <div class="lg:col-span-7">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="px-2.5 py-0.5 bg-gradient-to-r from-[#ff6c00] to-amber-500 text-white text-[9px] font-black uppercase tracking-wider rounded-full shadow-sm">
                            ⭐ Premium Add-On
                        </span>
                        <span id="addon-billing-tag" class="px-2.5 py-0.5 bg-white/10 text-slate-300 text-[9px] font-bold uppercase tracking-wider rounded-full border border-white/10">
                            One Time Purchase
                        </span>
                    </div>

                    <h2 id="addon-title" class="text-lg sm:text-xl font-extrabold text-white tracking-tight leading-tight">
                        Mobile App White Label
                    </h2>
                    
                    <p id="addon-desc" class="text-xs text-slate-300 font-normal leading-relaxed mt-1 max-w-xl">
                        Publish your institute's own branded mobile application on <strong>Google Play Store</strong> and <strong>Apple App Store</strong>. Give students & parents a premium experience with your institute logo and direct access.
                    </p>

                    <!-- Features List Grid -->
                    <div class="flex flex-wrap items-center gap-3 sm:gap-5 mt-3.5 pt-2.5 border-t border-white/10">
                        <div class="flex items-center gap-2 text-xs text-slate-200">
                            <div class="h-5 w-5 rounded-md bg-orange-500/20 text-[#ff6c00] flex items-center justify-center shrink-0 border border-orange-500/30">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="font-semibold text-[11px]">Your Logo on Play Store & App Store</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-200">
                            <div class="h-5 w-5 rounded-md bg-orange-500/20 text-[#ff6c00] flex items-center justify-center shrink-0 border border-orange-500/30">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="font-semibold text-[11px]">Branded Push Notification Channel</span>
                        </div>
                    </div>
                </div>

                <!-- Right Pricing & CTA Card -->
                <div class="lg:col-span-5 bg-white/5 backdrop-blur-md rounded-xl p-3.5 sm:p-4 border border-white/10 flex flex-col items-center text-center justify-between">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="h-6 w-6 rounded-lg bg-gradient-to-tr from-[#ff6c00] to-amber-400 text-white flex items-center justify-center shadow-md shadow-orange-500/20 shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-orange-400">One-Time Lifetime Fee</p>
                    </div>
                    
                    <div class="my-1 flex items-baseline justify-center gap-1.5">
                        <span id="addon-price-display" class="text-2xl sm:text-3xl font-black text-white tracking-tight">₹5,000</span>
                        <span id="addon-period-display" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">/ One Time</span>
                    </div>

                    <p class="text-[10px] text-slate-400 mb-3 leading-tight">
                        Zero recurring commission. Lifetime setup & store submission included.
                    </p>

                    @php
                        $instName = auth('institute')->user()->institute_name ?? 'our Institute';
                        $waMsg = urlencode("Hi Tuoora Support, I would like to request the Mobile App White Label Add-On for {$instName}. Please share the onboarding process.");
                    @endphp

                    <div class="w-full flex">
                        <a href="https://wa.me/919104081291?text={{ $waMsg }}" target="_blank"
                            class="w-full py-2.5 px-4 bg-gradient-to-r from-[#ff6c00] to-orange-500 hover:from-orange-500 hover:to-amber-500 text-white rounded-xl font-bold text-xs uppercase tracking-wider text-center shadow-md shadow-orange-500/20 hover:scale-[1.01] active:scale-95 transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            Request White Label
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Features Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-slate-100/50 p-4 flex items-start gap-3">
            <div class="h-8 w-8 bg-orange-50 text-[#ff6c00] rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <h4 class="text-xs font-bold text-slate-800">Automated Billing</h4>
                <p class="text-[10px] text-slate-400 mt-0.5 leading-relaxed">Streamline your finance department with automated invoices.</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-100/50 p-4 flex items-start gap-3">
            <div class="h-8 w-8 bg-orange-50 text-[#ff6c00] rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div>
                <h4 class="text-xs font-bold text-slate-800">Priority Support</h4>
                <p class="text-[10px] text-slate-400 mt-0.5 leading-relaxed">24/7 access to our specialized support engineers.</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-100/50 p-4 flex items-start gap-3">
            <div class="h-8 w-8 bg-orange-50 text-[#ff6c00] rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <div>
                <h4 class="text-xs font-bold text-slate-800">WhatsApp Integration</h4>
                <p class="text-[10px] text-slate-400 mt-0.5 leading-relaxed">Keep parents and students informed via automated WhatsApp.</p>
            </div>
        </div>
    </div>

    <!-- Recent Billing History -->
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100/50 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-800">Recent Billing History</h2>
            
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-slate-700 border-collapse">
                <thead class="bg-slate-50/75">
                    <tr>
                        <th class="px-4 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Date</th>
                        <th class="px-4 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Plan Name</th>
                        <th class="px-4 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Amount Paid</th>
                        <th class="px-4 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                    </tr>
                </thead>
                <tbody id="billing-history-container" class="divide-y divide-slate-100">
                    <tr>
                        <td colspan="4" class="px-4 py-12 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <div class="h-4 w-4 border-2 border-orange-500/20 border-t-[#ff6c00] rounded-full animate-spin mx-auto mb-2"></div>
                            Loading history...
                        </td>
                    </tr>
                </tbody>
            </table>
    <!-- Beautiful Success/Error Modal -->
    <div id="payment-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
        
        <!-- Modal Card -->
        <div class="relative bg-white rounded-3xl p-8 max-w-sm w-full mx-4 shadow-2xl border border-slate-100/80 transform scale-95 opacity-0 transition-all duration-300 flex flex-col items-center text-center" id="payment-modal-card">
            
            <!-- Success Icon -->
            <div id="modal-icon-success" class="hidden h-16 w-16 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-4 ring-8 ring-emerald-50/50">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <!-- Error Icon -->
            <div id="modal-icon-error" class="hidden h-16 w-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mb-4 ring-8 ring-rose-50/50">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>

            <!-- Title -->
            <h3 id="modal-title" class="text-base font-black text-slate-800 tracking-tight mb-2"></h3>
            
            <!-- Message -->
            <p id="modal-message" class="text-[11px] text-slate-500 font-medium leading-relaxed mb-6"></p>

            <!-- Action Button -->
            <button id="modal-btn" class="w-full py-3 bg-slate-900 hover:bg-[#ff6c00] text-white rounded-xl font-bold text-xs uppercase tracking-widest shadow-lg hover:shadow-orange-500/20 active:scale-95 transition-all">
                OK
            </button>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', fetchAllData);
    
    async function fetchAllData() {
        try {
            const response = await fetch('/api/v1/institute/subscriptions/all-data', { 
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                } 
            });
            const result = await response.json();
            
            if (result.status === 'success') {
                const data = result.data;
                
                // 1. Current Subscription & Capacity
                if (data.subscription) {
                    const sub = data.subscription;
                    const subPlanName = document.getElementById('sub-plan-name');
                    if (subPlanName) subPlanName.innerText = sub.plan_name;
                    
                    const subStatus = document.getElementById('sub-status');
                    if (subStatus) subStatus.innerText = sub.status;
                    
                    const subRenewal = document.getElementById('sub-renewal');
                    if (subRenewal) subRenewal.innerText = (sub.expires_at || sub.end_date) ? new Date(sub.expires_at || sub.end_date).toLocaleDateString() : 'N/A';
                }

                // 2. Plans Grid
                renderPlans(data.plans, data.subscription, data.history);

                // 3. White Label Add-On Details
                renderAddon(data.white_label_addon || (data.addons && data.addons[0] ? data.addons[0] : null));

                // 4. Billing History
                renderHistory(data.history);
            }
        } catch (e) { console.error('Fetch all error:', e); }
    }

    function renderAddon(addon) {
        if (!addon) return;
        const section = document.getElementById('addon-section');
        if (addon.is_active === false) {
            if (section) section.classList.add('hidden');
            return;
        }

        if (section) section.classList.remove('hidden');

        const titleEl = document.getElementById('addon-title');
        const descEl = document.getElementById('addon-desc');
        const priceEl = document.getElementById('addon-price-display');
        const periodEl = document.getElementById('addon-period-display');
        const billingTagEl = document.getElementById('addon-billing-tag');

        if (titleEl && addon.title) titleEl.innerText = addon.title;
        if (descEl && addon.description) descEl.innerHTML = addon.description;
        if (priceEl && (addon.formatted_price || addon.price !== undefined)) {
            priceEl.innerText = addon.formatted_price || ('₹' + parseFloat(addon.price).toLocaleString());
        }
        if (periodEl && addon.billing_type) periodEl.innerText = '/ ' + addon.billing_type;
        if (billingTagEl && addon.billing_type) billingTagEl.innerText = addon.billing_type + ' Purchase';
    }

    function renderPlans(plans, subscription, history) {
        const container = document.getElementById('plans-container');
        const loader = document.getElementById('plans-loader');
        
        const hasUsedFreePlan = history ? history.some(h => h.plan_name.toLowerCase().includes('free')) : false;

        container.innerHTML = '';
        plans.forEach(plan => {
            const nameLower = plan.name.toLowerCase();
            const isFreePlan = nameLower.includes('free') || parseFloat(plan.price) === 0;
            
            if (isFreePlan) {
                return; // Hide free plan
            }

            const card = document.createElement('div');
            card.className = `bg-white rounded-3xl p-6 border border-slate-100 shadow-xl flex flex-col items-center text-center justify-center relative overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:border-orange-200`;
            
            const badgeHtml = '';

            let buttonHtml = '';
            if (isFreePlan && hasUsedFreePlan) {
                buttonHtml = `<button disabled class="w-full mt-6 py-3 bg-slate-50 text-slate-400 rounded-xl font-bold text-[10px] uppercase tracking-widest cursor-not-allowed border border-slate-100">Already Used</button>`;
            } else {
                if (parseFloat(plan.price) === 0) {
                    buttonHtml = `<button id="plan-btn-${plan.id}" onclick="window.location.href='{{ route('institute.subscription.renew.show') }}'" class="w-full mt-6 py-3.5 bg-slate-900 hover:bg-[#ff6c00] text-white rounded-xl font-bold text-[11px] uppercase tracking-widest shadow-lg hover:shadow-orange-500/30 hover:scale-[1.02] active:scale-95 transition-all">Select Plan</button>`;
                } else {
                    buttonHtml = `
                    <div class="flex flex-col gap-2 w-full mt-6">
                        <button id="plan-btn-${plan.id}" onclick="choosePlan(${plan.id})" class="w-full py-3.5 bg-slate-900 hover:bg-[#ff6c00] text-white rounded-xl font-bold text-[11px] uppercase tracking-widest shadow-lg hover:shadow-orange-500/30 hover:scale-[1.02] active:scale-95 transition-all">Pay Online</button>
                        <a href="{{ route('institute.subscription.renew.show') }}" class="w-full py-2.5 text-center bg-slate-50 hover:bg-slate-100 text-slate-600 rounded-xl font-bold text-[10px] uppercase tracking-widest transition-all border border-slate-200">Pay Offline</a>
                    </div>`;
                }
            }

            const textColor = 'text-slate-800';
            const mutedColor = 'text-slate-500';

            card.innerHTML = `
                ${badgeHtml}
                <div class="w-full">
                    <h4 class="text-[11px] font-black uppercase tracking-widest ${mutedColor} mb-3">${plan.name}</h4>
                    <div class="flex flex-col items-center justify-center">
                        <span class="text-4xl font-black ${textColor} tracking-tight">₹${parseFloat(plan.price).toLocaleString()}</span>
                        <span class="text-[9px] font-bold ${mutedColor} tracking-widest mt-2 bg-slate-100/10 px-3 py-1 rounded-full border border-slate-100">/${plan.duration_days} DAYS</span>
                    </div>
                </div>
                ${buttonHtml}
            `;
            container.appendChild(card);
        });
        
        loader.classList.add('hidden');
        container.classList.remove('hidden');
    }

    function renderHistory(history) {
        const container = document.getElementById('billing-history-container');
        container.innerHTML = '';
        
        if (history.length === 0) {
            container.innerHTML = `<tr><td colspan="4" class="px-4 py-8 text-center text-slate-400 font-bold text-[10px] uppercase">No billing history</td></tr>`;
            return;
        }
        
        history.forEach(item => {
            const statusColors = { 
                'active': 'bg-emerald-50 text-emerald-600', 
                'success': 'bg-emerald-50 text-emerald-600', 
                'pending': 'bg-amber-50 text-amber-600',
                'expired': 'bg-rose-50 text-rose-600',
                'inactive': 'bg-rose-50 text-rose-600'
            };
            const date = new Date(item.created_at).toLocaleDateString('en-GB');

            container.innerHTML += `
                <tr class="hover:bg-slate-50/50">
                    <td class="px-4 py-3 text-xs font-medium text-slate-700">${date}</td>
                    <td class="px-4 py-3 text-xs font-bold text-slate-800">${item.plan_name}</td>
                    <td class="px-4 py-3 text-xs font-bold text-slate-800">₹${parseFloat(item.amount).toLocaleString()}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded text-[8px] font-bold uppercase ${statusColors[item.status.toLowerCase()] || 'bg-slate-100'}">${item.status}</span>
                    </td>
                </tr>
            `;
        });
    }

    function showModal(type, title, message, callback = null) {
        const modal = document.getElementById('payment-modal');
        const card = document.getElementById('payment-modal-card');
        const successIcon = document.getElementById('modal-icon-success');
        const errorIcon = document.getElementById('modal-icon-error');
        const titleEl = document.getElementById('modal-title');
        const msgEl = document.getElementById('modal-message');
        const btn = document.getElementById('modal-btn');

        successIcon.classList.add('hidden');
        errorIcon.classList.add('hidden');

        if (type === 'success') {
            successIcon.classList.remove('hidden');
        } else {
            errorIcon.classList.remove('hidden');
        }

        titleEl.innerText = title;
        msgEl.innerText = message;

        btn.onclick = function() {
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                if (callback) callback();
            }, 150);
        };

        modal.classList.remove('hidden');
        modal.offsetHeight; // Force reflow
        card.classList.remove('scale-95', 'opacity-0');
    }

    async function choosePlan(planId) {
        const btn = document.getElementById(`plan-btn-${planId}`);
        const originalText = btn.innerText;
        btn.disabled = true; btn.innerText = 'WAIT...';

        try {
            const headers = { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' };
            const token = localStorage.getItem('token');
            if (token) headers['Authorization'] = `Bearer ${token}`;

            const response = await fetch('/api/v1/institute/subscriptions/purchase', {
                method: 'POST',
                headers: headers,
                body: JSON.stringify({ plan_id: planId })
            });

            const result = await response.json();
            if (!response.ok) throw new Error(result.message || 'Failed');

            const options = {
                "key": result.razorpay_key,
                "amount": result.amount * 100,
                "currency": "INR",
                "name": "FeeEasy",
                "description": "Subscription for " + result.plan_name,
                "order_id": result.razorpay_order_id,
                "prefill": {
                    "name": result.institute_name || "",
                    "email": result.email || "",
                    "contact": result.phone || ""
                },
                "handler": async function (resp) {
                    btn.innerText = 'VERIFY...';
                    const verifyResponse = await fetch('/api/v1/institute/subscriptions/verify-payment', {
                        method: 'POST',
                        headers: headers,
                        body: JSON.stringify({
                            razorpay_order_id: resp.razorpay_order_id || result.razorpay_order_id,
                            razorpay_payment_id: resp.razorpay_payment_id,
                            razorpay_signature: resp.razorpay_signature,
                            razorpay_invoice_id: resp.razorpay_invoice_id || null,
                            plan_id: planId
                        })
                    });

                    if (verifyResponse.ok) {
                        showModal('success', 'Payment Successful!', 'Your payment has been verified and your subscription has been activated.', () => {
                            window.location.reload();
                        });
                    } else {
                        const errResult = await verifyResponse.json();
                        showModal('error', 'Verification Failed', errResult.message || 'Unknown error occurred.');
                    }
                },
                "theme": { "color": "#ff6c00" }
            };
            const rzp = new Razorpay(options);
            rzp.open();

        } catch (error) { 
            showModal('error', 'Payment Error', error.message || 'Something went wrong.'); 
        } finally { 
            btn.disabled = false; btn.innerText = originalText; 
        }
    }
</script>
@endsection
