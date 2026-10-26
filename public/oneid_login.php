<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';

if (user_logged_in()) {
    redirect('public/dashboard.php');
}

$page_img = asset_url('assets/auth.jpg', 'https://images.unsplash.com/photo-1517502884422-41eaead166d4?q=80&w=1600&auto=format&fit=crop');

if (is_post()) {
    $token = $_POST['csrf_token'] ?? '';
    if (!csrf_validate($token)) {
        flash('error', lang('msg.csrf_error', 'Xavfsizlik xatosi.'));
        redirect('public/oneid_login.php');
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'login_simulation') {
        $email = 'oneid_user@example.com';
        
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $user_id = (int)$user['id'];
        } else {
            // Create a mock OneID user
            $hash = password_hash('oneid123', PASSWORD_DEFAULT);
            $stmtInsert = $pdo->prepare('INSERT INTO users (full_name, phone, email, password, created_at) VALUES (?, ?, ?, ?, NOW())');
            $stmtInsert->execute(['OneID Foydalanuvchisi', '+998990000000', $email, $hash]);
            $user_id = (int)$pdo->lastInsertId();
        }

        login_user($user_id);
        flash('success', lang('msg.oneid_success', 'OneID orqali muvaffaqiyatli kirdingiz (Simulyatsiya).'));
        redirect('public/dashboard.php');
    }
}

require_once __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
  <div class="col-lg-10">
    <div class="row g-0 shadow-sm rounded overflow-hidden">
      
      <div class="col-md-6 p-4 p-md-5 bg-white order-2 order-md-1 d-flex flex-column justify-content-center text-center">
        <div class="mb-4">
          <i class="fa-solid fa-fingerprint text-primary" style="font-size: 4rem;"></i>
        </div>
        <h4 class="mb-3"><?= e(lang('oneid.title', 'Yagona identifikatsiya tizimi')) ?></h4>
        <p class="text-muted mb-4"><?= e(lang('oneid.subtitle', 'OneID orqali davlat xizmatlariga kirish (Simulyatsiya)')) ?></p>
        
        <form method="post">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="login_simulation">
          <button class="btn btn-primary w-100 py-3 fw-semibold mb-3" type="submit" style="font-size: 1.1rem;">
            <?= e(lang('oneid.btn', "Avtorizatsiyadan o'tish (Mock Login)")) ?>
          </button>
        </form>
        
        <div>
          <a href="<?= e(rtrim(BASE_URL,'/')) ?>/public/login.php" class="small text-secondary"><i class="fa-solid fa-arrow-left me-1"></i><?= e(lang('oneid.back', 'Ortga qaytish')) ?></a>
        </div>
      </div>
      
      <div class="col-md-6 p-0 order-1 order-md-2">
        <div class="cover" style="min-height: 340px; height: 100%">
          <img src="<?= e($page_img) ?>" alt="Uzbekistan – tarixiy obida">
          <div class="cover-overlay"></div>
          <div class="cover-caption">
            <div class="fw-semibold"><?= e(lang('oneid.hero_title', 'Xavfsiz va tezkor kirish')) ?></div>
            <div class="small"><?= e(lang('oneid.hero_subtitle', "OneID tizimi orqali ma'lumotlaringiz xavfsizligi kafolatlanadi.")) ?></div>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
