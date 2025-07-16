<?php

use App\Controllers\ContractTypeController;
use App\Controllers\DepartmentController;
use App\Controllers\UserController;
use App\Controllers\ProcurementController;

$department = $_SESSION['department'] ?? null;
$departments = (new DepartmentController)->getAllDepartments();

$getUserInfo = (new UserController)->getUserByDept($department);

$userid = $_SESSION['id'];

$user_department = (new UserController)->getUserById($userid);

$department = $user_department['department'];

$departments = (new DepartmentController)->getAllDepartments();

$id = $user_department['id'];

$get_contract_types = (new ContractTypeController)->getContractType($department);

$name = $user_department['firstname'] . ' ' . $user_department['middlename'] . ' ' . $user_department['lastname'];

$procurementModes = (new ProcurementController)->getAllProcMode();

?>

<!-- Modal -->
<div class="modal fade" id="allModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                            type="button" role="tab" aria-controls="home" aria-selected="true">Employment
                            Contract</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                            type="button" role="tab" aria-controls="profile" aria-selected="false">BAC
                            Contracts</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                            type="button" role="tab" aria-controls="contact" aria-selected="false">Contact</button>
                    </li>
                </ul>
                <div class="tab-content gap-3" id="myTabContent">

                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <form action="contracts/save_contract.php" method="post" enctype="multipart/form-data">
                            <div class="col-md-12 d-flex gap-2 p-3">
                                <div class="col-md-6 p-2">
                                    <div class="mb-3">
                                        <label class="badge text-muted">Contract</label>
                                        <input type="file" name="contract_file" class="form-control" required>

                                    </div>
                                    <div class="mb-3">
                                        <label class="badge text-muted">Starting Date</label>
                                        <input type="date" class="form-control" name="contract_start" id="floatingInput"
                                            placeholder="name@example.com" required>

                                    </div>

                                    <div class="mb-3">

                                        <label class="badge text-muted">Contract Type</label>
                                        <select class="form-select form-select-md mb-3" name="contract_type"
                                            aria-label=".form-select-lg example">
                                            <option selected hidden>Select contract type</option>
                                            <?php
                                            $contract_types = (new ContractTypeController)->getContractTypes();
                                            ?>
                                            <?php if ($department == 'ISD-HRAD' || $department == 'IASD'): ?>
                                                <?php foreach ($contract_types as $contract_type): ?>

                                                    <?php if ($contract_type['contract_type'] === 'Employment Contract' | $contract_type['contract_type'] === 'Rental Contract'): ?>
                                                        <option value="<?= $contract_type['contract_type'] ?>">
                                                            <?= $contract_type['contract_type'] ?>
                                                        </option>
                                                    <?php else: ?>

                                                    <?php endif; ?>

                                                <?php endforeach; ?>

                                            <?php else: ?>
                                                <option disabled> no contract available</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 p-2">
                                    <div class="mb-3">
                                        <label class="badge text-muted">Contract Name</label>
                                        <input type="text" class="form-control" name="contract_name" id="floatingInput"
                                            placeholder="" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="badge text-muted">End Date</label>
                                        <input type="date" class="form-control" name="contract_end" id="floatingInput"
                                            placeholder="" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="badge text-muted">Department Assigned</label>
                                        <select name="department_assigned" class="form-select" id="">
                                            <?php foreach ($departments as $department): ?>
                                                <option hidden>Select Department</option>
                                                <option value="<?= $department['department_name'] ?>">
                                                    <?= $department['department_name'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div>
                                        <input type="hidden" name="uploader_id" value="<?= $getUserInfo['id'] ?>">
                                        <input type="hidden" name="uploader_department"
                                            value="<?= $getUserInfo['department'] ?>">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success float-end">Submit</button>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
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
                                    <label class="badge text-muted">Contract Type</label>
                                    <select class="form-select" name="contract_type" id="contract_type">
                                        <option value="" hidden>Select Type</option>
                                        <?php foreach ($get_contract_types as $contract_type): ?>
                                            <option value="<?= $contract_type['contract_type'] ?>">
                                                <?= $contract_type['contract_type'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Second Row -->
                            <div class="row p-3">
                                <div class="col-md-4 mb-3">
                                    <label class="badge text-muted">Total Contract Cost</label>
                                    <input type="text" class="form-control" name="contractPrice"
                                        id="total_contract_cost" placeholder="₱ 0.00">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="badge text-muted">Mode of Procurement</label>
                                    <select class="form-select" name="procurementMode">
                                        <option value="" hidden>Select mode</option>
                                        <?php foreach ($procurementModes as $procurementMode): ?>
                                            <option value="<?= $procurementMode['procMode'] ?>">
                                                <?= $procurementMode['procMode'] ?>
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
                                    <input type="date" id="startDate" class="form-control" name="contract_start">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="badge text-muted">End Date</label>
                                    <input type="date" id="endDate" class="form-control" name="contract_end">
                                </div>

                                <!-- Hidden Inputs -->
                                <input type="hidden" name="uploader_department" value="<?= $department ?>">
                                <input type="hidden" name="uploader" value="<?= $name ?>">
                                <input type="hidden" name="uploader_id" value="<?= $id ?>">
                            </div>
                            <button type="submit" class="btn btn-success float-end">Submit</button>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">.
                        <form>

                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>