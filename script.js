const header = document.querySelector("[data-header]");
const menuButton = document.querySelector(".menu-toggle");
const navigation = document.querySelector(".main-nav");
const year = document.querySelector("[data-year]");

const updateHeader = () => {
  header.classList.toggle("is-scrolled", window.scrollY > 24);
};

const closeMenu = () => {
  menuButton.setAttribute("aria-expanded", "false");
  navigation.classList.remove("is-open");
  document.body.classList.remove("menu-open");
};

menuButton.addEventListener("click", () => {
  const isOpen = menuButton.getAttribute("aria-expanded") === "true";
  menuButton.setAttribute("aria-expanded", String(!isOpen));
  navigation.classList.toggle("is-open", !isOpen);
  document.body.classList.toggle("menu-open", !isOpen);
});

navigation.querySelectorAll("a").forEach((link) => {
  link.addEventListener("click", closeMenu);
});

window.addEventListener("scroll", updateHeader, { passive: true });
window.addEventListener("resize", () => {
  if (window.innerWidth > 1050) {
    closeMenu();
  }
});

const observer = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("is-visible");
        observer.unobserve(entry.target);
      }
    });
  },
  { threshold: 0.12 },
);

document.querySelectorAll(".reveal").forEach((element) => observer.observe(element));

year.textContent = new Date().getFullYear();
updateHeader();

/* === Reduced motion preference === */
const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

/* === Config injectée par PHP (fallback sur les valeurs par défaut) === */
const CLEV = window.CLEV || {};

/* === Hero rotating headline === */
const rotator = document.querySelector("[data-rotator]");

if (rotator && !reducedMotion) {
  const linePrimary = rotator.querySelector(".rotator-primary");
  const lineSecondary = rotator.querySelector(".rotator-secondary");
  const phrases = CLEV.heroPhrases || [
    ["Votre beauté.", "Votre moment."],
    ["Votre éclat.", "Notre passion."],
    ["Votre bien-être.", "Notre priorité."],
    ["Une parenthèse.", "Rien qu'à vous."],
  ];
  let rotatorIndex = 0;
  const ROTATE_DELAY = 4000;
  const FADE_DURATION = 550;

  setInterval(() => {
    rotator.classList.add("is-fading");
    setTimeout(() => {
      rotatorIndex = (rotatorIndex + 1) % phrases.length;
      linePrimary.textContent = phrases[rotatorIndex][0];
      lineSecondary.textContent = phrases[rotatorIndex][1];
      rotator.classList.remove("is-fading");
    }, FADE_DURATION);
  }, ROTATE_DELAY);
}

/* === Single-line rotating texts (manifesto, contact) === */
const createSingleRotator = (selector, phrases, delay = 4200) => {
  const el = document.querySelector(selector);
  if (!el || reducedMotion || phrases.length < 2) return;
  let i = 0;
  setInterval(() => {
    el.classList.add("is-fading");
    setTimeout(() => {
      i = (i + 1) % phrases.length;
      el.textContent = phrases[i];
      el.classList.remove("is-fading");
    }, 550);
  }, delay);
};

createSingleRotator("[data-rotator-manifesto]", CLEV.manifestoWords || [
  "vous faire rayonner.",
  "vous faire vibrer.",
  "sublimer votre éclat.",
  "vous faire respirer.",
]);

createSingleRotator("[data-rotator-contact]", CLEV.contactWords || [
  "rayonner ?",
  "vous évader ?",
  "vous sublimer ?",
  "souffler ?",
], 4600);

/* === Quote rotating === */
const quotePrimary = document.querySelector("[data-quote-primary]");
const quoteSecondary = document.querySelector("[data-quote-secondary]");

if (quotePrimary && quoteSecondary && !reducedMotion) {
  const quotes = CLEV.quotes || [
    ["Prendre soin de soi n'est pas un luxe.", "C'est une façon de se retrouver."],
    ["La beauté commence là où", "vous décidez d'être vous-même."],
    ["Le bien-être n'est pas une destination.", "C'est un art de vivre."],
  ];
  let quoteIndex = 0;
  const quoteBlock = quotePrimary.closest(".quote-rotator");

  setInterval(() => {
    quoteBlock.classList.add("is-fading");
    setTimeout(() => {
      quoteIndex = (quoteIndex + 1) % quotes.length;
      quotePrimary.textContent = quotes[quoteIndex][0];
      quoteSecondary.textContent = quotes[quoteIndex][1];
      quoteBlock.classList.remove("is-fading");
    }, 550);
  }, 6000);
}

/* === Hero carousel === */
const carousel = document.querySelector("[data-carousel]");

