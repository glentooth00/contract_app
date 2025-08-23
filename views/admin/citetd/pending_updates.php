<?php

use App\Controllers\NotificationController;
use App\Controllers\PendingDataController;
session_start();

$department = $_SESSION['department'];
$userRole = $_SESSION['user_role'];
$userType =  $_SESSION['user_role'];
$page_title = "List - $department";

require_once __DIR__ . '../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../vendor/autoload.php';

use App\Controllers\ContractController;
use App\Controllers\ContractTypeController;
use App\Controllers\ContractHistoryController;

$contracts = (new NotificationController)->displayAllPendingUpdatesForCITET($department);

$getAllContractType = (new ContractTypeController)->getContractTypes();

// $pendingUpdates = (new NotificationController)->displayAllPendingUpdates();

$getOneLatest = (new ContractHistoryController)->insertLatestData();
if ($getOneLatest) {
    //     echo '<script>alert("Latest data inserted")</script>';
// } else {
    // Optional: echo nothing or a silent message
    // echo "No contract data available to insert.";
}

include_once '../../../views/layouts/includes/header.php';
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<!-- Loading Spinner - Initially visible -->
<div id="loadingSpinner" class="text-center"
    style="z-index:9999999;padding:100px;height:100%;width:100%;background-color: rgb(203 199 199 / 82%);position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
    <div class="spinner-border" style="width: 3rem; height: 3rem;margin-top:15em;" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>

