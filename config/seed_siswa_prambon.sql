-- Import siswa SMAN 1 Prambon
-- Sumber: RENCANA PEMBAGIAN TUGAS MENGAJAR.xlsx :: sheet 'DAFTAR SISWA'
-- Dibuat: 2026-09-28 11:06:16
-- Jumlah: 1050 siswa | password default: Abcde12345@
--
-- CARO MENGHAPUS (rollback):
--   DELETE FROM tb_siswa WHERE nis BETWEEN '6443' AND '7520';
--   DELETE FROM tb_user  WHERE role='siswa';

USE db_raport;
START TRANSACTION;

-- 1. User (role siswa)
INSERT INTO tb_user (username, password, role) VALUES
('6443', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6448', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6470', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6489', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6492', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6496', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6510', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6519', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6529', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6533', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6552', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6566', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6588', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6590', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6593', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6601', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6604', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6605', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6615', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6618', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6621', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6625', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6628', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6635', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6658', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6661', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6678', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6680', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6689', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6691', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6714', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6745', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6765', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6788', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6791', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6456', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6458', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6474', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6495', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6497', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6498', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6505', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6523', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6530', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6534', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6540', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6546', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6549', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6556', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6558', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6563', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6572', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6574', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6575', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6594', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6626', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6637', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6651', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6659', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6683', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6685', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6707', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6725', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6742', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6743', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6759', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6763', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6773', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6776', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6447', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6449', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7167', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6469', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6475', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6478', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6479', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6480', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6482', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6501', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6506', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6535', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6802', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6559', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6569', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6571', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6596', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6597', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6610', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6613', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6623', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6641', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6803', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6660', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6694', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6697', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6706', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6709', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6734', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6740', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6748', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6758', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6772', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6781', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6793', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6452', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6485', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6493', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6500', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6507', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6511', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6513', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6518', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6526', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6528', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6537', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6550', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6573', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6578', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6583', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6607', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6619', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6639', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6652', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6654', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6670', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6692', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6705', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6713', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6715', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6727', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6747', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6752', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6756', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6782', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6783', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6785', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6786', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6795', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6445', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6454', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6461', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6471', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6483', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6502', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6508', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6542', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6564', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6567', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6600', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6611', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6614', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6806', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6624', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6630', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6634', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6636', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6650', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6805', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6655', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6665', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6676', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6687', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6719', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6720', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6722', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6729', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6738', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6755', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6760', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6761', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6789', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6796', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6455', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6467', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6468', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6477', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6488', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6499', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6504', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6512', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6524', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6544', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6545', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6553', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6555', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6580', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6581', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6587', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6592', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6602', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6622', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6629', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6648', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6804', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6666', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6688', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6698', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6702', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6704', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6712', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6721', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6728', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6739', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6744', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6766', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6769', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6777', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6794', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6446', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6491', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6514', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6516', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6527', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6538', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6543', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6547', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6551', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6554', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6557', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6565', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6577', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6579', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6582', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6603', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6627', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6638', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6640', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6647', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6662', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6667', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6671', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6673', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6681', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6682', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6696', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6703', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6730', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6732', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6735', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6737', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6753', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6762', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6778', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6451', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6453', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6464', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6465', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6486', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6490', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6515', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6520', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6532', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6560', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6562', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6576', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6586', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6595', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6608', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6617', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6620', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6632', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6644', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6645', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6646', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6807', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6663', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6669', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6677', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6679', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6686', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6693', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6718', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6726', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6736', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6741', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6746', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6750', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6770', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6774', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6444', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6457', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6460', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6473', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6476', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6503', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6536', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6539', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6541', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6548', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6561', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6570', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6591', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6609', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6616', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6631', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6633', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6643', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6649', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6656', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6674', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6675', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6684', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6700', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6716', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6717', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6724', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6749', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6754', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6757', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6767', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6768', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6775', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6787', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6790', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6797', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6450', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6462', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6463', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6466', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6472', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6481', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6484', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6487', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6509', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6517', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6521', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6522', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6531', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6568', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6584', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6585', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6589', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6598', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6606', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6653', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6664', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6668', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6672', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6690', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6695', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6701', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6710', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6711', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6731', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6733', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6751', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6764', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6771', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6779', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6780', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6792', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6815', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6821', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6830', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6855', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6864', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7169', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6888', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6890', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6904', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6912', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6923', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6932', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6937', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6941', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6949', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6954', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6964', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6987', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6993', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7000', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7028', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7037', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7054', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7056', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7061', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7063', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7067', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7073', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7097', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7110', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7124', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7125', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7133', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7145', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7153', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7163', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6810', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6811', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6824', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6828', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6838', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6848', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6850', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6860', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6866', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6870', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6873', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6902', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6933', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6935', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6940', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6948', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6957', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6960', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6972', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7001', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7005', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7033', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7026', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7041', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7042', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7078', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7080', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7083', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7090', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7122', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7123', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7131', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7132', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7143', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7144', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7156', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6829', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6831', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6833', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6836', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6845', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6857', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6882', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6885', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6905', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6906', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6917', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6929', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6934', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6936', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6942', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6943', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6946', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6988', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6991', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6999', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7003', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7023', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7040', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7044', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7060', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7064', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7071', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7081', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7089', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7092', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7093', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7109', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7118', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7129', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7138', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7161', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6809', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6812', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6818', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6827', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6835', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6837', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6840', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6849', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6879', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6897', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6910', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6911', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6913', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6920', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6922', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6925', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6939', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6950', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6961', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6971', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6984', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6990', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6996', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7006', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7008', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7016', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7022', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7025', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7029', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7031', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7048', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7058', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7062', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7070', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7112', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7154', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6814', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6817', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6834', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6854', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6863', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6872', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6875', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7166', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6909', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6930', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6951', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6959', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6966', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6970', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6975', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6979', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6983', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6994', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6998', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7168', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7007', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7015', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7039', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7055', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7074', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7075', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7076', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7077', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7079', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7086', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7117', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7126', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7140', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7152', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7158', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7164', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6822', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6832', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6862', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6868', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6878', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6883', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6892', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6952', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6967', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6974', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6981', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6982', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6992', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6997', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7002', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7010', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7020', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7021', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7032', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7036', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7043', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7047', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7051', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7072', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7084', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7098', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7107', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7108', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7121', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7127', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7146', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7147', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6820', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6841', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6844', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6847', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6856', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6886', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6903', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6907', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6915', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6918', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6928', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6947', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6955', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6958', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6973', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6976', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6986', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7017', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7018', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7030', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7035', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7038', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7057', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7066', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7082', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7087', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7100', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7113', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7128', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7151', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6808', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6813', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6816', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6826', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6839', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6867', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6893', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6894', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6896', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6900', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6931', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6945', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6985', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7004', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7024', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7027', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7050', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7052', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7069', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7088', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7095', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7096', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7099', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7101', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7102', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7105', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7106', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7116', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7141', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7160', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7162', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7170', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6825', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6843', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6858', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6859', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6876', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6880', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6914', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6916', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6919', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6921', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6926', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6927', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6944', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6965', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6968', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6969', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6977', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6989', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7014', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7019', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7059', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7068', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7104', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7114', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7119', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7120', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7130', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7135', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7137', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7139', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7142', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7148', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7150', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7155', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7157', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7165', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6819', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6846', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6851', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6852', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6865', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6869', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6874', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6877', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6881', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6884', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6889', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6891', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6895', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6898', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6901', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6938', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6953', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6962', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6963', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6978', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6980', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('6995', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7011', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7012', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7013', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7049', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7053', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7065', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7085', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7091', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7094', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7103', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7115', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7134', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7136', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7149', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7176', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7180', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7184', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7185', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7196', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7206', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7229', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7260', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7269', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7278', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7282', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7284', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7286', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7303', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7313', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7334', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7337', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7342', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7349', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7353', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7368', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7378', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7403', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7437', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7439', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7448', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7461', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7463', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7467', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7473', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7491', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7511', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7516', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7518', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7175', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7186', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7188', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7189', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7207', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7210', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7218', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7222', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7232', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7237', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7238', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7252', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7255', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7257', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7265', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7301', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7302', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7326', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7328', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7341', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7350', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7354', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7358', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7362', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7372', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7396', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7397', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7436', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7452', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7453', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7456', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7486', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7490', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7492', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7509', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7517', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7171', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7177', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7182', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7191', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7200', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7217', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7233', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7243', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7251', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7256', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7266', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7267', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7275', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7289', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7299', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7304', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7306', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7312', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7319', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7320', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7322', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7330', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7332', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7363', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7388', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7391', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7413', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7416', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7438', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7443', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7466', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7470', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7494', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7505', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7508', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7181', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7192', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7198', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7225', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7228', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7234', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7235', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7239', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7250', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7253', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7262', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7272', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7283', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7293', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7295', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7316', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7321', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7324', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7329', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7338', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7340', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7351', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7370', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7386', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7390', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7400', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7404', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7415', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7449', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7459', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7481', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7484', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7485', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7500', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7515', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7519', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7195', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7202', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7209', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7212', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7216', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7220', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7240', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7244', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7248', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7259', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7263', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7281', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7291', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7292', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7315', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7352', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7356', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7360', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7379', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7389', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7399', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7401', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7407', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7418', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7422', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7428', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7431', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7442', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7445', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7478', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7487', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7489', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7493', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7514', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7173', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7179', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7183', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7242', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7249', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7258', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7271', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7297', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7300', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7308', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7311', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7327', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7336', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7339', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7367', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7371', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7382', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7384', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7394', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7409', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7412', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7423', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7425', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7446', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7464', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7471', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7475', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7477', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7495', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7498', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7501', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7502', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7503', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7513', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7172', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7174', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7215', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7219', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7221', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7230', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7231', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7241', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7261', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7268', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7273', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7287', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7298', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7307', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7309', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7314', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7325', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7331', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7346', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7366', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7373', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7375', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7387', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7395', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7410', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7411', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7432', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7450', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7474', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7476', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7483', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7488', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7504', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7507', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7512', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7521', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7178', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7187', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7193', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7199', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7201', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7203', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7208', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7223', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7227', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7247', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7277', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7279', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7280', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7285', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7343', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7347', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7357', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7364', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7365', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7369', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7374', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7377', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7381', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7385', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7405', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7408', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7414', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7426', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7444', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7447', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7457', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7458', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7460', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7479', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7510', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7523', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7194', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7236', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7264', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7270', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7274', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7276', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7288', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7294', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7296', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7305', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7310', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7318', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7333', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7345', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7348', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7376', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7380', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7383', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7398', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7406', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7417', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7419', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7420', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7421', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7427', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7429', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7433', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7435', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7441', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7454', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7462', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7465', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7472', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7480', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7482', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7522', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7190', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7197', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7204', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7205', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7211', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7213', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7214', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7224', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7226', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7245', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7246', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7254', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7290', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7317', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7323', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7335', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7344', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7355', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7359', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7361', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7392', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7393', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7402', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7424', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7430', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7434', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7440', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7451', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7455', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7468', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7469', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7496', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7497', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7499', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7506', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa'),
('7520', '$2y$10$icC1IQGI.4EAky4OGi1y9uKkxzpTrpjAlplsdN9ad.j2qp0tzYmYe', 'siswa');

