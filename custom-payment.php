<?php
require 'includes/config.php';
redirectToLogin();

$pageTitle = 'Custom Order Payment';

// Run migration if tables don't exist
$tableCheck = $conn->query("SHOW TABLES LIKE 'custom_orders'");
if ($tableCheck->num_rows === 0) {
    $migrationSQL = file_get_contents(__DIR__ . '/migrate_custom_orders.sql');
    if ($migrationSQL) {
        $conn->multi_query($migrationSQL);
        while ($conn->next_result()) {;}
    }
}

$orderId = intval($_GET['order_id'] ?? 0);
if ($orderId <= 0) {
    header("Location: custom-design.php");
    exit();
}

// Get order details
$stmt = $conn->prepare("SELECT co.*, cd.design_image as orig_design_image FROM custom_orders co LEFT JOIN custom_designs cd ON co.design_id = cd.id WHERE co.id = ? AND co.user_id = ?");
$stmt->bind_param("ii", $orderId, $_SESSION['user_id']);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    header("Location: custom-design.php");
    exit();
}

// Check if already paid
$existingPayment = $conn->prepare("SELECT id FROM custom_order_payments WHERE custom_order_id = ?");
$existingPayment->bind_param("i", $orderId);
$existingPayment->execute();
$hasPaid = $existingPayment->get_result()->num_rows > 0;
$existingPayment->close();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$hasPaid) {
    $paymentMethod = in_array($_POST['payment_method'] ?? '', ['gcash', 'maya', 'cod']) ? $_POST['payment_method'] : '';
    $referenceNumber = trim($_POST['reference_number'] ?? '');

    if (empty($paymentMethod)) {
        $error = 'Please select a payment method.';
    } elseif ($paymentMethod === 'cod') {
        // COD - no proof needed
        $stmt = $conn->prepare("INSERT INTO custom_order_payments (custom_order_id, payment_method, amount, payment_status) VALUES (?, 'cod', ?, 'pending')");
        $stmt->bind_param("id", $orderId, $order['total_price']);
        if ($stmt->execute()) {
            $conn->query("UPDATE custom_orders SET status = 'payment_uploaded' WHERE id = " . intval($orderId));
            header("Location: custom-order-tracking.php?order_id=" . $orderId);
            exit();
        } else {
            $error = 'Failed to process payment. Please try again.';
        }
        $stmt->close();
    } else {
        // GCash/Maya - needs payment proof screenshot
        $paymentProof = '';
        if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['payment_proof'];
            $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];
            $maxSize = 5 * 1024 * 1024; // 5MB

            if (!in_array($file['type'], $allowedTypes)) {
                $error = 'Only PNG, JPG, and WEBP images are allowed.';
            } elseif ($file['size'] > $maxSize) {
                $error = 'File size must be less than 5MB.';
            } else {
                $uploadDir = __DIR__ . '/uploads/payments/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'payment_' . $orderId . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $filepath = $uploadDir . $filename;
                
                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    $paymentProof = 'uploads/payments/' . $filename;
                } else {
                    $error = 'Failed to upload payment proof.';
                }
            }
        }

        if (empty($error) && empty($paymentProof)) {
            $error = 'Please upload your payment screenshot.';
        }

        if (empty($error)) {
            $refNum = $conn->real_escape_string($referenceNumber);
            $stmt = $conn->prepare("INSERT INTO custom_order_payments (custom_order_id, payment_method, payment_proof, reference_number, amount, payment_status) VALUES (?, ?, ?, ?, ?, 'pending')");
            $stmt->bind_param("isssd", $orderId, $paymentMethod, $paymentProof, $refNum, $order['total_price']);
            if ($stmt->execute()) {
                $conn->query("UPDATE custom_orders SET status = 'payment_uploaded' WHERE id = " . intval($orderId));
                header("Location: custom-order-tracking.php?order_id=" . $orderId);
                exit();
            } else {
                $error = 'Failed to process payment. Please try again.';
            }
            $stmt->close();
        }
    }
}

$typeNames = ['tshirt' => 'T-Shirt', 'hoodie' => 'Hoodie', 'polo' => 'Polo'];
$typeName = $typeNames[$order['product_type']] ?? 'T-Shirt';
?>

<?php include 'includes/header/header.php'; ?>

