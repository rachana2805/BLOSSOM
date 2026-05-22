<?php
session_start();
include("db.php");

// If already logged in, redirect
if(isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}

$error = "";
$success = "";

if(isset($_POST['register'])){
    $first_name  = trim($_POST['first_name']);
    $last_name   = trim($_POST['last_name']);
    $name        = $first_name . " " . $last_name;
    $email       = trim($_POST['email']);
    $password    = $_POST['password'];
    $confirm     = $_POST['confirm_password'];

    if(strlen($password) < 6){
        $error = "Password must be at least 6 characters long!";
    } elseif($password !== $confirm){
        $error = "Passwords do not match!";
    } else {
        // Check if email already exists
        $check = $conn->query("SELECT id FROM users WHERE email='$email'");
        if($check->num_rows > 0){
            $error = "Email already registered. Please login.";
        } else {
            $sql = "INSERT INTO users (name, email, password, role) 
                    VALUES ('$name', '$email', '$password', 'user')";
            if($conn->query($sql) === TRUE){
                $success = "Account created! Redirecting to login...";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Blossom &mdash; Sign Up</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --rose: #8B2252; --rose-light: #a83068;
    --blush: #f0e8ed; --blush-mid: #e8d9e3;
    --white: #ffffff; --gray: #9a8a90; --text: #3d2535;
  }
  body {
    min-height: 100vh; display: flex; align-items: center; justify-content: center;
    background-color: var(--blush);
    background-image: radial-gradient(ellipse at 20% 20%, #e5cedd 0%, transparent 50%),
                      radial-gradient(ellipse at 80% 80%, #dfc8d8 0%, transparent 50%);
    font-family: 'Jost', sans-serif;
  }
  .card {
    background: var(--white); border-radius: 20px; padding: 52px 60px 48px;
    width: 100%; max-width: 520px;
    box-shadow: 0 8px 48px rgba(139, 34, 82, 0.08);
    animation: fadeUp 0.6s ease both;
  }
  @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
  .brand { text-align:center; letter-spacing:0.18em; font-size:13px; font-weight:500; color:var(--rose); text-transform:uppercase; margin-bottom:6px; }
  h1 { font-family:'Cormorant Garamond',serif; font-size:42px; font-weight:600; color:var(--rose); text-align:center; line-height:1.1; margin-bottom:8px; }
  .subtitle { text-align:center; color:var(--gray); font-size:14px; font-weight:300; margin-bottom:30px; }
  .row { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
  .field { position:relative; margin-bottom:14px; }
  .field input {
    width:100%; padding:14px 18px;
    border:1.5px solid var(--blush-mid); border-radius:10px;
    background:var(--blush); font-family:'Jost',sans-serif;
    font-size:14px; font-weight:300; color:var(--text);
    outline:none; transition:border-color 0.25s, background 0.25s;
  }
  .field input::placeholder { color:var(--gray); }
  .field input:focus { border-color:var(--rose); background:#fff; }
  .btn {
    width:100%; padding:15px; margin-top:10px; background:var(--rose);
    color:#fff; border:none; border-radius:50px; font-family:'Jost',sans-serif;
    font-size:13px; font-weight:500; letter-spacing:0.15em; text-transform:uppercase;
    cursor:pointer; transition:background 0.25s, transform 0.15s;
  }
  .btn:hover { background:var(--rose-light); transform:translateY(-1px); }
  .alert { padding:11px 16px; border-radius:8px; font-size:13px; margin-bottom:16px; text-align:center; }
  .alert-error { background:#fde8e8; color:#c0392b; }
  .alert-success { background:#e8f8e8; color:#27ae60; }
  .footer-links { display:flex; justify-content:center; margin-top:24px; }
  .footer-links a { font-size:13px; font-weight:300; color:var(--rose); text-decoration:none; }
  .footer-links a:hover { opacity:0.7; }
</style>
</head>
<body>
<div class="card">
  <form method="POST" action="register.php">
    <p class="brand">Blossom</p>
    <h1>Create Account</h1>
    <p class="subtitle">Join us and start your journey</p>

    <?php if($error): ?>
      <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if($success): ?>
      <div class="alert alert-success"><?php echo $success; ?></div>
      <script>setTimeout(()=>{ window.location.href='login.php'; }, 1500);</script>
    <?php endif; ?>

    <div class="row">
      <div class="field"><input type="text" name="first_name" placeholder="First Name" required></div>
      <div class="field"><input type="text" name="last_name" placeholder="Last Name" required></div>
    </div>
    <div class="field"><input type="email" name="email" placeholder="Email Address" required></div>
    <div class="field"><input type="password" name="password" placeholder="Password" minlength="6" required></div>
    <div class="field"><input type="password" name="confirm_password" placeholder="Confirm Password" minlength="6" required></div>

    <button type="submit" name="register" class="btn">Create Account</button>
  </form>
  <div class="footer-links">
    <a href="login.php">Already have an account? Login</a>
  </div>
</div>
</body>
</html>
