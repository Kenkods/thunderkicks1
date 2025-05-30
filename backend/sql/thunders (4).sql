-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2025 at 08:16 AM
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `TransferCartToOrder` (IN `userId` INT, IN `selectedIds` TEXT)   BEGIN
    DECLARE cartId INT;
    DECLARE totalAmount DECIMAL(10,2);
    DECLARE orderId INT;

    -- Get cart_id from one of the selected items
    SELECT cart_id INTO cartId
    FROM cart_items
    WHERE FIND_IN_SET(cart_items_id, selectedIds) > 0
    LIMIT 1;

    -- Calculate total amount
    SELECT SUM(price * quantity) INTO totalAmount
    FROM cart_items
    WHERE FIND_IN_SET(cart_items_id, selectedIds) > 0;

    -- Insert into orders
    INSERT INTO orders (user_id, total_amount, status, created_at, updated_at)
    VALUES (userId, totalAmount, 'Pending', NOW(), NOW());

    SET orderId = LAST_INSERT_ID();

    -- Insert into order_items
    INSERT INTO order_items (order_id, shoe_id, size_id, quantity, price, created_at)
    SELECT orderId, shoe_id, size_id, quantity, price, NOW()
    FROM cart_items
    WHERE FIND_IN_SET(cart_items_id, selectedIds) > 0;

    -- Delete selected cart_items
    DELETE FROM cart_items
    WHERE FIND_IN_SET(cart_items_id, selectedIds) > 0;
    
    

    -- Delete cart if no more items remain
    IF NOT EXISTS (
        SELECT 1 FROM cart_items WHERE cart_id = cartId
    ) THEN
        DELETE FROM cart WHERE cart_id = cartId;
        
        
       UPDATE cart c
JOIN (
    SELECT cart_id, SUM(price * quantity) AS computed_total
    FROM cart_items
    GROUP BY cart_id
) ci ON c.cart_id = ci.cart_id
SET c.total_amount = ci.computed_total;
        
    END IF;

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
(2, 'Anta'),
(3, 'Nike'),
(4, 'New Balance');

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
(2, 'Women'),
(3, 'Kids');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'PENDING',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `status`, `created_at`, `updated_at`) VALUES
(4, 4, 6300.00, 'Pending', '2025-05-29 23:24:16', '2025-05-29 23:24:16'),
(5, 4, NULL, 'Pending', '2025-05-29 23:25:35', '2025-05-29 23:25:35'),
(6, 4, 13905.00, 'Pending', '2025-05-30 04:40:35', '2025-05-30 04:40:35'),
(7, 4, 12692.00, 'Pending', '2025-05-30 05:37:20', '2025-05-30 05:37:20');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_items_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `shoe_id` int(11) DEFAULT NULL,
  `size_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_items_id`, `order_id`, `shoe_id`, `size_id`, `quantity`, `price`, `created_at`) VALUES
