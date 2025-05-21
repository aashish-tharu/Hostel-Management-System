<!DOCTYPE html>
<html>
    <head>
        <title>Admin Login</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link rel="stylesheet" href="admin_style.css">
    </head>
    <body>
        <div class="admin-container" id="adminLogin">
            <div class="admin-logo">
                <h1>U</h1>
                <h2>ADMIN PORTAL</h2>
                <h3>HOSTEL MANAGEMENT SYSTEM</h3>
            </div>
            <form method="post" action="admin_auth.php">
                <div class="input-group">
                    <i class="fa-solid fa-user-shield"></i>
                    <input type="text" name="admin_username" placeholder="Admin ID" required>
                </div>
                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="admin_password" placeholder="Password" required>
                </div>
                <input type="submit" class="admin-btn" value="Login">
                <div class="admin-links">
                    <a href="forgot_password.php"><i class="fa-solid fa-key"></i> Forgot Password?</a>
                </div>
            </form>
            <div class="admin-footer">
                <p>Restricted Access. Unauthorized entry prohibited.</p>
                <p class="copyright">© 2025 Chitkara University Admin Portal</p>
            </div>
        </div>
        <script src="admin_script.js"></script>
    </body>
</html>