<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Monik Group | Management Portal'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* ── Dashboard Layout ───────────────────────────── */
        :root { --sidebar-width: 255px; }

        body {
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
            /* inherits white gradient from style.css */
        }

        /* Sidebar */
        #sidebar {
            width: var(--sidebar-width);
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid rgba(100, 120, 200, 0.15);
            position: fixed;
            height: 100vh;
            z-index: 1000;
            transition: 0.3s;
            box-shadow: 2px 0 20px rgba(37, 99, 235, 0.07);
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 22px 20px;
            font-weight: 800;
            font-size: 1rem;
            color: var(--accent-blue);
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(100, 120, 200, 0.12);
            letter-spacing: 0.04em;
        }

        .nav-section-label {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 16px 20px 6px;
        }

        .nav-link-custom {
            color: var(--text-muted);
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            border-radius: 10px;
            margin: 2px 10px;
            font-size: 0.88rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .nav-link-custom i { width: 18px; text-align: center; font-size: 0.9rem; }
        .nav-link-custom:hover {
            background: rgba(37, 99, 235, 0.08);
            color: var(--accent-blue);
        }
        .nav-link-custom.active {
            background: rgba(37, 99, 235, 0.12);
            color: var(--accent-blue);
            font-weight: 600;
        }
        .nav-link-custom.logout {
            color: var(--accent-red);
        }
        .nav-link-custom.logout:hover {
            background: rgba(220, 38, 38, 0.08);
            color: var(--accent-red);
        }

        .sidebar-divider {
            border: none;
            border-top: 1px solid rgba(100, 120, 200, 0.12);
            margin: 10px 16px;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 16px;
            border-top: 1px solid rgba(100, 120, 200, 0.12);
            font-size: 0.75rem;
            color: var(--text-muted);
            text-align: center;
        }

        /* Content area */
        #content-wrapper {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 28px 32px;
            transition: 0.3s;
            min-height: 100vh;
        }

        /* Top bar */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(100, 120, 200, 0.15);
        }
        .top-bar .user-name {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        .top-bar .user-role {
            font-size: 0.78rem;
            color: var(--text-muted);
            font-weight: 500;
        }
        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Role badge in top bar */
        .role-pill {
            background: rgba(37, 99, 235, 0.1);
            color: var(--accent-blue);
            border: 1px solid rgba(37, 99, 235, 0.2);
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #content-wrapper { margin-left: 0; width: 100%; padding: 16px; }
        }
    </style>
</head>
<body>

<!-- ── Sidebar ──────────────────────────────────────────── -->
<div id="sidebar">
    <div class="sidebar-brand">
        <img src="../assets/MonikLogoOnly.png" alt="Monik Logo" style="height:32px;width:32px;object-fit:contain;border-radius:8px;">
        <span>MONIK PORTAL</span>
    </div>

    <div class="mt-2 flex-grow-1">
        <div class="nav-section-label">Navigation</div>

        <a href="<?php
            if($_SESSION['role'] == 'Main Admin') echo 'main_admin.php';
            elseif($_SESSION['role'] == 'Assign Admin') echo 'assign_admin.php';
            else echo 'technician.php';
        ?>" class="nav-link-custom active">
            <i class="fas fa-th-large"></i> Dashboard
        </a>

        <?php if($_SESSION['role'] == 'Main Admin'): ?>
            <div class="nav-section-label">Management</div>
            <a href="all_faults.php" class="nav-link-custom"><i class="fas fa-database"></i> All Faults</a>
            <a href="manage_users.php" class="nav-link-custom"><i class="fas fa-user-gear"></i> Manage Staff</a>
        <?php endif; ?>

        <?php if($_SESSION['role'] == 'Assign Admin'): ?>
            <div class="nav-section-label">Operations</div>
            <a href="assign_admin.php" class="nav-link-custom"><i class="fas fa-satellite-dish"></i> New Dispatches</a>
            <a href="all_faults.php" class="nav-link-custom"><i class="fas fa-file-invoice"></i> Fault Records</a>
        <?php endif; ?>

        <?php if($_SESSION['role'] == 'Technician'): ?>
            <div class="nav-section-label">My Work</div>
            <a href="technician.php" class="nav-link-custom"><i class="fas fa-screwdriver-wrench"></i> Active Tasks</a>
            <a href="my_history.php" class="nav-link-custom"><i class="fas fa-clock-rotate-left"></i> My History</a>
        <?php endif; ?>
    </div>

    <hr class="sidebar-divider">
    <div style="padding: 0 0 8px;">
        <a href="../auth/logout.php" class="nav-link-custom logout">
            <i class="fas fa-right-from-bracket"></i> Logout
        </a>
    </div>
    <div class="sidebar-footer">
        © <?php echo date("Y"); ?> Monik Group IT
    </div>
</div>

<!-- ── Content Wrapper ─────────────────────────────────── -->
<div id="content-wrapper">
    <div class="top-bar">
        <div>
            <div class="user-name"><?php echo htmlspecialchars($_SESSION['full_name']); ?></div>
            <div class="user-role">Monik Group IT</div>
        </div>
        <div class="top-bar-right">
            <span class="role-pill"><i class="fas fa-circle-user me-1"></i><?php echo $_SESSION['role']; ?></span>
            <img src="../assets/Monik.jpeg" alt="Monik" style="height:34px;object-fit:contain;border-radius:8px;opacity:0.9;box-shadow:0 2px 8px rgba(37,99,235,0.15);">
        </div>
    </div>