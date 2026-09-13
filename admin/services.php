<?php
require __DIR__ . '/config.php';
require __DIR__ . '/layout.php';
admin_require();

// Suppression d'un service
if (($_GET['delete'] ?? '') !== '') {
    $c = clev_content();
    $slug = $_GET['delete'];
    $c['services'] = array_values(array_filter(
        $c['services'],
        fn($s) => $s['slug'] !== $slug
    ));
    clev_save($c);
    flash('Service supprimé.');
    header('Location: services.php');
    exit;
}

// Ajout d'un service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    csrf_check();
    $c = clev_content();

    $title = trim($_POST['title'] ?? '');
    if ($title === '') {
        flash('Le titre est requis.', 'error');
        header('Location: services.php');
        exit;
    }

    $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/', '-', iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $title) ?: ''), '-'));
    if ($slug === '' || array_filter($c['services'], fn($s) => $s['slug'] === $slug)) {
        $slug .= '-' . substr(bin2hex(random_bytes(3)), 0, 4);
    }

    $image = basename($_POST['hero_image'] ?? '') ?: 'spa-room-wide.jpg';
    $maxNum = 0;
    foreach ($c['services'] as $s) {
        $maxNum = max($maxNum, (int)$s['number']);
    }

    $c['services'][] = [
        'slug'         => $slug,
        'number'       => str_pad((string)($maxNum + 1), 2, '0', STR_PAD_LEFT),
        'title'        => $title,
        'short'        => trim($_POST['short'] ?? ''),
        'lead'         => trim($_POST['short'] ?? ''),
        'detail_title' => $title,
        'detail_text'  => 'Décrivez ce service…',
        'benefits'     => ['Bénéfice 1', 'Bénéfice 2'],
        'hero_image'   => $image,
        'detail_image' => $image,
        'cta_title'    => 'Réservez ce soin',
        'icon'         => 'M12 8v16M8 12h8',
        'featured'     => false,
    ];

    clev_save($c);
    flash('Service ajouté. Complétez sa fiche.');
    header('Location: service-edit.php?slug=' . rawurlencode($slug));
    exit;
}

$c = clev_content();
$images = list_images();

admin_head('Services');
?>
      <h1>Services</h1>
      <p class="page-sub">Chaque service a sa propre page détaillée sur le site.</p>

      <?php foreach ($c['services'] as $s): ?>
      <div class="list-item">
        <img src="../assets/images/<?= h($s['hero_image']) ?>" alt="" />
        <div class="grow">
          <strong><?= h($s['title']) ?></strong>
          <small><?= h($s['short']) ?></small>
        </div>
        <div class="actions">
          <a class="btn btn-small btn-secondary" href="../service.php?slug=<?= h($s['slug']) ?>" target="_blank">Voir</a>
          <a class="btn btn-small" href="service-edit.php?slug=<?= h($s['slug']) ?>">Modifier</a>
          <a class="btn btn-small btn-danger" href="services.php?delete=<?= h($s['slug']) ?>"
             onclick="return confirm('Supprimer le service « <?= h($s['title']) ?> » ?')">Supprimer</a>
        </div>
      </div>
      <?php endforeach; ?>

      <h2>Ajouter un service</h2>
      <div class="card">
        <form method="post">
          <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>" />
          <input type="hidden" name="action" value="add" />
          <div class="grid-2">
            <div class="field">
              <label>Nom du service *</label>
              <input type="text" name="title" required placeholder="Ex. Soins capillaires" />
            </div>
            <div class="field">
              <label>Image principale</label>
              <select name="hero_image">
                <?php foreach ($images as $img): ?>
                <option value="<?= h($img) ?>"><?= h($img) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="field">
            <label>Description courte</label>
            <input type="text" name="short" placeholder="Résumé affiché sur la carte" />
          </div>
          <button class="btn" type="submit">Ajouter le service</button>
        </form>
      </div>
<?php admin_foot(); ?>
