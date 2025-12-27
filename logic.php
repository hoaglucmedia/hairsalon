<?php
// --- CẤU HÌNH PHPMAILER (Gửi Email) ---
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function sendActivationEmail($email, $name, $token) {
    // (Phần gửi mail giữ nguyên, bạn có thể bỏ qua nếu đã tắt chức năng này)
    return true; 
}

// --- LOGIC ADMIN ---
if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    if (isset($_POST['save_product'])) {
        $id = $_POST['p_id']; $name = $_POST['p_name']; $price = $_POST['p_price']; $img = $_POST['p_image'];
        $sql = $id ? "UPDATE products SET name=?, price=?, image=? WHERE id=?" : "INSERT INTO products (name, price, image) VALUES (?, ?, ?)";
        $conn->prepare($sql)->execute($id ? [$name, $price, $img, $id] : [$name, $price, $img]);
        echo "<script>alert('Đã lưu sản phẩm!'); location.href='index.php?page=admin';</script>"; exit();
    }
    if (isset($_GET['delete_product'])) {
        $conn->prepare("DELETE FROM products WHERE id=?")->execute([$_GET['delete_product']]);
        header("Location: index.php?page=admin"); exit();
    }
    if (isset($_POST['save_stylist'])) {
        $id = $_POST['s_id']; $name = $_POST['s_name']; $exp = $_POST['s_exp']; $ava = $_POST['s_avatar'];
        $sql = $id ? "UPDATE stylists SET name=?, experience=?, avatar=? WHERE id=?" : "INSERT INTO stylists (name, experience, avatar) VALUES (?, ?, ?)";
        $conn->prepare($sql)->execute($id ? [$name, $exp, $ava, $id] : [$name, $exp, $ava]);
        echo "<script>alert('Đã lưu!'); location.href='index.php?page=admin';</script>"; exit();
    }
    if (isset($_GET['delete_stylist'])) {
        $conn->prepare("DELETE FROM stylists WHERE id=?")->execute([$_GET['delete_stylist']]);
        header("Location: index.php?page=admin"); exit();
    }
    // Admin duyệt đơn hàng
    if (isset($_GET['confirm_order'])) { 
        $conn->prepare("UPDATE orders SET status='confirmed', reject_reason=NULL WHERE id=?")->execute([$_GET['confirm_order']]); 
        header("Location: index.php?page=admin"); exit();
    }
    if (isset($_GET['reject_order']) && isset($_GET['reason'])) {
        $id = $_GET['reject_order']; $reason = $_GET['reason'];
        $conn->prepare("UPDATE orders SET status='rejected', reject_reason=? WHERE id=?")->execute([$reason, $id]);
        header("Location: index.php?page=admin"); exit();
    }
}

// --- LOGIC STYLIST (THỢ) ---
if (isset($_SESSION['role']) && $_SESSION['role'] == 'stylist') {
    if (isset($_GET['confirm_booking'])) {
        $id = $_GET['confirm_booking'];
        $stylist_name = $_SESSION['fullname']; 
        $conn->prepare("UPDATE bookings SET status='confirmed' WHERE id=? AND stylist=?")->execute([$id, $stylist_name]);
        header("Location: index.php?page=stylist"); exit();
    }
    if (isset($_GET['complete_booking'])) {
        $id = $_GET['complete_booking'];
        $stylist_name = $_SESSION['fullname']; 
        $conn->prepare("UPDATE bookings SET status='completed' WHERE id=? AND stylist=?")->execute([$id, $stylist_name]);
        header("Location: index.php?page=stylist"); exit();
    }
    if (isset($_GET['reject_booking']) && isset($_GET['reason'])) {
        $id = $_GET['reject_booking'];
        $reason = $_GET['reason']; 
        $stylist_name = $_SESSION['fullname'];
        // Khi từ chối, status sẽ thành 'rejected', lúc này giờ đó sẽ trống lại
        $conn->prepare("UPDATE bookings SET status='rejected', reject_reason=? WHERE id=? AND stylist=?")->execute([$reason, $id, $stylist_name]);
        header("Location: index.php?page=stylist"); exit();
    }
}

