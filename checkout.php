<?php
require 'includes/config.php';
redirectToLogin();

$pageTitle = 'Checkout';
$error = '';
$success = '';

// Get user discount type and address from database
$user_discount = 'regular';
$user_address = '';
$user_data = null;
$user_stmt = $conn->prepare("SELECT user_type, street_address, barangay, city, province, zipcode FROM users WHERE id = ?");
$user_stmt->bind_param("i", $_SESSION['user_id']);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
if ($user_result->num_rows > 0) {
    $user_data = $user_result->fetch_assoc();
    $user_discount = $user_data['user_type'];
    $address_parts = array_filter([
        $user_data['street_address'] ?? '',
        $user_data['barangay'] ?? '',
        $user_data['city'] ?? '',
        $user_data['province'] ?? '',
        $user_data['zipcode'] ?? ''
    ]);
    $user_address = implode(', ', $address_parts);
}
$user_stmt->close();

// Check if subtotal exists and is greater than 0
$subtotal_from_session = floatval($_POST['subtotal'] ?? 0);
if ($subtotal_from_session <= 0) {
    // Check if coming from cart page with empty cart
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: cart.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = sanitizeInput($_POST['payment_method'] ?? '');
    $delivery_address = sanitizeInput($_POST['delivery_address'] ?? '');
    $delivery_method = sanitizeInput($_POST['delivery_method'] ?? 'delivery');
    $notes = sanitizeInput($_POST['notes'] ?? '');
    $discount_type = sanitizeInput($_POST['discount_type'] ?? 'regular');
    $subtotal = floatval($_POST['subtotal'] ?? 0);

    // Validate discount type matches user account type
    if ($discount_type !== 'regular' && $discount_type !== $user_discount) {
        $discount_type = 'regular';
    }

    // For store pickup, set address and zero delivery fee
    if ($delivery_method === 'pickup') {
        $delivery_address = 'Store Pickup';
    }

    // Validate cart is not empty
    if ($subtotal <= 0) {
        $error = 'Your cart is empty! Please add items before checking out.';
    } elseif (empty($payment_method) || ($delivery_method === 'delivery' && empty($delivery_address))) {
        $error = 'Please fill in all required fields!';
    } elseif (!verifyCsrfToken()) {
        $error = 'Invalid form submission. Please try again.';
    } else {
        // Server-side price validation: re-check prices from database
        $cart_items_raw = $_POST['cart_items'] ?? '[]';
        $items_to_validate = json_decode($cart_items_raw, true);
        $validated_subtotal = 0;
        $price_mismatch = false;

        if (is_array($items_to_validate)) {
            foreach ($items_to_validate as &$item) {
                $pid = intval($item['id']);
                $check_stmt = $conn->prepare("SELECT price FROM products WHERE id = ? AND status = 'active'");
                $check_stmt->bind_param("i", $pid);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                if ($check_result->num_rows > 0) {
                    $db_price = floatval($check_result->fetch_assoc()['price']);
                    if (abs($db_price - floatval($item['price'])) > 0.01) {
                        $price_mismatch = true;
                        $item['price'] = $db_price; // correct the price
                    }
                    $validated_subtotal += $db_price * intval($item['quantity']);
                } else {
                    $error = 'One or more products in your cart are no longer available.';
                    break;
                }
                $check_stmt->close();
            }
            unset($item);
        }

        if ($price_mismatch && empty($error)) {
            $subtotal = $validated_subtotal;
            $cart_items_raw = json_encode($items_to_validate);
        }

        if (empty($error)) {
        // Calculate discount
        $discount_percent = calculateDiscount($discount_type);
        $discount_calc = applyDiscount($subtotal, $discount_percent);
        $discount_amount = $discount_calc['discount_amount'];
        $discounted_total = $discount_calc['total'];
        $delivery_fee = ($delivery_method === 'pickup') ? 0 : 50;
        $final_total = $discounted_total + $delivery_fee;

        // Get validated cart data
        $cart_items = $cart_items_raw;

        // Insert order
        $stmt = $conn->prepare("INSERT INTO orders (user_id, subtotal, discount_amount, discount_type, delivery_fee, total, payment_method, delivery_address, notes, status, created_at) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");
        
        $stmt->bind_param("iddsidsss", 
            $_SESSION['user_id'], 
            $subtotal, 
            $discount_amount, 
            $discount_type, 
            $delivery_fee, 
            $final_total, 
            $payment_method, 
            $delivery_address, 
            $notes
        );

        if ($stmt->execute()) {
            $order_id = $conn->insert_id;

            // Insert order items (with color and size if provided)
            $items = json_decode($cart_items, true);
            
            // Check if color/size columns exist
            $columns_check = $conn->query("SHOW COLUMNS FROM order_items LIKE 'color'");
            $has_color_size = ($columns_check && $columns_check->num_rows > 0);
            
            foreach ($items as $item) {
                $item_subtotal = $item['price'] * $item['quantity'];
                $product_id = intval($item['id']);
                $quantity = intval($item['quantity']);
                $unit_price = floatval($item['price']);
                $item_color = isset($item['color']) ? $item['color'] : '';
                $item_size = isset($item['size']) ? $item['size'] : '';

                if ($has_color_size) {
                    // Insert with color and size
                    $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price, subtotal, color, size) 
                                                VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $item_stmt->bind_param("iiiddss", $order_id, $product_id, $quantity, $unit_price, $item_subtotal, $item_color, $item_size);
                } else {
                    // Insert without color and size (fallback)
                    $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price, subtotal) 
                                                VALUES (?, ?, ?, ?, ?)");
                    $item_stmt->bind_param("iidid", $order_id, $product_id, $quantity, $unit_price, $item_subtotal);
                }
                
                if ($item_stmt->execute()) {
                    // Success
                } else {
                    // Log error but continue
                    error_log("Failed to insert order item: " . $item_stmt->error);
                }
                $item_stmt->close();
            }

            $_SESSION['last_order_id'] = $order_id;
            $_SESSION['payment_method'] = $payment_method;
            $_SESSION['order_total'] = $final_total;

            // Send order confirmation email
            require_once 'includes/email-helper.php';
            sendOrderConfirmationEmail($conn, $order_id);

            // Link any pending custom designs to this order
            $designStmt = $conn->prepare("UPDATE custom_designs SET order_id = ? WHERE user_id = ? AND order_id IS NULL AND status = 'pending'");
            $designStmt->bind_param("ii", $order_id, $_SESSION['user_id']);
            $designStmt->execute();
            $designStmt->close();

            // Redirect to payment or order confirmation
            if ($payment_method === 'gcash') {
                header("Location: payment_gcash.php?order_id=" . $order_id);
            } elseif ($payment_method === 'maya') {
                header("Location: payment_maya.php?order_id=" . $order_id);
            } else {
                header("Location: order_confirmation.php?order_id=" . $order_id);
            }
            exit();
        } else {
            $error = 'Failed to create order. Please try again.';
        }
        $stmt->close();
    } // end price validation check
    }
}

