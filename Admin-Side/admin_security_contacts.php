<?php
session_start();
include("connect.php");


$sql = "SELECT * FROM contact ORDER BY sno";
$result = $conn->query($sql);


if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['update'])) {
    $sno = $_POST['sno'];
    $name = $_POST['name'];
    $designation = $_POST['designation'];
    $hostel = $_POST['hostel'];
    $mobile = $_POST['mobile'];
    $personal = $_POST['personal'];

    $stmt = $conn->prepare("UPDATE contact SET name=?, designation=?, hostel=?, mobile=?, personal=? WHERE sno=?");
    $stmt->bind_param("sssssi", $name, $designation, $hostel, $mobile, $personal, $sno);

    if ($stmt->execute()) {
        header("Location: admin_security_contacts.php?success=Contact updated successfully");
    } else {
        header("Location: admin_security_contacts.php?error=Failed to update contact");
    }
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Warden - Update Contact</title>
    <link rel="stylesheet" href="admin_security.css">
</head>
<body>
    <header>
        <h1>Warden Dashboard - Update Contact</h1>
        <nav>
            <a href="admin_dashboard.php">Home</a>
            <a href="admin_security_contacts.php">Update Contact</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="container">
        <h2>Update Contact Information</h2>

        <?php if (isset($_GET['success'])) echo "<div class='success'>{$_GET['success']}</div>"; ?>
        <?php if (isset($_GET['error'])) echo "<div class='error'>{$_GET['error']}</div>"; ?>

        <table border="1">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Hostel</th>
                    <th>Mobile</th>
                    <th>Personal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <form method="post">
                            <input type="hidden" name="sno" value="<?php echo $row['sno']; ?>">
                            <td><input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>"></td>
                            <td><input type="text" name="designation" value="<?php echo htmlspecialchars($row['designation']); ?>"></td>
                            <td><input type="text" name="hostel" value="<?php echo htmlspecialchars($row['hostel']); ?>"></td>
                            <td><input type="text" name="mobile" value="<?php echo htmlspecialchars($row['mobile']); ?>"></td>
                            <td><input type="text" name="personal" value="<?php echo htmlspecialchars($row['personal']); ?>"></td>
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