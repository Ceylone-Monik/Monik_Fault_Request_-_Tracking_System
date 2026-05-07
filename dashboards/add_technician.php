<?php
require_once '../config/db.php';

// Security: Only Assign Admin can add technicians
if (($_SESSION['role'] ?? '') !== 'Assign Admin') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name  = trim($_POST['full_name']);
    $username   = trim($_POST['username']);
    $profession = trim($_POST['profession']);
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (full_name, username, password, role, profession) VALUES (?, ?, ?, 'Technician', ?)");
        $stmt->execute([$full_name, $username, $password, $profession]);
        header("Location: manage_technicians.php?success=1");
        exit();
    } catch (Exception $e) {
        // Check for duplicate username (unique constraint)
        if ($e->getCode() == 23000) {
            header("Location: manage_technicians.php?error=duplicate");
        } else {
            header("Location: manage_technicians.php?error=general");
        }
        exit();
    }
}

// If accessed directly without POST
header("Location: manage_technicians.php");
exit();
?>
