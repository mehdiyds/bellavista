-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
<<<<<<< HEAD
-- Généré le : mar. 03 juin 2025 à 15:04
=======
-- Généré le : mar. 03 juin 2025 à 15:41
>>>>>>> 52f7dabc35ba20efef15c934042d395af6e47b9a
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
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
<<<<<<< HEAD
=======
  `email` varchar(100) DEFAULT NULL,
>>>>>>> 52f7dabc35ba20efef15c934042d395af6e47b9a
  `telephone` varchar(20) NOT NULL,
  `adresse` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`client_id`, `nom`, `email`, `telephone`, `adresse`) VALUES
(1, 'Bernard', NULL, '0123456789', '123 Rue de Paris'),
(2, 'Petit', NULL, '0987654321', '456 Avenue des Champs'),
(3, 'Leroy', NULL, '0555666777', '789 Boulevard Central'),
(4, 'Moreau', NULL, '0333444555', '321 Rue Principale');

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
(1001, 1, '2023-05-15 18:30:00', 17.50, 17.50, 'en attente', NULL, NULL),
(1002, 2, '2023-05-15 19:15:00', 9.50, 5.00, 'en attente', NULL, NULL),
(1003, 3, '2023-05-15 20:00:00', 19.00, 19.00, 'en attente', NULL, NULL),
(1004, 4, '2023-05-15 20:45:00', 16.50, 10.00, 'en attente', NULL, NULL);

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
(1, 1001, 1, 1, 12.50),
(2, 1001, 2, 2, 2.50),
(3, 1002, 3, 1, 8.00),
(4, 1002, 4, 1, 1.50),
(5, 1003, 5, 1, 14.00),
(6, 1003, 6, 1, 5.00),
(7, 1004, 7, 1, 10.50),
(8, 1004, 8, 1, 6.00);

-- --------------------------------------------------------

--
-- Structure de la table `historique`
--

CREATE TABLE `historique` (
  `historique_id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `nom_client` varchar(100) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `adresse` text NOT NULL,
  `commande` text NOT NULL,
  `montant_total` decimal(10,2) NOT NULL,
  `montant_paye` decimal(10,2) NOT NULL,
  `reste` decimal(10,2) NOT NULL,
  `date_commande` datetime NOT NULL,
  `date_annulation` datetime DEFAULT current_timestamp(),
  `statut_avant_annulation` varchar(50) NOT NULL,
  `raison_annulation` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `livraisons`
--

CREATE TABLE `livraisons` (
  `livraison_id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `livreur_id` int(11) NOT NULL,
  `date_assignation` datetime DEFAULT current_timestamp(),
  `date_livraison` datetime DEFAULT NULL,
  `statut` enum('assignée','en cours','livrée','échouée') DEFAULT 'assignée',
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

<<<<<<< HEAD
INSERT INTO `livreurs` (`livreur_id`, `nom`, `prenom`, `telephone`, `statut`, `mdp`) VALUES
(1, 'km,n', 'nassim', '26719771', 'disponible', '123456');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

=======
>>>>>>> 52f7dabc35ba20efef15c934042d395af6e47b9a
CREATE TABLE `produits` (
  `produit_id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `categorie` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
<<<<<<< HEAD
=======

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`produit_id`, `nom`, `description`, `prix`, `categorie`) VALUES
(1, 'Pizza Margherita', NULL, 12.50, NULL),
(2, 'Coca-Cola', NULL, 2.50, NULL),
(3, 'Salade César', NULL, 8.00, NULL),
(4, 'Eau minérale', NULL, 1.50, NULL),
(5, 'Pizza 4 fromages', NULL, 14.00, NULL),
(6, 'Tiramisu', NULL, 5.00, NULL),
(7, 'Pasta Carbonara', NULL, 10.50, NULL),
(8, 'Vin rouge', NULL, 6.00, NULL);
>>>>>>> 52f7dabc35ba20efef15c934042d395af6e47b9a

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
<<<<<<< HEAD
  ADD PRIMARY KEY (`client_id`);
=======
  ADD PRIMARY KEY (`client_id`),
  ADD UNIQUE KEY `email` (`email`);
>>>>>>> 52f7dabc35ba20efef15c934042d395af6e47b9a

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
<<<<<<< HEAD
-- Index pour la table `historique`
--
ALTER TABLE `historique`
  ADD PRIMARY KEY (`historique_id`),
  ADD KEY `commande_id` (`commande_id`),
  ADD KEY `client_id` (`client_id`);

--
=======
>>>>>>> 52f7dabc35ba20efef15c934042d395af6e47b9a
-- Index pour la table `livraisons`
--
ALTER TABLE `livraisons`
  ADD PRIMARY KEY (`livraison_id`),
  ADD UNIQUE KEY `commande_id` (`commande_id`),
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
  ADD PRIMARY KEY (`produit_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
<<<<<<< HEAD
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT;
=======
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
>>>>>>> 52f7dabc35ba20efef15c934042d395af6e47b9a

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
<<<<<<< HEAD
  MODIFY `commande_id` int(11) NOT NULL AUTO_INCREMENT;
=======
  MODIFY `commande_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1005;
>>>>>>> 52f7dabc35ba20efef15c934042d395af6e47b9a

--
-- AUTO_INCREMENT pour la table `details_commandes`
--
ALTER TABLE `details_commandes`
<<<<<<< HEAD
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `historique`
--
ALTER TABLE `historique`
  MODIFY `historique_id` int(11) NOT NULL AUTO_INCREMENT;
=======
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
>>>>>>> 52f7dabc35ba20efef15c934042d395af6e47b9a

--
-- AUTO_INCREMENT pour la table `livraisons`
--
ALTER TABLE `livraisons`
  MODIFY `livraison_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `livreurs`
--
ALTER TABLE `livreurs`
<<<<<<< HEAD
  MODIFY `livreur_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
=======
  MODIFY `livreur_id` int(11) NOT NULL AUTO_INCREMENT;
>>>>>>> 52f7dabc35ba20efef15c934042d395af6e47b9a

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
<<<<<<< HEAD
  MODIFY `produit_id` int(11) NOT NULL AUTO_INCREMENT;
=======
  MODIFY `produit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
>>>>>>> 52f7dabc35ba20efef15c934042d395af6e47b9a

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
<<<<<<< HEAD
-- Contraintes pour la table `historique`
--
ALTER TABLE `historique`
  ADD CONSTRAINT `historique_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`commande_id`),
  ADD CONSTRAINT `historique_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`);

--
=======
>>>>>>> 52f7dabc35ba20efef15c934042d395af6e47b9a
-- Contraintes pour la table `livraisons`
--
ALTER TABLE `livraisons`
  ADD CONSTRAINT `livraisons_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`commande_id`),
  ADD CONSTRAINT `livraisons_ibfk_2` FOREIGN KEY (`livreur_id`) REFERENCES `livreurs` (`livreur_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
