<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/header.php';
?>
<?php $page_img = asset_url('assets/page-faq.png', 'https://images.unsplash.com/photo-1544989164-31dc3c645987?q=80&w=1600&auto=format&fit=crop'); ?>
<div class="cover mb-3" style="height: 180px;">
  <img src="<?= e($page_img) ?>" alt="Uzbekistan – tarixiy obida">
  <div class="cover-overlay"></div>
  <div class="cover-caption">
    <div class="h6 fw-semibold"><?= e(lang('faq.hero.title', "Tez-tez so'raladigan savollar (FAQ)")) ?></div>
    <div class="small"><?= e(lang('faq.hero.subtitle', "Yordam va yo'riqnomalar")) ?></div>
  </div>
</div>
<div class="row">
  <div class="col-lg-10 mx-auto">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title mb-3"><?= e(lang('faq.card.title', "Tez-tez so'raladigan savollar (FAQ)")) ?></h5>
        <div class="accordion" id="faq">
          <div class="accordion-item">
            <h2 class="accordion-header" id="q1"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#a1"><?= e(lang('faq.q1', 'Qanday qilib murojaat yuboraman?')) ?></button></h2>
            <div id="a1" class="accordion-collapse collapse show" data-bs-parent="#faq"><div class="accordion-body"><?= e(lang('faq.a1', "Ro'yxatdan o'ting yoki tizimga kiring, keyin 'Yangi murojaat' menyusi orqali shaklni to'ldiring.")) ?></div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="q2"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a2"><?= e(lang('faq.q2', "Murojaat holatini qayerdan ko'raman?")) ?></button></h2>
            <div id="a2" class="accordion-collapse collapse" data-bs-parent="#faq"><div class="accordion-body"><?= e(lang('faq.a2', "Dashboard sahifasida hamda 'Mening murojaatlarim' bo'limida holat badge ko'rinishida ko'rsatiladi.")) ?></div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="q3"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a3"><?= e(lang('faq.q3', "Qaysi fayllarni yuklasam bo'ladi?")) ?></button></h2>
            <div id="a3" class="accordion-collapse collapse" data-bs-parent="#faq"><div class="accordion-body"><?= e(lang('faq.a3', 'PDF, JPG yoki PNG formatidagi fayllar ruxsat etiladi. Maksimal hajm 10MB.')) ?></div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
