-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Nov 29, 2025 at 05:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `QuanLyThuVien`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `detail` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`id`, `user_id`, `action`, `detail`, `created_at`) VALUES
(1, 5, 'delete_book - Deleted book id=6', NULL, '2025-11-26 13:18:12'),
(2, 5, 'add_book - Added book: reg (code=rg, id=7)', NULL, '2025-11-26 13:18:25'),
(3, 5, 'delete_book - Deleted book id=7', NULL, '2025-11-26 13:18:35'),
(4, 5, 'add_reader - Added reader: vu dinh (id=1)', NULL, '2025-11-26 17:14:24'),
(5, 5, 'borrow_book - Book id=2 borrowed by reader_id=1', NULL, '2025-11-26 17:14:43'),
(6, 5, 'delete_book - Deleted book id=2', NULL, '2025-11-26 17:21:46'),
(7, 5, 'add_book - Added book: Dế Mèn phiêu lưu ký (Tô Hoài) (code=DM1, id=8)', NULL, '2025-11-26 17:22:50'),
(8, 5, 'borrow_book - Book id=8 borrowed by reader_id=1', NULL, '2025-11-26 17:23:24'),
(9, 5, 'add_book - Added book: Lược Sử Loài Người Bằng Tranh - Tập 1: Khởi Đầu Của Loài Người [AlphaBooks] (', NULL, '2025-11-26 17:33:49'),
(10, 5, 'delete_book - Deleted book id=9', NULL, '2025-11-26 17:34:24'),
(11, 5, 'delete_book - Deleted book id=9', NULL, '2025-11-26 17:41:00'),
(12, 5, 'delete_book - Deleted book id=8', NULL, '2025-11-26 17:41:43'),
(13, 5, 'borrow_book - Book id=5 borrowed by reader_id=1', NULL, '2025-11-26 17:42:11'),
(14, 5, 'logout - User logged out', NULL, '2025-11-26 17:44:12'),
(15, 5, 'login - User logged in: thuthu', NULL, '2025-11-26 17:44:45'),
(16, 5, 'delete_book - Deleted book id=4', NULL, '2025-11-27 01:02:33'),
(17, 5, 'add_book - Added book: Dế Mèn Phiêu Lưu Ký (tái bản 2023) (code=DM1, id=10)', NULL, '2025-11-27 01:07:19'),
(18, 5, 'delete_reader - Delete reader id=1', NULL, '2025-11-27 01:22:09'),
(19, 5, 'add_book - Added book: Đất Rừng Phương Nam (code=DRPN, id=11)', NULL, '2025-11-27 01:37:42'),
(20, 5, 'edit_book - Edit book id=10 title=Dế Mèn Phiêu Lưu Ký (tái bản 2023)', NULL, '2025-11-27 01:38:50'),
(21, 5, 'edit_book - Edit book id=10 title=Dế Mèn Phiêu Lưu Ký (tái bản 2023)', NULL, '2025-11-27 01:38:57'),
(22, 5, 'delete_book - Deleted book id=10', NULL, '2025-11-27 01:39:05'),
(23, 5, 'delete_book - Deleted book id=5', NULL, '2025-11-27 01:39:39'),
(24, 5, 'delete_book - Deleted book id=11', NULL, '2025-11-27 01:49:36'),
(25, 5, 'add_book - Added book: Đất Rừng Phương Nam (code=DRPN, id=12)', NULL, '2025-11-27 01:50:13'),
(26, 5, 'add_book - Added book: Dế Mèn Phiêu Lưu Ký (tái bản 2023) (code=DM1, id=13)', NULL, '2025-11-27 01:54:35'),
(27, 5, 'add_book - Added book: Công nghệ 10- Thiết kế và công nghệ Cánh diều (code=CN, id=14)', NULL, '2025-11-27 01:56:25'),
(28, 5, 'delete_book - Deleted book id=12', NULL, '2025-11-27 01:57:03'),
(29, 5, 'add_book - Added book: Đất Rừng Phương Nam (code=DRPN, id=15)', NULL, '2025-11-27 01:59:43'),
(30, 5, 'add_reader - Added reader: vu dinh (id=2)', NULL, '2025-11-27 02:00:00'),
(31, 5, 'delete_book - Deleted book id=14', NULL, '2025-11-27 02:04:03'),
(32, 5, 'delete_book - Deleted book id=15', NULL, '2025-11-27 02:11:58'),
(33, 5, 'add_book - Added book: Sách - Đất Rừng Phương Nam  4.8  5 đánh giá (code=DRPN, id=16)', NULL, '2025-11-27 02:12:55'),
(34, 5, 'add_book - Added book: Sách - Dế Mèn Phiêu Lưu Ký (tái bản 2023) (code=DM1, id=17)', NULL, '2025-11-27 02:13:30'),
(35, 5, 'logout - User logged out', NULL, '2025-11-27 03:04:07'),
(36, 5, 'login - User logged in: thuthu', NULL, '2025-11-27 03:14:09'),
(37, 5, 'logout - User logged out', NULL, '2025-11-27 03:24:28'),
(38, 5, 'login - User logged in: thuthu', NULL, '2025-11-27 03:34:48'),
(39, 5, 'logout - User logged out', NULL, '2025-11-27 03:35:07'),
(40, 5, 'login - User logged in: thuthu', NULL, '2025-11-27 03:36:01'),
(41, 5, 'logout - User logged out', NULL, '2025-11-27 03:37:35'),
(42, NULL, 'login - User logged in: capthe1', NULL, '2025-11-27 04:02:15'),
(43, NULL, 'logout - User logged out', NULL, '2025-11-27 04:25:31'),
(44, 5, 'login - User logged in: thuthu', NULL, '2025-11-27 04:25:48'),
(45, 5, 'logout - User logged out', NULL, '2025-11-27 04:25:53'),
(46, NULL, 'login - User logged in: capthe1', NULL, '2025-11-27 04:26:00'),
(47, NULL, 'logout - User logged out', NULL, '2025-11-27 04:26:26'),
(48, NULL, 'login - User logged in: capthe1', NULL, '2025-11-27 04:26:43'),
(49, NULL, 'logout - User logged out', NULL, '2025-11-27 04:27:00'),
(50, NULL, 'login - User logged in: capthe1', NULL, '2025-11-27 04:28:35'),
(51, NULL, 'logout - User logged out', NULL, '2025-11-27 04:29:22'),
(52, NULL, 'login - User logged in: capthe1', NULL, '2025-11-27 04:31:05'),
(53, NULL, 'logout - User logged out', NULL, '2025-11-27 04:31:57'),
(54, 5, 'login - User logged in: thuthu', NULL, '2025-11-27 04:32:02'),
(55, 5, 'logout - User logged out', NULL, '2025-11-27 04:32:14'),
(56, NULL, 'login - User logged in: capthe1', NULL, '2025-11-27 04:33:19'),
(61, 5, 'login - User logged in: thuthu', NULL, '2025-11-27 04:59:15'),
(62, 5, 'logout - User logged out', NULL, '2025-11-27 04:59:29'),
(63, 8, 'login - User logged in: capthe1', NULL, '2025-11-27 05:05:55'),
(64, 8, 'logout - User logged out', NULL, '2025-11-27 05:12:11'),
(65, 5, 'login - User logged in: thuthu', NULL, '2025-11-27 05:12:14'),
(66, 5, 'logout - User logged out', NULL, '2025-11-27 05:12:22'),
(67, 8, 'login - User logged in: capthe1', NULL, '2025-11-27 05:12:29'),
(68, 8, 'logout - User logged out', NULL, '2025-11-27 05:17:32'),
(69, 5, 'login - User logged in: thuthu', NULL, '2025-11-27 05:17:35'),
(70, 5, 'logout - User logged out', NULL, '2025-11-27 05:19:14'),
(71, 8, 'login - User logged in: capthe1', NULL, '2025-11-27 05:19:22'),
(72, 8, 'logout - User logged out', NULL, '2025-11-27 05:39:26'),
(73, 5, 'login - User logged in: thuthu', NULL, '2025-11-27 05:41:35'),
(74, 5, 'logout - User logged out', NULL, '2025-11-27 05:41:49'),
(75, 8, 'login - User logged in: capthe1', NULL, '2025-11-27 05:47:09'),
(76, 8, 'logout - User logged out', NULL, '2025-11-27 05:47:25'),
(77, 5, 'login - User logged in: thuthu', NULL, '2025-11-27 12:12:12'),
(78, 5, 'logout - User logged out', NULL, '2025-11-27 12:12:30'),
(79, 8, 'login - User logged in: capthe1', NULL, '2025-11-27 12:29:33'),
(80, 8, 'logout - User logged out', NULL, '2025-11-27 12:29:42'),
(81, 5, 'login - User logged in: thuthu', NULL, '2025-11-29 12:03:53'),
(82, 5, 'logout - User logged out', NULL, '2025-11-29 12:09:11'),
(83, 8, 'login - User logged in: capthe1', NULL, '2025-11-29 12:09:41'),
(84, 8, 'logout - User logged out', NULL, '2025-11-29 12:10:08'),
(85, 5, 'login - User logged in: thuthu', NULL, '2025-11-29 12:10:11'),
(86, 5, 'logout - User logged out', NULL, '2025-11-29 12:37:48'),
(87, 5, 'login - User logged in: thuthu', NULL, '2025-11-29 12:43:52'),
(88, 5, 'add_borrow - Borrow book_id=17 reader_id=2', NULL, '2025-11-29 12:47:01'),
(89, 5, 'return_book - Return borrow_id=4, book_id=17, reader_id=2', NULL, '2025-11-29 13:00:51'),
(90, 5, 'add_borrow - Borrow book_id=17 reader_id=2', NULL, '2025-11-29 13:07:40'),
(91, 5, 'logout - User logged out', NULL, '2025-11-29 13:33:08'),
(92, 8, 'login - User logged in: capthe1', NULL, '2025-11-29 13:33:15'),
(93, 8, 'logout - User logged out', NULL, '2025-11-29 13:33:33'),
(94, 5, 'login - User logged in: thuthu', NULL, '2025-11-29 13:33:43'),
(95, 5, 'logout - User logged out', NULL, '2025-11-29 13:58:18'),
(96, 8, 'login - User logged in: capthe1', NULL, '2025-11-29 13:58:25'),
(97, 8, 'logout - User logged out', NULL, '2025-11-29 13:58:41'),
(98, 8, 'login - User logged in: capthe1', NULL, '2025-11-29 13:59:03'),
(99, 8, 'logout - User logged out', NULL, '2025-11-29 13:59:11'),
(100, 5, 'login - User logged in: thuthu', NULL, '2025-11-29 14:01:24'),
(101, 5, 'logout - User logged out', NULL, '2025-11-29 14:04:28'),
(102, 8, 'login - User logged in: capthe1', NULL, '2025-11-29 14:04:34'),
(103, 8, 'logout - User logged out', NULL, '2025-11-29 14:06:07'),
(104, 5, 'login - User logged in: thuthu', NULL, '2025-11-29 15:41:47'),
(105, 5, 'logout - User logged out', NULL, '2025-11-29 16:08:20');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `publish_year` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 0,
  `location` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cover` varchar(255) DEFAULT NULL,
  `isbn` varchar(50) DEFAULT NULL,
  `total` int(11) NOT NULL DEFAULT 0,
  `available` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `code`, `title`, `author`, `description`, `publisher`, `publish_year`, `category_id`, `quantity`, `location`, `created_at`, `cover`, `isbn`, `total`, `available`) VALUES
(16, 'DRPN', 'Sách - Đất Rừng Phương Nam  4.8  5 đánh giá', 'Nhà văn Đoàn Giỏi (1925 - 1989)', NULL, NULL, NULL, 2, 0, NULL, '2025-11-27 02:12:55', 'book_6927b3a77350b.jpg', NULL, 16, 16),
(17, 'DM1', 'Sách - Dế Mèn Phiêu Lưu Ký (tái bản 2023)', 'Tô Hoài', NULL, NULL, NULL, 2, 0, NULL, '2025-11-27 02:13:30', 'book_6927b3ca25780.jpg', NULL, 18, 17);

-- --------------------------------------------------------

--
-- Table structure for table `borrow`
--

CREATE TABLE `borrow` (
  `id` int(11) NOT NULL,
  `reader_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) NOT NULL,
  `borrow_date` date NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `status` enum('dang_muon','da_tra','qua_han') DEFAULT 'dang_muon',
  `fine` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrow`
--

INSERT INTO `borrow` (`id`, `reader_id`, `user_id`, `book_id`, `borrow_date`, `due_date`, `return_date`, `status`, `fine`, `created_at`) VALUES
(4, 2, 5, 17, '2025-11-29', '2025-11-29', '2025-11-29', 'da_tra', 0.00, '2025-11-29 12:47:01'),
(5, 2, 5, 17, '2025-11-29', '2025-12-29', NULL, 'dang_muon', 0.00, '2025-11-29 13:07:40');

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `id` int(11) NOT NULL,
  `reader_id` int(11) NOT NULL,
  `card_code` varchar(50) NOT NULL,
  `issue_date` date DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `status` enum('active','locked') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `code`, `name`) VALUES
(1, 'T001', 'Khoa học'),
(2, 'T002', 'Văn học'),
(3, 'T003', 'Công nghệ');

-- --------------------------------------------------------

--
-- Table structure for table `readers`
--

CREATE TABLE `readers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `fullname` varchar(200) NOT NULL,
  `dob` date DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `readers`
