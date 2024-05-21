<?php
/**
 * Intellectual Property rights, and copyright, reserved by Plug and Pay, Ltd. as allowed by law include,
 * but are not limited to, the working concept, function, and behavior of this software,
 * the logical code structure and expression as written.
 *
 * @package     WooCommerce TBC Credit Card Payment Gateway
 * @author      Plug and Pay Ltd. https://plugandpay.ge/
 * @copyright   Copyright (c) Plug and Pay Ltd. (support@plugandpay.ge)
 * @since       4.1.0
 * @license     https://plugandpay.ge/eula
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeAreDe\TbcPay\TbcPayProcessor;

/**
 * TBC background tasks class.
 */
class PlugandPay_WC_TBC_Credit_Card_Background_Tasks {

	/**
	 * __FILE__ from the root plugin file.
	 *
	 * @since 4.1.0
	 * @var string
	 */
	public $file;

	/**
	 * Constructor.
	 *
	 * @since 4.1.0
	 * @param string $file Must be __FILE__ from the root plugin file.
	 */
	public function __construct( $file ) {
		$this->file = $file;

		add_action( 'run_tbc_checkout_order_status_check_in_background', [ $this, 'check_tbc_checkout_order_status' ], 10, 2 );
		add_filter( 'woocommerce_cancel_unpaid_order', [ $this, 'check_tbc_ufc_order_status' ], 10, 2 );
	}

	/**
	 * Check order status in background periodically.
	 *
	 * @since 4.1.0
	 * @param int $order_id Order id.
	 * @param int $retry_index Retry counter index.
	 * @return bool
	 */
	public function check_tbc_checkout_order_status( $order_id, $retry_index ) {

		$order   = wc_get_order( $order_id );
		$gateway = new PlugandPay_WC_TBC_Checkout_Gateway( $this->file );

		$trans_id = $order->get_transaction_id();
		$response = $gateway->get_transaction_status_from_api( $trans_id, $order->get_currency() );

		$gateway->log( sprintf( 'TBC Checkout reply on status check in the background: %s, order_id: %d', wp_json_encode( $response, JSON_PRETTY_PRINT ), $order->get_id() ), 'info' );

		if ( $response && isset( $response['status'] ) ) {

			switch ( $response['status'] ) {

				case 'WaitingConfirm':
				case 'Succeeded':
					$gateway->payment_complete( $order, $trans_id );
					$gateway->log( 'TBC Checkout: payment successful.', 'notice' );
					return true;

				case 'Failed':
					$gateway->payment_failed( $order, $trans_id );
					$gateway->log( 'TBC Checkout: payment failed.', 'notice' );
					return true;
			}

			$gateway->log( 'API did not return status Succeeded or Failed.', 'error' );
		} else {
			$gateway->log( 'API did not return anything.', 'error' );
		}

		$retries = [
			1 => MINUTE_IN_SECONDS * 3,
			2 => MINUTE_IN_SECONDS * 5,
			3 => MINUTE_IN_SECONDS * 10,
		];

		if ( isset( $retries[ $retry_index + 1 ] ) ) {
			$gateway->log( 'Reschedule background check, retry in (m): ' . $retries[ $retry_index + 1 ] / MINUTE_IN_SECONDS, 'alert' );

			WC()->queue()->schedule_single(
				time() + $retries[ $retry_index + 1 ],
				'run_tbc_checkout_order_status_check_in_background',
				[
					'order_id'    => $order->get_id(),
					'retry_index' => $retry_index + 1,
				]
			);
		}

		return false;
	}

	/**
	 * Check order status before it is marked canceled.
	 *
	 * @since 4.1.0
	 * @param bool     $is_checkout Is order created via checkout.
	 * @param WC_Order $order Order object.
	 * @return bool    True cancels order and False does not.
	 */
	public function check_tbc_ufc_order_status( $is_checkout, $order ) {

		if ( $is_checkout ) {
			if ( 'tbc_credit_card_gateway' === $order->get_payment_method() ) {

				$gateway  = new PlugandPay_WC_TBC_UFC_Gateway();
				$trans_id = $order->get_transaction_id();
				$account  = $order->get_meta( '_merchant_account' );

				$gateway->log( sprintf( 'Checking transaction status for order id: %s, merchant: %s, before automatic woo cancel system marks it canceled.', $order->get_id(), $account['merchant_currency'] ) );

				$tbc      = new TbcPayProcessor( $account['merchant_cert_path'], $account['merchant_cert_pass'], $order->get_customer_ip_address() );
				$response = $tbc->get_transaction_result( $trans_id );

				if ( ! isset( $response['RESULT'] ) || 'OK' !== $response['RESULT'] ) {
					$gateway->log( 'TBC did not return OK: ' . json_encode( $response ), 'error' );
					return true;
				}

				$complete_message = $gateway->set_order_status( $order, $response );

				$gateway->log( sprintf( ' %s transaction id: %s, order id: %s', $complete_message, $trans_id, $order->get_id() ) );

				return false;
			}

			return true;
		}

		return false;
	}

}

