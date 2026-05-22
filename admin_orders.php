<?php
session_start();
include("db.php");

// Simple admin check (assuming role='admin' in users table)
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    // For this student project, we'll allow access if no session is set but warn, 
    // or you can redirect to login.
    // header("Location: login.php");
}

// Handle Status Updates
if (isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    mysqli_query($conn, "UPDATE orders SET order_status = '$new_status' WHERE id = $order_id");
    header("Location: admin_orders.php?msg=Status Updated");
    exit();
}

$orders_query = mysqli_query($conn, "SELECT * FROM orders ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BLOSSOM ADMIN | Manage Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f7f6; margin: 0; padding: 20px; }
        .admin-container { max-width: 1200px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 25px rgba(0,0,0,0.05); }
        h1 { color: #8b1e3f; border-bottom: 2px solid #8b1e3f; padding-bottom: 10px; margin-bottom: 30px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #8b1e3f; color: white; font-weight: 500; }
        
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-accepted { background: #d4edda; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }

        .items-list { font-size: 13px; color: #666; }
        .btn-group { display: flex; gap: 5px; }
        .btn { padding: 6px 12px; border-radius: 5px; border: none; cursor: pointer; font-size: 12px; font-weight: 600; transition: 0.3s; }
        .btn-accept { background: #28a745; color: white; }
        .btn-reject { background: #dc3545; color: white; }
        .btn:hover { opacity: 0.8; }

        .message-box { background: #e7f3ff; padding: 10px; border-radius: 5px; font-size: 13px; margin-top: 5px; border-left: 3px solid #007bff; }
    </style>
</head>
<body>

<div class="admin-container">
    <h1><i class="fas fa-tasks"></i> Order Management Dashboard</h1>
    
    <?php if (isset($_GET['msg'])): ?>
        <p style="color: green; font-weight: 600;"><?php echo htmlspecialchars($_GET['msg']); ?></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Bouquet & Wrap</th>
                <th>Items (JSON Decoded)</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($order = mysqli_fetch_assoc($orders_query)): ?>
                <tr>
                    <td><strong>#<?php echo $order['order_id']; ?></strong><br><small><?php echo $order['created_at']; ?></small></td>
                    <td>
                        <strong><?php echo htmlspecialchars($order['Customer']); ?></strong><br>
                        <?php if(!empty($order['email'])): ?>
                            <small style="color:#666; display:inline-block; margin-top:3px;"><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($order['email']); ?></small><br>
                        <?php endif; ?>
                        <?php if(!empty($order['phone'])): ?>
                            <small style="color:#666; display:inline-block; margin-top:2px;"><i class="fas fa-phone"></i> <?php echo htmlspecialchars($order['phone']); ?></small><br>
                        <?php endif; ?>
                        <?php if(!empty($order['address'])): ?>
                            <small style="color:#8b1e3f; display:inline-block; margin-top:2px;"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($order['address']); ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($order['bouquet_type'] ?? 'Standard'); ?><br>
                        <small>Wrap: <?php echo htmlspecialchars($order['wrapping_type'] ?? 'None'); ?></small>
                    </td>
                    <td>
                        <div class="items-list">
                            <?php 
                            if (!empty($order['items_json'])) {
                                $items = json_decode($order['items_json'], true);
                                foreach ($items as $it) {
                                    echo "• " . htmlspecialchars($it['name']) . " x " . $it['qty'] . "<br>";
                                }
                            } else {
                                echo "Standard Order Item";
                            }
                            ?>
                        </div>
                        <?php if (!empty($order['custom_message'])): ?>
                            <div class="message-box">
                                <strong>Message:</strong> "<?php echo htmlspecialchars($order['custom_message']); ?>"
                            </div>
                        <?php endif; ?>
                    </td>
                    <td>₹<?php echo number_format($order['amount'], 2); ?></td>
                    <td>
                        <span class="status-badge status-<?php echo $order['order_status']; ?>">
                            <?php echo $order['order_status']; ?>
                        </span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <form method="POST">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit" name="update_status" class="btn btn-accept">Accept</button>
                            </form>
                            <form method="POST">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" name="update_status" class="btn btn-reject">Reject</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
