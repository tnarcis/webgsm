<?php
if (!defined('ABSPATH')) exit;

// Obține clienții B2B
$b2b_users = get_users(array(
    'meta_query' => array(
        'relation' => 'OR',
        array('key' => '_is_pj', 'value' => 'yes'),
        array('key' => '_is_pj', 'value' => '1'),
        array('key' => 'billing_cui', 'compare' => 'EXISTS')
    )
));
?>

<div class="wrap webgsm-b2b-admin">
    <h1><span class="dashicons dashicons-groups"></span> Clienți B2B</h1>
    
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Client</th>
                <th>Email</th>
                <th>CUI</th>
                <th>Tier</th>
                <th>Comenzi</th>
                <th>Valoare Totală</th>
                <th>Acțiuni</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($b2b_users)): ?>
                <tr><td colspan="7">Nu există clienți B2B înregistrați.</td></tr>
            <?php else: ?>
                <?php foreach ($b2b_users as $user): 
                    $b2b = WebGSM_B2B_Pricing::instance();
                    $cui = get_user_meta($user->ID, 'billing_cui', true);
                    $tier = $b2b->get_user_tier($user->ID);
                    $orders = $b2b->get_user_total_orders($user->ID);
                    $value = $b2b->get_user_total_value($user->ID);
                    $tiers = get_option('webgsm_b2b_tiers', $b2b->get_default_tiers());
                    $tier_label = isset($tiers[$tier]['label']) ? $tiers[$tier]['label'] : ucfirst($tier);
                ?>
                <tr>
                    <td>
                        <strong><?php echo esc_html($user->display_name); ?></strong>
                        <br><small><?php echo esc_html(get_user_meta($user->ID, 'billing_company', true)); ?></small>
                    </td>
                    <td><?php echo esc_html($user->user_email); ?></td>
                    <td><?php echo esc_html($cui ?: '-'); ?></td>
                    <td>
                        <span class="tier-badge tier-<?php echo esc_attr($tier); ?>">
                            <?php echo esc_html($tier_label); ?>
                        </span>
                    </td>
                    <td><?php echo intval($orders); ?></td>
                    <td><?php echo wc_price($value); ?></td>
                    <td>
                        <a href="<?php echo admin_url('user-edit.php?user_id=' . $user->ID); ?>" class="button button-small">Editează</a>
                        <a href="<?php echo admin_url('edit.php?post_type=shop_order&_customer_user=' . $user->ID); ?>" class="button button-small">Comenzi</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
