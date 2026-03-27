<?php
session_start();
require '../backend/db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE student_id='$student_id'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            // Setup session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: user_dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found with that Student ID.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Complaint Management</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Complaint Management System</h1>
        <div class="header-links">
            <a href="index.php">Home</a>
            <a href="register.php">Register</a>
        </div>
    </header>

    <main class="main-wrapper">
        <div class="container small">
            <h2 class="text-center">User Login</h2>
            
            <?php if($error) echo "<div class='msg error'>$error</div>"; ?>
            
            <form action="login.php" method="POST">
                <label>Student ID</label>
                <input type="text" name="student_id" required placeholder="24CE089">
                
                <label>Password</label>
                <input type="password" name="password" required placeholder="xxxxxx">
                
                <button type="submit">Login</button>
            </form>
            <p class="text-center mt-4">Don't have an account? <a href="register.php" style="color: var(--primary); font-weight: 500;">Register here</a>.</p>
        </div>
    </main>
</body>
</html>
