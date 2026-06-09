<x-admin-layout title="System Settings">

    <div class="">
        <div class="max-w-8xl mx-auto">


            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                        <!-- Branding Section -->
                        <div class="lg:col-span-1">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Platform Branding</h3>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed font-medium">Customize the identity and
                                contact points of your SaaS application.</p>
                        </div>
                        <div class="lg:col-span-2 space-y-6">
                            <div>
                                <x-input-label for="site_name" value="Site Name"
                                    class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                <x-text-input id="site_name" name="settings[site_name]" type="text"
                                    class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm"
                                    value="{{ $settings['site_name'] ?? '' }}" placeholder="FeeEasy" />
                            </div>
                            <div>
                                <x-input-label for="support_email" value="Support Email Address"
                                    class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                <x-text-input id="support_email" name="settings[support_email]" type="email"
                                    class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm"
                                    value="{{ $settings['support_email'] ?? '' }}" placeholder="support@feeeasy.com" />
                            </div>
                        </div>

                        <div class="lg:col-span-3 border-t border-gray-50 my-2"></div>

                        <!-- Billing Section -->
                        <div class="lg:col-span-1">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Billing & Localization
                            </h3>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed font-medium">Manage the currency symbol
                                used across all new plans.</p>
                        </div>
                        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="currency_symbol" value="Primary Currency Symbol"
                                    class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                <x-text-input id="currency_symbol" name="settings[currency_symbol]" type="text"
                                    class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm"
                                    value="{{ $settings['currency_symbol'] ?? '₹' }}" placeholder="₹" />
                            </div>
                        </div>

                        <div class="lg:col-span-3 border-t border-gray-50 my-2"></div>

                        <!-- Version Control Section -->
                        <div class="lg:col-span-1">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Application Versions
                            </h3>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed font-medium">Manage the current release
                                versions for the admin app, web portal, and student app.</p>
                        </div>
                        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="app_version" value="App Version"
                                    class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                <x-text-input id="app_version" name="settings[app_version]" type="text"
                                    class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm"
                                    value="{{ $settings['app_version'] ?? '' }}" placeholder="1.0.0" />
                            </div>
                            <div>
                                <x-input-label for="web_version" value="Web Version"
                                    class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                <x-text-input id="web_version" name="settings[web_version]" type="text"
                                    class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm"
                                    value="{{ $settings['web_version'] ?? '' }}" placeholder="1.0.0" />
                            </div>
                            <div>
                                <x-input-label for="student_app_version" value="Student App Version"
                                    class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                <x-text-input id="student_app_version" name="settings[student_app_version]" type="text"
                                    class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm"
                                    value="{{ $settings['student_app_version'] ?? '' }}" placeholder="1.0.0" />
                            </div>
                        </div>

                        <div class="lg:col-span-3 border-t border-gray-50 my-2"></div>

                        <!-- Payment & Bank Details Section -->
                        <div class="lg:col-span-1">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Payment & Bank Details</h3>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed font-medium">Configure offline payment settings. These details are shown to institutes when they renew their subscription.</p>
                        </div>
                        <div class="lg:col-span-2 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="bank_holder_name" value="Bank Account Holder Name" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                    <x-text-input id="bank_holder_name" name="settings[bank_holder_name]" type="text"
                                        class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm"
                                        value="{{ $settings['bank_holder_name'] ?? '' }}" placeholder="Tuoora Education" />
                                </div>
                                <div>
                                    <x-input-label for="bank_name" value="Bank Name" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                    <x-text-input id="bank_name" name="settings[bank_name]" type="text"
                                        class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm"
                                        value="{{ $settings['bank_name'] ?? '' }}" placeholder="HDFC Bank" />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="bank_account_number" value="Account Number" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                    <x-text-input id="bank_account_number" name="settings[bank_account_number]" type="text"
                                        class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm"
                                        value="{{ $settings['bank_account_number'] ?? '' }}" placeholder="e.g., 501002xxxxxx" />
                                </div>
                                <div>
                                    <x-input-label for="bank_ifsc" value="IFSC Code" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                    <x-text-input id="bank_ifsc" name="settings[bank_ifsc]" type="text"
                                        class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm"
                                        value="{{ $settings['bank_ifsc'] ?? '' }}" placeholder="e.g., HDFC0001234" />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="payment_qr_image" value="QR Code Image" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                
                                <div class="flex items-center gap-4">
                                    @if(isset($settings['payment_qr_path']) && $settings['payment_qr_path'] !== '')
                                        <div class="shrink-0">
                                            <img src="/images/{{ $settings['payment_qr_path'] }}" alt="Current QR" class="w-16 h-16 object-cover rounded-lg border border-gray-200 shadow-sm">
                                        </div>
                                    @endif
                                    
                                    <div class="flex-1">
                                        <input id="payment_qr_image" name="payment_qr_image" type="file" accept="image/*"
                                            class="w-full bg-gray-50 border-gray-100 rounded-xl py-2 px-4 text-sm file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                        <p class="text-[10px] text-gray-400 mt-1.5">Upload a square image (JPG, PNG). Current file: {{ $settings['payment_qr_path'] ?? 'None' }}</p>
                                    </div>
                                </div>
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
                        <button type="submit"
                            class="px-8 py-3 bg-primary text-white rounded-xl shadow-lg shadow-indigo-600/20 font-bold uppercase tracking-widest text-xs hover:bg-indigo-700 transition transform active:scale-95">
                            Save System Configuration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>