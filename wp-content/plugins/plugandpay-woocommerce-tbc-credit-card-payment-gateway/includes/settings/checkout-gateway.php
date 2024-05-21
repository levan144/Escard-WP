<?php
/**
 * Intellectual Property rights, and copyright, reserved by Plug and Pay, Ltd. as allowed by law include,
 * but are not limited to, the working concept, function, and behavior of this software,
 * the logical code structure and expression as written.
 *
 * @package     WooCommerce TBC Credit Card Payment Gateway
 * @author      Plug and Pay Ltd. https://plugandpay.ge/
 * @copyright   Copyright (c) Plug and Pay Ltd. (support@plugandpay.ge)
 * @since       3.0.0
 * @license     https://plugandpay.ge/eula
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings for TBC (Checkout) Gateway.
 */
return [
	'enabled'           => [
		'title'   => __( 'Enable/Disable', 'tbc-gateway' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable TBC Checkout', 'tbc-gateway' ),
		'default' => 'yes',
	],
	'title'             => [
		'title'       => __( 'Title', 'tbc-gateway' ),
		'type'        => 'text',
		'description' => __( 'This controls the title which the user sees during checkout.', 'tbc-gateway' ),
		'default'     => __( 'TBC', 'tbc-gateway' ),
		'desc_tip'    => true,
	],
	'description'       => [
		'title'       => __( 'Description', 'tbc-gateway' ),
		'type'        => 'text',
		'description' => __( 'This controls the description which the user sees during checkout.', 'tbc-gateway' ),
		'default'     => __( 'Pay with TBC Checkout', 'tbc-gateway' ),
		'desc_tip'    => true,
	],
	'order_button_text' => [
		'title'       => __( 'Order button text', 'tbc-gateway' ),
		'type'        => 'text',
		'description' => __( 'This controls the order button text which the user sees during checkout.', 'tbc-gateway' ),
		'default'     => __( 'Proceed to TBC', 'tbc-gateway' ),
		'desc_tip'    => true,
	],
	'debug'             => [
		'title'       => __( 'Debug Log', 'tbc-gateway' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable Logging', 'tbc-gateway' ),
		'default'     => 'no',
		/* translators: %s: log file path */
		'description' => sprintf( __( 'Log TBC Checkout events inside: <code>%s</code>', 'tbc-gateway' ), wc_get_log_file_path( 'tbc_checkout_gateway' ) ),
	],
	'client_accounts'   => [
		'type' => 'client_accounts',
	],
	'payment_action'    => [
		'title'       => __( 'Payment Action', 'tbc-gateway' ),
		'type'        => 'select',
		'class'       => 'wc-enhanced-select',
		'description' => __( 'Choose whether you wish to capture funds immediately (SMS) or authorize payment only (DMS).', 'tbc-gateway' ),
		'default'     => 'capture',
		'desc_tip'    => true,
		'options'     => [
			'capture'   => __( 'Direct Payment', 'tbc-gateway' ),
			'authorize' => __( 'Pre authorisation/Completion', 'tbc-gateway' ),
		],
	],
	'card_payments'     => [
		'title'   => __( 'Card payments', 'tbc-gateway' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable', 'tbc-gateway' ),
		'default' => 'yes',
	],
	'qr_payments'       => [
		'title'   => __( 'QR payments', 'tbc-gateway' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable', 'tbc-gateway' ),
		'default' => 'yes',
	],
	'ertguli_payments'  => [
		'title'   => __( 'Ertguli payments', 'tbc-gateway' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable', 'tbc-gateway' ),
		'default' => 'yes',
	],
	'apple_payments'    => [
		'title'   => __( 'ApplePay payments', 'tbc-gateway' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable', 'tbc-gateway' ),
		'default' => 'yes',
	],
	'ib_payments'       => [
		'title'   => __( 'InternetBank payments', 'tbc-gateway' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable', 'tbc-gateway' ),
		'default' => 'yes',
	],
	'skip_info_message' => [
		'title'       => __( 'Skip transaction results page', 'tbc-gateway' ),
		'type'        => 'checkbox',
		'label'       => __( 'Yes', 'tbc-gateway' ),
		'default'     => 'no',
		'description' => __( 'Skips the bank-side transaction results page after payment and redirects the customer directly to your page.', 'tbc-gateway' ),
	],
];
