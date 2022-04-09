-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 09 avr. 2022 à 11:12
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `sortie`
--

-- --------------------------------------------------------

--
-- Structure de la table `campus`
--

DROP TABLE IF EXISTS `campus`;
CREATE TABLE IF NOT EXISTS `campus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_9D0968116C6E55B5` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `campus`
--

INSERT INTO `campus` (`id`, `nom`) VALUES
(17, 'autem'),
(21, 'cupiditate'),
(16, 'ea'),
(19, 'eum'),
(15, 'fugiat'),
(18, 'perspiciatis'),
(20, 'ullam');

-- --------------------------------------------------------

--
-- Structure de la table `etat`
--

DROP TABLE IF EXISTS `etat`;
CREATE TABLE IF NOT EXISTS `etat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_55CAF762A4D60759` (`libelle`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `etat`
--

INSERT INTO `etat` (`id`, `libelle`) VALUES
(16, 'Activité en cours'),
(18, 'Annulée'),
(15, 'Clôturée'),
(13, 'Créée'),
(19, 'historisee'),
(14, 'Ouverte'),
(17, 'Passée');

-- --------------------------------------------------------

--
-- Structure de la table `lieu`
--

DROP TABLE IF EXISTS `lieu`;
CREATE TABLE IF NOT EXISTS `lieu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ville_id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2F577D59A73F0036` (`ville_id`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `lieu`
--

INSERT INTO `lieu` (`id`, `ville_id`, `nom`, `rue`, `latitude`, `longitude`) VALUES
(81, 48, 'Antoine', '69, rue Pauline Ferrand', 21.835031, 96.64988),
(82, 56, 'Bernard', '14, place de Maillet', -80.554377, 144.079815),
(83, 42, 'Marin', '42, place Victor Carlier', -80.887981, 157.193005),
(84, 50, 'Bodin Simon SARL', '127, impasse de Petit', -39.976327, 45.487186),
(85, 48, 'Louis', '47, rue de Delorme', 31.57695, 147.796987),
(86, 54, 'Torres SA', '65, avenue Océane Lopes', -41.014804, -43.356877),
(87, 57, 'Maillard', '21, place Leconte', -18.660057, 124.150616),
(88, 52, 'Gautier', '253, avenue Duhamel', 47.740537, 12.586232),
(89, 50, 'Imbert S.A.R.L.', '17, boulevard de Bousquet', 16.942043, -177.563088),
(90, 59, 'Thibault Mercier SARL', '80, rue Margaud Dumont', -38.452952, 167.032901),
(91, 46, 'Fontaine Faivre S.A.', '20, rue Marcel Riou', -74.400921, -90.904801),
(92, 53, 'Joseph SA', '12, rue Gilles Blondel', 72.599706, -77.505936),
(93, 50, 'Denis S.A.R.L.', '730, place Clerc', -83.49308, -73.066033),
(94, 57, 'Charpentier Baudry et Fils', 'avenue de Clement', 9.255216, -36.484517),
(95, 49, 'Diaz et Fils', '820, avenue de Guichard', -22.128143, -5.711561),
(96, 42, 'Bruneau Mathieu S.A.S.', '55, rue Perez', -86.708614, -27.323891),
(97, 43, 'Jourdan S.A.R.L.', '3, rue Thibault Poirier', -73.393968, -172.55993),
(98, 44, 'Mendes', 'impasse Hortense Collet', -25.298676, 37.421909),
(99, 53, 'Bigot SAS', '8, place Inès Benoit', -71.666272, -116.969325),
(100, 50, 'Royer Blot S.A.S.', '1, place Loiseau', -55.952523, -39.36006),
(101, 46, 'Bruneau et Fils', '1, impasse Isaac Leclerc', -83.394711, -125.257948),
(102, 59, 'Dufour Guichard S.A.', '40, impasse de Boutin', 79.066901, -107.116105),
(103, 50, 'Charpentier', '89, rue Boulanger', -59.225642, 172.86698),
(104, 59, 'Millet', '6, boulevard de Delannoy', 54.884955, -102.237214),
(105, 41, 'Etienne SARL', '21, impasse Luc Diaz', -33.412674, -130.011718),
(106, 53, 'Clement S.A.R.L.', 'rue de Mallet', -12.21803, 165.680855),
(107, 43, 'Chretien Collin et Fils', 'impasse Marthe Merle', -15.314206, 102.184278),
(108, 53, 'Briand Marin SAS', '67, place de Dupuy', -11.577775, -156.164343),
(109, 52, 'Marchand SA', '29, place Le Gall', -32.680703, 127.387977),
(110, 51, 'Riviere Collet S.A.S.', '13, rue de Legros', 9.307836, 177.631952),
(111, 59, 'Munoz Hardy S.A.S.', '35, place de Turpin', 44.444255, 91.507336),
(112, 47, 'Imbert Jacques et Fils', 'place Thibaut David', 31.406427, 110.259998),
(113, 53, 'Salmon S.A.S.', '8, avenue de Collet', 17.18169, -132.279822),
(114, 47, 'Bonneau', '58, place Anouk Pires', 72.287448, 21.000919),
(115, 43, 'Moreno', '630, boulevard Allard', -79.256961, 111.995712),
(116, 46, 'Huet', '43, rue de Gilles', 20.496053, 179.698053),
(117, 49, 'Pons Traore S.A.S.', '50, chemin Michel', 8.923328, -168.702005),
(118, 56, 'Bodin Alves SARL', '1, avenue de Mercier', 34.652068, -170.17447),
(119, 50, 'Sauvage', 'boulevard de Legendre', -52.373776, -49.664449),
(120, 41, 'Vincent', '2, rue Gérard Bertin', 72.537264, 92.427389),
(121, 52, 'ins', '92 rue de paris', 1225, 1477),
(122, 50, 'mk', '96 reu de blabla', 124, 159),
(136, 48, 'zz', 'zzzz', 55, 561645);

-- --------------------------------------------------------

--
-- Structure de la table `participant`
--

DROP TABLE IF EXISTS `participant`;
CREATE TABLE IF NOT EXISTS `participant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campus_id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mot_passe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `administrateur` tinyint(1) NOT NULL,
  `actif` tinyint(1) NOT NULL,
  `pseudo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D79F6B11E7927C74` (`email`),
  UNIQUE KEY `UNIQ_D79F6B1186CC499D` (`pseudo`),
  KEY `IDX_D79F6B11AF5D55E1` (`campus_id`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `participant`
--

INSERT INTO `participant` (`id`, `campus_id`, `email`, `mot_passe`, `nom`, `prenom`, `administrateur`, `actif`, `pseudo`, `telephone`, `image_file`) VALUES
(63, 17, 'admin@sortir.com', '$2y$13$CB7Vv1R9kuaibKcqBRlrHuODiFAlBp43.R4nV87wnzTAy71zMlReu', 'Dupont', 'Alfred', 1, 1, 'admin', '0653251668', NULL),
(64, 20, 'marine.vasseur@leger.net', '$2y$13$YYcf1dLOnZLi/zfsqbrqtOlX8u945/nyBq2zppl1RAGOjfizFFRaq', 'Moulin', 'Marthe', 0, 1, 'lacombe.susanne', '0681728777', NULL),
(65, 18, 'nicole58@wanadoo.fr', '$2y$13$mNgnpF.oTesnyZ25VBATPOxma8G8zsk5gCS3gncZvwg4aEE2XMxAa', 'Vidal', 'Benjamin', 0, 1, 'josette.humbert', '0627820198', NULL),
(66, 17, 'muller.eric@peltier.org', '$2y$13$zBdsOLK.B1xZzMMadxxAr.PfwJpqWcnExig53CjZLttvCxQOLwTFi', 'Garnier', 'Dominique', 0, 1, 'martine48', '0694158116', NULL),
(67, 19, 'edith.boulanger@petitjean.fr', '$2y$13$FOxuQjbFLsGOhZNqDoW44eexUSODTkYaarsGSb.bRV92et5prEsZW', 'Chauvet', 'Célina', 0, 1, 'alexandrie.germain', '0684166956', NULL),
(68, 21, 'ymartineau@orange.fr', '$2y$13$HeDuLlAWoO..qcKk3OPVmuvmONQ7zUI6mDqUF2BycqPK.cA0d09Ee', 'Pons', 'Renée', 0, 1, 'francois.valerie', '0682752320', NULL),
(69, 15, 'martin88@didier.com', '$2y$13$T.VU6Si6Wc8vVLqhmWofSOXYMAwOGB4EeYsoGPreHlUj76P96TLwq', 'Le Gall', 'François', 0, 1, 'mathilde88', '0683342810', NULL),
(70, 19, 'hmendes@ifrance.com', '$2y$13$C5TDl0mCb1LcvrAnn7MT0uU.99alyIyxcWmu6E5UMA71Irtllmwt.', 'Aubert', 'Éléonore', 0, 1, 'neveu.roger', '0636457367', NULL),
(71, 18, 'ins@ins.fr', '$2y$13$aPvQ9fpCCo2lJkzb32cKVORpgdpGOtPTIp18iQ4ENIIXcmHSnk.pi', 'Pereira', 'Patrick', 1, 1, 'ins', '0678079276', 'th-6246eb67aa43c.jpg'),
(72, 16, 'robert31@lopes.com', '$2y$13$NVq33X4ncsWP6b6zeNZznO9VIeYLLyFh1dIB5GbuZYq33nYDOJoIG', 'Faivre', 'Chantal', 0, 1, 'paul81', '0650184190', NULL),
(73, 18, 'theophile53@chevalier.fr', '$2y$13$TIwt0rsopHnsGWtJQOGB8eeFu/83eL/a.IbkZg1ywMLT9igmdvNxO', 'Thomas', 'Marcel', 0, 1, 'thomas49', '0646858456', NULL),
(74, 17, 'qcarre@lecomte.com', '$2y$13$z7gvQ981euz.Kp0kVVKcYe00fA.TnlqncidMh6XdmQQzI21UvZXfy', 'Berger', 'Margaret', 0, 1, 'thibaut.navarro', '0656060992', NULL),
(75, 16, 'yfouquet@legrand.com', '$2y$13$c7tp15WQCJXCu1Ix42nZxuVxyANcX8D/eRImKWTQcgPL8XjO94h0W', 'Moulin', 'Frédérique', 0, 1, 'klefevre', '0621190157', NULL),
(76, 20, 'zmoulin@muller.org', '$2y$13$BUWJ7XkKuwDnRe0NszwbP.9YkYWYt3dRhxOucbvc58I247HeaDkry', 'Morel', 'Noémi', 0, 0, 'mdurand', '0694161028', NULL),
(77, 16, 'emilie47@leveque.net', '$2y$13$UcOdc9XI7J0ZpTRta4SMGuIc5N6MHpj7E9ZIxsnHeg6L2prI3cZvK', 'Bonnin', 'Noël', 0, 1, 'vaillant.pauline', '0615024071', NULL),
(78, 17, 'noel59@tele2.fr', '$2y$13$46Hk0kh6RfDXj/DWJgY6F..mHS.inzTSEq/tmgdFad6c1LNZe3flG', 'Leconte', 'Suzanne', 0, 1, 'masson.bernadette', '0680030183', NULL),
(79, 15, 'aurelie.dupuis@carpentier.fr', '$2y$13$eRTEGsd.8BQ7OoIHtCWKN.wFZkjY/4v5LLEKbTfd/dXwk3w1cQaNm', 'Schmitt', 'Marcelle', 0, 0, 'tjoseph', '0623356919', NULL),
(80, 19, 'guillaume44@philippe.fr', '$2y$13$5YG0SyuYjYD6Y5ExXiuhv.TWvgYL7rQ1ZneEzBc6Y51K0U/WNcle.', 'Samson', 'Louis', 0, 1, 'nicolas.lefevre', '0671885172', NULL),
(81, 17, 'francois53@club-internet.fr', '$2y$13$TqzkCkVQBSZ0ph0GcS2WFOim0my.REf.0tuump5Qw3AeP2blStc0O', 'Potier', 'Timothée', 0, 1, 'stephanie.martin', '0637459439', NULL),
(82, 17, 'lgilbert@lebrun.fr', '$2y$13$n8IQ1f9RUnVVrm8w7slI3OX67PRQE3K2CEmHw9fKfsbXm9rQpW3IS', 'Renaud', 'Inès', 0, 1, 'zmarion', '0659516030', NULL),
(83, 18, 'corinne88@sfr.fr', '$2y$13$frftQFY3QFQe1843SYgDN.hDw46L6eVawGRXgnpwtDd9TSD8Ycv2O', 'Bruneau', 'Aurore', 0, 1, 'bertrand.sebastien', '0631318584', NULL),
(84, 17, 'camille09@gillet.com', '$2y$13$3yinzwlAC.I/I7t5bziRU.X7gS.GjvUMEbGkajqvK5ZpY8NiEPA4G', 'Masse', 'Marcel', 0, 1, 'emoreau', '0637239711', NULL),
(85, 21, 'mchartier@live.com', '$2y$13$zwQDsdqrz5ZEgFuXCURISu19ufJl0FynVho/WYvk66eMfYSIHCcoC', 'Ferrand', 'Victoire', 0, 1, 'matthieu.bonnin', '0637831270', NULL),
(86, 20, 'michel56@yahoo.fr', '$2y$13$5pReCSriTnXcFI43VnxSxO3SACGndCDVAlVFQ.nUIlgqhqoGQNAFS', 'Etienne', 'Guillaume', 0, 1, 'mcoulon', '0687877736', NULL),
(87, 18, 'gaillard.audrey@guibert.com', '$2y$13$kNsa0p6n24zfIMEp0jw6kucRNiNAa9lH6rZSfaTdz5ZKhRdMm1jky', 'Cousin', 'Marine', 0, 1, 'slenoir', '0699225921', NULL),
(88, 17, 'simone82@wanadoo.fr', '$2y$13$K4IsF6iHLaf4u.lKieQnmOygbChMoHIiItY6KS4hDhSOkgoEIDiGS', 'Robin', 'Dominique', 0, 1, 'xbreton', '0674906278', NULL),
(89, 15, 'nath38@bernard.com', '$2y$13$1N9F5mXbor8Y20esD6j0GeJEBWkcwj5Ln1ivcQiWTDxJPNl7SJdNq', 'Vaillant', 'Guy', 0, 1, 'josette86', '0670262314', NULL),
(90, 21, 'elise.hamon@garnier.com', '$2y$13$dFqFWGEVdv7Y/ofiz2MS4.pFb2SlZckm4Yv3HqAy/OfAwDtsrS8vq', 'Aubert', 'Maurice', 0, 1, 'diaz.victor', '0683355403', NULL),
(91, 17, 'sjacquot@hotmail.fr', '$2y$13$1zRQ.O4J893ep25q146hqeKM91gX9S81LrV10CVQv5/mzXGGo4.ZK', 'Descamps', 'Bernard', 0, 1, 'perez.martin', '0608664149', NULL),
(92, 21, 'roger55@ferrand.com', '$2y$13$aO/DHYOfEiGQy0RJikcaC.nlzL8eiCyifAQPeL6r/69uQdo/QqTj.', 'Dias', 'Arthur', 0, 1, 'ephilippe', '0690621679', NULL),
(93, 17, 'daniel.elise@hotmail.fr', '$2y$13$5vEblKA/3nUVzOZ57wKVGuN.yQF2.pTlZuGmTQ3y9Ymy22ArYPufK', 'Imbert', 'Rémy', 0, 1, 'petit.celine', '0610485982', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `participant_sortie`
--

DROP TABLE IF EXISTS `participant_sortie`;
CREATE TABLE IF NOT EXISTS `participant_sortie` (
  `participant_id` int(11) NOT NULL,
  `sortie_id` int(11) NOT NULL,
  PRIMARY KEY (`participant_id`,`sortie_id`),
  KEY `IDX_8E436D739D1C3019` (`participant_id`),
  KEY `IDX_8E436D73CC72D953` (`sortie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sortie`
--

DROP TABLE IF EXISTS `sortie`;
CREATE TABLE IF NOT EXISTS `sortie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_heure_debut` datetime NOT NULL,
  `duree` int(11) NOT NULL,
  `date_limite_inscription` datetime NOT NULL,
  `nb_inscriptions_max` int(11) NOT NULL,
  `infos_sortie` longtext COLLATE utf8mb4_unicode_ci,
  `lieu_id` int(11) NOT NULL,
  `campus_id` int(11) NOT NULL,
  `organisateur_id` int(11) NOT NULL,
  `etat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3C3FD3F26AB213CC` (`lieu_id`),
  KEY `IDX_3C3FD3F2AF5D55E1` (`campus_id`),
  KEY `IDX_3C3FD3F2D5E86FF` (`etat_id`),
  KEY `IDX_3C3FD3F2D936B2FA` (`organisateur_id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sortie`
--

INSERT INTO `sortie` (`id`, `nom`, `date_heure_debut`, `duree`, `date_limite_inscription`, `nb_inscriptions_max`, `infos_sortie`, `lieu_id`, `campus_id`, `organisateur_id`, `etat_id`) VALUES
(1, 'Beatae doloribus libero aspernatur.', '2021-11-23 14:35:06', 52, '2022-03-14 10:39:45', 23, 'Ea odit aut voluptatem id magnam exercitationem voluptatum. Tenetur aut nam earum magni dignissimos. Eaque repellendus animi corrupti quasi rem delectus. Ducimus a ea ad assumenda et. Aut eum culpa odit et nobis aperiam repudiandae.', 108, 20, 76, 19),
(2, 'Minima rerum sunt eveniet.', '2022-02-03 00:47:21', 5, '2022-02-04 01:54:05', 7, 'Molestias quae iure aut. Ad molestias ut sed ut et qui. Architecto fuga perspiciatis rem harum ipsam in cupiditate quia. In laudantium laboriosam enim nobis.', 82, 21, 68, 19),
(3, 'Sint qui quia aut.', '2022-01-20 10:43:52', 34, '2022-01-26 05:42:34', 10, 'Autem voluptatem molestiae et ea quibusdam aut eos. Ipsum laborum totam ut alias. Aut est illo corporis consequuntur laboriosam explicabo voluptatem officiis. Delectus esse ut ut itaque. Aliquid nulla sunt rerum similique. Dicta expedita reiciendis est odit a tempora veritatis. Eum labore cum voluptas earum.', 112, 15, 89, 19),
(4, 'Quaerat corrupti omnis odio accusamus.', '2021-12-14 14:08:21', 60, '2022-03-08 16:31:14', 4, 'Est libero neque sit aut magnam. Non quas officiis eum dolorum. Corrupti assumenda neque unde eaque. Voluptatibus eum dolores officia hic dolor voluptas. Repellat velit possimus atque at est. Sunt non cum aut.', 97, 20, 86, 19),
(5, 'Incidunt inventore dicta esse quos tempora.', '2022-01-07 18:18:03', 43, '2022-02-09 00:41:41', 17, 'Sunt qui tempore aspernatur. Debitis consectetur ea dolor modi nostrum dignissimos molestias. Facilis quibusdam quod excepturi officiis sed illo. Vel nihil id earum eum facilis. In cumque necessitatibus natus in debitis aliquam. Dolorum architecto maxime id adipisci aut explicabo. Fuga officia sed ab autem.', 117, 17, 78, 19),
(6, 'Non libero laudantium et.', '2021-10-12 09:47:37', 49, '2022-01-27 14:09:33', 20, 'Quia placeat in sed delectus eius eum. Sed omnis aperiam ipsum vitae ut ut eum sit. Harum sed ratione expedita non debitis non cupiditate. Sint architecto ut odit error. Placeat consequatur nam aperiam ipsum.', 117, 17, 93, 19),
(7, 'Deleniti est consequuntur sunt facere autem.', '2022-03-17 15:59:59', 62, '2022-03-27 04:10:01', 7, 'Voluptas consectetur ut molestiae et. Aut fugiat sapiente corporis iure nihil. Mollitia aut velit aut perspiciatis in culpa aut. Necessitatibus maxime aut alias libero velit. Nihil tempore dolor in eum earum. Vitae cumque ea delectus.', 93, 20, 86, 17),
(8, 'Accusamus laudantium quis nesciunt.', '2022-01-15 16:40:09', 19, '2022-02-03 05:44:25', 11, 'Qui placeat corporis minima. Culpa repudiandae laboriosam tempore minima quia cumque. Temporibus et laboriosam aspernatur qui non rerum qui. Cumque reiciendis vel similique nobis. Ea repudiandae occaecati officiis sint voluptatem veritatis.', 95, 16, 72, 19),
(9, 'Non porro iste fugiat consequatur.', '2022-02-28 10:27:40', 37, '2022-03-09 01:56:49', 14, 'Ea in ex sunt. Eos aut repellat ut occaecati mollitia veritatis voluptatem. Voluptas et facilis distinctio ducimus exercitationem voluptas voluptatem iusto. Cupiditate possimus non rerum officia blanditiis est blanditiis. Culpa pariatur porro maxime aperiam aut et.', 87, 17, 84, 19),
(10, 'Possimus veniam incidunt corrupti.', '2022-01-27 09:51:27', 58, '2022-02-04 16:08:41', 14, 'Quas dolor ea dignissimos pariatur iusto non aperiam. Dolorem et atque et dolorem quos. Voluptatem ex numquam numquam tempora aut. Provident nihil illum natus et ad voluptatem ea consequatur. Et voluptas consectetur voluptates consectetur rerum quo soluta. Voluptatem voluptate eaque culpa praesentium nam possimus dolor.', 119, 17, 91, 19),
(11, 'Sit vitae earum sint laboriosam.', '2022-01-16 22:48:35', 1, '2022-02-08 21:47:39', 4, 'Ut perspiciatis qui repellat sapiente eos voluptatem. Consequatur quibusdam eos esse voluptates eligendi. In inventore labore qui sit sed eum. Quasi ducimus aut magni.', 98, 16, 77, 19),
(12, 'Dicta corrupti corrupti consequuntur.', '2022-02-18 06:57:12', 27, '2022-02-26 21:50:25', 9, 'Non ullam sit maiores nihil. Nostrum ducimus est asperiores. Laudantium id aut laborum itaque sed. Veritatis quas magnam magnam. Doloremque voluptatum adipisci quos ducimus. Quisquam consequatur rerum eum saepe iste optio.', 108, 18, 71, 19),
(13, 'Sequi voluptatem saepe accusamus est sequi.', '2021-12-21 16:52:17', 13, '2022-01-09 15:09:08', 25, 'Est occaecati est vel velit quis. Ut sapiente impedit consequatur. Quibusdam repudiandae neque mollitia enim accusantium. Impedit adipisci laudantium aut fuga consectetur. Ullam in quia ut expedita quo voluptatem molestias. Possimus iure voluptates consequuntur et occaecati.', 100, 18, 83, 19),
(14, 'Labore soluta vero qui autem eum.', '2022-02-26 08:51:28', 61, '2022-03-11 20:20:08', 18, 'Deserunt dolor consequuntur ea autem. Asperiores reprehenderit occaecati incidunt quia fugit qui. Quibusdam modi dolorum quod voluptatem. Quod unde necessitatibus fugit hic.', 114, 17, 84, 19),
(15, 'Voluptate quae quod eum in.', '2022-02-06 09:46:36', 67, '2022-03-01 19:20:47', 19, 'Ut natus odio vel similique sed veritatis. Repellendus culpa rem officiis natus est iste. Dignissimos vel mollitia saepe omnis minima id. Minus consectetur alias suscipit omnis similique deserunt quis. Quidem eum perferendis deleniti sint.', 116, 21, 85, 19),
(16, 'Quis velit amet aspernatur velit.', '2021-11-13 20:41:27', 58, '2022-01-17 11:18:14', 11, 'Quod labore eos qui id beatae quae aut id. Deleniti delectus est rerum enim assumenda dolorem quod molestiae. Doloribus necessitatibus odio nesciunt sapiente et eos molestias. Sequi eum qui ut voluptas vel aut rerum. Corrupti aut alias et enim quis. Est inventore voluptatem deleniti nisi itaque dolores. Perspiciatis nulla necessitatibus vel quia quo.', 109, 19, 67, 19),
(17, 'Fugit rem et tempora aut.', '2021-12-03 15:03:59', 28, '2022-03-23 19:21:12', 4, 'Totam voluptatum omnis provident perspiciatis animi ut. Voluptatem optio tenetur porro vitae inventore ut facilis. Qui velit ullam et quam nihil. Delectus corrupti sit deleniti voluptatibus qui non. Corporis consequatur eum cumque libero omnis sequi eveniet omnis. Cumque velit dolores eum et dolorem maiores.', 88, 18, 73, 19),
(18, 'Molestias ipsum neque enim dolore temporibus.', '2022-02-27 13:56:03', 5, '2022-03-08 06:53:26', 22, 'Iusto minima ad consequatur autem. Architecto et qui quia ea rerum fugiat molestiae. Necessitatibus voluptates voluptas impedit. Aut voluptatum dolores est quos. Quia dicta ratione deserunt perferendis. Quia quasi animi doloremque totam eligendi nesciunt. Cupiditate quia asperiores incidunt excepturi rerum iure nulla quam.', 88, 17, 78, 19),
(19, 'Rerum sunt sit mollitia dolor debitis.', '2021-11-18 17:43:27', 10, '2022-01-07 21:09:12', 19, 'Inventore culpa error veritatis eligendi ad mollitia saepe. Culpa perferendis eos explicabo optio. Nostrum corrupti molestias architecto ducimus eos quia. Natus eligendi sed beatae et minus. Eos quia dicta at sunt.', 104, 19, 80, 19),
(20, 'Consequatur atque alias.', '2022-02-23 05:51:19', 72, '2022-03-03 19:49:34', 5, 'In ratione nam fuga dolore in. Voluptatem est tempora delectus atque ut. Autem ipsam id ut rerum. Voluptas aut facere dolor. Quia qui labore dolor tenetur nemo qui eum rerum.', 98, 17, 74, 19),
(21, 'Sed aut eaque velit exercitationem sapiente.', '2022-02-06 19:17:35', 28, '2022-03-14 21:20:23', 8, 'Enim minima tempore fuga. Saepe optio numquam qui perspiciatis. A unde in nihil temporibus facere rerum. Natus accusamus deserunt quis voluptate officiis aperiam molestias illo. Dolorem id debitis beatae eveniet. Voluptates praesentium vero qui qui harum aut quis.', 85, 17, 63, 19),
(22, 'Facere eos necessitatibus qui corporis.', '2022-03-01 19:49:07', 34, '2022-03-09 17:44:54', 23, 'Dolorem aliquam consequatur vitae vitae dolore id similique. Qui laborum ipsam reprehenderit sapiente quia omnis vitae. Atque veniam dicta exercitationem pariatur ipsam. Dolorem quisquam et quae magni laborum rerum. Repudiandae quia blanditiis unde error. Veritatis voluptas debitis quo aperiam. Consectetur nisi distinctio et dolorem atque magni sit.', 100, 16, 75, 19),
(23, 'Incidunt inventore labore placeat exercitationem.', '2021-12-20 19:20:42', 62, '2021-12-22 15:41:22', 16, 'Enim quo non amet laboriosam veniam. Quo veniam voluptatum est minus excepturi ut deserunt. Porro accusantium qui maiores. Officiis culpa provident tenetur. Id ea dolorem voluptatem animi. Rerum aperiam totam non voluptatum mollitia temporibus.', 99, 16, 72, 19),
(24, 'Voluptatibus nam minus quam.', '2022-01-12 06:06:40', 18, '2022-03-19 09:59:21', 9, 'Explicabo praesentium distinctio sunt. Id consequatur culpa eligendi suscipit. Et atque qui in. Ut sit necessitatibus aut earum possimus quia. Cupiditate recusandae non harum fugiat.', 102, 16, 72, 19),
(25, 'Voluptatem et qui commodi.', '2021-12-28 13:15:52', 64, '2022-01-08 14:23:42', 16, 'Esse assumenda ad voluptates possimus rem hic unde delectus. Eum molestiae ratione eveniet voluptatem omnis. Perspiciatis voluptatem ducimus vero. Rerum voluptate odit rem rerum iusto esse. Consequatur nisi et perspiciatis rerum. Autem saepe suscipit molestias odit.', 89, 17, 82, 19),
(26, 'Laboriosam reprehenderit aut voluptate ut.', '2021-09-29 20:52:19', 2, '2021-12-10 01:53:54', 9, 'Ex eos quasi voluptas non. Maxime quisquam earum ad tenetur distinctio voluptatem similique. Et nemo adipisci quis ducimus ducimus. Tenetur perspiciatis explicabo est culpa. Excepturi similique libero nostrum esse eligendi blanditiis illum sint. Itaque ipsum eveniet numquam eos.', 104, 15, 79, 19),
(27, 'Dolore veritatis totam voluptatem et.', '2021-12-08 16:59:40', 16, '2021-12-11 14:51:03', 25, 'Veniam voluptatem et nihil velit iste. Qui aut fugit dolor inventore cupiditate. Dolor esse accusamus impedit a. Dolorem doloribus dolores quis.', 117, 20, 86, 19),
(28, 'Et harum magni consequatur voluptatem repellendus.', '2021-10-08 16:56:12', 9, '2022-02-13 08:42:15', 3, 'In necessitatibus eos facilis velit quisquam natus quo. Deserunt corporis laudantium laborum dolor assumenda laboriosam voluptas. Minus qui numquam sint est explicabo. Nihil rem nostrum doloribus tempore odio voluptas velit quidem.', 86, 17, 82, 19),
(29, 'Ipsum adipisci assumenda consequatur.', '2021-10-05 09:02:04', 34, '2021-10-08 19:31:37', 4, 'Voluptas velit quia asperiores corporis eos quo. Sed nobis quia et reprehenderit repellat. Ea et aut nesciunt exercitationem. Ab aperiam odio molestiae in ad est qui. Facere consequuntur corporis hic explicabo deleniti eius quae. Repellat placeat quae voluptate quia expedita quis.', 89, 17, 82, 19),
(30, 'Nihil ut quos in.', '2022-01-07 16:25:59', 36, '2022-03-18 13:54:15', 11, 'Nam rem rem fugiat ducimus accusamus qui expedita. Magni ex est aut quam eum. Consequatur sunt voluptas suscipit ut facere quia tempore. Sint molestiae eos impedit et odit ipsum nobis corporis. Id maxime commodi consectetur laborum tempora. Deserunt consequatur vel magni expedita quibusdam dolor et. Rerum beatae deserunt voluptas veniam sed sit.', 84, 19, 67, 19),
(31, 'Blanditiis quas rerum omnis qui.', '2021-12-17 17:58:39', 34, '2022-01-17 23:11:57', 5, 'Numquam reiciendis et qui sed. Magnam sed sint accusantium enim deleniti illo. Aut atque aut quis alias. Facilis nostrum at dolores. Ipsa et consequatur amet facilis dolorem. Quia rerum illum voluptas qui ea velit. Enim eum rem ullam.', 90, 20, 76, 19),
(32, 'Ut ut assumenda non.', '2022-02-08 14:43:55', 69, '2022-03-15 00:33:13', 7, 'Assumenda eveniet tempore enim recusandae. Ut ad voluptatem aut exercitationem eaque non. Voluptatem dignissimos rerum tempore ut eum vel omnis distinctio. Dolor non doloremque enim quaerat omnis quaerat id. Sed non necessitatibus aut mollitia dicta. Asperiores facere libero molestiae inventore quia repellat repellat. Ut voluptatem rerum fugiat autem ut omnis ducimus.', 109, 17, 78, 19),
(33, 'Quas non molestiae numquam qui.', '2022-02-23 03:24:26', 3, '2022-03-10 02:08:25', 15, 'Officiis placeat eum maiores quam autem sint accusamus blanditiis. Sunt reiciendis nulla neque. Temporibus earum consequatur nemo vitae. Minima eum repellat nihil harum nostrum provident voluptas. Inventore non recusandae vero nemo sit.', 120, 20, 64, 19),
(34, 'Et enim labore voluptatibus.', '2021-11-11 01:14:24', 35, '2022-02-25 09:10:45', 23, 'Vel qui perspiciatis expedita quae. Rerum ad minima aliquam aut omnis placeat. Voluptatem maiores eum ut. Temporibus sit maxime rerum suscipit recusandae facere veniam. Consectetur esse et sed iste sed omnis dolor. Voluptatem optio dignissimos magni ex recusandae eos ad.', 95, 16, 75, 19),
(35, 'Numquam eligendi nulla veniam eos quisquam.', '2022-01-22 19:00:14', 72, '2022-03-07 21:25:53', 15, 'Amet dolorum velit cumque. Debitis labore explicabo molestiae earum. Dolorum ut enim quasi necessitatibus ad ex numquam. Nobis qui vero ducimus distinctio quas non non. Necessitatibus quis accusamus rerum cumque cumque dicta.', 95, 18, 87, 19),
(36, 'Veniam alias temporibus deleniti iure veritatis.', '2021-10-30 22:34:53', 10, '2021-11-15 05:08:18', 13, 'Id pariatur provident dolor illum. Expedita iure distinctio et iure. Aut cupiditate aut nisi tempore eos eveniet qui. Laboriosam voluptas quia nam minus. Vel architecto excepturi quae esse. Aut laboriosam dolore error nostrum pariatur qui sed.', 97, 17, 78, 19),
(37, 'Nihil rem voluptatem tempora.', '2022-01-16 15:50:13', 48, '2022-03-24 00:32:00', 23, 'Optio dolores explicabo provident rem. Quis et id cum voluptatum nobis illo qui. Totam qui odit voluptatum omnis molestiae at quibusdam. A in excepturi eveniet minima nihil ut libero. Voluptates possimus et et ipsam deserunt dicta labore quia. Error commodi et ullam maxime aut accusantium adipisci.', 116, 18, 73, 19),
(38, 'Animi quam explicabo.', '2021-12-09 01:59:27', 39, '2022-01-08 23:17:14', 17, 'Esse ipsum iusto ex laboriosam qui. Aspernatur corrupti omnis officia et sapiente blanditiis. Dolore fuga totam sit pariatur. Itaque delectus ut sequi maxime explicabo incidunt.', 87, 17, 74, 19),
(39, 'Dolor voluptatum itaque beatae cupiditate porro.', '2022-01-07 22:00:47', 59, '2022-02-28 19:57:36', 7, 'Magnam modi possimus et. Et dolorum omnis esse praesentium corrupti reiciendis. Dolor quae inventore aut quidem nisi molestias. Quo est et porro quas ut.', 107, 15, 89, 19),
(40, 'Quam ut pariatur et.', '2022-01-11 02:27:11', 42, '2022-02-05 18:24:04', 18, 'Dolorum illo ut repudiandae optio omnis sint ut. Voluptatibus repellat enim iste et fuga libero hic iure. Nulla aut numquam nostrum harum laboriosam vero non. Ducimus sit nam nobis est dolorem natus.', 116, 15, 89, 19),
(41, 'ttt', '2022-04-10 15:32:00', 90, '2022-04-08 13:31:00', 30, 'pouiyuftdyrt', 81, 17, 63, 15),
(42, 'mllll', '2022-04-10 16:17:00', 90, '2022-04-05 16:18:00', 60, 'ghfqgfqusgfusygdf', 81, 17, 63, 15),
(43, 'mllll', '2022-04-10 20:39:00', 90, '2022-04-01 19:38:00', 14, 'htuyiuioop', 82, 18, 71, 15),
(44, 'sortie', '2022-04-10 20:53:00', 90, '2022-04-01 21:54:00', 20, 'guyfgeurgfsd', 90, 18, 71, 15),
(45, 'ttt', '2022-04-01 21:31:00', 60, '2022-04-10 22:32:00', 50, 'iioyiyipim', 94, 18, 71, 17),
(46, 'ttt', '2022-04-16 14:25:00', 30, '2022-04-08 15:26:00', 20, 'nbfdfs', 109, 18, 71, 16),
(47, 'test2', '2022-04-10 15:30:00', 90, '2022-04-02 16:30:00', 30, 'oriusyiueozp', 114, 18, 71, 15),
(48, 'test', '2022-04-10 15:30:00', 90, '2022-04-02 16:30:00', 30, 'oriusyiueozp', 118, 18, 71, 15),
(49, 'ttt5', '2022-04-10 14:46:00', 15, '2022-04-09 00:00:00', 14, 'iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii', 87, 18, 71, 14),
(50, 'ttt', '2022-04-10 14:50:00', 10, '2022-04-09 00:00:00', 4, 'huutf', 135, 18, 71, 14),
(51, 'azrty', '2022-04-10 15:54:00', 60, '2022-04-03 13:52:00', 12, 'huphzprçgh', 85, 18, 71, 15),
(52, 'hhhhhh', '2022-04-10 02:23:00', 90, '2022-04-07 01:22:00', 15, 'lhgzqygfyi', 84, 18, 71, 15),
(53, 'test3', '2022-04-24 18:13:00', 90, '2022-04-08 16:11:00', 30, 'EHZFGLGRF', 110, 18, 71, 16),
(54, 'test5', '2022-04-15 17:20:00', 60, '2022-04-05 17:20:00', 60, 'ghfidlyqsgh', 118, 18, 71, 15),
(55, 'test15', '2022-04-30 18:36:00', 60, '2022-04-28 19:37:00', 20, 'KGKYGGH', 89, 18, 71, 16),
(57, 'ins', '2022-04-23 14:29:00', 60, '2022-04-14 14:29:00', 20, 'eGMZOYRDFSJLKQM', 114, 18, 71, 14),
(58, 'test16', '2022-04-23 10:53:00', 62, '2022-04-13 10:53:00', 15, 'fngrndgbcv', 105, 18, 71, 14),
(59, 'test19', '2022-04-10 16:05:00', 1404, '2022-04-07 15:04:00', 50, 'iuyutjyrdt', 112, 18, 71, 15),
(60, 'test02', '2022-04-23 16:36:00', 60, '2022-04-14 15:35:00', 15, 'ugilygkhb', 121, 18, 71, 13),
(61, 'sortieGroupe', '2022-04-22 16:28:00', 36, '2022-04-14 16:28:00', 50, '44', 122, 18, 71, 13),
(62, 'test14', '2022-04-23 14:32:00', 96, '2022-04-14 14:31:00', 18, '1qknldbclhvsbdq', 124, 18, 71, 14),
(63, 'bis', '2022-04-28 14:34:00', 189, '2022-04-20 14:34:00', 14, 'hlfutffufku', 125, 18, 71, 13),
(64, 'sfgdfhgjh', '2022-04-22 13:33:00', 159, '2022-04-12 11:35:00', 17, 'jhlsdisdhfliqs', 88, 18, 71, 14),
(65, 'test15', '2022-04-23 16:22:00', 19, '2022-04-12 00:00:00', 50, 'jtdyrhgnfd', 126, 18, 71, 14),
(66, 'ya', '2022-04-29 17:48:00', 90, '2022-04-21 00:00:00', 50, '19edfergrtg', 98, 18, 71, 13),
(67, 'test1989', '2022-04-22 17:50:00', 1999, '2022-04-15 00:00:00', 19, 'vliqgdifyv', 83, 18, 71, 14),
(68, 'test15', '2022-04-29 17:51:00', 936, '2022-04-14 00:00:00', 18, 'ygfldsfqjL', 127, 18, 71, 14),
(69, 'ttt', '2022-04-17 13:49:00', 88, '2022-04-08 00:00:00', 55, 'thdrydgd', 88, 18, 71, 15),
(70, 'test15', '2022-04-17 17:22:00', 555, '2022-04-13 00:00:00', 555, 'jydthrqgef', 128, 18, 71, 13),
(71, 'MINA', '2022-04-22 17:40:00', 999, '2022-04-13 00:00:00', 55, '^piutydrte', 129, 18, 71, 14),
(72, 'MINA', '2022-04-22 17:41:00', 55, '2022-04-13 00:00:00', 55, 'yutydtsdqs', 130, 18, 71, 14),
(73, 'MINA', '2022-04-23 17:44:00', 55, '2022-04-21 00:00:00', 55, 'yçturytyrt', 131, 18, 71, 14),
(74, 'MINA', '2022-04-23 18:03:00', 777, '2022-04-21 00:00:00', 777, 'ryehtrzfe', 132, 18, 71, 14),
(75, 'MINA', '2022-04-16 21:26:00', 77, '2022-04-12 00:00:00', 77, 'iyuytrez', 133, 18, 71, 14),
(76, 'MINA', '2022-04-16 09:49:00', 99, '2022-04-12 00:00:00', 88, 'ixyrghjgkbjl', 134, 18, 71, 14),
(77, 'MINA', '2022-04-23 06:56:00', 90, '2022-04-19 00:00:00', 20, 'ulgiyuityrt', 97, 18, 71, 14),
(78, 'lll', '2022-04-13 13:39:00', 55, '2022-04-09 00:00:00', 855, 'dfghgj', 136, 18, 71, 14);

-- --------------------------------------------------------

--
-- Structure de la table `sortie_participant`
--

DROP TABLE IF EXISTS `sortie_participant`;
CREATE TABLE IF NOT EXISTS `sortie_participant` (
  `sortie_id` int(11) NOT NULL,
  `participant_id` int(11) NOT NULL,
  PRIMARY KEY (`sortie_id`,`participant_id`),
  KEY `IDX_E6D4CDADCC72D953` (`sortie_id`),
  KEY `IDX_E6D4CDAD9D1C3019` (`participant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ville`
--

DROP TABLE IF EXISTS `ville`;
CREATE TABLE IF NOT EXISTS `ville` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_postal` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ville`
--

INSERT INTO `ville` (`id`, `nom`, `code_postal`) VALUES
(41, 'Robert', '05 849'),
(42, 'Nicolas-sur-Teixeira', '17 642'),
(43, 'Thomas-sur-Tessier', '84 160'),
(44, 'Morvan', '99 650'),
(45, 'Bouchet-sur-Mer', '69105'),
(46, 'Pelletier-sur-Rodriguez', '23 017'),
(47, 'GarnierVille', '36913'),
(48, 'PiresBourg', '17 692'),
(49, 'Martynec', '95064'),
(50, 'GuibertVille', '38 399'),
(51, 'Guyonnec', '54 434'),
(52, 'LemonnierBourg', '05 911'),
(53, 'Le Goff', '72 669'),
(54, 'Vincent', '00720'),
(55, 'Diallo-la-Forêt', '03 630'),
(56, 'Mendesdan', '35 420'),
(57, 'Robin-les-Bains', '86 649'),
(58, 'Jacob', '89733'),
(59, 'BerthelotBourg', '09 510'),
(60, 'Laurent-sur-Philippe', '52 225'),
(61, 'test', '80000');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `lieu`
--
ALTER TABLE `lieu`
  ADD CONSTRAINT `FK_2F577D59A73F0036` FOREIGN KEY (`ville_id`) REFERENCES `ville` (`id`);

--
-- Contraintes pour la table `participant`
--
ALTER TABLE `participant`
  ADD CONSTRAINT `FK_D79F6B11AF5D55E1` FOREIGN KEY (`campus_id`) REFERENCES `campus` (`id`);

--
-- Contraintes pour la table `participant_sortie`
--
ALTER TABLE `participant_sortie`
  ADD CONSTRAINT `FK_8E436D739D1C3019` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_8E436D73CC72D953` FOREIGN KEY (`sortie_id`) REFERENCES `sortie` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `sortie`
--
ALTER TABLE `sortie`
  ADD CONSTRAINT `FK_3C3FD3F26AB213CC` FOREIGN KEY (`lieu_id`) REFERENCES `lieu` (`id`),
  ADD CONSTRAINT `FK_3C3FD3F2AF5D55E1` FOREIGN KEY (`campus_id`) REFERENCES `campus` (`id`),
  ADD CONSTRAINT `FK_3C3FD3F2D5E86FF` FOREIGN KEY (`etat_id`) REFERENCES `etat` (`id`),
  ADD CONSTRAINT `FK_3C3FD3F2D936B2FA` FOREIGN KEY (`organisateur_id`) REFERENCES `participant` (`id`);

--
-- Contraintes pour la table `sortie_participant`
--
ALTER TABLE `sortie_participant`
  ADD CONSTRAINT `FK_E6D4CDAD9D1C3019` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_E6D4CDADCC72D953` FOREIGN KEY (`sortie_id`) REFERENCES `sortie` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
