-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2025 at 05:04 PM
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
-- Database: `thunders`
--

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `brand_id` int(11) NOT NULL,
  `brand_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`brand_id`, `brand_name`) VALUES
(1, 'Adidas'),
(2, 'Adidas'),
(3, 'Adidas'),
(4, 'Anta'),
(5, 'Anta'),
(6, 'Anta'),
(7, 'Nike'),
(8, 'Nike'),
(9, 'Nike'),
(10, 'Nike'),
(11, 'Nike'),
(12, 'Nike'),
(13, 'Nike'),
(14, 'Nike'),
(15, 'New Balance');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `shoe_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `cat_id` int(11) NOT NULL,
  `category_name` varchar(90) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`cat_id`, `category_name`) VALUES
(1, 'Men'),
(2, 'Men'),
(3, 'Men'),
(4, 'Men'),
(5, 'Men'),
(6, 'Men'),
(7, 'Men'),
(8, 'Men'),
(9, 'Men'),
(10, 'Men'),
(11, 'Kids'),
(12, 'Kids'),
(13, 'Kids'),
(14, 'Kids'),
(15, 'Women');

-- --------------------------------------------------------

--
-- Table structure for table `shoes`
--

CREATE TABLE `shoes` (
  `shoe_id` int(11) NOT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `name` varchar(120) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `shoe_img` varchar(255) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shoes`
--

INSERT INTO `shoes` (`shoe_id`, `cat_id`, `brand_id`, `name`, `price`, `shoe_img`, `type_id`) VALUES
(1, 1, 1, 'Subzone Shoes', 3400.00, NULL, 1),
(2, 2, 2, 'Own The Game 3 Shoes', 2900.00, NULL, 2),
(3, 3, 3, 'Subzone Shoes', 3400.00, NULL, 3),
(4, 4, 4, 'Anta Shock Wave 5 Pro \'Focus\'', 6995.00, NULL, 4),
(5, 5, 5, 'Anta KT9', 4341.00, NULL, 5),
(6, 6, 6, 'Anta Kai 2 \'Dallas\'', 6995.00, NULL, 6),
(7, 7, 7, 'Nike G.T Cut Academy EP', 4995.00, NULL, 7),
(8, 8, 8, 'Giannis Immortality 4 EP', 4295.00, NULL, 8),
(9, 9, 9, 'Nike Precision 7 EasyOn', 3695.00, NULL, 9),
(10, 10, 10, 'G.T Hassle Academy EP', 4995.00, NULL, 10),
(11, 11, 11, 'Nike Dunk Low', 3095.00, NULL, NULL),
(12, 12, 12, 'Jordan 4 Retro \'Aluminum\' ', 3995.00, NULL, NULL),
(13, 13, 13, 'Nike Court Borough Low Recraft ', 2395.00, NULL, NULL),
(14, 14, 14, 'Jordan 23/7.2 EasyOn', 3295.00, NULL, NULL),
(15, 15, 15, 'FuelCell SuperComp Trainer v3', 10.00, 'null', 7);

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `size_id` int(11) NOT NULL,
  `shoe_id` int(11) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`size_id`, `shoe_id`, `size`, `stock`) VALUES
(1, 1, 8, 8),
(2, 1, 9, 7),
(3, 1, 10, 7),
(4, 1, 11, 4),
(5, 2, 8, 10),
(6, 2, 9, 8),
(7, 2, 10, 5),
(8, 2, 11, 8),
(9, 2, 12, 9),
(10, 3, 8, 8),
(11, 3, 9, 7),
(12, 3, 10, 7),
(13, 3, 11, 4),
(14, 4, 8, 10),
(15, 4, 9, 8),
(16, 4, 10, 5),
(17, 4, 11, 8),
(18, 4, 12, 9),
(19, 5, 8, 8),
(20, 5, 9, 7),
(21, 5, 10, 7),
(22, 5, 11, 4),
(23, 6, 8, 10),
(24, 6, 9, 8),
(25, 6, 10, 5),
(26, 6, 11, 8),
(27, 6, 12, 9),
(28, 7, 8, 8),
(29, 7, 9, 7),
(30, 7, 10, 7),
(31, 7, 11, 4),
(32, 8, 8, 10),
(33, 8, 9, 8),
(34, 8, 10, 5),
(35, 8, 11, 8),
(36, 8, 12, 9),
(37, 9, 8, 8),
(38, 9, 9, 7),
(39, 9, 10, 7),
(40, 9, 11, 4),
(41, 10, 8, 10),
(42, 10, 9, 8),
(43, 10, 10, 5),
(44, 10, 11, 8),
(45, 10, 12, 9);

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE `type` (
  `type_id` int(11) NOT NULL,
  `shoe_type` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`type_id`, `shoe_type`) VALUES
(1, 'Basketball'),
(2, 'Basketball'),
(3, 'Basketball'),
(4, 'Basketball'),
(5, 'Basketball'),
(6, 'Basketball'),
(7, 'Basketball'),
(8, 'Basketball'),
(9, 'Basketball'),
(10, 'Basketball');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(120) NOT NULL,
  `email` varchar(120) NOT NULL,
  `username` varchar(120) NOT NULL,
  `password` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `username`, `password`) VALUES
(1, 'john', 'jkqt02@gmail.com', 'kenkods', 'ken'),
(2, 'brent opaw', 'brent@gmai.com', 'brenpaw', 'brent123'),
(3, 'brent opaw', 'brent@gmai.com', 'brenpaw', '$2y$10$l75V6HT42ze23Z0cCv3kOelRdeuaLXIe7Go3a7u8AF3pACTJYLnQm'),
(4, 'ken', 'ken@gmail.com', 'kenneth123', '$2y$10$1dWbdtVrd4MvH6h63kd9Q.LlF26XbF6YfmqaS55NbO7ZnDlqXaCiG'),
(5, 'juswa', 'juswa@gmail.com', 'juswa', '$2y$10$f1gM38tgRRSIZb/9Q.bmzOmIf6EF9OBhQ3VVo7aPAp/5P4LkXU6Ua'),
(6, 'kenneth', 'ken@gmail.com', 'kenneth12345', '$2y$10$oJVr80rfXtxBUpdi83BAFuF/snJH8fHnfomv6inUJ0BbcFHutr37O'),
(7, 'kurt', 'kurt@gmail.com', 'khurt', '$2y$10$XgJe65IqwORGWp35k2aJveGsKh0Yl9mcS/tCZXmBYGT4AEQEFbC4m'),
(8, 'khanksie orga', 'jkqt02@gmail.com', 'khanskie', '$2y$10$aPLw8Opg2kB6.x7XShNiP.jnq8V48Jhc4O4X58YAHkq2l8jPQ.Qzm');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `shoe_id` (`shoe_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `shoes`
--
ALTER TABLE `shoes`
  ADD PRIMARY KEY (`shoe_id`),
  ADD KEY `cat_id` (`cat_id`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `fk_type` (`type_id`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`size_id`),
  ADD KEY `shoe_id` (`shoe_id`);

--
-- Indexes for table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `shoes`
--
ALTER TABLE `shoes`
  MODIFY `shoe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `size_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `type`
--
ALTER TABLE `type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`shoe_id`) REFERENCES `shoes` (`shoe_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shoes`
--
ALTER TABLE `shoes`
  ADD CONSTRAINT `fk_type` FOREIGN KEY (`type_id`) REFERENCES `type` (`type_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shoes_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `category` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shoes_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`brand_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sizes`
--
ALTER TABLE `sizes`
  ADD CONSTRAINT `sizes_ibfk_1` FOREIGN KEY (`shoe_id`) REFERENCES `shoes` (`shoe_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
