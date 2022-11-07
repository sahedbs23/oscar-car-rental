-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Nov 06, 2022 at 02:29 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oscar`
--

-- --------------------------------------------------------

--
-- Table structure for table `car_brands`
--

CREATE TABLE `car_brands` (
                              `id` bigint NOT NULL,
                              `brand_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `car_features`
--

CREATE TABLE `car_features` (
                                `id` bigint NOT NULL,
                                `vehicle_id` bigint NOT NULL,
                                `inside_height` float DEFAULT NULL,
                                `inside_length` float DEFAULT NULL,
                                `inside_width` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `car_locations`
--

CREATE TABLE `car_locations` (
                                 `id` bigint NOT NULL,
                                 `location` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `car_models`
--

CREATE TABLE `car_models` (
                              `id` bigint NOT NULL,
                              `car_model` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fuels`
--

CREATE TABLE `fuels` (
                         `id` bigint NOT NULL,
                         `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fuel_vehicle`
--

CREATE TABLE `fuel_vehicle` (
                                `vehicle_id` bigint DEFAULT NULL,
                                `fuel_id` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
                            `id` bigint NOT NULL,
                            `location` bigint NOT NULL,
                            `car_brand` bigint NOT NULL,
                            `car_model` bigint NOT NULL,
                            `license_plate` varchar(50) DEFAULT NULL,
                            `car_year` int DEFAULT '0',
                            `number_of_doors` int DEFAULT '0',
                            `number_of_seats` int DEFAULT '0',
                            `fuel_type` varchar(100) DEFAULT NULL,
                            `transmission` varchar(100) DEFAULT NULL,
                            `car_type_group` varchar(100) DEFAULT NULL,
                            `car_type` varchar(100) DEFAULT NULL,
                            `car_km` float DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `car_brands`
--
ALTER TABLE `car_brands`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `brand_name` (`brand_name`);

--
-- Indexes for table `car_features`
--
ALTER TABLE `car_features`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `car_locations`
--
ALTER TABLE `car_locations`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `location` (`location`);

--
-- Indexes for table `car_models`
--
ALTER TABLE `car_models`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `car_model` (`car_model`);

--
-- Indexes for table `fuels`
--
ALTER TABLE `fuels`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `fuel_vehicle`
--
ALTER TABLE `fuel_vehicle`
    ADD KEY `vehicle_id` (`vehicle_id`),
    ADD KEY `fuel_id` (`fuel_id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `license_plate` (`license_plate`),
    ADD KEY `location` (`location`),
    ADD KEY `car_brand` (`car_brand`),
    ADD KEY `car_model` (`car_model`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `car_brands`
--
ALTER TABLE `car_brands`
    MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `car_features`
--
ALTER TABLE `car_features`
    MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `car_locations`
--
ALTER TABLE `car_locations`
    MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `car_models`
--
ALTER TABLE `car_models`
    MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fuels`
--
ALTER TABLE `fuels`
    MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
    MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `car_features`
--
ALTER TABLE `car_features`
    ADD CONSTRAINT `car_features_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`);

--
-- Constraints for table `fuel_vehicle`
--
ALTER TABLE `fuel_vehicle`
    ADD CONSTRAINT `fuel_vehicle_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`),
    ADD CONSTRAINT `fuel_vehicle_ibfk_2` FOREIGN KEY (`fuel_id`) REFERENCES `fuels` (`id`);

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
    ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`location`) REFERENCES `car_locations` (`id`),
    ADD CONSTRAINT `vehicles_ibfk_2` FOREIGN KEY (`car_brand`) REFERENCES `car_brands` (`id`),
    ADD CONSTRAINT `vehicles_ibfk_3` FOREIGN KEY (`car_model`) REFERENCES `car_models` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
