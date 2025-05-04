-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2025 at 06:07 AM
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
(8, 'a1', 'a1@gmail.com', '$2y$10$4MOY70/0s73xx4l.REZVmuclYSlp1osIAnIkRpaZ.5j5FQfUd.Ppu'),
(9, 'Awan', 'awan@gmail.com', '$2y$10$YBXfaGjVhNLyOMD6Opciku0lRDMqg3fqZuLTWW/yDQJjdIvTKQbva');

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
(28, 'January 2025', '2025-01-22', 'IN valley', 'this is event ', 1, '../uploads/Screenshot_30-11-2024_182214_www.geeksforgeeks.org.jpeg', 5),
(29, 'inshallah website project EMS done', '2025-01-28', 'virtual university of pakistan', 'virtual university of pakistan ( this is event managements system', 1, '../uploads/Screenshot (244).png', 12),
(30, 'Protoype ', '2025-12-12', 'At home this Event', 'YOU\"VE to attened this event at the end of hte day', 7, '../uploads/WhatsApp Image 2025-03-29 at 20.51.44_197a4200.jpg', 10),
(31, 'Atif', '2025-04-30', 'Soon valley', 'this is event', 1, '../uploads/pic.jpg', 12),
(32, 'wedding', '2025-05-05', 'Rawalpindi', 'This is wedding of my cousin Shahbaz with Saddaf shahbaz', 1, '../uploads/image6.jpg', 10),
(33, 'aqit', '2025-05-04', 'school', 'this is event', 1, '../uploads/pic.jpg', 12),
(34, 'new', '2025-05-06', 'in the middle of no where', 'the quick brown fox jumps over the lazzy dog ', 1, '../uploads/OIP.jpeg', 12);

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
(55, 31, 2, 'Attendee at has RSVP\'d: attend to the event \"Atif\"', '2025-04-29 11:13:37', 0, NULL),
(56, 31, 2, 'Attendee at has RSVP\'d: attend to the event \"Atif\"', '2025-04-30 11:31:23', 0, NULL),
(57, 32, 2, 'Attendee at has RSVP\'d: attend to the event \"wedding\"', '2025-04-30 11:47:17', 0, NULL),
(58, 31, 1, 'Attendee atif1 has RSVP\'d: attend to the event \"Atif\"', '2025-04-30 11:59:02', 0, NULL);

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
(6, 'a11', 'a11@gmail.com', '$2y$10$0lxIwEdFzD24KX347rX4lOs5mb5sqCsjDuPatvoN8qMKhjIa1pgVO'),
(7, 'Kainat', 'kainat@gmail.com', '$2y$10$MrtXsJqQ2trPpbNPrjaUzeT9RShWmV2EUc.vwmtqnCAVB/bHgzUaS');

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
(27, 31, 2, 'attend'),
(28, 32, 2, 'attend'),
(29, 31, 1, 'attend'),
(30, 30, 1, 'attend');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `organizers`
--
ALTER TABLE `organizers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `rsvps`
--
ALTER TABLE `rsvps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

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
