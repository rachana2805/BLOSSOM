<?php
date_default_timezone_set('Asia/Kolkata');
$conn = new mysqli("localhost", "root", "", "blossom_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
