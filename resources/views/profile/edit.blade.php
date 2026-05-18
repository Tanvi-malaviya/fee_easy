<x-admin-layout title="My Profile">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="mb-4 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight mb-1">Account Settings</h1>
                <p class="text-[11px] font-medium text-slate-500">Manage your profile details and security preferences.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-orange-50 flex items-center justify-center text-[#ff6c00] border border-orange-100/50 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            
            <!-- Left Column: Profile Info -->
            <div class="lg:col-span-6">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden relative group h-full">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#ff6c00] to-orange-300"></div>
                    <div class="p-4">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <!-- Right Column: Security -->
            <div class="lg:col-span-6">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden relative group h-full">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#ff6c00] to-orange-300"></div>
                    <div class="p-4">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
