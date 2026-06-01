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
                    if (subRenewal) subRenewal.innerText = sub.expires_at ? new Date(sub.expires_at).toLocaleDateString() : 'N/A';
                }

                // 2. Plans Grid
                renderPlans(data.plans, data.subscription, data.history);

                // 3. Billing History
                renderHistory(data.history);
            }
        } catch (e) { console.error('Fetch all error:', e); }
    }

    function renderPlans(plans, subscription, history) {
        const container = document.getElementById('plans-container');
        const loader = document.getElementById('plans-loader');
        
        const hasUsedFreePlan = history ? history.some(h => h.plan_name.toLowerCase().includes('free')) : false;

        container.innerHTML = '';
        plans.forEach(plan => {
            const card = document.createElement('div');
            const nameLower = plan.name.toLowerCase();
            const isActive = subscription && subscription.plan_name && subscription.plan_name.toLowerCase() === nameLower;
            const isFreePlan = nameLower.includes('free');
            
            if (isActive) {
                card.className = `bg-gradient-to-br from-[#ff6c00] to-[#e05f00] rounded-3xl p-6 shadow-2xl flex flex-col items-center text-center justify-between relative overflow-hidden transition-all duration-300 transform md:scale-105 z-10`;
            } else {
                card.className = `bg-white rounded-3xl p-6 border border-slate-100 shadow-xl flex flex-col items-center text-center justify-center relative overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:border-orange-200`;
            }
            
            const badgeHtml = isActive ? `<span class="absolute top-0 inset-x-0 bg-white/20 text-white text-[9px] font-black uppercase tracking-widest py-1.5 backdrop-blur-md shadow-sm">Current Active Plan</span>` : '';

            let buttonHtml = '';
            if (isActive) {
                buttonHtml = `<button disabled class="w-full mt-6 py-3.5 bg-white text-[#ff6c00] rounded-xl font-black text-[11px] uppercase tracking-widest shadow-md">Active Plan</button>`;
            } else if (isFreePlan && hasUsedFreePlan) {
                buttonHtml = `<button disabled class="w-full mt-6 py-3 bg-slate-50 text-slate-400 rounded-xl font-bold text-[10px] uppercase tracking-widest cursor-not-allowed border border-slate-100">Already Used</button>`;
            } else {
                const hasActiveSubscription = subscription && subscription.status && subscription.status.toLowerCase() === 'active';
                if (!hasActiveSubscription) {
                    buttonHtml = `<button id="plan-btn-${plan.id}" onclick="window.location.href='{{ route('institute.subscription.renew.show') }}'" class="w-full mt-6 py-3.5 bg-slate-900 hover:bg-[#ff6c00] text-white rounded-xl font-bold text-[11px] uppercase tracking-widest shadow-lg hover:shadow-orange-500/30 hover:scale-[1.02] active:scale-95 transition-all">Select Plan</button>`;
                }
            }

            const textColor = isActive ? 'text-white' : 'text-slate-800';
            const mutedColor = isActive ? 'text-white/90' : 'text-slate-500';

            card.innerHTML = `
                ${badgeHtml}
                <div class="w-full ${isActive ? 'mt-4' : ''}">
                    <h4 class="text-[11px] font-black uppercase tracking-widest ${mutedColor} mb-3">${plan.name}</h4>
                    <div class="flex flex-col items-center justify-center">
                        <span class="text-4xl font-black ${textColor} tracking-tight">₹${parseFloat(plan.price).toLocaleString()}</span>
                        <span class="text-[9px] font-bold ${mutedColor} tracking-widest mt-2 bg-slate-100/10 px-3 py-1 rounded-full border ${isActive ? 'border-white/20' : 'border-slate-100'}">/${plan.duration_days} DAYS</span>
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
                "handler": async function (resp) {
                    btn.innerText = 'VERIFY...';
                    const verifyResponse = await fetch('/api/v1/institute/subscriptions/verify-payment', {
                        method: 'POST',
                        headers: headers,
                        body: JSON.stringify({
                            razorpay_order_id: resp.razorpay_order_id,
                            razorpay_payment_id: resp.razorpay_payment_id,
                            razorpay_signature: resp.razorpay_signature,
                            plan_id: planId
                        })
                    });

                    if (verifyResponse.ok) {
                        alert('Payment successful!');
                        window.location.reload();
                    } else { alert('Verification failed'); }
                },
                "theme": { "color": "#ff6c00" }
            };
            const rzp = new Razorpay(options);
            rzp.open();

        } catch (error) { alert(error.message || 'Error'); }
        finally { btn.disabled = false; btn.innerText = originalText; }
    }
</script>
@endsection
