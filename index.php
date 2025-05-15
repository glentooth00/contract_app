<?php
$session = session_start();

$page_title = 'Login';


?>
<?php include_once 'views/layouts/includes/header.php'; ?>

<div class="container d-flex justify-content-center" style="margin-top: 10em;margin-bottom:20em;">
    <div class="formHolder card col-md-3 bg-white p-4 rounded mt-5">
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
                <div class="input-group mb-1 position-relative">
                    <!-- Left-side Icon -->
                    <span class="input-group-text">
                        <img width="25px;" src="public/images/password.svg">
                    </span>

                    <!-- Password Field -->
                    <input type="password" id="password" class="form-control" name="password" placeholder="Password"
                        required>

                    <!-- Eye Icon -->
                    <span class="position-absolute" onclick="togglePassword()"
                        style="top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;">
                        <i id="togglePasswordIcon" class="fas fa-eye text-muted"></i>
                    </span>
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
                <button type="submit" class="button-3" name="submit">Login</button>
            </div>
        </form>
        <div class="mt-3 forgotPassword p-1">
            <a href="password/index.php">Forgot password?</a>
        </div>
    </div>
</div>

<?php include_once 'views/layouts/includes/footer.php'; ?>

<style>
    /* CSS */
    .button-3 {
        width: 100%;
        padding: 8px;
        appearance: none;
        background-color: #2ea44f;
        border: 1px solid rgba(27, 31, 35, .15);
        border-radius: 6px;
        box-shadow: rgba(27, 31, 35, .1) 0 1px 0;
        box-sizing: border-box;
        color: #fff;
        cursor: pointer;
        display: inline-block;
        font-family: -apple-system, system-ui, "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji";
        font-size: 14px;
        font-weight: 600;
        line-height: 20px;
        padding: 6px 16px;
        position: relative;
        text-align: center;
        text-decoration: none;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
        vertical-align: middle;
        white-space: nowrap;
    }

    .button-3:focus:not(:focus-visible):not(.focus-visible) {
        box-shadow: none;
        outline: none;
    }

    .button-3:hover {
        background-color: #2c974b;
    }

    .button-3:focus {
        box-shadow: rgba(46, 164, 79, .4) 0 0 0 3px;
        outline: none;
    }

    .button-3:disabled {
        background-color: #94d3a2;
        border-color: rgba(27, 31, 35, .1);
        color: rgba(255, 255, 255, .8);
        cursor: default;
    }

    .button-3:active {
        background-color: #298e46;
        box-shadow: rgba(20, 70, 32, .2) 0 1px 0 inset;
    }

    .formHolder {
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
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
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('togglePasswordIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>