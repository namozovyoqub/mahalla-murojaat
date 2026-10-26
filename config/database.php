<?php
// config/database.php
// PDO ulanish konfiguratsiyasi (MySQL + SQLite serverless fallback)

declare(strict_types=1);

function getPDO(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $db_type = getenv('DB_TYPE') ?: 'auto';
    $db_host = getenv('DB_HOST') ?: '127.0.0.1';
    $db_port = getenv('DB_PORT') ?: '3306';
    $db_name = getenv('DB_NAME') ?: 'mahalla';
    $db_user = getenv('DB_USER') ?: 'root';
    $db_pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
    $db_charset = 'utf8mb4';

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    if ($db_type !== 'sqlite') {
        try {
            $dsn = "mysql:host={$db_host};port={$db_port};dbname={$db_name};charset={$db_charset}";
            $pdo = new PDO($dsn, $db_user, $db_pass, $options);
            _init_mysql_schema($pdo);
            return $pdo;
        } catch (Throwable $e) {
            // Agar MySQL ga ulanib bo'lmasa (masalan, Vercel serverless yoki MySQL yoqilmagan bo'lsa),
            // avtomatik ravishda SQLite ga o'tamiz
            if ($db_type === 'mysql') {
                throw $e;
            }
        }
    }

    // SQLite fallback rejimi
    $isVercel = !empty(getenv('VERCEL')) || !empty(getenv('AWS_LAMBDA_FUNCTION_NAME'));
    $sqliteDir = $isVercel ? sys_get_temp_dir() : (dirname(__DIR__) . '/uploads');
    if (!is_dir($sqliteDir)) {
        @mkdir($sqliteDir, 0775, true);
    }
    $sqliteFile = rtrim($sqliteDir, '/\\') . '/mahalla.sqlite';

    $dsn = "sqlite:{$sqliteFile}";
    $pdo = new PDO($dsn, null, null, $options);
    $pdo->sqliteCreateFunction('NOW', function() {
        return date('Y-m-d H:i:s');
    });
    $pdo->sqliteCreateFunction('DATABASE', function() {
        return 'mahalla';
    });
    $pdo->exec('PRAGMA foreign_keys = ON;');
    _init_sqlite_schema($pdo);

    return $pdo;
}

