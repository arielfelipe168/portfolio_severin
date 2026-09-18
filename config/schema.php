<?php
/**
 * Création des tables + données initiales tirées du CV fourni.
 * Ce script ne s'exécute qu'une seule fois, à la toute première connexion.
 */

function installSchema(PDO $pdo): void
{
    $pdo->exec("
        CREATE TABLE admin_users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password_hash TEXT NOT NULL
        );

        CREATE TABLE profile (
            id INTEGER PRIMARY KEY CHECK (id = 1),
            nom_complet TEXT,
            titre TEXT,
            photo TEXT,
            bio TEXT,
            date_naissance TEXT,
            lieu_naissance TEXT,
            nationalite TEXT,
            statut_civil TEXT,
            telephone TEXT,
            telephone2 TEXT,
            email TEXT,
            adresse TEXT,
            mobilite TEXT
        );

        CREATE TABLE experiences (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            periode TEXT NOT NULL,
            poste TEXT NOT NULL,
            structure TEXT,
            ordre INTEGER DEFAULT 0
        );

        CREATE TABLE formations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            annee TEXT NOT NULL,
            diplome TEXT NOT NULL,
            etablissement TEXT,
            ordre INTEGER DEFAULT 0
        );

        CREATE TABLE competences (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            categorie TEXT NOT NULL, -- 'technique' ou 'transversale'
            libelle TEXT NOT NULL,
            ordre INTEGER DEFAULT 0
        );

        CREATE TABLE langues (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            langue TEXT NOT NULL,
            niveau TEXT,
            ordre INTEGER DEFAULT 0
        );

        CREATE TABLE outils (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            libelle TEXT NOT NULL,
            ordre INTEGER DEFAULT 0
        );

        CREATE TABLE loisirs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            libelle TEXT NOT NULL,
            ordre INTEGER DEFAULT 0
        );

        CREATE TABLE associations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            libelle TEXT NOT NULL,
            ordre INTEGER DEFAULT 0
        );
    ");

    // --- Compte admin par défaut : identifiant admin / mot de passe admin123 ---
    $stmt = $pdo->prepare("INSERT INTO admin_users (username, password_hash) VALUES (?, ?)");
    $stmt->execute(['admin', password_hash('admin123', PASSWORD_DEFAULT)]);

    // --- Profil ---
    $pdo->prepare("INSERT INTO profile (id, nom_complet, titre, photo, bio, date_naissance, lieu_naissance,
            nationalite, statut_civil, telephone, telephone2, email, adresse, mobilite)
            VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")
        ->execute([
            'Ossoungbemi Séverin Adewalé',
            'Journaliste, Producteur, Reporter Sportif, Animateur, Présentateur, Maître de Cérémonie',
            'profile.jpeg',
            "Journaliste polyvalent spécialisé dans le reportage sportif, la production audiovisuelle et l'animation. Correspondant BBC Afrique à Lomé, passionné par la couverture de grands événements sportifs, culturels et politiques.",
            '24/11/1998',
            'Kétou',
            'Béninoise',
            'Marié',
            '01 61 91 07 26',
            '01 65 17 09 60',
            'adewale98puissant@gmail.com',
            'Sèmè, Abomey-Calavi',
            'Nationale',
        ]);

    // --- Expériences professionnelles ---
    $experiences = [
        ['Depuis juin 2025', 'Correspondant BBC Afrique', 'Lomé, Togo'],
        ['Juillet 2024 - Décembre 2025', 'Responsable Média', 'LégiBénin'],
        ['Avril 2023 - Avril 2025', 'Journaliste-Rédacteur', 'Africa Foot United'],
        ['Mars 2022 - Juillet 2024', 'Journaliste', 'First Afrique TV'],
        ['2022', 'Journaliste Stagiaire', 'CAPP FM'],
        ['2020 - 2021', 'Journaliste, division des sports', 'Radio Tokpa'],
        ['2019 - 2020', 'Journaliste', 'Journal sportif « Le Champion » et « Bénin Sports »'],
        ['2018 - 2019', 'Journaliste', 'Radio-École APM de Soulé Issiaka'],
        ['2017 - 2018', 'Chargé de communication et membre de pilotage', 'Projet « WEZIZA », Ouémé'],
        ['2017 - 2018', 'Journaliste', 'Station universitaire Radio Univers'],
        ['2017 - 2018', 'Journaliste', 'Radio Communautaire FM Alaketu'],
    ];
    $stmt = $pdo->prepare("INSERT INTO experiences (periode, poste, structure, ordre) VALUES (?, ?, ?, ?)");
    foreach ($experiences as $i => $e) {
        $stmt->execute([$e[0], $e[1], $e[2], $i]);
    }

    // --- Formations ---
    $formations = [
        ['2019', 'Licence Professionnelle Nationale en Journalisme', "Institut Universitaire Panafricain (IUP) de Porto-Novo"],
        ['2016', 'Baccalauréat, Série A1', "Collège d'Enseignement Général I de Kétou"],
        ['2013', "Brevet d'Étude du Premier Cycle (BEPC)", "Moderne Court, Collège d'Enseignement Général I de Kétou"],
    ];
    $stmt = $pdo->prepare("INSERT INTO formations (annee, diplome, etablissement, ordre) VALUES (?, ?, ?, ?)");
    foreach ($formations as $i => $f) {
        $stmt->execute([$f[0], $f[1], $f[2], $i]);
    }

    // --- Compétences techniques ---
    $techniques = [
        'Conception et réalisation de documentaires',
        "Réalisation et présentation d'émission audio-visuelle",
        'Post de voix et commentaire sur production audio-visuelle',
        'Reportage audio-visuel',
        'Bonnes capacités de communication et de rédaction (presse écrite et audio-visuel)',
        'Voix OFF',
        "Préparation de questions et de points à investiguer",
        "Réalisation d'interviews, micro-trottoir, articles d'actualité",
        'Animation des conférences de rédaction',
        'Rédaction de brèves et de lancements radio',
        'Enregistrement audio + montage',
        'Réalisation de reportages pour le journal télévisé',
        'Interviews de personnalités politiques et artistiques',
        "Rédaction d'articles pour le site internet de la chaîne",
        "Réalisation d'enquêtes sur des sujets de société",
        "Rédaction d'articles pour différents médias",
        'Animation de conférences de presse',
        "Recherche de sujets pour l'émission",
        "Coordination des équipes sur le terrain",
        'Couverture de grandes compétitions sportives, culturelles, artistiques, politiques',
    ];
    $stmt = $pdo->prepare("INSERT INTO competences (categorie, libelle, ordre) VALUES ('technique', ?, ?)");
    foreach ($techniques as $i => $t) {
        $stmt->execute([$t, $i]);
    }

    // --- Compétences transversales ---
    $transversales = [
        'Motivation et proactivité',
        'Rigoureux et organisé',
        'Adaptation et flexibilité',
        'Discret et diplomate',
        "Esprit d'équipe",
        'Sens de la collaboration',
    ];
    $stmt = $pdo->prepare("INSERT INTO competences (categorie, libelle, ordre) VALUES ('transversale', ?, ?)");
    foreach ($transversales as $i => $t) {
        $stmt->execute([$t, $i]);
    }

    // --- Langues ---
    $langues = [
        ['Français', 'Très bonne maîtrise'],
        ['Anglais', 'Bien'],
        ['Yoruba', 'Maternelle'],
        ['Nagot', 'Maternelle'],
    ];
    $stmt = $pdo->prepare("INSERT INTO langues (langue, niveau, ordre) VALUES (?, ?, ?)");
    foreach ($langues as $i => $l) {
        $stmt->execute([$l[0], $l[1], $i]);
    }

    // --- Outils informatiques ---
    $outils = ['Word', 'Excel', 'PowerPoint'];
    $stmt = $pdo->prepare("INSERT INTO outils (libelle, ordre) VALUES (?, ?)");
    foreach ($outils as $i => $o) {
        $stmt->execute([$o, $i]);
    }

    // --- Loisirs ---
    $loisirs = ['Musique', 'Lecture', 'Football', 'Voyage'];
    $stmt = $pdo->prepare("INSERT INTO loisirs (libelle, ordre) VALUES (?, ?)");
    foreach ($loisirs as $i => $l) {
        $stmt->execute([$l, $i]);
    }

    // --- Associations ---
    $associations = [
        'Conseiller Juridique de la JCI Porto-Novo Impact, mandat 2025',
        "Membre de l'Association de la Presse Sportive du Bénin (APS-Bénin), depuis 2023",
    ];
    $stmt = $pdo->prepare("INSERT INTO associations (libelle, ordre) VALUES (?, ?)");
    foreach ($associations as $i => $a) {
        $stmt->execute([$a, $i]);
    }
}
