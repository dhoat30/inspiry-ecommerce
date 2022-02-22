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
            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                if($cart_item_key === $cartItemKey){ 
                    $product = $cart_item['data'];
                    $regularPrice = $product->regular_price; 
                    $productPrice = $product->price; 
                }
            } 
            $dataArray = array(
                'code'=>  200, 
                'subtotal'=> WC()->cart->subtotal, 
                'total'=> WC()->cart->get_total(), 
                'tax' => WC()->cart->get_taxes_total(), 
                'shipping'=> WC()->cart->get_shipping_total() + WC()->cart->get_shipping_taxes()[1], 
                'productPrice'=> $productPrice, 
                'regularPrice' => $regularPrice
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

// refresh unit price 

add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_cart_unit_refresh' );

function woocommerce_cart_unit_refresh( $fragments ) {
  global $woocommerce;

  ob_start();

  ?>
      <div class="product-summary">
                <h1>My Cart</h1>
                <table class="cart-items-table" >
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Details</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                            $product = $cart_item['data'];
                            
                            $delivery = wc_get_product_terms( $cart_item['product_id'], 'pa_delivery' )[0]->name;
                            $availability = ""; 
                            $product_id = ''; 
                            $colourAttribute = ''; 
                            $sizeAttribute = '';  
                                    if($cart_item['data']->post_type ==='product_variation'){ 
                                        $product_id =  $cart_item['variation_id']; 
                                        $variation = wc_get_product( $product_id );
                                        $availability = $variation->get_availability();
                                        $colourAttribute = $product->get_attributes()['pa_colour'];
                                        $sizeAttribute = $product->get_attributes()['pa_sizes'];
                                        
                                    }
                                    else{ 
                                        $product_id = $cart_item['product_id'];
                                        $availability = $product->get_availability();
                                    }
                            $quantity = $cart_item['quantity'];
                            $regularPrice = $product->regular_price; 
                            $subtotal = WC()->cart->get_product_subtotal( $product, $cart_item['quantity'] );
                            $link = $product->get_permalink( $cart_item );
                    
                            ?>
                            <tr id="<?php echo $cart_item_key?>">
                                <td class="image-column">
                                    <a href="<?php echo $link?>">
                                        <div class="img-container">
                                            <img src="<?php echo get_the_post_thumbnail_url($product_id, array( 500, 500));?>" alt="<?php echo $product->name?>"/>
                                        </div>
                                    </a>
                                
                                </td>
                                <td class="product-info-column">
                                    <a href="<?php echo $link?>" class="product-title">
                                        <?php echo $product->name?>
                                    </a>
                                    <!-- variation attributes  -->
                                    <div class="variation-attributes">
                                        <?php 
                                        if($colourAttribute){ 
                                            ?>
                                            <div class="item">
                                                Color: <span><?php echo $colourAttribute; ?> </span>       
                                            </div>
                                            <?php 
                                        }
                                        ?>
                                        <?php 
                                        if($sizeAttribute){ 
                                            ?>
                                            <div class="item">
                                                Size: <span><?php echo $sizeAttribute; ?> </span>       
                                            </div>
                                            <?php 
                                        }
                                        ?>
                                    </div>
                                    <div class="availability-container">
                                        
                                        <div class="availability">
                                            <i class="fa-solid fa-cube"></i>
                                            Availability: 
                                            <span>
                                                <?php 
                                                if( $availability['class']=== 'in-stock'){ 
                                                    echo "In stock"; 
                                                }
                                                else{ 
                                                echo "Pre order";
                                                }
                                                    
                                                ?>
                                            </span>
                                        </div>
                                        <div class="arrives">
                                            Arrives: <span> <?php echo $delivery;?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="quantity-column" >
                                    <div class="quantity-container"> 
                                        <input class="minus" type="button" value="–" control-id="ControlID-1">
                                        <input 
                                        type="number" 
                                        name="quantity" 
                                        id="cart-quantity" 
                                        value="<?php echo $quantity;?>"
                                        max="25"
                                        min="1"
                                        data-cart_item_key="<?php echo $cart_item_key;?>"
                                        />
                                        <input class="plus" type="button" value="+" control-id="ControlID-3">
                                    </div>
                            
                                </td>
                                <td class="price-column" >
                                    <div class="price-container">
                                        <?php 
                                        if($product->price !== $regularPrice ){ 
                                        ?>
                                            <h4 class="regular-price striked" >$<span><?php echo $regularPrice;?></span></h4>
                                            <h5 class="sale-price">$<span><?php echo $product->price; ?></span></h5>
                                        <?php 
                                        }
                                        else{ 
                                            ?>
                                            <h4 class="regular-price" >$<span><?php echo $product->price;?></span></h4>
                                            <?php 
                                        }
                                        ?>
                                    </div>
                                </td>
                                <td class="remove-column" > 
                                    <i class="fa-light fa-xmark"></i>
                                </td>
                            </tr>
                            <?php 
                        }
                            ?>
                    </tbody>
                </table>
            </div>
 <?php
  $fragments['.product-summary'] = ob_get_clean();
  return $fragments;
}