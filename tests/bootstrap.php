<?php
/**
 * PHPUnit Bootstrap File for WebGSM
 *
 * This file sets up the testing environment for WordPress and WooCommerce tests.
 */

// Define testing constant
define('WEBGSM_TESTING', true);

// Composer autoloader
$composer_autoload = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($composer_autoload)) {
    require_once $composer_autoload;
} else {
    die("Please run 'composer install' before running tests.\n");
}

// WordPress tests directory
$_tests_dir = getenv('WP_TESTS_DIR');
if (!$_tests_dir) {
    $_tests_dir = '/tmp/wordpress-tests-lib';
}

// WordPress core directory
$_core_dir = getenv('WP_CORE_DIR');
if (!$_core_dir) {
    $_core_dir = '/tmp/wordpress';
}

// Give access to tests_add_filter() function
if (file_exists($_tests_dir . '/includes/functions.php')) {
    require_once $_tests_dir . '/includes/functions.php';
}

/**
 * Manually load the plugin being tested
 */
function _manually_load_webgsm_plugin() {
    $base_dir = dirname(__DIR__);

    // Load WooCommerce if available (for integration tests)
    if (defined('WP_PLUGIN_DIR') && file_exists(WP_PLUGIN_DIR . '/woocommerce/woocommerce.php')) {
        require_once WP_PLUGIN_DIR . '/woocommerce/woocommerce.php';
    }

    // Load WebGSM Checkout Pro plugin files
    $plugin_files = [
        $base_dir . '/plugins/webgsm-checkout-pro/includes/class-checkout-anaf.php',
        $base_dir . '/plugins/webgsm-checkout-pro/includes/class-checkout-validate.php',
        $base_dir . '/plugins/webgsm-checkout-pro/includes/class-checkout-save.php',
    ];

    foreach ($plugin_files as $file) {
        if (file_exists($file)) {
            require_once $file;
        }
    }

    // Load theme functions
    $theme_files = [
        $base_dir . '/themes/martfury-child/includes/facturi.php',
        $base_dir . '/themes/martfury-child/includes/n8n-webhooks.php',
    ];

    foreach ($theme_files as $file) {
        if (file_exists($file)) {
            require_once $file;
        }
    }
}

// Load the plugin before WordPress test suite
if (function_exists('tests_add_filter')) {
    tests_add_filter('muplugins_loaded', '_manually_load_webgsm_plugin');
}

// Start up the WP testing environment (if available)
if (file_exists($_tests_dir . '/includes/bootstrap.php')) {
    require_once $_tests_dir . '/includes/bootstrap.php';
} else {
    // If WordPress test suite is not installed, use Brain\Monkey for unit tests
    if (class_exists('Brain\Monkey')) {
        // Brain\Monkey will be initialized in individual test files
        echo "WordPress test suite not found. Using Brain\\Monkey for unit tests.\n";
        echo "To install WordPress tests, run: tests/bin/install-wp-tests.sh\n\n";
    }
}

// Define WordPress constants if not already defined (for unit tests without WP)
if (!defined('ABSPATH')) {
    define('ABSPATH', $_core_dir . '/');
}

if (!function_exists('wp_verify_nonce')) {
    function wp_verify_nonce($nonce, $action) {
        return true; // Mock for unit tests
    }
}

if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field($str) {
        return trim(strip_tags($str));
    }
}

if (!function_exists('sanitize_email')) {
    function sanitize_email($email) {
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }
}

if (!function_exists('is_email')) {
    function is_email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

echo "WebGSM Test Bootstrap Complete\n";
