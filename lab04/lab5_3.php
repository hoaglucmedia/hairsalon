<?php
$questions = [
    "Câu 1: 2 + 2 bằng bao nhiêu?",
    "Câu 2: Thủ đô của Việt Nam là đâu?",
    "Câu 3: Chó có mấy chân?",
    "Câu 4: Ai là tác giả của bài thơ 'Tỏ lòng'?",
    "Câu 5: Đâu là hành tinh thứ 3 trong hệ mặt trời?",
    "Câu 6: 5 x 6 bằng bao nhiêu?",
    "Câu 7: Nước nào có diện tích lớn nhất thế giới?",
    "Câu 8: Người sáng lập Microsoft là ai?",
    "Câu 9: Năm 2020, dịch bệnh nào gây ra khủng hoảng toàn cầu?",
    "Câu 10: 1kg gạo có bao nhiêu hạt?"
];

$n = count($questions); // số lượng câu hỏi
$m = 5; // số câu lấy ngẫu nhiên (có thể cho người dùng nhập)

// Kiểm tra m hợp lệ
if ($m >= $n || $m <= 0) {
    die("Số câu hỏi m phải nhỏ hơn $n và lớn hơn 0.");
}

// Lấy m chỉ số ngẫu nhiên trong khoảng 0 -> n-1
$randomKeys = array_rand($questions, $m);

echo "<h2>Đề thi ngẫu nhiên ($m câu):</h2>";

// array_rand trả về 1 số nếu m = 1, nên cần đảm bảo là mảng
if (!is_array($randomKeys)) {
    $randomKeys = [$randomKeys];
}

foreach ($randomKeys as $key) {
    echo "<p>" . $questions[$key] . "</p>";
}
?>
