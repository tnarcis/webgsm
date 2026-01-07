<?php

/**
 * WebGSM My Account - Meniu restructurat (fără a strica endpoint-uri existente)
 * @version 1.1
 */
if (!defined('ABSPATH')) exit;

// ==========================================
// MODIFICĂ MENIUL (nu îl înlocuiește complet)
// ==========================================
add_filter('woocommerce_account_menu_items', function($items) {
    
    // Redenumește items existente
    if (isset($items['dashboard'])) $items['dashboard'] = 'Panou control';
    if (isset($items['orders'])) $items['orders'] = 'Comenzi';
    if (isset($items['downloads'])) $items['downloads'] = 'Descărcări';
    if (isset($items['edit-account'])) $items['edit-account'] = 'Detalii cont';
    if (isset($items['customer-logout'])) $items['customer-logout'] = 'Ieșire din cont';
    
    // Reordonează
    $order = [
        'dashboard',
        'orders',
        'retururi',
        'garantie',
        'downloads',
        'edit-address',
        'adrese-salvate',
        'date-facturare',
        'edit-account',
        'customer-logout'
    ];
    
    $sorted = [];
    foreach ($order as $key) {
        if (isset($items[$key])) {
            $sorted[$key] = $items[$key];
        }
    }
    
    // Adaugă items rămase
    foreach ($items as $key => $val) {
        if (!isset($sorted[$key])) {
            $sorted[$key] = $val;
        }
    }
    
    return $sorted;
}, 99);


// ==========================================
// Endpoint: adrese-salvate - fără secțiune suplimentară jos
// ==========================================
// (Secțiunea de jos a fost eliminată la cererea utilizatorului)


// ===============================
// AJAX Stergere Adresa Salvata
// ===============================
// Încarc nonce-ul cu wp_localize_script pe pagina My Account
add_action('wp_enqueue_scripts', function() {
    if (!is_account_page()) return;
    wp_enqueue_script('jquery');
    $nonce = wp_create_nonce('webgsm_nonce');
    wp_localize_script('jquery', 'webgsm_myaccount', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => $nonce
    ]);
}, 10);

