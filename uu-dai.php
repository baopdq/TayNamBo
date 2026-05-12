<?php require_once __DIR__ . '/includes/config.php';
$conn = getConnection();
ensureTourImagesTable($conn);

$limit = 12;
$query = "SELECT t.*, tc.Cat_Name, d.Dest_Name, cover.image_path AS gallery_cover
          FROM tbtour t 
          JOIN tbtourcategory tc ON t.Cat_ID = tc.Cat_ID
          LEFT JOIN tbldestination d ON t.Dest_ID = d.Dest_ID
          LEFT JOIN (
              SELECT x.tour_id, x.image_path
              FROM tbtour_images x
              INNER JOIN (
                  SELECT tour_id, MIN(id) AS min_id
                  FROM tbtour_images
                  GROUP BY tour_id
              ) fm ON fm.tour_id = x.tour_id AND fm.min_id = x.id
          ) cover ON cover.tour_id = t.Tour_ID
          WHERE t.trang_thai = 'active'
          ORDER BY t.Price_Adult ASC, t.created_at DESC
          LIMIT $limit";
$result = $conn->query($query);
$tours = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $tours[] = $row;
    }
}

$pageTitle = 'Ưu đãi tour';
require __DIR__ . '/includes/header.php';
?>

<style>
    .deals-hero { background: linear-gradient(135deg, var(--accent-dark) 0%, var(--accent) 100%); color: #fff; padding: 52px 20px 40px; text-align: center; }
    .deals-hero h1 { font-size: 34px; margin-bottom: 10px; }
    .deals-hero p { opacity: 0.95; max-width: 560px; margin: 0 auto; }
</style>

<div class="deals-hero">
    <div class="container">
        <h1>Ưu đãi &amp; tour giá tốt</h1>
        <p>Chọn lựa hành trình phù hợp ngân sách — xem chi tiết lịch khởi hành và đặt chỗ trực tuyến.</p>
    </div>
</div>

<div class="container" style="margin: 40px auto 60px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 28px;">
        <p style="color: var(--text-muted); margin: 0;">Hiển thị tối đa <?= $limit ?> tour với mức giá hấp dẫn.</p>
        <a href="<?= SITE_URL ?>/danh-sach-tour.php" class="btn-outline" style="padding: 10px 20px;">Tất cả tour</a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 28px;">
        <?php if (count($tours) > 0): ?>
            <?php foreach ($tours as $tour): ?>
                <div class="tour-card">
                    <div class="tour-image">
                        <?php $displayImage = getTourDisplayImage($tour); ?>
                        <img src="<?= sanitize($displayImage) ?>" alt="<?= sanitize($tour['ten_tour']) ?>">
                        <div style="position: absolute; top: 10px; left: 10px; background: var(--accent); color: #fff; padding: 5px 12px; border-radius: 20px; font-size: 12px;">
                            Ưu đãi
                        </div>
                    </div>
                    <div style="padding: 20px;">
                        <h3 style="font-size: 18px; margin-bottom: 10px; color: var(--text);">
                            <a href="chi-tiet-tour.php?id=<?= $tour['Tour_ID'] ?>" style="color: inherit;">
                                <?= sanitize($tour['ten_tour']) ?>
                            </a>
                        </h3>
                        <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 12px;">
                            <?= sanitize($tour['Dest_Name'] ?? '') ?>
                        </p>
                        <div style="font-weight: 700; color: var(--accent);"><?= formatPrice($tour['Price_Adult']) ?></div>
                        <a href="chi-tiet-tour.php?id=<?= $tour['Tour_ID'] ?>" class="btn-primary" style="display: inline-block; margin-top: 14px; padding: 10px 18px; font-size: 14px;">Chi tiết &amp; đặt tour</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: var(--text-muted); grid-column: 1 / -1;">Chưa có tour khả dụng. Vui lòng quay lại sau.</p>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; $conn->close(); ?>
