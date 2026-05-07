<?php
require_once '../includes/header.php';
require_once '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Technician') {
    header("Location: ../auth/login.php"); 
    exit();
}

$tech_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM faults WHERE assigned_to = ? AND status != 'Resolved' ORDER BY created_at DESC");
$stmt->execute([$tech_id]);
$my_tasks = $stmt->fetchAll();
?>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4 fade-in mt-2">
        <h2 class="page-title" style="font-size:1.4rem;margin:0;">Active Tasks</h2>
        <span class="status-badge status-assigned"><?php echo count($my_tasks); ?> Pending</span>
    </div>

    <?php if (empty($my_tasks)): ?>
    <div class="glass-card p-5 text-center fade-in">
        <i class="fas fa-clipboard-check mb-3" style="font-size:3rem;color:var(--accent-green);"></i>
        <h4 style="color:var(--text-primary);">No current tasks!</h4>
        <p style="color:var(--text-muted);">Check "My History" to see your completed work.</p>
    </div>
    <?php else: ?>
    <div class="row g-3 fade-slide-up">
        <?php foreach ($my_tasks as $task):
            $isProgress = $task['status'] === 'In Progress';
            $borderCls  = $isProgress ? 'border-progress' : 'border-assigned';
        ?>
        <div class="col-md-6">
            <div class="task-card <?php echo $borderCls; ?>">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <a href="fault_detail.php?id=<?php echo $task['id']; ?>" style="color:var(--accent-blue);font-size:1rem;font-weight:800;text-decoration:none;" title="View detail"><?php echo $task['ticket_id']; ?> <i class="fas fa-arrow-up-right-from-square" style="font-size:0.6rem;opacity:0.7;"></i></a>
                    <span class="status-badge status-<?php echo $isProgress ? 'progress' : 'assigned'; ?>">
                        <?php echo $task['status']; ?>
                    </span>
                </div>
                <p class="small mb-1" style="color:var(--text-muted);">
                    <i class="fas fa-building me-1"></i><?php echo htmlspecialchars($task['company_name']); ?>
                </p>
                <p class="mb-3" style="color:var(--text-primary);font-size:0.9rem;line-height:1.5;">
                    <?php echo htmlspecialchars($task['description']); ?>
                </p>
                <hr class="divider">

                <?php if ($task['status'] === 'Assigned'): ?>
                <!-- Start Working → opens modal with optional notes -->
                <button type="button" class="btn-primary-glow w-100"
                        style="border-radius:8px;padding:0.6rem;"
                        onclick="openRepairModal('<?php echo $task['id']; ?>', '<?php echo htmlspecialchars($task['ticket_id']); ?>')">
                    <i class="fas fa-play me-1"></i> Start Working
                </button>
                <?php else: ?>
                <!-- Mark as Resolved -->
                <form action="update_task.php" method="POST">
                    <input type="hidden" name="ticket_id" value="<?php echo $task['id']; ?>">
                    <button name="status" value="Resolved" class="btn-success-glow w-100" style="border-radius:8px;padding:0.6rem;">
                        <i class="fas fa-check me-1"></i> Mark Resolved
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- ── Start Working Modal ──────────────────────────────── -->
<div class="modal fade" id="repairModal" tabindex="-1" aria-labelledby="repairModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:1px solid rgba(100,120,200,0.2);background:rgba(255,255,255,0.95);backdrop-filter:blur(20px);">
            <div class="modal-header" style="border-bottom:1px solid rgba(100,120,200,0.15);padding:1.25rem 1.5rem;">
                <div>
                    <h5 class="modal-title mb-0" style="font-weight:700;color:var(--text-primary);" id="repairModalLabel">
                        <i class="fas fa-play me-2" style="color:var(--accent-blue);"></i> Start Working
                    </h5>
                    <small style="color:var(--text-muted);">Ticket: <strong id="modalTicketId" style="color:var(--accent-blue);"></strong></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="update_task.php" method="POST">
                <div class="modal-body" style="padding:1.5rem;">
                    <input type="hidden" name="ticket_id" id="modalTicketDbId">
                    <input type="hidden" name="status" value="In Progress">

                    <div class="mb-3">
                        <label class="form-label-dark">Initial Repair Notes <span style="font-weight:400;text-transform:none;letter-spacing:0;color:var(--text-muted);">(optional)</span></label>
                        <textarea name="tech_notes" class="form-control-dark form-control" rows="3"
                                  placeholder="Describe what you found or plan to do…"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid rgba(100,120,200,0.15);padding:1rem 1.5rem;gap:0.75rem;">
                    <button type="button" class="btn-ghost" data-bs-dismiss="modal" style="border-radius:8px;">Cancel</button>
                    <button type="submit" class="btn-primary-glow" style="border-radius:8px;padding:0.6rem 1.5rem;">
                        <i class="fas fa-play me-1"></i> Confirm Start
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openRepairModal(dbId, displayId) {
    document.getElementById('modalTicketDbId').value = dbId;
    document.getElementById('modalTicketId').innerText = displayId;
    new bootstrap.Modal(document.getElementById('repairModal')).show();
}
</script>

<?php require_once '../includes/footer.php'; ?>