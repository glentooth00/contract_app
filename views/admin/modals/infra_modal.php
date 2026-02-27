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

<!-- Infrastructure Contract Modal -->
<div class="modal fade" id="infraModal" tabindex="-1" aria-labelledby="infraModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header" style="background-color:#328E6E;">
                <h5 class="modal-title text-white mb-0" id="infraModalLabel" style="font-size:20px;">
                    Infrastructure Contract
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="contractForm" action="contracts/save_infra.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="contract_type" value="<?= INFRA ?>">
                    <input type="hidden" name="uploader_department" value="<?= $department ?>">
                    <input type="hidden" name="uploader" value="<?= $name ?>">
                    <input type="hidden" name="uploader_id" value="<?= $id ?>">

                    <!-- Row 1: Contract File & Name -->
                    <div class="row g-3 p-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contract File</label>
                            <input type="file" name="contract_file" class="form-control">
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label">Contract Name</label>
                            <input type="text" class="form-control" name="contract_name" placeholder="">
                        </div>
                    </div>

                    <!-- Row 2: Cost, Procurement, Supplier -->
                    <div class="row g-3 p-3">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Total Contract Cost</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-peso-sign"></i></span>
                                <input type="text" class="form-control contract-cost" id="total_contract_cost"
                                    name="contractPrice" placeholder="0.00">
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Mode of Procurement</label>
                            <select class="form-select" name="procurementMode">
                                <option value="" hidden>Select mode</option>
                                <?php foreach ($procurementModes as $procurementMode): ?>
                                    <option value="<?= $procurementMode['procMode'] ?>"><?= $procurementMode['procMode'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <?php if ($department === 'BAC'): ?>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Assign To</label>
                                <select class="form-select" name="department_assigned" required>
                                    <option>--Select Department--</option>
                                    <option value="ISD">ISD</option>
                                    <option value="CITET">CITET</option>
                                    <option value="FSD">FSD</option>
                                    <option value="TSD">TSD</option>
                                    <option value="IASD">IASD</option>
                                    <option value="AOSD">AOSD</option>
                                </select>
                            </div>
                        <?php endif; ?>

                        <div class="col-md-4 mb-3" id="supplier_field" style="display: none;">
                            <label class="form-label">Supplier</label>
                            <input type="text" class="form-control" name="supplier" placeholder="">
                        </div>
                    </div>

                    <!-- Row 3: Dates & Departments -->
                    <div class="row g-3 p-3">
                        <?php if ($department === 'GM'): ?>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Implementing Department</label>
                                <select name="implementing_dept" class="form-select">
                                    <option hidden>Select Department</option>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?= $dept['department_name'] ?>"><?= $dept['department_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Start Date</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                <input type="date" class="form-control" id="start" name="contract_start">
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">End Date</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                <input type="date" class="form-control" id="end" name="contract_end">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save Contract</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Material-Inspired Styles -->
<style>
    .modal-content {
        border-radius: 12px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
    }

    .form-label {
        font-weight: 500;
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 6px;
    }

    .form-control,
    .form-select {
        border-radius: 6px;
        padding: 8px 10px;
        font-size: 0.875rem;
        border: 1px solid #ddd;
        box-shadow: none;
        transition: all 0.2s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #3f51b5;
        box-shadow: 0 0 0 0.2rem rgba(63, 81, 181, 0.15);
        outline: none;
    }

    .input-group-text {
        background-color: #f5f5f5;
        border-radius: 6px 0 0 6px;
        border: 1px solid #ddd;
    }

    .btn {
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.875rem;
        padding: 6px 14px;
    }

    .btn:hover {
        opacity: 0.9;
    }
</style>

<!-- JS: Cost formatting and auto-end date -->
<script>
    let duration = 30;

    document.getElementById('total_contract_cost').addEventListener('input', function (e) {
        let value = e.target.value;
        value = value.replace(/[^\d.]/g, '');
        e.target.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    });

    document.getElementById('start').addEventListener('change', function () {
        const startDate = new Date(this.value);
        if (!isNaN(startDate) && duration > 0) {
            startDate.setDate(startDate.getDate() + duration);
            const year = startDate.getFullYear();
            const month = String(startDate.getMonth() + 1).padStart(2, '0');
            const day = String(startDate.getDate()).padStart(2, '0');
            document.getElementById('end').value = `${year}-${month}-${day}`;
        }
    });

    // Optional: supplier field toggle for Goods Contract
    document.getElementById('contract_type')?.addEventListener('change', function () {
        const supplierField = document.getElementById('supplier_field');
        supplierField.style.display = this.value === 'Goods Contract' ? 'block' : 'none';
    });
</script>