<?php
require 'db.php';
require 'header.php'; // Include the header

if ($_SESSION['role'] !== 'hr') {
    header("Location: login.php");
    exit;
}

$hr_id = $_SESSION['user_id'];

// Fetch job posts with application counts and applicants
$stmt = $conn->prepare("
    SELECT 
        job_posts.*,
        COUNT(CASE WHEN applications.status = 'accepted' THEN 1 END) AS accepted_count,
        COUNT(CASE WHEN applications.status = 'rejected' THEN 1 END) AS rejected_count,
        COALESCE(GROUP_CONCAT(CONCAT(users.username, '|', applications.status)), '') AS applicants
    FROM job_posts
    LEFT JOIN applications ON job_posts.id = applications.job_post_id
    LEFT JOIN users ON applications.applicant_id = users.id
    WHERE job_posts.hr_id = ?
    GROUP BY job_posts.id
");
$stmt->execute([$hr_id]);
$job_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2>HR Dashboard</h2>
    <a href="create-job.php" class="btn btn-success mb-3">Create Job Post</a>
    <div class="list-group">
        <?php if (empty($job_posts)): ?>
            <p class="text-muted">You have not created any job posts yet.</p>
        <?php else: ?>
            <?php foreach ($job_posts as $job): ?>
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <?php echo htmlspecialchars($job['title']); ?>
                        </h5>
                        <div>
                            <span class="badge bg-success">Accepted: <?php echo $job['accepted_count']; ?></span>
                            <span class="badge bg-danger">Rejected: <?php echo $job['rejected_count']; ?></span>
                        </div>
                    </div>

                    <p class="mb-1 text-muted"><?php echo htmlspecialchars($job['description']); ?></p>
                    <small>Posted on: <?php echo htmlspecialchars($job['created_at']); ?></small>

                    <div class="mt-3">
                        <strong>Applying Applicants:</strong>
                        <ul class="list-unstyled">
                            <?php if (!empty($job['applicants'])): ?>
                                <?php 
                                // Convert the concatenated string into an array of applicants
                                $applicantList = explode(',', $job['applicants']);
                                foreach ($applicantList as $applicantData): 
                                    list($username, $status) = explode('|', $applicantData);
                                ?>
                                    <li>
                                        <?php echo htmlspecialchars($username); ?> / 
                                        <span class="fw-bold text-<?php echo $status === 'accepted' ? 'success' : ($status === 'rejected' ? 'danger' : 'primary'); ?>">
                                            <?php echo ucfirst($status); ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="text-muted">No applicants yet.</li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="mt-2">
                        <a href="view-applications.php?job_id=<?php echo $job['id']; ?>" class="btn btn-sm btn-primary">
                            View Applications
                        </a>
                        <a href="view-messages.php?job_id=<?php echo $job['id']; ?>" class="btn btn-sm btn-secondary">
                            View Messages
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php
require 'footer.php'; // Include the footer
?>
