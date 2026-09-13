<?php
require __DIR__ . '/config.php';
require __DIR__ . '/layout.php';
admin_require();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $c = clev_content();
    $p = $_POST;

    // Hero
    $c['hero']['eyebrow']        = trim($p['hero_eyebrow'] ?? '');
    $c['hero']['intro']          = trim($p['hero_intro'] ?? '');
    $c['hero']['cta_primary']    = trim($p['hero_cta_primary'] ?? '');
    $c['hero']['cta_secondary']  = trim($p['hero_cta_secondary'] ?? '');
    $c['hero']['note_title']     = trim($p['hero_note_title'] ?? '');
    $c['hero']['note_text']      = trim($p['hero_note_text'] ?? '');
    $c['hero']['location_label'] = trim($p['hero_location_label'] ?? '');
    $c['hero']['location_value'] = trim($p['hero_location_value'] ?? '');

    $phrases = [];
    foreach (($p['hero_p1'] ?? []) as $i => $l1) {
        $l2 = $p['hero_p2'][$i] ?? '';
        if (trim($l1) !== '' || trim($l2) !== '') {
            $phrases[] = [trim($l1), trim($l2)];
        }
    }
    if ($phrases) $c['hero']['phrases'] = $phrases;

    // Manifesto
    $c['manifesto']['eyebrow'] = trim($p['manifesto_eyebrow'] ?? '');
    $c['manifesto']['title']   = trim($p['manifesto_title'] ?? '');
    $words = array_values(array_filter(array_map('trim', $p['manifesto_words'] ?? []), fn($w) => $w !== ''));
    if ($words) $c['manifesto']['words'] = $words;

    // En-tête services
    $c['services_heading']['eyebrow'] = trim($p['sh_eyebrow'] ?? '');
    $c['services_heading']['title']   = trim($p['sh_title'] ?? '');
    $c['services_heading']['text']    = trim($p['sh_text'] ?? '');

    // Expérience
    $c['experience']['eyebrow']       = trim($p['exp_eyebrow'] ?? '');
    $c['experience']['title']         = trim($p['exp_title'] ?? '');
    $c['experience']['lead']          = trim($p['exp_lead'] ?? '');
    $c['experience']['image_caption'] = trim($p['exp_image_caption'] ?? '');
    $c['experience']['cta']           = trim($p['exp_cta'] ?? '');
    if (!empty($p['exp_image'])) $c['experience']['image'] = basename($p['exp_image']);

    $expItems = [];
    foreach (($p['exp_item_title'] ?? []) as $i => $t) {
        $txt = $p['exp_item_text'][$i] ?? '';
        if (trim($t) !== '') {
            $expItems[] = ['title' => trim($t), 'text' => trim($txt)];
        }
    }
    if ($expItems) $c['experience']['items'] = $expItems;

    // En-tête galerie
    $c['gallery_heading']['eyebrow'] = trim($p['gh_eyebrow'] ?? '');
    $c['gallery_heading']['title']   = trim($p['gh_title'] ?? '');
    $c['gallery_heading']['text']    = trim($p['gh_text'] ?? '');

    // Citations
    $quotes = [];
    foreach (($p['quote_l1'] ?? []) as $i => $l1) {
        $l2 = $p['quote_l2'][$i] ?? '';
        if (trim($l1) !== '' || trim($l2) !== '') {
            $quotes[] = [trim($l1), trim($l2)];
        }
    }
    if ($quotes) $c['quotes'] = $quotes;

    // Formulaire réservation
    $c['booking']['eyebrow'] = trim($p['bk_eyebrow'] ?? '');
    $c['booking']['title']   = trim($p['bk_title'] ?? '');
    $c['booking']['text']    = trim($p['bk_text'] ?? '');
    $c['booking']['submit']  = trim($p['bk_submit'] ?? '');
    $c['booking']['note']    = trim($p['bk_note'] ?? '');

    // Footer
    $c['footer']['tagline'] = trim($p['footer_tagline'] ?? '');

    clev_save($c);
    flash('Contenu enregistré avec succès.');
    header('Location: texts.php');
    exit;
}

$c = clev_content();
$images = list_images();

