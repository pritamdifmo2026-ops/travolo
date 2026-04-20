<?php
include_once 'includes/auth.php';
if (is_logged_in()) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Travolo</title>
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Social Login SDKs -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
    <style>
        :root {
            --primary-color: #133a25;
            --accent-color: #F7921E;
            --text-main: #2d3748;
            --text-sub: #718096;
            --bg-gradient: linear-gradient(135deg, #f6f9fc 0%, #eef2f7 100%);
        }

        body {
            background: var(--bg-gradient);
            font-family: 'Prompt', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            perspective: 1000px;
        }

        .login-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            position: relative;
            z-index: 1;
            transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .back-to-home {
            position: absolute;
            top: 20px;
            left: 20px;
            color: var(--text-sub);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: color 0.3s;
        }

        .back-to-home:hover {
            color: var(--accent-color);
        }

        .logo-wrapper {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-wrapper img {
            max-width: 180px;
            height: auto;
        }

        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .login-header h2 {
            font-size: 28px;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .login-header p {
            color: var(--text-sub);
            font-size: 16px;
        }

        .form-group-custom {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 8px;
            display: block;
        }

        .form-control-custom {
            width: 100%;
            padding: 14px 20px;
            padding-left: 50px;
            border: 2px solid #edf2f7;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
            outline: none;
            background: #f8fafc;
        }

        .form-control-custom:focus {
            border-color: var(--accent-color);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(247, 146, 30, 0.1);
        }

        .form-icon {
            position: absolute;
            left: 20px;
            top: 48px;
            color: var(--text-sub);
            transition: color 0.3s;
        }

        .form-control-custom:focus + .form-icon {
            color: var(--accent-color);
        }

        .btn-premium {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 12px;
            background: var(--primary-color);
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 10px 20px rgba(19, 58, 37, 0.2);
        }

        .btn-premium:hover {
            background: #F7921E;
            box-shadow: 0 10px 20px rgba(247, 146, 30, 0.3);
            transform: translateY(-2px);
        }

        .btn-premium:active {
            transform: translateY(0);
        }

        .otp-display {
            display: none;
        }

        .otp-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 30px;
        }

        .otp-digit {
            width: 50px;
            height: 50px;
            border: 2px solid #edf2f7;
            border-radius: 10px;
            text-align: center;
            font-size: 22px;
            font-weight: 800;
            color: var(--primary-color);
            background: #f8fafc;
            transition: all 0.3s;
        }

        .otp-digit:focus {
            border-color: var(--accent-color);
            background: #fff;
            outline: none;
            box-shadow: 0 0 0 4px rgba(247, 146, 30, 0.1);
        }

        .resend-link {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: var(--text-sub);
        }

        .resend-link a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 700;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card {
            animation: fadeIn 0.8s ease-out;
        }

        .limit-reached-alert {
            background: #fff5f5;
            border: 1px solid #feb2b2;
            color: #c53030;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <a href="index.php" class="back-to-home"><i class="fas fa-arrow-left me-2"></i>Home</a>
        
        <div class="logo-wrapper">
            <img src="assets/images/logo1.png" alt="Travolo Logo">
        </div>

        <div id="phoneSection">
            <div class="login-header">
                <h2>Welcome to Travolo</h2>
                <p>Login to book your premium cab experience.</p>
            </div>

            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'limit_reached'): ?>
                <div class="limit-reached-alert">
                    <i class="fas fa-exclamation-circle"></i>
                    You've reached your free search limit. Please login to continue.
                </div>
            <?php endif; ?>

            <form id="phoneForm">
                <input type="hidden" name="action" value="send_otp">
                
                <div class="form-group-custom">
                    <label class="form-label">Full Name</label>
                    <input type="text" id="userName" name="name" class="form-control-custom" placeholder="John Doe" required>
                    <i class="fas fa-user form-icon" style="top: 48px;"></i>
                </div>

                <div class="form-group-custom">
                    <label class="form-label">Email Address</label>
                    <input type="email" id="userEmail" name="email" class="form-control-custom" placeholder="you@example.com" required>
                    <i class="fas fa-envelope form-icon" style="top: 48px;"></i>
                </div>

                <div class="form-group-custom">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" id="mobileNumber" name="phone" class="form-control-custom" placeholder="Enter 10 Digit Mobile" value="<?php echo htmlspecialchars($_GET['phone'] ?? ''); ?>" required pattern="[6-9][0-9]{9}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" title="Please enter a valid 10-digit mobile number">
                    <i class="fas fa-phone form-icon" style="top: 48px;"></i>
                </div>

                <button type="submit" class="btn-premium mb-4" id="sendOtpBtn">
                    Get OTP <i class="fas fa-paper-plane"></i>
                </button>

                <!-- Social Login Section -->
                <div class="text-center mb-4">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;">
                        <hr style="flex:1; border-color:#eee;">
                        <span style="font-size:12px; color:#999; font-weight:700; text-transform:uppercase;">Or Continue With</span>
                        <hr style="flex:1; border-color:#eee;">
                    </div>
                    
                    <div class="d-flex gap-3 justify-content-center">
                        <div id="googleBtn" class="shadow-sm"></div>
                        <button type="button" onclick="loginFB()" class="btn btn-outline-light shadow-sm" style="width:40px; height:40px; border-radius:50%; border:1px solid #eee; display:flex; align-items:center; justify-content:center; padding:0; background:#1877F2; color:#fff;">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div id="otpSection" class="otp-display">
            <div class="login-header">
                <h2>Verify OTP</h2>
                <p>We've sent a 6-digit code to <span id="displayPhone" class="fw-bold text-dark"></span></p>
            </div>

            <form id="otpForm">
                <input type="hidden" name="action" value="verify_otp">
                <input type="hidden" name="phone" id="otpPhone">
                <div class="otp-inputs">
                    <input type="text" class="otp-digit" maxlength="1" pattern="\d*" inputmode="numeric">
                    <input type="text" class="otp-digit" maxlength="1" pattern="\d*" inputmode="numeric">
                    <input type="text" class="otp-digit" maxlength="1" pattern="\d*" inputmode="numeric">
                    <input type="text" class="otp-digit" maxlength="1" pattern="\d*" inputmode="numeric">
                    <input type="text" class="otp-digit" maxlength="1" pattern="\d*" inputmode="numeric">
                    <input type="text" class="otp-digit" maxlength="1" pattern="\d*" inputmode="numeric">
                </div>
                <input type="hidden" name="otp" id="finalOtp">
                
                <button type="submit" class="btn-premium">
                    Verify & Login <i class="fas fa-check-circle"></i>
                </button>

                <div class="resend-link">
                    Didn't receive code? <a href="#" id="resendOtp">Resend Now</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // OTP Input Navigation
    const digits = document.querySelectorAll('.otp-digit');
    digits.forEach((digit, index) => {
        digit.addEventListener('input', (e) => {
            if (e.target.value.length === 1 && index < digits.length - 1) {
                digits[index + 1].focus();
            }
        });
        digit.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && e.target.value.length === 0 && index > 0) {
                digits[index - 1].focus();
            }
        });
    });

    // Form Handling
    const phoneForm = document.getElementById('phoneForm');
    const otpSection = document.getElementById('otpSection');
    const phoneSection = document.getElementById('phoneSection');
    const otpForm = document.getElementById('otpForm');

    phoneForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const phone = document.getElementById('mobileNumber').value;
        
        if (phone.length < 10) {
             Swal.fire('Error', 'Please enter a valid phone number', 'error');
             return;
        }

        Swal.fire({
            title: 'Sending OTP...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch('api/auth_handler.php', {
            method: 'POST',
            body: new FormData(this)
        })
        .then(res => res.json())
        .then(data => {
            Swal.close();
            if (data.status === 'success') {
                document.getElementById('displayPhone').innerText = phone;
                document.getElementById('otpPhone').value = phone;
                phoneSection.classList.add('otp-display'); // Hide phone section
                otpSection.style.display = 'block'; // Show OTP section
                
                // For Demo: Show OTP in alert
                Swal.fire({
                    icon: 'info',
                    title: 'OTP Sent',
                    text: 'For demo purpose, your OTP is: ' + data.otp,
                    confirmButtonColor: '#F7921E'
                });
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(() => Swal.fire('Error', 'Network or server error', 'error'));
    });

    otpForm.addEventListener('submit', function(e) {
        e.preventDefault();
        let otp = "";
        digits.forEach(d => otp += d.value);
        
        if (otp.length < 6) {
            Swal.fire('Error', 'Please enter 6 digit OTP', 'error');
            return;
        }
        
        document.getElementById('finalOtp').value = otp;

        Swal.fire({
            title: 'Verifying...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch('api/auth_handler.php', {
            method: 'POST',
            body: new FormData(this)
        })
        .then(res => res.json())
        .then(data => {
            Swal.close();
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Welcome!',
                    text: 'Login successful',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    const params = new URLSearchParams(window.location.search);
                    const returnUrl = params.get('return_url') || 'index.php';
                    window.location.href = returnUrl;
                });
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(() => Swal.fire('Error', 'Verification failed', 'error'));
    });

    // SOCIAL LOGIN LOGIC
    // ==================
    
    // 1. Common Social Auth Handler
    window.handleSocialAuth = function(data) {
        Swal.fire({ title: 'Authenticating...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
        
        const formData = new FormData();
        formData.append('action', 'social_login');
        formData.append('email', data.email);
        formData.append('name', data.name);
        formData.append('social_id', data.social_id);
        formData.append('social_type', data.social_type);
        if (data.phone) formData.append('phone', data.phone);

        fetch('api/auth_handler.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(res => {
            Swal.close();
            if (res.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Welcome!', text: 'Login successful', timer: 1500, showConfirmButton: false }).then(() => {
                    const params = new URLSearchParams(window.location.search);
                    window.location.href = params.get('return_url') || 'index.php';
                });
            } else if (res.status === 'require_phone') {
                Swal.fire({
                    title: 'One last thing!',
                    text: 'Please enter your mobile number to complete your registration.',
                    input: 'tel',
                    inputPlaceholder: 'Enter 10 Digit Mobile',
                    showCancelButton: true,
                    confirmButtonColor: '#F7921E',
                    preConfirm: (phone) => {
                        if (!/^[6-9][0-9]{9}$/.test(phone)) {
                            Swal.showValidationMessage('Invalid mobile number');
                        }
                        return phone;
                    }
                }).then((pResult) => {
                    if (pResult.isConfirmed) {
                        handleSocialAuth({ ...data, phone: pResult.value });
                    }
                });
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        });
    };

    // 2. Google Login Callback
    window.handleCredentialResponse = (response) => {
        const base64Url = response.credential.split('.')[1];
        const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
        const payload = JSON.parse(window.atob(base64));
        
        handleSocialAuth({
            email: payload.email,
            name: payload.name,
            social_id: payload.sub,
            social_type: 'Google'
        });
    };

    // 3. Facebook Login Function
    window.loginFB = () => {
        FB.login(function(response) {
            if (response.status === 'connected') {
                FB.api('/me', {fields: 'name,email'}, function(userData) {
                    handleSocialAuth({
                        email: userData.email,
                        name: userData.name,
                        social_id: userData.id,
                        social_type: 'Facebook'
                    });
                });
            }
        }, {scope: 'public_profile,email'});
    };

    window.addEventListener('load', function() {
        // Init Google
        if (window.google) {
            google.accounts.id.initialize({
                client_id: "your_google_client_id_here.apps.googleusercontent.com", 
                callback: handleCredentialResponse,
                auto_select: false
            });
            google.accounts.id.renderButton(
                document.getElementById("googleBtn"),
                { theme: "outline", size: "large", shape: "pill", type: "icon" }
            );
        }

        // Init Facebook
        if (window.FB) {
            FB.init({
              appId      : 'your_fb_app_id_here',
              cookie     : true,
              xfbml      : true,
              version    : 'v18.0'
            });
        }

        // Auto-hide limit alert and clean URL
        const limitAlert = document.querySelector('.limit-reached-alert');
        if (limitAlert) {
            setTimeout(() => limitAlert.style.display = 'none', 3000);
        }
        if (window.history.replaceState) {
            const url = new URL(window.location);
            if (url.searchParams.has('msg')) {
                url.searchParams.delete('msg');
                window.history.replaceState({}, '', url);
            }
        }
    });

</script>

</body>
</html>
