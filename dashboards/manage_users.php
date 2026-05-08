<?php
// 1. Session and Auth check
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../config/db.php';

// Security: Only Main Admin can manage users
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Main Admin') {
    header("Location: ../auth/login.php"); 
    exit();
}

// Fetch all staff members except the current Main Admin
$stmt = $pdo->prepare("SELECT id, full_name, username, role, profession, created_at FROM users WHERE id != ? ORDER BY role DESC");
$stmt->execute([$_SESSION['user_id']]);
$all_users = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<style>
/* Centering text in buttons and modals */
.btn-primary-glow, .btn-danger, .btn-ghost {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
    padding-top: 0.7rem;
    padding-bottom: 0.7rem;
}

/* Modal styling matching the theme */
.glass-modal {
    background: var(--bg-card) !important;
    backdrop-filter: blur(16px);
    border: 1px solid var(--border-glass) !important;
    border-radius: var(--radius-lg);
}

/* --- CUSTOM ROLE BADGE STYLING --- */
.badge-role {
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.3px;
}

/* Cyan/Blue for Assign Admin */
.badge-assign-admin {
    background: rgba(0, 212, 255, 0.1);
    color: #00d4ff;
    border: 1px solid rgba(0, 212, 255, 0.2);
}

/* Green for Technician */
.badge-technician {
    background: rgba(40, 199, 111, 0.1);
    color: #28c76f;
    border: 1px solid rgba(40, 199, 111, 0.2);
}
</style>

<div class="container-fluid px-0">
    <div class="row g-4 fade-slide-up">
        
        <div class="col-lg-4">
            <div class="glass-card p-4">
                <div class="section-title mb-4">
                    <i class="fas fa-user-plus me-2 text-primary"></i> Register New Staff
                </div>
                <form action="add_user.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label-dark">System Role</label>
                        <select name="role" class="form-select-dark form-select" required onchange="toggleProfession(this.value, 'profField')">
                            <option value="Assign Admin">Assign Admin</option>
                            <option value="Technician">Technician</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dark">Full Name</label>
                        <input type="text" name="full_name" class="form-control-dark form-control" required placeholder="e.g. Sunil Perera">
                    </div>
                    <div id="profField" class="mb-3 d-none">
                        <label class="form-label-dark">Profession / Skill</label>
                        <input type="text" name="profession" class="form-control-dark form-control" placeholder="e.g. IT, Electrician">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dark">Username</label>
                        <input type="text" name="username" class="form-control-dark form-control" required placeholder="username123">
                    </div>
                    <div class="mb-4">
                        <label class="form-label-dark">Password</label>
                        <input type="password" name="password" class="form-control-dark form-control" required placeholder="••••••••">
                    </div>
                    <button class="btn-primary-glow w-100">
                        <i class="fas fa-check me-1"></i> Create User Account
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="glass-card p-4">
                <div class="section-title mb-4 d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-users me-2 text-info"></i> Active Staff Members</span>
                    <span class="badge bg-secondary"><?php echo count($all_users); ?> Members</span>
                </div>

                <div class="table-responsive">
                    <table class="table-dark-custom w-100">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Username</th>
                                <th>Specialty</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_users as $user): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($user['full_name']); ?></strong></td>
                                <td>
                                    <?php 
                                        // Dynamic class selection
                                        $badgeClass = ($user['role'] == 'Assign Admin') ? 'badge-assign-admin' : 'badge-technician';
                                    ?>
                                    <span class="badge-role <?php echo $badgeClass; ?>">
                                        <?php echo $user['role']; ?>
                                    </span>
                                </td>
                                <td class="text-muted"><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo $user['profession'] ? htmlspecialchars($user['profession']) : '<span class="text-muted small">N/A</span>'; ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-info" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($user)); ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['full_name']); ?>')">
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

<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-modal">
            <form action="edit_user_logic.php" method="POST">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white"><i class="fas fa-user-edit me-2"></i>Edit Staff Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <div class="mb-3">
                        <label class="form-label-dark">Full Name</label>
                        <input type="text" name="full_name" id="edit_full_name" class="form-control-dark form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dark">System Role</label>
                        <select name="role" id="edit_role" class="form-select-dark form-select" required onchange="toggleProfession(this.value, 'editProfField')">
                            <option value="Assign Admin">Assign Admin</option>
                            <option value="Technician">Technician</option>
                        </select>
                    </div>
                    <div id="editProfField" class="mb-3">
                        <label class="form-label-dark">Profession / Skill</label>
                        <input type="text" name="profession" id="edit_profession" class="form-control-dark form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dark">Username</label>
                        <input type="text" name="username" id="edit_username" class="form-control-dark form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-dark">New Password (Optional)</label>
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

<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content glass-modal text-center" style="padding: 25px;">
            <div class="modal-body pb-0">
                <i class="fas fa-exclamation-triangle text-danger mb-3" style="font-size: 2.8rem;"></i>
                <h5 class="text-white mb-2">Are you sure?</h5>
                <p class="text-muted small mb-1">You are about to remove</p>
                <strong id="deleteUserName" class="text-white d-block mb-1" style="font-size: 1.05rem;"></strong>
                <p class="text-muted small">from the staff list.</p>
            </div>
            <div class="d-flex gap-2 justify-content-center mt-3">
                <button type="button" class="btn btn-ghost btn-sm px-4" data-bs-dismiss="modal">Cancel</button>
                <a id="confirmDeleteBtn" href="#" class="btn btn-danger btn-sm px-4" style="line-height: 1.5; padding-top: 8px;">Remove</a>
            </div>
        </div>
    </div>
</div>

<script>
function toggleProfession(role, fieldId) {
    const profField = document.getElementById(fieldId);
    if (role === 'Technician') {
        profField.classList.remove('d-none');
    } else {
        profField.classList.add('d-none');
    }
}

function openEditModal(user) {
    document.getElementById('edit_user_id').value = user.id;
    document.getElementById('edit_full_name').value = user.full_name;
    document.getElementById('edit_username').value = user.username;
    document.getElementById('edit_role').value = user.role;
    document.getElementById('edit_profession').value = user.profession || '';
    toggleProfession(user.role, 'editProfField');
    new bootstrap.Modal(document.getElementById('editUserModal')).show();
}

function confirmDelete(userId, userName) {
    document.getElementById('deleteUserName').innerText = userName;
    document.getElementById('confirmDeleteBtn').href = 'delete_user.php?id=' + userId;
    new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
}
</script>

<?php require_once '../includes/footer.php'; ?>