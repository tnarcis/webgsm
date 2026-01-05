<?php
/**
 * WebGSM My Account - Meniu restructurat (fără a strica endpoint-uri existente)
 * @version 1.1
 */
if (!defined('ABSPATH')) exit;

// ==========================================
// MODIFICĂ MENIUL (nu îl înlocuiește complet)
// ==========================================
add_filter('woocommerce_account_menu_items', function($items) {
    
    // Redenumește items existente
    if (isset($items['dashboard'])) $items['dashboard'] = 'Panou control';
    if (isset($items['orders'])) $items['orders'] = 'Comenzi';
    if (isset($items['downloads'])) $items['downloads'] = 'Descărcări';
    if (isset($items['edit-account'])) $items['edit-account'] = 'Detalii cont';
    if (isset($items['customer-logout'])) $items['customer-logout'] = 'Ieșire din cont';
    
    // Reordonează
    $order = [
        'dashboard',
        'orders',
        'retururi',
        'garantie',
        'downloads',
        'edit-address',
        'adrese-salvate',
        'date-facturare',
        'edit-account',
        'customer-logout'
    ];
    
    $sorted = [];
    foreach ($order as $key) {
        if (isset($items[$key])) {
            $sorted[$key] = $items[$key];
        }
    }
    
    // Adaugă items rămase
    foreach ($items as $key => $val) {
        if (!isset($sorted[$key])) {
            $sorted[$key] = $val;
        }
    }
    
    return $sorted;
}, 99);

// ==========================================
// CSS - DOAR ICONIȚE LINE ART
// ==========================================
add_action('wp_head', function() {
    if (!is_account_page()) return;
    ?>
    <style id="webgsm-myaccount-icons">
    /* Reset iconițe vechi (emoji) */
    .woocommerce-MyAccount-navigation ul li a::before {
        content: none !important;
    }
    
    /* Iconițe Line Art - SVG */
    .woocommerce-MyAccount-navigation ul li a {
        position: relative;
        padding-left: 44px !important;
    }
    
    .woocommerce-MyAccount-navigation ul li a::after {
        content: '';
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.6;
    }
    
    .woocommerce-MyAccount-navigation ul li a:hover::after,
    .woocommerce-MyAccount-navigation ul li.is-active a::after {
        opacity: 1;
    }
    
    /* Dashboard */
    .woocommerce-MyAccount-navigation-link--dashboard a::after {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23666' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25'/%3E%3C/svg%3E");
    }
    
    /* Comenzi */
    .woocommerce-MyAccount-navigation-link--orders a::after {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23666' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z'/%3E%3C/svg%3E");
    }
    
    /* Retururi */
    .woocommerce-MyAccount-navigation-link--retururi a::after {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23666' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3'/%3E%3C/svg%3E");
    }
    
    /* Garanție */
    .woocommerce-MyAccount-navigation-link--garantie a::after {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23666' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z'/%3E%3C/svg%3E");
    }
    
    /* Descărcări */
    .woocommerce-MyAccount-navigation-link--downloads a::after {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23666' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3'/%3E%3C/svg%3E");
    }
    
    /* Adresă / edit-address */
    .woocommerce-MyAccount-navigation-link--edit-address a::after {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23666' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M15 10.5a3 3 0 11-6 0 3 3 0 016 0z'/%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z'/%3E%3C/svg%3E");
    }
    
    /* Adrese salvate */
    .woocommerce-MyAccount-navigation-link--adrese-salvate a::after {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23666' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z'/%3E%3C/svg%3E");
    }
    
    /* Date facturare */
    .woocommerce-MyAccount-navigation-link--date-facturare a::after {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23666' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z'/%3E%3C/svg%3E");
    }
    
    /* Setări cont */
    .woocommerce-MyAccount-navigation-link--edit-account a::after {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23666' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z'/%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M15 12a3 3 0 11-6 0 3 3 0 016 0z'/%3E%3C/svg%3E");
    }
    
    /* Logout */
    .woocommerce-MyAccount-navigation-link--customer-logout a::after {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23666' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75'/%3E%3C/svg%3E");
    }
    
    /* Hover - albastru */
    .woocommerce-MyAccount-navigation ul li a:hover::after,
    .woocommerce-MyAccount-navigation ul li.is-active a::after {
        filter: brightness(0) saturate(100%) invert(37%) sepia(74%) saturate(1555%) hue-rotate(200deg) brightness(91%) contrast(92%);
    }
    /* SEPARATOARE MENIU */
.woocommerce-MyAccount-navigation-link--orders {
    margin-top: 5px !important;
    border-top: 1px solid #eee !important;
}

.woocommerce-MyAccount-navigation-link--edit-address {
    margin-top: 5px !important;
    border-top: 1px solid #eee !important;
}

.woocommerce-MyAccount-navigation-link--edit-account {
    margin-top: 5px !important;
    border-top: 1px solid #eee !important;
}

.woocommerce-MyAccount-navigation-link--customer-logout {
    margin-top: 5px !important;
    border-top: 1px solid #eee !important;
}
    </style>
    <?php
}, 99);