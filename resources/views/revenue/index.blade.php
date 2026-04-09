<x-admin-layout title="Revenue Analytics">

    <div class="py-6" x-data="{ showModal: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Revenue Overview</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">Track platform earnings and subscription income.</p>
                </div>
                <div>
                    <button @click="$dispatch('open-modal', 'record-payment')"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white hover:bg-indigo-700 transition shadow-indigo-600/20 whitespace-nowrap">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Record Manual Payment
                    </button>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Daily Revenue -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition hover:shadow-md duration-300">
                    <div class="flex items-center">
                        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-blue-50 text-blue-600">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707m12.728 0A9 9 0 115.636 5.636 9 9 0 0118.364 5.636z"></path></svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Today's Revenue</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1">₹{{ number_format($dailyRevenue, 0) }}<span class="text-sm font-semibold text-gray-400">.{{ explode('.', number_format($dailyRevenue, 2, '.', ''))[1] }}</span></h3>
                        </div>
                    </div>
                </div>

                <!-- This Month -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition hover:shadow-md duration-300">
                    <div class="flex items-center">
                        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-amber-50 text-amber-600">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">This Month ({{ now()->format('M') }})</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1">₹{{ number_format($thisMonthRevenue, 0) }}<span class="text-sm font-semibold text-gray-400">.{{ explode('.', number_format($thisMonthRevenue, 2, '.', ''))[1] }}</span></h3>
                        </div>
                    </div>
                </div>

                <!-- This Year -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition hover:shadow-md duration-300">
                    <div class="flex items-center">
                        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">This Year ({{ now()->format('Y') }})</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1">₹{{ number_format($thisYearRevenue, 0) }}<span class="text-sm font-semibold text-gray-400">.{{ explode('.', number_format($thisYearRevenue, 2, '.', ''))[1] }}</span></h3>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition hover:shadow-md duration-300">
                    <div class="flex items-center">
                        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Total Revenue</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1">₹{{ number_format($totalRevenue, 0) }}<span class="text-sm font-semibold text-gray-400">.{{ explode('.', number_format($totalRevenue, 2, '.', ''))[1] }}</span></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction History Card -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/75">
                    <h2 class="text-lg font-bold text-gray-800">Transaction History</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date / Time</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Institute</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Gateway</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($transactions as $payment)
                                <tr class="hover:bg-gray-50/50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $payment->paid_at ? $payment->paid_at->format('d M, Y') : $payment->created_at->format('d M, Y') }}</div>
                                        <div class="text-[10px] text-gray-400 uppercase font-semibold">{{ $payment->created_at->format('H:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $payment->subscription->institute->institute_name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $payment->subscription->institute->name ?? 'Deleted' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $payment->payment_gateway == 'razorpay' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-purple-50 text-purple-600 border border-purple-100' }}">
                                            {{ $payment->payment_gateway }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-emerald-600">₹{{ number_format($payment->amount, 2) }}</div>
                                        <div class="text-[10px] font-mono text-gray-400">{{ $payment->transaction_id ?? '---' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg bg-green-50 text-green-700 border border-green-100">Success</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">No transactions recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($transactions->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Manual Record Modal -->
    <x-modal name="record-payment" :show="$errors->any()" focusable>
        <form method="post" action="{{ route('revenue.store_manual') }}" class="p-8">
            @csrf
            <h2 class="text-lg font-bold text-gray-900">Record Manual Payment</h2>
            <p class="mt-1 text-sm text-gray-600">Update platform earnings with manual payment entry.</p>

            <div class="mt-8 space-y-6">
                <div>
                    <x-input-label for="institute_id" value="Select Institute" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                    <select id="institute_id" name="institute_id"
                        class="mt-1 block w-full border-gray-200 bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl py-2.5 px-4 text-sm transition">
                        <option value="">Choose an institute...</option>
                        @foreach($institutes as $inst)
                            <option value="{{ $inst->id }}">{{ $inst->institute_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="amount" value="Amount Paid (₹)" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                        <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full bg-gray-50 border-gray-200 rounded-xl py-2 px-3 text-sm" placeholder="e.g. 5000" required />
                    </div>
                    <div>
                        <x-input-label for="paid_at" value="Date of Payment" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                        <x-text-input id="paid_at" name="paid_at" type="date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full bg-gray-50 border-gray-200 rounded-xl py-2 px-3 text-sm" required />
                    </div>
                </div>

                <div>
                    <x-input-label for="transaction_id" value="Reference/Transaction ID" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                    <x-text-input id="transaction_id" name="transaction_id" type="text" class="mt-1 block w-full bg-gray-50 border-gray-200 rounded-xl py-2 px-3 text-sm" placeholder="Optional reference" />
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-800 transition">Cancel</button>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-xl shadow-sm text-sm font-semibold hover:bg-indigo-700 transition shadow-indigo-600/20">Record Revenue</button>
            </div>
        </form>
    </x-modal>
</x-admin-layout>
x-admin-layout>