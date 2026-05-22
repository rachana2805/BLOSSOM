<?php
include("db.php");
$sql = "ALTER TABLE orders 
        ADD COLUMN email VARCHAR(100) NULL AFTER Customer, 
        ADD COLUMN phone VARCHAR(20) NULL AFTER email, 
        ADD COLUMN address TEXT NULL AFTER phone;";
if ($conn->query($sql) === TRUE) {
    echo "Columns added successfully";
} else {
    echo "Error: " . $conn->error;
}
?>
