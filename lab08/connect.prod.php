<?php
define("SERVERNAME", "sql313.infinityfree.com");
define("USERNAME", "if0_40609708");
define("PASSWORD", "androissKZ12345");
define("DBNAME", "if0_40609708_book_store");
try {
    // Tạo kết nối PDO
    $dsn = "mysql:host=" . SERVERNAME . ";dbname=" . DBNAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, USERNAME, PASSWORD);

    // Thiết lập chế độ lỗi
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
