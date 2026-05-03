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
            height: 100vh;
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
            max-width: 580px;
            padding: 5px;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .register-card {
            background: #ffffff;
            border-radius: 1.5rem;
            padding: 0.75rem 1.75rem;
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
        }

        .logo-box { 
            text-align: center; 
            margin-bottom: 0.35rem; 
        }
        .logo-box img { 
            height: 30px; 
            width: auto;
        }

        /* Timeline Section Fixed */
        .timeline-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            position: relative;
            padding: 0 4.5rem;
        }

        .timeline-line {
            position: absolute;
            top: 10px;
            left: 5.5rem;
            right: 5.5rem;
            height: 2px;
            background: #f1f5f9;
            z-index: 0;
            overflow: hidden;
        }

        .timeline-progress {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background: #FF6B00;
            z-index: 1;
            transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
            width: 0%;
        }
        
        .step-item {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 40px;
        }

        .step-circle {
            height: 20px;
            width: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            font-weight: 800;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            background: white;
            border: 1.5px solid #f1f5f9;
        }
        
        .step-active { background: #FF6B00; color: white; border-color: #FF6B00; box-shadow: 0 5px 15px rgba(255, 107, 0, 0.3); transform: scale(1.1); }
        .step-completed { background: #22c55e; color: white; border-color: #22c55e; }
        .step-pending { color: #94a3b8; }
        
        .step-label {
            font-size: 0.55rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 3px;
            text-align: center;
        }
        .label-active { color: #1e293b; }
        .label-pending { color: #94a3b8; }

        .step-content { display: none; }
        .step-content.active { display: block; animation: slideUp 0.5s ease-out; }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header-section { text-align: center; margin-bottom: 0.75rem; }
        .header-section h2 { font-size: 1.15rem; font-weight: 900; color: #1e293b; letter-spacing: -0.5px; }
        .header-section p { font-size: 0.65rem; font-weight: 700; color: #FF6B00; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 0px; }

        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.6rem; }
        .form-group-full { grid-column: span 2; }

        .form-label {
            display: block;
            font-size: 0.6rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.15rem;
            padding-left: 0.1rem;
        }

        .input-field {
            width: 100%;
            height: 2.5rem;
            padding: 0 0.85rem;
            background: #f8fafc;
            border: 1.5px solid transparent;
            border-radius: 0.75rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #1e293b;
            transition: all 0.3s ease;
            outline: none;
        }

        .input-field:focus { border-color: #FF6B00; background: #ffffff; }

        .submit-btn {
            width: 100%;
            height: 2.75rem;
            background: #FF6B00;
            color: white;
            border: none;
            border-radius: 0.85rem;
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .submit-btn:hover { background: #e66000; transform: translateY(-1px); }
        .submit-btn:disabled { background: #cbd5e1; cursor: not-allowed; }

        .otp-container { display: flex; justify-content: center; gap: 0.5rem; margin: 0.75rem 0; }
        .otp-input {
            width: 40px;
            height: 50px;
            text-align: center;
            font-size: 1.4rem;
            font-weight: 800;
            border: 1.5px solid #f1f5f9;
            border-radius: 0.75rem;
            background: #fcfdfe;
            outline: none;
        }

        /* Setup Profile Ultra Compact */
        .setup-container {
            max-width: 480px;
            margin: 0 auto;
        }
        
        .logo-upload-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .logo-upload {
            position: relative;
            width: 65px;
            height: 65px;
            border-radius: 50%;
            border: 1.5px dashed #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background: #f8fafc;
            overflow: hidden;
        }
        .logo-upload img { width: 100%; height: 100%; object-fit: cover; display: none; }
        .logo-upload i { font-size: 1rem; color: #94a3b8; }
        
        .logo-label {
            font-size: 0.55rem;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 0.2rem;
        }

        .footer-text { text-align: center; margin-top: 0.5rem; padding-top: 0.5rem; border-top: 1px solid #f1f5f9; }
        .footer-text p { font-size: 0.7rem; font-weight: 600; color: #94a3b8; }
        .footer-text a { color: #FF6B00; text-decoration: none; font-weight: 800; cursor: pointer; position: relative; z-index: 102; }
        
        .success-checkmark {
            width: 50px;
            height: 50px;
            background: #dcfce7;
            color: #22c55e;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin: 0 auto 0.75rem;
        }

        .loader { width: 16px; height: 16px; border: 2.5px solid #ffffff33; border-top: 2.5px solid white; border-radius: 50%; animation: spin 0.8s linear infinite; display: none; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .error-message { font-size: 0.6rem; color: #ef4444; font-weight: 600; margin-top: 0.15rem; display: none; }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>

    <div class="register-wrapper">
        <div class="register-card">
            <div class="logo-box">
                <img src="{{ asset('images/turooa.png') }}" alt="Turooa Logo">
            </div>

            <div class="timeline-container">
                <div class="timeline-line">
                    <div class="timeline-progress" id="timelineProgress"></div>
                </div>

                <div class="step-item" id="step1-indicator">
                    <div class="step-circle" id="circle1">1</div>
                    <span class="step-label" id="label1">Register</span>
                </div>
                <div class="step-item" id="step2-indicator">
                    <div class="step-circle" id="circle2">2</div>
                    <span class="step-label" id="label2">Verify</span>
                </div>
                <div class="step-item" id="step3-indicator">
                    <div class="step-circle" id="circle3">3</div>
                    <span class="step-label" id="label3">Setup</span>
                </div>
            </div>

            @php $initialStep = $initialStep ?? 1; @endphp

            <!-- STEP 1: REGISTRATION -->
            <div class="step-content {{ $initialStep == 1 ? 'active' : '' }}" id="step1">
                <div class="header-section">
                    <h2>Join FeeEasy</h2>
                    <p>Smart Management</p>
                </div>
                <form id="registerForm">
                    @csrf
                    <div class="form-grid">
                        <div>
                            <label class="form-label">Institute Name</label>
                            <input type="text" name="institute_name" class="input-field" placeholder="Academy" required>
                            <span class="error-message" id="error-institute_name"></span>
                        </div>
                        <div>
                            <label class="form-label">Owner Name</label>
                            <input type="text" name="name" class="input-field" placeholder="Name" required>
                            <span class="error-message" id="error-name"></span>
                        </div>
                        <div class="form-group-full">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="input-field" placeholder="email@example.com" required>
                            <span class="error-message" id="error-email"></span>
                        </div>
                        <div>
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="input-field" placeholder="••••" required>
                            <span class="error-message" id="error-password"></span>
                        </div>
                        <div>
                            <label class="form-label">Confirm</label>
                            <input type="password" name="password_confirmation" class="input-field" placeholder="••••" required>
                        </div>
                    </div>
                    <button type="submit" class="submit-btn" id="btn1">
                        <span>Continue</span>
                        <div class="loader"></div>
                    </button>
                </form>
                <div class="footer-text" style="position: relative; z-index: 100;">
                    <p>Have an account? <a href="{{ route('institute.login') }}" style="color: #FF6B00; text-decoration: none; font-weight: 800; position: relative; z-index: 101;">Log In</a></p>
                </div>
            </div>

            <!-- STEP 2: OTP -->
            <div class="step-content {{ $initialStep == 2 ? 'active' : '' }}" id="step2">
                <div class="header-section">
                    <h2>Verify Email</h2>
                    <p>Enter code</p>
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
                    <p>Didn't receive? <a href="javascript:void(0)" onclick="resendOtp()">Resend OTP</a></p>
                </div>
            </div>

            <!-- STEP 3: SETUP -->
            <div class="step-content {{ $initialStep == 3 ? 'active' : '' }}" id="step3">
                <div class="header-section">
                    <h2>Complete Setup</h2>
                    <p>Finalize Profile</p>
                </div>
                <div class="setup-container">
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

                        <div class="form-grid">
                            <div>
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="input-field" placeholder="+91 00000 00000" required>
                                <span class="error-message" id="error-phone"></span>
                            </div>
                            <div>
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="input-field" placeholder="City" required>
                                <span class="error-message" id="error-city"></span>
                            </div>
                            <div>
                                <label class="form-label">State</label>
                                <input type="text" name="state" class="input-field" placeholder="State" required>
                                <span class="error-message" id="error-state"></span>
                            </div>
                            <div>
                                <label class="form-label">Pincode</label>
                                <input type="text" name="pincode" class="input-field" placeholder="Pincode" required>
                                <span class="error-message" id="error-pincode"></span>
                            </div>
                            <div class="form-group-full">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="input-field" placeholder="Area, Landmark" required>
                                <span class="error-message" id="error-address"></span>
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
        const timelineProgress = document.getElementById('timelineProgress');
        const circles = [document.getElementById('circle1'), document.getElementById('circle2'), document.getElementById('circle3')];
        const labels = [document.getElementById('label1'), document.getElementById('label2'), document.getElementById('label3')];
        const steps = [document.getElementById('step1'), document.getElementById('step2'), document.getElementById('step3')];

        let currentStep = {{ $initialStep ?? 1 }};

        function setStep(stepNumber) {
            currentStep = stepNumber;
            const progress = ((stepNumber - 1) / 2) * 100;
            timelineProgress.style.width = progress + '%';

            circles.forEach((c, i) => {
                const idx = i + 1;
                if (idx < stepNumber) {
                    c.className = 'step-circle step-completed';
                    c.innerHTML = '<i class="fas fa-check"></i>';
                    labels[i].className = 'step-label label-active';
                } else if (idx === stepNumber) {
                    c.className = 'step-circle step-active';
                    c.innerHTML = idx;
                    labels[i].className = 'step-label label-active';
                } else {
                    c.className = 'step-circle step-pending';
                    c.innerHTML = idx;
                    labels[i].className = 'step-label label-pending';
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
            try {
                const response = await fetch("{{ route('institute.resend-otp') }}", {
                    method: 'POST',
                    headers: { 
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                });
                const data = await response.json();
                alert(data.message);
            } catch (error) {
                alert('Failed to resend OTP.');
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
                        <div style="text-align: center; padding: 0.5rem 0;">
                            <div class="success-checkmark"><i class="fas fa-check"></i></div>
                            <h3 style="font-weight: 900; color: #1e293b; margin-bottom: 0.25rem; font-size: 1.15rem;">Done!</h3>
                            <p style="font-size: 0.75rem; color: #64748b; font-weight: 700;">Dashboard loading...</p>
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
    </script>
</body>
</html>
