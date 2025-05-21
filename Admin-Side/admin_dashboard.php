<?php
    session_start();
    include("connect.php");
    
    if (!isset($_SESSION['Username'])) {
        header("Location: index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Uni-Hostel Hub</title>
    <link rel="stylesheet" href="admin_home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <header class="admin-header">
        <h1><i class="fas fa-shield-alt"></i> Admin Dashboard</h1>
        <nav class="admin-nav">
            <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="user_manage.php"><i class="fas fa-users"></i> User Management</a>
            <a href="gatepass.php"><i class="fas fa-door-open"></i> Gate Pass</a>
            <a href="admin_food.php"><i class="fas fa-utensils"></i> Food Management</a>
            <a href="admin_security_contacts.php"><i class="fas fa-user-shield"></i> Security</a>
            <a href="admin_maintenance.php"><i class="fas fa-tools"></i> Maintenance</a>
            <a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </header>

    <div class="admin-container">
        <div class="admin-info">
            <img src="media/profile.jpg" class="admin-pic">
            <h2>Welcome, <?php
                    $username = $_SESSION['Username']; 
                    $check = "SELECT * FROM admin_login WHERE id='$username'";
                    $data = mysqli_query($conn, $check);
                    $result = mysqli_fetch_assoc($data);
                    echo $result['name'];
                ?></h2>
        </div>

        <h3 class="admin-help-text">Administration Panel</h3>

        <div class="admin-quick-links">
            <div class="admin-card">
                <div class="card-icon" style="background-color: #4e73df;">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <h2>User Management</h2>
                <p>View and manage all student accounts</p>
                <div class="card-stats">
                    <span><?php 
                        $total_users = $conn->query("SELECT COUNT(*) FROM signup")->fetch_row()[0];
                        echo $total_users; 
                    ?> Registered Users</span>
                </div>
                <a href="user_manage.php" class="admin-btn">Manage Users</a>
            </div>

            <div class="admin-card">
                <div class="card-icon" style="background-color: #1cc88a;">
                    <i class="fas fa-door-open fa-2x"></i>
                </div>
                <h2>Gate Pass Approvals</h2>
                <p>Approve or reject student gate pass requests</p>
                <div class="card-stats">
                    <span> Pending Requests</span>
                </div>
                <a href="gatepass.php" class="admin-btn">Review Passes</a>
            </div>

            <div class="admin-card">
                <div class="card-icon" style="background-color: #36b9cc;">
                    <i class="fas fa-utensils fa-2x"></i>
                </div>
                <h2>Food Management</h2>
                <p>Update mess menu and view food reviews</p>
                <div class="card-stats">
                    <span> New Reviews Today</span>
                </div>
                <div class="card-actions">
                    <a href="admin_food.php" class="admin-btn-sm">Update Menu</a>
                    <a href="admin_food_reviews.php" class="admin-btn-sm">View Reviews & Messages</a>
                </div>
            </div>

            <div class="admin-card">
                <div class="card-icon" style="background-color: #f6c23e;">
                    <i class="fas fa-user-shield fa-2x"></i>
                </div>
                <h2>Security Management</h2>
                <p>Update security contacts and view messages</p>
                <div class="card-stats">
                    <span><?php 
                        $security_msgs = $conn->query("SELECT COUNT(*) FROM message")->fetch_row()[0];
                        echo $security_msgs; 
                    ?> Unread Messages</span>
                </div>
                <div class="card-actions">
                    <a href="admin_security_contacts.php" class="admin-btn-sm">Update Contacts</a>
                    <a href="admin_security_messages.php" class="admin-btn-sm">View Messages</a>
                </div>
            </div>

            <div class="admin-card">
                <div class="card-icon" style="background-color: #e74a3b;">
                    <i class="fas fa-tools fa-2x"></i>
                </div>
                <h2>Maintenance</h2>
                <p>Manage hostel maintenance requests</p>
                <div class="card-stats">
                    <span><?php 
                        $pending_requests = $conn->query("SELECT COUNT(*) FROM maintenance_requests WHERE status='Pending'")->fetch_row()[0];
                        echo $pending_requests; 
                    ?> Pending Requests</span>
                </div>
                <a href="admin_maintenance.php" class="admin-btn">Manage Requests</a>
            </div>

            <div class="admin-card">
                <div class="card-icon" style="background-color: #36b9cc;">
                    <i class="fas fa-utensils fa-2x"></i>
                </div>
                <h2>Medical Requests</h2>
                <p>You can accept or reject the medical</p>
                <div class="card-stats">
                    <span> New Reviews Today</span>
                </div>
                <div class="card-actions">
                    <a href="admin_medical.php" class="admin-btn-sm">Update medical request</a>
                </div>
            </div>
        </div>
    </div>

    <footer class="admin-footer">
        <p>Made with 💖 by Aashish
        </p>
    </footer>

</body>
</html>