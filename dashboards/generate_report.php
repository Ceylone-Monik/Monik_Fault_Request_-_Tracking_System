<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../config/db.php';
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Main Admin', 'Assign Admin'])) {
    die("Unauthorized access.");
}

// Capture All Filters
$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';
$name = $_GET['name'] ?? '';
$comp = $_GET['company'] ?? '';

// Dynamic SQL Building
$sql = "SELECT f.*, u.full_name AS tech_name FROM faults f 
        LEFT JOIN users u ON f.assigned_to = u.id WHERE 1=1";
$params = [];

if (!empty($from) && !empty($to)) {
    $sql .= " AND DATE(f.created_at) BETWEEN ? AND ?";
    $params[] = $from;
    $params[] = $to;
}
if (!empty($name)) {
    $sql .= " AND u.full_name LIKE ?";
    $params[] = "%$name%";
}
if (!empty($comp)) {
    $sql .= " AND f.company_name LIKE ?";
    $params[] = "%$comp%";
}

$stmt = $pdo->prepare($sql . " ORDER BY f.created_at DESC");
$stmt->execute($params);
$tickets = $stmt->fetchAll();

// Construct HTML for PDF
$html = '
<style>
    body { font-family: sans-serif; font-size: 11px; }
    .header { text-align: center; border-bottom: 2px solid #2563eb; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #f1f5f9; padding: 8px; border: 1px solid #ddd; }
    td { padding: 8px; border: 1px solid #ddd; }
</style>
<div class="header">
    <h2>MONIK GROUP IT - FILTERED SYSTEM REPORT</h2>
    <p>Generated: ' . date('Y-m-d H:i') . '</p>
</div>
<table>
    <thead>
        <tr>
            <th>TICKET ID</th>
            <th>COMPANY</th>
            <th>STATUS</th>
            <th>TECHNICIAN</th>
            <th>DATE</th>
        </tr>
    </thead>
    <tbody>';

foreach ($tickets as $t) {
    $html .= "<tr>
                <td>{$t['ticket_id']}</td>
                <td>" . htmlspecialchars($t['company_name']) . "</td>
                <td>{$t['status']}</td>
                <td>" . ($t['tech_name'] ?? 'Unassigned') . "</td>
                <td>" . date('Y-m-d', strtotime($t['created_at'])) . "</td>
              </tr>";
}
$html .= '</tbody></table>';

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("Monik_Filtered_Report.pdf", ["Attachment" => 0]);