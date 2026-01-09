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
       SVG ICONS STYLING
       ============================================= */
    svg.section-icon,
    svg.icon-small,
    svg.icon-action,
    svg.icon-plus-small,
    svg.plus-icon {
        display: inline-block;
        vertical-align: middle;
        stroke: currentColor;
        stroke-width: 1.5;
        fill: none;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
    
    svg.section-icon {
        width: 16px;
        height: 16px;
        margin-right: 6px;
    }
    
    svg.icon-small {
        width: 12px;
        height: 12px;
        margin-right: 4px;
    }
    
    svg.icon-action {
        width: 12px;
        height: 12px;
        margin-right: 3px;
    }
    
    svg.icon-plus-small {
        width: 14px;
        height: 14px;
        margin-right: 4px;
    }
    
    svg.plus-icon {
        width: 16px !important;
        height: 16px !important;
        display: inline-block !important;
        flex-shrink: 0;
    }
    
    /* =============================================
       MY ACCOUNT - DESIGN COMPACT & SUBTIL
       ============================================= */
    
    .woocommerce-account .woocommerce {
        max-width: 1200px;
        margin: 0 auto;
        padding: 15px;
    }
    
    /* Mobile responsive - containerul principal */
    @media (max-width: 1024px) {
        .woocommerce-account .woocommerce {
            max-width: 100%;
            padding: 12px;
        }
    }
    
    @media (max-width: 768px) {
        .woocommerce-account .woocommerce {
            max-width: 100%;
            padding: 10px;
        }
    }
    
    @media (max-width: 480px) {
        .woocommerce-account .woocommerce {
            max-width: 100%;
            padding: 8px;
        }
    }
    
    /* =============================================
       MENIU NAVIGARE - COLOANE GRUPATE ELEGANTE
       ============================================= */
    .woocommerce-MyAccount-navigation {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 25px;
        padding: 0;
        max-width: 280px;
        border: 1px solid #e8eaed; /* linii continue pe laterale */
    }
    
    .woocommerce-MyAccount-navigation ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        gap: 0;
        width: 100%;
    }
    
    .woocommerce-MyAccount-navigation ul li {
        margin: 0;
        border-bottom: 1px solid #e8eaed;
        padding: 0 !important;
        min-height: 0;
        line-height: 1.3;
    }

    /* Headerele de grup (li fără link) - fallback pe tag-uri interne */
    .woocommerce-MyAccount-navigation ul li > span,
    .woocommerce-MyAccount-navigation ul li > strong,
    .woocommerce-MyAccount-navigation ul li.section-title,
    .woocommerce-MyAccount-navigation ul li.title,
    .woocommerce-MyAccount-navigation ul li:not(:has(a)) {
        display: block;
        padding: 6px 12px !important;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.3px;
        color: #8a8f9c;
        text-transform: uppercase;
        background: #f8f9fa;
        border-top: 1px solid #e8eaed;
        border-bottom: 1px solid #e8eaed;
        min-height: auto;
    }
    
    .woocommerce-MyAccount-navigation ul li:last-child {
        border-bottom: none;
    }
    
    /* Separatori intre grupuri - subțiri, fără spațiu vizual suplimentar */
    .woocommerce-MyAccount-navigation ul li:nth-child(4),
    .woocommerce-MyAccount-navigation ul li:nth-child(6) {
        border-bottom: 1px solid #e8eaed;
    }
    
    .woocommerce-MyAccount-navigation ul li a {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px !important;
        text-decoration: none;
        color: #2c3e50;
        font-size: 11px;
        font-weight: 500;
        transition: all 0.25s ease;
        white-space: nowrap;
        font-family: 'Segoe UI', 'Helvetica Neue', -apple-system, sans-serif;
        letter-spacing: 0.2px;
        border-left: 4px solid transparent;
    }
    
    .woocommerce-MyAccount-navigation ul li a:hover {
        color: #3498db;
        background: rgba(52, 152, 219, 0.06);
        border-left-color: #3498db;
        padding-left: 13px;
    }
    
    .woocommerce-MyAccount-navigation ul li.is-active a {
        color: #3498db;
        background: rgba(52, 152, 219, 0.1);
        border-left-color: #3498db;
        font-weight: 600;
        padding-left: 13px;
    }
    
    
    /* =============================================
       ICONIȚE MENIU - SVG LINE ART
       ============================================= */
    .woocommerce-MyAccount-navigation ul li a::before {
        content: '';
        display: inline-block;
        width: 18px;
        height: 18px;
        margin-right: 8px;
        background-color: #64748b;
        -webkit-mask-size: contain;
        mask-size: contain;
        -webkit-mask-repeat: no-repeat;
        mask-repeat: no-repeat;
        -webkit-mask-position: center;
        mask-position: center;
        transition: background-color 0.25s ease;
        flex-shrink: 0;
    }

    .woocommerce-MyAccount-navigation ul li a:hover::before,
    .woocommerce-MyAccount-navigation ul li.is-active a::before {
        background-color: #2563eb;
    }

    /* Dashboard - Home */
    .woocommerce-MyAccount-navigation-link--dashboard a::before {
        -webkit-mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='m2.25 12 8.954-8.955a1.126 1.126 0 0 1 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25'/%3E%3C/svg%3E");
        mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='m2.25 12 8.954-8.955a1.126 1.126 0 0 1 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25'/%3E%3C/svg%3E");
    }

    /* Comenzi - Shopping Bag */
    .woocommerce-MyAccount-navigation-link--orders a::before {
        -webkit-mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z'/%3E%3C/svg%3E");
        mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z'/%3E%3C/svg%3E");
    }

    /* Retururi - Arrow Uturn Left */
    .woocommerce-MyAccount-navigation-link--retururi a::before {
        -webkit-mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3'/%3E%3C/svg%3E");
        mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3'/%3E%3C/svg%3E");
    }

    /* Garanție - Shield Check */
    .woocommerce-MyAccount-navigation-link--garantie a::before {
        -webkit-mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z'/%3E%3C/svg%3E");
        mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z'/%3E%3C/svg%3E");
    }

    /* Adrese Salvate - Map Pin */
    .woocommerce-MyAccount-navigation-link--adrese-salvate a::before {
        -webkit-mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z'/%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z'/%3E%3C/svg%3E");
        mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z'/%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z'/%3E%3C/svg%3E");
    }

    /* Detalii cont - User */
    .woocommerce-MyAccount-navigation-link--edit-account a::before {
        -webkit-mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z'/%3E%3C/svg%3E");
        mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z'/%3E%3C/svg%3E");
    }

    /* Ieșire din cont - Arrow Right On Rectangle */
    .woocommerce-MyAccount-navigation-link--customer-logout a::before {
        -webkit-mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9'/%3E%3C/svg%3E");
        mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='1.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9'/%3E%3C/svg%3E");
        background-color: #e53935;
    }

    .woocommerce-MyAccount-navigation-link--customer-logout a:hover::before {
        background-color: #c62828;
    }
    
    .woocommerce-MyAccount-navigation-link--customer-logout a {
        color: #e53935 !important;
        border-left-color: transparent;
    }
    .woocommerce-MyAccount-navigation-link--customer-logout a:hover {
        color: #c62828 !important;
        background: rgba(229, 57, 53, 0.1) !important;
        border-left-color: #e53935 !important;
    }
    
    /* Mobile responsive */
    @media (max-width: 768px) {
        .woocommerce-MyAccount-navigation ul li a {
            padding: 9px 12px;
            font-size: 11px;
        }
        
        .woocommerce-MyAccount-navigation ul li a::before {
            font-size: 16px;
        }
    }
    .woocommerce-MyAccount-navigation-link--customer-logout a:hover {
        color: #c62828 !important;
        background: rgba(229, 57, 53, 0.08) !important;
    }
    
    /* Mobile responsive tabs */
    @media (max-width: 768px) {
        .woocommerce-MyAccount-navigation {
            gap: 0;
            padding-bottom: 0;
        }
        
        .woocommerce-MyAccount-navigation ul {
            gap: 0;
        }
        
        .woocommerce-MyAccount-navigation ul li {
            flex: 1;
            min-width: 80px;
        }
        
        .woocommerce-MyAccount-navigation ul li a {
            padding: 10px 12px;
            font-size: 11px;
            flex-direction: column;
            text-align: center;
            gap: 4px;
        }
        
        .woocommerce-MyAccount-navigation ul li a::before {
            margin-right: 0;
            margin-bottom: 0;
            font-size: 18px;
        }
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
        font-size: 22px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0 0 24px 0;
        padding-bottom: 16px;
        border-bottom: 3px solid #2ecc71;
        font-family: 'Segoe UI', 'Helvetica Neue', sans-serif;
        letter-spacing: 0.3px;
        text-transform: capitalize;
    }
    
    /* Stilizare pentru h2 sectiuni - "Achizitiile mele", "Date salvate", "Setari" */
    /* Exclude h2-urile din woocommerce-customer-details si woocommerce-order-details */
    .woocommerce-MyAccount-content h2:not(.woocommerce-column__title):not(.woocommerce-order-details__title) {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin: 30px 0 20px 0;
        padding-bottom: 14px;
        border-bottom: 3px solid #e74c3c;
        font-family: 'Segoe UI', 'Helvetica Neue', sans-serif;
        letter-spacing: 0.4px;
    }
    
    .woocommerce-MyAccount-content h4 {
        font-size: 16px;
        font-weight: 700;
        color: #34495e;
        margin: 18px 0 12px 0;
        font-family: 'Segoe UI', 'Helvetica Neue', sans-serif;
        letter-spacing: 0.2px;
    }
    
    /* =============================================
       TABEL COMENZI - COMPACT
       ============================================= */
    .woocommerce-MyAccount-content table:not(.woocommerce-table--order-details):not(.shop_table),
    .woocommerce-orders-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 13px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .woocommerce-MyAccount-content table thead,
    .woocommerce-orders-table thead {
        background: linear-gradient(135deg, #bdc3c7 0%, #95a5a6 100%);
    }
    
    .woocommerce-MyAccount-content table th,
    .woocommerce-orders-table th {
        padding: 14px 16px;
        text-align: left;
        font-weight: 700;
        color: #fff;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        border-bottom: none;
        border-right: none;
        font-family: 'Segoe UI', 'Helvetica Neue', sans-serif;
    }
    
    .woocommerce-MyAccount-content table td,
    .woocommerce-orders-table td {
        padding: 13px 16px;
        border-bottom: 1px solid #2ecc71;
        border-right: none;
        vertical-align: middle;
        color: #555;
        font-family: 'Segoe UI', 'Helvetica Neue', sans-serif;
    }
    
    .woocommerce-MyAccount-content table tbody tr,
    .woocommerce-orders-table tbody tr {
        transition: all 0.2s ease;
    }
    
    .woocommerce-MyAccount-content table tbody tr:hover,
    .woocommerce-orders-table tbody tr:hover {
        background: rgba(46, 204, 113, 0.06);
        box-shadow: inset 0 0 0 1px rgba(46, 204, 113, 0.15);
    }
    
    .woocommerce-MyAccount-content table tbody tr:last-child td,
    .woocommerce-orders-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* Footer gradient pentru tabel */
    .woocommerce-orders-table tfoot,
    .woocommerce-orders-table tbody:last-child tr:last-child td {
        background: linear-gradient(180deg, #f8f9fa 0%, #e8eaed 100%);
        border-top: 1px solid #d0d0d0;
    }
    
    .woocommerce-orders-table tfoot th,
    .woocommerce-orders-table tfoot td {
        padding: 12px 16px;
        font-weight: 600;
        color: #2c3e50;
        border-right: none;
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

/* =============================================
   RESPONSIVE - ADRESE & FIRME - TELEFON
   ============================================= */

/* Container pentru liste */
.webgsm-addresses-list,
.companies-list,
.persons-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

/* Itemuri radio (adrese, firme, persoane) */
.webgsm-radio.address-item,
.webgsm-radio.company-item,
.webgsm-radio.person-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 12px;
    border: 1px solid #e8e8e8;
    border-radius: 6px;
    background: #fff;
    transition: all 0.2s ease;
}

.webgsm-radio.address-item:hover,
.webgsm-radio.company-item:hover,
.webgsm-radio.person-item:hover {
    border-color: #d0d0d0;
    background: #f9f9f9;
}

/* Radio input */
.webgsm-radio input[type="radio"] {
    margin-top: 4px;
    flex-shrink: 0;
}

/* Bullet pentru default */
.addr-bullet,
.company-bullet,
.person-bullet {
    flex-shrink: 0;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #e8e8e8;
    transition: all 0.2s ease;
}

.addr-bullet.active,
.company-bullet.active,
.person-bullet.active {
    background: #2ecc71;
}

/* Label - text container */
.radio-label {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.radio-label strong {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #2c3e50;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.radio-label small {
    display: block;
    font-size: 11px;
    color: #666;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.radio-label small.webgsm-detail-secondary {
    display: block;
}

/* Delete button */
.webgsm-radio .delete-address,
.webgsm-radio .delete-company,
.webgsm-radio .delete-person {
    flex-shrink: 0;
    background: #f5f5f5;
    border: none;
    border-radius: 4px;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.2s ease;
    color: #999;
    padding: 0;
}

.webgsm-radio .delete-address:hover,
.webgsm-radio .delete-company:hover,
.webgsm-radio .delete-person:hover {
    background: #ffe0e0;
    color: #d32f2f;
}

/* =============================================
   BUTOANE ADAUGĂ - COMPACT ȘI STILIZAT
   ============================================= */
.btn-add-item {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 20px;
    background: #3b82f6;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
}

.btn-add-item:hover {
    background: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
}

.btn-add-item:active {
    transform: translateY(0);
    box-shadow: 0 1px 2px rgba(59, 130, 246, 0.2);
}

/* Mobile responsive */
@media (max-width: 768px) {
    .webgsm-addresses-list,
    .companies-list,
    .persons-list {
        gap: 8px;
    }
    
    .webgsm-radio.address-item,
    .webgsm-radio.company-item,
    .webgsm-radio.person-item {
        padding: 10px;
        gap: 8px;
    }
    
    .radio-label strong {
        font-size: 12px;
    }
    
    .radio-label small {
        font-size: 10px;
    }
    
    .radio-label small.webgsm-detail-secondary {
        display: none !important;
    }
    
    /* Tabel responsive */
    .woocommerce-orders-table {
        font-size: 12px;
    }
    
    .woocommerce-orders-table td,
    .woocommerce-orders-table th {
        padding: 6px 8px !important;
        white-space: normal;
        word-break: break-word;
    }
    
    .woocommerce-orders-table td {
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: table-cell;
    }
}

@media (max-width: 480px) {
    .webgsm-radio.address-item,
    .webgsm-radio.company-item,
    .webgsm-radio.person-item {
        padding: 8px;
        gap: 6px;
    }
    
    .radio-label {
        gap: 2px;
    }
    
    .radio-label strong {
        font-size: 11px;
        max-width: 120px;
    }
    
    .radio-label small {
        font-size: 9px;
        max-width: 120px;
    }
    
    .radio-label small.webgsm-detail-secondary {
        display: none !important;
    }
    
    .webgsm-radio .delete-address,
    .webgsm-radio .delete-company,
    .webgsm-radio .delete-person {
        width: 20px;
        height: 20px;
        font-size: 14px;
    }
    
    /* Tabel responsive telefon */
    .woocommerce-orders-table {
        font-size: 10px;
    }
    
    .woocommerce-orders-table td,
    .woocommerce-orders-table th {
        padding: 4px 6px !important;
        max-width: 80px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
}

    /* =============================================
       TABELE GARANȚII/RETURURI/COMENZI - SCROLL ORIZONTAL MOBILE + FULL WIDTH DESKTOP
       ============================================= */
    
    /* DESKTOP - Tabel la full width pentru TOATE tabelele */
    @media (min-width: 769px) {
        .woocommerce-MyAccount-content .woocommerce-orders-table,
        .woocommerce-MyAccount-content .garantii-table,
        .woocommerce-MyAccount-content table.shop_table,
        .woocommerce-MyAccount-content .my_account_orders {
            display: table !important;
            width: 100% !important;
            overflow-x: visible !important;
            max-width: 100% !important;
        }
        
        .woocommerce-MyAccount-content .woocommerce-orders-table table,
        .woocommerce-MyAccount-content .garantii-table table,
        .woocommerce-MyAccount-content table.shop_table,
        .woocommerce-MyAccount-content .my_account_orders table {
            width: 100% !important;
            table-layout: auto;
            max-width: 100% !important;
        }
        
        .woocommerce-MyAccount-content .woocommerce-orders-table th,
        .woocommerce-MyAccount-content .woocommerce-orders-table td,
        .woocommerce-MyAccount-content .garantii-table th,
        .woocommerce-MyAccount-content .garantii-table td,
        .woocommerce-MyAccount-content table.shop_table th,
        .woocommerce-MyAccount-content table.shop_table td,
        .woocommerce-MyAccount-content .my_account_orders th,
        .woocommerce-MyAccount-content .my_account_orders td {
            padding: 12px 15px !important;
        }
        
        /* Coloana Actiuni centrata si fara diacritice - DESKTOP */
        .woocommerce-MyAccount-content .woocommerce-orders-table thead th:last-child,
        .woocommerce-MyAccount-content table.shop_table thead th:last-child {
            text-align: center !important;
            font-size: 0 !important;
        }
        
        .woocommerce-MyAccount-content .woocommerce-orders-table thead th:last-child::before,
        .woocommerce-MyAccount-content table.shop_table thead th:last-child::before {
            content: 'Actiuni' !important;
            font-size: 13px !important;
            display: block !important;
        }
    }
    
    @media (max-width: 768px) {
        /* MOBILE - Container cu scroll */
        .woocommerce-MyAccount-content {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
        }
        
        /* Tabel fix normal - fără scroll pe el direct */
        .woocommerce-MyAccount-content .woocommerce-orders-table,
        .woocommerce-MyAccount-content .garantii-table,
        .woocommerce-MyAccount-content table.shop_table,
        .woocommerce-MyAccount-content .my_account_orders {
            display: table !important;
            width: 100% !important;
            margin: 0 !important;
            table-layout: fixed !important;
            min-width: 480px !important;
        }
        
        .woocommerce-MyAccount-content .woocommerce-orders-table thead,
        .woocommerce-MyAccount-content .garantii-table thead,
        .woocommerce-MyAccount-content table.shop_table thead,
        .woocommerce-MyAccount-content .my_account_orders thead,
        .woocommerce-MyAccount-content .woocommerce-orders-table tbody,
        .woocommerce-MyAccount-content .garantii-table tbody,
        .woocommerce-MyAccount-content table.shop_table tbody,
        .woocommerce-MyAccount-content .my_account_orders tbody {
            display: table-row-group !important;
        }
        
        .woocommerce-MyAccount-content .woocommerce-orders-table tr,
        .woocommerce-MyAccount-content .garantii-table tr,
        .woocommerce-MyAccount-content table.shop_table tr,
        .woocommerce-MyAccount-content .my_account_orders tr {
            display: table-row !important;
        }
        
        .woocommerce-MyAccount-content .woocommerce-orders-table th,
        .woocommerce-MyAccount-content .woocommerce-orders-table td,
        .woocommerce-MyAccount-content .garantii-table th,
        .woocommerce-MyAccount-content .garantii-table td,
        .woocommerce-MyAccount-content table.shop_table th,
        .woocommerce-MyAccount-content table.shop_table td,
        .woocommerce-MyAccount-content .my_account_orders th,
        .woocommerce-MyAccount-content .my_account_orders td {
            display: table-cell !important;
            white-space: nowrap !important;
            padding: 8px 2px !important;
            font-size: 11px !important;
            vertical-align: middle !important;
        }
        
        /* Lățimi specifice pentru fiecare coloană - COMPACT și ALINIAT */
        .woocommerce-MyAccount-content .woocommerce-orders-table th:nth-child(1),
        .woocommerce-MyAccount-content .woocommerce-orders-table td:nth-child(1),
        .woocommerce-MyAccount-content table.shop_table th:nth-child(1),
        .woocommerce-MyAccount-content table.shop_table td:nth-child(1) {
            width: 65px !important;
        }
        
        .woocommerce-MyAccount-content .woocommerce-orders-table th:nth-child(2),
        .woocommerce-MyAccount-content .woocommerce-orders-table td:nth-child(2),
        .woocommerce-MyAccount-content table.shop_table th:nth-child(2),
        .woocommerce-MyAccount-content table.shop_table td:nth-child(2) {
            width: 75px !important;
            padding: 8px 2px !important;
        }
        
        .woocommerce-MyAccount-content .woocommerce-orders-table th:nth-child(3),
        .woocommerce-MyAccount-content .woocommerce-orders-table td:nth-child(3),
        .woocommerce-MyAccount-content table.shop_table th:nth-child(3),
        .woocommerce-MyAccount-content table.shop_table td:nth-child(3) {
            width: 80px !important;
            padding: 8px 2px !important;
        }
        
        .woocommerce-MyAccount-content .woocommerce-orders-table th:nth-child(4),
        .woocommerce-MyAccount-content .woocommerce-orders-table td:nth-child(4),
        .woocommerce-MyAccount-content table.shop_table th:nth-child(4),
        .woocommerce-MyAccount-content table.shop_table td:nth-child(4) {
            width: 60px !important;
            padding: 8px 2px !important;
        }
        
        /* Font mai mic pentru coloana Total */
        .woocommerce-MyAccount-content .woocommerce-orders-table td:nth-child(4),
        .woocommerce-MyAccount-content .woocommerce-orders-table td:nth-child(4) *,
        .woocommerce-MyAccount-content table.shop_table td:nth-child(4),
        .woocommerce-MyAccount-content table.shop_table td:nth-child(4) * {
            font-size: 10px !important;
        }
        
        /* Ascunde textul extra din coloana Total pe mobile - mai multe variante */
        .woocommerce-MyAccount-content .woocommerce-orders-table td:nth-child(4) .count,
        .woocommerce-MyAccount-content table.shop_table td:nth-child(4) .count,
        .woocommerce-MyAccount-content .woocommerce-orders-table td:nth-child(4) small,
        .woocommerce-MyAccount-content table.shop_table td:nth-child(4) small,
        .woocommerce-MyAccount-content .woocommerce-orders-table .woocommerce-Price-currencySymbol ~ *,
        .woocommerce-MyAccount-content table.shop_table .woocommerce-Price-currencySymbol ~ * {
            display: none !important;
        }
        
        /* Ascunde brl, br, span-uri extra după preț */
        .woocommerce-MyAccount-content .woocommerce-orders-table td.woocommerce-orders-table__cell-order-total br,
        .woocommerce-MyAccount-content table.shop_table td.order-total br {
            display: none !important;
        }
        
        .woocommerce-MyAccount-content .woocommerce-orders-table th:nth-child(5),
        .woocommerce-MyAccount-content .woocommerce-orders-table td:nth-child(5),
        .woocommerce-MyAccount-content table.shop_table th:nth-child(5),
        .woocommerce-MyAccount-content table.shop_table td:nth-child(5) {
            width: 105px !important;
        }
        
        /* Header styling */
        .woocommerce-MyAccount-content .woocommerce-orders-table thead th,
        .woocommerce-MyAccount-content table.shop_table thead th,
        .woocommerce-MyAccount-content .my_account_orders thead th {
            background: #f8f9fa !important;
            font-size: 10px !important;
            font-weight: 600 !important;
            padding: 6px 2px !important;
        }
        
        /* Coloana Actiuni centrata */
        .woocommerce-MyAccount-content .woocommerce-orders-table thead th:nth-child(5),
        .woocommerce-MyAccount-content table.shop_table thead th:nth-child(5) {
            text-align: center !important;
            font-size: 0 !important;
        }
        
        .woocommerce-MyAccount-content .woocommerce-orders-table thead th:nth-child(5)::before,
        .woocommerce-MyAccount-content table.shop_table thead th:nth-child(5)::before {
            content: 'Actiuni' !important;
            font-size: 10px !important;
            display: block !important;
        }
        
        /* Butoane compacte pe mobile - VERTICAL STACK */
        .woocommerce-MyAccount-content .woocommerce-orders-table td:nth-child(5),
        .woocommerce-MyAccount-content table.shop_table td:nth-child(5) {
            padding: 4px 2px !important;
        }
        
        .woocommerce-MyAccount-content .woocommerce-orders-table .button,
        .woocommerce-MyAccount-content table.shop_table .button {
            padding: 4px 2px !important;
            font-size: 9px !important;
            white-space: normal !important;
            line-height: 1.2 !important;
            margin: 2px 0 !important;
            display: block !important;
            width: 100% !important;
            text-align: center !important;
            min-height: 20px !important;
        }
        
        /* Uniformizează textul din butoane */
        .woocommerce-MyAccount-content .woocommerce-orders-table .button *,
        .woocommerce-MyAccount-content table.shop_table .button * {
            font-size: inherit !important;
            font-weight: inherit !important;
        }
        
        /* Link factură vizibil și compact */
        .woocommerce-MyAccount-content .woocommerce-orders-table a[href*="factura"],
        .woocommerce-MyAccount-content table.shop_table a[href*="factura"],
        .woocommerce-MyAccount-content .download-invoice {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            padding: 4px 2px !important;
            font-size: 9px !important;
            width: 100% !important;
            margin: 2px 0 !important;
            text-align: center !important;
            line-height: 1.2 !important;
            white-space: normal !important;
            min-height: 20px !important;
        }
        
        /* Indicator vizual că se poate scroll */
        .woocommerce-MyAccount-content .woocommerce-orders-table::after {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 20px;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.8));
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .woocommerce-MyAccount-content:has(.woocommerce-orders-table) {
            position: relative;
        }
    }
    
    @media (max-width: 480px) {
        .woocommerce-MyAccount-content .woocommerce-orders-table tbody,
        .woocommerce-MyAccount-content .woocommerce-orders-table thead {
            min-width: 450px;
        }
        
        .woocommerce-MyAccount-content .woocommerce-orders-table th,
        .woocommerce-MyAccount-content .woocommerce-orders-table td {
            padding: 6px 8px !important;
            font-size: 11px !important;
        }
    }
    
    /* =============================================
       PAGINA VIEW ORDER - TABEL PRODUSE COMANDATE
       ============================================= */
    
    /* DESKTOP + MOBILE - Coloana dreapta (Total/Detalii) */
    .woocommerce-order-details .woocommerce-table--order-details thead th:last-child,
    .woocommerce-order-details table.shop_table thead th:last-child {
        text-align: right !important;
        font-size: 0 !important;
    }
    
    .woocommerce-order-details .woocommerce-table--order-details thead th:last-child::before,
    .woocommerce-order-details table.shop_table thead th:last-child::before {
        content: 'Detalii' !important;
        font-size: 13px !important;
        display: inline-block !important;
    }
    
    .woocommerce-order-details .woocommerce-table--order-details tbody td:last-child,
    .woocommerce-order-details table.shop_table tbody td:last-child {
        text-align: right !important;
    }
    
    /* Footer - Subtotal, Livrare, Total cu background gri */
    .woocommerce-order-details .woocommerce-table--order-details tfoot tr,
    .woocommerce-order-details table.shop_table tfoot tr {
        background: #f8f9fa !important;
    }
    
    .woocommerce-order-details .woocommerce-table--order-details tfoot th {
        text-align: left !important;
        font-weight: 600 !important;
    }
    
    .woocommerce-order-details .woocommerce-table--order-details tfoot td {
        text-align: right !important;
    }
    
    /* Rename "Acțiuni" to "Descarca" în footer */
    .woocommerce-order-details .woocommerce-table--order-details tfoot .woocommerce-table__product-total,
    .woocommerce-order-details table.shop_table tfoot th:contains('Acțiuni'),
    .woocommerce-order-details table.shop_table tfoot th.woocommerce-table__product-total {
        font-size: 0 !important;
    }
    
    .woocommerce-order-details .woocommerce-table--order-details tfoot .woocommerce-table__product-total::before,
    .woocommerce-order-details table.shop_table tfoot th.woocommerce-table__product-total::before {
        content: 'Descarca' !important;
        font-size: 13px !important;
        display: inline-block !important;
    }
    
    @media (max-width: 768px) {
        /* Scroll pe mobile pentru tabel produse comandate */
        .woocommerce-order-details {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
        }
        
        .woocommerce-order-details table {
            min-width: 480px !important;
        }
        
        .woocommerce-order-details .woocommerce-table--order-details thead th:last-child::before,
        .woocommerce-order-details table.shop_table thead th:last-child::before {
            font-size: 11px !important;
        }
        
        .woocommerce-order-details .woocommerce-table--order-details tfoot .woocommerce-table__product-total::before,
        .woocommerce-order-details table.shop_table tfoot th.woocommerce-table__product-total::before {
            font-size: 11px !important;
        }
    }
    
    /* =============================================
       LINII VERZI - PAGINA DETALII COMANDA
       ============================================= */
    
    /* Linie verde sub "Detalii comanda" - fara diacritice */
    .woocommerce-order-details h2.woocommerce-order-details__title {
        border-bottom: 3px solid #22c55e !important;
        padding-bottom: 10px !important;
        margin-bottom: 20px !important;
        font-size: 0 !important;
    }
    
    .woocommerce-order-details h2.woocommerce-order-details__title::before {
        content: 'Detalii comanda' !important;
        font-size: 24px !important;
        display: block !important;
        font-weight: 700 !important;
    }
    
    /* Linii verzi sub titlurile de adrese */
    .woocommerce-customer-details h2.woocommerce-column__title {
        border-bottom: 3px solid #22c55e !important;
    }
    
    /* Linia verde deasupra sectiunii de adrese */
    .woocommerce-order-details .woocommerce-customer-details {
        border-top: 3px solid #22c55e !important;
        padding-top: 20px !important;
        margin-top: 30px !important;
    }
    
    /* =============================================
       DETALII COMANDA - SCROLL ORIZONTAL MOBILE
       ============================================= */
    
    /* Container wrapper pentru scroll */
    .woocommerce-order-details,
    .woocommerce-customer-details,
    .woocommerce-order-overview,
    .order-details-wrapper {
        width: 100%;
        max-width: 100%;
    }
    
    @media (max-width: 768px) {
        /* Wrapper tabel detalii comanda */
        .woocommerce-table--order-details,
        .woocommerce-order-details table,
        .order_details,
        table.shop_table.order_details {
            display: block !important;
            width: 100% !important;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
        }
        
        /* Tabelul intern pastreaza latimea */
        .woocommerce-table--order-details tbody,
        .woocommerce-table--order-details thead,
        .woocommerce-order-details table tbody,
        .woocommerce-order-details table thead,
        table.shop_table.order_details tbody,
        table.shop_table.order_details thead {
            display: table !important;
            width: 100% !important;
            min-width: 500px !important;
        }
        
        .woocommerce-table--order-details tr,
        .woocommerce-order-details table tr,
        table.shop_table.order_details tr {
            display: table-row !important;
        }
        
        .woocommerce-table--order-details th,
        .woocommerce-table--order-details td,
        .woocommerce-order-details table th,
        .woocommerce-order-details table td,
        table.shop_table.order_details th,
        table.shop_table.order_details td {
            display: table-cell !important;
            white-space: nowrap !important;
            padding: 10px 12px !important;
            vertical-align: middle !important;
        }
        
        /* Coloana produs - permite wrap */
        .woocommerce-table--order-details td.product-name,
        table.shop_table.order_details td.product-name {
            white-space: normal !important;
            min-width: 200px !important;
            max-width: 250px !important;
        }
        
        /* Adrese comanda - stack vertical */
        .woocommerce-columns--addresses {
            display: flex !important;
            flex-direction: column !important;
            gap: 15px !important;
        }
        
        .woocommerce-columns--addresses .woocommerce-column {
            width: 100% !important;
            padding: 15px !important;
            background: #f8fafc !important;
            border-radius: 8px !important;
            border: 1px solid #e5e7eb !important;
        }
        
        .woocommerce-columns--addresses .woocommerce-column h2 {
            font-size: 14px !important;
            margin-bottom: 10px !important;
        }
        
        /* Order overview - info comanda */
        .woocommerce-order-overview {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 10px !important;
            padding: 0 !important;
            margin: 0 0 20px 0 !important;
            list-style: none !important;
        }
        
        .woocommerce-order-overview li {
            flex: 1 1 calc(50% - 10px) !important;
            background: #f8fafc !important;
            padding: 10px 12px !important;
            border-radius: 6px !important;
            border: 1px solid #e5e7eb !important;
            margin: 0 !important;
        }
        
        .woocommerce-order-overview li strong {
            display: block !important;
            font-size: 11px !important;
            color: #6b7280 !important;
            font-weight: 500 !important;
            margin-bottom: 2px !important;
        }
    }
    
    @media (max-width: 480px) {
        .woocommerce-table--order-details tbody,
        .woocommerce-table--order-details thead,
        table.shop_table.order_details tbody,
        table.shop_table.order_details thead {
            min-width: 450px !important;
        }
        
        .woocommerce-table--order-details th,
        .woocommerce-table--order-details td,
        table.shop_table.order_details th,
        table.shop_table.order_details td {
            padding: 8px 10px !important;
            font-size: 12px !important;
        }
        
        .woocommerce-table--order-details td.product-name,
        table.shop_table.order_details td.product-name {
            min-width: 180px !important;
            font-size: 11px !important;
        }
        
        /* Order overview - 1 coloana pe telefoane mici */
        .woocommerce-order-overview li {
            flex: 1 1 100% !important;
        }
        
        .woocommerce-columns--addresses .woocommerce-column {
            padding: 12px !important;
        }
        
        .woocommerce-columns--addresses .woocommerce-column address {
            font-size: 12px !important;
            line-height: 1.5 !important;
        }
    }
    
    /* Buton descarcare factura in pagina detalii comanda - identic cu "Comanda din nou" */
    .woocommerce-order-details a[href*="download_factura"],
    .woocommerce-order-details a[href*="download_storno"],
    table.shop_table.order_details a[href*="download_factura"],
    table.shop_table.order_details a[href*="download_storno"],
    .woocommerce-order-details tfoot a[href*="download"],
    table.shop_table tfoot a[href*="download"] {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        border: 1px solid #667eea !important;
        color: #ffffff !important;
        padding: 8px 16px !important;
        font-size: 13px !important;
        line-height: 1.4 !important;
        display: inline-block !important;
        text-decoration: none !important;
        border-radius: 6px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 2px 4px rgba(102, 126, 234, 0.2) !important;
    }
    
    .woocommerce-order-details a[href*="download_factura"]:hover,
    .woocommerce-order-details a[href*="download_storno"]:hover,
    table.shop_table.order_details a[href*="download_factura"]:hover,
    table.shop_table.order_details a[href*="download_storno"]:hover,
    .woocommerce-order-details tfoot a[href*="download"]:hover,
    table.shop_table tfoot a[href*="download"]:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%) !important;
        border-color: #764ba2 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3) !important;
        transform: translateY(-2px) !important;
    }
    
    @media (max-width: 768px) {
        .woocommerce-order-details a[href*="download_factura"],
        .woocommerce-order-details a[href*="download_storno"],
        table.shop_table.order_details a[href*="download_factura"],
        table.shop_table.order_details a[href*="download_storno"],
        .woocommerce-order-details tfoot a[href*="download"],
        table.shop_table tfoot a[href*="download"] {
            padding: 6px 12px !important;
            font-size: 12px !important;
        }
    }
    
    </style>
    <?php
});

