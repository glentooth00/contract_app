<?php

session_start();

require_once __DIR__ . '/../../vendor/autoload.php'; // corrected path

use App\Controllers\ContractController;


$page_title = 'Manage Contracts';

$department =  $_SESSION['department'] ?? null;

$getUploader_department = (new ContractController)->getWhereDepartment($department);
$getDept_assigned = (new ContractController)->getDepartmentAssigned($department);
    
$assigned_dept = $getDept_assigned['department_assigned'] ?? '';

$uploader_dept = $getUploader_department['uploader_department'] ?? '';

define('CONTRACTS_PER_PAGE', 10);

$contractController = new ContractController();

$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;

$start = ($page - 1) * CONTRACTS_PER_PAGE;

$contract_filter = $_GET['contract_type_filter'] ?? null;
$search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : null;


$contracts = $contractController->getOldContractsWithPagination(
    $start, 
    CONTRACTS_PER_PAGE, // Use the defined constant for limit
    $assigned_dept, 
    $uploader_dept, 
    $contract_filter, // Correct filter variable
    $search_query     // Correct search variable
);

$totalContracts = $contractController->getTotalContracts($contract_filter, $search_query);
$totalPages = ceil($totalContracts / CONTRACTS_PER_PAGE);



include_once '../../views/layouts/includes/header.php';
?>

<!-- Loading Spinner - Initially visible -->
    <div id="loadingSpinner" class="text-center" style="z-index:9999999;padding:100px;height:100%;width:100%;background-color: rgb(203 199 199 / 82%);position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <div class="spinner-border" style="width: 3rem; height: 3rem;margin-top:15em;" role="status">
    <span class="sr-only">Loading...</span>
    </div>
</div>

<div class="pageContent">
    <div class="sideBar bg-dark">
       <?php include_once 'sidebar.php'; ?>
    </div>

    <div class="mainContent" style="margin:auto;margin-top:0;">
        <!-- Content that will be shown after loading -->
        <div class="mt-2" id="content">
            <h2>Active Contracts</h2>
            <span class="mt-2 p-1 d-flex">
                    <!-- <?= $department =  $_SESSION['department'] ?? null; ?> Account -->

                    <?php if(isset($user_department)){ ?>

                        <?php switch( $user_department ) { case 'IT': ?>

                                <span class="badge p-2" style="background-color: #0d6efd;"><?= $user_department; ?> user</span>
                            
                            <?php break; case 'ISD-HRAD': ?>

                                <span class="badge p-2" style="background-color: #3F7D58;"><?= $user_department; ?> user</span>
                            
                            <?php break; case 'CITETD': ?>

                                <span class="badge p-2" style="background-color: #FFB433;"><?= $user_department; ?> user</span>
                            
                            <?php break; case 'IASD': ?>
                                
                                <span class="badge p-2" style="background-color: #EB5B00;"><?= $user_department; ?> user</span>
                            
                            <?php break;case 'ISD-MSD': ?>

                                <span class="badge p-2" style="background-color: #6A9C89;"><?= $user_department; ?> user</span>
                            
                            <?php break; case 'BAC':  ?>

                                <span class="badge p-2" style="background-color: #3B6790;"><?= $user_department; ?> user</span>
                            
                            <?php break;  case '': ?>
                            
                            <?php default: ?>
                                <!-- <span class="badge text-muted">no department assigned</span> -->
                        <?php } ?>

                        <?php }else { ?>

                        <!-- <span class="badge text-muted">no department assigned</span> -->

                        <?php } ?>
                </span>
            <hr>

    <div class="d-flex align-items-center gap-3 flex-wrap mb-1" style="margin-left: 1%;">
        <a class="btn text-white btn-success p-2" data-mdb-ripple-init style="width:15%;padding-right:10px;" href="#!" role="button" data-bs-toggle="modal" data-bs-target="#<?= $department ?>Modal">
            <i class="fa fa-file-text-o" aria-hidden="true"></i>
            Add Contract
        </a>

        
        <form method="GET" action="contracts.php">
            <select class="form-select w-auto" name="contract_type_filter" onchange="this.form.submit()">
                <option value="" <?= isset($_GET['contract_type_filter']) && $_GET['contract_type_filter'] == "" ? "selected" : "" ?>>All Contracts</option>
                <option value="Employment Contract" <?= isset($_GET['contract_type_filter']) && $_GET['contract_type_filter'] == "Employment Contract" ? "selected" : "" ?>>Employment Contract</option>
                <option value="Construction Contract" <?= isset($_GET['contract_type_filter']) && $_GET['contract_type_filter'] == "Construction Contract" ? "selected" : "" ?>>Construction Contract</option>
                <option value="Licensing Agreement" <?= isset($_GET['contract_type_filter']) && $_GET['contract_type_filter'] == "Licensing Agreement" ? "selected" : "" ?>>Licensing Agreement</option>
                <option value="Purchase Agreement" <?= isset($_GET['contract_type_filter']) && $_GET['contract_type_filter'] == "Purchase Agreement" ? "selected" : "" ?>>Purchase Agreement</option>
                <option value="Service Agreement" <?= isset($_GET['contract_type_filter']) && $_GET['contract_type_filter'] == "Service Agreement" ? "selected" : "" ?>>Service Agreement</option>
            </select>
        </form>

        <form method="GET" action="contracts.php" class="input-group" style="width: 250px;">
            <input type="text" class="form-control" name="search_query" value="<?= isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : '' ?>" placeholder="Search Contract">
            <button class="btn bg-dark text-white" type="submit">
                <i class="fa fa-search"></i>
            </button>
        </form>
    </div>


