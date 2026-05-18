<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4.5">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1.5">
            <label for="email" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">{{ __('Email Address') }}</label>
            <input id="email" class="block w-full text-sm font-bold bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#ff6c00] focus:ring-4 focus:ring-orange-500/10 rounded-xl px-4 py-3 outline-none transition-all" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="admin@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-rose-500 font-semibold ml-1" />
        </div>

        <!-- Password -->
        <div class="space-y-1.5 pt-1">
            <div class="flex items-center justify-between ml-1">
                <label for="password" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Password') }}</label>
                @if (Route::has('password.request'))
                    <a class="text-[11px] font-bold text-[#ff6c00] hover:text-[#e66100] transition-colors" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
            <input id="password" class="block w-full text-sm font-bold bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#ff6c00] focus:ring-4 focus:ring-orange-500/10 rounded-xl px-4 py-3 outline-none transition-all" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-rose-500 font-semibold ml-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1.5 pb-2">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-[#ff6c00] focus:ring-[#ff6c00] shadow-sm transition-all" name="remember">
                <span class="ms-2.5 text-xs font-bold text-slate-600 group-hover:text-slate-900 transition-colors">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3.5 bg-[#ff6c00] text-white rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-orange-500/25 hover:bg-[#e66100] active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                {{ __('Secure Login') }}
            </button>
        </div>
    </form>
</x-guest-layout>