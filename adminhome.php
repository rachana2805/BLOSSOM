<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Home | BLOSSOM</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root { --primary:#8b1e3f; --pink:#c75b6d; --lightpink:#f4ebee; }
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Poppins',sans-serif; min-height:100vh; display:flex; flex-direction:column; justify-content:center; align-items:center; background:linear-gradient(135deg,#8b1e3f,#c75b6d); }
.logo { font-family:'Playfair Display',serif; font-size:42px; color:white; letter-spacing:3px; margin-bottom:8px; }
.subtitle { color:rgba(255,255,255,0.7); font-size:13px; letter-spacing:3px; text-transform:uppercase; margin-bottom:60px; }
.buttons { display:flex; gap:24px; }
.btn { width:200px; padding:24px; border-radius:20px; text-align:center; cursor:pointer; text-decoration:none; transition:transform 0.2s, box-shadow 0.2s; }
.btn:hover { transform:translateY(-5px); box-shadow:0 15px 35px rgba(0,0,0,0.2); }
.btn i { font-size:36px; margin-bottom:14px; display:block; }
.btn span { font-size:15px; font-weight:600; display:block; }
.btn small { font-size:11px; opacity:0.8; margin-top:4px; display:block; }
.btn-white { background:white; color:var(--primary); }
.btn-outline { background:rgba(255,255,255,0.15); color:white; border:2px solid rgba(255,255,255,0.5); backdrop-filter:blur(10px); }
.logout { margin-top:50px; color:rgba(255,255,255,0.6); font-size:13px; text-decoration:none; cursor:pointer; }
.logout:hover { color:white; }
</style>
</head>
<body>
<div class="logo">BLOSSOM</div>
<div class="subtitle">Admin Panel</div>

<div class="buttons">
  <a href="index.php" class="btn btn-white">
    <!-- Using inline SVG or standard FA -->
    <i class="fas fa-eye" style="color:var(--primary)"></i>
    <span>View Website</span>
    <small>See the shop</small>
  </a>
  <a href="dashboard.php" class="btn btn-outline">
    <i class="fas fa-th-large"></i>
    <span>Dashboard</span>
    <small>Manage your store</small>
  </a>
</div>

<a href="admin_logout.php" class="logout">&larr; Logout</a>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</body>
</html>
