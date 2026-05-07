<?php
// ── Auth check before any HTML output ──
require_once '../config/db.php';

if (($_SESSION['role'] ?? '') !== 'Assign Admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch all technicians
$techs = $pdo->query("SELECT id, full_name, username, profession, created_at FROM users WHERE role = 'Technician' ORDER BY created_at DESC")->fetchAll();

require_once '../includes/header.php';
?>

<div class="container-fluid px-0">
    <div class="row g-4 fade-slide-up">

        <!-- ── Add Technician Form ────────────────────────── -->
        <div class="col-lg-4">
            <div class="glass-card p-4">
                <div class="section-title mb-4">
                    <i class="fas fa-user-plus me-2" style="color:var(--accent-green);"></i> Add New Technician
                </div>

                <?php if (isset($_GET['success'])): ?>
                <div class="alert-glass alert-glass-success d-flex align-items-center gap-2 mb-3">
                    <i class="fas fa-check-circle" style="color:var(--accent-green);"></i>
                    <span>Technician account created successfully!</span>
                </div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                <div class="alert-glass alert-glass-danger d-flex align-items-center gap-2 mb-3">
                    <i class="fas fa-exclamation-circle" style="color:var(--accent-red);"></i>
                    <span>
                        <?php
                        $err = $_GET['error'];
                        if ($err === 'duplicate') echo 'Username already exists. Please choose another.';
                        else echo 'An error occurred. Please try again.';
                        ?>
                    </span>
                </div>
                <?php endif; ?>

                <form action="add_technician.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label-dark">Full Name</label>
                        <input type="text" name="full_name" class="form-control-dark form-control" required placeholder="e.g. Kamal Perera">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dark">Profession / Skill</label>
                        <input type="text" name="profession" class="form-control-dark form-control" required placeholder="e.g. IT, Electrician, Plumber">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dark">Username</label>
                        <input type="text" name="username" class="form-control-dark form-control" required placeholder="kperera">
                    </div>
                    <div class="mb-4">
                        <label class="form-label-dark">Password</label>
                        <input type="password" name="password" class="form-control-dark form-control" required placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn-success-glow w-100" style="border-radius:8px;padding:0.7rem;">
                        <i class="fas fa-plus me-1"></i> Create Technician Account
                    </button>
                </form>
            </div>
        </div>

        <!-- ── Technician List ───────────────────────────── -->
        <div class="col-lg-8">
            <div class="glass-card p-4">
                <div class="section-title mb-4 d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-hard-hat me-2" style="color:var(--accent-amber);"></i> All Technicians</span>
                    <span class="status-badge status-assigned"><?php echo count($techs); ?> registered</span>
                </div>

                <?php if (empty($techs)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-hard-hat mb-3" style="font-size:3rem;color:var(--text-muted);"></i>
                    <p style="color:var(--text-muted);">No technicians registered yet. Use the form to add the first one.</p>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table-dark-custom w-100">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Profession / Skill</th>
                                <th>Registered</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($techs as $t): ?>
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--accent-green),#16a34a);display:flex;align-items:center;justify-content:center;font-size:0.8rem;color:#fff;font-weight:700;flex-shrink:0;">
                                            <?php echo strtoupper(substr($t['full_name'], 0, 1)); ?>
                                        </div>
                                        <strong style="color:var(--text-primary);"><?php echo htmlspecialchars($t['full_name']); ?></strong>
                                    </div>
                                </td>
                                <td style="color:var(--text-muted);font-size:0.88rem;"><?php echo htmlspecialchars($t['username']); ?></td>
                                <td>
                                    <span style="background:rgba(245,158,11,0.1);color:var(--accent-amber);border:1px solid rgba(245,158,11,0.25);border-radius:6px;padding:2px 10px;font-size:0.8rem;font-weight:600;">
                                        <?php echo htmlspecialchars($t['profession'] ?? '—'); ?>
                                    </span>
                                </td>
                                <td style="color:var(--text-muted);font-size:0.85rem;"><?php echo date('d M Y', strtotime($t['created_at'])); ?></td>
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

<?php require_once '../includes/footer.php'; ?>
