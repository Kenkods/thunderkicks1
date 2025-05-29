-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2025 at 09:16 PM
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

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddOrUpdateCartItem` (IN `p_user_id` INT, IN `p_shoe_id` INT, IN `p_quantity` INT, IN `p_price` DECIMAL(10,2), IN `p_size` INT)   BEGIN
    DECLARE v_cart_id INT;
    DECLARE v_existing_quantity INT;
    DECLARE v_size_id INT;

    -- Get or create cart for user
    SELECT cart_id INTO v_cart_id
    FROM cart
    WHERE user_id = p_user_id
    LIMIT 1;

    IF v_cart_id IS NULL THEN
        INSERT INTO cart (user_id, total_amount) VALUES (p_user_id, 0);
        SET v_cart_id = LAST_INSERT_ID();
    END IF;

    -- 
    SELECT size_id INTO v_size_id
    FROM sizes
    WHERE shoe_id = p_shoe_id AND size = p_size
    LIMIT 1;

    -- Check if item with the same size already exists
    SELECT quantity INTO v_existing_quantity
    FROM cart_items
    WHERE cart_id = v_cart_id AND shoe_id = p_shoe_id AND size_id = v_size_id
    LIMIT 1;

    IF v_existing_quantity IS NULL THEN
        SET v_existing_quantity = 0;
    END IF;

    -- Update or insert
    IF v_existing_quantity > 0 THEN
        UPDATE cart_items
        SET quantity = v_existing_quantity + p_quantity,
            price = p_price
        WHERE cart_id = v_cart_id AND shoe_id = p_shoe_id AND size_id = v_size_id;
    ELSE
        INSERT INTO cart_items (cart_id, shoe_id, quantity, price, size_id)
        VALUES (v_cart_id, p_shoe_id, p_quantity, p_price, v_size_id);
    END IF;

    -- Update total_amount in cart
    UPDATE cart c
    JOIN (
        SELECT cart_id, SUM(quantity * price) AS total
        FROM cart_items
        WHERE cart_id = v_cart_id
        GROUP BY cart_id
    ) t ON c.cart_id = t.cart_id
    SET c.total_amount = t.total;

END$$

DELIMITER ;

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
  `total_amount` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `total_amount`, `created_at`) VALUES
(7, 4, 5800.00, '2025-05-29 18:59:52');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_items_id` int(11) NOT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `shoe_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `size_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`cart_items_id`, `cart_id`, `shoe_id`, `quantity`, `price`, `created_at`, `size_id`) VALUES
(51, 7, 2, 1, 2900.00, '2025-05-29 18:59:52', 7),
(52, 7, 2, 1, 2900.00, '2025-05-29 19:01:00', 9);

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
(15, 15, 15, 'FuelCell SuperComp Trainer v3', 10.00, 'null', 7),
(16, 1, 1, 'Rosuie', 150.00, '/thunderkicks1/public/uploads/shoe_6835c08ab139a.png', 1);

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
(45, 10, 12, 9),
(46, 16, 7, 10),
(47, 16, 8, 10),
(48, 16, 9, 10),
(49, 16, 10, 10),
(50, 16, 11, 10),
(51, 16, 12, 10);

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
(8, 'khanksie orga', 'jkqt02@gmail.com', 'khanskie', '$2y$10$aPLw8Opg2kB6.x7XShNiP.jnq8V48Jhc4O4X58YAHkq2l8jPQ.Qzm'),
(9, 'John Kenneth Pantonial', 'jkennn.02@gmail.com', 'jkennn2', '$2y$10$kHBCkQy7WuLUz0X085Nm4enqhQfOLHsN.CsGjioKNWN3VAES53GRC'),
(10, 'John Kenneth Pantonial', 'jkennn.02@gmail.com', 'Kenneth02', '$2y$10$pnW8/1NtsUk2Jy04N1cVSOnfB/4WircVvEQao.4BuD1HZH8W2r5VC'),
(11, 'John Kenneth Pantonial', 'khan@gmail.com', '123kenneth', '$2y$10$pgcWFRO4Siq84F6BwmTSBujiFviBzj0f8HLUoBNDJWr.Kq6gJRy6u');

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
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_items_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `fk_cart_items_size` (`size_id`);

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
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_items_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `shoes`
--
ALTER TABLE `shoes`
  MODIFY `shoe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `size_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `type`
--
ALTER TABLE `type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cart_items_size` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`size_id`) ON UPDATE CASCADE;

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
