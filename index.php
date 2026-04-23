<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'includes/config.php';
$pageTitle = 'Home';
$bodyClass = 'home-page';
include 'includes/header/header.php';
?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-bg" style="background-image: url('images/hero/clark-street-mercantile-qnKhZJPKFD8-unsplash.jpg');"></div>
        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center" style="min-height: 85vh;">
                <div class="col-lg-7 hero-content">
                    <div class="hero-panel">
                        <span class="hero-badge">New Collection 2026</span>
                        <h1>Elevate Your Style<br>with Thread &amp; Press Hub</h1>
                        <p class="hero-subtitle">Discover premium apparel that combines comfort, quality, and modern aesthetics. Curated collections for every occasion.</p>
                        <div class="hero-buttons">
                            <a href="shop.php" class="btn btn-hero">Shop Now <i class="fas fa-arrow-right ms-2"></i></a>
                            <a href="about.php" class="btn btn-hero-outline">Learn More</a>
                        </div>
                        <div class="hero-metrics">
                            <div class="hero-metric">
                                <strong>500+</strong>
                                <span>Styled pieces</span>
                            </div>
                            <div class="hero-metric">
                                <strong>4.9</strong>
                                <span>Customer rating</span>
                            </div>
                            <div class="hero-metric">
                                <strong>24h</strong>
                                <span>Custom design review</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Bar -->
    <section class="features-bar home-section home-features-section">
        <div class="container">
            <div class="home-surface">
                <div class="row">
                    <div class="col-md-3 col-6 mb-3 mb-md-0">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-truck"></i></div>
                            <div>
                                <h6>Free Shipping</h6>
                                <p>On orders over ₱2,000</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3 mb-md-0">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-shield-halved"></i></div>
                            <div>
                                <h6>Secure Payment</h6>
                                <p>100% secure checkout</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-gem"></i></div>
                            <div>
                                <h6>Premium Quality</h6>
                                <p>Handpicked materials</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-rotate-left"></i></div>
                            <div>
                                <h6>Easy Returns</h6>
                                <p>30-day return policy</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Shop by Category -->
    <section class="py-5 home-section">
        <div class="container">
            <div class="home-surface">
                <div class="section-heading">
                    <h2>Shop by Category</h2>
                    <p>Browse our curated collections for every style and occasion</p>
                </div>
                <div class="row g-4">
                    <div class="col-md-3 col-6">
                        <a href="shop.php?gender=mens" class="category-card">
                            <img src="images/hero/mens-card.jpg" alt="Men" onerror="this.src='https://placehold.co/400x500/1a1a1a/ffffff?text=Men'">
                            <div class="category-overlay">
                                <h4>Men</h4>
                                <span>250+ Products</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="shop.php?gender=womens" class="category-card">
                            <img src="images/hero/womens-card.jpg" alt="Women" onerror="this.src='https://placehold.co/400x500/333333/ffffff?text=Women'">
                            <div class="category-overlay">
                                <h4>Women</h4>
                                <span>350+ Products</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="shop.php?gender=kids" class="category-card">
                            <img src="images/hero/kids-card.jpg" alt="Kids" onerror="this.src='https://placehold.co/400x500/555555/ffffff?text=Kids'">
                            <div class="category-overlay">
                                <h4>Kids</h4>
                                <span>150+ Products</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="shop.php?category=accessories" class="category-card">
                            <img src="images/hero/accessories-card.jpg" alt="Accessories" onerror="this.src='https://placehold.co/400x500/777777/ffffff?text=Accessories'">
                            <div class="category-overlay">
                                <h4>Accessories</h4>
                                <span>100+ Products</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <?php
    $featured_result = $conn->query("SELECT * FROM products WHERE status = 'active' ORDER BY id DESC LIMIT 8");
    ?>
    <section class="py-5 home-section" style="background: var(--bg-light);">
        <div class="container">
            <div class="home-surface">
                <div class="d-flex justify-content-between align-items-end mb-4 section-heading-split">
                    <div class="section-heading text-start mb-0">
                        <h2>Featured Products</h2>
                        <p>Our most popular picks this season</p>
                    </div>
                    <a href="shop.php" class="btn btn-outline-dark btn-sm" style="white-space:nowrap;">View All <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
                <div class="row g-4">
                    <?php if ($featured_result && $featured_result->num_rows > 0): ?>
                        <?php while ($p = $featured_result->fetch_assoc()): ?>
                            <div class="col-lg-3 col-md-4 col-6">
                                <div class="product-card">
                                    <div class="product-image-wrapper">
                                        <img src="images/products/<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" class="product-image" onerror="this.src='https://placehold.co/300x380/f0f0f0/999?text=<?php echo urlencode($p['name']); ?>'">
                                        <div class="product-actions">
                                            <button class="product-action-btn" onclick="addToCart(<?php echo (int)$p['id']; ?>, '<?php echo htmlspecialchars(addslashes($p['name']), ENT_QUOTES); ?>', <?php echo (float)$p['price']; ?>)" title="Add to Cart">
                                                <i class="fas fa-shopping-bag"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="product-body">
                                        <h5 class="product-name"><?php echo htmlspecialchars($p['name']); ?></h5>
                                        <div class="product-price">₱<?php echo number_format($p['price'], 2); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-box-open"></i>
                                <h5>No products yet</h5>
                                <p>Check back soon for our latest arrivals!</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Promo Banner -->
    <section class="home-section">
        <div class="container">
            <div class="promo-banner">
                <img src="images/hero/promo-banner.jpg" alt="Summer Sale Promo">
                <div class="promo-content container text-center">
                    <span class="hero-badge" style="background:rgba(255,255,255,0.2); color:#fff; border-color:rgba(255,255,255,0.3);">Limited Time Offer</span>
                    <h2>Summer Sale — Up to 50% Off</h2>
                    <p>Don't miss our biggest sale of the season. Premium styles at unbeatable prices.</p>
                    <a href="shop.php" class="btn btn-light btn-lg px-5">Shop the Sale <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Special Discounts  -->
    <section class="py-5 home-section">
        <div class="container">
            <div class="home-surface">
                <div class="section-heading">
                    <h2>Special Discounts</h2>
                    <p>We support our community with exclusive discounts</p>
                </div>
                <div class="row g-4 justify-content-center">
                    <div class="col-md-5">
                        <div class="discount-card">
                            <div class="discount-icon"><i class="fas fa-wheelchair"></i></div>
                            <h5>PWD Discount</h5>
                            <p class="discount-rate">20% OFF</p>
                            <p class="discount-copy">Valid PWD ID required at checkout</p>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="discount-card">
                            <div class="discount-icon"><i class="fas fa-users"></i></div>
                            <h5>Senior Citizen Discount</h5>
                            <p class="discount-rate">20% OFF</p>
                            <p class="discount-copy">Valid Senior Citizen ID required at checkout</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer/footer.php'; ?>