<?php
require_once __DIR__ . '/includes/config.php';
$conn = getConnection();
ensureTourImagesTable($conn);

$intro_tours_query = "SELECT t.*, tc.Cat_Name, d.Dest_Name, cover.image_path AS gallery_cover
                      FROM tbtour t 
                      LEFT JOIN tbtourcategory tc ON t.Cat_ID = tc.Cat_ID
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
                      ORDER BY t.created_at DESC 
                      LIMIT 6";
$intro_tours_result = $conn->query($intro_tours_query);
$intro_tours = [];
if ($intro_tours_result) {
    while ($row = $intro_tours_result->fetch_assoc()) {
        $intro_tours[] = $row;
    }
}

$trending_dests_query = "SELECT * FROM tbldestination LIMIT 8";
$trending_dests_result = $conn->query($trending_dests_query);
$trending_dests = [];
if ($trending_dests_result) {
    while ($row = $trending_dests_result->fetch_assoc()) {
        $trending_dests[] = $row;
    }
}

function homeTourExcerpt(array $tour): string {
    $raw = $tour['mo_ta'] ?? $tour['Description'] ?? '';
    $plain = strip_tags((string) $raw);
    if (function_exists('mb_substr')) {
        return mb_strlen($plain) > 110 ? mb_substr($plain, 0, 110) . '…' : $plain;
    }
    return strlen($plain) > 110 ? substr($plain, 0, 110) . '…' : $plain;
}

$pageTitle = 'Trang chủ';
require __DIR__ . '/includes/header.php';
?>

<style>
    .hero .hero-bg { background-image: url('images/home_slider.jpg'); }
    .hero { min-height: 88vh; }
    .dest-pills { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 28px; }
    .dest-pill {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 18px; border-radius: 50px;
        background: var(--white); color: var(--text);
        border: 1px solid var(--border); font-size: 14px; font-weight: 500;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }
    .dest-pill:hover { border-color: var(--primary); color: var(--primary); }
    .home-contact {
        background: var(--bg);
        border-top: 1px solid var(--border);
        padding: 72px 0;
    }
    .home-contact-inner {
        max-width: 560px; margin: 0 auto; text-align: center;
    }
    .home-contact-inner p { color: var(--text-muted); margin-bottom: 24px; line-height: 1.7; }
    @media (max-width: 900px) {
        .categories-grid { grid-template-columns: repeat(2, 1fr) !important; }
        .features-grid { grid-template-columns: 1fr !important; }
    }
    @media (max-width: 520px) {
        .categories-grid { grid-template-columns: 1fr !important; }
    }
</style>

<!-- Hero -->
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="hero-badge"><i class="fas fa-leaf"></i> Miền Tây sông nước</div>
        <h1>Du lịch <span>Tây Nam Bộ</span></h1>
        <p>Chợ nổi bình minh, rừng tràm xanh, ẩm thực bản địa — đặt tour trực tuyến, đồng hành cùng đội ngũ am hiểu địa phương.</p>
        <div class="hero-actions">
            <a href="<?= SITE_URL ?>/danh-sach-tour.php" class="btn-white"><i class="fas fa-map-marked-alt"></i> Xem tour</a>
            <a href="<?= SITE_URL ?>/uu-dai.php" class="btn-outline" style="border-color: rgba(255,255,255,0.6); color: #fff;"><i class="fas fa-tags"></i> Ưu đãi</a>
        </div>
        <form class="hero-search" action="<?= SITE_URL ?>/danh-sach-tour.php" method="GET" role="search">
            <div class="search-field">
                <i class="fas fa-search"></i>
                <div>
                    <label for="home_kw">Từ khóa</label>
                    <input id="home_kw" type="text" name="keyword" placeholder="Ví dụ: Cần Thơ, chợ nổi…">
                </div>
            </div>
            <button type="submit" class="btn-primary" style="border-radius: var(--radius-sm); padding: 14px 28px;"><i class="fas fa-arrow-right"></i></button>
        </form>
    </div>
    <div class="hero-stats">
        <div class="stat-item"><div class="stat-number">120+</div><div class="stat-label">Tour &amp; tuyến</div></div>
        <div class="stat-item"><div class="stat-number">15k+</div><div class="stat-label">Lượt khách</div></div>
        <div class="stat-item"><div class="stat-number">4.9</div><div class="stat-label">Đánh giá</div></div>
    </div>
</section>

