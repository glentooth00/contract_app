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
                                        <img src="<?= image_source ?>../../../public/images/underReview.svg" id="review" width="25px;"
                                            title="This Contract is Under review">
                                    <?php endif; ?>

                                    <?php if ($getFlag['flag_type'] === NA): ?>
                                        <img src="<?= image_source ?>../../../public/images/withComment.svg" id="attention" width="25px;"
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
                            <img src="<?= image_source ?>../../../public/images/doc.png" width="80" alt="Contract Icon">
                        </div>

                        <!-- Contract Dates -->
                        <div style="font-size:14px; color:#6c757d;">
                            <div style="margin-bottom:6px;">
                                <strong style="color:#343a40;">From:</strong>
                                <?=
                                    ($date = $contract['contract_start'] ?? $contract['rent_start'] ?? null)
                                    ? date('F-d-Y', strtotime($date))
                                    : ''
                                    ?>
                            </div>
                            <div style="margin-bottom:6px;">
                                <strong style="color:#343a40;">To:</strong>
                                <?=
                                    ($date = $contract['contract_end'] ?? $contract['rent_end'] ?? null)
                                    ? date('F-d-Y', strtotime($date))
                                    : ''
                                    ?>
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
                    $contractType = $contract['contract_type'] ?? '';

                    // Determine correct end field
                    $endDateValue = $contract['contract_end'] ?? $contract['rent_end'] ?? null;

                    $isExpired = false;
                    $days = 0;

                    if (!empty($endDateValue)) {
                        $end = new DateTime($endDateValue);
                        $end->setTime(23, 59, 59); // expire at end of day
            
                        $now = new DateTime();

                        if ($now > $end) {
                            $isExpired = true;
                        } else {
                            $days = $now->diff($end)->days;
                        }
                    }

                    // Get ERT once
                    $typeConfig = (new ContractTypeController)
                        ->getContractTypeByDepartment($contractType);

                    $ert = 0;

                    foreach ($typeConfig as $row) {
                        if ($row['contract_type'] === $contractType) {
                            $ert = $row['contract_ert'];
                            break;
                        }
                    }
                    ?>

                    <?php switch ($contractType):
                        case EMP_CON: ?>
                            <!-- Code for EMP_CON -->
                            <span>
                                <?php
                                $contractType = $contract['contract_type'];

                                // Get ERT
                                $typeConfig = (new ContractTypeController)
                                    ->getContractTypeByDepartment($contractType);

                                $ert = 0;

                                foreach ($typeConfig as $row) {
                                    if ($row['contract_type'] === $contractType) {
                                        $ert = $row['contract_ert'];
                                        break;
                                    }
                                }

                                // Calculate remaining days
                                // Calculate remaining days
                                $days = 0;

                                if (!empty($contract['contract_end'])) {

                                    $end = new DateTime($contract['contract_end']);
                                    $end->setTime(23, 59, 59);

                                    $now = new DateTime();

                                    if ($now <= $end) {
                                        $days = $now->diff($end)->days + 1; // 👈 ADD 1 HERE
                                    }
                                }

                                // 🔥 Expiration rule
                                $isExpired =
                                    strtoupper($contract['status']) === 'EXPIRED'
                                    || $days === 0;
                                ?>

                                <div class="text-center mt-2">

                                    <?php if ($isExpired == 1): ?>

                                        <div class="p-2 text-white rounded fw-bold" style="background: #EB191998;">
                                            Expired
                                        </div>

                                    <?php elseif ($days >= $ert): ?>

                                        <div class="p-2 text-white rounded fw-bold" style="background: #58B94F;">
                                            <?= $days ?> days remaining
                                        </div>

                                    <?php else: ?>

                                        <div class="p-2 bg-warning rounded fw-bold" style="background-color: #FF9760;">
                                            <?= $days ?> days remaining
                                        </div>

                                    <?php endif; ?>

                                </div>
                            </span>
                            <?php break;
                        case PSC_LONG: ?>
                            <!-- Code for PSC_LONG -->
                            <!-- Code for EMP_CON -->
                            <span>
                                <?php
                                $contractType = $contract['contract_type'];

                                // Get contract type config
                                $typeConfig = (new ContractTypeController)
                                    ->getContractTypeByDepartment($contractType);

                                $ert = 0;

                                foreach ($typeConfig as $row) {
                                    if ($row['contract_type'] === $contractType) {
                                        $ert = $row['contract_ert'];
                                        break;
                                    }
                                }

                                $days = 0;
                                $isExpired = false;

                                if (!empty($contract['contract_end'])) {

                                    $end = new DateTime($contract['contract_end']);
                                    $end->setTime(23, 59, 59); // expire end of day
                
                                    $now = new DateTime();

                                    if ($now > $end) {
                                        $isExpired = true;
                                    } else {
                                        $days = $now->diff($end)->days + 1; // inclusive counting
                                    }
                                }

                                // Expire if status is manually set
                                if (strtoupper($contract['status']) === 'EXPIRED') {
                                    $isExpired = true;
                                }
                                ?>

                                <div class="text-center mt-2">

                                    <?php if ($isExpired): ?>

                                        <div class="p-2 text-white rounded fw-bold" style="background:#EB191998;">
                                            Expired
                                        </div>

                                    <?php elseif ($days >= $ert): ?>

                                        <div class="p-2 text-white rounded fw-bold" style="background:#58B94F;">
                                            <?= $days ?> days remaining
                                        </div>

                                    <?php else: ?>

                                        <div class="p-2 text-white rounded fw-bold" style="background:#FF9760;">
                                            <?= $days ?> days remaining
                                        </div>

                                    <?php endif; ?>

                                </div>
                            </span>
                            <?php break;
                        case PSC_SHORT: ?>
                            <!-- Code for PSC_SHORT -->
                            <!-- Code for EMP_CON -->
                            <span>
                                <?php
                                $contractType = $contract['contract_type'];

                                // Get contract type config ONCE
                                $typeConfig = (new ContractTypeController)
                                    ->getContractTypeByDepartment($contractType);

                                $ert = 0;

                                foreach ($typeConfig as $row) {
                                    if ($row['contract_type'] === $contractType) {
                                        $ert = $row['contract_ert'];
                                        break;
                                    }
                                }

                                // Date logic
                                $end = new DateTime($contract['contract_end']);
                                $now = new DateTime();

                                $interval = $now->diff($end);
                                $days = $interval->days;
                                $isExpired = $interval->invert; // 1 if expired
                                ?>

                                <div class="text-center mt-2">

                                    <?php if ($isExpired == 1): ?>

                                        <div class="p-2 text-white rounded fw-bold" style="background: #EB191998;">
                                            Expired
                                        </div>

                                    <?php elseif ($days >= $ert): ?>

                                        <div class="p-2 text-white rounded fw-bold" style="background: #58B94F;">
                                            <?= $days ?> days remaining
                                        </div>

                                    <?php else: ?>

                                        <div class="p-2 bg-warning rounded fw-bold" style="background-color: #FF9760;">
                                            <?= $days ?> days remaining
                                        </div>

                                    <?php endif; ?>

                                </div>
                            </span>
                            <?php break;
                        case GOODS: ?>
                            <span>
                                <?php
                                $contractType = $contract['contract_type'];

                                // Get contract type config ONCE
                                $typeConfig = (new ContractTypeController)
                                    ->getContractTypeByDepartment($contractType);

                                $ert = 0;

                                foreach ($typeConfig as $row) {
                                    if ($row['contract_type'] === $contractType) {
                                        $ert = $row['contract_ert'];
                                        break;
                                    }
                                }

                                // Date logic
                                $end = new DateTime($contract['contract_end']);
                                $now = new DateTime();

                                $interval = $now->diff($end);
                                $days = $interval->days;
                                $isExpired = $interval->invert; // 1 if expired
                                ?>

                                <div class="text-center mt-2">

                                    <?php if ($isExpired == 1): ?>

                                        <div class="p-2 text-white rounded fw-bold" style="background: #EB191998;">
                                            Expired
                                        </div>

                                    <?php elseif ($days >= $ert): ?>

                                        <div class="p-2 text-white rounded fw-bold" style="background: #58B94F;">
                                            <?= $days ?> days remaining
                                        </div>

                                    <?php else: ?>

                                        <div class="p-2 bg-warning rounded fw-bold" style="background-color: #FF9760;">
                                            <?= $days ?> days remaining
                                        </div>

                                    <?php endif; ?>

                                </div>
                            </span>
                            <?php break;
                        case INFRA: ?>
                            <!-- Code for PSC_SHORT -->
                            <!-- Code for EMP_CON -->
                            <span>
                                <?php
                                $contractType = $contract['contract_type'];

                                // Get contract type config ONCE
                                $typeConfig = (new ContractTypeController)
                                    ->getContractTypeByDepartment($contractType);

                                $ert = 0;

                                foreach ($typeConfig as $row) {
                                    if ($row['contract_type'] === $contractType) {
                                        $ert = $row['contract_ert'];
                                        break;
                                    }
                                }

                                // Date logic
                                $end = new DateTime($contract['contract_end']);
                                $now = new DateTime();

                                $interval = $now->diff($end);
                                $days = $interval->days;
                                $isExpired = $interval->invert; // 1 if expired
                                ?>

                                <div class="text-center mt-2">

                                    <?php if ($isExpired == 1): ?>

                                        <div class="p-2 text-white rounded fw-bold" style="background: #EB191998;">
                                            Expired
                                        </div>

                                    <?php elseif ($days >= $ert): ?>

                                        <div class="p-2 text-white rounded fw-bold" style="background: #58B94F;">
                                            <?= $days ?> days remaining
                                        </div>

                                    <?php else: ?>

                                        <div class="p-2 bg-warning rounded fw-bold" style="background-color: #FF9760;">
                                            <?= $days ?> days remaining
                                        </div>

                                    <?php endif; ?>

                                </div>
                            </span>
                            <?php break;
                        case SACC:
                            ?>
                            <span>
                                <?php
                                $contractType = $contract['contract_type'];

                                // Get contract type config
                                $typeConfig = (new ContractTypeController)
                                    ->getContractTypeByDepartment($contractType);

                                $ert = 0;

                                foreach ($typeConfig as $row) {
                                    if ($row['contract_type'] === $contractType) {
                                        $ert = $row['contract_ert'];
                                        break;
                                    }
                                }

                                // Date logic
                                $days = 0;
                                $isExpired = false;

                                $endDateValue = $contract['contract_end'] ?? null; // make sure you use the correct field
                
                                if (!empty($endDateValue)) {
                                    $end = new DateTime($endDateValue);
                                    $end->setTime(23, 59, 59);

                                    $now = new DateTime();

                                    // Expired if date passed OR status is EXPIRED
                                    if ($now > $end || strtoupper($contract['contract_status'] ?? '') === 'EXPIRED') {
                                        $isExpired = true;
                                    } else {
                                        $days = $now->diff($end)->days + 1; // inclusive counting
                                    }
                                }
                                ?>

                                <div class="text-center mt-2">

                                    <?php if ($isExpired): ?>
                                        <div class="p-2 text-white rounded fw-bold" style="background:#EB191998;">
                                            Expired
                                        </div>

                                    <?php elseif ($days >= $ert): ?>
                                        <div class="p-2 text-white rounded fw-bold" style="background:#58B94F;">
                                            <?= $days ?> days remaining
                                        </div>

                                    <?php else: ?>
                                        <div class="p-2 text-white rounded fw-bold" style="background:#FF9760;">
                                            <?= $days ?> days remaining
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </span>
                            <?php
                            break; // 🔥 THIS BREAK IS CRUCIAL
                        case TRANS_RENT: ?>
                            <span>
                                <?php
                                $contractType = $contract['contract_type'];

                                // Get contract type config ONCE
                                $typeConfig = (new ContractTypeController)
                                    ->getContractTypeByDepartment($contractType);

                                $ert = 0;

                                foreach ($typeConfig as $row) {
                                    if ($row['contract_type'] === $contractType) {
                                        $ert = $row['contract_ert'];
                                        break;
                                    }
                                }

                                // Date logic
                                $end = new DateTime($contract['rent_end']);
                                $now = new DateTime();

                                $interval = $now->diff($end);
                                $days = $interval->days;
                                $isExpired = $interval->invert; // 1 if expired
                                ?>

                                <div class="text-center mt-2">

                                    <?php if ($isExpired == 1): ?>

                                        <div class="p-2 text-white rounded fw-bold" style="background: #EB191998;">
                                            Expired
                                        </div>

                                    <?php elseif ($days >= $ert): ?>

                                        <div class="p-2 text-white rounded fw-bold" style="background: #58B94F;">
                                            <?= $days ?> days remaining
                                        </div>

                                    <?php else: ?>

                                        <div class="p-2 bg-warning rounded fw-bold" style="background-color: #FF9760;">
                                            <?= $days ?> days remaining
                                        </div>

                                    <?php endif; ?>

                                </div>
                            </span><?php break;
                        case TEMP_LIGHTING: ?>
                            <span>
                                <?php
                                $contractType = $contract['contract_type'];

                                // Get contract type config ONCE
                                $typeConfig = (new ContractTypeController)
                                    ->getContractTypeByDepartment($contractType);

                                $ert = 0;

                                foreach ($typeConfig as $row) {
                                    if ($row['contract_type'] === $contractType) {
                                        $ert = $row['contract_ert'];
                                        break;
                                    }
                                }

                                // Date logic
                                $end = new DateTime($contract['contract_end']);
                                $now = new DateTime();

                                $interval = $now->diff($end);
                                $days = $interval->days;
                                $isExpired = $interval->invert; // 1 if expired
                                ?>

                                <div class="text-center mt-2">

                                    <?php if ($isExpired == 1): ?>

                                        <div class="p-2 text-white rounded fw-bold" style="background: #EB191998;">
                                            Expired
                                        </div>

                                    <?php elseif ($days >= $ert): ?>

                                        <div class="p-2 text-white rounded fw-bold" style="background: #58B94F;">
                                            <?= $days ?> days remaining
                                        </div>

                                    <?php else: ?>

                                        <div class="p-2 bg-warning rounded fw-bold" style="background-color: #FF9760;">
                                            <?= $days ?> days remaining
                                        </div>

                                    <?php endif; ?>

                                </div>
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