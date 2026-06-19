<x-admin-layout title="Institutes Management">
    <!-- Filters & Search -->
    <div class=" rounded-2xl mb-3">
        <form id="search-form" action="{{ route('institutes.index') }}" method="GET"
            class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none" id="search-icon">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center hidden" id="search-loader">
                    <svg class="animate-spin h-5 w-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </div>
                <input type="text" id="search-input" name="search" value="{{ request('search') }}" autocomplete="off"
                    placeholder="Search by name, institute, email or phone..."
                    class="block w-full pl-10 pr-24 py-2.5 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm transition">
                <!-- Search Button inside input -->
                <div class="absolute inset-y-0 right-0 flex items-center pr-1">
                    <button type="submit"
                        class="no-loader inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary hover:opacity-90 text-white text-xs font-semibold rounded-lg transition">
                        Search
                    </button>
                </div>
            </div>

            <div class="w-full md:w-48">
                <select name="status" onchange="this.form.submit()"
                    class="block w-full pl-3 pr-10 py-2.5 text-sm border border-gray-200 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl bg-white transition font-medium text-gray-700 cursor-pointer">
                    <option value="all">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('institutes.create') }}"
                    class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-xl shadow-lg shadow-primary/20 text-sm font-bold text-white bg-primary hover:opacity-90 focus:outline-none transition transform active:scale-95 whitespace-nowrap">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                        </path>
                    </svg>
                    Add Institute
                </a>
            </div> <!-- @if(request()->has('search') || request()->has('status'))
                    <a href="{{ route('institutes.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 hover:text-red-800 transition">
                        Clear Filters
                    </a>
                @endif -->
        </form>
    </div>

    <!-- Table Section -->
    <div class="relative bg-white border border-gray-100 rounded-2xl shadow-sm">
        <!-- Table Loading Overlay -->
        <div id="table-loader"
            class="hidden absolute inset-0 bg-white/70 backdrop-blur-sm rounded-2xl z-10 flex items-center justify-center">
            <div class="flex flex-col items-center gap-3">
                <svg class="animate-spin h-8 w-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span class="text-xs font-semibold text-primary uppercase tracking-widest">Searching...</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/75">
                    <tr>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Institute</th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Contact Info</th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Location</th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th scope="col"
                            class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100" id="institutes-table-body">
                    @forelse($institutes as $institute)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-1">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm overflow-hidden border border-primary/20 shadow-sm">
                                        @if($institute->logo)
                                            <img src="{{ asset('storage/' . $institute->logo) }}"
                                                class="h-full w-full object-cover">
                                        @else
                                            {{ substr($institute->institute_name ?? $institute->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900 leading-tight">
                                            {{ $institute->institute_name }}
                                        </div>
                                        <div class="text-xs text-gray-500">Owner: {{ $institute->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3">
                                <div class="text-sm text-gray-900 font-medium">{{ $institute->email }}</div>
                                <div class="text-xs text-gray-500">{{ $institute->phone }}</div>
                            </td>
                            <td class="px-6 py-3">
                                <div class="text-sm text-gray-900">{{ $institute->city ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $institute->state ?? '' }}</div>
                            </td>
                            <td class="px-6 py-3">
                                <button type="button"
                                    onclick="togglePortalMenu(event, {{ $institute->id }}, '{{ $institute->status }}')"
                                    class="status-btn-{{ $institute->id }} no-loader px-2 py-0.5 inline-flex items-center justify-between text-[8.5px] font-bold uppercase tracking-widest leading-none rounded border transition cursor-pointer
                                            @if($institute->status === 'active') bg-green-50 text-green-700 border-green-100/50 hover:bg-green-100
                                            @elseif($institute->status === 'blocked') bg-red-50 text-red-700 border-red-100/50 hover:bg-red-100
                                            @else bg-gray-50 text-gray-700 border-gray-100/50 hover:bg-gray-100 @endif">
                                    {{ $institute->status }}
                                    <svg class="w-3 h-3 ml-2 opacity-60" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('institutes.show', $institute) }}"
                                        class="text-emerald-600 hover:text-emerald-900 transition-colors p-1.5 bg-emerald-50 rounded-lg"
                                        title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('institutes.edit', [$institute, 'from' => 'index']) }}"
                                        class="text-primary hover:opacity-80 transition-colors p-1.5 bg-primary/10 rounded-lg"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <button type="button"
                                        onclick="confirmDelete('{{ route('institutes.destroy', $institute) }}')"
                                        class="text-red-600 hover:text-red-900 transition-colors p-1.5 bg-red-50 rounded-lg no-loader"
                                        title="Delete">
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
                            <td colspan="5" class="p-0">
                                <x-empty-state title="No institutes found"
                                    subtitle="No institutes found matching your criteria. Try adjusting your search query or filters."
                                    icon="users" plain="true" class="py-12" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($institutes->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $institutes->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- Global Portal Dropdown -->
    <div id="status-portal-menu"
        class="hidden fixed z-[9999] w-32 rounded-xl shadow-2xl bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none">
        <div class="py-1" id="portal-menu-content">
            <!-- Content will be injected via JS -->
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search-input');
            const searchForm = document.getElementById('search-form');
            let timeout = null;

            if (searchInput) {
                // Restore cursor position after page reload
                if (searchInput.value !== "") {
                    searchInput.focus();
                    const val = searchInput.value;
                    searchInput.value = '';
                    searchInput.value = val;
                }
                // Submit on Enter key
                searchInput.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        searchForm.submit();
                    }
                });
            }

            // Show table loader on form submit
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

            // Handle window resize/scroll to hide menu (avoiding ghost menus)
            window.addEventListener('scroll', () => document.getElementById('status-portal-menu').classList.add('hidden'), true);
            window.addEventListener('resize', () => document.getElementById('status-portal-menu').classList.add('hidden'));
        });

        function togglePortalMenu(event, id, currentStatus) {
            event.stopPropagation();
            const menu = document.getElementById('status-portal-menu');
            const content = document.getElementById('portal-menu-content');
            const btn = event.currentTarget;
            const rect = btn.getBoundingClientRect();

            // Toggle logic
            if (!menu.classList.contains('hidden') && menu.dataset.activeId == id) {
                menu.classList.add('hidden');
                return;
            }

            // Build menu content
            const statuses = [
                { value: 'active', label: 'Activate', color: 'hover:bg-green-50 hover:text-green-700' },
                { value: 'blocked', label: 'Block', color: 'hover:bg-red-50 hover:text-red-700' }
            ];

            let html = '';
            statuses.forEach(s => {
                html += `
                    <form action="/admin/institutes/${id}/status" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="status" value="${s.value}">
                        <button type="submit" class="no-loader group flex items-center px-4 py-1.5 text-[8.5px] font-bold uppercase tracking-widest text-gray-700 w-full text-left transition ${s.color}">
                            ${s.label}
                        </button>
                    </form>
                `;
            });

            content.innerHTML = html;
            menu.dataset.activeId = id;

            // Positioning Logic
            const menuHeight = 160; // Approximate height
            const spaceBelow = window.innerHeight - rect.bottom;

            if (spaceBelow < menuHeight) {
                // Open Upward
                menu.style.top = (rect.top + window.scrollY - menuHeight - 8) + 'px';
            } else {
                // Open Downward
                menu.style.top = (rect.bottom + window.scrollY + 8) + 'px';
            }

            menu.style.left = (rect.left + window.scrollX) + 'px';
            menu.classList.remove('hidden');
        }

        // Modal Logic
        function confirmDelete(actionUrl) {
            document.getElementById('deleteForm').action = actionUrl;
            document.getElementById('deleteModal').classList.remove('hidden');
        }
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background Overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50 backdrop-blur-sm" aria-hidden="true"
                onclick="closeDeleteModal()"></div>

            <!-- Modal Placement Trick -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Dialog -->
            <div
                class="inline-block px-6 py-6 overflow-hidden text-center align-middle transform bg-white rounded-2xl shadow-xl sm:my-8 sm:max-w-sm sm:w-full border border-gray-100">
                <div class="flex flex-col items-center">
                    <div class="flex items-center justify-center w-14 h-14 bg-red-50 rounded-full mb-4">
                        <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1" id="modal-title">Delete Institute?</h3>
                    <p class="text-[10px] text-gray-500 font-medium px-4">All associated data will be permanently
                        removed. This action cannot be undone. Are you sure you want to proceed?</p>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex items-center gap-3">
                    <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 py-2.5 text-[10px] font-bold text-gray-400 hover:text-gray-600 transition uppercase tracking-widest border border-gray-100 rounded-xl">Cancel</button>
                    <form id="deleteForm" method="POST" action="" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="w-full py-2.5 text-[10px] font-bold text-white bg-primary rounded-xl shadow-lg shadow-primary/20 hover:bg-primary/90 transition uppercase tracking-widest">Yes,
                            Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>