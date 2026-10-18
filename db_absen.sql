-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 18 Okt 2026 pada 05.18
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_absensi_fisip`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `dosen`
--

CREATE TABLE `dosen` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `nip` varchar(30) NOT NULL,
  `nidn` varchar(30) DEFAULT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `gelar_depan` varchar(50) DEFAULT NULL,
  `gelar_belakang` varchar(50) DEFAULT NULL,
  `prodi_id` int(11) UNSIGNED DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dosen`
--

INSERT INTO `dosen` (`id`, `user_id`, `nip`, `nidn`, `nama_lengkap`, `gelar_depan`, `gelar_belakang`, `prodi_id`, `no_hp`) VALUES
(1, 1, '196801011995031002', '0001016803', 'Dr. Yakobus Murib, S.Sos., M.Si', NULL, NULL, 1, ''),
(2, 2, '197002121998021001', '0012027001', 'Dr. Alimin Alwi, M.Si', NULL, NULL, 2, '08123456781'),
(3, 3, '198003152006041003', '0015038002', 'Marthinus Sesa, S.IP., M.Si', NULL, NULL, 3, '08123456782'),
(4, 4, '197505202002121004', '0020057502', 'Jacobus Kastanya, S.Sos., M.Si', NULL, NULL, 4, '08123456783'),
(5, 17, '197701132000031002', '13011977', 'John E. Samori', '', 'SE,. MM', 2, '081248381228');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas_kuliah`
--

CREATE TABLE `kelas_kuliah` (
  `id` int(11) UNSIGNED NOT NULL,
  `tahun_akademik_id` int(11) UNSIGNED NOT NULL,
  `mata_kuliah_id` int(11) UNSIGNED NOT NULL,
  `dosen_id` int(11) UNSIGNED DEFAULT NULL,
  `nama_kelas` varchar(50) DEFAULT NULL,
  `hari` varchar(10) DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `ruangan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelas_kuliah`
--

INSERT INTO `kelas_kuliah` (`id`, `tahun_akademik_id`, `mata_kuliah_id`, `dosen_id`, `nama_kelas`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan`) VALUES
(1, 2, 1, 1, 'A', 'Senin', '08:00:00', '09:40:00', 'R.201'),
(2, 2, 3, 2, 'A', 'Selasa', '10:00:00', '11:40:00', 'R.202'),
(3, 2, 5, 3, 'A', 'Rabu', '13:00:00', '14:40:00', 'R.203'),
(4, 2, 6, 4, 'A', 'Kamis', '08:00:00', '09:40:00', 'R.204'),
(5, 2, 7, 2, 'A', 'Jumat', '09:00:00', '10:40:00', 'R.205'),
(6, 2, 8, 5, 'A', 'Senin', '18:05:00', '19:00:00', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `nim` varchar(30) NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `prodi_id` int(11) UNSIGNED DEFAULT NULL,
  `angkatan` varchar(4) DEFAULT NULL,
  `kelas_paralel` varchar(20) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `user_id`, `nim`, `nama_lengkap`, `prodi_id`, `angkatan`, `kelas_paralel`, `no_hp`) VALUES
