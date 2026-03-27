<?php
session_start();
require '../backend/db.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $room_no = mysqli_real_escape_string($conn, $_POST['room_no']);
    $phone_no = mysqli_real_escape_string($conn, $_POST['phone_no']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if phone number or student ID already exists
    $check_query = "SELECT id FROM users WHERE phone_no='$phone_no' OR student_id='$student_id'";
    $result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($result) > 0) {
        $error = "Phone number or Student ID already registered!";
    } else {
        // Hash the password for basic security best practice
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (name, student_id, room_no, phone_no, password) VALUES ('$name', '$student_id', '$room_no', '$phone_no', '$hashed_password')";
        if (mysqli_query($conn, $sql)) {
            $success = "Registration successful! You can now login.";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Complaint Management</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Complaint Management System</h1>
        <div class="header-links">
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
        </div>
    </header>

    <main class="main-wrapper">
        <div class="container small">
            <h2 class="text-center">User Registration</h2>
            
            <?php if($error) echo "<div class='msg error'>$error</div>"; ?>
            <?php if($success) echo "<div class='msg success'>$success</div>"; ?>
            
            <form action="register.php" method="POST">
                <label>Full Name</label>
                <input type="text" name="name" required placeholder="Nilay Rajeshbhai Patel">
                
                <label>Student ID</label>
                <input type="text" name="student_id" required placeholder="24CE089">

                <label>Room No.</label>
                <input type="text" name="room_no" required placeholder="203">

                <label>Phone No.</label>
                <input type="tel" name="phone_no" required placeholder="9876543210" pattern="[0-9]{10}">
                
                <label>Password</label>
                <input type="password" name="password" required placeholder="xxxxxx">
                
                <button type="submit">Create Account</button>
            </form>
            <p class="text-center mt-4">Already have an account? <a href="login.php" style="color: var(--primary); font-weight: 500;">Login here</a>.</p>
        </div>
    </main>
</body>
</html>
