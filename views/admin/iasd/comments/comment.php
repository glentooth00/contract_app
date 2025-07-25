<?php 
use App\Controllers\CommentController;

session_start();

require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../../vendor/autoload.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if($_POST['user_department'] === IASD){
        
        $commentData = [
            'department' => $_POST['user_department'],
            'contract_id' => $_POST['contract_id'],
            'audit_id' => $_POST['audit_id'],
            'comment' => $_POST['comment'],
            'comment_id' => $_POST['contract_id'],
            'status' => '1',
            'username' => $_POST['user_name'],
        ];

        $saveComment = ( new CommentController )->saveComment($commentData);

        if($saveComment){

            
            $_SESSION['notification'] = [
                'message' => 'Comment submitted successfully!',
                'type' => 'success'
            ];

            header("Location: " . $_SERVER['HTTP_REFERER']);

        }

    }//end of if user_department is IASD

        if(in_array($_POST['user_department'], [GM, IT, ISD, CITET, BAC, FSD, AOSD, CHIEF, 'ISD-MSD', CHIEF])){
            // echo 'other dept';
        $commentData = [
            'department' => $_POST['user_department'],
            'contract_id' => $_POST['contract_id'],
            'user_id' => $_POST['audit_id'],
            'comment' => $_POST['comment'],
            'comment_id' => $_POST['contract_id'],
            'status' => '1',
            'username' => $_POST['user_name'],
        ];

        $saveComment = ( new CommentController )->saveCommentForUser($commentData);

        if($saveComment){

            
            $_SESSION['notification'] = [
                'message' => 'Comment submitted successfully!',
                'type' => 'success'
            ];

            header("Location: " . $_SERVER['HTTP_REFERER']);

        }

    }//end of if user_department is IASD

}
// header("Location: " . $_SERVER['HTTP_REFERER']);

