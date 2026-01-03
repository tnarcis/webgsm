<?php
/**
 * MODUL ÎNREGISTRARE ÎMBUNĂTĂȚITĂ
 * - Confirmare email obligatorie
 * - Alegere PF/PJ la înregistrare
 * - Câmpuri suplimentare (telefon, etc.)
 */

// =============================================
// CÂMPURI SUPLIMENTARE LA ÎNREGISTRARE
// =============================================

// Adaugă câmpuri noi în formularul de înregistrare
add_action('woocommerce_register_form_start', function() {
    ?>
    <p class="form-row form-row-first">
        <label for="reg_billing_first_name"><?php esc_html_e('Prenume', 'flavor'); ?> <span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php echo isset($_POST['billing_first_name']) ? esc_attr($_POST['billing_first_name']) : ''; ?>" required />
    </p>
    
    <p class="form-row form-row-last">
        <label for="reg_billing_last_name"><?php esc_html_e('Nume', 'flavor'); ?> <span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php echo isset($_POST['billing_last_name']) ? esc_attr($_POST['billing_last_name']) : ''; ?>" required />
    </p>
    
    <p class="form-row form-row-wide">
        <label for="reg_billing_phone"><?php esc_html_e('Telefon', 'flavor'); ?> <span class="required">*</span></label>
        <input type="tel" class="input-text" name="billing_phone" id="reg_billing_phone" value="<?php echo isset($_POST['billing_phone']) ? esc_attr($_POST['billing_phone']) : ''; ?>" required />
    </p>
    
    <div class="clear"></div>
    
    <div style="background:#f8f9fa; padding:20px; border-radius:10px; margin:20px 0;">
        <p style="margin:0 0 15px 0; font-weight:600;">Tip cont:</p>
        <p class="form-row form-row-wide" style="margin-bottom:10px;">
            <label style="display:inline-flex; align-items:center; margin-right:25px; cursor:pointer;">
                <input type="radio" name="tip_facturare" value="pf" checked style="margin-right:8px;"> 
                <span>👤 Persoană Fizică</span>
            </label>
            <label style="display:inline-flex; align-items:center; cursor:pointer;">
                <input type="radio" name="tip_facturare" value="pj" style="margin-right:8px;"> 
                <span>🏢 Persoană Juridică</span>
            </label>
        </p>
    </div>
    
    <div id="campuri-firma-register" style="display:none; background:#fff3cd; padding:20px; border-radius:10px; margin-bottom:20px;">
        <h4 style="margin:0 0 15px 0;">🏢 Date Firmă</h4>
        
        <p class="form-row form-row-wide" style="display:flex; gap:10px; align-items:flex-end;">
            <span style="flex:1;">
                <label for="reg_firma_cui">CUI / CIF <span class="required">*</span></label>
                <input type="text" class="input-text" name="firma_cui" id="reg_firma_cui" placeholder="ex: RO12345678">
            </span>
            <button type="button" id="btn_cauta_cui_register" class="button" style="width:auto; padding:10px 16px; font-size:13px; margin-top:8px;">🔍 Autocompletare ANAF</button>
        </p>
        
        <div id="anaf_result_register" style="display:none; padding:10px; border-radius:5px; margin-bottom:15px;"></div>
        
        <p class="form-row form-row-wide">
            <label for="reg_firma_nume">Denumire Firmă <span class="required">*</span></label>
            <input type="text" class="input-text" name="firma_nume" id="reg_firma_nume">
        </p>
        
        <p class="form-row form-row-wide">
            <label for="reg_firma_reg_com">Nr. Reg. Comerțului</label>
            <input type="text" class="input-text" name="firma_reg_com" id="reg_firma_reg_com" placeholder="ex: J40/1234/2020">
        </p>
        
        <p class="form-row form-row-wide">
            <label for="reg_firma_adresa">Adresa Firmă</label>
            <input type="text" class="input-text" name="firma_adresa" id="reg_firma_adresa">
        </p>
        
        <p class="form-row form-row-first">
            <label for="reg_firma_judet">Județ</label>
            <input type="text" class="input-text" name="firma_judet" id="reg_firma_judet">
        </p>
        
        <p class="form-row form-row-last">
            <label for="reg_firma_oras">Localitate</label>
            <input type="text" class="input-text" name="firma_oras" id="reg_firma_oras">
        </p>
        
        <div class="clear"></div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        $('input[name="tip_facturare"]').on('change', function() {
            if($(this).val() === 'pj') {
                $('#campuri-firma-register').slideDown();
            } else {
                $('#campuri-firma-register').slideUp();
            }
        });
        
        // Căutare ANAF la înregistrare
        $('#btn_cauta_cui_register').on('click', function() {
            var cui = $('#reg_firma_cui').val().trim().replace(/^RO/i, '');
            
            if(!cui || cui.length < 2) {
                $('#anaf_result_register').html('<span style="color:red;">Introdu un CUI valid.</span>').show();
                return;
            }
            
            $('#anaf_result_register').html('<span style="color:#666;">⏳ Se caută...</span>').show();
            
            $.ajax({
                url: '<?php echo admin_url("admin-ajax.php"); ?>',
                type: 'POST',
                data: { action: 'cauta_cui_anaf', cui: cui },
                success: function(response) {
                    if(response.success && response.data) {
                        var d = response.data;
                        $('#reg_firma_nume').val(d.denumire || '');
                        $('#reg_firma_reg_com').val(d.nrRegCom || '');
                        $('#reg_firma_adresa').val(d.adresa || '');
                        $('#reg_firma_judet').val(d.judet || '');
                        $('#reg_firma_oras').val(d.localitate || '');
                        $('#reg_firma_cui').val(d.tva ? 'RO' + cui : cui);
                        
                        var tva = d.tva ? '✓ Plătitor TVA' : '✗ Neplătitor TVA';
                        $('#anaf_result_register').html('<span style="color:green;">✓ ' + d.denumire + ' - ' + tva + '</span>').css('background', '#d4edda').show();
                    } else {
                        $('#anaf_result_register').html('<span style="color:red;">CUI negăsit. Completează manual.</span>').css('background', '#f8d7da').show();
                    }
                }
            });
        });
    });
    </script>
    <?php
});

