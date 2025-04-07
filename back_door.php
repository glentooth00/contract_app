<?php
$session = session_start();

$page_title  = 'Login';
// require_once __DIR__ . '/vendor/autoload.php';

// use App\Controllers\UserController;

// $userController = new UserController();
// $users = $userController->getUsers();

// echo "<pre>";
// print_r($users);
// echo "</pre>";

?>
<?php include_once 'views/layouts/includes/header.php'; ?>

<body class="bg-dark">
    <div class="container mt-5 d-flex justify-content-center">
        <div class="formHolder col-md-3 bg-white p-4 rounded mt-5">
            <div class="d-flex justify-content-center">
                <h2 class="text-dark">Login</h2>
            </div>
            <form action="authenticate.php" method="post">
                <div class="col-12">
                    <label class="badge text-muted">Username</label>
                    <input type="text" class="form-control" name="username" placeholder="Enter username" required>
                    <p class="notifMsg p-1">
                        <?php if (isset($_SESSION['username'])): ?>
                            <?= $_SESSION['username']; ?>
                            <?php unset($_SESSION['username']); ?> <!-- Unset session variable after displaying it -->
                        <?php endif; ?>
                    </p>

                </div>
                <div class="password col-12">
                    <label class="badge text-muted">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Enter password" required>
                    <p class="notifMsg p-1">
                        <?php if (isset($_SESSION['password'])): ?>
                            <?= $_SESSION['password']; ?>
                            <?php unset($_SESSION['password']); ?> <!-- Unset session variable after displaying it -->
                        <?php endif; ?>
                    </p>

                </div>
                <div class="col-12 mb-1 mt-4">
                    <!-- <label class="badge text-muted">Password</label> -->
                    <button type="submit" class="btn btn-success btn-block w-100" name="submit">Login</button>

                </div>
            </form>
        </div>
    </div>
</body>

<style>
    .notifMsg{
        font-size: 13px;
        color: red;
        font-weight: 500;
    }
    .password{
        margin-top: -6%;
    }
</style>