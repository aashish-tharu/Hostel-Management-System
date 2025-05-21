<?php
session_start();
include("connect.php");

if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['approve'])) {
        $id = $_POST['id'];
        $warden = $_POST['warden'];
        $remark = $_POST['remark'];
        
        $sql = "UPDATE gatepass SET statuss='Approved', warden=?, remark=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $warden, $remark, $id);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            $message = "Gatepass approved successfully!";
        } else {
            $message = "Error approving gatepass.";
        }
    } elseif (isset($_POST['reject'])) {
        $id = $_POST['id'];
        $warden = $_POST['warden'];
        $remark = $_POST['remark'];
        
        $sql = "UPDATE gatepass SET statuss='Rejected', warden=?, remark=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $warden, $remark, $id);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            $message = "Gatepass rejected successfully!";
        } else {
            $message = "Error rejecting gatepass.";
        }
    }
}

$sql = "SELECT * FROM gatepass WHERE statuss = 'Pending' OR statuss = '' OR statuss IS NULL";
$result = $conn->query($sql);


$sql_approved = "SELECT * FROM gatepass WHERE statuss = 'Approved'";
$result_approved = $conn->query($sql_approved);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warden Gatepass Approval</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .approved {
            color: green;
            font-weight: bold;
        }
        .pending {
            color: orange;
            font-weight: bold;
        }
        .rejected {
            color: red;
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        button.reject {
            background-color: #f44336;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .error {
            background-color: #f2dede;
            color: #a94442;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Warden Gatepass Approval System</h1>
        
        <?php if (isset($message)): ?>
            <div class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <h2>Pending Gatepass Requests</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Student ID</th>
                        <th>Reason</th>
                        <th>Leave Date</th>
                        <th>Departure Time</th>
                        <th>Return Date</th>
                        <th>Return Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['student_id']; ?></td>
                        <td><?php echo $row['reason']; ?></td>
                        <td><?php echo $row['leave_date']; ?></td>
                        <td><?php echo $row['departure_time']; ?></td>
                        <td><?php echo $row['return_date']; ?></td>
                        <td><?php echo $row['return_time']; ?></td>
                        <td class="pending"><?php echo empty($row['statuss']) ? 'Pending' : $row['statuss']; ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <div class="form-group">
                                    <label for="warden">Your Name:</label>
                                    <input type="text" name="warden" required>
                                </div>
                                <div class="form-group">
                                    <label for="remark">Remark:</label>
                                    <textarea name="remark" rows="2" required></textarea>
                                </div>
                                <button type="submit" name="approve">Approve</button>
                                <button type="submit" name="reject" class="reject">Reject</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No pending gatepass requests.</p>
        <?php endif; ?>
        
        <h2>Approved Gatepasses</h2>
        <?php if ($result_approved->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Student ID</th>
                        <th>Reason</th>
                        <th>Leave Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Warden</th>
                        <th>Remark</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result_approved->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['student_id']; ?></td>
                        <td><?php echo $row['reason']; ?></td>
                        <td><?php echo $row['leave_date']; ?></td>
                        <td><?php echo $row['return_date']; ?></td>
                        <td class="approved"><?php echo $row['statuss']; ?></td>
                        <td><?php echo $row['warden']; ?></td>
                        <td><?php echo $row['remark']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No approved gatepasses.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>