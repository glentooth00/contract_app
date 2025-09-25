<?php 
use App\Controllers\CommentController;
use App\Controllers\FlagController;

$account_no = $getContract['account_no'];
?>

<div class="row align-items-center">
    <div class="col-10 col-sm-11 d-flex">
        <h2 class="mt-2" >
            <a href="list.php" class="text-dark pt-2" style="text-decoration: none;">
                <i class="fa fa-angle-double-left"></i>
            </a>
            <?= $contract_data ?>
            <?php if(!empty($account_no)): ?>
                <span id="account_no">
                (<?= $account_no ?>)
            </span>
            <?php endif; ?>
        </h2>
        
    <?php include_once('../flags/flags.php'); ?>

    </div>
    <div class="col-2 col-sm-1 d-flex justify-content-end pe-4">
        <?php 
            $contractId = $getContract['id'];
            $hasComment = (new CommentController)->hasComment($contractId);
            $hasCommentCount = (new CommentController)->hasCommentCount($contractId);
        ?>
        <div class="d-flex align-items-center gap-2">

         <?php if (isset($department)) { ?>

                <?php switch ($department) {
                    case 'IT': ?>

                        <span class="badge p-2" style="background-color: #0d6efd;"><?= $department . ' ' . $role ?> user</span>

                        <?php break;
                    case 'ISD': ?>

                        <span class="badge p-2" style="background-color: #3F7D58;"><?= $department . ' ' . $role ?> user</span>

                        <?php break;
                    case 'CITETD': ?>

                        <span class="badge p-2" style="background-color: #FFB433;"><?= $department . ' ' . $role ?> user</span>

                        <?php break;
                    case 'IASD': ?>

                        <span class="badge p-2" style="background-color: #EB5B00;"><?= $department . ' ' . $role ?> user</span>

                        <?php break;
                    case 'ISD-MSD': ?>

                        <span class="badge p-2" style="background-color: #6A9C89;"><?= $department . ' ' . $role ?> user</span>

                        <?php break;
                    case 'BAC': ?>

                        <span class="badge p-2" style="background-color: #3B6790;"><?= $department . ' ' . $role ?> user</span>

                        <?php break;
                    case '': ?>

                    <?php default: ?>

                <?php } ?>

            <?php } else { ?>

        <?php } ?>

        <?php include_once 'bell.php'; ?>
            <!-- Comment icon with badge -->
                        <?php if(isset($contractId)): ?>
                                <?php 
                                $contractId = $getContract['id'];

                                    $hasComment = ( new CommentController )->hasComment($contractId);
                                ?>
                                <?php if($hasComment == true): ?>
                                    <span class="float-end">
                                        <?php include_once 'message.php'; ?> 
                                    </span>

                                <?php else: ?>
                                    <span class="float-end">
                                        <?php include_once 'no_message.php'; ?> 
                                    </span>
                                <?php endif; ?>
                                
                                <span class="p-3">
                                    <?php
                                        $id = $contractId;
                                        $getFlag = ( new FlagController )->getFlag($id);
                                    ?>
                                    <?php if( $getFlag['status'] ?? '' === 1 ): ?>
                                        
                                        <?php if($getFlag['flag_type'] === UR): ?>
                                                <img src="../../../public/images/underReview.svg" id="review" width="27px;" title="This Contract is Under review">
                                            <?php endif;  ?>
                                            <?php if($getFlag['flag_type'] === NA): ?>
                                                <img src="../../../public/images/withComment.svg" id="attention" width="27px;" title="This Contract Needs Attention">
                                            <?php endif;  ?>
                                    <?php endif; ?>
                                </span>
                                <?php endif; ?>
            <!-- Three-dot dropdown -->
            <div class="dotMenu" onclick="toggleView()" id="dotMenu">
                <img src="../../../public/images/dotMenu.svg" width="25px">
                <div id="dropMenu">
                    <ul>
                        <li>
                            <a href="" class="w-100" data-toggle="modal" data-target="#suspendModal"><img  src="../../../public/images/suspendFile.svg" width="20px"><small id="" class="p-2 mb-5">Suspend Contract</small></a>
                        </li>
                        <li>
                            <a href="" class="w-100"><img  src="../../../public/images/bell.svg" width="20px"><small id=""  class="p-2 mb-5">Notification</small></a>
                        </li>
                        <?php if($department === IASD): ?>
                            <li>
                                <span><img src="../../../public/images/flagContract.svg" width="25px"><small data-toggle="modal" data-target="#flagModal" id="flagContract">Flag Contract</small></span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Modal -->
    <div class="modal fade" id="flagModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Set Flag to this Document</h5>
        </div>
        <div class="modal-body">
           <form action="flag/flag_contract.php" method="POST">
            <div class="d-flex col-md-12">
                <input type="hidden" name="contract_id" value="<?= $contractId ?>">

                <div class="col-md-6">
                <div class="form-check">

                    <input class="form-check-input" type="checkbox" id="attention" name="attention">
                    <img src="../../../public/images/withComment.svg" width="25px">
                    <label class="form-check-label" for="attention">Needs Attention</label>
                </div>
                </div>

                <div class="col-md-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="review" name="review">
                    <img src="../../../public/images/underReview.svg" width="25px">
                    <label class="form-check-label" for="review">Under Review</label>
                </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Apply Flag</button>
            </div>
            </form>
        </div>
        </div>
    </div>
    </div>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    document.querySelectorAll('.view-comment-trigger').forEach(function (img) {
                        img.addEventListener('click', function () {
                            const contractId = this.dataset.contractId;

                            fetch(`comments/update_status.php?contract_id=${contractId}`)
                                .then(response => response.text())
                                .then(data => {
                                    console.log('PHP response:', data);

                                    // Hide the badge in real-time
                                    const badge = document.getElementById(`comment-count-badge-${contractId}`);
                                    if (badge) {
                                        badge.style.display = 'none';
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                });
                        });
                    });
                });
                
            </script>


<style>
    #account_no{
        color: grey;
        font-size: 25px;
    }
    #dotMenu:hover{
        cursor: pointer;
    }
</style>