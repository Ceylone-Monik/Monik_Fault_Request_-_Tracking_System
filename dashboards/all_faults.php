<?php
// 1. Auth and Session check
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../config/db.php';

if (!in_array($_SESSION['role'] ?? '', ['Main Admin', 'Assign Admin'])) {
    header("Location: ../auth/login.php"); exit();
}

// 2. Fetch initial data
$tickets = $pdo->query("
    SELECT f.*, u.full_name AS tech_name
    FROM faults f
    LEFT JOIN users u ON f.assigned_to = u.id
    ORDER BY f.created_at DESC
")->fetchAll();

require_once '../includes/header.php';
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<div class="glass-card p-4 fade-slide-up">
    <div class="section-title mb-4 d-flex justify-content-between align-items-center">
        <span><i class="fas fa-filter me-2" style="color:var(--accent-blue);"></i> Advanced Filtering</span>
        <div class="d-flex gap-3">
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
                <?php foreach ($tickets as $t): ?>
                <tr class="clickable-row" onclick="window.location='fault_detail.php?id=<?php echo $t['id']; ?>'">
                    <td><strong style="color:var(--accent-blue);"><?php echo $t['ticket_id']; ?></strong></td>
                    <td><?php echo htmlspecialchars($t['company_name']); ?></td>
                    <td><?php echo $t['status']; ?></td>
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

    // 2. Add Custom Date Filter for DataTables
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var min = $('#fromDate').val();
            var max = $('#toDate').val();
            var date = data[4]; // Date is in Column 4

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

    // 3. Update Function to Sync PDF Link and Redraw Table
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

        // Apply column-specific filters
        table.column(1).search(comp); // Company Name filter
        table.column(3).search(tech); // Technician Name filter
        
        // Trigger redrawing for the Date Range filter
        table.draw();
    }

    // Attach event listeners to all filter inputs
    $('#fromDate, #toDate, #techSearch, #compSearch').on('change keyup', updateFilters);
});
</script>

<?php require_once '../includes/footer.php'; ?>