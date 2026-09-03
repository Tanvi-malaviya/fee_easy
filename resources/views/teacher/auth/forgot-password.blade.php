<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password - Tuoora Teacher Portal</title>
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
            <h1 class="text-xl font-black text-slate-900">Reset Your Password</h1>
            <p class="text-sm text-slate-500 mt-1">We'll email you a 6-digit code</p>
        </div>

        <div id="alert-box" class="hidden mb-5 p-3.5 rounded-xl text-sm font-semibold"></div>

        <form id="forgot-form" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Email Address</label>
                <input type="email" name="email" required class="w-full h-12 px-4 rounded-xl border-2 border-slate-100 focus:border-primary outline-none text-sm font-medium" placeholder="you@institute.com">
            </div>
            <button type="submit" class="w-full h-12 bg-primary hover:bg-orange-700 text-white rounded-xl text-sm font-black uppercase tracking-wide transition-all">Send Code</button>
        </form>

        <p class="text-center mt-6 text-xs font-bold text-slate-400">
            <a href="{{ route('teacher.login') }}" class="text-primary">Back to Login</a>
        </p>
    </div>

    <script>
        document.getElementById('forgot-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = e.target.email.value;
            const alertBox = document.getElementById('alert-box');
            try {
                const res = await fetch('{{ route('teacher.password.email') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ email }),
                });
                const data = await res.json();
                alertBox.classList.remove('hidden', 'bg-rose-50', 'text-rose-700', 'bg-emerald-50', 'text-emerald-700');
                if (data.status === 'success') {
                    alertBox.classList.add('bg-emerald-50', 'text-emerald-700');
                    alertBox.innerText = data.message;
                    setTimeout(() => window.location.href = '{{ route('teacher.password.reset') }}?email=' + encodeURIComponent(email), 1200);
                } else {
                    alertBox.classList.add('bg-rose-50', 'text-rose-700');
                    alertBox.innerText = data.message || 'Something went wrong.';
                }
            } catch (err) {
                alertBox.classList.remove('hidden');
                alertBox.classList.add('bg-rose-50', 'text-rose-700');
                alertBox.innerText = 'Something went wrong.';
            }
        });
    </script>
</body>

</html>
