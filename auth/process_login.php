<?php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$user]);
    $userData = $stmt->fetch();

    if ($userData && password_verify($pass, $userData['password'])) {
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['role'] = $userData['role'];
        $_SESSION['full_name'] = $userData['full_name'];

        // Redirect based on role
        if ($userData['role'] == 'Main Admin') {
            header("Location: ../dashboards/main_admin.php");
        } elseif ($userData['role'] == 'Assign Admin') {
            header("Location: ../dashboards/assign_admin.php");
        } else {
            header("Location: ../dashboards/technician.php");
        }
    } else {
        header("Location: login.php?error=invalid");
    }
}
?>