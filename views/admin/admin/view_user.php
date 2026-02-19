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
// var_dump($getUserDatas);
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

                        <span class="badge p-2" style="background-color: #0d6efd;">Admin user</span>

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

        <div class="d-flex col-md-12 gap-3 mb-2 p-2">

            <div class="col-md-3 card">
                <div class="d-flex justify-content-center" style="border-radius: 20px;padding:25px;">
                    <img id="uploadBtn" data-toggle="modal" data-target="#imageModal"
                        src="/contract_app/admin/user_image/<?= $img ?>" width="65%"
                        style="background-color: #e4e4e4;border-radius: 200px;padding: 15px;" class="profile-pic">
                    <!-- 
                    <img width="30px" class="icons" id="uploadBtn" src="../../../public/images/upload.svg"
                        type=" button" id="modalBtn" data-toggle="modal" data-target="#imageModal"
                        style="background-color: #ffffff;height: 31px;padding: 5px;border-radius: 20px;position: absolute;top: 13em;left: 16em;"> -->

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

                        <?php
                        $badgeColor = '';
                        switch ($user_department) {
                            case IT:
                                $badgeColor = '#0d6efd';
                                break;
                            case 'ISD-HRAD':
                                $badgeColor = '#3F7D58';
                                break;
                            case CITET:
                                $badgeColor = '#FFB433';
                                break;
                            case IASD:
                                $badgeColor = '#EB5B00';
                                break;
                            case 'ISD-MSD':
                                $badgeColor = '#6A9C89';
                                break;
                            case FSD:
                                $badgeColor = '#4E6688';
                                break;
                            case BAC:
                                $badgeColor = '#3B6790';
                                break;
                            case AOSD:
                                $badgeColor = '#85193C';
                                break;
                            case '':
                                $badgeColor = '';
                                break;
                            default:
                                $badgeColor = '';
                                break;
                        }
                        ?>

                        <?php if (!empty($user_department) && $badgeColor): ?>
                            <span class="badge p-2 text-white mb-2 mt-2" style="background-color: <?= $badgeColor ?>;">
                                <?= htmlspecialchars($user_department) ?>
                            </span>
                        <?php else: ?>
                            <span class="badge text-muted">no department assigned</span>
                        <?php endif; ?>
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

                            <div class="col-md-12">
                                <h5>Contract types Assigned</h5>
                                <hr>
                                <div>
                                    <?php
                                    $contractTypesJson = $getUser['contract_types'] ?? '[]';
                                    $contractTypes = json_decode($contractTypesJson, true); // decode JSON string to PHP array
                                    ?>

                                    <div class="d-flex flex-wrap gap-2">
                                        <?php foreach ($contractTypes as $type):
                                            $badgeColor = '';
                                            switch ($type) {
                                                case INFRA:
                                                    $badgeColor = '#328E6E';
                                                    break;
                                                case SACC:
                                                    $badgeColor = '#123458';
                                                    break;
                                                case GOODS:
                                                    $badgeColor = '#F75A5A';
                                                    break;
                                                case EMP_CON:
                                                    $badgeColor = '#DC8686';
                                                    break;
                                                case PSC_LONG:
                                                    $badgeColor = '#007bff';
                                                    break;
                                                case PSC_SHORT:
                                                    $badgeColor = '#28a745';
                                                    break;
                                                case TRANS_RENT:
                                                    $badgeColor = '#003092';
                                                    break;
                                                case TEMP_LIGHTING:
                                                    $badgeColor = '#03A791';
                                                    break;
                                                default:
                                                    $badgeColor = '#FAB12F';
                                                    break;
                                            }
                                            ?>
                                            <label class="form-check-label">
                                                <span class="badge text-white"
                                                    style="background-color: <?= $badgeColor ?>; border-radius: 5px; font-size: 14px; padding: 7px;">
                                                    <?= htmlspecialchars($type) ?>
                                                </span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>



                                </div>
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

                            <?php $userID = $getUser['id'] ?>

                        <?php endforeach; ?>
                    </div>

                </div>
            </div>


        </div>

        <div class="col-md-12" style=" margin-left: .5em;">
            <div class="card">


                <div class="p-2 mt-3" style="margin-left:10px;">
                    <h5>Activity</h5>
                    <hr>
                </div>

                <div class="col-md-11 d-flex gap-3 p-3" style="margin-top: -30px;">

                    <?php

                    $userID;

                    $getContractsByUsers = (new ContractController)->getcontractByUsersId($userID);

                    ?>

                    <div class="col-md-6 card">
                        <div class="mt-1 p-3">
                            <h5>Contracts Uploaded</h5>
                            <hr>


                            <table id="table" class="table table-bordered table-striped display mt-2 hover">

                                <thead>
                                    <tr>
                                        <th scope="col" style="text-align: center; border: 1px solid #A9A9A9;">Contract
                                            Name</th>
                                        <th scope="col" style="text-align: center; border: 1px solid #A9A9A9;">Contract
                                            Type</th>
                                        <th scope="col" style="text-align: center; border: 1px solid #A9A9A9;">File</th>
                                        <th scope="col" style="text-align: center; border: 1px solid #A9A9A9;">Date
                                            Uploaded</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($getContractsByUsers as $getContractsByUser): ?>
                                        <>

                                            <td style="text-align: center;">
                                                <?= $getContractsByUser['contract_name'] ?>
                                            </td>
                                            <td style="text-align: center;">

                                                <?php
                                                $type = $getContractsByUser['contract_type'] ?? '';
                                                $badgeColor = '';
                                                switch ($type) {
                                                    case INFRA:
                                                        $badgeColor = '#328E6E';
                                                        break;
                                                    case SACC:
                                                        $badgeColor = '#123458';
                                                        break;
                                                    case GOODS:
                                                        $badgeColor = '#F75A5A';
                                                        break;
                                                    case EMP_CON:
                                                        $badgeColor = '#FAB12F';
                                                        break;
                                                    case PSC_LONG:
                                                        $badgeColor = '#007bff';
                                                        break;
                                                    case PSC_SHORT:
                                                        $badgeColor = '#28a745';
                                                        break;
                                                    case TRANS_RENT:
                                                        $badgeColor = '#003092';
                                                        break;
                                                    case TEMP_LIGHTING:
                                                        $badgeColor = '#03A791';
                                                        break;
                                                    default:
                                                        $badgeColor = '#FAB12F';
                                                        break;
                                                }
                                                ?>
                                                <span class="p-2 text-white badge"
                                                    style="font-size:11.5px;background-color: <?= $badgeColor ?>; border-radius: 5px;">
                                                    <?= htmlspecialchars($type) ?>
                                                </span>
                                            </td>
                                            <td style="text-align: center;">
                                                <?php if (!empty($getContractsByUser['contract_file'])): ?>
                                                    <!-- Trigger the modal with this button -->
                                                    <button class="btn btn-primary badge p-2" data-bs-toggle="modal"
                                                        data-bs-target="#fileModal<?= $getContractsByUser['id'] ?>"
                                                        style="text-align: center !important;">
                                                        View file
                                                    </button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="fileModal<?= $getContractsByUser['id'] ?>"
                                                        tabindex="-1"
                                                        aria-labelledby="fileModalLabel<?= $getContractsByUser['id'] ?>"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-xl"
                                                            style="min-height: 100vh; max-height: 300vh;">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="fileModalLabel<?= $getContractsByUser['id'] ?>">
                                                                        <?= $getContractsByUser['contract_name'] ?> -
                                                                        <?= $getContractsByUser['contract_type'] ?>
                                                                    </h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body" style="padding: 0; overflow-y: auto;">
                                                                    <!-- Display the contract file inside the modal -->
                                                                    <iframe
                                                                        src="<?= htmlspecialchars("../../../" . $getContractsByUser['contract_file']) ?>"
                                                                        width="100%" style="height: 80vh;"
                                                                        frameborder="0"></iframe>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    No file
                                                <?php endif; ?>


                                            </td>
                                            <td style="text-align: center;">
                                                <?php
                                                $dateuploaded = date('M-d-Y', strtotime($getContractsByUser['created_at']))
                                                    ?>
                                                <?= $dateuploaded ?>
                                            </td>
                                            </tr>
                                        <?php endforeach; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="col-md-6 card">

                        <?php

                        $departmentName = $user_department;

                        $getContractsByDepartmentAssigned = (new ContractController)->getContractsAssigned($departmentName);

                        ?>

                        <div class="mt-1 p-3">
                            <h5>Contracts Assigned</h5>
                            <hr>


                            <table id="assignedContractsTable"
                                class="table table-bordered table-striped display mt-2 hover">


                                <thead>
                                    <tr>
                                        <th scope="col" style="text-align: center; border: 1px solid #A9A9A9;">Contract
                                            Name</th>
                                        <th scope="col" style="text-align: center; border: 1px solid #A9A9A9;">Contract
                                            Type</th>
                                        <th scope="col" style="text-align: center; border: 1px solid #A9A9A9;">File</th>
                                        <th scope="col" style="text-align: center; border: 1px solid #A9A9A9;">Date
                                            Uploaded</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($getContractsByDepartmentAssigned as $assignedContracts): ?>
                                        <tr>

                                            <td style="text-align: center;">
                                                <?= $assignedContracts['contract_name'] ?>
                                            </td>
                                            <td style="text-align: center;">

                                                <?php
                                                $type = $getContractsByUser['contract_type'] ?? '';
                                                $badgeColor = '';
                                                switch ($type) {
                                                    case INFRA:
                                                        $badgeColor = '#328E6E';
                                                        break;
                                                    case SACC:
                                                        $badgeColor = '#123458';
                                                        break;
                                                    case GOODS:
                                                        $badgeColor = '#F75A5A';
                                                        break;
                                                    case EMP_CON:
                                                        $badgeColor = '#FAB12F';
                                                        break;
                                                    case PSC_LONG:
                                                        $badgeColor = '#007bff';
                                                        break;
                                                    case PSC_SHORT:
                                                        $badgeColor = '#28a745';
                                                        break;
                                                    case TRANS_RENT:
                                                        $badgeColor = '#003092';
                                                        break;
                                                    case TEMP_LIGHTING:
                                                        $badgeColor = '#03A791';
                                                        break;
                                                    default:
                                                        $badgeColor = '#FAB12F';
                                                        break;
                                                }
                                                ?>
                                                <span class="p-2 text-white badge"
                                                    style="background-color: <?= $badgeColor ?>; border-radius: 5px;">
                                                    <?= htmlspecialchars($type) ?>
                                                </span>
                                            </td>
                                            <td style="text-align: center;">
                                                <?php if (!empty($assignedContracts['contract_file'])): ?>
                                                    <!-- Trigger the modal with this button -->
                                                    <button class="btn btn-primary badge p-2" data-bs-toggle="modal"
                                                        data-bs-target="#fileModal<?= $assignedContracts['id'] ?>"
                                                        style="text-align: center !important;">
                                                        View file
                                                    </button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="fileModal<?= $assignedContracts['id'] ?>"
                                                        tabindex="-1"
                                                        aria-labelledby="fileModalLabel<?= $assignedContracts['id'] ?>"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-xl"
                                                            style="min-height: 100vh; max-height: 300vh;">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="fileModalLabel<?= $assignedContracts['id'] ?>">
                                                                        <?= $assignedContracts['contract_name'] ?> -
                                                                        <?= $assignedContracts['contract_type'] ?>
                                                                    </h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body" style="padding: 0; overflow-y: auto;">
                                                                    <!-- Display the contract file inside the modal -->
                                                                    <iframe
                                                                        src="<?= htmlspecialchars("../../../" . $assignedContracts['contract_file']) ?>"
                                                                        width="100%" style="height: 80vh;"
                                                                        frameborder="0"></iframe>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    No file
                                                <?php endif; ?>


                                            </td>
                                            <td style="text-align: center;">
                                                <?= $getContractsByUser['created_at'] ?>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- <div class="col-md-2 card">
                        <div class="mt-1 p-3">
                            <h5>Activity Logs</h5>
                            <hr>
                        </div>
                    </div> -->
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
                                        value="<?= htmlspecialchars($getUser['firstname']) ?>" autofocus>
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
        role="alert" style="position: fixed; bottom: 1.5em; right: 1em; z-index: 1000;">

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

