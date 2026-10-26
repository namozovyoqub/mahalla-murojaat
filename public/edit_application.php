<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_user();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    flash('error', 'Noto‘g‘ri murojaat ID.');
    redirect('public/applications.php');
}

// Murojaatni yuklash
$stmt = $pdo->prepare('SELECT * FROM applications WHERE id = ? AND user_id = ?');
$stmt->execute([$id, (int)$_SESSION['user_id']]);
$app = $stmt->fetch();

if (!$app) {
    flash('error', 'Murojaat topilmadi yoki sizda ruxsat yo‘q.');
    redirect('public/applications.php');
}

// Faqat Yangi statusidagi murojaatlarni tahrirlash mumkin
if ($app['status'] !== 'Yangi') {
    flash('error', 'Faqat yangi murojaatlarni tahrirlash mumkin.');
    redirect('public/applications.php');
}

$page_img = asset_url('assets/page-create.jpg', 'https://images.unsplash.com/photo-1523731407965-2430cd12f5e4?q=80&w=1600&auto=format&fit=crop');

$values = [
    'type' => $app['type'],
    'mahalla' => $app['mahalla'] ?? '',
    'title' => $app['title'],
    'message' => $app['message']
];

$maxSize = 10 * 1024 * 1024; // 10MB
$allowedMime = ['application/pdf', 'image/jpeg', 'image/png', 'video/mp4', 'video/mpeg', 'video/quicktime', 'video/x-msvideo'];
$allowedExt = ['pdf', 'jpg', 'jpeg', 'png', 'mp4', 'mpeg', 'mov', 'avi'];

