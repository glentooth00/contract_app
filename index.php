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
<?php include_once 'views/layouts/includes/header.php'; ?>

<div class="container d-flex justify-content-center" style="margin-top: 10em;margin-bottom:16em;">
    <div class=" card formHolder col-md-3 bg-white p-4 rounded mt-5 shadow-lg p-3 mb-5 bg-white rounded">
        <div class="d-flex justify-content-center mb-1">
            <div>
                <img width="60px" src="public/images/login.svg">
            </div>
            <div>
                <h1 class="text-dark p-2">Login</h1>
            </div>

        </div>
        <form action="authenticate.php" method="post">
            <div class="col-12">
                <div class="input-group">
                    <span class="input-group-text"><img width="25px;" src="public/images/username.svg"></span>
                    <input type="text" class="form-control" name="username" placeholder="Username" required autofocus>
                </div>

                <p class="notifMsg p-1">
                    <?php if (isset($_SESSION['username'])): ?>
                        <?= $_SESSION['username']; ?>
                        <?php unset($_SESSION['username']); ?>
                    <?php endif; ?>
                </p>
            </div>
            <div class="password col-12">
                <div class="input-group mb-1">
                    <span class="input-group-text"><img width="25px;" src="public/images/password.svg"></span>
                    <input type="password" id="password" class="form-control" name="password" placeholder="Password"
                        required>
                </div>

                <div class="mt-3" style="margin-left:1%;">
                    <input type="checkbox" class="form-check-input mt-1" onclick="checkPassword()">
                    <span class="badge text-muted">Show password</span>
                </div>

                <p class="notifMsg p-1">
                    <?php if (isset($_SESSION['password'])): ?>
                        <?= $_SESSION['password']; ?>
                        <?php unset($_SESSION['password']); ?>
                    <?php endif; ?>
                </p>
            </div>

            <div class="col-12 mb-1 mt-4">
                <!-- <label class="badge text-muted">Password</label> -->
                <button type="submit" class="btn btn-success btn-block w-100" name="submit">Login</button>

            </div>
        </form>

        <div class="mt-3 forgotPassword p-1">
            <a href="password/index.php">Forgot password?</a>
        </div>

    </div>
</div>

<?php include_once 'views/layouts/includes/footer.php'; ?>

<style>
    #formHolder {
        box-shadow: rgba(0, 0, 0, 0.45) 0px 25px 20px -20px !important;
    }

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