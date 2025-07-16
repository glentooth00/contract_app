<?php 

use App\Controllers\UserController;

?>
<!-- Off canvas ---->

        <div class="offcanvas offcanvas-start w-25 p-2" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">Comments</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
             <hr>
            
            <div class="offcanvas-body offcanvas-md">
              <?php foreach ($comments as $comment): ?>
                <?php 
                    $auditID = $comment['audit_id'];
                    $userID = $comment['user_id'];
                    $auditName = (new UserController)->getUserById($auditID);
                    $userName = (new UserController)->getUserById($userID);
                ?>
                
                <div class="comment" style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                    
                    <!-- Left: Audit side -->
                    <?php if($auditName): ?>
                        <div style="flex: 1; text-align: left;background-color: #cefbc7;padding: 10px;border-radius: 10px;">
                            <p><strong><?= htmlspecialchars($auditName['firstname'].' '.$auditName['middlename'].' '.$auditName['lastname']) ?>:</strong></p>
                            <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                            <span class="badge text-muted"><small><?= date('M-D-Y H:i A', strtotime($comment['created_at'])); ?></small></span>
                        </div>
                    <?php endif; ?>

                    <!-- Right: User side -->
                    <?php if($userName): ?>
                        <div style="flex: 1;text-align: right;background-color: #ffcf6d7d;padding: 10px;border-radius: 10px;"">
                            <p><strong><?= htmlspecialchars($userName['firstname'].' '.$userName['middlename'].' '.$userName['lastname']) ?>:</strong></p>
                            <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                            <span class="badge text-muted"><small><?= date('M-D-Y h:i A', strtotime($comment['created_at'])); ?></small></span>
                        </div>
                    <?php endif; ?>
                    
                </div>
            <?php endforeach; ?>

                <!----comments display here ----->

            </div>

            <form action="comments/comment.php" method="post">
                <input type="hidden" id="contractID" value="<?= $contractId ?>" name="contract_id">
                <input type="hidden" id="auditId" value="<?= $user_id ?>" name="audit_id">
                <input type="hidden" id="userId" value="<?= $user_id ?>" name="user_id">
                <input type="hidden" id="userDepartment" value="<?= $user_department ?>" name="user_department">
                <hr>
                <div class="p-3">
                    <textarea class="form-control" name="comment" id="commentTextArea" rows="3" placeholder="Leave a comment..."></textarea>
                </div>
                <div class="p-3">
                <button type="submit" class="float-end" id="submitComment">Comment</button> 
                </div>
            </form>
            </div>


        <!---- Off canva ----->