<?php 

add_action('woocommerce_single_product_summary', function() { 

    echo '
    <div class="accordion-container">'; 
        productDescription(); 
        productDetails();
        productShippingReturn();
    echo '
   
    </div>'; 
    // echo  $product->get_description();

}, 70); 

function productDescription(){ 
    global $product; 
    echo '
    <div class="item">
        <h2 class="title">
            Description
            <span>+</span>
        </h2>
        <div class="content">
        '. $product->get_description().'
        </div> 
    </div>'; 
}
function productDetails(){
    global $product; 
  
    $attributesArr = array(
        'Brand Name'=> $product->get_attribute( 'pa_brand-name' ), 
        'Collection'=> $product->get_attribute( 'pa_collection' ),
        'Colour'=> $product->get_attribute('pa_colour'), 
        'Design Name'=> $product->get_attribute( 'pa_design-name' ), 
        'Design Style'=> $product->get_attribute( 'pa_design-style' ), 
        'Pattern' => $product->get_attribute( 'pa_pattern' ), 
        'Composition'=> $product->get_attribute('pa_composition'), 
        'Construction'=> $product->get_attribute('pa_construction'), 
        'Dimensions'=> $product->get_attribute('pa_dimensions'), 
        'Dimensions'=> $product->get_attribute('pa_dimensions'), 
    );

    echo '
    <div class="item">
        <h2 class="title">
            Details
            <span>+</span>
        </h2>
        <div class="content">
            <table class="woocommerce-product-attributes shop_attributes">
                <tbody>'; 
                foreach ($attributesArr as $key => $value) {
                    if($value){ 
                        echo '
                        <tr>
                            <th class="woocommerce-product-attributes-item__label">'.$key.'</th>
                            <td class="woocommerce-product-attributes-item__value">'.$value.'<td>
                        </tr>'; 
                    }
                   
                   }
                echo '
                </tbody>
            </table>
        </div> 
    </div>'; 
}

function productShippingReturn(){ 
    global $product; 
    echo '
    <div class="item">
        <h2 class="title">
            Shipping & Returns
            <span>+</span>
        </h2>
        <div class="content">
        '. $product->get_description().'
        </div> 
    </div>'; 
}