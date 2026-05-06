<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Ticket | Monik Group</title>
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
    <a href="../index.php" style="color:var(--text-muted);text-decoration:none;font-size:0.85rem;">
        <i class="fas fa-arrow-left me-1"></i> Back to Home
    </a>
</nav>

<div class="page-wrapper">
<div class="container" style="max-width:580px;">

    <div class="text-center mb-4 fade-in">
        <div style="display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;background:linear-gradient(135deg,var(--accent-blue),#3b6fd4);border-radius:16px;margin-bottom:1rem;box-shadow:0 8px 24px rgba(79,142,247,0.35);">
            <i class="fas fa-search" style="font-size:1.4rem;color:#fff;"></i>
        </div>
        <h1 class="page-title" style="font-size:1.6rem;">Track Your Fault Request</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin-top:0.3rem;">Enter your Ticket ID to see the current status and assigned technician.</p>
    </div>

    <div class="glass-card p-4 fade-slide-up">
        <label class="form-label-dark">Ticket ID</label>
        <div class="d-flex gap-2 mb-3">
            <input type="text" id="tidInput" class="form-control-dark form-control"
                   placeholder="e.g. TIC-1234"
                   onkeydown="if(event.key==='Enter') searchTicket()">
            <button class="btn-primary-glow" onclick="searchTicket()" style="white-space:nowrap;border-radius:8px;">
                <i class="fas fa-search me-1"></i> Search
            </button>
        </div>
        <div id="resultArea"></div>
    </div>

</div>
</div>

<footer class="text-center py-4 mt-5" style="color:var(--text-muted);font-size:0.8rem;border-top:1px solid rgba(255,255,255,0.06);">
    © <?php echo date("Y"); ?> Monik Group IT
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/css/script.js"></script>
</body>
</html>