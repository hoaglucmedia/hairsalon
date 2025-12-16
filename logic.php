<?php
// Lưu ý: File này phụ thuộc vào biến $conn từ config.php
// Đã loại bỏ hoàn toàn phần gửi Email (PHPMailer) để đơn giản hóa

// --- 3. LOGIC ADMIN ---
if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    
    // A. Quản lý Sản phẩm
    if (isset($_POST['save_product'])) {
        $id = $_POST['p_id'];
        $name = $_POST['p_name'];
        $price = $_POST['p_price'];
        $img = $_POST['p_image'];
        
        if ($id) {
            $sql = "UPDATE products SET name=?, price=?, image=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$name, $price, $img, $id]);
        } else {
            $sql = "INSERT INTO products (name, price, image) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$name, $price, $img]);
        }
        echo "<script>alert('Đã lưu sản phẩm!'); location.href='index.php?page=admin';</script>";
        exit();
    }

    if (isset($_GET['delete_product'])) {
        $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
        $stmt->execute([$_GET['delete_product']]);
        header("Location: index.php?page=admin");
        exit();
    }
    
    // B. Quản lý Thợ (Stylist)
    if (isset($_POST['save_stylist'])) {
        $id = $_POST['s_id'];
        $name = $_POST['s_name'];
        $exp = $_POST['s_exp'];
        $ava = $_POST['s_avatar'];
        
        if ($id) {
            $sql = "UPDATE stylists SET name=?, experience=?, avatar=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$name, $exp, $ava, $id]);
        } else {
            $sql = "INSERT INTO stylists (name, experience, avatar) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$name, $exp, $ava]);
        }
        echo "<script>alert('Đã lưu thông tin thợ!'); location.href='index.php?page=admin';</script>";
        exit();
    }

    if (isset($_GET['delete_stylist'])) {
        $stmt = $conn->prepare("DELETE FROM stylists WHERE id=?");
        $stmt->execute([$_GET['delete_stylist']]);
        header("Location: index.php?page=admin");
        exit();
    }
    
    // C. Duyệt & Từ chối Booking (Admin đã bỏ quyền duyệt, nhưng giữ code xóa nếu cần dọn dẹp)
    // Admin có thể xóa booking nếu cần thiết (tùy chỉnh thêm)

    // D. Duyệt & Từ chối Đơn hàng (Sản phẩm)
    if (isset($_GET['confirm_order'])) { 
        $stmt = $conn->prepare("UPDATE orders SET status='confirmed', reject_reason=NULL WHERE id=?");
        $stmt->execute([$_GET['confirm_order']]); 
        header("Location: index.php?page=admin");
        exit();
    }
    
    if (isset($_GET['reject_order']) && isset($_GET['reason'])) {
        $stmt = $conn->prepare("UPDATE orders SET status='rejected', reject_reason=? WHERE id=?");
        $stmt->execute([$_GET['reason'], $_GET['reject_order']]);
        header("Location: index.php?page=admin");
        exit();
    }
}

// --- 3.5 LOGIC STYLIST (Dành cho Thợ) ---
if (isset($_SESSION['role']) && $_SESSION['role'] == 'stylist') {
    
    // Thợ xác nhận lịch (Duyệt yêu cầu)
    if (isset($_GET['confirm_booking'])) {
        $id = $_GET['confirm_booking'];
        $stylist_name = $_SESSION['fullname']; 
        $stmt = $conn->prepare("UPDATE bookings SET status='confirmed' WHERE id=? AND stylist=?");
        $stmt->execute([$id, $stylist_name]);
        header("Location: index.php?page=stylist");
        exit();
    }

    // Thợ xác nhận khách đã đến/Cắt xong (Hoàn thành)
    if (isset($_GET['complete_booking'])) {
        $id = $_GET['complete_booking'];
        $stylist_name = $_SESSION['fullname']; 
        $stmt = $conn->prepare("UPDATE bookings SET status='completed' WHERE id=? AND stylist=?");
        $stmt->execute([$id, $stylist_name]);
        header("Location: index.php?page=stylist");
        exit();
    }

    // Thợ từ chối/Hủy lịch
    if (isset($_GET['reject_booking']) && isset($_GET['reason'])) {
        $id = $_GET['reject_booking'];
        $reason = $_GET['reason']; 
        $stylist_name = $_SESSION['fullname'];
        
        $stmt = $conn->prepare("UPDATE bookings SET status='rejected', reject_reason=? WHERE id=? AND stylist=?");
        $stmt->execute([$reason, $id, $stylist_name]);
        header("Location: index.php?page=stylist");
        exit();
    }
}

// --- 4. LOGIC USER ---

