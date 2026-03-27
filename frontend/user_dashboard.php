<?php
session_start();
require '../backend/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the complaints specific to the logged-in user
$sql = "SELECT c.id, c.title, c.status, c.created_at, cat.category_name
        FROM complaints c 
        JOIN categories cat ON c.category_id = cat.id 
        WHERE c.user_id = '$user_id' 
        ORDER BY c.id ASC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Complaint Management System</h1>
        <div class="header-links">
            <a href="index.php">Home</a>
            <a href="submit_complaint.php">Submit Complaint</a>
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <main class="main-wrapper">
        <div class="container wide">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
            
            <div class="mb-4">
                <a href="submit_complaint.php" class="btn">+ Submit a New Complaint</a>
            </div>
            
            <h3>Your Complaints History</h3>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th>Category</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Date Submitted</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td>
                                <?php 
                                    $statusClass = 'status-pending';
                                    if($row['status'] == 'Resolved') $statusClass = 'status-resolved';
                                    if($row['status'] == 'In Progress') $statusClass = 'status-progress';
                                ?>
                                <span class="status-badge <?php echo $statusClass; ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
                            <td><?php echo date('d-M-Y H:i', strtotime($row['created_at'])); ?></td>
                            <td style="text-align: center; white-space: nowrap;">
                                <a href="edit_complaint.php?id=<?php echo $row['id']; ?>" class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; margin-right: 5px;">Edit</a>
                                <a href="delete_complaint.php?id=<?php echo $row['id']; ?>" class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; background-color: var(--danger);" onclick="return confirm('Are you sure you want to delete this complaint?');">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
            <?php else: ?>
                <p>You have not submitted any complaints yet.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
