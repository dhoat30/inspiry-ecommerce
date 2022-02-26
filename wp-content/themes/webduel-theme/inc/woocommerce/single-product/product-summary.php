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


// availability section _--------------------------------------------------------------

// add_action('woocommerce_single_product_summary', function (){ 
//     global $product;    
//     // Available on backorder
//     $countryOfOrigin = $product->get_attribute( 'pa_country-of-origin' );
//     $deliveryETA = $product->get_attribute( 'pa_availability' );
//     if($countryOfOrigin){ 
//         $countryOfOrigin = 'Country Of Origin: '.$countryOfOrigin; 
//     }
//     echo '
//     <div class="availability">
//         <h3 class="title">'; 
//         if($product->get_availability()['class'] === 'in-stock'){ 
//            echo ' 
//             <i class="fa-solid fa-circle-check" style="color: var(--green); "></i>
//             <span  style="color: var(--green); ">
//             IN STOCK
//             </span>
//             '; 

//         }
//         else{ 
//             echo ' 
//             <i class="fa-solid fa-circle-check" style="color: var(--orange); "></i>
//             <span  style="color: var(--orange); ">
//             Pre Order
//             </span>
//             '; 
//         }
//         echo '
//         <h3>
//         <div class="content">
//             <h4 class="subtitle">
//                 '.$countryOfOrigin.'
//             </h4>
//             <h5 class="delivery-info">
//                 '.$deliveryETA.'
//             </h5>
//         </div>
//     </div>'; 
// }, 60) ; 

// social share -----------------------------------------------------------
add_action('woocommerce_single_product_summary', function(){
    echo '
    <div class="social-share-container">
        <p>Share:</p>
    ';  
    echo do_shortcode('[webduelSocialShare]');
    echo '</div>';
}, 80); 

// add variation availability data before add to cart button ---------------------------------
function iconic_output_engraving_field() {
	
    global $product;
    echo $product->get_stock_quantity();

    // $product->is_type( $type ) checks the product type, string/array $type ( 'simple', 'grouped', 'variable', 'external' ), returns boolean
    
    if ( $product->is_type( 'variable' ) ) {
        $dataArray = array(); 
        foreach($product->get_available_variations() as $key){
            $variation = wc_get_product( $key['variation_id'] );
            $stock = $variation->get_availability();
            
            array_push($dataArray, array(
                "variation_id" => $key['variation_id'], 
                "availability"=>$stock['class']
             )); 
        }
        
        ?>
        <div class="variation-availability-data" 
        data-variation_availability='<?php   print_r(json_encode($dataArray)); ?>'>
        </div>
        <?php
       
    }
}

add_action( 'woocommerce_before_add_to_cart_button', 'iconic_output_engraving_field', 10 );