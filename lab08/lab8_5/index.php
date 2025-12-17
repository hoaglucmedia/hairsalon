<?php
require "include/function.php";
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    require "../lab8_5/config/connect.local.php";
} else {
    require "../lab8_5/config/connect.prod.php";
}
require "database/Db.class.php";
// Khởi tạo đối tượng DB để dùng trong toàn hệ thống
$db = new DB($pdo);
// ==================== ROUTER ====================
$mod = $_GET['mod'] ?? '';
$ac  = $_GET['ac'] ?? '';

switch ($mod) {
    case 'book':
        // gọi module book
        require "module/book/index.php";
        break;

    case 'news':
        // gọi module news
        require "module/news/index.php";
        break;

    default:
        // trang mặc định (home)
        require "view/index.php";
        break;
}
