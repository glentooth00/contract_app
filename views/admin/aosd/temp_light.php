<?php

use App\Controllers\ContractHistoryController;
session_start();

use App\Controllers\DepartmentController;
use App\Controllers\EmploymentContractController;
use App\Controllers\UserController;
use App\Controllers\ContractController;


require_once __DIR__ . '../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../vendor/autoload.php';

$department = $_SESSION['department'] ?? null;
$departments = (new DepartmentController)->getAllDepartments();
$getUserInfo = (new UserController)->getUserByDept($department);
$userid = $_SESSION['id'] ?? null;
// $type = $_SESSION['TEMP_LIGHTING'];

//------------------------- GET CONTRACT NAME ---------------------------//

$contract_id = $_SESSION['contract_id'];

$getContract = (new ContractController)->getContractbyId($contract_id);

$contract_data = $getContract['contract_name'];

$page_title = 'View Contract | ' . $getContract['contract_name'];

//-----------------------------------------------------------------------//


include_once '../../../views/layouts/includes/header.php';
?>

<div class="main-layout ">

    <?php include_once '../menu/sidebar.php'; ?>


    <div class="content-area">

        <h2 class="mt-2"><a href="" onclick="history.back(); return false;" class="text-dark pt-2"><i
                    class="fa fa-angle-double-left" aria-hidden="true"></i></a>
            <?= $contract_data ?> 
            
            <?php if(!empty($getContract['account_no'])): ?>
                 <span class="badge" style="color: #9BA4B5;">(<?= $getContract['account_no'] ?>)</span>
            <?php endif; ?>
           
        </h2>
        <hr>

        <?php
        $start = new DateTime($getContract['rent_start'] ?? $getContract['contract_start'] ?? 'now');
        $end = new DateTime($getContract['rent_end'] ?? $getContract['contract_end'] ?? 'now');
        $today = new DateTime();

        $interval = $today->diff($end);
        $remainingDays = $interval->invert ? -$interval->days : $interval->days;

        // Check if the contract is about to expire or already expired
        if ($remainingDays > 0 && $remainingDays <= 15) {
            echo '
            <div class="alert alert-warning text-center border-danger display-2 p-2" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="90" height="90" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                    </svg>
                   This contract is expiring in ' . $remainingDays . ' day' . ($remainingDays === 1 ? '' : 's') . '.
                    </div>';
        } elseif ($getContract['contract_status'] === 'Expired') {
            echo '<div class="alert alert-danger text-center display-2 p-2" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="90" height="90" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                    </svg>
                    THIS CONTRACT HAS EXPIRED!
                    </div>';
        }
        ?>

        <?php if ($department === $getContract['uploader_department']) { ?>

            <div class="gap-1">

                <?php if ($getContract['contract_status'] === 'Expired') { ?>
                    <span id="add" style="float:inline-end;display:inline;" data-bs-toggle="modal"
                        data-bs-target="#transformerModal">
                        <i class="fa fa-plus" aria-hidden="true" style="width:40px;font-size:25px;"></i>
                    </span>
                <?php } ?>


                <span id="close" style="float: inline-end;display:none;">

                    <i class="fa fa-times" style="width:40px;font-size:25px;" aria-hidden="true"></i>

                </span>

                <span id="save" style="float: inline-end;display:none;">
                    <i class="fa fa-floppy-o" aria-hidden="true" style="width:40px;font-size:25px;" alt=""></i>
                </span>

                <span id="edit" style="float: inline-end;display:inline;">
                    <i class="fa fa-pencil-square-o" aria-hidden="true" style="width:40px;font-size:25px;" alt=""></i>
                </span>


            </div>

        <?php } ?>


        <!-- Modal -->
        <div class="modal fade" id="transformerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-lg" style="width: 45em;
    margin-left: -5em;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Transformer Rental Contract</h5>
                        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="contracts/transformerRent.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" class="form-control" name="contract_type" value="<?= TRANS_RENT ?>"
                                readonly>
                            <div class="col-md-12 d-block gap-2">
                                <div class="col-md-12 d-flex gap-2 row justify-content-center">
                                    <div class="col-md-3 p-2" style="width: 13em;">
                                        <div>
                                            <input type="hidden" class="form-control" name="uploader_department"
                                                value="<?= $department ?>" required>
                                            <lable class="badge text-muted">Contract Name</lable>
                                            <input type="text" class="form-control"
                                                value="<?= $getContract['contract_name'] ?>" name="contract_name"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-3 p-2" style="width: 13em;">
                                        <div>
                                            <lable class="badge text-muted">TC No.</lable>
                                            <input type="text" class="form-control" name="tc_no"
                                                value="<?= $getContract['tc_no'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3 p-2" style="width: 13em;">
                                        <div>
                                            <lable class="badge text-muted">Account no.</lable>
                                            <input type="text" class="form-control"
                                                value="<?= $getContract['account_no'] ?>" name="account_no" required>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-12 d-flex gap-5 row justify-content-center">

                                    <div class="col-md-4 p-2" style="width: 15em;">
                                        <div>
                                            <lable class="badge text-muted">Rent Start</lable>
                                            <div class="d-flex">
                                                <i class="fa fa-calendar p-2" style="font-size: 20px;"
                                                    aria-hidden="true"></i>
                                                <input type="date" id="rent_start" class="form-control"
                                                    name="rent_start" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 p-2" style="width: 15em;">
                                        <div>
                                            <lable class="badge text-muted">Rent End</lable>
                                            <div class="d-flex">
                                                <i class="fa fa-calendar p-2" style="font-size: 20px;"
                                                    aria-hidden="true"></i>
                                                <input type="date" id="rent_end" class="form-control" name="rent_end"
                                                    required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-4 p-2">
                                        <div>
                                            <lable class="badge text-muted">Date End</lable>
                                            <i class="fa fa-calendar p-2" style="font-size: 20px;" aria-hidden="true"></i>
                                            <input type="date" id="date_end" class="form-control" name="contract_end" required>
                                        </div>
                                    </div> -->
                                </div>
                                <div class="col-md-12 d-flex gap-4 row justify-content-center">
                                    <!-- <div class="col-md-5 p-2">
                                            <div>
                                                <lable class="badge text-muted">Party of Second Part</lable>
                                                <input type="text" class="form-control" name="party_of_second_part"
                                                    required>
                                            </div>
                                        </div> -->
                                    <div class="col-md-5 p-2">
                                        <div>
                                            <lable class="badge text-muted">Contract file</lable>
                                            <input id="contractFileInput" type="file" class="form-control"
                                                name="contract_file" style="width: 16.7em;" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 p-2">
                                        <div>
                                            <?php
                                            $userid;
                                            $getUser = (new UserController)->getUserById($userid);

                                            // var_dump($getUser['firstname']);
                                            ?>
                                            <input type="hidden" id="date_start" class="form-control" name="uploader_id"
                                                value="<?= $userid ?>">
                                            <input type="hidden" id="date_start" class="form-control" name="uploader"
                                                value="<?= $getUser['firstname'] . ' ' . $getUser['middlename'] . ' ' . $getUser['lastname'] ?>">
                                            <input type="hidden" id="date_start" class="form-control"
                                                name="uploader_dept" value="<?= $department ?>" required>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-4 p-2">
                                        <div>
                                            <lable class="badge text-muted">Date End</lable>
                                            <input type="date" id="date_end" class="form-control" name="contract_end" required>
                                        </div>
                                    </div> -->
                                </div>

                            </div>

                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                        <button type="submit" id="submitButton" class="btn btn-primary" disabled
                            onmouseover="pointer">Submit New Contract</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>


        <script>
            const fileInput = document.getElementById('contractFileInput');
            const submitButton = document.getElementById('submitButton');

            fileInput.addEventListener('change', function () {

                if (fileInput.files.length > 0) {
                    submitButton.disabled = false;
                } else {
                    submitButton.disabled = true;
                }

            });

        </script>



        <div class="mt-3 col-md-12 d-flex gap-5">



            <div class="row col-md-3">
                <input type="hidden" id="contractId" style="margin-left:9px;" class="form-control pl-5"
                    value="<?= $getContract['id']; ?>" name="contract_name" readonly>
                <div class="mt-3">
                    <label class="badge text-muted" style="font-size: 15px;">Contract Name:</label>
                    <input type="text" id="contractName" style="margin-left:9px;" class="form-control pl-5"
                        value="<?= $getContract['contract_name']; ?>" name="contract_name" readonly>
                </div>

            </div>

            <?php if ($getContract['contract_type'] == TEMP_LIGHTING): ?>
                <!-- Specific block for TEMP_LIGHTING contract type -->
                <div class="row col-md-2">
                    <div class="mt-3">
                        <label class="badge text-muted" style="font-size: 15px;">Contract Start date:</label>
                        <div class="d-flex">
                            <i class="fa fa-calendar p-2" style="font-size: 20px;" aria-hidden="true"></i>
                            <input type="date" id="startDate" class="form-control pl-5"
                                value="<?= isset($getContract['contract_start']) ? substr($getContract['contract_start'], 0, 10) : ''; ?>"
                                name="contract_start" readonly>
                        </div>
                    </div>
                </div>

                <div class="row col-md-2">
                    <div class="mt-3">
                        <label class="badge text-muted" style="font-size: 15px;">Contract End date:</label>
                        <div class="d-flex">
                            <i class="fa fa-calendar p-2" style="font-size: 20px;" aria-hidden="true"></i>
                            <input type="date" id="endDate" class="form-control pl-5"
                                value="<?= isset($getContract['contract_end']) ? substr($getContract['contract_end'], 0, 10) : ''; ?>"
                                name="contract_end" readonly>
                        </div>
                    </div>
                </div>
            <?php elseif ($getContract['contract_type'] == TRANS_RENT): ?>

                <!-- Block for other contract types, if needed -->
                <div class="row col-md-2">
                    <div class="mt-3">
                        <label class="badge text-muted" style="font-size: 15px;">Rent Start date:</label>
                        <div class="d-flex">
                            <i class="fa fa-calendar p-2" style="font-size: 20px;" aria-hidden="true"></i>
                            <input type="date" id="startDate" class="form-control pl-5"
                                value="<?= isset($getContract['rent_start']) ? substr($getContract['rent_start'], 0, 10) : ''; ?>"
                                name="contract_start" readonly>

                        </div>
                    </div>
                </div>

                <div class="row col-md-2">
                    <div class="mt-3">
                        <label class="badge text-muted" style="font-size: 15px;">Rent End date:</label>
                        <div class="d-flex">
                            <i class="fa fa-calendar p-2" style="font-size: 20px;" aria-hidden="true"></i>
                            <input type="date" id="endDate" class="form-control pl-5"
                                value="<?= isset($getContract['rent_end']) ? substr($getContract['rent_end'], 0, 10) : ''; ?>"
                                name="contract_end" readonly>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <!-- Block for other contract types, if needed -->
                <div class="row col-md-2">
                    <div class="mt-3">
                        <label class="badge text-muted" style="font-size: 15px;">Rent Start date:</label>
                        <div class="d-flex">
                            <i class="fa fa-calendar p-2" style="font-size: 20px;" aria-hidden="true"></i>
                            <input type="date" id="startDate" class="form-control pl-5"
                                value="<?= isset($getContract['rent_start']) ? substr($getContract['rent_start'], 0, 10) : ''; ?>"
                                name="contract_start" readonly>

                        </div>
                    </div>
                </div>

                <div class="row col-md-2">
                    <div class="mt-3">
                        <label class="badge text-muted" style="font-size: 15px;">Rent End date:</label>
                        <div class="d-flex">
                            <i class="fa fa-calendar p-2" style="font-size: 20px;" aria-hidden="true"></i>
                            <input type="date" id="endDate" class="form-control pl-5"
                                value="<?= isset($getContract['rent_end']) ? substr($getContract['rent_end'], 0, 10) : ''; ?>"
                                name="contract_end" readonly>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- <?= $getContract['contract_type'] ?> -->

            <div class="row col-md-2">

                <div class="mt-3">
                    <label class="badge text-muted" style="font-size: 15px;">
                        <?php
                        $start = new DateTime($getContract['rent_start'] ?? $getContract['contract_start'] ?? 'now');
                        $end = new DateTime($getContract['rent_end'] ?? $getContract['contract_end'] ?? 'now');

                        $today = new DateTime();

                        // Calculate the difference
                        $interval = $today->diff($end);
                        $remainingDays = $interval->format('%r%a'); // This gives you the remaining days, including negative if past
                        
                        // Check if the remaining days is less than 0
                        if ($remainingDays < 0) {
                            $remainingDays = 0; // You may want to set it to 0 if the contract has ended
                        }
                        ?>Days Remaining:</label>
                    <?php
                    if (isset($getContract['contract_status']) && $getContract['contract_status'] === 'Expired') {
                        $remainingDays = 0;
                    }
                    ?>
                    <div class="d-flex">
                        <input type="text" id="remainingDays" style="margin-left:7px;" class="form-control"
                            value="<?= $remainingDays ?> day<?= $remainingDays != 1 ? 's' : '' ?>" readonly>
                    </div>

                </div>

                <?php

                $remainingDays;
                if ($remainingDays == 0) {

                    $data = [
                        'id' => $getContract['id'],
                        'contract_status' => 'Expired',
                    ];

                    (new ContractController)->updateStatusExpired($data);
                }
                ?>
            </div>

            <div class="row col-md-2">
                <div class="mt-3">
                    <label class="badge text-muted" style="font-size: 15px;">Status</label>
                    <div class="d-flex">
                        <?php if (!$getContract['contract_status'] === 'Active' | $getContract['contract_status'] === 'Expired'): ?>
                            <i class="fa fa-ban p-2" style="color:#BF3131;font-size: 20px;" aria-hidden="true"></i>
                            <span class="alert p-1 alert-warning border-danger text-danger text-center"
                                style="width: 7em;"><?= $getContract['contract_status']; ?></span>
                        <?php else: ?>
                            <i class="fa fa-check p-2" style="color:green;font-size: 20px;" aria-hidden="true"></i>
                            <span class="alert p-1 alert-success border-success text-success text-center"
                                style="width: 7em;"><?= $getContract['contract_status']; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!---- 2nd row ------>
        <div class="mt-3 col-md-12 d-flex gap-5">

            <div class="row col-md-2">
                <div class="mt-3">
                    <label class="badge text-muted" style="font-size: 15px;">Contract type:</label>
                    <input type="text" id="contractType" style="margin-left:9px;" class="form-control pl-5"
                        value="<?= $getContract['contract_type']; ?>" name="contract_type" readonly>
                </div>
            </div>

            <div class="row col-md-2">
                <?php if ($getContract['account_no']): ?>
                    <div class="mt-3">
                        <label class="badge text-muted" style="font-size: 15px;">Account no:</label>
                        <input type="text" id="contractInput" style="margin-left:9px;" class="form-control pl-5"
                            value="<?= $getContract['account_no']; ?>" name="contract_type" readonly>
                    </div>
                <?php endif; ?>
            </div>


            <div class="row col-md-2">
                <div class="mt-3">
                    <label class="badge text-muted" style="font-size: 15px;">Implementing Dept:</label>
                    <input type="text" style="margin-left:9px;" class="form-control pl-5"
                        value="<?= $getContract['uploader_department']; ?>" name="contract_type" readonly>
                </div>
            </div>

            <div class="row col-md-2">
                <div class="mt-3">
                    <label class="badge text-muted" style="font-size: 15px;">Uploaded by:</label>
                    <input type="text" style="margin-left:9px;" class="form-control pl-5"
                        value="<?= $getUser['firstname'] . ' ' . $getUser['middlename'] . ' ' . $getUser['lastname']; ?>"
                        name="contract_type" readonly>
                </div>
            </div>


            <?php

            $getUser = (new UserController)->getUserById($getContract['uploader_id']);

            ?>


            <div class="row col-md-3 mt-4 float-end p-2">
                <div class="mt-3 float-end" style="margin-left: 10%;">
                    <?php
                    $dept = $_SESSION['department'];
                    ?>
                    <?php if ($_SESSION['department'] === 'ISD-MSD'): ?>

                        <?php
                        // Determine which end date to use
                        $endDate = !empty($getContract['rent_end']) ? new DateTime($getContract['rent_end']) : new DateTime($getContract['contract_end']);
                        $today = new DateTime();

                        $remainingDays = $today->diff($endDate)->days;
                        $isExpired = $endDate < $today;

                        ?>
                        <?php if (!$isExpired && $remainingDays <= 3 && $getContract['contract_status'] === 'Active'): ?>
                            <div class="d-flex gap-2">
                                <!-- <button class="btn btn-primary" data-id="<?= $getContract['id'] ?>"
                        data-contractname="<?= $getContract['contract_name'] ?>"
                        data-startdate="<?= $getContract['contract_start'] ?>"
                        data-enddate="<?= $getContract['contract_end'] ?>"
                        data-departmentassigned="<?= $getContract['department_assigned'] ?>"
                        data-type="<?= $getContract['contract_type'] ?>" data-bs-toggle="modal"
                        data-bs-target="#extendModal">
                        Extend
                    </button> -->

                                <form action="contracts/end_contract.php" method="post">
                                    <input type="hidden" name="contract_id" value="<?= $getContract['id'] ?>">
                                    <button type="submit" class="btn btn-warning">End Contract</button>
                                </form>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>


                </div>
            </div>



            <!-- Extend Modal -->
            <div class="modal fade" id="extendModal" data-bs-backdrop="static" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Extend Contract</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="contracts/renew_contract.php" method="POST" enctype="multipart/form-data">
                                <div class="d-flex col-md-12 gap-3 p-4">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <input type="hidden" id="contract_id" name="contract_id"
                                                class="form-control">
                                            <label for="contract_name" class="form-label badge text-muted">Contract
                                                Name</label>
                                            <input type="text" id="contract_name"
                                                value="<?= $getContract['contract_name'] ?>" name="contract_name"
                                                class="form-control">
                                        </div>
                                        <div class="mb-2">
                                            <label for="start_date" class="form-label badge text-muted">Start
                                                Date</label>
                                            <input type="date" id="start_date" name="contract_start"
                                                class="form-control">
                                        </div>
                                        <div class="mb-2">
                                            <label for="start_date" class="form-label badge text-muted">Contract
                                                File</label>
                                            <input type="file" id="start_date" name="contract_file"
                                                class="form-control">
                                        </div>

                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="department_assigned"
                                                class="form-label badge text-muted">Department Assigned</label>
                                            <select id="department_assigned" name="department_assigned"
                                                class="form-select">
                                                <option value="" hidden>Select Department</option>
                                                <?php foreach ($departments as $dept): ?>
                                                    <option value="<?= $dept['department_name'] ?>">
                                                        <?= $dept['department_name'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>

                                        </div>

                                        <div class="mb-2">
                                            <label for="end_date" class="form-label badge text-muted">End Date</label>
                                            <input type="date" id="end_date" name="contract_end" class="form-control">
                                        </div>
                                        <div class="mb-2">
                                            <label for="start_date" class="form-label badge text-muted">Contract
                                                Type</label>
                                            <input type="text" id="contract_type" name="contract_type"
                                                class="form-control">
                                        </div>

                                    </div>
                                </div>
                        </div>

                        <div class="modal-footer">
                            <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                            <button type="submit" class="btn btn-success">Renew Contract</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>


        <div class="col-md-12">
            <div class="col-md-4">

            <?php if($getContract['contract_type'] === EMP_CON): ?>
                <?php if ($getContract['department_assigned']): ?>
                    <div class="mt-3">
                        <label class="badge text-muted" style="font-size: 15px;">Department Assigned:</label>
                        <select id="deptSelect" class="form-select" style="margin-left:9px;" disabled>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?= $department['department_name']; ?>"
                                    <?= ($department['department_name'] == $getContract['department_assigned']) ? 'selected' : ''; ?>>
                                    <?= $department['department_name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <!-- <input type="text" style="margin-left:9px;" class="form-control pl-5" value="<?= $getContract['department_assigned']; ?>"  name="department_assigned" readonly> -->
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            </div>
        </div>



        <div class="mt-3 col-md-12 d-flex gap-5">

            <!-- <div class="row col-md-3">
                <div class="mt-3">
                    <label class="badge text-muted" style="font-size: 15px;">Implementing Dept:</label>
                    <input type="text" style="margin-left:9px;" class="form-control pl-5"
                        value="<?= $getContract['uploader_department']; ?>" name="contract_type" readonly>
                </div>
            </div> -->

            <?php

            $getUser = (new UserController)->getUserById($getContract['uploader_id']);

            ?>


            <!-- Extend Modal -->
            <div class="modal fade" id="extendModal" data-bs-backdrop="static" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Extend Contract</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="contracts/renew_contract.php" method="POST" enctype="multipart/form-data">
                                <div class="d-flex col-md-12 gap-3 p-4">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <input type="hidden" id="contract_id" name="contract_id"
                                                class="form-control">
                                            <label for="contract_name" class="form-label badge text-muted">Contract
                                                Name</label>
                                            <input type="text" id="contract_name" name="contract_name"
                                                class="form-control" value="<?= $getContract['contract_name'] ?>">
                                        </div>
                                        <div class="mb-2">
                                            <label for="start_date" class="form-label badge text-muted">Start
                                                Date</label>
                                            <input type="date" id="start_date" name="contract_start"
                                                class="form-control">
                                        </div>
                                        <div class="mb-2">
                                            <label for="start_date" class="form-label badge text-muted">Contract
                                                File</label>
                                            <input type="file" id="start_date" name="contract_file"
                                                class="form-control">
                                        </div>

                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="department_assigned"
                                                class="form-label badge text-muted">Department Assigned</label>
                                            <select id="department_assigned" name="department_assigned"
                                                class="form-select">
                                                <option value="" hidden>Select Department</option>
                                                <?php foreach ($departments as $dept): ?>
                                                    <option value="<?= $dept['department_name'] ?>">
                                                        <?= $dept['department_name'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>

                                        </div>

                                        <div class="mb-2">
                                            <label for="end_date" class="form-label badge text-muted">End Date</label>
                                            <input type="date" id="end_date" name="contract_end" class="form-control">
                                        </div>
                                        <div class="mb-2">
                                            <label for="start_date" class="form-label badge text-muted">Contract
                                                Type</label>
                                            <input type="text" id="contract_type" name="contract_type"
                                                class="form-control">
                                        </div>

                                    </div>
                                </div>
                        </div>

                        <div class="modal-footer">
                            <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                            <button type="submit" class="btn btn-success">Renew Contract</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $remainingDays;
        if ($remainingDays === 0) {

            $data = [
                'account_no' => $getContract['account_no'],
                'status' => 'Expired',
            ];

        
            (new ContractHistoryController)->updateExpiredDays($data);
        }
        ?>

        <div>
            <div class="mt-1">

                <h4>Contract History</h4>
            </div>
            <hr>
            <div>
                <table class="table table-stripped table-hover">
                    <thead>
                        <tr>
                            <th style="text-align: center !important;"><span class="badge text-muted">Status</span></th>
                            <th style="text-align: center !important;"><span class="badge text-muted">Contract
                                    File</span></th>
                            <th style="text-align: center !important;"><span class="badge text-muted">Date Start</span>
                            </th>
                            <th style="text-align: center !important;"><span class="badge text-muted">Date End</span>
                            </th>
                            <!-- <th style="text-align: center !important;"><span class="badge text-muted">Action</span></th> -->
                        </tr>
                    </thead>

                    <?php
                   $id = $getContract['account_no'];
                    $status = $getContract['contract_status'];
                    $contractHist_datas = (new ContractHistoryController)->getByContractId($id);



                    if ($status === 'Expired') {

                        $stat = [
                            'id' => $getContract['account_no'],
                            'status' => 'Expired',
                        ];

                        $updateStatus = (new ContractHistoryController)->updateStatus($stat);

                    }

                    // var_dump($contractHist_datas);
                    
                    ?>
                    <tbody class="">
                        <?php if (!empty($contractHist_datas)): ?>
                            <?php foreach ($contractHist_datas as $employement_data): ?>
                                <tr>
                                    <td style="text-align: center !important;">

                                        <?php if ($employement_data['status'] == 'Active'): ?>
                                            <span class="badge bg-success p-2"><?= $employement_data['status']; ?></span>
                                        <?php elseif ($employement_data['status'] == 'Expired'): ?>
                                            <span class="badge bg-danger p-2">Rental Contract Ended</span>
                                        <?php else: ?>
                                            <span class="badge text-dark bg-warning p-2">Employment Contract ended</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: center !important;">


                                        <?php if (!empty($employement_data['contract_file'])): ?>
                                            <!-- Trigger the modal with this button -->
                                            <button class="btn btn-primary badge p-2" data-bs-toggle="modal"
                                                data-bs-target="#fileModal<?= $employement_data['id'] ?>"
                                                style="text-align: center !important;">
                                                View file
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="fileModal<?= $employement_data['id'] ?>" tabindex="-1"
                                                aria-labelledby="fileModalLabel<?= $employement_data['id'] ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-xl" style="min-height: 100vh; max-height: 300vh;">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="fileModalLabel<?= $employement_data['id'] ?>">
                                                                <?= $employement_data['contract_name'] ?> -
                                                                <?= $employement_data['contract_type'] ?>
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body" style="padding: 0; overflow-y: auto;">
                                                            <!-- Display the contract file inside the modal -->
                                                            <iframe
                                                                src="<?= htmlspecialchars("../../../" . $employement_data['contract_file']) ?>"
                                                                width="100%" style="height: 80vh;" frameborder="0"></iframe>
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
                                    <td style="text-align: center !important;">

                                        <?php if ($employement_data['contract_type'] === TEMP_LIGHTING): ?>
                                            <?php if (!empty($employement_data['date_start'])): ?>
                                                <?php $datestart = new DateTime($employement_data['date_start']); ?>
                                                <span class="badge text-dark"><?= date_format($datestart, "M-d-Y"); ?></span>
                                            <?php else: ?>
                                                <span class="badge text-danger">No Start Date</span>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if ($employement_data['contract_type'] === TRANS_RENT): ?>
                                            <?php if (!empty($employement_data['rent_start'])): ?>
                                                <?php $datestart = new DateTime($employement_data['rent_start']); ?>
                                                <span class="badge text-dark"><?= date_format($datestart, "M-d-Y"); ?></span>
                                            <?php else: ?>
                                                <span class="badge text-danger">No Start Date</span>s
                                            <?php endif; ?>
                                        <?php endif; ?>

                                    </td>
                                    <td style="text-align: center !important;">


                                        <?php if ($employement_data['contract_type'] === TEMP_LIGHTING): ?>
                                            <?php if (!empty($employement_data['date_end'])): ?>
                                                <?php $datestart = new DateTime($employement_data['date_end']); ?>
                                                <span class="badge text-dark"><?= date_format($datestart, "M-d-Y"); ?></span>
                                            <?php else: ?>
                                                <span class="badge text-danger">No Start Date</span>
                                            <?php endif; ?>
                                        <?php endif; ?>


                                        <?php if ($employement_data['contract_type'] === TRANS_RENT): ?>
                                            <?php if (!empty($employement_data['rent_end'])): ?>
                                                <?php $datestart = new DateTime($employement_data['rent_end']); ?>
                                                <span class="badge text-dark"><?= date_format($datestart, "M-d-Y"); ?></span>
                                            <?php else: ?>
                                                <span class="badge text-danger">No Start Date</span>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                    </td>
                                    <!-- <td style="text-align: center !important;">
                                     
                                        <button class="btn btn-danger btn-sm" title="Delete">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>

                                      
                                        <button class="btn btn-primary btn-sm" title="Edit" data-bs-toggle="modal"
                                            data-bs-target="#editModal">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </button>
                                    </td> -->


                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No contract data found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
<?php

// include_once '../modals/isdmsd_modal.php';
// include_once '../modals/transformer_rental.php';

?>

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
    .pageContent {
        display: flex;
        min-height: 100vh;
    }

    .sideBar {
        width: 260px;
        /* or whatever fixed width you want */
        min-height: 100vh;
        /* color: white; */
    }

    .mainContent {
        flex: 1;
        padding: 20px;
        background-color: #FFF;
    }


    /* Header styles */
    .headerDiv {
        background-color: #FBFBFB;
        padding: 20px;
    }

    thead th {
        text-align: left;
    }

    .glow-text {
        background-color: #3CCF4E;
        /* box-shadow: 0 0 15px #23ff3e, 0 0 30px #f3f4f3; */
        border-radius: 10px;
        padding: 10px 20px;
        color: white;
        font-weight: bold;
    }

    #edit:hover {
        cursor: pointer;
    }

    #save:hover {
        cursor: pointer;
    }

    #close:hover {
        cursor: pointer;
    }

    #add:hover {
        cursor: pointer;
    }
