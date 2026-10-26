<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
$__page_title = lang('nav.news','Yangiliklar');
require_once __DIR__ . '/../includes/header.php';

$cover = asset_url('assets/news-hero.jpg', 'https://images.unsplash.com/photo-1542037104857-ffbb0b9155fb?q=80&w=1600&auto=format&fit=crop');

$lang = $_SESSION['lang'] ?? 'uz';
$rows = [];
try {
    $stmt = $pdo->query('SELECT id, title, title_ru, cover_url, created_at FROM news ORDER BY created_at DESC LIMIT 12');
    $rows = $stmt->fetchAll() ?: [];
} catch (Throwable $e) { $rows = []; }
?>
<div class="cover mb-3" style="height: 220px;">
  <img src="<?= e($cover) ?>" alt="Yangiliklar">
  <div class="cover-overlay"></div>
  <div class="cover-caption">
    <div class="h5 fw-semibold"><?= e(lang('nav.news','Yangiliklar')) ?></div>
    <div class="small"><?= e(lang('news.latest',"So'nggi xabarlar va e'lonlar")) ?></div>
  </div>
</div>
<div class="row g-3">
  <?php if (!$rows): ?>
    <div class="col-12"><div class="text-muted small"><?= e(lang('news.none',"Hozircha yangiliklar yo'q.")) ?></div></div>
  <?php else: ?>
    <?php foreach ($rows as $n): ?>
      <?php 
        $img = news_cover_url($n['cover_url']);
        $title = ($lang === 'ru' && !empty($n['title_ru'])) ? $n['title_ru'] : $n['title'];
      ?>
      <div class="col-12 col-sm-6 col-lg-4">
        <a class="card h-100 shadow-sm text-decoration-none card-hover" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/news_view.php?id=<?= (int)$n['id'] ?>">
          <div class="cover" style="height: 160px;">
            <img src="<?= e($img) ?>" alt="cover">
            <div class="cover-overlay"></div>
          </div>
          <div class="card-body">
            <div class="fw-semibold line-clamp-2"><?= e($title) ?></div>
            <div class="text-muted small mt-1"><?= e(format_datetime($n['created_at'])) ?></div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
