<x-admin-layout title="WhatsApp Hub">
    <x-slot name="header">

    </x-slot>

    <div class="" x-data="{}">
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
                            {{ App\Models\InstituteWhatsappSetting::where('is_active', true)->count() }}
                        </h3>
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
                                        <x-modal name="edit-whatsapp-{{ $institute->id }}" :show="false" focusable>
                                            <form method="post" action="{{ route('whatsapp.update', $institute) }}"
                                                class="p-8 text-left">
                                                @csrf @method('PATCH')
                                                <div class="border-b border-gray-100 pb-5 mb-8">
                                                    <h2 class="text-xl font-black text-gray-900">WhatsApp API Integration</h2>
                                                    <p class="text-sm text-gray-500 mt-1">Configure Meta Cloud API credentials for {{ $institute->institute_name }}.</p>
                                                </div>

                                                <div class="space-y-6">
                                                    <div>
                                                        <x-input-label for="access_token" value="Meta Access Token (Permanent)"
                                                            class="text-xs font-black uppercase tracking-widest text-gray-500 mb-2" />
                                                        <x-text-input id="access_token" name="access_token" type="password"
                                                            class="mt-1 block w-full py-3 px-4 text-base bg-gray-50/50 border-gray-100 rounded-2xl font-mono transition"
                                                            value="{{ $institute->whatsappSettings->access_token ?? '' }}"
                                                            placeholder="EAAW..." />
                                                    </div>

                                                    <div class="grid grid-cols-2 gap-6">
                                                        <div>
                                                            <x-input-label for="phone_number_id" value="Phone Number ID"
                                                                class="text-xs font-black uppercase tracking-widest text-gray-500 mb-2" />
                                                            <x-text-input id="phone_number_id" name="phone_number_id"
                                                                type="text"
                                                                class="mt-1 block w-full py-3 px-4 text-base bg-gray-50/50 border-gray-100 rounded-2xl font-mono transition"
                                                                value="{{ $institute->whatsappSettings->phone_number_id ?? '' }}"
                                                                placeholder="1098..." />
                                                        </div>
                                                        <div>
                                                            <x-input-label for="business_account_id" value="Business ID"
                                                                class="text-xs font-black uppercase tracking-widest text-gray-500 mb-2" />
                                                            <x-text-input id="business_account_id" name="business_account_id"
                                                                type="text"
                                                                class="mt-1 block w-full py-3 px-4 text-base bg-gray-50/50 border-gray-100 rounded-2xl font-mono transition"
                                                                value="{{ $institute->whatsappSettings->business_account_id ?? '' }}"
                                                                placeholder="153..." />
                                                        </div>
                                                    </div>

                                                    <div class="flex items-center bg-emerald-50/50 p-5 rounded-[2rem] border border-emerald-100/50">
                                                        <input type="checkbox" name="is_active"
                                                            id="is_active_{{ $institute->id }}" value="1"
                                                            class="w-5 h-5 rounded-lg border-emerald-200 text-emerald-600 shadow-sm focus:ring-emerald-500 transition cursor-pointer"
                                                            {{ $institute->whatsappSettings && $institute->whatsappSettings->is_active ? 'checked' : '' }}>
                                                        <label for="is_active_{{ $institute->id }}"
                                                            class="ml-4 text-[11px] font-black text-emerald-800 uppercase tracking-widest cursor-pointer select-none">Enable Messaging Integration</label>
                                                    </div>
                                                </div>

                                                <div class="flex justify-end pt-8 mt-8 border-t border-gray-100 gap-4">
                                                    <x-secondary-button x-on:click="$dispatch('close')"
                                                        class="rounded-xl font-black uppercase text-[10px] py-2.5">Cancel</x-secondary-button>
                                                    <x-primary-button
                                                        class="rounded-xl font-black uppercase text-[10px] py-2.5 bg-indigo-600 hover:bg-indigo-700 shadow-xl shadow-indigo-600/20">
                                                        Save Credentials
                                                    </x-primary-button>
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