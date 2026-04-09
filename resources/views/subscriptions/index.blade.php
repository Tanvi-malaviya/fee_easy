<x-admin-layout title="Subscription Management">

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Subscription Management</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">Manage institute plans, trials, and renewals.</p>
                </div>
                <div>
                    <button @click="$dispatch('open-modal', 'assign-plan')"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white hover:bg-indigo-700 transition shadow-indigo-600/20 whitespace-nowrap">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Assign New Plan
                    </button>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center transition hover:shadow-md duration-300">
                    <div class="p-3 rounded-xl bg-green-50 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Active Subscriptions</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $subscriptions->where('status', 'active')->count() }}</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center transition hover:shadow-md duration-300">
                    <div class="p-3 rounded-xl bg-orange-50 text-orange-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Expiring Soon</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $subscriptions->filter(fn($s) => \Carbon\Carbon::parse($s->end_date)->diffInDays(now()) < 7)->count() }}</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center transition hover:shadow-md duration-300">
                    <div class="p-3 rounded-xl bg-indigo-50 text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Estimated Revenue</p>
                        <p class="text-2xl font-bold text-gray-900">₹{{ number_format($subscriptions->where('status', 'active')->sum('amount'), 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Subscriptions Table -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/75">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Institute</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Plan & Amount</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Validity Period</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($subscriptions as $subscription)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $subscription->institute->institute_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $subscription->institute->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">{{ $subscription->plan_name }}</div>
                                        <div class="text-sm text-indigo-600 font-bold">₹{{ number_format($subscription->amount, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Until {{ \Carbon\Carbon::parse($subscription->end_date)->format('d M, Y') }}</div>
                                        @php $daysLeft = \Carbon\Carbon::parse($subscription->end_date)->diffInDays(now(), false); @endphp
                                        @if($daysLeft < 0)
                                            <span class="text-xs font-bold {{ strtolower($subscription->status) == 'trial' ? 'text-blue-600' : 'text-green-600' }}">
                                                {{ abs($daysLeft) }} Days {{ strtolower($subscription->status) == 'trial' ? 'Trial Left' : 'Remaining' }}
                                            </span>
                                        @else
                                            <span class="text-xs font-bold text-red-600 italic">Expired</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2.5 py-1 inline-flex text-[10px] leading-5 font-bold rounded-lg border
                                            @if(strtolower($subscription->status) == 'active') bg-green-50 text-green-700 border-green-100 
                                            @elseif(strtolower($subscription->status) == 'trial') bg-blue-50 text-blue-700 border-blue-100 animate-pulse
                                            @elseif(strtolower($subscription->status) == 'cancelled') bg-gray-50 text-gray-700 border-gray-100
                                            @else bg-red-50 text-red-700 border-red-100 @endif">
                                            {{ strtolower($subscription->status) == 'trial' ? 'FREE TRIAL' : strtoupper($subscription->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex justify-end gap-2">
                                            @php $status = trim(strtolower($subscription->status)); @endphp
                                            @if($status == 'trial')
                                                <button @click="$dispatch('open-modal', 'convert-trial-{{ $subscription->id }}')"
                                                    class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase tracking-wider shadow-sm hover:bg-indigo-700 transition">
                                                    Activate Paid
                                                </button>
                                                <x-modal name="convert-trial-{{ $subscription->id }}" :show="false" focusable>
                                                    <form method="post" action="{{ route('subscriptions.convert', $subscription) }}" class="p-8 text-left">
                                                        @csrf @method('PATCH')
                                                        <h2 class="text-lg font-bold text-gray-900">Convert to Paid Subscription</h2>
                                                        <div class="mt-6">
                                                            <x-input-label for="plan_id" value="Choose Purchased Plan" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                                            <select name="plan_id" class="mt-1 block w-full border-gray-200 bg-gray-50 rounded-xl py-2.5 px-4 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition">
                                                                @foreach($plans as $plan)
                                                                    <option value="{{ $plan->id }}">{{ $plan->name }} (₹{{ number_format($plan->price, 2) }})</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mt-8 flex justify-end gap-3">
                                                            <button type="button" x-on:click="$dispatch('close')" class="text-sm font-semibold text-gray-500">Cancel</button>
                                                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl text-sm font-bold shadow-sm hover:bg-indigo-700">Activate Plan</button>
                                                        </div>
                                                    </form>
                                                </x-modal>
                                            @elseif($status == 'cancelled')
                                                <form action="{{ route('subscriptions.activate', $subscription) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase tracking-wider shadow-sm">Reactive</button>
                                                </form>
                                            @else
                                                <button @click="$dispatch('open-modal', 'extend-{{ $subscription->id }}')" class="p-1.5 bg-gray-50 text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Extend">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                                </button>
                                                <form action="{{ route('subscriptions.cancel', $subscription) }}" method="POST" onsubmit="return confirm('Cancel subscription?');">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="p-1.5 bg-gray-50 text-red-600 hover:bg-red-50 rounded-lg transition" title="Cancel">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </form>
                                                <x-modal name="extend-{{ $subscription->id }}" :show="false" focusable>
                                                    <form method="post" action="{{ route('subscriptions.extend', $subscription) }}" class="p-8 text-left">
                                                        @csrf @method('PATCH')
                                                        <h2 class="text-lg font-bold text-gray-900">Extend Validity</h2>
                                                        <div class="mt-6 space-y-4">
                                                            <div>
                                                                <x-input-label value="Days to Add" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                                                <x-text-input name="days" type="number" class="w-full bg-gray-50 border-gray-200 rounded-xl py-2 px-3 text-sm" placeholder="e.g. 30" required />
                                                            </div>
                                                            <div>
                                                                <x-input-label value="Amount Collected" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                                                <x-text-input name="amount" type="number" step="0.01" class="w-full bg-gray-50 border-gray-200 rounded-xl py-2 px-3 text-sm" placeholder="0.00" required />
                                                            </div>
                                                        </div>
                                                        <div class="mt-8 flex justify-end gap-3">
                                                            <button type="button" x-on:click="$dispatch('close')" class="text-sm font-semibold text-gray-500">Cancel</button>
                                                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl text-sm font-bold transition hover:bg-indigo-700">Extend Now</button>
                                                        </div>
                                                    </form>
                                                </x-modal>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">No subscriptions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($subscriptions->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $subscriptions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Assign Plan Modal -->
    <x-modal name="assign-plan" :show="false" focusable>
        <form method="post" action="{{ route('subscriptions.store') }}" class="p-8">
            @csrf
            <h2 class="text-lg font-bold text-gray-900">Assign New Subscription</h2>
            <div class="mt-8 space-y-6">
                <div>
                    <x-input-label for="institute_id" value="Select Institute" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                    <select name="institute_id" class="w-full border-gray-200 bg-gray-50 rounded-xl py-2.5 px-4 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition">
                        @foreach($institutes as $institute)
                            <option value="{{ $institute->id }}">{{ $institute->institute_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="plan_id" value="Select Plan" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                    <select name="plan_id" class="w-full border-gray-200 bg-gray-50 rounded-xl py-2.5 px-4 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition">
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} (₹{{ $plan->price }}/{{ $plan->duration_days }} days)</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="start_date" value="Start Date" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                    <x-text-input name="start_date" type="date" class="w-full bg-gray-50 border-gray-200 rounded-xl py-2 px-3 text-sm" value="{{ date('Y-m-d') }}" required />
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="text-sm font-semibold text-gray-500">Cancel</button>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl text-sm font-bold transition hover:bg-indigo-700">Assign Plan</button>
            </div>
        </form>
    </x-modal>

</x-admin-layout>
in-layout>