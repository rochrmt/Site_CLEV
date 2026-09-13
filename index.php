<?php
require __DIR__ . '/lib.php';
$c = clev_content();

$pageTitle = $c['site']['title'];
$pageDesc  = $c['site']['description'];

/* Config injectée pour script.js (phrases rotatives, numéro WhatsApp) */
$jsConfig = [
    'heroPhrases'      => $c['hero']['phrases'],
    'manifestoWords'   => $c['manifesto']['words'],
    'contactWords'     => $c['contact']['words'],
    'quotes'           => $c['quotes'],
    'whatsapp'         => $c['site']['whatsapp'],
];

require __DIR__ . '/inc/header.php';
?>
    <script>
      window.CLEV = <?= json_encode($jsConfig, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    </script>

    <main id="main">
      <section id="accueil" class="hero">
        <div class="hero-copy reveal">
          <p class="eyebrow"><?= h($c['hero']['eyebrow']) ?></p>
          <h1 class="hero-rotator" data-rotator>
            <span class="rotator-primary"><?= h($c['hero']['phrases'][0][0]) ?></span><br /><em class="rotator-secondary"><?= h($c['hero']['phrases'][0][1]) ?></em>
          </h1>
          <p class="hero-intro"><?= h($c['hero']['intro']) ?></p>
          <div class="hero-actions">
            <a class="button button-primary" href="#reservation">
              <?= h($c['hero']['cta_primary']) ?>
              <svg aria-hidden="true" viewBox="0 0 24 24">
                <path d="M5 12h14M13 6l6 6-6 6" />
              </svg>
            </a>
            <a class="text-link" href="#services"><?= h($c['hero']['cta_secondary']) ?></a>
          </div>
          <div class="hero-note">
            <span class="hero-note-icon" aria-hidden="true">✦</span>
            <p><strong><?= h($c['hero']['note_title']) ?></strong><?= h($c['hero']['note_text']) ?></p>
          </div>
        </div>

        <div class="hero-visual reveal reveal-delay">
          <div class="hero-carousel" data-carousel>
            <div class="hero-carousel-track">
              <?php foreach ($c['hero']['images'] as $i => $img): ?>
              <img
                src="<?= img_url($img) ?>"
                alt="CLEV Beauty & Spa"
                <?= $i > 0 ? 'loading="lazy"' : '' ?>
              />
              <?php endforeach; ?>
            </div>
            <button class="hero-carousel-prev" type="button" aria-label="Image précédente" data-carousel-prev>
              <svg aria-hidden="true" viewBox="0 0 24 24"><path d="M15 6l-6 6 6 6" /></svg>
            </button>
            <button class="hero-carousel-next" type="button" aria-label="Image suivante" data-carousel-next>
              <svg aria-hidden="true" viewBox="0 0 24 24"><path d="M9 6l6 6-6 6" /></svg>
            </button>
            <div class="hero-carousel-dots" data-carousel-dots aria-hidden="true"></div>
          </div>
          <div class="hero-seal" aria-hidden="true">
            <span>Prendre soin de soi</span>
            <strong>CB</strong>
          </div>
          <div class="hero-location">
            <svg aria-hidden="true" viewBox="0 0 24 24">
              <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z" />
              <circle cx="12" cy="10" r="2.5" />
            </svg>
            <p><span><?= h($c['hero']['location_label']) ?></span><strong><?= h($c['hero']['location_value']) ?></strong></p>
          </div>
        </div>
      </section>

      <section class="manifesto" aria-label="Notre philosophie">
        <p class="eyebrow reveal"><?= h($c['manifesto']['eyebrow']) ?></p>
        <h2 class="reveal">
          <?= h($c['manifesto']['title']) ?>
          <em data-rotator-manifesto><?= h($c['manifesto']['words'][0]) ?></em>
        </h2>
        <div class="manifesto-line" aria-hidden="true"></div>
      </section>

      <section id="services" class="services section">
        <div class="section-heading reveal">
          <div>
            <p class="eyebrow"><?= h($c['services_heading']['eyebrow']) ?></p>
            <h2><?= rh($c['services_heading']['title']) ?></h2>
          </div>
          <p><?= h($c['services_heading']['text']) ?></p>
        </div>

        <div class="service-grid">
          <?php foreach ($c['services'] as $i => $s): ?>
          <a class="service-card<?= !empty($s['featured']) ? ' service-card-featured' : '' ?> reveal<?= $i % 2 ? ' reveal-delay' : '' ?>" href="service.php?slug=<?= h($s['slug']) ?>">
            <span class="service-number"><?= h($s['number']) ?></span>
            <svg class="service-icon" aria-hidden="true" viewBox="0 0 48 48">
              <?php foreach (explode('|', $s['icon']) as $d): ?>
              <path d="<?= h($d) ?>" />
              <?php endforeach; ?>
            </svg>
            <h3><?= h($s['title']) ?></h3>
            <p><?= h($s['short']) ?></p>
            <span class="service-link">En savoir plus →</span>
          </a>
          <?php endforeach; ?>
        </div>
      </section>

      <section id="experience" class="experience">
        <div class="experience-image reveal">
          <img
            src="<?= img_url($c['experience']['image']) ?>"
            alt="CLEV Beauty & Spa"
            loading="lazy"
          />
          <span><?= h($c['experience']['image_caption']) ?></span>
        </div>
        <div class="experience-copy reveal reveal-delay">
          <p class="eyebrow"><?= h($c['experience']['eyebrow']) ?></p>
          <h2><?= rh($c['experience']['title']) ?></h2>
          <p class="experience-lead"><?= h($c['experience']['lead']) ?></p>
          <ul class="experience-list">
            <?php foreach ($c['experience']['items'] as $i => $item): ?>
            <li>
              <span><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
              <div><strong><?= h($item['title']) ?></strong><p><?= h($item['text']) ?></p></div>
            </li>
            <?php endforeach; ?>
          </ul>
          <a class="button button-outline" href="tel:<?= h($c['contact']['phone_secondary_link']) ?>"><?= h($c['experience']['cta']) ?></a>
        </div>
      </section>

      <section id="galerie" class="gallery section">
        <div class="section-heading reveal">
          <div>
            <p class="eyebrow"><?= h($c['gallery_heading']['eyebrow']) ?></p>
            <h2><?= rh($c['gallery_heading']['title']) ?></h2>
          </div>
          <p><?= h($c['gallery_heading']['text']) ?></p>
        </div>

        <div class="gallery-grid">
          <?php
          $sizeClass = ['featured' => ' gallery-item-featured', 'tall' => ' gallery-item-tall', 'wide' => ' gallery-item-wide'];
          foreach ($c['gallery'] as $i => $g):
              $cls = $sizeClass[$g['size'] ?? 'normal'] ?? '';
          ?>
          <figure class="gallery-item<?= $cls ?> reveal<?= $i % 2 ? ' reveal-delay' : '' ?>">
            <img
              src="<?= img_url($g['image']) ?>"
              alt="<?= h($g['alt']) ?>"
              loading="lazy"
            />
            <figcaption>
              <span><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
              <div>
                <strong><?= h($g['title']) ?></strong>
                <small><?= h($g['text']) ?></small>
              </div>
            </figcaption>
          </figure>
          <?php endforeach; ?>
        </div>
      </section>

      <section class="quote-section">
        <div class="quote-mark" aria-hidden="true">“</div>
        <blockquote class="reveal quote-rotator">
          <span class="quote-primary" data-quote-primary><?= h($c['quotes'][0][0]) ?></span><br />
          <em class="quote-secondary" data-quote-secondary><?= h($c['quotes'][0][1]) ?></em>
        </blockquote>
        <p class="reveal">CLEV BEAUTY & SPA</p>
      </section>

      <section id="reservation" class="booking section">
        <div class="section-heading reveal">
          <div>
            <p class="eyebrow"><?= h($c['booking']['eyebrow']) ?></p>
            <h2><?= rh($c['booking']['title']) ?></h2>
          </div>
          <p><?= h($c['booking']['text']) ?></p>
        </div>

        <form class="booking-form reveal reveal-delay" id="booking-form" novalidate>
          <div class="form-row">
            <div class="form-field">
              <label for="booking-name">Nom complet *</label>
              <input type="text" id="booking-name" name="name" placeholder="Votre nom" required />
            </div>
            <div class="form-field">
              <label for="booking-phone">Téléphone *</label>
              <input type="tel" id="booking-phone" name="phone" placeholder="+236 ..." required />
            </div>
          </div>
          <div class="form-row">
            <div class="form-field">
              <label for="booking-service">Service souhaité *</label>
              <select id="booking-service" name="service" required>
                <option value="" disabled selected>Choisissez un soin</option>
                <?php foreach ($c['services'] as $s): ?>
                <option value="<?= h($s['title']) ?>"><?= h($s['title']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-field">
              <label for="booking-date">Date souhaitée</label>
              <input type="date" id="booking-date" name="date" />
            </div>
          </div>
          <div class="form-field">
            <label for="booking-message">Précisions (optionnel)</label>
            <textarea id="booking-message" name="message" rows="3" placeholder="Une demande particulière ? Dites-nous tout."></textarea>
          </div>
          <button type="submit" class="button button-primary booking-submit">
            <?= h($c['booking']['submit']) ?>
            <svg aria-hidden="true" viewBox="0 0 24 24">
              <path d="M5 12h14M13 6l6 6-6 6" />
            </svg>
          </button>
          <p class="booking-note">
            <span aria-hidden="true">✦</span>
            <?= h($c['booking']['note']) ?>
            <strong><?= h($c['site']['whatsapp_display']) ?></strong>.
          </p>
        </form>
      </section>

      <section id="contact" class="contact">
        <div class="contact-intro reveal">
          <p class="eyebrow"><?= h($c['contact']['eyebrow']) ?></p>
          <h2><?= h($c['contact']['title_first']) ?><br /><em data-rotator-contact><?= h($c['contact']['words'][0]) ?></em></h2>
          <p><?= h($c['contact']['text']) ?></p>
          <a class="button button-light" href="tel:<?= h($c['contact']['phone_main_link']) ?>">
            <?= h($c['contact']['phone_main']) ?>
            <svg aria-hidden="true" viewBox="0 0 24 24">
              <path d="M5 12h14M13 6l6 6-6 6" />
            </svg>
          </a>
        </div>

        <div class="contact-details reveal reveal-delay">
          <div class="contact-card">
            <span>Adresse</span>
            <p><?= rh($c['contact']['address']) ?></p>
            <a
              href="<?= h($c['contact']['maps_url']) ?>"
              target="_blank"
              rel="noreferrer"
            >Voir sur la carte ↗</a>
          </div>
          <div class="contact-card">
            <span>Nous joindre</span>
            <p>
              <a href="tel:<?= h($c['contact']['phone_main_link']) ?>"><?= h($c['contact']['phone_main']) ?></a><br />
              <a href="tel:<?= h($c['contact']['phone_secondary_link']) ?>"><?= h($c['contact']['phone_secondary']) ?></a>
            </p>
            <a href="mailto:<?= h($c['contact']['email']) ?>"><?= h($c['contact']['email']) ?></a>
          </div>
          <div class="contact-card">
            <span>Nous suivre</span>
            <p>Actualités, conseils beauté<br />et nouveautés de l'institut.</p>
            <a
              href="<?= h($c['contact']['facebook_url']) ?>"
              target="_blank"
              rel="noreferrer"
            >Facebook ↗</a>
          </div>
        </div>
      </section>
    </main>

    <div class="lightbox" role="dialog" aria-modal="true" aria-label="Galerie en plein écran" data-lightbox>
      <button class="lightbox-close" type="button" aria-label="Fermer la galerie" data-lightbox-close>
        <svg aria-hidden="true" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18" /></svg>
      </button>
      <button class="lightbox-prev" type="button" aria-label="Image précédente" data-lightbox-prev>
        <svg aria-hidden="true" viewBox="0 0 24 24"><path d="M15 6l-6 6 6 6" /></svg>
      </button>
      <figure class="lightbox-figure">
        <img src="" alt="" data-lightbox-img />
        <figcaption class="lightbox-caption" data-lightbox-caption></figcaption>
      </figure>
      <button class="lightbox-next" type="button" aria-label="Image suivante" data-lightbox-next>
        <svg aria-hidden="true" viewBox="0 0 24 24"><path d="M9 6l6 6-6 6" /></svg>
      </button>
      <p class="lightbox-counter" data-lightbox-counter></p>
    </div>

<?php require __DIR__ . '/inc/footer.php'; ?>
