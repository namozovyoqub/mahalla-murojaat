<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
logout_user();
flash('success', 'Tizimdan chiqdingiz.');
redirect('public/login.php');
