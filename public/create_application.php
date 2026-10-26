<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_user();

$page_img = asset_url('assets/page-create.jpg', 'https://images.unsplash.com/photo-1523731407965-2430cd12f5e4?q=80&w=1600&auto=format&fit=crop');

$values = ['type'=>'','mahalla'=>'','title'=>'','message'=>''];
$maxSize = 10 * 1024 * 1024; // 10MB
$allowedMime = ['application/pdf','image/jpeg','image/png','video/mp4','video/mpeg','video/quicktime','video/x-msvideo'];
$allowedExt = ['pdf','jpg','jpeg','png','mp4','mpeg','mov','avi'];

if (is_post()) {
    $token = $_POST['csrf_token'] ?? '';
    if (!csrf_validate($token)) {
        flash('error', 'Xavfsizlik xatosi.');
        redirect('public/create_application.php');
    }

    $type = $_POST['type'] ?? '';
    $mahalla = trim($_POST['mahalla'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $values = compact('type','mahalla','title','message');

    $errors = [];
    if (!in_array($type, array_keys(app_types()), true)) $errors[] = lang('err.invalid_type', 'Murojaat turi noto\'g\'ri.');
    if ($mahalla === '' || !in_array($mahalla, bukhara_mahallas(), true)) $errors[] = lang('err.invalid_mahalla', 'Mahalla nomi noto\'g\'ri.');
    if ($title === '' || mb_strlen($title) < 3) $errors[] = lang('err.short_title', 'Sarlavha kamida 3 belgi.');
    if ($message === '' || mb_strlen($message) < 10) $errors[] = lang('err.short_body', 'Murojaat matni kamida 10 belgi.');

    $relativePath = null;

    if (!empty($_FILES['file']['name'])) {
        $f = $_FILES['file'];
        if ($f['error'] !== UPLOAD_ERR_OK) {
            $errors[] = lang('err.file_upload', 'Fayl yuklashda xatolik.');
        } else {
            if ($f['size'] > $maxSize) $errors[] = lang('err.file_size', 'Fayl hajmi 10MB dan oshmasin.');
            $fi = new finfo(FILEINFO_MIME_TYPE);
            $mime = $fi->file($f['tmp_name']);
            if (!in_array($mime, $allowedMime, true)) $errors[] = lang('err.file_mime', 'Ruxsat etilmagan fayl turi.');
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExt, true)) $errors[] = lang('err.file_ext', 'Ruxsat etilmagan fayl kengaytmasi.');
            if (!$errors) {
                ensure_upload_dir();
                $basename = date('YmdHis').'_'.bin2hex(random_bytes(8)).'.'.$ext;
                $target = rtrim(UPLOAD_DIR, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$basename;
                if (!move_uploaded_file($f['tmp_name'], $target)) {
                    $errors[] = lang('err.file_save', 'Fayl saqlanmadi.');
                } else {
                    $relativePath = 'uploads/'.$basename;
                }
            }
        }
    }

    if (!$errors) {
        try {
            $public_code = generate_public_code($pdo);
            $stmt = $pdo->prepare('INSERT INTO applications (user_id, type, mahalla, title, message, file_path, public_code, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, \'Yangi\', NOW(), NOW())');
            $stmt->execute([(int)$_SESSION['user_id'], $type, $mahalla, $title, $message, $relativePath, $public_code]);
            flash('success', lang('msg.app_sent_code', 'Murojaat yuborildi. Tekshirish kodi: ') . $public_code);
        } catch (Throwable $e) {
            $stmt = $pdo->prepare('INSERT INTO applications (user_id, type, mahalla, title, message, file_path, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, \'Yangi\', NOW(), NOW())');
            $stmt->execute([(int)$_SESSION['user_id'], $type, $mahalla, $title, $message, $relativePath]);
            flash('success', lang('msg.app_sent', 'Murojaat yuborildi.'));
        }
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
    <div class="h6 fw-semibold"><?= e(lang('app.new.title', 'Yangi murojaat')) ?></div>
    <div class="small"><?= e(lang('app.new.subtitle', 'Ariza, shikoyat yoki taklif yuborish')) ?></div>
  </div>
  </div>
<div class="row justify-content-center">
  <div class="col-md-8 col-lg-7">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title mb-3"><i class="fa-solid fa-file-pen me-1"></i><?= e(lang('app.new.form_title', 'Yangi murojaat')) ?></h5>
        <form method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <div class="mb-3">
            <label class="form-label"><?= e(lang('app.label.type', 'Murojaat turi')) ?></label>
            <select name="type" class="form-select" required>
              <option value=""><?= e(lang('app.select_placeholder', '— Tanlang —')) ?></option>
              <?php foreach (app_types() as $k=>$v): ?>
                <option value="<?= e($k) ?>" <?= $values['type']===$k?'selected':'' ?>><?= e($v) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label"><?= e(lang('app.label.mahalla', 'Mahalla')) ?></label>
            <select name="mahalla" class="form-select" required>
              <option value=""><?= e(lang('app.select_mahalla_placeholder', '— Mahallani tanlang —')) ?></option>
              <?php foreach (bukhara_mahallas() as $m): ?>
                <option value="<?= e($m) ?>" <?= $values['mahalla']===$m?'selected':'' ?>><?= e($m) ?></option>
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
          <div class="mb-3">
            <label class="form-label"><?= e(lang('app.label.file', 'Fayl (pdf, jpg, png, video | max 10MB)')) ?></label>
            <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.mp4,.mpeg,.mov,.avi">
          </div>
          <button class="btn btn-success" type="submit"><i class="fa-solid fa-paper-plane me-1"></i><?= e(lang('app.btn.send', 'Yuborish')) ?></button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
