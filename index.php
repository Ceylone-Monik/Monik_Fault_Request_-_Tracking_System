<?php $cssPath = ''; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monik Group | Fault Request & Tracking System</title>
    <meta name="description" content="Submit and track IT fault requests for Monik Group of Companies.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar-monik d-flex align-items-center justify-content-between px-4 py-3" style="position:sticky;top:0;z-index:100;">
    <div class="d-flex align-items-center">
        <img src="assets/MonikLogoOnly.png" alt="Monik Logo" style="height:38px;width:38px;object-fit:contain;border-radius:8px;margin-right:10px;">
        <span class="navbar-brand-text">Monik Group <span class="brand-badge">IT Portal</span></span>
    </div>
    <a href="auth/login.php" class="btn-ghost text-decoration-none" style="font-size:0.85rem; padding: 0.4rem 1rem; border: 1px solid rgba(255,255,255,0.15); border-radius: 8px; color: var(--text-muted); transition: all 0.3s;">
        <i class="fas fa-lock me-1"></i> Staff Login
    </a>
</nav>

<div class="page-wrapper">
<div class="container" style="max-width: 900px;">

    <!-- Hero Header -->
    <div class="text-center mb-5 mt-4 fade-in">
        <img src="assets/Monik.jpeg" alt="Monik Group" style="height:80px;object-fit:contain;border-radius:12px;margin-bottom:1.5rem;box-shadow:0 8px 32px rgba(0,0,0,0.4);">
        <h1 class="page-title mb-2">Fault Request & Tracking System</h1>
        <p style="color:var(--text-muted);font-size:1rem;">Submit a new IT fault report or track your existing request by Ticket ID.</p>
    </div>

    <!-- Action Cards -->
    <div class="row g-4 justify-content-center mb-5 fade-slide-up">
        <div class="col-md-5">
            <a href="public/submit_fault.php" class="hero-card hero-card-green text-decoration-none d-flex">
                <div class="hero-icon"><i class="fas fa-paper-plane"></i></div>
                <div class="hero-title">Submit Fault</div>
                <div class="hero-sub">Report a new IT issue</div>
            </a>
        </div>
        <div class="col-md-5">
            <a href="public/search_ticket.php" class="hero-card hero-card-blue text-decoration-none d-flex">
                <div class="hero-icon"><i class="fas fa-search"></i></div>
                <div class="hero-title">Track Ticket</div>
                <div class="hero-sub">Check your request status</div>
            </a>
        </div>
    </div>

    <!-- Info Strip -->
    <div class="glass-card p-4 text-center fade-in" style="animation-delay:0.2s;">
        <div class="row g-3">
            <div class="col-md-4">
                <i class="fas fa-shield-alt mb-2" style="color:var(--accent-blue);font-size:1.5rem;"></i>
                <p class="mb-0" style="font-size:0.82rem;color:var(--text-muted);">Secure &amp; Confidential</p>
            </div>
            <div class="col-md-4">
                <i class="fas fa-clock mb-2" style="color:var(--accent-green);font-size:1.5rem;"></i>
                <p class="mb-0" style="font-size:0.82rem;color:var(--text-muted);">Real-Time Status Updates</p>
            </div>
            <div class="col-md-4">
                <i class="fas fa-users-cog mb-2" style="color:var(--accent-purple);font-size:1.5rem;"></i>
                <p class="mb-0" style="font-size:0.82rem;color:var(--text-muted);">Expert Technician Dispatch</p>
            </div>
        </div>
    </div>

</div>
</div>

<footer class="text-center py-4 mt-5" style="color:var(--text-muted);font-size:0.8rem;border-top:1px solid rgba(255,255,255,0.06);">
    © <?php echo date("Y"); ?> <strong style="color:var(--text-primary)">Monik Group IT</strong> &mdash; Fault Management System
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>