<?php
/**
 * My Subscriptions section on the My Account page
 *
 * @author   Prospress
 * @category WooCommerce Subscriptions/Templates
 * @version  1.0.0 - Migrated from WooCommerce Subscriptions v2.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="woocommerce_account_subscriptions">
<style>
    .subscription-container {
    display: flex;
    justify-content: space-around;
    margin: 20px;
}

.subscription-box {
    border: 2px solid #000; /* Pink border */
    border-radius: 30px;
    width: 350px; /* Fixed width, you can use % or vw for responsiveness */
    padding: 20px;
    margin: 10px;
    text-align:center;
    background: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.subscription-header {
    text-align: center;
    margin-bottom: 20px;
}

.subscription-header h2 {
    color: #333;
    margin: 0;
}

.price {
    color: #FF69B4; /* Pink color for the price */
    font-weight: bold;
    font-size:30px;
}

.subscription-details {
    list-style: none;
    padding: 0;
    margin-bottom: 20px;
}

.subscription-details li {
    margin-bottom: 10px;
    color: #666;
}

.subscription-button {
    font-size: 16px;
  font-weight: 400;
  fill: #FFFFFF;
  color: #FFFFFF!important;
  background-color: #000000;

  border-radius: 30px 30px 30px 30px;
  padding: 15px 55px 15px 55px;
}

.subscription-button:hover {
    
}

</style>
<?php 

function has_active_subscription( $user_id, $product_id ) {
    // Get all the active subscriptions for the user
    $active_subscriptions = wcs_get_users_subscriptions( $user_id );
    // Check if there are any active subscriptions
    if ( ! empty( $active_subscriptions ) ) {
        foreach ( $active_subscriptions as $subscription ) {
            // print_r($subscription->get_items());
            // exit;
            if ( sizeof( $subscription_items = $subscription->get_items() ) > 0 ) {
                foreach ( $subscription_items as $item_id => $item ) {
                    $product = $item->get_product();
                    if ( $subscription->has_status( 'active' ) &&  $product->get_id() == $product_id) {
                        return true;   
                    }
                    //Examples of use
                }
            }
          
        }
    }
    return false;
}

function is_product_in_active_subscription($product_id) {
    $user_id = get_current_user_id();

    // Get active subscriptions for the current user
     $subscriptions = wcs_get_subscriptions(array(
        'customer_id' => get_current_user_id(),
        'status' => 'active',
    ));
    
        $active_subscriptions = has_active_subscription( get_current_user_id(), $product_id );
    
    if (!$active_subscriptions) {
        return false; // No active subscriptions found
    }
    
    return true;
    // Iterate through the user's active subscriptions
    // foreach ($subscriptions as $subscription) {
      
        // if($subscription->status === 'active'){
        //     $subscription_products = $subscription->get_items();
           
        //     foreach ($subscription_products as $subscription_product) {
        //         // Check if the product ID matches the specified product ID
        //         if ($subscription_product['product_id'] == $product_id) {
        //             return true; // Product found in an active subscription
        //         }
        //     }
        // }

}

function add_cancel_subscription_button_to_my_account() {
    $args = array(
                'subscription_status' => 'active',
                'subscriptions_per_page' => 1, // Set to -1 to load all subscriptions
                'customer_id'            => get_current_user_id(),
            );
            
            
            // Get subscriptions
            $subscriptions = wcs_get_subscriptions($args);
    if ($subscriptions) {
        foreach ($subscriptions as $subscription) {
          //  $cancel_url = $subscripteion->get_cancel_url();
            return cancelButton($subscription);

            // echo '<a href="' . esc_url($cancel_url) . '" class="button cancel-subscription">Cancel</a>';
        }
    }
}

