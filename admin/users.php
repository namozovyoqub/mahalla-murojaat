<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_admin();
require_once __DIR__ . '/../includes/header.php';

$page_img = asset_url('assets/admin-users.jpg', 'https://images.unsplash.com/photo-1603451952566-9b6a26c6f1d0?q=80&w=1600&auto=format&fit=crop');

$filters = [
  'q' => trim($_GET['q'] ?? ''),
  'mahalla' => trim($_GET['mahalla'] ?? '')
];

if (is_post() && isset($_POST['delete_user_id'])) {
    $token = $_POST['csrf_token'] ?? '';
    if (csrf_validate($token)) {
        $did = (int)$_POST['delete_user_id'];
        $pdo->prepare('DELETE FROM users WHERE id = ?')->execute([$did]);
        flash('success', lang('msg.user_deleted', 'Foydalanuvchi o\'chirildi.'));
        redirect('admin/users.php');
    }
}

$query = 'SELECT u.*, COUNT(a.id) AS app_count FROM users u LEFT JOIN applications a ON a.user_id = u.id WHERE 1=1';
$params = [];

if ($filters['q'] !== '') {
    $query .= ' AND (u.full_name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)';
    $like = '%' . $filters['q'] . '%';
    array_push($params, $like, $like, $like);
}

if ($filters['mahalla'] !== '' && in_array($filters['mahalla'], bukhara_mahallas(), true)) {
    $query .= ' AND u.mahalla = ?';
    $params[] = $filters['mahalla'];
}

$query .= ' GROUP BY u.id ORDER BY u.created_at DESC LIMIT 200';
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$rows = $stmt->fetchAll();
?>
<div class="cover mb-3" style="height: 180px;">
  <img src="<?= e($page_img) ?>" alt="Uzbekistan – tarixiy obida">
  <div class="cover-overlay"></div>
  <div class="cover-caption">
    <div class="h6 fw-semibold"><?= e(lang('admin.users.title', 'Foydalanuvchilar')) ?></div>
    <div class="small"><?= e(lang('admin.users.subtitle', 'Ro\'yxat va hisoblar')) ?></div>
  </div>
</div>
<div class="row">
  <div class="col-12">
    <div class="card shadow-sm mb-3">
      <div class="card-body">
        <form class="row g-2">
          <div class="col-12 col-md-4">
            <label class="form-label"><?= e(lang('admin.users.search', 'Qidiruv')) ?></label>
            <input type="text" name="q" value="<?= e($filters['q']) ?>" class="form-control" placeholder="<?= e(lang('admin.users.search_placeholder', 'Ism, email yoki telefon...')) ?>">
          </div>
          <div class="col-12 col-md-4">
            <label class="form-label"><?= e(lang('register.mahalla', 'Mahalla')) ?></label>
            <select name="mahalla" class="form-select">
              <option value="">— <?= e(lang('common.all', 'Barchasi')) ?> —</option>
              <?php foreach (bukhara_mahallas() as $m): ?>
                <option value="<?= e($m) ?>" <?= $filters['mahalla']===$m?'selected':'' ?>><?= e($m) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-12 col-md-4 d-flex align-items-end">
            <button class="btn btn-primary w-100"><i class="fa-solid fa-filter me-1"></i><?= e(lang('admin.app.filter', 'Filtrlash')) ?></button>
          </div>
        </form>
      </div>
    </div>
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title mb-3"><i class="fa-solid fa-users me-1"></i><?= e(lang('admin.users.title', 'Foydalanuvchilar')) ?></h5>
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th><?= e(lang('register.fullname', 'F.I.Sh')) ?></th>
                <th><?= e(lang('common.email', 'Email')) ?> / <?= e(lang('register.phone', 'Telefon')) ?></th>
                <th><?= e(lang('register.mahalla', 'Mahalla')) ?></th>
                <th><?= e(lang('admin.users.registered', "Ro'yxatdan o'tgan")) ?></th>
                <th><?= e(lang('admin.users.apps_count', 'Murojaatlar')) ?></th>
                <th><?= e(lang('admin.users.actions', 'Amallar')) ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($rows as $r): ?>
                <tr>
                  <td><?= (int)$r['id'] ?></td>
                  <td><?= e($r['full_name']) ?></td>
                  <td><?= e($r['email']) ?><br><small class="text-muted"><?= e($r['phone']) ?></small></td>
                  <td><?= e($r['mahalla'] ?? lang('admin.users.not_set', 'Belgilanmagan')) ?></td>
                  <td><?= e(format_datetime($r['created_at'])) ?></td>
                  <td><span class="badge text-bg-secondary"><?= (int)$r['app_count'] ?></span></td>
                  <td>
                    <form method="post" style="display:inline-block;" onsubmit="return confirm(<?= json_encode(lang('admin.users.confirm_delete', "Rostdan ham ushbu foydalanuvchini o'chirmoqchimisiz?")) ?>);">
                      <?= csrf_field() ?>
                      <input type="hidden" name="delete_user_id" value="<?= (int)$r['id'] ?>">
                      <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
