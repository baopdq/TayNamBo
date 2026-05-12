<?php require_once __DIR__ . '/includes/config.php';

$pageTitle = 'Giới thiệu — Cẩm nang đặt tour';
require __DIR__ . '/includes/header.php';
?>

<style>
    .about-hero { background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%); color: #fff; padding: 56px 20px 48px; text-align: center; margin-top: 0; }
    .about-hero h1 { font-size: 36px; margin-bottom: 12px; }
    .about-hero p { max-width: 640px; margin: 0 auto; opacity: 0.92; font-size: 17px; }
    .about-section { padding: 48px 0; border-bottom: 1px solid var(--border); }
    .about-section h2 { font-size: 24px; margin-bottom: 16px; color: var(--text); }
    .about-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px; margin-top: 28px; }
    .about-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px; }
    .about-card strong { color: var(--primary); font-size: 28px; display: block; margin-bottom: 8px; }
    .about-cta { text-align: center; padding: 48px 20px; }

    .scope-grid { display: grid; gap: 16px; margin-top: 20px; max-width: 900px; }
    .scope-item { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 18px 20px; line-height: 1.75; }
    .scope-item strong { color: var(--primary); display: block; margin-bottom: 6px; font-size: 15px; }
    .guide-block h3 { font-size: 20px; color: var(--primary-dark); margin-bottom: 14px; margin-top: 0; }
    .guide-list { list-style: none; padding: 0; margin: 0 0 16px; }
    .guide-list li { position: relative; padding-left: 1.5em; margin-bottom: 10px; line-height: 1.75; color: var(--text); }
    .guide-list li::before { position: absolute; left: 0; top: 0; font-weight: 700; color: var(--primary); }
    .guide-list.check li::before { content: '✓'; }
    .guide-list.cross li::before { content: '✕'; color: #c0392b; }
    .guide-note { margin-top: 14px; padding: 14px 18px; background: rgba(255, 152, 0, 0.10); border-left: 4px solid var(--accent); border-radius: 0 var(--radius-sm) var(--radius-sm) 0; font-size: 15px; line-height: 1.7; color: var(--text); }
    .guide-note strong { color: var(--accent-dark); }
    .guide-summary { margin-top: 32px; padding: 28px 24px; background: linear-gradient(135deg, rgba(0, 137, 123, 0.12) 0%, rgba(255, 152, 0, 0.12) 100%); border: 2px solid var(--primary-light); border-radius: var(--radius); box-shadow: var(--shadow); }
    .guide-summary h2 { font-size: 22px; color: var(--primary-dark); margin-bottom: 14px; border: none; padding: 0; }
    .guide-summary p { margin: 0; font-size: 17px; line-height: 1.85; color: var(--text); font-weight: 500; }
    .guide-toc { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 20px; }
    .guide-toc a { font-size: 14px; padding: 8px 14px; background: var(--white); border: 1px solid var(--border); border-radius: 50px; color: var(--primary); font-weight: 600; }
    .guide-toc a:hover { border-color: var(--primary); background: var(--bg); }

    #pham-vi, #cam-nang, #muc-3, #muc-4, #muc-5, #muc-6, #muc-8, #tom-tat { scroll-margin-top: 96px; }
</style>

<div class="about-hero">
    <div class="container">
        <h1>Giới thiệu TayNamBo</h1>
        <p>Khám phá miền sông nước và cẩm nang ngắn giúp bạn đặt tour chủ động, minh bạch — trước khi giữ chỗ hay đặt cọc.</p>
    </div>
</div>

<div class="container">
    <div class="about-section">
        <h2>Vì sao chọn chúng tôi</h2>
        <p style="color: var(--text-muted); line-height: 1.8; max-width: 800px;">
            Đội ngũ am hiểu địa phương, lịch trình linh hoạt cho nhóm nhỏ và gia đình, hỗ trợ đặt tour trực tuyến minh bạch.
            Mỗi hành trình đều ưu tiên trải nghiệm văn hóa bản địa và an toàn trên tuyến.
        </p>
        <div class="about-grid">
            <div class="about-card"><strong>10+</strong> năm kết nối đối tác địa phương</div>
            <div class="about-card"><strong>1200+</strong> khách tin tưởng mỗi năm</div>
            <div class="about-card"><strong>24/7</strong> hỗ trợ qua hotline &amp; tin nhắn</div>
        </div>
    </div>

    <div class="about-section" id="pham-vi">
        <h2>Phạm vi &amp; định hướng nghiệp vụ</h2>
        <p style="color: var(--text-muted); line-height: 1.8; max-width: 900px;">
            Chúng tôi thu hẹp phạm vi chuyên môn: <strong style="color: var(--text);">công ty lữ hành phục vụ khách du lịch Tây Nam Bộ</strong>, khảo sát nghiệp vụ thực tế trên tuyến và với đối tác địa phương.
            Khách hàng có thể chọn tour phù hợp, theo dõi điểm đến và lịch trình cụ thể; khi áp dụng hình thức ghép đoàn, chúng tôi có cam kết rõ ràng về số lượng chỗ / phương án sắp xếp xe du lịch.
        </p>
        <div class="scope-grid">
            <div class="scope-item">
                <strong>Khách hàng</strong>
                Đặt tour, giữ chỗ, đặt cọc; lựa chọn tour ghép hoặc tour riêng; gợi ý &amp; tìm kiếm tour theo nhu cầu.
            </div>
            <div class="scope-item">
                <strong>Đối tác</strong>
                Đối soát thu chi, phối hợp dịch vụ trên tuyến.
            </div>
            <div class="scope-item">
                <strong>Nội bộ công ty</strong>
                Nhân viên điều phối, hướng dẫn viên, nhân viên vận hành &amp; quản lý.
            </div>
        </div>
    </div>

    <div class="about-section" id="cam-nang">
        <h2>Cẩm nang trước khi đặt tour</h2>
        <p style="color: var(--text-muted); line-height: 1.8; max-width: 800px; margin-bottom: 8px;">
            Gợi ý kiểm tra nhanh giúp bạn đồng hành an tâm — phù hợp với tour nội địa và tour Tây Nam Bộ.
        </p>
        <nav class="guide-toc" aria-label="Mục lục cẩm nang">
            <a href="#muc-3">§3 Chương trình</a>
            <a href="#muc-4">§4 Khách sạn &amp; ăn uống</a>
            <a href="#muc-5">§5 HDV &amp; xe</a>
            <a href="#muc-6">§6 Hoàn / hủy &amp; bảo hiểm</a>
            <a href="#muc-8">§8 Lỗi thường gặp</a>
            <a href="#tom-tat">Tóm lại</a>
        </nav>

        <div class="guide-block" style="padding-top: 32px; padding-bottom: 24px;" id="muc-3">
            <h3>3. Kiểm tra kỹ chương trình tour</h3>
            <ul class="guide-list check">
                <li><strong>Lịch trình chi tiết:</strong> Đi những đâu? Mỗi điểm tham quan có đủ thời gian không?</li>
                <li><strong>Dịch vụ bao gồm &amp; không bao gồm:</strong> Vé tham quan, khách sạn mấy sao, số bữa ăn, phương tiện di chuyển… Phụ thu nếu đi vào mùa cao điểm, dịp lễ Tết.</li>
                <li><strong>Loại hình di chuyển:</strong> Xe du lịch, tàu, máy bay, hãng hàng không nào (nếu có)? Có đủ chỗ không? Hoặc phương án sắp xếp xe du lịch thế nào?</li>
                <li><strong>Thời gian tự do:</strong> Có phù hợp với sở thích của bạn không?</li>
            </ul>
            <p class="guide-note"><strong>Lưu ý:</strong> Một số tour giá rẻ có thể đưa bạn đến nhiều điểm mua sắm bắt buộc — hãy đọc kỹ mô tả và hỏi rõ trước khi cọc.</p>
        </div>

        <div class="guide-block" style="padding-bottom: 24px;" id="muc-4">
            <h3>4. Kiểm tra khách sạn &amp; dịch vụ ăn uống</h3>
            <ul class="guide-list check">
                <li><strong>Tên khách sạn cụ thể</strong> — xem đánh giá độc lập trước khi đặt (nếu chương trình đã công bố).</li>
                <li><strong>Vị trí khách sạn:</strong> Gần trung tâm hay xa? Thuận tiện đi lại không?</li>
                <li><strong>Thực đơn bữa ăn:</strong> Có phù hợp nhu cầu của bạn (ăn chay, ăn kiêng, halal…)? Bạn nên thông báo sớm với nhà điều hành.</li>
            </ul>
            <p class="guide-note"><strong>Lưu ý:</strong> Một số tour không bao gồm bữa ăn hoặc chỉ phục vụ theo thực đơn cố định — kiểm tra mục &quot;bao gồm / không bao gồm&quot; trên phiếu xác nhận.</p>
        </div>

        <div class="guide-block" style="padding-bottom: 24px;" id="muc-5">
            <h3>5. Hỏi rõ về hướng dẫn viên &amp; xe di chuyển</h3>
            <ul class="guide-list check">
                <li>Hướng dẫn viên có kinh nghiệm, nhiệt tình không? (Có thể xem đánh giá tour tương đương hoặc hỏi bộ phận tư vấn.)</li>
                <li>Xe đưa đón có điều hòa, đời mới không?</li>
                <li>Có phụ phí khi đón tại sân bay hoặc điểm xa trung tâm không?</li>
            </ul>
        </div>

        <div class="guide-block" style="padding-bottom: 24px;" id="muc-6">
            <h3>6. Chính sách hoàn / hủy tour &amp; bảo hiểm du lịch</h3>
            <ul class="guide-list check">
                <li><strong>Điều kiện hủy tour:</strong> Hủy trước bao nhiêu ngày được hoàn / giữ một phần tiền? Có phí hủy không?</li>
                <li><strong>Bảo hiểm du lịch:</strong> Công ty có hỗ trợ/cung cấp không? Mức đền bù khi xảy ra sự cố?</li>
                <li><strong>Hỗ trợ khi rủi ro:</strong> Hoãn chuyến bay, mất hành lý, sự cố thời tiết… Chính sách xử lý thế nào?</li>
            </ul>
            <p class="guide-note">Chi tiết theo từng đơn hàng được thể hiện tại phần <strong>chính sách hủy</strong> trên website và xác nhận từ nhân viên — bạn vui lòng đối chiếu trước khi thanh toán.</p>
        </div>

        <div class="guide-block" style="padding-bottom: 8px;" id="muc-8">
            <h3>8. Tránh những lỗi thường gặp khi đặt tour</h3>
            <ul class="guide-list cross">
                <li>Chỉ nhìn giá rẻ mà không xem xét chất lượng dịch vụ đi kèm.</li>
                <li>Đặt tour sát ngày đi, không đủ thời gian chuẩn bị visa hoặc vé máy bay (nếu chương trình có chặng bay).</li>
                <li>Không đọc kỹ cam kết / điều kiện, dễ phát sinh tranh chấp khi hủy hoặc đổi lịch.</li>
                <li>Không kiểm tra kỹ lịch trình, dẫn đến kỳ vọng không khớp thực tế.</li>
            </ul>
        </div>

        <div class="guide-summary" id="tom-tat">
            <h2>Tóm lại</h2>
            <p>Khi đặt tour du lịch, hãy dành thời gian tìm hiểu kỹ công ty lữ hành, <strong>lịch trình</strong>, <strong>dịch vụ đi kèm</strong>, <strong>chính sách hoàn hủy</strong> và <strong>bảo hiểm du lịch</strong> (nếu áp dụng) để có một chuyến đi suôn sẻ và an toàn. Đây là các hạng mục chúng tôi khuyến khích mọi khách đối chiếu trước khi xác nhận đặt chỗ và đặt cọc.</p>
        </div>
    </div>

    <div class="about-section">
        <h2>Chính sách &amp; điều khoản</h2>
        <p style="color: var(--text-muted); line-height: 1.8;">
            Thông tin đặt tour, thanh toán, huỷ/đổi lịch và quyền riêng tư được áp dụng theo quy định hiện hành của công ty.
            Khi đặt dịch vụ qua website, bạn xác nhận đã đọc các điều khoản sử dụng và cam kết cung cấp thông tin chính xác.
            Mọi thắc mắc vui lòng liên hệ qua trang Liên hệ hoặc hotline trong giờ làm việc.
        </p>
    </div>

    <div class="about-cta">
        <h2 style="margin-bottom: 16px;">Sẵn sàng khởi hành?</h2>
        <a href="<?= SITE_URL ?>/danh-sach-tour.php" class="btn-primary" style="display: inline-block; padding: 14px 28px;">Xem danh sách tour</a>
        <a href="<?= SITE_URL ?>/uu-dai.php" class="btn-outline" style="display: inline-block; padding: 14px 28px; margin-left: 12px;">Tour ưu đãi</a>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
