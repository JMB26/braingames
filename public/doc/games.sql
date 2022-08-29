-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 29 août 2022 à 15:14
-- Version du serveur : 8.0.27
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `games`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `nom`) VALUES
(1, 'Puzzle'),
(2, 'Jeu 3D Bois');

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `txt` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `idgame_id` int NOT NULL,
  `iduser_id` int NOT NULL,
  `idwriter_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F9E962A3B8B8B6B` (`idgame_id`),
  KEY `IDX_5F9E962A786A81FB` (`iduser_id`),
  KEY `IDX_5F9E962A65DC4F43` (`idwriter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `games`
--

DROP TABLE IF EXISTS `games`;
CREATE TABLE IF NOT EXISTS `games` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mark` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isbn` int NOT NULL,
  `age` int NOT NULL,
  `idcat_id` int NOT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `txt` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` int NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FF232B31821004EF` (`idcat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `id` int NOT NULL AUTO_INCREMENT,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idswap_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C53D045FDFA2989C` (`idswap_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reset_password_request`
--

DROP TABLE IF EXISTS `reset_password_request`;
CREATE TABLE IF NOT EXISTS `reset_password_request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_7CE748AA76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `shape`
--

DROP TABLE IF EXISTS `shape`;
CREATE TABLE IF NOT EXISTS `shape` (
  `id` int NOT NULL AUTO_INCREMENT,
  `etat` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `shape`
--

INSERT INTO `shape` (`id`, `etat`) VALUES
(1, 'Neuf'),
(2, 'Trés bon'),
(3, 'Bon');

-- --------------------------------------------------------

--
-- Structure de la table `swap`
--

DROP TABLE IF EXISTS `swap`;
CREATE TABLE IF NOT EXISTS `swap` (
  `id` int NOT NULL AUTO_INCREMENT,
  `swapuser` tinyint(1) NOT NULL,
  `swapbuyer` tinyint(1) NOT NULL,
  `idshape_id` int NOT NULL,
  `idgameuser_id` int NOT NULL,
  `idgamebuyer_id` int NOT NULL,
  `iduser_id` int NOT NULL,
  `idbuyer_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_25938561FAF324B6` (`idshape_id`),
  KEY `IDX_25938561786A81FB` (`iduser_id`),
  KEY `IDX_25938561C6A01F2F` (`idbuyer_id`),
  KEY `IDX_25938561E5A9E971` (`idgameuser_id`),
  KEY `IDX_25938561CB50B679` (`idgamebuyer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pseudo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `typname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` int NOT NULL,
  `news` tinyint(1) NOT NULL,
  `token` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `pseudo`, `type`, `typname`, `note`, `news`, `token`, `date`, `status`) VALUES
(1, 'user@brain-games.fr', '[]', '$2y$13$MMtVvcGv3XgNwaC5fjtLAuZ1a7woeRhrqAPPT5Erkts35KU/rTC2q', '', '', '', 0, 0, '', '0000-00-00 00:00:00', 0),
(2, 'admin@brain-games.fr', '[\"ROLE_ADMIN\"]', '$2y$13$33s8BrjxAPwoauNhsg8zEOTow9tcgaW4rur2K605CBe388LLucEBW', '', '', '', 0, 0, '', '0000-00-00 00:00:00', 0);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `FK_5F9E962A3B8B8B6B` FOREIGN KEY (`idgame_id`) REFERENCES `games` (`id`),
  ADD CONSTRAINT `FK_5F9E962A65DC4F43` FOREIGN KEY (`idwriter_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_5F9E962A786A81FB` FOREIGN KEY (`iduser_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `FK_FF232B31821004EF` FOREIGN KEY (`idcat_id`) REFERENCES `categories` (`id`);

--
-- Contraintes pour la table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `FK_C53D045FDFA2989C` FOREIGN KEY (`idswap_id`) REFERENCES `swap` (`id`);

--
-- Contraintes pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `swap`
--
ALTER TABLE `swap`
  ADD CONSTRAINT `FK_25938561786A81FB` FOREIGN KEY (`iduser_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_25938561C6A01F2F` FOREIGN KEY (`idbuyer_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_25938561CB50B679` FOREIGN KEY (`idgamebuyer_id`) REFERENCES `games` (`id`),
  ADD CONSTRAINT `FK_25938561E5A9E971` FOREIGN KEY (`idgameuser_id`) REFERENCES `games` (`id`),
  ADD CONSTRAINT `FK_25938561FAF324B6` FOREIGN KEY (`idshape_id`) REFERENCES `shape` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