?>

<?php include 'includes/header/header.php'; ?>

<div class="container my-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="cart.php">Cart</a></li>
            <li class="breadcrumb-item active">Checkout</li>
        </ol>
    </nav>
    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 2rem;">Checkout</h1>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" id="checkoutForm">
        <?php echo csrfTokenField(); ?>
        <div class="row">
            <div class="col-lg-8">
                <!-- Delivery Method -->
                <div class="card mb-4" style="border: 1px solid var(--border-light); border-radius: var(--radius-md);">
                    <div class="card-body p-4">
                        <h5 class="mb-3" style="font-weight: 600;"><i class="fas fa-shipping-fast" style="margin-right: 0.5rem;"></i> Delivery Method</h5>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="delivery_method" id="deliveryMethod" value="delivery" checked onchange="toggleDeliveryAddress()">
                            <label class="form-check-label" for="deliveryMethod" style="cursor: pointer;">
                                <strong>Deliver to Address</strong> — We'll ship to your saved address
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="delivery_method" id="pickupMethod" value="pickup" onchange="toggleDeliveryAddress()">
                            <label class="form-check-label" for="pickupMethod" style="cursor: pointer;">
                                <strong>Store Pickup</strong> — Pick up at our studio for free
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Delivery Address -->
                <div class="card mb-4" id="addressCard" style="border: 1px solid var(--border-light); border-radius: var(--radius-md);">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0" style="font-weight: 600;"><i class="fas fa-map-marker-alt" style="margin-right: 0.5rem;"></i> Delivery Address</h5>
                            <a href="profile.php" class="btn btn-sm btn-outline-dark" style="border-radius: 8px; font-size: 0.8rem; font-weight: 600;">
                                <i class="fas fa-edit me-1"></i> Edit Address
                            </a>
                        </div>
                        <?php if (!empty($user_address)): ?>
                            <div style="background: var(--bg-light, #f8f9fa); border-radius: 10px; padding: 1rem; margin-bottom: 1rem; border: 1px solid #e9ecef;">
                                <div style="display: flex; align-items: start; gap: 0.75rem;">
                                    <i class="fas fa-home" style="color: var(--accent-green, #2d6a4f); margin-top: 3px;"></i>
                                    <div>
                                        <strong style="font-size: 0.9rem;">Saved Address</strong>
                                        <p style="margin: 0.25rem 0 0; font-size: 0.88rem; color: #555;"><?php echo htmlspecialchars($user_address); ?></p>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="delivery_address" id="deliveryAddressInput" value="<?php echo htmlspecialchars($user_address); ?>">
                        <?php else: ?>
                            <div style="background: #fff3cd; border-radius: 10px; padding: 1rem; margin-bottom: 1rem;">
                                <p style="margin: 0; font-size: 0.88rem; color: #856404;">
                                    <i class="fas fa-exclamation-triangle me-1"></i> No saved address found. 
                                    <a href="profile.php" style="color: #856404; font-weight: 600;">Add one in your profile</a> or enter below.
                                </p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Full Address *</label>
                                <textarea class="form-control" name="delivery_address" id="deliveryAddressInput" rows="3" placeholder="Enter your complete delivery address" required></textarea>
                            </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label class="form-label">Special Instructions</label>
                            <textarea class="form-control" name="notes" rows="2" placeholder="Any special requests or instructions"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card mb-4" style="border: 1px solid var(--border-light); border-radius: var(--radius-md);">
                    <div class="card-body p-4">
                        <h5 class="mb-3" style="font-weight: 600;"><i class="fas fa-wallet" style="margin-right: 0.5rem;"></i> Payment Method</h5>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="gcash" value="gcash" required>
                            <label class="form-check-label" for="gcash" style="cursor: pointer;">
                                <i class="fas fa-mobile-alt" style="color: var(--primary); margin-right: 0.5rem;"></i>
                                <strong>GCash Pay</strong> - Instant digital payment
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="maya" value="maya" required>
                            <label class="form-check-label" for="maya" style="cursor: pointer;">
                                <i class="fas fa-mobile-alt" style="color: #2ecc71; margin-right: 0.5rem;"></i>
                                <strong>Maya Pay</strong> - Digital wallet payment
                            </label>
                        </div>
                        <div class="form-check mb-3" id="codOption">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" required>
                            <label class="form-check-label" for="cod" style="cursor: pointer;">
                                <i class="fas fa-money-bill-wave" style="color: var(--primary); margin-right: 0.5rem;"></i>
                                <strong>Cash on Delivery</strong> - Pay when order arrives
                            </label>
                        </div>
                        <div class="mt-3" style="background: var(--bg-light); border-radius: var(--radius-sm); padding: 0.75rem 1rem;">
                            <small class="text-muted"><i class="fas fa-info-circle" style="margin-right: 0.25rem;"></i> GCash and Maya provide instant confirmation, while COD allows payment upon delivery.</small>
                        </div>
                    </div>
                </div>

                <!-- Discount Section -->
                <div class="discount-section">
                    <h6><i class="fas fa-ticket-alt"></i> Discount & Benefits</h6>
                    <p class="text-muted mb-3">Your account type: <strong><?php echo ucfirst(str_replace('_', ' ', $user_discount)); ?></strong></p>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="discount_type" id="regular" value="regular" <?php echo $user_discount === 'regular' ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="regular">
                            <strong>No Discount</strong> - Regular pricing
                        </label>
                    </div>

                    <?php if ($user_discount === 'pwd'): ?>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="discount_type" id="pwd" value="pwd" checked>
                        <label class="form-check-label" for="pwd">
                            <i class="fas fa-wheelchair" style="color: var(--primary);"></i>
                            <strong>PWD Discount - 20% OFF</strong>
                        </label>
                    </div>
                    <?php endif; ?>

                    <?php if ($user_discount === 'senior'): ?>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="discount_type" id="senior" value="senior" checked>
                        <label class="form-check-label" for="senior">
                            <i class="fas fa-users" style="color: var(--primary);"></i>
                            <strong>Senior Citizen Discount - 20% OFF</strong>
                        </label>
                    </div>
                    <?php endif; ?>

                    <small class="text-muted">
                        <i class="fas fa-lock"></i> Valid ID will be required for verification during delivery
                    </small>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="col-lg-4">
                <div class="order-summary sticky-top" style="top: 20px;">
                    <h5 class="mb-4" style="font-weight: 700;">Order Summary</h5>
                    
                    <div id="orderItems" style="max-height: 300px; overflow-y: auto; margin-bottom: 1.5rem;">
                        <!-- Items will be loaded here -->
                    </div>

                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span id="summarySubtotal">₱0.00</span>
                    </div>

                    <div class="summary-row">
                        <span>Discount (<span id="discountTypeLabel">Regular</span>):</span>
                        <span id="summaryDiscount" style="color: var(--primary); font-weight: 600;">₱0.00</span>
                    </div>

                    <div class="summary-row">
                        <span>Delivery Fee:</span>
                        <span id="summaryDelivery">₱50.00</span>
                    </div>

                    <div class="summary-row total">
                        <span>Total Amount:</span>
                        <span id="summaryTotal">₱0.00</span>
                    </div>

                    <input type="hidden" name="subtotal" id="hiddenSubtotal">
                    <input type="hidden" name="cart_items" id="cartItemsInput">

                    <button type="submit" class="btn btn-dark btn-lg w-100 mt-4" style="border-radius: var(--radius-sm); padding: 0.75rem;">
                        Place Order <i class="fas fa-arrow-right" style="margin-left: 0.5rem;"></i>
                    </button>

                    <a href="cart.php" class="btn btn-outline-dark w-100 mt-2" style="border-radius: var(--radius-sm); padding: 0.75rem;">
                        <i class="fas fa-arrow-left" style="margin-right: 0.5rem;"></i> Back to Cart
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
const DELIVERY_FEE = 50;
const DISCOUNT_RATES = {
    'regular': 0,
    'pwd': 0.20,
    'senior': 0.20
};

