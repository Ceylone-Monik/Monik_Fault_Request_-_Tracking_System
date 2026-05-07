<?php
session_start();
require_once '../config/db.php';

// Security check: Only POST requests from Assign Admins
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['role'] == 'Assign Admin') {
    
    $ticketDbId = $_POST['ticket_db_id'];
    $techId     = $_POST['tech_id'];

    // Ensure we have both IDs before updating
    if (!empty($ticketDbId) && !empty($techId)) {
        try {
            // Update the ticket: Assign the tech and change status to 'Assigned'
            $stmt = $pdo->prepare("UPDATE faults SET assigned_to = ?, status = 'Assigned' WHERE id = ?");
            $stmt->execute([$techId, $ticketDbId]);
            
            // Redirect back with success message
            header("Location: assign_admin.php?success=dispatched");
            exit(); 
        } catch (Exception $e) {
            // Error handling
            die("Assignment Error: " . $e->getMessage());
        }
    } else {
        // Redirect back if data is missing
        header("Location: assign_admin.php?error=missing_info");
        exit();
    }
} else {
    // If someone tries to access this file directly
    header("Location: ../auth/login.php");
    exit();
}
?>