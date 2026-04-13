<x-admin-layout title="Subscription Plans">
    <div class="py-0">
        <div class="max-w-7xl mx-auto">


            <!-- Filters & Search -->
            <div class=" mb-3">
                <form id="search-form" action="{{ route('plans.index') }}" method="GET"
                    class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                            id="search-icon">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center hidden" id="search-loader">
                            <svg class="animate-spin h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                        <input type="text" id="search-input" name="search" value="{{ request('search') }}"
                            autocomplete="off" placeholder="Search by plan name..."
                            class="block w-full pl-10 pr-24 py-2.5 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:bg-white focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition">
                        <!-- Search Button inside input -->
                        <div class="absolute inset-y-0 right-0 flex items-center pr-1">
                            <button type="submit"
                                class="no-loader inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition">
                                <!-- <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg> -->
                                Search
                            </button>
                        </div>
                    </div>

                    <div class="w-full md:w-48">
                        <select name="status" onchange="this.form.submit()"
                            class="block w-full pl-3 pr-10 py-2.5 text-sm border border-gray-200 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl bg-white transition font-medium text-gray-700 cursor-pointer appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke%3D%22currentColor%22%3E%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%222%22%20d%3D%22M19%209l-7%207-7-7%22%2F%3E%3C%2Fsvg%3E')] bg-[size:1.25em_1.25em] bg-[position:right_1rem_center] bg-no-repeat">
                            <option value="all">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                    </div>

                    <!-- @if(request()->has('search') || request()->has('status'))
                                <a href="{{ route('plans.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 hover:text-red-800 transition">
                                    Clear Filters
                                </a>
                            @endif -->

                    <div class="flex items-center ml-auto">
                        <button type="button" @click="$dispatch('open-modal', 'create-plan')"
                            class="relative inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition shadow-indigo-600/20 whitespace-nowrap min-w-[150px]">
                            <span class="flex items-center btn-content">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create New Plan
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Management Card -->
            <div class="relative bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <!-- Table Loading Overlay -->
                <div id="table-loader"
                    class="hidden absolute inset-0 bg-white/70 backdrop-blur-sm rounded-2xl z-10 flex items-center justify-center">
                    <div class="flex flex-col items-center gap-3">
                        <svg class="animate-spin h-8 w-8 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span
                            class="text-xs font-semibold text-indigo-500 uppercase tracking-widest">Searching...</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/75 border-b border-gray-100">
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Plan
                                </th>
                                <th
                                    class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                    Price / Duration</th>
                                <th
                                    class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                    Status</th>
                                <th
                                    class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($plans as $plan)
                                <tr class="hover:bg-gray-50/50 transition duration-150">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="">
                                            <div class="text-sm font-bold text-gray-900 leading-tight">{{ $plan->name }}
                                            </div>
                                            <div
                                                class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mt-0.5">
                                                ID: #PLN-{{ $plan->id }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-center">
                                        <div class="text-sm font-bold text-emerald-600 leading-tight">
                                            {{ $currency }}{{ number_format($plan->price, 0) }}</div>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">
                                            {{ $plan->duration_days }} Days</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-center">
                                        <button type="button"
                                            onclick="togglePlanStatusMenu(event, {{ $plan->id }}, {{ $plan->status }})"
                                            class="status-btn-{{ $plan->id }} px-2 py-0.5 inline-flex items-center justify-between text-[7px] font-black uppercase tracking-[0.15em] rounded border transition cursor-pointer mx-auto
                                                @if($plan->status) bg-green-50 text-green-700 border-green-100/50 hover:bg-green-100
                                                @else bg-red-50 text-red-700 border-red-100/50 hover:bg-red-100 @endif">
                                            {{ $plan->status ? 'Active' : 'Inactive' }}
                                            <svg class="w-2.5 h-2.5 ml-1.5 opacity-60" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-right text-xs">
                                        <div class="flex justify-end gap-2 text-sm font-medium">
                                            <button type="button" onclick='openEditPlanModal(@json($plan))'
                                                class="text-indigo-600 hover:text-indigo-900 transition-colors p-1.5 bg-indigo-50 rounded-lg group"
                                                title="Edit Plan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </button>
                                            <button type="button"
                                                onclick="confirmDeletePlan('{{ route('plans.destroy', $plan) }}')"
                                                class="text-red-600 hover:text-red-900 transition-colors p-1.5 bg-red-50 rounded-lg no-loader"
                                                title="Delete Plan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500 italic">No subscription
                                        plans found.</td>
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

    <!-- Global Status Menu -->
    <div id="status-portal-menu"
        class="hidden fixed z-[9999] w-32 rounded-xl shadow-2xl bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none">
        <div class="py-1" id="portal-menu-content">
            <!-- Content will be injected via JS -->
        </div>
    </div>

    <!-- Create Plan Modal -->
    <x-modal name="create-plan" :show="$errors->any()" focusable>
        <form method="post" action="{{ route('plans.store') }}" class="p-8">
            @csrf
            <h2 class="text-xl font-bold text-gray-900 border-b border-gray-100 pb-4">Create New Plan</h2>

            <div class="mt-6 space-y-6">
                <div>
                    <x-input-label for="create_name" value="Plan Name" />
                    <x-text-input id="create_name" name="name" type="text"
                        class="mt-1 p-2 block w-full bg-gray-50 focus:bg-white" placeholder="e.g. Premium Access"
                        required />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="create_price" value="Price ({{ $currency }})" />
                        <x-text-input id="create_price" name="price" type="number" step="1" min="0"
                            class="mt-1 block w-full bg-gray-50 p-2 focus:bg-white" placeholder="100" required />
                    </div>

                    <div>
                        <x-input-label for="create_duration" value="Duration (Days)" />
                        <x-text-input id="create_duration" name="duration_days" type="number" min="1"
                            class="mt-1 block p-2 w-full bg-gray-50 focus:bg-white" placeholder="365" required />
                    </div>
                </div>

                <div>
                    <x-input-label for="create_status" value="Status" />
                    <select name="status" id="create_status"
                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white cursor-pointer"
                        required>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <input type="hidden" name="trial_days" value="0">

            <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-6">
                <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-200">Save
                    Plan</x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Edit Plan Modal -->
    <x-modal name="edit-plan" focusable>
        <form id="edit-plan-form" method="post" action="" class="p-8">
            @csrf
            @method('PATCH')
            <h2 class="text-xl font-bold text-gray-900 border-b border-gray-100 pb-4">Edit Subscription Plan</h2>

            <div class="mt-6 space-y-6">
                <div>
                    <x-input-label for="edit_name" value="Plan Name" />
                    <x-text-input id="edit_name" name="name" type="text"
                        class="mt-1 block w-full bg-gray-50 focus:bg-white" required />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="edit_price" value="Price ({{ $currency }})" />
                        <x-text-input id="edit_price" name="price" type="number" step="1" min="0"
                            class="mt-1 block w-full bg-gray-50 focus:bg-white" required />
                    </div>

                    <div>
                        <x-input-label for="edit_duration" value="Duration (Days)" />
                        <x-text-input id="edit_duration" name="duration_days" type="number" min="1"
                            class="mt-1 block w-full bg-gray-50 focus:bg-white" required />
                    </div>
                </div>

                <div>
                    <x-input-label for="edit_status" value="Status" />
                    <select name="status" id="edit_status"
                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white cursor-pointer"
                        required>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <input type="hidden" name="trial_days" id="edit_trial" value="0">

            <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-6">
                <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-200">Update
                    Plan</x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal name="confirm-delete-plan" focusable>
        <form id="delete-plan-form" method="post" action="" class="p-8">
            @csrf
            @method('DELETE')

            <div class="flex items-center gap-4 text-red-600 mb-4">
                <div class="p-3 bg-red-50 rounded-full ring-8 ring-red-50/50">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold">Archive Plan?</h2>
            </div>

            <p class="text-sm text-gray-600 mb-8 leading-relaxed">
                Are you sure you want to archive this subscription plan? Existing subscriptions using this plan will
                remain active until their expiry, but no new subscriptions can be created using this plan.
            </p>

            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                <x-secondary-button x-on:click="$dispatch('close')">Keep Plan</x-secondary-button>
                <button type="submit"
                    class="inline-flex items-center px-6 py-2.5 bg-red-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none transition-all shadow-lg shadow-red-200">
                    Confirm Archive
                </button>
            </div>
        </form>
    </x-modal>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search-input');
            const searchForm = document.getElementById('search-form');

            if (searchInput) {
                if (searchInput.value !== "") {
                    searchInput.focus();
                    const val = searchInput.value;
                    searchInput.value = '';
                    searchInput.value = val;
                }
                searchInput.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        searchForm.submit();
                    }
                });
            }

            searchForm.addEventListener('submit', function () {
                document.getElementById('table-loader').classList.remove('hidden');
            });

            // Close portal menu when clicking outside
            document.addEventListener('click', function (event) {
                const menu = document.getElementById('status-portal-menu');
                if (!menu.contains(event.target) && !event.target.closest('[class^="status-btn-"]')) {
                    menu.classList.add('hidden');
                }
            });

            window.addEventListener('scroll', () => document.getElementById('status-portal-menu').classList.add('hidden'), true);
            window.addEventListener('resize', () => document.getElementById('status-portal-menu').classList.add('hidden'));
        });

        function togglePlanStatusMenu(event, id, currentStatus) {
            event.stopPropagation();
            const menu = document.getElementById('status-portal-menu');
            const content = document.getElementById('portal-menu-content');
            const btn = event.currentTarget;
            const rect = btn.getBoundingClientRect();

            if (!menu.classList.contains('hidden') && menu.dataset.activeId == id) {
                menu.classList.add('hidden');
                return;
            }

            const statuses = [
                { value: 1, label: 'Activate', color: 'hover:bg-green-50 hover:text-green-700', active: currentStatus == 1 },
                { value: 0, label: 'Deactivate', color: 'hover:bg-red-50 hover:text-red-700', active: currentStatus == 0 }
            ];

            let html = '';
            statuses.forEach(s => {
                if (!s.active) {
                    html += `
                        <form action="/plans/${id}/status" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="PATCH">
                            <input type="hidden" name="status" value="${s.value}">
                            <button type="submit" class="no-loader group flex items-center px-4 py-1.5 text-[8.5px] font-bold uppercase tracking-widest text-gray-700 w-full text-left transition ${s.color}">
                                ${s.label}
                            </button>
                        </form>
                    `;
                }
            });

            content.innerHTML = html || '<div class="px-4 py-1.5 text-[8.5px] font-bold text-gray-400 italic">No Actions</div>';
            menu.dataset.activeId = id;

            const menuHeight = 80;
            const spaceBelow = window.innerHeight - rect.bottom;
            menu.style.top = (spaceBelow < menuHeight ? (rect.top + window.scrollY - menuHeight - 8) : (rect.bottom + window.scrollY + 8)) + 'px';
            menu.style.left = (rect.left + window.scrollX) + 'px';
            menu.classList.remove('hidden');
        }

        function openEditPlanModal(plan) {
            const form = document.getElementById('edit-plan-form');
            form.action = `/plans/${plan.id}`;
            document.getElementById('edit_name').value = plan.name;
            document.getElementById('edit_price').value = plan.price;
            document.getElementById('edit_duration').value = plan.duration_days;
            document.getElementById('edit_status').value = plan.status;

            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-plan' }));
        }

        function confirmDeletePlan(action) {
            const form = document.getElementById('delete-plan-form');
            form.action = action;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-delete-plan' }));
        }
    </script>
</x-admin-layout>