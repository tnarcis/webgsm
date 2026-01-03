<?php
/**
 * MODUL STYLING MY ACCOUNT - VERSIUNEA 2
 * Design modern, subtil, compact
 */

add_action('wp_head', function() {
    if(!is_account_page() && !is_checkout()) return;
    ?>
    <style>
    /* =============================================
       MY ACCOUNT - DESIGN COMPACT & SUBTIL
       ============================================= */
    
    .woocommerce-account .woocommerce {
        max-width: 1200px;
        margin: 0 auto;
        padding: 15px;
    }
    
    /* =============================================
       MENIU NAVIGARE - COMPACT
       ============================================= */
    .woocommerce-MyAccount-navigation {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 8px rgba(0,0,0,0.06);
        overflow: hidden;
        margin-bottom: 20px;
    }
    
    .woocommerce-MyAccount-navigation ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .woocommerce-MyAccount-navigation ul li {
        margin: 0;
        border-bottom: 1px solid #f5f5f5;
    }
    
    .woocommerce-MyAccount-navigation ul li:last-child {
        border-bottom: none;
    }
    
    .woocommerce-MyAccount-navigation ul li a {
        display: flex;
        align-items: center;
        padding: 11px 16px;
        color: #666;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.15s ease;
    }
    
    .woocommerce-MyAccount-navigation ul li a:hover {
    color: #444;
}

.woocommerce-MyAccount-navigation ul li.is-active a {
    color: #333;
    font-weight: 600;
}
    
    
    /* Iconițe pentru meniu */
    .woocommerce-MyAccount-navigation ul li a::before {
        font-size: 16px;
        margin-right: 10px;
        width: 20px;
        text-align: center;
        opacity: 0.8;
    }
    
    .woocommerce-MyAccount-navigation-link--dashboard a::before { content: '🏠'; }
    .woocommerce-MyAccount-navigation-link--orders a::before { content: '📦'; }
    .woocommerce-MyAccount-navigation-link--retururi a::before { content: '↩️'; }
    .woocommerce-MyAccount-navigation-link--garantie a::before { content: '🛡️'; }
    .woocommerce-MyAccount-navigation-link--facturi a::before { content: '📄'; }
    .woocommerce-MyAccount-navigation-link--date-facturare a::before { content: '🏢'; }
    .woocommerce-MyAccount-navigation-link--downloads a::before { content: '⬇️'; }
    .woocommerce-MyAccount-navigation-link--edit-address a::before { content: '📍'; }
    .woocommerce-MyAccount-navigation-link--edit-account a::before { content: '👤'; }
    .woocommerce-MyAccount-navigation-link--customer-logout a::before { content: '🚪'; }
    
    .woocommerce-MyAccount-navigation-link--customer-logout a {
        color: #999;
    }
    .woocommerce-MyAccount-navigation-link--customer-logout a:hover {
        color: #e53935;
        background: #fff5f5;
    }
    
    /* =============================================
       CONȚINUT PRINCIPAL
       ============================================= */
    .woocommerce-MyAccount-content {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 8px rgba(0,0,0,0.06);
        padding: 25px;
    }
    
    .woocommerce-MyAccount-content h3 {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin: 0 0 18px 0;
        padding-bottom: 12px;
        border-bottom: 1px solid #eee;
    }
    
    /* =============================================
       TABEL COMENZI - COMPACT
       ============================================= */
    .woocommerce-orders-table {
        width: 100%;
        border-collapse: collapse;
        margin: 15px 0;
        font-size: 13px;
    }
    
    .woocommerce-orders-table thead {
        background: #fafafa;
    }
    
    .woocommerce-orders-table th {
        padding: 10px 12px;
        text-align: left;
        font-weight: 600;
        color: #888;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #eee;
    }
    
    .woocommerce-orders-table td {
        padding: 12px;
        border-bottom: 1px solid #f5f5f5;
        vertical-align: middle;
        color: #555;
    }
    
    .woocommerce-orders-table tbody tr:hover {
        background: #fcfcfc;
    }
    
    .woocommerce-orders-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* =============================================
       BUTOANE - MICI & ROTUNJITE
       ============================================= */
    .woocommerce-MyAccount-content .button,
    .woocommerce-MyAccount-content button,
    .woocommerce-orders-table .button,
    .woocommerce a.button,
    a.button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 14px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.15s ease;
        border: 1px solid #ddd;
        cursor: pointer;
        background: #fff;
        color: #555;
        gap: 5px;
    }
    
    .woocommerce-MyAccount-content .button:hover,
    .woocommerce-orders-table .button:hover,
    .woocommerce a.button:hover,
    a.button:hover {
        background: #f5f5f5;
        border-color: #ccc;
        color: #333;
    }
    
    /* Butoane în tabel - foarte compacte */
.woocommerce-orders-table .button,
.woocommerce-orders-table a.button {
    padding: 3px 8px;
    font-size: 9px;
    margin: 1px;
    border-radius: 4px;
    }
    
    /* Buton submit */
    button[type="submit"],
    .button.alt,
    input[type="submit"] {
        background: #4a4a4a !important;
        color: #fff !important;
        border: none !important;
    }
    
    button[type="submit"]:hover,
    .button.alt:hover,
    input[type="submit"]:hover {
        background: #333 !important;
    }
    
    /* Buton căutare ANAF */
    .btn-cauta-cui,
    .btn-cauta,
    #btn_cauta_cui,
    #btn_cauta_cui_checkout {
        padding: 8px 14px !important;
        font-size: 12px !important;
        border-radius: 6px !important;
        background: #555 !important;
        color: #fff !important;
        border: none !important;
        font-weight: 500 !important;
    }
    
    .btn-cauta-cui:hover,
    .btn-cauta:hover,
    #btn_cauta_cui:hover,
    #btn_cauta_cui_checkout:hover {
        background: #333 !important;
    }
    
    /* =============================================
       FORMULARE - COMPACT
       ============================================= */
    .woocommerce-MyAccount-content .form-row {
        margin-bottom: 15px;
    }
    
    .woocommerce-MyAccount-content label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: #666;
        font-size: 13px;
    }
    
    .woocommerce-MyAccount-content input[type="text"],
    .woocommerce-MyAccount-content input[type="email"],
    .woocommerce-MyAccount-content input[type="password"],
    .woocommerce-MyAccount-content input[type="tel"],
    .woocommerce-MyAccount-content select,
    .woocommerce-MyAccount-content textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.15s ease;
        background: #fff;
        color: #333;
    }
    
    .woocommerce-MyAccount-content input:focus,
    .woocommerce-MyAccount-content select:focus,
    .woocommerce-MyAccount-content textarea:focus {
        border-color: #999;
        outline: none;
    }
    
    .woocommerce-MyAccount-content select {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 10 10'%3E%3Cpath fill='%23666' d='M5 7L1 3h8z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 35px;
    }
    
    /* =============================================
       MESAJE - SUBTILE
       ============================================= */
    .woocommerce-message {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 15px;
        color: #166534;
        font-size: 13px;
    }
    
    .woocommerce-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 15px;
        color: #991b1b;
        font-size: 13px;
    }
    
    .woocommerce-info {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 15px;
        color: #1e40af;
        font-size: 13px;
    }
    
    /* =============================================
       STATUS - COMPACT
       ============================================= */
    .woocommerce-orders-table td span[style*="color:orange"],
    .woocommerce-orders-table td span[style*="color:green"],
    .woocommerce-orders-table td span[style*="color:red"],
    .woocommerce-orders-table td span[style*="color:blue"] {
        font-size: 11px !important;
        font-weight: 600 !important;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    /* =============================================
       CHECKOUT - STYLING FACTURARE
       ============================================= */
    .tip-factura-checkout {
        background: #fafafa !important;
        padding: 18px !important;
        border-radius: 10px !important;
        border: 1px solid #eee !important;
    }
    
    .tip-factura-checkout h4 {
        font-size: 14px !important;
        color: #555 !important;
        margin: 0 0 12px 0 !important;
    }
    
    .tip-factura-toggle label {
        padding: 12px 10px !important;
        border-radius: 8px !important;
        border: 1px solid #ddd !important;
        font-size: 13px !important;
    }
    
    .tip-factura-toggle label.active {
        border-color: #999 !important;
        background: #f5f5f5 !important;
    }
    
    .tip-factura-toggle .icon {
        font-size: 22px !important;
    }
    
    .tip-factura-toggle .text {
        font-size: 12px !important;
        color: #555 !important;
    }
    
    .campuri-pj-checkout {
        border: 1px solid #ddd !important;
        border-radius: 10px !important;
        padding: 18px !important;
    }
    
    .campuri-pj-checkout h4 {
        font-size: 14px !important;
        color: #555 !important;
    }
    
    .campuri-pj-checkout input {
        padding: 10px 12px !important;
        border-radius: 6px !important;
        font-size: 13px !important;
    }
    
    .campuri-pj-checkout .anaf-msg {
        font-size: 12px !important;
        padding: 10px 12px !important;
        border-radius: 6px !important;
    }
    
    .campuri-pj-checkout .firma-salvata {
        padding: 12px 15px !important;
        border-radius: 6px !important;
        font-size: 13px !important;
        background: #f5f5f5 !important;
        border: 1px solid #e5e5e5 !important;
    }
    
    /* =============================================
       DATE FACTURARE IN CONT
       ============================================= */
    .facturare-toggle label {
        padding: 15px !important;
        border-radius: 10px !important;
        border: 1px solid #ddd !important;
    }
    
    .facturare-toggle label.active {
        border-color: #999 !important;
        background: #f8f8f8 !important;
    }
    
    .facturare-toggle .toggle-icon {
        font-size: 26px !important;
    }
    
    .facturare-toggle .toggle-title {
        font-size: 13px !important;
        color: #555 !important;
    }
    
    .firma-box {
        border: 1px solid #ddd !important;
        border-radius: 10px !important;
        padding: 20px !important;
    }
    
    .firma-box h4 {
        font-size: 14px !important;
        color: #555 !important;
    }
    
    .firma-box input {
        padding: 10px 12px !important;
        border-radius: 6px !important;
        border: 1px solid #ddd !important;
    }
    
    .firma-box input:focus {
        border-color: #999 !important;
        box-shadow: none !important;
    }
    
    .anaf-result {
        border-radius: 6px !important;
        padding: 10px 12px !important;
        font-size: 13px !important;
    }
    
    .anaf-result.success {
        background: #f0fdf4 !important;
        border: 1px solid #bbf7d0 !important;
        color: #166534 !important;
    }
    
    .anaf-result.error {
        background: #fef2f2 !important;
        border: 1px solid #fecaca !important;
        color: #991b1b !important;
    }
    
    .anaf-result.loading {
        background: #f5f5f5 !important;
        border: 1px solid #e5e5e5 !important;
        color: #666 !important;
    }
    
    /* =============================================
       RESPONSIVE
       ============================================= */
    @media (max-width: 768px) {
        .woocommerce-MyAccount-navigation ul li a {
            padding: 10px 14px;
            font-size: 12px;
        }
        
        .woocommerce-MyAccount-content {
            padding: 18px;
        }
        
        .woocommerce-orders-table th,
        .woocommerce-orders-table td {
            padding: 10px 8px;
            font-size: 12px;
        }
        
        .woocommerce-orders-table .button {
            padding: 4px 8px;
            font-size: 10px;
        }
    }
    
    /* =============================================
       EXTRA - MISC
       ============================================= */
    .woocommerce-MyAccount-content a {
        color: #555;
        text-decoration: none;
    }
    
    .woocommerce-MyAccount-content a:hover {
        color: #333;
        text-decoration: underline;
    }
    
    .woocommerce-MyAccount-content hr {
        border: none;
        border-top: 1px solid #eee;
        margin: 25px 0;
    }
    
    .woocommerce-Address {
        background: #fafafa;
        padding: 15px;
        border-radius: 8px;
    }
    
    .woocommerce-Address-title h3 {
        font-size: 14px !important;
        margin: 0 0 10px 0 !important;
        padding: 0 !important;
        border: none !important;
    }

/* Schimbă textul butonului Vezi în Detalii */
.woocommerce-orders-table .woocommerce-button.button.view {
    font-size: 0;
}
.woocommerce-orders-table .woocommerce-button.button.view::after {
    content: 'Detalii';
    font-size: 11px;
}
/* Buton factură - evidențiat */
.woocommerce-orders-table .button.factura,
.woocommerce-orders-table a[href*="download_factura"],
.woocommerce-orders-table a[href*="download_storno"] {
    background: #f0f7ff !important;
    border-color: #cce0ff !important;
    color: #1a73e8 !important;
}

.woocommerce-orders-table a[href*="download_factura"]:hover,
.woocommerce-orders-table a[href*="download_storno"]:hover {
    background: #e0efff !important;
    border-color: #99c9ff !important;
}
/* Butoane tabel - înălțime redusă */
.woocommerce-orders-table .button,
.woocommerce-orders-table a.button,
.woocommerce-orders-table .woocommerce-button {
    padding: 3px 8px !important;
    font-size: 10px !important;
    line-height: 1.2 !important;
    min-height: unset !important;
    height: auto !important;
}
/* Coloană dată - format scurt */
.woocommerce-orders-table td.woocommerce-orders-table__cell-order-date {
    white-space: nowrap;
}

/* Ascunde textul "pentru X articole" de la total */
.woocommerce-orders-table td.woocommerce-orders-table__cell-order-total small,
.woocommerce-orders-table td.woocommerce-orders-table__cell-order-total .amount + br,
.woocommerce-orders-table td.woocommerce-orders-table__cell-order-total br {
    display: none !important;
}

/* Coloană total - compact */
.woocommerce-orders-table td.woocommerce-orders-table__cell-order-total {
    white-space: nowrap;
}
// Schimbă formatul datei în comenzi
add_filter('woocommerce_my_account_my_orders_columns', function($columns) {
    return $columns;
});

// Format dată scurt
add_filter('woocommerce_order_date_format', function() {
    return 'd.m.Y';
});

/* Tabel comenzi - rânduri mai subțiri */
.woocommerce-orders-table td {
    padding: 8px 10px !important;
}

.woocommerce-orders-table th {
    padding: 8px 10px !important;
}

/* Status - pe o singură linie */
.woocommerce-orders-table td.woocommerce-orders-table__cell-order-status {
    white-space: nowrap;
    min-width: 90px;
}

/* Coloană acțiuni - mai compactă */
.woocommerce-orders-table td.woocommerce-orders-table__cell-order-actions {
    white-space: nowrap;
}

/* Elimină spațiul dublu / background-ul gri de pe rânduri */
.woocommerce-orders-table tbody tr {
    background: #fff !important;
}

.woocommerce-orders-table tbody tr:hover {
    background: #fafafa !important;
}

//* Elimină orice styling alternativ pe rânduri */
.woocommerce-orders-table tbody tr:nth-child(odd),
.woocommerce-orders-table tbody tr:nth-child(even) {
    background: #fff !important;
}
.woocommerce-orders-table tbody tr:nth-child(odd):hover,
.woocommerce-orders-table tbody tr:nth-child(even):hover {
    background: #fafafa !important;
}
/* Forțează rânduri subțiri */
.woocommerce-orders-table,
.woocommerce-orders-table tbody,
.woocommerce-orders-table tr,
.woocommerce-orders-table td,
.woocommerce-orders-table th {
    padding-top: 6px !important;
    padding-bottom: 6px !important;
}

/* Elimină complet textul "pentru X elemente" */
.woocommerce-orders-table__cell-order-total {
    font-size: 0;
}
.woocommerce-orders-table__cell-order-total .woocommerce-Price-amount {
    font-size: 13px !important;
}
.woocommerce-orders-table__cell-order-total small {
    display: none !important;
}

/* Coloana order number - fără extra spațiu */
.woocommerce-orders-table__cell-order-number {
    padding-left: 10px !important;
}

/* Toate celulele - spațiere uniformă */
.woocommerce-orders-table td {
    padding: 6px 8px !important;
    line-height: 1.3 !important;
}

.woocommerce-orders-table th {
    padding: 8px !important;
}
    </style>
    <?php
});

