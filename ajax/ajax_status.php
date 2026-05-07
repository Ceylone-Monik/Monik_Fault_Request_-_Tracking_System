<?php
require_once '../config/db.php';
$tid = $_GET['tid'] ?? '';

if ($tid) {
    $stmt = $pdo->prepare("
        SELECT f.ticket_id, f.status, f.company_name, f.branch, f.fault_type, f.description,
               f.technician_notes, f.created_at, f.started_at, f.resolved_at,
               u.full_name AS tech_name, u.profession AS tech_profession
        FROM faults f
        LEFT JOIN users u ON f.assigned_to = u.id
        WHERE f.ticket_id = ?
    ");
    $stmt->execute([$tid]);
    $res = $stmt->fetch();

    if ($res) {
        $statusMap = [
            'New'         => ['cls' => 'new',      'icon' => 'fa-circle-dot'],
            'Assigned'    => ['cls' => 'assigned',  'icon' => 'fa-user-check'],
            'In Progress' => ['cls' => 'progress',  'icon' => 'fa-spinner'],
            'Resolved'    => ['cls' => 'resolved',  'icon' => 'fa-check-circle'],
        ];
        $sm   = $statusMap[$res['status']] ?? ['cls' => 'new', 'icon' => 'fa-circle'];
        $tech = $res['tech_name']
            ? "<span style='color:var(--accent-green);margin-right:4px;'>&#10003;</span><strong>" . htmlspecialchars($res['tech_name']) . "</strong><span style='color:var(--text-muted);'> — " . htmlspecialchars($res['tech_profession']) . "</span>"
            : "<span style='color:var(--text-muted);font-style:italic;'>Pending assignment</span>";

        // ── Info Card ──────────────────────────────────────────
        echo "
        <div class='ticket-result-card mt-3'>
            <div class='ticket-result-header'>
                <span style='color:var(--accent-blue);font-weight:700;font-size:1rem;'>{$res['ticket_id']}</span>
                <span class='status-badge status-{$sm['cls']}'><i class='fas {$sm['icon']} me-1'></i>{$res['status']}</span>
            </div>
            <div class='ticket-result-body'>
                <div class='ticket-info-row'>
                    <div class='ticket-info-label'><i class='fas fa-building me-1'></i> Company</div>
                    <div class='ticket-info-value'>" . htmlspecialchars($res['company_name']) . "</div>
                </div>
                <div class='ticket-info-row'>
                    <div class='ticket-info-label'><i class='fas fa-map-marker-alt me-1'></i> Branch</div>
                    <div class='ticket-info-value'>" . (!empty($res['branch']) ? htmlspecialchars($res['branch']) : "<span style='color:var(--text-muted);font-style:italic;'>Not specified</span>") . "</div>
                </div>
                <div class='ticket-info-row'>
                    <div class='ticket-info-label'><i class='fas fa-tag me-1'></i> Fault Type</div>
                    <div class='ticket-info-value'>" . htmlspecialchars($res['fault_type']) . "</div>
                </div>
                <div class='ticket-info-row'>
                    <div class='ticket-info-label'><i class='fas fa-align-left me-1'></i> Description</div>
                    <div class='ticket-info-value'>" . htmlspecialchars($res['description']) . "</div>
                </div>
                <div class='ticket-info-row'>
                    <div class='ticket-info-label'><i class='fas fa-hard-hat me-1'></i> Technician</div>
                    <div class='ticket-info-value'>$tech</div>
                </div>";

        if (!empty($res['technician_notes'])) {
            echo "
                <div class='ticket-info-row' style='border-top:1px solid var(--border-glass); padding-top:10px; margin-top:4px;'>
                    <div class='ticket-info-label'><i class='fas fa-comment-medical me-1'></i> Repair Notes</div>
                    <div class='ticket-info-value' style='font-style:italic;'>\"" . htmlspecialchars($res['technician_notes']) . "\"</div>
                </div>";
        }

        echo "
            </div>
        </div>";

        // ── Activity Timeline ───────────────────────────────────
        // Helper: format datetime
        $fmt = fn($dt) => $dt ? date('d M Y, h:i A', strtotime($dt)) : null;

        $timeSubmitted = $fmt($res['created_at']);
        $timeStarted   = $fmt($res['started_at']);
        $timeResolved  = $fmt($res['resolved_at']);

        $isAssigned = in_array($res['status'], ['Assigned', 'In Progress', 'Resolved']);

        // Total time badge
        $totalBadge = '';
        if ($res['resolved_at'] && $res['created_at']) {
            $diff = strtotime($res['resolved_at']) - strtotime($res['created_at']);
            $hrs  = floor($diff / 3600);
            $mins = floor(($diff % 3600) / 60);
            $totalBadge = "<span style='margin-left:10px;font-size:0.78rem;background:rgba(22,163,74,0.12);color:var(--accent-green);border-radius:6px;padding:2px 10px;display:inline-flex;align-items:center;gap:4px;'><i class='fas fa-clock'></i> Total: {$hrs}h {$mins}m</span>";
        }

        // Timeline dot helper
        $dot = fn($color, $icon) => "<div style='position:absolute;left:-24px;top:2px;width:20px;height:20px;border-radius:50%;background:{$color};display:flex;align-items:center;justify-content:center;flex-shrink:0;'><i class='fas {$icon}' style='font-size:0.5rem;color:#fff;'></i></div>";

        echo "
        <div class='ticket-result-card mt-3'>
            <div class='ticket-result-header'>
                <span style='color:var(--accent-purple);font-weight:700;font-size:0.8rem;letter-spacing:0.08em;text-transform:uppercase;'>
                    <i class='fas fa-timeline me-2'></i>Activity Timeline
                </span>
            </div>
            <div class='ticket-result-body'>
                <div style='position:relative;padding-left:30px;'>

                    <!-- Vertical line -->
                    <div style='position:absolute;left:9px;top:8px;bottom:8px;width:2px;background:var(--border-glass);border-radius:2px;'></div>

                    <!-- Step 1: Submitted -->
                    <div style='position:relative;margin-bottom:22px;'>
                        " . $dot('var(--accent-blue)', 'fa-paper-plane') . "
                        <div style='font-size:0.78rem;font-weight:700;color:var(--accent-blue);text-transform:uppercase;letter-spacing:0.06em;'>Request Submitted</div>
                        <div style='font-size:0.87rem;color:var(--text-primary);margin-top:2px;'>{$timeSubmitted}</div>
                    </div>

                    <!-- Step 2: Assigned -->
                    <div style='position:relative;margin-bottom:22px;'>
                        " . $dot($isAssigned ? 'var(--accent-purple)' : 'var(--border-glass)', 'fa-user-check') . "
                        <div style='font-size:0.78rem;font-weight:700;color:" . ($isAssigned ? 'var(--accent-purple)' : 'var(--text-muted)') . ";text-transform:uppercase;letter-spacing:0.06em;'>Assigned to Technician</div>
                        <div style='font-size:0.87rem;color:var(--text-primary);margin-top:2px;'>" . ($isAssigned && $res['tech_name'] ? htmlspecialchars($res['tech_name']) : "<span style='color:var(--text-muted);font-style:italic;'>Pending dispatch</span>") . "</div>
                    </div>

                    <!-- Step 3: Work Started -->
                    <div style='position:relative;margin-bottom:22px;'>
                        " . $dot($timeStarted ? 'var(--accent-amber)' : 'var(--border-glass)', 'fa-play') . "
                        <div style='font-size:0.78rem;font-weight:700;color:" . ($timeStarted ? 'var(--accent-amber)' : 'var(--text-muted)') . ";text-transform:uppercase;letter-spacing:0.06em;'>Work Started</div>
                        <div style='font-size:0.87rem;color:var(--text-primary);margin-top:2px;'>" . ($timeStarted ?? "<span style='color:var(--text-muted);font-style:italic;'>Not yet started</span>") . "</div>
                    </div>

                    <!-- Step 4: Resolved -->
                    <div style='position:relative;'>
                        " . $dot($timeResolved ? 'var(--accent-green)' : 'var(--border-glass)', 'fa-check') . "
                        <div style='font-size:0.78rem;font-weight:700;color:" . ($timeResolved ? 'var(--accent-green)' : 'var(--text-muted)') . ";text-transform:uppercase;letter-spacing:0.06em;'>Fault Resolved</div>
                        <div style='font-size:0.87rem;color:var(--text-primary);margin-top:2px;'>" . ($timeResolved ? $timeResolved . $totalBadge : "<span style='color:var(--text-muted);font-style:italic;'>Still open</span>") . "</div>
                    </div>

                </div>
            </div>
        </div>";

    } else {
        echo "
        <div class='alert-glass alert-glass-danger d-flex align-items-center gap-2 mt-3'>
            <i class='fas fa-exclamation-circle' style='color:var(--accent-red);'></i>
            <span>No ticket found with ID <strong>" . htmlspecialchars($tid) . "</strong>. Please check and try again.</span>
        </div>";
    }
}
?>