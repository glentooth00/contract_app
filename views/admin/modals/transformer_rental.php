<?php
use App\Controllers\ContractTypeController;
use App\Controllers\DepartmentController;
use App\Controllers\UserController;

$department = $_SESSION['department'] ?? null;
$departments = (new DepartmentController)->getAllDepartments();
$getUserInfo = (new UserController)->getUserByDept($department);
$userid = $_SESSION['id'];
$getUser = (new UserController)->getUserById($userid);
?>

<!-- Transformer Rental Modal -->
<div class="modal fade" id="transformerModal" tabindex="-1" role="dialog" aria-labelledby="transformerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header" style="background-color:#FAB12F;">
                <h5 class="modal-title text-white mb-0" id="transformerModalLabel" style="font-size:20px;">
                    Transformer Rental
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="contracts/trans_rent.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="contract_type" value="<?= TRANS_RENT ?>" readonly>
                    <input type="hidden" name="uploader_id" value="<?= $userid ?>">
                    <input type="hidden" name="uploader"
                        value="<?= $getUser['firstname'] . ' ' . $getUser['middlename'] . ' ' . $getUser['lastname'] ?>">
                    <input type="hidden" name="uploader_dept" value="<?= $department ?>">

                    <div class="row g-3 p-3">
                        <!-- Customer / Account Details -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Customer Name</label>
                            <input type="text" class="form-control" name="contract_name" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">TC No.</label>
                            <input type="text" class="form-control" name="tc_no" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Account No.</label>
                            <input type="text" class="form-control" name="account_no" required>
                        </div>

                        <!-- Dates -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Installation Date</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                <input type="date" id="rent_start" class="form-control" name="rent_start" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Retirement Date</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                <input type="date" id="rent_end" class="form-control" name="rent_end" required>
                            </div>
                        </div>

                        <!-- Contract File & Address -->
                        <div class="col-md-5 mb-3">
                            <label class="form-label">Contract File</label>
                            <input type="file" class="form-control" name="contract_file" required>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" placeholder="Enter address" required>
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

<!-- Material Flat Styles for Inputs -->
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
        width: 100%;
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
        transition: all 0.2s ease;
    }

    .btn:hover {
        opacity: 0.9;
    }
</style>

<!-- JS for auto-calculating retirement date -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const rentStartInput = document.getElementById('rent_start');
        const rentEndInput = document.getElementById('rent_end');

        if (rentStartInput && rentEndInput) {
            rentStartInput.addEventListener('change', function () {
                const startDate = new Date(this.value);
                if (!isNaN(startDate)) {
                    startDate.setDate(startDate.getDate() + 90);
                    const year = startDate.getFullYear();
                    const month = String(startDate.getMonth() + 1).padStart(2, '0');
                    const day = String(startDate.getDate()).padStart(2, '0');
                    rentEndInput.value = `${year}-${month}-${day}`;
                }
            });
        }
    });
</script>