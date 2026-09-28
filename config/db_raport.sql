-- e-Raport STS (Sumatif Tengah Semester)
-- 4 Scope User: Admin, Guru, Wali Kelas, Siswa
-- Database: db_raport
--
-- Catatan: file ini bersifat DESTRUKTIF (DROP TABLE) — jangan dijalankan
--         pada database yang sudah berisi data produksi. Gunakan
--         config/migration_mapel_referensi.sql untuk mengubah skema
--         database yang sudah terisi.

CREATE DATABASE IF NOT EXISTS `db_raport` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_raport`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `tb_nilai_sts`;
DROP TABLE IF EXISTS `tb_presensi_sts`;
DROP TABLE IF EXISTS `tb_pengaturan_bobot`;
DROP TABLE IF EXISTS `tb_pengaturan_rapor`;
DROP TABLE IF EXISTS `tb_pengampu`;
DROP TABLE IF EXISTS `tb_siswa`;
DROP TABLE IF EXISTS `tb_kelas`;
DROP TABLE IF EXISTS `tb_guru`;
DROP TABLE IF EXISTS `tb_mapel_mapping`;
DROP TABLE IF EXISTS `tb_mapel_referensi`;
DROP TABLE IF EXISTS `tb_mapel`;
DROP TABLE IF EXISTS `tb_admin`;
DROP TABLE IF EXISTS `tb_user`;
DROP TABLE IF EXISTS `tb_nilai`;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Table tb_user
CREATE TABLE `tb_user` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL UNIQUE,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 2. Table tb_admin
CREATE TABLE `tb_admin` (
  `id_admin` varchar(10) NOT NULL,
  `id_user` int NOT NULL,
  PRIMARY KEY (`id_admin`),
  KEY `fk_user_admin` (`id_user`),
  CONSTRAINT `fk_user_admin` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 3. Table tb_mapel_referensi (Data Referensi / master mata pelajaran)
--    Menyimpan daftar induk SELURUH mapel. Tidak menyimpan jenjang,
--    kategori, atau urutan rapor (itu ada di tb_mapel_mapping).
CREATE TABLE `tb_mapel_referensi` (
  `id_mapel` varchar(20) NOT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `sistem` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = mapel sistem, tidak boleh dihapus',
  PRIMARY KEY (`id_mapel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 3b. Table tb_mapel_mapping (urutan & kategori cetak rapor per jenjang)
