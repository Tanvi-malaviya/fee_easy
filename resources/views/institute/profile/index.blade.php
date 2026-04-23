@extends('layouts.institute')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-10">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Institute Profile</h1>
            <p class="text-[13px] text-slate-400 mt-1.5 font-bold uppercase tracking-widest leading-none border-l-2 border-blue-600 pl-3">Manage your administrative details</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar: Identity Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center relative overflow-hidden group">
                <div class="absolute top-0 left-0 right-0 h-24 bg-gradient-to-br from-[#1e3a8a] to-blue-600"></div>
                <div class="relative pt-8">
                    <div class="h-24 w-24 rounded-[2rem] bg-white p-1.5 mx-auto shadow-xl relative z-10 group-hover:scale-105 transition-transform duration-500 overflow-hidden cursor-pointer" onclick="document.getElementById('logo-input').click()">
                        <img id="profile-logo-preview" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->guard('institute')->user()->name) }}&background=1e3a8a&color=fff&size=128" class="w-full h-full object-cover rounded-[1.75rem]">
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                    </div>
                    <input type="file" id="logo-input" class="hidden" accept="image/*" onchange="previewLogo(this)">
                    <h3 id="display-institute-name" class="mt-4 text-xl font-black text-slate-800 leading-tight">Loading...</h3>
                    <p id="display-owner-name" class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Authorized Administrator</p>
                    
                    <div class="mt-6 pt-6 border-t border-slate-50 grid grid-cols-2 gap-4 text-left">
                        <div>
                            <p class="text-[8px] font-black text-slate-300 uppercase tracking-widest">Status</p>
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-black text-emerald-600 mt-1 uppercase">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Active
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-[8px] font-black text-slate-300 uppercase tracking-widest">Since</p>
                            <p class="text-[10px] font-black text-slate-700 mt-1 uppercase">{{ auth()->guard('institute')->user()->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Security Card -->
            <div class="bg-slate-900 p-6 rounded-2xl text-white relative overflow-hidden shadow-lg shadow-slate-900/10">
                <div class="relative z-10">
                    <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Account Security</h4>
                    <div class="space-y-3">
                        <div onclick="openPasswordModal()" class="flex items-center gap-3 p-3 bg-white/5 rounded-xl border border-white/5 hover:bg-white/10 transition-colors cursor-pointer group/sec">
                            <div class="h-8 w-8 bg-blue-500/20 rounded-lg flex items-center justify-center text-blue-400 group-hover/sec:scale-110 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <span class="text-[11px] font-bold">Change Password</span>
                        </div>
                        
                        <a href="{{ route('institute.plans.index') }}" class="flex items-center gap-3 p-3 bg-white/5 rounded-xl border border-white/5 hover:bg-white/10 transition-colors cursor-pointer group/plan">
                            <div class="h-8 w-8 bg-emerald-500/20 rounded-lg flex items-center justify-center text-emerald-400 group-hover/plan:scale-110 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                            </div>
                            <div>
                                <span class="text-[11px] font-bold block leading-none">Subscription Plan</span>
                                <span id="sidebar-sub-status" class="text-[8px] font-black text-emerald-500 uppercase tracking-widest mt-1.5 block">Loading...</span>
                            </div>
                        </a>

                        <a href="{{ route('institute.whatsapp.setup') }}" class="flex items-center gap-3 p-3 bg-white/5 rounded-xl border border-white/5 hover:bg-white/10 transition-colors cursor-pointer group/wa">
                            <div class="h-8 w-8 bg-blue-500/20 rounded-lg flex items-center justify-center text-blue-400 group-hover/wa:scale-110 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                            </div>
                            <div>
                                <span class="text-[11px] font-bold block leading-none">WhatsApp API</span>
                                <span class="text-[8px] font-black text-blue-400 uppercase tracking-widest mt-1.5 block">Integration Settings</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Settings Form -->
        <div class="lg:col-span-2 space-y-6">
            <form id="profile-form" class="space-y-6" enctype="multipart/form-data">
                @csrf
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between">
                        <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">General Information</h4>
                        <button type="submit" id="save-profile-btn" class="flex items-center gap-2 text-[10px] font-black text-blue-600 uppercase tracking-widest hover:text-blue-700 transition-all">
                            <span>Save Changes</span>
                            <div id="save-loader" class="h-3 w-3 border-2 border-blue-600/20 border-t-blue-600 rounded-full animate-spin hidden"></div>
                        </button>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Institute Name</label>
                                <input type="text" name="institute_name" id="field-institute_name" required class="w-full h-11 px-4 bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-600/20 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Owner Name</label>
                                <input type="text" name="name" id="field-name" required class="w-full h-11 px-4 bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-600/20 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                                <input type="email" name="email" id="field-email" required class="w-full h-11 px-4 bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-600/20 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone Number</label>
                                <input type="text" name="phone" id="field-phone" required class="w-full h-11 px-4 bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-600/20 transition-all">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                        <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Address Details</h4>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Address Line 1</label>
                                <input type="text" name="address" id="field-address" class="w-full h-11 px-4 bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-600/20 transition-all">
                            </div>
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Address Line 2</label>
                                <input type="text" name="address_line_2" id="field-address_line_2" class="w-full h-11 px-4 bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-600/20 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">City</label>
                                <input type="text" name="city" id="field-city" class="w-full h-11 px-4 bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-600/20 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">State</label>
                                <input type="text" name="state" id="field-state" class="w-full h-11 px-4 bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-600/20 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Country</label>
                                <input type="text" name="country" id="field-country" class="w-full h-11 px-4 bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-600/20 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pincode</label>
                                <input type="text" name="pincode" id="field-pincode" class="w-full h-11 px-4 bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-600/20 transition-all">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Password Modal -->
<div id="password-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="p-6 border-b border-slate-50 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-black text-slate-800 leading-tight">Update Password</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Enhance your account security</p>
            </div>
            <button onclick="closePasswordModal()" class="h-8 w-8 rounded-lg hover:bg-slate-50 flex items-center justify-center text-slate-400 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="password-form" class="p-6 space-y-4">
            @csrf
            <div class="space-y-1.5">
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Current Password</label>
                <input type="password" name="current_password" required class="w-full h-11 px-4 bg-slate-50 border border-transparent rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600/20 transition-all">
                <p id="error-current_password" class="text-[10px] font-bold text-rose-500 mt-1 hidden"></p>
            </div>
            <div class="space-y-1.5">
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">New Password</label>
                <input type="password" name="password" required class="w-full h-11 px-4 bg-slate-50 border border-transparent rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600/20 transition-all">
                <p id="error-password" class="text-[10px] font-bold text-rose-500 mt-1 hidden"></p>
            </div>
            <div class="space-y-1.5">
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Confirm New Password</label>
                <input type="password" name="password_confirmation" required class="w-full h-11 px-4 bg-slate-50 border border-transparent rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600/20 transition-all">
            </div>

            <button type="submit" id="submit-btn" class="w-full h-12 bg-[#1e3a8a] text-white rounded-xl font-black text-[13px] uppercase tracking-widest shadow-lg shadow-blue-900/10 hover:scale-[1.02] transition-all flex items-center justify-center gap-3 mt-6">
                <span id="btn-text">Update Password</span>
                <div id="btn-loader" class="h-4 w-4 border-2 border-white/20 border-t-white rounded-full animate-spin hidden"></div>
            </button>
        </form>
    </div>
</div>

<!-- Plans Modal -->
<div id="plans-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="p-4 border-b border-slate-50 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-black text-slate-800 leading-tight">Subscription Plans</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Upgrade your experience with premium features</p>
            </div>
            <button onclick="closePlansModal()" class="h-8 w-8 rounded-lg hover:bg-slate-50 flex items-center justify-center text-slate-400 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6">
            <div id="plans-loader" class="py-20 flex flex-col items-center justify-center">
                <div class="h-10 w-10 border-4 border-blue-600/20 border-t-blue-600 rounded-full animate-spin"></div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-4">Fetching best plans for you...</p>
            </div>
            <div id="plans-container" class="grid grid-cols-1 md:grid-cols-3 gap-6 hidden">
                <!-- Plans will be injected here -->
            </div>
        </div>
    </div>
</div>

<script>
    // --- Profile Logic ---
    document.addEventListener('DOMContentLoaded', fetchProfile);

    async function fetchProfile() {
        try {
            const headers = {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            };
            const token = localStorage.getItem('token');
            if (token) headers['Authorization'] = `Bearer ${token}`;

            const response = await fetch('/api/v1/institute/profile', { headers });
            const result = await response.json();
            if (result.status === 'success') {
                const data = result.data;
                
                // Update Sidebar
                document.getElementById('display-institute-name').innerText = data.institute_name || data.name;
                document.getElementById('display-owner-name').innerText = `Managed by ${data.name}`;
                if (data.logo_url) {
                    document.getElementById('profile-logo-preview').src = data.logo_url;
                }

                // Sidebar Subscription Status
                const sub = result.subscription;
                const sidebarSub = document.getElementById('sidebar-sub-status');
                if (sub) {
                    sidebarSub.innerText = `${sub.plan_name} - ${sub.status.toUpperCase()}`;
                    sidebarSub.className = `text-[8px] font-black ${sub.status.toLowerCase() === 'active' ? 'text-emerald-500' : 'text-rose-500'} uppercase tracking-widest mt-1.5 block`;
                } else {
                    sidebarSub.innerText = 'NO ACTIVE PLAN';
                    sidebarSub.className = 'text-[8px] font-black text-slate-400 uppercase tracking-widest mt-1.5 block';
                }

                // Fill Form
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
        } catch (error) {
            console.error('Error fetching profile:', error);
        }
    }

    document.getElementById('profile-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('save-profile-btn');
        const loader = document.getElementById('save-loader');
        
        btn.disabled = true;
        loader.classList.remove('hidden');

        try {
            const formData = new FormData(e.target);
            const logoInput = document.getElementById('logo-input');
            if (logoInput.files[0]) {
                formData.append('logo', logoInput.files[0]);
            }

            const headers = {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            };
            const token = localStorage.getItem('token');
            if (token) headers['Authorization'] = `Bearer ${token}`;

            const response = await fetch('/api/v1/institute/profile/update', {
                method: 'POST',
                body: formData,
                headers: headers
            });

            const result = await response.json();
            if (response.ok) {
                alert('Profile updated successfully!');
                fetchProfile();
            } else {
                console.error('Update Error Response:', result);
                alert(result.message || 'Error updating profile');
            }
        } catch (error) {
            console.error('Update Request Failed:', error);
            alert('Something went wrong.');
        } finally {
            btn.disabled = false;
            loader.classList.add('hidden');
        }
    });

    function previewLogo(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-logo-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.getElementById('profile-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('save-profile-btn');
        const loader = document.getElementById('save-loader');
        
        btn.disabled = true;
        loader.classList.remove('hidden');

        try {
            const formData = new FormData(e.target);
            const logoInput = document.getElementById('logo-input');
            if (logoInput.files[0]) {
                formData.append('logo', logoInput.files[0]);
            }

            const headers = {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            };
            const token = localStorage.getItem('token');
            if (token) headers['Authorization'] = `Bearer ${token}`;

            const response = await fetch('/api/v1/institute/profile/update', {
                method: 'POST',
                body: formData,
                headers: headers
            });

            const result = await response.json();
            if (response.ok) {
                alert('Profile updated successfully!');
                fetchProfile();
            } else {
                console.error('Update Error Response:', result);
                alert(result.message || 'Error updating profile');
            }
        } catch (error) {
            console.error('Update Request Failed:', error);
            alert('Something went wrong.');
        } finally {
            btn.disabled = false;
            loader.classList.add('hidden');
        }
    });

    // --- Password Logic ---
    function openPasswordModal() {
        document.getElementById('password-modal').classList.replace('hidden', 'flex');
        document.body.style.overflow = 'hidden';
    }

    function closePasswordModal() {
        document.getElementById('password-modal').classList.replace('flex', 'hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('password-form').reset();
        hideErrors();
    }

    function hideErrors() {
        document.querySelectorAll('[id^="error-"]').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('#password-form input').forEach(el => el.classList.remove('border-rose-500', 'bg-rose-50'));
    }

    document.getElementById('password-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        hideErrors();
        
        const btn = document.getElementById('submit-btn');
        const text = document.getElementById('btn-text');
        const loader = document.getElementById('btn-loader');
        
        btn.disabled = true;
        text.classList.add('opacity-50');
        loader.classList.remove('hidden');

        try {
            const formData = new FormData(e.target);
            const response = await fetch('{{ route("institute.profile.password.update") }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const result = await response.json();
            if (response.ok) {
                text.innerText = 'Success!';
                loader.classList.add('hidden');
                setTimeout(() => {
                    closePasswordModal();
                    text.innerText = 'Update Password';
                    btn.disabled = false;
                    text.classList.remove('opacity-50');
                    alert('Password has been updated successfully.');
                }, 1000);
            } else {
                if (result.errors) {
                    Object.keys(result.errors).forEach(key => {
                        const errorEl = document.getElementById(`error-${key}`);
                        if (errorEl) { errorEl.innerText = result.errors[key][0]; errorEl.classList.remove('hidden'); }
                        const inputEl = document.querySelector(`#password-form [name="${key}"]`);
                        if (inputEl) inputEl.classList.add('border-rose-500', 'bg-rose-50');
                    });
                } else {
                    alert('An error occurred.');
                }
                btn.disabled = false;
                text.classList.remove('opacity-50');
                loader.classList.add('hidden');
            }
        } catch (error) {
            alert('Something went wrong.');
            btn.disabled = false;
            text.classList.remove('opacity-50');
            loader.classList.add('hidden');
        }
    });
</script>
@endsection
