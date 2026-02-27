<?php
use App\Controllers\UserController;
use App\Controllers\ContractTypeController;

$userid = $_SESSION['id'];
$user_department = (new UserController)->getUserById($userid);
$department = $user_department['department'];
$id = $user_department['id'];
$get_contract_types = (new ContractTypeController)->getContractType($department);
$name = $user_department['firstname'] . ' ' . $user_department['middlename'] . ' ' . $user_department['lastname'];
?>

<!-- Power Suppliers Contract Modal -->
<div class="modal fade" id="powerSupplyShortModal" tabindex="-1" aria-labelledby="powerSupplyShortModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header" style="background-color:#FAB12F;">
                <h5 class="modal-title text-white mb-0" id="powerSupplyShortModalLabel" style="font-size:20px;">
                    Power Suppliers Contract
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="contracts/save_contract_powersupply.php" method="post" enctype="multipart/form-data">
                    <div class="row g-3 p-3">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Contract File</label>
                                <input type="file" name="contract_file" class="form-control form-control-sm">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Starting Date</label>
                                <input type="date" name="contract_start" class="form-control form-control-sm">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Power Supply Contract Type</label>
                                <select class="form-select form-select-sm" name="contract_type">
                                    <option value="" hidden>Select Power Supply contract type</option>
                                    <?php if ($department === CITET): ?>
                                        <?php foreach ($get_contract_types as $contract_type): ?>
                                            <option value="<?= $contract_type['contract_type'] ?>">
                                                <?= $contract_type['contract_type'] ?></option>
                                        <?php endforeach; ?>
                                    <?php elseif ($department === IASD || $department === 'PSPTD'): ?>
                                        <option value="Power Suppliers Contract (LONG TERM)">Power Suppliers Contract (LONG
                                            TERM)</option>
                                        <option value="Power Suppliers Contract (SHORT TERM)">Power Suppliers Contract
                                            (SHORT TERM)</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Contract Name</label>
                                <input type="text" name="contract_name" class="form-control form-control-sm">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="contract_end" class="form-control form-control-sm">
                            </div>

                            <!-- Hidden Inputs -->
                            <input type="hidden" name="uploader_department" value="<?= $department ?>">
                            <input type="hidden" name="uploader" value="<?= $name ?>">
                            <input type="hidden" name="uploader_id" value="<?= $id ?>">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn" style="background-color: #118B50; color:#fff;">Save
                            Contract</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Material-inspired flat style -->
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
        padding: 6px 10px;
        font-size: 0.875rem;
        box-shadow: none;
        border: 1px solid #ddd;
        transition: all 0.2s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #3f51b5;
        box-shadow: 0 0 0 0.2rem rgba(63, 81, 181, 0.15);
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