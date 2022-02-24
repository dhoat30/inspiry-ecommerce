<?php
// add to cart  Ajax -------------------------------------------------------------
add_action('wp_ajax_woocommerce_ajax_update_cart', 'woocommerce_ajax_update_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_update_cart', 'woocommerce_ajax_update_cart');
        
function woocommerce_ajax_update_cart() {

            $cartItemKey = sanitize_text_field($_POST['cartItemKey']);
            $qty = sanitize_text_field($_POST['qty']);
            global $woocommerce;

            $woocommerce->cart->set_quantity( $cartItemKey, $qty, false );
            $woocommerce->cart->calculate_totals();
            $productPrice = ''; 
            $regularPrice = ''; 
            $salePrice = ''; 
            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                if($cart_item_key === $cartItemKey){ 
                    $product = $cart_item['data'];
                    $regularPrice = $product->regular_price; 
                    $productPrice = $product->price; 
                    $salePrice = $product->get_sale_price();
                }
            } 
            $dataArray = array(
                'code'=>  200, 
                'subtotal'=> WC()->cart->subtotal, 
                'total'=> WC()->cart->get_total(), 
                'tax' => WC()->cart->get_taxes_total(), 
                'shipping'=> WC()->cart->get_shipping_total() + WC()->cart->get_shipping_taxes()[1], 
                'productPrice'=> $productPrice, 
                'regularPrice' => $regularPrice, 
                'salePrice'=> $salePrice
            );
            echo wp_send_json($dataArray);

           wp_die();
}

// add coupon  Ajax -------------------------------------------------------------
add_action('wp_ajax_woocommerce_ajax_add_coupon', 'woocommerce_ajax_add_coupon');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_coupon', 'woocommerce_ajax_add_coupon');
        
function woocommerce_ajax_add_coupon() {

            $couponCode = $_POST['couponCode'];
            global $woocommerce;
            
            if($couponCode === 'remove'){ 
                WC()->cart->remove_coupons();
                    $dataArray = array(
                        'code'=>  202, 
                        'couponCode'=> $couponCode, 
                        'subtotal'=> WC()->cart->subtotal, 
                        'total'=> WC()->cart->get_total(), 
                        'tax' => WC()->cart->get_taxes_total(), 
                        'shipping'=> WC()->cart->get_shipping_total() + WC()->cart->get_shipping_taxes()[1]
                    );
                     echo wp_send_json($dataArray);
            }
            else{ 
                WC()->cart->remove_coupons();
                $ret = WC()->cart->add_discount( $couponCode ); 
                if($ret){ 
                    $dataArray = array(
                        'code'=>  200, 
                        'couponCode'=> $couponCode, 
                        'subtotal'=> WC()->cart->subtotal, 
                    'total'=> WC()->cart->get_total(), 
                    'tax' => WC()->cart->get_taxes_total(), 
                    'shipping'=> WC()->cart->get_shipping_total() + WC()->cart->get_shipping_taxes()[1]
                    );
                     echo wp_send_json($dataArray);
                }
            }
           
           wp_die();
}

// testing windcave  -------------------------------------------------------------
add_action('wp_ajax_windcave_validation', 'windcave_validation');
add_action('wp_ajax_nopriv_windcave_validation', 'windcave_validation');
        
function windcave_validation() {
            // Mark as on-hold (we're awaiting the payment)
            $order = wc_get_order(455803 );
            $order->update_status( 'processing', __( 'Awaiting offline payment', 'wc-gateway-offline' ) );
                        
            // Reduce stock levels
            $order->reduce_order_stock();
            
            // Remove cart
            WC()->cart->empty_cart();
            // Redirect to the thank you page
			return array(
				'result' => 'success',
				'redirect' =>'https://inspiry.local/'
			);
 
            echo wp_send_json(20);
           wp_die();
}