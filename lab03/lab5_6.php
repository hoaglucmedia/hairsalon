<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lab5_6</title>
</head>

<body>
    <?php
    function loaiBoKhoangTrangDu($chuoi)
    {
        // Xóa khoảng trắng ở đầu và cuối
        $chuoi = trim($chuoi);
        // Thay nhiều khoảng trắng liên tiếp bằng 1 khoảng trắng
        $chuoi = preg_replace('/\s+/', ' ', $chuoi);
        return $chuoi;
    }

    // Ví dụ
    $chuoi = "   Đây   là   một    chuỗi   có   nhiều   khoảng   trắng.   ";
    echo "Chuỗi sau khi xử lý: '" . loaiBoKhoangTrangDu($chuoi) . "'";
    ?>

</body>

</html>