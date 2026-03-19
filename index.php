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

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>

<body class="h-screen bg-gray-100 font-sans">

<?php if (isset($_SESSION['username'])): ?>
    <div class="fixed top-5 left-1/2 -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <?= $_SESSION['username']; ?>
    </div>
    <?php unset($_SESSION['username']); ?>
<?php endif; ?>

<div class="flex h-full">

    <!-- LEFT: LOGIN -->
    <div class="w-full md:w-1/2 bg-white flex items-center justify-center px-6">

        <div class="w-full max-w-md">

            <!-- Title -->
            <div class="mb-10">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">
                    Contract Management System
                </h2>
                <p class="text-gray-500">
                    Monitor contract expiration and lifecycle management securely.
                </p>
            </div>

            <!-- Form -->
            <form action="authenticate.php" method="post" class="space-y-6">

                <!-- Username -->
                <div>
                    <label class="text-xs uppercase font-semibold text-gray-500">
                        Username
                    </label>
                    <input type="text" name="username" required autofocus
                        class="w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none py-2 bg-transparent">
                </div>

                <!-- Password -->
                <div class="relative">
                    <label class="text-xs uppercase font-semibold text-gray-500">
                        Password
                    </label>

                    <input type="password" id="password" name="password" required
                        class="w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none py-2 bg-transparent">

                    <span onclick="togglePassword()"
                        class="absolute right-0 top-8 cursor-pointer text-gray-400 hover:text-gray-600">
                        <i id="togglePasswordIcon" class="fas fa-eye"></i>
                    </span>
                </div>

                <!-- Button -->
                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition duration-200 shadow-md">
                    Sign In
                </button>

            </form>

            <!-- Forgot -->
            <div class="mt-4">
                <a href="password/index.php" class="text-sm text-indigo-600 hover:underline">
                    Forgot password?
                </a>
            </div>

        </div>
    </div>

    <!-- RIGHT: INFO -->
    <div class="hidden md:flex w-1/2 relative items-center justify-center">

        <!-- Background -->
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1544396821-4dd40b938ad3?q=80&w=1173&auto=format&fit=crop"
                 class="w-full h-full object-cover">
        </div>

        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/80 to-black/70"></div>

        <!-- Content -->
        <div class="relative z-10 text-white max-w-lg px-10">

            <h1 class="text-4xl font-bold mb-6">
                Monitor. Track. Renew.
            </h1>

            <p class="text-lg text-gray-200">
                Centralized platform for managing contract lifecycles,
                monitoring expiration dates, and ensuring compliance.
            </p>

            <ul class="mt-6 space-y-2 text-gray-200">
                <li>✔ Expiration Alerts</li>
                <li>✔ Department-Based Contract Tracking</li>
                <li>✔ Status Monitoring & Reporting</li>
                <li>✔ Secure Document Management</li>
            </ul>

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