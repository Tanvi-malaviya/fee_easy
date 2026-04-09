<x-admin-layout title="Revenue Analytics">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">
                    {{ __('Revenue Analytics') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Track institute subscriptions and total platform income.</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <span class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg shadow-sm text-sm font-bold text-indigo-600 bg-indigo-50">
                    Live Revenue Tracking
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6" x-data="{}">
        <div class="max-w-7xl mx-auto">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Revenue -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition hover:shadow-md">
                    <div class="flex items-center">
                        <div class="p-4 rounded-2xl bg-indigo-50 text-indigo-600 text-2xl">
                            💰
                        </div>
                        <div class="ml-5">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Revenue</p>
                            <h3 class="text-2xl font-black text-gray-900 mt-1">₹{{ number_format($totalRevenue, 2) }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Last 30 Days -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition hover:shadow-md">
                    <div class="flex items-center">
                        <div class="p-4 rounded-2xl bg-emerald-50 text-emerald-600 text-2xl">
                            📈
                        </div>
                        <div class="ml-5">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Last 30 Days</p>
                            <h3 class="text-2xl font-black text-gray-900 mt-1">₹{{ number_format($monthlyRevenue, 2) }}</h3>
                        </div>
                    </div>
                </div>

                <!-- This Month -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition hover:shadow-md">
                    <div class="flex items-center">
                        <div class="p-4 rounded-2xl bg-amber-50 text-amber-600 text-2xl">
                            📅
                        </div>
                        <div class="ml-5">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">This Month ({{ now()->format('M') }})</p>
                            <h3 class="text-2xl font-black text-gray-900 mt-1">₹{{ number_format($thisMonthRevenue, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction History Card -->
            <div class="bg-white border border-gray-100 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <h2 class="text-lg font-bold text-gray-900">Transaction History</h2>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Audit Trail</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Date / Time</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Institute</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Gateway</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">ID / Amount</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($transactions as $payment)
                                <tr class="hover:bg-gray-50/50 transition duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $payment->paid_at ? $payment->paid_at->format('d M, Y') : $payment->created_at->format('d M, Y') }}</div>
                                        <div class="text-[10px] font-bold text-indigo-500 uppercase">{{ $payment->paid_at ? $payment->paid_at->format('h:i A') : $payment->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $payment->subscription->institute->institute_name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $payment->subscription->institute->name ?? 'Deleted Institute' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($payment->payment_gateway == 'razorpay')
                                                <span class="px-2 py-0.5 rounded text-[10px] bg-blue-600 text-white font-black uppercase">Razorpay</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-[10px] bg-gray-100 text-gray-600 font-bold uppercase tracking-wider">Manual</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-[10px] font-mono text-gray-400 truncate w-32" title="{{ $payment->transaction_id ?? 'No ID' }}">
                                            {{ $payment->transaction_id ?? '---' }}
                                        </div>
                                        <div class="text-sm font-black text-emerald-600">₹{{ number_format($payment->amount, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-black rounded-lg bg-green-100 text-green-700">Success</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="text-4xl mb-4">📭</div>
                                            <p class="text-gray-500 font-bold">No transactions recorded yet.</p>
                                            <p class="text-xs text-gray-400 mt-1 uppercase">Your financial audit trail will appear here.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($transactions->hasPages())
                    <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
