<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Tuoora</title>
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
            padding: 15px;
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
            max-width: 400px;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card {
            background: #ffffff;
            border-radius: 1.25rem;
            padding: 1rem 1.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
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
            object-fit: contain;
        }

        .logo-section h1 {
            display: none;
            font-size: 1.4rem;
            font-weight: 900;
            color: #1e293b;
            letter-spacing: -0.5px;
            margin-bottom: 0.1rem;
            text-transform: uppercase;
        }

        .logo-section p {
            font-size: 0.6rem;
            font-weight: 700;
            color: #FF6B00;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .instruction-text {
            font-size: 0.8rem;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 1.25rem;
        }

        .form-group {
            text-align: left;
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            font-size: 0.65rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.4rem;
            padding-left: 0.2rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-field {
            width: 100%;
            height: 2.8rem;
            padding: 0 1.25rem 0 3rem;
            background: #fcfdfe;
            border: 2px solid #f1f5f9;
            border-radius: 0.85rem;
            font-size: 0.85rem;
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
            left: 1.1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.9rem;
        }

        .submit-btn {
            width: 100%;
            height: 2.8rem;
            background: #FF6B00;
            color: white;
            border: none;
            border-radius: 0.85rem;
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 16px rgba(255, 107, 0, 0.15);
        }

        .submit-btn:hover {
            background: #e66000;
            transform: translateY(-1px);
        }

        .submit-btn:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
            transform: none;
        }

        .back-to-login {
            margin-top: 1.25rem;
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 700;
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .back-to-login:hover {
            color: #FF6B00;
        }

        #toast {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            background: #1e293b;
            color: white;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 100;
            transform: translateX(150%);
            transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        #toast.show { transform: translateX(0); }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>
    <div id="toast"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="logo-section">
                <div class="logo-box">
                    <img src="{{ asset('images/turooa.png') }}" alt="Logo">
                </div>
                <h1>Tuoora</h1>
                <p>Management System</p>
            </div>

            <h2 style="font-size: 1.1rem; color: #1e293b; margin-bottom: 0.4rem; font-weight: 700;">Reset Password</h2>
            <p class="instruction-text">Enter your registered email address and we'll send you an OTP to reset your password.</p>

            <form id="forgot-password-form">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" id="email" required class="input-field" placeholder="admin@tuoora.com">
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>

                <button type="submit" id="submit-btn" class="submit-btn">
                    Send Reset Code
                </button>
            </form>

            <a href="{{ route('institute.login') }}" class="back-to-login">
                <i class="fas fa-arrow-left mr-1"></i> Back to Login
            </a>
        </div>
    </div>

    <script>
        const form = document.getElementById('forgot-password-form');
        const submitBtn = document.getElementById('submit-btn');
        const toast = document.getElementById('toast');

        function showToast(message, isError = false) {
            toast.textContent = message;
            toast.style.background = isError ? '#e11d48' : '#10b981';
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';

            try {
                const response = await fetch("{{ route('institute.password.email') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: document.getElementById('email').value
                    })
                });

                const result = await response.json();

                if (response.ok) {
                    showToast(result.message);
                    setTimeout(() => {
                        window.location.href = "{{ route('institute.password.reset', ['token' => 'default']) }}";
                    }, 1500);
                } else {
                    showToast(result.message || 'Email not found.', true);
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Send Reset Code';
                }
            } catch (error) {
                showToast('Something went wrong. Please try again.', true);
                submitBtn.disabled = false;
                submitBtn.textContent = 'Send Reset Code';
            }
        });
    </script>
</body>
</html>
