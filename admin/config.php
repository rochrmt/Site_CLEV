<?php
declare(strict_types=1);

/* ⚠️ Changez ce mot de passe après la première connexion.
   Pour générer un nouveau hash : php -r "echo password_hash('VOTRE_MDP', PASSWORD_DEFAULT);" */
const ADMIN_PASSWORD_HASH = '$2y$10$1GaYBRtOFjX1DFlWE.1m1Oidd5mLMVhy15gYe5hR2/mw08kyufuD.'; // clev2026

session_start();

require_once dirname(__DIR__) . '/lib.php';

function admin_logged(): bool {
    return !empty($_SESSION['clev_admin']);
}

function admin_require(): void {
    if (!admin_logged()) {
        header('Location: login.php');
        exit;
    }
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_check(): void {
    if (($_POST['csrf'] ?? '') !== ($_SESSION['csrf'] ?? '')) {
        http_response_code(403);
        exit('Requête invalide (CSRF).');
    }
}

function flash(string $msg, string $type = 'ok'): void {
    $_SESSION['flash'] = ['msg' => $msg, 'type' => $type];
}

function get_flash(): ?array {
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

/* Upload sécurisé d'image vers assets/images. Retourne le nom de fichier ou null. */
function upload_image(array $file): ?string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) return null;
    if ($file['size'] > 8 * 1024 * 1024) return null;

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];

    /* Détection du type MIME : fileinfo si dispo, sinon getimagesize() */
    $mime = null;
    if (function_exists('mime_content_type')) {
        $mime = mime_content_type($file['tmp_name']);
    } elseif (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = $finfo ? finfo_file($finfo, $file['tmp_name']) : null;
        if ($finfo) finfo_close($finfo);
    }
    if (!$mime) {
        $info = @getimagesize($file['tmp_name']);
        $mime = $info['mime'] ?? null;
    }
    if (!$mime || !isset($allowed[$mime])) {
        /* Dernier recours : valider par l'extension */
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $extMap = ['jpg' => 'jpg', 'jpeg' => 'jpg', 'png' => 'png', 'webp' => 'webp', 'gif' => 'gif'];
        if (!isset($extMap[$ext])) return null;
        if (!@getimagesize($file['tmp_name'])) return null; // vérifie que c'est bien une image
        $ext = $extMap[$ext];
    } else {
        $ext = $allowed[$mime];
    }

    $name = preg_replace('/[^a-zA-Z0-9._-]/', '-', pathinfo($file['name'], PATHINFO_FILENAME));
    $name = trim($name, '.-') ?: 'image';
    $filename = $name . '-' . substr(bin2hex(random_bytes(4)), 0, 6) . '.' . $ext;

    if (move_uploaded_file($file['tmp_name'], IMAGES_DIR . '/' . $filename)) {
        return $filename;
    }
    return null;
}
