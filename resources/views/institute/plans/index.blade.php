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

        <!-- Recent Billing History Section -->
        <div class="mt-6">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 leading-tight">Recent Billing History</h2>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-1">Track your previous transactions and subscription statuses</p>
                </div>
            </div>

            <div class="bg-white rounded-[1rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-4 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Subscription Plan</th>
                                <th class="px-4 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Amount Paid</th>
                                <th class="px-4 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                                <th class="px-4 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Date & Time</th>
                            </tr>
                        </thead>
                        <tbody id="billing-history-container">
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="h-8 w-8 border-3 border-blue-600/20 border-t-blue-600 rounded-full animate-spin mb-4"></div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Loading history...</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div id="billing-pagination" class="px-8 py-5 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between hidden">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        Showing page <span id="current-page-num" class="text-slate-600">1</span> of <span id="total-pages-num" class="text-slate-600">1</span>
                    </p>
                    <div class="flex items-center gap-2">
                        <button onclick="changeBillingPage('prev')" id="prev-page-btn" class="h-10 w-10 flex items-center justify-center rounded-xl border border-slate-100 bg-white text-slate-400 hover:text-blue-600 hover:border-blue-600/20 transition-all disabled:opacity-30 disabled:pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button onclick="changeBillingPage('next')" id="next-page-btn" class="h-10 w-10 flex items-center justify-center rounded-xl border border-slate-100 bg-white text-slate-400 hover:text-blue-600 hover:border-blue-600/20 transition-all disabled:opacity-30 disabled:pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        fetchPlans();
        fetchBillingHistory();
    });

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
                    card.className = 'bg-white p-6 rounded-2xl border border-slate-100 hover:border-blue-600/30 transition-all group/pitem relative overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 duration-300';
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

    let currentBillingPage = 1;
    let totalBillingPages = 1;

    async function fetchBillingHistory(page = 1) {
        const container = document.getElementById('billing-history-container');
        const pagination = document.getElementById('billing-pagination');
        
        try {
            const token = localStorage.getItem('token');
            const headers = { 'X-Requested-With': 'XMLHttpRequest' };
            if (token) headers['Authorization'] = `Bearer ${token}`;

            const response = await fetch(`/api/v1/institute/subscriptions/history?page=${page}`, { headers });
            const result = await response.json();
            
            if (result.status === 'success') {
                container.innerHTML = '';
                
                if (result.data.length === 0) {
                    container.innerHTML = `
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="h-12 w-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 mb-4">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <p class="text-sm font-black text-slate-400 uppercase tracking-widest">No billing history found</p>
                                </div>
                            </td>
                        </tr>
                    `;
                    pagination.classList.add('hidden');
                    return;
                }
                
                // Update Pagination Controls
                currentBillingPage = result.meta.current_page;
                totalBillingPages = result.meta.last_page;
                
                document.getElementById('current-page-num').innerText = currentBillingPage;
                document.getElementById('total-pages-num').innerText = totalBillingPages;
                document.getElementById('prev-page-btn').disabled = currentBillingPage === 1;
                document.getElementById('next-page-btn').disabled = currentBillingPage === totalBillingPages;
                
                if (totalBillingPages > 1) pagination.classList.remove('hidden');
                else pagination.classList.add('hidden');

                result.data.forEach(item => {
                    const statusColors = {
                        'active': 'bg-emerald-50 text-emerald-600 border-emerald-100',
                        'success': 'bg-emerald-50 text-emerald-600 border-emerald-100',
                        'pending': 'bg-amber-50 text-amber-600 border-amber-100',
                        'failed': 'bg-rose-50 text-rose-600 border-rose-100'
                    };
                    const statusColor = statusColors[item.status.toLowerCase()] || 'bg-slate-50 text-slate-600 border-slate-100';
                    
                    const date = new Date(item.created_at);
                    const formattedDate = date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
                    const formattedTime = date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });

                    container.innerHTML += `
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-5 border-t border-slate-50">
                                <p class="text-sm font-black text-slate-700">${item.plan_name}</p>
                            </td>
                            <td class="px-8 py-5 border-t border-slate-50">
                                <p class="text-sm font-black text-slate-700">₹${parseFloat(item.amount).toLocaleString()}</p>
                            </td>
                            <td class="px-8 py-5 border-t border-slate-50">
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border ${statusColor}">
                                    ${item.status}
                                </span>
                            </td>
                            <td class="px-8 py-5 border-t border-slate-50">
                                <p class="text-sm font-bold text-slate-700">${formattedDate}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">${formattedTime}</p>
                            </td>
                        </tr>
                    `;
                });
            }
        } catch (error) {
            console.error('Billing History Error:', error);
            container.innerHTML = '<tr><td colspan="4" class="px-8 py-20 text-center text-rose-500 font-bold">Failed to load history.</td></tr>';
        }
    }

    function changeBillingPage(direction) {
        if (direction === 'next' && currentBillingPage < totalBillingPages) {
            fetchBillingHistory(currentBillingPage + 1);
        } else if (direction === 'prev' && currentBillingPage > 1) {
            fetchBillingHistory(currentBillingPage - 1);
        }
    }
</script>
@endsection
