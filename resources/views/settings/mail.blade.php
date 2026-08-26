<x-admin-layout title="Mail & SMTP Settings">

    <div class="py-3">
        <div class="max-w-6xl mx-auto space-y-6">
            <!-- Navigation Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Mail & SMTP Configuration</h2>
                    <p class="text-xs text-gray-500 mt-1">Configure SMTP email credentials and senders dynamically from the Admin Panel.</p>
                </div>
            </div>

            <!-- Settings Form Card -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <form action="{{ route('settings.mail.update') }}" method="POST" class="p-6 space-y-6" x-data="{ mailer: '{{ $settings['mail_mailer'] ?? 'smtp' }}', showPassword: false }">
                    @csrf

                    <!-- SMTP Server Connection Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-6 border-b border-gray-100">
                        <div class="lg:col-span-1">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">SMTP Server Settings</h3>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed font-medium">
                                Configure your outbound email server parameters. For Gmail, use <span class="font-semibold text-gray-700">smtp.gmail.com</span> with port <span class="font-semibold text-gray-700">465 (SSL)</span> or <span class="font-semibold text-gray-700">587 (TLS)</span>.
                            </p>
                        </div>

                        <div class="lg:col-span-2 space-y-5">
                            <div>
                                <x-input-label for="mail_mailer" value="Mail Driver / Protocol" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                <select id="mail_mailer" name="settings[mail_mailer]" x-model="mailer"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-4 text-sm focus:ring-primary focus:border-primary">
                                    <option value="smtp">SMTP (Recommended for Gmail, Outlook, cPanel)</option>
                                    <option value="sendmail">Sendmail</option>
                                    <option value="log">Log (Testing only - emails written to storage/logs)</option>
                                </select>
                            </div>

                            <div x-show="mailer === 'smtp'" class="space-y-5">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="md:col-span-2">
                                        <x-input-label for="mail_host" value="SMTP Host" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                        <x-text-input id="mail_host" name="settings[mail_host]" type="text"
                                            class="w-full bg-gray-50 border-gray-200 rounded-xl py-2.5 px-4 text-sm"
                                            value="{{ $settings['mail_host'] ?? 'smtp.gmail.com' }}" placeholder="smtp.gmail.com" />
                                    </div>
                                    <div>
                                        <x-input-label for="mail_port" value="SMTP Port" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                        <x-text-input id="mail_port" name="settings[mail_port]" type="number"
                                            class="w-full bg-gray-50 border-gray-200 rounded-xl py-2.5 px-4 text-sm"
                                            value="{{ $settings['mail_port'] ?? '465' }}" placeholder="465" />
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="mail_encryption" value="Encryption Type" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                    <select id="mail_encryption" name="settings[mail_encryption]"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-4 text-sm focus:ring-primary focus:border-primary">
                                        <option value="ssl" {{ ($settings['mail_encryption'] ?? 'ssl') === 'ssl' ? 'selected' : '' }}>SSL (Port 465)</option>
                                        <option value="tls" {{ ($settings['mail_encryption'] ?? '') === 'tls' ? 'selected' : '' }}>TLS (Port 587)</option>
                                        <option value="null" {{ ($settings['mail_encryption'] ?? '') === 'null' ? 'selected' : '' }}>None (No Encryption)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Authentication Credentials Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-6 border-b border-gray-100">
                        <div class="lg:col-span-1">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Authentication</h3>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed font-medium">
                                Enter your SMTP account credentials.
                            </p>
                            <div class="mt-3 p-3 bg-amber-50 rounded-xl border border-amber-200/60 text-[11px] text-amber-800 leading-normal">
                                <span class="font-bold block mb-1">💡 For Gmail:</span>
                                Enable 2-Step Verification on your Google Account and generate a 16-character <strong>App Password</strong>. Use that password below.
                            </div>
                        </div>

                        <div class="lg:col-span-2 space-y-5">
                            <div>
                                <x-input-label for="mail_username" value="SMTP Username / Email" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                <x-text-input id="mail_username" name="settings[mail_username]" type="text"
                                    class="w-full bg-gray-50 border-gray-200 rounded-xl py-2.5 px-4 text-sm"
                                    value="{{ $settings['mail_username'] ?? '' }}" placeholder="your-email@gmail.com" />
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <x-input-label for="mail_password" value="SMTP Password / App Password" class="text-xs font-semibold uppercase tracking-wider text-gray-500" />
                                    <button type="button" @click="showPassword = !showPassword" class="text-xs text-primary font-medium hover:underline focus:outline-none">
                                        <span x-text="showPassword ? 'Hide Password' : 'Show Password'"></span>
                                    </button>
                                </div>
                                <div class="relative">
                                    <input id="mail_password" name="settings[mail_password]" :type="showPassword ? 'text' : 'password'"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-4 text-sm focus:ring-primary focus:border-primary"
                                        value="{{ $settings['mail_password'] ?? '' }}" placeholder="Enter SMTP password or App Password" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sender Information Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-1">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Sender Information</h3>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed font-medium">
                                The sender email and name that will appear in the "From" header of all emails sent by the system (such as OTP, password resets, receipts).
                            </p>
                        </div>

                        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="mail_from_address" value="From Email Address" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                <x-text-input id="mail_from_address" name="settings[mail_from_address]" type="email"
                                    class="w-full bg-gray-50 border-gray-200 rounded-xl py-2.5 px-4 text-sm"
                                    value="{{ $settings['mail_from_address'] ?? '' }}" placeholder="noreply@feeeasy.com" />
                            </div>

                            <div>
                                <x-input-label for="mail_from_name" value="From Sender Name" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                <x-text-input id="mail_from_name" name="settings[mail_from_name]" type="text"
                                    class="w-full bg-gray-50 border-gray-200 rounded-xl py-2.5 px-4 text-sm"
                                    value="{{ $settings['mail_from_name'] ?? config('app.name', 'FeeEasy') }}" placeholder="FeeEasy" />
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end pt-6 border-t border-gray-100">
                        <button type="submit" class="px-8 py-3 bg-primary text-white rounded-xl shadow-lg shadow-indigo-600/20 font-bold uppercase tracking-widest text-xs hover:bg-indigo-700 transition transform active:scale-95">
                            Save Mail Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- Send Test Email Card -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Test SMTP Email Delivery
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">Send a test email to verify that your configured SMTP credentials and network connection are working correctly.</p>
                        </div>
                    </div>

                    <form action="{{ route('settings.mail.test') }}" method="POST" class="mt-5 flex flex-col sm:flex-row gap-3">
                        @csrf
                        <div class="flex-1">
                            <x-text-input name="test_email" type="email" required
                                class="w-full bg-gray-50 border-gray-200 rounded-xl py-2.5 px-4 text-sm"
                                placeholder="Enter recipient email (e.g. your-email@gmail.com)"
                                value="{{ auth()->user()->email ?? '' }}" />
                        </div>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-900 text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-black transition transform active:scale-95 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Send Test Email
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
