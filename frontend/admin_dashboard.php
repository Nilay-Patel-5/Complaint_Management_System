<?php
session_start();
require '../backend/db.php';

// Verify admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$msg = '';

// Process status update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $complaint_id = mysqli_real_escape_string($conn, $_POST['complaint_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $update_sql = "UPDATE complaints SET status='$new_status' WHERE id='$complaint_id'";
    if (mysqli_query($conn, $update_sql)) {
        $msg = "Complaint #$complaint_id status updated successfully to $new_status.";
    } else {
        $msg = "Error updating status: " . mysqli_error($conn);
    }
}

// Handle status filter
$status_filter = isset($_GET['filter_status']) ? mysqli_real_escape_string($conn, $_GET['filter_status']) : 'All';
$where_clause = "";
if ($status_filter != 'All') {
    $where_clause = " WHERE c.status = '$status_filter' ";
}

// Fetch all complaints with user and category details
$sql = "SELECT c.id, c.title, c.description, c.status, c.created_at, cat.category_name, u.name as user_name, u.student_id, u.room_no, u.phone_no 
        FROM complaints c 
        JOIN categories cat ON c.category_id = cat.id 
        JOIN users u ON c.user_id = u.id
        $where_clause
        ORDER BY c.id ASC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .update-form {
            display: flex; 
            flex-direction: row; 
            margin: 0; 
            max-width: none;
            align-items: center;
        }
        .update-form select {
            margin: 0; 
            padding: 5px;
            font-size: 14px;
        }
        .update-form button {
            margin: 0 0 0 5px; 
            padding: 5px 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Control Panel</h1>
        <div class="header-links">
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_logout.php">Logout</a>
        </div>
    </header>

    <main class="main-wrapper">
        <div class="container wide">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                <h2 style="margin: 0;">Complaints Overview</h2>
                
                <form action="admin_dashboard.php" method="GET" style="display: flex; align-items: center; gap: 10px; margin: 0;">
                    <label style="margin: 0; font-weight: 500;">Filter by Status: </label>
                    <select name="filter_status" onchange="this.form.submit()" style="margin: 0; width: auto; padding: 5px 10px;">
                        <option value="All" <?php if($status_filter == 'All') echo 'selected'; ?>>All</option>
                        <option value="Pending" <?php if($status_filter == 'Pending') echo 'selected'; ?>>Pending</option>
                        <option value="In Progress" <?php if($status_filter == 'In Progress') echo 'selected'; ?>>In Progress</option>
                        <option value="Resolved" <?php if($status_filter == 'Resolved') echo 'selected'; ?>>Resolved</option>
                    </select>
                </form>
            </div>
            
            <?php if($msg): ?>
                <div class="msg success"><?php echo $msg; ?></div>
            <?php endif; ?>

            <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>User Details</th>
                            <th>Category</th>
                            <th>Complaint Specifics</th>
                            <th>Status</th>
                            <th>Submit Date</th>
                            <th>Action</th>
                        </tr>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['user_name']); ?></strong><br>
                                <span style="color: var(--text-muted); font-size: 0.85rem;">ID: <?php echo htmlspecialchars($row['student_id']); ?> | Room: <?php echo htmlspecialchars($row['room_no']); ?> | Phone: <?php echo htmlspecialchars($row['phone_no']); ?></span>
                            </td>
                            <td><span style="font-weight: 500;"><?php echo htmlspecialchars($row['category_name']); ?></span></td>
                            <td>
                                <strong style="color: var(--text-main);"><?php echo htmlspecialchars($row['title']); ?></strong>
                                <p style="margin: 0.3rem 0 0 0; font-size: 0.9rem; color: var(--text-muted);"><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                            </td>
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
                            <td>
                                <form action="admin_dashboard.php" method="POST" class="update-form">
                                    <input type="hidden" name="complaint_id" value="<?php echo $row['id']; ?>">
                                    <select name="status">
                                        <option value="Pending" <?php if($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                        <option value="In Progress" <?php if($row['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                                        <option value="Resolved" <?php if($row['status'] == 'Resolved') echo 'selected'; ?>>Resolved</option>
                                    </select>
                                    <button type="submit" name="update_status">Update</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
            <?php else: ?>
                <p>No complaints found in the database.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
