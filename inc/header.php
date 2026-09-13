<?php
/* En-tête partagé : <head> + header du site.
   Attend : $c (contenu), $pageTitle, $pageDesc */
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="<?= h($pageDesc) ?>" />
    <meta name="theme-color" content="#f6f1ea" />
    <title><?= h($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Italiana&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="styles.css" />
    <script src="script.js" defer></script>
  </head>
  <body>
    <a class="skip-link" href="#main">Aller au contenu</a>

    <header class="site-header" data-header>
      <a class="brand" href="index.php" aria-label="CLEV Beauty & Spa — Accueil">
        <img src="assets/images/clev-logo.jpg" alt="" />
        <span>
          <strong>CLEV</strong>
          <small>Beauty & Spa</small>
        </span>
      </a>

      <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav">
        <span></span>
        <span></span>
        <span></span>
        <span class="sr-only">Ouvrir le menu</span>
      </button>

      <nav id="main-nav" class="main-nav" aria-label="Navigation principale">
        <a href="index.php#services">Nos soins</a>
        <a href="index.php#experience">L'institut</a>
        <a href="index.php#galerie">Galerie</a>
        <a href="index.php#contact">Contact</a>
      </nav>

      <a class="header-cta" href="index.php#reservation">Prendre rendez-vous</a>
    </header>
