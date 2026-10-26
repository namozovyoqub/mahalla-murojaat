<?php
// includes/auth.php
// Foydalanuvchi va Admin autentifikatsiya yordamchilari

declare(strict_types=1);

function user_logged_in(): bool {
    return !empty($_SESSION['user_id']);
}

function admin_logged_in(): bool {
    return !empty($_SESSION['admin_id']);
}

function require_user(): void {
    if (!user_logged_in()) {
        flash('error', lang('msg.require_login','Avval tizimga kiring.'));
        redirect('public/login.php');
    }
}

function require_admin(): void {
    if (!admin_logged_in()) {
        flash('error', lang('msg.require_admin','Admin sifatida tizimga kiring.'));
        redirect('admin/login.php');
    }
}

function login_user(int $user_id): void {
    $_SESSION['user_id'] = $user_id;
    session_regenerate_id(true);
}

function logout_user(): void {
    unset($_SESSION['user_id']);
    session_regenerate_id(true);
}

function login_admin(int $admin_id): void {
    $_SESSION['admin_id'] = $admin_id;
    session_regenerate_id(true);
}

function logout_admin(): void {
    unset($_SESSION['admin_id']);
    session_regenerate_id(true);
}

function current_user(PDO $pdo): ?array {
    if (!user_logged_in()) return null;
    static $cache = null;
    if ($cache) return $cache;
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $cache = $stmt->fetch() ?: null;
    return $cache;
}

function current_admin(PDO $pdo): ?array {
    if (!admin_logged_in()) return null;
    static $cache = null;
    if ($cache) return $cache;
    $stmt = $pdo->prepare('SELECT * FROM admins WHERE id = ?');
    $stmt->execute([$_SESSION['admin_id']]);
    $cache = $stmt->fetch() ?: null;
    return $cache;
}
