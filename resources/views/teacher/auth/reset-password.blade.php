<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - Tuoora Teacher Portal</title>
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
            <h1 class="text-xl font-black text-slate-900">Enter Reset Code</h1>
            <p class="text-sm text-slate-500 mt-1">Check your email for the 6-digit code</p>
        </div>

        <div id="alert-box" class="hidden mb-5 p-3.5 rounded-xl text-sm font-semibold"></div>

        <form id="reset-form" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Email Address</label>
                <input type="email" name="email" required value="{{ request('email') }}" class="w-full h-12 px-4 rounded-xl border-2 border-slate-100 focus:border-primary outline-none text-sm font-medium">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">6-Digit Code</label>
                <input type="text" name="otp" required maxlength="6" class="w-full h-12 px-4 rounded-xl border-2 border-slate-100 focus:border-primary outline-none text-sm font-medium tracking-[0.3em]">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">New Password</label>
                <input type="password" name="password" required class="w-full h-12 px-4 rounded-xl border-2 border-slate-100 focus:border-primary outline-none text-sm font-medium">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Confirm Password</label>
                <input type="password" name="password_confirmation" required class="w-full h-12 px-4 rounded-xl border-2 border-slate-100 focus:border-primary outline-none text-sm font-medium">
            </div>
            <button type="submit" class="w-full h-12 bg-primary hover:bg-orange-700 text-white rounded-xl text-sm font-black uppercase tracking-wide transition-all">Reset Password</button>
        </form>
    </div>

    <script>
        document.getElementById('reset-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            const alertBox = document.getElementById('alert-box');
            try {
                const res = await fetch('{{ route('teacher.password.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        email: form.email.value,
                        otp: form.otp.value,
                        password: form.password.value,
                        password_confirmation: form.password_confirmation.value,
                    }),
                });
                const data = await res.json();
                alertBox.classList.remove('hidden', 'bg-rose-50', 'text-rose-700', 'bg-emerald-50', 'text-emerald-700');
                if (data.status === 'success') {
                    alertBox.classList.add('bg-emerald-50', 'text-emerald-700');
                    alertBox.innerText = data.message;
                    setTimeout(() => window.location.href = '{{ route('teacher.login') }}', 1200);
                } else {
                    alertBox.classList.add('bg-rose-50', 'text-rose-700');
                    alertBox.innerText = data.message || (data.errors ? Object.values(data.errors)[0][0] : 'Something went wrong.');
                }
            } catch (err) {
                alertBox.classList.remove('hidden');
                alertBox.classList.add('bg-rose-50', 'text-rose-700');
                alertBox.innerText = err.message || 'Something went wrong.';
            }
        });
    </script>
</body>

</html>
