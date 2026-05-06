<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Assign Admin') {
    header("Location: ../auth/login.php"); exit();
}

$techs          = $pdo->query("SELECT id, full_name, profession FROM users WHERE role = 'Technician'")->fetchAll();
$pendingTickets = $pdo->query("SELECT * FROM faults WHERE status IN ('New', 'Assigned') ORDER BY created_at ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispatch Portal | Monik Group</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar-monik d-flex align-items-center justify-content-between px-4 py-3">
    <div class="d-flex align-items-center">
        <span class="logo-mark" style="background:linear-gradient(135deg,var(--accent-purple),#7c3aed);">AA</span>
        <div>
            <span class="navbar-brand-text">Dispatch Portal</span><br>
            <span style="font-size:0.75rem;color:var(--text-muted);">Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
        </div>
    </div>
    <a href="../auth/logout.php" class="btn-danger-glow text-decoration-none">
        <i class="fas fa-sign-out-alt me-1"></i> Logout
    </a>
</nav>

<div class="page-wrapper">
<div class="container-fluid px-4">
<div class="row g-4 fade-slide-up">

    <!-- Add Technician -->
    <div class="col-lg-4">
        <div class="glass-card p-4 h-100">
            <div class="section-title"><i class="fas fa-hard-hat me-2"></i>Add Technician</div>
            <form action="add_user.php" method="POST">
                <input type="hidden" name="role" value="Technician">
                <div class="mb-3">
                    <label class="form-label-dark">Full Name</label>
                    <input type="text" name="full_name" class="form-control-dark form-control" required placeholder="Kamal Perera">
                </div>
                <div class="mb-3">
                    <label class="form-label-dark">Profession</label>
                    <input type="text" name="profession" class="form-control-dark form-control" required placeholder="e.g. IT, Electrician">
                </div>
                <div class="mb-3">
                    <label class="form-label-dark">Username</label>
                    <input type="text" name="username" class="form-control-dark form-control" required placeholder="kperera">
                </div>
                <div class="mb-4">
                    <label class="form-label-dark">Password</label>
                    <input type="password" name="password" class="form-control-dark form-control" required placeholder="••••••••">
                </div>
                <button class="btn-success-glow w-100" style="border-radius:8px;padding:0.7rem;">
                    <i class="fas fa-plus me-1"></i> Add Technician
                </button>
            </form>
        </div>
    </div>

    <!-- Pending Dispatch Table -->
    <div class="col-lg-8">
        <div class="glass-card p-4">
            <div class="section-title d-flex justify-content-between align-items-center">
                <span><i class="fas fa-satellite-dish me-2"></i>Pending Dispatch</span>
                <span class="status-badge status-new"><?php echo count($pendingTickets); ?> tickets</span>
            </div>

            <?php if (empty($pendingTickets)): ?>
            <div class="alert-glass alert-glass-success d-flex align-items-center gap-2">
                <i class="fas fa-check-circle" style="color:var(--accent-green)"></i>
                <span>All tickets have been dispatched!</span>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table-dark-custom w-100">
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Type</th>
                            <th>Company</th>
                            <th>Assign To</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($pendingTickets as $t): ?>
                        <tr>
                            <td><strong style="color:var(--accent-blue);"><?php echo $t['ticket_id']; ?></strong></td>
                            <td><?php echo $t['fault_type']; ?></td>
                            <td style="font-size:0.82rem;color:var(--text-muted);"><?php echo htmlspecialchars($t['company_name']); ?></td>
                            <form action="dispatch_logic.php" method="POST" class="contents">
                                <input type="hidden" name="ticket_db_id" value="<?php echo $t['id']; ?>">
                                <td>
                                    <select name="tech_id" class="form-select-dark form-select form-select-sm" required style="min-width:160px;">
                                        <option value="">Choose tech…</option>
                                        <?php foreach ($techs as $tech): ?>
                                        <option value="<?php echo $tech['id']; ?>" <?php echo ($t['assigned_to'] == $tech['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($tech['full_name']); ?> (<?php echo $tech['profession']; ?>)
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <button class="btn-primary-glow" style="padding:0.35rem 0.9rem;font-size:0.8rem;border-radius:6px;">
                                        <i class="fas fa-paper-plane me-1"></i> Assign
                                    </button>
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

</div>
</div>
</div>

<footer class="text-center py-4 mt-5" style="color:var(--text-muted);font-size:0.8rem;border-top:1px solid rgba(255,255,255,0.06);">
    © <?php echo date("Y"); ?> Monik Group IT
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>