@extends('layouts.institute')

@section('content')
<div class="max-w-[1000px] mx-auto pb-6 pt-2">

    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-[550] text-slate-800 tracking-tight leading-tight">Edit Institute Profile</h1>
            <p class="text-[10px] text-slate-400 mt-0.5 font-medium leading-relaxed">Update your institute details and institutional settings</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('institute.profile.index') }}" class="text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors">
                Discard Changes
            </a>
            <button onclick="document.getElementById('profile-form').requestSubmit()" class="px-5 py-2.5 bg-[#ff6c00] hover:bg-[#e05f00] text-white rounded-xl font-bold text-[10px] uppercase tracking-widest shadow-lg shadow-orange-500/20 hover:scale-[1.02] active:scale-95 transition-all">
                Save Changes
            </button>
        </div>
    </div>

    <form id="profile-form" class="space-y-4" enctype="multipart/form-data">
        @csrf

        <!-- Institute Identity -->
        <div class="bg-white rounded-[1rem] shadow-xl border border-slate-100/50 p-5">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-1 h-4 bg-[#ff6c00] rounded-full"></div>
                <h2 class="text-base font-[550] text-slate-800 tracking-tight">Institute Identity</h2>
            </div>

            <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
                <div class="relative group cursor-pointer" onclick="document.getElementById('logo-input').click()">
                    <div class="h-24 w-24 bg-white rounded-xl p-1 shadow-2xl border border-slate-100 flex items-center justify-center overflow-hidden shrink-0">
                        <img id="profile-logo-preview" 
                             src="{{ auth()->guard('institute')->user()->logo ? asset('storage/' . auth()->guard('institute')->user()->logo) : 'https://ui-avatars.com/?name=' . urlencode(auth()->guard('institute')->user()->name) . '&background=ff6c00&color=fff' }}" 
                             class="w-full h-full object-cover rounded-xl">
                    </div>
                    <div class="absolute inset-0 bg-black/40 rounded-xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="text-[9px] font-black text-[#ff6c00] uppercase tracking-widest">Identity Requirements</p>
                    <p class="text-[10px] text-slate-400 font-medium mt-0.5 leading-relaxed">Upload your official institutional emblem. Recommended size is 512x512px. Supported formats: PNG, JPG (Max 2MB).</p>
                    
                    <div class="flex items-center gap-3 mt-2 text-[11px] font-bold">
                        <button type="button" onclick="document.getElementById('logo-input').click()" class="text-[#ff6c00] hover:text-[#e05f00] flex items-center gap-1 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Upload Image
                        </button>
                        <button type="button" onclick="removeLogo()" class="text-slate-400 hover:text-slate-600 flex items-center gap-1 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Remove Logo
                        </button>
                    </div>
                </div>
                <input type="file" id="logo-input" name="logo" class="hidden" accept="image/*" onchange="previewLogo(this)">
            </div>
        </div>

        <!-- Basic Information -->
        <div class="bg-white rounded-[1rem] shadow-xl border border-slate-100/50 p-5">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-1 h-4 bg-[#ff6c00] rounded-full"></div>
                <h2 class="text-base font-[550] text-slate-800 tracking-tight">Basic Information</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Institute Name</label>
                    <input type="text" name="institute_name" id="field-institute_name" placeholder="Enter Institute Name" class="input">
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Owner Name</label>
                    <input type="text" name="name" id="field-name" placeholder="Enter Owner Name" class="input">
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Contact Email</label>
                    <input type="email" name="email" id="field-email" placeholder="email@example.com" class="input">
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone Number</label>
                    <input type="text" name="phone" id="field-phone" placeholder="Phone Number" class="input">
                </div>
            </div>
        </div>

        <!-- Residential Address -->
        <div class="bg-white rounded-[1rem] shadow-xl border border-slate-100/50 p-5">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-1 h-4 bg-[#ff6c00] rounded-full"></div>
                <h2 class="text-base font-[550] text-slate-800 tracking-tight">Residential Address</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-1 space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Address Line 1</label>
                    <input type="text" name="address" id="field-address" placeholder="Flat, House no., Building" class="input">
                </div>
                <div class="md:col-span-1 space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Address Line 2</label>
                    <input type="text" name="address_line_2" id="field-address_line_2" placeholder="Area, Street, Sector" class="input">
                </div>
                
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">City</label>
                        <input type="text" name="city" id="field-city" placeholder="City" class="input">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">State / Province</label>
                        <input type="text" name="state" id="field-state" placeholder="State" class="input">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Country</label>
                        <input type="text" name="country" id="field-country" placeholder="Country" class="input" value="India">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Pincode / Zip</label>
                        <input type="text" name="pincode" id="field-pincode" placeholder="Pincode" class="input">
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden save loader overlay -->
        <div id="save-loader" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] hidden items-center justify-center">
            <div class="bg-white p-6 rounded-2xl shadow-xl flex items-center gap-3">
                <div class="h-5 w-5 border-2 border-slate-200 border-t-[#ff6c00] rounded-full animate-spin"></div>
                <span class="text-xs font-bold text-slate-700">Saving changes...</span>
            </div>
        </div>
    </form>
</div>

<style>
.input {
    width: 100%;
    height: 40px;
    padding: 0 12px;
    border-radius: 10px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    font-weight: 550;
    font-size: 12px;
    color: #334155;
    transition: all 0.2s;
}
.input:focus {
    outline: none;
    background: #fff;
    border-color: #ff6c00;
    box-shadow: 0 4px 12px rgba(255, 108, 0, 0.05);
}
.input::placeholder { color: #cbd5e1; font-weight: 500; }
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
                if (data.logo_url) {
                    document.getElementById('profile-logo-preview').src = data.logo_url;
                }

                // Populate form fields
                document.getElementById('field-institute_name').value = data.institute_name || '';
                document.getElementById('field-name').value = data.name || '';
                document.getElementById('field-email').value = data.email || '';
                document.getElementById('field-phone').value = data.phone || '';
                document.getElementById('field-address').value = data.address || '';
                document.getElementById('field-address_line_2').value = data.address_line_2 || '';
                document.getElementById('field-city').value = data.city || '';
                document.getElementById('field-state').value = data.state || '';
                document.getElementById('field-country').value = data.country || 'India';
                document.getElementById('field-pincode').value = data.pincode || '';
            }
        } catch (error) { console.error('Error fetching profile:', error); }
    }

    document.getElementById('profile-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const loader = document.getElementById('save-loader');
        loader.classList.replace('hidden', 'flex');

        try {
            const formData = new FormData(e.target);
            const response = await fetch('/api/v1/institute/profile/update', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Authorization': `Bearer ${localStorage.getItem('token')}` }
            });
            if (response.ok) { 
                showToast('Profile updated successfully!'); 
                setTimeout(() => {
                    window.location.href = "{{ route('institute.profile.index') }}";
                }, 1000);
            } else { 
                showToast('Error updating profile', 'error'); 
            }
        } catch (error) { showToast('Something went wrong.', 'error'); }
        finally { loader.classList.replace('flex', 'hidden'); }
    });

    function previewLogo(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('profile-logo-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeLogo() {
        if(confirm('Are you sure you want to remove the logo?')) {
            document.getElementById('profile-logo-preview').src = 'https://ui-avatars.com/?name=' + encodeURIComponent(document.getElementById('field-institute_name').value || 'Institute') + '&background=ff6c00&color=fff';
            // Optional: Handle logo removal on backend if needed, or let user upload a new one
        }
    }
</script>
@endsection
