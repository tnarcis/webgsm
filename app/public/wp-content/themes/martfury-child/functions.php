<?php
// Înarcă stilurile temei părinte
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('martfury-parent', get_template_directory_uri() . '/style.css');
});

// PRIORITATE: Încarcă header-account-menu.php ÎNAINTE de tema părinte
require_once get_stylesheet_directory() . '/includes/header-account-menu.php';

// Remove eleganticons preload - loaded via CSS instead
add_action('wp_head', function() {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var preloadLinks = document.querySelectorAll("link[rel=preload][href*=eleganticons]");
            preloadLinks.forEach(function(link) {
                link.remove();
            });
        });
    </script>';
}, 1);

// Încarcă modulele
require_once get_stylesheet_directory() . '/includes/retururi.php';
require_once get_stylesheet_directory() . '/includes/garantie.php';
require_once get_stylesheet_directory() . '/includes/awb-tracking.php';
require_once get_stylesheet_directory() . '/includes/facturi.php';
require_once get_stylesheet_directory() . '/includes/notificari.php';
require_once get_stylesheet_directory() . '/includes/n8n-webhooks.php';
require_once get_stylesheet_directory() . '/includes/facturare-pj.php';
require_once get_stylesheet_directory() . '/includes/my-account-styling.php';
require_once get_stylesheet_directory() . '/includes/webgsm-myaccount-headers.php'; 
require_once get_stylesheet_directory() . '/includes/admin-tools.php';
require_once get_stylesheet_directory() . '/includes/registration-enhanced.php';
require_once get_stylesheet_directory() . '/includes/webgsm-design-system.php';
require_once get_stylesheet_directory() . '/includes/webgsm-myaccount.php';
require_once get_stylesheet_directory() . '/includes/webgsm-myaccount-modals.php';

