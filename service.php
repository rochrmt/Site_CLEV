<?php
require __DIR__ . '/lib.php';
$c = clev_content();

$slug = $_GET['slug'] ?? '';
$service = null;
foreach ($c['services'] as $s) {
    if ($s['slug'] === $slug) { $service = $s; break; }
}

if ($service === null) {
    http_response_code(404);
    $pageTitle = 'Service introuvable — CLEV Beauty & Spa';
    $pageDesc  = $c['site']['description'];
    require __DIR__ . '/inc/header.php';
    ?>
    <main id="main">
      <section class="service-hero" style="min-height:70svh;display:flex;align-items:center;justify-content:center;flex-direction:column;text-align:center;">
        <h1 style="font-family:var(--serif);font-size:clamp(40px,6vw,72px);font-weight:400;">Service introuvable</h1>
        <a class="button button-primary" href="index.php#services">Retour aux soins</a>
      </section>
    </main>
    <?php
    require __DIR__ . '/inc/footer.php';
    exit;
}

$index = array_search($service, $c['services'], true);

$pageTitle = $service['title'] . ' — CLEV Beauty & Spa';
$pageDesc  = $service['short'];
$styles    = array_values($service['styles'] ?? []);

$jsConfig = [
    'whatsapp' => $c['site']['whatsapp'],
    'service'  => [
        'slug'   => $service['slug'],
        'title'  => $service['title'],
        'styles' => $styles,
    ],
];

require __DIR__ . '/inc/header.php';
?>
    <script>
      window.CLEV = <?= json_encode($jsConfig, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    </script>

    <main id="main">
      <section class="service-hero">
        <a class="service-back" href="index.php#services">← Retour aux soins</a>
        <div class="service-hero-content reveal">
          <p class="eyebrow">Nos expertises · <?= h($service['number']) ?></p>
          <h1><?= rh($service['title']) ?></h1>
          <p class="service-hero-lead"><?= h($service['lead']) ?></p>
          <a class="button button-primary" href="index.php#reservation" data-booking-open>
            Réserver ce soin
            <svg aria-hidden="true" viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
          </a>
        </div>
        <div class="service-hero-image reveal reveal-delay">
          <img src="<?= img_url($service['hero_image']) ?>" alt="<?= h($service['title']) ?> CLEV Beauty & Spa" />
        </div>
      </section>

      <section class="service-detail section">
        <div class="service-detail-copy reveal">
          <p class="eyebrow">Le soin</p>
          <h2><?= rh($service['detail_title']) ?></h2>
          <p><?= h($service['detail_text']) ?></p>
          <ul class="service-benefits">
            <?php foreach ($service['benefits'] as $b): ?>
            <li><span>✦</span><?= h($b) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="service-detail-image reveal reveal-delay">
          <img src="<?= img_url($service['detail_image']) ?>" alt="<?= h($service['title']) ?> CLEV Beauty & Spa" loading="lazy" />
        </div>
      </section>

      <section class="service-cta">
        <div class="service-cta-inner reveal">
          <h2><?= rh($service['cta_title']) ?></h2>
          <a class="button button-light" href="index.php#reservation" data-booking-open>
            Réserver via WhatsApp
            <svg aria-hidden="true" viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
          </a>
        </div>
      </section>
    </main>

    <div class="booking-modal" role="dialog" aria-modal="true" aria-labelledby="booking-modal-title" data-booking-modal>
      <div class="booking-modal-card">
        <button class="booking-modal-close" type="button" aria-label="Fermer la réservation" data-booking-close>
          <svg aria-hidden="true" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18" /></svg>
        </button>
        <p class="eyebrow">Prise de rendez-vous</p>
        <h2 id="booking-modal-title">Réserver — <?= h($service['title']) ?></h2>
        <p class="booking-step-counter" data-step-counter></p>
        <div class="booking-progress" data-booking-progress aria-hidden="true"></div>

        <form id="service-booking-form" novalidate>
          <?php if ($styles): ?>
          <fieldset class="booking-step" data-step>
            <legend class="booking-step-title">Choisissez votre style</legend>
            <div class="style-chips" data-style-chips>
              <?php foreach ($styles as $st): ?>
              <button type="button" class="style-chip" data-style-chip data-value="<?= h($st) ?>"><?= h($st) ?></button>
              <?php endforeach; ?>
              <button type="button" class="style-chip style-chip-other" data-style-chip data-value="Autre / à définir ensemble">Autre / à définir ensemble</button>
            </div>
            <p class="booking-error" data-style-error hidden>Sélectionnez un style pour continuer.</p>
          </fieldset>
          <?php endif; ?>

          <fieldset class="booking-step" data-step>
            <legend class="booking-step-title">Vos coordonnées</legend>
            <div class="form-field">
              <label for="sb-name">Nom complet *</label>
              <input type="text" id="sb-name" name="name" placeholder="Votre nom" autocomplete="name" required />
            </div>
            <div class="form-field">
              <label for="sb-phone">Téléphone *</label>
              <input type="tel" id="sb-phone" name="phone" placeholder="+236 ..." autocomplete="tel" required />
            </div>
          </fieldset>

          <fieldset class="booking-step" data-step>
            <legend class="booking-step-title">Votre rendez-vous</legend>
            <div class="form-field">
              <label for="sb-date">Date souhaitée</label>
              <input type="date" id="sb-date" name="date" />
            </div>
            <div class="form-field">
              <label for="sb-message">Précisions (optionnel)</label>
              <textarea id="sb-message" name="message" rows="3" placeholder="Une demande particulière ? Dites-nous tout."></textarea>
            </div>
          </fieldset>

          <fieldset class="booking-step" data-step>
            <legend class="booking-step-title">Récapitulatif</legend>
            <dl class="booking-summary" data-summary></dl>
            <p class="booking-note">
              <span aria-hidden="true">✦</span>
              Votre message s'ouvrira dans WhatsApp pour être envoyé au
              <strong><?= h($c['site']['whatsapp_display']) ?></strong>.
            </p>
          </fieldset>

          <div class="booking-modal-foot">
            <button type="button" class="button button-outline" data-step-prev hidden>Retour</button>
            <button type="button" class="button button-primary" data-step-next>
              Continuer
              <svg aria-hidden="true" viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
            </button>
            <button type="submit" class="button button-primary" data-step-submit hidden>
              Envoyer sur WhatsApp
              <svg aria-hidden="true" viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
            </button>
          </div>
        </form>
      </div>
    </div>

<?php require __DIR__ . '/inc/footer.php'; ?>
