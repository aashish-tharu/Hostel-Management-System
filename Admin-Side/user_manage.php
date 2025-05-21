<?php
session_start();
include("connect.php");

if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $id = $_POST['student_id'];
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $student_id = $conn->real_escape_string($_POST['student_id']);
    $hostel = $conn->real_escape_string($_POST['Hostel']);
    $floor = (int)$_POST['floor'];
    $room = (int)$_POST['room'];
    
    $stmt = $conn->prepare("UPDATE signup SET 
                          Name = ?, 
                          Email = ?, 
                          StudentID = ?, 
                          HostelName = ?, 
                          Floor = ?, 
                          Roomno = ? 
                          WHERE StudentID = ?");
    $stmt->bind_param("ssssiis", $name, $email, $student_id, $hostel, $floor, $room, $id);
    
    if ($stmt->execute()) {
        $success = "User updated successfully!";
    } else {
        $error = "Error updating user: " . $stmt->error;
    }
}


$users = $conn->query("SELECT * FROM signup");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin_style.css">
    <style>
        .user-management {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .user-table th, .user-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .user-table th {
            background-color: #4e73df;
            color: white;
            font-weight: 600;
        }
        
        .user-table tr:nth-child(even) {
            background-color: #f8f9fc;
        }
        
        .user-table tr:hover {
            background-color: #f1f3f9;
        }
        
        .action-btns {
            display: flex;
            gap: 5px;
        }
        
        .edit-btn, .save-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .edit-btn {
            background-color: #f6c23e;
            color: #000;
        }
        
        .save-btn {
            background-color: #1cc88a;
            color: white;
        }
        
        .edit-form {
            display: none;
        }
        
        .edit-mode .view-mode {
            display: none;
        }
        
        .edit-mode .edit-form {
            display: block;
        }
        
        .form-group {
            margin-bottom: 10px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .status-message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="user-management">
        <h1><i class="fas fa-users-cog"></i> User Management</h1>
        
        <?php if (isset($success)): ?>
            <div class="status-message success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="status-message error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Student ID</th>
                    <th>Hostel</th>
                    <th>Floor</th>
                    <th>Room</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $users->fetch_assoc()): ?>
                <tr id="user-<?php echo $user['StudentID']; ?>">
                    <td><?php echo $user['StudentID']; ?></td>
                    
                   
                    <td class="view-mode"><?php echo htmlspecialchars($user['Name']); ?></td>
                    <td class="view-mode"><?php echo htmlspecialchars($user['Email']); ?></td>
                    <td class="view-mode"><?php echo htmlspecialchars($user['StudentID']); ?></td>
                    <td class="view-mode"><?php echo htmlspecialchars($user['HostelName']); ?></td>
                    <td class="view-mode"><?php echo $user['Floor']; ?></td>
                    <td class="view-mode"><?php echo $user['Roomno']; ?></td>
                    
                
                    <td class="edit-form">
                        <form method="post" class="user-edit-form">
                            <input type="hidden" name="user_id" value="<?php echo $user['StudentID']; ?>">
                            <div class="form-group">
                                <input type="text" name="name" value="<?php echo htmlspecialchars($user['Name']); ?>" required>
                            </div>
                    </td>
                    <td class="edit-form">
                            <div class="form-group">
                                <input type="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
                            </div>
                    </td>
                    <td class="edit-form">
                            <div class="form-group">
                                <input type="text" name="student_id" value="<?php echo htmlspecialchars($user['StudentID']); ?>" required>
                            </div>
                    </td>
                    <td class="edit-form">
                            <div class="form-group">
                                <input type="text" name="Hostel" value="<?php echo htmlspecialchars($user['HostelName']); ?>" required>
                            </div>
                    </td>
                    <td class="edit-form">
                            <div class="form-group">
                                <input type="number" name="floor" min="0" max="10" value="<?php echo $user['Floor']; ?>" required>
                            </div>
                    </td>
                    <td class="edit-form">
                            <div class="form-group">
                                <input type="number" name="room" value="<?php echo $user['Roomno']; ?>" required>
                            </div>
                    </td>
                    <td>
                        <div class="action-btns view-mode">
                            <button type="button" class="edit-btn" onclick="enableEditMode(<?php echo $user['StudentID']; ?>)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                        <div class="action-btns edit-form">
                            <button type="submit" name="update_user" class="save-btn">
                                <i class="fas fa-save"></i> Save
                            </button>
                            <button type="button" class="edit-btn" onclick="disableEditMode(<?php echo $user['StudentID']; ?>)">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                        </div>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        function enableEditMode(userId) {
            document.getElementById(`user-${userId}`).classList.add('edit-mode');
        }
        
        function disableEditMode(userId) {
            document.getElementById(`user-${userId}`).classList.remove('edit-mode');
        }
    </script>
</body>
</html>