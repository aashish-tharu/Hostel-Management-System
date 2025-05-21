<?php
    session_start();
    include("connect.php");
    
    if (!isset($_SESSION['Username'])) {
        header("Location: index.php");
        exit();
    }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['id'])) {
        $id = $conn->real_escape_string($_POST['id']);
        $new_status = ($_POST['action'] === 'approve') ? 'Approved' : 'Rejected';
        
        $update_sql = "UPDATE medical_leaves SET status = '$new_status' WHERE id = $id";
        if ($conn->query($update_sql) === TRUE) {
            $message = "Leave request has been " . strtolower($new_status) . " successfully.";
        } else {
            $message = "Error updating record: " . $conn->error;
        }
    }
}

$sql = "SELECT id, student_id, full_name, symptoms, leave_from, leave_to, doctor_help, ambulance, status, submitted_at 
        FROM medical_leaves 
        ORDER BY status ASC, submitted_at DESC";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Leaves Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            position: sticky;
            top: 0;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-pending {
            color: orange;
            font-weight: bold;
        }
        .status-approved {
            color: green;
            font-weight: bold;
        }
        .status-rejected {
            color: red;
            font-weight: bold;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            color: white;
            font-weight: bold;
        }
        .btn-approve {
            background-color: #4CAF50;
        }
        .btn-reject {
            background-color: #f44336;
        }
        .btn:hover {
            opacity: 0.8;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            display: inline-block;
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
    <h1>Medical Leaves Management</h1>
    
    <?php if (isset($message)): ?>
        <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student ID</th>
                    <th>Full Name</th>
                    <th>Symptoms</th>
                    <th>Leave From</th>
                    <th>Leave To</th>
                    <th>Doctor Help</th>
                    <th>Ambulance</th>
                    <th>Status</th>
                    <th>Submitted At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['student_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['symptoms']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['leave_from'])); ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['leave_to'])); ?></td>
                        <td><?php echo $row['doctor_help'] ? 'Yes' : 'No'; ?></td>
                        <td><?php echo $row['ambulance'] ? 'Yes' : 'No'; ?></td>
                        <td class="status-<?php echo strtolower($row['status']); ?>">
                            <?php echo $row['status']; ?>
                        </td>
                        <td><?php echo date('M d, Y H:i', strtotime($row['submitted_at'])); ?></td>
                        <td>
                            <?php if ($row['status'] === 'Pending'): ?>
                            <div class="action-buttons">
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="btn btn-approve">Approve</button>
                                </form>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="btn btn-reject">Reject</button>
                                </form>
                            </div>
                            <?php else: ?>
                                <em>Processed</em>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No medical leave records found.</p>
    <?php endif; ?>
    
    <?php $conn->close(); ?>
</body>
</html>