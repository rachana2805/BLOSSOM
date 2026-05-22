<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BLOSSOM | About Us</title>
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
    color: #333;
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

/* ===== PAGE CONTAINER ===== */
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
    background: rgba(255, 255, 255, 0.45);
    backdrop-filter: blur(8px);
    z-index: -1;
}

/* ===== FLOATING CONTENT BOX ===== */
.about-box {
    background: rgba(255, 255, 255, 0.95);
    padding: 60px 50px;
    border-radius: 20px;
    box-shadow: 0 15px 45px rgba(139, 30, 63, 0.15), 0 5px 15px rgba(0,0,0,0.05);
    width: 100%;
    max-width: 850px;
    animation: floating 6s ease-in-out infinite;
    position: relative;
    border: 1px solid rgba(255, 255, 255, 0.8);
    text-align: center;
}

@keyframes floating {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-15px); box-shadow: 0 25px 60px rgba(139, 30, 63, 0.2); }
    100% { transform: translateY(0px); }
}

.about-box h1 {
    font-family: 'Playfair Display', serif;
    color: #8b1e3f;
    font-size: 42px;
    margin-bottom: 20px;
    text-shadow: 2px 2px 5px rgba(139, 30, 63, 0.1);
}

.about-box p.welcome {
    font-size: 18px;
    color: #b03a5b;
    margin-bottom: 35px;
    font-weight: 500;
    line-height: 1.6;
}

.about-content {
    font-size: 16px;
    line-height: 1.8;
    color: #444;
    text-align: left;
    margin-bottom: 40px;
}

.offerings {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 40px;
}

.offering-card {
    flex: 1;
    background: #fdfafb;
    padding: 25px 15px;
    border-radius: 12px;
    border: 1px solid rgba(176, 58, 91, 0.1);
    transition: 0.3s;
    text-align: center;
}

.offering-card:hover {
    transform: translateY(-5px);
    border-color: #b03a5b;
    box-shadow: 0 8px 15px rgba(176, 58, 91, 0.08);
}

.offering-card i {
    font-size: 28px;
    color: #b03a5b;
    margin-bottom: 15px;
    display: block;
}

.offering-card h3 {
    font-size: 15px;
    color: #8b1e3f;
    font-weight: 600;
}

.usp {
    background: #fdf0f4;
    padding: 30px;
    border-radius: 15px;
    border-left: 5px solid #8b1e3f;
    text-align: left;
    font-style: italic;
    font-size: 16px;
    line-height: 1.7;
    color: #555;
}

/* ===== CONTACT SECTION ===== */
.contact-section {
    padding: 80px 20px;
    text-align: center;
    background: rgba(255, 255, 255, 0.9);
    position: relative;
    z-index: 2;
}

.contact-box h2 {
    font-family: 'Playfair Display', serif;
    color: #8b1e3f;
    font-size: 32px;
    margin-bottom: 30px;
}

.contact-item {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-bottom: 15px;
    font-size: 15px;
    color: #555;
}

.contact-item i {
    color: #b03a5b;
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
    padding: 25px;
    background: #6d1530;
    color: white;
    font-size: 13px;
    position: relative;
    z-index: 2;
}

/* Particles */
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
    background: rgba(139, 30, 63, 0.15);
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

<!-- ===== ABOUT CONTENT ===== -->
<div class="page-container">
    <div class="particles" id="particles"></div>
    
    <div class="about-box">
        <h1>About Us</h1>
        <p class="welcome">🌸 Blossom Elegant Gifts – Where emotions are beautifully crafted into unforgettable gifts 🎁🌷</p>
        
        <div class="about-content">
            <p>At Blossom, we believe that every occasion—whether it’s a birthday, anniversary, or a simple “thinking of you”—deserves a special touch. Our platform is designed to make gifting easy, meaningful, and personalized to your unique taste.</p>
        </div>

        <div class="offerings">
            <div class="offering-card">
                <i class="fas fa-hand-holding-heart"></i>
                <h3>Fresh & Customized<br>Flower Bouquets</h3>
            </div>
            <div class="offering-card">
                <i class="fas fa-gift"></i>
                <h3>Unique<br>Gift Items</h3>
            </div>
            <div class="offering-card">
                <i class="fas fa-palette"></i>
                <h3>Personalized Posters<br>& Decorations</h3>
            </div>
        </div>

        <div class="usp">
            <p>🌹✨ What makes us special is our <strong>custom bouquet feature</strong>, where you can design your own arrangement by selecting specific flowers, colors, and styles according to your preference. We turn your vision into a stunning floral reality.</p>
        </div>
    </div>
</div>

<!-- ===== CONTACT SECTION ===== -->
<section id="contact" class="contact-section">
    <div class="contact-box">
        <h2>Contact Blossom</h2>
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
// Generate simple floating particles for enhanced antigravity feel
document.addEventListener("DOMContentLoaded", function() {
    const particleContainer = document.getElementById('particles');
    for (let i = 0; i < 15; i++) {
        let p = document.createElement('div');
        p.classList.add('particle');
        let size = Math.random() * 8 + 4;
        p.style.width = size + 'px';
        p.style.height = size + 'px';
        p.style.left = Math.random() * 100 + '%';
        p.style.animationDuration = (Math.random() * 15 + 10) + 's';
        p.style.animationDelay = (Math.random() * 10) + 's';
        p.style.opacity = Math.random() * 0.4 + 0.1;
        particleContainer.appendChild(p);
    }
});
</script>

</body>
</html>