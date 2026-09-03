<x-admin-layout title="Add-ons">
    <div class="py-0">
        <div class="max-w-7xl mx-auto">

            <!-- Filters & Search -->
            <div class="mb-3">
                <form id="search-form" action="{{ route('addons.index') }}" method="GET"
                    class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="search-input" name="search" value="{{ request('search') }}"
                            autocomplete="off" placeholder="Search by add-on name..."
                            class="block w-full pl-10 pr-24 py-2.5 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm transition">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-1">
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary hover:opacity-90 text-white text-xs font-semibold rounded-lg transition">
                                Search
                            </button>
                        </div>
                    </div>

                    <div class="w-full md:w-48">
                        <select name="status" onchange="this.form.submit()"
                            class="block w-full pl-3 pr-10 py-2.5 text-sm border border-gray-200 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary rounded-xl bg-white transition font-medium text-gray-700 cursor-pointer appearance-none">
                            <option value="all">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Enabled</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Disabled</option>
                        </select>
                    </div>

                    <div class="flex items-center ml-auto">
                        <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'create-addon' }))"
                            class="relative inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-primary hover:opacity-90 focus:outline-none transition shadow-primary/20 whitespace-nowrap min-w-[150px]">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add New Add-on
                        </button>
                    </div>
                </form>
            </div>

            <!-- Management Card -->
            <div class="relative bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/75 border-b border-gray-100">
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Add-on</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Price</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Kind</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($addOns as $addOn)
                                <tr class="hover:bg-gray-50/50 transition duration-150">
                                    <td class="px-4 py-2">
                                        <div class="text-sm font-bold text-gray-900 leading-tight">{{ $addOn->name }}</div>
                                        <div class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mt-0.5">{{ $addOn->slug }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-center">
                                        <div class="text-sm font-bold text-emerald-600 leading-tight">{{ $addOn->formatted_price }}</div>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">{{ $addOn->billing_type }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-center">
                                        <span class="px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-600 border border-indigo-100 text-[9px] font-bold uppercase">{{ $addOn->kind }}</span>
                                        @if($addOn->kind === 'quota' && $addOn->quota_key)
                                            <div class="text-[10px] text-gray-400 mt-0.5">{{ $addOn->quota_key }}: {{ $addOn->quota_value }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-center">
                                        <form action="{{ route('addons.status', $addOn) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="enabled" value="{{ $addOn->enabled ? 0 : 1 }}">
                                            <button type="submit"
                                                class="px-2 py-0.5 inline-flex items-center text-[9px] font-bold uppercase tracking-widest leading-none rounded border transition cursor-pointer
                                                    @if($addOn->enabled) bg-emerald-50 text-emerald-600 border-emerald-100 hover:bg-emerald-100
                                                    @else bg-rose-50 text-rose-600 border-rose-100 hover:bg-rose-100 @endif">
                                                {{ $addOn->enabled ? 'Enabled' : 'Disabled' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-right text-xs">
                                        <div class="flex justify-end gap-2 text-sm font-medium">
                                            <button type="button" onclick='openEditAddonModal(@json($addOn))'
                                                class="text-indigo-600 hover:text-indigo-900 transition-colors p-1.5 bg-indigo-50 rounded-lg"
                                                title="Edit Add-on">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            @if($addOn->kind !== 'custom')
                                                <button type="button" onclick="confirmDeleteAddon('{{ route('addons.destroy', $addOn) }}')"
                                                    class="text-red-600 hover:text-red-900 transition-colors p-1.5 bg-red-50 rounded-lg"
                                                    title="Delete Add-on">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-0">
                                        <x-empty-state
                                            title="No add-ons found"
                                            subtitle="No add-ons matching your filters were found."
                                            icon="fees"
                                            plain="true"
                                            class="py-12"
                                        />
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($addOns->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $addOns->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create Add-on Modal -->
    <x-modal name="create-addon" :show="$errors->any()" maxWidth="lg" focusable>
        <form method="post" action="{{ route('addons.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3">Add New Add-on</h2>

            <div class="mt-3 space-y-3">
                <div>
                    <x-input-label for="create_name" value="Add-on Name" class="text-xs font-semibold" />
                    <x-text-input id="create_name" name="name" type="text"
                        class="mt-0.5 p-2 block w-full bg-gray-50 focus:bg-white text-sm" placeholder="e.g. Priority Support"
                        required />
                </div>

                <div>
                    <x-input-label for="create_description" value="Description" class="text-xs font-semibold" />
                    <textarea id="create_description" name="description" rows="2"
                        class="mt-0.5 block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring-primary transition p-2 border text-sm text-gray-900 bg-gray-50 focus:bg-white outline-none"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <x-input-label for="create_price" value="Price ({{ $currency }})" class="text-xs font-semibold" />
                        <x-text-input id="create_price" name="price" type="number" step="1" min="0" max="999999"
                            class="mt-0.5 block w-full bg-gray-50 p-2 focus:bg-white text-sm" placeholder="1000" required />
                    </div>
                    <div>
                        <x-input-label for="create_billing_type" value="Billing Label" class="text-xs font-semibold" />
                        <x-text-input id="create_billing_type" name="billing_type" type="text"
                            class="mt-0.5 block w-full bg-gray-50 p-2 focus:bg-white text-sm" placeholder="One Time" value="One Time" />
                    </div>
                </div>

                <div>
                    <x-input-label for="create_kind" value="Kind" class="text-xs font-semibold" />
                    <select name="kind" id="create_kind" onchange="toggleQuotaFields('create')"
                        class="mt-0.5 block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring-primary transition px-3 py-2 border text-sm text-gray-900 bg-gray-50 focus:bg-white cursor-pointer outline-none"
                        required>
                        <option value="flag">Flag — purchase grants a yes/no entitlement</option>
                        <option value="quota">Quota — purchase sets a numeric limit</option>
                        <option value="custom">Custom — needs its own backend code (advanced)</option>
                    </select>
                    <p class="text-[11px] text-gray-400 mt-1">Cannot be changed after creation.</p>
                </div>

                <div id="create_quota_fields" class="grid grid-cols-2 gap-3 hidden">
                    <div>
                        <x-input-label for="create_quota_key" value="Quota Key" class="text-xs font-semibold" />
                        <x-text-input id="create_quota_key" name="quota_key" type="text"
                            class="mt-0.5 block w-full bg-gray-50 p-2 focus:bg-white text-sm" placeholder="extra_storage_mb" />
                    </div>
                    <div>
                        <x-input-label for="create_quota_value" value="Quota Value" class="text-xs font-semibold" />
                        <x-text-input id="create_quota_value" name="quota_value" type="number" step="1" min="0"
                            class="mt-0.5 block w-full bg-gray-50 p-2 focus:bg-white text-sm" placeholder="5000" />
                    </div>
                </div>

                <div>
                    <x-input-label for="create_enabled" value="Status" class="text-xs font-semibold" />
                    <select name="enabled" id="create_enabled"
                        class="mt-0.5 block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring-primary transition px-3 py-2 border text-sm text-gray-900 bg-gray-50 focus:bg-white cursor-pointer outline-none">
                        <option value="1">Enabled (purchasable)</option>
                        <option value="0">Disabled</option>
                    </select>
                </div>
            </div>

            <div class="mt-5 flex justify-end gap-3 border-t border-gray-100 pt-3">
                <x-secondary-button x-on:click="$dispatch('close')" class="text-xs">Cancel</x-secondary-button>
                <x-primary-button class="bg-primary hover:opacity-90 shadow-lg shadow-primary/20 text-xs">Create Add-on</x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Edit Add-on Modal -->
    <x-modal name="edit-addon" maxWidth="lg" focusable>
        <form id="edit-addon-form" method="post" action="" class="p-6">
            @csrf
            @method('PATCH')
            <h2 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3">Edit Add-on</h2>

            <div class="mt-3 space-y-3">
                <div>
                    <x-input-label for="edit_name" value="Add-on Name" class="text-xs font-semibold" />
                    <x-text-input id="edit_name" name="name" type="text"
                        class="mt-0.5 block w-full bg-gray-50 focus:bg-white text-sm" required />
                </div>

                <div>
                    <x-input-label for="edit_description" value="Description" class="text-xs font-semibold" />
                    <textarea id="edit_description" name="description" rows="2"
                        class="mt-0.5 block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring-primary transition p-2 border text-sm text-gray-900 bg-gray-50 focus:bg-white outline-none"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <x-input-label for="edit_price" value="Price ({{ $currency }})" class="text-xs font-semibold" />
                        <x-text-input id="edit_price" name="price" type="number" step="1" min="0" max="999999"
                            class="mt-0.5 block w-full bg-gray-50 focus:bg-white text-sm" required />
                    </div>
                    <div>
                        <x-input-label for="edit_billing_type" value="Billing Label" class="text-xs font-semibold" />
                        <x-text-input id="edit_billing_type" name="billing_type" type="text"
                            class="mt-0.5 block w-full bg-gray-50 focus:bg-white text-sm" />
                    </div>
                </div>

                <div>
                    <x-input-label value="Kind" class="text-xs font-semibold" />
                    <div id="edit_kind_display" class="mt-0.5 px-3 py-2 rounded-xl bg-gray-100 text-sm text-gray-500 font-medium"></div>
                </div>

                <div id="edit_quota_fields" class="grid grid-cols-2 gap-3 hidden">
                    <div>
                        <x-input-label for="edit_quota_key" value="Quota Key" class="text-xs font-semibold" />
                        <x-text-input id="edit_quota_key" name="quota_key" type="text"
                            class="mt-0.5 block w-full bg-gray-50 focus:bg-white text-sm" />
                    </div>
                    <div>
                        <x-input-label for="edit_quota_value" value="Quota Value" class="text-xs font-semibold" />
                        <x-text-input id="edit_quota_value" name="quota_value" type="number" step="1" min="0"
                            class="mt-0.5 block w-full bg-gray-50 focus:bg-white text-sm" />
                    </div>
                </div>

                <div>
                    <x-input-label for="edit_enabled" value="Status" class="text-xs font-semibold" />
                    <select name="enabled" id="edit_enabled"
                        class="mt-0.5 block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring-primary transition px-3 py-2 border text-sm text-gray-900 bg-gray-50 focus:bg-white cursor-pointer outline-none">
                        <option value="1">Enabled (purchasable)</option>
                        <option value="0">Disabled</option>
                    </select>
                </div>
            </div>

            <div class="mt-5 flex justify-end gap-3 border-t border-gray-100 pt-3">
                <x-secondary-button x-on:click="$dispatch('close')" class="text-xs">Cancel</x-secondary-button>
                <x-primary-button class="bg-primary hover:opacity-90 shadow-lg shadow-primary/20 text-xs">Update Add-on</x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal name="confirm-delete-addon" maxWidth="lg" focusable>
        <form id="delete-addon-form" method="post" action="" class="p-5">
            @csrf
            @method('DELETE')
            <div class="flex items-start gap-3 mb-3">
                <div class="w-10 h-10 flex items-center justify-center bg-primary/10 rounded-full shrink-0">
                    <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-black text-gray-900 leading-tight uppercase">Delete Add-on?</h2>
                    <p class="text-[10px] font-bold text-primary uppercase tracking-widest mt-0.5">Irreversible Action</p>
                </div>
            </div>
            <p class="text-[13px] text-gray-500 mb-5 leading-relaxed">
                Are you sure you want to delete this add-on? Add-ons with existing purchases cannot be deleted — disable them instead.
            </p>
            <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-4 py-2 border border-gray-200 text-gray-500 rounded-xl font-bold text-[11px] uppercase tracking-widest hover:bg-gray-50 transition-all">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-primary text-white rounded-xl font-bold text-[11px] uppercase tracking-widest hover:opacity-90 shadow-lg shadow-primary/20 transition-all">
                    Yes, Delete
                </button>
            </div>
        </form>
    </x-modal>

    <script>
        function toggleQuotaFields(prefix) {
            const kindEl = document.getElementById(prefix + '_kind');
            const kind = kindEl ? kindEl.value : document.getElementById('edit_kind_display').dataset.kind;
            const fields = document.getElementById(prefix + '_quota_fields');
            fields.classList.toggle('hidden', kind !== 'quota');
        }

        function openEditAddonModal(addOn) {
            const form = document.getElementById('edit-addon-form');
            form.action = `/admin/addons/${addOn.id}`;
            document.getElementById('edit_name').value = addOn.name;
            document.getElementById('edit_description').value = addOn.description || '';
            document.getElementById('edit_price').value = addOn.price;
            document.getElementById('edit_billing_type').value = addOn.billing_type;
            document.getElementById('edit_quota_key').value = addOn.quota_key || '';
            document.getElementById('edit_quota_value').value = addOn.quota_value || '';
            document.getElementById('edit_enabled').value = addOn.enabled ? '1' : '0';

            const kindDisplay = document.getElementById('edit_kind_display');
            kindDisplay.textContent = addOn.kind;
            kindDisplay.dataset.kind = addOn.kind;
            toggleQuotaFields('edit');

            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-addon' }));
        }

        function confirmDeleteAddon(action) {
            const form = document.getElementById('delete-addon-form');
            form.action = action;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-delete-addon' }));
        }

        document.addEventListener('DOMContentLoaded', function () {
            toggleQuotaFields('create');
        });
    </script>
</x-admin-layout>
