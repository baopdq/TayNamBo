<?php require_once __DIR__ . '/includes/config.php';

if (!isLoggedIn()) {
    redirect(SITE_URL . '/dang-nhap.php');
}

$conn = getConnection();
$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_pwd = $_POST['current_pwd'] ?? '';
    $new_pwd = $_POST['new_pwd'] ?? '';
    $confirm_pwd = $_POST['confirm_pwd'] ?? '';

    // Lấy mật khẩu hiện tại
    $user = $conn->query("SELECT Cu_Password FROM tblcustomer WHERE Cu_ID = $user_id")->fetch_assoc();

    if (!password_verify($current_pwd, $user['Cu_Password'])) {
        $error = 'Mật khẩu hiện tại không đúng';
    } elseif (strlen($new_pwd) < 6) {
        $error = 'Mật khẩu mới phải ít nhất 6 ký tự';
    } elseif ($new_pwd !== $confirm_pwd) {
        $error = 'Mật khẩu xác nhận không khớp';
    } else {
        $hashed_pwd = password_hash($new_pwd, PASSWORD_DEFAULT);
        $conn->query("UPDATE tblcustomer SET Cu_Password = '$hashed_pwd' WHERE Cu_ID = $user_id");
        $success = 'Đổi mật khẩu thành công';
    }
}

$pageTitle = "Đổi Mật Khẩu";
require __DIR__ . '/includes/header.php';
?>

<div class="container" style="margin-top: 100px; margin-bottom: 100px;">
    <div style="max-width: 400px; margin: 0 auto; background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 40px;">
        <h1 style="text-align: center; margin-bottom: 30px; font-size: 24px;">Đổi Mật Khẩu</h1>

        <?php if ($error): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: var(--radius); margin-bottom: 20px;">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: var(--radius); margin-bottom: 20px;">
                <?= $success ?>
                <br>
                <a href="ho-so.php" style="color: #155724; font-weight: 600; text-decoration: underline;">Quay lại hồ sơ</a>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Mật Khẩu Hiện Tại</label>
                <input type="password" name="current_pwd" required 
                       style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 16px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Mật Khẩu Mới</label>
                <input type="password" name="new_pwd" required 
                       style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 16px;">
                <small style="color: var(--text-muted); display: block; margin-top: 4px;">Tối thiểu 6 ký tự</small>
            </div>

            <div style="margin-bottom: 30px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Xác Nhận Mật Khẩu Mới</label>
                <input type="password" name="confirm_pwd" required 
                       style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 16px;">
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; padding: 12px; font-size: 16px;">
                Đổi Mật Khẩu
            </button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <a href="ho-so.php" style="color: var(--primary); text-decoration: none;">Quay lại</a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; $conn->close(); ?>
