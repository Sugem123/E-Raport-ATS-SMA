-- Referensi mapel SMAN 1 Prambon
-- Sumber: referensi mapel.xlsx (28 mapel)
-- Keputusan: kode 'S' (Sejarah) tetap, Sosiologi -> 'SOS'
-- CATATAN: script ini MENGHAPUS seluruh referensi lama; tb_mapel_mapping
--         dan tb_pengampu ikut terhapus (ON DELETE CASCADE).
USE db_raport;
START TRANSACTION;

DELETE FROM `tb_mapel_referensi`;

INSERT INTO `tb_mapel_referensi` (`id_mapel`, `nama_mapel`, `sistem`) VALUES
('PAIDBP', 'Pendidikan Agama Islam dan Budi Pekerti', 0),
('PAKDBP', 'Pendidikan Agama Kristen dan Budi Pekerti', 0),
('P.Pan', 'Pendidikan Pancasila', 0),
('BIN', 'Bahasa Indonesia', 0),
('BIG', 'Bahasa Inggris', 0),
('BIG Lanjut', 'Bahasa Inggris Tingkat Lanjut', 0),
('MLBD', 'Muatan Lokal Bahasa Daerah', 0),
('MU', 'Matematika (Umum)', 0),
('MTK Minat', 'Matematika (Peminatan)', 0),
('MTL', 'Matematika Tingkat Lanjut', 0),
('B', 'Biologi', 0),
('F', 'Fisika', 0),
('K', 'Kimia', 0),
('G', 'Geografi', 0),
('S', 'Sejarah', 0),
('STL', 'Sejarah Tingkat Lanjut', 0),
('SOS', 'Sosiologi', 0),
('E', 'Ekonomi', 0),
('PJODK', 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 0),
('BDKB', 'Bimbingan dan Konseling/Konselor (BP/BK)', 0),
('SDB', 'Seni dan Budaya', 0),
('PDK', 'Prakarya dan Kewirausahaan', 0),
('I', 'Informatika', 0),
('TIK', 'Teknologi Informasi dan Komunikasi', 0),
('SB', 'Seni Budaya', 0),
('IPA', 'Ilmu Pengetahuan Alam', 0),
('IPS', 'Ilmu Pengetahuan Sosial', 0),
('PAKADBP', 'Pendidikan Agama Katholik dan Budi Pekerti', 0);

COMMIT;
