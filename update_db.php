<?php
include("db.php");

$sql1 = "ALTER TABLE orders ADD COLUMN user_id INT NULL AFTER id";
$sql2 = "ALTER TABLE orders ADD COLUMN delivery_time DATETIME NULL AFTER order_status";

if ($conn->query($sql1) === TRUE) {
    echo "Added user_id column successfully\n";
} else {
    echo "Error adding user_id: " . $conn->error . "\n";
}

if ($conn->query($sql2) === TRUE) {
    echo "Added delivery_time column successfully\n";
} else {
    echo "Error adding delivery_time: " . $conn->error . "\n";
}
?>
