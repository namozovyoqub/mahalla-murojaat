<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/header.php';

$page_img = asset_url('assets/page-track.jpg', 'https://images.unsplash.com/photo-1523731407965-2430cd12f5e4?q=80&w=1600&auto=format&fit=crop');

$app = null;
$app_id = trim((string)($_GET['id'] ?? ''));
$code = trim((string)($_GET['code'] ?? ''));

if (is_post()) {
    $token = $_POST['csrf_token'] ?? '';
    if (!csrf_validate($token)) {
        flash('error', 'Xavfsizlik xatosi.');
        redirect('public/track.php');
    }
    $app_id = trim((string)($_POST['app_id'] ?? ''));
    $code = strtoupper(trim((string)($_POST['code'] ?? '')));
    if ($code !== '') {
        $stmt = $pdo->prepare('SELECT id, public_code, type, mahalla, title, status, created_at, updated_at FROM applications WHERE public_code = ?');
        $stmt->execute([$code]);
        $app = $stmt->fetch();
        if (!$app) { flash('error', 'Kod bo‘yicha murojaat topilmadi.'); }
    } elseif ($app_id !== '' && ctype_digit($app_id)) {
        $stmt = $pdo->prepare('SELECT id, public_code, type, mahalla, title, status, created_at, updated_at FROM applications WHERE id = ?');
        $stmt->execute([(int)$app_id]);
        $app = $stmt->fetch();
        if (!$app) { flash('error', 'Murojaat ID bo‘yicha topilmadi.'); }
    } else {
        flash('error', 'ID yoki Kod ni to‘g‘ri kiriting.');
    }
}
?>
<div class="cover mb-3" style="height: 180px;">
  <img src="<?= e($page_img) ?>" alt="Uzbekistan – tarixiy obida">
  <div class="cover-overlay"></div>
  <div class="cover-caption">
    <div class="h6 fw-semibold"><?= e(lang('track.title','Holatni tekshirish')) ?></div>
    <div class="small"><?= e(lang('track.subtitle','ID yoki kod orqali murojaat holatini ko‘ring')) ?></div>
  </div>
</div>

<div class="row justify-content-center">
  <div class="col-md-7 col-lg-6">
    <div class="card shadow-sm mb-3">
      <div class="card-body">
        <h5 class="card-title mb-3"><i class="fa-solid fa-magnifying-glass me-1"></i><?= e(lang('track.title','Holatni tekshirish')) ?></h5>
        <form method="post">
          <?= csrf_field() ?>
          <div class="mb-3">
            <label class="form-label"><?= e(lang('track.code_label','Murojaat kodi (afzal)')) ?></label>
            <input type="text" name="code" value="<?= e($code) ?>" class="form-control" placeholder="Masalan: AB3D7K9PQS" pattern="[A-Za-z0-9]{8,12}">
          </div>
          <div class="mb-3">
            <label class="form-label"><?= e(lang('track.id_label','Yoki ID')) ?></label>
            <input type="number" min="1" name="app_id" value="<?= e($app_id) ?>" class="form-control" placeholder="Masalan: 1024">
          </div>
          <button class="btn btn-primary" type="submit"><?= e(lang('track.submit','Tekshirish')) ?></button>
        </form>
      </div>
    </div>

    <?php if ($app): ?>
    <div class="card shadow-sm mb-3">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div class="fw-semibold">ID: <?= (int)$app['id'] ?> • Kod: <?= e($app['public_code'] ?? '') ?></div>
          <span class="badge bg-<?= e(status_badge_class($app['status'])) ?>"><?= e($app['status']) ?></span>
        </div>
        <div class="small text-muted mb-1"><?= e(app_types()[$app['type']] ?? ucfirst((string)$app['type'])) ?> • <?= e(format_datetime($app['created_at'])) ?><?php if (!empty($app['mahalla'])): ?> • <?= e(lang('app.label.mahalla', 'Mahalla')) ?>: <?= e($app['mahalla']) ?><?php endif; ?></div>
        <div class="line-clamp-3"><?= e($app['title']) ?></div>
      </div>
    </div>
    <?php
      $logs = [];
      try {
        $st = $pdo->prepare('SELECT prev_status, new_status, comment, created_at FROM application_logs WHERE application_id = ? ORDER BY id DESC');
        $st->execute([(int)$app['id']]);
        $logs = $st->fetchAll() ?: [];
      } catch (Throwable $e) { $logs = []; }
    ?>
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="fw-semibold mb-2"><i class="fa-solid fa-clock-rotate-left me-1"></i><?= e(lang('track.history','Jarayon tarixi')) ?></div>
        <?php if ($logs): ?>
          <ul class="list-group list-group-flush">
            <?php foreach ($logs as $row): ?>
            <li class="list-group-item">
              <div class="small text-muted mb-1"><?= e(format_datetime($row['created_at'])) ?></div>
              <div>
                <span class="badge text-bg-<?= e(status_badge_class((string)$row['prev_status'])) ?>"><?= e($row['prev_status'] ?? '-') ?></span>
                <i class="fa-solid fa-arrow-right mx-1"></i>
                <span class="badge text-bg-<?= e(status_badge_class((string)$row['new_status'])) ?>"><?= e($row['new_status'] ?? '-') ?></span>
              </div>
              <?php if (!empty($row['comment'])): ?>
                <div class="mt-1 small"><?= nl2br(e($row['comment'])) ?></div>
              <?php endif; ?>
            </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <div class="text-muted small"><?= e(lang('admin.app.no_history','Hozircha tarix yo\'q.')) ?></div>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
