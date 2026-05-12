<?php require_once __DIR__ . '/includes/config.php';

$conn = getConnection();
$success = '';
$error = '';

// Xử lý form liên hệ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ho_ten = sanitize($_POST['ho_ten'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $dien_thoai = sanitize($_POST['dien_thoai'] ?? '');
    $tieu_de = sanitize($_POST['tieu_de'] ?? '');
    $noi_dung = sanitize($_POST['noi_dung'] ?? '');

    if (empty($ho_ten) || empty($email) || empty($dien_thoai) || empty($tieu_de) || empty($noi_dung)) {
        $error = 'Vui lòng điền đầy đủ thông tin';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ';
    } else {
        // Lưu vào database
        $conn->query("INSERT INTO tblcontact (Ho_Ten, Email, Dien_Thoai, Tieu_De, Noi_Dung, Ngay_Tao) 
                     VALUES ('$ho_ten', '$email', '$dien_thoai', '$tieu_de', '$noi_dung', NOW())");
        
        // Gửi email thông báo
        $to = "info@dulichtaynam.vn";
        $subject = "Liên hệ mới từ: $ho_ten";
        $message = "Tên: $ho_ten\nEmail: $email\nĐiện thoại: $dien_thoai\n\nTiêu đề: $tieu_de\n\nNội dung:\n$noi_dung";
        $headers = "From: $email\r\nReply-To: $email";
        
        if (mail($to, $subject, $message, $headers)) {
            $success = 'Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi trong 24 giờ.';
        } else {
            $success = 'Tin nhắn của bạn đã được lưu lại. Chúng tôi sẽ liên hệ với bạn sớm.';
        }
    }
}

$pageTitle = "Liên Hệ Với Chúng Tôi";
require __DIR__ . '/includes/header.php';
?>

<style>
    .contact-container { max-width: 1000px; margin: 100px auto 60px; padding: 0 20px; }
    .contact-header { text-align: center; margin-bottom: 60px; }
    .contact-content { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
    .contact-info-item { margin-bottom: 30px; }
    .contact-icon { font-size: 32px; margin-bottom: 15px; }
    .contact-label { color: var(--text-muted); font-size: 14px; margin-bottom: 5px; }
    .contact-value { font-weight: 600; font-size: 16px; }
</style>

<div class="contact-container">
    <div class="contact-header">
        <h1 style="font-size: 40px; margin-bottom: 15px;">Liên Hệ Với Chúng Tôi</h1>
        <p style="font-size: 18px; color: var(--text-muted);">Chúng tôi luôn sẵn sàng hỗ trợ bạn</p>
    </div>

    <div class="contact-content">
        <!-- Form liên hệ -->
        <div>
            <h2 style="margin-bottom: 25px; font-size: 22px;">Gửi Tin Nhắn</h2>
            
            <?php if ($error): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: var(--radius); margin-bottom: 20px;">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: var(--radius); margin-bottom: 20px;">
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <form method="POST" style="background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 30px;">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Họ Tên *</label>
                    <input type="text" name="ho_ten" required 
                           style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius-sm);">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Email *</label>
                    <input type="email" name="email" required 
                           style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius-sm);">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Số Điện Thoại *</label>
                    <input type="tel" name="dien_thoai" required 
                           style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius-sm);">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Tiêu Đề *</label>
                    <input type="text" name="tieu_de" required 
                           style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius-sm);">
                </div>

                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Nội Dung *</label>
                    <textarea name="noi_dung" rows="6" required 
                              style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); resize: vertical;"></textarea>
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; padding: 12px; font-size: 16px; font-weight: 600;">
                    Gửi Tin Nhắn
                </button>
            </form>
        </div>

        <!-- Thông tin liên hệ -->
        <div>
            <h2 style="margin-bottom: 25px; font-size: 22px;">Thông Tin Liên Hệ</h2>

            <div class="contact-info-item">
                <div class="contact-icon">📍</div>
                <div class="contact-label">Địa Chỉ</div>
                <div class="contact-value">
                    123 Đường Trần Hưng Đạo<br>
                    Thành phố Cần Thơ, Việt Nam
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-icon">📞</div>
                <div class="contact-label">Số Điện Thoại</div>
                <div class="contact-value">
                    <a href="tel:+84912345678" style="color: var(--primary); text-decoration: none;">
                        +84 (0) 912 345 678
                    </a><br>
                    <a href="tel:+84987654321" style="color: var(--primary); text-decoration: none;">
                        +84 (0) 987 654 321
                    </a>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-icon">📧</div>
                <div class="contact-label">Email</div>
                <div class="contact-value">
                    <a href="mailto:info@dulichtaynam.vn" style="color: var(--primary); text-decoration: none;">
                        info@dulichtaynam.vn
                    </a><br>
                    <a href="mailto:support@dulichtaynam.vn" style="color: var(--primary); text-decoration: none;">
                        support@dulichtaynam.vn
                    </a>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-icon">⏰</div>
                <div class="contact-label">Giờ Làm Việc</div>
                <div class="contact-value">
                    Thứ Hai - Thứ Sáu: 8:00 - 17:00<br>
                    Thứ Bảy: 9:00 - 12:00<br>
                    Chủ Nhật: Nghỉ
                </div>
            </div>

            <!-- Mạng xã hội -->
            <div style="margin-top: 40px; padding-top: 25px; border-top: 1px solid var(--border);">
                <h3 style="margin-bottom: 20px; font-size: 16px;">Theo Dõi Chúng Tôi</h3>
                <div style="display: flex; gap: 15px;">
                    <a href="https://facebook.com" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: var(--bg); border-radius: 50%; text-decoration: none; font-size: 20px;">👍</a>
                    <a href="https://linkedin.com" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: var(--bg); border-radius: 50%; text-decoration: none; font-size: 20px;">🔗</a>
                    <a href="https://instagram.com" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: var(--bg); border-radius: 50%; text-decoration: none; font-size: 20px;">📷</a>
                    <a href="https://youtube.com" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: var(--bg); border-radius: 50%; text-decoration: none; font-size: 20px;">🎥</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bản đồ -->
    <div style="margin-top: 60px;">
        <h2 style="margin-bottom: 25px; font-size: 22px;">Vị Trí Của Chúng Tôi</h2>
        <div style="border-radius: var(--radius); overflow: hidden; height: 400px;">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3927.4346868046617!2d105.76823!3d10.01654!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a088df7b000001%3A0x1234567890abcdef!2sCan%20Tho!5e0!3m2!1sen!2svn!4v1234567890" 
                    width="100%" height="100%" style="border:none;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; $conn->close(); ?>
