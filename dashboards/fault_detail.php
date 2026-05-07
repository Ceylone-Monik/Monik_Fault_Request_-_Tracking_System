<?php
// ── Auth check before any HTML output ──
require_once '../config/db.php';

// All three roles can view fault details
$allowed = ['Main Admin', 'Assign Admin', 'Technician'];
if (!in_array($_SESSION['role'] ?? '', $allowed)) {
    header("Location: ../auth/login.php");
    exit();
}

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header("Location: ../auth/login.php");
    exit();
}

$stmt = $pdo->prepare("
    SELECT f.*,
           u.full_name  AS tech_name,
           u.profession AS tech_profession
    FROM faults f
    LEFT JOIN users u ON f.assigned_to = u.id
    WHERE f.id = ?
");
$stmt->execute([$id]);
$fault = $stmt->fetch();

if (!$fault) {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../includes/header.php';

// Helpers
$statusMap = ['New' => 'new', 'Assigned' => 'assigned', 'In Progress' => 'progress', 'Resolved' => 'resolved'];
$cls = $statusMap[$fault['status']] ?? 'new';

function fmtTime($dt) {
    return $dt ? date('d M Y, h:i A', strtotime($dt)) : null;
}

$timeSubmitted = fmtTime($fault['created_at']);
$timeStarted   = fmtTime($fault['started_at']);
$timeResolved  = fmtTime($fault['resolved_at']);

// Back link depending on role
$backLink = match($_SESSION['role']) {
    'Technician'   => 'technician.php',
    'Assign Admin' => 'assign_admin.php',
    default        => 'all_faults.php',
};
?>

<div class="container-fluid px-0 fade-slide-up" style="max-width:860px;">

    <!-- Back + Title -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="<?php echo $backLink; ?>" class="btn-ghost text-decoration-none" style="border-radius:8px;padding:0.45rem 1rem;font-size:0.85rem;">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        <div>
            <h2 class="page-title mb-0" style="font-size:1.35rem;">Fault Detail</h2>
            <small style="color:var(--text-muted);">Full information and activity timeline</small>
        </div>
    </div>

    <div class="row g-4">

        <!-- Left: Fault Info -->
        <div class="col-lg-7">
            <div class="glass-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span style="font-size:1.1rem;font-weight:800;color:var(--accent-blue);">
                        <?php echo htmlspecialchars($fault['ticket_id']); ?>
                    </span>
                    <span class="status-badge status-<?php echo $cls; ?>"><?php echo $fault['status']; ?></span>
                </div>

                <?php
                $rows = [
                    ['fas fa-building',     'Company',     $fault['company_name']],
                    ['fas fa-map-marker-alt','Branch',     $fault['branch'] ?? '—'],
                    ['fas fa-id-badge',     'Employee ID', $fault['employee_id']],
                    ['fas fa-tag',          'Fault Type',  $fault['fault_type']],
                    ['fas fa-align-left',   'Description', $fault['description']],
                ];
                foreach ($rows as [$icon, $label, $value]): ?>
                <div style="display:flex;gap:1rem;padding:0.6rem 0;border-bottom:1px solid var(--border-glass);">
                    <div style="width:130px;color:var(--text-muted);font-size:0.82rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;flex-shrink:0;">
                        <i class="<?php echo $icon; ?> me-1"></i><?php echo $label; ?>
                    </div>
                    <div style="color:var(--text-primary);font-size:0.9rem;font-weight:500;">
                        <?php echo htmlspecialchars($value ?? '—'); ?>
                    </div>
                </div>
                <?php endforeach; ?>

                <?php if ($fault['tech_name']): ?>
                <div style="display:flex;gap:1rem;padding:0.6rem 0;border-bottom:1px solid var(--border-glass);">
                    <div style="width:130px;color:var(--text-muted);font-size:0.82rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;flex-shrink:0;">
                        <i class="fas fa-hard-hat me-1"></i>Technician
                    </div>
                    <div style="color:var(--text-primary);font-size:0.9rem;font-weight:500;">
                        <?php echo htmlspecialchars($fault['tech_name']); ?>
                        <span style="color:var(--text-muted);font-weight:400;"> — <?php echo htmlspecialchars($fault['tech_profession'] ?? ''); ?></span>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($fault['technician_notes'])): ?>
                <div style="margin-top:1rem;padding:0.9rem 1rem;background:rgba(37,99,235,0.06);border-left:3px solid var(--accent-blue);border-radius:0 8px 8px 0;">
                    <div style="font-size:0.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:0.3rem;">
                        <i class="fas fa-comment-medical me-1"></i> Technician Notes
                    </div>
                    <div style="color:var(--text-primary);font-size:0.9rem;font-style:italic;">
                        "<?php echo htmlspecialchars($fault['technician_notes']); ?>"
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right: Timeline -->
        <div class="col-lg-5">
            <div class="glass-card p-4 h-100">
                <div class="section-title mb-4">
                    <i class="fas fa-timeline me-2" style="color:var(--accent-purple);"></i> Activity Timeline
                </div>

                <!-- Timeline items -->
                <div style="position:relative;padding-left:28px;">

                    <!-- Vertical line -->
                    <div style="position:absolute;left:9px;top:8px;bottom:8px;width:2px;background:var(--border-glass);border-radius:2px;"></div>

                    <!-- Step 1: Submitted -->
                    <div style="position:relative;margin-bottom:28px;">
                        <div style="position:absolute;left:-28px;top:2px;width:20px;height:20px;border-radius:50%;background:var(--accent-blue);display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-paper-plane" style="font-size:0.55rem;color:#fff;"></i>
                        </div>
                        <div style="font-size:0.8rem;font-weight:700;color:var(--accent-blue);text-transform:uppercase;letter-spacing:0.05em;">Request Submitted</div>
                        <div style="font-size:0.88rem;color:var(--text-primary);margin-top:2px;"><?php echo $timeSubmitted; ?></div>
                    </div>

                    <!-- Step 2: Assigned -->
                    <div style="position:relative;margin-bottom:28px;">
                        <?php $isAssigned = in_array($fault['status'], ['Assigned','In Progress','Resolved']); ?>
                        <div style="position:absolute;left:-28px;top:2px;width:20px;height:20px;border-radius:50%;background:<?php echo $isAssigned ? 'var(--accent-purple)' : 'var(--border-glass)'; ?>;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-user-check" style="font-size:0.55rem;color:#fff;"></i>
                        </div>
                        <div style="font-size:0.8rem;font-weight:700;color:<?php echo $isAssigned ? 'var(--accent-purple)' : 'var(--text-muted)'; ?>;text-transform:uppercase;letter-spacing:0.05em;">Assigned to Technician</div>
                        <div style="font-size:0.88rem;color:var(--text-primary);margin-top:2px;">
                            <?php if ($isAssigned && $fault['tech_name']): ?>
                                <?php echo htmlspecialchars($fault['tech_name']); ?>
                            <?php else: ?>
                                <span style="color:var(--text-muted);font-style:italic;">Pending dispatch</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Step 3: Work Started -->
                    <div style="position:relative;margin-bottom:28px;">
                        <div style="position:absolute;left:-28px;top:2px;width:20px;height:20px;border-radius:50%;background:<?php echo $timeStarted ? 'var(--accent-amber)' : 'var(--border-glass)'; ?>;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-play" style="font-size:0.55rem;color:#fff;"></i>
                        </div>
                        <div style="font-size:0.8rem;font-weight:700;color:<?php echo $timeStarted ? 'var(--accent-amber)' : 'var(--text-muted)'; ?>;text-transform:uppercase;letter-spacing:0.05em;">Work Started</div>
                        <div style="font-size:0.88rem;color:var(--text-primary);margin-top:2px;">
                            <?php if ($timeStarted): ?>
                                <?php echo $timeStarted; ?>
                            <?php else: ?>
                                <span style="color:var(--text-muted);font-style:italic;">Not yet started</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Step 4: Resolved -->
                    <div style="position:relative;">
                        <div style="position:absolute;left:-28px;top:2px;width:20px;height:20px;border-radius:50%;background:<?php echo $timeResolved ? 'var(--accent-green)' : 'var(--border-glass)'; ?>;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-check" style="font-size:0.55rem;color:#fff;"></i>
                        </div>
                        <div style="font-size:0.8rem;font-weight:700;color:<?php echo $timeResolved ? 'var(--accent-green)' : 'var(--text-muted)'; ?>;text-transform:uppercase;letter-spacing:0.05em;">Fault Resolved</div>
                        <div style="font-size:0.88rem;color:var(--text-primary);margin-top:2px;">
                            <?php if ($timeResolved): ?>
                                <?php echo $timeResolved; ?>
                                <?php
                                // Show total time taken
                                $diff = strtotime($fault['resolved_at']) - strtotime($fault['created_at']);
                                $hrs  = floor($diff / 3600);
                                $mins = floor(($diff % 3600) / 60);
                                ?>
                                <div style="margin-top:4px;font-size:0.78rem;background:rgba(22,163,74,0.1);color:var(--accent-green);border-radius:6px;padding:2px 8px;display:inline-block;">
                                    <i class="fas fa-clock me-1"></i> Total: <?php echo "{$hrs}h {$mins}m"; ?>
                                </div>
                            <?php else: ?>
                                <span style="color:var(--text-muted);font-style:italic;">Still open</span>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
