<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FeeEasy') }} - Institute Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
                        primary: '#FF6B00',
                         primary2: '#713f1bff',
                        secondary: '#00A7B5',
                        tertiary: '#2ECC71',
                        neutral: '#F8F9FA',
                        brand: {
                            900: '#FF6B00', // Using Primary as Brand 900 for consistency
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #F8F9FA; }
        .nav-link.active { color: #FF6B00; }
        .nav-link.active::after { content: ''; position: absolute; bottom: -20px; left: 0; right: 0; height: 3px; background: #FF6B00; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        .btn-primary { background-color: #FF6B00; color: white; transition: all 0.3s; }
        .btn-primary:hover { background-color: #e66000; transform: translateY(-1px); }
        
        .card-premium { background-color: white; border: 1px solid #f1f5f9; border-radius: 2rem; transition: all 0.3s; }
        .card-premium:hover { shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1); transform: translateY(-2px); }
    </style>
</head>
<body class="bg-[#F8F9FA] text-slate-900 antialiased overflow-x-hidden">

    <!-- TOP NAVBAR -->
    <header class="fixed top-0 left-0 right-0 h-16 bg-white border-b border-slate-100 z-[100] shadow-sm">
        <div class="max-w-[1600px] mx-auto h-full px-6 flex items-center justify-between">
            
            <!-- Logo Section -->
            <div class="flex items-center gap-3">
                <button id="mobile-sidebar-toggle" class="lg:hidden h-9 w-9 flex items-center justify-center text-slate-400 bg-slate-50 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                </button>
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/turooa.png') }}" alt="Logo" class="h-12 w-auto object-contain" onerror="this.style.display='none'">

                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="hidden lg:flex items-center gap-10 h-full">
                @php
                    $navItems = [
                        ['route' => 'institute.dashboard', 'label' => 'Dashboard'],
                        ['route' => 'institute.students.index', 'label' => 'Students'],
                        ['route' => 'institute.batches.index', 'label' => 'Batch'],
                        ['route' => 'institute.reports.index', 'label' => 'Report'],
                        ['route' => 'institute.fees.index', 'label' => 'Finance'],
                    ];
                @endphp

                @foreach($navItems as $item)
                    @php 
                        $active = request()->routeIs($item['route']) || (request()->is('institute/batches/*') && $item['label'] == 'Batch') || (request()->is('institute/students/*') && $item['label'] == 'Students');
                    @endphp
                    <a href="{{ route($item['route']) }}" 
                       class="relative h-full flex items-center text-[14px] font-bold transition-all {{ $active ? 'text-[#FF6B00]' : 'text-slate-500 hover:text-slate-800' }}">
                        {{ $item['label'] }}
                        @if($active)
                            <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-[#FF6B00] rounded-full"></div>
                        @endif
                    </a>
                @endforeach
            </nav>

            <!-- Right Profile Section -->
            <div class="flex items-center gap-4">
                <a href="{{ route('institute.notifications.index') }}" class="text-slate-400 hover:text-slate-800 transition-colors relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span id="notif-dot" class="absolute -top-1 -right-1 h-2.5 w-2.5 bg-[#FF6B00] rounded-full border-2 border-white hidden"></span>
                </a>
                
                <div class="h-6 w-[1px] bg-slate-200 hidden md:block"></div>

                <a href="{{ route('institute.profile.index') }}" class="h-9 w-9 rounded-full bg-slate-100 overflow-hidden border border-slate-100 hover:border-orange-500 transition-all shrink-0">
                    <img src="{{ auth('institute')->user()->logo ? asset('storage/' . auth('institute')->user()->logo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth('institute')->user()->institute_name) . '&background=F1F5F9&color=64748B&bold=true' }}" 
                         class="h-full w-full object-cover">
                </a>

                <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                    class="h-9 w-9 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all group"
                    title="Logout">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>

                <form id="logout-form" action="{{ route('institute.logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </header>

    <!-- MOBILE MENU -->
    <div id="mobile-menu" class="fixed inset-0 bg-white z-[200] hidden flex-col">
        <div class="p-6 flex items-center justify-between border-b border-slate-100">
            <h1 class="text-xl font-black text-slate-800">Menu</h1>
            <button id="close-menu" class="p-2 text-slate-400"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div class="p-6 flex flex-col space-y-4 overflow-y-auto">
            @foreach($navItems as $item)
                <a href="{{ route($item['route']) }}" class="text-lg font-black text-slate-700 uppercase tracking-widest">{{ $item['label'] }}</a>
            @endforeach
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main class="mt-16 px-4 md:px-6 pt-2 pb-4 min-h-[calc(100vh-64px)]">
        @yield('content')
    </main>

    <!-- Global Components -->
    <div id="global-loader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white/60 backdrop-blur-[2px] hidden transition-all duration-300">
        <div class="flex flex-col items-center">
            <div class="h-12 w-12 border-4 border-slate-100 border-t-primary rounded-full animate-spin"></div>
            <span class="mt-4 text-[10px] font-black text-slate-500 tracking-widest uppercase">Processing...</span>
        </div>
    </div>

    <div id="toast-container" class="fixed top-6 right-6 z-[100] space-y-3 pointer-events-none"></div>

    <script>
        // Mobile Menu Logic
        const menuBtn = document.getElementById('menu-btn');
        const closeMenu = document.getElementById('close-menu');
        const mobileMenu = document.getElementById('mobile-menu');

        menuBtn?.addEventListener('click', () => mobileMenu.classList.replace('hidden', 'flex'));
        closeMenu?.addEventListener('click', () => mobileMenu.classList.replace('flex', 'hidden'));

        // Loader Logic
        function toggleLoader(show) {
            const loader = document.getElementById('global-loader');
            if (show) {
                loader.classList.remove('hidden');
                loader.classList.add('flex');
                document.body.style.overflow = 'hidden';
            } else {
                loader.classList.add('hidden');
                loader.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
        }

        // Toast Logic
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-tertiary' : 'bg-rose-600';
            const icon = type === 'success' 
                ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>'
                : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>';

            toast.className = `${bgColor} text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 animate-in slide-in-from-right duration-300 pointer-events-auto cursor-pointer`;
            toast.innerHTML = `<div class="h-8 w-8 bg-white/20 rounded-lg flex items-center justify-center shrink-0">${icon}</div><p class="text-[11px] font-black uppercase tracking-widest">${message}</p>`;
            toast.onclick = () => toast.remove();
            container.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('animate-out', 'fade-out', 'slide-out-to-right');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        document.addEventListener('DOMContentLoaded', () => {
            
            // Mobile Menu Toggle
            const menuBtn = document.getElementById('mobile-sidebar-toggle');
            const closeBtn = document.getElementById('close-menu');
            const menu = document.getElementById('mobile-menu');

            if (menuBtn && menu) {
                menuBtn.addEventListener('click', () => {
                    menu.classList.remove('hidden');
                    menu.classList.add('flex');
                });
            }

            if (closeBtn && menu) {
                closeBtn.addEventListener('click', () => {
                    menu.classList.add('hidden');
                    menu.classList.remove('flex');
                });
            }
        });
    </script>
    @stack('modals')
</body>
</html>
