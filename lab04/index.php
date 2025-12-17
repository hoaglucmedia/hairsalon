<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Danh sách bài Lab</title>
    <link href="../../site/public/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <script src="../../site/public/vendor/bootstrap/js/bootstrap.min.js"></script>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h2>Danh sách bài tập lab04</h2>
            </div>
            <div class="card-body">
                <p class="text-center">Bấm vào từng bài để mở file PHP tương ứng:</p>
                <div class="row row-cols-2 row-cols-md-3 g-3">
                    <?php
                        $files = [
                            "lab4_1.php", "lab4_2.php", "lab4_3.php", "lab4_4.php",
                            "lab5_1.php", "lab5_2.php", "lab5_3.php",
                        ];
                        foreach ($files as $file) {
                            echo '<div class="col text-center">
                  <a href="' . $file . '" class="btn btn-outline-primary w-100">' . $file . '</a>
                </div>';
                        }
                    ?>
                </div>
            </div>
           
        </div>
    </div>

</body>

</html>