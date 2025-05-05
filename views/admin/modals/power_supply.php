<?php

use App\Controllers\UserController;
use App\Controllers\ContractTypeController;

$userid = $_SESSION['id'];

$user_department = (new UserController)->getUserById($userid);

$department = $user_department['department'];

$get_contract_types = ( new ContractTypeController )->getContractType($department);

$name = $user_department['firstname'].' '. $user_department['middlename'].' '. $user_department['lastname'];

var_dump($get_contract_types);


?>

<!---- CITETD MODAL ---->
<div class="modal fade" id="powerSupplyModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Power Suppliers Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                <label class="badge text-muted">Starting Date</label>
                                <input type="date" class="form-control" name="contract_start" id="floatingInput"
                                    placeholder="name@example.com">

                            </div>

                            <div class="mb-3">
                                <label class="badge text-muted">Power Supply Contract type</label>
                                <select class="form-select form-select-md mb-3" name="contract_type">
                                <option value="" hidden>Select Power Supply contract type</option>
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
                                <label class="badge text-muted">End Date</label>
                                <input type="date" class="form-control" name="contract_end" id="floatingInput"
                                    placeholder="">
                            </div>
                            <div class="mb-3">
                                <input type="hidden" class="form-control" name="uploader_department"
                                    value="<?= $department ?>" id="floatingInput" placeholder="">
                            </div>
                            <div class="mb-3">
                                <input type="hidden" class="form-control" name="uploader"
                                    value="<?= $name ?>" id="floatingInput" placeholder="">
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