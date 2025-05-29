<?php

use App\Controllers\ContractTypeController;
use App\Controllers\DepartmentController;
use App\Controllers\UserController;

$department = $_SESSION['department'] ?? null;
$departments = (new DepartmentController)->getAllDepartments();
$getUserInfo = (new UserController)->getUserByDept($department);

?>

<!-- Modal -->
<div class="modal fade" id="temporaryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Temporary Lighting Contract</h5>
            </div>
            <div class="modal-body">
                <form action="contracts/rentals.php" method="post" enctype="multipart/form-data">
                      <div class="p-2 col-md-12">
                                <input type="hidden" class="form-control" name="contract_type"
                                    value="<?= TEMP_LIGHTING ?>" readonly>
                                <div class="d-block gap-2">
                                    <div class="col-md-12 d-flex">
                                        <div class="col-md-5 p-2">
                                            <div>
                                                <lable class="badge text-muted">Contract Name</lable>
                                                <input type="text" class="form-control" name="contract_name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-5 p-2">
                                            <div>
                                                <lable class="badge text-muted">TC No.</lable>
                                                <input type="text" class="form-control" name="TC_no" required>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-4 p-2">
                                        <div>
                                            <lable class="badge text-muted">Date End</lable>
                                            <div class="d-flex">
                                                <i class="fa fa-calendar p-2" style="font-size: 20px;" aria-hidden="true"></i>
                                                <input type="date" id="date_end" class="form-control" name="contract_end" required>
                                            </div>
                                        </div>
                                    </div> -->
                                        <!-- <div class="col-md-4 p-2">
                                        <div>
                                            <lable class="badge text-muted">Date End</lable>
                                            <i class="fa fa-calendar p-2" style="font-size: 20px;" aria-hidden="true"></i>
                                            <input type="date" id="date_end" class="form-control" name="contract_end" required>
                                        </div>
                                    </div> -->
                                    </div>
                                    <div class="col-md-12 d-flex">

                                        <div class="col-md-5 p-2">
                                            <div>
                                                <lable class="badge text-muted">Actual Date Started</lable>
                                                <div class="d-flex">
                                                    <i class="fa fa-calendar p-2" style="font-size: 20px;"
                                                        aria-hidden="true"></i>
                                                    <input type="date" id="date_start" class="form-control"
                                                        name="contract_start" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 p-2">
                                            <div>
                                                <lable class="badge text-muted">Expiration End</lable>
                                                <div class="d-flex">
                                                    <i class="fa fa-calendar p-2" style="font-size: 20px;"
                                                        aria-hidden="true"></i>
                                                    <input type="date" id="date_end" class="form-control"
                                                        name="contract_end" required>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-4 p-2">
                                        <div>
                                            <lable class="badge text-muted">Date End</lable>
                                            <i class="fa fa-calendar p-2" style="font-size: 20px;" aria-hidden="true"></i>
                                            <input type="date" id="date_end" class="form-control" name="contract_end" required>
                                        </div>
                                    </div> -->
                                    </div>
                                    <div class="col-md-12 d-flex">
                                        <div class="col-md-5 p-2">
                                            <div>
                                                <lable class="badge text-muted">Party of Second Part</lable>
                                                <input type="text" class="form-control" name="party_of_second_part"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-5 p-2">
                                            <div>
                                                <lable class="badge text-muted">Contract file</lable>
                                                <input type="file" class="form-control" name="contract_file"
                                                    style="width: 16.7em;" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4 p-2">
                                            <div>
                                                <?php
                                                $userid;
                                                $getUser = (new UserController)->getUserById($userid);

                                                // var_dump($getUser['firstname']);
                                                ?>
                                                <input type="hidden" id="date_start" class="form-control"
                                                    name="uploader_id" value="<?= $userid ?>">
                                                <input type="hidden" id="date_start" class="form-control"
                                                    name="uploader"
                                                    value="<?= $getUser['firstname'] . ' ' . $getUser['middlename'] . ' ' . $getUser['lastname'] ?>">
                                                <input type="hidden" id="date_start" class="form-control"
                                                    name="uploader_dept" value="<?= $department ?>" required>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-4 p-2">
                                        <div>
                                            <lable class="badge text-muted">Date End</lable>
                                            <input type="date" id="date_end" class="form-control" name="contract_end" required>
                                        </div>
                                    </div> -->
                                    </div>

                                </div>
                            </div>

                     </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ISD-MSD MODAL -->
