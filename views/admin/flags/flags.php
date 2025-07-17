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
                <img src="../../../public/images/underReview.svg" id="review" width="30px;" title="This Contract is Under review">
            <?php endif;  ?>

            <?php if($getFlag['flag_type'] === NA): ?>
                <img src="../../../public/images/withComment.svg" id="attention" width="30px;" title="This Contract Needs Attention">
            <?php endif;  ?>
            
        <?php endif; ?>
    </span>
<?php endif; ?>

<style>
    #attention, #review:hover{
        cursor: pointer;
    }
</style>