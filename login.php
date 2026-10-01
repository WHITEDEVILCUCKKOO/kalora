<?php

session_start();

if(isset($_SESSION['user_id'])){

    header("Location: admin.php");
    exit();

}

// Agar login nahi hai to niche login page ka HTML chalega

?>
<?php include"admin_access/db_config.php" ?>

<?php


if(isset($_POST['login_main'])){

    $password = mysqli_real_escape_string($mydb, $_POST['password']);
    $email    = mysqli_real_escape_string($mydb, $_POST['email']);

    $check_sql = "SELECT * FROM login_users 
                  WHERE user_email = '$email' 
                  AND user_password = '$password'";

    $check_res = mysqli_query($mydb, $check_sql);

    if(!$check_res){
        die("Database Error: " . mysqli_error($mydb));
    }

    if(mysqli_num_rows($check_res) > 0){

        $user = mysqli_fetch_assoc($check_res);

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['user_email'];

        header("Location: admin.php");
        exit();

    } else {

        echo "<script>
                alert('Wrong Email or Password Please Try Again 😑');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #ffffff;
            --bg-subtle: #f8fafc;
            --fg: #0f172a;
            --muted: #64748b;
            --accent: #0066ff;
            --accent-light: #e6f0ff;
            --border: #e2e8f0;
            --error: #ef4444;
            --success: #10b981;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            overflow: hidden;
        }

        /* Floating shapes */
        .floating-shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.6;
            pointer-events: none;
        }

        .shape-1 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, rgba(0, 102, 255, 0.1), rgba(0, 102, 255, 0.05));
            top: -100px;
            right: -50px;
            animation: float1 20s ease-in-out infinite;
        }

        .shape-2 {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, rgba(0, 102, 255, 0.08), rgba(0, 102, 255, 0.02));
            bottom: -50px;
            left: -50px;
            animation: float2 15s ease-in-out infinite;
        }

        .shape-3 {
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, rgba(0, 102, 255, 0.06), transparent);
            top: 50%;
            left: 10%;
            animation: float3 18s ease-in-out infinite;
        }

        @keyframes float1 {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(-30px, 30px) rotate(10deg); }
        }

        @keyframes float2 {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(20px, -20px) rotate(-10deg); }
        }

        @keyframes float3 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(10px, 20px) scale(1.1); }
        }

        /* Grid pattern */
        .grid-pattern {
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(rgba(0, 102, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 102, 255, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            animation: gridMove 30s linear infinite;
        }

        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(60px, 60px); }
        }

        /* Login card */
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            box-shadow: 
                0 4px 6px -1px rgba(0, 0, 0, 0.05),
                0 20px 50px -12px rgba(0, 102, 255, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            box-shadow: 
                0 4px 6px -1px rgba(0, 0, 0, 0.05),
                0 25px 60px -15px rgba(0, 102, 255, 0.2);
        }

        /* Form elements */
        .input-group {
            position: relative;
        }

        .input-field {
            width: 100%;
            padding: 16px 16px 16px 48px;
            border: 2px solid var(--border);
            border-radius: 12px;
            font-size: 16px;
            font-family: inherit;
            background: var(--bg);
            color: var(--fg);
            transition: all 0.3s ease;
            outline: none;
        }

        .input-field:focus {
            border-color: var(--accent);
            background: var(--bg);
            box-shadow: 0 0 0 4px var(--accent-light);
        }

        .input-field::placeholder {
            color: var(--muted);
            opacity: 0.7;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            transition: color 0.3s ease;
            pointer-events: none;
        }

        .input-field:focus + .input-icon {
            color: var(--accent);
        }

        /* Button */
        .btn-primary {
            position: relative;
            width: 100%;
            padding: 16px 32px;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-primary:hover {
            background: #0052cc;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px -10px rgba(0, 102, 255, 0.5);
        }

        .btn-primary:hover::before {
            opacity: 1;
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary:focus-visible {
            outline: 3px solid var(--accent-light);
            outline-offset: 2px;
        }

        /* Loader */
        .btn-loader {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        .btn-primary.loading .btn-text {
            opacity: 0;
        }

        .btn-primary.loading .btn-loader {
            display: block;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        @keyframes spin {
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }

        /* Password toggle */
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            padding: 4px;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--accent);
        }

        /* Checkbox */
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .checkbox-custom {
            width: 20px;
            height: 20px;
            border: 2px solid var(--border);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .checkbox-wrapper input:checked + .checkbox-custom {
            background: var(--accent);
            border-color: var(--accent);
        }

        .checkbox-custom svg {
            opacity: 0;
            transform: scale(0.5);
            transition: all 0.2s ease;
        }

        .checkbox-wrapper input:checked + .checkbox-custom svg {
            opacity: 1;
            transform: scale(1);
        }

        /* Links */
        .link {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent);
            transition: width 0.3s ease;
        }

        .link:hover::after {
            width: 100%;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 16px;
            color: var(--muted);
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* Social buttons */
        .btn-social {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 20px;
            border: 2px solid var(--border);
            border-radius: 12px;
            background: var(--bg);
            color: var(--fg);
            font-size: 14px;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-social:hover {
            border-color: var(--accent);
            background: var(--accent-light);
        }

        /* Animations */
        .fade-in-up {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }
        .delay-6 { animation-delay: 0.6s; }

        /* Success state */
        .success-overlay {
            position: absolute;
            inset: 0;
            background: var(--bg);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.5s ease;
            border-radius: inherit;
        }

        .success-overlay.show {
            opacity: 1;
            pointer-events: auto;
        }

        .success-checkmark {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--success);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: scaleIn 0.5s ease forwards;
        }

        @keyframes scaleIn {
            from { transform: scale(0); }
            to { transform: scale(1); }
        }

        /* Focus visible for accessibility */
        *:focus-visible {
            outline: 3px solid var(--accent);
            outline-offset: 2px;
        }

        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                margin: 16px;
                padding: 32px 24px;
            }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <!-- Background elements -->
    <div class="grid-pattern"></div>
    <div class="floating-shape shape-1"></div>
    <div class="floating-shape shape-2"></div>
    <div class="floating-shape shape-3"></div>

    <!-- Login Card -->
    <div class="login-card w-full max-w-md rounded-2xl p-10 relative">
        <!-- Success Overlay -->
        <div class="success-overlay" id="successOverlay">
            <div class="success-checkmark">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <p class="mt-6 text-lg font-semibold text-gray-800">Login Successful</p>
            <p class="mt-2 text-sm text-gray-500">Redirecting...</p>
        </div>

        <!-- Header -->
        <div class="text-center mb-8 fade-in-up">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mx-auto mb-5 shadow-lg shadow-blue-500/20">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                    <polyline points="10 17 15 12 10 7"/>
                    <line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Welcome back</h1>
            <p class="text-gray-500">Sign in to your account to continue</p>
        </div>

        <!-- Form -->
        <form id="loginForm" method="post" class="space-y-5">
            <!-- Email -->
            <div class="input-group fade-in-up delay-1">
                <input 
                    type="email" 
                    id="email"
                    class="input-field" 
                    placeholder="Email address"
                    name="email"
                    required
                    autocomplete="email"
                    aria-label="Email address"
                >
                <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
            </div>

            <!-- Password -->
            <div class="input-group fade-in-up delay-2">
                <input 
                    type="password" 
                    id="password"
                    name="password"
                    class="input-field pr-12" 
                    placeholder="Password"
                    required
                    autocomplete="current-password"
                    aria-label="Password"
                >
                <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                <button type="button" class="password-toggle" id="togglePassword" aria-label="Toggle password visibility">
                    <svg id="eyeIcon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>

            <!-- Remember & Forgot -->
            <div class="flex items-center justify-between fade-in-up delay-3">
                <label class="checkbox-wrapper">
                    <input type="checkbox" class="sr-only" id="remember">
                    <div class="checkbox-custom">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <span class="text-sm text-gray-600">Remember me</span>
                </label>
                <a href="#" class="link text-sm">Forgot password?</a>
            </div>

            <!-- Submit -->
            <button type="submit" name="login_main" class="btn-primary fade-in-up delay-4" id="submitBtn">
                <span class="btn-text">Sign in</span>
                <div class="btn-loader"></div>
            </button>
        </form>

        <!-- Divider -->
        <!-- <div class="divider my-8 fade-in-up delay-5">
            <span class="text-sm">or continue with</span>
        </div> -->

        <!-- Social Login -->
        <!-- <div class="flex gap-4 fade-in-up delay-6">
            <button type="button" class="btn-social" aria-label="Sign in with Google">
                <svg width="18" height="18" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Google
            </button>
            <button type="button" class="btn-social" aria-label="Sign in with Apple">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                </svg>
                Apple
            </button>
        </div> -->

        <!-- Sign up link -->
        <!-- <p class="text-center mt-8 text-gray-600 fade-in-up delay-6">
            Don't have an account? 
            <a href="#" class="link">Create one</a>
        </p> -->
    </div>

    <script>
        // DOM Elements
        const form = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');
        const submitBtn = document.getElementById('submitBtn');
        const successOverlay = document.getElementById('successOverlay');

        // Password visibility toggle
        togglePassword.addEventListener('click', () => {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            
            // Toggle icon
            if (type === 'text') {
                eyeIcon.innerHTML = `
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                    <line x1="1" y1="1" x2="23" y2="23"/>
                `;
            } else {
                eyeIcon.innerHTML = `
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                `;
            }
        });

        // Form submission
        // form.addEventListener('submit', async (e) => {
        //     e.preventDefault();
            
        //     // Add loading state
        //     submitBtn.classList.add('loading');
        //     submitBtn.disabled = true;

        //     // Simulate API call
        //     await new Promise(resolve => setTimeout(resolve, 1500));

        //     // Show success
        //     submitBtn.classList.remove('loading');
        //     successOverlay.classList.add('show');

        //     // Reset after delay
        //     setTimeout(() => {
        //         successOverlay.classList.remove('show');
        //         submitBtn.disabled = false;
        //         form.reset();
        //     }, 3000);
        // });

        // Input animations
        const inputs = document.querySelectorAll('.input-field');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('focused');
            });
            input.addEventListener('blur', () => {
                input.parentElement.classList.remove('focused');
            });
        });

        // Parallax effect on floating shapes
        document.addEventListener('mousemove', (e) => {
            const shapes = document.querySelectorAll('.floating-shape');
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            
            shapes.forEach((shape, index) => {
                const speed = (index + 1) * 10;
                const xOffset = (x - 0.5) * speed;
                const yOffset = (y - 0.5) * speed;
                shape.style.transform = `translate(${xOffset}px, ${yOffset}px)`;
            });
        });

        // Keyboard accessibility for social buttons
        document.querySelectorAll('.btn-social').forEach(btn => {
            btn.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    btn.click();
                }
            });
        });
    </script>
</body>
</html>