// Validare câmpuri la înregistrare
add_filter('woocommerce_registration_errors', function($errors, $username, $email) {
    if(empty($_POST['billing_first_name'])) {
        $errors->add('billing_first_name_error', 'Te rugăm să completezi prenumele.');
    }
    
    if(empty($_POST['billing_last_name'])) {
        $errors->add('billing_last_name_error', 'Te rugăm să completezi numele.');
    }
    
    if(empty($_POST['billing_phone'])) {
        $errors->add('billing_phone_error', 'Te rugăm să completezi telefonul.');
    }
    
    // Validare câmpuri firmă dacă e PJ
    if(isset($_POST['tip_facturare']) && $_POST['tip_facturare'] === 'pj') {
        if(empty($_POST['firma_cui'])) {
            $errors->add('firma_cui_error', 'Te rugăm să completezi CUI-ul firmei.');
        }
        if(empty($_POST['firma_nume'])) {
            $errors->add('firma_nume_error', 'Te rugăm să completezi denumirea firmei.');
        }
    }
    
    return $errors;
}, 10, 3);

// Salvează datele suplimentare la înregistrare
add_action('woocommerce_created_customer', function($customer_id) {
    if(isset($_POST['billing_first_name'])) {
        update_user_meta($customer_id, 'billing_first_name', sanitize_text_field($_POST['billing_first_name']));
        update_user_meta($customer_id, 'first_name', sanitize_text_field($_POST['billing_first_name']));
    }
    
    if(isset($_POST['billing_last_name'])) {
        update_user_meta($customer_id, 'billing_last_name', sanitize_text_field($_POST['billing_last_name']));
        update_user_meta($customer_id, 'last_name', sanitize_text_field($_POST['billing_last_name']));
    }
    
    if(isset($_POST['billing_phone'])) {
        update_user_meta($customer_id, 'billing_phone', sanitize_text_field($_POST['billing_phone']));
    }
    
    // Salvează tipul de facturare
    $tip_facturare = isset($_POST['tip_facturare']) ? sanitize_text_field($_POST['tip_facturare']) : 'pf';
    update_user_meta($customer_id, '_tip_facturare', $tip_facturare);
    
    // Salvează datele firmei dacă e PJ
    if($tip_facturare === 'pj') {
        update_user_meta($customer_id, '_firma_cui', sanitize_text_field($_POST['firma_cui'] ?? ''));
        update_user_meta($customer_id, '_firma_nume', sanitize_text_field($_POST['firma_nume'] ?? ''));
        update_user_meta($customer_id, '_firma_reg_com', sanitize_text_field($_POST['firma_reg_com'] ?? ''));
        update_user_meta($customer_id, '_firma_adresa', sanitize_text_field($_POST['firma_adresa'] ?? ''));
        update_user_meta($customer_id, '_firma_judet', sanitize_text_field($_POST['firma_judet'] ?? ''));
        update_user_meta($customer_id, '_firma_oras', sanitize_text_field($_POST['firma_oras'] ?? ''));
    }
    
    // Marchează contul ca neconfirmat
    update_user_meta($customer_id, '_email_confirmed', 0);
    update_user_meta($customer_id, '_confirmation_token', wp_generate_password(32, false));
    
    // Trimite email de confirmare
    envoi_email_confirmare($customer_id);
});