<span class="text-sm badge "style="color:#AAB99A;margin-left:.5%">NOTE: Search by Contract type and Contract Name.</span>
</div>

        <div class="container mt-1">
        <table class="table table-striped table-hover border p-3">
            <thead>
                <tr>
                    <th>Contract Name</th>
                    <th>Type</th>
                    <th style="text-align: center !important;">Status</th>
                    <th style="text-align: center !important;">Start</th>
                    <th style="text-align: center !important;"></th>
                    <th style="text-align: center !important;">End</th>
                    <th style="text-align: center !important;">File</th>
                    <th style="text-align: center !important;">Action</th>
                </tr>
            </thead>

            <?php if($department === $uploader_dept | $department === $assigned_dept) : ?>

                <tbody class="table-striped p-2">
                <?php 
                if (!empty($contracts)) {
                    foreach ($contracts as $contract) { ?>
                    <tr>
                            <td><?= htmlspecialchars($contract['contract_name']) ?></td>
                            <td><?= htmlspecialchars($contract['contract_type']) ?></td>
                            <td style="text-align: center !important;">

                                <span class="badge p-2 glow-text "  style="background: #3CCF4E;width:8em;"><?= htmlspecialchars($contract['contract_status']) ?></span>
                                
                            </td>
                            <td style="text-align: center !important;"><span class="badge text-muted"><?= date("M-d-Y", strtotime($contract['contract_start'])) ?></span></td>
                            <td><hr></td>
                            <td style="text-align: center !important;"><span class="badge text-muted"><?= date("M-d-Y", strtotime($contract['contract_end'])) ?></span></td>
                            <td style="text-align: center !important;">
            <?php if (!empty($contract['contract_file'])): ?>
                <!-- Trigger the modal with this button -->
                <button class="btn btn-primary badge p-2" data-bs-toggle="modal" data-bs-target="#fileModal<?= $contract['id'] ?>" style="text-align: center !important;">
                    View file
                </button>

                        <!-- Modal -->
                        <div class="modal fade" id="fileModal<?= $contract['id'] ?>" tabindex="-1" aria-labelledby="fileModalLabel<?= $contract['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog modal-xl" style="min-height: 100vh; max-height: 300vh;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="fileModalLabel<?= $contract['id'] ?>"><?= $contract['contract_name'] ?> - <?= $contract['contract_type'] ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" style="padding: 0; overflow-y: auto;">
                                        <!-- Display the contract file inside the modal -->
                                        <iframe src="<?= htmlspecialchars("../../" . $contract['contract_file']) ?>" width="100%" style="height: 80vh;" frameborder="0"></iframe>
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

                            <?php if($department === $uploader_dept): ?>
                                    
                                    <a href="view_contract.php?contract_id=<?= $contract['id'] ?>" class="btn btn-success badge p-2" ><i class="fa fa-eye" aria-hidden="true"></i> VIew</a>
                            
                                    <a id="delete" data-id="<?= $contract['id'] ?>" class="btn btn-danger badge p-2" >
                                        <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                    </a>
                            
                                <?php else: ?>
                                    <!-- <a id="delete" data-id="<?= $contract['id'] ?>" class="btn btn-danger badge p-2" >
                                            <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                        </a> -->
                                        <a href="view_contract.php?contract_id=<?= $contract['id'] ?>" class="btn btn-success badge p-2" ><i class="fa fa-eye" aria-hidden="true"></i> VIew</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php }
                    } else { ?>
                        <tr>
                            <td colspan="7" class="text-center">No contracts found.</td>
                        </tr>
                    <?php } ?>
                </tbody>

            <?php endif; ?>

          
            
        </table>

            <!-- Pagination links -->
            <?php if ($totalContracts >= 10): ?>
                            <!-- Pagination links -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php 
                        $queryParams = $_GET;
                        $queryParams['page'] = 1;
                        $firstPageUrl = '?' . http_build_query($queryParams);

                        $queryParams['page'] = $page - 1;
                        $prevPageUrl = '?' . http_build_query($queryParams);

                        $queryParams['page'] = $page + 1;
                        $nextPageUrl = '?' . http_build_query($queryParams);

                        $queryParams['page'] = $totalPages;
                        $lastPageUrl = '?' . http_build_query($queryParams);
                        ?>
                        
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= $firstPageUrl ?>" aria-label="First">
                                <span aria-hidden="true">&laquo;&laquo;</span>
                            </a>
                        </li>
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= $prevPageUrl ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        <?php for ($i = 1; $i <= $totalPages; $i++): 
                            $queryParams['page'] = $i;
                            $pageUrl = '?' . http_build_query($queryParams);
                        ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="<?= $pageUrl ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= $nextPageUrl ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                        <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= $lastPageUrl ?>" aria-label="Last">
                                <span aria-hidden="true">&raquo;&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>