// Đăng Ký (Dùng SĐT - Kích hoạt ngay lập tức)
if (isset($_POST['register'])) {
    $u = $_POST['reg_username'];
    $p = $_POST['reg_password']; 
    $phone = $_POST['reg_phone']; // Dùng SĐT thay Email
    $fn = $_POST['reg_fullname'];

    // Kiểm tra mật khẩu mạnh
    $uppercase = preg_match('@[A-Z]@', $p); 
    $lowercase = preg_match('@[a-z]@', $p); 
    $number    = preg_match('@[0-9]@', $p); 
    $special   = preg_match('@[^\w]@', $p);

    if(!$uppercase || !$lowercase || !$number || !$special || strlen($p) < 8) {
        echo "<script>
            alert('Mật khẩu yếu! Phải có ít nhất 8 ký tự, bao gồm: Chữ hoa, Chữ thường, Số và Ký tự đặc biệt.');
            window.history.back();
        </script>";
        exit(); 
    }

    // Kiểm tra trùng username hoặc SĐT
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? OR phone=?");
    $stmt->execute([$u, $phone]);
    
    if ($stmt->rowCount() > 0) {
        echo "<script>alert('Tên tài khoản hoặc Số điện thoại đã tồn tại!'); window.history.back();</script>";
    } else {
        // Lưu user với trạng thái ĐÃ KÍCH HOẠT (is_verified = 1)
        // Cột email để trống hoặc NULL tùy cấu hình DB
        $sql = "INSERT INTO users (username, password, phone, fullname, role, is_verified) VALUES (?, ?, ?, ?, 'user', 1)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute([$u, $p, $phone, $fn])) {
            echo "<script>alert('Đăng ký thành công! Bạn có thể đăng nhập ngay.'); window.location.href='index.php?page=login';</script>";
            exit();
        } else {
            echo "<script>alert('Lỗi hệ thống!');</script>";
        }
    }
}

// Đăng Nhập
if (isset($_POST['login'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];
    
    // Check tài khoản cứng (Admin/Demo)
    if (($u == 'admin' && $p == '123') || ($u == 'demo' && $p == '123')) {
        $_SESSION['user'] = ($u == 'admin') ? 'Admin' : 'Khách Demo';
        $_SESSION['fullname'] = ($u == 'admin') ? 'Quản Trị Viên' : 'Khách Demo';
        $_SESSION['role'] = ($u == 'admin') ? 'admin' : 'user';
        header("Location: index.php?page=home"); 
        exit();
    } else {
        // Check Database
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
        $stmt->execute([$u, $p]);
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            
            // Vì đăng ký mới đã auto kích hoạt, nên ta chỉ cần check login
            // (Nếu muốn kỹ hơn có thể check lại is_verified ở đây)
            $_SESSION['user'] = $row['username']; 
            $_SESSION['role'] = $row['role'];
            $_SESSION['fullname'] = !empty($row['fullname']) ? $row['fullname'] : $row['username'];
            
            // Điều hướng theo quyền
            if ($row['role'] == 'stylist') {
                header("Location: index.php?page=stylist");
            } elseif ($row['role'] == 'admin') {
                header("Location: index.php?page=admin");
            } else {
                header("Location: index.php?page=home");
            }
            exit();
            
        } else { 
            echo "<script>alert('Sai thông tin đăng nhập!'); window.history.back();</script>"; 
        }
    }
}

// Đổi Mật Khẩu
if (isset($_POST['change_password']) && isset($_SESSION['user'])) {
    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];
    $u = $_SESSION['user'];

    // Kiểm tra độ mạnh mật khẩu mới
    $uppercase = preg_match('@[A-Z]@', $new_pass);
    $lowercase = preg_match('@[a-z]@', $new_pass);
    $number    = preg_match('@[0-9]@', $new_pass);
    $special   = preg_match('@[^\w]@', $new_pass);

    if(!$uppercase || !$lowercase || !$number || !$special || strlen($new_pass) < 8) {
        echo "<script>alert('Mật khẩu mới yếu! Cần 8 ký tự gồm Hoa, Thường, Số, Ký tự đặc biệt.'); window.location.href='index.php?page=profile';</script>"; 
        exit();
    }

    if ($new_pass != $confirm_pass) {
        echo "<script>alert('Mật khẩu xác nhận không khớp!');</script>";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
        $stmt->execute([$u, $old_pass]);
        
        if ($stmt->rowCount() > 0) {
            $stmt = $conn->prepare("UPDATE users SET password=? WHERE username=?");
            $stmt->execute([$new_pass, $u]);
            echo "<script>alert('Đổi mật khẩu thành công!'); window.location.href='index.php?page=profile';</script>";
            exit();
        } else {
            echo "<script>alert('Mật khẩu cũ không đúng!');</script>";
        }
    }
}

// Đăng Xuất
if (isset($_GET['action']) && $_GET['action'] == 'logout') { 
    session_destroy(); 
    header("Location: index.php"); 
    exit(); 
}

// --- GIỎ HÀNG ---

