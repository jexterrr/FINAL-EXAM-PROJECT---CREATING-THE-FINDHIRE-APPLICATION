<?php
require 'header.php'; // Include the reusable header
?>

<div class="container mt-5">
    <div class="row text-center">
        <h1>Welcome to FindHire!</h1>
        <p class="lead">Your gateway to job opportunities and streamlined recruitment.</p>
    </div>
    <div class="row mt-4">
        <?php if (!$isLoggedIn): ?>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">New User?</h5>
                        <p class="card-text">Create an account to get started!</p>
                        <a href="register.php" class="btn btn-primary">Register</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Already a Member?</h5>
                        <p class="card-text">Login to access your account.</p>
                        <a href="login.php" class="btn btn-success">Login</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Welcome Back!</h5>
                        <p class="card-text">Continue where you left off:</p>
                        <a href="<?php echo $userRole == 'hr' ? 'hr-dashboard.php' : 'applicant-dashboard.php'; ?>" class="btn btn-primary">
                            Go to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
require 'footer.php'; // Include the reusable footer
?>
