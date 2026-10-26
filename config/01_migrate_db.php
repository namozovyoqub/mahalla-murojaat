<?php
declare(strict_types=1);
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../includes/helpers.php';

try {
    $pdo = getPDO();
    // applications jadvalida public_code ustuni borligini tekshirish
    $stmt = $pdo->query("DESCRIBE applications");
    $hasPublicCode = false;
    while ($row = $stmt->fetch()) {
        if ($row['Field'] === 'public_code') {
            $hasPublicCode = true;
            break;
        }
    }

    if (!$hasPublicCode) {
        echo "applications jadvaliga public_code ustuni qo'shilmoqda...\n";
        $pdo->exec("ALTER TABLE applications ADD COLUMN public_code CHAR(10) DEFAULT NULL");
        
        // Mavjud murojaatlarni kod bilan to'ldirish
        $rows = $pdo->query("SELECT id FROM applications WHERE public_code IS NULL")->fetchAll();
        foreach ($rows as $r) {
            $code = generate_public_code($pdo);
            $stmtUpdate = $pdo->prepare("UPDATE applications SET public_code = ? WHERE id = ?");
            $stmtUpdate->execute([$code, $r['id']]);
            echo "Murojaat ID {$r['id']} uchun yangi kod yaratildi: {$code}\n";
        }
        
        // Ustunni NOT NULL va UNIQUE qilish
        $pdo->exec("ALTER TABLE applications MODIFY COLUMN public_code CHAR(10) NOT NULL");
        $pdo->exec("ALTER TABLE applications ADD UNIQUE KEY uniq_app_public_code (public_code)");
        echo "public_code ustuni muvaffaqiyatli qo'shildi va to'ldirildi!\n";
    } else {
        echo "public_code ustuni allaqachon mavjud.\n";
    }
} catch (Exception $e) {
    echo "Migratsiya vaqtida xatolik: " . $e->getMessage() . "\n";
}