if (is_post()) {
    $token = $_POST['csrf_token'] ?? '';
    if (!csrf_validate($token)) {
        flash('error', 'Xavfsizlik xatosi.');
        redirect('public/edit_application.php?id=' . $id);
    }

    $type = $_POST['type'] ?? '';
    $mahalla = trim($_POST['mahalla'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $delete_file = isset($_POST['delete_file']) && $_POST['delete_file'] === '1';

    $values = compact('type', 'mahalla', 'title', 'message');

    $errors = [];
    if (!in_array($type, array_keys(app_types()), true)) $errors[] = lang('err.invalid_type', 'Murojaat turi noto\'g\'ri.');
    if ($mahalla === '' || !in_array($mahalla, bukhara_mahallas(), true)) $errors[] = lang('err.invalid_mahalla', 'Mahalla nomi noto\'g\'ri.');
    if ($title === '' || mb_strlen($title) < 3) $errors[] = lang('err.short_title', 'Sarlavha kamida 3 belgi.');
    if ($message === '' || mb_strlen($message) < 10) $errors[] = lang('err.short_body', 'Murojaat matni kamida 10 belgi.');

    $relativePath = $app['file_path'];

    if ($delete_file && $relativePath) {
        $fullOldPath = dirname(__DIR__) . '/' . $relativePath;
        if (is_file($fullOldPath)) {
            @unlink($fullOldPath);
        }
        $relativePath = null;
    }

    if (!empty($_FILES['file']['name'])) {
        $f = $_FILES['file'];
        if ($f['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Fayl yuklashda xatolik.';
        } else {
            if ($f['size'] > $maxSize) $errors[] = 'Fayl hajmi 10MB dan oshmasin.';
            $fi = new finfo(FILEINFO_MIME_TYPE);
            $mime = $fi->file($f['tmp_name']);
            if (!in_array($mime, $allowedMime, true)) $errors[] = 'Ruxsat etilmagan fayl turi.';
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExt, true)) $errors[] = 'Ruxsat etilmagan fayl kengaytmasi.';
            
            if (!$errors) {
                ensure_upload_dir();
                // Eski faylni o'chiramiz
                if ($app['file_path']) {
                    $fullOldPath = dirname(__DIR__) . '/' . $app['file_path'];
                    if (is_file($fullOldPath)) {
                        @unlink($fullOldPath);
                    }
                }
                
                $basename = date('YmdHis') . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                $target = rtrim(UPLOAD_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $basename;
                if (!move_uploaded_file($f['tmp_name'], $target)) {
                    $errors[] = 'Fayl saqlanmadi.';
                } else {
                    $relativePath = 'uploads/' . $basename;
                }
            }
        }
    }

    if (!$errors) {
        $stmt = $pdo->prepare('UPDATE applications SET type = ?, mahalla = ?, title = ?, message = ?, file_path = ?, updated_at = NOW() WHERE id = ?');
        $stmt->execute([$type, $mahalla, $title, $message, $relativePath, $id]);
        flash('success', 'Murojaat muvaffaqiyatli tahrirlandi.');
        redirect('public/applications.php');
    } else {
        flash('error', implode(' ', $errors));
    }
}

require_once __DIR__ . '/../includes/header.php';
?>
<div class="cover mb-3" style="height: 180px;">
  <img src="<?= e($page_img) ?>" alt="Uzbekistan – tarixiy obida">
  <div class="cover-overlay"></div>
  <div class="cover-caption">
    <div class="h6 fw-semibold">Murojaatni tahrirlash</div>
    <div class="small">№<?= (int)$app['id'] ?> sonli murojaatni tahrirlash</div>
  </div>
</div>
<div class="row justify-content-center">
  <div class="col-md-8 col-lg-7">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title mb-3"><i class="fa-solid fa-file-signature me-1"></i>Murojaatni tahrirlash (№<?= (int)$app['id'] ?>)</h5>
        <form method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <div class="mb-3">
            <label class="form-label"><?= e(lang('app.label.type', 'Murojaat turi')) ?></label>
            <select name="type" class="form-select" required>
              <option value=""><?= e(lang('app.select_placeholder', '— Tanlang —')) ?></option>
              <?php foreach (app_types() as $k => $v): ?>
                <option value="<?= e($k) ?>" <?= $values['type'] === $k ? 'selected' : '' ?>><?= e($v) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label"><?= e(lang('app.label.mahalla', 'Mahalla')) ?></label>
            <select name="mahalla" class="form-select" required>
              <option value=""><?= e(lang('app.select_mahalla_placeholder', '— Mahallani tanlang —')) ?></option>
              <?php foreach (bukhara_mahallas() as $m): ?>
                <option value="<?= e($m) ?>" <?= $values['mahalla'] === $m ? 'selected' : '' ?>><?= e($m) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label"><?= e(lang('app.label.title', 'Sarlavha')) ?></label>
            <input type="text" name="title" class="form-control" value="<?= e($values['title']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><?= e(lang('app.label.message', 'Murojaat matni')) ?></label>
            <textarea name="message" class="form-control" rows="6" required><?= e($values['message']) ?></textarea>
          </div>
          
          <?php if (!empty($app['file_path'])): ?>
            <div class="mb-3 p-2 border rounded bg-light">
              <div class="small text-muted mb-1">Mavjud yuklangan fayl:</div>
              <a href="<?= e(rtrim(BASE_URL,'/')) ?>/<?= e($app['file_path']) ?>" target="_blank" class="btn btn-outline-secondary btn-sm mb-2"><i class="fa-solid fa-paperclip me-1"></i>Faylni ko‘rish</a>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="delete_file" id="deleteFileCheck" value="1">
                <label class="form-check-label text-danger small" for="deleteFileCheck">
                  Mavjud biriktirilgan faylni o‘chirish
                </label>
              </div>
            </div>
          <?php endif; ?>

          <div class="mb-3">
            <label class="form-label"><?= e(lang('app.label.file', 'Yangi fayl yuklash (pdf, jpg, png, video | max 10MB)')) ?></label>
            <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.mp4,.mpeg,.mov,.avi">
            <span class="form-text text-muted small">Eski fayl o‘rniga yangi fayl biriktiriladi.</span>
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <a href="<?= e(rtrim(BASE_URL,'/')) ?>/public/applications.php" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-1"></i>Orqaga</a>
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-save me-1"></i>O‘zgarishlarni saqlash</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
