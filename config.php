<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Danh sách các địa chỉ được coi là Localhost
$whitelist = array(
    '127.0.0.1',
    '::1',
    'localhost'
);

// Kiểm tra: Nếu tên miền hiện tại nằm trong whitelist thì là Local, ngược lại là Online
if (in_array($_SERVER['HTTP_HOST'], $whitelist)) {
    
    // --- CẤU HÌNH LOCALHOST (XAMPP) ---
    $servername  = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname      = "hairsalon";

} else {
    
    // --- CẤU HÌNH INFINITYFREE (ONLINE) ---
    // Lưu ý: Hostname PHẢI LÀ sql305... (Lấy từ ảnh bạn gửi)
    // Tuyệt đối không để "localhost" ở đây
    $servername  = "sql305.infinityfree.com"; 
    $username_db = "if0_40487423";
    $password_db = "XLHYE6gs94La";
    $dbname      = "if0_40487423_shop";
}

try {
    $conn = new PDO(
        "mysql:host=$servername;dbname=$dbname;charset=utf8",
        $username_db,
        $password_db
    );
    // Báo lỗi dạng Exception để dễ bắt lỗi
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Mặc định trả về mảng kết hợp (key là tên cột)
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Đoạn này giúp bạn debug nhanh:
    // Nếu chạy Local thì hiện lỗi chi tiết
    if (in_array($_SERVER['HTTP_HOST'], $whitelist)) {
        die("Lỗi kết nối Local: " . $e->getMessage());
    } else {
        // Nếu chạy Online thì báo lỗi chung chung để bảo mật
        die("Lỗi kết nối Server. Vui lòng kiểm tra lại cấu hình Database.");
    }
}
?>