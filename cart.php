<?php
session_start();
// ===== DATABASE CONNECTION =====
$conn = new mysqli("localhost", "root", "", "blossom_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* =====================================================
   REMOVE ITEM FROM CART
===================================================== */
if (isset($_GET['remove'])) {
    $id = intval($_GET['remove']);
    $conn->query("DELETE FROM cart WHERE id = $id");
    header("Location: cart.php");
    exit();
}

/* =====================================================
   CLEAR ALL ITEMS FROM CART
===================================================== */
if (isset($_GET['clear'])) {
    $conn->query("DELETE FROM cart");
    header("Location: cart.php");
    exit();
}

/* =====================================================
   UPDATE QUANTITY
===================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_qty'])) {
    $item_id = intval($_POST['item_id']);
    $quantity = intval($_POST['quantity']);
    
    if ($quantity > 0) {
        $conn->query("UPDATE cart SET quantity = $quantity WHERE id = $item_id");
    }
    
    header("Location: cart.php");
    exit();
}

/* =====================================================
   ADD ITEM TO CART (AJAX FROM products.php)
===================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_name'])) {

    header('Content-Type: application/json');

    $product_name = $conn->real_escape_string($_POST['product_name']);
    $price        = floatval($_POST['price']);
    $qty          = isset($_POST['qty']) ? intval($_POST['qty']) : 1;
    $items_json   = isset($_POST['items_json']) ? $conn->real_escape_string($_POST['items_json']) : '';
    $wrapping     = isset($_POST['wrapping_type']) ? $conn->real_escape_string($_POST['wrapping_type']) : '';

    // Check if product already exists (but for gift bouquets, usually they are unique if they have different items)
    $check_sql = "SELECT * FROM cart WHERE product='$product_name'";
    if (!empty($items_json)) {
        $check_sql .= " AND items_json='$items_json'";
    } else {
        $check_sql .= " AND (items_json='' OR items_json IS NULL)";
    }
    
    $check = $conn->query($check_sql);

    if ($check->num_rows > 0) {
        $conn->query("UPDATE cart 
                      SET quantity = quantity + $qty 
                      WHERE product='$product_name' AND (items_json='$items_json' OR (items_json IS NULL AND '$items_json'=''))");
    } else {
        $conn->query("INSERT INTO cart (product, price, quantity, items_json, wrapping_type) 
                      VALUES ('$product_name', $price, $qty, '$items_json', '$wrapping')");
    }

    echo json_encode(["success" => true]);
    exit(); 
}

$result = $conn->query("SELECT * FROM cart");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BLOSSOM | Your Cart</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">

<style>
/* ===== RESET & BASE ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: url("images/bg2.jpeg") no-repeat center center fixed;
    background-size: cover;
    min-height: 100vh;
}

/* ===== NAVBAR ===== */
nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 60px;
    background: rgba(255, 255, 255, 0.97);
    box-shadow: 0 2px 20px rgba(139, 30, 63, 0.12);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.logo {
    font-family: 'Playfair Display', serif;
    font-size: 26px;
    font-weight: 700;
    color: #8b1e3f;
    letter-spacing: 2px;
}

nav ul {
    list-style: none;
    display: flex;
    gap: 35px;
    margin: 0;
    padding: 0;
}

nav ul li a {
    text-decoration: none;
    color: #555;
    font-size: 14px;
    font-weight: 500;
    transition: color 0.3s;
    letter-spacing: 0.5px;
}

nav ul li a:hover,
nav ul li a.active {
    color: #8b1e3f;
    font-weight: 600;
}

.primary-btn {
    background: #b03a5b;
    color: white;
    border: none;
    padding: 10px 25px;
    border-radius: 30px;
    font-weight: 500;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.3s;
}

.primary-btn:hover {
    background: #8b1e3f;
}

/* ===== PAGE HEADING ===== */
h1 {
    text-align: center;
    font-family: 'Playfair Display', serif;
    color: #8b1e3f;
    font-size: 38px;
    margin: 50px 0 10px;
    text-shadow: 0 1px 4px rgba(255, 255, 255, 0.7);
}

.cart-subtitle {
    text-align: center;
    color: #888;
    font-size: 14px;
    margin-bottom: 35px;
}

/* ===== CART CONTAINER ===== */
.cart-container {
    max-width: 1050px;
    margin: 0 auto 60px;
    background: white;
    padding: 40px;
    border-radius: 25px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
}

/* ===== TABLE ===== */
table {
    width: 100%;
    border-collapse: collapse;
}

th {
    text-align: left;
    padding: 14px 16px;
    background: #f8eef2;
    color: #8b1e3f;
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

td {
    padding: 16px;
    border-bottom: 1px solid #f0e0e6;
    font-size: 14px;
    color: #444;
    vertical-align: middle;
}

tbody tr:last-child td {
    border-bottom: none;
}

tbody tr:hover td {
    background: #fdf6f8;
}

td small {
    color: #aaa;
    font-size: 12px;
    display: block;
    margin-top: 5px;
    line-height: 1.7;
}

/* ===== QUANTITY FORM ===== */
.qty-form {
    display: flex;
    align-items: center;
    gap: 6px;
}

.qty-form input[type="number"] {
    width: 60px;
    padding: 6px 10px;
    border: 1px solid #e0c8d0;
    border-radius: 10px;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    color: #444;
    text-align: center;
    outline: none;
    transition: border 0.3s;
}

.qty-form input[type="number"]:focus {
    border-color: #b03a5b;
}

.qty-update-btn {
    padding: 6px 12px;
    border: none;
    background: #f0e0e6;
    color: #8b1e3f;
    border-radius: 10px;
    font-size: 12px;
    font-family: 'Poppins', sans-serif;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.3s;
}

.qty-update-btn:hover {
    background: #e0c0cc;
}

/* ===== REMOVE BUTTON ===== */
.remove-btn {
    padding: 7px 16px;
    border: none;
    background: #fdeaed;
    color: #c75b6d;
    border-radius: 20px;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    font-weight: 500;
    transition: 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.remove-btn:hover {
    background: #c75b6d;
    color: white;
}

/* ===== CART FOOTER ===== */
.cart-footer {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #f8eef2;
    flex-wrap: wrap;
    gap: 20px;
}

.total-section {
    font-size: 22px;
    font-weight: 700;
    color: #8b1e3f;
}

.total-section span {
    font-size: 13px;
    font-weight: 400;
    color: #aaa;
    display: block;
    margin-bottom: 4px;
}

.cart-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    justify-content: flex-end;
    align-items: center;
}

.clear-btn {
    padding: 11px 22px;
    border: 2px solid #e0c0cc;
    background: white;
    color: #c75b6d;
    border-radius: 25px;
    font-size: 14px;
    font-family: 'Poppins', sans-serif;
    font-weight: 500;
    cursor: pointer;
    transition: 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.clear-btn:hover {
    background: #fdeaed;
    border-color: #c75b6d;
}

.continue-btn {
    padding: 11px 22px;
    border: 2px solid #b03a5b;
    background: white;
    color: #b03a5b;
    border-radius: 25px;
    font-size: 14px;
    font-family: 'Poppins', sans-serif;
    font-weight: 500;
    cursor: pointer;
    transition: 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.continue-btn:hover {
    background: #fdf0f4;
}

.checkout-btn {
    padding: 12px 30px;
    border: none;
    background: #8b1e3f;
    color: white;
    border-radius: 25px;
    font-size: 15px;
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.checkout-btn:hover {
    background: #6d1530;
    transform: scale(1.04);
}

/* ===== EMPTY CART ===== */
.empty-cart {
    text-align: center;
    padding: 70px 20px;
}

.empty-cart i {
    font-size: 64px;
    color: #e8c5ce;
    margin-bottom: 20px;
    display: block;
}

.empty-cart p {
    color: #aaa;
    font-size: 16px;
    margin-bottom: 25px;
}

.empty-cart a {
    display: inline-block;
    padding: 12px 30px;
    background: #8b1e3f;
    color: white;
    border-radius: 25px;
    text-decoration: none;
    font-size: 15px;
    font-weight: 500;
    transition: background 0.3s;
}

.empty-cart a:hover {
    background: #6d1530;
}

/* ===== CONTACT SECTION ===== */
.contact-section {
    background: #8b1e3f;
    padding: 60px 20px;
    text-align: center;
}

.contact-box {
    max-width: 500px;
    margin: auto;
    color: white;
}

.contact-box h2 {
    font-family: 'Playfair Display', serif;
    font-size: 30px;
    margin-bottom: 10px;
}

.contact-box .subtitle {
    font-size: 14px;
    opacity: 0.85;
    margin-bottom: 30px;
}

.contact-item {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-bottom: 15px;
    font-size: 15px;
}

.contact-item i {
    font-size: 16px;
    opacity: 0.9;
}

.social-icons {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 30px;
}

.social-icons .icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: white;
    transition: transform 0.3s, opacity 0.3s;
    text-decoration: none;
}

.social-icons .icon:hover {
    transform: scale(1.15);
    opacity: 0.85;
}

.social-icons .instagram {
    background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
}

.social-icons .whatsapp {
    background: #25D366;
}

footer {
    text-align: center;
    padding: 20px;
    background: #6d1530;
    color: white;
    font-size: 13px;
}
</style>
</head>

<body>

<!-- ===== NAVBAR ===== -->
<nav>
    <div class="logo">BLOSSOM</div>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="products.php">Shop</a></li>
        <li><a href="customize.php">Customize</a></li>
        <li><a href="cart.php" class="active">Cart</a></li>
        <li><a href="trackorder.php">Track Order</a></li>
        <li><a href="#contact">Contact</a></li>
    </ul>
    <?php if(isset($_SESSION['user_id'])): ?>
      <span style="font-size:13px;color:#8b1e3f;margin-right:10px;">Hi, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
      <button onclick="window.location.href='logout.php'" class="primary-btn">Logout</button>
    <?php else: ?>
      <button onclick="window.location.href='login.php'" class="primary-btn">Login</button>
    <?php endif; ?>
</nav>

<!-- ===== PAGE HEADING ===== -->
<h1>Your Cart</h1>
<p class="cart-subtitle">Review your items before placing an order</p>

<!-- ===== CART TABLE ===== -->
<div class="cart-container">

<?php if ($result && $result->num_rows > 0): ?>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $Total = 0;

        while ($row = $result->fetch_assoc()):
            $is_delivery = (strpos($row['product'], 'Delivery:') === 0);
            $total = $row['price'] * $row['quantity'];
            $Total += $total;
        ?>
            <tr>
                <td>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($row['product']); ?></h3>
                        <?php if(!empty($row['items_json'])): 
                            $details = json_decode($row['items_json'], true);
                            if($details): ?>
                            <ul style="list-style: none; font-size: 0.85rem; color: #b03a5b; margin-top: 5px; padding-left: 0;">
                                <?php foreach($details as $det): ?>
                                    <li>• <?php echo htmlspecialchars($det['name']); ?> &times; <?php echo $det['qty']; ?></li>
                                <?php endforeach; ?>
                                <?php if(!empty($row['wrapping_type'])): ?>
                                    <li style="font-weight:600;">Wrap: <?php echo htmlspecialchars($row['wrapping_type']); ?></li>
                                <?php endif; ?>
                            </ul>
                        <?php endif; endif; ?>
                    </div>
                    <?php if (!$is_delivery): ?>
                    <small>
                        🎀 Ribbon: <?php echo htmlspecialchars($row['ribbon'] ?? '') ?: "—"; ?> &nbsp;|&nbsp;
                        🎁 Wrap: <?php echo htmlspecialchars($row['wrap'] ?? '') ?: "—"; ?> &nbsp;|&nbsp;
                        💌 Message: <?php echo htmlspecialchars($row['message'] ?? '') ?: "—"; ?>
                    </small>
                    <?php endif; ?>
                </td>

                <td><?php echo $is_delivery ? '—' : '₹'.number_format($row['price'], 2); ?></td>

                <td>
                    <?php if ($is_delivery): ?>
                        —
                    <?php else: ?>
                    <!-- Inline quantity update form -->
                    <form method="POST" action="cart.php" class="qty-form">
                        <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                        <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="1" max="99">
                        <button type="submit" name="update_qty" class="qty-update-btn">Update</button>
                    </form>
                    <?php endif; ?>
                </td>

                <td><strong><?php echo $is_delivery ? '—' : '₹'.number_format($total, 2); ?></strong></td>

                <td>
                    <a href="cart.php?remove=<?php echo $row['id']; ?>"
                       class="remove-btn"
                       onclick="return confirm('Remove this item from your cart?')">
                        <i class="fas fa-trash-alt"></i> Remove
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Footer: total + action buttons -->
    <div class="cart-footer">
        <div class="total-section">
            <span>Grand Total</span>
            ₹<?php echo number_format($Total, 2); ?>
        </div>

        <div class="cart-actions">
            <a href="cart.php?clear=1"
               class="clear-btn"
               onclick="return confirm('Clear all items from your cart?')">
                <i class="fas fa-trash"></i> Clear Cart
            </a>
            
            <a href="products.php" class="continue-btn">
                <i class="fas fa-arrow-left"></i> Continue Shopping
            </a>

            <a href="checkout.php" class="checkout-btn">
                <i class="fas fa-arrow-right"></i> Proceed to Checkout
            </a>
        </div>
    </div>

<?php else: ?>

    <!-- Empty state -->
    <div class="empty-cart">
        <i class="fas fa-shopping-basket"></i>
        <p>Your cart is empty. Browse our beautiful bouquets!</p>
        <a href="products.php">Shop Now</a>
    </div>

<?php endif; ?>

</div>

<!-- ===== CONTACT SECTION ===== -->
<section id="contact" class="contact-section">
    <div class="contact-box">
        <h2>Contact Blossom</h2>
        <p class="subtitle">We'd love to hear from you.</p>

        <div class="contact-item">
            <i class="fas fa-map-marker-alt"></i>
            <span>Mangalore, Karnataka</span>
        </div>
        <div class="contact-item">
            <i class="fas fa-phone"></i>
            <span>+91 97392 96978</span>
        </div>
        <div class="contact-item">
            <i class="fas fa-envelope"></i>
            <span><em>blossoms@gmail.com</em></span>
        </div>

        <div class="social-icons">
            <a href="https://www.instagram.com/blossoms_mangalore" target="_blank" class="icon instagram">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://wa.me/919739296978" target="_blank" class="icon whatsapp">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>
    </div>
</section>

<footer>
    © 2026 BLOSSOM | Developed by Rachana K S, Shivani Gatty, Neha Rai
</footer>

</body>
</html>

<?php 
// Close database connection
$conn->close();
?>
