<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subscription Payment Details</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: '#ff6600',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
            overflow: hidden; /* Prevent scrolling entirely */
        }
        .bg-pattern {
            position: fixed;
            inset: 0;
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 20px 20px;
            z-index: 1;
            pointer-events: none;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.25s ease-out forwards;
        }
    </style>
</head>

<body class="relative min-h-screen flex items-center justify-center py-2 px-3.5">
    <div class="bg-pattern"></div>

    <div class="w-full max-w-sm relative z-10 animate-fadeIn">
        <!-- Header (Compact Single Row) -->
        <div class="flex items-center gap-2.5 mb-3.5 px-1">
            <div class="h-9 w-9 bg-white text-primary rounded-xl flex items-center justify-center shrink-0 border border-orange-100 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-sm font-black text-slate-800 tracking-tight leading-none">Subscription Payment</h1>
                <p class="text-[9px] text-slate-400 font-bold mt-0.5 uppercase tracking-wider">Choose method to pay</p>
            </div>
        </div>

        <!-- Segmented Tab Switcher Container -->
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-3 w-full flex flex-col gap-3">
            <div class="bg-slate-100/75 rounded-lg p-0.5 border border-slate-100 flex gap-0.5">
                <button type="button" onclick="switchTab('qr')" id="btn-tab-qr" class="flex-1 py-1.5 px-2.5 text-[10px] font-black rounded-md transition-all shadow-sm bg-white text-slate-800 border border-slate-100">
                    UPI QR CODE
                </button>
                <button type="button" onclick="switchTab('bank')" id="btn-tab-bank" class="flex-1 py-1.5 px-2.5 text-[10px] font-black text-slate-400 rounded-md transition-all hover:text-slate-800">
                    BANK TRANSFER
                </button>
            </div>

            <!-- Tab Content: QR -->
            <div id="tab-content-qr" class="min-h-[175px] flex flex-col items-center justify-center bg-slate-50/50 rounded-xl border border-slate-100 p-3">
                <div class="relative w-full max-w-[95px] aspect-square rounded-lg overflow-hidden shadow-sm border border-white bg-white">
                    <img src="{{ $paymentSettings['qr_url'] ?? asset('images/payment_qr_code.png') }}" alt="Payment QR Code" class="w-full h-full object-cover">
                </div>
                
                @if(!empty($paymentSettings['upi_id']) && $paymentSettings['upi_id'] !== '—')
                    <div class="mt-2.5 flex items-center gap-1.5 bg-white border border-slate-200/60 px-2.5 py-1.5 rounded-lg w-full max-w-[240px] shadow-sm">
                        <div class="flex-1 min-w-0">
                            <p class="text-[7px] font-black text-slate-400 uppercase tracking-wider leading-none">UPI ID</p>
                            <p id="upi-val" class="text-[10px] font-bold text-slate-700 truncate select-all mt-0.5 leading-none">{{ $paymentSettings['upi_id'] }}</p>
                        </div>
                        <button type="button" onclick="copyText('upi-val', 'UPI ID')" class="p-1 text-slate-400 hover:text-primary active:scale-90 transition-all rounded-md hover:bg-slate-50 shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                        </button>
                    </div>
                @else
                    <p class="text-[9px] font-bold text-slate-400 mt-2 uppercase tracking-wider">Scan with any UPI App</p>
                @endif
            </div>

            <!-- Tab Content: Bank Details -->
            <div id="tab-content-bank" class="hidden min-h-[175px] flex-col justify-center bg-slate-50/50 rounded-xl border border-slate-100 p-3">
                <div class="bg-white border border-slate-100/80 rounded-lg p-2.5 shadow-sm divide-y divide-slate-100 space-y-1.5 text-[10px]">
                    <div class="flex justify-between items-center pb-1.5">
                        <div class="min-w-0 flex-1 pr-2">
                            <span class="block text-[7px] font-black text-slate-400 uppercase tracking-wider">Holder Name</span>
                            <span id="bank-holder" class="font-bold text-slate-700 truncate block">{{ $paymentSettings['bank_holder_name'] ?? 'Tuoora Education' }}</span>
                        </div>
                        <button type="button" onclick="copyText('bank-holder', 'Holder Name')" class="text-slate-400 hover:text-primary p-1 active:scale-95 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" /></svg>
                        </button>
                    </div>
                    <div class="flex justify-between items-center py-1.5">
                        <div class="min-w-0 flex-1 pr-2">
                            <span class="block text-[7px] font-black text-slate-400 uppercase tracking-wider">Bank Name</span>
                            <span id="bank-name" class="font-bold text-slate-700 truncate block">{{ $paymentSettings['bank_name'] ?? 'HDFC Bank' }}</span>
                        </div>
                        <button type="button" onclick="copyText('bank-name', 'Bank Name')" class="text-slate-400 hover:text-primary p-1 active:scale-95 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" /></svg>
                        </button>
                    </div>
                    <div class="flex justify-between items-center py-1.5">
                        <div class="min-w-0 flex-1 pr-2">
                            <span class="block text-[7px] font-black text-slate-400 uppercase tracking-wider">Account Number</span>
                            <span id="bank-acc" class="font-bold text-slate-700 truncate block">{{ $paymentSettings['bank_account'] ?? '' }}</span>
                        </div>
                        <button type="button" onclick="copyText('bank-acc', 'Account Number')" class="text-slate-400 hover:text-primary p-1 active:scale-95 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" /></svg>
                        </button>
                    </div>
                    <div class="flex justify-between items-center pt-1.5">
                        <div class="min-w-0 flex-1 pr-2">
                            <span class="block text-[7px] font-black text-slate-400 uppercase tracking-wider">IFSC Code</span>
                            <span id="bank-ifsc" class="font-bold text-slate-700 truncate block">{{ $paymentSettings['bank_ifsc'] ?? '' }}</span>
                        </div>
                        <button type="button" onclick="copyText('bank-ifsc', 'IFSC Code')" class="text-slate-400 hover:text-primary p-1 active:scale-95 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inline Footer Note (Saves 60px of vertical space) -->
        <div class="mt-3.5 text-center">
            <p class="text-[9px] font-semibold text-slate-400/80 leading-normal">
                After payment, please submit the transaction proof / UTR reference inside your institute dashboard renewal panel.
            </p>
        </div>
    </div>

    <!-- Toast Component (Centered, small) -->
    <div id="toast" class="fixed bottom-4 left-1/2 -translate-x-1/2 z-[300] bg-slate-900/95 backdrop-blur-sm text-white text-[9px] font-black uppercase tracking-wider px-3.5 py-2 rounded-xl shadow-lg flex items-center gap-1.5 translate-y-10 opacity-0 pointer-events-none transition-all duration-350">
        <svg class="w-3 h-3 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4"/>
        </svg>
        <span id="toast-message"></span>
    </div>

    <script>
        function switchTab(tab) {
            const qrBtn = document.getElementById('btn-tab-qr');
            const bankBtn = document.getElementById('btn-tab-bank');
            const qrContent = document.getElementById('tab-content-qr');
            const bankContent = document.getElementById('tab-content-bank');

            if (tab === 'qr') {
                qrBtn.className = "flex-1 py-1.5 px-2.5 text-[10px] font-black rounded-md transition-all shadow-sm bg-white text-slate-800 border border-slate-100";
                bankBtn.className = "flex-1 py-1.5 px-2.5 text-[10px] font-black text-slate-400 rounded-md transition-all hover:text-slate-800";
                qrContent.classList.remove('hidden');
                qrContent.classList.add('flex');
                bankContent.classList.add('hidden');
            } else {
                bankBtn.className = "flex-1 py-1.5 px-2.5 text-[10px] font-black rounded-md transition-all shadow-sm bg-white text-slate-800 border border-slate-100";
                qrBtn.className = "flex-1 py-1.5 px-2.5 text-[10px] font-black text-slate-400 rounded-md transition-all hover:text-slate-800";
                bankContent.classList.remove('hidden');
                bankContent.classList.add('flex');
                qrContent.classList.add('hidden');
            }
        }

        function copyText(elementId, labelName) {
            const textToCopy = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(textToCopy).then(() => {
                showToast(`${labelName} Copied`);
            }).catch(err => {
                console.error('Copy failed: ', err);
            });
        }

        function showToast(message) {
            const toast = document.getElementById('toast');
            const msg = document.getElementById('toast-message');

            if (!toast || !msg) return;

            msg.innerText = message;
            toast.classList.remove('translate-y-10', 'opacity-0', 'pointer-events-none');
            toast.classList.add('translate-y-0', 'opacity-100');

            setTimeout(() => {
                toast.classList.add('translate-y-10', 'opacity-0', 'pointer-events-none');
                toast.classList.remove('translate-y-0', 'opacity-100');
            }, 2000);
        }
    </script>
</body>

</html>
