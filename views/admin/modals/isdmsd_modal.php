<?php 

use App\Controllers\ContractTypeController;
use App\Controllers\DepartmentController;
use App\Controllers\UserController;

$department =  $_SESSION['department'] ?? null;
$departments = (new DepartmentController)->getAllDepartments();

$getUserInfo = (new UserController)->getUserByDept($department);



?>
<!---- ISD-RAD MODAL ---->
<div class="modal fade" id="<?= $department ?>Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">MSD MODAL</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
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
                                <?php 
                                    $contract_types =  (new ContractTypeController)->getContractTypes();
                                ?>
                                <?php if($department == 'ISD-HRAD'): ?>
                                        <?php foreach($contract_types as $contract_type): ?>

                                            <?php if($contract_type['contract_type'] === 'Employment Contract' | $contract_type['contract_type'] === 'Rental Contract' ) : ?>
                                                <option value="<?= $contract_type['contract_type'] ?>"><?= $contract_type['contract_type'] ?></option>
                                            <?php else: ?>
                                                
                                            <?php endif; ?>
                                            
                                        <?php endforeach; ?>
                                        
                                    <?php else: ?>
                                        <option disabled> no contract available</option>
                                <?php endif; ?>
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
                      
                   <div class="mb-3">
                    <label class="badge text-muted">Department Assigned</label>
                    <select name="department_assigned" class="form-select" id="">
                        <?php foreach($departments as $department): ?>
                            <option hidden>Select Department</option>
                            <option value="<?= $department['department_name'] ?>"><?= $department['department_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>
                    <div>
                            <input type="hidden" name="uploader_id" value="<?= $getUserInfo['id'] ?>">
                            <input type="hidden" name="uploader_department" value="<?= $getUserInfo['department'] ?>">
                       </div>
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
  

 