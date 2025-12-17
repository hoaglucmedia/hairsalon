<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lab5_3</title>
</head>

<body>
    <?php
    function tongChuSo($chuoi)
    {
        $tong = 0;
        // Duyệt qua từng ký tự trong chuỗi
        for ($i = 0; $i < strlen($chuoi); $i++) {
            $kyTu = $chuoi[$i];
            // Kiểm tra nếu ký tự là chữ số
            if (ctype_digit($kyTu)) {
                $tong += (int)$kyTu;
            }
        }
        return $tong;
    }

    // Ví dụ sử dụng:
    $chuoi = "ngay15thang7nam2015";
    echo "Tổng các chữ số trong chuỗi là: " . tongChuSo($chuoi);
    ?>

</body>

</html>