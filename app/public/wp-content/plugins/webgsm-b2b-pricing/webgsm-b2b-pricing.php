<?php
/**
 * Plugin Name: WebGSM B2B Pricing
 * Description: Sistem de prețuri diferențiate pentru clienți B2B (Persoane Juridice) cu discount pe produs/categorie, tiers și protecție preț minim.
 * Version: 1.0.0
 * Author: WebGSM
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * WC requires at least: 6.0
 * Text Domain: webgsm-b2b
 */

if (!defined('ABSPATH')) exit;

// Verifică dacă WooCommerce este activ
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    add_action('admin_notices', function() {
        echo '<div class="error"><p><strong>WebGSM B2B Pricing</strong> necesită WooCommerce activ.</p></div>';
    });
    return;
}

// Constante
define('WEBGSM_B2B_VERSION', '1.0.0');
define('WEBGSM_B2B_PATH', plugin_dir_path(__FILE__));
define('WEBGSM_B2B_URL', plugin_dir_url(__FILE__));

/**
 * Clasa principală WebGSM B2B Pricing
 */
class WebGSM_B2B_Pricing {
    // =========================================
    // DEBUGGING COMPLET
    // =========================================
    
    /**
     * Debugging în consolă JS - arată tot ce se întâmplă
     */
    public function add_console_debugging() {
        if (!current_user_can('manage_options')) return;
        
        $user_id = get_current_user_id();
        $is_pj = $this->is_user_pj() ? 'true' : 'false';
        $tier = $this->get_user_tier() ?: 'none';
        $discount_implicit = get_option('webgsm_b2b_discount_implicit', 0);
        $tiers = get_option('webgsm_b2b_tiers', $this->get_default_tiers());
        
        // Obține meta-uri user
        $user_meta = array(
            '_is_pj' => get_user_meta($user_id, '_is_pj', true),
            '_tip_client' => get_user_meta($user_id, '_tip_client', true),
            'billing_cui' => get_user_meta($user_id, 'billing_cui', true),
        );
        
        // Debug per produs
        $products_debug = isset($GLOBALS['webgsm_b2b_debug']) ? $GLOBALS['webgsm_b2b_debug'] : array();
        
        ?>
        <script>
        console.group('🔧 WebGSM B2B Pricing - DEBUG');
        console.log('📌 User ID:', <?php echo $user_id; ?>);
        console.log('🏢 Is PJ:', <?php echo $is_pj; ?>);
        console.log('⭐ Tier:', '<?php echo $tier; ?>');
        console.log('💰 Discount Implicit (din setări):', '<?php echo $discount_implicit; ?>%');
        console.log('📊 Tiers Config:', <?php echo json_encode($tiers); ?>);
        console.log('👤 User Meta:', <?php echo json_encode($user_meta); ?>);
        
        <?php if (!empty($products_debug)) : ?>
        console.group('📦 Produse Afișate');
        <?php foreach ($products_debug as $debug) : ?>
        console.log('Product #<?php echo $debug['product_id']; ?>:', {
            'Preț Original': <?php echo $debug['price_original']; ?>,
            'Discount PJ': '<?php echo $debug['discount_pj']; ?>%',
            '⚠️ SURSA DISCOUNT': '<?php echo isset($debug['discount_source']) ? $debug['discount_source'] : 'unknown'; ?>',
            'Discount Tier': '<?php echo $debug['discount_tier']; ?>%',
            'Discount TOTAL': '<?php echo $debug['discount_total']; ?>%',
            'Preț Final B2B': <?php echo $debug['price_final']; ?>,
            'Tier': '<?php echo $debug['tier']; ?>'
        });
        <?php endforeach; ?>
        console.groupEnd();
        <?php endif; ?>
        
        console.groupEnd();
        </script>
        <?php
    }
    
    /**
     * DEBUG: Buton pentru a seta userul curent ca PJ (doar admin)
     */
    public function debug_set_pj_button() {
        if (!current_user_can('manage_options')) return;
        if (isset($_GET['set_pj']) && $_GET['set_pj'] === '1') {
            update_user_meta(get_current_user_id(), '_is_pj', 'yes');
            update_user_meta(get_current_user_id(), 'billing_cui', 'RO12345678');
            update_user_meta(get_current_user_id(), '_tip_client', 'pj');
            echo '<div style="position:fixed;bottom:60px;right:20px;z-index:9999;background:#22c55e;color:#fff;padding:10px 18px;border-radius:8px;font-size:15px;box-shadow:0 2px 8px rgba(0,0,0,0.12);">Userul tău a fost setat ca PJ! <a href="'.esc_url(remove_query_arg('set_pj')).'" style="color:#fff;text-decoration:underline;">Reîncarcă</a></div>';
        } else {
            echo '<a href="'.esc_url(add_query_arg('set_pj','1')).'" style="position:fixed;bottom:60px;right:20px;z-index:9999;background:#2563eb;color:#fff;padding:10px 18px;border-radius:8px;font-size:15px;box-shadow:0 2px 8px rgba(0,0,0,0.12);text-decoration:none;">Setează userul ca PJ (test)</a>';
        }
    }
    
    /**
     * DEBUG: Afișează statusul PJ pe frontend pentru test
     */
    public function debug_show_pj_status() {
        if (!current_user_can('manage_options')) return;
        $is_pj = $this->is_user_pj() ? 'DA (PJ)' : 'NU (PF)';
        $tier = $this->get_user_tier() ?: 'bronze';
        $discount = get_option('webgsm_b2b_discount_implicit', 0);
        echo '<div style="position:fixed;bottom:20px;right:20px;z-index:9999;background:#2563eb;color:#fff;padding:10px 18px;border-radius:8px;font-size:15px;box-shadow:0 2px 8px rgba(0,0,0,0.12);">'
            .'<strong>PJ:</strong> '.$is_pj.' | <strong>Tier:</strong> '.$tier.' | <strong>Discount:</strong> '.$discount.'%</div>';
    }
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function __construct() {
        $this->init_hooks();
        add_action('wp_footer', array($this, 'debug_show_pj_status'));
        add_action('wp_footer', array($this, 'debug_set_pj_button'));
    }
    
