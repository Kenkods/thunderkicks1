-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2025 at 07:54 PM
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

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `total_amount`, `created_at`) VALUES
(28, 14, 106287.00, '2025-05-30 13:34:52'),
(33, 17, 17998.00, '2025-05-30 15:05:56');

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
(83, 28, 22, 1, 3192.00, '2025-05-30 13:34:52', 85),
(84, 28, 23, 1, 10000.00, '2025-05-30 13:35:16', 93),
(85, 28, 24, 1, 75000.00, '2025-05-30 13:35:18', 98),
(86, 28, 18, 1, 7095.00, '2025-05-30 13:35:19', 63),
(87, 28, 58, 1, 11000.00, '2025-05-30 13:35:27', 301),
(98, 33, 20, 2, 8999.00, '2025-05-30 15:05:56', 73);

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
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notif_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`notif_id`, `user_id`, `order_id`, `description`, `created_at`) VALUES
(2, 12, 23, 'New order #23 received with total amount ₱17998.00by: 12', '2025-05-30 12:55:45'),
(3, NULL, 24, 'New order #24 received with total amount ₱7095.00', '2025-05-30 13:10:45'),
(4, NULL, 25, 'New order #25 received with total amount ₱9500.00', '2025-05-30 13:16:35'),
(5, NULL, 26, 'New order #26 received with total amount ₱10000.00', '2025-05-30 13:17:45'),
(6, NULL, 27, 'New order #27 received with total amount ₱7095.00', '2025-05-30 13:29:15'),
(7, NULL, 28, 'New order #28 received with total amount ₱44594.00', '2025-05-30 13:42:29'),
(8, NULL, 29, 'New order #29 received with total amount ₱46000.00', '2025-05-30 13:43:34'),
(9, NULL, 30, 'New order #30 received with total amount ₱7999.00', '2025-05-30 14:07:39'),
(10, NULL, 31, 'New order #31 received with total amount ₱13905.00', '2025-05-30 15:05:44'),
(11, NULL, 32, 'New order #32 received with total amount ₱35112.00', '2025-05-30 15:06:09'),
(12, NULL, 33, 'New order #33 received with total amount ₱8000.00', '2025-05-30 15:06:22'),
(13, NULL, 34, 'New order #34 received with total amount ₱30375.00', '2025-05-30 15:06:38'),
(14, NULL, 35, 'New order #35 received with total amount ₱9500.00', '2025-05-30 17:47:44'),
(15, NULL, 36, 'New order #36 received with total amount ₱7095.00', '2025-05-30 17:51:23');

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
(7, 4, 12692.00, 'Pending', '2025-05-30 05:37:20', '2025-05-30 05:37:20'),
(8, 4, 7095.00, 'Pending', '2025-05-30 08:49:00', '2025-05-30 08:49:00'),
(9, 4, 10000.00, 'Pending', '2025-05-30 08:51:02', '2025-05-30 08:51:02'),
(10, 4, NULL, 'Pending', '2025-05-30 08:51:13', '2025-05-30 08:51:13'),
(11, 4, 8999.00, 'Completed', '2025-05-30 08:51:25', '2025-05-30 10:24:18'),
(12, 4, 75000.00, 'Pending', '2025-05-30 08:53:38', '2025-05-30 08:53:38'),
(13, 4, 3192.00, 'Pending', '2025-05-30 08:56:08', '2025-05-30 08:56:08'),
(14, 4, 5466.00, 'Completed', '2025-05-30 09:19:08', '2025-05-30 10:36:09'),
(15, 4, NULL, 'Pending', '2025-05-30 09:19:20', '2025-05-30 09:19:20'),
(16, 4, 13000.00, 'Completed', '2025-05-30 09:24:25', '2025-05-30 10:20:54'),
(17, 4, 13905.00, 'Completed', '2025-05-30 09:25:40', '2025-05-30 10:19:06'),
(18, 4, 13000.00, 'Completed', '2025-05-30 10:27:25', '2025-05-30 10:29:43'),
(19, 4, 8999.00, 'Completed', '2025-05-30 10:29:23', '2025-05-30 10:35:43'),
(20, 4, 10000.00, 'Completed', '2025-05-30 10:39:39', '2025-05-30 10:39:49'),
(21, 4, 13905.00, 'Pending', '2025-05-30 12:09:50', '2025-05-30 12:09:50'),
(22, 12, 18499.00, 'Pending', '2025-05-30 12:50:19', '2025-05-30 12:50:19'),
(23, 12, 17998.00, 'Pending', '2025-05-30 12:55:45', '2025-05-30 12:55:45'),
(24, 12, 7095.00, 'Pending', '2025-05-30 13:10:45', '2025-05-30 13:10:45'),
(25, 13, 9500.00, 'Pending', '2025-05-30 13:16:35', '2025-05-30 13:16:35'),
(26, 13, 10000.00, 'Pending', '2025-05-30 13:17:45', '2025-05-30 13:17:45'),
(27, 14, 7095.00, 'Completed', '2025-05-30 13:29:15', '2025-05-30 13:29:26'),
(28, 15, 44594.00, 'Pending', '2025-05-30 13:42:29', '2025-05-30 13:42:29'),
(29, 15, 46000.00, 'Pending', '2025-05-30 13:43:34', '2025-05-30 13:43:34'),
(30, 17, 7999.00, 'Completed', '2025-05-30 14:07:39', '2025-05-30 14:08:09'),
(31, 17, 13905.00, 'Pending', '2025-05-30 15:05:44', '2025-05-30 15:05:44'),
(32, 17, 35112.00, 'Pending', '2025-05-30 15:06:09', '2025-05-30 15:06:09'),
(33, 17, 8000.00, 'Pending', '2025-05-30 15:06:22', '2025-05-30 15:06:22'),
(34, 17, 30375.00, 'Pending', '2025-05-30 15:06:38', '2025-05-30 15:06:38'),
(35, 12, 9500.00, 'Pending', '2025-05-30 17:47:44', '2025-05-30 17:47:44'),
(36, 12, 7095.00, 'Pending', '2025-05-30 17:51:23', '2025-05-30 17:51:23');

