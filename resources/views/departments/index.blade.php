<x-admin-layout title="Staff Departments">
    <div class="py-0">
        <div class="max-w-7xl mx-auto">

            <!-- Filters & Search -->
            <div class="mb-4">
                <form id="search-form" action="{{ route('departments.index') }}" method="GET"
                    class="flex flex-col md:flex-row gap-4 items-center">
                    
                    <!-- Search input -->
                    <div class="flex-1 relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="search-input" name="search" value="{{ request('search') }}"
                            autocomplete="off" placeholder="Search by department name..."
                            class="block w-full pl-10 pr-24 py-2.5 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm transition">
                        
                        <div class="absolute inset-y-0 right-0 flex items-center pr-1">
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary hover:opacity-90 text-white text-xs font-semibold rounded-lg transition">
                                Search
                            </button>
                        </div>
                    </div>



                    <!-- Create New Department Button -->
                    <div class="flex items-center w-full md:w-auto md:ml-auto shrink-0">
                        <button type="button" @click="openCreateModal()"
                            class="relative w-full md:w-auto inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-primary hover:opacity-90 focus:outline-none transition shadow-primary/20 whitespace-nowrap min-w-[170px]">
                            <span class="flex items-center">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create Department
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
                        <svg class="animate-spin h-8 w-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span class="text-xs font-semibold text-primary uppercase tracking-widest">Searching...</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/75 border-b border-gray-100">
                                <th class="px-5 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Department</th>
                                <th class="px-5 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Created At</th>
                                <th class="px-5 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($departments as $dept)
                                <tr class="hover:bg-gray-50/50 transition duration-150">
                                    
                                    <!-- Department Info -->
                                    <td class="px-5 py-3 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-bold text-gray-900 leading-tight">{{ $dept->name }}</div>
                                            <div class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mt-0.5">
                                                ID: #DEPT-{{ $dept->id }}
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Created Date -->
                                    <td class="px-5 py-3 whitespace-nowrap text-center">
                                        <span class="text-xs font-bold text-gray-500">
                                            {{ $dept->created_at ? $dept->created_at->format('M d, Y') : '—' }}
                                        </span>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-5 py-3 whitespace-nowrap text-right text-xs">
                                        <div class="flex justify-end gap-2 text-sm font-medium">
                                            
                                            <!-- Edit button -->
                                            <button type="button" @click="openEditModal('{{ route('departments.update', $dept->id) }}', '{{ addslashes($dept->name) }}')"
                                                class="text-indigo-600 hover:text-indigo-900 transition-colors p-1.5 bg-indigo-50 hover:bg-indigo-100 rounded-lg cursor-pointer"
                                                title="Edit Department">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </button>

                                            <!-- Delete button -->
                                            <button type="button"
                                                onclick="confirmDeleteDepartment('{{ route('departments.destroy', $dept->id) }}', '{{ addslashes($dept->name) }}')"
                                                class="text-red-600 hover:text-red-900 transition-colors p-1.5 bg-red-50 hover:bg-red-100 rounded-lg no-loader"
                                                title="Delete Department">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-0">
                                        <x-empty-state 
                                            title="No staff departments found" 
                                            subtitle="No staff departments matching your criteria were found. Click 'Create Department' to add one." 
                                            icon="teacher"
                                            plain="true"
                                            class="py-12"
                                        />
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($departments->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $departments->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-modal name="confirm-delete-dept" maxWidth="lg" focusable>
        <form id="delete-dept-form" method="post" action="" class="p-5">
            @csrf
            @method('DELETE')

            <div class="flex items-start gap-3 mb-3">
                <div class="w-10 h-10 flex items-center justify-center bg-primary/10 rounded-full shrink-0">
                    <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-black text-gray-900 leading-tight uppercase">Delete Department?</h2>
                    <p class="text-[10px] font-bold text-primary uppercase tracking-widest mt-0.5">Irreversible Action</p>
                </div>
            </div>

            <p class="text-[13px] text-gray-500 mb-5 leading-relaxed">
                Are you sure you want to delete the department <strong id="delete-dept-name" class="text-gray-900"></strong>?
                This department will be removed from all associated staff members.
            </p>

            <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                <button type="button" x-on:click="$dispatch('close')" 
                    class="px-4 py-2 border border-gray-200 text-gray-500 rounded-xl font-bold text-[11px] uppercase tracking-widest hover:bg-gray-50 transition-all">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-primary text-white rounded-xl font-bold text-[11px] uppercase tracking-widest hover:opacity-90 shadow-lg shadow-primary/20 transition-all">
                    Yes, Delete Department
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Create Department Modal -->
    <x-modal name="create-dept" maxWidth="lg" focusable>
        <form method="POST" action="{{ route('departments.store') }}" class="p-6">
            @csrf
            
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center border border-orange-100">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900 leading-tight">Create Staff Department</h3>
                    <p class="text-[10px] font-bold text-primary uppercase tracking-widest mt-0.5">Add a new global department</p>
                </div>
            </div>

            <!-- Department Name Input -->
            <div class="mt-4">
                <x-input-label for="create-name" value="Department Name" class="text-xs font-semibold text-gray-700" />
                <x-text-input id="create-name" name="name" type="text"
                    class="mt-1.5 p-2.5 block w-full bg-gray-50 border border-gray-200 focus:bg-white text-sm rounded-xl focus:ring-primary focus:border-primary transition"
                    placeholder="e.g. Science, Commerce, Administration"
                    required />
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 mt-6">
                <button type="button" x-on:click="$dispatch('close')" 
                    class="px-4 py-2.5 border border-gray-200 text-gray-500 rounded-xl font-bold text-[11px] uppercase tracking-widest hover:bg-gray-50 transition-all text-center">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2.5 bg-primary text-white rounded-xl font-bold text-[11px] uppercase tracking-widest hover:opacity-90 shadow-lg shadow-primary/20 transition-all">
                    Save Department
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Edit Department Modal -->
    <x-modal name="edit-dept" maxWidth="lg" focusable>
        <form id="edit-dept-form" method="POST" action="" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center border border-orange-100">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900 leading-tight">Edit Staff Department</h3>
                    <p class="text-[10px] font-bold text-primary uppercase tracking-widest mt-0.5">Modify global department name</p>
                </div>
            </div>

            <!-- Department Name Input -->
            <div class="mt-4">
                <x-input-label for="edit-name" value="Department Name" class="text-xs font-semibold text-gray-700" />
                <x-text-input id="edit-name" name="name" type="text"
                    class="mt-1.5 p-2.5 block w-full bg-gray-50 border border-gray-200 focus:bg-white text-sm rounded-xl focus:ring-primary focus:border-primary transition"
                    placeholder="e.g. Science, Commerce, Administration"
                    required />
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 mt-6">
                <button type="button" x-on:click="$dispatch('close')" 
                    class="px-4 py-2.5 border border-gray-200 text-gray-500 rounded-xl font-bold text-[11px] uppercase tracking-widest hover:bg-gray-50 transition-all text-center">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2.5 bg-primary text-white rounded-xl font-bold text-[11px] uppercase tracking-widest hover:opacity-90 shadow-lg shadow-primary/20 transition-all">
                    Update Department
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Toast Notification Pop-up -->
    @if(session('success'))
        <div x-data="{ show: true }" 
             x-init="setTimeout(() => show = false, 4000)"
             x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-[-20px]"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-[-20px]"
             class="fixed top-6 right-6 z-[9999]">
            <div class="bg-emerald-600 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 min-w-[300px]">
                <div class="bg-white/20 p-2 rounded-xl">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-black uppercase tracking-widest opacity-80">Success</p>
                    <p class="text-sm font-bold">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="ml-auto text-white/50 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
    @endif

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
            }

            searchForm.addEventListener('submit', function () {
                document.getElementById('table-loader').classList.remove('hidden');
            });
        });

        function openCreateModal() {
            document.getElementById('create-name').value = '';
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'create-dept' }));
        }

        function openEditModal(action, name) {
            const form = document.getElementById('edit-dept-form');
            form.action = action;
            document.getElementById('edit-name').value = name;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-dept' }));
        }

        function confirmDeleteDepartment(action, name) {
            const form = document.getElementById('delete-dept-form');
            form.action = action;
            document.getElementById('delete-dept-name').innerText = name;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-delete-dept' }));
        }
    </script>
</x-admin-layout>
