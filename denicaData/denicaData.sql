-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2025 at 06:08 PM
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
-- Database: `denica`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `AdminID` int(11) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PasswordHash` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `CartID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`CartID`, `CustomerID`) VALUES
(1, 1),
(2, 3),
(3, 4),
(4, 7),
(5, 8),
(6, 9),
(7, 10);

-- --------------------------------------------------------

--
-- Table structure for table `cartitem`
--

CREATE TABLE `cartitem` (
  `CartItemID` int(11) NOT NULL,
  `CartID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cartitem`
--

INSERT INTO `cartitem` (`CartItemID`, `CartID`, `ItemID`, `Quantity`) VALUES
(9, 1, 2, 2),
(10, 1, 5, 1),
(11, 2, 3, 1),
(12, 2, 6, 1),
(13, 3, 4, 3),
(14, 4, 5, 2),
(15, 5, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CustomerID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PasswordHash` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CustomerID`, `Name`, `Email`, `PasswordHash`) VALUES
(1, 'amisha', '123@gmail.com', '$2y$10$fQGQsvyEYPnole0fTzsLiuF5jKjNa/vbbR0rPO8n7Lo65t2JNL/cu'),
(3, 'banana', '789@gmail.com', '$2y$10$NlyFe3BNGpfNCFztg/laaOB1q44JF5K7FW2IemabTvr2aml/R/mpu'),
(4, 'strawberry', '456@gmail.com', '$2y$10$IIn6CjwriR1Aj3NFjfCGE.Qzc2sR1NrK08i96o34JLyyPMXPnyu4.'),
(7, 'Aina Sofea', 'aina.sofea@gmail.com', '$2y$10$uZyH9F5yUQw1z0L5p5C8Pe9kDkLxYJ4eYxZ0aM2B4Pp4xC9pQKZpG'),
(8, 'Daniel Hakim', 'daniel.hakim@gmail.com', '$2y$10$J4dPz3cH7X6KQp5zCzVt0O9qFv3b4E8R1YpFzMZk1c5Kx2Z4L8Qq'),
(9, 'Nur Aisyah', 'aisyah.nur@gmail.com', '$2y$10$Wq8E7Q0pV2x5Ck1YF6LZQb3O8F5pN7M9HkXzD4p6Qy0B8K1P5R'),
(10, 'Muhammad Irfan', 'irfan.muhammad@gmail.com', '$2y$10$B1kF8VZC2L4xM9yE7R5KpP6N0ZJQd3Hq4XyA8C5F6M1RZ0Q2');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `ItemID` int(11) NOT NULL,
  `ProductName` varchar(100) NOT NULL,
  `Brand` varchar(225) NOT NULL,
  `ScentProfile` varchar(225) NOT NULL,
  `Description` varchar(225) NOT NULL,
  `Category` varchar(100) NOT NULL,
  `Price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`ItemID`, `ProductName`, `Brand`, `ScentProfile`, `Description`, `Category`, `Price`) VALUES
(2, 'Mahsuri', 'Medin', 'Floral', 'A soft fruity-floral scent with fresh opening and gentle floral heart, perfect for everyday wear.', 'Woman', 35.5),
(3, 'Mahsuri', 'Medin', 'Floral', 'A soft fruity-floral scent with fresh opening and gentle floral heart, perfect for everyday wear.', 'Woman', 35.5),
(4, 'Mahsuri', 'Medin Fragrance', 'Fruity-Floral', 'A soft fruity-floral scent with fresh opening and gentle floral heart, perfect for everyday wear.', 'Women', 35.5),
(5, 'Amaya', 'Szindore', 'Fresh', 'Fresh and slightly aquatic with citrus notes, versatile daily perfume.', 'Women', 40.9),
(6, 'Serena Aurea', 'The Toxic Lab', 'Musky-Amber', 'Warm musky-amber scent with sophisticated evening appeal.', 'Unisex', 29.9);

-- --------------------------------------------------------

--
-- Table structure for table `orderitem`
--

CREATE TABLE `orderitem` (
  `OrderItemID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `UnitPrice` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderitem`
--

INSERT INTO `orderitem` (`OrderItemID`, `OrderID`, `ItemID`, `Quantity`, `UnitPrice`) VALUES
(1, 1, 2, 2, 35.5),
(2, 1, 5, 1, 40.9),
(3, 2, 3, 1, 35.5),
(4, 2, 6, 1, 29.9),
(5, 3, 4, 3, 35.5),
(6, 4, 5, 2, 40.9),
(7, 5, 6, 1, 29.9);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `CartID` int(11) NOT NULL,
  `OrderDate` date NOT NULL,
  `OrderStatus` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `CustomerID`, `CartID`, `OrderDate`, `OrderStatus`) VALUES
(1, 1, 1, '2025-12-15', 'Completed'),
(2, 3, 2, '2025-12-18', 'Completed'),
(3, 4, 3, '2025-12-20', 'Processing'),
(4, 7, 4, '2025-12-22', 'Pending'),
(5, 8, 5, '2025-12-23', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `ReviewID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `ReviewDate` date NOT NULL,
  `Rating` int(11) NOT NULL,
  `Comment` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`ReviewID`, `CustomerID`, `ItemID`, `ReviewDate`, `Rating`, `Comment`) VALUES