(7, 11, '202501007', 'Gita Maharani', 4, '2025', 'A', '081234567807'),
(13, 28, '2026031044001', 'Melky Filex Wanma', 2, '2026', 'REGULER I', ''),
(14, 29, '2026031044004', 'Enjellina Yuliana .Y. Naroba', 2, '2026', 'REGULER I', ''),
(15, 30, '2026031044007', 'Julian Albertzon Silas Baransano', 2, '2026', 'REGULER I', ''),
(16, 31, '2026031044010', 'Sunny Yunice Sinay', 2, '2026', 'REGULER I', ''),
(17, 32, '2026031044013', 'Jeremia Wesley Naa', 2, '2026', 'REGULER I', ''),
(18, 33, '2026031044016', 'Agnes Anastasya Yerkohok', 2, '2026', 'REGULER I', ''),
(19, 34, '2026031044019', 'Venancya Rudolva Eko', 2, '2026', 'REGULER I', ''),
(20, 35, '2026031044022', 'Penias Edowai', 2, '2026', 'REGULER I', ''),
(21, 36, '2026031044025', 'Jordan Yoel Matruty', 2, '2026', 'REGULER I', ''),
(22, 37, '2026031044028', 'Sipora Et Labora Dabili', 2, '2026', 'REGULER I', ''),
(23, 38, '2026031044031', 'Stevani Agustiani Wonda Mulait', 2, '2026', 'REGULER I', ''),
(24, 39, '2026031044034', 'Yoas Edowai', 2, '2026', 'REGULER I', ''),
(25, 40, '2026031044037', 'Menase Kadepa', 2, '2026', 'REGULER I', ''),
(26, 41, '2026031044040', 'Tesalonika Christy Prabha Kalabory', 2, '2026', 'REGULER I', ''),
(27, 42, '2026031044043', 'Rossa Novalin Yarawobi', 2, '2026', 'REGULER I', ''),
(28, 43, '2026031044046', 'Agus Ugipa', 2, '2026', 'REGULER I', ''),
(29, 44, '2026031044049', 'Thopilus Wopari', 2, '2026', 'REGULER I', ''),
(30, 45, '2026031044052', 'Adrian Rais Litiloly', 2, '2026', 'REGULER I', ''),
(31, 46, '2026031044055', 'Wihelmina Nisiodei Mofu', 2, '2026', 'REGULER I', ''),
(32, 47, '2026031044058', 'Sefanya Fawan', 2, '2026', 'REGULER I', ''),
(33, 48, '2026031044061', 'Ortisan Sefaniwi', 2, '2026', 'REGULER I', ''),
(34, 49, '2026031044064', 'Didimus Serti', 2, '2026', 'REGULER I', ''),
(35, 50, '2026031044067', 'Kristian Wenda', 2, '2026', 'REGULER I', ''),
(36, 51, '2026031044070', 'Yehuda Treido', 2, '2026', 'REGULER I', ''),
(37, 52, '2026031044073', 'Rido Obet Karsao', 2, '2026', 'REGULER I', ''),
(38, 53, '2026031044076', 'Lerry Valensius Mansario Wambrauw', 2, '2026', 'REGULER I', ''),
(39, 54, '2026031044079', 'Syallom Daud Yaung Hurunama', 2, '2026', 'REGULER I', ''),
(40, 55, '2026031044082', 'Johan Speiner Dimara', 2, '2026', 'REGULER I', ''),
(41, 56, '2026031044085', 'Lamekh Genbri Wonda', 2, '2026', 'REGULER I', ''),
(42, 57, '2026031044088', 'Debora Elisabeth Kabuare', 2, '2026', 'REGULER I', ''),
(43, 58, '2026031044091', 'Frans Worabai', 2, '2026', 'REGULER I', ''),
(44, 59, '2026031044094', 'Aron Ariel Mnusefer', 2, '2026', 'REGULER I', ''),
(45, 60, '2026031044097', 'Eson Gelei', 2, '2026', 'REGULER I', ''),
(46, 61, '2026031044100', 'Yohanes Julian Dekeniap', 2, '2026', 'REGULER I', ''),
(47, 62, '2026031044103', 'Edison Wenda', 2, '2026', 'REGULER I', ''),
(48, 63, '2026031044106', 'Meiyanti Vaustina Syufi', 2, '2026', 'REGULER I', ''),
(49, 64, '2026031044109', 'Makdalikus Uropka', 2, '2026', 'REGULER I', ''),
(50, 65, '2026031044112', 'Malpin Wandikbo', 2, '2026', 'REGULER I', ''),
(51, 66, '2026031044115', 'Dani Kogoya', 2, '2026', 'REGULER I', ''),
(52, 67, '2026031044118', 'Gabriela Lesomar', 2, '2026', 'REGULER I', ''),
(53, 68, '2026031044121', 'Dorkas Puput M. Sayori', 2, '2026', 'REGULER I', ''),
(54, 69, '2026031044124', 'Odinus Kogoya', 2, '2026', 'REGULER I', ''),
(55, 70, '2026031044127', 'Marten Eka Pigome', 2, '2026', 'REGULER I', ''),
(56, 71, '2026031044130', 'Jeklin Evelin Bedes', 2, '2026', 'REGULER I', ''),
(57, 72, '2026031044133', 'Christina Helena Yomima I.B Mansoben', 2, '2026', 'REGULER I', ''),
(58, 73, '2026031044136', 'Tindon Wanimbo', 2, '2026', 'REGULER I', ''),
(59, 74, '2026031044139', 'Donald Yikwa', 2, '2026', 'REGULER I', ''),
(60, 75, '2026031044142', 'Ronaldus Ukago', 2, '2026', 'REGULER I', ''),
(61, 76, '2026031044145', 'Omeri Mirin', 2, '2026', 'REGULER I', ''),
(62, 77, '2026031044148', 'Rosalina Hutri Wanma', 2, '2026', 'REGULER I', ''),
(63, 78, '2026031044151', 'Leorah Sari Alexandrina Banggo', 2, '2026', 'REGULER I', ''),
(64, 79, '2026031044154', 'Septiani Melisa Salipadang', 2, '2026', 'REGULER I', ''),
(65, 80, '2026031044157', 'Marcellino Bryan Asmuruf', 2, '2026', 'REGULER I', ''),
(66, 81, '2026031044160', 'Gevin Rivan Alimdam', 2, '2026', 'REGULER I', ''),
(67, 82, '2026031044163', 'Danielo Ruben Merabano', 2, '2026', 'REGULER I', ''),
(68, 83, '2026031044166', 'Moses Agustinus S. Antoh', 2, '2026', 'REGULER I', ''),
(69, 84, '2026031044169', 'George Hendry Wamafma', 2, '2026', 'REGULER I', ''),
(70, 85, '2026031044172', 'Demas Simson Tokoro', 2, '2026', 'REGULER I', ''),
(71, 86, '2026031044175', 'Yosias Lepki', 2, '2026', 'REGULER I', ''),
(72, 87, '2026031044178', 'Melince Gobai', 2, '2026', 'REGULER I', ''),
(73, 88, '2026031044181', 'Septinus Maikel Rumainum', 2, '2026', 'REGULER I', ''),
(74, 89, '2026031044184', 'Nurjannah Andi Ruslan', 2, '2026', 'REGULER I', ''),
(75, 90, '2026031044187', 'Valleria Cynthia Belle Hubi', 2, '2026', 'REGULER I', ''),
(76, 91, '2026031044190', 'Welmina Marlian Rifunai', 2, '2026', 'REGULER I', ''),
(77, 92, '2026031044193', 'Paskalino Usmany', 2, '2026', 'REGULER I', ''),
(78, 93, '2026031044196', 'Septinus Romario Momo', 2, '2026', 'REGULER I', ''),
(79, 94, '2026031044199', 'Neles Kakadoki Yobe', 2, '2026', 'REGULER I', ''),
(80, 95, '2026031044202', 'Ondison Kogoya', 2, '2026', 'REGULER I', ''),
(81, 96, '2026031044205', 'Arnol Aner Asmuruf', 2, '2026', 'REGULER I', ''),
(82, 97, '2026031044208', 'Yubelika Agata A. Wetipo', 2, '2026', 'REGULER I', ''),
(83, 98, '2026031044002', 'Galbraith Yusuf Rahayan', 2, '2026', 'REGULER II', ''),
(84, 99, '2026031044005', 'Deiton Enumbi', 2, '2026', 'REGULER II', ''),
(85, 100, '2026031044008', 'Marcello Marcelino Sikoway', 2, '2026', 'REGULER II', ''),
(86, 101, '2026031044011', 'Miselyn Trivia Seravina Indey', 2, '2026', 'REGULER II', ''),
(87, 102, '2026031044014', 'Syalomitha P. Patiraja Wane', 2, '2026', 'REGULER II', ''),
(88, 103, '2026031044017', 'Alfrian G. Amerson Kogoya', 2, '2026', 'REGULER II', ''),
(89, 104, '2026031044020', 'Dormina Arnelita Randongkir', 2, '2026', 'REGULER II', ''),
(90, 105, '2026031044023', 'Valentino Tiran Fraseck Mirin', 2, '2026', 'REGULER II', ''),
(91, 106, '2026031044026', 'Marten Anton Keni', 2, '2026', 'REGULER II', ''),
(92, 107, '2026031044029', 'Rahmat Rizky', 2, '2026', 'REGULER II', ''),
(93, 108, '2026031044032', 'Melani Maria Worumboi', 2, '2026', 'REGULER II', ''),
(94, 109, '2026031044035', 'Arnol Weipsa', 2, '2026', 'REGULER II', ''),
(95, 110, '2026031044038', 'Divino Eldadson Kombong', 2, '2026', 'REGULER II', ''),
(96, 111, '2026031044041', 'Hans Nelson Bilasi', 2, '2026', 'REGULER II', ''),
(97, 112, '2026031044044', 'Abet Mote', 2, '2026', 'REGULER II', ''),
(98, 113, '2026031044047', 'Priskila Anny Welmince Howay', 2, '2026', 'REGULER II', ''),
(99, 114, '2026031044050', 'Rio Reifan Mokay', 2, '2026', 'REGULER II', ''),
(100, 115, '2026031044053', 'Silas Kornelius Jitmau', 2, '2026', 'REGULER II', ''),
(101, 116, '2026031044056', 'Henry Josua Kareth', 2, '2026', 'REGULER II', ''),
(102, 117, '2026031044059', 'Metusalak Wabia', 2, '2026', 'REGULER II', ''),
(103, 118, '2026031044062', 'Glorya Tjandra Martavina Nauw', 2, '2026', 'REGULER II', ''),
(104, 119, '2026031044065', 'Daniel Rumpaisum', 2, '2026', 'REGULER II', ''),
(105, 120, '2026031044068', 'Desita Marlina Christin Thesia', 2, '2026', 'REGULER II', ''),
(106, 121, '2026031044071', 'Henok Hisage', 2, '2026', 'REGULER II', ''),
(107, 122, '2026031044074', 'Irisa Ariy Simalye', 2, '2026', 'REGULER II', ''),
(108, 123, '2026031044077', 'Helvison Lemauk', 2, '2026', 'REGULER II', ''),
(109, 124, '2026031044080', 'Penison Wenda', 2, '2026', 'REGULER II', ''),
(110, 125, '2026031044083', 'Marfel Israel Hara', 2, '2026', 'REGULER II', ''),
(111, 126, '2026031044086', 'Angelina Charolina Mewengkang Wanimbo', 2, '2026', 'REGULER II', ''),
(112, 127, '2026031044089', 'Rita Enjela Kafiar', 2, '2026', 'REGULER II', ''),
(113, 128, '2026031044092', 'Huios Natanael Piter Ortumilena', 2, '2026', 'REGULER II', ''),
(114, 129, '2026031044095', 'Valentinus Aisasior', 2, '2026', 'REGULER II', ''),
(115, 130, '2026031044098', 'Godlif Lodwik Mnusefer', 2, '2026', 'REGULER II', ''),
(116, 131, '2026031044101', 'Melkianus Baho', 2, '2026', 'REGULER II', ''),
(117, 132, '2026031044104', 'Deton Wandikbo', 2, '2026', 'REGULER II', ''),
(118, 133, '2026031044107', 'Alfredo Amerdin Wania', 2, '2026', 'REGULER II', ''),
(119, 134, '2026031044110', 'Elizabeth Maniagasi', 2, '2026', 'REGULER II', ''),
(120, 135, '2026031044113', 'Kilyon Kirihio', 2, '2026', 'REGULER II', ''),
(121, 136, '2026031044116', 'Ehud Sharon Olmert Matulessy', 2, '2026', 'REGULER II', ''),
(122, 137, '2026031044119', 'Elissabeth Natalia Yeimo', 2, '2026', 'REGULER II', ''),
(123, 138, '2026031044122', 'Toni Janampa', 2, '2026', 'REGULER II', ''),
(124, 139, '2026031044125', 'Greivens Jozeph Rences Oentoe Djopari', 2, '2026', 'REGULER II', ''),
(125, 140, '2026031044128', 'Juniareni Gracia Carolina Wamafma', 2, '2026', 'REGULER II', ''),
(126, 141, '2026031044131', 'Demianus Samuel Fait Sien Iek', 2, '2026', 'REGULER II', ''),
(127, 142, '2026031044134', 'Alex Luga Karoba', 2, '2026', 'REGULER II', ''),
(128, 143, '2026031044137', 'Buana Nusantara Naa', 2, '2026', 'REGULER II', ''),
(129, 144, '2026031044140', 'Drogba Sobari Windesi', 2, '2026', 'REGULER II', ''),
(130, 145, '2026031044143', 'Elpi T Bum', 2, '2026', 'REGULER II', ''),
(131, 146, '2026031044146', 'Virginia Sofianti Solossa', 2, '2026', 'REGULER II', ''),
(132, 147, '2026031044149', 'Eldamariani Mathea Waker', 2, '2026', 'REGULER II', ''),
(133, 148, '2026031044152', 'Olvi Novalia Yapri', 2, '2026', 'REGULER II', ''),
(134, 149, '2026031044155', 'Jaket B Kasipmabin', 2, '2026', 'REGULER II', ''),
(135, 150, '2026031044158', 'Papuana Balyo', 2, '2026', 'REGULER II', ''),
(136, 151, '2026031044161', 'Marsela Yolanda Yuliana Numberi', 2, '2026', 'REGULER II', ''),
(137, 152, '2026031044164', 'Yakup Sesa', 2, '2026', 'REGULER II', ''),
(138, 153, '2026031044167', 'Yus Rivan Richson Pahabol', 2, '2026', 'REGULER II', ''),
(139, 154, '2026031044170', 'Jessy Jessyca Orocomna', 2, '2026', 'REGULER II', ''),
(140, 155, '2026031044173', 'Emanuel Boma', 2, '2026', 'REGULER II', ''),
(141, 156, '2026031044176', 'Simon Farel Aweno', 2, '2026', 'REGULER II', ''),
(142, 157, '2026031044179', 'Adriano Polce Korwa', 2, '2026', 'REGULER II', ''),
(143, 158, '2026031044182', 'Everon Mabel', 2, '2026', 'REGULER II', ''),
(144, 159, '2026031044185', 'Natallend Barend Waroy', 2, '2026', 'REGULER II', ''),
(145, 160, '2026031044188', 'Wiclif Yakonias Enok', 2, '2026', 'REGULER II', ''),
(146, 161, '2026031044191', 'Rivaldo Albetzhon Yusuf Naa', 2, '2026', 'REGULER II', ''),
(147, 162, '2026031044194', 'Francisko Giyai', 2, '2026', 'REGULER II', ''),
(148, 163, '2026031044197', 'Gasper Mlik', 2, '2026', 'REGULER II', ''),
(149, 164, '2026031044200', 'Alberto Fery Bless', 2, '2026', 'REGULER II', ''),
(150, 165, '2026031044203', 'Tenri Lutfiah Zahra Aulia', 2, '2026', 'REGULER II', ''),
(151, 166, '2026031044206', 'Agustiar Henrry Jacob Baibaba', 2, '2026', 'REGULER II', ''),
(152, 167, '2026031044209', 'Yusak Gire', 2, '2026', 'REGULER II', ''),
(153, 168, '2026031044003', 'Ester Agustina Bunai', 2, '2026', 'REGULER III', ''),
(154, 169, '2026031044006', 'Jhosua Israel Maniburi', 2, '2026', 'REGULER III', ''),
(155, 170, '2026031044009', 'Aldo Hengki Pito Papa Mote', 2, '2026', 'REGULER III', ''),
(156, 171, '2026031044012', 'Yason Wenda', 2, '2026', 'REGULER III', ''),
(157, 172, '2026031044015', 'Ruth Dogopia', 2, '2026', 'REGULER III', ''),
(158, 173, '2026031044018', 'Rafly Yikwa', 2, '2026', 'REGULER III', ''),
(159, 174, '2026031044021', 'Melsi Patulak', 2, '2026', 'REGULER III', ''),
(160, 175, '2026031044024', 'Yehuda Pakage', 2, '2026', 'REGULER III', ''),
(161, 176, '2026031044027', 'Victoria Louisa Lontaan', 2, '2026', 'REGULER III', ''),
(162, 177, '2026031044030', 'Mona Patrisia Wenda', 2, '2026', 'REGULER III', ''),
(163, 178, '2026031044033', 'Wilhelmina Magal', 2, '2026', 'REGULER III', ''),
(164, 179, '2026031044036', 'Yohanes Marco Magai', 2, '2026', 'REGULER III', ''),
(165, 180, '2026031044039', 'Petra A. Yaung', 2, '2026', 'REGULER III', ''),
(166, 181, '2026031044042', 'Maria Kristina Sumel', 2, '2026', 'REGULER III', ''),
(167, 182, '2026031044045', 'Wildrens Nate Sasarari', 2, '2026', 'REGULER III', ''),
(168, 183, '2026031044048', 'Hendrik Kombado', 2, '2026', 'REGULER III', ''),
(169, 184, '2026031044051', 'Miros Ruben Virgil Asmuruf', 2, '2026', 'REGULER III', ''),
(170, 185, '2026031044054', 'Onnyie Degei Poge', 2, '2026', 'REGULER III', ''),
(171, 186, '2026031044057', 'Cores Maikel Fakdawer', 2, '2026', 'REGULER III', ''),
(172, 187, '2026031044060', 'Natasha Fransina Saroge', 2, '2026', 'REGULER III', ''),
(173, 188, '2026031044063', 'Eugelion Melkians Puhiri', 2, '2026', 'REGULER III', ''),
(174, 189, '2026031044066', 'Yomahe Haren Ruth Murip', 2, '2026', 'REGULER III', ''),
(175, 190, '2026031044069', 'Beatha Wokun', 2, '2026', 'REGULER III', ''),
(176, 191, '2026031044072', 'Enias Elopere', 2, '2026', 'REGULER III', ''),
(177, 192, '2026031044075', 'Andi Doga', 2, '2026', 'REGULER III', ''),
(178, 193, '2026031044078', 'Nus Magai Yogi', 2, '2026', 'REGULER III', ''),
(179, 194, '2026031044081', 'Gabriel Evan Rumansara', 2, '2026', 'REGULER III', ''),
(180, 195, '2026031044084', 'Kartinus Tinal', 2, '2026', 'REGULER III', ''),
(181, 196, '2026031044087', 'Alinus Tabuni', 2, '2026', 'REGULER III', ''),
(182, 197, '2026031044090', 'Rosiana Jeklin Tabuni', 2, '2026', 'REGULER III', ''),
(183, 198, '2026031044093', 'Patric Hendrix De Caprio Tahitoe', 2, '2026', 'REGULER III', ''),
(184, 199, '2026031044096', 'Moses Walat Salamuk', 2, '2026', 'REGULER III', ''),
(185, 200, '2026031044099', 'Sera Ghomboroku Adolof Rewomi', 2, '2026', 'REGULER III', ''),
(186, 201, '2026031044102', 'Sandy Arivin Agustinus Bonay', 2, '2026', 'REGULER III', ''),
(187, 202, '2026031044105', 'Kristian Danilo Boseren', 2, '2026', 'REGULER III', ''),
(188, 203, '2026031044108', 'Aris Saibori', 2, '2026', 'REGULER III', ''),
(189, 204, '2026031044111', 'Blasius Rufus Yordan Awumin Puman', 2, '2026', 'REGULER III', ''),
(190, 205, '2026031044114', 'Defron Kogoya', 2, '2026', 'REGULER III', ''),
(191, 206, '2026031044117', 'Juan Frangklin George Manuhutu', 2, '2026', 'REGULER III', ''),
(192, 207, '2026031044120', 'Alberthzon Hendrol Z Kogoya', 2, '2026', 'REGULER III', ''),
(193, 208, '2026031044123', 'Carl Willem Ottow Saa', 2, '2026', 'REGULER III', ''),
(194, 209, '2026031044126', 'Sekianus Hembopa', 2, '2026', 'REGULER III', ''),
(195, 210, '2026031044129', 'Andreas Wickliff Maxi Karma', 2, '2026', 'REGULER III', ''),
(196, 211, '2026031044132', 'Theodora Phatricia Yakwart', 2, '2026', 'REGULER III', ''),
(197, 212, '2026031044135', 'Mario Yopri Wenda', 2, '2026', 'REGULER III', ''),
(198, 213, '2026031044138', 'Israel Varlan Eduard Naa', 2, '2026', 'REGULER III', ''),
(199, 214, '2026031044141', 'Thomas V. Hitsani Hisage', 2, '2026', 'REGULER III', ''),
(200, 215, '2026031044144', 'Degresia Desi Totou', 2, '2026', 'REGULER III', ''),
(201, 216, '2026031044147', 'Alex Michael Wariori', 2, '2026', 'REGULER III', ''),
(202, 217, '2026031044150', 'Lambertus Emanuel Kapiyau', 2, '2026', 'REGULER III', ''),
(203, 218, '2026031044153', 'Relifani Wanimbo', 2, '2026', 'REGULER III', ''),
(204, 219, '2026031044156', 'Akwila Yosep Kogoya', 2, '2026', 'REGULER III', ''),
(205, 220, '2026031044159', 'Christian Nichola Manangsang Tanati', 2, '2026', 'REGULER III', ''),
(206, 221, '2026031044162', 'Juventus Ajai', 2, '2026', 'REGULER III', ''),
(207, 222, '2026031044165', 'Vian Richard Irianto Yom', 2, '2026', 'REGULER III', ''),
(208, 223, '2026031044168', 'Vanesha Miracle Ronsumbre', 2, '2026', 'REGULER III', ''),
(209, 224, '2026031044171', 'Roy Marthen Iek', 2, '2026', 'REGULER III', ''),
(210, 225, '2026031044174', 'Habel Jenggo Duwiri', 2, '2026', 'REGULER III', ''),
(211, 226, '2026031044177', 'Marthina Aprilia Wefma', 2, '2026', 'REGULER III', ''),
(212, 227, '2026031044180', 'Yulius K Fatie', 2, '2026', 'REGULER III', ''),
(213, 228, '2026031044183', 'Yanus Mabel', 2, '2026', 'REGULER III', ''),
(214, 229, '2026031044186', 'Stevani Mariani Yoku', 2, '2026', 'REGULER III', ''),
(215, 230, '2026031044189', 'Yani Tasya Viola Huwae', 2, '2026', 'REGULER III', ''),
(216, 231, '2026031044192', 'Desi Natalia Warnares', 2, '2026', 'REGULER III', ''),
(217, 232, '2026031044195', 'Kristian Wenand Payai', 2, '2026', 'REGULER III', ''),
(218, 233, '2026031044198', 'Maya Kezia Meyland Duwiri', 2, '2026', 'REGULER III', ''),
(219, 234, '2026031044201', 'Jefryzon Gustap Kareth', 2, '2026', 'REGULER III', ''),
(220, 235, '2026031044204', 'Yoice Paula Walli', 2, '2026', 'REGULER III', ''),
(221, 236, '2026031044207', 'Benyoni Wadomeipode Pakage', 2, '2026', 'REGULER III', ''),
(222, 237, '2026031044210', 'Beby Boys Wanimbo', 2, '2026', 'REGULER III', ''),
(223, 238, '2026032044001', 'Ati Wisal', 2, '2026', 'EKSTENSI I', ''),
(224, 239, '2026032044002', 'Mison Kogoya', 2, '2026', 'EKSTENSI I', ''),
(225, 240, '2026032044003', 'Martenci B. Marbase', 2, '2026', 'EKSTENSI I', ''),
(226, 241, '2026032044004', 'David Lopulalan', 2, '2026', 'EKSTENSI I', ''),
(227, 242, '2026032044005', 'Batseba Herlin Hena Kaway', 2, '2026', 'EKSTENSI I', ''),
(228, 243, '2026032044006', 'Alfred Namang', 2, '2026', 'EKSTENSI I', ''),
(229, 244, '2026032044007', 'Novica Charlota Senis', 2, '2026', 'EKSTENSI I', ''),
(230, 245, '2026032044008', 'Selius Mimin', 2, '2026', 'EKSTENSI I', ''),
(231, 246, '2026032044009', 'Santi Sisilia Sem', 2, '2026', 'EKSTENSI I', ''),
(232, 247, '2026032044010', 'Gita Maria Monim', 2, '2026', 'EKSTENSI I', ''),
(233, 248, '2026032044011', 'Menius Murib', 2, '2026', 'EKSTENSI I', ''),
(234, 249, '2026032044012', 'Yoppy Yosep Hisage', 2, '2026', 'EKSTENSI I', ''),
(235, 250, '2026032044013', 'Erry Trukna', 2, '2026', 'EKSTENSI I', ''),
(236, 251, '2026032044014', 'Stanley A Pascal Usmany', 2, '2026', 'EKSTENSI I', ''),
(237, 252, '2026032044015', 'Renny Inseri Bukorpioper', 2, '2026', 'EKSTENSI I', ''),
(238, 253, '2026032044016', 'Dio Mimin', 2, '2026', 'EKSTENSI I', ''),
(239, 254, '2026032044017', 'Tonni Wijangge', 2, '2026', 'EKSTENSI I', ''),
(240, 255, '2026032044018', 'Oktovianus Filep Marani', 2, '2026', 'EKSTENSI I', ''),
(241, 256, '2026032044019', 'Pujiono', 2, '2026', 'EKSTENSI I', ''),
(242, 257, '2026032044020', 'Enda Widia Entong', 2, '2026', 'EKSTENSI I', ''),
(243, 258, '2026032044021', 'Sopia Bagau', 2, '2026', 'EKSTENSI I', ''),
(244, 259, '2026032044022', 'Susana Lena Kayai', 2, '2026', 'EKSTENSI I', ''),
(245, 260, '2026032044023', 'Oktaviani Bukwab', 2, '2026', 'EKSTENSI I', ''),
(246, 261, '2026032044024', 'Bonapasia Keiya', 2, '2026', 'EKSTENSI I', ''),
(247, 262, '2026032044025', 'Soleman Dimara', 2, '2026', 'EKSTENSI I', ''),
(248, 263, '2026032044026', 'Theodorus Moses Makay', 2, '2026', 'EKSTENSI I', ''),
(249, 264, '2026032044027', 'Dance Maga', 2, '2026', 'EKSTENSI I', ''),
(250, 265, '2026032044028', 'Alvian Patrikx Numberi', 2, '2026', 'EKSTENSI I', ''),
(251, 266, '2026032044029', 'Cansius Z P Kambari', 2, '2026', 'EKSTENSI I', ''),
(252, 267, '2026032044030', 'Daniel Kayoi', 2, '2026', 'EKSTENSI I', ''),
(253, 268, '2026032044031', 'Yemi Busup', 2, '2026', 'EKSTENSI I', ''),
(254, 269, '2026032044032', 'Fredricho Sampari Yulius Yawan', 2, '2026', 'EKSTENSI I', ''),
(255, 270, '2026032044033', 'Porurok Yahuli', 2, '2026', 'EKSTENSI I', ''),
(256, 271, '2026032044034', 'Alowisius Agustinus Sedik', 2, '2026', 'EKSTENSI I', ''),
(257, 272, '2026032044035', 'Abraham Karabek Awom', 2, '2026', 'EKSTENSI I', ''),
(258, 273, '2026032044036', 'Bertho Wanimbo', 2, '2026', 'EKSTENSI I', ''),
(259, 274, '2026032044037', 'Ferni Jikwa', 2, '2026', 'EKSTENSI I', ''),
(260, 275, '2026032044038', 'Aplena Wonatorei', 2, '2026', 'EKSTENSI I', ''),
(261, 276, '2026032044039', 'Devano Nalmark Dwaramury', 2, '2026', 'EKSTENSI I', ''),
(262, 277, '2026032044040', 'Karin Novia Bagre', 2, '2026', 'EKSTENSI I', ''),
(263, 278, '2026032044041', 'Yesaya Buiney', 2, '2026', 'EKSTENSI I', ''),
(264, 279, '2026032044042', 'Yulianus Hobera', 2, '2026', 'EKSTENSI I', ''),
(265, 280, '2026032044043', 'Kevin Ronaldo Fonataba', 2, '2026', 'EKSTENSI I', ''),
(266, 281, '2026032044044', 'Emiron Wonda', 2, '2026', 'EKSTENSI I', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mata_kuliah`
--

CREATE TABLE `mata_kuliah` (
  `id` int(11) UNSIGNED NOT NULL,
  `kode_mk` varchar(30) NOT NULL,
  `nama_mk` varchar(150) NOT NULL,
  `sks` int(2) NOT NULL DEFAULT 2,
  `prodi_id` int(11) UNSIGNED DEFAULT NULL,
  `semester_paket` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mata_kuliah`
--

INSERT INTO `mata_kuliah` (`id`, `kode_mk`, `nama_mk`, `sks`, `prodi_id`, `semester_paket`) VALUES
(1, 'ADM101', 'Pengantar Ilmu Administrasi', 3, 1, 1),
(2, 'ADM201', 'Manajemen Publik', 3, 1, 3),
(3, 'IP101', 'Pengantar Ilmu Politik', 3, 2, 1),
(4, 'IP201', 'Pemerintahan Daerah', 3, 2, 3),
(5, 'HI101', 'Pengantar Hubungan Internasional', 3, 3, 1),
(6, 'IK101', 'Pengantar Ilmu Komunikasi', 3, 4, 1),
(7, 'SOS101', 'Pengantar Sosiologi', 3, 5, 1),
(8, '1111', 'Dasar-Dasar Komputer', 2, 2, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-10-17-000001', 'App\\Database\\Migrations\\CreateAbsensiTables', 'default', 'App', 1792228062, 1),
(2, '2026-10-17-000002', 'App\\Database\\Migrations\\WidenKelasParalel', 'default', 'App', 1792235693, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaturan_kampus`
--

CREATE TABLE `pengaturan_kampus` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama_lokasi` varchar(150) NOT NULL DEFAULT 'Kampus FISIP UNCEN',
  `latitude_pusat` decimal(10,7) NOT NULL,
  `longitude_pusat` decimal(10,7) NOT NULL,
  `radius_toleransi_meter` int(11) NOT NULL DEFAULT 150,
  `syarat_kehadiran_persen` int(3) NOT NULL DEFAULT 75
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaturan_kampus`
--

INSERT INTO `pengaturan_kampus` (`id`, `nama_lokasi`, `latitude_pusat`, `longitude_pusat`, `radius_toleransi_meter`, `syarat_kehadiran_persen`) VALUES
(1, 'Kampus FISIP Universitas Cenderawasih', -2.5969440, 140.6685560, 150, 75);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pertemuan`
--

CREATE TABLE `pertemuan` (
  `id` int(11) UNSIGNED NOT NULL,
  `kelas_kuliah_id` int(11) UNSIGNED NOT NULL,
  `pertemuan_ke` int(2) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `jam_buka` datetime DEFAULT NULL,
  `jam_tutup` datetime DEFAULT NULL,
  `materi_kuliah` text DEFAULT NULL,
  `qr_secret_salt` varchar(64) DEFAULT NULL,
  `token_cadangan` varchar(6) DEFAULT NULL,
  `status` enum('open','closed') NOT NULL DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pertemuan`
--

INSERT INTO `pertemuan` (`id`, `kelas_kuliah_id`, `pertemuan_ke`, `tanggal`, `jam_buka`, `jam_tutup`, `materi_kuliah`, `qr_secret_salt`, `token_cadangan`, `status`) VALUES
(1, 1, 1, '2026-10-17', '2026-10-17 18:12:02', '2026-10-17 18:37:51', 'Pengantar Ilmu Administrasi', '1d71f6c5e26f0f2975d2ad07d2505c2d451f794d307030f344b0bea069525fac', '946915', 'closed'),
(2, 6, 1, '2026-10-17', '2026-10-17 18:56:06', NULL, '', 'c7d0a99d58d2d81311da6e65bf9b4c261ecddf53ee962862675e727e743d3b57', '956080', 'open');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peserta_kelas`
--

CREATE TABLE `peserta_kelas` (
  `id` int(11) UNSIGNED NOT NULL,
  `kelas_kuliah_id` int(11) UNSIGNED NOT NULL,
  `mahasiswa_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peserta_kelas`
--

INSERT INTO `peserta_kelas` (`id`, `kelas_kuliah_id`, `mahasiswa_id`) VALUES
(7, 4, 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `presensi`
--

CREATE TABLE `presensi` (
  `id` int(11) UNSIGNED NOT NULL,
  `pertemuan_id` int(11) UNSIGNED NOT NULL,
  `mahasiswa_id` int(11) UNSIGNED NOT NULL,
  `waktu_presensi` datetime DEFAULT NULL,
  `status` enum('Hadir','Izin','Sakit','Alpa') NOT NULL DEFAULT 'Hadir',
  `metode_absen` enum('qr','token','manual') NOT NULL DEFAULT 'qr',
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `jarak_meter` decimal(10,2) DEFAULT NULL,
  `bukti_dokumen` varchar(255) DEFAULT NULL,
  `catatan_keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `program_studi`
--

CREATE TABLE `program_studi` (
  `id` int(11) UNSIGNED NOT NULL,
  `kode_prodi` varchar(30) NOT NULL,
  `nama_prodi` varchar(150) NOT NULL,
  `jenjang` varchar(30) DEFAULT NULL,
  `kaprodi` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `program_studi`
--

INSERT INTO `program_studi` (`id`, `kode_prodi`, `nama_prodi`, `jenjang`, `kaprodi`) VALUES
(1, 'S1-ADM', 'S1 Ilmu Administrasi Publik', 'S1', 'Dr. Yohanis Ronsumbre, M.Si'),
(2, 'S1-IP', 'S1 Ilmu Pemerintahan', 'S1', 'Dr. Alimin Alwi, M.Si'),
(3, 'S1-HI', 'S1 Hubungan Internasional', 'S1', 'Dr. Marthinus Sesa, M.Si'),
(4, 'S1-IK', 'S1 Ilmu Komunikasi', 'S1', 'Dr. Jacobus Kastanya, M.Si'),
(5, 'S1-SOS', 'S1 Sosiologi', 'S1', 'Dr. Erfiningsih, M.Si'),
(6, 'S1-ANT', 'S1 Antropologi Sosial', 'S1', 'Dr. Wilhelmus Renyaan, M.Si'),
(7, 'S2-ADM', 'Magister Ilmu Administrasi', 'S2', 'Prof. Dr. Yacob Pattiasina, M.Si');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tahun_akademik`
--

CREATE TABLE `tahun_akademik` (
  `id` int(11) UNSIGNED NOT NULL,
  `tahun` varchar(20) NOT NULL,
  `semester` enum('Ganjil','Genap') NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tahun_akademik`
--

INSERT INTO `tahun_akademik` (`id`, `tahun`, `semester`, `is_active`) VALUES
(1, '2025/2026', 'Genap', 0),
(2, '2026/2027', 'Ganjil', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `role` enum('admin','dosen','mahasiswa','pimpinan') NOT NULL DEFAULT 'mahasiswa',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `email`, `role`, `is_active`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'dosen1', '$2y$10$pMIsbFzUe4YXKRixQOMrhO7BuuKnj.92WAIj2b8WVP5F8wPfcPCDq', 'dosen1@fisip.uncen.ac.id', 'dosen', 1, NULL, '2026-10-17 18:07:54', '2026-10-17 20:10:33'),
(2, 'dosen2', '$2y$10$t1S7NCwM88ka/0cHDfIPeOnkE8w7tY/C48OqHEZum.1/Y8JJPitG6', 'dosen2@fisip.uncen.ac.id', 'dosen', 1, NULL, '2026-10-17 18:07:54', '2026-10-17 18:07:54'),
(3, 'dosen3', '$2y$10$UoNjg8yQpjwvF8BImP2S1esErQ/Y2aJyPrTWauemtVTAIZrWOK/Le', 'dosen3@fisip.uncen.ac.id', 'dosen', 1, NULL, '2026-10-17 18:07:54', '2026-10-17 18:07:54'),
(4, 'dosen4', '$2y$10$D4LE6m/pMe0/R7YObkYHjO7emjJlIlMx2xfagTj42x6cHjfNFwNj6', 'dosen4@fisip.uncen.ac.id', 'dosen', 1, NULL, '2026-10-17 18:07:54', '2026-10-17 18:07:54'),
(11, 'mhs7', '$2y$10$NdeAygZMXvcZ.Sc/a1wYJ.gZhIiijBbyq8turkEeEdBvyntGPMo9q', 'mhs7@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 18:07:56', '2026-10-17 18:07:56'),
(15, 'admin', '$2y$10$SGtaUxjV77nf/kFU1R447uVUvkh3HQzk8CD5Gyzy24YB3ffcb0MSy', 'admin@fisip.uncen.ac.id', 'admin', 1, NULL, '2026-10-17 18:07:56', '2026-10-17 18:07:56'),
(16, 'pimpinan', '$2y$10$Y5MbA9wp3IxT.m5h14NU8OBT6v2MMH1pLWLjNaSx6OgVtMBEIx3/a', 'dekan@fisip.uncen.ac.id', 'pimpinan', 1, NULL, '2026-10-17 18:07:56', '2026-10-17 18:07:56'),
(17, 'john', '$2y$10$sApuxBMp.PzHqwbU0K1LxufAOkKd10lJK7b5JAwmCZJG0GUk.uF6.', 'john@fisip.uncen.ac.id', 'dosen', 1, NULL, '2026-10-17 18:52:30', '2026-10-17 18:52:30'),
(28, '2026031044001', '$2y$10$qnDm88L34QZtC8bZvkcgx.LmRdvMl4e4LAk9YV8iKhO.8gALKbDBS', '2026031044001@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:30', '2026-10-17 20:17:30'),
(29, '2026031044004', '$2y$10$d.zornO5uu1ENpsvzk2NAOIYDUjtpNK8GkRkWRrz22AiYwwX7msh6', '2026031044004@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:30', '2026-10-17 20:17:30'),
(30, '2026031044007', '$2y$10$rJPxGcoUgqJ7UQDobDLlEepoEGUOH//9GH/qr77Gkd1BSz.G4CtsC', '2026031044007@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:30', '2026-10-17 20:17:30'),
(31, '2026031044010', '$2y$10$DqYcA6mzs7/WeQTFKSNvRu.Lbwwsl0mDvDaHLbiCimfyZSyJxz7Eu', '2026031044010@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:30', '2026-10-17 20:17:30'),
(32, '2026031044013', '$2y$10$PMajrfgVmVcrnPDMnigpKOhrGnvKjHG5/OnqgjxRFYKufQZXsitM.', '2026031044013@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:30', '2026-10-17 20:17:30'),
(33, '2026031044016', '$2y$10$npaDAv19Mf6P1GAq3j/tf.TF4BaYLD2gFAeoDvzGzoq8SQd.nL.Va', '2026031044016@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:31', '2026-10-17 20:17:31'),
(34, '2026031044019', '$2y$10$ka92JZhHEeZZdroQ7m.NTeExk2woqJQrt8N.9BnFEztOq2y7eKqtS', '2026031044019@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:31', '2026-10-17 20:17:31'),
(35, '2026031044022', '$2y$10$BwZK9xU03UIP0LN/pv1UzOfBxmUgDp2SajKk0L/SDjLxtfvOnk.iy', '2026031044022@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:31', '2026-10-17 20:17:31'),
(36, '2026031044025', '$2y$10$TkRnQCpEkmIH9idzYtDJie/gQkFNbycR9jSb5LAMsJsGv/Jrsc2NS', '2026031044025@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:31', '2026-10-17 20:17:31'),
(37, '2026031044028', '$2y$10$OxUAi287XrFxpkWvDG27tuulslQeJN4zH6rrps2GEdc1sFgvlNb7u', '2026031044028@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:31', '2026-10-17 20:17:31'),
(38, '2026031044031', '$2y$10$CjaiYnTYpeemrywCeDoGjeM2MbXQUBIqtaiu3Mz1oQmdnmYZmsilG', '2026031044031@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:31', '2026-10-17 20:17:31'),
(39, '2026031044034', '$2y$10$SvRh7eSIhHCp2K0qhOgLNOcpHFg26XSfOnoWqXG3qiVxDQJZo5s.m', '2026031044034@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:31', '2026-10-17 20:17:31'),
(40, '2026031044037', '$2y$10$fqy8FcXuSvwdfZ1XPGbUK.DL7j4zMWGIjoJ.5bEHwpHEZi0lSqybG', '2026031044037@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:31', '2026-10-17 20:17:31'),
(41, '2026031044040', '$2y$10$qWvVB30QemkXmHXWvotmV.9jqX/8miwvEzb1yU.0T0hiTgbm3ObpG', '2026031044040@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:31', '2026-10-17 20:17:31'),
(42, '2026031044043', '$2y$10$/U80Q3mEOtKkhz9M2Gz21uUHjlwtkPh2iHxBoK2Ah9iAxJTMrmNBm', '2026031044043@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:31', '2026-10-17 20:17:31'),
(43, '2026031044046', '$2y$10$LY0vjU7ybtqREz.D1jqKTOV7v88cwHNgPUmaaPOuAS2xNHnm/D/nC', '2026031044046@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:31', '2026-10-17 20:17:31'),
(44, '2026031044049', '$2y$10$G1K33BtZpQA8EGkuwTMzuOQJW6Q2y7mukOD7Cu5x8EjH9y3fC79EO', '2026031044049@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:31', '2026-10-17 20:17:31'),
(45, '2026031044052', '$2y$10$x6a6b79MfhpuN861TjWStuirs4BvxL2NevxbksFqBNkCwgqimfQma', '2026031044052@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:31', '2026-10-17 20:17:31'),
(46, '2026031044055', '$2y$10$4nXXOPlkJOLVh7uBwmXbKO5WyEW1.kOkKyb1DCRiESX2L819VOgaq', '2026031044055@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:31', '2026-10-17 20:17:31'),
(47, '2026031044058', '$2y$10$w11.jq4kmPuhpWutNdCY3ui4Sh1I0TUcpGRIx1Sy2Y3Nos7Jtz4Ga', '2026031044058@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:31', '2026-10-17 20:17:31'),
(48, '2026031044061', '$2y$10$vNZ1Lx4wZg/sY69dpL8TlOv3.5jbS4J1eSvNAJmFR4bK4vJmYUZ/e', '2026031044061@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:31', '2026-10-17 20:17:31'),
(49, '2026031044064', '$2y$10$1N7JO6DicA..PLBPsvICJO7EWUV8vGM/.XUeZPkOwrsPZ2uZN0k92', '2026031044064@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:32', '2026-10-17 20:17:32'),
(50, '2026031044067', '$2y$10$A.1kNeVC1sQSp7ZBpS/OOeA3sNkTb03sNzJ6CvlX4QyaPgp0oEztq', '2026031044067@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:32', '2026-10-17 20:17:32'),
(51, '2026031044070', '$2y$10$Z17FO1Pq3Em50xL4oIknN.7.2Phx810eTAICNtjB66HLhFI832wg.', '2026031044070@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:32', '2026-10-17 20:17:32'),
(52, '2026031044073', '$2y$10$xmn1YV9c3crVcmd2nNV8s.3iww4fsTotI5A/L3v4OQwX.8gMYjGV2', '2026031044073@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:32', '2026-10-17 20:17:32'),
(53, '2026031044076', '$2y$10$sAmj8n6ITAV2uMxAYgxJEuy4PscA6AdJ0/EO3OlI6N96FVf/37XWC', '2026031044076@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:32', '2026-10-17 20:17:32'),
(54, '2026031044079', '$2y$10$lkWL1yySPUxgRqz7WKdSQu81KDnuAYYxpGjs1FOV/CFh6QfwGgj.2', '2026031044079@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:32', '2026-10-17 20:17:32'),
(55, '2026031044082', '$2y$10$9D.WzLFRvirbTMDMben.eOO.aZNi56/SjtPxh6BZkdRXhvzPhGVnO', '2026031044082@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:32', '2026-10-17 20:17:32'),
(56, '2026031044085', '$2y$10$6G.ZBpoS84VeRI3pxf8Sde6QZkT16j1LIp8b5NfnKKPwRLAzXpx2O', '2026031044085@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:32', '2026-10-17 20:17:32'),
(57, '2026031044088', '$2y$10$Ue.CcI9PnN1GLUEMDc.nOOll4TNg2B5bGouaQ7wAjSxAd.tF9/1fi', '2026031044088@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:32', '2026-10-17 20:17:32'),
(58, '2026031044091', '$2y$10$a.p7anm/MLV1TdcWwlPQXek0iJV5V4e9e..n9YZDpPU1ScW8knERu', '2026031044091@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:32', '2026-10-17 20:17:32'),
(59, '2026031044094', '$2y$10$YLNw1SHWWVjs3x0o/dF2q.UybQd8Lp7r0rSH4Bw440T0jYIlyyl5a', '2026031044094@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:32', '2026-10-17 20:17:32'),
(60, '2026031044097', '$2y$10$Pd18fEe/p/uDH8W9fmX2k.Lt03dwLn1dTUUnRP4vpu4QV6J001QEG', '2026031044097@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:32', '2026-10-17 20:17:32'),
(61, '2026031044100', '$2y$10$jX5HMU3B.Ob7vIybQ7HWouBtWl1Y6e0pA.vZ0N7lu9j9IZGqIuWGO', '2026031044100@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:32', '2026-10-17 20:17:32'),
(62, '2026031044103', '$2y$10$eIpBvG7vyr9e4X4L/5WQrOwE.jEIlUrkOLbL4V6jFPXZe1OuclbLG', '2026031044103@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:32', '2026-10-17 20:17:32'),
(63, '2026031044106', '$2y$10$8jKdu5d0/ouBwmk3X0RIo.NzEbIrQlD4K3kELL.tHCeac6qNAjUJO', '2026031044106@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:32', '2026-10-17 20:17:32'),
(64, '2026031044109', '$2y$10$GEzsokbUZoKSwKo3/u1kSOD.fKFd8IvsMOGWoQP9h8o.RqZVjX6um', '2026031044109@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:33', '2026-10-17 20:17:33'),
(65, '2026031044112', '$2y$10$0hqHfVRz94uo/r27I1SeO./9C7UoC0JMpy5F2FYIHAzWpgKBxBNlC', '2026031044112@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:33', '2026-10-17 20:17:33'),
(66, '2026031044115', '$2y$10$sKzuOXqd83qXIcbFJv4Ga.oKLs7t4G87IZMmDzGv6yTCfi0p04uqa', '2026031044115@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:33', '2026-10-17 20:17:33'),
(67, '2026031044118', '$2y$10$Oxp1r5DdHkogaJxAAS8Kyek//tQOAsuK1iWqHABcMRUtsGhiMcCTm', '2026031044118@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:33', '2026-10-17 20:17:33'),
(68, '2026031044121', '$2y$10$1alaxfxgwww/U9ekKMJwker4.TNoMuzeK3URQy5uVGtcGhv8SgUV2', '2026031044121@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:33', '2026-10-17 20:17:33'),
(69, '2026031044124', '$2y$10$hphuwZSXyvCw1XB0PccK0edbgIPLipSk6.k5g4VBaOyxVYJAHFT7O', '2026031044124@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:33', '2026-10-17 20:17:33'),
(70, '2026031044127', '$2y$10$jOKzTghtz0xOG0OZKrmK5upZKp.UP//igfCG1E4Wvwbys.3AHvAL.', '2026031044127@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:33', '2026-10-17 20:17:33'),
(71, '2026031044130', '$2y$10$tYR3Y0BmDEUCda/lHr8gYedyw.Kx9zwWusWc/NuWmgteNejQ6OaTC', '2026031044130@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:33', '2026-10-17 20:17:33'),
(72, '2026031044133', '$2y$10$AdBabPLhcl3h/MO0AkdsxOD8lquZbCfVbdHyHHt0XDzBAaWn5QxRC', '2026031044133@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:33', '2026-10-17 20:17:33'),
(73, '2026031044136', '$2y$10$2kEimZUZ9trinC6ITl7lrOcpNX4Tf0cS1HkJBHzkOHBHecIKDiTAu', '2026031044136@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:33', '2026-10-17 20:17:33'),
(74, '2026031044139', '$2y$10$5b0mC2TBGOkbjWGV4ISUJexN6i.qIGnkeiOhcNYzqTDxTnZA9voXG', '2026031044139@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:33', '2026-10-17 20:17:33'),
(75, '2026031044142', '$2y$10$hUj6BeEUAMF/gV2DHDLxNeK.DeEMxUDDyUG5D0creFhgIOoaSifFy', '2026031044142@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:33', '2026-10-17 20:17:33'),
(76, '2026031044145', '$2y$10$KGTBRvBnsV2bmRxrHieO9OFcPGlxtqfHZA1YORAKosvIZqOOQn4qy', '2026031044145@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:33', '2026-10-17 20:17:33'),
(77, '2026031044148', '$2y$10$sWvwztFRVk.JP/W64.WFsuEiiGPLuezDii1N7GLcalRB.jj2iy1TG', '2026031044148@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:33', '2026-10-17 20:17:33'),
(78, '2026031044151', '$2y$10$q8uUnsgalud1uY1ONDE8eOLUCfig.S0YxugYslvXyZo9A.QCw4EXe', '2026031044151@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:33', '2026-10-17 20:17:33'),
(79, '2026031044154', '$2y$10$kV67YfoPhkZdj8ZPi8/jZeMcP3lDOb7B0GHHvQ08mF8zgFoeYrBvK', '2026031044154@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:33', '2026-10-17 20:17:33'),
(80, '2026031044157', '$2y$10$Llyu7xEATlSMzgJJVgKZiuNgwe7VtgEsCkiNAqMsikIPKU2h8fXt6', '2026031044157@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:34', '2026-10-17 20:17:34'),
(81, '2026031044160', '$2y$10$pKFW7gkYYcMaGQlICs8CTeTl79lQQ0OsmXpvdgQe.79razcbvJlsW', '2026031044160@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:34', '2026-10-17 20:17:34'),
(82, '2026031044163', '$2y$10$0lC7I1c.KlCXviWY7Scq6ui03qGd/je9d3KrCO94ARwF1p8WrSbdG', '2026031044163@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:34', '2026-10-17 20:17:34'),
(83, '2026031044166', '$2y$10$DAg9Qs.vwMkZB9S1UVKqGuWkipQm4oNTlGSFPVbp/eHKoJkh3lgBy', '2026031044166@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:34', '2026-10-17 20:17:34'),
(84, '2026031044169', '$2y$10$fqbXYeGtzbuN9j/4HaggKeBOB4M3kxYOFc.vvBPJt1ujp.7hIl7yC', '2026031044169@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:34', '2026-10-17 20:17:34'),
(85, '2026031044172', '$2y$10$3wfAdwYQXEAMcSlhcHLgdOY8w0kuP7deyhUC.LGBjsg8fNw999bmi', '2026031044172@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:34', '2026-10-17 20:17:34'),
(86, '2026031044175', '$2y$10$LDT8hMqSgC2VtBfV2VtDeeBNDITv6yOseFLIiMKQK4JRqwhCk7t6m', '2026031044175@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:34', '2026-10-17 20:17:34'),
(87, '2026031044178', '$2y$10$A1gjbXzqq47gOWl6F4lEfOnMwYS18miLtlYQgS89t6q5lwzXpZ0NW', '2026031044178@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:34', '2026-10-17 20:17:34'),
(88, '2026031044181', '$2y$10$SqhiiELEWINskloEsF3Uzu3IBXj..1mL8rec0E.CrgUQpJJ6J.Wwm', '2026031044181@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:34', '2026-10-17 20:17:34'),
(89, '2026031044184', '$2y$10$/X28REkXQuz1DI.ouIjOJuSbZ9KdBWbzdm6QNi6bDF4BavDZu0mEi', '2026031044184@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:34', '2026-10-17 20:17:34'),
(90, '2026031044187', '$2y$10$rNYjBkqVRFifzQxvZvz1/ewFlQtI3SAQAo/IkTFdW3VSAJXcnI2zu', '2026031044187@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:34', '2026-10-17 20:17:34'),
(91, '2026031044190', '$2y$10$RJ2qJKflHsX7nqBYmsdxEuvxOd4hk2ZCCUT8FNkf.47mMxzHWVWlW', '2026031044190@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:34', '2026-10-17 20:17:34'),
(92, '2026031044193', '$2y$10$n7QCJvHk7OlZu.6FKCLBc.FHkjiwznBTPC2zwb36Fc5SyY/ccHcS.', '2026031044193@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:34', '2026-10-17 20:17:34'),
(93, '2026031044196', '$2y$10$l6KgXRk3G/7Gmc9nu./viucC88CcKz2ZiJadExa0u2Cn8tKfSAejW', '2026031044196@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:34', '2026-10-17 20:17:34'),
(94, '2026031044199', '$2y$10$WtV5PJW1.Tgte02qWEzYaODSti4Wc/gf/Ze3KQGC12.VzSeS.3DFm', '2026031044199@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:34', '2026-10-17 20:17:34'),
(95, '2026031044202', '$2y$10$yxB465pVpG./j5HOuY6fduMm7wQMMQhLUkfUjTxyVH2qULHKWG9nm', '2026031044202@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:34', '2026-10-17 20:17:34'),
(96, '2026031044205', '$2y$10$6cWUnLf0ZcvG6M834wNDX.aQ/lJUTxDtv4Yyhqp48QBPrsaWib7iS', '2026031044205@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:35', '2026-10-17 20:17:35'),
(97, '2026031044208', '$2y$10$XsABMDNK3amk.nRva/Zw2uOf/F01wxm2BlELgrUQWz2urJ5dtnGcO', '2026031044208@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:35', '2026-10-17 20:17:35'),
(98, '2026031044002', '$2y$10$trzdkvTMof6O7G30vLM07ukdJxn4KqY9dgeT5yYDQ1AzuY3Hv9/aW', '2026031044002@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:35', '2026-10-17 20:17:35'),
(99, '2026031044005', '$2y$10$QtJqCXytTGuuFrDJDtnrTePZkPrdV8CymWJyEQhEvhxkEoaoT3DIm', '2026031044005@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:35', '2026-10-17 20:17:35'),
(100, '2026031044008', '$2y$10$AWbp51/pZguoMC.OMloek.s35vqNgloTDZa8c2qTtav/OS4bdvWbO', '2026031044008@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:35', '2026-10-17 20:17:35'),
(101, '2026031044011', '$2y$10$ZcIxc2JMdcbgj4gMI7IlvueLuTIeansGvgKkF/2sq8XRnDkBXLkxe', '2026031044011@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:35', '2026-10-17 20:17:35'),
(102, '2026031044014', '$2y$10$9oReA.XtbtWkHLWXc9VPT.v5kmcIrLsab4mAo0tFjFxNUkPH/IzZO', '2026031044014@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:35', '2026-10-17 20:17:35'),
(103, '2026031044017', '$2y$10$e99vk70iQ2Pepvrn7qonkOBkSm5.MotrqVHa6HohEbvFNaYz0mb3S', '2026031044017@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:35', '2026-10-17 20:17:35'),
(104, '2026031044020', '$2y$10$ZwZWnDSt/.c0V/kbs/OZg.yejOejFiOlKoP3MVDrNsvuaB.9vtKD.', '2026031044020@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:35', '2026-10-17 20:17:35'),
(105, '2026031044023', '$2y$10$rn7SHPKSWgWmcFriRJ5jA.Mvd8/dOXoL5658bO64c4gghUosQ2HuC', '2026031044023@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:35', '2026-10-17 20:17:35'),
(106, '2026031044026', '$2y$10$3idCJHMUlK3RP6JkMJmUYO4/fSIwgvoGHqm30xPlT5.1A22d01ije', '2026031044026@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:35', '2026-10-17 20:17:35'),
(107, '2026031044029', '$2y$10$MPfioRQ7.Ua1h.oSzkXOh.bvkqw3ocsfCI5LnDnaiUZEDdWqsiI02', '2026031044029@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:35', '2026-10-17 20:17:35'),
(108, '2026031044032', '$2y$10$HCSE5bnavpC7USytT89Db.if1WSXd0SWLTOYqa/EPAK2ZvoMzLvle', '2026031044032@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:35', '2026-10-17 20:17:35'),
(109, '2026031044035', '$2y$10$iXuaM817QwThnjtOyN5PfOud1mg/j0fwsXEfnPe1.nmhp4oPiqEhG', '2026031044035@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:35', '2026-10-17 20:17:35'),
(110, '2026031044038', '$2y$10$gKMBHniLXGxaguOw6MXY8u/LxWFuYH4UgJY.rhAGpAR5LsPpXm/82', '2026031044038@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:35', '2026-10-17 20:17:35'),
(111, '2026031044041', '$2y$10$VL/ruVp01QU4Cu.qibwEweLo.0ICdtKkrAqC3D7vRYom3U30WMiZq', '2026031044041@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:35', '2026-10-17 20:17:35'),
(112, '2026031044044', '$2y$10$iN0xOplBQmDg9OyqmjjEUePI9WOAbkP9Ug/NbCN5GnAVxNjnyW44y', '2026031044044@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:36', '2026-10-17 20:17:36'),
(113, '2026031044047', '$2y$10$umNWmmeBVLi1.dgQbeGFFeV2wcINFiAtqmzOOv6yMles72ZyHhwuC', '2026031044047@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:36', '2026-10-17 20:17:36'),
(114, '2026031044050', '$2y$10$P6u2MrqMlTM4dDf85QuWNOIdSUKeU6t9tTOvzG0j2IBUnS4MpyNE.', '2026031044050@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:36', '2026-10-17 20:17:36'),
(115, '2026031044053', '$2y$10$O/kTK41r.vnXyidAbhOQdOwHdNh.j10wkjFXeB2ebACgqA/rl8aa.', '2026031044053@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:36', '2026-10-17 20:17:36'),
(116, '2026031044056', '$2y$10$ZfMxtIFLsH6R9m7MoHqzwOSzybduRnjQHqbQbSLawuWaZAcFDjrBW', '2026031044056@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:36', '2026-10-17 20:17:36'),
(117, '2026031044059', '$2y$10$RjH9Z9Wl2xIXxgHcU/D45O/QJ4izVP1fLl8DmVr9ing4xp4dCIY6m', '2026031044059@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:36', '2026-10-17 20:17:36'),
(118, '2026031044062', '$2y$10$BpFGPlvgv6zSZ8Pe.zi2E.Q0RwQGYuKFOddwoVNenHZqzRoO/eZFe', '2026031044062@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:36', '2026-10-17 20:17:36'),
(119, '2026031044065', '$2y$10$YI7mp8pEnR5hJfQJdU8Uk.zpkNoIFieR.eq7K8LQaNteHAUc7nJcK', '2026031044065@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:36', '2026-10-17 20:17:36'),
(120, '2026031044068', '$2y$10$8FEj9CY1Tb0Cki.vKiF91.BM9NvBKQNB/od0vfvi1AlWfHbpXw8t2', '2026031044068@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:36', '2026-10-17 20:17:36'),
(121, '2026031044071', '$2y$10$2Tt7tW18Bc8bvgw6BjfirOw4AvCqE8Ibz63zreU76UaznuQ84hjFy', '2026031044071@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:36', '2026-10-17 20:17:36'),
(122, '2026031044074', '$2y$10$Y5zKwDVhbFK4BItZV1crDek/Yfc0xe0arlX7aIJigMO.oHmoBDTKK', '2026031044074@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:36', '2026-10-17 20:17:36'),
(123, '2026031044077', '$2y$10$xxXDd2KwpbBKnrS6Upw0KOtuJURwygMV65yZ6jQnLREe5AFTalsWu', '2026031044077@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:36', '2026-10-17 20:17:36'),
(124, '2026031044080', '$2y$10$B//N.d0Klfg3t.vDq82yyuVd3IQbWyOtSEW/yqn3PijzU.mpg1BYe', '2026031044080@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:36', '2026-10-17 20:17:36'),
(125, '2026031044083', '$2y$10$iiK8otXeXoaskFcZxMeaS.KuADBWJQKZALCrsWqBENLyNHzgHhyhm', '2026031044083@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:36', '2026-10-17 20:17:36'),
(126, '2026031044086', '$2y$10$V/YTE1iUX1DXrc63OZ3nNeon0XLt9Ys1Ojpbn2EiAly/GOux5.Ghm', '2026031044086@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:36', '2026-10-17 20:17:36'),
(127, '2026031044089', '$2y$10$Eb3lvP2jhktrhrKWJawNae4zG0OXHVQoKEWwQa1OKAig0V2JuAOMS', '2026031044089@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:36', '2026-10-17 20:17:36'),
(128, '2026031044092', '$2y$10$wLxu5swvSqdKZUNOdusge.G9d.wRCnuOP0nZ75xF1./aWF/9jk42i', '2026031044092@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:37', '2026-10-17 20:17:37'),
(129, '2026031044095', '$2y$10$JuFzT.JkS5JuDnwEWqe3ieRjOxk7wc0Vpuk6xpI/AiG32FKAqGGVa', '2026031044095@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:37', '2026-10-17 20:17:37'),
(130, '2026031044098', '$2y$10$7T3SSlIB5tceikZg8EPuy.doW8vFi758WKTc8.q7dQOzSKcOGzPCC', '2026031044098@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:37', '2026-10-17 20:17:37'),
(131, '2026031044101', '$2y$10$wZPUVJhNPhKWXIe50dos/enovsuDqko4tfq3Lyiq.dHowOC83Tr6m', '2026031044101@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:37', '2026-10-17 20:17:37'),
(132, '2026031044104', '$2y$10$Uj40FF9L82whdZIxR24F4eAIVYqm3/bOA52OLKe1sSycQ7KZr9ZQy', '2026031044104@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:37', '2026-10-17 20:17:37'),
(133, '2026031044107', '$2y$10$AYcxSn8iLEm.y5VZmO1MTeVa.8xYQxGfd4HKwVYBaLUTRCIqmsK2G', '2026031044107@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:37', '2026-10-17 20:17:37'),
(134, '2026031044110', '$2y$10$SU3jJUWvFUhzGGrtlHB7S.T44hkWjecV0iVGQQ.lhP6DDnZeBb1Nm', '2026031044110@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:37', '2026-10-17 20:17:37'),
(135, '2026031044113', '$2y$10$2JqM/wx8CYYq1gBwKwYb6e.CRllq4R5QW8srSro92UmPj4U2e4MZm', '2026031044113@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:37', '2026-10-17 20:17:37'),
(136, '2026031044116', '$2y$10$S.RSi8StaDOaJUwTuEnEUu5cbd1PeYqyjKHEP6RYXSEOZ11sQ3dki', '2026031044116@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:37', '2026-10-17 20:17:37'),
(137, '2026031044119', '$2y$10$ldHQDrkOgccoPov2cjgvT.mr2UCWlDvUmRJlaeaaysy/pXSzvJ.U.', '2026031044119@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:37', '2026-10-17 20:17:37'),
(138, '2026031044122', '$2y$10$qHiSW3Nv9LO0AtZzLxrbLOJI7wgKFfKE9Lb/7kDMCudyPncX9h9f.', '2026031044122@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:37', '2026-10-17 20:17:37'),
(139, '2026031044125', '$2y$10$4g9BXaupQtwhMlAmCkYB0OL5P4n2B7ILJFVApbxqBK7jGF2x1OyBG', '2026031044125@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:37', '2026-10-17 20:17:37'),
(140, '2026031044128', '$2y$10$CUNsUH5jA2agAz1jIcwMfOUpQHZsZnCxQqgOle8D0rXQvhahNTU7S', '2026031044128@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:37', '2026-10-17 20:17:37'),
(141, '2026031044131', '$2y$10$n9WA53b/lCWjnEa94NA4Yeix6BRdzMFz0JJuNPfEZygzK/UgnHkD6', '2026031044131@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:37', '2026-10-17 20:17:37'),
(142, '2026031044134', '$2y$10$dVLOJcLpNxiIfP4/v7bNZu6xS4qN.CIidl4c4ZeE1.tpki68XFHk.', '2026031044134@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:37', '2026-10-17 20:17:37'),
(143, '2026031044137', '$2y$10$9iLG8tGvWDqWsQPXHhSF5uwgycbkXg5QK/lg57k7CRHdEHjZ9cfNq', '2026031044137@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:37', '2026-10-17 20:17:37'),
(144, '2026031044140', '$2y$10$2jcDuFvnzP0ZUzjbITsQ7.Ab9Y3.8oxyUrLR8Abc5fs9kYpX0WmQW', '2026031044140@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:38', '2026-10-17 20:17:38'),
(145, '2026031044143', '$2y$10$kcUpJGF5vIJr4sw1u3fsceR4HuEUDRsiLrPWQNK28wtt37Tqo38PS', '2026031044143@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:38', '2026-10-17 20:17:38'),
(146, '2026031044146', '$2y$10$e1hqHjsmT.m0Ki9flD9RIu.dFzGC3T.DW2hWPAW7G/2dVwMGzAZKS', '2026031044146@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:38', '2026-10-17 20:17:38'),
(147, '2026031044149', '$2y$10$2K/HJdyudc3TV06iJ.fK/eSOMzWTokc6CBl/clcaZBEm.8C2eMm0u', '2026031044149@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:38', '2026-10-17 20:17:38'),
(148, '2026031044152', '$2y$10$vwBCa4XaoPTB1zTw1sHmLeh3c1HPL7AyrMMmHYztwLa1MTbQcvmmO', '2026031044152@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:38', '2026-10-17 20:17:38'),
(149, '2026031044155', '$2y$10$e6gvqzsHXq7U.TNt4Fn3MuRCT828eSyVTry9RWojLSeEK/0r7iVDq', '2026031044155@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:38', '2026-10-17 20:17:38'),
(150, '2026031044158', '$2y$10$HK74hMPxelKe9kzCPESqIunEYNBv/yBGoRWhLbwi/sTKyIO.lk/tC', '2026031044158@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:38', '2026-10-17 20:17:38'),
(151, '2026031044161', '$2y$10$IDUdXzvmpXN9b4AwE6bQz.kmYRrzJAxTH/8Ka0X0nB6GsgcMklJEG', '2026031044161@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:38', '2026-10-17 20:17:38'),
(152, '2026031044164', '$2y$10$hb/7KspT4HZYjQcQfSKMvOcR.fgySP/dRo8gKoplrOYVVUapIv.2u', '2026031044164@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:38', '2026-10-17 20:17:38'),
(153, '2026031044167', '$2y$10$ffmu9k85R7eoTyrX3kK/UuGwijWI6oNrr.8ssArr/iWfF2WxTnSvy', '2026031044167@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:38', '2026-10-17 20:17:38'),
(154, '2026031044170', '$2y$10$yGbTd.2IUivGW/v2UodOCuctsVjroC6UkW4qRozKtfpcHM6XvSAHe', '2026031044170@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:38', '2026-10-17 20:17:38'),
(155, '2026031044173', '$2y$10$.bXNQ4eGZtkNHQFpbQE9GeK25xFx18llTUMEtktM1oirZMn0wAKWi', '2026031044173@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:38', '2026-10-17 20:17:38'),
(156, '2026031044176', '$2y$10$B.6BY8JM9H6X3.6CHt1f6.wms4FtiPbpxVOw/gmHLKeoUGGZTE8VG', '2026031044176@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:38', '2026-10-17 20:17:38'),
(157, '2026031044179', '$2y$10$1/hfzGwjoD/Dyheu.6ILDeX3iT2b3C15BbWDrvIIWSUoateLSWLaC', '2026031044179@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:38', '2026-10-17 20:17:38'),
(158, '2026031044182', '$2y$10$wLTJjuMWsYK.tTkOOzd8pOxPQ1bmmnDl/d.uD43Okc/kMOwm2N7Jq', '2026031044182@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:38', '2026-10-17 20:17:38'),
(159, '2026031044185', '$2y$10$T8m6xPjd.tCe9vVeT9xMD.Rx/ckcoIYx60aAWGHWEvLyzNNdZuZ0u', '2026031044185@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:38', '2026-10-17 20:17:38'),
(160, '2026031044188', '$2y$10$vn/IgJ7lHAnFsNRUg6b3n.3CKjpqRLkhWeL3EEGMt1tWPIb/BtVkK', '2026031044188@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:39', '2026-10-17 20:17:39'),
(161, '2026031044191', '$2y$10$EmPpWDzKVdgUQMEIRPI01ODq2HGANKePi0lAJZJsAHeupSgwX0oU.', '2026031044191@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:39', '2026-10-17 20:17:39'),
(162, '2026031044194', '$2y$10$7CZ3/HLx7Cbqd1Hu5uYUY.SHjoVMZJKQku1u3OHV.sNNxmczKi9.C', '2026031044194@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:39', '2026-10-17 20:17:39'),
(163, '2026031044197', '$2y$10$mI6Z.vMy/AsBZfvxALK7fuBwf7GGcP3ILvx3i1bwv03vUn/5yoIxO', '2026031044197@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:39', '2026-10-17 20:17:39'),
(164, '2026031044200', '$2y$10$IFEDuwy/Aw4f.3juYEli9.uzIaBjfAQULQElcaJiPeL6S46A.hbyi', '2026031044200@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:39', '2026-10-17 20:17:39'),
(165, '2026031044203', '$2y$10$4Dn8effflVBYKy4sCmV8x.7tN2vnHlaFY88R9ZPqW4k7E3sK3hg1S', '2026031044203@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:39', '2026-10-17 20:17:39'),
(166, '2026031044206', '$2y$10$p0lrGYIsRWgPjCGJ3Gy7WOQMsZCf.pnO2h8pvDNlvcwkH2Nel3wcC', '2026031044206@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:39', '2026-10-17 20:17:39'),
(167, '2026031044209', '$2y$10$OKia0SOMVOeWXusTqlyrguqZ5L/P8YadkEFF7zSJhN3lKKSRvRKSa', '2026031044209@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:39', '2026-10-17 20:17:39'),
(168, '2026031044003', '$2y$10$yUrdOGnXELFBxeYwzv79Ne5eAp.ymkP43u37j.eZibdQ2cyELsKny', '2026031044003@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:39', '2026-10-17 20:17:39'),
(169, '2026031044006', '$2y$10$hd70tjsxCSEtr6PtymX36u/bWbm9HQtVRJL360R3QUJj2XuFJQqu2', '2026031044006@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:39', '2026-10-17 20:17:39'),
(170, '2026031044009', '$2y$10$kX4RVk3U1meiqw4N/gwEQu6AL/nss7Rg0HjMNC1SDMvpi8pLVT7zm', '2026031044009@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:39', '2026-10-17 20:17:39'),
(171, '2026031044012', '$2y$10$qS8z8VXy1I14q5.S3674zuH/hlgmpUC94snYtYSdJ3qbAgih06lzG', '2026031044012@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:39', '2026-10-17 20:17:39'),
(172, '2026031044015', '$2y$10$4D6rLGuM67Mk9gQwEBsEZuZAf/uQvNqO4mnND3G7oSKbEHf5DBatO', '2026031044015@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:39', '2026-10-17 20:17:39'),
(173, '2026031044018', '$2y$10$79bsAYKtRd2XzpXwLt8eh.7V.IicHySSH7aPAHFZuIvH7G4dqk6d2', '2026031044018@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:39', '2026-10-17 20:17:39'),
(174, '2026031044021', '$2y$10$5fAj2DxW/ihYl0TfTbD2NO38Z1ocVPvyJd7966FOK.lwnjLjmdcrO', '2026031044021@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:39', '2026-10-17 20:17:39'),
(175, '2026031044024', '$2y$10$IeoeC3OYils.prPJGcXtw.Ldi90TTY9oXIKCzMXufYKCpvjp9W0Vq', '2026031044024@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:39', '2026-10-17 20:17:39'),
(176, '2026031044027', '$2y$10$Li5X8IDVa25Cv3d.dKuI6ucAR1EDFpuv9L6gXt4DuaPscd4j1b9MS', '2026031044027@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:40', '2026-10-17 20:17:40'),
(177, '2026031044030', '$2y$10$cP5WOgeo13hTs5yc7ZCMIuUo51XkC.yjAbEAeagaOGybkESz8g/T.', '2026031044030@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:40', '2026-10-17 20:17:40'),
(178, '2026031044033', '$2y$10$BMoP0GElVbrN3oMKq1FQDeYbPADD9293Xe2yd2Tgk8V/a4RfDDmKe', '2026031044033@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:40', '2026-10-17 20:17:40'),
(179, '2026031044036', '$2y$10$Gg0CDv6c8YIhcBC75oXAPORpf8FwIARMxiiyAqGu4mseCuSNiIbHm', '2026031044036@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:40', '2026-10-17 20:17:40'),
(180, '2026031044039', '$2y$10$K/N5lPvdcrR75YCvJUg8xufCcYBB/GfsTBzlcEDErT5kwfWqsMRDm', '2026031044039@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:40', '2026-10-17 20:17:40'),
(181, '2026031044042', '$2y$10$Y3dkcXKZj6jCruRpZVn6dew1rJzROdHfC1gSXyUAPRnBN8NqrC4PG', '2026031044042@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:40', '2026-10-17 20:17:40'),
(182, '2026031044045', '$2y$10$GcTeUmbGagwgEgTGVulaveSEQboN4cIGf9Ik3L6.PYrF6.B4smaTm', '2026031044045@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:40', '2026-10-17 20:17:40'),
(183, '2026031044048', '$2y$10$Uq5Smq.AHN/THJhkMqcxQO86EjL/.3o57LGQxUuCgtvtLzDKV4bxW', '2026031044048@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:40', '2026-10-17 20:17:40'),
(184, '2026031044051', '$2y$10$bFc1hlIif7ZaFoxddDewCuyNRlLJ/zheHAGLNgT9i7NcmaZ5YwTDa', '2026031044051@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:40', '2026-10-17 20:17:40'),
(185, '2026031044054', '$2y$10$FfvzaCfGWG03VaDx4Qe0W.ZRlI2.kmNAi.q01OwXKRNsVj.tmue2q', '2026031044054@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:40', '2026-10-17 20:17:40'),
(186, '2026031044057', '$2y$10$YUN6fhp6OnA/t90hfJCHH.TaVX8VY4KrTr0RzTzkdMuYFOcLtQclC', '2026031044057@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:40', '2026-10-17 20:17:40'),
(187, '2026031044060', '$2y$10$E3vuYsQfQbD4akW7KOFjTOferbUYbrAy4yQVKDmv/jhnkLkIyXeOG', '2026031044060@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:40', '2026-10-17 20:17:40'),
(188, '2026031044063', '$2y$10$SGEO8Kt.U0zeyFRuGLL3yeIhKv5DZIPi94UimO2YLmLN1VC4HjtT2', '2026031044063@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:40', '2026-10-17 20:17:40'),
(189, '2026031044066', '$2y$10$DhOQtvkWwFkyVYc5rzIpz.pEpQXbRrKRtB.ZSt5ZRVqa36Zy.avKS', '2026031044066@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:40', '2026-10-17 20:17:40'),
(190, '2026031044069', '$2y$10$4T/oJNb9KqLE5fiXDG2w7uBMfSE3XLVeOqiVefqCR0m5lu6MverjS', '2026031044069@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:40', '2026-10-17 20:17:40'),
(191, '2026031044072', '$2y$10$jc7Kzih1Xm9fzTq6V0x7remD8vcgvAjTShCDpSdGJNI0nGcz7DBFC', '2026031044072@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:40', '2026-10-17 20:17:40'),
(192, '2026031044075', '$2y$10$BG4zNGdda7u/FW2gkaDipehlOPcvPihZylmsSBeYnY6BUhqVuXZgK', '2026031044075@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:40', '2026-10-17 20:17:40'),
(193, '2026031044078', '$2y$10$r0qlxov32kXcbFITr95L0OMQdqonL97CTr7Ng8UtCASUBjMtkKEL.', '2026031044078@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:41', '2026-10-17 20:17:41'),
(194, '2026031044081', '$2y$10$Yi3Uhf0yFnOhpbXHuUU5ZuHy5/72wRcdAEGRLGWN0d4GH83Q9EVve', '2026031044081@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:41', '2026-10-17 20:17:41'),
(195, '2026031044084', '$2y$10$rvGmo7gaYLG1vBZ.wnuOpuc8r40l/ArDiYqcQWieaMAdYLRjt3U2C', '2026031044084@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:41', '2026-10-17 20:17:41'),
(196, '2026031044087', '$2y$10$.bUcO.U7Daq4N3YzXnXiWulsjLA92l679UL6I03Du7cSDOP1E9fEy', '2026031044087@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:41', '2026-10-17 20:17:41'),
(197, '2026031044090', '$2y$10$.tu76tHZdLKlW6ODMRFKfetNBcjydsItq9MjIPobPJzBDZJjVhbcG', '2026031044090@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:41', '2026-10-17 20:17:41'),
(198, '2026031044093', '$2y$10$nOAZwuHFNWRUWzM2ERF92uIPH9c/NLJWSJXrwALrwfzqXNaTOS0p6', '2026031044093@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:41', '2026-10-17 20:17:41'),
(199, '2026031044096', '$2y$10$RdXSNiXENAN7fDD3L43hTukmNLlO/GjlbOtzf3BUsv5Js2dzakAk.', '2026031044096@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:41', '2026-10-17 20:17:41'),
(200, '2026031044099', '$2y$10$MOwTGb.iarorTYY7fRMAG.JgoYZvW2VnUzl0/c767z.vvhgBCVMAS', '2026031044099@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:41', '2026-10-17 20:17:41'),
(201, '2026031044102', '$2y$10$i0/iw2ddnChCuy7zNULSUeb40rw.KmnrhPr6J54AFYCT6LZP3XHO2', '2026031044102@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:41', '2026-10-17 20:17:41'),
(202, '2026031044105', '$2y$10$Yt338P/YHmRjTdnl/rs3XenrLI/tuUZZqjLXY9K2uOfohuhKAyMIG', '2026031044105@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:41', '2026-10-17 20:17:41'),
(203, '2026031044108', '$2y$10$qIBm8IliJHU4VKWq.ADC3u0OY7Pm6s934ktVs7iHJIeZOxtTEnXc.', '2026031044108@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:41', '2026-10-17 20:17:41'),
(204, '2026031044111', '$2y$10$cfk8H9lQIKBQrdp10Ny9ZevWbbp3RBGcPYjCgCTNC33boNIptT0c.', '2026031044111@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:41', '2026-10-17 20:17:41'),
(205, '2026031044114', '$2y$10$yeXRFvPnScDp3FkJPyFzN./b0C2NdiddbFqzdsFcS40/QcrxAXkQq', '2026031044114@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:41', '2026-10-17 20:17:41'),
(206, '2026031044117', '$2y$10$uqr4Hnddr8hcg4GZtXsf3.AX1ol5nzOc.9azU2KwBSRR546Odig8e', '2026031044117@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:41', '2026-10-17 20:17:41'),
(207, '2026031044120', '$2y$10$iOySAWmlXd8C1A3XpjZ0U.dBcWhkiDOH2nfCvEBrK9kUzBqoCIgSe', '2026031044120@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:41', '2026-10-17 20:17:41'),
(208, '2026031044123', '$2y$10$r195p1kM7mya46PGI7Oiqe4sTRY/hRKahA4DN0J6f/ut/qRvW8RfS', '2026031044123@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:41', '2026-10-17 20:17:41'),
(209, '2026031044126', '$2y$10$J8zRfIBW88VPMKiCZBcmyOzw/pvSogaA9mCJNwytEq0mfTlKye/au', '2026031044126@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:42', '2026-10-17 20:17:42'),
(210, '2026031044129', '$2y$10$bwZEu7YLBRz7yPH2YUAKY.EdvcZUmfa88LtRuiTF9PeOFDGDryHd2', '2026031044129@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:42', '2026-10-17 20:17:42'),
(211, '2026031044132', '$2y$10$jbvWE26KQCdn0FtDr67Dxe.MV4Lyyd5KqywmggxGbK0Rso9UCxxOW', '2026031044132@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:42', '2026-10-17 20:17:42'),
(212, '2026031044135', '$2y$10$gPPAs89KMLd7CcVSrhZSmOMVREiZ4vhMd48QEa0XQqW1r3Mb6JevS', '2026031044135@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:42', '2026-10-17 20:17:42'),
(213, '2026031044138', '$2y$10$aoiW4YeVoVZZVA6JVO2wXOxLruDC97rlCt11a3d4xQqRpfi61C2KK', '2026031044138@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:42', '2026-10-17 20:17:42'),
(214, '2026031044141', '$2y$10$Ch6D.Fb3EVa2GgKWrCZekugLuLJD.u5j3iASIVus39Kq5Q3h25Nvy', '2026031044141@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:42', '2026-10-17 20:17:42'),
(215, '2026031044144', '$2y$10$NYa/KVyyqAcelZ9Wn26Hqe69eau5ImS3xvfhQCnpIMMTi/NpYjkCG', '2026031044144@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:42', '2026-10-17 20:17:42'),
(216, '2026031044147', '$2y$10$6rv6MratD0r6El0Z5coJoepegwEu41/UtotN70hkUkRSFNtsxkL4W', '2026031044147@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:42', '2026-10-17 20:17:42'),
(217, '2026031044150', '$2y$10$8DINBvBdhe2NbJwlKlCIoelISYSZ.u72vwSuNBxEPewgiklxjvC6G', '2026031044150@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:42', '2026-10-17 20:17:42'),
(218, '2026031044153', '$2y$10$juaDuRqUDxFHXsmdKpCcWOieEj5C3kLCQ2LgDRJvEO1C55SaOB5Ba', '2026031044153@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:42', '2026-10-17 20:17:42'),
(219, '2026031044156', '$2y$10$Wwtweu2NcB3xfaN58eoPp.jxv5kXuNyy04pGwWz2gpdvW/h5fc0X2', '2026031044156@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:42', '2026-10-17 20:17:42'),
(220, '2026031044159', '$2y$10$fugRVxhmOHKnVm.YDFuh1OSwcLWsFZLxbwZwB63aQfRuAvJtBU/ju', '2026031044159@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:42', '2026-10-17 20:17:42'),
(221, '2026031044162', '$2y$10$3tX9vuVg8J4kWtkYY1ji.eSP9FhJsObjb7iG7fL1kpaKhhL.jLqtO', '2026031044162@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:42', '2026-10-17 20:17:42'),
(222, '2026031044165', '$2y$10$lFNrBKn3wpPQm9MA/zdvEedLXVtojLQEzuUVCDr2VtIoIhBqyXd/6', '2026031044165@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:42', '2026-10-17 20:17:42'),
(223, '2026031044168', '$2y$10$YU6pHPQcaC.nCLdeSzfwm.OZaNZgHU5GtyOlfM1Y3VYu9BRQKnVTy', '2026031044168@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:42', '2026-10-17 20:17:42'),
(224, '2026031044171', '$2y$10$c/RS4moLu1bXriG3xYz8v.84Oc6IoMiKqfZTqiX3u0O.BCl2lvCr6', '2026031044171@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:42', '2026-10-17 20:17:42'),
(225, '2026031044174', '$2y$10$05FDoV0WV4AGM6hN169HfOTIHi6fnE74XeymL8fLCwb40LQMqSzZa', '2026031044174@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:43', '2026-10-17 20:17:43'),
(226, '2026031044177', '$2y$10$Ws09qNkd.gBTBtsqHfNW6eDazV7wEkJfoKDBxOgcBSWNdzjfZl8da', '2026031044177@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:43', '2026-10-17 20:17:43'),
(227, '2026031044180', '$2y$10$6nTmbTNyc.UJGCUviNnntO3h6mZln3IQ54/HoDJejZTPUM54SBbhy', '2026031044180@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:43', '2026-10-17 20:17:43'),
(228, '2026031044183', '$2y$10$V7czwrLmRLHEW0.SsCFCd.hXbivr8S36S5iq0Clfl6nTZpVSRsoBa', '2026031044183@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:43', '2026-10-17 20:17:43'),
(229, '2026031044186', '$2y$10$N.0iHWlR4bza9DE7R0ggiujsIVPUbgJ9/1bbDIcdi1EwckEJZR0Rq', '2026031044186@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:43', '2026-10-17 20:17:43'),
(230, '2026031044189', '$2y$10$2UrWoFfHJ/SHhiqgPyUaoec/GwmoWNamFphCwtJ8IpXuPjNohc8Hi', '2026031044189@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:43', '2026-10-17 20:17:43'),
(231, '2026031044192', '$2y$10$I8Sz4K5mk9wnfQ7tzcYcm.HQqag.H890BtjkzdMG1BBAF3MLT4DfW', '2026031044192@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:43', '2026-10-17 20:17:43'),
(232, '2026031044195', '$2y$10$Ftc7NBsnezQOyKGYV4tkhepMBLThJhtuWLvo5e0QEe6GHEuT5UFgS', '2026031044195@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:43', '2026-10-17 20:17:43'),
(233, '2026031044198', '$2y$10$nBf1rKE0iG01OLwzFMBkjuDl45cWhh0HKDmbZeFowC/Os/2ghabri', '2026031044198@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:43', '2026-10-17 20:17:43'),
(234, '2026031044201', '$2y$10$.3Yv2jMuWhFNpFZE4NsKE.yhcGjrHjms8WRJ8iUyjTnJfEYuuBG46', '2026031044201@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:43', '2026-10-17 20:17:43'),
(235, '2026031044204', '$2y$10$1T3/DjaUuQe6KuLDDfRoQOkLxdB6N1VWxxEept2ZVtmL/4PYEPAbq', '2026031044204@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:43', '2026-10-17 20:17:43'),
(236, '2026031044207', '$2y$10$tXBgQw3MceTdBqAl5V9l3.Qs6EBIDZ9dVwM./l/yJq9aaibn1K6n.', '2026031044207@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:43', '2026-10-17 20:17:43'),
(237, '2026031044210', '$2y$10$cf/6Ry8KkBAqCDf4wl44leQy/0qPAvEUcgVqaXozNCbDacBIqPp7W', '2026031044210@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:43', '2026-10-17 20:17:43'),
(238, '2026032044001', '$2y$10$sXK9bALd/PH09GLenfsd4.nP9DmtSVK1tw6OB2H7CSH9wc6a.h2eK', '2026032044001@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:43', '2026-10-17 20:17:43'),
(239, '2026032044002', '$2y$10$GnwsOJWZEKxYhofYyVpu4eIDmHPMLyMYbteJBzWFzOWTkBgt2Yv9S', '2026032044002@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:43', '2026-10-17 20:17:43'),
(240, '2026032044003', '$2y$10$i4bS.2zOdQ0k700DCgP1X.u/6WARTTKtZAw1CP2O08A43Nknx/qHy', '2026032044003@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:43', '2026-10-17 20:17:43'),
(241, '2026032044004', '$2y$10$bCjCwpNPKQ.9hFM3OPBVQOw2ZWTC7/NkH/pzFGxaFKu1ssXAgb7gK', '2026032044004@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:44', '2026-10-17 20:17:44'),
(242, '2026032044005', '$2y$10$VlgOLh2nz2NACeEEWFx5v.Jt0lKqVs3KBuBPJdSx0.HdBacx7692C', '2026032044005@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:44', '2026-10-17 20:17:44'),
(243, '2026032044006', '$2y$10$O6DbUTTR5yPbtzMK4ulNj.gpDkuncD0AZyQU55rFT2cketufv18ju', '2026032044006@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:44', '2026-10-17 20:17:44'),
(244, '2026032044007', '$2y$10$UY34IcJYx3OZOi0NzNYv9uqYT9w4AmfpUJyv4gQeDtY/zOEKj/RsG', '2026032044007@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:44', '2026-10-17 20:17:44'),
(245, '2026032044008', '$2y$10$k/z0qUFP5M1BY7kaVrK3teLtL/Qvxs7T78Gt5/M7owrh.y5uWySai', '2026032044008@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:44', '2026-10-17 20:17:44'),
(246, '2026032044009', '$2y$10$LBc3WgdUI1FnevwZ2XxF0OllWrYZmnbAFp0eKB0vfn5cdYjlp9ePy', '2026032044009@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:44', '2026-10-17 20:17:44'),
(247, '2026032044010', '$2y$10$LO1oRyfI/6Hw1mHOnoQvnO20FPoTXa/EinOiqCT60n81KBRefVLOq', '2026032044010@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:44', '2026-10-17 20:17:44'),
(248, '2026032044011', '$2y$10$J8ghrzqL7u/j71KQTx6bJ.y41cXtb2alkjMG8jvu2Ao/YtrmzxbsC', '2026032044011@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:44', '2026-10-17 20:17:44'),
(249, '2026032044012', '$2y$10$rv6Fl7YDjArbt2tc.DoZAup.FChTNtOX7wwKUMNdXJUHGRRU1UM6i', '2026032044012@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:44', '2026-10-17 20:17:44'),
(250, '2026032044013', '$2y$10$ctdtrF5WjOxkByeRjtRsSerC5.n8xulIvEPGQ7Li3vZdQKfVn4A1K', '2026032044013@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:44', '2026-10-17 20:17:44'),
(251, '2026032044014', '$2y$10$hKe.W4CSqcUZ1rOcQr2XgOxlF87bRofOf64KCbuTOVKsKzhKUHW1e', '2026032044014@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:44', '2026-10-17 20:17:44'),
(252, '2026032044015', '$2y$10$p1rTCvXaG0BSEd8w6MVeM.OsG3n/AthoC1jyfcPF3MBxhNpFyGIMK', '2026032044015@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:44', '2026-10-17 20:17:44'),
(253, '2026032044016', '$2y$10$09AYvNMcpD3vyhNUMfCh.Ozr.VTEbhCDnj.IS02CvFXJ0HWNxCY7C', '2026032044016@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:44', '2026-10-17 20:17:44'),
(254, '2026032044017', '$2y$10$X9evWIoCoxXMvGTQA1xWbOHVy3OhvEP8YFNUCVnqAaK4PRG0nQI.2', '2026032044017@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:44', '2026-10-17 20:17:44'),
(255, '2026032044018', '$2y$10$qQQVVEivGYYhdr6FrtwYU.K1x6ZsWWFIROsXrCgLB0ntsK13u5M9y', '2026032044018@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:44', '2026-10-17 20:17:44'),
(256, '2026032044019', '$2y$10$CXsmCNWv7rEHSssBds8yxOQU7/KH78QqUabt1X7CYNJIB9fKm/3Xi', '2026032044019@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:44', '2026-10-17 20:17:44'),
(257, '2026032044020', '$2y$10$nMPyh.uNYOaH3fc0W1Z0ruWRxNERoVruuJ0z1TG8tQshz4YiOlAAC', '2026032044020@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:45', '2026-10-17 20:17:45'),
(258, '2026032044021', '$2y$10$53PtbSMV8Oh9DkCPZzDV7uAshFIAcm3d4skP2Hw.IMuoEHqlI.dB.', '2026032044021@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:45', '2026-10-17 20:17:45'),
(259, '2026032044022', '$2y$10$zZgGkjKBYZC3sUJEY0vS5uxto7YdCxc2tEOLS8OGJqbU3bkEHkFMO', '2026032044022@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:45', '2026-10-17 20:17:45'),
(260, '2026032044023', '$2y$10$n7STkAFpoch.cRlpzv7jHeRLyxQj4.yUjkQ/WbtGRwkH1ZCQVASbO', '2026032044023@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:45', '2026-10-17 20:17:45'),
(261, '2026032044024', '$2y$10$5RtATTS11dQHT0kuXpFlWO.zD8kP3PXdR.uJO2AnWt94uJkf0j3u2', '2026032044024@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:45', '2026-10-17 20:17:45'),
(262, '2026032044025', '$2y$10$kqo3Ta2dCkzC2OcUDOfpReEdvTVnD6Ed/oZ6A4bhk/WJKj1KDz1fW', '2026032044025@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:45', '2026-10-17 20:17:45'),
(263, '2026032044026', '$2y$10$SDIdUGe1yXL40FrCneJtjudrKdKYG4ZEnyokmzXSmWU7wrnAtzyl2', '2026032044026@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:45', '2026-10-17 20:17:45'),
(264, '2026032044027', '$2y$10$5wjgJPe4H1kgDRVMC3sh6eMheuIQdmo1J.z4ze9ZYgh1teBn.oZCC', '2026032044027@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:45', '2026-10-17 20:17:45'),
(265, '2026032044028', '$2y$10$i9mq1IddEx.lvRQcdTsOCODDg/e0GqCFxxt39SESDXzwMzPX8HURa', '2026032044028@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:45', '2026-10-17 20:17:45'),
(266, '2026032044029', '$2y$10$O7BHhC.0RHh7QGI78qPu2eFatU9E6ZERA4A.GJnVqH48kfygwi74G', '2026032044029@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:45', '2026-10-17 20:17:45'),
(267, '2026032044030', '$2y$10$rf6Khxq.SzfwDXTL6cO1TeYsstZnNzFIlvIHNRU8FbNNvEzIbZ8Xu', '2026032044030@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:45', '2026-10-17 20:17:45'),
(268, '2026032044031', '$2y$10$x2KMsoW46sUzZpd9.jhDcunzSohcSQXIFrO5BfPIZ6OgYhsANQ9p6', '2026032044031@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:45', '2026-10-17 20:17:45'),
(269, '2026032044032', '$2y$10$w7Dbsblg6unPHdV8OHRPNePmYQ6SYh/63TRwN0JdM1Uxo4UZWSUBC', '2026032044032@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:45', '2026-10-17 20:17:45'),
(270, '2026032044033', '$2y$10$IHALHKvsENSr7Vhn3j8a1.HX23dRBxdT.OQAJ.D7N8gxA736i3ZSO', '2026032044033@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:45', '2026-10-17 20:17:45'),
(271, '2026032044034', '$2y$10$xMKS2zUCkoRSb7ft/cLpm.CYwWap2nz213Rs6HshSNkxrToimxoEa', '2026032044034@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:45', '2026-10-17 20:17:45'),
(272, '2026032044035', '$2y$10$400ddpx3A56wFw.06ySw/.trP41balWTQB/r0wBjDhO.7YsZoWSwu', '2026032044035@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:45', '2026-10-17 20:17:45'),
(273, '2026032044036', '$2y$10$kAoc2m/78TXGa8H/rL/zjuVw0L2dpMo9T2J858QLE0XLHus/KN0Ii', '2026032044036@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:46', '2026-10-17 20:17:46'),
(274, '2026032044037', '$2y$10$SdtfE47xbhB2Pq3UPQ8QgOmOX/T0apyxib1eOA54y1Zr3LKqvKM.q', '2026032044037@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:46', '2026-10-17 20:17:46'),
(275, '2026032044038', '$2y$10$6HdVWXebuDfZzPjKvLmht.DUAoMna3LSSZ.eJfJzqcYN3JN3OGW6u', '2026032044038@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:46', '2026-10-17 20:17:46'),
(276, '2026032044039', '$2y$10$Eje70P9IS1b0picbpDseP.8P6XC9aRTRSm1ufLU9vrcmlbMidlRd2', '2026032044039@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:46', '2026-10-17 20:17:46'),
(277, '2026032044040', '$2y$10$2LnA55M2n7xPaqVJJC7bBONDz6Bn8A4jGtitOhZlYbCb78CpO5JEa', '2026032044040@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:46', '2026-10-17 20:17:46'),
(278, '2026032044041', '$2y$10$Ww5ZYkg1nP9uDE/1SkepTOaeZ4gwkt35gF5uWLRAmrgDodm.txKie', '2026032044041@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:46', '2026-10-17 20:17:46'),
(279, '2026032044042', '$2y$10$.knO6Zag96juY42OqFzHmO0uicc4.Mfe6pb2k82c9stLrSF4pUiWq', '2026032044042@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:46', '2026-10-17 20:17:46'),
(280, '2026032044043', '$2y$10$gII5wUcQSWFxH/sTQVfiy.m13RVXZvbNXpCwwGEq1gL4IQfiUFRqO', '2026032044043@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:46', '2026-10-17 20:17:46'),
(281, '2026032044044', '$2y$10$HGLtRbGl.S0kHRGKF4lnIOSoYLlPiGaNqJ7q4fY20KiY.aDKcS4tW', '2026032044044@student.uncen.ac.id', 'mahasiswa', 1, NULL, '2026-10-17 20:17:46', '2026-10-17 20:17:46');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD KEY `dosen_prodi_id_foreign` (`prodi_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `kelas_kuliah`
--
ALTER TABLE `kelas_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_kuliah_tahun_akademik_id_foreign` (`tahun_akademik_id`),
  ADD KEY `kelas_kuliah_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  ADD KEY `kelas_kuliah_dosen_id_foreign` (`dosen_id`);

--
-- Indeks untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD KEY `mahasiswa_prodi_id_foreign` (`prodi_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_mk` (`kode_mk`),
  ADD KEY `mata_kuliah_prodi_id_foreign` (`prodi_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengaturan_kampus`
--
ALTER TABLE `pengaturan_kampus`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pertemuan`
--
ALTER TABLE `pertemuan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pertemuan_kelas_kuliah_id_foreign` (`kelas_kuliah_id`);

--
-- Indeks untuk tabel `peserta_kelas`
--
ALTER TABLE `peserta_kelas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kelas_kuliah_id_mahasiswa_id` (`kelas_kuliah_id`,`mahasiswa_id`),
  ADD KEY `peserta_kelas_mahasiswa_id_foreign` (`mahasiswa_id`);

--
-- Indeks untuk tabel `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pertemuan_id_mahasiswa_id` (`pertemuan_id`,`mahasiswa_id`),
  ADD KEY `presensi_mahasiswa_id_foreign` (`mahasiswa_id`);

--
-- Indeks untuk tabel `program_studi`
--
ALTER TABLE `program_studi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_prodi` (`kode_prodi`);

--
-- Indeks untuk tabel `tahun_akademik`
--
ALTER TABLE `tahun_akademik`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `role` (`role`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `kelas_kuliah`
--
ALTER TABLE `kelas_kuliah`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=267;

--
-- AUTO_INCREMENT untuk tabel `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `pengaturan_kampus`
--
ALTER TABLE `pengaturan_kampus`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pertemuan`
--
ALTER TABLE `pertemuan`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `peserta_kelas`
--
ALTER TABLE `peserta_kelas`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `program_studi`
--
ALTER TABLE `program_studi`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `tahun_akademik`
--
ALTER TABLE `tahun_akademik`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=282;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `dosen`
--
ALTER TABLE `dosen`
  ADD CONSTRAINT `dosen_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `program_studi` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `dosen_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kelas_kuliah`
--
ALTER TABLE `kelas_kuliah`
  ADD CONSTRAINT `kelas_kuliah_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `kelas_kuliah_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kelas_kuliah_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `program_studi` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `mahasiswa_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD CONSTRAINT `mata_kuliah_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `program_studi` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Ketidakleluasaan untuk tabel `pertemuan`
--
ALTER TABLE `pertemuan`
  ADD CONSTRAINT `pertemuan_kelas_kuliah_id_foreign` FOREIGN KEY (`kelas_kuliah_id`) REFERENCES `kelas_kuliah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `peserta_kelas`
--
ALTER TABLE `peserta_kelas`
  ADD CONSTRAINT `peserta_kelas_kelas_kuliah_id_foreign` FOREIGN KEY (`kelas_kuliah_id`) REFERENCES `kelas_kuliah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `peserta_kelas_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `presensi`
--
ALTER TABLE `presensi`
  ADD CONSTRAINT `presensi_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `presensi_pertemuan_id_foreign` FOREIGN KEY (`pertemuan_id`) REFERENCES `pertemuan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
