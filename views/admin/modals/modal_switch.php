<?php

$contractTypes = json_decode($_SESSION['contract_types'], true);

if (!empty($contractTypes)) {
    foreach ($contractTypes as $type) {
        switch ($type) {
            case TEMP_LIGHTING:
                include_once 'isdmsd_modal.php';
                break;

            case TRANS_RENT:
                include_once 'transformer_rental.php';
                break;

            case EMP_CON:
                include_once 'hrad_modal.php';
                break;

            case GOODS:
                include_once 'goods_modal.php';
                break;

            case INFRA:
                include_once 'infra_modal.php';
                break;

            case SACC:
                include_once 'services_modal.php';
                break;

            case PSC_SHORT:
                include_once 'power_supply.php';
                break;
            // case "Service and Consultancy Contract":
            //     // Add other includes if needed
            //     break 2;

            // case TRANS_RENT:
            //     include_once __DIR__ . "../../contrcat_buttons/Transformer_rental_button.php";
            //     break 2;
        }
    }
}
?>