<!-- Tour nổi bật -->
<section class="section" style="background: var(--white);">
    <div class="container">
        <div class="section-header">
            <div class="section-badge">Nổi bật</div>
            <h2 class="section-title">Tour đang mở bán</h2>
            <p class="section-subtitle">Chọn hành trình phù hợp — xem lịch khởi hành và giá minh bạch trên từng tour.</p>
        </div>
        <?php if (!empty($intro_tours)): ?>
            <div class="tours-grid">
                <?php foreach ($intro_tours as $tour): ?>
                    <article class="tour-card">
                        <div class="tour-image">
                            <?php $displayImage = getTourDisplayImage($tour); ?>
                            <img src="<?= htmlspecialchars($displayImage, ENT_QUOTES, 'UTF-8') ?>" alt="<?= sanitize($tour['ten_tour']) ?>">
                            <span class="tour-badge"><?= sanitize($tour['Cat_Name'] ?? 'Tour') ?></span>
                        </div>
                        <div class="tour-body">
                            <div class="tour-category"><?= sanitize($tour['Dest_Name'] ?? '') ?></div>
                            <h3 class="tour-title"><a href="<?= SITE_URL ?>/chi-tiet-tour.php?id=<?= (int)$tour['Tour_ID'] ?>"><?= sanitize($tour['ten_tour']) ?></a></h3>
                            <div class="tour-meta">
                                <span class="tour-meta-item"><i class="far fa-clock"></i> <?= sanitize($tour['Duration'] ?? $tour['thoi_gian'] ?? '') ?></span>
                                <span class="tour-meta-item"><i class="fas fa-bus"></i> <?= sanitize($tour['Vehicle'] ?? '') ?></span>
                            </div>
                            <p style="font-size: 14px; color: var(--text-muted); line-height: 1.6; margin-bottom: 12px;"><?= sanitize(homeTourExcerpt($tour)) ?></p>
                            <div class="tour-footer">
                                <div>
                                    <div class="tour-price-label">Giá từ</div>
                                    <div class="tour-price"><?= formatPrice($tour['Price_Adult']) ?></div>
                                </div>
                                <a href="<?= SITE_URL ?>/chi-tiet-tour.php?id=<?= (int)$tour['Tour_ID'] ?>" class="btn-book">Chi tiết</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <div style="text-align: center; margin-top: 40px;">
                <a href="<?= SITE_URL ?>/danh-sach-tour.php" class="btn-outline">Xem tất cả tour</a>
            </div>
        <?php else: ?>
            <p style="text-align: center; color: var(--text-muted);">Chưa có tour. Vui lòng thêm dữ liệu trong quản trị.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Điểm đến -->
<?php if (!empty($trending_dests)): ?>
<section class="section" style="background: var(--bg);">
    <div class="container">
        <div class="section-header">
            <div class="section-badge">Điểm đến</div>
            <h2 class="section-title">Khám phá theo tỉnh thành</h2>
            <p class="section-subtitle">Lọc nhanh tour theo điểm đến bạn quan tâm.</p>
        </div>
        <div class="categories-grid">
            <?php foreach ($trending_dests as $i => $dest): ?>
                <a href="<?= SITE_URL ?>/danh-sach-tour.php?keyword=<?= urlencode($dest['Dest_Name']) ?>" class="category-card">
                    <img src="images/trend_<?= ($i % 8) + 1 ?>.png" alt="<?= sanitize($dest['Dest_Name']) ?>">
                    <div class="category-overlay">
                        <span class="category-name"><?= sanitize($dest['Dest_Name']) ?></span>
                        <span class="category-count">Xem tour</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Lý do chọn -->
<section class="section" style="background: var(--white);">
    <div class="container">
        <div class="section-header">
            <div class="section-badge">Vì sao chọn chúng tôi</div>
            <h2 class="section-title">Trải nghiệm đồng bộ, an tâm đặt tour</h2>
            <p class="section-subtitle">Quy trình rõ ràng từ tư vấn đến xác nhận đơn và hỗ trợ trên tuyến.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-hand-holding-heart"></i></div>
                <h3 class="feature-title">Hướng dẫn bản địa</h3>
                <p class="feature-text">Đối tác và HDV thân thuộc miền Tây, linh hoạt theo nhóm nhỏ và gia đình.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                <h3 class="feature-title">Giá &amp; lịch minh bạch</h3>
                <p class="feature-text">Thông tin tour, lịch khởi hành và mức giá hiển thị rõ trước khi bạn xác nhận.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-headset"></i></div>
                <h3 class="feature-title">Hỗ trợ nhanh</h3>
                <p class="feature-text">Hotline và form liên hệ — phản hồi trong giờ làm việc, kèm theo dõi đơn đặt online.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <h2>Sẵn sàng khởi hành?</h2>
    <p>Ưu đãi theo mùa và combo nhiều ngày — xem ngay hoặc nhắn tin cho đội tư vấn.</p>
    <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
        <a href="<?= SITE_URL ?>/uu-dai.php" class="btn-white">Tour ưu đãi</a>
        <a href="<?= SITE_URL ?>/tin-tuc.php" class="btn-outline" style="border-color: rgba(255,255,255,0.55); color: #fff;">Tin &amp; cẩm nang</a>
    </div>
</section>

<!-- Liên hệ ngắn -->
<section class="home-contact">
    <div class="container home-contact-inner">
        <h2 class="section-title" style="margin-bottom: 12px;">Cần tư vấn lịch trình?</h2>
        <p>Gửi yêu cầu qua trang liên hệ — chúng tôi sẽ gợi ý tour và mùa đẹp nhất cho nhóm của bạn.</p>
        <a href="<?= SITE_URL ?>/lien-he.php" class="btn-primary"><i class="fas fa-envelope"></i> Liên hệ ngay</a>
    </div>
</section>

<?php
require __DIR__ . '/includes/footer.php';
$conn->close();
