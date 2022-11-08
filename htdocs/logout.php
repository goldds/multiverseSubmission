<?php 
session_start();
    session_destroy();
    unset($_SESSION['user_id']);
    unset($_SESSION['email']);
    header('location:index.php');
?>