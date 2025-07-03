-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2025 at 10:52 AM
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
-- Database: `stationery_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(1, 'Writing Instruments'),
(2, 'Paper Products'),
(3, 'Office Supplies'),
(4, 'Art Materials');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_number` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `customer_name`, `address`, `contact_number`, `email`, `username`, `password`, `shipping_address`) VALUES
(1, 'Miguel Bernando', 'Jupiter Street, Makati City, NCR 1142, Mars', '09543058392', 'migs@gmail.com', 'akosimigs', '123456', 'Jupiter Street, Makati City, NCR 1142, Mars');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `order_id` int(11) NOT NULL,
  `order_item_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `order_item_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`order_item_id`, `quantity`, `price`, `product_id`) VALUES
(1, 4, 12.50, 1),
(2, 5, 12.50, 1),
(3, 4, 55.00, 3),
(4, 5, 5.00, 5),
(5, 5, 25.00, 4),
(6, 1, 8.00, 2),
(7, 5, 12.50, 1),
(8, 1, 8.00, 2),
(9, 4, 15.00, 6),
(10, 4, 25.00, 4),
(11, 3, 55.00, 3),
(12, 3, 12.50, 1),
(13, 3, 12.50, 1),
(14, 4, 15.00, 6),
(15, 4, 12.50, 1),
(16, 5, 12.50, 1),
(17, 4, 12.50, 1),
(18, 5, 55.00, 3),
(19, 1, 12.50, 1),
(20, 1, 8.00, 2),
(21, 1, 55.00, 3),
(22, 1, 12.50, 1),
(23, 1, 8.00, 2),
(24, 1, 55.00, 3),
(25, 1, 12.50, 1),
(26, 1, 8.00, 2),
(27, 1, 55.00, 3),
(28, 1, 12.50, 1),
(29, 1, 8.00, 2),
(30, 1, 55.00, 3),
(31, 1, 12.50, 1),
(32, 1, 8.00, 2),
(33, 1, 55.00, 3),
(34, 1, 12.50, 1),
(35, 1, 8.00, 2),
(36, 1, 55.00, 3),
(37, 1, 12.50, 1),
(38, 1, 8.00, 2),
(39, 1, 55.00, 3),
(40, 1, 12.50, 1),
(41, 1, 8.00, 2),
(42, 1, 12.50, 1),
(43, 1, 8.00, 2),
(44, 1, 5.00, 5),
(45, 1, 12.50, 1);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `order_id`, `customer_id`, `date`, `payment_method`, `total`) VALUES
(1, NULL, 1, '2025-07-03', 'cash', 50.00),
(2, NULL, 1, '2025-07-03', 'gcash', 432.50),
(3, NULL, 1, '2025-07-03', 'cash', 8.00),
(4, NULL, 1, '2025-07-03', 'cash', 62.50),
(5, NULL, 1, '2025-07-03', 'gcash', 168.00),
(6, NULL, 1, '2025-07-03', 'credit_card', 202.50),
(7, NULL, 1, '2025-07-03', 'cash', 97.50),
(8, NULL, 1, '2025-07-03', 'cash', 50.00),
(9, NULL, 1, '2025-07-03', 'cash', 62.50),
(10, NULL, 1, '2025-07-03', 'cash', 325.00),
(11, NULL, 1, '2025-07-03', 'cash', 75.50),
(12, NULL, 1, '2025-07-03', 'cash', 75.50),
(13, NULL, 1, '2025-07-03', 'cash', 75.50),
(14, NULL, 1, '2025-07-03', 'cash', 75.50),
(15, NULL, 1, '2025-07-03', 'cash', 75.50),
(16, NULL, 1, '2025-07-03', 'cash', 75.50),
(17, NULL, 1, '2025-07-03', 'cash', 75.50),
(18, NULL, 1, '2025-07-03', 'cash', 20.50),
(19, NULL, 1, '2025-07-03', 'cash', 20.50),
(20, NULL, 1, '2025-07-03', 'cash', 5.00),
(21, NULL, 1, '2025-07-03', 'cash', 12.50);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `date_added` date DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `recommended` enum('Highly Recommended','Neutral','Not recommended') NOT NULL,
  `stock` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `ratings` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `date_added`, `category_id`, `price`, `recommended`, `stock`, `image_url`, `ratings`) VALUES
