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

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>

<body class="h-screen bg-gray-100 font-sans">

<?php if (isset($_SESSION['username'])): ?>
    <div id="notification" class="fixed top-5 left-1/2 -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <?= $_SESSION['username']; unset($_SESSION['username']); ?>
    </div>
<?php endif; ?>

<div class="flex h-full">

    <!-- LEFT: FORM -->
    <div class="w-full md:w-1/2 bg-white flex items-center justify-center px-6">

        <div class="w-full max-w-md">

            <!-- Title -->
            <div class="mb-10 text-center">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">
                    Request Password Change
                </h2>
                <p class="text-gray-500">
                    Enter your username to request a password reset securely.
                </p>
            </div>

            <!-- Form -->
            <form action="changeRequest.php" method="post" class="space-y-6">

                <div>
                    <label class="text-xs uppercase font-semibold text-gray-500">
                        Username
                    </label>

                    <input type="text" name="username" required autofocus
                        class="w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none py-2 bg-transparent">

                    <?php if (isset($_SESSION['username'])): ?>
                        <p class="text-red-500 text-sm mt-2">
                            <?= $_SESSION['username']; unset($_SESSION['username']); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition duration-200 shadow-md">
                    Request Password Change
                </button>

            </form>

            <!-- Back -->
            <div class="mt-4 text-center">
                <a href="../index.php" class="text-sm text-indigo-600 hover:underline">
                    ← Back to Login
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
                Secure. Fast. Simple.
            </h1>

            <p class="text-lg text-gray-200">
                Use the Contract Management System to safely manage your password
                and maintain access to your account.
            </p>

            <ul class="mt-6 space-y-2 text-gray-200">
                <li>✔ Quick Password Reset Requests</li>
                <li>✔ Secure Verification</li>
                <li>✔ Access Recovery Notifications</li>
                <li>✔ Centralized Account Management</li>
            </ul>

        </div>
    </div>

</div>

<script>
setTimeout(() => {
    const notif = document.getElementById('notification');
    if (notif) notif.style.display = 'none';
}, 5000);
</script>

</body>

</html>