<?php
use App\Controllers\SuspensionController;
use App\Controllers\ContractController;
session_start();

date_default_timezone_set('Asia/Manila'); // set this once at the top


require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '/../../../../vendor/autoload.php';

$endSuspensionData = [
    'contract_status' => 'Active',
    'account_no' => $_POST['account_no'] ?? '',
    'contract_id' => $_POST['contract_id'] ?? '',
    'contract_type' => $_POST['contract_type'] ?? '',
    'type_of_suspension' => $_POST['type_of_suspension'] ?? '',
    'updated_at' => $_POST['updated_at'] ?? '',
    'rent_end' => $_POST['rent_end'] ?? null,
    'contract_end' => $_POST['contract_end'] ?? null,
];

if (isset($_POST['end_suspension'])) {

    if ($endSuspensionData['contract_type'] === TEMP_LIGHTING) {


        $endSuspensionData = [
            'contract_status' => 'Active',
            'account_no' => $_POST['account_no'],
            'contract_id' => $_POST['contract_id'],
            'contract_type' => $_POST['contract_type'],
            'type_of_suspension' => $_POST['type_of_suspension'] ?? '',
            'updated_at' => $_POST['updated_at'],
            'rent_end' => $_POST['rent_end'] ?? null,
            'contract_end' => $_POST['contract_end'] ?? null,
        ];

        $id = $endSuspensionData['contract_id'];


        $start = strtotime(substr($endSuspensionData['updated_at'], 0, 19));
        $end = time();
        $diff = $end - $start;

        $days = floor($diff / 86400);
        // $days = 10;
        $hours = floor(($diff % 86400) / 3600);
        $minutes = floor(($diff % 3600) / 60);
        $seconds = $diff % 60;

        // echo "$days day(s), $hours hour(s), $minutes minute(s), $seconds second(s)";

        $contract_end = new DateTime($endSuspensionData['contract_end']);
        $contract_end->add(new DateInterval("P{$days}D"));
        $newContract_End = $contract_end->format("Y-m-d");

        $newContractUpdate = [
            'contract_end' => $newContract_End,
            'id' => $endSuspensionData['contract_id'],
            'contract_status' => 'Active',
        ];

        $cancelSuspension = (new contractController)->updateSuspension($newContractUpdate);

        if ($cancelSuspension) {

            echo $id = $newContractUpdate['id'];

            $deleteSuspension = (new SuspensionController)->deleteSuspension($id);

            if ($deleteSuspension) {
                $_SESSION['notification'] = [
                    'message' => "Contract successfully resumed! Remaining days: $remaining_days",
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
            }
        }

    }

    if ($endSuspensionData['contract_type'] === TRANS_RENT) {


        $endSuspensionData = [
            'contract_status' => 'Active',
            'account_no' => $_POST['account_no'],
            'contract_id' => $_POST['contract_id'],
            'contract_type' => $_POST['contract_type'],
            'type_of_suspension' => $_POST['type_of_suspension'] ?? '',
            'updated_at' => $_POST['updated_at'],
            'rent_end' => $_POST['rent_end'] ?? null,
            'contract_end' => $_POST['contract_end'] ?? null,
        ];

        $start = strtotime(substr($endSuspensionData['updated_at'], 0, 19));
        $end = time();
        $diff = $end - $start;

        $days = floor($diff / 86400);
        // $days = 10;
        $hours = floor(($diff % 86400) / 3600);
        $minutes = floor(($diff % 3600) / 60);
        $seconds = $diff % 60;

        // echo "$days day(s), $hours hour(s), $minutes minute(s), $seconds second(s)";

        $contract_end = new DateTime($endSuspensionData['rent_end']);
        $contract_end->add(new DateInterval("P{$days}D"));
        $newContract_End = $contract_end->format("Y-m-d");

        $newContractUpdate = [
            'rent_end' => $newContract_End,
            'id' => $endSuspensionData['contract_id'],
            'contract_status' => 'Active',
        ];

        $cancelSuspension = (new contractController)->updateTransRentSuspension($newContractUpdate);

        if ($cancelSuspension) {

            $id = $newContractUpdate['id'];

            $deleteSuspension = (new SuspensionController)->deleteSuspension($id);

            if ($deleteSuspension) {
                $_SESSION['notification'] = [
                    'message' => "Contract successfully resumed! Remaining days: $remaining_days",
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
            }
        }

    }


} elseif (isset($_POST['terminate'])) {

    echo 'YO';

    $directory = "termination/";
    echo $file = $_FILES["document"]["name"];



}


