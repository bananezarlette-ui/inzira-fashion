<?php
define('SITE_NAME', 'Inzira Fashion');
define('CURRENCY',  'RWF');

// Auto-detect base URL (works in any subfolder OR web root)
function getBaseURL() {
    $docRoot     = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
    $projectRoot = rtrim(str_replace('\\', '/', dirname(__DIR__)), '/');
    $base        = str_replace($docRoot, '', $projectRoot);
    return rtrim($base, '/');
}
define('BASE_URL', getBaseURL());

// Helper: prefix any internal path with the project base
function base($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

function formatPrice($price) {
    return CURRENCY . ' ' . number_format($price, 0, '.', ',');
}
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
