-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 21 jan. 2021 à 09:36
-- Version du serveur :  10.4.16-MariaDB
-- Version de PHP : 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `test`
--

-- --------------------------------------------------------

--
-- Structure de la table `brand`
--

CREATE TABLE `brand` (
  `id` int(11) NOT NULL,
  `brand_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `brand`
--

INSERT INTO `brand` (`id`, `brand_name`) VALUES
(1, ''),
(2, 'Acer'),
(3, 'Asus'),
(9, 'Compaq'),
(6, 'Dell'),
(10, 'Fujitsu'),
(5, 'HP'),
(4, 'Lenovo'),
(7, 'MSI'),
(8, 'Toshiba');

-- --------------------------------------------------------

--
-- Structure de la table `cardex`
--

CREATE TABLE `cardex` (
  `id` int(11) NOT NULL,
  `affichage` varchar(3) NOT NULL DEFAULT 'oui',
  `civilite` varchar(20) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` int(11) NOT NULL,
  `items_category` int(11) NOT NULL,
  `brand_category` int(11) NOT NULL,
  `password` varchar(100) NOT NULL,
  `join_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `cardex`
--

INSERT INTO `cardex` (`id`, `affichage`, `civilite`, `last_name`, `first_name`, `email`, `phone`, `items_category`, `brand_category`, `password`, `join_date`) VALUES
(29, 'oui', 'Mr', 'Patfoort', 'Romuald', 'romuald.patfoort@gmail.com', 603124194, 1, 6, 'password', '2021-01-12');

-- --------------------------------------------------------

--
-- Structure de la table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `items`
--

INSERT INTO `items` (`id`, `name`) VALUES
(3, 'écran'),
(5, 'imprimante'),
(1, 'pc portable'),
(4, 'tablette'),
(2, 'tour');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_name` (`brand_name`);

--
-- Index pour la table `cardex`
--
ALTER TABLE `cardex`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_category` (`brand_category`),
  ADD KEY `items_category` (`items_category`);

--
-- Index pour la table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `brand`
--
ALTER TABLE `brand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `cardex`
--
ALTER TABLE `cardex`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `cardex`
--
ALTER TABLE `cardex`
  ADD CONSTRAINT `cardex_ibfk_1` FOREIGN KEY (`brand_category`) REFERENCES `brand` (`id`),
  ADD CONSTRAINT `cardex_ibfk_2` FOREIGN KEY (`items_category`) REFERENCES `items` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
