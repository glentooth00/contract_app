<?php

use App\Controllers\ContractTypeController;
use App\Controllers\DepartmentController;
use App\Controllers\UserController;

$department = $_SESSION['department'] ?? null;
$departments = (new DepartmentController)->getAllDepartments();
$getUserInfo = (new UserController)->getUserByDept($department);

?>

<!-- Modal -->
<div class="modal fade" id="temporaryModal" tabindex="-1" role="dialog" aria-labelledby="temporaryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="temporaryModalLabel">Temporary Lighting Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="contracts/rentals.php" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" class="form-control" name="contract_type" value="<?= TEMP_LIGHTING ?>"
                        readonly>

                    <div class="row px-3">
                        <!-- Contract Name -->
                        <div class="col-md-6 p-2">
                            <label class="badge text-muted">Contract Name</label>
                            <input type="text" class="form-control" name="contract_name" required>
                        </div>

                        <!-- TC No. -->
                        <div class="col-md-6 p-2">
                            <label class="badge text-muted">TC No.</label>
                            <input type="text" class="form-control" name="TC_no" required>
                        </div>

                        <div class="col-md-6 p-2">
                            <label class="badge text-muted">Account No.</label>
                            <input type="text" class="form-control" name="account_no" required>
                        </div>

                        <!-- Party of Second Part -->
                        <div class="col-md-6 p-2">
                            <label class="badge text-muted">Party of Second Part</label>
                            <input type="text" class="form-control" name="party_of_second_part" required>
                        </div>

                        <!-- Actual Date Started -->
                        <div class="col-md-6 p-2">
                            <label class="badge text-muted">Actual Date Started</label>
                            <div class="d-flex">
                                <i class="fa fa-calendar p-2" style="font-size: 20px;" aria-hidden="true"></i>
                                <input type="date" class="form-control" name="contract_start" required>
                            </div>
                        </div>

                        <!-- Expiration End -->
                        <div class="col-md-6 p-2">
                            <label class="badge text-muted">Expiration End</label>
                            <div class="d-flex">
                                <i class="fa fa-calendar p-2" style="font-size: 20px;" aria-hidden="true"></i>
                                <input type="date" class="form-control" name="contract_end" required>
                            </div>
                        </div>



                        <!-- Contract File -->
                        <div class="col-md-6 p-2">
                            <label class="badge text-muted">Contract File</label>
                            <input type="file" class="form-control" name="contract_file" required>
                        </div>

                        <!-- Uploader Info -->
                        <?php
                        $userid;
                        $getUser = (new UserController)->getUserById($userid);
                        ?>
                        <input type="hidden" name="uploader_id" value="<?= $userid ?>">
                        <input type="hidden" name="uploader"
                            value="<?= $getUser['firstname'] . ' ' . $getUser['middlename'] . ' ' . $getUser['lastname'] ?>">
                        <input type="hidden" name="uploader_dept" value="<?= $department ?>">

                        <!-- Divider -->
                        <div class="col-md-12 mt-3">
                            <hr>
                        </div>

                        <!-- Address Field -->
                        <div class="col-md-12 p-2">
                            <label class="badge text-muted">Address</label>
                            <input type="text" class="form-control" name="address" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>