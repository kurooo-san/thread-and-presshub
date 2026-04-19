<?php
require 'includes/config.php';
redirectToLogin();

$pageTitle = 'Maya Payment';

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id === 0) {
    header("Location: orders.php");
    exit();
}

// Get order details
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows === 0) {
    header("Location: orders.php");
    exit();
}

$order = $order_result->fetch_assoc();
$stmt->close();

// Handle payment confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reference = sanitizeInput($_POST['reference_number'] ?? '');
    
    if (!verifyCsrfToken()) {
        $error = 'Invalid form submission. Please try again.';
    } elseif (empty($reference)) {
        $error = 'Please enter Maya reference number!';
    } else {
        // Record transaction as pending
        $trans_stmt = $conn->prepare("INSERT INTO gcash_transactions (order_id, reference_number, amount, status) VALUES (?, ?, ?, 'pending')");
        $trans_stmt->bind_param("isd", $order_id, $reference, $order['total']);
        
        if ($trans_stmt->execute()) {
            // Update order status to confirmed
            $update_stmt = $conn->prepare("UPDATE orders SET status = 'confirmed', payment_reference = ? WHERE id = ?");
            $update_stmt->bind_param("si", $reference, $order_id);
            $update_stmt->execute();
            $update_stmt->close();
            
            // Clear cart
            $_SESSION['cart'] = array();
            
            header("Location: order_confirmation.php?order_id=" . $order_id);
            exit();
        } else {
            $error = 'Failed to process payment. Please try again.';
        }
        $trans_stmt->close();
    }
}
?>

<?php include 'includes/header/header.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="orders.php">Orders</a></li>
                    <li class="breadcrumb-item active">Maya Payment</li>
                </ol>
            </nav>
            <div class="card" style="border: 1px solid var(--border-light); border-radius: var(--radius-md);">
                <div class="card-body p-4">
                    <h5 class="mb-4 text-center" style="font-weight: 700;"><i class="fas fa-mobile-alt" style="margin-right: 0.5rem; color: #2ecc71;"></i> Maya Payment</h5>
                    <div class="mb-4" style="background: #e8f8f0; border-radius: var(--radius-sm); padding: 1rem;">
                        <h6 class="mb-2"><i class="fas fa-info-circle" style="margin-right: 0.25rem;"></i> Payment Instructions</h6>
                        <p class="mb-0">
                            <strong>Maya Account:</strong> <span style="font-weight: 700;">0917-123-4567</span><br>
                            <strong>Amount:</strong> <span style="font-weight: 700;">₱<?php echo number_format($order['total'], 2); ?></span>
                        </p>
                    </div>

                    <ol class="mb-4">
                        <li class="mb-2">Open your Maya app</li>
                        <li class="mb-2">Select <strong>Send Money</strong></li>
                        <li class="mb-2">Enter the Maya number: <strong>0917-123-4567</strong></li>
                        <li class="mb-2">Enter amount: <strong>₱<?php echo number_format($order['total'], 2); ?></strong></li>
                        <li class="mb-2">Complete the transaction</li>
                        <li>Enter the reference number below</li>
                    </ol>

                    <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <?php echo csrfTokenField(); ?>
                        <div class="form-group mb-3">
                            <label class="form-label">Order ID</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($order['id']); ?>" disabled>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Total Amount</label>
                            <input type="text" class="form-control" value="₱<?php echo number_format($order['total'], 2); ?>" disabled>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Maya Reference Number *</label>
                            <input type="text" class="form-control" name="reference_number" placeholder="e.g., M123456789AB" required>
                            <small class="text-muted">You can find this in your Maya app under Transaction History</small>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 mb-2" style="border-radius: var(--radius-sm); padding: 0.75rem;">
                            Confirm Payment <i class="fas fa-arrow-right" style="margin-left: 0.5rem;"></i>
                        </button>
                    </form>

                    <a href="orders.php" class="btn btn-outline-dark w-100" style="border-radius: var(--radius-sm); padding: 0.75rem;">
                        <i class="fas fa-arrow-left" style="margin-right: 0.5rem;"></i> Back to Orders
                    </a>

                    <div class="alert alert-warning mt-4 mb-0">
                        <small>
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Note:</strong> Please ensure the payment is successful before entering the reference number. Our team will verify the payment within 10 minutes.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer/footer.php'; ?>
