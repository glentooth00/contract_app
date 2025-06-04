<div class="modal fade" id="transformerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-lg" style="width: 45em;
    margin-left: -5em;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Transformer Rental Contract</h5>
                        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php $department = $_SESSION['department'] ?? null; ?>
                        <form action="contracts/transformerRent.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" class="form-control" name="contract_type" value="<?= TRANS_RENT ?>"
                                readonly>
                            <div class="col-md-12 d-block gap-2">
                                <div class="col-md-12 d-flex gap-2 row justify-content-center">
                                    <div class="col-md-3 p-2" style="width: 13em;">
                                        <div>
                                            <input type="hidden" class="form-control" name="uploader_department"
                                                value="<?= $department ?>" required>
                                            <lable class="badge text-muted">Contract Name</lable>
                                            <input type="text" class="form-control"
                                                value="<?= $getContract['contract_name'] ?>" name="contract_name"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-3 p-2" style="width: 13em;">
                                        <div>
                                            <lable class="badge text-muted">TC No.</lable>
                                            <input type="text" class="form-control" name="tc_no"
                                                value="<?= $getContract['tc_no'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3 p-2" style="width: 13em;">
                                        <div>
                                            <lable class="badge text-muted">Account no.</lable>
                                            <input type="text" class="form-control"
                                                value="<?= $getContract['account_no'] ?>" name="account_no" required>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-12 d-flex gap-5 row justify-content-center">

                                    <div class="col-md-4 p-2" style="width: 15em;">
                                        <div>
                                            <lable class="badge text-muted">Rent Start</lable>
                                            <div class="d-flex">
                                                <i class="fa fa-calendar p-2" style="font-size: 20px;"
                                                    aria-hidden="true"></i>
                                                <input type="date" id="rent_start" class="form-control"
                                                    name="rent_start" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 p-2" style="width: 15em;">
                                        <div>
                                            <lable class="badge text-muted">Rent End</lable>
                                            <div class="d-flex">
                                                <i class="fa fa-calendar p-2" style="font-size: 20px;"
                                                    aria-hidden="true"></i>
                                                <input type="date" id="rent_end" class="form-control" name="rent_end"
                                                    required>
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
                                <div class="col-md-12 d-flex gap-4 row justify-content-center">
                                    <!-- <div class="col-md-5 p-2">
                                            <div>
                                                <lable class="badge text-muted">Party of Second Part</lable>
                                                <input type="text" class="form-control" name="party_of_second_part"
                                                    required>
                                            </div>
                                        </div> -->
                                    <div class="col-md-5 p-2">
                                        <div>
                                            <lable class="badge text-muted">Contract file</lable>
                                            <input id="contractFileInput" type="file" class="form-control"
                                                name="contract_file" style="width: 16.7em;" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 p-2">
                                        <div>
                                            <?php
                                            $userid;
                                            $getUser = (new UserController)->getUserById($userid);

                                            // var_dump($getUser['firstname']);
                                            ?>
                                            <input type="hidden" id="date_start" class="form-control" name="uploader_id"
                                                value="<?= $userid ?>">
                                            <input type="hidden" id="date_start" class="form-control" name="uploader"
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
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                        <button type="submit" id="submitButton" class="btn btn-primary" disabled
                            onmouseover="pointer">Submit New Contract</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

          <script>
            const fileInput = document.getElementById('contractFileInput');
            const submitButton = document.getElementById('submitButton');

            fileInput.addEventListener('change', function () {

                if (fileInput.files.length > 0) {
                    submitButton.disabled = false;
                } else {
                    submitButton.disabled = true;
                }

            });

        </script>