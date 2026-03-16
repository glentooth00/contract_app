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
    <div class="contract-title">
        <?php
        $displayName = $contract['contract_name'] ?? '';
        $displayName = strlen($displayName) > 20 ? substr($displayName, 0, 35) . '...' : $displayName;
        ?>
        <?= htmlspecialchars($displayName) ?>
    </div>

    <!-- Contract Type below the name -->
    <span class="badge contract-type-badge" style="background-color: <?= $badgeColor ?>;">
        <?= htmlspecialchars($type) ?>
    </span>

    <?php if ($hasComment): ?>
        <span class="float-end ms-1">
            <?php include_once 'message.php'; ?>
        </span>
    <?php endif; ?>

    <?php if (($flagData['status'] ?? '') === 1): ?>
        <span class="float-end ms-1">
            <?php if ($flagData['flag_type'] === UR): ?>
                <img src="<?= image_source ?>../../../public/images/underReview.svg" width="20" title="Under Review">
            <?php elseif ($flagData['flag_type'] === NA): ?>
                <img src="<?= image_source ?>../../../public/images/withComment.svg" width="20" title="Needs Attention">
            <?php endif; ?>
        </span>
    <?php endif; ?>
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
                                <span class="badge contract-status-badge <?= $isExpired ? 'expired' : 'active' ?>">
                                    <?= $isExpired ? 'EXPIRED' : ($contract['contract_status'] ?? 'ACTIVE') ?>
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
 /* GRID */
.contract-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(260px,1fr));
    gap:18px;
}

/* LINK */
.contract-card-link{
    text-decoration:none;
    color:inherit;
}

/* FILE CARD */
.contract-card{
    background:#fff;
    border:1px solid #e4e9f0;
    border-radius:10px;
    overflow:hidden;
    transition:all .15s ease;
    box-shadow:0 2px 6px rgba(0,0,0,0.04);
}

.contract-card:hover{
    transform:translateY(-3px);
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
}

/* FILE HEADER (like folder tab) */
/* Contract Header */
.contract-header {
    padding: 10px 14px;
    border-bottom: 1px solid #eef2f6;
    background: #f8fafc;
    display: flex;
    flex-direction: column; /* stack items vertically */
    gap: 6px;
}

.contract-title {
    font-size: 13px;
    font-weight: 600;
    color: #2f3a4a;
}

/* FILE BODY */
.contract-content{
    display:flex;
    gap:14px;
    padding:14px;
    align-items:flex-start;
}

/* DOCUMENT ICON */
.contract-icon{
    width:42px;
    height:52px;
    background:#ffffff;
    border:1px solid #dfe5ec;
    border-radius:4px;
    position:relative;
    display:flex;
    align-items:center;
    justify-content:center;
}

.contract-icon:before{
    content:"";
    position:absolute;
    top:-1px;
    right:-1px;
    width:10px;
    height:10px;
    background:#e9edf3;
    border-left:1px solid #dfe5ec;
    border-bottom:1px solid #dfe5ec;
    transform:rotate(45deg);
}

.contract-icon img{
    width:22px;
}

/* FILE META */
.contract-dates{
    font-size:12px;
    color:#667085;
    line-height:1.5;
}

.contract-dates strong{
    color:#344054;
}

/* BADGES */
.contract-type-badge {
    font-size: 14px;
    padding: 3px 6px;
    border-radius: 4px;
    color: #fff;
    display: inline-block;
    max-width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.contract-status-badge{
    font-size:11px;
    padding:3px 6px;
    border-radius:4px;
}

/* FOOTER STRIP */
.contract-days{
    padding:10px;
    border-top:1px solid #eef2f6;
    text-align:center;
    font-size:12px;
    font-weight:600;
}

/* STATUS COLORS */
.success-box{
    color:#2f7d32;
}

.warning-box{
    color:#c27a00;
}

.expired-box{
    color:#c0392b;
}

/* PAGINATION */
.pagination{
    display:flex;
    justify-content:flex-end;
    gap:6px;
    margin-top:20px;
}

.pagination a{
    font-size:12px;
    padding:6px 10px;
    border-radius:6px;
    border:1px solid #d8dee6;
    background:#fff;
}

.pagination a.active{
    background:#1f3a5f;
    color:#fff;
}

.contract-status-badge.expired {
    background-color:#EB1919;
    color:#fff;
}

.contract-status-badge.active {
    background-color:#58B94F;
    color:#fff;
}
</style>