--     Satu mapel bisa dipetakan ke banyak jenjang dengan kategori & urutan
--     yang berbeda. Contoh: FIS -> jenjang 10 (Umum), jenjang 11 (Pilihan).
CREATE TABLE `tb_mapel_mapping` (
  `id_mapel` varchar(20) NOT NULL,
  `jenjang` varchar(3) NOT NULL COMMENT '10 / 11 / 12',
  `kategori` varchar(20) NOT NULL DEFAULT 'Umum' COMMENT 'Umum / Pilihan',
  `urutan` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_mapel`, `jenjang`),
  KEY `fk_mapping_referensi` (`id_mapel`),
  CONSTRAINT `fk_mapping_referensi` FOREIGN KEY (`id_mapel`) REFERENCES `tb_mapel_referensi` (`id_mapel`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4. Table tb_guru
CREATE TABLE `tb_guru` (
  `id_guru` varchar(20) NOT NULL,
  `id_user` int NOT NULL,
  `nama_guru` varchar(100) NOT NULL,
  PRIMARY KEY (`id_guru`),
  KEY `fk_user_guru` (`id_user`),
  CONSTRAINT `fk_user_guru` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 5. Table tb_kelas (dengan id_guru_walikelas)
CREATE TABLE `tb_kelas` (
  `id_kelas` int NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(20) NOT NULL UNIQUE,
  `tingkat` varchar(5) NOT NULL,
  `id_guru_walikelas` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_kelas`),
  KEY `fk_kelas_walikelas` (`id_guru_walikelas`),
  CONSTRAINT `fk_kelas_walikelas` FOREIGN KEY (`id_guru_walikelas`) REFERENCES `tb_guru` (`id_guru`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 6. Table tb_siswa
CREATE TABLE `tb_siswa` (
  `nis` varchar(20) NOT NULL,
  `id_user` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nisn` varchar(20) DEFAULT NULL,
  `id_kelas` int NOT NULL,
  PRIMARY KEY (`nis`),
  UNIQUE KEY `uq_siswa_nisn` (`nisn`),
  KEY `fk_user_siswa` (`id_user`),
  KEY `fk_kelas_siswa` (`id_kelas`),
  CONSTRAINT `fk_user_siswa` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_kelas_siswa` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 7. Table tb_pengampu (Penugasan Guru Mengajar Mapel di Kelas)
CREATE TABLE `tb_pengampu` (
  `id_pengampu` int NOT NULL AUTO_INCREMENT,
  `id_guru` varchar(20) NOT NULL,
  `id_mapel` varchar(20) NOT NULL,
  `id_kelas` int NOT NULL,
  PRIMARY KEY (`id_pengampu`),
  UNIQUE KEY `uk_guru_mapel_kelas` (`id_guru`, `id_mapel`, `id_kelas`),
  KEY `fk_pengampu_guru` (`id_guru`),
  KEY `fk_pengampu_mapel` (`id_mapel`),
  KEY `fk_pengampu_kelas` (`id_kelas`),
  CONSTRAINT `fk_pengampu_guru` FOREIGN KEY (`id_guru`) REFERENCES `tb_guru` (`id_guru`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pengampu_mapel` FOREIGN KEY (`id_mapel`) REFERENCES `tb_mapel_referensi` (`id_mapel`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pengampu_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 8. Table tb_pengaturan_bobot (Dikelola oleh Admin)
CREATE TABLE `tb_pengaturan_bobot` (
  `id_pengaturan` int NOT NULL DEFAULT 1,
  `bobot_sumatif` decimal(5,2) NOT NULL DEFAULT 60.00,
  `bobot_sts` decimal(5,2) NOT NULL DEFAULT 40.00,
  `kkm` decimal(5,2) NOT NULL DEFAULT 75.00,
  PRIMARY KEY (`id_pengaturan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 8b. Table tb_pengaturan_rapor (Identitas Satuan Pendidikan & Format Rapor)
CREATE TABLE `tb_pengaturan_rapor` (
  `id` int NOT NULL DEFAULT 1,
  `nama_sekolah` varchar(150) NOT NULL DEFAULT 'SMA NEGERI 1 PRAMBON NGANJUK',
  `alamat_sekolah` varchar(200) NOT NULL DEFAULT 'JL. A. YANI 1 SUGIHWARAS PRAMBON',
  `logo_sekolah` varchar(255) DEFAULT NULL,
  `npsn` varchar(30) NOT NULL DEFAULT '20539744',
  `akreditasi` varchar(30) NOT NULL DEFAULT 'A (Unggul)',
  `slogan` varchar(255) NOT NULL DEFAULT 'Unggul dalam Prestasi, Berkarakter, dan Berbudaya Lingkungan',
  `telepon` varchar(50) NOT NULL DEFAULT '(0358) 771234',
  `email` varchar(100) NOT NULL DEFAULT 'info@sman1prambon.sch.id',
  `website` varchar(100) NOT NULL DEFAULT 'sman1prambon.sch.id',
  `deskripsi_sekolah` text DEFAULT NULL,
  `tahun_ajaran` varchar(20) NOT NULL DEFAULT '2025/2026',
  `semester` varchar(10) NOT NULL DEFAULT '2',
  `nama_kepala_sekolah` varchar(150) NOT NULL DEFAULT 'Iin Yuristin Nadhiroh, S. Pd., M. MPd.',
  `nip_kepala_sekolah` varchar(30) NOT NULL DEFAULT '197405141999032010',
  `tempat_rapor` varchar(50) NOT NULL DEFAULT 'Prambon',
  `tanggal_rapor` varchar(50) NOT NULL DEFAULT '19 Juni 2026',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 9. Table tb_nilai_sts (Diinput oleh Guru Mapel)
CREATE TABLE `tb_nilai_sts` (
  `id_nilai` int NOT NULL AUTO_INCREMENT,
  `nis` varchar(20) NOT NULL,
  `id_pengampu` int NOT NULL,
  `sumatif_1` decimal(5,2) DEFAULT NULL,
  `sumatif_2` decimal(5,2) DEFAULT NULL,
  `sumatif_3` decimal(5,2) DEFAULT NULL,
  `rata_sumatif` decimal(5,2) DEFAULT NULL,
  `nilai_sts` decimal(5,2) DEFAULT NULL,
  `nilai_akhir` decimal(5,2) DEFAULT NULL,
  `status_kelulusan` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_nilai`),
  UNIQUE KEY `uk_siswa_pengampu` (`nis`, `id_pengampu`),
  KEY `fk_nilai_siswa` (`nis`),
  KEY `fk_nilai_pengampu` (`id_pengampu`),
  CONSTRAINT `fk_nilai_siswa` FOREIGN KEY (`nis`) REFERENCES `tb_siswa` (`nis`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_nilai_pengampu` FOREIGN KEY (`id_pengampu`) REFERENCES `tb_pengampu` (`id_pengampu`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 10. Table tb_presensi_sts (Diinput oleh Wali Kelas: S, I, A)
CREATE TABLE `tb_presensi_sts` (
  `id_presensi` int NOT NULL AUTO_INCREMENT,
  `nis` varchar(20) NOT NULL UNIQUE,
  `sakit` int NOT NULL DEFAULT 0,
  `izin` int NOT NULL DEFAULT 0,
  `alpa` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_presensi`),
  KEY `fk_presensi_siswa` (`nis`),
  CONSTRAINT `fk_presensi_siswa` FOREIGN KEY (`nis`) REFERENCES `tb_siswa` (`nis`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================================================
-- DATA AWAL (SEED)
-- Password untuk semua user awal: 'admin'
-- ========================================================

INSERT INTO `tb_user` (`id_user`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$jjKNjSDBr7IWn14yXPEEr.e/IY8Fv4KI5EiO4qmCp9TXZCN.JkXwe', 'admin'),
(2, 'guru_1', '$2y$10$jjKNjSDBr7IWn14yXPEEr.e/IY8Fv4KI5EiO4qmCp9TXZCN.JkXwe', 'guru'),
(3, 'guru_2', '$2y$10$jjKNjSDBr7IWn14yXPEEr.e/IY8Fv4KI5EiO4qmCp9TXZCN.JkXwe', 'guru'),
(4, 'walikelas_x1', '$2y$10$jjKNjSDBr7IWn14yXPEEr.e/IY8Fv4KI5EiO4qmCp9TXZCN.JkXwe', 'walikelas'),
(5, 'siswa_1', '$2y$10$jjKNjSDBr7IWn14yXPEEr.e/IY8Fv4KI5EiO4qmCp9TXZCN.JkXwe', 'siswa'),
(6, 'siswa_2', '$2y$10$jjKNjSDBr7IWn14yXPEEr.e/IY8Fv4KI5EiO4qmCp9TXZCN.JkXwe', 'siswa');

INSERT INTO `tb_admin` (`id_admin`, `id_user`) VALUES
('ADM001', 1);

-- Data Referensi: daftar induk seluruh mapel
INSERT INTO `tb_mapel_referensi` (`id_mapel`, `nama_mapel`, `sistem`) VALUES
('MTK', 'Matematika', 0),
('BIND', 'Bahasa Indonesia', 0),
('BING', 'Bahasa Inggris', 0),
('FIS', 'Fisika', 0),
('EKO', 'Ekonomi', 0),
('PKN', 'Pendidikan Pancasila dan Kewarganegaraan', 0);

-- Mapping per jenjang: urutan & kategori pada saat cetak rapor
-- Contoh FIS: Umum di kelas X, Pilihan di kelas XI & XII.
INSERT INTO `tb_mapel_mapping` (`id_mapel`, `jenjang`, `kategori`, `urutan`) VALUES
('PKN', '10', 'Umum', 1),
('BIND', '10', 'Umum', 2),
('MTK', '10', 'Umum', 3),
('BING', '10', 'Umum', 4),
('FIS', '10', 'Umum', 5),
('PKN', '11', 'Umum', 1),
('BIND', '11', 'Umum', 2),
('MTK', '11', 'Umum', 3),
('BING', '11', 'Umum', 4),
('FIS', '11', 'Pilihan', 5),
('EKO', '11', 'Pilihan', 6),
('PKN', '12', 'Umum', 1),
('BIND', '12', 'Umum', 2),
('MTK', '12', 'Umum', 3),
('BING', '12', 'Umum', 4),
('FIS', '12', 'Pilihan', 5),
('EKO', '12', 'Pilihan', 6);

INSERT INTO `tb_guru` (`id_guru`, `id_user`, `nama_guru`) VALUES
('GURU001', 2, 'Monica Tambunan, S.Pd.'),
('GURU002', 3, 'Budi Santoso, M.Pd.'),
('GURU003', 4, 'Dra. Hj. Siti Aminah, M.Pd.');

INSERT INTO `tb_kelas` (`id_kelas`, `nama_kelas`, `tingkat`, `id_guru_walikelas`) VALUES
(1, 'X-1', '10', 'GURU003'),
(2, 'X-2', '10', 'GURU001'),
(3, 'XI-MIPA-1', '11', 'GURU002');

INSERT INTO `tb_siswa` (`nis`, `id_user`, `nama`, `id_kelas`) VALUES
('SIS001', 5, 'Dilan Pratama', 1),
('SIS002', 6, 'Bella Safitri', 1);

INSERT INTO `tb_pengampu` (`id_pengampu`, `id_guru`, `id_mapel`, `id_kelas`) VALUES
(1, 'GURU001', 'PKN', 1),
(2, 'GURU002', 'MTK', 1);

INSERT INTO `tb_pengaturan_bobot` (`id_pengaturan`, `bobot_sumatif`, `bobot_sts`, `kkm`) VALUES
(1, 60.00, 40.00, 75.00);

INSERT INTO `tb_pengaturan_rapor` (`id`, `nama_sekolah`, `alamat_sekolah`, `logo_sekolah`, `npsn`, `akreditasi`, `slogan`, `telepon`, `email`, `website`, `deskripsi_sekolah`, `tahun_ajaran`, `semester`, `nama_kepala_sekolah`, `nip_kepala_sekolah`, `tempat_rapor`, `tanggal_rapor`) VALUES
(1, 'SMA NEGERI 1 PRAMBON NGANJUK', 'JL. A. YANI 1 SUGIHWARAS PRAMBON', NULL, '20539744', 'A (Unggul)', 'Unggul dalam Prestasi, Berkarakter, dan Berbudaya Lingkungan', '(0358) 771234', 'info@sman1prambon.sch.id', 'sman1prambon.sch.id', 'SMA Negeri 1 Prambon Nganjuk berkomitmen mewujudkan generasi cerdas, berakhlak mulia, kompetitif, serta siap menghadapi tantangan era digital dengan Kurikulum Merdeka.', '2025/2026', '2', 'Iin Yuristin Nadhiroh, S. Pd., M. MPd.', '197405141999032010', 'Prambon', '19 Juni 2026');

INSERT INTO `tb_nilai_sts` (`id_nilai`, `nis`, `id_pengampu`, `sumatif_1`, `sumatif_2`, `sumatif_3`, `rata_sumatif`, `nilai_sts`, `nilai_akhir`, `status_kelulusan`) VALUES
(1, 'SIS001', 1, 85.00, 80.00, 90.00, 85.00, 88.00, 86.20, 'Tercapai'),
(2, 'SIS001', 2, 80.00, 75.00, 85.00, 80.00, 78.00, 79.20, 'Tercapai'),
(3, 'SIS002', 1, 70.00, 65.00, 75.00, 70.00, 68.00, 69.20, 'Belum Tercapai');

INSERT INTO `tb_presensi_sts` (`id_presensi`, `nis`, `sakit`, `izin`, `alpa`) VALUES
(1, 'SIS001', 1, 0, 0),
(2, 'SIS002', 2, 1, 0);
