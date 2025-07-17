<?php 
use App\Controllers\FlagController;
?>

<?php if(isset($contractId)): ?>
    <span class="p-3">
        <?php
            $id = $contractId;
            $getFlag = ( new FlagController )->getFlag($id);
        ?>

        <?php if( $getFlag['status'] ?? '' === 1 ): ?>
            
            <?php if($getFlag['flag_type'] === UR): ?>
                <img src="../../../public/images/underReview.svg" id="review" data-toggle="modal" data-target="#removeFlagModal" width="30px;" title="This Contract is Under review">
            <?php endif;  ?>

            <?php if($getFlag['flag_type'] === NA): ?>
                <img src="../../../public/images/withComment.svg" id="attention" data-toggle="modal" data-target="#removeFlagModal" width="30px;" title="This Contract Needs Attention">
            <?php endif;  ?>
            
        <?php endif; ?>
    </span>
<?php endif; ?>

        <!-- Modal -->
        <div class="modal fade" id="removeFlagModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Remove Flag?</h5>
            </div>
            <div class="modal-body">
                <ul>
                    <li>Did you made the necessary changes?</li>
                </ul>
            </div>
            <form action="removeFlag.php" method="POST">

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Remove flag</button>
                </div>
            </form>
            </div>
        </div>
        </div>

<style>
    #attention, #review:hover{
        cursor: pointer;
    }
</style>