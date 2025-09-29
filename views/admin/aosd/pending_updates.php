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
                                <?php $dateStart = date('M-d-y', strtotime($contract['contract_start'] ?? $contract['rent_start'])) ?>
                                <span class="badge text-muted">
                                    <?= $dateStart ?>
                                </span>
                                
                            </td>
                            <td class="text-center">
                                <?php $dateEnd = date('M-d-y', strtotime($contract['contract_end'] ?? $contract['rent_end'])) ?>
                                <span class="badge text-muted">
                                    <?= $dateEnd ?>
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
                                    <button type="button" class="btn btn-success btn-sm view-details"
                                        data-id="<?= $contract['id'] ?>"
                                        data-toggle="modal"
                                        data-target="#myModal"
                                        data-contract-id = "<?= $contract['contract_id'] ?>"
                                        >
                                        <i class="fa fa-eye"></i> View
                                    </button>

                            <!----update modal ------->

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
                                                    <input type="hidden" name="id" id="modal-contract-id" >
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


<!-- The Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document"><!-- make it wider -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Compare Contract Details</h5>
        <button type="button" class="btn close float-end" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row" id="modal-body-content">
          <!-- AJAX will insert two columns here -->
          <div class="col-md-6 border-end" id="current-contract">
            <h3 class="text-start fw-bold">Current</h3>
            <div class="p-2">
              LOADING...
            </div>
          </div>
          <div class="col-md-6" id="pending-contract">
            <h3 class="text-start fw-bold">Pending Update</h3>
            <div class="p-2">
              LOADING...
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
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

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>

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


    // retrieve data using ajax 
    $(document).ready(function(){
        $('.view-details').on('click', function() {

            //variable from button data-id
            var itemId = $(this).data('id');
            var contractId = $(this).data('contractId');

            $.ajax({
                url: 'get_data.php',
                type: 'POST',
                data: { id:itemId,contract_id:contractId },
                success: function(response){
                    response = JSON.parse(response); // make sure JSON is parsed

                    if(response.success){
                        let data = response.data;

                        let rawDateStart = data.contract_start ?? rent_start;
                        let isoDateStart =  rawDateStart.replace(" ", "T");
                        let newDateStart =  new Date(isoDateStart);
                        let formattedDateStart = newDateStart.toLocaleDateString("en-US");

                        let rawDateEnd = data.contract_end ?? rent_end;
                        let isoDateEnd =  rawDateEnd.replace(" ", "T");
                        let newDateEnd =  new Date(isoDateEnd);
                        let formattedDateEnd = newDateEnd.toLocaleDateString("en-US");

                        let pendingHtml = "";

                    const fields = {
                        "Contract Name": data.contract_name,
                        "Contract Type": data.contract_type,
                        "Start Date": data.contract_start,
                        "End Date": data.contract_end,
                        "Rent Start": data.rent_start,
                        "Rent End": data.rent_end,
                        "Total Cost": data.total_cost,
                        "Status": data.contract_status,
                        "Approval Status": data.approval_status,
                        "Account No": data.account_no,
                        "TC No": data.tc_no,
                        "Address": data.address,
                        "Assigned Department": data.assigned_dept,
                        "Second Party": data.second_party,
                        "Supplier": data.supplier,
                        "Procurement Mode": data.proc_mode
                    };

                    for (let label in fields) {
                        let value = fields[label];
                        if (value && value.trim() !== "") {
                            pendingHtml += `
                                <div class="form-group mb-2">
                                    <label><strong>${label}:</strong></label>
                                    <input type="text" class="form-control" value="${value}" disabled>
                                </div>
                            `;
                        }
                    }




                        $('#pending-contract .p-2').html(pendingHtml);
                    }else {
                        $('#modal-body-content').html('<p>Error: ' + response.message + '</p>');
                    }
                },
                error: function(xhr, status, error ){
                    $('#modal-body-content').html('<p>An error occurred: ' + error + '</p>');
                }
            });

        });
    });



    //getCurrentData

    // retrieve data using ajax 
    $(document).ready(function(){
        $('.view-details').on('click', function() {

            //variable from button data-id
            var itemId = $(this).data('id');
            var contractId = $(this).data('contractId');

            $.ajax({
                url: 'get_currentData.php',
                type: 'POST',
                data: { id:itemId,contract_id:contractId },
                success: function(response){
                    response = JSON.parse(response); // make sure JSON is parsed

                    if(response.success){
                        let data = response.data;

                        let rawDateStart = data.contract_start ?? rent_start;
                        let isoDateStart =  rawDateStart.replace(" ", "T");
                        let newDateStart =  new Date(isoDateStart);
                        let formattedDateStart = newDateStart.toLocaleDateString("en-US");

                        let rawDateEnd = data.contract_end ?? rent_end;
                        let isoDateEnd =  rawDateEnd.replace(" ", "T");
                        let newDateEnd =  new Date(isoDateEnd);
                        let formattedDateEnd = newDateEnd.toLocaleDateString("en-US");


                        let currentHtml = "";

                        function addField(label, value) {
                            if (value && value.trim() !== "") {
                                currentHtml += `
                                    <div class="form-group mb-2">
                                        <label><strong>${label}:</strong></label>
                                        <input type="text" class="form-control" value="${value}" disabled>
                                    </div>
                                `;
                            }
                        }

                        // Add fields only if they have data
                        addField("Contract Name", data.contract_name);
                        addField("Contract Type", data.contract_type);
                        addField("Start Date", data.contract_start);
                        addField("End Date", data.contract_end);
                        addField("Rent Start", data.rent_start);
                        addField("Rent End", data.rent_end);
                        addField("Total Cost", data.total_cost);
                        addField("Status", data.contract_status);
                        addField("Approval Status", data.approval_status);
                        addField("Account No", data.account_no);
                        addField("TC No", data.tc_no);
                        addField("Address", data.address);
                        addField("Assigned Department", data.assigned_dept);
                        addField("Second Party", data.second_party);
                        addField("Supplier", data.supplier);
                        addField("Procurement Mode", data.proc_mode);


                        $('#current-contract .p-2').html(currentHtml);
                    }else {
                        $('#modal-body-content').html('<p>Error: ' + response.message + '</p>');
                    }
                },
                error: function(xhr, status, error ){
                    $('#modal-body-content').html('<p>An error occurred: ' + error + '</p>');
                }
            });

        });
    });


</script>