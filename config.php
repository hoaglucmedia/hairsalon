<?php
ob_start();


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


date_default_timezone_set('Asia/Ho_Chi_Minh');

// Lấy tên miền hiện tại
$host = $_SERVER['HTTP_HOST'];

// KIỂM TRA MÔI TRƯỜNG
if ($host == 'localhost' || $host == '127.0.0.1') {
    // === LOCALHOST (WampServer) ===
    $servername = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname = "hairsalon";
} else {

    $servername = "sql305.infinityfree.com"; 
    $username_db = "if0_40487423";          
    $password_db = "XLHYE6gs94La"; 
    $dbname = "if0_40487423_shop";     
}

try {
   
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username_db, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Tắt chế độ mô phỏng Prepare (giúp bảo mật hơn)
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
} catch(PDOException $e) {
    // Xử lý lỗi kết nối
    if ($host == 'localhost') {
        die("Lỗi kết nối Local: " . $e->getMessage());
    } else {
      
        error_log("Connection failed: " . $e->getMessage());
        die("Hệ thống đang bảo trì kết nối. Vui lòng quay lại sau.");
    }
}
?>