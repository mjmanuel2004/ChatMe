-- =============================================================================
-- Script de Création de la Base de Données pour Projet de Réseau Social
-- =============================================================================
-- Auteur:       MONSAN Josue (Modèle initial), Gemini (Finalisation)
-- Version:      4.0 (Ajout de réponses aux commentaires, vues des stories, blocages et index)
-- SGDB Cible:   MySQL / MariaDB
-- =============================================================================

-- Pour permettre de relancer le script sans erreurs, on supprime les tables existantes.
-- L'ordre de suppression est l'inverse de l'ordre de création pour respecter les dépendances.
DROP TABLE IF EXISTS Piste_Audit;
DROP TABLE IF EXISTS Signalement;
DROP TABLE IF EXISTS Notification;
DROP TABLE IF EXISTS Message;
DROP TABLE IF EXISTS Participants_Conversation;
DROP TABLE IF EXISTS Conversation;
DROP TABLE IF EXISTS Tags_Publication;
DROP TABLE IF EXISTS Publication_Hashtag;
DROP TABLE IF EXISTS Hashtag;
DROP TABLE IF EXISTS Publications_Sauvegardees;
DROP TABLE IF EXISTS Likes;
DROP TABLE IF EXISTS Vues_Story;
DROP TABLE IF EXISTS Blocages;
DROP TABLE IF EXISTS Commentaire;
DROP TABLE IF EXISTS Story;
DROP TABLE IF EXISTS Publication;
DROP TABLE IF EXISTS Abonnements;
DROP TABLE IF EXISTS Utilisateur;


-- ===============================================================
-- Table 1 : Utilisateur
-- Stocke les informations de base des utilisateurs.
-- ===============================================================
CREATE TABLE Utilisateur (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    pseudo VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    -- Colonne pour stocker le HACHAGE du mot de passe, JAMAIS le mot de passe en clair.
    mot_de_passe VARCHAR(255) NOT NULL,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    photo_profil_url VARCHAR(255),
    biographie TEXT,
    date_creation_compte DATETIME DEFAULT CURRENT_TIMESTAMP,
    -- On pourrait ajouter un statut : 'actif', 'banni', 'supprime'
    statut ENUM('actif', 'banni', 'supprime') DEFAULT 'actif'
) ENGINE=InnoDB;


-- ===============================================================
-- Table 2 : Abonnements
-- Table d'association pour gérer les relations "suiveur-suivi".
-- ===============================================================
CREATE TABLE Abonnements (
    id_suiveur INT NOT NULL,
    id_suivi INT NOT NULL,
    date_abonnement DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id_suiveur, id_suivi),
    FOREIGN KEY (id_suiveur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE,
    FOREIGN KEY (id_suivi) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE
) ENGINE=InnoDB;


-- ===============================================================
-- Table 3 : Publication
-- Stocke les publications (posts) des utilisateurs.
-- ===============================================================
CREATE TABLE Publication (
    id_publication INT AUTO_INCREMENT PRIMARY KEY,
    id_auteur INT NOT NULL,
    contenu_texte TEXT,
    media_url VARCHAR(255), -- Chemin vers une image ou vidéo
    date_publication DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_auteur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE
) ENGINE=InnoDB;


-- ===============================================================
-- Table 4 : Story
-- Stocke les stories qui ont une durée de vie limitée.
-- ===============================================================
CREATE TABLE Story (
    id_story INT AUTO_INCREMENT PRIMARY KEY,
    id_auteur INT NOT NULL,
    media_url VARCHAR(255) NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_auteur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE
) ENGINE=InnoDB;


