<?php
$url = 'https://vnexpress.net/the-thao';
$ch  = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$content = curl_exec($ch);
curl_close($ch);

$pattern = '/<h3 class="title-news">.*<\/h3>/imsU';
preg_match_all($pattern, $content, $arr);
echo "<h1>Danh sách link có class title-news trên vn express</h1>";
echo "<table border='1' width='80%' align='center'>";
echo "<tr><th>Tiêu đề</th><th>Link</th></tr>";

foreach ($arr[0] as $item) {
    if (preg_match('/<a[^>]*href="([^"]+)"[^>]*>(.*?)<\/a>/ims', $item, $match)) {
        $link  = $match[1];
        $title = strip_tags($match[2]);
        echo "<tr><td>$title</td><td><a href='$link' target='_blank'>$link</a></td></tr>";
    }
}
echo "</table>";
echo $content;
