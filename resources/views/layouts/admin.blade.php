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

    <style>
        /* Custom Scrollbar Styling */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #111827; /* gray-900 */
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #374151; /* gray-700 */
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #6366f1; /* indigo-500 */
        }
        /* For Firefox */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #374151 #111827;
        }
    </style>
</head>
<!-- Laravel Blade Layout with Full Sidebar Functionality Integrated into Your Original Code -->

<body class="font-sans antialiased bg-gray-50 text-gray-900">

    <div class="flex h-screen overflow-hidden bg-gray-50" x-data="layout()" x-init="init()">

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" x-transition.opacity @click="closeSidebar()"
            class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

        <!-- Sidebar -->
        <aside :class="{
            '-translate-x-full': !sidebarOpen,
            'translate-x-0': sidebarOpen,
            'w-20': sidebarCollapsed,
            'w-64': !sidebarCollapsed
        }" @touchstart="touchStart($event)" @touchmove="touchMove($event)"
            class="fixed inset-y-0 left-0 z-50 bg-gray-900 border-r border-gray-800 flex flex-col transform transition-all duration-300 ease-in-out lg:translate-x-0 lg:static shadow-2xl">

            <!-- Logo -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-gray-800">
                <span x-show="!sidebarCollapsed"
                    class="text-xl font-bold text-white tracking-wider flex items-center gap-2">
                    ⚡ FeeEasy
                </span>

                <!-- Desktop Collapse Button -->
                <button @click="toggleCollapse()" class="hidden lg:block text-gray-400 hover:text-white">
                    ☰
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto w-full custom-scrollbar">

                <!-- Core -->
                <!-- <div x-show="!sidebarCollapsed" class="px-4 mt-4 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                    Core Management
                </div> -->
                
                <a href="{{ route('dashboard') }}" @click="handleNavigation()"
                    class="flex items-center px-4 py-2.5 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Dashboard</span>
                </a>

                <a href="{{ route('institutes.index') }}" @click="handleNavigation()"
                    class="flex items-center px-4 py-2.5 {{ request()->routeIs('institutes.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Institutes</span>
                </a>
                
                <a href="{{ route('revenue.index') }}" @click="handleNavigation()"
                    class="flex items-center px-4 py-2.5 {{ request()->routeIs('revenue.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Revenue Analysis</span>
                </a>

                <!-- Subscription -->
                <!-- <div x-show="!sidebarCollapsed" class="px-4 mt-6 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                    Subscription Center
                </div> -->

                <a href="{{ route('plans.index') }}" @click="handleNavigation()"
                    class="flex items-center px-4 py-2.5 {{ request()->routeIs('plans.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Manage Plans</span>
                </a>

                <a href="{{ route('subscriptions.index') }}" @click="handleNavigation()"
                    class="flex items-center px-4 py-2.5 {{ request()->routeIs('subscriptions.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">All Subscriptions</span>
                </a>

                <!-- Marketing -->
                <!-- <div x-show="!sidebarCollapsed" class="px-4 mt-6 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                    Engagement
                </div> -->

                <a href="{{ route('whatsapp.index') }}" @click="handleNavigation()"
                    class="flex items-center px-4 py-2.5 {{ request()->routeIs('whatsapp.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">WhatsApp API</span>
                </a>

                <a href="{{ route('broadcast.index') }}" @click="handleNavigation()"
                    class="flex items-center px-4 py-2.5 {{ request()->routeIs('broadcast.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Broadcast Center</span>
                </a>

                <!-- System -->
                <!-- <div x-show="!sidebarCollapsed" class="px-4 mt-6 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                    System Control
                </div> -->

                <a href="{{ route('activity.index') }}" @click="handleNavigation()"
                    class="flex items-center px-4 py-2.5 {{ request()->routeIs('activity.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Activity Monitoring</span>
                </a>

                <a href="{{ route('settings.index') }}" @click="handleNavigation()"
                    class="flex items-center px-4 py-2.5 {{ request()->routeIs('settings.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
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
                    <!-- <a href="{{ route('profile.edit') }}" @click="handleNavigation()"
                        class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-colors group">
                        <svg class="w-4 h-4 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span x-show="!sidebarCollapsed">My Profile</span>
                    </a> -->

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" @click="handleNavigation()"
                            class="w-full flex items-center px-4 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-red-900/20 rounded-lg transition-colors group">
                            <svg class="w-4 h-4 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
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
                <div class="px-6 py-8 md:px-8 w-full max-w-7xl mx-auto">
                    <!-- Page Header Slot -->
                    @if (isset($header))
                        <div class="mb-8">
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

                sidebarOpen: false,
                sidebarCollapsed: false,

                touchStartX: 0,
                touchEndX: 0,

                init() {

                    if (window.innerWidth >= 1024) {
                        this.sidebarOpen = true
                    }

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

    </script>

</html>