--
-- Triggers `orders`
--
DELIMITER $$
CREATE TRIGGER `after_order_insert` AFTER INSERT ON `orders` FOR EACH ROW BEGIN
    INSERT INTO `notification` (
        `user_id`,
        `order_id`,
        `description`,
        `created_at`
    ) VALUES (
        NULL, 
        NEW.order_id,
        CONCAT('New order #', NEW.order_id, ' received with total amount ₱', NEW.total_amount),
        NOW()
    );
END
$$
DELIMITER ;

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
(11, 7, 22, 82, 1, 3192.00, '2025-05-30 05:37:20'),
(13, 8, 18, 61, 1, 7095.00, '2025-05-30 08:49:00'),
(14, 9, 23, 92, 1, 10000.00, '2025-05-30 08:51:02'),
(15, 11, 20, 73, 1, 8999.00, '2025-05-30 08:51:25'),
(16, 12, 24, 96, 1, 75000.00, '2025-05-30 08:53:38'),
(17, 13, 22, 85, 1, 3192.00, '2025-05-30 08:56:08'),
(18, 14, 27, 115, 1, 5466.00, '2025-05-30 09:19:08'),
(19, 16, 28, 122, 1, 13000.00, '2025-05-30 09:24:25'),
(20, 17, 19, 67, 1, 13905.00, '2025-05-30 09:25:40'),
(21, 18, 28, 121, 1, 13000.00, '2025-05-30 10:27:25'),
(22, 19, 20, 73, 1, 8999.00, '2025-05-30 10:29:23'),
(23, 20, 23, 93, 1, 10000.00, '2025-05-30 10:39:39'),
(24, 21, 19, 68, 1, 13905.00, '2025-05-30 12:09:50'),
(25, 22, 17, 53, 1, 9500.00, '2025-05-30 12:50:19'),
(26, 22, 20, 75, 1, 8999.00, '2025-05-30 12:50:19'),
(28, 23, 20, 73, 2, 8999.00, '2025-05-30 12:55:45'),
(29, 24, 18, 62, 1, 7095.00, '2025-05-30 13:10:45'),
(30, 25, 17, 54, 1, 9500.00, '2025-05-30 13:16:35'),
(31, 26, 23, 92, 1, 10000.00, '2025-05-30 13:17:45'),
(32, 27, 18, 61, 1, 7095.00, '2025-05-30 13:29:15'),
(33, 28, 20, 73, 1, 8999.00, '2025-05-30 13:42:29'),
(34, 28, 17, 57, 3, 9500.00, '2025-05-30 13:42:29'),
(35, 28, 18, 62, 1, 7095.00, '2025-05-30 13:42:29'),
(36, 29, 28, 122, 1, 13000.00, '2025-05-30 13:43:34'),
(37, 29, 29, 128, 1, 8000.00, '2025-05-30 13:43:34'),
(38, 29, 59, 308, 1, 10000.00, '2025-05-30 13:43:34'),
(39, 29, 60, 314, 1, 10500.00, '2025-05-30 13:43:34'),
(40, 29, 62, 326, 1, 4500.00, '2025-05-30 13:43:34'),
(43, 30, 26, 109, 1, 7999.00, '2025-05-30 14:07:39'),
(44, 31, 19, 68, 1, 13905.00, '2025-05-30 15:05:44'),
(45, 32, 22, 85, 11, 3192.00, '2025-05-30 15:06:09'),
(46, 33, 29, 128, 1, 8000.00, '2025-05-30 15:06:22'),
(47, 34, 30, 133, 9, 3375.00, '2025-05-30 15:06:38'),
(48, 35, 17, 57, 1, 9500.00, '2025-05-30 17:47:44'),
(49, 36, 18, 63, 1, 7095.00, '2025-05-30 17:51:23');

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
(23, 1, 2, 'Men\'s ANTA KAI 1 \"Sacred Bond\"', 10000.00, '/thunderkicks1/public/uploads/shoe_683938ac1af38.png', 1),
(24, 1, 2, 'ANTA Shock Wave 5 Pro \"Sun\"', 75000.00, '/thunderkicks1/public/uploads/shoe_68393907e4d6a.png', 1),
(25, 1, 2, 'Men\'s ANTA KAI 1 \"Yin\"', 69000.00, '/thunderkicks1/public/uploads/shoe_68393973d9a41.png', 1),
(26, 1, 1, 'Anthony Edwards 1 Low Trainers', 7999.00, '/thunderkicks1/public/uploads/shoe_68393abb9365a.png', 1),
(27, 1, 1, 'Harden Volume 9 Shoes', 5466.00, '/thunderkicks1/public/uploads/shoe_68393b4f771b4.png', 1),
(28, 1, 4, '1906R', 13000.00, '/thunderkicks1/public/uploads/shoe_68393c2617e77.png', 3),
(29, 1, 1, 'Adizero Evo SL', 8000.00, '/thunderkicks1/public/uploads/shoe_683946f4b9ec7.png', 2),
(30, 2, 1, 'Duramo Speed 2', 3375.00, '/thunderkicks1/public/uploads/shoe_6839474facb40.png', 2),
(31, 2, 1, 'Response Super', 4000.00, '/thunderkicks1/public/uploads/shoe_683948dac97ad.png', 2),
(32, 2, 3, 'Jordan Heir Series PF \'Mother\'s Day\'', 7000.00, '/thunderkicks1/public/uploads/shoe_6839496c15daf.png', 1),
(33, 2, 3, 'Sabrina 1 \'Dedication\' EP', 7555.00, '/thunderkicks1/public/uploads/shoe_683949ae33493.png', 1),
(58, 1, 4, 'New Balance 327 ', 11000.00, '/thunderkicks1/public/uploads/shoe_6839653e72098.png', 3),
(59, 2, 4, 'New Balance 530', 10000.00, '/thunderkicks1/public/uploads/shoe_6839658b29eb3.png', 3),
(60, 2, 4, 'New Balance 1906R', 10500.00, '/thunderkicks1/public/uploads/shoe_683965df8566c.png', 3),
(61, 2, 2, 'Anta Rocket 4.0', 5000.00, '/thunderkicks1/public/uploads/shoe_6839664dc3d72.png', 2),
(62, 2, 2, 'Anta A-Flash Bubble 3.0', 4500.00, '/thunderkicks1/public/uploads/shoe_683966af04583.png', 2);

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
(153, 33, 12, 10),
(154, 34, 7, 10),
(155, 34, 8, 10),
(156, 34, 9, 10),
(157, 34, 10, 10),
(158, 34, 11, 10),
(159, 34, 12, 10),
(160, 35, 7, 10),
(161, 35, 8, 10),
(162, 35, 9, 10),
(163, 35, 10, 10),
(164, 35, 11, 10),
(165, 35, 12, 10),
(166, 36, 7, 10),
(167, 36, 8, 10),
(168, 36, 9, 10),
(169, 36, 10, 10),
(170, 36, 11, 10),
(171, 36, 12, 10),
(172, 37, 7, 10),
(173, 37, 8, 10),
(174, 37, 9, 10),
(175, 37, 10, 10),
(176, 37, 11, 10),
(177, 37, 12, 10),
(178, 38, 7, 10),
(179, 38, 8, 10),
(180, 38, 9, 10),
(181, 38, 10, 10),
(182, 38, 11, 10),
(183, 38, 12, 10),
(184, 39, 7, 10),
(185, 39, 8, 10),
(186, 39, 9, 10),
(187, 39, 10, 10),
(188, 39, 11, 10),
(189, 39, 12, 10),
(190, 40, 7, 10),
(191, 40, 8, 10),
(192, 40, 9, 10),
(193, 40, 10, 10),
(194, 40, 11, 10),
(195, 40, 12, 10),
(196, 41, 7, 10),
(197, 41, 8, 10),
(198, 41, 9, 10),
(199, 41, 10, 10),
(200, 41, 11, 10),
(201, 41, 12, 10),
(202, 42, 7, 10),
(203, 42, 8, 10),
(204, 42, 9, 10),
(205, 42, 10, 10),
(206, 42, 11, 10),
(207, 42, 12, 10),
(208, 43, 7, 10),
(209, 43, 8, 10),
(210, 43, 9, 10),
(211, 43, 10, 10),
(212, 43, 11, 10),
(213, 43, 12, 10),
(214, 44, 7, 10),
(215, 44, 8, 10),
(216, 44, 9, 10),
(217, 44, 10, 10),
(218, 44, 11, 10),
(219, 44, 12, 10),
(220, 45, 7, 10),
(221, 45, 8, 10),
(222, 45, 9, 10),
(223, 45, 10, 10),
(224, 45, 11, 10),
(225, 45, 12, 10),
(226, 46, 7, 10),
(227, 46, 8, 10),
(228, 46, 9, 10),
(229, 46, 10, 10),
(230, 46, 11, 10),
(231, 46, 12, 10),
(232, 47, 7, 10),
(233, 47, 8, 10),
(234, 47, 9, 10),
(235, 47, 10, 10),
(236, 47, 11, 10),
(237, 47, 12, 10),
(238, 48, 7, 10),
(239, 48, 8, 10),
(240, 48, 9, 10),
(241, 48, 10, 10),
(242, 48, 11, 10),
(243, 48, 12, 10),
(244, 49, 7, 10),
(245, 49, 8, 10),
(246, 49, 9, 10),
(247, 49, 10, 10),
(248, 49, 11, 10),
(249, 49, 12, 10),
(250, 50, 7, 10),
(251, 50, 8, 10),
(252, 50, 9, 10),
(253, 50, 10, 10),
(254, 50, 11, 10),
(255, 50, 12, 10),
(256, 51, 7, 10),
(257, 51, 8, 10),
(258, 51, 9, 10),
(259, 51, 10, 10),
(260, 51, 11, 10),
(261, 51, 12, 10),
(262, 52, 7, 10),
(263, 52, 8, 10),
(264, 52, 9, 10),
(265, 52, 10, 10),
(266, 52, 11, 10),
(267, 52, 12, 10),
(268, 53, 7, 10),
(269, 53, 8, 10),
(270, 53, 9, 10),
(271, 53, 10, 10),
(272, 53, 11, 10),
(273, 53, 12, 10),
(274, 54, 7, 10),
(275, 54, 8, 10),
(276, 54, 9, 10),
(277, 54, 10, 10),
(278, 54, 11, 10),
(279, 54, 12, 10),
(280, 55, 7, 10),
(281, 55, 8, 10),
(282, 55, 9, 10),
(283, 55, 10, 10),
(284, 55, 11, 10),
(285, 55, 12, 10),
(286, 56, 7, 10),
(287, 56, 8, 10),
(288, 56, 9, 10),
(289, 56, 10, 10),
(290, 56, 11, 10),
(291, 56, 12, 10),
(292, 57, 7, 10),
(293, 57, 8, 10),
(294, 57, 9, 10),
(295, 57, 10, 10),
(296, 57, 11, 10),
(297, 57, 12, 10),
(298, 58, 7, 10),
(299, 58, 8, 10),
(300, 58, 9, 10),
(301, 58, 10, 10),
(302, 58, 11, 10),
(303, 58, 12, 10),
(304, 59, 7, 10),
(305, 59, 8, 10),
(306, 59, 9, 10),
(307, 59, 10, 10),
(308, 59, 11, 10),
(309, 59, 12, 10),
(310, 60, 7, 10),
(311, 60, 8, 10),
(312, 60, 9, 10),
(313, 60, 10, 10),
(314, 60, 11, 10),
(315, 60, 12, 10),
(316, 61, 7, 10),
(317, 61, 8, 10),
(318, 61, 9, 10),
(319, 61, 10, 10),
(320, 61, 11, 10),
(321, 61, 12, 10),
(322, 62, 7, 10),
(323, 62, 8, 10),
(324, 62, 9, 10),
(325, 62, 10, 10),
(326, 62, 11, 10),
(327, 62, 12, 10);

