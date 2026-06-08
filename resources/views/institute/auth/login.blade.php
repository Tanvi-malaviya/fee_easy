<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Institute Login - Tuoora</title>
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

        .options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 0.25rem;
            margin-bottom: 1.25rem;
            padding: 0 0.25rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .remember-me input {
            width: 1rem;
            height: 1rem;
            accent-color: #FF6B00;
            cursor: pointer;
        }

        .remember-me span {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
        }

        .forgot-link {
            font-size: 0.75rem;
            font-weight: 700;
            color: #FF6B00;
            text-decoration: none;
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
        }

        .submit-btn:hover {
            background: #e66000;
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(255, 107, 0, 0.2);
        }

        .footer-text {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #f1f5f9;
            font-size: 0.78rem;
            font-weight: 600;
            color: #94a3b8;
            text-align: center;
        }

        .footer-text a {
            color: #FF6B00;
            text-decoration: none;
            font-weight: 800;
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
        @include('institute.auth.partials.brand-panel')

        <div class="auth-form-side">
            <div class="auth-form-inner">
                <div class="form-head">
                    <h1>Institute Login</h1>
                    <p>Welcome back! Please sign in to continue to your dashboard.</p>
                </div>

                @if ($errors->any())
                    <div class="error-box">
                        @foreach ($errors->all() as $error)
                            <p><i class="fas fa-circle-exclamation"></i> {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('institute.login') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <div class="input-wrapper">
                            <input type="email" name="email" required value="{{ $email ?? old('email') }}"
                                class="input-field" placeholder="admin@institute.com">
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-wrapper">
                            <input type="password" name="password" id="login-password" required value="{{ $password ?? '' }}"
                                class="input-field" placeholder="••••••••" style="padding-right:3rem">
                            <i class="fas fa-lock input-icon"></i>
                            <i class="fas fa-eye password-toggle" onclick="togglePwd('login-password', this)"></i>
                        </div>
                    </div>

                    <div class="options-row">
                        <label class="remember-me">
                            <input type="checkbox" name="remember" {{ isset($remember) && $remember ? 'checked' : '' }}>
                            <span>Remember Me</span>
                        </label>
                        <a href="{{ route('institute.password.request') }}" class="forgot-link">Forgot Password?</a>
                    </div>

                    <button type="submit" class="submit-btn">
                        Log In
                    </button>
                </form>

                <div class="footer-text">
                    <p>New Institute? <a href="{{ route('institute.register') }}">Create Account</a></p>
                </div>
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
