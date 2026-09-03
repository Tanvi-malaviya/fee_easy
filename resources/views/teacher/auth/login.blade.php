<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teacher Login - Tuoora</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Outfit', 'sans-serif'] }, colors: { primary: '#FF6B00' } } } }
    </script>
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>

<body class="min-h-screen bg-slate-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-100 p-8">
        <div class="text-center mb-8">
            <img src="{{ asset('images/infinity logo transparent.png') }}" class="h-10 mx-auto mb-4" onerror="this.style.display='none'">
            <h1 class="text-xl font-black text-slate-900">Teacher Portal</h1>
            <p class="text-sm text-slate-500 mt-1">Sign in to manage your batches</p>
        </div>

        @if ($errors->any())
            <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 text-sm font-semibold">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('teacher.login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Email Address</label>
                <input type="email" name="email" required value="{{ old('email') }}" class="w-full h-12 px-4 rounded-xl border-2 border-slate-100 focus:border-primary outline-none text-sm font-medium" placeholder="you@institute.com">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Password</label>
                <input type="password" name="password" required class="w-full h-12 px-4 rounded-xl border-2 border-slate-100 focus:border-primary outline-none text-sm font-medium" placeholder="••••••••">
            </div>
            <div class="text-right">
                <a href="{{ route('teacher.password.request') }}" class="text-xs font-bold text-primary">Forgot Password?</a>
            </div>
            <button type="submit" class="w-full h-12 bg-primary hover:bg-orange-700 text-white rounded-xl text-sm font-black uppercase tracking-wide transition-all">Log In</button>
        </form>
    </div>
</body>

</html>
