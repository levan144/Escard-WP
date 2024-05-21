<?php
/**
 * Intellectual Property rights, and copyright, reserved by Plug and Pay, Ltd. as allowed by law include,
 * but are not limited to, the working concept, function, and behavior of this software,
 * the logical code structure and expression as written.
 *
 * @package     WooCommerce TBC Credit Card Payment Gateway
 * @author      Plug and Pay Ltd. https://plugandpay.ge/
 * @copyright   Copyright (c) Plug and Pay Ltd. (support@plugandpay.ge)
 * @since       2.0.8
 * @license     https://plugandpay.ge/eula
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * TBC extras class.
 */
class PlugandPay_WC_TBC_Credit_Card_Extras {

	/**
	 * __FILE__ from the root plugin file.
	 *
	 * @since 2.0.8
	 * @var string
	 */
	public $file;

	/**
	 * Constructor.
	 *
	 * @since 2.0.8
	 * @param string $file Must be __FILE__ from the root plugin file.
	 */
	public function __construct( $file ) {
		$this->file = $file;

		add_filter( 'woocommerce_gateway_icon', [ $this, 'add_gateway_icons' ], 10, 2 );
	}

	/**
	 * Add TBC (& visa, mastercard) logo to the gateway.
	 *
	 * @since 2.0.8
	 * @param string $icons Html image tags.
	 * @param string $gateway_id Gateway id.
	 * @return string
	 */
	public function add_gateway_icons( $icons, $gateway_id ) {
		if ( 'tbc_credit_card_gateway' === $gateway_id ) {

			$icons .= sprintf(
				'<img width="40" src="%1$sassets/tbc.svg" alt="TBC" />
				 <img width="40" src="%1$sassets/visa.svg" style="margin:0 3px;" alt="VISA" />
				 <img width="40" src="%1$sassets/mastercard.svg" style="margin:0 3px;" alt="MASTERCARD" />',
				plugin_dir_url( $this->file )
			);

		}

		if ( 'tbc_checkout_gateway' === $gateway_id ) {

			$method_to_logo = [
				'card_payments'    => 'Cards',
				'qr_payments'      => 'QR',
				'ertguli_payments' => 'Ertguli',
				'apple_payments'   => 'ApplePay',
			];

			foreach ( $method_to_logo as $method => $logo ) {
				$option = $this->get_option( 'tbc_checkout_gateway', $method, 'yes' );

				if ( 'yes' === $option ) {

					$icons .= sprintf(
						'<img height="22" src="%1$sassets/checkout/%2$s.png" alt="TBC %2$s" />',
						plugin_dir_url( $this->file ),
						$logo
					);

				}
			}

			$icons .= sprintf(
				'<img height="22" src="%1$sassets/checkout/TBC.png" alt="TBC Checkout" />',
				plugin_dir_url( $this->file )
			);

		}

		return $icons;
	}

	/**
	 * Get gateway setting.
	 *
	 * @since 4.0.0
	 * @param string $id Gateway id.
	 * @param string $key Setting key.
	 * @param mixed  $default Default value.
	 * @return mixed|null
	 */
	public function get_option( $id, $key, $default = null ) {
		$settings = get_option( sprintf( 'woocommerce_%s_settings', $id ) );
		return $settings[ $key ] ?? $default;
	}

}

