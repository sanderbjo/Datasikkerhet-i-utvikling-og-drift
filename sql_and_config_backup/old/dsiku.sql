-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 19, 2024 at 10:25 PM
-- Server version: 8.0.36-0ubuntu0.22.04.1
-- PHP Version: 8.1.2-1ubuntu2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dsiku`
--

-- --------------------------------------------------------

--
-- Table structure for table `anonyme_kommentarer`
--

CREATE TABLE `anonyme_kommentarer` (
  `id` int NOT NULL,
  `melding` text NOT NULL,
  `melding_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `anonyme_kommentarer`
--

INSERT INTO `anonyme_kommentarer` (`id`, `melding`, `melding_id`) VALUES
(1, 'efs', 4),
(2, 'ioj', 4),
(3, 'ok', 4),
(4, 'test igjen', 4),
(5, 'ok da', 6),
(6, 'Her kommer en kommentar', 10);

-- --------------------------------------------------------

--
-- Table structure for table `bruker`
--

CREATE TABLE `bruker` (
  `id` int NOT NULL,
  `navn` varchar(45) NOT NULL,
  `epost` varchar(45) NOT NULL,
  `passord` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `rolle_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `bruker`
--

INSERT INTO `bruker` (`id`, `navn`, `epost`, `passord`, `rolle_id`) VALUES
(1, 'Testforelser', 'test@test.com', '$2y$10$U/VfrVP..bruTNTaoKFN3upT0Pau8cfZXwjCw/ah/Ep4lhZj00qbe', 1),
(2, 'Testbruker', 'testern@test.no', '$2y$10$d.CCvEgybk3SQzbGZ.FdguiJTRM96zKuKHxFQ.MiZwq0F0cE4CzU2', 2),
(3, 'Fore Leser', 'fore@leser.no', '$2y$10$Ia2KxBA12PZqW2D7vJpulufvS3QMFH1L2KMYAI7wJ3wzf8FcDMOpO', 1),
(4, 'Fore Leser', 'fore@leser.no', '$2y$10$xjsZdsXAC4ofxy4wEAyDweEQUAx2kA3OJMvNJ2t8F1q2Eb2wdl0fG', 1),
(5, 'Stu Dent', 'student@test.no', '$2y$10$22.brPORKFgfLHlKeIl7dOCJdMrdNuqtaCXwMC6bBJnV/x2Ruw/bq', 2),
(6, 'studenten', 'student@student.no', '$2y$10$eL9pDSJNmh/TYOYwMznR7e/U.YVlDGc4Sdx5l0jBLD2bwbZ774TWC', 2),
(7, 'Test123', 'test123@test.no', '$2y$10$jHWElqs5dBSfUJgir9YZc.dPkwa5H8AwKVDZaWD7hGDCPeZnG/n62', 2),
(8, 'TomHeine', 'tomheine@test.no', '$2y$10$GgOZHf6cp5cJ8ZuNKFWRDuPQLYbNCsYcBKqnz5biLBgeIUiHBP99e', 1),
(9, 'Tobias', 'tobiasje@hiof.no', '$2y$10$YHu.tKaM1alnMPB2B/8jru2sMZbCwYz4jkEuFOCLjebZqKoRTOzSm', 2),
(10, 'Test Student', 't@t.no', '$2y$10$4GbCe6YsqXeXly8v/l2OvOE09TL/tvy.QxOqu.KVfvy1zya0/D70e', 2),
(12, 'ok', 'ok@ok.no', '$2y$10$EFSA4jU14.kZkTkJXaImneMR7tXBGIE2tUoW0mPRlhm96ybbcPa1m', 2),
(13, 'Leser', 'lese@l.ti', '$2y$10$XkBRtn9X8B2gw4/QF5SnlOmpO6P9Pf515VDRS5bUwj6l7jExe1zw.', 1),
(14, 'Testleser', 'for@leser.no', '$2y$10$OTzd0zmpYhslZ7RiDryVfOwGS/7F8AwZwsB.7fIqwiHhLb701Se/a', 1),
(15, 'Forle', 'for@le.no', '$2y$10$4fWSAtjwvhOkExCnpwgr7eaVwvIDUuH9RJoOBGJiGrpy3RhP3AWQa', 1),
(17, 'Fork', 'fo@rk.ti', '$2y$10$SuryVHlaneYTnSPtfqhhpecTSucppLTqh8Cxnx/7I74XhXUaeflbS', 1),
(18, 'Ubat', 'ub@at.tu', '$2y$10$Wr6JrllsslLKYnYv4Y3LI.ACjwYn59H6b/UM6UjTTj6LLmo/4iNhu', 1),
(21, 't', 't@t.t', '$2y$10$Tp185FHViHH/ZYRr96EHkOgC/BemHjao.5bbEODF4wV720bwGYpzS', 2),
(22, 'uiuop', 'tyuiop@ytuiopiuiopoiu.no', '$2y$10$gnxWXEY2haPrceOPOpwV9eHT9xSanMFqT8wqWOJZF.fLhm0c0mYja', 2),
(23, 'Bjornar Leser', 'bjornarleser@foreleser.no', '$2y$10$aIT8N.Ti2MkEOY07VY9k5.FkIq5KKYVx8YXPJsQY.73jkUimia.sS', 1),
(24, 'Bjornar Student', 'bjornarstudent@student.no', '$2y$10$TvdbF55ZSHCIRt3IV2rGb.DedNcIDyt6R2iexH67SM.EQYpyatM9.', 2),
(25, 'teststudent7', 'test@test.tv', '$2y$10$5CmbMosqTJyV1UZu5nqYjO36OsMXAmyyV3z1IYZdf1Bsf8NfLNahG', 2),
(26, 'Test test', 'test@testing.com', '$2y$10$AT5DVkIY0t0qNXzHyUZ9oeYgtno.i5.hB8m8LkxtfwwlzbyYgBeNO', 2),
(27, 'testforeleser', 'sindrbh@hiof.no', '$2y$10$GSYmEfWqbCAkr4FpJuY0XeVlcNJ6SKWfy8E0nZiVgfXnpq7v.Lg9.', 1),
(28, 'Bjornar', 'bjornar.guttormsen@hiof.no', '$2y$10$99Nr9pvBLwDrjtKDyQNUxeD9SxSoa1f3gaKD3SrPEfCo9urvbvg7W', 2),
(29, 'l', 'c@t.tv', '$2y$10$4UbBNTb0vf72TxCHcm8FCedrGDdDHW4NC25Rnf4tRtmKhPwH07YxW', 1),
(30, 'stud', 'stud@test.li', '$2y$10$sZMbzrue0qAFdzz7eaMRx.Gv.iulMdNoxr.c5V6N3vwbXxynCzKdK', 2),
(31, 'lese student', 'lese@student.no', '$2y$10$F74mh7IYyM8X.ltS1yIw4.weskPM1.pqiOCuBuVbLX8nu1D9vpVb6', 2),
(32, 'F L', 'f@l.no', '$2y$10$hz2VIj/Q/Jc9wPbOjO7TEu3TJBKYeuZoiqGF9TuGGXuNicgZtzZbW', 1),
(33, 'test', 'test@gmail.com', 'abcdef', 2),
(34, 'test', 'test@gmail.com', 'abcdef', 2);

-- --------------------------------------------------------

--
-- Table structure for table `emne`
--

CREATE TABLE `emne` (
  `emnekode` varchar(12) NOT NULL,
  `navn` varchar(45) NOT NULL,
  `pin` int NOT NULL,
  `bruker_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `emne`
--

INSERT INTO `emne` (`emnekode`, `navn`, `pin`, `bruker_id`) VALUES
('12emen3', '123emne', 2983, 17),
('BJORN24', 'BjornarEmne', 9876, 23),
('cemne', 'cemne', 9999, 29),
('emne123', 'emne321', 1113, 27),
('okUi23', 'OKhehe', 1234, 18),
('SIST408', 'SisteEmne', 1234, 32),
('test', 'testemne', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `melding`
--

CREATE TABLE `melding` (
  `id` int NOT NULL,
  `innhold` text NOT NULL,
  `bruker_id` int NOT NULL,
  `emne_emnekode` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `melding`
--

INSERT INTO `melding` (`id`, `innhold`, `bruker_id`, `emne_emnekode`) VALUES
(1, 'Dette er en test melding', 2, 'test'),
(2, 'test', 1, 'test'),
(3, 'test', 1, 'test'),
(4, 'Emnet ditt suger', 24, 'BJORN24'),
(5, '123', 9, '12emen3'),
(6, 'dritt', 28, 'BJORN24'),
(7, 'tester', 30, 'cemne'),
(8, 'test', 26, '12emen3'),
(9, 'dette er et flott emne!', 31, 'okUi23'),
(10, 'Dette er en drittemne', 31, 'SIST408'),
(11, 'OK GREIT NOK', 31, 'SIST408'),
(12, '123', 9, '12emen3');

-- --------------------------------------------------------

--
-- Table structure for table `rapporterte_meldinger`
--

CREATE TABLE `rapporterte_meldinger` (
  `id` int NOT NULL,
  `melding_id` int DEFAULT NULL,
  `rapportert_tidspunkt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `begrunnelse` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `rapporterte_meldinger`
--

INSERT INTO `rapporterte_meldinger` (`id`, `melding_id`, `rapportert_tidspunkt`, `begrunnelse`) VALUES
(1, 10, '2024-02-19 15:01:32', 'Dette er ikke greit');

-- --------------------------------------------------------

--
-- Table structure for table `rolle`
--

CREATE TABLE `rolle` (
  `navn` varchar(45) NOT NULL,
  `id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `rolle`
--

INSERT INTO `rolle` (`navn`, `id`) VALUES
('Foreleser', 1),
('Student', 2);

-- --------------------------------------------------------

--
-- Table structure for table `svar`
--

CREATE TABLE `svar` (
  `id` int NOT NULL,
  `innhold` text NOT NULL,
  `melding_id` int NOT NULL,
  `bruker_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `svar`
--

INSERT INTO `svar` (`id`, `innhold`, `melding_id`, `bruker_id`) VALUES
(1, 'takk for test melding', 1, 1),
(2, 'test', 2, 1),
(3, 'tester', 3, 1),
(4, 'Nei det gjør det ikke', 4, 23),
(7, 'tester', 7, 29),
(10, 'Nei det er det ikke', 10, 32);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anonyme_kommentarer`
--
ALTER TABLE `anonyme_kommentarer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `melding_id` (`melding_id`);

--
-- Indexes for table `bruker`
--
ALTER TABLE `bruker`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_bruker_rolle1_idx` (`rolle_id`);

--
-- Indexes for table `emne`
--
ALTER TABLE `emne`
  ADD PRIMARY KEY (`emnekode`),
  ADD KEY `fk_emne_bruker1_idx` (`bruker_id`);

--
-- Indexes for table `melding`
--
ALTER TABLE `melding`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_melding_bruker1_idx` (`bruker_id`),
  ADD KEY `fk_melding_emne1_idx` (`emne_emnekode`);

--
-- Indexes for table `rapporterte_meldinger`
--
ALTER TABLE `rapporterte_meldinger`
  ADD PRIMARY KEY (`id`),
  ADD KEY `melding_id` (`melding_id`);

--
-- Indexes for table `rolle`
--
ALTER TABLE `rolle`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `svar`
--
ALTER TABLE `svar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_svar_melding1_idx` (`melding_id`),
  ADD KEY `fk_svar_bruker1_idx` (`bruker_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anonyme_kommentarer`
--
ALTER TABLE `anonyme_kommentarer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bruker`
--
ALTER TABLE `bruker`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `melding`
--
ALTER TABLE `melding`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `rapporterte_meldinger`
--
ALTER TABLE `rapporterte_meldinger`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `svar`
--
ALTER TABLE `svar`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anonyme_kommentarer`
--
ALTER TABLE `anonyme_kommentarer`
  ADD CONSTRAINT `anonyme_kommentarer_ibfk_1` FOREIGN KEY (`melding_id`) REFERENCES `melding` (`id`);

--
-- Constraints for table `bruker`
--
ALTER TABLE `bruker`
  ADD CONSTRAINT `fk_bruker_rolle1` FOREIGN KEY (`rolle_id`) REFERENCES `rolle` (`id`);

--
-- Constraints for table `emne`
--
ALTER TABLE `emne`
  ADD CONSTRAINT `fk_emne_bruker1` FOREIGN KEY (`bruker_id`) REFERENCES `bruker` (`id`);

--
-- Constraints for table `melding`
--
ALTER TABLE `melding`
  ADD CONSTRAINT `fk_melding_bruker1` FOREIGN KEY (`bruker_id`) REFERENCES `bruker` (`id`),
  ADD CONSTRAINT `fk_melding_emne1` FOREIGN KEY (`emne_emnekode`) REFERENCES `emne` (`emnekode`);

--
-- Constraints for table `rapporterte_meldinger`
--
ALTER TABLE `rapporterte_meldinger`
  ADD CONSTRAINT `rapporterte_meldinger_ibfk_1` FOREIGN KEY (`melding_id`) REFERENCES `melding` (`id`);

--
-- Constraints for table `svar`
--
ALTER TABLE `svar`
  ADD CONSTRAINT `fk_svar_bruker1` FOREIGN KEY (`bruker_id`) REFERENCES `bruker` (`id`),
  ADD CONSTRAINT `fk_svar_melding1` FOREIGN KEY (`id`) REFERENCES `melding` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
