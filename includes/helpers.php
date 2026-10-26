<?php
// includes/helpers.php
// Yordamchi funksiyalar va umumiy utilitlar

declare(strict_types=1);

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function is_post(): bool {
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function redirect(string $relativePath): void {
    $url = rtrim(BASE_URL, '/').'/'.ltrim($relativePath, '/');
    header('Location: '.$url);
    exit;
}

function flash(string $key, ?string $message = null): ?string {
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }
    if (isset($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}

function format_datetime(?string $ts): string {
    if (!$ts) return '';
    return date('Y-m-d H:i', strtotime($ts));
}

function ensure_upload_dir(): void {
    if (!is_dir(UPLOAD_DIR)) {
        @mkdir(UPLOAD_DIR, 0775, true);
    }
}

function asset_url(string $relativePath, string $fallbackUrl = ''): string {
    $abs = dirname(__DIR__) . '/public/' . ltrim($relativePath, '/');
    if (is_file($abs)) {
        $url = rtrim(BASE_URL, '/') . '/public/' . ltrim($relativePath, '/');
        $ver = @filemtime($abs) ?: time();
        return $url . '?v=' . $ver;
    }
    return $fallbackUrl;
}

function lang(string $key, ?string $default = null): string {
    $map = $GLOBALS['__i18n'] ?? [];
    return (string)($map[$key] ?? ($default ?? $key));
}

function generate_public_code(PDO $pdo): string {
    $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $len = strlen($alphabet);
    for ($attempt=0; $attempt<5; $attempt++) {
        $code = '';
        for ($i=0; $i<10; $i++) {
            $code .= $alphabet[random_int(0, $len-1)];
        }
        try {
            $stmt = $pdo->prepare('SELECT 1 FROM applications WHERE public_code = ? LIMIT 1');
            $stmt->execute([$code]);
            if (!$stmt->fetchColumn()) {
                return $code;
            }
        } catch (Throwable $e) {
            return $code; // Jadval hali yaratilmagan bo'lishi mumkin
        }
    }
    return strtoupper(bin2hex(random_bytes(5))); // zaxira varianti
}

function status_badge_class(string $status): string {
    switch ($status) {
        case 'Yangi': return 'secondary';
        case 'Qabul qilindi': return 'primary';
        case 'Ko‘rib chiqilmoqda': return 'warning';
        case 'Hal qilindi': return 'success';
        case 'Rad etildi': return 'danger';
        default: return 'secondary';
    }
}

function statuses(): array {
    return ['Yangi','Qabul qilindi','Ko‘rib chiqilmoqda','Hal qilindi','Rad etildi'];
}

function app_types(): array {
    return [
        'ariza' => 'Ariza',
        'shikoyat' => 'Shikoyat',
        'taklif' => 'Taklif',
    ];
}

function bukhara_mahallas(): array {
    return [
        'Nizomiy', 'M.Ashrafiy', 'J.Ikromiy', 'Shayxul-olam', 'Bihishtiyon',
        'G‘afur G‘ulom', 'Piridastgir', 'A.Temur', 'A.Donish', 'Furqat',
        'Zarafshon', 'Kukaldosh', 'Do‘stlik', 'A.Navoiy', 'S.Ayniy',
        'Avshormahalla', 'Havzi-nav', 'Gulchorbog‘', 'Yoshlik', 'I.Buxoriy',
        'Toshmait', 'X.Olimjon', 'Jo‘bor', 'Gulshan', 'Foshun',
        'M.Ulug‘bek', 'Navro‘z', 'Dilkusho', 'Turki Jandi', 'Shergiron',
        'Kuksaroy', 'S.Sheroziy', 'R.Xamroyev', 'Ibn Sino', 'M.Narshaxiy',
        'Namozgoh', 'Guliston', 'Shirbudin', 'Xo‘jamushkin', 'Istiqlol',
        'O.Xo‘jayev', 'Shodlik', 'Sifatmuniy', 'S.Boxarskiy', 'Chorbaxossa',
        'A.G‘ijduvoniy', 'Navbahor', 'Bunyodkor', 'Sitorai Mohi Hossa', 'Bog‘idasht',
        'Sheyxon', 'X.G‘unjoriy', 'F.Xo‘jayev', 'S.Raximov', 'Fitrat',
        'To‘qimachi', 'Oybek', 'Yangiobod', 'Sharq Yulduzi', 'M.Tarobiy',
        'B.Naqshband', 'M.Burxonov', 'Losha', 'Otbozor', 'Varaxsha'
    ];
}

