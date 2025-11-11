-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2024 at 07:59 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE bookings (
  id int(11) NOT NULL,
  booking_date date DEFAULT NULL,
  time_slot varchar(50) DEFAULT NULL,
  firstName varchar(50) DEFAULT NULL,
  lastName varchar(50) DEFAULT NULL,
  email varchar(255) DEFAULT NULL,
  contactNo varchar(11) DEFAULT NULL,
  amount decimal(10,2) DEFAULT NULL,
  included varchar(50) DEFAULT NULL,
  deposit_amount decimal(10,2) DEFAULT NULL,
  balance_amount decimal(10,2) DEFAULT NULL,
  status varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO bookings (id, booking_date, time_slot, firstName, lastName, email, contactNo, amount, included, deposit_amount, balance_amount, status) VALUES
(90, '2024-05-09', '8am - 5pm', 'nelia', 'casubha', 'casjai@hajda', '121212', 8250.00, 'LPG gas and Stove', NULL, NULL, 'fully paid'),
(92, '2024-05-02', '8am - 5pm', 'era', 'dane', 'caca@caca', '121212', 8000.00, 'N/A', NULL, NULL, 'fully paid'),
(94, '2024-05-11', '8am - 5pm', 'iner', 'casubha', 'caca@caca', '123', 8000.00, 'N/A', NULL, NULL, 'fully paid'),
(98, '2024-05-14', '2pm - 10pm', 'iner', 'casubha', 'caca@caca', '123', 8000.00, 'N/A', NULL, NULL, 'fully paid'),
(100, '2024-05-13', '12nn - 8pm', 'nelia', 'casubha', 'casjai@hajda', '100', 8250.00, 'LPG gas and Stove', 1000.00, 7250.00, 'fully paid'),
(112, '2024-05-27', '12nn - 8pm', 'kim', 'casubha', 'casjai@hajda', '321', 8250.00, 'LPG gas and Stove', 1000.00, 7250.00, 'pending'),
(113, '2024-05-29', '12nn - 8pm', 'Lucky', 'Casubha', 'casubhalucky5@gmail.com', '12345', 8250.00, 'LPG gas and Stove', 1000.00, 7250.00, 'fully paid'),
(120, '2024-06-02', 'overnight', 'Lucky', 'Casubha', 'casubhalucky5@gmail.com', '111', 9250.00, 'LPG gas and Stove', 1000.00, 8250.00, 'fully paid'),
(125, '2024-06-03', 'overnight', 'jade', 'nofuente', 'dafaf@adad', '121212', 9250.00, 'LPG gas and Stove', 1000.00, 8250.00, 'fully paid'),
(143, '2024-06-04', 'overnight', 'jade', 'nofuente', 'dafaf@adad', '121345', 9250.00, 'LPG gas and Stove', 1000.00, 8250.00, 'pending'),
(145, '2024-06-06', 'overnight', 'Casubha', '121', 'casubhalucky5@gmail.com', '12', 9300.00, 'LPG gas and Stove', 1000.00, 8300.00, 'fully paid'),
(146, '2024-06-07', 'overnight', 'Casubha', '121', 'casubhalucky5@gmail.com', '12', 9300.00, 'LPG gas and Stove', 1000.00, 8300.00, 'fully paid'),
(149, '2024-06-09', '22 hours', 'nelia', 'casubha', 'casjai@hajda', '122', 10300.00, 'LPG gas and Stove', 1000.00, 9300.00, 'fully paid'),
(154, '2024-06-12', 'overnight', 'nelia', 'casubha', 'casjai@hajda', '123', 9300.00, 'LPG gas and Stove', 1000.00, 8300.00, 'fully paid'),
(162, '2024-06-17', '22 hours', 'Lucky', 'Casubha', 'casubhalucky5@gmail.com', '09817776517', 10300.00, 'LPG gas and Stove', 1000.00, 9300.00, 'fully paid'),
(165, '2024-06-23', '12nn - 8pm', 'Kurt', 'Almodovar', 'almodovarkurt64@gmail.com', '09128740126', 8300.00, 'LPG gas and Stove', 1000.00, 7300.00, 'fully paid'),
(167, '2024-07-19', '12nn - 8pm', 'Isaac', 'Fernandez', 'casubhalucky5@gmail.com', '09671598572', 8300.00, 'LPG gas and Stove', 1000.00, 7300.00, 'deposited'),
(169, '2024-07-16', '12nn - 8pm', 'Princess', 'Mendoza', 'casubhalucky5@gmail.com', '09778965123', 8000.00, 'N/A', 1000.00, 7000.00, 'deposited'),
(170, '2024-07-08', 'overnight', 'Princess', 'Mendoza', 'casubhalucky5@gmail.com', '09778965123', 9300.00, 'LPG gas and Stove', 1000.00, 8300.00, 'fully paid'),
(175, '2024-07-11', '2pm - 10pm', 'Zofia', 'Juan', 'casubhalucky5@gmail.com', '09769856187', 8000.00, 'N/A', 1000.00, 7000.00, 'fully paid'),
(177, '2024-07-06', '8am - 5pm', 'Jeruh', 'Fonal', 'jeruh@gmail.com', '09817776517', 8300.00, 'LPG gas and Stove', 1000.00, 7300.00, 'fully paid'),
(178, '2024-07-17', 'overnight', 'Jade', 'Barroso', 'laurienejadebarroso@gmail.com', '09817776517', 9300.00, 'LPG gas and Stove', 1000.00, 8300.00, 'deposited'),
(180, '2024-07-18', '8am - 5pm', 'Lucky', 'Casubha', 'casubhalucky5@gmail.com', '0981777651', 8000.00, 'N/A', 1000.00, 7000.00, 'pending'),
(181, '2024-07-22', 'overnight', 'Lucky', 'Casubha', 'casubhalucky5@gmail.com', '0981777651', 9300.00, 'LPG gas and Stove', 1000.00, 8300.00, 'fully paid'),
(184, '2024-07-15', '12nn - 8pm', 'Jade', 'Barroso', 'laurienejadebarroso@gmail.com', '981777651', 8300.00, 'LPG gas and Stove', 1000.00, 7300.00, 'pending'),
(185, '2024-07-23', '8am - 5pm', 'Jade', 'Barroso', 'laurienejadebarroso@gmail.com', '981777651', 8300.00, 'LPG gas and Stove', 1000.00, 7300.00, 'pending'),
(186, '2024-07-31', 'overnight', 'Jade', 'Barroso', 'laurienejadebarroso@gmail.com', '981777651', 9300.00, 'LPG gas and Stove', 1000.00, 8300.00, 'pending');


CREATE TABLE forget_password (
  id int(11) NOT NULL,
  email varchar(200) NOT NULL,
  temp_key varchar(200) NOT NULL,
  created timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


CREATE TABLE `user accounts` (
  `ID` int(11) NOT NULL,
  `First Name` varchar(60) NOT NULL,
  `Last Name` varchar(60) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(60) NOT NULL,
  `email` varchar(255) NOT NULL,
  `Role` varchar(50) NOT NULL,
  `temp_key` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `user accounts` (`ID`, `First Name`, `Last Name`, `Username`, `Password`, `email`, `Role`, `temp_key`) VALUES
(1, '', '', 'admin', '$2y$10$i4AJYPnZtV6AceEys9gik.jdG/TK/JPf/h3tsFlgNdo2Lz0K2yfCu', '', 'admin', ''),
(14, 'Celine', 'Almodovar', 'CelineAlmodovar01', '$2y$10$JlcclYgD2igtXQ7uCyRGiOvvSh6jk/YsdfRshjMMp2qRo1Odr8xhu', 'casubhalucky5@gmail.com', 'admin', ''),
(49, 'Lucky', 'Casubha', 'lucky123', '$2y$10$GaKUafnRXb5H/.HgnvLROep.R1UOCEfOxlugLy9EeGj47ZhlN0FCG', 'casubhalucky1@gmail.com', 'staff', ''),
(50, 'Jeruh', 'Fornal', 'jeruh123', '$2y$10$1Qm8aJJan1YZKnDL/84MB.ySJaJj1eo2nOR5om1GKA8h1PeoCRbfK', 'jeruh@gmail.com', 'staff', ''),
(51, 'Goyie', 'Hipolito', 'goyie_01', '$2y$10$QlzSMMUWZAN7t8rBcZBw.eOR9IOt9kFX7dalxwNyMBdq9acjGS2H6', 'goyiehipolito@gmail.com', 'staff', '');

-- Modify the primary keys
ALTER TABLE `user accounts`
  ADD PRIMARY KEY (`ID`);

-- Update the auto-increment value for user accounts
ALTER TABLE `user accounts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

-- Assuming there's a 'bookings' table, adjust its primary key and auto-increment
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;


ALTER TABLE `user accounts`
  MODIFY COLUMN temp_key VARCHAR(200) NULL;

ALTER TABLE forget_password
  MODIFY COLUMN id INT(11) NOT NULL AUTO_INCREMENT;



COMMIT;
