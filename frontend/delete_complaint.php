<?php
session_start();
require '../backend/db.php';

// Secure backend deletion script
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $complaint_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];
    
    // Ensure the user actually owns this complaint before executing the delete
    $check_sql = "SELECT id FROM complaints WHERE id='$complaint_id' AND user_id='$user_id'";
    $result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($result) > 0) {
        $delete_sql = "DELETE FROM complaints WHERE id='$complaint_id'";
        mysqli_query($conn, $delete_sql);
    }
}

// Redirect back to dashboard seamlessly
header("Location: user_dashboard.php");
exit();
?>
