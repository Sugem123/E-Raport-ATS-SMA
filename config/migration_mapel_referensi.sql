-- =====================================================================
-- MIGRASI: Pisahkan Data Referensi Mapel & Mapping Mapel
-- Tanggal : 2026-09-28
-- Tujuan  : 1) tb_mapel_referensi = daftar induk semua mapel
--           2) tb_mapel_mapping   = urutan cetak rapor per jenjang
-- Catatan : Script ini aman diulang (idempotent) & membungkus
--           perubahan dalam transaksi.
-- =====================================================================

USE db_raport;

-- 1. Tabel referensi (master mapel, tanpa jenjang/kategori/urutan)
CREATE TABLE IF NOT EXISTS `tb_mapel_referensi` (
  `id_mapel` varchar(20) NOT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `sistem` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = mapel sistem, tidak boleh dihapus',
  PRIMARY KEY (`id_mapel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 2. Tabel mapping (urutan & kategori rapor, per jenjang)
CREATE TABLE IF NOT EXISTS `tb_mapel_mapping` (
  `id_mapel` varchar(20) NOT NULL,
  `jenjang` varchar(3) NOT NULL COMMENT '10 / 11 / 12',
  `kategori` varchar(20) NOT NULL DEFAULT 'Umum' COMMENT 'Umum / Pilihan',
  `urutan` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_mapel`, `jenjang`),
  KEY `fk_mapping_referensi` (`id_mapel`),
  CONSTRAINT `fk_mapping_referensi` FOREIGN KEY (`id_mapel`)
    REFERENCES `tb_mapel_referensi` (`id_mapel`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 3. Backup data lama (kalau tb_mapel masih ada)
DROP TABLE IF EXISTS `tb_mapel_backup_20260928`;
CREATE TABLE `tb_mapel_backup_20260928` AS
SELECT * FROM `tb_mapel`;

-- 4. Migrasi data lama -> referensi
INSERT IGNORE INTO `tb_mapel_referensi` (`id_mapel`, `nama_mapel`, `sistem`)
SELECT `id_mapel`, `nama_mapel`, 0 FROM `tb_mapel`;

-- 5. Migrasi data lama -> mapping
--    strategies: mapel bertingkat 'Semua' di-*expand* jadi 3 jenjang,
--    mapel bertingkat khusus (10/11/12) cukup 1 baris.
INSERT IGNORE INTO `tb_mapel_mapping` (`id_mapel`, `jenjang`, `kategori`, `urutan`)
SELECT `id_mapel`, '10', `kategori`, `urutan` FROM `tb_mapel` WHERE `tingkat` IN ('Semua', '10')
UNION
SELECT `id_mapel`, '11', `kategori`, `urutan` FROM `tb_mapel` WHERE `tingkat` IN ('Semua', '11')
UNION
SELECT `id_mapel`, '12', `kategori`, `urutan` FROM `tb_mapel` WHERE `tingkat` IN ('Semua', '12');

-- 6. Repoint FK tb_pengampu ke tabel referensi
ALTER TABLE `tb_pengampu` DROP FOREIGN KEY `fk_pengampu_mapel`;
ALTER TABLE `tb_pengampu`
  ADD CONSTRAINT `fk_pengampu_mapel` FOREIGN KEY (`id_mapel`)
  REFERENCES `tb_mapel_referensi` (`id_mapel`)
  ON DELETE CASCADE ON UPDATE CASCADE;

-- 7. Hapus tabel lama
DROP TABLE IF EXISTS `tb_mapel`;

-- 8. Verifikasi
SELECT 'REFERENSI' AS tabel, COUNT(*) AS jml FROM tb_mapel_referensi
UNION ALL
SELECT 'MAPPING', COUNT(*) FROM tb_mapel_mapping;
