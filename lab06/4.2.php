<?php
    // Hàm lấy dữ liệu từ form POST
    function postIndex($index, $value = "")
    {
        if (! isset($_POST[$index])) {
            return $value;
        }

        return trim($_POST[$index]);
    }

    // Hàm kiểm tra tính hợp lệ của username
    function checkUserName($string)
    {
        // Cho phép: chữ cái a-z, A-Z, số 0-9, ký tự ., _ và -
        return preg_match("/^[a-zA-Z0-9._-]*$/", $string);
    }

    // Hàm kiểm tra định dạng email
    function checkEmail($string)
    {
        return preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/", $string);
    }

    // Hàm kiểm tra mật khẩu
    function checkPassword($string)
    {
        // Ít nhất 8 ký tự, có 1 số, 1 chữ hoa, 1 chữ thường
        return preg_match("/^(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{8,}$/", $string);
    }

    // Hàm kiểm tra số điện thoại
    function checkPhone($string)
    {
        // Chỉ cho phép số, tối thiểu 9 và tối đa 11 chữ số
        return preg_match("/^[0-9]{9,11}$/", $string);
    }

    // Hàm kiểm tra ngày sinh
    function checkDateFormat($string)
    {
        // Định dạng dd/mm/yyyy hoặc dd-mm-yyyy
        return preg_match("/^(0?[1-9]|[12][0-9]|3[01])[\/-](0?[1-9]|1[0-2])[\/-](\d{4})$/", $string);
    }

    // Lấy dữ liệu từ form
    $sm       = postIndex("submit");
    $username = postIndex("username");
    $password = postIndex("password");
    $email    = postIndex("email");
    $date     = postIndex("date");
    $phone    = postIndex("phone");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <title>4.2</title>
    <style>
    fieldset {
        width: 50%;
        margin: 100px auto;
    }

    .info {
        width: 600px;
        color: #006;
        background: #6FC;
        margin: 0 auto;
        padding: 10px;
    }

    #frm1 input {
        width: 300px
    }
    </style>
</head>

<body>
    <fieldset>
        <legend style="margin:0 auto">Đăng ký thông tin</legend>
        <form action="4.2.php" method="post" enctype="multipart/form-data" id='frm1'>
            <table align="center">
                <tr>
                    <td>UserName</td>
                    <td><input type="text" name="username" value="<?php echo $username; ?>" />*</td>
                </tr>
                <tr>
                    <td>Mật khẩu</td>
                    <td><input type="text" name="password" />*</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type="text" name="email" value="<?php echo $email; ?>" />*</td>
                </tr>
                <tr>
                    <td>Ngày sinh</td>
                    <td><input type="text" name="date" value="<?php echo $date; ?>" />*</td>
                </tr>
                <tr>
                    <td>Điện thoại</td>
                    <td><input type="text" name="phone" value="<?php echo $phone; ?>" /></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><input type="submit" value="submit" name="submit"></td>
                </tr>
            </table>
        </form>
    </fieldset>

    <?php
        if ($sm != "") {
            echo '<div class="info">Kết quả kiểm tra:<br />';
            if (! checkUserName($username)) {
                echo "Username không hợp lệ (chỉ cho phép a-z, A-Z, 0-9, ., _, -)<br>";
            }

            if (! checkPassword($password)) {
                echo "Mật khẩu phải ≥8 ký tự, có ít nhất 1 số, 1 chữ hoa, 1 chữ thường<br>";
            }

            if (! checkEmail($email)) {
                echo "Email không hợp lệ<br>";
            }

            if (! checkDateFormat($date)) {
                echo "Ngày sinh phải có định dạng dd/mm/yyyy hoặc dd-mm-yyyy<br>";
            }

            if (! checkPhone($phone)) {
                echo "Số điện thoại chỉ được nhập số (9–11 chữ số)<br>";
            }

            // Nếu tất cả hợp lệ
            if (checkUserName($username) && checkPassword($password) && checkEmail($email) && checkDateFormat($date) && checkPhone($phone)) {
                echo "Tất cả dữ liệu hợp lệ!<br>";
                echo "Username: $username <br>";
                echo "Email: $email <br>";
                echo "Ngày sinh: $date <br>";
                echo "Điện thoại: $phone <br>";
            }
            echo '</div>';
        }
    ?>
</body>

</html>