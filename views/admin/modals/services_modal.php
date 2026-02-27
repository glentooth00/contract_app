<?php
use App\Controllers\DepartmentController;
use App\Controllers\ProcurementController;
use App\Controllers\UserController;
use App\Controllers\ContractTypeController;

$userid = $_SESSION['id'];
$user_department = (new UserController)->getUserById($userid);
$department = $user_department['department'];
$departments = (new DepartmentController)->getAllDepartments();
$id = $user_department['id'];
$get_contract_types = (new ContractTypeController)->getContractType($department);
$name = $user_department['firstname'] . ' ' . $user_department['middlename'] . ' ' . $user_department['lastname'];
$procurementModes = (new ProcurementController)->getAllProcMode();
?>

<!--Services & Consultancy Contract Modal -->
<div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header" style="background-color:#123458;">
                <h5 class="modal-title text-white mb-0" id="serviceModalLabel" style="font-size:20px;">Services & Consultancy Contract</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="contractForm" action="contracts/save_contract.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="contract_type" value="<?= SACC ?>">
                    <input type="hidden" name="uploader_department" value="<?= $department ?>">
                    <input type="hidden" name="uploader" value="<?= $name ?>">
                    <input type="hidden" name="uploader_id" value="<?= $id ?>">

                    <!-- First Row -->
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label class="form-label">Contract File</label>
                            <input type="file" name="contract_file" class="form-control form-control-sm">
                        </div>

                        <div class="col-md-5 mb-3">
                            <label class="form-label">Contract Name</label>
                            <input type="text" class="form-control form-control-sm" name="contract_name" placeholder="">
                        </div>

                        <div class="col-md-4 mb-3" id="supplier_field" style="display: none;">
                            <label class="form-label">Supplier</label>
                            <input type="text" class="form-control form-control-sm" name="supplier" placeholder="">
                        </div>
                    </div>

                    <!-- Second Row -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Total Contract Cost</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="fa-solid fa-peso-sign"></i></span>
                                <input type="text" class="form-control contract-cost" name="contractPrice" placeholder="0.00">
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Mode of Procurement</label>
                            <select class="form-select form-select-sm" name="procurementMode">
                                <option value="" hidden>Select mode</option>
                                <?php foreach ($procurementModes as $procurementMode): ?>
                                        <option value="<?= $procurementMode['procMode'] ?>"><?= $procurementMode['procMode'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Third Row -->
                    <div class="row">
                        <?php if ($department === GM): ?>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Implementing Department</label>
                                    <select name="implementing_dept" class="form-select form-select-sm">
                                        <option hidden>Select Department</option>
                                        <?php foreach ($departments as $dept): ?>
                                                <option value="<?= $dept['department_name'] ?>"><?= $dept['department_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                        <?php endif; ?>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control form-control-sm" id="start" name="contract_start">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control form-control-sm" id="end" name="contract_end">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn" style="background-color: #118B50; color:#fff;">Save Contract</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Material-inspired flat style -->
<style>
    .modal-content {
        border-radius: 12px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.06);
    }

    .form-label {
        font-weight: 500;
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 6px;
    }

    .form-control, .form-select {
        border-radius: 6px;
        padding: 6px 10px;
        font-size: 0.875rem;
        box-shadow: none;
        border: 1px solid #ddd;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #3f51b5;
        box-shadow: 0 0 0 0.2rem rgba(63,81,181,0.15);
    }

    .input-group-text {
        background-color: #f1f1f1;
        border-radius: 6px 0 0 6px;
        border: 1px solid #ddd;
    }

    .btn {
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.875rem;
        padding: 6px 14px;
        transition: all 0.2s ease;
    }

    .btn:hover {
        opacity: 0.9;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Format contract cost input
        document.querySelectorAll('.contract-cost').forEach(input => {
            input.addEventListener('input', function() {
                let value = this.value.replace(/[^\d.]/g,'');
                this.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            });
        });
    });
</script>