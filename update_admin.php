<?php
require 'includes/config.php';

// Update admin password
$newPassword = '$2y$10$2BnxOSPEW.NR9gw2OvMZtOZW6gEyJZfUhMJ3CJ40vOCc/1ZxcNV9y';
$email = 'myadmin@threadpresshub.com';

$stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
$stmt->bind_param("ss", $newPassword, $email);

if ($stmt->execute()) {
    echo "<h2>✓ Admin password updated successfully!</h2>";
    echo "<p>You can now login with:</p>";
    echo "<ul>";
    echo "<li><strong>Email:</strong> myadmin@threadpresshub.com</li>";
    echo "<li><strong>Password:</strong> groupfour123</li>";
    echo "</ul>";
    echo "<p><a href='login.php'>Go to login page</a></p>";
} else {
    echo "<h2>✗ Error updating password</h2>";
    echo "<p>Error: " . $stmt->error . "</p>";
}

$stmt->close();
$conn->close();
?>
