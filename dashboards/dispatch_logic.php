<?php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['role'] == 'Assign Admin') {
    $ticketDbId = $_POST['ticket_db_id'];
    $techId = $_POST['tech_id'];

    try {
        $stmt = $pdo->prepare("UPDATE faults SET assigned_to = ?, status = 'Assigned' WHERE id = ?");
        $stmt->execute([$techId, $ticketDbId]);
        
        header("Location: assign_admin.php?success=dispatched");
    } catch (Exception $e) {
        die("Assignment Error: " . $e->getMessage());
    }
}
?>