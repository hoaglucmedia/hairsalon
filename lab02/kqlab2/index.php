<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Danh sách bài tập</title>
    <link href="../../../site/public/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <script src="../../../site/public/vendor/bootstrap/js/bootstrap.min.js"></script>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h2>Danh sách bài tập lab02</h2>
            </div>
            <div class="card-body">
                <p class="text-center">Bấm vào từng bài để mở file PHP tương ứng:</p>
                <div class="row row-cols-2 row-cols-md-3 g-3">
                    <?php
                        $files = [
                            "4_1.php", "4_2.php", "4_3.php", "4_4.php", "4_5.php", "4_6.php", "4_6b.php",
                            "4_7.php", "4_7b.php", "4_8.php", "4_9.php", "5_1.php", "5_2.php", "5_3.php",
                        ];
                        foreach ($files as $file) {
                            echo '<div class="col text-center">
                  <a href="' . $file . '" class="btn btn-outline-success w-100">' . $file . '</a>
                </div>';
                        }
                    ?>
                </div>
            </div>
          
        </div>
    </div>

</body>

</html>