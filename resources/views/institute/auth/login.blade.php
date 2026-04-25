<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Institute Login - FeeEasy</title>
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
            overflow: hidden;
            position: relative;
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
            padding: 1.25rem 2.25rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
            text-align: center;
        }

        .logo-box {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 55px;
            width: 55px;
            background: #1e3a8a;
            border-radius: 1rem;
            margin-bottom: 0.75rem;
            box-shadow: 0 10px 20px rgba(30, 58, 138, 0.2);
        }

        .logo-box svg {
            width: 1.8rem;
            height: 1.8rem;
            color: white;
        }

        .logo-section h1 {
            font-size: 1.8rem;
            font-weight: 900;
            color: #1e293b;
            letter-spacing: -0.5px;
            margin-bottom: 0.15rem;
            text-transform: uppercase;
        }

        .logo-section p {
            font-size: 0.65rem;
            font-weight: 700;
            color: #3b82f6;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 1.25rem;
        }

        .form-group {
            text-align: left;
            margin-bottom: 0.85rem;
        }

        .form-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
            padding-left: 0.25rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-field {
            width: 100%;
            height: 3.5rem;
            padding: 0 1.5rem 0 3.5rem;
            background: #fcfdfe;
            border: 2px solid #f1f5f9;
            border-radius: 1.25rem;
            font-size: 0.95rem;
            font-weight: 600;
            color: #1e293b;
            transition: all 0.3s ease;
            outline: none;
        }

        .input-field:focus {
            border-color: #3b82f6;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.05);
        }

        .input-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .input-field:focus + .input-icon {
            color: #3b82f6;
        }

        .options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 0.75rem;
            margin-bottom: 1rem;
            padding: 0 0.25rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .remember-me input {
            width: 1.1rem;
            height: 1.1rem;
            accent-color: #1e3a8a;
            cursor: pointer;
        }

        .remember-me span {
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
        }

        .forgot-link {
            font-size: 0.8rem;
            font-weight: 700;
            color: #3b82f6;
            text-decoration: none;
        }

        .submit-btn {
            width: 100%;
            height: 3.5rem;
            background: #1e3a8a;
            color: white;
            border: none;
            border-radius: 1.25rem;
            font-size: 0.9rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(30, 58, 138, 0.15);
        }

        .submit-btn:hover {
            background: #1e40af;
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(30, 58, 138, 0.2);
        }

        .footer-text {
            margin-top: 1.25rem;
            padding-top: 1rem;
            border-top: 1px solid #f1f5f9;
            font-size: 0.8rem;
            font-weight: 600;
            color: #94a3b8;
        }

        .footer-text a {
            color: #1e3a8a;
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
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h1>FeeEasy</h1>
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
                        <input type="email" name="email" required value="{{ old('email') }}" class="input-field" placeholder="admin@institute.com">
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" required class="input-field" placeholder="••••••••">
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                </div>

                <div class="options-row">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        <span>Remember Me</span>
                    </label>
                    <a href="#" class="forgot-link">Forgot Password?</a>
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