admin_head('Textes du site');
?>
      <h1>Textes du site</h1>
      <p class="page-sub">Modifiez les textes de la page d'accueil. Les champs « Titre » acceptent <code>&lt;br&gt;</code> et <code>&lt;em&gt;</code>.</p>

      <form method="post">
        <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>" />

        <h2>Section Hero</h2>
        <div class="card">
          <div class="grid-2">
            <div class="field">
              <label>Petit titre (eyebrow)</label>
              <input type="text" name="hero_eyebrow" value="<?= h($c['hero']['eyebrow']) ?>" />
            </div>
            <div class="field">
              <label>Bouton principal</label>
              <input type="text" name="hero_cta_primary" value="<?= h($c['hero']['cta_primary']) ?>" />
            </div>
          </div>
          <div class="field">
            <label>Phrases rotatives (2 lignes par phrase)</label>
            <?php foreach ($c['hero']['phrases'] as $ph): ?>
            <div class="repeat-row">
              <input type="text" name="hero_p1[]" value="<?= h($ph[0]) ?>" placeholder="Ligne 1" />
              <input type="text" name="hero_p2[]" value="<?= h($ph[1]) ?>" placeholder="Ligne 2 (colorée)" />
            </div>
            <?php endforeach; ?>
            <div class="repeat-row">
              <input type="text" name="hero_p1[]" placeholder="Ligne 1 — nouvelle phrase" />
              <input type="text" name="hero_p2[]" placeholder="Ligne 2 — nouvelle phrase" />
            </div>
            <p class="hint">Laissez une ligne vide pour supprimer la phrase.</p>
          </div>
          <div class="field">
            <label>Texte d'introduction</label>
            <textarea name="hero_intro" rows="3"><?= h($c['hero']['intro']) ?></textarea>
          </div>
          <div class="grid-2">
            <div class="field">
              <label>Lien secondaire</label>
              <input type="text" name="hero_cta_secondary" value="<?= h($c['hero']['cta_secondary']) ?>" />
            </div>
            <div class="field">
              <label>Titre de la note</label>
              <input type="text" name="hero_note_title" value="<?= h($c['hero']['note_title']) ?>" />
            </div>
          </div>
          <div class="field">
            <label>Texte de la note</label>
            <input type="text" name="hero_note_text" value="<?= h($c['hero']['note_text']) ?>" />
          </div>
          <div class="grid-2">
            <div class="field">
              <label>Label localisation</label>
              <input type="text" name="hero_location_label" value="<?= h($c['hero']['location_label']) ?>" />
            </div>
            <div class="field">
              <label>Valeur localisation</label>
              <input type="text" name="hero_location_value" value="<?= h($c['hero']['location_value']) ?>" />
            </div>
          </div>
        </div>

        <h2>Manifesto</h2>
        <div class="card">
          <div class="grid-2">
            <div class="field">
              <label>Eyebrow</label>
              <input type="text" name="manifesto_eyebrow" value="<?= h($c['manifesto']['eyebrow']) ?>" />
            </div>
            <div class="field">
              <label>Titre</label>
              <input type="text" name="manifesto_title" value="<?= h($c['manifesto']['title']) ?>" />
            </div>
          </div>
          <div class="field">
            <label>Mots rotatifs (un par ligne de champ)</label>
            <?php foreach ($c['manifesto']['words'] as $w): ?>
            <div class="repeat-row">
              <input type="text" name="manifesto_words[]" value="<?= h($w) ?>" />
            </div>
            <?php endforeach; ?>
            <div class="repeat-row">
              <input type="text" name="manifesto_words[]" placeholder="Nouveau mot…" />
            </div>
          </div>
        </div>

        <h2>En-tête « Nos expertises »</h2>
        <div class="card">
          <div class="grid-2">
            <div class="field">
              <label>Eyebrow</label>
              <input type="text" name="sh_eyebrow" value="<?= h($c['services_heading']['eyebrow']) ?>" />
            </div>
            <div class="field">
              <label>Titre (HTML léger autorisé)</label>
              <input type="text" name="sh_title" value="<?= h($c['services_heading']['title']) ?>" />
            </div>
          </div>
          <div class="field">
            <label>Texte</label>
            <textarea name="sh_text" rows="2"><?= h($c['services_heading']['text']) ?></textarea>
          </div>
        </div>

        <h2>Section « L'expérience CLEV »</h2>
        <div class="card">
          <div class="grid-2">
            <div class="field">
              <label>Eyebrow</label>
              <input type="text" name="exp_eyebrow" value="<?= h($c['experience']['eyebrow']) ?>" />
            </div>
            <div class="field">
              <label>Titre (HTML léger autorisé)</label>
              <input type="text" name="exp_title" value="<?= h($c['experience']['title']) ?>" />
            </div>
          </div>
          <div class="field">
            <label>Texte d'introduction</label>
            <textarea name="exp_lead" rows="3"><?= h($c['experience']['lead']) ?></textarea>
          </div>
          <div class="grid-2">
            <div class="field">
              <label>Image de la section</label>
              <div class="img-picker">
                <img src="../assets/images/<?= h($c['experience']['image']) ?>" alt="" />
                <select name="exp_image">
                  <?php foreach ($images as $img): ?>
                  <option value="<?= h($img) ?>" <?= $img === $c['experience']['image'] ? 'selected' : '' ?>><?= h($img) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="field">
              <label>Légende sur l'image</label>
              <input type="text" name="exp_image_caption" value="<?= h($c['experience']['image_caption']) ?>" />
            </div>
          </div>
          <div class="field">
            <label>Points de la liste (titre + description)</label>
            <?php foreach ($c['experience']['items'] as $it): ?>
            <div class="repeat-row">
              <input type="text" name="exp_item_title[]" value="<?= h($it['title']) ?>" placeholder="Titre" />
              <input type="text" name="exp_item_text[]" value="<?= h($it['text']) ?>" placeholder="Description" />
            </div>
            <?php endforeach; ?>
            <div class="repeat-row">
              <input type="text" name="exp_item_title[]" placeholder="Titre — nouveau point" />
              <input type="text" name="exp_item_text[]" placeholder="Description — nouveau point" />
            </div>
          </div>
          <div class="field">
            <label>Texte du bouton</label>
            <input type="text" name="exp_cta" value="<?= h($c['experience']['cta']) ?>" />
          </div>
        </div>

        <h2>En-tête « Galerie »</h2>
        <div class="card">
          <div class="grid-2">
            <div class="field">
              <label>Eyebrow</label>
              <input type="text" name="gh_eyebrow" value="<?= h($c['gallery_heading']['eyebrow']) ?>" />
            </div>
            <div class="field">
              <label>Titre</label>
              <input type="text" name="gh_title" value="<?= h($c['gallery_heading']['title']) ?>" />
            </div>
          </div>
          <div class="field">
            <label>Texte</label>
            <textarea name="gh_text" rows="2"><?= h($c['gallery_heading']['text']) ?></textarea>
          </div>
        </div>

        <h2>Citations rotatives</h2>
        <div class="card">
          <?php foreach ($c['quotes'] as $q): ?>
          <div class="repeat-row">
            <input type="text" name="quote_l1[]" value="<?= h($q[0]) ?>" placeholder="Ligne 1" />
            <input type="text" name="quote_l2[]" value="<?= h($q[1]) ?>" placeholder="Ligne 2 (colorée)" />
          </div>
          <?php endforeach; ?>
          <div class="repeat-row">
            <input type="text" name="quote_l1[]" placeholder="Ligne 1 — nouvelle citation" />
            <input type="text" name="quote_l2[]" placeholder="Ligne 2 — nouvelle citation" />
          </div>
          <p class="hint">Laissez une ligne vide pour supprimer la citation.</p>
        </div>

        <h2>Formulaire de réservation</h2>
        <div class="card">
          <div class="grid-2">
            <div class="field">
              <label>Eyebrow</label>
              <input type="text" name="bk_eyebrow" value="<?= h($c['booking']['eyebrow']) ?>" />
            </div>
            <div class="field">
              <label>Titre (HTML léger autorisé)</label>
              <input type="text" name="bk_title" value="<?= h($c['booking']['title']) ?>" />
            </div>
          </div>
          <div class="field">
            <label>Texte</label>
            <textarea name="bk_text" rows="2"><?= h($c['booking']['text']) ?></textarea>
          </div>
          <div class="grid-2">
            <div class="field">
              <label>Texte du bouton</label>
              <input type="text" name="bk_submit" value="<?= h($c['booking']['submit']) ?>" />
            </div>
            <div class="field">
              <label>Note sous le bouton</label>
              <input type="text" name="bk_note" value="<?= h($c['booking']['note']) ?>" />
            </div>
          </div>
        </div>

        <h2>Footer</h2>
        <div class="card">
          <div class="field">
            <label>Slogan</label>
            <input type="text" name="footer_tagline" value="<?= h($c['footer']['tagline']) ?>" />
          </div>
        </div>

        <button class="btn" type="submit">Enregistrer toutes les modifications</button>
      </form>
<?php admin_foot(); ?>
