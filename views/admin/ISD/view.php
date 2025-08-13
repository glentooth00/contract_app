<?php
use App\Controllers\ContractHistoryController;
use App\Controllers\ContractTypeController;
use App\Controllers\DepartmentController;
use App\Controllers\EmploymentContractController;
use App\Controllers\SuspensionController;
use App\Controllers\UserController;
use App\Controllers\CommentController;
session_start();
date_default_timezone_set('Asia/Manila');
use App\Controllers\ContractController;
require_once __DIR__ . '../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../vendor/autoload.php';


$department = $_SESSION['department'] ?? null;
$userid = $_SESSION['id'] ?? null;

$user_role = $_SESSION['user_role'];

$user_name = $_SESSION['firstname'].' '. $_SESSION['firstname'] .' '.$_SESSION['lastname'];

if($userid){
    $isLoggedIn = $userid;
}

if($department === IASD){
    $user_id = $_SESSION['id'];
    $user_department = $_SESSION['department'];
}else{
    $user_id = $_SESSION['id'];
    $user_department = $_SESSION['department'];
}
$User = $_SESSION['firstname'].' '.$_SESSION['middlename'].' '.$_SESSION['lastname'];

//------------------------- GET CONTRACT NAME ---------------------------//
$contract_id = $_GET['contract_id'];
$getContract = (new ContractController)->getContractbyId($contract_id);
$contract_data = $getContract['contract_name'];
$contractId = $getContract['id'];
$comments = (new CommentController)->getComments($contractId);
$page_title = 'View Contract | ' . $getContract['contract_name'];
//-----------------------------------------------------------------------//
//------------------------- GET Departments ----------------------------//
$departments = (new DepartmentController)->getAllDepartments();
//-----------------------------------------------------------------------//
date_default_timezone_set('Asia/Manila');
$contractId = $getContract['id'];
$contractEnding = $getContract['contract_end'] ?? null;
$rentEnding = $getContract['rent_end'] ?? null;
include_once '../../../views/layouts/includes/header.php';
?>
<div class="main-layout ">
    <?php include_once '../menu/sidebar.php'; ?>
    <div class="content-area">
        <input type="hidden" id="loggedInUser" value="<?= $User ?>">
        <input type="hidden" id="uploader_id" value="<?= $getContract['uploader_id'] ?>">
        <input type="hidden" id="uploader_dept" value="<?= $getContract['uploader_department'] ?>">
        
    <?php include_once __DIR__ . '/../view_header/view_header.php' ?>
        <hr>
        <!-- Modal -->
        <div class="modal fade" id="suspendModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Suspend Contract</h5>
                    </div>
                    <div class="modal-body">
                        <form action="contracts/suspend.php" method="post">
                            <div class="form-group">
                                
                                <?php if ($getContract['contract_type'] === TEMP_LIGHTING): ?>
                                    <input type="hidden" name="contract_start"
                                        value="<?= $getContract['contract_start'] ?>">
                                    <input type="hidden" name="contract_end" value="<?= $getContract['contract_end'] ?>">
                                <?php endif; ?>

                                <?php if ($getContract['contract_type'] === TRANS_RENT): ?>
                                    <input type="hidden" name="rent_start" value="<?= $getContract['rent_start'] ?>">
                                    <input type="hidden" name="rent_end" value="<?= $getContract['rent_end'] ?>">
                                <?php endif; ?>

                                <?php if ($getContract['contract_type'] === EMP_CON): ?>
                                    <input type="hidden" name="contract_start" value="<?= $getContract['contract_start'] ?>">
                                    <input type="hidden" name="contract_end" value="<?= $getContract['contract_end'] ?>">
                                <?php endif; ?>

                                 <?php if ($getContract['contract_type'] === GOODS): ?>
                                    <input type="hidden" name="contract_start" value="<?= $getContract['contract_start'] ?>">
                                    <input type="hidden" name="contract_end" value="<?= $getContract['contract_end'] ?>">
                                <?php endif; ?>

                                <?php if ($getContract['contract_type'] === INFRA): ?>
                                    <input type="hidden" name="contract_start" value="<?= $getContract['contract_start'] ?>">
                                    <input type="hidden" name="contract_end" value="<?= $getContract['contract_end'] ?>">
                                <?php endif; ?>

                                <label for="suspendReason" class="badge text-muted mb-2">Type of Suspension</label>
                                <div class="d-flex">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" onclick="showDiv()" type="radio"
                                            name="type_of_suspension" value="Due to Disaster" id="flexRadioDefault1">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Due to Disaster
                                        </label>
                                    </div>
                                    <div onclick="hideDiv()" class="form-check">
                                        <input class="form-check-input" value="Unsatisfactory Output" type="radio"
                                            name="type_of_suspension" id="flexRadioDefault2" checked>
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            Unsatisfactory Output
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class=" form-group mb-3" id="myDiv" style="display: none;">
                                <label for="suspendReason" class="badge text-muted">Number of suspension days</label>
                                <input type="number" class="form-control" id="suspendReason" name="no_of_days" rows="3"
                                    placeholder="Enter reason for suspension">
                            </div>
                            <div class=" form-group">
                                <label for="suspendReason" class="badge text-muted">Reason for Suspension</label>
                                <textarea class="form-control" id="suspendReason" name="reason" rows="3"
                                    placeholder="Enter reason for suspension"></textarea>
                            </div>
                            <div class=" form-group">
                                <input type="hidden" name="contract_id" value="<?= $getContract['id'] ?>">
                                <input type="hidden" name="account_no" value="<?= $getContract['account_no'] ?>">
                                <input type="hidden" name=" contract_type" value="<?= $getContract['contract_type'] ?>">
                            </div>
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if ($getContract['contract_type'] === TRANS_RENT ) {
            $start = new DateTime($getContract['rent_start']);
            $end = new DateTime($getContract['rent_end']);
        } else {
            $start = new DateTime($getContract['contract_start']);
            $end = new DateTime($getContract['contract_end']);
        }
        $today = new DateTime();
        $interval = $today->diff($end);
        $remainingDays = $interval->invert ? -$interval->days : $interval->days;
        // Check if the contract is about to expire or already expired
        if ($remainingDays > 0 && $remainingDays <= 15) {
            echo '
            <div class="alert alert-info text-center border-info display-2 p-2" role="alert">
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
        } elseif ($getContract['contract_status'] === 'Suspended') {
            echo '<div class="alert alert-warning text-center display-2 p-2" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="90" height="90" fill="currentColor" class="bi bi-pause-circle-fill" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                    </svg>
                    THIS CONTRACT HAS BEEN SUSPENDED!
                    </div>';
        }
        ?>
        
        <?php
        $id = $getContract['account_no'] ?? $getContract['id'];
        $suspended = (new SuspensionController)->getSuspensionByAccount_no($id);
        $num_o_days = $suspended['no_of_days'] ?? 0;
        $suspension_start = $suspended['created_at'] ?? null;
        $suspensionType = $suspended['type_of_suspension'] ?? '';
        // Format created_at for JS (must be in a valid ISO 8601 format)
        $formattedStart = $suspension_start ? date('Y-m-d\TH:i:s', strtotime($suspension_start)) : null;
        ?>
        <?php if ($getContract['contract_status'] === 'Suspended'): ?>
            
            <?php if ($suspensionType === DTD): ?>
                <div id="draggable" class="card" style="
                    box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
                        font-weight: bold;
                        color: red;
                        background-color: #ebebf7;
                        padding: 40px;
                        position: absolute;
                        margin: 3% 5% 7% 11%;
                        width: 20em;
                        text-align: center;
                        font-size: 50px;
                        z-index: 99;">
                </div>
            <?php endif; ?>
            <?php if ($suspensionType === UNSAS): ?>
                <div id="draggable" class="card display" style="
                    box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
                        font-weight: bold;
                        color: red;
                        background-color: #ebebf7;
                        padding: 40px;
                        position: absolute;
                        margin: 3% 5% 7% 11%;
                        width: 20em;
                        text-align: center;
                        font-size: 50px;
                        z-index: 99;">
                </div>
            <?php endif; ?>
        <?php endif; ?>

    <?php if(in_array($user_role, ['MSD','HRAD'])): ?>

        <?php if ($department === $getContract['uploader_department'] || $department === $getContract['department_assigned'] || $department === $getContract['implementing_dept']) { ?>
            <div class="gap-1"><?php if ($getContract['contract_status'] === 'Expired') { ?>
                    <?php if ($getContract['contract_type'] === TEMP_LIGHTING): ?> <span id="add"
                            style="float:inline-end;display:inline;" data-bs-toggle="modal" data-bs-target="#tempLightModal"> <i
                                class="fa fa-plus" aria-hidden="true" style="width:40px;font-size:25px;"></i> </span>
                    <?php endif; ?>
                    <?php if ($getContract['contract_type'] === TRANS_RENT): ?>
                        <span id="add" style="float:inline-end;display:inline;" data-bs-toggle="modal"
                            data-bs-target="#transformerModal"> <i class="fa fa-plus" aria-hidden="true"
                                style="width:40px;font-size:25px;"></i> </span>
                    <?php endif; ?>     <?php } ?> <span id="close" style="float: inline-end;display:none;"><i class="fa fa-times"
                        style="width:40px;font-size:25px;" aria-hidden="true"></i></span><span id="save"
                    style="float: inline-end;display:none;"><i class="fa fa-floppy-o" aria-hidden="true"
                        style="width:40px;font-size:25px;" alt=""></i></span><span id="edit"
                    style="float: inline-end;display:inline;"><i class="fa fa-pencil-square-o" aria-hidden="true"
                        style="width:40px;font-size:25px;" alt=""></i></span>
            </div>
            <?php } ?>
    <?php endif; ?>

        <div class="mt-3 col-md-12 d-flex gap-5">
            <div class="row col-md-2"><input type="hidden" id="contractId" style="margin-left:9px;"
                    class="form-control pl-5" value="<?= $getContract['id']; ?>" name="id" readonly>
                <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">Contract Name:</label><input
                        type="text" id="contractName" style="margin-left:9px;" class="form-control pl-5"
                        value="<?= $getContract['contract_name']; ?>" name="contract_name" readonly></div>
            </div>


            <?php if($getContract['contract_type'] === SACC ): ?>
                <div class="row col-md-2">
                <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">Start Date:</label>
                    <div class="d-flex"><i class="fa fa-calendar p-2" style="font-size: 20px;"
                            aria-hidden="true"></i><?php if ($getContract['contract_type'] === SACC ): ?>
                            <?php
                            $rentstart = date('Y-m-d', strtotime($getContract['contract_start']));
                            ?> <input type="date" id="saccStartDate" style="margin-left:px;"
                                class="form-control pl-5" value="<?= $rentstart ?>" name="rent_start"
                                readonly><?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php if($getContract['contract_type'] === SACC ): ?>
                <div class="row col-md-2">
                <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">End Date:</label>
                    <div class="d-flex"><i class="fa fa-calendar p-2" style="font-size: 20px;"
                            aria-hidden="true"></i><?php if ($getContract['contract_type'] === SACC ): ?>
                            <?php
                            $rentstart = date('Y-m-d', strtotime($getContract['contract_end']));
                            ?> <input type="date" id="saccEndDate" style="margin-left:px;"
                                class="form-control pl-5" value="<?= $rentstart ?>" name="rent_start"
                                readonly><?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if($getContract['contract_type'] === INFRA): ?>
                <div class="row col-md-2">
                <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">Start Date:</label>
                    <div class="d-flex"><i class="fa fa-calendar p-2" style="font-size: 20px;"
                            aria-hidden="true"></i><?php if ($getContract['contract_type'] === INFRA): ?>
                            <?php
                            $rentstart = date('Y-m-d', strtotime($getContract['contract_start']));
                            ?> <input type="date" id="infraStart" style="margin-left:px;"
                                class="form-control pl-5" value="<?= $rentstart ?>" name="rent_start"
                                readonly><?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php if($getContract['contract_type'] === INFRA): ?>
                <div class="row col-md-2">
                <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">End Date:</label>
                    <div class="d-flex"><i class="fa fa-calendar p-2" style="font-size: 20px;"
                            aria-hidden="true"></i><?php if ($getContract['contract_type'] === INFRA): ?>
                            <?php
                            $rentstart = date('Y-m-d', strtotime($getContract['contract_end']));
                            ?> <input type="date" id="infraEnd" style="margin-left:px;"
                                class="form-control pl-5" value="<?= $rentstart ?>" name="rent_start"
                                readonly><?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

              <?php if($getContract['contract_type'] === GOODS ) : ?>
            <div class="row col-md-2">
                <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">Start Date:</label>
                    <div class="d-flex"><i class="fa fa-calendar p-2" style="font-size: 20px;"
                            aria-hidden="true"></i><?php if ($getContract['contract_type'] === GOODS): ?>
                            <?php
                                $rentstart = date('Y-m-d', strtotime($getContract['contract_start']));
                                $formatted = date('M/d/Y', strtotime($getContract['contract_start']));
                            ?> 
                            <input type="text" id="goodsStart2" style="margin-left:px;width:10em;" class="form-control pl-5" value="<?= $formatted ?>" readonly>
                            <input type="date" id="goodsStart1" style="margin-left:px;width:10em;" class="form-control pl-5" value="<?= $rentstart ?>" hidden>
                            <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <?php endif; ?>
            <?php if($getContract['contract_type'] === GOODS ) : ?>
            <div class="row col-md-2">
                <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">End Date:</label>
                    <div class="d-flex"><i class="fa fa-calendar p-2" style="font-size: 20px;"
                            aria-hidden="true"></i><?php if ($getContract['contract_type'] === GOODS): ?>
                            <?php
                                $rentEnd = date('Y-m-d', strtotime($getContract['contract_end']));
                                $formatted2 = date('M/d/Y', strtotime($getContract['contract_end']));
                            ?>
                            <input type="text" id="goodsEnd2" style="margin-left:px;" class="form-control pl-5" value="<?= $formatted2 ?>" name="rent_end" readonly>
                            <input type="date" id="goodsEnd1" style="margin-left:px;" class="form-control pl-5" value="<?= $rentEnd ?>" name="rent_end" hidden>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if($getContract['contract_type'] === EMP_CON ) : ?>
            <div class="row col-md-2">
                <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">Start Date:</label>
                    <div class="d-flex"><i class="fa fa-calendar p-2" style="font-size: 20px;"
                            aria-hidden="true"></i><?php if ($getContract['contract_type'] === EMP_CON): ?>
                            <?php
                                $rentstart = date('Y-m-d', strtotime($getContract['contract_start']));
                                $formatted = date('M-d-Y', strtotime($getContract['contract_start']));
                            ?> 
                            <input type="text" id="empConStart2" style="margin-left:px;width:10em;" class="form-control pl-5" value="<?= $formatted ?>" readonly>
                            <input type="date" id="empConStart1" style="margin-left:px;width:10em;" class="form-control pl-5" value="<?= $rentstart ?>" hidden>
                            <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <?php endif; ?>
            <?php if($getContract['contract_type'] === EMP_CON ) : ?>
            <div class="row col-md-2">
                <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">End Date:</label>
                    <div class="d-flex"><i class="fa fa-calendar p-2" style="font-size: 20px;"
                            aria-hidden="true"></i><?php if ($getContract['contract_type'] === EMP_CON): ?>
                            <?php
                                $rentEnd = date('Y-m-d', strtotime($getContract['contract_end']));
                                $formatted2 = date('M-d-Y', strtotime($getContract['contract_end']));
                            ?>
                            <input type="text" id="empConEnd2" style="margin-left:px;" class="form-control pl-5" value="<?= $formatted2 ?>" name="rent_end" readonly>
                            <input type="date" id="empConEnd1" style="margin-left:px;" class="form-control pl-5" value="<?= $rentEnd ?>" name="rent_end" hidden>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if($getContract['contract_type'] === TRANS_RENT ) : ?>
            <div class="row col-md-2">
                <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">Installation Date:</label>
                    <div class="d-flex"><i class="fa fa-calendar p-2" style="font-size: 20px;"
                            aria-hidden="true"></i><?php if ($getContract['contract_type'] === TRANS_RENT): ?>
                            <?php
                                $rentstart = date('Y-m-d', strtotime($getContract['rent_start']));
                                $formatted = date('M-d-Y', strtotime($getContract['rent_start']));
                            ?> 
                            <input type="text" id="startTransRent2" style="margin-left:px;width:10em;" class="form-control pl-5" value="<?= $formatted ?>" name="rent_start" readonly>
                            <input type="date" id="startTransRent1" style="margin-left:px;width:10em;" class="form-control pl-5" value="<?= $rentstart ?>" name="rent_start" hidden>
                            <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <?php endif; ?>
            <?php if($getContract['contract_type'] === TRANS_RENT ) : ?>
            <div class="row col-md-2">
                <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">Retirement Date:</label>
                    <div class="d-flex"><i class="fa fa-calendar p-2" style="font-size: 20px;"
                            aria-hidden="true"></i><?php if ($getContract['contract_type'] === TRANS_RENT): ?>
                            <?php
                                $rentEnd = date('Y-m-d', strtotime($getContract['rent_end']));
                                $formatted2 = date('M-d-Y', strtotime($getContract['rent_end']));
                            ?>
                            <input type="text" id="endTransRent2" style="margin-left:px;" class="form-control pl-5" value="<?= $formatted2 ?>" name="rent_end" readonly>
                            <input type="date" id="endTransRent1" style="margin-left:px;" class="form-control pl-5" value="<?= $rentEnd ?>" name="rent_end" hidden>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if($getContract['contract_type'] === TEMP_LIGHTING ) : ?>
            <div class="row col-md-2">
                <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">Installation Date:</label>
                    <div class="d-flex"><i class="fa fa-calendar p-2" style="font-size: 20px;"
                            aria-hidden="true"></i><?php if ($getContract['contract_type'] === TEMP_LIGHTING): ?>
                            <?php
                            $rentstart = date('Y-m-d', strtotime($getContract['contract_start']));
                            ?> 
                            <input type="date" id="tempLightStart" style="margin-left:px;" class="form-control pl-5" value="<?= $rentstart ?>" name="contract_start" readonly><?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php if($getContract['contract_type'] === TEMP_LIGHTING ) : ?>
            <div class="row col-md-2">
                <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">Retirement Date:</label>
                    <div class="d-flex"><i class="fa fa-calendar p-2" style="font-size: 20px;"
                            aria-hidden="true"></i><?php if ($getContract['contract_type'] === TEMP_LIGHTING): ?>
                            <?php
                            $rentEnd = date('Y-m-d', strtotime($getContract['contract_end']));
                            ?>
                        <input type="date" id="tempLightEnd" style="margin-left:px;" class="form-control pl-5" value="<?= $rentEnd ?>" name="contract_start" readonly><?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <div class="row col-md-2">
                <div class="mt-3"><label class="badge text-muted" <?php
                if ($getContract['contract_type'] === TRANS_RENT) {
                    $start = new DateTime($getContract['rent_start']);
                    $end = new DateTime($getContract['rent_end']);
                } else {
                    $start = new DateTime($getContract['contract_start']);
                    $end = new DateTime($getContract['contract_end']);
                }
                $today = new DateTime();
                $interval = $today->diff($end);
                $remainingDays = $interval->invert ? -$interval->days : $interval->days;
                ?>
                        style="font-size: 15px;">Days Remaining:</label>
                    <div class="d-flex"><input type="text" style="margin-left:7px;" class="form-control"
                            value=" <?= $remainingDays ?> day<?= $remainingDays != 1 ? 's' : '' ?>" readonly><?php
                                $remainingDays;
                                if ($remainingDays === 0) {
                                    $data = [
                                        'id' => $getContract['id'],
                                        'contract_status' => 'Expired',
                                        ];
                                    (new ContractController)->updateStatusExpired($data);
                            } else {
                        }
                    ?> </div>
                </div>
            </div>
            <div class="row col-md-2">
                <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">Status</label>
                    <div class="d-flex">
                        <?php if (!$getContract['contract_status'] == 'Active' | $getContract['contract_status'] == 'Expired'): ?>
                            <i class="fa fa-ban p-2" style="color:#BF3131;font-size: 20px;" aria-hidden="true"></i>
                            <span class="alert p-1 alert-warning border-danger text-danger text-center"
                                style="width: 7em;"><?= $getContract['contract_status']; ?></span>
                        <?php elseif ($getContract['contract_status'] == 'Suspended'): ?>
                            <i class="fa fa-pause-circle p-2" style="color:green;font-size: 30px;margin-top:-6px;"
                                aria-hidden="true"></i><span
                                class="alert p-1 alert-warning border-warning text-danger text-center"
                                style="width: 7em;"><?= $getContract['contract_status']; ?></span>
                        <?php else: ?>
                            <i class="fa fa-check p-2" style="color:green;font-size: 20px;" aria-hidden="true"></i><span
                                class="alert p-1 alert-success border-success text-success text-center"
                                style="width: 7em;"><?= $getContract['contract_status']; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <!---- 2nd row ------>
        <div class="mt-3 col-md-12 d-flex gap-5">
            
        <?php 
            $getContractTypes = ( new ContractTypeController)->getContractTypes();

        ?>

            <div class="row col-md-2">
                <div class="mt-3">
                    <label class="badge text-muted" style="font-size: 15px;">Contract type:</label>

                        <input type="text" id="contractTypeInput" style="margin-left:9px;"
                        class="form-control pl-5" value="<?= $getContract['contract_type']; ?>" name="contract_type"
                        readonly>
                    
                        <select class="p-1 form-select" name="contract_type" id="contractTypeSelect" style="width:12em;margin-left:9px;" hidden>
                            <option value="<?= $getContract['contract_type']; ?>"><?= $getContract['contract_type']; ?></option>
                            <?php foreach($getContractTypes as $types): ?>
                                <option value="<?= $types['contract_type'] ?>"><?= $types['contract_type'] ?></option>
                            <?php endforeach; ?>
                        </select>

                </div>
            </div>
            
            <?php if($getContract['address']): ?>
            <div class="row col-md-2">
                <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">Address:</label><input
                    type="text" id="address" style="margin-left:9px;" class="form-control pl-5"
                    value="<?= $getContract['address']; ?>" name="address" readonly>
                </div>
            </div>
            <?php endif; ?>

            <?php if($getContract['tc_no']): ?>
            <div class="row col-md-2">
                <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">TC no:</label><input
                    type="text" id="tc_no" style="margin-left:9px;" class="form-control pl-5"
                    value="<?= $getContract['tc_no']; ?>" name="address" readonly>
                </div>
            </div>
            <?php endif; ?>

            <?php if($getContract['account_no']): ?>
            <div class="row col-md-2">
                <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">Account #:</label><input
                    type="text" id="accountNumber" style="margin-left:9px;" class="form-control pl-5"
                    value="<?= $getContract['account_no']; ?>" name="account_no" readonly>
                </div>
            </div>
            <?php endif; ?>

            <?php if($getContract['party_of_second_part']): ?>
                <div class="row col-md-2">
                    <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">Party of Second Part:</label><input
                            type="text" id="address" style="margin-left:9px;" class="form-control pl-5"
                            value="<?= $getContract['party_of_second_part']; ?>" name="address" readonly>
                        </div>
                </div>
            <?php endif; ?>
                
                
                <?php if (!empty($getContract['contractPrice'])): ?>


                <div class="row col-md-2">

                <script>

                    //peso format
                        document.addEventListener('DOMContentLoaded', function () {
                            const input = document.getElementById('ttc');

                            // Remove ₱ and commas on focus so user can type a clean number
                            input.addEventListener('focus', function () {
                                let rawValue = input.value.replace(/[₱,]/g, '').trim();
                                input.value = rawValue;
                            });

                            // Format input on keyup
                            input.addEventListener('input', function () {
                                let value = input.value.replace(/[₱,]/g, '').trim();

                                if (!isNaN(value) && value !== "") {
                                    input.value = '₱' + Number(value).toLocaleString();
                                } else if (value === "") {
                                    input.value = "";
                                }
                            });
                        });
                </script>
                    
                <?php if(!empty($getContract['contractPrice'])): ?>
                    <div class="mt-3">
                        <label class="badge text-muted" style="font-size: 15px;">Total Contract
                            cost</label>
                            <input type="text" id="ttc" style="margin-left:9px;"
                            class="form-control pl-5" value="₱<?=  $getContract['contractPrice']; ?>"
                            name="contract_type" disabled>
                        </div>
                </div>
                <?php endif; ?>

                <?php endif; ?> <?php if (!$getContract['supplier']): ?> <?php else: ?>
                <div class="row col-md-2">
                    <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">Supplier</label><input
                            type="text" id="contractInput" style="margin-left:9px;" class="form-control pl-5"
                            value="<?= $getContract['supplier']; ?>" name="contract_type" readonly></div>
                </div><?php endif; ?> 

                <?php if ($getContract['contract_type'] === INFRA): ?>
                    <?php if(!empty($getContract['implementing_dept'])): ?>
                <div class="row col-md-2">
                    <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">Implementing
                            Department</label><input type="text" id="impDept" style="margin-left:9px;"
                            class="form-control pl-5" value="<?= $getContract['implementing_dept'] ?>" name="contract_type"
                            readonly></div>
                </div>
                    <?php endif; ?>
                <?php endif; ?>
                 <?php if ($getContract['contract_type'] === EMP_CON): ?>
                <div class="row col-md-2">
                    <div class="mt-3">

                        <label class="badge text-muted" style="font-size: 15px;">Assigned
                            Department</label>
                            <input type="text" id="deptSelect" style="margin-left:9px;"
                            class="form-control pl-5" value="<?= $getContract['department_assigned']; ?>"
                            name="contract_type" readonly>


                        </div>
                </div>
                <?php endif; ?>

            <div class="row col-md-3">
                <div class="mt-3">
                    <!-- <label class="badge text-muted" style="font-size: 15px;">Department
                        Assigned:</label><select id="deptSelect" class="form-select" style="margin-left:9px;"
                            disabled><?php foreach ($departments as $department): ?>
                                <option value="<?= $department['department_name']; ?>"
                                    <?= ($department['department_name'] == $getContract['department_assigned']) ? 'selected' : ''; ?>><?= $department['department_name']; ?> </option>
                            <?php endforeach; ?>
                        </select>--><!-- <input type="text" style="margin-left:9px;" class="form-control pl-5"
                            value="<?= $getContract['department_assigned']; ?>" name="department_assigned" readonly>
                            -->
                </div>
            </div>
            <!-- <div class="row col-md-2">
                <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">Status</label>
                    <div class="d-flex">
                        <?php if (!$getContract['contract_status'] == 'Active' | $getContract['contract_status'] == 'Expired'): ?>
                            <i class="fa fa-ban p-2" style="color:#BF3131;font-size: 20px;" aria-hidden="true"></i><span
                                class="alert p-1 alert-warning border-danger text-danger text-center"
                                style="width: 7em;"><?= $getContract['contract_status']; ?></span><?php else: ?> <i
                                class="fa fa-check p-2" style="color:green;font-size: 20px;" aria-hidden="true"></i><span
                                class="alert p-1 alert-success border-success text-success text-center"
                                style="width: 7em;"><?= $getContract['contract_status']; ?></span><?php endif; ?>
                    </div>
                </div>
        </div>-->
        </div>
        <div class="mt-3 col-md-12 d-flex gap-5">
            <div class="row col-md-2">
                <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">Uploading Dept:</label><input
                        type="text" style="margin-left:9px;" class="form-control pl-5" id="uploadingDept"
                        value="<?= $getContract['uploader_department']; ?>" name="contract_type" readonly></div>
            </div><?php

            $getUser = (new UserController)->getUserById($getContract['uploader_id']);

            ?>
            <div class="row col-md-3">
                <div class="mt-3"><label class="badge text-muted" style="font-size: 15px;">Uploaded by:</label><input
                        type="text" style="margin-left:9px;" class="form-control pl-5"
                        id = "uploadedBy"
                        value="<?= $getUser['firstname'] . ' ' . $getUser['middlename'] . ' ' . $getUser['lastname']; ?>"
                        name="contract_type" readonly></div>
            </div>
            <div class="row col-md-3 mt-4 float-end">
                <div class="mt-3 float-end" style="margin-left: 90%;"><?php
                $dept = $_SESSION['department'];
                ?> <?php if ($dept === 'ISD-HRAD'): ?>
                        <?php
                        $start = new DateTime($getContract['contract_start']);
                        $end = new DateTime($getContract['contract_end']);
                        $today = new DateTime();

                        // Calculate remaining days (positive if end date is in the future)
                        $interval = $today->diff($end);
                        $remainingDays = $interval->invert ? -$interval->days : $interval->days;
                        ?>
                        <?php if ($remainingDays <= 15 && $remainingDays >= 0): ?>
                            <div class="d-flex gap-2"><button class="btn btn-primary" data-id="<?= $getContract['id'] ?>"
                                    data-contractname="<?= $getContract['contract_name'] ?>"
                                    data-startdate="<?= $getContract['contract_start'] ?>"
                                    data-enddate="<?= $getContract['contract_end'] ?>"
                                    data-departmentassigned="<?= $getContract['department_assigned'] ?>"
                                    data-type="<?= $getContract['contract_type'] ?>" data-bs-toggle="modal"
                                    data-bs-target="#extendModal">Extend </button>
                                <form action="contracts/end_contract.php" method="post"><input type="hidden" name="contract_id"
                                        value="<?= $getContract['id'] ?>"><button type="submit" class="btn btn-warning">End
                                        Contract</button></form>
                            </div><?php endif; ?> <?php endif; ?>
                </div>
            </div>
            <!-- Extend Modal -->
            <div class="modal fade" id="extendModal" data-bs-backdrop="static" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Extend Contract</h5><button type="button"
                                class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="contracts/renew_contract.php" method="POST" enctype="multipart/form-data">
                                <div class="d-flex col-md-12 gap-3 p-4">
                                    <div class="col-md-6">
                                        <div class="mb-2"><input type="hidden" id="contract_id" name="contract_id"
                                                class="form-control"><label for="contract_name"
                                                class="form-label badge text-muted">Contract Name</label><input
                                                type="text" id="contract_name" name="contract_name"
                                                class="form-control"></div>
                                        <div class="mb-2"><label for="start_date"
                                                class="form-label badge text-muted">Start
                                                Date</label><input type="date" id="start_date" name="contract_start"
                                                class="form-control"></div>
                                        <div class="mb-2"><label for="start_date"
                                                class="form-label badge text-muted">Contract File</label><input
                                                type="file" id="start_date" name="contract_file" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2"><label for="department_assigned"
                                                class="form-label badge text-muted">Department Assigned</label><select
                                                id="department_assigned" name="department_assigned" class="form-select">
                                                <option value="" hidden>Select Department</option>
                                                <?php foreach ($departments as $dept): ?>
                                                    <option value="<?= $dept['department_name'] ?>">
                                                        <?= $dept['department_name'] ?>
                                                    </option><?php endforeach; ?>
                                            </select></div>
                                        <div class="mb-2"><label for="end_date" class="form-label badge text-muted">End
                                                Date</label><input type="date" id="end_date" name="contract_end"
                                                class="form-control"></div>
                                        <div class="mb-2"><label for="start_date"
                                                class="form-label badge text-muted">Contract Type</label><input
                                                type="text" id="contract_type" name="contract_type"
                                                class="form-control"></div>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Close</button>--><button type="submit" class="btn btn-success">Renew Contract</button>
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
                                    File</span>
                            </th>
                            <th style="text-align: center !important;"><span class="badge text-muted">Date Start</span>
                            </th>
                            <th style="text-align: center !important;"><span class="badge text-muted">Date End</span>
                            </th>
                            <!-- <th style="text-align: center !important;"><span class="badge text-muted">Action</span>
                            </th>-->
                        </tr>
                    </thead>
                    <?php
                    // $id = $getContract['account_no'];
                    // $status = $getContract['contract_status'];
                    // $contractHist_datas = (new ContractHistoryController)->getByContractId($id);

                    if($getContract['contract_type'] === EMP_CON){
                        $id = $getContract['id'];
                        $status = $getContract['contract_status'];
                        $contractHist_datas = (new ContractHistoryController)->getByContractId($id);
                        
                    }

                    if($getContract['contract_type'] === TRANS_RENT){
                        $id = $getContract['account_no'];
                        $status = $getContract['contract_status'];
                        $contractHist_datas = (new ContractHistoryController)->getByContractId($id);
                    }

                    if($getContract['contract_type'] === TEMP_LIGHTING){
                        $id = $getContract['account_no'];
                        $status = $getContract['contract_status'];
                        $contractHist_datas = (new ContractHistoryController)->getByContractId($id);
                    }

                    if($getContract['contract_type'] === INFRA){
                        $id = $getContract['id'];
                        $status = $getContract['contract_status'];
                        $contractHist_datas = (new ContractHistoryController)->getByContractId($id);
                    }

                    if($getContract['contract_type'] === GOODS){
                        $id = $getContract['id'];
                        $status = $getContract['contract_status'];
                        $contractHist_datas = (new ContractHistoryController)->getByContractId($id);
                    }

                    if($getContract['contract_type'] === SACC){
                        $id = $getContract['id'];
                        $status = $getContract['contract_status'];
                        $contractHist_datas = (new ContractHistoryController)->getByContractId($id);
                    }



                    if ($status === 'Expired') {

                        $stat = [
                            'id' => $getContract['account_no'],
                            'status' => 'Expired',
                        ];

                        $updateStatus = (new ContractHistoryController)->updateStatus($stat);

                    }

                    // var_dump($contractHist_datas);
                    
                    ?>
                    <tbody class=""><?php if (!empty($contractHist_datas)): ?>
                            <?php foreach ($contractHist_datas as $employement_data): ?>
                                <tr>
                                    <td style="text-align: center !important;">
                                        <?php if ($employement_data['status'] == 'Active'): ?> <span
                                                class="badge bg-success p-2"><?= $employement_data['status']; ?></span><?php elseif ($employement_data['status'] == 'Expired'): ?>
                                            <span class="badge bg-danger p-2">Rental Contract Ended</span><?php else: ?> <span
                                                class="badge text-dark bg-warning p-2">Employment Contract
                                                ended</span><?php endif; ?>
                                    </td>
                                    <td style="text-align: center !important;">
                                        <?php if (!empty($employement_data['contract_file'])): ?>
                                            <!-- Trigger the modal with this button --><button class="btn btn-primary badge p-2"
                                                data-bs-toggle="modal" data-bs-target="#fileModal<?= $employement_data['id'] ?>"
                                                style="text-align: center !important;">View file </button>
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
                                                            </h5><button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body" style="padding: 0; overflow-y: auto;">
                                                            <!-- Display the contract file inside the modal --><iframe
                                                                src="<?= htmlspecialchars("../../../" . $employement_data['contract_file']) ?>"
                                                                width="100%" style="height: 80vh;" frameborder="0"></iframe>
                                                        </div>
                                                        <div class="modal-footer"><button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><?php else: ?> No file <?php endif; ?>
                                    </td>
                                    
                                    <?php if ($employement_data['contract_type'] === EMP_CON): ?>
                                        <td style="text-align: center !important;">
                                            <?php if (!empty($employement_data['date_start'])): ?>
                                                <?php $datestart = new DateTime($employement_data['date_start']); ?> <span
                                                    class="badge text-dark"><?= date_format($datestart, "M-d-Y"); ?></span><?php else: ?>
                                                <span class="badge text-danger">No Start Date</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align: center !important;">
                                            <?php if (!empty($employement_data['date_end'])): ?>
                                                <?php $datestart = new DateTime($employement_data['date_end']); ?> <span
                                                    class="badge text-dark"><?= date_format($datestart, "M-d-Y"); ?></span><?php else: ?>
                                                <span class="badge text-danger">No Start Date</span><?php endif; ?>
                                        </td>
                                    <?php endif; ?>

                                    <?php if ($employement_data['contract_type'] === TRANS_RENT): ?>
                                        <td style="text-align: center !important;">
                                            <?php if (!empty($employement_data['rent_start'])): ?>
                                                <?php $datestart = new DateTime($employement_data['rent_start']); ?> <span
                                                    class="badge text-dark"><?= date_format($datestart, "M-d-Y"); ?></span><?php else: ?>
                                                <span class="badge text-danger">No Start Date</span><?php endif; ?>
                                        </td>
                                        <td style="text-align: center !important;">
                                            <?php if (!empty($employement_data['rent_end'])): ?>
                                                <?php $datestart = new DateTime($employement_data['rent_end']); ?> <span
                                                    class="badge text-dark"><?= date_format($datestart, "M-d-Y"); ?></span><?php else: ?>
                                                <span class="badge text-danger">No Start Date</span><?php endif; ?>
                                        </td><?php endif; ?>

                                    <?php if ($employement_data['contract_type'] === TEMP_LIGHTING): ?>
                                        <td style="text-align: center !important;">
                                            <?php if (!empty($employement_data['date_start'])): ?>
                                                <?php $datestart = new DateTime($employement_data['date_start']); ?> <span
                                                    class="badge text-dark"><?= date_format($datestart, "M-d-Y"); ?></span><?php else: ?>
                                                <span class="badge text-danger">No Start Date</span><?php endif; ?>
                                        </td>
                                        <td style="text-align: center !important;">
                                            <?php if (!empty($employement_data['date_end'])): ?>
                                                <?php $datestart = new DateTime($employement_data['date_end']); ?> <span
                                                    class="badge text-dark"><?= date_format($datestart, "M-d-Y"); ?></span><?php else: ?>
                                                <span class="badge text-danger">No Start Date</span><?php endif; ?>
                                        </td><?php endif; ?>
                                        

                                         <?php if ($employement_data['contract_type'] === INFRA): ?>
                                        <td style="text-align: center !important;">
                                            <?php if (!empty($employement_data['date_start'])): ?>
                                                <?php $datestart = new DateTime($employement_data['date_start']); ?> <span
                                                    class="badge text-dark"><?= date_format($datestart, "M-d-Y"); ?></span><?php else: ?>
                                                <span class="badge text-danger">No Start Date</span><?php endif; ?>
                                        </td>
                                        <td style="text-align: center !important;">
                                            <?php if (!empty($employement_data['date_end'])): ?>
                                                <?php $datestart = new DateTime($employement_data['date_end']); ?> <span
                                                    class="badge text-dark"><?= date_format($datestart, "M-d-Y"); ?></span><?php else: ?>
                                                <span class="badge text-danger">No Start Date</span><?php endif; ?>
                                        </td><?php endif; ?>

                                         <?php if ($employement_data['contract_type'] === GOODS): ?>
                                        <td style="text-align: center !important;">
                                            <?php if (!empty($employement_data['date_start'])): ?>
                                                <?php $datestart = new DateTime($employement_data['date_start']); ?> <span
                                                    class="badge text-dark"><?= date_format($datestart, "M-d-Y"); ?></span><?php else: ?>
                                                <span class="badge text-danger">No Start Date</span><?php endif; ?>
                                        </td>
                                        <td style="text-align: center !important;">
                                            <?php if (!empty($employement_data['date_end'])): ?>
                                                <?php $datestart = new DateTime($employement_data['date_end']); ?> <span
                                                    class="badge text-dark"><?= date_format($datestart, "M-d-Y"); ?></span><?php else: ?>
                                                <span class="badge text-danger">No Start Date</span><?php endif; ?>
                                        </td><?php endif; ?>

                                        <?php if ($employement_data['contract_type'] === SACC): ?>
                                        <td style="text-align: center !important;">
                                            <?php if (!empty($employement_data['date_start'])): ?>
                                                <?php $datestart = new DateTime($employement_data['date_start']); ?> <span
                                                    class="badge text-dark"><?= date_format($datestart, "M-d-Y"); ?></span><?php else: ?>
                                                <span class="badge text-danger">No Start Date</span><?php endif; ?>
                                        </td>
                                        <td style="text-align: center !important;">
                                            <?php if (!empty($employement_data['date_end'])): ?>
                                                <?php $datestart = new DateTime($employement_data['date_end']); ?> <span
                                                    class="badge text-dark"><?= date_format($datestart, "M-d-Y"); ?></span><?php else: ?>
                                                <span class="badge text-danger">No Start Date</span><?php endif; ?>
                                        </td><?php endif; ?>
                                    <!-- <td style="text-align: center !important;"><button class="btn btn-danger btn-sm"
                                        title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button><button
                                        class="btn btn-primary btn-sm" title="Edit" data-bs-toggle="modal"
                                        data-bs-target="#editModal"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                    </td>-->
                                </tr><?php endforeach; ?> <?php else: ?>
                            <tr>
                                <td colspan="4">No contract data found.</td>
                            </tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php