(1, 'Ballpoint Pen', '2025-06-30', 1, 12.50, 'Highly Recommended', 100, 'ballpoint_pen.png', 10),
(2, 'Pencil #2', '2025-06-30', 1, 8.00, 'Neutral', 200, 'pencil_2.png', 4),
(3, 'A4 Notebook', '2025-06-30', 2, 55.00, 'Highly Recommended', 150, 'a4_notebook.png', 10),
(4, 'Sticky Notes', '2025-06-30', 3, 25.00, 'Not recommended', 80, 'sticky_notes.png', 0),
(5, 'Eraser', '2025-06-30', 1, 5.00, 'Neutral', 300, 'eraser.png', 5),
(6, 'Ruler 12-inch', '2025-06-30', 1, 15.00, 'Highly Recommended', 120, 'ruler_12-inch.png', 9),
(7, 'Highlighter (Yellow)', '2025-06-30', 3, 30.00, 'Highly Recommended', 90, 'highlighter_yellow.png', 7),
(8, 'Correction Tape', '2025-06-30', 3, 35.00, 'Neutral', 60, 'correction_tape.png', 4),
(9, 'Glue Stick', '2025-06-30', 3, 20.00, 'Not recommended', 70, 'glue_stick.png', 1),
(10, 'Crayons 12 colors', '2025-06-30', 4, 75.00, 'Highly Recommended', 50, 'crayons_12_colors.png', 7),
(11, 'Colored Pencils 24 colors', '2025-06-30', 4, 120.00, 'Neutral', 40, 'colored_pencils_24_colors.png', 6),
(12, 'Scissors (Student)', '2025-06-30', 3, 45.00, 'Highly Recommended', 55, 'scissors_student.png', 8),
(13, 'Binder Clips (Small)', '2025-06-30', 3, 18.00, 'Not recommended', 100, 'binder_clips_small.png', 0),
(14, 'Drawing Pad', '2025-06-30', 2, 60.00, 'Highly Recommended', 45, 'drawing_pad.png', 7),
(15, 'Index Cards', '2025-06-30', 2, 22.00, 'Neutral', 130, 'index_cards.png', 4),
(16, 'Permanent Marker (Black)', '2025-06-30', 3, 28.00, 'Highly Recommended', 75, 'permanent_marker_black.png', 10),
(17, 'Folder (Plastic)', '2025-06-30', 2, 20.00, 'Neutral', 85, 'folder_plastic.png', 5),
(18, 'Sketch Pen Set', '2025-06-30', 4, 95.00, 'Highly Recommended', 35, 'sketch_pen_set.png', 7),
(19, 'Whiteboard Marker (Blue)', '2025-06-30', 3, 26.00, 'Not recommended', 60, 'whiteboard_marker_blue.png', 0),
(20, 'Stapler (Mini)', '2025-06-30', 3, 65.00, 'Highly Recommended', 40, 'stapler_mini.png', 9);

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE `receipt` (
  `OR_number` int(11) NOT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `order_item_id` (`order_item_id`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `receipt`
--
ALTER TABLE `receipt`
  ADD PRIMARY KEY (`OR_number`),
  ADD KEY `payment_id` (`payment_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `receipt`
--
ALTER TABLE `receipt`
  MODIFY `OR_number` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_item_id`) REFERENCES `order_item` (`order_item_id`);

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order_details` (`order_id`),
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`);

--
-- Constraints for table `receipt`
--
ALTER TABLE `receipt`
  ADD CONSTRAINT `receipt_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`payment_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
