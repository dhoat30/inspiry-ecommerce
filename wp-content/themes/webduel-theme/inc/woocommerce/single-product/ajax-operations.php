<?php   
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
// show live cart item number in the header on add to cart
    add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_item_fragment');

    function woocommerce_header_add_to_cart_item_fragment( $fragments ) {
        global $woocommerce;
        ob_start();
        ?>
        <span class="cart-item-count">Cart (<?php echo WC()->cart->get_cart_contents_count(); ?>)</span>
        <?php
        $fragments['span.cart-item-count'] = ob_get_clean();
        return $fragments;
    }


//add to cart ajax
       /**
 * Show cart contents / total Ajax
 */

add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );

function woocommerce_header_add_to_cart_fragment( $fragments ) {
  global $woocommerce;

  ob_start();

  ?>
     <div class="cart-box">
            <div class="flex-card">
                <?php

                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                        $product = $cart_item['data'];
                        $product_id = $product_id = $cart_item['product_id'];
                        
                        $quantity = $cart_item['quantity'];
                        $price = WC()->cart->get_product_price( $product );
                        $subtotal = WC()->cart->get_product_subtotal( $product, $cart_item['quantity'] );
                        $link = $product->get_permalink( $cart_item );
                        // Anything related to $product, check $product tutorial
                        $meta = wc_get_formatted_cart_item_data( $cart_item );
                    
                    ?>
                    <div class="product-card">
                        <?php 

                        // condition to check if the product is simple
                        if($product->name == "Free Sample"){
                                // pulling information of an original product in a form of an objec†
                            $originalProduct = wc_get_product( $cart_item["free_sample"] );

                            if(!empty($originalProduct)){
                                $permalink = get_the_permalink($originalProduct->get_id()); 
                                $imageID = $originalProduct->image_id; 
                                $name = $originalProduct->get_name();
                            }
                        ?>
                            <a href="<?php echo $permalink; ?>"  class="mini_cart_item">
                                <div class="img-container">
                                    <img src="<?php echo wp_get_attachment_image_url( $imageID , 'woocommerce_thumbnail' );?>" alt="<?php echo $name;?>"/>
                                </div>

                                <div class="title-container">
                                    <h5 class="paragraph-font-size regular"> <?php echo $quantity;?> X  Free Sample (<?php echo $name; ?> )</h5>
                                </div>
                                    
                                <div class="price-container">
                                    <h6 class="paragraph-font-size poppins-font bold">$<?php echo number_format($product->price * $quantity) ?></h6>
                                </div>
                            </a>
                            <?php
                        }
                        else{
                            ?>
                            <a href="<?php echo $link?>"  class="mini_cart_item">    
                                <div class="img-container">
                                    <img src="<?php echo get_the_post_thumbnail_url($product_id, 'medium');?>" alt="<?php echo $product->name?>"/>
                                </div>
                                <div class="title-container">
                                    <h5 class="paragraph-font-size regular"> <?php echo $quantity;?> X  <?php echo $product->name?></h5>
                                </div>
                                    
                                <div class="price-container">
                                    <h6 class="paragraph-font-size poppins-font bold">$<?php echo number_format($product->price * $quantity); ?></h6>
                                </div>    
                            </a>
                            <?php
                        }?>
                    </div>
                        <?php 
                }   
                        ?>
                    

                    <div class="pop-up-footer">
                        <div class="total-container">
                            <div class="total poppins-font">
                                Total: $<?php 
                                $totalAmount = str_replace(".00", "", (string)number_format (WC()->cart->total, 2, ".", ""));
                                echo number_format($totalAmount); ?>
                            </div>
                        </div>
                        <div class="cont-shopping">
                            <a class="rm-txt-dec button btn-dk-green-border btn-full-width center-align" href="#">Continue Shopping</a>
                        </div>
                        <div class="checkout-btn">
                            <a class="rm-txt-dec button btn-dk-green btn-full-width center-align checkout-btn-header" href="<?php echo get_site_url();?>/cart">Checkout</a>
                        </div>
                    </div>
            </div>
        </div>	
 <?php
  $fragments['.cart-box'] = ob_get_clean();
  return $fragments;
}

// remove item from cart pop up --ajax
// Remove product in the cart using ajax
// function warp_ajax_product_remove()
// {
//     // Get mini cart
//     ob_start();

//     foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item)
//     {
//         if($cart_item['product_id'] == $_POST['product_id'] && $cart_item_key == $_POST['cart_item_key'] )
//         {
//             WC()->cart->remove_cart_item($cart_item_key);
//         }
//         else if ($cart_item['variation_id'] == $_POST['product_id'] && $cart_item_key == $_POST['cart_item_key'] ){ 
//             WC()->cart->remove_cart_item($cart_item_key);
//         }
//     }

//     WC()->cart->calculate_totals();
//     WC()->cart->maybe_set_cart_cookies();

//     woocommerce_mini_cart();

//     $mini_cart = ob_get_clean();

//     // Fragments and mini cart are returned
//     $data = array(
//         'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', array(
//                 'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>'
//             )
//         ),
//         'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() )
//     );

//     wp_send_json( $data );

//     die();
// }

// add_action( 'wp_ajax_product_remove', 'warp_ajax_product_remove' );
// add_action( 'wp_ajax_nopriv_product_remove', 'warp_ajax_product_remove' );