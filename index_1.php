<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trang TH - Danh sách Lab</title>
    <link href="../site/public/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <script src="../site/public/vendor/bootstrap/js/bootstrap.min.js"></script>

    <style>
        body {
            background: linear-gradient(120deg, #c9e8ff, #ffffff);
            font-family: "Poppins", sans-serif;
        }

        .card {
            border-radius: 18px;
            overflow: hidden;
            border: none;
            background: #ffffff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card-header {
            background: linear-gradient(90deg, #0d6efd, #0aa5ff);
            padding: 28px 0;
        }

        h2 {
            font-weight: 700;
            letter-spacing: 1px;
        }

        .btn-outline-success {
            border-width: 2px;
            font-weight: 600;
            padding: 12px 0;
            border-radius: 12px;
            background: white;
            transition: all 0.3s ease;
            font-size: 18px;
        }

        .btn-outline-success:hover {
            background: #198754;
            color: #fff;
            box-shadow: 0 6px 15px rgba(25, 135, 84, 0.4);
            transform: translateY(-3px);
        }

        .card-body p {
            font-size: 17px;
            color: #444;
        }

        .card-footer {
            background: #f8f9fa;
            padding: 22px;
            font-size: 17px;
            border-top: 1px solid #e3e3e3;
        }

        .card-footer b {
            color: #0d6efd;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header text-white text-center">
                <h2>Các Bài Lab Thực Hành</h2>
            </div>
            <div class="card-body text-center">
                

                <div class="d-grid gap-3 col-8 mx-auto">
                    <a href="lab02/kqlab2" class="btn btn-outline-success btn-lg">Lab 02</a>
                    <a href="lab03" class="btn btn-outline-success btn-lg">Lab 03</a>
                    <a href="lab04" class="btn btn-outline-success btn-lg">Lab 04</a>
                    <a href="lab05" class="btn btn-outline-success btn-lg">Lab 05</a>
                    <a href="lab06" class="btn btn-outline-success btn-lg">Lab 06</a>
                    <a href="lab07" class="btn btn-outline-success btn-lg">Lab 07</a>
                    <a href="lab08" class="btn btn-outline-success btn-lg">Lab 08</a>
                </div>

            </div>

            <div class="card-footer text-muted text-center">
                Họ tên: <b>Nguyễn Hoàng Lực</b><br>
                MSSV: <b>DH52201040</b><br>
                Lớp: <b>D22_TH13</b>
            </div>
        </div>
    </div>

</body>

</html>
