 <table id="table" class="table table-bordered table-striped display mt-2 hover">
            <thead>
                <tr>
                    <th scope="col" style="border: 1px solid #A9A9A9;">Name</th>
                    <th scope="col" style="text-align: center; border: 1px solid #A9A9A9;">Contract type</th>
                    <th scope="col" style="text-align: center; border: 1px solid #A9A9A9;">Start</th>
                    <th scope="col" style="text-align: center; border: 1px solid #A9A9A9;">End</th>
                    <th scope="col" style="text-align: center; border: 1px solid #A9A9A9;">Status</th>
                    <th scope="col" style="text-align: center; border: 1px solid #A9A9A9;">Days Remaining</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($contracts)): ?>
                    <?php foreach ($contracts as $contract): ?>
                        <tr>
                            <td><a href="view.php?contract_id=<?= htmlspecialchars($contract['id']) ?>"
                                    style="text-decoration: none; color: black;">
                                <?= htmlspecialchars($contract['contract_name'] ?? '') ?>
                                </a>
                                   <?php if (isset($contract['account_no'])): ?>
                                    <span class="badge account_number">(
                                        <?= $contract['account_no'] ?> )</span>
                                <?php endif; ?>
                                <?php 
                                    $contractId = $contract['id'];
                                    $hasComment = ( new CommentController )->hasComment($contractId);
                                ?>
                                <?php if($hasComment == true): ?>
                                    <span class="float-end" id="hasComment"><img src="../../../public/images/withComment.svg" width="23px" alt="This Contract has comment!"></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                            <?php
                                $type = isset($contract['contract_type']) ? $contract['contract_type'] : '';
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
                                        $badgeColor = '#FAB12F';
                                        break;
                                }
                                ?>
                                <span class="p-2 text-white badge"
                                    style="background-color: <?= $badgeColor ?>; border-radius: 5px;">
                                    <?= htmlspecialchars($type) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if ($contract['contract_type'] === TRANS_RENT) { ?>
                                    <span class="badge text-secondary">
                                        <?= !empty($contract['rent_start']) ? date('F-d-Y', strtotime($contract['rent_start'])) : '' ?>
                                    <?php } else { ?>
                                        <span class="badge text-secondary">
                                            <?= !empty($contract['contract_start']) ? date('F-d-Y', strtotime($contract['contract_start'])) : '' ?>
                                        </span>
                                    <?php } ?>
                            </td>
                            <td class="text-center">
                                <?php if ($contract['contract_type'] === TRANS_RENT) { ?>
                                    <span class="badge text-secondary">
                                        <?= !empty($contract['rent_end']) ? date('F-d-Y', strtotime($contract['rent_end'])) : '' ?>
                                    <?php } else { ?>
                                        <span class="badge text-secondary">
                                            <?= !empty($contract['contract_end']) ? date('F-d-Y', strtotime($contract['contract_end'])) : '' ?>
                                        </span>
                                    <?php } ?>
                            </td>
                            <td class="text-center">
                                <span
                                    class="badge text-white <?= ($contract['contract_status'] ?? '') === 'Active' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= htmlspecialchars($contract['contract_status'] ?? '') ?>
                                </span>
                            </td>
                            <?php
                            $contractType = $contract['contract_type'];
                            $getFromContractType = (new ContractTypeController)->getContractTypeByDepartment($contractType);
                            foreach ($getFromContractType as $row) {
                                if ($contractType === $row['contract_type']) {
                                    $end = new DateTime($contract['contract_end']);
                                    $now = new DateTime();
                                    $interval = $now->diff($end);
                                    $diff = $interval->days;
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
                                                $diff = (int) $interval->format('%r%a'); // includes negative sign if expired
                                                if ($diff >= $ert) {
                                                    echo '<td class="text-center table-success">
                                                    <span class="text-success fw-bold">' . $diff . ' days remaining</span>
                                                </td>';
                                                } elseif ($diff > 0 && $diff < $ert) {
                                                    echo '<td class="text-center table-danger">
                                                    <span class="text-danger fw-bold">' . $diff . ' days remaining before expiring</span>
                                                </td>';
                                                } else {
                                                    echo '<td class="text-center table-danger p-2">
                                                    <span class="text-danger fw-bold"> 
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="16" fill="currentColor" class="bi bi-exclamation-octagon" viewBox="0 0 16 16">
                                                            <path d="M4.54.146A.5.5 0 0 1 4.893 0h6.214a.5.5 0 0 1 .353.146l4.394 4.394a.5.5 0 0 1 .146.353v6.214a.5.5 0 0 1-.146.353l-4.394 4.394a.5.5 0 0 1-.353.146H4.893a.5.5 0 0 1-.353-.146L.146 11.46A.5.5 0 0 1 0 11.107V4.893a.5.5 0 0 1 .146-.353zM5.1 1 1 5.1v5.8L5.1 15h5.8l4.1-4.1V5.1L10.9 1z"/>
                                                            <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                                                        </svg> Expired
                                                    </span>
                                                </td>';
                                                }
                                            }
                                        }
                                        ?>
                                    </span>
                                    <?php break;
                                case PSC_LONG: ?>
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
                                                if ($diff >= $ert) {
                                                    echo '<td class="text-center table-success">
                                                            <span class="text-success fw-bold">' . $diff . ' days remaining </span>
                                                        </td>';
                                                } else {
                                                    echo '
                                                    <td class="text-center table-danger">
                                                        <span class="text-danger fw-bold">' . $diff . ' days remaining before expiring </span>
                                                    </td>';
                                                }
                                            }
                                        }
                                        ?>
                                    </span>
                                    <?php break;
                                case PSC_SHORT: ?>
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
                                                if ($diff >= $ert) {
                                                    echo '<td class="text-center table-success">
                                                            <span class="text-success fw-bold">' . $diff . ' days remaining </span>
                                                        </td>';
                                                } else {
                                                    echo '
                                                    <td class="text-center table-danger">
                                                        <span class="text-danger fw-bold">' . $diff . ' days remaining before expiring </span>
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
                                                if ($diff >= $ert) {
                                                    echo '<td class="text-center table-success">
                                                            <span class="text-success fw-bold">' . $diff . ' days remaining </span>
                                                        </td>';
                                                } else {
                                                    echo '
                                                    <td class="text-center table-danger">
                                                        <span class="text-danger fw-bold">' . $diff . ' days remaining before expiring </span>
                                                    </td>';
                                                }
                                            }
                                        }
                                        ?>
                                    </span>
                                    <?php break;
                                case TRANS_RENT: ?>
                                    <span>
                                        <?php
                                        $getFromContractType = (new ContractTypeController)->getContractTypeByDepartment($contractType);
                                        foreach ($getFromContractType as $row) {
                                            if ($contractType === $row['contract_type']) {
                                                $end = new DateTime($contract['rent_end']);
                                                $now = new DateTime();
                                                $ert = $row['contract_ert'];
                                                $interval = $now->diff($end);
                                                $diff = $interval->days;
                                                if ($diff >= $ert) {
                                                    echo '<td class="text-center table-success fw-bold">
                                                            <span class="text-success fw-bold">' . $diff . ' days remaining </span>
                                                        </td>';
                                                } else {
                                                    echo '
                                                    <td class="text-center table-danger">
                                                        <span class="text-danger fw-bold">' . $diff . ' days remaining before expiring </span>
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
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No contracts found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>