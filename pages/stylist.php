<?php if (isset($_SESSION['role']) && $_SESSION['role']=='stylist'): ?>
    <div class="container animate-fade">
        <div class="section-title"><h2>Khu Vực Stylist</h2></div>
        
        <div class="admin-wrap">
            <div class="admin-header">
                <h3>📅 Lịch Làm Việc Của: <span style="color:var(--gold)"><?php echo $_SESSION['fullname']; ?></span></h3>
            </div>

            <?php
            $my_name = $_SESSION['fullname'];
            // Lấy danh sách khách đặt đúng tên thợ này (trừ những đơn đã hủy)
            // Sắp xếp: Ngày mới nhất lên đầu, giờ tăng dần
            $stmt = $conn->prepare("SELECT * FROM bookings WHERE stylist = ? AND status != 'rejected' ORDER BY book_date DESC, book_time ASC");
            $stmt->execute([$my_name]);
            ?>

            <div style="overflow-x:auto;">
                <table>
                    <tr>
                        <th>Ngày và giờ</th>
                        <th>Khách hàng</th>
                        <th>SĐT</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                    
                    <?php if ($stmt->rowCount() == 0): ?>
                        <tr><td colspan="5" style="text-align:center; padding:20px; color:#888;">Chưa có lịch đặt nào.</td></tr>
                    <?php else: ?>
                        <?php while($row = $stmt->fetch()): ?>
                        <tr style="<?php echo $row['status']=='completed' ? 'opacity:0.6; background:#f9f9f9;' : ''; ?>">
                            
                            <!-- Cột Ngày Giờ -->
                            <td>
                                <?php echo date("d/m/Y", strtotime($row['book_date'])); ?> 
                                <br>
                                <span style="font-weight:bold; color:var(--gold); font-size:1.1em;">
                                    <?php echo date("H:i", strtotime($row['book_time'])); ?>
                                </span>
                            </td>
                            
                            <!-- Cột Khách -->
                            <td><?php echo $row['customer_name']; ?></td>
                            
                            <!-- Cột SĐT (Click để gọi) -->
                            <td>
                                <a href="tel:<?php echo $row['phone']; ?>" style="color:inherit; font-weight:bold; text-decoration:none;">
                                    <i class="fas fa-phone-alt" style="font-size:0.8em; margin-right:5px;"></i>
                                    <?php echo $row['phone']; ?>
                                </a>
                            </td>
                            
                            <!-- Cột Trạng thái -->
                            <td>
                                <?php 
                                if($row['status']=='pending') echo '<span style="color:#f39c12; font-weight:bold;">⏳ Chờ duyệt</span>';
                                elseif($row['status']=='confirmed') echo '<span style="color:#3498db; font-weight:bold;">🔵 Đã duyệt (Chờ khách)</span>';
                                elseif($row['status']=='completed') echo '<span style="color:#27ae60; font-weight:bold;">✅ Đã xong</span>';
                                ?>
                            </td>
                            
                            <!-- Cột Hành động -->
                            <td>
                                <?php if($row['status'] == 'pending'): ?>
                                    <!-- Nút Duyệt -->
                                    <a href="index.php?page=stylist&confirm_booking=<?php echo $row['id']; ?>" 
                                       class="btn-action" style="background:#3498db; text-decoration:none;">
                                       Duyệt
                                    </a>
                                    <!-- Nút Hủy -->
                                    <a href="#" onclick="rejectByStylist(<?php echo $row['id']; ?>)" 
                                       class="btn-action btn-delete">
                                       Hủy
                                    </a>
                                    
                                <?php elseif($row['status'] == 'confirmed'): ?>
                                    <!-- Nút Hoàn thành (Khách đến) -->
                                    <a href="index.php?page=stylist&complete_booking=<?php echo $row['id']; ?>" 
                                       class="btn-action" style="background:#27ae60; width:100%; text-align:center; display:block; margin-bottom:5px; text-decoration:none;"
                                       onclick="return confirm('Xác nhận khách đã đến và cắt xong?')">
                                       ✅ Khách Đến & Xong
                                    </a>
                                    <!-- Nút Hủy (Nếu khách bùng kèo phút chót) -->
                                    <a href="#" onclick="rejectByStylist(<?php echo $row['id']; ?>)" 
                                       style="color:#e74c3c; font-size:0.8rem; text-decoration:underline;">
                                       Khách không đến?
                                    </a>
                                    
                                <?php else: ?>
                                    <span style="color:#aaa;">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <!-- Script Hủy Lịch -->
    <script>
        function rejectByStylist(id) {
            let reason = prompt("Nhập lý do hủy (VD: Khách không đến, Sai số, Bận đột xuất...):");
            if (reason) {
                window.location.href = `index.php?page=stylist&reject_booking=${id}&reason=${encodeURIComponent(reason)}`;
            }
        }
    </script>

<?php else: ?>
    <!-- Màn hình chặn truy cập -->
    <div class="container" style="text-align:center; padding:100px 20px;">
        <i class="fas fa-cut" style="font-size:50px; color:#ccc; margin-bottom:20px;"></i>
        <h2>Bạn không phải là Stylist!</h2>
        <p>Vui lòng đăng nhập bằng tài khoản thợ để truy cập.</p>
        <a href="index.php?page=home" style="color:var(--gold); text-decoration:underline">Quay về trang chủ</a>
    </div>
<?php endif; ?>