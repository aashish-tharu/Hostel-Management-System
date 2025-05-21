<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user"; 


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM `message` WHERE sn = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin_security_messages.php?success=Message deleted successfully");
    } else {
        header("Location: admin_security_messages.php?error=Failed to delete message");
    }
    exit();
}


$sql = "SELECT * FROM `message` ORDER BY sn";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Messages</title>
    <link rel = "stylesheet" href = "admin_security_msg.css">
</head>
<body>
    <h2>Message Management</h2>
    <nav>
            <a href="admin_dashboard.php">Home</a>
            <a href="admin_food_msg.php">View messages</a>
            <a href="admin_logout.php">Logout</a>
    </nav>

    <div class="container">
        <?php if (isset($_GET['success'])) echo "<p style='color:green;'>{$_GET['success']}</p>"; ?>
        <?php if (isset($_GET['error'])) echo "<p style='color:red;'>{$_GET['error']}</p>"; ?>

        <table>
            <tr>
                <th>SN</th>
                <th>Name</th>
                <th>ID</th>
                <th>Number</th>
                <th>Message</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['sn']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['number']); ?></td>
                    <td><?php echo htmlspecialchars($row['message']); ?></td>
                    <td><a class="delete-btn" href="?delete=<?php echo $row['sn']; ?>">Delete</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>

</body>
</html>
<?php $conn->close(); ?>
