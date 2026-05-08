<?php
// 1. Session and Auth check (Must be at the very top)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/db.php';

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Main Admin', 'Assign Admin'])) {
    header("Location: ../auth/login.php"); 
    exit();
}

// 2. Fetch all fault data initially
try {
    $tickets = $pdo->query("
        SELECT f.*, u.full_name AS tech_name
        FROM faults f
        LEFT JOIN users u ON f.assigned_to = u.id
        ORDER BY f.created_at DESC
    ")->fetchAll();
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}

// 3. Set Page Title and Include Header
$pageTitle = "All Faults | Monik Group";
require_once '../includes/header.php';
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
/* Clickable row styles */
.clickable-row {
    cursor: pointer;
    transition: background 0.15s ease;
}
.clickable-row:hover {
    background: rgba(37, 99, 235, 0.06) !important;
}

/* DataTables adjustment for the portal theme */
.dataTables_wrapper .dataTables_info, 
.dataTables_wrapper .dataTables_paginate {
    color: var(--text-muted) !important;
    font-size: 0.85rem;
    margin-top: 15px;
}
</style>

<div class="glass-card p-4 fade-slide-up">
    <div class="section-title mb-4 d-flex justify-content-between align-items-center">
        <span><i class="fas fa-filter me-2" style="color:var(--accent-blue);"></i> Advanced Filtering</span>
        <div class="d-flex gap-2">
            <button type="button" id="resetFilters" class="btn btn-ghost btn-sm">
                <i class="fas fa-undo me-1"></i> Clear Filters
            </button>
            
            <a href="generate_report.php" id="exportPdfBtn" target="_blank" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf me-1"></i> Export Filtered PDF
            </a>
            <span class="status-badge status-assigned"><?php echo count($tickets); ?> tickets</span>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-2">
            <label class="form-label-dark">From Date</label>
            <input type="date" id="fromDate" class="form-control form-control-dark">
        </div>
        <div class="col-md-2">
            <label class="form-label-dark">To Date</label>
            <input type="date" id="toDate" class="form-control form-control-dark">
        </div>
        <div class="col-md-4">
            <label class="form-label-dark">Technician Name</label>
            <input type="text" id="techSearch" class="form-control form-control-dark" placeholder="Search Technician...">
        </div>
        <div class="col-md-4">
            <label class="form-label-dark">Company Name</label>
            <input type="text" id="compSearch" class="form-control form-control-dark" placeholder="Search Company...">
        </div>
    </div>

    <div class="table-responsive">
        <table id="faultsTable" class="table-dark-custom w-100">
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Company</th>
                    <th>Status</th>
                    <th>Technician</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $t): 
                    $statusMap = ['New'=>'new','Assigned'=>'assigned','In Progress'=>'progress','Resolved'=>'resolved'];
                    $cls = $statusMap[$t['status']] ?? 'new';
                ?>
                <tr class="clickable-row" onclick="window.location='fault_detail.php?id=<?php echo $t['id']; ?>'">
                    <td><strong style="color:var(--accent-blue);"><?php echo $t['ticket_id']; ?></strong></td>
                    <td><?php echo htmlspecialchars($t['company_name']); ?></td>
                    <td><span class="status-badge status-<?php echo $cls; ?>"><?php echo $t['status']; ?></span></td>
                    <td><?php echo htmlspecialchars($t['tech_name'] ?? 'Unassigned'); ?></td>
                    <td style="color:var(--text-muted);"><?php echo date('Y-m-d', strtotime($t['created_at'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // 1. Initialize DataTable
    var table = $('#faultsTable').DataTable({
        "dom": 'rtip', 
        "order": [[4, "desc"]]
    });

    // 2. Custom Date Range Filter for DataTables
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var min = $('#fromDate').val();
            var max = $('#toDate').val();
            var date = data[4]; 

            if (
                (min === "" && max === "") ||
                (min === "" && date <= max) ||
                (min <= date && max === "") ||
                (min <= date && date <= max)
            ) {
                return true;
            }
            return false;
        }
    );

    // 3. Update Function: Syncs Table View and Export PDF link
    function updateFilters() {
        const from = $('#fromDate').val();
        const to = $('#toDate').val();
        const tech = $('#techSearch').val();
        const comp = $('#compSearch').val();

        // Update PDF link with current parameters
        const params = new URLSearchParams({
            from: from,
            to: to,
            name: tech,
            company: comp
        });
        $('#exportPdfBtn').attr('href', 'generate_report.php?' + params.toString());

        // Apply column filters
        table.column(1).search(comp); // Company filter
        table.column(3).search(tech); // Technician filter
        
        // Apply Date filter redraw
        table.draw();
    }

    // 4. Clear Filters Logic
    $('#resetFilters').on('click', function() {
        // Reset HTML inputs
        $('#fromDate, #toDate, #techSearch, #compSearch').val('');
        
        // Reset DataTable and redraw
        table.search('').columns().search('').draw();
        
        // Reset PDF link
        $('#exportPdfBtn').attr('href', 'generate_report.php');
    });

    // Event listeners
    $('#fromDate, #toDate, #techSearch, #compSearch').on('change keyup', updateFilters);
});
</script>

<?php require_once '../includes/footer.php'; ?>