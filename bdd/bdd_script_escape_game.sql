--
-- Create schema escape_game
--

DROP SCHEMA IF EXISTS escape_game;
CREATE SCHEMA escape_game;
USE escape_game;

-- -----------------------------
-- Définition de mj
-- -----------------------------

CREATE TABLE mj (
  idMJ int(11) NOT NULL AUTO_INCREMENT,
  loginMJ VARCHAR(255) DEFAULT NULL,
  mdpMJ VARCHAR(255) DEFAULT NULL,
  nomMJ VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (idMJ)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- -----------------------------
-- Définition de jeu
-- -----------------------------

CREATE TABLE jeu (
  idJeu int(11) NOT NULL AUTO_INCREMENT,
  titreJeu VARCHAR(255) DEFAULT NULL,
  descriptionJeu VARCHAR(500) DEFAULT NULL,
  niv int(11) DEFAULT NULL,
  imageJeu mediumtext DEFAULT NULL,
  tmpsMoy TIME DEFAULT 0,
  meilleurTmps TIME DEFAULT 0,
  pireTmps TIME DEFAULT 0,
  PRIMARY KEY (idJeu)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- -----------------------------
-- Définition de enigme
-- -----------------------------

CREATE TABLE enigme (
  idEnigme int(11) NOT NULL AUTO_INCREMENT,
  titreEnigme VARCHAR(255) DEFAULT NULL,
  enonceEnigme mediumtext DEFAULT NULL,
  repUnique VARCHAR(255) DEFAULT NULL,
  imageEnigme VARCHAR(500) DEFAULT NULL,
  ordre int (11) DEFAULT 0,
  idJeu int(11) DEFAULT NULL,
  videoEnigme VARCHAR(500) DEFAULT NULL,
  PRIMARY KEY (idEnigme),
CONSTRAINT FK_enigme_jeu FOREIGN KEY (idJeu) REFERENCES jeu (idJeu)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- -----------------------------
-- Définition de equipe
-- -----------------------------

CREATE TABLE equipe (
  idEqu int(11) NOT NULL AUTO_INCREMENT,
  loginEqu VARCHAR(255) DEFAULT NULL,
  mdpEqu VARCHAR(255) DEFAULT NULL,
  nbJoueurs int(11) CHECK (nbJoueurs<10 AND nbJoueurs>0),
  nomEqu VARCHAR(255) DEFAULT NULL,
  imageEqu VARCHAR(500) DEFAULT NULL,
  PRIMARY KEY (idEqu)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- ------------------------------
-- Définition de partie
-- ------------------------------
CREATE TABLE partie
( idPart int (11) NOT NULL AUTO_INCREMENT,
tmpsTotal TIME DEFAULT 0,
idJeu int(11) DEFAULT NULL,
idEqu int(11) DEFAULT NULL,
partieFinie BOOLEAN ,
partieReussie BOOLEAN,
PRIMARY KEY (idPart),
CONSTRAINT FK_partie_jeu FOREIGN KEY (idJeu) REFERENCES jeu (idJeu),
CONSTRAINT FK_partie_equipe FOREIGN KEY (idEqu) REFERENCES equipe (idEqu)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- -----------------------------
-- Définition de stadeTent
-- -----------------------------

CREATE TABLE stadeTent (
  codeStade VARCHAR(2) NOT NULL,
  libelleStade VARCHAR(255) NOT NULL,
  PRIMARY KEY (codeStade)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- -----------------------------
-- Définition de tentative
-- -----------------------------

CREATE TABLE tentative (
  idTent int(11) NOT NULL AUTO_INCREMENT,
  dateDebut TIMESTAMP DEFAULT 0,
  dateFin TIMESTAMP  DEFAULT 0,
   nbIndices int (11) DEFAULT 0,
   stade int (11) DEFAULT NULL,
   idPart int(11) NOT NULL,
   idEnigme int(11) NOT NULL,
   codeStade VARCHAR(2) DEFAULT "EC",
  PRIMARY KEY (idTent),
  CONSTRAINT FK_tent_enigme FOREIGN KEY (idEnigme) REFERENCES enigme (idEnigme),
  CONSTRAINT FK_tent_partie FOREIGN KEY (idPart) REFERENCES partie (idPart),
  CONSTRAINT FK_tent_stade FOREIGN KEY (codeStade) REFERENCES stadeTent (codeStade)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- -----------------------------
-- Définition de reponsesPossibles
-- -----------------------------

CREATE TABLE reponsesPossibles (
  idRepPoss int(11) NOT NULL AUTO_INCREMENT,
  enonceRepPoss mediumtext DEFAULT NULL,
  idEnigme int(11) DEFAULT NULL,
  PRIMARY KEY (idRepPoss),
  CONSTRAINT FK_repPoss_enigme FOREIGN KEY (idEnigme) REFERENCES enigme (idEnigme)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- -----------------------------
-- Définition de indice
-- -----------------------------

CREATE TABLE indice (
  idIndice int(11) NOT NULL AUTO_INCREMENT,
  enonceIndice mediumtext DEFAULT NULL,
  idEnigme int(11) DEFAULT NULL,
  nbSecMaxIndice int (11) DEFAULT 60,
  PRIMARY KEY (idIndice),
  CONSTRAINT FK_indice_enigme FOREIGN KEY (idEnigme) REFERENCES enigme (idEnigme)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- -----------------------------
-- Définition de aider
-- -----------------------------


CREATE TABLE aider (
  idAide int (11) NOT NULL AUTO_INCREMENT,
  idTent int(11) NOT NULL,
  idMJ int(11) DEFAULT NULL ,
  msg mediumtext DEFAULT NULL,
  dateMsg TIMESTAMP DEFAULT 0,
  PRIMARY KEY (idAide),
  CONSTRAINT FK_aider_MJ FOREIGN KEY (idMJ) REFERENCES mj (idMJ),
 CONSTRAINT FK_aider_tent FOREIGN KEY (idTent) REFERENCES tentative (idTent)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- -------------------------
-- insertion valeurs fixes
-- --------------------------

insert into StadeTent values("T","terminée");
insert into StadeTent values("A", "abandonnée");
insert into StadeTent values("EC", "en cours");


-- -----------------------------
-- insertion scénarios
-- -----------------------------


insert into jeu (titreJeu, descriptionJeu, niv,imageJeu) values ("Attrapez le vif d'or","Bienvenue dans le monde des sorciers, Harry et ses amis sont enfermés dans un cachot à Poudlard. Ils ont découvert que le vif d'or renfermait en réalité un puissant sortilège capable d'évincer Voldemort.
 Rassemblez votre équipe et aidez-les à s'enfuir. Il faut trouver le vif d'or avant que le maître des ténèbres ne prenne le contrôle du chateau...",2,"harrypotter.jpg");

insert into jeu (titreJeu, descriptionJeu, niv,imageJeu) values ("Oompa Loompa en détresse","Petit Oompa Loompa étourdi que vous êtes ! vous êtes sorti de la chocolaterie de Willy Wonka et n'arrivez pas à y retourner. Une chance s'offre à vous : l'usine ouvre pour une viste exceptionnelle.. arriverez -vous à retrouver votre chemin sans que le grand chocolatier ne s'aperçoive de votre absence? ",2,"chocolaterie.jpg");


-- -----------------------------
-- insertion enigmes
-- -----------------------------

-- Jeu 1--
insert into enigme(idEnigme, titreEnigme, enonceEnigme, repUnique, imageEnigme, ordre, idJeu) values (1,"Le dragon","Quel est le nom du dragon de Hagrid?","Norbert","norbert2.jpg",1,1);

insert into enigme (idEnigme, titreEnigme, enonceEnigme, repUnique, imageEnigme, ordre, idJeu) values (2, "Trop de dragons, tuent le dragon...","Norbert, bébé dragon, et Flamme, sa soeur, sont nés de la même portée. 
Dans celle-ci, Norbert a eu 2fois plus de soeurs que de frères, tandis que Flamme a eu 2 fois plus de frères que de soeurs. Combien de jeunes dragons sont nés ?","4","bebedragons.jpg",2,1);

insert into enigme(idEnigme, titreEnigme, enonceEnigme, repUnique, imageEnigme, ordre, idJeu, videoEnigme) values (3, "Sacré molosse","Quel est le nom du chien à 3 têtes dans Harry Potter à l'école des sorciers?","Touffu","touffu.jpg",3,1,"hp.mp4");

insert into enigme(idEnigme, titreEnigme, enonceEnigme, repUnique, imageEnigme, ordre, idJeu) values (4, "Miroir Oh miroir","Quel est le nom de la potion qui permet de prendre temporairement l'apparence d'un autre","polynectar", "secrets.jpg",8,1);

insert into enigme (idEnigme, titreEnigme, enonceEnigme, repUnique, imageEnigme, ordre, idJeu) values (5, "Dobby elfe libre","Que faut-il offrir aux elfes de maison pour  leur rendre leur liberté?","vetement","dobby.jpg",5,1);

insert into enigme (idEnigme, titreEnigme, enonceEnigme, repUnique, imageEnigme, ordre, idJeu, videoEnigme)values (6, "Deux sorciers pour le prix d'un","Quel est le prénom du frère de Dumbledore","Abelforth","abel.jpg",6,1,"hp1.mp4");

insert into enigme (idEnigme, titreEnigme, enonceEnigme, repUnique, imageEnigme, ordre, idJeu) values (7, "Saleté de Moldu","Comment s'appelle l'horrible tante de Harry?","Petunia","petunia.jpg",7,1);

insert into enigme (idEnigme, titreEnigme, enonceEnigme, repUnique, imageEnigme, ordre, idJeu) values (8, "Pas rat pluie","Quelle est la couleur du parapluie de Hagrid?","rose","hagrid.jpg",4,1);

insert into enigme (idEnigme, titreEnigme, enonceEnigme, repUnique, imageEnigme, ordre, idJeu, videoEnigme) values (9, "Attention au départ!","Qui a créé les dragées au goût surprise vendues dans le train qui mène à Poudlarc ?","Bertie Crochue","train.jpg",9,1,"hp3.mp4");


-- Jeu 2 -- 
insert into enigme (idEnigme, titreEnigme, enonceEnigme, repUnique, imageEnigme, ordre, idJeu)values (10, "Les tickets d'or","Combien de tickets ont été caché dans des tablettes de chocolat du monde entier?","5", "ticket.jpg",1,2);

insert into enigme (idEnigme, titreEnigme, enonceEnigme, repUnique, imageEnigme, ordre, idJeu)values (11, "Moman","Quel enfant ne vient pas accompagné de l'un de ses parents?","Charlie","accompagne.jpg",2,2);

insert into enigme (idEnigme, titreEnigme, enonceEnigme, repUnique, imageEnigme, ordre, idJeu, videoEnigme)values (12, "Nom c'est nom...","Quelle est le nom de famille de Charlie ?","Bucket","famille.jpg",3,2,"charlie1.mp4");

insert into enigme (idEnigme, titreEnigme, enonceEnigme, repUnique, imageEnigme, ordre, idJeu)values (13, "Salut papa","Quel est le métier du père de Willy Wonka ?","dentiste","pere.jpg",4,2);

insert into enigme (idEnigme, titreEnigme, enonceEnigme, repUnique, imageEnigme, ordre, idJeu)values (14, "Famille nombreuse","Chez Charlie, combien de personnes dorment dans le même lit, dans la cuisine ?","4","lit.jpg",5,2);


-- -----------------------------
-- insertion mj
-- -----------------------------

insert into mj (loginMJ, mdpMJ, nomMJ) values ("mjma","mjma","Les développeuses");
-- -----------------------------
-- insertion equipe
-- -----------------------------

insert into equipe(loginEqu, mdpEqu, nbJoueurs, nomEqu, imageEqu) values ("ENSC","ensc","4","ENSC","ensc.jpg");

insert into equipe (loginEqu, mdpEqu, nbJoueurs, nomEqu, imageEqu)values ("Enseirb","enseirb","4","Enseirb matmeca","enseirb.jpg");

insert into equipe (loginEqu, mdpEqu, nbJoueurs, nomEqu, imageEqu)values ("CBP","cbp","4","Enscpb","cbp.jpg");


-- -------------------
-- insertion indices
-- -------------------
insert into indice (enonceIndice, idEnigme) values ("ça commence par Nor",1);
insert into indice (enonceIndice, idEnigme) values ("il y a autant de mâles que de femelles",2);
insert into indice (enonceIndice, idEnigme) values ("Il a beaucoup de poils, c'est un chien...",3);
insert into indice (enonceIndice, idEnigme) values ("Ce n'est peut être pas un hasard s'il fait pousser une queue de cochon à Dudley",8);
insert into indice (enonceIndice, idEnigme) values ("Une chaussette est un type de ...",5);
insert into indice (enonceIndice, idEnigme) values ("Mon 1er est le frère de Caïn, mon second est le 4eme en anglais, mon tout est ce que tu cherches",6);
insert into indice (enonceIndice, idEnigme) values ("Pourtant, c'est un joli nom de fleur",7);
insert into indice (enonceIndice, idEnigme) values ("Mon 1er est un préfixe signifiant plusieurs, mon second est un jus de fruit loin d'être 100% pur jus",4);
insert into indice (enonceIndice, idEnigme) values ("Son nom de famille qualifie un nez de sorcière",9);

insert into indice (enonceIndice, idEnigme) values ("Entre 4 et 6",10);
insert into indice (enonceIndice, idEnigme) values ("Il vient accompagné de son grand-père",11);
insert into indice (enonceIndice, idEnigme) values ("Presque comme un auteur du théâtre de l'absurde",12);
insert into indice (enonceIndice, idEnigme) values ("On aime généralement pas se retrouver dans leur cabinet",13);
insert into indice (enonceIndice, idEnigme) values ("Combien Charlie a t-il de grands-parents?",14);

