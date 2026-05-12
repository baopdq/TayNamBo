<?php require_once __DIR__ . '/includes/config.php';

if (!isLoggedIn()) {
    redirect(SITE_URL . '/dang-nhap.php');
}

$conn = getConnection();

// Lấy thông tin từ URL
$tour_id = intval($_GET['tour_id'] ?? 0);
$schedule_id = intval($_GET['schedule_id'] ?? 0);
$qty_adult = intval($_GET['qty_adult'] ?? 1);
$qty_child = intval($_GET['qty_child'] ?? 0);

if ($tour_id === 0 || $schedule_id === 0) {
    redirect(SITE_URL . PUBLIC_DANH_SACH_TOUR);
}

// Lấy thông tin tour
$tour = $conn->query("SELECT * FROM tbtour WHERE Tour_ID = $tour_id")->fetch_assoc();
if (!$tour) {
    redirect(SITE_URL . PUBLIC_DANH_SACH_TOUR);
}

// Lấy lịch khởi hành (kèm xe / đối tác điểm) — chỉ lịch còn mở đặt
$schedule = $conn->query("SELECT lk.*, x.bien_so, x.so_ghe AS xe_so_ghe, x.ten_loai_xe AS xe_loai,
    nx.ten_nha_xe,
    dtd.ten_don_vi AS doi_tac_ten, dtd.nguoi_lien_he AS doi_tac_lh, dtd.dien_thoai AS doi_tac_phone
    FROM lich_khai_hanh lk
    LEFT JOIN xe x ON lk.xe_id = x.id
    LEFT JOIN nha_xe nx ON x.nha_xe_id = nx.id
    LEFT JOIN doi_tac_diem_den dtd ON lk.doi_tac_diem_id = dtd.id
    WHERE lk.id = $schedule_id AND lk.tour_id = $tour_id
      AND COALESCE(lk.da_chot, 0) = 0
      AND lk.ngay_khai_hanh > CURDATE()")->fetch_assoc();
if (!$schedule) {
    redirect(SITE_URL . PUBLIC_CHI_TIET_TOUR . '?id=' . $tour_id);
}

// Lấy thông tin khách hàng
$customer = $conn->query("SELECT * FROM tblcustomer WHERE Cu_ID = " . $_SESSION['user_id'])->fetch_assoc();

// Tính tổng tiền
$total_price = ($tour['Price_Adult'] * $qty_adult) + ($tour['Price_Child'] * $qty_child);

$error = '';

// Nếu submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!captcha_verify($_POST['captcha'] ?? null)) {
        $error = 'Mã xác thực không đúng hoặc đã hết hạn. Vui lòng làm mới mã và thử lại.';
    } else {
    lich_khai_hanh_apply_auto_chot($conn);
    $passengers = $qty_adult + $qty_child;
    $live = $conn->query("SELECT so_cho_con, COALESCE(da_chot, 0) AS da_chot FROM lich_khai_hanh WHERE id = $schedule_id AND tour_id = $tour_id AND COALESCE(da_chot, 0) = 0 AND ngay_khai_hanh > CURDATE()")->fetch_assoc();
    if (!$live || (int) $live['da_chot'] !== 0) {
        $error = 'Lịch này đã chốt hoặc không còn mở đặt chỗ. Vui lòng chọn ngày khác.';
    } elseif ($passengers > (int) $live['so_cho_con']) {
        $error = 'Số khách vượt quá chỗ còn lại trên lịch đã chọn (' . (int) $live['so_cho_con'] . ' chỗ).';
    } else {
    $ma_don = 'DT-' . time();
    $ten_tour = sanitize($tour['ten_tour']);
    $ghi_chu = sanitize($_POST['ghi_chu'] ?? '');
    $ngay_dat = date('Y-m-d H:i:s');
    $pa = (float) $tour['Price_Adult'];
    $pc = (float) $tour['Price_Child'];

    // Insert vào bảng tblbooking
    $insert_query = "INSERT INTO tblbooking 
                     (ma_don, Cu_ID, Tour_ID, schedule_id, so_nguoi_lon, so_tre_em, tong_tien, gia_nguoi_lon, gia_tre_em, ghi_chu, trang_thai_don, trang_thai_tt, created_at)
                     VALUES 
                     ('$ma_don', " . (int) $_SESSION['user_id'] . ", $tour_id, $schedule_id, $qty_adult, $qty_child, $total_price, $pa, $pc, '$ghi_chu', 'pending', 'chua_thanh_toan', '$ngay_dat')";

    if ($conn->query($insert_query)) {
        // Cập nhật số chỗ còn lại
        $conn->query("UPDATE lich_khai_hanh SET so_cho_con = so_cho_con - ($qty_adult + $qty_child) WHERE id = $schedule_id");
        lich_khai_hanh_apply_auto_chot($conn);

        // Chuyển tới trang xác nhận
        redirect(SITE_URL . PUBLIC_XAC_NHAN_DAT . '?ma_don=' . $ma_don);
    } else {
        $error = "Đặt tour thất bại: " . $conn->error;
    }
    }
    }
}

$pageTitle = "Xác Nhận Đặt Tour";
require __DIR__ . '/includes/header.php';
?>

