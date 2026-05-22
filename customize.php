<?php
session_start();
include("db.php");

// Fetch Gift Items (Chocolates, Hot Wheels, Extras) from items table
$items_query = mysqli_query($conn, "SELECT * FROM items WHERE status='active' AND stock > 0 AND category != 'Flowers'");
$gift_items = [];
while ($row = mysqli_fetch_assoc($items_query)) {
    $gift_items[$row['category']][] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>BLOSSOM | Customize Your Bouquet</title>

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

/* ===== ANTIGRAVITY FLOATING UI ===== */
.page-container {
    min-height: calc(100vh - 70px);
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 60px 20px;
    position: relative;
    z-index: 1;
}

.page-container::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(255, 255, 255, 0.5);
    backdrop-filter: blur(8px);
    z-index: -1;
}

.floating-box {
    background: rgba(255, 255, 255, 0.95);
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(139, 30, 63, 0.15), 0 5px 15px rgba(0,0,0,0.05);
    width: 100%;
    max-width: 650px;
    animation: floating 6s ease-in-out infinite;
    position: relative;
    border: 1px solid rgba(255, 255, 255, 0.8);
}

@keyframes floating {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-20px); box-shadow: 0 25px 50px rgba(139, 30, 63, 0.2), 0 10px 20px rgba(0,0,0,0.08); }
    100% { transform: translateY(0px); }
}

.floating-box h1 {
    font-family: 'Playfair Display', serif;
    color: #8b1e3f;
    text-align: center;
    margin-bottom: 10px;
    font-size: 36px;
    text-shadow: 2px 2px 5px rgba(139, 30, 63, 0.1);
}

.floating-box p.subtitle {
    text-align: center;
    color: #b03a5b;
    margin-bottom: 30px;
    font-weight: 500;
    font-size: 15px;
}

/* Form Sections */
.form-section {
    background: #fdfafb;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.02);
    border: 1px solid rgba(176, 58, 91, 0.1);
}

.form-section h3 {
    font-size: 18px;
    color: #8b1e3f;
    margin-bottom: 15px;
    font-weight: 600;
    border-bottom: 2px dashed #fdf0f4;
    padding-bottom: 8px;
}

.checkbox-group {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.flower-checkbox {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    font-size: 16px;
    color: #444;
    font-weight: 500;
    padding: 8px 12px;
    background: white;
    border-radius: 8px;
    border: 1px solid #eee;
    transition: 0.3s;
}

.flower-checkbox:hover {
    border-color: #b03a5b;
    box-shadow: 0 4px 8px rgba(176,58,91,0.1);
}

.flower-checkbox input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: #8b1e3f;
    cursor: pointer;
}

/* Extra Item Rows */
.extra-item-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    padding: 12px 15px;
    background: white;
    border-radius: 8px;
    margin-bottom: 10px;
    border: 1px solid #eee;
    transition: 0.3s;
}

.extra-item-row:hover {
    border-color: #b03a5b;
    box-shadow: 0 4px 8px rgba(176,58,91,0.05);
}

.extra-item-row label {
    margin: 0;
    padding: 0;
}

.dynamic-input {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    outline: none;
    transition: 0.3s;
    color: #333;
}

.dynamic-input:focus {
    border-color: #8b1e3f;
    box-shadow: 0 0 5px rgba(139,30,63,0.2);
}

@keyframes subtle-float {
    0% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
    100% { transform: translateY(0); }
}

