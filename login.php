<?php
session_start();
include("db.php");

// If already logged in, redirect
if(isset($_SESSION['user_id'])){
    if(isset($_SESSION['admin_logged_in'])){
        header("Location: dashboard.php");
    } else {
        header("Location: index.php");
    }
    exit();
}

$error = "";

if(isset($_POST['login'])){
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $sql    = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $user = $result->fetch_assoc();

        if($user['password'] === $password){
            // Set session variables
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            if($user['role'] === 'admin'){
                $_SESSION['admin_logged_in'] = true;
                header("Location: dashboard.php");
            } else {
                // If there was a redirect target set (e.g. from checkout), go there
                $redirect = $_SESSION['redirect_after_login'] ?? 'index.php';
                unset($_SESSION['redirect_after_login']);
                header("Location: " . $redirect);
            }
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | BLOSSOM Elegant Gifts</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root { --primary:#8b1e3f; --card:rgba(255,255,255,0.95); }
*, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
body {
  font-family:'Poppins',sans-serif; min-height:100vh;
  display:flex; justify-content:center; align-items:center;
  background:linear-gradient(135deg,#efe4e8,#f6eef1);
}
.login-card {
  width:100%; max-width:480px; padding:56px 60px;
  border-radius:20px; background:var(--card); text-align:center;
  box-shadow:0 12px 40px rgba(139,30,63,0.12);
  animation:fadeUp 0.5s ease both;
}
@keyframes fadeUp { from{opacity:0;transform:translateY(18px);} to{opacity:1;transform:translateY(0);} }
.logo { font-family:'Playfair Display',serif; font-size:28px; color:var(--primary); letter-spacing:4px; }
.welcome { font-family:'Playfair Display',serif; font-size:36px; color:var(--primary); margin:14px 0 4px; }
.sub { color:#888; font-size:14px; margin-bottom:32px; }
.input-group { margin-bottom:18px; }
.input-group input {
  width:100%; padding:13px 18px; border-radius:10px;
  border:1.5px solid #e0d0d8; outline:none; background:#fafafa;
  font-family:'Poppins',sans-serif; font-size:14px; color:#333;
  transition:border-color 0.25s;
}
.input-group input:focus { border-color:var(--primary); background:#fff; }
.login-btn {
  margin-top:6px; width:100%; padding:14px; border:none;
  border-radius:30px; background:linear-gradient(to right,#8b1e3f,#b24b63);
  color:white; font-size:15px; font-family:'Poppins',sans-serif;
  font-weight:500; cursor:pointer; transition:opacity 0.25s,transform 0.15s;
}
.login-btn:hover { opacity:0.9; transform:translateY(-1px); }
.alert-error { background:#fde8e8; color:#c0392b; padding:10px 16px; border-radius:8px; font-size:13px; margin-bottom:18px; }
.bottom { margin-top:24px; display:flex; justify-content:center; gap:30px; font-size:13px; flex-wrap:wrap; }
.bottom a { text-decoration:none; color:var(--primary); transition:opacity 0.2s; }
.bottom a:hover { opacity:0.7; }
</style>
</head>
<body>

<div class="login-card">
  <div class="logo">BLOSSOM</div>
  <div class="welcome">Welcome Back</div>
  <div class="sub">Login to your account</div>

  <?php if($error): ?>
    <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form method="POST" action="login.php">
    <div class="input-group">
      <input type="email" name="email" placeholder="Email Address" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
    </div>
    <div class="input-group">
      <input type="password" name="password" placeholder="Password" required>
    </div>
    <button type="submit" name="login" class="login-btn">LOGIN</button>
  </form>

  <div class="bottom">
    <a href="forgot_password.php">Forgot Password?</a>
    <a href="register.php">Don't have an account? Sign up</a>
  </div>
</div>

</body>
</html>