// JavaScript pentru butonul de ștergere - handler complet
add_action('wp_footer', function() {
    if (!is_account_page()) return;
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        console.log('[WebGSM] Delete handlers initialized');
        
        // Adaugă butoane "Adaugă" deasupra fiecărei secțiuni
        function addActionButtons() {
            console.log('[WebGSM] Adding action buttons...');
            
            // Buton pentru Adrese livrare
            var addressesSection = $('.webgsm-addresses-list, table:contains("Adrese")').first();
            console.log('[WebGSM] Addresses section found:', addressesSection.length);
            if (addressesSection.length && !$('#btn-add-address').length) {
                var addressBtn = '<button type="button" id="btn-add-address" class="btn-add-item" style="margin-bottom:15px;">📍 Adaugă adresă livrare</button>';
                addressesSection.before(addressBtn);
                console.log('[WebGSM] Address button added');
            }
            
            // Buton pentru Firme
            var companiesSection = $('.companies-list, table:contains("Firme")').first();
            console.log('[WebGSM] Companies section found:', companiesSection.length);
            if (companiesSection.length && !$('#btn-add-company').length) {
                var companyBtn = '<button type="button" id="btn-add-company" class="btn-add-item" style="margin-bottom:15px;">🏢 Adaugă companie</button>';
                companiesSection.before(companyBtn);
                console.log('[WebGSM] Company button added');
            }
            
            // Buton pentru Persoane fizice
            var personsSection = $('.persons-list, table:contains("Persoane")').first();
            console.log('[WebGSM] Persons section found:', personsSection.length);
            if (personsSection.length && !$('#btn-add-person').length) {
                var personBtn = '<button type="button" id="btn-add-person" class="btn-add-item" style="margin-bottom:15px;">👤 Adaugă persoană fizică</button>';
                personsSection.before(personBtn);
                console.log('[WebGSM] Person button added');
            }
            
            // Dacă nu găsește niciunul, încearcă să le adauge după heading-uri
            if (!$('#btn-add-address').length) {
                $('h3:contains("Adrese"), h4:contains("Adrese")').first().after('<button type="button" id="btn-add-address" class="btn-add-item" style="margin:15px 0;">📍 Adaugă adresă livrare</button>');
            }
            if (!$('#btn-add-company').length) {
                $('h3:contains("Firme"), h4:contains("Firme"), h3:contains("Compan"), h4:contains("Compan")').first().after('<button type="button" id="btn-add-company" class="btn-add-item" style="margin:15px 0;">🏢 Adaugă companie</button>');
            }
            if (!$('#btn-add-person').length) {
                $('h3:contains("Persoan"), h4:contains("Persoan"), h3:contains("PF"), h4:contains("PF")').first().after('<button type="button" id="btn-add-person" class="btn-add-item" style="margin:15px 0;">👤 Adaugă persoană fizică</button>');
            }
        }
        
        // Adaugă butoanele la încărcare și după un delay pentru DOM dinamic
        setTimeout(addActionButtons, 100);
        setTimeout(addActionButtons, 500);
        
        // Handler pentru deschidere modale
        $(document).on('click', '#btn-add-address', function(e) {
            e.preventDefault();
            console.log('[WebGSM] Opening address modal');
            $('#edit_address_index').val(''); // Reset index pentru adăugare nouă
            $('#modal_title').text('Adaugă adresă livrare');
            $('#address_modal_saved').fadeIn(200);
        });
        
        $(document).on('click', '#btn-add-company', function(e) {
            e.preventDefault();
            console.log('[WebGSM] Opening company modal');
            $('#edit_company_index').val(''); // Reset index pentru adăugare nouă
            $('#company_modal_title').text('Adaugă companie');
            $('#company_modal_saved').fadeIn(200);
        });
        
        $(document).on('click', '#btn-add-person', function(e) {
            e.preventDefault();
            console.log('[WebGSM] Opening person modal');
            $('#edit_person_index').val(''); // Reset index pentru adăugare nouă
            $('#person_modal_title').text('Adaugă persoană fizică');
            $('#person_modal_saved').fadeIn(200);
        });
        
        // Căutare automată ANAF când utilizatorul introduce CUI
        var anafTimeout;
        $(document).on('input', '#company_cui_modal', function() {
            clearTimeout(anafTimeout);
            var $input = $(this);
            var cui = $input.val().trim().replace(/^RO/i, '');
            
            // Resetează status
            $('#anaf_status_modal').hide();
            
            // Caută doar dacă are minim 6 cifre
            if (cui.length >= 6) {
                $('#anaf_status_modal').show().html('🔍 Se caută automat...').css({background: '#eff6ff', color: '#1e40af', border: '1px solid #bfdbfe'});
                
                anafTimeout = setTimeout(function() {
                    $.ajax({
                        url: (typeof webgsm_myaccount !== 'undefined') ? webgsm_myaccount.ajax_url : ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'cauta_cui_anaf',
                            cui: cui
                        },
                        success: function(response) {
                            if (response.success && response.data) {
                                var data = response.data;
                                $('#company_name_modal').val(data.denumire || '');
                                $('#company_reg_modal').val(data.nrRegCom || '');
                                $('#company_address_modal').val(data.adresa || '');
                                $('#company_city_modal').val(data.localitate || '');
                                
                                // Setează județul dacă există în lista de opțiuni
                                if (data.judet) {
                                    $('#company_county_modal option').each(function() {
                                        if ($(this).text().indexOf(data.judet) > -1) {
                                            $('#company_county_modal').val($(this).val());
                                            return false;
                                        }
                                    });
                                }
                                
                                $('#anaf_status_modal').html('✓ Date completate automat').css({background: '#f0fdf4', color: '#166534', border: '1px solid #bbf7d0'});
                                setTimeout(function() { $('#anaf_status_modal').fadeOut(); }, 2500);
                            } else {
                                $('#anaf_status_modal').html('✗ CUI negăsit în ANAF').css({background: '#fef2f2', color: '#991b1b', border: '1px solid #fecaca'});
                                setTimeout(function() { $('#anaf_status_modal').fadeOut(); }, 3000);
                            }
                        },
                        error: function() {
                            $('#anaf_status_modal').html('✗ Eroare la verificare').css({background: '#fef2f2', color: '#991b1b', border: '1px solid #fecaca'});
                            setTimeout(function() { $('#anaf_status_modal').fadeOut(); }, 3000);
                        }
                    });
                }, 800); // Delay de 800ms după ce utilizatorul termină de scris
            }
        });
        
        // Handler pentru închidere modale
        $(document).on('click', '.popup-close, .modal-close-btn, .modal-cancel-btn, .popup-overlay', function(e) {
            $(this).closest('.webgsm-popup').fadeOut(200);
        });
        
        // Handler pentru salvare adresă
        $(document).on('click', '#save_address_modal_btn', function(e) {
            e.preventDefault();
            var $btn = $(this);
            
            var data = {
                action: 'webgsm_save_address',
                nonce: (typeof webgsm_myaccount !== 'undefined') ? webgsm_myaccount.nonce : webgsm_checkout.nonce,
                index: $('#edit_address_index').val(),
                label: $('#modal_label').val(),
                name: $('#modal_name').val(),
                phone: $('#modal_phone').val(),
                address: $('#modal_address').val(),
                city: $('#modal_city').val(),
                county: $('#modal_county').val(),
                postcode: $('#modal_postcode').val()
            };
            
            $btn.prop('disabled', true).text('Se salvează...');
            
            $.ajax({
                url: (typeof webgsm_myaccount !== 'undefined') ? webgsm_myaccount.ajax_url : webgsm_checkout.ajax_url,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        alert('Adresa salvată cu succes!');
                        location.reload();
                    } else {
                        alert('Eroare: ' + (response.data || 'Nu s-a putut salva'));
                        $btn.prop('disabled', false).text('Salveaza');
                    }
                },
                error: function() {
                    alert('Eroare la comunicare cu serverul');
                    $btn.prop('disabled', false).text('Salveaza');
                }
            });
        });
        
        // Handler pentru salvare companie
        $(document).on('click', '#save_company_modal_btn', function(e) {
            e.preventDefault();
            var $btn = $(this);
            
            var data = {
                action: 'webgsm_save_company',
                nonce: (typeof webgsm_myaccount !== 'undefined') ? webgsm_myaccount.nonce : webgsm_checkout.nonce,
                index: $('#edit_company_index').val(),
                cui: $('#company_cui_modal').val(),
                name: $('#company_name_modal').val(),
                reg: $('#company_reg_modal').val(),
                phone: $('#company_phone_modal').val(),
                email: $('#company_email_modal').val(),
                address: $('#company_address_modal').val(),
                county: $('#company_county_modal').val(),
                city: $('#company_city_modal').val()
            };
            
            $btn.prop('disabled', true).text('Se salvează...');
            
            $.ajax({
                url: (typeof webgsm_myaccount !== 'undefined') ? webgsm_myaccount.ajax_url : webgsm_checkout.ajax_url,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        alert('Compania salvată cu succes!');
                        location.reload();
                    } else {
                        alert('Eroare: ' + (response.data || 'Nu s-a putut salva'));
                        $btn.prop('disabled', false).text('Salveaza');
                    }
                },
                error: function() {
                    alert('Eroare la comunicare cu serverul');
                    $btn.prop('disabled', false).text('Salveaza');
                }
            });
        });
        
        // Handler pentru salvare persoană
        $(document).on('click', '#save_person_modal_btn', function(e) {
            e.preventDefault();
            var $btn = $(this);
            
            var data = {
                action: 'webgsm_save_person',
                nonce: (typeof webgsm_myaccount !== 'undefined') ? webgsm_myaccount.nonce : webgsm_checkout.nonce,
                index: $('#edit_person_index').val(),
                name: $('#person_name_modal').val(),
                cnp: $('#person_cnp_modal').val(),
                phone: $('#person_phone_modal').val(),
                email: $('#person_email_modal').val(),
                address: $('#person_address_modal').val(),
                county: $('#person_county_modal').val(),
                city: $('#person_city_modal').val(),
                postcode: $('#person_postcode_modal').val()
            };
            
            $btn.prop('disabled', true).text('Se salvează...');
            
            $.ajax({
                url: (typeof webgsm_myaccount !== 'undefined') ? webgsm_myaccount.ajax_url : webgsm_checkout.ajax_url,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        alert('Persoana salvată cu succes!');
                        location.reload();
                    } else {
                        alert('Eroare: ' + (response.data || 'Nu s-a putut salva'));
                        $btn.prop('disabled', false).text('Salveaza');
                    }
                },
                error: function() {
                    alert('Eroare la comunicare cu serverul');
                    $btn.prop('disabled', false).text('Salveaza');
                }
            });
        });
        
        // Funcție generică pentru ștergere
        function deleteItem($btn, action, confirmMsg, itemType) {
            var index = $btn.data('index');
            
            console.log('[WebGSM] Delete ' + itemType + ', index:', index);
            console.log('[WebGSM] Button element:', $btn[0]);
            console.log('[WebGSM] Button HTML:', $btn[0].outerHTML);
            
            if (index === undefined || index === null) {
                console.error('[WebGSM] No index found on button');
                alert('Eroare: butonul nu are index valid. Verifică că butonul are atributul data-index.');
                return false;
            }
            
            if (!confirm(confirmMsg)) {
                return false;
            }
            
            // Determină nonce și ajax_url
            var nonce = '';
            var ajax_url = '';
            
            if (typeof webgsm_checkout !== 'undefined') {
                nonce = webgsm_checkout.nonce;
                ajax_url = webgsm_checkout.ajax_url;
            } else if (typeof webgsm_myaccount !== 'undefined') {
                nonce = webgsm_myaccount.nonce;
                ajax_url = webgsm_myaccount.ajax_url;
            }
            
            if (!nonce || !ajax_url) {
                alert('Eroare: configurație AJAX lipsă');
                console.error('[WebGSM] Missing nonce or ajax_url');
                return false;
            }
            
            $btn.prop('disabled', true).css('opacity', '0.5');
            
            $.ajax({
                url: ajax_url,
                type: 'POST',
                data: {
                    action: action,
                    index: index,
                    nonce: nonce
                },
                success: function(response) {
                    console.log('[WebGSM] Response:', response);
                    if (response.success) {
                        var $row = $btn.closest('tr');
                        if ($row.length) {
                            $row.fadeOut(300, function() { $(this).remove(); });
                        } else {
                            $btn.closest('.webgsm-radio, .address-item, .company-item, .person-item').fadeOut(300, function() { $(this).remove(); });
                        }
                    } else {
                        alert('Eroare: ' + (response.data || 'Operațiune eșuată'));
                        $btn.prop('disabled', false).css('opacity', '1');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('[WebGSM] AJAX error:', status, error);
                    alert('Eroare la comunicarea cu serverul');
                    $btn.prop('disabled', false).css('opacity', '1');
                }
            });
        }
        
        // Handler universal pentru toate butoanele de ștergere
        $(document).on('click', '.delete-address, .delete-saved-address, .delete-company, .delete-saved-company, .delete-person, .delete-saved-person, button[class*="delete"], a[class*="delete"]', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $btn = $(this);
            var btnClass = $btn.attr('class');
            var action = 'webgsm_delete_address';
            var confirmMsg = 'Sigur vrei să ștergi acest element?';
            var itemType = 'item';
            
            console.log('[WebGSM] Button class:', btnClass);
            
            // Detectează tipul bazat pe clasă sau context
            if (btnClass && (btnClass.indexOf('delete-company') > -1 || btnClass.indexOf('delete-saved-company') > -1)) {
                action = 'webgsm_delete_company';
                confirmMsg = 'Sigur vrei să ștergi această firmă?';
                itemType = 'company';
            } else if (btnClass && (btnClass.indexOf('delete-person') > -1 || btnClass.indexOf('delete-saved-person') > -1)) {
                action = 'webgsm_delete_person';
                confirmMsg = 'Sigur vrei să ștergi această persoană?';
                itemType = 'person';
            } else if ($btn.closest('.company-item').length || $btn.closest('.companies-list').length) {
                action = 'webgsm_delete_company';
                confirmMsg = 'Sigur vrei să ștergi această firmă?';
                itemType = 'company';
            } else if ($btn.closest('.person-item').length || $btn.closest('.persons-list').length) {
                action = 'webgsm_delete_person';
                confirmMsg = 'Sigur vrei să ștergi această persoană?';
                itemType = 'person';
            } else if (btnClass && (btnClass.indexOf('delete-address') > -1 || btnClass.indexOf('delete-saved-address') > -1)) {
                action = 'webgsm_delete_address';
                confirmMsg = 'Sigur vrei să ștergi această adresă?';
                itemType = 'address';
            }
            
            console.log('[WebGSM] Detected type:', itemType, 'action:', action);
            deleteItem($btn, action, confirmMsg, itemType);
            return false;
        });
    });
    </script>
    <?php
}, 999);

