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

// add to cart  Ajax -------------------------------------------------------------
add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
        
function woocommerce_ajax_add_to_cart() {

            $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
            $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
            $variation_id = absint($_POST['variation_id']);
            $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
            $product_status = get_post_status($product_id);

            if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {

                do_action('woocommerce_ajax_added_to_cart', $product_id);

                if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
                    wc_add_to_cart_message(array($product_id => $quantity), true);
                }

                WC_AJAX :: get_refreshed_fragments();
            } else {

                $data = array(
                    'error' => true,
                    'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));

                echo wp_send_json($data);
            }

            wp_die();
        }

    

// availability section _--------------------------------------------------------------

add_action('woocommerce_single_product_summary', function (){ 
    global $product;    
    // Available on backorder
    $countryOfOrigin = $product->get_attribute( 'pa_country-of-origin' );
    $deliveryETA = $product->get_attribute( 'pa_delivery' );
    if($countryOfOrigin){ 
        $countryOfOrigin = 'Country Of Origin: '.$countryOfOrigin; 
    }
    echo '
    <div class="availability">
        <h3 class="title">'; 
        if($product->get_availability()['class'] === 'in-stock'){ 
           echo ' 
            <i class="fa-solid fa-circle-check" style="color: var(--green); "></i>
            <span  style="color: var(--green); ">
            IN STOCK
            </span>
            '; 

        }
        else{ 
            echo ' 
            <i class="fa-solid fa-circle-check" style="color: var(--orange); "></i>
            <span  style="color: var(--orange); ">
            Available on backorder
            </span>
            '; 
        }
        echo '
        <h3>
        <div class="content">
            <h4 class="subtitle">
                '.$countryOfOrigin.'
            </h4>
            <h5 class="delivery-info">
                '.$deliveryETA.'
            </h5>
        </div>
    </div>'; 
}, 60) ; 

// social share -----------------------------------------------------------
add_action('woocommerce_single_product_summary', function(){
    echo '
    <div class="social-share-container">
        <p>Share:</p>
    ';  
    echo do_shortcode('[webduelSocialShare]');
    echo '</div>';
}, 80); 