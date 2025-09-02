<?php
require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../../vendor/autoload.php';

use App\Controllers\ContractController;
use App\Controllers\NotificationController;
use App\Controllers\PendingDataController;
use App\Models\ContractModel;

if (isset($_POST['contract_id'])) {
    $contract_id = $_POST['contract_id'];

    // Get pending data
    $pending = (new PendingDataController())->getNewData($contract_id);

    // Get current contract data
    $current = (new ContractController())->getContractById($contract_id); // Write this method if not exists

    if (!$pending) {
        echo "<p class='text-danger'>No pending data found.</p>";
        exit;
    }

    if (!$current) {
        echo "<p class='text-danger'>No current contract data found.</p>";
        exit;
    }
    ?>
<!-- Pending Data -->
<form action="contracts/approve_update.php" method="POST">
    <div class="row p-2">
        <!-- Current Data -->
        <div class="col-md-6">
            <div class="card p-3" style="background-color: #F8F9FA;">
                <h5>Current Data</h5>
                <hr>

                <!-- Contract Name -->
                <div class="mb-3">
                    <label><strong>Contract Name:</strong></label>
                    <input type="text" value="<?= $current['contract_name'] ?>" class="form-control" readonly>
                </div>

                <div class="">
                    <input type="hidden" value="<?= $pending['contract_type'] ?>" name="contract_type" class="form-control" readonly>
                </div>
                <!-- Start Date -->
                <div class="mb-3">
                    <label><strong>Start Date:</strong></label>

                    <?php if($pending['contract_type'] === TRANS_RENT ): ?> 
                        <div class="input-group">
                            <input type="text"  value="<?= date('M d, Y', strtotime($current['rent_start'])) ?>" class="form-control" readonly>
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        </div>
                    <?php endif; ?>

                    <?php if($current['contract_type'] === TEMP_LIGHTING ): ?> 
                        <div class="input-group">
                            <input type="text" value="<?= date('M d, Y', strtotime($current['contract_start'])) ?>" class="form-control" readonly>
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        </div>
                    <?php endif; ?>

                    <?php if($current['contract_type'] === GOODS ): ?> 
                        <div class="input-group">
                            <input type="text" value="<?= date('M d, Y', strtotime($current['contract_start'])) ?>" class="form-control" readonly>
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        </div>
                    <?php endif; ?>

                    <?php if($current['contract_type'] === INFRA ): ?> 
                        <div class="input-group">
                            <input type="text" value="<?= date('M d, Y', strtotime($current['contract_start'])) ?>" class="form-control" readonly>
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        </div>
                    <?php endif; ?>

                    
                    

                </div>

                <!-- End Date -->
                <div class="mb-3">
                    <label><strong>End Date:</strong></label>
                    <?php if($current['contract_type'] === TRANS_RENT ): ?> 
                        <div class="input-group">
                            <input type="text" value="<?= date('M d, Y', strtotime($current['rent_end'])) ?>" class="form-control" readonly>
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        </div>
                    <?php endif; ?>

                    <?php if($current['contract_type'] === TEMP_LIGHTING ): ?> 
                        <div class="input-group">
                            <input type="text" value="<?= date('M d, Y', strtotime($current['contract_end'])) ?>" class="form-control" readonly>
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        </div>
                    <?php endif; ?>

                    <?php if($current['contract_type'] === GOODS ): ?> 
                        <div class="input-group">
                            <input type="text" value="<?= date('M d, Y', strtotime($current['contract_end'])) ?>" class="form-control" readonly>
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        </div>
                    <?php endif; ?>

                    <?php if($current['contract_type'] === INFRA ): ?> 
                        <div class="input-group">
                            <input type="text" value="<?= date('M d, Y', strtotime($current['contract_end'])) ?>" class="form-control" readonly>
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        </div>
                    <?php endif; ?>

                    <input type="hidden" name="uploader_department" value="<?= $pending['uploader_department'] ?>" >
                </div>

                <?php if(empty($current['address'])): ?>
                    <div class="mb-3">
                        <label><strong>Address:</strong></label>
                        <input type="text" value="<?= $current['address'] ?>" class="form-control" readonly>
                    </div>
                <?php else: ?>
                    <div class="mb-3">
                        <label><strong>Address:</strong></label>
                        <input type="text" value="<?= $current['address'] ?>" class="form-control" readonly>
                    </div>
                <?php endif; ?>

                <?php if(empty($current['proc_mode'])): ?>
                    <div class="mb-3">
                        <label><strong>Procurement Mode:</strong></label>
                        <input type="text" value="<?= $current['procurementMode'] ?>" class="form-control" readonly>
                    </div>
                <?php else: ?>
                    <div class="mb-3">
                        <label><strong>Procurement Mode:</strong></label>
                        <input type="text" value="<?= $current['procurementMode'] ?>" class="form-control" readonly>
                    </div>
                <?php endif; ?>

                <?php if(empty($current['contractPrice'])): ?>
                    <div class="mb-3">
                        <label><strong>Procurement Mode:</strong></label>
                        <input type="text" value="<?= $current['contractPrice'] ?>" class="form-control" readonly>
                    </div>
                <?php else: ?>
                    <div class="mb-3">
                        <label><strong>Procurement Mode:</strong></label>
                        <input type="text" value="<?= $current['contractPrice'] ?>" class="form-control" readonly>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Pending Data -->
        <div class="col-md-6">
            <div class="card p-3" style="background-color: #E8F9FF;">
                <h5>Pending Data</h5>
                <hr>
                
                    <input type="hidden" value="<?= $pending['contract_id'] ?>" name="contract_id" class="form-control" readonly>
                <!-- Contract Name -->
                <div class="mb-3">
                    <label><strong>Contract Name:</strong></label>
                    <input type="text" value="<?= $pending['contract_name'] ?>" name="contract_name" class="form-control" readonly>
                </div>

                 <div class="">
                    <input type="hidden" value="<?= $pending['contract_type'] ?>" name="contract_type" class="form-control" readonly>
                </div>

                <!-- Start Date -->
                <div class="mb-3">
                    <label><strong>Start Date:</strong></label>

                    <?php if($pending['contract_type'] === TRANS_RENT ): ?> 
                        <div class="input-group">
                            <input type="text" name="rent_start" value="<?= date('M d, Y', strtotime($pending['contract_start'])) ?>" class="form-control" readonly>
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        </div>
                    <?php endif; ?>

                    <?php if($pending['contract_type'] === TEMP_LIGHTING ): ?> 
                        <div class="input-group">
                            <input type="text" name="contract_start" value="<?= date('M d, Y', strtotime($pending['contract_start'])) ?>" class="form-control" readonly>
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        </div>
                    <?php endif; ?>

                    
                    <?php if($pending['contract_type'] === GOODS ): ?> 
                        <div class="input-group">
                            <input type="text" name="contract_start" value="<?= date('M d, Y', strtotime($pending['contract_start'])) ?>" class="form-control" readonly>
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        </div>
                    <?php endif; ?>

                     <?php if($pending['contract_type'] === INFRA ): ?> 
                        <div class="input-group">
                            <input type="text" name="contract_start" value="<?= date('M d, Y', strtotime($pending['contract_start'])) ?>" class="form-control" readonly>
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        </div>
                    <?php endif; ?>

                </div>

                <!-- End Date -->
                <div class="mb-3">
                    <label><strong>End Date:</strong></label>
                    <?php if($pending['contract_type'] === TRANS_RENT ): ?> 
                        <div class="input-group">
                            <input type="text" name="rent_end" value="<?= date('M d, Y', strtotime($pending['contract_end'])) ?>" class="form-control" readonly>
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        </div>
                    <?php endif; ?>

                    <?php if($pending['contract_type'] === TEMP_LIGHTING ): ?> 
                        <div class="input-group">
                            <input type="text" name="contract_end" value="<?= date('M d, Y', strtotime($pending['contract_end'])) ?>" class="form-control" readonly>
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        </div>
                    <?php endif; ?>

                    <?php if($pending['contract_type'] === GOODS ): ?> 
                        <div class="input-group">
                            <input type="text" name="contract_end" value="<?= date('M d, Y', strtotime($pending['contract_end'])) ?>" class="form-control" readonly>
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        </div>
                    <?php endif; ?>

                    <?php if($pending['contract_type'] === INFRA ): ?> 
                        <div class="input-group">
                            <input type="text" name="contract_end" value="<?= date('M d, Y', strtotime($pending['contract_end'])) ?>" class="form-control" readonly>
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        </div>
                    <?php endif; ?>

                    <input type="hidden" name="uploader_department" value="<?= $pending['uploader_department'] ?>" >
                </div>

                    <div class="mb-3">
                        <label><strong>Address:</strong></label>
                        <input type="text" name="address" value="<?= $pending['address'] ?>" class="form-control" readonly>
                    </div>

                <?php if(!empty($pending['proc_mode'])): ?>
                    <div class="mb-3">
                        <label><strong>Procurement Mode:</strong></label>
                        <input type="text" name="proc_mode" value="<?= $pending['proc_mode'] ?>" class="form-control" readonly>
                    </div>
                <?php endif; ?>

                <?php if(empty($pending['total_cost'])): ?>
                    <div class="mb-3">
                        <label><strong>Procurement Mode:</strong></label>
                        <input type="text" name="total_cost" value="<?= $pending['total_cost'] ?>" class="form-control" readonly>
                    </div>
                <?php else: ?>
                    <div class="mb-3">
                        <label><strong>Procurement Mode:</strong></label>
                        <input type="text" name="total_cost" value="<?= $pending['total_cost'] ?>" class="form-control" readonly>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <div class="p-2 mt-4">
            <button class="btn btn-primary btn-sm float-end">Approve update</button>
        </div>
    </div>
</form>



    <?php
}
?>