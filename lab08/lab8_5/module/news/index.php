<?php
// nạp class News
loadClass("News");
$newsObj = new News($pdo);

// lấy action
$ac = getIndex("ac", "list");

$content = ""; // nội dung sẽ đưa ra view

if ($ac == "list") {
    $rows = $newsObj->list(); // giả sử trả về mảng tin tức
    if (empty($rows)) {
        $content = "<p>Không có tin tức nào.</p>";
    } else {
        $content = "<h3>Danh sách tin tức</h3><ul>";
        foreach ($rows as $r) {
            $content .= "<li><a href='index.php?mod=news&ac=detail&id=" . $r['id'] . "'>"
            . htmlspecialchars($r['title']) . "</a></li>";
        }
        $content .= "</ul>";
    }
}

if ($ac == "detail") {
    $id  = getIndex("id");
    $row = $newsObj->detail($id); // giả sử trả về 1 bản ghi
    if ($row) {
        $content = "<h3>" . htmlspecialchars($row['title']) . "</h3>";
        $content .= "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
    } else {
        $content = "<p>Không tìm thấy tin tức.</p>";
    }
}

// đưa ra view
require "view/index.php";
