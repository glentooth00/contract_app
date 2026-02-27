<?php
use App\Controllers\ContractHistoryController;

$id = $getContract['account_no'] ?? $getContract['id'];
$status = $getContract['contract_status'];
$contractHist_datas = (new ContractHistoryController)->getByContractId($id);

// Update status if expired
if ($status === 'Expired') {
    (new ContractHistoryController)->updateStatus(['id' => $id, 'status' => 'Expired']);
}
?>

<div class="contract-history-container mt-5">
    <h4>Contract History</h4>
    <hr>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Contract File</th>
                    <th style="text-align: center;">Date Start</th>
                    <th style="text-align: center;">Date End</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($contractHist_datas)): ?>
                    <?php foreach ($contractHist_datas as $data): ?>
                        <tr>
                            <!-- Status -->
                            <td>
                                <?php
                                $status = $data['status'] ?? '';
                                switch ($status) {
                                    case 'Active':
                                        $badgeClass = 'bg-success';
                                        $badgeText = 'Active';
                                        break;
                                    case 'Expired':
                                        $badgeClass = 'bg-danger';
                                        $badgeText = 'Rental Contract Ended';
                                        break;
                                    default:
                                        $badgeClass = 'bg-warning text-dark';
                                        $badgeText = 'Employment Contract Ended';
                                }
                                ?>
                                <span class="badge <?= $badgeClass ?> p-2"><?= $badgeText ?></span>
                            </td>

                            <!-- Contract File -->
                            <td>
                                <?php if (!empty($data['contract_file'])): ?>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#fileModal<?= $data['id'] ?>">View File
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="fileModal<?= $data['id'] ?>" tabindex="-1"
                                        aria-labelledby="fileModalLabel<?= $data['id'] ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-xl modal-dialog-centered" style="max-height: 95vh;">
                                            <div class="modal-content" style="height: 95vh;">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="fileModalLabel<?= $data['id'] ?>">
                                                        <?= htmlspecialchars($data['contract_name']) ?> -
                                                        <?= htmlspecialchars($data['contract_type']) ?>
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-0" style="height: calc(95vh - 60px); overflow-y: auto;">
                                                    <iframe src="<?= htmlspecialchars("../../../" . $data['contract_file']) ?>"
                                                        width="100%" height="100%" style="border:none;"></iframe>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php else: ?>
                                    <span class="text-muted">No file</span>
                                <?php endif; ?>
                            </td>

                            <!-- Date Start -->
                            <td>
                                <?php if (!empty($data['date_start'])): ?>
                                    <span class="badge bg-light text-dark">
                                        <?= date('M d, Y', strtotime($data['date_start'] ?? $data['rent_start'])) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger">No Start Date</span>
                                <?php endif; ?>
                            </td>

                            <!-- Date End -->
                            <td>
                                <?php if (!empty($data['date_end'])): ?>
                                    <span class="badge bg-light text-dark">
                                        <?= date('M d, Y', strtotime($data['date_end'] ?? $data['rent_end'])) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger">No End Date</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">No contract data found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .contract-history-container h4 {
        font-weight: 600;
        margin-bottom: 15px;
    }

    .table th,
    .table td {
        vertical-align: middle;
        font-size: 14px;
    }

    .table td .badge {
        font-size: 13px;
    }

    .modal-xl {
        max-width: 90% !important;
    }
</style>