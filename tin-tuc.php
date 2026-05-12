<?php require_once __DIR__ . '/includes/config.php';
$conn = getConnection();

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$keyword = isset($_GET['keyword']) ? sanitize($_GET['keyword']) : '';
$cat_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;

$where = "WHERE 1=1";
if ($keyword) {
    $where .= " AND (b.Blog_Title LIKE '%" . $conn->real_escape_string($keyword) . "%' OR b.Blog_Content LIKE '%" . $conn->real_escape_string($keyword) . "%')";
}
if ($cat_id) {
    $where .= " AND b.Cat_ID = $cat_id";
}

$total_posts = 0;
$posts = [];
$categories = [];
$latest_posts = [];

try {
    $count_query = "SELECT COUNT(*) as total FROM tblblog b $where";
    $count_result = $conn->query($count_query);
    if ($count_result) {
        $total_posts = $count_result->fetch_assoc()['total'];

        $query = "SELECT b.*, c.Cat_Name 
                  FROM tblblog b 
                  LEFT JOIN tblblogcategory c ON b.Cat_ID = c.Cat_ID 
                  $where 
                  ORDER BY b.created_at DESC 
                  LIMIT $limit OFFSET $offset";
        $result = $conn->query($query);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $posts[] = $row;
            }
        }
    }

    $cat_result = $conn->query("SELECT * FROM tblblogcategory ORDER BY Cat_Name");
    if ($cat_result) {
        while ($row = $cat_result->fetch_assoc()) {
            $categories[] = $row;
        }
    }

    $latest_result = $conn->query("SELECT * FROM tblblog ORDER BY created_at DESC LIMIT 4");
    if ($latest_result) {
        while ($row = $latest_result->fetch_assoc()) {
            $latest_posts[] = $row;
        }
    }
} catch (Exception $e) {
}

$pages = ceil($total_posts / $limit);

$pageTitle = "Tin tức du lịch";
require __DIR__ . '/includes/header.php';
?>

