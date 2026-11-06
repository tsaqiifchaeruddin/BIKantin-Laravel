-- ============================================================
--  BI-KANTIN - Setup Database (MySQL / MariaDB via WSL)
--  Jalankan dari WSL dengan:
--      sudo mariadb < database/mysql-setup.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS db_bikantin_web
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Akun aplikasi (dipakai oleh Laravel di .env)
CREATE USER IF NOT EXISTS 'bikantin'@'localhost' IDENTIFIED BY 'bikantin';

GRANT ALL PRIVILEGES ON db_bikantin_web.* TO 'bikantin'@'localhost';

FLUSH PRIVILEGES;

SELECT 'OK - Database db_bikantin_web siap.' AS hasil;