(1, 1, 2, '2025-12-16', 5, 'Lovely floral scent, very elegant and long-lasting.'),
(2, 3, 3, '2025-12-18', 4, 'Nice fragrance but slightly strong for daytime use.'),
(3, 4, 4, '2025-12-20', 5, 'Absolutely amazing! Perfect balance of fruity and floral.'),
(4, 7, 5, '2025-12-22', 4, 'Fresh and clean scent, suitable for daily wear.'),
(5, 8, 6, '2025-12-23', 5, 'Musky and luxurious fragrance, highly recommended.');

-- --------------------------------------------------------

--
-- Table structure for table `shipment`
--

CREATE TABLE `shipment` (
  `ShipmentID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `CourierName` varchar(100) NOT NULL,
  `TrackingNumber` int(11) NOT NULL,
  `ShippingStatus` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipment`
--

INSERT INTO `shipment` (`ShipmentID`, `OrderID`, `CourierName`, `TrackingNumber`, `ShippingStatus`) VALUES
(1, 1, 'J&T Express', 0, 'Delivered'),
(2, 2, 'Pos Laju', 0, 'Delivered'),
(3, 3, 'Shopee Express', 0, 'In Transit'),
(4, 4, 'DHL eCommerce', 0, 'Pending'),
(5, 5, 'J&T Express', 0, 'Shipped');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`AdminID`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`CartID`),
  ADD KEY `cst_fk_key` (`CustomerID`);

--
-- Indexes for table `cartitem`
--
ALTER TABLE `cartitem`
  ADD PRIMARY KEY (`CartItemID`),
  ADD KEY `fk_cart` (`CartID`),
  ADD KEY `fk_item` (`ItemID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CustomerID`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`ItemID`);

--
-- Indexes for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD PRIMARY KEY (`OrderItemID`),
  ADD KEY `fk_order` (`OrderID`),
  ADD KEY `fk_itemOrder` (`ItemID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `fk_customer` (`CustomerID`),
  ADD KEY `fk_cartOrder` (`CartID`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`ReviewID`),
  ADD KEY `fk_customerReview` (`CustomerID`),
  ADD KEY `fk_itemReview` (`ItemID`);

--
-- Indexes for table `shipment`
--
ALTER TABLE `shipment`
  ADD PRIMARY KEY (`ShipmentID`),
  ADD KEY `fk_orderShipment` (`OrderID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `CartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cartitem`
--
ALTER TABLE `cartitem`
  MODIFY `CartItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `ItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orderitem`
--
ALTER TABLE `orderitem`
  MODIFY `OrderItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `ReviewID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `shipment`
--
ALTER TABLE `shipment`
  MODIFY `ShipmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cst_fk_key` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`);

--
-- Constraints for table `cartitem`
--
ALTER TABLE `cartitem`
  ADD CONSTRAINT `fk_cart` FOREIGN KEY (`CartID`) REFERENCES `cart` (`CartID`),
  ADD CONSTRAINT `fk_item` FOREIGN KEY (`ItemID`) REFERENCES `item` (`ItemID`);

--
-- Constraints for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD CONSTRAINT `fk_itemOrder` FOREIGN KEY (`ItemID`) REFERENCES `item` (`ItemID`),
  ADD CONSTRAINT `fk_order` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_cartOrder` FOREIGN KEY (`CartID`) REFERENCES `cart` (`CartID`),
  ADD CONSTRAINT `fk_customer` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `fk_customerReview` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`),
  ADD CONSTRAINT `fk_itemReview` FOREIGN KEY (`ItemID`) REFERENCES `item` (`ItemID`);

--
-- Constraints for table `shipment`
--
ALTER TABLE `shipment`
  ADD CONSTRAINT `fk_orderShipment` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
