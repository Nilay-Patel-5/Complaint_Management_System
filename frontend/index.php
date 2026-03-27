<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Complaint Management System</h1>
        <div class="header-links">
            <a href="index.php">Home</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="user_dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">User Login</a>
                <a href="register.php">Register</a>
                <a href="admin_login.php">Admin Login</a>
            <?php endif; ?>
        </div>
    </header>

    <main class="main-wrapper">
        <div class="container text-center">
            <h2>Welcome to <br><span style="color: var(--primary);">Complaint Management System</span></h2>
            
            <?php if(!isset($_SESSION['user_id'])): ?>
                <div class="mt-4">
                    <p style="margin-bottom: 1.5rem;">Join us today to submit a new complaint or track an existing one.</p>
                    <a href="login.php" class="btn">Login</a>
                    <a href="register.php" class="btn btn-success" style="margin-left: 10px;">Register Now</a>
                </div>
            <?php else: ?>
                <div class="mt-4">
                    <a href="user_dashboard.php" class="btn">Go to Dashboard</a>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
