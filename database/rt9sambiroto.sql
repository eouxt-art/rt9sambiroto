-- RT 9 Sambiroto Database Structure
-- ================================

-- Table: users
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` text NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('admin','operator') NOT NULL DEFAULT 'operator',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NULL,
  `last_login` datetime NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: menu
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_menu` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL UNIQUE,
  `url` varchar(255) NOT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: artikel
CREATE TABLE `artikel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL UNIQUE,
  `konten` longtext NOT NULL,
  `gambar` varchar(255) NULL,
  `pembuat` int(11) NOT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `status` enum('draft','aktif','arsip') NOT NULL DEFAULT 'draft',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`pembuat`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: komentar
CREATE TABLE `komentar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artikel_id` int(11) NOT NULL,
  `nama_pengoment` varchar(100) NOT NULL,
  `email_pengoment` varchar(100) NOT NULL,
  `isi_komentar` text NOT NULL,
  `status` enum('pending','aktif','tolak') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`artikel_id`) REFERENCES `artikel`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: like
CREATE TABLE `like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artikel_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_like` (`artikel_id`, `ip_address`),
  FOREIGN KEY (`artikel_id`) REFERENCES `artikel`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: agenda
CREATE TABLE `agenda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `tgl_agenda` date NOT NULL,
  `jam_mulai` time NULL,
  `jam_selesai` time NULL,
  `lokasi` varchar(255) NULL,
  `pembuat` int(11) NOT NULL,
  `status` enum('draft','aktif','selesai') NOT NULL DEFAULT 'aktif',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`pembuat`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: laporan_keuangan
CREATE TABLE `laporan_keuangan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bulan` varchar(7) NOT NULL,
  `catatan` text NULL,
  `pembuat` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`pembuat`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: detail_laporan
CREATE TABLE `detail_laporan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `laporan_id` int(11) NOT NULL,
  `jenis` enum('pendapatan','pengeluaran') NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`laporan_id`) REFERENCES `laporan_keuangan`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: warga
CREATE TABLE `warga` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_urut` int(11) NOT NULL,
  `nik` varchar(20) NULL UNIQUE,
  `no_kk` varchar(20) NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `tgl_lahir` date NULL,
  `tempat_lahir` varchar(100) NULL,
  `alamat` text NULL,
  `pekerjaan` varchar(100) NULL,
  `pendidikan` varchar(100) NULL,
  `status_pernikahan` enum('Belum Kawin','Kawin','Cerai Hidup','Cerai Mati') NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: gallery
CREATE TABLE `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text NULL,
  `gambar` varchar(255) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `uploader` int(11) NOT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`uploader`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: kontak
CREATE TABLE `kontak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telepon` varchar(20) NULL,
  `pesan` text NOT NULL,
  `status` enum('baru','dibaca','dibalas') NOT NULL DEFAULT 'baru',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user
-- Username: admin, Password: admin123
INSERT INTO `users` VALUES (
  1,
  'admin',
  'admin@rt9sambiroto.local',
  '$2y$10$SlFBZndOMXd1RkVGQk1QRO3L6H1QXqUEgvXLlXL.dH9O3O/Jy5g4K',
  'Administrator',
  'admin',
  1,
  NOW(),
  NULL,
  NULL
);

-- Insert default menu
INSERT INTO `menu` (`nama_menu`, `slug`, `url`, `urutan`, `is_active`, `created_at`) VALUES
('Beranda', 'beranda', 'http://localhost/rt9sambiroto/', 1, 1, NOW()),
('Artikel', 'artikel', 'http://localhost/rt9sambiroto/artikel', 2, 1, NOW()),
('Agenda', 'agenda', 'http://localhost/rt9sambiroto/agenda', 3, 1, NOW()),
('Laporan Keuangan', 'laporan', 'http://localhost/rt9sambiroto/laporan', 4, 1, NOW()),
('Data Warga', 'warga', 'http://localhost/rt9sambiroto/warga', 5, 1, NOW()),
('Galeri', 'galeri', 'http://localhost/rt9sambiroto/gallery', 6, 1, NOW()),
('Kontak', 'kontak', 'http://localhost/rt9sambiroto/kontak', 7, 1, NOW());

-- Insert sample agenda
INSERT INTO `agenda` (`judul`, `deskripsi`, `tgl_agenda`, `jam_mulai`, `jam_selesai`, `lokasi`, `pembuat`, `status`, `created_at`) VALUES
('Rapat Rutin RT', 'Rapat bulanan membahas perkembangan dan kebersihan lingkungan', '2026-05-20', '19:00:00', '21:00:00', 'Rumah Ketua RT', 1, 'aktif', NOW()),
('Kerja Bakti Lingkungan', 'Bersih-bersih dan pemeliharaan fasilitas RT', '2026-05-25', '06:00:00', '08:00:00', 'Lingkungan RT 9', 1, 'aktif', NOW());

-- Insert sample artikel
INSERT INTO `artikel` (`judul`, `slug`, `konten`, `pembuat`, `status`, `created_at`) VALUES
('Selamat Datang di Website RT 9 Sambiroto', 'selamat-datang-website-rt-9', 'Selamat datang di website resmi RT 9 Desa Sambiroto. Website ini dibuat untuk memberikan informasi lengkap tentang kegiatan, agenda, laporan keuangan, dan data warga RT 9. Melalui website ini, kami berharap dapat meningkatkan transparansi dan komunikasi dengan seluruh warga. Silakan jelajahi berbagai menu yang telah kami sediakan untuk mendapatkan informasi lebih lengkap.', 1, 'aktif', NOW());

-- Insert sample warga (optional)
INSERT INTO `warga` (`no_urut`, `nik`, `no_kk`, `nama_lengkap`, `jenis_kelamin`, `tgl_lahir`, `tempat_lahir`, `alamat`, `pekerjaan`, `pendidikan`, `status_pernikahan`, `created_at`) VALUES
(1, '3209123456789012', '3209000000000001', 'Budi Santoso', 'Laki-laki', '1980-05-15', 'Semarang', 'Jalan Kenanga No. 1, RT 9, Desa Sambiroto', 'PNS', 'S1', 'Kawin', NOW()),
(2, '3209123456789013', '3209000000000001', 'Siti Nurhaliza', 'Perempuan', '1985-03-20', 'Semarang', 'Jalan Kenanga No. 1, RT 9, Desa Sambiroto', 'Ibu Rumah Tangga', 'SMA', 'Kawin', NOW());
