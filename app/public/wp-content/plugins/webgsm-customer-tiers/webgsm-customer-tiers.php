<?php
/**
 * Plugin Name: WebGSM Customer Tiers
 * Description: Sistem de niveluri pentru clienți B2B cu badges și progress tracking
 * Version: 2.0.0
 * Author: WebGSM
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Text Domain: webgsm-tiers
 */

if (!defined('ABSPATH')) exit;

// Constante
define('WEBGSM_TIERS_VERSION', '2.0.0');
define('WEBGSM_TIERS_PATH', plugin_dir_path(__FILE__));
define('WEBGSM_TIERS_URL', plugin_dir_url(__FILE__));

// Include clasa admin
require_once WEBGSM_TIERS_PATH . 'includes/class-tier-admin.php';

/**
 * Clasa principală WebGSM Customer Tiers
 */
class WebGSM_Customer_Tiers {
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function __construct() {
        add_action('init', array($this, 'init'));
    }
    
    public function init() {
        // Include badges & progress
        $this->load_badges_system();
        
        // Admin hooks
        if (is_admin()) {
            new WebGSM_Tier_Admin();
        }
    }
    
    private function load_badges_system() {
        // =========================================
        // BADGES CSS - ELEGANT LINE-ART STYLE
        // =========================================
        
        add_action('wp_head', array($this, 'badges_css'));
    }
    
    public function badges_css() {
        ?>
        <style>
        /* ========================================
           WebGSM B2B Tier Badges - Elegant Design
           ======================================== */
        
        .webgsm-tier-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .webgsm-tier-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .webgsm-tier-badge:hover::before {
            left: 100%;
        }
        
        .webgsm-tier-badge svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }
        
