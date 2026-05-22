<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>

  <title>BLOSSOM | Elegant Gifts</title>

  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="common.css">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<style>
  nav ul li a {
    text-decoration: none;
    color: black;
  }

  nav ul li a:hover {
    color: #c75b6d;
  }


  :root {
    --pink: #c75b6d;
    --darkpink: #8b1e3f;
  }

  body {
    margin: 0;
    font-family: 'Poppins', sans-serif;

    /* 🌸 Main Background Image */
    background: url("images/bg2.jpeg") no-repeat center center fixed;
    background-size: cover;
  }

  /* NAVBAR */
  nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 80px;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(6px);
    position: sticky;
    top: 0;
  }


  .logo {
    font-family: 'Playfair Display', serif;
    font-size: 26px;
    color: var(--darkpink);
  }

  nav ul {
    display: flex;
    gap: 30px;
    list-style: none;
  }

  nav ul li {
    cursor: pointer;
    font-weight: 500;
  }

  nav ul li:hover {
    color: var(--darkpink);
  }

  button {
    padding: 10px 22px;
    border-radius: 30px;
    border: none;
    cursor: pointer;
    font-weight: 500;
  }

  .primary-btn {
    background: var(--pink);
    color: white;
  }

  /* HERO */
  .hero {
    text-align: center;
    padding: 120px 20px;
    color: #5a1a2e;
    background: rgba(255, 255, 255, 0.8);
    margin: 40px;
    border-radius: 20px;
  }

  .hero h1 {
    font-family: 'Playfair Display', serif;
    font-size: 60px;
    margin-bottom: 10px;
  }

  .hero p {
    font-size: 20px;
    font-style: italic;
  }

  /* SECTION */
  .section {
    padding: 80px 100px;
    text-align: center;
    background: rgba(255, 255, 255, 0.9);
    margin: 40px;
    border-radius: 20px;
  }

  .contact-section {
    padding: 80px 100px;
    text-align: center;
    background: rgba(255, 255, 255, 0.9);
    margin: 40px;
    border-radius: 20px;
  }

  .section h2 {
    font-family: 'Playfair Display', serif;
    color: var(--darkpink);
    font-size: 40px;
  }

  /* PRODUCTS */
  .products {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-top: 50px;
  }

  .product {
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
  }

  .product img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-radius: 15px;
  }

  .price {
    color: var(--darkpink);
    font-weight: 600;
    margin: 10px 0;
  }

  /* CUSTOMIZATION */
  select,
  input {
    padding: 10px;
    margin: 10px;
    border-radius: 8px;
    border: 1px solid #ddd;
  }

  /* CART */
  .cart-box {
    background: white;
    padding: 30px;
    border-radius: 15px;
    max-width: 600px;
    margin: auto;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
  }

  footer {
    text-align: center;
    padding: 20px;
    background: rgba(255, 255, 255, 0.9);
    margin-top: 60px;
  }
</style>
</head>

<body>

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
    <?php if (isset($_SESSION['user_id'])): ?>
      <span style="font-size:13px;color:#8b1e3f;margin-right:10px;">Hi,
        <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
      <a href="logout.php" class="primary-btn" style="text-decoration: none; display: inline-block; padding: 10px 22px; border-radius: 30px; font-weight: 500;">Logout</a>
    <?php else: ?>
      <a href="login.php" class="primary-btn" style="text-decoration: none; display: inline-block; padding: 10px 22px; border-radius: 30px; font-weight: 500;">Login</a>
    <?php endif; ?>
  </nav>

  <!-- HERO -->
  <section class="hero" id="home">
    <h1>BLOSSOM</h1>
    <p>Every petal has its own story</p>
    <div style="display: flex; gap: 15px; justify-content: center; margin-top: 20px;">
      <button class="primary-btn" onclick="window.location.href='products.php'">
        Shop Now
      </button>
      <button class="primary-btn" onclick="window.location.href='customize.php?mode=gift'"
        style="background: linear-gradient (135deg, #8b1e3f, #b03a5b);">
        Build Gift Bouquet
      </button>
    </div>
  </section>
  <section id="contact" class="contact-section">

    <div class="contact-box">

      <h2>Contact Blossom</h2>
      <p class="subtitle">We’d love to hear from you.</p>

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
</body>

<footer>
  © 2026 BLOSSOM | Developed by Rachana K S, Shivani Gatty, Neha Rai
</footer>

</html>