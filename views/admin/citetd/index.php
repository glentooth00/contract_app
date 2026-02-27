<?php
session_start();
$department = $_SESSION['department'] ?? null;
$role = $_SESSION['user_role'] ?? null;
$page_title = "List - $department";
$userid = $_SESSION['id'] ?? null;
require_once __DIR__ . '../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../vendor/autoload.php';


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

<div class="main-layout" style="display: flex; gap: 1.5rem;">

    <?php include_once '../menu/sidebar.php'; ?>

    <div class="content-area" style="flex: 1; padding: 2rem; background-color: #f8f9fa; min-height: 90vh;">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 style="font-size: 1.8rem; font-weight: 600; color: #343a40;">Contracts Overview</h1>

            <?php if (isset($department)): ?>
                <?php
                $colors = [
                    'IT' => '#0d6efd',
                    'ISD-HRAD' => '#3F7D58',
                    'CITETD' => '#FFB433',
                    'IASD' => '#EB5B00',
                    'ISD-MSD' => '#6A9C89',
                    'BAC' => '#3B6790'
                ];
                $bg = $colors[$department] ?? '#6c757d';
                ?>
                <span class="badge p-2" style="background-color: <?= $bg ?>; font-size: 0.9rem;">
                    <?= htmlspecialchars($department) ?> user
                </span>
            <?php endif; ?>
        </div>

        <hr style="border-top: 2px solid #dee2e6;">

        <!-- Filters & Search -->
        <div class="mb-4">
            <?php include_once '../contents/filter.php'; ?>
        </div>

        <!-- Dashboard Content -->
        <div>
            <?php include_once '../contents/dashboard_content.php'; ?>
        </div>

    </div>
</div>

<?php include_once '../../../views/layouts/includes/footer.php'; ?>


<!-- Modal: Confirm Delete -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-0">Are you sure you want to delete this contract?</p>
            </div>
            <div class="modal-footer">
                <a href="#" id="confirmDelete" class="btn btn-danger">Yes, Delete</a>
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


<!-- Notification -->
<?php if (isset($_SESSION['notification'])): ?>
    <div id="notification"
        class="alert <?php echo ($_SESSION['notification']['type'] == 'success') ? 'alert-success' : ($_SESSION['notification']['type'] == 'warning' ? 'alert-warning' : 'alert-danger'); ?> d-flex align-items-center position-fixed bottom-0 end-0 m-3 shadow-sm border rounded-3 fade show"
        role="alert" style="z-index: 1050; min-width: 280px;">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Notification:">
            <use
                xlink:href="<?php echo ($_SESSION['notification']['type'] == 'success') ? '#check-circle-fill' : ($_SESSION['notification']['type'] == 'warning' ? '#exclamation-triangle-fill' : '#exclamation-circle-fill'); ?>" />
        </svg>
        <div><?= htmlspecialchars($_SESSION['notification']['message']); ?></div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['notification']); ?>
    <script>
        setTimeout(() => {
            let notification = document.getElementById('notification');
            if (notification) {
                notification.classList.remove('show');
                notification.classList.add('fade');
                notification.style.transition = 'opacity 1s ease';
            }
        }, 6000);
    </script>
<?php endif; ?>

<style>
    .content-area h1 {
        letter-spacing: 0.5px;
    }

    .badge {
        font-size: 0.85rem;
        font-weight: 500;
    }

    .modal-content {
        border-radius: 12px;
    }

    .modal-header {
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    .modal-footer .btn {
        border-radius: 6px;
    }

    /* Small responsive adjustments */
    @media (max-width: 768px) {
        .content-area {
            padding: 1rem;
        }

        #searchContainer {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 1rem !important;
        }
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
</script>