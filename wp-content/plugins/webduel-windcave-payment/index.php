<?php 
/**
 * Plugin Name: WooCommerce Offline Gateway
 * Plugin URI: https://www.skyverge.com/?p=3343
 * Description: Clones the "Cheque" gateway to create another manual / offline payment method; can be used for testing as well.
 * Author: SkyVerge
 * Author URI: http://www.skyverge.com/
 * Version: 1.0.2
 * Text Domain: wc-gateway-offline
 * Domain Path: /i18n/languages/
 *
 * Copyright: (c) 2015-2016 SkyVerge, Inc. (info@skyverge.com) and WooCommerce
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   WC-Gateway-Offline
 * @author    SkyVerge
 * @category  Admin
 * @copyright Copyright (c) 2015-2016, SkyVerge, Inc. and WooCommerce
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 * This offline gateway forks the WooCommerce core "Cheque" payment gateway to create another offline payment method.
 */
 
defined( 'ABSPATH' ) or exit;


// Make sure WooCommerce is active
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	return;
}


/**
 * Add the gateway to WC Available Gateways
 * 
 * @since 1.0.0
 * @param array $gateways all available WC gateways
 * @return array $gateways all WC gateways + offline gateway
 */
function wc_offline_add_to_gateways( $gateways ) {
	$gateways[] = 'WC_Gateway_Offline';
	return $gateways;
}
add_filter( 'woocommerce_payment_gateways', 'wc_offline_add_to_gateways' );


/**
 * Adds plugin page links
 * 
 * @since 1.0.0
 * @param array $links all plugin links
 * @return array $links all plugin links + our custom links (i.e., "Settings")
 */
