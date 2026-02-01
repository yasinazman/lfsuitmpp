-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 01, 2026 at 06:51 AM
-- Server version: 8.0.30
-- PHP Version: 8.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lfsuitmpp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `full_name`, `created`, `modified`) VALUES
(8, 'yasin', '$2y$12$5kQYbbwD9Q3F4KD/r7Y74OTdI6np6SFDN6KP./tqA5GwZPHPEp9gO', 'yasin azman', '2026-01-02 18:55:54', '2026-01-02 18:55:54');

-- --------------------------------------------------------

--
-- Table structure for table `found_reports`
--

CREATE TABLE `found_reports` (
  `id` int NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text,
  `found_location` varchar(100) DEFAULT NULL,
  `found_date` date DEFAULT NULL,
  `finder_name` varchar(100) NOT NULL,
  `finder_contact` varchar(20) NOT NULL,
  `finder_matrix_id` varchar(20) DEFAULT NULL,
  `image_file` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `claimer_name` varchar(255) DEFAULT NULL,
  `claimer_matrix_id` varchar(50) DEFAULT NULL,
  `claimer_contact` varchar(50) DEFAULT NULL,
  `claimed_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `found_reports`
--

INSERT INTO `found_reports` (`id`, `item_name`, `category`, `description`, `found_location`, `found_date`, `finder_name`, `finder_contact`, `finder_matrix_id`, `image_file`, `status`, `claimer_name`, `claimer_matrix_id`, `claimer_contact`, `claimed_date`, `created`, `modified`) VALUES
(29, 'The Batu', 'Others', 'Nice hair ', 'Fakulti', '2026-01-15', 'Leo', '0115556666', '20000000', '1769869888_Funny_reaction_pictures___Memes.jpg', 'Pending', NULL, NULL, NULL, NULL, '2026-01-31 22:31:28', '2026-01-31 22:31:28'),
(30, 'Cap', 'Personal', 'Beige & Blue Color', 'Court Volleyball', '2026-01-17', 'Yuji Nishida', '0198547455', '202456984', '1769870061_crtz.jpg', 'Claimed', 'Meri', '2025321025', '0198823654', '2026-01-31 23:17:04', '2026-01-31 22:34:21', '2026-01-31 23:17:04'),
(31, 'Keys', 'Electronics', 'Spiderman Key Chain', 'Makmal Komputer 6', '2026-01-19', 'Hael', '0134568521', '2024894563', '1769870369_Chaveiro_para_moto.jpg', 'Found', NULL, NULL, NULL, NULL, '2026-01-31 22:39:29', '2026-01-31 23:17:56'),
(32, 'Dompet', 'Wallet/Bag', 'Brown', 'Lab', '2026-01-31', 'Khairul aming', '012548796', '2024741253', '1769870955_Brown_Wallet.jpg', 'Claimed', 'Johang', '2024856932', '0114587496', '2026-01-31 23:13:21', '2026-01-31 22:49:15', '2026-01-31 23:13:21'),
(33, 'Bottle', 'Personal', 'Black', 'fakulti', '2026-01-28', 'Zizang', '0136542233', '2023569874', '1769871295_download__1_.jpg', 'Found', NULL, NULL, NULL, NULL, '2026-01-31 22:54:55', '2026-01-31 23:12:01'),
(34, 'hye', 'Others', '.', '.', '2026-01-16', 'bye', '000254875', '0102030506', '1769871440_Baby_holding_laugh_meme.jpg', 'Pending', NULL, NULL, NULL, NULL, '2026-01-31 22:57:20', '2026-01-31 22:57:20'),
(35, 'Kunci Kereta', 'Personal', 'Black', 'Fakulti', '2026-01-29', 'Feroz', '0198574693', '2025748933', '1769872003_1set_Car_Key_Case___Keychain_Compatible_With_Chevrolet.jpg', 'Found', NULL, NULL, NULL, NULL, '2026-01-31 23:06:43', '2026-01-31 23:12:27');

-- --------------------------------------------------------

--
-- Table structure for table `lost_reports`
--

CREATE TABLE `lost_reports` (
  `id` int NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text,
  `last_seen_location` varchar(100) DEFAULT NULL,
  `lost_date` date DEFAULT NULL,
  `reporter_name` varchar(100) NOT NULL,
  `reporter_contact` varchar(20) NOT NULL,
  `reporter_matrix_id` varchar(20) DEFAULT NULL,
  `image_file` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `claimer_name` varchar(255) DEFAULT NULL,
  `claimer_matrix_id` varchar(50) DEFAULT NULL,
  `claimer_contact` varchar(50) DEFAULT NULL,
  `claimed_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lost_reports`
--

INSERT INTO `lost_reports` (`id`, `item_name`, `category`, `description`, `last_seen_location`, `lost_date`, `reporter_name`, `reporter_contact`, `reporter_matrix_id`, `image_file`, `status`, `claimer_name`, `claimer_matrix_id`, `claimer_contact`, `claimed_date`, `created`, `modified`) VALUES
(43, 'Headphone', 'Personal', 'Brand Marshall, White', 'Library', '2026-01-08', 'Alif Ajis', '0125896489', '2023158641', '1769869400_«У тебя будет своя Волна , которую не предай».jpg', 'Lost', NULL, NULL, NULL, NULL, '2026-01-31 22:23:20', '2026-01-31 23:11:40'),
(44, 'Spec', 'Personal', 'Bingkai Hitam', 'Cafeteria', '2026-01-11', 'Zul Aripin', '0136548569', '2025189654', '1769869552_German verbs quiz.jpg', 'Claimed', 'Kamarul', '2024659911', '01133225486', '2026-01-31 23:15:34', '2026-01-31 22:25:52', '2026-01-31 23:15:34'),
(45, 'Wallet', 'Wallet/Bag', 'Pink & White Color', 'Cafeteria', '2026-01-09', 'Nora Daniy', '0114578965', '2024516598', '1769870153_Pinky Wallet.jpg', 'Lost', NULL, NULL, NULL, NULL, '2026-01-31 22:35:53', '2026-01-31 23:20:20'),
(46, 'Bababa', 'Others', 'Cute', 'Bababa', '2026-01-23', 'zzzzz', '0123456789', '0001122233', '1769870484_Masha except black.jpg', 'Pending', NULL, NULL, NULL, NULL, '2026-01-31 22:41:24', '2026-01-31 22:41:24'),
(47, 'Topi', 'Personal', 'Black', 'Library', '2026-01-28', 'Rosyam noh', '0114568523', '2024857496', '1769870642_download (3).jpg', 'Lost', NULL, NULL, NULL, NULL, '2026-01-31 22:44:02', '2026-01-31 23:18:07'),
(48, 'Iphone 17 Pro', 'Electronics', 'Grey', 'Dewan Kuliah', '2026-01-23', 'Siti Nurhaliza', '0198564125', '2023889563', '1769870796_iPhone 17 Pro Silver Hands-on Shot.jpg', 'Lost', NULL, NULL, NULL, NULL, '2026-01-31 22:46:36', '2026-01-31 23:10:36'),
(49, 'Card', 'Personal', 'Mastercard, Black', 'Kandang', '2026-01-27', 'Fattah amer', '0115286341', '202341543', '1769871114_download (4).jpg', 'Claimed', 'Azrul', '2025887746', '0178549632', '2026-01-31 23:19:58', '2026-01-31 22:51:54', '2026-01-31 23:19:58'),
(50, 'Earbud', 'Personal', 'Brown', 'library', '2026-01-23', 'Adam', '0145698745', '2021478952', '1769871586_Skullcandy Sesh True Wireless Earbuds review - The Gadgeteer.jpg', 'Pending', NULL, NULL, NULL, NULL, '2026-01-31 22:59:46', '2026-01-31 22:59:46'),
(51, 'Bottle', 'Personal', 'Black', 'Court Basket', '2026-01-27', 'LeBron', '0117498632', '2023124578', '1769871872_download (2).jpg', 'Lost', NULL, NULL, NULL, NULL, '2026-01-31 23:04:32', '2026-01-31 23:18:11'),
(52, 'Google Pixel 10', 'Electronics', 'Purple', 'Fakulti', '2026-01-21', 'Amiza', '0125478694', '2025487796', '1769872214_Google Pixel 10 🍡.jpg', 'Claimed', 'Yasin', '2025187498', '0126589331', '2026-01-31 23:21:36', '2026-01-31 23:10:14', '2026-01-31 23:21:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `found_reports`
--
ALTER TABLE `found_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lost_reports`
--
ALTER TABLE `lost_reports`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `found_reports`
--
ALTER TABLE `found_reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `lost_reports`
--
ALTER TABLE `lost_reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
