<?php
session_start();
include("db.php");

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

$result = $conn->query("SELECT * FROM users WHERE role='user' ORDER BY id DESC");
$count = $result->num_rows;

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id=$id AND role='user'");
    header("Location: customers-admin.php");
    exit();
}

// Optional Add Customer Admin
if (isset($_POST['add'])) {
    $name  = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']); // Using phone in email or something, skip phone for now as it's not in our schema
    $pass  = "user123";
    $conn->query("INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$pass', 'user')");
    header("Location: customers-admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customers | BLOSSOM Admin</title>
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
.topbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:35px; }
.topbar h1 { font-family:'Playfair Display',serif; font-size:30px; color:var(--darkpink); }
.card { background:white; border-radius:16px; padding:28px; box-shadow:0 4px 15px rgba(139,30,63,0.07); margin-bottom:30px; }
.card-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
.card-header h3 { font-family:'Playfair Display',serif; color:var(--darkpink); font-size:20px; }
.add-form { display:flex; gap:12px; flex-wrap:wrap; margin-bottom:20px; align-items:center; }
.add-form input { padding:10px 14px; border-radius:8px; border:1px solid #ddd; font-family:'Poppins',sans-serif; font-size:13px; outline:none; }
.add-form input:focus { border-color:var(--pink); }
.add-btn { background:linear-gradient(135deg,#8b1e3f,#c75b6d); color:white; border:none; padding:10px 22px; border-radius:30px; cursor:pointer; font-family:'Poppins',sans-serif; font-size:13px; font-weight:500; }
table { width:100%; border-collapse:collapse; font-size:13px; }
th { text-align:left; color:var(--muted); font-weight:500; padding:8px 10px; border-bottom:1px solid #f0e0e5; font-size:12px; text-transform:uppercase; }
td { padding:12px 10px; border-bottom:1px solid #fdf0f3; color:var(--text); vertical-align:middle; }
tr:last-child td { border-bottom:none; }
.avatar { width:36px; height:36px; border-radius:50%; background:linear-gradient(135deg,#8b1e3f,#c75b6d); display:flex; align-items:center; justify-content:center; color:white; font-size:14px; font-weight:600; }
.delete-btn { background:none; border:1px solid #e53935; color:#e53935; border-radius:6px; padding:4px 10px; font-size:11px; cursor:pointer; font-family:'Poppins',sans-serif; text-decoration:none; }
.delete-btn:hover { background:#e53935; color:white; }
.badge { background:var(--lightpink); color:var(--darkpink); padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600; }
.empty-msg { text-align:center; color:var(--muted); padding:30px; font-size:14px; }
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
    <a href="customers-admin.php" class="active"><i class="fas fa-users"></i> Customers</a>
    <a href="settings-admin.php"><i class="fas fa-cog"></i> Settings</a>
  </nav>
  <a href="admin_logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>
<div class="main">
  <div class="topbar"><h1>Customers</h1><span class="badge"><?php echo $count; ?> customers</span></div>
  <div class="card">
    <div class="card-header"><h3>Add Customer</h3></div>
    <form class="add-form" method="POST">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="tel" name="phone" placeholder="Phone Number" required>
      <button type="submit" name="add" class="add-btn"><i class="fas fa-plus"></i> Add Customer</button>
    </form>
  </div>
  <div class="card">
    <div class="card-header"><h3>Customer List</h3></div>
    <table>
      <thead><tr><th></th><th>Name</th><th>Email</th><th>Action</th></tr></thead>
      <tbody>
        <?php if($count == 0): ?>
          <tr><td colspan="4" class="empty-msg">No customers yet.</td></tr>
        <?php else: ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><div class="avatar"><?php echo strtoupper(substr($row['name'], 0, 1)); ?></div></td>
              <td><?php echo htmlspecialchars($row['name']); ?></td>
              <td><?php echo htmlspecialchars($row['email']); ?></td>
              <td><a href="?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Delete this customer?')"><i class="fas fa-trash"></i> Delete</a></td>
            </tr>
          <?php endwhile; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
