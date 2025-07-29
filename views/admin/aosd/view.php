<?php
use App\Controllers\ContractHistoryController;
use App\Controllers\DepartmentController;
use App\Controllers\EmploymentContractController;
use App\Controllers\UserController;
use App\Controllers\CommentController;
session_start();

use App\Controllers\ContractController;

require_once __DIR__ . '../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../vendor/autoload.php';

$department = $_SESSION['department'] ?? null;

$userid = $_SESSION['id'] ?? null;
if($department === IASD){
    $user_id = $_SESSION['id'];
    $user_department = $_SESSION['department'];
}else{
    $user_id = $_SESSION['id'];
    $user_department = $_SESSION['department'];
}

if($userid){
    $isLoggedIn = $userid;
}
$user_name = $_SESSION['firstname'].' '. $_SESSION['middlename'] .' '.$_SESSION['lastname'];

//------------------------- GET CONTRACT NAME ---------------------------//

$contract_id = $_GET['contract_id'];

$getContract = (new ContractController)->getContractbyId($contract_id);

$contract_data = $getContract['contract_name'];
$contractId = $getContract['id'];

$getComments = (new CommentController)->getCommentsByContractId($contractId);

$comments = (new CommentController)->getComments($contractId);

$page_title = 'View Contract | ' . $getContract['contract_name'];

//-----------------------------------------------------------------------//

//------------------------- GET Departments ----------------------------//

$departments = (new DepartmentController)->getAllDepartments();

//-----------------------------------------------------------------------//

include_once '../../../views/layouts/includes/header.php';

?>

<!-- Loading Spinner - Initially visible -->
<!-- <div id="loadingSpinner" class="text-center"
    style="z-index:9999999;padding:100px;height:100%;width:100%;background-color: rgb(203 199 199 / 82%);position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
    <div class="spinner-border" style="width: 3rem; height: 3rem;margin-top:15em;" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div> -->

