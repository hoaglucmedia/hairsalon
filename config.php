<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nếu đang chạy trên InfinityFree
if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'epizy.com') !== false) {
    // --- INFINITYFREE ---
$servername  = "localhost";               // QUAN TRỌNG
$username_db = "if0_40487423";
$password_db = "XLHYE6gs94La";
$dbname      = "if0_40487423_shop";          // Tên Database trên host
} else {
    // --- LOCALHOST (XAMPP) ---
    $servername  = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname      = "hairsalon";
}

try {
    $conn = new PDO(
        "mysql:host=$servername;dbname=$dbname;charset=utf8",
        $username_db,
        $password_db
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi kết nối cơ sở dữ liệu.");
}
?>