// AJAX handlers pentru ștergere
add_action('wp_ajax_webgsm_delete_address', function() {
    check_ajax_referer('webgsm_nonce', 'nonce');
    if (!is_user_logged_in()) wp_send_json_error('Neautorizat');
    
    $user_id = get_current_user_id();
    $index = isset($_POST['index']) ? intval($_POST['index']) : -1;
    $addresses = get_user_meta($user_id, 'webgsm_addresses', true);
    
    if (!is_array($addresses)) $addresses = [];
    if ($index < 0 || $index >= count($addresses)) wp_send_json_error('Index invalid');
    
    array_splice($addresses, $index, 1);
    update_user_meta($user_id, 'webgsm_addresses', $addresses);
    
    wp_send_json_success(['message' => 'Adresa ștearsă cu succes', 'addresses' => $addresses]);
}, 5);

add_action('wp_ajax_webgsm_delete_company', function() {
    error_log('[WebGSM] Delete company handler called');
    error_log('[WebGSM] POST data: ' . print_r($_POST, true));
    
    check_ajax_referer('webgsm_nonce', 'nonce');
    if (!is_user_logged_in()) {
        error_log('[WebGSM] User not logged in');
        wp_send_json_error('Neautorizat');
    }
    
    $user_id = get_current_user_id();
    $index = isset($_POST['index']) ? intval($_POST['index']) : -1;
    error_log('[WebGSM] Delete company - User ID: ' . $user_id . ', Index: ' . $index);
    
    $companies = get_user_meta($user_id, 'webgsm_companies', true);
    error_log('[WebGSM] Companies count: ' . (is_array($companies) ? count($companies) : 0));
    error_log('[WebGSM] Companies data: ' . print_r($companies, true));
    
    if (!is_array($companies)) $companies = [];
    if ($index < 0 || $index >= count($companies)) {
        error_log('[WebGSM] Invalid index - index: ' . $index . ', count: ' . count($companies));
        wp_send_json_error('Index invalid - primit: ' . $index . ', total: ' . count($companies));
    }
    
    array_splice($companies, $index, 1);
    update_user_meta($user_id, 'webgsm_companies', $companies);
    error_log('[WebGSM] Company deleted successfully, remaining: ' . count($companies));
    
    wp_send_json_success(['message' => 'Firma ștearsă cu succes', 'companies' => $companies]);
}, 5);

