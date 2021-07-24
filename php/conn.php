<?php

$servername = "localhost";
$db = "id17109168_yromem_db";
$username = "id17109168_yromem_admin";
$pw = "aeaLKT(]l%NbLH0X";
$port = "3306"; // 000webhost using 3306. localhost using 3308

try {
    $conn = new PDO("mysql:host=$servername;dbname=$db;port=$port;charset=utf8;", $username, $pw);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