<div class="main-layout ">

    <?php include_once '../menu/sidebar.php'; ?>


    <div class="content-area">

        <?php include_once __DIR__ . '/../view_header/view_header.php' ?>
        </h2>
            
        <hr>

        <?php
        $start = new DateTime($getContract['contract_start']);
        $end = new DateTime($getContract['contract_end']);
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

        <?php if ($department === $getContract['uploader_department'] || $department === $getContract['department_assigned'] || $department === $getContract['implementing_dept']) { ?>


            <div class="gap-1">
                <span id="close" style="float: inline-end;display:none;">

                    <i class="fa fa-times" style="width:30px;font-size:25px;" aria-hidden="true"></i>

                </span>
                <span id="save" style="float: inline-end;display:none;">
                    <i class="fa fa-floppy-o" aria-hidden="true" style="width:30px;font-size:25px;" alt=""></i>
                </span>
                <span id="edit" style="float: inline-end;display:inline;">
                    <i class="fa fa-pencil-square-o" aria-hidden="true" style="width:30px;font-size:25px;" alt=""></i>
                </span>
            </div>

        <?php } ?>

        <div class="mt-3 col-md-12 d-flex gap-5">

            <div class="row col-md-2">
                <input type="hidden" id="contractId" style="margin-left:9px;" class="form-control pl-5"
                    value="<?= $getContract['id']; ?>" name="contract_name" readonly>
                <div class="mt-3">
                    <label class="badge text-muted" style="font-size: 15px;">Contract Name:</label>
                    <input type="text" id="contractName" style="margin-left:9px;" class="form-control pl-5"
                        value="<?= $getContract['contract_name']; ?>" name="contract_name" readonly>
                </div>
            </div>

            <div class="col-md-2 mt-3">
                <label class="badge text-muted" style="font-size: 15px;">Start date:</label>
                <div class="input-group">
                    <?php $startdate =date('Y-m-d', strtotime($getContract['contract_start']));  ?>
                    <span class="input-group-text"><i class="fa fa-calendar" style="font-size: 18px;"></i></span>
                    <input type="date" id="startDate" class="form-control"
                        value="<?=  $startdate ?>" name="contract_start" readonly>
                </div>
            </div>

            <div class="col-md-2 mt-3">
                <label class="badge text-muted" style="font-size: 15px;">End date:</label>
                <div class="input-group">
                     <?php $Enddate =date('Y-m-d', strtotime($getContract['contract_end']));  ?>
                    <span class="input-group-text"><i class="fa fa-calendar" style="font-size: 18px;"></i></span>
                    <input type="date" id="endDate" class="form-control" value="<?= $Enddate ?>"
                        name="contract_end" readonly>
                </div>
            </div>

            <div class="row col-md-2">

                <div class="mt-3">
                    <label class="badge text-muted" <?php

                    $start = new DateTime($getContract['contract_start']);
                    $end = new DateTime($getContract['contract_end']);
                    $today = new DateTime();

                    $interval = $today->diff($end);
                    $remainingDays = $interval->invert ? -$interval->days : $interval->days;



                    ?> style="font-size: 15px;">Days Remaining:</label>
                    <div class="d-flex">
                        <input type="text" id="daysRemaining" style="margin-left:7px;" class="form-control"
                            value=" <?= $remainingDays ?> day<?= $remainingDays != 1 ? 's' : '' ?>" readonly>
                        <?php

                        $remainingDays;
                        // echo $id = $getContract['id'];
                        
                        if ($remainingDays === 0) {

                            $data = [
                                'id' => $getContract['id'],
                                'contract_status' => 'Expired',
                            ];

                            (new ContractController)->updateStatusExpired($data);

                        } else {
                            // echo 'contract still active';
                        }


                        ?>

                    </div>
                </div>
            </div>
            <div class="row col-md-2">
                <div class="mt-3">
                    <label class="badge text-muted" style="font-size: 15px;">Status</label>
                    <div class="d-flex">
                        <?php if (!$getContract['contract_status'] == 'Active' | $getContract['contract_status'] == 'Expired'): ?>
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

            <?php if (!empty($getContract['contractPrice'])): ?>
                <div class="row col-md-2">
                    <div class="mt-3">
                        <label class="badge text-muted" style="font-size: 15px;">Total Contract cost</label>
                        <input type="text" id="contractInput" style="margin-left:9px;" class="form-control pl-5"
                            value="<?= '₱ ' . $getContract['contractPrice']; ?>" name="contract_type" readonly>
                    </div>
                </div>
            <?php endif; ?>



            <?php if (!$getContract['supplier']): ?>

            <?php else: ?>
                <div class="row col-md-2">
                    <div class="mt-3">
                        <label class="badge text-muted" style="font-size: 15px;">Supplier</label>
                        <input type="text" id="contractInput" style="margin-left:9px;" class="form-control pl-5"
                            value="<?= $getContract['supplier']; ?>" name="contract_type" readonly>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(!empty($getContract['implementing_dept'])): ?>
                 <div class="row col-md-2">
                <div class="mt-3">
                    <label class="badge text-muted" style="font-size: 15px;">Implementing Department</label>
                    <input type="text" id="impDept" style="margin-left:9px;" class="form-control pl-5"
                        value="<?= $getContract['implementing_dept']; ?>" name="contract_type" readonly>
                </div>
            </div>
            <?php  endif;?>

           

            <div class="row col-md-3">
                <div class="mt-3">
                    <!-- <label class="badge text-muted" style="font-size: 15px;">Department Assigned:</label>
                    <select id="deptSelect" class="form-select" style="margin-left:9px;" disabled>
                        <?php foreach ($departments as $department): ?>
                            <option value="<?= $department['department_name']; ?>"
                                <?= ($department['department_name'] == $getContract['department_assigned']) ? 'selected' : ''; ?>>
                                <?= $department['department_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select> -->
                    <!-- <input type="text" style="margin-left:9px;" class="form-control pl-5" value="<?= $getContract['department_assigned']; ?>"  name="department_assigned" readonly> -->
                </div>
            </div>
            <!-- <div class="row col-md-2">
                <div class="mt-3">
                    <label class="badge text-muted" style="font-size: 15px;">Status</label>
                    <div class="d-flex">
                        <?php if (!$getContract['contract_status'] == 'Active' | $getContract['contract_status'] == 'Expired'): ?>
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
            </div> -->

        </div>

        <div class="mt-3 col-md-12 d-flex gap-5">

            <div class="row col-md-3">
                <div class="mt-3">
                    <label class="badge text-muted" style="font-size: 15px;">Uploading Dept:</label>
                    <input type="text" style="margin-left:9px;" class="form-control pl-5"
                        value="<?= $getContract['uploader_department']; ?>" name="contract_type" readonly>
                </div>
            </div>

            <?php

            $getUser = (new UserController)->getUserById($getContract['uploader_id']);

            ?>

            <div class="row col-md-3">
                <div class="mt-3">
                    <label class="badge text-muted" style="font-size: 15px;">Uploaded by:</label>
                    <input type="text" style="margin-left:9px;" class="form-control pl-5"
                        value="<?= $getUser['firstname'] . ' ' . $getUser['middlename'] . ' ' . $getUser['lastname']; ?>"
                        name="contract_type" readonly>
                </div>
            </div>

            <div class="row col-md-3 mt-4 float-end">
                <div class="mt-3 float-end" style="margin-left: 90%;">
                    <?php
                    $dept = $_SESSION['department'];
                    ?>
                    <?php if ($dept === 'ISD-HRAD'): ?>
                        <?php
                        $start = new DateTime($getContract['contract_start']);
                        $end = new DateTime($getContract['contract_end']);
                        $today = new DateTime();

                        // Calculate remaining days (positive if end date is in the future)
                        $interval = $today->diff($end);
                        $remainingDays = $interval->invert ? -$interval->days : $interval->days;
                        ?>

                        <?php if ($remainingDays <= 15 && $remainingDays >= 0): ?>
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary" data-id="<?= $getContract['id'] ?>"
                                    data-contractname="<?= $getContract['contract_name'] ?>"
                                    data-startdate="<?= $getContract['contract_start'] ?>"
                                    data-enddate="<?= $getContract['contract_end'] ?>"
                                    data-departmentassigned="<?= $getContract['department_assigned'] ?>"
                                    data-type="<?= $getContract['contract_type'] ?>" data-bs-toggle="modal"
                                    data-bs-target="#extendModal">
                                    Extend
                                </button>
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
                                            <input type="text" id="contract_name" name="contract_name"
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


        <div>
            <div class="mt-5">
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
                    $id = $getContract['id'];
                    $status = $getContract['contract_status'];
                    $contractHist_datas = (new ContractHistoryController)->getByContractId($id);

                    if ($status === 'Expired') {

                        $stat = [
                            'id' => $getContract['id'],
                            'status' => 'Expired',
                        ];
                        $updateStatus = (new ContractHistoryController)->updateStatusBycontract_id($stat);

                    }

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

                                        <?php if (!empty($employement_data['date_start'])): ?>
                                            <?php $datestart = new DateTime($employement_data['date_start']); ?>
                                            <span class="badge text-dark"><?= date_format($datestart, "M-d-Y"); ?></span>
                                        <?php else: ?>
                                            <span class="badge text-danger">No Start Date</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: center !important;">
                                        <?php if (!empty($employement_data['date_end'])): ?>
                                            <?php $datestart = new DateTime($employement_data['date_end']); ?>
                                            <span class="badge text-dark"><?= date_format($datestart, "M-d-Y"); ?></span>
                                        <?php else: ?>
                                            <span class="badge text-danger">No Start Date</span>
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

       <!-- Off canvas ---->

        <div class="offcanvas offcanvas-start w-25 p-2" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">Comments</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
             <hr>
            
            <div class="offcanvas-body offcanvas-md">
              <?php foreach ($comments as $comment): ?>
    <?php 
        $auditID = $comment['audit_id'];
        $userID = $comment['user_id'];
        $auditName = (new UserController)->getUserById($auditID);
        $userName = (new UserController)->getUserById($userID);
    ?>
    
    <div class="comment" style="display: flex; justify-content: space-between; margin-bottom: 20px;">
        
        <!-- Left: Audit side -->
        <?php if($auditName): ?>
            <div style="flex: 1; text-align: left;background-color: #cefbc7;padding: 10px;border-radius: 10px;">
                <p><strong><?= htmlspecialchars($auditName['firstname'].' '.$auditName['middlename'].' '.$auditName['lastname']) ?>:</strong></p>
                <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                <span class="badge text-muted"><small><?= date('M-D-Y H:i A', strtotime($comment['created_at'])); ?></small></span>
            </div>
        <?php endif; ?>

        <!-- Right: User side -->
        <?php if($userName): ?>
            <div style="flex: 1; text-align: right;background-color: #ffcf6d7d;padding: 10px;border-radius: 10px;"">
                <p><strong><?= htmlspecialchars($userName['firstname'].' '.$userName['middlename'].' '.$userName['lastname']) ?>:</strong></p>
                <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                <span class="badge text-muted"><small><?= date('M-D-Y h:i A', strtotime($comment['created_at'])); ?></small></span>
            </div>
        <?php endif; ?>
        
    </div>
<?php endforeach; ?>

                <!----comments display here ----->

            </div>

            <form action="comments/comment.php" method="post">
                <input type="hidden" id="contractID" value="<?= $contractId ?>" name="contract_id">
                <input type="hidden" id="auditId" value="<?= $user_id ?>" name="audit_id">
                <input type="hidden" id="userId" value="<?= $user_id ?>" name="user_id">
                <input type="hidden" id="userDepartment" value="<?= $user_department ?>" name="user_department">
                <hr>
                <div class="p-3">
                    <textarea class="form-control" name="comment" id="commentTextArea" rows="3" placeholder="Leave a comment..."></textarea>
                </div>
                <div class="p-3">
                <button type="submit" class="float-end" id="submitComment">Comment</button> 
                </div>
            </form>
            </div>


        <!---- Off canva ----->

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


<!----- off canva for comments ------->

<div class="offcanvas offcanvas-start w-25 p-2" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Comments</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <hr>

<div class="offcanvas-body offcanvas-md">
    <div id="comment-container">

    </div>
        <script>

        const loggedInUserId = <?= json_encode($isLoggedIn); ?>;

            function fetchNotificationCount() {
                // const userId = 123; // Replace with dynamic value (e.g., from PHP)
                const contractId = <?= json_encode($contractId) ?>;
                console.log(contractId);
                fetch('contracts/get_messages.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        contract_id: contractId
                    })
                    })
                    .then(response => response.json())


                    .then(data => {
                        console.log('Fetched comments:', data);

                        const container = document.getElementById('comment-container');
                        container.innerHTML = ''; // Clear previous content

                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(comment => {
                                const div = document.createElement('div');
                                div.className = 'comment-box';

                                // Check if the comment belongs to the logged-in user
                                const isMine = comment.user_id == loggedInUserId;
                                
                                div.style.textAlign = isMine ? 'right' : 'left'; // align text based on ownership
                                div.style.backgroundColor = isMine ? '#d1ffd6' : '#f0f0f0'; // optional styling
                                div.style.padding = '10px';
                                div.style.borderRadius = '10px';
                                div.style.marginBottom = '10px';

                                div.innerHTML = `
                                    <p><strong>${comment.username}:</strong></p>
                                    <p>${comment.comment}</p>
                                    <hr>
                                    <p><small>${comment.created_at}</small></p>
                                `;
                                
                                container.appendChild(div);
                            });

                        } else {
                            container.innerHTML = '<p>No comments found.</p>';
                        }
                    })

                }
            // Call the function
            fetchNotificationCount();
            // Repeat every 10 seconds
            setInterval(fetchNotificationCount, 10000);
        </script>

