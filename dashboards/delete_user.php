<?php
session_start();
require_once '../config/db.php';

if (isset($_GET['id']) && $_SESSION['role'] == 'Main Admin') {
    $id = $_GET['id'];

    try {
        // Prevent accidental self-deletion if current session ID matches
        if ($id == $_SESSION['user_id']) {
            header("Location: manage_users.php?error=self_delete");
            exit();
        }

        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        
        header("Location: manage_users.php?success=deleted");
    } catch (Exception $e) {
        die("Deletion Error: " . $e->getMessage());
    }
} else {
    header("Location: ../auth/login.php");
}
?>