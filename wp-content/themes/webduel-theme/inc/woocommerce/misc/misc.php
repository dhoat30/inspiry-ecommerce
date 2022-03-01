<?php 
//attributes links - show brand attributes in menu 
add_filter('woocommerce_attribute_show_in_nav_menus', 'wc_reg_for_menus', 1, 2);

function wc_reg_for_menus( $register, $name = '' ) {
     if ( $name == 'pa_brands' ) $register = true;
     return $register;
}
