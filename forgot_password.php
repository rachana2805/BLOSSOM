<?php
session_start();
include("db.php");

// If already logged in, redirect
if(isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
    exit();
}

$error = "";
$success = "";

if(isset($_POST['reset_password'])){
    $email        = trim($_POST['email']);
    $new_password = $_POST['new_password'];
    $confirm      = $_POST['confirm_password'];

    if(strlen($new_password) < 6){
        $error = "Password must be at least 6 characters long!";
    } elseif($new_password !== $confirm){
        $error = "Passwords do not match!";
    } else {
        $email_escaped = $conn->real_escape_string($email);
        $check = $conn->query("SELECT id FROM users WHERE email='$email_escaped'");
        
        if($check->num_rows > 0){
            $new_password_escaped = $conn->real_escape_string($new_password);
            $sql = "UPDATE users SET password='$new_password_escaped' WHERE email='$email_escaped'";
            if($conn->query($sql) === TRUE){
                $success = "Password reset successfully! Redirecting to login...";
            } else {
                $error = "Database error. Please try again.";
            }
        } else {
            // For security, you shouldn't strictly reveal if an email doesn't exist, but for a college project it's fine
            $error = "No account found with that email address.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password | BLOSSOM Elegant Gifts</title>
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
.welcome { font-family:'Playfair Display',serif; font-size:32px; color:var(--primary); margin:14px 0 4px; }
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
.alert-success { background:#e8f8e8; color:#27ae60; padding:10px 16px; border-radius:8px; font-size:13px; margin-bottom:18px; }
.bottom { margin-top:24px; display:flex; justify-content:center; gap:20px; font-size:13px; }
.bottom a { text-decoration:none; color:var(--primary); transition:opacity 0.2s; }
.bottom a:hover { opacity:0.7; }
</style>
</head>
<body>

<div class="login-card">
  <div class="logo">BLOSSOM</div>
  <div class="welcome">Reset Password</div>
  <div class="sub">Set a new password for your account</div>

  <?php if($error): ?>
    <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>
  <?php if($success): ?>
    <div class="alert-success"><?php echo htmlspecialchars($success); ?></div>
    <script>setTimeout(()=>{ window.location.href='login.php'; }, 2000);</script>
  <?php endif; ?>

  <form method="POST" action="forgot_password.php">
    <div class="input-group">
      <input type="email" name="email" placeholder="Your account Email Address" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
    </div>
    <div class="input-group">
      <input type="password" name="new_password" placeholder="New Password" minlength="6" required>
    </div>
    <div class="input-group">
      <input type="password" name="confirm_password" placeholder="Confirm New Password" minlength="6" required>
    </div>
    <button type="submit" name="reset_password" class="login-btn">UPDATE PASSWORD</button>
  </form>

  <div class="bottom">
    <a href="login.php">&larr; Back to Login</a>
  </div>
</div>

</body>
</html>
