<x-admin-layout title="Subscription Plans">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">
                    {{ __('Subscription Plans') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1 uppercase font-bold tracking-widest text-[10px]">Revenue Packages & Pricing Control</p>
            </div>
            <a href="{{ route('plans.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent rounded-2xl shadow-xl shadow-indigo-100 text-sm font-black uppercase tracking-widest text-white bg-indigo-600 hover:bg-indigo-700 transition transform hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Create New Plan
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center transition hover:shadow-md">
                    <div class="p-4 rounded-2xl bg-indigo-50 text-indigo-600 text-2xl">💎</div>
                    <div class="ml-5">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Total Plans</p>
                        <h3 class="text-2xl font-black text-gray-900 mt-1">{{ $plans->total() }}</h3>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center transition hover:shadow-md">
                    <div class="p-4 rounded-2xl bg-emerald-50 text-emerald-600 text-2xl">⏳</div>
                    <div class="ml-5">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Active Offers</p>
                        <h3 class="text-2xl font-black text-gray-900 mt-1">{{ $plans->where('status', true)->count() }}</h3>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center transition hover:shadow-md">
                    <div class="p-4 rounded-2xl bg-amber-50 text-amber-600 text-2xl">🛡️</div>
                    <div class="ml-5">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Standard Trial</p>
                        <h3 class="text-2xl font-black text-gray-900 mt-1">{{ App\Models\SystemSetting::get('default_trial_days', 14) }} Days</h3>
                    </div>
                </div>
            </div>

            <!-- Management Card -->
            <div class="bg-white border border-gray-100 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <h2 class="text-lg font-bold text-gray-900">Plan Inventory</h2>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Pricing Table</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                <th class="px-8 py-4">Plan Name</th>
                                <th class="px-8 py-4">Pricing ({{ App\Models\SystemSetting::get('currency_symbol', '₹') }})</th>
                                <th class="px-8 py-4">Duration</th>
                                <th class="px-8 py-4">Free Trial</th>
                                <th class="px-8 py-4">Status</th>
                                <th class="px-8 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($plans as $plan)
                                <tr class="hover:bg-gray-50/50 transition duration-200">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="text-sm font-black text-gray-900">{{ $plan->name }}</div>
                                        <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1">ID: #{{ $plan->id }}</div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="text-lg font-black text-emerald-600">
                                            {{ App\Models\SystemSetting::get('currency_symbol', '₹') }}{{ number_format($plan->price, 2) }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="px-3 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase inline-block">
                                            {{ $plan->duration_days }} Days
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-700">{{ $plan->trial_days }} Days</div>
                                        @if($plan->trial_days > 0)
                                            <div class="text-[9px] font-black text-emerald-500 uppercase">Available</div>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        @if($plan->status)
                                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-green-50 text-green-700 border border-green-200/50">Active</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-red-50 text-red-700 border border-red-200/50">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-right">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('plans.edit', $plan) }}" class="p-2 bg-gray-50 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Archive this plan permanently?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 bg-gray-50 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-20 text-center">
                                        <div class="text-4xl mb-4">💎</div>
                                        <p class="text-gray-500 font-bold">No retail plans found.</p>
                                        <a href="{{ route('plans.create') }}" class="text-indigo-600 text-sm font-black uppercase mt-2 inline-block">Add First Plan</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