-- --------------------------------------------------------

--
-- Stand-in structure for view `top_4_shoes`
-- (See below for the actual view)
--
CREATE TABLE `top_4_shoes` (
`shoe_id` int(11)
,`cat_id` int(11)
,`brand_id` int(11)
,`name` varchar(120)
,`price` decimal(10,2)
,`shoe_img` varchar(255)
,`type_id` int(11)
,`size_id` int(11)
,`size` int(11)
,`total_quantity` decimal(32,0)
);

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
(11, 'John Kenneth Pantonial', 'khan@gmail.com', '123kenneth', '$2y$10$pgcWFRO4Siq84F6BwmTSBujiFviBzj0f8HLUoBNDJWr.Kq6gJRy6u'),
(12, 'deb', 'davezkiecabz@gmail.com', 'deb', '$2y$10$jrxDoSP8U3zrZxyhRVsttO0DQovEbta207kvQOFP9MRpstQ12Kfv.'),
(13, 'brent', 'brent@gmail.com', 'brent', '$2y$10$Cp/Hfa2zy0j5h56YDYhZ0O0A1aa9z9SHmZtGO99utO6sPeWnQ6fca'),
(14, 'brent', 'bren3t@gmail.com', 'brent123', '$2y$10$dFKpkUp/xlYsOjcSvivEZeYMvrtPa5esb6MU723A2YEujxQo11sim'),
(15, 'ralph levevev', 'adasda@awdas', 'rra', '$2y$10$ikW5VEaQ0sYtVEMGpUSCket4O1dlDSbZjFyoses3NR.Ns/dGbwGju'),
(16, 'brent areglo', 'brent@gmail.com', 'brent', '$2y$10$FbkmYmYnUCQ0wyH3yYD83.mt5VVy4bxrP4G4J2EsVquVgMfFjU0A2'),
(17, 'brent areglo', 'brent2@gmail.com', 'brent1', '$2y$10$J98hW5lb34e9UZRAnsngJuBcq0M3vfOcdx/SQ16Lf5btNxImf9uCK');

