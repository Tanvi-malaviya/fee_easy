<x-admin-layout title="Institutes Management">
    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm mb-3">
        <form id="search-form" action="{{ route('institutes.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
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
                    placeholder="Search by name, institute, email or phone..." 
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:bg-white focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition">
            </div>
            
            <div class="w-full md:w-48">
                <select name="status" onchange="this.form.submit()" 
                    class="block w-full pl-3 pr-10 py-2.5 text-sm border-gray-200 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl bg-gray-50 transition font-medium text-gray-700 cursor-pointer">
                    <option value="all">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('institutes.create') }}" id="create-btn" onclick="showBtnLoader(this)"
                    class="relative inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition shadow-indigo-600/20 whitespace-nowrap min-w-[170px]">
                    <span class="flex items-center btn-content">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create New Institute
                    </span>
                    <span class="hidden btn-loader">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </a>
                <!-- @if(request()->has('search') || request()->has('status'))
                    <a href="{{ route('institutes.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 hover:text-red-800 transition">
                        Clear Filters
                    </a>
                @endif -->
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/75">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Institute</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact Info</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Location</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100" id="institutes-table-body">
                    @forelse($institutes as $institute)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-1">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm overflow-hidden border border-gray-100 shadow-sm">
                                        @if($institute->logo)
                                            <img src="{{ asset('storage/' . $institute->logo) }}" class="h-full w-full object-cover">
                                        @else
                                            {{ substr($institute->institute_name ?? $institute->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900 leading-tight">{{ $institute->institute_name }}</div>
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
                                <button 
                                    type="button" 
                                    onclick="togglePortalMenu(event, {{ $institute->id }}, '{{ $institute->status }}')"
                                    class="status-btn-{{ $institute->id }} px-2 py-0.5 inline-flex items-center justify-between text-[7px] font-black uppercase tracking-[0.15em] rounded border transition cursor-pointer
                                    @if($institute->status === 'active') bg-green-50 text-green-700 border-green-100/50 hover:bg-green-100
                                    @elseif($institute->status === 'suspended') bg-amber-50 text-amber-700 border-amber-100/50 hover:bg-amber-100
                                    @elseif($institute->status === 'blocked') bg-red-50 text-red-700 border-red-100/50 hover:bg-red-100
                                    @else bg-gray-50 text-gray-700 border-gray-100/50 hover:bg-gray-100 @endif">
                                    {{ $institute->status }}
                                    <svg class="w-3.5 h-3.5 ml-2 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('institutes.show', $institute) }}" 
                                        class="text-emerald-600 hover:text-emerald-900 transition-colors p-1.5 bg-emerald-50 rounded-lg" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('institutes.edit', $institute) }}" 
                                        class="text-indigo-600 hover:text-indigo-900 transition-colors p-1.5 bg-indigo-50 rounded-lg" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('institutes.destroy', $institute) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this institute?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors p-1.5 bg-red-50 rounded-lg no-loader" title="Delete">
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
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">
                                No institutes found matching your criteria.
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
    <div id="status-portal-menu" class="hidden fixed z-[9999] w-32 rounded-xl shadow-2xl bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none">
        <div class="py-1" id="portal-menu-content">
            <!-- Content will be injected via JS -->
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const searchForm = document.getElementById('search-form');
            let timeout = null;

            if (searchInput) {
                // To maintain focus and put cursor at the end
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
                    }, 500); // 500ms debounce
                });
            }

            // Close portal menu when clicking outside
            document.addEventListener('click', function(event) {
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
                { value: 'inactive', label: 'Deactivate', color: 'hover:bg-gray-50' },
                { value: 'suspended', label: 'Suspend', color: 'hover:bg-amber-50 hover:text-amber-700' },
                { value: 'blocked', label: 'Block', color: 'hover:bg-red-50 hover:text-red-700' }
            ];

            let html = '';
            statuses.forEach(s => {
                html += `
                    <form action="/institutes/${id}/status" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="status" value="${s.value}">
                        <button type="submit" class="group flex items-center px-4 py-1.5 text-[8.5px] font-bold uppercase tracking-widest text-gray-700 w-full text-left transition ${s.color}">
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

        function showBtnLoader(btn) {
            btn.querySelector('.btn-content').classList.add('invisible');
            btn.querySelector('.btn-loader').classList.remove('hidden');
            btn.querySelector('.btn-loader').classList.add('absolute', 'flex', 'inset-0', 'items-center', 'justify-center');
            btn.classList.add('opacity-90', 'cursor-not-allowed');
            btn.style.pointerEvents = 'none';
        }
    </script>
</x-admin-layout>