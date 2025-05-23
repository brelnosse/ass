-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour ass
CREATE DATABASE IF NOT EXISTS `ass` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `ass`;

-- Listage de la structure de table ass. archive
CREATE TABLE IF NOT EXISTS `archive` (
  `id` int NOT NULL AUTO_INCREMENT,
  `num_reg` varchar(100) NOT NULL DEFAULT '0',
  `client` varchar(100) DEFAULT NULL,
  `employe` varchar(100) DEFAULT NULL,
  `lib_sinistre` varchar(100) DEFAULT NULL,
  `ref_sinistre` varchar(100) DEFAULT NULL,
  `date_reg` date DEFAULT NULL,
  `date_ajout` date DEFAULT '0000-00-00',
  `montant` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table ass.archive : ~1 rows (environ)
INSERT INTO `archive` (`id`, `num_reg`, `client`, `employe`, `lib_sinistre`, `ref_sinistre`, `date_reg`, `date_ajout`, `montant`) VALUES
	(15, 'REG-20250410-5075', 'JOHN doe', 'NOSSE KENNE', 'un sinistre', 'SIN-20250410-6711', '2025-04-11', '2025-04-10', 15000);

-- Listage de la structure de table ass. archives_dossiers
CREATE TABLE IF NOT EXISTS `archives_dossiers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reference` varchar(50) NOT NULL,
  `nom_assure` varchar(100) NOT NULL,
  `chemin_dossier` varchar(255) NOT NULL,
  `date_creation` datetime NOT NULL,
  `derniere_modification` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reference` (`reference`),
  KEY `idx_reference` (`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table ass.archives_dossiers : ~0 rows (environ)

-- Listage de la structure de table ass. contrat
CREATE TABLE IF NOT EXISTS `contrat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ref` varchar(50) DEFAULT NULL,
  `client_ref` varchar(50) DEFAULT NULL,
  `libelle` varchar(50) DEFAULT NULL,
  `bdate` date DEFAULT NULL,
  `edate` date DEFAULT NULL,
  `ass` varchar(50) DEFAULT NULL,
  `modpass` varchar(50) DEFAULT NULL,
  `typeCont` varchar(50) DEFAULT NULL,
  `natCont` varchar(50) DEFAULT NULL,
  `montant` float DEFAULT NULL,
  `devise` varchar(50) DEFAULT NULL,
  `objCont` text,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `client_ref` (`client_ref`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table ass.contrat : ~0 rows (environ)

-- Listage de la structure de table ass. demandes_assurance
CREATE TABLE IF NOT EXISTS `demandes_assurance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reference` varchar(20) NOT NULL,
  `type_assurance` enum('Auto','Habitation','Santé','Vie','Professionnelle') NOT NULL,
  `statut` enum('En attente','En cours de traitement','Acceptée','Refusée') DEFAULT 'En attente',
  `nom_client` varchar(100) NOT NULL,
  `prenom_client` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `date_naissance` date NOT NULL,
  `formule` varchar(50) NOT NULL,
  `date_soumission` date NOT NULL,
  `date_effet_souhaitee` date NOT NULL,
  `commentaires` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reference` (`reference`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table ass.demandes_assurance : ~1 rows (environ)
INSERT INTO `demandes_assurance` (`id`, `reference`, `type_assurance`, `statut`, `nom_client`, `prenom_client`, `email`, `telephone`, `date_naissance`, `formule`, `date_soumission`, `date_effet_souhaitee`, `commentaires`, `created_at`) VALUES
	(13, 'DEM-20250410-2965', 'Vie', 'En attente', 'JOHN', 'doe', 'john@doe.com', '650194960', '2007-10-20', 'Premium', '2025-04-10', '2025-04-11', 'bonjour je suis un commentaire supplementaire', '2025-04-10 10:11:52');

-- Listage de la structure de table ass. details_auto
CREATE TABLE IF NOT EXISTS `details_auto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `demande_id` int NOT NULL,
  `marque_vehicule` varchar(100) NOT NULL,
  `modele_vehicule` varchar(100) NOT NULL,
  `annee_vehicule` int NOT NULL,
  `immatriculation` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `demande_id` (`demande_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table ass.details_auto : ~0 rows (environ)

-- Listage de la structure de table ass. details_habitation
CREATE TABLE IF NOT EXISTS `details_habitation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `demande_id` int NOT NULL,
  `type_logement` varchar(50) NOT NULL,
  `superficie` int NOT NULL,
  `adresse_bien` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `demande_id` (`demande_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table ass.details_habitation : ~1 rows (environ)
INSERT INTO `details_habitation` (`id`, `demande_id`, `type_logement`, `superficie`, `adresse_bien`) VALUES
	(1, 11, 'Appartement', 150, 'LITTORAL\r\nDOUALA\r\nLOGBESSOU');

-- Listage de la structure de table ass. details_sante
CREATE TABLE IF NOT EXISTS `details_sante` (
  `id` int NOT NULL AUTO_INCREMENT,
  `demande_id` int NOT NULL,
  `situation_familiale` varchar(50) NOT NULL,
  `nombre_beneficiaires` int NOT NULL,
  `profession` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `demande_id` (`demande_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table ass.details_sante : ~1 rows (environ)
INSERT INTO `details_sante` (`id`, `demande_id`, `situation_familiale`, `nombre_beneficiaires`, `profession`) VALUES
	(1, 9, 'Marié(e)', 15, 'docteuf');

-- Listage de la structure de table ass. dossiers_archive
CREATE TABLE IF NOT EXISTS `dossiers_archive` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reference` varchar(191) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `date_creation` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reference` (`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table ass.dossiers_archive : ~0 rows (environ)

-- Listage de la structure de table ass. employe
CREATE TABLE IF NOT EXISTS `employe` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` bigint NOT NULL,
  `poste` varchar(50) NOT NULL,
  `bdate` date NOT NULL,
  `photo` varchar(255) NOT NULL,
  `auth_key` varchar(20) NOT NULL,
  `mdp` varchar(150) DEFAULT NULL,
  `added_date` date DEFAULT NULL,
  `received_email` int DEFAULT NULL,
  `status` varchar(12) DEFAULT NULL,
  `status_compte` enum('active','unactive') DEFAULT 'active',
  `blocked_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table ass.employe : ~8 rows (environ)
INSERT INTO `employe` (`id`, `nom`, `prenom`, `email`, `phone`, `poste`, `bdate`, `photo`, `auth_key`, `mdp`, `added_date`, `received_email`, `status`, `status_compte`, `blocked_date`) VALUES
	(1, 'NOSSE KENNE', 'Brel', 'brelnosse2@gmail.com', 676144352, 'rasd', '2000-12-20', 'ressources/upload/1742565681Capture d’écran 2025-02-06 185255.png', 'dgeV3e', '$2y$10$OWWBt4Asqd.5hfJukWV9gu5ogpAIiusDw33ls4FrJ90zVH8.jj4zi', '2025-03-21', 1, 'disconnected', 'active', NULL),
	(87, 'john', 'doe', 'nossebrel@yahoo.fr', 673809889, 'agts', '2006-12-30', 'ressources/upload/1742655608Capture d’écran 2025-01-21 161410.png', 'GxJ3F2', '$2y$10$o8lvZcnobeuA6hT875dOO.KpXw4WbmBHDVUi/sNMl9SqQRu2hTOCC', '2025-03-22', 1, 'disconnected', 'unactive', '2025-04-09 12:56:54'),
	(89, 'NGUEF ', 'bens', 'dongmowilfried550@gmail.com', 681700595, 'agts', '2003-01-31', 'ressources/upload/1743521834un étudiant sombre et stylisé travaillant sur une table et qui réfléchit.png', 'x851de', NULL, '2025-04-01', 1, 'disconnected', 'active', NULL),
	(92, 'wit', 'witselz', 'witselwil58@gmail.com', 681700532, 'agts', '2006-01-31', 'ressources/upload/1743530428Toque de chef avec une fourchette et un couteau en croix, sans background.png', 'fmZBDz', '$2y$10$QbSITEsg92I/Lb8jok/TzO3zBQBsHLo5G5ut3xl728.oWzixE5Aka', '2025-04-01', 1, 'disconnected', 'active', NULL),
	(93, 'DONGMO ', 'Ronaldo', 'jasondongmo2003@gmail.com', 681036787, 'agts', '2003-09-10', 'ressources/upload/1743579018plat.jpg', 'rZ7WEl', NULL, '2025-04-02', 1, 'disconnected', 'active', NULL),
	(94, 'DONGMO ', 'JASON', 'wilfriedwoze@gmail.com', 658470515, 'agts', '2003-09-10', 'ressources/upload/1743586766i1.png', 'A8vh6u', '$2y$10$OZpd1i3a8N5hZLoDOdY0Cedofo4wrebWHWIwzrTE/pGlvHsL.Gdrm', '2025-04-02', 1, 'disconnected', 'active', NULL),
	(95, 'NOSSE KENNE', 'Brel', 'brelnosse@gmail.com', 676144352, 'rasd', '1985-01-11', 'ressources/upload/1744186493Capture d’écran 2025-03-29 162658.png', 'mIi8GB', '$2y$10$7FblJgUZxhHrhVKvaWolke79DhCYK20.QlZ2deZsFR/9FEPLbRvy2', '2025-04-09', 1, 'disconnected', 'active', NULL),
	(96, 'bonjour', 'john', 'nossebrel@gmail.com', 650194960, 'rasd', '2005-01-19', 'ressources/upload/1744187312tictactoe.png', '9wr7Oi', '$2y$10$gMPJpB77Dd94XGDiWKDj7.Wo.TJHiSri5BuhME3uV90jXU4oJ2jcm', '2025-04-09', 1, 'disconnected', 'active', NULL);

-- Listage de la structure de table ass. historique_demandes
CREATE TABLE IF NOT EXISTS `historique_demandes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `demande_id` int NOT NULL,
  `action` varchar(50) NOT NULL,
  `ancien_statut` varchar(50) DEFAULT NULL,
  `nouveau_statut` varchar(50) DEFAULT NULL,
  `commentaire` text,
  `date_modification` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `utilisateur_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `demande_id` (`demande_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table ass.historique_demandes : ~0 rows (environ)

-- Listage de la structure de table ass. journal
CREATE TABLE IF NOT EXISTS `journal` (
  `id_journal` int NOT NULL AUTO_INCREMENT,
  `id_emp` int DEFAULT NULL,
  `period` datetime DEFAULT NULL,
  `label` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_journal`)
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table ass.journal : ~39 rows (environ)
INSERT INTO `journal` (`id_journal`, `id_emp`, `period`, `label`) VALUES
	(116, 87, '2025-03-23 08:45:37', 'Définition du mot de passe'),
	(117, 1, '2025-03-23 09:00:47', 'Compte activé'),
	(118, 87, '2025-03-23 09:10:24', 'Définition du mot de passe'),
	(119, 1, '2025-03-23 09:15:03', 'Tentative d\'envoie de code par email échouer !'),
	(120, 1, '2025-03-23 09:15:20', 'Tentative d\'envoie de code par email échouer !'),
	(121, 1, '2025-03-23 09:37:56', 'Tentative d\'envoie de code par email échouer !'),
	(122, 1, '2025-03-23 09:38:03', 'Tentative d\'envoie de code par email échouer !'),
	(123, 1, '2025-03-23 09:38:17', 'Tentative d\'envoie de code par email échouer !'),
	(124, 1, '2025-03-23 09:38:40', 'Tentative d\'envoie de code par email reussi !!!'),
	(125, 1, '2025-03-23 09:40:39', 'Définition du mot de passe'),
	(126, 1, '2025-03-24 14:14:18', 'Compte désactivé'),
	(127, 1, '2025-03-24 14:14:20', 'Compte activé'),
	(128, 88, '2025-03-24 17:38:01', 'Tentative d\'envoie de code par échouer !'),
	(129, 88, '2025-03-24 17:44:07', 'Tentative d\'envoie de code par email reussi !!!'),
	(130, 1, '2025-04-01 10:19:47', 'Compte désactivé'),
	(131, 1, '2025-04-01 10:19:51', 'Compte activé'),
	(132, 89, '2025-04-01 16:37:22', 'Tentative d\'envoie de code par email reussi !!!'),
	(133, 90, '2025-04-01 16:51:56', 'Tentative d\'envoie de code par email reussi !!!'),
	(134, 1, '2025-04-01 18:46:23', 'Compte désactivé'),
	(135, 91, '2025-04-01 18:55:59', 'Tentative d\'envoie de code par email reussi !!!'),
	(136, 92, '2025-04-01 19:00:33', 'Tentative d\'envoie de code par email reussi !!!'),
	(137, 92, '2025-04-01 19:02:11', 'Définition du mot de passe'),
	(138, 1, '2025-04-01 19:27:54', 'Compte activé'),
	(139, 93, '2025-04-02 08:30:25', 'Tentative d\'envoie de code par email reussi !!!'),
	(140, 93, '2025-04-02 08:35:58', 'Compte désactivé'),
	(141, 93, '2025-04-02 08:36:03', 'Compte activé'),
	(142, 94, '2025-04-02 10:39:32', 'Tentative d\'envoie de code par email reussi !!!'),
	(143, 94, '2025-04-02 10:41:50', 'Définition du mot de passe'),
	(144, 1, '2025-04-07 18:35:31', 'Compte désactivé'),
	(145, 1, '2025-04-07 18:38:48', 'Compte activé'),
	(146, 1, '2025-04-09 09:04:09', 'Compte désactivé'),
	(147, 1, '2025-04-09 09:04:15', 'Compte activé'),
	(148, 95, '2025-04-09 09:14:59', 'Tentative d\'envoie de code par email reussi !!!'),
	(149, 95, '2025-04-09 09:24:27', 'Définition du mot de passe'),
	(150, 96, '2025-04-09 09:28:38', 'Tentative d\'envoie de code par email reussi !!!'),
	(151, 96, '2025-04-09 09:30:12', 'Définition du mot de passe'),
	(152, 1, '2025-04-09 12:44:10', 'Compte désactivé'),
	(153, 1, '2025-04-09 12:44:28', 'Compte activé'),
	(154, 87, '2025-04-09 12:56:54', 'Compte désactivé');

-- Listage de la structure de table ass. notif
CREATE TABLE IF NOT EXISTS `notif` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_contrat` int NOT NULL,
  `id_rasd` int DEFAULT NULL,
  `status` enum('1','0') DEFAULT '0',
  `date_env` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_contrat` (`id_contrat`),
  KEY `id_rasd` (`id_rasd`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table ass.notif : ~0 rows (environ)

-- Listage de la structure de table ass. password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(150) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table ass.password_resets : ~1 rows (environ)
INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`) VALUES
	(32, 'witselwil58@gmail.com', 'dd949225854970ec5181baaa007297fba9b1872720bce4b37316d1a525f44449', '2025-04-01 19:29:42');

-- Listage de la structure de table ass. pieces_jointes
CREATE TABLE IF NOT EXISTS `pieces_jointes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `demande_id` int NOT NULL,
  `nom_fichier` varchar(255) NOT NULL,
  `chemin_fichier` varchar(255) NOT NULL,
  `type_fichier` varchar(50) NOT NULL,
  `taille` int NOT NULL,
  `date_upload` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `demande_id` (`demande_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table ass.pieces_jointes : ~4 rows (environ)
INSERT INTO `pieces_jointes` (`id`, `demande_id`, `nom_fichier`, `chemin_fichier`, `type_fichier`, `taille`, `date_upload`) VALUES
	(4, 10, 'Capture d’écran 2025-01-21 161410.png', 'C:\\laragon\\www\\assc1\\assurance\\uploads\\67f54a991c37d_Capture d’écran 2025-01-21 161410.png', 'image/png', 40070, '2025-04-08 16:11:05'),
	(5, 11, 'Capture d’écran 2025-02-10 080618.png', 'C:\\laragon\\www\\assc1\\assurance\\uploads\\67f5521b9ec75_Capture d’écran 2025-02-10 080618.png', 'image/png', 274349, '2025-04-08 16:43:07'),
	(6, 12, 'Capture d’écran 2025-01-21 161410.png', 'C:\\laragon\\www\\assc1\\assurance\\uploads\\67f779d897fbf_Capture d’écran 2025-01-21 161410.png', 'image/png', 40070, '2025-04-10 07:57:12'),
	(7, 13, 'Capture d’écran 2025-01-21 161410.png', 'C:\\laragon\\www\\assc1\\assurance\\uploads\\67f799688fa73_Capture d’écran 2025-01-21 161410.png', 'image/png', 40070, '2025-04-10 10:11:52');

-- Listage de la structure de table ass. quittances
CREATE TABLE IF NOT EXISTS `quittances` (
  `id` int NOT NULL AUTO_INCREMENT,
  `num_reglement` varchar(50) NOT NULL,
  `date_reglement` date NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `sinistre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_ajout` datetime NOT NULL,
  `ajouter_par` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sinistre_reference` (`sinistre`(250))
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table ass.quittances : ~0 rows (environ)

-- Listage de la structure de table ass. sinistres
CREATE TABLE IF NOT EXISTS `sinistres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `contrat_id` int DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `libelle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `garantie_souscrite` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nature_sinistre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date_declaration` date NOT NULL,
  `date_validation` date NOT NULL,
  `montant_expertise` decimal(50,0) NOT NULL DEFAULT '0',
  `montant_indemnise` decimal(50,0) NOT NULL DEFAULT '0',
  `motif` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `contrat_id` (`contrat_id`),
  CONSTRAINT `fk_sinistre_contrat` FOREIGN KEY (`contrat_id`) REFERENCES `contrat` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table ass.sinistres : ~1 rows (environ)
INSERT INTO `sinistres` (`id`, `contrat_id`, `reference`, `libelle`, `garantie_souscrite`, `nature_sinistre`, `date_declaration`, `date_validation`, `montant_expertise`, `montant_indemnise`, `motif`, `date_creation`) VALUES
	(16, NULL, 'SIN-20250410-6711', 'un sinistre', 'Tous risques', 'Matériel', '2025-04-10', '2025-04-12', 650000, 650000, 'accident de travail', '2025-04-10 07:59:34');

-- Listage de la structure de table ass. users
CREATE TABLE IF NOT EXISTS `users` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Role` enum('admin','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table ass.users : ~2 rows (environ)
INSERT INTO `users` (`Id`, `Username`, `Password`, `Role`) VALUES
	(1, 'admin', '*01A6717B58FF5C7EAFFF6CB7C96F7428EA65FE4C', 'admin'),
	(2, 'user1', '*0D22657BD7E16A953E5DEF4EC9E5933C4931755C', 'user');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
