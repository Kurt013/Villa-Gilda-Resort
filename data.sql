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
(1, '', '', 'admin', '$2y$10$i4AJYPnZtV6AceEys9gik.jdG/TK/JPf/h3tsFlgNdo2Lz0K2yfCu', 'kurtvincentalmodovar65@gmail.com', 'admin', ''),
(49, 'Lucky', 'Casubha', 'lucky123', '$2y$10$GaKUafnRXb5H/.HgnvLROep.R1UOCEfOxlugLy9EeGj47ZhlN0FCG', 'casubhalucky1@gmail.com', 'staff', ''),
(50, 'Jeruh', 'Fornal', 'jeruh123', '$2y$10$1Qm8aJJan1YZKnDL/84MB.ySJaJj1eo2nOR5om1GKA8h1PeoCRbfK', 'jeruh@gmail.com', 'staff', ''),

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
