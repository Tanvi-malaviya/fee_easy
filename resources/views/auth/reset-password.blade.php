<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Tuoora</title>
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
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .input-field:focus+.input-icon {
            color: #FF6B00;
        }

        .password-toggle {
            position: absolute;
            right: 1.15rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .password-toggle:hover {
            color: #FF6B00;
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
            letter-spacing: 0.1em;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(255, 107, 0, 0.15);
            margin-top: 0.5rem;
        }

        .submit-btn:hover {
            background: #e66000;
            transform: translateY(-2px);
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
            'brandSubtext' => 'Set a strong new password for your admin account to keep the central dashboard secure.',
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
                    <h1>Set New Password</h1>
                    <p>Enter your email and choose a new password for your administrator account.</p>
                </div>

                @if ($errors->any())
                    <div class="error-box">
                        @foreach ($errors->all() as $error)
                            <p><i class="fas fa-circle-exclamation"></i> {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Address -->
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <div class="input-wrapper">
                            <input type="email" name="email" id="email" required value="{{ old('email', $request->email) }}"
                                class="input-field" placeholder="admin@example.com" autofocus autocomplete="username">
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <div class="input-wrapper">
                            <input type="password" name="password" id="password" required class="input-field"
                                placeholder="••••••••" style="padding-right: 3rem;" autocomplete="new-password">
                            <i class="fas fa-lock input-icon"></i>
                            <i class="fas fa-eye password-toggle" onclick="togglePwd('password', this)"></i>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <div class="input-wrapper">
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="input-field" placeholder="••••••••" style="padding-right: 3rem;" autocomplete="new-password">
                            <i class="fas fa-check-double input-icon"></i>
                            <i class="fas fa-eye password-toggle" onclick="togglePwd('password_confirmation', this)"></i>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePwd(id, el) {
            const input = document.getElementById(id);
            if (!input) return;
            const showing = input.type === 'text';
            input.type = showing ? 'password' : 'text';
            el.classList.toggle('fa-eye', showing);
            el.classList.toggle('fa-eye-slash', !showing);
        }
    </script>
</body>

</html>
