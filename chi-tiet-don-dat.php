<?php require_once __DIR__ . '/includes/config.php';

if (!isLoggedIn()) {
    redirect(SITE_URL . '/dang-nhap.php');
}

$conn = getConnection();
$user_id = $_SESSION['user_id'];
$booking_id = intval($_GET['id'] ?? 0);

// Lấy chi tiết đơn
$query = "SELECT dt.*, t.ten_tour, t.Tour_Image, d.Dest_Name, c.Cu_Name, c.Cu_Email, c.Cu_Phone,
          lk.ngay_khai_hanh, lk.diem_don_chinh, lk.gio_xuat_phat,
          x.bien_so, x.so_ghe AS xe_so_ghe, nx.ten_nha_xe,
          dtd.ten_don_vi AS doi_tac_ten, dtd.nguoi_lien_he AS doi_tac_lh, dtd.dien_thoai AS doi_tac_phone
          FROM tblbooking dt
          JOIN tbtour t ON dt.Tour_ID = t.Tour_ID
          LEFT JOIN tbldestination d ON t.Dest_ID = d.Dest_ID
          JOIN tblcustomer c ON dt.Cu_ID = c.Cu_ID
          LEFT JOIN lich_khai_hanh lk ON dt.schedule_id = lk.id
          LEFT JOIN xe x ON lk.xe_id = x.id
          LEFT JOIN nha_xe nx ON x.nha_xe_id = nx.id
          LEFT JOIN doi_tac_diem_den dtd ON lk.doi_tac_diem_id = dtd.id
          WHERE dt.B_ID = $booking_id";

$booking = $conn->query($query)->fetch_assoc();

if (!$booking) {
    redirect(SITE_URL . '/dat-tour-cua-toi.php');
}
// Kiểm tra quyền (khách hàng hoặc admin)
if ($booking['Cu_ID'] != $user_id && $_SESSION['role'] !== 'admin') {
    redirect(SITE_URL . '/dat-tour-cua-toi.php');
}

$pageTitle = "Chi Tiết Đơn Đặt - " . $booking['ma_don'];
require __DIR__ . '/includes/header.php';

function getStatusBadge($status) {
    $badges = [
        'pending' => ['bg' => '#fff3cd', 'color' => '#856404', 'text' => 'Đang chờ'],
        'confirmed' => ['bg' => '#d4edda', 'color' => '#155724', 'text' => 'Đã xác nhận'],
        'cancelled' => ['bg' => '#f8d7da', 'color' => '#721c24', 'text' => 'Đã hủy'],
    ];
    $badge = $badges[$status] ?? $badges['pending'];
    return "<span style='background: {$badge['bg']}; color: {$badge['color']}; padding: 6px 12px; border-radius: 20px; font-weight: 600;'>{$badge['text']}</span>";
}

function getPaymentBadge($status) {
    $badges = [
        'chua_thanh_toan' => ['bg' => '#fff3cd', 'color' => '#856404', 'text' => 'Chưa thanh toán'],
        'da_thanh_toan' => ['bg' => '#d4edda', 'color' => '#155724', 'text' => 'Đã thanh toán'],
    ];
    $badge = $badges[$status] ?? $badges['chua_thanh_toan'];
    return "<span style='background: {$badge['bg']}; color: {$badge['color']}; padding: 6px 12px; border-radius: 20px; font-weight: 600;'>{$badge['text']}</span>";
}
?>

