<?php
// ── Auth check MUST happen before header.php outputs any HTML ──
require_once '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Assign Admin') {
    header("Location: ../auth/login.php");
    exit();
}

$techs          = $pdo->query("SELECT id, full_name, profession FROM users WHERE role = 'Technician'")->fetchAll();
$pendingTickets = $pdo->query("SELECT * FROM faults WHERE status = 'New' ORDER BY created_at ASC")->fetchAll();

require_once '../includes/header.php';
?>

<div class="container-fluid px-0">
    <div class="glass-card p-4 fade-slide-up">
        <div class="section-title d-flex justify-content-between align-items-center mb-4">
            <span><i class="fas fa-satellite-dish me-2 text-warning"></i> Pending Dispatch</span>
            <span class="status-badge status-new"><?php echo count($pendingTickets); ?> new tickets</span>
        </div>

        <?php if (empty($pendingTickets)): ?>
            <div class="alert-glass alert-glass-success d-flex align-items-center gap-2">
                <i class="fas fa-check-circle" style="color:var(--accent-green)"></i>
                <span>Queue is empty. All faults have been dispatched!</span>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table-dark-custom w-100">
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Company</th>
                            <th>Fault Description</th>
                            <th>Assign Technician</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendingTickets as $t): ?>
                        <tr style="cursor:default;">
                            <td><a href="fault_detail.php?id=<?php echo $t['id']; ?>" style="color:var(--accent-blue);font-weight:700;text-decoration:none;" title="View fault detail"><?php echo $t['ticket_id']; ?></a></td>
                            <td style="font-size:0.82rem;color:var(--text-muted);"><?php echo htmlspecialchars($t['company_name']); ?></td>
                            <td><?php echo htmlspecialchars($t['description']); ?></td>
                            <form action="dispatch_logic.php" method="POST">
                                <input type="hidden" name="ticket_db_id" value="<?php echo $t['id']; ?>">
                                <td>
                                    <select name="tech_id" class="form-select-dark form-select form-select-sm" required style="min-width:160px;">
                                        <option value="">Select Staff...</option>
                                        <?php foreach ($techs as $tech): ?>
                                        <option value="<?php echo $tech['id']; ?>">
                                            <?php echo htmlspecialchars($tech['full_name']); ?> (<?php echo $tech['profession']; ?>)
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <button type="submit" class="btn-primary-glow btn-sm">Dispatch</button>
                                </td>
                            </form>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>