if (carousel) {
  const track = carousel.querySelector(".hero-carousel-track");
  const slides = Array.from(track.querySelectorAll("img"));
  const prevBtn = carousel.querySelector("[data-carousel-prev]");
  const nextBtn = carousel.querySelector("[data-carousel-next]");
  const dotsContainer = carousel.querySelector("[data-carousel-dots]");
  const total = slides.length;
  let current = 0;
  let autoplayTimer = null;
  const AUTOPLAY_DELAY = 5000;

  // Build dots
  slides.forEach((_, i) => {
    const dot = document.createElement("button");
    dot.type = "button";
    dot.setAttribute("aria-label", `Aller à l'image ${i + 1}`);
    dot.addEventListener("click", () => goTo(i, true));
    dotsContainer.appendChild(dot);
  });
  const dots = Array.from(dotsContainer.querySelectorAll("button"));

  const update = () => {
    track.style.transform = `translateX(-${current * 100}%)`;
    dots.forEach((dot, i) => dot.classList.toggle("is-active", i === current));
  };

  const goTo = (index, fromUser = false) => {
    current = (index + total) % total;
    update();
    if (fromUser) restartAutoplay();
  };

  const next = () => goTo(current + 1);
  const prev = () => goTo(current - 1);

  const startAutoplay = () => {
    autoplayTimer = setInterval(next, AUTOPLAY_DELAY);
  };

  const stopAutoplay = () => {
    if (autoplayTimer) clearInterval(autoplayTimer);
    autoplayTimer = null;
  };

  const restartAutoplay = () => {
    stopAutoplay();
    startAutoplay();
  };

  nextBtn.addEventListener("click", () => next());
  prevBtn.addEventListener("click", () => prev());

  carousel.addEventListener("mouseenter", stopAutoplay);
  carousel.addEventListener("mouseleave", startAutoplay);

  // Touch / swipe support
  let touchStartX = 0;
  let touchDeltaX = 0;

  carousel.addEventListener(
    "touchstart",
    (e) => {
      touchStartX = e.touches[0].clientX;
      touchDeltaX = 0;
      stopAutoplay();
    },
    { passive: true },
  );

  carousel.addEventListener(
    "touchmove",
    (e) => {
      touchDeltaX = e.touches[0].clientX - touchStartX;
    },
    { passive: true },
  );

  carousel.addEventListener("touchend", () => {
    if (Math.abs(touchDeltaX) > 50) {
      if (touchDeltaX < 0) next();
      else prev();
    }
    startAutoplay();
  });

  // Respect reduced motion
  update();
  if (!reducedMotion) startAutoplay();
}

/* === Gallery lightbox === */
const lightbox = document.querySelector("[data-lightbox]");

if (lightbox) {
  const lbImg = lightbox.querySelector("[data-lightbox-img]");
  const lbCaption = lightbox.querySelector("[data-lightbox-caption]");
  const lbCounter = lightbox.querySelector("[data-lightbox-counter]");
  const lbClose = lightbox.querySelector("[data-lightbox-close]");
  const lbPrev = lightbox.querySelector("[data-lightbox-prev]");
  const lbNext = lightbox.querySelector("[data-lightbox-next]");

  const galleryItems = Array.from(document.querySelectorAll(".gallery-item")).map((figure) => {
    const img = figure.querySelector("img");
    const title = figure.querySelector("figcaption strong");
    return {
      src: img.getAttribute("src"),
      alt: img.getAttribute("alt") || "",
      caption: title ? title.textContent : "",
    };
  });

  let lbIndex = 0;
  const lbTotal = galleryItems.length;
  let lastFocused = null;

  const renderLightbox = () => {
    const item = galleryItems[lbIndex];
    lbImg.src = item.src;
    lbImg.alt = item.alt;
    lbCaption.textContent = item.caption;
    lbCounter.textContent = `${lbIndex + 1} / ${lbTotal}`;
  };

  const openLightbox = (index) => {
    lbIndex = index;
    lastFocused = document.activeElement;
    renderLightbox();
    lightbox.classList.add("is-open");
    document.body.classList.add("lightbox-open");
    lbClose.focus();
  };

  const closeLightbox = () => {
    lightbox.classList.remove("is-open");
    document.body.classList.remove("lightbox-open");
    if (lastFocused) lastFocused.focus();
  };

  const lbGoTo = (index) => {
    lbIndex = (index + lbTotal) % lbTotal;
    lbImg.style.opacity = "0";
    setTimeout(() => {
      renderLightbox();
      lbImg.style.opacity = "1";
    }, 150);
  };

  galleryItems.forEach((item, i) => {
    const figure = document.querySelectorAll(".gallery-item")[i];
    figure.addEventListener("click", () => openLightbox(i));
    figure.setAttribute("tabindex", "0");
    figure.setAttribute("role", "button");
    figure.setAttribute("aria-label", `Agrandir l'image : ${item.caption}`);
    figure.addEventListener("keydown", (e) => {
      if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        openLightbox(i);
      }
    });
  });

  lbClose.addEventListener("click", closeLightbox);
  lbNext.addEventListener("click", () => lbGoTo(lbIndex + 1));
  lbPrev.addEventListener("click", () => lbGoTo(lbIndex - 1));

  lightbox.addEventListener("click", (e) => {
    if (e.target === lightbox) closeLightbox();
  });

  document.addEventListener("keydown", (e) => {
    if (!lightbox.classList.contains("is-open")) return;
    if (e.key === "Escape") closeLightbox();
    if (e.key === "ArrowRight") lbGoTo(lbIndex + 1);
    if (e.key === "ArrowLeft") lbGoTo(lbIndex - 1);
  });

  // Touch / swipe support
  let lbTouchX = 0;
  let lbDeltaX = 0;

  lightbox.addEventListener(
    "touchstart",
    (e) => {
      lbTouchX = e.touches[0].clientX;
      lbDeltaX = 0;
    },
    { passive: true },
  );

  lightbox.addEventListener(
    "touchmove",
    (e) => {
      lbDeltaX = e.touches[0].clientX - lbTouchX;
    },
    { passive: true },
  );

  lightbox.addEventListener("touchend", () => {
    if (Math.abs(lbDeltaX) > 50) {
      lbGoTo(lbDeltaX < 0 ? lbIndex + 1 : lbIndex - 1);
    }
  });
}

