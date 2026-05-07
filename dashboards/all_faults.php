<?php
require_once '../includes/header.php';
require_once '../config/db.php';

if (!in_array($_SESSION['role'], ['Main Admin', 'Assign Admin'])) {
    header("Location: ../auth/login.php"); exit();
}

$tickets = $pdo->query("
    SELECT f.*, u.full_name AS tech_name 
    FROM faults f 
    LEFT JOIN users u ON f.assigned_to = u.id 
    ORDER BY f.created_at DESC
")->fetchAll();
?>

<div class="glass-card p-4 fade-slide-up">
    <div class="section-title mb-4">
        <i class="fas fa-database me-2 text-info"></i> All System Fault Records
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
                ?>
                <tr>
                    <td><a href="fault_detail.php?id=<?php echo $t['id']; ?>" style="color:var(--accent-blue);font-weight:700;text-decoration:none;" title="View detail"><?php echo $t['ticket_id']; ?> <i class="fas fa-arrow-up-right-from-square" style="font-size:0.65rem;opacity:0.7;"></i></a></td>
                    <td><?php echo htmlspecialchars($t['company_name']); ?></td>
                    <td><span class="status-badge status-<?php echo $cls; ?>"><?php echo $t['status']; ?></span></td>
                    <td><?php echo $t['tech_name'] ? htmlspecialchars($t['tech_name']) : '<em class="text-muted">Unassigned</em>'; ?></td>
                    <td class="text-muted"><?php echo date('Y-m-d', strtotime($t['created_at'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>