<x-admin-layout title="Subscription Management">
    <style>
        /* Hide number input spinners */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>

    <div class="">
        <div class="max-w-7xl mx-auto">
        
            @if(isset($pendingRenewals) && $pendingRenewals->count() > 0)
                <!-- Pending Renewal Requests Panel -->
                <div class="mb-8 bg-gradient-to-br from-amber-50/40 to-orange-50/20 border border-amber-200/60 rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="h-9 w-9 bg-amber-100 text-amber-800 rounded-xl flex items-center justify-center shrink-0 border border-amber-200/50 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-800 tracking-tight">Manual Offline Renewal Verification</h3>
                            <p class="text-[10px] text-slate-400 font-medium">Verify transaction references and activate subscriptions</p>
                        </div>
                        <span class="ml-auto px-2.5 py-1 bg-amber-500 text-white text-[10px] font-black rounded-lg uppercase tracking-wider shadow-sm shadow-amber-500/10">
                            {{ $pendingRenewals->count() }} Pending
                        </span>
                    </div>

                    <div class="bg-white border border-slate-100 rounded-xl overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead class="bg-slate-50/50">
                                    <tr>
                                        <th class="px-5 py-3.5 text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider">Institute Details</th>
                                        <th class="px-5 py-3.5 text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider">Transaction ID</th>
                                        <th class="px-5 py-3.5 text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider">Payment Proof</th>
                                        <th class="px-5 py-3.5 text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider">Message</th>
                                        <th class="px-5 py-3.5 text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider">Submitted</th>
                                        <th class="px-5 py-3.5 text-right text-[10px] font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100">
                                    @foreach($pendingRenewals as $renewal)
                                        <tr class="hover:bg-slate-50/40 transition-colors">
                                            <td class="px-5 py-4 whitespace-nowrap">
                                                <div class="text-xs font-black text-slate-800 leading-tight">{{ $renewal->institute->institute_name }}</div>
                                                <div class="text-[10px] text-slate-400 font-bold mt-1">ID: ST-{{ sprintf('%04d', $renewal->institute->id) }} | Code: {{ $renewal->institute->institute_code }}</div>
                                            </td>
                                            <td class="px-5 py-4 whitespace-nowrap">
                                                <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[10px] font-mono font-bold rounded-lg border border-slate-200">
                                                    {{ $renewal->transaction_id }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 whitespace-nowrap">
                                                @if($renewal->screenshot)
                                                    <a href="{{ Storage::url($renewal->screenshot) }}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-primary/10 hover:bg-primary/20 text-primary text-[10px] font-bold rounded-lg transition-all border border-primary/20">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                        View Screenshot Proof
                                                    </a>
                                                @else
                                                    <span class="text-[10px] text-slate-400 italic">No proof image</span>
                                                @endif
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="text-xs text-slate-600 font-medium max-w-[200px] truncate" title="{{ $renewal->message ?? 'No notes' }}">
                                                    {{ $renewal->message ?? '-' }}
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 whitespace-nowrap">
                                                <div class="text-[10px] font-bold text-slate-700">{{ $renewal->created_at->format('d M, Y') }}</div>
                                                <div class="text-[9px] text-slate-400 font-medium mt-0.5">{{ $renewal->created_at->diffForHumans() }}</div>
                                            </td>
                                            <td class="px-5 py-4 whitespace-nowrap text-right">
                                                <div class="flex justify-end items-center gap-2">
                                                    <!-- Approve Button Triggers Modal -->
                                                    <button @click="$dispatch('open-modal', 'approve-renewal-{{ $renewal->id }}')" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-[10px] font-black uppercase tracking-wider rounded-lg shadow-md shadow-emerald-500/10 hover:shadow-lg transition-all active:scale-95">
                                                        Approve
                                                    </button>
 
                                                    <!-- Reject Form -->
                                                    <form action="{{ route('subscriptions.renewals.reject', $renewal) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to reject this manual renewal proof?')">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100 text-[10px] font-black uppercase tracking-wider rounded-lg transition-all active:scale-95">
                                                            Reject
                                                        </button>
                                                    </form>
                                                </div>
 
                                                <!-- Approval Dropdown Modal for Plan Selection -->
                                                <x-modal name="approve-renewal-{{ $renewal->id }}" :show="false" focusable>
                                                    <form method="post" action="{{ route('subscriptions.renewals.approve', $renewal) }}" class="p-5 text-left">
                                                        @csrf @method('PATCH')
                                                        <h2 class="text-base font-bold text-slate-900 leading-tight">Approve Subscription Renewal</h2>
                                                        <p class="mt-1 text-[10px] text-slate-500 font-medium border-b border-slate-50 pb-2.5">
                                                            Select the package to activate for <strong>{{ $renewal->institute->institute_name }}</strong>.
                                                        </p>
 
                                                        <div class="mt-4">
                                                            <x-input-label for="plan_id" value="Select Billing Plan" class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-1" />
                                                            <select name="plan_id" class="mt-1 block w-full border-gray-100 bg-gray-50/50 focus:border-primary focus:ring-primary rounded-xl shadow-sm text-sm font-bold py-2.5 px-3 transition outline-none" required>
                                                                @foreach($plans as $plan)
                                                                    <option value="{{ $plan->id }}">{{ $plan->name }} - {{ $currency }}{{ number_format($plan->price, 0) }} ({{ $plan->duration_days }} days)</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
 
                                                        <div class="mt-5 flex justify-end gap-2">
                                                            <button type="button" x-on:click="$dispatch('close')" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-3">Cancel</button>
                                                            <button type="submit" onclick="showBtnLoader(this)" class="relative inline-flex items-center justify-center bg-emerald-500 text-white px-5 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-emerald-500/20 hover:opacity-90 transition min-w-[120px]">
                                                                <span class="btn-content">Activate Plan</span>
                                                                <span class="hidden btn-loader">
                                                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                    </svg>
                                                                </span>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </x-modal>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Filters & Search -->
            <div class=" rounded-2xl  mb-3">
                <form id="search-form" action="{{ route('subscriptions.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none" id="search-icon">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center hidden" id="search-loader">
                            <svg class="animate-spin h-5 w-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <input type="text" id="search-input" name="search" value="{{ request('search') }}" autocomplete="off"
                            placeholder="Search by institute or owner name..." 
                            class="block w-full pl-10 pr-24 py-2.5 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm transition">
                        <!-- Search Button inside input -->
                        <div class="absolute inset-y-0 right-0 flex items-center pr-1">
                            <button type="submit" class="no-loader inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary hover:opacity-90 text-white text-xs font-semibold rounded-lg transition">
                                Search
                            </button>
                        </div>
                    </div>
                    
                    <div class="w-full md:w-48">
                        <select name="status" onchange="this.form.submit()" 
                            class="block w-full pl-3 pr-10 py-2.5 text-sm border border-gray-200 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary rounded-xl bg-white transition font-medium text-gray-700 cursor-pointer outline-none">
                            <option value="all">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="trial" {{ request('status') == 'trial' ? 'selected' : '' }}>Trial</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" @click="$dispatch('open-modal', 'assign-plan')"
                            class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-xl shadow-lg shadow-primary/20 text-sm font-bold text-white bg-primary hover:opacity-90 focus:outline-none transition transform active:scale-95 whitespace-nowrap">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Assign New Plan
                        </button>
                        <!-- @if(request()->has('search') || request()->has('status'))
                            <a href="{{ route('subscriptions.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 hover:text-red-800 transition">
                                Clear
                            </a>
                        @endif -->
                    </div>
                </form>
            </div>

            <!-- Management Table -->
            <div class="relative bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <!-- Table Loading Overlay -->
                <div id="table-loader" class="hidden absolute inset-0 bg-white/70 backdrop-blur-sm rounded-2xl z-10 flex items-center justify-center">
                    <div class="flex flex-col items-center gap-3">
                        <svg class="animate-spin h-8 w-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-xs font-semibold text-primary uppercase tracking-widest">Searching...</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Institute</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Plan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Validity</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($subscriptions as $subscription)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 leading-tight">{{ $subscription->institute->institute_name }}</div>
                                        <div class="text-xs text-gray-500 font-medium mt-0.5">{{ $subscription->institute->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $subscription->plan_name }}</div>
                                        <div class="text-[10px] text-primary font-bold uppercase tracking-wider mt-1">{{ $currency }}{{ number_format($subscription->amount, 0) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Expires On</span>
                                            <span class="text-sm font-bold text-gray-900 mt-0.5">{{ \Carbon\Carbon::parse($subscription->end_date)->format('d M, Y') }}</span>
                                            
                                            @php
                                                $endDate = \Carbon\Carbon::parse($subscription->end_date)->startOfDay();
                                                $today = \Carbon\Carbon::today();
                                                $daysLeft = $today->diffInDays($endDate, false);
                                            @endphp
                                            @if($daysLeft > 0)
                                                <div class="inline-flex items-center mt-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full {{ strtolower($subscription->status) == 'trial' ? 'bg-primary' : 'bg-emerald-500' }} mr-2"></span>
                                                    <span class="text-[10px] font-bold {{ strtolower($subscription->status) == 'trial' ? 'text-primary' : 'text-emerald-600' }} uppercase tracking-wider">
                                                        {{ $daysLeft }} Days {{ strtolower($subscription->status) == 'trial' ? 'Trial left' : 'Remaining' }}
                                                    </span>
                                                </div>
                                            @elseif($daysLeft == 0)
                                                <div class="inline-flex items-center mt-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2 animate-pulse"></span>
                                                    <span class="text-[10px] font-bold text-amber-600 uppercase tracking-wider">
                                                        Expires Today
                                                    </span>
                                                </div>
                                            @else
                                                <div class="inline-flex items-center mt-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2"></span>
                                                    <span class="text-[10px] font-bold text-red-600 uppercase tracking-wider italic">Expired {{ abs($daysLeft) }} days ago</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                         @php $status = trim(strtolower($subscription->status)); @endphp
                                         <span class="px-2 py-0.5 inline-flex text-[10px] font-bold rounded uppercase tracking-wider
                                             @if($status == 'active') bg-emerald-50 text-emerald-700 border border-emerald-100 
                                             @elseif($status == 'trial') bg-primary/10 text-primary border border-primary/20 
                                             @elseif($status == 'cancelled') bg-gray-50 text-gray-500 border border-gray-100
                                             @else bg-red-50 text-red-700 border border-red-100 @endif">
                                             {{ $status == 'trial' ? 'Trial' : $status }}
                                         </span>
                                     </td>
                                     <td class="px-6 py-4 whitespace-nowrap text-right">
                                         <div class="flex justify-end items-center gap-1">
                                              @if($status == 'trial')
                                                 <button @click="$dispatch('open-modal', 'convert-trial-{{ $subscription->id }}')"
                                                     title="Convert to Paid"
                                                     class="no-loader inline-flex items-center justify-center w-8 h-8 bg-primary text-white rounded-lg shadow-lg shadow-primary/20 hover:opacity-90 transition transform active:scale-95">
                                                     <!-- Dollar / Paid icon -->
                                                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                     </svg>
                                                 </button>
                                             @endif

                                             @if($status == 'cancelled')
                                                 <form action="{{ route('subscriptions.activate', $subscription) }}" method="POST" class="inline">
                                                      @csrf @method('PATCH')
                                                      <button type="submit" class="no-loader bg-primary/10 text-primary border border-primary/20 px-4 py-1.5 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-primary/20 transition transform active:scale-95">
                                                          Activate
                                                      </button>
                                                  </form>
                                             @else
                                                 <div class="flex gap-1">
                                                     <button type="button"
                                                         title="Change Plan"
                                                         onclick="openChangePlan('{{ route('subscriptions.changePlan', $subscription) }}', {{ $subscription->plan_id ?? 'null' }})"
                                                         class="no-loader inline-flex items-center justify-center w-8 h-8 bg-primary/10 text-primary border border-primary/20 rounded-lg hover:bg-primary/20 transition transform active:scale-95">
                                                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                         </svg>
                                                     </button>
                                                     <button @click="$dispatch('open-modal', 'extend-subscription-{{ $subscription->id }}')"
                                                         title="Extend Subscription"
                                                         class="no-loader inline-flex items-center justify-center w-8 h-8 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg hover:bg-emerald-100 transition transform active:scale-95">
                                                         <!-- Calendar/extend icon -->
                                                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                         </svg>
                                                     </button>
                                                     
                                                     <form id="cancelSubscriptionForm-{{ $subscription->id }}" action="{{ route('subscriptions.cancel', $subscription) }}" method="POST" class="inline">
                                                         @csrf @method('PATCH')
                                                         <button type="button" title="Cancel Subscription"
                                                             onclick="confirmCancelSubscription('{{ route('subscriptions.cancel', $subscription) }}')"
                                                             class="no-loader inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 border border-red-100 rounded-lg hover:bg-red-100 transition transform active:scale-95">
                                                             <!-- X / Cancel icon -->
                                                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                             </svg>
                                                         </button>
                                                     </form>
                                                 </div>
                                             @endif
                                         </div>

                                        <!-- Modals nested for context -->
                                        <x-modal name="convert-trial-{{ $subscription->id }}" :show="false" focusable>
                                            <form method="post" action="{{ route('subscriptions.convert', $subscription) }}" class="p-5 text-left">
                                                @csrf @method('PATCH')
                                                <h2 class="text-base font-bold text-gray-900 leading-tight">Convert Trial to Paid</h2>
                                                <p class="mt-1 text-[10px] text-gray-500 font-medium border-b border-gray-50 pb-2.5">Select plan and confirm activation.</p>

                                                <div class="mt-4">
                                                    <x-input-label for="plan_id" value="Select Purchased Plan" class="text-[10px] font-semibold uppercase tracking-wider text-gray-500 mb-1" />
                                                    <select name="plan_id" id="plan_id" class="mt-1 block w-full border-gray-100 bg-gray-50/50 focus:border-primary focus:ring-primary rounded-xl shadow-sm text-sm font-bold py-2 px-3 transition appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke%3D%22currentColor%22%3E%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%222%22%20d%3D%22M19%209l-7%207-7-7%22%2F%3E%3C%2Fsvg%3E')] bg-[size:1.25em_1.25em] bg-[position:right_1rem_center] bg-no-repeat pr-10 outline-none" required>
                                                        @foreach($plans as $plan)
                                                            <option value="{{ $plan->id }}">{{ $plan->name }} ({{ $currency }}{{ number_format($plan->price, 0) }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                    <div class="mt-5 flex justify-end gap-2">
                                                        <button type="button" x-on:click="$dispatch('close')" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3">Cancel</button>
                                                        <button type="submit" onclick="showBtnLoader(this)" class="relative inline-flex items-center justify-center bg-primary text-white px-5 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-primary/20 hover:opacity-90 transition min-w-[110px]">
                                                            <span class="btn-content">Confirm Payment</span>
                                                            <span class="hidden btn-loader">
                                                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                </svg>
                                                            </span>
                                                        </button>
                                                    </div>
                                            </form>
                                        </x-modal>

                                        <x-modal name="extend-subscription-{{ $subscription->id }}" :show="false" focusable>
                                            <form method="post" action="{{ route('subscriptions.extend', $subscription) }}" class="p-5 text-left">
                                                @csrf @method('PATCH')
                                                <h2 class="text-base font-bold text-gray-900 leading-tight">Extend Subscription</h2>
                                                <p class="mt-1 text-[10px] text-gray-500 font-medium border-b border-gray-50 pb-2.5">Enter extension details below.</p>
                                                
                                                <div class="mt-4 space-y-3.5">
                                                    <div>
                                                        <x-input-label for="days" value="Extension Duration (Days)" class="text-[10px] font-semibold uppercase tracking-wider text-gray-500 mb-1" />
                                                        <x-text-input id="days" name="days" type="number" class="mt-1 block w-full py-2 px-3 text-sm font-bold bg-gray-50/50 border-gray-100 rounded-xl" placeholder="e.g. 30" required />
                                                    </div>
                                                    <div>
                                                        <x-input-label for="amount" value="Amount Received ({{ $currency }})" class="text-[10px] font-semibold uppercase tracking-wider text-gray-500 mb-1" />
                                                        <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full py-2 px-3 text-sm font-bold bg-gray-50/50 border-gray-100 rounded-xl" placeholder="0.00" required />
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-5 flex justify-end gap-2">
                                                    <button type="button" x-on:click="$dispatch('close')" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3">Cancel</button>
                                                    <button type="submit" onclick="showBtnLoader(this)" class="relative inline-flex items-center justify-center bg-primary text-white px-5 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-primary/20 hover:opacity-90 transition min-w-[110px]">
                                                        <span class="btn-content">Extend Validity</span>
                                                        <span class="hidden btn-loader">
                                                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                        </span>
                                                    </button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-bold uppercase tracking-widest opacity-50">No subscriptions found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($subscriptions->hasPages())
                    <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
                        {{ $subscriptions->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('search-input');
                    const searchForm = document.getElementById('search-form');
                    let timeout = null;

                    if (searchInput) {
                        // Restore cursor position after page reload
                        if (searchInput.value !== "") {
                            searchInput.focus();
                            const val = searchInput.value;
                            searchInput.value = '';
                            searchInput.value = val;
                        }
                        // Submit on Enter key
                        searchInput.addEventListener('keydown', function(e) {
                            if (e.key === 'Enter') {
                                e.preventDefault();
                                searchForm.submit();
                            }
                        });
                    }

                    // Show table loader on form submit
                    searchForm.addEventListener('submit', function() {
                        document.getElementById('table-loader').classList.remove('hidden');
                    });
                });
                function showBtnLoader(btn) {
                    btn.querySelector('.btn-content').classList.add('invisible');
                    btn.querySelector('.btn-loader').classList.remove('hidden');
                    btn.querySelector('.btn-loader').classList.add('absolute', 'flex', 'inset-0', 'items-center', 'justify-center');
                    btn.classList.add('opacity-90', 'cursor-not-allowed');
                    btn.style.pointerEvents = 'none';
                }
            </script>
        </div>
    </div>

    <!-- Global Assign Plan Modal -->
    <x-modal name="assign-plan" :show="false" focusable>
        <form method="post" action="{{ route('subscriptions.store') }}" class="p-5">
            @csrf
            <h2 class="text-base font-bold text-gray-900 leading-tight">Assign New Subscription</h2>
            <p class="mt-1 text-[10px] text-gray-500 font-medium border-b border-gray-50 pb-2.5">Onboard institute to a new cycle.</p>

            <div class="mt-5 space-y-3.5">
                <div>
                    <x-input-label for="institute_id" value="Select Institute" class="text-[10px] font-semibold uppercase tracking-wider text-gray-500 mb-1" />
                    <select name="institute_id" id="institute_id" class="mt-1 block w-full border-gray-100 bg-gray-50/50 focus:border-primary focus:ring-primary rounded-xl shadow-sm text-sm font-bold py-2 px-3 transition appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke%3D%22currentColor%22%3E%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%222%22%20d%3D%22M19%209l-7%207-7-7%22%2F%3E%3C%2Fsvg%3E')] bg-[size:1.25em_1.25em] bg-[position:right_1rem_center] bg-no-repeat pr-10 outline-none">
                        @foreach($institutes as $institute)
                            <option value="{{ $institute->id }}">{{ $institute->institute_name }} ({{ $institute->name }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="plan_id" value="Select Billing Plan" class="text-[10px] font-semibold uppercase tracking-wider text-gray-500 mb-1" />
                    <select name="plan_id" id="plan_id" class="mt-1 block w-full border-gray-100 bg-gray-50/50 focus:border-primary focus:ring-primary rounded-xl shadow-sm text-sm font-bold py-2 px-3 transition appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke%3D%22currentColor%22%3E%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%222%22%20d%3D%22M19%209l-7%207-7-7%22%2F%3E%3C%2Fsvg%3E')] bg-[size:1.25em_1.25em] bg-[position:right_1rem_center] bg-no-repeat pr-10 outline-none">
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} - {{ $currency }}{{ number_format($plan->price, 0) }} ({{ $plan->duration_days }} days)</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="start_date" value="Commencement Date" class="text-[10px] font-semibold uppercase tracking-wider text-gray-500 mb-1" />
                    <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full py-2 px-3 text-sm font-bold bg-gray-50/50 border-gray-100 rounded-xl" value="{{ date('Y-m-d') }}" required />
                </div>
            </div>

            <div class="mt-5 flex justify-end gap-2">
                <button type="button" x-on:click="$dispatch('close')" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3">Cancel</button>
                <button type="submit" onclick="showBtnLoader(this)" class="relative inline-flex items-center justify-center bg-primary text-white px-5 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-primary/20 hover:opacity-90 transition min-w-[110px]">
                    <span class="btn-content">Activate Plan</span>
                    <span class="hidden btn-loader">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Cancel Subscription Confirmation Modal -->
    <div id="cancelSubscriptionModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true" role="dialog">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity" onclick="closeCancelModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <!-- Dialog -->
            <div class="inline-block px-6 py-6 overflow-hidden text-center align-middle transform bg-white rounded-2xl shadow-xl sm:my-8 sm:max-w-sm sm:w-full">
                <div class="flex flex-col items-center">
                    <div class="flex items-center justify-center w-14 h-14 bg-red-50 rounded-full mb-4">
                        <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Cancel Subscription?</h3>
                    <p class="text-[10px] text-gray-500 font-medium px-4">This institute will lose all access at the end of their current billing cycle. Are you sure you want to proceed?</p>
                </div>
                <div class="mt-6 flex items-center gap-3">
                    <button type="button" onclick="closeCancelModal()" class="flex-1 py-2.5 text-[10px] font-bold text-gray-400 hover:text-gray-600 transition uppercase tracking-widest border border-gray-100 rounded-xl">Cancel</button>
                    <form id="cancelSubForm" method="POST" action="" class="flex-1">
                        @csrf @method('PATCH')
                        <button type="submit" class="w-full py-2.5 text-[10px] font-bold text-white bg-primary rounded-xl shadow-lg shadow-primary/20 hover:opacity-90 transition uppercase tracking-widest">Yes, Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmCancelSubscription(actionUrl) {
            document.getElementById('cancelSubForm').action = actionUrl;
            document.getElementById('cancelSubscriptionModal').classList.remove('hidden');
        }
        function closeCancelModal() {
            document.getElementById('cancelSubscriptionModal').classList.add('hidden');
        }
        function openChangePlan(actionUrl, currentPlanId) {
            document.getElementById('changePlanForm').action = actionUrl;
            const select = document.getElementById('changePlanSelect');
            if (currentPlanId && select) { select.value = currentPlanId; }
            document.getElementById('changePlanModal').classList.remove('hidden');
        }
        function closeChangePlan() {
            document.getElementById('changePlanModal').classList.add('hidden');
        }
    </script>

    <!-- Change Plan Modal -->
    <div id="changePlanModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true" role="dialog">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm" onclick="closeChangePlan()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-middle transform bg-white rounded-2xl shadow-xl sm:my-8 sm:max-w-lg sm:w-full sm:p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-primary/10">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Change Subscription Plan</h3>
                        <p class="text-[10px] text-gray-500 mt-0.5">Select a new plan to assign.</p>
                    </div>
                </div>
                <form id="changePlanForm" method="POST" action="">
                    @csrf @method('PATCH')
                    <div>
                        <label class="block text-[10px] font-semibold uppercase tracking-wider text-gray-500 mb-1">Select New Plan</label>
                        <select id="changePlanSelect" name="plan_id" required
                            class="block w-full border border-gray-200 bg-gray-50 focus:border-primary focus:ring-1 focus:ring-primary rounded-xl text-sm font-semibold py-2 px-3 transition outline-none">
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }} — {{ $currency }}{{ number_format($plan->price, 0) }} ({{ $plan->duration_days }} days)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-5 flex justify-end gap-2">
                        <button type="button" onclick="closeChangePlan()" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3">Cancel</button>
                        <button type="submit" class="px-5 py-2 text-[10px] font-bold text-white bg-primary rounded-xl shadow-sm hover:opacity-90 transition shadow-primary/20 uppercase tracking-widest">Change Plan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-admin-layout>