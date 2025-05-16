<?php
$session = session_start();

$page_title = 'Login';
// require_once __DIR__ . '/vendor/autoload.php';

// use App\Controllers\UserController;

// $userController = new UserController();
// $users = $userController->getUsers();

// echo "<pre>";
// print_r($users);
// echo "</pre>";

?>
<?php include_once '../views/layouts/includes/header.php'; ?>

<body class="">
    <div class="container d-flex justify-content-center" style="margin-top:11em;margin-bottom:20em;">
        <div class="formHolder card col-md-5 bg-white p-4 rounded mt-5">
            <div class="d-flex justify-content-center mb-1">
                <div>
                    <img width="60px" src="../public/images/changepass.svg">
                </div>
                <div>
                    <h1 class="text-dark p-2">Change Password</h1>
                </div>

            </div>
            <form action="changeRequest.php" method="post" class="d-flex justify-content-center">
                <div class="w-50">
                    <div class="mb-1 mt-4 text-center">
                        <input type="text" class="form-control" name="username" placeholder="Enter username" required
                            autofocus>
                        <p class="notifMsg p-1 text-danger">
                            <?php if (isset($_SESSION['username'])): ?>
                                <?= $_SESSION['username']; ?>
                                <?php unset($_SESSION['username']); ?>
                            <?php endif; ?>
                        </p>
                    </div>

                    <div class="mb-3 text-center">
                        <!-- <input type="password" id="password" class="form-control" name="password"
                            placeholder="Enter password" required> -->
                        <!-- <div class="form-check mt-2 text-start">
                            <input type="checkbox" class="form-check-input" onclick="checkPassword()" id="showPassword">
                            <label class="form-check-label text-muted" for="showPassword">Show password</label>
                        </div> -->
                        <p class="notifMsg p-1 text-danger">
                            <?php if (isset($_SESSION['password'])): ?>
                                <?= $_SESSION['password']; ?>
                                <?php unset($_SESSION['password']); ?>
                            <?php endif; ?>
                        </p>
                    </div>

                    <div class="mb-1">
                        <button type="submit" class="btn btn-success w-100" name="submit">Request Change
                            password</button>
                    </div>
                </div>
            </form>
            <div class="mt-3 forgotPassword p-1">
                <a href="../index.php" class="button">Login</a>
            </div>
        </div>
    </div>

    <?php include_once '../views/layouts/includes/footer.php'; ?>

    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path
                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
        </symbol>
        <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
            <path
                d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
        </symbol>
        <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path
                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
        </symbol>
    </svg>


    <?php if (isset($_SESSION['notification'])): ?>
        <div id="notification"
            class="alert <?php echo ($_SESSION['notification']['type'] == 'success') ? 'alert-success border-success' : ($_SESSION['notification']['type'] == 'warning' ? 'alert-warning border-warning' : 'alert-danger border-danger'); ?> d-flex align-items-center float-end alert-dismissible fade show"
            role="alert" style="position: absolute; bottom: 5em; right: 10px; z-index: 1000; margin-bottom: -4em;">
            <!-- Icon -->
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                aria-label="<?php echo ($_SESSION['notification']['type'] == 'success') ? 'Success' : ($_SESSION['notification']['type'] == 'warning' ? 'Warning' : 'Error'); ?>:">
                <use
                    xlink:href="<?php echo ($_SESSION['notification']['type'] == 'success') ? '#check-circle-fill' : ($_SESSION['notification']['type'] == 'warning' ? '#exclamation-triangle-fill' : '#exclamation-circle-fill'); ?>" />
            </svg>
            <!-- Message -->
            <div>
                <?php echo $_SESSION['notification']['message']; ?>
            </div>
            <!-- Close Button -->
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['notification']); // Clear notification after displaying ?>

        <script>
            // Automatically fade the notification out after 6 seconds
            setTimeout(function () {
                let notification = document.getElementById('notification');
                if (notification) {
                    notification.classList.remove('show');
                    notification.classList.add('fade');
                    notification.style.transition = 'opacity 1s ease';
                }
            }, 7000); // 6 seconds
        </script>
    <?php endif; ?>

    <style>
        .notifMsg {
            font-size: 13px;
            color: red;
            font-weight: 500;
        }

        .password {
            margin-top: -6%;
        }

        .forgotPassword {
            margin-top: 4%;
            font-size: 15px;
        }

        .forgotPassword a {
            text-decoration: none;
        }

        .formHolder {
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }

        @media screen and (max-width: 479px) {}
    </style>
    <script>
        function checkPassword() {
            var x = document.getElementById("password");

            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>