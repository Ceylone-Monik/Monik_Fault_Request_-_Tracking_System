<?php
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emp_id       = $_POST['emp_id'];
    $company_code = $_POST['company'];
    $branch       = trim($_POST['branch'] ?? '');
    $description  = $_POST['description'];
    
    // Map code to Full Company Name
    $companies = [
        "MA" => "Monik Agro Ventures Pvt Ltd",
        "ME" => "Monik Evermark Pvt Ltd",
        "ML" => "Monik Lands Pvt Ltd",
        "CB" => "Ceylon Monik Building Society Ltd",
        "MT" => "Monik Trading Pvt Ltd",
        "MW" => "Monik Water Pvt Ltd",
        "MH" => "Monik Homes Pvt Ltd",
        "MK" => "Monik International Pvt Ltd",
        "CMC" => "Commercial Micro Credit Investment Trust"
    ];
    
    $company_name = $companies[$company_code] ?? "Unknown Company";
    $ticket_id = "TIC-" . rand(1000, 9999);

    try {
        $sql = "INSERT INTO faults (ticket_id, company_name, branch, employee_id, fault_type, description, status)
                VALUES (?, ?, ?, ?, 'General', ?, 'New')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ticket_id, $company_name, $branch, $emp_id, $description]);

        // Redirect to success page with the ticket ID in the URL
        header("Location: success.php?tid=" . $ticket_id);
    } catch (Exception $e) {
        die("Submission Error: " . $e->getMessage());
    }
}
?>