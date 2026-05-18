<x-guest-layout>
    <div class="mb-5 text-xs font-semibold text-slate-500 leading-relaxed bg-orange-50/50 p-4 rounded-xl border border-orange-100/50">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4.5">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1.5">
            <label for="email" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">{{ __('Email Address') }}</label>
            <input id="email" class="block w-full text-sm font-bold bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#ff6c00] focus:ring-4 focus:ring-orange-500/10 rounded-xl px-4 py-3 outline-none transition-all" type="email" name="email" :value="old('email')" required autofocus placeholder="admin@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-rose-500 font-semibold ml-1" />
        </div>

        <div class="pt-2 flex items-center gap-3">
            <a href="{{ route('login') }}" class="w-1/3 py-3.5 bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-800 rounded-xl text-xs font-bold uppercase tracking-widest text-center transition-all">
                {{ __('Back') }}
            </a>
            <button type="submit" class="flex-1 py-3.5 bg-[#ff6c00] text-white rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-orange-500/25 hover:bg-[#e66100] active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                {{ __('Send Reset Link') }}
            </button>
        </div>
    </form>
</x-guest-layout>
