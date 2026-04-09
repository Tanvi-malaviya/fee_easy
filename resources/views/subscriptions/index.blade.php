<x-admin-layout title="Subscription Management">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">
                    {{ __('Subscription Management') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Track institute plans, manage renewals, and monitor revenue.</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <button @click="$dispatch('open-modal', 'assign-plan')"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition shadow-indigo-600/30">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Assign New Plan
                </button>
            </div>
        </div>
    </x-slot>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-green-50 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Active Subscriptions</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $subscriptions->where('status', 'active')->count() }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-orange-50 text-orange-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Expiring Soon</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $subscriptions->filter(fn($s) => \Carbon\Carbon::parse($s->end_date)->diffInDays(now()) < 7)->count() }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-indigo-50 text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Monthly Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">
                        ₹{{ number_format($subscriptions->where('status', 'active')->sum('amount'), 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscriptions Table -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden mt-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/75">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Institute</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Plan & Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Validity</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($subscriptions as $subscription)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">
                                    {{ $subscription->institute->institute_name }}</div>
                                <div class="text-xs text-gray-500">{{ $subscription->institute->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $subscription->plan_name }}</div>
                                <div class="text-sm text-indigo-600 font-bold">
                                    ₹{{ number_format($subscription->amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs text-gray-500">From:
                                    {{ \Carbon\Carbon::parse($subscription->start_date)->format('M d, Y') }}</div>
                                <div class="text-sm font-medium text-gray-900">Until:
                                    {{ \Carbon\Carbon::parse($subscription->end_date)->format('M d, Y') }}</div>
                                @php
                                    $daysLeft = \Carbon\Carbon::parse($subscription->end_date)->diffInDays(now(), false);
                                @endphp
                                @if($daysLeft < 0)
                                    <span class="text-[11px] font-bold {{ strtolower($subscription->status) == 'trial' ? 'text-blue-600' : 'text-green-600' }}">
                                        {{ abs($daysLeft) }} Days {{ strtolower($subscription->status) == 'trial' ? 'Trial Left' : 'Remaining' }}
                                    </span>
                                @else
                                    <span class="text-[11px] font-bold text-red-600 whitespace-nowrap italic">Expired {{ $daysLeft }} days ago</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md 
                                    @if(strtolower($subscription->status) == 'active') bg-green-100 text-green-700 border border-green-200 
                                    @elseif(strtolower($subscription->status) == 'trial') bg-blue-100 text-blue-700 border border-blue-200 animate-pulse
                                    @elseif(strtolower($subscription->status) == 'cancelled') bg-gray-100 text-gray-700 border border-gray-200
                                    @else bg-red-100 text-red-700 border border-red-200 @endif">
                                    {{ strtolower($subscription->status) == 'trial' ? 'Free Trial' : ucfirst($subscription->status) }}
                                </span>
                            </td>
                             <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="flex justify-end items-center gap-2">
                                    @php $status = trim(strtolower($subscription->status)); @endphp

                                    @if($status == 'trial')
                                        <button @click="$dispatch('open-modal', 'convert-trial-{{ $subscription->id }}')"
                                            class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg font-bold text-xs shadow-sm hover:bg-indigo-700 transition">
                                            Paid
                                        </button>

                                        <!-- Convert Trial Modal -->
                                        <x-modal name="convert-trial-{{ $subscription->id }}" :show="false" focusable>
                                            <form method="post" action="{{ route('subscriptions.convert', $subscription) }}" class="p-6">
                                                @csrf @method('PATCH')
                                                <h2 class="text-lg font-medium text-gray-900">Convert Trial to Paid</h2>
                                                <p class="mt-1 text-sm text-gray-600">Select the plan the institute has purchased.</p>
                                                
                                                <div class="mt-6">
                                                    <x-input-label for="plan_id" value="Select Plan" />
                                                    <select name="plan_id" id="plan_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                                        @foreach($plans as $plan)
                                                            <option value="{{ $plan->id }}">{{ $plan->name }} (₹{{ number_format($plan->price, 2) }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mt-6 flex justify-end">
                                                    <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                                                    <x-primary-button class="ml-3">Confirm Payment & Activate</x-primary-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    @endif

                                    @if($status == 'cancelled')
                                        <form action="{{ route('subscriptions.activate', $subscription) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="bg-indigo-600 text-white px-4 py-1.5 rounded-lg font-bold text-xs shadow-sm shadow-indigo-200">
                                                Activate Plan
                                            </button>
                                        </form>
                                    @else
                                        <button @click="$dispatch('open-modal', 'extend-subscription-{{ $subscription->id }}')"
                                            class="text-indigo-600 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 px-3 py-1.5 rounded-lg text-xs font-semibold">Extend</button>

                                        <form action="{{ route('subscriptions.cancel', $subscription) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Cancel this subscription?')">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-red-600 bg-red-50 hover:bg-red-100 border border-red-100 px-3 py-1.5 rounded-lg text-xs font-semibold">Cancel</button>
                                        </form>
                                    @endif
                                </div>

                                <!-- Extend Modal (Simplified using Breeze Modal Component if exists, or custom) -->
                                <x-modal name="extend-subscription-{{ $subscription->id }}" :show="false" focusable>
                                    <form method="post" action="{{ route('subscriptions.extend', $subscription) }}"
                                        class="p-6">
                                        @csrf @method('PATCH')
                                        <h2 class="text-lg font-medium text-gray-900">Extend Subscription</h2>
                                        <p class="mt-1 text-sm text-gray-600">Enter the number of days to add to the current
                                            validity.</p>
                                        <div class="mt-6">
                                            <x-input-label for="days" value="Number of Days" />
                                            <x-text-input id="days" name="days" type="number" class="mt-1 block w-full"
                                                placeholder="30" required />
                                        </div>
                                        <div class="mt-4">
                                            <x-input-label for="amount" value="Amount Received (₹)" />
                                            <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full"
                                                placeholder="0.00" required />
                                        </div>
                                        <div class="mt-6 flex justify-end">
                                            <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                                            <x-primary-button class="ml-3">Extend & Record Payment</x-primary-button>
                                        </div>
                                    </form>
                                </x-modal>
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
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $subscriptions->links() }}
        </div>
    </div>

    <!-- Assign Plan Modal -->
    <x-modal name="assign-plan" :show="false" focusable>
        <form method="post" action="{{ route('subscriptions.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900">Assign New Subscription</h2>
            <p class="mt-1 text-sm text-gray-600">Select an institute and a plan to start a new billing cycle.</p>

            <div class="mt-6 space-y-4">
                <div>
                    <x-input-label for="institute_id" value="Select Institute" />
                    <select name="institute_id" id="institute_id"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @foreach($institutes as $institute)
                            <option value="{{ $institute->id }}">{{ $institute->institute_name }} ({{ $institute->name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="plan_id" value="Select Plan" />
                    <select name="plan_id" id="plan_id"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} - ₹{{ $plan->price }}
                                ({{ $plan->duration_days }} days)</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="start_date" value="Start Date" />
                    <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full"
                        value="{{ date('Y-m-d') }}" required />
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                <x-primary-button class="ml-3">Assign Plan</x-primary-button>
            </div>
        </form>
    </x-modal>

</x-admin-layout>