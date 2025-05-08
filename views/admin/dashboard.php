<?php

use App\Controllers\ContractController;
use App\Controllers\ContractTypeController;
use App\Controllers\UserController;

session_start();

require_once __DIR__ . '../../../vendor/autoload.php';

$get_id = $_SESSION['data'];

$id = $get_id['id'];

$get_user_department = (new UserController)->getUserDepartmentById($id);

$user_department = $get_user_department['department'];

$department = $_SESSION['department'] = $get_id['department'];

$savedContracts = new ContractController();

$page_title = 'Dashboard';

$contractsPerPage = 10; // Default number of contracts per page
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page - 1) * $contractsPerPage;

// Get filters
$contract_filter = isset($_GET['contract_type_filter']) ? $_GET['contract_type_filter'] : null;
$search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : null;
$status_filter = isset($_GET['contract_status_filter']) ? $_GET['contract_status_filter'] : null; // ✅ ADDED

// Fetch filtered contracts with pagination
$contracts = $savedContracts->getOldContractsWithPaginationAll($start, $contractsPerPage, $contract_filter, $search_query, $status_filter);

// Get total number of contracts for pagination
$totalContracts = $savedContracts->getTotalContracts($contract_filter, $status_filter, $search_query);

// Check if the total contracts are less than 10, adjust contracts per page
if ($totalContracts <= 10) {
    $contractsPerPage = $totalContracts;
}

// Make sure totalContracts is not zero before dividing
if ($totalContracts > 0 && $contractsPerPage > 0) {
    $totalPages = ceil($totalContracts / $contractsPerPage);
} else {
    // If no contracts exist, set totalPages to 0 or 1 depending on your needs
    $totalPages = 1;  // Fallback to 1 page if no contracts
}

// If there's only 1 or fewer total records, ensure pagination is for 1 page
if ($totalPages < 1) {
    $totalPages = 1;
}

$emp_ert = (new ContractTypeController)->getEmploymentErt();

$EmpErt = $emp_ert['contract_ert'];


include_once '../../views/layouts/includes/header.php';
?>