add_action('wp_ajax_webgsm_delete_person', function() {
    error_log('[WebGSM] Delete person handler called');
    check_ajax_referer('webgsm_nonce', 'nonce');
    if (!is_user_logged_in()) {
        error_log('[WebGSM] User not logged in');
        wp_send_json_error('Neautorizat');
    }
    
    $user_id = get_current_user_id();
    $index = isset($_POST['index']) ? intval($_POST['index']) : -1;
    error_log('[WebGSM] Delete person index: ' . $index);
    $persons = get_user_meta($user_id, 'webgsm_persons', true);
    
    if (!is_array($persons)) $persons = [];
    if ($index < 0 || $index >= count($persons)) {
        error_log('[WebGSM] Invalid person index');
        wp_send_json_error('Index invalid');
    }
    
    array_splice($persons, $index, 1);
    update_user_meta($user_id, 'webgsm_persons', $persons);
    error_log('[WebGSM] Person deleted successfully');
    
    wp_send_json_success(['message' => 'Persoana ștearsă cu succes', 'persons' => $persons]);
}, 5);

// AJAX handlers pentru salvare (adăugare/editare)
add_action('wp_ajax_webgsm_save_address', function() {
    check_ajax_referer('webgsm_nonce', 'nonce');
    if (!is_user_logged_in()) wp_send_json_error('Neautorizat');
    
    $user_id = get_current_user_id();
    $addresses = get_user_meta($user_id, 'webgsm_addresses', true);
    if (!is_array($addresses)) $addresses = [];
    
    $address = [
        'label' => sanitize_text_field($_POST['label'] ?? ''),
        'name' => sanitize_text_field($_POST['name'] ?? ''),
        'phone' => sanitize_text_field($_POST['phone'] ?? ''),
        'address' => sanitize_text_field($_POST['address'] ?? ''),
        'city' => sanitize_text_field($_POST['city'] ?? ''),
        'county' => sanitize_text_field($_POST['county'] ?? ''),
        'postcode' => sanitize_text_field($_POST['postcode'] ?? '')
    ];
    
    // Validare câmpuri obligatorii
    if (empty($address['name']) || empty($address['phone']) || empty($address['address']) || empty($address['city'])) {
        wp_send_json_error('Câmpurile marcate cu * sunt obligatorii');
    }
    
    $index = isset($_POST['index']) && $_POST['index'] !== '' ? intval($_POST['index']) : -1;
    
    if ($index >= 0 && $index < count($addresses)) {
        // Editare
        $addresses[$index] = $address;
    } else {
        // Adăugare nouă
        $addresses[] = $address;
    }
    
    update_user_meta($user_id, 'webgsm_addresses', $addresses);
    wp_send_json_success(['message' => 'Adresa salvată cu succes', 'addresses' => $addresses]);
}, 5);

