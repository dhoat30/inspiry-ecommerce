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


// add free delivery tag ----------------------------------------------
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



// add deal information on single product page --------------------------------------
add_action('woocommerce_single_variation', 'webduelModal', 10); 

function webduelModal(){ 
   if(is_product()){ 
        global $product; 
        // get current deal for the single product 
        $currentDeal = $product->get_attribute( 'pa_current-deal' );
    
        // get modal Categories
        $modalCategories = get_terms( array( 
            'taxonomy' => 'modal-categories',
            'parent'   => 0
        ) );
        // get category slug for custom post type
        $categorySlug = ''; 
        foreach ($modalCategories as $item ) {
            // process element here
            if($item->name === $currentDeal){
                $categorySlug = $item->slug; 
            }
        }
        
        singleProductQuery($categorySlug); 
   }
   
}   

// modal query 
function singleProductQuery($categorySlug){ 
       //  query modal with category slug
       $the_query = new WP_Query( array(
        'post_type' => 'modal',
        'tax_query' => array(
            array (
                'taxonomy' => 'modal-categories',
                'field' => 'slug',
                'terms' => $categorySlug
            )
        ),
    ) );
    while($the_query->have_posts()){ 
        $the_query->the_post(); 
        echo '<div class="deal-section">
               <div class="content" >
                    <div class=" title">'.get_the_title().'</div>
                    <div class="subtitle">'.get_the_content().'</div>
                </div>
            </div>
        '; 
    }
    wp_reset_postdata();
}

// add to cart  quantity input 
add_action( 'woocommerce_after_add_to_cart_quantity', 'ts_quantity_plus_sign' );
 
function ts_quantity_plus_sign() {
   echo '<button type="button" class="plus" >+</button>';
}
 
add_action( 'woocommerce_before_add_to_cart_quantity', 'ts_quantity_minus_sign' );
function ts_quantity_minus_sign() {
   echo '<button type="button" class="minus" >-</button>';
}
 
add_action( 'wp_footer', 'ts_quantity_plus_minus' );
 
function ts_quantity_plus_minus() {
   // To run this on the single product page
   if ( ! is_product() ) return;
   ?>
   <script type="text/javascript">
          
      jQuery(document).ready(function($){   
          
            $('form.cart').on( 'click', 'button.plus, button.minus', function() {
 
            // Get current quantity values
            var qty = $( this ).closest( 'form.cart' ).find( '.qty' );
            var val   = parseFloat(qty.val());
            var max = parseFloat(qty.attr( 'max' ));
            var min = parseFloat(qty.attr( 'min' ));
            var step = parseFloat(qty.attr( 'step' ));
 
            // Change the value if plus or minus
            if ( $( this ).is( '.plus' ) ) {
               if ( max && ( max <= val ) ) {
                  qty.val( max );
               } 
            else {
               qty.val( val + step );
                 }
            } 
            else {
               if ( min && ( min >= val ) ) {
                  qty.val( min );
               } 
               else if ( val > 1 ) {
                  qty.val( val - step );
               }
            }
             
         });
          
      });
          
   </script>
   <?php
}