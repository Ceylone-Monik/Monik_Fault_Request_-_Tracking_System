<?php $tid = $_GET['tid'] ?? 'Error'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Submitted | Monik Group</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar-monik d-flex align-items-center justify-content-between px-4 py-3">
    <div class="d-flex align-items-center">
        <span class="logo-mark">MG</span>
        <span class="navbar-brand-text">Monik Group <span class="brand-badge">Fault Portal</span></span>
    </div>
</nav>

<div class="page-wrapper">
<div class="container" style="max-width:520px;">
    <div class="glass-card p-5 text-center fade-slide-up">

        <div style="display:inline-flex;align-items:center;justify-content:center;width:80px;height:80px;background:linear-gradient(135deg,var(--accent-green),#16a34a);border-radius:50%;margin-bottom:1.5rem;box-shadow:0 8px 32px rgba(34,197,94,0.4);">
            <i class="fas fa-check" style="font-size:2rem;color:#fff;"></i>
        </div>

        <h2 style="font-weight:800;margin-bottom:0.5rem;">Fault Reported!</h2>
        <p style="color:var(--text-muted);margin-bottom:1.5rem;">Your request has been submitted successfully. Please save your Ticket ID below.</p>

        <div style="background:rgba(79,142,247,0.1);border:1px solid rgba(79,142,247,0.3);border-radius:12px;padding:1.25rem;margin-bottom:2rem;">
            <p style="color:var(--text-muted);font-size:0.75rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;margin-bottom:0.4rem;">Your Ticket ID</p>
            <div style="font-size:2rem;font-weight:800;color:var(--accent-blue);letter-spacing:0.05em;">
                <?php echo htmlspecialchars($tid); ?>
            </div>
        </div>

        <div class="d-flex gap-3 justify-content-center">
            <a href="search_ticket.php" class="btn-primary-glow text-decoration-none" style="border-radius:8px;font-size:0.9rem;">
                <i class="fas fa-search me-1"></i> Track This Ticket
            </a>
            <a href="../index.php" class="btn-ghost text-decoration-none" style="font-size:0.9rem;">
                <i class="fas fa-home me-1"></i> Return Home
            </a>
        </div>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>