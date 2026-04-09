<x-admin-layout title="Subscription Management">

    <div class="">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition hover:shadow-md duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-green-50 text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Active Subscriptions</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $subscriptions->where('status', 'active')->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition hover:shadow-md duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-orange-50 text-orange-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Expiring Soon</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">
                                {{ $subscriptions->filter(fn($s) => \Carbon\Carbon::parse($s->end_date)->diffInDays(now()) < 7)->count() }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition hover:shadow-md duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-indigo-50 text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Estimated revenue</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">₹{{ number_format($subscriptions->where('status', 'active')->sum('amount'), 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Management Table -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-50 flex flex-col sm:flex-row justify-between items-center bg-gray-50/75 gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 leading-none">Institute Subscriptions</h2>
                        <p class="text-xs text-gray-500 font-medium mt-1.5 uppercase tracking-widest">Active Billing Pipeline</p>
                    </div>
                    <button @click="$dispatch('open-modal', 'assign-plan')"
                        class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-xl shadow-lg shadow-indigo-600/20 text-xs font-bold uppercase tracking-widest text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition transform hover:-translate-y-0.5">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Assign New Plan
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Institute</th>
                                <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Plan</th>
                                <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Validity</th>
                                <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($subscriptions as $subscription)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 leading-tight">{{ $subscription->institute->institute_name }}</div>
                                        <div class="text-xs text-gray-500 font-medium mt-0.5">{{ $subscription->institute->name }}</div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $subscription->plan_name }}</div>
                                        <div class="text-[10px] text-indigo-600 font-bold uppercase tracking-wider mt-1">₹{{ number_format($subscription->amount, 0) }}</div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Expires On</span>
                                            <span class="text-sm font-bold text-gray-900 mt-0.5">{{ \Carbon\Carbon::parse($subscription->end_date)->format('d M, Y') }}</span>
                                            
                                            @php $daysLeft = \Carbon\Carbon::parse($subscription->end_date)->diffInDays(now(), false); @endphp
                                            @if($daysLeft < 0)
                                                <div class="inline-flex items-center mt-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full {{ strtolower($subscription->status) == 'trial' ? 'bg-blue-500' : 'bg-emerald-500' }} mr-2"></span>
                                                    <span class="text-[10px] font-bold {{ strtolower($subscription->status) == 'trial' ? 'text-blue-600' : 'text-emerald-600' }} uppercase tracking-wider">
                                                        {{ abs($daysLeft) }} Days {{ strtolower($subscription->status) == 'trial' ? 'Trial left' : 'Remaining' }}
                                                    </span>
                                                </div>
                                            @else
                                                <div class="inline-flex items-center mt-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2"></span>
                                                    <span class="text-[10px] font-bold text-red-600 uppercase tracking-wider italic">Expired {{ $daysLeft }} days ago</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        @php $status = trim(strtolower($subscription->status)); @endphp
                                        <span class="px-2 py-0.5 inline-flex text-[10px] font-bold rounded uppercase tracking-wider
                                            @if($status == 'active') bg-emerald-50 text-emerald-700 border border-emerald-100 
                                            @elseif($status == 'trial') bg-blue-50 text-blue-700 border border-blue-100 animate-pulse
                                            @elseif($status == 'cancelled') bg-gray-50 text-gray-500 border border-gray-100
                                            @else bg-red-50 text-red-700 border border-red-100 @endif">
                                            {{ $status == 'trial' ? 'Trial' : $status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-right">
                                        <div class="flex justify-end items-center gap-1.5">
                                            @if($status == 'trial')
                                                <button @click="$dispatch('open-modal', 'convert-trial-{{ $subscription->id }}')"
                                                    class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase tracking-wider shadow-sm hover:bg-indigo-700 transition">
                                                    Paid
                                                </button>
                                            @endif

                                            @if($status == 'cancelled')
                                                <form action="{{ route('subscriptions.activate', $subscription) }}" method="POST" class="inline">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="bg-indigo-50 text-indigo-700 border border-indigo-100 px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase tracking-wider hover:bg-indigo-100 transition">
                                                        Activate
                                                    </button>
                                                </form>
                                            @else
                                                <div class="flex gap-1">
                                                    <button @click="$dispatch('open-modal', 'extend-subscription-{{ $subscription->id }}')"
                                                        class="bg-emerald-50 text-emerald-700 border border-emerald-100 px-2.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-emerald-100 transition">Extend</button>
                                                    
                                                    <form action="{{ route('subscriptions.cancel', $subscription) }}" method="POST" class="inline" onsubmit="return confirm('Cancel this subscription?')">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="bg-red-50 text-red-600 border border-red-100 px-2.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-red-100 transition">Cancel</button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Modals nested for context -->
                                        <x-modal name="convert-trial-{{ $subscription->id }}" :show="false" focusable>
                                            <form method="post" action="{{ route('subscriptions.convert', $subscription) }}" class="p-8 text-left">
                                                @csrf @method('PATCH')
                                                <h2 class="text-xl font-bold text-gray-900 leading-tight">Convert Trial to Paid</h2>
                                                <p class="mt-2 text-sm text-gray-500 font-medium border-b border-gray-50 pb-4">Select the plan the institute has purchased and confirm activation.</p>

                                                <div class="mt-8">
                                                    <x-input-label for="plan_id" value="Select Purchased Plan" class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2" />
                                                    <select name="plan_id" id="plan_id" class="mt-1 block w-full border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm font-bold py-3 px-4 transition" required>
                                                        @foreach($plans as $plan)
                                                            <option value="{{ $plan->id }}">{{ $plan->name }} (₹{{ number_format($plan->price, 0) }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mt-8 flex justify-end gap-3">
                                                    <button type="button" x-on:click="$dispatch('close')" class="text-sm font-bold text-gray-400 uppercase tracking-widest px-4">Cancel</button>
                                                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-indigo-600/20 hover:bg-indigo-700 transition">Confirm Payment</button>
                                                </div>
                                            </form>
                                        </x-modal>

                                        <x-modal name="extend-subscription-{{ $subscription->id }}" :show="false" focusable>
                                            <form method="post" action="{{ route('subscriptions.extend', $subscription) }}" class="p-8 text-left">
                                                @csrf @method('PATCH')
                                                <h2 class="text-xl font-bold text-gray-900 leading-tight">Extend Subscription</h2>
                                                <p class="mt-2 text-sm text-gray-500 font-medium border-b border-gray-50 pb-4">Enter extension details and record the payment amount.</p>
                                                
                                                <div class="mt-8 space-y-6">
                                                    <div>
                                                        <x-input-label for="days" value="Extension Duration (Days)" class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2" />
                                                        <x-text-input id="days" name="days" type="number" class="mt-1 block w-full py-3 px-4 text-sm font-bold bg-gray-50/50 border-gray-100 rounded-xl" placeholder="e.g. 30" required />
                                                    </div>
                                                    <div>
                                                        <x-input-label for="amount" value="Amount Received (₹)" class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2" />
                                                        <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full py-3 px-4 text-sm font-bold bg-gray-50/50 border-gray-100 rounded-xl" placeholder="0.00" required />
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-8 flex justify-end gap-3">
                                                    <button type="button" x-on:click="$dispatch('close')" class="text-sm font-bold text-gray-400 uppercase tracking-widest px-4">Cancel</button>
                                                    <button type="submit" class="bg-emerald-600 text-white px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-emerald-600/20 hover:bg-emerald-700 transition">Extend Validity</button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-bold uppercase tracking-widest opacity-50">No subscriptions active</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($subscriptions->hasPages())
                    <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
                        {{ $subscriptions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Global Assign Plan Modal -->
    <x-modal name="assign-plan" :show="false" focusable>
        <form method="post" action="{{ route('subscriptions.store') }}" class="p-8">
            @csrf
            <h2 class="text-xl font-bold text-gray-900 leading-tight">Assign New Subscription</h2>
            <p class="mt-2 text-sm text-gray-500 font-medium border-b border-gray-50 pb-4">Onboard an institute to a new billing cycle.</p>

            <div class="mt-8 space-y-6">
                <div>
                    <x-input-label for="institute_id" value="Select Institute" class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2" />
                    <select name="institute_id" id="institute_id" class="mt-1 block w-full border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm font-bold py-3 px-4 transition">
                        @foreach($institutes as $institute)
                            <option value="{{ $institute->id }}">{{ $institute->institute_name }} ({{ $institute->name }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="plan_id" value="Select Billing Plan" class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2" />
                    <select name="plan_id" id="plan_id" class="mt-1 block w-full border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm font-bold py-3 px-4 transition">
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} - ₹{{ number_format($plan->price, 0) }} ({{ $plan->duration_days }} days)</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="start_date" value="Commencement Date" class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2" />
                    <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full py-3 px-4 text-sm font-bold bg-gray-50/50 border-gray-100 rounded-xl" value="{{ date('Y-m-d') }}" required />
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="text-sm font-bold text-gray-400 uppercase tracking-widest px-4">Cancel</button>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-indigo-600/20 hover:bg-indigo-700 transition">Activate Plan</button>
            </div>
        </form>
    </x-modal>
</x-admin-layout>