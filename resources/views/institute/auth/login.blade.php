<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Institute Login - Tuoora</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Outfit', sans-serif; 
            background-color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-y: auto;
            position: relative;
            padding: 20px 0;
        }

        .bg-pattern {
            position: fixed;
            inset: 0;
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 30px 30px;
            z-index: 1;
        }

        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 500px;
            padding: 20px;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card {
            background: #ffffff;
            border-radius: 1.5rem;
            padding: 1rem 2rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
            text-align: center;
        }

        .logo-box {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.2rem;
        }

        .logo-box img {
            height: 60px;
            width: auto;
            object-contain: contain;
        }

        .logo-section h1 {
            font-size: 1.6rem;
            font-weight: 900;
            color: #1e293b;
            letter-spacing: -0.5px;
            margin-bottom: 0.1rem;
            text-transform: uppercase;
        }

        .logo-section p {
            font-size: 0.65rem;
            font-weight: 700;
            color: #FF6B00;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .form-group {
            text-align: left;
            margin-bottom: 0.75rem;
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
            height: 3.2rem;
            padding: 0 1.5rem 0 3.5rem;
            background: #fcfdfe;
            border: 2px solid #f1f5f9;
            border-radius: 1rem;
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
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .input-field:focus + .input-icon {
            color: #FF6B00;
        }

        .options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 0.5rem;
            margin-bottom: 0.75rem;
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
            height: 3.2rem;
            background: #FF6B00;
            color: white;
            border: none;
            border-radius: 1rem;
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
            margin-top: 1rem;
            padding-top: 0.75rem;
            border-top: 1px solid #f1f5f9;
            font-size: 0.75rem;
            font-weight: 600;
            color: #94a3b8;
        }

        .footer-text a {
            color: #FF6B00;
            text-decoration: none;
            font-weight: 800;
        }

        .error-box {
            background: #fff1f2;
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
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
    <div class="bg-pattern"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="logo-section">
                <div class="logo-box">
                    <img src="{{ asset('images/turooa.png') }}" alt="Logo">
                </div>
                <!-- <h1>Tuoora</h1> -->
                <p>Management System</p>
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
                        <input type="email" name="email" required value="{{ $email ?? old('email') }}" class="input-field" placeholder="admin@institute.com">
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" required value="{{ $password ?? '' }}" class="input-field" placeholder="••••••••">
                        <i class="fas fa-lock input-icon"></i>
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
</body>
</html>