<link href="plugins/colorbox/colorbox.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles/blog_styles.css">
<link rel="stylesheet" type="text/css" href="styles/blog_responsive.css">

	<!-- home -->

	<div class="home">
		<div class="home_background parallax-window" data-parallax="scroll" data-image-src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&h=600&fit=crop"></div>
		<div class="home_content">
			<div class="home_title">Tin &amp; cẩm nang du lịch</div>
		</div>
	</div>

	<!-- Blog -->

	<div class="blog">
		<div class="container">
			<div class="row">

				<!-- Blog Content -->

				<div class="col-lg-8">
					
					<div class="blog_post_container">

						<?php if (!empty($posts)): ?>
							<?php foreach ($posts as $post): ?>
								<!-- Blog Post -->
								<div class="blog_post">
									<div class="blog_post_image">
										<img src="<?= !empty($post['Blog_Image']) ? sanitize($post['Blog_Image']) : 'images/blog_1.jpg' ?>" alt="<?= sanitize($post['Blog_Title']) ?>">
										<div class="blog_post_date d-flex flex-column align-items-center justify-content-center">
											<div class="blog_post_day"><?= date('d', strtotime($post['created_at'])) ?></div>
											<div class="blog_post_month">Tháng <?= date('m/Y', strtotime($post['created_at'])) ?></div>
										</div>
									</div>
									<div class="blog_post_meta">
										<ul>
											<li class="blog_post_meta_item"><a href="javascript:void(0)">bởi <?= sanitize($post['Author'] ?? 'Ban biên tập') ?></a></li>
											<li class="blog_post_meta_item"><a href="tin-tuc.php?cat_id=<?= $post['Cat_ID'] ?>"><?= sanitize($post['Cat_Name'] ?? 'Chưa phân loại') ?></a></li>
										</ul>
									</div>
									<div class="blog_post_title"><a href="chi-tiet-bai-viet.php?id=<?= $post['Blog_ID'] ?>"><?= sanitize($post['Blog_Title']) ?></a></div>
									<div class="blog_post_text">
										<p><?= mb_substr(strip_tags($post['Blog_Content']), 0, 200) ?>...</p>
									</div>
									<div class="blog_post_link"><a href="chi-tiet-bai-viet.php?id=<?= $post['Blog_ID'] ?>">Đọc tiếp</a></div>
								</div>
							<?php endforeach; ?>
						<?php else: ?>
							<!-- Dummy Blog Post -->
							<div class="blog_post">
								<div class="blog_post_image">
									<img src="images/blog_1.jpg" alt="https://unsplash.com/@anniespratt">
									<div class="blog_post_date d-flex flex-column align-items-center justify-content-center">
										<div class="blog_post_day">01</div>
										<div class="blog_post_month">Tháng 12/2024</div>
									</div>
								</div>
								<div class="blog_post_meta">
									<ul>
										<li class="blog_post_meta_item"><a href="">bởi Ban biên tập</a></li>
										<li class="blog_post_meta_item"><a href="">Chưa phân loại</a></li>
										<li class="blog_post_meta_item"><a href="">3 bình luận</a></li>
									</ul>
								</div>
								<div class="blog_post_title"><a href="tin-tuc.php">Điểm nhấn không thể bỏ qua khi đi miền Tây</a></div>
								<div class="blog_post_text">
									<p>Rời chợ vào đúng sáng sớm để không bỏ lỡ những chiếc xuồng hàng chồng chất. Trưa có thể nghỉ trưa trong nhà vườn, chiều bách bộ làng cổ là đủ một ngày trọn ý — nhớ chủ động nước uống, mũ rộng và quần áo gọn. Mùa nước nổi thường đẹp rực khoảng tháng 9–11.</p>
								</div>
								<div class="blog_post_link"><a href="tin-tuc.php">Đọc tiếp</a></div>
							</div>

							<!-- Dummy Blog Post -->
							<div class="blog_post">
								<div class="blog_post_image">
									<img src="images/blog_2.jpg" alt="https://unsplash.com/@tschax">
									<div class="blog_post_date d-flex flex-column align-items-center justify-content-center">
										<div class="blog_post_day">01</div>
										<div class="blog_post_month">Tháng 12/2024</div>
									</div>
								</div>
								<div class="blog_post_meta">
									<ul>
										<li class="blog_post_meta_item"><a href="">bởi Ban biên tập</a></li>
										<li class="blog_post_meta_item"><a href="">Chưa phân loại</a></li>
										<li class="blog_post_meta_item"><a href="">3 bình luận</a></li>
									</ul>
								</div>
								<div class="blog_post_title"><a href="tin-tuc.php">Điểm nhấn không thể bỏ qua khi đi miền Tây</a></div>
								<div class="blog_post_text">
									<p>Rời chợ vào đúng sáng sớm để không bỏ lỡ những chiếc xuồng hàng chồng chất. Trưa có thể nghỉ trưa trong nhà vườn, chiều bách bộ làng cổ là đủ một ngày trọn ý — nhớ chủ động nước uống, mũ rộng và quần áo gọn. Mùa nước nổi thường đẹp rực khoảng tháng 9–11.</p>
								</div>
								<div class="blog_post_link"><a href="tin-tuc.php">Đọc tiếp</a></div>
							</div>
						<?php endif; ?>

					</div>
						
					<?php if ($pages > 1): ?>
					<div class="blog_navigation">
						<ul>
							<?php for ($i = 1; $i <= $pages; $i++): ?>
								<li class="blog_dot <?= $i === $page ? 'active' : '' ?>" onclick="window.location.href='tin-tuc.php?page=<?= $i ?><?= $keyword ? '&keyword='.urlencode($keyword) : '' ?><?= $cat_id ? '&cat_id='.$cat_id : '' ?>'" style="cursor: pointer;">
									<div></div><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>.
								</li>
							<?php endfor; ?>
						</ul>
					</div>
					<?php else: ?>
					<div class="blog_navigation">
						<ul>
							<li class="blog_dot active"><div></div>01.</li>
							<li class="blog_dot"><div></div>02.</li>
							<li class="blog_dot"><div></div>03.</li>
						</ul>
					</div>
					<?php endif; ?>
				</div>

				<!-- Blog Sidebar -->

				<div class="col-lg-4 sidebar_col">

					<!-- Sidebar search -->
					<div class="sidebar_search">
						<form action="tin-tuc.php" method="GET">
							<input id="sidebar_search_input" type="search" name="keyword" class="sidebar_search_input" placeholder="Tìm bài đọc…" value="<?= $keyword ?>" required="required">
							<button id="sidebar_search_button" type="submit" class="sidebar_search_button trans_300" value="Gửi">
								<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
								width="17px" height="17px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
									<g>
										<g>
											<g>
												<path class="mag_glass" fill="#FFFFFF" d="M78.438,216.78c0,57.906,22.55,112.343,63.493,153.287c40.945,40.944,95.383,63.494,153.287,63.494
												s112.344-22.55,153.287-63.494C489.451,329.123,512,274.686,512,216.78c0-57.904-22.549-112.342-63.494-153.286
												C407.563,22.549,353.124,0,295.219,0c-57.904,0-112.342,22.549-153.287,63.494C100.988,104.438,78.439,158.876,78.438,216.78z
												M119.804,216.78c0-96.725,78.69-175.416,175.415-175.416s175.418,78.691,175.418,175.416
												c0,96.725-78.691,175.416-175.416,175.416C198.495,392.195,119.804,313.505,119.804,216.78z"/>
											</g>
										</g>
										<g>
											<g>
												<path class="mag_glass" fill="#FFFFFF" d="M6.057,505.942c4.038,4.039,9.332,6.058,14.625,6.058s10.587-2.019,14.625-6.058L171.268,369.98
												c8.076-8.076,8.076-21.172,0-29.248c-8.076-8.078-21.172-8.078-29.249,0L6.057,476.693
												C-2.019,484.77-2.019,497.865,6.057,505.942z"/>
											</g>
										</g>
									</g>
								</svg>
							</button>
						</form>
					</div>
					
					<!-- Sidebar Archives -->
					<div class="sidebar_archives">
						<div class="sidebar_title">Kho lưu bài</div>
						<div class="sidebar_list">
							<ul>
								<li><a href="tin-tuc.php">Tháng 03/2024</a></li>
								<li><a href="tin-tuc.php">Tháng 04/2024</a></li>
								<li><a href="tin-tuc.php">Tháng 05/2024</a></li>
							</ul>
						</div>
					</div>
					
					<!-- Sidebar Archives -->
					<div class="sidebar_categories">
						<div class="sidebar_title">Danh mục</div>
						<div class="sidebar_list">
							<ul>
								<?php if (!empty($categories)): ?>
									<?php foreach ($categories as $cat): ?>
										<li><a href="tin-tuc.php?cat_id=<?= $cat['Cat_ID'] ?>"><?= sanitize($cat['Cat_Name']) ?></a></li>
									<?php endforeach; ?>
								<?php else: ?>
									<li><a href="tin-tuc.php">Du lịch miền Tây</a></li>
									<li><a href="tin-tuc.php">Điểm đến khác biệt</a></li>
									<li><a href="tin-tuc.php">Tour ngắn ngày</a></li>
									<li><a href="tin-tuc.php">Mẹo & kinh nghiệm</a></li>
									<li><a href="tin-tuc.php">Đời sống &amp; du lịch</a></li>
									<li><a href="tin-tuc.php">Chưa phân loại</a></li>
								<?php endif; ?>
							</ul>
						</div>
					</div>

					<!-- Sidebar Latest Posts -->

					<div class="sidebar_latest_posts">
						<div class="sidebar_title">Bài mới nhất</div>
						<div class="latest_posts_container">
							<ul>

								<?php if (!empty($latest_posts)): ?>
									<?php foreach ($latest_posts as $lpost): ?>
										<!-- Latest Post -->
										<li class="latest_post clearfix">
											<div class="latest_post_image">
												<a href="chi-tiet-bai-viet.php?id=<?= $lpost['Blog_ID'] ?>"><img src="<?= !empty($lpost['Blog_Image']) ? sanitize($lpost['Blog_Image']) : 'images/latest_1.jpg' ?>" alt=""></a>
											</div>
											<div class="latest_post_content">
												<div class="latest_post_title trans_200"><a href="chi-tiet-bai-viet.php?id=<?= $lpost['Blog_ID'] ?>"><?= sanitize($lpost['Blog_Title']) ?></a></div>
												<div class="latest_post_meta">
													<div class="latest_post_author trans_200"><a href="javascript:void(0)">của <?= sanitize($lpost['Author'] ?? 'Ban biên tập') ?></a></div>
													<div class="latest_post_date trans_200"><a href="javascript:void(0)"><?= date('d/m/Y', strtotime($lpost['created_at'])) ?></a></div>
												</div>
											</div>
										</li>
									<?php endforeach; ?>
								<?php else: ?>
									<!-- Latest Post Dummy -->
									<li class="latest_post clearfix">
										<div class="latest_post_image">
											<a href="tin-tuc.php"><img src="images/latest_1.jpg" alt=""></a>
										</div>
										<div class="latest_post_content">
											<div class="latest_post_title trans_200"><a href="tin-tuc.php">Phác thảo một ngày ở chợ nổi</a></div>
											<div class="latest_post_meta">
												<div class="latest_post_author trans_200"><a href="javascript:void(0)">của Nguyễn Mai</a></div>
												<div class="latest_post_date trans_200"><a href="javascript:void(0)">25/08/2024</a></div>
											</div>
										</div>
									</li>

									<!-- Latest Post Dummy -->
									<li class="latest_post clearfix">
										<div class="latest_post_image">
											<a href="tin-tuc.php"><img src="images/latest_2.jpg" alt=""></a>
										</div>
										<div class="latest_post_content">
											<div class="latest_post_title trans_200"><a href="tin-tuc.php">Gợi ý chợ và làng miệt vườn tuần này</a></div>
											<div class="latest_post_meta">
												<div class="latest_post_author trans_200"><a href="javascript:void(0)">của Nguyễn Mai</a></div>
												<div class="latest_post_date trans_200"><a href="javascript:void(0)">25/08/2024</a></div>
											</div>
										</div>
									</li>
								<?php endif; ?>

							</ul>
						</div>
					</div>

					<!-- Sidebar Gallery -->
					<div class="sidebar_gallery">
						<div class="sidebar_title">Khung ảnh trong tour</div>
						<div class="gallery_container">
							<ul class="gallery_items d-flex flex-row align-items-start justify-content-between flex-wrap">
								<li class="gallery_item">
									<a class="colorbox" href="https://images.unsplash.com/photo-1473625247510-8ceb1760943f?ixlib=rb-0.3.5&s=c0996cd16eda8c6f54c398de02d03cd3&auto=format&fit=crop&w=720&q=80">
										<img src="images/gallery_1.jpg" alt="https://unsplash.com/@mantashesthaven">
									</a>
								</li>
								<li class="gallery_item">
									<a class="colorbox" href="https://images.unsplash.com/photo-1495162048225-6b3b37b8a69e?ixlib=rb-0.3.5&s=861dd3c7b9d3e735d7fd7cbb1eefed64&auto=format&fit=crop&w=720&q=80">
										<img src="images/gallery_2.jpg" alt="https://unsplash.com/@kensuarez">
									</a>
								</li>
								<li class="gallery_item">
									<a class="colorbox" href="https://images.unsplash.com/photo-1502646275263-04be86afa386?ixlib=rb-0.3.5&s=682a41d7d9bf6e3feabc73a5fdd61dd2&auto=format&fit=crop&w=720&q=80">
										<img src="images/gallery_3.jpg" alt="https://unsplash.com/@jakobowens1">
									</a>
								</li>
								<li class="gallery_item">
									<a class="colorbox" href="https://images.unsplash.com/photo-1484820301304-0b43512779dc?ixlib=rb-0.3.5&s=7a3393e9f507fb4718c36337a8014c52&auto=format&fit=crop&w=720&q=80">
										<img src="images/gallery_4.jpg" alt="https://unsplash.com/@seefromthesky">
									</a>
								</li>
								<li class="gallery_item">
									<a class="colorbox" href="https://images.unsplash.com/photo-1490380169520-0a4b88d52565?ixlib=rb-0.3.5&s=7e6b68b1911fb4ffeea4c0750b8a5269&auto=format&fit=crop&w=720&q=80">
										<img src="images/gallery_5.jpg" alt="https://unsplash.com/@deannaritchie">
									</a>
								</li>
								<li class="gallery_item">
									<a class="colorbox" href="https://images.unsplash.com/photo-1504434026032-a7e440a30b68?ixlib=rb-0.3.5&s=2cc35bf903b78ba4f7f7ed69bc2abe3f&auto=format&fit=crop&w=720&q=80">
										<img src="images/gallery_6.jpg" alt="https://unsplash.com/@benobro">
									</a>
								</li>
							</ul>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

<script src="plugins/colorbox/jquery.colorbox-min.js"></script>
<script src="plugins/parallax-js-master/parallax.min.js"></script>
<script src="js/blog_custom.js"></script>

<?php require __DIR__ . '/includes/footer.php'; $conn->close(); ?>
