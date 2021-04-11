<?php

$servername = "localhost";
$db = "id16429717_incupe";
$username = "id16429717_incupe_dev";
$pw = "SO4o-s3hB0y{@-l%";
// $db = "id16429686_yromem_db";
// $username = "id16429686_yromem_admin";
// $pw = "aeaLKT(]l%NbLH0X";
// $port = "3306";

try {
    // $conn = new PDO("mysql:host=$servername;dbname=$db;port=$port;charset=utf8;", $username, $pw);
    $conn = new PDO("mysql:host=$servername;dbname=$db;charset=utf8;", $username, $pw);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
