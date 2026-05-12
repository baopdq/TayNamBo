<?php require_once __DIR__ . '/includes/config.php';
$conn = getConnection();
ensureTourImagesTable($conn);

if (!isset($_GET['id'])) {
    redirect(SITE_URL . PUBLIC_DANH_SACH_TOUR);
}

$tour_id = intval($_GET['id']);
$query = "SELECT t.*, tc.Cat_Name, d.Dest_Name 
          FROM tbtour t 
          JOIN tbtourcategory tc ON t.Cat_ID = tc.Cat_ID
          LEFT JOIN tbldestination d ON t.Dest_ID = d.Dest_ID
          WHERE t.Tour_ID = $tour_id AND t.trang_thai = 'active'";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    redirect(SITE_URL . PUBLIC_DANH_SACH_TOUR);
}

$tour = $result->fetch_assoc();
$tourGallery = getTourGallery($conn, $tour_id);
$primaryImage = getTourDisplayImage($tour);

$coverPath = trim((string) ($tour['Tour_Image'] ?? ''));
$galleryThumbs = $tourGallery;
if ($coverPath !== '') {
    $coverInGallery = false;
    foreach ($tourGallery as $row) {
        if (($row['image_path'] ?? '') === $coverPath) {
            $coverInGallery = true;
            break;
        }
    }
    if (!$coverInGallery) {
        array_unshift($galleryThumbs, ['id' => 0, 'image_path' => $coverPath, 'sort_order' => -1]);
    }
}

$schedule_query = "SELECT lk.*, x.bien_so, x.so_ghe AS xe_so_ghe, x.ten_loai_xe AS xe_loai,
    nx.ten_nha_xe,
    dtd.ten_don_vi AS doi_tac_ten, dtd.nguoi_lien_he AS doi_tac_lh, dtd.dien_thoai AS doi_tac_phone
    FROM lich_khai_hanh lk
    LEFT JOIN xe x ON lk.xe_id = x.id
    LEFT JOIN nha_xe nx ON x.nha_xe_id = nx.id
    LEFT JOIN doi_tac_diem_den dtd ON lk.doi_tac_diem_id = dtd.id
    WHERE lk.tour_id = $tour_id
      AND COALESCE(lk.da_chot, 0) = 0
      AND lk.ngay_khai_hanh > CURDATE()
    ORDER BY lk.ngay_khai_hanh";
$schedule_result = $conn->query($schedule_query);
$schedules = [];
while ($row = $schedule_result->fetch_assoc()) {
    $schedules[] = $row;
}
$hasSchedules = count($schedules) > 0;

$pageTitle = $tour['ten_tour'];
require __DIR__ . '/includes/header.php';

$loginRedirect = rawurlencode('chi-tiet-tour.php?id=' . $tour_id);
?>

<style>
    .detail-hero { background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%); color: var(--white); padding: 60px 20px; text-align: center; }
    .detail-hero h1 { font-size: 42px; font-weight: 700; margin-bottom: 16px; }
    .detail-hero p { font-size: 18px; opacity: 0.9; }
    .detail-container { display: grid; grid-template-columns: 2fr 1fr; gap: 40px; margin: 40px 0; }
    .detail-image { width: 100%; height: 400px; object-fit: cover; border-radius: var(--radius); }
    .detail-gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr)); gap: 10px; margin-top: 12px; }
    .detail-gallery img { width: 100%; height: 70px; object-fit: cover; border-radius: var(--radius-sm); border: 2px solid transparent; cursor: pointer; }
    .detail-gallery img.active { border-color: var(--primary); }
    .booking-form { background: var(--white); padding: 30px; border-radius: var(--radius); border: 1px solid var(--border); }
    .info-box { background: var(--bg); padding: 20px; border-radius: var(--radius); margin-bottom: 20px; }
    .info-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
    .info-label { font-weight: 600; color: var(--text); }
    .info-value { color: var(--text-muted); }
</style>

