--
-- Create schema escape_game
--

DROP SCHEMA IF EXISTS `escape_game`;
CREATE SCHEMA `escape_game`;
USE escape_game;

-- -----------------------------
-- Définition de mj
-- -----------------------------

CREATE TABLE `mj` (
  `idMJ` int(11) NOT NULL AUTO_INCREMENT,
  `loginMJ` VARCHAR(255) DEFAULT NULL,
  `mdpMJ` VARCHAR(255) DEFAULT NULL,
  `nomMJ` VARCHAR(255) DEFAULT NULL,
  `TIMESTAMP` Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idMJ`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- -----------------------------
-- Définition de enigme
-- -----------------------------

CREATE TABLE `enigme` (
  `idEnigme` int(11) NOT NULL AUTO_INCREMENT,
  `titre` VARCHAR(255) DEFAULT NULL,
  `enonce` mediumtext DEFAULT NULL,
  `reponse` VARCHAR(255) DEFAULT NULL,
  `imageEnigme` mediumtext DEFAULT NULL,
  `idJeu` int(11) DEFAULT 0,
  `TIMESTAMP` Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idEnigme`),
  KEY `Cle_numEnigme` (`idEnigme`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- -----------------------------
-- Définition de jeu
-- -----------------------------

CREATE TABLE `jeu` (
  `idJeu` int(11) NOT NULL AUTO_INCREMENT,
  `titreJeu` VARCHAR(255) DEFAULT NULL,
  `nbEnigmes` int(11) DEFAULT 0,
  `niv` int(11) DEFAULT 0,
  `imageJeu` mediumtext DEFAULT NULL,
  `tps` datetime DEFAULT NULL,
  `idMJ` int(11) DEFAULT 0,
  `TIMESTAMP` Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idJeu`),
  CONSTRAINT `FK_jeu_1` FOREIGN KEY (`idMJ`) REFERENCES `MJ` (`idMJ`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- -----------------------------
-- Définition de equipe
-- -----------------------------

CREATE TABLE `equipe` (
  `numEqu` int(11) NOT NULL AUTO_INCREMENT,
  `loginEqu` VARCHAR(255) DEFAULT NULL,
  `mdpEqu` VARCHAR(255) DEFAULT NULL,
  `nbJoueurs` int(11) DEFAULT 0,
  `nomEqu` VARCHAR(255) DEFAULT NULL,
  `imageEqu` mediumtext DEFAULT NULL,
  `idJeu` int(11) DEFAULT 0,
  `idMJ` int(11) DEFAULT 0,
  `TIMESTAMP` Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`numEqu`),
  KEY `Cle_numEqu` (`numEqu`),
  CONSTRAINT `FK_equipe_1` FOREIGN KEY (`idJeu`) REFERENCES `Jeu` (`idJeu`),
  CONSTRAINT `FK_equipe_2` FOREIGN KEY (`idJeu`) REFERENCES `Jeu` (`idJeu`),
  CONSTRAINT `FK_equipe_3` FOREIGN KEY (`idMJ`) REFERENCES `MJ` (`idMJ`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- -----------------------------
-- Définition de resoudre
-- -----------------------------

CREATE TABLE `resoudre` (
  `idEnigme` int(11) NOT NULL,
  `numEqu` int(11) NOT NULL,
  `temps` datetime DEFAULT NULL,
  `TIMESTAMP` Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`numEqu`),
  CONSTRAINT `FK_resoudre_1` FOREIGN KEY (`idEnigme`) REFERENCES `Enigme` (`idEnigme`),
  CONSTRAINT `FK_resoudre_2` FOREIGN KEY (`numEqu`) REFERENCES `Equipe` (`numEqu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -----------------------------
-- Définition de joueur
-- -----------------------------

CREATE TABLE `joueur` (
  `idJoueur` int(11) NOT NULL AUTO_INCREMENT,
  `nomJoueur` VARCHAR(255) DEFAULT NULL,
  `numEqu` int(11) DEFAULT 0,
  `TIMESTAMP` Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idJoueur`),
  CONSTRAINT `FK_joueur_1` FOREIGN KEY (`numEqu`) REFERENCES `Equipe` (`numEqu`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

