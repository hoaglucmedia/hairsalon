<?php
// Khởi động session
session_start();

// Lấy tên miền hiện tại (Ví dụ: localhost hoặc albarber.rf.gd)
$host = $_SERVER['HTTP_HOST'];

// KIỂM TRA MÔI TRƯỜNG
if ($host == 'localhost' || $host == '127.0.0.1') {
    // === CẤU HÌNH LOCALHOST (WampServer) ===
    $servername = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname = "hairsalon";
} else {
    // === CẤU HÌNH INFINITYFREE (Hosting) ===
    // QUAN TRỌNG: Bạn phải vào VistaPanel -> MySQL Databases để lấy thông tin này
    
    $servername = "sql305.infinityfree.com"; // Thay bằng "MySQL Hostname"
    $username_db = "if0_40487423";          // Thay bằng "MySQL Username"
    $password_db = "XLHYE6gs94La"; // Mật khẩu đăng nhập hosting
    $dbname = "if0_40487423_shop";     // Thay bằng tên Database trên hosting
}

try {
    // Tạo kết nối PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username_db, $password_db);
    
    // Cấu hình báo lỗi và kiểu dữ liệu trả về
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    // Khi lên hosting, nên ẩn lỗi chi tiết để bảo mật, chỉ hiện thông báo chung
    if ($host == 'localhost') {
        die("Lỗi kết nối Local: " . $e->getMessage());
    } else {
        die("Hệ thống đang bảo trì kết nối (Lỗi Database). Vui lòng quay lại sau.");
    }
}
?>