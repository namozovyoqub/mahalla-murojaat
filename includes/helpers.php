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
    $clean = ltrim($relativePath, '/');
    $abs = dirname(__DIR__) . '/public/' . $clean;
    if (is_file($abs)) {
        $isVercel = !empty(getenv('VERCEL')) || !empty(getenv('AWS_LAMBDA_FUNCTION_NAME'));
        if ($isVercel || BASE_URL === '') {
            $url = '/' . $clean;
        } else {
            $url = rtrim(BASE_URL, '/') . '/public/' . $clean;
        }
        $ver = @filemtime($abs) ?: time();
        return $url . '?v=' . $ver;
    }
    return $fallbackUrl;
}

function news_cover_url(?string $url): string {
    if (!$url) {
        return asset_url('assets/news1.png');
    }
    if (str_contains($url, 'photo-1520975922284') || str_contains($url, 'constitution')) {
        return asset_url('assets/news-constitution.jpg');
    }
    if (str_contains($url, 'photo-1542037104857') || str_contains($url, 'budget')) {
        return asset_url('assets/news-open-budget.jpg');
    }
    if (str_contains($url, 'photo-1517502884422') || str_contains($url, 'raqamli')) {
        return asset_url('assets/news-raqamli.png');
    }
    if (preg_match('#^https?://#i', $url)) {
        return $url;
    }
    return asset_url($url, asset_url('assets/news1.png'));
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
        case 'Ko‘rib chiqilmoqda':
        case "Ko'rib chiqilmoqda": return 'warning';
        case 'Hal qilindi': return 'success';
        case 'Rad etildi': return 'danger';
        default: return 'secondary';
    }
}

function status_label(?string $status): string {
    if (!$status) return '-';
    switch ($status) {
        case 'Yangi': return lang('status.yangi', 'Yangi');
        case 'Qabul qilindi': return lang('status.qabul', 'Qabul qilindi');
        case 'Ko‘rib chiqilmoqda':
        case "Ko'rib chiqilmoqda": return lang('status.korib', 'Ko‘rib chiqilmoqda');
        case 'Hal qilindi': return lang('status.hal', 'Hal qilindi');
        case 'Rad etildi': return lang('status.rad', 'Rad etildi');
        default: return $status;
    }
}

function statuses(): array {
    return ['Yangi','Qabul qilindi','Ko‘rib chiqilmoqda','Hal qilindi','Rad etildi'];
}

function app_types(): array {
    return [
        'ariza' => lang('type.ariza', 'Ariza'),
        'shikoyat' => lang('type.shikoyat', 'Shikoyat'),
        'taklif' => lang('type.taklif', 'Taklif'),
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

