<?php
require_once __DIR__ . '/includes/auth.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$pdo = getDB();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (attemptLogin($pdo, $username, $password)) {
        header('Location: dashboard.php');
        exit;
    }
    $error = "Identifiant ou mot de passe incorrect.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion — Espace admin</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="login-wrap">
  <form class="login-card" method="post">
    <h1>Espace administrateur</h1>
    <p>Connectez-vous pour gérer le contenu du portfolio.</p>
    <?php if ($error): ?>
      <div class="flash error"><?= e($error) ?></div>
    <?php endif; ?>
    <label for="username">Identifiant</label>
    <input type="text" id="username" name="username" required autofocus>
    <label for="password">Mot de passe</label>
    <input type="password" id="password" name="password" required>
    <button type="submit" class="btn btn-primary" style="width:100%;">Se connecter</button>
  </form>
</div>
</body>
</html>