--

INSERT INTO `readers` (`id`, `name`, `code`, `fullname`, `dob`, `phone`, `email`, `address`, `created_at`) VALUES
(2, '', NULL, 'vu dinh', NULL, '0337804594', 'dinhvanvu1562005@gmail.com', NULL, '2025-11-27 02:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `role` enum('admin','thuthu','capthe') DEFAULT NULL,
  `status` enum('active','locked') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `phone`, `password`, `fullname`, `role`, `status`, `created_at`) VALUES
(1, '', NULL, NULL, '$2y$10$7E3iZEKjUvlEjM0GpYug0e7aRzAsyybG9l6NQOPGzUBXKDl8hxW3y', 'Quản trị viên', 'admin', 'active', '2025-11-10 12:19:00'),
(4, 'admin', NULL, NULL, '$2y$10$JwuheJdP8O4anp.KyTagdOd2Vy5G73.B9CbZ2yiU9FsD87Fe2mFqG', 'Admin', 'admin', 'active', '2025-11-11 06:39:56'),
(5, 'thuthu', NULL, '0337804597', '$2y$10$wWPB8Llz9Lb0QtNbd889ZuosqgwLRTUWygW1WMFrYFfIrPFK36IEG', 'Thủ thư', 'thuthu', 'active', '2025-11-26 09:49:24'),
(8, 'capthe1', NULL, NULL, '$2y$10$Y6z1QHdTvFsIdRa6EabSqe4H/thKC/lqqhWtA20vX1f2pnXlv5mhq', 'Nhân viên cấp thẻ', 'capthe', 'active', '2025-11-27 05:05:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `borrow`
--
ALTER TABLE `borrow`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reader_id` (`reader_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `fk_borrow_user` (`user_id`);

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cards_reader` (`reader_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `readers`
--
ALTER TABLE `readers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `borrow`
--
ALTER TABLE `borrow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `readers`
--
ALTER TABLE `readers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD CONSTRAINT `audit_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `borrow`
--
ALTER TABLE `borrow`
  ADD CONSTRAINT `borrow_ibfk_1` FOREIGN KEY (`reader_id`) REFERENCES `readers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrow_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_borrow_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `fk_cards_reader` FOREIGN KEY (`reader_id`) REFERENCES `readers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
