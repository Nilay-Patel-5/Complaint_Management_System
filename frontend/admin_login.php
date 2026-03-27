<?php
session_start();

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hardcoded credentials for simplicity in this project
    $admin_user = 'admin';
    $admin_pass = 'admin123';

    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid admin username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Complaint Management System</h1>
        <div class="header-links">
            <a href="index.php">Home</a>
        </div>
    </header>

    <main class="main-wrapper">
        <div class="container small">
            <h2 class="text-center">Admin Login</h2>
            
            <?php if($error) echo "<div class='msg error'>$error</div>"; ?>
            
            <form action="admin_login.php" method="POST">
                <label>Admin Username</label>
                <input type="text" name="username" required placeholder="Enter your User ID">
                
                <label>Password</label>
                <input type="password" name="password" required placeholder="xxxxxx">
                
                <button type="submit">Login to Admin Panel</button>
            </form>
        </div>
    </main>
</body>
</html>
