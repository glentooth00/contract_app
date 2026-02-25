<?php

use App\Controllers\UserController;
session_start();
$department = $_SESSION['department'] ?? null;
$role = $_SESSION['user_role'] ?? null;
$user_id = $_SESSION['id'] ?? null;
$page_title = "List - $department";
$userid = $_SESSION['id'] ?? null;
require_once __DIR__ . '../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../vendor/autoload.php';

use App\Controllers\ContractController;
use App\Controllers\ContractTypeController;
use App\Controllers\CommentController;
use App\Controllers\FlagController;

// getting the contract types to be compared with the contracts for their expiration
$contractTypes = $getAllContractType = (new ContractTypeController)->getContractTypes();
foreach ($contractTypes as $row) {

    $contractType = $row['contract_type'];
    $EmpErt = $row['contract_ert'];

}
include_once '../../../views/layouts/includes/header.php';
?>
<style>
    #attention {
        padding: 0;
    }
</style>

<!-- Loading Spinner - Initially visible -->
<!-- <div id="loadingSpinner" class="text-center"
    style="z-index:9999999;padding:100px;height:100%;width:100%;background-color: rgb(203 199 199 / 82%);position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
    <div class="spinner-border" style="width: 3rem; height: 3rem;margin-top:15em;" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div> -->

<div class="main-layout">
    <?php include_once '../menu/sidebar.php'; ?>

    <div class="content-area">

        <h1>Manage Password</h1>
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
                    <img width="25px" src="../../../../../public/images/bell.svg" alt="Activities need attention">
                </div>
            </a>



            <?php if (isset($department)) { ?>


                <?php switch ($department) {
                    case 'IT': ?>

                        <span class="badge p-2" style="background-color: #0d6efd;">
                            <?= $role ?> user
                        </span>

                        <?php break;
                    case 'ISD': ?>

                        <span class="badge p-2" style="background-color: #3F7D58;">
                            <?= $role ?> user
                        </span>

                        <?php break;
                    case 'CITET': ?>

                        <span class="badge p-2" style="background-color: #FFB433;">
                            <?= $role ?> user
                        </span>

                        <?php break;
                    case 'IASD': ?>

                        <span class="badge p-2" style="background-color: #EB5B00;">
                            <?= $role ?> user
                        </span>

                        <?php break;
                    case 'ISD-MSD': ?>

                        <span class="badge p-2" style="background-color: #6A9C89;">
                            <?= $role ?> user
                        </span>

                        <?php break;
                    case 'BAC': ?>

                        <span class="badge p-2" style="background-color: #3B6790;">
                            <?= $role ?> user
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
        <!-- Wrap both search and filter in a flex container -->
        <div class="d-flex gap-3 col-md-5">
            <?php
            $getPassword = (new UserController())->getUserpassword($id);
            ?>

            <!-- Current Password -->
            <div class="flex-fill">
                <label for="currentPassword1" class="form-label">Current Password</label>
                <input type="text" id="currentPassword1" class="form-control" value="<?= $getPassword['password'] ?>"
                    readonly>
            </div>

            <!-- New Password + Button -->
            <div class="flex-fill">
                <form>
                    <label for="currentPassword2" class="form-label">New Password</label>

                    <div class="d-flex gap-2">
                        <input type="password" name="newPassword" id="current-Password2" class="form-control"
                            placeholder="Enter new password">

                        <button type="submit" id="btnChangePassword" class="btn btn-primary btn-sm">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>

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



<!-- popup notification ---->

<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <sym bol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <pat h
            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
    </sym>
    <sym bol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <pat h
            d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
    </sym>
    <sym bol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <pat h
            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
    </sym>
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


    function updatePassword() {
        var id = document.getElementById(currentPassword2).value

        console.log(id);


    }

</script>