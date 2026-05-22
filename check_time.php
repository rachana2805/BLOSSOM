<?php
include("db.php");
$php_time = date('Y-m-d H:i:s');
$res = $conn->query("SELECT NOW() as mysql_time");
$mysql_time = $res->fetch_assoc()['mysql_time'];

echo "PHP Time: $php_time\n";
echo "MySQL Time: $mysql_time\n";
echo "PHP Timestamp: ".time()."\n";
echo "MySQL parsed: ".strtotime($mysql_time)."\n";
?>
