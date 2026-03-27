<?php
session_start();
require '../backend/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

if (!isset($_GET['id']) && $_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: user_dashboard.php");
    exit();
}

$complaint_id = isset($_GET['id']) ? intval($_GET['id']) : intval($_POST['id']);

// Fetch complaint to verify ownership and pre-fill data
$sql = "SELECT * FROM complaints WHERE id='$complaint_id' AND user_id='$user_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    header("Location: user_dashboard.php");
    exit();
}

$complaint = mysqli_fetch_assoc($result);

// Fetch categories
$cat_query = "SELECT * FROM categories";
$categories = mysqli_query($conn, $cat_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    $update_sql = "UPDATE complaints SET category_id='$category_id', title='$title', description='$description' WHERE id='$complaint_id' AND user_id='$user_id'";
    
    if (mysqli_query($conn, $update_sql)) {
        $success = "Complaint updated successfully!";
        $complaint['category_id'] = $category_id;
        $complaint['title'] = $title;
        $complaint['description'] = $description;
    } else {
        $error = "Error updating complaint: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Complaint</title>
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
            <h2 class="text-center">Edit Complaint</h2>
            <p class="text-center">Update the details of your issue below.</p>
            
            <?php if($error) echo "<div class='msg error'>$error</div>"; ?>
            <?php if($success) echo "<div class='msg success'>$success</div>"; ?>
            
            <form action="edit_complaint.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $complaint_id; ?>">
                
                <label>Issue Category</label>
                <select name="category_id" required>
                    <option value="">-- Select Category --</option>
                    <?php while($cat = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $complaint['category_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['category_name']); ?></option>
                    <?php endwhile; ?>
                </select>
                
                <label>Title (Brief Description)</label>
                <input type="text" name="title" required value="<?php echo htmlspecialchars($complaint['title']); ?>">
                
                <label>Detailed Description</label>
                <textarea name="description" rows="5" required><?php echo htmlspecialchars($complaint['description']); ?></textarea>
                
                <button type="submit">Update Complaint</button>
            </form>
        </div>
    </main>
</body>
</html>
