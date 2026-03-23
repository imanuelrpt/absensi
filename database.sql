CREATE DATABASE IF NOT EXISTS `absensi_sekolah`;
USE `absensi_sekolah`;

CREATE TABLE `kelas` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nama_kelas` VARCHAR(50) NOT NULL,
    `wali_kelas` VARCHAR(100) NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed sample classes
INSERT INTO `kelas` (`nama_kelas`, `wali_kelas`) VALUES
('Kelas 10 A', '-'), ('Kelas 10 B', '-'), ('Kelas 10 C', '-'), ('Kelas 10 D', '-'), ('Kelas 10 E', '-'),
('Kelas 11 A', '-'), ('Kelas 11 B', '-'), ('Kelas 11 C', '-'), ('Kelas 11 D', '-'), ('Kelas 11 E', '-'),
('Kelas 12 A', '-'), ('Kelas 12 B', '-'), ('Kelas 12 C', '-'), ('Kelas 12 D', '-'), ('Kelas 12 E', '-');

CREATE TABLE `users` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nama` VARCHAR(100) NOT NULL,
    `nomor_induk` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'guru', 'siswa') NOT NULL DEFAULT 'siswa',
    `kelas_id` INT(11) UNSIGNED NULL,
    `face_descriptor` TEXT NULL COMMENT 'JSON array of face-api.js descriptor',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`kelas_id`) REFERENCES `kelas`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `qr_code` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `kode_qr` VARCHAR(255) NOT NULL UNIQUE,
    `tanggal` DATE NOT NULL,
    `jam_masuk` TIME NOT NULL DEFAULT '07:00:00',
    `batas_terlambat` TIME NOT NULL DEFAULT '07:30:00',
    `status` ENUM('aktif', 'tidak aktif') NOT NULL DEFAULT 'aktif',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `absensi` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `kelas_id` INT(11) UNSIGNED NULL,
    `qr_id` INT(11) UNSIGNED NOT NULL,
    `tanggal` DATE NOT NULL,
    `jam_masuk` TIME NOT NULL,
    `status` ENUM('hadir', 'terlambat', 'tidak hadir') NOT NULL DEFAULT 'hadir',
    `metode` ENUM('qr', 'wajah') NOT NULL DEFAULT 'qr',
    `foto_absen` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`kelas_id`) REFERENCES `kelas`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (`qr_id`) REFERENCES `qr_code`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;