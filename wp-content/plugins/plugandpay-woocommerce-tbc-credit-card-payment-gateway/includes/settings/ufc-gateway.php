<?php
/**
 * Intellectual Property rights, and copyright, reserved by Plug and Pay, Ltd. as allowed by law include,
 * but are not limited to, the working concept, function, and behavior of this software,
 * the logical code structure and expression as written.
 *
 * @package     WooCommerce TBC Credit Card Payment Gateway
 * @author      Plug and Pay Ltd. https://plugandpay.ge/
 * @copyright   Copyright (c) Plug and Pay Ltd. (support@plugandpay.ge)
 * @since       1.0.0
 * @license     https://plugandpay.ge/eula
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings for TBC (UFC) Gateway.
 */
return [
	'enabled'                 => [
		'title'   => __( 'Enable/Disable', 'tbc-gateway' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable TBC', 'tbc-gateway' ),
		'default' => 'no',
	],
	'title'                   => [
		'title'       => __( 'Title', 'tbc-gateway' ),
		'type'        => 'text',
		'description' => __( 'This controls the title which the user sees during checkout.', 'tbc-gateway' ),
		'default'     => __( 'TBC', 'tbc-gateway' ),
		'desc_tip'    => true,
	],
	'description'             => [
		'title'       => __( 'Description', 'tbc-gateway' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'This controls the description which the user sees during checkout.', 'tbc-gateway' ),
		'default'     => __( 'Pay with your credit card via TBC', 'tbc-gateway' ),
	],
	'order_button_text'       => [
		'title'       => __( 'Order button text', 'tbc-gateway' ),
		'type'        => 'text',
		'description' => __( 'This controls the order button text which the user sees during checkout.', 'tbc-gateway' ),
		'default'     => __( 'Proceed to TBC', 'tbc-gateway' ),
		'desc_tip'    => true,
	],
	'debug'                   => [
		'title'       => __( 'Debug Log', 'tbc-gateway' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable Logging', 'tbc-gateway' ),
		'default'     => 'no',
		/* translators: %s: log file path */
		'description' => sprintf( __( 'Log TBC Credit Card events inside <code>%s</code> Note: this may log personal information. We recommend using this for debugging purposes only and deleting the logs when finished.', 'tbc-gateway' ), wc_get_log_file_path( 'tbc_credit_card_gateway' ) ),
	],
	'payment_form_language'   => [
		'title'       => __( 'Payment Form Language', 'tbc-gateway' ),
		'type'        => 'select',
		'class'       => 'wc-enhanced-select',
		'description' => __( 'You can use this option to force specific language on the TBC payment form.', 'tbc-gateway' ),
		'default'     => '',
		'desc_tip'    => true,
		'options'     => [
			''   => __( 'Auto', 'tbc-gateway' ),
			'GE' => __( 'Georgian', 'tbc-gateway' ),
			'EN' => __( 'English', 'tbc-gateway' ),
		],
	],
	'close_business_day_cron' => [
		'title'       => __( 'Close Business Day', 'tbc-gateway' ),
		'type'        => 'select',
		'class'       => 'wc-enhanced-select',
		/* translators: %s: cron command */
		'description' => sprintf( __( 'TBC will close business day for all merchants automatically at 00:00 everyday. If you wish to do it yourself (some other time) you can enable server cron, but you will have to set up cron job manually on the server e.g. <code>%s</code>', 'tbc-gateway' ), '0 0 * * * wget -O - ' . get_bloginfo( 'url' ) . '/wc-api/close_business_day >> /dev/null 2>&1' ),
		'default'     => 'disabled',
		'options'     => [
			'disabled'    => __( 'TBC (00:00)', 'tbc-gateway' ),
			'server_cron' => __( 'Server Cron', 'tbc-gateway' ),
		],
	],
	'payment_action'          => [
		'title'       => __( 'Payment Action', 'tbc-gateway' ),
		'type'        => 'select',
		'class'       => 'wc-enhanced-select',
		'description' => __( 'Choose whether you wish to capture funds immediately (SMS) or authorize payment only (DMS). Warning: authorize (DMS) will not work with the save card feature!', 'tbc-gateway' ),
		'default'     => 'capture',
		'desc_tip'    => true,
		'options'     => [
			'capture'   => __( 'Capture', 'tbc-gateway' ),
			'authorize' => __( 'Authorize', 'tbc-gateway' ),
		],
	],
	'ertguli_points'          => [
		'title'       => __( 'Ertguli points', 'tbc-gateway' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable payment with Ertguli points', 'tbc-gateway' ),
		'default'     => 'no',
		'description' => __( 'Contact TBC to enable this feature.', 'tbc-gateway' ),
	],
	'save_card'               => [
		'title'       => __( 'Save Card', 'tbc-gateway' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable save card', 'tbc-gateway' ),
		'default'     => 'no',
		'description' => __( 'Contact TBC to enable this feature. It is only possible to capture (SMS) funds on a saved card, authorize (DMS) is not available.', 'tbc-gateway' ),
	],
	'save_card_label'         => [
		'title'   => __( 'Save Card Label', 'tbc-gateway' ),
		'type'    => 'text',
		'default' => __( 'Save to account', 'tbc-gateway' ),
	],
	'ok_slug'                 => [
		'title'       => __( 'Ok', 'tbc-gateway' ),
		'type'        => 'text',
		'default'     => __( 'tbc/ok', 'tbc-gateway' ),
		'description' => __( 'Only change this if you know what you are doing!', 'tbc-gateway' ),
	],
	'fail_slug'               => [
		'title'       => __( 'Fail', 'tbc-gateway' ),
		'type'        => 'text',
		'default'     => __( 'tbc/fail', 'tbc-gateway' ),
		'description' => __( 'Only change this if you know what you are doing!', 'tbc-gateway' ),
	],
	'merchant_accounts'       => [
		'type' => 'merchant_accounts',
	],
];
