<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Main Admin') {
    header("Location: ../auth/login.php"); exit();
}

$totalTickets      = $pdo->query("SELECT COUNT(*) FROM faults")->fetchColumn();
$totalAssignAdmins = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'Assign Admin'")->fetchColumn();
$totalTechs        = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'Technician'")->fetchColumn();
$tickets           = $pdo->query("SELECT * FROM faults ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Admin Dashboard | Monik Group</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar-monik d-flex align-items-center justify-content-between px-4 py-3">
    <div class="d-flex align-items-center">
        <span class="logo-mark">MA</span>
        <div>
            <span class="navbar-brand-text">Main Admin Dashboard</span><br>
            <span style="font-size:0.75rem;color:var(--text-muted);">Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
        </div>
    </div>
    <a href="../auth/logout.php" class="btn-danger-glow text-decoration-none">
        <i class="fas fa-sign-out-alt me-1"></i> Logout
    </a>
</nav>

<div class="page-wrapper">
<div class="container-fluid px-4">

    <!-- Stat Cards -->
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

        <!-- Add Assign Admin -->
        <div class="col-lg-4">
            <div class="glass-card p-4 h-100">
                <div class="section-title"><i class="fas fa-user-plus me-2"></i>Add New Assign Admin</div>
                <form action="add_user.php" method="POST">
                    <input type="hidden" name="role" value="Assign Admin">
                    <div class="mb-3">
                        <label class="form-label-dark">Full Name</label>
                        <input type="text" name="full_name" class="form-control-dark form-control" required placeholder="John Silva">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dark">Username</label>
                        <input type="text" name="username" class="form-control-dark form-control" required placeholder="jsilva">
                    </div>
                    <div class="mb-4">
                        <label class="form-label-dark">Password</label>
                        <input type="password" name="password" class="form-control-dark form-control" required placeholder="••••••••">
                    </div>
                    <button class="btn-primary-glow w-100" style="border-radius:8px;padding:0.7rem;">
                        <i class="fas fa-plus me-1"></i> Create Admin
                    </button>
                </form>
            </div>
        </div>

        <!-- Tickets Table -->
        <div class="col-lg-8">
            <div class="glass-card p-4">
                <div class="section-title d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-list-alt me-2"></i>All System Tickets</span>
                    <span style="font-size:0.8rem;color:var(--accent-blue);"><?php echo count($tickets); ?> total</span>
                </div>
                <div class="table-responsive">
                    <table class="table-dark-custom w-100">
                        <thead>
                            <tr>
                                <th>Ticket ID</th>
                                <th>Company</th>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($tickets as $t):
                            $statusMap = ['New'=>'new','Assigned'=>'assigned','In Progress'=>'progress','Resolved'=>'resolved'];
                            $cls = $statusMap[$t['status']] ?? 'new';
                        ?>
                            <tr>
                                <td><strong style="color:var(--accent-blue);"><?php echo $t['ticket_id']; ?></strong></td>
                                <td style="font-size:0.82rem;color:var(--text-muted);"><?php echo htmlspecialchars($t['company_name']); ?></td>
                                <td><?php echo htmlspecialchars($t['employee_id']); ?></td>
                                <td><?php echo $t['fault_type']; ?></td>
                                <td><span class="status-badge status-<?php echo $cls; ?>"><?php echo $t['status']; ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
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