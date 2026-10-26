<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_user();
require_once __DIR__ . '/../includes/header.php';

$dash_img = asset_url('assets/dashboard-cover.png', 'https://images.unsplash.com/photo-1557804506-669a67965ba0?q=80&w=1600&auto=format&fit=crop');

$user = current_user($pdo);

$stats = array_fill_keys(statuses(), 0);
$stmt = $pdo->prepare('SELECT status, COUNT(*) c FROM applications WHERE user_id = ? GROUP BY status');
$stmt->execute([(int)$user['id']]);
foreach ($stmt as $row) { $stats[$row['status']] = (int)$row['c']; }
$total = array_sum($stats);
?>
<div class="cover mb-3" style="height: 200px;">
  <img src="<?= e($dash_img) ?>" alt="Uzbekistan – tarixiy obida">
  <div class="cover-overlay"></div>
  <div class="cover-caption">
    <div class="h5 fw-semibold"><?= e(lang('dashboard.hero.title', 'Shaxsiy kabinet')) ?></div>
    <div class="small"><?= e(lang('dashboard.hero.subtitle', 'Murojaatlaringizni boshqaring')) ?></div>
  </div>
  </div>
<div class="row g-3">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-0"><?= e(lang('dashboard.welcome', 'Salom')) ?>, <?= e($user['full_name']) ?>!</h5>
            <small class="text-muted"><?= e(lang('common.profile', 'Profil')) ?>: <?= e($user['email']) ?> | <?= e(lang('common.phone', 'Tel')) ?>: <?= e($user['phone']) ?></small>
          </div>
          <div>
            <a class="btn btn-success" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/create_application.php"><i class="fa-solid fa-plus me-1"></i><?= e(lang('dashboard.new_app', 'Yangi murojaat')) ?></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php foreach ($stats as $k=>$v): ?>
  <div class="col-6 col-md-4 col-lg-2">
    <div class="card text-center shadow-sm">
      <div class="card-body">
        <div class="fw-semibold mb-1"><?= e(status_label($k)) ?></div>
        <div class="display-6"><?= e((string)$v) ?></div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-body d-flex justify-content-between">
        <div><?= e(lang('dashboard.total', 'Jami murojaatlar')) ?>: <strong><?= e((string)$total) ?></strong></div>
        <div><a href="<?= e(rtrim(BASE_URL,'/')) ?>/public/applications.php" class="btn btn-outline-primary btn-sm"><?= e(lang('dashboard.view_all', "Barchasini ko'rish")) ?></a></div>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
