<?php
declare(strict_types=1);

const CONTENT_PATH = __DIR__ . '/data/content.json';
const IMAGES_DIR = __DIR__ . '/assets/images';
const IMAGES_URL = 'assets/images/';

function clev_content(): array {
    static $cache = null;
    if ($cache === null) {
        $cache = json_decode(file_get_contents(CONTENT_PATH), true);
    }
    return $cache;
}

function clev_save(array $data): void {
    file_put_contents(
        CONTENT_PATH,
        json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
    );
}

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

/* Autorise uniquement <br> et <em> dans les titres */
function rh(string $s): string {
    return strip_tags($s, '<br><em>');
}

function img_url(string $file): string {
    return IMAGES_URL . rawurlencode($file);
}

function list_images(): array {
    $files = glob(IMAGES_DIR . '/*.{jpg,jpeg,png,webp,gif}', GLOB_BRACE) ?: [];
    return array_map('basename', $files);
}
