<?php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['full_name'];
    $user = $_POST['username'];
    $role = $_POST['role'];
    $profession = $_POST['profession'] ?? NULL;
    $hashed_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (full_name, username, password, role, profession) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $user, $hashed_pass, $role, $profession]);
        
        // Redirect back to the correct dashboard
        $redirect = ($role == 'Assign Admin') ? 'main_admin.php' : 'assign_admin.php';
        header("Location: $redirect?success=user_added");
    } catch (Exception $e) {
        die("Error adding user: " . $e->getMessage());
    }
}
?>