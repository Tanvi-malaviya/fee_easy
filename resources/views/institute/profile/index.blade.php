@extends('layouts.institute')

@section('content')
<div class="max-w-7xl mx-auto pb-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-800">Institute Profile</h1>
            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1 border-l-2 border-blue-600 pl-3">
                Manage your administrative details
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3">

        <!-- LEFT SIDEBAR -->
        <div class="lg:col-span-1 space-y-3 sticky top-6 h-fit">

            <!-- Profile Card -->
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-2xl p-6 shadow-xl relative overflow-hidden">
                <div class="flex flex-col items-center text-center space-y-3 relative z-10">
                    <div class="relative group cursor-pointer" onclick="document.getElementById('logo-input').click()">
                        <img id="profile-logo-preview"
                            src="{{ auth()->guard('institute')->user()->logo ? asset('storage/' . auth()->guard('institute')->user()->logo) : 'https://ui-avatars.com/?name=' . urlencode(auth()->guard('institute')->user()->name) . '&background=fff&color=1e3a8a' }}"
                            class="h-20 w-20 rounded-xl object-cover border-4 border-white/20 transition-transform group-hover:scale-105">
                        <div class="absolute inset-0 bg-black/40 rounded-xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-black text-lg leading-tight">{{ auth()->guard('institute')->user()->name }}</h3>
                        <p class="text-xs opacity-70 font-medium">{{ auth()->guard('institute')->user()->email }}</p>
                        <p id="sidebar-sub-status" class="text-[9px] font-black uppercase tracking-widest mt-2 px-2 py-0.5 bg-white/20 rounded-full inline-block">Loading Plan...</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border">
                <h4 class="text-xs font-black text-slate-400 uppercase mb-3 px-1">Quick Actions</h4>
                <div class="space-y-1">
                    <button onclick="openPasswordModal()" class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition group text-left">
                        <span class="text-lg">🔒</span>
                        <span class="text-sm font-bold text-slate-700">Change Password</span>
                    </button>
                    <a href="{{ route('institute.plans.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition group">
                        <span class="text-lg">💳</span>
                        <span class="text-sm font-bold text-slate-700">Subscription Plan</span>
                    </a>
                    <a href="{{ route('institute.whatsapp.setup') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition group">
                        <span class="text-lg">📲</span>
                        <span class="text-sm font-bold text-slate-700">WhatsApp API</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- RIGHT CONTENT -->
        <div class="lg:col-span-3 ">
            <form id="profile-form" class="" enctype="multipart/form-data">
                @csrf

                <!-- GENERAL INFO -->
                <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                    <div class="p-5 border-b bg-slate-50/50 flex justify-between items-center">
                        <h4 class="text-sm font-black text-slate-600 uppercase tracking-wider">General Information</h4>
                        <span class="text-[10px] bg-blue-50 text-blue-600 px-3 py-1 rounded-full font-black uppercase tracking-widest border border-blue-100">
                            Since {{ auth()->guard('institute')->user()->created_at->format('M Y') }}
                        </span>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Institute Name</label>
                            <input type="text" name="institute_name" id="field-institute_name" placeholder="Enter Institute Name" class="input">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Owner Name</label>
                            <input type="text" name="name" id="field-name" placeholder="Enter Owner Name" class="input">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                            <input type="email" name="email" id="field-email" placeholder="email@example.com" class="input">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone Number</label>
                            <input type="text" name="phone" id="field-phone" placeholder="Phone Number" class="input">
                        </div>

                        <!-- Logo Upload Hidden -->
                        <input type="file" id="logo-input" name="logo" class="hidden" accept="image/*" onchange="previewLogo(this)">
                    </div>
                </div>

                <!-- ADDRESS -->
                <div class="bg-white rounded-2xl shadow-sm border overflow-hidden mt-2">
                    <div class="p-5 border-b bg-slate-50/50">
                        <h4 class="text-sm font-black text-slate-600 uppercase tracking-wider">Address Details</h4>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2 space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Address Line 1</label>
                            <input type="text" name="address" id="field-address" placeholder="Flat, House no., Building" class="input">
                        </div>
                        <div class="md:col-span-2 space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Address Line 2</label>
                            <input type="text" name="address_line_2" id="field-address_line_2" placeholder="Area, Street, Sector" class="input">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">City</label>
                            <input type="text" name="city" id="field-city" placeholder="City" class="input">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">State</label>
                            <input type="text" name="state" id="field-state" placeholder="State" class="input">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Country</label>
                            <input type="text" name="country" id="field-country" placeholder="Country" class="input">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pincode</label>
                            <input type="text" name="pincode" id="field-pincode" placeholder="Pincode" class="input">
                        </div>
                    </div>
                </div>

                <!-- SAVE BUTTON -->
                <div class="flex justify-end pt-2">
                    <button type="submit" id="save-profile-btn"
                        class="px-10 py-4 bg-blue-600 text-white rounded-xl font-black text-xs uppercase tracking-widest shadow-xl shadow-blue-900/10 hover:scale-[1.02] active:scale-95 transition-all flex items-center gap-3">
                        <span>Save All Changes</span>
                        <div id="save-loader" class="h-4 w-4 border-2 border-white/20 border-t-white rounded-full animate-spin hidden"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Password Modal -->
