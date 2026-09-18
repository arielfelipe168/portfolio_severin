<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$pdo = getDB();

$categorie = ($_GET['cat'] ?? 'technique') === 'transversale' ? 'transversale' : 'technique';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfCheck()) {
    $action = $_POST['action'] ?? '';
    $postCat = ($_POST['categorie'] ?? 'technique') === 'transversale' ? 'transversale' : 'technique';

    if ($action === 'save') {
        $id      = $_POST['id'] ?? '';
        $libelle = trim($_POST['libelle'] ?? '');
        $ordre   = (int) ($_POST['ordre'] ?? 0);

        if ($id) {
            $pdo->prepare("UPDATE competences SET libelle=?, ordre=? WHERE id=?")->execute([$libelle, $ordre, $id]);
            $msg = 'Compétence mise à jour.';
        } else {
            $pdo->prepare("INSERT INTO competences (categorie, libelle, ordre) VALUES (?, ?, ?)")
                ->execute([$postCat, $libelle, $ordre]);
            $msg = 'Compétence ajoutée.';
        }
        $_SESSION['flash'] = ['type' => 'success', 'message' => $msg];
    }

    if ($action === 'delete') {
        $pdo->prepare("DELETE FROM competences WHERE id=?")->execute([$_POST['id']]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Compétence supprimée.'];
    }

    header('Location: competences.php?cat=' . $postCat);
    exit;
}

$editItem = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM competences WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editItem = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($editItem) $categorie = $editItem['categorie'];
}

$items = fetchCompetences($pdo, $categorie);
$pageTitle = 'Compétences';
$activeNav = 'competences';
require __DIR__ . '/includes/header.php';
?>

<h2 class="page-title">Compétences</h2>
<p class="page-sub">Gérez séparément les compétences techniques et transversales.</p>

<div class="tabs">
  <a href="?cat=technique" class="<?= $categorie === 'technique' ? 'active' : '' ?>">Techniques</a>
  <a href="?cat=transversale" class="<?= $categorie === 'transversale' ? 'active' : '' ?>">Transversales</a>
</div>

<form class="card" method="post">
  <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
  <input type="hidden" name="action" value="save">
  <input type="hidden" name="id" value="<?= e($editItem['id'] ?? '') ?>">
  <input type="hidden" name="categorie" value="<?= e($categorie) ?>">

  <label for="libelle">Libellé de la compétence</label>
  <input type="text" id="libelle" name="libelle" value="<?= e($editItem['libelle'] ?? '') ?>" required>
  <label for="ordre">Ordre d'affichage</label>
  <input type="text" id="ordre" name="ordre" value="<?= e((string)($editItem['ordre'] ?? count($items))) ?>">

  <button type="submit" class="btn btn-primary"><?= $editItem ? 'Mettre à jour' : 'Ajouter' ?></button>
  <?php if ($editItem): ?>
    <a href="competences.php?cat=<?= e($categorie) ?>" class="btn btn-ghost">Annuler</a>
  <?php endif; ?>
</form>

<div class="card">
  <table>
    <thead><tr><th>Libellé</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($items as $item): ?>
        <tr>
          <td><?= e($item['libelle']) ?></td>
          <td class="actions">
            <a class="btn btn-ghost" href="?edit=<?= (int)$item['id'] ?>&cat=<?= e($categorie) ?>">Modifier</a>
            <form method="post" onsubmit="return confirm('Supprimer cette compétence ?');">
              <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
              <input type="hidden" name="categorie" value="<?= e($categorie) ?>">
              <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$items): ?>
        <tr><td colspan="2">Aucune compétence enregistrée.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