/* === Helpers réservation === */
const markInvalid = (field) => {
  field.style.borderColor = "var(--terracotta)";
  field.addEventListener(
    "input",
    () => { field.style.borderColor = ""; },
    { once: true },
  );
};

const formatDate = (value) =>
  value
    ? new Date(value + "T00:00:00").toLocaleDateString("fr-FR", {
        weekday: "long",
        day: "numeric",
        month: "long",
        year: "numeric",
      })
    : "À définir ensemble";

const openWhatsApp = (number, text) => {
  window.open(`https://wa.me/${number}?text=${encodeURIComponent(text)}`, "_blank");
};

/* === Booking form → WhatsApp === */
const bookingForm = document.getElementById("booking-form");

if (bookingForm) {
  const WHATSAPP_NUMBER = CLEV.whatsapp || "23672679175";
  const serviceSelect = document.getElementById("booking-service");
  const styleField = document.getElementById("booking-style-field");
  const styleSelect = document.getElementById("booking-style");
  const stylesMap = CLEV.serviceStyles || {};

  // Affiche les types de soins créés par l'admin pour le service choisi
  serviceSelect.addEventListener("change", () => {
    const option = serviceSelect.selectedOptions[0];
    const styles = (option && stylesMap[option.dataset.slug]) || [];

    styleSelect.innerHTML =
      '<option value="" disabled selected>Choisissez un type de soin</option>';
    styles.forEach((s) => {
      const opt = document.createElement("option");
      opt.value = s;
      opt.textContent = s;
      styleSelect.appendChild(opt);
    });
    const other = document.createElement("option");
    other.value = "Autre / à définir ensemble";
    other.textContent = "Autre / à définir ensemble";
    styleSelect.appendChild(other);

    const hasStyles = styles.length > 0;
    styleField.hidden = !hasStyles;
    styleSelect.disabled = !hasStyles;
    styleSelect.required = hasStyles;
  });

  bookingForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const name = document.getElementById("booking-name").value.trim();
    const phone = document.getElementById("booking-phone").value.trim();
    const service = serviceSelect.value;
    const style = styleSelect.required ? styleSelect.value : "";
    const date = document.getElementById("booking-date").value;
    const message = document.getElementById("booking-message").value.trim();

    let valid = true;
    bookingForm.querySelectorAll("[required]").forEach((field) => {
      if (!field.value.trim()) {
        markInvalid(field);
        valid = false;
      }
    });
    if (styleSelect.required && !style) {
      markInvalid(styleSelect);
      valid = false;
    }
    if (!valid) return;

    const text =
      `Bonjour CLEV Beauty & Spa, je souhaite prendre rendez-vous.\n\n` +
      `*Nom :* ${name}\n` +
      `*Téléphone :* ${phone}\n` +
      `*Service :* ${service}\n` +
      (style ? `*Type de soin :* ${style}\n` : "") +
      `*Date souhaitée :* ${formatDate(date)}\n` +
      (message ? `*Précisions :* ${message}\n` : "") +
      `\nMerci de me confirmer la disponibilité.`;

    openWhatsApp(WHATSAPP_NUMBER, text);
  });
}

/* === Réservation par étapes (pages services) === */
const bookingModal = document.querySelector("[data-booking-modal]");

