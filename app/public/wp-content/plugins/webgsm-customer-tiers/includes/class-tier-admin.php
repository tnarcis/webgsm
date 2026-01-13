<?php
/**
 * Tier Admin Class
 * Handles admin interface for tier management
 */

if (!defined('ABSPATH')) {
    exit;
}

class WebGSM_Tier_Admin {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Register settings
        add_action('admin_init', array($this, 'register_settings'));
        
        // Add customer tier column in users list
        add_filter('manage_users_columns', array($this, 'add_tier_column'));
        add_filter('manage_users_custom_column', array($this, 'show_tier_column'), 10, 3);
        
        // Add tier info in customer edit page
        add_action('show_user_profile', array($this, 'show_tier_info_in_profile'));
        add_action('edit_user_profile', array($this, 'show_tier_info_in_profile'));
        
        // Enqueue admin styles
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Customer Tiers', 'webgsm-customer-tiers'),
            __('Customer Tiers', 'webgsm-customer-tiers'),
            'manage_woocommerce',
            'webgsm-customer-tiers',
            array($this, 'render_admin_page'),
            'dashicons-awards',
            56
        );
        
        add_submenu_page(
            'webgsm-customer-tiers',
            __('Tier Settings', 'webgsm-customer-tiers'),
            __('Settings', 'webgsm-customer-tiers'),
            'manage_woocommerce',
            'webgsm-customer-tiers',
            array($this, 'render_admin_page')
        );
        
        add_submenu_page(
            'webgsm-customer-tiers',
            __('Customers Overview', 'webgsm-customer-tiers'),
            __('Customers', 'webgsm-customer-tiers'),
            'manage_woocommerce',
            'webgsm-customer-tiers-customers',
            array($this, 'render_customers_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('webgsm_tiers_settings_group', 'webgsm_tiers_settings');
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'webgsm-customer-tiers') !== false) {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
            
            wp_enqueue_style(
                'webgsm-tiers-admin',
                WEBGSM_TIERS_PLUGIN_URL . 'assets/css/admin.css',
                array(),
                WEBGSM_TIERS_VERSION
            );
            
            wp_enqueue_script(
                'webgsm-tiers-admin',
                WEBGSM_TIERS_PLUGIN_URL . 'assets/js/admin.js',
                array('jquery', 'wp-color-picker'),
                WEBGSM_TIERS_VERSION,
                true
            );
        }
    }
    
    /**
     * Render main admin page
     */
    public function render_admin_page() {
        if (isset($_POST['webgsm_tiers_save']) && check_admin_referer('webgsm_tiers_settings')) {
            $this->save_tier_settings();
        }
        
        $manager = WebGSM_Tier_Manager::get_instance();
        $tiers = $manager->get_tiers();
        
        ?>
        <div class="wrap">
            <h1><?php _e('Customer Tiers Settings', 'webgsm-customer-tiers'); ?></h1>
            
            <div class="webgsm-tiers-admin-container">
                <form method="post" action="">
                    <?php wp_nonce_field('webgsm_tiers_settings'); ?>
                    
                    <table class="widefat webgsm-tiers-table">
                        <thead>
                            <tr>
                                <th><?php _e('Tier Name', 'webgsm-customer-tiers'); ?></th>
                                <th><?php _e('Min. Valoare Lunară (RON)', 'webgsm-customer-tiers'); ?></th>
                                <th><?php _e('Discount (%)', 'webgsm-customer-tiers'); ?></th>
                                <th><?php _e('Color', 'webgsm-customer-tiers'); ?></th>
                                <th><?php _e('Activ', 'webgsm-customer-tiers'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tiers as $key => $tier) : ?>
                                <tr>
                                    <td>
                                        <input type="text" 
                                               name="tiers[<?php echo esc_attr($key); ?>][name]" 
                                               value="<?php echo esc_attr($tier['name']); ?>"
                                               class="regular-text" />
                                    </td>
                                    <td>
                                        <input type="number" 
                                               name="tiers[<?php echo esc_attr($key); ?>][min_value]" 
                                               value="<?php echo esc_attr($tier['min_value']); ?>"
                                               step="0.01"
                                               min="0"
                                               class="small-text" />
                                    </td>
                                    <td>
                                        <input type="number" 
                                               name="tiers[<?php echo esc_attr($key); ?>][discount]" 
                                               value="<?php echo esc_attr($tier['discount']); ?>"
                                               step="0.1"
                                               min="0"
                                               max="100"
                                               class="small-text" />
                                    </td>
                                    <td>
                                        <input type="text" 
                                               name="tiers[<?php echo esc_attr($key); ?>][color]" 
                                               value="<?php echo esc_attr($tier['color']); ?>"
                                               class="webgsm-color-picker" />
                                    </td>
                                    <td>
                                        <input type="checkbox" 
                                               name="tiers[<?php echo esc_attr($key); ?>][enabled]" 
                                               value="1"
                                               <?php checked(isset($tier['enabled']) && $tier['enabled']); ?> />
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <p class="submit">
                        <button type="submit" name="webgsm_tiers_save" class="button button-primary">
                            <?php _e('Save Settings', 'webgsm-customer-tiers'); ?>
                        </button>
                    </p>
                </form>
                
                <div class="webgsm-tiers-info">
                    <h2><?php _e('Cum funcționează sistemul de tier-uri?', 'webgsm-customer-tiers'); ?></h2>
                    <ul>
                        <li>✅ Tier-ul se calculează automat bazat pe <strong>valoarea totală a comenzilor finalizate în luna curentă</strong></li>
                        <li>✅ Discount-ul din tier se <strong>adaugă la discount-ul existent</strong> din produs/categorie</li>
                        <li>✅ Clienții avansează automat în tier-ul următor când ating valoarea minimă</li>
                        <li>✅ La începutul fiecărei luni noi, calculul se resetează (tier-ul se recalculează)</li>
                        <li>✅ Doar comenzile cu status "completed" sau "processing" sunt luate în calcul</li>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Save tier settings
     */
    private function save_tier_settings() {
        if (!isset($_POST['tiers']) || !is_array($_POST['tiers'])) {
            return;
        }
        
        $tiers = array();
        foreach ($_POST['tiers'] as $key => $tier_data) {
            $tiers[$key] = array(
                'name' => sanitize_text_field($tier_data['name']),
                'min_value' => floatval($tier_data['min_value']),
                'discount' => floatval($tier_data['discount']),
                'color' => sanitize_hex_color($tier_data['color']),
                'enabled' => isset($tier_data['enabled'])
            );
        }
        
        $manager = WebGSM_Tier_Manager::get_instance();
        $manager->update_tiers($tiers);
        
        echo '<div class="notice notice-success"><p>' . __('Settings saved successfully!', 'webgsm-customer-tiers') . '</p></div>';
    }
    
    /**
     * Render customers page
     */
    public function render_customers_page() {
        $calculator = WebGSM_Tier_Calculator::get_instance();
        $customers = $calculator->get_all_customers_with_tiers();
        
        ?>
        <div class="wrap">
            <h1><?php _e('Customers & Tiers Overview', 'webgsm-customer-tiers'); ?></h1>
            
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php _e('Customer', 'webgsm-customer-tiers'); ?></th>
                        <th><?php _e('Email', 'webgsm-customer-tiers'); ?></th>
                        <th><?php _e('Valoare Lunară', 'webgsm-customer-tiers'); ?></th>
                        <th><?php _e('Tier Curent', 'webgsm-customer-tiers'); ?></th>
                        <th><?php _e('Discount', 'webgsm-customer-tiers'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer) : ?>
                        <tr>
                            <td>
                                <a href="<?php echo admin_url('user-edit.php?user_id=' . $customer['id']); ?>">
                                    <?php echo esc_html($customer['name']); ?>
                                </a>
                            </td>
                            <td><?php echo esc_html($customer['email']); ?></td>
                            <td><?php echo wc_price($customer['monthly_total']); ?></td>
                            <td>
                                <?php if ($customer['tier']) : ?>
                                    <span style="background: <?php echo esc_attr($customer['tier']['color']); ?>; color: white; padding: 3px 10px; border-radius: 3px; font-size: 12px;">
                                        <?php echo esc_html($customer['tier']['name']); ?>
                                    </span>
                                <?php else : ?>
                                    <span style="color: #999;">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($customer['tier']) : ?>
                                    <?php echo esc_html($customer['tier']['discount']); ?>%
                                <?php else : ?>
                                    0%
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    
    /**
     * Add tier column to users list
     */
    public function add_tier_column($columns) {
        $columns['webgsm_tier'] = __('Tier', 'webgsm-customer-tiers');
        return $columns;
    }
    
    /**
     * Show tier column content
     */
    public function show_tier_column($value, $column_name, $user_id) {
        if ($column_name === 'webgsm_tier') {
            $manager = WebGSM_Tier_Manager::get_instance();
            $tier = $manager->get_customer_tier($user_id);
            
            if ($tier) {
                return '<span style="background: ' . esc_attr($tier['color']) . '; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px;">' 
                       . esc_html($tier['name']) . '</span>';
            }
        }
        return $value;
    }
    
    /**
     * Show tier info in user profile
     */
    public function show_tier_info_in_profile($user) {
        if (!current_user_can('manage_woocommerce')) {
            return;
        }
        
        $manager = WebGSM_Tier_Manager::get_instance();
        $calculator = WebGSM_Tier_Calculator::get_instance();
        
        $tier = $manager->get_customer_tier($user->ID);
        $progress = $manager->get_customer_tier_progress($user->ID);
        $history = $calculator->get_customer_monthly_history($user->ID);
        
        ?>
        <h2><?php _e('Customer Tier Information', 'webgsm-customer-tiers'); ?></h2>
        <table class="form-table">
            <tr>
                <th><?php _e('Tier Curent', 'webgsm-customer-tiers'); ?></th>
                <td>
                    <?php if ($tier) : ?>
                        <span style="background: <?php echo esc_attr($tier['color']); ?>; color: white; padding: 5px 15px; border-radius: 3px; font-weight: bold;">
                            <?php echo esc_html($tier['name']); ?> (<?php echo esc_html($tier['discount']); ?>% discount)
                        </span>
                    <?php else : ?>
                        <span style="color: #999;">Fără tier</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th><?php _e('Valoare Lunară', 'webgsm-customer-tiers'); ?></th>
                <td>
                    <strong><?php echo wc_price($progress['current_total']); ?></strong>
                </td>
            </tr>
            <?php if ($progress['next_tier']) : ?>
                <tr>
                    <th><?php _e('Următorul Tier', 'webgsm-customer-tiers'); ?></th>
                    <td>
                        <?php echo esc_html($progress['next_tier']['name']); ?> 
                        (mai este nevoie de <?php echo wc_price($progress['amount_to_next']); ?>)
                        <div style="background: #f0f0f0; height: 20px; border-radius: 10px; margin-top: 5px; overflow: hidden;">
                            <div style="background: <?php echo esc_attr($progress['next_tier']['color']); ?>; height: 100%; width: <?php echo min(100, $progress['percentage']); ?>%;"></div>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
            <tr>
                <th><?php _e('Istoric Lunar', 'webgsm-customer-tiers'); ?></th>
                <td>
                    <table class="widefat" style="max-width: 500px;">
                        <thead>
                            <tr>
                                <th>Luna</th>
                                <th>Total Comenzi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $month_data) : ?>
                                <tr>
                                    <td><?php echo esc_html($month_data['month_short']); ?></td>
                                    <td><?php echo $month_data['formatted_total']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <?php
    }
}
