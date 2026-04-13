<x-admin-layout title="Revenue Analytics">

    <div class="" x-data="{ showModal: false }">
        <div class="max-w-7xl mx-auto ">

            <!-- Standalone header removed for consistency -->

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-3">
                <!-- Daily Revenue -->
                <div
                    class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition hover:shadow-md duration-300">
                    <div class="flex items-center">
                        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-blue-50 text-blue-600">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707m12.728 0A9 9 0 115.636 5.636 9 9 0 0118.364 5.636z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Today's Revenue
                            </p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1">
                                {{ $currency }}{{ number_format($dailyRevenue, 0) }}<span
                                    class="text-sm font-semibold text-gray-400">.{{ explode('.', number_format($dailyRevenue, 2, '.', ''))[1] }}</span>
                            </h3>
                        </div>
                    </div>
                </div>

                <!-- This Month -->
                <div
                    class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition hover:shadow-md duration-300">
                    <div class="flex items-center">
                        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-amber-50 text-amber-600">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">This Month
                                ({{ now()->format('M') }})</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1">
                                {{ $currency }}{{ number_format($thisMonthRevenue, 0) }}<span
                                    class="text-sm font-semibold text-gray-400">.{{ explode('.', number_format($thisMonthRevenue, 2, '.', ''))[1] }}</span>
                            </h3>
                        </div>
                    </div>
                </div>

                <!-- This Year -->
                <div
                    class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition hover:shadow-md duration-300">
                    <div class="flex items-center">
                        <div
                            class="w-12 h-12 flex items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">This Year
                                ({{ now()->format('Y') }})</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1">
                                {{ $currency }}{{ number_format($thisYearRevenue, 0) }}<span
                                    class="text-sm font-semibold text-gray-400">.{{ explode('.', number_format($thisYearRevenue, 2, '.', ''))[1] }}</span>
                            </h3>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div
                    class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition hover:shadow-md duration-300">
                    <div class="flex items-center">
                        <div
                            class="w-12 h-12 flex items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Total Revenue
                            </p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1">
                                {{ $currency }}{{ number_format($totalRevenue, 0) }}<span
                                    class="text-sm font-semibold text-gray-400">.{{ explode('.', number_format($totalRevenue, 2, '.', ''))[1] }}</span>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction History Card -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden min-h-[400px]">
                <!-- <div
                    class="px-6 py-5 border-b border-gray-50 flex flex-col sm:flex-row justify-between items-center bg-gray-50/75 gap-4">
                    <div>
                        <h2 class="text-lg font-medium text-gray-800 leading-none">Transaction History</h2>
                    </div>
                    <button @click="$dispatch('open-modal', 'record-payment')"
                        class="inline-flex items-center px-8 py-3 bg-indigo-600 border border-transparent rounded-xl shadow-lg text-xs font-bold text-white uppercase tracking-widest text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition transform active:scale-95 shadow-indigo-600/20 whitespace-nowrap">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Record Manual Payment
                    </button>
                </div> -->

                <!-- Search & Filters -->
                <div class="px-6 py-4 border-b border-gray-50 ">
                    <form id="search-form" action="{{ route('revenue.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search"
                                placeholder="Search institute or owner..." 
                                class="block w-full pl-10 pr-24 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:bg-white focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition">
                            
                            <div class="absolute inset-y-0 right-0 flex items-center pr-1">
                                <button type="submit" class="inline-flex items-center px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-bold uppercase rounded-lg transition shadow-sm tracking-wider no-loader">
                                    Search
                                </button>
                            </div>
                        </div>
                        
                        <div class="w-full md:w-48">
                            <select name="source" onchange="this.form.submit()" 
                                class="block w-full pl-3 pr-10 py-2 text-sm border-gray-200 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl bg-gray-50 transition font-medium text-gray-700">
                                <option value="all">All Sources</option>
                                <option value="admin" {{ request('source') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="app" {{ request('source') == 'app' ? 'selected' : '' }}>App</option>
                                <option value="web" {{ request('source') == 'web' ? 'selected' : '' }}>Web</option>
                            </select>
                        </div>

                        <!-- <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                            Apply
                        </button> -->
                    </form>
                </div>

                <div class="overflow-x-auto relative">
                    <!-- Table Loading Overlay -->
                    <div id="table-loader" class="hidden absolute inset-0 bg-white/70 backdrop-blur-[2px] z-50 flex items-center justify-center transition-all duration-300">
                        <div class="flex flex-col items-center gap-3">
                            <div class="relative">
                                <div class="w-12 h-12 rounded-full border-4 border-indigo-50"></div>
                                <div class="absolute inset-0 w-12 h-12 rounded-full border-4 border-indigo-600 border-t-transparent animate-spin"></div>
                            </div>
                            <span class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em] animate-pulse">Filtering Transactions...</span>
                        </div>
                    </div>

                    <table class="w-full text-left divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date / Time</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Institute</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Source</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Amount</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($transactions as $payment)
                                <tr class="hover:bg-gray-50/50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">
                                            {{ $payment->paid_at ? $payment->paid_at->format('d M, Y') : $payment->created_at->format('d M, Y') }}
                                        </div>
                                        <div class="text-[10px] text-gray-400 uppercase font-semibold">
                                            {{ $payment->created_at->format('H:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 leading-tight">
                                            {{ $payment->subscription->institute->institute_name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $payment->subscription->institute->name ?? 'Deleted' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col gap-1">
                                            @php
                                                $source = $payment->payment_source ?? 'admin';
                                                $sourceClasses = [
                                                    'admin' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                                    'app' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                    'web' => 'bg-purple-50 text-purple-600 border-purple-100',
                                                ][$source] ?? 'bg-gray-50 text-gray-600 border-gray-100';
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase border {{ $sourceClasses }} w-fit">
                                                {{ $source }}
                                            </span>
                                            @if($payment->payment_gateway && $payment->payment_gateway != 'manual')
                                                <span class="text-[9px] text-gray-400 font-medium uppercase tracking-tighter">
                                                    Via {{ $payment->payment_gateway }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="text-sm font-bold text-emerald-600">
                                            {{ $currency }}{{ number_format($payment->amount, 0) }}</div>
                                        <div class="text-[10px] font-mono text-gray-400 italic">ID:
                                            {{ $payment->transaction_id ?? '---' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="px-2.5 py-1 inline-flex text-[10px] font-bold rounded uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-100">Success</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic font-medium">No transactions match your criteria.</td>
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
                    <x-input-label for="institute_id" value="Select Institute"
                        class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                    <select id="institute_id" name="institute_id" onchange="clearError(this)"
                        class="mt-1 block w-full border-gray-200 bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl py-2.5 px-4 text-sm transition @error('institute_id') border-red-500 @enderror">
                        <option value="">Choose an institute...</option>
                        @foreach($institutes as $inst)
                            <option value="{{ $inst->id }}" {{ old('institute_id') == $inst->id ? 'selected' : '' }}>
                                {{ $inst->institute_name }}</option>
                        @endforeach
                    </select>
                    @error('institute_id')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="amount" value="Amount Paid ({{ $currency }})"
                            class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                        <x-text-input id="amount" name="amount" type="number" step="0.01" value="{{ old('amount') }}"
                            oninput="clearError(this)"
                            class="mt-1 block w-full bg-gray-50 border-gray-200 rounded-xl py-2 px-3 text-sm @error('amount') border-red-500 @enderror"
                            placeholder="e.g. 5000" required />
                        @error('amount')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <x-input-label for="paid_at" value="Date of Payment"
                            class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                        <x-text-input id="paid_at" name="paid_at" type="date"
                            value="{{ old('paid_at', date('Y-m-d')) }}" oninput="clearError(this)"
                            max="{{ date('Y-m-d') }}"
                            class="mt-1 block w-full bg-gray-50 border-gray-200 rounded-xl py-2 px-3 text-sm @error('paid_at') border-red-500 @enderror"
                            required />
                        @error('paid_at')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <x-input-label for="transaction_id" value="Reference/Transaction ID"
                        class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                    <x-text-input id="transaction_id" name="transaction_id" type="text"
                        value="{{ old('transaction_id') }}" oninput="clearError(this)"
                        class="mt-1 block w-full bg-gray-50 border-gray-200 rounded-xl py-2 px-3 text-sm @error('transaction_id') border-red-500 @enderror"
                        placeholder="Optional reference" />
                    @error('transaction_id')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-widest hover:text-gray-700 transition">Cancel</button>
                <button type="submit"
                    class="px-8 py-3 bg-indigo-600 text-white rounded-xl shadow-lg text-xs font-bold uppercase tracking-widest hover:bg-indigo-700 transition transform active:scale-95 shadow-indigo-600/20">Record
                    Revenue</button>
            </div>
        </form>
    </x-modal>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('search-form');
            const tableLoader = document.getElementById('table-loader');

            if (searchForm) {
                searchForm.addEventListener('submit', function() {
                    tableLoader.classList.remove('hidden');
                });
            }

            // Also trigger loader on source change
            const sourceSelect = document.querySelector('select[name="source"]');
            if (sourceSelect) {
                sourceSelect.addEventListener('change', function() {
                    tableLoader.classList.remove('hidden');
                });
            }
        });

        function clearError(el) {
            if (!el) return;
            el.classList.remove('border-red-500');
            const parent = el.closest('div');
            const errorMsg = parent.querySelector('p.text-red-500');
            if (errorMsg) {
                errorMsg.classList.add('hidden');
            }
        }
    </script>
</x-admin-layout>