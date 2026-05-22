<?php
session_start();
include "db.php";

/* PROTECT PAGE */
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

/* ADD PRODUCT */
if (isset($_POST['add'])) {
    $name  = $conn->real_escape_string($_POST['name']);
    $price = floatval($_POST['price']);
    $image = $conn->real_escape_string($_POST['image']);
    $stock = intval($_POST['stock']);

    $conn->query("INSERT INTO products (name, price, image, stock) VALUES ('$name', $price, '$image', $stock)");
    header("Location: products-admin.php");
    exit();
}

/* UPDATE PRODUCT */
if (isset($_POST['update'])) {
    $id    = intval($_POST['id']);
    $name  = $conn->real_escape_string($_POST['name']);
    $price = floatval($_POST['price']);
    $image = $conn->real_escape_string($_POST['image']);
    $stock = intval($_POST['stock']);

    $conn->query("UPDATE products SET name='$name', price=$price, image='$image', stock=$stock WHERE id=$id");
    header("Location: products-admin.php");
    exit();
}

/* TOGGLE STOCK: Quick buttons for In Stock / Out of Stock */
if (isset($_GET['toggle_stock'])) {
    $id = intval($_GET['toggle_stock']);
    $current_stock = intval($_GET['current']);
    $new_stock = ($current_stock > 0) ? 0 : 10;
    
    $conn->query("UPDATE products SET stock=$new_stock WHERE id=$id");
    header("Location: products-admin.php");
    exit();
}

/* DELETE PRODUCT */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM products WHERE id=$id");
    header("Location: products-admin.php");
    exit();
}

/* EDIT MODE: load product to edit */
$edit_product = null;
if (isset($_GET['edit'])) {
    $eid = intval($_GET['edit']);
    $er = $conn->query("SELECT * FROM products WHERE id=$eid");
    $edit_product = $er->fetch_assoc();
}

