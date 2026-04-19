<?php
// Determine current page for active state
$currentPage = basename($_SERVER['PHP_SELF']);

// Get sidebar badge counts if not already set
if (!isset($pending_designs_count)) {
    $pending_designs_count = 0;
    $_cd_check = $conn->query("SHOW TABLES LIKE 'custom_designs'");
    if ($_cd_check && $_cd_check->num_rows > 0) {
        $pending_designs_count = (int)$conn->query("SELECT COUNT(*) as c FROM custom_designs WHERE status = 'pending'")->fetch_assoc()['c'];
    }
}
if (!isset($pending_custom_orders)) {
    $pending_custom_orders = 0;
    $_co_check = $conn->query("SHOW TABLES LIKE 'custom_orders'");
    if ($_co_check && $_co_check->num_rows > 0) {
        $pending_custom_orders = (int)$conn->query("SELECT COUNT(*) as c FROM custom_orders WHERE status IN ('pending_payment','payment_uploaded')")->fetch_assoc()['c'];
    }
}
?>
<div class="admin-layout" style="display:flex;">
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar" style="width:260px;min-width:260px;background:#1a1a1a;color:#fff;display:flex;flex-direction:column;flex-shrink:0;position:sticky;top:0;height:100vh;overflow-y:auto;z-index:1040;">
        <div class="sidebar-header">
            <span class="sidebar-brand"><i class="fas fa-tachometer-alt"></i> Admin Panel</span>
            <button class="sidebar-close d-lg-none" id="sidebarClose"><i class="fas fa-times"></i></button>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="sidebar-link <?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-chart-pie"></i> <span>Dashboard</span>
            </a>
            <div class="sidebar-divider"></div>
            <div class="sidebar-section-title">Management</div>
            <a href="products.php" class="sidebar-link <?php echo $currentPage === 'products.php' ? 'active' : ''; ?>">
                <i class="fas fa-shirt"></i> <span>Products</span>
            </a>
            <a href="orders.php" class="sidebar-link <?php echo $currentPage === 'orders.php' || $currentPage === 'order_details.php' ? 'active' : ''; ?>">
                <i class="fas fa-receipt"></i> <span>Orders</span>
            </a>
            <a href="users.php" class="sidebar-link <?php echo $currentPage === 'users.php' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> <span>Users</span>
            </a>
            <div class="sidebar-divider"></div>
            <div class="sidebar-section-title">Custom</div>
            <a href="custom-designs.php" class="sidebar-link <?php echo $currentPage === 'custom-designs.php' ? 'active' : ''; ?>">
                <i class="fas fa-palette"></i> <span>Custom Designs</span>
                <?php if (isset($pending_designs_count) && $pending_designs_count > 0): ?>
                    <span class="sidebar-badge bg-warning"><?php echo $pending_designs_count; ?></span>
                <?php endif; ?>
            </a>
            <a href="custom-orders.php" class="sidebar-link <?php echo $currentPage === 'custom-orders.php' ? 'active' : ''; ?>">
                <i class="fas fa-list-check"></i> <span>Custom Orders</span>
                <?php if (isset($pending_custom_orders) && $pending_custom_orders > 0): ?>
                    <span class="sidebar-badge bg-danger"><?php echo $pending_custom_orders; ?></span>
                <?php endif; ?>
            </a>
            <div class="sidebar-divider"></div>
            <div class="sidebar-section-title">Communication</div>
            <a href="contact-management.php" class="sidebar-link <?php echo $currentPage === 'contact-management.php' ? 'active' : ''; ?>">
                <i class="fas fa-envelope"></i> <span>Contact Messages</span>
            </a>
            <a href="support-chat.php" class="sidebar-link <?php echo $currentPage === 'support-chat.php' ? 'active' : ''; ?>">
                <i class="fas fa-headset"></i> <span>Support Chat</span>
            </a>
            <div class="sidebar-divider"></div>
            <a href="profile.php" class="sidebar-link <?php echo $currentPage === 'profile.php' ? 'active' : ''; ?>">
                <i class="fas fa-user-gear"></i> <span>My Profile</span>
            </a>
            <a href="audit-log.php" class="sidebar-link <?php echo $currentPage === 'audit-log.php' ? 'active' : ''; ?>">
                <i class="fas fa-clipboard-list"></i> <span>Audit Log</span>
            </a>
            <a href="../index.php" class="sidebar-link">
                <i class="fas fa-store"></i> <span>View Store</span>
            </a>
        </nav>
    </aside>
    <!-- Sidebar overlay for mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <!-- Main Content -->
    <div class="admin-main-content" style="flex:1;min-width:0;">
        <!-- Mobile sidebar toggle -->
        <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
