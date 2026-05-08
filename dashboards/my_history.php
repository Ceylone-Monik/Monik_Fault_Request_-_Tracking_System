<?php
require_once '../includes/header.php';
require_once '../config/db.php';

if ($_SESSION['role'] !== 'Technician') {
    header("Location: ../auth/login.php"); exit();
}

$tech_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM faults WHERE assigned_to = ? AND status = 'Resolved' ORDER BY created_at DESC");
$stmt->execute([$tech_id]);
$history = $stmt->fetchAll();
?>

<style>
/* Clickable row styles matching your other dashboards */
.clickable-row {
    cursor: pointer;
    transition: background 0.15s ease, box-shadow 0.15s ease;
}
.clickable-row:hover {
    background: rgba(37, 99, 235, 0.06) !important;
    box-shadow: inset 3px 0 0 var(--accent-blue);
}
.clickable-row td {
    user-select: none;
}
</style>

<div class="glass-card p-4 fade-slide-up">
    <div class="section-title mb-4 d-flex justify-content-between align-items-center">
        <span><i class="fas fa-history me-2 text-success"></i> My Resolved Work History</span>
        <span class="status-badge status-resolved"><?php echo count($history); ?> Resolved</span>
    </div>

    <?php if(empty($history)): ?>
        <div class="text-center p-5">
            <i class="fas fa-folder-open mb-3 text-muted" style="font-size:3rem;"></i>
            <p class="text-muted">No completed tasks found in your history.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table-dark-custom w-100">
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Company</th>
                        <th>Resolution Notes</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $h): 
                        // Link to the detail page for this specific fault
                        $url = "fault_detail.php?id={$h['id']}";
                    ?>
                    <tr class="clickable-row" onclick="window.location='<?php echo $url; ?>'" title="Click to view details">
                        <td><strong class="text-info"><?php echo $h['ticket_id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($h['company_name']); ?></td>
                        <td class="small italic text-muted">
                            "<?php echo $h['technician_notes'] ? htmlspecialchars($h['technician_notes']) : 'No notes provided'; ?>"
                        </td>
                        <td><?php echo date('M d, Y', strtotime($h['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>