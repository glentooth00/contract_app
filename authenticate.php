<?php
session_start();

require_once __DIR__ . '../src/Config/constants.php';
require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\UserController;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Initialize login attempts if not already
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    }

    // If 5 or more failed attempts, block login and suggest contacting admin
    if ($_SESSION['login_attempts'] >= 5) {
        $_SESSION['username'] = 'Too many failed login attempts. Please contact the admin to reset your password.';
        header('Location: index.php');
        exit;
    }

    $auth = new UserController();

    $login = $auth->authenticate([
        'username' => $_POST['username'],
        'password' => $_POST['password'],
    ]);

    $userData = $_SESSION['data'] ?? null;


    if (is_array($userData)) {
        $inputUsername = $_POST['username'];
        $inputPassword = $_POST['password'];
        $storedUsername = $userData['username'];
        $storedPassword = $userData['password'];


        if ($inputUsername === $storedUsername) {
            if ($inputPassword === $storedPassword) {
                // Reset login attempts on success
                $_SESSION['login_attempts'] = 0;

                $_SESSION['id'] = $userData['id'] ?? null;
                $_SESSION['department'] = $userData['department'];
                $_SESSION['user_role'] = $userData['user_role'];
                $_SESSION['contract_types'] = $userData['contract_types'];
                $_SESSION['firstname'] = $userData['firstname'];
                $_SESSION['middlename'] = $userData['middlename'];
                $_SESSION['lastname'] = $userData['lastname'];
                $_SESSION['user_type'] = $userData['user_type'];

                switch ($_SESSION['department']) {
                    case "IT":
                        header("Location: views/admin/admin/index.php");
                        break;
                    case "ISD":
                        header("Location: views/admin/ISD/index.php");
                        break;
                    case "ISD-MSD":
                        header("Location: views/admin/msd/index.php");
                        break;
                    case "ISD-HRAD":
                        header("Location: views/admin/msd/index.php");
                        break;
                    case "CITET":
                        header("Location: views/admin/citetd/index.php");
                        break;
                    case "BAC":
                        header("Location: views/admin/bac/index.php");
                        break;
                    case "IASD":
                        header("Location: views/admin/iasd/index.php");
                        break;
                    case "FSD":
                        header("Location: views/admin/fsd/index.php");
                        break;
                    case "PSPTD":
                        header("Location: views/admin/psptd/index.php");
                        break;
                    case "ISD-SDI":
                        header("Location: views/admin/sdi/index.php");
                        break;
                    case "AOSD":
                        header("Location: views/admin/aosd/index.php");
                        break;
                    case "BOS":
                        header("Location: views/admin/bos/index.php");
                        break;
                    case "OGM":
                        header("Location: views/admin/gm/index.php");
                        break;
                    case "TSD":
                        header("Location: views/admin/TSD/index.php");
                        break;
                    default:
                        $_SESSION['username'] = 'Department not recognized.';
                        header('Location: index.php');
                }
                exit;
            } else {
                $_SESSION['password'] = 'Incorrect password.';
                $_SESSION['login_attempts'] += 1;
                header('Location: index.php');
                exit;
            }
        } else {
            $_SESSION['username'] = 'Incorrect username.';
            $_SESSION['login_attempts'] += 1;
            header('Location: index.php');
            exit;
        }
    } else {
        $_SESSION['username'] = 'Incorrect credentials.';
        $_SESSION['login_attempts'] += 1;
        header('Location: index.php');
        exit;
    }
}
