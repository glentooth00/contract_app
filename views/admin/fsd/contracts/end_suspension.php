<?php

use App\Controllers\ContractController;
use App\Controllers\SuspensionController;
session_start();

require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $remainingDays = $_POST['remaining_days'] ?? 0;
    $remainingHours = $_POST['remaining_hours'] ?? 0;

    $endSuspensionData = [
        'contract_status' => 'Active',
        'account_no' => $_POST['account_no'],
        'contract_id' => $_POST['contract_id'],
        'contract_type' => $_POST['contract_type'],
        'type_of_suspension' => $_POST['type_of_suspension'] ?? '',
        'updated_at' => $_POST['updated_at'] ?? '' ,
        'rent_end' => $_POST['rent_end'] ?? null,
        'contract_end' => $_POST['contract_end'] ?? null,
    ];

    $type = $endSuspensionData['contract_type'];

    if ($remainingDays > 0) {

        if ($remainingHours > 11) {

            echo $forDeduction = $remainingDays + 1;

            $getContract = (new ContractController)->getContract($endSuspensionData);

            $contract_end = date_create($getContract['contract_end']);
            date_sub($contract_end, date_interval_create_from_date_string("$forDeduction days"));
            $returnDate = $contract_end->format('Y-m-d'); // properly formatted date string

            $resumeContractData = [
                'contract_end' => $returnDate,
                'id' => $endSuspensionData['contract_id'],
                'contract_status' => 'Active',
            ];


            $resumeContract = (new ContractController)->updateSuspension($resumeContractData);

            if ($resumeContract) {

                $id = $resumeContractData['id'];

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

}

        if ($remainingDays == 0) {

            $forDeduction = 1;

            $getContract = (new ContractController)->getContract($endSuspensionData);

            $contract_end = date_create($getContract['contract_end'] ?? $getContract['rent_end']);
            date_sub($contract_end, date_interval_create_from_date_string("$forDeduction days"));
            $returnDate = $contract_end->format('Y-m-d'); // properly formatted date string

        if ($type === TRANS_RENT) {

            $resumeContractData = [

                'rent_end' => $returnDate,
                'id' => $endSuspensionData['contract_id'],
                'contract_status' => 'Active',
            ];


            $resumeContract = (new ContractController)->updateTransRentSuspension($resumeContractData);

            if ($resumeContract) {

                $id = $resumeContractData['id'];

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

        if ($type === TEMP_LIGHTING) {

            $resumeContractData = [
                'contract_end' => $returnDate,
                'id' => $endSuspensionData['contract_id'],
                'contract_status' => 'Active',
            ];

            $resumeContract = (new ContractController)->updateSuspension($resumeContractData);

            if ($resumeContract) {

                $id = $resumeContractData['id'];

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

        if ($type === EMP_CON) {

            $resumeContractData = [
                'contract_end' => $returnDate,
                'id' => $endSuspensionData['contract_id'],
                'contract_status' => 'Active',
            ];

            // var_dump($resumeContractData);

            $resumeContract = (new ContractController)->updateSuspension($resumeContractData);

            if ($resumeContract) {

                $id = $resumeContractData['id'];

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

        if ($type === GOODS) {

            $resumeContractData = [
                'contract_end' => $returnDate,
                'id' => $endSuspensionData['contract_id'],
                'contract_status' => 'Active',
            ];

            // var_dump($resumeContractData);

            $resumeContract = (new ContractController)->updateSuspension($resumeContractData);

            if ($resumeContract) {

                $id = $resumeContractData['id'];

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

        if ($type === SACC) {

            $resumeContractData = [
                'contract_end' => $returnDate,
                'id' => $endSuspensionData['contract_id'],
                'contract_status' => 'Active',
            ];

            // var_dump($resumeContractData);

            $resumeContract = (new ContractController)->updateSuspension($resumeContractData);

            if ($resumeContract) {

                $id = $resumeContractData['id'];

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

        if ($type === INFRA) {

            $resumeContractData = [
                'contract_end' => $returnDate,
                'id' => $endSuspensionData['contract_id'],
                'contract_status' => 'Active',
            ];

            // var_dump($resumeContractData);

            $resumeContract = (new ContractController)->updateSuspension($resumeContractData);

            if ($resumeContract) {

                $id = $resumeContractData['id'];

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

        if ($type === PSC_LONG) {

            $resumeContractData = [
                'contract_end' => $returnDate,
                'id' => $endSuspensionData['contract_id'],
                'contract_status' => 'Active',
            ];

            // var_dump($resumeContractData);

            $resumeContract = (new ContractController)->updateSuspension($resumeContractData);

            if ($resumeContract) {

                $id = $resumeContractData['id'];

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

        if ($type === PSC_SHORT) {

            $resumeContractData = [
                'contract_end' => $returnDate,
                'id' => $endSuspensionData['contract_id'],
                'contract_status' => 'Active',
            ];

            // var_dump($resumeContractData);

            $resumeContract = (new ContractController)->updateSuspension($resumeContractData);

            if ($resumeContract) {

                $id = $resumeContractData['id'];

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
    }
}
