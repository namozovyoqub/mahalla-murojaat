<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_admin();

$__page_title = lang('nav.admin.news','Yangiliklar');
require_once __DIR__ . '/../includes/header.php';

$cover = asset_url('assets/admin-news.jpg', 'https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=1600&auto=format&fit=crop');

if (is_post()) {
    $token = $_POST['csrf_token'] ?? '';
    if (!csrf_validate($token)) {
        flash('error', lang('msg.csrf_error','Xavfsizlik xatosi.'));
        redirect('admin/news.php');
    }
    $action = $_POST['action'] ?? 'create';
    if ($action === 'create') {
        $title = trim((string)($_POST['title'] ?? ''));
        $body = trim((string)($_POST['body'] ?? ''));
        $cover_url = trim((string)($_POST['cover_url'] ?? ''));
        if ($title === '' || $body === '') {
            flash('error', lang('msg.required_title_body','Sarlavha va matn talab qilinadi.'));
        } else {
            $stmt = $pdo->prepare('INSERT INTO news (title, body, cover_url) VALUES (?,?,?)');
            $stmt->execute([$title, $body, $cover_url !== '' ? $cover_url : null]);
            flash('success', lang('msg.news_added', "Yangilik qo'shildi."));
            redirect('admin/news.php');
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare('DELETE FROM news WHERE id = ?');
            $stmt->execute([$id]);
            flash('success', lang('msg.deleted', "O'chirildi."));
        }
        redirect('admin/news.php');
    }
}

$stmt = $pdo->query('SELECT id, title, cover_url, created_at FROM news ORDER BY created_at DESC');
$rows = $stmt->fetchAll() ?: [];
?>
<div class="cover mb-3" style="height: 180px;">
  <img src="<?= e($cover) ?>" alt="Admin — yangiliklar">
  <div class="cover-overlay"></div>
  <div class="cover-caption">
    <div class="h6 fw-semibold"><?= e(lang('news.manage','Yangiliklar boshqaruvi')) ?></div>
    <div class="small"><?= e(lang('news.manage_desc',"Qo'shish, tahrirlash va o'chirish")) ?></div>
  </div>
</div>
<div class="row">
  <div class="col-lg-5">
    <div class="card shadow-sm mb-3">
      <div class="card-body">
        <div class="fw-semibold mb-2"><i class="fa-solid fa-plus me-1"></i><?= e(lang('news.add',"Yangilik qo'shish")) ?></div>
        <form method="post">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="create">
          <div class="mb-2">
            <label class="form-label"><?= e(lang('news.title','Sarlavha')) ?></label>
            <input type="text" name="title" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label"><?= e(lang('news.cover_url','Qopqoq rasm URL (ixtiyoriy)')) ?></label>
            <input type="url" name="cover_url" class="form-control" placeholder="https://...">
          </div>
          <div class="mb-3">
            <label class="form-label"><?= e(lang('news.body','Matn')) ?></label>
            <textarea name="body" rows="6" class="form-control" required></textarea>
          </div>
          <button class="btn btn-primary" type="submit"><?= e(lang('common.save','Saqlash')) ?></button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-lg-7">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="fw-semibold mb-2"><i class="fa-solid fa-newspaper me-1"></i><?= e(lang('news.list','Yangi postlar')) ?></div>
        <?php if (!$rows): ?>
          <div class="text-muted small"><?= e(lang('news.none',"Hozircha yangiliklar yo'q.")) ?></div>
        <?php else: ?>
          <div class="list-group list-group-flush">
            <?php foreach ($rows as $n): ?>
              <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-start">
                  <div class="me-2">
                    <div class="fw-semibold">#<?= (int)$n['id'] ?> — <?= e($n['title']) ?></div>
                    <div class="text-muted small"><?= e(format_datetime($n['created_at'])) ?></div>
                  </div>
                  <div class="ms-auto">
                    <a class="btn btn-sm btn-outline-primary" href="<?= e(rtrim(BASE_URL,'/')) ?>/admin/news_edit.php?id=<?= (int)$n['id'] ?>"><?= e(lang('common.edit','Tahrirlash')) ?></a>
                    <form method="post" class="d-inline" onsubmit="return confirm(<?= json_encode(lang('common.confirm_delete',"O'chirishni tasdiqlaysizmi?")) ?>);">
                      <?= csrf_field() ?>
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="id" value="<?= (int)$n['id'] ?>">
                      <button class="btn btn-sm btn-outline-danger" type="submit"><?= e(lang('common.delete',"O'chirish")) ?></button>
                    </form>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
