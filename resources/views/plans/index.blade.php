<x-admin-layout title="Subscription Plans">

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Subscription Plans</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">Review and manage your automated pricing packages.</p>
                </div>
                <div>
                    <a href="{{ route('plans.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white hover:bg-indigo-700 transition shadow-indigo-600/20 whitespace-nowrap">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Create New Plan
                    </a>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Plans -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center transition hover:shadow-md duration-300">
                    <div class="w-12 h-12 flex items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Total Plans</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $plans->total() }}</h3>
                    </div>
                </div>

                <!-- Active Offers -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center transition hover:shadow-md duration-300">
                    <div class="w-12 h-12 flex items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Active Offers</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $plans->where('status', true)->count() }}</h3>
                    </div>
                </div>

                <!-- Trial System -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center transition hover:shadow-md duration-300">
                    <div class="w-12 h-12 flex items-center justify-center rounded-full bg-amber-50 text-amber-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Standard Trial</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ App\Models\SystemSetting::get('default_trial_days', 14) }} Days</h3>
                    </div>
                </div>
            </div>

            <!-- Management Card -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/75 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-800">Plan Inventory</h2>
                    <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Pricing Table</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Plan Name</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Price / Duration</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Trial Period</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($plans as $plan)
                                <tr class="hover:bg-gray-50/50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $plan->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-bold text-emerald-600">₹{{ number_format($plan->price, 2) }}</div>
                                        <div class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">{{ $plan->duration_days }} Days</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-xs font-bold text-gray-700">{{ $plan->trial_days }} Days</div>
                                        @if($plan->trial_days > 0)
                                            <span class="mt-1 inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-100">Available</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($plan->status)
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg bg-green-50 text-green-700 border border-green-100">Active</span>
                                        @else
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg bg-red-50 text-red-700 border border-red-100">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('plans.edit', $plan) }}" class="p-1.5 bg-gray-50 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Archive this plan permanently?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1.5 bg-gray-50 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">No subscription plans found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
