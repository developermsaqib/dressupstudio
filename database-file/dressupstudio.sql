-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Aug 21, 2025 at 10:25 AM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dressupstudio`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `first name` text COLLATE utf8mb4_general_ci NOT NULL,
  `last name` text COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` int NOT NULL,
  `ph.num` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart item`
--

CREATE TABLE `cart item` (
  `id` int NOT NULL,
  `customer id` int NOT NULL,
  `add` text COLLATE utf8mb4_general_ci NOT NULL,
  `remove` text COLLATE utf8mb4_general_ci NOT NULL,
  `total amount` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`) VALUES
(11, 'Enchanted Elegance', 'uploads/68a6d3fa7a2d0.jpeg'),
(12, 'Galactic Glamour', 'uploads/68a6d65764462.jpeg'),
(13, 'Timeless Tales', 'uploads/68a6d73f7ff2b.jpeg'),
(14, 'Whimsical Wonders', 'uploads/68a6d7f7ad95e.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int NOT NULL,
  `first_name` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` text COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `ph.num` int NOT NULL,
  `address` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int NOT NULL,
  `firstname` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lastname` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'customer',
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `firstname`, `lastname`, `email`, `password`, `role`, `phone`, `address`) VALUES
(52, 'Muhammad', 'Saqib', 'contact.msaqib@gmail.com', '$2y$10$uS7fUTTZgMiTYKp36FalFOSIBfKatWXZxKMvKvoy9kzNaIXzCfHGm', 'admin', NULL, NULL),
(53, 'Jareer', 'Ahmad', 'contact.msaqib@gmail.com', '$2y$10$Lcbxy3swBIVvbT.Aw150GuxXzbM0oyytPok3cb8KSu2c77x.SG8OG', 'customer', NULL, NULL),
(54, 'hari ', 'hari', 'hari@gmail.com', '$2y$10$84ybPcissf4UXBGLHsjB8eV/ojBYLZ9wnJfXNjIRYgMcHU4QOhgwy', 'customer', NULL, NULL),
(55, NULL, NULL, NULL, '$2y$10$Huhj3HrznSOkZOjqV4asde58x1.NXvSDXj4pFRNYf9S20KWTpW3Fu', NULL, NULL, NULL),
(56, 'kirtan', 'ki', 'kirtan@gmail.com', '$2y$10$QF.fa2uCa8O74gVKFv6ex.jLoY2zVtTlmtTW9N15udC6kPmqGhE3G', 'customer', NULL, NULL),
(57, 'hari ', 'hari', 'hari@gmail.com', '$2y$10$/Tc/j9eHygtu9XzzCO85Nu4s9RiIN0dZjONVPb0jaBt2gTB/1YS/K', 'customer', NULL, NULL),
(58, 'h', 'j', 'hj@gmail.com', '$2y$10$gjUt2lJfycqx5w6fHqTsN.GRH.Iem2QnxC8FnyNp.zVSrvgMyqdRS', 'customer', NULL, NULL),
(59, 'jiya', 'hafeez', 'jia@gmail.com', '$2y$10$QPpy248doaqp5MdCcenlLubG.MIKNDgpr7kb812Mxg5jO1DwU.DsK', 'customer', NULL, NULL),
(60, 'Alka', 'Admin', 'alkadarshan08@gmail.com', 'dressupstudio@1234', 'admin', NULL, NULL),
(61, NULL, NULL, NULL, '$2y$10$7U9akgmvCReDLoDXIjnyheWxHAqYUcNc0LXepT3ZY9WKKnVHIlFS2', NULL, NULL, NULL),
(62, 'Dhaneshwari', 'darshan', 'alkadarshan08@gmail.com', '$2y$10$cX5Nep4VEvLqsnNFa2kEh.59dls8CS3U2Xz01bFuqW3M9h4zK8oaq', 'customer', NULL, NULL),
(63, 'Admin', 'User', 'alkadarshan08@gmail.com', 'dressupstudio@1234', 'admin', NULL, NULL),
(64, 'Admin', 'ALKA', 'kirtngori@gmail.com', '$2y$10$PK6mPoejt5L8tlQW.JPwNOTRrhy4WbkpdN1c.5YipwdXidyQ1aRb6', 'admin', NULL, NULL),
(65, 'jii', 'aa', 'jia@gmail.com', '$2y$10$IQvekqr.k8dICdS9USeHkO79DFSwBWIKIwkVM0z.IPEO3K5ZN9wbO', 'customer', NULL, NULL),
(66, NULL, NULL, NULL, '$2y$10$hCW6FqKqVXVMgd6xBHu68eoh6.xL.xq1X7MmdHyjrrygbt31Jnmdq', NULL, NULL, NULL),
(67, NULL, NULL, NULL, '$2y$10$R7vSaseN3I.qNiqvuZPqIecUySAOVbcrafZ6z202sHihhsnm.V.U2', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `city` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cart_data` text COLLATE utf8mb4_general_ci,
  `status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_name`, `user_email`, `address`, `city`, `phone`, `payment_method`, `cart_data`, `status`, `created_at`) VALUES
(1, 'ayesha', 'ayesha@gmail.com', 'RA Bazar lal kurti', 'Nowshera', '9827943678', 'JazzCash', '[{\"id\":102,\"name\":\"Baby Girl Pink Chicken Fancy Cotton Frock\",\"price\":\"Rs. 2,890\",\"image\":\"coton frock.webp\",\"quantity\":1},{\"id\":2,\"name\":\"Fusion\",\"price\":\"Rs. 3,500\",\"image\":\" perfume 3500.webp\",\"quantity\":2},{\"id\":3,\"name\":\"Bluetooth Headphones\",\"price\":\"Rs 999\",\"image\":\"bluthoth.webp\",\"quantity\":1},{\"id\":1,\"name\":\"Silver Grey Elegant Dress\",\"price\":\"Rs 2499\",\"image\":\"dress.webp\",\"quantity\":1}]', 'Delivered', '2025-07-16 13:31:13'),
(2, 'user', 'default@gmail.com', 'igt8iyfouugvljbh ', 'jookihu', '0987085806', 'COD', '[{\"id\":102,\"name\":\"Baby Girl Pink Chicken Fancy Cotton Frock\",\"price\":\"Rs. 2,890\",\"image\":\"coton frock.webp\",\"quantity\":1}]', 'Pending', '2025-07-28 05:26:31'),
(4, 'ww v', 'al08@gmail.com', 'RA Bazar lal kurti', 'Nowshera', '03369010524', 'COD', '[{\"id\":6,\"name\":\"Paxton Stainless Steel\",\"price\":\" Rs. 10,749\",\"image\":\"paxton stainless steel 10749.webp\",\"quantity\":1}]', 'Pending', '2025-08-20 20:39:43'),
(5, 'Dhaneshwari', 'alkadarshan08@gmail.com', 'badrashi mor mohamhad ali quater nsr', 'Nowshera', '03369010524', 'JazzCash', '[{\"id\":3,\"name\":\"Blck floral dress\",\"price\":\"Rs. 1,592\",\"image\":\"women.webp\",\"quantity\":1}]', 'Pending', '2025-08-21 00:44:07'),
(6, 'jii', 'jia@gmail.com', 'kajdhkhjavbf', 'naksjnxkjas', '02938520944', 'COD', '[{\"id\":5,\"name\":\"Perfume\",\"price\":\"Rs 89.99\",\"image\":\"men3900 perfume.webp\",\"quantity\":3}]', 'Pending', '2025-08-21 00:46:23');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `stock` int NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `category_id` int NOT NULL,
  `subcategory_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int NOT NULL,
  `category_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `category_id`, `name`) VALUES
(4, 11, 'Classic Princess Gowns'),
(5, 11, 'Ethereal Fantasy'),
(6, 11, 'Modern Fairytale');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart item`
--
ALTER TABLE `cart item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `subcategory_id` (`subcategory_id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`);

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
