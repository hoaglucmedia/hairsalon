<div class="login-box animate-fade">
    
    <!-- 1. FORM ĐĂNG NHẬP -->
    <div id="loginForm" class="form-section">
        <h2 style="color:var(--gold); margin-bottom:20px">ĐĂNG NHẬP</h2>
        <form method="POST" action="index.php">
            <input type="text" name="username" placeholder="Tài khoản" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit" name="login">VÀO HỆ THỐNG</button>
        </form>
        <span class="toggle-link" onclick="toggleForms()">Chưa có tài khoản? Đăng ký ngay</span>
    </div>


    <div id="registerForm" class="form-section hidden">
        <h2 style="color:var(--gold); margin-bottom:20px">ĐĂNG KÝ NHANH</h2>
        <form method="POST" action="index.php">
            
            <input type="text" name="reg_username" placeholder="Tên đăng nhập (Viết liền không dấu)" required>
            <input type="text" name="reg_fullname" placeholder="Họ và tên của bạn" required>
            
            <!-- [THAY ĐỔI] Nhập SĐT thay vì Email -->
            <input type="text" name="reg_phone" placeholder="Số điện thoại (VD: 0912...)" required pattern="[0-9]{10,11}" title="Nhập đúng số điện thoại 10-11 số">
            
            <!-- Ô NHẬP MẬT KHẨU CÓ RÀNG BUỘC -->
            <div style="position:relative;">
                <input type="password" name="reg_password" placeholder="Mật khẩu" required
                       pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}"
                       title="Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt"
                       oninput="checkPassStrength(this.value)">
                
                <!-- Thanh hiển thị độ mạnh (Optional) -->
                <div id="pass-strength" style="height: 3px; width: 0%; background: red; transition: 0.3s; margin-bottom: 10px; border-radius:2px;"></div>
            </div>
            
            <p style="font-size: 0.75rem; color: #888; text-align: left; margin-top: -10px; margin-bottom: 15px;">
                * Yêu cầu: 8+ ký tự, Hoa, Thường, Số, Ký tự đặc biệt.
            </p>

            <button type="submit" name="register">TẠO TÀI KHOẢN</button>
        </form>
        <span class="toggle-link" onclick="toggleForms()">Đã có tài khoản? Đăng nhập</span>
    </div>

    <!-- SCRIPT KIỂM TRA ĐỘ MẠNH MẬT KHẨU -->
    <script>
        function checkPassStrength(password) {
            let strength = 0;
            // Kiểm tra các điều kiện
            if (password.match(/[a-z]+/)) strength += 1;
            if (password.match(/[A-Z]+/)) strength += 1;
            if (password.match(/[0-9]+/)) strength += 1;
            if (password.match(/[\W_]+/)) strength += 1;
            if (password.length >= 8) strength += 1;

            const bar = document.getElementById('pass-strength');
            
            // Đổi màu thanh hiển thị
            switch(strength) {
                case 0: case 1: case 2: 
                    bar.style.width = '30%'; bar.style.backgroundColor = '#e74c3c'; // Đỏ (Yếu)
                    break;
                case 3: case 4:
                    bar.style.width = '60%'; bar.style.backgroundColor = '#f1c40f'; // Vàng (Trung bình)
                    break;
                case 5:
                    bar.style.width = '100%'; bar.style.backgroundColor = '#27ae60'; // Xanh (Mạnh)
                    break;
            }
        }
    </script>
</div>