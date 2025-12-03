-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 25 juin 2025 à 21:48
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
-- Base de données : `bellavista`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id_cat` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id_cat`, `nom`, `description`, `image`) VALUES
(41, 'fastfood', 'bniin', 'uploads/categories/pizzza.png');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `adresse` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`client_id`, `nom`, `telephone`, `adresse`) VALUES
(5, 'NASSIM CHAARABI', '95845834', 'RUE OMKALTHOUM'),
(6, 'cghjk', '7876', 'RUE OMKALTHOUM'),
(7, 'yassine ', '23444555', 'korba'),
(8, 'yassine ', '234567555', 'korba'),
(9, 'nassim', '24555577', 'korba '),
(10, 'nassim', '22333555', 'korba'),
(11, 'NASSIM CHAARABI', '23456745', 'RUE OMKALTHOUM'),
(12, 'aaaaaaaa', '234567890', 'RUE OMKALTHOUM'),
(13, 'mehdi yedes', '234567234', 'RUE OMKALTHOUM'),
(14, 'NASSIM CHAARABI', '234567234', 'RUE OMKALTHOUM'),
(15, 'NASSIM CHAARABI', '234567234', 'RUE OMKALTHOUM'),
(16, 'mtar', '234567234', 'RUE OMKALTHOUM'),
(17, 'ahmed', '234567234', 'bhar');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `commande_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `date_commande` datetime DEFAULT current_timestamp(),
  `montant_total` decimal(10,2) NOT NULL,
  `montant_paye` decimal(10,2) DEFAULT 0.00,
  `reste` decimal(10,2) GENERATED ALWAYS AS (`montant_paye` - `montant_total`) STORED,
  `statut` enum('en attente','en préparation','assignée','en livraison','livrée','annulée') DEFAULT 'en attente',
  `notes` text DEFAULT NULL,
  `commande` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`commande_id`, `client_id`, `date_commande`, `montant_total`, `montant_paye`, `statut`, `notes`, `commande`) VALUES
(1234567901, 5, '2025-06-11 18:47:10', 12.00, 20.00, 'en attente', NULL, '1 pizza'),
(1234567902, 5, '2025-06-11 19:30:31', 12.00, 12.00, 'en attente', NULL, '1 pizza'),
(1234567904, 12, '2025-06-11 19:38:33', 48.00, 48.00, 'livrée', NULL, '4 pizza'),
(1234567905, 13, '2025-06-11 20:02:32', 48.00, 48.00, 'livrée', NULL, '4 pizza'),
(1234567906, 11, '2025-06-11 20:53:09', 12.00, 12.00, 'en attente', NULL, '1 pizza'),
(1234567907, 11, '2025-06-11 20:54:11', 12.00, 12.00, 'en attente', NULL, '1 pizza'),
(1234567908, 13, '2025-06-11 21:05:22', 12.00, 12.00, 'en attente', NULL, '1 pizza'),
(1234567909, 14, '2025-06-11 21:11:50', 12.00, 12.00, 'en attente', NULL, '1 pizza'),
(1234567910, 15, '2025-06-11 21:14:43', 12.00, 12.00, 'en attente', NULL, '1 pizza'),
(1234567911, 13, '2025-06-11 21:18:17', 12.00, 12.00, 'en attente', NULL, '1 pizza'),
(1234567912, 13, '2025-06-11 21:24:43', 12.00, 12.00, 'en attente', NULL, '1 pizza'),
(1234567913, 14, '2025-06-11 21:29:02', 36.00, 36.00, 'assignée', NULL, '3 pizza'),
(1234567916, 16, '2025-06-11 21:31:11', 156.00, 156.00, 'assignée', NULL, '13 pizza'),
(1234567917, 17, '2025-06-11 21:32:01', 156.00, 156.00, 'assignée', NULL, '13 pizza');

-- --------------------------------------------------------

--
-- Structure de la table `details_commandes`
--

CREATE TABLE `details_commandes` (
  `detail_id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 1,
  `prix_unitaire` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `historique_commandes`
--

