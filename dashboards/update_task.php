<?php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['role'] == 'Technician') {
    $id = $_POST['ticket_id'];
    $new_status = $_POST['status'];

    try {
        $stmt = $pdo->prepare("UPDATE faults SET status = ? WHERE id = ? AND assigned_to = ?");
        $stmt->execute([$new_status, $id, $_SESSION['user_id']]);
        
        header("Location: technician.php?updated=true");
    } catch (Exception $e) {
        die("Update Error: " . $e->getMessage());
    }
}
?>