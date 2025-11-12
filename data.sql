-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2024 at 07:59 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

--
-- Database: `villa gilda`
--

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
-- Password: @Admin123
(1, 'Kurt Vincent', 'Almodovar', 'kurt123', '$2a$12$x4KSJElOpGXbv509pvC6Xu/RZlTsKvqISRje7pDnu2WWPvuzCsGC2', 'kurtvincentalmodovar65@gmail.com', 'admin', ''),

-- Password: @User123
(2, 'Lucky', 'Casubha', 'lucky123', '$2a$12$tg0iXkRdsCXeS7tguRegt.OocYk4NTdNuqNEvXk1yzuEYY3M14r2a', 'casubhalucky5@gmail.com', 'staff', ''),
(3, 'Jeruh', 'Fornal', 'jeruh123', '$2a$12$tg0iXkRdsCXeS7tguRegt.OocYk4NTdNuqNEvXk1yzuEYY3M14r2a', 'jeruhasis@gmail.com', 'staff', ''),
(4, 'Lauriene Jade', 'Barroso', 'lauriene123', '$2a$12$tg0iXkRdsCXeS7tguRegt.OocYk4NTdNuqNEvXk1yzuEYY3M14r2a', 'laurienejadebarroso@gmail.com', 'staff', ''),
(5, 'Zofia Dennise', 'Juan', 'zof123', '$2a$12$tg0iXkRdsCXeS7tguRegt.OocYk4NTdNuqNEvXk1yzuEYY3M14r2a', 'zofiadennisejuan@gmail', 'staff', ''),
(6, 'Era Dane', 'Par', 'era123', '$2a$12$tg0iXkRdsCXeS7tguRegt.OocYk4NTdNuqNEvXk1yzuEYY3M14r2a', 'par.eradane@gmail.com', 'staff', ''),

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
