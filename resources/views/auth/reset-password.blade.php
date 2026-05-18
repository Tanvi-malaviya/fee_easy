<x-guest-layout>
    <div class="mb-5 text-xs font-semibold text-slate-500 leading-relaxed bg-orange-50/50 p-4 rounded-xl border border-orange-100/50 text-center">
        {{ __('Choose a new, strong password for your account.') }}
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4.5">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="space-y-1.5">
            <label for="email" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">{{ __('Email Address') }}</label>
            <input id="email" class="block w-full text-sm font-bold bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#ff6c00] focus:ring-4 focus:ring-orange-500/10 rounded-xl px-4 py-3 outline-none transition-all" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-rose-500 font-semibold ml-1" />
        </div>

        <!-- Password -->
        <div class="space-y-1.5 pt-1">
            <label for="password" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">{{ __('New Password') }}</label>
            <input id="password" class="block w-full text-sm font-bold bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#ff6c00] focus:ring-4 focus:ring-orange-500/10 rounded-xl px-4 py-3 outline-none transition-all" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-rose-500 font-semibold ml-1" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1.5 pt-1">
            <label for="password_confirmation" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">{{ __('Confirm New Password') }}</label>
            <input id="password_confirmation" class="block w-full text-sm font-bold bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#ff6c00] focus:ring-4 focus:ring-orange-500/10 rounded-xl px-4 py-3 outline-none transition-all" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-xs text-rose-500 font-semibold ml-1" />
        </div>

        <div class="pt-3">
            <button type="submit" class="w-full py-3.5 bg-[#ff6c00] text-white rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-orange-500/25 hover:bg-[#e66100] active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                {{ __('Update Password') }}
            </button>
        </div>
    </form>
</x-guest-layout>
