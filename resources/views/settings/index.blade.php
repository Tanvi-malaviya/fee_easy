<x-admin-layout title="System Settings">

    <div class="">
        <div class="max-w-8xl mx-auto">
            
       
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <form action="{{ route('settings.update') }}" method="POST" class="p-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                        <!-- Branding Section -->
                        <div class="lg:col-span-1">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Platform Branding</h3>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed font-medium">Customize the identity and contact points of your SaaS application.</p>
                        </div>
                        <div class="lg:col-span-2 space-y-6">
                            <div>
                                <x-input-label for="site_name" value="Site Name" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                <x-text-input id="site_name" name="settings[site_name]" type="text" class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm" 
                                    value="{{ App\Models\SystemSetting::get('site_name') }}" placeholder="FeeEasy" />
                            </div>
                            <div>
                                <x-input-label for="support_email" value="Support Email Address" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                <x-text-input id="support_email" name="settings[support_email]" type="email" class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm" 
                                    value="{{ App\Models\SystemSetting::get('support_email') }}" placeholder="support@feeeasy.com" />
                            </div>
                        </div>

                        <div class="lg:col-span-3 border-t border-gray-50 my-2"></div>

                        <!-- Billing Section -->
                        <div class="lg:col-span-1">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Billing & Localization</h3>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed font-medium">Manage currency symbols and default trial periods for all new plans.</p>
                        </div>
                        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="currency_symbol" value="Primary Currency Symbol" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                <x-text-input id="currency_symbol" name="settings[currency_symbol]" type="text" class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm" 
                                    value="{{ App\Models\SystemSetting::get('currency_symbol', '₹') }}" placeholder="₹" />
                            </div>
                            <div>
                                <x-input-label for="default_trial_days" value="Global Default Trial (Days)" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                <x-text-input id="default_trial_days" name="settings[default_trial_days]" type="number" class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm" 
                                    value="{{ App\Models\SystemSetting::get('default_trial_days', 14) }}" placeholder="14" />
                            </div>
                        </div>

                        <div class="lg:col-span-3 border-t border-gray-50 my-2"></div>

                        <!-- Status Section -->
                        <!-- <div class="lg:col-span-1">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Advanced Status</h3>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed font-medium">System-level controls and maintenance management.</p>
                        </div>
                        <div class="lg:col-span-2">
                             <div class="p-6 bg-amber-50 rounded-2xl border border-amber-100/50 flex items-start gap-4">
                                <div class="p-3 bg-white rounded-xl shadow-sm text-xl">⚠️</div>
                                <div>
                                    <h4 class="text-sm font-bold text-amber-900 uppercase tracking-wider">Maintenance Mode</h4>
                                    <p class="text-xs text-amber-700 mt-1 font-medium">Changes made here affect the entire platform visibility for institutes. Use with caution.</p>
                                    <button type="button" class="mt-4 px-5 py-2 bg-amber-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-lg shadow-sm hover:bg-amber-700 transition">Enable Platform Lockdown</button>
                                </div>
                             </div>
                        </div> -->
                    </div>

                    <div class=" flex justify-end border-t border-gray-50">
                        <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl shadow-lg shadow-indigo-600/20 font-bold uppercase tracking-widest text-xs hover:bg-indigo-700 transition transform active:scale-95">
                            Save System Configuration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
