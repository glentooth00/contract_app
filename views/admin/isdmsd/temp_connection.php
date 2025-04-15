<?php 
session_start();

$page_title = 'Temporary Connection';

$department =  $_SESSION['department'] ?? null;


include_once '../../../views/layouts/includes/header.php';
?>

<!-- Loading Spinner - Initially visible -->
<div id="loadingSpinner" class="text-center" style="z-index:9999999;padding:100px;height:100%;width:100%;background-color: rgb(203 199 199 / 82%);position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <div class="spinner-border" style="width: 3rem; height: 3rem;margin-top:15em;" role="status">
    <span class="sr-only">Loading...</span>
    </div>
</div>


<div class="pageContent">
    <div class="sideBar bg-dark">
       <?php include_once '../sidebar.php'; ?>
    </div>

    <div class="mainContent" style="margin:auto;margin-top:0;">
        <!-- Content that will be shown after loading -->
        <div class="mt-2" id="content">
            <h2>Active Contracts</h2>
          
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



<?php 
include_once '../../../views/layouts/includes/footer.php';
?>

<script>
            // When the page finishes loading, hide the spinner
            window.onload = function() {
            document.getElementById("loadingSpinner").style.display = "none"; // Hide the spinner
            document.getElementById("content").style.display = "block"; // Show the page content
        };
</script>