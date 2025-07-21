<?php 
use App\Controllers\CommentController;

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
            <!-- Comment icon with badge -->
            <div id="viewComment" class="position-relative">
                <?php if ($hasCommentCount > 0): ?>
                    <span id="comment-count-badge-<?= $getContract['id'] ?>"
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                        style="font-size: 14px;">
                        <?= $hasCommentCount; ?>
                    </span>
                <?php endif; ?>
                <img
                    src="../../../public/images/viewComment.svg"
                    width="33px"
                    alt="This Contract has comment!"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasWithBothOptions"
                    aria-controls="offcanvasWithBothOptions"
                    data-contract-id="<?= $getContract['id'] ?>"
                    data-audit-id="<?= $user_id ?>"
                    data-user-id="<?= $user_id ?>"
                    data-department="<?= $user_department ?>"
                    class="view-comment-trigger"
                />
            </div>
            <!-- Three-dot dropdown -->
            <div class="dotMenu" onclick="toggleView()" id="dotMenu">
                <img src="../../../public/images/dotMenu.svg" width="25px">
                <div id="dropMenu">
                    <ul>
                        <li>
                            <a href="" class="w-100" data-toggle="modal" data-target="#suspendModal"><img  src="../../../public/images/suspendFile.svg" width="20px"><small id="" class="p-2 mb-5">Suspend Contract</small></a>
                        </li>
                        <li>
                            <a href="" class="w-100" data-toggle="modal" data-target="#suspendModal"><img  src="../../../public/images/bell.svg" width="20px"><small id=""  class="p-2 mb-5">Notification</small></a>
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
</style>