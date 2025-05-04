<?php

use App\Controllers\ContractHistoryController;

session_start();

require_once __DIR__ . '../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../vendor/autoload.php'; // corrected path

use App\Controllers\ContractController;
use App\Controllers\ContractTypeController;
use App\Controllers\EmploymentContractController;

$page_title = 'Manage Contracts';

$department = $_SESSION['department'] ?? null;


$userid = $_SESSION['id'];


//------------------------------------- for pagination ------------------------------------------------//
// Get GET parameters
$searchQuery = $_GET['search_query'] ?? '';
$contractTypeFilter = $_GET['contract_type_filter'] ?? '';
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$perPage = 10; // Number of items per page

// Parameters for pagination
$department = 'ISD-MSD'; // Example department

// Get the contracts for the current page
$contracts = (new ContractController)->getContractsByCITETDepartment($department, $searchQuery, $perPage, $currentPage, $contractTypeFilter);
$totalContracts = (new ContractController)->getTotalContractsCountCITETD($department, $searchQuery, $contractTypeFilter);


//------------------------------------- for pagination ------------------------------------------------//


//------------------------Filtering--------------------------------------------//
if (!empty($contractTypeFilter)) {
    $contracts = array_filter($contracts, function ($contract) use ($contractTypeFilter) {
        return $contract['contract_type'] === $contractTypeFilter;
    });
}
//------------------------Filtering--------------------------------------------//
$getDept_assigned = (new ContractController)->getDepartmentAssigned($department);

$uploader = $getDept_assigned['uploader_department'] ?? '';

$assigned_department = $getDept_assigned['department_assigned'] ?? '';
// Correct the line below, remove the extra '$$' before $contracts
$uploader_department = $getDept_assigned['uploader_department'] ?? '';

// echo 'uploader dept is ' . $uploader_dept. '</br>';;

define('CONTRACTS_PER_PAGE', 10);

$contractController = new ContractController();

$page = isset($_GET['page']) ? max((int) $_GET['page'], 1) : 1;

$start = ($page - 1) * CONTRACTS_PER_PAGE;

$contract_filter = $_GET['contract_type_filter'] ?? null;
$search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : null;

$contractTypes = (new ContractTypeController)->getContractTypes();
// $contracts = (new ContractController)->getAllContractsByDept($department);

// var_dump($contracts);

$totalContracts = $contractController->getTotalContracts($contract_filter, $search_query);
$totalPages = ceil($totalContracts / CONTRACTS_PER_PAGE);

$getOneLatest = (new ContractHistoryController)->insertLatestData();

if ($getOneLatest) {
    // echo "Contract data inserted successfully.";
} else {
    // Optional: echo nothing or a silent message
    // echo "No contract data available to insert.";
}

include_once '../../../views/layouts/includes/header.php';
?>

<!-- Loading Spinner - Initially visible -->
<div id="loadingSpinner" class="text-center"
    style="z-index:9999999;padding:100px;height:100%;width:100%;background-color: rgb(203 199 199 / 82%);position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
    <div class="spinner-border" style="width: 3rem; height: 3rem;margin-top:15em;" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>

