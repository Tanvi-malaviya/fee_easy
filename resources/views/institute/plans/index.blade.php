@extends('layouts.institute')

@section('title', 'Subscription Plans')

@section('content')
<div class="pt-2">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('institute.profile.index') }}" class="h-10 w-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-600/30 transition-all shadow-sm group">
            <svg class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-800 leading-tight">Subscription Plans</h1>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-1">Choose the perfect plan for your institute's growth</p>
        </div>
    </div>

    <!-- Plans Grid -->
    <div id="plans-loader" class="py-20 flex flex-col items-center justify-center bg-white rounded-3xl border border-slate-100 shadow-sm">
        <div class="h-10 w-10 border-4 border-blue-600/20 border-t-blue-600 rounded-full animate-spin"></div>
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-4">Fetching best plans for you...</p>
    </div>

    <div class="max-w-[1400px] mx-auto">
        <div id="plans-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 hidden">
            <!-- Plans will be injected here -->
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', fetchPlans);

    async function fetchPlans() {
        const loader = document.getElementById('plans-loader');
        const container = document.getElementById('plans-container');
        
        try {
            const response = await fetch('/api/v1/institute/plans', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const result = await response.json();
            
            if (result.status === 'success') {
                container.innerHTML = '';
                result.data.forEach(plan => {
                    const card = document.createElement('div');
                    card.className = 'bg-white p-8 rounded-2xl border border-slate-100 hover:border-blue-600/30 transition-all group/pitem relative overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 duration-300';
                    card.innerHTML = `
                        <div class="absolute -right-8 -bottom-8 h-32 w-32 bg-blue-600/5 rounded-full group-hover/pitem:scale-150 transition-transform duration-700"></div>
                        <div class="relative z-10">
                            <div class="h-12 w-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-6">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <h4 class="text-xs font-black text-blue-600 uppercase tracking-[0.2em] mb-2">${plan.name}</h4>
                            <div class="flex items-baseline gap-1 mb-6">
                                <span class="text-4xl font-black text-slate-800">₹${parseFloat(plan.price).toLocaleString()}</span>
                                <span class="text-xs font-bold text-slate-400 tracking-widest">/${plan.duration_days} DAYS</span>
                            </div>
                            
                            <div class="space-y-4 mb-8">
                                <div class="flex items-center gap-3">
                                    <div class="h-5 w-5 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span class="text-sm font-bold text-slate-600">Full Dashboard Access</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="h-5 w-5 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span class="text-sm font-bold text-slate-600">Student & Batch Management</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="h-5 w-5 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span class="text-sm font-bold text-slate-600">Attendance & Fees Tracking</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="h-5 w-5 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span class="text-sm font-bold text-slate-600">Priority WhatsApp Support</span>
                                </div>
                            </div>

                            <button onclick="choosePlan(${plan.id})" id="plan-btn-${plan.id}" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all shadow-lg shadow-slate-900/10 hover:shadow-blue-600/20 active:scale-[0.98]">
                                Choose Plan
                            </button>
                        </div>
                    `;
                    container.appendChild(card);
                });
                
                loader.classList.add('hidden');
                container.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Fetch Plans Error:', error);
            alert('Failed to load plans.');
        }
    }

    async function choosePlan(planId) {
        const btn = document.getElementById(`plan-btn-${planId}`);
        const originalText = btn.innerText;
        
        btn.disabled = true;
        btn.innerText = 'INITIALIZING...';

        try {
            const headers = {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            };
            const token = localStorage.getItem('token');
            if (token) headers['Authorization'] = `Bearer ${token}`;

            const response = await fetch('/api/v1/institute/subscriptions/purchase', {
                method: 'POST',
                headers: headers,
                body: JSON.stringify({ plan_id: planId })
            });

            const result = await response.json();
            if (!response.ok) throw new Error(result.message || 'Failed to initiate purchase');

            const options = {
                "key": result.razorpay_key,
                "amount": result.amount * 100,
                "currency": "INR",
                "name": "FeeEasy",
                "description": "Subscription for " + result.plan_name,
                "order_id": result.razorpay_order_id,
                "handler": async function (resp) {
                    btn.innerText = 'VERIFYING...';
                    
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

                    const verifyResult = await verifyResponse.json();
                    if (verifyResponse.ok) {
                        alert('Payment successful! Your subscription is now active.');
                        window.location.href = '{{ route("institute.profile.index") }}';
                    } else {
                        alert(verifyResult.message || 'Payment verification failed');
                    }
                    
                    btn.disabled = false;
                    btn.innerText = originalText;
                },
                "prefill": {
                    "name": result.institute_name,
                    "email": result.email,
                    "contact": result.phone
                },
                "theme": {
                    "color": "#1e3a8a"
                },
                "modal": {
                    "ondismiss": function() {
                        btn.disabled = false;
                        btn.innerText = originalText;
                    }
                }
            };
            
            const rzp = new Razorpay(options);
            rzp.open();

        } catch (error) {
            console.error('Plan Purchase Error:', error);
            alert(error.message || 'Something went wrong.');
            btn.disabled = false;
            btn.innerText = originalText;
        }
    }
</script>
@endsection
