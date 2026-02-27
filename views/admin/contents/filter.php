<?php
require_once __DIR__ . '../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../vendor/autoload.php';

use App\Controllers\ContractController;
?>
<style>
    #searchContainer input.form-control:focus,
    #searchContainer select.form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        outline: none;
    }

    #searchContainer select.form-select:hover,
    #searchContainer input.form-control:hover {
        border-color: #6c757d;
    }
</style>

<div id="filterItems" style="margin-bottom: 20px; display: flex; justify-content: flex-start; gap: 10px;">


    <!-- Contract Type Filter -->
    <!-- <div style="text-align: right;">
                <label>Filter :</label>
                <select id="statusFilter" class="form-select" style="width: 340px;margin-top:-1em">
                    <option value="">Select All</option>
                    <?php if (!empty($getAllContractType)): ?>
                        <?php foreach ($getAllContractType as $contract): ?>
                            <option value="<?= htmlspecialchars($contract['contract_type']) ?>">
                                <?= htmlspecialchars($contract['contract_type']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div> -->
    <div id="searchContainer" style="display: flex; align-items: center; gap: 10px;">
        <form method="GET" style="display: flex; flex-direction: column; gap: 4px;">
            <label for="searchInput" style="font-size: 0.85rem; color: #6c757d; font-weight: 500;">Search
                Contracts:</label>
            <input type="text" name="searchItem" id="searchInput" class="form-control"
                value="<?= htmlspecialchars($_GET['searchItem'] ?? '') ?>" placeholder="Type contract name..."
                style="width: 250px; min-width: 180px; border-radius: 8px; border: 1px solid #ced4da; transition: all 0.2s;">
        </form>

        <!-- Contract Type Filter -->
        <form method="GET" style="display: flex; flex-direction: column; gap: 4px;">
            <label for="filterItem" style="font-size: 0.85rem; color: #6c757d; font-weight: 500;">Filter by
                Type:</label>
            <select name="filterItem" id="filterItem" class="form-select" onchange="this.form.submit()"
                style="width: 200px; min-width: 150px; border-radius: 8px; border: 1px solid #ced4da; transition: all 0.2s;">
                <option value="">Select All</option>
                <?php foreach ($getAllContractType as $contract): ?>
                    <option value="<?= htmlspecialchars($contract['contract_type']) ?>" <?= (($_GET['filterItem'] ?? '') == $contract['contract_type']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($contract['contract_type']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php
        $searchItem = $_GET['searchItem'] ?? '';
        $filterItem = $_GET['filterItem'] ?? '';
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

        $data = (new ContractController)
            ->getContractsByDepartmentAllSearch($department, $searchItem, $filterItem, $page, 8);

        $contracts = $data['results'];
        $totalPages = $data['totalPages'];
        $currentPage = $data['currentPage'];

        ?>
    </div>

</div>