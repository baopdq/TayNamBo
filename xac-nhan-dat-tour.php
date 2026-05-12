<?php require_once __DIR__ . '/includes/config.php';

$conn = getConnection();
$ma_don = trim((string) ($_GET['ma_don'] ?? ''));

if ($ma_don === '') {
    redirect(SITE_URL . PUBLIC_DANH_SACH_TOUR);
}

// Lấy chi tiết đơn đặt
$ma_esc = $conn->real_escape_string($ma_don);
$booking = $conn->query("SELECT dt.*, t.ten_tour, t.Tour_Image, d.Dest_Name,
                        lk.ngay_khai_hanh, lk.gio_xuat_phat, lk.diem_don_chinh,
                        x.bien_so, nx.ten_nha_xe, dtd.ten_don_vi AS doi_tac_ten
                        FROM tblbooking dt
                        JOIN tbtour t ON dt.Tour_ID = t.Tour_ID
                        LEFT JOIN tbldestination d ON t.Dest_ID = d.Dest_ID
                        LEFT JOIN lich_khai_hanh lk ON dt.schedule_id = lk.id
                        LEFT JOIN xe x ON lk.xe_id = x.id
                        LEFT JOIN nha_xe nx ON x.nha_xe_id = nx.id
                        LEFT JOIN doi_tac_diem_den dtd ON lk.doi_tac_diem_id = dtd.id
                        WHERE dt.ma_don = '$ma_esc'")->fetch_assoc();

if (!$booking) {
    redirect(SITE_URL . PUBLIC_DANH_SACH_TOUR);
}

$pageTitle = "Xác Nhận Đơn Đặt";
require __DIR__ . '/includes/header.php';
?>

<div class="container" style="margin-top: 100px; margin-bottom: 100px;">
    <div style="max-width: 700px; margin: 0 auto;">
        <!-- Success Card -->
        <div style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border: 2px solid #28a745; border-radius: var(--radius); padding: 40px; text-align: center; margin-bottom: 40px;">
            <div style="font-size: 60px; margin-bottom: 15px;">✓</div>
            <h1 style="color: #155724; margin-bottom: 10px; font-size: 28px;">Đặt Tour Thành Công!</h1>
            <p style="color: #155724; font-size: 16px; margin: 0;">Cảm ơn bạn đã đặt tour với chúng tôi</p>
        </div>

        <!-- Booking Details -->
        <div style="background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 30px; margin-bottom: 30px;">
            <h2 style="margin-bottom: 25px; font-size: 20px;">Chi Tiết Đơn Đặt</h2>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; padding-bottom: 25px; border-bottom: 1px solid var(--border);">
                <div>
                    <label style="color: var(--text-muted); font-size: 13px; text-transform: uppercase;">Mã Đơn</label>
                    <p style="font-weight: 700; font-size: 18px; color: var(--primary); margin: 8px 0;">
                        <?= sanitize($booking['ma_don']) ?>
                    </p>
                </div>
                <div>
                    <label style="color: var(--text-muted); font-size: 13px; text-transform: uppercase;">Ngày Đặt</label>
                    <p style="font-weight: 600; margin: 8px 0;">
                        <?= date('d/m/Y H:i', strtotime($booking['created_at'])) ?>
                    </p>
                </div>
            </div>

            <div style="margin-bottom: 25px;">
                <h3 style="margin-bottom: 15px; font-size: 16px;">Tour Đã Đặt</h3>
                <div style="background: var(--bg); padding: 15px; border-radius: var(--radius);">
                    <p style="font-weight: 600; margin: 0 0 8px 0;">
                        📍 <?= sanitize($booking['ten_tour']) ?>
                    </p>
                    <p style="margin: 0; color: var(--text-muted); font-size: 14px;">
                        Điểm đến: <?= sanitize($booking['Dest_Name'] ?? 'N/A') ?>
                    </p>
                    <?php if (!empty($booking['ngay_khai_hanh'])): ?>
                        <p style="margin: 10px 0 0; font-size: 14px; color: var(--text);">
                            Ngày chạy: <strong><?= date('d/m/Y', strtotime($booking['ngay_khai_hanh'])) ?></strong>
                            <?php if (!empty($booking['gio_xuat_phat'])): ?> · <?= sanitize($booking['gio_xuat_phat']) ?><?php endif; ?>
                        </p>
                    <?php endif; ?>
                    <?php if (!empty($booking['ten_nha_xe']) || !empty($booking['bien_so'])): ?>
                        <p style="margin: 6px 0 0; font-size: 14px; color: var(--text-muted);">
                            Xe: <?= sanitize(trim(($booking['ten_nha_xe'] ?? '') . ($booking['bien_so'] ? ' · ' . $booking['bien_so'] : ''))) ?>
                        </p>
                    <?php endif; ?>
                    <?php if (!empty($booking['diem_don_chinh'])): ?>
                        <p style="margin: 6px 0 0; font-size: 13px; color: var(--text-muted);">Đón: <?= nl2br(sanitize($booking['diem_don_chinh'])) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($booking['doi_tac_ten'])): ?>
                        <p style="margin: 6px 0 0; font-size: 13px; color: var(--text-muted);">Đối tác điểm: <?= sanitize($booking['doi_tac_ten']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="color: var(--text-muted); font-size: 13px;">Người Lớn</label>
                    <p style="font-weight: 600; margin: 6px 0;"><?= $booking['so_nguoi_lon'] ?> người</p>
                </div>
                <div>
                    <label style="color: var(--text-muted); font-size: 13px;">Trẻ Em</label>
                    <p style="font-weight: 600; margin: 6px 0;"><?= $booking['so_tre_em'] ?> người</p>
                </div>
            </div>
        </div>

        <!-- Price Summary -->
        <div style="background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 30px; margin-bottom: 30px;">
            <h2 style="margin-bottom: 20px; font-size: 16px;">Tóm Tắt Giá</h2>

            <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span>Giá người lớn × <?= $booking['so_nguoi_lon'] ?></span>
                    <span><?= formatPrice($booking['gia_nguoi_lon'] ?? 0) ?></span>
                </div>
                <?php if ($booking['so_tre_em'] > 0): ?>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Giá trẻ em × <?= $booking['so_tre_em'] ?></span>
                        <span><?= formatPrice($booking['gia_tre_em'] ?? 0) ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; font-weight: 700; font-size: 18px;">
                <span>Tổng Cộng</span>
                <span style="color: var(--accent);">
                    <?= formatPrice($booking['tong_tien']) ?>
                </span>
            </div>
        </div>

        <!-- Next Steps -->
        <div style="background: #e7f3ff; border: 1px solid #b3d9ff; border-radius: var(--radius); padding: 25px; margin-bottom: 30px;">
            <h3 style="margin-bottom: 15px; color: #0056b3; font-size: 16px;">Bước Tiếp Theo</h3>
            <ol style="margin: 0; padding-left: 20px; color: #0056b3;">
                <li style="margin-bottom: 10px;">Chúng tôi sẽ liên hệ với bạn để xác nhận đơn đặt trong 24 giờ</li>
                <li style="margin-bottom: 10px;">Vui lòng kiểm tra email hoặc sms để nhận thông báo</li>
                <li>Hoàn thành thanh toán để hoàn tất đặt tour</li>
            </ol>
        </div>

        <!-- Action Buttons -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <a href="dat-tour-cua-toi.php" class="btn-primary" style="display: block; text-align: center; padding: 12px;">
                Xem Đơn Của Tôi
            </a>
            <a href="danh-sach-tour.php" class="btn-outline" style="display: block; text-align: center; padding: 12px;">
                Khám Phá Tour Khác
            </a>
        </div>

        <!-- Contact Info -->
        <div style="background: var(--bg); padding: 20px; border-radius: var(--radius); margin-top: 30px; text-align: center; color: var(--text-muted); font-size: 14px;">
            <p style="margin: 0 0 8px 0;">Cần hỗ trợ? Liên hệ với chúng tôi</p>
            <p style="margin: 0;">📞 Hotline: (84+) 123 456 789 | 📧 Email: info@dulichtaynam.vn</p>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; $conn->close(); ?>
