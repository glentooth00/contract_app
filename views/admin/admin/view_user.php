<?php

session_start();

require_once __DIR__ . '../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../vendor/autoload.php';

use App\Controllers\UserController;
use App\Controllers\ContractController;
use App\Controllers\DepartmentController;

$department = $_SESSION['department'];
$page_title = "View user";

$id = $_GET['id'];

$contracts = (new ContractController)->getContractsByDepartment($department);

$getUsers = (new UserController)->getUserById($id);

$getUserDatas = (new UserController)->getUserDataById($id);

$user = $getUsers['firstname'] . ' ' . $getUsers['middlename'] . ' ' . $getUsers['lastname'];
$user_department = $getUsers['department'];
$user_role = $getUsers['user_role'];
$img = $getUsers['user_image'];

include_once __DIR__ . '../../../../views/layouts/includes/header.php';
?>

<div class="main-layout">

    <?php include_once '../menu/sidebar.php'; ?>

    <div class="content-area">

        <h2 class="mt-2"><a href="users.php" class="text-dark pt-2"><i class="fa fa-angle-double-left"
                    aria-hidden="true"></i></a>
            <?= $user ?></h2>

        <span class="p-1 d-flex float-end" style="margin-top: -2.5em;">

            <?php if (isset($department)) { ?>

                <?php switch ($department) {
                    case 'IT': ?>

                        <span class="badge p-2" style="background-color: #0d6efd;"><?= $department; ?> user</span>

                        <?php break;
                    case 'ISD-HRAD': ?>

                        <span class="badge p-2" style="background-color: #3F7D58;"><?= $department; ?> user</span>

                        <?php break;
                    case 'CITETD': ?>

                        <span class="badge p-2" style="background-color: #FFB433;"><?= $department; ?> user</span>

                        <?php break;
                    case 'IASD': ?>

                        <span class="badge p-2" style="background-color: #EB5B00;"><?= $department; ?> user</span>

                        <?php break;
                    case 'ISD-MSD': ?>

                        <span class="badge p-2" style="background-color: #6A9C89;"><?= $department; ?> user</span>

                        <?php break;
                    case 'BAC': ?>

                        <span class="badge p-2" style="background-color: #3B6790;"><?= $department; ?> user</span>

                        <?php break;
                    case '': ?>
                    <?php default: ?>
                        <!-- <span class="badge text-muted">no department assigned</span> -->
                <?php } ?>
            <?php } else { ?>
                <!-- <span class="badge text-muted">no department assigned</span> -->
            <?php } ?>
        </span>

        <hr>

        <div class="d-flex col-md-12 gap-3">

            <div class="col-md-3 card">
                <div class="d-flex justify-content-center" style="border-radius: 20px;padding:25px;">
                    <img src="/contract_app/admin/user_image/<?= $img ?>" width="65%"
                        style="background-color: #e4e4e4;border-radius: 200px;padding: 15px;" class="profile-pic">

                    <img width="30px" class="icons" id="uploadBtn" src="../../../public/images/upload.svg"
                        type=" button" id="modalBtn" data-toggle="modal" data-target="#imageModal"
                        style="background-color: #ffffff;height: 31px;padding: 5px;border-radius: 20px;position: absolute;top: 13em;left: 16em;">

                </div>
                <div class="p-1">

                </div>
                <hr>
                <div style="text-align: center;" class="mb-3">
                    <div>
                        <span>
                            <h4 class="fw-bold"><?= $user ?></h4>
                        </span>
                    </div>
                    <div>
                        <span>
                            <h6 class="fw-bold text-muted"><?= $user_department ?></h6>
                        </span>
                    </div>
                    <div class="mb-2">
                        <span>
                            <h6 class="fw-bold text-muted"><?= $user_role ?></h6>
                        </span>
                    </div>


                </div>
            </div>



            <div class="col-md-9 card p-4 position-relative">
                <div class="col-md-12">
                    <button class="btn btn-dark" id="modalBtn" data-toggle="modal" data-target="#dataModal"
                        style="float: inline-end;">
                        <img width="18px" height="25px" src="../../../public/images/edit2.svg"></button>
                </div>

                <div class="row p-3 g-3">

                    <div class="row p-3 g-3">
                        <?php foreach ($getUserDatas as $getUser): ?>
                            <div class="col-md-3">
                                <label class="form-label text-muted">Firstname</label>
                                <input type="text" name="firstname" class="form-control"
                                    value="<?= $getUser['firstname'] ?>" readonly>
                            </div>
                            <div class=" col-md-3">
                                <label class="form-label text-muted">Middlename</label>
                                <input type="text" class="form-control" name="middlename"
                                    value="<?= $getUser['middlename'] ?>" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted">Lastname</label>
                                <input type="text" class="form-control" name="lastname" value="<?= $getUser['lastname'] ?>"
                                    readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted">Gender</label>
                                <input type="text" class="form-control" name="gender" value="<?= $getUser['gender'] ?>"
                                    readonly>
                            </div>
                            <div class="col-md-3 mb-4">
                                <label class="form-label text-muted">Department</label>
                                <input type="text" class="form-control" name="gender" value="<?= $getUser['department'] ?>"
                                    readonly>
                            </div>
                            <span class="">
                                <h5>Credentials</h5>
                            </span>
                            <hr>
                            <div class="col-md-3">
                                <label class="form-label text-muted">Username</label>
                                <input type="text" class="form-control" name="middlename"
                                    value="<?= $getUser['username'] ?>" readonly>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <div>
                                    <label class="form-label text-muted">Password</label>
                                    <input type="password" class="form-control" id="userPassword" name="middlename"
                                        value="<?= $getUser['password'] ?>" readonly>
                                </div>
                                <div class="ms-2">
                                    <button type="button" class="btn btn-light" onclick="togglePassword()">
                                        <img width="21px" class="icons" id="closeBtn"
                                            src="../../../public/images/viewPass.svg"></button>
                                </div>
                            </div>

                            <!-- <div class="ms-2 ">
                                <button type="button" class="btn btn-primary float-end">
                                    Edit</button>
                            </div> -->

                        <?php endforeach; ?>
                    </div>

                </div>
            </div>


        </div>



    </div>

