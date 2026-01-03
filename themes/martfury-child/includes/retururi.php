<?php
/**
 * MODUL RETURURI
 * Gestionează cererile de retur ale clienților
 * - Verifică 14 zile de la DATA LIVRĂRII (status completed)
 * - Permite selectare cantitate (dacă sunt mai multe bucăți)
 * - Upload poze
 * - Generează factură storno la aprobare
 */

// Adaugă tab-ul "Retururi" în My Account
add_filter('woocommerce_account_menu_items', function($items) {
    $new_items = array();
    foreach($items as $key => $value) {
        $new_items[$key] = $value;
        if($key === 'orders') {
            $new_items['retururi'] = 'Retururi';
        }
    }
    return $new_items;
}, 10);

// Creează endpoint-ul pentru retururi
add_action('init', function() {
    add_rewrite_endpoint('retururi', EP_ROOT | EP_PAGES);
});

// Înregistrează Custom Post Type pentru Cereri Retur
add_action('init', function() {
    register_post_type('cerere_retur', array(
        'labels' => array(
            'name' => 'Cereri Retur',
            'singular_name' => 'Cerere Retur',
            'menu_name' => 'Cereri Retur',
            'all_items' => 'Toate cererile',
            'view_item' => 'Vezi cererea',
            'edit_item' => 'Editează cererea'
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 56,
        'menu_icon' => 'dashicons-undo',
        'supports' => array('title'),
        'capability_type' => 'post'
    ));
});

// Funcție helper: calculează cantitatea deja returnată dintr-un produs
function get_qty_returned($order_id, $product_id, $customer_id) {
    $retururi = get_posts(array(
        'post_type' => 'cerere_retur',
        'author' => $customer_id,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_order_id',
                'value' => $order_id
            ),
            array(
                'key' => '_product_id',
                'value' => $product_id
            )
        ),
        'numberposts' => -1
    ));
    
    $total_returned = 0;
    foreach($retururi as $retur) {
        $qty = get_post_meta($retur->ID, '_qty_retur', true);
        $total_returned += $qty ? intval($qty) : 1;
    }
    
    return $total_returned;
}

// Funcție helper: ia data livrării (când comanda a devenit "completed")
function get_delivery_date($order) {
    $date_completed = $order->get_date_completed();
    
    if($date_completed) {
        return $date_completed->getTimestamp();
    }
    
    return $order->get_date_created()->getTimestamp();
}

// Funcție helper: upload poze retur
function upload_poze_retur($files, $retur_id) {
    if(!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }
    if(!function_exists('wp_generate_attachment_metadata')) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
    }
    
    $uploaded_ids = array();
    $allowed_types = array('image/jpeg', 'image/png', 'image/jpg');
    
    $file_count = count($files['name']);
    
    for($i = 0; $i < $file_count; $i++) {
        if($files['error'][$i] !== UPLOAD_ERR_OK || empty($files['name'][$i])) {
            continue;
        }
        
        if(!in_array($files['type'][$i], $allowed_types)) {
            continue;
        }
        
        if($files['size'][$i] > 5 * 1024 * 1024) {
            continue;
        }
        
        $file = array(
            'name' => $files['name'][$i],
            'type' => $files['type'][$i],
            'tmp_name' => $files['tmp_name'][$i],
            'error' => $files['error'][$i],
            'size' => $files['size'][$i]
        );
        
        $upload = wp_handle_upload($file, array('test_form' => false));
        
        if($upload && !isset($upload['error'])) {
            $attachment = array(
                'post_mime_type' => $upload['type'],
                'post_title' => sanitize_file_name($file['name']),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            
            $attach_id = wp_insert_attachment($attachment, $upload['file'], $retur_id);
            
            if($attach_id) {
                $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);
                $uploaded_ids[] = $attach_id;
            }
        }
    }
    
    return $uploaded_ids;
}

// =============================================
// GENERARE STORNO SMARTBILL LA APROBARE RETUR
// =============================================

