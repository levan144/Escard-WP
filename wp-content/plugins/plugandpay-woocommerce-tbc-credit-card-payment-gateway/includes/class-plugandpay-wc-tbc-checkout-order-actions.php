<?php
/**
 * Intellectual Property rights, and copyright, reserved by Plug and Pay, Ltd. as allowed by law include,
 * but are not limited to, the working concept, function, and behavior of this software,
 * the logical code structure and expression as written.
 *
 * @package     WooCommerce TBC Credit Card Payment Gateway
 * @author      Plug and Pay Ltd. https://plugandpay.ge/
 * @copyright   Copyright (c) Plug and Pay Ltd. (support@plugandpay.ge)
 * @since       4.0.0
 * @license     https://plugandpay.ge/eula
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * TBC (Checkout) order actions class.
 */
class PlugandPay_WC_TBC_Checkout_Order_Actions {

	/**
	 * The current version of the plugin.
	 *
	 * @since 4.0.0
	 * @var string
	 */
	public $version;

	/**
	 * Constructor.
	 *
	 * @since 4.0.0
	 * @param string $software_version Current software version of this plugin.
	 */
	public function __construct( $software_version ) {
		$this->version = $software_version;

		add_action( 'woocommerce_order_actions', [ $this, 'add_actions' ] );
		add_action( 'woocommerce_order_action_tbc_checkout_cancel_preauth', [ $this, 'cancel_preauth' ] );
		add_action( 'woocommerce_order_action_tbc_checkout_complete_preauth', [ $this, 'complete_preauth' ] );

	}

	/**
	 * Add order actions to select box on edit order page.
	 *
	 * @since 4.0.0
	 * @param array $actions order actions array to display.
	 * @return array
	 */
	public function add_actions( $actions ) {
		global $theorder;

		if ( 'tbc_checkout_gateway' === $theorder->get_payment_method() ) {
			$actions['tbc_checkout_cancel_preauth']   = __( 'Cancel TBC checkout Pre Auth', 'tbc-gateway' );
			$actions['tbc_checkout_complete_preauth'] = __( 'Complete TBC checkout Pre Auth', 'tbc-gateway' );
		}

		return $actions;
	}

	/**
	 * Cancel preauth.
	 *
	 * @since 4.0.0
	 * @param WC_Order $order Order object.
	 */
	public function cancel_preauth( $order ) {

		$gateway = new PlugandPay_WC_TBC_Checkout_Gateway( $this->version );
		$gateway->log( 'Trying to cancel preauth!', 'info' );

		$response = $gateway->api_request(
			wp_json_encode( [ 'amount' => $order->get_total() ] ),
			'payments/' . $order->get_transaction_id() . '/cancel',
			[
				'Authorization' => 'Bearer ' . $gateway->get_access_token( $order->get_currency() ),
				'Content-Type'  => 'application/json',
			]
		);

		if ( false === $response ) {
			$error = __( 'Canceling preauth failed!', 'tbc-gateway' );
			$gateway->log( $error, 'error' );
			$order->add_order_note( $error );
			return;
		}

		$success = __( 'Preauth cancelled!', 'tbc-gateway' );
		$gateway->log( $success, 'info' );
		$order->add_order_note( $success );
	}

	/**
	 * Complete preauth.
	 *
	 * @since 4.0.0
	 * @param WC_Order $order Order object.
	 */
	public function complete_preauth( $order ) {

		$gateway = new PlugandPay_WC_TBC_Checkout_Gateway( $this->version );
		$gateway->log( 'Trying to complete preauth!', 'info' );

		$response = $gateway->api_request(
			wp_json_encode( [ 'amount' => $order->get_total() ] ),
			'payments/' . $order->get_transaction_id() . '/completion',
			[
				'Authorization' => 'Bearer ' . $gateway->get_access_token( $order->get_currency() ),
				'Content-Type'  => 'application/json',
			]
		);

		if ( isset( $response['status'] ) && 'Succeeded' === $response['status'] ) {
			$success = __( 'Preauth completed!', 'tbc-gateway' );
			$gateway->log( $success, 'info' );
			$order->add_order_note( $success );
			return;
		}

		$error = __( 'Completing preauth failed!', 'tbc-gateway' );
		$gateway->log( $error, 'error' );
		$order->add_order_note( $error );
	}

}

