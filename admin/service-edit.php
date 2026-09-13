<?php
require __DIR__ . '/config.php';
require __DIR__ . '/layout.php';
admin_require();

$slug = $_GET['slug'] ?? '';
$c = clev_content();
$service = null;
$key = null;
foreach ($c['services'] as $i => $s) {
    if ($s['slug'] === $slug) { $service = $s; $key = $i; break; }
}

if ($service === null) {
    flash('Service introuvable.', 'error');
    header('Location: services.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $p = $_POST;

    $service['number']       = trim($p['number'] ?? $service['number']);
    $service['title']        = trim($p['title'] ?? $service['title']);
    $service['short']        = trim($p['short'] ?? '');
    $service['lead']         = trim($p['lead'] ?? '');
    $service['detail_title'] = trim($p['detail_title'] ?? '');
    $service['detail_text']  = trim($p['detail_text'] ?? '');
    $service['cta_title']    = trim($p['cta_title'] ?? '');
    $service['icon']         = trim($p['icon'] ?? $service['icon']);
    $service['featured']     = !empty($p['featured']);

    if (!empty($p['hero_image']))   $service['hero_image']   = basename($p['hero_image']);
    if (!empty($p['detail_image'])) $service['detail_image'] = basename($p['detail_image']);

    // Upload d'une nouvelle image pour le hero ou le détail
    if (!empty($_FILES['hero_upload']['name'])) {
        $up = upload_image($_FILES['hero_upload']);
        if ($up) $service['hero_image'] = $up;
    }
    if (!empty($_FILES['detail_upload']['name'])) {
        $up = upload_image($_FILES['detail_upload']);
        if ($up) $service['detail_image'] = $up;
    }

    $benefits = array_values(array_filter(array_map('trim', $p['benefits'] ?? []), fn($b) => $b !== ''));
    $service['benefits'] = $benefits;

    $c['services'][$key] = $service;
    clev_save($c);
    flash('Service « ' . $service['title'] . ' » enregistré.');
    header('Location: service-edit.php?slug=' . rawurlencode($slug));
    exit;
}

$images = list_images();

admin_head('Modifier : ' . $service['title']);
?>
      <h1><?= h($service['title']) ?></h1>
      <p class="page-sub">
        <a href="services.php" style="color:var(--terracotta);">← Retour aux services</a> ·
        <a href="../service.php?slug=<?= h($slug) ?>" target="_blank" style="color:var(--terracotta);">Voir la page ↗</a>
      </p>

      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>" />

        <h2>Carte (page d'accueil)</h2>
        <div class="card">
          <div class="grid-2">
            <div class="field">
              <label>Titre</label>
              <input type="text" name="title" value="<?= h($service['title']) ?>" required />
            </div>
            <div class="field">
              <label>Numéro</label>
              <input type="text" name="number" value="<?= h($service['number']) ?>" />
            </div>
          </div>
          <div class="field">
            <label>Description courte</label>
            <textarea name="short" rows="2"><?= h($service['short']) ?></textarea>
          </div>
          <div class="field">
            <label>Icône SVG (attributs "d" des chemins, séparés par |)</label>
            <input type="text" name="icon" value="<?= h($service['icon']) ?>" />
            <p class="hint">Format : <code>M15 33c3-10...|M12 35h24...</code></p>
          </div>
          <div class="field">
            <label>
              <input type="checkbox" name="featured" value="1" <?= !empty($service['featured']) ? 'checked' : '' ?> style="width:auto;display:inline-block;margin-right:8px;" />
              Carte mise en avant (fond terracotta)
            </label>
          </div>
        </div>

        <h2>Page détaillée</h2>
        <div class="card">
          <div class="field">
            <label>Texte d'introduction (hero)</label>
            <textarea name="lead" rows="3"><?= h($service['lead']) ?></textarea>
          </div>
          <div class="field">
            <label>Titre de la section détail (HTML léger autorisé)</label>
            <input type="text" name="detail_title" value="<?= h($service['detail_title']) ?>" />
          </div>
          <div class="field">
            <label>Texte détaillé</label>
            <textarea name="detail_text" rows="5"><?= h($service['detail_text']) ?></textarea>
          </div>
          <div class="field">
            <label>Bénéfices (un par champ)</label>
            <?php foreach ($service['benefits'] as $b): ?>
            <div class="repeat-row">
              <input type="text" name="benefits[]" value="<?= h($b) ?>" />
            </div>
            <?php endforeach; ?>
            <div class="repeat-row">
              <input type="text" name="benefits[]" placeholder="Nouveau bénéfice…" />
            </div>
          </div>
          <div class="field">
            <label>Titre du bloc final (CTA, HTML léger autorisé)</label>
            <input type="text" name="cta_title" value="<?= h($service['cta_title']) ?>" />
          </div>
        </div>

        <h2>Images</h2>
        <div class="card">
          <div class="grid-2">
            <div class="field">
              <label>Image du hero (grande)</label>
              <div class="img-picker">
                <img src="../assets/images/<?= h($service['hero_image']) ?>" alt="" />
                <select name="hero_image">
                  <?php foreach ($images as $img): ?>
                  <option value="<?= h($img) ?>" <?= $img === $service['hero_image'] ? 'selected' : '' ?>><?= h($img) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div style="margin-top:10px;">
                <label>Ou envoyer une nouvelle image</label>
                <input type="file" name="hero_upload" accept="image/*" />
              </div>
            </div>
            <div class="field">
              <label>Image de la section détail</label>
              <div class="img-picker">
                <img src="../assets/images/<?= h($service['detail_image']) ?>" alt="" />
                <select name="detail_image">
                  <?php foreach ($images as $img): ?>
                  <option value="<?= h($img) ?>" <?= $img === $service['detail_image'] ? 'selected' : '' ?>><?= h($img) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div style="margin-top:10px;">
                <label>Ou envoyer une nouvelle image</label>
                <input type="file" name="detail_upload" accept="image/*" />
              </div>
            </div>
          </div>
        </div>

        <button class="btn" type="submit">Enregistrer le service</button>
      </form>
<?php admin_foot(); ?>