-- ===============================================================
-- Table 5 : Commentaire
-- Stocke les commentaires faits sur les publications.
-- ===============================================================
CREATE TABLE Commentaire (
    id_commentaire INT AUTO_INCREMENT PRIMARY KEY,
    id_publication INT NOT NULL,
    id_auteur INT NOT NULL,
    contenu TEXT NOT NULL,
    date_commentaire DATETIME DEFAULT CURRENT_TIMESTAMP,
    -- NOUVEAU: Pour gérer les réponses aux commentaires (commentaires imbriqués)
    id_commentaire_parent INT NULL,
    
    FOREIGN KEY (id_publication) REFERENCES Publication(id_publication) ON DELETE CASCADE,
    FOREIGN KEY (id_auteur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE,
    -- NOUVEAU: La clé étrangère pointe sur la même table pour les réponses
    FOREIGN KEY (id_commentaire_parent) REFERENCES Commentaire(id_commentaire) ON DELETE CASCADE
) ENGINE=InnoDB;


-- ===============================================================
-- Table 6 : Likes
-- Table d'association pour les "likes" sur les publications.
-- ===============================================================
CREATE TABLE Likes (
    id_utilisateur INT NOT NULL,
    id_publication INT NOT NULL,
    date_like DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id_utilisateur, id_publication),
    FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE,
    FOREIGN KEY (id_publication) REFERENCES Publication(id_publication) ON DELETE CASCADE
) ENGINE=InnoDB;


-- ===============================================================
-- Table 7 : Publications_Sauvegardees
-- Table d'association pour les publications sauvegardées par les utilisateurs.
-- ===============================================================
CREATE TABLE Publications_Sauvegardees (
    id_utilisateur INT NOT NULL,
    id_publication INT NOT NULL,
    date_sauvegarde DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id_utilisateur, id_publication),
    FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE,
    FOREIGN KEY (id_publication) REFERENCES Publication(id_publication) ON DELETE CASCADE
) ENGINE=InnoDB;


-- ===============================================================
-- Table 8 : Hashtag
-- Dictionnaire de tous les hashtags utilisés.
-- ===============================================================
CREATE TABLE Hashtag (
    id_hashtag INT AUTO_INCREMENT PRIMARY KEY,
    nom_hashtag VARCHAR(100) UNIQUE NOT NULL
) ENGINE=InnoDB;


-- ===============================================================
-- Table 9 : Publication_Hashtag
-- Table d'association qui lie les publications aux hashtags.
-- ===============================================================
CREATE TABLE Publication_Hashtag (
    id_publication INT NOT NULL,
    id_hashtag INT NOT NULL,
    
    PRIMARY KEY (id_publication, id_hashtag),
    FOREIGN KEY (id_publication) REFERENCES Publication(id_publication) ON DELETE CASCADE,
    FOREIGN KEY (id_hashtag) REFERENCES Hashtag(id_hashtag) ON DELETE CASCADE
) ENGINE=InnoDB;


-- ===============================================================
-- Table 10 : Tags_Publication
-- Gère les utilisateurs identifiés ("tagués") dans une publication.
-- ===============================================================
CREATE TABLE Tags_Publication (
    id_publication INT NOT NULL,
    id_utilisateur_tague INT NOT NULL,
    date_tag DATETIME DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_publication, id_utilisateur_tague),
    FOREIGN KEY (id_publication) REFERENCES Publication(id_publication) ON DELETE CASCADE,
    FOREIGN KEY (id_utilisateur_tague) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE
) ENGINE=InnoDB;


-- ===============================================================
-- Table 11 : Blocages (NOUVEAU)
-- Gère les relations de blocage entre utilisateurs.
-- ===============================================================
CREATE TABLE Blocages (
    id_bloqueur INT NOT NULL,
    id_bloque INT NOT NULL,
    date_blocage DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_bloqueur, id_bloque),
    FOREIGN KEY (id_bloqueur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE,
    FOREIGN KEY (id_bloque) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE
) ENGINE=InnoDB;


-- ===============================================================
-- Table 12 : Vues_Story (NOUVEAU)
-- Journalise les vues des stories par les utilisateurs.
-- ===============================================================
CREATE TABLE Vues_Story (
    id_story INT NOT NULL,
    id_utilisateur_vue INT NOT NULL,
    date_vue DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_story, id_utilisateur_vue),
    FOREIGN KEY (id_story) REFERENCES Story(id_story) ON DELETE CASCADE,
    FOREIGN KEY (id_utilisateur_vue) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE
) ENGINE=InnoDB;


