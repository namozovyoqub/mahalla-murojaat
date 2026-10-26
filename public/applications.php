<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_user();
require_once __DIR__ . '/../includes/header.php';

$page_img = asset_url('assets/page-list.jpg', 'https://images.unsplash.com/photo-1544989164-31dc3c645987?q=80&w=1600&auto=format&fit=crop');

$stmt = $pdo->prepare('SELECT * FROM applications WHERE user_id = ? ORDER BY updated_at DESC');
$stmt->execute([(int)$_SESSION['user_id']]);
$rows = $stmt->fetchAll();
?>
<div class="cover mb-3" style="height: 180px;">
  <img src="<?= e($page_img) ?>" alt="Uzbekistan – tarixiy obida">
  <div class="cover-overlay"></div>
  <div class="cover-caption">
    <div class="h6 fw-semibold"><?= e(lang('app.list.title', 'Mening murojaatlarim')) ?></div>
    <div class="small"><?= e(lang('app.list.subtitle', 'Yuborilgan murojaatlar va holatlar')) ?></div>
  </div>
</div>
<div class="row">
  <div class="col-12">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0"><i class="fa-solid fa-inbox me-1"></i><?= e(lang('app.list.heading', 'Mening murojaatlarim')) ?></h5>
      <a class="btn btn-success btn-sm" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/create_application.php"><i class="fa-solid fa-plus me-1"></i><?= e(lang('app.btn.new', 'Yangi murojaat')) ?></a>
    </div>
  </div>
  <?php if (!$rows): ?>
    <div class="col-12">
      <div class="alert alert-secondary"><?= e(lang('app.list.empty', "Hozircha murojaatlar yo'q.")) ?></div>
    </div>
  <?php endif; ?>
  <?php foreach ($rows as $r): ?>
    <div class="col-12">
      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <div class="fw-semibold"><?= e(lang('app.item_num', 'Murojaat №')) ?><?= (int)$r['id'] ?> — [<?= e(app_types()[$r['type']] ?? $r['type']) ?>] <?= e($r['title']) ?></div>
              <div class="text-muted small"><?= e(lang('common.created', 'Yaratilgan')) ?>: <?= e(format_datetime($r['created_at'])) ?> | <?= e(lang('common.updated', 'Yangilangan')) ?>: <?= e(format_datetime($r['updated_at'])) ?><?php if (!empty($r['mahalla'])): ?> | <?= e(lang('app.label.mahalla', 'Mahalla')) ?>: <?= e($r['mahalla']) ?><?php endif; ?></div>
            </div>
            <div>
              <span class="badge text-bg-<?= e(status_badge_class($r['status'])) ?>"><?= e(status_label($r['status'])) ?></span>
            </div>
          </div>
          <p class="mt-2 mb-2"><?= nl2br(e($r['message'])) ?></p>
          <?php if (!empty($r['file_path'])): ?>
            <a class="btn btn-outline-secondary btn-sm mb-2" target="_blank" href="<?= e(rtrim(BASE_URL,'/')) ?>/<?= e($r['file_path']) ?>"><i class="fa-solid fa-paperclip me-1"></i><?= e(lang('common.attachment', 'Biriktirilgan fayl')) ?></a>
          <?php endif; ?>
          
          <div class="mt-3 pt-2 border-top d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="d-flex align-items-center gap-2">
              <?php if ($r['status'] === 'Yangi'): ?>
                <a class="btn btn-outline-primary btn-sm" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/edit_application.php?id=<?= (int)$r['id'] ?>"><i class="fa-solid fa-pen-to-square me-1"></i><?= e(lang('common.edit', 'Tahrirlash')) ?></a>
              <?php else: ?>
                <span class="text-muted small" title="<?= e(lang('app.edit_locked_title', "Qabul qilingan murojaatlarni tahrirlab bo'lmaydi")) ?>"><i class="fa-solid fa-lock me-1"></i><?= e(lang('app.edit_locked', 'Tahrirlash yopiq')) ?></span>
              <?php endif; ?>
            </div>
            <?php if (isset($r['public_code']) && $r['public_code']): ?>
              <div class="small d-flex align-items-center gap-2">
                <span class="text-muted"><?= e(lang('common.check_code', 'Tekshirish kodi')) ?>:</span>
                <code><?= e($r['public_code']) ?></code>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-copy="<?= e($r['public_code']) ?>"><i class="fa-regular fa-copy me-1"></i><?= e(lang('common.copy', 'Nusxa olish')) ?></button>
                <a class="btn btn-outline-primary btn-sm" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/track.php?code=<?= e(urlencode($r['public_code'])) ?>"><i class="fa-solid fa-magnifying-glass me-1"></i><?= e(lang('track.submit', 'Tekshirish')) ?></a>
              </div>
            <?php endif; ?>
          </div>

          <?php if (!empty($r['admin_comment'])): ?>
            <div class="mt-3 p-2 border rounded bg-light">
              <div class="small text-muted mb-1"><?= e(lang('admin.app.admin_comment', 'Admin izohi')) ?>:</div>
              <div><?= nl2br(e($r['admin_comment'])) ?></div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
