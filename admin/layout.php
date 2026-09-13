<?php
/* Layout partagé de l'admin. Attend : $adminTitle */
function admin_head(string $adminTitle): void {
    ?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= h($adminTitle) ?> — Admin CLEV</title>
    <link rel="stylesheet" href="admin.css" />
  </head>
  <body>
    <aside class="sidebar">
      <a class="admin-brand" href="index.php">
        <strong>CLEV</strong>
        <small>Administration</small>
      </a>
      <nav>
        <a href="index.php">Tableau de bord</a>
        <a href="texts.php">Textes du site</a>
        <a href="services.php">Services</a>
        <a href="gallery.php">Galerie</a>
        <a href="hero.php">Images du hero</a>
        <a href="contact.php">Contact & réglages</a>
      </nav>
      <div class="sidebar-foot">
        <a href="../index.php" target="_blank">Voir le site ↗</a>
        <a href="logout.php">Déconnexion</a>
      </div>
    </aside>
    <main class="admin-main">
      <?php $f = get_flash(); if ($f): ?>
        <div class="flash flash-<?= h($f['type']) ?>"><?= h($f['msg']) ?></div>
      <?php endif; ?>
    <?php
}

function admin_foot(): void {
    ?>
    </main>
  </body>
</html>
    <?php
}
