<?php
// ── Auth check MUST happen before header.php outputs any HTML ──
require_once '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Main Admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch stats — all use fetchColumn() to get a plain integer
$totalTickets      = $pdo->query("SELECT COUNT(*) FROM faults")->fetchColumn();
$totalAssignAdmins = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'Assign Admin'")->fetchColumn();
$totalTechs        = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'Technician'")->fetchColumn();

require_once '../includes/header.php';
?>

<div class="container-fluid px-0">
    <div class="row g-3 mb-4 fade-in">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-number" style="color:var(--accent-blue);"><?php echo $totalTickets; ?></div>
                <div class="stat-label"><i class="fas fa-ticket-alt me-1"></i> Total Tickets</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-number" style="color:var(--accent-purple);"><?php echo $totalAssignAdmins; ?></div>
                <div class="stat-label"><i class="fas fa-user-shield me-1"></i> Assign Admins</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-number" style="color:var(--accent-green);"><?php echo $totalTechs; ?></div>
                <div class="stat-label"><i class="fas fa-tools me-1"></i> Technicians</div>
            </div>
        </div>
    </div>

    <div class="row g-4 fade-slide-up">
        <div class="col-lg-12">
            <div class="glass-card p-4">
                <div class="section-title mb-4">
                    <i class="fas fa-chart-line me-2 text-primary"></i> System Overview
                </div>
                <p style="color:var(--text-muted);">Use the sidebar to manage full fault records or register new staff members.</p>
                <div class="d-flex gap-2">
                    <a href="all_faults.php" class="btn-primary-glow text-decoration-none" style="border-radius:8px;padding:0.5rem 1.2rem;font-size:0.88rem;">
                        <i class="fas fa-database me-1"></i> View All Faults
                    </a>
                    <a href="manage_users.php" class="btn-ghost text-decoration-none" style="border-radius:8px;padding:0.5rem 1.2rem;font-size:0.88rem;">
                        <i class="fas fa-users me-1"></i> Manage Staff
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>