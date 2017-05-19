CREATE database IF NOT EXISTS site character set utf8;

USE site;

CREATE TABLE IF NOT EXISTS commande (
    id_commande int(3) NOT NULL AUTO_INCREMENT,
    id_membre int(3) DEFAULT NULL,
    montant int(3) NOT NULL,
    date_enregistrement datetime NOT NULL,
    etat enum('En cours de traitement', 'Envoyée', 'Livrée') NOT NULL,
    PRIMARY KEY (id_commande)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT 2;

CREATE TABLE IF NOT EXISTS membre (
    id_membre int(3) NOT NULL AUTO_INCREMENT,
    pseudo varchar(20) NOT NULL,
    mdp varchar(32) NOT NULL,
    nom varchar(20) NOT NULL,
    prenom varchar(20) NOT NULL,
    email varchar(20) NOT NULL,
    civilite enum('m', 'f') NOT NULL,
    ville varchar(20) NOT NULL,
    code_postal int(5) UNSIGNED ZEROFILL NOT NULL,
    adresse varchar(50) NOT NULL,
    statut int(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (id_membre)
)Engine=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT 2;

CREATE TABLE IF NOT EXISTS produit (
    id_produit int(3) NOT NULL AUTO_INCREMENT,
    reference varchar(20) NOT NULL,
    categorie varchar(20) NOT NULL,
    titre varchar(100) NOT NULL,
    description text NOT NULL,
    couleur varchar(20) NOT NULL,
    taille varchar(2) NOT NULL,
    sexe enum('H', 'F', 'H/F') NOT NULL,
    photo varchar(250) NOT NULL,
    prix int(3) NOT NULL,
    stock int(3) NOT NULL,
    PRIMARY KEY (id_produit)
)Engine=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT 10;

CREATE TABLE IF NOT EXISTS detail_commande (
    id_detail_commande int(3) NOT NULL AUTO_INCREMENT,
    id_commande int(3) NOT NULL,
    id_produit int(3) NOT NULL,
    quantite int(3) NOT NULL,
    prix int(3) NOT NULL,
    PRIMARY KEY (id_detail_commande)
)Engine=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT 2;

ALTER TABLE commande ADD CONSTRAINT fk_id_membre FOREIGN KEY (id_membre) REFERENCES membre(id_membre);
ALTER TABLE detail_commande ADD CONSTRAINT fk_id_commande FOREIGN KEY (id_commande) REFERENCES commande(id_commande);
ALTER TABLE detail_commande ADD CONSTRAINT fk_id_produit FOREIGN KEY (id_produit) REFERENCES produit(id_produit);