(6, 4, 3, 11, 1, 3400.00, '2025-05-29 23:24:16'),
(7, 4, 2, 9, 1, 2900.00, '2025-05-29 23:24:16'),
(9, 6, 19, 67, 1, 13905.00, '2025-05-30 04:40:35'),
(10, 7, 17, 53, 1, 9500.00, '2025-05-30 05:37:20'),
(11, 7, 22, 82, 1, 3192.00, '2025-05-30 05:37:20');

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
(17, 1, 3, 'Zion 4 PF \'Iridescence', 9500.00, '/thunderkicks1/public/uploads/shoe_6839341f1b729.png', 1),
(18, 1, 3, 'Nike Go FlyEase', 7095.00, '/thunderkicks1/public/uploads/shoe_683936076cafb.png', 1),
(19, 1, 3, 'Nike Vaporfly 4', 13905.00, '/thunderkicks1/public/uploads/shoe_6839369d2fe66.png', 2),
(20, 2, 3, 'Nike Zoom Vomero 5', 8999.00, '/thunderkicks1/public/uploads/shoe_683937479b402.png', 2),
(21, 2, 3, 'Nike Cortez Textile', 5043.00, '/thunderkicks1/public/uploads/shoe_683937d84739c.png', 3),
(22, 3, 3, 'Air Jordan 1 Low', 3192.00, '/thunderkicks1/public/uploads/shoe_6839385388600.png', 3),
(23, 1, 4, 'Men\'s ANTA KAI 1 \"Sacred Bond\"', 10000.00, '/thunderkicks1/public/uploads/shoe_683938ac1af38.png', 1),
(24, 1, 4, 'ANTA Shock Wave 5 Pro \"Sun\"', 75000.00, '/thunderkicks1/public/uploads/shoe_68393907e4d6a.png', 1),
(25, 1, 4, 'Men\'s ANTA KAI 1 \"Yin\"', 69000.00, '/thunderkicks1/public/uploads/shoe_68393973d9a41.png', 1),
(26, 1, 1, 'Anthony Edwards 1 Low Trainers', 7999.00, '/thunderkicks1/public/uploads/shoe_68393abb9365a.png', 1),
(27, 1, 1, 'Harden Volume 9 Shoes', 5466.00, '/thunderkicks1/public/uploads/shoe_68393b4f771b4.png', 1),
(28, 1, 4, '1906R', 13000.00, '/thunderkicks1/public/uploads/shoe_68393c2617e77.png', 3),
(29, 1, 1, 'Adizero Evo SL', 8000.00, '/thunderkicks1/public/uploads/shoe_683946f4b9ec7.png', 2),
(30, 2, 1, 'Duramo Speed 2', 3375.00, '/thunderkicks1/public/uploads/shoe_6839474facb40.png', 2),
(31, 2, 1, 'Response Super', 4000.00, '/thunderkicks1/public/uploads/shoe_683948dac97ad.png', 2),
(32, 2, 3, 'Jordan Heir Series PF \'Mother\'s Day\'', 7000.00, '/thunderkicks1/public/uploads/shoe_6839496c15daf.png', 1),
(33, 2, 3, 'Sabrina 1 \'Dedication\' EP', 7555.00, '/thunderkicks1/public/uploads/shoe_683949ae33493.png', 1);

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
(52, 17, 7, 0),
(53, 17, 8, 7),
(54, 17, 9, 11),
(55, 17, 10, 8),
(56, 17, 11, 7),
(57, 17, 12, 15),
(58, 18, 7, 10),
(59, 18, 8, 10),
(60, 18, 9, 10),
(61, 18, 10, 10),
(62, 18, 11, 10),
(63, 18, 12, 10),
(64, 19, 7, 4),
(65, 19, 8, 9),
(66, 19, 9, 9),
(67, 19, 10, 11),
(68, 19, 11, 7),
(69, 19, 12, 6),
(70, 20, 7, 0),
(71, 20, 8, 8),
(72, 20, 9, 12),
(73, 20, 10, 7),
(74, 20, 11, 7),
(75, 20, 12, 5),
(76, 21, 7, 0),
(77, 21, 8, 11),
(78, 21, 9, 8),
(79, 21, 10, 3),
(80, 21, 11, 3),
(81, 21, 12, 3),
(82, 22, 7, 12),
(83, 22, 8, 12),
(84, 22, 9, 12),
(85, 22, 10, 5),
(86, 22, 11, 4),
(87, 22, 12, 3),
(88, 23, 7, 0),
(89, 23, 8, 8),
(90, 23, 9, 8),
(91, 23, 10, 11),
(92, 23, 11, 11),
(93, 23, 12, 7),
(94, 24, 7, 0),
(95, 24, 8, 12),
(96, 24, 9, 12),
(97, 24, 10, 12),
(98, 24, 11, 12),
(99, 24, 12, 12),
(100, 25, 7, 0),
(101, 25, 8, 10),
(102, 25, 9, 10),
(103, 25, 10, 10),
(104, 25, 11, 10),
(105, 25, 12, 10),
(106, 26, 7, 0),
(107, 26, 8, 6),
(108, 26, 9, 8),
(109, 26, 10, 12),
(110, 26, 11, 8),
(111, 26, 12, 8),
(112, 27, 7, 0),
(113, 27, 8, 13),
(114, 27, 9, 14),
(115, 27, 10, 12),
(116, 27, 11, 7),
(117, 27, 12, 13),
(118, 28, 7, 0),
(119, 28, 8, 13),
(120, 28, 9, 7),
(121, 28, 10, 7),
(122, 28, 11, 7),
(123, 28, 12, 7),
(124, 29, 7, 2),
(125, 29, 8, 9),
(126, 29, 9, 9),
(127, 29, 10, 4),
(128, 29, 11, 6),
(129, 29, 12, 6),
(130, 30, 7, 10),
(131, 30, 8, 10),
(132, 30, 9, 10),
(133, 30, 10, 10),
(134, 30, 11, 10),
(135, 30, 12, 10),
(136, 31, 7, 10),
(137, 31, 8, 10),
(138, 31, 9, 10),
(139, 31, 10, 10),
(140, 31, 11, 10),
(141, 31, 12, 10),
(142, 32, 7, 10),
(143, 32, 8, 10),
(144, 32, 9, 10),
(145, 32, 10, 10),
(146, 32, 11, 10),
(147, 32, 12, 10),
(148, 33, 7, 10),
(149, 33, 8, 10),
(150, 33, 9, 10),
(151, 33, 10, 10),
(152, 33, 11, 10),
(153, 33, 12, 10);

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
(2, 'Running'),
(3, 'Casual');

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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_items_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `shoe_id` (`shoe_id`),
  ADD KEY `size_id` (`size_id`);

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
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_items_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_items_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `shoes`
--
ALTER TABLE `shoes`
  MODIFY `shoe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `size_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT for table `type`
--
ALTER TABLE `type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`shoe_id`) REFERENCES `shoes` (`shoe_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`size_id`) ON UPDATE CASCADE;

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
