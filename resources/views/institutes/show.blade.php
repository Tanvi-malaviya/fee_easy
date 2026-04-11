<x-admin-layout title="{{ $institute->institute_name }} - Details">
    <div class=" flex flex-col gap-8">
        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-4">

                <!-- Primary Information -->
                <div class="bg-white border border-gray-100 rounded-3xl shadow-sm overflow-hidden">
                    <div class="px-8 py-4 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('institutes.index') }}"
                                class="p-1.5 bg-white border border-gray-100 rounded-lg text-gray-400 hover:text-indigo-600 transition shadow-sm active:scale-90">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                            </a>
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Primary Information
                            </h3>
                            <span class="px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider
                                @if($institute->status == 'active') bg-emerald-100 text-emerald-700 border border-emerald-200 
                                @elseif($institute->status == 'suspended') bg-amber-100 text-amber-700 border border-amber-200
                                @else bg-red-100 text-red-700 border border-red-200 @endif">
                                ● {{ $institute->status }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('institutes.edit', $institute) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-[10px] font-bold text-gray-600 hover:bg-gray-50 transition shadow-sm active:scale-95">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                EDIT
                            </a>
                            <form action="{{ route('institutes.destroy', $institute) }}" method="POST"
                                onsubmit="return confirm('Archive this institute records?');" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 bg-red-50 border border-red-100 rounded-lg text-[10px] font-bold text-red-600 hover:bg-red-100 transition shadow-sm active:scale-95">
                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    DELETE
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="flex flex-col md:flex-row gap-8">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-32 h-32 rounded-3xl bg-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden">
                                    @if($institute->logo)
                                        <img src="{{ asset('storage/' . $institute->logo) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="text-4xl">🏢</div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                                <div>
                                    <label class="text-xs font-semibold uppercase tracking-wider text-gray-500">Owner /
                                        Representative</label>
                                    <p class="text-lg font-bold text-gray-900 mt-1">{{ $institute->name }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold uppercase tracking-wider text-gray-500">Email
                                        Address</label>
                                    <p class="text-base font-semibold text-gray-700 mt-1">{{ $institute->email }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold uppercase tracking-wider text-gray-500">Phone
                                        Number</label>
                                    <p class="text-base font-semibold text-gray-700 mt-1">{{ $institute->phone }}</p>
                                </div>
                                <div>
                                    <label
                                        class="text-xs font-semibold uppercase tracking-wider text-gray-500">Registration
                                        Date</label>
                                    <p class="text-base font-semibold text-gray-700 mt-1">
                                        {{ $institute->created_at->format('d M, Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Location Row (Starts below logo) -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-6">
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-gray-500">Full
                                    Address</label>
                                <p class="text-base font-semibold text-gray-700 mt-1 leading-relaxed">
                                    {{ $institute->address ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-gray-500">City</label>
                                <p class="text-base font-semibold text-gray-700 mt-1">{{ $institute->city ?? 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-gray-500">State &
                                    Pincode</label>
                                <p class="text-base font-semibold text-gray-700 mt-1">{{ $institute->state ?? 'N/A' }},
                                    {{ $institute->pincode ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Recent Activity Trace -->
                <div class="bg-white border border-gray-100 rounded-3xl shadow-sm overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Latest Subscriptions
                        </h3>
                        <!-- <a href="{{ route('subscriptions.index') }}" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:underline">View Ledger</a> -->
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50/50">
                                <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="px-8 py-4">Plan Name</th>
                                    <th class="px-8 py-4">Valid Until</th>
                                    <th class="px-8 py-4">Amount</th>
                                    <th class="px-8 py-4 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($institute->subscriptions as $sub)
                                    <tr class="hover:bg-gray-50/30 transition">
                                        <td class="px-8 py-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $sub->plan_name }}</div>
                                            <div class="text-xs text-gray-500">Started:
                                                {{ \Carbon\Carbon::parse($sub->start_date)->format('d M, Y') }}</div>
                                        </td>
                                        <td class="px-8 py-4 text-sm font-medium text-gray-700">
                                            {{ \Carbon\Carbon::parse($sub->end_date)->format('d M, Y') }}
                                        </td>
                                        <td class="px-8 py-4 text-sm font-bold text-indigo-600">
                                            {{ $currency }}{{ number_format($sub->amount, 2) }}
                                        </td>
                                        <td class="px-8 py-4 text-right">
                                            <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase
                                                    @if($sub->status == 'active') bg-green-50 text-green-600 border border-green-100
                                                    @elseif($sub->status == 'trial') bg-blue-50 text-blue-600 border border-blue-100
                                                    @else bg-gray-50 text-gray-400 border border-gray-100 @endif">
                                                {{ $sub->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-8 py-8 text-center text-xs text-gray-400 italic">No
                                            subscription history available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Sidebar Stats Area -->
            <div class="space-y-4">

                <!-- Metrics Card -->
                <!-- <div class="bg-indigo-600 rounded-3xl p-8 text-white shadow-xl shadow-indigo-100 relative overflow-hidden group">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full group-hover:scale-110 transition duration-500"></div>
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] text-indigo-100 opacity-80">Operational Pulse</h3>
                    
                    <div class="grid grid-cols-2 gap-4 mt-8">
                        <div>
                            <p class="text-4xl font-black">{{ $stats['students_count'] }}</p>
                            <p class="text-[10px] font-black uppercase tracking-widest text-indigo-100 mt-1">Total Students</p>
                        </div>
                        <div>
                            <p class="text-4xl font-black">{{ $stats['batches_count'] }}</p>
                            <p class="text-[10px] font-black uppercase tracking-widest text-indigo-100 mt-1">Active Batches</p>
                        </div>
                    </div>

                    <div class="mt-8 pt-8 border-t border-white/10">
                        <div class="flex justify-between items-center mb-1">
                             <span class="text-[10px] font-black uppercase tracking-widest text-indigo-100">Utility Capacity</span>
                             <span class="text-[10px] font-black uppercase text-white">Scale Mode</span>
                        </div>
                        <div class="w-full bg-white/20 h-1.5 rounded-full overflow-hidden">
                             <div class="bg-white h-full w-2/3"></div>
                        </div>
                    </div>
                </div> -->

                <!-- Current Plan Section -->
                @if($stats['active_subscription'])
                    <div class="p-6 bg-indigo-50 rounded-3xl border border-indigo-100 shadow-sm shadow-indigo-100/20">
                        <p class="text-xs font-semibold text-indigo-400 uppercase tracking-wider">Service Eligibility</p>
                        <h4 class="text-xl font-black text-indigo-700 mt-1">{{ $stats['active_subscription']->plan_name }}
                        </h4>
                        <p class="text-[10px] text-indigo-500 mt-2 font-semibold uppercase tracking-wider">Expires in
                            {{ \Carbon\Carbon::parse($stats['active_subscription']->end_date)->diffInDays(now()) }} Days</p>
                    </div>
                @else
                    <div class="p-6 bg-red-50 rounded-3xl border border-red-100 shadow-sm shadow-red-100/20">
                        <p class="text-[10px] font-black text-red-400 uppercase tracking-widest">Alert</p>
                        <h4 class="text-xl font-black text-red-700 mt-1">No Active Plan</h4>
                        <p class="text-xs text-red-500 mt-2 font-bold uppercase tracking-widest">Services Suspended</p>
                    </div>
                @endif

                <!-- WhatsApp Integration Card -->
                <div class="bg-white border border-gray-100 rounded-3xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">WhatsApp Integration
                        </h3>
                    </div>
                    <div class="p-6">
                        <div
                            class="p-4 {{ $institute->whatsappSettings && $institute->whatsappSettings->access_token ? 'bg-emerald-50 border-emerald-100' : 'bg-gray-50 border-gray-100' }} rounded-2xl border mb-4">
                            <p
                                class="text-[10px] font-bold {{ $institute->whatsappSettings && $institute->whatsappSettings->access_token ? 'text-emerald-400' : 'text-gray-400' }} uppercase tracking-wider mb-0.5">
                                Mobile No</p>
                            <h4
                                class="text-xl font-black {{ $institute->whatsappSettings && $institute->whatsappSettings->access_token ? 'text-emerald-700' : 'text-gray-700' }}">
                                {{ $institute->whatsappSettings && $institute->whatsappSettings->phone_number ? $institute->whatsappSettings->phone_number : 'Not Configured' }}
                            </h4>
                            <div class="mt-2 space-y-0.5">
                                @if($institute->whatsappSettings && $institute->whatsappSettings->business_account_id)
                                    <p
                                        class="text-[10px] {{ $institute->whatsappSettings && $institute->whatsappSettings->access_token ? 'text-emerald-600/70' : 'text-gray-400' }} font-bold uppercase tracking-wider">
                                        Bus ID: {{ $institute->whatsappSettings->business_account_id }}
                                    </p>
                                @endif
                                @if($institute->whatsappSettings && $institute->whatsappSettings->access_token)
                                    <p
                                        class="text-[10px] {{ $institute->whatsappSettings && $institute->whatsappSettings->access_token ? 'text-emerald-600/70' : 'text-gray-400' }} font-bold uppercase tracking-wider">
                                        Token: ••••••••••••
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="">
                            <button @click="$dispatch('open-modal', 'edit-whatsapp-{{ $institute->id }}')"
                                class="w-full px-4 py-3.5 rounded-2xl bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase tracking-widest hover:bg-indigo-100 transition transform active:scale-95 shadow-sm shadow-indigo-100/50">
                                Configure Integration
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <x-modal name="edit-whatsapp-{{ $institute->id }}" :show="false" focusable>
        <form method="post" action="{{ route('whatsapp.update', $institute) }}" class="p-8 text-left">
            @csrf @method('PATCH')
            <div class="border-b border-gray-100 pb-5 mb-8">
                <h2 class="text-lg font-bold text-gray-900">WhatsApp API Integration</h2>
                <p class="text-xs text-gray-500 mt-1">Configure Meta Cloud API credentials for <span
                        class="font-bold text-gray-700">{{ $institute->institute_name }}</span>.</p>
            </div>

            <div class="space-y-6">
                <div>
                    <x-input-label for="phone_number" value="WhatsApp Phone Number"
                        class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                    <x-text-input id="phone_number" name="phone_number" type="text"
                        class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm"
                        value="{{ $institute->whatsappSettings->phone_number ?? '' }}" placeholder="+91..." />
                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="access_token" value="Meta Access Token"
                        class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                    <x-text-input id="access_token" name="access_token" type="password"
                        class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm font-mono"
                        value="{{ $institute->whatsappSettings->access_token ?? '' }}" placeholder="EAAW..." />
                    <x-input-error :messages="$errors->get('access_token')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="business_account_id" value="Meta Business ID"
                        class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                    <x-text-input id="business_account_id" name="business_account_id" type="text"
                        class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm font-mono"
                        value="{{ $institute->whatsappSettings->business_account_id ?? '' }}" placeholder="153..." />
                    <x-input-error :messages="$errors->get('business_account_id')" class="mt-2" />
                </div>

            </div>

            <div class="flex justify-end pt-8 mt-8 border-t border-gray-100 gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-widest hover:text-gray-700 transition">Cancel</button>
                <button type="submit"
                    class="bg-indigo-600 text-white px-8 py-3 rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-indigo-600/20 hover:bg-indigo-700 transition transform active:scale-95">Save
                    Credentials</button>
            </div>
        </form>
    </x-modal>
</x-admin-layout>