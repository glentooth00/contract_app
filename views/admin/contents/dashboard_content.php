<?php

$department = $_SESSION['department'] ?? null;
$role = $_SESSION['user_role'] ?? null;
$page_title = "List - $department";
$expired = 'Expired';
require_once __DIR__ . '../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../vendor/autoload.php';

use App\Controllers\ContractTypeController;
use App\Controllers\CommentController;
use App\Controllers\FlagController;

?>
<?php if (!empty($contracts)): ?>
    <div style="display:flex; flex-wrap:wrap; gap:20px;">
        <?php foreach ($contracts as $contract): ?>

            <?php
            $type = $contract['contract_type'] ?? '';

            switch ($type) {
                case INFRA:
                    $badgeColor = '#328E6E';
                    break;
                case SACC:
                    $badgeColor = '#123458';
                    break;
                case GOODS:
                    $badgeColor = '#F75A5A';
                    break;
                case EMP_CON:
                    $badgeColor = '#FAB12F';
                    break;
                case PSC_LONG:
                    $badgeColor = '#007bff';
                    break;
                case PSC_SHORT:
                    $badgeColor = '#28a745';
                    break;
                case TRANS_RENT:
                    $badgeColor = '#003092';
                    break;
                case TEMP_LIGHTING:
                    $badgeColor = '#03A791';
                    break;
                default:
                    $badgeColor = '#6c757d';
                    break;
            }
            ?>
            <?php $contractName = '<strong>' . $contract['contract_name'] . '</strong>'; ?>
            <a href="view.php?contract_id=<?= $contract['id'] ?>&type=<?= $contract['contract_type'] ?>"
                style="text-decoration:none;" data-bs-html="true" data-bs-toggle="popover" data-bs-trigger="hover focus"
                data-bs-placement="top" class="text-muted" data-bs-content="<?= $contractName ?>">
                <div style="
                    width:360px;
                    background:#ffffff;
                    border-radius:12px;
                    padding:20px;
                    box-shadow:0 6px 18px rgba(0,0,0,0.08);
                    transition:0.2s ease-in-out" onmouseover="this.style.transform='translateY(-4px)'"
                    onmouseout="this.style.transform='translateY(0)'">

                    <!-- Contract Title -->
                    <h5 style="font-weight:700; color:#212529; margin-bottom:10px;">

                        <?php
                        $contractName = $contract['contract_name'] ?? '';
                        $displayName = strlen($contractName) > 25 ? substr($contractName, 0, 25) . '...' : $contractName;

                        ?>

                        <?= htmlspecialchars($displayName) ?>
                        <?php
                        $contractId = $contract['id'];

                        $hasComment = (new CommentController)->hasComment($contractId);
                        ?>
                        <?php if ($hasComment == true): ?>
                            <span class="float-end">
                                <?php include_once 'message.php'; ?>
                            </span>
                        <?php endif; ?>

                        <?php if (isset($contract['id'])): ?>
                            <span class="p-3">
                                <?php
                                $id = $contract['id'];
                                $getFlag = (new FlagController)->getFlag($id);
                                ?>

                                <?php if ($getFlag['status'] ?? '' === 1): ?>

                                    <?php if ($getFlag['flag_type'] === UR): ?>
                                        <img src="../../../public/images/underReview.svg" id="review" width="25px;"
                                            title="This Contract is Under review">
                                    <?php endif; ?>

                                    <?php if ($getFlag['flag_type'] === NA): ?>
                                        <img src="../../../public/images/withComment.svg" id="attention" width="25px;"
                                            title="This Contract Needs Attention">
                                    <?php endif; ?>

                                <?php endif; ?>
                            </span>
                        <?php endif; ?>
                    </h5>

                    <!-- Contract Type Badge -->
                    <span class="badge" style="
                            background-color: <?= $badgeColor ?>;
                            color:#fff;
                            padding:5px;
                            font-size:11px;
                            border-radius:6px;
                            letter-spacing:0.5px;
                        ">
                        <?= htmlspecialchars($type) ?>
                    </span>



                    <hr style="margin:15px 0;">

                    <!-- Content Section -->
                    <div style="display:flex; align-items:center; gap:20px;">

                        <!-- Icon -->
                        <div style="
                            width:70px;
                            height:70px;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            background:#f8f9fa;
                            border-radius:10px;
                        ">
                            <img src="../../../public/images/doc.png" width="80" alt="Contract Icon">
                        </div>

                        <!-- Contract Dates -->
                        <div style="font-size:14px; color:#6c757d;">
                            <div style="margin-bottom:6px;">
                                <strong style="color:#343a40;">From:</strong>
                                <?= !empty($contract['contract_start']) ? date('F-d-Y', strtotime($contract['contract_start'])) : '' ?>
                            </div>
                            <div style="margin-bottom:6px;">
                                <strong style="color:#343a40;">To:</strong>
                                <?= !empty($contract['contract_end']) ? date('F-d-Y', strtotime($contract['contract_end'])) : '' ?>
                            </div>
                            <div>
                                <strong style="color:#343a40;">Status</strong>
                                <span class="badge" style="justify-content:center;background-color: #2FC762;padding:5px 10px;"
                                    role="alert">
                                    <?= htmlspecialchars($contract['contract_status']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php
                    $contractType = $contract['contract_type'];

                    $getFromContractType = (new ContractTypeController)->getContractTypeByDepartment($contractType);

                    foreach ($getFromContractType as $row) {

                        if ($contractType === $row['contract_type']) {

                            $end = new DateTime($contract['contract_end']);
                            $now = new DateTime();

                            $interval = $now->diff($end);
                            $diff = $interval->days;

                            $diff;

                        }

                    }
                    ?>

                    <?php switch ($contractType):
                        case EMP_CON: ?>
                            <!-- Code for EMP_CON -->
                            <span>
                                <?php
                                $getFromContractType = (new ContractTypeController)->getContractTypeByDepartment($contractType);

                                foreach ($getFromContractType as $row) {
                                    if ($contractType === $row['contract_type']) {
                                        $end = new DateTime($contract['contract_end']);
                                        $now = new DateTime();
                                        $ert = $row['contract_ert'];

                                        $interval = $now->diff($end);
                                        $diff = $interval->days;

                                        $expired = 'Expired';
                                        // $diff;
                
                                        if ($diff >= $ert) {
                                            echo '<div class="text-center text-danger ">
                                                            <span class="text-success fw-bold"> ' . $diff . ' days remaining </span>
                                                        </div>';
                                        } else {
                                            echo '
                                                    <div class="text-center p-2">
                                                        <span class="text-danger fw-bold position-relative"> 
                                                       <svg xmlns="http://www.w3.org/2000/svg" width="20" height="16" fill="currentColor" class="bi bi-exclamation-octagon" viewBox="0 0 16 16">
                                                        <path d="M4.54.146A.5.5 0 0 1 4.893 0h6.214a.5.5 0 0 1 .353.146l4.394 4.394a.5.5 0 0 1 .146.353v6.214a.5.5 0 0 1-.146.353l-4.394 4.394a.5.5 0 0 1-.353.146H4.893a.5.5 0 0 1-.353-.146L.146 11.46A.5.5 0 0 1 0 11.107V4.893a.5.5 0 0 1 .146-.353zM5.1 1 1 5.1v5.8L5.1 15h5.8l4.1-4.1V5.1L10.9 1z"/>
                                                        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                                                        </svg> ' . $expired . '</span>
                                                    </div>';

                                        }

                                    }
                                }
                                ?>
                            </span>
                            <?php break;
                        case PSC_LONG: ?>
                            <!-- Code for PSC_LONG -->
                            <!-- Code for EMP_CON -->
                            <span>
                                <?php
                                $getFromContractType = (new ContractTypeController)->getContractTypeByDepartment($contractType);

                                foreach ($getFromContractType as $row) {
                                    if ($contractType === $row['contract_type']) {
                                        $end = new DateTime($contract['contract_end']);
                                        $now = new DateTime();
                                        $ert = $row['contract_ert'];

                                        $interval = $now->diff($end);
                                        $diff = $interval->days;

                                        $expired = 'Expired';

                                        if ($diff >= $ert) {
                                            echo '<td class="text-center table-success">
                                                            <span class="text-success fw-bold">' . $diff . ' days remaining </span>
                                                        </td>';
                                        } elseif ($contract['contract_status'] === 'Expired') {
                                            echo '
                                                    <td class="text-center table-danger">
                                                        <span class="text-danger fw-bold"> 
                                                       <svg xmlns="http://www.w3.org/2000/svg" width="20" height="16" fill="currentColor" class="bi bi-exclamation-octagon" viewBox="0 0 16 16">
                                                        <path d="M4.54.146A.5.5 0 0 1 4.893 0h6.214a.5.5 0 0 1 .353.146l4.394 4.394a.5.5 0 0 1 .146.353v6.214a.5.5 0 0 1-.146.353l-4.394 4.394a.5.5 0 0 1-.353.146H4.893a.5.5 0 0 1-.353-.146L.146 11.46A.5.5 0 0 1 0 11.107V4.893a.5.5 0 0 1 .146-.353zM5.1 1 1 5.1v5.8L5.1 15h5.8l4.1-4.1V5.1L10.9 1z"/>
                                                        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                                                        </svg> ' . $expired . '</span>
                                                    </td>';
                                        } else {
                                            echo '
                                                    <div class=" p-0 mb-0" style="background-color:#f8d7da;border-radius:6px;">
                                                        <span class="text-danger fw-bold">' . $diff . ' days remaining </span>
                                                    </div>';
                                        }

                                    }
                                }
                                ?>
                            </span>
                            <?php break;
                        case PSC_SHORT: ?>
                            <!-- Code for PSC_SHORT -->
                            <!-- Code for EMP_CON -->
                            <span>
                                <?php
                                $getFromContractType = (new ContractTypeController)->getContractTypeByDepartment($contractType);

                                foreach ($getFromContractType as $row) {
                                    if ($contractType === $row['contract_type']) {
                                        $end = new DateTime($contract['contract_end']);
                                        $now = new DateTime();
                                        $ert = $row['contract_ert'];

                                        $interval = $now->diff($end);
                                        $diff = $interval->days;

                                        // $diff;
                
                                        if ($diff >= $ert) {
                                            echo '<td class="text-center table-success">
                                                            <span class="text-success fw-bold">' . $diff . ' days remaining </span>
                                                        </td>';
                                        } elseif ($contract['contract_status'] === 'Expired') {
                                            echo '
                                                    <td class="text-center table-danger">
                                                        <span class="text-danger fw-bold"> 
                                                       <svg xmlns="http://www.w3.org/2000/svg" width="20" height="16" fill="currentColor" class="bi bi-exclamation-octagon" viewBox="0 0 16 16">
                                                        <path d="M4.54.146A.5.5 0 0 1 4.893 0h6.214a.5.5 0 0 1 .353.146l4.394 4.394a.5.5 0 0 1 .146.353v6.214a.5.5 0 0 1-.146.353l-4.394 4.394a.5.5 0 0 1-.353.146H4.893a.5.5 0 0 1-.353-.146L.146 11.46A.5.5 0 0 1 0 11.107V4.893a.5.5 0 0 1 .146-.353zM5.1 1 1 5.1v5.8L5.1 15h5.8l4.1-4.1V5.1L10.9 1z"/>
                                                        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                                                        </svg> ' . $expired . '</span>
                                                    </td>';
                                        } else {
                                            echo '
                                                    <td class="text-center table-danger">
                                                        <span class="text-danger fw-bold">' . $diff . ' days remaining </span>
                                                    </td>';
                                        }

                                    }
                                }
                                ?>
                            </span>

                            <?php break;
                        case GOODS: ?>
                            <span>
                                <?php
                                $getFromContractType = (new ContractTypeController)->getContractTypeByDepartment($contractType);

                                foreach ($getFromContractType as $row) {
                                    if ($contractType === $row['contract_type']) {
                                        $end = new DateTime($contract['contract_end']);
                                        $now = new DateTime();
                                        $ert = $row['contract_ert'];

                                        $interval = $now->diff($end);
                                        $diff = $interval->days;

                                        // $diff;
                
                                        if ($diff >= $ert) {
                                            echo '<td class="text-center table-success">
                                                            <span class="text-success fw-bold">' . $diff . ' days remaining </span>
                                                        </td>';
                                        } elseif ($contract['contract_status'] === 'Expired') {
                                            echo '
                                                    <td class="text-center table-danger">
                                                        <span class="text-danger fw-bold"> 
                                                       <svg xmlns="http://www.w3.org/2000/svg" width="20" height="16" fill="currentColor" class="bi bi-exclamation-octagon" viewBox="0 0 16 16">
                                                        <path d="M4.54.146A.5.5 0 0 1 4.893 0h6.214a.5.5 0 0 1 .353.146l4.394 4.394a.5.5 0 0 1 .146.353v6.214a.5.5 0 0 1-.146.353l-4.394 4.394a.5.5 0 0 1-.353.146H4.893a.5.5 0 0 1-.353-.146L.146 11.46A.5.5 0 0 1 0 11.107V4.893a.5.5 0 0 1 .146-.353zM5.1 1 1 5.1v5.8L5.1 15h5.8l4.1-4.1V5.1L10.9 1z"/>
                                                        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                                                        </svg> ' . $expired . '</span>
                                                    </td>';
                                        } else {
                                            echo '
                                                    <td class="text-center table-danger">
                                                        <span class="text-danger fw-bold">' . $diff . ' days remaining </span>
                                                    </td>';
                                        }

                                    }
                                }
                                ?>
                            </span>

                            <?php break;
                        case INFRA: ?>
                            <!-- Code for PSC_SHORT -->
                            <!-- Code for EMP_CON -->
                            <span>
                                <?php
                                $getFromContractType = (new ContractTypeController)->getContractTypeByDepartment($contractType);

                                foreach ($getFromContractType as $row) {
                                    if ($contractType === $row['contract_type']) {
                                        $end = new DateTime($contract['contract_end']);
                                        $now = new DateTime();
                                        $ert = $row['contract_ert'];

                                        $interval = $now->diff($end);
                                        $diff = $interval->days;

                                        // $diff;
                
                                        if ($diff >= $ert) {
                                            echo '<td class="text-center table-success">
                                                            <span class="text-success fw-bold">' . $diff . ' days remaining </span>
                                                        </td>';
                                        } elseif ($contract['contract_status'] === 'Expired') {
                                            echo '
                                                    <td class="text-center table-danger">
                                                        <span class="text-danger fw-bold"> 
                                                       <svg xmlns="http://www.w3.org/2000/svg" width="20" height="16" fill="currentColor" class="bi bi-exclamation-octagon" viewBox="0 0 16 16">
                                                        <path d="M4.54.146A.5.5 0 0 1 4.893 0h6.214a.5.5 0 0 1 .353.146l4.394 4.394a.5.5 0 0 1 .146.353v6.214a.5.5 0 0 1-.146.353l-4.394 4.394a.5.5 0 0 1-.353.146H4.893a.5.5 0 0 1-.353-.146L.146 11.46A.5.5 0 0 1 0 11.107V4.893a.5.5 0 0 1 .146-.353zM5.1 1 1 5.1v5.8L5.1 15h5.8l4.1-4.1V5.1L10.9 1z"/>
                                                        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                                                        </svg> ' . $expired . '</span>
                                                    </td>';
                                        } else {
                                            echo '
                                                    <td class="text-center table-danger">
                                                        <span class="text-danger fw-bold">' . $diff . ' days remaining </span>
                                                    </td>';
                                        }

                                    }
                                }
                                ?>
                            </span>
                            <?php break;
                        case SACC: ?>
                            <!-- Code for PSC_SHORT -->
                            <!-- Code for EMP_CON -->
                            <span>
                                <?php
                                $getFromContractType = (new ContractTypeController)->getContractTypeByDepartment($contractType);

                                foreach ($getFromContractType as $row) {
                                    if ($contractType === $row['contract_type']) {
                                        $end = new DateTime($contract['contract_end']);
                                        $now = new DateTime();
                                        $ert = $row['contract_ert'];

                                        $interval = $now->diff($end);
                                        $diff = $interval->days;

                                        // $diff;
                
                                        if ($diff >= $ert) {
                                            echo '<td class="text-center table-success">
                                                            <span class="text-success fw-bold">' . $diff . ' days remaining </span>
                                                        </td>';
                                        } elseif ($contract['contract_status'] === 'Expired') {
                                            echo '
                                                    <td class="text-center table-danger">
                                                        <span class="text-danger fw-bold"> 
                                                       <svg xmlns="http://www.w3.org/2000/svg" width="20" height="16" fill="currentColor" class="bi bi-exclamation-octagon" viewBox="0 0 16 16">
                                                        <path d="M4.54.146A.5.5 0 0 1 4.893 0h6.214a.5.5 0 0 1 .353.146l4.394 4.394a.5.5 0 0 1 .146.353v6.214a.5.5 0 0 1-.146.353l-4.394 4.394a.5.5 0 0 1-.353.146H4.893a.5.5 0 0 1-.353-.146L.146 11.46A.5.5 0 0 1 0 11.107V4.893a.5.5 0 0 1 .146-.353zM5.1 1 1 5.1v5.8L5.1 15h5.8l4.1-4.1V5.1L10.9 1z"/>
                                                        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                                                        </svg> ' . $expired . '</span>
                                                    </td>';
                                        } else {
                                            echo '
                                                    <td class="text-center table-danger">
                                                        <span class="text-danger fw-bold">' . $diff . ' days remaining </span>
                                                    </td>';
                                        }

                                    }
                                }
                                ?>
                            </span>
                            <?php break;
                        case TEMP_LIGHTING: ?>
                            <span>
                                <?php
                                $getFromContractType = (new ContractTypeController)->getContractTypeByDepartment($contractType);

                                foreach ($getFromContractType as $row) {
                                    if ($contractType === $row['contract_type']) {
                                        $end = new DateTime($contract['contract_end']);
                                        $now = new DateTime();
                                        $ert = $row['contract_ert'];

                                        $interval = $now->diff($end);
                                        $diff = $interval->days;

                                        // $diff;
                
                                        if ($diff >= $ert) {
                                            echo '<td class="text-center table-success">
                                                            <span class="text-success fw-bold">' . $diff . ' days remaining </span>
                                                        </td>';
                                        } elseif ($contract['contract_status'] === 'Expired') {
                                            echo '
                                                    <td class="text-center table-danger">
                                                        <span class="text-danger fw-bold"> 
                                                       <svg xmlns="http://www.w3.org/2000/svg" width="20" height="16" fill="currentColor" class="bi bi-exclamation-octagon" viewBox="0 0 16 16">
                                                        <path d="M4.54.146A.5.5 0 0 1 4.893 0h6.214a.5.5 0 0 1 .353.146l4.394 4.394a.5.5 0 0 1 .146.353v6.214a.5.5 0 0 1-.146.353l-4.394 4.394a.5.5 0 0 1-.353.146H4.893a.5.5 0 0 1-.353-.146L.146 11.46A.5.5 0 0 1 0 11.107V4.893a.5.5 0 0 1 .146-.353zM5.1 1 1 5.1v5.8L5.1 15h5.8l4.1-4.1V5.1L10.9 1z"/>
                                                        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                                                        </svg> ' . $expired . '</span>
                                                    </td>';
                                        } else {
                                            echo '
                                                    <td class="text-center table-danger">
                                                        <span class="text-danger fw-bold">' . $diff . ' days remaining </span>
                                                    </td>';
                                        }
                                    }
                                }
                                ?>
                            </span>
                            <?php break;
                        default: ?>
                            <!-- Code if no match -->
                            <p>Unknown Contract Type</p>
                    <?php endswitch; ?>
                </div>
            </a>

        <?php endforeach; ?>
    </div>
    <div style="display:flex; justify-content:right; gap:6px; margin-top:25px;margin-right:15px;">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?searchItem=<?= urlencode($searchItem) ?>
             &filterItem=<?= urlencode($filterItem) ?>
             &page=<?= $i ?>" style="
            padding:6px 12px;
            border-radius:6px;
            text-decoration:none;
            background: <?= $i == $currentPage ? '#11488B' : '#f1f1f1' ?>;
            color: <?= $i == $currentPage ? '#fff' : '#000' ?>;
       ">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>

<?php else: ?>
    <div style="
        width:100%;
        padding:40px;
        text-align:center;
        background:#f8f9fa;
        border-radius:10px;
        color:#6c757d;
        font-size:15px;
    ">
        No contracts found.
    </div>

<?php endif; ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.forEach(function (popoverTriggerEl) {
            new bootstrap.Popover(popoverTriggerEl);
        });
    });
</script>