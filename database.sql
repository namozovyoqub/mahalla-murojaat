-- Mahalla onlayn murojaat tizimi – MySQL 8+ sxema
-- UTF8MB4

CREATE DATABASE IF NOT EXISTS `mahalla` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `mahalla`;

-- Users jadvali
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

-- Yangiliklar jadvali
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

-- Murojaat loglari (status va izohlar tarixi) — applications dan keyin yaratiladi

-- Admins jadvali
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_admins_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Applications jadvali
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
  UNIQUE KEY `uniq_app_public_code` (`public_code`),
  CONSTRAINT `fk_app_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Murojaat loglari (status va izohlar tarixi)
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
  KEY `idx_log_created` (`created_at`),
  CONSTRAINT `fk_logs_app` FOREIGN KEY (`application_id`) REFERENCES `applications`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
