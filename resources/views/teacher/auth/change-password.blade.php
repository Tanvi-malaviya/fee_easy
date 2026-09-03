<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change Password - Tuoora Teacher Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Outfit', 'sans-serif'] }, colors: { primary: '#FF6B00' } } } }
    </script>
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>

<body class="min-h-screen bg-slate-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-100 p-8">
        @php($teacher = Auth::guard('teacher')->user())
        <div class="text-center mb-8">
            <div class="h-12 w-12 mx-auto mb-4 rounded-full bg-orange-50 flex items-center justify-center text-primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
            </div>
            <h1 class="text-xl font-black text-slate-900">
                {{ $teacher->must_change_password ? 'Set a New Password' : 'Change Password' }}
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                @if($teacher->must_change_password)
                    For security, please set a new password before continuing.
                @else
                    Update the password on your account.
                @endif
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 text-sm font-semibold">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('teacher.password.change.update') }}" class="space-y-4">
            @csrf
            @unless($teacher->must_change_password)
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Current Password</label>
                    <input type="password" name="current_password" required class="w-full h-12 px-4 rounded-xl border-2 border-slate-100 focus:border-primary outline-none text-sm font-medium">
                </div>
            @endunless
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">New Password</label>
                <input type="password" name="password" required class="w-full h-12 px-4 rounded-xl border-2 border-slate-100 focus:border-primary outline-none text-sm font-medium">
                <p class="text-[11px] text-slate-400 mt-1">8-15 chars, with uppercase, lowercase, number & symbol.</p>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Confirm New Password</label>
                <input type="password" name="password_confirmation" required class="w-full h-12 px-4 rounded-xl border-2 border-slate-100 focus:border-primary outline-none text-sm font-medium">
            </div>
            <button type="submit" class="w-full h-12 bg-primary hover:bg-orange-700 text-white rounded-xl text-sm font-black uppercase tracking-wide transition-all">Update Password</button>
        </form>
    </div>
</body>

</html>
