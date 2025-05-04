-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 23, 2025 at 09:29 AM
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
-- Database: `eventifydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendees`
--

CREATE TABLE `attendees` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendees`
--

INSERT INTO `attendees` (`id`, `name`, `email`, `password`) VALUES
(1, 'atif1', 'atif1@gmail.com', '$2y$10$p6RxJKBXXY/Sq.Ti4/PIUOrXRtsEP5nvhoSS0aHc4xKaJlwm.GrX6'),
(2, 'at', 'at@gmail.com', '$2y$10$km10hDkO01JMaQSIfgBdPe87ztmPCwp0zorqz8mQXbY8mv9W6WlNa'),
(3, 'atif1', 'ati@gmial.ocm', '$2y$10$2nYETEMfc3eii71J/W0znee77Zv68ULfqG1ZKumGxFrmbMpiegI56'),
(7, 'a', 'a@gmail.com', '$2y$10$4BolaWxRrfJ6UwUzx3I4Ouf2JQLD5smvdzIneEkVxDWfwhX.FUvGS'),
(8, 'a1', 'a1@gmail.com', '$2y$10$4MOY70/0s73xx4l.REZVmuclYSlp1osIAnIkRpaZ.5j5FQfUd.Ppu');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `organizer_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `capacity` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `date`, `location`, `description`, `organizer_id`, `image_path`, `capacity`) VALUES
(4, 'azan', '2025-01-17', 'soon', 'this is event', 4, '../uploads/IMG-20241207-WA0049.jpg', 0),
(5, 'event1', '2025-01-06', 'vs code11', 'this is testing event 111', 1, '../uploads/WhatsApp Image 2024-12-14 at 11.58.52_547b6799.jpg', 0),
(11, 'event 003', '2025-01-08', 'event 3', 'this is event', 1, '', 0),
(13, 'Wajouu', '2025-01-24', 'wajouu uchalaviii', 'this is meetupff', 1, '../uploads/pic.jpg', 0),
(19, 'event11', '2025-01-16', 'ad', 'this is event', 1, '', 0),
(21, 'tamattr', '2025-02-08', 'in the bucket', 'this is tomatto event', 1, '../uploads/logoo.jpg', 0),
(24, 'this is event 0001', '2025-01-25', 'no where', 'in the middle of no wherer', 1, '', 10),
(25, 'GOOD', '2025-01-19', 'SOOON ', 'this is event', 1, '../uploads/WhatsApp Image 2024-12-14 at 11.18.52_a516e2e0.jpg', 4),
(26, 'Kainat', '2025-06-21', 'Heart', 'Well come ', 1, '../uploads/atu-logo-png-transparent.png', 3),
(28, 'January 2025', '2025-01-22', 'IN valley', 'this is event ', 1, '../uploads/Screenshot_30-11-2024_182214_www.geeksforgeeks.org.jpeg', 5);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `attendee_id` int(11) NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `event_id`, `attendee_id`, `message`, `created_at`, `is_read`, `user_id`) VALUES
(23, 24, 2, 'Attendee at has RSVP\'d: maybe to the event \"this is event 0001\"', '2025-01-09 15:29:37', 0, NULL),
(27, 4, 3, 'hi atu', '2025-01-09 15:47:21', 0, NULL),
(37, 25, 1, 'this is notifcaiton', '2025-01-10 09:54:23', 0, NULL),
(38, 24, 8, 'Attendee a1 has RSVP\'d: attend to the event \"this is event 0001\"', '2025-01-12 12:10:56', 0, NULL),
(39, 25, 8, 'Attendee a1 has RSVP\'d: maybe to the event \"GOOD\"', '2025-01-12 12:12:04', 0, NULL),
(41, 24, 1, 'Attendee atif1 has RSVP\'d: decline to the event \"this is event 0001\"', '2025-01-21 06:19:22', 0, NULL),
(42, 19, 1, 'Attendee atif1 has RSVP\'d: decline to the event \"event11\"', '2025-01-21 06:35:47', 0, NULL),
(43, 5, 7, 'Attendee a has RSVP\'d: decline to the event \"event1\"', '2025-01-21 09:26:30', 0, NULL),
(44, 21, 7, 'hello', '2025-01-21 09:32:35', 0, NULL),
(46, 26, 7, 'Attendee a has RSVP\'d: attend to the event \"Kainat\"', '2025-01-21 10:53:30', 0, NULL),
(47, 24, 2, 'Attendee at has RSVP\'d: maybe to the event \"this is event 0001\"', '2025-01-22 15:18:26', 0, NULL),
(48, 26, 2, 'Attendee at has RSVP\'d: attend to the event \"Kainat\"', '2025-01-22 15:19:54', 0, NULL),
(52, 25, 2, 'oran awin na yar mia ki surnain  kisi hor no chan piaya aadhain )', '2025-01-23 05:27:40', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `organizers`
--

CREATE TABLE `organizers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organizers`
--

INSERT INTO `organizers` (`id`, `name`, `email`, `password`) VALUES
(1, 'atif', 'atif@gmail.com', '$2y$10$xU17qYlphwcsu.bI0okemuzkR2pNwdzzmanLDOrz63qpftArEmQlK'),
(2, 'atif2', 'atif2@gmail.com', '$2y$10$yasi8lGoTQ.Mxy0jFSQRS.TMnuWq6eRcVpaMNOgyuE2PlvIRwUPzm'),
(3, 'atif', 'atif3@gmail.com', '$2y$10$DALafvDPd7ckTbazjZf.LOpgvAzQmge9njp3qqH5h5t3iWXAmR6Yu'),
(4, 'ati', 'ati@gmail.com', '$2y$10$1FPTmf5qEuSKyflx0YHTJe32FyP/L1QvrDojEgXhBLMjcZ.NOicee'),
(5, 'AtifAwan', 'dratifawan7@gmail.com', '$2y$10$EIjPFypChY7ys435BD22TOvddd76tNGrbfC3vAtt9F.SUREbECSPy'),
(6, 'a11', 'a11@gmail.com', '$2y$10$0lxIwEdFzD24KX347rX4lOs5mb5sqCsjDuPatvoN8qMKhjIa1pgVO');

-- --------------------------------------------------------

--
-- Table structure for table `rsvps`
--

CREATE TABLE `rsvps` (
  `id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `attendee_id` int(11) DEFAULT NULL,
  `status` enum('attend','maybe','decline') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rsvps`
--

INSERT INTO `rsvps` (`id`, `event_id`, `attendee_id`, `status`) VALUES
(3, 5, 1, 'decline'),
(13, 13, 7, 'attend'),
(14, 24, 7, 'attend'),
(15, 24, 2, 'decline'),
(16, 19, 2, 'decline'),
(17, 24, 8, 'decline'),
(18, 25, 8, 'maybe'),
(19, 24, 1, 'decline'),
(20, 19, 1, 'decline'),
(21, 5, 7, 'decline'),
(22, 26, 7, 'attend'),
(23, 26, 2, 'attend');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendees`
--
ALTER TABLE `attendees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organizer_id` (`organizer_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `fk_attendee_id` (`attendee_id`);

--
-- Indexes for table `organizers`
--
ALTER TABLE `organizers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `rsvps`
--
ALTER TABLE `rsvps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `attendee_id` (`attendee_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendees`
--
ALTER TABLE `attendees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `organizers`
--
ALTER TABLE `organizers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `rsvps`
--
ALTER TABLE `rsvps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`organizer_id`) REFERENCES `organizers` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_attendee_id` FOREIGN KEY (`attendee_id`) REFERENCES `attendees` (`id`),
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

--
-- Constraints for table `rsvps`
--
ALTER TABLE `rsvps`
  ADD CONSTRAINT `rsvps_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  ADD CONSTRAINT `rsvps_ibfk_2` FOREIGN KEY (`attendee_id`) REFERENCES `attendees` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
