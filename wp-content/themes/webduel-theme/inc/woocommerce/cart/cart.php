<?php 

// add banner on cart page 
add_action('webduel_hero_section', function(){ 
    if(is_cart() ){ 
        $imageLarge = get_the_post_thumbnail_url(get_the_id(), 'large' );
       echo '<img src="'.$imageLarge.'"/>'; 
  
    }
      
}, 10);

// add page title
add_action('woocommerce_before_cart', function(){ 
    echo '<h1>My Cart</h1>'; 
}, 20); 

add_action('woocommerce_before_cart', function(){ 
    ?>
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
                foreach ( $product->get_available_variations() as $key ) {
                    print_r($key);
                    // $variation = wc_get_product( $key['variation_id'] );
                    // $stock = $variation->get_availability();
                    // $stock_string = $stock['availability'] ? $stock['availability'] : __( 'In stock', 'woocommerce' );
                    // $attr_string = array();
                    // foreach ( $key['attributes'] as $attr_name => $attr_value ) {
                    //     $attr_string[] = $attr_value;
                    // }
                    // echo '<br/>' . implode( ', ', $attr_string ) . ': ' . $stock_string;
                }
                $delivery = wc_get_product_terms( $cart_item['product_id'], 'pa_delivery' )[0]->name;
                $availability = ""; 
                $product_id = ''; 
                        if($cart_item['data']->post_type ==='product_variation'){ 
                            $product_id =  $cart_item['variation_id']; 
                            $variation = wc_get_product( $product_id );
                            $availability = $variation->get_availability();
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
                <tr>
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
                        <h3><?php echo $quantity;?> </h3>
                    </td>
                    <td class="price-column" >
                        <div class="price-container">
                            <?php 
                            if($product->price !== $regularPrice ){ 
                            ?>
                                <h4 class="regular-price striked" >$<?php echo $regularPrice;?></h4>
                                <h5 class="sale-price">$<?php echo $product->price; ?></h5>
                            <?php 
                            }
                            else{ 
                                ?>
                                <h4 class="regular-price" >$<?php echo $product->price;?></h4>
                                <?php 
                            }
                            ?>
                        </div>
                    </td>
                    <td class="remove-column" > 
                        <i class="fa-thin fa-xmark"></i>
                    </td>
                </tr>
                <?php 
            }
                ?>
        </tbody>
    </table>
    <?php 
}, 30); 