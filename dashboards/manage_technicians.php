<?php
// ── Auth check before any HTML output ──
require_once '../config/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (($_SESSION['role'] ?? '') !== 'Assign Admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch all technicians
$techs = $pdo->query("SELECT id, full_name, username, profession, created_at FROM users WHERE role = 'Technician' ORDER BY created_at DESC")->fetchAll();

require_once '../includes/header.php';
?>

<div class="container-fluid px-0">
    <div class="row g-4 fade-slide-up">

        <div class="col-lg-4">
            <div class="glass-card p-4">
                <div class="section-title mb-4">
                    <i class="fas fa-user-plus me-2" style="color:var(--accent-green);"></i> Add New Technician
                </div>

                <form action="add_technician.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label-dark">Full Name</label>
                        <input type="text" name="full_name" class="form-control-dark form-control" required placeholder="e.g. Kamal Perera">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dark">Profession / Skill</label>
                        <input type="text" name="profession" class="form-control-dark form-control" required placeholder="e.g. IT, Electrician">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dark">Username</label>
                        <input type="text" name="username" class="form-control-dark form-control" required placeholder="kperera">
                    </div>
                    <div class="mb-4">
                        <label class="form-label-dark">Password</label>
                        <input type="password" name="password" class="form-control-dark form-control" required placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn-success-glow w-100" style="border-radius:8px;padding:0.7rem;">
                        <i class="fas fa-plus me-1"></i> Create Technician Account
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="glass-card p-4">
                <div class="section-title mb-4 d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-hard-hat me-2" style="color:var(--accent-amber);"></i> All Technicians</span>
                    <span class="status-badge status-assigned"><?php echo count($techs); ?> registered</span>
                </div>

                <div class="table-responsive">
                    <table class="table-dark-custom w-100">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Profession / Skill</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($techs as $t): ?>
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--accent-green),#16a34a);display:flex;align-items:center;justify-content:center;font-size:0.8rem;color:#fff;font-weight:700;flex-shrink:0;">
                                            <?php echo strtoupper(substr($t['full_name'], 0, 1)); ?>
                                        </div>
                                        <strong style="color:var(--text-primary);"><?php echo htmlspecialchars($t['full_name']); ?></strong>
                                    </div>
                                </td>
                                <td class="text-muted small"><?php echo htmlspecialchars($t['username']); ?></td>
                                <td>
                                    <span style="background:rgba(245,158,11,0.1);color:var(--accent-amber);border:1px solid rgba(245,158,11,0.25);border-radius:6px;padding:2px 10px;font-size:0.8rem;font-weight:600;">
                                        <?php echo htmlspecialchars($t['profession'] ?? '—'); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-info" onclick="openEditTechModal(<?php echo htmlspecialchars(json_encode($t)); ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="confirmDeleteTech(<?php echo $t['id']; ?>, '<?php echo htmlspecialchars($t['full_name']); ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editTechModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card" style="background: var(--bg-card); border: 1px solid var(--border-glass);">
            <form action="edit_tech_logic.php" method="POST">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white"><i class="fas fa-user-edit me-2"></i>Edit Technician Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="edit_tech_id">
                    <div class="mb-3">
                        <label class="form-label-dark">Full Name</label>
                        <input type="text" name="full_name" id="edit_full_name" class="form-control-dark form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dark">Profession / Skill</label>
                        <input type="text" name="profession" id="edit_profession" class="form-control-dark form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dark">Username</label>
                        <input type="text" name="username" id="edit_username" class="form-control-dark form-control" required>
                    </div>
                    <div class="mb-3 mt-1">
                        <label class="form-label-dark">Change Password (Optional)</label>
                        <input type="password" name="password" class="form-control-dark form-control" placeholder="••••••••">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-ghost btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-glow btn-sm px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteTechModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content glass-card text-center" style="background: var(--bg-card); border: 1px solid var(--border-glass); padding: 25px;">
            <div class="modal-body pb-0">
                <i class="fas fa-exclamation-triangle text-danger mb-3" style="font-size: 2.8rem;"></i>
                <h5 class="text-white mb-2">Are you sure?</h5>
                <p class="text-muted small mb-1">Removing technician:</p>
                <strong id="deleteTechName" class="text-white d-block mb-1" style="font-size: 1.05rem;"></strong>
            </div>
            <div class="d-flex gap-2 justify-content-center mt-3">
                <button type="button" class="btn btn-ghost btn-sm px-4" data-bs-dismiss="modal">Cancel</button>
                <a id="confirmDeleteTechBtn" href="#" class="btn btn-danger btn-sm px-4">Remove</a>
            </div>
        </div>
    </div>
</div>

<script>
function openEditTechModal(tech) {
    document.getElementById('edit_tech_id').value = tech.id;
    document.getElementById('edit_full_name').value = tech.full_name;
    document.getElementById('edit_username').value = tech.username;
    document.getElementById('edit_profession').value = tech.profession;
    new bootstrap.Modal(document.getElementById('editTechModal')).show();
}

function confirmDeleteTech(techId, techName) {
    document.getElementById('deleteTechName').innerText = techName;
    document.getElementById('confirmDeleteTechBtn').href = 'delete_tech.php?id=' + techId;
    new bootstrap.Modal(document.getElementById('deleteTechModal')).show();
}
</script>

<?php require_once '../includes/footer.php'; ?>