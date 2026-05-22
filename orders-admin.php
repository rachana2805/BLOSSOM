<?php
session_start();
include("db.php");

if(!isset($_SESSION['admin_logged_in'])){
    header("Location: admin.php");
    exit();
}

$result     = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");
$orderCount = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Orders | BLOSSOM Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root { --pink:#c75b6d; --darkpink:#8b1e3f; --lightpink:#f4ebee; --text:#3a1020; --muted:#a07080; }
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Poppins',sans-serif; background:var(--lightpink); color:var(--text); display:flex; min-height:100vh; }

/* SIDEBAR */
.sidebar { width:240px; background:linear-gradient(160deg,#8b1e3f,#c75b6d); display:flex; flex-direction:column; padding:30px 0; position:fixed; height:100vh; }
.sidebar .logo { font-family:'Playfair Display',serif; font-size:26px; color:white; text-align:center; padding-bottom:4px; }
.sidebar .admin-label { text-align:center; color:rgba(255,255,255,0.55); font-size:11px; letter-spacing:2px; text-transform:uppercase; margin-bottom:30px; }
.sidebar nav a { display:flex; align-items:center; gap:12px; padding:14px 30px; color:rgba(255,255,255,0.75); text-decoration:none; font-size:14px; border-left:3px solid transparent; transition:0.2s; }
.sidebar nav a:hover, .sidebar nav a.active { background:rgba(255,255,255,0.15); color:white; border-left:3px solid white; }
.sidebar .logout { margin-top:auto; padding:14px 30px; color:rgba(255,255,255,0.7); text-decoration:none; display:flex; align-items:center; gap:12px; font-size:14px; }

/* MAIN */
.main { margin-left:240px; flex:1; padding:40px; overflow-x:auto; }
.topbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; }
.topbar h1 { font-family:'Playfair Display',serif; color:var(--darkpink); font-size:28px; }
.badge { background:var(--lightpink); color:var(--darkpink); padding:4px 14px; border-radius:20px; font-size:12px; font-weight:500; }

/* CARD */
.card { background:white; border-radius:16px; padding:24px; box-shadow:0 4px 15px rgba(139,30,63,0.07); overflow-x:auto; }

/* TABLE */
table { width:100%; border-collapse:collapse; font-size:13px; min-width:860px; }
th { padding:10px 12px; background:#fdf0f4; color:var(--muted); text-transform:uppercase; font-size:11px; letter-spacing:0.5px; text-align:left; }
td { padding:12px 12px; border-bottom:1px solid #f5e6ea; color:#444; vertical-align:top; }
tr:last-child td { border-bottom:none; }
tr:hover td { background:#fffafc; }

/* ITEM LIST inside td */
.item-row { margin-bottom:6px; padding:6px 0; border-bottom:1px dashed #f0e0e6; }
.item-row:last-child { border-bottom:none; margin-bottom:0; }
.item-name { font-weight:500; color:#333; }
.item-meta { font-size:11px; color:#aaa; margin-top:3px; line-height:1.6; }
.item-meta span { display:inline-block; margin-right:8px; }

.empty-msg { text-align:center; padding:30px; color:var(--muted); }
</style>
</head>
<body>

<div class="sidebar">
  <div class="logo">BLOSSOM</div>
  <div class="admin-label">Admin Panel</div>
  <nav>
    <a href="dashboard.php"><i class="fas fa-th-large"></i> Dashboard</a>
    <a href="products-admin.php"><i class="fas fa-spa"></i> Products</a>
    <a href="orders-admin.php" class="active"><i class="fas fa-box"></i> Orders</a>
    <a href="settings-admin.php"><i class="fas fa-cog"></i> Settings</a>
  </nav>
  <a href="admin_logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main">
  <div class="topbar">
    <h1>Orders</h1>
    <span class="badge"><?php echo $orderCount; ?> order<?php echo $orderCount != 1 ? 's' : ''; ?></span>
  </div>

  <div class="card">
    <table>
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Customer Info</th>
          <th>Items &amp; Customization</th>
          <th>Amount</th>
          <th>Payment</th>
        </tr>
      </thead>
      <tbody>
      <?php if($orderCount == 0): ?>
        <tr><td colspan="5" class="empty-msg">No orders yet. Orders will appear here when customers checkout.</td></tr>
      <?php else: ?>
        <?php while($order = mysqli_fetch_assoc($result)):
              $oid = $order['id'];
              $items_q = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id = $oid");
        ?>
        <tr>
          <td><strong><?php echo htmlspecialchars($order['order_id']); ?></strong></td>
          <td>
            <strong><?php echo htmlspecialchars($order['Customer']); ?></strong><br>
            <?php if(!empty($order['email'])): ?>
              <span style="font-size:11px; color:#666; display:block; margin-top:2px;">
                <i class="fas fa-envelope" style="width:14px; text-align:center;"></i> <?php echo htmlspecialchars($order['email']); ?>
              </span>
            <?php endif; ?>
            <?php if(!empty($order['phone'])): ?>
              <span style="font-size:11px; color:#666; display:block; margin-top:2px;">
                <i class="fas fa-phone" style="width:14px; text-align:center;"></i> <?php echo htmlspecialchars($order['phone']); ?>
              </span>
            <?php endif; ?>
            <?php if(!empty($order['address'])): ?>
              <span style="font-size:11px; color:#8b1e3f; display:block; margin-top:2px; line-height:1.4;">
                <i class="fas fa-map-marker-alt" style="width:14px; text-align:center;"></i> <?php echo htmlspecialchars($order['address']); ?>
              </span>
            <?php endif; ?>
          </td>
          <td>
            <?php 
              // 1. Check for Polymorphic Link (item_id/item_type)
              if(!empty($order['item_id']) && !empty($order['item_type'])) {
                  if($order['item_type'] === 'custom') {
                      $custom_res = mysqli_query($conn, "SELECT * FROM items WHERE id = " . $order['item_id']);
                      if($custom_item = mysqli_fetch_assoc($custom_res)) {
                          echo "<div class='item-row'><div class='item-name'>✨ " . htmlspecialchars($custom_item['name']) . " <span style='font-weight:400; color:#888;'>(Custom)</span></div>";
                          if(!empty($order['custom_message'])) echo "<div class='item-meta'>💌 \"" . htmlspecialchars($order['custom_message']) . "\"</div>";
                          echo "</div>";
                      }
                  } else {
                      $prod_res = mysqli_query($conn, "SELECT * FROM products WHERE id = " . $order['item_id']);
                      if($prod_item = mysqli_fetch_assoc($prod_res)) {
                          echo "<div class='item-row'><div class='item-name'>📦 " . htmlspecialchars($prod_item['name']) . "</div></div>";
                      }
                  }
              }

              // 2. Fallback to order_items for detailed sub-items/legacy
              if(mysqli_num_rows($items_q) > 0): 
            ?>
              <?php while($item = mysqli_fetch_assoc($items_q)): ?>
              <div class="item-row">
                <div class="item-name">
                  <?php echo htmlspecialchars($item['product_name']); ?>
                  &nbsp;<span style="color:#c75b6d;font-weight:400;">(x<?php echo $item['quantity']; ?>)</span>
                </div>
                <div class="item-meta">
                  <?php if(!empty($item['ribbon'])): ?><span>🎀 <?php echo htmlspecialchars($item['ribbon']); ?></span><?php endif; ?>
                  <?php if(!empty($item['wrap'])): ?><span>🎁 <?php echo htmlspecialchars($item['wrap']); ?></span><?php endif; ?>
                  <?php if(!empty($item['message'])): ?><span>💌 "<?php echo htmlspecialchars($item['message']); ?>"</span><?php endif; ?>
                </div>
              </div>
              <?php endwhile; ?>
            <?php elseif(empty($order['item_id'])): ?>
              <span style="color:#ccc;">—</span>
            <?php endif; ?>
          </td>
          <td><strong>&#8377;<?php echo number_format($order['amount'], 2); ?></strong></td>
          <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
        </tr>
        <?php endwhile; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>