<?php
session_start();
require_once __DIR__ . '../../../../vendor/autoload.php'; // corrected path

use App\Controllers\ContractController;
echo $department = $_SESSION['department'];
$contracts = (new ContractController)->getContractsByCITETDepartment($department);

// var_dump($contracts);
include_once '../../../views/layouts/includes/header.php';
?>

<div class="container mt-4">
    <div class="row mb-3">
        <!-- Search bar for DataTables -->
        <div class="col-md-6 d-flex align-items-center">
            <label for="search" class="mr-3">Search: </label>
            <input type="text" id="search" class="form-control" placeholder="Search">
        </div>

        <!-- Filter Dropdown for Contract Type -->
        <div class="col-md-6">
            <label for="contractFilter">Filter by Contract Type:</label>
            <select id="contractFilter" class="form-control">
                <option value="">All</option>
                <option value="Transformer Rental Contract">Transformer Rental Contract</option>
                <option value="Employment Contract">Employment Contract</option>
                <option value="Temporary Lighting Contract">Temporary Lighting Contract</option>
                <option value="Rental Contract">Rental Contract</option>
                <option value="Infrastructure Contract">Infrastructure Contract</option>
                <option value="Goods Contract">Goods Contract</option>
                <option value="Service and Consultancy Contract">Service and Consultancy Contract</option>
                <option value="Power Suppliers Contract (LONG TERM)">Power Suppliers Contract (LONG TERM)</option>
                <option value="Power Suppliers Contract (SHORT TERM)">Power Suppliers Contract (SHORT TERM)</option>
            </select>
        </div>
    </div>

    <table id="example" class="table display">
        <thead>
            <tr>
                <th>Name</th>
                <th>Contract type</th>
                <th>Start</th>
                <th>End</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contracts as $contract): ?>
                <tr>
                    <td><?php echo htmlspecialchars($contract['contract_name']); ?></td>
                    <td><?php echo htmlspecialchars($contract['contract_type']); ?></td>
                    <td><?php echo htmlspecialchars($contract['contract_start']); ?></td>
                    <td><?php echo htmlspecialchars($contract['contract_end']); ?></td>
                    <td><?php echo htmlspecialchars($contract['contract_status']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<!-- Include DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        var table = $('#example').DataTable({
            // Optional: DataTables customization options can go here
        });

        // Global search (searching across all columns)
        $('#search').on('keyup', function () {
            table.search(this.value).draw(); // Global search across all columns
        });

        // Filter the table based on contract type
        $('#contractFilter').on('change', function () {
            var filterValue = $(this).val();
            table.column(1).search(filterValue).draw(); // Column 1 is for "Contract Type"
        });
    });
</script>