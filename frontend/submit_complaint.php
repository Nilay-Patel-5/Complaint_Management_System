<?php
session_start();
require '../backend/db.php';

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

// Fetch available categories from database
$cat_query = "SELECT * FROM categories";
$categories = mysqli_query($conn, $cat_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $sql = "INSERT INTO complaints (user_id, category_id, title, description) 
            VALUES ('$user_id', '$category_id', '$title', '$description')";
            
    if (mysqli_query($conn, $sql)) {
        $success = "Your complaint has been submitted successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Complaint</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Complaint Management System</h1>
        <div class="header-links">
            <a href="user_dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <main class="main-wrapper">
        <div class="container small">
            <h2 class="text-center">Submit a New Complaint</h2>
            <p class="text-center">Please fill out the details of your issue below.</p>
            
            <?php if($error) echo "<div class='msg error'>$error</div>"; ?>
            <?php if($success) echo "<div class='msg success'>$success</div>"; ?>
            
            <form action="submit_complaint.php" method="POST">
                <label>Issue Category</label>
                <select name="category_id" required>
                    <option value="">-- Select Category --</option>
                    <?php while($cat = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
                    <?php endwhile; ?>
                </select>
                
                <label>Title (Brief Description)</label>
                <input type="text" name="title" required placeholder="Internet is not working">
                
                <label>Detailed Description</label>
                <textarea name="description" rows="5" required placeholder="Provide more details about your issue..."></textarea>
                
                <button type="submit">Submit Complaint</button>
            </form>
        </div>
    </main>
</body>
</html>