-- ===============================================================
-- Tables pour la Messagerie
-- ===============================================================

-- Table 13 : Conversation
CREATE TABLE Conversation (
    id_conversation INT AUTO_INCREMENT PRIMARY KEY,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    titre_conversation VARCHAR(255) -- Utile pour les discussions de groupe
) ENGINE=InnoDB;

-- Table 14 : Participants_Conversation
CREATE TABLE Participants_Conversation (
    id_conversation INT NOT NULL,
    id_utilisateur INT NOT NULL,
    date_adhesion DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id_conversation, id_utilisateur),
    FOREIGN KEY (id_conversation) REFERENCES Conversation(id_conversation) ON DELETE CASCADE,
    FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table 15 : Message
CREATE TABLE Message (
    id_message INT AUTO_INCREMENT PRIMARY KEY,
    id_conversation INT NOT NULL,
    id_expediteur INT NOT NULL,
    contenu TEXT NOT NULL,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_conversation) REFERENCES Conversation(id_conversation) ON DELETE CASCADE,
    FOREIGN KEY (id_expediteur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE
) ENGINE=InnoDB;


-- ===============================================================
-- Tables pour le Système, la Modération et l'Audit
-- ===============================================================

-- Table 16 : Notification
CREATE TABLE Notification (
    id_notification INT AUTO_INCREMENT PRIMARY KEY,
    id_destinataire INT NOT NULL,
    type_notification ENUM('nouveau_like', 'nouveau_commentaire', 'nouvel_abonne', 'message_prive', 'tag_publication', 'reponse_commentaire') NOT NULL,
    id_source INT, 
    est_lue BOOLEAN DEFAULT FALSE,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_destinataire) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE
) ENGINE=InnoDB;


-- Table 17 : Signalement
CREATE TABLE Signalement (
    id_signalement INT AUTO_INCREMENT PRIMARY KEY,
    id_rapporteur INT NOT NULL,
    id_contenu_signale INT NOT NULL,
    type_contenu ENUM('publication', 'commentaire', 'utilisateur', 'story') NOT NULL,
    motif TEXT NOT NULL,
    date_signalement DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('en_attente', 'traite_accepte', 'traite_rejete') DEFAULT 'en_attente',
    
    FOREIGN KEY (id_rapporteur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE
) ENGINE=InnoDB;


-- Table 18 : Piste_Audit
-- Journal général pour les actions importantes dans l'application.
CREATE TABLE Piste_Audit (
    id_audit INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur_acteur INT,
    type_action VARCHAR(50) NOT NULL,
    table_concernee VARCHAR(50),
    id_enregistrement_concerne INT,
    details_action TEXT,
    date_action DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_utilisateur_acteur) REFERENCES Utilisateur(id_utilisateur) ON DELETE SET NULL
) ENGINE=InnoDB;


-- ===============================================================
-- Section 19 : Optimisations avec les Index (NOUVEAU)
-- Ajout d'index sur les clés étrangères et colonnes fréquemment
-- utilisées dans les recherches pour améliorer les performances.
-- ===============================================================
CREATE INDEX idx_publication_auteur ON Publication(id_auteur);
CREATE INDEX idx_commentaire_publication ON Commentaire(id_publication);
CREATE INDEX idx_commentaire_auteur ON Commentaire(id_auteur);
CREATE INDEX idx_likes_publication ON Likes(id_publication);
CREATE INDEX idx_message_conversation ON Message(id_conversation);
CREATE INDEX idx_tags_publication ON Tags_Publication(id_publication);
CREATE INDEX idx_notification_destinataire ON Notification(id_destinataire);


-- =============================================================================
-- Fin du script.
-- ============================================================================= 