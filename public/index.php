<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/header.php';
$img1 = asset_url('assets/registan.png', 'https://images.unsplash.com/photo-1603451952566-9b6a26c6f1d0?q=80&w=1600&auto=format&fit=crop'); // Samarqand, Registon
$img2 = asset_url('assets/bukhara.png',  'https://images.unsplash.com/photo-1544989164-31dc3c645987?q=80&w=1600&auto=format&fit=crop'); // Buxoro
$img3 = asset_url('assets/khiva.png',    'https://images.unsplash.com/photo-1523731407965-2430cd12f5e4?q=80&w=1600&auto=format&fit=crop'); // Xiva
$cover = asset_url('assets/cover.png',   'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=1600&auto=format&fit=crop');
?>
<div id="heroCarousel" class="carousel slide mb-4" data-bs-ride="carousel" data-bs-interval="5000">
  <div class="carousel-inner rounded-3 overflow-hidden">
    <div class="carousel-item active">
      <div class="cover" style="height: 360px;">
        <img src="<?= e($img1) ?>" alt="Uzbekistan – tarixiy obida">
        <div class="cover-overlay"></div>
        <div class="cover-caption">
          <h2 class="h3 fw-bold mb-2"><?= e(lang('home.hero1.title','Onlayn murojaat – tez va qulay')) ?></h2>
          <p class="mb-3"><?= e(lang('home.hero1.subtitle','Ariza, shikoyat yoki takliflaringizni bir necha bosqichda yuboring.')) ?></p>
          <div class="d-flex gap-2">
            <?php if (user_logged_in()): ?>
              <a class="btn btn-success" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/create_application.php"><i class="fa-solid fa-paper-plane me-1"></i><?= e(lang('home.btn.send','Yuborish')) ?></a>
              <a class="btn btn-outline-light" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/applications.php"><?= e(lang('home.btn.my_apps','Mening murojaatlarim')) ?></a>
            <?php else: ?>
              <a class="btn btn-primary" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/register.php"><?= e(lang('home.btn.start','Boshlash')) ?></a>
              <a class="btn btn-outline-light" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/login.php"><?= e(lang('home.btn.login','Kirish')) ?></a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="carousel-item">
      <div class="cover" style="height: 360px;">
        <img src="<?= e($img2) ?>" alt="Uzbekistan – tarixiy obida">
        <div class="cover-overlay"></div>
        <div class="cover-caption">
          <h2 class="h3 fw-bold mb-2"><?= e(lang('home.hero2.title','Holatni kuzatish')) ?></h2>
          <p class="mb-3"><?= e(lang('home.hero2.subtitle','Murojaatingiz ko‘rib chiqilishi bosqichlarini real vaqtda kuzating.')) ?></p>
        </div>
      </div>
    </div>
    <div class="carousel-item">
      <div class="cover" style="height: 360px;">
        <img src="<?= e($img3) ?>" alt="Uzbekistan – tarixiy obida">
        <div class="cover-overlay"></div>
        <div class="cover-caption">
          <h2 class="h3 fw-bold mb-2"><?= e(lang('home.hero3.title','Xavfsiz va ishonchli')) ?></h2>
          <p class="mb-3"><?= e(lang('home.hero3.subtitle','Ma’lumotlaringiz himoya qilinadi. Fayllar xavfsiz saqlanadi.')) ?></p>
        </div>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden"><?= e(lang('carousel.prev','Oldingi')) ?></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden"><?= e(lang('carousel.next','Keyingi')) ?></span>
  </button>
</div>

