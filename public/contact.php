<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/header.php';
?>
<?php $page_img = asset_url('assets/page-contact.jpg', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=1600&auto=format&fit=crop'); ?>
<div class="cover mb-3" style="height: 180px;">
  <img src="<?= e($page_img) ?>" alt="Uzbekistan – tarixiy obida">
  <div class="cover-overlay"></div>
  <div class="cover-caption">
    <div class="h6 fw-semibold"><?= e(lang('contact.hero.title', 'Aloqa')) ?></div>
    <div class="small"><?= e(lang('contact.hero.subtitle', 'Savol va takliflar uchun')) ?></div>
  </div>
</div>
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title"><i class="fa-solid fa-headset me-1"></i><?= e(lang('contact.card.title', 'Aloqa')) ?></h5>
        <p class="text-muted"><?= e(lang('contact.card.text', 'Savollar va takliflar uchun quydagi manzillarga murojaat qiling.')) ?></p>
        <ul class="list-unstyled">
          <li class="mb-2"><i class="fa-solid fa-envelope me-2 text-primary"></i><?= e(lang('contact.email', 'Email')) ?>: <span class="text-muted">yoqub707@gmail.com</span></li>
          <li class="mb-2"><i class="fa-solid fa-phone me-2 text-primary"></i><?= e(lang('contact.phone', 'Telefon')) ?>: <span class="text-muted">+998334064444</span></li>
          <li class="mb-2"><i class="fa-solid fa-location-dot me-2 text-primary"></i><?= e(lang('contact.address', 'Manzil')) ?>: <span class="text-muted">Toshkent, O'zbekiston</span></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
