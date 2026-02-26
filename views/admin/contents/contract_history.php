<?php
use App\Controllers\ContractHistoryController;
?>

<div>
    <div class="mt-5">
        <h4>Contract History </h4>
    </div>
    <hr>
    <div>
        <table class="table table-stripped table-hover">
            <thead>
                <tr>
                    <th style="text-align: center !important;"><span class="badge text-muted">Status</span></th>
                    <th style="text-align: center !important;"><span class="badge text-muted">Contract
                            File</span></th>
                    <th style="text-align: center !important;"><span class="badge text-muted">Date Start</span>
                    </th>
                    <th style="text-align: center !important;"><span class="badge text-muted">Date End</span>
                    </th>
                    <!-- <th style="text-align: center !important;"><span class="badge text-muted">Action</span></th> -->
                </tr>
            </thead>
            <?php
            $id = $getContract['account_no'] ?? $getContract['id'];
            $status = $getContract['contract_status'];
            $contractHist_datas = (new ContractHistoryController)->getByContractId($id);

            if ($status == 'Expired') {

                $stat = [
                    'id' => $id,
                    'status' => 'Expired',
                ];

                $updateStatus = (new ContractHistoryController)->updateStatus($stat);


            }

            // var_dump($contractHist_datas);
            ?>
            <tbody class="">
                <?php if (!empty($contractHist_datas)): ?>
                    <?php foreach ($contractHist_datas as $employement_data): ?>
                        <tr>
                            <td style="text-align: center !important;">

                                <?php if ($employement_data['status'] == 'Active'): ?>
                                    <span class="badge bg-success p-2"><?= $employement_data['status']; ?></span>
                                <?php elseif ($employement_data['status'] == 'Expired'): ?>
                                    <span class="badge bg-danger p-2">Rental Contract Ended</span>
                                <?php else: ?>
                                    <span class="badge text-dark bg-warning p-2">Employment Contract ended</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center !important;">


                                <?php if (!empty($employement_data['contract_file'])): ?>
                                    <!-- Trigger the modal with this button -->
                                    <button class="btn btn-primary badge p-2" data-bs-toggle="modal"
                                        data-bs-target="#fileModal<?= $employement_data['id'] ?>"
                                        style="text-align: center !important;">
                                        View file
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="fileModal<?= $employement_data['id'] ?>" tabindex="-1"
                                        aria-labelledby="fileModalLabel<?= $employement_data['id'] ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-xl" style="min-height: 100vh; max-height: 300vh;">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="fileModalLabel<?= $employement_data['id'] ?>">
                                                        <?= $employement_data['contract_name'] ?> -
                                                        <?= $employement_data['contract_type'] ?>
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body" style="padding: 0; overflow-y: auto;">
                                                    <!-- Display the contract file inside the modal -->
                                                    <iframe
                                                        src="<?= htmlspecialchars("../../../" . $employement_data['contract_file']) ?>"
                                                        width="100%" style="height: 80vh;" frameborder="0"></iframe>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    No file
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center !important;">

                                <?php if (!empty($employement_data['date_start'])): ?>
                                    <?php $datestart = new DateTime($employement_data['date_start']); ?>
                                    <span class="badge text-dark"><?= date_format($datestart, "M-d-Y"); ?></span>
                                <?php else: ?>
                                    <span class="badge text-danger">No Start Date</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center !important;">
                                <?php if (!empty($employement_data['date_end'])): ?>
                                    <?php $datestart = new DateTime($employement_data['date_end']); ?>
                                    <span class="badge text-dark"><?= date_format($datestart, "M-d-Y"); ?></span>
                                <?php else: ?>
                                    <span class="badge text-danger">No Start Date</span>
                                <?php endif; ?>

                            </td>

                            <!-- <td style="text-align: center !important;">

                                        <button class="btn btn-danger btn-sm" title="Delete">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>


                                        <button class="btn btn-primary btn-sm" title="Edit" data-bs-toggle="modal"
                                            data-bs-target="#editModal">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </button>
                                    </td> -->


                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No contract data found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
    </div>
</div>