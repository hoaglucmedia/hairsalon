<?php
// Nạp class Book
loadClass("Book");
$bookObj = new Book($pdo);

// Lấy action từ URL, mặc định là list
$ac = getIndex("ac", "list");

$content = ""; // nội dung sẽ đưa ra view

if ($ac == "list") {
    $cat_id = getIndex("cat_id", null);
    $pub_id = getIndex("pub_id", null);

    // Nếu có cat_id thì lọc theo loại
    if ($cat_id) {
        $rows  = $bookObj->listByCat($cat_id);
        $title = "Danh sách sách theo loại " . htmlspecialchars($cat_id);
    }
    // Nếu có pub_id thì lọc theo nhà xuất bản
    elseif ($pub_id) {
        $rows  = $bookObj->listByPub($pub_id);
        $title = "Danh sách sách theo NXB " . htmlspecialchars($pub_id);
    }
    // Nếu không có tham số thì lấy tất cả
    else {
        $rows  = $bookObj->list();
        $title = "Danh sách tất cả sách";
    }

    if (empty($rows)) {
        $content = "<p>Không có sách nào.</p>";
    } else {
        $content = "<h3>$title</h3><ul>";
        foreach ($rows as $r) {
            $content .= "<li><a href='index.php?mod=book&ac=detail&book_id=" . $r['book_id'] . "'>"
            . htmlspecialchars($r['book_name']) . "</a> - "
            . number_format($r['price']) . "đ</li>";
        }
        $content .= "</ul>";
    }
}

if ($ac == "detail") {
    $book_id = getIndex("book_id");
    $rows    = $bookObj->detail($book_id); // giả sử có phương thức detail
    if ($rows) {
        $content = "<h3>" . htmlspecialchars($rows['book_name']) . "</h3>";
        $content .= "<p>Giá: " . number_format($rows['price']) . "đ</p>";
        $content .= "<p>" . nl2br(htmlspecialchars($rows['description'])) . "</p>";
        if (! empty($rows['img'])) {
            $content .= "<img src='lab8_5/image/book/" . htmlspecialchars($rows['img']) . "' style='max-width:200px'>";
        }
    } else {
        $content = "<p>Không tìm thấy sách.</p>";
    }
}

// Đưa ra view chung
require "view/index.php";