function genereaza_storno_retur($retur_id) {
    $order_id = get_post_meta($retur_id, '_order_id', true);
    $product_id = get_post_meta($retur_id, '_product_id', true);
    $qty_retur = get_post_meta($retur_id, '_qty_retur', true);
    
    if(!$qty_retur) $qty_retur = 1;
    
    // Verifică dacă storno-ul a fost deja generat
    $storno_existent = get_post_meta($retur_id, '_smartbill_storno_number', true);
    if($storno_existent) {
        return array('number' => $storno_existent);
    }
    
    // Verifică dacă comanda are factură
    $invoice_series = get_post_meta($order_id, '_smartbill_invoice_series', true);
    $invoice_number = get_post_meta($order_id, '_smartbill_invoice_number', true);
    
    if(!$invoice_series || !$invoice_number) {
        return array('error' => 'Comanda nu are factură generată');
    }
    
    $order = wc_get_order($order_id);
    if(!$order) return array('error' => 'Comanda nu există');
    
    // Găsește produsul în comandă pentru a lua prețul corect
    $product_price = 0;
    $product_name = '';
    $product_sku = '';
    
    foreach($order->get_items() as $item) {
        if($item->get_product_id() == $product_id) {
            $product_price = $item->get_total() / $item->get_quantity();
            $product_name = $item->get_name();
            $product = $item->get_product();
            $product_sku = $product ? $product->get_sku() : '';
            break;
        }
    }
    
    if(!$product_name) {
        return array('error' => 'Produsul nu a fost găsit în comandă');
    }
    
    // Pregătește datele clientului
    $billing_company = $order->get_billing_company();
    $billing_cif = get_post_meta($order_id, '_billing_cif', true);
    
    $client = array(
        'name' => $billing_company ? $billing_company : $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
        'vatCode' => $billing_cif ? $billing_cif : '',
        'address' => $order->get_billing_address_1() . ' ' . $order->get_billing_address_2(),
        'city' => $order->get_billing_city(),
        'county' => $order->get_billing_state(),
        'country' => $order->get_billing_country(),
        'email' => $order->get_billing_email(),
        'phone' => $order->get_billing_phone(),
        'isTaxPayer' => !empty($billing_cif)
    );
    
    // Produsul pentru storno (cu valoare negativă)
    $products = array(
        array(
            'name' => $product_name,
            'code' => $product_sku,
            'measuringUnitName' => 'buc',
            'currency' => $order->get_currency(),
            'quantity' => $qty_retur,
            'price' => -abs($product_price), // Preț negativ pentru storno
            'isTaxIncluded' => true,
            'taxPercentage' => 19,
            'saveToDb' => false
        )
    );
    
    // Datele facturii storno
    $storno_data = array(
        'companyVatCode' => SMARTBILL_CIF,
        'seriesName' => SMARTBILL_SERIE_FACTURA,
        'client' => $client,
        'products' => $products,
        'issueDate' => date('Y-m-d'),
        'currency' => $order->get_currency(),
        'language' => 'RO',
        'observations' => 'Storno pentru retur - Factura originală: ' . $invoice_series . $invoice_number . ' | Comandă #' . $order->get_order_number()
    );
    
    // Trimite la SmartBill
    $response = smartbill_request('invoice', $storno_data);
    
    if(isset($response['errorText']) && !empty($response['errorText'])) {
        return array('error' => $response['errorText']);
    }
    
    if(isset($response['number'])) {
        // Salvează datele storno-ului la cererea de retur
        update_post_meta($retur_id, '_smartbill_storno_number', $response['number']);
        update_post_meta($retur_id, '_smartbill_storno_series', $response['series']);
        update_post_meta($retur_id, '_smartbill_storno_date', date('Y-m-d'));
        
        return $response;
    }
    
    return array('error' => 'Răspuns necunoscut de la SmartBill');
}

// Hook: Generează storno când returul e aprobat
add_action('save_post_cerere_retur', function($post_id) {
    if(isset($_POST['status_retur'])) {
        $old_status = get_post_meta($post_id, '_status_retur', true);
        $new_status = sanitize_text_field($_POST['status_retur']);
        
        // Dacă statusul se schimbă în "finalizat", generează storno
if($new_status === 'finalizat' && $old_status !== 'finalizat') {
            $result = genereaza_storno_retur($post_id);
            
            if(isset($result['error'])) {
                // Adaugă eroarea ca notificare admin
                set_transient('storno_error_' . $post_id, $result['error'], 60);
            }
        }
        
        update_post_meta($post_id, '_status_retur', $new_status);
        
        if($old_status !== $new_status) {
            do_action('status_retur_schimbat', $post_id, $old_status, $new_status);
        }
    }
});

