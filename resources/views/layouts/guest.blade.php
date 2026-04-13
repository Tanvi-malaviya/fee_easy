<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
Experience the future of fee management and administrative control with our premium authorized portal.
</p>
</div>
</div>

<div class="relative z-10">
    <div class="flex items-center gap-4">
        <div class="flex -space-x-2">
            <div class="w-8 h-8 rounded-full border-2 border-gray-900 bg-indigo-500"></div>
            <div class="w-8 h-8 rounded-full border-2 border-gray-900 bg-emerald-500"></div>
            <div class="w-8 h-8 rounded-full border-2 border-gray-900 bg-amber-500"></div>
        </div>
        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Trusted by 500+ Institutions</p>
    </div>
</div>
</div>

<!-- Mobile Branding (Only visible on small screens) -->
<div class="md:hidden bg-gray-900 p-8 flex items-center justify-between border-b border-gray-800">
    <span class="text-2xl font-black text-white tracking-tighter uppercase">
        ⚡ {{ App\Models\SystemSetting::get('site_name', 'FeeEasy') }}
    </span>
</div>

<!-- Right Pane: Authentication Form -->
<div class="flex-1 flex flex-col items-center justify-center p-8 lg:p-12 bg-white overflow-y-auto">
    <div class="w-full max-w-[420px] animate-in fade-in slide-in-from-bottom-4 duration-700">
        {{ $slot }}

        <div class="mt-12 text-center md:text-left">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">
                &copy; {{ date('Y') }} {{ App\Models\SystemSetting::get('site_name', 'FeeEasy') }} &bull; Secure
                Administrative Gateway
            </p>
        </div>
    </div>
</div>

</div>
</body>

</html> background: radial-gradient(circle at 20% 20%, rgba(66, 133, 244, 0.4) 0%, transparent 40%),
radial-gradient(circle at 80% 80%, rgba(26, 75, 140, 0.5) 0%, transparent 40%);
filter: blur(80px);
z-index: 0;
}
.auth-card {
width: 100%;
max-width: 400px;
position: relative;
z-index: 10;
padding: 2rem;
}
</style>
</head>

<body class="antialiased text-white">
    <div class="auth-bg">
        <div class="auth-card">
            {{ $slot }}
        </div>
    </div>
</body>

</html>