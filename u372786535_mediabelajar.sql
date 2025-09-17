-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 17, 2025 at 03:03 PM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u372786535_mediabelajar`
--

-- --------------------------------------------------------

--
-- Table structure for table `audiobook`
--

CREATE TABLE `audiobook` (
  `id_audio` int(11) NOT NULL,
  `file_audio` varchar(255) NOT NULL,
  `id_materi` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id_guru` int(11) NOT NULL,
  `nama_guru` varchar(100) NOT NULL,
  `email_guru` varchar(100) NOT NULL,
  `password_guru` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `logo_universitas` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`id_guru`, `nama_guru`, `email_guru`, `password_guru`, `image`, `logo_universitas`) VALUES
(4, 'DWIYANA PUTRIANI', 'admin@guru.com', 'Admin12345', 'Gambar WhatsApp 2025-07-09 pukul 13.15.08_64e95a6c.jpg', 'Gambar WhatsApp 2025-07-09 pukul 13.26.00_08527e0e.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `jenjang_pendidikan`
--

CREATE TABLE `jenjang_pendidikan` (
  `id_jenjang` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `jenjang` varchar(50) NOT NULL,
  `institusi` varchar(100) NOT NULL,
  `jurusan` varchar(100) NOT NULL,
  `tahun_lulus` year(4) NOT NULL,
  `nim` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenjang_pendidikan`
--

INSERT INTO `jenjang_pendidikan` (`id_jenjang`, `id_guru`, `jenjang`, `institusi`, `jurusan`, `tahun_lulus`, `nim`) VALUES
(8, 4, 'SD', 'SDN 1 KALIJAGA BARU  ', '-', '2015', NULL),
(9, 4, 'SMP', 'SMPN 2 LENEK ', 'IPS', '2018', NULL),
(11, 4, 'SMA', 'MA DARUSSOLIHIN NW KALIJAGA ', 'IPS', '2021', NULL),
(12, 4, 'S1', 'UNIVERSITAS HAMZANWADI', 'PENDIDIKAN GURU SEKOLAH DASAR ', '2025', '210102344');

-- --------------------------------------------------------

--
-- Table structure for table `kuis`
--

CREATE TABLE `kuis` (
  `id_kuis` int(11) NOT NULL,
  `pertanyaan` text NOT NULL,
  `pilihan_a` varchar(255) NOT NULL,
  `pilihan_b` varchar(255) NOT NULL,
  `pilihan_c` varchar(255) NOT NULL,
  `jawaban_benar` enum('a','b','c') NOT NULL,
  `id_materi` int(11) DEFAULT NULL,
  `pilihan_d` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kuis`
--

INSERT INTO `kuis` (`id_kuis`, `pertanyaan`, `pilihan_a`, `pilihan_b`, `pilihan_c`, `jawaban_benar`, `id_materi`, `pilihan_d`) VALUES
(21, 'Proses pernapasan disebut juga dengan istilah...? \r\n', 'a. Respirasi', 'b. Sirkulasi', 'c. Pencernaan', 'a', NULL, 'D. Ekskresi'),
(22, 'Gas yang kita hirup saat bernapas adalah...?', 'A. Karbon monoksida', 'B. Nitrogen', 'C. Oksigen', 'c', NULL, 'D. Karbon dioksida '),
(23, 'Karbon dioksida adalah gas yang bersifat...?', 'A. Menyegarkan', 'B. Beracun', 'C. Sehat', 'b', NULL, 'D. Wangi '),
(24, 'Organ pertama tempat masuknya udara ke tubuh adalah...?', 'A. Faring', 'B. Trakea', 'C. Hidung', 'c', NULL, 'D. Paru-paru'),
(25, ' Faring adalah saluran yang menghubungkan hidung dengan...?', 'A. Paru-paru', 'B. Jantung', 'C. Laring', 'c', NULL, 'D. Bronkus'),
(26, ' Laring dikenal juga sebagai...?', 'A. Kerongkongan', 'B. Cabang tenggorokan', 'C. Pangkal tenggorokan', 'c', NULL, 'D. Rongga dada'),
(27, 'Trakea tersusun dari tulang rawan berbentuk...?', 'A. Tabung', 'B. Cincin', 'C. Balok', 'b', NULL, 'D. Bola'),
(28, 'Trakea memiliki jaringan silia yang berfungsi untuk...?', 'A. Menyaring darah', 'B. Menyerap makanan', ' C. Mendorong debu keluar', 'c', NULL, 'D. Mengeluarkan air'),
(29, 'Percabangan trakea menuju paru-paru disebut...?', 'A. Bronkus ', ' B. Alveolus ', 'C. Diafragma', 'a', NULL, 'D. Silia'),
(30, 'Otot yang membantu pernapasan dan memisahkan rongga dada dan perut disebut ...?', 'A. Paru-paru', 'B. Diafragma', 'C. Bronkus', 'b', NULL, 'D. Otot rusuk'),
(31, 'Penyakit pernapasan yang disebabkan oleh virus dan mudah menular adalah...?', 'A. Asma', ' B. TBC', 'C. Influenza', 'c', NULL, 'D. Bronkitis'),
(32, 'Penyakit yang membuat saluran pernapasan menyempit dan menyebabkan suara mengi disebut...?', 'A. Bronkitis ', ' B. Asma ', ' C. Influenza', 'b', NULL, 'D. TBC'),
(33, 'Salah satu cara merawat organ pernapasan adalah...?', 'A. Merokok setiap hari ', 'B. Menghirup debu', 'C. Menggunakan masker di lingkungan kotor', 'c', NULL, 'D. Tidak membersihkan rumah '),
(34, 'Di dalam rongga hidung terdapat rambut halus dan ... untuk menyaring udara?', 'A. Silia ', 'B. Otot ', 'C. Selaput lendir', 'c', NULL, 'D. Otot perut'),
(35, 'Tempat pertukaran oksigen dan karbon dioksida terjadi di dalam...?', 'A. Bronkus ', 'B. Laring', 'C. Trakea ', 'c', NULL, 'D. Alveolus');

-- --------------------------------------------------------

--
-- Table structure for table `materi`
--

CREATE TABLE `materi` (
  `id_materi` int(11) NOT NULL,
  `judul_materi` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `id_guru` int(11) DEFAULT NULL,
  `tanggal_upload` datetime DEFAULT current_timestamp(),
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `gambar` longblob DEFAULT NULL,
  `link_materi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `materi`
--

INSERT INTO `materi` (`id_materi`, `judul_materi`, `deskripsi`, `id_guru`, `tanggal_upload`, `status`, `gambar`, `link_materi`) VALUES
(21, 'organ pernafasan pada manusia ', 'organ pernafasan pada manusia', 4, '2025-06-26 08:17:19', 'published', 0x2e2e2f2e2e2f6173736574732f75706c6f6164732f67616d6261722f616e61746f6d692e6a7067, 'https://www.canva.com/design/DAGir0XIvaQ/pa4f8LICbRqErZOd629brA/edit?utm_content=DAGir0XIvaQ&utm_campaign=designshare&utm_medium=link2&utm_source=sharebutton');

-- --------------------------------------------------------

--
-- Table structure for table `organ_data`
--

CREATE TABLE `organ_data` (
  `id` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `organ_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `scrambled_word` varchar(255) DEFAULT NULL,
  `correct_word` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organ_data`
--

INSERT INTO `organ_data` (`id`, `id_guru`, `organ_name`, `description`, `image`, `scrambled_word`, `correct_word`, `created_at`) VALUES
(9, 4, 'HIDUNG ', 'Aku adalah tempat masuknya udara pertama kali saat manusia bernapas. Di dalamku ada rambut-rambut halus yang menyaring kotoran. siapakah aku?', 'organ_686e9c17beb327.83794668.jpg', 'UDIHNG', 'HIDUNG', '2025-03-22 15:51:14'),
(10, 4, 'faring', 'Aku adalah percabangan saluran di tenggorokan, tempat bertemunya udara, makanan, dan minuman.\r\nSiapakah aku?', 'organ_6873b80f918d95.56930570.jpg', 'IRNAGF', 'FARING', '2025-03-22 15:54:14'),
(12, 4, 'LARING', 'Aku berada di belakang faring dan terdiri dari sembilan tulang rawan. Aku juga dikenal sebagai pangkal tenggorokan.\r\nSiapakah aku?', 'organ_6873b8cd9a0564.64093859.jpg', 'GALIRN', 'LARING', '2025-03-22 16:19:06'),
(13, 4, 'TRAKEA', 'Aku adalah saluran berbentuk cincin yang berada di depan kerongkongan. Aku punya silia untuk membersihkan debu.\r\nSiapakah aku?', 'organ_6873b9bed20341.49633874.jpg', 'eatkar', 'trakea', '2025-03-23 03:56:56'),
(14, 4, 'bronkus', 'Aku adalah cabang dari trakea yang menuju ke paru-paru kanan dan kiri.\r\nSiapakah aku?', 'organ_6873bb111da885.17268700.jpg', 'srokbnu', 'bronkus', '2025-07-13 13:56:33'),
(15, 4, 'paru-paru', 'Aku tempat pertukaran oksigen dan karbon dioksida terjadi. Aku terdiri atas banyak alveolus dan berada di dalam rongga dada.\r\nSiapakah aku?', 'organ_6873c5b88e4261.33894053.jpg', 'rupa-rupa', 'paru-paru', '2025-07-13 13:58:12');

-- --------------------------------------------------------

--
-- Table structure for table `pembelajaran`
--

CREATE TABLE `pembelajaran` (
  `id_pembelajaran` int(11) NOT NULL,
  `id_materi` int(11) NOT NULL,
  `poin_tujuan` text NOT NULL,
  `poin_capaian` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembelajaran`
--

INSERT INTO `pembelajaran` (`id_pembelajaran`, `id_materi`, `poin_tujuan`, `poin_capaian`) VALUES
(18, 21, 'TP 1: Siswa dapat mengidentifikasi organ-organ penyusun sistem pernapasan manusia melalui media pembelajaran audiobook berbasis web.\r\n\r\nTP 2: Siswa dapat menjelaskan fungsi masing-masing organ pernapasan seperti hidung, tenggorokan, trakea, bronkus, dan paru-paru.\r\n\r\nTP 3: Siswa dapat menggambarkan jalur udara saat bernapas dari hidung hingga paru-paru secara lisan atau tulisan.\r\n\r\nTP 4: Siswa dapat menjelaskan proses pernapasan (inhalasi dan ekshalasi) secara sederhana.\r\n\r\nTP 5: Siswa dapat mengaitkan pentingnya menjaga kesehatan organ pernapasan dengan kebiasaan hidup sehat.\r\n\r\nTP 6: Siswa dapat mengidentifikasi gangguan atau penyakit pada sistem pernapasan serta cara pencegahannya.', 'Peserta didik mampu memahami struktur organ tubuh manusia dan fungsinya, serta pentingnya menjaga kesehatan organ tubuh. Siswa dapat menjelaskan hubungan antara organ penyusun sistem tubuh manusia, khususnya sistem pernapasan, dan cara memelihara kesehatan sistem tersebut.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audiobook`
--
ALTER TABLE `audiobook`
  ADD PRIMARY KEY (`id_audio`),
  ADD KEY `id_materi` (`id_materi`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id_guru`),
  ADD UNIQUE KEY `email_guru` (`email_guru`);

--
-- Indexes for table `jenjang_pendidikan`
--
ALTER TABLE `jenjang_pendidikan`
  ADD PRIMARY KEY (`id_jenjang`),
  ADD KEY `id_guru` (`id_guru`);

--
-- Indexes for table `kuis`
--
ALTER TABLE `kuis`
  ADD PRIMARY KEY (`id_kuis`),
  ADD KEY `id_materi` (`id_materi`);

--
-- Indexes for table `materi`
--
ALTER TABLE `materi`
  ADD PRIMARY KEY (`id_materi`),
  ADD KEY `id_guru` (`id_guru`);

--
-- Indexes for table `organ_data`
--
ALTER TABLE `organ_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembelajaran`
--
ALTER TABLE `pembelajaran`
  ADD PRIMARY KEY (`id_pembelajaran`),
  ADD KEY `fk_pembelajaran_materi` (`id_materi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audiobook`
--
ALTER TABLE `audiobook`
  MODIFY `id_audio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jenjang_pendidikan`
--
ALTER TABLE `jenjang_pendidikan`
  MODIFY `id_jenjang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `kuis`
--
ALTER TABLE `kuis`
  MODIFY `id_kuis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `materi`
--
ALTER TABLE `materi`
  MODIFY `id_materi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `organ_data`
--
ALTER TABLE `organ_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `pembelajaran`
--
ALTER TABLE `pembelajaran`
  MODIFY `id_pembelajaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audiobook`
--
ALTER TABLE `audiobook`
  ADD CONSTRAINT `audiobook_ibfk_1` FOREIGN KEY (`id_materi`) REFERENCES `materi` (`id_materi`) ON DELETE CASCADE;

--
-- Constraints for table `jenjang_pendidikan`
--
ALTER TABLE `jenjang_pendidikan`
  ADD CONSTRAINT `jenjang_pendidikan_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kuis`
--
ALTER TABLE `kuis`
  ADD CONSTRAINT `kuis_ibfk_1` FOREIGN KEY (`id_materi`) REFERENCES `materi` (`id_materi`) ON DELETE CASCADE;

--
-- Constraints for table `materi`
--
ALTER TABLE `materi`
  ADD CONSTRAINT `materi_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE CASCADE;

--
-- Constraints for table `pembelajaran`
--
ALTER TABLE `pembelajaran`
  ADD CONSTRAINT `fk_pembelajaran_materi` FOREIGN KEY (`id_materi`) REFERENCES `materi` (`id_materi`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