-- --------------------------------------------------------

--
-- Stand-in structure for view `viewreceipt`
-- (See below for the actual view)
--
CREATE TABLE `viewreceipt` (
`order_id` int(11)
,`user_id` int(11)
,`created_at` timestamp
,`total_amount` decimal(10,2)
,`status` varchar(50)
,`shoe_id` int(11)
,`size_id` int(11)
,`quantity` int(11)
,`price` decimal(10,2)
);

-- --------------------------------------------------------

--
-- Structure for view `top_4_shoes`
--
DROP TABLE IF EXISTS `top_4_shoes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `top_4_shoes`  AS SELECT `s`.`shoe_id` AS `shoe_id`, `s`.`cat_id` AS `cat_id`, `s`.`brand_id` AS `brand_id`, `s`.`name` AS `name`, `s`.`price` AS `price`, `s`.`shoe_img` AS `shoe_img`, `s`.`type_id` AS `type_id`, `oi_summary`.`size_id` AS `size_id`, `sz`.`size` AS `size`, `oi_summary`.`total_quantity` AS `total_quantity` FROM (((select `oi`.`shoe_id` AS `shoe_id`,`oi`.`size_id` AS `size_id`,sum(`oi`.`quantity`) AS `total_quantity` from `order_items` `oi` group by `oi`.`shoe_id`,`oi`.`size_id` order by sum(`oi`.`quantity`) desc limit 4) `oi_summary` join `shoes` `s` on(`s`.`shoe_id` = `oi_summary`.`shoe_id`)) join `sizes` `sz` on(`oi_summary`.`size_id` = `sz`.`size_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `viewreceipt`
--
DROP TABLE IF EXISTS `viewreceipt`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `viewreceipt`  AS SELECT `o`.`order_id` AS `order_id`, `o`.`user_id` AS `user_id`, `o`.`created_at` AS `created_at`, `o`.`total_amount` AS `total_amount`, `o`.`status` AS `status`, `oi`.`shoe_id` AS `shoe_id`, `oi`.`size_id` AS `size_id`, `oi`.`quantity` AS `quantity`, `oi`.`price` AS `price` FROM (`orders` `o` join `order_items` `oi` on(`o`.`order_id` = `oi`.`order_id`)) ;

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
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notif_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_notification_order` (`order_id`);

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
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_items_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notif_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_items_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `shoes`
--
ALTER TABLE `shoes`
  MODIFY `shoe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `size_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=328;

--
-- AUTO_INCREMENT for table `type`
--
ALTER TABLE `type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `fk_notification_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `shoes`
--
ALTER TABLE `shoes`
  ADD CONSTRAINT `fk_type` FOREIGN KEY (`type_id`) REFERENCES `type` (`type_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shoes_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `category` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shoes_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`brand_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