</div>


<!-- Modal -->
<div class="modal fade" id="dataModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> <!-- Made it wider -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update User Data</h5>
            </div>
            <div class="modal-body">

                <form action="users/update_user_data.php" method="post">
                    <?php foreach ($getUserDatas as $getUser): ?>
                        <input type="hidden" name="id" value="<?= $getUser['id']; ?>">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Firstname</label>
                                    <input type="text" class="form-control" name="firstname"
                                        value="<?= htmlspecialchars($getUser['firstname']) ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Lastname</label>
                                    <input type="text" class="form-control" name="lastname"
                                        value="<?= $getUser['lastname'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="<?= $getUser['gender'] ?>" hidden selected><?= $getUser['gender'] ?>
                                        </option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Middlename</label>
                                    <input type="text" class="form-control" name="middlename"
                                        value="<?= $getUser['middlename'] ?>">
                                </div>
                                <div class="mb-3">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Department</label>
                                        <select class="form-select form-select-md mb-3" name="department"
                                            aria-label=".form-select-lg example">
                                            <option value="<?= $getUser['department'] ?>" selected hidden>
                                                <?= $getUser['department'] ?>
                                            </option>
                                            <?php
                                            $getDept = (new DepartmentController)->getAllDepartments();
                                            ?>
                                            <?php foreach ($getDept as $dept): ?>
                                                <option value="<?= $dept['department_name'] ?>">
                                                    <?= $dept['department_name'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <h5 class="mt-3">Credentials</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Username</label>
                                <input type="text" class="form-control" name="username" value="<?= $getUser['username'] ?>">
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-end">
                                <div class="w-100">
                                    <label class="form-label text-muted">Password</label>
                                    <input type="text" class="form-control" id="userPassword" name="password"
                                        value="<?= $getUser['password'] ?>">
                                </div>
                                <div class="ms-2 mb-2">
                                    <button type="button" class="btn btn-light" onclick="togglePassword()">
                                        <img width="21px" class="icons" src="../../../public/images/viewPass.svg">
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document"> <!-- Vertically centers modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update User image</h5>
            </div>
            <div class="modal-body">
                <form action="users/update_user.php" method="post" enctype="multipart/form-data">
                    <div class="d-flex flex-column align-items-center">
                        <img src="/contract_app/admin/user_image/<?= $img ?>" alt="User Image"
                            class="img-fluid rounded-circle mb-3"
                            style="width: 350px; height: 350px; object-fit: cover; border: 2px solid #ccc;">

                        <!-- Upload input -->
                        <div class="mb-3 w-75">
                            <label class="form-label small text-muted">Upload New Image</label>
                            <input type="file" value="<?= $getUser['user_image'] ?>"
                                class="form-control form-control-sm" name="user_image" accept="image/*">
                            <input type="hidden" class="form-control" name="id"
                                value="<?= htmlspecialchars($getUser['id']) ?>">
                        </div>
                    </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="submit" class="btn btn-success">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- popup notification ---->

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


<?php
require_once __DIR__ . "../../../layouts/includes/footer.php";
?>

<style>
    .upload {
        display: inline;
        width: 36px;
        background-color: #FBFBFB;
        padding: 5px;
        position: absolute;
        border-radius: 21px;
        z-index: 10;
    }

    .upload:hover {
        cursor: pointer;
    }

    .modalBtn:hover {
        cursor: pointer;
    }

    #uploadBtn {
        transition: transform 0.2s ease;
        cursor: pointer;
    }

    #uploadBtn:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
</style>
<script>
    function togglePassword() {
        const input = document.getElementById("userPassword");
        input.type = input.type === "password" ? "text" : "password";
    }

</script>