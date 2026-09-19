<?php
/** Escape output safely */
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/** Truncate long text */
function excerpt($text, $limit = 120) {
    $text = strip_tags($text ?? '');
    if (strlen($text) <= $limit) return $text;
    return substr($text, 0, $limit) . '…';
}

/** Gradient helper */
function gradientClass($id) {
    return 'craft-gradient-' . (intval($id) % 6);
}

/** Redirect helper */
function redirect($url) {
    header("Location: $url");
    exit;
}

/** Flash messages */
function setFlash($type, $msg) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

function getFlash() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['flash'])) return null;
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

/** Experience badge based on years */
function experienceBadge($years) {
    if ($years >= 25) return 'Master Artisan';
    if ($years >= 15) return 'Senior Artisan';
    if ($years >= 8)  return 'Skilled Artisan';
    return 'Emerging Artisan';
}
?>