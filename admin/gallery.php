<?php
require __DIR__ . '/config.php';
require __DIR__ . '/layout.php';
admin_require();

$c = clev_content();

/* Actions GET : monter / descendre / supprimer */
if (isset($_GET['move'], $_GET['i'])) {
    $i = (int)$_GET['i'];
    $dir = $_GET['move'] === 'up' ? -1 : 1;
    $j = $i + $dir;
    if (isset($c['gallery'][$i], $c['gallery'][$j])) {
        [$c['gallery'][$i], $c['gallery'][$j]] = [$c['gallery'][$j], $c['gallery'][$i]];
        clev_save($c);
    }
    header('Location: gallery.php');
    exit;
}

if (isset($_GET['delete'])) {
    $i = (int)$_GET['delete'];
    if (isset($c['gallery'][$i])) {
        array_splice($c['gallery'], $i, 1);
        clev_save($c);
        flash('Image retirée de la galerie.');
    }
    header('Location: gallery.php');
    exit;
}

/* POST : mise à jour d'un item ou ajout */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';

    if ($action === 'update' && isset($_POST['i'])) {
        $i = (int)$_POST['i'];
        if (isset($c['gallery'][$i])) {
            $c['gallery'][$i]['title'] = trim($_POST['title'] ?? '');
            $c['gallery'][$i]['text']  = trim($_POST['text'] ?? '');
            $c['gallery'][$i]['alt']   = trim($_POST['alt'] ?? '');
            $c['gallery'][$i]['size']  = in_array($_POST['size'] ?? '', ['normal','featured','tall','wide'], true)
                ? $_POST['size'] : 'normal';
            clev_save($c);
            flash('Image mise à jour.');
        }
    }

    if ($action === 'add') {
        $filename = null;

        if (!empty($_FILES['upload']['name'])) {
            $filename = upload_image($_FILES['upload']);
            if (!$filename) {
                flash('Upload échoué : format non supporté ou fichier trop lourd (max 8 Mo).', 'error');
                header('Location: gallery.php');
                exit;
            }
        } elseif (!empty($_POST['existing'])) {
            $filename = basename($_POST['existing']);
        }

        if ($filename) {
            $c['gallery'][] = [
                'image' => $filename,
                'alt'   => trim($_POST['alt'] ?? 'CLEV Beauty & Spa'),
                'title' => trim($_POST['title'] ?? 'Nouvelle image'),
                'text'  => trim($_POST['text'] ?? ''),
                'size'  => 'normal',
            ];
            clev_save($c);
            flash('Image ajoutée à la galerie.');
        } else {
            flash('Choisissez une image.', 'error');
        }
    }

    header('Location: gallery.php');
    exit;
}

$images = list_images();
$usedInGallery = array_column($c['gallery'], 'image');

admin_head('Galerie');
?>
      <h1>Galerie</h1>
      <p class="page-sub"><?= count($c['gallery']) ?> images affichées. Utilisez ↑ ↓ pour réordonner.</p>

      <?php foreach ($c['gallery'] as $i => $g): ?>
      <div class="card" style="display:flex;gap:18px;align-items:flex-start;">
        <img src="../assets/images/<?= h($g['image']) ?>" alt="" style="width:110px;height:110px;object-fit:cover;border-radius:8px;flex:0 0 auto;" />
        <form method="post" style="flex:1;min-width:0;">
          <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>" />
          <input type="hidden" name="action" value="update" />
          <input type="hidden" name="i" value="<?= $i ?>" />
          <div class="grid-2">
            <div class="field">
              <label>Titre</label>
              <input type="text" name="title" value="<?= h($g['title']) ?>" />
            </div>
            <div class="field">
              <label>Format</label>
              <select name="size">
                <option value="normal"   <?= $g['size'] === 'normal' ? 'selected' : '' ?>>Normal</option>
                <option value="featured" <?= $g['size'] === 'featured' ? 'selected' : '' ?>>Grand (2×2)</option>
                <option value="tall"     <?= $g['size'] === 'tall' ? 'selected' : '' ?>>Haut (2 lignes)</option>
                <option value="wide"     <?= $g['size'] === 'wide' ? 'selected' : '' ?>>Large (2 colonnes)</option>
              </select>
            </div>
          </div>
          <div class="grid-2">
            <div class="field">
              <label>Texte de légende</label>
              <input type="text" name="text" value="<?= h($g['text']) ?>" />
            </div>
            <div class="field">
              <label>Texte alternatif (alt)</label>
              <input type="text" name="alt" value="<?= h($g['alt']) ?>" />
            </div>
          </div>
          <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
            <button class="btn btn-small" type="submit">Enregistrer</button>
            <a class="btn btn-small btn-secondary" href="gallery.php?move=up&i=<?= $i ?>">↑</a>
            <a class="btn btn-small btn-secondary" href="gallery.php?move=down&i=<?= $i ?>">↓</a>
            <a class="btn btn-small btn-danger" href="gallery.php?delete=<?= $i ?>"
               onclick="return confirm('Retirer cette image de la galerie ? (le fichier n\'est pas supprimé)')">Retirer</a>
            <small style="color:var(--muted);"><?= h($g['image']) ?></small>
          </div>
        </form>
      </div>
      <?php endforeach; ?>

      <h2>Ajouter une image</h2>
      <div class="card">
        <form method="post" enctype="multipart/form-data">
          <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>" />
          <input type="hidden" name="action" value="add" />
          <div class="grid-2">
            <div class="field">
              <label>Envoyer une image (jpg, png, webp — max 8 Mo)</label>
              <input type="file" name="upload" accept="image/*" />
            </div>
            <div class="field">
              <label>Ou choisir une image existante</label>
              <select name="existing">
                <option value="">— Sélectionner —</option>
                <?php foreach ($images as $img): ?>
                <option value="<?= h($img) ?>"><?= h($img) ?><?= in_array($img, $usedInGallery, true) ? ' (déjà en galerie)' : '' ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="grid-2">
            <div class="field">
              <label>Titre</label>
              <input type="text" name="title" placeholder="Titre de la légende" />
            </div>
            <div class="field">
              <label>Texte de légende</label>
              <input type="text" name="text" placeholder="Description courte" />
            </div>
          </div>
          <button class="btn" type="submit">Ajouter à la galerie</button>
        </form>
      </div>
<?php admin_foot(); ?>
