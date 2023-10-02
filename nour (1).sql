-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 02 oct. 2023 à 04:32
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `nour`
--

-- --------------------------------------------------------

--
-- Structure de la table `billing`
--

CREATE TABLE `billing` (
  `BillingID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `Month` varchar(15) NOT NULL,
  `Year` int(11) NOT NULL,
  `AmountPaid` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `busroutes`
--

CREATE TABLE `busroutes` (
  `RouteID` int(11) NOT NULL,
  `RouteName` varchar(100) NOT NULL,
  `Stops` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `invoices`
--

CREATE TABLE `invoices` (
  `InvoiceID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `Month` varchar(15) NOT NULL,
  `Year` int(11) NOT NULL,
  `TotalAmount` decimal(10,2) NOT NULL,
  `PaymentStatus` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `studentinformation`
--

CREATE TABLE `studentinformation` (
  `StudentID` int(11) NOT NULL,
  `MassarNumber` varchar(20) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `FatherName` varchar(50) DEFAULT NULL,
  `PlaceOfBirth` varchar(100) DEFAULT NULL,
  `SchoolLevel` varchar(50) DEFAULT NULL,
  `BoardingPoint` varchar(100) DEFAULT NULL,
  `DropOffPoint` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `studentinformation`
--

INSERT INTO `studentinformation` (`StudentID`, `MassarNumber`, `FirstName`, `LastName`, `FatherName`, `PlaceOfBirth`, `SchoolLevel`, `BoardingPoint`, `DropOffPoint`) VALUES
(1, 's131403648', 'محمد', 'قوبع', 'بوتمين', 'تارجيست', '2 Bac', 'رياض السلام', 'ايبيريا');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`UserID`, `username`, `email`, `password`, `reg_date`) VALUES
(10, 'admin', 'mohamedkobaa0@gmail.com', '$2y$10$ne5VwA68F5TsJsoSvFzG3e1oJSGp/0BypDy7TZVLhLENC9IgPIXrC', '2023-10-01 17:44:17');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `billing`
--
ALTER TABLE `billing`
  ADD PRIMARY KEY (`BillingID`),
  ADD UNIQUE KEY `StudentID` (`StudentID`,`Month`,`Year`);

--
-- Index pour la table `busroutes`
--
ALTER TABLE `busroutes`
  ADD PRIMARY KEY (`RouteID`);

--
-- Index pour la table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`InvoiceID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Index pour la table `studentinformation`
--
ALTER TABLE `studentinformation`
  ADD PRIMARY KEY (`StudentID`),
  ADD UNIQUE KEY `MassarNumber` (`MassarNumber`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `billing`
--
ALTER TABLE `billing`
  MODIFY `BillingID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `busroutes`
--
ALTER TABLE `busroutes`
  MODIFY `RouteID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `InvoiceID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `studentinformation`
--
ALTER TABLE `studentinformation`
  MODIFY `StudentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `billing`
--
ALTER TABLE `billing`
  ADD CONSTRAINT `billing_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `studentinformation` (`StudentID`);

--
-- Contraintes pour la table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `studentinformation` (`StudentID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
