<?php
require __DIR__ . '/config.php';
require __DIR__ . '/layout.php';
admin_require();

$c = clev_content();

if (isset($_GET['move'], $_GET['i'])) {
    $i = (int)$_GET['i'];
    $dir = $_GET['move'] === 'up' ? -1 : 1;
    $j = $i + $dir;
    if (isset($c['hero']['images'][$i], $c['hero']['images'][$j])) {
        [$c['hero']['images'][$i], $c['hero']['images'][$j]] = [$c['hero']['images'][$j], $c['hero']['images'][$i]];
        clev_save($c);
    }
    header('Location: hero.php');
    exit;
}

if (isset($_GET['delete'])) {
    $i = (int)$_GET['delete'];
    if (isset($c['hero']['images'][$i])) {
        array_splice($c['hero']['images'], $i, 1);
        clev_save($c);
        flash('Image retirée du carousel.');
    }
    header('Location: hero.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $filename = null;

    if (!empty($_FILES['upload']['name'])) {
        $filename = upload_image($_FILES['upload']);
        if (!$filename) {
            flash('Upload échoué : format non supporté ou fichier trop lourd (max 8 Mo).', 'error');
            header('Location: hero.php');
            exit;
        }
    } elseif (!empty($_POST['existing'])) {
        $filename = basename($_POST['existing']);
    }

    if ($filename) {
        $c['hero']['images'][] = $filename;
        clev_save($c);
        flash('Image ajoutée au carousel.');
    } else {
        flash('Choisissez une image.', 'error');
    }
    header('Location: hero.php');
    exit;
}

$images = list_images();
$used = $c['hero']['images'];

admin_head('Images du hero');
?>
      <h1>Images du hero</h1>
      <p class="page-sub">Ces images défilent dans le carousel de la page d'accueil.</p>

      <?php foreach ($c['hero']['images'] as $i => $img): ?>
      <div class="list-item">
        <img src="../assets/images/<?= h($img) ?>" alt="" />
        <div class="grow">
          <strong><?= h($img) ?></strong>
          <small>Position <?= $i + 1 ?> <?= $i === 0 ? '— affichée en premier' : '' ?></small>
        </div>
        <div class="actions">
          <a class="btn btn-small btn-secondary" href="hero.php?move=up&i=<?= $i ?>">↑</a>
          <a class="btn btn-small btn-secondary" href="hero.php?move=down&i=<?= $i ?>">↓</a>
          <a class="btn btn-small btn-danger" href="hero.php?delete=<?= $i ?>"
             onclick="return confirm('Retirer cette image du carousel ?')">Retirer</a>
        </div>
      </div>
      <?php endforeach; ?>

      <h2>Ajouter une image</h2>
      <div class="card">
        <form method="post" enctype="multipart/form-data">
          <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>" />
          <div class="grid-2">
            <div class="field">
              <label>Envoyer une image</label>
              <input type="file" name="upload" accept="image/*" />
            </div>
            <div class="field">
              <label>Ou choisir une image existante</label>
              <select name="existing">
                <option value="">— Sélectionner —</option>
                <?php foreach ($images as $img): ?>
                <option value="<?= h($img) ?>"><?= h($img) ?><?= in_array($img, $used, true) ? ' (déjà utilisée)' : '' ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <button class="btn" type="submit">Ajouter au carousel</button>
        </form>
      </div>
<?php admin_foot(); ?>