<section class="my-4">
  <div class="section-title"><?= e(lang('home.quick_services','Tezkor xizmatlar')) ?></div>
  <div class="row g-3">
    <div class="col-6 col-sm-6 col-md-3">
      <a class="card h-100 shadow-sm text-center text-decoration-none card-hover" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/create_application.php">
        <div class="card-body">
          <div class="display-6 text-primary"><i class="fa-solid fa-paper-plane"></i></div>
          <div class="fw-semibold mt-2"><?= e(lang('home.card.apply','Murojaat yuborish')) ?></div>
        </div>
      </a>
    </div>
    <div class="col-6 col-md-3">
      <a class="card h-100 shadow-sm text-center text-decoration-none card-hover" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/applications.php">
        <div class="card-body">
          <div class="display-6 text-success"><i class="fa-solid fa-list-check"></i></div>
          <div class="fw-semibold mt-2"><?= e(lang('home.card.status',"Holatni ko'rish")) ?></div>
        </div>
      </a>
    </div>
    <div class="col-6 col-md-3">
      <a class="card h-100 shadow-sm text-center text-decoration-none card-hover" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/faq.php">
        <div class="card-body">
          <div class="display-6 text-warning"><i class="fa-solid fa-circle-question"></i></div>
          <div class="fw-semibold mt-2"><?= e(lang('home.card.faq','FAQ')) ?></div>
        </div>
      </a>
    </div>
    <div class="col-6 col-md-3">
      <a class="card h-100 shadow-sm text-center text-decoration-none card-hover" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/contact.php">
        <div class="card-body">
          <div class="display-6 text-danger"><i class="fa-solid fa-headset"></i></div>
          <div class="fw-semibold mt-2"><?= e(lang('home.card.support','Qo‘llab-quvvatlash')) ?></div>
        </div>
      </a>
    </div>
    <div class="col-6 col-md-3">
      <a class="card h-100 shadow-sm text-center text-decoration-none card-hover" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/register.php">
        <div class="card-body">
          <div class="display-6 text-info"><i class="fa-solid fa-user-plus"></i></div>
          <div class="fw-semibold mt-2"><?= e(lang('home.card.register',"Ro'yxatdan o'tish")) ?></div>
        </div>
      </a>
    </div>
    <div class="col-6 col-md-3">
      <a class="card h-100 shadow-sm text-center text-decoration-none card-hover" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/login.php">
        <div class="card-body">
          <div class="display-6 text-secondary"><i class="fa-solid fa-right-to-bracket"></i></div>
          <div class="fw-semibold mt-2"><?= e(lang('home.card.login','Kirish')) ?></div>
        </div>
      </a>
    </div>
    <div class="col-6 col-md-3">
      <a class="card h-100 shadow-sm text-center text-decoration-none card-hover" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/about.php">
        <div class="card-body">
          <div class="display-6 text-primary"><i class="fa-solid fa-circle-info"></i></div>
          <div class="fw-semibold mt-2"><?= e(lang('home.card.about','Platforma haqida')) ?></div>
        </div>
      </a>
    </div>
    <div class="col-6 col-md-3">
      <a class="card h-100 shadow-sm text-center text-decoration-none card-hover" href="#">
        <div class="card-body">
          <div class="display-6 text-success"><i class="fa-solid fa-shield-halved"></i></div>
          <div class="fw-semibold mt-2"><?= e(lang('home.card.security','Maʼlumotlar xavfsizligi')) ?></div>
        </div>
      </a>
    </div>
  </div>
  
  <div class="section-title mt-4"><?= e(lang('nav.news','Yangiliklar')) ?></div>
  <?php
    $lang = $_SESSION['lang'] ?? 'uz';
    try {
      $st = $pdo->query('SELECT id, title, title_ru, cover_url, created_at FROM news ORDER BY created_at DESC LIMIT 3');
      $homeNews = $st->fetchAll() ?: [];
    } catch (Throwable $e) { $homeNews = []; }
  ?>
  <?php if ($homeNews): ?>
    <div class="row g-3">
      <?php foreach ($homeNews as $n): ?>
        <?php 
          $img = news_cover_url($n['cover_url']); 
          $title = ($lang === 'ru' && !empty($n['title_ru'])) ? $n['title_ru'] : $n['title'];
        ?>
        <div class="col-12 col-md-6 col-lg-4">
          <a class="card shadow-sm h-100 card-hover text-decoration-none" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/news_view.php?id=<?= (int)$n['id'] ?>">
            <div class="cover" style="height: 160px;">
              <img src="<?= e($img) ?>" alt="Yangilik" class="img-fluid w-100 h-100 object-fit-cover">
              <div class="cover-overlay"></div>
            </div>
            <div class="card-body">
              <div class="fw-semibold line-clamp-2"><?= e($title) ?></div>
              <div class="text-muted small mt-1"><?= e(format_datetime($n['created_at'])) ?></div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="mt-2">
      <a class="btn btn-outline-primary btn-sm" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/news.php"><?= e(lang('common.all','Barchasi')) ?></a>
    </div>
  <?php else: ?>
    <div class="row g-3">
      <div class="col-md-4">
        <div class="card shadow-sm h-100 card-hover">
          <div class="cover" style="height: 160px;">
            <img src="<?= e(asset_url('assets/news1.png', 'https://images.unsplash.com/photo-1542037104857-ffbb0b9155fb?q=80&w=1200&auto=format&fit=crop')) ?>" alt="Yangilik" class="img-fluid w-100 h-100 object-fit-cover">
            <div class="cover-overlay"></div>
          </div>
          <div class="card-body">
            <div class="fw-semibold"><?= e(lang('news.fallback.1.title', 'Mahallada raqamli xizmatlar kengaymoqda')) ?></div>
            <div class="text-muted small mt-1"><?= e(lang('news.fallback.1.desc', 'E-structural islohotlar doirasida onlayn murojaatlar potentsiali oshmoqda. Bu orqali fuqarolarimiz o‘z arizalarini uydan chiqmagan holda, qisqa fursatlarda yuborish va natijasini bilish imkoniyatiga ega bo‘lmoqdalar. Shuningdek, jarayon shaffofligi ta’minlanadi.')) ?></div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm h-100 card-hover">
          <div class="cover" style="height: 160px;">
            <img src="<?= e(asset_url('assets/news2.png', 'https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=1200&auto=format&fit=crop')) ?>" alt="Yangilik" class="img-fluid w-100 h-100 object-fit-cover">
            <div class="cover-overlay"></div>
          </div>
          <div class="card-body">
            <div class="fw-semibold"><?= e(lang('news.fallback.2.title', 'Qulayliklar: murojaat holatini kuzatish')) ?></div>
            <div class="text-muted small mt-1"><?= e(lang('news.fallback.2.desc', 'Endi foydalanuvchilar har bir bosqichni onlayn kuzatishlari mumkin. Har bir holat o‘zgarishi xususida tizim xabar berib boradi va izoh qoldirish imkonini taqdim etadi.')) ?></div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm h-100 card-hover">
          <div class="cover" style="height: 160px;">
            <img src="<?= e(asset_url('assets/news3.jpg', 'https://images.unsplash.com/photo-1520975922284-4f50457f2874?q=80&w=1200&auto=format&fit=crop')) ?>" alt="Yangilik" class="img-fluid w-100 h-100 object-fit-cover">
            <div class="cover-overlay"></div>
          </div>
          <div class="card-body">
            <div class="fw-semibold"><?= e(lang('news.fallback.3.title', 'Xavfsizlikni yanada kuchaytirdik')) ?></div>
            <div class="text-muted small mt-1"><?= e(lang('news.fallback.3.desc', "Foydalanuvchi ma'lumotlarini himoyalash bo'yicha yangilanishlar joriy etildi. Eng zamonaviy shifrlash protokollari yordamida har bir ariza xavfsizligi kafolatlanadi.")) ?></div>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
  

