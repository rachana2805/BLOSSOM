<?php
session_start();
include("db.php");
$error = "";

// If already logged in, redirect
if(isset($_SESSION['admin_logged_in'])){
    header("Location: adminhome.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email' AND role='admin'");
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if ($admin['password'] === $password) {
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['user_name'] = $admin['name'];
            $_SESSION['user_role'] = $admin['role'];
            $_SESSION['admin_logged_in'] = true;
            header("Location: adminhome.php");
            exit();
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "Invalid admin email";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login | BLOSSOM</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root { --primary:#8b1e3f; --pink:#c75b6d; --lightpink:#f4ebee; }
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Poppins',sans-serif; height:100vh; display:flex; justify-content:center; align-items:center; background:linear-gradient(135deg,#8b1e3f,#c75b6d); }
.card { background:white; border-radius:20px; padding:50px 60px; text-align:center; box-shadow:0 20px 60px rgba(0,0,0,0.15); width:420px; }
.logo { font-family:'Playfair Display',serif; font-size:28px; color:var(--primary); letter-spacing:2px; }
.admin-tag { font-size:11px; color:#a07080; letter-spacing:3px; text-transform:uppercase; margin-bottom:30px; }
h2 { font-family:'Playfair Display',serif; color:var(--primary); font-size:24px; margin-bottom:6px; }
p { color:#a07080; font-size:13px; margin-bottom:30px; }
input { width:100%; padding:12px 16px; border-radius:10px; border:1px solid #ddd; font-family:'Poppins',sans-serif; font-size:13px; margin-bottom:14px; outline:none; }
input:focus { border-color:var(--pink); }
.login-btn { width:100%; padding:13px; border:none; border-radius:30px; background:linear-gradient(135deg,#8b1e3f,#c75b6d); color:white; font-family:'Poppins',sans-serif; font-size:14px; font-weight:500; cursor:pointer; margin-top:6px; }
.error { color:#e53935; font-size:13px; margin-top:15px; display:block; background:#fdeaed; padding:10px; border-radius:8px;}
</style>
</head>
<body>
<div class="card">
  <div class="logo">BLOSSOM</div>
  <div class="admin-tag">Admin Panel</div>
  <h2>Welcome Back</h2>
  <p>Login to your admin account</p>
  <form method="POST" action="admin.php">
    <input type="email" name="email" placeholder="Admin Email" value="admin@blossom.com" required>
    <input type="password" name="password" placeholder="Password" value="admin123" required>
    <button type="submit" class="login-btn">LOGIN</button>
  </form>
  
  <?php if($error != ""): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>
</div>
</body>
</html>
