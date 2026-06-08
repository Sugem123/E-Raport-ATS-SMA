-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 08, 2026 at 04:34 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_raport`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id_admin` varchar(10) NOT NULL,
  `id_user` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`id_admin`, `id_user`) VALUES
('ADMIN00001', 1),
('ADMIN00002', 7),
('ADMIN00003', 9),
('ADMIN00005', 14),
('ADMIN00004', 15),
('ADMIN00006', 20);

-- --------------------------------------------------------

--
-- Table structure for table `tb_guru`
--

CREATE TABLE `tb_guru` (
  `id_guru` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `id_user` int NOT NULL,
  `nama_guru` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `mata_pelajaran` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_guru`
--

INSERT INTO `tb_guru` (`id_guru`, `id_user`, `nama_guru`, `mata_pelajaran`) VALUES
('GURU000001', 2, 'Monica Tambunan', 'PKN'),
('GURU000002', 3, 'Budi', 'Matematika'),
('GURU000003', 16, 'Debay', 'Musik'),
('GURU000004', 27, 'Akri Jamaluddin', 'Sejarah Peminatan');

-- --------------------------------------------------------

--
-- Table structure for table `tb_nilai`
--

CREATE TABLE `tb_nilai` (
  `id_nilai` int NOT NULL,
  `nis` varchar(10) NOT NULL,
  `id_guru_matpel` varchar(50) NOT NULL,
  `tugas` decimal(3,0) NOT NULL,
  `uts` decimal(3,0) NOT NULL,
  `uas` decimal(3,0) NOT NULL,
  `nilai_akhir` decimal(5,2) NOT NULL,
  `status_kelulusan` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_nilai`
--

INSERT INTO `tb_nilai` (`id_nilai`, `nis`, `id_guru_matpel`, `tugas`, `uts`, `uas`, `nilai_akhir`, `status_kelulusan`) VALUES
(1, 'SISWA00003', 'GURU000002', '90', '70', '80', '80.00', 'Lulus'),
(2, 'SISWA00002', 'GURU000002', '80', '70', '50', '65.00', 'Tidak Lulus'),
(3, 'SISWA00004', 'GURU000002', '60', '70', '90', '75.00', 'Lulus'),
(5, 'SISWA00005', 'GURU000002', '90', '89', '80', '85.70', 'Lulus'),
(7, 'SISWA00002', 'GURU000001', '89', '44', '99', '79.50', 'Lulus'),
(8, 'SISWA00005', 'GURU000004', '50', '50', '40', '46.00', 'Tidak Lulus'),
(9, 'SISWA00002', 'GURU000004', '90', '60', '80', '77.00', 'Lulus'),
(10, 'SISWA00003', 'GURU000004', '10', '56', '38', '35.00', 'Tidak Lulus');

-- --------------------------------------------------------

--
-- Table structure for table `tb_siswa`
--

CREATE TABLE `tb_siswa` (
  `nis` varchar(10) NOT NULL,
  `id_user` int NOT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `kelas` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_siswa`
--

INSERT INTO `tb_siswa` (`nis`, `id_user`, `nama`, `kelas`) VALUES
('SISWA00002', 5, 'dilan', '11mipa6'),
('SISWA00003', 6, 'bella', '12ips3'),
('SISWA00004', 18, 'anggun', '12ips3'),
('SISWA00005', 19, 'antoni', '10mipa4'),
('ssssssssss', 26, 'Debzy', '12ips2');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `role` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$jjKNjSDBr7IWn14yXPEEr.e/IY8Fv4KI5EiO4qmCp9TXZCN.JkXwe', 'admin'),
(2, 'guru_1', '$2a$12$v4jrJ1XTPljj5qGWLxrAHekk3pp./SGxTM1VtbV3njeHIXLubXb/i', 'guru'),
(3, 'guru_2', '$2y$10$Ssc6ktSXeiFXIjXD/d5d6.zrkakamdef24Yh0/0k3i7WtZXzlexAi', 'guru'),
(5, 'siswa_2', '$2a$12$v4jrJ1XTPljj5qGWLxrAHekk3pp./SGxTM1VtbV3njeHIXLubXb/i', 'siswa'),
(6, 'siswa_3', '$2a$12$v4jrJ1XTPljj5qGWLxrAHekk3pp./SGxTM1VtbV3njeHIXLubXb/i', 'siswa'),
(7, 'admin-67', '$2y$10$wyonZYlzCvOjbJC817MfDun8bHAYN6HbsxVhJ2KwqhWJbvmM0AZMC', 'admin'),
(9, 'mudah', '$2y$10$9YC4cuVbO5//rsB1L/RPQeovg0bz9XIecbL5MZX6zNVXfK/yQ6RxG', 'admin'),
(14, 'sulit sulit', '$2y$10$Vtz7zMSBfR9j4ZD6YuGdCuc93TmskSq6Ij4rL4QJpCIH8w.RCWIb.', 'admin'),
(15, 'aaaaaaaa', '$2y$10$ovSrGk.41wFw6pE54DnRUuHDIxdEUZvEUp4VESvj7uQcGWIjwblzy', 'admin'),
(16, 'debayisme', '$2y$10$y6rAh3tIM8wPsP0v1I50zuYc/IpxVFeueQ7pOY47Q1uq04wHOpuoa', 'guru'),
(18, 'siswa_4', '$2y$10$9YC4cuVbO5//rsB1L/RPQeovg0bz9XIecbL5MZX6zNVXfK/yQ6RxG', 'siswa'),
(19, 'sulit', '$2y$10$6VTP.0GaIY3j9D606YbQauSaYnQI/UCc1pOJOpmO51DKfDqoMsBqm', 'siswa'),
(20, 'gelap', '$2y$10$rb7cMAoC5dkZfFM1FLpYyeKcZB8M2kEC1Sss3/U5B.Lm4Zw5KaZrW', 'admin'),
(26, 'debyaisme', '$2y$10$dF9C/CXfhvhgf9R8PLkDlOKP33Bot3Np6gYqcy5hHPAyK3DaRqp.e', 'siswa'),
(27, 'jamaluddin691', '$2y$10$/6r8y4dRqtfjz8uCXdNJ1uQiKXS9oi8Ydl9zE.n1IyghmJMHfrAEy', 'guru');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD KEY `fk_user_admin` (`id_user`);

--
-- Indexes for table `tb_guru`
--
ALTER TABLE `tb_guru`
  ADD PRIMARY KEY (`id_guru`),
  ADD KEY `fk_user_guru` (`id_user`);

--
-- Indexes for table `tb_nilai`
--
ALTER TABLE `tb_nilai`
  ADD PRIMARY KEY (`id_nilai`),
  ADD KEY `fk_guru_matpel` (`id_guru_matpel`);

--
-- Indexes for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD PRIMARY KEY (`nis`),
  ADD KEY `fk_user_siswa` (`id_user`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_nilai`
--
ALTER TABLE `tb_nilai`
  MODIFY `id_nilai` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD CONSTRAINT `fk_user_admin` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_guru`
--
ALTER TABLE `tb_guru`
  ADD CONSTRAINT `fk_user_guru` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_nilai`
--
ALTER TABLE `tb_nilai`
  ADD CONSTRAINT `fk_guru_matpel` FOREIGN KEY (`id_guru_matpel`) REFERENCES `tb_guru` (`id_guru`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD CONSTRAINT `fk_user_siswa` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
