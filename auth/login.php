<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login | Monik Group</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="page-wrapper d-flex align-items-center justify-content-center" style="min-height:100vh;padding:2rem;">
<div style="width:100%;max-width:420px;">

    <!-- Logo -->
    <div class="text-center mb-4 fade-in">
        <div style="display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;background:linear-gradient(135deg,var(--accent-blue),var(--accent-purple));border-radius:18px;margin-bottom:1rem;box-shadow:0 8px 32px rgba(79,142,247,0.4);">
            <i class="fas fa-lock" style="font-size:1.6rem;color:#fff;"></i>
        </div>
        <h1 class="page-title" style="font-size:1.7rem;">Management Login</h1>
        <p style="color:var(--text-muted);font-size:0.88rem;">Monik Group IT — Staff &amp; Admin Access</p>
    </div>

    <div class="glass-card p-4 fade-slide-up">

        <?php if(isset($_GET['error'])): ?>
        <div class="alert-glass alert-glass-danger d-flex align-items-center gap-2 mb-3">
            <i class="fas fa-exclamation-circle" style="color:var(--accent-red);"></i>
            <span>Invalid username or password. Please try again.</span>
        </div>
        <?php endif; ?>

        <form action="process_login.php" method="POST">
            <div class="mb-3">
                <label class="form-label-dark"><i class="fas fa-user me-1"></i> Username</label>
                <input type="text" name="username" class="form-control-dark form-control" placeholder="Enter your username" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label-dark"><i class="fas fa-key me-1"></i> Password</label>
                <input type="password" name="password" class="form-control-dark form-control" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn-primary-glow w-100" style="border-radius:10px;padding:0.75rem;font-size:0.95rem;">
                <i class="fas fa-sign-in-alt me-2"></i> Sign In
            </button>
        </form>
    </div>

    <div class="text-center mt-3" style="font-size:0.82rem;">
        <a href="../index.php" style="color:var(--text-muted);text-decoration:none;">
            <i class="fas fa-arrow-left me-1"></i> Back to Portal
        </a>
    </div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>