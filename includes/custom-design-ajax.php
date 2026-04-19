<?php
require __DIR__ . '/config.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Please log in to use the design tool.']);
    exit();
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$userId = (int) $_SESSION['user_id'];

switch ($action) {
    case 'save':
        handleSave($conn, $userId);
        break;
    case 'list':
        handleList($conn, $userId);
        break;
    case 'get':
        handleGet($conn, $userId);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
}

function handleSave($conn, $userId) {
    $productType = $conn->real_escape_string(trim($_POST['product_type'] ?? 'tshirt'));
    $notes = $conn->real_escape_string(trim($_POST['notes'] ?? ''));
    $designImage = $_POST['design_image'] ?? '';
    $designImageBack = $_POST['design_image_back'] ?? '';
    $designData = $_POST['design_data'] ?? '{}';

    if (empty($designImage)) {
        echo json_encode(['success' => false, 'message' => 'No design image provided.']);
        return;
    }

    // Validate product type
    $allowedTypes = ['tshirt', 'hoodie', 'polo'];
    if (!in_array($productType, $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'Invalid apparel type.']);
        return;
    }

    // Validate the design image is a valid base64 data URI
    if (strpos($designImage, 'data:image/') !== 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid image format.']);
        return;
    }

    $uploadDir = __DIR__ . '/../uploads/designs/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Save front image
    $dbImagePath = saveDesignImage($designImage, $uploadDir, $userId, 'front');
    if (!$dbImagePath) {
        echo json_encode(['success' => false, 'message' => 'Failed to save front design image.']);
        return;
    }

    // Save back image (if provided)
    $dbImageBackPath = '';
    if (!empty($designImageBack) && strpos($designImageBack, 'data:image/') === 0) {
        $dbImageBackPath = saveDesignImage($designImageBack, $uploadDir, $userId, 'back');
        if (!$dbImageBackPath) {
            $dbImageBackPath = '';
        }
    }

    // Add design_image_back column if it doesn't exist
    $conn->query("ALTER TABLE custom_designs ADD COLUMN IF NOT EXISTS design_image_back LONGTEXT DEFAULT NULL AFTER design_image");

    $stmt = $conn->prepare("INSERT INTO custom_designs (user_id, product_type, design_image, design_image_back, design_data, notes, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("isssss", $userId, $productType, $dbImagePath, $dbImageBackPath, $designData, $notes);

    if ($stmt->execute()) {
        $designId = $conn->insert_id;
        echo json_encode([
            'success' => true,
            'message' => 'Design submitted successfully!',
            'design_id' => $designId
        ]);
    } else {
        // Clean up uploaded files on DB failure
        @unlink($uploadDir . basename($dbImagePath));
        if ($dbImageBackPath) @unlink($uploadDir . basename($dbImageBackPath));
        echo json_encode(['success' => false, 'message' => 'Failed to save design. Please try again.']);
    }
    $stmt->close();
}

function saveDesignImage($imageDataUri, $uploadDir, $userId, $side) {
    $imageData = explode(',', $imageDataUri, 2);
    if (count($imageData) !== 2) return false;

    $decoded = base64_decode($imageData[1], true);
    if ($decoded === false) return false;

    $ext = 'png';
    if (strpos($imageData[0], 'jpeg') !== false) $ext = 'jpg';
    elseif (strpos($imageData[0], 'webp') !== false) $ext = 'webp';

    $filename = 'design_' . $userId . '_' . $side . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $filePath = $uploadDir . $filename;

    if (!file_put_contents($filePath, $decoded)) return false;

    return 'uploads/designs/' . $filename;
}

function handleList($conn, $userId) {
    $stmt = $conn->prepare("SELECT id, product_type, design_image, notes, status, created_at FROM custom_designs WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $designs = [];
    while ($row = $result->fetch_assoc()) {
        $designs[] = $row;
    }
    $stmt->close();

    echo json_encode(['success' => true, 'designs' => $designs]);
}

function handleGet($conn, $userId) {
    $designId = (int) ($_GET['id'] ?? 0);

    $stmt = $conn->prepare("SELECT * FROM custom_designs WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $designId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $design = $result->fetch_assoc();
    $stmt->close();

    if ($design) {
        echo json_encode(['success' => true, 'design' => $design]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Design not found.']);
    }
}