$getUser = (new UserController)->getUserById($getContract['uploader_id']);

?>
<!-- Extend Modal -->
<div class="modal fade" id="extendModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Extend Contract</h5><button type="button"
                    class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="contracts/renew_contract.php" method="POST" enctype="multipart/form-data">
                    <div class="d-flex col-md-12 gap-3 p-4">
                        <div class="col-md-6">
                            <div class="mb-2"><input type="hidden" id="contract_id" name="contract_id"
                                    class="form-control"><label for="contract_name"
                                    class="form-label badge text-muted">Contract Name</label><input type="text"
                                    id="contract_name" name="contract_name" class="form-control"
                                    value="<?= $getContract['contract_name'] ?>"></div>
                            <div class="mb-2"><label for="start_date" class="form-label badge text-muted">Start
                                    Date</label><input type="date" id="start_date" name="contract_start"
                                    class="form-control"></div>
                            <div class="mb-2"><label for="start_date" class="form-label badge text-muted">Contract
                                    File</label><input type="file" id="start_date" name="contract_file"
                                    class="form-control"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2"><label for="department_assigned"
                                    class="form-label badge text-muted">Department Assigned</label><select
                                    id="department_assigned" name="department_assigned" class="form-select">
                                    <option value="" hidden>Select Department</option>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?= $dept['department_name'] ?>"><?= $dept['department_name'] ?>
                                        </option><?php endforeach; ?>
                                </select></div>
                            <div class="mb-2"><label for="end_date" class="form-label badge text-muted">End
                                    Date</label><input type="date" id="end_date" name="contract_end"
                                    class="form-control"></div>
                            <div class="mb-2"><label for="start_date" class="form-label badge text-muted">Contract
                                    Type</label><input type="text" id="contract_type" name="contract_type"
                                    class="form-control"></div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close</button>--><button type="submit" class="btn btn-success">Renew Contract</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="transformerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-lg" style="width: 45em;
 margin-left: -5em;
            ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Transformer Rental Contract</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">--><span
                    aria-hidden="true">&times;
                </span></button>
            </div>
            <div class="modal-body"><?php $department = $_SESSION['department'] ?? null; ?>
                <form action="contracts/transformerRent.php" method="post" enctype="multipart/form-data"><input
                        type="hidden" class="form-control" name="contract_type" value="<?= TRANS_RENT ?>" readonly>
                    <div class="col-md-12 d-block gap-2">
                        <div class="col-md-12 d-flex gap-2 row justify-content-center">
                            <div class="col-md-3 p-2" style="width: 13em;">
                                <div><input type="hidden" class="form-control" name="uploader_department"
                                        value="<?= $department ?>" required>
                                    <lable class="badge text-muted">Contract Name</lable><input type="text"
                                        class="form-control" value="<?= $getContract['contract_name'] ?>"
                                        name="contract_name" required>
                                </div>
                            </div>
                            <div class="col-md-3 p-2" style="width: 13em;">
                                <div>
                                    <lable class="badge text-muted">TC No.</lable><input type="text"
                                        class="form-control" name="tc_no" value="<?= $getContract['tc_no'] ?>" required>
                                </div>
                            </div>
                            <div class="col-md-3 p-2" style="width: 13em;">
                                <div>
                                    <lable class="badge text-muted">Account no.</lable><input type="text"
                                        class="form-control" value="<?= $getContract['account_no'] ?>" name="account_no"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 d-flex gap-5 row justify-content-center">
                            <div class="col-md-4 p-2" style="width: 15em;">
                                <div>
                                    <lable class="badge text-muted">Installation Date</lable>
                                    <div class="d-flex"><i class="fa fa-calendar p-2" style="font-size: 20px;"
                                            aria-hidden="true"></i><input type="date" id="rent_start"
                                            class="form-control" name="rent_start" required></div>
                                </div>
                            </div>
                            <div class="col-md-4 p-2" style="width: 15em;">
                                <div>
                                    <lable class="badge text-muted">Retirement Date</lable>
                                    <div class="d-flex"><i class="fa fa-calendar p-2" style="font-size: 20px;"
                                            aria-hidden="true"></i><input type="date" id="contract_end"
                                            class="form-control" name="rent_end" required></div>
                                </div>
                            </div>
                            <!-- <div class="col-md-4 p-2">
                                <div>
                                    <lable class="badge text-muted">Date End</lable><i class="fa fa-calendar p-2"
                                        style="font-size: 20px;" aria-hidden="true"></i><input type="date" id="date_end"
                                        class="form-control" name="contract_end" required>
                                </div>
                        </div>-->
                        </div>
                        <div class="col-md-12 d-flex gap-4 row justify-content-center">
                            <!-- <div class="col-md-5 p-2">
                            <div>
                                <lable class="badge text-muted">Party of Second Part</lable><input type="text"
                                    class="form-control" name="party_of_second_part" required>
                            </div>
                    </div>-->
                            <div class="col-md-5 p-2">
                                <div>
                                    <lable class="badge text-muted">Contract file</lable><input id="templighting"
                                        type="file" class="form-control" name="contract_file" style="width: 16.7em;"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4 p-2">
                                <div><?php
                                $userid;
                                $getUser = (new UserController)->getUserById($userid);

                                // var_dump($getUser['firstname']);
                                ?> <input type="hidden" id="date_start" class="form-control" name="uploader_id"
                                        value="<?= $userid ?>"><input type="hidden" id="date_start" class="form-control"
                                        name="uploader"
                                        value="<?= $getUser['firstname'] . ' ' . $getUser['middlename'] . ' ' . $getUser['lastname'] ?>"><input
                                        type="hidden" id="date_start" class="form-control" name="uploader_dept"
                                        value="<?= $department ?>" required></div>
                            </div>
                            <!-- <div class="col-md-4 p-2">
                        <div>
                            <lable class="badge text-muted">Date End</lable><input type="date" id="date_end"
                                class="form-control" name="contract_end" required>
                        </div>
            </div>-->
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--><button
                    type="submit" id="submittemplight" class="btn btn-primary" disabled onmouseover="pointer">Submit New
                    Contract</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>document.addEventListener("DOMContentLoaded", function () {
        const fileInputt = document.getElementById('templighting');
        const submitButton = document.getElementById('submittemplight');

        fileInputt.addEventListener('change', function () {
            submitButton.disabled = fileInputt.files.length === 0;
        });
    });