.bouquet-card {
    background: white;
    border-radius: 12px;
    padding: 18px;
    border: 1px solid #eee;
    text-align: center;
    box-shadow: 0 4px 10px rgba(139,30,63,0.05);
    transition: all 0.3s ease;
    animation: subtle-float 5s ease-in-out infinite;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.bouquet-card:hover {
    box-shadow: 0 8px 15px rgba(139,30,63,0.1);
    transform: translateY(-2px);
    border-color: #b03a5b;
}

.extra-item-row select:disabled,
.extra-item-row input:disabled {
    background-color: #f9f9f9;
    color: #bbb;
    border-color: #eee;
    cursor: not-allowed;
}

/* Mode Selector Styles */
.mode-selector {
    display: flex;
    gap: 15px;
    margin-bottom: 30px;
}

.mode-btn {
    flex: 1;
    padding: 15px;
    border-radius: 15px;
    border: 2px solid #fdf0f4;
    background: white;
    cursor: pointer;
    text-align: center;
    transition: 0.3s;
    font-weight: 600;
    color: #8b1e3f;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.mode-btn i { font-size: 24px; }
.mode-btn.active {
    border-color: #8b1e3f;
    background: #fdf0f4;
}

/* Gift Item UI */
.gift-item-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    background: white;
    border-radius: 8px;
    border: 1px solid #eee;
    margin-bottom: 8px;
}

.gift-qty-controls {
    display: flex;
    align-items: center;
    gap: 12px;
}

.gift-qty-btn {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 1px solid #8b1e3f;
    background: white;
    color: #8b1e3f;
    cursor: pointer;
    font-weight: bold;
}

.gift-qty-btn:hover { background: #8b1e3f; color: white; }

.total-price-display {
    text-align: right;
    font-size: 18px;
    color: #8b1e3f;
    font-weight: 600;
    margin-bottom: 25px;
    padding: 12px 20px;
    background: rgba(253, 240, 244, 0.8);
    border-radius: 8px;
    border: 1px dashed #c75b6d;
    display: none;
}

/* Dynamic Rows */
.dynamic-row {
    display: none;
    align-items: center;
    justify-content: space-between;
    padding: 12px 15px;
    background: white;
    border-radius: 8px;
    margin-bottom: 10px;
    border: 1px solid #eee;
    animation: fadeIn 0.4s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}

.dynamic-row label {
    font-weight: 500;
    color: #555;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.dynamic-row select,
.dynamic-row input[type="number"] {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    outline: none;
    transition: 0.3s;
    color: #333;
}

.dynamic-row select:focus,
.dynamic-row input[type="number"]:focus {
    border-color: #8b1e3f;
    box-shadow: 0 0 5px rgba(139,30,63,0.2);
}

.btn-container {
    text-align: center;
    margin-top: 10px;
}

.submit-btn {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, #b03a5b, #8b1e3f);
    color: white;
    font-size: 18px;
    font-family: 'Poppins', sans-serif;
    font-weight: 500;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    box-shadow: 0 10px 20px rgba(139, 30, 63, 0.3);
    transition: all 0.4s ease;
    letter-spacing: 1px;
}

.submit-btn:hover {
    background: linear-gradient(135deg, #8b1e3f, #6d1530);
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 15px 25px rgba(139, 30, 63, 0.5);
}

.submit-btn:active {
    transform: translateY(1px);
}

/* Summary Modal / Toast */
.summary-result {
    margin-top: 25px;
    padding: 20px;
    background: rgba(253, 240, 244, 0.8);
    border-radius: 12px;
    color: #444;
    font-size: 15px;
    display: none;
    border: 2px dashed #c75b6d;
    animation: fadeIn 0.5s ease-in-out;
}

.summary-result h3 {
    color: #8b1e3f;
    margin-bottom: 12px;
    font-family: 'Playfair Display', serif;
    font-size: 22px;
    text-align: center;
}

.summary-result ul {
    list-style: none;
}

.summary-result li {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid rgba(139,30,63,0.1);
    font-weight: 500;
}

.summary-result li:last-child {
    border-bottom: none;
}

.empty-message {
    color: #999;
    font-size: 14px;
    font-style: italic;
    padding: 5px 0;
}

/* ===== CONTACT SECTION ===== */
.contact-section {
    background: #8b1e3f;
    padding: 60px 20px;
    text-align: center;
    position: relative;
    z-index: 2;
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
    position: relative;
    z-index: 2;
}

/* Particle animation for light feeling */
.particles {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0; left: 0;
    overflow: hidden;
    z-index: -1;
    pointer-events: none;
}
.particle {
    position: absolute;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 50%;
    animation: drift linear infinite;
}
@keyframes drift {
    from { transform: translateY(100vh) rotate(0deg); opacity: 0; }
    10% { opacity: 1; }
    90% { opacity: 1; }
    to { transform: translateY(-100px) rotate(360deg); opacity: 0; }
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
        <li><a href="cart.php">Cart</a></li>
        <li><a href="trackorder.php">Track Order</a></li>
        <li><a href="#contact">Contact</a></li>
    </ul>
    <button onclick="window.location.href='<?php echo isset($_SESSION['user_id']) ? 'logout.php' : 'login.php'; ?>'" class="primary-btn">
        <?php echo isset($_SESSION['user_id']) ? 'Logout' : 'Login'; ?>
    </button>
</nav>

<!-- ===== CUSTOMIZE UI ===== -->
<div class="page-container">
    <div class="particles" id="particles"></div>
    <div class="floating-box">
        <h1>Customization Studio</h1>
        <p class="subtitle">Choose between Fresh Flowers or a Gift Collection.</p>

        <!-- Mode Selector -->
        <div class="mode-selector">
            <button type="button" class="mode-btn active" id="mode-btn-floral" onclick="switchMode('floral')">
                <i class="fas fa-seedling"></i>
                Floral Design
            </button>
            <button type="button" class="mode-btn" id="mode-btn-gift" onclick="switchMode('gift')">
                <i class="fas fa-gift"></i>
                Gift Collection
            </button>
        </div>

        <form id="customizeForm" onsubmit="handleFormSubmit(event)">
            <input type="hidden" name="custom_mode" id="custom_mode" value="floral">
            
            <!-- FLORAL MODE SECTIONS -->
            <div id="floral-sections">
                <!-- SECTION 1: Select Flowers -->
                <div class="form-section">
                    <h3>1. Custom Bouquet Flowers</h3>
                    <div class="checkbox-group">
                        <label class="flower-checkbox">
                            <input type="checkbox" name="flower" value="Rose" data-price="60" onchange="toggleFlowerOptions('rose'); calculateTotal();">
                            <i class="fas fa-seedling" style="color: #c75b6d;"></i> Rose (₹60)
                        </label>
                        <label class="flower-checkbox">
                            <input type="checkbox" name="flower" value="Lily" data-price="150" onchange="toggleFlowerOptions('lily'); calculateTotal();">
                            <i class="fas fa-seedling" style="color: #ff9a9e;"></i> Lily (₹150)
                        </label>
                        <label class="flower-checkbox">
                            <input type="checkbox" name="flower" value="Sunflower" data-price="100" onchange="toggleFlowerOptions('sunflower'); calculateTotal();">
                            <i class="fas fa-sun" style="color: #ffb347;"></i> Sunflower (₹100)
                        </label>
                        <label class="flower-checkbox">
                            <input type="checkbox" name="flower" value="Gerbera" data-price="80" onchange="toggleFlowerOptions('gerbera'); calculateTotal();">
                            <i class="fas fa-seedling" style="color: #460659;"></i> Gerbera (₹80)
                        </label>
                        <label class="flower-checkbox">
                            <input type="checkbox" name="flower" value="Orchid" data-price="70" onchange="toggleFlowerOptions('orchid'); calculateTotal();">
                            <i class="fas fa-seedling" style="color: #340473;"></i> Orchids (₹70)
                        </label>
                    </div>
                </div>

                <!-- SECTION 2 & 3: Colour and Qty -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-section" id="color-section">
                        <h3>2. Flower Colour</h3>
                        <div id="color-empty-msg" class="empty-message">Select a flower.</div>
                        <div class="dynamic-row" id="color-row-rose">
                            <select id="color-rose" name="color-rose"><option value="Red">Red</option><option value="White">White</option><option value="Yellow">Yellow</option><option value="Pink">Pink</option></select>
                        </div>
                        <div class="dynamic-row" id="color-row-lily">
                            <select id="color-lily" name="color-lily"><option value="White">White</option><option value="Pink">Pink</option><option value="Purple">Purple</option></select>
                        </div>
                        <div class="dynamic-row" id="color-row-sunflower">
                            <select id="color-sunflower" name="color-sunflower"><option value="Yellow">Yellow</option></select>
                        </div>
                        <div class="dynamic-row" id="color-row-gerbera">
                            <select id="color-gerbera" name="color-gerbera"><option value="Red">Red</option><option value="Pink">Pink</option><option value="Yellow">Yellow</option><option value="White">White</option></select>
                        </div>
                        <div class="dynamic-row" id="color-row-orchid">
                            <select id="color-orchid" name="color-orchid"><option value="Purple">Purple</option><option value="White">White</option><option value="Pink">Pink</option></select>
                        </div>
                    </div>
                    <div class="form-section" id="qty-section">
                        <h3>3. Quantity</h3>
                        <div id="qty-empty-msg" class="empty-message">Select a flower.</div>
                        <div class="dynamic-row" id="qty-row-rose"><input type="number" id="qty-rose" min="1" max="100" value="1" onchange="calculateTotal()"></div>
                        <div class="dynamic-row" id="qty-row-lily"><input type="number" id="qty-lily" min="1" max="100" value="1" onchange="calculateTotal()"></div>
                        <div class="dynamic-row" id="qty-row-sunflower"><input type="number" id="qty-sunflower" min="1" max="100" value="1" onchange="calculateTotal()"></div>
                        <div class="dynamic-row" id="qty-row-gerbera"><input type="number" id="qty-gerbera" min="1" max="100" value="1" onchange="calculateTotal()"></div>
                        <div class="dynamic-row" id="qty-row-orchid"><input type="number" id="qty-orchid" min="1" max="100" value="1" onchange="calculateTotal()"></div>
                    </div>
                </div>

                <!-- SECTION 4: Ribbon & Wrapping -->
                <div class="form-section" id="extra-section">
                    <h3>4. Ribbon & Wrapping</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <h4 style="margin-bottom:8px; color:#b03a5b; font-size:14px;">Ribbons (₹2)</h4>
                            <?php foreach(['Red Ribbon', 'Pink Ribbon', 'Gold Ribbon', 'White Ribbon'] as $rib): ?>
                            <div class="extra-item-row" style="padding: 5px 10px;">
                                <label class="flower-checkbox" style="border:none; padding:0; font-size:12px;">
                                    <input type="checkbox" name="ribbon" value="<?php echo $rib; ?>" data-id="<?php echo strtolower(str_replace(' ', '-', $rib)); ?>" data-price="2" onchange="toggleExtra('<?php echo strtolower(str_replace(' ', '-', $rib)); ?>', this)"> <?php echo $rib; ?>
                                </label>
                                <input type="number" id="qty-<?php echo strtolower(str_replace(' ', '-', $rib)); ?>" class="dynamic-input" min="1" max="20" value="1" disabled onchange="calculateTotal()" style="width: 45px; height:25px; padding:2px;">
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div>
                            <h4 style="margin-bottom:8px; color:#b03a5b; font-size:14px;">Wrapping (₹30)</h4>
                            <?php foreach(['Transparent Wrap', 'Black Wrap', 'Pastel Pink Wrap', 'Lavender Wrap'] as $wrap): ?>
                            <div class="extra-item-row" style="padding: 5px 10px;">
                                <label class="flower-checkbox" style="border:none; padding:0; font-size:12px;">
                                    <input type="checkbox" name="wrapping" value="<?php echo $wrap; ?>" data-id="<?php echo strtolower(str_replace(' ', '-', $wrap)); ?>" data-price="30" onchange="toggleExtra('<?php echo strtolower(str_replace(' ', '-', $wrap)); ?>', this)"> <?php echo $wrap; ?>
                                </label>
                                <input type="number" id="qty-<?php echo strtolower(str_replace(' ', '-', $wrap)); ?>" class="dynamic-input" min="1" max="20" value="1" disabled onchange="calculateTotal()" style="width: 45px; height:25px; padding:2px;">
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GIFT MODE SECTIONS -->
            <div id="gift-sections" style="display: none;">
                <div class="form-section">
                    <h3>1. Select Gift Theme</h3>
                    <div class="checkbox-group">
                        <label class="flower-checkbox">
                            <input type="radio" name="gift_bouquet_type" value="Chocolate Bouquet" onchange="updateGiftPrice(200)">
                            <i class="fas fa-cookie"></i> Chocolate Bouquet
                        </label>
                        <label class="flower-checkbox">
                            <input type="radio" name="gift_bouquet_type" value="Hot Wheels Bouquet" onchange="updateGiftPrice(300)">
                            <i class="fas fa-car"></i> Hot Wheels Bouquet
                        </label>
                        <label class="flower-checkbox">
                            <input type="radio" name="gift_bouquet_type" value="Mixed Bouquet" onchange="updateGiftPrice(400)">
                            <i class="fas fa-gift"></i> Mixed Bouquet
                        </label>
                    </div>
                    <input type="hidden" id="gift_base_price" value="0">
                </div>

                <div class="form-section">
                    <h3>2. Select Wrapping</h3>
                    <select name="gift_wrapping" id="gift_wrapping" class="dynamic-input" style="width: 100%;" onchange="calculateTotal()">
                        <option value="" data-price="0" selected>Select Wrapping Style...</option>
                        <option value="Paper Wrap" data-price="50">Paper Wrap (₹50)</option>
                        <option value="Basket" data-price="150">Basket (₹150)</option>
                        <option value="Premium Wrap" data-price="250">Premium Wrap (₹250)</option>
                    </select>
                </div>

                <div class="form-section">
                    <h3>3. Add Items</h3>
                    <div id="gift-items-container">
                        <?php foreach ($gift_items as $category => $items): ?>
                            <h4 style="margin: 15px 0 10px; color: #8b1e3f; font-size: 14px;"><?php echo $category; ?></h4>
                            <?php foreach ($items as $item): ?>
                                <div class="gift-item-row">
                                    <div style="font-size: 14px;">
                                        <strong><?php echo $item['name']; ?></strong><br>
                                        <small style="color:#b03a5b;">₹<?php echo $item['price']; ?></small>
                                    </div>
                                    <div class="gift-qty-controls" data-id="<?php echo $item['id']; ?>" data-name="<?php echo $item['name']; ?>" data-price="<?php echo $item['price']; ?>" data-stock="<?php echo $item['stock']; ?>" data-category="<?php echo $item['category']; ?>">
                                        <button type="button" class="gift-qty-btn" onclick="adjustGiftQty(this, -1)">-</button>
                                        <span id="gift_qty_<?php echo $item['id']; ?>" style="font-weight:600; min-width:15px; text-align:center;">0</span>
                                        <button type="button" class="gift-qty-btn" onclick="adjustGiftQty(this, 1)">+</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                    <input type="hidden" name="gift_items_json" id="gift_items_json">
                </div>
            </div>

            <!-- UNIVERSAL SECTIONS -->
            <div class="form-section">
                <h3>5. Personalized Message</h3>
                <textarea name="custom_message" id="custom_message" class="dynamic-input" style="width:100%; height:80px; resize:none;" placeholder="Write a sweet note..."></textarea>
            </div>

            <div id="total-price-container" class="total-price-display">
                Estimated Total: ₹<span id="extra-total-price">0</span>
                <div style="font-size:12px; color:#888; font-weight:400; margin-top:5px;">(Includes Add-ons & Components)</div>
            </div>

            <!-- SECTION 5: Special Customization -->
            <div class="form-section" style="text-align: center;">
                <h3 style="border-bottom:none; margin-bottom:10px;"><i class="fas fa-star" style="color: #b03a5b;"></i> Special Customization</h3>
                <p style="color: #555; font-size: 14px; margin-bottom: 20px;">For Special Customization, contact us on WhatsApp or Instagram!</p>
                <div style="display: flex; justify-content: center; gap: 15px;">
                    <a href="https://wa.me/919739296978" target="_blank" style="padding: 10px 20px; background: #25D366; color: white; border-radius: 20px; text-decoration: none; font-weight: 500; font-size: 14px;"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                    <a href="https://www.instagram.com/blossoms_mangalore" target="_blank" style="padding: 10px 20px; background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fd5949 45%, #d6249f 60%, #285AEB 90%); color: white; border-radius: 20px; text-decoration: none; font-weight: 500; font-size: 14px;"><i class="fab fa-instagram"></i> Instagram</a>
                </div>
            </div>

            <div class="btn-container" style="display: flex; gap: 15px; flex-direction: column;">
                <button type="submit" class="submit-btn"><i class="fas fa-magic"></i> Create My Bouquet Summary</button>
                <button type="button" class="submit-btn" onclick="addToCartAndRedirect()" style="background: linear-gradient(135deg, #2b9348, #007f5f);"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
            </div>
        </form>

        <div id="summary" class="summary-result">
            <h3><i class="fas fa-gift"></i> Your Bouquet Summary</h3>
            <ul id="summaryList"></ul>
        </div>

    </div>
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

<script>
const EXTRA_ITEM_PRICE = 80;
let giftItems = {};

function switchMode(mode) {
    document.getElementById('custom_mode').value = mode;
    document.getElementById('mode-btn-floral').classList.toggle('active', mode === 'floral');
    document.getElementById('mode-btn-gift').classList.toggle('active', mode === 'gift');
    
    document.getElementById('floral-sections').style.display = (mode === 'floral') ? 'block' : 'none';
    document.getElementById('gift-sections').style.display = (mode === 'gift') ? 'block' : 'none';
    
    calculateTotal();
}

function updateGiftPrice(price) {
    document.getElementById('gift_base_price').value = price;
    calculateTotal();
}

function adjustGiftQty(btn, change) {
    const controls = btn.parentElement;
    const id = controls.dataset.id;
    const name = controls.dataset.name;
    const price = parseFloat(controls.dataset.price);
    const stock = parseInt(controls.dataset.stock);
    const category = controls.dataset.category;

    if (!giftItems[id]) {
        giftItems[id] = { name, price, qty: 0, category, stock };
    }

    let newQty = giftItems[id].qty + change;
    
    // LIMIT: Ensure total quantity in THIS category doesn't exceed 10
    if (change > 0) {
        let categoryTotal = 0;
        Object.values(giftItems).forEach(item => {
            if (item.category === category) categoryTotal += item.qty;
        });
        
        if (categoryTotal + change > 10) {
            alert(`You can only select a total of 10 items from the ${category} category.`);
            return;
        }
    }

    if (newQty < 0) return;
    if (newQty > stock) { alert(`Only ${stock} in stock!`); return; }

    giftItems[id].qty = newQty;
    document.getElementById(`gift_qty_${id}`).innerText = newQty;
    if (newQty === 0) delete giftItems[id];
    
    document.getElementById('gift_items_json').value = JSON.stringify(Object.values(giftItems));
    calculateTotal();
}



function toggleExtra(itemId, checkbox) {
    const qtyInput = document.getElementById(`qty-${itemId}`);
    qtyInput.disabled = !checkbox.checked;
    if (!checkbox.checked) qtyInput.value = 1;
    calculateTotal();
}

function calculateTotal() {
    let total = 0;
    const mode = document.getElementById('custom_mode').value;

    if (mode === 'floral') {
        const flowers = document.querySelectorAll('input[name="flower"]:checked');
        flowers.forEach(cb => {
            const flowerId = cb.value.toLowerCase();
            const qtyInput = document.getElementById(`qty-${flowerId}`);
            if (qtyInput) {
                const qty = parseInt(qtyInput.value) || 0;
                const price = parseFloat(cb.dataset.price) || 0;
                total += qty * price;
            }
        });

        const extras = document.querySelectorAll('input[name="ribbon"]:checked, input[name="wrapping"]:checked');
        extras.forEach(cb => {
            const qty = parseInt(document.getElementById(`qty-${cb.dataset.id}`).value) || 0;
            const price = parseFloat(cb.dataset.price) || 0;
            total += qty * price;
        });
    } else {
        total += parseFloat(document.getElementById('gift_base_price').value) || 0;
        const wrapSel = document.getElementById('gift_wrapping');
        total += parseFloat(wrapSel.options[wrapSel.selectedIndex].dataset.price) || 0;
        Object.values(giftItems).forEach(i => { total += i.price * i.qty; });
    }
    
    const container = document.getElementById('total-price-container');
    if (total > 0) {
        container.style.display = 'block';
        document.getElementById('extra-total-price').innerText = total.toLocaleString();
    } else {
        container.style.display = (mode === 'gift') ? 'block' : 'none'; 
        document.getElementById('extra-total-price').innerText = (mode === 'gift') ? total.toLocaleString() : "0";
    }
}

function toggleFlowerOptions(flowerId) {
    const checkbox = document.querySelector(`input[value="${flowerId.charAt(0).toUpperCase() + flowerId.slice(1)}"]`);
    const colorRow = document.getElementById(`color-row-${flowerId}`);
    const qtyRow = document.getElementById(`qty-row-${flowerId}`);
    
    if (checkbox.checked) {
        colorRow.style.display = 'flex';
        qtyRow.style.display = 'flex';
    } else {
        colorRow.style.display = 'none';
        qtyRow.style.display = 'none';
    }
    
    const selectedCount = document.querySelectorAll('input[name="flower"]:checked').length;
    document.getElementById('color-empty-msg').style.display = selectedCount > 0 ? 'none' : 'block';
    document.getElementById('qty-empty-msg').style.display = selectedCount > 0 ? 'none' : 'block';
}

function handleFormSubmit(event) {
    event.preventDefault();
    const mode = document.getElementById('custom_mode').value;

    const summaryDiv = document.getElementById('summary');
    const summaryList = document.getElementById('summaryList');
    summaryList.innerHTML = '';
    summaryDiv.style.display = 'block';

    if (mode === 'floral') {
        const flowers = document.querySelectorAll('input[name="flower"]:checked');
        if (flowers.length === 0) { 
            alert("Select at least one flower to create your bouquet."); 
            summaryDiv.style.display = 'none'; 
            return; 
        }
        flowers.forEach(cb => {
            const id = cb.value.toLowerCase();
            const color = document.getElementById(`color-${id}`).value;
            const qty = document.getElementById(`qty-${id}`).value;
            summaryList.innerHTML += `<li><span><i class="fas fa-seedling"></i> ${color} ${cb.value}</span> <span>x${qty}</span></li>`;
        });
    } else {
        const giftTypeInput = document.querySelector('input[name="gift_bouquet_type"]:checked');
        const giftWrapInput = document.getElementById('gift_wrapping');
        
        if (!giftTypeInput) { alert("Please select a Gift Theme (Step 1)."); summaryDiv.style.display = 'none'; return; }
        if (giftWrapInput.value === "") { alert("Please select a Wrapping Style (Step 2)."); summaryDiv.style.display = 'none'; return; }
        if (Object.keys(giftItems).length === 0) { alert("Add at least one item to your gift bouquet (Step 3)."); summaryDiv.style.display = 'none'; return; }
        
        const bType = giftTypeInput.value;
        const wType = giftWrapInput.value;
        summaryList.innerHTML += `<li><span><strong>Base:</strong> ${bType}</span></li>`;
        summaryList.innerHTML += `<li><span><strong>Wrap:</strong> ${wType}</span></li>`;
        Object.values(giftItems).forEach(i => {
            summaryList.innerHTML += `<li><span>• ${i.name}</span> <span>x${i.qty}</span></li>`;
        });
    }

    summaryList.innerHTML += `<li style="margin-top:10px; color:#8b1e3f;"><span>Total</span> <span>₹${document.getElementById('extra-total-price').innerText}</span></li>`;
    summaryDiv.scrollIntoView({ behavior: 'smooth' });
}

async function addToCartAndRedirect() {
    const mode = document.getElementById('custom_mode').value;

    let itemsToAdd = [];

    if (mode === 'floral') {
        const floralCheckboxes = document.querySelectorAll('input[name="flower"]:checked');
        floralCheckboxes.forEach(cb => {
            let flowerId = cb.value.toLowerCase();
            const colorInput = document.getElementById(`color-${flowerId}`);
            const qtyInput = document.getElementById(`qty-${flowerId}`);
            const price = parseFloat(cb.dataset.price) || 0;
            if (colorInput && qtyInput && parseInt(qtyInput.value) > 0) {
                itemsToAdd.push({
                    product_name: `${colorInput.value} ${cb.value}`,
                    price: price,
                    qty: qtyInput.value
                });
            }
        });

        // Extras
        const extras = document.querySelectorAll('input[name="ribbon"]:checked, input[name="wrapping"]:checked');
        extras.forEach(cb => {
            const qty = document.getElementById(`qty-${cb.getAttribute('data-id')}`).value;
            const price = parseFloat(cb.dataset.price) || 0;
            itemsToAdd.push({ product_name: cb.value, price: price, qty: qty });
        });
    } else {
        // Gift Mode Integration with Cart
        const giftTypeInput = document.querySelector('input[name="gift_bouquet_type"]:checked');
        const giftWrapInput = document.getElementById('gift_wrapping');
        
        if (!giftTypeInput) { alert("Please select a Gift Theme (Step 1)."); return; }
        if (giftWrapInput.value === "") { alert("Please select a Wrapping Style (Step 2)."); return; }
        if (Object.keys(giftItems).length === 0) { alert("Add at least one item to your gift bouquet (Step 3)."); return; }
        
        const bType = giftTypeInput.value;
        const wType = giftWrapInput.value;
        const itemsJson = document.getElementById('gift_items_json').value;
        const totalPrice = document.getElementById('extra-total-price').innerText.replace(/,/g, '');
        const message = document.getElementById('custom_message').value;

        itemsToAdd.push({
            product_name: `Gift Collection: ${bType}`,
            price: totalPrice,
            qty: 1,
            items_json: itemsJson,
            wrapping_type: wType,
            message: message
        });
    }

    if (itemsToAdd.length === 0) {
        alert("Please select at least one item.");
        return;
    }

    try {
        for(let item of itemsToAdd) {
            let body = 'product_name=' + encodeURIComponent(item.product_name) + 
                       '&price=' + encodeURIComponent(item.price) + 
                       '&qty=' + encodeURIComponent(item.qty);
            
            if (item.items_json) body += '&items_json=' + encodeURIComponent(item.items_json);
            if (item.wrapping_type) body += '&wrapping_type=' + encodeURIComponent(item.wrapping_type);
            if (item.message) body += '&message=' + encodeURIComponent(item.message);

            await fetch('cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: body
            });
        }
        window.location.href = "cart.php";
        return;
    } catch (e) {
        alert("Error adding to cart: " + e.message);
    }
}

// Generate simple floating particles for enhanced antigravity feel
document.addEventListener("DOMContentLoaded", function() {
    const particleContainer = document.getElementById('particles');
    for (let i = 0; i < 20; i++) {
        let p = document.createElement('div');
        p.classList.add('particle');
        let size = Math.random() * 10 + 5;
        p.style.width = size + 'px';
        p.style.height = size + 'px';
        p.style.left = Math.random() * 100 + '%';
        p.style.animationDuration = (Math.random() * 10 + 10) + 's';
        p.style.animationDelay = (Math.random() * 10) + 's';
        p.style.opacity = Math.random() * 0.5 + 0.3;
        particleContainer.appendChild(p);
    }

    // Check URL parameters for direct mode access
    const urlParams = new URLSearchParams(window.location.search);
    const requestedMode = urlParams.get('mode');
    if (requestedMode === 'gift') {
        switchMode('gift');
    }
});
</script>

</body>
</html>