<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Lab8_4.1 - Tìm kiếm sách theo tên (bảng book)</title>
    <style>
    body {
        font-family: Arial, sans-serif;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        margin: 10px 0;
    }

    th,
    td {
        border: 1px solid #ccc;
        padding: 8px;
        text-align: left;
        vertical-align: top;
    }

    img {
        max-width: 100px;
    }

    .form {
        margin-bottom: 16px;
    }

    .pagination a {
        margin: 4px;
        padding: 4px 8px;
        border: 1px solid #ccc;
        text-decoration: none;
    }

    .pagination strong {
        margin: 0 4px;
        padding: 4px 8px;
        border: 1px solid #000;
        background: #eee;
    }
    </style>
</head>

<body>
    <h2>Tìm kiếm sách theo tên</h2>
    <form method="get" action="" class="form">
        <label>Nhập tên sách:</label>
        <input type="text" name="keyword"
            value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>" required>
        <input type="submit" value="Tìm kiếm">
    </form>
    <hr>

    <?php
        // KẾT NỐI CSDL (PDO)
        if ($_SERVER['HTTP_HOST'] === 'localhost') {
            require "connect.local.php";
        } else {
            require "connect.prod.php";
        }

        // XỬ LÝ TÌM KIẾM TRONG BẢNG book
        if (isset($_GET['keyword'])) {
            $search = trim($_GET['keyword']);

                         // Thiết lập phân trang
            $limit  = 5; // số sách mỗi trang
            $page   = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $offset = ($page - 1) * $limit;

            // Đếm tổng số kết quả
            $countSql = "SELECT COUNT(*) FROM book WHERE book_name LIKE :kw";
            $stm      = $pdo->prepare($countSql);
            $stm->bindValue(":kw", "%$search%");
            $stm->execute();
            $totalRows  = $stm->fetchColumn();
            $totalPages = ceil($totalRows / $limit);

            // Lấy dữ liệu theo trang
            $sql = "SELECT book_id, book_name, price, description, img, pub_id, cat_id
                FROM book
                WHERE book_name LIKE :kw
                LIMIT :limit OFFSET :offset";
            $stm = $pdo->prepare($sql);
            $stm->bindValue(":kw", "%$search%");
            $stm->bindValue(":limit", $limit, PDO::PARAM_INT);
            $stm->bindValue(":offset", $offset, PDO::PARAM_INT);
            $stm->execute();
            $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

            if (empty($rows)) {
                echo "<p>Không tìm thấy sách nào với từ khóa <strong>" . htmlspecialchars($search) . "</strong>.</p>";
            } else {
                echo "<h3>Kết quả cho từ khóa: <em>" . htmlspecialchars($search) . "</em></h3>";
                echo "<table>";
                echo "<tr>
                <th>Mã sách</th>
                <th>Tên sách</th>
                <th>Giá</th>
                <th>Mô tả</th>
                <th>Hình</th>
                <th>Mã NXB</th>
                <th>Mã loại</th>
              </tr>";
                foreach ($rows as $r) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($r['book_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($r['book_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($r['price']) . "</td>";
                    echo "<td>" . nl2br(htmlspecialchars($r['description'])) . "</td>";

                    $imgPath = "lab8_5/image/book/" . $r['img'];
                    echo "<td>";
                    if (! empty($r['img'])) {
                        echo "<img src='" . htmlspecialchars($imgPath) . "' alt='" . htmlspecialchars($r['book_name']) . "'>";
                    } else {
                        echo "(Không có hình)";
                    }
                    echo "</td>";
                    echo "<td>" . htmlspecialchars($r['pub_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($r['cat_id']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";

                // Hiển thị phân trang
                if ($totalPages > 1) {
                    echo "<div class='pagination'>";
                    for ($i = 1; $i <= $totalPages; $i++) {
                        if ($i == $page) {
                            echo "<strong>$i</strong>";
                        } else {
                            echo "<a href='?keyword=" . urlencode($search) . "&page=$i'>$i</a>";
                        }
                    }
                    echo "</div>";
                }
            }
        }
    ?>
</body>

</html>