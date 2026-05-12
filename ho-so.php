<?php require_once __DIR__ . '/includes/config.php';

if (!isLoggedIn()) {
    redirect(SITE_URL . '/dang-nhap.php');
}

$conn = getConnection();
$user_id = $_SESSION['user_id'];
$customer = $conn->query("SELECT * FROM tblcustomer WHERE Cu_ID = $user_id")->fetch_assoc();

$error = '';
$success = '';

// Cập nhật profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ho_ten = sanitize($_POST['ho_ten'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $dia_chi = sanitize($_POST['dia_chi'] ?? '');
    
    if (empty($ho_ten) || empty($phone)) {
        $error = 'Vui lòng điền đầy đủ thông tin';
    } else {
        $update = "UPDATE tblcustomer SET Cu_Name = '$ho_ten', Cu_Phone = '$phone', Cu_Address = '$dia_chi' WHERE Cu_ID = $user_id";
        if ($conn->query($update)) {
            $success = 'Cập nhật thông tin thành công';
            $_SESSION['ho_ten'] = $ho_ten;
            $customer['Cu_Name'] = $ho_ten;
            $customer['Cu_Phone'] = $phone;
            $customer['Cu_Address'] = $dia_chi;
        } else {
            $error = 'Lỗi: ' . $conn->error;
        }
    }
}

// Lấy danh sách đơn đặt tour
$bookings = $conn->query("SELECT * FROM tblbooking WHERE Cu_ID = $user_id ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);

$pageTitle = "Hồ Sơ Cá Nhân";
require __DIR__ . '/includes/header.php';
?>

<div class="container" style="margin-top: 100px; margin-bottom: 100px;">
    <h1 style="margin-bottom: 40px;">Hồ Sơ Cá Nhân</h1>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        <!-- Form cập nhật thông tin -->
        <div style="background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 30px;">
            <h2 style="margin-bottom: 20px; font-size: 22px;">Thông Tin Tài Khoản</h2>

            <?php if ($error): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: var(--radius); margin-bottom: 15px;">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: var(--radius); margin-bottom: 15px;">
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px;">Họ Tên</label>
                    <input type="text" name="ho_ten" value="<?= sanitize($customer['Cu_Name']) ?>" required 
                           style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm);">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px;">Email</label>
                    <input type="email" value="<?= sanitize($customer['Cu_Email']) ?>" disabled 
                           style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg);">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px;">Số Điện Thoại</label>
                    <input type="tel" name="phone" value="<?= sanitize($customer['Cu_Phone']) ?>" required 
                           style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm);">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px;">Địa Chỉ</label>
                    <input type="text" name="dia_chi" value="<?= sanitize($customer['Cu_Address'] ?? '') ?>" 
                           style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm);" placeholder="123 Đường ABC, TP">
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; padding: 12px;">
                    Lưu Thay Đổi
                </button>
            </form>

            <hr style="margin: 30px 0; border: none; border-top: 1px solid var(--border);">

            <div>
                <h3 style="margin-bottom: 15px;">Bảo Mật</h3>
                <a href="doi-mat-khau.php" class="btn-outline" style="display: inline-block; padding: 10px 20px;">
                    Đổi Mật Khẩu
                </a>
            </div>
        </div>

        <!-- Sidebar thống kê -->
        <div>
            <div style="background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px; margin-bottom: 20px;">
                <h3 style="margin-bottom: 20px;">Thống Kê</h3>
                
                <div style="background: var(--bg); padding: 15px; border-radius: var(--radius); margin-bottom: 15px;">
                    <div style="color: var(--text-muted); font-size: 14px;">Tổng Số Đơn</div>
                    <div style="font-size: 28px; font-weight: 700; color: var(--accent);">
                        <?= count($bookings) ?>
                    </div>
                </div>

                <div style="background: var(--bg); padding: 15px; border-radius: var(--radius); margin-bottom: 15px;">
                    <div style="color: var(--text-muted); font-size: 14px;">Đơn Đang Chờ</div>
                    <div style="font-size: 28px; font-weight: 700; color: var(--primary);">
                        <?= count(array_filter($bookings, fn($b) => $b['trang_thai_don'] === 'pending')) ?>
                    </div>
                </div>

                <div style="background: var(--bg); padding: 15px; border-radius: var(--radius);">
                    <div style="color: var(--text-muted); font-size: 14px;">Đơn Hoàn Thành</div>
                    <div style="font-size: 28px; font-weight: 700; color: #28a745;">
                        <?= count(array_filter($bookings, fn($b) => $b['trang_thai_don'] === 'confirmed')) ?>
                    </div>
                </div>
            </div>

            <a href="dat-tour-cua-toi.php" class="btn-primary" style="display: block; text-align: center; padding: 12px;">
                Xem Đơn Đặt Tour
            </a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; $conn->close(); ?>
