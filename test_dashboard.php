<?php
session_start();
$_SESSION['admin_logged_in'] = true;
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = 'Admin User';
$_SESSION['user_role'] = 'admin';
include("dashboard.php");
?>
