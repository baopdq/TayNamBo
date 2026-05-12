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
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Vui lòng nhập email và mật khẩu';
    } else {
        // Kiểm tra email
        $user = $conn->query("SELECT * FROM tblcustomer WHERE Cu_Email = '$email'")->fetch_assoc();
        
        if (!$user) {
            $error = 'Email không tồn tại';
        } elseif (!password_verify($password, $user['Cu_Password'])) {
            $error = 'Mật khẩu không chính xác';
        } else {
            // Đăng nhập thành công
            $_SESSION['user_id'] = $user['Cu_ID'];
            $_SESSION['ho_ten'] = $user['Cu_Name'];
            $_SESSION['email'] = $user['Cu_Email'];
            $_SESSION['role'] = $user['role'] ?? 'customer';
            
            $redirect = $_GET['redirect'] ?? SITE_URL;
            redirect($redirect);
        }
    }
    }
}

$pageTitle = "Đăng Nhập";
require __DIR__ . '/includes/header.php';
?>

<style>
    .login-container { max-width: 450px; margin: 80px auto; }
    .login-form { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 40px; box-shadow: var(--shadow-lg); }
</style>

<div class="login-container">
    <h1 style="text-align: center; margin-bottom: 30px; font-size: 28px;">Đăng Nhập</h1>

    <?php if ($error): ?>
        <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 12px 16px; border-radius: var(--radius); margin-bottom: 20px;">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="login-form">
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Email</label>
            <input type="email" name="email" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius-sm);" placeholder="your@email.com">
        </div>

        <div style="margin-bottom: 24px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Mật Khẩu</label>
            <input type="password" name="password" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius-sm);" placeholder="••••••••">
        </div>

        <?php
        $captchaFieldId = 'captchaLogin';
        $captchaBust = time();
        require __DIR__ . '/includes/captcha-widget.php';
        ?>

        <button type="submit" class="btn-primary" style="width: 100%; padding: 14px; font-size: 16px; margin-bottom: 16px;">
            Đăng Nhập
        </button>
    </form>

    <div style="text-align: center; margin-top: 20px; color: var(--text-muted);">
        Chưa có tài khoản? <a href="dang-ky.php" style="color: var(--primary); font-weight: 600;">Đăng ký ngay</a>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; $conn->close(); ?>
