<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Tuoora</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('institute.auth.partials.brand-styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #ffffff;
            min-height: 100vh;
        }

        .form-group {
            text-align: left;
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.4rem;
            padding-left: 0.25rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-field {
            width: 100%;
            height: 3.1rem;
            padding: 0 1.5rem 0 3.25rem;
            background: #fcfdfe;
            border: 2px solid #f1f5f9;
            border-radius: 0.85rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: #1e293b;
            transition: all 0.3s ease;
            outline: none;
        }

        .input-field:focus {
            border-color: #FF6B00;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(255, 107, 0, 0.05);
        }

        .input-icon {
            position: absolute;
            left: 1.15rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.95rem;
        }

        .submit-btn {
            width: 100%;
            height: 3.1rem;
            background: #FF6B00;
            color: white;
            border: none;
            border-radius: 0.85rem;
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(255, 107, 0, 0.15);
            margin-top: 0.25rem;
        }

        .submit-btn:hover {
            background: #e66000;
            transform: translateY(-2px);
        }

        .back-to-login {
            margin-top: 1.5rem;
            display: inline-block;
            font-size: 0.78rem;
            font-weight: 700;
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .back-to-login:hover {
            color: #FF6B00;
        }

        .error-box {
            background: #fff1f2;
            border-radius: 0.85rem;
            padding: 0.9rem 1rem;
            margin-bottom: 1.25rem;
            text-align: left;
        }

        .error-box p {
            font-size: 0.8rem;
            color: #e11d48;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="auth-shell">
        @include('institute.auth.partials.brand-panel', [
            'brandHeadline' => 'Tuoora Admin Portal',
            'brandSubtext' => 'No worries — it happens. Enter your administrator email address and we will send you a secure link to reset your password.',
            'modules' => [
                ['fa-university', 'Institute Management'],
                ['fa-file-invoice-dollar', 'Revenue & Payments'],
                ['fa-tags', 'Subscription Plans'],
                ['fa-bullhorn', 'System Broadcasts'],
                ['fa-comments', 'WhatsApp Settings'],
                ['fa-gears', 'System Settings'],
            ],
            'brandFooterTagline' => 'A bridge of knowledge for all'
        ])

        <div class="auth-form-side">
            <div class="auth-form-inner">
                <div class="form-head">
                    <h1>Forgot Password</h1>
                    <p>Enter your administrator email address and we'll email you a password reset link.</p>
                </div>

                @if (session('status'))
                    <div class="mb-4 font-bold text-xs text-emerald-600 bg-emerald-50 border border-emerald-100/80 px-4 py-3 rounded-xl flex items-center gap-2.5 shadow-sm">
                        <svg class="w-4 h-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="error-box">
                        @foreach ($errors->all() as $error)
                            <p><i class="fas fa-circle-exclamation"></i> {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <div class="input-wrapper">
                            <input type="email" name="email" id="email" required value="{{ old('email') }}"
                                class="input-field" placeholder="admin@example.com" autofocus autocomplete="username">
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">
                        Send Password Reset Link
                    </button>
                </form>

                <a href="{{ route('login') }}" class="back-to-login">
                    <i class="fas fa-arrow-left"></i> Back to Login
                </a>
            </div>
        </div>
    </div>
</body>

</html>