<!-- Loading Spinner - Initially visible -->
<div id="loadingSpinner" class="text-center"
    style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<div class="pageContent">
    <div class="sideBar bg-dark">
        <?php include_once 'sidebar.php'; ?>
    </div>

    <div class="mainContent">
        <!-- Content that will be shown after loading -->
        <div id="content" class="mt-2">

            <div class="d-flex">
                <h2 class="col-md-10">Contracts Overview</h2>

                <span class="mt-2 p-1 d-flex">
                    <!-- <?= $department = $_SESSION['department'] ?? null; ?> Account -->

                    <?php if (isset($user_department)) { ?>

                        <?php switch ($user_department) {
                            case 'IT': ?>

                                <span class="badge p-2" style="background-color: #0d6efd;"><?= $user_department; ?> user</span>

                                <?php break;
                            case 'ISD-HRAD': ?>

                                <span class="badge p-2" style="background-color: #3F7D58;"><?= $user_department; ?> user</span>

                                <?php break;
                            case 'CITETD': ?>

                                <span class="badge p-2" style="background-color: #FFB433;"><?= $user_department; ?> user</span>

                                <?php break;
                            case 'IASD': ?>

                                <span class="badge p-2" style="background-color: #EB5B00;"><?= $user_department; ?> user</span>

                                <?php break;
                            case 'ISD-MSD': ?>

                                <span class="badge p-2" style="background-color: #6A9C89;"><?= $user_department; ?> user</span>

                                <?php break;
                            case 'BAC': ?>

                                <span class="badge p-2" style="background-color: #3B6790;"><?= $user_department; ?> user</span>

                                <?php break;
                            case '': ?>

                            <?php default: ?>
                                <!-- <span class="badge text-muted">no department assigned</span> -->
                        <?php } ?>

                    <?php } else { ?>

                        <!-- <span class="badge text-muted">no department assigned</span> -->

                    <?php } ?>
                </span>

            </div>
            <hr>

            <span class="text-sm badge" style="color:#AAB99A;">NOTE: Search by Contract type and Contract Name.</span>
            <div class="d-flex align-items-center gap-3 flex-wrap" style="margin-top:4px;">
                <div>
                    <form method="GET" action="dashboard.php" class="d-flex align-items-center">
                        <select class="form-select w-auto me-2" name="contract_type_filter"
                            onchange="this.form.submit()">
                            <option value="" <?= isset($_GET['contract_type_filter']) && $_GET['contract_type_filter'] == "" ? "selected" : "" ?>>All Contracts</option>
                            <option value="Employment Contract" <?= isset($_GET['contract_type_filter']) && $_GET['contract_type_filter'] == "Employment Contract" ? "selected" : "" ?>>Employment
                                Contract</option>
                            <option value="Construction Contract" <?= isset($_GET['contract_type_filter']) && $_GET['contract_type_filter'] == "Construction Contract" ? "selected" : "" ?>>Construction
                                Contract</option>
                            <option value="Licensing Agreement" <?= isset($_GET['contract_type_filter']) && $_GET['contract_type_filter'] == "Licensing Agreement" ? "selected" : "" ?>>Licensing
                                Agreement</option>
                            <option value="Purchase Agreement" <?= isset($_GET['contract_type_filter']) && $_GET['contract_type_filter'] == "Purchase Agreement" ? "selected" : "" ?>>Purchase
                                Agreement</option>
                            <option value="Service Agreement" <?= isset($_GET['contract_type_filter']) && $_GET['contract_type_filter'] == "Service Agreement" ? "selected" : "" ?>>Service
                                Agreement</option>
                        </select>

                        <select class="form-select w-auto" name="contract_status_filter" onchange="this.form.submit()">
                            <option value="" <?= isset($_GET['contract_status_filter']) && $_GET['contract_status_filter'] == "" ? "selected" : "" ?>>All Status</option>
                            <option value="Expired" <?= isset($_GET['contract_status_filter']) && $_GET['contract_status_filter'] == "Expired" ? "selected" : "" ?>>Expired</option>
                            <option value="Active" <?= isset($_GET['contract_status_filter']) && $_GET['contract_status_filter'] == "Active" ? "selected" : "" ?>>Active</option>
                        </select>
                    </form>
                </div>


                <form method="GET" action="dashboard.php" class="input-group" style="width: 250px;">
                    <input type="text" class="form-control" name="search_query"
                        value="<?= isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : '' ?>"
                        placeholder="Search Contract Name">
                    <button class="btn bg-dark text-white" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>

            <table class="table table-striped border p-2 mt-1">
                <thead>
                    <tr>
                        <th>Contract Name</th>
                        <th style="text-align: center !important;">Contract Type</th>
                        <th style="text-align: center !important;">Contract Start</th>
                        <th style="text-align: center !important;">Contract End</th>
                        <th style="text-align: center;">Days Remaining</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($contracts)): ?>
                        <tr>
                            <td colspan="5" class="text-center">No contracts available</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($contracts as $contract): ?>
                            <tr>
                                <td><?= isset($contract['contract_name']) ? htmlspecialchars($contract['contract_name']) : 'N/A' ?>
                                </td>
                                <td style="text-align: center !important;">
                                    <?= isset($contract['contract_type']) ? htmlspecialchars($contract['contract_type']) : 'N/A' ?>
                                </td>
                                <td style="text-align: center !important;">
                                    <span
                                        class="badge text-muted"><?= isset($contract['contract_start']) ? date("M-d-Y", strtotime($contract['contract_start'])) : 'N/A' ?></span>
                                </td>
                                <td style="text-align: center !important;">
                                    <span
                                        class="badge text-muted"><?= isset($contract['contract_end']) ? date("M-d-Y", strtotime($contract['contract_end'])) : 'N/A' ?></span>
                                </td>
                                <td style="text-align: center !important;">
                                    <?php
                                    $days_left = 0;

                                    if (!empty($contract['contract_end'])) {
                                        $contractEnd = new DateTime($contract['contract_end']);
                                        $currentDate = new DateTime();

                                        if ($currentDate <= $contractEnd) {
                                            $interval = $currentDate->diff($contractEnd);
                                            $days_left = $interval->days;
                                        }
                                    }
                                    ?>

                                    <?php if ($days_left <= $EmpErt && $days_left > 0): ?>
                                        <!-- Contracts expiring within 5 days -->
                                        <span class="badge p-2 font-monospace border border-danger fw-semibold"
                                            style="font-size:15px;background-color:#E52020;width:14em;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                                                <path
                                                    d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z" />
                                                <path
                                                    d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                                            </svg>
                                            <?= $days_left ?> days left
                                        </span>

                                    <?php elseif ($days_left <= 0): ?>
                                        <?php
                                        $_SESSION['contract_status'] = 'Expired';
                                        $_SESSION['contract_id'] = $contract['id'];
                                        ?>
                                        <!-- Expired contracts -->
                                        <span class="badge border-danger p-2 font-monospace fw-semibold"
                                            style="font-size:15px;background-color:#FF9B17;width:14em;">
                                            Expired
                                        </span>

                                    <?php else: ?>
                                        <!-- Contracts with more than 5 days left -->
                                        <span class="badge p-2 border font-monospace fw-semibold"
                                            style="font-size:15px;background-color:#04a12b;width:14em;">
                                            <?= $days_left ?> days until expiry
                                        </span>
                                    <?php endif; ?>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>


            </table>

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

<!-- <?php

$contract_status = $_SESSION['contract_status'];
$contract_id = $_SESSION['contract_id'];


$update_contract_status = $savedContracts->updateContractStatus($contract_id, $contract_status);


?> -->

<?php include_once '../../views/layouts/includes/footer.php'; ?>

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
</style>

<script>
    // When the page finishes loading, hide the spinner
    window.onload = function () {
        document.getElementById("loadingSpinner").style.display = "none"; // Hide the spinner
        document.getElementById("content").style.display = "block"; // Show the page content
    };
</script>