<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_admin();
require_once __DIR__ . '/../includes/header.php';

$page_img = asset_url('assets/admin-view.jpg', 'https://images.unsplash.com/photo-1517502884422-41eaead166d4?q=80&w=1600&auto=format&fit=crop');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { flash('error','Noto\'g\'ri ID'); redirect('admin/applications.php'); }

$stmt = $pdo->prepare('SELECT a.*, u.full_name, u.phone, u.email FROM applications a JOIN users u ON u.id = a.user_id WHERE a.id = ?');
$stmt->execute([$id]);
$app = $stmt->fetch();
if (!$app) { flash('error','Murojaat topilmadi'); redirect('admin/applications.php'); }

if (is_post()) {
    $token = $_POST['csrf_token'] ?? '';
    if (!csrf_validate($token)) {
        flash('error','Xavfsizlik xatosi');
        redirect('admin/application_view.php?id='.$id);
    }
    
    if (isset($_POST['delete_app'])) {
        $stmt = $pdo->prepare('DELETE FROM applications WHERE id = ?');
        $stmt->execute([$id]);
        flash('success', 'Murojaat muvaffaqiyatli o\'chirildi.');
        redirect('admin/applications.php');
    }

    $status = $_POST['status'] ?? '';
    $admin_comment = trim($_POST['admin_comment'] ?? '');
    if (!in_array($status, statuses(), true)) {
        flash('error','Noto\'g\'ri status');
    } else {
        $prev_status = $app['status'];
        $stmt = $pdo->prepare('UPDATE applications SET status = ?, admin_comment = ?, updated_at = NOW() WHERE id = ?');
        $stmt->execute([$status, $admin_comment, $id]);
        // Logga yozish (mavjud bo'lsa)
        try {
          $adm = current_admin($pdo);
          $admin_id = $adm['id'] ?? null;
          $log = $pdo->prepare('INSERT INTO application_logs (application_id, admin_id, prev_status, new_status, comment) VALUES (?,?,?,?,?)');
          $log->execute([$id, $admin_id, $prev_status, $status, $admin_comment !== '' ? $admin_comment : null]);
        } catch (Throwable $e) { /* agar jadval yo'q bo'lsa, e'tibor bermaslik */ }
        flash('success','Yangilandi');
        redirect('admin/application_view.php?id='.$id);
    }
}
?>
<div class="cover mb-3" style="height: 180px;">
  <img src="<?= e($page_img) ?>" alt="Uzbekistan – tarixiy obida">
  <div class="cover-overlay"></div>
  <div class="cover-caption">
    <div class="h6 fw-semibold"><?= e(lang('admin.app.details','Murojaat tafsilotlari')) ?></div>
    <div class="small"><?= e(lang('admin.app.view_update','Ko‘rish va yangilash')) ?></div>
  </div>
</div>
<div class="row">
  <div class="col-lg-8">
    <div class="card shadow-sm mb-3">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <h5 class="mb-0">[<?= e(app_types()[$app['type']] ?? $app['type']) ?>] <?= e($app['title']) ?></h5>
          <span class="badge text-bg-<?= e(status_badge_class($app['status'])) ?>"><?= e($app['status']) ?></span>
        </div>
        <div class="text-muted small"><?= e(lang('common.created','Yaratilgan')) ?>: <?= e(format_datetime($app['created_at'])) ?> | <?= e(lang('common.updated','Yangilangan')) ?>: <?= e(format_datetime($app['updated_at'])) ?></div>
        <p class="mt-3"><?= nl2br(e($app['message'])) ?></p>
        <?php if (!empty($app['file_path'])): ?>
          <a class="btn btn-outline-secondary btn-sm" target="_blank" href="<?= e(rtrim(BASE_URL,'/')) ?>/<?= e($app['file_path']) ?>"><i class="fa-solid fa-paperclip me-1"></i><?= e(lang('common.attachment','Biriktirilgan fayl')) ?></a>
        <?php endif; ?>
        <?php if (!empty($app['public_code'])): ?>
          <div class="mt-3 small">
            <span class="text-muted"><?= e(lang('common.check_code','Tekshirish kodi')) ?>:</span>
            <code><?= e($app['public_code']) ?></code>
            <button type="button" class="btn btn-outline-secondary btn-sm ms-2" data-copy="<?= e($app['public_code']) ?>"><?= e(lang('common.copy','Nusxa olish')) ?></button>
            <a class="btn btn-outline-primary btn-sm ms-1" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/track.php?code=<?= e(urlencode($app['public_code'])) ?>"><?= e(lang('common.view_public',"Public ko'rish")) ?></a>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <div class="card shadow-sm mb-3">
      <div class="card-body">
        <div class="fw-semibold mb-2"><i class="fa-solid fa-clock-rotate-left me-1"></i><?= e(lang('admin.app.history',"O'zgarishlar tarixi")) ?></div>
        <?php
          try {
            $logs_stmt = $pdo->prepare('SELECT prev_status, new_status, comment, created_at FROM application_logs WHERE application_id = ? ORDER BY id DESC');
            $logs_stmt->execute([$id]);
            $logs = $logs_stmt->fetchAll();
          } catch (Throwable $e) { $logs = []; }
        ?>
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
          <div class="text-muted small"><?= e(lang('admin.app.no_history',"Hozircha tarix yo'q.")) ?></div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card shadow-sm mb-3">
      <div class="card-body">
        <div class="fw-semibold mb-2"><i class="fa-solid fa-user me-1"></i><?= e(lang('common.user','Foydalanuvchi')) ?></div>
        <div><?= e($app['full_name']) ?></div>
        <div class="text-muted small"><?= e(lang('common.email','Email')) ?>: <?= e($app['email']) ?> | <?= e(lang('common.phone','Tel')) ?>: <?= e($app['phone']) ?></div>
        <?php if (!empty($app['mahalla'])): ?>
          <div class="text-muted small mt-1"><i class="fa-solid fa-location-dot me-1 text-primary"></i><?= e(lang('app.label.mahalla', 'Mahalla')) ?>: <strong><?= e($app['mahalla']) ?></strong></div>
        <?php endif; ?>
      </div>
    </div>
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="fw-semibold mb-2"><i class="fa-solid fa-pen-to-square me-1"></i><?= e(lang('admin.app.status_comment','Status va izoh')) ?></div>
        <form method="post">
          <?= csrf_field() ?>
          <div class="mb-3">
            <label class="form-label"><?= e(lang('common.status','Status')) ?></label>
            <select name="status" class="form-select" required>
              <?php foreach (statuses() as $s): ?>
                <option value="<?= e($s) ?>" <?= $app['status']===$s?'selected':'' ?>><?= e($s) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label"><?= e(lang('admin.app.admin_comment','Admin izohi')) ?></label>
            <textarea name="admin_comment" class="form-control" rows="5"><?= e($app['admin_comment'] ?? '') ?></textarea>
          </div>
          <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit"><?= e(lang('common.save','Saqlash')) ?></button>
            <a class="btn btn-outline-secondary" href="<?= e(rtrim(BASE_URL,'/')) ?>/admin/applications.php"><?= e(lang('common.back','Orqaga')) ?></a>
          </div>
        </form>
        <hr>
        <form method="post" onsubmit="return confirm('Rostdan ham ushbu murojaatni butunlay o\'chirmoqchimisiz?');">
          <?= csrf_field() ?>
          <input type="hidden" name="delete_app" value="1">
          <button class="btn btn-danger w-100" type="submit"><i class="fa-solid fa-trash me-1"></i>Murojaatni o'chirish</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
