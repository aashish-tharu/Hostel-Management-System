<?php
session_start();
include("connect.php");
if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['approve']) || isset($_GET['reject'])) {
    $id = intval($_GET['approve'] ?? $_GET['reject']);
    $status = isset($_GET['approve']) ? 'Approved' : 'Rejected';

    $sql = "UPDATE `maintenance_requests` SET status='$status', updated_at=NOW() WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin_maintenance.php?success=Request updated");
    } else {
        header("Location: admin_maintenance.php?error=Failed to update");
    }
    exit();
}

$sql = "SELECT * FROM `maintenance_requests` WHERE status='Pending' ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Maintenance Requests</title>
    <link rel = "stylesheet" href = "admin_maintenance.css">
</head>
<body>
    <header>
        <h1>Warden Dashboard - Update Food Menu</h1>
        <br>
        <nav>
            <a href="admin_dashboard.php">Home</a>
            <a href="admin_food_msg.php">View messages</a>
            <a href="admin_logout.php">Logout</a>
        </nav>
    </header>

    <div class="container">
        <h2>Admin - Maintenance Requests</h2>

        <?php if (isset($_GET['success'])) echo "<p style='color:green;'>{$_GET['success']}</p>"; ?>
        <?php if (isset($_GET['error'])) echo "<p style='color:red;'>{$_GET['error']}</p>"; ?>

        <table>
            <tr>
                <th>ID</th>
                <th>Student ID</th>
                <th>Room No</th>
                <th>Issue Type</th>
                <th>Description</th>
                <th>Urgency</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['room_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['issue_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo htmlspecialchars($row['urgency']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <a class="approve" href="?approve=<?php echo $row['id']; ?>">Approve</a>
                        <a class="reject" href="?reject=<?php echo $row['id']; ?>">Reject</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

</body>
</html>
<?php $conn->close(); ?>
