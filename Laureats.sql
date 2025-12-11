-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Dec 11, 2025 at 07:43 PM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `LaureatsINPT`
--

-- --------------------------------------------------------

--
-- Table structure for table `ENTREPRISE`
--

CREATE TABLE `ENTREPRISE` (
  `id_entreprise` int NOT NULL,
  `nom` varchar(150) NOT NULL,
  `secteur` varchar(100) DEFAULT NULL,
  `pays` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `LAUREAT`
--

CREATE TABLE `LAUREAT` (
  `id_laureat` int NOT NULL,
  `numero_inpt` varchar(50) DEFAULT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `genre` varchar(10) DEFAULT NULL,
  `annee_diplome` varchar(9) DEFAULT NULL,
  `filiere` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `PARCOURS_PROFESSIONNEL`
--

CREATE TABLE `PARCOURS_PROFESSIONNEL` (
  `id_parcours` int NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date DEFAULT NULL,
  `localisation` varchar(255) DEFAULT NULL,
  `id_laureat` int NOT NULL,
  `id_entreprise` int NOT NULL,
  `id_poste` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `POSTE`
--

CREATE TABLE `POSTE` (
  `id_poste` int NOT NULL,
  `titre` varchar(150) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ENTREPRISE`
--
ALTER TABLE `ENTREPRISE`
  ADD PRIMARY KEY (`id_entreprise`);

--
-- Indexes for table `LAUREAT`
--
ALTER TABLE `LAUREAT`
  ADD PRIMARY KEY (`id_laureat`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `numero_inpt_unique` (`numero_inpt`);

--
-- Indexes for table `PARCOURS_PROFESSIONNEL`
--
ALTER TABLE `PARCOURS_PROFESSIONNEL`
  ADD PRIMARY KEY (`id_parcours`),
  ADD KEY `id_laureat` (`id_laureat`),
  ADD KEY `id_entreprise` (`id_entreprise`),
  ADD KEY `id_poste` (`id_poste`);

--
-- Indexes for table `POSTE`
--
ALTER TABLE `POSTE`
  ADD PRIMARY KEY (`id_poste`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ENTREPRISE`
--
ALTER TABLE `ENTREPRISE`
  MODIFY `id_entreprise` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `LAUREAT`
--
ALTER TABLE `LAUREAT`
  MODIFY `id_laureat` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `PARCOURS_PROFESSIONNEL`
--
ALTER TABLE `PARCOURS_PROFESSIONNEL`
  MODIFY `id_parcours` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `POSTE`
--
ALTER TABLE `POSTE`
  MODIFY `id_poste` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `PARCOURS_PROFESSIONNEL`
--
ALTER TABLE `PARCOURS_PROFESSIONNEL`
  ADD CONSTRAINT `parcours_professionnel_ibfk_1` FOREIGN KEY (`id_laureat`) REFERENCES `LAUREAT` (`id_laureat`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `parcours_professionnel_ibfk_2` FOREIGN KEY (`id_entreprise`) REFERENCES `ENTREPRISE` (`id_entreprise`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `parcours_professionnel_ibfk_3` FOREIGN KEY (`id_poste`) REFERENCES `POSTE` (`id_poste`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
