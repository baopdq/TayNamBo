<?php require_once __DIR__ . '/includes/config.php';
$conn = getConnection();
ensureTourImagesTable($conn);

$danh_muc = isset($_GET['danh_muc']) ? sanitize($_GET['danh_muc']) : '';
$keyword = isset($_GET['keyword']) ? sanitize($_GET['keyword']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

$where = "WHERE t.trang_thai = 'active'";
if ($danh_muc) {
    $where .= " AND tc.Cat_ID = (SELECT Cat_ID FROM tbtourcategory WHERE Cat_Slug = '" . $conn->real_escape_string($danh_muc) . "')";
}
if ($keyword) {
    $where .= " AND (t.ten_tour LIKE '%" . $conn->real_escape_string($keyword) . "%' OR t.mo_ta LIKE '%" . $conn->real_escape_string($keyword) . "%')";
}

$count_query = "SELECT COUNT(*) as total FROM tbtour t JOIN tbtourcategory tc ON t.Cat_ID = tc.Cat_ID $where";
$count_result = $conn->query($count_query);
$total = $count_result->fetch_assoc()['total'];
$pages = ceil($total / $limit);

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
          $where 
          ORDER BY t.created_at DESC 
          LIMIT $limit OFFSET $offset";
$result = $conn->query($query);
$tours = [];
while ($row = $result->fetch_assoc()) {
    $tours[] = $row;
}

$cat_query = "SELECT * FROM tbtourcategory ORDER BY Cat_Name";
$cat_result = $conn->query($cat_query);
$categories = [];
while ($row = $cat_result->fetch_assoc()) {
    $categories[] = $row;
}

$pageTitle = "Danh Sách Tour";
require __DIR__ . '/includes/header.php';
?>

<div class="container" style="margin-top: 100px; margin-bottom: 60px;">
    <div class="filter-section" style="background: var(--white); padding: 20px; border-radius: var(--radius); margin-bottom: 40px;">
        <h3 style="margin-bottom: 20px;">Tìm Tour</h3>
        <form method="GET" class="filter-form" style="display: flex; gap: 15px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Danh Mục</label>
                <select name="danh_muc" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm);">
                    <option value="">Tất cả danh mục</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['Cat_Slug'] ?>" <?= $danh_muc === $cat['Cat_Slug'] ? 'selected' : '' ?>>
                            <?= sanitize($cat['Cat_Name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Tìm kiếm</label>
                <input type="text" name="keyword" value="<?= $keyword ?>" placeholder="Nhập tên tour..." 
                       style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm);">
            </div>
            <div style="display: flex; gap: 10px; align-items: flex-end;">
                <button type="submit" class="btn-primary">Tìm kiếm</button>
                <a href="danh-sach-tour.php" class="btn-outline" style="padding: 12px 20px;">Xóa bộ lọc</a>
            </div>
        </form>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 28px; margin-bottom: 40px;">
        <?php if (count($tours) > 0): ?>
            <?php foreach ($tours as $tour): ?>
                <div class="tour-card">
                    <div class="tour-image">
                        <?php $displayImage = getTourDisplayImage($tour); ?>
                        <img src="<?= sanitize($displayImage) ?>" alt="<?= sanitize($tour['ten_tour']) ?>">
                        <div style="position: absolute; top: 10px; right: 10px; background: var(--accent); color: var(--white); padding: 5px 12px; border-radius: 20px; font-size: 12px;">
                            <?= sanitize($tour['Cat_Name']) ?>
                        </div>
                    </div>
                    <div style="padding: 20px;">
                        <h3 style="font-size: 18px; margin-bottom: 10px; color: var(--text);">
                            <a href="chi-tiet-tour.php?id=<?= $tour['Tour_ID'] ?>" style="color: inherit;">
                                <?= sanitize($tour['ten_tour']) ?>
                            </a>
                        </h3>
                        <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 12px;">
                            📍 <?= $tour['Dest_Name'] ? sanitize($tour['Dest_Name']) : 'N/A' ?>
                        </p>
                        <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 15px; line-height: 1.5;">
                            <?= substr(sanitize($tour['mo_ta']), 0, 100) ?>...
                        </p>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 20px; font-weight: 700; color: var(--accent);">
                                <?= formatPrice($tour['Price_Adult']) ?>
                            </span>
                            <a href="chi-tiet-tour.php?id=<?= $tour['Tour_ID'] ?>" class="btn-primary" style="padding: 8px 16px; font-size: 14px;">
                                Chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                <p style="font-size: 16px; color: var(--text-muted);">Không tìm thấy tour nào phù hợp.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($pages > 1): ?>
        <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 40px;">
            <?php if ($page > 1): ?>
                <a href="danh-sach-tour.php?page=<?= $page - 1 ?><?= $danh_muc ? '&danh_muc=' . $danh_muc : '' ?><?= $keyword ? '&keyword=' . urlencode($keyword) : '' ?>" class="btn-outline">← Trước</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <a href="danh-sach-tour.php?page=<?= $i ?><?= $danh_muc ? '&danh_muc=' . $danh_muc : '' ?><?= $keyword ? '&keyword=' . urlencode($keyword) : '' ?>" 
                   class="<?= $i === $page ? 'btn-primary' : 'btn-outline' ?>" style="min-width: 40px;">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
            <?php if ($page < $pages): ?>
                <a href="danh-sach-tour.php?page=<?= $page + 1 ?><?= $danh_muc ? '&danh_muc=' . $danh_muc : '' ?><?= $keyword ? '&keyword=' . urlencode($keyword) : '' ?>" class="btn-outline">Tiếp →</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; $conn->close(); ?>
