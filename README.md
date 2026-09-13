# CLEV Beauty & Spa

Site vitrine de l'institut CLEV Beauty & Spa à Bangui, avec un **dashboard d'administration** pour gérer tout le contenu (textes, images, services).

## Lancer le site

Le site nécessite **PHP** (≥ 8.0). Le fichier `php.ini` du projet active les extensions `gd` et `fileinfo` :

```bash
php -c php.ini -S localhost:4173
```

Ouvrir ensuite `http://localhost:4173`.

## Améliorer les images

Les images trop petites (< 700 px de large) peuvent être améliorées automatiquement
(upscale bicubique + accentuation + contraste) :

```bash
php -c php.ini optimize-images.php
```

Les originaux sont sauvegardés dans `assets/images/originals/`.

## Dashboard d'administration

Accès : `http://localhost:4173/admin/`

Mot de passe par défaut : `clev2026` — **à changer** dans `admin/config.php` :

```bash
php -r "echo password_hash('NOUVEAU_MDP', PASSWORD_DEFAULT);"
```

Le dashboard permet de gérer :

- **Textes du site** : hero (phrases rotatives), manifesto, section expérience, en-têtes, citations, formulaire, footer
- **Services** : modifier, ajouter ou supprimer des services (chaque service a sa page détaillée via `service.php?slug=…`)
- **Galerie** : upload d'images, légendes, formats, ordre
- **Images du hero** : carousel de la page d'accueil
- **Contact & réglages** : téléphones, adresse, email, réseaux, numéro WhatsApp

## Structure

- `index.php` : page d'accueil (rendue depuis `data/content.json`)
- `service.php` : pages détaillées des services
- `data/content.json` : tout le contenu éditable
- `admin/` : dashboard d'administration (protégé par mot de passe)
- `inc/` : partials PHP partagés (header, footer)
- `lib.php` : helpers (chargement/sauvegarde du contenu)
- `styles.css` : identité visuelle et responsive design
- `script.js` : carousel, lightbox, animations, formulaire WhatsApp
- `assets/images` : visuels de l'institut
