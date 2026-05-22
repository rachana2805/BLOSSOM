<?php
session_start();
include("db.php");

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

$passMsg = "";
$clearMsg = "";

// Handle password change
if(isset($_POST['change_password'])){
    $newPass = $conn->real_escape_string($_POST['new_password']);
    $confirmPass = $conn->real_escape_string($_POST['confirm_password']);
    
    if($newPass === $confirmPass){
        $conn->query("UPDATE users SET password='$newPass' WHERE role='admin' LIMIT 1");
        $passMsg = "Password updated successfully!";
    } else {
        $passMsg = "Passwords do not match!";
    }
}

// Handle clear all data
if(isset($_POST['clear_all'])){
    $conn->query("TRUNCATE TABLE products");
    $conn->query("TRUNCATE TABLE orders");
    $conn->query("TRUNCATE TABLE order_items");
    $conn->query("TRUNCATE TABLE cart");
    $conn->query("DELETE FROM users WHERE role='user'"); // keep admin
    $clearMsg = "All products, orders, and customer data cleared!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Settings | BLOSSOM Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root { --pink:#c75b6d; --darkpink:#8b1e3f; --lightpink:#f4ebee; --text:#3a1020; --muted:#a07080; }
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Poppins',sans-serif; background:var(--lightpink); color:var(--text); display:flex; min-height:100vh; }
.sidebar { width:240px; background:linear-gradient(160deg,#8b1e3f,#c75b6d); display:flex; flex-direction:column; padding:30px 0; position:fixed; height:100vh; }
.sidebar .logo { font-family:'Playfair Display',serif; font-size:26px; color:white; text-align:center; padding-bottom:10px; letter-spacing:2px; }
.sidebar .admin-label { text-align:center; color:rgba(255,255,255,0.6); font-size:11px; letter-spacing:2px; text-transform:uppercase; margin-bottom:35px; }
.sidebar nav a { display:flex; align-items:center; gap:12px; padding:14px 30px; color:rgba(255,255,255,0.75); text-decoration:none; font-size:14px; font-weight:500; transition:all 0.2s; border-left:3px solid transparent; }
.sidebar nav a:hover, .sidebar nav a.active { background:rgba(255,255,255,0.15); color:white; border-left:3px solid white; }
.sidebar nav a i { width:18px; text-align:center; }
.sidebar .logout { margin-top:auto; padding:14px 30px; display:flex; align-items:center; gap:12px; color:rgba(255,255,255,0.6); font-size:14px; cursor:pointer; transition:0.2s; text-decoration:none; }
.sidebar .logout:hover { color:white; }
.main { margin-left:240px; flex:1; padding:40px; }
.topbar { margin-bottom:35px; }
.topbar h1 { font-family:'Playfair Display',serif; font-size:30px; color:var(--darkpink); }
.card { background:white; border-radius:16px; padding:28px; box-shadow:0 4px 15px rgba(139,30,63,0.07); margin-bottom:24px; max-width:600px; }
.card h3 { font-family:'Playfair Display',serif; color:var(--darkpink); font-size:18px; margin-bottom:20px; }
.field { margin-bottom:16px; }
.field label { display:block; font-size:13px; color:var(--muted); margin-bottom:6px; font-weight:500; }
.field input { width:100%; padding:10px 14px; border-radius:8px; border:1px solid #ddd; font-family:'Poppins',sans-serif; font-size:13px; outline:none; }
.field input:focus { border-color:var(--pink); }
.save-btn { background:linear-gradient(135deg,#8b1e3f,#c75b6d); color:white; border:none; padding:10px 28px; border-radius:30px; cursor:pointer; font-family:'Poppins',sans-serif; font-size:13px; font-weight:500; margin-top:8px; }
.danger-btn { background:none; border:1px solid #e53935; color:#e53935; padding:10px 28px; border-radius:30px; cursor:pointer; font-family:'Poppins',sans-serif; font-size:13px; font-weight:500; }
.danger-btn:hover { background:#e53935; color:white; }
.success-msg { color:#2e7d32; font-size:13px; margin-top:10px; display:block; background:#e8f8e8; padding:10px; border-radius:8px;}
</style>
</head>
<body>
<div class="sidebar">
  <div class="logo">BLOSSOM</div>
  <div class="admin-label">Admin Panel</div>
  <nav>
 <a href="dashboard.php"><i class="fas fa-th-large"></i> Dashboard</a>
<a href="products-admin.php"><i class="fas fa-spa"></i> Products</a>
<a href="orders-admin.php"><i class="fas fa-box"></i> Orders</a>
<a href="settings-admin.php" class="active"><i class="fas fa-cog"></i> Settings</a>
  </nav>
  <a href="admin_logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>
<div class="main">
  <div class="topbar"><h1>Settings</h1></div>

  <?php if($clearMsg): ?>
      <div class="success-msg"><?php echo htmlspecialchars($clearMsg); ?></div>
  <?php endif; ?>

  <div class="card">
    <h3>Shop Info</h3>
    <div class="field"><label>Shop Name</label><input type="text" id="shopName" placeholder="BLOSSOM"></div>
    <div class="field"><label>Contact Email</label><input type="email" id="shopEmail" placeholder="blossoms@gmail.com"></div>
    <div class="field"><label>Phone Number</label><input type="tel" id="shopPhone" placeholder="+91 97392 96978"></div>
    <div class="field"><label>Address</label><input type="text" id="shopAddress" placeholder="Mangalore, Karnataka"></div>
    <button class="save-btn" onclick="saveShop()">Save Changes</button>
    <div class="success-msg" id="shopMsg" style="display:none;">&#10003; Saved successfully!</div>
  </div>

  <div class="card">
    <h3>Change Admin Password</h3>
    <?php if($passMsg): ?>
      <div class="success-msg" style="margin-bottom:10px;"><?php echo htmlspecialchars($passMsg); ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="field"><label>New Password</label><input type="password" name="new_password" placeholder="Enter new password" required></div>
        <div class="field"><label>Confirm Password</label><input type="password" name="confirm_password" placeholder="Confirm new password" required></div>
        <button type="submit" name="change_password" class="save-btn">Update Password</button>
    </form>
  </div>

  <div class="card">
    <h3>Danger Zone</h3>
    <p style="font-size:13px;color:var(--muted);margin-bottom:16px;">This will delete all products, orders and customers from the system.</p>
    <form method="POST" onsubmit="return confirm('Are you sure? This will delete ALL data permanently!');">
        <button type="submit" name="clear_all" class="danger-btn"><i class="fas fa-trash"></i> Clear All Data</button>
    </form>
  </div>
</div>
<script>
function loadSettings() {
  const s = JSON.parse(localStorage.getItem("shopSettings")) || {};
  if (s.name) document.getElementById("shopName").value = s.name;
  if (s.email) document.getElementById("shopEmail").value = s.email;
  if (s.phone) document.getElementById("shopPhone").value = s.phone;
  if (s.address) document.getElementById("shopAddress").value = s.address;
}
function saveShop() {
  localStorage.setItem("shopSettings", JSON.stringify({
    name: document.getElementById("shopName").value,
    email: document.getElementById("shopEmail").value,
    phone: document.getElementById("shopPhone").value,
    address: document.getElementById("shopAddress").value
  }));
  const msg = document.getElementById("shopMsg");
  msg.style.display = "block";
  setTimeout(() => msg.style.display = "none", 2000);
}
loadSettings();
</script>
</body>
</html>
