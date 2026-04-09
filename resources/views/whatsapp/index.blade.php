<x-admin-layout title="WhatsApp Hub">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">
                    {{ __('WhatsApp Hub') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Manage API credentials and notification connectivity for all
                    institutes.</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <span
                    class="inline-flex items-center px-4 py-2 border border-emerald-200 rounded-lg shadow-sm text-sm font-bold text-emerald-600 bg-emerald-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z">
                        </path>
                    </svg>
                    Cloud API Ready
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6" x-data="{}">
        <div class="max-w-7xl mx-auto">
            <!-- Information Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Platform Info -->
                <div
                    class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center col-span-1 md:col-span-2 transition hover:shadow-md">
                    <div class="p-4 rounded-2xl bg-emerald-50 text-emerald-600 text-3xl">
                        📱
                    </div>
                    <div class="ml-5">
                        <h3 class="text-lg font-black text-gray-900 leading-tight">Meta Official API</h3>
                        <p class="text-xs text-gray-500 mt-1 uppercase font-bold tracking-widest">Enterprise Messaging
                            Engine</p>
                    </div>
                </div>

                <!-- Active Stat -->
                <div
                    class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center col-span-1 md:col-span-2 transition hover:shadow-md">
                    <div class="p-4 rounded-2xl bg-indigo-50 text-indigo-600 text-2xl">
                        🔗
                    </div>
                    <div class="ml-5">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Active Integrations</p>
                        <h3 class="text-3xl font-black text-gray-900 mt-1">
                            {{ App\Models\InstituteWhatsappSetting::where('is_active', true)->count() }}</h3>
                    </div>
                </div>
            </div>

            <!-- Management Table -->
            <div class="bg-white border border-gray-100 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <h2 class="text-lg font-bold text-gray-900">Institute Integration Status</h2>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Credential
                        Control</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                <th class="px-6 py-4">Institute</th>
                                <th class="px-6 py-4">API Status</th>
                                <th class="px-6 py-4">Last Verified</th>
                                <th class="px-6 py-4">Integration</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($institutes as $institute)
                                <tr class="hover:bg-gray-50/50 transition duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $institute->institute_name }}</div>
                                        <div class="text-xs text-gray-500 font-medium">{{ $institute->name }}
                                            ({{ $institute->city }})</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($institute->whatsappSettings && $institute->whatsappSettings->access_token)
                                            <span
                                                class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-green-50 text-green-700">Credentials
                                                Set</span>
                                        @else
                                            <span
                                                class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-gray-100 text-gray-400">Not
                                                Configured</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-xs font-bold text-gray-600">
                                            {{ $institute->whatsappSettings && $institute->whatsappSettings->last_verified_at ? $institute->whatsappSettings->last_verified_at->diffForHumans() : 'Never' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-5 flex items-center {{ $institute->whatsappSettings && $institute->whatsappSettings->is_active ? 'bg-emerald-500' : 'bg-gray-300' }} rounded-full p-1 cursor-pointer">
                                                <div
                                                    class="bg-white w-3 h-3 rounded-full shadow-sm transform {{ $institute->whatsappSettings && $institute->whatsappSettings->is_active ? 'translate-x-5' : '' }} transition duration-300">
                                                </div>
                                            </div>
                                            <span
                                                class="ml-3 text-[10px] font-black uppercase tracking-widest {{ $institute->whatsappSettings && $institute->whatsappSettings->is_active ? 'text-emerald-600' : 'text-gray-400' }}">
                                                {{ $institute->whatsappSettings && $institute->whatsappSettings->is_active ? 'Active' : 'Disabled' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex justify-end gap-2">
                                            <button @click="$dispatch('open-modal', 'edit-whatsapp-{{ $institute->id }}')"
                                                class="px-4 py-1.5 rounded-xl bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase tracking-widest hover:bg-indigo-100 transition shadow-sm shadow-indigo-100">
                                                Configure
                                            </button>

                                            <form action="{{ route('whatsapp.verify', $institute) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="px-4 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase tracking-widest hover:bg-emerald-100 transition shadow-sm shadow-emerald-100">
                                                    Verify
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Configuration Modal -->
                                        <x-modal name="edit-whatsapp-{{ $institute->id }}" :show="false" focusable>
                                            <form method="post" action="{{ route('whatsapp.update', $institute) }}"
                                                class="p-6 text-left">
                                                @csrf @method('PATCH')
                                                <h2 class="text-lg font-extrabold text-gray-900 border-b pb-4 mb-6">WhatsApp
                                                    API Settings</h2>

                                                <div class="mb-5">
                                                    <x-input-label for="access_token" value="Meta Access Token (Permanent)"
                                                        class="text-[10px] font-black uppercase" />
                                                    <x-text-input id="access_token" name="access_token" type="password"
                                                        class="mt-1 block w-full text-sm font-mono"
                                                        value="{{ $institute->whatsappSettings->access_token ?? '' }}"
                                                        placeholder="EAAW..." />
                                                </div>

                                                <div class="grid grid-cols-2 gap-4 mb-5">
                                                    <div>
                                                        <x-input-label for="phone_number_id" value="Phone Number ID"
                                                            class="text-[10px] font-black uppercase" />
                                                        <x-text-input id="phone_number_id" name="phone_number_id"
                                                            type="text"
                                                            class="mt-1 block w-full text-sm font-mono bg-gray-50 border-gray-200"
                                                            value="{{ $institute->whatsappSettings->phone_number_id ?? '' }}"
                                                            placeholder="1098..." />
                                                    </div>
                                                    <div>
                                                        <x-input-label for="business_account_id" value="Business ID"
                                                            class="text-[10px] font-black uppercase" />
                                                        <x-text-input id="business_account_id" name="business_account_id"
                                                            type="text"
                                                            class="mt-1 block w-full text-sm font-mono bg-gray-50 border-gray-200"
                                                            value="{{ $institute->whatsappSettings->business_account_id ?? '' }}"
                                                            placeholder="153..." />
                                                    </div>
                                                </div>

                                                <div class="flex items-center mb-6 bg-gray-50 p-4 rounded-2xl">
                                                    <input type="checkbox" name="is_active"
                                                        id="is_active_{{ $institute->id }}" value="1"
                                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                        {{ $institute->whatsappSettings && $institute->whatsappSettings->is_active ? 'checked' : '' }}>
                                                    <label for="is_active_{{ $institute->id }}"
                                                        class="ml-3 text-[10px] font-black text-gray-700 uppercase tracking-widest">Enable
                                                        Messaging for this Institute</label>
                                                </div>

                                                <div class="flex justify-end pt-5 border-t gap-3">
                                                    <x-secondary-button x-on:click="$dispatch('close')"
                                                        class="rounded-xl font-bold uppercase text-[10px]">Cancel</x-secondary-button>
                                                    <x-primary-button
                                                        class="rounded-xl font-bold uppercase text-[10px]">Save & Secure
                                                        Credentials</x-primary-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">No institutes found to
                                        manage.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-gray-50/50 text-xs text-gray-400">
                    {{ $institutes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>