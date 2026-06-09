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

        .submit-btn:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
            transform: none;
        }

        .resend-wrapper {
            margin-top: 1.5rem;
            font-size: 0.78rem;
            font-weight: 600;
            color: #94a3b8;
            text-align: center;
        }

        .resend-wrapper a {
            color: #FF6B00;
            text-decoration: none;
            font-weight: 800;
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

        .otp-input {
            letter-spacing: 0.5em;
            text-align: center;
            padding-left: 1.25rem !important;
            font-size: 1.1rem !important;
        }
    </style>
</head>

<body>
    <div id="toast"></div>

    <div class="auth-shell">
        @include('institute.auth.partials.brand-panel', [
            'brandSubtext' => 'Set a strong new password for your institute account to keep your dashboard secure.',
        ])

        <div class="auth-form-side">
            <div class="auth-form-inner">
                <div class="form-head">
                    <h1>Set New Password</h1>
                    <p>Enter the code sent to your email and your new password.</p>
                </div>

                <form id="reset-password-form">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Verification Code</label>
                        <div class="input-wrapper">
                            <input type="text" name="otp" id="otp" required maxlength="6" class="input-field otp-input"
                                placeholder="000000">
                            <i class="fas fa-shield-alt input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <div class="input-wrapper">
                            <input type="password" name="password" id="password" required class="input-field"
                                placeholder="••••••••" style="padding-right: 3rem;">
                            <i class="fas fa-lock input-icon"></i>
                            <i class="fas fa-eye password-toggle" onclick="togglePwd('password', this)"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <div class="input-wrapper">
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="input-field" placeholder="••••••••" style="padding-right: 3rem;">
                            <i class="fas fa-check-double input-icon"></i>
                            <i class="fas fa-eye password-toggle" onclick="togglePwd('password_confirmation', this)"></i>
                        </div>
                    </div>

                    <button type="submit" id="submit-btn" class="submit-btn">
                        Reset Password
                    </button>
                </form>

                <div class="resend-wrapper">
                    <span>Didn't receive the code? </span>
                    <a href="javascript:void(0)" id="resend-btn">Resend OTP</a>
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

        const form = document.getElementById('reset-password-form');
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

            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;

            if (password !== confirm) {
                showToast('Passwords do not match.', true);
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Updating...';

            try {
                const response = await fetch("{{ route('institute.password.update') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        otp: document.getElementById('otp').value,
                        password: password,
                        password_confirmation: confirm
                    })
                });

                const result = await response.json();

                if (response.ok) {
                    showToast(result.message);
                    setTimeout(() => {
                        window.location.href = "{{ route('institute.login') }}";
                    }, 2000);
                } else {
                    showToast(result.message || 'Invalid OTP or session expired.', true);
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Reset Password';
                }
            } catch (error) {
                showToast('Something went wrong. Please try again.', true);
                submitBtn.disabled = false;
                submitBtn.textContent = 'Reset Password';
            }
        });

        // Resend OTP Direct Ajax logic
        const resendBtn = document.getElementById('resend-btn');

        resendBtn.addEventListener('click', async () => {
            if (!resendBtn) return;
            resendBtn.style.pointerEvents = 'none';
            resendBtn.style.opacity = '0.6';
            resendBtn.textContent = 'Sending...';

            try {
                const response = await fetch("{{ route('institute.password.email') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: "{{ session('reset_email') }}"
                    })
                });

                const result = await response.json();

                if (response.ok) {
                    showToast('OTP resent successfully!');
                } else {
                    showToast(result.message || 'Error resending OTP.', true);
                }
            } catch (error) {
                showToast('Something went wrong.', true);
            } finally {
                resendBtn.textContent = 'Resend OTP';
                resendBtn.style.pointerEvents = 'auto';
                resendBtn.style.opacity = '1';
            }
        });
    </script>
</body>

</html>