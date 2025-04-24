<?php

use App\Controllers\DepartmentController;
use App\Controllers\UserController;
session_start();

require_once __DIR__ . '../../../../vendor/autoload.php';


$page_title = 'Manage Users';

$getUser = new UserController();
$results = $getUser->getAllUsers();

// $getDepartments = (new DepartmentController)->getAllDepartments();

include_once '../../../views/layouts/includes/header.php'; 
?>

<!-- Loading Spinner - Initially visible -->
    <div id="loadingSpinner" class="text-center" style="z-index:9999999;padding:100px;height:100%;width:100%;background-color: rgb(203 199 199 / 82%);position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <div class="spinner-border" style="width: 3rem; height: 3rem;margin-top:15em;" role="status">
    <span class="sr-only">Loading...</span>
    </div>
</div>
    </div>

<div class="pageContent">
    <div class="sideBar bg-dark">
       <?php include_once '../menu/sidebar.php'; ?>
    </div>

    <div class="mainContent" style="margin:auto;margin-top:0;">
        <!-- Content that will be shown after loading -->
        <div class="mt-3" id="content">
            <h2>Manage Users</h2>
            <hr>

<div class="d-flex align-items-center gap-3 flex-wrap" style="margin-left: 1%;">
    <a class="btn text-white btn-success p-2" data-mdb-ripple-init style="width:15%;padding-right:10px;" href="#!" role="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
        <i class="fa fa-user-plus" aria-hidden="true"></i>
        Add User
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
</div>

    <div class="container mt-3 col-lg-12 d-flex gap-3" style="margin-left:-.1em;">
<table class="table table-striped table-hover border p-3">
    <thead>
        <tr>
            <th style="text-align: center !important;">Image</th>
            <th style="text-align: center !important;">Name</th>
            <th style="text-align: center !important;">Role</th>
            <th style="text-align: center !important;">Department</th>
            <th style="text-align: center !important;">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $result) { ?>
            <tr>
                <td style="text-align: center !important;">

                <?php if ($result['gender'] === 'Male' || $result['gender'] === 'Female'): ?>
                    <img src="../../../admin/user_image/<?= $result['user_image'] ?>" width="90px;" style="border-radius:50px;">
                <?php elseif (!empty($result['user_image'])): ?>
                    <img src="../../../admin/user_image/<?= $result['user_image'] ?>" width="90px;" style="border-radius:50px;">
                <?php else: ?>
                    <img src="../../../public/images/male.png" width="90px;">
                <?php endif; ?>


                </td>
                <td style="text-align: center !important;padding:40px;">
                    <span class="mt-3"> <?= $result['firstname'] ?> <?= $result['middlename'] ?> <?= $result['lastname'] ?></span>
                </td>
                <td style="text-align: center !important;padding:40px;"><?= $result['user_role'] ?> </td>
                <td style="text-align: center !important;padding:40px;">

                    <?php if(isset($result['department'])){ ?>

                            <?php switch( $result['department'] ) { case 'IT': ?>

                                    <span class="badge p-2" style="background-color: #0d6efd;"><?= $result['department'] ?></span>
                                
                                <?php break; case 'ISD-HRAD': ?>

                                    <span class="badge p-2" style="background-color: #3F7D58;"><?= $result['department'] ?></span>
                                
                                <?php break; case 'CITETD': ?>

                                    <span class="badge p-2" style="background-color: #FFB433;"><?= $result['department'] ?></span>
                                
                                <?php break; case 'IASD': ?>
                                    
                                    <span class="badge p-2" style="background-color: #EB5B00;"><?= $result['department'] ?></span>
                                
                                <?php break;case 'ISD-MSD': ?>

                                    <span class="badge p-2" style="background-color: #6A9C89;"><?= $result['department'] ?></span>
                                
                                <?php break; case 'BAC':  ?>

                                    <span class="badge p-2" style="background-color: #3B6790;"><?= $result['department'] ?></span>
                                
                                <?php break;  case '': ?>
                                
                                <?php default: ?>
                                    <span class="badge text-muted">no department assigned</span>
                            <?php } ?>
                        
                    <?php }else { ?>

                        <span class="badge text-muted">no department assigned</span>
                    
                        <?php } ?>

                </td>
                <td  style="text-align: center !important;padding:40px;">
                    <div class="d-flex gap-2" style="margin-left:5em;">
                        <button class="btn btn-success btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i> View</button>
                        
                        <form action="users/delete.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $result['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
                            </button>
                        </form>

                    </div>
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



<!-- Add New Contract Modal --->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add User Account</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="users/save_user.php" method="post" enctype="multipart/form-data">
        <div class="col-md-12 d-flex gap-2">
            <div class="p-2">
                <div class="mb-3">
                    <label class="badge text-muted">User image  <span class="text-danger">*</span></label>
                   
                    <input type="file" name="user_image" class="form-control" required>
                    <span class="badge" style="color:#4C585B;">(suggested image size : 216 x 234 pixels)</span>

                </div>
                <div class="d-flex gap-2">
                <div class="mb-3">
                    <label class="badge text-muted">First name  <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="firstname" id="floatingInput" placeholder="Firstname" required>  
                </div>
                <div class="mb-3">
                    <label class="badge text-muted">Middle name  <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="middlename" id="floatingInput" placeholder="Middlename" required> 
                </div>
                <div class="mb-3">
                <label class="badge text-muted">Last name  <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="lastname" id="floatingInput" placeholder="Lastname" required>
                </div>
                </div>
                <div class="mb-3">
                    <label class="badge text-muted">Gender<span class="text-danger">*</span></label>
                        <select class="form-select form-select-md mb-3" name="gender" aria-label=".form-select-lg example">
                            <option selected hidden>Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                </div>
                <hr>
                <div class="col-md-12 gap-2 d-flex">
                <div class="mb-3 col-md-6">
                    <label class="badge text-muted">Department<span class="text-danger">*</span></label>
                        <select class="form-select form-select-md mb-3" name="department" aria-label=".form-select-lg example">
                            <option selected hidden>Select Department</option>
                            <?php 
                                $getDept = (new DepartmentController)->getAllDepartments();
                            ?>
                            <?php foreach($getDept as $dept): ?>
                                <option value="<?= $dept['department_name'] ?>"><?= $dept['department_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                </div>
                <div class="mb-3 col-md-6">
                    <label class="badge text-muted">Role  <span class="text-danger">*</span></label>
                        <select class="form-select form-select-md mb-3" name="user_role" aria-label=".form-select-lg example">
                            <option selected hidden>Select user role</option>
                            <option value="User">User</option>
                            <option value="Admin">Admin</option>
                        </select>
                </div>
                </div>
               
                <div class="mb-3">
                    <label class="badge text-muted">Username  <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="username" id="floatingInput" placeholder="Username" required> 
                </div>
                <div class="mb-3">
                    <label class="badge text-muted">Password  <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="password" id="floatingInput" placeholder="Password" required> 
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
        <button type="submit" class="btn btn-primary" style="background-color: #118B50;">Create user</button>
      </div>
      </form>
    </div>
  </div>
</div>

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


<?php include_once '../../../views/layouts/includes/footer.php';   ?>

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
    .sideBar{
        height: 150vh;
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