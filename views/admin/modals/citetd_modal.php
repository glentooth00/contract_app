   <!---- CITETD MODAL ---->
   <div class="modal fade" id="<?= $department ?>Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Employment Contract</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <h2>CITETD MODAL</h2>
        <div class="modal-body">
            <form action="contracts/save_contract.php" method="post" enctype="multipart/form-data">
            <div class="col-md-12 d-flex gap-2 p-3">
                <div class="col-md-6 p-2">
                    <div class="mb-3">
                        <label class="badge text-muted">Contract</label>
                        <input type="file" name="contract_file" class="form-control">

                    </div>
                    <div class="mb-3">
                        <label class="badge text-muted">Starting Date</label>
                        <input type="date" class="form-control" name="contract_start" id="floatingInput" placeholder="name@example.com">
                        
                    </div>
                    
                    <div class="mb-3">
                        <label class="badge text-muted">Contract Type</label>
                            <select class="form-select form-select-md mb-3" name="contract_type" aria-label=".form-select-lg example">
                                <option selected hidden>Select contract type</option>
                                <option value="Employment Contract">Employment Contract</option>
                                <option value="Construction Contract">Construction Contract</option>
                                <option value="Licensing Agreement">Licensing Agreement</option>
                                <option value="Purchase Agreement">Purchase Agreement</option>
                                <option value="Service Agreement">Service Agreement</option>
                            </select>
                    </div>
                    
                </div>

                <div class="col-md-6 p-2">
                    <div class="mb-3">
                        <label class="badge text-muted">Contract Name</label>
                        <input type="text" class="form-control" name="contract_name" id="floatingInput" placeholder="">  
                    </div>
                    <div class="mb-3">
                    <label class="badge text-muted">End Date</label>
                        <input type="date" class="form-control" name="contract_end" id="floatingInput" placeholder="">
                    </div>
                    <!-- <div class="mb-3">
                        <label class="badge text-muted">Contract Status</label>
                            <select class="form-select form-select-md mb-3" name="contract_status" aria-label=".form-select-lg example">
                                <option selected hidden>Select contract type</option>
                                <option value="Employment Contract">Employment Contract</option>
                                <option value="Construction Contract">Construction Contract</option>
                                <option value="Licensing Agreement">Licensing Agreement</option>
                                <option value="Purchase Agreement">Purchase Agreement</option>
                                <option value="Service Agreement">Service Agreement</option>
                            </select>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
            <button type="submit" class="btn btn-primary" style="background-color: #118B50;">Save Contract</button>
        </div>
        </form>
        </div>
    </div>
    </div>