<?php require_once __DIR__ . '/includes/config.php';

if (!isLoggedIn()) {
    redirect(SITE_URL . '/dang-nhap.php');
}

$conn = getConnection();
$user_id = $_SESSION['user_id'];

// Lấy danh sách đơn đặt tour
$query = "SELECT dt.*, t.ten_tour, t.Tour_Image, d.Dest_Name,
          lk.ngay_khai_hanh, lk.gio_xuat_phat,
          x.bien_so, nx.ten_nha_xe
          FROM tblbooking dt
          JOIN tbtour t ON dt.Tour_ID = t.Tour_ID
          LEFT JOIN tbldestination d ON t.Dest_ID = d.Dest_ID
          LEFT JOIN lich_khai_hanh lk ON dt.schedule_id = lk.id
          LEFT JOIN xe x ON lk.xe_id = x.id
          LEFT JOIN nha_xe nx ON x.nha_xe_id = nx.id
          WHERE dt.Cu_ID = $user_id
          ORDER BY dt.created_at DESC";
$result = $conn->query($query);
$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

$pageTitle = "Đơn Đặt Tour Của Tôi";
require __DIR__ . '/includes/header.php';

// Hàm định dạng trạng thái
function getStatusBadge($status) {
    $badges = [
        'pending' => ['background' => '#fff3cd', 'color' => '#856404', 'text' => 'Đang chờ'],
        'confirmed' => ['background' => '#d4edda', 'color' => '#155724', 'text' => 'Đã xác nhận'],
        'cancelled' => ['background' => '#f8d7da', 'color' => '#721c24', 'text' => 'Đã hủy'],
    ];
    $badge = $badges[$status] ?? $badges['pending'];
    return "<span style='background: {$badge['background']}; color: {$badge['color']}; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;'>{$badge['text']}</span>";
}
?>

<div class="container" style="margin-top: 100px; margin-bottom: 100px;">
    <h1 style="margin-bottom: 40px;">Danh Sách Đơn Đặt Tour</h1>

    <?php if (count($bookings) > 0): ?>
        <div style="background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--bg); border-bottom: 1px solid var(--border);">
                        <th style="padding: 15px; text-align: left; font-weight: 600;">Mã Đơn</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600;">Tour</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600;">Ngày Đặt</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600;">Số Khách</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600;">Giá Tiền</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600;">Trạng Thái</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600;">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 15px; font-weight: 600; color: var(--primary);">
                                <?= sanitize($booking['ma_don']) ?>
                            </td>
                            <td style="padding: 15px;">
                                <div>
                                    <strong><?= sanitize($booking['ten_tour']) ?></strong>
                                    <div style="font-size: 13px; color: var(--text-muted);">
                                        📍 <?= sanitize($booking['Dest_Name'] ?? 'N/A') ?>
                                    </div>
                                    <?php if (!empty($booking['ngay_khai_hanh'])): ?>
                                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                                            Ngày chạy: <?= date('d/m/Y', strtotime($booking['ngay_khai_hanh'])) ?>
                                            <?php if (!empty($booking['ten_nha_xe']) || !empty($booking['bien_so'])): ?>
                                                · <?= sanitize(trim(($booking['ten_nha_xe'] ?? '') . ($booking['bien_so'] ? ' ' . $booking['bien_so'] : ''))) ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td style="padding: 15px; color: var(--text-muted);">
                                <?= date('d/m/Y H:i', strtotime($booking['created_at'])) ?>
                            </td>
                            <td style="padding: 15px;">
                                <?= $booking['so_nguoi_lon'] ?> người lớn
                                <?php if ($booking['so_tre_em'] > 0): ?>
                                    , <?= $booking['so_tre_em'] ?> trẻ em
                                <?php endif; ?>
                            </td>
                            <td style="padding: 15px; font-weight: 600; color: var(--accent);">
                                <?= formatPrice($booking['tong_tien']) ?>
                            </td>
                            <td style="padding: 15px;">
                                <?= getStatusBadge($booking['trang_thai_don']) ?>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <a href="chi-tiet-don-dat.php?id=<?= $booking['B_ID'] ?>" class="btn-outline" 
                                   style="padding: 6px 12px; font-size: 13px;">Chi tiết</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div style="background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 60px 20px; text-align: center;">
            <p style="font-size: 16px; color: var(--text-muted); margin-bottom: 20px;">
                Bạn chưa có đơn đặt tour nào.
            </p>
            <a href="danh-sach-tour.php" class="btn-primary" style="display: inline-block; padding: 12px 24px;">
                Khám Phá Tour
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; $conn->close(); ?>
