<?php
// ── Auth check before any HTML output ──
require_once '../config/db.php';

if (!in_array($_SESSION['role'] ?? '', ['Main Admin', 'Assign Admin'])) {
    header("Location: ../auth/login.php"); exit();
}

$tickets = $pdo->query("
    SELECT f.*, u.full_name AS tech_name
    FROM faults f
    LEFT JOIN users u ON f.assigned_to = u.id
    ORDER BY f.created_at DESC
")->fetchAll();

require_once '../includes/header.php';
?>

<style>
/* Clickable row styles */
.clickable-row {
    cursor: pointer;
    transition: background 0.15s ease, box-shadow 0.15s ease;
}
.clickable-row:hover {
    background: rgba(37, 99, 235, 0.06) !important;
    box-shadow: inset 3px 0 0 var(--accent-blue);
}
.clickable-row td {
    /* prevent text selection on click */
    user-select: none;
}
</style>

<div class="glass-card p-4 fade-slide-up">
    <div class="section-title mb-4 d-flex justify-content-between align-items-center">
        <span><i class="fas fa-database me-2" style="color:var(--accent-blue);"></i> All System Fault Records</span>
        <span class="status-badge status-assigned"><?php echo count($tickets); ?> tickets</span>
    </div>

    <div class="table-responsive">
        <table class="table-dark-custom w-100">
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Company</th>
                    <th>Status</th>
                    <th>Technician</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $t):
                    $statusMap = ['New'=>'new','Assigned'=>'assigned','In Progress'=>'progress','Resolved'=>'resolved'];
                    $cls = $statusMap[$t['status']] ?? 'new';
                    $url = "fault_detail.php?id={$t['id']}";
                ?>
                <tr class="clickable-row" onclick="window.location='<?php echo $url; ?>'" title="Click to view fault details">
                    <td><strong style="color:var(--accent-blue);"><?php echo $t['ticket_id']; ?></strong></td>
                    <td><?php echo htmlspecialchars($t['company_name']); ?></td>
                    <td><span class="status-badge status-<?php echo $cls; ?>"><?php echo $t['status']; ?></span></td>
                    <td><?php echo $t['tech_name'] ? htmlspecialchars($t['tech_name']) : '<em class="text-muted">Unassigned</em>'; ?></td>
                    <td style="color:var(--text-muted);"><?php echo date('Y-m-d', strtotime($t['created_at'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>