<section>
    <header class="mb-3">
        <div class="flex items-center gap-3 mb-2">
            <div class="h-8 w-8 rounded-lg bg-slate-200/50 flex items-center justify-center text-slate-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <h2 class="text-xl font-bold text-slate-800">
                {{ __('Update Password') }}
            </h2>
        </div>
        <p class="text-[11px] font-medium text-slate-500 ml-11">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-3 ml-11">
        @csrf
        @method('put')

        <div class="space-y-1.5">
            <label for="update_password_current_password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">{{ __('Current Password') }}</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400 group-focus-within:text-slate-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
                <input id="update_password_current_password" name="current_password" type="password" class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all outline-none" autocomplete="current-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5 text-[11px] font-bold text-rose-500 ml-1" />
        </div>

        <div class="space-y-1.5">
            <label for="update_password_password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">{{ __('New Password') }}</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400 group-focus-within:text-[#ff6c00] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <input id="update_password_password" name="password" type="password" class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#ff6c00] focus:ring-4 focus:ring-orange-500/5 transition-all outline-none" autocomplete="new-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5 text-[11px] font-bold text-rose-500 ml-1" />
        </div>

        <div class="space-y-1.5">
            <label for="update_password_password_confirmation" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">{{ __('Confirm Password') }}</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400 group-focus-within:text-[#ff6c00] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#ff6c00] focus:ring-4 focus:ring-orange-500/5 transition-all outline-none" autocomplete="new-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5 text-[11px] font-bold text-rose-500 ml-1" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-6 py-2.5 bg-[#ff6c00] text-white rounded-xl text-xs font-bold shadow-sm hover:bg-[#e66100] active:scale-95 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                {{ __('Update Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-[11px] font-bold text-emerald-500 bg-emerald-50 px-3 py-1.5 rounded-lg flex items-center gap-1.5 border border-emerald-100">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    {{ __('Password updated.') }}
                </p>
            @endif
        </div>
    </form>
</section>
