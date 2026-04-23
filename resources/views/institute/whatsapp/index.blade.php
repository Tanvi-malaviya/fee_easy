@extends('layouts.institute')

@section('title', 'WhatsApp Integration')

@section('content')
<div class="p-6">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('institute.profile.index') }}" class="h-10 w-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-600/30 transition-all shadow-sm group">
            <svg class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-800 leading-tight">WhatsApp Integration</h1>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-1">Connect your Meta WhatsApp Cloud API for automated notifications</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Help/Status Card -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="h-10 w-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-wider">API Status</h3>
                        <div id="api-status-badge" class="flex items-center gap-1.5 mt-0.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-slate-300"></span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Not Configured</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <p class="text-xs font-bold text-slate-500 leading-relaxed">
                        To enable automated fees reminders and updates, you need to integrate your WhatsApp Cloud API credentials.
                    </p>
                    <div class="p-4 bg-blue-50/50 rounded-xl border border-blue-100/50">
                        <p class="text-[10px] font-bold text-blue-600/70 uppercase tracking-widest mb-2">Setup Guide</p>
                        <ul class="space-y-2">
                            <li class="flex items-center gap-2 text-[11px] font-bold text-blue-800">
                                <span class="h-1 w-1 bg-blue-400 rounded-full"></span>
                                Meta Business App
                            </li>
                            <li class="flex items-center gap-2 text-[11px] font-bold text-blue-800">
                                <span class="h-1 w-1 bg-blue-400 rounded-full"></span>
                                Add WhatsApp Product
                            </li>
                            <li class="flex items-center gap-2 text-[11px] font-bold text-blue-800">
                                <span class="h-1 w-1 bg-blue-400 rounded-full"></span>
                                Generate System Token
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Form -->
        <div class="lg:col-span-2">
            <form id="whatsapp-form" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                    <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Credential Settings</h4>
                </div>
                <div class="p-8">
                    <div id="form-loader" class="py-12 flex flex-col items-center justify-center">
                        <div class="h-8 w-8 border-3 border-blue-600/20 border-t-blue-600 rounded-full animate-spin"></div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-4">Loading configurations...</p>
                    </div>

                    <div id="form-content" class="space-y-6 hidden">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">WhatsApp Phone Number</label>
                            <input type="text" name="phone_number" id="field-phone_number" required class="w-full bg-slate-50 border-slate-100 rounded-xl py-3 px-4 text-sm font-bold text-slate-700 focus:bg-white focus:border-blue-600/30 transition-all outline-none" placeholder="e.g. 919876543210">
                            <p class="text-[9px] font-bold text-slate-400 ml-1 mt-1 uppercase">Include country code without + (e.g. 91 for India)</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone Number ID</label>
                                <input type="text" name="phone_number_id" id="field-phone_number_id" required class="w-full bg-slate-50 border-slate-100 rounded-xl py-3 px-4 text-sm font-bold text-slate-700 focus:bg-white focus:border-blue-600/30 transition-all outline-none" placeholder="e.g. 109842512345678">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Business Account ID</label>
                                <input type="text" name="business_account_id" id="field-business_account_id" required class="w-full bg-slate-50 border-slate-100 rounded-xl py-3 px-4 text-sm font-bold text-slate-700 focus:bg-white focus:border-blue-600/30 transition-all outline-none" placeholder="e.g. 153094812345678">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Permanent Access Token</label>
                            <textarea name="access_token" id="field-access_token" rows="4" required class="w-full bg-slate-50 border-slate-100 rounded-xl py-3 px-4 text-sm font-bold text-slate-700 focus:bg-white focus:border-blue-600/30 transition-all outline-none resize-none" placeholder="EAAW..."></textarea>
                        </div>

                      

                        <div class="pt-4">
                            <button type="submit" id="save-btn" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-blue-600 transition-all shadow-lg shadow-slate-900/10 hover:shadow-blue-600/20 active:scale-[0.98] flex items-center justify-center gap-3">
                                <span>Save Integration</span>
                                <div id="save-loader" class="h-4 w-4 border-2 border-white/20 border-t-white rounded-full animate-spin hidden"></div>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', fetchSettings);

    async function fetchSettings() {
        const loader = document.getElementById('form-loader');
        const content = document.getElementById('form-content');
        
        try {
            const headers = {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            };
            const token = localStorage.getItem('token');
            if (token) headers['Authorization'] = `Bearer ${token}`;

            const response = await fetch('/api/v1/institute/whatsapp-settings', { headers });
            const result = await response.json();
            
            if (result.status === 'success' && result.data) {
                const data = result.data;
                document.getElementById('field-phone_number').value = data.phone_number || '';
                document.getElementById('field-phone_number_id').value = data.phone_number_id || '';
                document.getElementById('field-business_account_id').value = data.business_account_id || '';
                document.getElementById('field-access_token').value = data.access_token || '';
                document.getElementById('field-is_active').checked = data.is_active;
                
                if (data.access_token) {
                    updateStatusBadge(data.is_active);
                }
            }
        } catch (error) {
            console.error('Fetch Error:', error);
        } finally {
            loader.classList.add('hidden');
            content.classList.remove('hidden');
        }
    }

    function updateStatusBadge(active) {
        const badge = document.getElementById('api-status-badge');
        if (active) {
            badge.innerHTML = `
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Active & Connected</span>
            `;
        } else {
            badge.innerHTML = `
                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                <span class="text-[10px] font-bold text-amber-600 uppercase tracking-widest">Paused / Disabled</span>
            `;
        }
    }

    document.getElementById('whatsapp-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('save-btn');
        const loader = document.getElementById('save-loader');
        
        btn.disabled = true;
        loader.classList.remove('hidden');

        try {
            const formData = new FormData(e.target);
            const data = {
                phone_number: formData.get('phone_number'),
                phone_number_id: formData.get('phone_number_id'),
                business_account_id: formData.get('business_account_id'),
                access_token: formData.get('access_token'),
                is_active: formData.get('is_active') === '1' ? 1 : 0
            };

            const headers = {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            };
            const token = localStorage.getItem('token');
            if (token) headers['Authorization'] = `Bearer ${token}`;

            const response = await fetch('/api/v1/institute/whatsapp-settings', {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (response.ok) {
                alert('WhatsApp settings updated successfully!');
                updateStatusBadge(data.is_active);
            } else {
                alert(result.message || 'Error updating settings');
            }
        } catch (error) {
            console.error('Update Request Failed:', error);
            alert('Something went wrong.');
        } finally {
            btn.disabled = false;
            loader.classList.add('hidden');
        }
    });
</script>
@endsection
