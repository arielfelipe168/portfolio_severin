<?php
// $pageTitle et $activeNav doivent être définis avant l'inclusion.
$activeNav = $activeNav ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle ?? 'Admin') ?> — Espace admin</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
  <aside class="sidebar">
    <h1>Espace admin</h1>
    <div class="sub">Connecté : <?= e($_SESSION['admin_username'] ?? '') ?></div>
    <nav>
      <a href="dashboard.php" class="<?= $activeNav === 'dashboard' ? 'active' : '' ?>">Tableau de bord</a>
      <a href="profile.php" class="<?= $activeNav === 'profile' ? 'active' : '' ?>">Profil &amp; photo</a>
      <a href="experiences.php" class="<?= $activeNav === 'experiences' ? 'active' : '' ?>">Expériences</a>
      <a href="formations.php" class="<?= $activeNav === 'formations' ? 'active' : '' ?>">Formations</a>
      <a href="competences.php" class="<?= $activeNav === 'competences' ? 'active' : '' ?>">Compétences</a>
      <a href="langues.php" class="<?= $activeNav === 'langues' ? 'active' : '' ?>">Langues</a>
      <a href="outils.php" class="<?= $activeNav === 'outils' ? 'active' : '' ?>">Outils</a>
      <a href="loisirs.php" class="<?= $activeNav === 'loisirs' ? 'active' : '' ?>">Loisirs</a>
      <a href="associations.php" class="<?= $activeNav === 'associations' ? 'active' : '' ?>">Associations</a>
    </nav>
    <div class="logout">
      <a href="../index.php" target="_blank">Voir le site ↗</a><br><br>
      <a href="logout.php">Se déconnecter</a>
    </div>
  </aside>
  <main class="main">
    <?php if (!empty($_SESSION['flash'])): ?>
      <div class="flash <?= e($_SESSION['flash']['type']) ?>"><?= e($_SESSION['flash']['message']) ?></div>
      <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
