<?php 
use App\Controllers\FlagController;
session_start();

require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../../vendor/autoload.php';

if($_SERVER["REQUEST_METHOD"] === 'POST'){

    $flags = [
        'contract_id' => $_POST['contract_id'],
        'review' => $_POST['review'] ?? '',
        'attention' => $_POST['attention'] ?? ''
    ];

    if($flags['attention'] === 'on'){
        
        $flagData = [
            'contract_id' => $_POST['contract_id'],
            'flag_type' => 'Needs Attention',
            'status' => 1
        ];

        $flagContract =  ( new FlagController )->flagContract($flagData);

            if($flagContract){

            $_SESSION['notification'] = [

                'message' => 'Contract has been Flagged! This Needs attention.',
                'type' => 'success'
            ];

            header("Location: " . $_SERVER['HTTP_REFERER']);

            }

    }

    if($flags['review'] === 'on'){

        $flagData = [
            'contract_id' => $_POST['contract_id'],
            'flag_type' => 'Under Review',
            'status' => 1
        ];

        $flagContract =  ( new FlagController )->flagContract($flagData);

        if($flagContract){

            $_SESSION['notification'] = [

                'message' => 'Contract has been Flagged! This is Under Review.',
                'type' => 'success'
            ];

            header("Location: " . $_SERVER['HTTP_REFERER']);

            }

    }

}





