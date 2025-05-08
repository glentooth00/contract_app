<?php
session_start();

$department = $_SESSION['department'] ?? null;
$page_title = "List - $department";

require_once __DIR__ . '../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../vendor/autoload.php';

use App\Controllers\ContractController;
use App\Controllers\ContractTypeController;

$contracts = (new ContractController)->getContractsByDepartment($department);

// getting the contract types to be compared with the contracts for their expiration
$contractTypes = $getAllContractType = (new ContractTypeController)->getContractTypes();
foreach ($contractTypes as $row) {

    $contractType = $row['contract_type'];
    $EmpErt = $row['contract_ert'];

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

        <h1>Contracts Overview</h1>
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
        <!-- Wrap both search and filter in a flex container -->
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
                    <th scope="col" style="border: 1px solid #A9A9A9;">Name</th>
                    <th scope="col" style="text-align: center; border: 1px solid #A9A9A9;">Contract type</th>
                    <th scope="col" style="text-align: center; border: 1px solid #A9A9A9;">Start</th>
                    <th scope="col" style="text-align: center; border: 1px solid #A9A9A9;">End</th>
                    <th scope="col" style="text-align: center; border: 1px solid #A9A9A9;">Status</th>
                    <th scope="col" style="text-align: center; border: 1px solid #A9A9A9;">Days Remaining</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($contracts)): ?>
                    <?php foreach ($contracts as $contract): ?>
                        <tr>
                            <td><?= htmlspecialchars($contract['contract_name'] ?? '') ?></td>
                            <td class="text-center">
                                <?php
                                $type = $contract['contract_type'] ?? '';
                                $badgeColor = match ($type) {
                                    TRANS_RENT => '#003092',
                                    TEMP_LIGHTING => '#03A791',
                                    'Power Suppliers Contract (LONG TERM)' => '#007bff',
                                    'Power Suppliers Contract (SHORT TERM)' => '#28a745',
                                    default => '#FAB12F'
                                };
                                ?>
                                <span class="p-2 text-white badge"
                                    style="background-color: <?= $badgeColor ?>; border-radius: 5px;">
                                    <?= htmlspecialchars($type) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge text-secondary">
                                    <?= !empty($contract['contract_start']) ? date('F-d-Y', strtotime($contract['contract_start'])) : '' ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge text-secondary">
                                    <?= !empty($contract['contract_end']) ? date('F-d-Y', strtotime($contract['contract_end'])) : '' ?></span>
                            </td>
                            <td class="text-center">
                                <span
                                    class="badge text-white <?= ($contract['contract_status'] ?? '') === 'Active' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= htmlspecialchars($contract['contract_status'] ?? '') ?>
                                </span>
                            </td>

                            <?php
                            $contractType = $contract['contract_type'];

                            $getFromContractType = (new ContractTypeController)->getContractTypeByDepartment($contractType);

                            foreach ($getFromContractType as $row) {

                                if ($contractType === $row['contract_type']) {

                                    $end = new DateTime($contract['contract_end']);
                                    $now = new DateTime();

                                    $interval = $now->diff($end);
                                    $diff = $interval->days;

                                    $diff;

                                }

                            }
                            ?>

                            <?php switch ($contractType):
                                case EMP_CON: ?>
                                    <!-- Code for EMP_CON -->
                                    <span>
                                        <?php
                                        $getFromContractType = (new ContractTypeController)->getContractTypeByDepartment($contractType);

                                        foreach ($getFromContractType as $row) {
                                            if ($contractType === $row['contract_type']) {
                                                $end = new DateTime($contract['contract_end']);
                                                $now = new DateTime();
                                                $ert = $row['contract_ert'];

                                                $interval = $now->diff($end);
                                                $diff = $interval->days;

                                                // $diff;
                        
                                                if ($diff >= $ert) {
                                                    echo '<td class="text-center table-success">
                                                            <span class="text-success fw-bold">' . $diff . ' days remaining </span>
                                                        </td>';
                                                } else {
                                                    echo '
                                                    <td class="text-center table-danger">
                                                        <span class="text-danger fw-bold">' . $diff . ' days remaining before expiring </span>
                                                    </td>';

                                                }

                                            }
                                        }
                                        ?>
                                    </span>
                                    <?php break;
                                case PSC_LONG: ?>
                                    <!-- Code for PSC_LONG -->
                                    <!-- Code for EMP_CON -->
                                    <span>
                                        <?php
                                        $getFromContractType = (new ContractTypeController)->getContractTypeByDepartment($contractType);

                                        foreach ($getFromContractType as $row) {
                                            if ($contractType === $row['contract_type']) {
                                                $end = new DateTime($contract['contract_end']);
                                                $now = new DateTime();
                                                $ert = $row['contract_ert'];

                                                $interval = $now->diff($end);
                                                $diff = $interval->days;

                                                // $diff;
                        
                                                if ($diff >= $ert) {
                                                    echo '<td class="text-center table-success">
                                                            <span class="text-success fw-bold">' . $diff . ' days remaining </span>
                                                        </td>';
                                                } else {
                                                    echo '
                                                    <td class="text-center table-danger">
                                                        <span class="text-danger fw-bold">' . $diff . ' days remaining before expiring </span>
                                                    </td>';

                                                }

                                            }
                                        }
                                        ?>
                                    </span>
                                    <?php break;
                                case PSC_SHORT: ?>
                                    <!-- Code for PSC_SHORT -->
                                    <!-- Code for EMP_CON -->
                                    <span>
                                        <?php
                                        $getFromContractType = (new ContractTypeController)->getContractTypeByDepartment($contractType);

                                        foreach ($getFromContractType as $row) {
                                            if ($contractType === $row['contract_type']) {
                                                $end = new DateTime($contract['contract_end']);
                                                $now = new DateTime();
                                                $ert = $row['contract_ert'];

                                                $interval = $now->diff($end);
                                                $diff = $interval->days;

                                                // $diff;
                        
                                                if ($diff >= $ert) {
                                                    echo '<td class="text-center table-success">
                                                            <span class="text-success fw-bold">' . $diff . ' days remaining </span>
                                                        </td>';
                                                } else {
                                                    echo '
                                                    <td class="text-center table-danger">
                                                        <span class="text-danger fw-bold">' . $diff . ' days remaining before expiring </span>
                                                    </td>';

                                                }

                                            }
                                        }
                                        ?>
                                    </span>
                                    <?php break;
                                case TEMP_LIGHTING: ?>
                                    <!-- Code for PSC_SHORT -->
                                    <!-- Code for EMP_CON -->
                                    <span>
                                        <?php
                                        $getFromContractType = (new ContractTypeController)->getContractTypeByDepartment($contractType);

                                        foreach ($getFromContractType as $row) {
                                            if ($contractType === $row['contract_type']) {
                                                $end = new DateTime($contract['contract_end']);
                                                $now = new DateTime();
                                                $ert = $row['contract_ert'];

                                                $interval = $now->diff($end);
                                                $diff = $interval->days;

                                                // $diff;
                        
                                                if ($diff >= $ert) {
                                                    echo '<td class="text-center table-success">
                                                            <span class="text-success fw-bold">' . $diff . ' days remaining </span>
                                                        </td>';
                                                } else {
                                                    echo '
                                                    <td class="text-center table-danger">
                                                        <span class="text-danger fw-bold">' . $diff . ' days remaining before expiring </span>
                                                    </td>';

                                                }

                                            }
                                        }
                                        ?>
                                    </span>
                                    <?php break;
                                case TRANS_RENT: ?>
                                    <!-- Code for PSC_SHORT -->
                                    <!-- Code for EMP_CON -->
                                    <span>
                                        <?php
                                        $getFromContractType = (new ContractTypeController)->getContractTypeByDepartment($contractType);

                                        foreach ($getFromContractType as $row) {
                                            if ($contractType === $row['contract_type']) {
                                                $end = new DateTime($contract['contract_end']);
                                                $now = new DateTime();
                                                $ert = $row['contract_ert'];

                                                $interval = $now->diff($end);
                                                $diff = $interval->days;

                                                // $diff;
                        
                                                if ($diff >= $ert) {
                                                    echo '<td class="text-center table-success fw-bold">
                                                            <span class="text-success fw-bold">' . $diff . ' days remaining </span>
                                                        </td>';
                                                } else {
                                                    echo '
                                                    <td class="text-center table-danger">
                                                        <span class="text-danger fw-bold">' . $diff . ' days remaining before expiring </span>
                                                    </td>';

                                                }

                                            }
                                        }
                                        ?>
                                    </span>
                                    <?php break;
                                default: ?>
                                    <!-- Code if no match -->
                                    <p>Unknown Contract Type</p>
                            <?php endswitch; ?>

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
</script>