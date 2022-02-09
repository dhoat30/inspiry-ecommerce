<?php 
// remove sale badge
add_filter('woocommerce_sale_flash', 'webduel_hide_sale_flash');
function webduel_hide_sale_flash(){
return false;
}

// remove default product images
// remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20); 


// change breadcrumb location
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
// add images 
// add_action('woocommerce_before_single_product_summary', function(){ 
//     global $product; 
//     $attachment_ids = $product->get_gallery_image_ids();
//     echo '<div class="product-images">'; 
//     woocommerce_breadcrumb();
//     //   thumbnails 
//     echo '<div class="splide" id="thumbnail-slider">
//     <div class="splide__track">
//           <ul class="splide__list">
//           <li class="splide__slide"><img  alt="'.get_the_title().'" data-splide-lazy="'.wp_get_attachment_image_src($product->get_image_id(), "woocommerce_gallery_thumbnail")[0].'" /></li>'; 
//               foreach( $attachment_ids as $attachment_id ) 
//                     {
                    
//                         echo ' <li class="splide__slide"> 
//                         <img  alt="'.get_the_title().'" 
//                         data-splide-lazy="'.wp_get_attachment_image_src($attachment_id, "woocommerce_gallery_thumbnail")[0].'" />
//                         ';  
//                          // Display Image instead of URL
//                          }; 
//     echo '
//            </li>
//           </ul>
//     </div>
//   </div>'; 
//     // main image
//     echo '<div class="splide" id="main-slider">
//     <div class="splide__track">
//           <ul class="splide__list">
//               <li class="splide__slide"  id="main-woo-image">
//               <img class="single-product-image"  alt="'.get_the_title().'" 
//               sizes="100vw"
//               data-zoom="'.wp_get_attachment_image_src($product->get_image_id(), "woocommerce_single")[0].'"
//               data-splide-lazy-srcset="'.wp_get_attachment_image_src($product->get_image_id(), "woocommerce_thumbnail")[0].' 500w, '.wp_get_attachment_image_src($product->get_image_id(), "woocommerce_single")[0].' 900w"
//               data-splide-lazy="'.wp_get_attachment_image_src($product->get_image_id(), "woocommerce_single")[0].'" />
//               </li>'; 
//                             foreach( $attachment_ids as $attachment_id ) 
//                     {
                    
//                    echo ' <li class="splide__slide"  > 
//                    <img  
//                    class="single-product-image"
//                    alt="'.get_the_title().'" 
//                    sizes="100vw"
//                    data-zoom="'.wp_get_attachment_image_src($product->get_image_id(), "woocommerce_single")[0].'"
//                    data-splide-lazy-srcset="'.wp_get_attachment_image_src($attachment_id, "woocommerce_thumbnail")[0].' 500w, '.wp_get_attachment_image_src($attachment_id, "woocommerce_single")[0].' 900w"
//                    data-splide-lazy="'.wp_get_attachment_image_src($attachment_id, "woocommerce_single")[0].'" />
//                    ';  
//                     // Display Image instead of URL
//                     }; 
//     echo '
//            </li>
//           </ul>
//     </div>
//   </div>';

//   echo '</div>'; 
  
// }, 30);






?>

