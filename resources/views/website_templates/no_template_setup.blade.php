<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Setup Required - {{ $institute->institute_name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a;
        }
        .font-outfit {
            font-family: 'Outfit', sans-serif;
        }
        .glow-orb {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.12) 0%, rgba(99, 102, 241, 0) 70%);
            filter: blur(40px);
            z-index: 0;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center relative overflow-hidden px-4">
    <!-- Background Glows -->
    <div class="glow-orb -top-20 -left-20"></div>
    <div class="glow-orb -bottom-20 -right-20"></div>

    <div class="max-w-md w-full text-center relative z-10 space-y-8 bg-slate-900/60 backdrop-blur-md border border-slate-800 p-8 rounded-3xl shadow-2xl">
        <div class="space-y-4">
            <!-- Icon -->
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-3xl animate-bounce">
                🌐
            </div>
            
            <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight font-outfit">
                Website Setup Required
            </h1>
            
            <p class="text-sm text-slate-400 leading-relaxed">
                Welcome to <strong>{{ $institute->institute_name }}</strong>. The administrator has not yet configured or published this website template.
            </p>
        </div>

        <div class="p-5 bg-slate-800/40 rounded-2xl border border-slate-800/60 text-left space-y-2">
            <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider block">Are you the Administrator?</span>
            <p class="text-xs text-slate-400 leading-relaxed">
                Please log in to your Institute Admin Panel, navigate to <strong>Website Settings</strong>, select a layout template, and publish the website.
            </p>
        </div>

        <div>
            <a href="{{ route('institute.profile.website.index') }}" class="inline-flex items-center justify-center w-full px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm transition-all active:scale-95 shadow-lg shadow-indigo-600/20">
                Go to Admin Panel &rarr;
            </a>
        </div>
    </div>
</body>
</html>