// Afișează eroare/succes storno în admin
add_action('admin_notices', function() {
    global $post;
    if(!$post || $post->post_type !== 'cerere_retur') return;
    
    $error = get_transient('storno_error_' . $post->ID);
    if($error) {
        echo '<div class="notice notice-error"><p>Eroare la generarea storno: ' . esc_html($error) . '</p></div>';
        delete_transient('storno_error_' . $post->ID);
    }
    
    $storno_number = get_post_meta($post->ID, '_smartbill_storno_number', true);
    $storno_series = get_post_meta($post->ID, '_smartbill_storno_series', true);
    if($storno_number && isset($_GET['storno_generated'])) {
        echo '<div class="notice notice-success"><p>Storno generat cu succes: ' . esc_html($storno_series . $storno_number) . '</p></div>';
    }
});

// Conținutul paginii de retururi
add_action('woocommerce_account_retururi_endpoint', function() {
    $customer_id = get_current_user_id();
    
    // Mesaj de succes după redirect
    if(isset($_GET['retur_success'])) {
        echo '<div class="woocommerce-message">Cererea de retur a fost trimisă cu succes! Vei fi contactat în curând.</div>';
    }
    
    // Procesare formular retur
    if(isset($_POST['submit_retur']) && wp_verify_nonce($_POST['retur_nonce'], 'submit_retur_form')) {
        $order_id = intval($_POST['order_id']);
        $product_id = intval($_POST['product_id']);
        $qty_retur = intval($_POST['qty_retur']);
        $motiv = sanitize_textarea_field($_POST['motiv_retur']);
        $tip_retur = sanitize_text_field($_POST['tip_retur']);
        
        // Validare câmpuri obligatorii
        if(empty($order_id) || empty($product_id) || empty($motiv) || empty($tip_retur) || $qty_retur < 1) {
            echo '<div class="woocommerce-error">Te rugăm să completezi toate câmpurile obligatorii.</div>';
        }
        else {
            // Verifică cantitatea disponibilă
            $order = wc_get_order($order_id);
            $qty_available = 0;
            
            foreach($order->get_items() as $item) {
                if($item->get_product_id() == $product_id) {
                    $qty_ordered = $item->get_quantity();
                    $qty_returned = get_qty_returned($order_id, $product_id, $customer_id);
                    $qty_available = $qty_ordered - $qty_returned;
                    break;
                }
            }
            
            if($qty_retur > $qty_available) {
                echo '<div class="woocommerce-error">Nu poți returna mai multe bucăți decât ai disponibile (' . $qty_available . ').</div>';
            }
            else {
                // Verifică dacă comanda e în perioada de retur (14 zile de la LIVRARE)
                $delivery_date = get_delivery_date($order);
                $days_since_delivery = (time() - $delivery_date) / DAY_IN_SECONDS;
                
                if($days_since_delivery > 14) {
                    echo '<div class="woocommerce-error">Perioada de retur de 14 zile de la livrare a expirat pentru această comandă.</div>';
                } else {
                    // Salvează cererea de retur
                    $retur_id = wp_insert_post(array(
                        'post_type' => 'cerere_retur',
                        'post_title' => 'Retur #' . $order_id . ' - ' . date('Y-m-d H:i'),
                        'post_status' => 'publish',
                        'post_author' => $customer_id
                    ));
                    
                    if($retur_id) {
                        update_post_meta($retur_id, '_order_id', $order_id);
                        update_post_meta($retur_id, '_product_id', $product_id);
                        update_post_meta($retur_id, '_qty_retur', $qty_retur);
                        update_post_meta($retur_id, '_motiv_retur', $motiv);
                        update_post_meta($retur_id, '_tip_retur', $tip_retur);
                        update_post_meta($retur_id, '_status_retur', 'nou');
                        update_post_meta($retur_id, '_customer_id', $customer_id);
                        
                        // Upload poze dacă există
                        if(!empty($_FILES['poze_retur']['name'][0])) {
                            $uploaded_ids = upload_poze_retur($_FILES['poze_retur'], $retur_id);
                            if(!empty($uploaded_ids)) {
                                update_post_meta($retur_id, '_poze_retur', $uploaded_ids);
                            }
                        }
                        
                        // Trigger pentru n8n webhook
                        do_action('cerere_retur_noua', $retur_id, $order_id, $product_id, $customer_id);
                        
                        // Redirect cu JavaScript
                        echo '<script>window.location.href = "' . wc_get_account_endpoint_url('retururi') . '?retur_success=1";</script>';
                        exit;
                    }
                }
            }
        }
    }
    
    // Afișează retururile existente ale clientului
    $retururi = get_posts(array(
        'post_type' => 'cerere_retur',
        'author' => $customer_id,
        'numberposts' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    if(!empty($retururi)) {
        echo '<h3>Cererile tale de retur</h3>';
        echo '<table class="woocommerce-orders-table" style="width:100%; margin-bottom:30px;">';
        echo '<thead><tr><th>Data</th><th>Comandă</th><th>Produs</th><th>Cantitate</th><th>Tip</th><th>Status</th><th>Storno</th></tr></thead>';
        echo '<tbody>';
        foreach($retururi as $retur) {
            $status = get_post_meta($retur->ID, '_status_retur', true);
            $order_id = get_post_meta($retur->ID, '_order_id', true);
            $product_id = get_post_meta($retur->ID, '_product_id', true);
            $qty = get_post_meta($retur->ID, '_qty_retur', true);
            $tip = get_post_meta($retur->ID, '_tip_retur', true);
            $product = wc_get_product($product_id);
            
            // Storno info
            $storno_number = get_post_meta($retur->ID, '_smartbill_storno_number', true);
            $storno_series = get_post_meta($retur->ID, '_smartbill_storno_series', true);
            
            $status_label = array(
                'nou' => '<span style="color:orange;">Nou</span>',
                'aprobat' => '<span style="color:green;">Aprobat</span>',
                'respins' => '<span style="color:red;">Respins</span>',
                'finalizat' => '<span style="color:blue;">Finalizat</span>'
            );
            
            echo '<tr>';
            echo '<td>' . get_the_date('d.m.Y', $retur) . '</td>';
            echo '<td>#' . $order_id . '</td>';
            echo '<td>' . ($product ? $product->get_name() : '-') . '</td>';
            echo '<td>' . ($qty ? $qty : 1) . ' buc</td>';
            echo '<td>' . ucfirst($tip) . '</td>';
            echo '<td>' . ($status_label[$status] ?? $status) . '</td>';
            echo '<td>';
            if($storno_number) {
                echo '<a href="' . admin_url('admin-ajax.php?action=download_storno_pdf&retur_id=' . $retur->ID) . '" class="button" target="_blank">📄 Storno</a>';
            } else {
                echo '-';
            }
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    }
    
    // Formularul de retur nou
    echo '<h3>Solicită un retur</h3>';
    echo '<p><em>Poți returna produsele în termen de 14 zile de la livrare.</em></p>';
    
    // Ia comenzile clientului (doar cele completate/livrate)
    $orders = wc_get_orders(array(
        'customer_id' => $customer_id,
        'status' => array('completed'),
        'limit' => 50
    ));
    
    // Filtrează doar comenzile din ultimele 14 zile de la livrare
    $eligible_orders = array();
    foreach($orders as $order) {
        $delivery_date = get_delivery_date($order);
        $days_since_delivery = (time() - $delivery_date) / DAY_IN_SECONDS;
        if($days_since_delivery <= 14) {
            $eligible_orders[] = $order;
        }
    }
    
    if(empty($eligible_orders)) {
        echo '<p>Nu ai comenzi eligibile pentru retur (doar comenzile livrate în ultimele 14 zile pot fi returnate).</p>';
        return;
    }
    
    ?>
    <form method="post" class="retur-form" enctype="multipart/form-data">
        <?php wp_nonce_field('submit_retur_form', 'retur_nonce'); ?>
        
        <p class="form-row">
            <label>Selectează comanda *</label>
            <select name="order_id" id="select_order_retur" required style="width:100%;">
                <option value="">-- Alege comanda --</option>
                <?php foreach($eligible_orders as $order): 
                    $delivery_date = get_delivery_date($order);
                    $days_left = 14 - floor((time() - $delivery_date) / DAY_IN_SECONDS);
                ?>
                    <option value="<?php echo $order->get_id(); ?>">
                        #<?php echo $order->get_order_number(); ?> - Livrat: <?php echo date('d.m.Y', $delivery_date); ?> - <?php echo $order->get_total(); ?> lei (<?php echo $days_left; ?> zile rămase)
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        
        <p class="form-row">
            <label>Selectează produsul *</label>
            <select name="product_id" id="select_product_retur" required style="width:100%;">
                <option value="">-- Alege mai întâi comanda --</option>
            </select>
        </p>
        
        <p class="form-row" id="qty_retur_row" style="display:none;">
            <label>Cantitate de returnat *</label>
            <select name="qty_retur" id="select_qty_retur" required style="width:100%;">
                <option value="1">1 bucată</option>
            </select>
        </p>
        
        <p class="form-row">
            <label>Tip retur *</label>
            <select name="tip_retur" required style="width:100%;">
                <option value="">-- Selectează --</option>
                <option value="defect">Produs defect</option>
                <option value="gresit">Produs greșit livrat</option>
                <option value="nemultumit">Nu sunt mulțumit</option>
                <option value="altul">Alt motiv</option>
            </select>
        </p>
        
        <p class="form-row">
            <label>Descrie motivul returului *</label>
            <textarea name="motiv_retur" required style="width:100%; min-height:100px;" placeholder="Te rugăm să descrii motivul returului cât mai detaliat..."></textarea>
        </p>
        
        <p class="form-row">
            <label>Adaugă poze (opțional, max 5 poze, JPG/PNG, max 5MB fiecare)</label>
            <input type="file" name="poze_retur[]" multiple accept="image/jpeg,image/png,image/jpg" style="width:100%;">
            <small style="color:#666;">Pozele ajută la procesarea mai rapidă a cererii tale.</small>
        </p>
        
        <p class="form-row">
            <button type="submit" name="submit_retur" class="button">Trimite cererea de retur</button>
        </p>
    </form>
    
    <script>
    jQuery(document).ready(function($) {
        // Limitează la 5 fișiere
        $('input[name="poze_retur[]"]').on('change', function() {
            if(this.files.length > 5) {
                alert('Poți încărca maxim 5 poze.');
                this.value = '';
            }
        });
        
        $('#select_order_retur').on('change', function() {
            var orderId = $(this).val();
            var productSelect = $('#select_product_retur');
            $('#qty_retur_row').hide();
            
            if(!orderId) {
                productSelect.html('<option value="">-- Alege mai întâi comanda --</option>');
                return;
            }
            
            productSelect.html('<option value="">Se încarcă...</option>');
            
            $.ajax({
                url: '<?php echo admin_url("admin-ajax.php"); ?>',
                type: 'POST',
                data: {
                    action: 'get_order_products_retur',
                    order_id: orderId
                },
                success: function(response) {
                    if(response.success) {
                        var options = '<option value="">-- Alege produsul --</option>';
                        if(response.data.length === 0) {
                            options = '<option value="">-- Toate produsele au fost deja returnate --</option>';
                        } else {
                            $.each(response.data, function(i, product) {
                                options += '<option value="' + product.id + '" data-qty="' + product.qty_available + '">' + product.name + ' (disponibil: ' + product.qty_available + ' buc)</option>';
                            });
                        }
                        productSelect.html(options);
                    }
                }
            });
        });
        
        $('#select_product_retur').on('change', function() {
            var selected = $(this).find(':selected');
            var qtyAvailable = selected.data('qty');
            var qtySelect = $('#select_qty_retur');
            
            if(qtyAvailable && qtyAvailable > 0) {
                var options = '';
                for(var i = 1; i <= qtyAvailable; i++) {
                    options += '<option value="' + i + '">' + i + ' bucată' + (i > 1 ? 'ți' : '') + '</option>';
                }
                qtySelect.html(options);
                $('#qty_retur_row').show();
            } else {
                $('#qty_retur_row').hide();
            }
        });
    });
    </script>
    <?php
});

// AJAX pentru produse retur
add_action('wp_ajax_get_order_products_retur', function() {
    $order_id = intval($_POST['order_id']);
    $order = wc_get_order($order_id);
    $customer_id = get_current_user_id();
    
    if(!$order || $order->get_customer_id() !== $customer_id) {
        wp_send_json_error();
    }
    
    $products = array();
    foreach($order->get_items() as $item_id => $item) {
        $product_id = $item->get_product_id();
        $qty_ordered = $item->get_quantity();
        
        $qty_returned = get_qty_returned($order_id, $product_id, $customer_id);
        $qty_available = $qty_ordered - $qty_returned;
        
        if($qty_available > 0) {
            $products[] = array(
                'id' => $product_id,
                'item_id' => $item_id,
                'name' => $item->get_name(),
                'qty_ordered' => $qty_ordered,
                'qty_available' => $qty_available
            );
        }
    }
    
    wp_send_json_success($products);
});

// AJAX pentru descărcare storno PDF
add_action('wp_ajax_download_storno_pdf', function() {
    if(!is_user_logged_in()) {
        wp_die('Neautorizat');
    }
    
    $retur_id = intval($_GET['retur_id']);
    $retur = get_post($retur_id);
    
    // Verifică dacă returul aparține userului curent sau e admin
    if(!$retur || ($retur->post_author != get_current_user_id() && !current_user_can('manage_woocommerce'))) {
        wp_die('Acces interzis');
    }
    
    $series = get_post_meta($retur_id, '_smartbill_storno_series', true);
    $number = get_post_meta($retur_id, '_smartbill_storno_number', true);
    
    if(!$series || !$number) {
        wp_die('Storno-ul nu există');
    }
    
    $url = SMARTBILL_API_URL . 'invoice/pdf?cif=' . SMARTBILL_CIF . '&seriesname=' . $series . '&number=' . $number;
    
    $args = array(
        'method' => 'GET',
        'timeout' => 30,
        'headers' => array(
            'Authorization' => 'Basic ' . base64_encode(SMARTBILL_USERNAME . ':' . SMARTBILL_TOKEN),
            'Accept' => 'application/octet-stream'
        )
    );
    
    $response = wp_remote_get($url, $args);
    
    if(is_wp_error($response)) {
        wp_die('Eroare la descărcare');
    }
    
    $pdf = wp_remote_retrieve_body($response);
    
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="Storno_' . $series . $number . '.pdf"');
    header('Content-Length: ' . strlen($pdf));
    
    echo $pdf;
    exit;
});

// Coloane admin pentru Cereri Retur
add_filter('manage_cerere_retur_posts_columns', function($columns) {
    return array(
        'cb' => $columns['cb'],
        'title' => 'Cerere',
        'order_id' => 'Comandă',
        'product' => 'Produs',
        'qty_retur' => 'Cantitate',
        'tip_retur' => 'Tip',
        'poze' => 'Poze',
        'status_retur' => 'Status',
        'storno' => 'Storno',
        'date' => 'Data'
    );
});

add_action('manage_cerere_retur_posts_custom_column', function($column, $post_id) {
    switch($column) {
        case 'order_id':
            $order_id = get_post_meta($post_id, '_order_id', true);
            echo '<a href="' . admin_url('post.php?post=' . $order_id . '&action=edit') . '">#' . $order_id . '</a>';
            break;
        case 'product':
            $product_id = get_post_meta($post_id, '_product_id', true);
            $product = wc_get_product($product_id);
            echo $product ? $product->get_name() : '-';
            break;
        case 'qty_retur':
            $qty = get_post_meta($post_id, '_qty_retur', true);
            echo ($qty ? $qty : 1) . ' buc';
            break;
        case 'tip_retur':
            echo ucfirst(get_post_meta($post_id, '_tip_retur', true));
            break;
        case 'poze':
            $poze = get_post_meta($post_id, '_poze_retur', true);
            echo $poze ? count($poze) . ' poze' : '-';
            break;
        case 'status_retur':
            $status = get_post_meta($post_id, '_status_retur', true);
            $colors = array('nou' => 'orange', 'aprobat' => 'green', 'respins' => 'red', 'finalizat' => 'blue');
            echo '<span style="color:' . ($colors[$status] ?? 'black') . '; font-weight:bold;">' . ucfirst($status) . '</span>';
            break;
        case 'storno':
            $storno_number = get_post_meta($post_id, '_smartbill_storno_number', true);
            $storno_series = get_post_meta($post_id, '_smartbill_storno_series', true);
            if($storno_number) {
                echo '<a href="' . admin_url('admin-ajax.php?action=download_storno_pdf&retur_id=' . $post_id) . '" target="_blank">' . $storno_series . $storno_number . '</a>';
            } else {
                echo '-';
            }
            break;
    }
}, 10, 2);

// Meta box pentru editare retur în admin
add_action('add_meta_boxes', function() {
    add_meta_box('retur_details', 'Detalii Retur', 'render_retur_metabox', 'cerere_retur', 'normal', 'high');
});

function render_retur_metabox($post) {
    $order_id = get_post_meta($post->ID, '_order_id', true);
    $product_id = get_post_meta($post->ID, '_product_id', true);
    $qty = get_post_meta($post->ID, '_qty_retur', true);
    $motiv = get_post_meta($post->ID, '_motiv_retur', true);
    $tip = get_post_meta($post->ID, '_tip_retur', true);
    $status = get_post_meta($post->ID, '_status_retur', true);
    $customer_id = get_post_meta($post->ID, '_customer_id', true);
    $poze = get_post_meta($post->ID, '_poze_retur', true);
    
    // Storno info
    $storno_number = get_post_meta($post->ID, '_smartbill_storno_number', true);
    $storno_series = get_post_meta($post->ID, '_smartbill_storno_series', true);
    $storno_date = get_post_meta($post->ID, '_smartbill_storno_date', true);
    
    $customer = get_user_by('id', $customer_id);
    $product = wc_get_product($product_id);
    ?>
    <table class="form-table">
        <tr>
            <th>Client:</th>
            <td><?php echo $customer ? $customer->display_name . ' (' . $customer->user_email . ')' : '-'; ?></td>
        </tr>
        <tr>
            <th>Comandă:</th>
            <td><a href="<?php echo admin_url('post.php?post=' . $order_id . '&action=edit'); ?>">#<?php echo $order_id; ?></a></td>
        </tr>
        <tr>
            <th>Produs:</th>
            <td><?php echo $product ? $product->get_name() : '-'; ?></td>
        </tr>
        <tr>
            <th>Cantitate returnată:</th>
            <td><strong><?php echo $qty ? $qty : 1; ?> bucăți</strong></td>
        </tr>
        <tr>
            <th>Tip retur:</th>
            <td><?php echo ucfirst($tip); ?></td>
        </tr>
        <tr>
            <th>Motiv:</th>
            <td style="background:#f9f9f9; padding:10px;"><?php echo nl2br(esc_html($motiv)); ?></td>
        </tr>
        <?php if($poze && is_array($poze)): ?>
        <tr>
            <th>Poze atașate:</th>
            <td>
                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <?php foreach($poze as $poza_id): 
                    $url = wp_get_attachment_url($poza_id);
                    if($url):
                ?>
                    <a href="<?php echo $url; ?>" target="_blank">
                        <img src="<?php echo $url; ?>" style="max-width:150px; max-height:150px; border:1px solid #ddd; border-radius:4px;">
                    </a>
                <?php endif; endforeach; ?>
                </div>
            </td>
        </tr>
        <?php endif; ?>
        <?php if($storno_number): ?>
        <tr>
            <th>Factură Storno:</th>
            <td>
                <strong><?php echo $storno_series . $storno_number; ?></strong> (<?php echo date('d.m.Y', strtotime($storno_date)); ?>)
                <br><a href="<?php echo admin_url('admin-ajax.php?action=download_storno_pdf&retur_id=' . $post->ID); ?>" class="button" target="_blank">📄 Descarcă PDF</a>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <th>Status:</th>
            <td>
                <select name="status_retur" style="width:200px;">
                    <option value="nou" <?php selected($status, 'nou'); ?>>Nou</option>
                    <option value="aprobat" <?php selected($status, 'aprobat'); ?>>Aprobat</option>
                    <option value="respins" <?php selected($status, 'respins'); ?>>Respins</option>
                    <option value="finalizat" <?php selected($status, 'finalizat'); ?>>Finalizat</option>
                </select>
                <?php if(!$storno_number && $status !== 'finalizat'): ?>
                <p class="description">Cand Statusul Finalizat este selectat  se va genera automat factura storno în SmartBill.</p>
                <?php endif; ?>
            </td>
        </tr>
    </table>
    <?php
}
