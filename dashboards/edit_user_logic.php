<?php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['role'] == 'Main Admin') {
    $id = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $profession = ($role === 'Technician') ? $_POST['profession'] : null;
    $password = $_POST['password'];

    try {
        if (!empty($password)) {
            // Update with new password
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET full_name = ?, username = ?, role = ?, profession = ?, password = ? WHERE id = ?");
            $stmt->execute([$full_name, $username, $role, $profession, $hashed_pass, $id]);
        } else {
            // Update without changing password
            $stmt = $pdo->prepare("UPDATE users SET full_name = ?, username = ?, role = ?, profession = ? WHERE id = ?");
            $stmt->execute([$full_name, $username, $role, $profession, $id]);
        }
        
        header("Location: manage_users.php?success=updated");
    } catch (Exception $e) {
        die("Update Error: " . $e->getMessage());
    }
}
?>