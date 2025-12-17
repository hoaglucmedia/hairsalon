<?php
// 5.1 Tìm kiếm và thay thế dữ liệu

// a. Lọc tất cả các link trong trang web
echo "<h2>5.1a - Danh sách link</h2>";
$url  = 'https://stu.edu.vn/';
$html = file_get_contents($url);

if ($html === false) {
    echo "Không tải được trang $url <br>";
} else {
    echo "Tải trang thành công: $url <br>";
}

$doc = new DOMDocument();
libxml_use_internal_errors(true);
$doc->loadHTML($html);
libxml_clear_errors();

$links    = $doc->getElementsByTagName('a');
$count    = 0;
$maxLinks = 30;
foreach ($links as $link) {
    echo $link->getAttribute('href') . "<br>";
    $count++;
    if ($count >= $maxLinks) {
        break;
    }

}

// b. Lọc email và số điện thoại
echo "<h2>5.1b - Email và số điện thoại</h2>";
$url  = 'https://dantri.com.vn/';
$html = file_get_contents($url);

if ($html === false) {
    echo "Không thể tải nội dung trang $url <br>";
} else {
    echo "Tải thành công trang $url <br>";
}

$email_pattern = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}/';
$phone_pattern = '/(\+?[0-9]{1,4}[ -]?)?(\(?\d{3}\)?[ -]?)?\d{3}[ -]?\d{4}/';

preg_match_all($email_pattern, $html, $emails);
preg_match_all($phone_pattern, $html, $phones);

echo "<pre>";
print_r($emails[0]);
echo "</pre>";

$count     = 0;
$maxPhones = 20;
foreach ($phones[0] as $phone) {
    echo($count + 1) . ": $phone <br>";
    $count++;
    if ($count >= $maxPhones) {
        break;
    }

}

// c. Kiểm tra tên hình ảnh hợp lệ
echo "<h2>5.1c - Kiểm tra tên hình ảnh</h2>";
$html = file_get_contents('https://dantri.com.vn/');
$doc  = new DOMDocument();
libxml_use_internal_errors(true);
$doc->loadHTML($html);
libxml_clear_errors();

$images       = $doc->getElementsByTagName('img');
$file_pattern = '/^[a-zA-Z0-9_-]+\.(jpg|jpeg|png|gif)$/';

$count    = 0;
$maxCount = 20;
foreach ($images as $image) {
    $src       = $image->getAttribute('src');
    $file_name = basename($src);

    if (preg_match($file_pattern, $file_name)) {
        echo " Hình ảnh hợp lệ: $file_name <br>";
    } else {
        echo "Hình ảnh không hợp lệ: $file_name <br>";
    }
    $count++;
    if ($count >= $maxCount) {
        break;
    }

}