/* FETCH PRODUCTS */
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Products | BLOSSOM Admin</title>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
:root {
  --pink: #c75b6d;
  --darkpink: #8b1e3f;
  --lightpink: #f4ebee;
  --text: #3a1020;
  --muted: #a07080;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Poppins', sans-serif; background: var(--lightpink); color: var(--text); display: flex; min-height: 100vh; }

/* SIDEBAR */
.sidebar { width: 240px; background: linear-gradient(160deg, #8b1e3f, #c75b6d); display: flex; flex-direction: column; padding: 30px 0; position: fixed; height: 100vh; }
.sidebar .logo { font-family: 'Playfair Display', serif; font-size: 26px; color: white; text-align: center; }
.sidebar .admin-label { text-align: center; color: rgba(255,255,255,0.6); font-size: 11px; letter-spacing: 2px; margin-bottom: 35px; }
.sidebar nav a { display: flex; align-items: center; gap: 12px; padding: 14px 30px; color: rgba(255,255,255,0.75); text-decoration: none; font-size: 14px; font-weight: 500; transition: 0.2s; border-left: 3px solid transparent; }
.sidebar nav a:hover, .sidebar nav a.active { background: rgba(255,255,255,0.15); color: white; border-left: 3px solid white; }
.sidebar .logout { margin-top: auto; padding: 14px 30px; display: flex; align-items: center; gap: 12px; color: rgba(255,255,255,0.6); cursor: pointer; }

/* MAIN */
.main { margin-left: 240px; flex: 1; padding: 40px; }
.topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px; }
.topbar h1 { font-family: 'Playfair Display', serif; font-size: 28px; color: var(--darkpink); }

/* CARD */
.card { background: white; border-radius: 16px; padding: 28px; box-shadow: 0 4px 15px rgba(139,30,63,0.07); margin-bottom: 30px; }
.card-header { margin-bottom: 20px; }
.card-header h3 { font-family: 'Playfair Display', serif; color: var(--darkpink); }

/* FORM */
input { padding: 10px; margin-right: 10px; border: 1px solid #ddd; border-radius: 6px; }
button { padding: 8px 14px; border: none; border-radius: 6px; cursor: pointer; font-size: 11px; font-weight: 500; }
.add-btn { background: var(--pink); color: white; padding: 10px 20px; font-size: 13px; }
.edit-btn { background: #fff3e0; color: #b26a00; }
.delete-btn { background: #fdeaed; color: #c0392b; }
.stock-btn-in { background: #e6f7ed; color: #2b9348; border: 1px solid #2b934833; }
.stock-btn-out { background: #fdeaed; color: #e74c3c; border: 1px solid #e74c3c33; }
.badge { padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 600; display: inline-block; }
.badge-in { background: #e6f7ed; color: #2b9348; }
.badge-out { background: #fdeaed; color: #e74c3c; }

/* TABLE */
table { width: 100%; border-collapse: collapse; }
th, td { padding: 10px; border-bottom: 1px solid #f1e0e5; text-align: left; }
th { color: var(--muted); font-size: 12px; text-transform: uppercase; }
.product-img { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
</style>
</head>

<body>

<div class="sidebar">
  <div class="logo">BLOSSOM</div>
  <div class="admin-label">Admin Panel</div>
  <nav>
    <a href="dashboard.php"><i class="fas fa-th-large"></i> Dashboard</a>
    <a href="products-admin.php" class="active"><i class="fas fa-spa"></i> Products</a>
    <a href="orders-admin.php"><i class="fas fa-box"></i> Orders</a>
    <a href="settings-admin.php"><i class="fas fa-users"></i> Users</a>
  </nav>
  <a href="admin_logout.php" style="margin-top:auto;padding:14px 30px;color:rgba(255,255,255,0.7);text-decoration:none;display:flex;align-items:center;gap:12px;font-size:14px;">
    <i class="fas fa-sign-out-alt"></i> Logout
  </a>
</div>

<div class="main">

  <div class="topbar">
    <h1>Manage Products</h1>
  </div>

  <!-- ADD / EDIT PRODUCT FORM -->
  <div class="card">
    <div class="card-header">
      <h3><?php echo $edit_product ? 'Edit Product' : 'Add New Product'; ?></h3>
      <?php if($edit_product): ?>
        <a href="products-admin.php" style="font-size:12px;color:var(--muted);">Cancel Edit</a>
      <?php endif; ?>
    </div>

    <?php if($edit_product): ?>
    <form method="POST">
      <input type="hidden" name="id" value="<?php echo $edit_product['id']; ?>">
      <input type="text" name="name" value="<?php echo htmlspecialchars($edit_product['name']); ?>" placeholder="Product Name" required>
      <input type="number" step="0.01" name="price" value="<?php echo $edit_product['price']; ?>" placeholder="Price" required>
      <input type="number" name="stock" value="<?php echo $edit_product['stock']; ?>" placeholder="Stock Quantity" required>
      <input type="text" name="image" value="<?php echo htmlspecialchars($edit_product['image']); ?>" placeholder="Image filename" required>
      <button type="submit" name="update" class="add-btn">Save Changes</button>
    </form>
    <?php else: ?>
    <form method="POST">
      <input type="text" name="name" placeholder="Product Name" required>
      <input type="number" step="0.01" name="price" placeholder="Price" required>
      <input type="number" name="stock" placeholder="Initial Stock" value="10" required>
      <input type="text" name="image" placeholder="Image filename" required>
      <button type="submit" name="add" class="add-btn">Add Product</button>
    </form>
    <?php endif; ?>
  </div>

  <!-- PRODUCT TABLE -->
  <div class="card">
    <div class="card-header">
      <h3>Product List</h3>
    </div>

    <table>
      <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Name</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Toggle Stock</th>
        <th>Action</th>
      </tr>

      <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td>#<?php echo $row['id']; ?></td>
        <td>
          <img src="images/<?php echo $row['image']; ?>" class="product-img">
        </td>
        <td><?php echo $row['name']; ?></td>
        <td>&#8377;<?php echo number_format($row['price'],2); ?></td>
        <td>
          <?php if($row['stock'] > 0): ?>
            <span class="badge badge-in">IN STOCK (<?php echo $row['stock']; ?>)</span>
          <?php else: ?>
            <span class="badge badge-out">OUT OF STOCK</span>
          <?php endif; ?>
        </td>
        <td>
          <a href="?toggle_stock=<?php echo $row['id']; ?>&current=<?php echo $row['stock']; ?>">
            <?php if($row['stock'] > 0): ?>
              <button type="button" class="stock-btn-out">Mark Out of Stock</button>
            <?php else: ?>
              <button type="button" class="stock-btn-in">Set In Stock (10)</button>
            <?php endif; ?>
          </a>
        </td>
        <td style="display:flex;gap:6px;">
          <a href="?edit=<?php echo $row['id']; ?>">
            <button type="button" class="edit-btn">Edit</button>
          </a>
          <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this product?')">
            <button type="button" class="delete-btn">Delete</button>
          </a>
        </td>
      </tr>
      <?php endwhile; ?>

    </table>
  </div>

</div>

</body>
</html>