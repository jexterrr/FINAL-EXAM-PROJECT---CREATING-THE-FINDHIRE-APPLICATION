<?php
require 'db.php';
require 'header.php'; // Include the header

if ($_SESSION['role'] !== 'applicant') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applicant_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];
    $job_post_id = $_POST['job_post_id']; // Include the job_post_id
    $message = trim($_POST['message']);

    try {
        // Insert the message with job_post_id
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, job_post_id, content) VALUES (?, ?, ?, ?)");
        $stmt->execute([$applicant_id, $receiver_id, $job_post_id, $message]);

        // Set success message
        $_SESSION['message'] = "Your message has been sent to HR.";
        $_SESSION['message_type'] = "success";
    } catch (PDOException $e) {
        // Set error message
        $_SESSION['message'] = "Failed to send the message. Error: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }

    header("Location: send-message.php?receiver_id=$receiver_id&job_post_id=$job_post_id");
    exit;
}

$receiver_id = $_GET['receiver_id']; // From the applicant-dashboard.php link
$job_post_id = $_GET['job_post_id']; // From the applicant-dashboard.php link

// Fetch messages and replies for this job post
$stmt = $conn->prepare("
    SELECT messages.id AS message_id, messages.content AS message_content, messages.sent_at AS message_sent_at,
           replies.content AS reply_content, replies.sent_at AS reply_sent_at
    FROM messages
    LEFT JOIN replies ON messages.id = replies.message_id
    WHERE messages.job_post_id = ? AND messages.sender_id = ?
    ORDER BY messages.sent_at ASC
");
$stmt->execute([$job_post_id, $_SESSION['user_id']]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2>Message HR</h2>

    <!-- Display Message History -->
    <div class="mb-4">
        <h4>Message History</h4>
        <div class="list-group">
            <?php if (empty($messages)): ?>
                <p class="text-muted">No messages yet.</p>
            <?php else: ?>
                <?php foreach ($messages as $message): ?>
                    <div class="list-group-item">
                        <h5>Your Message</h5>
                        <p><?php echo htmlspecialchars($message['message_content']); ?></p>
                        <small>Sent at: <?php echo htmlspecialchars($message['message_sent_at']); ?></small>
                        
                        <?php if (!empty($message['reply_content'])): ?>
                            <div class="mt-3">
                                <h6>HR Reply</h6>
                                <p><?php echo htmlspecialchars($message['reply_content']); ?></p>
                                <small>Replied at: <?php echo htmlspecialchars($message['reply_sent_at']); ?></small>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mt-2">No reply yet.</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- New Message Form -->
    <form action="send-message.php" method="POST">
        <input type="hidden" name="receiver_id" value="<?php echo htmlspecialchars($receiver_id); ?>">
        <input type="hidden" name="job_post_id" value="<?php echo htmlspecialchars($job_post_id); ?>">
        <div class="mb-3">
            <label for="message" class="form-label">Your Message</label>
            <textarea name="message" id="message" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send Message</button>
    </form>
</div>

<?php
require 'footer.php'; // Include the footer
?>
