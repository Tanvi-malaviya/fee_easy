<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Tuoora</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('institute.auth.partials.brand-styles')
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #ffffff;
            min-height: 100vh;
        }

        .form-label {
            display: block;
            font-size: 0.68rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .input-field {
            width: 100%;
            height: 3.4rem;
            background: #fcfdfe;
            border: 2px solid #f1f5f9;
            border-radius: 0.85rem;
            font-size: 1.8rem;
            font-weight: 900;
            color: #1e293b;
            text-align: center;
            letter-spacing: 0.4em;
            transition: all 0.3s ease;
            outline: none;
            padding-left: 0.4em;
        }

        .input-field:focus {
            border-color: #FF6B00;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(255, 107, 0, 0.05);
        }

        .submit-btn {
            width: 100%;
            height: 3.1rem;
            background: #FF6B00;
            color: white;
            border: none;
            border-radius: 0.85rem;
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(255, 107, 0, 0.15);
            margin-top: 1.25rem;
        }

        .submit-btn:hover {
            background: #e66000;
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(255, 107, 0, 0.2);
        }

        .footer-text { text-align: center; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #f1f5f9; }
        .footer-text p { font-size: 0.78rem; font-weight: 600; color: #94a3b8; }
        .footer-text a { color: #FF6B00; text-decoration: none; font-weight: 800; }
        .logout-btn { background: none; border: none; font-size: 0.68rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; cursor: pointer; margin-top: 0.6rem; }
        .logout-btn:hover { color: #FF6B00; }

        .error-box { background: #fff1f2; border-radius: 0.85rem; padding: 0.9rem 1rem; margin-bottom: 1.25rem; }
        .error-box p { font-size: 0.8rem; font-weight: 600; color: #e11d48; display: flex; align-items: center; gap: 0.5rem; }
    </style>
</head>

<body>
    <div class="auth-shell">
        @include('institute.auth.partials.brand-panel', [
            'brandSubtext' => 'We have sent a 6-digit verification code to your email. Enter it here to confirm your account.',
            'activeStep' => 2,
            'brandSteps' => [
                ['title' => 'Register', 'desc' => 'Create your institute account'],
                ['title' => 'Verify Email', 'desc' => 'Confirm with a secure OTP'],
                ['title' => 'Setup Profile', 'desc' => 'Add your institute details'],
            ],
        ])

        <div class="auth-form-side">
            <div class="auth-form-inner">
                <div class="form-head">
                    <h1>Check your Email</h1>
                    <p>Enter the 6-digit code we sent to verify your address.</p>
                </div>

                <form method="POST" action="{{ route('institute.verify-otp') }}">
                    @csrf

                    @if ($errors->any())
                        <div class="error-box">
                            @foreach ($errors->all() as $error)
                                <p><i class="fas fa-circle-exclamation"></i> {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <label class="form-label">Verification Code</label>
                    <input type="text" name="otp" required maxlength="6" class="input-field" placeholder="000000">

                    <button type="submit" class="submit-btn">
                        Verify &amp; Continue
                    </button>
                </form>

                <div class="footer-text">
                    <p>Didn't receive? <a href="#">Resend</a></p>
                    <form action="{{ route('institute.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn">Use a different email</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