</section>
<div class="row g-3 mb-3">
  <div class="col-6 col-lg-3">
    <div class="card shadow-sm card-hover h-100 text-center">
      <div class="card-body">
        <div class="display-6 text-primary"><i class="fa-solid fa-file-circle-check"></i></div>
        <div class="fw-semibold mt-2"><?= e(lang('home.feature.apply.title','Onlayn murojaat')) ?></div>
        <div class="text-muted small mb-2"><?= e(lang('home.feature.apply.desc','Tez va qulay topshirish')) ?></div>
        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#murojaatModal"><?= e(lang('common.more','Batafsil')) ?></button>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="card shadow-sm card-hover h-100 text-center">
      <div class="card-body">
        <div class="display-6 text-warning"><i class="fa-solid fa-signal"></i></div>
        <div class="fw-semibold mt-2"><?= e(lang('home.feature.status.title','Holat nazorati')) ?></div>
        <div class="text-muted small mb-2"><?= e(lang('home.feature.status.desc','Real vaqt yangilanishlari')) ?></div>
        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#holatModal"><?= e(lang('common.more','Batafsil')) ?></button>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="card shadow-sm card-hover h-100 text-center">
      <div class="card-body">
        <div class="display-6 text-success"><i class="fa-solid fa-comments"></i></div>
        <div class="fw-semibold mt-2"><?= e(lang('home.feature.contact.title','Aloqa')) ?></div>
        <div class="text-muted small mb-2"><?= e(lang('home.feature.contact.desc',"Admin izohlarini ko'ring")) ?></div>
        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#aloqaModal"><?= e(lang('common.more','Batafsil')) ?></button>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="card shadow-sm card-hover h-100 text-center">
      <div class="card-body">
        <div class="display-6 text-danger"><i class="fa-solid fa-shield"></i></div>
        <div class="fw-semibold mt-2"><?= e(lang('home.feature.secure.title','Xavfsiz')) ?></div>
        <div class="text-muted small mb-2"><?= e(lang('home.feature.secure.desc',"Ma'lumotlaringiz himoyalangan")) ?></div>
        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#xavfsizModal"><?= e(lang('common.more','Batafsil')) ?></button>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 align-items-stretch">
  <div class="col-md-4">
    <div class="card h-100 shadow-sm">
      <div class="card-body">
        <div class="section-title"><?= e(lang('home.how_it_works','Jarayon qanday?')) ?></div>
        <ol class="mb-0 small">
          <li><?= e(lang('home.how.step1',"Ro'yxatdan o'ting yoki tizimga kiring")) ?></li>
          <li><?= e(lang('home.how.step2',"Yangi murojaat yaratib, ma'lumotlarni kiriting")) ?></li>
          <li><?= e(lang('home.how.step3','Holatini kuzatib boring va javobni oling')) ?></li>
        </ol>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="cover" style="height: 260px;">
      <img src="<?= e($cover) ?>" alt="Uzbekistan – tarixiy obida">
      <div class="cover-overlay"></div>
      <div class="cover-caption">
        <div class="h5 fw-semibold mb-1"><?= e(lang('home.caption.title', 'Fuqarolar uchun qulay platforma')) ?></div>
        <div class="small"><?= e(lang('home.caption.desc', 'Shaxsiy kabinet orqali murojaatlar bilan ishlash ancha oson.')) ?></div>
      </div>
    </div>
  </div>
