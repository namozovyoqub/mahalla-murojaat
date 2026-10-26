<?php
// includes/csrf.php
// CSRF himoya funksiyalari

declare(strict_types=1);

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="'.e(csrf_token()).'">';
}

function csrf_validate(?string $token): bool {
    if (!$token || empty($_SESSION['csrf_token'])) {
        return false;
    }
    $ok = hash_equals($_SESSION['csrf_token'], $token);
    if ($ok) {
        // ixtiyoriy: tokenni aylantirish
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $ok;
}
