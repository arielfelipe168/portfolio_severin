<?php
/**
 * Connexion à la base de données SQLite.
 * Aucune configuration MySQL nécessaire : tout est stocké dans un seul fichier.
 */

define('DB_PATH', __DIR__ . '/../data/portfolio.sqlite');

function getDB(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $isNew = !file_exists(DB_PATH);

        if (!is_dir(dirname(DB_PATH))) {
            mkdir(dirname(DB_PATH), 0777, true);
        }

        $pdo = new PDO('sqlite:' . DB_PATH);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('PRAGMA foreign_keys = ON');

        if ($isNew) {
            require __DIR__ . '/schema.php';
            installSchema($pdo);
        }
    }

    return $pdo;
}