</script>
<div class="modal fade" id="tempLightModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-lg" style="width: 45em;
 margin-left: -5em;
            ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Temporary Lighting Contract</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">--><span
                    aria-hidden="true">&times;
                </span></button>
            </div>
            <div class="modal-body">
                <?php $department = $_SESSION['department'] ?? null; ?>
                <form action="contracts/transformerRent.php" method="post" enctype="multipart/form-data"><input
                        type="hidden" class="form-control" name="contract_type" value="<?= TEMP_LIGHTING ?>" readonly>
                    <div class="col-md-12 d-block gap-2">
                        <div class="col-md-12 d-flex gap-2 row justify-content-center">
                            <div class="col-md-3 p-2" style="width: 13em;">
                                <div><input type="hidden" class="form-control" name="uploader_department"
                                        value="<?= $department ?>" required>
                                    <lable class="badge text-muted">Contract Name</lable><input type="text"
                                        class="form-control" value="<?= $getContract['contract_name'] ?>"
                                        name="contract_name" required>
                                </div>
                            </div>
                            <div class="col-md-3 p-2" style="width: 13em;">
                                <div>
                                    <lable class="badge text-muted">TC No.</lable><input type="text"
                                        class="form-control" name="tc_no" value="<?= $getContract['tc_no'] ?>" required>
                                </div>
                            </div>
                            <div class="col-md-3 p-2" style="width: 13em;">
                                <div>
                                    <lable class="badge text-muted">Account no.</lable>
                                    <input type="text"
                                        class="form-control"  value="<?= $getContract['account_no'] ?>" name="account_no"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 d-flex gap-5 row justify-content-center">
                            <div class="col-md-4 p-2" style="width: 15em;">
                                <div>
                                    <lable class="badge text-muted">Installation Date</lable>
                                    <div class="d-flex"><i class="fa fa-calendar p-2" style="font-size: 20px;"
                                            aria-hidden="true"></i><input type="date" id="date_start"
                                            class="form-control" name="date_start" required></div>
                                </div>
                            </div>
                            <div class="col-md-4 p-2" style="width: 15em;">
                                <div>
                                    <lable class="badge text-muted">Retirement Date/lable><div class="d-flex"><i
                                                class="fa fa-calendar p-2" style="font-size: 20px;"
                                                aria-hidden="true"></i><input type="date" id="rent_end"
                                                class="form-control" name="date_end" required></div>
                                </div>
                            </div>
                            <!-- <div class="col-md-4 p-2">
                                    <div>
                                        <lable class="badge text-muted">Date End</lable><i class="fa fa-calendar p-2"
                                            style="font-size: 20px;" aria-hidden="true"></i><input type="date"
                                            id="date_end" class="form-control" name="contract_end" required>
                                    </div>
                            </div>-->
                        </div>
                        <div class="col-md-12 d-flex gap-4 row justify-content-center">
                            <!-- <div class="col-md-5 p-2">
                                <div>
                                    <lable class="badge text-muted">Party of Second Part</lable><input type="text"
                                        class="form-control" name="party_of_second_part" required>
                                </div>
                        </div>-->
                            <div class="col-md-5 p-2">
                                <div>
                                    <lable class="badge text-muted">Contract file</lable><input id="contractFileInput"
                                        type="file" class="form-control" name="contract_file" style="width: 16.7em;"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4 p-2">
                                <div>
                                    <?php $userid;
                                    $getUser = (new UserController)->getUserById($userid);

                                    // var_dump($getUser['firstname']);
                                    ?><input type="hidden" id="date_start" class="form-control" name="uploader_id"
                                        value="<?= $userid ?>"><input type="hidden" id="date_start" class="form-control"
                                        name="uploader"
                                        value="<?= $getUser['firstname'] . ' ' . $getUser['middlename'] . ' ' . $getUser['lastname'] ?>"><input
                                        type="hidden" id="date_start" class="form-control" name="uploader_dept"
                                        value="<?= $department ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--><button
                    type="submit" id="submitButton" class="btn btn-primary" disabled onmouseover="pointer">Submit New
                    Contract</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>const fileInput = document.getElementById('contractFileInput');
    const submitButton = document.getElementById('submitButton');

    fileInput.addEventListener('change', function () {

        if (fileInput.files.length > 0) {
            submitButton.disabled = false;
        }

        else {
            submitButton.disabled = true;
        }

    });

