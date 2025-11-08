-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 02, 2025 at 09:04 PM
-- Server version: 10.11.14-MariaDB
-- PHP Version: 8.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sajjadza_sso`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` char(36) NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `url` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `code`, `name`, `icon`, `image`, `url`, `created_at`, `updated_at`) VALUES
('8f31fcf7-c575-4919-b047-b623236b02c7', 'OSN', 'Bismillah', NULL, NULL, 'https://bismillah.sajjadzamzami.com', '2025-03-11 08:21:14', '2025-03-11 08:21:14'),
('915a863e-e783-41ff-b40d-f585c881f60f', 'SSO', 'sso', NULL, NULL, 'https://sso.sajjadzamzami.com', '2025-03-11 08:21:14', '2025-03-11 08:21:14'),
('92544d7b-8703-4970-a0b7-9d4a79c15496', 'ACCOUNTING', 'Accounting', NULL, NULL, 'https://jurnal.sajjadzamzami.com', '2025-03-11 08:21:14', '2025-03-11 08:21:14'),
('eadff18b-60d3-4d49-99cd-0b35e4bc447e', 'ACC', 'account', NULL, NULL, 'https://acc.sajjadzamzami.com', '2025-03-11 08:21:14', '2025-03-11 08:21:14');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `city_id` int(11) NOT NULL,
  `city_name` varchar(255) DEFAULT NULL,
  `prov_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`city_id`, `city_name`, `prov_id`) VALUES
(1, 'PIDIE JAYA', 1),
(2, 'SIMEULUE', 1),
(3, 'BIREUEN', 1),
(4, 'ACEH TIMUR', 1),
(5, 'ACEH UTARA', 1),
(6, 'PIDIE', 1),
(7, 'ACEH BARAT DAYA', 1),
(8, 'GAYO LUES', 1),
(9, 'ACEH SELATAN', 1),
(10, 'ACEH TAMIANG', 1),
(11, 'ACEH BESAR', 1),
(12, 'ACEH TENGGARA', 1),
(13, 'BENER MERIAH', 1),
(14, 'ACEH JAYA', 1),
(15, 'LHOKSEUMAWE', 1),
(16, 'ACEH BARAT', 1),
(17, 'NAGAN RAYA', 1),
(18, 'LANGSA', 1),
(19, 'BANDA ACEH', 1),
(20, 'ACEH SINGKIL', 1),
(21, 'SABANG', 1),
(22, 'ACEH TENGAH', 1),
(23, 'SUBULUSSALAM', 1),
(24, 'NIAS SELATAN', 2),
(25, 'MANDAILING NATAL', 2),
(26, 'DAIRI', 2),
(27, 'LABUHAN BATU UTARA', 2),
(28, 'TAPANULI UTARA', 2),
(29, 'SIMALUNGUN', 2),
(30, 'LANGKAT', 2),
(31, 'SERDANG BEDAGAI', 2),
(32, 'TAPANULI SELATAN', 2),
(33, 'ASAHAN', 2),
(34, 'PADANG LAWAS UTARA', 2),
(35, 'PADANG LAWAS', 2),
(36, 'LABUHAN BATU SELATAN', 2),
(37, 'PADANG SIDEMPUAN', 2),
(38, 'TOBA SAMOSIR', 2),
(39, 'TAPANULI TENGAH', 2),
(40, 'HUMBANG HASUNDUTAN', 2),
(41, 'SIBOLGA', 2),
(42, 'BATU BARA', 2),
(43, 'SAMOSIR', 2),
(44, 'PEMATANG SIANTAR', 2),
(45, 'LABUHAN BATU', 2),
(46, 'DELI SERDANG', 2),
(47, 'GUNUNGSITOLI', 2),
(48, 'NIAS UTARA', 2),
(49, 'NIAS', 2),
(50, 'KARO', 2),
(51, 'NIAS BARAT', 2),
(52, 'MEDAN', 2),
(53, 'PAKPAK BHARAT', 2),
(54, 'TEBING TINGGI', 2),
(55, 'BINJAI', 2),
(56, 'TANJUNG BALAI', 2),
(57, 'DHARMASRAYA', 3),
(58, 'SOLOK SELATAN', 3),
(59, 'SIJUNJUNG (SAWAH LUNTO SIJUNJUNG)', 3),
(60, 'PASAMAN BARAT', 3),
(61, 'SOLOK', 3),
(62, 'PASAMAN', 3),
(63, 'PARIAMAN', 3),
(64, 'TANAH DATAR', 3),
(65, 'PADANG PARIAMAN', 3),
(66, 'PESISIR SELATAN', 3),
(67, 'PADANG', 3),
(68, 'SAWAH LUNTO', 3),
(69, 'LIMA PULUH KOTO / KOTA', 3),
(70, 'AGAM', 3),
(71, 'PAYAKUMBUH', 3),
(72, 'BUKITTINGGI', 3),
(73, 'PADANG PANJANG', 3),
(74, 'KEPULAUAN MENTAWAI', 3),
(75, 'INDRAGIRI HILIR', 4),
(76, 'KUANTAN SINGINGI', 4),
(77, 'PELALAWAN', 4),
(78, 'PEKANBARU', 4),
(79, 'ROKAN HILIR', 4),
(80, 'BENGKALIS', 4),
(81, 'INDRAGIRI HULU', 4),
(82, 'ROKAN HULU', 4),
(83, 'KAMPAR', 4),
(84, 'KEPULAUAN MERANTI', 4),
(85, 'DUMAI', 4),
(86, 'SIAK', 4),
(87, 'TEBO', 5),
(88, 'TANJUNG JABUNG BARAT', 5),
(89, 'MUARO JAMBI', 5),
(90, 'KERINCI', 5),
(91, 'MERANGIN', 5),
(92, 'BUNGO', 5),
(93, 'TANJUNG JABUNG TIMUR', 5),
(94, 'SUNGAIPENUH', 5),
(95, 'BATANG HARI', 5),
(96, 'JAMBI', 5),
(97, 'SAROLANGUN', 5),
(98, 'PALEMBANG', 6),
(99, 'LAHAT', 6),
(100, 'OGAN KOMERING ULU TIMUR', 6),
(101, 'MUSI BANYUASIN', 6),
(102, 'PAGAR ALAM', 6),
(103, 'OGAN KOMERING ULU SELATAN', 6),
(104, 'BANYUASIN', 6),
(105, 'MUSI RAWAS', 6),
(106, 'MUARA ENIM', 6),
(107, 'OGAN KOMERING ULU', 6),
(108, 'OGAN KOMERING ILIR', 6),
(109, 'EMPAT LAWANG', 6),
(110, 'LUBUK LINGGAU', 6),
(111, 'PRABUMULIH', 6),
(112, 'OGAN ILIR', 6),
(113, 'BENGKULU TENGAH', 7),
(114, 'REJANG LEBONG', 7),
(115, 'MUKO MUKO', 7),
(116, 'KAUR', 7),
(117, 'BENGKULU UTARA', 7),
(118, 'LEBONG', 7),
(119, 'KEPAHIANG', 7),
(120, 'BENGKULU SELATAN', 7),
(121, 'SELUMA', 7),
(122, 'BENGKULU', 7),
(123, 'LAMPUNG UTARA', 8),
(124, 'WAY KANAN', 8),
(125, 'LAMPUNG TENGAH', 8),
(126, 'MESUJI', 8),
(127, 'PRINGSEWU', 8),
(128, 'LAMPUNG TIMUR', 8),
(129, 'LAMPUNG SELATAN', 8),
(130, 'TULANG BAWANG', 8),
(131, 'TULANG BAWANG BARAT', 8),
(132, 'TANGGAMUS', 8),
(133, 'LAMPUNG BARAT', 8),
(134, 'PESISIR BARAT', 8),
(135, 'PESAWARAN', 8),
(136, 'BANDAR LAMPUNG', 8),
(137, 'METRO', 8),
(138, 'BELITUNG', 9),
(139, 'BELITUNG TIMUR', 9),
(140, 'BANGKA', 9),
(141, 'BANGKA SELATAN', 9),
(142, 'BANGKA BARAT', 9),
(143, 'PANGKAL PINANG', 9),
(144, 'BANGKA TENGAH', 9),
(145, 'KEPULAUAN ANAMBAS', 10),
(146, 'BINTAN', 10),
(147, 'NATUNA', 10),
(148, 'BATAM', 10),
(149, 'TANJUNG PINANG', 10),
(150, 'KARIMUN', 10),
(151, 'LINGGA', 10),
(152, 'JAKARTA UTARA', 11),
(153, 'JAKARTA BARAT', 11),
(154, 'JAKARTA TIMUR', 11),
(155, 'JAKARTA SELATAN', 11),
(156, 'JAKARTA PUSAT', 11),
(157, 'KEPULAUAN SERIBU', 11),
(158, 'DEPOK', 12),
(159, 'KARAWANG', 12),
(160, 'CIREBON', 12),
(161, 'BANDUNG', 12),
(162, 'SUKABUMI', 12),
(163, 'SUMEDANG', 12),
(164, 'INDRAMAYU', 12),
(165, 'MAJALENGKA', 12),
(166, 'KUNINGAN', 12),
(167, 'TASIKMALAYA', 12),
(168, 'CIAMIS', 12),
(169, 'SUBANG', 12),
(170, 'PURWAKARTA', 12),
(171, 'BOGOR', 12),
(172, 'BEKASI', 12),
(173, 'GARUT', 12),
(174, 'PANGANDARAN', 12),
(175, 'CIANJUR', 12),
(176, 'BANJAR', 12),
(177, 'BANDUNG BARAT', 12),
(178, 'CIMAHI', 12),
(179, 'PURBALINGGA', 13),
(180, 'KEBUMEN', 13),
(181, 'MAGELANG', 13),
(182, 'CILACAP', 13),
(183, 'BATANG', 13),
(184, 'BANJARNEGARA', 13),
(185, 'BLORA', 13),
(186, 'BREBES', 13),
(187, 'BANYUMAS', 13),
(188, 'WONOSOBO', 13),
(189, 'TEGAL', 13),
(190, 'PURWOREJO', 13),
(191, 'PATI', 13),
(192, 'SUKOHARJO', 13),
(193, 'KARANGANYAR', 13),
(194, 'PEKALONGAN', 13),
(195, 'PEMALANG', 13),
(196, 'BOYOLALI', 13),
(197, 'GROBOGAN', 13),
(198, 'SEMARANG', 13),
(199, 'DEMAK', 13),
(200, 'REMBANG', 13),
(201, 'KLATEN', 13),
(202, 'KUDUS', 13),
(203, 'TEMANGGUNG', 13),
(204, 'SRAGEN', 13),
(205, 'JEPARA', 13),
(206, 'WONOGIRI', 13),
(207, 'KENDAL', 13),
(208, 'SURAKARTA (SOLO)', 13),
(209, 'SALATIGA', 13),
(210, 'SLEMAN', 14),
(211, 'BANTUL', 14),
(212, 'YOGYAKARTA', 14),
(213, 'GUNUNG KIDUL', 14),
(214, 'KULON PROGO', 14),
(215, 'GRESIK', 15),
(216, 'KEDIRI', 15),
(217, 'SAMPANG', 15),
(218, 'BANGKALAN', 15),
(219, 'SUMENEP', 15),
(220, 'SITUBONDO', 15),
(221, 'SURABAYA', 15),
(222, 'JEMBER', 15),
(223, 'PAMEKASAN', 15),
(224, 'JOMBANG', 15),
(225, 'PROBOLINGGO', 15),
(226, 'BANYUWANGI', 15),
(227, 'PASURUAN', 15),
(228, 'BOJONEGORO', 15),
(229, 'BONDOWOSO', 15),
(230, 'MAGETAN', 15),
(231, 'LUMAJANG', 15),
(232, 'MALANG', 15),
(233, 'BLITAR', 15),
(234, 'SIDOARJO', 15),
(235, 'LAMONGAN', 15),
(236, 'PACITAN', 15),
(237, 'TULUNGAGUNG', 15),
(238, 'MOJOKERTO', 15),
(239, 'MADIUN', 15),
(240, 'PONOROGO', 15),
(241, 'NGAWI', 15),
(242, 'NGANJUK', 15),
(243, 'TUBAN', 15),
(244, 'TRENGGALEK', 15),
(245, 'BATU', 15),
(246, 'TANGERANG', 16),
(247, 'SERANG', 16),
(248, 'PANDEGLANG', 16),
(249, 'LEBAK', 16),
(250, 'TANGERANG SELATAN', 16),
(251, 'CILEGON', 16),
(252, 'KLUNGKUNG', 17),
(253, 'KARANGASEM', 17),
(254, 'BANGLI', 17),
(255, 'TABANAN', 17),
(256, 'GIANYAR', 17),
(257, 'BADUNG', 17),
(258, 'JEMBRANA', 17),
(259, 'BULELENG', 17),
(260, 'DENPASAR', 17),
(261, 'MATARAM', 18),
(262, 'DOMPU', 18),
(263, 'SUMBAWA BARAT', 18),
(264, 'SUMBAWA', 18),
(265, 'LOMBOK TENGAH', 18),
(266, 'LOMBOK TIMUR', 18),
(267, 'LOMBOK UTARA', 18),
(268, 'LOMBOK BARAT', 18),
(269, 'BIMA', 18),
(270, 'TIMOR TENGAH SELATAN', 19),
(271, 'FLORES TIMUR', 19),
(272, 'ALOR', 19),
(273, 'ENDE', 19),
(274, 'NAGEKEO', 19),
(275, 'KUPANG', 19),
(276, 'SIKKA', 19),
(277, 'NGADA', 19),
(278, 'TIMOR TENGAH UTARA', 19),
(279, 'BELU', 19),
(280, 'LEMBATA', 19),
(281, 'SUMBA BARAT DAYA', 19),
(282, 'SUMBA BARAT', 19),
(283, 'SUMBA TENGAH', 19),
(284, 'SUMBA TIMUR', 19),
(285, 'ROTE NDAO', 19),
(286, 'MANGGARAI TIMUR', 19),
(287, 'MANGGARAI', 19),
(288, 'SABU RAIJUA', 19),
(289, 'MANGGARAI BARAT', 19),
(290, 'LANDAK', 20),
(291, 'KETAPANG', 20),
(292, 'SINTANG', 20),
(293, 'KUBU RAYA', 20),
(294, 'PONTIANAK', 20),
(295, 'KAYONG UTARA', 20),
(296, 'BENGKAYANG', 20),
(297, 'KAPUAS HULU', 20),
(298, 'SAMBAS', 20),
(299, 'SINGKAWANG', 20),
(300, 'SANGGAU', 20),
(301, 'MELAWI', 20),
(302, 'SEKADAU', 20),
(303, 'KOTAWARINGIN TIMUR', 21),
(304, 'SUKAMARA', 21),
(305, 'KOTAWARINGIN BARAT', 21),
(306, 'BARITO TIMUR', 21),
(307, 'KAPUAS', 21),
(308, 'PULANG PISAU', 21),
(309, 'LAMANDAU', 21),
(310, 'SERUYAN', 21),
(311, 'KATINGAN', 21),
(312, 'BARITO SELATAN', 21),
(313, 'MURUNG RAYA', 21),
(314, 'BARITO UTARA', 21),
(315, 'GUNUNG MAS', 21),
(316, 'PALANGKA RAYA', 21),
(317, 'TAPIN', 22),
(318, 'BANJAR', 22),
(319, 'HULU SUNGAI TENGAH', 22),
(320, 'TABALONG', 22),
(321, 'HULU SUNGAI UTARA', 22),
(322, 'BALANGAN', 22),
(323, 'TANAH BUMBU', 22),
(324, 'BANJARMASIN', 22),
(325, 'KOTABARU', 22),
(326, 'TANAH LAUT', 22),
(327, 'HULU SUNGAI SELATAN', 22),
(328, 'BARITO KUALA', 22),
(329, 'BANJARBARU', 22),
(330, 'KUTAI BARAT', 23),
(331, 'SAMARINDA', 23),
(332, 'PASER', 23),
(333, 'KUTAI KARTANEGARA', 23),
(334, 'BERAU', 23),
(335, 'PENAJAM PASER UTARA', 23),
(336, 'BONTANG', 23),
(337, 'KUTAI TIMUR', 23),
(338, 'BALIKPAPAN', 23),
(339, 'MALINAU', 24),
(340, 'NUNUKAN', 24),
(341, 'BULUNGAN (BULONGAN)', 24),
(342, 'TANA TIDUNG', 24),
(343, 'TARAKAN', 24),
(344, 'BOLAANG MONGONDOW (BOLMONG)', 25),
(345, 'BOLAANG MONGONDOW SELATAN', 25),
(346, 'MINAHASA SELATAN', 25),
(347, 'BITUNG', 25),
(348, 'MINAHASA', 25),
(349, 'KEPULAUAN SANGIHE', 25),
(350, 'MINAHASA UTARA', 25),
(351, 'KEPULAUAN TALAUD', 25),
(352, 'KEPULAUAN SIAU TAGULANDANG BIARO (SITARO)', 25),
(353, 'MANADO', 25),
(354, 'BOLAANG MONGONDOW UTARA', 25),
(355, 'BOLAANG MONGONDOW TIMUR', 25),
(356, 'MINAHASA TENGGARA', 25),
(357, 'KOTAMOBAGU', 25),
(358, 'TOMOHON', 25),
(359, 'BANGGAI KEPULAUAN', 26),
(360, 'TOLI-TOLI', 26),
(361, 'PARIGI MOUTONG', 26),
(362, 'BUOL', 26),
(363, 'DONGGALA', 26),
(364, 'POSO', 26),
(365, 'MOROWALI', 26),
(366, 'TOJO UNA-UNA', 26),
(367, 'BANGGAI', 26),
(368, 'SIGI', 26),
(369, 'PALU', 26),
(370, 'MAROS', 27),
(371, 'WAJO', 27),
(372, 'BONE', 27),
(373, 'SOPPENG', 27),
(374, 'SIDENRENG RAPPANG / RAPANG', 27),
(375, 'TAKALAR', 27),
(376, 'BARRU', 27),
(377, 'LUWU TIMUR', 27),
(378, 'SINJAI', 27),
(379, 'PANGKAJENE KEPULAUAN', 27),
(380, 'PINRANG', 27),
(381, 'JENEPONTO', 27),
(382, 'PALOPO', 27),
(383, 'TORAJA UTARA', 27),
(384, 'LUWU', 27),
(385, 'BULUKUMBA', 27),
(386, 'MAKASSAR', 27),
(387, 'SELAYAR (KEPULAUAN SELAYAR)', 27),
(388, 'TANA TORAJA', 27),
(389, 'LUWU UTARA', 27),
(390, 'BANTAENG', 27),
(391, 'GOWA', 27),
(392, 'ENREKANG', 27),
(393, 'PAREPARE', 27),
(394, 'KOLAKA', 28),
(395, 'MUNA', 28),
(396, 'KONAWE SELATAN', 28),
(397, 'KENDARI', 28),
(398, 'KONAWE', 28),
(399, 'KONAWE UTARA', 28),
(400, 'KOLAKA UTARA', 28),
(401, 'BUTON', 28),
(402, 'BOMBANA', 28),
(403, 'WAKATOBI', 28),
(404, 'BAU-BAU', 28),
(405, 'BUTON UTARA', 28),
(406, 'GORONTALO UTARA', 29),
(407, 'BONE BOLANGO', 29),
(408, 'GORONTALO', 29),
(409, 'BOALEMO', 29),
(410, 'POHUWATO', 29),
(411, 'MAJENE', 30),
(412, 'MAMUJU', 30),
(413, 'MAMUJU UTARA', 30),
(414, 'POLEWALI MANDAR', 30),
(415, 'MAMASA', 30),
(416, 'MALUKU TENGGARA BARAT', 31),
(417, 'MALUKU TENGGARA', 31),
(418, 'SERAM BAGIAN BARAT', 31),
(419, 'MALUKU TENGAH', 31),
(420, 'SERAM BAGIAN TIMUR', 31),
(421, 'MALUKU BARAT DAYA', 31),
(422, 'AMBON', 31),
(423, 'BURU', 31),
(424, 'BURU SELATAN', 31),
(425, 'KEPULAUAN ARU', 31),
(426, 'TUAL', 31),
(427, 'HALMAHERA BARAT', 32),
(428, 'TIDORE KEPULAUAN', 32),
(429, 'TERNATE', 32),
(430, 'PULAU MOROTAI', 32),
(431, 'KEPULAUAN SULA', 32),
(432, 'HALMAHERA SELATAN', 32),
(433, 'HALMAHERA TENGAH', 32),
(434, 'HALMAHERA TIMUR', 32),
(435, 'HALMAHERA UTARA', 32),
(436, 'YALIMO', 33),
(437, 'DOGIYAI', 33),
(438, 'ASMAT', 33),
(439, 'JAYAPURA', 33),
(440, 'PANIAI', 33),
(441, 'MAPPI', 33),
(442, 'TOLIKARA', 33),
(443, 'PUNCAK JAYA', 33),
(444, 'PEGUNUNGAN BINTANG', 33),
(445, 'JAYAWIJAYA', 33),
(446, 'LANNY JAYA', 33),
(447, 'NDUGA', 33),
(448, 'BIAK NUMFOR', 33),
(449, 'KEPULAUAN YAPEN (YAPEN WAROPEN)', 33),
(450, 'PUNCAK', 33),
(451, 'INTAN JAYA', 33),
(452, 'WAROPEN', 33),
(453, 'NABIRE', 33),
(454, 'MIMIKA', 33),
(455, 'BOVEN DIGOEL', 33),
(456, 'YAHUKIMO', 33),
(457, 'SARMI', 33),
(458, 'MERAUKE', 33),
(459, 'DEIYAI (DELIYAI)', 33),
(460, 'KEEROM', 33),
(461, 'SUPIORI', 33),
(462, 'MAMBERAMO RAYA', 33),
(463, 'MAMBERAMO TENGAH', 33),
(464, 'RAJA AMPAT', 34),
(465, 'MANOKWARI SELATAN', 34),
(466, 'MANOKWARI', 34),
(467, 'KAIMANA', 34),
(468, 'MAYBRAT', 34),
(469, 'SORONG SELATAN', 34),
(470, 'FAKFAK', 34),
(471, 'PEGUNUNGAN ARFAK', 34),
(472, 'TAMBRAUW', 34),
(473, 'SORONG', 34),
(474, 'TELUK WONDAMA', 34),
(475, 'TELUK BINTUNI', 34),
(476, 'KOTA LUAR NEGERI', 0);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(25, '2024_01_24_010658_reimbursements_table', 1),
(26, '2024_01_24_011108_reimbursement_payments_table', 1),
(32, '2025_02_10_012225_master_accounts', 3),
(33, '2025_02_10_012243_accounts', 3),
(34, '2025_02_10_012425_assets', 3),
(35, '2025_02_10_012445_transaction_periods', 3),
(37, '2025_02_10_012531_transactions', 3),
(40, '2025_02_10_012542_transaction_details', 3),
(41, '2025_02_10_013502_banks', 3),
(42, '2025_02_12_095512_ro_invoice_transactions', 3),
(43, '2025_02_12_112225_po_invoice_transactions', 3),
(44, '2025_02_12_112244_tp_invoice_transactions', 3),
(45, '2025_02_19_152808_po_payment_transactions', 3),
(46, '2025_02_19_153029_ro_payment_transactions', 3),
(47, '2025_02_19_153053_tp_payment_transactions', 3),
(48, '2014_10_12_000000_create_users_table', 4),
(49, '2014_10_12_100000_create_password_resets_table', 4),
(50, '2019_08_19_000000_create_failed_jobs_table', 4),
(51, '2019_12_14_000001_create_personal_access_tokens_table', 4),
(52, '2024_12_23_075457_create_permission_tables', 4),
(53, '2024_12_25_074150_settings_table', 4),
(54, '2025_01_17_150257_applications_table', 4),
(55, '2025_01_19_203701_otp_table', 4),
(1081, '2025_03_14_111711_warehouse_stocks_table', 5),
(1082, '2025_03_14_111712_warehouse_stock_meters_table', 5),
(1086, '2025_03_14_111727_store_stocks_table', 5),
(1087, '2025_03_14_111728_store_stock_meters_table', 5),
(1264, '2024_01_21_111429_product_types_table', 6),
(1265, '2024_01_21_221167_warehouses_table', 6),
(1266, '2024_01_21_221726_stores_table', 6),
(1267, '2024_01_22_215743_units_table', 6),
(1268, '2024_01_22_215804_packs_table', 6),
(1269, '2024_01_22_216045_products_table', 6),
(1270, '2024_01_22_220458_principals_table', 6),
(1271, '2024_01_22_221006_principal_pics_table', 6),
(1272, '2024_01_22_232604_principal_addresses_table', 6),
(1273, '2024_01_22_270715_customers_table', 6),
(1274, '2024_01_22_311745_partners_table', 6),
(1275, '2024_01_22_311756_partner_pics_table', 6),
(1276, '2024_01_22_311812_partner_addresses_table', 6),
(1277, '2024_01_22_394902_stocks_table', 6),
(1278, '2024_01_22_394948_stock_meters_table', 6),
(1279, '2024_01_23_105852_clients_table', 6),
(1280, '2024_01_23_110057_client_pics_table', 6),
(1281, '2024_01_23_112405_client_addresses_table', 6),
(1282, '2024_01_23_112727_logistics_table', 6),
(1283, '2024_01_23_112744_logistic_addresses_table', 6),
(1284, '2024_01_23_213937_request_orders_table', 6),
(1285, '2024_01_23_215543_request_order_products_table', 6),
(1286, '2024_01_23_221047_request_order_files_table', 6),
(1287, '2024_01_23_221156_transports_table', 6),
(1288, '2024_01_23_221163_transport_invoices_table', 6),
(1289, '2024_01_23_221166_transport_payments_table', 6),
(1290, '2024_01_23_221218_purchase_orders_table', 6),
(1291, '2024_01_23_223609_purchase_order_products_table', 6),
(1292, '2024_01_23_223621_purchase_order_files_table', 6),
(1293, '2024_01_23_224018_purchase_receives', 6),
(1294, '2024_01_23_224029_purchase_receive_products_table', 6),
(1295, '2024_01_23_224107_request_order_invoices_table', 6),
(1296, '2024_01_23_224118_request_order_payments_table', 6),
(1297, '2024_01_23_224538_purchase_order_invoices_table', 6),
(1298, '2024_01_23_225520_purchase_order_payments_table', 6),
(1299, '2024_01_23_243917_bals_table', 6),
(1300, '2024_01_23_243927_bal_products_table', 6),
(1301, '2024_01_23_244258_bal_unpacks_table', 6),
(1302, '2024_01_23_244327_bal_unpack_products_table', 6),
(1303, '2025_03_14_111714_stock_movements_table', 6),
(1304, '2025_03_14_111715_stock_movement_products_table', 6),
(1305, '2025_03_14_111716_stock_movement_files_table', 6),
(1306, '2025_06_10_215923_request_process_table', 6),
(1307, '2025_06_10_220053_request_process_products_table', 6),
(1308, '2025_06_10_221938_payments', 6),
(1309, '2025_06_10_222038_customer_orders_table', 6),
(1310, '2025_06_10_222055_customer_order_products_table', 6),
(1311, '2025_06_27_000335_partner_debts_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 6),
(3, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4),
(4, 'App\\Models\\User', 5);

-- --------------------------------------------------------

--
-- Table structure for table `otp`
--

CREATE TABLE `otp` (
  `id` char(36) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `otp` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'bismillah', 'web', '2025-03-11 08:21:13', '2025-03-11 08:21:13'),
(2, 'account', 'web', '2025-03-11 08:21:13', '2025-03-11 08:21:13'),
(3, 'sso', 'web', '2025-03-11 08:21:13', '2025-03-11 08:21:13'),
(4, 'accounting', 'web', '2025-03-11 08:21:13', '2025-03-11 08:21:13');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'developer', 'web', '2025-03-11 08:21:13', '2025-03-11 08:21:13'),
(2, 'admin', 'web', '2025-03-11 08:21:13', '2025-03-11 08:21:13'),
(3, 'accounting', 'web', '2025-03-11 08:21:13', '2025-03-11 08:21:13'),
(4, 'employee', 'web', '2025-03-29 05:00:49', '2025-03-29 05:03:05');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(3, 1),
(3, 2),
(4, 1),
(4, 2),
(4, 3);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` char(36) NOT NULL DEFAULT '1241ccd4-43b8-4eda-a555-a9ee86456a0b',
  `group` varchar(255) NOT NULL,
  `group_key` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `input_type` varchar(255) NOT NULL DEFAULT 'text',
  `value` text DEFAULT NULL,
  `is_urgent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `group`, `group_key`, `name`, `key`, `description`, `input_type`, `value`, `is_urgent`, `created_at`, `updated_at`) VALUES
('0dce868d-08f8-4c65-8904-3636ae2af53d', 'Site', 'site', 'Logo', 'logo', 'Logo untuk website', 'image', 'settings/IY5jfKZYZiMhXBMQXRBVak3mOkwnDonlrt4X5gER.png', 1, '2024-12-25 21:05:21', '2025-03-11 08:42:55'),
('12a3f22f-750c-4e06-8c2e-6cccfb50dcb6', 'Letter', 'letter', 'Background Surat', 'background', 'Kop Surat A4', 'image', 'settings/tmBpbxegwxq9HJ9sH64cfRk52uor9Wn3dasaTaP3.png', 1, '2025-02-03 04:42:34', '2025-03-11 08:43:39'),
('1626205a-3961-4cb4-a1aa-ad3ab5720eeb', 'Site', 'site', 'Favicon', 'favicon', 'Upload gambar dengan rasio 1:1', 'image', 'settings/sy55l32L1xZ4iFX7BWWMbPU7OtUO2bxwrPQVRjUb.png', 1, '2024-12-25 21:05:54', '2025-03-11 08:39:11'),
('177999f1-382b-44ee-9205-2bed9b2bf24f', 'Letter', 'letter', 'Purchasing Name', 'purchasing-name', 'Nama penanggung jawab Purchase order / Pembelian ke Principal', 'text', 'Setiadi', 1, '2025-02-03 12:00:54', '2025-02-03 12:01:32'),
('1c63c36e-64fb-408d-86af-5e4f5d50a1d3', 'Letter', 'letter', 'Director', 'director', 'Nama Direksi yang Digunakan Untuk Surat dan lainnya', 'text', 'Teuku Ria Fahriza', 0, '2025-02-03 04:50:34', '2025-02-03 15:07:28'),
('22f79ff7-fa6d-4b8d-b34c-fa24bc8fbee9', 'Company', 'company', 'Company Contact Person Email', 'cp-email', 'Company Contact Person Email', 'text', 'sajjadzamzami@gmail.com', 1, '2025-02-03 09:44:57', '2025-07-15 04:15:11'),
('238e5d40-f530-46a2-968f-dc2eeaafad54', 'Letter', 'letter', 'Administrasi Name', 'administrasi-name', 'Siapa Nama Administrasi yang akan di letakkan pada PIC Surat', 'text', 'Setiadi', 1, '2025-02-04 03:38:48', '2025-02-04 03:39:09'),
('239f1c78-7d44-42c2-8f10-f678396c5e9d', 'Theme', 'theme', 'Button Border Color', 'btn-border', 'Nilai Default \'#926e38\'', 'text', '#412F73', 1, '2024-12-26 05:17:30', '2025-01-17 13:11:57'),
('27a91b2a-64fa-4cd4-83b1-465e33e3b812', 'Letter', 'letter', 'Bank Transfer - Rekening', 'rekening', 'Bank Transfer - Rekening | Nomor Rekening yang digunakan untuk pembayaran dan akan disertakan pada surat Invoice', 'number', '1270007938085', 1, '2025-02-03 13:51:22', '2025-02-03 13:54:49'),
('3313cff0-3ae7-429a-88b7-2ade63680ddd', 'Company', 'company', 'Company City', 'city', 'Alamat Kota Perusahaan untuk keperluan sistem lebih lanjut', 'text', 'Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta', 1, '2025-01-23 17:15:17', '2025-07-15 04:17:37'),
('40aa2835-2e30-451f-bcb8-6fc4f5a29559', 'Theme', 'theme', 'Background Subtle', 'bg-subtle', 'Nilai default \'#926e380c\'', 'text', '#7B653C0c', 1, '2024-12-26 05:16:10', '2025-03-11 08:46:15'),
('4db73672-ad72-4512-83ff-46f02ec607cf', 'Company', 'company', 'Company Contact Person Phone', 'cp-phone', 'Contact Person yang mewakili perusahaan untuk keperluan sistem lebih lanjut', 'text', '+6285156546132', 1, '2025-01-23 17:06:41', '2025-07-15 04:18:35'),
('4f1af75f-3cd1-44ea-b7f5-e6d6def166cf', 'Company', 'company', 'Company Contact Person Name', 'cp-name', 'Contact Person yang mewakili perusahaan untuk keperluan sistem lebih lanjut', 'text', 'Sajjad Zamzami', 1, '2025-01-23 17:06:03', '2025-07-15 04:16:34'),
('57613a79-d7c5-4b09-a579-ddd7288599db', 'Letter', 'letter', 'Bank Transfer - Rekening Name', 'rekening-name', 'Bank Transfer - Rekening Name | Nama Rekening yang digunakan pada invoice', 'text', 'PT Rizq Sahara Multindo', 1, '2025-02-03 13:52:31', '2025-02-03 13:53:35'),
('6d6de589-6acd-437b-b86a-7f8074ae667d', 'Theme', 'theme', 'Button Hover Background', 'btn-hover-bg', 'Nilai default \'#a47d42\'', 'text', '#FCA846', 1, '2024-12-26 05:18:02', '2025-03-11 08:46:43'),
('7fcfe7da-a8cf-42aa-af87-d0c3114443ad', 'Company', 'company', 'Company Phone', 'phone', 'Telp yang mewakili Perusahaan untuk keperluan sistem lebih lanjut', 'number', '6285156546132', 1, '2025-01-23 17:04:50', '2025-07-15 04:18:46'),
('8e5d7803-d425-4e13-a645-dea51a9362f9', 'Theme', 'theme', 'Button Background', 'btn-bg', 'Nilai Default #926e38', 'text', '#7B653C', 1, '2024-12-26 05:16:58', '2025-03-11 08:44:31'),
('930d264f-66b0-4b7e-b0fa-8cecac392c7d', 'Theme', 'theme', 'Primary RGBA', 'primary-rgba', 'Nilai default \'163, 118, 78\'', 'text', '123, 101, 60', 1, '2024-12-26 05:15:33', '2025-03-11 08:45:36'),
('a4ac5a3e-a71b-4f3a-8364-6bf5e1a46872', 'Company', 'company', 'Company Legal Name', 'legal-name', 'Nama Perusahaan untuk keperluan sistem lebih lanjut', 'text', 'Sajjad Zamzami Carpet', 1, '2025-01-23 17:10:32', '2025-07-15 04:17:04'),
('a888e029-7e36-497a-b27b-27085de77d0f', 'Company', 'company', 'Company Address', 'address', 'Alamat Company untuk keperluan sistem lebih lanjut', 'textarea', 'Jl. K.H. Mas Mansyur No.14, Kb. Kacang, Kecamatan Tanah Abang', 1, '2025-01-23 17:04:07', '2025-07-15 04:17:47'),
('bab1bbf0-31d3-4ceb-b8aa-3c84bd8f2251', 'Letter', 'letter', 'Kop Header', 'kop-header', 'Kop bagian atas pada surat', 'image', 'settings/8On74gsd6GwcW6icKubE9Fc79ZiXFq4rFZNiHxlF.png', 1, '2025-02-28 02:37:14', '2025-03-11 08:43:16'),
('cbe5cd7b-c416-4818-aa54-907d75415104', 'Theme', 'theme', 'Button Hover Border', 'btn-hover-border', 'Nilai default \'#a47d42\'', 'text', '#FCA846', 1, '2024-12-26 05:18:28', '2025-03-11 08:47:03'),
('cf966a94-8ba8-47b6-89d7-f8eac3636e54', 'Site', 'site', 'Dashboard - Greeting', 'dashboard-greeting', 'Title ucapan pada header dashboard', 'text', 'Masukan nilai PPN, contoh 11 untuk PPN 11%', 1, '2024-12-25 21:06:51', '2024-12-25 21:09:55'),
('db00b99e-884b-4c25-860d-eda0cc1d5cfe', 'Letter', 'letter', 'Kop Footer', 'kop-footer', 'Kop bagian bawah surat', 'image', 'settings/tR4SW2DuK7bN5fYaFmYy5QZbANuX5U0lnTvaIMIs.png', 1, '2025-02-28 02:37:52', '2025-03-11 08:43:28'),
('e2786502-1344-46fd-7062-2de265ba15aa', 'Site', 'site', 'PPN %', 'ppn', 'Nilai PPN, contoh 11 untuk PPN 11%', 'number', '11', 1, '2024-12-25 21:07:36', '2024-12-25 21:09:36'),
('eae2fc2d-1231-4390-b458-80a15be81d83', 'Site', 'site', 'Product Limit', 'product-limit', 'Tentukan berapa item yang ditampilkan per halaman saat menampilkan data produk di Permintaan Client atau Pembelian ke Principal', 'number', '6', 1, '2025-02-01 07:57:09', '2025-02-05 04:53:07'),
('ed186bd9-6cad-471e-97c6-6c8660c51375', 'Theme', 'theme', 'Primary Color', 'primary-color', 'Nilai default #926e38', 'text', '#7B653C', 1, '2024-12-26 05:15:02', '2025-03-11 08:44:54'),
('f15ac8c8-1070-46ca-afd3-695cc23eb557', 'Company', 'company', 'Company Postal Code', 'postal-code', 'Kode Pos Perusahaan untuk keperluan sistem lebih lanjut (surat menyurat dan lainnya)', 'text', '10240', 1, '2025-01-23 17:16:05', '2025-07-15 04:17:54'),
('f400a8b6-6e43-4bda-8780-015ebfb58004', 'Letter', 'letter', 'Bank Transfer - Bank Name', 'bank-name', 'Bank Transfer - Bank Name | Nama Bank yang digunakan untuk pembayaran dan akan disertakan pada surat Invoice', 'text', 'Mandiri Cabang Jakarta Aneka Tambang', 1, '2025-02-03 13:50:15', '2025-02-03 13:53:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` text DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` bigint(20) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `image`, `name`, `email`, `phone`, `position`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, NULL, 'AL Developer', 'al@hiamalif.com', NULL, NULL, NULL, '$2y$10$hvvl6hD1IH3CbnHObeg1iOIJwJibGCqQnYn9bZh8OG6VlBQI/gbuW', NULL, '2025-03-11 08:21:13', '2025-03-11 08:21:13'),
(2, NULL, 'Admin User', 'admin@example.com', NULL, NULL, NULL, '$2y$10$JPvSUpwFowYoNTzlEuQes.CFYk.r2tXjlSiYxTweGafefaEPSpvPm', NULL, '2025-03-11 08:21:13', '2025-03-11 08:21:13'),
(3, NULL, 'Accounting User 1', 'accounting1@example.com', NULL, NULL, NULL, '$2y$10$hVZp.DEhEaAVq7H.ge8UPecO5epcY0P5DcTm1fULb1tIyhLTZBUSe', NULL, '2025-03-11 08:21:13', '2025-03-11 08:21:13'),
(4, NULL, 'Accounting User 2', 'accounting2@example.com', NULL, NULL, NULL, '$2y$10$0dxKVhfsWN/LIjWLslDbQ.k25CogmKxRWfEqw/yqk/z55c61Me.T2', NULL, '2025-03-11 08:21:13', '2025-03-11 08:21:13'),
(5, 'users/FqKRWdo7m9bR0QiarXXKnVlmquUzLNpuvkzK2oZI.jpg', 'Karyawan One', 'karyawan@mail.com', 62781273212, NULL, NULL, '$2y$10$Wo2re9fWM7DyWnvpaCNsIOxWaEdZoWPyndASxr.SC92yrynpSvOAe', NULL, '2025-03-29 07:28:55', '2025-07-21 10:33:53'),
(6, 'users/XV6ijenfaAcgpXrMITN8BCgynVHJ09l56l6RsI5d.jpg', 'Mas Arief', 'arief@sajjadzamzami.com', 6285156546132, NULL, NULL, '$2y$10$eM6A4dCvzoSVyaT4iZpVK.TqQgI6DL5ME1c.k495G7CR4OBnz0aFq', NULL, '2025-07-21 10:33:24', '2025-07-21 10:33:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `applications_code_unique` (`code`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`city_id`) USING BTREE;

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `otp`
--
ALTER TABLE `otp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=477;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1312;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
