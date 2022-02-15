<?php 
// remove add to cart button on loop product page
add_action( 'woocommerce_after_shop_loop_item', 'remove_add_to_cart_buttons', 1 );
    function remove_add_to_cart_buttons() {
      if( is_product_category() || is_shop() || is_archive() ) { 
        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
        remove_action( 'woocommerce_after_shop_loop_item', 'wvs_pro_archive_variation_template', 30 );
      }
    }


// woocommerce variations 

add_action('woocommerce_shop_loop_item_title', 'wvs_pro_archive_variation_template', 5); 

// add product attributes on product loop product page
add_action("woocommerce_after_shop_loop_item_title", "add_product_attributes", 1); 

function add_product_attributes(){ 
   echo do_shortcode('[add_free_shipping_tag]'); 
     // add free shipping 
   echo do_shortcode(('[add_deal_text]'));
}

// show percentage discount on product loop page
add_action( 'woocommerce_after_shop_loop_item', 'webduel_show_sale_percentage_loop', 1 );
add_action("woocommerce_single_product_summary", 'webduel_show_sale_percentage_loop', 10);
function webduel_show_sale_percentage_loop() {
   global $product;
   if ( ! $product->is_on_sale() ) return;
   if ( $product->is_type( 'simple' ) ) {
      $max_percentage = ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100;
   } elseif ( $product->is_type( 'variable' ) ) {
      $max_percentage = 0;
      foreach ( $product->get_children() as $child_id ) {
         $variation = wc_get_product( $child_id );
         $price = $variation->get_regular_price();
         $sale = $variation->get_sale_price();
         if ( $price != 0 && ! empty( $sale ) ) $percentage = ( $price - $sale ) / $price * 100;
         if ( $percentage > $max_percentage ) {
            $max_percentage = $percentage;
         }
      }
   }
   if ( $max_percentage > 0 ) echo "<div class='sale-perc'>(" . round($max_percentage) . "% OFF)</div>"; 
}
