<?php
    session_start();
    include("connect.php");
    if (!isset($_SESSION['Username'])) {
        header("Location: index.php");
        exit;
    }

if (isset($_GET['delete']) && isset($_GET['table'])) {
    $id = intval($_GET['delete']);
    $table = $_GET['table'];
    $sql = "DELETE FROM `$table` WHERE sn = $id";
    $conn->query($sql);
    header("Location: admin_dashboard.php");
    exit();
}

$messages_sql = "SELECT * FROM foodmessage";
$messages_result = $conn->query($messages_sql);

$reviews_sql = "SELECT * FROM foodreviews";
$reviews_result = $conn->query($reviews_sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warden Dashboard</title>
    <link rel = "stylesheet" href="admin_food_review.css">
</head>
<body>
    <h1>View Students messages and Reviews</h1>
    <nav>
        <a href="admin_dashboard.php">Home</a>
        <a href="admin_food.php">Update mess menu</a>
        <a href="admin_logout.php">Logout</a>
    </nav>
    <div class="container">
        <h2>Food Messages</h2>
        <table>
            <tr>
                <th>Student ID</th>
                <th>Message</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $messages_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['studentID']; ?></td>
                    <td><?php echo $row['message']; ?></td>
                    <td><a class="delete-btn" href="?delete=<?php echo $row['sn']; ?>&table=foodmessage">Delete</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <div class="container">
        <h2>Food Reviews</h2>
        <table>
            <tr>
                <th>Student ID</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $reviews_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['studentID']; ?></td>
                    <td><?php echo $row['Rating']; ?></td>
                    <td><?php echo $row['Review']; ?></td>
                    <td><a class="delete-btn" href="?delete=<?php echo $row['sn']; ?>&table=foodreviews">Delete</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>