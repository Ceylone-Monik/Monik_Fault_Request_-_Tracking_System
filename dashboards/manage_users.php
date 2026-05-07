<?php
require_once '../includes/header.php';
require_once '../config/db.php';

// Security: Only Main Admin can manage users
if ($_SESSION['role'] !== 'Main Admin') {
    header("Location: ../auth/login.php"); 
    exit();
}

// Fetch all users except the current logged-in Main Admin
$stmt = $pdo->prepare("SELECT id, full_name, username, role, profession, created_at FROM users WHERE id != ? ORDER BY role DESC");
$stmt->execute([$_SESSION['user_id']]);
$all_users = $stmt->fetchAll();
?>

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
                        <select name="role" class="form-select-dark form-select" required onchange="toggleProfession(this.value)">
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
                    <button class="btn-primary-glow w-100" style="border-radius:8px;padding:0.7rem;">
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
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_users as $user): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($user['full_name']); ?></strong></td>
                                <td>
                                    <span class="badge <?php echo ($user['role'] == 'Assign Admin') ? 'bg-info' : 'bg-success'; ?> text-dark">
                                        <?php echo $user['role']; ?>
                                    </span>
                                </td>
                                <td class="text-muted"><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo $user['profession'] ? htmlspecialchars($user['profession']) : '<span class="text-muted small">N/A</span>'; ?></td>
                                <td class="text-muted small"><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function toggleProfession(role) {
    const profField = document.getElementById('profField');
    if (role === 'Technician') {
        profField.classList.remove('d-none');
    } else {
        profField.classList.add('d-none');
    }
}
</script>

<?php require_once '../includes/footer.php'; ?>