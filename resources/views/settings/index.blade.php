<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">
                    {{ __('System Settings') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Configure global site defaults, branding, and billing rules.</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <span class="inline-flex items-center px-4 py-2 border border-indigo-200 rounded-lg shadow-sm text-sm font-bold text-indigo-600 bg-indigo-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Core Configuration
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-100 rounded-3xl shadow-sm overflow-hidden">
                <form action="{{ route('settings.update') }}" method="POST" class="p-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                        <!-- Branding Section -->
                        <div class="lg:col-span-1">
                            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest italic">Platform Branding</h3>
                            <p class="text-xs text-gray-400 mt-2 leading-relaxed">Customize the identity and contact points of your SaaS application.</p>
                        </div>
                        <div class="lg:col-span-2 space-y-6">
                            <div>
                                <x-input-label for="site_name" value="Site Name" class="text-[10px] font-black uppercase tracking-widest text-gray-400" />
                                <x-text-input id="site_name" name="settings[site_name]" type="text" class="mt-1 block w-full" 
                                    value="{{ App\Models\SystemSetting::get('site_name') }}" placeholder="FeeEasy" />
                            </div>
                            <div>
                                <x-input-label for="support_email" value="Support Email Address" class="text-[10px] font-black uppercase tracking-widest text-gray-400" />
                                <x-text-input id="support_email" name="settings[support_email]" type="email" class="mt-1 block w-full" 
                                    value="{{ App\Models\SystemSetting::get('support_email') }}" placeholder="support@feeeasy.com" />
                            </div>
                        </div>

                        <div class="lg:col-span-3 border-t border-gray-50 my-2"></div>

                        <!-- Billing Section -->
                        <div class="lg:col-span-1">
                            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest italic">Billing & Localization</h3>
                            <p class="text-xs text-gray-400 mt-2 leading-relaxed">Manage currency symbols and default trial periods for all new plans.</p>
                        </div>
                        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="currency_symbol" value="Primary Currency Symbol" class="text-[10px] font-black uppercase tracking-widest text-gray-400" />
                                <x-text-input id="currency_symbol" name="settings[currency_symbol]" type="text" class="mt-1 block w-full" 
                                    value="{{ App\Models\SystemSetting::get('currency_symbol', '₹') }}" placeholder="₹" />
                            </div>
                            <div>
                                <x-input-label for="default_trial_days" value="Global Default Trial (Days)" class="text-[10px] font-black uppercase tracking-widest text-gray-400" />
                                <x-text-input id="default_trial_days" name="settings[default_trial_days]" type="number" class="mt-1 block w-full" 
                                    value="{{ App\Models\SystemSetting::get('default_trial_days', 14) }}" placeholder="14" />
                            </div>
                        </div>

                        <div class="lg:col-span-3 border-t border-gray-50 my-2"></div>

                        <!-- Status Section -->
                        <div class="lg:col-span-1">
                            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest italic">Advanced Status</h3>
                        </div>
                        <div class="lg:col-span-2">
                             <div class="p-6 bg-amber-50 rounded-3xl border border-amber-100/50 flex items-start gap-4">
                                <div class="text-2xl">⚠️</div>
                                <div>
                                    <h4 class="text-sm font-black text-amber-800 uppercase tracking-wider">Maintenance Mode</h4>
                                    <p class="text-xs text-amber-600 mt-1">Changes made here affect the entire platform visibility for institutes. Use with caution.</p>
                                    <button type="button" class="mt-4 px-4 py-2 bg-amber-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl">Enable Platform Lockdown</button>
                                </div>
                             </div>
                        </div>
                    </div>

                    <div class="mt-12 flex justify-end">
                        <x-primary-button class="px-10 py-4 bg-indigo-600 rounded-2xl shadow-xl shadow-indigo-100 font-black uppercase tracking-widest text-sm">
                            Save System Configuration
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
