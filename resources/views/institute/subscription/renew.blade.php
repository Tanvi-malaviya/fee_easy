@extends('layouts.institute')

@section('title', 'Subscription Renewal - Tuoora')

@section('content')
<div class="bg-pattern"></div>
<div class="max-w-3xl mx-auto mt-6 px-4 relative z-10">
    <!-- Page Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="h-12 w-12 bg-white text-[#ff6c00] rounded-2xl flex items-center justify-center shrink-0 border border-orange-100 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <h1 class="text-xl font-black text-slate-800 tracking-tight">Subscription Renewal</h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Verify your offline bank/UPI payment to restore access</p>
            </div>
        </div>
        <a href="{{ route('institute.dashboard') }}" class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-600 text-xs font-bold rounded-xl border border-slate-200 transition-all shadow-sm shrink-0 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Back to Dashboard
        </a>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm w-full overflow-hidden relative">
        <div class="p-5 md:p-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <!-- Left: QR & Account Details -->
                <div class="md:col-span-6 flex flex-col gap-3">
                    <div class="bg-slate-50/75 rounded-xl p-0.5 border border-slate-100 flex gap-0.5">
                        <button type="button" onclick="switchTab('qr')" id="btn-tab-qr" class="flex-1 py-1.5 px-2 text-[10px] font-bold rounded-lg transition-all shadow-sm bg-white text-slate-800 border border-slate-100">
                            Scan UPI QR
                        </button>
                        <button type="button" onclick="switchTab('bank')" id="btn-tab-bank" class="flex-1 py-1.5 px-2 text-[10px] font-bold rounded-lg transition-all text-slate-500 hover:text-slate-800">
                            Bank Details
                        </button>
                    </div>

                    <!-- Tab: QR -->
                    <div id="tab-content-qr" class="h-[200px] flex flex-col items-center justify-center bg-white rounded-xl border border-slate-100 p-3 shadow-sm relative overflow-hidden group">
                        <div class="relative w-full max-w-[130px] aspect-[4/5] rounded-lg overflow-hidden shadow-sm">
                            <img src="{{ $paymentSettings['qr_url'] ?? asset('images/payment_qr_code.png') }}" alt="Payment QR Code" class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-500">
                        </div>
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-wider mt-2 text-center">Scan with any UPI App</span>
                    </div>

                    <!-- Tab: Bank Details -->
                    <div id="tab-content-bank" class="hidden h-[200px] flex-col justify-center bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
                        <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2.5">Bank Transfer Details</h3>
                        <div class="space-y-2 text-[10px]">
                            <div class="flex justify-between border-b border-slate-50 pb-1">
                                <span class="text-slate-400">Holder:</span>
                                <span class="font-bold text-slate-800">{{ $paymentSettings['bank_holder_name'] ?? 'Tuoora Education' }}</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-50 pb-1">
                                <span class="text-slate-400">Bank:</span>
                                <span class="font-bold text-slate-800">{{ $paymentSettings['bank_name'] ?? 'HDFC Bank' }}</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-50 pb-1">
                                <span class="text-slate-400">A/C No:</span>
                                <span class="font-bold text-slate-800">{{ $paymentSettings['bank_account'] ?? '' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">IFSC:</span>
                                <span class="font-bold text-slate-800">{{ $paymentSettings['bank_ifsc'] ?? '' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Default share institute ID -->
                    <div class="bg-gradient-to-r from-orange-50/70 to-amber-50/30 border border-orange-100/60 rounded-xl p-3 shadow-sm">
                        <h4 class="text-[9px] font-black text-orange-800 uppercase tracking-wider">Institute Reference</h4>
                        <div class="text-[10px] text-slate-500 mt-0.5 font-medium space-y-0.5">
                            <div>Code: <strong class="text-slate-800" id="ref-inst-code">{{ $institute->institute_code }}</strong></div>
                        </div>
                    </div>
                </div>

                <!-- Right: Submission Form -->
                <div class="md:col-span-6">
                    <form id="renewForm" onsubmit="handleRenewSubmit(event)" class="bg-slate-50/50 rounded-xl border border-slate-100 p-4 flex flex-col gap-3">
                        @csrf
                        <div>
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1">Transaction ID / Ref Number *</label>
                            <input type="text" name="transaction_id" required placeholder="Enter UTR, Ref No. or Txn ID" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-orange-500/50 focus:ring-1 focus:ring-orange-500/20 transition-all font-medium">
                        </div>

                        <div>
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1">Payment Screenshot *</label>
                            <div class="bg-white border border-slate-200 rounded-lg p-2.5 flex flex-col items-center justify-center cursor-pointer hover:border-orange-500/30 transition-all group relative overflow-hidden" id="screenshot-upload-box">
                                <input type="file" name="screenshot" id="screenshot-input" required accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="previewScreenshot(event)">
                                <svg class="w-6 h-6 text-slate-300 group-hover:text-orange-500/50 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <span class="text-[9px] font-bold text-slate-400 group-hover:text-slate-500 transition-colors mt-1 text-center" id="screenshot-label">Upload screenshot (JPG, PNG)</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1">Message (Optional)</label>
                            <textarea name="message" rows="2" placeholder="Enter any specific note..." class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-orange-500/50 focus:ring-1 focus:ring-orange-500/20 transition-all font-medium resize-none"></textarea>
                        </div>

                        <button type="submit" id="submit-renew-btn" class="w-full py-2 bg-[#ff6c00] hover:bg-[#e66100] text-white text-xs font-black uppercase tracking-wider rounded-lg transition-all shadow-md shadow-orange-500/10 hover:shadow-lg active:scale-98">
                            Submit Proof
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(tab) {
        const qrBtn = document.getElementById('btn-tab-qr');
        const bankBtn = document.getElementById('btn-tab-bank');
        const qrContent = document.getElementById('tab-content-qr');
        const bankContent = document.getElementById('tab-content-bank');

        if (tab === 'qr') {
            qrBtn.className = "flex-1 py-1.5 px-2 text-[10px] font-bold rounded-lg transition-all shadow-sm bg-white text-slate-800 border border-slate-100";
            bankBtn.className = "flex-1 py-1.5 px-2 text-[10px] font-bold rounded-lg transition-all text-slate-500 hover:text-slate-800";
            qrContent.classList.remove('hidden');
            qrContent.classList.add('flex');
            bankContent.classList.add('hidden');
        } else {
            bankBtn.className = "flex-1 py-1.5 px-2 text-[10px] font-bold rounded-lg transition-all shadow-sm bg-white text-slate-800 border border-slate-100";
            qrBtn.className = "flex-1 py-1.5 px-2 text-[10px] font-bold rounded-lg transition-all text-slate-500 hover:text-slate-800";
            bankContent.classList.remove('hidden');
            bankContent.classList.add('flex');
            qrContent.classList.add('hidden');
        }
    }

    function copyInstituteDetails() {
        const idText = document.getElementById('ref-inst-id').innerText;
        const codeText = document.getElementById('ref-inst-code').innerText;
        const textToCopy = `Institute ID: ${idText} | Code: ${codeText}`;
        
        navigator.clipboard.writeText(textToCopy).then(() => {
            alert('Institute Details copied to clipboard!');
        }).catch(err => {
            console.error('Copy details failed: ', err);
        });
    }

    function previewScreenshot(event) {
        const input = event.target;
        const label = document.getElementById('screenshot-label');
        if (input.files && input.files[0]) {
            label.innerText = `Selected: ${input.files[0].name}`;
            label.classList.remove('text-slate-400');
            label.classList.add('text-orange-600');
        }
    }

    async function handleRenewSubmit(event) {
        event.preventDefault();
        const form = event.target;
        const submitBtn = document.getElementById('submit-renew-btn');
        const originalText = submitBtn.innerText;

        submitBtn.disabled = true;
        submitBtn.innerText = 'SUBMITTING...';

        const formData = new FormData(form);

        try {
            const response = await fetch('{{ route("institute.subscription.renew") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: formData
            });

            const result = await response.json();

            if (response.ok && result.status === 'success') {
                window.location.href = '{{ route("institute.dashboard") }}';
            } else {
                alert(result.message || 'Something went wrong. Please check your inputs.');
            }
        } catch (err) {
            console.error(err);
            alert('An error occurred during submission.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerText = originalText;
        }
    }
</script>

<style>
    @keyframes scaleUp {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    .animate-scaleUp {
        animation: scaleUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;400;500;600;700;800;900&display=swap');

    :root {
        --font-outfit: 'Outfit', sans-serif;
    }

    body {
        font-family: var(--font-outfit);
        background-color: #f8fafc;
    }

    .bg-pattern {
        position: fixed;
        inset: 0;
        background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
        background-size: 30px 30px;
        z-index: 1;
        pointer-events: none;
    }
</style>
@endsection
