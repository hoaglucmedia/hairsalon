<?php
// 1. Khởi động Session (nếu chưa có)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Danh sách các địa chỉ được coi là Localhost (Máy cá nhân)
$whitelist_local = array('127.0.0.1', '::1', 'localhost');

// --- LOGIC TỰ ĐỘNG CHỌN MÔI TRƯỜNG ---

// TRƯỜNG HỢP 1: DOCKER (Ưu tiên cao nhất)
// Kiểm tra xem có biến môi trường từ docker-compose truyền vào không
if (getenv('DB_HOST')) {
    // Lấy thông tin từ file docker-compose.yml
    $servername  = getenv('DB_HOST');     // Thường là 'db'
    $username_db = getenv('DB_USER');     // Thường là 'root'
    $password_db = getenv('DB_PASSWORD'); // Pass set trong docker
    $dbname      = getenv('DB_NAME');     // Tên DB set trong docker
} 

// TRƯỜNG HỢP 2: LOCALHOST (XAMPP/WAMP trên Windows)
// Nếu tên miền là localhost mà KHÔNG CÓ biến môi trường Docker
elseif (in_array($_SERVER['HTTP_HOST'], $whitelist_local)) {
    $servername  = "localhost";
    $username_db = "root";
    $password_db = "";            // XAMPP thường không có pass
    $dbname      = "hairsalon";   // Tên database dưới máy bạn
} 

// TRƯỜNG HỢP 3: INFINITYFREE (Hosting Online)
// Nếu không phải 2 trường hợp trên thì chắc chắn là đang chạy trên Host
else {
    $servername  = "sql305.infinityfree.com"; // Hostname chuẩn từ ảnh bạn gửi
    $username_db = "if0_40487423";            // Username chuẩn
    $password_db = "XLHYE6gs94La";            // Password chuẩn
    $dbname      = "if0_40487423_shop";       // DB Name chuẩn
}

// 3. Thực hiện kết nối (Dùng PDO)
try {
    $conn = new PDO(
        "mysql:host=$servername;dbname=$dbname;charset=utf8",
        $username_db,
        $password_db
    );
    
    // Cấu hình báo lỗi
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Xử lý lỗi kết nối
    // Nếu đang ở Docker hoặc Local thì hiện lỗi chi tiết để sửa
    if (getenv('DB_HOST') || in_array($_SERVER['HTTP_HOST'], $whitelist_local)) {
        die("Lỗi kết nối (Dev Mode): " . $e->getMessage());
    } else {
        // Nếu ở InfinityFree thì báo lỗi chung chung để bảo mật
        die("Hệ thống đang bảo trì, vui lòng quay lại sau.");
    }
}
?>