<?php include_once '../../../views/layouts/includes/footer.php'; ?>

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
        /* box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2); */
    }
</style>
<script>
    function togglePassword() {
        const input = document.getElementById("userPassword");
        input.type = input.type === "password" ? "text" : "password";
    }
    //----------------DAtatables
    $(document).ready(function () {
        var rowCount = $('#table tbody tr').length;

        // Check if the table has at least one data row (excluding the "No contracts found" message)
        if (rowCount > 0 && $('#table tbody tr td').first().attr('colspan') !== '6') {
            // Initialize DataTable
            var table = $('#table').DataTable({
                "paging": true,
                "searching": true,
                "lengthChange": true,
                "pageLength": 10,
                "ordering": false,
                "info": true
            });

            // Append the contract type filter next to the search input
            var searchInput = $('#table_filter input'); // DataTables search input field
            var filterDiv = $('#statusFilter').closest('div'); // The contract filter container
            searchInput.closest('div').append(filterDiv); // Move the filter next to the search input

            // Apply filter based on contract type selection
            $('#statusFilter').change(function () {
                var filterValue = $(this).val();
                if (filterValue) {
                    table.column(1).search(filterValue).draw(); // Column 1 is for contract type
                } else {
                    table.column(1).search('').draw(); // Reset filter
                }
            });
        }
    });

    $(document).ready(function () {
        var rowCount = $('#assignedContractsTable tbody tr').length;

        if (rowCount > 0 && $('#assignedContractsTable tbody tr td').first().attr('colspan') !== '6') {
            var table = $('#assignedContractsTable').DataTable({
                "paging": true,
                "searching": true,
                "lengthChange": true,
                "pageLength": 10,
                "ordering": false,
                "info": true
            });
        }
    });
    //----------------DAtatables
</script>