<div class="pageContent">
    <div class="sideBar bg-dark">
        <?php include_once '../menu/sidebar.php'; ?>
    </div>

    <div class="mainContent" style="margin:auto;margin-top:0;">
        <!-- Content that will be shown after loading -->
        <div class="mt-2" id="content">
            <h2>Active Contracts</h2>
            <span class="p-1 d-flex float-end" style="margin-top: -2.5em;">
                <!-- <?= $department = $_SESSION['department'] ?? null; ?> Account -->

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

            <div class="d-flex align-items-center gap-3 flex-wrap mb-1" style="margin-left: 1%;">

                <a class="btn text-white btn-success p-2 btn btn-outline-success" data-mdb-ripple-init
                    style="width:15%;padding-right:13px;font-size:15px;background-color:#007F73;" href="#!"
                    role="button" data-bs-toggle="modal" data-bs-target="#<?= $department ?>Modal">
                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                    Power Suppliers Contract<br>(Long Term)
                </a>


                <a class="btn text-white btn-success p-2" data-mdb-ripple-init
                    style="width:15%;padding-right:10px;font-size:15px;background-color:#A0153E;" href="#!"
                    role="button" data-bs-toggle="modal" data-bs-target="#shortTermModal">
                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                    Power Suppliers Contract<br>(Short Term)
                </a>

                <form method="GET" action="list.php">
                    <select class="form-select w-auto" name="contract_type_filter" onchange="this.form.submit()">
                        <option value="" <?= isset($_GET['contract_type_filter']) && $_GET['contract_type_filter'] == "" ? "selected" : "" ?>>All Contracts</option>
                        <?php
                        $selectedType = $_GET['contract_type_filter'] ?? '';
                        foreach ($contractTypes as $type) {
                            $typeName = htmlspecialchars($type['contract_type']);
                            $selected = ($selectedType === $typeName) ? 'selected' : '';
                            echo "<option value=\"$typeName\" $selected>$typeName</option>";
                        }
                        ?>
                    </select>
                </form>


                <form method="GET" action="list.php" class="input-group" style="width: 250px;">
                    <input type="text" class="form-control" name="search_query"
                        value="<?= isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : '' ?>"
                        placeholder="Search Contract">
                    <button class="btn bg-dark text-white" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </form>

            </div>


            <span class="text-sm badge " style="color:#AAB99A;margin-left:.5%">NOTE: Search by Contract type and
                Contract Name.</span>
        </div>

        <div class="" style="width: 100%;padding:12px;">

            <table class="table table-striped table-hover border p-3">
                <thead>
                    <tr>
                        <th style="text-align: center;">Contract Name</th>
                        <th style="text-align: center;">Type</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Start</th>
                        <th></th>
                        <th style="text-align: center;">End</th>
                        <!-- <th style="text-align: center;">File</th> -->
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($contracts)): ?>
                        <?php foreach ($contracts as $contract): ?>
                            <tr>
                                <td style="text-align: center;"><?= htmlspecialchars($contract['contract_name']) ?></td>
                                <td style="text-align: center;">
                                    <?php
                                    $badgeColor = "#FAB12F";
                                    if ($contract['contract_type'] == TRANS_RENT || $contract['contract_type'] == TEMP_LIGHTING) {
                                        $badgeColor = ($contract['contract_type'] == TEMP_LIGHTING) ? "#03A791" : "#003092";
                                    }
                                    ?>
                                    <span class="p-2 text-white badge"
                                        style="background-color: <?= $badgeColor ?>; border-radius:5px;">
                                        <?= htmlspecialchars($contract['contract_type']) ?>
                                    </span>
                                </td>
                                <td style="text-align: center;">

                                    <?php if ($contract['contract_status'] == 'Active'): ?>
                                        <span class="badge text-white bg-success">
                                            <?= htmlspecialchars($contract['contract_status']) ?></span>
                                    <?php else: ?>
                                        <span class="badge text-white  bg-danger">
                                            <?= htmlspecialchars($contract['contract_status']) ?></span>
                                    <?php endif; ?>

                                </td>
                                <td style="text-align: center;">
                                    <?php if ($contract['contract_type'] === TEMP_LIGHTING): ?>
                                        <span class="badge text-muted">
                                            <?= !empty($contract['contract_start']) ? date('M-d-Y', strtotime($contract['contract_start'])) : '' ?>
                                        </span>


                                    <?php elseif ($contract['contract_type'] === TRANS_RENT): ?>
                                        <span class="badge text-muted">
                                            <?= !empty($contract['rent_start']) ? date('M-d-Y', strtotime($contract['rent_start'])) : '' ?>
                                        </span>


                                    <?php endif; ?>

                                    <?php if ($contract['contract_type'] === EMP_CON): ?>
                                        <span class="badge text-muted">
                                            <?= !empty($contract['contract_start']) ? date('M-d-Y', strtotime($contract['contract_start'])) : '' ?>
                                        </span>
                                    <?php endif; ?>

                                </td>

                                <td style="text-align: center;">
                                <td style="text-align: center;">
                                    <?php if ($contract['contract_type'] === TEMP_LIGHTING): ?>
                                        <span class="badge text-muted">
                                            <?= !empty($contract['contract_end']) ? date('M-d-Y', strtotime($contract['contract_end'])) : '' ?>
                                        </span>


                                    <?php elseif ($contract['contract_type'] === TRANS_RENT): ?>
                                        <span class="badge text-muted">
                                            <?= !empty($contract['rent_end']) ? date('M-d-Y', strtotime($contract['rent_end'])) : '' ?>
                                        </span>


                                    <?php endif; ?>

                                    <?php if ($contract['contract_type'] === EMP_CON): ?>
                                        <span class="badge text-muted">
                                            <?= !empty($contract['contract_end']) ? date('M-d-Y', strtotime($contract['contract_end'])) : '' ?>
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td style="text-align: center;">
                                    <?php if ($contract['contract_type'] == EMP_CON): ?>
                                        <a href="view.php?contract_id=<?= $contract['id'] ?>" class="btn btn-success badge p-2"><i
                                                class="fa fa-eye" aria-hidden="true"></i> View</a>
                                    <?php else: ?>
                                        <a href="check.php?contract_id=<?= $contract['id'] ?>&type=<?= $contract['contract_type'] ?>"
                                            class="btn btn-success badge p-2"><i class="fa fa-eye" aria-hidden="true"></i> View</a>

                                        <a href="" id="delete" data-id="<?= $contract['id'] ?>" class="btn btn-danger badge p-2"><i
                                                class="fa fa-trash" aria-hidden="true"></i>
                                            Delete</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No contracts found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php
            $totalPages = ceil($totalContracts / $perPage);
            $baseUrl = strtok($_SERVER["REQUEST_URI"], '?'); // Get current page without query
            $queryParams = $_GET;
            ?>

            <?php if ($totalPages > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mt-3">
                        <!-- Previous button -->
                        <?php if ($currentPage > 1): ?>
                            <?php $queryParams['page'] = $currentPage - 1; ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= $baseUrl . '?' . http_build_query($queryParams) ?>">Previous</a>
                            </li>
                        <?php endif; ?>

                        <!-- Page numbers -->
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <?php $queryParams['page'] = $i; ?>
                            <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="<?= $baseUrl . '?' . http_build_query($queryParams) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next button -->
                        <?php if ($currentPage < $totalPages): ?>
                            <?php $queryParams['page'] = $currentPage + 1; ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= $baseUrl . '?' . http_build_query($queryParams) ?>">Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>

        </div>
    </div>
</div>



<!-- modals for every user ---->

<?php switch ($department) {

    case "ISD-HRAD":
        include_once '../modals/hrad_modal.php';
        break;
    case "CITETD":
        include_once '../modals/long_term.php';
        include_once '../modals/short_term.php';
        break;
    case "ISD-MSD":
        include_once '../modals/isdmsd_modal.php';
        include_once '../modals/transformer_rental.php';
        break;
}
?>

<!-- delete confirmation ---->
<!-- <div id="popup" class="alert alert-danger border-danger">
    <br>
    <span class="text-dark fw-bold p-4">Are you sure you want to Delete this record?</span>
    <div class="d-flex p-3 gap-2 justify-content-center">
        <button class="btn btn-danger btn-block w-25">Yes</button>
        <button class="btn btn-light btn-block w-25">No</button>
    </div>
</div>
-->


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
                <!-- Cancel button -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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


<?php include_once '../../../views/layouts/includes/footer.php'; ?>

<style>
    /* Flex container for the layout */
    .pageContent {
        display: flex;
        min-height: 100vh;
        /* Ensure it takes full viewport height */
    }

    /* Main content styles */
    .mainContent {
        background-color: #FFF;
        width: 100%;
        /* Main content takes up remaining space */
        padding: 20px;
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

    #popup {
        padding: 10px;
        position: absolute;
        top: 30%;
        left: 40%;
        height: 15%;
        font-size: 17px;
        border-radius: 5px;
        box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px, rgba(0, 0, 0, 0.23) 0px 6px 6px;
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

    // Function to display the confirmation modal
    function showConfirmationModal(contractId) {
        // Get the modal element and the confirm delete button
        var modal = new bootstrap.Modal(document.getElementById('confirmModal'));
        var confirmBtn = document.getElementById("confirmDelete");

        // Set the href for the confirmDelete link with the contractId
        confirmBtn.href = "contracts/delete.php?id=" + contractId;

        // Show the modal
        modal.show();
    }



</script>