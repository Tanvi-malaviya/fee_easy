<section>
    <header class="mb-3">
        <div class="flex items-center gap-3 mb-2">
            <div class="h-8 w-8 rounded-lg bg-orange-100/50 flex items-center justify-center text-[#ff6c00]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
            </div>
            <h2 class="text-xl font-bold text-slate-800">
                {{ __('Profile Information') }}
            </h2>
        </div>
        <p class="text-[11px] font-medium text-slate-500 ml-11">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-3 ml-11">
        @csrf
        @method('patch')

        <div class="space-y-1.5">
            <label for="name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">{{ __('Full Name') }}</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400 group-focus-within:text-[#ff6c00] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <input id="name" name="name" type="text" class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#ff6c00] focus:ring-4 focus:ring-orange-500/5 transition-all outline-none" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            </div>
            <x-input-error class="mt-1.5 text-[11px] font-bold text-rose-500 ml-1" :messages="$errors->get('name')" />
        </div>

        <div class="space-y-1.5">
            <label for="email" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">{{ __('Email Address') }}</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400 group-focus-within:text-[#ff6c00] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <input id="email" name="email" type="email" class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#ff6c00] focus:ring-4 focus:ring-orange-500/5 transition-all outline-none" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            </div>
            <x-input-error class="mt-1.5 text-[11px] font-bold text-rose-500 ml-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-[11px] font-bold mt-2 text-amber-600 bg-amber-50 px-3 py-2 rounded-lg border border-amber-100">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-amber-700 hover:text-amber-900 ml-1">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-bold text-[11px] text-emerald-600 bg-emerald-50 px-3 py-2 rounded-lg border border-emerald-100">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-6 py-2.5 bg-[#ff6c00] text-white rounded-xl text-xs font-bold shadow-sm hover:bg-[#e66100] active:scale-95 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ __('Save Changes') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-[11px] font-bold text-emerald-500 bg-emerald-50 px-3 py-1.5 rounded-lg flex items-center gap-1.5 border border-emerald-100">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    {{ __('Saved successfully.') }}
                </p>
            @endif
        </div>
    </form>
</section>