    private function init_hooks() {
        // Admin hooks
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'admin_assets'));
        
        // Product meta fields
        add_action('woocommerce_product_options_pricing', array($this, 'add_product_pricing_fields'));
        add_action('woocommerce_process_product_meta', array($this, 'save_product_pricing_fields'));
        
        // Category meta fields
        add_action('product_cat_add_form_fields', array($this, 'add_category_fields'));
        add_action('product_cat_edit_form_fields', array($this, 'edit_category_fields'));
        add_action('created_product_cat', array($this, 'save_category_fields'));
        add_action('edited_product_cat', array($this, 'save_category_fields'));
        
        // Price filters - NUCLEUL SISTEMULUI
        add_filter('woocommerce_product_get_price', array($this, 'apply_b2b_price'), 99, 2);
        add_filter('woocommerce_product_get_regular_price', array($this, 'apply_b2b_price'), 99, 2);
        add_filter('woocommerce_product_variation_get_price', array($this, 'apply_b2b_price'), 99, 2);
        add_filter('woocommerce_product_variation_get_regular_price', array($this, 'apply_b2b_price'), 99, 2);
        
        // Price HTML display
        add_filter('woocommerce_get_price_html', array($this, 'modify_price_html'), 99, 2);
        
        // Cart price - DEZACTIVAT, filtrul de preț e suficient
        // add_action('woocommerce_before_calculate_totals', array($this, 'apply_b2b_cart_price'), 99);
        
        // Display discount info în cart
        add_filter('woocommerce_cart_item_price', array($this, 'display_cart_item_tier_price'), 10, 3);
        
        // Display B2B discount în cart și checkout - DIRECT în HTML
        add_action('woocommerce_cart_totals_after_order_total', array($this, 'display_b2b_savings_row'), 10);
        add_action('woocommerce_review_order_after_order_total', array($this, 'display_b2b_savings_row'), 10);
        
        // Update user tier after order completed
        add_action('woocommerce_order_status_completed', array($this, 'update_user_tier_on_order'));
        
        // Admin columns for orders
        add_filter('manage_edit-shop_order_columns', array($this, 'add_order_profit_column'));
        add_action('manage_shop_order_posts_custom_column', array($this, 'render_order_profit_column'), 10, 2);
        
        // ÎNREGISTRARE CONT - Doar detectare PJ (formularul e în temă registration-enhanced.php)
        add_action('woocommerce_created_customer', array($this, 'detect_pj_on_registration'), 20);
        
        // Debugging în footer
        add_action('wp_footer', array($this, 'add_console_debugging'));
    }
    
    // =========================================
    // CACHE MANAGEMENT
    // =========================================
    
    /**
     * Șterge tot cache-ul de prețuri B2B
     */
    public function clear_all_price_cache() {
        global $wpdb;
        
        // Șterge din WP Object Cache
        wp_cache_flush();
        
        // Șterge transients legate de WooCommerce
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%_transient_wc_%'");
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%_transient_timeout_wc_%'");
        
        // Șterge cache-ul de sesiune WooCommerce (forțează recalculare coș)
        if (function_exists('WC') && WC()->session) {
            WC()->session->set('cart_totals', null);
        }
        
        // Clear cart cache pentru a forța recalcularea
        if (function_exists('WC') && WC()->cart) {
            WC()->cart->calculate_totals();
        }
    }
    
    // =========================================
    // VERIFICARE USER PJ
    // =========================================
    
    /**
     * Verifică dacă user-ul curent este Persoană Juridică
     */
    public function is_user_pj($user_id = null) {
        if (is_null($user_id)) {
            $user_id = get_current_user_id();
        }
        
        if (!$user_id) {
            return false;
        }
        
        // Verifică meta _is_pj
        $is_pj = get_user_meta($user_id, '_is_pj', true);
        if ($is_pj === 'yes' || $is_pj === '1' || $is_pj === true) {
            return true;
        }
        
        // Verifică rol WooCommerce (dacă folosești B2BKing sau similar)
        $user = get_userdata($user_id);
        if ($user && (in_array('b2b_customer', (array) $user->roles) || in_array('wholesale_customer', (array) $user->roles))) {
            return true;
        }
        
        // Fallback: verifică dacă are CUI salvat
        $cui = get_user_meta($user_id, 'billing_cui', true);
        if (!empty($cui)) {
            return true;
        }
        
        // Verifică și în alte meta keys posibile
        $tip_client = get_user_meta($user_id, '_tip_client', true);
        if (strtolower($tip_client) === 'pj' || strtolower($tip_client) === 'juridica') {
            return true;
        }
        
        return false;
    }
    
    /**
     * Obține tier-ul utilizatorului PJ
     */
    public function get_user_tier($user_id = null) {
        if (is_null($user_id)) {
            $user_id = get_current_user_id();
        }
        
        if (!$user_id || !$this->is_user_pj($user_id)) {
            return false;
        }
        
        // Verifică tier salvat
        $tier = get_user_meta($user_id, '_pj_tier', true);
        
        // Dacă nu există, calculează
        if (empty($tier)) {
            $tier = $this->calculate_user_tier($user_id);
            update_user_meta($user_id, '_pj_tier', $tier);
        }
        
        return $tier;
    }
    
    /**
     * Calculează tier-ul bazat pe istoricul comenzilor
     */
    public function calculate_user_tier($user_id) {
        $total_orders = $this->get_user_total_orders($user_id);
        $tiers = get_option('webgsm_b2b_tiers', $this->get_default_tiers());
        
        $current_tier = 'bronze';
        
        foreach ($tiers as $tier_name => $tier_data) {
            if ($total_orders >= $tier_data['min_orders']) {
                $current_tier = $tier_name;
            }
        }
        
        return $current_tier;
    }
    
    /**
     * Obține numărul total de comenzi al utilizatorului
     */
    public function get_user_total_orders($user_id) {
        $cached = get_user_meta($user_id, '_pj_total_orders', true);
        
        // Recalculează dacă cache-ul e mai vechi de 1 oră
        $last_calc = get_user_meta($user_id, '_pj_orders_calculated', true);
        if ($cached && $last_calc && (time() - $last_calc) < 3600) {
            return (int) $cached;
        }
        
        $orders = wc_get_orders(array(
            'customer_id' => $user_id,
            'status' => array('completed', 'processing'),
            'limit' => -1,
            'return' => 'ids'
        ));
        
        $count = count($orders);
        
        update_user_meta($user_id, '_pj_total_orders', $count);
        update_user_meta($user_id, '_pj_orders_calculated', time());
        
        return $count;
    }
    
    /**
     * Obține valoarea totală a comenzilor utilizatorului
     */
    public function get_user_total_value($user_id) {
        $cached = get_user_meta($user_id, '_pj_total_value', true);
        $last_calc = get_user_meta($user_id, '_pj_value_calculated', true);
        
        if ($cached && $last_calc && (time() - $last_calc) < 3600) {
            return (float) $cached;
        }
        
        $orders = wc_get_orders(array(
            'customer_id' => $user_id,
            'status' => array('completed', 'processing'),
            'limit' => -1
        ));
        
        $total = 0;
        foreach ($orders as $order) {
            $total += $order->get_total();
        }
        
        update_user_meta($user_id, '_pj_total_value', $total);
        update_user_meta($user_id, '_pj_value_calculated', time());
        
        return $total;
    }
    
    /**
     * Tiers implicite
     */
    public function get_default_tiers() {
        return array(
            'bronze' => array(
                'min_orders' => 0,
                'discount_extra' => 0,
                'label' => 'Bronze'
            ),
            'silver' => array(
                'min_orders' => 11,
                'discount_extra' => 3,
                'label' => 'Silver'
            ),
            'gold' => array(
                'min_orders' => 51,
                'discount_extra' => 5,
                'label' => 'Gold'
            ),
            'platinum' => array(
                'min_orders' => 101,
                'discount_extra' => 8,
                'label' => 'Platinum'
            )
        );
    }
    
    // =========================================
    // CALCUL PREȚ B2B - NUCLEUL SISTEMULUI
    // =========================================
    
    /**
     * Aplică prețul B2B
     * ACESTA ESTE MOTORUL PRINCIPAL - FĂRĂ CACHE pentru debugging
     */
    public function apply_b2b_price($price, $product) {
        // Nu aplica în admin (pentru a vedea prețul real)
        if (is_admin() && !wp_doing_ajax()) {
            return $price;
        }
        
        // Verifică dacă user-ul este PJ
        if (!$this->is_user_pj()) {
            return $price;
        }
        
        // Calculează prețul B2B direct, fără cache
        $b2b_price = $this->calculate_b2b_price($price, $product);
        
        return $b2b_price;
    }
    
    /**
     * Calculează prețul B2B cu toate regulile + DEBUGGING
     */
    public function calculate_b2b_price($price, $product) {
        $product_id = $product->get_id();
        
        // 1. Obține prețul minim (HARD LIMIT)
        $pret_minim = $this->get_pret_minim($product);
        
        // 2. Verifică dacă produsul e la promoție
        $sale_price = $product->get_sale_price();
        $is_on_sale = !empty($sale_price) && $sale_price < $price;
        
        // 3. Obține discount-ul PJ pentru produs sau categorie
        $discount_pj = $this->get_discount_pj($product);
        
        // 4. Obține discount-ul extra din tier
        $tier = $this->get_user_tier();
        $tiers = get_option('webgsm_b2b_tiers', $this->get_default_tiers());
        $discount_tier = isset($tiers[$tier]['discount_extra']) ? (float) $tiers[$tier]['discount_extra'] : 0;
        
        // 5. Calculează prețul cu discount PJ + Tier
        $discount_total = $discount_pj + $discount_tier;
        $pret_pj = $price - ($price * $discount_total / 100);
        
        // 6. REGULA CONFLICT: cel mai mic preț câștigă
        if ($is_on_sale) {
            $pret_final = min($pret_pj, (float) $sale_price);
        } else {
            $pret_final = $pret_pj;
        }
        
        // 7. VERIFICARE HARD LIMIT
        if ($pret_minim > 0 && $pret_final < $pret_minim) {
            $pret_final = $pret_minim;
        }
        
        // DEBUG: Stochează pentru afișare în consolă
        if (!isset($GLOBALS['webgsm_b2b_debug'])) {
            $GLOBALS['webgsm_b2b_debug'] = array();
        }
        
        // Obține sursa discount-ului
        $discount_info = $this->get_discount_pj($product, true);
        
        $GLOBALS['webgsm_b2b_debug'][$product_id] = array(
            'product_id' => $product_id,
            'price_original' => $price,
            'discount_pj' => $discount_pj,
            'discount_source' => $discount_info['source'],
            'discount_tier' => $discount_tier,
            'discount_total' => $discount_total,
            'price_final' => round($pret_final, 2),
            'tier' => $tier
        );
        
        return round($pret_final, 2);
    }
    
    /**
     * Obține prețul minim de vânzare
     */
    public function get_pret_minim($product) {
        $product_id = $product->get_id();
        
        // Verifică meta pe produs
        $pret_minim = get_post_meta($product_id, '_pret_minim_vanzare', true);
        
        if (!empty($pret_minim) && $pret_minim > 0) {
            return (float) $pret_minim;
        }
        
        // Fallback: calculează din preț achiziție + marjă minimă
        $pret_achizitie = get_post_meta($product_id, '_pret_achizitie', true);
        $marja_minima = get_option('webgsm_b2b_marja_minima', 5); // 5% implicit
        
        if (!empty($pret_achizitie) && $pret_achizitie > 0) {
            return (float) $pret_achizitie * (1 + $marja_minima / 100);
        }
        
        return 0;
    }
    
    /**
     * Obține discount-ul PJ pentru produs + sursa
     */
    public function get_discount_pj($product, $return_source = false) {
        $product_id = $product->get_id();
        
        // 1. Verifică discount specific pe produs
        $discount_produs = get_post_meta($product_id, '_discount_pj', true);
        
        if ($discount_produs !== '' && $discount_produs !== false && $discount_produs !== null) {
            if ($return_source) return array('discount' => (float) $discount_produs, 'source' => 'produs');
            return (float) $discount_produs;
        }
        
        // 2. Fallback: discount din categorie
        $categories = $product->get_category_ids();
        $discount_categorie = 0;
        $cat_name = '';
        
        foreach ($categories as $cat_id) {
            $cat_discount = get_term_meta($cat_id, '_discount_pj_categorie', true);
            if (!empty($cat_discount) && $cat_discount > $discount_categorie) {
                $discount_categorie = (float) $cat_discount;
                $term = get_term($cat_id);
                $cat_name = $term ? $term->name : '';
            }
        }
        
        if ($discount_categorie > 0) {
            if ($return_source) return array('discount' => $discount_categorie, 'source' => 'categorie: ' . $cat_name);
            return $discount_categorie;
        }
        
        // 3. Fallback: discount implicit global
        $discount_implicit = (float) get_option('webgsm_b2b_discount_implicit', 0);
        if ($return_source) return array('discount' => $discount_implicit, 'source' => 'implicit (setări)');
        return $discount_implicit;
    }
    
    // =========================================
    // ADMIN MENU & SETTINGS
    // =========================================
    
    public function add_admin_menu() {
        add_menu_page(
            'B2B Pricing',
            'B2B Pricing',
            'manage_options',
            'webgsm-b2b-pricing',
            array($this, 'render_admin_page'),
            'dashicons-chart-line',
            56
        );
        
        add_submenu_page(
            'webgsm-b2b-pricing',
            'Setări',
            'Setări',
            'manage_options',
            'webgsm-b2b-pricing',
            array($this, 'render_admin_page')
        );
        
        add_submenu_page(
            'webgsm-b2b-pricing',
            'Clienți B2B',
            'Clienți B2B',
            'manage_options',
            'webgsm-b2b-customers',
            array($this, 'render_customers_page')
        );
        
        add_submenu_page(
            'webgsm-b2b-pricing',
            'Rapoarte',
            'Rapoarte',
            'manage_options',
            'webgsm-b2b-reports',
            array($this, 'render_reports_page')
        );
    }
    
    public function register_settings() {
        register_setting('webgsm_b2b_settings', 'webgsm_b2b_discount_implicit');
        register_setting('webgsm_b2b_settings', 'webgsm_b2b_marja_minima');
        register_setting('webgsm_b2b_settings', 'webgsm_b2b_tiers');
        register_setting('webgsm_b2b_settings', 'webgsm_b2b_show_badge');
        register_setting('webgsm_b2b_settings', 'webgsm_b2b_badge_text');
        
        // Clear cache când se salvează setările
        add_action('update_option_webgsm_b2b_discount_implicit', array($this, 'clear_all_price_cache'));
        add_action('update_option_webgsm_b2b_marja_minima', array($this, 'clear_all_price_cache'));
        add_action('update_option_webgsm_b2b_tiers', array($this, 'clear_all_price_cache'));
    }
    
    public function admin_assets($hook) {
        // Încarcă pe paginile B2B settings
        if (strpos($hook, 'webgsm-b2b') !== false) {
            wp_enqueue_style('webgsm-b2b-admin', WEBGSM_B2B_URL . 'assets/admin.css', array(), WEBGSM_B2B_VERSION);
            wp_enqueue_script('webgsm-b2b-admin', WEBGSM_B2B_URL . 'assets/admin.js', array('jquery'), WEBGSM_B2B_VERSION, true);
        }
        
        // Încarcă CSS și în lista de produse WooCommerce pentru coloana Price
        if ($hook === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'product') {
            wp_enqueue_style('webgsm-b2b-admin', WEBGSM_B2B_URL . 'assets/admin.css', array(), WEBGSM_B2B_VERSION);
        }
    }
    
    // Placeholder pentru paginile admin - vor fi în fișiere separate
    public function render_admin_page() {
        include WEBGSM_B2B_PATH . 'admin/settings-page.php';
    }
    
    public function render_customers_page() {
        include WEBGSM_B2B_PATH . 'admin/customers-page.php';
    }
    
    public function render_reports_page() {
        include WEBGSM_B2B_PATH . 'admin/reports-page.php';
    }
    
    // =========================================
    // CONTINUĂ ÎN PROMPT 2...
    // =========================================
        // =========================================
        // CÂMPURI META PRODUS - ADMIN
        // =========================================
        /**
         * Adaugă câmpurile B2B în tab-ul Pricing al produsului
         */
        public function add_product_pricing_fields() {
            global $post;
            echo '<div class="options_group webgsm-b2b-fields">';
            echo '<h4 style="padding-left: 12px; margin-top: 15px; color: #2563eb; border-top: 1px solid #e5e7eb; padding-top: 15px;">
                <span class="dashicons dashicons-building" style="margin-right: 5px;"></span>
                Prețuri B2B (Persoane Juridice)
            </h4>';
            // Preț achiziție
            woocommerce_wp_text_input(array(
                'id' => '_pret_achizitie',
                'label' => 'Preț achiziție (cost)',
                'description' => 'Prețul de achiziție/cost al produsului. Folosit pentru calcul profitabilitate.',
                'desc_tip' => true,
                'type' => 'number',
                'custom_attributes' => array('step' => '0.01', 'min' => '0'),
                'data_type' => 'price'
            ));
            // Preț minim vânzare
            woocommerce_wp_text_input(array(
                'id' => '_pret_minim_vanzare',
                'label' => 'Preț minim vânzare',
                'description' => 'HARD LIMIT: Niciun discount nu va coborî prețul sub această valoare. Lasă gol pentru calcul automat din preț achiziție + marjă.',
                'desc_tip' => true,
                'type' => 'number',
                'custom_attributes' => array('step' => '0.01', 'min' => '0'),
                'data_type' => 'price'
            ));
            // Discount PJ
            woocommerce_wp_text_input(array(
                'id' => '_discount_pj',
                'label' => 'Discount PJ (%)',
                'description' => 'Discount procentual pentru Persoane Juridice. Lasă gol pentru a folosi discountul din categorie sau cel implicit.',
                'desc_tip' => true,
                'type' => 'number',
                'custom_attributes' => array('step' => '0.1', 'min' => '0', 'max' => '100'),
                'placeholder' => 'Din categorie'
            ));
            // Info box cu calcul preț
            $price = get_post_meta($post->ID, '_regular_price', true);
            $pret_minim = get_post_meta($post->ID, '_pret_minim_vanzare', true);
            $discount = get_post_meta($post->ID, '_discount_pj', true);
            if ($price) {
                $discount_val = $discount ? $discount : get_option('webgsm_b2b_discount_implicit', 5);
                $pret_pj = $price - ($price * $discount_val / 100);
                $pret_minim_display = $pret_minim ? $pret_minim : 'Auto';
                echo '<div class="webgsm-b2b-preview" style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 12px; margin: 10px 12px;">';
                echo '<p style="margin: 0 0 8px 0; font-weight: 600; color: #15803d;">📊 Preview preț B2B:</p>';
                echo '<table style="width: 100%; font-size: 13px;">';
                echo '<tr><td>Preț retail (PF):</td><td style="text-align: right;"><strong>' . wc_price($price) . '</strong></td></tr>';
                echo '<tr><td>Discount PJ aplicat:</td><td style="text-align: right;">' . $discount_val . '%</td></tr>';
                echo '<tr><td>Preț PJ calculat:</td><td style="text-align: right; color: #2563eb;"><strong>' . wc_price($pret_pj) . '</strong></td></tr>';
                echo '<tr><td>Preț minim (protecție):</td><td style="text-align: right;">' . ($pret_minim ? wc_price($pret_minim) : 'Auto') . '</td></tr>';
                echo '</table>';
                echo '</div>';
            }
            echo '</div>';
        }
        /**
         * Salvează câmpurile meta produs
         */
        public function save_product_pricing_fields($post_id) {
            if (isset($_POST['_pret_achizitie'])) {
                $pret_achizitie = sanitize_text_field($_POST['_pret_achizitie']);
                update_post_meta($post_id, '_pret_achizitie', $pret_achizitie);
            }
            if (isset($_POST['_pret_minim_vanzare'])) {
                $pret_minim = sanitize_text_field($_POST['_pret_minim_vanzare']);
                update_post_meta($post_id, '_pret_minim_vanzare', $pret_minim);
            }
            if (isset($_POST['_discount_pj'])) {
                $discount = sanitize_text_field($_POST['_discount_pj']);
                if ($discount !== '' && ($discount < 0 || $discount > 100)) {
                    $discount = max(0, min(100, $discount));
                }
                update_post_meta($post_id, '_discount_pj', $discount);
            }
            
            // Clear ALL price cache când se salvează produsul
            $this->clear_all_price_cache();
            wp_cache_delete('b2b_price_' . $post_id . '_' . get_current_user_id(), 'webgsm_b2b');
        }
        // =========================================
        // CÂMPURI META CATEGORIE
        // =========================================
        public function add_category_fields() {
            ?>
            <div class="form-field">
                <label for="_discount_pj_categorie">Discount PJ (%)</label>
                <input type="number" name="_discount_pj_categorie" id="_discount_pj_categorie" step="0.1" min="0" max="100" value="">
                <p class="description">Discount implicit pentru toate produsele din această categorie (dacă produsul nu are discount specific setat).</p>
            </div>
            <div class="form-field">
                <label for="_marja_minima_categorie">Marjă minimă (%)</label>
                <input type="number" name="_marja_minima_categorie" id="_marja_minima_categorie" step="0.1" min="0" max="100" value="">
                <p class="description">Marjă minimă de profit pentru produsele din această categorie. Lasă gol pentru valoarea globală.</p>
            </div>
            <?php
        }
        public function edit_category_fields($term) {
            $discount = get_term_meta($term->term_id, '_discount_pj_categorie', true);
            $marja = get_term_meta($term->term_id, '_marja_minima_categorie', true);
            ?>
            <tr class="form-field">
                <th scope="row"><label for="_discount_pj_categorie">Discount PJ (%)</label></th>
                <td>
                    <input type="number" name="_discount_pj_categorie" id="_discount_pj_categorie" step="0.1" min="0" max="100" value="<?php echo esc_attr($discount); ?>">
                    <p class="description">Discount implicit pentru toate produsele din această categorie (dacă produsul nu are discount specific setat).</p>
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row"><label for="_marja_minima_categorie">Marjă minimă (%)</label></th>
                <td>
                    <input type="number" name="_marja_minima_categorie" id="_marja_minima_categorie" step="0.1" min="0" max="100" value="<?php echo esc_attr($marja); ?>">
                    <p class="description">Marjă minimă de profit pentru produsele din această categorie.</p>
                </td>
            </tr>
            <?php
        }
        public function save_category_fields($term_id) {
            if (isset($_POST['_discount_pj_categorie'])) {
                $discount = sanitize_text_field($_POST['_discount_pj_categorie']);
                update_term_meta($term_id, '_discount_pj_categorie', $discount);
            }
            if (isset($_POST['_marja_minima_categorie'])) {
                $marja = sanitize_text_field($_POST['_marja_minima_categorie']);
                update_term_meta($term_id, '_marja_minima_categorie', $marja);
            }
            
            // Clear ALL price cache când se salvează categoria
            $this->clear_all_price_cache();
        }
        // =========================================
        // DISPLAY PREȚ PE FRONTEND
        // =========================================
        /**
         * Modifică HTML-ul prețului pentru a arăta badge B2B și prețul original
         */
        public function modify_price_html($price_html, $product) {
            // În admin, afișează ÎNTOTDEAUNA
            $is_admin = is_admin();
            
            // Verifică dacă suntem în pagina produsului (single product)
            $is_single_product = is_product();
            
            // Frontend: doar pentru PJ
            if (!$is_admin && !$this->is_user_pj()) {
                return $price_html;
            }
            
            // Obține prețul ORIGINAL (înainte de discount B2B) direct din meta
            $product_id = $product->get_id();
            $original_price = get_post_meta($product_id, '_regular_price', true);
            
            // Dacă e variație, ia prețul din variație
            if ($product->is_type('variation')) {
                $original_price = get_post_meta($product_id, '_regular_price', true);
                if (empty($original_price)) {
                    $parent_id = $product->get_parent_id();
                    $original_price = get_post_meta($parent_id, '_regular_price', true);
                }
            }
            
            // Prețul B2B (deja aplicat)
            $b2b_price = $product->get_price();
            
            // Preț minim setat manual
            $pret_minim = get_post_meta($product_id, '_pret_minim_vanzare', true);
            
            // ADMIN: Afișare simplă pe 3 linii
            if ($is_admin) {
                $output = '<div style="line-height:1.8; font-size:13px;">';
                
                // Linia 1: Preț setat (NEGRU) - cerc negru Unicode
                if ($original_price > 0) {
                    $output .= '<div style="color:#000; font-weight:500; display:flex; align-items:center; gap:5px;">';
                    $output .= '<span style="color:#000; font-size:10px; line-height:1;">●</span>';
                    $output .= wc_price($original_price);
                    $output .= '</div>';
                }
                
                // Linia 2: Preț B2B (ALBASTRU) - cerc albastru Unicode
                if ($b2b_price > 0) {
                    if ((float)$b2b_price < (float)$original_price) {
                        // Are discount - afișează albastru
                        $output .= '<div style="color:#2196F3; font-weight:600; display:flex; align-items:center; gap:5px;">';
                        $output .= '<span style="color:#2196F3; font-size:10px; line-height:1;">●</span>';
                        $output .= wc_price($b2b_price);
                        $output .= '</div>';
                    } elseif ((float)$b2b_price == (float)$original_price) {
                        // Fără discount - afișează gri
                        $output .= '<div style="color:#999; font-size:12px; display:flex; align-items:center; gap:5px;">';
                        $output .= '<span style="color:#999; font-size:10px; line-height:1;">●</span>';
                        $output .= 'Fără discount B2B';
                        $output .= '</div>';
                    }
                }
                
                // Linia 3: Preț minim (ROȘU) - cerc roșu Unicode
                if ($pret_minim > 0) {
                    $output .= '<div style="color:#f44336; font-size:11px; display:flex; align-items:center; gap:5px;">';
                    $output .= '<span style="color:#f44336; font-size:10px; line-height:1;">●</span>';
                    $output .= wc_price($pret_minim);
                    $output .= '</div>';
                }
                
                $output .= '</div>';
                return $output;
            }
            
            // FRONTEND: Afișare detaliată
            if ($is_single_product && $original_price > 0 && $b2b_price > 0 && (float)$b2b_price < (float)$original_price) {
                $price_display = '<div class="webgsm-b2b-price-wrapper" style="display: flex; flex-direction: column; gap: 5px;">';
                
                // Preț original tăiat (PF)
                $price_display .= '<div class="webgsm-original-price" style="display: flex; align-items: center; gap: 8px;">';
                $price_display .= '<span style="text-decoration: line-through; color: #9ca3af; font-size: 0.9em;">' . wc_price($original_price) . '</span>';
                $price_display .= '<span style="font-size: 11px; color: #64748b; background: #f1f5f9; padding: 2px 6px; border-radius: 3px;">Preț RRC</span>';
                $price_display .= '</div>';
                
                // Preț B2B (mai mare, evidențiat)
                $price_display .= '<div class="webgsm-b2b-price" style="display: flex; align-items: center; gap: 8px;">';
                $price_display .= '<span class="price" style="font-size: 1.3em; color: #15803d; font-weight: 700;">' . wc_price($b2b_price) . '</span>';
                $price_display .= '<span class="webgsm-b2b-badge" style="
                    display: inline-block;
                    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
                    color: #fff;
                    font-size: 10px;
                    padding: 3px 8px;
                    border-radius: 4px;
                    font-weight: 600;
                ">Preț B2B</span>';
                $price_display .= '</div>';
                
                // Economie
                $savings = (float)$original_price - (float)$b2b_price;
                $savings_percent = round(($savings / (float)$original_price) * 100);
                $price_display .= '<div class="webgsm-savings" style="font-size: 12px; color: #15803d; font-weight: 500;">';
                $price_display .= '✓ Economisești ' . wc_price($savings) . ' (' . $savings_percent . '%)';
                $price_display .= '</div>';
                
                $price_display .= '</div>';
                
                return $price_display;
            }
            
            // Pentru listing (shop, categorii) - doar badge simplu
            $show_badge = get_option('webgsm_b2b_show_badge', 'yes');
            if ($show_badge !== 'yes') {
                return $price_html;
            }
            
            $badge_text = get_option('webgsm_b2b_badge_text', 'Preț B2B');
            
            $badge = '<span class="webgsm-b2b-badge" style="
                display: inline-block;
                background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
                color: #fff;
                font-size: 10px;
                padding: 2px 6px;
                border-radius: 4px;
                margin-left: 6px;
                font-weight: 600;
                vertical-align: middle;
            ">' . esc_html($badge_text) . '</span>';
            
            return $price_html . $badge;
        }
        /**
         * Aplică prețul B2B în coș
         * NOTĂ: Prețul e deja aplicat prin filtrele de preț, 
         * dar coșul poate necesita recalculare în unele cazuri
         */
        public function apply_b2b_cart_price($cart) {
            if (is_admin() && !defined('DOING_AJAX')) {
                return;
            }
            if (!$this->is_user_pj()) {
                return;
            }
            
            foreach ($cart->get_cart() as $cart_item) {
                $product = $cart_item['data'];
                $product_id = $product->get_id();
                
                // Ia prețul ORIGINAL din meta, NU prin get_regular_price() care e deja filtrat
                $original_price = get_post_meta($product_id, '_regular_price', true);
                
                if (empty($original_price)) {
                    // Fallback pentru variații
                    if ($product->is_type('variation')) {
                        $original_price = get_post_meta($product_id, '_regular_price', true);
                        if (empty($original_price)) {
                            $parent_id = $product->get_parent_id();
                            $original_price = get_post_meta($parent_id, '_regular_price', true);
                        }
                    }
                }
                
                if ($original_price > 0) {
                    $b2b_price = $this->calculate_b2b_price((float)$original_price, $product);
                    $cart_item['data']->set_price($b2b_price);
                }
            }
        }
        
        /**
         * Display tier price info în cart
         */
        public function display_cart_item_tier_price($price_html, $cart_item, $cart_item_key) {
            if (!$this->is_user_pj()) {
                return $price_html;
            }
            
            $product = $cart_item['data'];
            $product_id = $product->get_id();
            $original_price = get_post_meta($product_id, '_regular_price', true);
            $b2b_price = $product->get_price();
            
            if ($original_price > 0 && $b2b_price > 0 && (float)$b2b_price < (float)$original_price) {
                $discount_pj = $this->get_discount_pj($product);
                $tier = $this->get_user_tier();
                $tiers = get_option('webgsm_b2b_tiers', $this->get_default_tiers());
                $discount_tier = isset($tiers[$tier]['discount_extra']) ? (float) $tiers[$tier]['discount_extra'] : 0;
                $discount_total = $discount_pj + $discount_tier;
                
                $price_html .= '<br><small style="color: #15803d;">Discount B2B: -' . number_format($discount_total, 1) . '%</small>';
            }
            
            return $price_html;
        }
        
        /**
         * Afișează rândul cu economiile B2B în cart/checkout (DUPĂ total)
         */
        public function display_b2b_savings_row() {
            if (!$this->is_user_pj()) {
                return;
            }
            
            $cart = WC()->cart;
            if (!$cart) return;
            
            $total_discount = 0;
            $total_original = 0;
            
            // Calculează economiile din B2B
            foreach ($cart->get_cart() as $cart_item) {
                $product = $cart_item['data'];
                $product_id = $product->get_id();
                $quantity = $cart_item['quantity'];
                
                // Ia prețul REAL original (înainte de orice discount)
                $original_price = get_post_meta($product_id, '_regular_price', true);
                
                // Calculează discount-ul exact cum îl aplică pluginul
                $discount_pj = $this->get_discount_pj($product);
                $tier = $this->get_user_tier();
                $tiers = get_option('webgsm_b2b_tiers', $this->get_default_tiers());
                $discount_tier = isset($tiers[$tier]['discount_extra']) ? (float) $tiers[$tier]['discount_extra'] : 0;
                $total_discount_percent = $discount_pj + $discount_tier;
                
                if ($original_price > 0 && $total_discount_percent > 0) {
                    $discount_amount = ((float)$original_price * $total_discount_percent / 100) * $quantity;
                    $total_discount += $discount_amount;
                    $total_original += (float)$original_price * $quantity;
                }
            }
            
            // Afișează economiile
            if ($total_discount > 0) {
                $discount_percent = round(($total_discount / $total_original) * 100, 1);
                ?>
                <tr class="webgsm-b2b-savings-row">
                    <th style="color: #15803d; font-weight: 600;">
                        🎯 Economie B2B (<?php echo $discount_percent; ?>%)
                    </th>
                    <td data-title="Economie B2B" style="color: #15803d; font-weight: 600;">
                        <strong>-<?php echo wc_price($total_discount); ?></strong>
                    </td>
                </tr>
                <style>
                    .webgsm-b2b-savings-row th,
                    .webgsm-b2b-savings-row td {
                        border-top: 2px dashed #15803d !important;
                        padding-top: 10px !important;
                    }
                </style>
                <?php
            }
        }
        // =========================================
        // UPDATE TIER LA COMANDĂ FINALIZATĂ
        // =========================================
        public function update_user_tier_on_order($order_id) {
            $order = wc_get_order($order_id);
            if (!$order) return;
            $user_id = $order->get_customer_id();
            if (!$user_id) return;
            delete_user_meta($user_id, '_pj_orders_calculated');
            delete_user_meta($user_id, '_pj_value_calculated');
            $new_tier = $this->calculate_user_tier($user_id);
            $old_tier = get_user_meta($user_id, '_pj_tier', true);
            update_user_meta($user_id, '_pj_tier', $new_tier);
            if ($old_tier && $new_tier !== $old_tier) {
                do_action('webgsm_b2b_tier_changed', $user_id, $old_tier, $new_tier);
            }
        }
        // =========================================
        // COLOANĂ PROFIT ÎN COMENZI ADMIN
        // =========================================
        public function add_order_profit_column($columns) {
            $new_columns = array();
            foreach ($columns as $key => $value) {
                $new_columns[$key] = $value;
                if ($key === 'order_total') {
                    $new_columns['order_profit'] = 'Profit';
                }
            }
            return $new_columns;
        }
        public function render_order_profit_column($column, $post_id) {
            if ($column !== 'order_profit') return;
            $order = wc_get_order($post_id);
            if (!$order) {
                echo '-';
                return;
            }
            $profit = 0;
            foreach ($order->get_items() as $item) {
                $product_id = $item->get_product_id();
                $pret_achizitie = get_post_meta($product_id, '_pret_achizitie', true);
                $qty = $item->get_quantity();
                $line_total = $item->get_total();
                if ($pret_achizitie) {
                    $cost = (float) $pret_achizitie * $qty;
                    $profit += $line_total - $cost;
                }
            }
            if ($profit > 0) {
                echo '<span style="color: #15803d;">' . wc_price($profit) . '</span>';
            } elseif ($profit < 0) {
                echo '<span style="color: #dc2626;">' . wc_price($profit) . '</span>';
            } else {
                echo '-';
            }
        }
        
        // =========================================
        // ÎNREGISTRARE CONT - EXTINDERE FORMULAR
        // =========================================

        /**
         * Adaugă toggle PF/PJ la începutul formularului cu LINE-ART
         */
        public function add_pj_toggle_to_registration() {
            ?>
            <style>
                .webgsm-reg-toggle {
                    display: flex;
                    gap: 15px;
                    margin: 25px 0;
                    padding: 20px;
                    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                    border: 1px solid #e2e8f0;
                    border-radius: 12px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
                }

                .webgsm-reg-toggle input[type="radio"] {
                    display: none;
                }

                .webgsm-reg-toggle label {
                    flex: 1;
                    padding: 16px 20px;
                    border: 2px solid #e2e8f0;
                    border-radius: 10px;
                    cursor: pointer;
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    text-align: center;
                    background: #fff;
                    font-weight: 600;
                    position: relative;
                    overflow: hidden;
                }

                .webgsm-reg-toggle label::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: -100%;
                    width: 100%;
                    height: 100%;
                    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
                    transition: left 0.5s;
                }

                .webgsm-reg-toggle label:hover {
                    border-color: #cbd5e1;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                }

                .webgsm-reg-toggle label:hover::before {
                    left: 100%;
                }

                .webgsm-reg-toggle input:checked + label {
                    border-color: #2563eb;
                    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
                    color: #1d4ed8;
                    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.25);
                    transform: translateY(-3px);
                }

                .webgsm-reg-toggle label svg {
                    width: 32px;
                    height: 32px;
                    stroke: #64748b;
                    stroke-width: 1.5;
                    fill: none;
                    display: block;
                    margin: 0 auto 8px;
                    transition: stroke 0.3s;
                }

                .webgsm-reg-toggle input:checked + label svg {
                    stroke: #2563eb;
                    stroke-width: 2;
                }

                .webgsm-reg-toggle label strong {
                    display: block;
                    font-size: 15px;
                    margin-bottom: 4px;
                }

                .webgsm-reg-toggle label small {
                    display: block;
                    font-size: 12px;
                    opacity: 0.9;
                    font-weight: 400;
                }

                .webgsm-reg-toggle .b2b-highlight {
                    display: inline-block;
                    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
                    color: #fff;
                    font-size: 10px;
                    padding: 4px 8px;
                    border-radius: 6px;
                    margin-top: 6px;
                    font-weight: 700;
                    box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);
                    animation: pulse 2s infinite;
                }

                @keyframes pulse {
                    0%, 100% { opacity: 1; }
                    50% { opacity: 0.8; }
                }

                .webgsm-pj-extra {
                    display: none;
                    margin-top: 20px;
                    padding: 20px;
                    background: #fff;
                    border: 1px solid #e5e7eb;
                    border-radius: 8px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
                    animation: slideDown 0.4s ease;
                }

                .webgsm-pj-extra.active {
                    display: block;
                }

                @keyframes slideDown {
                    from {
                        opacity: 0;
                        transform: translateY(-10px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .webgsm-pj-extra .form-row {
                    margin-bottom: 12px;
                }

                .webgsm-pj-extra label {
                    display: block;
                    margin-bottom: 6px;
                    font-size: 13px;
                    font-weight: 600;
                    color: #374151;
                }

                .webgsm-pj-extra input {
                    width: 100%;
                    padding: 10px 12px;
                    border: 1px solid #d1d5db;
                    border-radius: 6px;
                    font-size: 14px;
                    transition: border-color 0.2s;
                }

                .webgsm-pj-extra input:focus {
                    border-color: #2563eb;
                    outline: none;
                    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
                }

                @media (max-width: 600px) {
                    .webgsm-reg-toggle {
                        flex-direction: column;
                        gap: 10px;
                    }
                }
            </style>

            <div class="webgsm-reg-toggle">
                <input type="radio" name="tip_client" id="reg_pf" value="pf" checked>
                <label for="reg_pf">
                    <svg viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <strong>Persoană Fizică</strong>
                    <small>Cont personal standard</small>
                </label>

                <input type="radio" name="tip_client" id="reg_pj" value="pj">
                <label for="reg_pj">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 21h18"></path>
                        <path d="M5 21V7l7-4 7 4v14"></path>
                        <path d="M9 21v-6h6v6"></path>
                        <path d="M9 9h.01M15 9h.01M9 13h.01M15 13h.01"></path>
                    </svg>
                    <strong>Persoană Juridică</strong>
                    <small>Cont de firmă</small>
                    <div class="b2b-highlight">PREȚURI B2B</div>
                </label>
            </div>
            <?php
        }

        /**
         * Adaugă câmpurile suplimentare pentru PJ
         */
        public function add_pj_fields_to_registration() {
            ?>
            <div class="webgsm-pj-extra" id="pj-extra-fields">
                <p class="form-row">
                    <label for="billing_company">Denumire Firmă</label>
                    <input type="text" name="billing_company" id="billing_company" value="<?php echo isset($_POST['billing_company']) ? esc_attr($_POST['billing_company']) : ''; ?>">
                </p>

                <div style="display: flex; gap: 10px;">
                    <p class="form-row" style="flex: 1;">
                        <label for="billing_cui">CUI / CIF</label>
                        <input type="text" name="billing_cui" id="billing_cui" value="<?php echo isset($_POST['billing_cui']) ? esc_attr($_POST['billing_cui']) : ''; ?>" placeholder="RO12345678">
                    </p>

                    <p class="form-row" style="flex: 1;">
                        <label for="billing_nr_reg_com">Nr. Reg. Comerțului</label>
                        <input type="text" name="billing_nr_reg_com" id="billing_nr_reg_com" value="<?php echo isset($_POST['billing_nr_reg_com']) ? esc_attr($_POST['billing_nr_reg_com']) : ''; ?>" placeholder="J40/123/2020">
                    </p>
                </div>
            </div>

            <script>
            jQuery(document).ready(function($) {
                $('input[name="tip_client"]').on('change', function() {
                    if ($(this).val() === 'pj') {
                        $('#pj-extra-fields').addClass('active');
                    } else {
                        $('#pj-extra-fields').removeClass('active');
                    }
                });
            });
            </script>
            <?php
        }

        /**
         * Validează câmpurile PJ
         */
        public function validate_pj_registration($errors, $username, $email) {
            if (isset($_POST['tip_client']) && $_POST['tip_client'] === 'pj') {
                if (empty($_POST['billing_company'])) {
                    $errors->add('billing_company_error', '<strong>Eroare:</strong> Denumirea firmei este obligatorie pentru PJ.');
                }
                if (empty($_POST['billing_cui'])) {
                    $errors->add('billing_cui_error', '<strong>Eroare:</strong> CUI-ul este obligatoriu pentru PJ.');
                }
            }
            return $errors;
        }

        /**
         * Detectează și marchează user-ul ca PJ după înregistrare
         * Compatibil cu formularul din temă (registration-enhanced.php)
         */
        public function detect_pj_on_registration($customer_id) {
            // Verifică tipul din mai multe surse (tema folosește tip_facturare)
            $tip = '';
            $tip_fields = array('tip_facturare', 'tip_client', '_tip_facturare', '_tip_client');
            foreach ($tip_fields as $field) {
                if (!empty($_POST[$field])) {
                    $tip = sanitize_text_field($_POST[$field]);
                    break;
                }
            }
            
            // Verifică CUI din mai multe surse (tema folosește firma_cui)
            $cui = '';
            $cui_fields = array('firma_cui', 'billing_cui', '_firma_cui', 'cui', 'cif');
            foreach ($cui_fields as $field) {
                if (!empty($_POST[$field])) {
                    $cui = sanitize_text_field($_POST[$field]);
                    break;
                }
            }
            
            // Verifică și în user meta (poate tema l-a salvat deja)
            if (empty($cui)) {
                $cui = get_user_meta($customer_id, '_firma_cui', true);
                if (empty($cui)) {
                    $cui = get_user_meta($customer_id, 'billing_cui', true);
                }
            }
            
            // Verifică company din mai multe surse (tema folosește firma_nume)
            $company = '';
            $company_fields = array('firma_nume', 'billing_company', '_firma_nume');
            foreach ($company_fields as $field) {
                if (!empty($_POST[$field])) {
                    $company = sanitize_text_field($_POST[$field]);
                    break;
                }
            }
            
            if (empty($company)) {
                $company = get_user_meta($customer_id, '_firma_nume', true);
                if (empty($company)) {
                    $company = get_user_meta($customer_id, 'billing_company', true);
                }
            }
            
            // Detectează dacă e PJ
            $is_pj = ($tip === 'pj' || !empty($cui) || !empty($company));
            
            if ($is_pj) {
                // MARCHEAZĂ CA B2B
                update_user_meta($customer_id, '_is_pj', 'yes');
                update_user_meta($customer_id, '_tip_client', 'pj');
                
                // Copiază datele din tema în câmpurile standard billing
                if (!empty($cui)) {
                    update_user_meta($customer_id, 'billing_cui', $cui);
                }
                if (!empty($company)) {
                    update_user_meta($customer_id, 'billing_company', $company);
                }
                
                // Nr. Reg. Com
                $reg_com = '';
                if (!empty($_POST['firma_reg_com'])) {
                    $reg_com = sanitize_text_field($_POST['firma_reg_com']);
                } elseif (!empty($_POST['billing_nr_reg_com'])) {
                    $reg_com = sanitize_text_field($_POST['billing_nr_reg_com']);
                }
                if (empty($reg_com)) {
                    $reg_com = get_user_meta($customer_id, '_firma_reg_com', true);
                }
                if (!empty($reg_com)) {
                    update_user_meta($customer_id, 'billing_nr_reg_com', $reg_com);
                }
                
                // Adresa firmei -> billing address
                if (!empty($_POST['firma_adresa']) || !empty(get_user_meta($customer_id, '_firma_adresa', true))) {
                    $adresa = !empty($_POST['firma_adresa']) ? sanitize_text_field($_POST['firma_adresa']) : get_user_meta($customer_id, '_firma_adresa', true);
                    update_user_meta($customer_id, 'billing_address_1', $adresa);
                    update_user_meta($customer_id, 'shipping_address_1', $adresa);
                }
                
                if (!empty($_POST['firma_oras']) || !empty(get_user_meta($customer_id, '_firma_oras', true))) {
                    $oras = !empty($_POST['firma_oras']) ? sanitize_text_field($_POST['firma_oras']) : get_user_meta($customer_id, '_firma_oras', true);
                    update_user_meta($customer_id, 'billing_city', $oras);
                    update_user_meta($customer_id, 'shipping_city', $oras);
                }
                
                if (!empty($_POST['firma_judet']) || !empty(get_user_meta($customer_id, '_firma_judet', true))) {
                    $judet = !empty($_POST['firma_judet']) ? sanitize_text_field($_POST['firma_judet']) : get_user_meta($customer_id, '_firma_judet', true);
                    update_user_meta($customer_id, 'billing_state', $judet);
                    update_user_meta($customer_id, 'shipping_state', $judet);
                }
            } else {
                update_user_meta($customer_id, '_tip_client', 'pf');
            }
            
            // Setează țara implicit România
            if (empty(get_user_meta($customer_id, 'billing_country', true))) {
                update_user_meta($customer_id, 'billing_country', 'RO');
                update_user_meta($customer_id, 'shipping_country', 'RO');
            }
        }
}

// Inițializare plugin
function webgsm_b2b_pricing() {
    return WebGSM_B2B_Pricing::instance();
}
add_action('plugins_loaded', 'webgsm_b2b_pricing');

// Activare plugin
register_activation_hook(__FILE__, function() {
    // Setări implicite
    if (!get_option('webgsm_b2b_discount_implicit')) {
        update_option('webgsm_b2b_discount_implicit', 5);
    }
    if (!get_option('webgsm_b2b_marja_minima')) {
        update_option('webgsm_b2b_marja_minima', 5);
    }
    if (!get_option('webgsm_b2b_tiers')) {
        update_option('webgsm_b2b_tiers', WebGSM_B2B_Pricing::instance()->get_default_tiers());
    }
    
    // Flush rewrite rules
    flush_rewrite_rules();
});