<div class="modal fade" id="temporaryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Temporary Lighting Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="contracts/rentals.php" method="post" enctype="multipart/form-data">
                    <div class="col-md-12 d-flex gap-2 p-3">
                        <div class="col-md-12 p-2">
                            <!-- <div class="mb-3 col-md-5">
                                <label class="badge text-muted">Contract type</label>
                                <select name="contract_type" class="form-select" id="contract_type">
                                    <?php
                                    $contract_types = (new ContractTypeController)->getContractTypes();
                                    ?>
                                    <?php if ($department == 'ISD-MSD'): ?>
                                        <option hidden>Select Contract type</option>
                                        <?php foreach ($contract_types as $contract_type): ?>
                                            <?php if (in_array($contract_type['contract_type'], ['Transformer Rental', 'Temporary Lighting Contract'])): ?>

                                                <option value="<?= $contract_type['contract_type'] ?>">
                                                    <?= $contract_type['contract_type'] ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option disabled>No contract available</option>
                                    <?php endif; ?>
                                </select>
                            </div> -->

                            <!-- Transformer Rental Form -->
                            <!-- <div id="form-transformer-rental" class="contract-form-section" style="display:none;">
                                <hr>
                                <input type="hidden" class="form-control" name="contract_type" value="<?= TRANS_RENT ?>"
                                    readonly>
                                <div class="col-md-12 d-flex p-2 row">
                                    <div class="col-md-3">
                                        <label class="badge text-muted" for="">Date of Contract Creation</label>
                                    </div>
                                </div> -->
                            <!-- Add your form inputs here -->
                            <!-- </div> -->


                            <!----------------------------------------------------  DIVISION BETWEEM CONTRACTS ------------------------------------------------------------------------------->


                            <!-- Temporary Lighting Contract Form -->
                            <div id="form-temporary-lighting-contract" class="contract-form-section col-md-12">
                                <input type="hidden" class="form-control" name="contract_type"
                                    value="<?= TEMP_LIGHTING ?>" readonly>
                                <div class="col-md-12 d-block gap-2">
                                    <div class="col-md-12 d-flex gap-2 row">
                                        <div class="col-md-5 p-2">
                                            <div>
                                                <lable class="badge text-muted">Contract Name</lable>
                                                <input type="text" class="form-control" name="contract_name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-5 p-2">
                                            <div>
                                                <lable class="badge text-muted">TC No.</lable>
                                                <input type="text" class="form-control" name="TC_no" required>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-4 p-2">
                                        <div>
                                            <lable class="badge text-muted">Date End</lable>
                                            <div class="d-flex">
                                                <i class="fa fa-calendar p-2" style="font-size: 20px;" aria-hidden="true"></i>
                                                <input type="date" id="date_end" class="form-control" name="contract_end" required>
                                            </div>
                                        </div>
                                    </div> -->
                                        <!-- <div class="col-md-4 p-2">
                                        <div>
                                            <lable class="badge text-muted">Date End</lable>
                                            <i class="fa fa-calendar p-2" style="font-size: 20px;" aria-hidden="true"></i>
                                            <input type="date" id="date_end" class="form-control" name="contract_end" required>
                                        </div>
                                    </div> -->
                                    </div>
                                    <div class="col-md-12 d-flex gap-2 row">

                                        <div class="col-md-5 p-2">
                                            <div>
                                                <lable class="badge text-muted">Date Start</lable>
                                                <div class="d-flex">
                                                    <i class="fa fa-calendar p-2" style="font-size: 20px;"
                                                        aria-hidden="true"></i>
                                                    <input type="date" id="date_start" class="form-control"
                                                        name="contract_start" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 p-2">
                                            <div>
                                                <lable class="badge text-muted">Date End</lable>
                                                <div class="d-flex">
                                                    <i class="fa fa-calendar p-2" style="font-size: 20px;"
                                                        aria-hidden="true"></i>
                                                    <input type="date" id="date_end" class="form-control"
                                                        name="contract_end" required>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-4 p-2">
                                        <div>
                                            <lable class="badge text-muted">Date End</lable>
                                            <i class="fa fa-calendar p-2" style="font-size: 20px;" aria-hidden="true"></i>
                                            <input type="date" id="date_end" class="form-control" name="contract_end" required>
                                        </div>
                                    </div> -->
                                    </div>
                                    <div class="col-md-12 d-flex gap-4 row">
                                        <div class="col-md-5 p-2">
                                            <div>
                                                <lable class="badge text-muted">Party of Second Part</lable>
                                                <input type="text" class="form-control" name="party_of_second_part"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-5 p-2">
                                            <div>
                                                <lable class="badge text-muted">Contract file</lable>
                                                <input type="file" class="form-control" name="contract_file"
                                                    style="width: 16.7em;" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4 p-2">
                                            <div>
                                                <?php
                                                $userid;
                                                $getUser = (new UserController)->getUserById($userid);

                                                // var_dump($getUser['firstname']);
                                                ?>
                                                <input type="hidden" id="date_start" class="form-control"
                                                    name="uploader_id" value="<?= $userid ?>">
                                                <input type="hidden" id="date_start" class="form-control"
                                                    name="uploader"
                                                    value="<?= $getUser['firstname'] . ' ' . $getUser['middlename'] . ' ' . $getUser['lastname'] ?>">
                                                <input type="hidden" id="date_start" class="form-control"
                                                    name="uploader_dept" value="<?= $department ?>" required>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-4 p-2">
                                        <div>
                                            <lable class="badge text-muted">Date End</lable>
                                            <input type="date" id="date_end" class="form-control" name="contract_end" required>
                                        </div>
                                    </div> -->
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" style="background-color: #118B50;">Save Contract</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- JS to toggle form display -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const contractSelect = document.getElementById("contract_type");
        const formSections = document.querySelectorAll(".contract-form-section");

        function toggleFormSection() {
            const selected = contractSelect.value.toLowerCase().replace(/\s+/g, '-');
            formSections.forEach(section => section.style.display = "none");

            const targetForm = document.getElementById("form-" + selected);
            if (targetForm) {
                targetForm.style.display = "block";
            }
        }

        // Initial check (in case there's a pre-selected option)
        toggleFormSection();

        // Event listener for changes
        contractSelect.addEventListener("change", toggleFormSection);
    });

    document.getElementById('date_start').addEventListener('change', function () {
        const startDate = new Date(this.value);
        if (!isNaN(startDate)) {
            startDate.setDate(startDate.getDate() + 90);
            const year = startDate.getFullYear();
            const month = String(startDate.getMonth() + 1).padStart(2, '0');
            const day = String(startDate.getDate()).padStart(2, '0');
            const formattedDate = `${year}-${month}-${day}`;
            document.getElementById('date_end').value = formattedDate;
        }
    });
</script>