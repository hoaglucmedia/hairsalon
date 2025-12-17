-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th12 16, 2025 lúc 01:49 PM
-- Phiên bản máy phục vụ: 9.1.0
-- Phiên bản PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `hairsalon`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookings`
--

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(100) DEFAULT NULL,
  `book_date` date DEFAULT NULL,
  `book_time` time DEFAULT NULL,
  `stylist` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `reject_reason` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `bookings`
--

INSERT INTO `bookings` (`id`, `customer_name`, `book_date`, `book_time`, `stylist`, `status`, `created_at`, `reject_reason`, `phone`) VALUES
(15, 'Khách Demo', '2025-12-14', '11:00:00', 'Tuấn Anh', 'rejected', '2025-12-13 02:02:20', 'khach k den', '0986046133'),
(16, 'Khách Demo', '2025-12-14', '11:00:00', 'Tuấn Anh', 'confirmed', '2025-12-13 02:03:27', NULL, '0986046133');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `items` text,
  `total_price` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(20) DEFAULT 'pending',
  `reject_reason` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `username`, `items`, `total_price`, `created_at`, `address`, `status`, `reject_reason`) VALUES
(10, 'Admin', '[{\"id\":\"7\",\"name\":\"Sữa rửa mặt Oxy Perfect Wash\",\"price\":\"129000\",\"image\":\"https:\\/\\/encrypted-tbn2.gstatic.com\\/shopping?q=tbn:ANd9GcSnNCbhV5z1koy6fXbLW2_KSNu8YvLsp2NLy6sU73hPM9VQqZwrFH83PZ5YxwRuXBtADKgQudosmI8UMmYykyD_CxlgHvDGynA5Yb0yGJLXltvX8NJGij6KMU_ZSLMQOMMkybhAGEw&usqp=CAc\",\"quantity\":1}]', 129000, '2025-12-11 13:12:14', '72 Lê Thánh Tôn, Bến Nghé, Quận 1, Thành phố Hồ Chí Minh, Việt Nam', 'rejected', '1');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `price` int NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `description`) VALUES
(5, 'Volcanic Clay', 400000, 'https://classic.vn/wp-content/uploads/2016/10/volcanic-clay-v4-2019-810x800.jpg', NULL),
(6, 'Oxy Deep Shampoo 500ml', 340000, 'https://shop.rohto.com.vn/media/catalog/product/1/_/1_4_.jpg?optimize=medium&fit=bounds&height=&width=&canvas=:', NULL),
(7, 'Sữa rửa mặt Oxy Perfect Wash', 129000, 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcSnNCbhV5z1koy6fXbLW2_KSNu8YvLsp2NLy6sU73hPM9VQqZwrFH83PZ5YxwRuXBtADKgQudosmI8UMmYykyD_CxlgHvDGynA5Yb0yGJLXltvX8NJGij6KMU_ZSLMQOMMkybhAGEw&usqp=CAc', NULL),
(23, 'Mặt nạ đất sét L’Oreal Pure Clay', 250000, 'https://media.hcdn.vn/wysiwyg/HaNguyen/mat-na-dat-set-l-oreal-50g-8.png', NULL),
(24, 'Sữa tắm Oxy Cool Blue', 105000, 'https://cdn.famitaa.net/storage/uploads/noidung/oxy-perfect-cool-wash-rohto-mentholatum-100g-kem-rua-mat_00442.jpg', NULL),
(25, 'Sữa tắm cho nam Romano', 125000, 'https://www.lottemart.vn/media/catalog/product/cache/0x0/8/9/8935212811002-4-1.jpg.webp', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(50) DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `customer_name`, `rating`, `comment`) VALUES
(1, 'Nguyễn Văn A', 5, 'Cắt rất đẹp, thợ nhiệt tình!'),
(2, 'Admin', 5, '123123');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `stylists`
--

DROP TABLE IF EXISTS `stylists`;
CREATE TABLE IF NOT EXISTS `stylists` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `experience` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `stylists`
--

INSERT INTO `stylists` (`id`, `name`, `experience`, `avatar`) VALUES
(4, 'Tuấn Anh', '1 năm', NULL),
(5, 'Hoàng Lực', '1 năm', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` varchar(10) DEFAULT 'user',
  `fullname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `is_verified` tinyint DEFAULT '0',
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `fullname`, `verification_token`, `is_verified`, `phone`) VALUES
(1, 'admin', '123', 'admin@salon.com', 'admin', NULL, NULL, 1, NULL),
(2, 'demo', '123', 'demo@gmail.com', 'user', NULL, NULL, 1, NULL),
(13, 'hoangluc', '123', NULL, 'stylist', 'Hoàng Lực', NULL, 1, '0988888888'),
(12, 'hoangluc123', '123@123aB', NULL, 'user', 'Nguyễn Hoàng Lực', NULL, 1, '0919191919'),
(10, 'tuananh', '123', 'tuananh@salon.com', 'stylist', 'Tuấn Anh', NULL, 1, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
