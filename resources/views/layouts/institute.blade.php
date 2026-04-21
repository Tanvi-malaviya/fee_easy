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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Outfit', sans-serif; height: 100vh; margin: 0; background-color: #f8fafc; }
        
        /* Robust Flex Layout Fallback */
        .flex-frame { display: flex; height: 100vh; width: 100%; overflow: hidden; }
        
        #sidebar { 
            background-color: #f4f7fa !important; 
            border-right: 1px solid #e2e8f0;
            width: 18rem; /* 72 units */
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            padding: 1.5rem;
        }

        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            background-color: #f8fafc;
            position: relative;
        }

        .header-frame {
            height: 5rem;
            background-color: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2.5rem;
            flex-shrink: 0;
        }

        .content-scroll {
            flex-grow: 1;
            overflow-y: auto;
            padding: 0.5rem 1rem;
        }

        @media (max-width: 1023px) {
            #sidebar {
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                z-index: 70;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            #sidebar.show { transform: translateX(0); }
        }

        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 antialiased overflow-hidden">
    <!-- Main Frame -->
    <div class="flex-frame">
        
        <!-- Mobile Sidebar Overlay -->
        <div id="mobile-overlay" class="fixed inset-0 bg-slate-900/40 z-[60] hidden lg:hidden"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="custom-scrollbar">
            <!-- Logo Section -->
            <div class="flex items-center justify-between mb-10 px-2">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-[#1e3a8a] rounded-xl flex items-center justify-center shadow-lg shadow-blue-900/10">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                        <h1 class="text-[17px] font-bold text-slate-800 leading-tight">FeeEasy</h1>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Institute Panel</p>
                    </div>
                </div>
                <button id="close-btn" class="lg:hidden p-2 text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 space-y-1.5 overflow-y-auto custom-scrollbar pr-1">
                @php
                    $navItems = [
                        ['route' => 'institute.dashboard', 'label' => 'Dashboard', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                        ['route' => 'institute.students.index', 'label' => 'Students', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 01-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                        ['route' => 'institute.attendance.index', 'label' => 'Attendance', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['route' => 'institute.batches.index', 'label' => 'Batches', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                        ['route' => 'institute.fees.index', 'label' => 'Fees', 'icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                        ['route' => 'institute.reports.index', 'label' => 'Reports', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                        ['route' => 'institute.updates.index', 'label' => 'Updates', 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M16.5 15.5a1.875 1.875 0 100-3.75'],
                    ];
                @endphp

                @foreach($navItems as $item)
                    @php 
                        $isPlaceholder = str_starts_with($item['route'], '#');
                        $active = !$isPlaceholder && request()->routeIs($item['route']); 
                    @endphp
                    <a href="{{ $isPlaceholder ? $item['route'] : route($item['route']) }}" 
                       class="group flex items-center px-4 py-2.5 text-[14px] font-bold transition-all rounded-2xl {{ $active ? 'bg-white text-[#1e3a8a] shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                        <svg class="w-5 h-5 mr-3 transition-colors {{ $active ? 'text-[#1e3a8a]' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <!-- Support Card -->
            <!-- <div class="mt-8 p-6 bg-[#1e3a8a] rounded-[2rem] text-white relative overflow-hidden shrink-0">
                <div class="relative z-10">
                    <p class="text-[11px] font-bold text-blue-200 mb-2 leading-tight">Need assistance?</p>
                    <a href="#" class="block w-full py-2 bg-white text-[#1e3a8a] text-center rounded-xl text-[11px] font-extrabold shadow-lg">Support</a>
                </div>
                <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-white/10 rounded-full blur-xl"></div>
            </div> -->
            <!-- Profile Section -->
            <div class="mt-auto pt-6 border-t border-slate-200/60">
                <div class="flex items-center p-3 bg-white/50 rounded-2xl border border-white/60 shadow-sm backdrop-blur-sm group hover:bg-white hover:shadow-md transition-all duration-300">
                    <div class="h-10 w-10 rounded-xl bg-slate-200 border border-slate-200 overflow-hidden shrink-0 shadow-sm group-hover:scale-105 transition-transform">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->guard('institute')->user()->name) }}&background=1e3a8a&color=fff" class="w-full h-full object-cover">
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <h4 class="text-[13px] font-bold text-slate-800 truncate leading-snug">{{ auth()->guard('institute')->user()->name }}</h4>
                        <span class="text-[10px] uppercase font-bold text-slate-400">Institute Admin</span>
                    </div>
                    <form action="{{ route('institute.logout') }}" method="POST" class="ml-2">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-all group/logout" title="Logout">
                            <svg class="w-5 h-5 group-hover/logout:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Content Area -->
        <div class="main-content">
            <!-- Top Header -->
         

            <!-- Scrollable Body -->
            <div class="content-scroll">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Toggle Script -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const menuBtn = document.getElementById('menu-btn');
        const closeBtn = document.getElementById('close-btn');
        const overlay = document.getElementById('mobile-overlay');

        const toggle = () => {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('hidden');
        };

        menuBtn?.addEventListener('click', toggle);
        closeBtn?.addEventListener('click', toggle);
        overlay?.addEventListener('click', toggle);
    </script>
    @stack('modals')
</body>
</html>
