<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FeeEasy') }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,900&display=swap" rel="stylesheet" />

    <style>
        .auth-bg {
            background: 
                radial-gradient(circle at 0% 0%, rgba(79, 70, 229, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 100% 100%, rgba(99, 102, 241, 0.05) 0%, transparent 40%),
                #f8fafc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .auth-card {
            width: 100%;
            max-width: 440px;
            background: white;
            border-radius: 2rem;
            box-shadow: 
                0 20px 25px -5px rgba(0, 0, 0, 0.03),
                0 10px 10px -5px rgba(0, 0, 0, 0.02),
                0 0 0 1px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .input-standard {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
              padding: 2px;
        }

        .input-standard:focus {
            transform: translateY(-1px);
          }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased bg-slate-50">
    <div class="auth-bg">
        <div class="auth-card p-10 sm:p-12">
            <!-- Branding Header -->
            <div class="flex flex-col items-center mb-10 text-center">
                <a href="/" class="flex flex-col items-center gap-4 group transition-transform hover:scale-105 active:scale-95">
                    <div class="w-16 h-16 rounded-2xl bg-white p-2.5 flex items-center justify-center shadow-2xl shadow-indigo-500/20 border border-gray-100 shrink-0">
                        <x-application-logo class="w-full h-full object-contain" />
                    </div>
                    <div class="flex flex-col items-center">
                        <h1 class="text-2xl font-black text-gray-900 tracking-tight leading-none uppercase">
                            {{ App\Models\SystemSetting::get('site_name', 'FeeEasy') }}
                        </h1>
                        <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-[0.4em] mt-2 translate-x-[0.2em]">
                            Management System
                        </p>
                    </div>
                </a>
            </div>

            <!-- Page Slot (Forms) -->
            <div class="w-full">
                {{ $slot }}
            </div>

            <!-- Footer Branding -->
            <!-- <div class="mt-12 pt-8 border-t border-gray-50 flex flex-col items-center gap-3">
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Secure Administrative Gateway</span>
                </div>
                <p class="text-[9px] text-gray-300 font-bold uppercase tracking-[0.15em]">
                    &copy; {{ date('Y') }} {{ App\Models\SystemSetting::get('site_name', 'FeeEasy') }}
                </p>
            </div> -->
        </div>

        <!-- Support Link -->
        <!-- <p class="mt-8 text-xs text-gray-400 font-medium tracking-wide">
            Need assistance? <a href="#" class="text-indigo-600 hover:text-indigo-700 font-bold underline underline-offset-4 decoration-indigo-200">Contact Support</a>
        </p> -->
    </div>
</body>

</html>