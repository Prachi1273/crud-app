<?php
/**
 * Helper Functions
 */

/**
 * Sanitize output for HTML display
 */
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Format price with currency
 */
function formatPrice($price) {
    return 'Rs.' . number_format($price, 2);
}

/**
 * Display alert messages
 */
function displayAlert($message, $type = 'success') {
    return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
                ' . escape($message) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
}

/**
 * Redirect to a page
 */
function redirect($url) {
    header("Location: $url");
    exit();
}
?>
