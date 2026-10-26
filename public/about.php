<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/header.php';
 
$page_img = asset_url('assets/page-about.png', 'https://images.unsplash.com/photo-1517502884422-41eaead166d4?q=80&w=1600&auto=format&fit=crop');
?>
<div class="cover mb-3" style="height: 180px;">
  <img src="<?= e($page_img) ?>" alt="Uzbekistan – tarixiy obida">
  <div class="cover-overlay"></div>
  <div class="cover-caption">
    <div class="h6 fw-semibold"><?= e(lang('about.hero.title', 'Mahalla haqida')) ?></div>
    <div class="small"><?= e(lang('about.hero.subtitle', 'Platforma va maqsadlar')) ?></div>
  </div>
</div>
<div class="row">
  <div class="col-lg-8">
    <div class="card shadow-sm mb-3">
      <div class="card-body">
        <h5 class="card-title"><?= e(lang('about.card.title', 'Mahalla haqida')) ?></h5>
        <p><?= e(lang('about.card.text', 'Ushbu platforma fuqarolarga ariza, shikoyat va takliflarni onlayn tarzda yuborish, ularni kuzatish va javoblarni olish imkonini beradi.')) ?></p>
        <ul>
          <li><?= e(lang('about.list.1', 'Onlayn murojaat topshirish')) ?></li>
          <li><?= e(lang('about.list.2', 'Statusni real vaqt rejimida kuzatish')) ?></li>
          <li><?= e(lang('about.list.3', "Admin izohlarini ko'rish")) ?></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="fw-semibold mb-2"><i class="fa-solid fa-circle-info me-1"></i><?= e(lang('about.info.title', "Ma'lumot")) ?></div>
        <div class="text-muted small"><?= e(lang('about.info.text', "Platforma test rejimida ishlamoqda. Taklif va mulohazalaringizni aloqa bo'limi orqali yuborishingiz mumkin.")) ?></div>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
