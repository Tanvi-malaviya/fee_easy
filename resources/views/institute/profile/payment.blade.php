@extends('layouts.institute')

@section('content')
    <div class="max-w-[800px] mx-auto pb-6 pt-2">

        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-xl font-semibold text-slate-800 tracking-tight leading-tight">
                    UPI Payment Settings
                </h1>
                <p class="text-xs text-slate-400 mt-0.5 font-medium leading-relaxed">
                    Configure UPI ID and QR code to enable direct online fee payments for your students
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('institute.profile.index') }}"
                    class="text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors">
                    Back to Profile
                </a>
                <button onclick="document.getElementById('payment-form').requestSubmit()"
                    class="px-5 py-2.5 bg-[#ff6c00] hover:bg-[#e05f00] text-white rounded-xl font-bold text-[10px] uppercase tracking-widest shadow-lg shadow-orange-500/20 hover:scale-[1.02] active:scale-95 transition-all">
                    Save Settings
                </button>
            </div>
        </div>

        <form id="payment-form" class="space-y-4" enctype="multipart/form-data">
            @csrf

            <!-- Payment Configuration Section -->
            <div class="bg-white rounded-[1rem] shadow-xl border border-slate-100/50 p-5">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1 h-4 bg-[#ff6c00] rounded-full"></div>
                    <h2 class="text-base font-[550] text-slate-800 tracking-tight">UPI Configurations</h2>
                </div>

                <div class="space-y-4">
                    <!-- UPI ID Input -->
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">UPI ID (VPA)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </span>
                            <input type="text" name="upi_id" id="field-upi_id" placeholder="merchant@upi or mobile@ybl" 
                                class="input-with-icon">
                        </div>
                        <p class="text-[9px] text-slate-400 ml-1">Enter a valid merchant VPA or personal UPI ID (e.g. name@bank, phone@upi).</p>
                    </div>

                    <!-- QR Code Upload -->
                    <div class="border-t border-slate-100 pt-4">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 block mb-2">UPI QR Code Image</label>
                        
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                            <!-- QR Preview Box -->
                            <div class="relative group cursor-pointer shrink-0" onclick="document.getElementById('qr-input').click()">
                                <div class="h-32 w-32 bg-slate-50 border border-slate-200 rounded-2xl p-2 shadow-inner flex items-center justify-center overflow-hidden">
                                    <img id="qr-preview-img" src="" class="w-full h-full object-contain hidden">
                                    <div id="qr-placeholder" class="text-center p-2 text-slate-400">
                                        <svg class="w-8 h-8 mx-auto mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        <span class="text-[9px] font-bold uppercase tracking-wider block">No QR Uploaded</span>
                                    </div>
                                </div>
                                <div class="absolute inset-0 bg-black/40 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" 
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Upload Info -->
                            <div class="flex-1">
                                <p class="text-[9px] font-black text-[#ff6c00] uppercase tracking-widest">QR Code Specifications</p>
                                <p class="text-[10px] text-slate-400 font-medium mt-0.5 leading-relaxed">
                                    Please upload the QR code generated from your business app (GPay, PhonePe, Paytm, BHIM, etc.). Max size 2MB. Format: PNG, JPG, JPEG.
                                </p>
                                <div class="mt-3 flex items-center gap-2">
                                    <button type="button" onclick="document.getElementById('qr-input').click()"
                                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-[10px] transition-all">
                                        Choose File
                                    </button>
                                </div>
                            </div>
                            <input type="file" id="qr-input" name="upi_qr_code" class="hidden" accept="image/*" onchange="previewQR(this)">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Loader Overlay -->
            <div id="save-loader"
                class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] hidden items-center justify-center">
                <div class="bg-white p-6 rounded-2xl shadow-xl flex items-center gap-3">
                    <div class="h-5 w-5 border-2 border-slate-200 border-t-[#ff6c00] rounded-full animate-spin"></div>
                    <span class="text-xs font-bold text-slate-700">Updating Payment settings...</span>
                </div>
            </div>
        </form>
    </div>

    <style>
        .input-with-icon {
            width: 100%;
            height: 40px;
            padding: 0 12px 0 38px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            font-weight: 550;
            font-size: 12px;
            color: #334155;
            transition: all 0.2s;
        }

        .input-with-icon:focus {
            outline: none;
            background: #fff;
            border-color: #ff6c00;
            box-shadow: 0 4px 12px rgba(255, 108, 0, 0.05);
        }

        .input-with-icon::placeholder {
            color: #cbd5e1;
            font-weight: 500;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', fetchPaymentSettings);

        async function fetchPaymentSettings() {
            try {
                const headers = { 'X-Requested-With': 'XMLHttpRequest' };
                const token = localStorage.getItem('token');
                if (token) headers['Authorization'] = `Bearer ${token}`;

                const response = await fetch('/api/v1/institute/profile', { headers });
                const result = await response.json();
                
                if (result.status === 'success') {
                    const data = result.data;
                    document.getElementById('field-upi_id').value = data.upi_id || '';
                    
                    const qrImg = document.getElementById('qr-preview-img');
                    const qrPlaceholder = document.getElementById('qr-placeholder');
                    
                    if (data.upi_qr_code_url) {
                        qrImg.src = data.upi_qr_code_url;
                        qrImg.classList.remove('hidden');
                        qrPlaceholder.classList.add('hidden');
                    } else {
                        qrImg.classList.add('hidden');
                        qrPlaceholder.classList.remove('hidden');
                    }
                }
            } catch (error) {
                console.error('Error fetching payment settings:', error);
            }
        }

        function previewQR(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const qrImg = document.getElementById('qr-preview-img');
                    const qrPlaceholder = document.getElementById('qr-placeholder');
                    
                    qrImg.src = e.target.result;
                    qrImg.classList.remove('hidden');
                    qrPlaceholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('payment-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Front-end VPA verification (standard format check)
            const upiId = document.getElementById('field-upi_id').value.trim();
            if (upiId && !/^[\w\.\-]+@[\w\-]+$/.test(upiId)) {
                showToast('Please enter a valid UPI ID (VPA format: name@bank).', 'error');
                return;
            }

            const loader = document.getElementById('save-loader');
            loader.classList.replace('hidden', 'flex');

            try {
                const formData = new FormData(e.target);
                const response = await fetch('/api/v1/institute/profile/payment/update', {
                    method: 'POST',
                    body: formData,
                    headers: { 
                        'X-Requested-With': 'XMLHttpRequest', 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                        'Authorization': `Bearer ${localStorage.getItem('token')}` 
                    }
                });
                
                const result = await response.json();
                
                if (response.ok && result.status === 'success') {
                    showToast('Payment settings updated successfully!');
                    setTimeout(() => {
                        window.location.href = "{{ route('institute.profile.index') }}";
                    }, 1000);
                } else {
                    showToast(result.message || 'Error updating payment settings', 'error');
                }
            } catch (error) {
                console.error('Error updating settings:', error);
                showToast('Something went wrong.', 'error');
            } finally {
                loader.classList.replace('flex', 'hidden');
            }
        });
    </script>
@endsection
