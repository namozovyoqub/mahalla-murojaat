<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
logout_admin();
flash('success', 'Admin tizimidan chiqdingiz.');
redirect('admin/login.php');
