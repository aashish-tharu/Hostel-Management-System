<?php
session_start();
include("connect.php");

$sql = "SELECT * FROM food ORDER BY sn";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['update'])) {
    $sn = $_POST['sn'];
    $breakfast = $_POST['breakfast'];
    $lunch = $_POST['lunch'];
    $evening = $_POST['evening'];
    $dinner = $_POST['dinner'];

    $stmt = $conn->prepare("UPDATE food SET breakfast=?, lunch=?, evening=?, dinner=? WHERE sn=?");
    $stmt->bind_param("ssssi", $breakfast, $lunch, $evening, $dinner, $sn);

    if ($stmt->execute()) {
        header("Location: admin_food.php?success=Menu updated successfully");
    } else {
        header("Location: admin_food.php?error=Failed to update menu");
    }
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Warden - Update Food Menu</title>
    <link rel="stylesheet" href="admin_food.css">
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
        <h2>Update Food Menu</h2>

        <?php if (isset($_GET['success'])) echo "<div class='success'>{$_GET['success']}</div>"; ?>
        <?php if (isset($_GET['error'])) echo "<div class='error'>{$_GET['error']}</div>"; ?>

        <table border="1">
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Breakfast</th>
                    <th>Lunch</th>
                    <th>Evening</th>
                    <th>Dinner</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <form method="post">
                            <input type="hidden" name="sn" value="<?php echo $row['sn']; ?>">
                            <td><?php echo htmlspecialchars($row['day']); ?></td>
                            <td><input type="text" name="breakfast" value="<?php echo htmlspecialchars($row['breakfast']); ?>"></td>
                            <td><input type="text" name="lunch" value="<?php echo htmlspecialchars($row['lunch']); ?>"></td>
                            <td><input type="text" name="evening" value="<?php echo htmlspecialchars($row['evening']); ?>"></td>
                            <td><input type="text" name="dinner" value="<?php echo htmlspecialchars($row['dinner']); ?>"></td>
                            <td><button type="submit" name="update">Update</button></td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>
