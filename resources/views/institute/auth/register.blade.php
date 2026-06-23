<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Institute - Tuoora</title>
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

        /* register form side is a touch wider for the setup grid */
        .auth-form-side .auth-form-inner { max-width: 440px; }

        .header-section { text-align: left; margin-bottom: 1.25rem; }
        .header-section h2 { font-size: 1.5rem; font-weight: 800; color: #1e293b; letter-spacing: -0.5px; }
        .header-section p { font-size: 0.85rem; font-weight: 500; color: #64748b; margin-top: 0.25rem; }

        .step-content { display: none; }
        .step-content.active { display: block; animation: slideUp 0.5s ease-out; }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.1rem 0.9rem; }
        .form-group-full { grid-column: span 2; }

        .setup-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 0.85rem 0.65rem; }
        .col-span-3 { grid-column: span 3; }
        .col-span-2 { grid-column: span 2; }

        .form-label {
            display: block;
            font-size: 0.68rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.35rem;
            padding-left: 0.2rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-field {
            width: 100%;
            height: 2.8rem;
            padding: 0 1rem;
            background: #fcfdfe;
            border: 2px solid #f1f5f9;
            border-radius: 0.7rem;
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

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.9rem;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .password-toggle:hover {
            color: #FF6B00;
        }

        .submit-btn {
            width: 100%;
            height: 2.9rem;
            background: #FF6B00;
            color: white;
            border: none;
            border-radius: 0.7rem;
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 10px 18px rgba(255, 107, 0, 0.14);
        }

        .submit-btn:hover { background: #e66000; transform: translateY(-1px); }
        .submit-btn:disabled { background: #cbd5e1; cursor: not-allowed; transform: none; }

        .otp-container { display: flex; justify-content: space-between; gap: 0.5rem; margin: 1rem 0; }
        .otp-input {
            width: 100%;
            height: 3.2rem;
            text-align: center;
            font-size: 1.35rem;
            font-weight: 800;
            border: 2px solid #f1f5f9;
            border-radius: 0.7rem;
            background: #fcfdfe;
            outline: none;
            transition: all 0.3s ease;
        }
        .otp-input:focus {
            border-color: #FF6B00;
            box-shadow: 0 0 0 4px rgba(255, 107, 0, 0.05);
        }

        .logo-upload-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .logo-upload {
            position: relative;
            width: 72px;
            height: 72px;
            border-radius: 50%;
            border: 2px dashed #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background: #fcfdfe;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .logo-upload:hover { border-color: #FF6B00; background: #ffffff; }
        .logo-upload img { width: 100%; height: 100%; object-fit: cover; display: none; }
        .logo-upload i { font-size: 1.1rem; color: #94a3b8; }

        .logo-label {
            font-size: 0.6rem;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 0.4rem;
        }

        .footer-text { text-align: center; margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid #f1f5f9; }
        .footer-text p { font-size: 0.78rem; font-weight: 600; color: #94a3b8; }
        .footer-text a { color: #FF6B00; text-decoration: none; font-weight: 800; cursor: pointer; }

        .success-checkmark {
            width: 60px;
            height: 60px;
            background: #dcfce7;
            color: #22c55e;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1rem;
        }

        .loader { width: 16px; height: 16px; border: 2.5px solid #ffffff33; border-top: 2.5px solid white; border-radius: 50%; animation: spin 0.8s linear infinite; display: none; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .error-message { font-size: 0.68rem; color: #ef4444; font-weight: 600; margin-top: 0.2rem; display: none; }
    </style>
</head>

<body>
    @php $initialStep = $initialStep ?? 1; @endphp

    <div class="auth-shell">
        @include('institute.auth.partials.brand-panel', [
            'brandSubtext' => 'Set up your institute in three quick steps and start managing students, fees and staff right away.',
            'showTrialBadge' => true,
            'brandSteps' => [
                ['title' => 'Register', 'desc' => 'Create your institute account'],
                ['title' => 'Verify Email', 'desc' => 'Confirm with a secure OTP'],
                ['title' => 'Setup Profile', 'desc' => 'Add your institute details'],
            ],
        ])

        <div class="auth-form-side">
            <div class="auth-form-inner">

                <!-- STEP 1: REGISTRATION -->
                <div class="step-content {{ $initialStep == 1 ? 'active' : '' }}" id="step1">
                    <div class="header-section">
                        <h2>Create Account</h2>
                        <p>Let's get your institute started.</p>
                    </div>
                    <form id="registerForm">
                        @csrf
                        <div class="form-grid">
                            <div>
                                <label class="form-label">Institute Name</label>
                                <input type="text" name="institute_name" class="input-field" placeholder="Enter Institute Name" required>
                                <span class="error-message" id="error-institute_name"></span>
                            </div>
                            <div>
                                <label class="form-label">Owner Name</label>
                                <input type="text" name="name" class="input-field" placeholder="Enter Owner Name" required>
                                <span class="error-message" id="error-name"></span>
                            </div>
                            <div class="form-group-full">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="input-field" placeholder="Enter Email Address" required>
                                <span class="error-message" id="error-email"></span>
                            </div>
                            <div class="form-group-full">
                                <label class="form-label">Password</label>
                                <div class="input-wrapper">
                                    <input type="password" name="password" id="reg-password" class="input-field" placeholder="Enter Password" required style="padding-right: 2.5rem;">
                                    <i class="fas fa-eye password-toggle" onclick="togglePwd('reg-password', this)"></i>
                                </div>
                                <span class="error-message" id="error-password"></span>
                            </div>
                        </div>
                        <button type="submit" class="submit-btn" id="btn1">
                            <span>Continue</span>
                            <div class="loader"></div>
                        </button>
                    </form>
                    <div class="footer-text">
                        <p>Have an account? <a href="{{ route('institute.login') }}">Log In</a></p>
                    </div>
                </div>

                <!-- STEP 2: OTP -->
                <div class="step-content {{ $initialStep == 2 ? 'active' : '' }}" id="step2">
                    <div class="header-section">
                        <h2>Verify Email</h2>
                        <p>Enter the 6-digit code we sent to your email.</p>
                    </div>
                    <form id="otpForm">
                        @csrf
                        <div class="otp-container">
                            <input type="text" class="otp-input" maxlength="1" pattern="\d*" inputmode="numeric">
                            <input type="text" class="otp-input" maxlength="1" pattern="\d*" inputmode="numeric">
                            <input type="text" class="otp-input" maxlength="1" pattern="\d*" inputmode="numeric">
                            <input type="text" class="otp-input" maxlength="1" pattern="\d*" inputmode="numeric">
                            <input type="text" class="otp-input" maxlength="1" pattern="\d*" inputmode="numeric">
                            <input type="text" class="otp-input" maxlength="1" pattern="\d*" inputmode="numeric">
                        </div>
                        <input type="hidden" name="otp" id="fullOtp">
                        <span class="error-message" id="error-otp" style="text-align: center; display: block; margin-bottom: 0.5rem;"></span>
                        <button type="submit" class="submit-btn" id="btn2">
                            <span>Verify</span>
                            <div class="loader"></div>
                        </button>
                    </form>
                    <div class="footer-text">
                        <p>Didn't receive? <a href="javascript:void(0)" id="resendLink" onclick="resendOtp()">Resend OTP</a></p>
                        <span id="resendStatus" style="font-size: 0.68rem; margin-top: 0.35rem; display: block;"></span>
                    </div>
                </div>

                <!-- STEP 3: SETUP -->
                <div class="step-content {{ $initialStep == 3 ? 'active' : '' }}" id="step3">
                    <div class="header-section">
                        <h2>Complete Setup</h2>
                        <p>Finalize your institute profile.</p>
                    </div>
                    <form id="setupForm" enctype="multipart/form-data">
                        @csrf
                        <div class="logo-upload-wrapper">
                            <div class="logo-upload" onclick="document.getElementById('logoInput').click()">
                                <i class="fas fa-plus" id="logoIcon"></i>
                                <img id="logoPreview" src="" alt="Preview">
                                <input type="file" id="logoInput" name="logo" style="display: none;" accept="image/*">
                            </div>
                            <span class="logo-label">Institute Logo</span>
                        </div>

                        <div class="setup-grid">
                            <div class="col-span-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="input-field" placeholder="10 digit number" required maxlength="10"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <span class="error-message" id="error-phone"></span>
                            </div>
                            <div class="col-span-3">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="input-field" placeholder="Country" required>
                                <span class="error-message" id="error-country"></span>
                            </div>
                            <div class="col-span-2">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="input-field" placeholder="City" required>
                                <span class="error-message" id="error-city"></span>
                            </div>
                            <div class="col-span-2">
                                <label class="form-label">State</label>
                                <input type="text" name="state" class="input-field" placeholder="State" required>
                                <span class="error-message" id="error-state"></span>
                            </div>
                            <div class="col-span-2">
                                <label class="form-label">Pincode</label>
                                <input type="text" name="pincode" class="input-field" placeholder="Pincode" required>
                                <span class="error-message" id="error-pincode"></span>
                            </div>
                            <div class="col-span-3">
                                <label class="form-label">Address Line 1</label>
                                <input type="text" name="address" class="input-field" placeholder="Area, Landmark" required>
                                <span class="error-message" id="error-address"></span>
                            </div>
                            <div class="col-span-3">
                                <label class="form-label">Address Line 2 (Optional)</label>
                                <input type="text" name="address_line_2" class="input-field" placeholder="Apartment, suite, unit, etc.">
                                <span class="error-message" id="error-address_line_2"></span>
                            </div>
                        </div>
                        <button type="submit" class="submit-btn" id="btn3">
                            <span>Finish</span>
                            <div class="loader"></div>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        const vsteps = [document.getElementById('vstep1'), document.getElementById('vstep2'), document.getElementById('vstep3')];
        const steps = [document.getElementById('step1'), document.getElementById('step2'), document.getElementById('step3')];

        let currentStep = {{ $initialStep ?? 1 }};

        function setStep(stepNumber) {
            currentStep = stepNumber;

            vsteps.forEach((el, i) => {
                if (!el) return;
                const idx = i + 1;
                el.classList.remove('is-active', 'is-done', 'is-pending');
                const dot = el.querySelector('.vstep-dot');
                if (idx < stepNumber) {
                    el.classList.add('is-done');
                    if (dot) dot.innerHTML = '<i class="fas fa-check"></i>';
                } else if (idx === stepNumber) {
                    el.classList.add('is-active');
                    if (dot) dot.innerHTML = idx;
                } else {
                    el.classList.add('is-pending');
                    if (dot) dot.innerHTML = idx;
                }
            });

            steps.forEach((s, i) => s.classList.toggle('active', i + 1 === stepNumber));
        }

        setStep(currentStep);

        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn1');
            const loader = btn.querySelector('.loader');
            const span = btn.querySelector('span');

            btn.disabled = true; loader.style.display = 'block'; span.style.opacity = '0.5';
            document.querySelectorAll('.error-message').forEach(el => el.style.display = 'none');

            try {
                const formData = new FormData(e.target);
                const response = await fetch("{{ route('institute.register') }}", {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const data = await response.json();

                if (response.ok) {
                    setStep(2);
                } else {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            const errEl = document.getElementById('error-' + key);
                            if (errEl) { errEl.innerText = data.errors[key][0]; errEl.style.display = 'block'; }
                        });
                    } else if (data.message) {
                        alert(data.message);
                    }
                }
            } catch (error) {
                console.error('Fetch Error:', error);
                alert('Something went wrong. Please check your connection.');
            } finally {
                btn.disabled = false; loader.style.display = 'none'; span.style.opacity = '1';
            }
        });

        const otpInputs = document.querySelectorAll('.otp-input');
        otpInputs.forEach((input, index) => {
            input.addEventListener('keyup', (e) => {
                if (e.key >= 0 && e.key <= 9 && index < otpInputs.length - 1) otpInputs[index + 1].focus();
                else if (e.key === 'Backspace' && index > 0) otpInputs[index - 1].focus();
                document.getElementById('fullOtp').value = Array.from(otpInputs).map(i => i.value).join('');
            });

            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pastedData = (e.clipboardData || window.clipboardData).getData('text').trim();
                const digits = pastedData.replace(/\D/g, '').substring(0, otpInputs.length);

                if (digits) {
                    const chars = digits.split('');
                    otpInputs.forEach((inp, idx) => {
                        inp.value = chars[idx] || '';
                    });

                    const focusIndex = Math.min(digits.length - 1, otpInputs.length - 1);
                    if (focusIndex >= 0) otpInputs[focusIndex].focus();

                    document.getElementById('fullOtp').value = Array.from(otpInputs).map(i => i.value).join('');
                }
            });
        });

        document.getElementById('otpForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn2');
            const loader = btn.querySelector('.loader');
            const span = btn.querySelector('span');

            btn.disabled = true; loader.style.display = 'block'; span.style.opacity = '0.5';
            document.getElementById('error-otp').style.display = 'none';

            try {
                const formData = new FormData();
                formData.append('otp', document.getElementById('fullOtp').value);
                formData.append('_token', "{{ csrf_token() }}");

                const response = await fetch("{{ route('institute.verify-otp') }}", {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const data = await response.json();

                if (response.ok) {
                    setStep(3);
                } else {
                    document.getElementById('error-otp').innerText = data.message || 'Invalid OTP';
                    document.getElementById('error-otp').style.display = 'block';
                }
            } catch (error) {
                console.error('OTP Error:', error);
                alert('Verification failed. Try again.');
            } finally {
                btn.disabled = false; loader.style.display = 'none'; span.style.opacity = '1';
            }
        });

        async function resendOtp() {
            const link = document.getElementById('resendLink');
            const status = document.getElementById('resendStatus');

            link.style.pointerEvents = 'none';
            link.style.opacity = '0.5';
            status.innerText = 'Sending...';
            status.style.color = '#64748b';

            try {
                const response = await fetch("{{ route('institute.resend-otp') }}", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                });
                const data = await response.json();

                if (response.ok) {
                    status.innerText = 'New OTP sent to your email!';
                    status.style.color = '#22c55e';
                    let timeLeft = 30;
                    const timer = setInterval(() => {
                        timeLeft--;
                        if (timeLeft <= 0) {
                            clearInterval(timer);
                            link.style.pointerEvents = 'auto';
                            link.style.opacity = '1';
                            link.innerText = 'Resend OTP';
                            status.innerText = '';
                        } else {
                            link.innerText = `Resend OTP (${timeLeft}s)`;
                        }
                    }, 1000);
                } else {
                    status.innerText = data.message || 'Failed to send OTP.';
                    status.style.color = '#ef4444';
                    link.style.pointerEvents = 'auto';
                    link.style.opacity = '1';
                }
            } catch (error) {
                status.innerText = 'Error sending OTP. Try again.';
                status.style.color = '#ef4444';
                link.style.pointerEvents = 'auto';
                link.style.opacity = '1';
            }
        }

        document.getElementById('logoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('logoPreview').src = event.target.result;
                    document.getElementById('logoPreview').style.display = 'block';
                    document.getElementById('logoIcon').style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('setupForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn3');
            const loader = btn.querySelector('.loader');
            const span = btn.querySelector('span');

            btn.disabled = true; loader.style.display = 'block'; span.style.opacity = '0.5';

            try {
                const formData = new FormData(e.target);
                const response = await fetch("{{ route('institute.setup-profile') }}", {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const data = await response.json();

                if (response.ok) {
                    document.getElementById('setupForm').innerHTML = `
                        <div style="text-align: center; padding: 1rem 0;">
                            <div class="success-checkmark"><i class="fas fa-check"></i></div>
                            <h3 style="font-weight: 900; color: #1e293b; margin-bottom: 0.25rem; font-size: 1.25rem;">Done!</h3>
                            <p style="font-size: 0.8rem; color: #64748b; font-weight: 700;">Dashboard loading...</p>
                        </div>`;
                    setTimeout(() => window.location.href = data.redirect, 1500);
                } else {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            const errEl = document.getElementById('error-' + key);
                            if (errEl) { errEl.innerText = data.errors[key][0]; errEl.style.display = 'block'; }
                        });
                    } else {
                        alert(data.message || 'Setup failed');
                    }
                }
            } catch (error) {
                console.error('Setup Error:', error);
                alert('Something went wrong during setup.');
            } finally {
                btn.disabled = false; loader.style.display = 'none'; span.style.opacity = '1';
            }
        });
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
