<?php
include_once 'src/Config/constants.php';
session_start();
$page_title = 'Contract Management System - Login';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            font-family: "Segoe UI", sans-serif;
        }

        .min-vh-100 {
            min-height: 100vh;
        }

        /* LEFT PANEL */
        .login-panel {
            background: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* RIGHT PANEL */
        .info-panel {
            background: url("https://images.unsplash.com/photo-1544396821-4dd40b938ad3?q=80&w=1173&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D") no-repeat center center;
            background-size: cover;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info-overlay {
            position: absolute;
            inset: 0;
            background: rgba(10, 25, 47, 0.75);
        }

        .info-content {
            position: relative;
            z-index: 2;
            color: #ffffff;
            text-align: left;
            max-width: 500px;
        }

        .system-title {
            font-weight: 700;
            letter-spacing: 1px;
        }

        .button-primary {
            width: 100%;
            padding: 10px;
            background: #0d6efd;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-weight: 600;
            transition: 0.3s ease;
        }

        .button-primary:hover {
            background: #0b5ed7;
        }

        #notification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #dc3545;
            color: #fff;
            padding: 12px 20px;
            border-radius: 6px;
            z-index: 999;
        }

        @media (max-width: 768px) {
            .info-panel {
                display: none;
            }
        }
    </style>
</head>

<body>

    <?php if (isset($_SESSION['username'])): ?>
        <div id="notification">
            <?= $_SESSION['username']; ?>
        </div>
        <?php unset($_SESSION['username']); ?>
    <?php endif; ?>

    <div class="container-fluid p-0">
        <div class="row g-0 min-vh-100">

            <!-- LEFT: LOGIN (FLAT STYLE) -->
            <div class="col-md-6 login-panel">

                <div style="width: 420px;">

                    <div class="mb-5">
                        <h2 class="system-title mb-2">Contract Management System</h2>
                        <p class="text-muted">
                            Monitor contract expiration and lifecycle management securely.
                        </p>
                    </div>

                    <form action="authenticate.php" method="post">

                        <div class="mb-4">
                            <label class="form-label text-uppercase small fw-semibold">
                                Username
                            </label>
                            <input type="text" class="form-control form-control-lg border-0 border-bottom rounded-0"
                                name="username" required autofocus>
                        </div>

                        <div class="mb-4 position-relative">
                            <label class="form-label text-uppercase small fw-semibold">
                                Password
                            </label>

                            <input type="password" id="password"
                                class="form-control form-control-lg border-0 border-bottom rounded-0" name="password"
                                required>

                            <span onclick="togglePassword()"
                                style="position:absolute; top:45px; right:5px; cursor:pointer;">
                                <i id="togglePasswordIcon" class="fas fa-eye text-muted"></i>
                            </span>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="button-primary">
                                Sign In
                            </button>
                        </div>

                    </form>

                    <div class="mt-3">
                        <a href="password/index.php" class="text-decoration-none">
                            Forgot password?
                        </a>
                    </div>

                </div>

            </div>

            <!-- RIGHT: SYSTEM INFO -->
            <div class="col-md-6 info-panel">

                <div class="info-overlay"></div>

                <div class="info-content">
                    <h1 class="fw-bold mb-4">Monitor. Track. Renew.</h1>

                    <p class="lead">
                        Centralized platform for managing contract lifecycles,
                        monitoring expiration dates, and ensuring compliance.
                    </p>

                    <ul class="mt-4">
                        <li>✔ Expiration Alerts</li>
                        <li>✔ Department-Based Contract Tracking</li>
                        <li>✔ Status Monitoring & Reporting</li>
                        <li>✔ Secure Document Management</li>
                    </ul>
                </div>

            </div>

        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>

</body>

</html>