<div class="main-layout">

    <?php include_once '../menu/sidebar.php'; ?>


    <div class="content-area">

        <h1>Pending Updates</h1>
        <span class="p-1 d-flex float-end" style="margin-top: -2.5em;">
            <!-- <?= $department = $_SESSION['department'] ?? null; ?> Account -->
            <a href="pending_updates.php" style="text-decoration: none;">
                <div style="position: relative; display: inline-block; margin-right: 30px;">
                    <?php if (!empty($getLatestActivities)): ?>
                        <span class="badge bg-danger" style="position: absolute; top: -10px; right: -10px;
                display: inline-flex; justify-content: center; align-items: center;
                border-radius: 50%; width: 20px; height: 20px; font-size: 12px;">
                            <?= $getLatestActivities ?>
                        </span>
                    <?php endif; ?>
                    <img width="25px" src="../../../public/images/bell.svg" alt="Activities need attention">
                </div>
            </a>

            <?php if (isset($department)) { ?>

                <?php switch ($department) {
                    case 'IT': ?>

                        <span class="badge p-2" style="background-color: #0d6efd;">
                            <?= $department; ?> user
                        </span>

                        <?php break;
                    case 'ISD-HRAD': ?>

                        <span class="badge p-2" style="background-color: #3F7D58;">
                            <?= $department; ?> user
                        </span>

                        <?php break;
                    case 'CITETD': ?>

                        <span class="badge p-2" style="background-color: #FFB433;">
                            <?= $department; ?> user
                        </span>

                        <?php break;
                    case 'IASD': ?>

                        <span class="badge p-2" style="background-color: #EB5B00;">
                            <?= $department; ?> user
                        </span>

                        <?php break;
                    case 'ISD-MSD': ?>

                        <span class="badge p-2" style="background-color: #6A9C89;">
                            <?= $department; ?> user
                        </span>

                        <?php break;
                    case 'BAC': ?>

                        <span class="badge p-2" style="background-color: #3B6790;">
                            <?= $department; ?> user
                        </span>

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

        <div style="margin-bottom: 20px; display: flex; justify-content: flex-start; gap: 10px;">


            <!-- Contract Type Filter -->
            <div style="text-align: right;">
                <label>Filter :</label>
                <select id="statusFilter" class="form-select" style="width: 340px;margin-top:-1em">
                    <option value="">Select All</option>
                    <?php if (!empty($getAllContractType)): ?>
                        <?php foreach ($getAllContractType as $contract): ?>
                            <option value="<?= htmlspecialchars($contract['contract_type']) ?>">
                                <?= htmlspecialchars($contract['contract_type']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <table id="table" class="table table-bordered table-striped display mt-2 hover">
            <thead>
                <tr>
                    <th scope="col" style="border: 1px solid #A9A9A9;">Contract name</th>

                    <th scope="col" style="text-align: center; border: 1px solid #A9A9A9;">Start</th>
                    <th scope="col" style="text-align: center; border: 1px solid #A9A9A9;">End</th>
                    <!-- <th scope="col" style="text-align: center; border: 1px solid #A9A9A9;">Status</th> -->
                    <th scope="col" style="text-align: center; border: 1px solid #A9A9A9;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($contracts)): ?>
                    <?php foreach ($contracts as $contract): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($contract['contract_name'] ?? '') ?>
                            </td>

                            <td class="text-center">
                                <span class="badge text-secondary">
                                    <?= !empty($contract['contract_start']) ? date('F-d-Y', strtotime($contract['contract_start'])) : '' ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge text-secondary">
                                    <?= !empty($contract['contract_end']) ? date('F-d-Y', strtotime($contract['contract_end'])) : '' ?>
                                </span>
                            </td>
                            <!-- <td class="text-center">
                                <span
                                    class="badge text-white <?= ($contract['contract_status'] ?? '') === 'Active' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= htmlspecialchars($contract['contract_status'] ?? '') ?>
                                </span>
                            </td> -->
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">

                                    <?php if ($contract['data_type'] === 'Update'): ?>

                                        <a href="view_pending_updates.php" class="btn btn-success btn-sm view-btn"
                                            data-toggle="modal" data-target="#exampleModal" data-id="<?= $contract['id'] ?>"
                                            data-contract-id="<?= $contract['id'] ?>" data-name=" <?= $contract['contract_name'] ?>"
                                            data-start="<?= $contract['contract_start'] ?>"
                                            data-end="<?= $contract['contract_end'] ?>">
                                            <i class="fa fa-eye"></i> View
                                        </a>


                                        <!----update modal ------->
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Compare Changes</h5></span>
                                        </div>
                                        <div class="modal-body">
                                            <div class="col-md-12 d-flex gap-1">
                                                <div class="col-md-6 card p-3" style="background-color: #ECFAE5;">
                                                    <div>

                                                        <h5>Current Data</h5>
                                                        <hr>
                                                        <?php

                                                        $contract_id = $contract['contract_id'];

                                                        $getContractFromContracts = (new ContractController)->getContractbyId($contract_id);
                                            
                                                        ?>
                                                        
                                                        <div class="col-md-12">
                                                            <div class="mb-3">
                                                                <label class="badge text-muted float-start">Contract
                                                                    name</label>
                                                                <input type="text"
                                                                    value="<?= $getContractFromContracts['contract_name'] ?>"
                                                                    class="form-control" readonly>
                                                            </div>
                                                            <div class="d-flex gap-2">
                                                                <div class="col-md-6">
                                                                    <label class="badge text-muted float-start">Date
                                                                        Start</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="16" height="16"
                                                                                fill="currentColor"
                                                                                class="bi bi-calendar"
                                                                                viewBox="0 0 16 16">
                                                                                <path
                                                                                    d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-12a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                                                                            </svg>
                                                                        </span>
                                                                        <?php
                                                                        $dateStart = date('M-d-Y', strtotime($getContractFromContracts['contract_start']));
                                                                        ?>
                                                                        <input type="text"
                                                                            value="<?= $dateStart ?>"
                                                                            class="form-control" readonly>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label class="badge text-muted float-start">Date
                                                                        End</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="16" height="16"
                                                                                fill="currentColor"
                                                                                class="bi bi-calendar"
                                                                                viewBox="0 0 16 16">
                                                                                <path
                                                                                    d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-12a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                                                                            </svg>
                                                                        </span>
                                                                        <?php
                                                                        $dateEnd = date('M-d-Y', strtotime($getContractFromContracts['contract_end']));
                                                                        ?>
                                                                        <input type="text"
                                                                            value="<?= $dateEnd ?>"
                                                                            class="form-control" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mt-3">
                                                            </div>
                                                            <?php
                                                            $start = new DateTime($getContractFromContracts['contract_start']);
                                                            $end = new DateTime($getContractFromContracts['contract_end']);
                                                            $today = new DateTime();

                                                            $interval = $today->diff($end);
                                                            $remainingDays = $interval->invert ? -$interval->days : $interval->days;
                                                            ?>
                                                            <div class="mb-3">
                                                                <label class="badge text-muted float-start">Remaining days</label>
                                                                <input type="text" value="<?= $remainingDays ?> Days"
                                                                    class="form-control" readonly>
                                                            </div>
                                                        </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 card p-3" style="background-color: #E8F9FF;">
                                                                <div>
                                                                    <h5>Pending Changes</h5>
                                                                    <hr>
                                                                    <?php

                                                                    $contract_id = $contract['contract_id'];

                                                                    $getPendingUpdate = (new NotificationController)->getPendingDatabyId($contract_id);

                                                                    ?>
                                                                    <div class="col-md-12">
                                                                        <form action="contracts/approve_update.php" method="POST">
                                                                            <div class="mb-3">

                                                                                <input type="hidden" name="uploader_department"
                                                                                    value="<?= $getPendingUpdate['uploader_department'] ?>"
                                                                                    class="form-control" readonly>
                                                                                <input type="hidden" name="id"
                                                                                    value="<?= $getPendingUpdate['id'] ?>"
                                                                                    class="form-control" readonly>
                                                                                <input type="hidden" name="contract_id"
                                                                                    value="<?= $getPendingUpdate['contract_id'] ?>"
                                                                                    class="form-control" readonly>
                                                                                <label class="badge text-muted float-start">Contract
                                                                                    name</label>
                                                                                <input type="text" name="contract_name"
                                                                                    value="<?= $getPendingUpdate['contract_name'] ?>"
                                                                                    class="form-control">
                                                                            </div>
                                                                            <div class="d-flex gap-2">
                                                                                <div class="col-md-6">
                                                                                    <label class="badge text-muted float-start">Date
                                                                                        Start</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-text">
                                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                width="16" height="16"
                                                                                                fill="currentColor"
                                                                                                class="bi bi-calendar"
                                                                                                viewBox="0 0 16 16">
                                                                                                <path
                                                                                                    d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-12a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                                                                                            </svg>
                                                                                        </span>
                                                                                        <input type="text" name="contract_start"
                                                                                            value="<?= $getPendingUpdate['contract_start'] ?? '' ?>"
                                                                                            class="form-control">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label class="badge text-muted float-start">Date End</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-text">
                                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                width="16" height="16"
                                                                                                fill="currentColor"
                                                                                                class="bi bi-calendar"
                                                                                                viewBox="0 0 16 16">
                                                                                                <path
                                                                                                    d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-12a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                                                                                            </svg>
                                                                                        </span>
                                                                                        <input type="text" name="contract_end"
                                                                                            value="<?= $getPendingUpdate['contract_end'] ?? '' ?>"
                                                                                            class="form-control">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="mt-2">
                                                                                <?php

                                                                                $start = new DateTime($getPendingUpdate['contract_start']);
                                                                                $end = new DateTime($getPendingUpdate['contract_end']);
                                                                                $today = new DateTime();

                                                                                $interval = $today->diff($end);
                                                                                $remainingDays = $interval->invert ? -$interval->days : $interval->days;



                                                                                ?>

                                                                                <div class="mb-3">
                                                                                    <label
                                                                                        class="badge text-muted float-start mt-2">Contract
                                                                                        name</label>
                                                                                    <input type="text"
                                                                                        value="<?= $remainingDays ?> Days"
                                                                                        class="form-control" readonly>
                                                                                </div>
                                                                            </div>
                                                                    </div>


                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>
                                                    <div class="modal-footer">
                                                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                                                        <?php if(!$userRole === CHIEF): ?>
                                                            <button type="submit" class="btn btn-primary">Approve Update</button>
                                                        <?php endif; ?>

                                                        <?php if($userType === 'Manager'): ?>
                                                            <button type="submit" class="btn btn-primary">Approve Update</button>
                                                        <?php endif; ?>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    <?php else: ?>

                                        <a href="view_pending_updates.php?id=<?= $contract['id'] ?>" class="btn btn-success btn-sm"
                                            data-toggle="modal" data-target="#newData">
                                            <i class="fa fa-eye"></i> View New Data
                                        </a>


                                        <!-- Modal -->
                                        <div class="modal fade" id="newData" tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">New Data for Approval
                                                        </h5>
                                                       
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php
                                                        $id = $contract['id'];

                                                        $getPendingDatas = (new PendingDataController)->getNewData($id);
                                                        ?>
                                                        <form action="contracts/approve.php" method="POST"
                                                            enctype="multipart/form-data">
                                                            <div class="col-md-12 d-flex">

                                                                <div class="p-2 col-md-6">
                                                                    <div class="mb-3">
                                                                        <label class="badge text-dark float-start">Contract
                                                                            Name</label>
                                                                        <input type="text" class="form-control" name="contract_name"
                                                                            value="<?= $contract['contract_name'] ?>" readonly>
                                                                    </div>
                                                                    <div class="mb-3 col-md-12">
                                                                        <label class="badge text-dark float-start">Contract
                                                                            Start</label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-text">
                                                                                <i class="bi bi-calendar-date"></i>
                                                                            </span>
                                                                            <input type="date" class="form-control"
                                                                                name="contract_start"
                                                                                value="<?= $contract['contract_start'] ?>" readonly>
                                                                        </div>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label class="badge text-dark float-start">Contract
                                                                            file</label><br>
                                                                        <span id="file" class="badge bg-primary fs-6"
                                                                            data-toggle="modal" data-target="#testModal">View
                                                                            File</span>
                                                                        <input type="hidden" name="contract_file"
                                                                            value="<?= $contract['contract_file'] ?>">


                                                                        <!-- Modal -->
                                                                        <div class="modal fade" id="testModal" tabindex="-1"
                                                                            role="dialog" aria-labelledby="exampleModalLabel"
                                                                            aria-hidden="true">
                                                                            <div class="modal-dialog modal-xl" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title"
                                                                                            id="exampleModalLabel">
                                                                                            Modal title</h5>
                                                                                        <!-- <button type="button" class="close"
                                                                                        data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button> -->
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <iframe
                                                                                            src="<?= htmlspecialchars("../../../" . $contract['contract_file']) ?>"
                                                                                            width="100%" style="height: 80vh;"
                                                                                            frameborder="0"></iframe>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button"
                                                                                            class="btn btn-secondary"
                                                                                            data-dismiss="modal">Close</button>
                                                                                        <button type="button"
                                                                                            class="btn btn-primary">Save
                                                                                            changes</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                    </div>

                                                                    <!---FILE MODAL --->






                                                    </div>

                                                    <div class="p-2 col-md-6">
                                                        <div class="mb-3">
                                                            <label class="badge text-dark float-start">Contract
                                                                type</label>
                                                            <input type="text" class="form-control" name="contract_type"
                                                                value="<?= $contract['contract_type'] ?>" readonly>
                                                        </div>
                                                        <div class="mb-3 col-md-12">
                                                            <label class="badge text-dark float-start">Contract
                                                                End</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text">
                                                                    <i class="bi bi-calendar-date"></i>
                                                                </span>
                                                                <input type="date" class="form-control"
                                                                    name="contract_end"
                                                                    value="<?= $contract['contract_end'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="badge text-dark float-start">Creation
                                                                Date</label>
                                                            <input type="date" class="form-control" name="created_at"
                                                                value="<?= date('Y-m-d', strtotime($contract['created_at'])) ?>"
                                                                readonly>
                                                        </div>
                                                        </span>
                                                        <input type="hidden" class="form-control" name="id"
                                                            value="<?= $contract['id'] ?>" readonly>
                                                        <input type="hidden" class="form-control"
                                                            name="uploader_department"
                                                            value="<?= $contract['uploader_department'] ?>"
                                                            readonly><input type="hidden" class="form-control"
                                                            name="uploader" value="<?= $contract['uploader'] ?>"
                                                            readonly>
                                                        <input type="hidden" class="form-control" name="uploader_id"
                                                            value="<?= $contract['uploader_id'] ?>" readonly>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                                        <?php if($userRole == CHIEF): ?>
                                                            <span class="badge bg-secondary text-white fs-5 " style="margin-left:15em;">Waiting for approval</span>
                                                        <?php endif; ?>
                                                        <?php if($userRole == 'Manager' ): ?>
                                                            <button type="submit" class="btn btn-success">Approve</button>
                                                            <?php endif; ?>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endif; ?>

                                    <!-- Bootstrap Modal for confirmation -->
                                    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="confirmModalLabel">Confirm Deletion</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete this contract?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <!-- Yes, Delete button -->
                                                    <a href="#" id="confirmDelete" class="btn btn-danger">Yes, Delete</a>
                                                    <input type="text" name="id" id="modal-contract-id" >
                                                    <!-- Cancel button -->
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <a href="" class="btn btn-danger badge p-2 delete-btn" data-id="<?= $contract['id'] ?>">
                                        <i class="fa fa-trash"></i> Delete
                                    </a>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No contracts found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<?php include '../modals/power_supply.php'; ?>

<?php include_once '../../../views/layouts/includes/footer.php'; ?>






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

<style>
    #table_filter {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    #statusFilter {
        width: 200px;
        /* Adjust width as needed */
    }

    #file:hover {
        cursor: pointer;
    }
</style>
<?php if (isset($_GET['contract_id'])): ?>
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            $('#exampleModal').modal('show');
        });
    </script>
