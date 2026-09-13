<?php
/**
 * Améliore les images trop petites (ex. 206px) :
 * upscale bicubique ×4 + accentuation (unsharp mask) + contraste léger.
 * Les originaux sont sauvegardés dans assets/images/originals/.
 * Usage : php optimize-images.php
 */

$dir = __DIR__ . '/assets/images';
$backup = $dir . '/originals';
if (!is_dir($backup)) mkdir($backup, 0775, true);

$MIN_WIDTH = 700;   // en dessous → on améliore
$SCALE = 4;         // facteur d'agrandissement
$processed = 0;

foreach (glob($dir . '/*.jpg') as $file) {
    $name = basename($file);
    $info = getimagesize($file);
    if (!$info) continue;
    [$w, $h] = $info;

    if ($w >= $MIN_WIDTH) {
        echo "SKIP  $name ({$w}x{$h})\n";
        continue;
    }

    // Backup de l'original (une seule fois)
    if (!file_exists("$backup/$name")) copy($file, "$backup/$name");

    $src = imagecreatefromjpeg($file);
    if (!$src) { echo "ERREUR $name\n"; continue; }

    // 1. Upscale bicubique
    $up = imagescale($src, $w * $SCALE, $h * $SCALE, IMG_BICUBIC);
    imagedestroy($src);
    if (!$up) { echo "ERREUR scale $name\n"; continue; }

    // 2. Léger flou puis accentuation → rendu net et doux
    imagefilter($up, IMG_FILTER_GAUSSIAN_BLUR);
    $unsharp = [
        [-1, -1, -1],
        [-1, 16, -1],
        [-1, -1, -1],
    ];
    imageconvolution($up, $unsharp, 8, 0);

    // 3. Contraste et chaleur légers (palette terracotta du site)
    imagefilter($up, IMG_FILTER_CONTRAST, -6);
    imagefilter($up, IMG_FILTER_COLORIZE, 6, 2, 0, 6);
    imagefilter($up, IMG_FILTER_BRIGHTNESS, 4);

    // 4. Sauvegarde JPEG qualité 90
    imagejpeg($up, $file, 90);
    imagedestroy($up);

    $newW = $w * $SCALE;
    $newH = $h * $SCALE;
    $size = round(filesize($file) / 1024);
    echo "OK    $name : {$w}x{$h} → {$newW}x{$newH} ({$size} Ko)\n";
    $processed++;
}

echo "\n$processed image(s) améliorée(s). Originaux dans assets/images/originals/\n";
