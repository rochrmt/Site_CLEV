<?php
require __DIR__ . '/config.php';

$error = null;

if (admin_logged()) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $pwd = $_POST['password'] ?? '';
    if (password_verify($pwd, ADMIN_PASSWORD_HASH)) {
        session_regenerate_id(true);
        $_SESSION['clev_admin'] = true;
        header('Location: index.php');
        exit;
    }
    $error = 'Mot de passe incorrect.';
}
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Connexion — Admin CLEV</title>
    <link rel="stylesheet" href="admin.css" />
    <style>body{display:grid;grid-template-columns:1fr;}</style>
  </head>
  <body>
    <div class="login-wrap">
      <form class="login-box" method="post">
        <a class="admin-brand" href="../index.php" style="color:var(--ink);margin-bottom:24px;">
          <strong>CLEV</strong>
          <small>Administration</small>
        </a>
        <?php if ($error): ?>
          <div class="flash flash-error"><?= h($error) ?></div>
        <?php endif; ?>
        <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>" />
        <div class="field">
          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="password" required autofocus />
        </div>
        <button class="btn" type="submit" style="width:100%;justify-content:center;">Se connecter</button>
      </form>
    </div>
  </body>
</html>
