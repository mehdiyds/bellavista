-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 10 juin 2025 à 18:35
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
(36, 'drinks', 'fresh drinks and coffee', 'uploads/categories/6844a1b963e09_drink.png'),
(37, 'fast food', 'meuilleur fast food en tunise', 'uploads/categories/6844a1e3a2746_fastfood.png');

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
(5, 'mehdi yedes', '95845834', 'RUE OMKALTHOUM'),
(6, 'cghjk', '7876', 'RUE OMKALTHOUM'),
(7, 'yassine ', '23444555', 'korba'),
(8, 'yassine ', '234567555', 'korba'),
(9, 'nassim', '24555577', 'korba ');

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
(1234567896, 7, '2025-06-09 20:40:22', 27.50, 27.50, 'en livraison', NULL, '5 Latte + 1 Herbal Tea'),
(1234567897, 8, '2025-06-09 20:41:40', 27.50, 27.50, 'en livraison', NULL, '5 Latte + 1 Herbal Tea'),
(1234567898, 9, '2025-06-10 18:27:26', 23.00, 23.00, 'en livraison', NULL, '1 makloub + 1 pizza + 1 coffe');

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

--
-- Déchargement des données de la table `details_commandes`
--

INSERT INTO `details_commandes` (`detail_id`, `commande_id`, `produit_id`, `quantite`, `prix_unitaire`) VALUES
(1, 1234567898, 36, 1, 9.00),
(2, 1234567898, 32, 1, 10.00),
(3, 1234567898, 34, 1, 4.00);

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
(41, 1234567894, 5, '2025-06-05 13:09:29', 8.00, 10.00, 'livrée', NULL, '1 Espresso + 1 Cappuccino', '2025-06-07 20:55:14');

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
(10, 1234567897, 4, '2025-06-09 20:42:06', 'assignée'),
(11, 1234567896, 4, '2025-06-09 20:42:06', 'assignée'),
(12, 1234567898, 5, '2025-06-10 18:28:41', 'assignée');

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
(1, 'km,n', 'nassim', '26719771', 'indisponible', '123456'),
(2, 'TAHER', 'TAHAN', '23456789', 'indisponible', 'AZE'),
(3, 'MTARRR', 'ZDCQS', '3456789', 'en livraison', 'AZERTYU'),
(4, 'YEDES', 'TAHER', '96544234', 'en livraison', '123456789'),
(5, 'mtar', 'rayane', '23456788', 'en livraison', '123'),
(12, 'MAHMOUD', 'JKL', '123456', 'indisponible', 'QSDF');

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

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`produit_id`, `nom`, `description`, `prix`, `id_cat`, `caracteristiques`, `image`) VALUES
(32, 'pizza', 'delicieuse\\r\\nmeuilleur prix', 10.00, 37, 'taille: medium', 'uploads/produits/pizzza.png'),
(34, 'coffe', 'bien serreé', 4.00, 36, 'bien serré', 'uploads/produits/137-71810.png'),
(35, 'coffe', 'bien serreé', 4.00, 36, 'bien serré', 'uploads/produits/137-71810.png'),
(36, 'makloub', 'bniiin', 9.00, 37, 'taille:grand', 'uploads/produits/makloub.png');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `nombre_personnes` int(11) NOT NULL,
  `date_reservation` date NOT NULL,
  `heure_reservation` time NOT NULL,
  `type_table` varchar(50) NOT NULL,
  `demandes_speciales` text DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp(),
  `statut` enum('confirmée','annulée','terminée') DEFAULT 'confirmée'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  ADD PRIMARY KEY (`reservation_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_cat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `commande_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1234567899;

--
-- AUTO_INCREMENT pour la table `details_commandes`
--
ALTER TABLE `details_commandes`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `historique_commandes`
--
ALTER TABLE `historique_commandes`
  MODIFY `historique_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT pour la table `livraisons`
--
ALTER TABLE `livraisons`
  MODIFY `livraison_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `livreurs`
--
ALTER TABLE `livreurs`
  MODIFY `livreur_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `produit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `details_commandes_ibfk_2` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`produit_id`);

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
