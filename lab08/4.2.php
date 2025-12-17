<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <title>Quản lý sách (book)</title>
    <style>
    #container {
        width: 900px;
        margin: 0 auto;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid #ccc;
        padding: 8px;
        vertical-align: top;
    }

    .form-table td {
        border: none;
    }

    img {
        max-width: 80px;
    }

    .actions a {
        margin-right: 8px;
    }

    .error {
        color: #b00020;
    }

    .ok {
        color: #2e7d32;
    }
    </style>
</head>

<body>
    <div id="container">
        <h2>Quản lý sách (bảng book)</h2>

        <?php
            if ($_SERVER['HTTP_HOST'] === 'localhost') {
                require "connect.local.php"; // tạo $pdo
            } else {
                require "connect.prod.php"; // tạo $pdo
            }
            $lockedBooks = ["td01", "td02", "td03", "td04", "td05", "td06", "th01", "th02", "th03", "th04", "th05", "th06", "th07", "th08", "th09", "th10", "th11", "th12", "th13", "th14", "th15", "th16", "th17", "th18", "vh26"];

            // TẢI danh mục (category) và nhà xuất bản (publisher) cho dropdown
            $cats = [];
            $pubs = [];
            try {
                $stm  = $pdo->query("SELECT cat_id, cat_name FROM category ORDER BY cat_name");
                $cats = $stm->fetchAll(PDO::FETCH_ASSOC);
                $stm  = $pdo->query("SELECT pub_id, pub_name FROM publisher ORDER BY pub_name");
                $pubs = $stm->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo "<p class='error'>Lỗi tải danh mục/NXB: " . htmlspecialchars($e->getMessage()) . "</p>";
            }

            // ==================== XÓA SÁCH ====================
            if (isset($_GET['del'])) {
                $delId = $_GET['del'];
                if (in_array($delId, $lockedBooks)) {
                    echo "<p class='error'>Không được phép xóa sách có mã: " . htmlspecialchars($delId) . "</p>";
                } else {
                    try {
                        // Lấy tên file ảnh trước
                        $stm = $pdo->prepare("SELECT img FROM book WHERE book_id = :id");
                        $stm->execute([":id" => $delId]);
                        $row = $stm->fetch(PDO::FETCH_ASSOC);

                        if ($row && ! empty($row['img'])) {
                            $imgPath = "lab8_5/image/book/" . $row['img'];
                            if (file_exists($imgPath)) {
                                unlink($imgPath); // xóa file ảnh
                            }
                        }

                        // Xóa bản ghi trong DB
                        $stm = $pdo->prepare("DELETE FROM book WHERE book_id = :id");
                        $stm->execute([":id" => $delId]);
                        $n = $stm->rowCount();

                        echo $n > 0 ? "<p class='ok'>Đã xóa sách: " . htmlspecialchars($delId) . "</p>"
                            : "<p class='error'>Không tìm thấy sách cần xóa.</p>";
                    } catch (Exception $e) {
                        echo "<p class='error'>Lỗi xóa: " . htmlspecialchars($e->getMessage()) . "</p>";
                    }
                }
            }

            // ==================== THÊM SÁCH ====================
            if (isset($_POST["sm"])) {
                // xử lý upload ảnh
                $imgName = "";
                if (isset($_FILES['imgfile']) && $_FILES['imgfile']['error'] === UPLOAD_ERR_OK) {
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp'];
                    if (in_array($_FILES['imgfile']['type'], $allowedTypes)) {
                        $tmp    = $_FILES['imgfile']['tmp_name'];
                        $name   = basename($_FILES['imgfile']['name']);
                        $target = "lab8_5/image/book/" . $name;
                        if (move_uploaded_file($tmp, $target)) {
                            $imgName = $name;
                        } else {
                            echo "<p class='error'>Không thể lưu ảnh.</p>";
                        }
                    } else {
                        echo "<p class='error'>File không phải ảnh hợp lệ.</p>";
                    }
                }

                $data = [
                    ":book_id"     => trim($_POST["book_id"] ?? ""),
                    ":book_name"   => trim($_POST["book_name"] ?? ""),
                    ":description" => trim($_POST["description"] ?? ""),
                    ":price"       => (int) ($_POST["price"] ?? 0),
                    ":img"         => $imgName,
                    ":pub_id"      => trim($_POST["pub_id"] ?? ""),
                    ":cat_id"      => trim($_POST["cat_id"] ?? ""),
                ];

                // Kiểm tra cơ bản
                $errs = [];
                foreach (["book_id", "book_name", "pub_id", "cat_id"] as $f) {
                    if ($data[":" . $f] === "") {
                        $errs[] = "Thiếu trường: $f";
                    }

                }
                if ($data[":price"] < 0) {
                    $errs[] = "Giá không hợp lệ";
                }

                if (! empty($errs)) {
                    echo "<p class='error'>" . implode("; ", array_map('htmlspecialchars', $errs)) . "</p>";
                } else {
                    $sql = "INSERT INTO book (book_id, book_name, description, price, img, pub_id, cat_id)
                VALUES (:book_id, :book_name, :description, :price, :img, :pub_id, :cat_id)";
                    try {
                        $stm = $pdo->prepare($sql);
                        $stm->execute($data);
                        $n = $stm->rowCount();
                        echo $n > 0 ? "<p class='ok'>Đã thêm $n sách.</p>" : "<p class='error'>Lỗi thêm sách.</p>";
                    } catch (Exception $e) {
                        echo "<p class='error'>Lỗi thêm: " . htmlspecialchars($e->getMessage()) . "</p>";
                    }
                }
            }

            // ==================== SỬA SÁCH ====================
            if (isset($_POST["sm_update"])) {
                $book_id = $_POST["book_id_fix"] ?? "";
                if (in_array($book_id, $lockedBooks)) {
                    echo "<p class='error'>Không được phép sửa sách có mã: " . htmlspecialchars($book_id) . "</p>";
                } else {
                    // xử lý upload ảnh mới (nếu có)
                    $imgName = $_POST["img_old"] ?? "";
                    if (isset($_FILES['imgfile']) && $_FILES['imgfile']['error'] === UPLOAD_ERR_OK) {
                        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp'];
                        if (in_array($_FILES['imgfile']['type'], $allowedTypes)) {
                            $tmp    = $_FILES['imgfile']['tmp_name'];
                            $name   = basename($_FILES['imgfile']['name']);
                            $target = "lab8_5/image/book/" . $name;
                            if (move_uploaded_file($tmp, $target)) {
                                $imgName = $name;
                            } else {
                                echo "<p class='error'>Không thể lưu ảnh mới.</p>";
                            }
                        } else {
                            echo "<p class='error'>File không phải ảnh hợp lệ.</p>";
                        }
                    }

                    $book_name   = trim($_POST["book_name"] ?? "");
                    $description = trim($_POST["description"] ?? "");
                    $price       = (int) ($_POST["price"] ?? 0);
                    $pub_id      = trim($_POST["pub_id"] ?? "");
                    $cat_id      = trim($_POST["cat_id"] ?? "");

                    if ($book_id === "") {
                        echo "<p class='error'>Thiếu book_id để cập nhật.</p>";
                    } else {
                        $sql = "UPDATE book
                    SET book_name = :book_name,
                        description = :description,
                        price = :price,
                        img = :img,
                        pub_id = :pub_id,
                        cat_id = :cat_id
                    WHERE book_id = :book_id";
                        try {
                            $stm = $pdo->prepare($sql);
                            $stm->execute([
                                ":book_name"   => $book_name,
                                ":description" => $description,
                                ":price"       => $price,
                                ":img"         => $imgName,
                                ":pub_id"      => $pub_id,
                                ":cat_id"      => $cat_id,
                                ":book_id"     => $book_id,
                            ]);
                            echo "<p class='ok'>Đã cập nhật sách: " . htmlspecialchars($book_id) . "</p>";
                        } catch (Exception $e) {
                            echo "<p class='error'>Lỗi cập nhật: " . htmlspecialchars($e->getMessage()) . "</p>";
                        }
                    }
                }
            }

            // Nếu có ?edit=book_id thì tải dữ liệu để hiển thị form sửa
            $editing = null;
            if (isset($_GET["edit"])) {
                $editId = $_GET["edit"];
                try {
                    $stm = $pdo->prepare("SELECT * FROM book WHERE book_id = :id");
                    $stm->execute([":id" => $editId]);
                    $editing = $stm->fetch(PDO::FETCH_ASSOC);
                    if (! $editing) {
                        echo "<p class='error'>Không tìm thấy sách cần sửa.</p>";
                    }
                } catch (Exception $e) {
                    echo "<p class='error'>Lỗi tải dữ liệu sửa: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
            }
        ?>

        <!-- Form thêm sách: pub_id và cat_id lấy từ DB -->
        <form action="" method="post" enctype="multipart/form-data">
            <table class="form-table">
                <tr>
                    <td>Mã sách (book_id):</td>
                    <td><input type="text" name="book_id" required></td>
                    <td>Tên sách (book_name):</td>
                    <td><input type="text" name="book_name" required></td>
                </tr>
                <tr>
                    <td>Giá (price):</td>
                    <td><input type="number" name="price" min="0" step="1" required></td>
                    <td>Chọn ảnh (file):</td>
                    <td><input type="file" name="imgfile" accept="image/*"></td>
                </tr>
                <tr>
                    <td>Nhà xuất bản (pub_id):</td>
                    <td>
                        <select name="pub_id" required>
                            <option value="">-- Chọn NXB --</option>
                            <?php foreach ($pubs as $p): ?>
                            <option value="<?php echo htmlspecialchars($p['pub_id']); ?>">
                                <?php echo htmlspecialchars($p['pub_id'] . " - " . $p['pub_name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>Loại (cat_id):</td>
                    <td>
                        <select name="cat_id" required>
                            <option value="">-- Chọn loại --</option>
                            <?php foreach ($cats as $c): ?>
                            <option value="<?php echo htmlspecialchars($c['cat_id']); ?>">
                                <?php echo htmlspecialchars($c['cat_id'] . " - " . $c['cat_name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Mô tả (description):</td>
                    <td colspan="3"><textarea name="description" rows="3" style="width:100%;"></textarea></td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:right;">
                        <input type="submit" name="sm" value="Insert">
                    </td>
                </tr>
            </table>
        </form>

        <!-- Form sửa sách: chỉ hiện khi có ?edit=...; KHÔNG cho sửa book_id -->
        <?php if ($editing): ?>
        <hr>
        <h3>Sửa sách:                         <?php echo htmlspecialchars($editing['book_id']); ?></h3>
        <form action="" method="post" enctype="multipart/form-data">
            <table class="form-table">
                <tr>
                    <td>Mã sách (không chỉnh):</td>
                    <td>
                        <input type="text" value="<?php echo htmlspecialchars($editing['book_id']); ?>" disabled>
                        <input type="hidden" name="book_id_fix"
                            value="<?php echo htmlspecialchars($editing['book_id']); ?>">
                    </td>
                    <td>Tên sách:</td>
                    <td><input type="text" name="book_name"
                            value="<?php echo htmlspecialchars($editing['book_name']); ?>" required></td>
                </tr>
                <tr>
                    <td>Giá:</td>
                    <td><input type="number" name="price" min="0" step="1"
                            value="<?php echo htmlspecialchars($editing['price']); ?>" required></td>


                    <td>Chọn ảnh (file):</td>
                    <td><input type="file" name="imgfile" accept="image/*" value="<?php echo htmlspecialchars($editing['img']); ?>>
                        <input type=" hidden" name="img_old" value="<?php echo htmlspecialchars($editing['img']); ?>">
                    </td>

                    <td><img src="lab8_5/image/book/<?php echo htmlspecialchars($editing['img']); ?>"
                            alt="<?php echo htmlspecialchars($editing['img']); ?>"></td>
                </tr>
                <tr>
                    <td>Nhà xuất bản:</td>
                    <td>
                        <select name="pub_id" required>
                            <option value="">-- Chọn NXB --</option>
                            <?php foreach ($pubs as $p): ?>
                            <option value="<?php echo htmlspecialchars($p['pub_id']); ?>"<?php if ($p['pub_id'] === $editing['pub_id']) {
        echo 'selected';
}
?>>
                                <?php echo htmlspecialchars($p['pub_id'] . " - " . $p['pub_name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>Loại:</td>
                    <td>
                        <select name="cat_id" required>
                            <option value="">-- Chọn loại --</option>
                            <?php foreach ($cats as $c): ?>
                            <option value="<?php echo htmlspecialchars($c['cat_id']); ?>"<?php if ($c['cat_id'] === $editing['cat_id']) {
        echo 'selected';
}
?>>
                                <?php echo htmlspecialchars($c['cat_id'] . " - " . $c['cat_name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Mô tả:</td>
                    <td colspan="3"><textarea name="description" rows="3"
                            style="width:100%;"><?php echo htmlspecialchars($editing['description']); ?></textarea></td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:right;">
                        <input type="submit" name="sm_update" value="Update">
                    </td>
                </tr>
            </table>
        </form>
        <?php endif; ?>

        <hr>

        <?php
            // LẤY DANH SÁCH SÁCH
            try {
                $stm = $pdo->prepare("SELECT book_id, book_name, price, img, pub_id, cat_id, description FROM book ORDER BY book_name");
                $stm->execute();
                $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo "<p class='error'>Lỗi truy vấn: " . htmlspecialchars($e->getMessage()) . "</p>";
                $rows = [];
            }
        ?>

        <!-- Hiển thị danh sách sách -->
        <table>
            <tr>
                <th>Mã sách</th>
                <th>Tên sách</th>
                <th>Giá</th>
                <th>Hình</th>
                <th>Mã NXB</th>
                <th>Mã loại</th>
                <th>Mô tả</th>
                <th>Thao tác</th>
            </tr>
            <?php if (empty($rows)): ?>
            <tr>
                <td colspan="8" style="text-align:center;">Chưa có dữ liệu sách.</td>
            </tr>
            <?php else: ?>
            <?php foreach ($rows as $r): ?>
            <tr>
                <td><?php echo htmlspecialchars($r['book_id']); ?></td>
                <td><?php echo htmlspecialchars($r['book_name']); ?></td>
                <td><?php echo htmlspecialchars($r['price']); ?></td>
                <td>
                    <?php
                        if (! empty($r['img'])) {
                            $imgPath = "lab8_5/image/book/" . $r['img']; // thay đường dẫn nếu ảnh nằm nơi khác
                            echo "<img src='" . htmlspecialchars($imgPath) . "' alt='" . htmlspecialchars($r['book_name']) . "'>";
                        } else {
                            echo "(Không có hình)";
                        }
                    ?>
                </td>
                <td><?php echo htmlspecialchars($r['pub_id']); ?></td>
                <td><?php echo htmlspecialchars($r['cat_id']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($r['description'])); ?></td>
                <td class="actions">
                    <a href="?edit=<?php echo urlencode($r['book_id']); ?>">Sửa</a>
                    <a href="?del=<?php echo urlencode($r['book_id']); ?>"
                        onclick="return confirm('Xác nhận xóa sách này?');">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </table>

    </div>
</body>

</html>