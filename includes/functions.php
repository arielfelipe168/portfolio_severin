<?php

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function fetchProfile(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT * FROM profile WHERE id = 1");
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
}

function fetchAll(PDO $pdo, string $table, string $orderBy = 'ordre'): array
{
    return $pdo->query("SELECT * FROM $table ORDER BY $orderBy ASC")->fetchAll(PDO::FETCH_ASSOC);
}

function fetchCompetences(PDO $pdo, string $categorie): array
{
    $stmt = $pdo->prepare("SELECT * FROM competences WHERE categorie = ? ORDER BY ordre ASC");
    $stmt->execute([$categorie]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Gère l'upload sécurisé d'une photo de profil.
 * Retourne le nom du fichier stocké, ou null en cas d'erreur/absence.
 */
function handlePhotoUpload(array $file): ?string
{
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!isset($allowed[$mime])) {
        return null;
    }

    $ext = $allowed[$mime];
    $filename = 'profile_' . time() . '.' . $ext;
    $destDir = __DIR__ . '/../assets/uploads/';
    if (!is_dir($destDir)) {
        mkdir($destDir, 0777, true);
    }

    if (move_uploaded_file($file['tmp_name'], $destDir . $filename)) {
        return $filename;
    }

    return null;
}