// =============================================
// CONFIRMARE EMAIL
// =============================================

function envoi_email_confirmare($customer_id) {
    $user = get_user_by('ID', $customer_id);
    if(!$user) return;
    
    $token = get_user_meta($customer_id, '_confirmation_token', true);
    $confirm_url = add_query_arg(array(
        'confirm_email' => '1',
        'user_id' => $customer_id,
        'token' => $token
    ), wc_get_page_permalink('myaccount'));
    
    $subject = 'Confirmă adresa de email - ' . get_bloginfo('name');
    
    $message = '
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
        <h2 style="color: #333;">Bine ai venit!</h2>
        <p>Mulțumim pentru înregistrare. Te rugăm să confirmi adresa de email făcând click pe butonul de mai jos:</p>
        <p style="text-align: center; margin: 30px 0;">
            <a href="' . esc_url($confirm_url) . '" style="background: #4CAF50; color: #fff; padding: 15px 30px; text-decoration: none; border-radius: 8px; display: inline-block; font-weight: bold;">
                ✓ Confirmă Email
            </a>
        </p>
        <p style="color: #666; font-size: 13px;">Sau copiază acest link în browser:<br>' . esc_url($confirm_url) . '</p>
        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
        <p style="color: #999; font-size: 12px;">Acest email a fost trimis de ' . get_bloginfo('name') . '</p>
    </div>';
    
    $headers = array('Content-Type: text/html; charset=UTF-8');
    
    wp_mail($user->user_email, $subject, $message, $headers);
}

// Procesează confirmarea email
add_action('init', function() {
    if(isset($_GET['confirm_email']) && isset($_GET['user_id']) && isset($_GET['token'])) {
        $user_id = intval($_GET['user_id']);
        $token = sanitize_text_field($_GET['token']);
        
        $saved_token = get_user_meta($user_id, '_confirmation_token', true);
        
        if($saved_token && $saved_token === $token) {
            update_user_meta($user_id, '_email_confirmed', 1);
            delete_user_meta($user_id, '_confirmation_token');
            
            // Setează mesaj de succes
            wc_add_notice('Email confirmat cu succes! Acum te poți autentifica.', 'success');
            
            wp_redirect(wc_get_page_permalink('myaccount'));
            exit;
        } else {
            wc_add_notice('Link de confirmare invalid sau expirat.', 'error');
            wp_redirect(wc_get_page_permalink('myaccount'));
            exit;
        }
    }
});

// Blochează autentificarea dacă email-ul nu e confirmat
add_filter('wp_authenticate_user', function($user, $password) {
    if(is_wp_error($user)) {
        return $user;
    }
    
    // Verifică dacă e admin - adminii nu au nevoie de confirmare
    if(user_can($user, 'manage_options')) {
        return $user;
    }
    
    $email_confirmed = get_user_meta($user->ID, '_email_confirmed', true);
    
    // Dacă nu există meta (cont vechi), consideră confirmat
    if($email_confirmed === '') {
        return $user;
    }
    
    if($email_confirmed != 1) {
        return new WP_Error(
            'email_not_confirmed',
            '<strong>Email neconfirmat!</strong> Te rugăm să verifici inbox-ul și să confirmi adresa de email. <a href="#" class="resend-confirmation" data-user="' . $user->ID . '">Retrimite email de confirmare</a>'
        );
    }
    
    return $user;
}, 10, 2);

// AJAX pentru retrimitere email confirmare
add_action('wp_ajax_nopriv_resend_confirmation', function() {
    $user_id = intval($_POST['user_id']);
    
    if($user_id) {
        // Generează token nou
        update_user_meta($user_id, '_confirmation_token', wp_generate_password(32, false));
        envoi_email_confirmare($user_id);
        wp_send_json_success('Email retrimis!');
    }
    
    wp_send_json_error('Eroare');
});

// Script pentru retrimitere
add_action('wp_footer', function() {
    if(!is_account_page()) return;
    ?>
    <script>
    jQuery(document).ready(function($) {
        $(document).on('click', '.resend-confirmation', function(e) {
            e.preventDefault();
            var userId = $(this).data('user');
            
            $.ajax({
                url: '<?php echo admin_url("admin-ajax.php"); ?>',
                type: 'POST',
                data: { action: 'resend_confirmation', user_id: userId },
                success: function(response) {
                    if(response.success) {
                        alert('Email de confirmare retrimis! Verifică inbox-ul.');
                    }
                }
            });
        });
    });
    </script>
    <?php
});

// =============================================
// AFIȘARE STATUS CONFIRMARE ÎN ADMIN
// =============================================

