<?php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['role'] == 'Technician') {
    $id         = $_POST['ticket_id'];
    $new_status = $_POST['status'];
    $notes      = $_POST['tech_notes'] ?? null;

    try {
        if ($new_status === 'In Progress') {
            // Set started_at timestamp + optional notes
            $stmt = $pdo->prepare("UPDATE faults SET status = ?, technician_notes = ?, started_at = NOW() WHERE id = ? AND assigned_to = ?");
            $stmt->execute([$new_status, $notes, $id, $_SESSION['user_id']]);
        } elseif ($new_status === 'Resolved') {
            // Set resolved_at timestamp
            $stmt = $pdo->prepare("UPDATE faults SET status = ?, resolved_at = NOW() WHERE id = ? AND assigned_to = ?");
            $stmt->execute([$new_status, $id, $_SESSION['user_id']]);
        } else {
            // Generic fallback
            $stmt = $pdo->prepare("UPDATE faults SET status = ? WHERE id = ? AND assigned_to = ?");
            $stmt->execute([$new_status, $id, $_SESSION['user_id']]);
        }

        header("Location: technician.php?updated=true");
        exit();
    } catch (Exception $e) {
        die("Update Error: " . $e->getMessage());
    }
}
?>