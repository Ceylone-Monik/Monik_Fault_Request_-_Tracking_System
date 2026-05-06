<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Technician') {
    header("Location: ../auth/login.php"); exit();
}

$tech_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM faults WHERE assigned_to = ? AND status != 'Resolved' ORDER BY created_at DESC");
$stmt->execute([$tech_id]);
$my_tasks = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician Portal | Monik Group</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar-monik d-flex align-items-center justify-content-between px-4 py-3">
    <div class="d-flex align-items-center">
    <img src="../assets/MonikLogoOnly.png" alt="Monik" style="height:38px;width:38px;object-fit:contain;border-radius:8px;margin-right:10px;">
        <div>
            <span class="navbar-brand-text">Technician Portal</span><br>
            <span style="font-size:0.75rem;color:var(--text-muted);">Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
        </div>
    </div>
    <a href="../auth/logout.php" class="btn-danger-glow text-decoration-none">
        <i class="fas fa-sign-out-alt me-1"></i> Logout
    </a>
</nav>

<div class="page-wrapper">
<div class="container px-4" style="max-width:960px;">

    <div class="d-flex justify-content-between align-items-center mb-4 fade-in">
        <h2 class="page-title" style="font-size:1.4rem;margin:0;">My Assigned Tasks</h2>
        <span class="status-badge status-assigned"><?php echo count($my_tasks); ?> active</span>
    </div>

    <?php if (empty($my_tasks)): ?>
    <div class="glass-card p-5 text-center fade-in">
        <i class="fas fa-clipboard-check" style="font-size:3rem;color:var(--accent-green);margin-bottom:1rem;"></i>
        <h4 style="margin-bottom:0.5rem;">All caught up!</h4>
        <p style="color:var(--text-muted);">No active tasks assigned to you right now.</p>
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
                    <strong style="color:var(--accent-blue);font-size:1rem;"><?php echo $task['ticket_id']; ?></strong>
                    <span class="status-badge status-<?php echo $isProgress ? 'progress' : 'assigned'; ?>">
                        <?php echo $task['status']; ?>
                    </span>
                </div>
                <p style="color:var(--text-muted);font-size:0.78rem;margin-bottom:0.3rem;">
                    <i class="fas fa-building me-1"></i><?php echo htmlspecialchars($task['company_name']); ?>
                    &nbsp;·&nbsp;
                    <i class="fas fa-tag me-1"></i><?php echo $task['fault_type']; ?>
                </p>
                <p style="font-size:0.9rem;color:var(--text-primary);margin-bottom:1rem;line-height:1.5;">
                    <?php echo htmlspecialchars($task['description']); ?>
                </p>
                <hr class="divider">
                <form action="update_task.php" method="POST">
                    <input type="hidden" name="ticket_id" value="<?php echo $task['id']; ?>">
                    <?php if ($task['status'] === 'Assigned'): ?>
                    <button name="status" value="In Progress" class="btn-primary-glow w-100" style="border-radius:8px;padding:0.6rem;">
                        <i class="fas fa-play me-1"></i> Start Working
                    </button>
                    <?php else: ?>
                    <button name="status" value="Resolved" class="btn-success-glow w-100" style="border-radius:8px;padding:0.6rem;">
                        <i class="fas fa-check me-1"></i> Mark as Resolved
                    </button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>
</div>

<footer class="text-center py-4 mt-5" style="color:var(--text-muted);font-size:0.8rem;border-top:1px solid rgba(255,255,255,0.06);">
    © <?php echo date("Y"); ?> Monik Group IT
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>