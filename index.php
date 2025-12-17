<?php
// 1. Nhúng các file cấu hình và logic xử lý
require_once 'config.php';
require_once 'logic.php';

// 2. Nhúng phần đầu trang (Menu, CSS)
require_once 'includes/header.php';
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

$highlight_id = isset($_GET['highlight']) ? $_GET['highlight'] : null;

// Danh sách các trang hợp lệ được phép truy cập
// [QUAN TRỌNG] Đã thêm 'stylist' vào danh sách này
$allowed_pages = ['home', 'services', 'products', 'history', 'profile', 'admin', 'login', 'stylist'];

// Kiểm tra xem trang khách yêu cầu có nằm trong danh sách không
if (in_array($page, $allowed_pages)) {
    // Nếu file trang tồn tại trong thư mục pages/ thì nhúng vào
    if (file_exists("pages/$page.php")) {
        include "pages/$page.php";
    } else {
        echo "<div class='container' style='text-align:center; padding:50px; min-height:50vh'>
                <h2 style='color:var(--gold)'>404 - Trang đang xây dựng</h2>
                <p>File giao diện chưa tồn tại.</p>
              </div>";
    }
} else {
    // Nếu trang không hợp lệ, mặc định quay về trang chủ
    include "pages/home.php";
}

// 4. Nhúng phần chân trang (Footer, Modal Giỏ hàng, Script)
require_once 'includes/footer.php';
?>