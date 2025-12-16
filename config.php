<?php

session_start();


$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "hairsalon";

try {
  
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username_db, $password_db);
    
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
 
    die("Kết nối thất bại: " . $e->getMessage());
}
?>