CREATE TABLE `historique_commandes` (
  `historique_id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `date_commande` datetime NOT NULL,
  `montant_total` decimal(10,2) NOT NULL,
  `montant_paye` decimal(10,2) DEFAULT 0.00,
  `reste` decimal(10,2) GENERATED ALWAYS AS (`montant_paye` - `montant_total`) STORED,
  `statut` enum('en attente','en préparation','assignée','en livraison','livrée','annulée') DEFAULT 'en attente',
  `notes` text DEFAULT NULL,
  `commande` varchar(1000) DEFAULT NULL,
  `date_archivage` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `historique_commandes`
--

INSERT INTO `historique_commandes` (`historique_id`, `commande_id`, `client_id`, `date_commande`, `montant_total`, `montant_paye`, `statut`, `notes`, `commande`, `date_archivage`) VALUES
(40, 1234567895, 6, '2025-06-07 19:19:26', 29.00, 29.00, 'en attente', NULL, '7 Espresso + 1 Cappuccino', '2025-06-07 19:43:01'),
(41, 1234567894, 5, '2025-06-05 13:09:29', 8.00, 10.00, 'livrée', NULL, '1 Espresso + 1 Cappuccino', '2025-06-07 20:55:14'),
(43, 1234567898, 9, '2025-06-10 18:27:26', 23.00, 23.00, 'livrée', NULL, '1 makloub (9.00 DT) + 1 pizza (10.00 DT) + 1 coffe (4.00 DT)', '2025-06-10 19:23:44'),
(44, 1234567897, 8, '2025-06-09 20:41:40', 27.50, 27.50, 'livrée', NULL, '', '2025-06-10 19:25:06'),
(45, 1234567896, 7, '2025-06-09 20:40:22', 27.50, 27.50, 'livrée', NULL, '', '2025-06-10 19:26:23'),
(46, 1234567899, 10, '2025-06-10 19:36:54', 13.00, 13.00, 'livrée', NULL, '1 pizza (13.00 DT)', '2025-06-11 18:41:37'),
(47, 1234567900, 11, '2025-06-10 21:07:42', 12.00, 12.00, 'livrée', NULL, '1 pizza (12.00 DT)', '2025-06-11 18:43:40'),
(48, 1234567903, 5, '2025-06-11 19:36:50', 48.00, 48.00, 'livrée', NULL, '4 pizza (12.00 DT)', '2025-06-11 20:02:50'),
(49, 1234567915, 16, '2025-06-11 21:30:05', 36.00, 36.00, 'livrée', NULL, '3 pizza (12.00 DT)', '2025-06-11 21:43:37'),
(50, 1234567914, 16, '2025-06-11 21:29:45', 36.00, 36.00, 'livrée', NULL, '3 pizza (12.00 DT)', '2025-06-11 21:43:37');

-- --------------------------------------------------------

--
-- Structure de la table `livraisons`
--

CREATE TABLE `livraisons` (
  `livraison_id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `livreur_id` int(11) NOT NULL,
  `date_assignation` datetime DEFAULT current_timestamp(),
  `statut` enum('assignée','en cours','livrée','annulée') DEFAULT 'assignée'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `livraisons`
--

INSERT INTO `livraisons` (`livraison_id`, `commande_id`, `livreur_id`, `date_assignation`, `statut`) VALUES
(19, 1234567905, 1, '2025-06-11 20:02:59', 'livrée'),
(20, 1234567904, 1, '2025-06-11 20:02:59', 'livrée'),
(21, 1234567917, 1, '2025-06-11 21:42:02', 'assignée'),
(22, 1234567916, 4, '2025-06-11 21:42:02', 'assignée'),
(25, 1234567913, 8, '2025-06-11 21:43:02', 'assignée');

-- --------------------------------------------------------

--
-- Structure de la table `livreurs`
--

CREATE TABLE `livreurs` (
  `livreur_id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `telephone` varchar(20) NOT NULL,
  `statut` enum('disponible','en livraison','indisponible') DEFAULT 'disponible',
  `mdp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `livreurs`
--

INSERT INTO `livreurs` (`livreur_id`, `nom`, `prenom`, `telephone`, `statut`, `mdp`) VALUES
(1, 'km,n', 'nassim', '26719771', 'en livraison', '123456'),
(2, 'TAHER', 'TAHAN', '23456789', 'indisponible', 'AZE'),
(3, 'MTARRR', 'ZDCQS', '3456789', 'en livraison', 'AZERTYU'),
(4, 'YEDES', 'TAHER', '96544234', 'en livraison', '123456789'),
(5, 'mtar', 'rayane', '23456788', 'en livraison', '123'),
(8, 'majdouba', 'samir', '23647876', 'en livraison', '123'),
(12, 'MAHMOUD', 'JKL', '123456', 'en livraison', 'QSDF');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `produit_id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `id_cat` int(50) DEFAULT NULL,
  `caracteristiques` varchar(200) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `date_reservation` datetime NOT NULL,
  `heure_reservation` time NOT NULL,
  `nombre_personnes` int(11) NOT NULL,
  `numero_table` int(11) DEFAULT NULL,
  `statut` enum('confirmée','en attente','annulée','terminée') DEFAULT 'en attente',
  `notes` text DEFAULT NULL,
  `images` varchar(255) DEFAULT NULL COMMENT 'Chemin vers les images associées'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `client_id`, `date_reservation`, `heure_reservation`, `nombre_personnes`, `numero_table`, `statut`, `notes`, `images`) VALUES
(1, 11, '2025-06-26 00:00:00', '21:32:00', 4, 1, 'confirmée', '', NULL),
(2, 11, '2025-06-19 00:00:00', '20:37:00', 3, 3, 'confirmée', '', NULL),
(3, 11, '2025-06-24 00:00:00', '20:50:00', 5, 1, 'confirmée', '', NULL),
(4, 11, '2025-06-11 00:00:00', '21:53:00', 4, 4, 'confirmée', '', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `semi_administrateurs`
--

CREATE TABLE `semi_administrateurs` (
  `semi_admin_id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `num_cin` varchar(20) NOT NULL,
  `adresse` text NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp(),
  `statut` enum('actif','inactif') DEFAULT 'actif',
  `mdp` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `semi_administrateurs`
--

INSERT INTO `semi_administrateurs` (`semi_admin_id`, `nom`, `prenom`, `num_cin`, `adresse`, `telephone`, `email`, `date_creation`, `statut`, `mdp`) VALUES
(1, 'ahmed', 'chaouch', '12345553', 'korba', '22333444', 'ahmed@gmail.com', '2025-06-25 20:48:39', 'actif', '33EEZZ');

-- --------------------------------------------------------

--
-- Structure de la table `tables`
--

CREATE TABLE `tables` (
  `table_id` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `capacite` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `statut` enum('disponible','occupée','réservée','maintenance') DEFAULT 'disponible',
  `caracteristiques` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tables`
--

INSERT INTO `tables` (`table_id`, `numero`, `capacite`, `description`, `image`, `statut`, `caracteristiques`) VALUES
(1, 1, 5, 'ZABOURA', 'uploads/tables/68487acca737e_137-71810.png', 'disponible', 'MOURY7A'),
(3, 3, 4, 'bonne place', 'uploads/tables/6849dae73f628_coffee.png', 'disponible', NULL),
(4, 6, 4, NULL, 'uploads/tables/6849ec809ed0d_fastfood.png', 'réservée', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_cat`),
  ADD UNIQUE KEY `nom` (`nom`),
  ADD UNIQUE KEY `unique_nom` (`nom`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`commande_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Index pour la table `details_commandes`
--
ALTER TABLE `details_commandes`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `commande_id` (`commande_id`),
  ADD KEY `produit_id` (`produit_id`);

--
-- Index pour la table `historique_commandes`
--
ALTER TABLE `historique_commandes`
  ADD PRIMARY KEY (`historique_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Index pour la table `livraisons`
--
ALTER TABLE `livraisons`
  ADD PRIMARY KEY (`livraison_id`),
  ADD KEY `commande_id` (`commande_id`),
  ADD KEY `livreur_id` (`livreur_id`);

--
-- Index pour la table `livreurs`
--
ALTER TABLE `livreurs`
  ADD PRIMARY KEY (`livreur_id`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`produit_id`),
  ADD KEY `fk` (`id_cat`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Index pour la table `semi_administrateurs`
--
ALTER TABLE `semi_administrateurs`
  ADD PRIMARY KEY (`semi_admin_id`),
  ADD UNIQUE KEY `num_cin` (`num_cin`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`table_id`),
  ADD UNIQUE KEY `numero` (`numero`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_cat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `commande_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1234567918;

--
-- AUTO_INCREMENT pour la table `details_commandes`
--
ALTER TABLE `details_commandes`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `historique_commandes`
--
ALTER TABLE `historique_commandes`
  MODIFY `historique_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT pour la table `livraisons`
--
ALTER TABLE `livraisons`
  MODIFY `livraison_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `livreurs`
--
ALTER TABLE `livreurs`
  MODIFY `livreur_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `produit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `semi_administrateurs`
--
ALTER TABLE `semi_administrateurs`
  MODIFY `semi_admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `tables`
--
ALTER TABLE `tables`
  MODIFY `table_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`);

--
-- Contraintes pour la table `details_commandes`
--
ALTER TABLE `details_commandes`
  ADD CONSTRAINT `details_commandes_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`commande_id`),
  ADD CONSTRAINT `details_commandes_ibfk_2` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`produit_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `historique_commandes`
--
ALTER TABLE `historique_commandes`
  ADD CONSTRAINT `historique_commandes_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`);

--
-- Contraintes pour la table `livraisons`
--
ALTER TABLE `livraisons`
  ADD CONSTRAINT `livraisons_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`commande_id`),
  ADD CONSTRAINT `livraisons_ibfk_2` FOREIGN KEY (`livreur_id`) REFERENCES `livreurs` (`livreur_id`);

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `fk` FOREIGN KEY (`id_cat`) REFERENCES `categories` (`id_cat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
