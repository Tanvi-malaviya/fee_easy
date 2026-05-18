<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FeeEasy') }} - Secure Gateway</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <style>
        :root {
            --primary-color: #ff6c00;
            --primary-light: rgba(255, 108, 0, 0.15);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .bg-primary { background-color: var(--primary-color) !important; }
        .text-primary { color: var(--primary-color) !important; }
        .border-primary { border-color: var(--primary-color) !important; }

        .auth-bg {
            background: 
                radial-gradient(circle at 0% 0%, rgba(255, 108, 0, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 100% 100%, rgba(255, 108, 0, 0.12) 0%, transparent 50%),
                #f8fafc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .auth-card {
            width: 100%;
            max-width: 440px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 1.75rem;
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.08),
                0 0 0 1px rgba(255, 108, 0, 0.1);
            overflow: hidden;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            z-index: 10;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="font-sans text-slate-900 antialiased bg-slate-50">
    <div class="auth-bg">
        <!-- Glow Orbs -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-orange-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="auth-card p-8 sm:p-12">
            <!-- Top Gradient Bar -->
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#ff6c00] to-orange-300"></div>

            <!-- Branding Header -->
            <div class="flex flex-col items-center mb-8 text-center">
                <a href="/" class="flex flex-col items-center group transition-transform hover:scale-105 active:scale-95">
                    <img src="{{ asset('images/2.png') }}" alt="Logo" class="h-10 w-auto object-contain mb-3.5" />
                    <span class="text-[10px] font-black tracking-[0.3em] text-[#ff6c00] uppercase px-3.5 py-1 bg-orange-50 rounded-full border border-orange-100/50 shadow-sm">
                        Management Portal
                    </span>
                </a>
            </div>

            <!-- Page Slot (Forms) -->
            <div class="w-full">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>