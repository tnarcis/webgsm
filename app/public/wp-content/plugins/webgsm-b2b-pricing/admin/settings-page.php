<?php
if (!defined('ABSPATH')) exit;

$tiers = get_option('webgsm_b2b_tiers', WebGSM_B2B_Pricing::instance()->get_default_tiers());
$discount_implicit = get_option('webgsm_b2b_discount_implicit', 5);
$marja_minima = get_option('webgsm_b2b_marja_minima', 5);
$show_badge = get_option('webgsm_b2b_show_badge', 'yes');
$badge_text = get_option('webgsm_b2b_badge_text', 'Preț B2B');

// Salvare setări
if (isset($_POST['webgsm_b2b_save_settings']) && wp_verify_nonce($_POST['webgsm_b2b_nonce'], 'webgsm_b2b_save_settings')) {
    
    update_option('webgsm_b2b_discount_implicit', sanitize_text_field($_POST['discount_implicit']));
    update_option('webgsm_b2b_marja_minima', sanitize_text_field($_POST['marja_minima']));
    update_option('webgsm_b2b_show_badge', isset($_POST['show_badge']) ? 'yes' : 'no');
    update_option('webgsm_b2b_badge_text', sanitize_text_field($_POST['badge_text']));
    
    // Tiers
    $new_tiers = array();
    foreach ($_POST['tier_name'] as $key => $name) {
        $slug = sanitize_title($name);
        $new_tiers[$slug] = array(
            'label' => sanitize_text_field($name),
            'min_orders' => intval($_POST['tier_min_orders'][$key]),
            'discount_extra' => floatval($_POST['tier_discount'][$key])
        );
    }
    update_option('webgsm_b2b_tiers', $new_tiers);
    
    // Refresh variables
    $tiers = $new_tiers;
    $discount_implicit = get_option('webgsm_b2b_discount_implicit', 5);
    $marja_minima = get_option('webgsm_b2b_marja_minima', 5);
    $show_badge = get_option('webgsm_b2b_show_badge', 'yes');
    $badge_text = get_option('webgsm_b2b_badge_text', 'Preț B2B');
    
    echo '<div class="notice notice-success"><p>Setările au fost salvate!</p></div>';
}
?>

