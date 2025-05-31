-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 01 juin 2025 à 00:18
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `sportiva`
--

-- --------------------------------------------------------

--
-- Structure de la table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `coach_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('present','absent','late') NOT NULL,
  `nbattendance` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `page_id`, `sender_id`, `receiver_id`, `message`, `sent_at`) VALUES
(0, 1, 27, 27, 'hi ', '2025-05-16 04:22:04');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `user_id` int(11) NOT NULL,
  `age` int(11) DEFAULT NULL CHECK (`age` >= 5),
  `niveau` enum('niveau1','niveau2','niveau3','niveau4','niveau5') DEFAULT NULL,
  `total_attendances` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`user_id`, `age`, `niveau`, `total_attendances`) VALUES
(31, 19, 'niveau3', 0),
(33, 20, 'niveau5', 0),
(41, 25, '', 0),
(42, 20, 'niveau2', 0),
(50, 22, 'niveau5', 0),
(53, 25, NULL, 0),
(58, 26, 'niveau1', 0);

-- --------------------------------------------------------

--
-- Structure de la table `client_sessions`
--

CREATE TABLE `client_sessions` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `coach_id` int(11) NOT NULL,
  `session_date` datetime NOT NULL DEFAULT current_timestamp(),
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `client_sessions`
--

INSERT INTO `client_sessions` (`id`, `client_id`, `coach_id`, `session_date`, `notes`) VALUES
(26, 42, 47, '2025-05-19 11:41:44', ''),
(27, 31, 47, '2025-05-19 11:41:48', ''),
(28, 33, 47, '2025-05-19 11:41:50', ''),
(29, 42, 32, '2025-05-21 21:01:38', ''),
(30, 31, 32, '2025-05-21 21:01:39', ''),
(31, 33, 32, '2025-05-21 21:01:40', ''),
(32, 50, 32, '2025-05-21 21:01:42', ''),
(33, 50, 32, '2025-05-21 21:01:42', ''),
(34, 50, 32, '2025-05-21 21:01:42', ''),
(35, 50, 32, '2025-05-21 21:01:43', ''),
(36, 50, 32, '2025-05-21 22:10:34', ''),
(37, 58, 59, '2025-05-25 12:22:53', ''),
(38, 58, 59, '2025-05-25 12:23:01', ''),
(39, 58, 59, '2025-05-25 12:23:07', '');

-- --------------------------------------------------------

--
-- Structure de la table `coaches`
--

CREATE TABLE `coaches` (
  `user_id` int(11) NOT NULL,
  `specialite` varchar(255) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL CHECK (`experience` >= 0),
  `matricule` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `coaches`
--

INSERT INTO `coaches` (`user_id`, `specialite`, `experience`, `matricule`) VALUES
(32, '00', 5, '123456'),
(38, 'musculation', 2016, '444444'),
(43, 'tennis', 2020, '123456'),
(45, 'tennis', 2022, '123456'),
(47, 'tennis', 2023, '787878');

-- --------------------------------------------------------

--
-- Structure de la table `competitions`
--

CREATE TABLE `competitions` (
  `id` int(11) NOT NULL,
  `coach_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sport_type` varchar(100) NOT NULL,
  `min_niveau_required` varchar(100) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `competitions`
--

INSERT INTO `competitions` (`id`, `coach_id`, `name`, `date`, `location`, `created_at`, `sport_type`, `min_niveau_required`) VALUES
(7, 38, 'musc', '2025-05-30', 'annaba', '2025-05-18 23:07:19', 'musculation', 'niveau3'),
(8, 47, 'old', '2025-08-04', 'annaba', '2025-05-19 10:43:23', 'tennis', 'niveau5'),
(10, 32, 'ali', '2025-05-22', 'annaba', '2025-05-21 20:22:14', 'boxe', 'niveau3');

-- --------------------------------------------------------

--
-- Structure de la table `competition_registrations`
--

CREATE TABLE `competition_registrations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `competition_id` int(11) NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `competition_registrations`
--

INSERT INTO `competition_registrations` (`id`, `user_id`, `competition_id`, `registered_at`, `status`) VALUES
(10, 50, 7, '2025-05-21 20:11:43', 'pending'),
(11, 50, 10, '2025-05-21 22:29:14', 'pending');

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

CREATE TABLE `contact` (
  `contact_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `date_sent` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `coach_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `location` varchar(100) NOT NULL,
  `sport_type` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `coach_id`, `date`, `time`, `location`, `sport_type`, `created_at`) VALUES
