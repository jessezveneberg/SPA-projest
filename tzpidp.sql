-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Час створення: Чрв 09 2024 р., 21:46
-- Версія сервера: 5.7.39
-- Версія PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `tzpidp`
--

-- --------------------------------------------------------

--
-- Структура таблиці `Depreciation`
--

CREATE TABLE `Depreciation` (
  `DepreciationID` int(11) NOT NULL,
  `VehicleID` int(11) DEFAULT NULL,
  `DepreciationDate` date DEFAULT NULL,
  `DepreciationAmount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `Drivers`
--

CREATE TABLE `Drivers` (
  `DriverID` int(11) NOT NULL,
  `FirstName` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `LastName` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `LicenseNumber` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ContactInfo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `Drivers`
--

INSERT INTO `Drivers` (`DriverID`, `FirstName`, `LastName`, `LicenseNumber`, `ContactInfo`) VALUES
(1, 'Jane', 'Smith', 'S2345678', 'jane.smith@example.com'),
(2, 'Alice', 'Johnson', 'J3456789', 'alice.johnson@example.com');

-- --------------------------------------------------------

--
-- Структура таблиці `Fuel`
--

CREATE TABLE `Fuel` (
  `FuelID` int(11) NOT NULL,
  `VehicleID` int(11) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `Liters` decimal(10,2) DEFAULT NULL,
  `CostPerLiter` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `Fuel`
--

INSERT INTO `Fuel` (`FuelID`, `VehicleID`, `Date`, `Liters`, `CostPerLiter`) VALUES
(1, 2, '2024-06-09', '50.00', '12.51');

-- --------------------------------------------------------

--
-- Структура таблиці `Insurance`
--

CREATE TABLE `Insurance` (
  `InsuranceID` int(11) NOT NULL,
  `VehicleID` int(11) DEFAULT NULL,
  `PolicyNumber` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `StartDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL,
  `Cost` decimal(10,2) DEFAULT NULL,
  `Company` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `Insurance`
--

INSERT INTO `Insurance` (`InsuranceID`, `VehicleID`, `PolicyNumber`, `StartDate`, `EndDate`, `Cost`, `Company`) VALUES
(1, 1, 'dffdfdf', '2024-06-06', '2024-06-28', '0.13', 'fgfdfd');

-- --------------------------------------------------------

--
-- Структура таблиці `Maintenance`
--

CREATE TABLE `Maintenance` (
  `MaintenanceID` int(11) NOT NULL,
  `VehicleID` int(11) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `Description` text COLLATE utf8mb4_unicode_ci,
  `Cost` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `Maintenance`
--

INSERT INTO `Maintenance` (`MaintenanceID`, `VehicleID`, `Date`, `Description`, `Cost`) VALUES
(1, 3, '2024-06-17', 'заміна мастила', '50.02');

-- --------------------------------------------------------

--
-- Структура таблиці `Users`
--

CREATE TABLE `Users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PasswordHash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `FirstName` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `LastName` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CreatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Role` enum('User','Admin') COLLATE utf8mb4_unicode_ci DEFAULT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `Users`
--

INSERT INTO `Users` (`UserID`, `Username`, `PasswordHash`, `FirstName`, `LastName`, `Email`, `CreatedAt`, `Role`) VALUES
(1, 'test', '$2y$10$jLltpjESz5LaqFAkDhuXM.5f3G.4rUhsJwPeOv3P7GkPDj0dnqTBu', 'test', 'test', 'test@gmail.com', '2024-06-01 17:13:53', 'Admin'),
(9, 'test11', '$2y$10$XBL25dums.RuKD7BwU5GtelVb8RMrHtKdsXHdqunW1oTzUq.M1Dm2', 't', 't', 'test11@gmail.com', '2024-06-01 20:18:27', 'User'),
(10, 't1', '$2y$10$CgI4hHtMCY8xNW1xzwReJOr38yxI.yR6za0M1PMQ/z/VbMCnMzXAK', 't', 't', 't@gmail.com', '2024-06-09 15:53:44', 'User'),
(11, 'testovij', '$2y$10$kXoC4.yPekZSeyKeGdTEu.ZsjBTk0naQ6xOCnVXMwrL4dRsl2tjD6', 'testovij1', 'testovij', 'testovij@gmail.com', '2024-06-09 18:21:12', 'User');

-- --------------------------------------------------------

--
-- Структура таблиці `VehicleDrivers`
--

CREATE TABLE `VehicleDrivers` (
  `VehicleID` int(11) NOT NULL,
  `DriverID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `VehicleDrivers`
--

INSERT INTO `VehicleDrivers` (`VehicleID`, `DriverID`) VALUES
(3, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Структура таблиці `VehicleImages`
--

CREATE TABLE `VehicleImages` (
  `ImageID` int(11) NOT NULL,
  `VehicleID` int(11) DEFAULT NULL,
  `ImagePath` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `VehicleImages`
--

INSERT INTO `VehicleImages` (`ImageID`, `VehicleID`, `ImagePath`, `Description`) VALUES
(3, 2, 'img/honda.jpg', 'honda'),
(4, 1, 'img/toyota.png', 'toyota');

-- --------------------------------------------------------

--
-- Структура таблиці `Vehicles`
--

CREATE TABLE `Vehicles` (
  `VehicleID` int(11) NOT NULL,
  `Make` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Model` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Year` int(11) DEFAULT NULL,
  `RegistrationNumber` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `VehicleStatus` enum('active','maintenance','in_road','decommissioned') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `InitialCost` decimal(10,2) DEFAULT NULL,
  `UsefulLife` int(11) DEFAULT NULL,
  `DepreciationMethod` enum('linear','double_declining') COLLATE utf8mb4_unicode_ci DEFAULT 'linear'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `Vehicles`
--

INSERT INTO `Vehicles` (`VehicleID`, `Make`, `Model`, `Year`, `RegistrationNumber`, `VehicleStatus`, `InitialCost`, `UsefulLife`, `DepreciationMethod`) VALUES
(1, 'Toyota', 'Corolla', 2019, 'AF1234', 'active', '5000000.00', 5, 'linear'),
(2, 'Honda', 'Civic', 2021, 'CD5678', 'active', NULL, NULL, 'linear'),
(3, 'Ford', 'Fusion', 2018, 'EF9012', 'active', NULL, NULL, 'linear');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `Depreciation`
--
ALTER TABLE `Depreciation`
  ADD PRIMARY KEY (`DepreciationID`),
  ADD KEY `VehicleID` (`VehicleID`);

--
-- Індекси таблиці `Drivers`
--
ALTER TABLE `Drivers`
  ADD PRIMARY KEY (`DriverID`);

--
-- Індекси таблиці `Fuel`
--
ALTER TABLE `Fuel`
  ADD PRIMARY KEY (`FuelID`),
  ADD KEY `VehicleID` (`VehicleID`);

--
-- Індекси таблиці `Insurance`
--
ALTER TABLE `Insurance`
  ADD PRIMARY KEY (`InsuranceID`),
  ADD KEY `VehicleID` (`VehicleID`);

--
-- Індекси таблиці `Maintenance`
--
ALTER TABLE `Maintenance`
  ADD PRIMARY KEY (`MaintenanceID`),
  ADD KEY `VehicleID` (`VehicleID`);

--
-- Індекси таблиці `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Індекси таблиці `VehicleDrivers`
--
ALTER TABLE `VehicleDrivers`
  ADD PRIMARY KEY (`VehicleID`,`DriverID`),
  ADD KEY `DriverID` (`DriverID`);

--
-- Індекси таблиці `VehicleImages`
--
ALTER TABLE `VehicleImages`
  ADD PRIMARY KEY (`ImageID`),
  ADD KEY `VehicleID` (`VehicleID`);

--
-- Індекси таблиці `Vehicles`
--
ALTER TABLE `Vehicles`
  ADD PRIMARY KEY (`VehicleID`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `Depreciation`
--
ALTER TABLE `Depreciation`
  MODIFY `DepreciationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблиці `Drivers`
--
ALTER TABLE `Drivers`
  MODIFY `DriverID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблиці `Fuel`
--
ALTER TABLE `Fuel`
  MODIFY `FuelID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблиці `Insurance`
--
ALTER TABLE `Insurance`
  MODIFY `InsuranceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблиці `Maintenance`
--
ALTER TABLE `Maintenance`
  MODIFY `MaintenanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблиці `Users`
--
ALTER TABLE `Users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблиці `VehicleImages`
--
ALTER TABLE `VehicleImages`
  MODIFY `ImageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблиці `Vehicles`
--
ALTER TABLE `Vehicles`
  MODIFY `VehicleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `Depreciation`
--
ALTER TABLE `Depreciation`
  ADD CONSTRAINT `depreciation_ibfk_1` FOREIGN KEY (`VehicleID`) REFERENCES `Vehicles` (`VehicleID`);

--
-- Обмеження зовнішнього ключа таблиці `Fuel`
--
ALTER TABLE `Fuel`
  ADD CONSTRAINT `fuel_ibfk_1` FOREIGN KEY (`VehicleID`) REFERENCES `Vehicles` (`VehicleID`);

--
-- Обмеження зовнішнього ключа таблиці `Insurance`
--
ALTER TABLE `Insurance`
  ADD CONSTRAINT `insurance_ibfk_1` FOREIGN KEY (`VehicleID`) REFERENCES `Vehicles` (`VehicleID`);

--
-- Обмеження зовнішнього ключа таблиці `Maintenance`
--
ALTER TABLE `Maintenance`
  ADD CONSTRAINT `maintenance_ibfk_1` FOREIGN KEY (`VehicleID`) REFERENCES `Vehicles` (`VehicleID`);

--
-- Обмеження зовнішнього ключа таблиці `VehicleDrivers`
--
ALTER TABLE `VehicleDrivers`
  ADD CONSTRAINT `vehicledrivers_ibfk_1` FOREIGN KEY (`VehicleID`) REFERENCES `Vehicles` (`VehicleID`),
  ADD CONSTRAINT `vehicledrivers_ibfk_2` FOREIGN KEY (`DriverID`) REFERENCES `Drivers` (`DriverID`);

--
-- Обмеження зовнішнього ключа таблиці `VehicleImages`
--
ALTER TABLE `VehicleImages`
  ADD CONSTRAINT `vehicleimages_ibfk_1` FOREIGN KEY (`VehicleID`) REFERENCES `Vehicles` (`VehicleID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
