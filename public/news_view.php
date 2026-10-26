<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { redirect('public/news.php'); }

$stmt = $pdo->prepare('SELECT id, title, title_ru, body, body_ru, cover_url, created_at FROM news WHERE id = ?');
$stmt->execute([$id]);
$post = $stmt->fetch();
if (!$post) { redirect('public/news.php'); }

$lang = $_SESSION['lang'] ?? 'uz';
$title = ($lang === 'ru' && !empty($post['title_ru'])) ? $post['title_ru'] : $post['title'];
$body = ($lang === 'ru' && !empty($post['body_ru'])) ? $post['body_ru'] : $post['body'];

$__page_title = $title;
$__meta_desc = mb_substr(strip_tags($body), 0, 140);
require_once __DIR__ . '/../includes/header.php';

$img = news_cover_url($post['cover_url']);
?>
<div class="cover mb-3" style="height: 220px;">
  <img src="<?= e($img) ?>" alt="Cover">
  <div class="cover-overlay"></div>
  <div class="cover-caption">
    <div class="h5 fw-semibold line-clamp-1"><?= e($title) ?></div>
    <div class="small"><?= e(format_datetime($post['created_at'])) ?></div>
  </div>
</div>
<div class="card shadow-sm">
  <div class="card-body">
    <article class="content">
      <?= nl2br(e($body)) ?>
    </article>
    <div class="mt-3">
      <a class="btn btn-outline-secondary" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/news.php"><?= e(lang('common.back', 'Orqaga')) ?></a>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