</div>

<?php
  $totApps = 0; $totSolved = 0; $totUsers = 0;
  try {
    $totApps = (int)$pdo->query('SELECT COUNT(*) FROM applications')->fetchColumn();
    $totSolved = (int)$pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'Hal qilindi'")->fetchColumn();
    $totUsers = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
  } catch (Throwable $e) { /* ignore */ }
?>
<div class="row g-3 my-3">
  <div class="col-12 col-sm-4">
    <div class="card shadow-sm text-center card-hover">
      <div class="card-body">
        <div class="text-muted small"><?= e(lang('home.total_apps','Jami murojaatlar')) ?></div>
        <div class="display-6 counter" data-target="<?= (int)$totApps ?>">0</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-4">
    <div class="card shadow-sm text-center card-hover">
      <div class="card-body">
        <div class="text-muted small"><?= e(lang('home.resolved','Hal qilingan')) ?></div>
        <div class="display-6 counter" data-target="<?= (int)$totSolved ?>">0</div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-4">
    <div class="card shadow-sm text-center card-hover">
      <div class="card-body">
        <div class="text-muted small"><?= e(lang('home.users','Foydalanuvchilar')) ?></div>
        <div class="display-6 counter" data-target="<?= (int)$totUsers ?>">0</div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="murojaatModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title"><?= e(lang('home.modal.apply.title','Onlayn murojaat')) ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body"><?= e(lang('home.modal.apply.body','Ariza, shikoyat va takliflarni onlayn tarzda yuborish imkoniyati sizga vaqtni tejashga yordam beradi.')) ?></div>
      <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal"><?= e(lang('home.modal.close','Yopish')) ?></button></div>
    </div>
  </div>
</div>
<div class="modal fade" id="holatModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title"><?= e(lang('home.modal.status.title','Holat nazorati')) ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body"><?= e(lang('home.modal.status.body','Murojaatingizning har bir bosqichi haqida xabardor bo‘lib boring.')) ?></div>
      <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal"><?= e(lang('home.modal.close','Yopish')) ?></button></div>
    </div>
  </div>
</div>
<div class="modal fade" id="aloqaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title"><?= e(lang('home.modal.contact.title','Aloqa')) ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body"><?= e(lang('home.modal.contact.body','Admin javoblari va qo‘shimcha izohlar bilan tanishing.')) ?></div>
      <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal"><?= e(lang('home.modal.close','Yopish')) ?></button></div>
    </div>
  </div>
</div>
<div class="modal fade" id="xavfsizModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title"><?= e(lang('home.modal.security.title','Xavfsiz')) ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body"><?= e(lang('home.modal.security.body','Ma’lumotlar xavfsizligi va maxfiyligi ta’minlangan.')) ?></div>
      <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal"><?= e(lang('home.modal.close','Yopish')) ?></button></div>
    </div>
  </div>
</div>

<script>
  (function(){
    const counters = document.querySelectorAll('.counter');
    const animate = el => {
      const target = parseInt(el.getAttribute('data-target')||'0',10);
      const duration = 900; let start = 0; const t0 = performance.now();
      const tick = (now)=>{
        const p = Math.min(1, (now - t0)/duration);
        const val = Math.floor(p * target);
        el.textContent = val.toString();
        if (p < 1) requestAnimationFrame(tick);
      };
      requestAnimationFrame(tick);
    };
    const io = new IntersectionObserver((entries)=>{
      entries.forEach(e=>{ if (e.isIntersecting){ animate(e.target); io.unobserve(e.target);} });
    }, {threshold: .6});
    counters.forEach(c=>io.observe(c));

    // Force carousel start
    var myCarousel = document.querySelector('#heroCarousel')
    var carousel = new bootstrap.Carousel(myCarousel, {
      interval: 5000,
      ride: 'carousel'
    });
  })();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
