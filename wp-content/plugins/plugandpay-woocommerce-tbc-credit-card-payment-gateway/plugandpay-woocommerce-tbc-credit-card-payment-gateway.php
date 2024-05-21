<?php
/**
 * Plugin Name: WooCommerce TBC Credit Card Payment Gateway
 * Plugin URI:  https://plugandpay.ge/product/woocommerce-tbc-credit-card-payment-gateway/
 * Description: Accept Visa/Mastercard payments in your WooCommerce shop using TBC (UFC) or TBC (Checkout) gateways.
 * Version:     4.1.0
 * Author:      Plug and Pay Ltd.
 * Author URI:  http://plugandpay.ge/
 * License:     https://plugandpay.ge/eula
 * Domain Path: /languages
 * Text Domain: tbc-gateway
 * WC requires at least: 3.0.0
 * WC tested up to: 5.6
 *
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

// Composer autoload.
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Init plugin.
 *
 * @param string $file Must be __FILE__ from the root plugin file.
 * @param string $software_version Current software version of this plugin.
 *                                 Starts at version 1.0.0 and uses SemVer - https://semver.org
 */
PlugandPay_WC_TBC_Credit_Card_Plugin_Factory::instance( __FILE__, '4.1.0' );
