<?php
require 'db.php';
require 'header.php'; // Include the header

if ($_SESSION['role'] !== 'applicant') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_id = $_POST['job_id'];
    $applicant_id = $_SESSION['user_id'];
    $message = trim($_POST['message']);
    $resume = $_FILES['resume'];

    // Save resume to server
    $resumePath = 'uploads/' . basename($resume['name']);
    move_uploaded_file($resume['tmp_name'], $resumePath);

    // Insert application into database
    $stmt = $conn->prepare("INSERT INTO applications (job_post_id, applicant_id, resume, message) VALUES (?, ?, ?, ?)");
    $stmt->execute([$job_id, $applicant_id, $resumePath, $message]);

    header("Location: applicant-dashboard.php?success=1");
    exit;
}

$job_id = $_GET['job_id'];
?>

<div class="container mt-5">
    <h2>Apply to Job</h2>
    <form action="apply.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
        <div class="mb-3">
            <label for="message" class="form-label">Why are you a good fit for this job?</label>
            <textarea name="message" id="message" class="form-control" rows="5" required></textarea>
        </div>
        <div class="mb-3">
            <label for="resume" class="form-label">Upload Resume (PDF)</label>
            <input type="file" name="resume" id="resume" class="form-control" accept=".pdf" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit Application</button>
    </form>
</div>

<?php
require 'footer.php'; // Include the footer
?>
