<?php
// chat_history.php - View your chat history with the AI chatbot

require_once 'includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$userName = $_SESSION['fullname'] ?? 'User';

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get chat history for the logged-in user
$query = "SELECT id, user_message, bot_response, created_at FROM chat_history WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$chatMessages = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get total message count
$countQuery = "SELECT COUNT(*) as total FROM chat_history WHERE user_id = ?";
$countStmt = $conn->prepare($countQuery);
$countStmt->bind_param("i", $userId);
$countStmt->execute();
$countResult = $countStmt->get_result();
$countData = $countResult->fetch_assoc();
$totalMessages = $countData['total'];
$countStmt->close();

$conn->close();
?>

<!-- Include Header -->
<?php include 'includes/header/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat History - Thread and Press Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 80px;
        }
        
        .chat-container {
            max-width: 900px;
            margin: 30px auto;
        }
        
        .chat-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 12px 12px 0 0;
            margin-bottom: 0;
        }
        
        .chat-header h2 {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .chat-stats {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 10px;
        }
        
        .chat-content {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 0 0 12px 12px;
            padding: 20px;
            min-height: 400px;
        }
        
        .message-item {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .message-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .user-message {
            background-color: #e7f3ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 12px;
        }
        
        .user-label {
            font-size: 12px;
            color: #667eea;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        
        .bot-message {
            background-color: #f0f0f0;
            border-left: 4px solid #764ba2;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 12px;
        }
        
        .bot-label {
            font-size: 12px;
            color: #764ba2;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        
        .message-timestamp {
            font-size: 12px;
            color: #6c757d;
            margin-top: 10px;
        }
        
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 48px;
            color: #dee2e6;
            margin-bottom: 20px;
        }
        
        .btn-back {
            margin-bottom: 20px;
        }
        
        .message-content {
            word-wrap: break-word;
            line-height: 1.6;
        }
        
        .chat-count {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 13px;
            display: inline-block;
            margin-top: 10px;
        }
        
        .pagination-container {
            margin-top: 25px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="chat-container">
    
    <a href="profile.php" class="btn btn-outline-secondary btn-sm btn-back">
        <i class="fas fa-arrow-left"></i> Back to Profile
    </a>
    
    <div class="chat-header">
        <h2>
            <i class="fas fa-comments"></i>
            Chat History
        </h2>
        <div class="chat-stats">
            <p class="mb-0">Welcome, <strong><?php echo htmlspecialchars($userName); ?></strong></p>
            <div class="chat-count">
                <i class="fas fa-message"></i> Total Conversations: <strong><?php echo $totalMessages; ?></strong>
            </div>
        </div>
    </div>
    
    <div class="chat-content">
        <?php if (empty($chatMessages)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h5>No Chat History Yet</h5>
                <p>You haven't had any conversations with our AI chatbot yet.</p>
                <p>Start chatting with our support bot to get help with your questions!</p>
                <a href="index.php" class="btn btn-primary btn-sm mt-3">
                    <i class="fas fa-comments"></i> Start Chatting
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($chatMessages as $message): ?>
                <div class="message-item">
                    <div class="user-message">
                        <div class="user-label">
                            <i class="fas fa-user-circle"></i> You
                        </div>
                        <div class="message-content">
                            <?php echo htmlspecialchars($message['user_message']); ?>
                        </div>
                        <div class="message-timestamp">
                            <i class="fas fa-clock"></i> <?php echo date('M d, Y - h:i A', strtotime($message['created_at'])); ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($message['bot_response'])): ?>
                        <div class="bot-message">
                            <div class="bot-label">
                                <i class="fas fa-robot"></i> AI Assistant
                            </div>
                            <div class="message-content">
                                <?php echo nl2br(htmlspecialchars($message['bot_response'])); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Include Footer -->
<?php include 'includes/footer/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