// --- LOGIC USER ---
if (isset($_POST['register'])) {
    $u = $_POST['reg_username']; $p = $_POST['reg_password']; $phone = $_POST['reg_phone']; $fn = $_POST['reg_fullname'];
    
    // Check pass mạnh
    if(!preg_match('@[A-Z]@', $p) || !preg_match('@[0-9]@', $p) || strlen($p)<8) {
        echo "<script>alert('Mật khẩu yếu! Cần 8 ký tự, 1 Hoa, 1 Số.'); window.history.back();</script>"; exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? OR phone=?"); $stmt->execute([$u, $phone]);
    if ($stmt->rowCount() > 0) { echo "<script>alert('Tài khoản hoặc SĐT đã tồn tại!'); window.history.back();</script>"; } else {
        $sql = "INSERT INTO users (username, password, phone, fullname, role, is_verified) VALUES (?, ?, ?, ?, 'user', 1)";
        if ($conn->prepare($sql)->execute([$u, $p, $phone, $fn])) {
            echo "<script>alert('Đăng ký thành công! Đăng nhập ngay.'); window.location.href='index.php?page=login';</script>"; exit();
        }
    }
}

if (isset($_POST['login'])) {
    $u = $_POST['username']; $p = $_POST['password'];
    if (($u == 'admin' && $p == '123') || ($u == 'demo' && $p == '123')) {
        $_SESSION['user'] = $u=='admin'?'Admin':'Khách Demo'; $_SESSION['fullname'] = $u=='admin'?'Quản Trị Viên':'Khách Demo'; $_SESSION['role'] = $u=='admin'?'admin':'user';
        header("Location: index.php?page=home"); exit();
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?"); $stmt->execute([$u, $p]);
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $_SESSION['user'] = $row['username']; $_SESSION['role'] = $row['role']; $_SESSION['fullname'] = $row['fullname'] ?? $row['username'];
            if ($row['role'] == 'stylist') header("Location: index.php?page=stylist");
            elseif ($row['role'] == 'admin') header("Location: index.php?page=admin");
            else header("Location: index.php?page=home");
            exit();
        } else { echo "<script>alert('Sai thông tin!'); window.history.back();</script>"; }
    }
}

if (isset($_POST['change_password'])) {
    $old = $_POST['old_pass']; $new = $_POST['new_pass']; $cfm = $_POST['confirm_pass']; $u = $_SESSION['user'];
    if(!preg_match('@[A-Z]@', $new) || !preg_match('@[0-9]@', $new) || strlen($new)<8) { echo "<script>alert('Mật khẩu yếu!'); window.history.back();</script>"; exit(); }
    if ($new != $cfm) { echo "<script>alert('Mật khẩu không khớp!');</script>"; } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?"); $stmt->execute([$u, $old]);
        if ($stmt->rowCount() > 0) { $conn->prepare("UPDATE users SET password=? WHERE username=?")->execute([$new, $u]); echo "<script>alert('Đổi thành công!'); window.location.href='index.php?page=profile';</script>"; exit(); } 
        else { echo "<script>alert('Mật khẩu cũ sai!');</script>"; }
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'logout') { session_destroy(); header("Location: index.php"); exit(); }

