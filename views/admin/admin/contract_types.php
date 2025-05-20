<?php

use App\Controllers\ContractTypeController;
use App\Controllers\DepartmentController;
session_start();

require_once '../../../vendor/autoload.php';

$page_title = 'Departments';
$department = $_SESSION['department'];
$contractTypes = (new ContractTypeController)->getContractTypes();


include_once '../../layouts/includes/header.php';
?>

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
        <!-- Content that will be shown after loading -->
        <div class="mt-3" id="content">
            <h2>Contract Types</h2>
            <hr>

            <div class="d-flex align-items-center gap-3 flex-wrap" style="margin-left: 1%;">
                <a class="btn text-white btn-success p-2" data-mdb-ripple-init style="width:15%;padding-right:10px;"
                    href="#!" role="button" data-bs-toggle="modal" data-bs-target="#departmentModal">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    Add Contact Type
                </a>
            </div>
        </div>

        <div class="container mt-3 col-lg-12 d-flex gap-3" style="margin-left:-.1em;">
            <table class="table table-striped table-hover border p-3">
                <thead>
                    <tr>
                        <th style="text-align: center !important;">Contract Type</th>
                        <th style="text-align: center !important;">Contract Duration</th>
                        <th style="text-align: center !important;">Expiry Reminder Threshold</th>
                        <th style="text-align: center !important;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contractTypes as $contractType) { ?>
                        <tr>
                            <td style="text-align: center !important;">

                                <?php if (isset($contractType['contract_type'])) { ?>

                                    <?php switch ($contractType['contract_type']) {
                                        case 'Employment Contract': ?>

                                            <span class="badge p-2"
                                                style="background-color: #1B56FD;"><?= $contractType['contract_type'] ?></span>

                                            <?php break;
                                        case 'Rental Contract': ?>

                                            <span class="badge p-2"
                                                style="background-color: #DC8686;"><?= $contractType['contract_type'] ?></span>

                                            <?php break;
                                        case 'Power Suppliers Contract (LONG TERM)': ?>

                                            <span class="badge p-2"
                                                style="background-color: #F7374F;"><?= $contractType['contract_type'] ?></span>

                                            <?php break;
                                        case 'Power Suppliers Contract (SHORT TERM)': ?>

                                            <span class="badge p-2"
                                                style="background-color: #183B4E;"><?= $contractType['contract_type'] ?></span>

                                            <?php break;
                                        case 'Goods Contract': ?>

                                            <span class="badge p-2"
                                                style="background-color: #EC5228;"><?= $contractType['contract_type'] ?></span>

                                            <?php break;
                                        case 'Service and Consultancy Contract': ?>

                                            <span class="badge p-2"
                                                style="background-color: #0D4715;"><?= $contractType['contract_type'] ?></span>

                                            <?php break;
                                        case 'Infrastructure Contract': ?>

                                            <span class="badge p-2"
                                                style="background-color: #FFB200;"><?= $contractType['contract_type'] ?></span>

                                            <?php break;
                                        case 'Transformer Rental': ?>

                                            <span class="badge p-2"
                                                style="background-color: #118B50;"><?= $contractType['contract_type'] ?></span>

                                            <?php break;
                                        case 'Transformer Rental Contract': ?>

                                            <span class="badge p-2"
                                                style="background-color: #003161;"><?= $contractType['contract_type'] ?></span>

                                            <?php break;
                                        case 'Temporary Lighting Contract': ?>

                                            <span class="badge p-2"
                                                style="background-color: #3B6790;"><?= $contractType['contract_type'] ?></span>

                                        <?php default: ?>
                                            <!-- <span class="badge text-muted">no department assigned</span> -->
                                    <?php } ?>

                                <?php } else { ?>

                                    <span class="badge text-muted">no department assigned</span>

                                <?php } ?>

                            </td>
                            <td style="text-align: center !important;">

                                <?php if ($contractType['contract_type'] === 'Power Suppliers Contract (LONG TERM)' | $contractType['contract_type'] === 'Power Suppliers Contract (SHORT TERM)'): ?>

                                    <?php if (!empty($contractType['contract_duration'])): ?>

                                        <?= $contractType['contract_duration'] ?> yrs

                                    <?php else: ?>

                                        --

                                    <?php endif; ?>

                                <?php else: ?>

                                    <?php if (
                                        $contractType['contract_type'] === 'Goods Contract' | $contractType['contract_type'] === 'Service and Consultancy Contract' |
                                        $contractType['contract_type'] === 'Infrastructure Contract' | $contractType['contract_type'] === 'Transformer Rental' |
                                        $contractType['contract_type'] === 'Rental Contract' | $contractType['contract_type'] === 'Temporary Lighting Contract'
                                    ): ?>

                                        <?php if (!empty($contractType['contract_duration'])): ?>

                                            <?= $contractType['contract_duration'] ?> Days

                                        <?php else: ?>

                                        <?php endif; ?>

                                    <?php else: ?>

                                        --

                                    <?php endif; ?>

                                <?php endif; ?>

                            </td>
                            <td style="text-align: center !important;">
                                <!-- <?= $contractType['contract_ert'] ?> Days -->

                                <?php if (empty($contractType['contract_ert'])): ?>
                                    --
                                <?php else: ?>
                                    <?= $contractType['contract_ert'] ?> Days
                                <?php endif; ?>



                            </td>
                            <td class="d-flex justify-content-center gap-2 w-100">
                                <button class="btn btn-primary btn-sm">Update</button>
                                <a href="contract_types/delete_contract_type.php?id=<?= $contractType['id'] ?>"
                                    class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>


            <!-- Pagination links -->
            <!-- <?php if ($totalContracts >= 10): ?>
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
                <!--<?php endif; ?> -->
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


<?php
include_once '../../layouts/includes/footer.php';
?>

<!-- Add New Contract Modal --->
<div class="modal fade" id="departmentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Contract Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="contract_types/save_contract_type.php" method="post" enctype="multipart/form-data">
                    <span class="badge text-muted">ERT - Expiry Reminder Threshhold</span>
                    <div class="col-md-12 d-flex gap-3 p-3" style="margin-left:-3%;">
                        <div class="p-2 col-md-6">
                            <label class="badge text-muted mb-1" style="margin-left: -1%;">Contract type: <span
                                    class="text-danger"> *</span></label>
                            <input class="form-control" name="contract_type" required>
                        </div>
                        <div class="p-2 col-md-3 float-start">
                            <label class="badge text-muted mb-1" style="margin-left: -1%;">Contract Duration: <span
                                    class="text-danger"> *</span></label>
                            <input type="number" class="form-control" name="contract_duration">
                        </div>
                        <div class="p-2 col-md-3 float-start">
                            <label class="badge text-muted mb-1" style="margin-left: -1%;">Contract ERT: <span
                                    class="text-danger"> *</span></label>
                            <input type="number" class="form-control" name="contract_ert">
                        </div>
                    </div>

            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                <button type="submit" class="btn btn-primary" style="background-color: #118B50;">Add Contract
                    Type</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    // When the page finishes loading, hide the spinner
    window.onload = function () {
        document.getElementById("loadingSpinner").style.display = "none"; // Hide the spinner
        document.getElementById("content").style.display = "block"; // Show the page content
    };
</script>
<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>