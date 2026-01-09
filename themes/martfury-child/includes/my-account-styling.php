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
    .woocommerce-MyAccount-content h2 {
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
    .woocommerce-MyAccount-content table,
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
       TABELE GARANȚII/RETURURI - SCROLL ORIZONTAL MOBILE
       ============================================= */
    
    /* Wrapper pentru scroll orizontal */
    .woocommerce-MyAccount-content .woocommerce-orders-table {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    @media (max-width: 768px) {
        /* Container tabel cu scroll */
        .woocommerce-MyAccount-content .woocommerce-orders-table {
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 0 -10px;
            padding: 0 10px;
            max-width: calc(100% + 20px);
        }
        
        /* Tabelul intern - lățime minimă pentru a forța scroll */
        .woocommerce-MyAccount-content .woocommerce-orders-table tbody,
        .woocommerce-MyAccount-content .woocommerce-orders-table thead {
            display: table;
            width: 100%;
            min-width: 500px;
        }
        
        .woocommerce-MyAccount-content .woocommerce-orders-table tr {
            display: table-row;
        }
        
        .woocommerce-MyAccount-content .woocommerce-orders-table th,
        .woocommerce-MyAccount-content .woocommerce-orders-table td {
            display: table-cell;
            white-space: nowrap;
            padding: 8px 10px !important;
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
        /* Required for absolute positioned modals */
        min-height: 300px;
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
    
    /* Buton + rotund */
    .btn-add-new {
        width: 36px;
        height: 20px;
        border-radius: 5px;
        background: #2563eb;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
        padding: 0;
    }
    
    .btn-add-new:hover {
        background: #1d4ed8;
        transform: scale(1.06);
        box-shadow: 0 3px 10px rgba(37, 99, 235, 0.35);
    }
    
    .btn-add-new .plus-icon {
        color: #fff;
        width: 16px;
        height: 16px;
        line-height: 1;
        flex-shrink: 0;
        opacity: 1 !important;
        visibility: visible !important;
        display: inline-block !important;
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
    </style>
    
    <?php
});