<div id="password-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="p-4 border-b flex items-center justify-between">
            <h3 class="text-lg font-black text-slate-800">Update Password</h3>
            <button onclick="closePasswordModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form id="password-form" class="p-4 space-y-2">
            @csrf
            <div class="space-y-1.5">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Current Password</label>
                <input type="password" name="current_password" required class="input">
            </div>
            <div class="space-y-1.5">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">New Password</label>
                <input type="password" name="password" required class="input">
            </div>
            <div class="space-y-1.5">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Confirm New Password</label>
                <input type="password" name="password_confirmation" required class="input">
            </div>
            <button type="submit" id="submit-btn" class="w-full h-12 bg-blue-600 text-white rounded-xl font-black text-xs uppercase tracking-widest shadow-lg shadow-blue-900/10 hover:scale-[1.02] transition-all flex items-center justify-center gap-3 mt-6">
                <span>Update Password</span>
            </button>
        </form>
    </div>
</div>

<style>
.input {
    width: 100%;
    height: 48px;
    padding: 0 16px;
    border-radius: 12px;
    background: #f8fafc;
    border: 2px solid transparent;
    font-weight: 700;
    font-size: 14px;
    color: #334155;
    transition: all 0.2s;
}
.input:focus {
    outline: none;
    background: #fff;
    border-color: #3b82f6;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.08);
}
.input::placeholder { color: #94a3b8; font-weight: 500; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', fetchProfile);

    async function fetchProfile() {
        try {
            const headers = { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' };
            const token = localStorage.getItem('token');
            if (token) headers['Authorization'] = `Bearer ${token}`;

            const response = await fetch('/api/v1/institute/profile', { headers });
            const result = await response.json();
            if (result.status === 'success') {
                const data = result.data;
                if (data.logo_url) document.getElementById('profile-logo-preview').src = data.logo_url;

                const sub = result.subscription;
                const sidebarSub = document.getElementById('sidebar-sub-status');
                if (sub) {
                    sidebarSub.innerText = `${sub.plan_name} - ${sub.status.toUpperCase()}`;
                } else {
                    sidebarSub.innerText = 'NO ACTIVE PLAN';
                }

                document.getElementById('field-institute_name').value = data.institute_name || '';
                document.getElementById('field-name').value = data.name || '';
                document.getElementById('field-email').value = data.email || '';
                document.getElementById('field-phone').value = data.phone || '';
                document.getElementById('field-address').value = data.address || '';
                document.getElementById('field-address_line_2').value = data.address_line_2 || '';
                document.getElementById('field-city').value = data.city || '';
                document.getElementById('field-state').value = data.state || '';
                document.getElementById('field-country').value = data.country || '';
                document.getElementById('field-pincode').value = data.pincode || '';
            }
        } catch (error) { console.error('Error fetching profile:', error); }
    }

    document.getElementById('profile-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('save-profile-btn');
        const loader = document.getElementById('save-loader');
        btn.disabled = true; loader.classList.remove('hidden');

        try {
            const formData = new FormData(e.target);
            const response = await fetch('/api/v1/institute/profile/update', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Authorization': `Bearer ${localStorage.getItem('token')}` }
            });
            if (response.ok) { showToast('Profile updated successfully!'); fetchProfile(); }
            else { showToast('Error updating profile', 'error'); }
        } catch (error) { showToast('Something went wrong.', 'error'); }
        finally { btn.disabled = false; loader.classList.add('hidden'); }
    });

    function previewLogo(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => document.getElementById('profile-logo-preview').src = e.target.result;
            reader.readAsDataURL(input.files[0]);
        }
    }

    function openPasswordModal() { document.getElementById('password-modal').classList.replace('hidden', 'flex'); document.body.style.overflow = 'hidden'; }
    function closePasswordModal() { document.getElementById('password-modal').classList.replace('flex', 'hidden'); document.body.style.overflow = 'auto'; }

    document.getElementById('password-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('submit-btn');
        btn.disabled = true;
        try {
            const response = await fetch('{{ route("institute.profile.password.update") }}', {
                method: 'POST',
                body: new FormData(e.target),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (response.ok) { showToast('Password updated!'); closePasswordModal(); }
            else { showToast('Error updating password', 'error'); }
        } catch (error) { showToast('Something went wrong.', 'error'); }
        finally { btn.disabled = false; }
    });
</script>
@endsection