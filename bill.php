<?php
session_start();
include("db.php");

if(!isset($_GET['order_id'])){
    echo "<p style='text-align:center;padding:60px;font-family:Poppins,sans-serif;color:#c0392b;'>Invalid Access. <a href='index.php'>Go Home</a></p>";
    exit();
}

$order_id = (int)$_GET['order_id'];

// Fetch the order
$order_query = mysqli_query($conn, "SELECT * FROM orders WHERE id=$order_id");
$order = mysqli_fetch_assoc($order_query);

if(!$order){
    echo "<p style='text-align:center;padding:60px;font-family:Poppins,sans-serif;color:#c0392b;'>Order not found! <a href='index.php'>Go Home</a></p>";
    exit();
}

// Fetch order items directly from order_items (uses product_name, price, quantity)
$item_query = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id = $order_id");

$total = 0;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>BLOSSOM | Order Bill</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Poppins', sans-serif; background: #f6eef1; padding: 60px 20px; }

.bill-box {
  max-width: 700px; margin: auto; background: white;
  padding: 50px; border-radius: 20px;
  box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

h1 {
  text-align: center; color: #8b1e3f;
  font-family: 'Playfair Display', serif; font-size: 32px; margin-bottom: 6px;
}

.subtitle { text-align: center; color: #aaa; font-size: 14px; margin-bottom: 30px; }

.meta { font-size: 14px; margin-bottom: 20px; }
.meta p { margin-bottom: 6px; color: #555; }
.meta strong { color: #8b1e3f; }

hr { border: none; border-top: 1px solid #f0e0e6; margin: 20px 0; }

.item {
  display: flex; justify-content: space-between;
  padding: 10px 0; font-size: 14px; color: #444;
  border-bottom: 1px solid #f8eef2;
}

.total {
  display: flex; justify-content: space-between;
  margin-top: 24px; font-size: 18px; font-weight: 700; color: #8b1e3f;
}

.thankyou {
  text-align: center; margin-top: 30px; color: #27ae60;
  font-size: 16px; font-weight: 500;
}

.btn {
  display: block; width: fit-content; margin: 30px auto 0;
  padding: 12px 30px; background: #8b1e3f; color: white;
  border-radius: 25px; text-decoration: none; font-size: 14px;
  font-family: 'Poppins', sans-serif; font-weight: 500;
  transition: background 0.3s;
}
.btn:hover { background: #6d1530; }
</style>
</head>
<body>

<div class="bill-box">
  <h1>🌸 BLOSSOM</h1>
  <p class="subtitle">Order Confirmation</p>

  <div class="meta">
    <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?></p>
    <p><strong>Customer:</strong> <?php echo htmlspecialchars($order['Customer']); ?></p>
    <p><strong>Payment:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
  </div>

  <hr>

  <h3 style="font-family:'Playfair Display',serif;color:#8b1e3f;margin-bottom:12px;">Items Ordered</h3>

  <?php 
  // Standard Items from order_items table
  while($item = mysqli_fetch_assoc($item_query)):
    $is_delivery = (strpos($item['product_name'], 'Delivery:') === 0);
    $is_gift = (strpos($item['product_name'], 'Gift Collection:') === 0);
    $subtotal = $item['price'] * $item['quantity'];
  ?>
  <div class="item">
    <span>
      <strong style="color: #8b1e3f;"><?php echo htmlspecialchars($item['product_name']); ?></strong> 
      <?php if(!$is_delivery): ?>&times; <?php echo $item['quantity']; ?><?php endif; ?>
    </span>
    <span><?php echo $is_delivery ? '—' : '&#8377;'.number_format($subtotal, 2); ?></span>
  </div>

  <?php if($is_gift && !empty($order['items_json'])): 
    $json_items = json_decode($order['items_json'], true);
    if($json_items): ?>
    <div style="margin-left: 20px; font-size: 0.9em; color: #b03a5b; margin-bottom: 10px;">
        <?php foreach($json_items as $j_item): ?>
            <div style="display: flex; justify-content: space-between; padding: 2px 0;">
                <span>• <?php echo htmlspecialchars($j_item['name']); ?> &times; <?php echo $j_item['qty']; ?></span>
                <span>&#8377;<?php echo number_format($j_item['price'] * $j_item['qty'], 2); ?></span>
            </div>
        <?php endforeach; ?>
        <?php if(!empty($order['wrapping_type'])): ?>
            <div style="font-weight: 600; margin-top: 4px;">Wrap: <?php echo htmlspecialchars($order['wrapping_type']); ?></div>
        <?php endif; ?>
    </div>
  <?php endif; endif; ?>
  <?php endwhile; ?>

  <?php if (!empty($order['custom_message'])): ?>
  <div class="meta" style="margin-top:20px; background:#fff8fb; padding:15px; border-radius:10px; border:1px solid #f0e0e6;">
    <p><strong>Message:</strong><br>
    <?php echo nl2br(htmlspecialchars($order['custom_message'])); ?></p>
  </div>
  <?php endif; ?>

  <div class="total">
    <span>Grand Total</span>
    <span>&#8377;<?php echo number_format($order['amount'], 2); ?></span>
  </div>

  <div class="thankyou">
    ✅ Thank you for choosing BLOSSOM 🌸<br>
    Your order has been placed successfully!
  </div>

  <a href="products.php" class="btn">Continue Shopping</a>
</div>

</body>
</html>