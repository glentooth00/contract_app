<?php 
session_start();
$page_title = 'Dashboard';

include_once '../../views/layouts/includes/header.php'; 

// Include your ContractController to fetch contract data
require_once __DIR__ . '../../../vendor/autoload.php';
use App\Controllers\ContractController;

$contractController = new ContractController();
$contracts = $contractController->getAllContracts(); // Assuming getAllContracts() fetches the contracts

?>

<!-- Loading Spinner - Initially visible -->
<div id="loadingSpinner" class="text-center" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<div class="pageContent">
    <div class="sideBar bg-dark">
       <?php include_once 'sidebar.php'; ?>
    </div>

    <div class="mainContent">
        <!-- Content that will be shown after loading -->
        <div id="content">
            <h2>Contracts Overview</h2>
            <table class="table table-striped border p-2 mt-5">
                <thead>
                    <tr>
                        <th>Contract Name</th>
                        <th>Contract Type</th>
                        <th>Contract Start</th>
                        <th>Contract End</th>
                        <th style="text-align: center;">Days Remaining</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($contracts as $contract): ?>
                    <?php
                    $endDate = new DateTime($contract['contract_end']);
                    $currentDate = new DateTime();
                    $interval = $currentDate->diff($endDate);
                    $daysRemaining = $interval->format('%r%a'); // %r gives the sign, %a gives the total number of days

                    // Format the dates
                    $startDateFormatted = (new DateTime($contract['contract_start']))->format('F d, Y');
                    $endDateFormatted = $endDate->format('F d, Y');
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($contract['contract_name']); ?></td>
                        <td><?= htmlspecialchars($contract['contract_type']); ?></td>
                        <td><?= $startDateFormatted; ?></td> <!-- Formatted contract start date -->
                        <td><?= $endDateFormatted; ?></td>   <!-- Formatted contract end date -->
                        <td style="text-align: center;">
                            <!-- <?= $daysRemaining; ?>  -->

                            <?php if($daysRemaining > 5){ ?>
                                <div class="badge bg-success text-wrap text-white p-2 border border-success" style="width: 20rem; font-size: 15px;">
                                    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                                    <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z"/>
                                    <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                                    </svg>  -->
                                <?= $daysRemaining ?> Days left
                                </div>
                                 <?php   }else{ ?>
                                
                             <?php } ?>
                            
                            <?php if($daysRemaining < 5){ ?>
                                <div class="badge bg-warning text-wrap text-danger border border-danger p-2" style="width: 20rem; font-size: 15px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                                    <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z"/>
                                    <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                                    </svg> 
                                        <?= $daysRemaining ?> Days remaining before contract ends
                                </div>
                                 <?php   }else{ ?>
                                
                             <?php } ?>
                            
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once '../../views/layouts/includes/footer.php';   ?>

<style>
    /* Flex container for the layout */
    .pageContent {
        display: flex;
        min-height: 100vh; /* Ensure it takes full viewport height */
    }
    /* Main content styles */
    .mainContent {
        background-color: #FFF;
        width: 100%; /* Main content takes up remaining space */
        padding: 20px;
    }

    /* Header styles */
    .headerDiv {
        background-color: #FBFBFB;
        padding: 20px;
    }
</style>

<script>
    // When the page finishes loading, hide the spinner
    window.onload = function() {
        document.getElementById("loadingSpinner").style.display = "none"; // Hide the spinner
        document.getElementById("content").style.display = "block"; // Show the page content
    };
</script>