function wc_offline_gateway_plugin_links( $links ) {

	$plugin_links = array(
		'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=offline_gateway' ) . '">' . __( 'Configure', 'wc-gateway-offline' ) . '</a>'
	);

	return array_merge( $plugin_links, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wc_offline_gateway_plugin_links' );


/**
 * Offline Payment Gateway
 *
 * Provides an Offline Payment Gateway; mainly for testing purposes.
 * We load it later to ensure WC is loaded first since we're extending it.
 *
 * @class 		WC_Gateway_Offline
 * @extends		WC_Payment_Gateway
 * @version		1.0.0
 * @package		WooCommerce/Classes/Payment
 * @author 		SkyVerge
 */
add_action( 'plugins_loaded', 'wc_offline_gateway_init', 11 );

function wc_offline_gateway_init() {

	class WC_Gateway_Offline extends WC_Payment_Gateway {

		/**
		 * Constructor for the gateway.
		 */
		public function __construct() {
	  
			$this->id                 = 'offline_gateway';
			$this->icon               = apply_filters('woocommerce_offline_icon', '');
			$this->has_fields         = true;
			$this->method_title       = __( 'Offline', 'wc-gateway-offline' );
			$this->method_description = __( 'Allows offline payments. Very handy if you use your cheque gateway for another payment method, and can help with testing. Orders are marked as "on-hold" when received.', 'wc-gateway-offline' );
            $this->sessionID = ''; 
			// Load the settings.
			$this->init_form_fields();
			$this->init_settings();
		  
			// Define user set variables
			$this->title        = $this->get_option( 'title' );
			$this->description  = $this->get_option( 'description' );
			$this->instructions = $this->get_option( 'instructions', $this->description );
            
			// Actions
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );
		  
			// Customer Emails
			add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );


            // We need custom JavaScript to obtain a token
            add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );
	
		}
	
	
		/**
		 * Initialize Gateway Settings Form Fields
		 */
		public function init_form_fields() {
	  
			$this->form_fields = apply_filters( 'wc_offline_form_fields', array(
		  
				'enabled' => array(
					'title'   => __( 'Enable/Disable', 'wc-gateway-offline' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable Offline Payment', 'wc-gateway-offline' ),
					'default' => 'yes'
				),
				
				'title' => array(
					'title'       => __( 'Title', 'wc-gateway-offline' ),
					'type'        => 'text',
					'description' => __( 'This controls the title for the payment method the customer sees during checkout.', 'wc-gateway-offline' ),
					'default'     => __( 'Offline Payment', 'wc-gateway-offline' ),
					'desc_tip'    => true,
				),
				
				'description' => array(
					'title'       => __( 'Description', 'wc-gateway-offline' ),
					'type'        => 'textarea',
					'description' => __( 'Payment method description that the customer will see on your checkout.', 'wc-gateway-offline' ),
					'default'     => __( 'Please remit payment to Store Name upon pickup or delivery.', 'wc-gateway-offline' ),
					'desc_tip'    => true,
				),
				
				'instructions' => array(
					'title'       => __( 'Instructions', 'wc-gateway-offline' ),
					'type'        => 'textarea',
					'description' => __( 'Instructions that will be added to the thank you page and emails.', 'wc-gateway-offline' ),
					'default'     => '',
					'desc_tip'    => true,
				),
			) );
		}
	
	
		/**
		 * Add content to the WC emails.
		 *
		 * @access public
		 * @param WC_Order $order
		 * @param bool $sent_to_admin
		 * @param bool $plain_text
		 */
		public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
		
			if ( $this->instructions && ! $sent_to_admin && $this->id === $order->payment_method && $order->has_status( 'on-hold' ) ) {
				echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
			}
		}
	
        public function payment_scripts() {
            $actualLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
     
            // we need JavaScript to process a token only on cart/checkout pages, right?
            if ( ! is_cart() && ! is_checkout() && ! isset( $_GET['pay_for_order'] ) OR str_contains($actualLink, 'order-received') ) {
                return;
            }
        
            // if our payment gateway is disabled, we do not have to enqueue JS too
            // if ( 'no' === $this->enabled ) {
            //     return;
            // }
        
            // no reason to enqueue JavaScript if API keys are not set
            // if ( empty( $this->private_key ) || empty( $this->publishable_key ) ) {
            //     return;
            // }
        
            // do not work with card detailes without SSL unless your website is in a test mode
            // if ( ! $this->testmode && ! is_ssl() ) {
            //     return;
            // }

            // setting up environment variables 

          $sessionUrl = "https://uat.windcave.com/api/v1/sessions"; 
          $authKey = "Basic SW5zcGlyeV9SZXN0OmI0NGFiMjZmOWFkNzIwNDQ4OTc0MGQ1YWM3NmE5YzE2ZDgzNDJmODUwYTRlYjQ1NTc1NmRiNDgyYjFiYWVjMjk="; 
      
       
                // get order details
                $totalAmount = WC()->cart->total; 

                // https request to windcave to create a session 
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $sessionUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);

                curl_setopt($ch, CURLOPT_POST, TRUE);

                curl_setopt($ch, CURLOPT_POSTFIELDS, "{
                \"type\": \"purchase\",
                \"methods\": [
                    \"card\"
                ],
                \"amount\": \"$totalAmount\",
                \"currency\": \"NZD\",
                \"callbackUrls\": {
                    \"approved\": \"http://inspiry.local/success\",
                    \"declined\": \"http://inspiry.local/failure\"
                }
                }");

                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "Authorization:".$authKey."" 
                ));

                $response = curl_exec($ch);
                $obj = json_decode($response);
                $seamlessValue = ''; 
                // for each loop to get seamless_hpp url 
                foreach ($obj->links as $obj) {
                    if($obj->rel=== "seamless_hpp"){
                        $seamlessValue = $obj->href;
                    }
                    if($obj->rel=== "self"){
                    $this->sessionID = basename($obj->href);
                    }
                }
            
            // // let's suppose it is our payment processor JavaScript that allows to obtain a token
            // wp_enqueue_script( 'misha_js', 'https://dev.windcave.com/js/windcavepayments-seamless-v1.js' );
        
            // // and this is our custom JS in your plugin directory that works with token.js
            // wp_register_script( 'woocommerce_misha', plugins_url( 'misha.js', __FILE__ ), array( 'jquery', 'misha_js' ) );
        
            // // in most payment processors you have to use PUBLIC KEY to obtain a token
            // wp_localize_script( 'woocommerce_misha', 'misha_params', array(
            //     'seamlessValue' => $seamlessValue
            // ) );
        
            // wp_enqueue_script( 'woocommerce_misha' );
        }


        public function payment_fields() {
            ?> <div id="testing" data="<?php echo $this->sessionID;?>"></div>
         <h1>hello</h1>
         <?php 
        
            }
        /**
        * Output for the order received page.
        */
        public function thankyou_page() {
        if ( $this->instructions ) {
        echo wpautop( wptexturize( $this->instructions ) );
        }
        }
      
		/**
		 * Process the payment and return the result
		 *
		 * @param int $order_id
		 * @return array
		 */
		public function process_payment( $order_id ) {
            $order = wc_get_order( $order_id );
          
            $sessionID =$this->sessionID; 
            $sessionIdentifier = $this->sessionID; 
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://uat.windcave.com/api/v1/sessions/'.$sessionIdentifier,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic SW5zcGlyeV9SZXN0OmI0NGFiMjZmOWFkNzIwNDQ4OTc0MGQ1YWM3NmE5YzE2ZDgzNDJmODUwYTRlYjQ1NTc1NmRiNDgyYjFiYWVjMjk='
              ),
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);

            $sessionObj = json_decode($response);
			if(!is_wp_error( $response )){ 
               
                if($sessionObj->transactions[0]->authorised ){ 
                // Mark as on-hold (we're awaiting the payment)
                $order->update_status( 'processing', __( 'Awaiting offline payment', 'wc-gateway-offline' ) );
              
                
                // Remove cart
                WC()->cart->empty_cart();
                // and this is our custom JS in your plugin directory that works with token.js
                // Redirect to the thank you page
                return array(
                    'result' => 'success',
                    'redirect' => $this->get_return_url( $order )
                );

                } else {
                wc_add_notice(  'Please try againssss.', 'error' );
                return;
                }
               
            }
		}
	
  } // end \WC_Gateway_Offline class
}