</div>


    <form action="comments/comment.php" method="post">
        <input type="hidden" name="contract_id" value="<?= $contractId ?>">
        <input type="hidden" name="audit_id" value="<?= $user_id ?>">
        <input type="hidden" name="user_id" value="<?= $user_id ?>">
        <input type="hidden" name="user_department" value="<?= $user_department ?>">
        <input type="hidden" name="user_name" value="<?= $user_name ?>">
        <hr>
        <div class="p-3">
            <textarea class="form-control" name="comment" rows="3" placeholder="Leave a comment..."></textarea>
        </div>
        <div class="p-3">
            <button type="submit" class="float-end" id="submitComment">Comment</button>
        </div>
    </form>
</div>
<!----- off canva for comments ------->





<?php include_once '../../../views/layouts/includes/footer.php'; ?>

<style>
    .pageContent {
        display: flex;
        min-height: 100vh;
    }

    .mainContent {
        flex: 1;
        padding: 20px;
        background-color: #FFF;
        width: 300px;
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
    #hasComment:hover{
        cursor: pointer;
    }
    #viewComment:hover{
        cursor: pointer;

    }
    #submitComment{
                    display: inline-block;
                    outline: none;
                    border-width: 0px;
                    border-radius: 3px;
                    box-sizing: border-box;
                    font-size: inherit;
                    font-weight: 500;
                    max-width: 100%;
                    text-align: center;
                    text-decoration: none;
                    transition: background 0.1s ease-out 0s, box-shadow 0.15s cubic-bezier(0.47, 0.03, 0.49, 1.38) 0s;
                    background: rgb(0, 82, 204);
                    cursor: pointer;
                    height: 32px;
                    line-height: 32px;
                    padding: 0px 12px;
                    vertical-align: middle;
                    width: auto;
                    font-size: 14px;
                    color: rgb(255, 255, 255);
    }
    #submitComment:hover {
                        background: rgb(0, 101, 255);
                        text-decoration: inherit;
                        transition-duration: 0s, 0.15s;
                        color: rgb(255, 255, 255);
                    }
    #dropMenu{
    text-align: left;
    color: black;
    position: absolute;
    right: 43px;
    background-color: #ffffff;
    z-index: 1;
    width: 13em;
    padding: 15px 0px 0px 0px;
    border-radius: 10px 0px 10px 10px;
    box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
    display:none;
    font-weight: 500;
    font-size: 16px;
        a{
            text-decoration: none;
            color: #393E46;
            margin-bottom: 15px;

        }

    }
    #dropMenu:hover{
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
        const daysRemaining = document.getElementById('daysRemaining');
        const contractType = document.getElementById('contractType');
        const nameInput = document.getElementById('contractName');
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');
        const rentStart = document.getElementById('rent_start');
        const rentEnd = document.getElementById('rent_end');
        const deptSelect = document.getElementById('deptSelect');
        const id = document.getElementById('contractId');

        // Get the values for start and end dates, fallback to rent_start and rent_end if necessary
        const startDateValue = startDate?.value || rentStart?.value || '';
        const endDateValue = endDate?.value || rentEnd?.value || '';

        // Get other values
        const contractName = encodeURIComponent(nameInput?.value || '');
        const contractStart = encodeURIComponent(formatDate(startDateValue));
        const contractEnd = encodeURIComponent(formatDate(endDateValue));
        const department = encodeURIComponent(deptSelect?.value || ''); // Safe here
        const contract_id = encodeURIComponent(id?.value || '');
        const contract_type = encodeURIComponent(contractType?.value || '');
        const days_remaining = encodeURIComponent(daysRemaining?.value || '');

        // Redirect with query parameters
        window.location.href = `contracts/update.php?id=${contract_id}&name=${contractName}&start=${contractStart}&end=${contractEnd}&dept=${department}&type=${contract_type}&daysRemaining=${days_remaining}`;
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

        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.view-comment-trigger').forEach(function (img) {
                img.addEventListener('click', function () {
                    const contractId = this.dataset.contractId;

                    // Trigger PHP script without waiting for response
                    const url = 'comment/update_status.php?contract_id=' + encodeURIComponent(contractId);
                    const img = new Image(); // lightweight request
                    img.src = url; // sends GET request to the PHP script
                });
            });
        });

    function toggleView(){
        var div = document.getElementById("dropMenu");
        if(div.style.display === "block"){
            div.style.display = "none";
        }else{
            div.style.display = "block"  
            }
        }
</script>