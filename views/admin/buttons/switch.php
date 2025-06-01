<div class="d-flex justify-content-start mb-4">
    <?php

    $contractTypes = json_decode($_SESSION['contract_types'], true);

    if (!empty($contractTypes)) {
        foreach ($contractTypes as $type) {
            switch ($type) {
                case TEMP_LIGHTING:
                    include_once __DIR__ . "../../contract_buttons/temporary_lighting_button.php";
                    break;

                case TRANS_RENT:
                    include_once __DIR__ . "../../contract_buttons/Transformer_rental_button.php";
                    break;

                case EMP_CON:
                    include_once __DIR__ . "../../contract_buttons/employment_contract_button.php";
                    break;

                case GOODS:
                    include_once __DIR__ . "../../contract_buttons/goods_button.php";
                    break;

                case INFRA:
                    include_once __DIR__ . "../../contract_buttons/infra_button.php";
                    break;

                case SACC:
                    include_once __DIR__ . "../../contract_buttons/services_button.php";
                    break;

                case TEMP_LIGHTING:
                    include_once __DIR__ . "../../contract_buttons/temp_lighting_button.php";
                    break;

                case TRANS_RENT:
                    include_once __DIR__ . "../../contract_buttons/trans_rent_button.php";
                    break;

                case PSC_SHORT:
                    include_once __DIR__ . "../../contract_buttons/power_supply_short.php";
                    break;
                // case "Service and Consultancy Contract":
                //     // Add other includes if needed
                //     break 2;
    
                // case TRANS_RENT:
                //     include_once __DIR__ . "../../contract_buttons/Transformer_rental_button.php";
                //     break 2;
            }
        }
    }
    ?>
</div>