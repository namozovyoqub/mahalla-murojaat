<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';

$auth_img = asset_url('assets/cover.png', 'https://images.unsplash.com/photo-1603451952566-9b6a26c6f1d0?q=80&w=1600&auto=format&fit=crop');

$values = [
  'full_name' => '',
  'phone' => '',
  'email' => '',
  'mahalla' => ''
];

if (is_post()) {
    $token = $_POST['csrf_token'] ?? '';
    if (!csrf_validate($token)) {
        flash('error', 'Xavfsizlik xatosi. Iltimos, qayta urinib ko\'ring.');
        redirect('public/register.php');
    }

    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $mahalla = trim($_POST['mahalla'] ?? '');

    $values = compact('full_name','phone','email', 'mahalla');

    $errors = [];
    if ($full_name === '' || mb_strlen($full_name) < 3) $errors[] = 'F.I.Sh kamida 3 ta belgidan iborat bo\'lishi kerak.';
    if ($phone === '' || !preg_match('/^[0-9+\-()\s]{7,20}$/', $phone)) $errors[] = 'Telefon raqam noto\'g\'ri formatda.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email noto\'g\'ri.';
    if ($mahalla === '' || !in_array($mahalla, bukhara_mahallas(), true)) $errors[] = 'Mahalla nomi noto\'g\'ri.';
    if (mb_strlen($password) < 6) $errors[] = 'Parol kamida 6 belgidan iborat bo\'lishi kerak.';

    if (!$errors) {
        try {
            $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = 'Ushbu email bilan foydalanuvchi allaqachon mavjud.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $ins = $pdo->prepare('INSERT INTO users (full_name, phone, email, password, mahalla, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
                $ins->execute([$full_name, $phone, $email, $hash, $mahalla]);
                flash('success', 'Ro\'yxatdan o\'tish muvaffaqiyatli! Endi tizimga kiring.');
                redirect('public/login.php');
            }
        } catch (Throwable $e) {
            $errors[] = 'Server xatosi. Iltimos, keyinroq urinib ko\'ring.';
        }
    }

    if ($errors) {
        flash('error', implode(' ', $errors));
    }
}

require_once __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
  <div class="col-lg-10">
    <div class="row g-0 shadow-sm rounded overflow-hidden">
      <div class="col-md-6 p-4 p-md-5 bg-white order-2 order-md-1">
        <h4 class="mb-3"><i class="fa-solid fa-user-plus me-1"></i><?= e(lang('register.title', "Ro'yxatdan o'tish")) ?></h4>
        <form method="post" novalidate>
          <?= csrf_field() ?>
          <div class="mb-3">
            <label class="form-label"><?= e(lang('register.fullname', 'F.I.Sh')) ?></label>
            <input type="text" name="full_name" class="form-control" value="<?= e($values['full_name']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><?= e(lang('register.phone', 'Telefon')) ?></label>
            <input type="text" name="phone" class="form-control" value="<?= e($values['phone']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><?= e(lang('register.email', 'Email')) ?></label>
            <input type="email" name="email" class="form-control" value="<?= e($values['email']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><?= e(lang('register.mahalla', 'Mahalla')) ?></label>
            <select name="mahalla" class="form-select" required>
              <option value=""><?= e(lang('app.select_mahalla_placeholder', '— Mahallani tanlang —')) ?></option>
              <?php foreach (bukhara_mahallas() as $m): ?>
                <option value="<?= e($m) ?>" <?= $values['mahalla']===$m?'selected':'' ?>><?= e($m) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label"><?= e(lang('register.password', 'Parol')) ?></label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button class="btn btn-primary w-100" type="submit"><?= e(lang('register.submit', "Ro'yxatdan o'tish")) ?></button>
        </form>
        <div class="mt-3 d-flex justify-content-between">
          <span class="text-muted small"><?= e(lang('register.have_account', 'Hisobingiz bormi?')) ?></span>
          <a href="<?= e(rtrim(BASE_URL,'/')) ?>/public/login.php">Kirish</a>
        </div>
      </div>
      <div class="col-md-6 p-0 order-1 order-md-2">
        <div class="cover" style="min-height: 340px; height: 100%">
          <img src="<?= e($auth_img) ?>" alt="Uzbekistan – tarixiy obida">
          <div class="cover-overlay"></div>
          <div class="cover-caption">
            <div class="fw-semibold"><?= e(lang('register.hero.title', "Qulay va xavfsiz ro'yxatdan o'tish")) ?></div>
            <div class="small"><?= e(lang('register.hero.desc', 'Shaxsiy kabinet orqali murojaatlaringizni boshqarishingiz mumkin.')) ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