// Thêm vào giỏ
if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    
    $id = $_POST['product_id']; 
    $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    if ($qty < 1) $qty = 1;

    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) { 
            $item['quantity'] += $qty; 
            $found = true; 
            break; 
        }
    }
    
    if (!$found) {
        array_push($_SESSION['cart'], [
            'id' => $_POST['product_id'], 
            'name' => $_POST['product_name'], 
            'price' => $_POST['product_price'], 
            'image' => $_POST['product_image'], 
            'quantity' => $qty 
        ]);
    }
    
    $current_page = isset($_GET['page']) ? $_GET['page'] : 'home';
    header("Location: index.php?page=" . $current_page . "&open_cart=1");
    exit();
}

// Cập nhật số lượng (+/-)
if (isset($_GET['update_qty'])) {
    $index = $_GET['update_qty'];
    $type = $_GET['type'];

    if (isset($_SESSION['cart'][$index])) {
        if ($type == 'inc') {
            $_SESSION['cart'][$index]['quantity']++;
        } elseif ($type == 'dec') {
            if ($_SESSION['cart'][$index]['quantity'] > 1) {
                $_SESSION['cart'][$index]['quantity']--;
            }
        }
    }
    
    $current_page = isset($_GET['page']) ? $_GET['page'] : 'home';
    header("Location: index.php?page=" . $current_page . "&open_cart=1"); 
    exit();
}

// Xóa sản phẩm khỏi giỏ
if (isset($_GET['remove_item'])) {
    $index = $_GET['remove_item'];
    if (isset($_SESSION['cart'][$index])) { 
        array_splice($_SESSION['cart'], $index, 1); 
    }
    $current_page = isset($_GET['page']) ? $_GET['page'] : 'home';
    header("Location: index.php?page=" . $current_page . "&open_cart=1"); 
    exit();
}

// --- THANH TOÁN ---
if (isset($_GET['checkout']) && isset($_SESSION['cart']) && isset($_SESSION['user'])) {
    $user = $_SESSION['user']; 
    $total = 0;
    
    $address = isset($_GET['address']) ? urldecode($_GET['address']) : 'Tại quán';

    foreach($_SESSION['cart'] as $c) { 
        $qty = isset($c['quantity']) ? $c['quantity'] : 1;
        $total += ($c['price'] * $qty); 
    }
    
    $items_json = json_encode($_SESSION['cart'], JSON_UNESCAPED_UNICODE);
    
    $sql = "INSERT INTO orders (username, items, total_price, address, status) VALUES (?, ?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    
    if($stmt->execute([$user, $items_json, $total, $address])) { 
        unset($_SESSION['cart']); 
        echo "<script>alert('Thanh toán thành công! Đơn hàng sẽ giao đến: $address'); location.href='index.php?page=history';</script>"; 
        exit();
    }
}

// --- ĐẶT LỊCH (CHECK TRÙNG LỊCH 1 TIẾNG) ---
if (isset($_POST['book_now'])) {
    if (!isset($_SESSION['user'])) { 
        echo "<script>alert('Vui lòng đăng nhập!'); window.history.back();</script>"; 
    } else {
        $date = $_POST['date']; 
        $time = $_POST['time']; 
        $stylist = $_POST['stylist']; 
        $phone = $_POST['phone']; 
        $customer = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : $_SESSION['user'];
        
        $h = (int)date('H', strtotime($time));
        $booking_timestamp = strtotime("$date $time");
        $current_timestamp = time();

        if ($h < 8 || $h >= 20) { 
            echo "<script>alert('Quán chỉ mở từ 8h - 20h!'); window.history.back();</script>"; 
            exit(); 
        } elseif ($booking_timestamp < $current_timestamp) {
            echo "<script>alert('Lỗi: Không thể đặt lịch trong quá khứ!'); window.history.back();</script>"; 
            exit();
        }

        // Kiểm tra trùng lịch
        $stmt_check = $conn->prepare("SELECT book_time FROM bookings WHERE stylist=? AND book_date=? AND status!='rejected'");
        $stmt_check->execute([$stylist, $date]);
        
        $is_busy = false;
        $new_time = strtotime("$date $time");
        
        while($row = $stmt_check->fetch()) {
            $exist_time = strtotime("$date " . $row['book_time']);
            // Nếu khoảng cách giữa lịch mới và lịch cũ nhỏ hơn 60 phút
            if (abs($new_time - $exist_time) < 3600) {
                $is_busy = true;
                break;
            }
        }

        if ($is_busy) {
            echo "<script>alert('Rất tiếc! Stylist $stylist đã có khách vào khung giờ này (hoặc lân cận 1 tiếng). Vui lòng chọn giờ khác!'); window.history.back();</script>";
            exit();
        }
        
        $conn->prepare("INSERT INTO bookings (customer_name, book_date, book_time, stylist, phone, status) VALUES (?, ?, ?, ?, ?, 'pending')")->execute([$customer, $date, $time, $stylist, $phone]);
        echo "<script>alert('Đã đặt lịch thành công! Vui lòng chờ Stylist xác nhận.'); location.href='index.php?page=history';</script>"; 
        exit();
    }
}
?>