<style>
.payment-container {
    max-width: 700px;
    margin: 0 auto;
    padding: 2rem 1rem;
}
.payment-header {
    text-align: center;
    margin-bottom: 2rem;
}
.payment-header h1 {
    font-size: 2rem;
    font-weight: 800;
}
.payment-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid var(--border-light, #e5e5e5);
    box-shadow: 0 2px 16px rgba(0,0,0,0.06);
    overflow: hidden;
    margin-bottom: 1.5rem;
}
.payment-card-header {
    padding: 1rem 1.5rem;
    background: #fafafa;
    border-bottom: 1px solid #eee;
    font-weight: 700;
}
.payment-card-body {
    padding: 1.5rem;
}
.order-mini-summary {
    display: flex;
    gap: 1rem;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 12px;
    margin-bottom: 1.5rem;
}
.order-mini-img {
    width: 80px;
    height: 80px;
    border-radius: 10px;
    object-fit: cover;
    border: 1px solid #eee;
}
.order-mini-info {
    flex: 1;
}
.order-mini-info h6 {
    font-weight: 700;
    margin-bottom: 0.25rem;
}
.order-mini-info p {
    color: #888;
    font-size: 0.82rem;
    margin: 0;
}
.order-mini-price {
    font-size: 1.3rem;
    font-weight: 800;
    color: var(--accent-green, #2d6a4f);
}
.payment-method-options {
    display: grid;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}
.payment-method-option {
    border: 2px solid #e5e5e5;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 1rem;
}
.payment-method-option:hover {
    border-color: #aaa;
}
.payment-method-option.selected {
    border-color: var(--accent-green, #2d6a4f);
    background: rgba(45,106,79,0.04);
}
.payment-method-option input[type="radio"] {
    display: none;
}
.payment-method-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: #fff;
}
.payment-method-icon.gcash { background: #007bff; }
.payment-method-icon.maya { background: #2ecc71; }
.payment-method-icon.cod { background: #f39c12; }
.payment-method-text h6 {
    font-weight: 700;
    margin: 0;
    font-size: 0.95rem;
}
.payment-method-text p {
    margin: 0;
    color: #888;
    font-size: 0.78rem;
}
.payment-proof-section {
    display: none;
    margin-top: 1rem;
    padding: 1.25rem;
    background: #f8f9fa;
    border-radius: 12px;
    border: 1px solid #eee;
}
.payment-proof-section.show {
    display: block;
}
.upload-proof-area {
    border: 2px dashed #ddd;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    background: #fff;
    margin-bottom: 1rem;
}
.upload-proof-area:hover {
    border-color: var(--accent-green, #2d6a4f);
}
.upload-proof-area i {
    font-size: 2rem;
    color: #bbb;
    margin-bottom: 0.5rem;
}
.upload-proof-area p {
    color: #888;
    font-size: 0.82rem;
    margin: 0;
}
.upload-proof-area img {
    max-width: 200px;
    max-height: 200px;
    border-radius: 8px;
    margin-top: 0.5rem;
}
.payment-instructions {
    background: linear-gradient(135deg, #e8f5e9, #f1f8e9);
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 1rem;
    font-size: 0.85rem;
}
.payment-instructions h6 {
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--accent-green, #2d6a4f);
}
.payment-instructions ol {
    margin: 0;
    padding-left: 1.2rem;
}
.payment-instructions ol li {
    margin-bottom: 0.3rem;
}
.btn-pay {
    display: block;
    width: 100%;
    padding: 1rem;
    background: var(--accent-green, #2d6a4f);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 1.05rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-pay:hover {
    background: #245a42;
    transform: translateY(-1px);
}
.btn-pay:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}
.already-paid-msg {
    text-align: center;
    padding: 2rem;
}
.already-paid-msg i {
    font-size: 3rem;
    color: #27ae60;
    margin-bottom: 1rem;
}
/* Steps reuse */
.order-steps { display:flex; justify-content:center; gap:0; margin-bottom:2rem; }
.order-step { display:flex; align-items:center; gap:0.5rem; font-size:0.82rem; color:#bbb; font-weight:600; }
.order-step.active { color:var(--accent-green,#2d6a4f); }
.order-step.completed { color:#27ae60; }
.order-step .step-num { width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; background:#eee; color:#999; }
.order-step.active .step-num { background:var(--accent-green,#2d6a4f); color:#fff; }
.order-step.completed .step-num { background:#27ae60; color:#fff; }
.step-connector { width:40px; height:2px; background:#eee; margin:0 0.25rem; }
.step-connector.completed { background:#27ae60; }
@media (max-width:768px) { .order-steps { flex-wrap:wrap; gap:0.5rem; } .step-connector { display:none; } }
</style>

<div class="payment-container">
    <!-- Progress Steps -->
    <div class="order-steps">
        <div class="order-step completed"><span class="step-num"><i class="fas fa-check"></i></span> Design</div>
        <div class="step-connector completed"></div>
        <div class="order-step completed"><span class="step-num"><i class="fas fa-check"></i></span> Summary</div>
        <div class="step-connector completed"></div>
        <div class="order-step active"><span class="step-num">3</span> Payment</div>
        <div class="step-connector"></div>
        <div class="order-step"><span class="step-num">4</span> Tracking</div>
    </div>

    <div class="payment-header">
        <h1><i class="fas fa-credit-card me-2"></i>Payment</h1>
        <p class="text-muted">Choose your payment method and complete your order</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($hasPaid): ?>
        <div class="payment-card">
            <div class="payment-card-body already-paid-msg">
                <i class="fas fa-check-circle d-block"></i>
                <h4>Payment Already Submitted</h4>
                <p class="text-muted">Your payment for Order #<?php echo $orderId; ?> has been submitted and is being verified.</p>
                <a href="custom-order-tracking.php?order_id=<?php echo $orderId; ?>" class="btn btn-dark mt-3" style="border-radius:12px; padding:0.75rem 2rem;">
                    <i class="fas fa-truck me-2"></i>Track Your Order
                </a>
            </div>
        </div>
    <?php else: ?>

    <!-- Order Mini Summary -->
    <div class="order-mini-summary">
        <img src="<?php echo htmlspecialchars($order['design_image']); ?>" class="order-mini-img" alt="Design" onerror="this.src='https://placehold.co/80x80/f0f0f0/999?text=Design'">
        <div class="order-mini-info">
            <h6>Custom <?php echo htmlspecialchars($typeName); ?> — Order #<?php echo $orderId; ?></h6>
            <p>Size: <?php echo htmlspecialchars($order['size']); ?> · Qty: <?php echo (int)$order['quantity']; ?></p>
        </div>
        <div class="order-mini-price">₱<?php echo number_format($order['total_price'], 2); ?></div>
    </div>

    <form method="POST" enctype="multipart/form-data" id="paymentForm">
        <div class="payment-card">
            <div class="payment-card-header"><i class="fas fa-wallet me-2"></i>Select Payment Method</div>
            <div class="payment-card-body">
                <div class="payment-method-options">
                    <label class="payment-method-option" onclick="selectPayment('gcash')">
                        <input type="radio" name="payment_method" value="gcash" id="pm_gcash">
                        <div class="payment-method-icon gcash"><i class="fas fa-mobile-alt"></i></div>
                        <div class="payment-method-text">
                            <h6>GCash</h6>
                            <p>Pay via GCash mobile wallet</p>
                        </div>
                    </label>
                    <label class="payment-method-option" onclick="selectPayment('maya')">
                        <input type="radio" name="payment_method" value="maya" id="pm_maya">
                        <div class="payment-method-icon maya"><i class="fas fa-mobile-alt"></i></div>
                        <div class="payment-method-text">
                            <h6>Maya</h6>
                            <p>Pay via Maya digital wallet</p>
                        </div>
                    </label>
                    <label class="payment-method-option" onclick="selectPayment('cod')">
                        <input type="radio" name="payment_method" value="cod" id="pm_cod">
                        <div class="payment-method-icon cod"><i class="fas fa-money-bill-wave"></i></div>
                        <div class="payment-method-text">
                            <h6>Cash on Delivery</h6>
                            <p>Pay when you receive your order</p>
                        </div>
                    </label>
                </div>

                <!-- GCash Instructions -->
                <div class="payment-proof-section" id="gcashSection">
                    <div class="payment-instructions">
                        <h6><i class="fas fa-info-circle me-1"></i>GCash Payment Instructions</h6>
                        <ol>
                            <li>Open your <strong>GCash app</strong></li>
                            <li>Select <strong>Send Money</strong></li>
                            <li>Enter GCash number: <strong>0917-123-4567</strong></li>
                            <li>Enter amount: <strong>₱<?php echo number_format($order['total_price'], 2); ?></strong></li>
                            <li>Complete the transaction and <strong>take a screenshot</strong></li>
                            <li>Upload the screenshot below</li>
                        </ol>
                    </div>
                    <div class="upload-proof-area" onclick="document.getElementById('proofUpload').click()" id="proofArea">
                        <i class="fas fa-cloud-upload-alt d-block"></i>
                        <p><strong>Click to upload payment screenshot</strong></p>
                        <p>PNG, JPG, WEBP (max 5MB)</p>
                    </div>
                    <input type="file" id="proofUpload" name="payment_proof" accept="image/png,image/jpeg,image/webp" style="display:none" onchange="previewProof(this)">
                    <div class="mb-2">
                        <label class="form-label" style="font-size:0.85rem; font-weight:600;">Reference Number (optional)</label>
                        <input type="text" class="form-control" name="reference_number" placeholder="e.g., G123456789AB">
                    </div>
                </div>

                <!-- Maya Instructions -->
                <div class="payment-proof-section" id="mayaSection">
                    <div class="payment-instructions">
                        <h6><i class="fas fa-info-circle me-1"></i>Maya Payment Instructions</h6>
                        <ol>
                            <li>Open your <strong>Maya app</strong></li>
                            <li>Select <strong>Send Money</strong></li>
                            <li>Enter Maya number: <strong>0917-123-4567</strong></li>
                            <li>Enter amount: <strong>₱<?php echo number_format($order['total_price'], 2); ?></strong></li>
                            <li>Complete the transaction and <strong>take a screenshot</strong></li>
                            <li>Upload the screenshot below</li>
                        </ol>
                    </div>
                    <p class="text-muted" style="font-size:0.8rem;"><i class="fas fa-arrow-up me-1"></i>Use the upload area above to submit your Maya payment proof.</p>
                </div>

                <!-- COD Notice -->
                <div class="payment-proof-section" id="codSection">
                    <div class="payment-instructions" style="background:linear-gradient(135deg, #fff8e1, #fff3cd);">
                        <h6 style="color:#f39c12;"><i class="fas fa-truck me-1"></i>Cash on Delivery</h6>
                        <p style="margin:0;">You will pay <strong>₱<?php echo number_format($order['total_price'], 2); ?></strong> when you receive your order. Please prepare the exact amount.</p>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-pay" id="payBtn" disabled>
            <i class="fas fa-lock me-2"></i>Complete Payment — ₱<?php echo number_format($order['total_price'], 2); ?>
        </button>
    </form>

    <a href="custom-order-summary.php?design_id=<?php echo (int)$order['design_id']; ?>&type=<?php echo htmlspecialchars($order['product_type']); ?>&color=<?php echo urlencode($order['apparel_color']); ?>&size=<?php echo htmlspecialchars($order['size']); ?>&qty=<?php echo (int)$order['quantity']; ?>&print_size=medium&discount=<?php echo htmlspecialchars($order['discount_type']); ?>" class="btn-back" style="display:block;width:100%;padding:0.75rem;background:#fff;color:#666;border:1px solid #ddd;border-radius:12px;font-size:0.95rem;font-weight:600;cursor:pointer;text-align:center;text-decoration:none;margin-top:0.75rem;">
        <i class="fas fa-arrow-left me-2"></i>Back to Order Summary
    </a>

    <?php endif; ?>
</div>

<script>
function selectPayment(method) {
    // Update radio buttons
    document.querySelectorAll('.payment-method-option').forEach(opt => opt.classList.remove('selected'));
    document.getElementById('pm_' + method).checked = true;
    document.getElementById('pm_' + method).closest('.payment-method-option').classList.add('selected');

    // Show/hide sections
    document.getElementById('gcashSection').classList.toggle('show', method === 'gcash');
    document.getElementById('mayaSection').classList.toggle('show', method === 'maya');
    document.getElementById('codSection').classList.toggle('show', method === 'cod');

    // Enable pay button
    document.getElementById('payBtn').disabled = false;

    // For maya, share the same upload area
    if (method === 'maya') {
        document.getElementById('gcashSection').classList.add('show');
    }
}

function previewProof(input) {
    const file = input.files[0];
    if (!file) return;
    const area = document.getElementById('proofArea');
    const reader = new FileReader();
    reader.onload = function(e) {
        area.innerHTML = `<img src="${e.target.result}" alt="Payment Proof"><p style="margin-top:0.5rem; color:#27ae60; font-weight:600;"><i class="fas fa-check-circle me-1"></i>Screenshot uploaded</p>`;
    };
    reader.readAsDataURL(file);
}
</script>

<?php include 'includes/footer/footer.php'; ?>