-- 2. Siswa (id_user dicari lewat join username)
INSERT INTO tb_siswa (nis, id_user, nama, id_kelas)
SELECT s.nis, u.id_user, s.nama, s.id_kelas FROM (
  SELECT '6443' AS nis, 'ABEL APRILYA FRANSISCA' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6448' AS nis, 'ACHMAD ROSSI YOGA PRATAMA' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6470' AS nis, 'AMIN RISKA MUBAROK' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6489' AS nis, 'AUFA DHAIFULLAH' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6492' AS nis, 'AURA WENDY PURNAMA PUTRI' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6496' AS nis, 'AZERRA VINDIANITA' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6510' AS nis, 'CECYLIA MELYNDA MAHESWARI' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6519' AS nis, 'CHINDY MEILANY PUTRI' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6529' AS nis, 'DEA ERSA RAIHANUN' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6533' AS nis, 'DESINTA TRIHAPSARI' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6552' AS nis, 'ELMIRA EKA ZAHRA' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6566' AS nis, 'FIKA DWI RATNA SARI' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6588' AS nis, 'INDAH KHUMAIRO NUR RAMADHANI' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6590' AS nis, 'IRVAN FERDIAN ADITAMA' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6593' AS nis, 'JELITA DIAN SERURIN' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6601' AS nis, 'JONAS REVANO EVANDER SANEGA' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6604' AS nis, 'JUWITA DWI KARTIKA' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6605' AS nis, 'KANAYA PUTRI AMELIA' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6615' AS nis, 'KINANTI WULAN MADHANI' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6618' AS nis, 'LEXAVINO TSAQIF PRATHAMA' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6621' AS nis, 'LINDA AULIA SURYANTI' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6625' AS nis, 'M. Afthon Iman Huda Ismail' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6628' AS nis, 'M. RIZAL FIRMANSYAH' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6635' AS nis, 'MERINDA KIRANIA PUTRI' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6658' AS nis, 'MUHAMMAD KEYVIN PUTRA WILUJENG' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6661' AS nis, 'MUHAMMAD RASYA PRATAMA PUTRA' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6678' AS nis, 'NAWA BUDI PRATAMA' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6680' AS nis, 'NAZARA ADENO PRADISTINA' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6689' AS nis, 'NOVIA ANGGRAINI' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6691' AS nis, 'NUR ANAA FITA' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6714' AS nis, 'REFITO DEBRIAN WIFI' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6745' AS nis, 'SEPTIA NIKITA SARI' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6765' AS nis, 'SYAHRILA HIKMATUL MUSYIDA' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6788' AS nis, 'WAHYU SETIAWAN' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6791' AS nis, 'YENI PANGESTI' AS nama, 24 AS id_kelas
  UNION ALL
  SELECT '6456' AS nis, 'AHMAD GALIH FREDIANSAH' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6458' AS nis, 'AHMAD JUWAIR' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6474' AS nis, 'ANDINI NUR HALIZA' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6495' AS nis, 'AYNA FRECILIA' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6497' AS nis, 'BAGUS AZIS SAPUTRA' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6498' AS nis, 'BAGUS GUNTUR SAMUDRA' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6505' AS nis, 'BINTI KUSROTUL ZARIAH' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6523' AS nis, 'CINDY MARISKA PUTRI' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6530' AS nis, 'DEBBY DINARA' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6534' AS nis, 'DESITA WAHYUNI' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6540' AS nis, 'DIANDRA KEYLA ASMINTORO' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6546' AS nis, 'DIYAH AYU RETNO NINGSIH' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6549' AS nis, 'EGA PUTRA ADI NUGROHO' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6556' AS nis, 'ERINDA IRAWATI' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6558' AS nis, 'EVA NOVITASARI' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6563' AS nis, 'FARA ALIEFTA AZZARA' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6572' AS nis, 'FITRIYAN AKBAR PRAYOGI' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6574' AS nis, 'FUAD MAULANA' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6575' AS nis, 'GALIH ALIEF WAHYUDIANTO' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6594' AS nis, 'JELITA RIZKY RAMADHANI' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6626' AS nis, 'M FAIQ AZIZI SYAZANI' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6637' AS nis, 'MOCH. BAGUS RIZKY ADITYA PRATAMA' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6651' AS nis, 'MUFIROTUL QUSNA' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6659' AS nis, 'MUHAMMAD KHARIZ RAFIQI AL-BADRU' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6683' AS nis, 'NHOSYLA CAHAYA MUSTIKA' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6685' AS nis, 'NISA KURNIA VITTRI FIRNANDA' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6707' AS nis, 'RANIAH AULFA NI''MAH' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6725' AS nis, 'RISMA RAHMA ARIANI' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6742' AS nis, 'SELIA RATNA OLIVIA' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6743' AS nis, 'SENDY ARYA BIMA KUSUMADEWA' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6759' AS nis, 'SILVIA SAHMIA PUTRI' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6763' AS nis, 'SUKMA KANYA JASYAFA' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6773' AS nis, 'TRISTIARA AFIKA SARI' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6776' AS nis, 'VALENTINA TRIHAPSARI' AS nama, 25 AS id_kelas
  UNION ALL
  SELECT '6447' AS nis, 'Achmad Risya Al-Habsyi' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6449' AS nis, 'ACHNIZA ASYAFIRA CAHYA FEBRIANSANI' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '7167' AS nis, 'AHMAD AFRIN AVRIZA' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6469' AS nis, 'ALYA MAY SAFIRA' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6475' AS nis, 'ANGGI DWI LESTARI' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6478' AS nis, 'ANINDYA FITRIA NURAIDA' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6479' AS nis, 'ANITA TRI HAPSARI' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6480' AS nis, 'ANJANI CATUR PURBASARI' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6482' AS nis, 'APRILIA DWI NING TIAS' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6501' AS nis, 'BELLA ARTHALITA RAMADANI' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6506' AS nis, 'BINTI LAILATUL SILFIAH' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6535' AS nis, 'DEVI CHEZA PRATIWI' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6802' AS nis, 'DITA WIDHI CITRA LESTARI' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6559' AS nis, 'EVAMIA KESYA SASA BELLA' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6569' AS nis, 'FIRSTFIQI FRANDIKA ZULENDRA ANSARULLOH' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6571' AS nis, 'FITRIA AZZAHRA UTAMA' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6596' AS nis, 'JHOVAN FARISH ARDIANSYAH' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6597' AS nis, 'JIHAN FAIZAH MAS''UDAH' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6610' AS nis, 'KEYSA NAMIRA PUTRI HANIKOLA' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6613' AS nis, 'KHEYZA RAFFAIL ZAHWA ANTARIKSA' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6623' AS nis, 'LUTHVIAN RAMADITYA ADIKARA' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6641' AS nis, 'MOH. DIMAS ELITE MAYRIAN' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6803' AS nis, 'MUH. HAFIZH MAULANA WAFI' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6660' AS nis, 'M. RASHA DITYA' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6694' AS nis, 'PEBRIAN AJI SULISTIO' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6697' AS nis, 'PUTRI MAYA CINTA RAMADHANI' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6706' AS nis, 'RANI DWI SETIASIH' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6709' AS nis, 'RARA ZHA ''FILLA' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6734' AS nis, 'SAAD DWI CAHYONO' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6740' AS nis, 'SATYA NUR ABHIZAR AULIA' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6748' AS nis, 'SETIYA ADI FAHRUQI' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6758' AS nis, 'SILVIA MARCELLA PUTRI' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6772' AS nis, 'TIFANI NAIYA SABINA' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6781' AS nis, 'VINA ELSANDRA AMELIANTI' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6793' AS nis, 'ZAHARI DANUARTHA' AS nama, 26 AS id_kelas
  UNION ALL
  SELECT '6452' AS nis, 'ADIWINANTA DHARMA KUMARA' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6485' AS nis, 'ARDAN PUTRA INZAGY' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6493' AS nis, 'AURORA FITRI ANISA' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6500' AS nis, 'BAYU NUR WICAKSONO' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6507' AS nis, 'BRIAN ALIM ALAMSAH' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6511' AS nis, 'CHAFID DHOTUL FAZA' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6513' AS nis, 'CHEISYA NAZILIA PUTRI' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6518' AS nis, 'CHIKO DEVA ANDREAN' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6526' AS nis, 'DAFIAN RIZKY PERDANA' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6528' AS nis, 'DAVINA PUTRI MAULINA' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6537' AS nis, 'DEVRIANTO NOOR AFRIZAL' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6550' AS nis, 'EKA NOVITASARI' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6573' AS nis, 'FRISSA HUFFY RAHMANDA' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6578' AS nis, 'GIGIH SATRIYO WICAKSONO' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6583' AS nis, 'HARDING ALVINO TENDRA' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6607' AS nis, 'KARTIKA WIJAYA' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6619' AS nis, 'LIA SUCI ROHMATUL UMMAH' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6639' AS nis, 'MOH. AKBAR MAULANA BACHTIAR' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6652' AS nis, 'MUHAMAD AFIT DWI MAULANA' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6654' AS nis, 'MUHAMMAD ADILLA SAFA AL ISLAMI' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6670' AS nis, 'NAFISA AURA ZAHRA' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6692' AS nis, 'NURAISHA RISKA ARMIANI' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6705' AS nis, 'RAHMAT FABIYAN' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6713' AS nis, 'REFALDO BAGUS SAPUTRO' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6715' AS nis, 'REIHAN ACHMAD WAVA' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6727' AS nis, 'RIZA MAHENDRA' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6747' AS nis, 'SESA QIFTI ANGGRAINI' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6752' AS nis, 'SHELLOMYTHA PUTRI NAOMI' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6756' AS nis, 'SHILLA PUTRI APRILIA' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6782' AS nis, 'VIRA JULIA TRIA NADA' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6783' AS nis, 'VIRGIO DAVIN PRADHITYA' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6785' AS nis, 'VIVI MAYLANI' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6786' AS nis, 'WAHYU DEVAN SETYAWAN' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6795' AS nis, 'ZAHRATUL MAULIDAH' AS nama, 27 AS id_kelas
  UNION ALL
  SELECT '6445' AS nis, 'ACHMAD BADRUS SHOFA HARIADI' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6454' AS nis, 'AHMAD DANI KURNIAWAN' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6461' AS nis, 'AHMAD SHOLEHUDIN' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6471' AS nis, 'ANANDA NADHIRA SYFANA PUTRI' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6483' AS nis, 'APRILIA TRIVANIA' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6502' AS nis, 'BILQIS AULIA RAHMADHANI' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6508' AS nis, 'BRIAN SANDI DANDER' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6542' AS nis, 'DINDA FIRSTHY KIRENIA MAULIDA' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6564' AS nis, 'FARADISCA NURIL DEVIANA' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6567' AS nis, 'FIKI HUDA PRASETYA' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6600' AS nis, 'JOKO HADISASONO' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6611' AS nis, 'KHAIRIN NISA' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6614' AS nis, 'KHUSNIA AULIA PUTRI' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6806' AS nis, 'LUTHFI AHNAF DWI SURURI' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6624' AS nis, 'LYLA ARTANTI RAMADHANI' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6630' AS nis, 'M. TAHTA DWI IRSYADUL IBAD' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6634' AS nis, 'MELLANI SAGITA' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6636' AS nis, 'MOCH ALFIAN JAUHAR AL ARIFI' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6650' AS nis, 'MOURINHO MICHELE VALENCIA' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6805' AS nis, 'MUHAMMAD DESTIAN FIRMAN SAPUTRA' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6655' AS nis, 'MUHAMMAD HAMID FIRMANSYAH' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6665' AS nis, 'MUHAMMAD SYAFIQ NUR LATIF' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6676' AS nis, 'NATASYA SALSYA VIMALA' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6687' AS nis, 'NOVAL ALIF SADEWA' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6719' AS nis, 'REYVANO ANDREA VIRGIE' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6720' AS nis, 'REZA PUTRA NUR RAMA' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6722' AS nis, 'RIDHO PRAMUDYA RAHMADANA' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6729' AS nis, 'RIZKY FERDIANTO' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6738' AS nis, 'SASKI MEIKA UTAMI' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6755' AS nis, 'SHIFA NUR AZIZAH' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6760' AS nis, 'SINTA YULIA NOR RAHMADANI' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6761' AS nis, 'SITI SHANDY ROMANDOR' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6789' AS nis, 'WANDA YULIANA NUR NAYLA' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6796' AS nis, 'ZILDAN KUNCORO TRI WIBOWO' AS nama, 28 AS id_kelas
  UNION ALL
  SELECT '6455' AS nis, 'AHMAD FARIZ APRIYANTO' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6467' AS nis, 'ALFITO REZA PUTRA WARDHANA' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6468' AS nis, 'ALVINO PRATAMA PUTRA' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6477' AS nis, 'ANINDIA CAHYA AGUSTIN' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6488' AS nis, 'ARJA SETYA RADHI SUCIPTO' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6499' AS nis, 'BAGUS MUSTOPA' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6504' AS nis, 'BINTANG RIZKI NUGROHO' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6512' AS nis, 'CHANDY BUDI SETYAWAN' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6524' AS nis, 'CRYSNA NIRMALA' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6544' AS nis, 'DIVA AULIA PRAMESTI' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6545' AS nis, 'DIVA DWI APRILIA' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6553' AS nis, 'ELVIRA KHOIRUNNISA' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6555' AS nis, 'ERIKA EFA ROIFAH' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6580' AS nis, 'GUSTIAN ABIYU WIJATMIKO' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6581' AS nis, 'HAICHAL ALFARESTU PRATAMA SUTIKNO' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6587' AS nis, 'IDAFI DIWANGGA AHMAD' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6592' AS nis, 'JAVIER RADITYA HEZEKIAH' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6602' AS nis, 'JOVITA AGBEL PUTRI PRATAMA' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6622' AS nis, 'LINTANG FAHRENDYA MALELA PRATAMA' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6629' AS nis, 'M. ROBBIT FAHIMI' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6648' AS nis, 'MOHAMMAD RIZQI FADILLAH' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6804' AS nis, 'MUHAMMAD FAYYADH QUSHOYYI HIDAYATULLAH' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6666' AS nis, 'MUSTOFA NGIZY MUBAROK' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6688' AS nis, 'NOVAL HIDAYATULLAH HERMAWAN' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6698' AS nis, 'PUTRI NURIN NABILA' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6702' AS nis, 'RAHMA TRI RAHAYU' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6704' AS nis, 'RAHMANIA NUR JANAH' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6712' AS nis, 'RAYHAN FAIZ ERLANGGA' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6721' AS nis, 'RIA MUSTIRA' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6728' AS nis, 'RIZKY AFRIANSYAH PUTRA PRATAMA' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6739' AS nis, 'SATRIA BAGUS WIBISONO' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6744' AS nis, 'SEPTI AYU RISKA RHOMADINA' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6766' AS nis, 'SYHAAVIN HANIYYA MAHDI' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6769' AS nis, 'TEGAR DWI SANTOSO' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6777' AS nis, 'VALIN NAURA MEYSHA' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6794' AS nis, 'ZAHRA NUR FADHILLA' AS nama, 29 AS id_kelas
  UNION ALL
  SELECT '6446' AS nis, 'ACHMAD BAYU SAPUTRO' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6491' AS nis, 'AURA FARRIHATUSSALMA' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6514' AS nis, 'CHELSEA ANANDA SARI' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6516' AS nis, 'CHERY EARLYTA MAYANG' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6527' AS nis, 'DANY NOUVAL FADLUROHMAN' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6538' AS nis, 'DEWI RETNO WATI' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6543' AS nis, 'DINI TRI FADILA' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6547' AS nis, 'DWI ANANDA SARI' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6551' AS nis, 'EKA PUTRI RIANI' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6554' AS nis, 'ENJELINA' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6557' AS nis, 'ERINDA MARSELLINA PUTRI' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6565' AS nis, 'FERNINDA REVALINA PUTRI' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6577' AS nis, 'GHANESSA DWI NANDHARA' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6579' AS nis, 'GISYELLA OKTAFIANI' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6582' AS nis, 'HAIDAR ARDIANSYAH ZULFAIRUS' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6603' AS nis, 'JUVITA PUTRI VIJANATIN' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6627' AS nis, 'M. IRSYAD CAESAR MAY SARIA S.A.F' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6638' AS nis, 'MOCH. FADZRI ALAMSYAH' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6640' AS nis, 'MOH. ARFANI NUR HAKIM' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6647' AS nis, 'MOHAMMAD RIFANDIKA AKBAR KURNIAWAN' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6662' AS nis, 'MUHAMMAD RIDHO ARIFIN' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6667' AS nis, 'Nabila Mumtaz Sa''adah' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6671' AS nis, 'NAJWA ELISA WIJAYA' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6673' AS nis, 'NANDA PUTRI EKA RIYANTI' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6681' AS nis, 'NAZWA MELVIA PUTRI ZAHRA' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6682' AS nis, 'NENI WIDIANTI' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6696' AS nis, 'PRYTHA ERICA DWI RAHAYU' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6703' AS nis, 'RAHMADIRA JAYA SAHRUL MUNIR' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6730' AS nis, 'RIZKY PUTRA ADIANSYAH' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6732' AS nis, 'ROY DWI SAPUTRA' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6735' AS nis, 'SAIFUL ANWAR' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6737' AS nis, 'SANDY MAULANA' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6753' AS nis, 'SHERYL ISNANI SHAFA''AH' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6762' AS nis, 'SLAMET BAGUS TRI WAHYUDI' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6778' AS nis, 'VARRA SILVY OCTAVIANA' AS nama, 30 AS id_kelas
  UNION ALL
  SELECT '6451' AS nis, 'ADITYA GHANESA' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6453' AS nis, 'AGNES DEWI ANA' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6464' AS nis, 'ALDO DE FAIRUS' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6465' AS nis, 'ALEYSHA AZZAHRA AFANDY' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6486' AS nis, 'ARDIYANSAH' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6490' AS nis, 'AUGUSTIN RAHMA SETYAWAN' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6515' AS nis, 'CHELSEA PRIWIDYA PUTRI' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6520' AS nis, 'CHOIRUL NUR JAFARRUDIN' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6532' AS nis, 'DENI HERMANDA' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6560' AS nis, 'EVANA HUSNA AL WHAHDHA' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6562' AS nis, 'FACHRUL ICSHAN WIDYA PERMANA' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6576' AS nis, 'GANESA RENO APRILYAN' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6586' AS nis, 'IBNU IZZA AHSANI' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6595' AS nis, 'JEMI DWI MAHARANI' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6608' AS nis, 'KEVIN BARAKA GUSTI' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6617' AS nis, 'LANGIT DHIRYA ANGKASA' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6620' AS nis, 'LIFI REGINA KHANSA JAIDAH' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6632' AS nis, 'MARSYA PUTRI VALENTINA' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6644' AS nis, 'MOHAMAD WISNU NUR WACHID' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6645' AS nis, 'MOHAMMAD GALANG PUTRA PRATAMA' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6646' AS nis, 'MOHAMMAD KHOIRUL PRATAMA' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6807' AS nis, 'MUHAMMAD LUTHFI NUR RASYA' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6663' AS nis, 'MUHAMMAD RIZKY NUR JUNAIDI' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6669' AS nis, 'NAFFIZZA ZAHRA AVRYLIA' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6677' AS nis, 'NAURA ARZAPURY MICHAELA' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6679' AS nis, 'NAYLA FAIZA KUMAYROH' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6686' AS nis, 'NOBERTA AYUNING RAMADANI' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6693' AS nis, 'PARAMITHA SINTYA RAHMA AZZAHRA' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6718' AS nis, 'REYNA FALISHA ISMAIL' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6726' AS nis, 'RISQINA RIHAF ANANTA' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6736' AS nis, 'SALSABELLA MAULIDIA' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6741' AS nis, 'SAYYIDATUL LUTFIANA' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6746' AS nis, 'SEPTIAN RENDIANSYAH' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6750' AS nis, 'SHELLA AMANDA AULIA' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6770' AS nis, 'TEGUH DWI PRAMONO' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6774' AS nis, 'TYA NOVIKA PRAMBUDI' AS nama, 31 AS id_kelas
  UNION ALL
  SELECT '6444' AS nis, 'ABZY PRISHELLA BRILIAN' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6457' AS nis, 'AHMAD HASAN KARBALA' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6460' AS nis, 'AHMAD RIDHO FAHMI' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6473' AS nis, 'ANDINI AULIA PUTRI' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6476' AS nis, 'ANGGUN GITA SABRINA' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6503' AS nis, 'BIMO ACHYAR PUTRA WIBOWO' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6536' AS nis, 'DEVISTA NUR KALIMAH' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6539' AS nis, 'DIAN RATNA UTAMI' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6541' AS nis, 'DINDA AYU KANIA PUTRI' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6548' AS nis, 'DYAH AYU MAULYDA' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6561' AS nis, 'EVELYN MAUDYA ANDINI' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6570' AS nis, 'FITRI MEI LESTARI' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6591' AS nis, 'IZZA TUNAFSIA ISNAYNI' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6609' AS nis, 'KEYSA APRILLIA' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6616' AS nis, 'LAILA MAULIDA KHOLISHOTUR ROHMAH' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6631' AS nis, 'MAILITA DWI MAHARANI' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6633' AS nis, 'MEDICA PUJI LESTARI' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6643' AS nis, 'MOHAMAD RIZALUDIN' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6649' AS nis, 'MONIKA ALIA NUR AZIZAH' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6656' AS nis, 'MUHAMMAD HANDIKA PUTRA PRATAMA' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6674' AS nis, 'NASIKHATUL FADHILAH' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6675' AS nis, 'NASSANDRA MEI SELLA' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6684' AS nis, 'NINGTIYAS AGUSTIN' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6700' AS nis, 'RAFI SATRIYO WIDARTO' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6716' AS nis, 'REVA SRI WAHYUNI' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6717' AS nis, 'REVAN ADITYA PRATAMA' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6724' AS nis, 'RISMA PUTRI MAULIDYA' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6749' AS nis, 'SHAFRILIA HUMAWAN' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6754' AS nis, 'SHEVA RISQY FEBIYANTI' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6757' AS nis, 'SHINTA EKA FEBRIANI' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6767' AS nis, 'SYIFA AULIA NINGTYAS' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6768' AS nis, 'TANTI ELISHA SEKAR DINANTRI' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6775' AS nis, 'VALENTINA AMINARTI' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6787' AS nis, 'WAHYU DOSO GUSTOMO' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6790' AS nis, 'WIJI ELSA RIANA' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6797' AS nis, 'ZULFA AULIA ZAHRA' AS nama, 32 AS id_kelas
  UNION ALL
  SELECT '6450' AS nis, 'ADE NUR SYAIF' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6462' AS nis, 'AHMAD ZAKI AL HAFIDZ' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6463' AS nis, 'AJENG FEBRIANA ESA ARIANTI' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6466' AS nis, 'ALFINA NUR''AINI' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6472' AS nis, 'ANAYA FATIMATUN NIKMAH' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6481' AS nis, 'ANNISA PUTRI AULIA' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6484' AS nis, 'APRISKA NADIA PERMATASARI' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6487' AS nis, 'ARGA PRASETYAWAN' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6509' AS nis, 'CALISTA PUTRI RAHMAWATI' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6517' AS nis, 'CHIKA ARDINA SARI' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6521' AS nis, 'CICA FARESHA' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6522' AS nis, 'CIKA MUNA ALA CYA' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6531' AS nis, 'DELA NOVITASARI' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6568' AS nis, 'FIOLA PUTRI AMIRANDHANI' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6584' AS nis, 'HENI PRAMUDITHA ESA FITRI' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6585' AS nis, 'HIQMATUL MAQFIROH' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6589' AS nis, 'INTAN PUSPITA SARI' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6598' AS nis, 'JIHAN SEPTINA RAMADHANI' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6606' AS nis, 'KARINA MELIYANI PUTRI' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6653' AS nis, 'MUHAMAD FAREL ABDURROHMAN' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6664' AS nis, 'MUHAMMAD SULTHON MAHESA JAHNAN' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6668' AS nis, 'NADZUA PUTRI CHESILLIA' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6672' AS nis, 'NANA APRILIANA' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6690' AS nis, 'NUR AININ LUFIAH SUNGKAR' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6695' AS nis, 'PERMADANI INTAN MAULIDA' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6701' AS nis, 'RAHMA AZIZAH' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6710' AS nis, 'RASTA SHELVIA ARVANDA' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6711' AS nis, 'RATIH WAHYUNINGSIH' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6731' AS nis, 'RIZKY PUTRA RAMADHAN' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6733' AS nis, 'RYAN SANDI DANDER' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6751' AS nis, 'SHELLA PUTRI ANANTA' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6764' AS nis, 'SYAFIRA MAR''ATUS SHOLIKHAH' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6771' AS nis, 'TIARA ADIANI PUTRI PRATAMA' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6779' AS nis, 'VERLITA AYU NOVIYANTI' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6780' AS nis, 'VIKA EPRILIANA PUTRI' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6792' AS nis, 'YUSYA IRHAM MAULANA' AS nama, 33 AS id_kelas
  UNION ALL
  SELECT '6815' AS nis, 'AINUN AZIZAH' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '6821' AS nis, 'ALFIAN WAHYU NUR RAHMAN' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '6830' AS nis, 'ALYSHA ANGELINA MYRA PUTRI HAMBARA' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '6855' AS nis, 'ARTIKA TRINAUFALIA' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '6864' AS nis, 'AURELIA DEVIKA NATHANIA SARI' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '7169' AS nis, 'DANNIS ERFIANSYAH AUDHIKA SATYA' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '6888' AS nis, 'DARA DEKHA PRISDYAN' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '6890' AS nis, 'DELFINO PUTRA PRAMUDYA ISLAMI' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '6904' AS nis, 'DINDA PUTRI SULYA WINATA' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '6912' AS nis, 'EKA PUTRI ARIFIA' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '6923' AS nis, 'EVA YULIANI' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '6932' AS nis, 'FIFI JULIANAWATI' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '6937' AS nis, 'FLORIDA AULYA RAHMADHANI' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '6941' AS nis, 'HELLENA DEVIKA PRAMESTI' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '6949' AS nis, 'ILHAM ADIYATMA PRADIPTA AJI' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '6954' AS nis, 'IRMA MIFTAQHUL JANAH' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '6964' AS nis, 'KARTIKA LOKANANTA HENDRIYANTO' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '6987' AS nis, 'LUTHFIYAH NANDA KAYISAH' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '6993' AS nis, 'MARIA ROSALINDA NAITILI' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '7000' AS nis, 'MEIDITA ISNAINI LAIL' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '7028' AS nis, 'MUHAMMAD BISMA BRATA WIJAYA' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '7037' AS nis, 'MUKHAMAD WISNU SYAWAL' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '7054' AS nis, 'NAZWA CAHAYA MELATI' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '7056' AS nis, 'NEYZA APREILLINA NOORLIN AZZAHZIA' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '7061' AS nis, 'NIKEN NOVITA AGUSTIN' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '7063' AS nis, 'NIKMATUL QORIDAH' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '7067' AS nis, 'NOVI DWI AGUSTINA' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '7073' AS nis, 'NURUL HIDAYAH' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '7097' AS nis, 'Regina Alwi Evelyn Aurellia' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '7110' AS nis, 'RITAJ RAHMA HAQIQI' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '7124' AS nis, 'SHAFA AULYA NUR HIDAYAH' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '7125' AS nis, 'SHARON PUTRI FELISHIA' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '7133' AS nis, 'SISILIA WAHYU SALSA FIRNANDA' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '7145' AS nis, 'ULAN SRI RAHAYU NINGSIH' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '7153' AS nis, 'WIJI DYAH AYU SARI' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '7163' AS nis, 'ZASKIA PUTRI IMELDA' AS nama, 14 AS id_kelas
  UNION ALL
  SELECT '6810' AS nis, 'ADAM WIRADYTA FIRDAUS AKBAR' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '6811' AS nis, 'AFRILEA CHAERUNNISA' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '6824' AS nis, 'ALISYA ADELLEO REGINA PUTRI' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '6828' AS nis, 'ALVINDA NASYA MABELVA' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '6838' AS nis, 'ANGGI DWI NUR ALIFFAH' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '6848' AS nis, 'ARAFA TRI AQSO' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '6850' AS nis, 'ARDIANSYAH YOGA PRATAMA' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '6860' AS nis, 'AULIA MEI NING TYAS' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '6866' AS nis, 'AYU DWI LESTARI' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '6870' AS nis, 'AZKA IMELDA ATTAMEVIA' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '6873' AS nis, 'BELVA FATIKHA BELINDA PRASITHA' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '6902' AS nis, 'DIKA TRISDIAN AHMADI' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '6933' AS nis, 'FINA OKTAVIA' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '6935' AS nis, 'FITRIAN DIMAS SAPUTRA' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '6940' AS nis, 'HANINA RAHMATUL AFIKA' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '6948' AS nis, 'IKA SALSABILA FAJARINA' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '6957' AS nis, 'JIEHAN PUTRI CANTIKA NANDA' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '6960' AS nis, 'KANAYA AL TAFUNISA' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '6972' AS nis, 'KHOIRUNNISA'' NURIL QOLBI' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '7001' AS nis, 'MELISA OKTAVIA' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '7005' AS nis, 'MOCH. DEVINDA ARJUNA PUTRA' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '7033' AS nis, 'Muhamad Joko Nugroho' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '7026' AS nis, 'MUHAMMAD ANDHIKA PRATAMA' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '7041' AS nis, 'NADILLA ROTUL WINANDAR' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '7042' AS nis, 'Nadivatur Rohmah' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '7078' AS nis, 'PUTRI AYU WULANDARI' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '7080' AS nis, 'PUTRI SUGMA FAIZAH' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '7083' AS nis, 'QIRANI AURELYA NINGRUM' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '7090' AS nis, 'RARA AGUSTIN RIZKI ARIATIN' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '7122' AS nis, 'SELVIA RAHMAWATI' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '7123' AS nis, 'SERLY WIDYA WATI' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '7131' AS nis, 'SILVIA KESA NAFTALIN' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '7132' AS nis, 'SINTIA AINURRAHMA' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '7143' AS nis, 'TASYA REYHANA RIZQULLAH' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '7144' AS nis, 'TISYA ELFRIDA RAMADHANI' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '7156' AS nis, 'YUANITA APRILIA PUTRI' AS nama, 15 AS id_kelas
  UNION ALL
  SELECT '6829' AS nis, 'ALVIONA YOERI PRASTIKA PUTRI' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '6831' AS nis, 'Amel Cahya Sifana' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '6833' AS nis, 'ANA MAMLAATUL BIRRI' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '6836' AS nis, 'ANASTASYA IVE MAYLINDA' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '6845' AS nis, 'APRILIA NUR AINI' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '6857' AS nis, 'ARVIA SALSABELLA CANTIKA' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '6882' AS nis, 'Cintya Rofi''atul Sa''diyah' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '6885' AS nis, 'DANANG BIMA PRASTYO' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '6905' AS nis, 'DINDA PUTRI WAHYUNI' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '6906' AS nis, 'DITA NOVITA DWI ANDRIANI' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '6917' AS nis, 'ELVIANA ELIZABETH' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '6929' AS nis, 'FEBRIANO DIMAS PRATAMA' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '6934' AS nis, 'FIRSTINA IFFAH AYDIN KYNA' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '6936' AS nis, 'FITRIAN REHAN SAPUTRA' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '6942' AS nis, 'HERFIA RINDY ZAHRANIA' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '6943' AS nis, 'HESTI MELINDA' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '6946' AS nis, 'IKA ESTI ALIFIA' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '6988' AS nis, 'M. RIDHO AGANATA' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '6991' AS nis, 'MANDALA CHIKO FEBRYANO' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '6999' AS nis, 'MEGA AYUDEA RAHMA SAFARA' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '7003' AS nis, 'MIFTAHUL SEVINDA SUCI DWI VIGIANTI' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '7023' AS nis, 'MUFID MUJI RAHAYU' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '7040' AS nis, 'NABILLA RISTI FAUZYA' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '7044' AS nis, 'NAIMA RAHMAWATI' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '7060' AS nis, 'NIKEN CAHAYANING KUMALASARI' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '7064' AS nis, 'NIKO AHMAD ARISON' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '7071' AS nis, 'NUR KHOLIFAH' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '7081' AS nis, 'PUTRI WIDYA NUR AINI' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '7089' AS nis, 'RAKA DWI WIBOWO' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '7092' AS nis, 'RATIH RORO DEWANTI' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '7093' AS nis, 'RATNA PUSPITASARI' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '7109' AS nis, 'RISMA KURNIA PUTRI' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '7118' AS nis, 'SANDY PUTRA PRATAMA' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '7129' AS nis, 'SHINTIA ANINDYA INDRIANI' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '7138' AS nis, 'SULISTYA RAHMA' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '7161' AS nis, 'ZAHRA FIKRIA RAHMAWATI' AS nama, 16 AS id_kelas
  UNION ALL
  SELECT '6809' AS nis, 'ACHMAD DARUL ILMI' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6812' AS nis, 'AGNIKA AURA PRATIWI KUSPRAYITNO' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6818' AS nis, 'AKHMAD ZAKI RIVALDO' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6827' AS nis, 'ALVIN ZIDNA FAQIH' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6835' AS nis, 'ANANDA BAYU ADHE PRASETYA' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6837' AS nis, 'ANGGA EKA RADITYA' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6840' AS nis, 'ANINDA PUTRI RAHMADANI' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6849' AS nis, 'ARDIAN HABIB AL RASYID' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6879' AS nis, 'CHIKA ALTOFUN NUR ROHMAH' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6897' AS nis, 'DHELA PUTRI GIRIYANTI' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6910' AS nis, 'DZAKY ALHASANI' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6911' AS nis, 'EKA NOVA ANGGRAINI' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6913' AS nis, 'EKA WIDIYAWATI' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6920' AS nis, 'ERIKO DWI DESTANYO' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6922' AS nis, 'EVA MASLIKHATUL LUTFIYAH' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6925' AS nis, 'FAIZATUL VERLINDA ANGGRAINI' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6939' AS nis, 'HAFIDZ ZULFADLI ANNAUVAL' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6950' AS nis, 'INDAH NUR''AINI' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6961' AS nis, 'KANAYA AQUILINA WILLY' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6971' AS nis, 'KHESYA FREDERIKA PUTRI' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6984' AS nis, 'LUCKY DHARMA ALMUBAROK' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6990' AS nis, 'MAIA ZHAHROTUL KHUSNA' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6996' AS nis, 'MARSYA AULIA PANGESTU WULANDARI' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '7006' AS nis, 'MOCH. FADLI MAULANA AL AMIN' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '7008' AS nis, 'MOH. FAJAR PUTRA HANDOKO' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '7016' AS nis, 'MOH. RIZKY FADILAH' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '7022' AS nis, 'MOHAMMAD FARHAN ABIDIN' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '7025' AS nis, 'MUHAMMAD ALFARIS SIDDIQ' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '7029' AS nis, 'MUHAMMAD DARRELL AR RASYID' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '7031' AS nis, 'Muhammad Faaiz Abdul Aziiz' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '7048' AS nis, 'NATANIA ANARA EKA EFFENDI' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '7058' AS nis, 'Niba''ur Rizky Hakim' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '7062' AS nis, 'NIKMATUL AULIYA' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '7070' AS nis, 'NOVITA ALFINATUZ ZAHRO' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '7112' AS nis, 'ROLAND ADMAJA YUNSA BAKHTIAR' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '7154' AS nis, 'WILDANI ARROFAAH' AS nama, 17 AS id_kelas
  UNION ALL
  SELECT '6814' AS nis, 'AHMAD IRJA NAJA' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '6817' AS nis, 'AJENG FARADILLA NIRMALA' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '6834' AS nis, 'ANA TRIJAYANTI' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '6854' AS nis, 'ARJUNA IQBAL RINOERISTIAN' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '6863' AS nis, 'AULYA PUTRI LESTARI' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '6872' AS nis, 'BAYU SEPTIAN RAMADHANI' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '6875' AS nis, 'CALVIN ADRIAN MAULANA' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '7166' AS nis, 'CLARISSA RICHA PRAMESTI' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '6909' AS nis, 'DYLLA NUR ALIFAH' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '6930' AS nis, 'FEBRIANTY PRASTINA PUTRI' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '6951' AS nis, 'INDRI AMELLIA SARI' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '6959' AS nis, 'Johan Fran''s Alfaro' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '6966' AS nis, 'KEVIN NUR ALMETSA' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '6970' AS nis, 'Khansa Qurratul''ain' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '6975' AS nis, 'KRISNANDA NUR ROHIEM' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '6979' AS nis, 'LEVINA SELA ARDIYANTI' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '6983' AS nis, 'LOVEFANY NURKURNIA DEWI' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '6994' AS nis, 'MARIO WIDHO PRATAMA' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '6998' AS nis, 'MAULIDYAH ASKAKOFA ALHAQIQI' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '7168' AS nis, 'MOCH. DEBY RISKY ZULIAN' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '7007' AS nis, 'MOH. ARSANDI MAULANA RAFI' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '7015' AS nis, 'MOH. RIFKI NADZHIF KARIFIN' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '7039' AS nis, 'MUSYAFA FARUQ NABIL AFIFUDIN' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '7055' AS nis, 'NESYA PUTRI SILVIANA' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '7074' AS nis, 'PASHA RADITYA SUGARA' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '7075' AS nis, 'PHEBE LAYLA FITRIANI' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '7076' AS nis, 'PRISCHA YUANITA HARTANTI' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '7077' AS nis, 'PRIYAMBODO DWI UTOMO' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '7079' AS nis, 'PUTRI HANDAYANI' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '7086' AS nis, 'RAFAEL DEROSA WILDAN HADANA' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '7117' AS nis, 'SANDI FIKRI MULYA' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '7126' AS nis, 'SHERINA OKTAVIANI' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '7140' AS nis, 'SYAFA ALICIA NUR KHOLIFA' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '7152' AS nis, 'WIDIYA NUR AZIZAH' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '7158' AS nis, 'ZAFRAN RAFIF SUWANDONO' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '7164' AS nis, 'ZELCO FALENTSYELO ZESTENLY' AS nama, 18 AS id_kelas
  UNION ALL
  SELECT '6822' AS nis, 'ALIF YAHYA NURAINI' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '6832' AS nis, 'AMELIA DIAN RAHMAWATI' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '6862' AS nis, 'AULIANA ULIN NIKMAH' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '6868' AS nis, 'AYUDYA MEYSIFA RANI PUTRI' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '6878' AS nis, 'CHALLYSTA ZHAFAF AHNAZ' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '6883' AS nis, 'CITRA DWI PUSPITA SARI' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '6892' AS nis, 'DESTISTA WIRATNA' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '6952' AS nis, 'INTAN BELA FEBRIANY' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '6967' AS nis, 'KEYLA YUAN' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '6974' AS nis, 'KINTANAYA SHAFIYA HAFIRDA' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '6981' AS nis, 'LIVIA SRI WAHYUNI' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '6982' AS nis, 'LIVIANA ZASKIA PUTRI' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '6992' AS nis, 'MARETTA ANGELLINA SAPUTRI' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '6997' AS nis, 'MAULANA YOGA WIBAWA' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '7002' AS nis, 'MEYSYA RAHMATUL AZZA' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '7010' AS nis, 'MOH. HARIANTO' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '7020' AS nis, 'MOHAMMAD BAGUS ROMADHONI' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '7021' AS nis, 'MOHAMMAD BAYU SUKMA MAHENDRA' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '7032' AS nis, 'MUHAMMAD HAMDI AZAM' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '7036' AS nis, 'MUHAMMAD UBAIDILLAH' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '7043' AS nis, 'NADYA DEWI CALLISTA' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '7047' AS nis, 'NANIK LIVIANI' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '7051' AS nis, 'NAZALEA FEBI' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '7072' AS nis, 'NURIL MUSLIMAH' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '7084' AS nis, 'RADITA FAJAR PRASETYO' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '7098' AS nis, 'REGINA YUANDITA PRAYOGA' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '7107' AS nis, 'RISCO TIRTA ARDIANSYAH' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '7108' AS nis, 'RISMA AMALIA' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '7121' AS nis, 'SEKAR RORO WELIES' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '7127' AS nis, 'SHIFA NUR AINI' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '7146' AS nis, 'ULFA ZAHROTUL MAULA' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '7147' AS nis, 'UMMI NUR RAUDHATUL JANNAH' AS nama, 19 AS id_kelas
  UNION ALL
  SELECT '6820' AS nis, 'ALFIA ANGRAINI' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '6841' AS nis, 'ANINDYA AZZAHRA PUTRI IRAWAN' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '6844' AS nis, 'ANITA DWI OKTAVIA' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '6847' AS nis, 'APRILIANA SINAR SAPUTRI' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '6856' AS nis, 'ARUM ALVIRA' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '6886' AS nis, 'DANIEL LIM YONG SING' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '6903' AS nis, 'DINDA AZKYA RATRI' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '6907' AS nis, 'DWI AGUNG PURNOMO' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '6915' AS nis, 'ELOK WILUJENG BRILLIANDANI' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '6918' AS nis, 'ELZA CAHYA ISMAUL JULQO''IDAA' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '6928' AS nis, 'FAZA ZULFI NUR IZAMI' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '6947' AS nis, 'IKA PUTRI WAHYUNING TYAS' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '6955' AS nis, 'Izalatul Muqho''iroh' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '6958' AS nis, 'JIHAN DIVA SHEFANY PUTRI' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '6973' AS nis, 'KIKI HANDARUM SARI' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '6976' AS nis, 'LAILA DEVI PUSPITASARI' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '6986' AS nis, 'LUTFI ANA PUTRI FALINTINA' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '7017' AS nis, 'MOH. WILDAN ARIFIN' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '7018' AS nis, 'MOHAMAD DAFA ADLY FAUZAN' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '7030' AS nis, 'MUHAMMAD DWI ZAKARIA' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '7035' AS nis, 'MUHAMMAD SYAFAUL' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '7038' AS nis, 'MULYANDHARI RAHAYUNINGTYAS' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '7057' AS nis, 'NIA ALIFATUL ATIKA' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '7066' AS nis, 'NOVALIA ANGGUN PRIHASTIKA PRATIWI' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '7082' AS nis, 'PUTRI ZAHWANA TAUFANI' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '7087' AS nis, 'RAHMA CITRA NAZWA DEWI' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '7100' AS nis, 'RENDY BAGUS PRATAMA' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '7113' AS nis, 'SABNA RAHMA SARI' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '7128' AS nis, 'SHIFA NUR RAMADHANI' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '7151' AS nis, 'VIORISTA ADI VEMIKA' AS nama, 20 AS id_kelas
  UNION ALL
  SELECT '6808' AS nis, 'ABIMANYU PUTERA WICAKSONO' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '6813' AS nis, 'AHMAD ASHAR DODI AL FAYIL' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '6816' AS nis, 'AINUN ZULFA DWI AZIZAH' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '6826' AS nis, 'ALUNA NAOMI AGATA' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '6839' AS nis, 'ANGGRAINI PUTRI WULANDARI' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '6867' AS nis, 'AYU FITRIA LESTARI' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '6893' AS nis, 'DEVITA PUTRI FEBRIANI' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '6894' AS nis, 'DEVY FEBRIANA MERLYN PRABOWO' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '6896' AS nis, 'DEWI NUR MALASARI' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '6900' AS nis, 'DIAH AYU NOVIATUL FAJRIAH' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '6931' AS nis, 'FICO ARDIS FERNANDO' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '6945' AS nis, 'ICASIA AULIA IVANA' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '6985' AS nis, 'LULUK LAILATUS SYIFA' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '7004' AS nis, 'MIFTAKHUL JANNAH' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '7024' AS nis, 'MUHAMAD YUSUF HANAFI' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '7027' AS nis, 'MUHAMMAD ARIYA BIMA AL HAFIDZ' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '7050' AS nis, 'NAYLA AZKIYA DHIYA Y P' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '7052' AS nis, 'NAZRIL ILHAM FAJAR RIZKY' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '7069' AS nis, 'NOVIANDRI DWI PRASTIWI' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '7088' AS nis, 'RAHMA PRABOWO' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '7095' AS nis, 'RAZAN YAZID ILLMANY' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '7096' AS nis, 'REFINAZWA DWI SALSABELA PUTRI' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '7099' AS nis, 'RENDI WAHYU PRATAMA' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '7101' AS nis, 'REYCI SEPTIYASIH' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '7102' AS nis, 'REYVAN RADITYA FAHREZZA' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '7105' AS nis, 'RIFANA EKA MARDIA VIOLITA' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '7106' AS nis, 'RIRIN YUNIARTI' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '7116' AS nis, 'SAMUEL RHICARDO SYAPUTRA' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '7141' AS nis, 'SYAFAATUN NURKHOTIMAH' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '7160' AS nis, 'ZAHRA ESYA CORDELIA' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '7162' AS nis, 'ZASKIA EKA SINTIASARI' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '7170' AS nis, 'RIA YUANITA ESTI' AS nama, 21 AS id_kelas
  UNION ALL
  SELECT '6825' AS nis, 'ALTHAF ATAYA FIRZATULLAH HERMAWAN' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '6843' AS nis, 'ANINDYA RASYA PUTRI' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '6858' AS nis, 'ASTUTI LISTYAWATI' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '6859' AS nis, 'AUFA ASYIFATUN NAZWA' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '6876' AS nis, 'CARLY JECIKA PUTRI' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '6880' AS nis, 'CINDY ALISYA FEBRIANTY' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '6914' AS nis, 'ELFA NURIL CHOFIFAH' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '6916' AS nis, 'ELSA REFFI ALIVIANA' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '6919' AS nis, 'ERICA JESIE NOVITRIANI' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '6921' AS nis, 'ETHAN ARFIANSYAH PUTRA NINO' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '6926' AS nis, 'FAMANIHA AAISYI FARHA' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '6927' AS nis, 'FARIDA EKA JULIANTI' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '6944' AS nis, 'HUSNA RAHMADANIA' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '6965' AS nis, 'KAYLA SEKAR AZURA' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '6968' AS nis, 'KEYSHA AULIA PUTRI' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '6969' AS nis, 'KHANSA ALYA HAMIDAH' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '6977' AS nis, 'LAILI ALFI SYAHRIYATI' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '6989' AS nis, 'M. WILDAN NUR SHOLEH' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '7014' AS nis, 'Moh. Rifai' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '7019' AS nis, 'MOHAMAD SYAHRUL OKTAPIAN' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '7059' AS nis, 'NIKE DAFARA' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '7068' AS nis, 'NOVI NUR AZIZAH' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '7104' AS nis, 'RIFA PUTRI AISYANDA' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '7114' AS nis, 'SAFIRA LAILATUS SYIFA''' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '7119' AS nis, 'SATRIA BINTANG MUDA ANUGERAH GUSTI' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '7120' AS nis, 'SATRYA SETIO PENGGALIH' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '7130' AS nis, 'SILFIYA WAHYU YUNIANTO' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '7135' AS nis, 'SITI RAHMATUL ERVINA SARI' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '7137' AS nis, 'SUKMA ROHMATIN ULYA' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '7139' AS nis, 'SULTAN DEWANTORO' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '7142' AS nis, 'SYARAH FAUZIAH' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '7148' AS nis, 'VANDANA TRI ASMORO' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '7150' AS nis, 'VERLITA VALENCIA ARTHAMEIVIA' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '7155' AS nis, 'YAYUK SETYANINGTYAS' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '7157' AS nis, 'YUDHA ABRIAN TRI PUTRA SAMUDRA' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '7165' AS nis, 'ZHIVARA ALMIRA PUTRI' AS nama, 22 AS id_kelas
  UNION ALL
  SELECT '6819' AS nis, 'AL MIRA AZMI RAMANDHANI' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '6846' AS nis, 'APRILIA PUTRI ALINI' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '6851' AS nis, 'ARDILA' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '6852' AS nis, 'ARDINA FEBRIANI' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '6865' AS nis, 'AURELLIA GLADYS FLORENZA' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '6869' AS nis, 'Azizah Nur '' Aini' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '6874' AS nis, 'BILQIS KATALIA AZURA' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '6877' AS nis, 'Chalisa Zulva Atira' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '6881' AS nis, 'CINDY AMALIA' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '6884' AS nis, 'DAKA RONGGO PANGESTU' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '6889' AS nis, 'DAVINA NUR LAYLIYAH' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '6891' AS nis, 'Della Trihandita Medianti' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '6895' AS nis, 'DEWI MUTHOHAROH KHOLIFATHUL JANNAH' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '6898' AS nis, 'DHIKO DWI SASMITO' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '6901' AS nis, 'DIEDA RAMASYEP HAZAMISLAM' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '6938' AS nis, 'GALANG RAMADHAN' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '6953' AS nis, 'IRA FADHILA' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '6962' AS nis, 'KARISMA AYUDIA' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '6963' AS nis, 'KARISSA AYU ARDITA' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '6978' AS nis, 'LAYLUNA KHEYZA PUTRI WIJAYA' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '6980' AS nis, 'LIDIYA DIYAH PUSPITA' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '6995' AS nis, 'MARISA TRIOKTAVIANA' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '7011' AS nis, 'MOH. RENO FEBRIANTO' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '7012' AS nis, 'MOH. REVAN ARDIAN SANTOSO' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '7013' AS nis, 'Moh. Ridwan Andrian' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '7049' AS nis, 'NAYARA AURA SAHILA KARIN' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '7053' AS nis, 'NAZWA AISIA ANDINI KHOIRUNNISA' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '7065' AS nis, 'NOLADHEA FRITA DEVI' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '7085' AS nis, 'RAFA ARDYANZAH' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '7091' AS nis, 'RASTY PUTRI RATIFA' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '7094' AS nis, 'RAYHAN DZAKI HABIBULLOH' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '7103' AS nis, 'REZA NOVA PRATAMA' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '7115' AS nis, 'SALSABILA NOOR RAISYA' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '7134' AS nis, 'SITI ADINDA PUTRI OKTAVIA' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '7136' AS nis, 'SIVA SUKMA TABITA' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '7149' AS nis, 'VEGA AULIA FEBRIANA' AS nama, 23 AS id_kelas
  UNION ALL
  SELECT '7176' AS nis, 'ADITYA FATHAN RAZLIN FAZA AKHDAN' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7180' AS nis, 'AILSA AZMIARDINE YAJNA ZAHWA' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7184' AS nis, 'AISYA CINTA ASYIFA' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7185' AS nis, 'AISYA WAHYU HAYUNING DYAS' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7196' AS nis, 'ALYA JUNIKA MAWARDY' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7206' AS nis, 'ANGGITA MORENDA JULIANITA ERDA' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7229' AS nis, 'AYU WULAN RAHMADANI' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7260' AS nis, 'DHEVI PUSPITASARI' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7269' AS nis, 'EKA DUWI WULANDARI' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7278' AS nis, 'ELYA REGINA PUTRI' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7282' AS nis, 'EZAR FEBRYAN NADITAMA' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7284' AS nis, 'FADEL AHMAD HANGGARA' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7286' AS nis, 'FAHIRA PUTRI SUKMA WIBOWO' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7303' AS nis, 'GHAFIKY GESANG SURYA ALAMSYAH' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7313' AS nis, 'ISABELLA ANGGRAINI NUR AINI' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7334' AS nis, 'LAURA SINTIA MEI MAHESA' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7337' AS nis, 'LIDYA AZZAHRA APRILIA' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7342' AS nis, 'Lutfi Rizki Zulfana' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7349' AS nis, 'M AZAM ATALI' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7353' AS nis, 'M ZIDAN NAZRIL YUDA' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7368' AS nis, 'MOCHAMAD DIKQI FIRMAN SYAH' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7378' AS nis, 'MOHAMAD TEGAR BAGUS PRATAMA' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7403' AS nis, 'MUHAMMAD SALMAN RIZQULLAH' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7437' AS nis, 'OKTARI NAILA SALSABILA' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7439' AS nis, 'OLAF REYHAN ADJI' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7448' AS nis, 'PUTRI FATHANIA IRAWAN' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7461' AS nis, 'RAUF DAFFA FAWWAZI' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7463' AS nis, 'RETNO AYU SAFITRI' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7467' AS nis, 'REZI AKBAR AZADHA' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7473' AS nis, 'RISQI TAHMIDIN AKBAR' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7491' AS nis, 'SILVIA DZUROTUN NAFISAH' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7511' AS nis, 'YAHYA KEVIN SAPUTRA' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7516' AS nis, 'YUSRINA AIDA FAUZIAH' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7518' AS nis, 'Zahra Izza Aulia' AS nama, 4 AS id_kelas
  UNION ALL
  SELECT '7175' AS nis, 'ADITIA SORYA SAPUTRA' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7186' AS nis, 'AISYAH HAWANI' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7188' AS nis, 'ALDY PRIMA SAPUTRA' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7189' AS nis, 'ALFIANA MASKURIN' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7207' AS nis, 'ANGGUN RESTIANA ARIANTO' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7210' AS nis, 'APRILIA PUTRI ANDARISTA' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7218' AS nis, 'ARUM FELCIA WAFIYAH' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7222' AS nis, 'AULIA AINUR ROCHMAH' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7232' AS nis, 'BERLIANA SANI PUTRI ARTILERI' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7237' AS nis, 'BUNGA NAZWA PRASETYA PUTRI' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7238' AS nis, 'BUNGA NONIKA' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7252' AS nis, 'DESTA RAHMA ISLAMAYA' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7255' AS nis, 'Deva Setiawan Wijaya' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7257' AS nis, 'DEVI INDAH NUR AINI' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7265' AS nis, 'Dimas Permana Widodo' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7301' AS nis, 'GALUH MAHESTI CITRA LOKHA' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7302' AS nis, 'GANESHA BINTANG NUR RAMADHANIA' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7326' AS nis, 'KARISA PUTRI ANGGRAINI' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7328' AS nis, 'KEYLA DWI ANGGRAINI' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7341' AS nis, 'LUSIANA SAFERA' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7350' AS nis, 'M GALANG SYAPUTRA' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7354' AS nis, 'M. ROBHET ZAMZAMI' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7358' AS nis, 'MEILINDA DWI RAHMA' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7362' AS nis, 'MIA MARSHELA' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7372' AS nis, 'MOH ASYRAFFA HAKIM' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7396' AS nis, 'MUHAMMAD JIHAN RAMADHAN' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7397' AS nis, 'MUHAMMAD JOVI REDIANSYAH' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7436' AS nis, 'Nuril Putri Aisyah' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7452' AS nis, 'RADHITYA ZOHAR PRASETYO' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7453' AS nis, 'RADITHYA NANDO ARJUNA' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7456' AS nis, 'RAHMA NAZZILATUL HUZNA' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7486' AS nis, 'SEPTIANSYAH RIZKY DARMAWIJAYA' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7490' AS nis, 'SILVIA AULIA DIVA ANGGRAINI' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7492' AS nis, 'SILVIA RAHMAWATI' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7509' AS nis, 'WAHYU TRI HARIANTO' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7517' AS nis, 'YWANA MUKTI' AS nama, 5 AS id_kelas
  UNION ALL
  SELECT '7171' AS nis, 'ABI SAID MAULANA AFANDI' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7177' AS nis, 'Aditya Rizky Pratama' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7182' AS nis, 'Aini Nur Hayati' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7191' AS nis, 'ALFYAN ADI NUGARA' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7200' AS nis, 'AMELIA PUSPANINGRUM' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7217' AS nis, 'ARTIKA JULIA RACHMA' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7233' AS nis, 'BIMMA CAHYA PUTRA' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7243' AS nis, 'CITRA AULIA SYAFIRA' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7251' AS nis, 'DEPRI AHMAD CHOIRUDDIN' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7256' AS nis, 'DEVA TRI SEPTYA RAHAYU' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7266' AS nis, 'DINARSA ABIMA ROCHMAN ENADI' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7267' AS nis, 'DITA CANTIKA PRATAMA' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7275' AS nis, 'ELSA RIZQI FEBRIANTI' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7289' AS nis, 'FARAH OKTAFIANA NADROTUL KAMILAH' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7299' AS nis, 'FLORYNS CLEOVATA HERAEL' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7304' AS nis, 'GILANG PANDHU WIJAYA' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7306' AS nis, 'GUNTUR DWI UTOMO' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7312' AS nis, 'INGGRID LARUSITA' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7319' AS nis, 'JIHAN HAMIDAH HASIBAH TANJUNG' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7320' AS nis, 'JUNIOR AGIL AJI SYAHPUTRA' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7322' AS nis, 'Kalimatus Sadiah' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7330' AS nis, 'KEYSA AMELIA PUTRI' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7332' AS nis, 'KHOIRUN NISA' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7363' AS nis, 'MICHELLE DWI TIFANY' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7388' AS nis, 'MUH ALFIAN RIZKI FATHUR RAHMAN' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7391' AS nis, 'MUHAMMAD AFAN AL MUBAROK' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7413' AS nis, 'NADYA TRYAS APRILIA CINDY' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7416' AS nis, 'NAILA RAMADHANI PUTRI IMALA' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7438' AS nis, 'OKTAVIRA WIDIANINGRUM' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7443' AS nis, 'PUTRA RISKI ANUGERAH JONATAN' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7466' AS nis, 'REYHAN DIKA ANDRIANSYAH' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7470' AS nis, 'RIMA ALUN NASYIFA' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7494' AS nis, 'SITI AL ADAWIYAH' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7505' AS nis, 'VERA OKTA AURELLIA' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7508' AS nis, 'VIVIAN RISMAYA CAHYANI' AS nama, 6 AS id_kelas
  UNION ALL
  SELECT '7181' AS nis, 'AILSA NAOMI CANDRA LESTIYANING TYAS' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7192' AS nis, 'ALIEFVIA MEISAATUS SHOLEKHA' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7198' AS nis, 'ALYSIA PUTRI NABILA' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7225' AS nis, 'AULIA NUR ''AINI' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7228' AS nis, 'AYU MELVINA SARASWATI' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7234' AS nis, 'BINTANG CAHYA RIYADI' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7235' AS nis, 'BRAYEN PUTRA ADITYATAMA' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7239' AS nis, 'CALVIN MICHAEL CHRISTIAN' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7250' AS nis, 'DENDY ADITYA PRATAMA' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7253' AS nis, 'DESTRIANA AULIA DEWI' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7262' AS nis, 'DIAH AYU PUSPA SARI' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7272' AS nis, 'EKA RAMADA WIRADARMA' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7283' AS nis, 'Fachri Nizar Hasibuan' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7293' AS nis, 'FERLY NABIL RIZKIPRAMUDYA' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7295' AS nis, 'FIRDA AMELIA PURNAMA' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7316' AS nis, 'ISNAINI INDRIAN' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7321' AS nis, 'KAFEEL SAFARAS AL FATH' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7324' AS nis, 'KARINA ARUM SARI' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7329' AS nis, 'KEYRA RAHMADANI PERMATA UTOMO' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7338' AS nis, 'LINDA ANISSATUR ROHMAH' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7340' AS nis, 'LULUK NURAINI' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7351' AS nis, 'M GILANG WAHYU PRATAMA' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7370' AS nis, 'Mochamad Zamzami Aldi' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7386' AS nis, 'MUCHAMAD CHAIRUL ANAM' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7390' AS nis, 'MUHAMAD BRAMAS ADITA RISMAWAN' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7400' AS nis, 'MUHAMMAD RAMA OKTAFIYAN PRATAMA' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7404' AS nis, 'MUTIARA AYU RAMADHANI' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7415' AS nis, 'NAFISA NURIL AULIA' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7449' AS nis, 'PUTRI IRENE MANIHURUK' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7459' AS nis, 'RASENDRIYA NURAINI' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7481' AS nis, 'SAFIRA SALSABILLA' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7484' AS nis, 'SELLY MARTA NURALYA ANGGRAINI' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7485' AS nis, 'SEPTIANA FITRI NUR ROHMAH' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7500' AS nis, 'SYAFIRA AZTI OKTAVANIA' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7515' AS nis, 'YUDA ARIYA SAPUTRA' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7519' AS nis, 'ZALFA NURI DWI HAPSARI' AS nama, 7 AS id_kelas
  UNION ALL
  SELECT '7195' AS nis, 'ALVIN FEBRIANSYAH' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7202' AS nis, 'Ana Firda Ainur Rohmah' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7209' AS nis, 'APRILIA ERSA MELINDA' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7212' AS nis, 'APRILIA SINTA NURHAYATI' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7216' AS nis, 'ARNEZ LAURENZIA PRAYITNO' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7220' AS nis, 'ASYIFA PUTRI MEILANI' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7240' AS nis, 'CANTIKA PUTRI AGUNG CAHYANTI' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7244' AS nis, 'DAFIN PUTRA ANGGONO' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7248' AS nis, 'DEFINA DWI SAFITRI' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7259' AS nis, 'DHAMARA AIRLANGGA AL FATH' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7263' AS nis, 'Dibya Manggala Widagda' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7281' AS nis, 'EVELYN NOUFA SUSANTI' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7291' AS nis, 'FATIMATUZZAHRA' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7292' AS nis, 'FAZZA FERNANDA ARKANA' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7315' AS nis, 'ISNAINI DUROTUL MAHNUNIN' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7352' AS nis, 'M NASUHA AL MUBAROK' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7356' AS nis, 'MAULANA NAZJWAN ARRAFFI' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7360' AS nis, 'MELINDA YUANITA PERMATA SARI' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7379' AS nis, 'MOHAMMAD DAFFA ISHAK' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7389' AS nis, 'MUHAMAD ARIS RIYANTO' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7399' AS nis, 'MUHAMMAD RAEHAN FIRMANZAH' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7401' AS nis, 'MUHAMMAD RIDWAN' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7407' AS nis, 'NABILA PUTRI EFENDI' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7418' AS nis, 'NANDA BAGAS ARIYANTO' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7422' AS nis, 'NAZWA AULIA RIZKY' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7428' AS nis, 'NOVAL DIYAN BAYU SAPUTRA' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7431' AS nis, 'NOVIA ADINDA' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7442' AS nis, 'PUTRA BARAP SYAHENSYA' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7445' AS nis, 'PUTRI AYU FANESA' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7478' AS nis, 'RIZKY APRILIA KARTIKA' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7487' AS nis, 'SHABRINA PUTRI AGUSTINA' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7489' AS nis, 'SIFANA AYU CITRA KARINA' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7493' AS nis, 'SINTA MUTIA SETIA RAMADHANI' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7514' AS nis, 'YUANITA NUR HAPSARI' AS nama, 8 AS id_kelas
  UNION ALL
  SELECT '7173' AS nis, 'ACHMAD FARIS ADI WICHAKSONO' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7179' AS nis, 'AHMAD RIZKY ADITYA' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7183' AS nis, 'AIRA FATMAWATI' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7242' AS nis, 'CITRA AULIA SAFITRI' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7249' AS nis, 'DELFIA AFDIRA PUTRI' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7258' AS nis, 'DEWI MELVINA SARASWATI' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7271' AS nis, 'EKA NUR MAULIDA' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7297' AS nis, 'FIRLY ARUM PRATIWI' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7300' AS nis, 'GALUH MAHA DEWI' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7308' AS nis, 'HIFA ZURAIDAH' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7311' AS nis, 'INDAH AYU WULANDARI' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7327' AS nis, 'KEISHA DIVA NADIA' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7336' AS nis, 'LEONI REZQI AULIA' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7339' AS nis, 'LUCKY PUTRA SYABANA' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7367' AS nis, 'MOCHAMAD ALFIAN MUBAROQ' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7371' AS nis, 'MOCHAMMAD DELON YUAN PRATAMA' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7382' AS nis, 'MOHAMMAD RAFI' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7384' AS nis, 'MUALLIFATUZ ZAHROK' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7394' AS nis, 'MUHAMMAD DZULFIKAR RASYID' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7409' AS nis, 'NABILLA PUTRI APRIATIN' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7412' AS nis, 'NADIN NUR AZILLIA' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7423' AS nis, 'NEVIILL RICHARD ILLANA' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7425' AS nis, 'NIA PUTRI WULANDARI' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7446' AS nis, 'PUTRI AYU SHOLEKAH' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7464' AS nis, 'REVAN MULYA PRATAMA' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7471' AS nis, 'RINDI SURYA DINATA' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7475' AS nis, 'RIXCO AGZRIL BISMANTARA' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7477' AS nis, 'RIZKI AFIFI AL AZRO' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7495' AS nis, 'SITI DWI NUR KHOLIFAH' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7498' AS nis, 'SITI ZAHROTUSITA NUR QOMAIROCH' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7501' AS nis, 'SYAKEILA AURIELLA PUTRI PRASTIYA' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7502' AS nis, 'TIO FEBRY FIRMANSYAH' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7503' AS nis, 'VALENTINA VEBRIANI' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7513' AS nis, 'YONGKY SURYA LESMANA' AS nama, 9 AS id_kelas
  UNION ALL
  SELECT '7172' AS nis, 'ACHMAD ARYA UTUNGGA' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7174' AS nis, 'ADE RISKY PRATAMA' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7215' AS nis, 'ARIS CAHYO WIDODO' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7219' AS nis, 'ARZAQI ALIF FEBRIAN' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7221' AS nis, 'ATHARIZZ PRINZA AL FAKHRI' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7230' AS nis, 'AZZHARA PURNAMA MAHARANI' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7231' AS nis, 'BATHARI SOMA WIJAYA' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7241' AS nis, 'CHERIS DWI LARAS WATI' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7261' AS nis, 'DIAH AYU APRILIA' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7268' AS nis, 'EGA FARHAN ALFARISI' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7273' AS nis, 'ELISA SEPTIA SAPUTRI' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7287' AS nis, 'FAHRI HARI AR RASYID' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7298' AS nis, 'FITRIA MARSYA SEPTI WIYANA' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7307' AS nis, 'HERA OKTAVIONA SISWANTO' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7309' AS nis, 'HUSNA KHATIJAH' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7314' AS nis, 'ISMAIL JULIAN DHOHO' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7325' AS nis, 'KARINA TRISIA ANIS' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7331' AS nis, 'KHALISA FITRIANI' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7346' AS nis, 'M AFDHUL FHARIS' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7366' AS nis, 'MOCH. OCTA ANDREAN SYAHPUTRA' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7373' AS nis, 'MOH FADIL WAHIDAN' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7375' AS nis, 'MOH ZIDAN AZAMTU QOLBI' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7387' AS nis, 'MUFIDA ANGGRAINI' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7395' AS nis, 'MUHAMMAD ILHAM FUAD MUID' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7410' AS nis, 'Nadhifa Ainy Putri' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7411' AS nis, 'NADIN ALFI NURIN' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7432' AS nis, 'NOVIANA RAHMA SARI' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7450' AS nis, 'PUTRI NABILA' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7474' AS nis, 'RISTI ANISA APRILIANA' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7476' AS nis, 'RIZKHY RAMADHANI' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7483' AS nis, 'SASKIA AZZHARA' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7488' AS nis, 'SHARENA ANASTASYA PUTRI' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7504' AS nis, 'VANESSY OVELYA JOCELYN' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7507' AS nis, 'VINA PRACILIA NOFITASARI' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7512' AS nis, 'YASINTA NUR AULIA PUTRI AZZAHRA' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7521' AS nis, 'ZASKIA HERLENA' AS nama, 10 AS id_kelas
  UNION ALL
  SELECT '7178' AS nis, 'AHMAD ABBAD NAILUN NABHAN' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7187' AS nis, 'AKHID ABDILLAH' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7193' AS nis, 'ALISKA NUR FITRI' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7199' AS nis, 'AMELDA OLIVIA PRAYITNO' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7201' AS nis, 'AMELIA PUSPITA SARI' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7203' AS nis, 'ANA NIRMALA JATI' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7208' AS nis, 'ANISA RISKY RAHMAWATI' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7223' AS nis, 'AULIA FITROTUL MUNAWAROH' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7227' AS nis, 'AXZEL RIVALZI PUTRA PRATAMA' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7247' AS nis, 'DAVIN AKBAR PRATAMA' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7277' AS nis, 'ELVITA NUR OKTAVIA' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7279' AS nis, 'ELYANA TANTI AZAHRA' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7280' AS nis, 'ESZHAR CHILA PERTIWI' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7285' AS nis, 'FADIL PUTRA DERMAWANSYAH' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7343' AS nis, 'LUTFIYATUN NADHROH' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7347' AS nis, 'M AFDHUL FHARIS' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7357' AS nis, 'MAY DWI RINDIYANI' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7364' AS nis, 'MOCH REVAND BAIHAQI ALYANSYAH' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7365' AS nis, 'MOCH ZIDNI AZAMUL MAULA' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7369' AS nis, 'MOCHAMAD FAIZ ALFARIDZI' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7374' AS nis, 'MOH RAHMAN BAKKOH' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7377' AS nis, 'MOHAMAD REFI EFENDI' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7381' AS nis, 'MOHAMMAD NANDA RAISYAH AKHIFA' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7385' AS nis, 'MUCHAMAD AKBAR SATRIAWAN' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7405' AS nis, 'MUZAYANAH NUR BASITHOH' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7408' AS nis, 'NABILA RAMADHANI' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7414' AS nis, 'NAFEEZA NUR AS SHIFA' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7426' AS nis, 'Nindi Oktafia Sari' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7444' AS nis, 'PUTRI AMALIYA SARI' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7447' AS nis, 'PUTRI AYU SIWI' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7457' AS nis, 'RAISHA EKA NURAINI' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7458' AS nis, 'RANCY PUTRI AMELIA' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7460' AS nis, 'RATNA YULIA SARI' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7479' AS nis, 'ROHYANI FITRI ANISA' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7510' AS nis, 'WILDANUL MUKHOLLADUN' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7523' AS nis, 'Zhahira Aulia Mayrani' AS nama, 11 AS id_kelas
  UNION ALL
  SELECT '7194' AS nis, 'ALIYA KHABIBATUR ROHMAH' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7236' AS nis, 'BRIAN ARYA PUTRA PRABOWO' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7264' AS nis, 'DICKY HANGGONO LARAS' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7270' AS nis, 'EKA HALIMATUL NIKMAH' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7274' AS nis, 'ELLYSA NATASIA SULISTIANI PERMATA SARI' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7276' AS nis, 'ELVARETA NUR AZIZAH' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7288' AS nis, 'FANIA NURMALITA ANGGRAINI' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7294' AS nis, 'FIQRULADZAM AWALUDNPRAYOGA' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7296' AS nis, 'FIRDA SASKIA MUNIF' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7305' AS nis, 'GRESBYANT IVIANZHA WIBOWO' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7310' AS nis, 'IMAM ALFINDA KURNIAWAN' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7318' AS nis, 'JENITA EKA NUR FADILA' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7333' AS nis, 'LATIFIYA NUR RASYIDAH' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7345' AS nis, 'M Abdi Suroso' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7348' AS nis, 'M ANDIKA PRAYOGA' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7376' AS nis, 'MOH. RAIHAN RISKIA ADSILA' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7380' AS nis, 'MOHAMMAD DAYAT KUSUMADRAJAD' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7383' AS nis, 'MOHSYAHRUL MAHENDRA ALFARIZI' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7398' AS nis, 'MUHAMMAD NURCAHYO' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7406' AS nis, 'NABILA PUTRI AZAHRA' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7417' AS nis, 'NAJWA SHIFA AZKIA ZAHRA' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7419' AS nis, 'NARESWARA AZZAHRA' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7420' AS nis, 'NATASYA JALESVEVA AGUSTINA' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7421' AS nis, 'Naylatul Qodriya Mirantu' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7427' AS nis, 'NIZAR EFENDY' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7429' AS nis, 'NOVI AULIA PUTRI' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7433' AS nis, 'NOVITA AYU WAHYUNINGTYAS' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7435' AS nis, 'NURIA CITRA KHUZAMY' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7441' AS nis, 'PINGKAN ISNA AVRILIA' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7454' AS nis, 'Rado Setiawan' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7462' AS nis, 'RAYA PUTRA PRASHILLA' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7465' AS nis, 'REYHAN ANCA ADITYA HANZAWA' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7472' AS nis, 'RISKA NURVILIA' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7480' AS nis, 'ROSELA ELIANA SALSABILLA' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7482' AS nis, 'SALVA PRISCILLA PUJIANTI' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7522' AS nis, 'ZEFANA KUSNADINI' AS nama, 12 AS id_kelas
  UNION ALL
  SELECT '7190' AS nis, 'ALFINO BINTANG RAMADHAN' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7197' AS nis, 'Alya Nur Aziza' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7204' AS nis, 'ANANDA NOVAN KURNIAWAN' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7205' AS nis, 'ANGGI NOVITASARI' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7211' AS nis, 'APRILIA RAHMAWATI' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7213' AS nis, 'APRILLIA CINTA URSITA' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7214' AS nis, 'ARDHI RIZQI FAUZI' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7224' AS nis, 'AULIA MEI NUR KAMALIN' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7226' AS nis, 'AULIA ZAHRA VANESSA' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7245' AS nis, 'DANDY FEBRI ARDIANTO' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7246' AS nis, 'DAVA PUTRA VARIAWAN' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7254' AS nis, 'DESWITA ARIFATUNISA NUR SISWOYO' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7290' AS nis, 'FARUUQ PUTRA YOSSY' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7317' AS nis, 'JAVIER ZAKKA NOVIAN BAGASKARA' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7323' AS nis, 'KAMIL AKHMAD NURROHMAN' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7335' AS nis, 'LENI NURALISA' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7344' AS nis, 'LUTHFI NUR FADHILA' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7355' AS nis, 'MAHMUDAH AGHNIA APRILIANA' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7359' AS nis, 'MEISY SETYA PERTIWI' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7361' AS nis, 'MEYVIA ADELLIA VIONI' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7392' AS nis, 'MUHAMMAD ALIF LEON RAMADHANU' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7393' AS nis, 'MUHAMMAD BADRUL KIROM' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7402' AS nis, 'MUHAMMAD RIKO RAMADAN' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7424' AS nis, 'NEYSHA KURNIAWAN' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7430' AS nis, 'NOVI EKA RAHMAWATI' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7434' AS nis, 'NUR APRILYA PUSPA DEWI' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7440' AS nis, 'PEBRIAN VALENTINO' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7451' AS nis, 'QURRATAAYUNI UMMU HABIBAH' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7455' AS nis, 'RAFA ZHAFIRRU MAKAYASA' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7468' AS nis, 'RIA NUR AINI' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7469' AS nis, 'RIFDA RIZKY DWI FAUZIAH' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7496' AS nis, 'SITI GADISTIA RAMADHANI' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7497' AS nis, 'SITI LAILATUL NUR PUJI RAMADHANI' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7499' AS nis, 'SURYA CANDRA WIJAYA' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7506' AS nis, 'VINA NUR RAMADHANI' AS nama, 13 AS id_kelas
  UNION ALL
  SELECT '7520' AS nis, 'ZASKIA AYU RAHMA FAHMITA' AS nama, 13 AS id_kelas
) s
JOIN tb_user u ON u.username = s.nis
ON DUPLICATE KEY UPDATE nama = VALUES(nama), id_kelas = VALUES(id_kelas);

COMMIT;
