<?php
define("SERVERNAME", "sql313.infinityfree.com");
define("USERNAME", "if0_40609708");
define("PASSWORD", "androissKZ12345");
define("DBNAME", "if0_40609708_book_store");
define('ROOT', dirname(dirname(__FILE__)));
//Thu muc tuyet doi truoc cua config; c:/wamp/www/lab/
define("BASE_URL", "http://" . $_SERVER['SERVER_NAME'] . "/lab/"); //dia chi website
try {
    // Tạo kết nối PDO
    $dsn = "mysql:host=" . SERVERNAME . ";dbname=" . DBNAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, USERNAME, PASSWORD);

    // Thiết lập chế độ lỗi
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
