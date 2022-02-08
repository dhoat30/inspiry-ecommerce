<?php 
// remove sale badge
add_filter('woocommerce_sale_flash', 'webduel_hide_sale_flash');
function webduel_hide_sale_flash(){
return false;
}

// add_filter ( 'woocommerce_product_thumbnails_columns', 'webduel_change_gallery_columns' );
 
// function webduel_change_gallery_columns() {
//      return 1; 
// }
// remove default product images
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20); 


// change breadcrumb location
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
// add images 
add_action('woocommerce_before_single_product_summary', function(){ 
    global $product; 
    $attachment_ids = $product->get_gallery_image_ids();
    echo '<div class="product-images">'; 
    woocommerce_breadcrumb();
    //   thumbnails 
    echo '<div class="splide" id="thumbnail-slider">
    <div class="splide__track">
          <ul class="splide__list">
          <li class="splide__slide"><img  alt="'.get_the_title().'" data-splide-lazy="'.wp_get_attachment_image_src($product->get_image_id(), "woocommerce_gallery_thumbnail")[0].'" /></li>'; 
              foreach( $attachment_ids as $attachment_id ) 
                    {
                    
                        echo ' <li class="splide__slide"> 
                        <img  alt="'.get_the_title().'" 
                        data-splide-lazy="'.wp_get_attachment_image_src($attachment_id, "woocommerce_gallery_thumbnail")[0].'" />
                        ';  
                         // Display Image instead of URL
                         }; 
    echo '
           </li>
          </ul>
    </div>
  </div>'; 
    // main image
    echo '<div class="splide" id="main-slider">
    <div class="splide__track">
          <ul class="splide__list">
              <li class="splide__slide"  id="main-woo-image">
              <img class="single-product-image"  alt="'.get_the_title().'" 
              sizes="100vw"
              data-splide-lazy-srcset="'.wp_get_attachment_image_src($product->get_image_id(), "woocommerce_thumbnail")[0].' 500w, '.wp_get_attachment_image_src($product->get_image_id(), "woocommerce_single")[0].' 900w"
              data-splide-lazy="'.wp_get_attachment_image_src($product->get_image_id(), "woocommerce_single")[0].'" />
              <div class="zoom-pane-container"> </div>
              </li>'; 
                            foreach( $attachment_ids as $attachment_id ) 
                    {
                    
                   echo ' <li class="splide__slide"  > 
                   <img  alt="'.get_the_title().'" 
                   sizes="100vw"
                   data-splide-lazy-srcset="'.wp_get_attachment_image_src($attachment_id, "woocommerce_thumbnail")[0].' 500w, '.wp_get_attachment_image_src($attachment_id, "woocommerce_single")[0].' 900w"
                   data-splide-lazy="'.wp_get_attachment_image_src($attachment_id, "woocommerce_single")[0].'" />
                   ';  
                    // Display Image instead of URL
                    }; 
    echo '
           </li>
          </ul>
    </div>
  </div>';

  echo '</div>'; 
  
}, 30);




/**
 * Remove related products output
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

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

// remove sku code
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);


// add zoom page under the title 
// add_action('woocommerce_single_product_summary', function() { 
//   echo '<div class="zoom-pane-container"> </div>';
// }, 10);
?>

