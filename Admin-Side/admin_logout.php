<?php 
    session_start();
    session_abort();
    header("location:index.php");
?>