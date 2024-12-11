<?php
session_start();
if ($_SESSION['role'] !== 'hr') {
    header("Location: login.php");
    exit;
}

require 'db.php';

if (isset($_GET['application_id']) && isset($_GET['action'])) {
    $application_id = $_GET['application_id'];
    $action = $_GET['action'];

    // Update the application status
    $status = $action === 'accept' ? 'accepted' : 'rejected';
    $stmt = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $stmt->execute([$status, $application_id]);

    // Set success message
    $_SESSION['message'] = "Application has been " . ($action === 'accept' ? "accepted" : "rejected") . ".";
    $_SESSION['message_type'] = "success";

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
?>
