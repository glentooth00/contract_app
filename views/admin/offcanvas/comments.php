<?php 

use App\Controllers\UserController;
$department = $_SESSION['department'] ?? null;

?>
<!----- off canva for comments ------->

<div class="offcanvas offcanvas-start w-25 p-2" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Comments</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <hr>

<div class="offcanvas-body offcanvas-md">
    <?php foreach ($comments as $comment): ?>
        <?php 
            $commentUserId = $comment['user_id'] ?? $comment['audit_id'];
            $loggedInUserId = $user_id;

            // Get commenter user info
            $commentUser = (new UserController)->getUserById($commentUserId);

            // Logged-in user info (for department match)
            $loggedInUser = (new UserController)->getUserById($loggedInUserId);

            // Check if this is the logged-in user's comment
            $isOwnComment = ($commentUserId == $loggedInUserId);

            // Compare departments
            $sameDepartment = ( $commentUser['department'] === $loggedInUser['department']);

            // Set badge color by department
            $department = $commentUser['department'];
            switch ($department) {
                case 'IT': $badgeColor = '#0d6efd'; break;
                case 'ISD': $badgeColor = '#79d39d'; break;
                case 'CITET': $badgeColor = '#FFB433'; break;
                case 'IASD': $badgeColor = '#eb5b0047'; break;
                case 'ISD-MSD': $badgeColor = '#6A9C89'; break;
                case 'PSPTD': $badgeColor = '#83B582'; break;
                case 'FSD': $badgeColor = '#4E6688'; break;
                case 'BAC': $badgeColor = '#123458'; break;
                case 'AOSD': $badgeColor = '#03A791'; break;
                case 'GM': $badgeColor = '#A2D5C6'; break;
                default: $badgeColor = '#e0e0e0'; break;
            }

            // Alignment and style
            $alignment = $isOwnComment ? "flex-end" : "flex-start";
            $textAlign = $isOwnComment ? "right" : "left";

            $bubbleStyle = "
                background-color: {$badgeColor};
                padding: 10px;
                border-radius: 10px;
                width: 100%;
                text-align: {$textAlign};
            ";

            $user = $commentUser['firstname'] . ' ' . $commentUser['middlename'] . ' ' . $commentUser['lastname'];

        ?>

        <div class="d-flex" style="justify-content: <?= $alignment ?>; margin-bottom: 10px;">
            <div style="<?= $bubbleStyle ?>">
                <p><strong><?= htmlspecialchars($commentUser['firstname'] . ' ' . $commentUser['middlename'] . ' ' . $commentUser['lastname']) ?>:</strong></p>
                <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                <span class="badge text-muted"><small><?= date('M-d-Y h:i A', strtotime($comment['created_at'])) ?></small></span>
            </div>
        </div>
    <?php endforeach; ?>
</div>


    <form action="comments/comment.php" method="post">
        <input type="hidden" name="contract_id" value="<?= $contractId ?>">
        <input type="hidden" name="audit_id" value="<?= $user_id ?>">
        <input type="hidden" name="user_id" value="<?= $user_id ?>">
        <input type="hidden" name="user_department" value="<?= $user_department ?>">
        <hr>
        <div class="p-3">
            <textarea class="form-control" name="comment" rows="3" placeholder="Leave a comment..."></textarea>
        </div>
        <div class="p-3">
            <button type="submit" class="float-end" id="submitComment">Comment</button>
        </div>
    </form>
</div>
<!----- off canva for comments ------->