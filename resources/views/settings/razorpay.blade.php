<x-admin-layout title="Razorpay Settings">

    <div class="py-3">
        <div class="max-w-6xl mx-auto">
            <!-- Navigation Header -->
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Razorpay API Credentials</h2>
                    <p class="text-xs text-gray-500 mt-1">Manage dynamic API configurations for Razorpay payment gateway integration.</p>
                </div>
                <!-- <a href="{{ route('settings.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold text-xs uppercase tracking-wider transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Settings
                </a> -->
            </div>

            <!-- Settings Form Card -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <form action="{{ route('settings.razorpay.update') }}" method="POST" class="p-4 space-y-3">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
                        <!-- Left Info Section -->
                        <div class="lg:col-span-1">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Gateway Keys</h3>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed font-medium">Configure active API credentials. If left empty, the application will default to the keys defined in your system environment (.env) configuration.</p>
                        </div>

                        <!-- Fields Section -->
                        <div class="lg:col-span-2 space-y-6">
                            <div>
                                <x-input-label for="razorpay_key_id" value="Razorpay Key ID" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                <x-text-input id="razorpay_key_id" name="settings[razorpay_key_id]" type="text"
                                    class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm"
                                    value="{{ $settings['razorpay_key_id'] ?? '' }}" placeholder="rzp_live_xxxxxxxxxxxx" />
                            </div>

                            <div>
                                <x-input-label for="razorpay_key_secret" value="Razorpay Key Secret" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                <x-text-input id="razorpay_key_secret" name="settings[razorpay_key_secret]" type="password"
                                    class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm"
                                    value="{{ $settings['razorpay_key_secret'] ?? '' }}" placeholder="••••••••••••••••" />
                            </div>

                            <div>
                                <x-input-label for="razorpay_webhook_secret" value="Razorpay Webhook Secret" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                <x-text-input id="razorpay_webhook_secret" name="settings[razorpay_webhook_secret]" type="password"
                                    class="w-full bg-gray-50 border-gray-100 rounded-xl py-2.5 px-4 text-sm"
                                    value="{{ $settings['razorpay_webhook_secret'] ?? '' }}" placeholder="••••••••••••••••" />
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end pt-6 border-t border-gray-50">
                        <button type="submit" class="px-8 py-3 bg-primary text-white rounded-xl shadow-lg shadow-indigo-600/20 font-bold uppercase tracking-widest text-xs hover:bg-indigo-700 transition transform active:scale-95">
                            Save API Credentials
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
