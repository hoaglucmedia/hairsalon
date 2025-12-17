<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lab5_4</title>
</head>

<body>
<?php
function veHinhChuNhatRong($d, $r) {
    for ($i = 1; $i <= $r; $i++) {
        for ($j = 1; $j <= $d; $j++) {
            // In dấu * ở viền ngoài
            if ($i == 1 || $i == $r || $j == 1 || $j == $d) {
                echo "* ";
            } else {
                echo "&nbsp;"; 
                echo "&nbsp;"; 
                echo "&nbsp;"; 

               
            }
        }
        echo "<br>"; // Xuống dòng
    }
}

// Ví dụ: d = 6, r = 4
veHinhChuNhatRong(9, 7);
?>


</body>

</html>