<?php
require '../includes/config.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = 'Admin Dashboard';

// Get statistics
$users_count = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$products_count = $conn->query("SELECT COUNT(*) as count FROM products WHERE status = 'active'")->fetch_assoc()['count'];
$orders_count = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$total_revenue = $conn->query("SELECT COALESCE(SUM(total), 0) as sum FROM orders WHERE status != 'cancelled'")->fetch_assoc()['sum'];

// Add custom orders revenue
$co_rev_check = $conn->query("SHOW TABLES LIKE 'custom_orders'");
if ($co_rev_check && $co_rev_check->num_rows > 0) {
    $custom_revenue = $conn->query("SELECT COALESCE(SUM(total_price), 0) as sum FROM custom_orders WHERE status NOT IN ('cancelled')")->fetch_assoc()['sum'];
    $total_revenue += $custom_revenue;
}

// Get custom designs count (if table exists)
$custom_designs_count = 0;
$pending_designs_count = 0;
$cd_check = $conn->query("SHOW TABLES LIKE 'custom_designs'");
if ($cd_check && $cd_check->num_rows > 0) {
    $custom_designs_count = $conn->query("SELECT COUNT(*) as count FROM custom_designs")->fetch_assoc()['count'];
    $pending_designs_count = $conn->query("SELECT COUNT(*) as count FROM custom_designs WHERE status = 'pending'")->fetch_assoc()['count'];
}

// Get custom orders count (if table exists)
$custom_orders_count = 0;
$pending_custom_orders = 0;
$co_check = $conn->query("SHOW TABLES LIKE 'custom_orders'");
if ($co_check && $co_check->num_rows > 0) {
    $custom_orders_count = $conn->query("SELECT COUNT(*) as count FROM custom_orders")->fetch_assoc()['count'];
    $pending_custom_orders = $conn->query("SELECT COUNT(*) as count FROM custom_orders WHERE status IN ('pending_payment','payment_uploaded')")->fetch_assoc()['count'];
}

// Get recent orders
$recent_orders = $conn->query("SELECT o.*, u.fullname FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5");

