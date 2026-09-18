<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$pdo = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfCheck()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'save') {
        $id     = $_POST['id'] ?? '';
        $langue = trim($_POST['langue'] ?? '');
        $niveau = trim($_POST['niveau'] ?? '');
        $ordre  = (int) ($_POST['ordre'] ?? 0);

        if ($id) {
            $pdo->prepare("UPDATE langues SET langue=?, niveau=?, ordre=? WHERE id=?")
                ->execute([$langue, $niveau, $ordre, $id]);
            $msg = 'Langue mise à jour.';
        } else {
            $pdo->prepare("INSERT INTO langues (langue, niveau, ordre) VALUES (?, ?, ?)")
                ->execute([$langue, $niveau, $ordre]);
            $msg = 'Langue ajoutée.';
        }
        $_SESSION['flash'] = ['type' => 'success', 'message' => $msg];
    }

    if ($action === 'delete') {
        $pdo->prepare("DELETE FROM langues WHERE id=?")->execute([$_POST['id']]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Langue supprimée.'];
    }

    header('Location: langues.php');
    exit;
}

$editItem = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM langues WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editItem = $stmt->fetch(PDO::FETCH_ASSOC);
}

$items = fetchAll($pdo, 'langues');
$pageTitle = 'Langues';
$activeNav = 'langues';
require __DIR__ . '/includes/header.php';
?>

<h2 class="page-title">Langues</h2>
<p class="page-sub">Langues parlées et niveau de maîtrise.</p>

<form class="card" method="post">
  <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
  <input type="hidden" name="action" value="save">
  <input type="hidden" name="id" value="<?= e($editItem['id'] ?? '') ?>">

  <div class="form-row">
    <div>
      <label for="langue">Langue</label>
      <input type="text" id="langue" name="langue" value="<?= e($editItem['langue'] ?? '') ?>" required>
    </div>
    <div>
      <label for="niveau">Niveau</label>
      <input type="text" id="niveau" name="niveau" value="<?= e($editItem['niveau'] ?? '') ?>">
    </div>
  </div>
  <label for="ordre">Ordre d'affichage</label>
  <input type="text" id="ordre" name="ordre" value="<?= e((string)($editItem['ordre'] ?? count($items))) ?>">

  <button type="submit" class="btn btn-primary"><?= $editItem ? 'Mettre à jour' : 'Ajouter' ?></button>
  <?php if ($editItem): ?>
    <a href="langues.php" class="btn btn-ghost">Annuler</a>
  <?php endif; ?>
</form>

<div class="card">
  <table>
    <thead><tr><th>Langue</th><th>Niveau</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($items as $item): ?>
        <tr>
          <td><?= e($item['langue']) ?></td>
          <td><?= e($item['niveau']) ?></td>
          <td class="actions">
            <a class="btn btn-ghost" href="?edit=<?= (int)$item['id'] ?>">Modifier</a>
            <form method="post" onsubmit="return confirm('Supprimer cette langue ?');">
              <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
              <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$items): ?>
        <tr><td colspan="3">Aucune langue enregistrée.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