if (bookingModal) {
  const WHATSAPP_NUMBER = CLEV.whatsapp || "23672679175";
  const serviceTitle = (CLEV.service && CLEV.service.title) || "";
  const form = bookingModal.querySelector("#service-booking-form");
  const steps = Array.from(bookingModal.querySelectorAll("[data-step]"));
  const prevBtn = bookingModal.querySelector("[data-step-prev]");
  const nextBtn = bookingModal.querySelector("[data-step-next]");
  const submitBtn = bookingModal.querySelector("[data-step-submit]");
  const counter = bookingModal.querySelector("[data-step-counter]");
  const progress = bookingModal.querySelector("[data-booking-progress]");
  const chips = Array.from(bookingModal.querySelectorAll("[data-style-chip]"));
  const styleError = bookingModal.querySelector("[data-style-error]");
  const summary = bookingModal.querySelector("[data-summary]");
  const closeBtn = bookingModal.querySelector("[data-booking-close]");

  let currentStep = 0;
  let selectedStyle = "";
  let lastFocused = null;

  steps.forEach(() => progress.appendChild(document.createElement("span")));
  const dots = Array.from(progress.children);

  const buildSummary = () => {
    const name = document.getElementById("sb-name").value.trim();
    const phone = document.getElementById("sb-phone").value.trim();
    const date = document.getElementById("sb-date").value;
    const message = document.getElementById("sb-message").value.trim();

    const rows = [["Service", serviceTitle]];
    if (selectedStyle) rows.push(["Type de soin", selectedStyle]);
    rows.push(["Nom", name], ["Téléphone", phone], ["Date souhaitée", formatDate(date)]);
    if (message) rows.push(["Précisions", message]);

    summary.innerHTML = "";
    rows.forEach(([label, value]) => {
      const dt = document.createElement("dt");
      dt.textContent = label;
      const dd = document.createElement("dd");
      dd.textContent = value;
      summary.append(dt, dd);
    });
  };

  const showStep = (index) => {
    currentStep = index;
    steps.forEach((step, i) => {
      step.hidden = i !== index;
      step.classList.toggle("is-active", i === index);
    });
    dots.forEach((dot, i) => dot.classList.toggle("is-active", i <= index));
    counter.textContent = `Étape ${index + 1} sur ${steps.length}`;
    prevBtn.hidden = index === 0;
    nextBtn.hidden = index === steps.length - 1;
    submitBtn.hidden = index !== steps.length - 1;
    if (index === steps.length - 1) buildSummary();
  };

  const validateStep = (index) => {
    const step = steps[index];
    let valid = true;

    if (step.querySelector("[data-style-chips]") && !selectedStyle) {
      styleError.hidden = false;
      valid = false;
    }
    step.querySelectorAll("[required]").forEach((field) => {
      if (!field.value.trim()) {
        markInvalid(field);
        valid = false;
      }
    });
    return valid;
  };

  chips.forEach((chip) => {
    chip.addEventListener("click", () => {
      selectedStyle = chip.dataset.value;
      chips.forEach((c) => c.classList.toggle("is-selected", c === chip));
      if (styleError) styleError.hidden = true;
    });
  });

  nextBtn.addEventListener("click", () => {
    if (validateStep(currentStep)) showStep(currentStep + 1);
  });
  prevBtn.addEventListener("click", () => showStep(currentStep - 1));

  const openModal = (trigger) => {
    lastFocused = trigger;
    bookingModal.classList.add("is-open");
    document.body.classList.add("booking-open");
    showStep(0);
    closeBtn.focus();
  };

  const closeModal = () => {
    bookingModal.classList.remove("is-open");
    document.body.classList.remove("booking-open");
    if (lastFocused) lastFocused.focus();
  };

  document.querySelectorAll("[data-booking-open]").forEach((trigger) => {
    trigger.addEventListener("click", (e) => {
      e.preventDefault();
      openModal(trigger);
    });
  });

  closeBtn.addEventListener("click", closeModal);
  bookingModal.addEventListener("click", (e) => {
    if (e.target === bookingModal) closeModal();
  });
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && bookingModal.classList.contains("is-open")) closeModal();
  });

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const name = document.getElementById("sb-name").value.trim();
    const phone = document.getElementById("sb-phone").value.trim();
    const date = document.getElementById("sb-date").value;
    const message = document.getElementById("sb-message").value.trim();

    const text =
      `Bonjour CLEV Beauty & Spa, je souhaite prendre rendez-vous.\n\n` +
      `*Service :* ${serviceTitle}\n` +
      (selectedStyle ? `*Type de soin :* ${selectedStyle}\n` : "") +
      `*Nom :* ${name}\n` +
      `*Téléphone :* ${phone}\n` +
      `*Date souhaitée :* ${formatDate(date)}\n` +
      (message ? `*Précisions :* ${message}\n` : "") +
      `\nMerci de me confirmer la disponibilité.`;

    openWhatsApp(WHATSAPP_NUMBER, text);
  });
}
