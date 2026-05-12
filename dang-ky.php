<?php require_once __DIR__ . '/includes/config.php';
$conn = getConnection();

if (isLoggedIn()) {
    redirect(SITE_URL);
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!captcha_verify($_POST['captcha'] ?? null)) {
        $error = 'Mã xác thực không đúng hoặc đã hết hạn. Vui lòng làm mới mã và thử lại.';
    } else {
    $ho_ten = sanitize($_POST['ho_ten'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_pass = $_POST['confirm_pass'] ?? '';
    
    // Validate
    if (empty($ho_ten) || empty($email) || empty($phone) || empty($password)) {
        $error = 'Vui lòng điền đầy đủ thông tin';
    } elseif (strlen($password) < 6) {
        $error = 'Mật khẩu phải có ít nhất 6 ký tự';
    } elseif ($password !== $confirm_pass) {
        $error = 'Mật khẩu xác nhận không khớp';
    } else {
        // Kiểm tra email đã tồn tại
        $check = $conn->query("SELECT * FROM tblcustomer WHERE Cu_Email = '$email'");
        if ($check->num_rows > 0) {
            $error = 'Email đã được đăng ký';
        } else {
            // Hash mật khẩu
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert người dùng mới
            $insert = "INSERT INTO tblcustomer (Cu_Name, Cu_Email, Cu_Phone, Cu_Password, role, created_at)
                      VALUES ('$ho_ten', '$email', '$phone', '$hashed_pass', 'customer', NOW())";
            
            if ($conn->query($insert)) {
                $success = 'Đăng ký thành công! Vui lòng <a href="dang-nhap.php" style="color: var(--primary); font-weight: 600;">đăng nhập</a>';
            } else {
                $error = 'Lỗi: ' . $conn->error;
            }
        }
    }
    }
}

$pageTitle = "Đăng Ký";
require __DIR__ . '/includes/header.php';
?>

<style>
    .register-container { max-width: 500px; margin: 80px auto; }
    .register-form { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 40px; box-shadow: var(--shadow-lg); }
</style>

<div class="register-container">
    <h1 style="text-align: center; margin-bottom: 30px; font-size: 28px;">Đăng Ký Tài Khoản</h1>

    <?php if ($error): ?>
        <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 12px 16px; border-radius: var(--radius); margin-bottom: 20px;">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px 16px; border-radius: var(--radius); margin-bottom: 20px;">
            <?= $success ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="register-form">
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px;">Họ Tên</label>
            <input type="text" name="ho_ten" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius-sm);" placeholder="Nguyễn Văn A">
        </div>

        <div style="margin-bottom: 16px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px;">Email</label>
            <input type="email" name="email" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius-sm);" placeholder="your@email.com">
        </div>

        <div style="margin-bottom: 16px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px;">Số Điện Thoại</label>
            <input type="tel" name="phone" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius-sm);" placeholder="0123456789">
        </div>

        <div style="margin-bottom: 16px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px;">Mật Khẩu</label>
            <input type="password" name="password" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius-sm);" placeholder="••••••••">
            <small style="color: var(--text-muted);">Tối thiểu 6 ký tự</small>
        </div>

        <div style="margin-bottom: 24px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px;">Xác Nhận Mật Khẩu</label>
            <input type="password" name="confirm_pass" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius-sm);" placeholder="••••••••">
        </div>

        <?php
        $captchaFieldId = 'captchaRegister';
        $captchaBust = time();
        require __DIR__ . '/includes/captcha-widget.php';
        ?>

        <button type="submit" class="btn-primary" style="width: 100%; padding: 14px; font-size: 16px; margin-bottom: 16px;">
            Đăng Ký
        </button>
    </form>

    <div style="text-align: center; margin-top: 20px; color: var(--text-muted);">
        Đã có tài khoản? <a href="dang-nhap.php" style="color: var(--primary); font-weight: 600;">Đăng nhập</a>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; $conn->close(); ?>
