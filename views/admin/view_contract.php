<?php

use App\Controllers\EmploymentContractController;
use App\Controllers\UserController;
session_start();

use App\Controllers\ContractController;

require_once __DIR__ . '../../../vendor/autoload.php';

//------------------------- GET CONTRACT NAME ---------------------------//

$contract_id = $_GET['contract_id'];

$getContract = (new ContractController)->getContractbyId($contract_id);

$contract_data = $getContract['contract_name'];

$page_title = 'View Contract | '. $getContract['contract_name'];

//-----------------------------------------------------------------------//






include_once '../../views/layouts/includes/header.php'; 

?>

<!-- Loading Spinner - Initially visible -->
<!-- <div id="loadingSpinner" class="text-center" style="z-index:9999999;padding:100px;height:100%;width:100%;background-color: rgb(203 199 199 / 82%);position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <div class="spinner-border" style="width: 3rem; height: 3rem;margin-top:15em;" role="status">
    <span class="sr-only">Loading...</span>
    </div>
</div> -->

<div class="pageContent">
    <div class="sideBar bg-dark">
        <?php include_once 'sidebar.php'; ?>
    </div>

    <div class="mainContent">
        <h2 class="mt-2"><a href="" onclick="history.back(); return false;" class="text-dark pt-2"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
        <?= $contract_data ?></h2>
        <hr>

        <div class="mt-3 col-md-12 d-flex gap-5">
            
            <div class="row col-md-3" >
                <div class="mt-3">
                    <label class="badge text-muted" style="font-size: 15px;">Contract Name:</label>
                    <input type="text" style="margin-left:9px;" class="form-control pl-5" value="<?= $getContract['contract_name'];  ?>"  name="contract_name" readonly>
                </div>
            </div>
            <div class="row col-md-2" >
                <div class="mt-3">
                    <label class="badge text-muted" style="font-size: 15px;">Start date:</label>
                        <div class="d-flex">
                            <i class="fa fa-calendar p-2" style="font-size: 20px;" aria-hidden="true"></i>
                            <input type="date" style="margin-left:px;" class="form-control pl-5" value="<?= $getContract['contract_start'];  ?>"  name="contract_start" readonly>
                        </div>
                </div>
            </div>
            <div class="row col-md-2" >
                <div class="mt-3">
                        <label class="badge text-muted" style="font-size: 15px;">End date:</label>
                            <div class="d-flex">
                                <i class="fa fa-calendar p-2" style="font-size: 20px;" aria-hidden="true"></i>
                                <input type="date" style="margin-left:px;" class="form-control pl-5" value="<?= $getContract['contract_end'];  ?>"  name="contract_end" readonly>
                        </div>
                </div>
            </div>
            <div class="row col-md-2" >
                
                    <div class="mt-3">
                        <label class="badge text-muted" <?php 

                    $start = new DateTime($getContract['contract_start']);
                    $end = new DateTime($getContract['contract_end']);
                    $today = new DateTime();

                    $remainingDays = $today <= $end ? $today->diff($end)->days : 0;


                ?> style="font-size: 15px;">Days Remaining:</label>
                            <div class="d-flex">
                                <input type="text" style="margin-left:7px;" class="form-control" value=" <?= $remainingDays ?> day<?= $remainingDays != 1 ? 's' : '' ?>" readonly>
                            </div>
                    </div>
            </div>    
        </div>
       
        <!---- 2nd row ------>
        <div class="mt-3 col-md-12 d-flex gap-5">
            
            <div class="row col-md-3" >
                <div class="mt-3">
                    <label class="badge text-muted" style="font-size: 15px;">Contract type:</label>
                    <input type="text" style="margin-left:9px;" class="form-control pl-5" value="<?= $getContract['contract_type'];  ?>"  name="contract_type" readonly>
                </div>
            </div>

            <div class="row col-md-3" >
                <div class="mt-3">
                    <label class="badge text-muted" style="font-size: 15px;">Department Assigned:</label>
                    <input type="text" style="margin-left:9px;" class="form-control pl-5" value="<?= $getContract['department_assigned'];  ?>"  name="department_assigned" readonly>
                </div>
            </div>
            <!-- <div class="row col-md-2" >
                <div class="mt-3">
                    <label class="badge text-muted" style="font-size: 15px;">Start date:</label>
                        <div class="d-flex">
                            <i class="fa fa-calendar p-2" style="font-size: 20px;" aria-hidden="true"></i>
                            <input type="date" style="margin-left:px;" class="form-control pl-5" value="<?= $getContract['contract_start'];  ?>"  name="contract_start" readonly>
                        </div>
                </div>
            </div>
            <div class="row col-md-2" >
                <div class="mt-3">
                        <label class="badge text-muted" style="font-size: 15px;">End date:</label>
                            <div class="d-flex">
                                <i class="fa fa-calendar p-2" style="font-size: 20px;" aria-hidden="true"></i>
                                <input type="date" style="margin-left:px;" class="form-control pl-5" value="<?= $getContract['contract_end'];  ?>"  name="contract_end" readonly>
                        </div>
                </div>
            </div> -->
            <div class="row col-md-2" >
                <div class="mt-3">
                        <label class="badge text-muted" style="font-size: 15px;">Status</label>
                            <div class="d-flex">
                                <?php if(!$getContract['contract_status'] === 'Active' | $getContract['contract_status'] === 'Expired'): ?>
                                    <i class="fa fa-ban p-2" style="color:#BF3131;font-size: 20px;" aria-hidden="true"></i>
                                    <span class="alert p-1 alert-warning border-danger text-danger text-center"  style="width: 7em;"><?= $getContract['contract_status'];  ?></span>
                                <?php else: ?>
                                    <i class="fa fa-check p-2" style="color:green;font-size: 20px;" aria-hidden="true"></i>
                                    <span class="alert p-1 alert-success border-success text-success text-center"  style="width: 7em;"><?= $getContract['contract_status'];  ?></span>
                                <?php endif; ?>
                        </div>
                </div>
            </div>
            
        </div>

        <div class="mt-3 col-md-12 d-flex gap-5">
            
            <div class="row col-md-3" >
                <div class="mt-3">
                    <label class="badge text-muted" style="font-size: 15px;">Implementing Dept:</label>
                    <input type="text" style="margin-left:9px;" class="form-control pl-5" value="<?= $getContract['uploader_department'];  ?>"  name="contract_type" readonly>
                </div>
            </div>

            <?php 

                $getUser = (new UserController)->getUserById( $getContract['uploader_id']);

            ?>

            <div class="row col-md-3" >
                <div class="mt-3">
                    <label class="badge text-muted" style="font-size: 15px;">Uploaded by:</label>
                    <input type="text" style="margin-left:9px;" class="form-control pl-5" value="<?= $getUser['firstname'] . ' ' . $getUser['middlename']. ' ' . $getUser['lastname'];  ?>"  name="contract_type" readonly>   
                </div>
            </div>

            <div class="row col-md-3 mt-4 float-end">
                <div class="mt-3 float-end" style="margin-left: 90%;">
                    <?php 
                    $start = new DateTime($getContract['contract_start']);
                    $end = new DateTime($getContract['contract_end']);
                    $today = new DateTime();

                    // Calculate remaining days
                    $remainingDays = $today <= $end ? $today->diff($end)->days : 0;
                    ?>

                    <?php if ($remainingDays <= 15) {  ?>
                        <button class="btn btn-primary">Extend</button>
                        <button class="btn btn-warning">End Contract</button>
                    <?php } else { ?>
                        <!-- Optionally, you can show something else if the remaining days are more than 15 -->
                    <?php } ?>
                </div>
            </div>
        </div>

        <div>
            <div class="mt-5">
                <h4>Employment History</h4>
            </div>
            <hr class="">
            <div class="">
                <table class="table table-stripped table-hover">
                    <thead>
                        <tr>
                            <th style="text-align: center !important;">Status</th>
                            <th style="text-align: center !important;">Contract File</th>
                            <th style="text-align: center !important;">Date Start</th>
                            <th style="text-align: center !important;">Date End</th>
                        </tr>
                    </thead>
                    <?php 
                        $employement_datas = (new EmploymentContractController)->getByContractName($getContract['contract_name']);
                    ?>
                    <tbody class="">
                        <?php if (!empty($employement_datas)): ?>
                            <?php foreach ($employement_datas as $employement_data): ?>
                                <tr>
                                    <td style="text-align: center !important;">
                                        <!-- <?= $employement_data['status']; ?> -->
                                        <?php if($employement_data['status'] === 'Active' | $employement_data['status']=== 'Expired'): ?>
                                                <span class="badge bg-success p-2"><?= $employement_data['status']; ?></span>
                                        <?php else: ?>
                                            <span class="badge text-muted p-2">no status</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: center !important;">
                                        

                                <?php if (!empty($employement_data['contract_file'])): ?>
                                    <!-- Trigger the modal with this button -->
                                    <button class="btn btn-primary badge p-2" data-bs-toggle="modal" data-bs-target="#fileModal<?= $employement_data['id'] ?>" style="text-align: center !important;">
                                        View file
                                    </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="fileModal<?= $employement_data['id'] ?>" tabindex="-1" aria-labelledby="fileModalLabel<?= $employement_data['id'] ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-xl" style="min-height: 100vh; max-height: 300vh;">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="fileModalLabel<?= $employement_data['id'] ?>"><?= $employement_data['contract_name'] ?> - <?= $employement_data['contract_type'] ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body" style="padding: 0; overflow-y: auto;">
                                                            <!-- Display the contract file inside the modal -->
                                                            <iframe src="<?= htmlspecialchars("../../" . $employement_data['contract_file']) ?>" width="100%" style="height: 80vh;" frameborder="0"></iframe>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>  
                                            <?php else: ?>
                                                No file
                                            <?php endif; ?>
                                    </td>
                                    <td style="text-align: center !important;">
                                        <?php
                                            $date = new DateTime($employement_data['date_start']);
                                        ?>
                                      
                                        <span class="badge text-dark">  <?= $date->format('M-m-Y') ?></span>
                                    </td>
                                    <td style="text-align: center !important;">
                                        <?php
                                            $date = new DateTime($employement_data['date_end']);
                                        ?>
                                          <span class="badge text-dark">  <?= $date->format('M-m-Y') ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4">No contract data found.</td></tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
      

    </div>
</div>






<?php 
    include_once '../../views/layouts/includes/footer.php';
?>

<style>
.pageContent {
    display: flex;
    min-height: 100vh;
}

.sideBar {
    width: 260px; /* or whatever fixed width you want */
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

</style>

<script>
        // When the page finishes loading, hide the spinner
        window.onload = function() {
            document.getElementById("loadingSpinner").style.display = "none"; // Hide the spinner
            document.getElementById("content").style.display = "block"; // Show the page content
        };


            document.addEventListener("click", function(e) {
        // Check if the clicked element is a delete button
        if (e.target && e.target.id === "delete") {
            e.preventDefault(); // Prevent default action (if any)

            // Get the data-id of the clicked button
            var contractId = e.target.getAttribute('data-id');

            // Show the confirmation modal and pass the contractId
            showConfirmationModal(contractId);
        }
    });
</script>