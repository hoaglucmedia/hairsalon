<?php
header("Content-Type: text/html; charset=UTF-8");

// Hàm tải HTML bằng file_get_contents
function fetchHtmlFileGet($url)
{
    $html = file_get_contents($url);
    if (! $html) {
        echo "❌ Không thể tải trang $url<br>";
        return null;
    }
    echo "✔️ Tải thành công trang: $url<br>";
    return $html;
}

// Hàm tải HTML bằng cURL
function fetchHtmlCurl($url)
{
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT      => "Mozilla/5.0 (Windows NT 10.0; Win64; x64)",
    ]);
    $html = curl_exec($ch);
    curl_close($ch);
    if (! $html) {
        echo "❌ Không thể tải trang $url<br>";
        return null;
    }
    echo "✔️ Tải thành công trang: $url<br>";
    return $html;
}

// Chuẩn hóa link tương đối thành tuyệt đối
function normalizeUrl($href, $base)
{
    if (strpos($href, '//') === 0) {
        return 'https:' . $href;
    }
    if (strpos($href, '/') === 0) {
        return rtrim($base, '/') . $href;
    }
    return $href;
}

// Hàm phân tích DOM và in ra danh sách link
function printLinks($html, $xpathQuery, $label, $baseUrl)
{
    if (! $html) {
        return;
    }

    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML($html);
    libxml_clear_errors();
    $xpath = new DOMXPath($doc);

    $nodes = $xpath->query($xpathQuery);

    echo "<h2>Tiêu đề trang $label:</h2>";
    if ($nodes->length > 0) {
        foreach ($nodes as $node) {
            $title = trim($node->nodeValue);
            $href  = $node->getAttribute('href');
            $link  = normalizeUrl($href, $baseUrl);
            if ($title !== '' && $link !== '') {
                echo "<p><a href='$link' target='_blank'>$title</a></p>";
            }
        }
    } else {
        echo "<p>Không tìm thấy link nào cho $label. Kiểm tra lại XPath.</p>";
    }
}

// a) STU (demo: lấy tất cả link <a>)
$html = fetchHtmlFileGet("https://stu.edu.vn/");
printLinks($html, '//a', "STU", "https://stu.edu.vn");

// b) Dân trí (tiêu đề bài viết trong h2.article-title)
$html = fetchHtmlFileGet("https://dantri.com.vn/");
printLinks($html, '//h3[@class="article-title"]/a', "Dân trí", "https://dantri.com.vn");

// c) VnExpress (dùng cURL, chuyên mục Thời sự)
$html = fetchHtmlCurl("https://vnexpress.net/thoi-su");
printLinks($html, '//h3[@class="title-news"]/a', "VnExpress", "https://vnexpress.net");
