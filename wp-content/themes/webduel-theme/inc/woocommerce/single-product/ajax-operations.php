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