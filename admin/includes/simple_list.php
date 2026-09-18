<?php
/**
 * Gabarit générique pour les tables à un seul champ "libelle"
 * (outils, loisirs, associations). Les pages appelantes définissent
 * $table, $pageTitle, $activeNav, $label et $inputLabel avant d'inclure ce fichier.
 */
require_once __DIR__ . '/auth.php';
requireLogin();
$pdo = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfCheck()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'save') {
        $id      = $_POST['id'] ?? '';
        $libelle = trim($_POST['libelle'] ?? '');
        $ordre   = (int) ($_POST['ordre'] ?? 0);

        if ($id) {
            $pdo->prepare("UPDATE $table SET libelle=?, ordre=? WHERE id=?")->execute([$libelle, $ordre, $id]);
            $msg = ucfirst($label) . ' mis à jour.';
        } else {
            $pdo->prepare("INSERT INTO $table (libelle, ordre) VALUES (?, ?)")->execute([$libelle, $ordre]);
            $msg = ucfirst($label) . ' ajouté.';
        }
        $_SESSION['flash'] = ['type' => 'success', 'message' => $msg];
    }

    if ($action === 'delete') {
        $pdo->prepare("DELETE FROM $table WHERE id=?")->execute([$_POST['id']]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => ucfirst($label) . ' supprimé.'];
    }

    header('Location: ' . basename($_SERVER['PHP_SELF']));
    exit;
}

$editItem = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editItem = $stmt->fetch(PDO::FETCH_ASSOC);
}

$items = fetchAll($pdo, $table);
require __DIR__ . '/header.php';
?>

<h2 class="page-title"><?= e($pageTitle) ?></h2>
<p class="page-sub"><?= e($pageDescription ?? '') ?></p>

<form class="card" method="post">
  <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
  <input type="hidden" name="action" value="save">
  <input type="hidden" name="id" value="<?= e($editItem['id'] ?? '') ?>">

  <label for="libelle"><?= e($inputLabel) ?></label>
  <input type="text" id="libelle" name="libelle" value="<?= e($editItem['libelle'] ?? '') ?>" required>
  <label for="ordre">Ordre d'affichage</label>
  <input type="text" id="ordre" name="ordre" value="<?= e((string)($editItem['ordre'] ?? count($items))) ?>">

  <button type="submit" class="btn btn-primary"><?= $editItem ? 'Mettre à jour' : 'Ajouter' ?></button>
  <?php if ($editItem): ?>
    <a href="<?= e(basename($_SERVER['PHP_SELF'])) ?>" class="btn btn-ghost">Annuler</a>
  <?php endif; ?>
</form>

<div class="card">
  <table>
    <thead><tr><th><?= e($inputLabel) ?></th><th></th></tr></thead>
    <tbody>
      <?php foreach ($items as $item): ?>
        <tr>
          <td><?= e($item['libelle']) ?></td>
          <td class="actions">
            <a class="btn btn-ghost" href="?edit=<?= (int)$item['id'] ?>">Modifier</a>
            <form method="post" onsubmit="return confirm('Supprimer cet élément ?');">
              <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
              <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$items): ?>
        <tr><td colspan="2">Aucun élément enregistré.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/footer.php'; ?>
