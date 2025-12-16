<?php if (isset($_SESSION['role']) && $_SESSION['role']=='admin'): ?>
    
    <!-- CSS RIÊNG CHO TRANG ADMIN RESPONSIVE -->
    <style>
        table { 
            width: 100%; 
            border-collapse: collapse; 
            min-width: 800px; /* Đảm bảo bảng không bị co quá nhỏ */
        }
        th, td { 
            white-space: nowrap; 
            padding: 12px 15px; 
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th { background: var(--black); color: var(--gold); }
        
        .admin-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            flex-wrap: wrap; 
            gap: 15px; 
            margin-bottom: 20px; 
            border-bottom: 2px solid #eee; 
            padding-bottom: 15px; 
        }
        
        .search-form { display: flex; gap: 5px; }
        
        @media (max-width: 768px) {
            .admin-header { flex-direction: column; align-items: flex-start; }
            .search-form { width: 100%; }
            .search-form input { flex: 1; }
        }
    </style>

    <div class="container animate-fade">
        <div class="section-title"><h2>Quản Trị Viên</h2></div>
        
        <!-- 1. QUẢN LÝ SẢN PHẨM -->
        <div class="admin-wrap">
            <div class="admin-header">
                <h3>📦 Quản lý Sản phẩm</h3>
                
                <!-- Form Tìm kiếm -->
                <form method="GET" class="search-form">
                    <input type="hidden" name="page" value="admin">
                    <input type="text" name="search_product" placeholder="Tìm tên SP..." 
                           value="<?php echo isset($_GET['search_product']) ? htmlspecialchars($_GET['search_product']) : ''; ?>"
                           style="padding:8px 12px; border:1px solid #ddd; border-radius:4px; outline:none;">
                    <button type="submit" class="btn-action" style="background:var(--gold); color:black; font-weight:bold; border:none;">Tìm</button>
                    <?php if(isset($_GET['search_product'])): ?>
                        <a href="index.php?page=admin" class="btn-action" style="background:#ccc; color:black; text-decoration:none;">✕</a>
                    <?php endif; ?>
                </form>
            </div>
            
            <?php 
            // Logic lấy dữ liệu để Sửa
            $p_edit = ['id'=>'', 'name'=>'', 'price'=>'', 'image'=>''];
            if(isset($_GET['edit_p'])) { 
                $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->execute([$_GET['edit_p']]);
                $p_edit = $stmt->fetch();
            } 
            ?>
            
            <form method="POST">
                <input type="hidden" name="p_id" value="<?php echo $p_edit['id']; ?>">
                <div class="form-row">
                    <input type="text" name="p_name" placeholder="Tên Sản Phẩm" value="<?php echo $p_edit['name']; ?>" required>
                    <input type="number" name="p_price" placeholder="Giá tiền" value="<?php echo $p_edit['price']; ?>" required>
                </div>
                <div class="form-row">
                    <input type="text" name="p_image" placeholder="Link ảnh (URL)" value="<?php echo $p_edit['image']; ?>">
                    <button type="submit" name="save_product" class="btn-action" style="background:var(--black); width:100%; max-width:200px;">
                        <?php echo $p_edit['id'] ? 'CẬP NHẬT' : 'THÊM MỚI'; ?>
                    </button>
                </div>
            </form>

            <div style="overflow-x: auto;">
                <table>
                    <tr><th>ID</th><th>Ảnh</th><th>Tên</th><th>Giá</th><th>Hành động</th></tr>
                    <?php 
                    $sql_p = "SELECT * FROM products";
                    $params_p = [];
                    if (!empty($_GET['search_product'])) {
                        $sql_p .= " WHERE name LIKE ?";
                        $params_p[] = "%" . $_GET['search_product'] . "%";
                    }
                    
                    $stmt = $conn->prepare($sql_p);
                    $stmt->execute($params_p);
                    
                    if($stmt->rowCount() == 0) echo "<tr><td colspan='5' style='text-align:center; padding:15px; color:#888;'>Không tìm thấy.</td></tr>";

                    while($r = $stmt->fetch()): 
                    ?>
                    <tr>
                        <td><?php echo $r['id']; ?></td>
                        <td><img src="<?php echo $r['image']; ?>" style="width:40px; height:40px; object-fit:cover; border-radius:5px;" onerror="this.src='https://placehold.co/40'"></td>
                        <td><?php echo $r['name']; ?></td>
                        <td><?php echo number_format($r['price']); ?>đ</td>
                        <td>
                            <a href="index.php?page=admin&edit_p=<?php echo $r['id']; ?>" class="btn-action btn-edit">Sửa</a> 
                            <a href="index.php?page=admin&delete_product=<?php echo $r['id']; ?>" onclick="return confirm('Xóa sản phẩm này?')" class="btn-action btn-delete">Xóa</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>

        <!-- 2. QUẢN LÝ THỢ (STYLIST) -->
        <div class="admin-wrap">
            <div class="admin-header">
                <h3>✂ Quản lý Thợ</h3>
                
                <!-- Form Tìm kiếm -->
                <form method="GET" class="search-form">
                    <input type="hidden" name="page" value="admin">
                    <input type="text" name="search_stylist" placeholder="Tìm tên Thợ..." 
                           value="<?php echo isset($_GET['search_stylist']) ? htmlspecialchars($_GET['search_stylist']) : ''; ?>"
                           style="padding:8px 12px; border:1px solid #ddd; border-radius:4px; outline:none;">
                    <button type="submit" class="btn-action" style="background:var(--gold); color:black; font-weight:bold; border:none;">Tìm</button>
                    <?php if(isset($_GET['search_stylist'])): ?>
                        <a href="index.php?page=admin" class="btn-action" style="background:#ccc; color:black; text-decoration:none;">✕</a>
                    <?php endif; ?>
                </form>
            </div>
            
            <?php 
            $s_edit = ['id'=>'', 'name'=>'', 'experience'=>'', 'avatar'=>''];
            if(isset($_GET['edit_s'])) { 
                $stmt = $conn->prepare("SELECT * FROM stylists WHERE id = ?");
                $stmt->execute([$_GET['edit_s']]);
                $s_edit = $stmt->fetch();
            } 
            ?>
            
            <form method="POST">
                <input type="hidden" name="s_id" value="<?php echo $s_edit['id']; ?>">
                <div class="form-row">
                    <input type="text" name="s_name" placeholder="Tên Thợ" value="<?php echo $s_edit['name']; ?>" required>
                    <input type="text" name="s_exp" placeholder="Kinh nghiệm (VD: 5 năm)" value="<?php echo $s_edit['experience']; ?>" required>
                </div>
                <div class="form-row">
                    <input type="text" name="s_avatar" placeholder="Avatar URL" value="<?php echo $s_edit['avatar']; ?>">
                    <button type="submit" name="save_stylist" class="btn-action" style="background:var(--black); width:100%; max-width:200px;">
                        <?php echo $s_edit['id'] ? 'CẬP NHẬT' : 'THÊM MỚI'; ?>
                    </button>
                </div>
            </form>

            <div style="overflow-x: auto;">
                <table>
                    <tr><th>Tên</th><th>Kinh nghiệm</th><th>Hành động</th></tr>
                    <?php 
                    $sql_s = "SELECT * FROM stylists";
                    $params_s = [];
                    if (!empty($_GET['search_stylist'])) {
                        $sql_s .= " WHERE name LIKE ?";
                        $params_s[] = "%" . $_GET['search_stylist'] . "%";
                    }

                    $stmt = $conn->prepare($sql_s);
                    $stmt->execute($params_s);

                    if($stmt->rowCount() == 0) echo "<tr><td colspan='3' style='text-align:center; padding:15px; color:#888;'>Không tìm thấy.</td></tr>";

                    while($r = $stmt->fetch()): 
                    ?>
                    <tr>
                        <td><?php echo $r['name']; ?></td>
                        <td><?php echo $r['experience']; ?></td>
                        <td>
                            <a href="index.php?page=admin&edit_s=<?php echo $r['id']; ?>" class="btn-action btn-edit">Sửa</a> 
                            <a href="index.php?page=admin&delete_stylist=<?php echo $r['id']; ?>" onclick="return confirm('Xóa thợ này?')" class="btn-action btn-delete">Xóa</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>

        <!-- 3. DUYỆT ĐƠN HÀNG (ORDERS) -->
        <div class="admin-wrap">
            <div class="admin-header"><h3>🛒 Duyệt Đơn Hàng (Sản phẩm)</h3></div>
            <div style="overflow-x: auto;">
                <table>
                    <tr>
                        <th style="width:15%">Khách hàng</th>
                        <th style="width:25%">Địa chỉ giao</th>
                        <th style="width:15%">Tổng tiền</th>
                        <th style="width:25%">Chi tiết món</th>
                        <th style="width:20%">Hành động</th>
                    </tr>
                    <?php
                    // Chỉ hiện đơn hàng 'pending'
                    $stmt = $conn->prepare("SELECT * FROM orders WHERE status='pending' ORDER BY created_at DESC");
                    $stmt->execute();
                    
                    if($stmt->rowCount() == 0) echo "<tr><td colspan='5' style='text-align:center; color:#999; padding:20px'>Không có đơn hàng mới</td></tr>";
                    
                    while($row = $stmt->fetch()): 
                        $items = json_decode($row['items'], true);
                        $item_str = "";
                        if(is_array($items)) {
                            foreach($items as $i) {
                                $qty = isset($i['quantity']) ? $i['quantity'] : 1;
                                $item_str .= "• " . $i['name'] . " (x$qty)<br>";
                            }
                        }
                    ?>
                    <tr>
                        <td><?php echo $row['username']; ?></td>
                        <td style="font-size:0.9em; color:#555; white-space:normal; max-width:200px;"><?php echo $row['address']; ?></td>
                        <td><b style="color:var(--gold)"><?php echo number_format($row['total_price']); ?>đ</b></td>
                        <td style="font-size:0.85em; line-height:1.4"><?php echo $item_str; ?></td>
                        <td>
                            <a href="index.php?page=admin&confirm_order=<?php echo $row['id']; ?>" class="btn-action btn-edit" style="background:#27ae60; margin-right:5px">Duyệt</a>
                            <a href="#" onclick="rejectOrder(<?php echo $row['id']; ?>)" class="btn-action btn-delete">Hủy</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>

    </div>
<?php else: ?>
    <!-- Màn hình chặn truy cập -->
    <div class="container" style="text-align:center; padding:100px 20px;">
        <i class="fas fa-lock" style="font-size:50px; color:#ccc; margin-bottom:20px;"></i>
        <h2>Khu vực hạn chế</h2>
        <p>Bạn không có quyền truy cập trang quản trị này.</p>
        <a href="index.php?page=home" style="color:var(--gold); text-decoration:underline">Quay về trang chủ</a>
    </div>
<?php endif; ?>