<?php endif; ?>
<script>
    // When the page finishes loading, hide the spinner
    window.onload = function () {
        document.getElementById("loadingSpinner").style.display = "none"; // Hide the spinner
        document.getElementById("content").style.display = "block"; // Show the page content
    };

    let selectedContractId = null;

    document.addEventListener("click", function (e) {
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            e.preventDefault();

            // Get contract ID from data attribute
            selectedContractId = deleteBtn.getAttribute('data-id');

            // Show modal using Bootstrap 5
            const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
            confirmModal.show();
        }
    });

    // Handle Confirm Delete button click
    document.getElementById('confirmDelete').addEventListener('click', function (e) {
        if (selectedContractId) {
            // Redirect to deletion endpoint (adjust URL to match your backend)
            window.location.href = 'contracts/delete.php?id=' + selectedContractId;
        }
    });

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



    //----------------DAtatables


    $(document).ready(function () {
        $('.view-btn').on('click', function () {
            const id = $(this).data('id');
            const contractId = $(this).data('contract-id');
            const name = $(this).data('name').trim(); // remove leading space
            const start = $(this).data('start');
            const end = $(this).data('end');

            $('#modal-id').text(id);
            $('#modal-contract-id').text(contractId);
            $('#modal-contract-name').text(name);
            $('#modal-start-date').text(start);
            $('#modal-end-date').text(end);
        });
    });


</script>