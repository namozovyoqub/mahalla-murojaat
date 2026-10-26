<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_admin();
require_once __DIR__ . '/../includes/header.php';

$dash_img = asset_url('assets/dash-admin.jpg', 'https://images.unsplash.com/photo-1544989164-31dc3c645987?q=80&w=1600&auto=format&fit=crop');

$tot = (int)$pdo->query('SELECT COUNT(*) FROM applications')->fetchColumn();
$by = array_fill_keys(statuses(), 0);
foreach ($pdo->query('SELECT status, COUNT(*) c FROM applications GROUP BY status') as $row) {
    $by[$row['status']] = (int)$row['c'];
}
?>
<div class="cover mb-3" style="height: 200px;">
  <img src="<?= e($dash_img) ?>" alt="Uzbekistan – tarixiy obida">
  <div class="cover-overlay"></div>
  <div class="cover-caption">
    <div class="h5 fw-semibold"><?= e(lang('admin.dash.title', 'Admin boshqaruv paneli')) ?></div>
    <div class="small"><?= e(lang('admin.dash.subtitle', 'Statistika va murojaatlar hisobi')) ?></div>
  </div>
</div>
<div class="row g-3">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <h5 class="mb-1"><?= e(lang('admin.dash.title', 'Admin boshqaruv paneli')) ?></h5>
          <div class="text-muted"><?= e(lang('admin.dash.total_apps', 'Jami murojaatlar')) ?>: <strong><?= e((string)$tot) ?></strong></div>
        </div>
        <div>
          <a href="<?= e(rtrim(BASE_URL,'/')) ?>/admin/applications.php" class="btn btn-outline-primary"><?= e(lang('dashboard.view_all', "Barchasini ko'rish")) ?></a>
        </div>
      </div>
    </div>
  </div>
  <?php foreach ($by as $k=>$v): ?>
  <div class="col-6 col-md-4 col-lg-2">
    <div class="card text-center shadow-sm">
      <div class="card-body">
        <div class="fw-semibold mb-1"><?= e(status_label($k)) ?></div>
        <div class="display-6"><?= e((string)$v) ?></div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