<div class="container" style="margin-top: 100px; margin-bottom: 100px;">
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h1>Chi Tiết Đơn Đặt</h1>
            <a href="dat-tour-cua-toi.php" style="color: var(--primary); text-decoration: none;">← Quay lại</a>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
            <!-- Chi tiết chính -->
            <div>
                <!-- Thông tin đơn -->
                <div style="background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 25px; margin-bottom: 25px;">
                    <h2 style="margin-bottom: 20px; font-size: 20px;">Thông Tin Đơn</h2>
                    
                    <table style="width: 100%;">
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px 0; width: 35%; color: var(--text-muted);">Mã Đơn</td>
                            <td style="padding: 12px 0; font-weight: 600;"><?= sanitize($booking['ma_don']) ?></td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px 0; color: var(--text-muted);">Ngày Đặt</td>
                            <td style="padding: 12px 0;"><?= date('d/m/Y H:i', strtotime($booking['created_at'])) ?></td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px 0; color: var(--text-muted);">Trạng Thái</td>
                            <td style="padding: 12px 0;"><?= getStatusBadge($booking['trang_thai_don']) ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 0; color: var(--text-muted);">Thanh Toán</td>
                            <td style="padding: 12px 0;"><?= getPaymentBadge($booking['trang_thai_tt']) ?></td>
                        </tr>
                    </table>
                </div>

                <!-- Thông tin khách hàng -->
                <div style="background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 25px; margin-bottom: 25px;">
                    <h2 style="margin-bottom: 20px; font-size: 20px;">Thông Tin Khách Hàng</h2>
                    
                    <div style="margin-bottom: 16px;">
                        <label style="color: var(--text-muted); font-size: 14px;">Họ Tên</label>
                        <p style="font-weight: 600; margin: 6px 0;"><?= sanitize($booking['Cu_Name']) ?></p>
                    </div>
                    
                    <div style="margin-bottom: 16px;">
                        <label style="color: var(--text-muted); font-size: 14px;">Email</label>
                        <p style="margin: 6px 0;"><?= sanitize($booking['Cu_Email']) ?></p>
                    </div>
                    
                    <div style="margin-bottom: 16px;">
                        <label style="color: var(--text-muted); font-size: 14px;">Số Điện Thoại</label>
                        <p style="margin: 6px 0;"><?= sanitize($booking['Cu_Phone']) ?></p>
                    </div>
                </div>

                <!-- Thông tin tour -->
                <div style="background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 25px;">
                    <h2 style="margin-bottom: 20px; font-size: 20px;">Thông Tin Tour</h2>
                    
                    <div style="margin-bottom: 16px;">
                        <label style="color: var(--text-muted); font-size: 14px;">Tên Tour</label>
                        <p style="font-weight: 600; margin: 6px 0;"><?= sanitize($booking['ten_tour']) ?></p>
                    </div>
                    
                    <div style="margin-bottom: 16px;">
                        <label style="color: var(--text-muted); font-size: 14px;">Điểm Đến</label>
                        <p style="margin: 6px 0;"><?= sanitize($booking['Dest_Name'] ?? 'N/A') ?></p>
                    </div>

                    <?php if (!empty($booking['ngay_khai_hanh'])): ?>
                        <div style="margin-bottom: 16px; padding: 14px; background: var(--bg); border-radius: var(--radius-sm); font-size: 14px; line-height: 1.6;">
                            <strong>Lịch &amp; vận chuyển</strong>
                            <p style="margin: 8px 0 0;">Ngày chạy: <?= date('d/m/Y', strtotime($booking['ngay_khai_hanh'])) ?><?php if (!empty($booking['gio_xuat_phat'])): ?> · <?= sanitize($booking['gio_xuat_phat']) ?><?php endif; ?></p>
                            <?php if (!empty($booking['ten_nha_xe']) || !empty($booking['bien_so'])): ?>
                                <p style="margin: 4px 0 0;">Xe: <?= sanitize(trim(($booking['ten_nha_xe'] ?? '') . ($booking['bien_so'] ? ' · ' . $booking['bien_so'] : ''))) ?><?php if (!empty($booking['xe_so_ghe'])): ?> (<?= (int) $booking['xe_so_ghe'] ?> chỗ)<?php endif; ?></p>
                            <?php endif; ?>
                            <?php if (!empty($booking['diem_don_chinh'])): ?>
                                <p style="margin: 4px 0 0;">Đón: <?= nl2br(sanitize($booking['diem_don_chinh'])) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($booking['doi_tac_ten'])): ?>
                                <p style="margin: 4px 0 0;">Đối tác điểm: <?= sanitize($booking['doi_tac_ten']) ?><?php if (!empty($booking['doi_tac_phone'])): ?> · <?= sanitize($booking['doi_tac_phone']) ?><?php endif; ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div style="margin-bottom: 16px;">
                        <label style="color: var(--text-muted); font-size: 14px;">Người Lớn</label>
                        <p style="margin: 6px 0;"><?= $booking['so_nguoi_lon'] ?> người</p>
                    </div>
                    
                    <div>
                        <label style="color: var(--text-muted); font-size: 14px;">Trẻ Em</label>
                        <p style="margin: 6px 0;"><?= $booking['so_tre_em'] ?> người</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Tóm tắt giá -->
            <div>
                <div style="background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 25px; position: sticky; top: 120px;">
                    <h2 style="margin-bottom: 20px; font-size: 18px;">Tóm Tắt Giá</h2>
                    
                    <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border);">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span>Người lớn × <?= $booking['so_nguoi_lon'] ?></span>
                            <span><?= formatPrice($booking['gia_nguoi_lon'] ?? 0) ?></span>
                        </div>
                        <?php if ($booking['so_tre_em'] > 0): ?>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Trẻ em × <?= $booking['so_tre_em'] ?></span>
                                <span><?= formatPrice($booking['gia_tre_em'] ?? 0) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                        <strong>Tổng Cộng</strong>
                        <strong style="font-size: 20px; color: var(--accent);">
                            <?= formatPrice($booking['tong_tien']) ?>
                        </strong>
                    </div>

                    <?php if ($booking['trang_thai_tt'] === 'chua_thanh_toan' && $booking['trang_thai_don'] === 'confirmed'): ?>
                        <button onclick="alert('Chức năng thanh toán sẽ được cập nhật')" class="btn-primary" style="width: 100%; padding: 12px;">
                            Thanh Toán Ngay
                        </button>
                    <?php elseif ($booking['trang_thai_don'] === 'pending'): ?>
                        <div style="background: var(--bg); padding: 15px; border-radius: var(--radius); text-align: center; color: var(--text-muted);">
                            Đang chờ xác nhận từ admin
                        </div>
                    <?php elseif ($booking['trang_thai_don'] === 'cancelled'): ?>
                        <div style="background: #f8d7da; padding: 15px; border-radius: var(--radius); text-align: center; color: #721c24;">
                            Đơn này đã bị hủy
                        </div>
                    <?php else: ?>
                        <div style="background: #d4edda; padding: 15px; border-radius: var(--radius); text-align: center; color: #155724;">
                            ✓ Đã thanh toán
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; $conn->close(); ?>
