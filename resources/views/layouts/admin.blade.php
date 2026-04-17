<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FeeEasy Admin</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine JS -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- SlimSelect -->
    <link href="https://unpkg.com/slim-select@2.8.2/dist/slimselect.css" rel="stylesheet" />
    <script src="https://unpkg.com/slim-select@2.8.2/dist/slimselect.min.js"></script>

    <style>
        /* Custom Scrollbar Styling */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #111827;
            /* gray-900 */
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #374151;
            /* gray-700 */
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #6366f1;
            /* indigo-500 */
        }

        /* For Firefox */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #374151 #111827;
        }

        /* SlimSelect Premium Indigo Theme Overrides */
        .ss-main {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            min-height: 46px !important;
            border-radius: 0.75rem !important;
            padding: 0 1rem !important;
            border: 1px solid #e5e7eb !important;
            background-color: #f9fafb !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            cursor: pointer;
            box-shadow: none !important;
        }

        .ss-main:hover {
            border-color: #d1d5db !important;
            background-color: #f3f4f6 !important;
        }

        .ss-main:focus-within {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1) !important;
            background-color: #ffffff !important;
        }

        /* Floating Context-Menu Design */
        .ss-main.ss-open-below,
        .ss-main.ss-open-above {
            border-radius: 0.75rem !important;
            border-color: #6366f1 !important;
            background-color: #ffffff !important;
        }

        .ss-content {
            border-radius: 0.75rem !important;
            margin-top: 8px !important;
            /* Floating gap */
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            z-index: 10000 !important;
            overflow: hidden !important;
            padding: 0.5rem !important;
            background-color: #ffffff !important;
        }

        .ss-list {
            max-height: 180px !important;
        }

        .ss-option {
            border-radius: 0.5rem !important;
            padding: 0.6rem 0.8rem !important;
            margin-bottom: 2px !important;
            font-size: 0.875rem !important;
            transition: all 0.2s !important;
        }

        .ss-option:hover {
            background-color: #f5f3ff !important;
            color: #4f46e5 !important;
        }

        .ss-option.ss-highlighted {
            background-color: #4f46e5 !important;
            color: #ffffff !important;
        }

        .ss-placeholder {
            color: #9ca3af !important;
        }

        /* Global Button Loader Styles */
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .spinner-loader {
            display: inline-block;
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid currentColor;
            border-bottom-color: transparent;
            border-radius: 50%;
            animation: spin 0.75s linear infinite;
        }

        .btn-loading {
            pointer-events: none !important;
            opacity: 0.8 !important;
            position: relative !important;
            color: transparent !important;
        }

        .btn-loading .btn-spinner {
            color: white !important;
            position: absolute !important;
            left: 50% !important;
            top: 50% !important;
            transform: translate(-50%, -50%) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .ss-placeholder {
            color: #9ca3af !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
        }

        /* --- Global Button Standardization --- */
        /* Targets buttons, specific color utility links, and general button classes */
        button:not(.no-loader),
        .btn,
        a.bg-indigo-600,
        a.bg-white,
        a.bg-emerald-600,
        a.bg-red-600,
        a.border-gray-200 {
            box-shadow: none !important;
            /* Force shadow removal */
            border-radius: 0.75rem !important;
            /* Standardized rounded-xl */
            text-transform: uppercase !important;
            font-weight: 600 !important;
            /* Softened bold */
            letter-spacing: 0.05em !important;
            font-size: 13px !important;
            /* Increased font size */
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        /* --- Sidebar Navigation Refinement --- */
        /* Specifically targeting sidebar links to prevent "bulky" active look */
        nav a {
            font-size: 13px !important;
            padding-top: 6px !important;
            padding-bottom: 6px !important;
            font-weight: 400 !important;
        }

        nav a.bg-indigo-600 {
            font-weight: 400 !important;
            margin-top: 10px !important;
            margin-bottom: 8px !important;
            /* Keep active state visible without heavier font */
        }

        /* Standardize hover and active states for better feel */
        button:active,
        a.bg-indigo-600:active {
            transform: scale(0.95) !important;
        }

        /* Remove ring shadows on focus but keep accessibility */
        button:focus,
        a:focus {
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important;
            outline: none !important;
        }

        /* Ensure consistent primary button colors */
        .bg-indigo-600 {
            background-color: #4f46e5 !important;
        }

        .bg-indigo-600:hover {
            background-color: #4338ca !important;
        }

        .ss-arrow {
            margin-left: 10px !important;
            transition: transform 0.3s ease !important;
        }

        .ss-main.ss-open-below .ss-arrow,
        .ss-main.ss-open-above .ss-arrow {
            transform: rotate(180deg) !important;
        }

        .ss-arrow path {
            stroke: #9ca3af !important;
            stroke-width: 2.5px !important;
        }

        .ss-main:focus-within .ss-arrow path,
        .ss-main.ss-open-below .ss-arrow path,
        .ss-main.ss-open-above .ss-arrow path {
            stroke: #6366f1 !important;
        }
    </style>
</head>
<!-- Laravel Blade Layout with Full Sidebar Functionality Integrated into Your Original Code -->

<body class="font-sans antialiased bg-gray-50 text-gray-900 overflow-hidden h-screen">

    <div class="flex h-screen overflow-hidden bg-gray-50" x-data="layout()" x-init="init()">

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" x-transition.opacity @click="closeSidebar()"
            class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

        <!-- Sidebar -->
        <aside :class="{
            '-translate-x-full': !sidebarOpen,
            'translate-x-0': sidebarOpen,
            'w-20': sidebarCollapsed,
            'w-64': !sidebarCollapsed,
            'transition-all duration-300': isInitiallyLoaded
        }" @touchstart="touchStart($event)" @touchmove="touchMove($event)"
            class="fixed inset-y-0 left-0 z-50 bg-gray-900 border-r border-gray-800 flex flex-col transform lg:translate-x-0 lg:static shadow-2xl">

            <!-- Logo -->
            <div class="h-20 flex items-center border-b border-gray-800 bg-gray-900/50 backdrop-blur-md sticky top-0 z-10 transition-all duration-300"
                :class="sidebarCollapsed ? 'justify-center px-2' : 'justify-between px-4'">
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 transition-transform hover:scale-105 active:scale-95 overflow-hidden">
                    <div class="w-12 h-12 rounded-xl bg-white p-1.5 flex items-center justify-center shadow-lg shadow-indigo-500/10 border border-gray-800 shrink-0">
                        <img src="{{ url('assets/images/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <div x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 -translate-x-2" x-transition:enter-end="opacity-100 translate-x-0" class="flex flex-col">
                        <span class="text-sm font-black text-white tracking-tight leading-none uppercase">{{ App\Models\SystemSetting::get('site_name', 'FeeEasy') }}</span>
                        <span class="text-[8px] font-bold text-indigo-400 uppercase tracking-[0.3em] mt-1">Management</span>
                    </div>
                </a>

                <!-- Desktop Collapse Button (Only visible when expanded) -->
                <button @click="toggleCollapse()" x-show="!sidebarCollapsed" class="hidden lg:block text-gray-500 hover:text-white transition-colors p-1 hover:bg-gray-800 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                </button>

                <!-- Small overlay button to expand when collapsed -->
                <button @click="toggleCollapse()" x-show="sidebarCollapsed" class="absolute -right-3 top-7 w-6 h-6 bg-indigo-600 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-indigo-700 transition lg:flex hidden border-2 border-gray-900 z-50">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7"></path></svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-4 lg:py-6 space-y-1 w-full overflow-y-auto custom-scrollbar">
                <!-- Core -->
                <a href="{{ route('dashboard') }}" @click="handleNavigation()"
                    class="flex items-center px-4 py-2.5 mb-4 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all duration-200 group text-[13px] font-medium">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Dashboard</span>
                </a>

                <!-- Institute Management -->
                <div x-show="!sidebarCollapsed"
                    class="px-4 mt-8 mb-2 text-[10px] font-black text-gray-500 uppercase tracking-widest opacity-70">
                    Institute Management
                </div>

                <a href="{{ route('institutes.index') }}" @click="handleNavigation()"
                    class="flex items-center px-4 py-2.5 {{ request()->routeIs('institutes.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all duration-200 group text-[13px] font-medium">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Institutes</span>
                </a>

                <a href="{{ route('subscriptions.index') }}" @click="handleNavigation()"
                    class="flex items-center px-4 py-2.5 {{ request()->routeIs('subscriptions.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all duration-200 group text-[13px] font-medium">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">All Subscriptions</span>
                </a>

                <!-- Finance Management -->
                <div x-show="!sidebarCollapsed"
                    class="px-4 mt-8 mb-2 text-[10px] font-black text-gray-500 uppercase tracking-widest opacity-70">
                    Finance Management
                </div>

                <a href="{{ route('revenue.index') }}" @click="handleNavigation()"
                    class="flex items-center px-4 py-2.5 {{ request()->routeIs('revenue.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all duration-200 group text-[13px] font-medium">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Revenue Analysis</span>
                </a>

                <a href="{{ route('plans.index') }}" @click="handleNavigation()"
                    class="flex items-center px-4 py-2.5 {{ request()->routeIs('plans.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all duration-200 group text-[13px] font-medium">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Manage Plans</span>
                </a>

                <!-- Marketing -->
                <div x-show="!sidebarCollapsed"
                    class="px-4 mt-8 mb-2 text-[10px] font-black text-gray-500 uppercase tracking-widest opacity-70">
                    Engagement
                </div>
                <!-- 
                <a href="{{ route('whatsapp.index') }}" @click="handleNavigation()"
                    class="flex items-center px-4 py-2.5 {{ request()->routeIs('whatsapp.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all duration-200 group text-[13px] font-medium">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">WhatsApp API</span>
                </a> -->

                <a href="{{ route('broadcast.index') }}" @click="handleNavigation()"
                    class="flex items-center px-4 py-2.5 {{ request()->routeIs('broadcast.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all duration-200 group text-[13px] font-medium">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Broadcast Center</span>
                </a>

                <!-- System -->
                <div x-show="!sidebarCollapsed"
                    class="px-4 mt-8 mb-2 text-[10px] font-black text-gray-500 uppercase tracking-widest opacity-70">
                    System Control
                </div>



                <a href="{{ route('settings.index') }}" @click="handleNavigation()"
                    class="flex items-center px-4 py-2.5 {{ request()->routeIs('settings.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all duration-200 group text-[13px] font-medium">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">System Settings</span>
                </a>

            </nav>

            <!-- User Section -->
            <div class="px-4 py-4 border-t border-gray-800 bg-gray-900/50">
                <div x-show="!sidebarCollapsed" class="mb-4 px-4">
                    <p class="text-xs text-gray-500 font-medium truncate">{{ Auth::user()->email }}</p>
                </div>

                <div class="space-y-1">
                    <a href="{{ route('profile.edit') }}" @click="handleNavigation()"
                        class="flex items-center px-4 py-2 text-[13px] font-medium {{ request()->routeIs('profile.edit') ? 'text-indigo-400 bg-indigo-500/10' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} rounded-lg transition-colors group relative overflow-hidden">
                        <svg class="w-4 h-4 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="whitespace-nowrap">My Profile</span>
                        @if(request()->routeIs('profile.edit'))
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-4 bg-indigo-500 rounded-r-full"></div>
                        @endif
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" @click="handleNavigation()"
                            class="w-full flex items-center px-4 py-2 text-[13px] font-medium text-red-400 hover:text-red-300 hover:bg-red-900/20 rounded-lg transition-colors group">
                            <svg class="w-4 h-4 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span x-show="!sidebarCollapsed">Logout</span>
                        </button>
                    </form>
                </div>
            </div>

        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden">

            <!-- Header -->
            <header class="h-16 flex items-center justify-between px-6 bg-white border-b border-gray-200 z-40">

                <div class="flex items-center gap-4">

                    <!-- Hamburger Button -->
                    <button @click="toggleSidebar()"
                        class="text-gray-500 hover:text-gray-700 focus:outline-none lg:hidden">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <h1 class="text-lg font-semibold text-gray-800">
                        {{ $title ?? 'FeeEasy Panel' }}
                    </h1>

                </div>

            </header>

            <!-- Main Scrollable Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50/50 relative custom-scrollbar">
                <div class="px-3 py-3 md:px-3 w-full max-w-7xl mx-auto">
                    <!-- Page Header Slot -->
                    @if (isset($header))
                        <div class="mb-3">
                            {{ $header }}
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>

        </div>

    </div>

    <script>

        function layout() {
            return {

                sidebarOpen: window.innerWidth >= 1024,
                sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
                isInitiallyLoaded: false,

                touchStartX: 0,
                touchEndX: 0,

                init() {
                    this.$nextTick(() => {
                        this.isInitiallyLoaded = true;
                    });

                    window.addEventListener('resize', () => {
                        if (window.innerWidth >= 1024) {
                            this.sidebarOpen = true
                        } else {
                            this.sidebarOpen = false
                        }
                    })
                },

                toggleSidebar() {
                    this.sidebarOpen = !this.sidebarOpen
                },

                closeSidebar() {
                    if (window.innerWidth < 1024) {
                        this.sidebarOpen = false
                    }
                },

                toggleCollapse() {
                    this.sidebarCollapsed = !this.sidebarCollapsed

                    localStorage.setItem(
                        'sidebarCollapsed',
                        this.sidebarCollapsed
                    )
                },

                handleNavigation() {
                    if (window.innerWidth < 1024) {
                        this.sidebarOpen = false
                    }
                },

                touchStart(event) {
                    this.touchStartX = event.changedTouches[0].screenX
                },

                touchMove(event) {
                    this.touchEndX = event.changedTouches[0].screenX

                    if (this.touchStartX - this.touchEndX > 70) {
                        this.closeSidebar()
                    }

                    if (this.touchEndX - this.touchStartX > 70) {
                        this.sidebarOpen = true
                    }
                }

            }
        }

        // Global Button Loader Logic
        document.addEventListener('submit', function (e) {
            // Find the button that triggered the submit
            const button = e.submitter || e.target.querySelector('button[type="submit"]') || e.target.querySelector('button');

            if (button && !button.classList.contains('no-loader')) {
                // Prevent multiple clicks
                button.classList.add('btn-loading');

                // Create spinner element
                const spinnerContainer = document.createElement('div');
                spinnerContainer.className = 'btn-spinner';
                spinnerContainer.innerHTML = '<div class="spinner-loader"></div>';

                // Add to button
                button.appendChild(spinnerContainer);
            }
        });

    </script>

</html>