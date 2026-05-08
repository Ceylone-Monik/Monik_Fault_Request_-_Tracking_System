<?php
session_start();
require_once '../config/db.php';

if (isset($_GET['id']) && $_SESSION['role'] == 'Assign Admin') {
    $id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'Technician'");
        $stmt->execute([$id]);
        header("Location: manage_technicians.php?success=deleted");
    } catch (Exception $e) {
        die("Deletion Error: " . $e->getMessage());
    }
} else {
    header("Location: manage_technicians.php");
}