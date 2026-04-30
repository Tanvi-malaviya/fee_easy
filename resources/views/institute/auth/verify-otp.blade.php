<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - FeeEasy</title>
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

        .verify-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 20px;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .verify-card {
            background: #ffffff;
            border-radius: 1.5rem;
            padding: 1rem 1.5rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
            text-align: center;
        }

        /* Timeline Section */
        .timeline-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            position: relative;
            padding: 0 1rem;
        }

        .timeline-line {
            position: absolute;
            top: 14px;
            left: 2rem;
            right: 2rem;
            height: 2px;
            background: #f1f5f9;
            z-index: 0;
        }

        .timeline-progress {
            position: absolute;
            top: 14px;
            left: 2rem;
            height: 2px;
            background: #FF6B00;
            z-index: 0;
            transition: all 0.5s ease;
        }
        
        .step-item {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .step-circle {
            height: 28px;
            width: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 700;
            transition: all 0.3s ease;
        }
        
        .step-active { background: #FF6B00; color: white; box-shadow: 0 5px 15px rgba(255, 107, 0, 0.3); }
        .step-completed { background: #FF6B00; color: white; }
        .step-pending { background: #f8fafc; color: #94a3b8; border: 2px solid #f1f5f9; }
        
        .step-label {
            font-size: 7px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-top: 4px;
        }
        .label-active { color: #1e293b; }
        .label-pending { color: #94a3b8; }

        .logo-box {
            text-align: center;
            margin-bottom: 0.25rem;
        }

        .logo-box img {
            height: 35px;
            width: auto;
            margin: 0 auto;
        }

        .header-section { text-align: center; margin-bottom: 0.75rem; margin-top: 0.25rem; }
        .header-section i { font-size: 1.75rem; color: #FF6B00; margin-bottom: 0.25rem; }
        .header-section h2 { font-size: 1.4rem; font-weight: 900; color: #1e293b; letter-spacing: -0.5px; }
        .header-section p { font-size: 8px; font-weight: 700; color: #FF6B00; text-transform: uppercase; letter-spacing: 0.15em; margin-top: 1px; }

        .form-label {
            display: block;
            font-size: 0.65rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .input-field {
            width: 100%;
            height: 2.8rem;
            background: #f8fafc;
            border: 2px solid #f1f5f9;
            border-radius: 0.75rem;
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
            height: 3rem;
            background: #FF6B00;
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(255, 107, 0, 0.15);
            margin-top: 1rem;
        }

        .submit-btn:hover {
            background: #e66000;
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(255, 107, 0, 0.2);
        }

        .footer-text { text-align: center; margin-top: 1rem; padding-top: 0.75rem; border-top: 1px solid #f1f5f9; }
        .footer-text p { font-size: 0.75rem; font-weight: 600; color: #94a3b8; }
        .footer-text a { color: #FF6B00; text-decoration: none; font-weight: 800; }
        .logout-btn { background: none; border: none; font-size: 8px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; cursor: pointer; margin-top: 0.5rem; }
        .logout-btn:hover { color: #FF6B00; }

        .error-box { background: #fff1f2; border-radius: 1rem; padding: 0.75rem; margin-bottom: 1.25rem; }
        .error-box p { font-size: 0.75rem; font-weight: 600; color: #e11d48; display: flex; align-items: center; gap: 0.5rem; justify-content: center; }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>

    <div class="verify-wrapper">
        <div class="verify-card">
            <!-- Timeline Section -->
            <div class="timeline-container">
                <div class="timeline-line"></div>
                <div class="timeline-progress" style="width: 50%"></div>

                <!-- Step 1 (Completed) -->
                <div class="step-item">
                    <div class="step-circle step-completed"><i class="fas fa-check text-[10px]"></i></div>
                    <span class="step-label label-active">Register</span>
                </div>
                <!-- Step 2 (Active) -->
                <div class="step-item">
                    <div class="step-circle step-active">2</div>
                    <span class="step-label label-active">Verify</span>
                </div>
                <!-- Step 3 (Pending) -->
                <div class="step-item">
                    <div class="step-circle step-pending">3</div>
                    <span class="step-label label-pending">Setup</span>
                </div>
            </div>

            <div class="logo-box">
                <img src="{{ asset('images/turooa.png') }}" alt="Logo">
            </div>

            <!-- Header Section -->
            <div class="header-section">
                <i class="fas fa-envelope-open-text"></i>
                <h2>Check Email</h2>
                <p>Enter the 6-digit code sent to you</p>
            </div>

            <!-- Form Section -->
            <form method="POST" action="{{ route('institute.verify-otp') }}" class="space-y-3">
                @csrf
                
                @if ($errors->any())
                    <div class="error-box">
                        @foreach ($errors->all() as $error)
                            <p><i class="fas fa-circle-exclamation"></i> {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="space-y-3">
                    <label class="form-label">Verification Code</label>
                    <input type="text" name="otp" required maxlength="6"
                        class="input-field" placeholder="000000">
                </div>

                <button type="submit" class="submit-btn">
                    Verify & Continue
                </button>
            </form>

            <div class="footer-text">
                <p>Didn't receive? <a href="#">Resend</a></p>
                <form action="{{ route('institute.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">Use different email</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
