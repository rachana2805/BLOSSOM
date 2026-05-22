<?php
$ch = curl_init('http://localhost/blossom/blossom2/admin.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, ['email'=>'admin@blossom.com', 'password'=>'admin123']);
$response = curl_exec($ch);
$info = curl_getinfo($ch);
print_r($info);
curl_close($ch);
?>
