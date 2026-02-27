<?php
$department = $_SESSION['department'] ?? null;
$role = $_SESSION['user_role'] ?? null;
$page_title = "List - $department";

require_once __DIR__ . '../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../vendor/autoload.php';

use App\Controllers\ContractTypeController;
use App\Controllers\CommentController;
use App\Controllers\FlagController;

// Helper: calculate remaining days & expired status
function calculateContractStatus($contract)
{
    $endDateValue = $contract['contract_end'] ?? $contract['rent_end'] ?? null;
    $days = 0;
    $isExpired = false;

    if (!empty($endDateValue)) {
        $end = new DateTime($endDateValue);
        $end->setTime(23, 59, 59);
        $now = new DateTime();

        if ($now > $end) {
            $isExpired = true;
        } else {
            $days = $now->diff($end)->days + 1; // inclusive
        }
    }

    if (strtoupper($contract['contract_status'] ?? '') === 'EXPIRED') {
        $isExpired = true;
    }

    return ['isExpired' => $isExpired, 'days' => $days];
}

// Badge colors
$badgeColors = [
    INFRA => '#328E6E',
    SACC => '#123458',
    GOODS => '#F75A5A',
    EMP_CON => '#FAB12F',
    PSC_LONG => '#007bff',
    PSC_SHORT => '#28a745',
    TRANS_RENT => '#003092',
    TEMP_LIGHTING => '#03A791'
];
?>

<?php if (!empty($contracts)): ?>
    <div class="contract-grid">
        <?php foreach ($contracts as $contract): ?>
            <?php
            $type = $contract['contract_type'] ?? '';
            $contractId = $contract['id'] ?? 0;

            // Badge color fallback
            $badgeColor = $badgeColors[$type] ?? '#6c757d';

            // Fetch ERT config once
            $typeConfig = (new ContractTypeController())->getContractTypeByDepartment($type);
            $ert = 0;
            foreach ($typeConfig as $row) {
                if ($row['contract_type'] === $type) {
                    $ert = (int) $row['contract_ert'];
                    break;
                }
            }

            $statusData = calculateContractStatus($contract);
            $isExpired = $statusData['isExpired'];
            $days = $statusData['days'];

            // Popover content
            $popoverContent = '<strong>' . htmlspecialchars($contract['contract_name'] ?? '') . '</strong>';

            // Comment
            $hasComment = (new CommentController())->hasComment($contractId);

            // Flag
            $flagData = (new FlagController())->getFlag($contractId);
            ?>
            <a href="view.php?contract_id=<?= $contractId ?>&type=<?= $type ?>" class="contract-card-link"
                data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-html="true" data-bs-placement="top"
                data-bs-content="<?= $popoverContent ?>">
                <div class="contract-card">
                    <!-- Header -->
                    <div class="contract-header">
                        <h4 class="contract-title">
                            <?php
                            $displayName = $contract['contract_name'] ?? '';
                            $displayName = strlen($displayName) > 20 ? substr($displayName, 0, 20) . '...' : $displayName;
                            ?>
                            <?= htmlspecialchars($displayName) ?>
                            <?php if ($hasComment): ?>
                                <span class="float-end">
                                    <?php include_once 'message.php'; ?>
                                </span>
                            <?php endif; ?>

                            <?php if (($flagData['status'] ?? '') === 1): ?>
                                <span class="float-end ms-1">
                                    <?php if ($flagData['flag_type'] === UR): ?>
                                        <img src="<?= image_source ?>../../../public/images/underReview.svg" width="20"
                                            title="Under Review">
                                    <?php elseif ($flagData['flag_type'] === NA): ?>
                                        <img src="<?= image_source ?>../../../public/images/withComment.svg" width="20"
                                            title="Needs Attention">
                                    <?php endif; ?>
                                </span>
                            <?php endif; ?>
                            </h6>
                            <span class="badge contract-type-badge" style="background-color: <?= $badgeColor ?>;">
                                <?= htmlspecialchars($type) ?>
                            </span>
                    </div>

                    <hr>

                    <!-- Content Section -->
                    <div class="contract-content">
                        <div class="contract-icon">
                            <img src="<?= image_source ?>../../../public/images/doc.png" alt="Contract Icon">
                        </div>
                        <div class="contract-dates">
                            <div><strong>From:</strong>
                                <?= ($date = $contract['contract_start'] ?? $contract['rent_start'] ?? null)
                                    ? date('M d, Y', strtotime($date))
                                    : '-' ?>
                            </div>
                            <div><strong>To:</strong>
                                <?= ($date = $contract['contract_end'] ?? $contract['rent_end'] ?? null)
                                    ? date('M d, Y', strtotime($date))
                                    : '-' ?>
                            </div>
                            <div><strong>Status:</strong>
                                <span class="badge contract-status-badge <?= $isExpired ? 'expired' : '' ?>">
                                    <?= htmlspecialchars($contract['contract_status']) ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Remaining Days -->
                    <div class="contract-days text-center">
                        <?php if ($isExpired): ?>
                            <div class="expired-box">Expired</div>
                        <?php elseif ($days >= $ert): ?>
                            <div class="success-box">
                                <?= $days ?> days remaining
                            </div>
                        <?php else: ?>
                            <div class="warning-box">
                                <?= $days ?> days remaining
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?searchItem=<?= urlencode($searchItem) ?>&filterItem=<?= urlencode($filterItem) ?>&page=<?= $i ?>"
                class="<?= $i === $currentPage ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>

