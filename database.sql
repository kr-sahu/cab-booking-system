-- Zuber Database Schema
-- Provides the necessary structures for user authentication, ride booking, and payment management.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- DATABASE INITIALIZATION
CREATE DATABASE IF NOT EXISTS `cab_booking`;
USE `cab_booking`;

-- --------------------------------------------------------

--
-- TABLE: USERS
-- Stores personal account details and authentication credentials.
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) DEFAULT NULL, -- Primary display name
  `name` varchar(255) DEFAULT NULL,      -- Support for direct name updates
  `email` varchar(255) NOT NULL,        -- Login identifier
  `password` varchar(255) NOT NULL,     -- Hashed credential
  `profile_image` varchar(255) DEFAULT NULL, -- Relative path to avatar
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- TABLE: BOOKINGS
-- Records historical and active ride requests including assigned driver data.
--

CREATE TABLE IF NOT EXISTS `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL, -- Reference to the passenger
  `pickup_location` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `fare` decimal(10,2) NOT NULL,
  `distance` varchar(50) DEFAULT NULL,
  `status` enum('pending','accepted','completed','cancelled') DEFAULT 'pending',
  `driver_id` int(11) DEFAULT NULL, -- Reference to the assigned driver
  `driver_name` varchar(255) DEFAULT NULL,
  `driver_contact` varchar(20) DEFAULT NULL,
  `cab_model` varchar(100) DEFAULT NULL,
  `cab_number` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`driver_id`) REFERENCES `drivers`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- TABLE: USER_CARDS
-- Stores non-sensitive card metadata for payment reference.
--

CREATE TABLE IF NOT EXISTS `user_cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `card_holder` varchar(255) NOT NULL,
  `card_brand` varchar(20) NOT NULL, -- Visa, Mastercard, etc.
  `last_four` varchar(4) NOT NULL,    -- Last 4 digits for verification
  `expiry` varchar(10) NOT NULL,      -- MM/YY format
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- TABLE: ADMINS
-- Stores system administrator credentials.
--

CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert Default Admin (admin / Password@123)
-- Note: In a production environment, this should be handled by a secure setup script.
INSERT IGNORE INTO `admins` (`username`, `password`, `fullname`) VALUES 
('admin', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'System Administrator');

--
-- TABLE: DRIVERS
-- Stores driver details, credentials, and real-time availability status.
--

CREATE TABLE IF NOT EXISTS `drivers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `contact` varchar(20) NOT NULL,
  `license_no` varchar(50) NOT NULL,
  `gov_id` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `status` enum('available', 'on_trip', 'offline') DEFAULT 'offline',
  `is_approved` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `license_no` (`license_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- TABLE: CABS
-- Manages vehicle information and links them to assigned drivers.
--

CREATE TABLE IF NOT EXISTS `cabs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reg_no` varchar(50) NOT NULL UNIQUE,
  `cab_type` varchar(50) NOT NULL,
  `seats` int(11) DEFAULT 4,
  `model` varchar(100) NOT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `status` enum('available', 'busy', 'maintenance') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`driver_id`) REFERENCES `drivers`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;