<!-- modals for every user ---->

<?php switch( $department ){

    case "ISD-HRAD" :
        include_once 'modals/hrad_modal.php';
    break;
    case "CITETD" :
        include_once 'modals/citetd_modal.php';
    break;

    }
?>


  

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
    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
  </symbol>
  <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
  </symbol>
  <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
  </symbol>
</svg>


<?php if (isset($_SESSION['notification'])): ?>
    <div id="notification" class="alert <?php echo ($_SESSION['notification']['type'] == 'success') ? 'alert-success border-success' : ($_SESSION['notification']['type'] == 'warning' ? 'alert-warning border-warning' : 'alert-danger border-danger'); ?> d-flex align-items-center float-end alert-dismissible fade show" role="alert" style="position: absolute; bottom: 5em; right: 10px; z-index: 1000; margin-bottom: -4em;">
        <!-- Icon -->
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="<?php echo ($_SESSION['notification']['type'] == 'success') ? 'Success' : ($_SESSION['notification']['type'] == 'warning' ? 'Warning' : 'Error'); ?>:">
            <use xlink:href="<?php echo ($_SESSION['notification']['type'] == 'success') ? '#check-circle-fill' : ($_SESSION['notification']['type'] == 'warning' ? '#exclamation-triangle-fill' : '#exclamation-circle-fill'); ?>"/>
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
        setTimeout(function() {
            let notification = document.getElementById('notification');
            if (notification) {
                notification.classList.remove('show');
                notification.classList.add('fade');
                notification.style.transition = 'opacity 1s ease';
            }
        }, 7000); // 6 seconds
    </script>
<?php endif; ?>


<?php include_once '../../views/layouts/includes/footer.php';   ?>

<style>
    /* Flex container for the layout */
    .pageContent {
        display: flex;
        min-height: 100vh; /* Ensure it takes full viewport height */
    }
    /* Main content styles */
    .mainContent {
        background-color: #FFF;
        width: 100%; /* Main content takes up remaining space */
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