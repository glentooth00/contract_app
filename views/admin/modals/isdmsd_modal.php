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
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="temporaryModalLabel">Temporary Lighting Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="contracts/rentals.php" method="post" enctype="multipart/form-data">
                <div class="modal-body px-3">
                    <input type="hidden" class="form-control" name="contract_type" value="<?= TEMP_LIGHTING ?>"
                        readonly>

                    <div class="row g-2">
                        <!-- Customer Name -->
                        <div class="col-md-6">
                            <label class="badge text-muted">Customer Name</label>
                            <input type="text" class="form-control flat-input" name="contract_name" required>
                        </div>

                        <!-- TC No. -->
                        <div class="col-md-6">
                            <label class="badge text-muted">TC No.</label>
                            <input type="text" class="form-control flat-input" name="TC_no" required>
                        </div>

                        <!-- Account No. -->
                        <div class="col-md-6">
                            <label class="badge text-muted">Account No.</label>
                            <input type="text" class="form-control flat-input" name="account_no" required>
                        </div>

                        <!-- Party of Second Part -->
                        <div class="col-md-6">
                            <label class="badge text-muted">Party of Second Part</label>
                            <input type="text" class="form-control flat-input" name="party_of_second_part" required>
                        </div>

                        <!-- Actual Date Started -->
                        <div class="col-md-6">
                            <label class="badge text-muted">Actual Date Started</label>
                            <div class="d-flex align-items-center">
                                <i class="fa fa-calendar me-2" style="font-size:18px;"></i>
                                <input type="date" class="form-control flat-input" name="contract_start" required>
                            </div>
                        </div>

                        <!-- Expiration End -->
                        <div class="col-md-6">
                            <label class="badge text-muted">Expiration End</label>
                            <div class="d-flex align-items-center">
                                <i class="fa fa-calendar me-2" style="font-size:18px;"></i>
                                <input type="date" class="form-control flat-input" name="contract_end" required>
                            </div>
                        </div>

                        <!-- Contract File -->
                        <div class="col-md-6">
                            <label class="badge text-muted">Contract File</label>
                            <input type="file" class="form-control flat-input" name="contract_file" required>
                        </div>

                        <!-- Assign Department -->
                        <?php if ($department === 'BAC'): ?>
                            <div class="col-md-6">
                                <label class="badge text-muted">Assign To:</label>
                                <select class="form-select flat-input" name="department_assigned" required>
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
                        <div class="col-12 mt-2">
                            <hr>
                        </div>

                        <!-- Address Field -->
                        <div class="col-12">
                            <label class="badge text-muted">Address</label>
                            <input type="text" class="form-control flat-input" name="address" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-flat-success">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Flat Material-inspired input */
    .flat-input {
        font-size: 12px;
        padding: 6px 10px;
        border-radius: 4px;
        border: 1px solid #ccc;
        transition: all 0.2s ease;
    }

    .flat-input:focus {
        outline: none;
        border-color: #3f51b5;
        box-shadow: 0 0 0 2px rgba(63, 81, 181, 0.2);
    }

    /* Flat Material-inspired button */
    .btn-flat-success {
        font-size: 11px;
        font-weight: 600;
        background-color: #118B50;
        color: #fff;
        padding: 4px 10px;
        border-radius: 4px;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-flat-success:hover {
        background-color: #0f7d46;
    }

    .btn-flat-success:active {
        transform: translateY(1px);
    }
</style>