<div class="container" style="margin: 100px auto; max-width: 900px;">
    <h1 style="font-size: 32px; margin-bottom: 40px; text-align: center;">Xác Nhận Đặt Tour</h1>

    <?php if ($error !== ''): ?>
        <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: var(--radius); margin-bottom: 20px; color: #856404;">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        <!-- Form thông tin -->
        <form method="POST">
            <div class="info-box" style="background: var(--white); border: 1px solid var(--border); padding: 20px; border-radius: var(--radius); margin-bottom: 20px;">
                <h3 style="margin-bottom: 15px;">Thông Tin Đặt Tour</h3>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 5px;">Tour:</label>
                    <input type="text" value="<?= sanitize($tour['ten_tour']) ?>" disabled style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg);">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 5px;">Ngày Khởi Hành:</label>
                    <input type="text" value="<?= date('d/m/Y', strtotime($schedule['ngay_khai_hanh'])) ?>" disabled style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg);">
                </div>

                <div style="margin-bottom: 15px; padding: 14px; background: var(--bg); border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 14px; line-height: 1.6;">
                    <strong style="display:block;margin-bottom:8px;">Xe &amp; đón</strong>
                    <?php if (!empty($schedule['ten_nha_xe']) || !empty($schedule['bien_so'])): ?>
                        <div>Nhà xe: <?= sanitize(trim(($schedule['ten_nha_xe'] ?? '') . ($schedule['bien_so'] ? ' · ' . $schedule['bien_so'] : ''))) ?>
                            <?php if (!empty($schedule['xe_so_ghe'])): ?><span style="color:var(--text-muted);">(<?= (int) $schedule['xe_so_ghe'] ?> chỗ)</span><?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div style="color:var(--text-muted);">Chưa gán xe — sẽ thông báo sau.</div>
                    <?php endif; ?>
                    <?php if (!empty($schedule['gio_xuat_phat'])): ?>
                        <div>Giờ xuất phát dự kiến: <?= sanitize($schedule['gio_xuat_phat']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($schedule['diem_don_chinh'])): ?>
                        <div>Điểm đón: <?= nl2br(sanitize($schedule['diem_don_chinh'])) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($schedule['doi_tac_ten'])): ?>
                        <div style="margin-top:8px;padding-top:8px;border-top:1px solid var(--border);">
                            <strong>Đối tác tại điểm:</strong> <?= sanitize($schedule['doi_tac_ten']) ?>
                            <?php if (!empty($schedule['doi_tac_lh'])): ?> — <?= sanitize($schedule['doi_tac_lh']) ?><?php endif; ?>
                            <?php if (!empty($schedule['doi_tac_phone'])): ?> · <?= sanitize($schedule['doi_tac_phone']) ?><?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 5px;">Số Người Lớn:</label>
                        <input type="text" value="<?= $qty_adult ?>" disabled style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg);">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 5px;">Số Trẻ Em:</label>
                        <input type="text" value="<?= $qty_child ?>" disabled style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg);">
                    </div>
                </div>
            </div>

            <!-- Thông tin khách hàng -->
            <div class="info-box" style="background: var(--white); border: 1px solid var(--border); padding: 20px; border-radius: var(--radius); margin-bottom: 20px;">
                <h3 style="margin-bottom: 15px;">Thông Tin Khách Hàng</h3>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 5px;">Họ Tên:</label>
                    <input type="text" value="<?= sanitize($customer['Cu_Name']) ?>" disabled style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg);">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 5px;">Email:</label>
                    <input type="email" value="<?= sanitize($customer['Cu_Email']) ?>" disabled style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg);">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 5px;">Số Điện Thoại:</label>
                    <input type="text" value="<?= sanitize($customer['Cu_Phone']) ?>" disabled style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg);">
                </div>
            </div>

            <!-- Ghi chú -->
            <div class="info-box" style="background: var(--white); border: 1px solid var(--border); padding: 20px; border-radius: var(--radius); margin-bottom: 20px;">
                <h3 style="margin-bottom: 15px;">Ghi Chú Thêm</h3>
                <textarea name="ghi_chu" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); min-height: 100px; font-family: var(--font-body);" placeholder="Nhu cầu đặc biệt, yêu cầu thêm..."></textarea>
            </div>

            <?php
            $captchaFieldId = 'captchaBook';
            $captchaBust = time();
            require __DIR__ . '/includes/captcha-widget.php';
            ?>

            <!-- Nút submit -->
            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn-primary" style="flex: 1; padding: 14px; font-size: 16px;">
                    Xác Nhận Đặt Tour
                </button>
                <a href="<?= SITE_URL . PUBLIC_CHI_TIET_TOUR ?>?id=<?= $tour_id ?>" class="btn-outline" style="flex: 1; padding: 14px; text-align: center;">
                    Quay Lại
                </a>
            </div>
        </form>

        <!-- Tóm tắt giá -->
        <div>
            <div class="info-box" style="background: var(--white); border: 1px solid var(--border); padding: 20px; border-radius: var(--radius); position: sticky; top: 100px;">
                <h3 style="margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 15px;">Tóm Tắt Giá</h3>
                
                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>Người lớn (<?= $qty_adult ?> x <?= formatPrice($tour['Price_Adult']) ?>):</span>
                        <span><?= formatPrice($tour['Price_Adult'] * $qty_adult) ?></span>
                    </div>
                    <?php if ($qty_child > 0): ?>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Trẻ em (<?= $qty_child ?> x <?= formatPrice($tour['Price_Child']) ?>):</span>
                            <span><?= formatPrice($tour['Price_Child'] * $qty_child) ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <div style="border-top: 2px solid var(--border); padding-top: 15px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 700; font-size: 16px;">Tổng Cộng:</span>
                        <span style="font-size: 24px; font-weight: 700; color: var(--accent);"><?= formatPrice($total_price) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; $conn->close(); ?>
