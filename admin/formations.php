<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$pdo = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfCheck()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'save') {
        $id            = $_POST['id'] ?? '';
        $annee         = trim($_POST['annee'] ?? '');
        $diplome       = trim($_POST['diplome'] ?? '');
        $etablissement = trim($_POST['etablissement'] ?? '');
        $ordre         = (int) ($_POST['ordre'] ?? 0);

        if ($id) {
            $pdo->prepare("UPDATE formations SET annee=?, diplome=?, etablissement=?, ordre=? WHERE id=?")
                ->execute([$annee, $diplome, $etablissement, $ordre, $id]);
            $msg = 'Formation mise à jour.';
        } else {
            $pdo->prepare("INSERT INTO formations (annee, diplome, etablissement, ordre) VALUES (?, ?, ?, ?)")
                ->execute([$annee, $diplome, $etablissement, $ordre]);
            $msg = 'Formation ajoutée.';
        }
        $_SESSION['flash'] = ['type' => 'success', 'message' => $msg];
    }

    if ($action === 'delete') {
        $pdo->prepare("DELETE FROM formations WHERE id=?")->execute([$_POST['id']]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Formation supprimée.'];
    }

    header('Location: formations.php');
    exit;
}

$editItem = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM formations WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editItem = $stmt->fetch(PDO::FETCH_ASSOC);
}

$items = fetchAll($pdo, 'formations');
$pageTitle = 'Formations';
$activeNav = 'formations';
require __DIR__ . '/includes/header.php';
?>

<h2 class="page-title">Formations</h2>
<p class="page-sub">Diplômes et établissements affichés dans la section "Formations" du site.</p>

<form class="card" method="post">
  <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
  <input type="hidden" name="action" value="save">
  <input type="hidden" name="id" value="<?= e($editItem['id'] ?? '') ?>">

  <div class="form-row">
    <div>
      <label for="annee">Année</label>
      <input type="text" id="annee" name="annee" value="<?= e($editItem['annee'] ?? '') ?>" required>
    </div>
    <div>
      <label for="ordre">Ordre d'affichage</label>
      <input type="text" id="ordre" name="ordre" value="<?= e((string)($editItem['ordre'] ?? count($items))) ?>">
    </div>
  </div>
  <label for="diplome">Diplôme</label>
  <input type="text" id="diplome" name="diplome" value="<?= e($editItem['diplome'] ?? '') ?>" required>
  <label for="etablissement">Établissement</label>
  <input type="text" id="etablissement" name="etablissement" value="<?= e($editItem['etablissement'] ?? '') ?>">

  <button type="submit" class="btn btn-primary"><?= $editItem ? 'Mettre à jour' : 'Ajouter' ?></button>
  <?php if ($editItem): ?>
    <a href="formations.php" class="btn btn-ghost">Annuler</a>
  <?php endif; ?>
</form>

<div class="card">
  <table>
    <thead><tr><th>Année</th><th>Diplôme</th><th>Établissement</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($items as $item): ?>
        <tr>
          <td><?= e($item['annee']) ?></td>
          <td><?= e($item['diplome']) ?></td>
          <td><?= e($item['etablissement']) ?></td>
          <td class="actions">
            <a class="btn btn-ghost" href="?edit=<?= (int)$item['id'] ?>">Modifier</a>
            <form method="post" onsubmit="return confirm('Supprimer cette formation ?');">
              <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
              <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$items): ?>
        <tr><td colspan="4">Aucune formation enregistrée.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