<div class="detail-hero">
    <div class="container">
        <h1><?= sanitize($tour['ten_tour']) ?></h1>
        <p>📍 <?= sanitize($tour['Dest_Name']) ?> | ⭐ <?= $tour['rating'] ?? 4.8 ?>/5</p>
    </div>
</div>

<div class="container" style="margin-top: 40px; margin-bottom: 40px;">
    <div class="detail-container">
        <div>
            <img id="mainTourImage" src="<?= sanitize($primaryImage) ?>" alt="<?= sanitize($tour['ten_tour']) ?>" class="detail-image">
            <?php if (!empty($galleryThumbs)): ?>
                <div class="detail-gallery">
                    <?php foreach ($galleryThumbs as $idx => $img): ?>
                        <img src="<?= sanitize($img['image_path']) ?>"
                             alt="<?= sanitize($tour['ten_tour']) ?>"
                             class="<?= ($img['image_path'] ?? '') === $primaryImage ? 'active' : '' ?>"
                             onclick="setMainTourImage(this)">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div style="margin-top: 30px;">
                <h2 style="font-size: 24px; margin-bottom: 16px;">Mô tả tour</h2>
                <p style="line-height: 1.8; color: var(--text-muted);">
                    <?= nl2br(sanitize($tour['mo_ta'])) ?>
                </p>
            </div>
            <div style="margin-top: 40px;">
                <h2 style="font-size: 24px; margin-bottom: 20px;">Hành trình chi tiết</h2>
                <div style="background: var(--bg); padding: 20px; border-radius: var(--radius); line-height: 1.8; color: var(--text);">
                    <?= !empty($tour['itinerary']) ? nl2br(sanitize($tour['itinerary'])) : 'Chưa cập nhật' ?>
                </div>
            </div>
            <div style="margin-top: 40px;">
                <h2 style="font-size: 24px; margin-bottom: 20px;">Chính sách hủy bỏ</h2>
                <div style="background: var(--bg); padding: 20px; border-radius: var(--radius); line-height: 1.8; color: var(--text);">
                    <?= !empty($tour['cancellation_policy']) ? nl2br(sanitize($tour['cancellation_policy'])) : 'Xem điều kiện hủy tại quầy' ?>
                </div>
            </div>
        </div>

        <div>
            <div class="booking-form">
                <h3 style="font-size: 20px; margin-bottom: 20px;">Đặt Tour Ngay</h3>
                <div class="info-box">
                    <div class="info-row">
                        <span class="info-label">Người lớn:</span>
                        <span style="font-size: 18px; font-weight: 700; color: var(--accent);"><?= formatPrice($tour['Price_Adult']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Trẻ em:</span>
                        <span style="font-size: 16px; color: var(--accent);"><?= formatPrice($tour['Price_Child']) ?></span>
                    </div>
                </div>

                <?php if ($hasSchedules): ?>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 10px;">Chọn ngày khởi hành:</label>
                        <select id="schedule_id" onchange="updateScheduleHint(this)" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm);">
                            <option value="">-- Chọn lịch --</option>
                            <?php foreach ($schedules as $sched): ?>
                                <?php
                                $hint = 'Còn ' . (int) $sched['so_cho_con'] . ' chỗ';
                                if (!empty($sched['gio_xuat_phat'])) {
                                    $hint .= ' · Xuất phát ' . $sched['gio_xuat_phat'];
                                }
                                if (!empty($sched['ten_nha_xe']) && !empty($sched['bien_so'])) {
                                    $hint .= ' · ' . $sched['ten_nha_xe'] . ' — ' . $sched['bien_so'];
                                    if (!empty($sched['xe_so_ghe'])) {
                                        $hint .= ' (' . (int) $sched['xe_so_ghe'] . ' chỗ/xe)';
                                    }
                                }
                                if (!empty($sched['doi_tac_ten'])) {
                                    $hint .= ' · Đối tác điểm: ' . $sched['doi_tac_ten'];
                                }
                                if (!empty($sched['diem_don_chinh'])) {
                                    $hint .= ' · Đón: ' . preg_replace('/\s+/', ' ', trim($sched['diem_don_chinh']));
                                }
                                $optLabel = date('d/m/Y', strtotime($sched['ngay_khai_hanh'])) . ' — ' . (int) $sched['so_cho_con'] . ' chỗ';
                                if (!empty($sched['bien_so'])) {
                                    $optLabel .= ' · ' . $sched['bien_so'];
                                }
                                ?>
                                <option value="<?= (int) $sched['id'] ?>" data-hint="<?= htmlspecialchars($hint, ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars($optLabel, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p id="schedule_hint" style="margin-top: 10px; font-size: 13px; color: var(--text-muted); line-height: 1.5; min-height: 1.5em;"></p>
                    </div>
                <?php else: ?>
                    <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 16px;">Tour chưa có ngày khởi hành còn mở đặt (có thể đã chốt chỗ / đến ngày đi / hết chỗ).</p>
                <?php endif; ?>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 8px;">Người lớn:</label>
                        <input type="number" id="qty_adult" min="1" value="1" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm);">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 8px;">Trẻ em:</label>
                        <input type="number" id="qty_child" min="0" value="0" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm);">
                    </div>
                </div>

                <button onclick="bookTour(<?= $tour_id ?>)" class="btn-primary" style="width: 100%; padding: 14px; font-size: 16px;" <?= $hasSchedules ? '' : 'disabled title="Chưa có lịch"' ?>>
                    Đặt Tour Ngay
                </button>
                <p style="text-align: center; color: var(--text-muted); font-size: 13px; margin-top: 12px;">
                    Vui lòng đăng nhập để đặt tour
                </p>
            </div>

            <div class="info-box" style="margin-top: 20px;">
                <h4 style="margin-bottom: 12px;">Thời gian tour:</h4>
                <p><?= sanitize($tour['Duration']) ?></p>
            </div>

            <div class="info-box">
                <h4 style="margin-bottom: 12px;">Phương tiện:</h4>
                <p><?= sanitize($tour['Vehicle']) ?></p>
            </div>
        </div>
    </div>