        /* BRONZE - Arămiu elegant */
        .webgsm-tier-badge.tier-bronze {
            background: linear-gradient(135deg, #d4a574 0%, #b8956e 50%, #a67c52 100%);
            color: #4a3728;
            border: 1px solid #c9a077;
            box-shadow: 0 2px 8px rgba(180, 140, 100, 0.25);
        }
        
        .webgsm-tier-badge.tier-bronze:hover {
            box-shadow: 0 4px 16px rgba(180, 140, 100, 0.4);
            transform: translateY(-1px);
        }
        
        .webgsm-tier-badge.tier-bronze svg {
            stroke: #5d4532;
        }
        
        /* SILVER - Argintiu strălucitor */
        .webgsm-tier-badge.tier-silver {
            background: linear-gradient(135deg, #e8e8e8 0%, #c0c0c0 50%, #a8a8a8 100%);
            color: #3d3d3d;
            border: 1px solid #d0d0d0;
            box-shadow: 0 2px 8px rgba(160, 160, 160, 0.3);
        }
        
        .webgsm-tier-badge.tier-silver:hover {
            box-shadow: 0 4px 16px rgba(160, 160, 160, 0.5);
            transform: translateY(-1px);
        }
        
        .webgsm-tier-badge.tier-silver svg {
            stroke: #505050;
        }
        
        /* GOLD - Auriu Luxury */
        .webgsm-tier-badge.tier-gold {
            background: linear-gradient(135deg, #f7e199 0%, #d4af37 50%, #c5a028 100%);
            color: #5c4813;
            border: 1px solid #dbb840;
            box-shadow: 0 2px 8px rgba(212, 175, 55, 0.35);
        }
        
        .webgsm-tier-badge.tier-gold:hover {
            box-shadow: 0 4px 16px rgba(212, 175, 55, 0.5);
            transform: translateY(-1px);
        }
        
        .webgsm-tier-badge.tier-gold svg {
            stroke: #6b5518;
        }
        
        /* PLATINUM - Exclusivist Deep Blue/Perlat */
        .webgsm-tier-badge.tier-platinum {
            background: linear-gradient(135deg, #2c3e50 0%, #1a252f 50%, #0d1318 100%);
            color: #e5e5e5;
            border: 1px solid #4a6073;
            box-shadow: 0 2px 8px rgba(44, 62, 80, 0.4);
        }
        
        .webgsm-tier-badge.tier-platinum:hover {
            box-shadow: 0 4px 16px rgba(44, 62, 80, 0.6);
            transform: translateY(-1px);
        }
        
        .webgsm-tier-badge.tier-platinum svg {
            stroke: #bdc3c7;
        }
        
        /* Badge în header - mai mic */
        .webgsm-tier-badge.badge-header {
            padding: 2px 8px;
            font-size: 9px;
            border-radius: 12px;
        }
        
        .webgsm-tier-badge.badge-header svg {
            width: 10px;
            height: 10px;
        }
        
        /* Badge în dashboard - mai mare */
        .webgsm-tier-badge.badge-dashboard {
            padding: 6px 16px;
            font-size: 13px;
            border-radius: 25px;
        }
        
        .webgsm-tier-badge.badge-dashboard svg {
            width: 18px;
            height: 18px;
        }
        
        /* ========================================
           Progress Bar - Elegant Design
           ======================================== */
        
        .webgsm-tier-progress-wrapper {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        
        .webgsm-tier-progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        
        .webgsm-tier-progress-header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .webgsm-tier-progress-bar-container {
            background: #f3f4f6;
            border-radius: 10px;
            height: 12px;
            overflow: hidden;
            position: relative;
        }
        
        .webgsm-tier-progress-bar {
            height: 100%;
            border-radius: 10px;
            transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .webgsm-tier-progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        /* Progress bar colors by tier target */
        .webgsm-tier-progress-bar.to-silver {
            background: linear-gradient(90deg, #d4a574, #c0c0c0);
        }
        
        .webgsm-tier-progress-bar.to-gold {
            background: linear-gradient(90deg, #c0c0c0, #d4af37);
        }
        
        .webgsm-tier-progress-bar.to-platinum {
            background: linear-gradient(90deg, #d4af37, #2c3e50);
        }
        
        .webgsm-tier-progress-bar.max-tier {
            background: linear-gradient(90deg, #2c3e50, #1a252f);
        }
        
        .webgsm-tier-progress-info {
            display: flex;
            justify-content: space-between;
            margin-top: 12px;
            font-size: 13px;
            color: #6b7280;
        }
        
        .webgsm-tier-progress-info .current-value {
            font-weight: 600;
            color: #3b82f6;
        }
        
        .webgsm-tier-progress-info .next-tier {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .webgsm-tier-benefits {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }
        
        .webgsm-tier-benefits h4 {
            margin: 0 0 10px 0;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }
        
        .webgsm-tier-benefits ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .webgsm-tier-benefits li {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 20px;
            font-size: 12px;
            color: #166534;
        }
        
        .webgsm-tier-benefits li svg {
            width: 12px;
            height: 12px;
            stroke: #22c55e;
        }
        
        /* ========================================
           Upgrade Notification - Pop-up
           ======================================== */
        
        .webgsm-tier-upgrade-popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .webgsm-tier-upgrade-popup.active {
            opacity: 1;
            visibility: visible;
        }
        
        .webgsm-tier-upgrade-content {
            background: #fff;
            border-radius: 16px;
            padding: 40px;
            max-width: 420px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }
        
        .webgsm-tier-upgrade-popup.active .webgsm-tier-upgrade-content {
            transform: scale(1);
        }
        
        .webgsm-tier-upgrade-content .celebration-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: celebrate 0.6s ease;
        }
        
        @keyframes celebrate {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .webgsm-tier-upgrade-content .celebration-icon svg {
            width: 40px;
            height: 40px;
            stroke: #d97706;
        }
        
        .webgsm-tier-upgrade-content h2 {
            margin: 0 0 10px;
            font-size: 24px;
            color: #1f2937;
        }
        
        .webgsm-tier-upgrade-content p {
            margin: 0 0 20px;
            color: #6b7280;
            line-height: 1.6;
        }
        
        .webgsm-tier-upgrade-content .new-badge {
            margin: 20px 0;
        }
        
        .webgsm-tier-upgrade-content .close-btn {
            background: #3b82f6;
            color: #fff;
            border: none;
            padding: 12px 32px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .webgsm-tier-upgrade-content .close-btn:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }
        </style>
        <?php
    }
}

// Initialize
WebGSM_Customer_Tiers::instance();

// =========================================
// HELPER FUNCTIONS
// =========================================

function webgsm_get_tier_badge($tier, $size = 'default') {
    $tiers_config = array(
        'bronze' => array(
            'label' => 'Bronze',
            'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>'
        ),
        'silver' => array(
            'label' => 'Silver',
            'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>'
        ),
        'gold' => array(
            'label' => 'Gold',
            'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/></svg>'
        ),
        'platinum' => array(
            'label' => 'Platinum',
            'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l2.5 5.5L20 9.5l-4 4.5 1 6-5-3-5 3 1-6-4-4.5 5.5-1L12 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 0l-2 2m2-2l2 2"/></svg>'
        )
    );
    
    $tier = strtolower($tier);
    if (!isset($tiers_config[$tier])) {
        $tier = 'bronze';
    }
    
    $config = $tiers_config[$tier];
    $size_class = ($size === 'header') ? 'badge-header' : (($size === 'dashboard') ? 'badge-dashboard' : '');
    
    return sprintf(
        '<span class="webgsm-tier-badge tier-%s %s">%s %s</span>',
        esc_attr($tier),
        esc_attr($size_class),
        $config['icon'],
        esc_html($config['label'])
    );
}

// Upgrade notification
add_action('wp_footer', 'webgsm_show_tier_upgrade_popup');
function webgsm_show_tier_upgrade_popup() {
    if (!is_user_logged_in()) return;
    
    $user_id = get_current_user_id();
    $show_popup = get_user_meta($user_id, '_webgsm_show_tier_upgrade', true);
    $new_tier = get_user_meta($user_id, '_webgsm_new_tier', true);
    
    if ($show_popup !== 'yes' || empty($new_tier)) return;
    
    // Marchează ca văzut
    delete_user_meta($user_id, '_webgsm_show_tier_upgrade');
    delete_user_meta($user_id, '_webgsm_new_tier');
    ?>
    <div class="webgsm-tier-upgrade-popup active" id="webgsm-upgrade-popup">
        <div class="webgsm-tier-upgrade-content">
            <div class="celebration-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                </svg>
            </div>
            <h2>Felicitări! 🎉</h2>
            <p>Ai fost promovat la un nivel superior de parteneriat!</p>
            <div class="new-badge">
                <?php echo webgsm_get_tier_badge($new_tier, 'dashboard'); ?>
            </div>
            <p>Beneficiile tale au fost actualizate automat.</p>
            <button class="close-btn" onclick="document.getElementById('webgsm-upgrade-popup').classList.remove('active');">
                Mulțumesc!
            </button>
        </div>
    </div>
    <script>
    // Auto-close după 10 secunde
    setTimeout(function() {
        var popup = document.getElementById('webgsm-upgrade-popup');
        if (popup) popup.classList.remove('active');
    }, 10000);
    </script>
    <?php
}