<div class="wrap webgsm-b2b-admin">
    <h1>
        <span class="dashicons dashicons-chart-line" style="margin-right: 10px;"></span>
        WebGSM B2B Pricing - Setări
    </h1>
    
    <form method="post" action="">
        <?php wp_nonce_field('webgsm_b2b_save_settings', 'webgsm_b2b_nonce'); ?>
        
        <div class="webgsm-b2b-cards">
            
            <!-- SETĂRI GENERALE -->
            <div class="webgsm-b2b-card">
                <div class="card-header">
                    <h2><span class="dashicons dashicons-admin-settings"></span> Setări Generale</h2>
                </div>
                <div class="card-body">
                    <table class="form-table">
                        <tr>
                            <th><label for="discount_implicit">Discount implicit PJ (%)</label></th>
                            <td>
                                <input type="number" name="discount_implicit" id="discount_implicit" 
                                       value="<?php echo esc_attr($discount_implicit); ?>" 
                                       step="0.1" min="0" max="100" class="small-text">
                                <p class="description">Discount aplicat dacă produsul/categoria nu are discount specific setat.</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="marja_minima">Marjă minimă profit (%)</label></th>
                            <td>
                                <input type="number" name="marja_minima" id="marja_minima" 
                                       value="<?php echo esc_attr($marja_minima); ?>" 
                                       step="0.1" min="0" max="100" class="small-text">
                                <p class="description">Marja minimă adăugată la prețul de achiziție pentru calculul automat al prețului minim de vânzare.</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="show_badge">Afișează badge "Preț B2B"</label></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="show_badge" id="show_badge" value="yes" 
                                           <?php checked($show_badge, 'yes'); ?>>
                                    Afișează un badge lângă preț pentru utilizatorii PJ
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="badge_text">Text badge</label></th>
                            <td>
                                <input type="text" name="badge_text" id="badge_text" 
                                       value="<?php echo esc_attr($badge_text); ?>" class="regular-text">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- SISTEM TIERS -->
            <div class="webgsm-b2b-card">
                <div class="card-header">
                    <h2><span class="dashicons dashicons-awards"></span> Sistem Tiers (Niveluri Clienți)</h2>
                </div>
                <div class="card-body">
                    <p class="description" style="margin-bottom: 15px;">
                        Clienții avansează automat în tier-ul următor în funcție de numărul de comenzi finalizate.
                        Discount-ul extra din tier se adaugă la discount-ul din produs/categorie.
                    </p>
                    
                    <table class="widefat" id="tiers-table">
                        <thead>
                            <tr>
                                <th>Nume Tier</th>
                                <th>Min. Comenzi</th>
                                <th>Discount Extra (%)</th>
                                <th style="width: 50px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tiers as $slug => $tier): ?>
                            <tr>
                                <td><input type="text" name="tier_name[]" value="<?php echo esc_attr($tier['label']); ?>" class="regular-text"></td>
                                <td><input type="number" name="tier_min_orders[]" value="<?php echo esc_attr($tier['min_orders']); ?>" min="0" class="small-text"></td>
                                <td><input type="number" name="tier_discount[]" value="<?php echo esc_attr($tier['discount_extra']); ?>" step="0.1" min="0" max="100" class="small-text"></td>
                                <td><button type="button" class="button remove-tier" title="Șterge">&times;</button></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4">
                                    <button type="button" class="button" id="add-tier">+ Adaugă Tier</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <!-- INFO BOX -->
            <div class="webgsm-b2b-card info-card">
                <div class="card-header">
                    <h2><span class="dashicons dashicons-info"></span> Cum funcționează?</h2>
                </div>
                <div class="card-body">
                    <div class="info-flow">
                        <div class="flow-step">
                            <span class="step-number">1</span>
                            <span class="step-text">Preț retail (PF)</span>
                        </div>
                        <div class="flow-arrow">→</div>
                        <div class="flow-step">
                            <span class="step-number">2</span>
                            <span class="step-text">- Discount PJ (produs/categorie/implicit)</span>
                        </div>
                        <div class="flow-arrow">→</div>
                        <div class="flow-step">
                            <span class="step-number">3</span>
                            <span class="step-text">- Discount Tier</span>
                        </div>
                        <div class="flow-arrow">→</div>
                        <div class="flow-step">
                            <span class="step-number">4</span>
                            <span class="step-text">Verificare preț minim (HARD LIMIT)</span>
                        </div>
                        <div class="flow-arrow">→</div>
                        <div class="flow-step final">
                            <span class="step-number">✓</span>
                            <span class="step-text">Preț Final B2B</span>
                        </div>
                    </div>
                    
                    <div class="info-example" style="margin-top: 20px; padding: 15px; background: #f8fafc; border-radius: 8px;">
                        <h4 style="margin-top: 0;">Exemplu calcul:</h4>
                        <code style="display: block; white-space: pre-line;">
Preț retail: 1.000 lei
Discount PJ categorie: 10%
Tier client (Gold): +5% extra
────────────────────
Preț calculat: 1.000 - 15% = 850 lei
Preț minim setat: 800 lei
────────────────────
Preț final: 850 lei ✓ (peste minim)
                        </code>
                    </div>
                </div>
            </div>
            
        </div>
        
        <p class="submit">
            <button type="submit" name="webgsm_b2b_save_settings" class="button button-primary button-large">
                Salvează Setările
            </button>
        </p>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    // Adaugă tier
    $('#add-tier').on('click', function() {
        var newRow = '<tr>' +
            '<td><input type="text" name="tier_name[]" value="" class="regular-text" placeholder="Nume tier"></td>' +
            '<td><input type="number" name="tier_min_orders[]" value="0" min="0" class="small-text"></td>' +
            '<td><input type="number" name="tier_discount[]" value="0" step="0.1" min="0" max="100" class="small-text"></td>' +
            '<td><button type="button" class="button remove-tier" title="Șterge">&times;</button></td>' +
            '</tr>';
        $('#tiers-table tbody').append(newRow);
    });
    
    // Șterge tier
    $(document).on('click', '.remove-tier', function() {
        if ($('#tiers-table tbody tr').length > 1) {
            $(this).closest('tr').remove();
        } else {
            alert('Trebuie să existe cel puțin un tier.');
        }
    });
});
</script>
