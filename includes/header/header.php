<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - Thread & Press Hub' : 'Thread & Press Hub'; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../css/style.css' : 'css/style.css'; ?>" rel="stylesheet">
</head>
<?php
$bodyClasses = [];
if (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) {
    $bodyClasses[] = 'admin-page';
}
if (!empty($bodyClass)) {
    $bodyClasses[] = $bodyClass;
}
?>
<body<?php echo !empty($bodyClasses) ? ' class="' . htmlspecialchars(implode(' ', $bodyClasses)) . '"' : ''; ?>>
    <!-- Announcement Bar -->
    <div class="top-info-bar text-center">
        <div class="container">
            <small class="text-muted">Free shipping on orders over ₱2,000 | Use code: FREESHIP</small>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg cafe-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../index.php' : 'index.php'; ?>">
                <span class="brand-logo">TP</span> Thread &amp; Press Hub
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php if (strpos($_SERVER['PHP_SELF'], '/admin/') === false): ?>
                <ul class="navbar-nav mx-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="shop.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="shop.php?gender=mens">Men</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="shop.php?gender=womens">Women</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="shop.php?gender=kids">Kids</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="shop.php?category=accessories">Accessories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="custom-design.php"><i class="fas fa-palette me-1"></i>Design</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="my-custom-orders.php"><i class="fas fa-shirt me-1"></i>Custom Orders</a>
                    </li>
                    <?php endif; ?>
                </ul>
                <?php endif; ?>
                <ul class="navbar-nav align-items-center <?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') === false) ? '' : 'ms-auto'; ?>">
                    <?php if (strpos($_SERVER['PHP_SELF'], '/admin/') === false): ?>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="cart.php">
                            <i class="fas fa-shopping-bag"></i>
                            <span class="badge bg-danger" id="navCartCount" style="display:none;">0</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? 'profile.php' : 'profile.php'; ?>"><i class="fas fa-user me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../orders.php' : 'orders.php'; ?>"><i class="fas fa-box me-2"></i>Orders</a></li>
                                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? 'dashboard.php' : 'admin/dashboard.php'; ?>"><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../logout.php' : 'logout.php'; ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link btn-signin" href="login.php">Sign In</a>
                        </li>
                        <li class="nav-item ms-1">
                            <a class="btn btn-register" href="register.php">Sign Up</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Search Overlay -->
    <div class="search-overlay" id="searchOverlay">
        <div class="container">
            <div class="search-bar">
                <i class="fas fa-search" style="color: var(--text-light);"></i>
                <input type="text" id="searchInput" placeholder="Search for products..." autocomplete="off">
                <button type="button" onclick="performSearch()">Search</button>
            </div>
        </div>
    </div>

    <script>
        // Active nav link
        document.addEventListener('DOMContentLoaded', function() {
            var path = window.location.pathname.split('/').pop();
            var search = window.location.search;
            if (!path) path = 'index.php';
            document.querySelectorAll('.cafe-navbar .nav-link').forEach(function(link) {
                var href = link.getAttribute('href');
                if (href === path || href === path + search) {
                    link.classList.add('active');
                }
            });

            // Search toggle
            var searchBtn = document.getElementById('navSearchBtn');
            var searchOverlay = document.getElementById('searchOverlay');
            if (searchBtn && searchOverlay) {
                searchBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    searchOverlay.classList.toggle('active');
                    if (searchOverlay.classList.contains('active')) {
                        document.getElementById('searchInput').focus();
                    }
                });
                document.getElementById('searchInput').addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') performSearch();
                });
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') searchOverlay.classList.remove('active');
                });
            }
        });

        function performSearch() {
            var query = document.getElementById('searchInput').value.trim();
            if (query) {
                window.location.href = 'shop.php?search=' + encodeURIComponent(query);
            }
        }
    </script>
    <main class="container-fluid" style="padding:0;">
