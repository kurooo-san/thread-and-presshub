<?php
require 'includes/config.php';
$pageTitle = 'Promotions';
?>

<?php include 'includes/header/header.php'; ?>

<div class="container my-5">
    <div class="mb-5">
        <h1 class="display-5" style="font-weight: 800; margin-bottom: 1rem;">Current Promotions</h1>
        <p class="lead text-muted">Don't miss out on our exclusive deals and limited-time offers</p>
    </div>

    <!-- Featured Promotion Banner -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 p-5" style="background: linear-gradient(135deg, #1a1a1a 0%, #333 100%); color: white; border-radius: var(--radius-lg);">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 style="font-weight: 800; font-size: 2.5rem; margin-bottom: 1rem;">🎉 Spring Sale is Live!</h2>
                        <p style="font-size: 1.2rem; margin-bottom: 1.5rem;">Get <strong>UP TO 40% OFF</strong> on our entire spring collection. Use code <strong>SPRING40</strong> at checkout.</p>
                        <p style="font-size: 0.95rem; opacity: 0.95;">Valid until March 31, 2026 · Free shipping on orders over $50 · No minimum purchase required</p>
                    </div>
                    <div class="col-lg-4 text-center">
                        <a href="shop.php" class="btn btn-light btn-lg" style="padding: 0.75rem 2rem; font-weight: 700;">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Promotions Grid -->
    <div class="row g-4 mb-5">
        <!-- Promo 1 -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 promo-card">
                <div style="background: linear-gradient(135deg, #667bc0 0%, #8a9dd8 100%); height: 200px; display: flex; align-items: center; justify-content: center; color: white;">
                    <div class="text-center">
                        <h3 style="font-weight: 800; font-size: 3rem; margin: 0;">BUY 2<br>GET 1</h3>
                        <p style="margin-top: 0.5rem; font-size: 0.9rem;">On selected items</p>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title" style="font-weight: 700;">T-Shirts & Tops Bundle</h5>
                    <p class="card-text text-muted">Get amazing value with our buy 2 get 1 free promotion on selected t-shirts and tops. Perfect for updating your wardrobe!</p>
                    <small class="text-muted"><i class="fas fa-calendar"></i> Valid until March 15, 2026</small>
                </div>
            </div>
        </div>

        <!-- Promo 2 -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 promo-card">
                <div style="background: linear-gradient(135deg, #f5a623 0%, #f8b739 100%); height: 200px; display: flex; align-items: center; justify-content: center; color: white;">
                    <div class="text-center">
                        <h3 style="font-weight: 800; font-size: 3rem; margin: 0;">25% OFF</h3>
                        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Accessories Collection</p>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title" style="font-weight: 700;">Accessories Sale</h5>
                    <p class="card-text text-muted">Complement your outfits with our discounted accessories. Belts, scarves, hats, and more at unbeatable prices!</p>
                    <small class="text-muted"><i class="fas fa-calendar"></i> Valid until March 20, 2026</small>
                </div>
            </div>
        </div>

        <!-- Promo 3 -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 promo-card">
                <div style="background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); height: 200px; display: flex; align-items: center; justify-content: center; color: white;">
                    <div class="text-center">
                        <h3 style="font-weight: 800; font-size: 3rem; margin: 0;">FREE SHIPPING<br>₱1,500+</h3>
                        <p style="margin-top: 0.5rem; font-size: 0.9rem;">All orders</p>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title" style="font-weight: 700;">Free Shipping Offer</h5>
                    <p class="card-text text-muted">Enjoy complimentary shipping on all orders over ₱1,500. Fast delivery to your doorstep with no extra costs!</p>
                    <small class="text-muted"><i class="fas fa-calendar"></i> Ongoing offer</small>
                </div>
            </div>
        </div>

        <!-- Promo 4 -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 promo-card">
                <div style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); height: 200px; display: flex; align-items: center; justify-content: center; color: white;">
                    <div class="text-center">
                        <h3 style="font-weight: 800; font-size: 3rem; margin: 0;">30% OFF</h3>
                        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Clearance items</p>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title" style="font-weight: 700;">Clearance Collection</h5>
                    <p class="card-text text-muted">Make room for new arrivals! Enjoy 30% off on selected clearance items. Limited styles and quantities available.</p>
                    <small class="text-muted"><i class="fas fa-calendar"></i> Valid until April 5, 2026</small>
                </div>
            </div>
        </div>

        <!-- Promo 5 -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 promo-card">
                <div style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); height: 200px; display: flex; align-items: center; justify-content: center; color: white;">
                    <div class="text-center">
                        <h3 style="font-weight: 800; font-size: 2rem; margin: 0;">VIP MEMBERS<br>15% OFF</h3>
                        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Exclusive offer</p>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title" style="font-weight: 700;">VIP Member Exclusive</h5>
                    <p class="card-text text-muted">Sign up for our loyalty program and get 15% off all purchases. Plus early access to new collections!</p>
                    <small class="text-muted"><i class="fas fa-calendar"></i> Ongoing benefit</small>
                </div>
            </div>
        </div>

        <!-- Promo 6 -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 promo-card">
                <div style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); height: 200px; display: flex; align-items: center; justify-content: center; color: white;">
                    <div class="text-center">
                        <h3 style="font-weight: 800; font-size: 2rem; margin: 0;">REFER A FRIEND<br>₱150 OFF</h3>
                        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Each successful referral</p>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title" style="font-weight: 700;">Referral Program</h5>
                    <p class="card-text text-muted">Share the love! Refer friends and family, and both of you get ₱150 off your orders. No limit on referrals!</p>
                    <small class="text-muted"><i class="fas fa-calendar"></i> Ongoing program</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms Section -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 bg-light p-4">
                <h5 style="font-weight: 700; margin-bottom: 1rem;">Terms & Conditions</h5>
                <ul class="text-muted small">
                    <li>Promotions cannot be combined unless explicitly stated</li>
                    <li>Discounts are applied at checkout after taxes and shipping</li>
                    <li>Clearance items are final sale and cannot be returned</li>
                    <li>Offer codes must be valid at time of purchase</li>
                    <li>Thread and Press Hub reserves the right to modify or cancel promotions</li>
                    <li>Sale prices apply only to items marked as "on sale" in the shop</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    .promo-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }
    .promo-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12) !important;
    }
</style>

<?php include 'includes/footer/footer.php'; ?>
