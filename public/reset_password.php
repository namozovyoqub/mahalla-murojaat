<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';

if (user_logged_in()) {
    redirect('public/dashboard.php');
}

$page_img = asset_url('assets/auth.jpg', 'https://images.unsplash.com/photo-1517502884422-41eaead166d4?q=80&w=1600&auto=format&fit=crop');

$step = 1;
$email = '';
$test_code = '';

if (is_post()) {
    $token = $_POST['csrf_token'] ?? '';
    if (!csrf_validate($token)) {
        flash('error', 'Xavfsizlik xatosi.');
        redirect('public/reset_password.php');
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'request_code') {
        $email = strtolower(trim($_POST['email'] ?? ''));
        if ($email === '') {
            flash('error', 'Email manzilini kiriting.');
        } else {
            $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                // Kod yaratish
                $code = (string)random_int(100000, 999999);
                $_SESSION['reset_email'] = $email;
                $_SESSION['reset_code'] = $code;
                $_SESSION['reset_time'] = time();

                $test_code = $code; // Test rejimi uchun ekranda ko'rsatamiz
                $step = 2;
                flash('success', 'Tasdiqlash kodi email manzilingizga yuborildi (Simulyatsiya).');
            } else {
                flash('error', 'Ushbu email bilan ro‘yxatdan o‘tgan foydalanuvchi topilmadi.');
            }
        }
    } elseif ($action === 'reset_password') {
        $email = $_SESSION['reset_email'] ?? '';
        $session_code = $_SESSION['reset_code'] ?? '';
        $session_time = $_SESSION['reset_time'] ?? 0;

        $input_code = trim($_POST['code'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        if (!$email || !$session_code) {
            flash('error', 'Sessiya muddati tugagan yoki xatolik. Qaytadan urinib ko‘ring.');
            $step = 1;
        } elseif (time() - $session_time > 600) { // 10 daqiqa
            flash('error', 'Tasdiqlash kodining muddati tugagan.');
            unset($_SESSION['reset_email'], $_SESSION['reset_code'], $_SESSION['reset_time']);
            $step = 1;
        } elseif ($input_code !== $session_code) {
            flash('error', 'Tasdiqlash kodi noto‘g‘ri.');
            $step = 2;
        } elseif (strlen($password) < 6) {
            flash('error', 'Yangi parol kamida 6 belgidan iborat bo‘lishi kerak.');
            $step = 2;
        } elseif ($password !== $password_confirm) {
            flash('error', 'Parollar mos kelmadi.');
            $step = 2;
        } else {
            // Parolni yangilash
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE email = ?');
            $stmt->execute([$hash, $email]);

            unset($_SESSION['reset_email'], $_SESSION['reset_code'], $_SESSION['reset_time']);
            flash('success', 'Parolingiz muvaffaqiyatli o‘zgartirildi. Yangi parol bilan tizimga kiring.');
            redirect('public/login.php');
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
  <div class="col-lg-10">
    <div class="row g-0 shadow-sm rounded overflow-hidden">
      
      <div class="col-md-6 p-4 p-md-5 bg-white order-2 order-md-1">
        <h4 class="mb-3"><i class="fa-solid fa-key me-1"></i>Parolni tiklash</h4>
        
        <?php if ($step === 1): ?>
          <p class="text-muted small">Tizimga kirish parolini tiklash uchun ro‘yxatdan o‘tgan email manzilingizni kiriting. Biz sizga vaqtinchalik tasdiqlash kodini jo‘natamiz.</p>
          
          <form method="post" class="needs-validation" novalidate>
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="request_code">
            <div class="mb-3">
              <label class="form-label">Email manzil</label>
              <input type="email" name="email" class="form-control" placeholder="email@example.com" value="<?= e($email) ?>" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">Tasdiqlash kodini olish</button>
          </form>
          
        <?php else: ?>
          <?php if (!empty($test_code)): ?>
            <div class="alert alert-warning mb-3">
              <i class="fa-solid fa-flask me-1"></i> <strong>Test rejimi:</strong> Tasdiqlash kodi: <code><?= e($test_code) ?></code>
            </div>
          <?php endif; ?>
          
          <p class="text-muted small">Sizning <code><?= e($_SESSION['reset_email'] ?? '') ?></code> emailingizga yuborilgan 6 xonali tasdiqlash kodini kiriting va yangi parolni belgilang.</p>
          
          <form method="post" class="needs-validation" novalidate>
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="reset_password">
            
            <div class="mb-3">
              <label class="form-label">Tasdiqlash kodi</label>
              <input type="text" name="code" class="form-control text-center fw-semibold fs-5" placeholder="123456" maxlength="6" required>
            </div>
            
            <div class="mb-3">
              <label class="form-label">Yangi parol</label>
              <input type="password" name="password" class="form-control" placeholder="Kamida 6 ta belgi" required>
            </div>
            
            <div class="mb-3">
              <label class="form-label">Yangi parolni takrorlang</label>
              <input type="password" name="password_confirm" class="form-control" placeholder="Kamida 6 ta belgi" required>
            </div>
            
            <button class="btn btn-success w-100" type="submit">Parolni yangilash</button>
          </form>
        <?php endif; ?>
        
        <div class="mt-3 text-center">
          <a href="<?= e(rtrim(BASE_URL,'/')) ?>/public/login.php" class="small"><i class="fa-solid fa-arrow-left me-1"></i>Kirish sahifasiga qaytish</a>
        </div>
      </div>
      
      <div class="col-md-6 p-0 order-1 order-md-2">
        <div class="cover" style="min-height: 340px; height: 100%">
          <img src="<?= e($page_img) ?>" alt="Uzbekistan – tarixiy obida">
          <div class="cover-overlay"></div>
          <div class="cover-caption">
            <div class="fw-semibold">Parolni tiklash xizmati</div>
            <div class="small">Xavfsizlik qoidalariga rioya qilgan holda parolingizni tezda tiklang.</div>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
