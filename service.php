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

require __DIR__ . '/inc/header.php';
?>
    <main id="main">
      <section class="service-hero">
        <a class="service-back" href="index.php#services">← Retour aux soins</a>
        <div class="service-hero-content reveal">
          <p class="eyebrow">Nos expertises · <?= h($service['number']) ?></p>
          <h1><?= rh($service['title']) ?></h1>
          <p class="service-hero-lead"><?= h($service['lead']) ?></p>
          <a class="button button-primary" href="index.php#reservation">
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
          <a class="button button-light" href="index.php#reservation">
            Réserver via WhatsApp
            <svg aria-hidden="true" viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
          </a>
        </div>
      </section>
    </main>

<?php require __DIR__ . '/inc/footer.php'; ?>
