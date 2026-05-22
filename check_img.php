<?php
include("db.php");
$r = $conn->query("SELECT * FROM products WHERE name LIKE '%ferrero%'");
while($row = $r->fetch_assoc()) {
    echo $row['name']." -> ".$row['image']."\n";
}
?>
