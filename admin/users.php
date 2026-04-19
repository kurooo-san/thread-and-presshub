<?php
require '../includes/config.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = 'Manage Users';

// Get all users
$users = $conn->query("SELECT * FROM users WHERE user_type != 'admin' ORDER BY created_at DESC");
?>

<?php include '../includes/header/header.php'; ?>
<?php include '../includes/admin-sidebar.php'; ?>

<div class="admin-container">
    <div class="mb-4">
        <h1 class="text-coffee-dark mb-2" style="font-size: 2rem; font-weight: 800;">
            <i class="fas fa-users"></i> Manage Users
        </h1>
    </div>

    <div class="admin-card">
        <h5 class="text-coffee-dark mb-4" style="font-weight: 700;">
            <i class="fas fa-list"></i> Customer Accounts
        </h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Account Type</th>
                        <th>ID Number</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone'] ?? '-'); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $user['user_type'] === 'pwd' ? 'info' : ($user['user_type'] === 'senior' ? 'warning' : 'secondary'); ?>">
                                <?php echo ucfirst(str_replace('_', ' ', $user['user_type'])); ?>
                            </span>
                        </td>
                        <td>
                            <?php 
                                if ($user['user_type'] === 'pwd' && $user['pwd_id']) {
                                    echo htmlspecialchars($user['pwd_id']);
                                } elseif ($user['user_type'] === 'senior' && $user['senior_id']) {
                                    echo htmlspecialchars($user['senior_id']);
                                } else {
                                    echo '-';
                                }
                            ?>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
    </div><!-- /admin-main-content -->
</div><!-- /admin-layout -->
<script src="../js/admin-sidebar.js"></script>

<?php include '../includes/footer/footer.php'; ?>