function _init_mysql_schema(PDO $pdo): void {
    try {
        $pdo->exec("
        CREATE TABLE IF NOT EXISTS `users` (
          `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
          `full_name` VARCHAR(255) NOT NULL,
          `phone` VARCHAR(32) NOT NULL,
          `email` VARCHAR(191) NOT NULL,
          `password` VARCHAR(255) NOT NULL,
          `mahalla` VARCHAR(255) DEFAULT NULL,
          `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          UNIQUE KEY `uniq_users_email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        CREATE TABLE IF NOT EXISTS `news` (
          `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
          `title` VARCHAR(255) NOT NULL,
          `title_ru` VARCHAR(255) DEFAULT NULL,
          `body` TEXT NOT NULL,
          `body_ru` TEXT DEFAULT NULL,
          `cover_url` VARCHAR(255) DEFAULT NULL,
          `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          KEY `idx_news_created` (`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        CREATE TABLE IF NOT EXISTS `admins` (
          `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
          `name` VARCHAR(100) NOT NULL,
          `email` VARCHAR(191) NOT NULL,
          `password` VARCHAR(255) NOT NULL,
          `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          UNIQUE KEY `uniq_admins_email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        CREATE TABLE IF NOT EXISTS `applications` (
          `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
          `user_id` INT UNSIGNED NOT NULL,
          `type` ENUM('ariza','shikoyat','taklif') NOT NULL,
          `title` VARCHAR(255) NOT NULL,
          `message` TEXT NOT NULL,
          `file_path` VARCHAR(255) DEFAULT NULL,
          `mahalla` VARCHAR(255) DEFAULT NULL,
          `public_code` CHAR(10) NOT NULL,
          `status` ENUM('Yangi','Qabul qilindi','Ko‘rib chiqilmoqda','Hal qilindi','Rad etildi') NOT NULL DEFAULT 'Yangi',
          `admin_comment` TEXT DEFAULT NULL,
          `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          KEY `idx_app_user` (`user_id`),
          KEY `idx_app_status` (`status`),
          KEY `idx_app_type` (`type`),
          KEY `idx_app_created` (`created_at`),
          UNIQUE KEY `uniq_app_public_code` (`public_code`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        CREATE TABLE IF NOT EXISTS `application_logs` (
          `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
          `application_id` INT UNSIGNED NOT NULL,
          `admin_id` INT UNSIGNED DEFAULT NULL,
          `prev_status` VARCHAR(64) DEFAULT NULL,
          `new_status` VARCHAR(64) DEFAULT NULL,
          `comment` TEXT DEFAULT NULL,
          `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          KEY `idx_log_app` (`application_id`),
          KEY `idx_log_created` (`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // Mavjud jadvallarga yetishmayotgan ustunlarni qo'shish
        try {
            $cols = $pdo->query("SHOW COLUMNS FROM news LIKE 'title_ru'")->fetch();
            if (!$cols) {
                $pdo->exec("ALTER TABLE news ADD COLUMN title_ru VARCHAR(255) DEFAULT NULL");
                $pdo->exec("ALTER TABLE news ADD COLUMN body_ru TEXT DEFAULT NULL");
            }
        } catch (Throwable $e) {}

        try {
            $cols = $pdo->query("SHOW COLUMNS FROM users LIKE 'mahalla'")->fetch();
            if (!$cols) {
                $pdo->exec("ALTER TABLE users ADD COLUMN mahalla VARCHAR(255) DEFAULT NULL");
            }
        } catch (Throwable $e) {}

        try {
            $cols = $pdo->query("SHOW COLUMNS FROM applications LIKE 'public_code'")->fetch();
            if (!$cols) {
                $pdo->exec("ALTER TABLE applications ADD COLUMN public_code CHAR(10) DEFAULT NULL");
            }
        } catch (Throwable $e) {}

        _seed_default_data($pdo);
    } catch (Throwable $e) {}
}

function _init_sqlite_schema(PDO $pdo): void {
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      full_name TEXT NOT NULL,
      phone TEXT NOT NULL,
      email TEXT NOT NULL UNIQUE,
      password TEXT NOT NULL,
      mahalla TEXT DEFAULT NULL,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS news (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      title TEXT NOT NULL,
      title_ru TEXT DEFAULT NULL,
      body TEXT NOT NULL,
      body_ru TEXT DEFAULT NULL,
      cover_url TEXT DEFAULT NULL,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS admins (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      email TEXT NOT NULL UNIQUE,
      password TEXT NOT NULL,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS applications (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      user_id INTEGER NOT NULL,
      type TEXT NOT NULL,
      title TEXT NOT NULL,
      message TEXT NOT NULL,
      file_path TEXT DEFAULT NULL,
      mahalla TEXT DEFAULT NULL,
      public_code TEXT UNIQUE,
      status TEXT NOT NULL DEFAULT 'Yangi',
      admin_comment TEXT DEFAULT NULL,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    );

    CREATE TABLE IF NOT EXISTS application_logs (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      application_id INTEGER NOT NULL,
      admin_id INTEGER DEFAULT NULL,
      prev_status TEXT DEFAULT NULL,
      new_status TEXT DEFAULT NULL,
      comment TEXT DEFAULT NULL,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
    );
    ");

    _seed_default_data($pdo);
}

function _seed_default_data(PDO $pdo): void {
    try {
        $cnt = (int)$pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn();
        if ($cnt === 0) {
            $stmt = $pdo->prepare('INSERT INTO admins (name, email, password, created_at) VALUES (?, ?, ?, NOW())');
            $stmt->execute(['Super Admin', 'admin@mahalla.local', password_hash('admin123', PASSWORD_DEFAULT)]);
        }
    } catch (Throwable $e) {}

    try {
        // Avval mavjud yangiliklardagi noto'g'ri tashqi URL larni lokal rasmlarga to'g'irlaymiz
        $map = [
            'Konstitutsiyada mahalla maqomi mustahkamlandi' => 'assets/news-constitution.jpg',
            '"Mahalla budjeti" tizimi to‘liq joriy etildi' => 'assets/news-open-budget.jpg',
            '"Raqamli mahalla" platformasi yangilandi' => 'assets/news-raqamli.png'
        ];
        foreach ($map as $title => $cover) {
            $pdo->prepare("UPDATE news SET cover_url = ? WHERE title = ? AND (cover_url LIKE '%unsplash%' OR cover_url IS NULL OR cover_url = '')")->execute([$cover, $title]);
        }

        $cntNews = (int)$pdo->query('SELECT COUNT(*) FROM news')->fetchColumn();
        if ($cntNews === 0) {
            $stmt = $pdo->prepare('INSERT INTO news (title, body, cover_url, created_at) VALUES (?, ?, ?, NOW())');
            $stmt->execute([
                '"Mahalla budjeti" tizimi to‘liq joriy etildi',
                "2024-yil 1-yanvardan boshlab barcha tuman va shaharlarda 'Mahalla budjeti' tizimi ishga tushdi. Soliq tushumlarining bir qismi bevosita mahallalarda qoladi va infratuzilmani yaxshilashga sarflanadi.",
                'assets/news-open-budget.jpg'
            ]);
            $stmt->execute([
                'Konstitutsiyada mahalla maqomi mustahkamlandi',
                "Yangi tahrirdagi Konstitutsiyaning 127-moddasida mahalla fuqarolar yig‘inlari maqomi alohida belgilab qo‘yildi. Bu mahalla institutining jamiyatdagi rolini oshirishga xizmat qiladi.",
                'assets/news-constitution.jpg'
            ]);
            $stmt->execute([
                '"Raqamli mahalla" platformasi yangilandi',
                "Barcha ma'lumotlarni yagona bazada jamlash va aholiga onlayn xizmat ko‘rsatish sifatini oshirish maqsadida tizimga yangi qulayliklar qo‘shildi.",
                'assets/news-raqamli.png'
            ]);
        }
    } catch (Throwable $e) {}
}
