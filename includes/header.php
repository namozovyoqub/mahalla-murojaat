<?php
// includes/header.php

declare(strict_types=1);
// Global background image (local asset fallback to Uzbekistan tarixiy obida)
$__bg_image = asset_url('assets/bg.jpg', 'https://images.unsplash.com/photo-1603451952566-9b6a26c6f1d0?q=80&w=1920&auto=format&fit=crop');
?>
<!doctype html>
<html lang="<?= e($_SESSION['lang'] ?? 'uz') ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php if (!isset($__meta_desc)) { $__meta_desc = lang('footer.about_text', "Mahalla onlayn murojaat tizimi – fuqarolarning ariza, shikoyat va takliflarini tez va qulay qabul qilish platformasi."); } ?>
  <?php $__og_image = $__bg_image; ?>
  <?php $__favicon = asset_url('assets/favicon.png', ''); ?>
  <?php $__canonical = rtrim(BASE_URL,'/') . (string)($_SERVER['REQUEST_URI'] ?? ''); ?>
  <?php $___lang = $_SESSION['lang'] ?? 'uz'; $___og_locale = $___lang === 'ru' ? 'ru_RU' : 'uz_UZ'; ?>
  <?php if (!isset($__page_title)) {
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    $base = basename($script);
    $dir  = basename(dirname($script));
    $mapPublic = [
      'index.php' => lang('nav.home','Bosh sahifa'),
      'login.php' => lang('nav.login','Kirish'),
      'register.php' => lang('nav.register',"Ro'yxatdan o'tish"),
      'dashboard.php' => lang('nav.dashboard','Kabinet'),
      'create_application.php' => lang('nav.new_application','Yangi murojaat'),
      'applications.php' => lang('nav.my_applications','Mening murojaatlarim'),
      'news.php' => lang('nav.news','Yangiliklar'),
      'about.php' => lang('nav.about','Haqida'),
      'contact.php' => lang('nav.contact','Aloqa'),
      'faq.php' => lang('nav.faq','FAQ'),
      'track.php' => lang('nav.track','Holat tekshirish'),
    ];
    $mapAdmin = [
      'dashboard.php' => lang('admin.dash.title', 'Admin'),
      'applications.php' => lang('nav.admin.applications','Murojaatlar'),
      'users.php' => lang('nav.admin.users','Foydalanuvchilar'),
      'login.php' => lang('login.admin', 'Admin kirish'),
      'application_view.php' => lang('nav.admin.applications','Murojaatlar'),
    ];
    $__page_title = ($dir === 'admin') ? ($mapAdmin[$base] ?? lang('admin.dash.title', 'Admin')) : ($mapPublic[$base] ?? null);
  } ?>
  <?php $___title = isset($__page_title) ? ($__page_title . ' — ' . APP_NAME) : APP_NAME; ?>
  <title><?= e($___title) ?></title>
  <meta name="description" content="<?= e($__meta_desc) ?>">
  <meta property="og:title" content="<?= e($___title) ?>">
  <meta property="og:description" content="<?= e($__meta_desc) ?>">
  <meta property="og:image" content="<?= e($__og_image) ?>">
  <meta property="og:type" content="website">
  <meta property="og:locale" content="<?= e($___og_locale) ?>">
  <meta property="og:url" content="<?= e($__canonical) ?>">
  <?php if ($__favicon): ?>
    <link rel="icon" type="image/png" href="<?= e($__favicon) ?>">
  <?php endif; ?>
  <link rel="canonical" href="<?= e($__canonical) ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <meta name="theme-color" content="#56678f">
  <meta name="robots" content="index,follow">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= e($___title) ?>">
  <meta name="twitter:description" content="<?= e($__meta_desc) ?>">
  <meta name="twitter:image" content="<?= e($__og_image) ?>">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
  <script>
    // Anti-flash dark mode logic
    (function() {
      const theme = localStorage.getItem('theme');
      if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark-mode');
      }
    })();
  </script>
  <style>
    :root {
      /* Online-mahalla palitrasiga moslashtirilgan ranglar */
      --bs-primary: #3b82f6; /* Yorqinroq ko'k */
      --bs-primary-rgb: 59, 130, 246;
      --bs-body-bg: #f3f4f6;
    }
    
    /* Smooth theme transitions */
    body, .card, footer, nav, .navbar, .form-control, .form-select, .list-group-item, .alert, .bg-light, .bg-white {
      transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease !important;
    }

    /* Dark Mode Styling Override */
    .dark-mode {
      --bs-body-bg: #0f172a;
      --bs-body-color: #f8fafc;
      --bs-secondary-bg: #1e293b;
      --bs-border-color: #334155;
    }
    .dark-mode body {
      background-color: #0f172a !important;
      color: #f8fafc !important;
    }
    .dark-mode .bg-white {
      background-color: #1e293b !important;
    }
    .dark-mode .bg-light {
      background-color: #0f172a !important;
    }
    .dark-mode .bg-light.border-bottom {
      background-color: #1e293b !important;
      border-color: #334155 !important;
    }
    .dark-mode .text-dark {
      color: #f8fafc !important;
    }
    .dark-mode .text-muted {
      color: #94a3b8 !important;
    }
    .dark-mode .card {
      background-color: #1e293b !important;
      border: 1px solid #334155 !important;
      color: #f8fafc !important;
    }
    .dark-mode .list-group-item {
      background-color: #1e293b !important;
      color: #f8fafc !important;
      border-color: #334155 !important;
    }
    .dark-mode .form-control,
    .dark-mode .form-select {
      background-color: #1e293b !important;
      border-color: #475569 !important;
      color: #f8fafc !important;
    }
    .dark-mode .form-control::placeholder {
      color: #64748b !important;
    }
    .dark-mode .form-control:focus,
    .dark-mode .form-select:focus {
      background-color: #1e293b !important;
      color: #f8fafc !important;
      border-color: #3b82f6 !important;
      box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
    }
    .dark-mode footer {
      background-color: #1e293b !important;
      border-color: #334155 !important;
      color: #cbd5e1 !important;
    }
    .dark-mode footer a {
      color: #60a5fa !important;
    }
    .dark-mode footer .text-muted {
      color: #94a3b8 !important;
    }
    .dark-mode .border-bottom,
    .dark-mode .border-top {
      border-color: #334155 !important;
    }
    .dark-mode .alert-secondary {
      background-color: #334155 !important;
      color: #f8fafc !important;
      border-color: #475569 !important;
    }
    .dark-mode .preloader {
      background-color: #0f172a !important;
      color: #f8fafc !important;
    }
    .dark-mode .spinner-border {
      color: #60a5fa !important;
    }
    .dark-mode .section-title {
      color: #f8fafc !important;
    }
    .dark-mode .p-2.border.rounded.bg-light {
      background-color: #334155 !important;
      border-color: #475569 !important;
    }
    .dark-mode .navbar {
      background-color: #1e293b !important;
      border-bottom: 1px solid #334155 !important;
    }
    .dark-mode .navbar-nav .nav-link {
      color: rgba(255, 255, 255, 0.75) !important;
    }
    .dark-mode .navbar-nav .nav-link:hover {
      color: #fff !important;
    }
    .dark-mode .btn-outline-primary {
      color: #60a5fa !important;
      border-color: #60a5fa !important;
    }
    .dark-mode .btn-outline-primary:hover {
      background-color: #60a5fa !important;
      color: #1e293b !important;
    }
    .dark-mode .btn-outline-secondary {
      color: #cbd5e1 !important;
      border-color: #475569 !important;
    }
    .dark-mode .btn-outline-secondary:hover {
      background-color: #475569 !important;
      color: #f8fafc !important;
    }
    .dark-mode .text-bg-secondary {
      background-color: #475569 !important;
    }
    .dark-mode .text-bg-warning {
      background-color: #d97706 !important;
      color: #fff !important;
    }
    
    #theme-toggle {
      transition: transform 0.4s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    #theme-toggle:hover {
      transform: scale(1.2) rotate(25deg);
    }
    
    body { 
      background-color: var(--bs-body-bg);
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      position: relative;
      min-height: 100vh;
    }
    /* Side Glow Effects */
    body::before {
      content: "";
      position: fixed;
      top: 0; left: 0; bottom: 0; width: 15%;
      background: radial-gradient(ellipse at left center, rgba(59, 130, 246, 0.15), transparent 70%);
      pointer-events: none; z-index: -1;
    }
    body::after {
      content: "";
      position: fixed;
      top: 0; right: 0; bottom: 0; width: 15%;
      background: radial-gradient(ellipse at right center, rgba(59, 130, 246, 0.15), transparent 70%);
      pointer-events: none; z-index: -1;
    }
    
    .navbar { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
    .navbar-brand { font-weight: 700; letter-spacing: -0.025em; }
    
    .card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
      background: #fff;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .card-hover:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
    }
    
    .hero { background: transparent; border: none; }
    .section-title { font-weight: 800; color: #1f2937; margin-bottom: 1.5rem; letter-spacing: -0.025em; }

    .cover { position: relative; overflow: hidden; border-radius: 1rem; }
    .cover img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .cover-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.6), transparent); }
    .cover-caption { position: absolute; bottom: 0; left: 0; padding: 1.5rem; color: #fff; width: 100%; text-shadow: 0 2px 4px rgba(0,0,0,0.3); }

    .preloader { position: fixed; inset: 0; background: #fff; display: flex; align-items: center; justify-content: center; z-index: 2000; }
    .preloader.hidden { display: none; }
    
    /* Utility for clamps */
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
  </style>
</head>
<body>
<div id="preloader" class="preloader">
  <div class="text-center">
    <div class="spinner-border text-primary" role="status"></div>
    <div class="mt-3 fw-semibold"><?= e(lang('common.loading','Yuklanmoqda...')) ?></div>
  </div>
  </div>
<div class="bg-light border-bottom py-1 small">
  <div class="container-xl d-flex justify-content-between align-items-center">
    <div>
      <i class="fa-solid fa-phone me-1 text-primary"></i> +998334064444
      <span class="mx-2">|</span>
      <i class="fa-solid fa-envelope me-1 text-primary"></i> yoqub707@gmail.com
    </div>
    <div class="d-flex align-items-center gap-3">
      <div class="d-flex align-items-center gap-2">
        <?php
          $qUz = $_GET; $qUz['lang'] = 'uz';
          $qRu = $_GET; $qRu['lang'] = 'ru';
        ?>
        <a href="?<?= e(http_build_query($qUz)) ?>" class="text-decoration-none <?= (($_SESSION['lang'] ?? 'uz') === 'uz') ? 'fw-bold' : '' ?>">UZ</a>
        <span class="text-muted">/</span>
        <a href="?<?= e(http_build_query($qRu)) ?>" class="text-decoration-none <?= (($_SESSION['lang'] ?? 'uz') === 'ru') ? 'fw-bold' : '' ?>">RU</a>
      </div>
      <span class="text-muted">|</span>
      <button id="theme-toggle" class="btn btn-link p-0 text-decoration-none text-primary" style="font-size: 1.1rem; border: none; outline: none; box-shadow: none;" aria-label="Toggle Theme">
        <i class="fa-solid fa-moon"></i>
      </button>
    </div>
  </div>
</div>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-xl">
    <a class="navbar-brand" href="<?= e(rtrim(BASE_URL,'/')) ?>/<?= admin_logged_in() ? 'admin/dashboard.php' : (user_logged_in() ? 'public/dashboard.php' : 'public/index.php') ?>">
      <i class="fa-solid fa-people-roof me-2"></i><?= e(APP_NAME) ?>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <?php if (admin_logged_in()): ?>
          <li class="nav-item"><a class="nav-link" href="<?= e(rtrim(BASE_URL,'/')) ?>/admin/dashboard.php"><?= e(lang('nav.home','Bosh sahifa')) ?></a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(rtrim(BASE_URL,'/')) ?>/admin/applications.php"><?= e(lang('nav.admin.applications','Murojaatlar')) ?></a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(rtrim(BASE_URL,'/')) ?>/admin/news.php"><?= e(lang('nav.admin.news','Yangiliklar')) ?></a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(rtrim(BASE_URL,'/')) ?>/admin/users.php"><?= e(lang('nav.admin.users','Foydalanuvchilar')) ?></a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(rtrim(BASE_URL,'/')) ?>/admin/logout.php"><?= e(lang('nav.logout','Chiqish')) ?></a></li>
        <?php elseif (user_logged_in()): ?>
          <li class="nav-item"><a class="nav-link" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/index.php"><?= e(lang('nav.home','Bosh sahifa')) ?></a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/news.php"><?= e(lang('nav.news','Yangiliklar')) ?></a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/dashboard.php"><?= e(lang('nav.dashboard','Dashboard')) ?></a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/create_application.php"><?= e(lang('nav.new_application','Yangi murojaat')) ?></a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/applications.php"><?= e(lang('nav.my_applications','Mening murojaatlarim')) ?></a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/logout.php"><?= e(lang('nav.logout','Chiqish')) ?></a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/index.php"><?= e(lang('nav.home','Bosh sahifa')) ?></a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/about.php"><?= e(lang('nav.about','Haqida')) ?></a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/news.php"><?= e(lang('nav.news','Yangiliklar')) ?></a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/faq.php"><?= e(lang('nav.faq','FAQ')) ?></a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/track.php"><?= e(lang('nav.track','Holat tekshirish')) ?></a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/contact.php"><?= e(lang('nav.contact','Aloqa')) ?></a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/login.php"><?= e(lang('nav.login','Kirish')) ?></a></li>
          <li class="nav-item"><a class="nav-link" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/register.php"><?= e(lang('nav.register',"Ro'yxatdan o'tish")) ?></a></li>
          <li class="nav-item ms-lg-2"><a class="btn btn-light btn-sm" href="<?= e(rtrim(BASE_URL,'/')) ?>/public/register.php"><i class="fa-solid fa-paper-plane me-1"></i><?= e(lang('cta.apply','Murojaat yuborish')) ?></a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<main class="container-xl py-4">
  <?php if ($m = flash('success')): ?>
    <div class="alert alert-success"><i class="fa-solid fa-circle-check me-1"></i><?= e($m) ?></div>
  <?php endif; ?>
  <?php if ($m = flash('error')): ?>
    <div class="alert alert-danger"><i class="fa-solid fa-triangle-exclamation me-1"></i><?= e($m) ?></div>
  <?php endif; ?>
