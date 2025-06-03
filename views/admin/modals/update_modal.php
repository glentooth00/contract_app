<!----update modal ------->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Compare Changes</h5></span>
            </div>
            <div class="modal-body">
                <div class="col-md-12 d-flex gap-1">
                    <div class="col-md-6 card p-3" style="background-color: #ECFAE5;">
                        <div>

                            <h5>Current Data</h5>
                            <hr>
                            <?php

                            $contract_id = $contract['contract_id'];

                            $getContractFromContracts = (new ContractController)->getContractbyId($contract_id);

                            // var_dump($getContractFromContracts); 
                            
                            ?>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="badge text-muted">Contract name</label>
                                    <input type="text" value="<?= $getContractFromContracts['contract_name'] ?>"
                                        class="form-control" readonly>
                                </div>
                                <div class="d-flex gap-2">
                                    <div class="col-md-6">
                                        <label class="badge text-muted">Date Start</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                                                    <path
                                                        d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-12a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                                                </svg>
                                            </span>
                                            <input type="text"
                                                value="<?= $getContractFromContracts['contract_start'] ?? '' ?>"
                                                class="form-control" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="badge text-muted">Date End</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                                                    <path
                                                        d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-12a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                                                </svg>
                                            </span>
                                            <input type="text"
                                                value="<?= $getContractFromContracts['contract_end'] ?? '' ?>"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label class="badge text-muted" <?php

                                    $start = new DateTime($getContractFromContracts['contract_start']);
                                    $end = new DateTime($getContractFromContracts['contract_end']);
                                    $today = new DateTime();

                                    $interval = $today->diff($end);
                                    $remainingDays = $interval->invert ? -$interval->days : $interval->days;



                                    ?> style="font-size: 15px;">Days
                                        Remaining:</label>
                                    <div class="d-flex">
                                        <input type="text" style="margin-left:7px;" class="form-control"
                                            value=" <?= $remainingDays ?> day<?= $remainingDays != 1 ? 's' : '' ?>"
                                            readonly>
                                        <?php

                                        $remainingDays;
                                        // echo $id = $getContract['id'];
                                        
                                        if ($remainingDays === 0) {

                                            $data = [
                                                'id' => $getContractFromContracts['id'],
                                                'contract_status' => 'Expired',
                                            ];

                                            (new ContractController)->updateStatusExpired($data);

                                        } else {
                                            // echo 'contract still active';
                                        }


                                        ?>

                                    </div>
                                </div>

                            </div>

                            <!-- <p><strong>Row ID:</strong> <span id="modal-id"></span></p>
                            <p><strong>Contract ID:</strong> <span id="modal-contract-id"></span></p>
                            <p><strong>Contract Name:</strong> <span id="modal-contract-name"></span></p>
                            <p><strong>Start Date:</strong> <span id="modal-start-date"></span></p>
                            <p><strong>End Date:</strong> <span id="modal-end-date"></span></p> -->
                        </div>
                    </div>
                    <div class="col-md-6 card p-3" style="background-color: #E8F9FF;">
                        <div>
                            <h5>Pending Changes</h5>
                            <hr>
                            <?php

                            $contract_id = $contract['contract_id'];

                            $getPendingUpdate = (new NotificationController)->getPendingDatabyId($contract_id);

                            // var_dump($getContractFromContracts);
                            

                            ?>
                            <div class="col-md-12">
                                <form action="contracts/update.php" method="POST">
                                    <div class="mb-3">

                                        <input type="hidden" name="user_dept"
                                            value="<?= $getPendingUpdate['uploader_department'] ?>" class="form-control"
                                            readonly>
                                        <input type="hidden" name="id" value="<?= $getPendingUpdate['id'] ?>"
                                            class="form-control" readonly>
                                        <input type="hidden" name="contract_id"
                                            value="<?= $getPendingUpdate['contract_id'] ?>" class="form-control"
                                            readonly>
                                        <label class="badge text-muted">Contract
                                            name</label>
                                        <input type="text" name="contract_name"
                                            value="<?= $getPendingUpdate['contract_name'] ?>" class="form-control">
                                    </div>
                                    <div class="d-flex gap-2">
                                        <div class="col-md-6">
                                            <label class="badge text-muted">Date
                                                Start</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-12a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                                                    </svg>
                                                </span>
                                                <input type="text" name="contract_start"
                                                    value="<?= $getPendingUpdate['contract_start'] ?? '' ?>"
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="badge text-muted">Date End</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-12a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                                                    </svg>
                                                </span>
                                                <input type="text" name="contract_end"
                                                    value="<?= $getPendingUpdate['contract_end'] ?? '' ?>"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label class="badge text-muted" <?php

                                        $start = new DateTime($getPendingUpdate['contract_start']);
                                        $end = new DateTime($getPendingUpdate['contract_end']);
                                        $today = new DateTime();

                                        $interval = $today->diff($end);
                                        $remainingDays = $interval->invert ? -$interval->days : $interval->days;



                                        ?> style="font-size: 15px;">Days
                                            Remaining:</label>
                                        <div class="d-flex">
                                            <input type="text" style="margin-left:7px;" class="form-control"
                                                value=" <?= $remainingDays ?> day<?= $remainingDays != 1 ? 's' : '' ?>"
                                                readonly>
                                            <?php

                                            $remainingDays;
                                            // echo $id = $getContract['id'];
                                            
                                            if ($remainingDays === 0) {

                                                $data = [
                                                    'id' => $getPendingUpdate['id'],
                                                    'contract_status' => 'Expired',
                                                ];

                                                (new ContractController)->updateStatusExpired($data);

                                            } else {
                                                // echo 'contract still active';
                                            }


                                            ?>

                                        </div>
                                    </div>

                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button type="submit" class="btn btn-primary">Approve Update</button>
            </div>
            </form>
        </div>
    </div>
</div>