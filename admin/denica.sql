-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2025 at 12:09 PM
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
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `CustomerID` int(11) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `Email` varchar(150) NOT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`CustomerID`, `FullName`, `Email`, `Phone`, `CreatedAt`) VALUES
(1, 'Amisha Jembu Iloh', 'amisha@example.com', '0123456789', '2025-12-30 09:54:26'),
(2, 'Rizal Hakim', 'rizal@example.com', '0198765432', '2025-12-30 09:54:26'),
(3, 'Liyana Syafiqah', 'liyana@example.com', '0171122334', '2025-12-30 09:54:26'),
(4, 'Collina De Andriana', 'collina@gmail.com', '0183456789', '2025-12-30 10:20:17'),
(5, 'Aliah Rusydina', 'Arsy@gmail.com', '01118765432', '2025-12-30 10:20:17'),
(6, 'Delysha', 'Deky@gmal.com', '0121122334', '2025-12-30 10:20:17');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `ItemID` int(11) NOT NULL,
  `ProductName` varchar(100) NOT NULL,
  `Brand` varchar(100) NOT NULL,
  `ScentProfile` varchar(50) NOT NULL,
  `Description` text NOT NULL,
  `Category` varchar(20) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`ItemID`, `ProductName`, `Brand`, `ScentProfile`, `Description`, `Category`, `Price`, `CreatedAt`) VALUES
(1, 'Mahsuri', 'Medin Fragrance', 'Fruity', 'flowery', 'Woman', 35.50, '2025-12-30 08:32:47'),
(2, 'Amaya', 'Szindore', 'Fresh', 'Everyday Signature scent', 'Woman', 40.90, '2025-12-30 08:37:49'),
(4, 'Serena Aurea', 'The Toxic Lab', 'Musky', 'UNISEX appeal, ideal for evening wear or sophisticated statement.', 'UNISEX', 29.90, '2025-12-30 09:29:58');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `OrderDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `ItemsSold` int(11) NOT NULL,
  `GrandTotal` decimal(10,2) NOT NULL,
  `Status` enum('Pending','Processing','Completed','Cancelled') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `CustomerID`, `OrderDate`, `ItemsSold`, `GrandTotal`, `Status`) VALUES
(1, 1, '2025-12-28 06:30:00', 2, 258.00, 'Completed'),
(2, 2, '2025-12-27 02:15:00', 1, 99.90, 'Completed'),
(3, 3, '2025-12-21 08:45:00', 3, 428.50, 'Completed'),
(4, 1, '2025-11-15 04:00:00', 1, 149.50, 'Completed'),
(5, 3, '2025-12-01 01:30:00', 2, 278.90, 'Pending'),
(6, 1, '2025-10-05 02:20:00', 2, 258.90, 'Completed'),
(7, 4, '2025-10-10 07:45:00', 1, 149.50, 'Completed'),
(8, 5, '2025-10-22 04:30:00', 3, 378.40, 'Completed'),
(9, 2, '2025-11-01 01:15:00', 1, 129.00, 'Completed'),
(10, 6, '2025-11-15 06:00:00', 2, 278.90, 'Pending'),
(11, 3, '2025-11-20 08:50:00', 1, 99.90, 'Completed'),
(12, 1, '2025-12-01 03:10:00', 2, 249.90, 'Completed'),
(13, 4, '2025-12-15 05:20:00', 3, 378.40, 'Completed'),
(14, 5, '2025-12-28 06:30:00', 2, 258.00, 'Completed'),
(15, 2, '2025-12-30 01:15:00', 1, 129.00, 'Completed'),
(16, 3, '2025-12-30 03:45:00', 2, 249.90, 'Pending'),
(17, 6, '2025-12-30 06:30:00', 1, 99.90, 'Completed');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`CustomerID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`ItemID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `CustomerID` (`CustomerID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `ItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customers` (`CustomerID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
