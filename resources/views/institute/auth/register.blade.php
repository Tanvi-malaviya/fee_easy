<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Institute - FeeEasy</title>
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

        .register-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 750px;
            padding: 20px;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .register-card {
            background: #ffffff;
            border-radius: 1.5rem;
            padding: 1.25rem 2rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
        }

        /* Timeline Section */
        .timeline-line { background: #f1f5f9; }
        .timeline-progress { background: #3b82f6; }
        
        .step-circle {
            height: 36px;
            width: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            transition: all 0.3s ease;
        }
        
        .step-active { background: #3b82f6; color: white; box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3); }
        .step-pending { background: #f8fafc; color: #94a3b8; border: 2px solid #f1f5f9; }
        
        .step-label {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-top: 8px;
            text-align: center;
        }
        .label-active { color: #1e293b; }
        .label-pending { color: #94a3b8; }

        .header-section { text-align: center; margin-bottom: 1rem; margin-top: 0.25rem; }
        .header-section h2 { font-size: 1.6rem; font-weight: 900; color: #1e293b; letter-spacing: -0.5px; }
        .header-section p { font-size: 10px; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.15em; margin-top: 2px; }

        .form-label {
            display: block;
            font-size: 0.65rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.35rem;
            padding-left: 0.25rem;
        }

        .input-field {
            width: 100%;
            height: 3rem;
            padding: 0 1.25rem;
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
            border-color: #3b82f6;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.05);
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
            margin-top: 1rem;
        }

        .submit-btn:hover {
            background: #1e40af;
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(30, 58, 138, 0.2);
        }

        .footer-text { text-align: center; margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid #f1f5f9; }
        .footer-text p { font-size: 0.8rem; font-weight: 600; color: #94a3b8; }
        .footer-text a { color: #1e3a8a; text-decoration: none; font-weight: 800; }
        
        .error-box { background: #fff1f2; border-radius: 1rem; padding: 0.75rem; margin-bottom: 1.5rem; }
        .error-box p { font-size: 0.75rem; font-weight: 600; color: #e11d48; display: flex; align-items: center; gap: 0.5rem; }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>

    <div class="register-wrapper">
        <div class="register-card">
            <!-- Timeline Section -->
            <div class="flex items-center justify-between mb-8 relative px-6">
                <div class="absolute top-4.5 left-6 right-6 h-0.5 timeline-line z-0"></div>
                <div class="absolute top-4.5 left-6 h-0.5 timeline-progress z-0 transition-all duration-500" style="width: 0%"></div>

                <!-- Step 1 -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="step-circle step-active">1</div>
                    <span class="step-label label-active">Register</span>
                </div>
                <!-- Step 2 -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="step-circle step-pending">2</div>
                    <span class="step-label label-pending">Verify</span>
                </div>
                <!-- Step 3 -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="step-circle step-pending">3</div>
                    <span class="step-label label-pending">Setup</span>
                </div>
            </div>

            <!-- Header Section -->
            <div class="header-section">
                <h2>Create Institute</h2>
                <p>Start your 14-day free trial</p>
            </div>

            <!-- Form Section -->
            <form method="POST" action="{{ route('institute.register') }}" class="space-y-4">
                @csrf
                
                @if ($errors->any())
                    <div class="error-box">
                        @foreach ($errors->all() as $error)
                            <p><i class="fas fa-circle-exclamation"></i> {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="form-label">Institute Name</label>
                        <input type="text" name="institute_name" required value="{{ old('institute_name') }}"
                            class="input-field" placeholder="Global Academy">
                    </div>
                    <div class="space-y-1.5">
                        <label class="form-label">Admin Name</label>
                        <input type="text" name="name" required value="{{ old('name') }}"
                            class="input-field" placeholder="John Doe">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" required value="{{ old('email') }}"
                        class="input-field" placeholder="admin@institute.com">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" required 
                            class="input-field" placeholder="••••••••">
                    </div>
                    <div class="space-y-1.5">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" required 
                            class="input-field" placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    Create Account
                </button>
            </form>

            <div class="footer-text">
                <p>Already have an account? <a href="{{ route('institute.login') }}">Log in</a></p>
            </div>
        </div>
    </div>
</body>
</html>
