<?php 
	 add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_styles' );
	 function hello_elementor_child_enqueue_styles() {
 		  wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' ); 
 		  

 		  } 
 		  
 		  // remove menu link
add_filter( 'woocommerce_account_menu_items', 'misha_remove_my_account_dashboard' );
function misha_remove_my_account_dashboard( $menu_links ){
	
	unset( $menu_links[ 'dashboard' ] );
	return $menu_links;
	
}
// perform a redirect
add_action( 'template_redirect', 'misha_redirect_to_orders_from_dashboard' );
function misha_redirect_to_orders_from_dashboard(){
	if( is_account_page() && empty( WC()->query->get_current_endpoint() ) ){
		wp_safe_redirect( wc_get_account_endpoint_url( 'subscriptions' ) );
		exit;
	}
	
}

function validate_email( $fields, $errors ){
    if ( ! empty( $fields['billing_email'] ) ) {
        $laravel_api_endpoint = 'https://dashboard.escard.ge/api/wp/check/email';
        $response = wp_remote_post($laravel_api_endpoint, array(
            'method'      => 'POST',
            'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
            'body'        => json_encode(array('email' => $fields['billing_email'])), // Send the email as part of an array
            'data_format' => 'body',
        ));
        
      
            $response_body = wp_remote_retrieve_body($response);
            $data = json_decode($response_body, true);
            if(!is_user_logged_in()){
                if ( isset( $data['status'] ) && $data['status'] ) {
                    wc_add_notice(__('Email is already registered', 'woocommerce'), 'error');
                }
            }
    }
}

add_action( 'woocommerce_after_checkout_validation', 'validate_email', 10, 2 );


function filter_woocommerce_get_endpoint_url( $url, $endpoint, $value, $permalink ) {
   
    if( $endpoint === 'subscriptions' ) {     
        // Custom URL
        $url = site_url() . '/' . substr(get_locale(), 0, 2) . '/my-account/subscriptions/';     
	
    }

    return $url;

}
add_filter( 'woocommerce_get_endpoint_url', 'filter_woocommerce_get_endpoint_url', 10, 4 );


add_action('register_post', 'myplugin_register_post', 10, 3);

function myplugin_register_post($sanitized_user_login, $user_email, $errors) {
  
}

// Hook into user registration on WordPress
add_action('user_register', 'wp_send_user_to_laravel', 10, 1);

function wp_send_user_to_laravel($user_id) {
    $user_info = get_userdata($user_id);
    $password = $user_info->user_pass;
    // Get the billing phone from user meta
    $billing_phone = get_user_meta($user_id, 'billing_phone', true);
    // Perform some basic validation or sanitation here
    if ($password) {
        // Set up the user data
        $user_data = [
            'name' => $user_info->first_name,
            'lastname' => $user_info->last_name,
            'email' => $user_info->user_email,
            'phone' => $_POST['billing_phone'],
            'password' => $password // Plain text password
        ];
        
        
    
        // Use cURL or WP_Http to send the data to your Laravel application
        $response = wp_remote_post('https://dashboard.escard.ge/api/wp/register', [
            'body' => $user_data,
            'sslverify' => true // Ensure SSL verification is enabled
        ]);
        // print_r($response);
        // exit;
        // Handle the response from the Laravel application
        if (is_wp_error($response)) {
            // Handle the error according to your needs
            error_log('Error in sending user data: ' . $response->get_error_message());
        } else {
            // Check for the status code.
            $status_code = wp_remote_retrieve_response_code($response);
            if ($status_code == 201) {
                return ['success' => true, 'message' => 'User successfully created.', 'data' => $user_data];
            } else {
                return new WP_Error('user_creation_failed', 'Failed to create user: ' . $body, ['status' => $status_code]);            }
        }
    }
}

add_action('woocommerce_subscription_status_updated', 'sync_subscription_to_laravel', 10, 1);

function sync_subscription_to_laravel($subscription) {
    $laravel_api_endpoint = 'https://dashboard.escard.ge/api/wp/sync';

    // Gather the necessary subscription data
    $subscription_data = array(
        'email'    => $subscription->get_billing_email(),
        'active_until'  => $subscription->has_status('active') && !$subscription->has_status('pending') ? $subscription->get_date('next_payment') : date('Y-m-d'), // or use a custom date format if needed
    );
    
    
    $response = wp_remote_post($laravel_api_endpoint, array(
        'method'      => 'POST',
        'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
        'body'        => json_encode($subscription_data),
        'data_format' => 'body',
    ));

   
}




/**
 * Function for `woocommerce_new_customer_data` filter-hook.
 * 
 * @param array $customer_data An array of customer (user) data.
 *
 * @return array
 */


