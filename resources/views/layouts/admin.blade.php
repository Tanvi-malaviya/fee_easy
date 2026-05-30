
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tuoora Admin Panel</title>
    <link rel="icon" type="image/png" href="{{ asset('images/turooa.png') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine JS -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- SlimSelect -->
    <link href="https://unpkg.com/slim-select@2.8.2/dist/slimselect.css" rel="stylesheet" />
    <script src="https://unpkg.com/slim-select@2.8.2/dist/slimselect.min.js"></script>

    <!-- PREVENT SIDEBAR JUMP: Script executes before body renders -->
    <script>
        (function() {
            const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (collapsed) {
                document.documentElement.classList.add('sidebar-is-collapsed');
            }
        })();
    </script>

    <style>
        [x-cloak] { display: none !important; }

        /* Prevent Text Jumping: Hide labels immediately if collapsed class exists on root */
        .sidebar-is-collapsed .sidebar-text {
            display: none !important;
        }
        
        .sidebar-is-collapsed aside {
            width: 5rem !important; /* w-20 */
        }

        /* Custom Scrollbar Styling */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #ffffff;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #f3f4f6;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #FF6B00;
        }

        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #f3f4f6 #ffffff;
        }

        /* SlimSelect Styles */
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

        .ss-main:focus-within {
            border-color: #FF6B00 !important;
            box-shadow: 0 0 0 4px rgba(255, 107, 0, 0.1) !important;
            background-color: #ffffff !important;
        }

        .ss-content {
            border-radius: 0.75rem !important;
            margin-top: 8px !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            z-index: 10000 !important;
            background-color: #ffffff !important;
        }

        .ss-option:hover {
            background-color: #fff7ed !important;
            color: #FF6B00 !important;
        }

        /* Global Button Standardization */
        button:not(.no-loader),
        .btn,
        a.bg-orange-600,
        a.bg-white,
        a.bg-emerald-600,
        a.bg-red-600 {
            border-radius: 0.75rem !important;
            text-transform: uppercase !important;
            font-weight: 600 !important;
            letter-spacing: 0.05em !important;
            font-size: 13px !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        nav a {
            font-size: 13px !important;
            padding-top: 6px !important;
            padding-bottom: 6px !important;
            font-weight: 400 !important;
        }

        .bg-orange-600 { background-color: #FF6B00 !important; }

        /* Source of Truth: Primary Color */
        :root {
            --primary-color: #ff6600; /* Your common indigo color */
            --primary-light: rgba(255, 102, 0, 0.1);
        }

        .bg-primary { background-color: var(--primary-color) !important; }
        .text-primary { color: var(--primary-color) !important; }
        .border-primary { border-color: var(--primary-color) !important; }
        .focus\:ring-primary:focus { --tw-ring-color: var(--primary-color) !important; }

        /* Global Input Focus Style */
        input:focus, select:focus, textarea:focus, button:focus, button:active {
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 4px var(--primary-light) !important;
            outline: none !important;
        }

        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
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
    </style>
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-900 overflow-hidden h-screen">

    <!-- Floating Sleek Toast Notification System -->
    <div x-data="{
            toasts: [],
            addToast(message, type = 'success') {
                const id = Date.now();
                this.toasts.push({ id, message, type });
                setTimeout(() => this.removeToast(id), 5000);
            },
            removeToast(id) {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }
        }"
        x-init="
            @if(session('success')) addToast('{{ session('success') }}', 'success'); @endif
            @if(session('error')) addToast('{{ session('error') }}', 'error'); @endif
            @if($errors->any()) addToast('{{ $errors->first() }}', 'error'); @endif
        "
        class="fixed top-5 right-5 z-[9999] flex flex-col gap-3 max-w-md w-full pointer-events-none"
    >
        <template x-for="toast in toasts" :key="toast.id">
            <div x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="pointer-events-auto flex items-center p-4 rounded-2xl shadow-xl border bg-white/90 backdrop-blur-md"
                 :class="{
                     'border-emerald-100 text-emerald-800 shadow-emerald-500/5': toast.type === 'success',
                     'border-rose-100 text-rose-800 shadow-rose-500/5': toast.type === 'error'
                 }"
            >
                <!-- Icon -->
                <div class="mr-3 p-2 rounded-xl"
                     :class="{
                         'bg-emerald-50 text-emerald-600': toast.type === 'success',
                         'bg-rose-50 text-rose-600': toast.type === 'error'
                     }"
                >
                    <template x-if="toast.type === 'success'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </template>
                </div>
                <!-- Content -->
                <div class="flex-1 mr-2">
                    <p class="text-[9px] font-black uppercase tracking-wider text-slate-400" x-text="toast.type === 'success' ? 'Success' : 'Attention'"></p>
                    <p class="text-xs font-semibold mt-0.5 text-slate-700" x-text="toast.message"></p>
                </div>
                <!-- Dismiss button -->
                <button @click="removeToast(toast.id)" class="text-slate-400 hover:text-slate-600 transition shrink-0 p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </template>
    </div>

    <div class="flex h-screen overflow-hidden bg-gray-50" x-data="layout()" x-init="init()" x-cloak>

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" x-transition.opacity @click="closeSidebar()"
            class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

        <!-- Sidebar -->
        <aside id="main-sidebar" :class="{
            '-translate-x-full': !sidebarOpen,
            'translate-x-0': sidebarOpen,
            'w-20': sidebarCollapsed,
            'w-64': !sidebarCollapsed,
            'transition-all duration-300': isInitiallyLoaded
        }" 
        @touchstart="touchStart($event)" 
        @touchmove="touchMove($event)"
        class="fixed inset-y-0 left-0 z-50 bg-white border-r border-gray-100 flex flex-col transform lg:translate-x-0 lg:static shadow-sm">

            <!-- Logo -->
            <div class="h-16 flex items-center border-b border-gray-50 bg-white sticky top-0 z-10 px-6">
                <a href="{{ route('dashboard') }}" class="flex items-center shrink-0">
                    <img src="{{ asset('images/2.png') }}" alt="Logo" class="h-8 w-auto object-contain">
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-4 space-y-1 w-full overflow-y-auto custom-scrollbar">
                
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 mb-4 {{ request()->routeIs('dashboard') ? 'bg-orange-50 text-orange-600' : 'text-gray-500 hover:bg-gray-50 hover:text-orange-600' }} rounded-lg transition-all group">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="sidebar-text text-[13px] font-medium whitespace-nowrap">Dashboard</span>
                </a>

                <div x-show="!sidebarCollapsed" class="sidebar-text px-4 mt-8 mb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    Institute Management
                </div>

                <a href="{{ route('institutes.index') }}" class="flex items-center px-4 py-2.5 {{ request()->routeIs('institutes.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-500 hover:bg-gray-50 hover:text-orange-600' }} rounded-lg transition-all group">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="sidebar-text text-[13px] font-medium whitespace-nowrap">Institutes</span>
                </a>

                <a href="{{ route('subscriptions.index') }}" class="flex items-center px-4 py-2.5 {{ request()->routeIs('subscriptions.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-500 hover:bg-gray-50 hover:text-orange-600' }} rounded-lg transition-all group">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="sidebar-text text-[13px] font-medium whitespace-nowrap">All Subscriptions</span>
                </a>

                <a href="{{ route('departments.index') }}" class="flex items-center px-4 py-2.5 {{ request()->routeIs('departments.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-500 hover:bg-gray-50 hover:text-orange-600' }} rounded-lg transition-all group">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="sidebar-text text-[13px] font-medium whitespace-nowrap">Departments</span>
                </a>

                <div x-show="!sidebarCollapsed" class="sidebar-text px-4 mt-8 mb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    Finance Management
                </div>

                <a href="{{ route('revenue.index') }}" class="flex items-center px-4 py-2.5 {{ request()->routeIs('revenue.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-500 hover:bg-gray-50 hover:text-orange-600' }} rounded-lg transition-all group">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="sidebar-text text-[13px] font-medium whitespace-nowrap">Revenue Analysis</span>
                </a>

                <a href="{{ route('plans.index') }}" class="flex items-center px-4 py-2.5 {{ request()->routeIs('plans.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-500 hover:bg-gray-50 hover:text-orange-600' }} rounded-lg transition-all group">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="sidebar-text text-[13px] font-medium whitespace-nowrap">Manage Plans</span>
                </a>

                <div x-show="!sidebarCollapsed" class="sidebar-text px-4 mt-8 mb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    Engagement
                </div>

                <a href="{{ route('broadcast.index') }}" class="flex items-center px-4 py-2.5 {{ request()->routeIs('broadcast.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-500 hover:bg-gray-50 hover:text-orange-600' }} rounded-lg transition-all group">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="sidebar-text text-[13px] font-medium whitespace-nowrap">Broadcast Center</span>
                </a>

                <div x-show="!sidebarCollapsed" class="sidebar-text px-4 mt-8 mb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    System Control
                </div>

                <a href="{{ route('settings.index') }}" class="flex items-center px-4 py-2.5 {{ request()->routeIs('settings.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-500 hover:bg-gray-50 hover:text-orange-600' }} rounded-lg transition-all group">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="sidebar-text text-[13px] font-medium whitespace-nowrap">System Settings</span>
                </a>
            </nav>

            <!-- User Section -->
            <div class="mt-auto px-4 py-4 border-t border-gray-100 bg-white sticky bottom-0">
                <div x-show="!sidebarCollapsed" class="sidebar-text mb-3 px-4">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest truncate">{{ Auth::user()->email }}</p>
                </div>
                <div class="space-y-1">
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-[13px] font-medium {{ request()->routeIs('profile.edit') ? 'text-orange-600 bg-orange-50' : 'text-gray-500 hover:text-orange-600 hover:bg-gray-50' }} rounded-lg transition-colors group">
                        <svg class="w-4 h-4 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        <span x-show="!sidebarCollapsed" class="sidebar-text whitespace-nowrap">My Profile</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-2 text-[13px] font-bold text-rose-500 hover:bg-rose-50 rounded-lg group uppercase tracking-widest no-loader transition-all">
                            <svg class="w-4 h-4 flex-shrink-0" :class="{'mr-3': !sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            <span x-show="!sidebarCollapsed" class="sidebar-text">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            <header class="h-16 flex items-center justify-between px-6 bg-white border-b border-gray-200 z-40">
                <div class="flex items-center gap-4">
                    <button @click="toggleSidebar()" class="text-gray-500 hover:text-gray-700 lg:hidden">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <h1 class="text-lg font-bold text-primary uppercase tracking-tight">{{ $title ?? 'FeeEasy Panel' }}</h1>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50/50 custom-scrollbar">
                <div class="px-3 py-3 w-full max-w-7xl mx-auto">
                    @if (isset($header)) <div class="mb-3">{{ $header }}</div> @endif
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
                    // Slight delay to enable transitions after initial render
                    setTimeout(() => {
                        this.isInitiallyLoaded = true;
                    }, 100);

                    window.addEventListener('resize', () => {
                        this.sidebarOpen = window.innerWidth >= 1024;
                    });
                },

                toggleCollapse() {
                    this.sidebarCollapsed = !this.sidebarCollapsed;
                    localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
                    
                    // Keep the HTML class in sync for CSS selectors
                    if (this.sidebarCollapsed) {
                        document.documentElement.classList.add('sidebar-is-collapsed');
                    } else {
                        document.documentElement.classList.remove('sidebar-is-collapsed');
                    }
                },

                toggleSidebar() { this.sidebarOpen = !this.sidebarOpen },
                closeSidebar() { if (window.innerWidth < 1024) this.sidebarOpen = false },
                touchStart(e) { this.touchStartX = e.changedTouches[0].screenX },
                touchMove(e) {
                    this.touchEndX = e.changedTouches[0].screenX;
                    if (this.touchStartX - this.touchEndX > 70) this.closeSidebar();
                    if (this.touchEndX - this.touchStartX > 70) this.sidebarOpen = true;
                }
            }
        }

        document.addEventListener('submit', function (e) {
            const button = e.submitter || e.target.querySelector('button[type="submit"]');
            if (button && !button.classList.contains('no-loader')) {
                button.classList.add('btn-loading');
                const spinnerContainer = document.createElement('div');
                spinnerContainer.className = 'btn-spinner';
                spinnerContainer.innerHTML = '<div class="spinner-loader"></div>';
                button.appendChild(spinnerContainer);
            }
        });
    </script>
</body>
</html>

