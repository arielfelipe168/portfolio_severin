<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$pdo = getDB();

// --- Actions ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfCheck()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'save') {
        $id       = $_POST['id'] ?? '';
        $periode  = trim($_POST['periode'] ?? '');
        $poste    = trim($_POST['poste'] ?? '');
        $structure = trim($_POST['structure'] ?? '');
        $ordre    = (int) ($_POST['ordre'] ?? 0);

        if ($id) {
            $pdo->prepare("UPDATE experiences SET periode=?, poste=?, structure=?, ordre=? WHERE id=?")
                ->execute([$periode, $poste, $structure, $ordre, $id]);
            $msg = 'Expérience mise à jour.';
        } else {
            $pdo->prepare("INSERT INTO experiences (periode, poste, structure, ordre) VALUES (?, ?, ?, ?)")
                ->execute([$periode, $poste, $structure, $ordre]);
            $msg = 'Expérience ajoutée.';
        }
        $_SESSION['flash'] = ['type' => 'success', 'message' => $msg];
    }

    if ($action === 'delete') {
        $pdo->prepare("DELETE FROM experiences WHERE id=?")->execute([$_POST['id']]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Expérience supprimée.'];
    }

    header('Location: experiences.php');
    exit;
}

$editItem = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM experiences WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editItem = $stmt->fetch(PDO::FETCH_ASSOC);
}

$items = fetchAll($pdo, 'experiences');
$pageTitle = 'Expériences professionnelles';
$activeNav = 'experiences';
require __DIR__ . '/includes/header.php';
?>

<h2 class="page-title">Expériences professionnelles</h2>
<p class="page-sub">Ajoutez, modifiez ou supprimez les expériences affichées sur la ligne du temps du site.</p>

<form class="card" method="post">
  <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
  <input type="hidden" name="action" value="save">
  <input type="hidden" name="id" value="<?= e($editItem['id'] ?? '') ?>">

  <div class="form-row">
    <div>
      <label for="periode">Période (ex : Mars 2022 - Juillet 2024)</label>
      <input type="text" id="periode" name="periode" value="<?= e($editItem['periode'] ?? '') ?>" required>
    </div>
    <div>
      <label for="ordre">Ordre d'affichage</label>
      <input type="text" id="ordre" name="ordre" value="<?= e((string)($editItem['ordre'] ?? count($items))) ?>">
    </div>
  </div>
  <label for="poste">Poste</label>
  <input type="text" id="poste" name="poste" value="<?= e($editItem['poste'] ?? '') ?>" required>
  <label for="structure">Structure / organisation</label>
  <input type="text" id="structure" name="structure" value="<?= e($editItem['structure'] ?? '') ?>">

  <button type="submit" class="btn btn-primary"><?= $editItem ? 'Mettre à jour' : 'Ajouter' ?></button>
  <?php if ($editItem): ?>
    <a href="experiences.php" class="btn btn-ghost">Annuler</a>
  <?php endif; ?>
</form>

<div class="card">
  <table>
    <thead><tr><th>Période</th><th>Poste</th><th>Structure</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($items as $item): ?>
        <tr>
          <td><?= e($item['periode']) ?></td>
          <td><?= e($item['poste']) ?></td>
          <td><?= e($item['structure']) ?></td>
          <td class="actions">
            <a class="btn btn-ghost" href="?edit=<?= (int)$item['id'] ?>">Modifier</a>
            <form method="post" onsubmit="return confirm('Supprimer cette expérience ?');">
              <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
              <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$items): ?>
        <tr><td colspan="4">Aucune expérience enregistrée.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
