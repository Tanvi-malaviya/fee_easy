<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Teacher Portal') - Tuoora</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'sans-serif'] },
                    colors: {
                        primary: '#FF6B00',
                        primaryHover: '#E55A00',
                        secondary: '#00A8B5',
                        tertiary: '#2ECC71',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #F8F9FA; }
        .nav-link.active { color: #FF6B00; background: rgba(255,107,0,0.08); }
    </style>
</head>

<body class="text-slate-900 antialiased">

    <header class="fixed top-0 left-0 right-0 h-16 bg-white border-b border-slate-100 z-[100] shadow-sm">
        <div class="max-w-7xl mx-auto h-full px-4 md:px-6 flex items-center justify-between">
            <a href="{{ route('teacher.dashboard') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/infinity logo transparent.png') }}" alt="Tuoora" class="h-8 w-auto" onerror="this.style.display='none'">
                <span class="font-black text-slate-800 tracking-tight hidden sm:inline">Teacher Portal</span>
            </a>

            @auth('teacher')
                <nav class="hidden md:flex items-center gap-1">
                    <a href="{{ route('teacher.dashboard') }}" class="nav-link px-3 py-2 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 {{ request()->routeIs('teacher.dashboard') || request()->routeIs('teacher.batches.*') ? 'active' : '' }}">My Batches</a>
                    <a href="{{ route('teacher.salary.index') }}" class="nav-link px-3 py-2 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 {{ request()->routeIs('teacher.salary.*') ? 'active' : '' }}">Salary Slips</a>
                    <a href="{{ route('teacher.profile.index') }}" class="nav-link px-3 py-2 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 {{ request()->routeIs('teacher.profile.*') ? 'active' : '' }}">Profile</a>
                </nav>

                <div class="flex items-center gap-3">
                    <span class="hidden lg:inline text-xs font-semibold text-slate-500">{{ Auth::guard('teacher')->user()->full_name }}</span>
                    <a href="{{ route('teacher.profile.index') }}" class="h-9 w-9 rounded-full bg-slate-100 overflow-hidden border border-slate-100 shrink-0">
                        <img src="{{ Auth::guard('teacher')->user()->profile_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::guard('teacher')->user()->full_name) . '&background=F1F5F9&color=64748B&bold=true' }}" class="h-full w-full object-cover">
                    </a>
                    <form action="{{ route('teacher.logout') }}" method="POST">
                        @csrf
                        <button class="h-9 w-9 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </header>

    <main class="mt-16 px-4 md:px-6 pt-6 pb-10 max-w-7xl mx-auto w-full">
        @if (session('success'))
            <div class="mb-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-semibold">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-3.5 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 text-sm font-semibold">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>

    <div id="toast-container" class="fixed top-6 right-6 z-[200] space-y-3 pointer-events-none"></div>

    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-tertiary' : 'bg-rose-600';
            toast.className = `${bgColor} text-white px-5 py-3 rounded-xl shadow-2xl text-xs font-bold pointer-events-auto cursor-pointer`;
            toast.innerText = message;
            toast.onclick = () => toast.remove();
            container.appendChild(toast);
            setTimeout(() => toast.remove(), 3500);
        }

        function apiFetch(url, options = {}) {
            options.headers = Object.assign({
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }, options.headers || {});
            if (options.body && !(options.body instanceof FormData)) {
                options.headers['Content-Type'] = 'application/json';
            }
            options.credentials = 'same-origin';
            return fetch(url, options).then(async (res) => {
                const data = await res.json().catch(() => ({}));
                if (!res.ok) throw data;
                return data;
            });
        }
    </script>
    @stack('scripts')
</body>

</html>
