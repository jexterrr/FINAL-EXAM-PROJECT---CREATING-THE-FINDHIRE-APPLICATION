<?php
require 'db.php';
require 'header.php'; // Include the header

if ($_SESSION['role'] !== 'applicant') {
    header("Location: login.php");
    exit;
}

// Fetch available job posts
$stmt = $conn->prepare("
    SELECT job_posts.*, users.username AS hr_name 
    FROM job_posts 
    JOIN users ON job_posts.hr_id = users.id 
    ORDER BY job_posts.created_at DESC
");
$stmt->execute();
$job_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2>Available Jobs</h2>
    <div class="list-group">
        <?php if (empty($job_posts)): ?>
            <p class="text-muted">No job posts available at the moment.</p>
        <?php else: ?>
            <?php foreach ($job_posts as $job): ?>
                <div class="list-group-item">
                    <h5><?php echo htmlspecialchars($job['title']); ?></h5>
                    <p><?php echo htmlspecialchars($job['description']); ?></p>
                    <small>Posted by: <?php echo htmlspecialchars($job['hr_name']); ?></small>
                    <div class="mt-2">
                        <a href="apply.php?job_id=<?php echo $job['id']; ?>" class="btn btn-sm btn-primary">Apply</a>
                        <a href="send-message.php?receiver_id=<?php echo $job['hr_id']; ?>&job_post_id=<?php echo $job['id']; ?>" class="btn btn-sm btn-secondary">Message HR</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php
require 'footer.php'; // Include the footer
?>
