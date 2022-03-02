<?php 
//attributes links - show brand attributes in menu 
add_filter('woocommerce_attribute_show_in_nav_menus', 'wc_reg_for_menus', 1, 2);

function wc_reg_for_menus( $register, $name = '' ) {
     if ( $name == 'pa_brands' ) $register = true;
     return $register;
}

//yoast seo- add description if it doesn't exist 

add_filter( 'wpseo_metadesc', 'change_yoast_desc', 10, 2);

function change_yoast_desc ( $desc , $presentation ){
  global $product;
if(!$desc && $product && is_product() ){
  $desc = wp_trim_words($product->get_description(), 160);
}
  
	return $desc;
}

// add product custom field
// Display Fields
add_action('woocommerce_product_options_general_product_data', 'woocommerce_product_custom_fields');
// Save Fields
add_action('woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save');
function woocommerce_product_custom_fields()
{
    echo '<div class="product_custom_field">';
    // Custom Product Text Field
    woocommerce_wp_text_input(
        array(
            'id' => '_supplier_name',
            'placeholder' => 'Add Supplier Name',
            'label' => __('Supplier Name', 'woocommerce'),
            'desc_tip' => 'true'
        )
    );
    //Custom Product Number Field
    woocommerce_wp_text_input(
        array(
            'id' => '_product_cost',
            'placeholder' => 'Add Product Cost',
            'label' => __('Product Cost', 'woocommerce'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '0'
            )
        )
    );
    
    echo '</div>';
}


function woocommerce_product_custom_fields_save($post_id)
{
    // Custom Product Text Field
    $woocommerce_custom_supplier_name = $_POST['_supplier_name'];
    if (!empty($woocommerce_custom_supplier_name))
        update_post_meta($post_id, '_supplier_name', esc_attr($woocommerce_custom_supplier_name));
    // Custom Product Number Field
    $woocommerce_product_cost = $_POST['_product_cost'];
    if (!empty($woocommerce_product_cost))
        update_post_meta($post_id, '_product_cost', esc_attr($woocommerce_product_cost));
}