add_action('wp_ajax_webgsm_save_company', function() {
    check_ajax_referer('webgsm_nonce', 'nonce');
    if (!is_user_logged_in()) wp_send_json_error('Neautorizat');
    
    $user_id = get_current_user_id();
    $companies = get_user_meta($user_id, 'webgsm_companies', true);
    if (!is_array($companies)) $companies = [];
    
    $company = [
        'cui' => sanitize_text_field($_POST['cui'] ?? ''),
        'name' => sanitize_text_field($_POST['name'] ?? ''),
        'reg' => sanitize_text_field($_POST['reg'] ?? ''),
        'phone' => sanitize_text_field($_POST['phone'] ?? ''),
        'email' => sanitize_email($_POST['email'] ?? ''),
        'address' => sanitize_text_field($_POST['address'] ?? ''),
        'county' => sanitize_text_field($_POST['county'] ?? ''),
        'city' => sanitize_text_field($_POST['city'] ?? '')
    ];
    
    // Validare câmpuri obligatorii
    if (empty($company['cui']) || empty($company['name']) || empty($company['reg']) || empty($company['phone']) || empty($company['email'])) {
        wp_send_json_error('Câmpurile marcate cu * sunt obligatorii');
    }
    
    $index = isset($_POST['index']) && $_POST['index'] !== '' ? intval($_POST['index']) : -1;
    
    if ($index >= 0 && $index < count($companies)) {
        // Editare
        $companies[$index] = $company;
    } else {
        // Adăugare nouă
        $companies[] = $company;
    }
    
    update_user_meta($user_id, 'webgsm_companies', $companies);
    wp_send_json_success(['message' => 'Compania salvată cu succes', 'companies' => $companies]);
}, 5);