// Forțează formatul datei scurt
add_filter('woocommerce_my_account_my_orders_query', function($args) {
    return $args;
});

add_action('init', function() {
    add_filter('date_i18n', function($date, $format, $timestamp) {
        if($format === wc_date_format() && is_account_page()) {
            return date('d.m.Y', $timestamp);
        }
        return $date;
    }, 10, 3);
});
// Adaugă link-uri în dropdown-ul Account din header
add_action('wp_footer', function() {
    if(!is_user_logged_in()) return;
    ?>
    <script>
    jQuery(document).ready(function($) {
        // Găsește li-ul cu link-ul "Istoric comenzi" și adaugă după el
        var orderLi = $('a[href*="my-account/orders"]').filter(function() {
            return $(this).closest('.woocommerce-MyAccount-navigation').length === 0;
        }).closest('li');
        
        if(orderLi.length) {
            var newLinks = '<li><a href="<?php echo wc_get_account_endpoint_url("retururi"); ?>">↩️ Retururi</a></li>' +
                '<li><a href="<?php echo wc_get_account_endpoint_url("garantie"); ?>">🛡️ Garanție</a></li>' +
                '<li><a href="<?php echo wc_get_account_endpoint_url("date-facturare"); ?>">🏢 Date Facturare</a></li>';
            
            orderLi.after(newLinks);
        }
    });
    </script>
    <?php
});
