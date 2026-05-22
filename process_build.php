<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bouquet_type = mysqli_real_escape_string($conn, $_POST['bouquet_type']);
    $wrapping_type = mysqli_real_escape_string($conn, $_POST['wrapping_type']);
    $items_json = $_POST['items_json'];
    $custom_message = mysqli_real_escape_string($conn, $_POST['custom_message']);
    $total_price = floatval($_POST['total_price']);
    $customer_name = $_SESSION['user_name'] ?? 'Guest';
    $order_id_str = "BLD" . rand(1000, 9999);

    // Validation
    $parts = json_decode($items_json, true);
    if (!is_array($parts)) $parts = [];
    
    // Server-side limits and stock validation against products table
    $totalChocolates = 0;
    $totalHotWheels = 0;
    
    foreach ($parts as $part) {
        if(isset($part['id'])) {
            $id = intval($part['id']);
            $qty = intval($part['qty']);
            
            $check_query = mysqli_query($conn, "SELECT category, stock FROM products WHERE id=$id");
            if ($check_query && mysqli_num_rows($check_query) > 0) {
                $db_item = mysqli_fetch_assoc($check_query);
                if ($qty > $db_item['stock']) {
                    die("Error: Item " . $part['name'] . " is out of stock or quantity exceeds availability.");
                }
                if ($db_item['category'] == 'Chocolates') $totalChocolates += $qty;
                if ($db_item['category'] == 'Hot Wheels') $totalHotWheels += $qty;
            }
        }
    }

    if ($totalChocolates > 20) die("Error: Maximum 20 chocolates allowed.");
    if ($totalHotWheels > 10) die("Error: Maximum 10 Hot Wheels allowed.");

    // Insert into items table
    $customization_details = json_encode([
        'bouquet_type' => $bouquet_type,
        'wrapping_type' => $wrapping_type,
        'parts' => $parts
    ]);
    
    $item_name = "Custom $bouquet_type";
    $insert_item_sql = "INSERT INTO items (name, description, price, customization_details) VALUES ('" . mysqli_real_escape_string($conn, $item_name) . "', '$custom_message', $total_price, '" . mysqli_real_escape_string($conn, $customization_details) . "')";
    
    if (mysqli_query($conn, $insert_item_sql)) {
        $item_id = mysqli_insert_id($conn);
        
        // Insert into orders table with item_id and item_type
        $sql = "INSERT INTO orders (order_id, item_id, item_type, Customer, amount, payment_method, bouquet_type, wrapping_type, items_json, custom_message, order_status) 
                VALUES ('$order_id_str', $item_id, 'custom', '$customer_name', $total_price, 'Cash on Delivery', '$bouquet_type', '$wrapping_type', '" . mysqli_real_escape_string($conn, $items_json) . "', '$custom_message', 'pending')";

        if (mysqli_query($conn, $sql)) {
            $last_order_id = mysqli_insert_id($conn);
            
            // Deduct stock from products
            foreach ($parts as $part) {
                if(isset($part['id'])) {
                    $id = intval($part['id']);
                    $qty = intval($part['qty']);
                    mysqli_query($conn, "UPDATE products SET stock = stock - $qty WHERE id=$id");
                }
            }
            
            header("Location: bill.php?order_id=" . $last_order_id);
            exit();
        } else {
            echo "Error inserting order: " . mysqli_error($conn);
        }
    } else {
        echo "Error creating custom item: " . mysqli_error($conn);
    }
}
?>
