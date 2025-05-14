<?php

use App\Controllers\ProcurementController;
use App\Controllers\UserController;
use App\Controllers\ContractTypeController;

$userid = $_SESSION['id'];

$user_department = (new UserController)->getUserById($userid);

$department = $user_department['department'];

$id = $user_department['id'];

$get_contract_types = ( new ContractTypeController )->getContractType($department);

$name = $user_department['firstname'].' '. $user_department['middlename'].' '. $user_department['lastname'];

$procurementModes = ( new ProcurementController )->getAllProcMode();

?>

<!---- CITETD MODAL ---->
<div class="modal fade" id="bacModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Contract</h5>
                
            </div>
            <div class="modal-body">
                <form action="contracts/save_contract.php" method="post" enctype="multipart/form-data">
                    <div class="col-md-12 d-flex gap-2 p-3">
                        <div class="col-md-6 p-2">
                            <div class="mb-3">
                                <label class="badge text-muted">Contract file</label>
                                <input type="file" name="contract_file" class="form-control">

                            </div>
                            <div class="mb-3">
                                <label class="badge text-muted">Total Contract Cost</label>
                                <input type="text" class="form-control" name="contractPrice" id="total_contract_cost" placeholder="₱0.00">
                            </div>
                            <div class="mb-3">
                                <label class="badge text-muted">Starting Date</label>
                                <input type="date" class="form-control" name="contract_start" id="floatingInput"
                                    placeholder="name@example.com">
                            </div>

                            <div class="mb-3">
                                <label class="badge text-muted">Contract type</label>
                                <select class="form-select form-select-md mb-3" name="contract_type">
                                <option value="" hidden>Select Contract type</option>
                                    <?php foreach($get_contract_types as $contract_type): ?>
                                        <option value="<?= $contract_type['contract_type'] ?>"><?= $contract_type['contract_type'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>

                        <div class="col-md-6 p-2">
                            <div class="mb-3">
                                <label class="badge text-muted">Contract Name</label>
                                <input type="text" class="form-control" name="contract_name" id="floatingInput"
                                    placeholder="">
                            </div>
                             <div class="mb-3">
                                <label class="badge text-muted">Supplier</label>
                                <input type="text" class="form-control" name="supplier" id="floatingInput"
                                    placeholder="">
                            </div>
                            <div class="mb-3">
                                <label class="badge text-muted">End Date</label>
                                <input type="date" class="form-control" name="contract_end" id="floatingInput"
                                    placeholder="">
                            </div>
                            
                              <div class="mb-3">
                                <label class="badge text-muted">Mode of Procurement</label>
                                <select class="form-select form-select-md mb-3" name="contract_type">
                                <option value="" hidden>Select Procurement mode</option>
                                    <?php foreach($procurementModes as $procurementMode): ?>
                                        <option value="<?= $procurementMode['procMode'] ?>"><?= $procurementMode['procMode'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <input type="hidden" class="form-control" name="uploader_department"
                                    value="<?= $department ?>" id="floatingInput" placeholder="">
                            </div>
                            <div class="mb-3">
                                <input type="hidden" class="form-control" name="uploader"
                                    value="<?= $name ?>" id="floatingInput" placeholder="">
                            </div>
                            <div class="mb-3">
                                <input type="hidden" class="form-control" name="uploader_id"
                                    value="<?= $id ?>" id="floatingInput" placeholder="">
                            </div>

                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                <button type="submit" class="btn btn-primary" style="background-color: #118B50;">Save Contract</button>
            </div>
            </form>
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
    let formattedValue = '₱' + value.replace(/\B(?=(\d{3})+(?!\d))/g, ','); // Adds commas

    e.target.value = formattedValue;
});

</script>