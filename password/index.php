<?php
session_start();
$page_title = 'Contract Management System - Request Password Change';
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
        html, body {
            height: 100%;
            margin: 0;
            font-family: "Segoe UI", sans-serif;
        }

        .min-vh-100 {
            min-height: 100vh;
        }

        /* LEFT PANEL: FORM */
        .login-panel {
            background: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            width: 420px;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: rgba(0,0,0,0.08) 0px 4px 20px;
        }

        .system-title {
            font-weight: 700;
            letter-spacing: 1px;
        }

        .form-control {
            border: 0;
            border-bottom: 2px solid #dfe3e8;
            border-radius: 0;
            padding: 0.5rem;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: none;
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

        .notifMsg {
            font-size: 13px;
            color: red;
            font-weight: 500;
            margin-top: 4px;
        }

        /* RIGHT PANEL: INFO */
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

        .info-content h1 {
            font-weight: 700;
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
        <div id="notification" class="alert alert-danger position-fixed top-0 start-50 translate-middle-x mt-3 z-index-999">
            <?= $_SESSION['username']; unset($_SESSION['username']); ?>
        </div>
    <?php endif; ?>

    <div class="container-fluid p-0">
        <div class="row g-0 min-vh-100">

            <!-- LEFT PANEL: FORM -->
            <div class="col-md-6 login-panel">
                <div class="login-card">
                    <div class="mb-4 text-center">
                        <h2 class="system-title mb-2">Request Password Change</h2>
                        <p class="text-muted">Enter your username to request a password reset securely.</p>
                    </div>

                    <form action="changeRequest.php" method="post">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Username</label>
                            <input type="text" class="form-control" name="username" required autofocus>
                            <?php if (isset($_SESSION['username'])): ?>
                                <div class="notifMsg"><?= $_SESSION['username']; unset($_SESSION['username']); ?></div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="button-primary mt-3">Request Password Change</button>
                    </form>

                    <div class="mt-3 text-center">
                        <a href="../index.php" class="text-decoration-none">Back to Login</a>
                    </div>
                </div>
            </div>

            <!-- RIGHT PANEL: INFO -->
            <div class="col-md-6 info-panel">
                <div class="info-overlay"></div>
                <div class="info-content">
                    <h1>Secure. Fast. Simple.</h1>
                    <p class="lead mt-3">
                        Use the Contract Management System to safely manage your password and maintain access to your account.
                    </p>
                    <ul class="mt-3">
                        <li>✔ Quick Password Reset Requests</li>
                        <li>✔ Secure Verification</li>
                        <li>✔ Access Recovery Notifications</li>
                        <li>✔ Centralized Account Management</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Optional: fade out notification
        setTimeout(() => {
            const notif = document.getElementById('notification');
            if (notif) notif.style.display = 'none';
        }, 5000);
    </script>

</body>

</html>