function loadOrderData() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const subtotal = parseFloat(localStorage.getItem('subtotal')) || 0;
    
    // Display cart items
    let itemsHtml = '';
    cart.forEach(item => {
        itemsHtml += `
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(0,0,0,0.1);">
                <div>
                    <strong>${item.name}</strong> <br>
                    <small class="text-muted">Color: ${item.color || 'N/A'} | Size: ${item.size || 'N/A'}</small>
                </div>
                <span>₱${(item.price * item.quantity).toFixed(2)}</span>
            </div>
        `;
    });
    
    document.getElementById('orderItems').innerHTML = itemsHtml;
    document.getElementById('hiddenSubtotal').value = subtotal;
    document.getElementById('cartItemsInput').value = JSON.stringify(cart);
    
    updateOrderSummary();
}

function updateOrderSummary() {
    const subtotal = parseFloat(document.getElementById('hiddenSubtotal').value) || 0;
    const discountType = document.querySelector('input[name="discount_type"]:checked').value;
    const discountRate = DISCOUNT_RATES[discountType];
    const discountAmount = subtotal * discountRate;
    const discountedTotal = subtotal - discountAmount;
    const total = discountedTotal + DELIVERY_FEE;
    
    document.getElementById('summarySubtotal').textContent = '₱' + subtotal.toFixed(2);
    document.getElementById('summaryDiscount').textContent = '₱' + discountAmount.toFixed(2);
    document.getElementById('summaryDelivery').textContent = '₱' + DELIVERY_FEE.toFixed(2);
    document.getElementById('summaryTotal').textContent = '₱' + total.toFixed(2);
    
    // Update discount label
    const labels = {'regular': 'No Discount', 'pwd': 'PWD (20%)', 'senior': 'Senior (20%)'};
    document.getElementById('discountTypeLabel').textContent = labels[discountType];
}

