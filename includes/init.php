<?php
// includes/init.php
// Sessiya, konfiguratsiya, PDO va umumiy modullarni ulash

declare(strict_types=1);

// Vaqt zonasi
@date_default_timezone_set('Asia/Tashkent');

// Sessiya cookie sozlamalari
$cookieParams = session_get_cookie_params();
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => $cookieParams['domain'] ?? '',
    'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    'httponly' => true,
    'samesite' => 'Lax',
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Til almashtirish (?lang=uz|ru)
if (isset($_GET['lang'])) {
    $lang = strtolower((string)$_GET['lang']);
    if (in_array($lang, ['uz', 'ru'], true)) {
        $_SESSION['lang'] = $lang;
    }
}

// Ilova konstantalari (XAMPP va Vercel uchun avtomatik aniqlanadi)
if (!defined('BASE_URL')) {
    if (getenv('BASE_URL') !== false) {
        define('BASE_URL', rtrim(getenv('BASE_URL'), '/'));
    } else {
        $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        $base = '';
        if (preg_match('#^(/[^/]+)?/(public|admin|config|includes|api)#i', $script, $m)) {
            $base = $m[1] ?? '';
        } elseif (preg_match('#^/([^/]+)/#', $script, $m)) {
            $base = '/' . $m[1];
        }
        define('BASE_URL', rtrim($base, '/'));
    }
}
if (!defined('APP_NAME')) {
    define('APP_NAME', 'Mahalla onlayn murojaat tizimi');
}
if (!defined('UPLOAD_DIR')) {
    $isVercel = !empty(getenv('VERCEL')) || !empty(getenv('AWS_LAMBDA_FUNCTION_NAME'));
    $uploadPath = $isVercel ? (sys_get_temp_dir() . '/uploads') : (__DIR__ . '/../uploads');
    define('UPLOAD_DIR', $uploadPath);
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/csrf.php';
require_once __DIR__ . '/auth.php';

// I18n xarita yuklash (UZ default)
try {
    $langSel = $_SESSION['lang'] ?? 'uz';
    $i18nFile = __DIR__ . '/lang/' . $langSel . '.php';
    if (!is_file($i18nFile)) { $i18nFile = __DIR__ . '/lang/uz.php'; }
    if (is_file($i18nFile)) {
        $GLOBALS['__i18n'] = require $i18nFile;
    }
} catch (Throwable $e) { /* ignore */ }

// PDO ulanishi
$pdo = getPDO();

// Birinchi ishga tushirishda default admin yaratish
(function (PDO $pdo) {
    try {
        $exists = $pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn();
        if ((int)$exists === 0) {
            $stmt = $pdo->prepare('INSERT INTO admins (name, email, password, created_at) VALUES (?, ?, ?, NOW())');
            $hash = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt->execute(['Super Admin', 'admin@mahalla.local', $hash]);
        }
    } catch (Throwable $e) {
        // Ehtimol jadval hali yaratilmagan — jim qolamiz
    }
})($pdo);
