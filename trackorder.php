<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id'])){
    $_SESSION['redirect_after_login'] = 'trackorder.php';
    header("Location: login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);
$orders = [];
$result = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC");
if($result) {
    while($row = mysqli_fetch_assoc($result)){
        // Dynamic Status Logic
        $created_time = strtotime($row['created_at']);
        $delivery_time = strtotime($row['delivery_time']);
        $current_time = time();

        $status = "Order Placed";
        $progress = 1;

        if($delivery_time) {
            if($current_time >= $delivery_time + (30 * 60)) {
                $status = "Delivered";
                $progress = 4;
            } else if($current_time >= $delivery_time - (10 * 60)) {
                $status = "Ready";
                $progress = 3;
            } else if($current_time >= $created_time + (30 * 60)) {
                $status = "Preparing";
                $progress = 2;
            }
        }

        $row['dynamic_status'] = $status;
        $row['progress'] = $progress;
        
        // Fetch order items
        $order_id = $row['id'];
        $items_result = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id = $order_id");
        $items = [];
        if($items_result) {
            while($item = mysqli_fetch_assoc($items_result)) {
                $items[] = $item;
            }
        }
        $row['items'] = $items;

        $orders[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BLOSSOM | Track Order</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family:'Poppins',sans-serif; background:linear-gradient(135deg,#fff8fb,#f6eef1); min-height:100vh; color: #333; }

/* NAVBAR */
nav { display:flex; justify-content:space-between; align-items:center; padding:16px 60px; background:rgba(255,255,255,0.97); box-shadow:0 2px 16px rgba(139,30,63,0.1); position:sticky; top:0; z-index:100; }
.logo { font-family:'Playfair Display',serif; font-size:24px; color:#8b1e3f; letter-spacing:2px; }
nav ul { list-style:none; display:flex; gap:28px; }
nav ul li a { text-decoration:none; color:#555; font-size:13px; font-weight:500; transition:color 0.3s; }
nav ul li a:hover, nav ul li a.active { color:#8b1e3f; }
.nav-btn { background:#8b1e3f; color:white; border:none; padding:9px 22px; border-radius:25px; font-size:13px; cursor:pointer; }
.nav-btn:hover { background: #6d1530; }

/* HEADER */
.page-header { background: url('images/bg2.jpeg') center/cover no-repeat; padding: 60px 20px; text-align: center; position: relative; }
.page-header::before { content: ''; position: absolute; inset: 0; background: rgba(255,255,255,0.7); }
.page-header h1 { position: relative; font-family:'Playfair Display',serif; color:#8b1e3f; font-size:40px; }
.page-header p { position: relative; color: #555; font-size: 15px; margin-top: 10px; }

/* MAIN CONTENT */
.container { max-width: 900px; margin: 40px auto; padding: 0 20px; }

.empty-orders { text-align: center; padding: 50px; background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(139,30,63,0.05); }
.empty-orders i { font-size: 50px; color: #e8c5ce; margin-bottom: 20px; }
.empty-orders h3 { font-family: 'Playfair Display', serif; color: #8b1e3f; font-size: 24px; margin-bottom: 10px; }
.empty-orders a { display: inline-block; margin-top: 20px; padding: 10px 25px; background: #8b1e3f; color: white; text-decoration: none; border-radius: 25px; font-weight: 500; transition: background 0.3s; }
.empty-orders a:hover { background: #6d1530; }

.order-card { background: white; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(139,30,63,0.06); border: 1px solid #f8eef2; transition: transform 0.3s, box-shadow 0.3s; }
.order-card:hover { transform: translateY(-3px); box-shadow: 0 15px 40px rgba(139,30,63,0.1); }

.order-header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px dashed #f0e0e6; padding-bottom: 20px; margin-bottom: 25px; flex-wrap: wrap; gap: 15px; }
.order-id { font-family: 'Playfair Display', serif; font-size: 20px; color: #8b1e3f; font-weight: 700; }
.order-date { font-size: 13px; color: #888; margin-top: 5px; }
.order-amount { font-size: 18px; font-weight: 600; color: #333; background: #fdf6f8; padding: 5px 15px; border-radius: 20px; border: 1px solid #f8eef2; }
.delivery-date { font-size: 14px; font-weight: 500; color: #555; background: #f0f4f8; padding: 5px 12px; border-radius: 8px; display: inline-block; margin-top: 10px;}

/* STEPPER */
.stepper { display: flex; justify-content: space-between; position: relative; margin-bottom: 30px; }
.stepper::before { content: ''; position: absolute; top: 20px; left: 10%; right: 10%; height: 3px; background: #f0e0e6; z-index: 1; }

.step { position: relative; z-index: 2; width: 25%; text-align: center; }
.step-icon { width: 44px; height: 44px; margin: 0 auto 10px; background: white; border: 3px solid #f0e0e6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #aaa; font-size: 16px; transition: all 0.3s; }
.step.active .step-icon { border-color: #8b1e3f; color: #8b1e3f; background: #fff; box-shadow: 0 0 0 5px #fdf0f4; }
.step.completed .step-icon { background: #8b1e3f; border-color: #8b1e3f; color: white; }
.step-label { font-size: 12px; font-weight: 500; color: #888; text-transform: uppercase; letter-spacing: 0.5px; }
.step.active .step-label { color: #8b1e3f; font-weight: 600; }
.step.completed .step-label { color: #333; }

/* ORDER DETAILS */
.order-details { margin-top: 25px; }
.order-details h4 { font-size: 14px; color: #8b1e3f; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
.item-list { list-style: none; }
.item-row { display: flex; justify-content: space-between; font-size: 14px; padding: 10px 0; border-bottom: 1px solid #f8eef2; }
.item-row:last-child { border-bottom: none; }
.item-name { font-weight: 500; color: #444; }
.item-name small { display: block; color: #888; font-size: 12px; font-weight: 400; margin-top: 3px; }
.item-price { font-weight: 500; color: #333; }

@media (max-width: 768px) {
    .stepper { flex-direction: column; align-items: flex-start; padding-left: 20px; }
    .stepper::before { top: 0; bottom: 0; left: 40px; right: auto; width: 3px; height: 100%; }
    .step { width: 100%; display: flex; align-items: center; text-align: left; margin-bottom: 20px; }
    .step-icon { margin: 0 20px 0 0; }
}
</style>
</head>
<body>

<nav>
  <div class="logo">BLOSSOM</div>
  <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="products.php">Shop</a></li>
    <li><a href="customize.php">Customize</a></li>
    <li><a href="cart.php">Cart</a></li>
    <li><a href="trackorder.php" class="active">Track Order</a></li>
  </ul>
  <button class="nav-btn" onclick="window.location.href='logout.php'">Logout</button>
</nav>

<div class="page-header">
    <h1>Track Your Orders</h1>
    <p>Monitor the progress of your beautiful blossoms.</p>
</div>

<div class="container">
    <?php if(empty($orders)): ?>
        <div class="empty-orders">
            <i class="fas fa-box-open"></i>
            <h3>No Orders Found</h3>
            <p>You haven't placed any orders yet. Once you do, you can track them here!</p>
            <a href="products.php">Shop Now</a>
        </div>
    <?php else: ?>
        <?php foreach($orders as $order): ?>
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <div class="order-id">Order #<?php echo htmlspecialchars($order['order_id']); ?></div>
                        <div class="order-date">Placed on: <?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?></div>
                        <?php if($order['delivery_time']): ?>
                            <div class="delivery-date"><i class="fas fa-calendar-alt" style="color:#8b1e3f;margin-right:5px;"></i> Delivery: <?php echo date('d M Y, h:i A', strtotime($order['delivery_time'])); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="order-amount">
                        &#8377;<?php echo number_format($order['amount'], 2); ?>
                    </div>
                </div>

                <div class="stepper">
                    <div class="step <?php echo $order['progress'] >= 1 ? ($order['progress'] > 1 ? 'completed' : 'active') : ''; ?>">
                        <div class="step-icon"><i class="fas fa-clipboard-check"></i></div>
                        <div class="step-label">Order Placed</div>
                    </div>
                    <div class="step <?php echo $order['progress'] >= 2 ? ($order['progress'] > 2 ? 'completed' : 'active') : ''; ?>">
                        <div class="step-icon"><i class="fas fa-seedling"></i></div>
                        <div class="step-label">Preparing</div>
                    </div>
                    <div class="step <?php echo $order['progress'] >= 3 ? ($order['progress'] > 3 ? 'completed' : 'active') : ''; ?>">
                        <div class="step-icon"><i class="fas fa-truck"></i></div>
                        <div class="step-label">Ready</div>
                    </div>
                    <div class="step <?php echo $order['progress'] >= 4 ? 'completed active' : ''; ?>">
                        <div class="step-icon"><i class="fas fa-home"></i></div>
                        <div class="step-label">Delivered</div>
                    </div>
                </div>

                <div class="order-details">
                    <h4>Order Summary</h4>
                    <ul class="item-list">
                        <?php if(!empty($order['bouquet_type']) && empty($order['items'])): ?>
                            <li class="item-row">
                                <div class="item-name"><?php echo htmlspecialchars($order['bouquet_type']); ?></div>
                            </li>
                        <?php endif; ?>

                        <?php foreach($order['items'] as $item): ?>
                            <li class="item-row">
                                <div class="item-name">
                                    <?php echo htmlspecialchars($item['product_name']); ?> x<?php echo $item['quantity']; ?>
                                    <?php 
                                        $extras = [];
                                        if(!empty($item['ribbon'])) $extras[] = "Ribbon: ".$item['ribbon'];
                                        if(!empty($item['wrap'])) $extras[] = "Wrap: ".$item['wrap'];
                                        if(!empty($item['message'])) $extras[] = "Message: ".$item['message'];
                                        if(!empty($extras)) {
                                            echo "<small>".implode(" | ", $extras)."</small>";
                                        }
                                    ?>
                                </div>
                                <div class="item-price">&#8377;<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                            </li>
                        <?php endforeach; ?>
                        
                        <?php if(!empty($order['items_json'])): 
                            $json_items = json_decode($order['items_json'], true);
                            if(is_array($json_items)):
                                foreach($json_items as $ji): ?>
                                    <li class="item-row">
                                        <div class="item-name"><?php echo htmlspecialchars($ji['name']) ?? 'Custom Item'; ?> x<?php echo isset($ji['qty']) ? $ji['qty'] : 1; ?></div>
                                    </li>
                                <?php endforeach;
                            endif;
                        endif; ?>

                        <?php if(!empty($order['payment_method'])): ?>
                            <li class="item-row" style="margin-top: 10px; border-top: 1px dotted #ccc; padding-top: 10px;">
                                <div class="item-name" style="color:#8b1e3f; font-weight:600;"><i class="fas fa-wallet" style="margin-right:5px;"></i> Payment Method</div>
                                <div class="item-price"><?php echo htmlspecialchars($order['payment_method']); ?></div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
