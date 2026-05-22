<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<title>BLOSSOM | Products</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">

<style>
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
    background: rgba(255, 255, 255, 0.95);
    box-shadow: 0 2px 20px rgba(139, 30, 63, 0.1);
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
}

nav ul li a {
    text-decoration: none;
    color: #555;
    font-size: 14px;
    font-weight: 500;
    transition: color 0.3s;
}

nav ul li a:hover {
    color: #8b1e3f;
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
    transition: 0.3s;
}

.primary-btn:hover {
    background: #8b1e3f;
}

/* ===== OVERLAY ===== */
.overlay {
    background: rgba(255, 255, 255, 0.88);
    min-height: calc(100vh - 70px);
    padding: 50px 40px;
}

h1 {
    font-family: 'Playfair Display', serif;
    text-align: center;
    color: #8b1e3f;
    font-size: 36px;
    margin-bottom: 10px;
}

/* ===== TOAST NOTIFICATION ===== */
.toast {
    display: none;
    position: fixed;
    top: 90px;
    right: 30px;
    background: #8b1e3f;
    color: white;
    padding: 14px 24px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 500;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    z-index: 9999;
    animation: fadeInOut 2.5s ease forwards;
}

@keyframes fadeInOut {
    0%   { opacity: 0; transform: translateY(-10px); }
    15%  { opacity: 1; transform: translateY(0); }
    75%  { opacity: 1; }
    100% { opacity: 0; transform: translateY(-10px); }
}

/* ===== PRODUCTS GRID ===== */
.products {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-top: 50px;
    max-width: 1100px;
    margin-left: auto;
    margin-right: auto;
}

.product {
    background: white;
    padding: 25px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(139, 30, 63, 0.08);
    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s;
    position: relative;
    border: 1px solid rgba(139, 30, 63, 0.05);
    animation: floating 5s ease-in-out infinite;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

@keyframes floating {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-12px); }
    100% { transform: translateY(0px); }
}

.product:hover {
    transform: translateY(-15px) scale(1.02);
    box-shadow: 0 20px 40px rgba(139, 30, 63, 0.15);
}

.product img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-radius: 15px;
    margin-bottom: 18px;
}

.product h3 {
    font-family: 'Playfair Display', serif;
    color: #8b1e3f;
    margin-bottom: 8px;
    font-size: 22px;
}

.product .price {
    color: #b03a5b;
    font-weight: 600;
    font-size: 18px;
    margin-bottom: 10px;
}

