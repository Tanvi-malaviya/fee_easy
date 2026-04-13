<x-admin-layout title="Subscription Plans">
    <div class="py-0">
        <div class="max-w-7xl mx-auto">
          

            <!-- Filters & Search -->
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm mb-3">
                <form id="search-form" action="{{ route('plans.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none" id="search-icon">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center hidden" id="search-loader">
                            <svg class="animate-spin h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <input type="text" id="search-input" name="search" value="{{ request('search') }}" autocomplete="off"
                            placeholder="Search by plan name..." 
                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:bg-white focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition">
                    </div>
                    
                            <div class="w-full md:w-48">
                                <select name="status" onchange="this.form.submit()" 
                                    class="block w-full pl-3 pr-10 py-2.5 text-sm border-gray-200 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl bg-gray-50 transition font-medium text-gray-700 cursor-pointer appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke%3D%22currentColor%22%3E%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%222%22%20d%3D%22M19%209l-7%207-7-7%22%2F%3E%3C%2Fsvg%3E')] bg-[size:1.25em_1.25em] bg-[position:right_1rem_center] bg-no-repeat">
                                    <option value="all">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <!-- @if(request()->has('search') || request()->has('status'))
                                <a href="{{ route('plans.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 hover:text-red-800 transition">
                                    Clear Filters
                                </a>
                            @endif -->

                            <div class="flex items-center ml-auto">
                                <a href="{{ route('plans.create') }}" id="create-btn" onclick="showBtnLoader(this)"
                                    class="relative inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition shadow-indigo-600/20 whitespace-nowrap min-w-[150px]">
                                    <span class="flex items-center btn-content">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Create New Plan
                                    </span>
                                    <span class="hidden btn-loader">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                </form>
            </div>

            <!-- Management Card -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/75 border-b border-gray-100">
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Plan</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Price / Duration</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Trial Period</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($plans as $plan)
                                <tr class="hover:bg-gray-50/50 transition duration-150">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <!-- <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-700 font-bold text-sm overflow-hidden border border-gray-100 shadow-sm transition-colors group-hover:bg-indigo-100">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div> -->
                                            <div class="">
                                                <div class="text-sm font-bold text-gray-900 leading-tight">{{ $plan->name }}</div>
                                                <div class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mt-0.5">ID: #PLN-{{ $plan->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-center">
                                        <div class="text-sm font-bold text-emerald-600 leading-tight">{{ $currency }}{{ number_format($plan->price, 0) }}</div>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">{{ $plan->duration_days }} Days</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-center">
                                        <div class="text-sm font-bold text-gray-900 leading-tight">{{ $plan->trial_days }} Days</div>
                                        @if($plan->trial_days > 0)
                                            <div class="text-[10px] text-indigo-500 font-bold uppercase tracking-wider mt-0.5">Free Access</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-center">
                                        @if($plan->status)
                                            <span class="px-2.5 py-1 inline-flex text-[10px] font-bold rounded uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-100 mx-auto">Active</span>
                                        @else
                                            <span class="px-2.5 py-1 inline-flex text-[10px] font-bold rounded uppercase tracking-wider bg-red-50 text-red-700 border border-red-100 mx-auto">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-right text-xs">
                                        <div class="flex justify-end gap-2 text-sm font-medium">
                                            <a href="{{ route('plans.edit', $plan) }}" 
                                                class="text-indigo-600 hover:text-indigo-900 transition-colors p-1.5 bg-indigo-50 rounded-lg" 
                                                title="Edit Plan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('plans.destroy', $plan) }}" method="POST" class="inline" onsubmit="return confirm('Archive this plan permanently?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 transition-colors p-1.5 bg-red-50 rounded-lg no-loader" 
                                                    title="Delete Plan">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
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
                @if($plans->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $plans->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search Logic
            const searchInput = document.getElementById('search-input');
            const searchForm = document.getElementById('search-form');
            let timeout = null;

            if (searchInput) {
                if (searchInput.value !== "") {
                    searchInput.focus();
                    const val = searchInput.value;
                    searchInput.value = '';
                    searchInput.value = val;
                }

                searchInput.addEventListener('input', function() {
                    // Show loader, hide icon
                    document.getElementById('search-icon').classList.add('hidden');
                    document.getElementById('search-loader').classList.remove('hidden');

                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        searchForm.submit();
                    }, 500);
                });
            }
        });

        function showBtnLoader(btn) {
            btn.querySelector('.btn-content').classList.add('invisible');
            btn.querySelector('.btn-loader').classList.remove('hidden');
            btn.querySelector('.btn-loader').classList.add('absolute', 'flex', 'inset-0', 'items-center', 'justify-center');
            btn.classList.add('opacity-90', 'cursor-not-allowed');
            btn.style.pointerEvents = 'none';
        }
    </script>
</x-admin-layout>