// Coloană în lista de useri
add_filter('manage_users_columns', function($columns) {
    $columns['email_confirmed'] = 'Email Confirmat';
    return $columns;
});

add_action('manage_users_custom_column', function($value, $column_name, $user_id) {
    if($column_name === 'email_confirmed') {
        $confirmed = get_user_meta($user_id, '_email_confirmed', true);
        
        if($confirmed === '' || $confirmed == 1) {
            return '<span style="color:green;">✓ Da</span>';
        } else {
            return '<span style="color:red;">✗ Nu</span> <a href="#" class="confirm-user-email" data-user="' . $user_id . '" style="font-size:11px;">Confirmă manual</a>';
        }
    }
    return $value;
}, 10, 3);

// AJAX confirmare manuală din admin
add_action('wp_ajax_admin_confirm_email', function() {
    if(!current_user_can('edit_users')) {
        wp_send_json_error('Neautorizat');
    }
    
    $user_id = intval($_POST['user_id']);
    update_user_meta($user_id, '_email_confirmed', 1);
    delete_user_meta($user_id, '_confirmation_token');
    
    wp_send_json_success('Confirmat');
});

add_action('admin_footer', function() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        $(document).on('click', '.confirm-user-email', function(e) {
            e.preventDefault();
            var btn = $(this);
            var userId = btn.data('user');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: { action: 'admin_confirm_email', user_id: userId },
                success: function(response) {
                    if(response.success) {
                        btn.closest('td').html('<span style="color:green;">✓ Da</span>');
                    }
                }
            });
        });
    });
    </script>
    <?php
});


// =============================================
// STYLING FORMULAR ÎNREGISTRARE
// =============================================

add_action('wp_head', function() {
    if(!is_account_page()) return;
    ?>
    <style>
    /* Container formular */
    .woocommerce-form-register {
        max-width: 450px !important;
    }
    
    /* Toate form-row pe full width */
    .woocommerce-form-register .form-row,
    .woocommerce-form-register .form-row-first,
    .woocommerce-form-register .form-row-last,
    .woocommerce-form-register .form-row-wide {
        width: 100% !important;
        display: block !important;
        float: none !important;
        margin-bottom: 18px !important;
        margin-right: 0 !important;
    }
    
    /* Labels */
    .woocommerce-form-register label {
        display: block !important;
        margin-bottom: 8px !important;
        font-weight: 500 !important;
        color: #444 !important;
        font-size: 14px !important;
    }
    
    /* Toate input-urile */
    .woocommerce-form-register input[type="text"],
    .woocommerce-form-register input[type="email"],
    .woocommerce-form-register input[type="tel"],
    .woocommerce-form-register input[type="password"],
    .woocommerce-form-register input.input-text,
    .woocommerce-form-register .input-text {
        width: 100% !important;
        padding: 14px 16px !important;
        border: 2px solid #ccc !important;
        border-radius: 8px !important;
        font-size: 15px !important;
        background: #fff !important;
        box-sizing: border-box !important;
        display: block !important;
    }
    
    .woocommerce-form-register input:focus,
    .woocommerce-form-register .input-text:focus {
        border-color: #666 !important;
        outline: none !important;
    }
    
    /* Câmpuri firmă */
    #campuri-firma-register {
        margin-top: 10px !important;
    }
    
    #campuri-firma-register p {
        margin-bottom: 18px !important;
    }
    
    #campuri-firma-register input {
        width: 100% !important;
        padding: 14px 16px !important;
        border: 2px solid #ccc !important;
        border-radius: 8px !important;
        font-size: 15px !important;
        background: #fff !important;
        box-sizing: border-box !important;
    }
    
    #campuri-firma-register input:focus {
        border-color: #666 !important;
    }
    
    #campuri-firma-register label {
        display: block !important;
        margin-bottom: 8px !important;
        font-weight: 500 !important;
        color: #444 !important;
    }
    
   /* Buton căutare ANAF */
#btn_cauta_cui_register {
    width: auto !important;
    padding: 8px 14px !important;
    font-size: 12px !important;
    margin-top: 8px !important;
    border-radius: 6px !important;
    cursor: pointer !important;
    background: #555 !important;
    color: #fff !important;
    border: none !important;
    display: block !important;
    margin-left: auto !important;
    margin-right: auto !important;
    text-align: center !important;
    line-height: 1 !important;
}
#btn_cauta_cui_register:hover {
    background: #333 !important;
}

    
    /* Clear floats */
    .woocommerce-form-register .clear {
        clear: both !important;
        display: block !important;
    }
    
    /* Box tip facturare */
    .woocommerce-form-register > div[style*="background:#f8f9fa"] {
        margin: 25px 0 !important;
    }
    </style>
    <?php
});