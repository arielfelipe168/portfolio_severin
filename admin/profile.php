<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$pdo = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfCheck()) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Session expirée, merci de réessayer.'];
        header('Location: profile.php');
        exit;
    }

    $fields = [
        'nom_complet'     => trim($_POST['nom_complet'] ?? ''),
        'titre'           => trim($_POST['titre'] ?? ''),
        'bio'             => trim($_POST['bio'] ?? ''),
        'date_naissance'  => trim($_POST['date_naissance'] ?? ''),
        'lieu_naissance'  => trim($_POST['lieu_naissance'] ?? ''),
        'nationalite'     => trim($_POST['nationalite'] ?? ''),
        'statut_civil'    => trim($_POST['statut_civil'] ?? ''),
        'telephone'       => trim($_POST['telephone'] ?? ''),
        'telephone2'      => trim($_POST['telephone2'] ?? ''),
        'email'           => trim($_POST['email'] ?? ''),
        'adresse'         => trim($_POST['adresse'] ?? ''),
        'mobilite'        => trim($_POST['mobilite'] ?? ''),
    ];

    $newPhoto = handlePhotoUpload($_FILES['photo'] ?? []);

    $sql = "UPDATE profile SET nom_complet=?, titre=?, bio=?, date_naissance=?, lieu_naissance=?,
            nationalite=?, statut_civil=?, telephone=?, telephone2=?, email=?, adresse=?, mobilite=?";
    $params = array_values($fields);

    if ($newPhoto) {
        $sql .= ", photo=?";
        $params[] = $newPhoto;
    }
    $sql .= " WHERE id=1";

    $pdo->prepare($sql)->execute($params);

    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Profil mis à jour avec succès.'];
    header('Location: profile.php');
    exit;
}

$profile = fetchProfile($pdo);
$pageTitle = 'Profil & photo';
$activeNav = 'profile';
require __DIR__ . '/includes/header.php';

$photoPath = !empty($profile['photo']) && file_exists(__DIR__ . '/../assets/uploads/' . $profile['photo'])
    ? '../assets/uploads/' . $profile['photo']
    : 'https://placehold.co/240x300?text=Photo';
?>

<h2 class="page-title">Profil &amp; photo</h2>
<p class="page-sub">Ces informations alimentent l'en-tête et la section "Profil" du site public.</p>

<form class="card" method="post" enctype="multipart/form-data">
  <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">

  <img src="<?= e($photoPath) ?>" alt="Photo actuelle" class="photo-preview">
  <label for="photo">Changer la photo (JPG, PNG ou WEBP)</label>
  <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp">

  <div class="form-row">
    <div>
      <label for="nom_complet">Nom complet</label>
      <input type="text" id="nom_complet" name="nom_complet" value="<?= e($profile['nom_complet']) ?>" required>
    </div>
    <div>
      <label for="titre">Titre / fonction(s)</label>
      <input type="text" id="titre" name="titre" value="<?= e($profile['titre']) ?>">
    </div>
  </div>

  <label for="bio">Présentation (bio)</label>
  <textarea id="bio" name="bio"><?= e($profile['bio']) ?></textarea>

  <div class="form-row">
    <div>
      <label for="date_naissance">Date de naissance</label>
      <input type="text" id="date_naissance" name="date_naissance" value="<?= e($profile['date_naissance']) ?>">
    </div>
    <div>
      <label for="lieu_naissance">Lieu de naissance</label>
      <input type="text" id="lieu_naissance" name="lieu_naissance" value="<?= e($profile['lieu_naissance']) ?>">
    </div>
  </div>

  <div class="form-row">
    <div>
      <label for="nationalite">Nationalité</label>
      <input type="text" id="nationalite" name="nationalite" value="<?= e($profile['nationalite']) ?>">
    </div>
    <div>
      <label for="statut_civil">Statut civil</label>
      <input type="text" id="statut_civil" name="statut_civil" value="<?= e($profile['statut_civil']) ?>">
    </div>
  </div>

  <div class="form-row">
    <div>
      <label for="telephone">Téléphone principal</label>
      <input type="text" id="telephone" name="telephone" value="<?= e($profile['telephone']) ?>">
    </div>
    <div>
      <label for="telephone2">Téléphone secondaire</label>
      <input type="text" id="telephone2" name="telephone2" value="<?= e($profile['telephone2']) ?>">
    </div>
  </div>

  <div class="form-row">
    <div>
      <label for="email">Email</label>
      <input type="email" id="email" name="email" value="<?= e($profile['email']) ?>">
    </div>
    <div>
      <label for="mobilite">Mobilité géographique</label>
      <input type="text" id="mobilite" name="mobilite" value="<?= e($profile['mobilite']) ?>">
    </div>
  </div>

  <label for="adresse">Adresse</label>
  <input type="text" id="adresse" name="adresse" value="<?= e($profile['adresse']) ?>">

  <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
</form>

<?php require __DIR__ . '/includes/footer.php'; ?>
