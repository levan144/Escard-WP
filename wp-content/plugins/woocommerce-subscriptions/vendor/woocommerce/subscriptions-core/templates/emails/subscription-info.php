<?php
/**
 * Subscription information template
 *
 * @author  Brent Shepherd / Chuck Mac
 * @package WooCommerce_Subscriptions/Templates/Emails
 * @version 1.0.0 - Migrated from WooCommerce Subscriptions v3.0.4
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( empty( $subscriptions ) ) {
	return;
}

$has_automatic_renewal = false;
$is_parent_order       = wcs_order_contains_subscription( $order, 'parent' );
?>
<div style="margin-bottom: 40px;">
<h2 style="font-weight:800;"><?php esc_html_e( 'SUBSCRIPTION INFORMATION', 'woocommerce-subscriptions' ); ?></h2>
<table class="td" cellspacing="0" cellpadding="6" style="width: 450px; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; margin-bottom: 0.5em; border:none;" border="0">
	<thead>
	
	</thead>
	<tbody>
	<?php foreach ( $subscriptions as $subscription ) : ?>
		<?php $has_automatic_renewal = $has_automatic_renewal || ! $subscription->is_manual(); ?>
		<tr>
			<th class="td" scope="col" style="text-align:left; border:none; border-bottom:1px solid #c0c0c0; font-weight:400; font-size:15px;"><?php echo esc_html_x( 'ID', 'subscription ID table heading', 'woocommerce-subscriptions' ); ?></th>
			<td class="td" scope="row" style="text-align:right; border:none; border-bottom:1px solid #c0c0c0;  font-weight:400; font-size:15px;"><?php echo sprintf( esc_html_x( '#%s', 'subscription number in email table. (eg: #106)', 'woocommerce-subscriptions' ), esc_html( $subscription->get_order_number() ) ); ?></td>
		</tr>
		<tr>
			<th class="td" scope="col" style="text-align:left; border:none; border-bottom:1px solid #c0c0c0;  font-weight:400; font-size:15px;"><?php echo esc_html_x( 'Start date', 'table heading', 'woocommerce-subscriptions' ); ?></th>
			<td class="td" scope="row" style="text-align:right; border:none; border-bottom:1px solid #c0c0c0;  font-weight:400; font-size:15px;"><?php echo esc_html( date_i18n( wc_date_format(), $subscription->get_time( 'start_date', 'site' ) ) ); ?></td>
		</tr>
		<tr>
			<th class="td" scope="col" style="text-align:left; border:none; border-bottom:1px solid #c0c0c0;  font-weight:400; font-size:15px;"><?php echo esc_html_x( 'End date', 'table heading', 'woocommerce-subscriptions' ); ?></th>
			<td class="td" scope="row" style="text-align:right; border:none; border-bottom:1px solid #c0c0c0;  font-weight:400; font-size:15px;"><?php echo esc_html( ( 0 < $subscription->get_time( 'end' ) ) ? date_i18n( wc_date_format(), $subscription->get_time( 'end', 'site' ) ) : _x( 'When cancelled', 'Used as end date for an indefinite subscription', 'woocommerce-subscriptions' ) ); ?></td>
		</tr>
		<tr>
			<th class="td" scope="col" style="text-align:left; border:none; border-bottom:1px solid #c0c0c0;  font-weight:400; font-size:15px;"><?php echo esc_html_x( 'Recurring total', 'table heading', 'woocommerce-subscriptions' ); ?></th>
				<td class="td" scope="row" style="text-align:right; border:none; border-bottom:1px solid #c0c0c0;  font-weight:400; font-size:15px;">
				<?php echo wp_kses_post( $subscription->get_formatted_order_total() ); ?>
				
			</td>
		</tr>
		 <?php if ( $is_parent_order && $subscription->get_time( 'next_payment' ) > 0 ) : ?>
		<tr>
		    <th class="td" scope="col" style="text-align:left; border:none; border-bottom:1px solid #c0c0c0;  font-weight:400; font-size:15px;"><?php echo esc_html_x( 'Next Payment', 'table heading', 'woocommerce-subscriptions' ); ?></th>
		    <td class="td" scope="row" style="text-align:right; border:none; border-bottom:1px solid #c0c0c0;  font-weight:400; font-size:15px;"><?php printf( esc_html__( '%s', 'woocommerce-subscriptions' ), esc_html( date_i18n( wc_date_format(), $subscription->get_time( 'next_payment', 'site' ) ) ) ); ?></td>
		</tr>
			<?php endif; ?>
	
	<?php endforeach; ?>
</tbody>
</table>
<?php if ( $has_automatic_renewal && ! $is_admin_email && $subscription->get_time( 'next_payment' ) > 0 ) {
	if ( count( $subscriptions ) === 1 ) {
		$subscription   = reset( $subscriptions );
		$my_account_url = $subscription->get_view_order_url();
	} else {
		$my_account_url = wc_get_endpoint_url( 'subscriptions', '', wc_get_page_permalink( 'myaccount' ) );
	}

	// Translators: Placeholders are opening and closing My Account link tags.
	printf( '<p style="color:black; text-align:left">%s</p>', wp_kses_post( sprintf( _n(
		'This subscription is set to renew automatically using your payment method on file. You can manage or cancel this subscription from your %smy account page%s.',
		'These subscriptions are set to renew automatically using your payment method on file. You can manage or cancel your subscriptions from your my account page.',
		count( $subscriptions ),
		'woocommerce-subscriptions'
	), '<a href="' . $my_account_url . '">', '</a>' ) ) );
}?>
</div>