</div>

<script>
function updateScheduleHint(sel) {
    const el = document.getElementById('schedule_hint');
    if (!el || !sel) return;
    const opt = sel.options[sel.selectedIndex];
    el.textContent = opt && opt.dataset && opt.dataset.hint ? opt.dataset.hint : '';
}

function setMainTourImage(thumb) {
    const main = document.getElementById('mainTourImage');
    if (!main || !thumb) return;
    main.src = thumb.src;
    document.querySelectorAll('.detail-gallery img').forEach((img) => img.classList.remove('active'));
    thumb.classList.add('active');
}

function bookTour(tourId) {
    <?php if (!isLoggedIn()): ?>
        if (confirm('Bạn cần đăng nhập để đặt tour. Đăng nhập ngay?')) {
            window.location.href = '<?= SITE_URL ?>/dang-nhap.php?redirect=<?= $loginRedirect ?>';
        }
        return;
    <?php endif; ?>
    
    const scheduleEl = document.getElementById('schedule_id');
    const scheduleId = scheduleEl ? scheduleEl.value : '';
    const qtyAdult = document.getElementById('qty_adult').value;
    const qtyChild = document.getElementById('qty_child').value;

    if (scheduleEl && !scheduleId) {
        alert('Vui lòng chọn lịch khởi hành');
        return;
    }
    if (!scheduleEl) {
        alert('Tour chưa có lịch khởi hành. Vui lòng liên hệ hotline.');
        return;
    }
    
    window.location.href = '<?= SITE_URL ?>' + '/xu-ly-dat-tour.php?tour_id=' + tourId + 
                           '&schedule_id=' + scheduleId + 
                           '&qty_adult=' + qtyAdult + 
                           '&qty_child=' + qtyChild;
}
</script>

<?php require __DIR__ . '/includes/footer.php'; $conn->close(); ?>
