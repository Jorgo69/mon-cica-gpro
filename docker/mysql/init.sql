-- CICA-GPRO — MySQL initialization
-- This runs automatically on first container start

-- Ensure database exists (redundant with MYSQL_DATABASE env, but safe)
CREATE DATABASE IF NOT EXISTS `cica_gpro`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Grant privileges
GRANT ALL PRIVILEGES ON `cica_gpro`.* TO 'gpro'@'%';
FLUSH PRIVILEGES;
