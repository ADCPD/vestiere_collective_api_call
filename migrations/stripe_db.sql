-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : mysql:3306
-- Généré le : mar. 30 mars 2021 à 22:32
-- Version du serveur :  5.7.32
-- Version de PHP : 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `stripe_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `data` json DEFAULT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `history`
--

INSERT INTO `history` (`id`, `data`, `created_at`) VALUES
(1, '[\"[]\"]', '2021-03-31'),
(2, '[\"{\\\"items\\\":[{\\\"id\\\":1,\\\"priceAmount\\\":6741.6770699999997,\\\"priceCurrency\\\":6741.6770699999997},{\\\"id\\\":6,\\\"priceAmount\\\":8699.5275000000001,\\\"priceCurrency\\\":8699.5275000000001}],\\\"transaction_status\\\":\\\"unpaid\\\"}\"]', '2021-03-31'),
(3, '[\"{\\\"items\\\":[{\\\"id\\\":1,\\\"priceAmount\\\":6741.6770699999997,\\\"priceCurrency\\\":6741.6770699999997},{\\\"id\\\":6,\\\"priceAmount\\\":8699.5275000000001,\\\"priceCurrency\\\":8699.5275000000001}],\\\"transaction_status\\\":\\\"unpaid\\\"}\"]', '2021-03-31');

-- --------------------------------------------------------

--
-- Structure de la table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_currency` double NOT NULL,
  `price_amount` double NOT NULL,
  `seller_reference_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `item`
--

INSERT INTO `item` (`id`, `name`, `price_currency`, `price_amount`, `seller_reference_id`) VALUES
(1, 'Accusage', 6741.67707, 6741.67707, 7),
(2, 'Opportech', 7957.99453, 7957.99453, 8),
(3, 'Cofine', 7126.81868, 7126.81868, 9),
(4, 'Senmao', 2942.26552, 2942.26552, 9),
(5, 'Turnling', 538.36696, 538.36696, 9),
(6, 'Genmex', 8699.5275, 8699.5275, 7),
(7, 'Phormula', 3190.71354, 3190.71354, 8);

-- --------------------------------------------------------

--
-- Structure de la table `payout`
--

CREATE TABLE `payout` (
  `id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `currency` double NOT NULL,
  `seller_reference_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `seller`
--

CREATE TABLE `seller` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `seller`
--

INSERT INTO `seller` (`id`, `name`) VALUES
(7, 'Miboo'),
(8, 'Eidel'),
(9, 'Skinder');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1F1B251E43ED5055` (`seller_reference_id`);

--
-- Index pour la table `payout`
--
ALTER TABLE `payout`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_4E2EA90243ED5055` (`seller_reference_id`);

--
-- Index pour la table `seller`
--
ALTER TABLE `seller`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `payout`
--
ALTER TABLE `payout`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `seller`
--
ALTER TABLE `seller`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `FK_1F1B251E43ED5055` FOREIGN KEY (`seller_reference_id`) REFERENCES `seller` (`id`);

--
-- Contraintes pour la table `payout`
--
ALTER TABLE `payout`
  ADD CONSTRAINT `FK_4E2EA90243ED5055` FOREIGN KEY (`seller_reference_id`) REFERENCES `seller` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
