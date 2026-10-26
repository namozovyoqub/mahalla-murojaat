<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_admin();
require_once __DIR__ . '/../includes/header.php';

$page_img = asset_url('assets/admin-list.jpg', 'https://images.unsplash.com/photo-1523731407965-2430cd12f5e4?q=80&w=1600&auto=format&fit=crop');

$filters = [
  'q' => $_GET['q'] ?? '',
  'status' => $_GET['status'] ?? '',
  'type' => $_GET['type'] ?? '',
  'from' => $_GET['from'] ?? '',
  'to' => $_GET['to'] ?? '',
];

$hasPublicCode = false;
try {
    $chk = $pdo->query("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME='applications' AND COLUMN_NAME='public_code'")->fetchColumn();
    $hasPublicCode = (bool)$chk;
} catch (Throwable $e) { /* ignore */ }

$query = 'SELECT a.*, u.full_name FROM applications a JOIN users u ON u.id = a.user_id WHERE 1=1';
$params = [];

if ($filters['status'] && in_array($filters['status'], statuses(), true)) {
    $query .= ' AND a.status = ?';
    $params[] = $filters['status'];
}
if ($filters['type'] && in_array($filters['type'], array_keys(app_types()), true)) {
    $query .= ' AND a.type = ?';
    $params[] = $filters['type'];
}
if ($filters['from'] && preg_match('/^\d{4}-\d{2}-\d{2}$/', $filters['from'])) {
    $query .= ' AND a.created_at >= ?';
    $params[] = $filters['from'].' 00:00:00';
}
if ($filters['to'] && preg_match('/^\d{4}-\d{2}-\d{2}$/', $filters['to'])) {
    $query .= ' AND a.created_at <= ?';
    $params[] = $filters['to'].' 23:59:59';
}

if ($filters['q']) {
    $q = trim((string)$filters['q']);
    if (ctype_digit($q)) {
        $query .= ' AND (a.id = ? OR a.title LIKE ?)';
        $params[] = (int)$q;
        $params[] = '%' . $q . '%';
    } elseif ($hasPublicCode && strlen($q) >= 6 && strlen($q) <= 12 && preg_match('/^[A-Za-z0-9]+$/', $q)) {
        $query .= ' AND (UPPER(a.public_code) = ? OR a.title LIKE ? OR u.full_name LIKE ?)';
        $params[] = strtoupper($q);
        $params[] = '%' . $q . '%';
        $params[] = '%' . $q . '%';
    } else {
        $query .= ' AND (a.title LIKE ? OR u.full_name LIKE ? OR a.mahalla LIKE ?)';
        $params[] = '%' . $q . '%';
        $params[] = '%' . $q . '%';
        $params[] = '%' . $q . '%';
    }
}

$query .= ' ORDER BY a.created_at DESC LIMIT 100';

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$rows = $stmt->fetchAll();
?>
<div class="cover mb-3" style="height: 180px;">
  <img src="<?= e($page_img) ?>" alt="Uzbekistan – tarixiy obida">
  <div class="cover-overlay"></div>
  <div class="cover-caption">
    <div class="h6 fw-semibold">Murojaatlar</div>
    <div class="small">Filtrlash va ko‘rish</div>
  </div>
</div>
<div class="row">
  <div class="col-12">
    <div class="card shadow-sm mb-3">
      <div class="card-body">
        <form class="row g-2">
          <div class="col-12 col-md-3">
            <label class="form-label">ID yoki Kod</label>
            <input type="text" name="q" value="<?= e($filters['q']) ?>" class="form-control" placeholder="Masalan: 1024 yoki AB3D7K9PQS">
          </div>
          <div class="col-6 col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option value="">— Barchasi —</option>
              <?php foreach (statuses() as $s): ?>
                <option value="<?= e($s) ?>" <?= $filters['status']===$s?'selected':'' ?>><?= e($s) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-6 col-md-3">
            <label class="form-label">Turi</label>
            <select name="type" class="form-select">
              <option value="">— Barchasi —</option>
              <?php foreach (app_types() as $k=>$v): ?>
                <option value="<?= e($k) ?>" <?= $filters['type']===$k?'selected':'' ?>><?= e($v) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-6 col-md-3">
            <label class="form-label">Sana (dan)</label>
            <input type="date" name="from" class="form-control" value="<?= e($filters['from']) ?>">
          </div>
          <div class="col-6 col-md-3">
            <label class="form-label">Sana (gacha)</label>
            <input type="date" name="to" class="form-control" value="<?= e($filters['to']) ?>">
          </div>
          <div class="col-12 d-flex justify-content-end">
            <button class="btn btn-primary"><i class="fa-solid fa-filter me-1"></i>Filtrlash</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php if (!$rows): ?>
    <div class="col-12"><div class="alert alert-secondary">Ma'lumot topilmadi.</div></div>
  <?php endif; ?>
  <?php foreach ($rows as $r): ?>
    <div class="col-12">
      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <div class="fw-semibold">[<?= e(app_types()[$r['type']] ?? $r['type']) ?>] <?= e($r['title']) ?></div>
              <div class="small text-muted">Foydalanuvchi: <?= e($r['full_name']) ?> | Yaratilgan: <?= e(format_datetime($r['created_at'])) ?><?php if (!empty($r['mahalla'])): ?> | Mahalla: <?= e($r['mahalla']) ?><?php endif; ?></div>
            </div>
            <div>
              <span class="badge text-bg-<?= e(status_badge_class($r['status'])) ?>"><?= e($r['status']) ?></span>
            </div>
          </div>
          <div class="mt-2 d-flex gap-2">
            <?php if (isset($r['public_code']) && $r['public_code']): ?>
              <span class="small text-muted">Kod:</span>
              <code><?= e($r['public_code']) ?></code>
              <a class="btn btn-sm btn-outline-secondary" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/track.php?code=<?= e(urlencode($r['public_code'])) ?>">Public ko'rish</a>
            <?php endif; ?>
            <a class="btn btn-sm btn-outline-primary" href="<?= e(rtrim(BASE_URL,'/')) ?>/admin/application_view.php?id=<?= (int)$r['id'] ?>"><i class="fa-solid fa-eye me-1"></i>Ko'rish</a>
            <?php if (!empty($r['file_path'])): ?>
              <a class="btn btn-sm btn-outline-secondary" target="_blank" href="<?= e(rtrim(BASE_URL,'/')) ?>/<?= e($r['file_path']) ?>"><i class="fa-solid fa-paperclip me-1"></i>Fayl</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
