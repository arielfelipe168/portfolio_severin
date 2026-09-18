<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$pdo = getDB();
$profile = fetchProfile($pdo);

$counts = [
    'Expériences'   => (int) $pdo->query("SELECT COUNT(*) FROM experiences")->fetchColumn(),
    'Formations'    => (int) $pdo->query("SELECT COUNT(*) FROM formations")->fetchColumn(),
    'Compétences'   => (int) $pdo->query("SELECT COUNT(*) FROM competences")->fetchColumn(),
    'Langues'       => (int) $pdo->query("SELECT COUNT(*) FROM langues")->fetchColumn(),
    'Loisirs'       => (int) $pdo->query("SELECT COUNT(*) FROM loisirs")->fetchColumn(),
    'Associations'  => (int) $pdo->query("SELECT COUNT(*) FROM associations")->fetchColumn(),
];

$pageTitle = 'Tableau de bord';
$activeNav = 'dashboard';
require __DIR__ . '/includes/header.php';
?>

<h2 class="page-title">Bonjour 👋</h2>
<p class="page-sub">Voici un aperçu du contenu actuellement publié sur le portfolio de <?= e($profile['nom_complet'] ?? '') ?>.</p>

<div class="card">
  <table>
    <?php foreach ($counts as $label => $count): ?>
      <tr><td><?= e($label) ?></td><td style="text-align:right; font-weight:700;"><?= $count ?></td></tr>
    <?php endforeach; ?>
  </table>
</div>

<div class="card">
  <p style="margin:0 0 12px; font-weight:600;">Accès rapides</p>
  <div class="actions" style="flex-wrap:wrap;">
    <a class="btn btn-ghost" href="profile.php">Modifier le profil / la photo</a>
    <a class="btn btn-ghost" href="experiences.php">Ajouter une expérience</a>
    <a class="btn btn-ghost" href="formations.php">Ajouter une formation</a>
    <a class="btn btn-ghost" href="competences.php">Gérer les compétences</a>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
