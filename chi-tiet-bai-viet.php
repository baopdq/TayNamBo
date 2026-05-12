<?php require_once __DIR__ . '/includes/config.php';
$conn = getConnection();

if (!isset($_GET['id']) || !(int)$_GET['id']) {
    redirect(SITE_URL . '/tin-tuc.php');
}

$id = intval($_GET['id']);
$post = null;
$cat_name = '';

$q = "SELECT b.*, c.Cat_Name FROM tblblog b 
      LEFT JOIN tblblogcategory c ON b.Cat_ID = c.Cat_ID 
      WHERE b.Blog_ID = $id";
$r = @$conn->query($q);

if ($r && $r->num_rows) {
    $post = $r->fetch_assoc();
    $cat_name = $post['Cat_Name'] ?? '';
} else {
    redirect(SITE_URL . '/tin-tuc.php');
}

$pageTitle = sanitize($post['Blog_Title']);
require __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" type="text/css" href="styles/blog_styles.css">
<link rel="stylesheet" type="text/css" href="styles/blog_responsive.css">

<style>
    .post-wrap { max-width: 840px; margin: 110px auto 60px; padding: 0 20px 40px; }
    .post-hero-img { width: 100%; max-height: 420px; object-fit: cover; border-radius: var(--radius); margin-bottom: 24px; }
    .post-meta { color: var(--text-muted); font-size: 14px; margin-bottom: 16px; }
    .post-body { line-height: 1.85; color: var(--text); font-size: 17px; }
    .post-back { display: inline-block; margin-bottom: 24px; color: var(--primary); font-weight: 600; text-decoration: none; }
</style>

<div class="post-wrap">
    <a class="post-back" href="<?= SITE_URL ?>/tin-tuc.php">&larr; Quay lại tin tức</a>
    <h1 style="font-size: 32px; margin-bottom: 12px;"><?= sanitize($post['Blog_Title']) ?></h1>
    <p class="post-meta">
        <?= date('d/m/Y', strtotime($post['created_at'])) ?>
        &nbsp;·&nbsp; <?= sanitize($post['Author'] ?? 'Ban biên tập') ?>
        <?php if ($cat_name): ?>
            &nbsp;·&nbsp; <a href="<?= SITE_URL ?>/tin-tuc.php?cat_id=<?= (int)$post['Cat_ID'] ?>"><?= sanitize($cat_name) ?></a>
        <?php endif; ?>
    </p>
    <?php if (!empty($post['Blog_Image'])): ?>
        <img class="post-hero-img" src="<?= sanitize($post['Blog_Image']) ?>" alt="">
    <?php endif; ?>
    <div class="post-body"><?= nl2br(sanitize($post['Blog_Content'])) ?></div>
</div>

<?php require __DIR__ . '/includes/footer.php'; $conn->close(); ?>
