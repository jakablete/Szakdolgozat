-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2024 at 03:04 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `basketball_stat`
--

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `game_id` int(11) NOT NULL,
  `game_date` date NOT NULL,
  `home_team_id` int(11) NOT NULL,
  `away_team_id` int(11) NOT NULL,
  `home_team_score` int(11) DEFAULT 0,
  `away_team_score` int(11) DEFAULT 0,
  `home_team_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `away_team_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`game_id`, `game_date`, `home_team_id`, `away_team_id`, `home_team_score`, `away_team_score`, `home_team_name`, `away_team_name`) VALUES
(713, '2024-04-25', 18, 19, 30, 28, 'Szekszárd', 'Bonyhád'),
(714, '2024-04-25', 18, 20, 66, 26, 'Szekszárd', 'Zomba'),
(715, '2024-04-25', 20, 19, 21, 62, 'Zomba', 'Bonyhád'),
(716, '2024-04-25', 18, 20, 28, 34, 'Szekszárd', 'Zomba'),
(717, '2024-04-25', 18, 19, 74, 115, 'Szekszárd', 'Bonyhád'),
(723, '2024-04-26', 18, 20, 11, 14, 'Szekszárd', 'Zomba'),
(724, '2024-04-26', 18, 19, 120, 119, 'Szekszárd', 'Bonyhád'),
(725, '2024-04-26', 18, 20, 57, 36, 'Szekszárd', 'Zomba');

-- --------------------------------------------------------

--
-- Table structure for table `game_player_stats`
--

CREATE TABLE `game_player_stats` (
  `stat_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `two_points` int(11) NOT NULL DEFAULT 0,
  `miss_two` int(11) NOT NULL DEFAULT 0,
  `three_points` int(11) NOT NULL DEFAULT 0,
  `miss_three` int(11) NOT NULL DEFAULT 0,
  `free_throw` int(11) NOT NULL DEFAULT 0,
  `miss_free_throw` int(11) NOT NULL DEFAULT 0,
  `assists` int(11) NOT NULL DEFAULT 0,
  `rebounds` int(11) NOT NULL DEFAULT 0,
  `steals` int(11) NOT NULL DEFAULT 0,
  `turnover` int(11) NOT NULL DEFAULT 0,
  `faults` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `game_player_stats`
--

INSERT INTO `game_player_stats` (`stat_id`, `game_id`, `team_id`, `player_id`, `two_points`, `miss_two`, `three_points`, `miss_three`, `free_throw`, `miss_free_throw`, `assists`, `rebounds`, `steals`, `turnover`, `faults`) VALUES
(738, 713, 18, 44, 8, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(739, 713, 18, 45, 0, 0, 10, 10, 0, 0, 0, 0, 0, 0, 0),
(740, 713, 18, 46, 0, 0, 0, 0, 10, 10, 0, 0, 0, 0, 0),
(741, 713, 18, 47, 0, 0, 0, 0, 0, 0, 30, 0, 0, 0, 0),
(742, 713, 18, 48, 0, 0, 0, 0, 0, 0, 0, 36, 0, 0, 0),
(743, 713, 18, 49, 0, 0, 0, 0, 0, 0, 0, 0, 24, 0, 0),
(873, 714, 18, 49, 0, 0, 0, 0, 0, 0, 0, 0, 20, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `player_id` int(11) NOT NULL,
  `player_name` varchar(255) NOT NULL,
  `jersey_number` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`player_id`, `player_name`, `jersey_number`, `team_id`, `active`) VALUES
(44, 'Sprint Elek', 1, 18, 0),
(45, 'Fut László', 2, 18, 0),
(46, 'Nagy Roland', 3, 18, 0),
(47, 'Horváth Gábor', 4, 18, 0),
(48, 'Horvát Zsolt', 5, 18, 0),
(49, 'Nagy Ákos', 6, 18, 0),
(50, 'Balog Sándor', 7, 18, 0),
(51, 'Balogh Csaba', 8, 18, 0),
(52, 'Semmi Áron', 9, 18, 0),
(53, 'Éles Kornél', 10, 18, 0),
(54, 'Kiss Máté', 1, 19, 0),
(55, 'Nagy Botond', 2, 19, 0),
(56, 'Horváth Pál', 3, 19, 0),
(57, 'Beke Éliás', 4, 19, 0),
(58, 'Kovács István', 5, 19, 0),
(59, 'Éves Bérlet', 6, 19, 0),
(60, 'Ingyen Pizza', 7, 19, 0),
(61, 'Tíz Perc', 8, 19, 0),
(62, 'Fut Ákos', 9, 19, 0),
(63, 'Laki Olivér', 10, 19, 0),
(64, 'Kosár Csaba', 1, 20, 0),
(65, 'Kiss Roland', 2, 20, 0),
(66, 'Fut Kos', 3, 20, 0),
(67, 'Hall Ás', 4, 20, 0),
(68, 'Lab Rador', 5, 20, 0);

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `team_id` int(11) NOT NULL,
  `team_name` varchar(255) NOT NULL,
  `head_coach` varchar(255) NOT NULL,
  `assistant_coach` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`team_id`, `team_name`, `head_coach`, `assistant_coach`) VALUES
(18, 'Szekszárd', 'Kiss István', 'Hvver László'),
(19, 'Bonyhád', 'Rács Ákos', 'Pityu'),
(20, 'Zomba', 'Éljen Péter', 'Hajrá Áron');

-- --------------------------------------------------------

--
-- Table structure for table `user_form`
--

CREATE TABLE `user_form` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL DEFAULT 'user',
  `uname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_form`
--

INSERT INTO `user_form` (`id`, `name`, `password`, `user_type`, `uname`) VALUES
(41, 'c', '4a8a08f09d37b73795649038408b5f33', 'user', 'c'),
(42, 'a', '0cc175b9c0f1b6a831c399e269772661', 'admin', 'a'),
(43, 'Levi', '6a73901588db3d2eac37156006ceb546', 'user', 'Levi'),
(44, 'Levike', '6a73901588db3d2eac37156006ceb546', 'admin', 'Levike');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`game_id`),
  ADD KEY `fk_home_team` (`home_team_id`),
  ADD KEY `fk_away_team` (`away_team_id`);

--
-- Indexes for table `game_player_stats`
--
ALTER TABLE `game_player_stats`
  ADD PRIMARY KEY (`stat_id`),
  ADD KEY `fk_game` (`game_id`),
  ADD KEY `fk_player` (`player_id`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`player_id`),
  ADD KEY `fk_players_teams` (`team_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`team_id`);

--
-- Indexes for table `user_form`
--
ALTER TABLE `user_form`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `game_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=726;

--
-- AUTO_INCREMENT for table `game_player_stats`
--
ALTER TABLE `game_player_stats`
  MODIFY `stat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=874;

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `player_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user_form`
--
ALTER TABLE `user_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `fk_away_team` FOREIGN KEY (`away_team_id`) REFERENCES `teams` (`team_id`),
  ADD CONSTRAINT `fk_home_team` FOREIGN KEY (`home_team_id`) REFERENCES `teams` (`team_id`);

--
-- Constraints for table `game_player_stats`
--
ALTER TABLE `game_player_stats`
  ADD CONSTRAINT `fk_game` FOREIGN KEY (`game_id`) REFERENCES `games` (`game_id`),
  ADD CONSTRAINT `fk_player` FOREIGN KEY (`player_id`) REFERENCES `players` (`player_id`);

--
-- Constraints for table `players`
--
ALTER TABLE `players`
  ADD CONSTRAINT `fk_players_teams` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