add_action('wp_ajax_webgsm_save_person', function() {
    check_ajax_referer('webgsm_nonce', 'nonce');
    if (!is_user_logged_in()) wp_send_json_error('Neautorizat');
    
    $user_id = get_current_user_id();
    $persons = get_user_meta($user_id, 'webgsm_persons', true);
    if (!is_array($persons)) $persons = [];
    
    $person = [
        'name' => sanitize_text_field($_POST['name'] ?? ''),
        'cnp' => sanitize_text_field($_POST['cnp'] ?? ''),
        'phone' => sanitize_text_field($_POST['phone'] ?? ''),
        'email' => sanitize_email($_POST['email'] ?? ''),
        'address' => sanitize_text_field($_POST['address'] ?? ''),
        'county' => sanitize_text_field($_POST['county'] ?? ''),
        'city' => sanitize_text_field($_POST['city'] ?? ''),
        'postcode' => sanitize_text_field($_POST['postcode'] ?? '')
    ];
    
    // Validare câmpuri obligatorii
    if (empty($person['name']) || empty($person['phone']) || empty($person['email']) || empty($person['address'])) {
        wp_send_json_error('Câmpurile marcate cu * sunt obligatorii');
    }
    
    $index = isset($_POST['index']) && $_POST['index'] !== '' ? intval($_POST['index']) : -1;
    
    if ($index >= 0 && $index < count($persons)) {
        // Editare
        $persons[$index] = $person;
    } else {
        // Adăugare nouă
        $persons[] = $person;
    }
    
    update_user_meta($user_id, 'webgsm_persons', $persons);
    wp_send_json_success(['message' => 'Persoana salvată cu succes', 'persons' => $persons]);
}, 5);