// Add a second password field to the checkout page in WC 3.x.
add_filter( 'woocommerce_checkout_fields', 'wc_add_confirm_password_checkout', 10, 1 );
function wc_add_confirm_password_checkout( $checkout_fields ) {
    if ( get_option( 'woocommerce_registration_generate_password' ) == 'no' ) {
        $checkout_fields['account']['account_password2'] = array(
                'type'              => 'password',
                'label'             => __( 'Confirm password', 'woocommerce' ),
                'required'          => true,
                'placeholder'       => _x( 'Confirm Password', 'placeholder', 'woocommerce' )
        );
    }

    return $checkout_fields;
}

// Check the password and confirm password fields match before allow checkout to proceed.
add_action( 'woocommerce_after_checkout_validation', 'wc_check_confirm_password_matches_checkout', 10, 2 );
function wc_check_confirm_password_matches_checkout( $posted ) {
    $checkout = WC()->checkout;
    if ( ! is_user_logged_in() && ( $checkout->must_create_account || ! empty( $posted['createaccount'] ) ) ) {
        if ( strcmp( $posted['account_password'], $posted['account_password2'] ) !== 0 ) {
            wc_add_notice( __( 'Passwords do not match.', 'woocommerce' ), 'error' );
            
        }
    }
}
 
// Add this code to your theme's functions.php file or in a custom plugin

function my_custom_function_after_password_reset( $user, $new_pass ) {
    // Your custom code goes here
    // This function will be executed after a user's password is reset
     $laravel_api_endpoint = 'https://dashboard.escard.ge/api/wp/password';
    $user_email = $user->user_email;
    // Gather the necessary subscription data
    $data = array(
        'email'    => $user_email,
        'password' => $new_pass,
    );
   
    
    $response = wp_remote_post($laravel_api_endpoint, array(
        'method'      => 'POST',
        'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
        'body'        => json_encode($data),
        'data_format' => 'body',
    ));

if (is_wp_error($response)) {
            // Handle the error according to your needs
            error_log('Error in sending user data: ' . $response->get_error_message());
        } else {
            // Check for the status code.
            $status_code = wp_remote_retrieve_response_code($response);
            if ($status_code == 201) {
                return ['success' => true, 'message' => 'User successfully updated.', 'data' => $data];
            } else {
                // error_log('Error in sending user data: ' . $response->get_error_message());      
                
            }
        }

}

add_action( 'password_reset', 'my_custom_function_after_password_reset', 10, 2 );

function register_custom_rest_endpoint() {
    $GLOBALS['user_id'] = get_current_user_id();
    register_rest_route('custom/v1', '/cancel-subscriptions/', array(
        'methods' => 'POST',
        'callback' => 'cancel_subscriptions_rest_handler',
    ));
}
add_action('rest_api_init', 'register_custom_rest_endpoint');


function cancel_subscriptions_rest_handler($data) {
    $user_id = $GLOBALS['user_id'];
    if ($user_id > 0) {
        cancel_all_subscriptions_for_user($user_id);
        return 'Subscriptions cancelled successfully.';
    } else {
        return 'Invalid user ID.';
    }
}


/**
 * Custom function to cancel all user's subscriptions
 */
function cancel_all_subscriptions_for_user($user_id) {
    // Get user subscriptions
    $subscriptions = wcs_get_subscriptions(array(
        'customer_id' => $user_id,
        'status'      => 'active', // You can adjust the status as needed
    ));

    // Loop through each subscription and cancel it
    foreach ($subscriptions as $subscription) {
        $subscription->cancel_order('Subscription canceled by user.', true);
    }
}

/**
 * Hook into your button click event or any other trigger
 */
function cancel_subscriptions_on_button_click() {
    $user_id = get_current_user_id(); // Adjust this based on how you retrieve the user ID

    if ($user_id) {
        cancel_all_subscriptions_for_user($user_id);
        // You can add additional actions or messages here after canceling subscriptions
    }
}

// Hook into your button click event or any other trigger
add_action('your_button_click_action', 'cancel_subscriptions_on_button_click');


function lang_switcher() {
    $languages = trp_custom_language_switcher();
    $current_language = get_locale();
    if($current_language === 'ka_GE') {
        $url = $languages['en_US']['current_page_url'];
        $html = '<a onclick="window.location.href=\'' . $url . '\'">';
        $html .= '<label class="switch">';
        $html .= '<input type="checkbox">';
        $html .= '<span class="slider round regular"></span>';
        $html .= '<a href="#" onclick="window.location.href=\'' . $url . '\'" class="lang-text regular" style="z-index:99">GE</a>';
        $html .= '</label>';
        $html .= '</a>';
    } else {
        $url = $languages['ka_GE']['current_page_url'];
        $html = '<a href='. $url .'>';
        $html .= '<label class="switch">';
        $html .= '<input type="checkbox">';
        $html .= '<span class="slider round reversed"></span>';
        $html .= '<a href="'. $url .'" class="lang-text reversed" style="z-index:99">EN</a>';
        $html .= '</label>';
        $html .= '</a>';
    }
    return $html;
}
add_shortcode( 'custom_lang_switcher', 'lang_switcher' );