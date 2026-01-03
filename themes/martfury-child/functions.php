<?php
// Încarcă stilurile temei părinte
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('martfury-parent', get_template_directory_uri() . '/style.css');
});

// Încarcă modulele
require_once get_stylesheet_directory() . '/includes/retururi.php';
require_once get_stylesheet_directory() . '/includes/garantie.php';
require_once get_stylesheet_directory() . '/includes/awb-tracking.php';
require_once get_stylesheet_directory() . '/includes/facturi.php';
require_once get_stylesheet_directory() . '/includes/notificari.php';
require_once get_stylesheet_directory() . '/includes/n8n-webhooks.php';
require_once get_stylesheet_directory() . '/includes/facturare-pj.php';
require_once get_stylesheet_directory() . '/includes/my-account-styling.php';
require_once get_stylesheet_directory() . '/includes/admin-tools.php';
require_once get_stylesheet_directory() . '/includes/registration-enhanced.php';
require_once get_stylesheet_directory() . '/includes/webgsm-design-system.php';