<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lab5_5</title>
</head>

<body>
    <?php
    function tongCacSoTrongChuoi($chuoi)
    {
        // Tìm tất cả các dãy số trong chuỗi
        preg_match_all('/\d+/', $chuoi, $matches);

        // Chuyển từng chuỗi số thành số nguyên và tính tổng
        $tong = 0;
        foreach ($matches[0] as $so) {
            $tong += (int)$so;
        }

        return $tong;
    }

    // Ví dụ
    $chuoi = "ngay20thang6nam2015";
    echo "Tổng các số trong chuỗi '$chuoi' là: " . tongCacSoTrongChuoi($chuoi);
    ?>

</body>

</html>