.stock-status {
    font-size: 12px;
    font-weight: 500;
    margin-bottom: 15px;
    padding: 4px 12px;
    border-radius: 20px;
    display: inline-block;
}
.in-stock { background: #e6f7ed; color: #2b9348; }
.low-stock { background: #fff4e6; color: #f39c12; }
.out-of-stock { background: #fdeaed; color: #e74c3c; opacity: 0.6; }

.select-group {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px dashed #eee;
}

.custom-checkbox {
    width: 20px;
    height: 20px;
    accent-color: #8b1e3f;
    cursor: pointer;
}

.qty-input {
    width: 60px;
    padding: 6px;
    border: 1px solid #ddd;
    border-radius: 8px;
    text-align: center;
    font-size: 14px;
}

.qty-input:disabled {
    background: #f9f9f9;
    color: #ccc;
    cursor: not-allowed;
}

.total-sticky {
    position: fixed;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    background: white;
    padding: 15px 40px;
    border-radius: 40px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    z-index: 1001;
    display: none;
    align-items: center;
    gap: 25px;
    border: 1px solid #8b1e3f;
    animation: slideUp 0.5s ease-out;
}

@keyframes slideUp {
    from { transform: translate(-50%, 100px); opacity: 0; }
    to { transform: translate(-50%, 0); opacity: 1; }
}

.total-text { font-weight: 600; color: #8b1e3f; font-size: 18px; }

.bulk-add-btn {
    background: #8b1e3f;
    color: white;
    border: none;
    padding: 10px 25px;
    border-radius: 25px;
    font-weight: 500;
    cursor: pointer;
    transition: 0.3s;
}
.bulk-add-btn:hover { background: #b03a5b; }

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
    text-decoration: none;
    transition: transform 0.3s, opacity 0.3s;
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

<!-- Toast Notification -->
<div class="toast" id="toast">🛒 Added to cart!</div>

<!-- ===== NAVBAR ===== -->
<nav>
    <div class="logo">BLOSSOM</div>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="products.php">Shop</a></li>
        <li><a href="customize.php">Customize</a></li>
        <li><a href="cart.php">Cart</a></li>
        <li><a href="trackorder.php">Track Order</a></li>
        <li><a href="#contact">Contact</a></li>
    </ul>
    <button onclick="window.location.href='<?php echo isset($_SESSION['user_id']) ? 'logout.php' : 'login.php'; ?>'" class="primary-btn">
        <?php echo isset($_SESSION['user_id']) ? 'Logout' : 'Login'; ?>
    </button>
</nav>

<!-- ===== PRODUCTS ===== -->
<div class="overlay">
    <h1>Our Products</h1>

    <div class="products">

<?php
include("db.php");
$prod_result = mysqli_query($conn, "SELECT * FROM products ORDER BY id ASC");
if(mysqli_num_rows($prod_result) == 0):
?>
    <p style="text-align:center;color:#aaa;padding:40px;">No products available yet.</p>
<?php else: ?>
    <?php while($p = mysqli_fetch_assoc($prod_result)): 
        $stock = intval($p['stock']);
        $status_class = "in-stock";
        $status_text = "In Stock";
        if($stock <= 0) {
            $status_class = "out-of-stock";
            $status_text = "Out of Stock";
        } elseif($stock <= 5) {
            $status_class = "low-stock";
            $status_text = "Only $stock left";
        }
    ?>
        <div class="product <?php echo $status_class; ?>" style="animation-delay: <?php echo rand(0, 1000) / 1000; ?>s;">
            <div>
                <img src="images/<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" onerror="this.onerror=null; this.src='https://via.placeholder.com/300x250/fdf0f4/8b1e3f?text=BLOSSOM';">
                <h3><?php echo htmlspecialchars($p['name']); ?></h3>
                <p class="price">₹<?php echo number_format($p['price'], 2); ?></p>
                <span class="stock-status <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
            </div>
            
            <div class="select-group">
                <input type="checkbox" 
                       class="custom-checkbox prod-check" 
                       data-id="<?php echo $p['id']; ?>" 
                       data-name="<?php echo addslashes($p['name']); ?>" 
                       data-price="<?php echo $p['price']; ?>"
                       onchange="updateSelection(this)" 
                       <?php echo ($stock <= 0) ? 'disabled' : ''; ?>>
                
                <input type="number" 
                       class="qty-input prod-qty" 
                       id="qty-<?php echo $p['id']; ?>" 
                       min="1" 
                       max="<?php echo $stock; ?>" 
                       value="1" 
                       onchange="calculateProductsTotal()" 
                       disabled>
            </div>
        </div>
    <?php endwhile; ?>
<?php endif; ?>

    </div><!-- /.products -->
</div><!-- /.overlay -->

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
            <span>blossoms@gmail.com</span>
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

<!-- Sticky Total Bar -->
<div id="stickyTotal" class="total-sticky">
    <div class="total-text">Total: ₹<span id="grandTotal">0</span></div>
    <button class="bulk-add-btn" onclick="addSelectedToCart()">Add Selected to Cart</button>
</div>

<script>
function updateSelection(checkbox) {
    const qtyInput = checkbox.parentElement.querySelector('.qty-input');
    qtyInput.disabled = !checkbox.checked;
    if(!checkbox.checked) qtyInput.value = 1;
    calculateProductsTotal();
}

function calculateProductsTotal() {
    const checks = document.querySelectorAll('.prod-check');
    const sticky = document.getElementById('stickyTotal');
    let total = 0;
    let anyChecked = false;

    checks.forEach(chk => {
        if(chk.checked) {
            anyChecked = true;
            const qty = parseInt(chk.parentElement.querySelector('.qty-input').value) || 0;
            const price = parseFloat(chk.dataset.price);
            total += qty * price;
        }
    });

    document.getElementById('grandTotal').innerText = total.toLocaleString('en-IN', {minimumFractionDigits: 2});
    sticky.style.display = anyChecked ? 'flex' : 'none';
}

function addSelectedToCart() {
    const checks = document.querySelectorAll('.prod-check');
    let itemsAdded = 0;
    let itemsToProcess = [];

    checks.forEach(chk => {
        if(chk.checked) {
            const qty = parseInt(chk.parentElement.querySelector('.qty-input').value);
            itemsToProcess.push({
                name: chk.dataset.name,
                price: parseFloat(chk.dataset.price),
                qty: qty
            });
        }
    });

    if(itemsToProcess.length === 0) return;

    // Sequential add to cart
    let completed = 0;
    itemsToProcess.forEach(item => {
        addToCart(item.name, item.price, item.qty).then(() => {
            completed++;
            if(completed === itemsToProcess.length) {
                showToast(`🛒 ${completed} items added!`);
                // Clear selection
                checks.forEach(c => {
                    c.checked = false;
                    updateSelection(c);
                });
            }
        });
    });
}

function addToCart(productName, price, qty = 1) {
    return fetch('cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'product_name=' + encodeURIComponent(productName) + 
              '&price=' + encodeURIComponent(price) +
              '&quantity=' + encodeURIComponent(qty)
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert('Error: ' + data.message);
        }
        return data;
    });
}

let toastTimeout;
function showToast(msg) {
    const toast = document.getElementById('toast');
    toast.textContent = msg;
    toast.style.display = 'block';
    toast.style.animation = 'none';
    void toast.offsetWidth;
    toast.style.animation = 'fadeInOut 2.5s ease forwards';
    clearTimeout(toastTimeout);
    toastTimeout = setTimeout(() => { toast.style.display = 'none'; }, 2500);
}
</script>

</body>
</html>