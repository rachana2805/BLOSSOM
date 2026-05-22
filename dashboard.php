<?php
session_start();
include("db.php");

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

// ---- Fetch live stats ----
$total_orders   = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM orders"))[0];
$total_products = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM products"))[0];
$total_users    = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM users WHERE role='user'"))[0];

// ---- Recent 5 orders ----
$orders_result = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC LIMIT 5");

// ---- All products ----
$products_result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | BLOSSOM</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root { --pink:#c75b6d; --darkpink:#8b1e3f; --lightpink:#f4ebee; --text:#3a1020; --muted:#a07080; }
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Poppins',sans-serif; background:var(--lightpink); color:var(--text); display:flex; min-height:100vh; }

/* SIDEBAR */
.sidebar { width:240px; background:linear-gradient(160deg,#8b1e3f,#c75b6d); display:flex; flex-direction:column; padding:30px 0; position:fixed; height:100vh; top:0; left:0; }
.sidebar .logo { font-family:'Playfair Display',serif; font-size:26px; color:white; text-align:center; padding-bottom:4px; }
.sidebar .admin-label { text-align:center; color:rgba(255,255,255,0.55); font-size:11px; letter-spacing:2px; text-transform:uppercase; margin-bottom:30px; }
.sidebar nav a { display:flex; align-items:center; gap:12px; padding:14px 30px; color:rgba(255,255,255,0.75); text-decoration:none; font-size:14px; border-left:3px solid transparent; transition:0.2s; }
.sidebar nav a:hover, .sidebar nav a.active { background:rgba(255,255,255,0.15); color:white; border-left:3px solid white; }
.sidebar .logout { margin-top:auto; padding:14px 30px; color:rgba(255,255,255,0.7); text-decoration:none; display:flex; align-items:center; gap:12px; font-size:14px; }

/* MAIN */
.main { margin-left:240px; flex:1; padding:40px; }
.topbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:35px; }
.topbar h1 { font-family:'Playfair Display',serif; font-size:30px; color:var(--darkpink); }
.topbar .date { color:var(--muted); font-size:13px; }

/* STATS */
.stats { display:grid; grid-template-columns:repeat(3,1fr); gap:24px; margin-bottom:36px; }
.stat-card { background:white; border-radius:16px; padding:28px; display:flex; gap:20px; box-shadow:0 4px 15px rgba(139,30,63,0.07); }
.stat-icon { width:55px; height:55px; border-radius:14px; background:linear-gradient(135deg,#8b1e3f,#c75b6d); display:flex; align-items:center; justify-content:center; color:white; font-size:22px; flex-shrink:0; }
.stat-info p { font-size:12px; color:var(--muted); text-transform:uppercase; }
.stat-info h2 { font-size:30px; color:var(--darkpink); }

/* GRID */
.grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:30px; margin-bottom:30px; }
.card, .full-card { background:white; border-radius:16px; padding:28px; box-shadow:0 4px 15px rgba(139,30,63,0.07); }
.card-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; }
.card-header h3 { font-family:'Playfair Display',serif; color:var(--darkpink); font-size:18px; }
.badge { background:var(--lightpink); color:var(--darkpink); padding:4px 12px; border-radius:20px; font-size:12px; }

/* TABLE */
table { width:100%; border-collapse:collapse; font-size:13px; }
th, td { padding:10px 8px; border-bottom:1px solid #f0e0e5; text-align:left; }
th { color:var(--muted); text-transform:uppercase; font-size:11px; letter-spacing:0.5px; }
td { color:#444; }
.empty { text-align:center; color:var(--muted); padding:20px; }

/* PRODUCT IMG THUMB */
.thumb { width:44px; height:44px; object-fit:cover; border-radius:8px; }

/* View All link */
.view-all { font-size:12px; color:var(--pink); text-decoration:none; }
.view-all:hover { text-decoration:underline; }
</style>
</head>
<body>

<div class="sidebar">
  <div class="logo">BLOSSOM</div>
  <div class="admin-label">Admin Panel</div>
  <nav>
    <a href="dashboard.php" class="active"><i class="fas fa-th-large"></i> Dashboard</a>
    <a href="products-admin.php"><i class="fas fa-spa"></i> Products</a>
    <a href="orders-admin.php"><i class="fas fa-box"></i> Orders</a>
    <a href="settings-admin.php"><i class="fas fa-cog"></i> Settings</a>
  </nav>
  <a href="admin_logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main">

  <!-- TOPBAR -->
  <div class="topbar">
    <h1>Dashboard</h1>
    <div class="date"><i class="fas fa-calendar-alt"></i> <?php echo date("F d, Y"); ?></div>
  </div>

  <!-- STATS -->
  <div class="stats">
    <div class="stat-card">
      <div class="stat-icon"><i class="fas fa-shopping-bag"></i></div>
      <div class="stat-info"><p>Total Orders</p><h2><?php echo $total_orders; ?></h2></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon"><i class="fas fa-spa"></i></div>
      <div class="stat-info"><p>Products</p><h2><?php echo $total_products; ?></h2></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon"><i class="fas fa-users"></i></div>
      <div class="stat-info"><p>Customers</p><h2><?php echo $total_users; ?></h2></div>
    </div>
  </div>

  <!-- PRODUCTS + RECENT ORDERS -->
  <div class="grid-2">

    <!-- PRODUCTS CARD -->
    <div class="card">
      <div class="card-header">
        <h3>Products</h3>
        <a href="products-admin.php" class="view-all">Manage →</a>
      </div>
      <table>
        <thead><tr><th>Image</th><th>Name</th><th>Price</th></tr></thead>
        <tbody>
        <?php if(mysqli_num_rows($products_result) == 0): ?>
          <tr><td colspan="3" class="empty">No products yet</td></tr>
        <?php else: ?>
          <?php while($p = mysqli_fetch_assoc($products_result)): ?>
          <tr>
            <td><img src="images/<?php echo htmlspecialchars($p['image']); ?>" class="thumb" onerror="this.style.display='none'"></td>
            <td><?php echo htmlspecialchars($p['name']); ?></td>
            <td>&#8377;<?php echo number_format($p['price'], 2); ?></td>
          </tr>
          <?php endwhile; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- RECENT ORDERS CARD -->
    <div class="card">
      <div class="card-header">
        <h3>Recent Orders</h3>
        <a href="orders-admin.php" class="view-all">View All →</a>
      </div>
      <table>
        <thead><tr><th>Order ID</th><th>Customer</th><th>Amount</th><th>Payment</th></tr></thead>
        <tbody>
        <?php if(mysqli_num_rows($orders_result) == 0): ?>
          <tr><td colspan="4" class="empty">No orders yet</td></tr>
        <?php else: ?>
          <?php while($o = mysqli_fetch_assoc($orders_result)): ?>
          <tr>
            <td><?php echo htmlspecialchars($o['order_id']); ?></td>
            <td><?php echo htmlspecialchars($o['Customer']); ?></td>
            <td>&#8377;<?php echo number_format($o['amount'], 2); ?></td>
            <td><?php echo htmlspecialchars($o['payment_method']); ?></td>
          </tr>
          <?php endwhile; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>

</div>

</body>
</html>