<?php
/**
 * WebGSM Checkout Display
 * Afișare date PF/PJ în admin și email
 * 
 * NOTĂ: frontend_display a fost DEZACTIVAT - afișarea pe pagina thank you 
 * este gestionată de custom_thankyou_content din fișierul principal
 */

if (!defined('ABSPATH')) exit;

class WebGSM_Checkout_Display {
    
    public function __construct() {
        // Admin - afișare în detalii comandă
        add_action('woocommerce_admin_order_data_after_billing_address', [$this, 'admin_display'], 10, 1);
        
        // Email - afișare în email-uri
        add_action('woocommerce_email_after_order_table', [$this, 'email_display'], 10, 4);
        
        // DEZACTIVAT - frontend_display - gestionat de custom_thankyou_content
        // add_action('woocommerce_order_details_after_order_table', [$this, 'frontend_display'], 10, 1);
        
        // Coloană în lista comenzi admin
        add_filter('manage_edit-shop_order_columns', [$this, 'add_order_column']);
        add_filter('manage_woocommerce_page_wc-orders_columns', [$this, 'add_order_column']);
        add_action('manage_shop_order_posts_custom_column', [$this, 'display_order_column'], 10, 2);
        add_action('manage_woocommerce_page_wc-orders_custom_column', [$this, 'display_order_column_hpos'], 10, 2);
        
        // Ascunde adresa de facturare default pe thank you page pentru PJ
        add_filter('woocommerce_order_get_formatted_billing_address', [$this, 'filter_billing_address'], 10, 3);
    }
    
    /**
     * Afișare în admin - detalii comandă
     */
    public function admin_display($order) {
        $customer_type = $order->get_meta('_customer_type');
        if (empty($customer_type)) return;
        
        if ($customer_type === 'pj') {
            $this->display_pj_admin($order);
        } else {
            $this->display_pf_admin($order);
        }
    }
    
    private function display_pj_admin($order) {
        $cui = $order->get_meta('_billing_cui');
        $j = $order->get_meta('_billing_j');
        $iban = $order->get_meta('_billing_iban');
        $bank = $order->get_meta('_billing_bank');
        
        echo '<div style="background:#e3f2fd;padding:12px;margin-top:12px;border-radius:4px;border-left:4px solid #2196f3;">';
        echo '<p style="margin:0 0 10px 0;"><strong>🏢 Persoană Juridică</strong></p>';
        echo '<table style="width:100%;border-collapse:collapse;">';
        
        if (!empty($cui)) {
            echo '<tr><td style="padding:4px 8px 4px 0;width:100px;color:#666;">CUI:</td><td style="padding:4px 0;"><strong>' . esc_html($cui) . '</strong></td></tr>';
        }
        if (!empty($j)) {
            echo '<tr><td style="padding:4px 8px 4px 0;color:#666;">Reg.Com:</td><td style="padding:4px 0;">' . esc_html($j) . '</td></tr>';
        }
        if (!empty($iban)) {
            echo '<tr><td style="padding:4px 8px 4px 0;color:#666;">IBAN:</td><td style="padding:4px 0;font-family:monospace;">' . esc_html($iban) . '</td></tr>';
        }
        if (!empty($bank)) {
            echo '<tr><td style="padding:4px 8px 4px 0;color:#666;">Banca:</td><td style="padding:4px 0;">' . esc_html($bank) . '</td></tr>';
        }
        
        echo '</table></div>';
    }
    
    private function display_pf_admin($order) {
        $cnp = $order->get_meta('_billing_cnp');
        if (empty($cnp)) return;
        
        echo '<div style="background:#e8f5e9;padding:12px;margin-top:12px;border-radius:4px;border-left:4px solid #4caf50;">';
        echo '<p style="margin:0 0 10px 0;"><strong>👤 Persoană Fizică</strong></p>';
        echo '<table style="width:100%;border-collapse:collapse;">';
        echo '<tr><td style="padding:4px 8px 4px 0;width:100px;color:#666;">CNP:</td><td style="padding:4px 0;font-family:monospace;">' . esc_html($cnp) . '</td></tr>';
        echo '</table></div>';
    }
    
    /**
     * Afișare în email-uri
     */
    public function email_display($order, $sent_to_admin, $plain_text, $email) {
        $customer_type = $order->get_meta('_customer_type');
        if ($customer_type !== 'pj') return;
        
        $cui = $order->get_meta('_billing_cui');
        $j = $order->get_meta('_billing_j');
        
        if (empty($cui) && empty($j)) return;
        
        if ($plain_text) {
            echo "\nDate firmă:\n";
            if ($cui) echo "CUI: $cui\n";
            if ($j) echo "Reg.Com: $j\n";
        } else {
            echo '<h2 style="margin-top:20px;font-size:18px;color:#333;">Date firmă</h2>';
            echo '<table cellspacing="0" cellpadding="8" style="width:100%;border:1px solid #e5e5e5;margin-bottom:20px;" border="1">';
            if ($cui) {
                echo '<tr><th style="text-align:left;background:#f8f8f8;width:120px;">CUI</th><td>' . esc_html($cui) . '</td></tr>';
            }
            if ($j) {
                echo '<tr><th style="text-align:left;background:#f8f8f8;">Nr. Reg. Com.</th><td>' . esc_html($j) . '</td></tr>';
            }
            echo '</table>';
        }
    }
    
    /**
     * Coloană Tip Client în lista comenzi
     */
    public function add_order_column($columns) {
        $new_columns = [];
        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            if ($key === 'order_total') {
                $new_columns['customer_type'] = 'Tip Client';
            }
        }
        return $new_columns;
    }
    
    public function display_order_column($column, $post_id) {
        if ($column !== 'customer_type') return;
        $order = wc_get_order($post_id);
        $this->render_badge($order);
    }
    
    public function display_order_column_hpos($column, $order) {
        if ($column !== 'customer_type') return;
        $this->render_badge($order);
    }
    
    private function render_badge($order) {
        if (!$order) return;
        
        $customer_type = $order->get_meta('_customer_type');
        
        if ($customer_type === 'pj') {
            $cui = $order->get_meta('_billing_cui');
            echo '<span style="background:#e3f2fd;color:#1565c0;padding:3px 8px;border-radius:3px;font-size:11px;">🏢 PJ</span>';
            if ($cui) {
                echo '<br><small style="color:#666;">' . esc_html($cui) . '</small>';
            }
        } else {
            echo '<span style="background:#e8f5e9;color:#2e7d32;padding:3px 8px;border-radius:3px;font-size:11px;">👤 PF</span>';
        }
    }
    
    /**
     * Filtrează adresa de facturare pentru a nu afișa duplicat
     * (opțional - poate fi activat dacă adresa default tot apare)
     */
    public function filter_billing_address($address, $raw_address, $order) {
        // Dacă suntem pe pagina thank you și e PJ, nu modificăm
        // Afișarea e gestionată de custom_thankyou_content
        return $address;
    }
}

new WebGSM_Checkout_Display();
