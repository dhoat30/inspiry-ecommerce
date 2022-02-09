<?php

// remove sku code
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

// remove sidebar 
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

/**
 * Remove product page tabs
 */
add_filter( 'woocommerce_product_tabs', 'my_remove_all_product_tabs', 98 );
 
function my_remove_all_product_tabs( $tabs ) {
  unset( $tabs['description'] );        // Remove the description tab
  unset( $tabs['reviews'] );       // Remove the reviews tab
  unset( $tabs['additional_information'] );    // Remove the additional information tab
  return $tabs;
}

// price ------------------------------------------------------------------------------------------
// Trim zeros in price decimals
add_filter( 'woocommerce_price_trim_zeros', '__return_true' ); 

// remove short description on single product page
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );


// add free delivery tag 
add_action('woocommerce_single_product_summary', function(){ 
    echo '<div class="price-flex">';
}, 5); 
add_action('woocommerce_single_product_summary', function(){ 
    global $product; 
    if($product->get_shipping_class()=== "free-shipping"){ 
        echo '<div class="free-shipping">
        <i class="fa-solid fa-cube"></i>
        Free Delivery
        </div>'; 
    }
    echo '
    </div>
    ';

}, 10); 