</style>

<script>
    // When the page finishes loading, hide the spinner
    window.onload = function () {
        document.getElementById("loadingSpinner").style.display = "none"; // Hide the spinner
        document.getElementById("content").style.display = "block"; // Show the page content
    };


    document.addEventListener("click", function (e) {
        // Check if the clicked element is a delete button
        if (e.target && e.target.id === "delete") {
            e.preventDefault(); // Prevent default action (if any)

            // Get the data-id of the clicked button
            var contractId = e.target.getAttribute('data-id');

            // Show the confirmation modal and pass the contractId
            showConfirmationModal(contractId);
        }
    });


    document.getElementById('edit').addEventListener('click', function () {

        const nameInput = document.getElementById('contractName');
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');
        const rentStart = document.getElementById('rentStart');
        const rentEnd = document.getElementById('rentEnd');
        const deptSelect = document.getElementById('deptSelect');

        const saveBtn = document.getElementById('save');
        const editBtn = document.getElementById('edit');
        const closeBtn = document.getElementById('close');

        // Check if fields are currently readonly/disabled
        const isReadOnly = nameInput.hasAttribute('readonly');

        if (isReadOnly) {
            // Make all editable
            nameInput?.removeAttribute('readonly');
            startDate?.removeAttribute('readonly');
            endDate?.removeAttribute('readonly');
            deptSelect?.removeAttribute('disabled');
            rentStart?.removeAttribute('readonly');
            rentEnd?.removeAttribute('readonly');

            saveBtn.style.display = 'inline';
            editBtn.style.display = 'none';
            closeBtn.style.display = 'inline';

            nameInput.focus();
        } else {
            // Set them back to readonly/disabled
            nameInput.setAttribute('readonly', true);
            startDate.setAttribute('readonly', true);
            endDate.setAttribute('readonly', true);
            deptSelect.setAttribute('disabled', true);


            saveBtn.style.display = 'none';

        }
    });

    document.getElementById('close').addEventListener('click', function () {

        const nameInput = document.getElementById('contractName');
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');
        const deptSelect = document.getElementById('deptSelect');
        const saveBtn = document.getElementById('save');
        const editBtn = document.getElementById('edit');
        const closeBtn = document.getElementById('close');

        // Check if fields are currently readonly/disabled
        const isReadOnly = nameInput.hasAttribute('readonly');

        // Set them back to readonly/disabled
        nameInput?.setAttribute('readonly', true);
        startDate?.setAttribute('readonly', true);
        endDate?.setAttribute('readonly', true);
        deptSelect?.setAttribute('disabled', true);

        saveBtn.style.display = 'none';
        editBtn.style.display = 'inline';
        closeBtn.style.display = 'none';
    });


    document.getElementById('save').addEventListener('click', function () {

        // Get the relevant DOM elements
        const nameInput = document.getElementById('contractName');
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');
        const rentStart = document.getElementById('rent_start');
        const rentEnd = document.getElementById('rent_end');
        const deptSelect = document.getElementById('deptSelect');
        const id = document.getElementById('contractId');
        const days_remaining = document.getElementById('remainingDays');
        const contract_type = document.getElementById('contractType');

        // Get the values for start and end dates, fallback to rent_start and rent_end if necessary
        const startDateValue = startDate?.value || rentStart?.value || '';
        const endDateValue = endDate?.value || rentEnd?.value || '';

        // Get other values
        const contractName = encodeURIComponent(nameInput?.value || '');
        const contractStart = encodeURIComponent(formatDate(startDateValue));
        const contractEnd = encodeURIComponent(formatDate(endDateValue));
        const department = encodeURIComponent(deptSelect?.value || ''); // Safe here
        const contract_id = encodeURIComponent(id?.value || '');
        const daysRemaining = encodeURIComponent(days_remaining.value).match(/\d+/)[0];
        const con_type = encodeURIComponent(contract_type?.value || '');

        // Redirect with query parameters
        window.location.href = `contracts/update.php?id=${contract_id}&name=${contractName}&start=${contractStart}&end=${contractEnd}&dept=${department}&daysRemaining=${daysRemaining}&type=${con_type}`;
    });


    function formatDate(dateString) {
        const [year, month, day] = dateString.split('-');
        return `${month}/${day}/${year}`;
    }



    $('#extendModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);

        var contractId = button.data('id');
        var contractName = button.data('contractname');
        var startDate = button.data('startdate');
        var endDate = button.data('enddate');
        var departmentAssigned = button.data('departmentassigned');
        var contractType = button.data('type');

        $('#contract_id').val(contractId);
        $('#contract_name').val(contractName);
        $('#start_date').val(startDate);
        $('#end_date').val(endDate);
        $('#department_assigned').val(departmentAssigned);
        $('#contract_type').val(contractType);
    });




</script>