(15, 32, '2025-05-24', '03:33:00', 'qqq', 'judo', '2025-05-18 22:22:52'),
(16, 38, '2025-05-25', '15:00:00', 'annaba', 'musculation', '2025-05-18 23:06:47'),
(17, 47, '2025-06-26', '16:00:00', 'oran', 'tennis', '2025-05-19 10:42:14'),
(18, 59, '2025-02-25', '15:00:00', 'annaba', 'judo', '2025-05-25 11:24:11');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role` enum('client','coach','admin') NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `motdepasse` varchar(255) NOT NULL,
  `sport` enum('judo','tennis','boxe','athletisme','natation','musculation') NOT NULL,
  `date_inscription` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `role`, `nom`, `prenom`, `email`, `motdepasse`, `sport`, `date_inscription`) VALUES
(31, 'client', 'guemihi', 'ilyes', 'guemihii3@gmail.com', '$2y$10$VZCH67CLgnQ6slFO9HS3d.NJMv2cXZCljSI/OJvU.iSZ9sUGJiZfq', 'judo', '2025-05-18 22:09:54'),
(32, 'coach', 'smail', 'ahmad', 'ahmed4@gmail.com', '$2y$10$9lB4mCljTR66hel5zYS3IuMWqC6zNVbHTdErJOVxuaCVcvQas7eim', 'judo', '2025-05-18 22:12:57'),
(33, 'client', 'kaabar', 'mahmoud', 'mahmoud@gmail.com', '$2y$10$GIP5MT.enDaQSHiRZXxRougDg7utNGtaZt9VS4UYJ9hcLzdCEGeB2', 'tennis', '2025-05-18 22:15:29'),
(38, 'coach', 'alice', 'a', 'alice@gmail.com', '$2y$10$O16io2zC/ODZVWYp/W8fVOTSH0f0cB/Y.Yjm/3UFdcrcWjqL1Y1dm', 'musculation', '2025-05-18 22:55:57'),
(41, 'client', 'fateh', 'f', 'fateh@gmail.com', '$2y$10$V8Ed1uBZFZO20ice2PtX4OMkidBLm4jHXlxaSEznCwEzHuPg.5lSe', 'musculation', '2025-05-18 23:08:23'),
(42, 'client', 'ben', 'mohaned', 'ben@gmail.com', '$2y$10$8IUJKjomsjJ52Ya3DLgAJO0Cye6vvZ3ZJnezIADkLLw0NilQE.b5u', 'tennis', '2025-05-19 10:31:20'),
(43, 'coach', 'btr', 'mohcen', 'btr@gmail.com', '$2y$10$OUI1uR4DzTZTRTDWjCDK5eEXR6JBEJhqWgWiCIhriFjUufGmlfYlO', 'tennis', '2025-05-19 10:33:57'),
(45, 'coach', 'moh', 'seif', 'moh@gmail.com', '$2y$10$hUWOPg9pMMXoFnG55dG2DOpt8/ECzkfMYcnI1/2jeCZgLjpRkWCza', 'tennis', '2025-05-19 10:37:24'),
(47, 'coach', 'ppp', 'mounir', 'ppp@gmail.com', '$2y$10$457PsJuboVyCo0qlRDzzDesG37EOOIoJtYWUK80JE7zFPEl0Wzqvi', 'tennis', '2025-05-19 10:40:10'),
(50, 'admin', 'admin', 'admin', 'admin@gmail.com', '$2y$10$L6.7mcOlbU0zkX8Ne0IYIuy5pr51qJ05JZXE2W2OuLOcM2j4gVAO2', 'judo', '2025-05-21 19:25:18'),
(52, 'coach', 'kbir', 'mahmoud', 'kbir@gmail.com', '$2y$10$YLjBf4sO9waEZ0Vg3Ls6AOivMwIigMZlHFcEpy2rizgICtcn7F0/a', 'judo', '2025-05-21 22:36:20'),
(53, 'client', 'malaga', 'mehdi', 'mehdi@gmail.com', '$2y$10$EIiU0Wth069IOy1APvJDLOqLT4q7yCAFmfRDfK2ABC8YGELgeKDri', 'tennis', '2025-05-24 12:34:55'),
(55, 'coach', 'arsh', 'oussama', 'oussama@gmail.com', '$2y$10$XQfxrJpE0hHMW1cpve5RcOx0KTyzH5l7pLbDFMcA5STC7W7LmrN9a', 'boxe', '2025-05-24 12:56:57'),
(57, 'coach', 'byu', 'youssef', 'youssrf@gmail.com', '$2y$10$Rlp3BswSFcFxEFFrR9/WgOk51h4iNuz05U.dCoAd8E4O1aaFHz77q', 'judo', '2025-05-25 11:14:17'),
(58, 'client', 'ali', 'ali', 'ali@gmail.com', '$2y$10$Dv5dW1/LNdUJ5kiEEjjR1O0KOkh2Qr3gkWhiJUYIlvhCz6WbOnpn6', 'judo', '2025-05-25 11:18:19'),
(59, 'coach', 'walid', 'walid', 'walid@gmail.com', '$2y$10$YsRP5dIiw3Bq.c1KHfNPGuNGRZfumtZmnzwrYmD7LpQT8Yp3Xvj2C', 'judo', '2025-05-25 11:21:47');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `coach_id` (`coach_id`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`user_id`);

--
-- Index pour la table `client_sessions`
--
ALTER TABLE `client_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cs_client` (`client_id`),
  ADD KEY `fk_cs_coach` (`coach_id`);

--
-- Index pour la table `coaches`
--
ALTER TABLE `coaches`
  ADD PRIMARY KEY (`user_id`);

--
-- Index pour la table `competitions`
--
ALTER TABLE `competitions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coach_id` (`coach_id`);

--
-- Index pour la table `competition_registrations`
--
ALTER TABLE `competition_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_registration` (`user_id`,`competition_id`),
  ADD KEY `competition_id` (`competition_id`);

--
-- Index pour la table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`contact_id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `email` (`email`(768));

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coach_id` (`coach_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `client_sessions`
--
ALTER TABLE `client_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `competitions`
--
ALTER TABLE `competitions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `competition_registrations`
--
ALTER TABLE `competition_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`coach_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `client_sessions`
--
ALTER TABLE `client_sessions`
  ADD CONSTRAINT `fk_cs_client` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_cs_coach` FOREIGN KEY (`coach_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `coaches`
--
ALTER TABLE `coaches`
  ADD CONSTRAINT `coaches_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `competitions`
--
ALTER TABLE `competitions`
  ADD CONSTRAINT `competitions_ibfk_1` FOREIGN KEY (`coach_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `competition_registrations`
--
ALTER TABLE `competition_registrations`
  ADD CONSTRAINT `competition_registrations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `competition_registrations_ibfk_2` FOREIGN KEY (`competition_id`) REFERENCES `competitions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`coach_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
