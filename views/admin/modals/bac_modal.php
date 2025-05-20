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

<!---- CITETD MODAL ---->
<div class="modal fade" id="bacModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Contract</h5>
            </div>
            <div class="modal-body">
                <form action="procurement/save_contract.php" method="POST" enctype="multipart/form-data">

                    <!-- First Row -->
                    <div class="row p-3">
                        <div class="col-md-4 mb-3">
                            <label class="badge text-muted">Contract File</label>
                            <input type="file" name="contract_file" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="badge text-muted">Contract Name</label>
                            <input type="text" class="form-control" name="contract_name" placeholder="">
                        </div>
                        <div class="col-md-4 mb-3">
                            <?php if ($department === BAC): ?>

                                <label class="badge text-muted">Contract Type</label>
                                <select class="form-select" name="contract_type" id="contract_type">
                                    <option value="" hidden>Select Type</option>
                                    <?php foreach ($get_contract_types as $contract_type): ?>
                                        <option value="<?= $contract_type['contract_type'] ?>">
                                            <?= $contract_type['contract_type'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                            <?php else: ?>

                                <label class="badge text-muted">Contract Type</label>
                                <select class="form-select" name="contract_type" id="contract_type">
                                    <option value="" hidden>Select Type</option>
                                    <option value="Infrastructure Contract">Infrastructure Contract</option>
                                    <option value="Goods Contract">Goods Contract</option>
                                    <option value="Service and Consultancy Contract">Service and Consultancy Contract
                                    </option>
                                </select>

                            <?php endif; ?>

                        </div>
                    </div>

                    <!-- Second Row -->
                    <div class="row p-3">
                        <div class="col-md-4 mb-3">
                            <label class="badge text-muted">Total Contract Cost</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-peso-sign"></i></span>
                                <input type="text" class="form-control" name="contractPrice" id="total_contract_cost"
                                    placeholder="0.00">
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="badge text-muted">Mode of Procurement</label>
                            <select class="form-select" name="procurementMode">
                                <option value="" hidden>Select mode</option>
                                <?php foreach ($procurementModes as $procurementMode): ?>
                                    <option value="<?= $procurementMode['procMode'] ?>"><?= $procurementMode['procMode'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3" id="supplier_field" style="display: none;">
                            <label class="badge text-muted">Supplier</label>
                            <input type="text" class="form-control" name="supplier" placeholder="">
                        </div>


                    </div>

                    <div class="row p-3">
                        <div class="col-md-4 mb-3">
                            <label class="badge text-muted">Implementing Department</label>
                            <select name="department_assigned" class="form-select">
                                <option hidden>Select Department</option>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?= $dept['department_name'] ?>">
                                        <?= $dept['department_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="badge text-muted">Start Date</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa fa-calendar" style="font-size: 18px;" aria-hidden="true"></i>
                                </span>
                                <input type="date" class="form-control" name="contract_start">
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="badge text-muted">End Date</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa fa-calendar" style="font-size: 18px;" aria-hidden="true"></i>
                                </span>
                                <input type="date" class="form-control" name="contract_end">
                            </div>
                        </div>


                        <!-- Hidden Inputs -->
                        <input type="hidden" name="uploader_department" value="<?= $department ?>">
                        <input type="hidden" name="uploader" value="<?= $name ?>">
                        <input type="text" name="uploader_id" value="<?= $id ?>">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" style="background-color: #118B50;">Save
                            Contract</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<style>
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
        appearance: none;
    }
</style>
<script>
    document.getElementById('total_contract_cost').addEventListener('input', function (e) {
        let value = e.target.value;

        // Remove non-numeric characters (except for the decimal point)
        value = value.replace(/[^\d.]/g, '');

        // Add the peso symbol (₱) at the start
        let formattedValue = '' + value.replace(/\B(?=(\d{3})+(?!\d))/g, ','); // Adds commas

        e.target.value = formattedValue;
    });

    document.getElementById('contract_type').addEventListener('change', function () {
        var selected = this.value;
        var supplierField = document.getElementById('supplier_field');

        if (selected === 'Goods Contract') {
            supplierField.style.display = 'block';
        } else {
            supplierField.style.display = 'none';
        }
    });

</script>