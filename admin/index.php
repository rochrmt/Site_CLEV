<?php
require __DIR__ . '/config.php';
require __DIR__ . '/layout.php';
admin_require();

$c = clev_content();

admin_head('Tableau de bord');
?>
      <h1>Tableau de bord</h1>
      <p class="page-sub">Gérez tout le contenu du site CLEV Beauty & Spa.</p>

      <div class="tiles">
        <a class="tile" href="texts.php">
          <strong>Textes du site</strong>
          <small>Hero, manifesto, expérience, citations, formulaire…</small>
        </a>
        <a class="tile" href="services.php">
          <strong>Services</strong>
          <small><?= count($c['services']) ?> services — modifier, ajouter, supprimer</small>
        </a>
        <a class="tile" href="gallery.php">
          <strong>Galerie</strong>
          <small><?= count($c['gallery']) ?> images — légendes, ordre, upload</small>
        </a>
        <a class="tile" href="hero.php">
          <strong>Images du hero</strong>
          <small><?= count($c['hero']['images']) ?> images dans le carousel</small>
        </a>
        <a class="tile" href="contact.php">
          <strong>Contact & réglages</strong>
          <small>Téléphones, adresse, WhatsApp, réseaux sociaux</small>
        </a>
        <a class="tile" href="../index.php" target="_blank">
          <strong>Voir le site ↗</strong>
          <small>Ouvrir le site dans un nouvel onglet</small>
        </a>
      </div>
<?php admin_foot(); ?>