// Get monthly revenue data for chart (last 6 months) - combine regular + custom orders
$revenue_data = [];
$_co_exists = $conn->query("SHOW TABLES LIKE 'custom_orders'");
if ($_co_exists && $_co_exists->num_rows > 0) {
    $revenue_query = $conn->query("
        SELECT m.month, m.label,
               COALESCE(r.revenue, 0) + COALESCE(c.revenue, 0) as revenue,
               COALESCE(r.order_count, 0) + COALESCE(c.order_count, 0) as order_count
        FROM (
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, DATE_FORMAT(created_at, '%b %Y') as label
            FROM orders WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            UNION
            SELECT DATE_FORMAT(created_at, '%Y-%m'), DATE_FORMAT(created_at, '%b %Y')
            FROM custom_orders WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
        ) m
        LEFT JOIN (
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total) as revenue, COUNT(*) as order_count
            FROM orders WHERE status != 'cancelled' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ) r ON m.month = r.month
        LEFT JOIN (
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_price) as revenue, COUNT(*) as order_count
            FROM custom_orders WHERE status NOT IN ('cancelled') AND created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ) c ON m.month = c.month
        ORDER BY m.month ASC
    ");
} else {
    $revenue_query = $conn->query("
        SELECT DATE_FORMAT(created_at, '%Y-%m') as month, 
               DATE_FORMAT(created_at, '%b %Y') as label,
               COALESCE(SUM(total), 0) as revenue,
               COUNT(*) as order_count
        FROM orders 
        WHERE status != 'cancelled' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY month ASC
    ");
}
if ($revenue_query) {
    while ($row = $revenue_query->fetch_assoc()) {
        $revenue_data[] = $row;
    }
}
$chart_labels = json_encode(array_column($revenue_data, 'label'));
$chart_revenue = json_encode(array_map('floatval', array_column($revenue_data, 'revenue')));
$chart_orders = json_encode(array_map('intval', array_column($revenue_data, 'order_count')));
?>

<?php include '../includes/header/header.php'; ?>
<?php include '../includes/admin-sidebar.php'; ?>

<div class="admin-container">
    <h1 class="text-coffee-dark mb-4" style="font-size: 2.5rem; font-weight: 800;">
        <i class="fas fa-tachometer-alt"></i> Admin Dashboard
    </h1>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value"><?php echo number_format($users_count); ?></div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value"><?php echo number_format($products_count); ?></div>
                <div class="stat-label">Products</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value"><?php echo number_format($orders_count); ?></div>
                <div class="stat-label">Total Orders</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-value">₱<?php echo number_format($total_revenue, 2); ?></div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="admin-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="text-coffee-dark mb-0" style="font-weight: 700;">
                <i class="fas fa-chart-line"></i> Revenue Trends
            </h5>
            <span class="text-muted" style="font-size: 0.82rem;">Last 6 months</span>
        </div>
        <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Quick Access Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <a href="products.php" class="admin-quick-card">
                <div class="quick-card-icon"><i class="fas fa-shirt"></i></div>
                <div>
                    <h6>Products</h6>
                    <small class="text-muted"><?php echo number_format($products_count); ?> items</small>
                </div>
                <i class="fas fa-chevron-right ms-auto"></i>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="orders.php" class="admin-quick-card">
                <div class="quick-card-icon"><i class="fas fa-receipt"></i></div>
                <div>
                    <h6>Orders</h6>
                    <small class="text-muted"><?php echo number_format($orders_count); ?> total</small>
                </div>
                <i class="fas fa-chevron-right ms-auto"></i>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="users.php" class="admin-quick-card">
                <div class="quick-card-icon"><i class="fas fa-users"></i></div>
                <div>
                    <h6>Users</h6>
                    <small class="text-muted"><?php echo number_format($users_count); ?> registered</small>
                </div>
                <i class="fas fa-chevron-right ms-auto"></i>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="custom-designs.php" class="admin-quick-card">
                <div class="quick-card-icon"><i class="fas fa-palette"></i></div>
                <div>
                    <h6>Custom Designs</h6>
                    <small class="text-muted"><?php echo $pending_designs_count; ?> pending</small>
                </div>
                <?php if ($pending_designs_count > 0): ?>
                    <span class="badge bg-warning ms-auto"><?php echo $pending_designs_count; ?></span>
                <?php else: ?>
                    <i class="fas fa-chevron-right ms-auto"></i>
                <?php endif; ?>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="contact-management.php" class="admin-quick-card">
                <div class="quick-card-icon"><i class="fas fa-envelope"></i></div>
                <div>
                    <h6>Contact Messages</h6>
                    <small class="text-muted">View submissions</small>
                </div>
                <i class="fas fa-chevron-right ms-auto"></i>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="custom-orders.php" class="admin-quick-card">
                <div class="quick-card-icon"><i class="fas fa-list-check"></i></div>
                <div>
                    <h6>Custom Orders</h6>
                    <small class="text-muted"><?php echo $pending_custom_orders; ?> pending</small>
                </div>
                <?php if ($pending_custom_orders > 0): ?>
                    <span class="badge bg-danger ms-auto"><?php echo $pending_custom_orders; ?></span>
                <?php else: ?>
                    <i class="fas fa-chevron-right ms-auto"></i>
                <?php endif; ?>
            </a>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="admin-card">
        <h5 class="text-coffee-dark mb-4" style="font-weight: 700;">
            <i class="fas fa-clock"></i> Recent Orders
        </h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $recent_orders->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $order['id']; ?></td>
                        <td><?php echo htmlspecialchars($order['fullname']); ?></td>
                        <td>₱<?php echo number_format($order['total'], 2); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $order['status'] === 'completed' ? 'success' : ($order['status'] === 'pending' ? 'warning' : 'danger'); ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </td>
                        <td><?php echo ucfirst(str_replace('_', ' ', $order['payment_method'])); ?></td>
                        <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                        <td>
                            <a href="orders.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
    </div><!-- /admin-main-content -->
</div><!-- /admin-layout -->

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Revenue Chart
const ctx = document.getElementById('revenueChart');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo $chart_labels; ?>,
            datasets: [{
                label: 'Revenue (₱)',
                data: <?php echo $chart_revenue; ?>,
                backgroundColor: 'rgba(26, 26, 26, 0.85)',
                borderColor: '#1a1a1a',
                borderWidth: 1,
                borderRadius: 6,
                barPercentage: 0.6
            }, {
                label: 'Orders',
                data: <?php echo $chart_orders; ?>,
                type: 'line',
                borderColor: '#c8a96e',
                backgroundColor: 'rgba(200, 169, 110, 0.15)',
                borderWidth: 2.5,
                pointBackgroundColor: '#c8a96e',
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.3,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    position: 'top',
                    labels: { usePointStyle: true, padding: 20, font: { family: 'Inter', size: 12 } }
                },
                tooltip: {
                    backgroundColor: '#1a1a1a',
                    titleFont: { family: 'Inter', size: 13 },
                    bodyFont: { family: 'Inter', size: 12 },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            if (context.dataset.label === 'Revenue (₱)') {
                                return '₱' + context.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2});
                            }
                            return context.parsed.y + ' orders';
                        }
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 11 } } },
                y: {
                    position: 'left',
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        font: { family: 'Inter', size: 11 },
                        callback: function(val) { return '₱' + val.toLocaleString(); }
                    }
                },
                y1: {
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    ticks: { font: { family: 'Inter', size: 11 }, stepSize: 1 },
                    title: { display: true, text: 'Orders', font: { family: 'Inter', size: 11 } }
                }
            }
        }
    });
}

// Sidebar toggle
document.addEventListener('DOMContentLoaded', function() {
    var toggle = document.getElementById('sidebarToggle');
    var sidebar = document.getElementById('adminSidebar');
    var overlay = document.getElementById('sidebarOverlay');
    var close = document.getElementById('sidebarClose');
    
    function openSidebar() { sidebar.classList.add('open'); overlay.classList.add('active'); }
    function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('active'); }
    
    if (toggle) toggle.addEventListener('click', openSidebar);
    if (overlay) overlay.addEventListener('click', closeSidebar);
    if (close) close.addEventListener('click', closeSidebar);
});
</script>

<?php include '../includes/footer/footer.php'; ?>