<?php else: ?>
    <div class="no-contracts">No contracts found.</div>
<?php endif; ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.forEach(function (el) { new bootstrap.Popover(el); });
    });
</script>

<style>
    /* Grid layout */
    .contract-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        justify-content: start;
    }

    /* Card link */
    .contract-card-link {
        text-decoration: none;
        color: inherit;
    }

    /* Contract card */
    .contract-card {
        width: 280px;
        background: #ffffff;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        transition: all 0.2s ease;
        border: 1px solid #f1f5f9;
    }

    .contract-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 22px rgba(0, 0, 0, 0.1);
    }

    /* Header */
    .contract-header .contract-title {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .contract-type-badge {
        color: #fff;
        padding: 3px 6px;
        font-size: 10px;
        border-radius: 5px;
        letter-spacing: 0.4px;
    }

    /* Content */
    .contract-content {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .contract-icon {
        width: 55px;
        height: 55px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .contract-icon img {
        width: 32px;
    }

    .contract-dates div {
        font-size: 13px;
        color: #6c757d;
        margin-bottom: 4px;
    }

    .contract-dates strong {
        color: #343a40;
    }

    .contract-status-badge {
        padding: 4px 8px;
        border-radius: 5px;
        display: inline-block;
    }

    .contract-status-badge.expired {
        background-color: #EB1919;
        color: #fff;
    }

    /* Remaining days boxes */
    .contract-days .expired-box,
    .contract-days .success-box,
    .contract-days .warning-box {
        padding: 6px 8px;
        border-radius: 5px;
        font-weight: bold;
        font-size: 13px;
        margin-top: 4px;
    }

    .contract-days .expired-box {
        background-color: #EB1919;
        color: #fff;
    }

    .contract-days .success-box {
        background-color: #58B94F;
        color: #fff;
    }

    .contract-days .warning-box {
        background-color: #FF9760;
        color: #fff;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: right;
        gap: 6px;
        margin-top: 20px;
    }

    .pagination a {
        padding: 5px 10px;
        border-radius: 5px;
        text-decoration: none;
        background: #f1f1f1;
        color: #000;
        font-size: 13px;
    }

    .pagination a.active {
        background: #11488B;
        color: #fff;
    }

    .pagination a:hover {
        opacity: 0.8;
    }

    /* No contracts */
    .no-contracts {
        width: 100%;
        padding: 30px;
        text-align: center;
        background: #f8f9fa;
        border-radius: 10px;
        color: #6c757d;
        font-size: 14px;
    }

    /* Contract type badge inside header */
    .contract-type-badge {
        color: #fff;
        padding: 0.25em 0.5em;
        /* use em units relative to font-size */
        font-size: 0.8rem;
        /* relative font-size */
        border-radius: 0.35rem;
        letter-spacing: 0.3px;
        display: inline-block;
        max-width: 100%;
        /* ensure it wraps inside card */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        /* truncate long types */
    }

    /* Contract status badge */
    .contract-status-badge {
        padding: 0.25em 0.5em;
        font-size: 0.75rem;
        /* slightly smaller, scales */
        border-radius: 0.35rem;
        display: inline-block;
        max-width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Remaining days boxes dynamically scale */
    .contract-days .expired-box,
    .contract-days .success-box,
    .contract-days .warning-box {
        padding: 0.4em 0.6em;
        /* relative padding */
        border-radius: 0.35rem;
        font-weight: 600;
        font-size: 0.8rem;
        line-height: 1.2;
        display: inline-block;
        max-width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        text-align: center;
    }

    /* Make badges shrink on smaller cards */
    @media (max-width: 320px) {

        .contract-type-badge,
        .contract-status-badge,
        .contract-days .expired-box,
        .contract-days .success-box,
        .contract-days .warning-box {
            font-size: 0.7rem;
            padding: 0.2em 0.4em;
        }
    }
</style>