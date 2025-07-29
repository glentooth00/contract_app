<?php
use App\Controllers\SuspensionController;
use App\Controllers\ContractController;
session_start();

date_default_timezone_set('Asia/Manila'); // set this once at the top


require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '/../../../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $suspensionData = [
        'type_of_suspension' => $_POST['type_of_suspension'],
        'no_of_days' => $_POST['no_of_days'] ?? 0,
        'reason' => $_POST['reason'],
        'contract_id' => $_POST['contract_id'],
        'account_no' => $_POST['account_no'],
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
        'contract_start' => $_POST['contract_start'] ?? null,
        'contract_end' => $_POST['contract_end'] ?? null,
        'rent_start' => $_POST['rent_start'] ?? null,
        'rent_end' => $_POST['rent_end'] ?? null,
        'contract_type' => $_POST['contract_type'],
        'contract_status' => 'Suspended'
    ];

    var_dump($suspensionData );


    if ($suspensionData['contract_type'] === TRANS_RENT) {

        $newEndString = $suspensionData['rent_end'];
        $addTodate = !empty($suspensionData['no_of_days']) ? (int) $suspensionData['no_of_days'] : 0;

        if (!empty($newEndString)) {
            try {
                $newEnd = new DateTime($newEndString);
                $newEnd->add(new DateInterval("P{$addTodate}D"));
                $newContract_End = $newEnd->format("Y-m-d");
                $newContract_End;
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Invalid contract end date.";
        }

        $transRentData = [
            'contract_status' => 'Suspended',
            'id' => $_POST['contract_id'],
            'rent_end' => $newContract_End ?? null,
            'updated_at' => (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s')
        ];


        $iii = (new ContractController)->updateTransRentContractStatus($transRentData);

        if ($iii) {

            $suspension = (new SuspensionController)->saveSuspension($suspensionData);

            if ($suspension) {

                $_SESSION['notification'] = [
                    'message' => 'Contract successfully suspended saved!',
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

            }

        }


    }


    if ($suspensionData['contract_type'] === TEMP_LIGHTING) {

        $typeSus = $suspensionData['type_of_suspension'];

        if ($typeSus === DTD) {

            $newEndString = $suspensionData['contract_end'];

            $addTodate = !empty($suspensionData['no_of_days']) ? (int) $suspensionData['no_of_days'] : 0;

            if (!empty($newEndString)) {
                try {
                    $newEnd = new DateTime($newEndString);
                    $newEnd->add(new DateInterval("P{$addTodate}D"));
                    $newContract_End = $newEnd->format("Y-m-d");
                    $newContract_End;
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo "Invalid contract end date.";
            }

            $transRentData = [
                'contract_status' => 'Suspended',
                'id' => $_POST['contract_id'],
                'contract_end' => $newContract_End ?? null,
                'updated_at' => (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s')
            ];

            // print_r($transRentData);


            $ii = (new ContractController)->updateTempLightContractStatus($transRentData);

            if ($ii) {

                $suspensionData = [
                    'type_of_suspension' => $_POST['type_of_suspension'],
                    'no_of_days' => $_POST['no_of_days'] ?? 0,
                    'reason' => $_POST['reason'],
                    'contract_id' => $_POST['contract_id'],
                    'account_no' => $_POST['account_no'],
                    'created_at' => $now = date('Y-m-d H:i:s'),
                    'updated_at' => $now = date('Y-m-d H:i:s'),
                    'contract_start' => $_POST['contract_start'] ?? null,
                    'contract_end' => $_POST['contract_end'] ?? null,
                    'rent_start' => $_POST['rent_start'] ?? null,
                    'rent_end' => $_POST['rent_end'] ?? null,
                    'contract_type' => $_POST['contract_type'],
                    'contract_status' => 'Suspended'
                ];

                var_dump($suspensionData);



                $suspension = (new SuspensionController)->saveSuspension($suspensionData);

                if ($suspension) {

                    $_SESSION['notification'] = [
                        'message' => 'Contract successfully suspended saved!',
                        'type' => 'success'
                    ];

                    header("Location: " . $_SERVER['HTTP_REFERER']);

                }
            }
        }// END FOR DTD

        if ($typeSus === UNSAS) {

            $transRentData = [
                'contract_status' => 'Suspended',
                'id' => $_POST['contract_id'],
                'contract_end' => $_POST['contract_end'],
                'updated_at' => (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s')
            ];

            $o = (new ContractController)->updateTempLightContractStatus($transRentData);

            if ($o) {

                $suspensionData = [
                    'type_of_suspension' => $_POST['type_of_suspension'],
                    'no_of_days' => $_POST['no_of_days'] ?? 0,
                    'reason' => $_POST['reason'],
                    'contract_id' => $_POST['contract_id'],
                    'account_no' => $_POST['account_no'],
                    'created_at' => $now = date('Y-m-d H:i:s'),
                    'updated_at' => $now = date('Y-m-d H:i:s'),
                    'contract_start' => $_POST['contract_start'] ?? null,
                    'contract_end' => $_POST['contract_end'] ?? null,
                    'rent_start' => $_POST['rent_start'] ?? null,
                    'rent_end' => $_POST['rent_end'] ?? null,
                    'contract_type' => $_POST['contract_type'],
                    'contract_status' => 'Suspended'
                ];

                $suspension = (new SuspensionController)->saveSuspension($suspensionData);

                if ($suspension) {

                    $_SESSION['notification'] = [
                        'message' => 'Contract successfully suspended saved!',
                        'type' => 'success'
                    ];

                    header("Location: " . $_SERVER['HTTP_REFERER']);

                }

            }

        }

        if (isset($_POST['end_suspension'])) {
            echo 'end suspension button clicked';
        }






    }

    if ($suspensionData['contract_type'] === EMP_CON ) {

        $typeSus = $suspensionData['type_of_suspension'];

        if ($typeSus === DTD) {

            $newEndString = $suspensionData['contract_end'];

            $addTodate = !empty($suspensionData['no_of_days']) ? (int) $suspensionData['no_of_days'] : 0;

            if (!empty($newEndString)) {
                try {
                    $newEnd = new DateTime($newEndString);
                    $newEnd->add(new DateInterval("P{$addTodate}D"));
                    $newContract_End = $newEnd->format("Y-m-d");
                    $newContract_End;
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo "Invalid contract end date.";
            }

            $transRentData = [
                'contract_status' => 'Suspended',
                'id' => $_POST['contract_id'],
                'contract_end' => $newContract_End ?? null,
                'updated_at' => (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s')
            ];

            // print_r($transRentData);


            $ii = (new ContractController)->updateTempLightContractStatus($transRentData);

            if ($ii) {

                $suspensionData = [
                    'type_of_suspension' => $_POST['type_of_suspension'],
                    'no_of_days' => $_POST['no_of_days'] ?? 0,
                    'reason' => $_POST['reason'],
                    'contract_id' => $_POST['contract_id'],
                    'account_no' => $_POST['account_no'],
                    'created_at' => $now = date('Y-m-d H:i:s'),
                    'updated_at' => $now = date('Y-m-d H:i:s'),
                    'contract_start' => $_POST['contract_start'] ?? null,
                    'contract_end' => $_POST['contract_end'] ?? null,
                    'rent_start' => $_POST['rent_start'] ?? null,
                    'rent_end' => $_POST['rent_end'] ?? null,
                    'contract_type' => $_POST['contract_type'],
                    'contract_status' => 'Suspended'
                ];

                var_dump($suspensionData);



                $suspension = (new SuspensionController)->saveSuspension($suspensionData);

                if ($suspension) {

                    $_SESSION['notification'] = [
                        'message' => 'Contract successfully suspended saved!',
                        'type' => 'success'
                    ];

                    header("Location: " . $_SERVER['HTTP_REFERER']);

                }
            }

        }// END FOR DTD

        if ($typeSus === UNSAS) {

            $transRentData = [
                'contract_status' => 'Suspended',
                'id' => $_POST['contract_id'],
                'contract_end' => $_POST['contract_end'],
                'updated_at' => (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s')
            ];

            $o = (new ContractController)->updateTempLightContractStatus($transRentData);

            if ($o) {

                $suspensionData = [
                    'type_of_suspension' => $_POST['type_of_suspension'],
                    'no_of_days' => $_POST['no_of_days'] ?? 0,
                    'reason' => $_POST['reason'],
                    'contract_id' => $_POST['contract_id'],
                    'account_no' => $_POST['account_no'],
                    'created_at' => $now = date('Y-m-d H:i:s'),
                    'updated_at' => $now = date('Y-m-d H:i:s'),
                    'contract_start' => $_POST['contract_start'] ?? null,
                    'contract_end' => $_POST['contract_end'] ?? null,
                    'rent_start' => $_POST['rent_start'] ?? null,
                    'rent_end' => $_POST['rent_end'] ?? null,
                    'contract_type' => $_POST['contract_type'],
                    'contract_status' => 'Suspended'
                ];

                $suspension = (new SuspensionController)->saveSuspension($suspensionData);

                if ($suspension) {

                    $_SESSION['notification'] = [
                        'message' => 'Contract successfully suspended saved!',
                        'type' => 'success'
                    ];

                    header("Location: " . $_SERVER['HTTP_REFERER']);

                }

            }

        }

        if (isset($_POST['end_suspension'])) {
            echo 'end suspension button clicked';
    }
        
    }

    if ($suspensionData['contract_type'] === GOODS ) {

        $typeSus = $suspensionData['type_of_suspension'];

        if ($typeSus === DTD) {

            echo $newEndString = $suspensionData['contract_end'];

            $addTodate = !empty($suspensionData['no_of_days']) ? (int) $suspensionData['no_of_days'] : 0;

            if (!empty($newEndString)) {
                try {
                    $newEnd = new DateTime($newEndString);
                    $newEnd->add(new DateInterval("P{$addTodate}D"));
                    $newContract_End = $newEnd->format("Y-m-d");
                    $newContract_End;
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo "Invalid contract end date.";
            }

            $transRentData = [
                'contract_status' => 'Suspended',
                'id' => $_POST['contract_id'],
                'contract_end' => $newContract_End ?? null,
                'updated_at' => (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s')
            ];

            print_r($transRentData);


            $ii = (new ContractController)->updateTempLightContractStatus($transRentData);

            if ($ii) {

                $suspensionData = [
                    'type_of_suspension' => $_POST['type_of_suspension'],
                    'no_of_days' => $_POST['no_of_days'] ?? 0,
                    'reason' => $_POST['reason'],
                    'contract_id' => $_POST['contract_id'],
                    'account_no' => $_POST['account_no'],
                    'created_at' => $now = date('Y-m-d H:i:s'),
                    'updated_at' => $now = date('Y-m-d H:i:s'),
                    'contract_start' => $_POST['contract_start'] ?? null,
                    'contract_end' => $_POST['contract_end'] ?? null,
                    'rent_start' => $_POST['rent_start'] ?? null,
                    'rent_end' => $_POST['rent_end'] ?? null,
                    'contract_type' => $_POST['contract_type'],
                    'contract_status' => 'Suspended'
                ];

                var_dump($suspensionData);



                $suspension = (new SuspensionController)->saveSuspension($suspensionData);

                if ($suspension) {

                    $_SESSION['notification'] = [
                        'message' => 'Contract successfully suspended saved!',
                        'type' => 'success'
                    ];

                    header("Location: " . $_SERVER['HTTP_REFERER']);

                }
            }

        }// END FOR DTD

        if ($typeSus === UNSAS) {

            $transRentData = [
                'contract_status' => 'Suspended',
                'id' => $_POST['contract_id'],
                'contract_end' => $_POST['contract_end'],
                'updated_at' => (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s')
            ];

            $o = (new ContractController)->updateTempLightContractStatus($transRentData);

            if ($o) {

                $suspensionData = [
                    'type_of_suspension' => $_POST['type_of_suspension'],
                    'no_of_days' => $_POST['no_of_days'] ?? 0,
                    'reason' => $_POST['reason'],
                    'contract_id' => $_POST['contract_id'],
                    'account_no' => $_POST['account_no'],
                    'created_at' => $now = date('Y-m-d H:i:s'),
                    'updated_at' => $now = date('Y-m-d H:i:s'),
                    'contract_start' => $_POST['contract_start'] ?? null,
                    'contract_end' => $_POST['contract_end'] ?? null,
                    'rent_start' => $_POST['rent_start'] ?? null,
                    'rent_end' => $_POST['rent_end'] ?? null,
                    'contract_type' => $_POST['contract_type'],
                    'contract_status' => 'Suspended'
                ];

                $suspension = (new SuspensionController)->saveSuspension($suspensionData);

                if ($suspension) {

                    $_SESSION['notification'] = [
                        'message' => 'Contract successfully suspended saved!',
                        'type' => 'success'
                    ];

                    header("Location: " . $_SERVER['HTTP_REFERER']);

                }

            }

        }

        if (isset($_POST['end_suspension'])) {
            echo 'end suspension button clicked';
    }
        
    }

     if ($suspensionData['contract_type'] === INFRA ) {

        $typeSus = $suspensionData['type_of_suspension'];

        if ($typeSus === DTD) {

            echo $newEndString = $suspensionData['contract_end'];

            $addTodate = !empty($suspensionData['no_of_days']) ? (int) $suspensionData['no_of_days'] : 0;

            if (!empty($newEndString)) {
                try {
                    $newEnd = new DateTime($newEndString);
                    $newEnd->add(new DateInterval("P{$addTodate}D"));
                    $newContract_End = $newEnd->format("Y-m-d");
                    $newContract_End;
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo "Invalid contract end date.";
            }

            $transRentData = [
                'contract_status' => 'Suspended',
                'id' => $_POST['contract_id'],
                'contract_end' => $newContract_End ?? null,
                'updated_at' => (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s')
            ];

            print_r($transRentData);


            $ii = (new ContractController)->updateTempLightContractStatus($transRentData);

            if ($ii) {

                $suspensionData = [
                    'type_of_suspension' => $_POST['type_of_suspension'],
                    'no_of_days' => $_POST['no_of_days'] ?? 0,
                    'reason' => $_POST['reason'],
                    'contract_id' => $_POST['contract_id'],
                    'account_no' => $_POST['account_no'],
                    'created_at' => $now = date('Y-m-d H:i:s'),
                    'updated_at' => $now = date('Y-m-d H:i:s'),
                    'contract_start' => $_POST['contract_start'] ?? null,
                    'contract_end' => $_POST['contract_end'] ?? null,
                    'rent_start' => $_POST['rent_start'] ?? null,
                    'rent_end' => $_POST['rent_end'] ?? null,
                    'contract_type' => $_POST['contract_type'],
                    'contract_status' => 'Suspended'
                ];

                var_dump($suspensionData);



                $suspension = (new SuspensionController)->saveSuspension($suspensionData);

                if ($suspension) {

                    $_SESSION['notification'] = [
                        'message' => 'Contract successfully suspended saved!',
                        'type' => 'success'
                    ];

                    header("Location: " . $_SERVER['HTTP_REFERER']);

                }
            }

        }// END FOR DTD

        if ($typeSus === UNSAS) {

            $transRentData = [
                'contract_status' => 'Suspended',
                'id' => $_POST['contract_id'],
                'contract_end' => $_POST['contract_end'],
                'updated_at' => (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s')
            ];

            $o = (new ContractController)->updateTempLightContractStatus($transRentData);

            if ($o) {

                $suspensionData = [
                    'type_of_suspension' => $_POST['type_of_suspension'],
                    'no_of_days' => $_POST['no_of_days'] ?? 0,
                    'reason' => $_POST['reason'],
                    'contract_id' => $_POST['contract_id'],
                    'account_no' => $_POST['account_no'],
                    'created_at' => $now = date('Y-m-d H:i:s'),
                    'updated_at' => $now = date('Y-m-d H:i:s'),
                    'contract_start' => $_POST['contract_start'] ?? null,
                    'contract_end' => $_POST['contract_end'] ?? null,
                    'rent_start' => $_POST['rent_start'] ?? null,
                    'rent_end' => $_POST['rent_end'] ?? null,
                    'contract_type' => $_POST['contract_type'],
                    'contract_status' => 'Suspended'
                ];

                $suspension = (new SuspensionController)->saveSuspension($suspensionData);

                if ($suspension) {

                    $_SESSION['notification'] = [
                        'message' => 'Contract successfully suspended saved!',
                        'type' => 'success'
                    ];

                    header("Location: " . $_SERVER['HTTP_REFERER']);

                }

            }

        }

        if (isset($_POST['end_suspension'])) {
            echo 'end suspension button clicked';
    }
        
    }

}


// if ($_SERVER['REQUEST_METHOD'] === 'POST') {


//     $suspensionData = [
//         'type_of_suspension' => $_POST['type_of_suspension'],
//         'no_of_days' => $_POST['no_of_days'] ?? 0,
//         'reason' => $_POST['reason'],
//         'contract_id' => $_POST['contract_id'],
//         'account_no' => $_POST['account_no'],
//         'created_at' => date('Y-m-d H:i:s'),
//         'updated_at' => date('Y-m-d H:i:s'),
//         'contract_start' => $_POST['contract_start'] ?? null,
//         'contract_end' => $_POST['contract_end'] ?? null,
//         'rent_start' => $_POST['rent_start'] ?? null,
//         'rent_end' => $_POST['rent_end'] ?? null,
//     ];



//     // var_dump($suspensionData);

//     $ii = (new SuspensionController)->saveSuspension($suspensionData);

//     if ($ii) {

//         echo $newEndString = $suspensionData['contract_end'] ?? $suspensionData['rent_end'];
//         $addTodate = !empty($suspensionData['no_of_days']) ? (int) $suspensionData['no_of_days'] : 0;

//         if (!empty($newEndString)) {
//             try {
//                 $newEnd = new DateTime($newEndString);
//                 $newEnd->add(new DateInterval("P{$addTodate}D"));
//                 $newContract_End = $newEnd->format("Y-m-d");
//                 echo $newContract_End;
//             } catch (Exception $e) {
//                 echo "Error: " . $e->getMessage();
//             }
//         } else {
//             echo "Invalid contract end date.";
//         }


//         $updateContract = [
//             'contract_status' => 'Suspended',
//             'id' => $_POST['contract_id'],
//             'contract_start' => $_POST['contract_start'] ?? null,
//             'rent_start' => $_POST['rent_start'] ?? null,
//             'contract_end' => $newContract_End ?? null,
//             'rent_end' => $newContract_End ?? null,
//             'updated_at' => (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s')
//         ];

//         // var_dump($updateContract);




//         //     $updateStatus = (new ContractController)->updateContractStatus($updateContract);

//         //     if ($updateStatus) {

//         //         $_SESSION['notification'] = [
//         //             'message' => 'Contract successfully suspended saved!',
//         //             'type' => 'success'
//         //         ];

//         //         header("Location: " . $_SERVER['HTTP_REFERER']);

//         //     }


//         // } else {
//         //     echo 'save failed';
//     }




// }
// header("Location: " . $_SERVER['HTTP_REFERER']);