</script>
<!-- popup notification ----><svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
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
        <!-- Icon --><svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
            aria-label="<?php echo ($_SESSION['notification']['type'] == 'success') ? 'Success' : ($_SESSION['notification']['type'] == 'warning' ? 'Warning' : 'Error'); ?>:">
            <use
                xlink:href="<?php echo ($_SESSION['notification']['type'] == 'success') ? '#check-circle-fill' : ($_SESSION['notification']['type'] == 'warning' ? '#exclamation-triangle-fill' : '#exclamation-circle-fill'); ?>" />
        </svg>
        <!-- Message -->
        <div>
            <?php echo $_SESSION['notification']['message']; ?>
        </div>
        <!-- Close Button --><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['notification']); // Clear notification after displaying ?>

    <script> // Automatically fade the notification out after 6 seconds

        setTimeout(function () {
            let notification = document.getElementById('notification');

            if (notification) {
                notification.classList.remove('show');
                notification.classList.add('fade');
                notification.style.transition = 'opacity 1s ease';
            }
        }

            , 7000); // 6 seconds
    </script>
<?php endif; ?>
<?php include_once '../../../views/layouts/includes/footer.php'; ?>


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

    #add:hover {
        cursor: pointer;
    }

    #suspend:hover {
        cursor: pointer;
    }
        .dotMenu:hover{
        cursor: pointer;
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
</style>
<?php
date_default_timezone_set('Asia/Manila');
$updatedAt = new DateTime($getContract['updated_at']);
$timestamp = $updatedAt->getTimestamp(); // Unix timestamp
?>



<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
<script>
    // When the page finishes loading, hide the spinner
    // window.onload = function () {
    //     document.getElementById("loadingSpinner").style.display = "none"; // Hide the spinner
    //     document.getElementById("content").style.display = "block"; // Show the page content
    // };


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
        const rentStart = document.getElementById('rent_start');
        const rentEnd = document.getElementById('rent_end');
        const deptSelect = document.getElementById('deptSelect');
        const EndDate = document.getElementById('EmpEndDate');
        const StartDate = document.getElementById('EmpStartDate');
        const empStart = document.getElementById('empConStart');
        const empEnd = document.getElementById('empConEnd');
        const totalCost = document.getElementById('ttc');
        const saccStart = document.getElementById('saccStartDate');
        const saccEnd = document.getElementById('saccEndDate');
        const startGoods = document.getElementById('goodsStart');
        const endGoods = document.getElementById('goodsEnd');
        const infra_start = document.getElementById('infraStart');
        const infra_end = document.getElementById('infraEnd');

        const goodsStart1 = document.getElementById('goodsStart1');
        const goodsStart2 = document.getElementById('goodsStart2');

        const goodsEnd2 = document.getElementById('goodsEnd2');
        const goodsEnd1 = document.getElementById('goodsEnd1');

        const start_rent2 = document.getElementById('startTransRent2');
        const start_rent1 = document.getElementById('startTransRent1');

        const end_rent2 = document.getElementById('endTransRent2');
        const end_rent1 = document.getElementById('endTransRent1');

        const empCon2 =  document.getElementById('empConStart2');
        const empCon1 =  document.getElementById('empConStart1');

        const empEnd2 = document.getElementById('empConEnd2');
        const empEnd1 = document.getElementById('empConEnd1');

        const tempStart =  document.getElementById('tempLightStart');
        const tempEnd = document.getElementById('tempLightEnd');

        const contractAddress = document.getElementById('address');
        const tcNumber = document.getElementById('tc_no');
        const accountNo = document.getElementById('accountNumber');
        const contractInput = document.getElementById('contractTypeInput');
        const contractSelect = document.getElementById('contractTypeSelect');


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
            EndDate?.removeAttribute('readonly');
            StartDate?.removeAttribute('readonly');
            empStart?.removeAttribute('readonly');
            empEnd?.removeAttribute('readonly');
            totalCost?.removeAttribute('disabled');
            saccStart?.removeAttribute('readonly');
            saccEnd?.removeAttribute('readonly');
            startGoods?.removeAttribute('readonly');
            endGoods?.removeAttribute('readonly');
            infra_start?.removeAttribute('readonly');
            infra_end?.removeAttribute('readonly');
            contractAddress?.removeAttribute('readonly');

            start_rent1?.removeAttribute('hidden');
            start_rent2?.setAttribute('hidden','');

            end_rent1?.removeAttribute('hidden');
            end_rent2?.setAttribute('hidden', '');

            empCon2?.removeAttribute('readonly');
            empCon2?.setAttribute('hidden', true);

            empCon1?.removeAttribute('hidden');

            empEnd2?.removeAttribute('readonly');
            empEnd2?.setAttribute('hidden', true);

            empEnd1?.removeAttribute('hidden');

            goodsStart2?.removeAttribute('readonly');
            goodsStart2?.setAttribute('hidden', true);

            goodsStart1?.removeAttribute('hidden', true);

            goodsEnd2?.removeAttribute('readonly');
            goodsEnd2?.setAttribute('hidden', true);
            goodsEnd1?.removeAttribute('hidden');


            tempStart?.removeAttribute('readonly');
            tempEnd?.removeAttribute('readonly');
            tcNumber?.removeAttribute('readonly');
            accountNo?.removeAttribute('readonly');

            contractInput?.removeAttribute('readonly');
            contractInput?.setAttribute('hidden', true);

            contractSelect?.removeAttribute('hidden');
            

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
        const infraStart =  document.getElementById('EmpStartDate');
        const infraEnd =  document.getElementById('EmpEndDate');
        const saveBtn = document.getElementById('save');
        const editBtn = document.getElementById('edit');
        const closeBtn = document.getElementById('close');
        const empStart = document.getElementById('empConStart');
        const empEnd = document.getElementById('empConEnd');
        const totalCost = document.getElementById('ttc');
         const saccStart = document.getElementById('saccStartDate');
        const saccEnd = document.getElementById('saccEndDate');
        const startGoods = document.getElementById('goodsStart');
        const endGoods = document.getElementById('goodsEnd');
        const start_infra = document.getElementById('infraStart');
        const end_infra = document.getElementById('infraEnd');
        const contractAddress =document.getElementById('address');

        const start_rent1 = document.getElementById('startTransRent1');
        const start_rent2 = document.getElementById('startTransRent2');

        const empCon2 =  document.getElementById('empConStart2');
        const empCon1 =  document.getElementById('empConStart1');

        const empEnd2 = document.getElementById('empConEnd2');
        const empEnd1 = document.getElementById('empConEnd1');

        const end_rent1 = document.getElementById('endTransRent1');
        const end_rent2 = document.getElementById('endTransRent2');


        const tempStart =  document.getElementById('tempLightStart');
        const tempEnd = document.getElementById('tempLightEnd');

        const tcNumber = document.getElementById('tc_no');
        const accountNo = document.getElementById('accountNumber');
        const contractInput = document.getElementById('contractTypeInput');
        const contractSelect = document.getElementById('contractTypeSelect');

        const goodsStart1 = document.getElementById('goodsStart1');
        const goodsStart2 = document.getElementById('goodsStart2');

        const goodsEnd2 = document.getElementById('goodsEnd2');
        const goodsEnd1 = document.getElementById('goodsEnd1');

        // Check if fields are currently readonly/disabled
        const isReadOnly = nameInput.hasAttribute('readonly');

        // Set them back to readonly/disabled
        nameInput?.setAttribute('readonly', true);
        startDate?.setAttribute('readonly', true);
        endDate?.setAttribute('readonly', true);
        deptSelect?.setAttribute('disabled', true);
        infraStart?.setAttribute('readonly', true);
        infraEnd?.setAttribute('readonly', true);
        empStart?.setAttribute('readonly', true);
        empEnd?.setAttribute('readonly', true);
        totalCost?.setAttribute('disabled', true);
        saccStart?.setAttribute('readonly',true);
        saccEnd?.setAttribute('readonly', true);
        startGoods?.setAttribute('readonly', true);
        endGoods?.setAttribute('readonly', true);
        start_infra?.setAttribute('readonly', true);
        end_infra?.setAttribute('readonly', true);
        contractAddress?.setAttribute('readonly', true);

        goodsStart2?.removeAttribute('hidden', true);
        goodsStart2?.setAttribute('readonly', true);
        goodsStart1?.setAttribute('hidden', true);

        goodsEnd2?.removeAttribute('hidden');
        goodsEnd2?.setAttribute('readonly', true);
        goodsEnd1?.setAttribute('hidden', true);

        start_rent1?.setAttribute('hidden', true);
        //setting attribute to readonly 
        start_rent2?.setAttribute('readonly','');
        //and removes thre hidden attribute
        start_rent2?.removeAttribute('hidden','');


        end_rent1?.setAttribute('hidden', true);
        end_rent2?.setAttribute('readonly', '');
        end_rent2?.removeAttribute('hidden','');


        tempStart?.setAttribute('readonly', true);
        tempEnd?.setAttribute('readonly', true);
        tcNumber?.setAttribute('readonly',true);
        accountNo?.setAttribute('readonly',true);

        contractSelect?.setAttribute('hidden', true);
        contractInput?.removeAttribute('hidden', true);
        contractInput?.setAttribute('readonly', true);

        empCon2?.removeAttribute('hidden');
        empCon2?.setAttribute('readonly', true);

        empCon1?.setAttribute('hidden', true);

        empEnd2?.removeAttribute('hidden', true);
        empEnd2?.setAttribute('readonly', true);

        empEnd1?.setAttribute('hidden', true);

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
        const contract_type = document.getElementById('contractTypeInput');
        const EmpStart = document.getElementById('empConStart1');
        const EmpEnd = document.getElementById('empConEnd1');
        const totalCost = document.getElementById('ttc');
        const uploader_dept = document.getElementById('uploadingDept');
        const loginUser = document.getElementById('loggedInUser');
        const uploaded_by = document.getElementById('uploadedBy');
        const uploaderId = document.getElementById('uploader_id');
        const deptUploader = document.getElementById('uploader_dept');
        const saccStart =  document.getElementById('saccStartDate');
        const saccEnd = document.getElementById('saccEndDate');
        const startGoods = document.getElementById('goodsStart1');
        const endGoods = document.getElementById('goodsEnd1');
        const infra_start  = document.getElementById('infraStart');
        const infra_end = document.getElementById('infraEnd');
        const contractAddress = document.getElementById('address');
        const tcNumber = document.getElementById('tc_no');
        const accountNo = document.getElementById('accountNumber');

        const empStart = document.getElementById('empConStart2');

        const rent_start =  document.getElementById('startTransRent1');

        const rent_end = document.getElementById('endTransRent1');

        const tempStart =  document.getElementById('tempLightStart');
        const tempEnd = document.getElementById('tempLightEnd');
        const implementing_dept = document.getElementById('impDept');

        const contractTypeSelect = document.getElementById('contractTypeSelect');


        // Get the values for start and end dates, fallback to rent_start and rent_end if necessary
        const startDateValue = startDate?.value || rentStart?.value || '';
        const endDateValue = endDate?.value || rentEnd?.value || '';

        // Get other values
        const contractName = encodeURIComponent(nameInput?.value || '');

        const contractStart = encodeURIComponent(formatDate(startDateValue));
        const contractEnd = encodeURIComponent(formatDate(endDateValue));

        const department = encodeURIComponent(deptSelect?.value || ''); // Safe here
        const contract_id = encodeURIComponent(id?.value || '');
        const typeContract = encodeURIComponent(contract_type?.value || '');
        const EndEmpCon =  encodeURIComponent(EmpEnd?.Value || '');
        const StartEmpCon =  encodeURIComponent(EmpStart?.value || '');
        const EndConEmp = encodeURIComponent(EmpEnd?.value || '');
        const Cost = encodeURIComponent(totalCost?. value || '');
        const deptUpload = encodeURIComponent(uploader_dept?. value || '');
        const updatedby = encodeURIComponent(loginUser?.  value || '');
        const uploadedBy = encodeURIComponent(uploaded_by?. value || '');
        const uploadId = encodeURIComponent(uploaderId?. value || '');
        const dept_uploader = encodeURIComponent(deptUploader?. value || '');
        const saccDate_Start = encodeURIComponent(saccStart?. value || '');
        const saccDate_End = encodeURIComponent(saccEnd?. value || '');
        const goods_start = encodeURIComponent(startGoods?. value || '');
        const goods_end = encodeURIComponent(endGoods?. value || '');
        const infraStart = encodeURIComponent(infra_start?. value || '');
        const infraEnd = encodeURIComponent(infra_end?. value || '');
        const startRent = encodeURIComponent(rent_start?. value || '');
        const endRent =  encodeURIComponent(rent_end?. value || '');
        const startTemplight = encodeURIComponent(tempStart?. value || '');
        const endTemplight = encodeURIComponent(tempEnd?. value || '');
        const impDept = encodeURIComponent(implementing_dept?. value || '');
        const addressContract = encodeURIComponent(contractAddress?. value || '');
        const tcNo = encodeURIComponent(tcNumber?. value || '');
        const account_no = encodeURIComponent(accountNo?. value || '' );   
        const contractSelect = encodeURIComponent(contractTypeSelect?. value || '');  
        // Redirect with query parameters
        window.location.href = `contracts/update.php?id=${contract_id}&name=${contractName}&start=${contractStart}&end=${contractEnd}&type=${typeContract}&EmpStart=${StartEmpCon}&ConEmpEnd=${EndConEmp}&ttc=${Cost}&deptLoader=${deptUpload}&updatedBy=${updatedby}&uploadedBy=${uploadedBy}&uploadId=${uploadId}&uploader_dept=${dept_uploader}&saccDateStart=${saccDate_Start}&saccDateEnd=${saccDate_End}
                                &goodsStart=${goods_start}&goodsEnd=${goods_end}&infraStart=${infraStart}&infraEnd=${infraEnd}&transRentStart=${startRent}&transRentEnd=${endRent}&tempLightStart=${startTemplight}&tempLightEnd=${endTemplight}&implementingDept=${impDept}&address=${addressContract}&tcNumber=${tcNo}&account_no=${account_no}&contractType=${contractSelect}`;
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


    //suspension button
    function myFunction() {
        var x = document.getElementById("actions");
        if (x.style.display === "block") {
            x.style.display = "none";
        } else {
            x.style.display = "block";
        }
    }


    //show and hide div
    function showDiv() {
        var div = document.getElementById("myDiv");
        div.style.display = "block";
    }

    function hideDiv() {
        var div = document.getElementById("myDiv");
        div.style.display = "none";
    }

    const suspensionDays = <?= (int) $num_o_days ?>;
    const suspensionStart = "<?= $formattedStart ?>";

    if (!suspensionStart || suspensionDays === 0) {
        document.getElementById("draggable").innerHTML = `
  CONTRACT is Under Review.
`;

    } else {
        const startDate = new Date(suspensionStart);
        const suspensionEnd = new Date(startDate.getTime() + suspensionDays * 24 * 60 * 60 * 1000);

        const countdown = setInterval(() => {
            const now = new Date().getTime();
            const distance = suspensionEnd - now;

            if (distance <= 0) {
                clearInterval(countdown);
                document.getElementById("draggable").innerHTML = "Suspension has ended!";
            } else {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("draggable").innerHTML = `
                Suspension ends in: ${days}D ${hours}H ${minutes}m ${seconds}s
                <form action="contracts/end_suspension.php" method="post">
                    <input type="hidden" name="account_no" value="<?= $id ?>">
                    <input type="hidden" name="remaining_days" value="${days}">
                    <input type="hidden" name="remaining_hours" value="${hours}">
                    <input type="hidden" name="contract_id" value="<?= $contractId ?>">
                    <input type="hidden" name="contract_type" value="<?= $getContract['contract_type'] ?>">
                    <button type="submit" class="btn btn-sm btn-success fw-bold mt-5" 
                        style="width:10em; font-size:10px; position:absolute; bottom:10px; right:10px;">
                        End Suspension
                    </button>
                </form>
            `;
            }
        }, 1000);
    }

    //for draggable
    $(function () {
        $("#draggable").draggable();
    });





    const updatedAt = <?= (new DateTime($getContract['updated_at']))->getTimestamp() ?> * 1000;

    function getTimeElapsedString() {
        const now = Date.now();
        const diffInSeconds = Math.floor((now - updatedAt) / 1000);

        const days2 = Math.floor(diffInSeconds / (24 * 3600));
        const hours2 = Math.floor((diffInSeconds % (24 * 3600)) / 3600);
        const minutes2 = Math.floor((diffInSeconds % 3600) / 60);
        const seconds2 = diffInSeconds % 60;

        return `${days2} day(s), ${hours2} hour(s), ${minutes2} minute(s), ${seconds2} second(s)`;
    }

    function renderDraggableBox() {
        const timeElapsed = getTimeElapsedString();
        const displayBox = document.querySelector(".display");

        if (displayBox) {
            displayBox.innerHTML = `
            <div class="text-center fw-bold text-danger" style="font-size:1.2em;">
               Contract is Under Review.
            </div>
            <div class="text-center fw-bold text-danger fs-5 mb-3">
                ${timeElapsed}
            </div>

            <form action="contracts/end.php" method="post">
                <input type="hidden" name="account_no" value="<?= $id ?>">
                <input type="hidden" name="contract_id" value="<?= $contractId ?>">
                <input type="hidden" name="contract_type" value="<?= $getContract['contract_type'] ?>">
                <input type="hidden" name="contract_end" value="<?= $contractEnding ?>">
                <input type="hidden" name="rent_end" value="<?= $rentEnding ?>">
                <input type="hidden" name="updated_at" value="<?= $getContract['updated_at'] ?>">
                <div class="d-flex gap-2 mt-5 justify-content-end">
                    <button type="submit" name="terminate" class="btn btn-sm btn-danger fw-bold" style="width: 11em; font-size: 10px;">
                        Terminate Contract
                    </button>
                    <button type="submit" name="end_suspension" class="btn btn-sm btn-success fw-bold" style="width: 10em; font-size: 10px;">
                        End Suspension
                    </button>
                </div>
            </form>
        `;
        }
    }

    renderDraggableBox();
    setInterval(renderDraggableBox, 1000);

    function toggleView(){
        var div = document.getElementById("dropMenu");
        if(div.style.display === "block"){
            div.style.display = "none";
        }else{
            div.style.display = "block"  
            }
        }



</script>