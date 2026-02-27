<!DOCTYPE html>
<!-- DEPLOYMENT_VERSION: 1.0.2 -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NetIssue | Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Inter', sans-serif;
            overflow: hidden;
        }

        .bg-image {
            background-image: url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .bg-image::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 41, 59, 0.4) 100%);
        }

        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            z-index: 10;
            color: white;
            animation: fadeIn 1.1s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand-logo {
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: -1px;
            background: linear-gradient(to right, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 5px;
        }

        .input-group {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            overflow: hidden;
            transition: 0.3s;
        }

        .input-group:focus-within {
            background: rgba(255, 255, 255, 0.15);
            border-color: #60a5fa;
            box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.2);
        }

        .form-control {
            background: transparent !important;
            border: none !important;
            color: white !important;
            padding: 12px 15px;
            box-shadow: none !important;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .input-group-text {
            background: transparent !important;
            border: none !important;
            color: rgba(255, 255, 255, 0.5);
            padding-right: 15px;
            padding-left: 15px;
        }

        .password-toggle {
            cursor: pointer;
            transition: 0.2s;
        }

        .password-toggle:hover {
            color: #60a5fa !important;
        }

        .btn-login {
            background: linear-gradient(to right, #3b82f6, #8b5cf6);
            border: none;
            border-radius: 15px;
            padding: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
            margin-top: 20px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.5);
        }

        .register-section {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-text {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.4);
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="bg-image">
    <div class="login-card text-center">
        <div class="brand-logo"><i class="bi bi-rocket-takeoff"></i></div>
        <h3 class="fw-bold mb-1">NetIssue</h3>
        <p class="small text-white-50 mb-4">Support Ticketing System</p>

        <form action="login_process.php" method="POST">
            <div class="mb-3 text-start">
                <label class="form-label small fw-bold opacity-75">USERNAME</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                    <span class="input-group-text" style="visibility: hidden;"><i class="bi bi-eye"></i></span>
                </div>
            </div>

            <div class="mb-3 text-start">
                <label class="form-label small fw-bold opacity-75">PASSWORD</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="passwordInput" class="form-control" placeholder="••••••••" required>
                    <span class="input-group-text password-toggle" id="toggleBtn">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            <button type="submit" name="login" class="btn btn-primary w-100 btn-login">
                Sign In <i class="bi bi-arrow-right ms-2"></i>
            </button>
        </form>

        <div style="margin: 25px 0 15px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
            <p style="color: rgba(255,255,255,0.6); font-size: 0.85rem; margin-bottom: 8px;">
                Belum ada akaun lagi?
            </p>
            <a href="register.php" class="btn btn-outline-info btn-sm w-100" style="border-radius: 12px; font-weight: 700; letter-spacing: 0.5px; transition: 0.3s;">
                CREATE NEW ACCOUNT <i class="bi bi-person-plus ms-1"></i>
            </a>
        </div>

        <div class="footer-text">
            &copy; 2026 Diploma IT Networking Project<br>
            Secure Terminal Access
        </div>
    </div>
</div>

<script>
    const toggleBtn = document.querySelector('#toggleBtn');
    const passwordInput = document.querySelector('#passwordInput');
    const eyeIcon = document.querySelector('#eyeIcon');

    toggleBtn.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        eyeIcon.classList.toggle('bi-eye');
        eyeIcon.classList.toggle('bi-eye-slash');
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>