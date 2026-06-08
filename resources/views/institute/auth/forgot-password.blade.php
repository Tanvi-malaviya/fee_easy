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

        .submit-btn:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
            transform: none;
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
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        #toast.show {
            transform: translateX(0);
        }
    </style>
</head>

<body>
    <div id="toast"></div>

    <div class="auth-shell">
        @include('institute.auth.partials.brand-panel', [
            'brandSubtext' => 'No worries — it happens. Enter your registered email and we will send you a secure code to reset your password.',
        ])

        <div class="auth-form-side">
            <div class="auth-form-inner">
                <div class="form-head">
                    <h1>Reset Password</h1>
                    <p>Enter your registered email address and we'll send you an OTP to reset your password.</p>
                </div>

                <form id="forgot-password-form">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <div class="input-wrapper">
                            <input type="email" name="email" id="email" required class="input-field"
                                placeholder="admin@tuoora.com">
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                    </div>

                    <button type="submit" id="submit-btn" class="submit-btn">
                        Send Reset Code
                    </button>
                </form>

                <a href="{{ route('institute.login') }}" class="back-to-login">
                    <i class="fas fa-arrow-left"></i> Back to Login
                </a>
            </div>
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
