<?php
require_once '../config/db.php';
$tid = $_GET['tid'] ?? '';

if ($tid) {
    $stmt = $pdo->prepare("
        SELECT f.ticket_id, f.status, f.company_name, f.fault_type, f.description,
               u.full_name AS tech_name, u.profession AS tech_profession
        FROM faults f
        LEFT JOIN users u ON f.assigned_to = u.id
        WHERE f.ticket_id = ?
    ");
    $stmt->execute([$tid]);
    $res = $stmt->fetch();

    if ($res) {
        $statusMap = [
            'New'         => ['cls' => 'new',      'icon' => 'fa-circle-dot'],
            'Assigned'    => ['cls' => 'assigned',  'icon' => 'fa-user-check'],
            'In Progress' => ['cls' => 'progress',  'icon' => 'fa-spinner'],
            'Resolved'    => ['cls' => 'resolved',  'icon' => 'fa-check-circle'],
        ];
        $sm   = $statusMap[$res['status']] ?? ['cls' => 'new', 'icon' => 'fa-circle'];
        $tech = $res['tech_name']
            ? "<span style='color:var(--accent-green);margin-right:4px;'>&#10003;</span><strong>" . htmlspecialchars($res['tech_name']) . "</strong><span style='color:var(--text-muted);'> — " . htmlspecialchars($res['tech_profession']) . "</span>"
            : "<span style='color:var(--text-muted);font-style:italic;'>Pending assignment</span>";

        echo "
        <div class='ticket-result-card mt-3'>
            <div class='ticket-result-header'>
                <span style='color:var(--accent-blue);font-weight:700;font-size:1rem;'>{$res['ticket_id']}</span>
                <span class='status-badge status-{$sm['cls']}'><i class='fas {$sm['icon']} me-1'></i>{$res['status']}</span>
            </div>
            <div class='ticket-result-body'>
                <div class='ticket-info-row'>
                    <div class='ticket-info-label'><i class='fas fa-building me-1'></i> Company</div>
                    <div class='ticket-info-value'>" . htmlspecialchars($res['company_name']) . "</div>
                </div>
                <div class='ticket-info-row'>
                    <div class='ticket-info-label'><i class='fas fa-tag me-1'></i> Fault Type</div>
                    <div class='ticket-info-value'>" . htmlspecialchars($res['fault_type']) . "</div>
                </div>
                <div class='ticket-info-row'>
                    <div class='ticket-info-label'><i class='fas fa-align-left me-1'></i> Description</div>
                    <div class='ticket-info-value'>" . htmlspecialchars($res['description']) . "</div>
                </div>
                <div class='ticket-info-row'>
                    <div class='ticket-info-label'><i class='fas fa-hard-hat me-1'></i> Technician</div>
                    <div class='ticket-info-value'>$tech</div>
                </div>
            </div>
        </div>";
    } else {
        echo "
        <div class='alert-glass alert-glass-danger d-flex align-items-center gap-2 mt-3'>
            <i class='fas fa-exclamation-circle' style='color:var(--accent-red);'></i>
            <span>No ticket found with ID <strong>" . htmlspecialchars($tid) . "</strong>. Please check and try again.</span>
        </div>";
    }
}
?>