<?php
// Simple Database Connection File
$host = "127.0.0.1"; // Changed from localhost to 127.0.0.1 for Windows compatibility
$user = "root"; // Default XAMPP/WAMP username, change if needed
$pass = ""; // Default XAMPP/WAMP password, change if needed
$dbname = "complaint_system";
$port = 3307; // Custom port based on your XAMPP settings

try {
    // Added port parameter so PHP knows exactly where to find MySQL
    $conn = mysqli_connect($host, $user, $pass, $dbname, $port);
} catch (mysqli_sql_exception $e) {
    die("<div style='font-family: Arial; padding: 20px; border: 1px solid #f5c6cb; background-color: #f8d7da; color: #721c24; border-radius: 5px; max-width: 600px; margin: 20px auto; text-align: center;'>
            <h3>Database Connection Failed!</h3>
            <p>Please make sure you have started <b>MySQL</b> in your XAMPP / WAMP Control Panel.</p>
            <p style='font-size: 14px; color: #555;'><i>Technical Error: " . htmlspecialchars($e->getMessage()) . "</i></p>
         </div>");
}

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
