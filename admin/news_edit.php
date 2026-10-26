<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_admin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { flash('error', lang('msg.invalid_id', "Noto'g'ri ID")); redirect('admin/news.php'); }

$stmt = $pdo->prepare('SELECT * FROM news WHERE id = ?');
$stmt->execute([$id]);
$news = $stmt->fetch();
if (!$news) { flash('error', lang('msg.post_not_found','Post topilmadi')); redirect('admin/news.php'); }

$__page_title = lang('news.edit','Yangilikni tahrirlash') . ': #' . $news['id'];

if (is_post()) {
    $token = $_POST['csrf_token'] ?? '';
    if (!csrf_validate($token)) { flash('error', lang('msg.csrf_error','Xavfsizlik xatosi')); redirect('admin/news_edit.php?id='.$id); }
    $title = trim((string)($_POST['title'] ?? ''));
    $body = trim((string)($_POST['body'] ?? ''));
    $cover_url = trim((string)($_POST['cover_url'] ?? ''));
    if ($title === '' || $body === '') {
        flash('error', lang('msg.required_title_body','Sarlavha va matn talab qilinadi.'));
    } else {
        $upd = $pdo->prepare('UPDATE news SET title = ?, body = ?, cover_url = ? WHERE id = ?');
        $upd->execute([$title, $body, $cover_url !== '' ? $cover_url : null, $id]);
        flash('success', lang('msg.saved','Saqlangan.'));
        redirect('admin/news_edit.php?id='.$id);
    }
}

require_once __DIR__ . '/../includes/header.php';
$cover = asset_url('assets/admin-news.jpg', 'https://images.unsplash.com/photo-1557683316-973673baf926?q=80&w=1600&auto=format&fit=crop');
?>
<div class="cover mb-3" style="height: 180px;">
  <img src="<?= e($cover) ?>" alt="Admin — yangilik tahriri">
  <div class="cover-overlay"></div>
  <div class="cover-caption">
    <div class="h6 fw-semibold"><?= e(lang('news.edit','Yangilikni tahrirlash')) ?></div>
    <div class="small">#<?= (int)$news['id'] ?> — <?= e($news['title']) ?></div>
  </div>
</div>
<div class="card shadow-sm">
  <div class="card-body">
    <form method="post">
      <?= csrf_field() ?>
      <div class="mb-2">
        <label class="form-label"><?= e(lang('news.title','Sarlavha')) ?></label>
        <input type="text" name="title" class="form-control" value="<?= e($news['title']) ?>" required>
      </div>
      <div class="mb-2">
        <label class="form-label"><?= e(lang('news.cover_url','Qopqoq rasm URL (ixtiyoriy)')) ?></label>
        <input type="url" name="cover_url" class="form-control" value="<?= e($news['cover_url'] ?? '') ?>" placeholder="https://...">
      </div>
      <div class="mb-3">
        <label class="form-label"><?= e(lang('news.body','Matn')) ?></label>
        <textarea name="body" rows="10" class="form-control" required><?= e($news['body']) ?></textarea>
      </div>
      <button class="btn btn-primary" type="submit"><?= e(lang('common.save','Saqlash')) ?></button>
      <a class="btn btn-outline-secondary" href="<?= e(rtrim(BASE_URL,'/')) ?>/admin/news.php"><?= e(lang('common.back','Orqaga')) ?></a>
      <a class="btn btn-outline-primary" target="_blank" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/news_view.php?id=<?= (int)$news['id'] ?>"><?= e(lang('common.view_public',"Public ko'rish")) ?></a>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
