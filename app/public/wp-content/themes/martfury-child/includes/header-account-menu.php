<?php
/**
 * WebGSM - Header Account Menu Customization
 * Modifică meniul dropdown din header + SVG icons
 * IMPORTANT: Încărcat PRIMUL în functions.php pentru a suprascrie tema părinte
 */

if (!defined('ABSPATH')) exit;

// ==========================================
// DEZACTIVEAZĂ meniul WordPress custom
// ==========================================
add_filter('has_nav_menu', function($has_nav_menu, $location) {
    if ($location === 'user_logged') {
        return false; // Forțează tema să nu găsească meniu custom
    }
    return $has_nav_menu;
}, 10, 2);

// ==========================================
// OVERRIDE: Funcția martfury_nav_user_menu()
// Trebuie definită ÎNAINTE ca tema părinte să o definească
// ==========================================
if (!function_exists('martfury_nav_user_menu')) {
    function martfury_nav_user_menu() {
        $account = get_permalink(get_option('woocommerce_myaccount_page_id'));
        if (substr($account, -1, 1) != '/') {
            $account .= '/';
        }
        
        // SVG Icons
        $icon_user = '<svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>';
        
        $icon_orders = '<svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>';
        
        $icon_location = '<svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>';
        
        $icon_logout = '<svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>';
        
        $user_menu = [];
        $user_menu[] = sprintf(
            '<ul>
            <li>
                <a href="%s">%s Administrează cont</a>
            </li>
            <li>
                <a href="%s">%s Istoric comenzi</a>
            </li>
            <li>
                <a href="%s">%s Adrese</a>
            </li>
            </ul>',
            esc_url($account . 'edit-account'),
            $icon_user,
            esc_url($account . 'orders'),
            $icon_orders,
            esc_url($account . 'adrese-salvate'),
            $icon_location
        );
        
        return $user_menu;
    }
}

// ==========================================
// CSS + SVG ICONS pentru meniul header
// ==========================================
add_action('wp_head', function() {
    ?>
    <style>
    /* Header Account Menu - SVG Icons + Hover */
    .topbar-menu .extra-menu-item.account-item .account-links ul {
        padding: 5px 0 !important;
    }
    
    .topbar-menu .extra-menu-item.account-item .account-links li {
        padding: 0 !important;
        margin: 0 !important;
    }
    
    .topbar-menu .extra-menu-item.account-item .account-links li a {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        padding: 8px 16px !important;
        transition: all 0.2s ease !important;
        color: #475569 !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        line-height: 1.3 !important;
        text-decoration: none !important;
    }
    
    /* HOVER - Albastru intens pe text SI iconiță */
    .topbar-menu .extra-menu-item.account-item .account-links li a:hover {
        background: #eff6ff !important;
        color: #3b82f6 !important;
    }
    
    .topbar-menu .extra-menu-item.account-item .account-links li a svg.menu-icon {
        width: 12px !important;
        height: 12px !important;
        min-width: 12px !important;
        max-width: 12px !important;
        min-height: 12px !important;
        max-height: 12px !important;
        flex-shrink: 0 !important;
        stroke: #475569 !important;
        transition: all 0.2s ease !important;
        vertical-align: middle !important;
        display: block !important;
    }
    
    /* HOVER - SVG devine albastru */
    .topbar-menu .extra-menu-item.account-item .account-links li a:hover svg.menu-icon,
    .topbar-menu .extra-menu-item.account-item .account-links li a:hover svg.menu-icon path {
        stroke: #3b82f6 !important;
    }
    
    /* ASCUNDE items nedorite din meniu */
    .topbar-menu .extra-menu-item.account-item .account-links li a[href*="retururi"],
    .topbar-menu .extra-menu-item.account-item .account-links li a[href*="garantie"],
    .topbar-menu .extra-menu-item.account-item .account-links li a[href*="date-facturare"] {
        display: none !important;
    }
    </style>
    <?php
}, 100);
