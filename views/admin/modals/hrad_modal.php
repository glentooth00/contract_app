<?php

use App\Controllers\ContractTypeController;
use App\Controllers\DepartmentController;
use App\Controllers\UserController;

$department = $_SESSION['department'] ?? null;
$departments = (new DepartmentController)->getAllDepartments();

$getUserInfo = (new UserController)->getUserByDept($department);

echo $uploader = $getUserInfo['firstname'];
?>
<!-- ISD-RAD MODAL - Material Design Style -->
<div class="modal fade" id="hradModal" tabindex="-1" aria-labelledby="hradModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content flat-modal">
            <!-- Modal Header -->
            <div class="modal-header flat-header">
                <h5 class="modal-title" id="hradModalLabel">Add Employment Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form action="contracts/save_contract.php" method="post" enctype="multipart/form-data">
                    <div class="row g-3">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Contract File</label>
                                <input type="file" name="contract_file" class="form-control flat-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Starting Date</label>
                                <input type="date" class="form-control flat-input" name="contract_start" required>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Contract Name</label>
                                <input type="text" class="form-control flat-input" name="contract_name"
                                    placeholder="Enter contract name" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">End Date</label>
                                <input type="date" class="form-control flat-input" name="contract_end" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Department Assigned</label>
                                <select name="department_assigned" class="form-select flat-input" required>
                                    <option selected disabled>Select Department</option>
                                    <?php foreach ($departments as $department): ?>
                                        <option value="<?= $department['department_name'] ?>">
                                            <?= $department['department_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Inputs -->
                    <input type="hidden" name="contract_type" value="Employment Contract">
                    <input type="hidden" name="uploader"
                        value="<?= $getUserInfo['firstname'] . ' ' . $getUserInfo['middlename'] . ' ' . $getUserInfo['lastname'] ?>">
                    <input type="hidden" name="uploader_id" value="<?= $getUserInfo['id'] ?>">
                    <input type="hidden" name="uploader_department" value="<?= $getUserInfo['department'] ?>">

                    <!-- Modal Footer -->
                    <div class="modal-footer flat-footer">
                        <button type="button" class="btn flat-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn flat-btn-save">Save Contract</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Flat Material UI modal */
    .flat-modal {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        box-shadow: none;
        /* Flat style, no shadow */
        font-family: 'Roboto', sans-serif;
    }

    .flat-header {
        background-color: #f5f5f5;
        color: #333;
        font-weight: 500;
        font-size: 18px;
        border-bottom: 1px solid #e0e0e0;
        padding: 1rem 1.5rem;
    }

    .flat-header .btn-close {
        filter: none;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .form-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #555;
        margin-bottom: 0.25rem;
    }

    .flat-input {
        border-radius: 4px;
        border: 1px solid #cfcfcf;
        padding: 0.5rem 0.75rem;
        transition: border-color 0.2s, box-shadow 0.2s;
        font-size: 0.875rem;
        background-color: #fff;
    }

    .flat-input:focus {
        border-color: #3f51b5;
        box-shadow: 0 0 0 2px rgba(63, 81, 181, 0.2);
        outline: none;
    }

    .flat-footer {
        border-top: 1px solid #e0e0e0;
        padding: 0.75rem 1.5rem;
        justify-content: flex-end;
        gap: 10px;
    }

    .flat-btn-save {
        background-color: #3f51b5;
        color: #fff;
        font-weight: 500;
        border-radius: 4px;
        border: none;
        padding: 0.5rem 1rem;
        transition: background-color 0.2s;
    }

    .flat-btn-save:hover {
        background-color: #303f9f;
    }

    .flat-btn-cancel {
        background-color: transparent;
        color: #555;
        font-weight: 500;
        border: 1px solid #cfcfcf;
        border-radius: 4px;
        padding: 0.5rem 1rem;
    }

    .flat-btn-cancel:hover {
        background-color: #f0f0f0;
    }

    /* Add spacing between inputs */
    .flat-modal .form-group {
        margin-bottom: 1rem;
        /* space between each input field */
    }

    .flat-modal .flat-input,
    .flat-modal .form-select {
        width: 100%;
        box-sizing: border-box;
    }

    /* Existing flat input focus style */
    .flat-input:focus,
    .form-select:focus {
        border-color: #3f51b5;
        box-shadow: 0 0 0 2px rgba(63, 81, 181, 0.2);
        outline: none;
    }

    @media (max-width: 768px) {
        .modal-dialog {
            max-width: 95%;
        }
    }
</style>