// Adaugă adresa de livrare sub adresa de facturare
add_action('woocommerce_order_details_after_customer_details', function($order) {
    if (!$order) return;
    
    $shipping_address = $order->get_shipping_address_1();
    if (empty($shipping_address)) return;
    
    // Preia datele de livrare din order meta
    $shipping_label = get_post_meta($order->get_id(), '_shipping_address_label', true);
    $shipping_name = trim(get_post_meta($order->get_id(), '_shipping_first_name', true) . ' ' . get_post_meta($order->get_id(), '_shipping_last_name', true));
    $shipping_phone = get_post_meta($order->get_id(), '_shipping_phone', true);
    $shipping_city = $order->get_shipping_city();
    $shipping_state = $order->get_shipping_state();
    $shipping_postcode = $order->get_shipping_postcode();
    
    ?>
    <section class="woocommerce-customer-details" style="margin-top: 20px;">
        <section class="woocommerce-column woocommerce-column--shipping-address col-1">
            <h2 class="woocommerce-column__title">Adresa de livrare</h2>
            <address>
                <?php if ($shipping_label): ?>
                    <strong><?php echo esc_html($shipping_label); ?></strong><br>
                <?php endif; ?>
                <?php if ($shipping_name): ?>
                    <?php echo esc_html($shipping_name); ?><br>
                <?php endif; ?>
                <?php if ($shipping_phone): ?>
                    Tel: <?php echo esc_html($shipping_phone); ?><br>
                <?php endif; ?>
                <?php echo esc_html($shipping_address); ?><br>
                <?php echo esc_html($shipping_city); ?>, <?php echo esc_html($shipping_state); ?> <?php echo esc_html($shipping_postcode); ?>
            </address>
        </section>
    </section>
    <?php
}, 10, 1);

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
        
        // Adaugă SVG logout icon la linkul existent generat de Martfury
        var $accountMenu = $('.topbar-menu .extra-menu-item.account-item');
        var iconLogout = '<svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>';
        
        // Adaugă icon la Logout (tema generează automat acest link)
        $accountMenu.find('li.logout a').prepend(iconLogout);
    });
    </script>
    
    <style>
    /* Stilizare compactă pentru formularele de retur și garanție */
    .retur-form,
    .garantie-form {
        display: flex;
        flex-direction: column;
        gap: 0;
    }
    
    .retur-form .form-row,
    .garantie-form .form-row {
        width: 100%;
        max-width: 100%;
        margin-bottom: 12px !important;
        display: flex;
        flex-direction: column;
    }
    
    .retur-form .form-row label,
    .garantie-form .form-row label {
        display: block !important;
        margin-bottom: 6px !important;
        font-weight: 500 !important;
        color: #666 !important;
        font-size: 13px !important;
        width: 100%;
    }
    
    .retur-form select,
    .garantie-form select,
    .retur-form textarea,
    .garantie-form textarea,
    .retur-form input,
    .garantie-form input {
        padding: 6px 8px !important;
        font-size: 13px !important;
        line-height: 1.3 !important;
        min-height: auto !important;
        height: auto !important;
        width: 100% !important;
    }
    
    .retur-form select,
    .garantie-form select {
        height: 32px !important;
    }
    
    .retur-form button[type="submit"],
    .garantie-form button[type="submit"],
    .retur-form .button[type="submit"],
    .garantie-form .button[type="submit"] {
        width: auto !important;
        max-width: 200px !important;
        display: inline-block !important;
        padding: 6px 16px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        border-radius: 20px !important;
        background: #2196f3 !important;
        color: #fff !important;
        border: none !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        margin-top: 4px !important;
    }
    
    .retur-form button[type="submit"]:hover,
    .garantie-form button[type="submit"]:hover,
    .retur-form .button[type="submit"]:hover,
    .garantie-form .button[type="submit"]:hover {
        background: #1976d2 !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 2px 8px rgba(33, 150, 243, 0.3) !important;
    }

    /* =============================================
       PAGINA ADRESE SALVATE - DESIGN MODERN CARDURI
       ============================================= */
    
    .webgsm-saved-data-page {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }
    
    /* Secțiune container */
    .webgsm-data-section {
        background: #fff;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        position: relative;
    }
    
    /* Header secțiune cu titlu + buton + */
    .webgsm-data-section .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 18px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid #e5e7eb;
    }
    
    .webgsm-data-section .section-header h3 {
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #1e293b !important;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    /* SVG Icon para titlu */
    .section-header h3::before {
        content: '';
        display: inline-block;
        width: 16px;
        height: 16px;
        background-size: contain;
        background-repeat: no-repeat;
    }
    
    /* Buton + rotund - line art style */
    .btn-add-new {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: transparent;
        border: 1.5px solid #94a3b8;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        padding: 0;
        position: relative;
    }
    
    .btn-add-new:hover {
        background: #e0f2fe;
        border-color: #3b82f6;
        transform: scale(1.05);
    }
    
    .btn-add-new::before,
    .btn-add-new::after {
        content: '';
        position: absolute;
        background: #94a3b8;
        transition: background 0.2s ease;
    }
    
    .btn-add-new::before {
        width: 12px;
        height: 2px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    .btn-add-new::after {
        width: 2px;
        height: 12px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    .btn-add-new:hover::before,
    .btn-add-new:hover::after {
        background: #3b82f6;
    }
    
    .btn-add-new .plus-icon {
        display: none !important;
    }
    
    /* Conținut secțiune */
    .webgsm-data-section .section-content {
        padding: 18px;
    }
    
    /* Grid carduri */
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 16px;
    }
    
    /* Card individual */
    .data-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.2s ease;
    }
    
    .data-card:hover {
        border-color: #2563eb;
        box-shadow: 0 3px 10px rgba(37, 99, 235, 0.12);
    }
    
    /* Card header */
    .data-card .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 14px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .data-card .card-label {
        font-weight: 600;
        font-size: 13px;
        color: #1e293b;
    }
    
    .data-card .card-badge {
        font-size: 10px;
        background: #e0e7ff;
        color: #3730a3;
        padding: 2px 6px;
        border-radius: 10px;
        font-weight: 500;
    }
    
    /* Card body */
    .data-card .card-body {
        padding: 12px 14px;
    }
    
    .data-card .card-name {
        font-weight: 600;
        font-size: 13px;
        color: #1e293b;
        margin: 0 0 6px 0;
    }
    
    .data-card .card-detail {
        font-size: 12px;
        color: #64748b;
        margin: 0 0 3px 0;
        line-height: 1.4;
    }
    
    .data-card .card-phone,
    .data-card .card-email {
        font-size: 11px;
        color: #475569;
        margin: 4px 0 0 0;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    /* Card actions */
    .data-card .card-actions {
        display: flex;
        border-top: 1px solid #e5e7eb;
    }
    
    .data-card .card-actions button {
        flex: 1;
        padding: 8px;
        border: none;
        background: transparent;
        cursor: pointer;
        font-size: 11px;
        font-weight: 500;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 3px;
    }
    
    .data-card .btn-edit-item {
        color: #2563eb;
        border-right: 1px solid #e5e7eb;
    }
    
    .data-card .btn-edit-item:hover {
        background: #eff6ff;
        color: #1d4ed8;
    }
    
    .data-card .btn-delete-item {
        color: #dc2626;
    }
    
    .data-card .btn-delete-item:hover {
        background: #fef2f2;
        color: #b91c1c;
    }
    
    /* SVG icons în butoane */
    .data-card .btn-edit-item::before,
    .data-card .btn-delete-item::before {
        content: '';
        display: inline-block;
        width: 12px;
        height: 12px;
        background-size: contain;
        background-repeat: no-repeat;
    }
    
    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 36px 18px;
        color: #64748b;
    }
    
    .empty-state p {
        margin: 0 0 14px 0;
        font-size: 13px;
    }
    
    .btn-add-first {
        background: #f1f5f9;
        border: 2px dashed #cbd5e1;
        border-radius: 5px;
        padding: 6px 16px;
        font-size: 11px;
        font-weight: 500;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .btn-add-first:hover {
        background: #e0e7ff;
        border-color: #2563eb;
        color: #2563eb;
    }
    
    .btn-add-first svg {
        width: 14px;
        height: 14px;
    }
    
    /* =============================================
       RESPONSIVE - MOBILE
       ============================================= */
    @media (max-width: 768px) {
        .webgsm-saved-data-page {
            gap: 18px;
        }
        
        .webgsm-data-section .section-header {
            padding: 12px 14px;
        }
        
        .webgsm-data-section .section-header h3 {
            font-size: 12px !important;
        }
        
        .btn-add-new {
            width: 32px;
            height: 18px;
        }
        
        .btn-add-new .plus-icon {
            width: 14px;
            height: 14px;
        }
        
        .webgsm-data-section .section-content {
            padding: 14px;
        }
        
        .cards-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        
        .data-card .card-header {
            padding: 9px 12px;
        }
        
        .data-card .card-body {
            padding: 10px 12px;
        }
        
        .data-card .card-actions button {
            padding: 7px;
            font-size: 10px;
        }
    }
    
    @media (max-width: 480px) {
        .webgsm-data-section .section-header h3 {
            font-size: 11px !important;
        }
        
        .data-card .card-label {
            font-size: 12px;
        }
        
        .data-card .card-badge {
            font-size: 9px;
            padding: 2px 5px;
        }
        
        .data-card .card-name {
            font-size: 12px;
        }
        
        .data-card .card-detail {
            font-size: 11px;
        }
        
        .empty-state {
            padding: 28px 14px;
        }
    }

    /* =============================================
       MODAL POPUP - CENTRARE ȘI STILIZARE
       ============================================= */
    .webgsm-popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
        box-sizing: border-box;
    }
    
    .webgsm-popup .popup-content {
        background: #fff;
        border-radius: 12px;
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        box-shadow: 0 25px 50px rgba(0,0,0,0.25);
        animation: popupSlideIn 0.2s ease-out;
    }
    
    @keyframes popupSlideIn {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(-10px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    
    /* Label obligatoriu - asterisk rosu */
    .webgsm-popup label {
        display: block;
        font-size: 13px;
        font-weight: 500;
        color: #1e293b;
        margin-bottom: 6px;
    }
    
    .webgsm-popup .popup-body label {
        font-size: 13px;
        color: #475569;
        margin-bottom: 6px;
        font-weight: 500;
    }
    
    /* Input fields styling */
    .webgsm-popup input[type="text"],
    .webgsm-popup input[type="tel"],
    .webgsm-popup input[type="email"],
    .webgsm-popup select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 13px;
        transition: border-color 0.2s;
    }
    
    .webgsm-popup input[type="text"]:focus,
    .webgsm-popup input[type="tel"]:focus,
    .webgsm-popup input[type="email"]:focus,
    .webgsm-popup select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Buton X - mic și compact */
    .webgsm-popup .popup-close {
        width: 28px;
        height: 28px;
        min-width: 28px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #64748b;
        line-height: 1;
        transition: all 0.15s ease;
        padding: 0;
        margin-left: auto;
        flex-shrink: 0;
    }
    
    .webgsm-popup .popup-close:hover {
        background: #fee2e2;
        border-color: #fca5a5;
        color: #dc2626;
    }
    
    .webgsm-popup .popup-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    
    .webgsm-popup .popup-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        flex: 1;
    }
    
    .webgsm-popup .popup-body {
        padding: 24px;
    }
    
    /* Overlay pentru închidere */
    .webgsm-popup .popup-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: transparent;
    }
    
    /* Popup Footer - Butoane */
    .webgsm-popup .popup-footer {
        padding: 8px 16px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 6px;
        justify-content: flex-end;
        background: #f8fafc;
        align-items: center;
    }
    
    /* Reset și override pentru btn-primary în modal */
    .webgsm-popup .btn-primary {
        padding: 2px 14px !important;
        border-radius: 16px !important;
        font-size: 11px !important;
        font-weight: 500 !important;
        border: none !important;
        min-height: 20px !important;
        line-height: 1 !important;
        background: #3b82f6 !important;
        color: #fff !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
    }
    
    .webgsm-popup .btn-primary:hover {
        background: #2563eb !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    
    /* Reset și override pentru btn-secondary în modal */
    .webgsm-popup .btn-secondary {
        padding: 2px 14px !important;
        border-radius: 16px !important;
        font-size: 11px !important;
        font-weight: 500 !important;
        border: 1px solid #e2e8f0 !important;
        min-height: 20px !important;
        line-height: 1 !important;
        background: #fff !important;
        color: #64748b !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
    }
    
    .webgsm-popup .btn-secondary:hover {
        background: #fef2f2 !important;
        border-color: #fca5a5 !important;
        color: #dc2626 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);
    }
    
    /* Butoane Modal - Selectori specifici pentru My Account */
    .webgsm-popup .btn-secondary.modal-cancel-btn,
    .webgsm-popup #save_address_modal_btn,
    .webgsm-popup #save_company_modal_btn,
    .webgsm-popup #save_person_modal_btn {
        padding: 2px 14px !important;
        min-height: 20px !important;
        font-size: 11px !important;
        border-radius: 16px !important;
        font-weight: 500 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
    }
    
    /* Specific pentru butonul Salveaza - ID selector */
    #save_address_modal_btn,
    #save_company_modal_btn,
    #save_person_modal_btn {
        padding: 2px 14px !important;
        min-height: 20px !important;
        font-size: 11px !important;
        border-radius: 16px !important;
        font-weight: 500 !important;
        background: #3b82f6 !important;
        color: #fff !important;
        border: none !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
    }
    
    #save_address_modal_btn:hover,
    #save_company_modal_btn:hover,
    #save_person_modal_btn:hover {
        background: #2563eb !important;
    }

    </style>
    
    <?php
});
