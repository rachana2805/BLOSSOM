<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id'])){
    $_SESSION['redirect_after_login'] = 'checkout.php';
    header("Location: login.php");
    exit();
}

$total = 0;
$cart_items = [];

$result = mysqli_query($conn, "SELECT * FROM cart");
while($row = mysqli_fetch_assoc($result)){
    $cart_items[] = $row;
    $total += $row['price'] * $row['quantity'];
}

if(empty($cart_items)){
    echo "<p style='text-align:center;padding:60px;font-family:Poppins,sans-serif;'>Your cart is empty! <a href='products.php'>Shop Now</a></p>";
    exit();
}

if(isset($_POST['place_order'])){
    $customer = $conn->real_escape_string($_SESSION['user_name'] ?? 'Guest');
    $email    = $conn->real_escape_string($_POST['email'] ?? '');
    $phone    = $conn->real_escape_string($_POST['phone'] ?? '');
    $address  = $conn->real_escape_string($_POST['address'] ?? '');
    $payment  = $conn->real_escape_string($_POST['payment'] ?? 'Cash on Delivery');
    $order_id_str = "ORD" . rand(1000, 9999);

    // Detect gift bouquet metadata from cart items and determine item_id
    $final_items_json = "";
    $final_btype = "";
    $final_wtype = "";
    $final_msg = "";
    $final_item_id = 'NULL';
    $final_item_type = 'NULL';
    
    foreach($cart_items as $item){
        if(!empty($item['items_json'])){
            $final_items_json = $conn->real_escape_string($item['items_json']);
            $final_btype = $conn->real_escape_string($item['product']);
            $final_wtype = $conn->real_escape_string($item['wrapping_type'] ?? '');
            
            // Insert into items table as requested
            $cbtype = $conn->real_escape_string($item['product']);
            $cprice = floatval($item['price']);
            $cdetails = $conn->real_escape_string(json_encode([
                'wrapping_type' => $item['wrapping_type'] ?? '',
                'parts' => json_decode($item['items_json'], true)
            ]));
            
            $result_item = mysqli_query($conn, "INSERT INTO items (name, price, customization_details) VALUES ('$cbtype', $cprice, '$cdetails')");
            if ($result_item) {
                $final_item_id = mysqli_insert_id($conn);
                $final_item_type = "'custom'";
            }
        } else {
            // Check products table for normal product
            if ($final_item_id === 'NULL') {
                $p_name = $conn->real_escape_string($item['product']);
                $p_res = mysqli_query($conn, "SELECT id FROM products WHERE name='$p_name' LIMIT 1");
                if ($p_res && mysqli_num_rows($p_res) > 0) {
                    $p_row = mysqli_fetch_assoc($p_res);
                    $final_item_id = $p_row['id'];
                    $final_item_type = "'product'";
                }
            }
        }
        if(!empty($item['message'])) $final_msg .= $item['message'] . " ";
    }
    $final_msg = $conn->real_escape_string(trim($final_msg));

    $delivery_time = $conn->real_escape_string($_POST['delivery_time']);
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 'NULL';

    // 1. Insert into orders
    $sql_order = "INSERT INTO orders (user_id, order_id, item_id, item_type, Customer, email, phone, address, amount, payment_method, items_json, bouquet_type, wrapping_type, custom_message, delivery_time, order_status)
                  VALUES ($user_id, '$order_id_str', $final_item_id, $final_item_type, '$customer', '$email', '$phone', '$address', $total, '$payment', '$final_items_json', '$final_btype', '$final_wtype', '$final_msg', '$delivery_time', 'Order Placed')";

    if(mysqli_query($conn, $sql_order)){
        $insert_id = mysqli_insert_id($conn);

        // 2. Insert each cart item into order_items
        foreach($cart_items as $item){
            $prod  = $conn->real_escape_string($item['product']);
            $price = floatval($item['price']);
            $qty   = intval($item['quantity']);
            $rib   = $conn->real_escape_string($item['ribbon'] ?? '');
            $wrap  = $conn->real_escape_string($item['wrap'] ?? '');
            $msg   = $conn->real_escape_string($item['message'] ?? '');

            $sql_item = "INSERT INTO order_items (order_id, product_name, price, quantity, ribbon, wrap, message)
                         VALUES ($insert_id, '$prod', $price, $qty, '$rib', '$wrap', '$msg')";
            mysqli_query($conn, $sql_item);
        }

        // 3. Clear cart
        mysqli_query($conn, "DELETE FROM cart");

        header("Location: bill.php?order_id=" . $insert_id);
        exit();
    } else {
        $order_error = "Error placing order: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BLOSSOM | Checkout</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family:'Poppins',sans-serif; background:linear-gradient(135deg,#fff8fb,#f6eef1); min-height:100vh; }

/* NAVBAR */
nav { display:flex; justify-content:space-between; align-items:center; padding:16px 60px; background:rgba(255,255,255,0.97); box-shadow:0 2px 16px rgba(139,30,63,0.1); position:sticky; top:0; z-index:100; }
.logo { font-family:'Playfair Display',serif; font-size:24px; color:#8b1e3f; letter-spacing:2px; }
nav ul { list-style:none; display:flex; gap:28px; }
nav ul li a { text-decoration:none; color:#555; font-size:13px; font-weight:500; }
nav ul li a:hover { color:#8b1e3f; }
.nav-btn { background:#8b1e3f; color:white; border:none; padding:9px 22px; border-radius:25px; font-size:13px; cursor:pointer; }

/* PAGE */
.page-wrap { max-width:1080px; margin:50px auto; padding:0 20px; display:grid; grid-template-columns:1.1fr 1fr; gap:36px; }
h1 { text-align:center; font-family:'Playfair Display',serif; color:#8b1e3f; font-size:34px; margin-bottom:40px; }

/* BOXES */
.box { background:white; padding:36px; border-radius:20px; box-shadow:0 10px 35px rgba(139,30,63,0.08); }
.box h2 { font-family:'Playfair Display',serif; color:#8b1e3f; font-size:20px; margin-bottom:22px; padding-bottom:12px; border-bottom:1px solid #f0e0e6; }

/* INPUTS */
.input-group { margin-bottom:14px; }
.input-group label { display:block; font-size:12px; font-weight:600; color:#8b1e3f; text-transform:uppercase; letter-spacing:0.4px; margin-bottom:5px; }
.input-group input { width:100%; padding:12px 14px; border:1.5px solid #e8d0d8; border-radius:10px; font-family:'Poppins',sans-serif; font-size:14px; outline:none; transition:border-color 0.25s; }
.input-group input:focus { border-color:#8b1e3f; }

/* PAYMENT */
.payment-opts { display:flex; flex-direction:column; gap:12px; margin-bottom:22px; }
.popt { display:flex; align-items:center; gap:10px; padding:13px 16px; border:1.5px solid #e8d0d8; border-radius:10px; cursor:pointer; font-size:14px; transition:border-color 0.2s; }
.popt input { width:auto; margin:0; }
.popt:has(input:checked) { border-color:#8b1e3f; background:#fdf0f4; }

.place-btn { width:100%; padding:15px; border:none; background:linear-gradient(to right,#8b1e3f,#c75b6d); color:white; border-radius:30px; font-size:15px; font-family:'Poppins',sans-serif; font-weight:600; cursor:pointer; transition:opacity 0.2s; margin-top:6px; }
.place-btn:hover { opacity:0.9; }

/* ORDER SUMMARY */
.summary-item { display:flex; justify-content:space-between; align-items:flex-start; padding:12px 0; border-bottom:1px solid #f8eef2; gap:10px; }
.summary-item:last-of-type { border-bottom:none; }
.item-name { font-size:13px; font-weight:500; color:#333; }
.item-custom { font-size:11px; color:#aaa; margin-top:3px; }
.item-price { font-size:13px; font-weight:600; color:#8b1e3f; white-space:nowrap; }
.qty-badge { display:inline-block; background:#f0e8ed; color:#8b1e3f; border-radius:10px; padding:1px 8px; font-size:11px; margin-left:6px; }

.summary-total { display:flex; justify-content:space-between; margin-top:20px; padding-top:16px; border-top:2px solid #f0e0e6; }
.summary-total span { font-size:16px; font-weight:700; color:#8b1e3f; }

.alert-error { background:#fdeaed; color:#c0392b; padding:12px 16px; border-radius:10px; font-size:13px; margin-bottom:18px; }
</style>
</head>
<body>

<!-- NAVBAR -->
<nav>
  <div class="logo">BLOSSOM</div>
  <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="products.php">Shop</a></li>
    <li><a href="customize.php">Customize</a></li>
    <li><a href="cart.php">Cart</a></li>
    <li><a href="trackorder.php">Track Order</a></li>
  </ul>
  <?php if(isset($_SESSION['user_id'])): ?>
    <button class="nav-btn" onclick="window.location.href='logout.php'">Logout</button>
  <?php else: ?>
    <button class="nav-btn" onclick="window.location.href='login.php'">Login</button>
  <?php endif; ?>
</nav>

<h1>Secure Checkout</h1>

<div class="page-wrap">

  <!-- LEFT: FORM -->
  <div class="box">
    <?php if(isset($order_error)): ?>
      <div class="alert-error"><?php echo $order_error; ?></div>
    <?php endif; ?>

    <form method="POST" action="checkout.php">

      <h2><i class="fas fa-user" style="font-size:16px;margin-right:8px;"></i>Customer Details</h2>

      <div class="input-group">
        <label>Full Name</label>
        <input type="text" name="name" placeholder="Your name" value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>" required>
      </div>
      <div class="input-group">
        <label>Email</label>
        <input type="email" name="email" placeholder="your@email.com" required>
      </div>
      <div class="input-group">
        <label>Delivery Address</label>
        <input type="text" name="address" placeholder="Street, City, PIN" required>
      </div>
      <div class="input-group">
        <label>Phone Number</label>
        <input type="tel" name="phone" placeholder="+91 XXXXX XXXXX" required>
      </div>
      <div class="input-group">
        <label>Delivery Date & Time</label>
        <input type="datetime-local" name="delivery_time" required>
      </div>

      <h2 style="margin-top:28px;"><i class="fas fa-credit-card" style="font-size:16px;margin-right:8px;"></i>Payment Method</h2>

      <div class="payment-opts">
        <label class="popt"><input type="radio" name="payment" value="Cash on Delivery" checked> <i class="fas fa-money-bill-wave" style="color:#8b1e3f;"></i> &nbsp;Cash on Delivery</label>
      </div>

      <button type="submit" name="place_order" class="place-btn">
        <i class="fas fa-check-circle"></i> &nbsp;Place Order &mdash; &#8377;<?php echo number_format($total, 2); ?>
      </button>

    </form>
  </div>

  <!-- RIGHT: ORDER SUMMARY -->
  <div class="box">
    <h2><i class="fas fa-shopping-basket" style="font-size:16px;margin-right:8px;"></i>Order Summary</h2>

    <?php foreach($cart_items as $item):
      $is_delivery = (strpos($item['product'], 'Delivery:') === 0);
      $subtotal = $item['price'] * $item['quantity'];
    ?>
    <div class="summary-item">
      <div>
        <div class="item-name">
          <?php echo htmlspecialchars($item['product']); ?>
          <?php if(!$is_delivery): ?><span class="qty-badge">x<?php echo $item['quantity']; ?></span><?php endif; ?>
        </div>
        <?php if(!$is_delivery && (!empty($item['ribbon']) || !empty($item['wrap']) || !empty($item['message']))): ?>
        <div class="item-custom">
          <?php if(!empty($item['ribbon'])): ?>🎀 <?php echo htmlspecialchars($item['ribbon']); ?> &nbsp;<?php endif; ?>
          <?php if(!empty($item['wrap'])): ?>🎁 <?php echo htmlspecialchars($item['wrap']); ?> &nbsp;<?php endif; ?>
          <?php if(!empty($item['message'])): ?>💌 "<?php echo htmlspecialchars($item['message']); ?>"<?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
      <div class="item-price"><?php echo $is_delivery ? '—' : '&#8377;'.number_format($subtotal, 2); ?></div>
    </div>
    <?php endforeach; ?>

    <div class="summary-total">
      <span>Grand Total</span>
      <span>&#8377;<?php echo number_format($total, 2); ?></span>
    </div>
  </div>

</div>

</body>
</html>