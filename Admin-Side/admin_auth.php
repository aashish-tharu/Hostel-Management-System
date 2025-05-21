<?php 
include 'connect.php';
    $sid=$_POST['admin_username'];
    $password=$_POST['admin_password'];
    
    $sql="SELECT * FROM admin_login WHERE id='$sid' and passwd='$password'";
    $data = mysqli_query($conn, $sql);
    $total = mysqli_num_rows($data);
    if($total>0){
        session_start();
        $_SESSION['Username'] = $sid;
        header("Location: admin_dashboard.php");
    }
    else{
     echo "Not Found, Incorrect Email or Password";
    }
?>
