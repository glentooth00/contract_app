<?php
session_start();

$department = $_SESSION['department'];
$page_title = "Users List";

require_once __DIR__ . '../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../vendor/autoload.php';

use App\Controllers\ContractController;
use App\Controllers\ContractTypeController;
use App\Controllers\ContractHistoryController;
use App\Controllers\DepartmentController;
use App\Controllers\UserController;
use App\Controllers\UserRoleController;


$getUser = new UserController();
$results = $getUser->getAllUsers();

$contracts = (new ContractController)->getContractsByDepartment($department);

$getAllContractTypes = (new ContractTypeController)->getContractTypes();

$getOneLatest = (new ContractHistoryController)->insertLatestData();
if ($getOneLatest) {
    // echo '<script>alert("Latest data inserted")</script>';
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

<div class="main-layout">

    <?php include_once '../menu/sidebar.php'; ?>


    <div class="content-area">

        <h1>Users</h1>
        <span class="p-1 d-flex float-end" style="margin-top: -2.5em;">
            <!-- <?= $department = $_SESSION['department'] ?? null; ?> Account -->

            <?php if (isset($department)) { ?>

                <?php switch ($department) {
                    case 'IT': ?>

                        <span class="badge p-2" style="background-color: #0d6efd;"><?= $department; ?> user</span>

                        <?php break;
                    case 'ISD': ?>

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

        <a href="#!" class="btn btn-success text-white p-2 mb-3 d-flex align-items-center gap-2 fw-bold"
            style="width: 10em;" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <img style="margin-left: 17px;" width="23px" src="../../../public/images/add_user.svg"></img> Add User
        </a>

        <!-- Wrap both search and filter in a flex container -->
        <div style="margin-bottom: 20px; display: flex; justify-content: flex-start; gap: 10px;">


            <!-- Contract Type Filter -->
            <!-- <div style="text-align: right;">
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
            </div> -->
        </div>

        <table id="table" class="table table-bordered table-striped display mt-2 hover">
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

                            <?php
                            $imageSrc = '';
                            if (!empty($result['user_image'])) {
                                $imageSrc = "/contract_app/admin/user_image/{$result['user_image']}";
                            } else {
                                $imageSrc = "/contract_app/public/images/male.png";
                            }
                            ?>

                            <img src="<?= $imageSrc ?>" width="90" height="90"
                                style="border-radius: 50%; object-fit: cover;">
                        </td>
                        <td style="text-align: center !important;padding:40px;">
                            <span class="mt-3"> <?= $result['firstname'] ?>     <?= $result['middlename'] ?>
                                <?= $result['lastname'] ?></span>
                        </td>
                        <td style="text-align: center !important;padding:40px;"><?= $result['user_role'] ?> </td>
                        <td style="text-align: center !important;padding:40px;">

                            <?php
                                $department = isset($result['department']) ? $result['department'] : '';

                                switch ($department) {
                                    case IT:
                                        $badgeColor = '#0d6efd';
                                        break;
                                    case ISD:
                                        $badgeColor = '#3F7D58';
                                        break;
                                    case CITET:
                                        $badgeColor = '#FFB433';
                                        break;
                                    case IASD:
                                        $badgeColor = '#EB5B00';
                                        break;
                                    case 'ISD-MSD':
                                        $badgeColor = '#6A9C89';
                                        break;
                                    case 'PSPTD':
                                        $badgeColor = '#83B582';
                                        break;
                                    case FSD:
                                        $badgeColor = '#4E6688';
                                        break;
                                    case BAC:
                                        $badgeColor = '#123458';
                                        break;
                                    case AOSD:
                                        $badgeColor = '#03A791';
                                        break;
                                    case GM:
                                        $badgeColor = '#A2D5C6';
                                        break;
                                    case '':
                                        $badgeColor = '';
                                        break;
                                    default:
                                        $badgeColor = '';
                                        break;
                                }
                                
                                ?>
                            <?php if (!empty($department) && $badgeColor): ?>
                                <span class="badge p-2 text-white" style="background-color: <?= $badgeColor ?>;">
                                    <?= htmlspecialchars($department) ?>
                                </span>
                            <?php else: ?>
                                <span class="badge text-muted">no department assigned</span>
                            <?php endif; ?>


                        </td>
                        <td style="text-align: center !important;padding:40px;">
                            <div class="d-flex gap-2" style="margin-left:5em;">
                                <a href="view_user.php?id=<?= $result['id'] ?>" class="btn btn-success btn-sm"><i
                                        class="fa fa-pencil" aria-hidden="true"></i>
                                    View</a>

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
    </div>
</div>


<?php include_once '../../../views/layouts/includes/footer.php'; ?>




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

<!-- Add New Contract Modal --->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add User Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="users/save_user.php" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <!-- User Image -->
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="badge text-muted form-label">User image <span
                                        class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="user_image" required>
                            </div>
                        </div>

                        <!-- User Info -->
                        <div class="col-md-9">
                            <!-- Fullname Row -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="badge text-muted form-label">Firstname <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="firstname" class="form-control" placeholder="Firstname"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label class="badge text-muted form-label">Middlename <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="middlename" class="form-control" placeholder="Middlename"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label class="badge text-muted form-label">Lastname <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="lastname" class="form-control" placeholder="Lastname"
                                        required>
                                </div>
                            </div>

                            <!-- Account Info Row -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="badge text-muted form-label">Username <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="username" class="form-control" placeholder="Username"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label class="badge text-muted form-label">Password <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="password" class="form-control" placeholder="Password"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label class="badge text-muted form-label">Gender <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" name="gender" required>
                                        <option selected hidden>Select gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Department & Role Row -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="badge text-muted form-label">Department <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" name="department" required>
                                        <option selected hidden>Select Department</option>
                                        <?php
                                        $getDept = (new DepartmentController)->getAllDepartments();
                                        foreach ($getDept as $dept): ?>
                                            <option value="<?= htmlspecialchars($dept['department_name']) ?>">
                                                <?= htmlspecialchars($dept['department_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="badge text-muted form-label">Role <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" name="user_role" required>
                                        <option selected hidden>Select Role</option>
                                        <?php
                                        $getDept = (new UserRoleController)->getRoles();
                                        foreach ($getDept as $dept): ?>
                                            <option value="<?= htmlspecialchars($dept['user_role']) ?>">
                                                <?= htmlspecialchars($dept['user_role']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="badge text-muted form-label">User Type<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" name="user_type" required>
                                        <option value="" selected hidden>Select User Type</option>
                                        <option value="Admin">Admin</option>
                                        <option value="User">User</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contracts Section -->
                    <div class="col-12 mb-4">
                        <h3 class=" mb-2">Contracts</h3>
                        <hr>
                        <div class="row">
                            <?php foreach ($getAllContractTypes as $getAllContractType): ?>
                                <?php
                                $type = isset($getAllContractType['contract_type']) ? $getAllContractType['contract_type'] : '';

                                switch ($type) {
                                    case INFRA:
                                        $badgeColor = '#328E6E';
                                        break;
                                    case SACC:
                                        $badgeColor = '#123458';
                                        break;
                                    case GOODS:
                                        $badgeColor = '#F75A5A';
                                        break;
                                    case EMP_CON:
                                        $badgeColor = '#DC8686';
                                        break;
                                    case PSC_LONG:
                                        $badgeColor = '#007bff';
                                        break;
                                    case PSC_SHORT:
                                        $badgeColor = '#28a745';
                                        break;
                                    case TRANS_RENT:
                                        $badgeColor = '#003092';
                                        break;
                                    case TEMP_LIGHTING:
                                        $badgeColor = '#03A791';
                                        break;
                                    default:
                                        $badgeColor = '#FAB12F';
                                        break;
                                }
                                ?>

                                <div class="col-md-4 mb-2">
                                    <label class="form-check-label d-flex align-items-center gap-2">
                                        <input type="checkbox" class="form-check-input" name="contract_type[]"
                                            value="<?= htmlspecialchars($type) ?>">
                                        <span class="badge text-white"
                                            style="background-color: <?= $badgeColor ?>; border-radius: 5px; font-size: 11px; padding: 7px;">
                                            <?= htmlspecialchars($type) ?>
                                        </span>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" style="background-color: #118B50;">Create
                            user Account</button>
                    </div>
                </form>
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
        role="alert" style="position: fixed; bottom: 1.5em; right: 1em; z-index: 1000;">
        <!-- Icon -->
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
            aria-label="<?php echo ($_SESSION['notification']['type'] == 'success') ? 'Success' : ($_SESSION['notification']['type'] == 'warning' ? 'Warning' : 'Error'); ?>:">
            <use
                xlink:href="<?php echo ($_SESSION['notification']['type'] == 'success') ? '#check-circle-fill' : '#exclamation-triangle-fill'; ?>" />

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
</style>

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


    document.getElementById('department').addEventListener('change', function () {
        const roleSelect = document.getElementById('user_role');
        if (this.value === 'FSD') {
            roleSelect.value = 'Manager';
        } else {
            roleSelect.value = '';
        }
    });

</script>