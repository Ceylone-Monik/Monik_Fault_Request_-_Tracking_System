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

<div class="glass-card p-4 fade-slide-up">
    <div class="section-title mb-4">
        <i class="fas fa-history me-2 text-success"></i> My Resolved Work History
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
                    <?php foreach ($history as $h): ?>
                    <tr>
                        <td><strong class="text-info"><?php echo $h['ticket_id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($h['company_name']); ?></td>
                        <td class="small italic text-muted">"<?php echo htmlspecialchars($h['technician_notes']); ?>"</td>
                        <td><?php echo date('M d, Y', strtotime($h['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>