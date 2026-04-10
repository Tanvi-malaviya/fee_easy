<x-admin-layout title="WhatsApp Hub">

    <div class="">
        <div class="max-w-7xl mx-auto ">
        

        

            <!-- Management Table -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50 flex justify-between items-center bg-gray-50/75">
                    <h2 class="text-lg font-medium text-gray-800">Institute Integration Status</h2>
                    <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Credential Control</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-2 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Institute</th>
                                <th class="px-2 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">API Status</th>
                                <th class="px-2 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Last Verified</th>
                                <th class="px-2 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Integration</th>
                                <th class="px-2 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($institutes as $institute)
                                <tr class="hover:bg-gray-50/50 transition duration-150">
                                    <td class="px-2 py-2 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $institute->institute_name }}</div>
                                        <div class="text-xs text-gray-500 font-medium">{{ $institute->name }}</div>
                                    </td>
                                    <td class="px-2 py-2 whitespace-nowrap">
                                        @if($institute->whatsappSettings && $institute->whatsappSettings->access_token)
                                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase bg-green-50 text-green-700 border border-green-100">Credentials Set</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase bg-gray-50 text-gray-400 border border-gray-100">Not Configured</span>
                                        @endif
                                    </td>
                                    <td class="px-2 py-2 whitespace-nowrap">
                                        <div class="text-xs font-semibold text-gray-600">
                                            {{ $institute->whatsappSettings && $institute->whatsappSettings->last_verified_at ? $institute->whatsappSettings->last_verified_at->diffForHumans() : 'Never' }}
                                        </div>
                                    </td>
                                    <td class="px-2 py-2 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-9 h-5 flex items-center {{ $institute->whatsappSettings && $institute->whatsappSettings->is_active ? 'bg-emerald-500' : 'bg-gray-300' }} rounded-full p-1 transition-colors duration-200">
                                                <div class="bg-white w-3 h-3 rounded-full shadow-sm transform {{ $institute->whatsappSettings && $institute->whatsappSettings->is_active ? 'translate-x-4' : '' }} transition-transform duration-200"></div>
                                            </div>
                                            <span class="ml-3 text-[10px] font-bold uppercase tracking-wider {{ $institute->whatsappSettings && $institute->whatsappSettings->is_active ? 'text-emerald-600' : 'text-gray-400' }}">
                                                {{ $institute->whatsappSettings && $institute->whatsappSettings->is_active ? 'Active' : 'Disabled' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-2 py-2 whitespace-nowrap text-right">
                                        <div class="flex justify-end gap-2">
                                            <button @click="$dispatch('open-modal', 'edit-whatsapp-{{ $institute->id }}')"
                                                class="px-5 py-2 rounded-xl bg-indigo-50 text-indigo-700 text-[10px] font-bold uppercase tracking-widest hover:bg-indigo-100 transition transform active:scale-95 shadow-sm shadow-indigo-100/50">
                                                Configure
                                            </button>
                                            <form action="{{ route('whatsapp.verify', $institute) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-widest hover:bg-emerald-100 transition transform active:scale-95 shadow-sm shadow-emerald-100/50">
                                                    Verify
                                                </button>
                                            </form>
                                        </div>

                                        <x-modal name="edit-whatsapp-{{ $institute->id }}" :show="false" focusable>
                                            <form method="post" action="{{ route('whatsapp.update', $institute) }}" class="p-8 text-left">
                                                @csrf @method('PATCH')
                                                <div class="border-b border-gray-100 pb-5 mb-8">
                                                    <h2 class="text-lg font-bold text-gray-900">WhatsApp API Integration</h2>
                                                    <p class="text-xs text-gray-500 mt-1">Configure Meta Cloud API credentials for <span class="font-bold text-gray-700">{{ $institute->institute_name }}</span>.</p>
                                                </div>

                                                <div class="space-y-6">
                                                    <div>
                                                        <x-input-label for="access_token" value="Meta Access Token" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                                        <x-text-input id="access_token" name="access_token" type="password" class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm font-mono" value="{{ $institute->whatsappSettings->access_token ?? '' }}" placeholder="EAAW..." />
                                                    </div>

                                                    <div class="grid grid-cols-2 gap-6">
                                                        <div>
                                                            <x-input-label for="phone_number_id" value="Phone Number ID" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                                            <x-text-input id="phone_number_id" name="phone_number_id" type="text" class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm font-mono" value="{{ $institute->whatsappSettings->phone_number_id ?? '' }}" placeholder="1098..." />
                                                        </div>
                                                        <div>
                                                            <x-input-label for="business_account_id" value="Business ID" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                                            <x-text-input id="business_account_id" name="business_account_id" type="text" class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm font-mono" value="{{ $institute->whatsappSettings->business_account_id ?? '' }}" placeholder="153..." />
                                                        </div>
                                                    </div>

                                                    <div class="flex items-center bg-gray-50 p-4 rounded-xl border border-gray-100 mt-4">
                                                        <input type="checkbox" name="is_active" id="is_active_{{ $institute->id }}" value="1" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 transition cursor-pointer" {{ $institute->whatsappSettings && $institute->whatsappSettings->is_active ? 'checked' : '' }}>
                                                        <label for="is_active_{{ $institute->id }}" class="ml-3 text-xs font-semibold text-gray-700 uppercase tracking-wide cursor-pointer select-none">Enable Messaging Integration</label>
                                                    </div>
                                                </div>

                                                <div class="flex justify-end pt-8 mt-8 border-t border-gray-100 gap-3">
                                                    <button type="button" x-on:click="$dispatch('close')" class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-widest hover:text-gray-700 transition">Cancel</button>
                                                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-indigo-600/20 hover:bg-indigo-700 transition transform active:scale-95">Save Credentials</button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">No institutes found to manage.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($institutes->hasPages())
                    <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
                        {{ $institutes->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>