function cancelButton( $subscription ) {
    $current_status = $subscription->get_status();
    $subscription_id = $subscription->get_order_number();
    $subscription_url = $subscription->get_view_order_url();
    $cancel_url = $subscription_url . '?subscription_id=' . $subscription_id . '&change_subscription_to=cancelled';
    $cancel_subscription_url = wp_nonce_url( $cancel_url, $subscription_id . $current_status );

    if ( $current_status == 'active') {
        echo '<a href="' . $cancel_subscription_url . '" class="subscription-button m-auto" onclick="alert("Tem certeza que deseja cancelar sua assinatura?")">' .  __( 'გაუქმება', 'starling' ) . '</a>';
    }
}
 
 ?>
    <h3><?php _e('Subscriptions'); ?></h3>
    <div class="mt-4">
            <h3><?php _e('Current subscription') ?> </h3>
            <?php
            $args = array(
                'subscription_status' => 'active',
                'subscriptions_per_page' => 1, // Set to -1 to load all subscriptions
                'customer_id'            => get_current_user_id(),
            );
            
            
            // Get subscriptions
            $subscriptions = wcs_get_subscriptions($args);

            $subscription = !empty($subscriptions) ? reset($subscriptions) : false;
            
            ?>
            <div class="row">
                <div class="col-6">
                    <?php if($subscription){  ?>
                    <h4><?php echo date("Y-m-d", strtotime($subscription->get_date('next_payment'))) ?></h4>
                    <?php } ?>
                </div>
                <div class="col-6 text-right">
                    <?php if($subscription){ ?>
                    <h4><?php echo $subscription->get_total(); ?> ₾ / <?php print_r(reset($subscription->get_items())->get_name()); ?> (<?php _e('with tax'); ?>)</h4>
                    <?php } ?>
                </div>
            </div>
 <hr style="height:3px; margin-bottom:5px;  background:black;">
    </div>
    <div class="subscription-container row">
    
    
    <div class="subscription-box">
    <div class="subscription-header">
        <h2><?php _e('Month', 'subscription-custom'); ?></h2>
        <h3 class="price"><?php _e('₾ 15 / Month', 'subscription-custom'); ?></h3>
    </div>
    <ul class="subscription-details text-left">
        <li><?php _e('- 40% Discounts', 'subscription-custom'); ?></li>
        <li><?php _e('- Exclusive offers', 'subscription-custom'); ?></li>
        <li><?php _e('- Over 250 Partner Objects', 'subscription-custom'); ?></li>
        <li><?php _e('- Enjoy ESCARD Unlimitedly', 'subscription-custom'); ?></li>
        <li><?php _e('- Protect Yourself Everywhere', 'subscription-custom'); ?></li>
        <br>
        <li><?php _e('* Monthly Subscription Fee', 'subscription-custom'); ?></li>
        
        <br><br>
        <!-- ... more items ... -->
    </ul>
    <?php 
    if (is_product_in_active_subscription(2109)) {
    ?>
    <a href="#" class="subscription-button m-auto"><?php _e('Subscribed', 'subscription-custom'); ?></a>
    <?php } else { ?>
        <a href="https://escard.ge/ka/checkout/?add_product_to_cart=1&amp;product_id=2109/" class="subscription-button m-auto"><?php _e('Subscribe', 'subscription-custom'); ?></a>
    <?php } ?>
</div>

<div class="subscription-box">
    <div class="subscription-header">
        <h2><?php _e('Year', 'subscription-custom'); ?></h2>
        <h3 class="price"><?php _e('₾ 120 / Year', 'subscription-custom'); ?></h3>
    </div>
    <ul class="subscription-details text-left">
        <li><?php _e('- 40% Discounts', 'subscription'); ?></li>
        <li><?php _e('- Exclusive offers', 'subscription-custom'); ?></li>
        <li><?php _e('- Over 250 Partner Objects', 'subscription-custom'); ?></li>
        <li><?php _e('- Enjoy ESCARD Unlimitedly', 'subscription-custom'); ?></li>
        <li><?php _e('- Protect Yourself Everywhere', 'subscription-custom'); ?></li>
        <br>
        <li><?php _e('* Get up to 35% off when you buy a 1-year package', 'subscription-custom'); ?></li>
        <br>
        <!-- ... more items ... -->
    </ul>
    <?php 
    if (is_product_in_active_subscription(2404)) {
    ?>
    <a href="#" class="subscription-button m-auto"><?php _e('Subscribed', 'subscription-custom'); ?></a>
    <?php } else { ?>
        <a href="https://escard.ge/ka/checkout/?add_product_to_cart=1&amp;product_id=2404/" class="subscription-button m-auto"><?php _e('Subscribe', 'subscription-custom'); ?></a>
    <?php } ?>
</div>

   
</div>

    <?php
    function addCancelButton($subscription) {
        $actions = wcs_get_all_user_actions_for_subscription( $subscription, get_current_user_id() );
        if(!empty($actions)){
            foreach ( $actions as $key => $action ){
                if(strtolower($action['name']) == "cancel"){
                    $cancelLink = esc_url( $action['url'] );
                    echo "<a href='$cancelLink' class='button cancel'>".$action['name']."</a>";
                }
            }
        }
    }
add_action( 'woocommerce_my_subscriptions_actions', 'addCancelButton', 10 );
    ?>


</div>
