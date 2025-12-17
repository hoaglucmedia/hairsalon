<?php
    // Hàm postIndex dùng để lấy dữ liệu từ form POST
    function postIndex($index, $value = "")
    {
        if (! isset($_POST[$index])) {
            return $value;
        }

        return trim($_POST[$index]);
    }

    $username  = postIndex("username");
    $password1 = postIndex("password1");
    $password2 = postIndex("password2");
    $name      = postIndex("name");
    $thong_tin = postIndex("thong_tin");
    $sm        = postIndex("submit");
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>4.1</title>
    <style>
    fieldset {
        width: 50%;
        margin: 50px auto;
    }

    .info {
        width: 600px;
        color: #006;
        background: #6FC;
        margin: 0 auto;
        padding: 10px;
    }
    </style>
</head>

<body>
    <fieldset>
        <legend>Thông tin đăng ký</legend>
        <form action="4.1.php" method="post">
            <table align="center">
                <tr>
                    <td>Tên đăng nhập:</td>
                    <td><input type="text" name="username" value="<?php echo $username; ?>"></td>
                </tr>
                <tr>
                    <td>Mật khẩu:</td>
                    <td><input type="password" name="password1" /></td>
                </tr>
                <tr>
                    <td>Nhập lại mật khẩu:</td>
                    <td><input type="password" name="password2" /></td>
                </tr>
                <tr>
                    <td>Họ Tên:</td>
                    <td><input type="text" name="name" value="<?php echo $name; ?>" /></td>
                </tr>
                <tr>
                    <td>Thông tin thêm:</td>
                    <td><textarea name="thong_tin" rows="5" cols="40"><?php echo $thong_tin; ?></textarea></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><input type="submit" value="submit" name="submit"></td>
                </tr>
            </table>
        </form>
    </fieldset>

    <?php
        if ($sm != "") {
            $err = "";

            if (strlen($username) < 6) {
                $err .= "Username ít nhất phải 6 ký tự!<br>";
            }

            if ($password1 != $password2) {
                $err .= "Mật khẩu và mật khẩu nhập lại không khớp.<br>";
            }

            if (strlen($password1) < 8) {
                $err .= "Mật khẩu phải ít nhất 8 ký tự.<br>";
            }

            if (str_word_count($name) < 2) {
                $err .= "Họ tên phải chứa ít nhất 2 từ.<br>";
            }

            echo '<div class="info">';
            if ($err != "") {
                echo $err;
            } else {
                echo "Username: $username <br>";

                // Mã hóa mật khẩu bằng SHA1
                echo "Mật khẩu mã hóa SHA1: " . sha1($password1) . "<br>";
                // Kết hợp SHA1 và MD5
                echo "Mật khẩu kết hợp SHA1+MD5: " . md5(sha1($password1)) . "<br>";

                echo "Họ tên: " . ucwords($name) . "<br>";

                // Xử lý chuỗi thong_tin
                // 1. Loại bỏ thẻ HTML
                $processed = strip_tags($thong_tin);
                // 2. Thêm ký tự \ trước nháy đơn
                $processed = str_replace("'", "\\'", $processed);
                // 3. Thay thế \n bằng <br>
                $processed = nl2br($processed);
                // 4. Loại bỏ ký tự \ trước nháy đơn (trả lại chuỗi gốc nhưng đã xử lý)
                $processed = str_replace("\\'", "'", $processed);

                echo "Thông tin đã xử lý: <br>" . $processed;
            }
            echo '</div>';
        }
    ?>
</body>

</html>