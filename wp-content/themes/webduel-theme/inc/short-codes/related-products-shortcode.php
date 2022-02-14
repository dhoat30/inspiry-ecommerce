<?php 
// related products logic 
function relatedProductLoopShortCode(){ 
    global $product; 
    $childCategorySlug = "null"; 
    $categories = get_the_terms( $product->get_id(), 'product_cat' ); 
    //  print_r($categories);
    if ( $categories  ){
        // loop through each cat
        foreach($categories as $category) {
          // get the children (if any) of the current cat
          $children = get_categories( array ('taxonomy' => 'product_cat', 'parent' => $category->term_id ));
          if ( count($children) == 0 && $category->parent > 0 ) {
              // if no children, then echo the category name.
              $childCategorySlug = $category->slug; 
          }
        }
    }
    
    // echo $childCategorySlug; 
    $brand = array_shift( wc_get_product_terms( $product->id, 'pa_brands', array( 'fields' => 'names' ) ) );
    echo '
    <section class="trending-section  margin-row row-container">
        <h3 class="title">You may also like</h3>
    '; 

   echo  '<div class="related-product-section owl-carousel">'; 
        $argsLoving = array(
            'post_type' => 'product',
            'posts_per_page'=>12,
                'orderby' => 'date', 
                'order' => 'ASC',
                'tax_query' => array(
                    'relation'=> 'OR', 
                    array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'slug',
                        'terms'    => array($childCategorySlug),
                    )
                    ), 
        );
        $loving = new WP_Query( $argsLoving );

        while($loving->have_posts()){ 
            $loving->the_post(); 
            $product = wc_get_product( get_the_ID() );

       echo  '
        <a class="card" href="'.get_the_permalink().'">
            <div class="image-container">
                <img 
                loading="lazy" 
                src="'. get_the_post_thumbnail_url(null,"woocommerce_thumbnail").'" 
                alt="'.get_the_title().'"/>
            </div>
            <h5 >'.get_the_title().'</h5>
            <h6 class="price">'.$product->get_price_html().'</h6>
         </a>'; 
        }
        wp_reset_postdata();
       
   echo "</div>"; 
   echo "</section>"; 
}

add_shortcode('related_product_loop_short_code', 'relatedProductLoopShortCode'); 