// Listen for discount type changes
document.querySelectorAll('input[name="discount_type"]').forEach(input => {
    input.addEventListener('change', updateOrderSummary);
});

// Load on page load
loadOrderData();

// Toggle delivery address visibility based on delivery method
function toggleDeliveryAddress() {
    const method = document.querySelector('input[name="delivery_method"]:checked').value;
    const addressCard = document.getElementById('addressCard');
    const addressInput = document.getElementById('deliveryAddressInput');
    const deliveryFeeEl = document.getElementById('summaryDelivery');
    const codOption = document.getElementById('codOption');
    const codRadio = document.getElementById('cod');
    const gcashRadio = document.getElementById('gcash');

    if (method === 'pickup') {
        addressCard.style.display = 'none';
        codOption.style.display = 'none';
        if (codRadio.checked) {
            codRadio.checked = false;
            gcashRadio.checked = true;
        }
        if (addressInput) {
            addressInput.removeAttribute('required');
            if (addressInput.tagName === 'INPUT') {
                addressInput.value = 'Store Pickup';
            }
        }
        // Set delivery fee to 0 for pickup
        deliveryFeeEl.textContent = '₱0.00';
        updateOrderSummaryWithPickup();
    } else {
        addressCard.style.display = 'block';
        codOption.style.display = 'block';
        if (addressInput) {
            if (addressInput.tagName === 'TEXTAREA') {
                addressInput.setAttribute('required', 'required');
            }
            <?php if (!empty($user_address)): ?>
            if (addressInput.tagName === 'INPUT') {
                addressInput.value = <?php echo json_encode($user_address); ?>;
            }
            <?php endif; ?>
        }
        deliveryFeeEl.textContent = '₱50.00';
        updateOrderSummary();
    }
}

function updateOrderSummaryWithPickup() {
    const subtotal = parseFloat(document.getElementById('hiddenSubtotal').value) || 0;
    const discountType = document.querySelector('input[name="discount_type"]:checked').value;
    const discountRate = DISCOUNT_RATES[discountType];
    const discountAmount = subtotal * discountRate;
    const total = subtotal - discountAmount; // no delivery fee

    document.getElementById('summarySubtotal').textContent = '₱' + subtotal.toFixed(2);
    document.getElementById('summaryDiscount').textContent = '₱' + discountAmount.toFixed(2);
    document.getElementById('summaryTotal').textContent = '₱' + total.toFixed(2);
    
    const labels = {'regular': 'No Discount', 'pwd': 'PWD (20%)', 'senior': 'Senior (20%)'};
    document.getElementById('discountTypeLabel').textContent = labels[discountType];
}
</script>

<?php include 'includes/footer/footer.php'; ?>