if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    $id = $_POST['product_id']; $qty = max(1, (int)$_POST['quantity']); $found = false;
    foreach ($_SESSION['cart'] as &$item) { if ($item['id'] == $id) { $item['quantity'] += $qty; $found = true; break; } }
    if (!$found) array_push($_SESSION['cart'], ['id' => $_POST['product_id'], 'name' => $_POST['product_name'], 'price' => $_POST['product_price'], 'image' => $_POST['product_image'], 'quantity' => $qty]);
    header("Location: index.php?page=".(isset($_GET['page'])?$_GET['page']:'home')."&open_cart=1"); exit();
}
if (isset($_GET['update_qty'])) {
    $idx = $_GET['update_qty']; $type = $_GET['type'];
    if (isset($_SESSION['cart'][$idx])) { $type=='inc' ? $_SESSION['cart'][$idx]['quantity']++ : ($_SESSION['cart'][$idx]['quantity']>1 ? $_SESSION['cart'][$idx]['quantity']-- : null); }
    header("Location: index.php?page=".(isset($_GET['page'])?$_GET['page']:'home')."&open_cart=1"); exit();
}
if (isset($_GET['remove_item'])) { array_splice($_SESSION['cart'], $_GET['remove_item'], 1); header("Location: index.php?page=".(isset($_GET['page'])?$_GET['page']:'home')."&open_cart=1"); exit(); }
if (isset($_GET['checkout'])) {
    $user = $_SESSION['user']; $total = 0; $addr = urldecode($_GET['address']);
    foreach($_SESSION['cart'] as $c) $total += $c['price']*$c['quantity'];
    $conn->prepare("INSERT INTO orders (username, items, total_price, address, status) VALUES (?, ?, ?, ?, 'pending')")->execute([$user, json_encode($_SESSION['cart'], JSON_UNESCAPED_UNICODE), $total, $addr]);
    unset($_SESSION['cart']); echo "<script>alert('Thanh toán thành công!'); location.href='index.php?page=history';</script>"; exit();
}

// --- ĐẶT LỊCH (CHECK 1 TIẾNG - BỎ QUA ĐƠN HỦY) ---
if (isset($_POST['book_now'])) {
    if (!isset($_SESSION['user'])) { echo "<script>alert('Vui lòng đăng nhập!'); window.history.back();</script>"; } else {
        $date = $_POST['date']; $time = $_POST['time']; $stylist = $_POST['stylist']; $phone = $_POST['phone']; $cus = $_SESSION['fullname']??$_SESSION['user'];
        $h = (int)date('H', strtotime($time)); $book_ts = strtotime("$date $time");
        
        if ($h<8 || $h>=20) { echo "<script>alert('Quán chỉ mở 8h-20h'); window.history.back();</script>"; exit(); }
        if ($book_ts < time()) { echo "<script>alert('Không đặt lịch quá khứ'); window.history.back();</script>"; exit(); }
        
        // [QUAN TRỌNG] Kiểm tra trùng lịch
        // Chỉ coi là "bận" nếu trạng thái là: pending (chờ), confirmed (duyệt), completed (xong)
        // Nếu status = 'rejected' (bị hủy), thì coi như slot đó trống -> Khách khác đặt được.
        $stmt_check = $conn->prepare("SELECT book_time FROM bookings WHERE stylist=? AND book_date=? AND status IN ('pending', 'confirmed', 'completed')");
        $stmt_check->execute([$stylist, $date]);
        
        $is_busy = false;
        $new_time = strtotime("$date $time");
        
        while($row = $stmt_check->fetch()) {
            $exist_time = strtotime("$date " . $row['book_time']);
            // Nếu khoảng cách giữa lịch mới và các lịch ĐANG CÓ hiệu lực nhỏ hơn 60 phút
            if (abs($new_time - $exist_time) < 3600) {
                $is_busy = true;
                break;
            }
        }

        if ($is_busy) {
            echo "<script>alert('Stylist $stylist đã có khách vào khung giờ này (hoặc lân cận 1 tiếng). Vui lòng chọn giờ khác!'); window.history.back();</script>";
            exit();
        }
        
        // Lưu lịch (Status mặc định: pending)
        $conn->prepare("INSERT INTO bookings (customer_name, book_date, book_time, stylist, phone, status) VALUES (?, ?, ?, ?, ?, 'pending')")->execute([$cus, $date, $time, $stylist, $phone]);
        echo "<script>alert('Đã nhận được yêu cầu, bạn có thể check trong app hoặc nhân viên liên hệ sau.'); location.href='index.php?page=history';</script>"; exit();
    }
}
?>