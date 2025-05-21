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
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title mb-0" id="exampleModalLabel">Create Contract</h5>
                <span id="refreshBtn"><img id="refresh" width="22px" src="../../../public/images/refresh.svg"></span>
            </div>

            <div class="modal-body">
                <form id="contractForm" action="procurement/save_contract.php" method="POST"
                    enctype="multipart/form-data">
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
                                <input type="date" class="form-control" id="start" name="contract_start">
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="badge text-muted">End Date</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa fa-calendar" style="font-size: 18px;" aria-hidden="true"></i>
                                </span>
                                <input type="date" class="form-control" id="end" name="contract_end">
                            </div>
                        </div>


                        <!-- Hidden Inputs -->
                        <input type="hidden" name="uploader_department" value="<?= $department ?>">
                        <input type="hidden" name="uploader" value="<?= $name ?>">
                        <input type="hidden" name="uploader_id" value="<?= $id ?>">
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

    #refreshBtn:hover {
        cursor: pointer;
        border-radius: 25px;
        box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
    }
</style>
<script>
    let duration = 0;

    document.getElementById('total_contract_cost').addEventListener('input', function (e) {
        let value = e.target.value;

        // Remove non-numeric characters (except for the decimal point)
        value = value.replace(/[^\d.]/g, '');

        // Add commas for thousands separator
        let formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

        e.target.value = formattedValue;
    });

    document.getElementById('contract_type').addEventListener('change', function () {
        const selected = this.value;
        const supplierField = document.getElementById('supplier_field');

        // Show/hide supplier field only for Goods Contract
        supplierField.style.display = (selected === 'Goods Contract') ? 'block' : 'none';

        // Set duration based on selected contract type
        switch (selected) {
            case 'Goods Contract':
                duration = 15;
                break;
            case 'Infrastructure Contract':
            case 'Service and Consultancy Contract':
                duration = 30;
                break;
            default:
                duration = 0;
        }

        console.log("Duration set to:", duration);
    });


    document.getElementById('start').addEventListener('change', function () {
        const startDate = new Date(this.value);

        console.log("Duration on start change:", duration);

        if (!isNaN(startDate) && duration > 0) {
            startDate.setDate(startDate.getDate() + duration);
            const year = startDate.getFullYear();
            const month = String(startDate.getMonth() + 1).padStart(2, '0');
            const day = String(startDate.getDate()).padStart(2, '0');
            const formattedDate = `${year}-${month}-${day}`;
            document.getElementById('end').value = formattedDate;
        }
    });

    // Trigger contract_type change event on page load to initialize duration correctly
    window.addEventListener('DOMContentLoaded', function () {
        document.getElementById('contract_type').dispatchEvent(new Event('change'));
    });

    document.getElementById('refreshBtn').addEventListener('click', function () {
        const form = document.getElementById('contractForm');
        form.reset(); // Reset all form inputs
        document.getElementById('supplier_field').style.display = 'none'; // Hide supplier field again
        console.log("Form has been reset");
    });
</script>