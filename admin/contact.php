<?php
require __DIR__ . '/config.php';
require __DIR__ . '/layout.php';
admin_require();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $c = clev_content();
    $p = $_POST;

    $c['site']['title']            = trim($p['site_title'] ?? '');
    $c['site']['description']      = trim($p['site_description'] ?? '');
    $c['site']['whatsapp']         = preg_replace('/\D/', '', $p['whatsapp'] ?? '');
    $c['site']['whatsapp_display'] = trim($p['whatsapp_display'] ?? '');

    $c['contact']['eyebrow']              = trim($p['ct_eyebrow'] ?? '');
    $c['contact']['title_first']          = trim($p['ct_title_first'] ?? '');
    $c['contact']['text']                 = trim($p['ct_text'] ?? '');
    $c['contact']['phone_main']           = trim($p['phone_main'] ?? '');
    $c['contact']['phone_main_link']      = preg_replace('/[^\d+]/', '', $p['phone_main_link'] ?? '');
    $c['contact']['phone_secondary']      = trim($p['phone_secondary'] ?? '');
    $c['contact']['phone_secondary_link'] = preg_replace('/[^\d+]/', '', $p['phone_secondary_link'] ?? '');
    $c['contact']['email']                = trim($p['email'] ?? '');
    $c['contact']['address']              = trim($p['address'] ?? '');
    $c['contact']['maps_url']             = trim($p['maps_url'] ?? '');
    $c['contact']['facebook_url']         = trim($p['facebook_url'] ?? '');

    $words = array_values(array_filter(array_map('trim', $p['ct_words'] ?? []), fn($w) => $w !== ''));
    if ($words) $c['contact']['words'] = $words;

    clev_save($c);
    flash('Réglages enregistrés.');
    header('Location: contact.php');
    exit;
}

$c = clev_content();

admin_head('Contact & réglages');
?>
      <h1>Contact & réglages</h1>
      <p class="page-sub">Coordonnées affichées sur le site et numéro WhatsApp de réservation.</p>

      <form method="post">
        <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>" />

        <h2>Informations du site</h2>
        <div class="card">
          <div class="field">
            <label>Titre du site (balise title)</label>
            <input type="text" name="site_title" value="<?= h($c['site']['title']) ?>" />
          </div>
          <div class="field">
            <label>Description SEO</label>
            <textarea name="site_description" rows="2"><?= h($c['site']['description']) ?></textarea>
          </div>
        </div>

        <h2>WhatsApp (réservations)</h2>
        <div class="card">
          <div class="grid-2">
            <div class="field">
              <label>Numéro WhatsApp (format international, chiffres uniquement)</label>
              <input type="text" name="whatsapp" value="<?= h($c['site']['whatsapp']) ?>" />
              <p class="hint">Ex. 23672679175</p>
            </div>
            <div class="field">
              <label>Numéro affiché aux visiteurs</label>
              <input type="text" name="whatsapp_display" value="<?= h($c['site']['whatsapp_display']) ?>" />
            </div>
          </div>
        </div>

        <h2>Section Contact</h2>
        <div class="card">
          <div class="grid-2">
            <div class="field">
              <label>Eyebrow</label>
              <input type="text" name="ct_eyebrow" value="<?= h($c['contact']['eyebrow']) ?>" />
            </div>
            <div class="field">
              <label>Début du titre</label>
              <input type="text" name="ct_title_first" value="<?= h($c['contact']['title_first']) ?>" />
            </div>
          </div>
          <div class="field">
            <label>Mots rotatifs du titre</label>
            <?php foreach ($c['contact']['words'] as $w): ?>
            <div class="repeat-row">
              <input type="text" name="ct_words[]" value="<?= h($w) ?>" />
            </div>
            <?php endforeach; ?>
            <div class="repeat-row">
              <input type="text" name="ct_words[]" placeholder="Nouveau mot…" />
            </div>
          </div>
          <div class="field">
            <label>Texte</label>
            <textarea name="ct_text" rows="2"><?= h($c['contact']['text']) ?></textarea>
          </div>
          <div class="grid-2">
            <div class="field">
              <label>Téléphone principal (affiché)</label>
              <input type="text" name="phone_main" value="<?= h($c['contact']['phone_main']) ?>" />
            </div>
            <div class="field">
              <label>Téléphone principal (lien tel:)</label>
              <input type="text" name="phone_main_link" value="<?= h($c['contact']['phone_main_link']) ?>" />
            </div>
          </div>
          <div class="grid-2">
            <div class="field">
              <label>Téléphone secondaire (affiché)</label>
              <input type="text" name="phone_secondary" value="<?= h($c['contact']['phone_secondary']) ?>" />
            </div>
            <div class="field">
              <label>Téléphone secondaire (lien tel:)</label>
              <input type="text" name="phone_secondary_link" value="<?= h($c['contact']['phone_secondary_link']) ?>" />
            </div>
          </div>
          <div class="field">
            <label>Email</label>
            <input type="email" name="email" value="<?= h($c['contact']['email']) ?>" />
          </div>
          <div class="field">
            <label>Adresse (HTML léger autorisé)</label>
            <input type="text" name="address" value="<?= h($c['contact']['address']) ?>" />
          </div>
          <div class="grid-2">
            <div class="field">
              <label>Lien Google Maps</label>
              <input type="url" name="maps_url" value="<?= h($c['contact']['maps_url']) ?>" />
            </div>
            <div class="field">
              <label>Lien Facebook</label>
              <input type="url" name="facebook_url" value="<?= h($c['contact']['facebook_url']) ?>" />
            </div>
          </div>
        </div>

        <button class="btn" type="submit">Enregistrer les réglages</button>
      </form>
<?php admin_foot(); ?>
