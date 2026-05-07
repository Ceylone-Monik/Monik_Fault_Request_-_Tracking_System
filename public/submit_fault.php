<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Fault | Monik Group</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar-monik d-flex align-items-center justify-content-between px-4 py-3">
    <div class="d-flex align-items-center">
    <img src="../assets/MonikLogoOnly.png" alt="Monik" style="height:36px;width:36px;object-fit:contain;border-radius:8px;margin-right:10px;">
        <span class="navbar-brand-text">Monik Group <span class="brand-badge">Fault Portal</span></span>
    </div>
    <a href="../index.php" style="color:var(--text-muted);text-decoration:none;font-size:0.85rem;">
        <i class="fas fa-arrow-left me-1"></i> Back to Home
    </a>
</nav>

<div class="page-wrapper">
<div class="container" style="max-width:640px;">

    <div class="text-center mb-4 fade-in">
        <img src="../assets/MonikLogoOnly.png" alt="Monik Group" style="height:52px;object-fit:contain;border-radius:10px;margin-bottom:1rem;box-shadow:0 6px 20px rgba(0,0,0,0.4);">
        <h1 class="page-title" style="font-size:1.6rem;">Submit a Fault Request</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin-top:0.3rem;">Fill in the details below and our IT team will respond promptly.</p>
    </div>

    <div class="glass-card p-4 fade-slide-up">
        <form action="process_fault.php" method="POST" id="faultForm">

            <div class="mb-3">
                <label class="form-label-dark">Company</label>
                <select id="comp" name="company" class="form-select-dark form-select" required onchange="genID()">
                    <option value="">— Select your company —</option>
                    <option value="MA">Monik Agro Ventures (MA)</option>
                    <option value="ME">Monik Evermark (ME)</option>
                    <option value="ML">Monik Lands (ML)</option>
                    <option value="CB">Ceylon Monik Building (CB)</option>
                    <option value="MT">Monik Trading (MT)</option>
                    <option value="MW">Monik Water (MW)</option>
                    <option value="MH">Monik Homes (MH)</option>
                    <option value="MK">Monik International (MK)</option>
                    <option value="CMC">Commercial Micro Credit (CMC)</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label-dark">Branch</label>
                <input type="text" name="branch" class="form-control-dark form-control" required placeholder="e.g. Colombo, Kandy, Galle…">
            </div>

            <div class="mb-3">
                <label class="form-label-dark">Employee Number <span style="color:var(--text-muted);font-weight:400;text-transform:none;letter-spacing:0;">(last digits only)</span></label>
                <input type="number" id="num" class="form-control-dark form-control" placeholder="e.g. 123" required oninput="genID()">
            </div>

            <div class="mb-3">
                <label class="form-label-dark">Your Employee ID</label>
                <input type="text" id="finalID" name="emp_id" class="form-control-dark form-control readonly-id" readonly placeholder="Auto-generated after selecting company">
            </div>

            <div class="mb-4">
                <label class="form-label-dark">Fault Description</label>
                <textarea name="description" class="form-control-dark form-control" rows="4" required placeholder="Describe the issue in detail…"></textarea>
            </div>

            <button type="submit" class="btn-success-glow w-100" style="border-radius:10px;padding:0.75rem;">
                <i class="fas fa-paper-plane me-2"></i> Submit Fault Request
            </button>
        </form>
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